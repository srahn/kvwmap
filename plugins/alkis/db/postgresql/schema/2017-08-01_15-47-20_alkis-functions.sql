BEGIN;
SET search_path = alkis, public;

-- hinzugefügt am 10.03.2016 --
-- Funktion zum Löschen von kleinen Löchern in (Multi-)Polygonen --
-- gefunden hier: http://giswiki.hsr.ch/PostGIS_-_Tipps_und_Tricks#Eliminate_sliver_polygons --
 CREATE OR REPLACE FUNCTION public.Filter_Rings(geometry,float)
 RETURNS geometry AS
 $$
 SELECT ST_Collect( CASE WHEN d.inner_rings is NULL OR NOT st_within(st_collect(d.inner_rings), ST_MakePolygon(c.outer_ring)) THEN ST_MakePolygon(c.outer_ring) ELSE ST_MakePolygon(c.outer_ring, d.inner_rings) END) as final_geom		-- am 20.07.2016 angepasst
  FROM (/* Get outer ring of polygon */
        SELECT ST_ExteriorRing(b.the_geom) as outer_ring
          FROM (SELECT (ST_DumpRings((ST_Dump($1)).geom)).geom As the_geom, path(ST_DumpRings((ST_Dump($1)).geom)) as path) b
          WHERE b.path[1] = 0 /* ie the outer ring */
        ) c,
       (/* Get all inner rings > a particular area */
        SELECT ST_Accum(ST_ExteriorRing(b.the_geom)) as inner_rings
          FROM (SELECT (ST_DumpRings((ST_Dump($1)).geom)).geom As the_geom, path(ST_DumpRings((ST_Dump($1)).geom)) as path) b
          WHERE b.path[1] > 0 /* ie not the outer ring */
            AND ST_Area(b.the_geom) > $2
        ) d
 $$
 LANGUAGE 'sql' IMMUTABLE;


CREATE OR REPLACE FUNCTION alkis.postprocessing()
  RETURNS void AS '


SET client_encoding = ''UTF-8'';
SET enable_seqscan = off;

