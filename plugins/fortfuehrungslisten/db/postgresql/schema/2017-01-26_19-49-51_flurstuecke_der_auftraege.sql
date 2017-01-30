BEGIN;

CREATE OR REPLACE VIEW fortfuehrungslisten.ff_flurstuecke_der_auftraege AS 
 SELECT DISTINCT foo.ff_auftrag_id,
    foo.flurstkennz AS flurstueckskennzeichen,
    alkis.asflurstkennz(foo.flurstkennz::text) AS flurstkennz,
    substr(foo.flurstkennz::text, 1, 2) AS land,
    substr(foo.flurstkennz::text, 3, 4) AS gemarkung,
    ltrim(substr(foo.flurstkennz::text, 7, 3), '0'::text) AS flur,
    ltrim(substr(foo.flurstkennz::text, 10, 5), '0'::text) AS zaehler,
    ltrim(substr(foo.flurstkennz::text, 16, 3), '0'::text) AS nenner,
    foo.zeigtauf
   FROM ( SELECT ff_faelle.ff_auftrag_id,
            unnest(ff_faelle.zeigtaufaltesflurstueck) AS flurstkennz,
            'altes'::text AS zeigtauf
           FROM fortfuehrungslisten.ff_faelle
        UNION
         SELECT ff_faelle.ff_auftrag_id,
            unnest(ff_faelle.zeigtaufneuesflurstueck) AS flurstkennz,
            'neues'::text AS zeigtauf
           FROM fortfuehrungslisten.ff_faelle) foo
  ORDER BY alkis.asflurstkennz(foo.flurstkennz::text);

COMMIT;