BEGIN;
INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, "isAbstract", stereotype_id, general_id)
SELECT 
    'EAID_64A13069_294D_45e6_A9B1_E670CF8E05CF' AS xmi_id,
    'XP_ExterneReferenzTyp' AS name,
    'public' AS visibility,
    'F' AS "isSpecification",
    'F' AS "isRoot",
    'F'AS "isLeaf",
    2 AS package_id,
    'F' AS "isAbstract",
    --id
    'EAID_8D09BC59_987A_481a_9FDE_6816AD7DCEF1' AS stereotype_id,
    '-1' AS general_id;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_DF830322_3AAA_447c_81C6_EB7E0147272A' AS xmi_id,
'Beschreibung' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_72A1EE87_AA3B_4a6a_9B11_7551C0ADA5DF' AS multiplicity_id,
'EAID_89E2FBC9_269F_48f9_8817_2629D42BB250' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_D0EF9E18_901D_4e39_A783_FED4A3327649' AS initialvalue_id,
'1000' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_146A8C28_2FF8_48ca_BC31_DEFA5FDC8E03' AS xmi_id,
'Begruendung' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_8B8BF2AE_AA85_4c11_8711_E6BAB8D6AC01' AS multiplicity_id,
'EAID_36DB7BFE_D041_4604_85F2_4F684801A52A' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_D85940C0_BBB9_4aaa_9EFB_8CA6D460A7B9' AS initialvalue_id,
'1010' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_8129764D_627B_4327_8172_EC9589404D3C' AS xmi_id,
'Legende' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_2427F354_5892_48b7_BF18_DB25AB452F66' AS multiplicity_id,
'EAID_95E4466C_ABD8_49b6_872A_4D934548B669' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_6D899834_F6EB_4baf_BA47_B1C430FEFE16' AS initialvalue_id,
'1020' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_C3642CF7_E1A7_436e_B4BC_36C7632D2210' AS xmi_id,
'Rechtsplan' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_213D538B_725F_4eab_A9E7_3E0E8C433A52' AS multiplicity_id,
'EAID_24407A30_C520_48f6_8760_AA77A761F84E' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_E20369AD_66A3_419d_BD73_F12F32B55532' AS initialvalue_id,
'1030' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_93ED2B45_B411_40a6_9BC7_7094E5D2439B' AS xmi_id,
'Plangrundlage' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_7E5C16F7_B969_4d62_9B90_A1752814BB58' AS multiplicity_id,
'EAID_9763E7F8_ABF7_4b40_ABC9_7EC88313B331' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_4A287BA5_9DA3_493e_8B4E_B805D8068742' AS initialvalue_id,
'1040' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_FAFFF4D7_C98D_4827_AD3A_C0B3A7F44D3D' AS xmi_id,
'Umweltbericht' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_094B0E1E_BCD2_4c78_A5DD_DAF56D4D98F0' AS multiplicity_id,
'EAID_2F9B534B_ED47_4c37_8644_E6A7A159AE6D' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_784498E7_CD3D_454c_9A8C_017539ABFCCA' AS initialvalue_id,
'1050' AS initialvalue_body;


INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_B6E52F46_B148_4e7d_9398_0C8426BF40C2' AS xmi_id,
'Satzung' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_AAE73BD1_2983_43cd_BBE4_DE5D0A238E88' AS multiplicity_id,
'EAID_8624AE08_E25A_4f47_8198_3F02147A9406' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_1A46E3BB_CB47_4f8f_96BC_B3AC66ADD49F' AS initialvalue_id,
'1060' AS initialvalue_body;
INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_912C8E2F_F6AA_4940_B77B_729382C1F4E1' AS xmi_id,
'Karte' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_D60EA896_C63D_40a5_94EF_09372070F3BF' AS multiplicity_id,
'EAID_EAABB059_5E74_4396_A3F3_05D804EBDAD9' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_F1FFAFC9_1605_47af_B4D1_AFCFDBD8CF87' AS initialvalue_id,
'1070' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_36695474_0637_4e25_80BB_883B3C8BDCF7' AS xmi_id,
'Erlaeuterung' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_92E4FC60_5DD1_4848_ABC0_D64C6D60A266' AS multiplicity_id,
'EAID_F6C23E53_9D6E_47b6_A80E_961BF3EC0FA6' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_91C3AD5A_7432_4d09_86FE_226636A51D59' AS initialvalue_id,
'1080' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_1BACEFA2_A7DC_4eda_8AD0_9A578A765C45' AS xmi_id,
'ZusammenfassendeErklaerung' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_A4223D26_6BD9_45b9_8A09_ECF1C4D73EAD' AS multiplicity_id,
'EAID_BBA82212_48CA_46c2_8645_4810C71DD35F' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_7105074F_A92E_4090_9211_12CECF0EA5BE' AS initialvalue_id,
'1090' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_A9B606F1_2D70_4c21_AC24_63C18F58C304' AS xmi_id,
'Koordinatenliste' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_A1383FA6_5025_4b52_81CE_ADC5F0E5A833' AS multiplicity_id,
'EAID_3485B644_D4CF_441b_AA92_EB01A7ED5428' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_48BFD406_D40C_4c25_B20C_2562D999E094' AS initialvalue_id,
'2000' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_51FBE50F_FB52_4af5_80B7_41D5EFDC50CD' AS xmi_id,
'Grundstuecksverzeichnis' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_786B5A0C_F0A5_4f04_B848_BEEDB0A5CAF0' AS multiplicity_id,
'EAID_762E9A5B_68B6_4a50_B6C5_49923894C0A9' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_1750892A_D7BA_4bd7_AA85_C2AA77AE3C3B' AS initialvalue_id,
'2100' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_5D37ADDB_2299_43f4_B7C4_B89A75D05206' AS xmi_id,
'Pflanzliste' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_3A3D3E56_85FE_4138_8A19_3149605F09BC' AS multiplicity_id,
'EAID_C69B50E2_4040_4583_920F_6B25891CC688' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_A11734E5_3BDA_4c4b_94B5_A38414987973' AS initialvalue_id,
'2200' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_593474BA_1162_49dc_9728_2114ED516969' AS xmi_id,
'Gruenordnungsplan' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_67F30D37_2F37_48c4_B719_56D78FB02A67' AS multiplicity_id,
'EAID_4A3A7630_BC1C_4d8f_93A9_9C07ABA7B265' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_D05BA449_401E_4cc9_864D_8A3431AC2100' AS initialvalue_id,
'2300' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_60B7AFC0_B401_4e48_A19A_CB2340C74A06' AS xmi_id,
'Erschliessungsvertrag' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_9AEB8C79_3314_499b_A182_F8BAC5FDB325' AS multiplicity_id,
'EAID_E8CA417C_D5D5_4c54_A58F_3EEF916DEB88' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_6E742502_C077_4ebe_A9CC_CC4E238AA5CB' AS initialvalue_id,
'2400' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_85605118_2214_442d_94A6_29EA23EF04F1' AS xmi_id,
'Durchfuehrungsvertrag' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_5B64B9FA_D61D_4851_80C8_484F7846CBD2' AS multiplicity_id,
'EAID_C55919A8_92FC_4a92_BE1F_384A5A273654' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_C35E2003_7805_4acd_957D_525454403915' AS initialvalue_id,
'2500' AS initialvalue_body;

INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_2E57636A_65ED_44c5_87DF_72680877CF24' AS xmi_id,
'Rechtsverbindlich' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_51A3D187_A779_4c07_96BA_F3FA580812B6' AS multiplicity_id,
'EAID_903267E3_4086_4449_84B5_4E9E2DB5D3EB' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_961F49BA_C9D9_4633_8DEB_9C5647734971' AS initialvalue_id,
'9998' AS initialvalue_body;


INSERT INTO xplan_uml.uml_attributes(xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
SELECT
'EAID_CD248841_5FF4_4881_8F5D_23BABEB17F95' AS xmi_id,
'Informell' AS name,
397 AS uml_class_id,
'public' AS visibility,
'' AS "ownerSpace",
'changeable' AS changeability,
'instance' AS "targetScope",
'unordered' AS ordering,
'eaxmiid0' AS datatype,
'' AS classifier,
'EAID_CEFAFC03_2D9C_48f4_93CE_A3C85CAB5B6D' AS multiplicity_id,
'EAID_E579810B_D3DB_44f6_B630_5383DB660AB9' AS multiplicity_range_id,
'1' AS multiplicity_range_lower,
'1' AS multiplicity_range_upper,
'EAID_3486EAA6_C2BC_4b50_8F39_D65D5935F9C9' AS initialvalue_id,
'9999' AS initialvalue_body;
COMMIT;