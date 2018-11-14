BEGIN;

CREATE TABLE probaug.bau_geometrien
(
  gid serial NOT NULL,
  jahr integer NOT NULL,
  aktenzeichen integer NOT NULL,
  status boolean NOT NULL DEFAULT true,
	the_geom geometry(Geometry,:alkis_epsg),
  CONSTRAINT bau_geometrien_pk PRIMARY KEY (gid)
)
WITH (
  OIDS=TRUE
);

COMMIT;
