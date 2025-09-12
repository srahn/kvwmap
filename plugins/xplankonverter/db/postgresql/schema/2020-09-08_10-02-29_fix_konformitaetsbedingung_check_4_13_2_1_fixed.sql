BEGIN;
UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE konformitaet_nummer = '4.13.2.1' AND konformitaet_version_von = '4.1';
COMMIT;