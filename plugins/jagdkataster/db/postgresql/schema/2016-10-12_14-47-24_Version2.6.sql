BEGIN;

-- Spalte verzicht zum View jagdbezirk_paechter hinzufügen
DROP VIEW jagdkataster.jagdbezirk_paechter;

CREATE OR REPLACE VIEW jagdkataster.jagdbezirk_paechter AS 
 SELECT jb.oid,
    jb.id,
    jb.name,
    jb.art,
    jb.flaeche,
    jpb.bezirkid,
    jb.concode,
    jb.jb_zuordnung,
    jb.status,
    jb.verzicht,
    jb.the_geom
   FROM jagdkataster.jagdbezirke jb
     LEFT JOIN jagdkataster.jagdpaechter2bezirke jpb ON jb.concode::text = jpb.bezirkid::text
  GROUP BY jb.oid, jb.id, jb.name, jb.art, jb.flaeche, jpb.bezirkid, jb.concode, jb.jb_zuordnung, jb.status, jb.the_geom;


-- Tabelle Verdachtsflächen
CREATE TABLE jagdkataster.lk_ejb_verdachtsflaechen
(
  eigentuemer text,
  flaeche double precision
)
WITH (
  OIDS=TRUE
);

SELECT AddGeometryColumn('jagdkataster', 'lk_ejb_verdachtsflaechen','the_geom',25833,'MULTIPOLYGON', 2);

CREATE INDEX ixlkvrejbverd_the_geom_gist
  ON jagdkataster.lk_ejb_verdachtsflaechen
  USING gist
  (the_geom);


