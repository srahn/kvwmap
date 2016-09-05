BEGIN;

CREATE TYPE xplankonverter.enum_factory AS ENUM
   ('sql',
    'form',
    'default');
CREATE TYPE xplankonverter.enum_konvertierungsstatus AS ENUM
   ('in Erstellung',
    'erstellt',
    'Angaben vollständig',
    'in Konvertierung',
    'Konvertierung abgeschlossen',
    'Konvertierung abgebrochen',
    'in GML-Erstellung',
    'GML-Erstellung abgeschlossen',
    'GML-Erstellung abgebrochen');
CREATE TYPE xplankonverter.geometrietyp AS ENUM
   ('Punkt',
    'MultiPunkt',
    'Linie',
    'MultiLinie',
    'Flaeche',
    'MultiFlaeche');
CREATE TYPE xplankonverter.output_epsg AS ENUM
   ('25832',
    '25833');

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
  output_epsg xplankonverter.output_epsg NOT NULL DEFAULT '25832'::xplankonverter.output_epsg,
  geom_precision integer NOT NULL DEFAULT 3,
  gml_layer_group_id integer,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
COMMENT ON COLUMN xplankonverter.konvertierungen.bezeichnung IS 'Bezeichnung der Konvertierung. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';
COMMENT ON COLUMN xplankonverter.konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';
COMMENT ON COLUMN xplankonverter.konvertierungen.user_id IS 'Id des Nutzers, der den Datensatz angelegt hat. Dieser Wert solle automatisch vom System kvwmap beim Anlegen des Datensatzes erzeugt werden und ein Wert aus der MySQL-Tabelle users der kvwmap Karten- und Nutzerdatenbank kvwmapsp sein.';

