 -- View zum Austausch der BRWs
 
BEGIN;

SET search_path = bodenrichtwerte, public;
SET default_with_oids = true;
 
CREATE OR REPLACE VIEW boris205_view AS 
SELECT bw.oid, bw.gemeinde::text || '0000'::text AS gesl, g.gemeindename AS gena, bw.gutachterausschuss AS gasl, gm.gemkgschl AS genu, bw.ortsteilname AS ortst, bw.bodenrichtwertnummer AS wnum, 
       CASE
           WHEN bw.brwu IS NOT NULL THEN bw.brwu
           WHEN bw.brwb IS NOT NULL THEN bw.brwb
           ELSE bw.bodenrichtwert
       END AS brw, bw.stichtag AS stag, 1 AS brke, 1000 AS basma, '25833'::text AS bezug, bw.entwicklungszustand AS entw, bw.beitragszustand AS beit, bw.nutzungsart AS nuta, bw.ergaenzende_nutzung AS ergnuta, bw.bauweise AS bauw, bw.geschosszahl AS gez, bw.geschossflaechenzahl AS wgfz, bw.grundflaechenzahl AS grz, bw.baumassenzahl AS bmz, bw.flaeche AS flae, bw.tiefe AS gtie, bw.breite AS gbrei, bw.verfahrensgrund AS verg, 
       CASE
           WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SU'::character varying
           WHEN bw.brwu IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EU'::character varying
           WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'San'::text THEN 'SB'::character varying
           WHEN bw.brwb IS NOT NULL AND bw.verfahrensgrund::text = 'Entw'::text THEN 'EB'::character varying
           ELSE bw.verfahrensgrund_zusatz
       END AS verf, bw.bodenart AS bod, bw.ackerzahl AS acza, bw.gruenlandzahl AS grza, 'link_zur_umrechnungstabelle'::text AS lumnum, bw.zonentyp AS typ, bw.the_geom
  FROM bodenrichtwerte.bw_zonen bw
  LEFT JOIN alb_v_gemeinden g ON bw.gemeinde = g.gemeinde
  LEFT JOIN alb_v_gemarkungen gm ON bw.gemarkung = gm.gemkgschl;
	
COMMIT;