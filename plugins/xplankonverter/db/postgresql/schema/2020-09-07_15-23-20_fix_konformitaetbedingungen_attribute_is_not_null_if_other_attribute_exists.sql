BEGIN;
UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteBedeutung IS NULL ) OR (bedeutung IS NOT NULL AND detaillierteBedeutung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '3.1.2.4' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(dientZurDarstellungVon IS NULL ) OR (art IS NOT NULL AND dientZurDarstellungVon IS NOT NULL)"}'
WHERE
konformitaet_nummer = '3.3.1.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteTechnVorkehrung IS NULL ) OR (technVorkehrung IS NOT NULL AND detaillierteTechnVorkehrung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.10.2.2'
AND konformitaet_version_von = '5.2';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.12.2.2'
AND konformitaet_version_von = '5.2';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.12.2.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.13.1.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(zweckbestimmung IS NULL AND detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.13.2.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"detaillierteDachform IS NULL ) OR (dachform IS NOT NULL AND detaillierteDachform IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.3.1.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteSondernutzung IS NULL ) OR (sondernutzung IS NOT NULL AND detaillierteSondernutzung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.5.1.6' AND konformitaet_version_von = '5.3';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(weitereMassnahme2 IS NULL ) OR (weitereMassnahme1 IS NOT NULL AND weitereMassnahme2 IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.9.2.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(weitereMassnahme2 IS NULL ) OR (weitereMassnahme1 IS NOT NULL AND weitereMassnahme2 IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.9.3.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(weitereMassnahme2 IS NULL ) OR (weitereMassnahme1 IS NOT NULL AND weitereMassnahme2 IS NOT NULL)"}'
WHERE
konformitaet_nummer = '4.9.5.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(dientZurDarstellungVon IS NULL ) OR (art IS NOT NULL AND dientZurDarstellungVon IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.3.1.5' AND konformitaet_version_von = '5.3';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailMassnahme IS NULL ) OR (massnahme IS NOT NULL AND detailMassnahme IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.4.3.1' AND konformitaet_version_von = '5.3';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.8.1.2' AND konformitaet_version_von = '5.3';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.8.1.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.9.1.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '5.9.2.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.1.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.2.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.3.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.4.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.5.2' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.6.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.8.1' AND konformitaet_version_von = '4.1';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.2.8.1' AND konformitaet_version_von = '5.2';

UPDATE xplankonverter.validierungen
SET functionsargumente = '{"(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)"}'
WHERE
konformitaet_nummer = '6.3.1.1' AND konformitaet_version_von = '4.1';

COMMIT;