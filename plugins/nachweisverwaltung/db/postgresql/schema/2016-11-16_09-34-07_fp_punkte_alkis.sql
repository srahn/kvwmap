BEGIN;

ALTER TABLE nachweisverwaltung.fp_punkte2antraege RENAME pkz TO pkn;

CREATE TABLE nachweisverwaltung.fp_punkte_alkis
(
  ogc_fid serial NOT NULL,
  gml_id character varying, 
  gml_id_punktort character varying,
  identifier character varying,
  beginnt character(20),
  endet character(20),
  beginnt_punktort character(20),
  endet_punktort character(20),
  advstandardmodell character varying,
  sonstigesmodell character varying,
  anlass character varying,
  land integer,
  zst integer, 
  pkn character varying, 
  pktnr character varying(6),
  rw character varying(11),
  hw character varying(11), 
  hoe character varying(9), 
  hop character varying(9), 
  par character varying(4), 
  soe character varying, 
  soe_alk_pkz character varying, 
  soe_alk_par character varying, 
  soe_alk_vma character varying, 
  soe_weitere character varying, 
  abm integer, 
  fgp character varying, 
  bpn character varying, 
  aam integer, 
  bza integer, 
  zde character varying, 
  rho double precision, 
  kds character varying, 
  des integer,
  nam character varying,
  art character varying[], 
  idn character varying,
  vwl integer, 
  lzk boolean, 
  gst integer, 
  kst integer, 
  pru character(20), 
  hin character varying,
  fdv character varying, 
  zeigtauf character varying,
  hat character varying,
  beziehtsichauf character varying,
  gehoertzu character varying,
  datei character varying,
  CONSTRAINT fp_punkte_alkis_pkey PRIMARY KEY (ogc_fid)
);
SELECT AddGeometryColumn('nachweisverwaltung', 'fp_punkte_alkis','wkb_geometry',25833,'POINT', 3);

COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.gml_id IS 'Identifikator, global eindeutig';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.zst IS 'Stelle';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.pkn IS 'Punktkennung';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.pktnr IS 'Punktnummer';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.rw IS 'Rechtswert';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.hw IS 'Hochwert';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.hoe IS 'Hoehe';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.hop IS 'Hoehe, Oberkante Pfeiler im STN';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.par IS 'Punktart';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.soe IS 'sonstige Eigenschaft';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.soe_alk_pkz IS 'sonstige Eigenschaft - ALK-Punktkennzeichen';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.soe_alk_par IS 'sonstige Eigenschaft - ALK-Punktart';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.soe_alk_vma IS 'sonstige Eigenschaft - ALK-Vermarkungsart';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.soe_weitere IS 'sonstige Eigenschaft - weitere';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.abm IS 'Ab-/Vermarkung_Marke';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.fgp IS 'festgestellter Grenzpunkt';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.bpn IS 'besondere Punktnummer';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.aam IS 'Ausgesetzte Abmarkung';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.bza IS 'Bemerkung zur Abmarkung';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.zde IS 'Zeitpunkt der Entstehung';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.rho IS 'relative Höhe';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.kds IS 'Kartendarstellung';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.des IS 'AX_Datenerhebung_Punktort';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.nam IS 'Name';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.art IS 'Art des Punktes';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.vwl IS 'Vertrauenswürdigkeit';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.lzk IS 'Lagezuverlaessigkeit';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.gst IS 'Genauigkeitsstufe';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.kst IS 'Koordinatenstatus';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.pru IS 'Ueberpruefungsdatum';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.hin IS 'Hinweise';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.fdv IS 'AA-Fachdatenverbindung';



CREATE INDEX fp_punkte_alkis_bsa_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (beziehtsichauf COLLATE pg_catalog."default");



CREATE INDEX fp_punkte_alkis_geom_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING gist
  (wkb_geometry);



CREATE INDEX fp_punkte_alkis_gz_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (gehoertzu COLLATE pg_catalog."default");



CREATE INDEX fp_punkte_alkis_hat_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (hat COLLATE pg_catalog."default");



CREATE INDEX fp_punkte_alkis_za_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (zeigtauf COLLATE pg_catalog."default");



CREATE TABLE nachweisverwaltung.fp_afismv
(
  ogc_fid serial NOT NULL,
  gml_id character varying,
  punktkennung character varying,
  x character varying,
  y character varying,
  anlass character varying,
  punktvermarkung character varying,
  bemerkung character varying,
  upd character varying,
  h3d character varying,
  wertigkeit character varying,
  ordnung character varying,
  pfeilerhoehe character varying,
  gnsstauglichkeit character varying,
  pdfurl character varying,
  ellipsoidhoehe character varying,
  hoehe character varying,
  CONSTRAINT fp_afismv_pkey PRIMARY KEY (ogc_fid)
);

SELECT AddGeometryColumn('nachweisverwaltung', 'fp_afismv','wkb_geometry',25833,'POINT', 2);
	
