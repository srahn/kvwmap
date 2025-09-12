 -- View zum Austausch der BRWs
 
BEGIN;

SET search_path = bodenrichtwerte, public;
SET default_with_oids = true;
 
CREATE OR REPLACE VIEW bw_boris_view AS 
	SELECT (bw.gutachterausschuss || '_'::text) || lpad(bw.bodenrichtwertnummer::character(13)::text, 7, '0'::text) AS brw_id, 
    k.bezeichnung AS kreisname, k.schluesselgesamt AS kreisschl, 
    ag.amt_name AS geverna, ag.gemeinde_schluessel AS geverschl, 
    ag.regionalschluessel AS gesl, 
    NULL::text AS geteilschl, 
    g.bezeichnung AS gena, bw.gutachterausschuss AS gasl, 
    'Landkreis Ludwigslust-Parchim'::text AS gabe, 
    substring(bw.gemarkung::text, 3, 4) AS genu, NULL::text AS gema, 
    bw.ortsteilname AS ortst, 
    lpad(bw.bodenrichtwertnummer::character(13)::text, 8, '0'::text) AS wnum, 
        CASE
            WHEN bw.brwu IS NOT NULL THEN round(bw.brwu::numeric, 2)
            WHEN bw.brwb IS NOT NULL THEN round(bw.brwb::numeric, 2)
            ELSE round(bw.bodenrichtwert::numeric, 2)
        END AS brw, 
    to_char(bw.stichtag::timestamp with time zone, 'DD.MM.YYYY'::text) AS stag, 
    '1'::text AS brke, bw.bedarfswert::numeric(6,2) AS bedw, 
    bw.postleitzahl AS plz, bw.basiskarte AS basbe, '1000'::text AS basma, 
    'ETRS89_UTM33'::text AS bezug, bw.entwicklungszustand AS entw, 
    bw.beitragszustand AS beit, bw.nutzungsart AS nuta, 
    bw.ergaenzende_nutzung AS ergnuta, bw.bauweise AS bauw, 
    bw.geschosszahl AS gez, bw.geschossflaechenzahl AS wgfz, 
    bw.grundflaechenzahl AS grz, bw.baumassenzahl AS bmz, bw.flaeche AS flae, 
    bw.tiefe AS gtie, bw.breite AS gbrei, 
    bw.erschliessungsverhaeltnisse AS erve, bw.verfahrensgrund AS verg, 
        CASE
            WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SU'::character varying
            WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EU'::character varying
            WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SB'::character varying
            WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EB'::character varying
            ELSE bw.verfahrensgrund_zusatz
        END AS verf, 
    bw.bodenart AS bod, bw.ackerzahl AS acza, bw.gruenlandzahl AS grza, 
    bw.aufwuchs AS aufw, bw.wegeerschliessung AS weer, bw.bemerkungen AS bem, 
    ''::text AS frei, bw.oertliche_bezeichnung AS brzname, '0'::text AS umdart, 
    ('http://pfad/zur/umrechungstabelle/tabelle'::text || bw.stichtag) || '.pdf'::text AS lumnum, 
    bw.zonentyp AS typ, 
    to_char(bw.stichtag::timestamp with time zone + '1 day'::interval, 'DD.MM.YYYY'::text) AS guelt_von, 
    to_char(bw.stichtag::timestamp with time zone + '2 years'::interval, 'DD.MM.YYYY'::text) AS guelt_bis, 
    bw.the_geom AS geometrie
   FROM alkis.ax_kreisregion k, 
    bodenrichtwerte.bw_zonen bw
   LEFT JOIN alkis.ax_gemeinde g ON bw.gemeinde = g.schluesselgesamt
   LEFT JOIN alkis.lk_aemtergemeinden ag ON ag.gemeinde_schluessel::integer = bw.gemeinde
  WHERE k.bezeichnung::text = 'Landkreis Ludwigslust-Parchim'::text AND k.endet IS NULL AND g.endet IS NULL;
	
COMMIT;