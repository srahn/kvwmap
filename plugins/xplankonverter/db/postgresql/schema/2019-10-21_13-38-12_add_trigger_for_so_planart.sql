BEGIN;

CREATE OR REPLACE FUNCTION xplankonverter.set_so_planart()
  RETURNS trigger AS
$BODY$
	BEGIN
		IF ((TG_OP = 'INSERT' AND NEW.planart is NOT NULL) OR (TG_OP = 'UPDATE' AND NEW.planart IS NOT NULL AND NEW.planart != OLD.planart)) THEN
			SELECT
				(pa.codespace, (p.planart).id, pa.value)::xplan_gml.so_planart
			INTO
				NEW.planart
			FROM
				xplan_gml.so_plan p INNER JOIN xplan_gml.so_planart pa ON (p.planart).id =  pa.id;
		END IF;
		RETURN NEW;
	END;
	$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION xplankonverter.set_so_planart()
  OWNER TO kvwmap;

-- Trigger: set_so_planart on xplan_gml.so_plan

-- DROP TRIGGER set_so_planart ON xplan_gml.so_plan;

CREATE TRIGGER set_so_planart
  BEFORE INSERT OR UPDATE
  ON xplan_gml.so_plan
  FOR EACH ROW
  EXECUTE PROCEDURE xplankonverter.set_so_planart();
  
-- Function: xplankonverter.set_so_planart()

-- DROP FUNCTION xplankonverter.set_so_planart();

COMMIT;
