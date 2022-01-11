BEGIN;

DROP VIEW alkis.lk_bes_gebaeude;

CREATE OR REPLACE VIEW alkis.lk_bes_gebaeude AS 
 SELECT 
    o.ogc_fid,
    o.gml_id,
    o.beginnt,
    o.endet,
    o.bauwerksfunktion,
    p.beschreibung,
    o.wkb_geometry
   FROM alkis.ax_sonstigesbauwerkodersonstigeeinrichtung o
     LEFT JOIN alkis.ax_bauwerksfunktion_sonstigesbauwerkodersonstigeeinrichtun p ON p.wert = o.bauwerksfunktion;
		 
		 
DROP VIEW alkis.lk_gebaeude;

CREATE OR REPLACE VIEW alkis.lk_gebaeude AS 
 SELECT 
    o.ogc_fid,
    o.gml_id,
    o.beginnt,
    o.endet,
    o.gebaeudefunktion,
    p.beschreibung AS bezeichner,
    w.wert AS weiterefunktion,
    o.name,
    o.zustand,
    z.beschreibung AS gebaeudezustand,
    o.objekthoehe,
    o.lagezurerdoberflaeche,
    o.dachform,
    d.beschreibung AS dach_bezeichner,
    o.hochhaus,
    o.herkunft_source_source_ax_datenerhebung[1] AS ax_datenerhebung,
    da.beschreibung AS herkunft,
    o.wkb_geometry
   FROM alkis.ax_gebaeude o
     LEFT JOIN alkis.ax_gebaeudefunktion p ON p.wert = o.gebaeudefunktion
     LEFT JOIN alkis.ax_dachform d ON d.wert = o.dachform
     LEFT JOIN alkis.ax_zustand_gebaeude z ON z.wert = o.zustand
     LEFT JOIN alkis.ax_weitere_gebaeudefunktion w ON w.wert = ANY (o.weiteregebaeudefunktion)
     LEFT JOIN alkis.ax_datenerhebung da ON da.wert = o.herkunft_source_source_ax_datenerhebung[1]::integer;		


DROP VIEW alkis.lk_grabloch;

CREATE OR REPLACE VIEW alkis.lk_grabloch AS 
 SELECT 
    gr.ogc_fid,
    gr.gml_id,
    gr.gml_id AS identifier,
    gr.beginnt,
    gr.endet,
    gr.advstandardmodell,
    gr.anlass,
    gr.zeigtaufexternes_art AS art,
    gr.zeigtaufexternes_name AS name,
    gr.bedeutung,
    be.beschreibung,
    gr.ingemarkung_land,
    gr.kennziffer_nummerierungsbezirk AS nummerierungsbezirk,
    gr.ingemarkung_gemarkungsnummer,
    gr.nummerdesgrablochs,
    gr.wkb_geometry AS the_geom
   FROM alkis.ax_grablochderbodenschaetzung gr,
    alkis.ax_bedeutung_grablochderbodenschaetzung be
  WHERE be.wert = ANY (gr.bedeutung);


DROP VIEW alkis.lk_muster_vergleichsstueck;

CREATE OR REPLACE VIEW alkis.lk_muster_vergleichsstueck AS 
 SELECT 
    mu.ogc_fid,
    mu.gml_id,
    mu.gml_id AS identifier,
    mu.beginnt,
    mu.endet,
    mu.advstandardmodell,
    mu.anlass,
    mu.merkmal,
    me.beschreibung AS gruppe,
    mu.nummer,
    mu.kulturart,
    ku.beschreibung AS kultur,
    mu.bodenart,
    art.beschreibung,
    mu.zustandsstufeoderbodenstufe,
    zu.beschreibung AS zustand_bodenstufe,
    mu.entstehungsartoderklimastufewasserverhaeltnisse,
    ent.beschreibung AS entstehung,
    mu.bodenzahlodergruenlandgrundzahl,
    mu.ackerzahlodergruenlandzahl,
    mu.wkb_geometry AS the_geom
   FROM alkis.ax_musterlandesmusterundvergleichsstueck mu
     LEFT JOIN alkis.ax_merkmal_musterlandesmusterundvergleichsstueck me ON mu.merkmal = me.wert
     LEFT JOIN alkis.ax_bodenart_bodenschaetzung art ON mu.bodenart = art.wert
     LEFT JOIN alkis.ax_kulturart_bodenschaetzung ku ON mu.kulturart = ku.wert
     LEFT JOIN alkis.ax_zustandsstufeoderbodenstufe_bodenschaetzung zu ON mu.zustandsstufeoderbodenstufe = zu.wert
     LEFT JOIN alkis.ax_entstehungsartoderklimastufewasserverhaeltnisse_bodensc ent ON ent.wert = ANY (mu.entstehungsartoderklimastufewasserverhaeltnisse);	

COMMIT;
