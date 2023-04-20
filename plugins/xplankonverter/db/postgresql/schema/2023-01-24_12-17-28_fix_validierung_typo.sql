BEGIN;
UPDATE xplankonverter.validierungen
SET msg_error = 'Wenn diese Validierung fehlgeschlagen ist, werden statt dessen einzelne Fehlermeldungen für jede einzelne Regel ausgegeben.'
WHERE msg_error = 'Wenn diese Validierung fehltgeschlagen ist, werden statt dessen einzelne Fehlermeldungen für jede einzelne Regel ausgegeben.';
COMMIT;
