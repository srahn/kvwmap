BEGIN;
UPDATE xplankonverter.validierungen
SET functionsargumente = ARRAY['zweckbestimmung IS NULL OR ARRAY_LENGTH(zweckbestimmung,1) <= ARRAY_LENGTH(detaillierteZweckbestimmung,1)']
WHERE konformitaet_nummer = '5.4.1.6' AND konformitaet_version_von = '4.1';
COMMIT;
