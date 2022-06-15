BEGIN;

	INSERT INTO xplankonverter.validierungen(
		name,
		beschreibung,
		functionsname,
		msg_success,
		msg_warning,
		msg_error,
		msg_correcture,
		konformitaet_nummer,
		konformitaet_version_von,
		functionsargumente
	)
	VALUES (
		'Planname vorhanden',
		'Der Plan muss einen Namen haben. Das Attribut name im FeatureType XP_Plan ist laut UML-Modell ein Pflichtattribut vom Typ CharacterString.',
		'plan_attribute_has_value',
		'Der Plan hat einen Namen.',
		NULL,
		'Für den Plan wurde keine Name angegeben.',
		'Vergeben Sie einen aussagekräftigen Namen für den Plan.',
		NULL,
		'4.1',
		ARRAY['name IS NOT NULL', 'name != ''''']
	);

	INSERT INTO xplankonverter.validierungen(
		name,
		beschreibung,
		functionsname,
		msg_success,
		msg_warning,
		msg_error,
		msg_correcture,
		konformitaet_nummer,
		konformitaet_version_von,
		functionsargumente
	)
	VALUES (
		'Räumlicher Geltungsbereich vorhanden',
		'Der Plan muss einen räumlichen Geltungsbereich haben. Das Attribut raeumlicherGeltungsbereich im FeatureType XP_Plan ist laut UML-Modell ein Pflichtattribut vom Typ XP_Flaechengeometrie.',
		'plan_attribute_has_value',
		'Der Plan hat einen räumlichen Geltungsbereich.',
		NULL,
		'Für den Plan wurde kein räumlicher Geltungsbereich angegeben.',
		'Vergeben Sie einen räumlichen Geltungsbereich für den Plan an.',
		NULL,
		'4.1',
		ARRAY['raeumlichergeltungsbereich IS NOT NULL', 'NOT ST_IsEmpty(raeumlichergeltungsbereich)', 'ST_Area(raeumlichergeltungsbereich) > 0', 'lower(geometrytype(raeumlichergeltungsbereich)) IN (''polygon'', ''multipolygon'')']
	);

COMMIT;
