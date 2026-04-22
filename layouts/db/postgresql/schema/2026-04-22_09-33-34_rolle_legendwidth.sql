BEGIN;

ALTER TABLE kvwmap.rolle ADD legendwidth smallint DEFAULT 330 NOT NULL;

UPDATE kvwmap.rolle SET legendwidth = (SELECT (value::json->'layouts/gui.php'->'legend'->>'width')::int FROM kvwmap.config where name = 'sizes');

COMMIT;
