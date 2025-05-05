BEGIN;
-- Create conformity views

CREATE OR REPLACE VIEW xplankonverter.konformitaeten_der_konvertierungen
	AS
	SELECT DISTINCT c.name,
		r.konvertierung_id,
		k.nummer,
		k.version_von,
		k.version_bis,
		k.inhalt,
		k.bezeichnung
	FROM xplankonverter.regeln r
		JOIN xplankonverter.uml_class2konformitaeten c ON r.class_name::text = c.name::text
		JOIN xplankonverter.konformitaetsbedingungen k ON c.konformitaet_nummer::text = k.nummer::text AND c.konformitaet_version_von::text = k.version_von::text
	ORDER BY c.name, k.nummer;

ALTER TABLE xplankonverter.konformitaeten_der_konvertierungen
	OWNER TO kvwmap;

CREATE OR REPLACE VIEW xplankonverter.konformitaets_validierungen
	AS
	SELECT c2k.name AS class_name,
		k.nummer,
		k.version_von,
		k.version_bis,
		k.inhalt,
		k.bezeichnung,
		v.id,
		v.name,
		v.beschreibung,
		v.functionsname,
		v.msg_success,
		v.msg_warning,
		v.msg_error,
		v.msg_correcture,
		v.konformitaet_nummer,
		v.konformitaet_version_von,
		v.functionsargumente
	FROM xplankonverter.uml_class2konformitaeten c2k
		JOIN xplankonverter.konformitaetsbedingungen k ON c2k.konformitaet_nummer::text = k.nummer::text AND c2k.konformitaet_version_von::text = k.version_von::text
		JOIN xplankonverter.validierungen v ON k.nummer::text = v.konformitaet_nummer::text AND k.version_von::text = v.konformitaet_version_von::text;

ALTER TABLE xplankonverter.konformitaets_validierungen
	OWNER TO kvwmap;


CREATE OR REPLACE VIEW xplankonverter.uml_classes_has_konformitaeten
	AS
	SELECT DISTINCT uml_class2konformitaeten.name
	FROM xplankonverter.uml_class2konformitaeten
	ORDER BY uml_class2konformitaeten.name;

ALTER TABLE xplankonverter.uml_classes_has_konformitaeten
	OWNER TO kvwmap;

COMMIT;