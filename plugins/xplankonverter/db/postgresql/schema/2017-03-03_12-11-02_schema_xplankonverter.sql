BEGIN;

SET search_path = xplankonverter, pg_catalog;

--
-- TOC entry 4493 (class 1247 OID 408036)
-- Name: enum_factory; Type: TYPE; Schema: xplankonverter; Owner: -
--

CREATE TYPE enum_factory AS ENUM (
    'sql',
    'form',
    'default'
);


--
-- TOC entry 7516 (class 1247 OID 875084)
-- Name: enum_geometrie_typ; Type: TYPE; Schema: xplankonverter; Owner: -
--

CREATE TYPE enum_geometrie_typ AS ENUM (
    'Punkte',
    'Linien',
    'Flächen'
);


--
-- TOC entry 4490 (class 1247 OID 408016)
-- Name: enum_konvertierungsstatus; Type: TYPE; Schema: xplankonverter; Owner: -
--

CREATE TYPE enum_konvertierungsstatus AS ENUM (
    'in Erstellung',
    'erstellt',
    'Angaben vollständig',
    'in Konvertierung',
    'Konvertierung abgeschlossen',
    'Konvertierung abgebrochen',
    'in GML-Erstellung',
    'GML-Erstellung abgeschlossen',
    'GML-Erstellung abgebrochen',
    'INSPIRE-GML-Erstellung abgeschlossen',
    'INSPIRE-GML-Erstellung abgebrochen',
    'in INSPIRE-GML-Erstellung'
);


--
-- TOC entry 6585 (class 1247 OID 874975)
-- Name: epsg_codes; Type: TYPE; Schema: xplankonverter; Owner: -
--

CREATE TYPE epsg_codes AS ENUM (
    '4326',
    '31462',
    '31463',
    '31467',
    '31468',
    '31469',
    '25832',
    '25833',
    '325833',
    '3857',
    '32633',
    '3044',
    '4647',
    '5650',
    '2398'
);


--
-- TOC entry 7606 (class 1247 OID 1061311)
-- Name: validierungsstatus; Type: TYPE; Schema: xplankonverter; Owner: -
--

CREATE TYPE validierungsstatus AS ENUM (
    'Erfolg',
    'Warnung',
    'Fehler'
);


--
-- TOC entry 3896 (class 1255 OID 807423)
-- Name: update_konvertierung_state(); Type: FUNCTION; Schema: xplankonverter; Owner: -
--

CREATE FUNCTION update_konvertierung_state() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
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
$$;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1028 (class 1259 OID 897637)
-- Name: bundeslaender; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE bundeslaender (
    id integer NOT NULL,
    name character varying(62),
    the_geom public.geometry(MultiPolygon,25833)
);


--
-- TOC entry 2663 (class 1259 OID 4426026)
-- Name: inspire_regeln; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE inspire_regeln (
    class_name character varying,
    sql text,
    name character varying,
    beschreibung text,
    id integer NOT NULL
);


SET default_with_oids = true;

--
-- TOC entry 257 (class 1259 OID 408045)
-- Name: konvertierungen; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE konvertierungen (
    id integer NOT NULL,
    bezeichnung character varying,
    status enum_konvertierungsstatus DEFAULT 'in Erstellung'::enum_konvertierungsstatus NOT NULL,
    stelle_id integer,
    beschreibung text,
    shape_layer_group_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    user_id integer,
    geom_precision integer DEFAULT 3 NOT NULL,
    gml_layer_group_id integer,
    epsg epsg_codes,
    output_epsg epsg_codes DEFAULT '25832'::epsg_codes NOT NULL,
    input_epsg epsg_codes
);


--
-- TOC entry 12020 (class 0 OID 0)
-- Dependencies: 257
-- Name: COLUMN konvertierungen.bezeichnung; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN konvertierungen.bezeichnung IS 'Bezeichnung der Konvertierung. (Freitext)';


--
-- TOC entry 12021 (class 0 OID 0)
-- Dependencies: 257
-- Name: COLUMN konvertierungen.status; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';


--
-- TOC entry 12022 (class 0 OID 0)
-- Dependencies: 257
-- Name: COLUMN konvertierungen.stelle_id; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';


--
-- TOC entry 12023 (class 0 OID 0)
-- Dependencies: 257
-- Name: COLUMN konvertierungen.user_id; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN konvertierungen.user_id IS 'Id des Nutzers, der den Datensatz angelegt hat. Dieser Wert solle automatisch vom System kvwmap beim Anlegen des Datensatzes erzeugt werden und ein Wert aus der MySQL-Tabelle users der kvwmap Karten- und Nutzerdatenbank kvwmapsp sein.';


--
-- TOC entry 256 (class 1259 OID 408043)
-- Name: konvertierungen_id_seq; Type: SEQUENCE; Schema: xplankonverter; Owner: -
--

CREATE SEQUENCE konvertierungen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12024 (class 0 OID 0)
-- Dependencies: 256
-- Name: konvertierungen_id_seq; Type: SEQUENCE OWNED BY; Schema: xplankonverter; Owner: -
--

