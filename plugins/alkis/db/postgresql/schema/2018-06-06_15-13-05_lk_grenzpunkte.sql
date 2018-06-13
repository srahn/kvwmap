BEGIN;

DROP VIEW alkis.lk_grenzpunkte;

CREATE OR REPLACE VIEW alkis.lk_grenzpunkte AS
	SELECT o.oid,
	o.gml_id,
	o.punktkennung,
	ltrim(to_char("substring"(o.punktkennung::text, 1, 9)::integer, '999 99 99 99'::text), ' '::text) AS nbz,
	ltrim("substring"(o.punktkennung::text, 10, 6), '0'::text) AS pnr,
	o.beginnt AS o_beginnt,
	o.endet AS o_endet,
	d.bezeichnung AS zustaendige_stelle,
	o.zustaendigestelle_stelle,
	o.zeitpunktderentstehung,
	CASE
			WHEN o.festgestelltergrenzpunkt::text = 'true'::text THEN 'Ja'::text
			ELSE 'Nein'::text
	END AS festgestelltergrenzpunkt,
	da.bezeichnung AS ausgesetzteabmarkung_stelle,
	l.beschreibung AS verm_bedeutung,
	o.abmarkung_marke,
	ba.beschreibung AS bemerkung_zurabmarkung,
	o.bemerkungzurabmarkung,
	o.zeigtauf,
	array_to_string(o.sonstigeeigenschaft, ', '::text) AS sonstigeeigenschaft,
	o.relativehoehe,
	o.besonderepunktnummer,
	o.zeigtaufexternes_art as o_zeigtaufexternes_art,
	o.zeigtaufexternes_name as o_zeigtaufexternes_name,
	o.zeigtaufexternes_uri as o_zeigtaufexternes_uri,
	p.gml_id AS ax_punktortta_gml_id,
	p.beginnt AS p_beginnt,
	p.endet AS p_endet,
	rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS "position",
	ks.beschreibung as koordinatenstatus_beschreibung,
	p.koordinatenstatus,
	m.beschreibung AS punktgenauigkeit,
	p.genauigkeitsstufe,
	n.beschreibung AS punktvertrauenswuerdigkeit,
	p.vertrauenswuerdigkeit,
	e.beschreibung AS datenerhebung,
	array_to_string(p.processstep_ax_datenerhebung_punktort, ',') AS source_description,
	p.zeigtaufexternes_art as p_zeigtaufexternes_art,
	p.zeigtaufexternes_name as p_zeigtaufexternes_name,
	p.zeigtaufexternes_uri as p_zeigtaufexternes_uri,
	CASE 
		WHEN processstep_datetime[2] is null
		THEN 'Erhebung: '||to_char(cast(processstep_datetime[1] as date), 'DD.MM.YYYY')
		ELSE
		CASE
			WHEN 'Berechnung' = processstep_ax_li_processstep_punktort_description[1] THEN 'Berechnung: '||to_char(cast(processstep_datetime[1] as date), 'DD.MM.YYYY')||', '||'Erhebung: '||to_char(cast(processstep_datetime[2] as date), 'DD.MM.YYYY') 
			ELSE 'Erhebung: '||to_char(cast(processstep_datetime[1] as date), 'DD.MM.YYYY')||', '||'Berechnung: '||to_char(cast(processstep_datetime[2] as date) , 'DD.MM.YYYY')
		END
	END as erhebung_berechnung,
	CASE
			WHEN p.lagezuverlaessigkeit::text = ''::text THEN '' WHEN p.lagezuverlaessigkeit::text = 'true'::text THEN 'Ja'::text
			ELSE 'Nein'::text
	END AS lagezuverlaessigkeit,
	p.ueberpruefungsdatum,
	p.hinweise,
	CASE
			WHEN p.kartendarstellung::text = 'true'::text THEN 'Ja'::text
			ELSE 'Nein'::text
	END AS kartendarstellung,
	p.wkb_geometry
	FROM alkis.ax_grenzpunkt o
	LEFT JOIN alkis.ax_punktortta p ON o.gml_id = ANY (p.istteilvon)
	LEFT JOIN alkis.ax_marke l ON l.wert = o.abmarkung_marke
	LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
	LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit
	LEFT JOIN alkis.ax_bemerkungzurabmarkung_grenzpunkt ba ON o.bemerkungzurabmarkung = ba.wert
	LEFT JOIN alkis.ax_datenerhebung_punktort e ON e.wert = ANY (p.processstep_ax_datenerhebung_punktort::integer[])
	LEFT JOIN alkis.ax_dienststelle d ON o.zustaendigestelle_stelle::text = d.stelle::text
	LEFT JOIN alkis.ax_dienststelle da ON o.ausgesetzteabmarkung_stelle::text = da.stelle::text
	LEFT JOIN alkis.ax_koordinatenstatus_punktort ks ON koordinatenstatus = ks.wert
	WHERE d.endet IS NULL AND da.endet IS NULL;

COMMIT;
