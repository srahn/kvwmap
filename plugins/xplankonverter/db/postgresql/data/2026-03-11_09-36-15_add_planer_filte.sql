BEGIN;
  UPDATE
    kvwmap.layer
  SET
    pfad = regexp_replace(
      pfad,
      '[ \t\r\n]+from(?!.*from)',
      E',\n  xplankonverter.is_planer($USER_ID) AND k.user_id != $USER_ID AS editiersperre,\n  p.gml_id AS oid,\n  k.layer_selection_id,\n  ''{"plan_gml_id":"'' || p.gml_id || ''"}'' AS layer_params\nFROM',
      'gi'
    )
  WHERE
    name = 'bp_plan' AND
    maintable = 'bp_plan' AND
    lower(pfad) NOT LIKE '%editiersperre%';

COMMIT;