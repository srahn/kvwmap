BEGIN;
-- Function: xplankonverter.set_gemeinde_rs()

-- DROP FUNCTION xplankonverter.set_gemeinde_rs();

CREATE OR REPLACE FUNCTION xplankonverter.set_gemeinde_rs()
  RETURNS trigger AS
$BODY$
	BEGIN
	/* array_length of 0 returns null. not null check without array_length wont work on record of array of datatype */
		IF ((TG_OP = 'INSERT' AND array_length(NEW.gemeinde,1) IS NOT NULL) OR (TG_OP = 'UPDATE' AND array_length(NEW.gemeinde,1)IS NOT NULL)) THEN
			SELECT
				ARRAY_AGG(
					ROW(
						(SELECT DISTINCT ags FROM xplankonverter.gebietseinheiten g JOIN xplankonverter.konvertierungen k ON g.stelle_id = k.stelle_id WHERE gmd_name = (g).gemeindename AND k.id = NEW.konvertierung_id),
						(SELECT DISTINCT rs FROM xplankonverter.gebietseinheiten g JOIN xplankonverter.konvertierungen k ON g.stelle_id = k.stelle_id WHERE gmd_name = (g).gemeindename AND k.id = NEW.konvertierung_id),
						(g).gemeindename,
						(g).ortsteilname
					)::xplan_gml.xp_gemeinde
				)::xplan_gml.xp_gemeinde[]
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
ALTER FUNCTION xplankonverter.set_gemeinde_rs()
  OWNER TO kvwmap;


COMMIT;
