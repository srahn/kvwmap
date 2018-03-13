BEGIN;

ALTER TABLE xplan_gml.bp_flaechenobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.bp_baugebietsteilflaeche ALTER COLUMN vertikaledifferenzierung TYPE boolean USING vertikaledifferenzierung::boolean;
ALTER TABLE xplan_gml.bp_ueberbaubaregrundstuecksflaeche ALTER COLUMN vertikaledifferenzierung TYPE boolean USING vertikaledifferenzierung::boolean;
ALTER TABLE xplan_gml.bp_schutzpflegeentwicklungsflaeche ALTER COLUMN istausgleich TYPE boolean USING istausgleich::boolean;
ALTER TABLE xplan_gml.bp_geometrieobjekt ALTER COLUMN flussrichtung TYPE boolean USING flussrichtung::boolean;
ALTER TABLE xplan_gml.bp_geometrieobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.bp_anpflanzungbindungerhaltung ALTER COLUMN istausgleich TYPE boolean USING istausgleich::boolean;
ALTER TABLE xplan_gml.bp_schutzpflegeentwicklungsmassnahme ALTER COLUMN istausgleich TYPE boolean USING istausgleich::boolean;
ALTER TABLE xplan_gml.fp_flaechenobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.fp_geometrieobjekt ALTER COLUMN flussrichtung TYPE boolean USING flussrichtung::boolean;
ALTER TABLE xplan_gml.fp_geometrieobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.fp_schutzpflegeentwicklung ALTER COLUMN istausgleich TYPE boolean USING istausgleich::boolean;
ALTER TABLE xplan_gml.so_flaechenobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.so_geometrieobjekt ALTER COLUMN flussrichtung TYPE boolean USING flussrichtung::boolean;
ALTER TABLE xplan_gml.so_geometrieobjekt ALTER COLUMN flaechenschluss TYPE boolean USING flaechenschluss::boolean;
ALTER TABLE xplan_gml.so_bodenschutzrecht ALTER COLUMN istverdachtsflaeche TYPE boolean USING istverdachtsflaeche::boolean;
ALTER TABLE xplan_gml.so_wasserrecht ALTER COLUMN istnatuerlichesuberschwemmungsgebiet TYPE boolean USING istnatuerlichesuberschwemmungsgebiet::boolean;
ALTER TABLE xplan_gml.so_denkmalschutzrecht ALTER COLUMN weltkulturerbe TYPE boolean USING weltkulturerbe::boolean;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN durchfuehrungsvertrag TYPE boolean USING durchfuehrungsvertrag::boolean;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN staedtebaulichervertrag TYPE boolean USING staedtebaulichervertrag::boolean;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN veraenderungssperre TYPE boolean USING veraenderungssperre::boolean;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN gruenordnungsplan TYPE boolean USING gruenordnungsplan::boolean;
ALTER TABLE xplan_gml.bp_plan ALTER COLUMN erschliessungsvertrag TYPE boolean USING erschliessungsvertrag::boolean;

COMMIT;
