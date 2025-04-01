BEGIN;
UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(zweckbestimmung IS NULL AND detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(zweckbestimmung IS NULL AND detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL  AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL  AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL  AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL  AND (detaillierteZweckbestimmung IS NULL) = FALSE)'
WHERE functionsargumente[1] = '(detaillierteZweckbestimmung IS NULL ) OR (zweckbestimmung IS NOT NULL AND detaillierteZweckbestimmung IS NOT NULL)';

COMMIT;