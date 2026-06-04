BEGIN;
  --DROP FUNCTION xplankonverter.bplan_anzeigename(varchar, xplan_gml.bp_planart[], varchar, varchar);

  CREATE OR REPLACE FUNCTION xplankonverter.bplan_anzeigename(name character varying, planart xplan_gml.bp_planart[], nummer character varying, gemeindename character varying, fassungsbezeichnung character varying)
  RETURNS text
  LANGUAGE sql
  AS $function$
      SELECT
        CASE
          WHEN COALESCE(NULLIF(trim(name), ''), planart[1]::text, nummer, gemeindename) IS NULL
            THEN NULL  
          WHEN planart[1]::text::integer  IN (1000,10000,10001) 
            THEN 'BPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  = 3000 
            THEN 'Vorhabensbezogener BPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  = 3100 
            THEN 'Vorhaben- und Erschliessungsplan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  IN (4000,40000,40001,40002)
            THEN 'Innenbereichssatzung' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  IN (5000)
            THEN 'Aussenbereichssatzung' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' ||gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  IN (7000)
            THEN 'Örtliche Bauvorschrift' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
          WHEN planart[1]::text::integer  IN (9999)
            THEN 'Sonstiger Plan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
					ELSE 'BPlan'
        END;
    $function$;

  --DROP FUNCTION xplankonverter.fplan_anzeigename(varchar, xplan_gml.fp_planart, varchar, varchar);

  CREATE OR REPLACE FUNCTION xplankonverter.fplan_anzeigename(name character varying, planart xplan_gml.fp_planart, nummer character varying, gemeindename character varying, fassungsbezeichnung character varying)
  RETURNS text
  LANGUAGE sql
  AS $function$
		SELECT
			CASE
			WHEN COALESCE(NULLIF(trim(name), ''), planart::text, nummer, gemeindename) IS NULL
				THEN NULL  
			WHEN planart::text::integer  = 1000 
				THEN 'FPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN planart::text::integer  = 2000 
				THEN 'Gemeinsamer FPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN planart::text::integer  = 4000 
				THEN 'FPlan Regionalplan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN planart::text::integer  = 5000 
				THEN 'Sachlicher Teilplan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN planart::text::integer  = 9999
				THEN 'Sonstiger FPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN planart::text::integer  = 3000 
				THEN 'Regionaler FPlan' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' ||gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			ELSE 'FPlan'
		END;
  $function$;

  -- DROP FUNCTION xplankonverter.soplan_anzeigename(varchar, xplan_gml.so_planart, varchar, varchar);

  CREATE OR REPLACE FUNCTION xplankonverter.soplan_anzeigename(name character varying, planart xplan_gml.so_planart, nummer character varying, gemeindename character varying, fassungsbezeichnung character varying)
  RETURNS text
  LANGUAGE sql
  AS $function$
		SELECT
			CASE 
			WHEN COALESCE(NULLIF(trim(name), ''), (planart).id::text, nummer, gemeindename) IS NULL
				THEN NULL
			WHEN (planart).id::integer  = 1300 
				THEN 'Stadtumbaugebiet' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' '  || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN (planart).id::integer  = 1999 
				THEN 'Erhaltungssatzung (SOS)' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN (planart).id::integer  = 1200 
				THEN 'Staedtebauliche Entwicklungsmassnahme' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN (planart).id::integer  = 1100 
				THEN 'Sanierungssatzung(SAS)' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
			WHEN (planart).id::integer  = 9999
				THEN 'Sonstige Bausatzung(SOS)' || CASE WHEN replace(coalesce(NULLIF(trim(nummer), ''), '0'), '0, ', '') = '0' THEN '' ELSE ' Nr. ' || replace(coalesce(nummer, '0'), '0, ', '') || ' ' END || ' ' || gemeindename || ' ' || name || CASE WHEN fassungsbezeichnung IS NOT NULL AND fassungsbezeichung != '' THEN (' ' || fassungsbezeichnung) ELSE '' END
				ELSE 'SOPlan'
		END;
  $function$;

COMMIT;