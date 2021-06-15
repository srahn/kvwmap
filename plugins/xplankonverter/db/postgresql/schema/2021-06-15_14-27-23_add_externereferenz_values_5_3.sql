BEGIN;
-- fix xplan_externereferenz to version 5.3
-- type value currently must be added manually, as types cannot be altered through begin/commit block

-- Fix XPlan_UML issue xp_externereferenz class_id
UPDATE
	xplan_uml.uml_attributes
SET
	uml_class_id = 406
WHERE
	uml_class_id = 397
AND
	initialvalue_body IN
	('1000','1010','1020','1030','1040','1050','1060','1070','1080','1090','2000','2100','2200','2300','2400','2500','9998','9999')
AND
	name IN
('Beschreibung','Begruendung','Legende','Rechtsplan','Plangrundlage','Umweltbericht','Satzung','Karte',
 'Erlaeuterung','ZusammenfassendeErklaerung','Koordinatenliste','Grundstuecksverzeichnis','Pflanzliste',
 'Gruenordnungsplan','Erschliessungsvertrag','Durchfuehrungsvertrag','Rechtsverbindlich','Informell');
 
-- Add new values to XPlan_UML
-- Delete first to avoid potential duplication
DELETE FROM
	xplan_uml.uml_attributes
WHERE
	xmi_id IN
	(
		'EAID_35645118_1312_332d_91B3_19EA13EC04F2',
		'EAID_33604115_2214_321e_23A6_19EA13EA04F3',
		'EAID_33633114_3213_421d_73B6_19EA13EE04F1',
		'EAID_34602113_4314_421a_63A6_19EA13EE04F4',
		'EAID_33664112_5213_422d_53C6_19EA13ED04F1',
		'EAID_35646111_6314_412b_33A6_19EA13EE04F1',
		'EAID_33617112_7213_422d_73A6_19EA13DE04F3',
		'EAID_34608114_8314_422a_92D6_19EA13EE04F1'
	);

INSERT INTO
	xplan_uml.uml_attributes(
	xmi_id, name, model_id, uml_class_id, visibility, "isSpecification", "ownerSpace", changeability, "targetScope", ordering, created_at, updated_at, datatype, classifier, multiplicity_id, multiplicity_range_id, multiplicity_range_lower, multiplicity_range_upper, initialvalue_id, initialvalue_body)
VALUES
	('EAID_35645118_1312_332d_91B3_19EA13EC04F2', 'Verordnung', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_43543217_1322_332d_91B3_15EA23DB32F3', 'EAID_33343517_1322_232d_71B3_15EA23DB32D4', '1', '1', 'EAID_43543227_1321_232d_91D3_15EA43DB32F3', '1065'),
	('EAID_33604115_2214_321e_23A6_19EA13EA04F3', 'StaedtebaulicherVertrag', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_53543327_3221_232d_71D3_25EA73CC32C2', 'EAID_43543327_3121_222d_71D3_35EA73CC32D2', '1', '1', 'EAID_33543327_4221_832d_21D3_75EA73BA32D1', '2600'),
	('EAID_33633114_3213_421d_73B6_19EA13EE04F1', 'UmweltbezogeneStellungnahmen', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_23543427_4221_832d_23DC3_75EA73BA32D1', 'EAID_33533327_4221_832d_21C3_75EA73DA32D1', '1', '1', 'EAID_23735327_4221_334c_22C3_75EB78DA32D1', '2700'),
	('EAID_34602113_4314_421a_63A6_19EA13EE04F4', 'Beschluss', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_34602113_4314_421a_43A6_19EA23DD04F4', 'EAID_34606113_4314_421a_63A6_19EA13DE04F4', '1', '1', 'EAID_34602113_4414_421a_63A6_19EA13BE03F4', '2800'),
	('EAID_33664112_5213_422d_53C6_19EA13ED04F1', 'VorhabenUndErschliessungsplan', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_33654112_5213_422d_33C6_39EA13DE04F1', 'EAID_33564112_5213_522d_53C6_29EA13BD04F1', '1', '1', 'EAID_33464112_5213_322d_53C6_18EA13CD04F1', '2900'),
	('EAID_35646111_6314_412b_33A6_19EA13EE04F1', 'MetadatenPlan', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_35346121_6314_312b_13A5_44EA23EB04F1', 'EAID_35446121_6324_413b_33A6_29EA13EB14F1', '1', '1', 'EAID_35646111_6314_312c_33A6_19EA13CE32D1', '3000'),
	('EAID_33617112_7213_422d_73A6_19EA13DE04F3', 'Genehmigung', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_33617112_7213_522d_12A6_19EA13DE04F3', 'EAID_23517112_2213_422d_43A6_19EA13DE04F3', '1', '1', 'EAID_32717312_4213_427c_73A6_19EA13DE04F3', '4000'),
	('EAID_34608114_8314_422a_92D6_19EA13EE04F1', 'Bekanntmachung', NULL, 406, 'public', NULL, '', 'changeable', 'instance', 'unordered', NULL, NULL, 'eaxmiid0', '', 'EAID_54608124_8214_422a_92C6_19EA13EB24D1', 'EAID_24608114_8314_422a_42D3_19EA13AE04F1', '1', '1', 'EAID_24608314_8314_422b_92D6_19DA13EE23F1', '5000');

-- add enum_values (required to appear in form)
-- truncate to keep order
TRUNCATE
	xplan_gml.enum_xp_externereferenztyp;

INSERT INTO
	xplan_gml.enum_xp_externereferenztyp (wert,beschreibung)
VALUES
	(1000,'Beschreibung'),
	(1010,'Begruendung'),
	(1020,'Legende'),
	(1030,'Rechtsplan'),
	(1040,'Plangrundlage'),
	(1050,'Umweltbericht'),
	(1060,'Satzung'),
	(1065,'Elektronische Version des Verordnungstexts'),
	(1070,'Karte'),
	(1080,'Erlaeuterung'),
	(1090,'ZusammenfassendeErklaerung'),
	(2000,'Koordinatenliste'),
	(2100,'Grundstuecksverzeichnis'),
	(2200,'Pflanzliste'),
	(2300,'Gruenordnungsplan'),
	(2400,'Erschliessungsvertrag'),
	(2500,'Durchfuehrungsvertrag'),
	(2600,'Elektronische Version des städtebaulichen Vertrags'),
	(2700,'UmweltbezogeneStellungnahmen'),
	(2800,'Beschluss'),
	(2900,'VorhabenUndErschliessungsplan'),
	(3000,'MetadatenPlan'),
	(4000,'Genehmigung'),
	(5000,'Bekanntmachung'),
	(9998,'Rechtsverbindlich'),
	(9999,'Informell');

COMMIT;