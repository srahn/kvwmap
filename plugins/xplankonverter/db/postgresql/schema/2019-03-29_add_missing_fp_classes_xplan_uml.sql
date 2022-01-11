BEGIN;
/*
*** This file adds the elements of the missing package FP_Ver- und Entsorgung to xplan_uml according to the xplan 5.12 XMI
*** In future xmi2db db-creation, the relevant package should be added to the db-creation process
*/
-- package
INSERT INTO xplan_uml.packages (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", "isAbstract", model_id, parent_package_id, id, stereotype_id)
VALUES(
    'EAPK_BA360862_4183_4e1b_B8D2_AD2D21823A13',
    'FP_Ver_und_Entsorgung',
    'public',
    NULL,
    TRUE,
    FALSE,
    FALSE,
    NULL,
    18,
    40,
    'EAID_7CFDF703_1CAE_4fab_A468_205ED7AED4AE'
);

-- classes
INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, "isAbstract", id, stereotype_id, general_id)
VALUES(
    'EAID_97A29C7E_8A7A_4759_A373_FFA74343C9DA',
    'FP_VerEntsorgung',
    'public',
    FALSE,
    FALSE,
    FALSE,
    40,
    FALSE,
    397,
    'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699',
    'EAID_CA28B4E5_271E_448d_B801_E073C7F8CD7D'
);
INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, "isAbstract", id, stereotype_id, general_id)
VALUES(
    'EAID_DFBA842E_E8D7_4764_8512_70AB2D1239B5',
    'FP_ZentralerVersorgungsbereich',
    'public',
    FALSE,
    FALSE,
    FALSE,
    40,
    FALSE,
    398,
    'EAID_6FDC92A7_2818_42bf_9B7D_10397E273699',
    'EAID_0E32C364_396F_4c2e_A817_CD889E05775B'
);
INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, "isAbstract", id, stereotype_id, general_id)
VALUES(
    'EAID_23853DEE_0C2B_4f09_B7A0_A14C92568F5B',
    'FP_DetailZweckbestVerEntsorgung',
    'public',
    FALSE,
    FALSE,
    FALSE,
    40,
    FALSE,
    399,
    'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444',
    '-1'
);
INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, "isAbstract", id, stereotype_id, general_id)
VALUES(
    'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444',
    'FP_ZentralerVersorgungsbereichAuspraegung',
    'public',
    FALSE,
    FALSE,
    FALSE,
    40,
    FALSE,
    400,
    'EAID_E95F3575_1A65_42f2_B5F2_2EA449652444',
    '-1'
);

-- class_generalizations
INSERT INTO xplan_uml.class_generalizations (xmi_id, name, "isSpecification", package_id, parent_id, child_id, id)
VALUES (
    'EAID_CA28B4E5_271E_448d_B801_E073C7F8CD7D',
    '<undefined>',
    FALSE,
    40,
    'EAID_F1A00694_7C71_405c_B1FA_920E4BD98E87',
    'EAID_97A29C7E_8A7A_4759_A373_FFA74343C9DA',
    207
);
INSERT INTO xplan_uml.class_generalizations (xmi_id, name, "isSpecification", package_id, parent_id, child_id, id)
VALUES (
    'EAID_0E32C364_396F_4c2e_A817_CD889E05775B',
    '<undefined>',
    FALSE,
    40,
    'EAID_7963CD0E_AD9D_45ba_A1D8_9F44404D4604',
    'EAID_DFBA842E_E8D7_4764_8512_70AB2D1239B5',
    208
);

-- uml_attributes
/*
-- FP_VerEntsorgung
-- detaillierteZweckbestimmung
--textlicheErgaenzung
--zugunstenVon
--zweckbestimmung

-- FP_ZentralerVersorgungsbereich
-- auspraegung
*/

INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, id, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES(
'EAID_D4658724_D4FA_4b1c_A6A5_EA3DF5DC0583',
'detaillierteZweckbestimmung',
397,
'public',
'',
'changeable',
'instance',
'ordered',
1969,
'',
'EAID_440DAFD1_440A_41f1_B3F6_91AA8FB0912B',
'EAID_3F30EC0D_A7E4_407d_9847_FD6EE6C25CB5',
'EAID_B27E65EE_7D85_4d73_8201_D4260506EFB6',
'0',
'*',
'EAID_5A4CC869_CC83_4057_8916_03D81BAE84AE',
''
);
INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, id, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES(
'EAID_76556DE0_A96F_42c5_AD30_C8A2005892FA',
'textlicheErgaenzung',
397,
'public',
'',
'changeable',
'instance',
'ordered',
1970,
'',
'EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14',
'EAID_17EF9C39_A24A_4289_91BD_872820EA720D',
'EAID_C78BC6EF_910C_472d_A70E_ABCC7C09EC79',
'0',
'1',
'EAID_A61130BD_DE6E_4aae_AAAD_091251882038',
''
);
INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, id, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES(
'EAID_4F4FA0EC_0BDA_4191_BD38_00FE7B5B0E06',
'zugunstenVon',
397,
'public',
'',
'changeable',
'instance',
'ordered',
1971,
'',
'EAID_AF7C81A6_B1C1_4469_A09F_B97989024A14',
'EAID_3C35C327_E55A_486b_BC99_8AD36EF474BC',
'EAID_3B6B29D8_550E_4173_BAD0_381124687151',
'0',
'1',
'EAID_9B7356B9_6B43_402d_A4E9_B1FB1F5BBB8D',
''
);
INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, id, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES(
'EAID_7BEABF44_FA9F_43f2_8131_7CDBC54DEB83',
'zweckbestimmung',
397,
'public',
'',
'changeable',
'instance',
'ordered',
1972,
'',
'EAID_397BE0DE_B42E_4ced_BB44_75C8DD23A7B1',
'EAID_A1675EA1_25E5_4b30_9FA5_BA673782362E',
'EAID_864D65FD_8974_4d84_B5AD_EFC89359906F',
'0',
'*',
'EAID_A1036922_F9DC_45d9_9E0A_D1D830B1EB9D',
''
);

INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, "ownerSpace", changeability, "targetScope", ordering, id, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES(
'EAID_71E319BE_7153_4210_A157_2E7C535A4F02',
'auspraegung',
398,
'public',
'',
'changeable',
'instance',
'ordered',
1973,
'',
'EAID_6713BAE4_5771_47b2_A6E9_4665E81DEF14',
'EAID_299E6EBB_61A6_4e0f_AC81_884839DF45D1',
'EAID_90F70815_D9B0_435c_BE6D_C4BB9C4CA67F',
'0',
'1',
'EAID_A2E607ED_2685_49aa_A007_A8F4CD8C06AC',
''
);

COMMIT;