BEGIN;

UPDATE u_groups SET `Gruppenname` = 'eigene Abfragen' WHERE `Gruppenname` = 'Suchergebnis';

COMMIT;
