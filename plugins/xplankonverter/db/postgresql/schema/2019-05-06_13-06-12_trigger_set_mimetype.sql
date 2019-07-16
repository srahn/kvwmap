BEGIN;

CREATE FUNCTION xplankonverter.set_mimetype()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF 
AS $BODY$
DECLARE
	mimetype json := '{
		"xls" : "application/msexcel",
		"xlsx" : "application/msexcel",
		"doc" : "application/msword",
		"docx" : "application/msword",
		"odt" : "application/odt",
		"ods" : "application/odt",
		"pdf" : "application/pdf",
		"gml" : "application/vnd.ogc.gml",
		"xml" : "application/xml",
		"zip" : "application/zip",
		"ecw" : "image/ecw",
		"jpg" : "image/jpg",
		"jpeg" : "image/jpg",
		"png" : "image/png",
		"svg" : "image/svg+xml",
		"tif" : "image/tiff",
		"tiff" : "image/tiff",
		"php" : "text/html", 
		"htm" : "text/html",
		"html" : "text/html",
		"txt" : "text/plain"
		}';
BEGIN
	IF ((TG_OP = 'INSERT' AND NEW.externereferenz is NOT NULL) OR (TG_OP = 'UPDATE' AND NEW.externereferenz IS NOT NULL AND (OLD.externereferenz IS NULL OR NEW.externereferenz != OLD.externereferenz))) THEN
		RAISE NOTICE 'Check mimetype';
		SELECT
			ARRAY_AGG(
				ROW(
					(g).georefurl,
					(select row(codespace, id, value) from xplan_gml.xp_mimetypes WHERE id = mimetype ->> lower(regexp_replace((g).georefurl, '^.*\.', ''))),
					(g).art,
					(g).informationssystemurl,
					(g).referenzname,
					(g).referenzurl,
					(select row(codespace, id, value) from xplan_gml.xp_mimetypes WHERE id = mimetype ->> lower(regexp_replace((g).referenzurl, '^.*\.', ''))),
					(g).beschreibung,
					(g).datum,
					(g).typ
				)
			)
		INTO
			NEW.externereferenz
		FROM
			(
				SELECT unnest(NEW.externereferenz) g
			) AS ext;

	END IF;
	RETURN NEW;
END;
$BODY$;

CREATE TRIGGER set_mimetype
  BEFORE INSERT OR UPDATE
  ON xplan_gml.bp_plan
  FOR EACH ROW
  EXECUTE PROCEDURE xplankonverter.set_mimetype();	

COMMIT;
