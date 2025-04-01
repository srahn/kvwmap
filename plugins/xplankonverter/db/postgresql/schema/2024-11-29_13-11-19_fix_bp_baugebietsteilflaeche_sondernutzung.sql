BEGIN;
	UPDATE
		xplankonverter.mappingtable_gmlas_to_gml
	SET
		regel = 'CASE WHEN pg_typeof(gmlas.sondernutzung)::text = ''character varying'' THEN NULLIF(ARRAY[sondernutzung]::xplan_gml.xp_sondernutzungen[], array[NULL]::xplan_gml.xp_sondernutzungen[])
			ELSE NULLIF(gmlas.sondernutzung::xplan_gml.xp_sondernutzungen[], array[NULL]::xplan_gml.xp_sondernutzungen[])
			END AS sondernutzung'
	WHERE
		regel = 'gmlas.sondernutzung::xplan_gml.xp_sondernutzungen AS sondernutzung' AND
		t_column = 'sondernutzung' AND
		o_table = 'bp_baugebietsteilflaeche';
COMMIT;