BEGIN;

CREATE OR REPLACE function kvw_geomto3d(schema_name CHARACTER VARYING, table_name CHARACTER VARYING)
RETURNS void AS
$$
BEGIN
	EXECUTE 'ALTER TABLE ' || schema_name || '.' || table_name || ' ALTER COLUMN wkb_geometry TYPE geometry(GEOMETRYZ, 25833)';
END;
$$ LANGUAGE plpgsql VOLATILE COST 100;


DROP VIEW alkis.lk_so_punkte;
DROP VIEW alkis.lk_sp;
DROP VIEW alkis.lk_ap;
DROP VIEW alkis.lk_gebaeudepunkte;
DROP VIEW alkis.lk_grenzpunkte;

SELECT
  kvw_geomto3d(f_table_schema::CHARACTER VARYING, f_table_name::CHARACTER VARYING)
FROM
  geometry_columns
WHERE
  f_table_schema = 'alkis' AND
	(
		f_table_name LIKE 'ax_punktort%' OR
		f_table_name LIKE '%3d%'
	);

CREATE OR REPLACE VIEW alkis.lk_so_punkte AS 
SELECT
	o.oid,
	o.beginnt,
	o.endet,
	o.punktkennung,
	o.sonstigeeigenschaft,
	o.vermarkung_marke,
	l.beschreibung AS verm_bedeutung,
	p.genauigkeitsstufe,
	m.beschreibung AS punktgenauigkeit,
	p.vertrauenswuerdigkeit,
	n.beschreibung AS bedeutung,
	st_astext(p.wkb_geometry) AS koord,
	p.wkb_geometry
FROM
	alkis.ax_sonstigervermessungspunkt o LEFT JOIN
	alkis.ax_punktortau p ON o.gml_id = ANY (p.istteilvon) LEFT JOIN
	alkis.ax_marke l ON l.wert = o.vermarkung_marke LEFT JOIN
	alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe	LEFT JOIN
	alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

CREATE OR REPLACE VIEW alkis.lk_sp AS 
SELECT
	o.oid,
	o.punktkennung,
	o.beginnt,
	o.endet,
	o.sonstigeeigenschaft,
	o.vermarkung_marke,
	l.beschreibung AS verm_bedeutung,
	p.genauigkeitsstufe,
	m.beschreibung AS punktgenauigkeit,
	p.vertrauenswuerdigkeit,
	n.beschreibung AS bedeutung,
	rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord,
	p.wkb_geometry
FROM
	alkis.ax_sicherungspunkt o LEFT JOIN
	alkis.ax_punktortau p ON o.gml_id = ANY (p.istteilvon) LEFT JOIN
	alkis.ax_marke l ON l.wert = o.vermarkung_marke LEFT JOIN
	alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe LEFT JOIN
	alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

CREATE OR REPLACE VIEW alkis.lk_ap AS 
SELECT
	o.oid,
	o.gml_id,
	o.beginnt,
	o.endet,
	o.punktkennung,
	o.sonstigeeigenschaft,
	o.vermarkung_marke,
	l.beschreibung AS verm_bedeutung,
	p.genauigkeitsstufe,
	m.beschreibung AS punktgenauigkeit,
	p.vertrauenswuerdigkeit,
	n.beschreibung AS bedeutung,
	rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord,
	p.wkb_geometry
FROM
	alkis.ax_aufnahmepunkt o LEFT JOIN
	alkis.ax_punktortau p ON o.gml_id = ANY (p.istteilvon) LEFT JOIN
	alkis.ax_marke l ON l.wert = o.vermarkung_marke LEFT JOIN
	alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe LEFT JOIN
	alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

CREATE OR REPLACE VIEW alkis.lk_gebaeudepunkte AS 
SELECT DISTINCT
	o.oid,
	p.gml_id,
	o.beginnt,
	o.endet,
	o.punktkennung,
	m.wert,
	m.beschreibung AS punktgenauigkeit,
	p.vertrauenswuerdigkeit,
	n.beschreibung,
	p.wkb_geometry,
	rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord
FROM
	alkis.ax_besonderergebaeudepunkt o LEFT JOIN
	alkis.ax_punktortag p ON o.gml_id = ANY (p.istteilvon) LEFT JOIN
	alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe LEFT JOIN
	alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

CREATE OR REPLACE VIEW alkis.lk_grenzpunkte AS 
SELECT COALESCE(p.oid, o.oid) AS oid,
	o.gml_id,
	o.beginnt AS o_beginnt,
	o.endet AS o_endet,
	p.beginnt AS p_beginnt,
	p.endet AS p_endet,
	o.punktkennung,
	ltrim(to_char("substring"(o.punktkennung::text, 1, 9)::integer, '999 99 99 99'::text), ' '::text) AS nbz,
	ltrim("substring"(o.punktkennung::text, 10, 6), '0'::text) AS pnr,
	o.zustaendigestelle_stelle,
	d.bezeichnung AS zustaendige_stelle,
	o.abmarkung_marke,
	l.beschreibung AS verm_bedeutung,
	o.bemerkungzurabmarkung,
	ba.beschreibung AS bemerkung_zurabmarkung,
	o.relativehoehe,
	o.besonderepunktnummer,
  CASE
    WHEN o.festgestelltergrenzpunkt::text = 'true'::text THEN 'Ja'::text
    ELSE 'Nein'::text
  END AS festgestelltergrenzpunkt,
	array_to_string(o.sonstigeeigenschaft, ', '::text) AS sonstigeeigenschaft,
	o.zeitpunktderentstehung,
	o.zeigtauf,
	p.gml_id AS ax_punktortta_gml_id,
  CASE
    WHEN p.kartendarstellung::text = '1'::text THEN 'Ja'::text
    ELSE 'Nein'::text
  END AS kartendarstellung,
	p.koordinatenstatus,
	p.hinweise,
	rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS "position",
	p.herkunft_source_source_description[0] AS source_description,
	e.beschreibung AS datenerhebung,
	p.genauigkeitsstufe,
	m.beschreibung AS punktgenauigkeit,
	p.vertrauenswuerdigkeit,
	n.beschreibung AS punktvertrauenswuerdigkeit,
	p.wkb_geometry
FROM
 	alkis.ax_grenzpunkt o LEFT JOIN
	alkis.ax_punktortta p ON o.gml_id::text = ANY (p.istteilvon::text[]) LEFT JOIN
	alkis.ax_marke l ON l.wert = o.abmarkung_marke LEFT JOIN
	alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe LEFT JOIN
	alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit LEFT JOIN
	alkis.ax_bemerkungzurabmarkung_grenzpunkt ba ON o.bemerkungzurabmarkung = ba.wert LEFT JOIN
	alkis.ax_datenerhebung e ON e.wert = ANY (p.herkunft_source_source_description::integer[]) LEFT JOIN
	alkis.ax_dienststelle d ON o.zustaendigestelle_stelle::text = d.stelle::text
WHERE
  d.endet IS NULL;

COMMIT;
