BEGIN;
  CREATE OR REPLACE FUNCTION xplankonverter.is_planer(uid integer)
  RETURNS boolean
  LANGUAGE sql
  AS $function$
    SELECT EXISTS (
      SELECT 1
      FROM xplankonverter.planungsbuero_users
      WHERE user_id = uid
      limit 1
    );
  $function$;

  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN IF NOT EXISTS layer_selection_id integer;

  CREATE OR REPLACE FUNCTION xplankonverter.plan_anzeigename(name varchar, planart xplan_gml.bp_planart, nummer varchar, gemeindename varchar)
  RETURNS text
  LANGUAGE sql
  AS $$
    SELECT
      CASE  
        WHEN planart::text::integer  IN (1000,10000,10001) 
          THEN 'BPlan Nr. ' || nummer || ' ' || gemeindename || ' ' || name   
        WHEN planart::text::integer  = 3000 
          THEN 'Vorhabensbezogener BPlan Nr. ' || nummer || ' ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  = 3100 
          THEN 'Vorhaben- und Erschliessungsplan Nr. ' || nummer || ' ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (4000,40000,40001,40002) AND trim(nummer) != '0' 
          THEN 'Innenbereichssatzung Nr. ' || nummer || ' ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (4000,40000,40001,40002) AND trim(nummer) = '0' 
          THEN 'Innenbereichssatzung ' || gemeindename || ' ' || name   
        WHEN planart::text::integer  IN (5000) AND trim(nummer) != '0' 
          THEN 'Aussenbereichssatzung Nr. ' || nummer || ' ' ||gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (5000) AND trim(nummer) = '0' 
          THEN 'Aussenbereichssatzung ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (7000) AND trim(nummer) != '0' 
          THEN 'Örtliche Bauvorschrift Nr. ' || nummer || ' ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (7000) AND trim(nummer) = '0' 
          THEN 'Örtliche Bauvorschrift ' || gemeindename || ' ' || name 
        WHEN planart::text::integer  IN (9999) AND trim(nummer) != '0' 
          THEN 'Sonstiger Plan Nr. ' || nummer || ' ' || gemeindename || ' ' || name  
        WHEN planart::text::integer  IN (9999) AND trim(nummer) = '0' 
          THEN 'Sonstiger Plan ' || gemeindename || ' ' || name
          ELSE 'BPlan'
      END;
  $$;
  COMMENT ON FUNCTION xplankonverter.plan_anzeigename(varchar, xplan_gml."bp_planart", varchar, varchar) IS 'Erzeugt eine Bezeichnung für die Anzeige des Planes. Übergeben werden folgende Attribute des Plans: name, nummer, die erste planart planart[1] und gemeindename der ersten gemeinde (gemeinde[1]).gemeindename.';
COMMIT;