BEGIN;

ALTER TABLE nachweisverwaltung.fp_punkte2antraege RENAME pkz TO pkn;

CREATE TABLE nachweisverwaltung.fp_punkte_alkis
(
  ogc_fid serial NOT NULL,
  gml_id character varying, -- Identifikator, global eindeutig
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
  zst integer, -- Stelle
  pkn character varying, -- Punktkennung
  pktnr character varying(6), -- Punktnummer
  rw character varying(11), -- Rechtswert
  hw character varying(11), -- Hochwert
  hoe character varying(9), -- Hoehe
  hop character varying(9), -- Hoehe, Oberkante Pfeiler im STN
  par character varying(4), -- Punktart
  soe character varying, -- sonstige Eigenschaft
  soe_alk_pkz character varying, -- sonstige Eigenschaft - ALK-Punktkennzeichen
  soe_alk_par character varying, -- sonstige Eigenschaft - ALK-Punktart
  soe_alk_vma character varying, -- sonstige Eigenschaft - ALK-Vermarkungsart
  soe_weitere character varying, -- sonstige Eigenschaft - weitere
  abm integer, -- Ab-/Vermarkung_Marke
  fgp character varying, -- festgestellter Grenzpunkt
  bpn character varying, -- besondere Punktnummer
  aam integer, -- Ausgesetzte Abmarkung
  bza integer, -- Bemerkung zur Abmarkung
  zde character varying, -- Zeitpunkt der Entstehung
  rho double precision, -- relative Höhe
  kds character varying, -- Kartendarstellung
  des integer, -- AX_Datenerhebung_Punktort
  nam character varying, -- Name
  art character varying[], -- Art des Punktes
  idn character varying, -- Individualname
  vwl integer, -- Vertrauenswürdigkeit
  lzk boolean, -- Lagezuverlaessigkeit
  gst integer, -- Genauigkeitsstufe
  kst integer, -- Koordinatenstatus
  pru character(20), -- Ueberpruefungsdatum
  hin character varying, -- Hinweise
  fdv character varying, -- AA-Fachdatenverbindung
  zeigtauf character varying,
  hat character varying,
  beziehtsichauf character varying,
  gehoertzu character varying,
  datei character varying,
  wkb_geometry geometry,
  CONSTRAINT fp_punkte_alkis_pkey PRIMARY KEY (ogc_fid),
  CONSTRAINT enforce_dims_wbk_geometry CHECK (st_ndims(wkb_geometry) = 3),
  CONSTRAINT enforce_geotype_wbk_geometry CHECK (geometrytype(wkb_geometry) = 'POINT'::text OR wkb_geometry IS NULL),
  CONSTRAINT enforce_srid_wbk_geometry CHECK (st_srid(wkb_geometry) = 25833)
)
WITH (
  OIDS=TRUE
);
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
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.idn IS 'Individualname';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.vwl IS 'Vertrauenswürdigkeit';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.lzk IS 'Lagezuverlaessigkeit';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.gst IS 'Genauigkeitsstufe';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.kst IS 'Koordinatenstatus';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.pru IS 'Ueberpruefungsdatum';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.hin IS 'Hinweise';
COMMENT ON COLUMN nachweisverwaltung.fp_punkte_alkis.fdv IS 'AA-Fachdatenverbindung';


-- Index: nachweisverwaltung.fp_punkte_alkis_bsa_idx

-- DROP INDEX nachweisverwaltung.fp_punkte_alkis_bsa_idx;

CREATE INDEX fp_punkte_alkis_bsa_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (beziehtsichauf COLLATE pg_catalog."default");

-- Index: nachweisverwaltung.fp_punkte_alkis_geom_idx

-- DROP INDEX nachweisverwaltung.fp_punkte_alkis_geom_idx;

CREATE INDEX fp_punkte_alkis_geom_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING gist
  (wkb_geometry);

-- Index: nachweisverwaltung.fp_punkte_alkis_gz_idx

-- DROP INDEX nachweisverwaltung.fp_punkte_alkis_gz_idx;

CREATE INDEX fp_punkte_alkis_gz_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (gehoertzu COLLATE pg_catalog."default");

-- Index: nachweisverwaltung.fp_punkte_alkis_hat_idx

-- DROP INDEX nachweisverwaltung.fp_punkte_alkis_hat_idx;

