
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
   
COMMIT;