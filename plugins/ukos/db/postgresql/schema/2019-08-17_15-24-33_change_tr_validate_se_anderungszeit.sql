BEGIN;

  CREATE OR REPLACE FUNCTION ukos_okstra.validate_strassenelement()
  RETURNS trigger AS
  $BODY$
    DECLARE
      vp_beginnt_bei_geometrie  public.geometry(Point, 25833) = ST_StartPoint(NEW.liniengeometrie);
      vp_endet_bei_geometrie    public.geometry(Point, 25833) = ST_EndPoint(NEW.liniengeometrie);
      rec                        RECORD;
      tolerance                  NUMERIC;
      current_query text = current_query();
      sql                        text;
      aenderungszeit  TIMESTAMP WITH time zone = timezone('utc-1'::text, now());
      debug                      BOOLEAN = false;
    BEGIN
      --------------------------------------------------------------------------------------------------------
      -- Initialisierung
      EXECUTE 'SELECT value::NUMERIC FROM ukos_base.config WHERE key = $1' USING 'Topologietolerance' INTO tolerance;
      EXECUTE 'SELECT value FROM ukos_base.config WHERE key = $1' USING 'Debugmodus' INTO debug;
      IF debug THEN RAISE NOTICE 'Führe Trigger % on % aus.', TG_NAME, TG_TABLE_NAME; END IF;

      --------------------------------------------------------------------------------------------------------
      -- Exceptions

      -- Prüfe ob eine Liniengeometrie existiert
      IF NEW.liniengeometrie IS NULL THEN
        RAISE EXCEPTION 'Liniengeometrie leer. Strassenelement muss eine Liniengeometrie haben.';
      END IF;

      -- Prüfe ob eine stelle_id existiert
      IF TG_OP = 'INSERT' AND NEW.stelle_id IS NULL THEN
        RAISE EXCEPTION 'Stelle_id ist leer. Strassenelement muss eine stelle_id haben.';
      END IF;

      sql = FORMAT('
        SELECT
          id
        FROM
          ukos_okstra.strassenelement
        WHERE
          gueltig_bis > %2$L AND
          ST_Equals(%1$L, liniengeometrie)
      ', NEW.liniengeometrie, aenderungszeit);

      -- Prüfe ob Strassenelement mit gleicher Geometrie schon existiert
      FOR rec IN EXECUTE sql
      LOOP
        RAISE EXCEPTION 'Strassenelement id: % mit gleicher Liniengeometrie existiert schon.', rec.id;
      END LOOP;
      -- ToDo: Prüfen ob neues Strassenelement
      --        - vorhandenes Strassenelemnt schneidet
      --        - Verbindungspunkt schneidet
      --        - auf einem anderen Strassenelement liegt
      --------------------------------------------------------------------------------------------------------
      -- Berechnungen
      -- Berechnung der Länge
      IF debug THEN RAISE NOTICE 'Trigger % on Tabelle %: Berechne neue Länge für Strassenelement.', TG_NAME, TG_TABLE_NAME; END IF;
      NEW.laenge = ST_Length(NEW.liniengeometrie);
      PERFORM ukos_base.log(current_query, TG_NAME, TG_WHEN, TG_OP, format('%s.%s', TG_TABLE_SCHEMA, TG_TABLE_NAME), format('Berechne neue Länge für SE: %s', NEW.id));
      IF debug THEN RAISE NOTICE 'Beende Trigger % on % mit RETURN NEW.', TG_NAME, TG_TABLE_NAME; END IF;

      RETURN NEW;
    END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

COMMIT;
