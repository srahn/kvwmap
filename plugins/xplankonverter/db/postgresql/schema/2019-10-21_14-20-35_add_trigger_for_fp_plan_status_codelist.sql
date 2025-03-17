BEGIN;

CREATE OR REPLACE FUNCTION xplankonverter.set_fp_plan_status()
  RETURNS trigger AS
$BODY$
	BEGIN
		IF ((TG_OP = 'INSERT' AND NEW.status is NOT NULL) OR (TG_OP = 'UPDATE' AND NEW.status IS NOT NULL AND NEW.status != OLD.status)) THEN
			SELECT
				(pa.codespace, (p.status).id, pa.value)::xplan_gml.fp_status
			INTO
				NEW.status
			FROM
				xplan_gml.fp_plan p INNER JOIN xplan_gml.fp_status pa ON (p.status).id =  pa.id;
		END IF;
		RETURN NEW;
	END;
	$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION xplankonverter.set_fp_plan_status()
  OWNER TO kvwmap;

-- Trigger: set_fp_plan_status on xplan_gml.fp_plan

-- DROP TRIGGER set_fp_plan_status ON xplan_gml.fp_plan;

CREATE TRIGGER set_fp_plan_status
  BEFORE INSERT OR UPDATE
  ON xplan_gml.fp_plan
  FOR EACH ROW
  EXECUTE PROCEDURE xplankonverter.set_fp_plan_status();
  
-- Function: xplankonverter.set_fp_plan_status()

-- DROP FUNCTION xplankonverter.set_fp_plan_status();

COMMIT;