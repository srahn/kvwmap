TRUNCATE nachweisverwaltung.fp_punkte_alkis; 

SELECT setval('nachweisverwaltung.fp_punkte_alkis_ogc_fid_seq', 1, true); 

-- 
-- Festpunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, anlass, advstandardmodell, land, zst, pkn, pktnr, rw, hw, hoe, hop, par, abm, hin, pru, datei, wkb_geometry ) 
SELECT 
   * 
FROM 
  nachweisverwaltung.fp_pp_afismv;

-- 
-- Aufnahmepunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, abm, rho, 
    kds, des, nam, idn, vwl, gst, kst, hin, hat, datei, wkb_geometry
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_aufnahmepunkt;

-- 
-- Sicherungspunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, abm, rho, 
    kds, des, nam, idn, vwl, gst, kst, hin, beziehtsichauf, gehoertzu, datei, wkb_geometry
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_sicherungspunkt;

-- 
-- Sonstige Vermessungspunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, abm, rho, 
    kds, des, nam, idn, vwl, gst, kst, hin, hat, datei, wkb_geometry
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_sonstigervermessungspunkt;

-- 
-- Grenzpunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, abm, fgp, bpn, aam, bza, zde, rho, 
    kds, des, nam, art, idn, vwl, lzk, gst, kst, pru, hin, fdv, zeigtauf, hat, 
    beziehtsichauf, gehoertzu, datei, wkb_geometry
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_grenzpunkt;

-- 
-- Besondere Gebaeudepunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, 
    kds, des, nam, art, idn, vwl, gst, kst, hin, wkb_geometry 
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_besonderergebaeudepunkt;

-- 
-- Besondere Bauwerkspunkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, 
    kds, des, nam, art, idn, vwl, gst, kst, hin, wkb_geometry 
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_besondererbauwerkspunkt;

-- 
-- Besondere Topographische Punkte einlesen
-- 
INSERT INTO nachweisverwaltung.fp_punkte_alkis 
  ( gml_id, gml_id_punktort, identifier, beginnt, endet, beginnt_punktort, endet_punktort, 
    advstandardmodell, sonstigesmodell, anlass, land, zst, pkn, pktnr, rw, hw, par, 
    soe, soe_alk_pkz, soe_alk_par, soe_alk_vma, soe_weitere, 
    kds, des, nam, idn, vwl, gst, kst, hin, wkb_geometry 
  ) 
SELECT 
  * 
FROM 
  nachweisverwaltung.fp_besonderertopographischerpunkt;