-- Tabelle Befriedete Bezirke
CREATE TABLE jagdkataster.befriedete_bezirke
(
  id serial NOT NULL,
  name character varying(50),
  zu_gjb character varying(50)
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('jagdkataster', 'befriedete_bezirke','the_geom',25833,'MULTIPOLYGON', 2);

CREATE INDEX ixbefbezirke_the_geom_gist
  ON jagdkataster.befriedete_bezirke
  USING gist
  (the_geom);

ALTER TABLE jagdkataster.befriedete_bezirke
  ADD CONSTRAINT pkey PRIMARY KEY(id);


-- Tabelle Nutzungen für Befriedete Bezirke
CREATE TABLE jagdkataster.befriedete_bezirke_nutzungen
(
  schluessel integer NOT NULL,
  bezeichnung character varying NOT NULL,
  beschreibung character varying
)
WITH (
  OIDS=TRUE
);
INSERT INTO jagdkataster.befriedete_bezirke_nutzungen VALUES (11000, 'Gebäudefläche - § 5 Abs. 1 Nr. 1 LJagdG', 'Gebäude, die zum Aufenthalt von Menschen dienen, und Gebäude, die mit solchen Gebäuden räumlich zusammenhängen');
INSERT INTO jagdkataster.befriedete_bezirke_nutzungen VALUES (17000, 'Gebäudefläche - § 5 Abs. 1 Nr. 1 LJagdG', NULL);
INSERT INTO jagdkataster.befriedete_bezirke_nutzungen VALUES (16000, 'Gebäudefläche - § 5 Abs. 1 Nr. 1 LJagdG', NULL);
INSERT INTO jagdkataster.befriedete_bezirke_nutzungen VALUES (12000, 'Gebäudefläche - § 5 Abs. 1 Nr. 1 LJagdG', 'Gebäude, die zum Aufenthalt von Menschen dienen, und Gebäude, die mit solchen Gebäuden räumlich zusammenhängen');
INSERT INTO jagdkataster.befriedete_bezirke_nutzungen VALUES (18460, 'Hausgärten - § 5 Abs. 1 Nr. 2 LJagdG', NULL);

-- View Flurstücke für Befriedete Bezirke
-- st_area_utm muss pro LK angepasst werden!!!
CREATE OR REPLACE VIEW jagdkataster.befriedete_bezirke_flurstuecke AS 
 SELECT b.id, a.flurstueckskennzeichen, a.amtlicheflaeche, round((st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision))::numeric, 0) AS fst_teilflaeche_abs, round((st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision) / a.amtlicheflaeche * 100::double precision)::numeric, 1) AS fst_teilflaeche_proz, ((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text AS naschluessel, COALESCE(bn.bezeichnung, nag.gruppe) AS nutzung, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision))::numeric, 0) AS na_teilflaeche_abs, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision) / (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision)) * 100::double precision)::numeric, 1) AS na_teilflaeche_proz
   FROM jagdkataster.befriedete_bezirke b
   LEFT JOIN alkis.ax_flurstueck a ON st_intersects(b.the_geom, a.wkb_geometry) AND a.endet IS NULL AND (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric, 38) / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38) * 100::numeric) > 0.1
   LEFT JOIN alkis.n_nutzung n ON st_intersects(n.wkb_geometry, a.wkb_geometry) AND st_area_utm(st_intersection(n.wkb_geometry, a.wkb_geometry), 25833, 6384000::numeric, 38) > 0.001 AND st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric, 38) > 0.1
   LEFT JOIN alkis.n_nutzungsartenschluessel nas ON n.nutzungsartengruppe = nas.nutzungsartengruppe AND n.werteart1 = nas.werteart1 AND n.werteart2 = nas.werteart2
   LEFT JOIN alkis.n_nutzungsartengruppe nag ON nas.nutzungsartengruppe = nag.schluessel
   LEFT JOIN jagdkataster.befriedete_bezirke_nutzungen bn ON bn.schluessel::text = (((nas.nutzungsartengruppe::text || nas.nutzungsart::text) || nas.untergliederung1::text) || nas.untergliederung2::text)
  ORDER BY a.flurstueckskennzeichen, round((st_area_utm(st_intersection(b.the_geom, st_intersection(n.wkb_geometry, a.wkb_geometry)), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision) / (st_area_utm(st_intersection(b.the_geom, a.wkb_geometry), 25833, 6384000::numeric, 38)::double precision * (a.amtlicheflaeche / st_area_utm(a.wkb_geometry, 25833, 6384000::numeric, 38)::double precision)) * 100::double precision)::numeric, 1) DESC;


-- View Befriedete Bezirke
CREATE OR REPLACE VIEW jagdkataster.view_befriedete_bezirke AS 
 SELECT bf.oid, bf.id, bf.name, bf.zu_gjb, ( SELECT (count(q.id) || ' Flurstück'::text) || 
                CASE
                    WHEN count(q.id) <> 1 THEN 'e'::text
                    ELSE ''::text
                END
           FROM ( SELECT bbf.id
                   FROM jagdkataster.befriedete_bezirke_flurstuecke bbf
                  WHERE bbf.id = bf.id
                  GROUP BY bbf.id, bbf.flurstueckskennzeichen) q) AS nutzungen, bf.the_geom
   FROM jagdkataster.befriedete_bezirke bf;

CREATE OR REPLACE RULE delete_befriedeter_bezirk AS
    ON DELETE TO jagdkataster.view_befriedete_bezirke DO INSTEAD  DELETE FROM jagdkataster.befriedete_bezirke
  WHERE old.oid = befriedete_bezirke.oid;

CREATE OR REPLACE RULE insert_befriedeter_bezirk AS
    ON INSERT TO jagdkataster.view_befriedete_bezirke DO INSTEAD  INSERT INTO jagdkataster.befriedete_bezirke (id, name, zu_gjb, the_geom) 
  VALUES (new.id, new.name, new.zu_gjb, new.the_geom);

CREATE OR REPLACE RULE update_befriedeter_bezirk AS
    ON UPDATE TO jagdkataster.view_befriedete_bezirke DO INSTEAD  UPDATE jagdkataster.befriedete_bezirke SET name = new.name, zu_gjb = new.zu_gjb, the_geom = new.the_geom
  WHERE befriedete_bezirke.oid = old.oid;