CREATE INDEX fp_punkte_alkis_hat_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (hat COLLATE pg_catalog."default");

-- Index: nachweisverwaltung.fp_punkte_alkis_za_idx

-- DROP INDEX nachweisverwaltung.fp_punkte_alkis_za_idx;

CREATE INDEX fp_punkte_alkis_za_idx
  ON nachweisverwaltung.fp_punkte_alkis
  USING btree
  (zeigtauf COLLATE pg_catalog."default");


  
  -- DROP VIEW alkis.lk_fp_aufnahmepunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_aufnahmepunkt AS 
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
    ap.land,
    ap.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    au.name AS nam,
    au.individualname AS idn,
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




-- DROP VIEW alkis.lk_fp_besondererbauwerkspunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_besondererbauwerkspunkt AS 
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
    bwp.land,
    bwp.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    au.name AS nam,
    NULL::character varying[] AS art,
    au.individualname AS idn,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    st_force_3d(au.wkb_geometry) AS wkb_geometry
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
    bwp.land,
    bwp.stelle AS zst,
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
    ag.ax_datenerhebung_punktort AS des,
    ag.name AS nam,
    ag.art,
    NULL::character varying AS idn,
    ag.vertrauenswuerdigkeit AS vwl,
    ag.genauigkeitsstufe AS gst,
    ag.koordinatenstatus AS kst,
    ag.hinweise AS hin,
    st_force_3d(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besondererbauwerkspunkt bwp,
    alkis.ax_punktortag ag
  WHERE bwp.gml_id::text = ag.istteilvon::text;




-- DROP VIEW alkis.lk_fp_besonderergebaeudepunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_besonderergebaeudepunkt AS 
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
    gebp.land,
    gebp.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    gebp.name AS nam,
    string_to_array(gebp.art::text, ''::text) AS art,
    au.individualname AS idn,
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
    gebp.land,
    gebp.stelle AS zst,
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
    ag.ax_datenerhebung_punktort AS des,
    gebp.name AS nam,
    string_to_array(gebp.art::text, ''::text) AS art,
    NULL::character varying AS idn,
    ag.vertrauenswuerdigkeit AS vwl,
    ag.genauigkeitsstufe AS gst,
    ag.koordinatenstatus AS kst,
    ag.hinweise AS hin,
    st_force_3d(ag.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_besonderergebaeudepunkt gebp,
    alkis.ax_punktortag ag
  WHERE gebp.gml_id::text = ag.istteilvon::text;




-- DROP VIEW alkis.lk_fp_besonderertopographischerpunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_besonderertopographischerpunkt AS 
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
    topp.land,
    topp.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    au.name AS nam,
    au.individualname AS idn,
    au.vertrauenswuerdigkeit AS vwl,
    au.genauigkeitsstufe AS gst,
    au.koordinatenstatus AS kst,
    au.hinweise AS hin,
    au.wkb_geometry
   FROM alkis.ax_besonderertopographischerpunkt topp,
    alkis.ax_punktortau au
  WHERE topp.gml_id::text = au.istteilvon::text;


-- DROP VIEW alkis.lk_fp_grenzpunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_grenzpunkt AS 
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
    gp.land,
    gp.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    gp.name AS nam,
    NULL::character varying[] AS art,
    au.individualname AS idn,
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
    gp.land,
    gp.stelle AS zst,
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
    ta.ax_datenerhebung_punktort AS des,
    gp.name AS nam,
    NULL::character varying[] AS art,
    ''::character varying AS idn,
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
    st_force_3d(ta.wkb_geometry) AS wkb_geometry
   FROM alkis.ax_grenzpunkt gp,
    alkis.ax_punktortta ta
  WHERE gp.gml_id::text = ta.istteilvon::text;




-- DROP VIEW alkis.lk_fp_sicherungspunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_sicherungspunkt AS 
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
    sip.land,
    sip.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    sip.name AS nam,
    au.individualname AS idn,
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




-- DROP VIEW alkis.lk_fp_sonstigervermessungspunkt;

CREATE OR REPLACE VIEW alkis.lk_fp_sonstigervermessungspunkt AS 
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
    svp.land,
    svp.stelle AS zst,
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
    au.ax_datenerhebung_punktort AS des,
    au.name AS nam,
    au.individualname AS idn,
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
