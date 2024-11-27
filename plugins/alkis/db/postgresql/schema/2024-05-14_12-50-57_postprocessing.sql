BEGIN;


CREATE OR REPLACE FUNCTION alkis.postprocessing()
  RETURNS void AS
$BODY$


SET client_encoding = 'UTF-8';
SET enable_seqscan = off;


TRUNCATE alkis.pp_flurstueckshistorie;

INSERT INTO alkis.pp_flurstueckshistorie 
	SELECT flurstueckskennzeichen,
		zeitpunktderentstehung,
		vorgaengerflurstueckskennzeichen,
		nachfolgerflurstueckskennzeichen
  FROM 
		alkis.ax_historischesflurstueckohneraumbezug
  WHERE 
		endet IS NULL
	UNION ALL
	(	SELECT DISTINCT ON (f.flurstueckskennzeichen) 
			f.flurstueckskennzeichen,
			f.zeitpunktderentstehung,
			COALESCE(vva.vorgaengerflurstueckskennzeichen, vna.vorgaengerflurstueckskennzeichen) AS vorgaengerflurstueckskennzeichen,
			n.nachfolgerflurstueckskennzeichen
		FROM alkis.ax_flurstueck f
			LEFT JOIN LATERAL ( SELECT 
														array_agg(hf.flurstueckskennzeichen) AS vorgaengerflurstueckskennzeichen
													FROM 
														alkis.ax_historischesflurstueckohneraumbezug hf
													WHERE 
														hf.endet IS NULL AND 
														ARRAY[f.flurstueckskennzeichen] <@ hf.nachfolgerflurstueckskennzeichen) vva ON true
			LEFT JOIN LATERAL ( SELECT 
														array_agg(u.flurstueckskennzeichen) AS vorgaengerflurstueckskennzeichen
													FROM 
														alkis.ax_fortfuehrungsfall ff,
														LATERAL unnest(ff.zeigtaufaltesflurstueck) u(flurstueckskennzeichen)
													WHERE 
														ff.endet IS NULL AND 
														ARRAY[f.flurstueckskennzeichen] <@ ff.zeigtaufneuesflurstueck AND NOT ARRAY[f.flurstueckskennzeichen] <@ ff.zeigtaufaltesflurstueck) vna ON true
			LEFT JOIN LATERAL ( SELECT 
														array_agg(u.flurstueckskennzeichen) AS nachfolgerflurstueckskennzeichen
													FROM 
														alkis.ax_fortfuehrungsfall ff,
														LATERAL unnest(ff.zeigtaufneuesflurstueck) u(flurstueckskennzeichen)
													WHERE 
														ff.endet IS NULL AND 
														ARRAY[f.flurstueckskennzeichen] <@ ff.zeigtaufaltesflurstueck AND NOT ARRAY[f.flurstueckskennzeichen] <@ ff.zeigtaufneuesflurstueck) n ON true
		ORDER BY 
			f.flurstueckskennzeichen, f.beginnt DESC);

ANALYZE alkis.pp_flurstueckshistorie;


-- =================================
-- Flurstuecksnummern-Label-Position
-- =================================

  TRUNCATE alkis.pp_flurstueck_nr;  -- effektiver als DELETE

 INSERT INTO alkis.pp_flurstueck_nr
          ( fsgml, beginnt, endet, f_beginnt, f_endet, fsnum, abweichenderrechtszustand, the_geom )															-- angepasst am 25.06.2015
     SELECT DISTINCT f.gml_id,p.beginnt,p.endet,f.beginnt, f.endet,
           f.zaehler::text || COALESCE ('/' || f.nenner::text, '') AS fsnum, f.abweichenderrechtszustand,		-- angepasst am 25.06.2015
           p.wkb_geometry  -- manuelle Position des Textes
      FROM alkis.ap_pto             p
      JOIN alkis.ax_flurstueck      f ON f.gml_id = any(p.dientzurdarstellungvon)
     AND p.art = 'ZAE_NEN';

