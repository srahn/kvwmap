BEGIN;

CREATE TYPE xplankonverter.enum_konvertierungsstatus AS ENUM ('erstellt','Angaben vollständig','validiert','in Arbeit','fertig');
CREATE TYPE xplankonverter.enum_geometrietyp AS ENUM ('Point','Line','Polygon');
CREATE TYPE xplankonverter.enum_factory AS ENUM ('sql','form','default');

CREATE TABLE xplankonverter.konvertierungen
(
  id serial NOT NULL,
  bezeichnung character varying, -- Bezeichnung der Konvertierung. (Freitext)
  status xplankonverter.enum_konvertierungsstatus NOT NULL DEFAULT 'in Erstellung'::xplankonverter.enum_konvertierungsstatus, -- Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.
  stelle_id integer, -- Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.
  beschreibung text,
  shape_layer_group_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT now(),
  updated_at timestamp without time zone NOT NULL DEFAULT now(),
  user_id integer, -- Id des Nutzers, der den Datensatz angelegt hat. Dieser Wert solle automatisch vom System kvwmap beim Anlegen des Datensatzes erzeugt werden und ein Wert aus der MySQL-Tabelle users der kvwmap Karten- und Nutzerdatenbank kvwmapsp sein.
  geom_precision integer NOT NULL DEFAULT 3,
  gml_layer_group_id integer,
  epsg xplankonverter.epsg_codes,
  output_epsg xplankonverter.epsg_codes NOT NULL DEFAULT '25832'::xplankonverter.epsg_codes,
  input_epsg xplankonverter.epsg_codes,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
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

CREATE TABLE gml_classes.xp_plan (
  gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
  aendert uuid, -- DataType XP_VerbundenerPlan
  beschreibung text,
  bezugshoehe text,
  erstellungsmassstab text,
  genehmigungsdatum text,
  hatgenerattribut uuid, -- DataType XP_GenerAttribut
  informell uuid, -- DataType XP_ExterneReferenz
  internalid text,
  kommentar text,
  name text,
  nummer text,
  raeumlichergeltungsbereich geometry, -- Union XP_Flaechengeometrie
  rechtsverbindlich uuid, -- DataType XP_ExterneReferenz
  refbegruendung uuid, -- DataType XP_ExterneReferenz
  refbeschreibung uuid, -- DataType XP_ExterneReferenz
  refexternalcodelist uuid, -- DataType XP_ExterneReferenz
  reflegende uuid, -- DataType XP_ExterneReferenz
  refplangrundlage uuid, -- DataType XP_ExterneReferenz
  refrechtsplan uuid, -- DataType XP_ExterneReferenz
  technherstelldatum text,
  untergangsdatum text,
  verfahrensmerkmale uuid, -- DataType XP_VerfahrensMerkmal
  wurdegeaendertvon uuid, -- DataType XP_VerbundenerPlan
  xplangmlversion text DEFAULT '4.1'::text,
  CONSTRAINT xp_plan_pkey PRIMARY KEY (gml_id)
)
WITH ( OIDS=FALSE);
COMMENT ON TABLE gml_classes.xp_plan IS 'Tabelle XP_Plan';
COMMENT ON COLUMN gml_classes.xp_plan.aendert IS 'DataType XP_VerbundenerPlan';
COMMENT ON COLUMN gml_classes.xp_plan.hatgenerattribut IS 'DataType XP_GenerAttribut';
COMMENT ON COLUMN gml_classes.xp_plan.informell IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.raeumlichergeltungsbereich IS 'Union XP_Flaechengeometrie';
COMMENT ON COLUMN gml_classes.xp_plan.rechtsverbindlich IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.refbegruendung IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.refbeschreibung IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.refexternalcodelist IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.reflegende IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.refplangrundlage IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.refrechtsplan IS 'DataType XP_ExterneReferenz';
COMMENT ON COLUMN gml_classes.xp_plan.verfahrensmerkmale IS 'DataType XP_VerfahrensMerkmal';
COMMENT ON COLUMN gml_classes.xp_plan.wurdegeaendertvon IS 'DataType XP_VerbundenerPlan';

