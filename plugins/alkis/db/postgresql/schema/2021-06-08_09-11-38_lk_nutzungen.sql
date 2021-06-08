BEGIN;

DROP VIEW alkis.lk_nutzungen;

ALTER TABLE alkis.n_nutzung ADD COLUMN gid serial;

CREATE OR REPLACE VIEW alkis.lk_nutzungen AS
 SELECT n.gid,
    n.gml_id,
    n.beginnt,
    n.endet,
    n.werteart1,
    n.werteart2,
    n.info,
        CASE

            WHEN n.info = 1000 THEN 'Art der Bebauung - Offen'::text

            WHEN n.info = 2000 THEN 'Art der Bebauung - Geschlossen'::text

            ELSE NULL::text

        END AS info_bezeichner,
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
    nas.objektart AS alkis_objart,
    nas.attributart1 AS alkis_attributart1,
    nas.attributart2 AS alkis_attributart2,
    round(st_area(n.wkb_geometry)) AS flaeche,
    n.wkb_geometry AS the_geom
   FROM alkis.n_nutzung n
     LEFT JOIN alkis.n_nutzungsartenschluessel nas ON n.nutzungsartengruppe = nas.nutzungsartengruppe AND n.werteart1 = nas.werteart1 AND n.werteart2 = nas.werteart2
     LEFT JOIN alkis.n_nutzungsartengruppe nag ON nas.nutzungsartengruppe = nag.schluessel
     LEFT JOIN alkis.n_nutzungsart na ON nas.nutzungsartengruppe = na.nutzungsartengruppe AND nas.nutzungsart = na.schluessel
     LEFT JOIN alkis.n_untergliederung1 nu1 ON nas.nutzungsartengruppe = nu1.nutzungsartengruppe AND nas.nutzungsart = nu1.nutzungsart AND nas.untergliederung1 = nu1.schluessel
     LEFT JOIN alkis.n_untergliederung2 nu2 ON nas.nutzungsartengruppe = nu2.nutzungsartengruppe AND nas.nutzungsart = nu2.nutzungsart AND nas.untergliederung1 = nu2.untergliederung1 AND nas.untergliederung2 = nu2.schluessel;

COMMIT;
