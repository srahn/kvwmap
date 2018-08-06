BEGIN;

CREATE OR REPLACE FUNCTION xplankonverter.switch_rules_and_trigger(switch CHARACTER varying)
	RETURNS BOOLEAN AS
$BODY$ 
DECLARE
	sql text;
	r character varying;
	rules character varying[] = ARRAY[
		'check_auslegungsdatum_on_insert',
		'check_auslegungsdatum_on_update',
		'check_inkrafttretensdatum_on_insert',
		'check_inkrafttretensdatum_on_update'
	];
BEGIN
	for r IN SELECT unnest(rules)
	LOOP
		RAISE NOTICE '% RULE %', switch, r;
		EXECUTE 'ALTER TABLE xplan_gml.bp_plan ' || switch || ' RULE ' || r;
		EXECUTE 'ALTER TABLE xplan_gml.bp_plan ' || switch || ' TRIGGER ALL';
	END LOOP;
	RETURN true;
END;
$BODY$
	LANGUAGE plpgsql VOLATILE
	COST 100;

COMMIT;
