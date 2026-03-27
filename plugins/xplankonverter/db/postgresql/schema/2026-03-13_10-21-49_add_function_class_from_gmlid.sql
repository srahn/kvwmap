BEGIN;
CREATE OR REPLACE FUNCTION xplankonverter.get_xplan_object_class_from_gml_id(gml_id_of_object uuid)
	RETURNS text
	LANGUAGE 'sql'
	COST 1000
	STABLE PARALLEL UNSAFE
AS $BODY$
	SELECT 
		p.relname
	FROM
		xplan_gml.xp_objekt o
	INNER JOIN
		pg_class p
	ON
		p.oid = o.tableoid
	AND
		o.gml_id = gml_id_of_object
$BODY$;

ALTER FUNCTION xplankonverter.get_xplan_object_class_from_gml_id(uuid)
	OWNER TO kvwmap;
COMMIT;
