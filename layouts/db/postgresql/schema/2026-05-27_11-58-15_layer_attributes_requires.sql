BEGIN;

UPDATE kvwmap.layer_attributes la
SET options = regexp_replace(
    la.options,
    '(?<!'')(<requires>(' ||
    regexp_replace(sa.name, '([.[\]()*+?^$|\\])', '\\\1', 'g') ||
    ')</requires>)(?!'')',
    '''\1''',
    'g'
)
FROM kvwmap.layer_attributes sa
WHERE sa.layer_id = la.layer_id
  AND sa.type IN (
      'varchar',
      'text',
      'character varying',
      'char',
      'character'
  );

COMMIT;
