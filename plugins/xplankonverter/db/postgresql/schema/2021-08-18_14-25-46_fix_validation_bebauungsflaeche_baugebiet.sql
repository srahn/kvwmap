BEGIN;
DELETE FROM
	xplankonverter.uml_class2konformitaeten
WHERE
	name = 'FP_BebauungsFlaeche'
AND
	konformitaet_nummer IN (
		'6',
		'4.2.1',
		'4.2.4',
		'4.2.5',
		'4.2.6',
		'4.2.7',
		'4.2.8',
		'4.2.9',
		'4.2.10',
		'4',
		'5'
	);
DELETE FROM
	xplankonverter.uml_class2konformitaeten
WHERE
	name = 'BP_BaugebietsTeilFlaeche'
AND 
	konformitaet_nummer IN (
		'4',
		'5'
	);
	

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
AND konformitaet_nummer IN (
'4.12.2.2',
'4.12.2.2',
'4.13.1.1',
'4.13.2.1',
'5.9.1.1',
'5.9.2.1',
'5.8.1.2'
);

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'zweckbestimmung IS NULL OR ARRAY_LENGTH(zweckbestimmung,1) <= ARRAY_LENGTH(detaillierteZweckbestimmung,1)'
WHERE functionsargumente[1] = 'zweckbestimmung IS NULL OR ARRAY_LENGTH(zweckbestimmung <= ARRAY_LENGTH(detaillierteZweckbestimmung,1)'
AND konformitaet_nummer IN (
'5.5.2.3',
'4.5.14.4',
'4.7.2.3',
'4.8.1.3',
'4.8.2.3',
'4.8.4.1',
'5.4.2.3',
'5.7.1.6',
'5.5.3.6',
'5.5.4.1',
'5.5.1.3',
'4.5.13.3'
);

UPDATE
	xplankonverter.validierungen
SET
	functionsargumente[1] =
	'(detailartderfestlegung IS NULL ) OR (artderfestlegung IS NOT NULL AND (detailartderfestlegung IS NULL) = FALSE)'
WHERE
	functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
AND 
	konformitaet_nummer IN (
		'6.2.1.1',
		'6.2.4.1',
		'6.2.2.1',
		'6.2.3.1',
		'6.2.5.2',
		'6.2.6.1',
		'6.3.1.1'
	);

COMMIT;
