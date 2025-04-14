BEGIN;

CREATE OR REPLACE VIEW fortfuehrungslisten.gemarkungen AS 
 SELECT (foo.gid::text || foo.jahr::text)::bigint AS oid,
    foo.gemeindename,
    foo.gemkgnr,
    foo.jahr
   FROM ( SELECT f.gid,
            g.gemeindename,
            f.gemarkung AS gemkgnr,
            generate_series(2006, date_part('year'::text, now())::integer) AS jahr
           FROM alkis.pp_gemarkung f,
            alkis.pp_gemeinde g
          WHERE f.land = g.land AND f.gemeinde = g.gemeinde) foo;

COMMIT;
