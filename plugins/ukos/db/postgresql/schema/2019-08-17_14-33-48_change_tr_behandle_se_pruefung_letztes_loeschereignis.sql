BEGIN;

  -- DROP FUNCTION ukos_okstra.behandle_se();
  CREATE OR REPLACE FUNCTION ukos_okstra.behandle_se()
  RETURNS trigger AS
  $BODY$
    DECLARE
      aenderungszeit TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
      tolerance      NUMERIC;
      num_se         INTEGER;
      se             RECORD;
      se1            RECORD;
      se2            RECORD;
      sql            TEXT;
      msg            Text[];
      debug          BOOLEAN = false;
    BEGIN
      -- Bedingung
      /**
      * Muss nach pruefe_abhaengigkeiten aufgerufen werden, weil hier nicht geprüft wird ob id '00000000-0000-0000-0000-000000000000' oder
      * geloescht_von_ereignis NOT NULL und Anzahl SE > 2
      */

      -- Initialisiere
      EXECUTE 'SELECT value FROM ukos_base.config WHERE key = $1' USING 'Debugmodus' INTO debug;
      EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
      IF debug THEN RAISE NOTICE 'Führe Funktion ukos_okstra.behandle_se in Trigger % on % aus', TG_NAME, TG_TABLE_NAME; END IF;

      -- Frage die Anzahl der gültigen am Verbindungspunkt hängenden Strassenelemente ab
      EXECUTE FORMAT('
        SELECT
          count(id)
        FROM ukos_okstra.strassenelement
        WHERE
          gueltig_bis > %1$L AND
          (beginnt_bei_vp = %2$L OR endet_bei_vp = %2$L)
      ', aenderungszeit, OLD.id)
      INTO num_se;

      IF num_se = 0 THEN
        IF debug THEN RAISE NOTICE 'Es sind keine Strassenelemente vom Löschen des Verbindungspunktes % betroffen.', OLD.ident; END IF;
        IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN OLD', TG_NAME, TG_TABLE_NAME; END IF;
        RETURN OLD;
      END IF;

      IF OLD.geloescht_von_ereignis IS NOT NULL AND OLD.geloescht_von_ereignis NOT LIKE '' THEN
        IF debug THEN RAISE NOTICE 'Verbindungspunkt % nicht löschen nach Löschung des Strassenelementes.', OLD.ident; END IF;
        IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN NULL', TG_NAME, TG_TABLE_NAME; END IF;
        RETURN NULL;
      END IF;

      IF num_se = 1 THEN
        EXECUTE FORMAT('
          SELECT id, ident
          FROM ukos_okstra.strassenelement
          WHERE
            gueltig_bis > %1$L AND
            (beginnt_bei_vp = %2$L OR endet_bei_vp = %2$L)
        ', aenderungszeit, OLD.id)
        INTO se;
        IF debug THEN RAISE NOTICE 'Das Strassenelement % welches am Verbindungspunkt % hängt wird gelöscht.', se.ident, OLD.ident; END IF;

        EXECUTE FORMAT('
          UPDATE
            ukos_okstra.strassenelement
          SET
            geloescht_von_ereignis = %1$L
          WHERE
            id = %2$L
        ', 'Verbindungspunkt ' || OLD.ident || ' gelöscht ID:' || OLD.id, se.id);

        EXECUTE FORMAT('
          DELETE FROM ukos_okstra.strassenelement
          WHERE id = %1$L
        ', se.id);
      ELSE
        IF debug THEN RAISE NOTICE 'Am Verbindungspunkt % hängen noch zwei Strassenelemente. Diese werden verschmolzen.', OLD.ident; END IF;
        sql = FORMAT('
          SELECT
            (n.e).sequence i,
            se.id id,
            se.angelegt_von,
            se.stelle_id,
            CASE WHEN (n.e).edge > 1 THEN 1 ELSE -1 END richtung,
            se.liniengeometrie,
            ST_Length(se.liniengeometrie) length,
            CASE WHEN (n.e).edge < 0 THEN se.beginnt_bei_vp ELSE se.endet_bei_vp END AS vp,
            '''' AS beginnt_bei_vp,
            '''' AS endet_bei_vp
          FROM
            ukos_topo.edge_data ed JOIN
            (
              SELECT
                topology.GetNodeEdges(''ukos_topo'', topology.GetNodeByPoint(''ukos_topo'', punktgeometrie, %2$s)) e
              FROM
                ukos_okstra.verbindungspunkt
              WHERE id = %1$L
            ) n ON ed.edge_id = abs((n.e).edge) JOIN
            (
              SELECT
                id,
                beginnt_bei_vp,
                endet_bei_vp,
                angelegt_von,
                stelle_id,
                liniengeometrie,
                (topology.GetTopoGeomElements(liniengeometrie_topo))[1] AS element_id
              FROM
                ukos_okstra.strassenelement
            ) se ON abs((n.e).edge) = se.element_id
          ORDER BY
            (n.e).sequence
        ', OLD.id, tolerance);
        FOR se IN EXECUTE sql
        LOOP
          IF se.i = 1 THEN
            se1 = se;
          ELSE
            se2 = se;
          END IF;
        END LOOP;

        IF se1.richtung = se2.richtung THEN
          IF se1.length <= se2.length THEN
            se1.liniengeometrie = ST_Reverse(se1.liniengeometrie);
            se1.richtung = se1.richtung * -1;
          ELSE
            se2.liniengeometrie = ST_Reverse(se2.liniengeometrie);
            se1.richtung = se1.richtung * -1;
          END IF;
        END IF;
        IF se1.richtung > 0 THEN
          -- s1 abgehend, Richtung verläuft von s2 nach s1
          se.liniengeometrie = ST_MakeLine(se2.liniengeometrie, se1.liniengeometrie);
          se.beginnt_bei_vp = se2.vp;
          se.endet_bei_vp = se1.vp;
        ELSE
          -- s1 ankommend, Richtung verläuft von s1 nach s2
          se.liniengeometrie = ST_MakeLine(se1.liniengeometrie, se2.liniengeometrie);
          se.beginnt_bei_vp = se1.vp;
          se.endet_bei_vp = se2.vp;
        END IF;

        -- Lösche se1.id und se2.id
        EXECUTE FORMAT('
          UPDATE
            ukos_okstra.strassenelement
          SET
            geloescht_von_ereignis = %1$L
          WHERE
            id = %2$L
        ', 'Strassenelement verschmolzen', se1.id);
        EXECUTE FORMAT('
          DELETE FROM ukos_okstra.strassenelement
          WHERE id = %1$L
        ', se1.id);
        EXECUTE FORMAT('
          UPDATE
            ukos_okstra.strassenelement
          SET
            geloescht_von_ereignis = %1$L
          WHERE
            id = %2$L
        ', 'Strassenelement verschmolzen', se2.id);
        EXECUTE FORMAT('
          DELETE FROM ukos_okstra.strassenelement
          WHERE id = %1$L
        ', se2.id);

        -- Erzeuge neues Strassenelement mit se.liniengeometrie
        EXECUTE FORMAT('
          INSERT INTO ukos_okstra.strassenelement (angelegt_am, angelegt_von, stelle_id, liniengeometrie) VALUES
          (%1$L, %2$L, %3$s, %4$L)
        ', aenderungszeit, se1.angelegt_von, se1.stelle_id, se.liniengeometrie);
        IF debug THEN RAISE NOTICE 'Lege neues verschmolzenes Strassenelement mit liniengeometrie % an.', ST_ASText(se.liniengeometrie); END IF;
      END IF;
      IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN NULL', TG_NAME, TG_TABLE_NAME; END IF;
      RETURN OLD;
    END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
