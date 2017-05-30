BEGIN;

SET search_path = alkis, public;

-- Sicht zur Darstellung der Grenzpunkte:

CREATE OR REPLACE VIEW alkis.lk_grenzpunkte AS 
 SELECT COALESCE(p.oid, o.oid) AS oid,
    o.gml_id,
    o.beginnt AS o_beginnt,
    o.endet AS o_endet,
    p.beginnt AS p_beginnt,
    p.endet AS p_endet,
    o.punktkennung,
    ltrim(to_char("substring"(o.punktkennung, 1, 9)::integer, '999 99 99 99'::text), ' '::text) AS nbz,
    ltrim("substring"(o.punktkennung, 10, 6), '0'::text) AS pnr,
    o.zustaendigestelle_stelle,
    d.bezeichnung AS zustaendige_stelle,
    o.abmarkung_marke,
    l.beschreibung as verm_bedeutung,
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
    p.herkunft_source_description[0] as source_description,
    e.beschreibung AS datenerhebung,
    p.genauigkeitsstufe,
    m.beschreibung AS punktgenauigkeit,
    p.vertrauenswuerdigkeit,
    n.beschreibung AS punktvertrauenswuerdigkeit,
    p.wkb_geometry
   FROM alkis.ax_grenzpunkt o
     LEFT JOIN alkis.ax_punktortta p ON o.gml_id::text = ANY (p.istteilvon)
     LEFT JOIN alkis.ax_marke l ON l.wert = o.abmarkung_marke
     LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
     LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit
     LEFT JOIN alkis.ax_bemerkungzurabmarkung_grenzpunkt ba ON o.bemerkungzurabmarkung = ba.wert
     LEFT JOIN alkis.ax_datenerhebung e ON e.wert = ANY (p.herkunft_source_description::integer[])
     LEFT JOIN alkis.ax_dienststelle d ON o.zustaendigestelle_stelle = d.stelle
  WHERE d.endet IS NULL;

-- Sicht zur Darstellung der Zugehörigkeitshaken:

CREATE OR REPLACE VIEW alkis.s_zugehoerigkeitshaken_flurstueck AS 
 SELECT p.ogc_fid, p.beginnt, p.endet, p.wkb_geometry, p.drehwinkel * 57.296::double precision AS drehwinkel, f.flurstueckskennzeichen, f.abweichenderrechtszustand
   FROM alkis.ap_ppo p
   JOIN alkis.ax_flurstueck f ON f.gml_id::text = ANY (p.dientzurdarstellungvon::text[])
  WHERE p.art::text = 'Haken'::text;

-- Sicht zur Darstellung der Zuordnungspfeile:

CREATE OR REPLACE VIEW s_zuordungspfeil_flurstueck AS 
 SELECT l.ogc_fid,l.beginnt,l.endet, f.abweichenderrechtszustand, l.wkb_geometry
   FROM ap_lpo l
   JOIN ax_flurstueck f ON f.gml_id = any(l.dientzurdarstellungvon)
  WHERE l.art::text = 'Pfeil'
  AND ('DKKM1000' ~~ ANY (l.advstandardmodell));


-- Sicht zur Darstellung der Aufnahmepunkte:

CREATE OR REPLACE VIEW alkis.lk_ap AS 
SELECT o.oid, o.gml_id, o.beginnt, o.endet, o.punktkennung, o.sonstigeeigenschaft, o.vermarkung_marke, l.beschreibung as verm_bedeutung, p.genauigkeitsstufe, m.beschreibung as punktgenauigkeit, p.vertrauenswuerdigkeit, n.beschreibung as bedeutung, rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord, p.wkb_geometry
FROM alkis.ax_aufnahmepunkt o
LEFT JOIN alkis.ax_punktortau p ON o.gml_id = any(p.istteilvon)
LEFT JOIN alkis.ax_marke l ON l.wert = o.vermarkung_marke
LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

-- Sicht zur Darstellung der Sicherungspunkte:


CREATE OR REPLACE VIEW alkis.lk_sp AS 
SELECT o.oid, o.punktkennung, o.beginnt, o.endet, o.sonstigeeigenschaft, o.vermarkung_marke, l.beschreibung as verm_bedeutung, p.genauigkeitsstufe, m.beschreibung AS punktgenauigkeit, p.vertrauenswuerdigkeit, n.beschreibung as bedeutung, rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord, p.wkb_geometry
FROM alkis.ax_sicherungspunkt o
LEFT JOIN alkis.ax_punktortau p ON o.gml_id = any(p.istteilvon)
LEFT JOIN alkis.ax_marke l ON l.wert = o.vermarkung_marke
LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;


