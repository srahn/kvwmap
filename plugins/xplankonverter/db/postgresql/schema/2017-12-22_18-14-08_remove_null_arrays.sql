BEGIN;

UPDATE xplan_gml.rp_plan SET auslegungstartdatum = NULL WHERE auslegungstartdatum = '{NULL}';
UPDATE xplan_gml.rp_plan SET auslegungenddatum = NULL WHERE auslegungenddatum = '{NULL}';
UPDATE xplan_gml.rp_plan SET traegerbeteiligungsstartdatum = NULL WHERE traegerbeteiligungsstartdatum = '{NULL}';
UPDATE xplan_gml.rp_plan SET traegerbeteiligungsenddatum = NULL WHERE traegerbeteiligungsenddatum = '{NULL}';

COMMIT;
