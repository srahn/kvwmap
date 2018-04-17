BEGIN;
INSERT INTO
	xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, created_at, "isAbstract", stereotype_id, general_id)
VALUES
	--  ExterneReferenzTyp
	(
		'EAID_64A13069_294D_45e6_A9B1_E670CF8E05CF',
		'XP_ExterneReferenzTyp',
		'public',
		FALSE,
		FALSE,
		FALSE,
		2,
		current_timestamp,
		FALSE,
		'EAID_1BF22C4B_8804_4233_A920_271DF78D1D25',
		-1	
	),
	-- BP_EinfahrtTypen
	(
		'EAID_3B3C95EC_40F0_4862_8FF1_85C1CF25AB18',
		'BP_EinfahrtTypen',
		'public',
		FALSE,
		FALSE,
		FALSE,
		17,
		current_timestamp,
		FALSE,
		'EAID_1BF22C4B_8804_4233_A920_271DF78D1D25',
		-1	
	),
	-- BP_ZweckbestimmungenTMF
	(	'EAID_BB93E083_53A7_481c_9B25_A8FA4F1F7ACC',
		'BP_ZweckbestimmungenTMF',
		'public',
		FALSE,
		FALSE,
		FALSE,
		15,
		current_timestamp,
		FALSE,
		'EAID_1BF22C4B_8804_4233_A920_271DF78D1D25',
		-1
	)
;
-- Types
CREATE TYPE xplan_gml.bp_einfahrttypen AS ENUM
	(
		'1000',
		'2000',
		'3000'
	)
;
ALTER TYPE xplan_gml.bp_einfahrttypen
	OWNER TO kvwmap;
	
CREATE TYPE xplan_gml.bp_zweckbestimmungentmf AS ENUM
	(
		'1000',
		'2000',
		'3000'
	)
;
ALTER TYPE xplan_gml.bp_zweckbestimmungentmf
	OWNER TO kvwmap;
   
-- Enums
CREATE TABLE xplan_gml.enum_bp_einfahrttypen
	(
		wert integer NOT NULL,
		abkuerzung character varying,
		beschreibung character varying,
		CONSTRAINT enum_bp_einfahrttypen_pkey PRIMARY KEY (wert)
	)
	WITH (OIDS = TRUE);
ALTER TABLE xplan_gml.enum_bp_einfahrttypen
	OWNER TO kvwmap;
COMMENT ON TABLE xplan_gml.enum_bp_einfahrttypen
	IS 'Alias: "enum_BP_EinfahrtTypen"';
INSERT INTO 
	xplan_gml.enum_bp_einfahrttypen (wert, abkuerzung, beschreibung)
VALUES
	(
		1000,
		'Einfahrt',
		'Nur Einfahrt möglich'
	),
	(
		2000,
		'Ausfahrt',
		'Nur Ausfahrt möglich'
	),
	(
		3000,
		'EinAusfahrt',
		'Ein- und Ausfahrt möglich'
	)
;
CREATE TABLE xplan_gml.enum_bp_zweckbestimmungentmf
	(
		wert integer NOT NULL,
		abkuerzung character varying,
		beschreibung character varying,
		CONSTRAINT enum_bp_zweckbestimmungentmf_pkey PRIMARY KEY (wert)
	)
	WITH (OIDS = TRUE);
ALTER TABLE xplan_gml.enum_bp_zweckbestimmungentmf
	OWNER TO kvwmap;
COMMENT ON TABLE xplan_gml.enum_bp_zweckbestimmungentmf
	IS 'Alias: "enum_BP_ZweckbestimmungenTMF"';
INSERT INTO 
	xplan_gml.enum_bp_zweckbestimmungentmf (wert, abkuerzung, beschreibung)
VALUES
	(
		1000,
		'Luftreinhaltung',
		'Gebiete, in denen zum Schutz vor schädlichen Umwelteinwirkungen im Sinne des Bundes-Immissionsschutzgesetzes bestimmte Luft-verunreinigende Stoffe nicht oder nur beschränkt verwendet werden dürfen (§9, Abs. 1, Nr. 23a BauGB).'
	),
	(
		2000,
		'NutzungErneuerbarerEnergien',
		'Gebiete in denen bei der Errichtung von Gebäuden bestimmte bauliche Maßnahmen für den Einsatz erneuerbarer Energien wie insbesondere Solarenergie getroffen werden müssen (§9, Abs. 1, Nr. 23b BauGB).'
	),
	(
		3000,
		'MinderungStoerfallfolgen',
		'Gebiete, in denen bei der Errichtung von nach Art, Maß oder Nutzungsintensität zu bestimmenden Gebäuden oder sonstigen baulichen Anlagen in der Nachbarschaft von Betriebsbereichen nach § 3 Absatz 5a des Bundes-Immissionsschutzgesetzes bestimmte bauliche und sonstige technische Maßnahmen, die der Vermeidung oder Minderung der Folgen von Störfällen dienen, getroffen werden müssen (§9, Abs. 1, Nr. 23c BauGB).'
	)
;


-- Fix References
ALTER TABLE xplan_gml.bp_einfahrtpunkt
ALTER COLUMN typ TYPE xplan_gml.bp_einfahrttypen USING NULL;
	
ALTER TABLE xplan_gml.bp_einfahrtsbereichlinie
ALTER COLUMN typ  TYPE xplan_gml.bp_einfahrttypen USING NULL;
	
ALTER TABLE xplan_gml.bp_technischemassnahmenflaeche
ALTER COLUMN zweckbestimmung TYPE xplan_gml.bp_zweckbestimmungentmf USING NULL;

COMMIT;