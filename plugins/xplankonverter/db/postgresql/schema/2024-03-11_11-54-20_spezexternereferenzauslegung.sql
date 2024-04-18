BEGIN;
	-- Funktion zur Konvertierung von xplan_gml.xp_spezexternereferenz nach xplankonverter.xp_spezexternereferenzauslegung
	CREATE OR REPLACE FUNCTION xplankonverter.to_spezexternereferenzauslegung(
		externereferenz xplan_gml.xp_spezexternereferenz[],
		nurzurauslegung boolean
	)
	RETURNS xplankonverter.xp_spezexternereferenzauslegung[]
	LANGUAGE 'plpgsql'
	COST 100
	VOLATILE PARALLEL UNSAFE
	AS $BODY$
	DECLARE
		_output xplankonverter.xp_spezexternereferenzauslegung[];
		_sql text;
	BEGIN
		SELECT
			array_agg(
				(
					(e_sub).georefurl,
					(e_sub).georefmimetype,
					(e_sub).art,
					(e_sub).informationssystemurl,
					(e_sub).referenzname,
					(e_sub).referenzurl,
					(e_sub).referenzmimetype,
					(e_sub).beschreibung,
					(e_sub).datum,
					(e_sub).typ,
					nurzurauslegung
				)::xplankonverter.xp_spezexternereferenzauslegung
			)::xplankonverter.xp_spezexternereferenzauslegung[] output
		FROM
			(
				SELECT
					unnest(externereferenz) AS e_sub
			) foo
			INTO _output;
		--RAISE NOTICE 'output: %', _output;
		RETURN _output;
	END
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.to_spezexternereferenzauslegung(xplan_gml.xp_spezexternereferenz[], boolean)
    IS 'Wandelt ein Typ xplan_gml.xp_spezexternereferenz[] in xplankonverter.xp_spezexternereferenzauslegung[] um in dem an alle Arrayelemente das Attribut nurzurauslegung angehängt wird. Der Wert der für alle gesetzt werden soll, kann im gleichnamigen Parameter übergeben werden.';

	CREATE OR REPLACE FUNCTION xplankonverter.to_spezexternereferenz(
		externereferenz_mit_nurzurauslegung xplankonverter.xp_spezexternereferenzauslegung
	)
	RETURNS xplan_gml.xp_spezexternereferenz
	LANGUAGE 'sql'
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		SELECT
			row(
			(externereferenz_mit_nurzurauslegung).georefurl,
			(externereferenz_mit_nurzurauslegung).georefmimetype,
			(externereferenz_mit_nurzurauslegung).art,
			(externereferenz_mit_nurzurauslegung).informationssystemurl,
			(externereferenz_mit_nurzurauslegung).referenzname,
			(externereferenz_mit_nurzurauslegung).referenzurl,
			(externereferenz_mit_nurzurauslegung).referenzmimetype,
			(externereferenz_mit_nurzurauslegung).beschreibung,
			(externereferenz_mit_nurzurauslegung).datum,
			(externereferenz_mit_nurzurauslegung).typ
		)::text::xplan_gml.xp_spezexternereferenz
	$BODY$;
	COMMENT ON FUNCTION xplankonverter.to_spezexternereferenz(xplankonverter.xp_spezexternereferenzauslegung)
    IS 'Wandelt den gegebenen Wert vom Typ xplankonverter.spezexternereferenzauslegung in den Typ xplan_gml.spezexternereferenz um.';

	-- Erweiterung der Funktion set_mimetype um nurzurauslegung
	CREATE OR REPLACE FUNCTION xplankonverter.set_mimetype()
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
						(g).typ,
						(g).nurzurauslegung
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

  -- Neuer Typ xplankonverter.xp_spezexternereferenzauslegung
	DO $$
	BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'xp_spezexternereferenzauslegung') THEN
      CREATE TYPE xplankonverter.xp_spezexternereferenzauslegung AS (
				georefurl character varying,
				georefmimetype xplan_gml.xp_mimetypes,
				art xplan_gml.xp_externereferenzart,
				informationssystemurl character varying,
				referenzname character varying,
				referenzurl character varying,
				referenzmimetype xplan_gml.xp_mimetypes,
				beschreibung character varying,
				datum date,
				typ xplan_gml.xp_externereferenztyp,
				nurzurauslegung boolean
			);

			ALTER TABLE xplan_gml.xp_plan ALTER column externereferenz TYPE xplankonverter.xp_spezexternereferenzauslegung[] USING xplankonverter.to_spezexternereferenzauslegung(externereferenz, false);
    END IF;
	END$$;

	CREATE OR REPLACE FUNCTION xplankonverter.filter_nurzurauslegung(
		auslegungsstartdatum date[],
		auslegungsenddatum date[],
		externereferenzen_mit_nurzurauslegung xplankonverter.xp_spezexternereferenzauslegung[]
	)
	RETURNS xplan_gml.xp_spezexternereferenz[]
	LANGUAGE plpgsql
	COST 100
	STABLE PARALLEL UNSAFE
	AS $BODY$
		DECLARE
			_sql text; 
			_result xplan_gml.xp_spezexternereferenz[];
			i integer = 0;
			n integer = array_length(externereferenzen_mit_nurzurauslegung, 1);
			today date = (now() at time zone 'Europe/Berlin')::date;
			is_in_date_range boolean = false;
		BEGIN
			FOR i IN 1..n LOOP
				is_in_date_range = (today BETWEEN auslegungsstartdatum[i] AND auslegungsenddatum[i]);
				EXIT WHEN is_in_date_range;
			END LOOP;
			
			SELECT
				ARRAY_AGG(xplankonverter.to_spezexternereferenz(e))
			FROM
				(
					SELECT
						unnest(externereferenzen_mit_nurzurauslegung) AS e
				) foo
			WHERE
				(e).nurzurauslegung IS NULL OR
				NOT (e).nurzurauslegung OR
				(
				(e).nurzurauslegung AND
				is_in_date_range
				)
			INTO _result;
			RETURN _result;
		END;
	$BODY$;

COMMIT;