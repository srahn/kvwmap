BEGIN;


ALTER TABLE
	xplan_gml.fp_objekt
ADD COLUMN
	vongenehmigungausgenommen BOOLEAN
;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (id,feature_class,o_table,o_column,o_data_type,t_table,t_column,t_data_type,regel)
VALUES
(4455,true,'fp_ausgleichsflaeche','vongenehmigungausgenommen','boolean','fp_ausgleichsflaeche','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4456,true,'fp_abgrabung','vongenehmigungausgenommen','boolean','fp_abgrabung','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4457,true,'fp_bodenschaetze','vongenehmigungausgenommen','boolean','fp_bodenschaetze','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4458,true,'fp_unverbindlichevormerkung','vongenehmigungausgenommen','boolean','fp_unverbindlichevormerkung','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4459,true,'fp_anpassungklimawandel','vongenehmigungausgenommen','boolean','fp_anpassungklimawandel','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4460,true,'fp_landwirtschaftsflaeche','vongenehmigungausgenommen','boolean','fp_landwirtschaftsflaeche','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4461,true,'fp_landwirtschaft','vongenehmigungausgenommen','boolean','fp_landwirtschaft','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4462,true,'fp_spielsportanlage','vongenehmigungausgenommen','boolean','fp_spielsportanlage','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4463,true,'fp_bebauungsflaeche','vongenehmigungausgenommen','boolean','fp_bebauungsflaeche','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4464,true,'fp_ueberlagerungsobjekt','vongenehmigungausgenommen','boolean','fp_ueberlagerungsobjekt','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4465,true,'fp_nutzungsbeschraenkungsflaeche','vongenehmigungausgenommen','boolean','fp_nutzungsbeschraenkungsflaeche','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4466,true,'fp_schutzpflegeentwicklung','vongenehmigungausgenommen','boolean','fp_schutzpflegeentwicklung','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4467,true,'fp_privilegiertesvorhaben','vongenehmigungausgenommen','boolean','fp_privilegiertesvorhaben','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4468,true,'fp_generischesobjekt','vongenehmigungausgenommen','boolean','fp_generischesobjekt','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4469,true,'fp_gemeinbedarf','vongenehmigungausgenommen','boolean','fp_gemeinbedarf','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4470,true,'fp_zentralerversorgungsbereich','vongenehmigungausgenommen','boolean','fp_zentralerversorgungsbereich','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4471,true,'fp_wasserwirtschaft','vongenehmigungausgenommen','boolean','fp_wasserwirtschaft','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4472,true,'fp_flaechenschlussobjekt','vongenehmigungausgenommen','boolean','fp_flaechenschlussobjekt','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4473,true,'fp_strassenverkehr','vongenehmigungausgenommen','boolean','fp_strassenverkehr','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4474,true,'fp_gewaesser','vongenehmigungausgenommen','boolean','fp_gewaesser','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4475,true,'fp_gruen','vongenehmigungausgenommen','boolean','fp_gruen','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4476,true,'fp_verentsorgung','vongenehmigungausgenommen','boolean','fp_verentsorgung','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4477,true,'fp_aufschuettung','vongenehmigungausgenommen','boolean','fp_aufschuettung','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4478,true,'fp_waldflaeche','vongenehmigungausgenommen','boolean','fp_waldflaeche','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4479,true,'fp_kennzeichnung','vongenehmigungausgenommen','boolean','fp_kennzeichnung','boolean','vongenehmigungausgenommen','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen'),
(4480,true,'fp_vorbehalteflaeche','vongenehmigungausgenommen','boolean','fp_vorbehalteflaeche','vongenehmigungausgenommen','boolean','gmlas.vongenehmigungausgenommen AS vongenehmigungausgenommen');

INSERT INTO xplan_uml.uml_attributes (xmi_id,name,uml_class_id,visibility,changeability,"targetScope",ordering,datatype,classifier,multiplicity_id,multiplicity_range_id,multiplicity_range_lower,multiplicity_range_upper,initialvalue_id,initialvalue_body)
SELECT
'EAID_572346D6_2C4E_4400_76DD_76D2EBBD4E4D' AS xmi_id,
'vonGenehmigungAusgenommen' AS name,
191 AS uml_class_id,
'public' AS visibility,
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'boolean' AS datatype,
'EAID_751057C8_1B7E_4000_89CD_67D1EBAD3E4D' AS classifier,
'EAID_89F89282_5675_4041_BBE7_42073FC45713' AS multiplicity_id,
'EAID_65F43673_4674_321_ACE5_23164BD57422' AS multiplicity_range_id,
'0' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_C544DE58_5CC6_535f_825F_250292EF1C2C' AS initialvalue_id,
'' AS initialvalue_body;



COMMIT;
