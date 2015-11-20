BEGIN;

CREATE INDEX gemeinden_gkz_idx
  ON bauleitplanung.gemeinden
  USING btree
  (gkz);

CREATE INDEX b_plan_gebiete_planid_idx
  ON bauleitplanung.b_plan_gebiete
  USING btree
  (plan_id);
	
CREATE INDEX b_plan_sondergebiete_planid_idx
  ON bauleitplanung.b_plan_sondergebiete
  USING btree
  (plan_id);
	
CREATE INDEX f_plan_gebiete_planid_idx
  ON bauleitplanung.f_plan_gebiete
  USING btree
  (plan_id);
	
CREATE INDEX f_plan_sondergebiete_planid_idx
  ON bauleitplanung.f_plan_sondergebiete
  USING btree
  (plan_id);


COMMIT;
