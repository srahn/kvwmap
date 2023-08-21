BEGIN;
	-- Lösche textabschnitte, für die es keine Konvertierung mehr gibt
	DELETE FROM
		xplan_gml.xp_textabschnitt tl
	USING
	(
	SELECT DISTINCT
		ta.konvertierung_id
	FROM
		xplan_gml.xp_textabschnitt ta LEFT JOIN
		xplankonverter.konvertierungen k ON ta.konvertierung_id = k.id
	WHERE
		k.id IS NULL
	ORDER BY ta.konvertierung_id
	) d
	WHERE
		tl.konvertierung_id = d.konvertierung_id;

	-- Lösche Zuordnungen zwischen bp_objekten und bp_textabschnitten zu bp_textabschnitten die es nicht mehr gibt.
	DELETE FROM
		xplan_gml.bp_objekt_zu_bp_textabschnitt o2t
	USING
	(
		SELECT
			ot.bp_objekt_gml_id,
			ob.gml_id,
			ot.bp_textabschnitt_gml_id,
			ta.gml_id
		FROM
			xplan_gml.bp_objekt_zu_bp_textabschnitt ot
			LEFT JOIN xplan_gml.bp_textabschnitt ta ON ot.bp_textabschnitt_gml_id = ta.gml_id::text
			LEFT JOIN xplan_gml.bp_objekt ob ON ot.bp_objekt_gml_id::text = ob.gml_id::text
		WHERE
			ta.gml_id IS NULL OR
			ob.gml_id IS NULL
	) d
	WHERE
		o2t.bp_objekt_gml_id = d.bp_objekt_gml_id AND
		o2t.bp_textabschnitt_gml_id = d.bp_textabschnitt_gml_id;

	-- reftextinhalt für bp_objekt
	CREATE INDEX IF NOT EXISTS fki_inverszu_texte_xp_plan_fkey
		ON xplan_gml.xp_textabschnitt USING btree (inverszu_texte_xp_plan);

	ALTER TABLE IF EXISTS xplan_gml.bp_objekt
		ADD CONSTRAINT bp_objekt_gml_id_pkey PRIMARY KEY (gml_id);

	ALTER TABLE IF EXISTS xplan_gml.bp_objekt_zu_bp_textabschnitt
		ALTER COLUMN bp_objekt_gml_id TYPE uuid USING bp_objekt_gml_id::uuid;

	-- reftextinhalt für fp_objekt
	ALTER TABLE IF EXISTS xplan_gml.fp_objekt
		ADD CONSTRAINT fp_objekt_gml_id_pkey PRIMARY KEY (gml_id);

	-- reftextinhalt für so_objekt
	ALTER TABLE IF EXISTS xplan_gml.so_objekt
		ADD CONSTRAINT so_objekt_gml_id_pkey PRIMARY KEY (gml_id);

	-- reftextinhalt für rp_objekt
	ALTER TABLE IF EXISTS xplan_gml.rp_objekt
		ADD CONSTRAINT rp_objekt_gml_id_pkey PRIMARY KEY (gml_id);

	-- abweichungtext für bp_wohngebaeudeflaeche
	ALTER TABLE IF EXISTS xplan_gml.bp_textabschnitt
		ADD COLUMN inverszu_abweichungtext_bp_wohngebaeudeflaeche uuid;
-- Wenn die inverszu Beziehung genutzt werden soll, müsste die 1:n Beziehung zu den beiden Klassen auf array umgestellt werden.
--		,ALTER COLUMN inverszu_abweichungtext_bp_baugebietsteilflaeche TYPE uuid[] USING STRING_TO_ARRAY(inverszu_abweichungtext_bp_baugebietsteilflaeche, ',')::uuid[]
--		,ALTER COLUMN inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche TYPE uuid[] USING STRING_TO_ARRAY(inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche, ',')::uuid[]
-- bis dahin werden die Tabellen bp_baugebietsteilflaeche_zu_bp_textabschnitt und bp_nebenanlagenausschlussflaeche_zu_bp_textabschnitt verwendet.

	CREATE OR REPLACE FUNCTION xplankonverter.gml_id(gml_text text)
		RETURNS uuid
		LANGUAGE 'sql'
		COST 100
		STABLE PARALLEL UNSAFE
		AS $BODY$
			SELECT SPLIT_PART(gml_text, '_', 2)::uuid
		$BODY$;

COMMIT;
