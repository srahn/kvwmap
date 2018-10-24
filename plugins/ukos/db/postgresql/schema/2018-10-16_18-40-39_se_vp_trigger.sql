BEGIN;

	-- DROP FUNCTION ukos_okstra.validate_strassenelement();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_strassenelement()
	RETURNS trigger AS
	$BODY$
		DECLARE
			vp_beginnt_bei_geometrie	geometry(Point, 25833) = ST_StartPoint(NEW.liniengeometrie);
			vp_endet_bei_geometrie		geometry(Point, 25833) = ST_EndPoint(NEW.liniengeometrie);
			rec												RECORD;
			tolerance									NUMERIC;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;

			--------------------------------------------------------------------------------------------------------
			-- Exceptions

			-- Prüfe ob eine Liniengeometrie existiert
			IF NEW.liniengeometrie IS NULL THEN
				RAISE EXCEPTION 'Liniengeometrie leer. Strassenelement muss eine Liniengeometrie haben.';
			END IF;

			-- Prüfe ob Strassenelement mit gleicher Geometrie schon existiert
			FOR rec IN EXECUTE 'SELECT id FROM ukos_okstra.strassenelement WHERE '
				|| 'gueltig_bis > now() AND '
				|| 'ST_Equals($1, liniengeometrie)'
				USING NEW.liniengeometrie
			LOOP
				RAISE EXCEPTION 'Strassenelement mit gleicher Liniengeometrie existiert schon.';
			END LOOP;

			-- ToDo: Prüfen ob neues Strassenelement
			--				- vorhandenes Strassenelemnt schneidet
			--				- Verbindungspunkt schneidet
			--				- auf einem anderen Strassenelement liegt

			--------------------------------------------------------------------------------------------------------
			-- Berechnungen
			-- Berechnung der Länge
			NEW.laenge = ST_Length(NEW.liniengeometrie);

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	CREATE TRIGGER validate_strassenelement
	BEFORE INSERT
	ON ukos_okstra.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_strassenelement();

	-- DROP FUNCTION ukos_okstra.add_strassenelement();
	CREATE OR REPLACE FUNCTION ukos_okstra.add_verbindungspunkte()
	RETURNS trigger AS
	$BODY$
		DECLARE
			uuid_vp										character varying;
			vp_beginnt_bei_geometrie	geometry(Point, 25833) = ST_StartPoint(NEW.liniengeometrie);
			vp_endet_bei_geometrie		geometry(Point, 25833) = ST_EndPoint(NEW.liniengeometrie);
			rec												RECORD;
			tolerance									NUMERIC;
			liniengeometrie_changed	BOOLEAN;
			sql	TEXT;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Toplogietolerance' INTO tolerance;

			RAISE NOTICE 'Füge Verindungspunkte des Strassenelementes mit Liniengeometrie: % und Topologietolerance: % hinzu, falls noch nicht übergeben oder nicht vorhanden und setze ggf. neue Geometrie und Verindungspunkte in Strassenelement ein.', ST_AsText(NEW.liniengeometrie), tolerance;

			--------------------------------------------------------------------------------------------------------
			IF NEW.beginnt_bei_vp IS NULL THEN
				EXECUTE '
					SELECT id, punktgeometrie, ST_Equals($1, punktgeometrie) punkt_gleich
					FROM ukos_okstra.verbindungspunkt
					WHERE ST_Distance(punktgeometrie, $1) < $2
					ORDER BY ST_Distance(punktgeometrie, $1)
					LIMIT 1
				'
				USING vp_beginnt_bei_geometrie, tolerance
				INTO rec;

				IF rec.id IS NULL THEN --wenn kein Knoten an der Stelle gefunden wird
					uuid_vp = uuid_generate_v4(); --dann uuid neu generieren
					RAISE NOTICE 'Kein Verbindungspunkt (VP) am Anfang des Strassenelementes gefunden. Lege neuen VP an mit id: %', uuid_vp;
					EXECUTE 'INSERT INTO ukos_okstra.verbindungspunkt (id, id_strasse, punktgeometrie) VALUES ( $1, $2, $3 )'
					USING uuid_vp, NEW.id_strasse, vp_beginnt_bei_geometrie; --und Datensatz anlegen
					NEW.beginnt_bei_vp = uuid_vp; --und die id im neuen strassenelement eintragen
				ELSE
					RAISE NOTICE 'Verbindungspunkt id: % am Anfang des Strassenelementes gefunden.', rec.id;
					-- Die id des gefundenen Verbindungspunktes übernehmen
					NEW.beginnt_bei_vp = rec.id;
					IF NOT rec.punkt_gleich THEN
						RAISE NOTICE 'Übernehme die Koordinaten des gefundenen Anfangspunktes % (%)', rec.id, ST_AsText(rec.punktgeometrie);
						NEW.liniengeometrie = ST_SetPoint(NEW.liniengeometrie, 0, rec.punktgeometrie);
						liniengeometrie_changed = true;
					END IF;
				END IF;
			END IF;

			--------------------------------------------------------------------------------------------------------
			IF NEW.endet_bei_vp IS NULL THEN
				EXECUTE '
					SELECT id, punktgeometrie, ST_Equals($1, punktgeometrie) punkt_gleich
					FROM ukos_okstra.verbindungspunkt WHERE ST_Distance(punktgeometrie, $1) <= $2
					ORDER BY ST_Distance(punktgeometrie, $1)
					LIMIT 1
				'
				USING vp_endet_bei_geometrie, tolerance
				INTO rec;

				IF rec.id IS NULL THEN --wenn kein Knoten an der Stelle gefunden wird
					uuid_vp = uuid_generate_v4(); --dann uuid neu generieren
					RAISE NOTICE 'Kein Verbindungspunkt (VP) am Ende des Strassenelementes gefunden. Lege neuen VP an mit id: %', uuid_vp;
					EXECUTE 'INSERT INTO ukos_okstra.verbindungspunkt (id, id_strasse, punktgeometrie) VALUES ( $1, $2, $3 )'
					USING uuid_vp, NEW.id_strasse, vp_endet_bei_geometrie; --und Datensatz anlegen
					NEW.endet_bei_vp = uuid_vp; --und die id im neuen strassenelement eintragen
				ELSE
					RAISE NOTICE 'Verbindungspunkt id: % am Ende des Strassenelementes gefunden.', rec.id;
					-- Die id des gefundenen Verbindungspunktes übernehmen
					NEW.endet_bei_vp = rec.id;
					IF NOT rec.punkt_gleich THEN
						RAISE NOTICE 'Übernehme die Koordinaten des gefundenen Endpunktes % (%)', rec.id, ST_AsText(rec.punktgeometrie);
						NEW.liniengeometrie = ST_SetPoint(NEW.liniengeometrie, ST_NumPoints(NEW.liniengeometrie) - 1, rec.punktgeometrie);
						liniengeometrie_changed = true;
					END IF;
				END IF;
			END IF;

			--------------------------------------------------------------------------------------------------------
			sql = '
				UPDATE
					ukos_okstra.strassenelement
				SET
					beginnt_bei_vp = ' || quote_literal(NEW.beginnt_bei_vp) || ',
					endet_bei_vp = ' || quote_literal(NEW.endet_bei_vp) || '
			';
			IF liniengeometrie_changed THEN
				sql = sql || ', liniengeometrie = ' || quote_literal(NEW.liniengeometrie::text);
			END IF;
			sql = sql || ' WHERE id = ' || quote_literal(NEW.id);
			RAISE NOTICE 'Update Anfang, Endpunkt und ggf. Liniengeometrie von Strassenelement mit SQL: %', sql;
			EXECUTE sql;

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	CREATE TRIGGER add_verbindungspunkte
	AFTER INSERT
	ON ukos_okstra.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.add_verbindungspunkte();

	CREATE INDEX strassenelement_gist
	ON ukos_okstra.strassenelement
	USING gist
	(liniengeometrie);
	
