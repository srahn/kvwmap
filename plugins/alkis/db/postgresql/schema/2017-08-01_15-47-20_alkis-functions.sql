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

 -- Löschsatz verarbeiten (MIT Historie)
 -- context='delete'        => "endet" auf aktuelle Zeit setzen
 -- context='replace'       => "endet" des ersetzten auf "beginnt" des neuen Objekts setzen
 -- context='update'        => "endet" auf übergebene Zeit setzen und "anlass" festhalten
 CREATE OR REPLACE FUNCTION delete_feature_hist() RETURNS TRIGGER AS $$
 DECLARE
 	n INTEGER;
 	beginnt TEXT;
 	s TEXT;
 BEGIN
 	NEW.context := coalesce(lower(NEW.context),'delete');

 	IF length(NEW.featureid)=32 THEN
 		beginnt := substr(NEW.featureid, 17, 4) || '-'
 			|| substr(NEW.featureid, 21, 2) || '-'
 			|| substr(NEW.featureid, 23, 2) || 'T'
 			|| substr(NEW.featureid, 26, 2) || ':'
 			|| substr(NEW.featureid, 28, 2) || ':'
 			|| substr(NEW.featureid, 30, 2) || 'Z'
 			;
 	ELSIF length(NEW.featureid)=16 THEN
 		-- Ältestes nicht gelöschtes Objekt
 		EXECUTE 'SELECT min(beginnt) FROM ' || NEW.typename
 			|| ' WHERE gml_id=''' || substr(NEW.featureid, 1, 16) || ''''
 			|| ' AND endet IS NULL'
 			INTO beginnt;

 		IF beginnt IS NULL THEN
 			RAISE EXCEPTION '%: Keinen Kandidaten zum Löschen gefunden.', NEW.featureid;
 		END IF;
 	ELSE
 		RAISE EXCEPTION '%: Identifikator gescheitert.', NEW.featureid;
 	END IF;

 	IF NEW.context='delete' THEN
 		NEW.endet := to_char(CURRENT_TIMESTAMP AT TIME ZONE 'UTC','YYYY-MM-DD"T"HH24:MI:SS"Z"');

 	ELSIF NEW.context='update' THEN
 		IF NEW.endet IS NULL THEN
 			RAISE EXCEPTION '%: Endedatum nicht gesetzt', NEW.featureid;
 		END IF;

 	ELSIF NEW.context='replace' THEN
 		NEW.safetoignore := lower(NEW.safetoignore);
 		IF NEW.safetoignore IS NULL THEN
 			RAISE EXCEPTION '%: safeToIgnore nicht gesetzt.', NEW.featureid;
 		ELSIF NEW.safetoignore<>'true' AND NEW.safetoignore<>'false' THEN
 			RAISE EXCEPTION '%: safeToIgnore ''%'' ungültig (''true'' oder ''false'' erwartet).', NEW.featureid, NEW.safetoignore;
 		END IF;

 		IF length(NEW.replacedby)=32 AND NEW.replacedby<>NEW.featureid THEN
 			NEW.endet := substr(NEW.replacedby, 17, 4) || '-'
 				  || substr(NEW.replacedby, 21, 2) || '-'
 				  || substr(NEW.replacedby, 23, 2) || 'T'
 				  || substr(NEW.replacedby, 26, 2) || ':'
 				  || substr(NEW.replacedby, 28, 2) || ':'
 				  || substr(NEW.replacedby, 30, 2) || 'Z'
 				  ;
 		END IF;

 		IF NEW.endet IS NULL THEN
 			-- Beginn des ersten Nachfolgeobjektes
 			EXECUTE 'SELECT min(beginnt) FROM ' || NEW.typename || ' a'
 				|| ' WHERE gml_id=''' || substr(NEW.replacedby, 1, 16) || ''''
 				|| ' AND beginnt>''' || beginnt || ''''
 				INTO NEW.endet;
 		ELSE
 			EXECUTE 'SELECT count(*) FROM ' || NEW.typename
 				|| ' WHERE gml_id=''' || substr(NEW.replacedby, 1, 16) || ''''
 				|| ' AND beginnt=''' || NEW.endet || ''''
 				INTO n;
 			IF n<>1 THEN
 				RAISE EXCEPTION '%: Ersatzobjekt % % nicht gefunden.', NEW.featureid, NEW.replacedby, NEW.endet;
 			END IF;
 		END IF;

 		IF NEW.endet IS NULL THEN
 			IF NEW.safetoignore='false' THEN
 				RAISE EXCEPTION '%: Beginn des Ersatzobjekts % nicht gefunden.', NEW.featureid, NEW.replacedby;
 				-- RAISE NOTICE '%: Beginn des ersetzenden Objekts % nicht gefunden.', NEW.featureid, NEW.replacedby;
 			END IF;

 			NEW.ignored=true;
 			RETURN NEW;
 		END IF;

 	ELSE
 		RAISE EXCEPTION '%: Ungültiger Kontext % (''delete'', ''replace'' oder ''update'' erwartet).', NEW.featureid, NEW.context;

 	END IF;

 	s := 'UPDATE ' || NEW.typename || ' SET endet=''' || NEW.endet || '''';

 	IF NEW.context='update' AND NEW.anlass IS NOT NULL THEN
 		s := s || ',anlass=array_cat(anlass,''{' || array_to_string(NEW.anlass,',') || '}'')';
 	END IF;

 	s := s || ' WHERE gml_id=''' || substr(NEW.featureid, 1, 16) || ''''
 	       || ' AND beginnt=''' || beginnt || ''''
 	       ;
 	EXECUTE s;
 	GET DIAGNOSTICS n = ROW_COUNT;
 	-- RAISE NOTICE 'SQL[%]:%', n, s;
 	IF n<>1 THEN
 		RAISE EXCEPTION '%: % schlug fehl [%]', NEW.featureid, NEW.context, n;
 		-- RAISE NOTICE '%: % schlug fehl [%]', NEW.featureid, NEW.context, n;
 		-- NEW.ignored=true;
 		-- RETURN NEW;
 	END IF;

 	NEW.ignored := false;
 	RETURN NEW;
 END;
 $$ LANGUAGE plpgsql;

 --- Tabelle "delete" für Lösch- und Fortführungsdatensätze
 CREATE TABLE "delete" (
        ogc_fid         serial NOT NULL,
        typename        varchar,
        featureid       varchar,
        context         varchar,                -- delete/replace/update
        safetoignore    varchar,                -- replace.safetoignore 'true'/'false'
        replacedBy      varchar,                -- gmlid
        anlass          varchar[],              -- update.anlass
        endet           character(20),          -- update.endet
        ignored         boolean DEFAULT false,  -- Satz wurde nicht verarbeitet
        PRIMARY KEY (ogc_fid)
 );

 CREATE INDEX delete_fid ON "delete"(featureid);

 COMMENT ON COLUMN delete.context      IS 'Operation ''delete'', ''replace'' oder ''update''.';
 COMMENT ON COLUMN delete.safetoignore IS 'Attribut safeToIgnore von wfsext:Replace';
 COMMENT ON COLUMN delete.replacedBy   IS 'gml_id des Objekts, das featureid ersetzt';
 COMMENT ON COLUMN delete.anlass       IS 'Anlaß des Endes';
 COMMENT ON COLUMN delete.endet        IS 'Zeitpunkt des Endes';
 COMMENT ON COLUMN delete.ignored      IS 'Löschsatz wurde ignoriert';

 CREATE TRIGGER delete_feature_trigger
 	BEFORE INSERT ON delete
 	FOR EACH ROW
 	EXECUTE PROCEDURE delete_feature_hist();



COMMIT;
