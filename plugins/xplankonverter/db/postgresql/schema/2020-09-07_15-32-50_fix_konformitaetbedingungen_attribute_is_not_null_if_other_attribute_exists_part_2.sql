BEGIN;

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteDachform IS NULL ) OR (dachform IS NOT NULL AND detaillierteDachform IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.3.1.2' AND konformitaet_version_von = '4.1';


COMMIT;
