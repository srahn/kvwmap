BEGIN;
	ALTER TABLE rolle ADD COLUMN dataset_operations_position enum('unten','oben','beide') NOT NULL DEFAULT 'unten';
COMMIT;
