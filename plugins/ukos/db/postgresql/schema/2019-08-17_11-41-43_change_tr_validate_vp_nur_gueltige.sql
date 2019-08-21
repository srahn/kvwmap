BEGIN;

  CREATE OR REPLACE FUNCTION ukos_okstra.validate_verbindungspunkt()
  RETURNS trigger AS
  $BODY$
    DECLARE
      rec        RECORD;
      tolerance  NUMERIC;
      debug      BOOLEAN = false;
      sql        TEXT;
      aenderungszeit  TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
    BEGIN

      --------------------------------------------------------------------------------------------------------
      -- Initialisierung
      EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
      EXECUTE 'SELECT value FROM ukos_base.config WHERE key = $1' USING 'Debugmodus' INTO debug;
      IF debug THEN RAISE NOTICE 'Führe Trigger % on % aus', TG_NAME, TG_TABLE_NAME; END IF;

      --------------------------------------------------------------------------------------------------------
      -- Exceptions
      -- Prüfe ob eine Punktgeometrie existiert
      IF NEW.punktgeometrie IS NULL THEN
        RAISE EXCEPTION 'Punktgeometrie leer. Verbindungspunkt muss eine Punktgeometrie haben.';
      END IF;

      -- Prüfe ob eine stelle_id existiert
      IF TG_OP = 'INSERT' AND NEW.stelle_id IS NULL THEN
        RAISE EXCEPTION 'Stelle_id ist leer. Strassenelement muss eine stelle_id haben.';
      END IF;

      sql = FORMAT('
        SELECT
          id,
          punktgeometrie,
          ST_Equals(%1$L, punktgeometrie) punkt_gleich
        FROM
          ukos_okstra.verbindungspunkt
        WHERE
          gueltig_bis > %3$L AND
          ST_Distance(punktgeometrie, %1$L) < %2$s
        ORDER BY
          ST_Distance(punktgeometrie, %1$L)
        LIMIT 1
      ', NEW.punktgeometrie, tolerance, aenderungszeit);

      -- Prüfe ob schon ein Verbindungspunkt mit Tolerance-Abstand existiert
      FOR rec IN EXECUTE sql LOOP
        RAISE EXCEPTION 'Verbindungspunkt % im Abstand von % existiert schon.', rec.id, tolerance;
      END LOOP;
      IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN NEW', TG_NAME, TG_TABLE_NAME; END IF;
      RETURN NEW;
    END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
