BEGIN;

ALTER TABLE nachweisverwaltung.fp_punkte_alkis ALTER COLUMN des TYPE varchar;

DROP VIEW nachweisverwaltung.fp_aufnahmepunkt;

CREATE VIEW nachweisverwaltung.fp_aufnahmepunkt AS 
 SELECT ap.gml_id,
    au.gml_id AS gml_id_punktort,
    ap.identifier,
    ap.beginnt,
    ap.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    ap.advstandardmodell,
    ap.sonstigesmodell,
    ap.anlass,
    ap.zustaendigestelle_land::integer AS zustaendigestelle_land,
    ap.zustaendigestelle_stelle::integer AS zst,
    ap.punktkennung AS pkn,
    ltrim(substr(ap.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'AP'::character varying(4) AS par,
    ap.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(ap.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(ap.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    ap.vermarkung_marke AS abm,
    ap.relativehoehe AS rho,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    au.zeigtaufexternes_name AS nam,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    ap.hat,
    (((((substr(ap.punktkennung::text, 1, 3) || substr(ap.punktkennung::text, 4, 2)) || substr(ap.punktkennung::text, 6, 2)) || substr(ap.punktkennung::text, 8, 2)) || '/'::text) || ap.punktkennung::text) || '.tif'::text AS datei,
    st_force3D(au.wkb_geometry)
   FROM alkis.ax_aufnahmepunkt ap,
    alkis.ax_punktortau au
  WHERE ap.gml_id = au.istteilvon[1];

	
DROP VIEW nachweisverwaltung.fp_besondererbauwerkspunkt;

CREATE VIEW nachweisverwaltung.fp_besondererbauwerkspunkt AS 
 SELECT bwp.gml_id,
    au.gml_id AS gml_id_ax_punktort,
    bwp.identifier,
    bwp.beginnt,
    bwp.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    bwp.advstandardmodell,
    bwp.sonstigesmodell,
    bwp.anlass,
    bwp.zustaendigestelle_land::integer as land,
    bwp.zustaendigestelle_stelle::integer  AS zst,
    bwp.punktkennung AS pkn,
    ltrim(substr(bwp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'BwP'::character varying(4) AS par,
    bwp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    au.zeigtaufexternes_name AS nam,
    NULL::character varying[] AS art,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    ST_Force3D(au.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besondererbauwerkspunkt bwp,
    alkis.ax_punktortau au
  WHERE bwp.gml_id = au.istteilvon[1]
UNION
 SELECT bwp.gml_id,
    ag.gml_id AS gml_id_ax_punktort,
    bwp.identifier,
    bwp.beginnt,
    bwp.endet,
    ag.beginnt AS beginnt_punktort,
    ag.endet AS endet_punktort,
    bwp.advstandardmodell,
    bwp.sonstigesmodell,
    bwp.anlass,
    bwp.zustaendigestelle_land::integer as land,
    bwp.zustaendigestelle_stelle::integer  AS zst,
    bwp.punktkennung AS pkn,
    ltrim(substr(bwp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(ag.wkb_geometry)::character varying(11) AS rw,
    st_y(ag.wkb_geometry)::character varying(11) AS hw,
    'BwP'::character varying(4) AS par,
    bwp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(bwp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(bwp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    ag.kartendarstellung AS kds,
    ag.processstep_ax_datenerhebung_punktort[1] AS des,
    ag.zeigtaufexternes_name AS nam,
    ag.zeigtaufexternes_art as art,
    ag.vertrauenswuerdigkeit AS vwl,
    ag.genauigkeitsstufe AS gst,
    ag.koordinatenstatus AS kst,
    ag.hinweise AS hin,
    ST_Force3D(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besondererbauwerkspunkt bwp,
    alkis.ax_punktortag ag
  WHERE bwp.gml_id = ag.istteilvon[1];
	

DROP VIEW nachweisverwaltung.fp_besonderergebaeudepunkt;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_besonderergebaeudepunkt AS 
 SELECT gebp.gml_id,
    au.gml_id AS gml_id_punktort,
    gebp.identifier,
    gebp.beginnt,
    gebp.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    gebp.advstandardmodell,
    gebp.sonstigesmodell,
    gebp.anlass,
    gebp.zustaendigestelle_land::integer as land,
    gebp.zustaendigestelle_stelle::integer  AS zst,
    gebp.punktkennung AS pkn,
    ltrim(substr(gebp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'GebP'::character varying(4) AS par,
    gebp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    gebp.zeigtaufexternes_name AS nam,
    string_to_array(gebp.art::text, ''::text) AS art,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    ST_Force3D(au.wkb_geometry) as wkb_geometry
   FROM alkis.ax_besonderergebaeudepunkt gebp,
    alkis.ax_punktortau au
  WHERE gebp.gml_id = au.istteilvon[1]
UNION
 SELECT gebp.gml_id,
    ag.gml_id AS gml_id_punktort,
    gebp.identifier,
    gebp.beginnt,
    gebp.endet,
    ag.beginnt AS beginnt_punktort,
    ag.endet AS endet_punktort,
    gebp.advstandardmodell,
    gebp.sonstigesmodell,
    gebp.anlass,
    gebp.zustaendigestelle_land::integer as land,
    gebp.zustaendigestelle_stelle::integer  AS zst,
    gebp.punktkennung AS pkn,
    ltrim(substr(gebp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(ag.wkb_geometry)::character varying(11) AS rw,
    st_y(ag.wkb_geometry)::character varying(11) AS hw,
    'GebP'::character varying(4) AS par,
    gebp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gebp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gebp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    ag.kartendarstellung AS kds,
    ag.processstep_ax_datenerhebung_punktort[1] AS des,
    gebp.zeigtaufexternes_name AS nam,
    string_to_array(gebp.art::text, ''::text) AS art,
    ag.vertrauenswuerdigkeit AS vwl,
    ag.genauigkeitsstufe AS gst,
    ag.koordinatenstatus AS kst,
    ag.hinweise AS hin,
    ST_Force3D(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besonderergebaeudepunkt gebp,
    alkis.ax_punktortag ag
  WHERE gebp.gml_id = ag.istteilvon[1];
	
	
DROP VIEW nachweisverwaltung.fp_besonderertopographischerpunkt;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_besonderertopographischerpunkt AS 
 SELECT topp.gml_id,
    au.gml_id AS gml_id_punktort,
    topp.identifier,
    topp.beginnt,
    topp.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    topp.advstandardmodell,
    topp.sonstigesmodell,
    topp.anlass,
    topp.zustaendigestelle_land::integer as land,
    topp.zustaendigestelle_stelle::integer  AS zst,
    topp.punktkennung AS pkn,
    ltrim(substr(topp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'TopP'::character varying(4) AS par,
    topp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(topp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(topp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    au.zeigtaufexternes_name AS nam,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    ST_Force3D(au.wkb_geometry) as wkb_geometry
   FROM alkis.ax_besonderertopographischerpunkt topp,
    alkis.ax_punktortau au
  WHERE topp.gml_id = au.istteilvon[1];
	
	
DROP VIEW nachweisverwaltung.fp_grenzpunkt;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_grenzpunkt AS 
 SELECT gp.gml_id,
    au.gml_id AS gml_id_punktort,
    gp.identifier,
    gp.beginnt,
    gp.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    gp.advstandardmodell,
    gp.sonstigesmodell,
    gp.anlass,
    gp.zustaendigestelle_land::integer as land,
    gp.zustaendigestelle_stelle::integer  AS zst,
    gp.punktkennung AS pkn,
    ltrim(substr(gp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'GP'::character varying(4) AS par,
    gp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    gp.abmarkung_marke AS abm,
    gp.festgestelltergrenzpunkt AS fgp,
    gp.besonderepunktnummer AS bpn,
    NULL::integer AS aam,
    gp.bemerkungzurabmarkung AS bza,
    gp.zeitpunktderentstehung AS zde,
    gp.relativehoehe AS rho,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    gp.zeigtaufexternes_name AS nam,
    NULL::character varying[] AS art,
    au.vertrauenswuerdigkeit AS vwl,
    NULL::boolean AS lzk,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    NULL::text AS pru,
    au.hinweise AS hin,
    NULL::text AS fdv,
    gp.zeigtauf,
    NULL::character varying AS hat,
    NULL::character varying AS beziehtsichauf,
    NULL::character varying AS gehoertzu,
    NULL::character varying AS datei,
    ST_Force3D(au.wkb_geometry) as wkb_geometry
   FROM alkis.ax_grenzpunkt gp,
    alkis.ax_punktortau au
  WHERE gp.gml_id = au.istteilvon[1]
UNION
 SELECT gp.gml_id,
    ta.gml_id AS gml_id_punktort,
    gp.identifier,
    gp.beginnt,
    gp.endet,
    ta.beginnt AS beginnt_punktort,
    ta.endet AS endet_punktort,
    gp.advstandardmodell,
    gp.sonstigesmodell,
    gp.anlass,
    gp.zustaendigestelle_land::integer as land,
    gp.zustaendigestelle_stelle::integer  AS zst,
    gp.punktkennung AS pkn,
    ltrim(substr(gp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(ta.wkb_geometry)::character varying(11) AS rw,
    st_y(ta.wkb_geometry)::character varying(11) AS hw,
    'GP'::character varying(4) AS par,
    gp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(gp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(gp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    gp.abmarkung_marke AS abm,
    gp.festgestelltergrenzpunkt AS fgp,
    gp.besonderepunktnummer AS bpn,
    NULL::integer AS aam,
    gp.bemerkungzurabmarkung AS bza,
    gp.zeitpunktderentstehung AS zde,
    gp.relativehoehe AS rho,
    ta.kartendarstellung AS kds,
    ta.processstep_ax_datenerhebung_punktort[1] AS des,
    gp.zeigtaufexternes_name AS nam,
    NULL::character varying[] AS art,
    ta.vertrauenswuerdigkeit AS vwl,
    NULL::boolean AS lzk,
    ta.genauigkeitsstufe AS gst,
    ta.koordinatenstatus AS kst,
    NULL::text AS pru,
    ta.hinweise AS hin,
    NULL::text AS fdv,
    gp.zeigtauf,
    NULL::character varying AS hat,
    NULL::character varying AS beziehtsichauf,
    NULL::character varying AS gehoertzu,
    NULL::character varying AS datei,
    ST_Force3D(ta.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_grenzpunkt gp,
    alkis.ax_punktortta ta
  WHERE gp.gml_id = ta.istteilvon[1];
	
	
DROP VIEW nachweisverwaltung.fp_sicherungspunkt;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_sicherungspunkt AS 
 SELECT sip.gml_id,
    au.gml_id AS gml_id_punktort,
    sip.identifier,
    sip.beginnt,
    sip.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    sip.advstandardmodell,
    sip.sonstigesmodell,
    sip.anlass,
    sip.zustaendigestelle_land::integer as land,
    sip.zustaendigestelle_stelle::integer  AS zst,
    sip.punktkennung AS pkn,
    ltrim(substr(sip.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'SiP'::character varying(4) AS par,
    sip.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(sip.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(sip.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    sip.vermarkung_marke AS abm,
    sip.relativehoehe AS rho,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    sip.zeigtaufexternes_name AS nam,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    sip.beziehtsichauf,
    sip.gehoertzu,
    (("substring"(sip.punktkennung::text, 1, 9) || '/'::text) || sip.punktkennung::text) || '.tif'::text AS datei,
    ST_Force3D(au.wkb_geometry) as wkb_geometry
   FROM alkis.ax_sicherungspunkt sip,
    alkis.ax_punktortau au
  WHERE sip.gml_id = au.istteilvon[1];
	
	
DROP VIEW nachweisverwaltung.fp_sonstigervermessungspunkt;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_sonstigervermessungspunkt AS 
 SELECT svp.gml_id,
    au.gml_id AS gml_id_punktort,
    svp.identifier,
    svp.beginnt,
    svp.endet,
    au.beginnt AS beginnt_punktort,
    au.endet AS endet_punktort,
    svp.advstandardmodell,
    svp.sonstigesmodell,
    svp.anlass,
    svp.zustaendigestelle_land::integer as land,
    svp.zustaendigestelle_stelle::integer  AS zst,
    svp.punktkennung AS pkn,
    ltrim(substr(svp.punktkennung::text, 10, 6), '0'::text) AS pktnr,
    st_x(au.wkb_geometry)::character varying(11) AS rw,
    st_y(au.wkb_geometry)::character varying(11) AS hw,
    'SVP'::character varying(4) AS par,
    svp.sonstigeeigenschaft AS soe,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PKZ'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_pkz,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-PAR'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_par,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 2))
            WHEN split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 1) = 'ALK-VMA'::text THEN ltrim(split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 2))
            ELSE NULL::text
        END AS soe_alk_vma,
    concat_ws('|'::text,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[1]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[1]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[2]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[2]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[3]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[3]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[4]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[4]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[5]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[5]::text, ' '::text)
            ELSE NULL::text
        END,
        CASE
            WHEN split_part(svp.sonstigeeigenschaft[6]::text, ':'::text, 1) <> ALL (ARRAY['ALK-PKZ'::text, 'ALK-PAR'::text, 'ALK-VMA'::text]) THEN btrim(svp.sonstigeeigenschaft[6]::text, ' '::text)
            ELSE NULL::text
        END) AS soe_weitere,
    svp.vermarkung_marke AS abm,
    svp.relativehoehe AS rho,
    au.kartendarstellung AS kds,
    au.processstep_ax_datenerhebung_punktort[1] AS des,
    au.zeigtaufexternes_name AS nam,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    svp.hat,
    (("substring"(svp.punktkennung::text, 1, 9) || '/'::text) || svp.punktkennung::text) || '.tif'::text AS datei,
    ST_Force3D(au.wkb_geometry) as wkb_geometry
   FROM alkis.ax_sonstigervermessungspunkt svp,
    alkis.ax_punktortau au
  WHERE svp.gml_id = au.istteilvon[1];	

COMMIT;