-- Tabelle Farben für Jagdbezirkeinfärbung
-- farbid muss zwischen 1 und 10 lückenlos gefüllt sein!
-- Werte > 10 werden in jagdbezirke_anzeige ignoriert!
CREATE TABLE jagdkataster.jagdbezirke_anzeigefarben
(
  farbid serial NOT NULL,
  wert character varying NOT NULL,
  bezeichnung character varying NOT NULL
)
WITH (
  OIDS=TRUE
);
COMMENT ON COLUMN jagdkataster.jagdbezirke_anzeigefarben.farbid IS 'farbid muss zwischen 1 und 10 lückenlos gefüllt sein!
Werte > 10 werden in jagdbezirke_anzeige ignoriert!';

INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (1, '189 0 38', 'Rot 1');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (2, '240 59 32', 'Rot 2');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (3, '217 95 14', 'Orange 1');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (4, '254 153 41', 'Orange 2');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (5, '4 90 141', 'Blau 1');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (6, '43 140 190', 'Blau 2');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (7, '0 104 55', 'Grün 1');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (8, '49 163 84', 'Grün 2');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (9, '197 27 138', 'Violett 1');
INSERT INTO jagdkataster.jagdbezirke_anzeigefarben VALUES (10, '136 86 167', 'Violett 2');


-- Tabelle Jagdbezirkeinfärbung
CREATE TABLE jagdkataster.jagdbezirke_anzeige
(
  id character varying NOT NULL,
  farbid smallint NOT NULL DEFAULT 1,
  name character varying NOT NULL
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('jagdkataster', 'jagdbezirke_anzeige','the_geom',25833,'GEOMETRY', 2);

CREATE INDEX gist_jb_anzeige_geom
  ON jagdkataster.jagdbezirke_anzeige
  USING gist
  (the_geom);


-- Erstmaliges Einlesen der (nicht abgerundeten) Eigenjagdbezirke in jagdbezirke_anzeige
INSERT INTO jagdkataster.jagdbezirke_anzeige
SELECT id, cast(round(random() * 9 + 1) as integer), a.name, st_transform(
      case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_simplify(a.the_geom,0.1)
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          ) 
      end, 25833)
FROM jagdkataster.jagdbezirke a
WHERE id != '' and art = 'ejb' and status = 'f';


-- Trigger für das Einfärben neuer/geänderter Jagdbezirke während der Abrundung
CREATE OR REPLACE FUNCTION jagdkataster.jagdbezirke_anzeige()
  RETURNS trigger AS
$BODY$DECLARE
    old_jbid text; -- alte id aus jagdbezirke_anzeige
    new_jbid text; -- neue id aus jagdbezirke nach INSERT
    old_farbid1 integer; -- alte farbid aus jagdbezirke_anzeige 
    old_farbid2 integer; -- alte farbid aus jagdbezirke_anzeige bei Änderung der Zuordnung
BEGIN

-- Die Geometrie wird in allen Fällen immer aus dem EJB plus 
-- allen zugeordneten Teilflächen neu erzeugt

    IF (TG_OP = 'INSERT') THEN
      IF (NEW.jb_zuordnung != '') THEN
        new_jbid = NEW.jb_zuordnung;
      ELSE
        new_jbid = NEW.id;
      END IF;
      old_jbid = (SELECT id FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid);
      old_farbid1 = (SELECT farbid FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid);
      DELETE FROM jagdkataster.jagdbezirke_anzeige where id = old_jbid;
      INSERT INTO jagdkataster.jagdbezirke_anzeige 
      (select
      new_jbid,
      case
       when old_farbid1 is null then -- wenn es den Jagdbezirk noch nicht gibt
        cast(round(random() * 9 + 1) as integer) -- erzeuge zufällige Zahl zwischen 1 und 10
       else
        old_farbid1
       end,
       a.name, 
      st_transform(case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_simplify(a.the_geom,0.1)
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          ) 
      end, 25833) 
      as the_geom