-- Sicht zur Darstellung der sonstigen Vermessungspunkte:

CREATE OR REPLACE VIEW alkis.lk_so_punkte AS 
SELECT o.oid,o.beginnt,o.endet, o.punktkennung, o.sonstigeeigenschaft, o.vermarkung_marke, l.beschreibung as verm_bedeutung, p.genauigkeitsstufe, m.beschreibung AS punktgenauigkeit, p.vertrauenswuerdigkeit, n.beschreibung as bedeutung, st_astext(p.wkb_geometry) AS koord, p.wkb_geometry
FROM alkis.ax_sonstigervermessungspunkt o
LEFT JOIN alkis.ax_punktortau p ON o.gml_id = any(p.istteilvon)
LEFT JOIN alkis.ax_marke l ON l.wert = o.vermarkung_marke
LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

-- Sicht zur Darstellung der Gebäude:

CREATE OR REPLACE VIEW alkis.lk_gebaeude AS 
 SELECT o.oid, o.ogc_fid, o.gml_id, o.beginnt, o.endet, o.gebaeudefunktion, p.beschreibung as bezeichner, w.wert AS weiterefunktion, o.name, o.zustand, z.beschreibung AS gebaeudezustand, o.objekthoehe,
 o.lagezurerdoberflaeche, o.dachform, d.beschreibung AS dach_bezeichner, o.hochhaus, o.herkunft_source_ax_datenerhebung[1] ax_datenerhebung, da.beschreibung AS herkunft, o.wkb_geometry
   FROM alkis.ax_gebaeude o
   LEFT JOIN alkis.ax_gebaeudefunktion p ON p.wert = o.gebaeudefunktion
   LEFT JOIN alkis.ax_dachform d ON d.wert = o.dachform
   LEFT JOIN alkis.ax_zustand_gebaeude z ON z.wert = o.zustand
   LEFT JOIN alkis.ax_weitere_gebaeudefunktion w ON w.wert = ANY (o.weiteregebaeudefunktion)
   LEFT JOIN alkis.ax_datenerhebung da ON da.wert = o.herkunft_source_ax_datenerhebung[1]::integer;

-- Sicht zur Darstellung besonderer Gebäude:

CREATE OR REPLACE VIEW alkis.lk_bes_gebaeude AS 
 SELECT o.oid, o.ogc_fid, o.gml_id, o.beginnt, o.endet, o.bauwerksfunktion, p.beschreibung, o.wkb_geometry
   FROM alkis.ax_sonstigesbauwerkodersonstigeeinrichtung o
   LEFT JOIN alkis.ax_bauwerksfunktion_sonstigesbauwerkodersonstigeeinrichtun p ON p.wert = o.bauwerksfunktion;

-- Sicht zur Darstellung der Gebäudepunkte:

CREATE OR REPLACE VIEW alkis.lk_gebaeudepunkte AS 
 SELECT DISTINCT o.oid, p.gml_id, o.beginnt, o.endet, o.punktkennung, m.wert, m.beschreibung AS punktgenauigkeit, p.vertrauenswuerdigkeit, n.beschreibung, p.wkb_geometry, rtrim(ltrim(st_astext(p.wkb_geometry), 'POINT('::text), ')'::text) AS koord
   FROM alkis.ax_besonderergebaeudepunkt o
   LEFT JOIN alkis.ax_punktortag p ON o.gml_id = any(p.istteilvon)
   LEFT JOIN alkis.ax_genauigkeitsstufe_punktort m ON m.wert = p.genauigkeitsstufe
   LEFT JOIN alkis.ax_vertrauenswuerdigkeit_punktort n ON n.wert = p.vertrauenswuerdigkeit;

-- Sicht zur Darstellung der Nutzungsarten:

