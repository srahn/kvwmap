BEGIN;

  UPDATE
    layer l,
    used_layer ul
  SET
    l.queryable = '1',
    ul.queryable = '1'
  WHERE
    l.Layer_ID = ul.Layer_ID AND
    l.Name LIKE '%p_%';

COMMIT;
