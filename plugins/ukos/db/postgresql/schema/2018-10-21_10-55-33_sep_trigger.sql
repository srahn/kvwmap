BEGIN;

	-- DROP FUNCTION ukos_okstra.validate_strassenelementpunkt();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_strassenelementpunkt()
	RETURNS trigger AS
	$BODY$
		DECLARE
			rec				RECORD;
			tolerance	NUMERIC;
			accuracy	NUMERIC;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Koordinatengenauigkeit' INTO accuracy;

			--------------------------------------------------------------------------------------------------------
			-- Exceptions

			-- Prüfe ob eine Zuordnung zum Strassenelement existiert
			IF NEW.auf_strassenelement IS NULL THEN
				RAISE EXCEPTION 'Der Strassenelementpunkt muss einem Strassenelement zugeordnet sein.';
			END IF;

			-- Prüfe ob Punktgeometrie und/oder Station existieren
			-- Berechne jeweils fehlendes Element und fange Linie wenn Punkt in tolerance zu Linie liegt
			IF NEW.punktgeometrie IS NULL THEN
				IF NEW.station IS NULL THEN
					RAISE EXCEPTION 'Punktgeometrie und Station sind leer. Ein Strassenelementpunkt muss entweder eine Punktgeometrie oder eine Stationierungsangabe haben.';
				ELSE
					RAISE NOTICE 'Berechne Punktgeometrie entlang des Strassenelementes: % aus Station: % und Abstand zur Bestandsachse: %', NEW.auf_strassenelement, NEW.station, NEW.abstand_zur_bestandsachse;
					IF abs(NEW.abstand_zur_bestandsachse) <= tolerance THEN
						RAISE NOTICE 'Reduziere den Abstand zur Linie: % auf 0', NEW.auf_strassenelement;
						NEW.abstand_zur_bestandsachse = 0;
					END IF;
					EXECUTE '
						SELECT
							gdi_LineInterpolatePointWithOffset(liniengeometrie, $1, $2)
						FROM
							ukos_okstra.strassenelement
						WHERE
							id = $3
					'
					USING NEW.station, NEW.abstand_zur_bestandsachse, NEW.auf_strassenelement
					INTO NEW.punktgeometrie;
					RAISE NOTICE 'Setze Punktgeometrie auf: %', ST_AsText(NEW.punktgeometrie);
				END IF;
			ELSE
				RAISE NOTICE 'Berechne Station und Abstand zur Bestandsachse aus Punktgeometrie: %', ST_AsText(NEW.punktgeometrie);
				EXECUTE '
					SELECT
						foot_point, ordinate, abscissa
					FROM
						(
							SELECT
								gdi_LineLocatePointWithOffset(liniengeometrie, $1)
							FROM
								ukos_okstra.strassenelement
							WHERE
								id = $2
						) AS (foot_point GEOMETRY, ordinate NUMERIC, absicca NUMERIC)
				'
				USING NEW.punktgeometrie, NEW.auf_strassenelement
				INTO rec;

				NEW.station = rec.ordinate;
				RAISE NOTICE 'Station auf: % gesetzt.', NEW.station;
				IF abs(rec.abscissa) <= tolerance THEN
					NEW.abstand_zur_bestandsachse = 0;
					NEW.punktgeometrie = rec.foot_point;
					RAISE NOTICE 'Abstand zur Bestandsachse auf 0 gesetzt und Punktgeometrie auf: %', NEW.punktgeometrie;
				ELSE
					NEW.abstand_zur_bestandsachse = rec.abscissa;
					RAISE NOTICE 'Abstand zur Bestandsachse auf: % gesetzt.', NEW.abstand_zur_bestandsachse;
				END IF;
			END IF;

			NEW.punktgeometrie = ST_SnapToGrid(NEW.punktgeometrie, accuracy);

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;


	CREATE TRIGGER validate_strassenelementpunkt
	BEFORE INSERT
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strassenelementpunkt();

COMMIT;