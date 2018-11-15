BEGIN;

	--DROP TRIGGER tr_after_insert_add_teilelement_von_trapez ON ukos_okstra.querschnitttrapeze;
	-- DROP VIEW ukos_okstra.querschnitttrapeze;
	CREATE OR REPLACE View ukos_okstra.querschnitttrapeze AS
	SELECT
		q.oid,
		q.in_verkehrsflaeche,
		q.id AS querschnittstreifen_id,
		q.ident AS querschnittstreifen_ident,
		q.flaechengeometrie,
		q.gueltig_bis,
		t.id as teilelement_id,
		se.id AS strassenelement_id,
		se.id AS strassenelement_ident,
		t.beginnt_bei_strassenelempkt,
		t.endet_bei_strassenelempkt,
		bsep.station AS beginnt_bei_station,
	  q.x_wert_von_station_links,
		q.x_wert_von_station_rechts,
		esep.station AS endet_bei_station,
		q.x_wert_bis_station_links,
		q.x_wert_bis_station_rechts
	FROM
	  ukos_okstra.querschnittstreifen q JOIN
	  ukos_okstra.streckenobjekt_to_teilelement s2t ON (q.id = s2t.streckenobjekt_id) JOIN
	  ukos_okstra.teilelement t ON (s2t.teilelement_id = t.id) JOIN
	  ukos_okstra.strassenelementpunkt bsep ON (t.beginnt_bei_strassenelempkt = bsep.id) JOIN
	  ukos_okstra.strassenelementpunkt esep ON (t.endet_bei_strassenelempkt = esep.id) JOIN
		ukos_okstra.strassenelement se ON (bsep.auf_strassenelement = se.id)
	WHERE
		q.x_wert_von_station_links IS NOT NULL AND
		q.x_wert_von_station_rechts IS NOT NULL AND
		q.x_wert_bis_station_links IS NOT NULL AND
		q.x_wert_bis_station_rechts IS NOT NULL		
	;
	COMMENT ON view ukos_okstra.querschnitttrapeze IS 'Dient der Abbildung von Trapezen, die als Querschnittssteifen eingetragen und Fahrbahnen oder anderen Streckenobjekten zugeordnet werden können. Zur Erfassung von Flächen der Streckenobjekte durch Stationierung und Querprofil im Trapezmodell';

	-- DROP FUNCTION ukos_doppik.calc_flaecheninhalt();
	CREATE OR REPLACE FUNCTION ukos_doppik.calc_flaecheninhalt()
	RETURNS trigger AS
	$BODY$
		DECLARE

		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Wenn die Verkehrsflaeche eine Fläche hat,
			IF NEW.flaeche IS NOT NULL THEN
				NEW.flaecheninhalt = ST_Area(NEW.flaeche);
			END IF;

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.add_querschnittstreifen();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_querschnittstreifen()
	RETURNS trigger AS
	$BODY$
		DECLARE
			qs_id CHARACTER VARYING;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Wenn die Verkehrsflaeche eine Fläche hat,
			IF NEW.flaeche IS NOT NULL THEN
				-- erzeuge daraus einen Querschnittstreifen
				EXECUTE '
					INSERT INTO ukos_okstra.querschnittstreifen (angelegt_von, flaechengeometrie, angelegt_am, erfassungsdatum, in_verkehrsflaeche) VALUES
					($1, $2, $3, $4, $5)
					RETURNING id
				'
				USING NEW.angelegt_von, ST_GeometryN(NEW.flaeche, 1) , NEW.angelegt_am, NEW.angelegt_am, NEW.id
				INTO qs_id;
			ELSE
				RAISE NOTICE 'Die Verkehrsfläche hat noch keine Flächengeometrie. Digitalisieren Sie eine Fläche oder erfassen Sie Querschnitttrapeze mit Zuordnung zu einem Strassenelement!';
			END IF;

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.validate_querschnittstreifen();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_querschnittstreifen()
	RETURNS trigger AS
	$BODY$
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
				IF sum.se > 1 THEN
					RAISE EXCEPTION 'Die Flächengeometrie des Querschnittstreifen schneidet mehr als ein Strassenelement!';
					RETURN NULL;
				END IF;

				IF sum.sep > 2 THEN
					RAISE EXCEPTION 'Die Flächengeometrie des Querschnittstreifen schneidet sich in mehr als 2 Strassenelementpunkten!';
					RETURN NULL;
				END IF;

			END IF;

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.add_teilelement_von_flaeche();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_teilelement_von_flaeche()
	RETURNS trigger AS
	$BODY$
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
				-- Es ist eine Flaechengeometrie übergeben worden, die sich in 2 oder keinem Punkt schneidet
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
					EXECUTE '
						INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement) VALUES
						($1, $2)
						RETURNING id, station
					'
					USING schnitt.punktgeometrie, schnitt.auf_strassenelement
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

				IF NOT geschnitten THEN
					-- Die Fläche schneidet sich nicht mit einem Strassenelement
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
							EXECUTE '
								INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement) VALUES
								($1, $2)
								RETURNING id, station
							'
							USING se.beginnt_bei_vp_geom, se.id
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
							EXECUTE '
								INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement) VALUES
								($1, $2)
								RETURNING id, station
							'
							USING se.endet_bei_vp_geom, se.id
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
						INSERT INTO ukos_okstra.teilelement (erfassungsdatum, angelegt_von, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, auf_strassenelement) VALUES
						($1, $2, $3, $4, $5)
						RETURNING id
					'
					USING aenderungszeit, NEW.angelegt_von, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, se_id
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
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.add_teilelement_von_trapez();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_teilelement_von_trapez()
	RETURNS trigger AS
	$BODY$
		DECLARE
			se_liniengeometrie geometry;
			sep_id CHARACTER VARYING;
			te_id CHARACTER VARYING;
			qs_id CHARACTER VARYING;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
			beginnt_bei_strassenelempkt CHARACTER VARYING;
			endet_bei_strassenelempkt CHARACTER VARYING;
			accuracy NUMERIC;
			decimals INTEGER;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Koordinatengenauigkeit' INTO accuracy;

			decimals = CASE WHEN log(1 / accuracy) < 0 THEN 0 ELSE round(log(1 / accuracy))::INTEGER END;

			IF NEW.in_verkehrsflaeche IS NULL THEN
				RAISE EXCEPTION 'in_verkehrsflaeche ist leer! Es muss angegeben werden zu welcher Verkehrsfläche der Querschnittstreifen-Trapez gehören werden soll.';
			END IF;

			IF NEW.strassenelement_id IS NULL THEN
				RAISE EXCEPTION 'strassenelement_id ist leer! Es muss angegeben werden über welchem Strassenelement das Trapez berechnet werden soll.';
			END IF;

			IF NEW.beginnt_bei_station IS NULL THEN
				RAISE EXCEPTION 'beginnt_bei_station ist leer!';
			END IF;

			IF NEW.x_wert_von_station_links IS NULL THEN
				RAISE EXCEPTION 'x_wert_von_station_links ist leer!';
			END IF;

			IF NEW.x_wert_von_station_rechts IS NULL THEN
				RAISE EXCEPTION 'x_wert_von_station_rechts ist leer!';
			END IF;

			IF NEW.endet_bei_station IS NULL THEN
				RAISE EXCEPTION 'endet_bei_station ist leer!';
			END IF;

			IF NEW.x_wert_bis_station_links IS NULL THEN
				RAISE EXCEPTION 'x_wert_bis_station_links ist leer!';
			END IF;

			IF NEW.x_wert_bis_station_rechts IS NULL THEN
				RAISE EXCEPTION 'x_wert_bis_station_rechts ist leer!';
			END IF;

			-- Runde die angegebenen numerischen Werte
			NEW.beginnt_bei_station = round(NEW.beginnt_bei_station, decimals);
			NEW.x_wert_von_station_links = round(NEW.x_wert_von_station_links, decimals);
			NEW.x_wert_von_station_rechts = round(NEW.x_wert_von_station_rechts, decimals);
			NEW.endet_bei_station = round(NEW.endet_bei_station, decimals);
			NEW.x_wert_bis_station_links = round(NEW.x_wert_bis_station_links, decimals);
			NEW.x_wert_bis_station_rechts = round(NEW.x_wert_bis_station_rechts, decimals);

			-- Frage die Liniengeometrie des Strassenelementes ab
			EXECUTE '
				SELECT liniengeometrie
				FROM ukos_okstra.strassenelement
				WHERE id = $1
			'
			USING NEW.strassenelement_id
			INTO se_liniengeometrie;


			--------------------------------------------------------------------------------------------------------
			/*
			-- Strassenelementpunkt am Beginn finden oder erzeugen
			EXECUTE '
				SELECT
					id AS sep_id
				FROM
					ukos_okstra.strassenelementpunkt
				WHERE
					station = $1 AND
					abstand_zur_bestandsachse = 0 AND
					auf_strassenelement = $2 AND
					gueltig_bis > $3
			'
			USING NEW.beginnt_bei_station, NEW.strassenelement_id, aenderungszeit
			INTO beginnt_bei_strassenelempkt;

			IF beginnt_bei_strassenelempkt IS NULL THEN
				EXECUTE '
					INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
					($1, $2, 0)
					RETURNING id
				'
				USING NEW.strassenelement_id, NEW.beginnt_bei_station
				INTO beginnt_bei_strassenelempkt;
				RAISE NOTICE 'Straßenelementpunkt: % am Anfang an Station: % erzeugt.', beginnt_bei_strassenelempkt, NEW.beginnt_bei_station;
			ELSE
				RAISE NOTICE 'Straßenelementpunkt: % am Anfang an Station: % gefunden.', beginnt_bei_strassenelempkt, NEW.beginnt_bei_station;
			END IF;

			-- Strassenelementpunkt am Ende finden oder erzeugen
			EXECUTE '
				SELECT
					id AS sep_id
				FROM
					ukos_okstra.strassenelementpunkt
				WHERE
					station = $1 AND
					abstand_zur_bestandsachse = 0 AND
					auf_strassenelement = $2 AND
					gueltig_bis > $3
			'
			USING NEW.endet_bei_station, NEW.strassenelement_id, aenderungszeit
			INTO endet_bei_strassenelempkt;

			IF endet_bei_strassenelempkt IS NULL THEN
				EXECUTE '
					INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
					($1, $2, 0)
					RETURNING id
				'
				USING NEW.strassenelement_id, NEW.endet_bei_station
				INTO endet_bei_strassenelempkt;
				RAISE NOTICE 'Straßenelementpunkt: % am Ende an Station: % erzeugt.', endet_bei_strassenelempkt, NEW.endet_bei_station;
			ELSE
				RAISE NOTICE 'Straßenelementpunkt: % am Ende an Station: % gefunden.', endet_bei_strassenelempkt, NEW.endet_bei_station;
			END IF;

			-- Teilelement finden oder erzeugen
			EXECUTE '
				SELECT
					id AS te_id
				FROM
					ukos_okstra.teilelement
				WHERE
					beginnt_bei_strassenelempkt = $1 AND
					endet_bei_strassenelempkt = $2 AND
					auf_strassenelement = $3 AND
					gueltig_bis > $4
			'
			USING beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, NEW.strassenelement_id, aenderungszeit
			INTO te_id;

			IF te_id IS NULL THEN
				EXECUTE '
					INSERT INTO ukos_okstra.teilelement (beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, auf_strassenelement) VALUES
					($1, $2, $3)
					RETURNING id
				'
				USING beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, NEW.strassenelement_id, aenderungszeit
				INTO te_id;
				RAISE NOTICE 'Teilelement: % zwischen Strassenelementpunkt: % und % auf Strassenelement: % erzeugt.', te_id, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, NEW.strassenelement_id;
			ELSE
				RAISE NOTICE 'Teilelement: % zwischen Strassenelementpunkt: % und % auf Strassenelement: % gefunden.', te_id, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt, NEW.strassenelement_id;
			END IF;
*/
			-- Querschnittstreifen finden oder erzeugen
			EXECUTE '
				SELECT
					id AS qs_id
				FROM
					ukos_okstra.querschnittstreifen
				WHERE
					x_wert_von_station_links = $2 AND
					x_wert_von_station_rechts = $3 AND
					x_wert_bis_station_links = $4 AND
					x_wert_bis_station_rechts = $5 AND
					in_verkehrsflaeche = $6 AND
					gueltig_bis > $1
			'
			USING aenderungszeit, NEW.x_wert_von_station_links, NEW.x_wert_von_station_rechts, NEW.x_wert_bis_station_links, NEW.x_wert_bis_station_rechts, NEW.in_verkehrsflaeche
			INTO qs_id;

			IF qs_id IS NULL THEN
				RAISE NOTICE 'Erzeuge Querschnittstreifen als Trapez für Verkehrsfläche: % auf Strassenelement: %', NEW.in_verkehrsflaeche, NEW.strassenelement_id;
				EXECUTE '
					INSERT INTO ukos_okstra.querschnittstreifen (
						x_wert_von_station_links, x_wert_von_station_rechts, x_wert_bis_station_links, x_wert_bis_station_rechts,
						in_verkehrsflaeche,
						flaechengeometrie,
						angelegt_am,
						erfassungsdatum
					)
					VALUES (
						$2, $3, $5, $6,
						$7,
						(
							SELECT
								ST_SetSrid(
									ST_MakePolygon(
										ST_MakeLine(
											Array[
												gdi_LineInterpolatePointWithOffset($8, $1, $3),
												gdi_LineInterpolatePointWithOffset($8, $4, $6),
												gdi_LineInterpolatePointWithOffset($8, $4, -1 * $5),
												gdi_LineInterpolatePointWithOffset($8, $1, -1 * $2),
												gdi_LineInterpolatePointWithOffset($8, $1, $3)
											]
										)
									),
									25833
								)
						),
						$9,
						$9
					)
					RETURNING id
				'
				USING
					NEW.beginnt_bei_station,
					NEW.x_wert_von_station_links,
					NEW.x_wert_von_station_rechts,
					NEW.endet_bei_station,
					NEW.x_wert_bis_station_links,
					NEW.x_wert_bis_station_rechts,
					NEW.in_verkehrsflaeche,
					se_liniengeometrie,
					aenderungszeit
				INTO qs_id;
				RAISE NOTICE 'Querschnittstreifen: % für Verkehrsfläche: % erzeugt.', qs_id, NEW.in_verkehrsflaeche;
			ELSE
				RAISE NOTICE 'Querschnittstreifen: % für Verkehrsfläche: % gefunden.', qs_id, NEW.in_verkehrsflaeche;
			END IF;
			/*
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
			USING qs_id, te_id
			INTO te_id;

			IF te_id IS NULL THEN
				RAISE NOTICE 'Zuordnung zwischen Querschnittstreifen: % und Teilelement: % schon vorhanden.', qs_id, te_id;
			ELSE
				RAISE NOTICE 'Zuordnung zwischen Querschnittstreifen: % und Teilelement: % erzeugt.', qs_id, te_id;
			END IF;
*/
			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

