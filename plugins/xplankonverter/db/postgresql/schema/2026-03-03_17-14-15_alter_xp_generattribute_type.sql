BEGIN;
  DO $$
  BEGIN
    IF NOT EXISTS (
      SELECT 1
      FROM pg_type t
      JOIN pg_namespace n ON n.oid = t.typnamespace
      WHERE t.typname = 'xp_generattributtyp'
        AND n.nspname = 'xplan_gml'
    ) THEN
      CREATE TYPE xplan_gml.xp_generattributtyp AS ENUM ('XP_StringAttribut', 'XP_IntegerAttribut', 'XP_DoubleAttribut', 'XP_URLAttribut', 'XP_DatumAttribut');
    END IF;
  END$$;

  DO $$
  BEGIN
    IF NOT EXISTS (
      SELECT 1
      FROM pg_type t
      JOIN pg_namespace n ON n.oid = t.typnamespace
      WHERE t.typname = 'xp_generattribut_erweitert'
        AND n.nspname = 'xplan_gml'
    ) THEN
      CREATE TYPE xplan_gml.xp_generattribut_erweitert AS (
        "name" varchar,
        "wert" varchar,
        "typ" xplan_gml.xp_generattributtyp
      );
      COMMENT ON TYPE xplan_gml.xp_generattribut_erweitert IS 'Erweitert xp_generattribut um den wert und den typ.';
    END IF;
  END$$;


  CREATE OR REPLACE FUNCTION xplankonverter.to_xp_generattribut_erweitert(
    xp_generattribut xplan_gml.xp_generattribut[]
  )
  RETURNS xplan_gml.xp_generattribut_erweitert[]
  LANGUAGE plpgsql
  AS $$
  DECLARE
    result xplan_gml.xp_generattribut_erweitert[] := '{}';
    elem xplan_gml.xp_generattribut;
    attribut_wert varchar := '';
    attribut_type xplan_gml.xp_generattributtyp := 'XP_StringAttribut'::xplan_gml.xp_generattributtyp;
  BEGIN
    IF xp_generattribut IS NULL THEN
      RETURN NULL;
    END IF;
    FOREACH elem IN ARRAY xp_generattribut LOOP
      result := array_append(
        result,
        (elem.name, attribut_wert, attribut_type)::xplan_gml.xp_generattribut_erweitert
      );
    END LOOP;
    RETURN result;
  END;
  $$;

  --SELECT hatgenerattribut, xplankonverter.to_xp_generattribut_erweitert(hatgenerattribut) FROM xplan_gml.bp_plan WHERE hatgenerattribut IS NOT NULL;
  
  DO $$
  BEGIN
    IF NOT EXISTS (
      SELECT 1
      FROM information_schema.columns
      WHERE table_schema = 'xplan_gml'
        AND table_name = 'xp_plan'
        AND column_name = 'hatgenerattribut'
        AND udt_name = '_xp_generattribut_erweitert'
    ) THEN
      DROP VIEW xplan_gml.bp_plan_v;

      ALTER TABLE xplan_gml.xp_plan ALTER COLUMN hatgenerattribut TYPE xplan_gml.xp_generattribut_erweitert[] USING xplankonverter.to_xp_generattribut_erweitert(hatgenerattribut);

      CREATE OR REPLACE VIEW xplan_gml.bp_plan_v
      AS SELECT bp_plan.gml_id,
        bp_plan.name,
        bp_plan.nummer,
        bp_plan.internalid,
        bp_plan.beschreibung,
        bp_plan.kommentar,
        bp_plan.technherstelldatum,
        bp_plan.genehmigungsdatum,
        bp_plan.untergangsdatum,
        bp_plan.aendert,
        bp_plan.wurdegeaendertvon,
        bp_plan.erstellungsmassstab,
        bp_plan.bezugshoehe,
        bp_plan.raeumlichergeltungsbereich,
        bp_plan.verfahrensmerkmale,
        bp_plan.hatgenerattribut,
        bp_plan.user_id,
        bp_plan.created_at,
        bp_plan.updated_at,
        bp_plan.konvertierung_id,
        bp_plan.texte,
        bp_plan.begruendungstexte,
        bp_plan.externereferenz,
        bp_plan.inverszu_verbundenerplan_xp_verbundenerplan,
        bp_plan.technischerplanersteller,
        bp_plan.veraenderungssperredatum,
        bp_plan.gemeinde,
        bp_plan.verfahren,
        bp_plan.inkrafttretensdatum,
        bp_plan.durchfuehrungsvertrag,
        bp_plan.staedtebaulichervertrag,
        bp_plan.rechtsverordnungsdatum,
        bp_plan.rechtsstand,
        bp_plan.hoehenbezug,
        bp_plan.aufstellungsbeschlussdatum,
        bp_plan.ausfertigungsdatum,
        bp_plan.satzungsbeschlussdatum,
        bp_plan.veraenderungssperre,
        bp_plan.auslegungsenddatum,
        bp_plan.sonstplanart,
        bp_plan.gruenordnungsplan,
        bp_plan.plangeber,
        bp_plan.auslegungsstartdatum,
        bp_plan.traegerbeteiligungsstartdatum,
        bp_plan.aenderungenbisdatum,
        bp_plan.status,
        bp_plan.traegerbeteiligungsenddatum,
        bp_plan.planart,
        bp_plan.erschliessungsvertrag,
        bp_plan.bereich,
        bp_plan.versionbaunvodatum,
        bp_plan.versionbaunvotext,
        bp_plan.versionbaugbdatum,
        bp_plan.versionbaugbtext,
        bp_plan.versionsonstrechtsgrundlagedatum,
        bp_plan.versionsonstrechtsgrundlagetext,
        bp_plan.planaufstellendegemeinde,
        bp_plan.veraenderungssperrebeschlussdatum,
        bp_plan.veraenderungssperreenddatum,
        bp_plan.verlaengerungveraenderungssperre,
        bp_plan.zusammenzeichnung,
        xplan_gml.alle_verbundenen_plaene(bp_plan.gml_id) AS alle_verbundenen_plaene
      FROM xplan_gml.bp_plan;
    END IF;
  END
  $$;
COMMIT;