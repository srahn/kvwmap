BEGIN;
UPDATE `layer`
SET `Name` = 'bp_textabschnitt',
	`alias` = 'BP_TextAbschnitt',
	`pfad` = 'SELECT
  t.gml_id,
  t.schluessel,
  t.gesetzlichegrundlage,
  t.rechtscharakter,
  t.text,
  t.reftext,
  coalesce(t.schluessel, t.gml_id::text) AS vorschau,
	t.inverszu_texte_xp_plan AS plan_gml_id,
  t.inverszu_reftextinhalt_bp_objekt AS objekt_gml_id,
  p.name AS plan,
  t.konvertierung_id,
  k.stelle_id
FROM
  bp_textabschnitt t
  JOIN xplankonverter.konvertierungen k on t.konvertierung_id = k.id
  LEFT JOIN bp_plan p on p.gml_id::text = t.inverszu_texte_xp_plan
  LEFT JOIN bp_objekt o ON o.gml_id::text = t.inverszu_reftextinhalt_bp_objekt 
WHERE
  true'
WHERE lower(`Name`) LIKE 'bp%'
	AND lower(`Name`) LIKE '%textabschnitt%';
UPDATE `layer`
SET `Name` = 'fp_textabschnitt',
	`alias` = 'FP_TextAbschnitt',
	`pfad` = 'SELECT
  t.gml_id,
  t.schluessel,
  t.gesetzlichegrundlage,
  t.rechtscharakter,
  t.text,
  t.reftext,
  coalesce(t.schluessel, t.gml_id::text) AS vorschau,
	t.inverszu_texte_xp_plan AS plan_gml_id,
  t.inverszu_reftextinhalt_fp_objekt AS objekt_gml_id,
  p.name AS plan,
  t.konvertierung_id,
  k.stelle_id
FROM
  fp_textabschnitt t
  JOIN xplankonverter.konvertierungen k on t.konvertierung_id = k.id
  LEFT JOIN fp_plan p on p.gml_id::text = t.inverszu_texte_xp_plan
  LEFT JOIN fp_objekt o ON o.gml_id::text = t.inverszu_reftextinhalt_fp_objekt 
WHERE
  true'
WHERE lower(`Name`) LIKE 'fp%'
	AND lower(`Name`) LIKE '%textabschnitt%';
UPDATE `layer`
SET `Name` = 'so_textabschnitt',
	`alias` = 'SO_TextAbschnitt',
	`pfad` = 'SELECT
  t.gml_id,
  t.schluessel,
  t.gesetzlichegrundlage,
  t.rechtscharakter,
  t.text,
  t.reftext,
  coalesce(t.schluessel, t.gml_id::text) AS vorschau,
	t.inverszu_texte_xp_plan AS plan_gml_id,
  t.inverszu_reftextinhalt_so_objekt AS objekt_gml_id,
  p.name AS plan,
  t.konvertierung_id,
  k.stelle_id
FROM
  so_textabschnitt t
  JOIN xplankonverter.konvertierungen k on t.konvertierung_id = k.id
  LEFT JOIN so_plan p on p.gml_id::text = t.inverszu_texte_xp_plan
  LEFT JOIN so_objekt o ON o.gml_id::text = t.inverszu_reftextinhalt_so_objekt 
WHERE
  true'
WHERE lower(`Name`) LIKE 'so%'
	AND lower(`Name`) LIKE '%textabschnitt%';
UPDATE `layer`
SET `Name` = 'rp_textabschnitt',
	`alias` = 'RP_TextAbschnitt',
	`pfad` = 'SELECT
  t.gml_id,
  t.schluessel,
  t.gesetzlichegrundlage,
  t.rechtscharakter,
  t.text,
  t.reftext,
  coalesce(t.schluessel, t.gml_id::text) AS vorschau,
	t.inverszu_texte_xp_plan AS plan_gml_id,
  t.inverszu_reftextinhalt_rp_objekt AS objekt_gml_id,
  p.name AS plan,
  t.konvertierung_id,
  k.stelle_id
FROM
  rp_textabschnitt t
  JOIN xplankonverter.konvertierungen k on t.konvertierung_id = k.id
  LEFT JOIN rp_plan p on p.gml_id::text = t.inverszu_texte_xp_plan
  LEFT JOIN rp_objekt o ON o.gml_id::text = t.inverszu_reftextinhalt_rp_objekt 
WHERE
  true'
WHERE lower(`Name`) LIKE 'rp%'
	AND lower(`Name`) LIKE '%textabschnitt%';
UPDATE `layer`
SET `pfad` = REPLACE(
		`pfad`,
		'o.*',
		'o.*,
  o.gml_id AS objekt_gml_id'
	)
WHERE `Schema` LIKE 'xplan_gml'
	AND `Datentyp` IN (0, 1, 2)
	AND `maintable` NOT LIKE '%_plan'
	AND `maintable` NOT LIKE '%_bereich'
	AND `pfad` LIKE '%o.*%';
UPDATE `layer_attributes` la
	INNER JOIN `layer` l ON la.layer_id = l.Layer_ID
SET la.`form_element_type` = 'Textfeld'
WHERE l.`Name` LIKE '%_textabschnitt%'
	AND la.name = 'text';
UPDATE `layer`
SET pfad = REPLACE(
		REPLACE(
			REPLACE(
				REPLACE(
					REPLACE(
						REPLACE(
							REPLACE(
								pfad,
								'inverszu_reftextinhalt_bp_objekt',
								'inverszu_texte_xp_plan'
							),
							'inverszu_reftextinhalt_fp_objekt',
							'inverszu_texte_xp_plan'
						),
						'inverszu_reftextinhalt_rp_objekt',
						'inverszu_texte_xp_plan'
					),
					'inverszu_reftextinhalt_so_objekt',
					'inverszu_texte_xp_plan'
				),
				'coalesce(t.schluessel',
				'coalesce(t.text'
			),
			'p.gml_id::text',
			'p.gml_id'
		),
		't.inverszu_texte_xp_plan AS plan_gml_id',
		't.inverszu_texte_xp_plan AS plan_gml_id,
		t.inverszu_reftextinhalt_bp_objekt AS objekt_gml_id'
	)
WHERE `Name` LIKE '%_textabschnitt%';
COMMIT;