BEGIN;

ALTER TABLE spatial_ref_sys_alias ADD COLUMN datum character varying[],
																	ADD COLUMN projection character varying[],
																	ADD COLUMN false_easting numeric,
																	ADD COLUMN central_meridian numeric,
																	ADD COLUMN scale_factor numeric,
																	ADD COLUMN unit character varying[];
																	
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942_Adj_1983}', projection='{Gauss_Kruger}', false_easting=4500000.0, central_meridian=12.0, scale_factor=1.0, unit='{Meter}' WHERE srid=2398;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 2398, '{D_Pulkovo_1942_Adj_1983}', '{Gauss_Kruger}', 4500000.0, 12.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=2398);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942_Adj_1983}', projection='{Gauss_Kruger}', false_easting=5500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=2399;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 2399, '{D_Pulkovo_1942_Adj_1983}', '{Gauss_Kruger}', 5500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=2399);
UPDATE spatial_ref_sys_alias SET datum='{D_Rauenberg_1983}', projection='{Gauss_Kruger}', false_easting=4500000.0, central_meridian=12.0, scale_factor=1.0, unit='{Meter}' WHERE srid=3398;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 3398, '{D_Rauenberg_1983}', '{Gauss_Kruger}', 4500000.0, 12.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=3398);
UPDATE spatial_ref_sys_alias SET datum='{D_Rauenberg_1983}', projection='{Gauss_Kruger}', false_easting=5500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=3399;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 3399, '{D_Rauenberg_1983}', '{Gauss_Kruger}', 5500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=3399);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942_Adj_1983}', projection='{Gauss_Kruger}', false_easting=2500000.0, central_meridian=9.0, scale_factor=1.0, unit='{Meter}' WHERE srid=3834;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 3834, '{D_Pulkovo_1942_Adj_1983}', '{Gauss_Kruger}', 2500000.0, 9.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=3834);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942_Adj_1983}', projection='{Gauss_Kruger}', false_easting=3500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=3835;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 3835, '{D_Pulkovo_1942_Adj_1983}', '{Gauss_Kruger}', 3500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=3835);
UPDATE spatial_ref_sys_alias SET datum='{D_WGS_1984}', projection='{Mercator_Auxiliary_Sphere}', false_easting=0.0, central_meridian=0.0, scale_factor=NULL, unit='{Meter}' WHERE srid=3857;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 3857, '{D_WGS_1984}', '{Mercator_Auxiliary_Sphere}', 0.0, 0.0, NULL, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=3857);
UPDATE spatial_ref_sys_alias SET datum='{D_WGS_1984}', projection=NULL, false_easting=NULL, central_meridian=NULL, scale_factor=NULL, unit='{Degree}' WHERE srid=4326;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 4326, '{D_WGS_1984}', NULL, NULL, NULL, NULL, '{Degree}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=4326);
UPDATE spatial_ref_sys_alias SET datum='{D_ETRS_1989}', projection='{Transverse_Mercator}', false_easting=32500000.0, central_meridian=9.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=4647;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 4647, '{D_ETRS_1989}', '{Transverse_Mercator}', 32500000.0, 9.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=4647);
UPDATE spatial_ref_sys_alias SET datum='{D_ETRS_1989}', projection='{Transverse_Mercator}', false_easting=33500000.0, central_meridian=15.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=5650;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 5650, '{D_ETRS_1989}', '{Transverse_Mercator}', 33500000.0, 15.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=5650);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942_Adj_1983}', projection='{Gauss_Kruger}', false_easting=3500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=5665;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 5665, '{D_Pulkovo_1942_Adj_1983}', '{Gauss_Kruger}', 3500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=5665);
UPDATE spatial_ref_sys_alias SET datum='{D_ETRS_1989}', projection='{Transverse_Mercator}', false_easting=500000.0, central_meridian=9.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=25832;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 25832, '{D_ETRS_1989}', '{Transverse_Mercator}', 500000.0, 9.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=25832);
UPDATE spatial_ref_sys_alias SET datum='{D_ETRS_1989}', projection='{Transverse_Mercator}', false_easting=500000.0, central_meridian=15.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=25833;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 25833, '{D_ETRS_1989}', '{Transverse_Mercator}', 500000.0, 15.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=25833);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942}', projection='{Gauss_Kruger}', false_easting=2500000.0, central_meridian=9.0, scale_factor=1.0, unit='{Meter}' WHERE srid=28402;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 28402, '{D_Pulkovo_1942}', '{Gauss_Kruger}', 2500000.0, 9.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=28402);
UPDATE spatial_ref_sys_alias SET datum='{D_Pulkovo_1942}', projection='{Gauss_Kruger}', false_easting=3500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=28403;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 28403, '{D_Pulkovo_1942}', '{Gauss_Kruger}', 3500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=28403);
UPDATE spatial_ref_sys_alias SET datum='{D_Deutsches_Hauptdreiecksnetz}', projection='{Gauss_Kruger}', false_easting=4500000.0, central_meridian=12.0, scale_factor=1.0, unit='{Meter}' WHERE srid=31468;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 31468, '{D_Deutsches_Hauptdreiecksnetz}', '{Gauss_Kruger}', 4500000.0, 12.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=31468);
UPDATE spatial_ref_sys_alias SET datum='{D_Deutsches_Hauptdreiecksnetz}', projection='{Gauss_Kruger}', false_easting=5500000.0, central_meridian=15.0, scale_factor=1.0, unit='{Meter}' WHERE srid=31469;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 31469, '{D_Deutsches_Hauptdreiecksnetz}', '{Gauss_Kruger}', 5500000.0, 15.0, 1.0, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=31469);
UPDATE spatial_ref_sys_alias SET datum='{D_WGS_1984}', projection='{Transverse_Mercator}', false_easting=500000.0, central_meridian=9.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=32632;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 32632, '{D_WGS_1984}', '{Transverse_Mercator}', 500000.0, 9.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=32632);
UPDATE spatial_ref_sys_alias SET datum='{D_WGS_1984}', projection='{Transverse_Mercator}', false_easting=500000.0, central_meridian=15.0, scale_factor=0.9996, unit='{Meter}' WHERE srid=32633;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 32633, '{D_WGS_1984}', '{Transverse_Mercator}', 500000.0, 15.0, 0.9996, '{Meter}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=32633);
UPDATE spatial_ref_sys_alias SET datum='{D_ETRS_1989}', projection=NULL, false_easting=NULL, central_meridian=NULL, scale_factor=NULL, unit='{Degree}' WHERE srid=4258;
INSERT INTO spatial_ref_sys_alias (srid, datum, projection, false_easting, central_meridian, scale_factor, unit) SELECT 4258, '{D_ETRS_1989}', NULL, NULL, NULL, NULL, '{Degree}' WHERE NOT EXISTS (SELECT 1 FROM spatial_ref_sys_alias WHERE srid=4258);

COMMIT;