-- =================================
-- Flurstuecksnummern-Label-Position
-- =================================

  TRUNCATE alkis.pp_flurstueck_nr;  -- effektiver als DELETE

 INSERT INTO alkis.pp_flurstueck_nr
          ( fsgml, beginnt, endet, fsnum, abweichenderrechtszustand, the_geom )															-- angepasst am 25.06.2015
     SELECT DISTINCT f.gml_id,p.beginnt,p.endet,																														-- angepasst am 01.10.2015
           f.zaehler::text || COALESCE (''/'' || f.nenner::text, '''') AS fsnum, f.abweichenderrechtszustand,		-- angepasst am 25.06.2015
           p.wkb_geometry  -- manuelle Position des Textes
      FROM alkis.ap_pto             p
      JOIN alkis.ax_flurstueck      f ON f.gml_id = any(p.dientzurdarstellungvon)
     AND p.art = ''ZAE_NEN'';

-- Tabelle aus View befüllen
TRUNCATE alkis.pp_strassenname;
INSERT INTO alkis.pp_strassenname (gml_id, schriftinhalt, hor, ver, art, winkel, the_geom)
       SELECT gml_id, schriftinhalt, hor, ver, art, winkel, wkb_geometry
       FROM (
			 SELECT p.ogc_fid,
         l.gml_id,                               -- wird im PP zum Nachladen aus Katalog gebraucht
      -- p.advstandardmodell       AS modell,    -- TEST
      -- l.unverschluesselt, l.lage AS schluessel, -- zur Lage  TEST
         p.schriftinhalt,                        -- WMS: LABELITEM
         p.art,                                  -- WMS: CLASSITEM
         p.horizontaleausrichtung  AS hor,       -- Verfeinern der Text-Position ..
         p.vertikaleausrichtung    AS ver,       --  .. durch Klassifizierung hor/ver
         p.drehwinkel * 57.296     AS winkel,    -- * 180 / Pi
         p.wkb_geometry
    FROM alkis.ap_pto p
    JOIN alkis.ax_lagebezeichnungohnehausnummer l
      ON l.gml_id = ANY (p.dientzurdarstellungvon)
   WHERE  p.endet IS NULL
     AND  p.art IN (''Strasse'',''Weg'',''Platz'',''BezKlassifizierungStrasse'') -- CLASSES im LAYER
     AND (   ''DKKM1000'' = ANY (p.advstandardmodell) -- "Lika 1000" bevorzugen
       -- OR ''DLKM''     = ANY (p.advstandardmodell) -- oder auch Kataster allgemein
           -- Ersatzweise auch "keine Angabe", aber nur wenn es keinen besseren Text zur Lage gibt
          OR (p.advstandardmodell IS NULL
               -- Alternativen zur Legebezeichnung suchen in P- und L-Version
               AND (SELECT s.ogc_fid FROM alkis.ap_lto s -- irgend ein Feld eines anderen Textes (suchen)
                      JOIN alkis.ax_lagebezeichnungohnehausnummer ls ON ls.gml_id = ANY(s.dientzurdarstellungvon)
                     WHERE ls.gml_id = l.gml_id AND NOT s.advstandardmodell IS NULL
                     LIMIT 1  -- einer reicht als Beweis
                   ) IS NULL  -- "Subquery IS NULL" liefert true wenn kein weiterer Text gefunden wird
               AND (SELECT s.ogc_fid FROM alkis.ap_pto s
                      JOIN alkis.ax_lagebezeichnungohnehausnummer ls ON ls.gml_id = ANY(s.dientzurdarstellungvon)
                     WHERE ls.gml_id = l.gml_id AND NOT s.advstandardmodell IS NULL LIMIT 1
                   ) IS NULL
              )
         )
			 )as foo; -- View sucht das passende advstandardmodell

-- Schriftinhalt ergänzen
-- Das sind die Standardschreibweisen aus dem Katalog, die nicht mehr redundant in ap_pto sind.
UPDATE alkis.pp_strassenname  p
   SET schriftinhalt =     -- Hier ist der Label noch leer
   -- Subquery "Gib mir den Straßennamen":
   ( SELECT k.bezeichnung                         -- Straßenname ..
       FROM alkis.ax_lagebezeichnungkatalogeintrag k    --  .. aus Katalog
       JOIN alkis.ax_lagebezeichnungohnehausnummer l    -- verwendet als Lage o.H.
         ON (k.land=l.land AND k.regierungsbezirk=l.regierungsbezirk AND k.kreis=l.kreis AND k.gemeinde=l.gemeinde AND k.lage=l.lage )
      WHERE p.gml_id = l.gml_id LIMIT 1                   -- die gml_id wurde aus View importiert
    )
WHERE     p.schriftinhalt IS NULL
   AND NOT p.the_geom      IS NULL;



-- G E M A R K U N G

  TRUNCATE alkis.pp_gemarkung;

-- Vorkommende Paarungen Gemarkung <-> Gemeinde in ax_Flurstueck
INSERT INTO alkis.pp_gemarkung
  (               land, regierungsbezirk, kreis, gemeinde, gemarkung, schluesselgesamt)					-- angepasst am 21.10.2015
  SELECT DISTINCT land, gemeindezugehoerigkeit_regierungsbezirk, gemeindezugehoerigkeit_kreis, gemeindezugehoerigkeit_gemeinde, gemarkungsnummer, land||gemarkungsnummer		-- angepasst am 21.10.2015
  FROM            alkis.ax_flurstueck
  WHERE           endet IS NULL
  ORDER BY        land, gemeindezugehoerigkeit_regierungsbezirk, gemeindezugehoerigkeit_kreis, gemeindezugehoerigkeit_gemeinde, gemarkungsnummer 
;

-- Namen der Gemarkung dazu als Optimierung bei der Auskunft 
UPDATE alkis.pp_gemarkung a
   SET gemarkungsname =
   ( SELECT b.bezeichnung 
     FROM    alkis.ax_gemarkung b
     WHERE a.land=b.land
       AND a.gemarkung=b.gemarkungsnummer
       AND b.endet IS NULL
   );


-- G E M E I N D E

--DELETE FROM pp_gemeinde;
  TRUNCATE alkis.pp_gemeinde;

-- Vorkommende Gemeinden aus den gemarkungen
INSERT INTO alkis.pp_gemeinde
  (               land, regierungsbezirk, kreis, gemeinde)
  SELECT DISTINCT land, regierungsbezirk, kreis, gemeinde
  FROM            alkis.pp_gemarkung
  ORDER BY        land, regierungsbezirk, kreis, gemeinde 
;


-- Namen der Gemeinde dazu als Optimierung bei der Auskunft 
UPDATE alkis.pp_gemeinde a
   SET gemeindename =
   ( SELECT b.bezeichnung 
     FROM    alkis.ax_gemeinde b
     WHERE a.land=b.land 
       AND a.regierungsbezirk=b.regierungsbezirk 
       AND a.kreis=b.kreis
       AND a.gemeinde=b.gemeinde
       AND b.endet IS NULL
   );


INSERT INTO alkis.pp_amt
  (land, regierungsbezirk, kreis, amt, amtsname)
  SELECT DISTINCT g.land, g.regierungsbezirk, g.kreis, a.amt_schluessel,
                  CASE WHEN position(''('' in a.amt_name) > 0 
                    THEN substr(a.amt_name, 1, position(''('' in a.amt_name)-2)
                    ELSE a.amt_name
                  END as amt_name
  FROM            alkis.pp_gemeinde g, alkis.lk_aemtergemeinden a
  WHERE           g.land||g.regierungsbezirk||g.kreis||lpad(g.gemeinde::text, 3,''00'') = a.gemeinde_schluessel 
  ORDER BY        g.land, g.regierungsbezirk, g.kreis;
	 
-- ==============================================================================
-- Geometrien der Flurstücke schrittweise zu groesseren Einheiten zusammen fassen
-- ==============================================================================

DELETE FROM alkis.pp_flur;

INSERT INTO alkis.pp_flur (land, regierungsbezirk, kreis, gemarkung, flurnummer, anz_fs, the_geom )
   SELECT  f.land, f.gemeindezugehoerigkeit_regierungsbezirk, f.gemeindezugehoerigkeit_kreis, f.gemarkungsnummer as gemarkung, f.flurnummer, 
           count(gml_id) as anz_fs,
           st_multi(st_union(f.wkb_geometry)) AS the_geom		-- angepasst am 10.03.2016
     FROM  alkis.ax_flurstueck f
     WHERE f.endet IS NULL
  GROUP BY f.land, f.gemeindezugehoerigkeit_regierungsbezirk, f.gemeindezugehoerigkeit_kreis, f.gemarkungsnummer, f.flurnummer;
	

-- Fluren zu Gemarkungen zusammen fassen
-- -------------------------------------

-- Flächen vereinigen
UPDATE alkis.pp_gemarkung a
  SET the_geom = 
   ( SELECT st_multi(st_union(b.the_geom)) AS the_geom		-- angepasst am 10.03.2016
       FROM alkis.pp_flur b
      WHERE a.land      = b.land 
        AND a.gemarkung = b.gemarkung
   );
	 

-- Fluren zaehlen
UPDATE alkis.pp_gemarkung a
  SET anz_flur = 
   ( SELECT count(flurnummer) AS anz_flur 
     FROM    alkis.pp_flur b
     WHERE a.land      = b.land 
       AND a.gemarkung = b.gemarkung
   ); -- Gemarkungsnummer ist je BundesLand eindeutig


-- Gemarkungen zu Gemeinden zusammen fassen
-- ----------------------------------------

-- Flächen vereinigen (aus der bereits vereinfachten Geometrie)
UPDATE alkis.pp_gemeinde a
  SET the_geom = 
   ( SELECT st_multi(st_union(b.the_geom)) AS the_geom		-- angepasst am 10.03.2016
     FROM    alkis.pp_gemarkung b
     WHERE a.land     = b.land 
			AND a.regierungsbezirk = b.regierungsbezirk			-- hinzugefügt am 18.09.2015
			AND	a.kreis = b.kreis														-- hinzugefügt am 18.09.2015
      AND a.gemeinde = b.gemeinde
   );
	 

-- Gemarkungen zählen
UPDATE alkis.pp_gemeinde a
  SET anz_gemarkg = 
   ( SELECT count(gemarkung) AS anz_gemarkg 
     FROM    alkis.pp_gemarkung b
     WHERE a.land     = b.land 
		 AND a.regierungsbezirk = b.regierungsbezirk				-- hinzugefügt am 18.09.2015
		 AND	a.kreis = b.kreis															-- hinzugefügt am 18.09.2015
     AND a.gemeinde = b.gemeinde
   );

	 
-- Flächen vereinigen (aus der bereits vereinfachten Geometrie)
UPDATE alkis.pp_amt a
  SET the_geom = 
   (SELECT st_multi(st_union(b.the_geom)) AS the_geom			-- angepasst am 10.03.2016
    FROM alkis.pp_gemeinde b, alkis.lk_aemtergemeinden c
    WHERE      
    b.land||b.regierungsbezirk||b.kreis||lpad(b.gemeinde::text,3,''00'') = c.gemeinde_schluessel
       AND c.amt_schluessel = a.amt
    GROUP BY c.amt_name
   );
	 

-- Gemeinden zählen
UPDATE alkis.pp_amt a
  SET anz_gemeinden = 
   ( SELECT count(gemeinde) AS anz_gemeinden 
     FROM    alkis.pp_gemeinde b, alkis.lk_aemtergemeinden c
     WHERE      
    
    b.land||b.regierungsbezirk||b.kreis||lpad(b.gemeinde::text,3,''00'') = c.gemeinde_schluessel
       AND c.amt_schluessel = a.amt
    GROUP BY c.amt_name
   );

-- Ämter zu Kreis zusammenfassen
-- ----------------------------------------

-- Flächen vereinigen (aus der bereits vereinfachten Geometrie)
UPDATE alkis.pp_kreis a
  SET the_geom = 
   (SELECT st_multi(st_union(b.the_geom)) AS the_geom 			-- angepasst am 10.03.2016
    -- noch mal Zugabe
    FROM alkis.pp_amt b
   );
	

-- Ämter zählen
UPDATE alkis.pp_kreis a
  SET anz_aemter = 
   ( SELECT count(amt) AS anz_amt 
     FROM alkis.pp_amt b
   );

-- Löcher schliessen	 
UPDATE alkis.pp_flur SET the_geom = Filter_Rings(the_geom, 10);		-- hinzugefügt am 10.03.2016	 
UPDATE alkis.pp_gemarkung SET the_geom = Filter_Rings(the_geom, 10);		-- hinzugefügt am 10.03.2016
UPDATE alkis.pp_gemeinde SET the_geom = Filter_Rings(the_geom, 10);		-- hinzugefügt am 10.03.2016
UPDATE alkis.pp_amt SET the_geom = Filter_Rings(the_geom, 10);		-- hinzugefügt am 10.03.2016
UPDATE alkis.pp_kreis SET the_geom = Filter_Rings(the_geom, 10);		-- hinzugefügt am 10.03.2016
	 
-- Geometrie glätten / vereinfachen
-- Diese "simplen" Geometrien sollen nur für die Darstellung einer Übersicht verwendet werden.
-- Ablage der simplen Geometrie in einem alternativen Geometriefeld im gleichen Datensatz.

UPDATE alkis.pp_flur      SET simple_geom = st_simplify(the_geom, 0.4); -- Flur 

UPDATE alkis.pp_gemarkung SET simple_geom = st_simplify(the_geom, 2.0); -- Gemarkung  (Wirkung siehe pp_gemarkung_analyse)

UPDATE alkis.pp_gemeinde  SET simple_geom = st_simplify(the_geom, 5.0); -- Gemeinde (Wirkung siehe pp_gemeinde_analyse)

UPDATE alkis.pp_amt SET simple_geom = st_simplify(the_geom, 5.0); 

UPDATE alkis.pp_kreis SET simple_geom = st_simplify(the_geom, 5.0); 



TRUNCATE alkis.pp_zuordungspfeilspitze_flurstueck;
INSERT INTO alkis.pp_zuordungspfeilspitze_flurstueck
SELECT l.ogc_fid,st_azimuth(st_pointn(l.wkb_geometry, 1), st_pointn(l.wkb_geometry, 2)) * (- 180::double precision) / pi() + 90::double precision AS winkel, l.beginnt,l.endet, f.abweichenderrechtszustand, st_startpoint(l.wkb_geometry) AS wkb_geometry
   FROM alkis.ap_lpo l
   JOIN alkis.ax_flurstueck f ON f.gml_id::text = ANY (l.dientzurdarstellungvon::text[])
  WHERE l.art::text = ''Pfeil''::text AND (''DKKM1000''::text ~~ ANY (l.advstandardmodell::text[]));

TRUNCATE alkis.pp_zuordungspfeilspitze_bodensch;
INSERT INTO alkis.pp_zuordungspfeilspitze_bodensch
SELECT l.ogc_fid, st_azimuth(st_pointn(l.wkb_geometry, 1), st_pointn(l.wkb_geometry, 2)) * (- 180::double precision) / pi() + 90::double precision AS winkel, st_startpoint(l.wkb_geometry) AS wkb_geometry
   FROM alkis.ap_lpo l
   JOIN alkis.ax_bodenschaetzung b ON b.gml_id = any(l.dientzurdarstellungvon)
  WHERE l.art::text = ''Pfeil''::text AND (''DKKM1000''::text ~~ ANY (l.advstandardmodell::text[])) AND b.endet IS NULL AND l.endet IS NULL;

UPDATE alkis.ax_anschrift SET postleitzahlpostzustellung = lpad(postleitzahlpostzustellung, 5, ''0'') WHERE bestimmungsland is null;		-- Hinzugefügt am 18.01.2016



DELETE FROM alkis.n_nutzung;

-- 01 REO: ax_Wohnbauflaeche
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 11,                  0,      0,      artderbebauung, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_wohnbauflaeche
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 02 REO: ax_IndustrieUndGewerbeflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 12,                  coalesce(funktion, 0),  coalesce(lagergut, 0), null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_industrieundgewerbeflaeche
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 03 REO: ax_Halde
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 13,                  coalesce(lagergut, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_halde
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 04 ax_Bergbaubetrieb
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 14,                  coalesce(abbaugut, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_bergbaubetrieb
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 05 REO: ax_TagebauGrubeSteinbruch
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 15,                  coalesce(abbaugut, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_tagebaugrubesteinbruch
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 06 REO: ax_FlaecheGemischterNutzung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 16,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_flaechegemischternutzung
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 07 REO: ax_FlaecheBesondererFunktionalerPraegung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 17,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_flaechebesondererfunktionalerpraegung
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 08 REO: ax_SportFreizeitUndErholungsflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 18,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_sportfreizeitunderholungsflaeche
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 09 REO: ax_Friedhof
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 19,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry 
  FROM alkis.ax_friedhof
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 10 ax_Strassenverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,   zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 21,                  coalesce(funktion, 0),  0, null,   zustand, zeigtaufexternes_name, zweitname,   wkb_geometry 
  FROM alkis.ax_strassenverkehr
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 11 ax_Weg
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,  zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 22,                  coalesce(funktion, 0),  0, null,  null,    zeigtaufexternes_name, bezeichnung, wkb_geometry 
  FROM alkis.ax_weg
  WHERE endet IS NULL;

-- 12 ax_Platz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 23,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, zweitname,   wkb_geometry 
  FROM alkis.ax_platz
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 13 ax_Bahnverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,                         werteart2, info,          zustand, name,        bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 24,                  coalesce(funktion, bahnkategorie[0], 0), 0, null, zustand, NULL, null,        wkb_geometry 
  FROM alkis.ax_bahnverkehr
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 14 ax_Flugverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,               werteart2, info,  zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 25,                  coalesce(funktion, art, 0), 0, null,  zustand, zeigtaufexternes_name, bezeichnung, wkb_geometry 
  FROM alkis.ax_flugverkehr
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 15 ax_Schiffsverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 26,                  coalesce(funktion, 0),  0, null, zustand, zeigtaufexternes_name, null,        wkb_geometry 
  FROM alkis.ax_schiffsverkehr
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 16 ax_Landwirtschaft
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 31,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    name, null,        wkb_geometry 
  FROM alkis.ax_landwirtschaft
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 17 ax_Wald
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 32,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    name, bezeichnung, wkb_geometry 
  FROM alkis.ax_wald
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 18 ax_Gehoelz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 33,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    null, null,        wkb_geometry 
  FROM alkis.ax_gehoelz
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 19 ax_Heide
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 34,                  0,      0, null, null,    name, null,        wkb_geometry 
  FROM alkis.ax_heide
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 20 ax_Moor
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 35,                  0,      0, null, null,    name, null,        wkb_geometry 
  FROM alkis.ax_moor
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 21 ax_Sumpf
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 36,                  0,      0, null, null,    name, null,        wkb_geometry 
  FROM alkis.ax_sumpf
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 22 ax_UnlandVegetationsloseFlaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,                 zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 37,                  coalesce(funktion, 0),  coalesce(oberflaechenmaterial, 0), null, null,    name, null,        wkb_geometry 
  FROM alkis.ax_unlandvegetationsloseflaeche
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 24 ax_Fliessgewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand,  name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 41,                  coalesce(funktion, 0),  0, null, zustand,  zeigtaufexternes_name, null,        wkb_geometry 
  FROM alkis.ax_fliessgewaesser
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 25 ax_Hafenbecken
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,    zustand,   name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 42,                  coalesce(funktion, 0),  0, null, null,      zeigtaufexternes_name, null,        wkb_geometry 
  FROM alkis.ax_hafenbecken
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 26 ax_StehendesGewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung,         wkb_geometry)
  SELECT              gml_id, beginnt, endet, 43,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, gewaesserkennziffer, wkb_geometry 
  FROM alkis.ax_stehendesgewaesser
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

-- 27 ax_Meer
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT              gml_id, beginnt, endet, 44,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, bezeichnung, wkb_geometry 
  FROM alkis.ax_meer
  WHERE st_geometrytype(wkb_geometry) = ''ST_Polygon'';

UPDATE alkis.n_nutzung SET werteart1 = 0 WHERE werteart1 = 9999;
UPDATE alkis.n_nutzung SET werteart2 = 0 WHERE werteart2 = 9999;
'

LANGUAGE sql VOLATILE
COST 100;

COMMIT;