-- Tabelle aus View befüllen
TRUNCATE alkis.pp_strassenname;
INSERT INTO alkis.pp_strassenname (gml_id, schriftinhalt, hor, ver, art, winkel, the_geom, gemeinde, lage)
       SELECT gml_id, schriftinhalt, hor, ver, art, winkel, wkb_geometry, gemeinde, lage
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
         p.wkb_geometry,
         l.gemeinde,
         l.lage
    FROM alkis.ap_pto p
    JOIN alkis.ax_lagebezeichnungohnehausnummer l
      ON l.gml_id = ANY (p.dientzurdarstellungvon)
   WHERE p.endet IS NULL
     AND l.endet IS NULL
     AND  p.art IN ('Strasse','Weg','Platz','BezKlassifizierungStrasse') -- CLASSES im LAYER
     AND (   'DKKM1000' = ANY (p.advstandardmodell) -- "Lika 1000" bevorzugen
       -- OR 'DLKM'     = ANY (p.advstandardmodell) -- oder auch Kataster allgemein
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
      WHERE p.gml_id = l.gml_id
      and k.endet is null
      and l.endet is null
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


TRUNCATE alkis.pp_amt;
	 
INSERT INTO 
	alkis.pp_amt (land, regierungsbezirk, kreis, amt, amtsname, anz_gemeinden, the_geom)
  	
	SELECT  
		v.land, v.regierungsbezirk, v.kreis, v.verwaltungsgemeinschaft,
		CASE WHEN position('(' in v.bezeichnung) > 0
			THEN substr(v.bezeichnung, 1, position('(' in v.bezeichnung)-2)
			ELSE v.bezeichnung
		END as amt_name,
		count(*),
		st_multi(st_union(pp.the_geom))
  	FROM
		alkis.ax_gemeinde g
		join alkis.pp_gemeinde pp on g.land = pp.land and g.regierungsbezirk = pp.regierungsbezirk and g.kreis = pp.kreis and g.gemeinde = pp.gemeinde
  		join alkis.ax_verwaltungsgemeinschaft v on v.gml_id = any(g.istteilvon)
	where 
		g.istteilvon is not null and
		v.endet is null 
	GROUP BY
		v.land, v.regierungsbezirk, v.kreis, v.verwaltungsgemeinschaft, v.bezeichnung
  	ORDER BY        v.land, v.regierungsbezirk, v.kreis, v.verwaltungsgemeinschaft;

	 
-- Ämter zu Kreis zusammenfassen
-- ----------------------------------------

DELETE FROM alkis.pp_kreis;

INSERT INTO alkis.pp_kreis (land, regierungsbezirk, kreis, kreisname, anz_aemter, the_geom)
SELECT  a.land, a.regierungsbezirk, a.kreis, k.bezeichnung, count(a.amt), st_multi(st_union(a.the_geom))
FROM  alkis.pp_amt a, alkis.ax_kreisregion k
WHERE a.kreis = k.kreis
AND k.endet IS NULL
GROUP BY a.land, a.regierungsbezirk, a.kreis, k.bezeichnung;


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
   JOIN alkis.ax_flurstueck f ON f.gml_id = ANY (l.dientzurdarstellungvon)
  WHERE l.art::text = 'Pfeil' AND ('DKKM1000' ~~ ANY (l.advstandardmodell));

TRUNCATE alkis.pp_zuordungspfeilspitze_bodensch;
INSERT INTO alkis.pp_zuordungspfeilspitze_bodensch
SELECT l.ogc_fid, st_azimuth(st_pointn(l.wkb_geometry, 1), st_pointn(l.wkb_geometry, 2)) * (- 180::double precision) / pi() + 90::double precision AS winkel, st_startpoint(l.wkb_geometry) AS wkb_geometry
   FROM alkis.ap_lpo l
   JOIN alkis.ax_bodenschaetzung b ON b.gml_id = any(l.dientzurdarstellungvon)
  WHERE l.art::text = 'Pfeil' AND ('DKKM1000' ~~ ANY (l.advstandardmodell)) AND b.endet IS NULL AND l.endet IS NULL;

UPDATE alkis.ax_anschrift SET postleitzahlpostzustellung = lpad(postleitzahlpostzustellung, 5, '0') WHERE bestimmungsland is null;		-- Hinzugefügt am 18.01.2016



TRUNCATE alkis.n_nutzung;
ALTER SEQUENCE alkis.n_nutzung_gid_seq RESTART;

-- 01 REO: ax_Wohnbauflaeche
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe, werteart1, werteart2,           info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41001,                  11,         0,         0, artderbebauung, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_wohnbauflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 02 REO: ax_IndustrieUndGewerbeflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1,                                         werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41002,                  12, coalesce(funktion, 0), coalesce(lagergut, foerdergut, primaerenergie, 0), null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_industrieundgewerbeflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 03 REO: ax_Halde
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41003,                  13, coalesce(lagergut, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_halde
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 04 ax_Bergbaubetrieb
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,                       werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41004,                  14, coalesce(funktion, abbaugut, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_bergbaubetrieb
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 05 REO: ax_TagebauGrubeSteinbruch
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,                       werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41005,                  15, coalesce(funktion, abbaugut, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_tagebaugrubesteinbruch
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 06 REO: ax_FlaecheGemischterNutzung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41006,                  16, coalesce(funktion, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_flaechegemischternutzung
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 07 REO: ax_FlaecheBesondererFunktionalerPraegung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41007,                  17, coalesce(funktion, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_flaechebesondererfunktionalerpraegung
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 08 REO: ax_SportFreizeitUndErholungsflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41008,                  18, coalesce(funktion, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_sportfreizeitunderholungsflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 09 REO: ax_Friedhof
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     41009,                  19, coalesce(funktion, 0),         0, null, zustand, name,        null, wkb_geometry 
  FROM alkis.ax_friedhof
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 10 ax_Strassenverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42001,                  21, coalesce(funktion, 0),         0, null, zustand, zeigtaufexternes_name,   zweitname, wkb_geometry 
  FROM alkis.ax_strassenverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 11 ax_Weg
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42006,                  21, coalesce(funktion, 0),         0, null,    null, zeigtaufexternes_name, bezeichnung, wkb_geometry
  FROM alkis.ax_weg
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 12 ax_Platz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42009,                  21, coalesce(funktion, 0),         0, null,    null, zeigtaufexternes_name,   zweitname, wkb_geometry
  FROM alkis.ax_platz
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 13 ax_Bahnverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42010,                  22, coalesce(funktion, 0),         0, null, zustand, NULL,        NULL, wkb_geometry 
  FROM alkis.ax_bahnverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 14 ax_flugverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42015,                  23, coalesce(funktion, 0),         0, null, zustand, zeigtaufexternes_name, bezeichnung, wkb_geometry 
  FROM alkis.ax_flugverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 15 ax_Schiffsverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     42016,                  24, coalesce(funktion, 0),         0, null, zustand, zeigtaufexternes_name,        null, wkb_geometry 
  FROM alkis.ax_schiffsverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 16 ax_Landwirtschaft
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,                       werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43001,                  31, coalesce(vegetationsmerkmal, 0),         0, null,    null, name,        null, wkb_geometry 
  FROM alkis.ax_landwirtschaft
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 17 ax_Wald
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,            werteart1,            werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43002,                  32, coalesce(nutzung, 0), coalesce(zustand, 0), null,    null, name, bezeichnung, wkb_geometry 
  FROM alkis.ax_wald
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 18 ax_Gehoelz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43003,                  33,         0,         0, null,    null, null,        null, wkb_geometry 
  FROM alkis.ax_gehoelz
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 19 ax_Heide
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43004,                  34,         0,         0, null,    null, name,        null, wkb_geometry 
  FROM alkis.ax_heide
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 20 ax_Moor
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43005,                  35,         0,         0, null,    null, name,        null, wkb_geometry 
  FROM alkis.ax_moor
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 21 ax_Sumpf
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43006,                  36,         0,         0, null,    null, name,        null, wkb_geometry 
  FROM alkis.ax_sumpf
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 22 ax_UnlandVegetationsloseFlaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1,                         werteart2, info, zustand, name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     43007,                  37, coalesce(funktion, 0), coalesce(oberflaechenmaterial, 0), null,    null, name,        null, wkb_geometry 
  FROM alkis.ax_unlandvegetationsloseflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 24 ax_Fliessgewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     44001,                  41, coalesce(funktion, 0),         0, null, zustand, zeigtaufexternes_name,        null, wkb_geometry 
  FROM alkis.ax_fliessgewaesser
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 25 ax_Hafenbecken
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     44005,                  42, coalesce(funktion, 0),         0, null,    null, zeigtaufexternes_name,        null, wkb_geometry 
  FROM alkis.ax_hafenbecken
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 26 ax_StehendesGewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     44006,                  43, coalesce(funktion, 0),         0, null,    null, zeigtaufexternes_name, seekennzahl, wkb_geometry 
  FROM alkis.ax_stehendesgewaesser
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 27 ax_Meer
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, objektart, nutzungsartengruppe,             werteart1, werteart2, info, zustand,                  name, bezeichnung, wkb_geometry)
  SELECT                     gml_id, beginnt, endet,     44007,                  44, coalesce(funktion, 0),         0, null,    null, zeigtaufexternes_name, bezeichnung, wkb_geometry 
  FROM alkis.ax_meer
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

$BODY$
  LANGUAGE sql VOLATILE
  COST 100;



CREATE OR REPLACE FUNCTION alkis.execute_hist_operations(
	)
    RETURNS void
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE 
AS $BODY$
DECLARE
	r alkis.delete%ROWTYPE;
	s varchar;
	n integer;
BEGIN
	FOR r IN
		select *
		from alkis.delete 
		order by typename, featureid, context, replacedby, endet
	LOOP
		s := 'UPDATE alkis.' || r.typename || ' SET endet = ''' || r.endet || '''';
		IF r.context='update' AND r.anlass IS NOT NULL THEN
			s := s || ',anlass=array_cat(anlass,''{' || array_to_string(r.anlass,',') || '}'')';
		END IF;
		s := s || ' WHERE gml_id=''' || r.featureid || '''' || ' AND beginnt < ''' || r.endet || ''' AND (endet IS NULL OR endet = ''' || r.endet || ''')';
		EXECUTE s;
		--RAISE INFO '%', s;
		GET DIAGNOSTICS n = ROW_COUNT;
		IF n=0 THEN
			RAISE NOTICE 'Beenden des Objektes % schlug fehl: %', r.featureid, s;
		ELSE
			IF n>1 THEN
				RAISE NOTICE 'Es gab mehrere Objektversionen von % die jetzt alle beendet wurden: %', r.featureid, s;
			END IF;
			s := 'DELETE FROM alkis.delete WHERE ogc_fid = ' || r.ogc_fid;
			EXECUTE s;
			GET DIAGNOSTICS n = ROW_COUNT;
			IF n<>1 THEN
				RAISE EXCEPTION 'Löschen des Eintrags in der delete-Tabelle schlug fehl: %', s;
			END IF;
		END IF;
	END LOOP;
END;
$BODY$;



COMMIT;
