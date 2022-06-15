BEGIN;

DROP VIEW alkis.lk_nutzungen;

ALTER TABLE alkis.n_nutzung ALTER COLUMN beginnt TYPE timestamp without time zone USING beginnt::timestamp without time zone;
ALTER TABLE alkis.n_nutzung ALTER COLUMN endet TYPE timestamp without time zone USING endet::timestamp without time zone;

ALTER TABLE alkis.pp_zuordungspfeilspitze_flurstueck ALTER COLUMN beginnt TYPE timestamp without time zone USING beginnt::timestamp without time zone;
ALTER TABLE alkis.pp_zuordungspfeilspitze_flurstueck ALTER COLUMN endet TYPE timestamp without time zone USING endet::timestamp without time zone;

CREATE OR REPLACE VIEW alkis.lk_nutzungen AS 
 SELECT 
    n.gml_id,
    n.beginnt,
    n.endet,
    n.werteart1,
    n.werteart2,
    n.info,
    n.zustand,
    n.name,
    n.bezeichnung,
    ((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text AS nutzungsartschluessel,
    nag.bereich,
    nag.gruppe AS nutzungsartengruppe,
    na.nutzungsart,
    nu1.untergliederung1,
    nu2.untergliederung2,
    nas.nutzungsartengruppe AS nutzungsartengruppeschl,
    nas.nutzungsart AS nutzungsartschl,
    nas.untergliederung1 AS untergliederung1schl,
    nas.untergliederung2 AS untergliederung2schl,
    n.wkb_geometry AS the_geom
   FROM alkis.n_nutzung n
     LEFT JOIN alkis.n_nutzungsartenschluessel nas ON n.nutzungsartengruppe = nas.nutzungsartengruppe AND n.werteart1 = nas.werteart1 AND n.werteart2 = nas.werteart2
     LEFT JOIN alkis.n_nutzungsartengruppe nag ON nas.nutzungsartengruppe = nag.schluessel
     LEFT JOIN alkis.n_nutzungsart na ON nas.nutzungsartengruppe = na.nutzungsartengruppe AND nas.nutzungsart = na.schluessel
     LEFT JOIN alkis.n_untergliederung1 nu1 ON nas.nutzungsartengruppe = nu1.nutzungsartengruppe AND nas.nutzungsart = nu1.nutzungsart AND nas.untergliederung1 = nu1.schluessel
     LEFT JOIN alkis.n_untergliederung2 nu2 ON nas.nutzungsartengruppe = nu2.nutzungsartengruppe AND nas.nutzungsart = nu2.nutzungsart AND nas.untergliederung1 = nu2.untergliederung1 AND nas.untergliederung2 = nu2.schluessel;


COMMIT;
