BEGIN;

DO $$ 
    BEGIN
        BEGIN
            ALTER TABLE jagdkataster.jagdbezirke ADD COLUMN gid serial;
        EXCEPTION
            WHEN duplicate_column THEN RAISE NOTICE 'Die Spalte gid existiert bereits in jagdkataster.jagdbezirke.';
        END;
    END;
$$;

DO $$ 
    BEGIN
        BEGIN
            ALTER TABLE jagdkataster.jagdbezirke_abgerundet ADD COLUMN gid serial;
        EXCEPTION
            WHEN duplicate_column THEN RAISE NOTICE 'Die Spalte gid existiert bereits in jagdkataster.jagdbezirke_abgerundet.';
        END;
    END;
$$;

DO $$ 
    BEGIN
        BEGIN
            ALTER TABLE jagdkataster.jagdbezirke_anzeige ADD COLUMN gid serial;
        EXCEPTION
            WHEN duplicate_column THEN RAISE NOTICE 'Die Spalte gid existiert bereits in jagdkataster.jagdbezirke_anzeige.';
        END;
    END;
$$;

DO $$ 
    BEGIN
        BEGIN
            ALTER TABLE jagdkataster.lk_ejb_verdachtsflaechen ADD COLUMN gid serial;
        EXCEPTION
            WHEN duplicate_column THEN RAISE NOTICE 'Die Spalte gid existiert bereits in jagdkataster.lk_ejb_verdachtsflaechen.';
        END;
    END;
$$;

DROP VIEW IF EXISTS jagdkataster.jagdbezirk_paechter;
CREATE OR REPLACE VIEW jagdkataster.jagdbezirk_paechter AS 
 SELECT jb.gid,
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
  GROUP BY jb.gid, jb.id, jb.name, jb.art, jb.flaeche, jpb.bezirkid, jb.concode, jb.jb_zuordnung, jb.status, jb.verzicht, jb.the_geom;
	
DROP VIEW IF EXISTS jagdkataster.view_befriedete_bezirke;
CREATE OR REPLACE VIEW jagdkataster.view_befriedete_bezirke AS 
 SELECT bf.id,
    bf.name,
    bf.zu_gjb,
    ( SELECT (count(q.id) || ' Flurstück'::text) ||
                CASE
                    WHEN count(q.id) <> 1 THEN 'e'::text
                    ELSE ''::text
                END
           FROM ( SELECT bbf.id
                   FROM jagdkataster.befriedete_bezirke_flurstuecke bbf
                  WHERE bbf.id = bf.id
                  GROUP BY bbf.id, bbf.flurstueckskennzeichen) q) AS nutzungen,
    bf.the_geom
   FROM jagdkataster.befriedete_bezirke bf;

ALTER TABLE jagdkataster.view_befriedete_bezirke
  OWNER TO kvwmap;

-- Rule: delete_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke

-- DROP RULE delete_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke;

CREATE OR REPLACE RULE delete_befriedeter_bezirk AS
    ON DELETE TO jagdkataster.view_befriedete_bezirke DO INSTEAD  DELETE FROM jagdkataster.befriedete_bezirke
  WHERE old.id = befriedete_bezirke.id;

-- Rule: insert_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke

-- DROP RULE insert_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke;

CREATE OR REPLACE RULE insert_befriedeter_bezirk AS
    ON INSERT TO jagdkataster.view_befriedete_bezirke DO INSTEAD  INSERT INTO jagdkataster.befriedete_bezirke (id, name, zu_gjb, the_geom)
  VALUES (new.id, new.name, new.zu_gjb, new.the_geom);

-- Rule: update_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke

-- DROP RULE update_befriedeter_bezirk ON jagdkataster.view_befriedete_bezirke;

CREATE OR REPLACE RULE update_befriedeter_bezirk AS
    ON UPDATE TO jagdkataster.view_befriedete_bezirke DO INSTEAD  UPDATE jagdkataster.befriedete_bezirke SET name = new.name, zu_gjb = new.zu_gjb, the_geom = new.the_geom
  WHERE befriedete_bezirke.id = old.id;	

COMMIT;
