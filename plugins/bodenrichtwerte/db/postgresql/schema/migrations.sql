
### migration 2014-08-03 00:00:00

# Version 2.0.0

BEGIN;

CREATE SCHEMA bodenrichtwerte;

SET search_path = bodenrichtwerte, public;

SET default_with_oids = true;

CREATE TABLE bw_zonen (   
     gemeinde integer,
     gemarkung integer,
     ortsteilname character varying(60),
     postleitzahl integer,
     zonentyp character varying(256),
     gutachterausschuss integer,
     bodenrichtwertnummer serial,
     oertliche_bezeichnung character varying(256),
     bodenrichtwert real,
     stichtag date,
     basiskarte character varying(8),
     entwicklungszustand character varying(2),
     beitragszustand character varying(1),
     nutzungsart character varying(7),
     ergaenzende_nutzung character varying(30),
     bauweise character varying(2),
     geschosszahl character varying(9),
     grundflaechenzahl character varying(9),
     geschossflaechenzahl character varying(11),
     baumassenzahl character varying(9),
     flaeche character varying(12),
     tiefe character varying(8),
     breite character varying(8),
     wegeerschliessung character varying(1),
     ackerzahl character varying(7),
     gruenlandzahl character varying(7),
     aufwuchs character varying(2),
     verfahrensgrund character varying(4),
     verfahrensgrund_zusatz character varying(2),
     bemerkungen character varying(256),
     erschliessungsverhaeltnisse integer,
     bedarfswert real,
     bodenart character varying(6),
     brwu real,
     brws real,
     brwb real
 )
 WITH OIDS;
 
 SELECT AddGeometryColumn('bodenrichtwerte', 'bw_zonen','textposition',25833,'POINT', 2);
 CREATE INDEX bw_zonen_textposition_gist ON bw_zonen USING GIST (textposition);
 
 SELECT AddGeometryColumn('bodenrichtwerte', 'bw_zonen','the_geom',25833,'GEOMETRY', 2);
 CREATE INDEX bw_zonen_the_geom_gist ON bw_zonen USING GIST (the_geom);
 
 
  
 
 -- View zum Austausch der BRWs
 
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