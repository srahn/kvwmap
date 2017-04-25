BEGIN;

SET client_encoding = 'UTF-8';
SET default_with_oids = true;
SET search_path = alkis, public;


CREATE TABLE n_nutzung(
	gml_id		varchar,
	beginnt		character(20),	    
	endet		character(20),				
	nutzungsartengruppe	integer NOT NULL,
	werteart1		integer NOT NULL,
	werteart2		integer NOT NULL,
	info		     integer,
	zustand		integer,
	"name"		varchar,
	bezeichnung	varchar,
	CONSTRAINT	n_nutzung_pk      PRIMARY KEY (gml_id, beginnt)
);

-- ALTER TABLE nutzung ADD COLUMN beginnt character(20);
-- ALTER TABLE nutzung DROP CONSTRAINT nutzung_pk;
-- ALTER TABLE nutzung ADD  CONSTRAINT nutzung_pk PRIMARY KEY(gml_id, beginnt);


SELECT AddGeometryColumn('n_nutzung','wkb_geometry','25833','POLYGON',2);
-- Vereinzelt vorkommende MULTIPOLYGON, zulässig?


-- Geometrischer Index für die räumliche Suche
CREATE INDEX n_nutzung_geom_idx ON n_nutzung USING gist (wkb_geometry);

-- Kommentare
COMMENT ON TABLE  n_nutzung             IS 'Zusammenfassung von 26 Tabellen des Objektbereiches "Tatsächliche Nutzung".';
COMMENT ON COLUMN n_nutzung.nutzungsartengruppe IS 'Tausenderstelle = Nutzungsartengruppe nach ALKIS-nutzungsartenschluessel MV';
COMMENT ON COLUMN n_nutzung.werteart1 IS 'Bestimmende Werteart 1 nach ALKIS-Objektartenkatalog MV';
COMMENT ON COLUMN n_nutzung.werteart2 IS 'Bestimmende Werteart 2 nach ALKIS-Objektartenkatalog MV';
COMMENT ON COLUMN n_nutzung.info        IS 'Weitere verschlüsselte Information zur Nutzung. Aus verschiedenen Feldern importiert. Siehe "nutzung_meta.fldinfo".';
COMMENT ON COLUMN n_nutzung.name        IS 'NAM Eigenname';
COMMENT ON COLUMN n_nutzung.bezeichnung IS 'weitere unverschlüsselte Information wie Zweitname, Bezeichnung, fachliche Nummerierung usw.';
COMMENT ON COLUMN n_nutzung.zustand     IS 'ZUS "Zustand" beschreibt, ob der Abschnitt ungenutzt ist.';



CREATE TABLE n_nutzungsartenschluessel (
    nutzungsartengruppe integer NOT NULL,
    nutzungsart integer,
    untergliederung1 integer,
    untergliederung2 integer,
    objektart integer NOT NULL,
    werteart1 integer,
    werteart2 integer
);


COMMENT ON COLUMN n_nutzungsartenschluessel.nutzungsartengruppe IS 'Tausenderstelle = Nutzungsartengruppe nach ALKIS-nutzungsartenschluessel MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.nutzungsart IS 'Hunderterstelle = Nutzungsart nach ALKIS-nutzungsartenschluessel MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.untergliederung1 IS 'Zehnerstelle = Untergliederung 1. Stufe nach ALKIS-nutzungsartenschluessel MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.untergliederung2 IS 'Einerstelle = Untergliederung 2. Stufe nach ALKIS-nutzungsartenschluessel MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.objektart IS 'Objektart nach ALKIS-Objektartenkatalog MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.werteart1 IS 'Bestimmende Werteart 1 nach ALKIS-Objektartenkatalog MV';
COMMENT ON COLUMN n_nutzungsartenschluessel.werteart2 IS 'Bestimmende Werteart 2 nach ALKIS-Objektartenkatalog MV';


INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 2, 1, 41002, 2521, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 2, 2, 41002, 2522, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 3, 1, 41002, 2531, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 3, 2, 41002, 2532, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 5, 1, 41002, 2551, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 5, 2, 41002, 2552, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 6, 1, 41002, 2561, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 6, 2, 41002, 2562, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 1, 41002, 1740, 7000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 2, 41002, 1740, 1000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 3, 41002, 1740, 4000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 4, 41002, 1740, 2000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 5, 41002, 1740, 3000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 6, 41002, 1740, 6000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 7, 41002, 1740, 8000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 8, 41002, 1740, 5000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 7, 1, 41002, 2571, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 7, 2, 41002, 2572, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 8, 1, 41002, 2581, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 8, 2, 41002, 2582, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 1, 1, 41002, 2611, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 1, 2, 41002, 2612, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 2, 1, 41002, 2621, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 2, 2, 41002, 2622, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 2, 3, 41002, 2623, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 0, 1, 41002, 1701, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 0, 1, 41002, 2501, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 0, 2, 41002, 2502, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 0, 1, 41002, 2601, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 0, 2, 41002, 2602, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 1, 0, 41002, 1710, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 2, 0, 41002, 1720, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 3, 0, 41002, 1730, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 4, 0, 41002, 1740, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 5, 0, 41002, 1750, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 1, 41002, 2510, 1000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 2, 41002, 2510, 2000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 3, 41002, 2510, 3000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 4, 41002, 2510, 4000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 5, 41002, 2510, 5000);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 6, 0, 41002, 1760, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 7, 0, 41002, 1770, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 8, 0, 41002, 1780, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 9, 0, 41002, 1790, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 1, 0, 41002, 1410, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 2, 0, 41002, 1420, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 3, 0, 41002, 1430, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 4, 0, 41002, 1440, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 5, 0, 41002, 1450, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 6, 0, 41002, 1460, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 7, 0, 41002, 1470, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 8, 0, 41002, 1480, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 9, 0, 41002, 1490, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 1, 0, 41002, 2510, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 2, 0, 41002, 2520, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 3, 0, 41002, 2530, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 4, 0, 41002, 2540, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 5, 0, 41002, 2550, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 6, 0, 41002, 2560, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 7, 0, 41002, 2570, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 8, 0, 41002, 2580, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 1, 0, 41002, 2610, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 2, 0, 41002, 2620, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 3, 0, 41002, 2630, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 4, 0, 41002, 2640, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 1, 0, 0, 41002, 1700, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 2, 0, 0, 41002, 1400, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 3, 0, 0, 41002, 2500, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 4, 0, 0, 41002, 2600, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 2, 41005, 1002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 3, 41005, 1003, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 4, 41005, 1004, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 5, 41005, 1005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 6, 41005, 1006, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 7, 41005, 1007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 8, 41005, 1008, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 9, 41005, 1009, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 2, 1, 41005, 1011, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 2, 2, 41005, 1012, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 2, 1, 1, 41006, 2710, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 2, 1, 2, 41006, 2720, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 1, 1, 41008, 4211, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 2, 1, 41008, 4321, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 3, 1, 41008, 4331, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 3, 1, 41008, 4431, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 0, 1, 41008, 4101, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 0, 1, 41008, 4301, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 1, 0, 41006, 2110, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 2, 0, 41006, 2120, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 3, 0, 41006, 2130, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 4, 0, 41006, 2140, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 5, 0, 41006, 2150, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 6, 0, 41006, 2160, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 2, 1, 0, 41006, 2730, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 1, 0, 41007, 1110, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 2, 0, 41007, 1120, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 3, 0, 41007, 1130, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 4, 0, 41007, 1140, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 5, 0, 41007, 1150, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 6, 0, 41007, 1160, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 7, 0, 41007, 1170, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 3, 1, 0, 41007, 1310, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 3, 2, 0, 41007, 1320, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 1, 0, 41008, 4110, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 2, 0, 41008, 4120, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 3, 0, 41008, 4130, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 4, 0, 41008, 4140, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 5, 0, 41008, 4150, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 6, 0, 41008, 4160, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 7, 0, 41008, 4170, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 1, 0, 41008, 4210, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 2, 0, 41008, 4220, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 3, 0, 41008, 4230, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 4, 0, 41008, 4240, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 5, 0, 41008, 4250, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 6, 0, 41008, 4260, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 7, 0, 41008, 4270, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 8, 0, 41008, 4280, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 9, 0, 41008, 4290, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 1, 0, 41008, 4310, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 2, 0, 41008, 4320, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 3, 0, 41008, 4330, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 1, 0, 41008, 4410, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 2, 0, 41008, 4420, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 3, 0, 41008, 4430, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 4, 0, 41008, 4440, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 5, 0, 41008, 4450, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 6, 0, 41008, 4460, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 7, 0, 41008, 4470, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 1, 1, 0, 43001, 1011, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 1, 2, 0, 43001, 1012, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 1, 3, 0, 43001, 1013, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 2, 1, 0, 43001, 1021, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 2, 3, 41005, 1013, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 1, 41005, 2001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 2, 41005, 2002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 3, 41005, 2003, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 4, 41005, 2004, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 5, 41005, 2005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 6, 41005, 2006, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 7, 41005, 2007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 8, 41005, 2008, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 9, 41005, 2009, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 1, 41005, 2010, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 2, 41005, 2011, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 3, 41005, 2012, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 4, 41005, 2013, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 5, 41005, 2014, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 6, 41005, 2015, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 7, 41005, 2016, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 8, 41005, 2017, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 4, 9, 41005, 2018, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 5, 1, 41005, 2019, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 5, 2, 41005, 2020, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 5, 3, 41004, 2021, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 1, 41005, 4010, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 2, 41005, 4020, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 3, 41005, 4021, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 1, 1, 1, 44006, 8631, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 1, 1, 41004, 1001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 1, 2, 41004, 1007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 1, 41004, 2002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 2, 41004, 2003, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 3, 41004, 2005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 4, 41004, 2006, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 5, 41004, 2013, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 6, 41004, 2021, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 1, 41004, 3001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 2, 41004, 3002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 3, 41004, 3003, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 4, 41004, 3004, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 5, 41004, 3005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 6, 41004, 3006, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 7, 41004, 3007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 8, 41004, 3008, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 9, 41004, 3009, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 4, 1, 41004, 3010, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 4, 2, 41004, 3011, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 5, 1, 41004, 4020, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 5, 2, 41004, 4021, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 5, 3, 41004, 4022, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 5, 4, 41004, 4030, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 1, 41004, 5001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 2, 41004, 5002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 3, 41004, 5003, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 4, 41004, 5004, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 5, 41004, 5005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 6, 41004, 5006, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 7, 41004, 5007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 8, 41004, 5011, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 1, 41005, 1001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 4, 41005, 4022, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 5, 41005, 4030, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 1, 41005, 5001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 2, 41005, 5002, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 3, 41005, 5005, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 4, 41005, 5007, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 5, 41005, 5008, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 6, 41005, 5009, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 7, 41005, 5010, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 1, 1, 42006, 5211, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 1, 2, 42006, 5212, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 1, 1, 0, 44001, 8210, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 1, 2, 0, 44001, 8220, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 1, 3, 0, 44001, 8230, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 3, 1, 0, 44001, 8410, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 1, 1, 0, 44006, 8630, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 1, 2, 0, 44006, 8640, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 1, 0, 41004, 1000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 2, 0, 41004, 2000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 3, 0, 41004, 3000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 1, 43007, 1000, 1010);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 2, 43007, 1000, 1020);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 3, 43007, 1000, 1030);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 4, 43007, 1000, 1040);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 5, 43007, 1000, 1110);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 6, 43007, 1000, 1120);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 5, 0, 41004, 4000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 6, 0, 41004, 5000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 1, 0, 41005, 1000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 3, 0, 41005, 2000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 6, 0, 41005, 4000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 7, 0, 41005, 5000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (19, 0, 1, 0, 41009, 9403, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (19, 0, 2, 0, 41009, 9404, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (21, 0, 1, 0, 42001, 5130, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 1, 0, 42006, 5210, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 2, 0, 42006, 5220, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 3, 0, 42006, 5230, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 4, 0, 42006, 5240, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 5, 0, 42006, 5250, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 6, 0, 42006, 5260, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 1, 0, 42009, 5130, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 2, 0, 42009, 5310, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 3, 0, 42009, 5320, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (11, 0, 0, 0, 41001, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 1, 1, 42010, 1102, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 1, 2, 42010, 1104, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 1, 3, 42010, 1400, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 1, 4, 42010, 1500, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (12, 0, 0, 0, 41002, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 2, 1, 42010, 1201, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 2, 2, 42010, 1202, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 0, 41003, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 3, 1, 42010, 1301, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 3, 2, 42010, 1302, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (14, 0, 0, 0, 41004, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (15, 0, 0, 0, 41005, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 0, 0, 0, 41006, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 1, 1, 42015, 5511, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 1, 2, 42015, 5512, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 2, 1, 43007, 1110, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 2, 2, 43007, 1120, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 1, 41003, 1000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 2, 41003, 2000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 3, 41003, 4000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 4, 41003, 5000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 5, 41003, 6000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 6, 41003, 7000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (13, 0, 0, 7, 41003, 8000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 0, 0, 1, 41008, 4001, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (19, 0, 0, 1, 41009, 9401, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (19, 0, 0, 2, 41009, 9402, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (21, 0, 0, 1, 42001, 2311, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (21, 0, 0, 2, 42001, 2312, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (21, 0, 0, 3, 42001, 2313, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 0, 1, 42010, 2321, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 0, 2, 42010, 2322, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 0, 1, 42015, 5501, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 0, 1, 42016, 2341, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 3, 1, 0, 43001, 1031, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 5, 1, 0, 43001, 1051, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 5, 2, 0, 43001, 1052, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 3, 1, 0, 43002, 1310, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 3, 2, 0, 43002, 1320, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 1, 0, 0, 41006, 2100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 2, 0, 0, 41006, 2700, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 3, 0, 0, 41006, 6800, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (16, 4, 0, 0, 41006, 7600, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 1, 0, 0, 41007, 1100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 2, 0, 0, 41007, 1200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 3, 0, 0, 41007, 1300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 1, 0, 0, 41008, 4100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 2, 0, 0, 41008, 4200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 3, 0, 0, 41008, 4300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 4, 0, 0, 41008, 4400, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 1, 0, 0, 43001, 1010, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 2, 0, 0, 43001, 1020, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 3, 0, 0, 43001, 1030, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 4, 0, 0, 43001, 1040, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 5, 0, 0, 43001, 1050, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 6, 0, 0, 43001, 1200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 1, 0, 0, 43002, 1100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 2, 0, 0, 43002, 1200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 3, 0, 0, 43002, 1300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 4, 0, 42009, 5330, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 5, 0, 42009, 5340, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 6, 0, 42009, 5350, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 1, 0, 0, 44001, 8200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 2, 0, 0, 44001, 8300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 3, 0, 0, 44001, 8400, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 4, 0, 0, 44001, 8500, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 1, 0, 0, 44006, 8610, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 2, 0, 0, 44006, 8620, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 1, 0, 42010, 1100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 2, 0, 42010, 1200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 3, 0, 42010, 1300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 4, 0, 42010, 1600, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 1, 0, 42015, 5510, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 2, 0, 42015, 5520, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 3, 0, 42015, 5530, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 4, 0, 42015, 5540, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 5, 0, 42015, 5550, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 1, 0, 42016, 5610, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 2, 0, 42016, 5620, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 3, 0, 42016, 5630, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 4, 0, 42016, 5640, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (33, 0, 1, 0, 43003, 1400, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 1, 0, 43007, 1000, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 2, 0, 43007, 1100, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 3, 0, 43007, 1200, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 4, 0, 43007, 1300, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (42, 0, 1, 0, 44005, 8810, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (44, 0, 1, 0, 44007, 8710, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (17, 0, 0, 0, 41007, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (18, 0, 0, 0, 41008, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (19, 0, 0, 0, 41009, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (21, 0, 0, 0, 42001, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (22, 0, 0, 0, 42006, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (23, 0, 0, 0, 42009, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (24, 0, 0, 0, 42010, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (25, 0, 0, 0, 42015, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (26, 0, 0, 0, 42016, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (31, 0, 0, 0, 43001, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (32, 0, 0, 0, 43002, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (33, 0, 0, 0, 43003, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (34, 0, 0, 0, 43004, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (35, 0, 0, 0, 43005, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (36, 0, 0, 0, 43006, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (37, 0, 0, 0, 43007, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (41, 0, 0, 0, 44001, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (42, 0, 0, 0, 44005, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (43, 0, 0, 0, 44006, 0, 0);
INSERT INTO n_nutzungsartenschluessel VALUES (44, 0, 0, 0, 44007, 0, 0);


CREATE INDEX n_nutzungsartenschluessel_nutzungsartengruppe2_ix ON n_nutzungsartenschluessel USING btree (nutzungsartengruppe, werteart1, werteart2);
CREATE INDEX n_nutzungsartenschluessel_nutzungsartengruppe_ix ON n_nutzungsartenschluessel USING btree (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2);
CREATE INDEX n_nutzungsartenschluessel_objektart_ix ON n_nutzungsartenschluessel USING btree (objektart, werteart1, werteart2);



CREATE TABLE n_nutzungsartengruppe
(
  schluessel integer NOT NULL,
  gruppe character varying(100) NOT NULL,
  bereich character varying(100) NOT NULL
)
WITH (
  OIDS=TRUE
);
ALTER TABLE n_nutzungsartengruppe
  ADD CONSTRAINT n_nutzungsartengruppe_pk PRIMARY KEY(schluessel);

COMMENT ON COLUMN n_nutzungsartengruppe.schluessel IS 'Tausenderstelle = Nutzungsartengruppe';
COMMENT ON COLUMN n_nutzungsartengruppe.gruppe IS 'Nutzungsartengruppe nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_nutzungsartengruppe.bereich IS 'Nutzungsartenbereich nach ALKIS-Nutzungsartenkatalog MV';

INSERT INTO n_nutzungsartengruppe VALUES (11, 'Wohnbaufläche', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (12, 'Industrie- und Gewerbefläche', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (13, 'Halde', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (14, 'Bergbaubetrieb', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (15, 'Tagebau, Grube, Steinbruch', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (16, 'Fläche gemischter Nutzung', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (17, 'Fläche besonderer funktionaler Prägung', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (18, 'Sport-, Freizeit- und Erholungsfläche', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (19, 'Friedhof', 'Siedlung');
INSERT INTO n_nutzungsartengruppe VALUES (21, 'Straßenverkehr', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (22, 'Weg', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (23, 'Platz', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (24, 'Bahnverkehr', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (25, 'Flugverkehr', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (26, 'Schiffsverkehr', 'Verkehr');
INSERT INTO n_nutzungsartengruppe VALUES (31, 'Landwirtschaft', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (32, 'Wald', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (33, 'Gehölz', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (34, 'Heide', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (35, 'Moor', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (36, 'Sumpf', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (37, 'Unland / Vegetationslose Fläche', 'Vegetation');
INSERT INTO n_nutzungsartengruppe VALUES (41, 'Fließgewässer', 'Gewässer');
INSERT INTO n_nutzungsartengruppe VALUES (42, 'Hafenbecken', 'Gewässer');
INSERT INTO n_nutzungsartengruppe VALUES (43, 'Stehendes Gewässer', 'Gewässer');
INSERT INTO n_nutzungsartengruppe VALUES (44, 'Meer', 'Gewässer');


CREATE TABLE n_nutzungsart
(
  
  nutzungsartengruppe integer NOT NULL,
  schluessel integer NOT NULL,
  nutzungsart character varying(100) NOT NULL
)
WITH (
  OIDS=TRUE
);
ALTER TABLE n_nutzungsart
  ADD CONSTRAINT n_nutzungsart_pk PRIMARY KEY(nutzungsartengruppe,schluessel);

COMMENT ON COLUMN n_nutzungsart.nutzungsartengruppe IS 'Tausenderstelle = Nutzungsartengruppe nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_nutzungsart.schluessel IS 'Hunderterstelle = Nutzungsart';
COMMENT ON COLUMN n_nutzungsart.nutzungsart IS 'Nutzungsart nach ALKIS-Nutzungsartenkatalog MV';

INSERT INTO n_nutzungsart VALUES (12, 1, 'Industrie und Gewerbe');
INSERT INTO n_nutzungsart VALUES (12, 2, 'Handel und Dienstleistung');
INSERT INTO n_nutzungsart VALUES (12, 3, 'Versorgungsanlage');
INSERT INTO n_nutzungsart VALUES (12, 4, 'Entsorgung');
INSERT INTO n_nutzungsart VALUES (16, 1, 'Gebäude- und Freifläche, Mischnutzung mit Wohnen');
INSERT INTO n_nutzungsart VALUES (16, 2, 'Gebäude- und Freifläche Land- und Forstwirtschaft');
INSERT INTO n_nutzungsart VALUES (16, 3, 'Landwirtschaftliche Betriebsfläche');
INSERT INTO n_nutzungsart VALUES (16, 4, 'Forstwirtschaftliche Betriebsfläche');
INSERT INTO n_nutzungsart VALUES (17, 1, 'Öffentliche Zwecke');
INSERT INTO n_nutzungsart VALUES (17, 2, 'Parken');
INSERT INTO n_nutzungsart VALUES (17, 3, 'Historische Anlage');
INSERT INTO n_nutzungsart VALUES (18, 1, 'Sportanlage');
INSERT INTO n_nutzungsart VALUES (18, 2, 'Freizeitanlage');
INSERT INTO n_nutzungsart VALUES (18, 3, 'Erholungsfläche');
INSERT INTO n_nutzungsart VALUES (18, 4, 'Grünanlage');
INSERT INTO n_nutzungsart VALUES (31, 1, 'Ackerland');
INSERT INTO n_nutzungsart VALUES (31, 2, 'Grünland');
INSERT INTO n_nutzungsart VALUES (31, 3, 'Gartenland');
INSERT INTO n_nutzungsart VALUES (31, 4, 'Weingarten');
INSERT INTO n_nutzungsart VALUES (31, 5, 'Obstplantage');
INSERT INTO n_nutzungsart VALUES (31, 6, 'Brachland');
INSERT INTO n_nutzungsart VALUES (32, 1, 'Laubholz');
INSERT INTO n_nutzungsart VALUES (32, 2, 'Nadelholz');
INSERT INTO n_nutzungsart VALUES (32, 3, 'Laub- und Nadelholz');
INSERT INTO n_nutzungsart VALUES (41, 1, 'Fluss');
INSERT INTO n_nutzungsart VALUES (41, 2, 'Kanal');
INSERT INTO n_nutzungsart VALUES (41, 3, 'Graben');
INSERT INTO n_nutzungsart VALUES (41, 4, 'Bach');
INSERT INTO n_nutzungsart VALUES (43, 1, 'See');
INSERT INTO n_nutzungsart VALUES (43, 2, 'Teich');


CREATE TABLE n_untergliederung1
(
  
  nutzungsartengruppe integer NOT NULL,
  nutzungsart integer NOT NULL,
  schluessel integer NOT NULL,
  untergliederung1 character varying(100) NOT NULL
)
WITH (
  OIDS=TRUE
);
ALTER TABLE n_untergliederung1
  ADD CONSTRAINT n_untergliederung1_pk PRIMARY KEY(nutzungsartengruppe,nutzungsart,schluessel);

COMMENT ON COLUMN n_untergliederung1.nutzungsartengruppe IS 'Tausenderstelle = Nutzungsartengruppe nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_untergliederung1.nutzungsart IS 'Hunderterstelle = Nutzungsart nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_untergliederung1.schluessel IS 'Zehnerstelle = Untergliederung 1. Stufe';
COMMENT ON COLUMN n_untergliederung1.untergliederung1 IS 'Untergliederung 1. Stufe nach ALKIS-Nutzungsartenkatalog MV';

INSERT INTO n_untergliederung1 VALUES (12, 1, 1, 'Produktion');
INSERT INTO n_untergliederung1 VALUES (12, 1, 2, 'Handwerk');
INSERT INTO n_untergliederung1 VALUES (12, 1, 3, 'Tankstelle');
INSERT INTO n_untergliederung1 VALUES (12, 1, 4, 'Lagerplatz');
INSERT INTO n_untergliederung1 VALUES (12, 1, 5, 'Transport');
INSERT INTO n_untergliederung1 VALUES (12, 1, 6, 'Forschung');
INSERT INTO n_untergliederung1 VALUES (12, 1, 7, 'Grundstoff');
INSERT INTO n_untergliederung1 VALUES (12, 1, 8, 'Betriebliche Sozialeinrichtung');
INSERT INTO n_untergliederung1 VALUES (12, 1, 9, 'Werft');
INSERT INTO n_untergliederung1 VALUES (12, 2, 1, 'Verwaltung, freie Berufe');
INSERT INTO n_untergliederung1 VALUES (12, 2, 2, 'Bank, Kredit');
INSERT INTO n_untergliederung1 VALUES (12, 2, 3, 'Versicherung');
INSERT INTO n_untergliederung1 VALUES (12, 2, 4, 'Handel');
INSERT INTO n_untergliederung1 VALUES (12, 2, 5, 'Ausstellung, Messe');
INSERT INTO n_untergliederung1 VALUES (12, 2, 6, 'Beherbergung');
INSERT INTO n_untergliederung1 VALUES (12, 2, 7, 'Restauration');
INSERT INTO n_untergliederung1 VALUES (12, 2, 8, 'Vergnügung');
INSERT INTO n_untergliederung1 VALUES (12, 2, 9, 'Gärtnerei');
INSERT INTO n_untergliederung1 VALUES (12, 3, 1, 'Förderanlage');
INSERT INTO n_untergliederung1 VALUES (12, 3, 2, 'Wasserwerk');
INSERT INTO n_untergliederung1 VALUES (12, 3, 3, 'Kraftwerk');
INSERT INTO n_untergliederung1 VALUES (12, 3, 4, 'Umspannstation');
INSERT INTO n_untergliederung1 VALUES (12, 3, 5, 'Raffinerie');
INSERT INTO n_untergliederung1 VALUES (12, 3, 6, 'Gaswerk');
INSERT INTO n_untergliederung1 VALUES (12, 3, 7, 'Heizwerk');
INSERT INTO n_untergliederung1 VALUES (12, 3, 8, 'Funk- und Fernmeldeanlage');
INSERT INTO n_untergliederung1 VALUES (12, 4, 1, 'Kläranlage, Klärwerk');
INSERT INTO n_untergliederung1 VALUES (12, 4, 2, 'Abfallbehandlungsanlage');
INSERT INTO n_untergliederung1 VALUES (12, 4, 3, 'Deponie (oberirdisch)');
INSERT INTO n_untergliederung1 VALUES (12, 4, 4, 'Deponie (untertägig)');
INSERT INTO n_untergliederung1 VALUES (14, 0, 1, 'Erden, Lockergestein');
INSERT INTO n_untergliederung1 VALUES (14, 0, 2, 'Steine, Gestein, Festgestein');
INSERT INTO n_untergliederung1 VALUES (14, 0, 3, 'Erze');
INSERT INTO n_untergliederung1 VALUES (14, 0, 5, 'Treib- und Brennstoffe');
INSERT INTO n_untergliederung1 VALUES (14, 0, 6, 'Industrieminerale, Salze');
INSERT INTO n_untergliederung1 VALUES (15, 0, 1, 'Erden, Lockergestein');
INSERT INTO n_untergliederung1 VALUES (15, 0, 3, 'Steine, Gestein, Festgestein');
INSERT INTO n_untergliederung1 VALUES (15, 0, 6, 'Treib- und Brennstoffe');
INSERT INTO n_untergliederung1 VALUES (15, 0, 7, 'Industrieminerale, Salze');
INSERT INTO n_untergliederung1 VALUES (16, 1, 1, 'Wohnen mit Öffentlich');
INSERT INTO n_untergliederung1 VALUES (16, 1, 2, 'Wohnen mit Handel und Dienstleistungen');
INSERT INTO n_untergliederung1 VALUES (16, 1, 3, 'Wohnen mit Gewerbe und Industrie');
INSERT INTO n_untergliederung1 VALUES (16, 1, 4, 'Öffentlich mit Wohnen');
INSERT INTO n_untergliederung1 VALUES (16, 1, 5, 'Handel und Dienstleistungen mit Wohnen');
INSERT INTO n_untergliederung1 VALUES (16, 1, 6, 'Gewerbe und Industrie mit Wohnen');
INSERT INTO n_untergliederung1 VALUES (16, 2, 1, 'Wohnen und Betrieb');
INSERT INTO n_untergliederung1 VALUES (17, 1, 1, 'Verwaltung');
INSERT INTO n_untergliederung1 VALUES (17, 1, 2, 'Bildung und Forschung');
INSERT INTO n_untergliederung1 VALUES (17, 1, 3, 'Kultur');
INSERT INTO n_untergliederung1 VALUES (17, 1, 4, 'Religiöse Einrichtung');
INSERT INTO n_untergliederung1 VALUES (17, 1, 5, 'Gesundheit, Kur');
INSERT INTO n_untergliederung1 VALUES (17, 1, 6, 'Soziales');
INSERT INTO n_untergliederung1 VALUES (17, 1, 7, 'Sicherheit und Ordnung');
INSERT INTO n_untergliederung1 VALUES (17, 3, 1, 'Burg-, Festungsanlage');
INSERT INTO n_untergliederung1 VALUES (17, 3, 2, 'Schlossanlage');
INSERT INTO n_untergliederung1 VALUES (18, 1, 1, 'Golfplatz');
INSERT INTO n_untergliederung1 VALUES (18, 1, 2, 'Sportplatz');
INSERT INTO n_untergliederung1 VALUES (18, 1, 3, 'Rennbahn');
INSERT INTO n_untergliederung1 VALUES (18, 1, 4, 'Reitplatz');
INSERT INTO n_untergliederung1 VALUES (18, 1, 5, 'Schießanlage');
INSERT INTO n_untergliederung1 VALUES (18, 1, 6, 'Eis-, Rollschuhbahn');
INSERT INTO n_untergliederung1 VALUES (18, 1, 7, 'Tennisplatz');
INSERT INTO n_untergliederung1 VALUES (18, 2, 1, 'Zoo');
INSERT INTO n_untergliederung1 VALUES (18, 2, 2, 'Safaripark, Wildpark');
INSERT INTO n_untergliederung1 VALUES (18, 2, 3, 'Freizeitpark');
INSERT INTO n_untergliederung1 VALUES (18, 2, 4, 'Freilichttheater');
INSERT INTO n_untergliederung1 VALUES (18, 2, 5, 'Freilichtmuseum');
INSERT INTO n_untergliederung1 VALUES (18, 2, 6, 'Autokino, Freilichtkino');
INSERT INTO n_untergliederung1 VALUES (18, 2, 7, 'Verkehrsübungsplatz');
INSERT INTO n_untergliederung1 VALUES (18, 2, 8, 'Hundeübungsplatz');
INSERT INTO n_untergliederung1 VALUES (18, 2, 9, 'Modellflugplatz');
INSERT INTO n_untergliederung1 VALUES (18, 3, 1, 'Wochenend- und Ferienhausfläche');
INSERT INTO n_untergliederung1 VALUES (18, 3, 2, 'Schwimmbad, Freibad');
INSERT INTO n_untergliederung1 VALUES (18, 3, 3, 'Campingplatz');
INSERT INTO n_untergliederung1 VALUES (18, 4, 1, 'Grünfläche');
INSERT INTO n_untergliederung1 VALUES (18, 4, 2, 'Park');
INSERT INTO n_untergliederung1 VALUES (18, 4, 3, 'Botanischer Garten');
INSERT INTO n_untergliederung1 VALUES (18, 4, 4, 'Kleingarten');
INSERT INTO n_untergliederung1 VALUES (18, 4, 5, 'Wochenendplatz');
INSERT INTO n_untergliederung1 VALUES (18, 4, 6, 'Garten');
INSERT INTO n_untergliederung1 VALUES (18, 4, 7, 'Spielplatz, Bolzplatz');
INSERT INTO n_untergliederung1 VALUES (19, 0, 1, 'Friedhof (Park)');
INSERT INTO n_untergliederung1 VALUES (19, 0, 2, 'Historischer Friedhof');
INSERT INTO n_untergliederung1 VALUES (21, 0, 1, 'Fußgängerzone');
INSERT INTO n_untergliederung1 VALUES (22, 0, 1, 'Fahrweg');
INSERT INTO n_untergliederung1 VALUES (22, 0, 2, 'Fußweg');
INSERT INTO n_untergliederung1 VALUES (22, 0, 3, 'Gang');
INSERT INTO n_untergliederung1 VALUES (22, 0, 4, 'Radweg');
INSERT INTO n_untergliederung1 VALUES (22, 0, 5, 'Rad- und Fußweg');
INSERT INTO n_untergliederung1 VALUES (22, 0, 6, 'Reitweg');
INSERT INTO n_untergliederung1 VALUES (23, 0, 1, 'Fußgängerzone');
INSERT INTO n_untergliederung1 VALUES (23, 0, 2, 'Parkplatz');
INSERT INTO n_untergliederung1 VALUES (23, 0, 3, 'Rastplatz');
INSERT INTO n_untergliederung1 VALUES (23, 0, 4, 'Raststätte');
INSERT INTO n_untergliederung1 VALUES (23, 0, 5, 'Marktplatz');
INSERT INTO n_untergliederung1 VALUES (23, 0, 6, 'Festplatz');
INSERT INTO n_untergliederung1 VALUES (24, 0, 1, 'Eisenbahn');
INSERT INTO n_untergliederung1 VALUES (24, 0, 2, 'Stadtbahn');
INSERT INTO n_untergliederung1 VALUES (24, 0, 3, 'Seilbahn, Bergbahn');
INSERT INTO n_untergliederung1 VALUES (24, 0, 4, 'Magnetschwebebahn');
INSERT INTO n_untergliederung1 VALUES (25, 0, 1, 'Flughafen');
INSERT INTO n_untergliederung1 VALUES (25, 0, 2, 'Verkehrslandeplatz');
INSERT INTO n_untergliederung1 VALUES (25, 0, 3, 'Hubschrauberflugplatz');
INSERT INTO n_untergliederung1 VALUES (25, 0, 4, 'Landeplatz, Sonderlandeplatz');
INSERT INTO n_untergliederung1 VALUES (25, 0, 5, 'Segelfluggelände');
INSERT INTO n_untergliederung1 VALUES (26, 0, 1, 'Hafenanlage (Landfläche)');
INSERT INTO n_untergliederung1 VALUES (26, 0, 2, 'Schleuse (Landfläche)');
INSERT INTO n_untergliederung1 VALUES (26, 0, 3, 'Anlegestelle');
INSERT INTO n_untergliederung1 VALUES (26, 0, 4, 'Fähranlage');
INSERT INTO n_untergliederung1 VALUES (31, 1, 1, 'Streuobstacker');
INSERT INTO n_untergliederung1 VALUES (31, 1, 2, 'Hopfen');
INSERT INTO n_untergliederung1 VALUES (31, 1, 3, 'Spargel');
INSERT INTO n_untergliederung1 VALUES (31, 2, 1, 'Streuobstwiese');
INSERT INTO n_untergliederung1 VALUES (31, 3, 1, 'Baumschule');
INSERT INTO n_untergliederung1 VALUES (31, 5, 1, 'Obstbaumplantage');
INSERT INTO n_untergliederung1 VALUES (31, 5, 2, 'Obststrauchplantage');
INSERT INTO n_untergliederung1 VALUES (32, 3, 1, 'Laubwald mit Nadelholz');
INSERT INTO n_untergliederung1 VALUES (32, 3, 2, 'Nadelwald mit Laubholz');
INSERT INTO n_untergliederung1 VALUES (33, 0, 1, 'Latschenkiefer');
INSERT INTO n_untergliederung1 VALUES (37, 0, 1, 'Vegetationslose Fläche');
INSERT INTO n_untergliederung1 VALUES (37, 0, 2, 'Gewässerbegleitfläche');
INSERT INTO n_untergliederung1 VALUES (37, 0, 3, 'Sukzessionsfläche');
INSERT INTO n_untergliederung1 VALUES (37, 0, 4, 'Naturnahe Fläche');
INSERT INTO n_untergliederung1 VALUES (41, 1, 1, 'Altwasser');
INSERT INTO n_untergliederung1 VALUES (41, 1, 2, 'Altarm');
INSERT INTO n_untergliederung1 VALUES (41, 1, 3, 'Flussmündungstrichter');
INSERT INTO n_untergliederung1 VALUES (41, 3, 1, 'Fleet');
INSERT INTO n_untergliederung1 VALUES (42, 0, 1, 'Sportboothafenbecken');
INSERT INTO n_untergliederung1 VALUES (43, 1, 1, 'Stausee');
INSERT INTO n_untergliederung1 VALUES (43, 1, 2, 'Baggersee');
INSERT INTO n_untergliederung1 VALUES (44, 0, 1, 'Küstengewässer');



CREATE TABLE n_untergliederung2
(
  nutzungsartengruppe integer NOT NULL,
  nutzungsart integer NOT NULL,
  untergliederung1 integer NOT NULL,
  schluessel integer NOT NULL,
  untergliederung2 character varying(100) NOT NULL
)
WITH (
  OIDS=TRUE
);
ALTER TABLE n_untergliederung2
  ADD CONSTRAINT n_untergliederung2_pk PRIMARY KEY(nutzungsartengruppe,nutzungsart,untergliederung1,schluessel);

COMMENT ON COLUMN n_untergliederung2.nutzungsartengruppe IS 'Tausenderstelle = Nutzungsartengruppe nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_untergliederung2.nutzungsart IS 'Hunderterstelle = Nutzungsart nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_untergliederung2.untergliederung1 IS 'Zehnerstelle = Untergliederung 1. Stufe nach ALKIS-Nutzungsartenkatalog MV';
COMMENT ON COLUMN n_untergliederung2.schluessel IS 'Einerstelle = Untergliederung 2. Stufe';
COMMENT ON COLUMN n_untergliederung2.untergliederung2 IS 'Untergliederung 2. Stufe nach ALKIS-Nutzungsartenkatalog MV';

INSERT INTO n_untergliederung2 VALUES (12, 1, 0, 1, 'Gebäude- und Freifläche Industrie und Gewerbe');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 1, 'Abraum');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 2, 'Baustoffe');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 3, 'Erde');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 4, 'Kohle');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 5, 'Öl');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 6, 'Schlacke');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 7, 'Schrott, Altmaterial');
INSERT INTO n_untergliederung2 VALUES (12, 1, 4, 8, 'Schutt');
INSERT INTO n_untergliederung2 VALUES (12, 3, 0, 1, 'Gebäude- und Freifläche Versorgungsanlage');
INSERT INTO n_untergliederung2 VALUES (12, 3, 0, 2, 'Betriebsfläche Versorgungsanlage');
INSERT INTO n_untergliederung2 VALUES (12, 3, 1, 1, 'Erdöl');
INSERT INTO n_untergliederung2 VALUES (12, 3, 1, 2, 'Erdgas');
INSERT INTO n_untergliederung2 VALUES (12, 3, 1, 3, 'Sole, Lauge');
INSERT INTO n_untergliederung2 VALUES (12, 3, 1, 4, 'Kohlensäure');
INSERT INTO n_untergliederung2 VALUES (12, 3, 1, 5, 'Erdwärme');
INSERT INTO n_untergliederung2 VALUES (12, 3, 2, 1, 'Gebäude- und Freifläche Versorgungsanlage, Wasser');
INSERT INTO n_untergliederung2 VALUES (12, 3, 2, 2, 'Betriebsfläche Versorgungsanlage, Wasser');
INSERT INTO n_untergliederung2 VALUES (12, 3, 3, 1, 'Gebäude- und Freifläche Versorgungsanlage, Elektrizität');
INSERT INTO n_untergliederung2 VALUES (12, 3, 3, 2, 'Betriebsfläche Versorgungsanlage, Elektrizität');
INSERT INTO n_untergliederung2 VALUES (12, 3, 5, 1, 'Gebäude- und Freifläche Versorgungsanlage, Öl');
INSERT INTO n_untergliederung2 VALUES (12, 3, 5, 2, 'Betriebsfläche Versorgungsanlage, Öl');
INSERT INTO n_untergliederung2 VALUES (12, 3, 6, 1, 'Gebäude- und Freifläche Versorgungsanlage, Gas');
INSERT INTO n_untergliederung2 VALUES (12, 3, 6, 2, 'Betriebsfläche Versorgungsanlage, Gas');
INSERT INTO n_untergliederung2 VALUES (12, 3, 7, 1, 'Gebäude- und Freifläche Versorgungsanlage, Wärme');
INSERT INTO n_untergliederung2 VALUES (12, 3, 7, 2, 'Betriebsfläche Versorgungsanlage, Wärme');
INSERT INTO n_untergliederung2 VALUES (12, 3, 8, 1, 'Gebäude- und Freifläche Versorgungsanlage, Funk- und Fernmeldewesen');
INSERT INTO n_untergliederung2 VALUES (12, 3, 8, 2, 'Betriebsfläche Versorgungsanlage, Funk- und Fernmeldewesen');
INSERT INTO n_untergliederung2 VALUES (12, 4, 0, 1, 'Gebäude- und Freifläche Entsorgungsanlage');
INSERT INTO n_untergliederung2 VALUES (12, 4, 0, 2, 'Betriebsfläche Entsorgungsanlage');
INSERT INTO n_untergliederung2 VALUES (12, 4, 1, 1, 'Gebäude- und Freifläche Entsorgungsanlage, Abwasserbeseitigung');
INSERT INTO n_untergliederung2 VALUES (12, 4, 1, 2, 'Betriebsfläche Entsorgungsanlage, Abwasserbeseitigung');
INSERT INTO n_untergliederung2 VALUES (12, 4, 2, 1, 'Gebäude- und Freifläche Entsorgungsanlage, Abfallbeseitigung');
INSERT INTO n_untergliederung2 VALUES (12, 4, 2, 2, 'Betriebsfläche Entsorgungsanlage, Abfallbeseitigung');
INSERT INTO n_untergliederung2 VALUES (12, 4, 2, 3, 'Betriebsfläche Entsorgungsanlage, Schlamm');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 1, 'Baustoffe');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 2, 'Kohle');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 3, 'Erde');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 4, 'Schutt');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 5, 'Schlacke');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 6, 'Abraum');
INSERT INTO n_untergliederung2 VALUES (13, 0, 0, 7, 'Schrott, Altmaterial');
INSERT INTO n_untergliederung2 VALUES (14, 0, 1, 1, 'Ton');
INSERT INTO n_untergliederung2 VALUES (14, 0, 1, 2, 'Kalk, Kalktuff, Kreide');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 1, 'Schiefer, Dachschiefer');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 2, 'Metamorpher Schiefer');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 3, 'Kalkstein');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 4, 'Dolomitstein');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 5, 'Basalt, Diabas');
INSERT INTO n_untergliederung2 VALUES (14, 0, 2, 6, 'Talkschiefer, Speckstein');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 1, 'Eisen');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 2, 'Buntmetallerze');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 3, 'Kupfer');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 4, 'Blei');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 5, 'Zink');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 6, 'Zinn');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 7, 'Wismut, Kobalt, Nickel');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 8, 'Uran');
INSERT INTO n_untergliederung2 VALUES (14, 0, 3, 9, 'Mangan');
INSERT INTO n_untergliederung2 VALUES (14, 0, 4, 1, 'Antimon');
INSERT INTO n_untergliederung2 VALUES (14, 0, 4, 2, 'Edelmetallerze');
INSERT INTO n_untergliederung2 VALUES (14, 0, 5, 1, 'Kohle');
INSERT INTO n_untergliederung2 VALUES (14, 0, 5, 2, 'Braunkohle');
INSERT INTO n_untergliederung2 VALUES (14, 0, 5, 3, 'Steinkohle');
INSERT INTO n_untergliederung2 VALUES (14, 0, 5, 4, 'Ölschiefer');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 1, 'Gipsstein');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 2, 'Anhydritstein');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 3, 'Steinsalz');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 4, 'Kalisalz');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 5, 'Kalkspat');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 6, 'Flussspat');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 7, 'Schwerspat');
INSERT INTO n_untergliederung2 VALUES (14, 0, 6, 8, 'Graphit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 1, 'Ton');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 2, 'Bentonit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 3, 'Kaolin');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 4, 'Lehm');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 5, 'Löß, Lößlehm');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 6, 'Mergel');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 7, 'Kalk, Kalktuff, Kreide');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 8, 'Sand');
INSERT INTO n_untergliederung2 VALUES (15, 0, 1, 9, 'Kies, Kiessand');
INSERT INTO n_untergliederung2 VALUES (15, 0, 2, 1, 'Farberden');
INSERT INTO n_untergliederung2 VALUES (15, 0, 2, 2, 'Quarzsand');
INSERT INTO n_untergliederung2 VALUES (15, 0, 2, 3, 'Kieselerde');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 1, 'Tonstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 2, 'Schiefer, Dachschiefer');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 3, 'Metamorpher Schiefer');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 4, 'Mergelstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 5, 'Kalkstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 6, 'Dolomitstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 7, 'Travertin');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 8, 'Marmor');
INSERT INTO n_untergliederung2 VALUES (15, 0, 3, 9, 'Sandstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 1, 'Grauwacke');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 2, 'Quarzit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 3, 'Gneis');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 4, 'Basalt, Diabas ');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 5, 'Andesit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 6, 'Porphyr, Quarzporphyr');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 7, 'Granit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 8, 'Granodiorit');
INSERT INTO n_untergliederung2 VALUES (15, 0, 4, 9, 'Tuff-, Bimsstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 5, 1, 'Trass');
INSERT INTO n_untergliederung2 VALUES (15, 0, 5, 2, 'Lavaschlacke');
INSERT INTO n_untergliederung2 VALUES (15, 0, 5, 3, 'Talkschiefer, Speckstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 6, 1, 'Torf');
INSERT INTO n_untergliederung2 VALUES (15, 0, 6, 2, 'Kohle');
INSERT INTO n_untergliederung2 VALUES (15, 0, 6, 3, 'Braunkohle');
INSERT INTO n_untergliederung2 VALUES (15, 0, 6, 4, 'Steinkohle');
INSERT INTO n_untergliederung2 VALUES (15, 0, 6, 5, 'Ölschiefer');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 1, 'Gipsstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 2, 'Anhydritstein');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 3, 'Kalkspat');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 4, 'Schwerspat');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 5, 'Quarz');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 6, 'Feldspat');
INSERT INTO n_untergliederung2 VALUES (15, 0, 7, 7, 'Pegmatitsand');
INSERT INTO n_untergliederung2 VALUES (16, 2, 1, 1, 'Wohnen');
INSERT INTO n_untergliederung2 VALUES (16, 2, 1, 2, 'Betrieb');
INSERT INTO n_untergliederung2 VALUES (18, 0, 0, 1, 'Gebäude- und Freifläche Sport, Freizeit und Erholung');
INSERT INTO n_untergliederung2 VALUES (18, 1, 0, 1, 'Gebäude- und Freifläche Erholung, Sport');
INSERT INTO n_untergliederung2 VALUES (18, 2, 1, 1, 'Gebäude- und Freifläche Erholung, Zoologie');
INSERT INTO n_untergliederung2 VALUES (18, 3, 0, 1, 'Gebäude- und Freifläche Erholung');
INSERT INTO n_untergliederung2 VALUES (18, 3, 2, 1, 'Gebäude- und Freifläche Erholung, Bad');
INSERT INTO n_untergliederung2 VALUES (18, 3, 3, 1, 'Gebäude- und Freifläche Erholung, Camping');
INSERT INTO n_untergliederung2 VALUES (18, 4, 3, 1, 'Gebäude- und Freifläche Erholung, Botanik');
INSERT INTO n_untergliederung2 VALUES (19, 0, 0, 1, 'Gebäude- und Freifläche Friedhof');
INSERT INTO n_untergliederung2 VALUES (19, 0, 0, 2, 'Friedhof (ohne Gebäude)');
INSERT INTO n_untergliederung2 VALUES (21, 0, 0, 1, 'Gebäude- und Freifläche zu Verkehrsanlagen, Straße');
INSERT INTO n_untergliederung2 VALUES (21, 0, 0, 2, 'Verkehrsbegleitfläche Straße');
INSERT INTO n_untergliederung2 VALUES (21, 0, 0, 3, 'Straßenentwässerungsanlage');
INSERT INTO n_untergliederung2 VALUES (22, 0, 1, 1, 'Hauptwirtschaftsweg');
INSERT INTO n_untergliederung2 VALUES (22, 0, 1, 2, 'Wirtschaftsweg');
INSERT INTO n_untergliederung2 VALUES (24, 0, 0, 1, 'Gebäude- und Freifläche zu Verkehrsanlagen, Schiene');
INSERT INTO n_untergliederung2 VALUES (24, 0, 0, 2, 'Verkehrsbegleitfläche Bahnverkehr');
INSERT INTO n_untergliederung2 VALUES (24, 0, 1, 1, 'Güterverkehr');
INSERT INTO n_untergliederung2 VALUES (24, 0, 1, 2, 'S-Bahn');
INSERT INTO n_untergliederung2 VALUES (24, 0, 1, 3, 'Museumsbahn');
INSERT INTO n_untergliederung2 VALUES (24, 0, 1, 4, 'Bahn im Freizeitpark');
INSERT INTO n_untergliederung2 VALUES (24, 0, 2, 1, 'Straßenbahn');
INSERT INTO n_untergliederung2 VALUES (24, 0, 2, 2, 'U-Bahn');
INSERT INTO n_untergliederung2 VALUES (24, 0, 3, 1, 'Zahnradbahn');
INSERT INTO n_untergliederung2 VALUES (24, 0, 3, 2, 'Standseilbahn');
INSERT INTO n_untergliederung2 VALUES (25, 0, 0, 1, 'Gebäude- und Freifläche zu Verkehrsanlagen, Luftfahrt');
INSERT INTO n_untergliederung2 VALUES (25, 0, 1, 1, 'Internationaler Flughafen');
INSERT INTO n_untergliederung2 VALUES (25, 0, 1, 2, 'Regionalflughafen');
INSERT INTO n_untergliederung2 VALUES (26, 0, 0, 1, 'Gebäude- und Freifläche zu Verkehrsanlagen, Schifffahrt');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 1, 'Fels');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 2, 'Steine, Schotter');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 3, 'Geröll');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 4, 'Sand');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 5, 'Schnee');
INSERT INTO n_untergliederung2 VALUES (37, 0, 1, 6, 'Eis, Firn');
INSERT INTO n_untergliederung2 VALUES (37, 0, 2, 1, 'Bebaute Gewässerbegleitfläche');
INSERT INTO n_untergliederung2 VALUES (37, 0, 2, 2, 'Unbebaute Gewässerbegleitfläche');
INSERT INTO n_untergliederung2 VALUES (43, 1, 1, 1, 'Speicherbecken');


-- END --
COMMIT;
