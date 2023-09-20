BEGIN;
	ALTER TABLE layer ADD COLUMN vector_tile_url varchar(255) DEFAULT NULL COMMENT 'Hier kann eine URL zu einer Vektorkachelstyledatei eingetragen werden, die kvmobile mitteilt, dass der Layer als PmVectorTile dargestellt werden soll.';
COMMIT;