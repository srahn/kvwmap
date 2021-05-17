BEGIN;

DROP VIEW alkis.lk_grenzpunkte;

CREATE OR REPLACE VIEW alkis.lk_grenzpunkte AS

SELECT o.ogc_fid, 
			 o.gml_id,
       o.punktkennung,
       ltrim(to_char(substring(o.punktkennung, 1, 9)::integer, '999 99 99 99'), ' ') AS nbz,
       ltrim(substring(o.punktkennung, 10, 6), '0') AS pnr,
       o.beginnt AS o_beginnt,
       o.endet AS o_endet,
       d.bezeichnung AS zustaendige_stelle,
       o.zustaendigestelle_stelle,
       o.zeitpunktderentstehung,
       CASE WHEN o.festgestelltergrenzpunkt = 'true' THEN
         'Ja'
       ELSE
         'Nein'
       END AS festgestelltergrenzpunkt,
       da.bezeichnung AS ausgesetzteabmarkung_stelle,
       l.beschreibung AS verm_bedeutung,
       o.abmarkung_marke,
       ba.beschreibung AS bemerkung_zurabmarkung,
       o.bemerkungzurabmarkung,
       o.zeigtauf,
       array_to_string(o.sonstigeeigenschaft, ', ') AS sonstigeeigenschaft,
       o.relativehoehe,
       o.besonderepunktnummer,
       o.zeigtaufexternes_art AS o_zeigtaufexternes_art,
       o.zeigtaufexternes_name AS o_zeigtaufexternes_name,
       o.zeigtaufexternes_uri AS o_zeigtaufexternes_uri,
       p.gml_id AS ax_punktortta_gml_id,
       p.beginnt AS p_beginnt,
       p.endet AS p_endet,
       substring(ST_AsText(p.wkb_geometry) FROM '\((.*)\)') AS "position",
       ks.beschreibung AS koordinatenstatus_beschreibung,
       p.koordinatenstatus,
       m.beschreibung AS punktgenauigkeit,
       p.genauigkeitsstufe,
       n.beschreibung AS punktvertrauenswuerdigkeit,
       p.vertrauenswuerdigkeit,
       e.beschreibung AS datenerhebung,
       array_to_string(p.processstep_ax_datenerhebung_punktort, ',') AS source_description,
       p.zeigtaufexternes_art AS p_zeigtaufexternes_art,
       p.zeigtaufexternes_name AS p_zeigtaufexternes_name,
       p.zeigtaufexternes_uri AS p_zeigtaufexternes_uri,
       ( SELECT string_agg(
                  concat(
                    coalesce(beschreibung, 'Erhebung'),
                    ': ',
                    to_char(zeitpunkt::timestamp, 'DD.MM.YYYY') ),
                  ', ')
           FROM unnest(p.processstep_ax_li_processstep_punktort_description, p.processstep_datetime) AS u (beschreibung, zeitpunkt)
          WHERE zeitpunkt IS NOT NULL
       ) AS erhebung_berechnung,
       CASE WHEN p.lagezuverlaessigkeit = 'true' THEN
         'Ja'
       ELSE
         'Nein'
       END AS lagezuverlaessigkeit,
       p.ueberpruefungsdatum,
       p.hinweise,
       CASE WHEN p.kartendarstellung = 'true' THEN
         'Ja'
       ELSE
         'Nein'
       END AS kartendarstellung,
       p.wkb_geometry
  FROM alkis.ax_grenzpunkt o
  JOIN alkis.ax_punktortta p
       ON o.gml_id = ANY (p.istteilvon)
  JOIN alkis.ax_marke l
       ON l.wert = o.abmarkung_marke
  LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m
       ON m.wert = p.genauigkeitsstufe
  LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n
       ON n.wert = p.vertrauenswuerdigkeit
  LEFT JOIN alkis.ax_bemerkungzurabmarkung_grenzpunkt ba
       ON o.bemerkungzurabmarkung = ba.wert
  LEFT JOIN alkis.ax_datenerhebung_punktort e
       ON e.wert = ANY (p.processstep_ax_datenerhebung_punktort::integer[])
  LEFT JOIN alkis.ax_dienststelle d
       ON o.zustaendigestelle_land = d.land AND o.zustaendigestelle_stelle = d.stelle
  LEFT JOIN alkis.ax_dienststelle da
       ON o.zustaendigestelle_land = da.land AND o.ausgesetzteabmarkung_stelle = da.stelle
  LEFT JOIN alkis.ax_koordinatenstatus_punktort ks
       ON p.koordinatenstatus = ks.wert
WHERE d.endet IS NULL AND da.endet IS NULL;

COMMIT;
