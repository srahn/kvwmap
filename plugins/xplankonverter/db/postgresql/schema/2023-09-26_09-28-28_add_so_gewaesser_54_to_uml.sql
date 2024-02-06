BEGIN;
-- SO_Gewaeser et al already exists in xplan_gml but not in xplan_uml-schema

INSERT INTO
	xplankonverter.mappingtable_gmlas_to_gml (feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,codespace,regel)
VALUES
	(true,'so_gewaesser','nummer','character varying','so_gewaesser','nummer','character varying',NULL,'gmlas.nummer AS nummer'),
	(true,'so_gewaesser','artderfestlegung','character varying','so_gewaesser','artderfestlegung','so_klassifizgewaesser',NULL,'gmlas.artderfestlegung::xplan_gml.so_klassifizgewaesser AS artderfestlegung'),
	(true,'so_gewaesser','detailartderfestlegung','character varying','so_gewaesser','detailartderfestlegung','so_detailklassifizgewaesser','detailartderfestlegung_codespace','(gmlas.detailartderfestlegung_codespace,gmlas.detailartderfestlegung,NULL)::xplan_gml.so_detailklassifizgewaesser AS detailartderfestlegung'),
	(true,'so_gewaesser','ebene','integer','so_gewaesser','ebene','integer',NULL,'gmlas.ebene AS ebene'),
	(true,'so_gewaesser','flaechenschluss','boolean','so_gewaesser','flaechenschluss','boolean',NULL,'gmlas.flaechenschluss AS flaechenschluss'),
	(true,'so_gewaesser','flussrichtung','boolean','so_gewaesser','flussrichtung','boolean',NULL,'gmlas.flussrichtung AS flussrichtung'),
	(true,'so_gewaesser','gehoertzubereich_href','character varying','so_gewaesser','gehoertzubereich','text',NULL,'CASE WHEN gmlas.gehoertzubereich_href LIKE ''#GML_%'' OR gmlas.gehoertzubereich_href LIKE ''#gml_%'' OR gmlas.gehoertzubereich_href LIKE ''#Gml_%'' THEN substring(gmlas.gehoertzubereich_href, 6) ELSE gmlas.gehoertzubereich_href END AS gehoertzubereich'),
	(true,'so_gewaesser','gesetzlichegrundlage','character varying','so_gewaesser','gesetzlichegrundlage','xp_gesetzlichegrundlage','gesetzlichegrundlage_codespace','(gmlas.gesetzlichegrundlage_codespace,gmlas.gesetzlichegrundlage,NULL)::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
	(true,'so_gewaesser','gliederung1','character varying','so_gewaesser','gliederung1','character varying',NULL,'gmlas.gliederung1 AS gliederung1'),
	(true,'so_gewaesser','gliederung2','character varying','so_gewaesser','gliederung2','character varying',NULL,'gmlas.gliederung2 AS gliederung2'),
	(true,'so_gewaesser','id','character varying','so_gewaesser','gml_id','uuid',NULL,'CASE WHEN gmlas.id LIKE ''GML_%'' OR gmlas.id LIKE ''gml_%'' OR gmlas.id LIKE ''Gml_%'' THEN substring(gmlas.id, 5)::uuid ELSE gmlas.id::uuid END AS gml_id'),
	(true,'so_gewaesser','istnatuerlichesuberschwemmungsgebiet','boolean','so_gewaesser','istnatuerlichesuberschwemmungsgebiet','boolean',NULL,'gmlas.istnatuerlichesuberschwemmungsgebiet AS istnatuerlichesuberschwemmungsgebiet'),
	(true,'so_gewaesser','xplan_name','character varying','so_gewaesser','name','character varying',NULL,'gmlas.xplan_name AS name'),
	(true,'so_gewaesser','nordwinkel','double precision','so_gewaesser','nordwinkel','double precision',NULL,'gmlas.nordwinkel AS nordwinkel'),
	(true,'so_gewaesser','nummer','character varying','so_gewaesser','nummer','character varying',NULL,'gmlas.nummer AS nummer'),
	(true,'so_gewaesser','position','geometry','so_gewaesser','position','geometry',NULL,'gmlas.position AS position'),
	(true,'so_gewaesser','rechtscharakter','character varying','so_gewaesser','rechtscharakter','so_rechtscharakter',NULL,'gmlas.rechtscharakter::xplan_gml.so_rechtscharakter AS rechtscharakter'),
	(true,'so_gewaesser','rechtsstand','character varying','so_gewaesser','rechtsstand','xp_rechtsstand',NULL,'gmlas.rechtsstand::xplan_gml.xp_rechtsstand AS rechtsstand'),
	(true,'so_gewaesser','sonstrechtscharakter','character varying','so_gewaesser','sonstrechtscharakter','so_sonstrechtscharakter','sonstrechtscharakter_codespace','(gmlas.sonstrechtscharakter_codespace,gmlas.sonstrechtscharakter,NULL)::xplan_gml.so_sonstrechtscharakter AS sonstrechtscharakter'),
	(true,'so_gewaesser','text','character varying','so_gewaesser','text','character varying',NULL,'gmlas.text AS text'),
	(true,'so_gewaesser','uuid','character varying','so_gewaesser','uuid','character varying',NULL,'gmlas.uuid AS uuid'),
	(true,'so_gewaesser','aufschrift','character varying','so_gewaesser','aufschrift','character varying',NULL,'gmlas.aufschrift AS aufschrift');

INSERT INTO xplan_uml.uml_classes(
	xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isActive", package_id, model_id, created_at, updated_at, "isAbstract", id, stereotype_id, general_id)
	VALUES
	('EAID_51242DCE_6D5D_3302_D66D_3CC7DC42352D', 'SO_Gewaesser', 'public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 413, 'EAID_6FDC92A7_3718_42bf_9B7C_11397E273688' , '-1'),
	('EAID_6323ADBE_6D5D_3532_D53D_3DD6DC31253B', 'SO_KlassifizGewaesser','public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 414,'EAID_7C09BC56_677A_591a_9FDE_6816BD7DCDD2', '-1'),
	('EAID_61242ABC_3B3A_6501_C33D_3DD3DD51251C', 'SO_DetailKlassifizGewaesser', 'public', FALSE, FALSE, FALSE, NULL, 41, NULL, now()::timestamp, NULL, FALSE, 415,'EAID_D85F3564_2B65_42f2_B5F2_3BA348652443', '-1');



INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECD_6D9D_3602_B57D_3DB7BB43042C', 'artDerFestlegung',413,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_6323ADBE_6D5D_3532_D53D_3DD6DC31253B','EAID_354F23B2_B87C_4221_BD8D_AADCC64EB8C2','EAID_354F23B0_B87D_3331_BB5C_CFCDE32EA8D3','0','1','EAID_3B78ED22_D3C2_8a33_8639_3792DC235237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECD_6D9D_3602_B57D_3DB7BB43042C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61242ECE_5D9D_2602_B57D_3DB7BB43042B', 'detailArtDerFestlegung',413,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_61242ABC_3B3A_6501_C33D_3DD3DD51251C','EAID_354F23B2_B87C_4221_BD8D_AADCC64EB8C2','EAID_354F23B0_B37D_1331_BC5C_CDDCE32EA7D2','0','1','EAID_3B78ED22_D3C2_8a33_8639_3792DC235237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61242ECE_5D9D_2602_B57D_3DB7BB43042B'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61232ECC_5C8D_2403_D56C_3DD7CB64333C', 'name',413,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_354F23B2_C77D_4324_CD8D_CDDAA4ACC6C2','EAID_354F23B3_C38D_1831_BC5C_DCCDEEEAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_5743DD234237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61232ECC_5C8D_2403_D56C_3DD7CB64333C'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_61232ECC_5C8D_2403_A56C_3CC6CB64443D', 'nummer',413,'public','','changeable','instance','unordered',now()::timestamp,'','EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14','EAID_354F23B2_C77D_4324_CD8D_CDDAA4ACC6C2','EAID_354F23B3_C38D_1831_BC5C_DCCDEEEAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_5743DD234237',''
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_61232ECC_5C8D_2403_A56C_3CC6CB64443D'
	);


-- SO_KlassifizEnums
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123EDCC_4C4D_3442_C55C_3DB7BB65422D', 'Gewaesser',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_444F23B2_C77D_4324_CD4D_CDCAA3BCC6B2','EAID_354F23B3_C38D_2832_BC4C_DCBDBABAA3D2','0','1','EAID_4B782D32_C2C3_8a23_7649_6743DD334231','1000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123EDCC_4C4D_3442_C55C_3DB7BB65422D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C3B_4472_A55A_2DC7BB64543A', 'Gewaesser1Ordnung',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_144F23B2_C77D_4324_CD4D_CDCAA3BDC6C1','EAID_354F23B3_C37D_3842_CC4C_DCBCBEBBA3A2','0','1','EAID_3C782D32_C2C3_8a22_7649_6743AD334312','10000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'AID_6123ECCC_3C3B_4472_A55A_2DC7BB64543A'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6323ECCC_3C4D_1742_C55C_2AB5DB64343D', 'Gewaesser2Ordnung',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_244F23B2_C72C_2226_CD4D_CDCAA3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAA3F2','0','1','EAID_4B782D32_C2C3_8a23_6649_6743CD334223','10001'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6323ECCC_3C4D_1742_C55C_2AB5DB64343D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_2D4D_1743_C55C_2AB6DB64343D', 'Gewaesser3Ordnung',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_244F23B2_C72C_2226_CD4D_CDCAA3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAA3F2','0','1','EAID_4B782D32_C2C3_8a23_6649_6743CD334224','10002'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_2D4D_1743_C55C_2AB6DB64343D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_C24D_1743_C55C_2BB7CB64343D', 'StehendesGewaesser',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_244F23B2_C72C_2226_CD4D_CDCAA3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAA3F2','0','1','EAID_4B782D32_C2C3_8a23_6649_6743CD334225','10003'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_C24D_1743_C55C_2BB7CB64343D'
	);
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6321ECCC_3C4D_1743_D55C_2AB7CB64343D', 'Hafen',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_244F23B2_C72C_2226_CD4D_CDCAA3BBC1A3','EAID_354C23F3_C38D_1832_BC4C_DCBDBEBAA3F2','0','1','EAID_4B782D32_C2C3_8a23_6649_6743CD334226','2000'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6321ECCC_3C4D_1743_D55C_2AB7CB64343D'
	);
	
INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,"ownerSpace",changeability,"targetScope",ordering,created_at,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_6123ECCC_3C4D_2244_C55C_2CBA7454453D', 'Sonstiges',413,'public','','changeable','instance','unordered',now()::timestamp,'','eaxmiid0','EAID_444F23B2_C7CD_3424_CD4D_BDCBB2BCC6C4','EAID_354C23B3_C38D_4832_CC4C_DCB1B1BA1312','0','1','EAID_4B722C32_C2C3_8a23_7649_6113DC114231','9999'
WHERE 
	NOT EXISTS (
		SELECT xmi_id FROM xplan_uml.uml_attributes WHERE xmi_id = 'EAID_6123ECCC_3C4D_2244_C55C_2CBA7454453D'
	);
	
INSERT INTO xplan_uml.class_generalizations (xmi_id,name,"isSpecification",package_id,parent_id,child_id,inheritance_order)
VALUES
('EAID_C1419ADC_A1CE_45eb_816C_D5201B5CED14','<undefined>',false,37,'EAID_39AFD230_9897_490b_9DCA_89E2D8CFD7D1','EAID_51242DCE_6D5D_3302_D66D_3CC7DC42352D',NULL);
COMMIT;