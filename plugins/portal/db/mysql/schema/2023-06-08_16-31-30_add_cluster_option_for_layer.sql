BEGIN;
	ALTER TABLE layer ADD COLUMN cluster_option BOOLEAN NOT NULL DEFAULT true COMMENT 'Wenn true werden Punkte in kvmobile als Cluster dargestellt, sonst als einzelne Marker.';
COMMIT;