/*
INSERT INTO ukos_okstra.strassenelement (liniengeometrie, id_strasse, angelegt_am, erfassungsdatum) values 
	(ST_geometryfromtext('Linestring(400130 5900070, 400100 5900020.11)', 25833), '00000000-0000-0000-0000-000000000000', now(), '2018-10-16');

select id, beginnt_bei_vp, endet_bei_vp, laenge from ukos_okstra.strassenelement
delete from ukos_okstra.strassenelement

select id from ukos_okstra.verbindungspunkt
delete from ukos_okstra.verbindungspunkt where id not like '00000000-0000-0000-0000-000000000000'

select * from ukos_base.idents
delete from ukos_base.idents
*/

	-- DROP FUNCTION ukos_okstra.validate_strassenelement();
	CREATE OR REPLACE FUNCTION ukos_okstra.validate_verbindungspunkt()
	RETURNS trigger AS
	$BODY$
		DECLARE
			rec												RECORD;
			tolerance									NUMERIC;
		BEGIN
			--------------------------------------------------------------------------------------------------------
			-- Initialisierung
			EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;

			--------------------------------------------------------------------------------------------------------
			-- Exceptions
			-- Prüfe ob eine Punktgeometrie existiert
			IF NEW.punktgeometrie IS NULL THEN
				RAISE EXCEPTION 'Punktgeometrie leer. Verbindungspunkt muss eine Punktgeometrie haben.';
			END IF;

			-- Prüfe ob schon ein Verbindungspunkt mit Tolerance-Abstand existiert
			FOR rec IN EXECUTE '
				SELECT id, punktgeometrie, ST_Equals($1, punktgeometrie) punkt_gleich
				FROM ukos_okstra.verbindungspunkt
				WHERE ST_Distance(punktgeometrie, $1) < $2
				ORDER BY ST_Distance(punktgeometrie, $1)
				LIMIT 1
			'
			USING NEW.punktgeometrie, tolerance
			LOOP
				RAISE EXCEPTION 'Verbindungspunkt % im Abstand von % existiert schon.', rec.id, tolerance;
			END LOOP;

		RETURN NEW;
	END;
	$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

	CREATE TRIGGER validate_verbindungspunkt
	BEFORE INSERT
	ON ukos_okstra.verbindungspunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.validate_verbindungspunkt();

	CREATE OR REPLACE FUNCTION ukos_okstra.split_strassenelemente()
		RETURNS trigger AS
	$BODY$
			DECLARE
				rec							RECORD;
				tolerance				NUMERIC;
				teil_anfang_id	CHARACTER VARYING;
				teil_ende_id		CHARACTER VARYING;
				sql							TEXT;
				aenderungszeit	TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
			BEGIN
				--------------------------------------------------------------------------------------------------------
				-- Initialisierung
				EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Toplogietolerance' INTO tolerance;

				RAISE NOTICE 'Trenne Strassenelemente am Verbindungspunkt % auf mit Punktgeometrie: % und Topologietolerance: %, wenn Verbindungspunkt auf solche fällt ', NEW.id, ST_AsText(NEW.punktgeometrie), tolerance;
				--------------------------------------------------------------------------------------------------------
				FOR rec IN EXECUTE '
					SELECT
						id,
						st_distance($1, liniengeometrie),
						st_astext(liniengeometrie),
						ST_LineLocatePoint(liniengeometrie, $1),
						ST_LineSubstring(liniengeometrie, 0, ST_LineLocatePoint(liniengeometrie, $1)) teil_anfang,
						ST_LineSubstring(liniengeometrie, ST_LineLocatePoint(liniengeometrie, $1), 1) teil_ende
					FROM
						ukos_okstra.strassenelement
					WHERE
						gueltig_bis > $3 AND
						$1 && liniengeometrie AND
						st_distance($1, liniengeometrie) < $2 AND
						ST_Length(ST_LineSubstring(liniengeometrie, 0, ST_LineLocatePoint(liniengeometrie, $1))) > $2 AND
						ST_Length(ST_LineSubstring(liniengeometrie, ST_LineLocatePoint(liniengeometrie, $1), 1)) > $2
				'
				USING NEW.punktgeometrie, tolerance, aenderungszeit
				LOOP
					RAISE NOTICE 'Teile Strassenelement % auf in die Teile % und %.', rec.id, rec.teil_anfang, rec.teil_ende;

					-- Setze das alte Strassenelement auf historisch
					EXECUTE 'UPDATE ukos_okstra.strassenelement SET gueltig_bis = now() WHERE id = $1'
					USING rec.id;

					-- Füge das neue Strassenelement vom Anfangsteil des alten Strassenelementes ein
					EXECUTE '
						INSERT INTO ukos_okstra.strassenelement(
							gueltig_von, gueltig_bis,
							id_strassenelement, id_preisermittlung, 
							id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
							id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
							id_eigentuemer, id_baulasttraeger, ahk, baujahr,
							angelegt_am, angelegt_von,
							geaendert_am, geaendert_von, ident_hist, bemerkung, 
							objektname, zusatzbezeichnung,
							objektart, objektart_kurz, 
							objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
							baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
							eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
							kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
							hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
							geometrie_streckenobjekt, 
							liniengeometrie,
							gdf_id, verkehrsrichtung, laenge, stufe, erfassungsdatum, 
							systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
							quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
							unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
							hat_teilelement,
							beginnt_bei_vp,
							endet_bei_vp,
							in_nullpunkt, 
							in_komplexem_knoten)
						SELECT
							$1, ''2100-01-01 02:00:00+01'',
							id_strassenelement, id_preisermittlung, 
							id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
							id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
							id_eigentuemer, id_baulasttraeger, ahk, baujahr,
							$1, $2,
							geaendert_am, geaendert_von, ident_hist, bemerkung, 
							objektname, zusatzbezeichnung,
							objektart, objektart_kurz, 
							objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
							baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
							eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
							kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
							hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
							geometrie_streckenobjekt,
							$3,
							gdf_id, verkehrsrichtung,
							$4,
							stufe, erfassungsdatum, 
							systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
							quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
							unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
							hat_teilelement,
							beginnt_bei_vp,
							$5,
							in_nullpunkt, 
							in_komplexem_knoten
						FROM ukos_okstra.strassenelement
						WHERE id = $6
						RETURNING id
					'
					USING aenderungszeit, NEW.angelegt_von, rec.teil_anfang, ST_Length(rec.teil_anfang), NEW.id, rec.id
					INTO teil_anfang_id;
					RAISE NOTICE 'Strassenelement id: % am Anfang angelegt', teil_anfang_id;

					-- Füge das neue Strassenelement vom Endteil des alten Strassenelementes ein
					EXECUTE '
						INSERT INTO ukos_okstra.strassenelement(
							gueltig_von, gueltig_bis,
							id_strassenelement, id_preisermittlung, 
							id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
							id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
							id_eigentuemer, id_baulasttraeger, ahk, baujahr,
							angelegt_am, angelegt_von,
							geaendert_am, geaendert_von, ident_hist, bemerkung, 
							objektname, zusatzbezeichnung,
							objektart, objektart_kurz, 
							objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
							baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
							eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
							kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
							hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
							geometrie_streckenobjekt, 
							liniengeometrie,
							gdf_id, verkehrsrichtung, laenge, stufe, erfassungsdatum, 
							systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
							quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
							unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
							hat_teilelement,
							beginnt_bei_vp,
							endet_bei_vp,
							in_nullpunkt, 
							in_komplexem_knoten)
						SELECT
							$1, ''2100-01-01 02:00:00+01'',
							id_strassenelement, id_preisermittlung, 
							id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
							id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
							id_eigentuemer, id_baulasttraeger, ahk, baujahr,
							$1, $2,
							geaendert_am, geaendert_von, ident_hist, bemerkung, 
							objektname, zusatzbezeichnung,
							objektart, objektart_kurz, 
							objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
							baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
							eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
							kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
							hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
							geometrie_streckenobjekt,
							$3,
							gdf_id, verkehrsrichtung,
							$4,
							stufe, erfassungsdatum, 
							systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
							quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
							unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
							hat_teilelement,
							$5,
							endet_bei_vp,
							in_nullpunkt, 
							in_komplexem_knoten
						FROM ukos_okstra.strassenelement
						WHERE id = $6
						RETURNING id
					'
					USING aenderungszeit, NEW.angelegt_von, rec.teil_ende, ST_Length(rec.teil_ende), NEW.id, rec.id
					INTO teil_ende_id;
					RAISE NOTICE 'Strassenelement id: % am Ende angelegt', teil_ende_id;

					-- ToDo:
					-- Wenn sich ein Strassenelement ändert müssen auch die abhängigen Objekte geändert werden. Das ist Bestandteil eines anderen Triggers
					-- Wenn ein neues Strassenelement erzeugt wird als Teil eines anderen, müssen auch die an dem alten hängenden Objekte für das neue Teil übernommen werden. Das kann hier gemacht werden oder in einem separaten Trigger dem die id des alten Strassenelementes übergeben wird.

				END LOOP;

			RETURN NEW;
		END;
		$BODY$
		LANGUAGE plpgsql VOLATILE
		COST 100;

	CREATE TRIGGER split_strassenelemente
	AFTER INSERT
	ON ukos_okstra.verbindungspunkt
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_okstra.split_strassenelemente();

COMMIT;