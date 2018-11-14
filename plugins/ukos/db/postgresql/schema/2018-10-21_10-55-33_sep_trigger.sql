BEGIN;

	ALTER TABLE ukos_okstra.strassenelementpunkt ALTER station DROP NOT NULL;

	INSERT INTO ukos_okstra.strassenelementpunkt (id, auf_strassenelement, station, abstand_zur_bestandsachse) VALUES ('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', 0, 0);

	-- DROP FUNCTION ukos_okstra.validate_strassenelementpunkt();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_strassenelementpunkt()
	RETURNS trigger AS
	$BODY$
		DECLARE
			rec RECORD;
			tolerance NUMERIC;
			accuracy NUMERIC;
			decimals INTEGER;
			anzahl_strassenelemente INTEGER;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Koordinatengenauigkeit' INTO accuracy;
			decimals = CASE WHEN log(1 / accuracy) < 0 THEN 0 ELSE round(log(1 / accuracy))::INTEGER END;
			--------------------------------------------------------------------------------------------------------
			-- Exceptions

			-- Prüfe ob eine Zuordnung zum Strassenelement existiert
			IF NEW.auf_strassenelement IS NULL THEN
				RAISE EXCEPTION 'Der Strassenelementpunkt muss einem Strassenelement zugeordnet sein!';
			END IF;

			-- Prüfe ob das angegebene Strassenelement existiert
			EXECUTE '
				SELECT count(id)
				FROM ukos_okstra.strassenelement
				WHERE
					id = $1
			'
			USING NEW.auf_strassenelement
			INTO anzahl_strassenelemente;

			IF anzahl_strassenelemente = 0 THEN
				RAISE EXCEPTION 'Das Strassenelement existiert nicht!';
			END IF;

			-- Prüfe ob Punktgeometrie und/oder Station im Strassenelementpunkt angegeben sind
			-- und berechne jeweils fehlendes Element und fange Linie wenn Punkt in tolerance zu Linie liegt
			IF NEW.punktgeometrie IS NULL THEN
				IF NEW.station IS NULL THEN
					RAISE EXCEPTION 'Punktgeometrie und Station sind leer. Ein Strassenelementpunkt muss entweder eine Punktgeometrie oder eine Stationierungsangabe haben!';
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
					RAISE NOTICE 'Korrigiere Punktgeometrie auf: %', ST_AsText(NEW.punktgeometrie);
				END IF;
			ELSE
				RAISE NOTICE 'Punktgeometrie in Strassenelementpunkt vorhanden.';
				IF NEW.station IS NULL OR NEW.abstand_zur_bestandsachse IS NULL THEN
					RAISE NOTICE 'Berechne Station und Abstand zur Bestandsachse aus Punktgeometrie: %', ST_AsText(NEW.punktgeometrie);
					EXECUTE '
					SELECT
						foot_point, ordinate, abscissa
					FROM
						gdi_LineLocatePointWithOffset(
							(
								SELECT
									liniengeometrie
								FROM
									ukos_okstra.strassenelement
								WHERE
									id = $2
							),
							$1
						) AS (
							foot_point GEOMETRY,
							ordinate NUMERIC,
							abscissa NUMERIC
						)
					'
					USING NEW.punktgeometrie, NEW.auf_strassenelement
					INTO rec;

					NEW.station = rec.ordinate;
					RAISE NOTICE 'Station auf: % gesetzt.', NEW.station;
					IF abs(rec.abscissa) <= tolerance THEN
						NEW.abstand_zur_bestandsachse = 0;
						NEW.punktgeometrie = rec.foot_point;
						RAISE NOTICE 'Abstand zur Bestandsachse auf 0 gesetzt und Punktgeometrie auf: %', ST_AsText(NEW.punktgeometrie);
					ELSE
						NEW.abstand_zur_bestandsachse = rec.abscissa;
						RAISE NOTICE 'Abstand zur Bestandsachse mit Toleranz: % auf: % gesetzt.', tolerance, NEW.abstand_zur_bestandsachse;
					END IF;
				END IF;
			END IF;

			-- ToDo:
			-- Was machen mit Strassenelementpunkten, die negative Station oder Station länger als Strassenelement haben? Zulassen?

			NEW.punktgeometrie = ST_SnapToGrid(NEW.punktgeometrie, accuracy);
			NEW.station = round(NEW.station, decimals);
			NEW.abstand_zur_bestandsachse = round(NEW.abstand_zur_bestandsachse, decimals);

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	/*
		INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
('8106924c-5e05-49f9-a07c-fd84f463074f', 70.71, 10)
		INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
('8106924c-5e05-49f9-a07c-fd84f463074f', 80, 2)	
		INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
('8106924c-5e05-49f9-a07c-fd84f463074f', 80, -4)
		INSERT INTO ukos_okstra.strassenelementpunkt (auf_strassenelement, station, abstand_zur_bestandsachse) VALUES
('8106924c-5e05-49f9-a07c-fd84f463074f', 20, 3)

	SELECT id, st_astext(liniengeometrie) FROM ukos_okstra.strassenelement WHERE id = '8106924c-5e05-49f9-a07c-fd84f463074f'
	SELECT id, st_AsText(punktgeometrie, station, abstand_von_bestandsachse) FROM ukos_okstra.strassenelementpunkt WHERE auf_strassenelement = '8106924c-5e05-49f9-a07c-fd84f463074f'
	INSERT INTO ukos_okstra.verbindungspunkt (punktgeometrie) VALUES (ST_GeomFromText('POINT(500049.9995 6000049.9995)', 25833))
	*/

	-- DROP FUNCTION ukos_okstra.check_abhaengigkeiten_strassenelement_punkt();
	CREATE OR REPLACE FUNCTION ukos_okstra.check_abhaengigkeiten_strassenelementpunkt()
	RETURNS trigger AS
	$BODY$
		DECLARE
			anzahl_te INTEGER;
			anzahl_sap INTEGER;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Frage Abhängigkeiten von noch lebenden Teilelementen ab
			EXECUTE '
				SELECT
					count(*)
				FROM
				  ukos_okstra.teilelement te
				WHERE
					gueltig_bis > $1 AND
					(
						beginnt_bei_strassenelempkt = $2 OR
						endet_bei_strassenelempkt = $2
					)
			'
			USING aenderungszeit, OLD.id
			INTO anzahl_te;
			IF anzahl_te > 0 THEN
				RETURN NULL; -- Abbruch des Löschvorgangs der Strassenelemente
			END IF;
			--------------------------------------------------------------------------------------------------------
			-- Frage Abhängigkeiten von noch lebenden Strassenausstattungspunkten ab
			EXECUTE '
				SELECT
					count(*)
				FROM
					ukos_okstra.strassenausstattung_punkt
				WHERE
					gueltig_bis > $1 AND
					bei_strassenelementpunkt_id = $2
			'
			USING aenderungszeit, OLD.id
			INTO anzahl_sap;
			IF anzahl_sap > 0 THEN
				RETURN NULL; -- Abbruch des Löschvorgangs des Strassenelementes
			END IF;

		RETURN OLD; -- Weiter mit Löschvorgang des Strassenelementes
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;
	COMMENT ON FUNCTION ukos_okstra.check_abhaengigkeiten_strassenelementpunkt() IS 'Objekte, die diese Funktion über Trigger auslösen müssen vor dem Start dieser Funktion schon untergegangen sein (guelig_bis <= aenderungszeit).'

	-- strassenelementpunkt Trigger
	-- INSERT Trigger on strassenelementpunkt
	CREATE TRIGGER tr_before_insert_validate_strassenelementpunkt
	BEFORE INSERT
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strassenelementpunkt();

	-- UPDATE Trigger on strassenelementpunkt
	CREATE TRIGGER tr_before_update_validate_strassenelementpunkt
	BEFORE UPDATE
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strassenelementpunkt();

	-- DELETE Trigger on strassenelementpunkt
	CREATE TRIGGER tr_before_delete_10_check_abhaengigkeiten
	BEFORE DELETE
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.check_abhaengigkeiten_strassenelementpunkt();

	CREATE TRIGGER tr_before_delete_20_untergang
	BEFORE DELETE
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_okstra.strassenelementpunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	--
	CREATE TRIGGER tr_before_delete_20_untergang
	BEFORE DELETE
	ON ukos_doppik.ampel
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_doppik.ampel
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang
	BEFORE DELETE
	ON ukos_doppik.hydrant
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_doppik.hydrant
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	CREATE TRIGGER tr_before_delete_20_untergang
	BEFORE DELETE
	ON ukos_doppik.haltestelle
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_doppik.haltestelle
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

COMMIT;