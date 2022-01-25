BEGIN;

  CREATE SCHEMA IF NOT EXISTS gebietseinheiten;

  ALTER TABLE IF EXISTS xplankonverter.bundeslaender SET SCHEMA gebietseinheiten;

  DROP TABLE IF EXISTS gebietseinheiten.gemeindeteile;
  DROP TABLE IF EXISTS gebietseinheiten.gemeinden;
  DROP TABLE IF EXISTS gebietseinheiten.gemeindeverbaende;
  DROP TABLE IF EXISTS gebietseinheiten.kreise;

  CREATE TABLE IF NOT EXISTS gebietseinheiten.kreise(
    krs_schl integer PRIMARY KEY,
    krs_nr character(3),
    krs_name character varying(80),
    geom geometry(Multipolygon, 5650)
  );
  CREATE INDEX IF NOT EXISTS kreise_gist ON gebietseinheiten.kreise USING gist (geom);
  DELETE FROM gebietseinheiten.kreise;
  /*INSERT INTO gebietseinheiten.kreise (krs_schl, krs_nr, krs_name, geom)
  SELECT
    schluessel::integer AS krs_schl,
    substring(schluessel::character(5), 3, 3) AS krs_nr,
    gen::character varying(80) AS krs_name,
    ST_Transform(wkb_geometry, 5650) AS geom
  FROM
    import.kreise_laiv
  ORDER BY krs_name;*/

  CREATE TABLE IF NOT EXISTS gebietseinheiten.gemeindeverbaende(
    gvb_schl integer PRIMARY KEY,
    gvb_laiv_schl integer,
    gvb_nr character(4),
    gvb_name character varying(80),
    stelle_id integer,
    krs_schl integer,
    geom geometry(Multipolygon, 5650)
  );
  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeverbaende
    ADD CONSTRAINT kreis_fkey FOREIGN KEY (krs_schl)
    REFERENCES gebietseinheiten.kreise (krs_schl) MATCH SIMPLE
    ON UPDATE CASCADE ON DELETE CASCADE;
  CREATE INDEX IF NOT EXISTS gemeindeverbaende_gist ON gebietseinheiten.gemeindeverbaende USING gist (geom);
  DELETE FROM gebietseinheiten.gemeindeverbaende;
  /*INSERT INTO gebietseinheiten.gemeindeverbaende (gvb_schl, gvb_laiv_schl, gvb_nr, gvb_name, stelle_id, krs_schl, geom)
  SELECT DISTINCT
    substring(gem.rs, 1, 9)::integer AS gvb_schl,
    (gvb.zugehoerig || lpad(gvb.schluessel::character(4), 4, '0'))::integer AS gvb_laiv_schl,
    substring(gem.rs, 6, 4)::character(4) AS gvb_nr,
    gvb.gen AS gvb_name,
    substring(gem.rs, 1, 9)::integer AS stelle_id,
    gvb.zugehoerig::integer AS krs_schl,
    ST_Multi(ST_Transform(gvb.wkb_geometry, 5650)) AS geom
  FROM
    import.gemeindeverbaende_laiv gvb JOIN
    import.gemeinden_laiv gem ON gvb.schluessel = gem.zugehoerig
  ORDER BY krs_schl, gvb_schl;*/

  CREATE TABLE IF NOT EXISTS gebietseinheiten.gemeinden(
    gem_schl bigint PRIMARY KEY,
    uuid uuid NOT NULL DEFAULT uuid_generate_v1mc(),
    ags character(9),
    rs character(12),
    gem_nr character(3),
    gem_name character varying,
    stelle_id integer,
    geom geometry(Multipolygon, 5650),
    geom_25833 geometry(Multipolygon, 25833),
    gvb_schl integer,
    hat_eindeutigen_namen boolean NOT NULL DEFAULT true
  );
  ALTER TABLE IF EXISTS gebietseinheiten.gemeinden
    ADD CONSTRAINT gemeindeverbaende_fkey FOREIGN KEY (gvb_schl)
    REFERENCES gebietseinheiten.gemeindeverbaende (gvb_schl) MATCH SIMPLE
    ON UPDATE CASCADE ON DELETE CASCADE;
  CREATE INDEX IF NOT EXISTS gemeinden_gist ON gebietseinheiten.gemeinden USING gist (geom);
  CREATE INDEX IF NOT EXISTS gemeinden_gist_25833 ON gebietseinheiten.gemeinden USING gist (geom_25833);
  DELETE FROM gebietseinheiten.gemeinden;
  /*INSERT INTO gebietseinheiten.gemeinden (gem_schl, ags, rs, gem_nr, gem_name, stelle_id, geom, geom_25833, gvb_schl)
  SELECT
    g.rs::bigint AS gem_schl,
    g.schluessel::character(9) AS ags,
    g.rs::character(12) AS rs,
    substring(g.rs, 10, 3) AS gem_nr,
    g.gen AS gem_name,
    substring(g.rs, 1, 9)::integer AS stelle_id,
    ST_Transform(g.wkb_geometry, 5650) AS geom,
    ST_Transform(g.wkb_geometry, 25833) AS geom_25833,
    substring(rs, 1, 9)::integer AS gvb_schl
  FROM
    import.gemeinden_laiv g
  ORDER BY rs;*/
  -- Kennzeichne die Gemeinden, deren Name in gebietseinheiten.gemeinden nicht eindeutig ist.
  UPDATE
    gebietseinheiten.gemeinden g
  SET
    hat_eindeutigen_namen = false
  FROM
    (
      SELECT
        gem_name
      FROM
        gebietseinheiten.gemeinden
      GROUP BY gem_name
      HAVING count(gem_name) > 1
    ) mehrfach
  WHERE
    g.gem_name = mehrfach.gem_name;

  -- Kennzeichne die Gemeinden, deren Name in xplan_gml.bp_plan nicht eindeutig ist.
  UPDATE
    gebietseinheiten.gemeinden g
  SET
    hat_eindeutigen_namen = false
  FROM
    (
      SELECT
        gem_name
      FROM
        (
          SELECT DISTINCT
            (gemeinde[1]).rs AS rs,
            (gemeinde[1]).gemeindename AS gem_name
          FROM
            xplan_gml.bp_plan
        ) unique_rs_and_gem_name
      GROUP BY gem_name
      HAVING count(gem_name) > 1
    ) mehrfach
  WHERE
    g.gem_name = mehrfach.gem_name;

  CREATE TABLE IF NOT EXISTS gebietseinheiten.gemeindeteile (
    gtl_schl bigint PRIMARY KEY,
    gtl_nr character(4) NOT NULL,
    gtl_name character varying(80),
    geom geometry(Multipolygon, 5650),
    geom_25833 geometry(MultiPolygon, 25833),
    gem_schl bigint
  );
  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeteile
    ADD CONSTRAINT gemeinden_fkey FOREIGN KEY (gem_schl)
    REFERENCES gebietseinheiten.gemeinden (gem_schl) MATCH SIMPLE
    ON UPDATE CASCADE ON DELETE CASCADE;
  CREATE INDEX IF NOT EXISTS gemeindeteile_gist ON gebietseinheiten.gemeindeteile USING gist (geom);
  CREATE INDEX IF NOT EXISTS gemeindeteile_gist_25833 ON gebietseinheiten.gemeinden USING gist (geom_25833);
  DELETE FROM gebietseinheiten.gemeindeteile;
  /*INSERT INTO gebietseinheiten.gemeindeteile (gtl_schl, gtl_nr, gtl_name, geom, geom_25833, gem_schl)
  SELECT
    ovz.rs_gemeinde * 10000 + ovz.nr AS gtl_schl,
    lpad(ovz.nr::text, 4, '0') AS gtl_nr,
    ovz.ortsteil_wohnplatz AS gtl_name,
    ST_Transform(gtl.wkb_geometry, 5650) AS geom,
    ST_Transform(gtl.wkb_geometry, 25833) AS geom_25833,
    ovz.rs_gemeinde AS gem_schl
  FROM
    import.ortsverzeichnis_laiv ovz LEFT JOIN
    import.gemeindeteile_hro gtl ON
    	ovz.rs_gemeinde = gtl.gem_schl::bigint AND
  	ovz.ortsteil_wohnplatz LIKE gtl.gtl_name
  WHERE
    ovz.kennnr = '2'
  ORDER BY gtl_schl;*/

  -- Planaufstellende Gemeinden
  -- Hier noch mal prüfen wie die gemeindeteile mit den gemeinden verknüpft werden
  -- Wenn sich namen ändern mit gleicher ID geht das so nicht.
  DROP TABLE IF EXISTS gebietseinheiten.planaufstellende_gemeinden;
  CREATE TABLE IF NOT EXISTS gebietseinheiten.planaufstellende_gemeinden(
    gem_id serial NOT NULL PRIMARY KEY,
    gem_schl bigint,
    uuid uuid NOT NULL DEFAULT uuid_generate_v1mc(),
    ags character(8),
    rs character(12),
    gem_nr character(3),
    gem_name character varying,
    stelle_id integer,
    geom geometry(Multipolygon, 5650),
    gvb_schl integer
  );
  DELETE FROM gebietseinheiten.planaufstellende_gemeinden;
  INSERT INTO gebietseinheiten.planaufstellende_gemeinden (gem_schl, ags, rs, gem_nr, gem_name, stelle_id, gvb_schl)
  SELECT DISTINCT
    rs::bigint AS gem_schl,
    ags::character(8),
    rs::character(12),
    substring(rs, 10, 3)::character(3) AS gem_nr,
    gmd_name AS gem_name,
    stelle_id,
    id_amt AS gvb_schl
  FROM
    xplankonverter.planaufstellende_gebietseinheiten
  ORDER BY rs;

  DROP TABLE IF EXISTS gebietseinheiten.planaufstellende_gemeindeteile;
  CREATE TABLE IF NOT EXISTS gebietseinheiten.planaufstellende_gemeindeteile (
    gtl_id serial NOT NULL PRIMARY KEY,
    gtl_schl bigint,
    gtl_nr character(4) NOT NULL,
    gtl_name character varying(80),
    geom geometry(Multipolygon, 5650),
    gem_schl bigint,
    gem_name character varying
  );
  DELETE FROM gebietseinheiten.planaufstellende_gemeindeteile;
  INSERT INTO gebietseinheiten.planaufstellende_gemeindeteile (gtl_schl, gtl_nr, gtl_name, gem_schl, gem_name)
  SELECT DISTINCT
    (rs || lpad(id_ot::text, 4, '0'))::bigint AS gtl_schl,
    lpad(id_ot::text, 4, '0')::character(4) AS gtl_nr,
    ot_name AS gtl_name,
    rs::bigint AS gem_schl,
    gmd_name AS gem_name
  FROM
    xplankonverter.planaufstellende_gebietseinheiten
  ORDER BY gtl_schl;

  --
  -- Ergänze die Tabellen planaufstellende_gemeinde und planaufstellende_gemeindeteile wenn noch nicht in den Tabellen enthalten
  --
  INSERT INTO gebietseinheiten.planaufstellende_gemeinden (gem_schl, uuid, ags, rs, gem_nr, gem_name, stelle_id, geom, gvb_schl)
  SELECT
    g.gem_schl, g.uuid, g.ags, g.rs, g.gem_nr, g.gem_name, g.stelle_id, g.geom, g.gvb_schl
  FROM
    gebietseinheiten.gemeinden g LEFT JOIN
    gebietseinheiten.planaufstellende_gemeinden pg ON
      g.gem_schl = pg.gem_schl AND
      g.gem_name = pg.gem_name AND
      g.gvb_schl = pg.gvb_schl
  WHERE
    pg.gem_id IS NULL;
  
  INSERT INTO gebietseinheiten.planaufstellende_gemeindeteile (gtl_schl, gtl_nr, gtl_name, geom, gem_schl, gem_name)
  SELECT
    gtl.gtl_schl, gtl.gtl_nr, gtl.gtl_name, gtl.geom, gtl.gem_schl, g.gem_name
  FROM
    gebietseinheiten.gemeinden g JOIN
    gebietseinheiten.gemeindeteile gtl ON g.gem_schl = gtl.gem_schl LEFT JOIN
    gebietseinheiten.planaufstellende_gemeindeteile pgtl ON
      gtl.gtl_schl = pgtl.gtl_schl AND
      gtl.gtl_name = pgtl.gtl_name AND
      gtl.gem_schl = pgtl.gem_schl AND
      g.gem_name = pgtl.gem_name
  WHERE
    pgtl.gtl_id IS NULL;

--  DROP TABLE IF EXISTS xplankonverter.kreise;
--  DROP TABLE IF EXISTS xplankonverter.gemeindeverbaende;
--  DROP TABLE IF EXISTS xplankonverter.gemeinden;
--  DROP TABLE IF EXISTS xplankonverter.gebietseinheiten;
--  DROP TABLE IF EXISTS xplankonverter.gemeindeteile;

COMMIT;
