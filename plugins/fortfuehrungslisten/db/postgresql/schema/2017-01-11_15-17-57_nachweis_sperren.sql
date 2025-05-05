BEGIN;

ALTER TABLE fortfuehrungslisten.ff_auftraege ADD COLUMN gesperrt boolean NOT NULL DEFAULT false;

CREATE OR REPLACE RULE r2_auftrag_sperren AS
    ON UPDATE TO fortfuehrungslisten.ff_auftraege
   WHERE ( SELECT old.gesperrt AND new.gesperrt) DO INSTEAD  SELECT 'Die Bearbeitung des Fortführungsnachweises ist gesperrt.',
    'error' AS msg_type;
COMMENT ON RULE r2_auftrag_sperren ON fortfuehrungslisten.ff_auftraege IS 'Die Regel prüft ob das Attribut an_sperren auf true steht. Wenn ja, wir die Änderung nicht gespeichert und statt dessen eine Fehlermeldung zurück geliefert.';

COMMIT;