CREATE OR REPLACE VIEW alkis.lk_nutzungen AS 
SELECT n.oid, n.gml_id, n.beginnt, n.endet, n.werteart1, n.werteart2, n.info, n.zustand, n.name, n.bezeichnung, 
nas.nutzungsartengruppe::text||nas.nutzungsart::text||nas.untergliederung1::text||nas.untergliederung2::text as nutzungsartschluessel,
nag.bereich, nag.gruppe as nutzungsartengruppe, na.nutzungsart, nu1.untergliederung1, nu2.untergliederung2, 
nas.nutzungsartengruppe as nutzungsartengruppeschl,nas.nutzungsart as nutzungsartschl,nas.untergliederung1 as untergliederung1schl,nas.untergliederung2 as untergliederung2schl,
n.wkb_geometry as the_geom
FROM alkis.n_nutzung n 
LEFT JOIN alkis.n_nutzungsartenschluessel nas on n.nutzungsartengruppe = nas.nutzungsartengruppe and n.werteart1 = nas.werteart1 and n.werteart2 = nas.werteart2 
LEFT JOIN alkis.n_nutzungsartengruppe nag on nas.nutzungsartengruppe = nag.schluessel 
LEFT JOIN alkis.n_nutzungsart na on nas.nutzungsartengruppe = na.nutzungsartengruppe and nas.nutzungsart = na.schluessel 
LEFT JOIN alkis.n_untergliederung1 nu1 on nas.nutzungsartengruppe = nu1.nutzungsartengruppe and nas.nutzungsart = nu1.nutzungsart and nas.untergliederung1 = nu1.schluessel
LEFT JOIN alkis.n_untergliederung2 nu2 on nas.nutzungsartengruppe = nu2.nutzungsartengruppe and nas.nutzungsart = nu2.nutzungsart and nas.untergliederung1 = nu2.untergliederung1 and nas.untergliederung2 = nu2.schluessel;

-- Sicht zur Darstellung der Muster- und Vergleichsstücke:

CREATE OR REPLACE VIEW alkis.lk_muster_vergleichsstueck AS 
 SELECT mu.oid, mu.ogc_fid, mu.gml_id, mu.identifier, mu.beginnt, mu.endet, mu.advstandardmodell, mu.anlass, mu.merkmal, me.beschreibung AS gruppe, mu.nummer, mu.kulturart, ku.beschreibung AS kultur, mu.bodenart, art.beschreibung, mu.zustandsstufeoderbodenstufe, zu.beschreibung AS zustand_bodenstufe, mu.entstehungsartoderklimastufewasserverhaeltnisse, ent.beschreibung AS entstehung, mu.bodenzahlodergruenlandgrundzahl, mu.ackerzahlodergruenlandzahl, mu.wkb_geometry AS the_geom
   FROM alkis.ax_musterlandesmusterundvergleichsstueck mu
   LEFT JOIN alkis.ax_merkmal_musterlandesmusterundvergleichsstueck me ON mu.merkmal = me.wert
   LEFT JOIN alkis.ax_bodenart_bodenschaetzung art ON mu.bodenart = art.wert
   LEFT JOIN alkis.ax_kulturart_bodenschaetzung ku ON mu.kulturart = ku.wert
   LEFT JOIN alkis.ax_zustandsstufeoderbodenstufe_bodenschaetzung zu ON mu.zustandsstufeoderbodenstufe = zu.wert
   LEFT JOIN alkis.ax_entstehungsartoderklimastufewasserverhaeltnisse_bodensc ent ON ent.wert = ANY(mu.entstehungsartoderklimastufewasserverhaeltnisse);

-- Sicht zur Darstellung der Grablöcher:

CREATE OR REPLACE VIEW alkis.lk_grabloch AS 
 SELECT gr.oid, gr.ogc_fid, gr.gml_id, gr.identifier, gr.beginnt, gr.endet, gr.advstandardmodell, gr.anlass, gr.art, gr.name, gr.bedeutung, be.beschreibung, gr.ingemarkung_land, gr.nummerierungsbezirk, gr.ingemarkung_gemarkungsnummer, gr.nummerdesgrablochs, gr.wkb_geometry AS the_geom
   FROM alkis.ax_grablochderbodenschaetzung gr, alkis.ax_bedeutung_grablochderbodenschaetzung be
  WHERE be.wert = ANY (gr.bedeutung);


-- Sicht zur Darstellung der Hausnummern:

CREATE OR REPLACE VIEW alkis.s_hausnummer_gebaeude AS 
 SELECT p.ogc_fid, p.beginnt, p.endet, l.hausnummer, p.drehwinkel * 57.296::double precision AS drehwinkel, p.wkb_geometry
   FROM alkis.ap_pto p
   JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id::text = ANY (p.dientzurdarstellungvon) AND l.endet IS NULL
  WHERE p.art = 'HNR';

COMMIT;