from jagdkataster.jagdbezirke a where a.id=new_jbid and a.id !='' and a.art='ejb' and a.status = 'f');
    RETURN NEW;
    END IF;

    IF (TG_OP = 'UPDATE') THEN
      IF (NEW.jb_zuordnung != '' AND NEW.jb_zuordnung = OLD.jb_zuordnung) THEN
        new_jbid = NEW.jb_zuordnung;
        old_jbid = NEW.jb_zuordnung;
      END IF;
      IF (NEW.jb_zuordnung != '' AND NEW.jb_zuordnung != OLD.jb_zuordnung) THEN
        new_jbid = NEW.jb_zuordnung;
        old_jbid = OLD.jb_zuordnung;
      END IF;
      IF (NEW.id != '') THEN
        new_jbid = NEW.id;
        old_jbid = OLD.id;
      END IF;

      IF (NEW.id != '' OR (NEW.jb_zuordnung = OLD.jb_zuordnung)) THEN
      old_farbid1 = (SELECT farbid FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid);
      DELETE FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid;
      INSERT INTO jagdkataster.jagdbezirke_anzeige 
      (select
      new_jbid,
      old_farbid1,
      a.name, 
      st_transform(case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_multi(st_simplify(a.the_geom,0.1)) 
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          )
      end, 25833)
      as the_geom
      from jagdkataster.jagdbezirke a where a.id=new_jbid and a.id !='' and a.art='ejb' and a.status = 'f');
      END IF;

      IF (NEW.jb_zuordnung != '' AND (NEW.jb_zuordnung != OLD.jb_zuordnung)) THEN
      old_farbid1 = (SELECT farbid FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid);
      old_farbid2 = (SELECT farbid FROM jagdkataster.jagdbezirke_anzeige where id = old_jbid);
      -- Änderung des neuen JB, wenn die Zuordnung sich geändert hat
      DELETE FROM jagdkataster.jagdbezirke_anzeige where id = new_jbid;
      INSERT INTO jagdkataster.jagdbezirke_anzeige 
      (select
      new_jbid,
      old_farbid1,
      a.name, 
      st_transform(case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_multi(st_simplify(a.the_geom,0.1)) 
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          )
      end, 25833)
      as the_geom
      from jagdkataster.jagdbezirke a where a.id=new_jbid and a.id !='' and a.art='ejb' and a.status = 'f');
      -- Änderung des neuen JB, wenn die Zuordnung sich geändert hat
      DELETE FROM jagdkataster.jagdbezirke_anzeige where id = old_jbid;
      INSERT INTO jagdkataster.jagdbezirke_anzeige 
      (select
      old_jbid,
      old_farbid2,
      a.name, 
      st_transform(case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_multi(st_simplify(a.the_geom,0.1)) 
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          )
      end, 25833)
      as the_geom
      from jagdkataster.jagdbezirke a where a.id=old_jbid and a.id !='' and a.art='ejb' and a.status = 'f');
      END IF;
           
      RETURN NEW;
    END IF;

    IF (TG_OP = 'DELETE') THEN
      IF (OLD.jb_zuordnung != '') THEN
        old_jbid = OLD.jb_zuordnung;
      ELSE
        old_jbid = OLD.id;
      END IF;
      old_farbid1 = (SELECT farbid FROM jagdkataster.jagdbezirke_anzeige where id = old_jbid);
      DELETE FROM jagdkataster.jagdbezirke_anzeige where id = old_jbid;
      INSERT INTO jagdkataster.jagdbezirke_anzeige 
      (select
      old_jbid,
      old_farbid1,
      a.name, 
      st_transform(case 
        when st_union(a.the_geom,(
            select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb'  and b.jb_zuordnung=a.id)) is null 
        then st_multi(st_simplify(a.the_geom,0.1)) 
        else 
          st_multi(st_simplify(
           st_buffer(
            st_union(
             st_buffer(a.the_geom,0.01),
             st_buffer((select st_union(b.the_geom) as the_geom from jagdkataster.jagdbezirke b where b.art!='ejb' and b.jb_zuordnung=a.id),0.01)
            ),-0.01)
           ,0.1)
          ) 
      end, 25833)
      as the_geom
from jagdkataster.jagdbezirke a where a.id=old_jbid and a.id !='' and a.art='ejb' and a.status = 'f');
      RETURN OLD;
    END IF;

RETURN null;
END;$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER jagdbezirke_anzeige
  AFTER INSERT OR UPDATE OR DELETE
  ON jagdkataster.jagdbezirke
  FOR EACH ROW
  EXECUTE PROCEDURE jagdkataster.jagdbezirke_anzeige();


-- Tabelle Abgerundete Eigenjagdbezirke
CREATE TABLE jagdkataster.jagdbezirke_abgerundet
(
  id character varying(10) NOT NULL,
  flaeche numeric,
  name character varying(50),
  concode character varying(5),
  conname character varying(40),
  status_abrundung character varying(20),
  verzicht boolean,
  datum_beschluss date,
  datum_bestandskraft date,
  datum_erfassung date
)
WITH (
  OIDS=TRUE
);
SELECT AddGeometryColumn('jagdkataster', 'jagdbezirke_abgerundet','the_geom',25833,'MULTIPOLYGON', 2);

CREATE INDEX jagdbezirke_abgerundet_the_geom_gist
  ON jagdkataster.jagdbezirke_abgerundet
  USING gist
  (the_geom);

CREATE OR REPLACE FUNCTION jagdkataster.ejb_bereinigen()
  RETURNS trigger AS
$BODY$DECLARE
   
BEGIN

UPDATE jagdkataster.jagdbezirke set status='t' where jb_zuordnung=NEW.id;
UPDATE jagdkataster.jagdbezirke set status='t' where jb_zuordnung=NEW.id || '-V'; --(Bei Verzichtsflächen)

RETURN NEW;

END;$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER ejb_bereinigen
  AFTER INSERT
  ON jagdkataster.jagdbezirke_abgerundet
  FOR EACH ROW
  EXECUTE PROCEDURE jagdkataster.ejb_bereinigen();



-- Ergänzung zur Tabelle jagdbezirke:
-- Trigger jagdbezirke_abrunden
CREATE OR REPLACE FUNCTION jagdkataster.jagdbezirke_abrunden()
  RETURNS trigger AS
$BODY$DECLARE

count_abtrennung INTEGER;
count_verzicht INTEGER;
count_zuordnung INTEGER;
    
BEGIN

-- Abzug = 'atf' (Abtrennflächen)
-- Zuordnung = 'jbe', 'agf', 'jbf', 'jex' (Enklaven, Angliederungsflächen, jagdbezirksfreie Flächen, Exklaven)
-- Sonderfall: Abtrennflächen durch Verzicht ('atv') ergeben immer zusätzliche separate Objekte

    IF (TG_OP = 'UPDATE') THEN
     IF NEW.art = 'ajb' THEN

      NEW.art = 'ejb';  -- Der EJB in Abrundung bleibt EJB in Abrundung, 
      NEW.status='t';   -- wird aber historisch

        SELECT count(b.art) INTO count_abtrennung FROM jagdkataster.jagdbezirke b WHERE b.art = 'atf' AND b.jb_zuordnung=NEW.id;
        SELECT count(b.art) INTO count_verzicht FROM jagdkataster.jagdbezirke b WHERE b.art = 'atv' AND b.jb_zuordnung=NEW.id;
        SELECT count(b.art) INTO count_zuordnung FROM jagdkataster.jagdbezirke b WHERE b.art in ('jbe','agf','jbf','jex') AND b.jb_zuordnung=NEW.id;

        INSERT INTO jagdkataster.jagdbezirke_abgerundet
        SELECT id, NULL, name, concode, conname, 'vor Bestandskraft', 'f', NULL, NULL, cast(now() as date),
					st_transform(
         case
         when count_abtrennung = 0 
         then 
           case
           when count_zuordnung = 0 
           then                     -- Wenn es keine Abzugsflächen gibt und keine anderen Zuordnungsflächen: AJB = EJB
             st_multi(a.the_geom)
           else                     -- Wenn es keine Abzugsflächen gibt, aber Zuordnungsflächen: AJB = EJB + Zuordnung
            st_multi((st_union(a.the_geom,(select st_union(b.the_geom) FROM jagdkataster.jagdbezirke b WHERE b.art in ('jbe','agf','jbf','jex') AND b.jb_zuordnung=a.id))))
           end
         else
           case
           when count_zuordnung = 0
           then                     -- Wenn es Abzugsflächen gibt aber keine anderen Zuordnungsflächen: AJB = EJB - Abzug
             st_multi(st_difference(
               a.the_geom,
               (select st_union(b.the_geom) FROM jagdkataster.jagdbezirke b WHERE b.art = 'atf' AND b.jb_zuordnung=a.id))
             )

           else                     -- Wenn es Abzugsflächen gibt und Zuordnungsflächen: AJB = EJB + Zuordnung - Abzug
            st_multi((st_difference(
              (select st_union(a.the_geom,(select st_union(b.the_geom) FROM jagdkataster.jagdbezirke b WHERE b.art in ('jbe','agf','jbf','jex') AND b.jb_zuordnung=a.id))),
              (select st_union(b.the_geom) FROM jagdkataster.jagdbezirke b WHERE b.art = 'atf' AND b.jb_zuordnung=a.id))))
           end
         end, 25833)
        FROM jagdkataster.jagdbezirke a WHERE a.id = NEW.id;

   -- Wenn der EJB in Abrundung auf "Verzicht" gesetzt wurde, wird ein zweiter - deckungsgleicher - abgerundeter EJB mit Verzicht angelegt
      IF NEW.verzicht = 't' THEN

        INSERT INTO jagdkataster.jagdbezirke_abgerundet
        SELECT id, NULL, name, NULL, NULL, 'vor Bestandskraft', 't', NULL, NULL, cast(now() as date), the_geom
        FROM jagdkataster.jagdbezirke_abgerundet WHERE id = NEW.id;

      END IF;

   -- Wenn Verzicht-Flächen vorhanden sind, aber nicht den gesamten EJB überdecken, werden diese in die abgerundeten EJB übernommen
      IF count_verzicht > 0 THEN

        INSERT INTO jagdkataster.jagdbezirke_abgerundet
        (SELECT jb_zuordnung, NULL, (SELECT name FROM jagdkataster.jagdbezirke b where b.id = NEW.id), NULL, NULL, 'vor Bestandskraft', 't', NULL, NULL, cast(now() as date), st_transform(st_multi(a.the_geom), 25833)
        FROM jagdkataster.jagdbezirke a WHERE a.jb_zuordnung = NEW.id AND art = 'atv');

      END IF;

      UPDATE jagdkataster.jagdbezirke_abgerundet set flaeche = round(cast(st_area(the_geom)/10000 as numeric),1) where id = NEW.id;

    END IF;
    RETURN NEW;
   END IF;

RETURN NULL;
END;$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE TRIGGER jagdbezirke_abrunden
  BEFORE UPDATE
  ON jagdkataster.jagdbezirke
  FOR EACH ROW
  EXECUTE PROCEDURE jagdkataster.jagdbezirke_abrunden();
	
COMMIT;
