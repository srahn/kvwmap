BEGIN;

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
			IF NEW.flaechengeometrie IS NULL THEN
				IF NEW.x_wert_von_station_links IS NULL THEN
					RAISE EXCEPTION 'x_wert_von_station_links ist leer!';
				END IF;
				IF NEW.x_wert_von_station_rechts IS NULL THEN
					RAISE EXCEPTION 'x_wert_von_station_rechts ist leer!';
				END IF;
				IF NEW.x_wert_bis_station_links IS NULL THEN
					RAISE EXCEPTION 'x_wert_bis_station_links ist leer!';
				END IF;
				IF NEW.x_wert_bis_station_rechts IS NULL THEN
					RAISE EXCEPTION 'x_wert_bis_station_rechts ist leer!';
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
				-- Es ist keine Flächengeometrie übergeben worden,
				-- ToDo:
				-- Lege Strassenelementpunkte an
				-- berechne die Flächengeometrie aus den Stationswerten
				-- füge die Geometrie zur Fläche des Verkehrsflaechen Objektes in_verkehrsflaeche hinzu

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

				-- Ordne das Teilelement dem Streckenobjekt Querschnittstreifen zu
				EXECUTE '
					INSERT INTO ukos_okstra.streckenobjekt_to_teilelement (streckenobjekt_id, teilelement_id) VALUES
					($1, $2)
				'
				USING NEW.id, te_id;

			END IF; -- end of flaechengeometrie is not null

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

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

	--

	CREATE TRIGGER calc_flaecheninhalt
	BEFORE INSERT
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_doppik.calc_flaecheninhalt();

	CREATE TRIGGER add_querschnittstreifen
	AFTER INSERT
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_querschnittstreifen();

	--

	CREATE TRIGGER validate_querschnittstreifen
	BEFORE INSERT
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_querschnittstreifen();

	CREATE TRIGGER add_teilelement_von_flaeche
	AFTER INSERT
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_teilelement_von_flaeche();

	--
	CREATE TRIGGER _10_untergang
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
  EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER _20_delete_querschnittstreifen
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_querschnittstreifen();

	CREATE TRIGGER _99_stop
	BEFORE DELETE
	ON ukos_doppik.fahrbahn
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	--
	CREATE TRIGGER _10_untergang
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER _20_delete_teilelemente
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_teilelemente();

	CREATE TRIGGER _99_stop
	BEFORE DELETE
	ON ukos_okstra.querschnittstreifen
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

COMMIT;