/*
INSERT INTO ukos_okstra.querschnitttrapeze (
	in_verkehrsflaeche,
	beginnt_bei_station,
  x_wert_von_station_links,
  x_wert_von_station_rechts,
  endet_bei_station,
  x_wert_bis_station_links,
  x_wert_bis_station_rechts
) VALUES (
	'vf_id', 10, 5, 6, 20, 5, 7
)
	*/


	-- DROP FUNCTION ukos_okstra.delete_querschnittstreifen();
	CREATE OR REPLACE FUNCTION ukos_okstra.delete_querschnittstreifen()
	RETURNS trigger AS
	$BODY$
		DECLARE
			qs RECORD;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Frage Querschnittstreifen der Verkehrsflaeche ab
			FOR qs IN EXECUTE '
				SELECT
					id
				FROM
					ukos_okstra.querschnittstreifen
				WHERE
					in_verkehrsflaeche = $1
				'
			USING OLD.id
			LOOP
				EXECUTE '
					DELETE FROM ukos_okstra.querschnittstreifen WHERE id = $1
				'
				USING qs.id;
			END LOOP;

			RETURN OLD;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- Trigger on fahrbahn
	-- INSERT Trigger
	CREATE TRIGGER tr_before_insert_calc_flaecheninhalt
	BEFORE INSERT
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

	CREATE TRIGGER tr_after_insert_add_querschnittstreifen
	AFTER INSERT
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_querschnittstreifen();

	-- DELETE Trigger
	CREATE TRIGGER tr_before_delete_10_untergang
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
  EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_20_delete_querschnittstreifen
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_querschnittstreifen();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	-- Trigger on querschnittstreifen
	-- INSERT Trigger
	CREATE TRIGGER tr_before_insert_validate_querschnittstreifen
	BEFORE INSERT
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_querschnittstreifen();

	CREATE TRIGGER tr_after_insert_add_teilelement_von_flaeche
	AFTER INSERT
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_teilelement_von_flaeche();

	-- Trigger on View querschnitttrapeze
	-- INSTED OF INSERT Trigger
	CREATE TRIGGER tr_after_insert_add_teilelement_von_trapez
	INSTEAD OF INSERT
	ON ukos_okstra.querschnitttrapeze
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_teilelement_von_trapez();

	-- DELETE Trigger
	CREATE TRIGGER tr_before_delete_10_untergang
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_20_delete_teilelemente
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_teilelemente();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

COMMIT;