BEGIN;

ALTER TABLE ukos_okstra.querschnittstreifen ADD COLUMN sum_se INTEGER NOT NULL DEFAULT 0;
ALTER TABLE ukos_okstra.querschnittstreifen ADD COLUMN sum_sep INTEGER NOT NULL DEFAULT 0;

	CREATE OR REPLACE FUNCTION ukos_okstra.validate_querschnittstreifen()
		RETURNS trigger
		LANGUAGE 'plpgsql'
		COST 100
		VOLATILE NOT LEAKPROOF 
	AS $BODY$
		DECLARE
			sql text;
			sum RECORD;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN

			RAISE NOTICE 'Validate Querschnittstreifen.';
			IF NEW.in_verkehrsflaeche IS NULL THEN
				RAISE EXCEPTION 'in_verkehrsflaeche ist leer! Es muss angegeben werden in welcher Verkehrsfläche der Querschnittstreifen liegt.';
			END IF;

			IF NEW.flaechengeometrie IS NULL THEN
				IF NEW.id_strassenelement IS NULL THEN
					RAISE EXCEPTION 'id_strassenelement ist leer. Wenn keine Geometrie übergeben wird, muss eine Strassenelement ID übergeben werden!';
				END IF;
			ELSE
				-- Extrahiere die erste Geometrie des Multipolygons
				IF ST_GeometryType(NEW.flaechengeometrie) = 'MultiPolygon' THEN
					NEW.flaechengeometrie = ST_GeometryN(NEW.flaechengeometrie, 1);
				END IF;

				-- Weise Anfrage zurück, wenn flaechengeometrie mehr als 2 Schnittpunkte mit Strassenelementen hat
				-- und wenn mehr als ein Strassenelment geschnitten werden
				sql = '
					SELECT
						count(id) se,
						sum(ST_NumGeometries(ST_Intersection(liniengeometrie, ST_ExteriorRing(''' || NEW.flaechengeometrie::text || ''')))) sep
					FROM
						ukos_okstra.strassenelement
					WHERE
						gueltig_bis > ' || quote_literal(aenderungszeit) || ' AND
						ST_Intersects(liniengeometrie, ST_ExteriorRing(''' || NEW.flaechengeometrie::text || '''))
				';
				RAISE NOTICE 'SQL: %', sql;
				EXECUTE sql
				INTO sum;

				IF sum.se IS NULL THEN
					NEW.sum_se = 0;
				ELSE
					NEW.sum_se = sum.se;
				END IF;
				IF sum.sep IS NULL THEN
					NEW.sum_sep = 0;
				ELSE
					NEW.sum_sep = sum.sep;
				END IF;

				IF sum.se = 1 AND sum.sep > 2 THEN
					RAISE EXCEPTION 'Die Flächengeometrie des Querschnittstreifen schneidet sich in mehr als 2 Strassenelementpunkten! Eine Berechnung des Anfangs- und Endpunktes ist damit nicht eindeutig möchlich.';
					RETURN NULL;
				END IF;

			END IF;

			RETURN NEW;
		END;
	$BODY$;

	CREATE OR REPLACE FUNCTION ukos_okstra.add_teilelement_von_flaeche()
			RETURNS trigger
			LANGUAGE 'plpgsql'
			COST 100
			VOLATILE NOT LEAKPROOF 
	AS $BODY$
		DECLARE
			schnitt RECORD;
			se RECORD;
			se_id CHARACTER VARYING;
			sep RECORD;
			num_sep INTEGER;
			station NUMERIC;
			geschnitten BOOLEAN = false;
			te_id CHARACTER VARYING;
			beginnt_bei_strassenelempkt CHARACTER VARYING;
			endet_bei_strassenelempkt CHARACTER VARYING;
			sql text;
			strassenelementsuchabstand integer;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN

		-- Initialisieren
		EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Strassenelementsuchabstand' INTO strassenelementsuchabstand;

			--------------------------------------------------------------------------------------------------------
			IF NEW.flaechengeometrie IS NULL THEN
				-- Es ist keine Flächengeometrie übergeben worden
				-- das ist erlaubt, da diese nachträglich über den view querschnitttrapeze eingegeben werden kann
				-- in dem Fall muss hier aber die Strassenelement_id übergeben werden, damit man später weiss
				-- mit welchem Strassenelement die Trapeze gerechnet werden sollen.
				-- setze die Strassenelement_id
				se_id = NEW.id_strassenelement;
			ELSE
				IF NEW.sum_se = 1 AND NEW.sum_sep = 2 THEN
					-- Es ist eine Flaechengeometrie übergeben worden, die sich in 2 Punkten mit einem SE schneidet
					-- verschneide die flaechengeometrie mit Strassenelementen und finde Strassenelementpunkt am Beginn und Ende
					FOR schnitt IN EXECUTE '
						SELECT
							id auf_strassenelement,
							(p).geom punktgeometrie
						FROM
							(
								SELECT
									id,
									ST_DumpPoints(ST_Intersection(liniengeometrie, ST_ExteriorRing($1))) p
								FROM
									ukos_okstra.strassenelement
								WHERE
									gueltig_bis > $2 AND
									ST_Intersects(liniengeometrie, ST_ExteriorRing($1))
							)	ptab
						'
					USING NEW.flaechengeometrie, aenderungszeit
					LOOP
						EXECUTE FORMAT('
							INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement, angelegt_von, stelle_id) VALUES
							(%1$L, %2$L, %3$L, %4$s)
							RETURNING id, station
						', schnitt.punktgeometrie, schnitt.auf_strassenelement, NEW.angelegt_von, NEW.stelle_id)
						INTO sep;
						IF station IS NULL THEN
							-- Beim ersten Schnittpunkt merke die Station und id
							station = sep.station;
							beginnt_bei_strassenelempkt = sep.id;
						ELSE
							-- Beim zweiten Schnittpunkt
							IF sep.station > station THEN
								-- Station des zweiten ist größer als beim ersten Punkt, also ist dieser der Endpunkt
								endet_bei_strassenelempkt = sep.id;
							ELSE
								-- Station des zweiten ist kleiner als beim ersten Punkt, also ist dieser der Anfangspunkt
								endet_bei_strassenelempkt = beginnt_bei_strassenelempkt;
								beginnt_bei_strassenelempkt = sep.id;
							END IF;
							se_id = schnitt.auf_strassenelement;
						END IF;
						geschnitten = true;
					END LOOP;
				END IF;

				IF NOT geschnitten THEN
					-- Die Fläche schneidet sich in keinem oder mehreren Strassenelementen oder nur in einem SEP
					-- Finde das Strassenlement über die dichtesten Entfernungen
					RAISE NOTICE 'Suche dichtestes Strassenelement an der Fläche %', NEW.flaechengeometrie;
					sql = '
						SELECT
							se.id,
							se.beginnt_bei_vp,
							se.endet_bei_vp,
							vp_beginnt.punktgeometrie AS beginnt_bei_vp_geom,
							vp_endet.punktgeometrie AS endet_bei_vp_geom,
							avg(ST_Distance((points.dump).geom, se.liniengeometrie)) dist
						FROM
							ukos_okstra.strassenelement se JOIN
							ukos_okstra.verbindungspunkt vp_beginnt ON (se.beginnt_bei_vp = vp_beginnt.id) JOIN
							ukos_okstra.verbindungspunkt vp_endet ON (se.endet_bei_vp = vp_endet.id),
							(
								SELECT ST_DumpPoints($2) dump
							) points
						WHERE
							se.gueltig_bis > $1 AND
							se.liniengeometrie && ST_Expand(ST_Envelope($2), $3)
						GROUP BY
							se.id, se.beginnt_bei_vp, se.endet_bei_vp, vp_beginnt.punktgeometrie, vp_endet.punktgeometrie
						ORDER BY dist
						LIMIT 1
					';
					EXECUTE sql
					USING aenderungszeit, NEW.flaechengeometrie, strassenelementsuchabstand
					INTO se;

					IF se.id IS NULL THEN
						RAISE EXCEPTION 'Es konnte kein Strassenelement im Abstand von % m zum Querschnittstreifen gefunden werden. Erhöhen Sie ggf. den Parameter Strassenelementsuchabstand!', strassenelementsuchabstand;
					ELSE
						-- setze Strassenelementpunkte an beginnt_ und endet_bei_vp des Strassenelementes um ein Teilelement über das gesamte Strassenelement bilden zu können.
						-- falls sie noch nicht existieren
						-- suche Strassenelementpunkt am Anfang des Strassenelementes
						EXECUTE '
							SELECT
								sep.id
							FROM
								ukos_okstra.strassenelement se JOIN
								ukos_okstra.strassenelementpunkt sep ON (se.id = sep.auf_strassenelement)
							WHERE
								se.gueltig_bis > $1 AND
								sep.gueltig_bis > $1 AND
								(
									se.beginnt_bei_vp = $2 OR
									se.endet_bei_vp = $2
								) AND
								ST_Equals(punktgeometrie, $3)
							LIMIT 1
						'
						USING aenderungszeit, se.beginnt_bei_vp, se.beginnt_bei_vp_geom
						INTO sep;

						IF sep.id IS NULL THEN
							RAISE NOTICE 'Kein Straßenelementpunkt am Anfang des Strassenelementes gefunden. Lege neuen an.';
							EXECUTE FORMAT('
								INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement, angelegt_von, stelle_id) VALUES
								(%1$L, %2$L, %3$L, %4$s)
								RETURNING id, station
							', se.beginnt_bei_vp_geom, se.id, NEW.angelegt_von, NEW.stelle_id)
							INTO sep;
						ELSE
							RAISE NOTICE 'Straßenelementpunkt: % am Anfang des Strassenelementes gefunden', sep.id;
						END IF;
						beginnt_bei_strassenelempkt = sep.id;

						-- suche Strassenelementpunkt am Ende des Strassenelementes
						EXECUTE '
							SELECT
								sep.id
							FROM
								ukos_okstra.strassenelement se JOIN
								ukos_okstra.strassenelementpunkt sep ON (se.id = sep.auf_strassenelement)
							WHERE
								se.gueltig_bis > $1 AND
								sep.gueltig_bis > $1 AND
								(
									se.beginnt_bei_vp = $2 OR
									se.endet_bei_vp = $2
								) AND
								ST_Equals(punktgeometrie, $3)
							LIMIT 1
						'
						USING aenderungszeit, se.endet_bei_vp, se.endet_bei_vp_geom
						INTO sep;

						IF sep.id IS NULL THEN
							RAISE NOTICE 'Kein Straßenelementpunkt am Ende des Strassenelementes gefunden. Lege neuen an.';
							EXECUTE FORMAT('
								INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement, angelegt_von, stelle_id) VALUES
								(%1$L, %2$L, %3$L, %4$s)
								RETURNING id, station
							', se.endet_bei_vp_geom, se.id, NEW.angelegt_von, NEW.stelle_id)
							INTO sep;
						ELSE
							RAISE NOTICE 'Straßenelementpunkt: % am Ende des Strassenelementes gefunden', sep.id;
						END IF;
						endet_bei_strassenelempkt = sep.id;

					END IF; -- end of se an flaeche gefunden
					se_id = se.id;
				END IF; -- end of is not geschnitten

				-- erzeuge das Teilelement mit den gefundenen Strassenlementpunkten falls es noch nicht existiert
				EXECUTE '
					SELECT
						id
					FROM
						ukos_okstra.teilelement
					WHERE
						gueltig_bis > $1 AND
						beginnt_bei_strassenelempkt = $2 AND
						endet_bei_strassenelempkt = $3 AND
						auf_strassenelement = $4
				'
				USING aenderungszeit, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, se_id
				INTO te_id;

				IF te_id IS NULL THEN
					EXECUTE '
						INSERT INTO ukos_okstra.teilelement (
								erfassungsdatum,
								angelegt_von,
								beginnt_bei_strassenelempkt,
								endet_bei_strassenelempkt,
								auf_strassenelement,
								stelle_id
							)
						VALUES
							($1, $2, $3, $4, $5, $6)
						RETURNING id
					'
					USING aenderungszeit, NEW.angelegt_von, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, se_id, NEW.stelle_id
					INTO te_id;
				END IF;

				-- Zuordnung Streckenobjekt Querschnittstreifen zu Teilelement erzeugen wenn noch nicht exisitert
				EXECUTE '
					INSERT INTO ukos_okstra.streckenobjekt_to_teilelement (streckenobjekt_id, teilelement_id)
					SELECT $1, $2
					WHERE
						NOT EXISTS (
							SELECT
								streckenobjekt_id, teilelement_id
							FROM
								ukos_okstra.streckenobjekt_to_teilelement
							WHERE
								streckenobjekt_id = $1 AND
								teilelement_id = $2
						)
					RETURNING teilelement_id
				'
				USING NEW.id, te_id
				INTO te_id;

				IF te_id IS NULL THEN
					RAISE NOTICE 'Zuordnung zwischen Querschnittstreifen: % und Teilelement: % schon vorhanden.', NEW.id, te_id;
				ELSE
					RAISE NOTICE 'Zuordnung zwischen Querschnittstreifen: % und Teilelement: % erzeugt.', NEW.id, te_id;
				END IF;

			END IF; -- end of flaechengeometrie is not null

			-- Aggregiere die Flächengeometrien aller Querschnittstreifen die zur Verkehrsfläche gehören und
			-- trage diese in der verkehrsfläche ein und
			-- berechne den Flächeninhalt neu und
			-- setze die strassenelement_id
			EXECUTE '
				UPDATE
					ukos_okstra.verkehrsflaeche
				SET
					flaeche = (
						SELECT
							ST_Multi(ST_Union(flaechengeometrie))
						FROM
							ukos_okstra.querschnittstreifen
						WHERE
							in_verkehrsflaeche = $1 AND
							gueltig_bis > $3
					),
					flaecheninhalt = (
						SELECT
							ST_Area(ST_Union(flaechengeometrie))
						FROM
							ukos_okstra.querschnittstreifen
						WHERE
							in_verkehrsflaeche = $1 AND
							gueltig_bis > $3
					),
					id_strassenelement = $2
				WHERE
					id = $1
			'
			USING NEW.in_verkehrsflaeche, se_id, aenderungszeit;

			RETURN NEW;
		END;
	$BODY$;

COMMIT;
