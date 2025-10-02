BEGIN;
	ALTER TABLE `stelle` 
	ADD COLUMN ows_inspireidentifiziert bool DEFAULT 0
	COMMENT 'Sind die Daten inspireidentifiziert?' 
	AFTER `ows_fees`;
COMMIT;