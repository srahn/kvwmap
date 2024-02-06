BEGIN;
-- adds class fp_detailsondernutzung and attribut detailliertesondernutzung to fp_bebauungsflaeche from xplan 5.4 to db

SELECT setval('xplan_uml.uml_classes_id2_seq', (SELECT max(id) from xplan_uml.uml_classes));

INSERT INTO xplan_uml.uml_classes(
	xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isActive", package_id, model_id, created_at, updated_at, "isAbstract", stereotype_id, general_id)
	VALUES
	('EAID_B7CD9A85_F270_42de_91ED_82AADEB11E31', 'FP_DetailSondernutzung', 'public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444' , '-1');

SELECT setval('xplan_uml.uml_attributes_id_seq', (SELECT max(id) from xplan_uml.uml_attributes));

INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, changeability, "targetScope", ordering, datatype, classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_B7CD9A85_F270_42de_91ED_82AADEB11E34' AS xmi_id,
'detailliertesondernutzung' AS name,
(SELECT id FROM xplan_uml.uml_classes WHERE name LIKE 'FP_BebauungsFlaeche') AS uml_class_id,
'public' AS visibility,
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'boolean' AS datatype,
'EAID_B7CD9A85_F270_42de_91ED_82AADEB11E31' AS classifier,
'EAID_89F89282_5675_4041_BBE7_42073FC45713' AS multiplicity_id,
'EAID_65F43673_4674_321_ACE5_23164BD57422' AS multiplicity_range_id,
'0' AS multiplicity_range_lower,
'*' AS multiplicity_range_upper,
'EAID_C544DE58_5CC6_535f_825F_250292EF1C2C' AS initialvalue_id,
'' AS initialvalue_body;

CREATE TABLE IF NOT EXISTS xplan_gml.fp_detailsondernutzung(
  codespace text,
  id character varying NOT NULL,
  value text,
  CONSTRAINT fp_detailsondernutzung_pkey PRIMARY KEY (id)
);

COMMENT ON TABLE xplan_gml.fp_detailsondernutzung
  IS 'Alias: "FP_DetailSondernutzung", UML-Typ: Code Liste';

COMMENT ON COLUMN xplan_gml.fp_detailsondernutzung.codespace
  IS 'codeSpace  text ';

COMMENT ON COLUMN xplan_gml.fp_detailsondernutzung.id
  IS 'id  character varying ';

ALTER TABLE xplan_gml.fp_bebauungsflaeche ADD COLUMN detailliertesondernutzung xplan_gml.fp_detailsondernutzung[];

CREATE SEQUENCE IF NOT EXISTS xplankonverter.mappingtable_gmlas_to_gml_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    CACHE 1;

ALTER TABLE xplankonverter.mappingtable_gmlas_to_gml
  ALTER COLUMN id TYPE integer USING id::integer,
  ALTER COLUMN id SET NOT NULL,
  ALTER COLUMN id SET DEFAULT nextval('xplankonverter.mappingtable_gmlas_to_gml_id_seq'::regclass);

SELECT setval('xplankonverter.mappingtable_gmlas_to_gml_id_seq', (SELECT max(id) from xplankonverter.mappingtable_gmlas_to_gml));

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,regel)
VALUES
(true,'fp_bebauungsflaeche','detailliertesondernutzung','ARRAY character varying','fp_bebauungsflaeche','detailliertesondernutzung','fp_detailsondernutzung[]','NULLIF(detailliertesondernutzung, ARRAY[NULL]) END AS detailliertesondernutzung')

COMMIT;