CREATE TABLE xplankonverter.regeln
(
  class_name character varying NOT NULL, -- Name der Klassse im XPlan-Datenmodell, die mit dieser Regel befüllt werden soll.
  factory xplankonverter.enum_factory NOT NULL DEFAULT 'sql'::xplankonverter.enum_factory, -- Art der Befüllung der Klasse mit Werten. SQL ... Daten werden über ein SQL-Statement abgefragt. form ... Daten werden über ein Web-Formular vom Nutzer eingegeben. default ... Daten werden aus einer Tabelle mit Default-Werten übernommen.
  sql text, -- Das SQL-Statement mit dem die Objekte der Klasse bestückt werden sollen.
  name character varying NOT NULL DEFAULT 'Konvertierungsregel'::character varying, -- Name der Regel.
  beschreibung text, -- Beschreibung der Regel.
  geometrietyp xplankonverter.geometrietyp NOT NULL, -- Typ der Geometrie, die zur Klasse gehört. Point, Line, Polyline
  epsg_code integer, -- EPSG-Code in dem die Geometrien für diese Klasse vorliegen.
  konvertierung_id integer NOT NULL, -- Id der Konvertierung zu dem diese Regel gehört.
  stelle_id integer, -- Id der Stelle in der die Konvertierungsregel erstellt und angewendet werden kann.
  created_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  updated_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
  bereich_gml_id uuid,
  id integer NOT NULL DEFAULT nextval('xplankonverter.regeln_regel_id_seq'::regclass),
  layer_id integer,
  CONSTRAINT regeln_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

COMMENT ON COLUMN xplankonverter.regeln.class_name IS 'Name der Klassse im XPlan-Datenmodell, die mit dieser Regel befüllt werden soll.';
COMMENT ON COLUMN xplankonverter.regeln.factory IS 'Art der Befüllung der Klasse mit Werten. SQL ... Daten werden über ein SQL-Statement abgefragt. form ... Daten werden über ein Web-Formular vom Nutzer eingegeben. default ... Daten werden aus einer Tabelle mit Default-Werten übernommen.';
COMMENT ON COLUMN xplankonverter.regeln.sql IS 'Das SQL-Statement mit dem die Objekte der Klasse bestückt werden sollen.';
COMMENT ON COLUMN xplankonverter.regeln.name IS 'Name der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.beschreibung IS 'Beschreibung der Regel.';
COMMENT ON COLUMN xplankonverter.regeln.geometrietyp IS 'Typ der Geometrie, die zur Klasse gehört. Point, Line, Polyline';
COMMENT ON COLUMN xplankonverter.regeln.epsg_code IS 'EPSG-Code in dem die Geometrien für diese Klasse vorliegen.';
COMMENT ON COLUMN xplankonverter.regeln.konvertierung_id IS 'Id der Konvertierung zu dem diese Regel gehört.';
COMMENT ON COLUMN xplankonverter.regeln.stelle_id IS 'Id der Stelle in der die Konvertierungsregel erstellt und angewendet werden kann.';

CREATE TABLE xplankonverter.shapefiles
(
  id integer NOT NULL DEFAULT nextval('xplankonverter.shapes_id_seq'::regclass),
  filename character varying,
  konvertierung_id integer,
  stelle_id integer,
  layer_id integer,
  epsg_code integer,
  datatype smallint,
  CONSTRAINT shapes_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);
-- Function: xplankonverter.update_konvertierung_state()

-- DROP FUNCTION xplankonverter.update_konvertierung_state();

	-- Function: xplankonverter.update_konvertierung_state()

	-- DROP FUNCTION xplankonverter.update_konvertierung_state();

	CREATE OR REPLACE FUNCTION xplankonverter.update_konvertierung_state()
	  RETURNS trigger AS
	$BODY$
	DECLARE
	  _konvertierung_id integer;
	  plan_or_regel_assigned BOOLEAN;
	  old_state character varying;
	  new_state Character varying;
	BEGIN
	  IF (TG_OP = 'INSERT') THEN
	    _konvertierung_id := NEW.konvertierung_id;
	    RAISE NOTICE 'update_konvertierung_state nach insert';
	  ELSIF (TG_OP = 'DELETE') THEN
	    _konvertierung_id := OLD.konvertierung_id;
	    RAISE NOTICE 'update_konvertierung_state nach delete';
	  END IF;
	  RAISE NOTICE 'for konvertierung_id: %', _konvertierung_id;

	  SELECT
	    status
	  FROM
	    xplankonverter.konvertierungen
	  WHERE
	    id = _konvertierung_id
	  INTO
	    old_state;

	  SELECT distinct
	    case WHEN p.gml_id IS NOT NULL OR r.id IS NOT NULL THEN true ELSE false END AS plan_or_regel_assigned
	  FROM
	    xplankonverter.konvertierungen k LEFT JOIN
	    xplan_gml.rp_plan p ON k.id = p.konvertierung_id LEFT JOIN
	    xplankonverter.regeln r ON k.id = r.konvertierung_id
	  WHERE
	    k.id = _konvertierung_id
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
	    xplankonverter.konvertierungen
	  SET
	    status = new_state::xplankonverter.enum_konvertierungsstatus
	  WHERE
	    id = _konvertierung_id;

	RETURN NULL;
	END;
	$BODY$
	  LANGUAGE plpgsql VOLATILE
	  COST 100;
	ALTER FUNCTION xplankonverter.update_konvertierung_state()
	  OWNER TO pgadmin;


CREATE TRIGGER update_konvertierung_state
	AFTER INSERT OR DELETE
	ON xplankonverter.regeln
	FOR EACH ROW
	EXECUTE PROCEDURE xplankonverter.update_konvertierung_state();

CREATE TRIGGER update_konvertierung_state
	AFTER INSERT OR DELETE
	ON xplan_gml.rp_plan
	FOR EACH ROW
	EXECUTE PROCEDURE xplankonverter.update_konvertierung_state();

CREATE TABLE xplankonverter.layer_colors (
  name text NOT NULL,
  geometrietyp text NOT NULL,
  color text,
  CONSTRAINT layer_colors_pkey PRIMARY KEY (name, geometrietyp)
)

INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturschutzrechtlichesschutzgebiet', 'polygon', '0 100 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserschutz', 'point', '0 105 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserschutz', 'line', '0 105 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserschutz', 'polygon', '0 105 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gewaesser', 'point', '30 144 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gewaesser', 'line', '30 144 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gewaesser', 'polygon', '30 144 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_klimaschutz', 'point', '255 215 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_klimaschutz', 'line', '255 215 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_klimaschutz', 'polygon', '255 215 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erholung', 'point', '154 205 50');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erholung', 'line', '154 205 50');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erholung', 'polygon', '154 205 50');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erneuerbareenergie', 'point', '238 220 130');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erneuerbareenergie', 'line', '238 220 130');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_erneuerbareenergie', 'polygon', '238 220 130');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_forstwirtschaft', 'point', '48 128 20');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_forstwirtschaft', 'line', '48 128 20');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_forstwirtschaft', 'polygon', '48 128 20');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kulturlandschaft', 'point', '255 236 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kulturlandschaft', 'line', '255 236 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kulturlandschaft', 'polygon', '255 236 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_landwirtschaft', 'point', '127 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_landwirtschaft', 'line', '127 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_landwirtschaft', 'polygon', '127 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_radwegwanderweg', 'point', '84 139 84');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_radwegwanderweg', 'line', '84 139 84');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_radwegwanderweg', 'polygon', '84 139 84');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sportanlage', 'point', '54 100 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sportanlage', 'line', '54 100 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sportanlage', 'polygon', '54 100 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigerfreiraumschutz', 'point', '152 251 152');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigerfreiraumschutz', 'line', '152 251 152');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigerfreiraumschutz', 'polygon', '152 251 152');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_rohstoff', 'point', '139 101 8');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_rohstoff', 'line', '139 101 8');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_rohstoff', 'polygon', '139 101 8');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_energieversorgung', 'point', '176 23 31');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_energieversorgung', 'line', '176 23 31');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_energieversorgung', 'polygon', '176 23 31');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_entsorgung', 'point', '138 54 15');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_entsorgung', 'line', '138 54 15');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_entsorgung', 'polygon', '138 54 15');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kommunikation', 'point', '81 81 81');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kommunikation', 'line', '81 81 81');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_kommunikation', 'polygon', '81 81 81');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_laermschutzbauschutz', 'point', '255 128 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_laermschutzbauschutz', 'line', '255 128 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_laermschutzbauschutz', 'polygon', '255 128 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sozialeinfrastruktur', 'point', '188 143 143');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sozialeinfrastruktur', 'line', '188 143 143');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sozialeinfrastruktur', 'polygon', '188 143 143');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserwirtschaft', 'point', '61 89 171');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserwirtschaft', 'line', '61 89 171');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserwirtschaft', 'polygon', '61 89 171');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigeinfrastruktur', 'point', '255 127 80');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigeinfrastruktur', 'line', '255 127 80');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigeinfrastruktur', 'polygon', '255 127 80');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_verkehr', 'point', '132 132 132');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_verkehr', 'line', '132 132 132');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_verkehr', 'polygon', '132 132 132');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_strassenverkehr', 'point', '170 170 170');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_strassenverkehr', 'line', '170 170 170');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_strassenverkehr', 'polygon', '170 170 170');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_schienenverkehr', 'point', '138 51 36');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_schienenverkehr', 'line', '138 51 36');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_schienenverkehr', 'polygon', '138 51 36');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_luftverkehr', 'point', '183 183 183');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_luftverkehr', 'line', '183 183 183');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_luftverkehr', 'polygon', '183 183 183');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserverkehr', 'point', '0 0 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserverkehr', 'line', '0 0 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wasserverkehr', 'polygon', '0 0 139');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstverkehr', 'point', '139 121 94');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstverkehr', 'line', '139 121 94');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstverkehr', 'polygon', '139 121 94');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_raumkategorie', 'point', '139 35 35');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_raumkategorie', 'line', '139 35 35');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_raumkategorie', 'polygon', '139 35 35');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_achse', 'point', '142 56 142');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_achse', 'line', '142 56 142');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_achse', 'polygon', '142 56 142');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_freiraum', 'point', '0 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_freiraum', 'line', '0 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_freiraum', 'polygon', '0 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_bodenschutz', 'point', '139 69 19');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_bodenschutz', 'line', '139 69 19');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_bodenschutz', 'polygon', '139 69 19');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gruenzuggruenzaesur', 'point', '144 238 144');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gruenzuggruenzaesur', 'line', '144 238 144');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_gruenzuggruenzaesur', 'polygon', '144 238 144');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_hochwasserschutz', 'point', '0 191 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_hochwasserschutz', 'line', '0 191 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_hochwasserschutz', 'polygon', '0 191 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturlandschaft', 'point', '202 255 112');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturlandschaft', 'line', '202 255 112');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturlandschaft', 'polygon', '202 255 112');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturschutzrechtlichesschutzgebiet', 'point', '0 100 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_naturschutzrechtlichesschutzgebiet', 'line', '0 100 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sperrgebiet', 'point', '139 28 98');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sperrgebiet', 'line', '139 28 98');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sperrgebiet', 'polygon', '139 28 98');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_zentralerort', 'point', '255 0 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_zentralerort', 'line', '255 0 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_zentralerort', 'polygon', '255 0 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_funktionszuweisung', 'point', '255 69 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_funktionszuweisung', 'line', '255 69 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_funktionszuweisung', 'polygon', '255 69 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_siedlung', 'point', '244 85 5');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_siedlung', 'line', '244 85 5');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_siedlung', 'polygon', '244 85 5');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wohnensiedlung', 'point', '140 0 11');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wohnensiedlung', 'line', '140 0 11');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_wohnensiedlung', 'polygon', '140 0 11');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_industriegewerbe', 'point', '107 42 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_industriegewerbe', 'line', '107 42 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_industriegewerbe', 'polygon', '107 42 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_einzelhandel', 'point', '203 65 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_einzelhandel', 'line', '203 65 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_einzelhandel', 'polygon', '203 65 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigersiedlungsbereich', 'point', '231 145 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigersiedlungsbereich', 'line', '231 145 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_sonstigersiedlungsbereich', 'polygon', '231 145 255');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_grenze', 'point', '30 30 30');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_grenze', 'line', '30 30 30');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_grenze', 'polygon', '30 30 30');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_planungsraum', 'point', '192 192 192');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_planungsraum', 'line', '192 192 192');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_planungsraum', 'polygon', '192 192 192');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_generischesobjekt', 'point', '255 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_generischesobjekt', 'line', '255 255 0');
INSERT INTO layer_colors (name, geometrietyp, color) VALUES ('rp_generischesobjekt', 'polygon', '255 255 0');

COMMIT;