BEGIN;

CREATE TYPE xplankonverter.enum_konvertierungsstatus AS ENUM ('erstellt','Angaben vollständig','validiert','in Arbeit','fertig');
CREATE TYPE xplankonverter.enum_geometrietyp AS ENUM ('Point','Line','Polygon');
CREATE TYPE xplankonverter.enum_factory AS ENUM ('sql','form','default');

CREATE TABLE xplankonverter.konvertierungen
(
  id serial NOT NULL,
  bezeichnung character varying,
  beschreibung text,
  status xplankonverter.enum_konvertierungsstatus NOT NULL DEFAULT 'erstellt',
  stelle_id integer,
  layer_group_id integer,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.konvertierungen.bezeichnung IS 'Bezeichnung der Konvertierung. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.bescheibung IS 'Nähere Angaben zur Konvertierung, bzw. zum Plan, der mit den dazugehörigen Regeln konvertiert werden soll. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';
COMMENT ON COLUMN xplankonverter.konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';

CREATE TABLE xplankonverter.regeln
(
  id serial NOT NULL,
  class_name character varying,
  factory xplankonverter.enum_factory NOT NULL DEFAULT 'sql',
  sql text,
  name character varying,
  beschreibung text,
  geometrietyp xplankonverter.enum_geometrietyp,
  epsg_code integer,
  konvertierung_id integer,
  stelle_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  updated_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  CONSTRAINT regeln_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.regeln.id IS 'Id der Konvertierungsregel.';
COMMENT ON COLUMN xplankonverter.regeln.class_name IS 'Name der Klassse im XPlan-Datenmodell, die mit dieser Regel befüllt werden soll.';
COMMENT ON COLUMN xplankonverter.regeln.factory IS 'Art der Befüllung der Klasse mit Werten. SQL ... Daten werden über ein SQL-Statement abgefragt. form ... Daten werden über ein Web-Formular vom Nutzer eingegeben. default ... Daten werden aus einer Tabelle mit Default-Werten übernommen.';
COMMENT ON COLUMN xplankonverter.regeln.sql IS 'Das SQL-Statement mit dem die Objekte der Klasse bestückt werden sollen.';
COMMENT ON COLUMN xplankonverter.regeln.name IS 'Name der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.beschreibung IS 'Beschreibung der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.geometrietyp IS 'Typ der Geometrie, die zur Klasse gehört. Point, Line, Polyline';
COMMENT ON COLUMN xplankonverter.regeln.epsg_code IS 'EPSG-Code in dem die Geometrien für diese Klasse vorliegen.';
COMMENT ON COLUMN xplankonverter.regeln.konvertierung_id IS 'Id der Konvertierung zu dem diese Regel gehört.';
COMMENT ON COLUMN xplankonverter.regeln.stelle_id IS 'Id der Stelle in der die Konvertierungsregel erstellt und angewendet werden kann.';
COMMIT;

CREATE TABLE xplankonverter.shapefiles
(
  id serial NOT NULL,
  filename character varying,
  konvertierung_id integer,
  stelle_id integer,
  layer_id integer,
  epsg_code integer,
  datatype smallint,
  CONSTRAINT shapes_pkey PRIMARY KEY (id)
)
WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.shapefiles.id IS 'Id der Shape Datei.';
COMMENT ON COLUMN xplankonverter.shapefiles.filename IS 'Dateiname der Shape Datei.';
COMMENT ON COLUMN xplankonverter.shapefiles.konvertierung_id IS 'Id der Konvertierung zu der die Shape Datei gehört.';
COMMENT ON COLUMN xplankonverter.shapefiles.stelle_id IS 'Id der Stelle in kvwmap zu der die Shape Datei gehört.';
COMMENT ON COLUMN xplankonverter.shapefiles.layer_id IS 'Id des Layers in den die Shape Datei eingelesen wurde.';

-- Function: xplankonverter.update_konvertierung_state()

-- DROP FUNCTION xplankonverter.update_konvertierung_state();

CREATE OR REPLACE FUNCTION xplankonverter.update_konvertierung_state()
  RETURNS trigger AS
$BODY$
DECLARE
  konvertierung_id integer;
  plan_or_regel_assigned BOOLEAN;
  old_state character varying;
  new_state Character varying;
BEGIN
  IF (TG_OP = 'INSERT') THEN
    konvertierung_id := NEW.konvertierung_id;
    RAISE NOTICE 'update_konvertierung_state nach insert';
  ELSIF (TG_OP = 'DELETE') THEN
    konvertierung_id := OLD.konvertierung_id;
    RAISE NOTICE 'update_konvertierung_state nach delete';
  END IF;
  RAISE NOTICE 'for konvertierung_id: %', konvertierung_id;

  SELECT
    status
  FROM
    konvertierungen
  WHERE
    id = konvertierung_id
  INTO
    old_state;

  SELECT distinct
    case WHEN p.gml_id IS NOT NULL OR r.id IS NOT NULL THEN true ELSE false END AS plan_or_regel_assigned
  FROM
    konvertierungen k LEFT JOIN
    xplan_gml.rp_plan p ON k.id = p.konvertierung_id LEFT JOIN
    regeln r ON k.id = r.konvertierung_id
  WHERE
    k.id = konvertierung_id
  INTO
    plan_or_regel_assigned;

  RAISE NOTICE 'Mindestens ein Plan oder Regel ist zugeordnet: %', plan_or_regel_assigned;
  RAISE NOTICE 'Alter Konvertierungsstatus: %', old_state;
  new_state := old_state;
  IF (plan_or_regel_assigned) THEN
    IF (old_state = 'in Erstellung') THEN
      new_state := 'erstellt';
    END IF;
  ELSE
    new_state := 'in Erstellung';
  END IF;
  RAISE NOTICE 'Neuer Konvertierungsstatus: %', new_state;
  UPDATE
    konvertierungen
  SET
    status = new_state::enum_konvertierungsstatus
  WHERE
    id = konvertierung_id;

RETURN NULL;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

