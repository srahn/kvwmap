BEGIN;

	DROP TABLE xplankonverter.gebietseinheiten;

	CREATE TABLE xplankonverter.gebietseinheiten (
		id_amt integer,
		amt_name character varying(254),
		id_gmd integer,
		rs character(12),
		ags integer,
		gmd_name character varying(254),
		id_ot character varying,
		ot_name character varying,
		stelle_id integer
	)
	WITH (OIDS=TRUE);

	/*
	CREATE TABLE xplankonverter.gebietseinheiten AS
	SELECT
	  a.id_amt,
	  a.amt_name,
	  g.id_gmd,
	  o.rs,
	  g.id_gmd AS ags,
	  g.gmd_name,
	  o.nr AS id_ot,
	  o.ortsteil_wohnplatz AS ot_name,
	  a.id_amt AS stelle_id
	FROM
	  import.aemter_laiv a LEFT JOIN
	  import.gemeinden_laiv g ON a.id_amt = g.id_amt LEFT JOIN
	  import.ortsverzeichnis_laiv o ON g.id_gmd = o.gem_nr
	ORDER BY a.amt_name, g.gmd_name, o.ortsteil_wohnplatz
	*/

	CREATE INDEX stelle_id_idx ON xplankonverter.gebietseinheiten USING btree (stelle_id);

	CREATE OR REPLACE FUNCTION xplankonverter.set_gemeinde_rs()
	RETURNS trigger AS
	$BODY$
	BEGIN
		IF ((TG_OP = 'INSERT' AND NEW.gemeinde is NOT NULL) OR (TG_OP = 'UPDATE' AND NEW.gemeinde IS NOT NULL AND NEW.gemeinde != OLD.gemeinde)) THEN
			SELECT
				ARRAY_AGG(
					ROW(
						(SELECT DISTINCT ags FROM xplankonverter.gebietseinheiten g JOIN xplankonverter.konvertierungen k ON g.stelle_id = k.stelle_id WHERE gmd_name = (g).gemeindename AND k.id = NEW.konvertierung_id),
						(SELECT DISTINCT rs FROM xplankonverter.gebietseinheiten g JOIN xplankonverter.konvertierungen k ON g.stelle_id = k.stelle_id WHERE gmd_name = (g).gemeindename AND k.id = NEW.konvertierung_id),
						(g).gemeindename,
						(g).ortsteilname
					)
				)
			INTO
				NEW.gemeinde
			FROM
				(
					SELECT unnest(NEW.gemeinde) g
				) AS gem;
		END IF;
		RETURN NEW;
	END;
	$BODY$
		LANGUAGE plpgsql VOLATILE
	COST 100;

COMMIT;
