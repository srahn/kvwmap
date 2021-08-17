BEGIN;

UPDATE xplankonverter.validierungen
SET functionsargumente = ARRAY['(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND (detailArtDerFestlegung IS NOT NULL) = FALSE)']
WHERE functionsargumente = ARRAY['(artDerFestlegung IS NULL AND detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL))']
AND konformitaet_nummer IN ('6.2.7.1', '6.2.9.1', '6.2.8.1')
AND msg_correcture = 'Wenn das Attribut detailArtDerFestlegung belegt ist, muss auch das Attribut artDerFestlegung belegt sein.'
AND name = 'Konsistenz der Attribute artDerFestlegung und detailArtDerFestlegung';

UPDATE xplankonverter.validierungen
SET functionsargumente = ARRAY['(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)']
WHERE functionsargumente =
ARRAY['(detailArtDerFestlegung IS NULL ) OR (artDerFestlegung IS NOT NULL AND detailArtDerFestlegung IS NOT NULL)']
AND konformitaet_nummer IN ('6.2.1.1','6.2.2.1','6.2.3.1','6.2.4.1','6.2.5.2','6.2.6.1','6.3.1.1')
AND msg_correcture = 'Wenn das Attribut detailArtDerFestlegung belegt ist muss auch das Attribut artDerFestlegung belegt sein.'
AND name = 'Konsistenz der Attribute artDerFestlegung und detailArtDerFestlegung';


COMMIT;
