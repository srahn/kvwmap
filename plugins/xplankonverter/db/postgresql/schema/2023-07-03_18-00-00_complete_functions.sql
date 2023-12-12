BEGIN;
	CREATE OR REPLACE FUNCTION xplankonverter.complete_bp_allgartderbaulnutzung(
		allgartderbaulnutzung xplan_gml.xp_allgartderbaulnutzung,
		besondereartderbaulnutzung xplan_gml.xp_besondereartderbaulnutzung
	)
	RETURNS xplan_gml.xp_allgartderbaulnutzung
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			CASE
				WHEN allgartderbaulnutzung IS NOT NULL THEN allgartderbaulnutzung
				WHEN besondereartderbaulnutzung IS NULL THEN NULL
				WHEN besondereartderbaulnutzung::text::integer < 1400 THEN '1000'
				WHEN besondereartderbaulnutzung::text::integer < 1700 THEN '2000'
				WHEN besondereartderbaulnutzung::text::integer < 2000 THEN '3000'
				ELSE '4000'
			END::xplan_gml.xp_allgartderbaulnutzung	
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.complete_bp_allgartderbaulnutzung(xplan_gml.xp_allgartderbaulnutzung, xplan_gml.xp_besondereartderbaulnutzung) IS 'Ergänzt Attribut allgartderbaulnutzung der Klasse bp_baugebietstailflaeche nach Konformitätsbedingung 4.5.1.2 wenn es leer ist aber besondereartderbaulnutzung angegeben.';

	CREATE OR REPLACE FUNCTION xplankonverter.complete_bp_besondereartderbaulnutzung(
		besondereartderbaulnutzung xplan_gml.xp_besondereartderbaulnutzung,
		sondernutzung xplan_gml.xp_sondernutzungen[]
	)
	RETURNS xplan_gml.xp_besondereartderbaulnutzung
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			CASE
				WHEN besondereartderbaulnutzung IS NOT NULL THEN besondereartderbaulnutzung
				WHEN sondernutzung IS NULL THEN NULL
				WHEN sondernutzung[0]::text::integer < 1500 THEN '2000'
				ELSE '2100'
			END::xplan_gml.xp_besondereartderbaulnutzung	
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.complete_bp_besondereartderbaulnutzung(xplan_gml.xp_besondereartderbaulnutzung, xplan_gml.xp_sondernutzungen[]) IS 'Ergänzt Attribut besondereartderbaulnutzung der Klasse bp_baugebietstailflaeche nach Konformitätsbedingung 4.5.1.3 wenn es leer ist aber sondernutzung angegeben.';

	CREATE OR REPLACE FUNCTION xplankonverter.complete_fp_allgartderbaulnutzung(
		allgartderbaulnutzung xplan_gml.xp_allgartderbaulnutzung,
		besondereartderbaulnutzung xplan_gml.xp_besondereartderbaulnutzung
	)
	RETURNS xplan_gml.xp_allgartderbaulnutzung
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			CASE
				WHEN allgartderbaulnutzung IS NOT NULL THEN allgartderbaulnutzung
				WHEN besondereartderbaulnutzung IS NULL THEN NULL
				WHEN besondereartderbaulnutzung::text::integer < 1400 THEN '1000'
				WHEN besondereartderbaulnutzung::text::integer < 1700 THEN '2000'
				WHEN besondereartderbaulnutzung::text::integer < 2000 THEN '3000'
				ELSE '4000'
			END::xplan_gml.xp_allgartderbaulnutzung	
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.complete_fp_allgartderbaulnutzung(xplan_gml.xp_allgartderbaulnutzung, xplan_gml.xp_besondereartderbaulnutzung) IS 'Ergänzt Attribut allgartderbaulnutzung der Klasse fp_bebauungsflaeche nach Konformitätsbedingung 5.3.1.1 wenn es leer ist aber besondereartderbaulnutzung angegeben.';

	CREATE OR REPLACE FUNCTION xplankonverter.complete_fp_besondereartderbaulnutzung(
		besondereartderbaulnutzung xplan_gml.xp_besondereartderbaulnutzung,
		sondernutzung xplan_gml.xp_sondernutzungen[]
	)
	RETURNS xplan_gml.xp_besondereartderbaulnutzung
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			CASE
				WHEN besondereartderbaulnutzung IS NOT NULL THEN besondereartderbaulnutzung
				WHEN sondernutzung IS NULL THEN NULL
				WHEN sondernutzung[0]::text::integer < 1500 THEN '2000'
				ELSE '2100'
			END::xplan_gml.xp_besondereartderbaulnutzung	
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.complete_fp_besondereartderbaulnutzung(xplan_gml.xp_besondereartderbaulnutzung, xplan_gml.xp_sondernutzungen[]) IS 'Ergänzt Attribut besondereartderbaulnutzung der Klasse fp_baugebietstailflaeche nach Konformitätsbedingung 5.3.1.2 wenn es leer ist aber sondernutzung angegeben.';

	/*
	Brauche noch Funktion, die stylesheetid ausgibt wenn leer 
	geg: Attribute der Klasse und des ppo, stylesheetlistentabellenname
	Fragen:
	  - Alle Attribute übergeben oder nur die ggf. benötigt werden? Wohl besser alles zu übergeben
		- Eine Funktion für alle Fachklassen? Dann auch Name der Fachklasse mit übergeben
		- Oder eine Funktion pro Fachklasse. ggf. auch je eine pro Fachklasse und po-Klassentyp bzw. pro Stylesheetzuordnung
		  Diese Funktionen ließen sich dann aus der Tabelle xplanung.stylesheetzuordnung heraus per Script erzeugen

SELECT
  fo.gml_id,
  fo.bauweise,
  xplankonverter.complete_bp_allgartderbaulnutzung(
    fo.allgartderbaulnutzung,
    fo.besondereartderbaulnutzung
  ) AS allgartderbaulnutzung,
  xplankonverter.complete_bp_besondereartderbaulnutzung(
    fo.besondereartderbaulnutzung,
    fo.sondernutzung
  ) AS besondereartderbaulnutzung,
  fo.gliederung1,
  po.gml_id,
  po.stylesheetid,
  po.art,
  po.index,
  po.position
FROM
  xplan_gml.bp_baugebietsteilflaeche fo JOIN
  xplan_gml.xp_ppo po ON po.dientzurdarstellungvon LIKE '%' || fo.gml_id::text || '%'
WHERE
  fo.gml_id = 'ce6bca34-6603-4afb-94d0-be5ecd094169'

	*/
COMMIT;