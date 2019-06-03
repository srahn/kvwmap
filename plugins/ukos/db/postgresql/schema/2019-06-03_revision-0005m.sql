BEGIN;

  CREATE OR REPLACE FUNCTION ukos_okstra.split_strassenelemente()
  RETURNS trigger AS
  $BODY$
    DECLARE
      tolerance        NUMERIC;
      se              RECORD;
      teil_anfang_id  CHARACTER VARYING;
      teil_ende_id    CHARACTER VARYING;
      new_se_id        CHARACTER VARYING;
      sep              RECORD;
      new_sep_id      CHARACTER VARYING;
      new_sep_station  NUMERIC;
      sql              TEXT;
      aenderungszeit  TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
      debug            BOOLEAN = false;
    BEGIN
      --------------------------------------------------------------------------------------------------------
      -- Initialisierung
      EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
      EXECUTE 'SELECT value FROM ukos_base.config WHERE key = $1' USING 'Debugmodus' INTO debug;
      IF debug THEN RAISE NOTICE 'Führe Trigger % on % aus', TG_NAME, TG_TABLE_NAME; END IF;

      --------------------------------------------------------------------------------------------------------
      sql = FORMAT('
        SELECT
          id,
          ident,
          beginnt_bei_vp,
          endet_bei_vp,
          st_distance(%1$L, liniengeometrie),
          st_astext(liniengeometrie),
          ST_LineLocatePoint(liniengeometrie, %1$L),
          ST_LineInterpolatePoint(liniengeometrie, ST_LineLocatePoint(liniengeometrie, %1$L)) fusspunkt,
          ST_LineSubstring(liniengeometrie, 0, ST_LineLocatePoint(liniengeometrie, %1$L)) teil_anfang,
          ST_LineSubstring(liniengeometrie, ST_LineLocatePoint(liniengeometrie, %1$L), 1) teil_ende
        FROM
          ukos_okstra.strassenelement
        WHERE
          gueltig_bis > %3$L AND
          %1$L && liniengeometrie AND
          st_distance(%1$L, liniengeometrie) < %2$s AND
          ST_Length(ST_LineSubstring(liniengeometrie, 0, ST_LineLocatePoint(liniengeometrie, %1$L))) > %2$s AND
          ST_Length(ST_LineSubstring(liniengeometrie, ST_LineLocatePoint(liniengeometrie, %1$L), 1)) > %2$s
      ', NEW.punktgeometrie, tolerance, aenderungszeit);
      IF debug THEN RAISE NOTICE 'Prüfe ob Verbindungspunkt Strassenelemente auftrennt mit SQL: %', sql; END IF;
      FOR se IN EXECUTE sql LOOP
        IF debug THEN RAISE NOTICE 'Trenne Strassenelement: % am Verbindungspunkt % auf mit Punktgeometrie: % und Topologietolerance: %', se.ident, NEW.ident, ST_AsText(NEW.punktgeometrie), tolerance; END IF;


        IF debug THEN RAISE NOTICE 'Setze beginnt_ und endet_bei_vp auf default, damit sie beim Löschen des SE nicht mit gelöscht werden.'; END IF;
        EXECUTE FORMAT('UPDATE ukos_okstra.strassenelement SET beginnt_bei_vp = %1$L, endet_bei_vp = %1$L WHERE id = %2$L', '00000000-0000-0000-0000-000000000000', se.id);
        IF debug THEN RAISE NOTICE 'Lösche Strassenelement: %', se.ident; END IF;
        EXECUTE FORMAT('DELETE FROM ukos_okstra.strassenelement WHERE id = %1$L', se.id);


        -- Füge das neue Strassenelement vom Anfangsteil des alten Strassenelementes ein
        sql = FORMAT('
          INSERT INTO ukos_okstra.strassenelement(
            stelle_id, gueltig_von, gueltig_bis,
            id_strassenelement, id_preisermittlung, 
            id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
            id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
            id_eigentuemer, id_baulasttraeger, ahk, baujahr,
            angelegt_am, angelegt_von,
            geaendert_am, geaendert_von,
            ident_hist, bemerkung, 
            objektname, zusatzbezeichnung,
            objektart, objektart_kurz, 
            objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
            baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
            eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
            kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
            hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
            geometrie_streckenobjekt, 
            liniengeometrie,
            gdf_id, verkehrsrichtung,
            laenge,
            stufe, erfassungsdatum, 
            systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
            quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
            unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
            hat_teilelement,
            beginnt_bei_vp,
            endet_bei_vp,
            in_nullpunkt, 
            in_komplexem_knoten,
            geaendert_von_ereignis
          )
          SELECT
            stelle_id, %1$L, ''2100-01-01 02:00:00+01'',
            id_strassenelement, id_preisermittlung, 
            id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
            id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
            id_eigentuemer, id_baulasttraeger, ahk, baujahr,
            %1$L, %2$L,
            %1$L, %2$L,
            ident_hist, bemerkung, 
            objektname, zusatzbezeichnung,
            objektart, objektart_kurz, 
            objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
            baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
            eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
            kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
            hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
            geometrie_streckenobjekt,
            %3$L,
            gdf_id, verkehrsrichtung,
            %4$s,
            stufe, erfassungsdatum, 
            systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
            quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
            unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
            hat_teilelement,
            %8$L,
            %5$L,
            in_nullpunkt, 
            in_komplexem_knoten,
            %7$L
          FROM ukos_okstra.strassenelement
          WHERE id = %6$L
          RETURNING id
        ', aenderungszeit, NEW.angelegt_von, se.teil_anfang, ST_Length(se.teil_anfang), NEW.id, se.id, 'Trigger umgehen', se.beginnt_bei_vp);
        EXECUTE sql INTO teil_anfang_id;
        IF debug THEN RAISE NOTICE 'Strassenelement id: % am Anfang angelegt', teil_anfang_id; END IF;

        -- Füge das neue Strassenelement vom Endteil des alten Strassenelementes ein
        sql = FORMAT('
          INSERT INTO ukos_okstra.strassenelement(
            stelle_id, gueltig_von, gueltig_bis,
            id_strassenelement, id_preisermittlung, 
            id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
            id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
            id_eigentuemer, id_baulasttraeger, ahk, baujahr,
            angelegt_am, angelegt_von,
            geaendert_am, geaendert_von,
            ident_hist, bemerkung, 
            objektname, zusatzbezeichnung,
            objektart, objektart_kurz, 
            objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
            baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
            eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
            kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
            hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
            geometrie_streckenobjekt, 
            liniengeometrie,
            gdf_id, verkehrsrichtung,
            laenge,
            stufe, erfassungsdatum, 
            systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
            quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
            unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
            hat_teilelement,
            beginnt_bei_vp,
            endet_bei_vp,
            in_nullpunkt, 
            in_komplexem_knoten,
            geaendert_von_ereignis
          )
          SELECT
            stelle_id, %1$L, ''2100-01-01 02:00:00+01'',
            id_strassenelement, id_preisermittlung, 
            id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
            id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
            id_eigentuemer, id_baulasttraeger, ahk, baujahr,
            %1$L, %2$L,
            %1$L, %2$L,
            ident_hist, bemerkung, 
            objektname, zusatzbezeichnung,
            objektart, objektart_kurz, 
            objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
            baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
            eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
            kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
            hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt,
            geometrie_streckenobjekt,
            %3$L,
            gdf_id, verkehrsrichtung,
            %4$s,
            stufe, erfassungsdatum, 
            systemdatum, textfeld, art_der_erfassung, art_der_erfassung_sonst, 
            quelle_der_information, quelle_der_information_sonst, rfid, migrationshinweise, 
            unscharf, hat_objekt_id, zwischen_kreuzungsbereichen, im_kreuzungsbereich, 
            hat_teilelement,
            %5$L,
            %8$L,
            in_nullpunkt, 
            in_komplexem_knoten,
            %7$L
          FROM ukos_okstra.strassenelement
          WHERE id = %6$L
          RETURNING id
        ', aenderungszeit, NEW.angelegt_von, se.teil_ende, ST_Length(se.teil_ende), NEW.id, se.id, 'Trigger umgehen', se.endet_bei_vp);
        EXECUTE sql INTO teil_ende_id;
        IF debug THEN RAISE NOTICE 'Strassenelement id: % am Ende angelegt', teil_ende_id; END IF;

        -- Passe die Strassenelementpunkte des alten Strassenelementes an
        FOR sep IN EXECUTE '
          SELECT
            id,
            station,
            abstand_zur_bestandsachse
          FROM
            ukos_okstra.strassenelementpunkt
          WHERE
            gueltig_bis > $2 AND
            auf_strassenelement = $1
          ORDER BY
            station, abstand_zur_bestandsachse
        '
        USING se.id, aenderungszeit
        LOOP
          IF sep.station < ST_Length(se.teil_anfang) THEN
            IF debug THEN RAISE NOTICE 'Erzeuge und ordne Strassenelementpunkt % dem Anfangsteil % zu,', sep.id, teil_anfang_id; END IF;
            new_se_id = teil_anfang_id;
            new_sep_station = sep.station;
          ELSE
            IF debug THEN RAISE NOTICE 'Erzeuge und ordne Strassenelementpunkt % dem Endteil % zu.', se.id, teil_ende_id; END IF;
            new_sep_station = sep.station - ST_Length(se.teil_anfang);
            new_se_id = teil_ende_id;
          END IF;

          -- Setze den alten Strassenelementpunkt auf historisch
          EXECUTE 'UPDATE ukos_okstra.strassenelementpunkt SET gueltig_bis = $2 WHERE id = $1'
          USING sep.id, aenderungszeit;

          -- Füge den neuen Strassenelementpunkt ein
          EXECUTE '
            INSERT INTO ukos_okstra.strassenelementpunkt(
              gueltig_von, gueltig_bis,
              id_strassenelement,
              id_preisermittlung, 
              id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
              id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
              id_eigentuemer, id_baulasttraeger, ahk, baujahr,
              angelegt_am, angelegt_von,
              geaendert_am, geaendert_von,
              ident_hist, bemerkung, 
              objektname, zusatzbezeichnung, objektart, objektart_kurz, 
              objektnummer, zustandsnote, datum_der_benotung, pauschalpreis, 
              baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung, 
              eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand, 
              kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis, 
              hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt, punktgeometrie,
              station,
              abstand_zur_bestandsachse,
              abstand_zur_fahrbahnoberkante,
              auf_strassenelement,
              id_strasse
            )
            SELECT
              $1, ''2100-01-01 02:00:00+01'',
              $2,
              id_preisermittlung, 
              id_zustand, id_zustandsbewertung_01, id_zustandsbewertung_02, 
              id_zustandsbewertung_03, id_zustandsbewertung_04, id_zustandsbewertung_05, 
              id_eigentuemer, id_baulasttraeger, ahk, baujahr,
              $1, $3,
              geaendert_am, geaendert_von, ident_hist, bemerkung,
              objektname, zusatzbezeichnung, objektart, objektart_kurz,
              objektnummer, zustandsnote, datum_der_benotung, pauschalpreis,
              baulasttraeger, baulasttraeger_dritter, abschreibung, art_der_preisermittlung,
              eroeffnungsbilanzwert, zeitwert, fremdobjekt, fremddatenbestand,
              kommunikationsobjekt, erzeugt_von_ereignis, geloescht_von_ereignis,
              hat_vorgaenger_hist_objekt, hat_nachfolger_hist_objekt, punktgeometrie,
              $5,
              abstand_zur_bestandsachse, abstand_zur_fahrbahnoberkante,
              $2,
              id_strasse
            FROM ukos_okstra.strassenelementpunkt
            WHERE id = $4
            RETURNING id
          '
          USING aenderungszeit, new_se_id, NEW.angelegt_von, sep.id, new_sep_station
          INTO new_sep_id;

          IF debug THEN RAISE NOTICE 'Neuen Strassenelementpunkt % mit Station % und Abstand: % angelegt.', new_sep_id, new_sep_station, sep.abstand_zur_bestandsachse; END IF;

        END LOOP;

      END LOOP;
      IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN NEW', TG_NAME, TG_TABLE_NAME; END IF;
      RETURN NEW;
    END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
