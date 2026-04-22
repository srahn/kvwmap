BEGIN;
  UPDATE 
    "kvwmap"."datatype_attributes"
  SET
    "tooltip" = '{
  "href": "index.php?go=show_snippet&snippet=hilfe#Externereferenztypen",
  "title" : "Typ / Inhalt des referierten Dokuments oder Rasterplans. Klick für detailierte Beschreibung",
  "target": "_blank"
}'
  WHERE
    "name" = 'typ' AND
    "options" LIKE '%enum_xp_externereferenztyp%';

  DO $$
    DECLARE
      admin_stelle_id INT;
      new_menue_id INT;
    BEGIN
      SELECT id INTO admin_stelle_id FROM kvwmap.stelle WHERE bezeichnung = 'Administration' LIMIT 1;

      INSERT INTO kvwmap.u_menues (name, links, obermenue, menueebene, "order", title)
      SELECT
        'Layerparameter',
        'index.php?go=Layer_Parameter',
        (SELECT id FROM kvwmap.u_menues WHERE name LIKE 'Layerverwaltung' LIMIT 1),
        2,
        70,
        'Layerparameter Anzeigen und Ändern'
      WHERE NOT EXISTS (
        SELECT 1 FROM kvwmap.u_menues WHERE name LIKE 'Layerparameter%'
      );

      SELECT id INTO new_menue_id
      FROM kvwmap.u_menues
      WHERE name LIKE 'Layerparameter%'
      LIMIT 1;

      INSERT INTO kvwmap.u_menue2stelle (stelle_id, menue_id)
      SELECT
        admin_stelle_id,
        new_menue_id
      WHERE NOT EXISTS (
        SELECT 1 FROM kvwmap.u_menue2stelle WHERE stelle_id = admin_stelle_id AND menue_id = new_menue_id
      );

      INSERT INTO kvwmap.u_menue2rolle (user_id, stelle_id, menue_id, status)
      SELECT
        user_id,
        stelle_id,
        new_menue_id,
        0
      FROM
        kvwmap.rolle
      WHERE
        stelle_id = admin_stelle_id AND
        NOT EXISTS (
          SELECT 1 FROM kvwmap.u_menue2rolle WHERE stelle_id = admin_stelle_id AND menue_id = new_menue_id
        );
    END
  $$;

  INSERT INTO kvwmap.layer_parameter (key, alias, options_sql, multiple, default_value)
  VALUES ('plan_gml_id', 'Plan-ID', 'SELECT gml_id AS value, xplankonverter.plan_anzeigename(name, nummer, planart[1], (gemeinde[1]).gemeindename) AS output FROM xplan_gml.xp_plan', false, '');

COMMIT;