COMMENT ON TABLE nachweisverwaltung.fp_afismv IS 'Tabelle wird gefüllt über: ogr2ogr -overwrite -f PostgreSQL "PG:user=XXXXXX password=XXXXXXXXXX host=XXXXXX dbname=XXXXXX schemas=nachweisverwaltung" "WFS:http://www.geodaten-mv.de/dienste/afis_wfs?service=WFS&request=GetFeature&version=1.1.0&typeName=afismv:afis_wfs&srsName=EPSG:25833" -lco OVERWRITE=yes -nln fp_afismv';
	
	

	
CREATE OR REPLACE VIEW nachweisverwaltung.fp_pp_afismv AS 
 SELECT fp_afismv.gml_id,
    fp_afismv.anlass,
    '{DLKM}'::character varying AS advstandardmodell,
    13 AS land,
    40 AS zst,
    fp_afismv.punktkennung AS pkn,
    ltrim("right"(fp_afismv.punktkennung::text, 5), '0'::text) AS pktnr,
    round("substring"(fp_afismv.x::text, 3)::numeric, 3)::character varying(11) AS rw,
    round(fp_afismv.y::numeric, 3)::character varying(11) AS hw,
    fp_afismv.hoehe::character varying(9) AS hoe,
    (fp_afismv.hoehe::numeric(9,3) + fp_afismv.pfeilerhoehe::numeric / 1000::numeric)::numeric(9,3)::character varying(9) AS hop,
        CASE
            WHEN "right"(fp_afismv.punktkennung::text, 1) <> '0'::text THEN 'OP'::character varying(4)
            ELSE 'TP'::character varying(4)
        END AS par,
    fp_afismv.punktvermarkung::integer AS abm,
    fp_afismv.bemerkung AS hin,
    fp_afismv.upd AS pru,
        CASE
            WHEN length(fp_afismv.punktkennung::text) = 8 THEN ((substr(fp_afismv.punktkennung::text, 1, 3) || '/'::text) || fp_afismv.punktkennung::text) || '.tif'::text
            ELSE ((substr(fp_afismv.punktkennung::text, 1, 4) || '/'::text) || fp_afismv.punktkennung::text) || '.tif'::text
        END AS datei,
        CASE
            WHEN fp_afismv.hoehe IS NULL THEN st_force3d(st_transform(fp_afismv.wkb_geometry, 25833))
            ELSE st_setsrid(st_makepoint("substring"(fp_afismv.x::text, 3)::double precision, fp_afismv.y::double precision, fp_afismv.hoehe::double precision), 25833)
        END AS wkb_geometry
   FROM nachweisverwaltung.fp_afismv;

COMMENT ON VIEW nachweisverwaltung.fp_pp_afismv
  IS '30.11.2016, H.Riedel
Abrage zum Befuellen der fp_punkte_alkis mit den Lagefestpunkten aus AFIS MV';

CREATE OR REPLACE VIEW nachweisverwaltung.fp_aufnahmepunkt AS 
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
    ap.zustaendigestelle_land,
    ap.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_aufnahmepunkt ap,
    alkis.ax_punktortau au
  WHERE ap.gml_id::text = au.istteilvon::text;

CREATE OR REPLACE VIEW nachweisverwaltung.fp_besondererbauwerkspunkt AS 
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
    bwp.zustaendigestelle_land,
    bwp.zustaendigestelle_stelle AS zst,
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
    st_force3d(au.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besondererbauwerkspunkt bwp,
    alkis.ax_punktortau au
  WHERE bwp.gml_id::text = au.istteilvon::text
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
    bwp.zustaendigestelle_land,
    bwp.zustaendigestelle_stelle AS zst,
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
    ag.zeigtaufexternes_art AS art,
    ag.vertrauenswuerdigkeit AS vwl,
    ag.genauigkeitsstufe AS gst,
    ag.koordinatenstatus AS kst,
    ag.hinweise AS hin,
    st_force3d(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besondererbauwerkspunkt bwp,
    alkis.ax_punktortag ag
  WHERE bwp.gml_id::text = ag.istteilvon::text;

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
    gebp.zustaendigestelle_land,
    gebp.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_besonderergebaeudepunkt gebp,
    alkis.ax_punktortau au
  WHERE gebp.gml_id::text = au.istteilvon::text
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
    gebp.zustaendigestelle_land,
    gebp.zustaendigestelle_stelle AS zst,
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
    st_force3d(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besonderergebaeudepunkt gebp,
    alkis.ax_punktortag ag
  WHERE gebp.gml_id::text = ag.istteilvon::text;

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
    topp.zustaendigestelle_land,
    topp.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_besonderertopographischerpunkt topp,
    alkis.ax_punktortau au
  WHERE topp.gml_id::text = au.istteilvon::text;

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
    gp.zustaendigestelle_land,
    gp.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_grenzpunkt gp,
    alkis.ax_punktortau au
  WHERE gp.gml_id::text = au.istteilvon::text
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
    gp.zustaendigestelle_land,
    gp.zustaendigestelle_stelle AS zst,
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
    st_force3d(ta.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_grenzpunkt gp,
    alkis.ax_punktortta ta
  WHERE gp.gml_id::text = ta.istteilvon::text;

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
    sip.zustaendigestelle_land,
    sip.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_sicherungspunkt sip,
    alkis.ax_punktortau au
  WHERE sip.gml_id::text = au.istteilvon::text;

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
    svp.zustaendigestelle_land,
    svp.zustaendigestelle_stelle AS zst,
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
    au.wkb_geometry
   FROM alkis.ax_sonstigervermessungspunkt svp,
    alkis.ax_punktortau au
  WHERE svp.gml_id::text = au.istteilvon::text;



COMMIT;
