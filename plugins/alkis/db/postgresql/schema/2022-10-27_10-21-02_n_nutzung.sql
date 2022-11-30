BEGIN;

ALTER TABLE alkis.n_nutzung ADD COLUMN datumderletztenueberpruefung timestamp without time zone;
ALTER TABLE alkis.n_nutzung ADD COLUMN herkunft_source_source_ax_datenerhebung character varying[];


DROP VIEW alkis.lk_nutzungen;

CREATE OR REPLACE VIEW alkis.lk_nutzungen AS 
 SELECT n.gid,
    n.gml_id,
    n.beginnt,
    n.endet,
    n.werteart1,
    n.werteart2,
    n.info,
        CASE
            WHEN n.info = 1000 THEN 'Art der Bebauung - Offen'::text
            WHEN n.info = 2000 THEN 'Art der Bebauung - Geschlossen'::text
            ELSE NULL::text
        END AS info_bezeichner,
    n.zustand,
    n.name,
    n.bezeichnung,
    ((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text AS nutzungsartschluessel,
    nag.bereich,
    nag.gruppe AS nutzungsartengruppe,
    na.nutzungsart,
    nu1.untergliederung1,
    nu2.untergliederung2,
    nas.nutzungsartengruppe AS nutzungsartengruppeschl,
    nas.nutzungsart AS nutzungsartschl,
    nas.untergliederung1 AS untergliederung1schl,
    nas.untergliederung2 AS untergliederung2schl,
    nas.objektart AS alkis_objart,
    nas.attributart1 AS alkis_attributart1,
    nas.attributart2 AS alkis_attributart2,
    round(st_area(n.wkb_geometry)) AS flaeche,
    n.datumderletztenueberpruefung,
    n.herkunft_source_source_ax_datenerhebung as datenerhebung_art,
    n.wkb_geometry AS the_geom
   FROM alkis.n_nutzung n
     LEFT JOIN alkis.n_nutzungsartenschluessel nas ON n.nutzungsartengruppe = nas.nutzungsartengruppe AND n.werteart1 = nas.werteart1 AND n.werteart2 = nas.werteart2
     LEFT JOIN alkis.n_nutzungsartengruppe nag ON nas.nutzungsartengruppe = nag.schluessel
     LEFT JOIN alkis.n_nutzungsart na ON nas.nutzungsartengruppe = na.nutzungsartengruppe AND nas.nutzungsart = na.schluessel
     LEFT JOIN alkis.n_untergliederung1 nu1 ON nas.nutzungsartengruppe = nu1.nutzungsartengruppe AND nas.nutzungsart = nu1.nutzungsart AND nas.untergliederung1 = nu1.schluessel
     LEFT JOIN alkis.n_untergliederung2 nu2 ON nas.nutzungsartengruppe = nu2.nutzungsartengruppe AND nas.nutzungsart = nu2.nutzungsart AND nas.untergliederung1 = nu2.untergliederung1 AND nas.untergliederung2 = nu2.schluessel;



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


TRUNCATE alkis.pp_amt;
	 
INSERT INTO alkis.pp_amt
  (land, regierungsbezirk, kreis, amt, amtsname, postleitzahlpostzustellung, ort_post, strasse, hausnummer, fax, telefon, weitereadressen)
  SELECT DISTINCT g.land, g.regierungsbezirk, g.kreis, a.amt_schluessel,
                  CASE WHEN position('(' in a.amt_name) > 0
                    THEN substr(a.amt_name, 1, position('(' in a.amt_name)-2)
                    ELSE a.amt_name
                  END as amt_name,
                  postleitzahlpostzustellung,
                  ort_post,
                  strasse,
                  hausnummer,
                  fax,
                  telefon,
                  weitereadressen
  FROM            alkis.pp_gemeinde g, alkis.lk_aemtergemeinden a
  LEFT JOIN    	  alkis.ax_dienststelle d ON d.schluesselgesamt = a.dienststelle_schluessel AND stellenart IN (1700, 1900)
  LEFT JOIN       alkis.ax_anschrift an ON d.hat = an.gml_id
  WHERE           g.land||g.regierungsbezirk||g.kreis||lpad(g.gemeinde::text, 3,'00') = a.gemeinde_schluessel and an.endet is null and d.endet is null 
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
    b.land||b.regierungsbezirk||b.kreis||lpad(b.gemeinde::text,3,'00') = c.gemeinde_schluessel
       AND c.amt_schluessel = a.amt
    GROUP BY c.amt_name
   );
	 

-- Gemeinden zählen
UPDATE alkis.pp_amt a
  SET anz_gemeinden = 
   ( SELECT count(gemeinde) AS anz_gemeinden 
     FROM    alkis.pp_gemeinde b, alkis.lk_aemtergemeinden c
     WHERE      
    
    b.land||b.regierungsbezirk||b.kreis||lpad(b.gemeinde::text,3,'00') = c.gemeinde_schluessel
       AND c.amt_schluessel = a.amt
    GROUP BY c.amt_name
   );

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



DELETE FROM alkis.n_nutzung;

-- 01 REO: ax_Wohnbauflaeche
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 11,                  0,      0,      artderbebauung, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_wohnbauflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 02 REO: ax_IndustrieUndGewerbeflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 12,                  coalesce(funktion, 0),  coalesce(lagergut, foerdergut, 0), null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_industrieundgewerbeflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 03 REO: ax_Halde
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 13,                  coalesce(lagergut, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_halde
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 04 ax_Bergbaubetrieb
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 14,                  coalesce(abbaugut, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_bergbaubetrieb
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 05 REO: ax_TagebauGrubeSteinbruch
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 15,                  coalesce(abbaugut, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_tagebaugrubesteinbruch
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 06 REO: ax_FlaecheGemischterNutzung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 16,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_flaechegemischternutzung
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 07 REO: ax_FlaecheBesondererFunktionalerPraegung
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 17,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_flaechebesondererfunktionalerpraegung
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 08 REO: ax_SportFreizeitUndErholungsflaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 18,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_sportfreizeitunderholungsflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 09 REO: ax_Friedhof
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 19,                  coalesce(funktion, 0),  0, null, zustand, name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_friedhof
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 10 ax_Strassenverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,   zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 21,                  coalesce(funktion, 0),  0, null,   zustand, zeigtaufexternes_name, zweitname,   wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_strassenverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 11 ax_Weg
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,  zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 22,                  coalesce(funktion, 0),  0, null,  null,    zeigtaufexternes_name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_weg
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 12 ax_Platz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 23,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, zweitname,   wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_platz
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 13 ax_Bahnverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,                         werteart2, info,          zustand, name,        bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 24,                  coalesce(funktion, bahnkategorie[1], 0), 0, null, zustand, NULL, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_bahnverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 14 ax_Flugverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,               werteart2, info,  zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 25,                  coalesce(funktion, art, 0), 0, null,  zustand, zeigtaufexternes_name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_flugverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 15 ax_Schiffsverkehr
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 26,                  coalesce(funktion, 0),  0, null, zustand, zeigtaufexternes_name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_schiffsverkehr
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 16 ax_Landwirtschaft
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 31,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_landwirtschaft
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 17 ax_Wald
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 32,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_wald
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 18 ax_Gehoelz
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1,          werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 33,                  coalesce(vegetationsmerkmal, 0), 0, null, null,    null, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_gehoelz
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 19 ax_Heide
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 34,                  0,      0, null, null,    name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_heide
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 20 ax_Moor
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 35,                  0,      0, null, null,    name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_moor
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 21 ax_Sumpf
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 36,                  0,      0, null, null,    name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_sumpf
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 22 ax_UnlandVegetationsloseFlaeche
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,                 zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 37,                  coalesce(funktion, 0),  coalesce(oberflaechenmaterial, 0), null, null,    name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_unlandvegetationsloseflaeche
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 24 ax_Fliessgewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand,  name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 41,                  coalesce(funktion, 0),  0, null, zustand,  zeigtaufexternes_name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_fliessgewaesser
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 25 ax_Hafenbecken
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info,    zustand,   name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 42,                  coalesce(funktion, 0),  0, null, null,      zeigtaufexternes_name, null,        wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_hafenbecken
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 26 ax_StehendesGewaesser
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung,         wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 43,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, gewaesserkennziffer, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_stehendesgewaesser
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

-- 27 ax_Meer
-- -------------------------------------
INSERT INTO alkis.n_nutzung (gml_id, beginnt, endet, nutzungsartengruppe, werteart1, werteart2, info, zustand, name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung)
  SELECT              gml_id, beginnt, endet, 44,                  coalesce(funktion, 0),  0, null, null,    zeigtaufexternes_name, bezeichnung, wkb_geometry, datumderletztenueberpruefung, herkunft_source_source_ax_datenerhebung
  FROM alkis.ax_meer
  WHERE st_geometrytype(wkb_geometry) = 'ST_Polygon';

$BODY$
  LANGUAGE sql VOLATILE
  COST 100;


COMMIT;