ALTER SEQUENCE konvertierungen_id_seq OWNED BY konvertierungen.id;


SET default_with_oids = false;

--
-- TOC entry 694 (class 1259 OID 834141)
-- Name: layer_colors; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE layer_colors (
    name text NOT NULL,
    geometrietyp text NOT NULL,
    color text
);


SET default_with_oids = true;

--
-- TOC entry 258 (class 1259 OID 408064)
-- Name: regeln; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE regeln (
    class_name character varying NOT NULL,
    factory enum_factory DEFAULT 'sql'::enum_factory NOT NULL,
    sql text,
    name character varying DEFAULT 'Konvertierungsregel'::character varying NOT NULL,
    beschreibung text,
    geometrietyp enum_geometrie_typ NOT NULL,
    epsg_code integer,
    konvertierung_id integer,
    stelle_id integer,
    created_at timestamp without time zone DEFAULT (now())::timestamp without time zone NOT NULL,
    updated_at timestamp without time zone DEFAULT (now())::timestamp without time zone NOT NULL,
    bereich_gml_id uuid,
    id integer NOT NULL,
    layer_id integer,
    bereiche text[]
);


--
-- TOC entry 12025 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.class_name; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.class_name IS 'Name der Klassse im XPlan-Datenmodell, die mit dieser Regel befüllt werden soll.';


--
-- TOC entry 12026 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.factory; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.factory IS 'Art der Befüllung der Klasse mit Werten. SQL ... Daten werden über ein SQL-Statement abgefragt. form ... Daten werden über ein Web-Formular vom Nutzer eingegeben. default ... Daten werden aus einer Tabelle mit Default-Werten übernommen.';


--
-- TOC entry 12027 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.sql; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.sql IS 'Das SQL-Statement mit dem die Objekte der Klasse bestückt werden sollen.';


--
-- TOC entry 12028 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.name; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.name IS 'Name der Regel.';


--
-- TOC entry 12029 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.beschreibung; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.beschreibung IS 'Beschreibung der Regel.';


--
-- TOC entry 12030 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.geometrietyp; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.geometrietyp IS 'Typ der Geometrie, die zur Klasse gehört. Point, Line, Polyline';


--
-- TOC entry 12031 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.epsg_code; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.epsg_code IS 'EPSG-Code in dem die Geometrien für diese Klasse vorliegen.';


--
-- TOC entry 12032 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.konvertierung_id; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.konvertierung_id IS 'Id der Konvertierung zu dem diese Regel gehört.';


--
-- TOC entry 12033 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN regeln.stelle_id; Type: COMMENT; Schema: xplankonverter; Owner: -
--

COMMENT ON COLUMN regeln.stelle_id IS 'Id der Stelle in der die Konvertierungsregel erstellt und angewendet werden kann.';


SET default_with_oids = false;

--
-- TOC entry 2439 (class 1259 OID 3296311)
-- Name: regeln_backup; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE regeln_backup (
    class_name character varying,
    factory enum_factory,
    sql text,
    name character varying,
    beschreibung text,
    geometrietyp enum_geometrie_typ,
    epsg_code integer,
    konvertierung_id integer,
    stelle_id integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    bereich_gml_id uuid,
    id integer,
    layer_id integer,
    bereiche text[]
);


--
-- TOC entry 334 (class 1259 OID 659869)
-- Name: regeln_regel_id_seq; Type: SEQUENCE; Schema: xplankonverter; Owner: -
--

CREATE SEQUENCE regeln_regel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12034 (class 0 OID 0)
-- Dependencies: 334
-- Name: regeln_regel_id_seq; Type: SEQUENCE OWNED BY; Schema: xplankonverter; Owner: -
--

ALTER SEQUENCE regeln_regel_id_seq OWNED BY regeln.id;


SET default_with_oids = true;

--
-- TOC entry 260 (class 1259 OID 409400)
-- Name: shapefiles; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE shapefiles (
    id integer NOT NULL,
    filename character varying,
    konvertierung_id integer,
    stelle_id integer,
    layer_id integer,
    epsg_code integer,
    datatype smallint
);


--
-- TOC entry 259 (class 1259 OID 409398)
-- Name: shapes_id_seq; Type: SEQUENCE; Schema: xplankonverter; Owner: -
--

CREATE SEQUENCE shapes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12035 (class 0 OID 0)
-- Dependencies: 259
-- Name: shapes_id_seq; Type: SEQUENCE OWNED BY; Schema: xplankonverter; Owner: -
--

ALTER SEQUENCE shapes_id_seq OWNED BY shapefiles.id;


--
-- TOC entry 804 (class 1259 OID 875159)
-- Name: validierungen; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE validierungen (
    id integer NOT NULL,
    name character varying NOT NULL,
    beschreibung text,
    functionsname character varying,
    msg_success character varying,
    msg_warning character varying,
    msg_error character varying,
    msg_correcture text
);


--
-- TOC entry 803 (class 1259 OID 875157)
-- Name: validierungen_id_seq; Type: SEQUENCE; Schema: xplankonverter; Owner: -
--

