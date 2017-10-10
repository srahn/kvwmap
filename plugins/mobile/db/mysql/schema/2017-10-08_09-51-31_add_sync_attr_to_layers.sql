BEGIN;

ALTER TABLE `layer` ADD COLUMN sync enum('0', '1') NOT NULL DEFAULT '0' COMMENT 'Wenn 1, werden Änderungen in maintable_delta gespeichert und stellt ein das Layer für Syncronisierung mit kvmobile verfügbar ist.';

COMMIT;
