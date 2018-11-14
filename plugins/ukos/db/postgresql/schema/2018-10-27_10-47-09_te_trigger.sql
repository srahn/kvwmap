BEGIN;

	ALTER TABLE ukos_okstra.verkehrseinschraenkung
	ALTER art SET DEFAULT '00';

	ALTER TABLE ukos_okstra.teilelement RENAME auf_strasselement TO auf_strassenelement;
	ALTER TABLE ukos_okstra.teilelement ALTER auf_strassenelement SET DEFAULT '00000000-0000-0000-0000-000000000000';
	INSERT INTO ukos_okstra.teilelement (id, beginnt_bei_strassenelempkt, endet_bei_strassenelempkt) VALUES ('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000');

	CREATE TRIGGER __first_snap_to_grid
			BEFORE INSERT OR UPDATE OF geometrie_streckenobjekt
			ON ukos_okstra.verkehrseinschraenkung
			FOR EACH ROW
			EXECUTE PROCEDURE ukos_base.first_snap_to_grid();

	CREATE TABLE ukos_okstra.streckenobjekt_to_teilelement (
		streckenobjekt_id character varying NOT NULL,
			teilelement_id character varying NOT NULL
	)
	WITH (
			OIDS=TRUE
	);

	-- DROP FUNCTION ukos_okstra.validate_strecke();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_strecke()
	RETURNS trigger AS
	$BODY$
		DECLARE
			rec RECORD;
			tolerance NUMERIC;
			accuracy NUMERIC;
		BEGIN
			-------------------------------------------------------------------------
			-- Prüfe ob eine Streckengeometrie vorhanden ist
			IF NEW.geometrie_streckenobjekt IS NULL THEN
				RAISE EXCEPTION 'Das Streckenobjekt hat keine Liniengeometrie!';
			END IF;

			-- Wandle die gegebene MultiLinestring in eine gerichtete Liniengeometrie
			NEW.geometrie_streckenobjekt = ST_Multi(ST_LineMerge(NEW.geometrie_streckenobjekt));

			-- ToDo:
			-- Prüfe ob Anfangs und Endpunkt auf einem Strassenelement liegen
			-- Prüfe ob Streckenteile alle auf Strassenelemente liegen

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.add_teilelemente();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_teilelemente()
	RETURNS trigger AS
	$BODY$
		DECLARE
			sql text;
			vp RECORD;
			sep RECORD;
			te RECORD;
			tolerance NUMERIC;
			accuracy NUMERIC;
			liniengeometrie_streckenobjekt geometry(LineString, 25833);
			punkt_anfang geometry(Point, 25833);
			punkt_ende geometry(Point, 25833);
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN
			-------------------------------------------------------------------------
			-- Initialisieren
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
			liniengeometrie_streckenobjekt = ST_GeometryN(NEW.geometrie_streckenobjekt, 1);
			punkt_anfang = ST_StartPoint(liniengeometrie_streckenobjekt);
			punkt_ende  = ST_EndPoint(liniengeometrie_streckenobjekt);

			RAISE NOTICE 'Add Teilelemente für Streckenobjekt: %, Anfang: %, Ende: %', ST_AsText(NEW.geometrie_streckenobjekt), ST_AsText(punkt_anfang), ST_AsText(punkt_ende);
			-------------------------------------------------------------------------
			-- Fehlende Strassenelementpunkte auf Verbindungspunkten erzeugen
			sql = '
				SELECT
					vpse.id,
					vpse.punktgeometrie,
					vpse.auf_strassenelement
				FROM
					(
						SELECT
							vp.id,
							vp.punktgeometrie,
							min(se.id) auf_strassenelement
						FROM
							ukos_okstra.verbindungspunkt vp JOIN
							ukos_okstra.strassenelement se ON se.beginnt_bei_vp = vp.id
						WHERE
							vp.gueltig_bis > now() AND
							se.gueltig_bis > now() AND
							ST_Distance(''' || NEW.geometrie_streckenobjekt::text || ''', vp.punktgeometrie) < ' || tolerance || '
						GROUP BY vp.id
					) vpse LEFT JOIN
					(
						SELECT
							id,
							punktgeometrie
						FROM
							ukos_okstra.strassenelementpunkt
						WHERE
							 gueltig_bis > ' || quote_literal(aenderungszeit) || '
					) sep ON ST_Equals(vpse.punktgeometrie, sep.punktgeometrie)
				WHERE
					sep.id IS NULL
				';
			FOR vp IN EXECUTE sql
			LOOP
				RAISE NOTICE 'Erzeuge Strassenelementpunkt auf VP: % an Punkt: %', vp.id, ST_AsText(vp.punktgeometrie);
				EXECUTE '
					INSERT INTO ukos_okstra.strassenelementpunkt (punktgeometrie, auf_strassenelement, angelegt_von) VALUES
					($1, $2, $3)
				'
				USING vp.punktgeometrie, vp.auf_strassenelement, NEW.angelegt_von;
			END LOOP;

			-------------------------------------------------------------------------
			-- Fehlenden Strassenelementpunkt am Anfang der Strecke anlegen
			EXECUTE '
				SELECT
					id,
					punktgeometrie,
					auf_strassenelement
				FROM
					ukos_okstra.strassenelementpunkt
				WHERE
					gueltig_bis > $3 AND
					ST_Distance(punktgeometrie, $1) < $2
				ORDER BY
					ST_Distance(punktgeometrie, $1)
				LIMIT 1
			'
			USING punkt_anfang, tolerance, aenderungszeit
			INTO sep;
			IF sep.id IS NULL THEN
				--kein SEP an der Stelle gefunden, anlegen
				RAISE NOTICE 'Erzeuge Strassenelementpunkt am Anfang an Punkt: %', ST_AsText(punkt_anfang);
				EXECUTE '
					INSERT INTO ukos_okstra.strassenelementpunkt (
						punktgeometrie,
						auf_strassenelement,
						angelegt_von
					) VALUES (
						$1,
						(SELECT id FROM ukos_okstra.strassenelement WHERE id != ''00000000-0000-0000-0000-000000000000'' AND gueltig_bis > $3 AND ST_Distance(liniengeometrie, $1) < $4),
						$2
					)
					RETURNING id, punktgeometrie
				'
				USING punkt_anfang, NEW.angelegt_von, aenderungszeit, tolerance
				INTO sep;
			ELSE
				RAISE NOTICE 'Strassenelementpunkt am Anfang: % an Punkt: % schon vorhanden.', sep.id, ST_AsText(sep.punktgeometrie);
			END IF;
			RAISE NOTICE 'Übernehme die Koordinaten des gefundenen Strassenelementpunktes % als neuen Anfang der Strecke', sep.id;
			liniengeometrie_streckenobjekt = ST_SetPoint(liniengeometrie_streckenobjekt, 0, sep.punktgeometrie);

			-------------------------------------------------------------------------
			-- Fehlenden Strassenelementpunkt am Ende der Strecke anlegen
			EXECUTE '
				SELECT
					id,
					punktgeometrie,
					auf_strassenelement
				FROM
					ukos_okstra.strassenelementpunkt
				WHERE
					gueltig_bis > $3 AND
					ST_Distance(punktgeometrie, $1) < $2
				ORDER BY
					ST_Distance(punktgeometrie, $1)
				LIMIT 1
			'
			USING punkt_ende, tolerance, aenderungszeit
			INTO sep;
			IF sep.id IS NULL THEN
				--kein SEP an der Stelle gefunden, anlegen
				RAISE NOTICE 'Erzeuge Strassenelementpunkt am Ende an Punkt: %', ST_AsText(punkt_ende);
				EXECUTE '
					INSERT INTO ukos_okstra.strassenelementpunkt (
						punktgeometrie,
						auf_strassenelement,
						angelegt_von
					) VALUES (
						$1,
						(SELECT id FROM ukos_okstra.strassenelement WHERE id != ''00000000-0000-0000-0000-000000000000'' AND gueltig_bis > $3 AND ST_Distance(liniengeometrie, $1) < $4),
						$2
					)
					RETURNING id, punktgeometrie
				'
				USING punkt_ende, NEW.angelegt_von, aenderungszeit, tolerance
				INTO sep;
			ELSE
				RAISE NOTICE 'Strassenelementpunkt am Ende: % an Punkt: % schon vorhanden.', sep.id, ST_AsText(sep.punktgeometrie);
			END IF;
			RAISE NOTICE 'Übernehme die Koordinaten des gefundenen Strassenelementpunktes % als neues Ende der Strecke', sep.id;
			liniengeometrie_streckenobjekt = ST_SetPoint(liniengeometrie_streckenobjekt, ST_NumPoints(liniengeometrie_streckenobjekt) - 1, sep.punktgeometrie);

			-- Fehlende Teilelemente entlang der Strecke bilden
			-- Alle SEP finden, station berechnen sortieren und leg und die te finden, die fehlen
			FOR te IN EXECUTE '
				SELECT
					a.beginnt_bei_sep,
					a.endet_bei_sep,
					a.auf_strassenelement,
					t.id,
					t.beginnt_bei_strassenelempkt,
					t.endet_bei_strassenelempkt
				FROM
					(
						SELECT
							id AS beginnt_bei_sep,
							lead(id) OVER (ORDER BY ST_LineLocatePoint($1, punktgeometrie)) endet_bei_sep,
							sep.auf_strassenelement,
							ST_LineLocatePoint($1, punktgeometrie) ordinate
						FROM
							ukos_okstra.strassenelementpunkt sep
						WHERE
							ST_Distance($1, punktgeometrie) < $2
						ORDER BY ordinate
					) a LEFT JOIN
					ukos_okstra.teilelement t ON
						(a.beginnt_bei_sep = t.beginnt_bei_strassenelempkt AND a.endet_bei_sep = t.endet_bei_strassenelempkt) OR
						(a.beginnt_bei_sep = t.endet_bei_strassenelempkt AND a.endet_bei_sep = t.beginnt_bei_strassenelempkt)
				'
				USING liniengeometrie_streckenobjekt, tolerance
			LOOP
				IF te.endet_bei_sep IS NOT NULL THEN
					IF te.id IS NULL THEN
						RAISE NOTICE 'Lege fehlendes Teilelement zwischen Strassenelementpunkt % und % an.', te.beginnt_bei_sep, te.endet_bei_sep;
						EXECUTE '
							INSERT INTO ukos_okstra.teilelement (
								beginnt_bei_strassenelempkt,
								endet_bei_strassenelempkt,
								auf_strassenelement,
								erfassungsdatum,
								angelegt_von
							)
							VALUES
								($1, $2, $3, $4, $5)
							RETURNING id
						'
						USING te.beginnt_bei_sep, te.endet_bei_sep, te.auf_strassenelement, aenderungszeit, NEW.angelegt_von
						INTO te.id;
					END IF;

					RAISE NOTICE 'Ordne das Teilelement % dem Streckenobjekt % zu.', te.id, NEW.id;
					EXECUTE '
						INSERT INTO ukos_okstra.streckenobjekt_to_teilelement (teilelement_id, streckenobjekt_id) VALUES
						($1, $2)
					'
					USING te.id, NEW.id;
				END IF;
			END LOOP;

			NEW.geometrie_streckenobjekt = ST_Multi(liniengeometrie_streckenobjekt);

			RETURN NEW;
		END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.delete_teilelemente();
	CREATE OR REPLACE FUNCTION ukos_okstra.delete_teilelemente()
	RETURNS trigger AS
	$BODY$
		DECLARE
			te RECORD;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Frage Teilelemente des Streckenobjektes ab, die nicht mehr von anderen abhängen und starte die Löschung
			FOR te IN EXECUTE '
				SELECT
					s2t.teilelement_id,
					s2t.streckenobjekt_id,
				  andere_te.teilelement_id AS id
				FROM
					ukos_okstra.streckenobjekt_to_teilelement s2t LEFT JOIN
					(
						SELECT
							s2t2.teilelement_id,
							s2t2.streckenobjekt_id
						FROM
							ukos_okstra.streckenobjekt_to_teilelement s2t2 JOIN
							ukos_base.streckenobjekt so ON s2t2.streckenobjekt_id = so.id
						WHERE
							so.gueltig_bis > $1 AND
							so.id != $2
					) andere_te ON s2t.teilelement_id = andere_te.teilelement_id
				WHERE
					s2t.streckenobjekt_id = $2 AND
				  andere_te.teilelement_id IS NULL
				'
				USING aenderungszeit, OLD.id
			LOOP
				EXECUTE '
					DELETE FROM ukos_okstra.teilelement WHERE id = $1
				'
				USING te.teilelement_id;
			END LOOP;

		RETURN OLD;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	-- DROP FUNCTION ukos_okstra.delete_strassenelementpunkte();
	CREATE OR REPLACE FUNCTION ukos_okstra.delete_strassenelementpunkte()
	RETURNS trigger AS
	$BODY$
		DECLARE
			te RECORD;
			aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Frage Strassenelementpunkte des Teilelementes ab und starte die Löschung
			EXECUTE '
				SELECT
					beginnt_bei_strassenelempkt,
					endet_bei_strassenelempkt
				FROM
				  ukos_okstra.teilelement te
				WHERE
					id = $1
				'
			USING OLD.id
			INTO te;
			EXECUTE '
				DELETE FROM ukos_okstra.strassenelementpunkt WHERE id = $1
			'
			USING te.beginnt_bei_strassenelempkt;
			EXECUTE '
				DELETE FROM ukos_okstra.strassenelementpunkt WHERE id = $1
			'
			USING te.endet_bei_strassenelempkt;
		RETURN OLD;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;


	-- Trigger on verkehrseinschraenkung
	-- INSERT Trigger
	CREATE TRIGGER tr_before_insert_validate_strecke
	BEFORE INSERT
	ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strecke();

	CREATE TRIGGER tr_after_insert_add_teilelemente
	AFTER INSERT
	ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_teilelemente();

	-- UPDATE Trigger
	CREATE TRIGGER tr_before_update_10_validate_strecke
	BEFORE UPDATE OF geometrie_streckenobjekt
	ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strecke();

	CREATE TRIGGER tr_before_update_20_delete_teilelemente
	BEFORE UPDATE OF geometrie_streckenobjekt
	ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_teilelemente();

	CREATE TRIGGER tr_after_update_add_teilelemente
	AFTER UPDATE OF geometrie_streckenobjekt
	ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_teilelemente();

	-- DELETE Trigger
	CREATE TRIGGER tr_before_delete_10_untergang
  BEFORE DELETE
  ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
  EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_20_delete_teilelemente
  BEFORE DELETE
  ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_teilelemente();

	CREATE TRIGGER tr_before_delete_99_stop
  BEFORE DELETE
  ON ukos_okstra.verkehrseinschraenkung
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

	-- Trigger on teilelement
	-- DELETE Trigger
	CREATE TRIGGER tr_before_delete_10_untergang
	BEFORE DELETE
	ON ukos_okstra.teilelement
	FOR EACH ROW
  EXECUTE PROCEDURE ukos_okstra.untergang();

	CREATE TRIGGER tr_before_delete_20_delete_strassenelementpunkte
	BEFORE DELETE
	ON ukos_okstra.teilelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.delete_strassenelementpunkte();

	CREATE TRIGGER tr_before_delete_99_stop
	BEFORE DELETE
	ON ukos_okstra.teilelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.stop();

COMMIT;