CREATE SEQUENCE validierungen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12036 (class 0 OID 0)
-- Dependencies: 803
-- Name: validierungen_id_seq; Type: SEQUENCE OWNED BY; Schema: xplankonverter; Owner: -
--

ALTER SEQUENCE validierungen_id_seq OWNED BY validierungen.id;


--
-- TOC entry 806 (class 1259 OID 875170)
-- Name: validierungsergebnisse; Type: TABLE; Schema: xplankonverter; Owner: -; Tablespace: 
--

CREATE TABLE validierungsergebnisse (
    id integer NOT NULL,
    konvertierung_id integer,
    validierung_id integer,
    status validierungsstatus,
    msg text,
    created_at timestamp without time zone,
    user_id integer,
    regel_id integer,
    shape_gid integer
);


--
-- TOC entry 805 (class 1259 OID 875168)
-- Name: validierungsergebnisse_id_seq; Type: SEQUENCE; Schema: xplankonverter; Owner: -
--

CREATE SEQUENCE validierungsergebnisse_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 12037 (class 0 OID 0)
-- Dependencies: 805
-- Name: validierungsergebnisse_id_seq; Type: SEQUENCE OWNED BY; Schema: xplankonverter; Owner: -
--

ALTER SEQUENCE validierungsergebnisse_id_seq OWNED BY validierungsergebnisse.id;


--
-- TOC entry 11860 (class 2604 OID 408048)
-- Name: id; Type: DEFAULT; Schema: xplankonverter; Owner: -
--

ALTER TABLE ONLY konvertierungen ALTER COLUMN id SET DEFAULT nextval('konvertierungen_id_seq'::regclass);


--
-- TOC entry 11870 (class 2604 OID 659871)
-- Name: id; Type: DEFAULT; Schema: xplankonverter; Owner: -
--

ALTER TABLE ONLY regeln ALTER COLUMN id SET DEFAULT nextval('regeln_regel_id_seq'::regclass);


--
-- TOC entry 11871 (class 2604 OID 409403)
-- Name: id; Type: DEFAULT; Schema: xplankonverter; Owner: -
--

ALTER TABLE ONLY shapefiles ALTER COLUMN id SET DEFAULT nextval('shapes_id_seq'::regclass);


--
-- TOC entry 11872 (class 2604 OID 875162)
-- Name: id; Type: DEFAULT; Schema: xplankonverter; Owner: -
--

ALTER TABLE ONLY validierungen ALTER COLUMN id SET DEFAULT nextval('validierungen_id_seq'::regclass);


--
-- TOC entry 11873 (class 2604 OID 875173)
-- Name: id; Type: DEFAULT; Schema: xplankonverter; Owner: -
--

ALTER TABLE ONLY validierungsergebnisse ALTER COLUMN id SET DEFAULT nextval('validierungsergebnisse_id_seq'::regclass);


--
-- TOC entry 11887 (class 2606 OID 897657)
-- Name: bundeslaender_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY bundeslaender
    ADD CONSTRAINT bundeslaender_pkey PRIMARY KEY (id);


--
-- TOC entry 11889 (class 2606 OID 4426033)
-- Name: inspire_regeln_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY inspire_regeln
    ADD CONSTRAINT inspire_regeln_pkey PRIMARY KEY (id);


--
-- TOC entry 11875 (class 2606 OID 408054)
-- Name: konvertierungen_id_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY konvertierungen
    ADD CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id);


--
-- TOC entry 11881 (class 2606 OID 834162)
-- Name: layer_colors_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY layer_colors
    ADD CONSTRAINT layer_colors_pkey PRIMARY KEY (name, geometrietyp);


--
-- TOC entry 11877 (class 2606 OID 659882)
-- Name: regeln_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY regeln
    ADD CONSTRAINT regeln_pkey PRIMARY KEY (id);


--
-- TOC entry 11879 (class 2606 OID 409408)
-- Name: shapes_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY shapefiles
    ADD CONSTRAINT shapes_pkey PRIMARY KEY (id);


--
-- TOC entry 11883 (class 2606 OID 875167)
-- Name: validierung_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY validierungen
    ADD CONSTRAINT validierung_pkey PRIMARY KEY (id);


--
-- TOC entry 11885 (class 2606 OID 875178)
-- Name: validierungsergebnisse_pkey; Type: CONSTRAINT; Schema: xplankonverter; Owner: -; Tablespace: 
--

ALTER TABLE ONLY validierungsergebnisse
    ADD CONSTRAINT validierungsergebnisse_pkey PRIMARY KEY (id);


--
-- TOC entry 11890 (class 2620 OID 807425)
-- Name: update_konvertierung_state; Type: TRIGGER; Schema: xplankonverter; Owner: -
--

CREATE TRIGGER update_konvertierung_state AFTER INSERT OR DELETE ON regeln FOR EACH ROW EXECUTE PROCEDURE update_konvertierung_state();

ALTER TABLE regeln DISABLE TRIGGER update_konvertierung_state;

-- INSERT INTO layer_colors Default colors

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