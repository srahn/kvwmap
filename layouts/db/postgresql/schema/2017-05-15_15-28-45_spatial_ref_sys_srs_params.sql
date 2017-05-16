BEGIN;

ALTER TABLE spatial_ref_sys_alias ADD COLUMN datum character varying[],
																	ADD COLUMN projection character varying[],
																	ADD COLUMN false_easting numeric,
																	ADD COLUMN central_meridian numeric,
																	ADD COLUMN scale_factor numeric,
																	ADD COLUMN unit character varying[];

COMMIT;
