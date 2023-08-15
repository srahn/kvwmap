BEGIN;

	UPDATE
		xplankonverter.mappingtable_gmlas_to_gml
	SET
		t_column = 'vongenehmigungausgenommen',
		t_data_type = 'boolean'
	WHERE
		t_column = 'boolean' AND
		t_data_type = 'vongenehmigungausgenommen';

	CREATE OR REPLACE FUNCTION xplankonverter.gml_id(
		gml_text text)
	RETURNS uuid
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT SPLIT_PART(gml_text, '_', 2)::uuid
	$BODY$;

	CREATE INDEX IF NOT EXISTS xp_ppo_dientzurdarstellungvon_idx ON xplan_gml.xp_ppo (xplankonverter.gml_id(dientzurdarstellungvon));

	DROP VIEW IF EXISTS xplankonverter.zusammenzeichnung_der_stellen;
	CREATE VIEW xplankonverter.zusammenzeichnung_der_stellen AS
	SELECT DISTINCT ON (k.stelle_id)
		k.stelle_id,
		k.id AS konvertierung_id,
		p.wirksamkeitsdatum
	FROM
	xplankonverter.konvertierungen k JOIN
	xplan_gml.fp_plan p ON k.id = p.konvertierung_id
	WHERE
		p.zusammenzeichnung
	ORDER BY
		k.stelle_id, p.wirksamkeitsdatum, k.id DESC;

	ALTER TABLE IF EXISTS xplan_gml.fp_verentsorgung ADD CONSTRAINT fp_verentsorgung_pkey PRIMARY KEY (gml_id);

COMMIT;