BEGIN;

ALTER TABLE kvwmap.layer_attributes
ADD COLUMN visibility_rules jsonb;

UPDATE kvwmap.layer_attributes
SET visibility_rules = jsonb_build_object(
  'logic', 'AND',
  'rules', jsonb_build_array(
    jsonb_build_object(
      'attribute', vcheck_attribute,
      'operator', vcheck_operator,
      'value',
        CASE
          WHEN vcheck_operator = 'IN'
            THEN to_jsonb(string_to_array(vcheck_value, '|'))
          ELSE to_jsonb(vcheck_value)
        END
    )
  )
)
WHERE vcheck_attribute IS NOT NULL AND vcheck_attribute != '';

ALTER TABLE kvwmap.datatype_attributes
ADD COLUMN visibility_rules jsonb;

UPDATE kvwmap.datatype_attributes
SET visibility_rules = jsonb_build_object(
  'logic', 'AND',
  'rules', jsonb_build_array(
    jsonb_build_object(
      'attribute', vcheck_attribute,
      'operator', vcheck_operator,
      'value',
        CASE
          WHEN vcheck_operator = 'IN'
            THEN to_jsonb(string_to_array(vcheck_value, '|'))
          ELSE to_jsonb(vcheck_value)
        END
    )
  )
)
WHERE vcheck_attribute IS NOT NULL AND vcheck_attribute != '';

COMMIT;
