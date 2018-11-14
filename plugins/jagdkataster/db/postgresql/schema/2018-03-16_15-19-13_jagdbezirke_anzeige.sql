BEGIN;

-- Function: jagdkataster.jagdbezirke_anzeige()

-- DROP FUNCTION jagdkataster.jagdbezirke_anzeige();

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
      IF old_farbid1 IS NULL THEN 
				old_farbid1 = cast(round(random() * 9 + 1) as integer); -- erzeuge zufällige Zahl zwischen 1 und 10
      END IF;
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


COMMIT;
