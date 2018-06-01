BEGIN;

CREATE OR REPLACE VIEW alkis.lk_grenzpunkte AS
 SELECT o.oid,
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
    p.processstep_ax_datenerhebung_punktort[1] AS source_description,
    e.beschreibung AS datenerhebung,
    p.genauigkeitsstufe,
    m.beschreibung AS punktgenauigkeit,
    p.vertrauenswuerdigkeit,
    n.beschreibung AS punktvertrauenswuerdigkeit,
    p.wkb_geometry
   FROM alkis.ax_grenzpunkt o
     LEFT JOIN alkis.ax_punktortta p ON o.gml_id = ANY (p.istteilvon)
     LEFT JOIN alkis.ax_marke l ON l.wert = o.abmarkung_marke
     LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
     LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit
     LEFT JOIN alkis.ax_bemerkungzurabmarkung_grenzpunkt ba ON o.bemerkungzurabmarkung = ba.wert
     LEFT JOIN alkis.ax_datenerhebung e ON e.wert = ANY (p.processstep_ax_datenerhebung_punktort::integer[])
     LEFT JOIN alkis.ax_dienststelle d ON o.zustaendigestelle_stelle::text = d.stelle::text
  WHERE d.endet IS NULL;

COMMIT;
