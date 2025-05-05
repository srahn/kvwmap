BEGIN;

	CREATE TABLE xplankonverter.gebietseinheiten (
		land character varying,
		kreis character varying,
		amt character varying,
		gemeinde character varying,
		ortsteil character varying,
		rs character(12),
		ags character(8),
		otnr character(4),
		gvb_schl integer
	)
	WITH (OIDS=TRUE);

	CREATE OR REPLACE FUNCTION xplankonverter.set_gemeinde_rs()
	RETURNS trigger AS
	$BODY$
	BEGIN
		IF ((TG_OP = 'INSERT' AND NEW.gemeinde is NOT NULL) OR (TG_OP = 'UPDATE' AND NEW.gemeinde IS NOT NULL AND NEW.gemeinde != OLD.gemeinde)) THEN
			SELECT
				ARRAY_AGG(
					ROW(
						(SELECT DISTINCT ags FROM xplankonverter.gebietseinheiten WHERE gemeinde = (g).gemeindename),
						(SELECT DISTINCT rs FROM xplankonverter.gebietseinheiten WHERE gemeinde = (g).gemeindename),
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

	CREATE TRIGGER set_gemeinde_rs
	BEFORE INSERT OR UPDATE
	ON xplan_gml.bp_plan
	FOR EACH ROW

	EXECUTE PROCEDURE xplankonverter.set_gemeinde_rs();
	CREATE TRIGGER set_gemeinde_rs
	BEFORE INSERT OR UPDATE
	ON xplan_gml.fp_plan
	FOR EACH ROW

	EXECUTE PROCEDURE xplankonverter.set_gemeinde_rs();
	CREATE TRIGGER set_gemeinde_rs
	BEFORE INSERT OR UPDATE
	ON xplan_gml.so_plan
	FOR EACH ROW
	EXECUTE PROCEDURE xplankonverter.set_gemeinde_rs();

COMMIT;
