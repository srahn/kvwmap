########################################
# SQL für die Erzeugung von EJB-Verdachtsflächen


# Tabellendefinition
####################

DROP TABLE IF EXISTS jagdkataster.ejb_verdachtsflaechen;

CREATE TABLE jagdkataster.ejb_verdachtsflaechen
(
  eigentuemer character varying,
  flaeche integer
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('jagdkataster', 'ejb_verdachtsflaechen','the_geom',2398,'GEOMETRY', 2);

INSERT INTO jagdkataster.ejb_verdachtsflaechen 
SELECT 
 eigentuemer, round(st_area(st_buffer(the_geom, -10))) as flaeche, st_buffer(the_geom, -10) as the_geom 
FROM (
  select 
   (st_dump(st_memunion(st_buffer(o.the_geom,10)))).geom as the_geom, 
   array_to_string(array(
     select rtrim(name1,',') 
     from alb_g_eigentuemer ee, alb_g_namen nn 
     where ee.lfd_nr_name=nn.lfd_nr_name 
     and ee.bezirk=e.bezirk 
     and ee.blatt=e.blatt order by rtrim(name1,',')),' || '
   ) as eigentuemer 
  from alb_g_namen n, alb_g_eigentuemer e, alb_g_buchungen b, alknflst f, alkobj_e_fla o 
  where e.lfd_nr_name=n.lfd_nr_name 
  and e.bezirk=b.bezirk 
  and e.blatt=b.blatt 
  and b.flurstkennz=f.flurstkennz 
  and f.objnr=o.objnr 
  group by e.bezirk, e.blatt
 ) as foo 
WHERE st_area(st_buffer(the_geom, -10))>750000

VACUUM ANALYZE jagdkataster.ejb_verdachtsflaechen;

CREATE INDEX ixejbverd_the_geom_gist
ON jagdkataster.ejb_verdachtsflaechen
USING gist
(the_geom );