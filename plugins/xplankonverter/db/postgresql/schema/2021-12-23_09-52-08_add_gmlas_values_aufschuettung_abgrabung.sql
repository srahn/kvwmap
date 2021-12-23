BEGIN;
INSERT INTO
	xplankonverter.mappingtable_gmlas_to_gml (id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
VALUES
	(4430,TRUE,'fp_aufschuettung','aufschuettungsmaterial','character varying','fp_aufschuettung','aufschuettungsmaterial','character varying',NULL,NULL,'gml.aufschuettungsmaterial AS aufschuettungsmaterial') ON CONFLICT DO NOTHING;
INSERT INTO
	xplankonverter.mappingtable_gmlas_to_gml (id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,complex_type,regel)
VALUES
(4431,TRUE,'fp_abgrabung','abbaugut','character varying','fp_abgrabung','abbaugut','character varying',NULL,NULL,'gml.abbaugut AS abbaugut') ON CONFLICT DO NOTHING;
COMMIT;