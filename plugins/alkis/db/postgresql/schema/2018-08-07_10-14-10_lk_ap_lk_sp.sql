BEGIN;

DROP VIEW alkis.lk_ap;

CREATE OR REPLACE VIEW alkis.lk_ap AS 
SELECT 
 o.oid,
 o.gml_id,
 o.punktkennung,
 ltrim(to_char("substring"(o.punktkennung::text, 1, 9)::integer, '999 99 99 99'::text), ' '::text) AS nbz,
 ltrim("substring"(o.punktkennung::text, 10, 6), '0'::text) AS pnr,
 o.beginnt AS o_beginnt,
 o.endet AS o_endet,
 d.bezeichnung AS zustaendige_stelle,
 o.zustaendigestelle_stelle,
 l.beschreibung AS verm_bedeutung,
 o.vermarkung_marke,
 o.hat,
 array_to_string(o.sonstigeeigenschaft, ', '::text) AS sonstigeeigenschaft,
 o.relativehoehe,
 o.zeigtaufexternes_art AS o_zeigtaufexternes_art,
 o.zeigtaufexternes_name AS o_zeigtaufexternes_name,
 o.zeigtaufexternes_uri AS o_zeigtaufexternes_uri,
 p.gml_id AS ax_punktortta_gml_id,
 p.beginnt AS p_beginnt,
 p.endet AS p_endet,
 rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS "position",
 ks.beschreibung AS koordinatenstatus_beschreibung,
 p.koordinatenstatus,
 m.beschreibung AS punktgenauigkeit,
 p.genauigkeitsstufe,
 n.beschreibung AS punktvertrauenswuerdigkeit,
 p.vertrauenswuerdigkeit,
 e.beschreibung AS datenerhebung,
 array_to_string(p.processstep_ax_datenerhebung_punktort, ','::text) AS source_description,
 p.zeigtaufexternes_art AS p_zeigtaufexternes_art,
 p.zeigtaufexternes_name AS p_zeigtaufexternes_name,
 p.zeigtaufexternes_uri AS p_zeigtaufexternes_uri,
 CASE
  WHEN p.processstep_datetime[2] IS NULL THEN 'Erhebung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
  ELSE
  CASE
   WHEN 'Berechnung'::text = p.processstep_ax_li_processstep_punktort_description[1]::text THEN ((('Berechnung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)) || ', '::text) || 'Erhebung: '::text) || to_char(p.processstep_datetime[2]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
   ELSE ((('Erhebung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)) || ', '::text) || 'Berechnung: '::text) || to_char(p.processstep_datetime[2]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
  END
 END AS erhebung_berechnung,
 CASE
  WHEN p.lagezuverlaessigkeit::text = ''::text THEN ''::text
  WHEN p.lagezuverlaessigkeit::text = 'true'::text THEN 'Ja'::text
  ELSE 'Nein'::text
 END AS lagezuverlaessigkeit,
 p.ueberpruefungsdatum,
 p.hinweise,
 CASE
  WHEN p.kartendarstellung::text = 'true'::text THEN 'Ja'::text
  ELSE 'Nein'::text
 END AS kartendarstellung,
 p.wkb_geometry	
FROM alkis.ax_aufnahmepunkt o
LEFT JOIN alkis.ax_punktortau p ON o.gml_id = ANY (p.istteilvon)
LEFT JOIN alkis.ax_marke l ON l.wert = o.vermarkung_marke
LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit
LEFT JOIN alkis.ax_datenerhebung_punktort e ON e.wert = ANY (p.processstep_ax_datenerhebung_punktort::integer[])
LEFT JOIN alkis.ax_dienststelle d ON o.zustaendigestelle_stelle::text = d.stelle::text
LEFT JOIN alkis.ax_koordinatenstatus_punktort ks ON p.koordinatenstatus = ks.wert
WHERE d.endet IS NULL;


DROP VIEW alkis.lk_sp;

CREATE OR REPLACE VIEW alkis.lk_sp AS 
 SELECT o.oid,
    o.gml_id,
    o.punktkennung,
    ltrim(to_char("substring"(o.punktkennung::text, 1, 9)::integer, '999 99 99 99'::text), ' '::text) AS nbz,
    ltrim("substring"(o.punktkennung::text, 10, 6), '0'::text) AS pnr,
    o.beginnt AS o_beginnt,
    o.endet AS o_endet,
    d.bezeichnung AS zustaendige_stelle,
    o.zustaendigestelle_stelle,
    l.beschreibung AS verm_bedeutung,
    o.vermarkung_marke,
    ap.gml_id AS ap_gml_id,
    array_to_string(o.sonstigeeigenschaft, ', '::text) AS sonstigeeigenschaft,
    o.relativehoehe,
    o.zeigtaufexternes_art AS o_zeigtaufexternes_art,
    o.zeigtaufexternes_name AS o_zeigtaufexternes_name,
    o.zeigtaufexternes_uri AS o_zeigtaufexternes_uri,
    p.gml_id AS ax_punktortta_gml_id,
    p.beginnt AS p_beginnt,
    p.endet AS p_endet,
    rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS "position",
    ks.beschreibung AS koordinatenstatus_beschreibung,
    p.koordinatenstatus,
    m.beschreibung AS punktgenauigkeit,
    p.genauigkeitsstufe,
    n.beschreibung AS punktvertrauenswuerdigkeit,
    p.vertrauenswuerdigkeit,
    e.beschreibung AS datenerhebung,
    array_to_string(p.processstep_ax_datenerhebung_punktort, ','::text) AS source_description,
    p.zeigtaufexternes_art AS p_zeigtaufexternes_art,
    p.zeigtaufexternes_name AS p_zeigtaufexternes_name,
    p.zeigtaufexternes_uri AS p_zeigtaufexternes_uri,
        CASE
            WHEN p.processstep_datetime[2] IS NULL THEN 'Erhebung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
            ELSE
            CASE
                WHEN 'Berechnung'::text = p.processstep_ax_li_processstep_punktort_description[1]::text THEN ((('Berechnung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)) || ', '::text) || 'Erhebung: '::text) || to_char(p.processstep_datetime[2]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
                ELSE ((('Erhebung: '::text || to_char(p.processstep_datetime[1]::date::timestamp with time zone, 'DD.MM.YYYY'::text)) || ', '::text) || 'Berechnung: '::text) || to_char(p.processstep_datetime[2]::date::timestamp with time zone, 'DD.MM.YYYY'::text)
            END
        END AS erhebung_berechnung,
        CASE
            WHEN p.lagezuverlaessigkeit::text = ''::text THEN ''::text
            WHEN p.lagezuverlaessigkeit::text = 'true'::text THEN 'Ja'::text
            ELSE 'Nein'::text
        END AS lagezuverlaessigkeit,
    p.ueberpruefungsdatum,
    p.hinweise,
        CASE
            WHEN p.kartendarstellung::text = 'true'::text THEN 'Ja'::text
            ELSE 'Nein'::text
        END AS kartendarstellung,
    p.wkb_geometry
   FROM alkis.ax_sicherungspunkt o
     LEFT JOIN alkis.ax_punktortau p ON o.gml_id = ANY (p.istteilvon)
     LEFT JOIN alkis.ax_marke l ON l.wert = o.vermarkung_marke
     LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
     LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit
     LEFT JOIN alkis.ax_datenerhebung_punktort e ON e.wert = ANY (p.processstep_ax_datenerhebung_punktort::integer[])
     LEFT JOIN alkis.ax_dienststelle d ON o.zustaendigestelle_stelle::text = d.stelle::text
     LEFT JOIN alkis.ax_koordinatenstatus_punktort ks ON p.koordinatenstatus = ks.wert
     LEFT JOIN alkis.ax_aufnahmepunkt ap ON o.gml_id = ANY (ap.hat)
  WHERE d.endet IS NULL;

COMMIT;
