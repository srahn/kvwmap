
--- migration 2014-08-03 00:00:00

BEGIN;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 8 (class 2615 OID 879860)
-- Name: bauleitplanung; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA bauleitplanung;


SET search_path = bauleitplanung, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 586 (class 1259 OID 883230)
-- Dependencies: 8
-- Name: aemter; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE aemter (
    amtsnr integer,
    name character varying(255),
    amtsfrei integer,
    beschriftung character varying(255)
);


--
-- TOC entry 587 (class 1259 OID 883236)
-- Dependencies: 8
-- Name: b_plan_gebiete; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE b_plan_gebiete (
    plan_id integer NOT NULL,
    gebietstyp integer NOT NULL,
    flaeche numeric(18,2),
    kap_gemziel integer,
    kap_nachstell integer,
    konkretisierung integer
);


--
-- TOC entry 588 (class 1259 OID 883239)
-- Dependencies: 8
-- Name: b_plan_sondergebiete; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE b_plan_sondergebiete (
    plan_id integer,
    gebietstyp integer NOT NULL,
    flaeche numeric(18,2),
    kap1_gemziel integer,
    kap1_nachstell integer,
    kap2_gemziel integer,
    kap2_nachstell integer,
    konkretisierung integer
);


--
-- TOC entry 589 (class 1259 OID 883242)
-- Dependencies: 8
-- Name: b_plan_stammdaten; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE b_plan_stammdaten (
    plan_id integer NOT NULL,
    gkz integer,
    art character varying(50),
    pl_nr character varying(50),
    gemeinde_alt character varying(50),
    geltungsbereich numeric(18,2),
    bezeichnung character varying(100),
    aktuell character varying(1),
    lfd_rok_nr character varying(50),
    aktenzeichen character varying(255),
    kap_gemziel integer,
    kap_nachstell integer,
    datumeing date,
    datumzust date,
    datumabl date,
    datumgenehm date,
    datumbeka date,
    datumaufh date,
    erteilteaufl text,
    ert_hinweis text,
    ert_bemerkungen text
);


--
-- TOC entry 590 (class 1259 OID 883248)
-- Dependencies: 589 8
-- Name: b_plan_stammdaten_id_seq; Type: SEQUENCE; Schema: bauleitplanung; Owner: -
--

CREATE SEQUENCE b_plan_stammdaten_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 11488 (class 0 OID 0)
-- Dependencies: 590
-- Name: b_plan_stammdaten_id_seq; Type: SEQUENCE OWNED BY; Schema: bauleitplanung; Owner: -
--

ALTER SEQUENCE b_plan_stammdaten_id_seq OWNED BY b_plan_stammdaten.plan_id;


--
-- TOC entry 591 (class 1259 OID 883250)
-- Dependencies: 8
-- Name: gebietstypen; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE gebietstypen (
    id integer NOT NULL,
    typ character varying(100),
    art boolean,
    einheit character varying(30)
);


--
-- TOC entry 592 (class 1259 OID 883253)
-- Dependencies: 591 8
-- Name: gebietstypen_id_seq; Type: SEQUENCE; Schema: bauleitplanung; Owner: -
--

CREATE SEQUENCE gebietstypen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 11489 (class 0 OID 0)
-- Dependencies: 592
-- Name: gebietstypen_id_seq; Type: SEQUENCE OWNED BY; Schema: bauleitplanung; Owner: -
--

ALTER SEQUENCE gebietstypen_id_seq OWNED BY gebietstypen.id;


--
-- TOC entry 593 (class 1259 OID 883255)
-- Dependencies: 8
-- Name: gemeinden; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE gemeinden (
    gkz_alt integer,
    gkz integer,
    gemeinde character varying(255),
    ob integer,
    mb integer,
    nb integer,
    pr integer,
    kreis integer,
    amt_neu integer,
    amt_alt integer,
    zo integer,
    sur integer,
    dummy character(1)
);


--
-- TOC entry 594 (class 1259 OID 883258)
-- Dependencies: 8
-- Name: gemeinden2; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE gemeinden2 (
    gkz integer,
    kkz integer,
    prz integer,
    akz integer,
    gemeinde character varying(255),
    kreis character varying(255),
    planungsregion character varying(50),
    amt character varying(50),
    zentrort character varying(12),
    ordraum character varying(12),
    zoname character varying(50),
    plz character varying(255)
);


--
-- TOC entry 595 (class 1259 OID 883264)
-- Dependencies: 8
-- Name: konkretisierungen; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE konkretisierungen (
    id integer NOT NULL,
    gebiets_id integer,
    bezeichnung character varying(100),
    einheit character varying(30)
);


--
-- TOC entry 596 (class 1259 OID 883267)
-- Dependencies: 595 8
-- Name: konkretisierungen_id_seq; Type: SEQUENCE; Schema: bauleitplanung; Owner: -
--

CREATE SEQUENCE konkretisierungen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 11490 (class 0 OID 0)
-- Dependencies: 596
-- Name: konkretisierungen_id_seq; Type: SEQUENCE OWNED BY; Schema: bauleitplanung; Owner: -
--

ALTER SEQUENCE konkretisierungen_id_seq OWNED BY konkretisierungen.id;


--
-- TOC entry 597 (class 1259 OID 883269)
-- Dependencies: 8
-- Name: kreise; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE kreise (
    kkz integer,
    kreis character varying(255)
);


--
-- TOC entry 598 (class 1259 OID 883272)
-- Dependencies: 8
-- Name: planungsregionen; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE planungsregionen (
    prz integer,
    planungsregion character varying(50)
);


--
-- TOC entry 599 (class 1259 OID 883275)
-- Dependencies: 8
-- Name: stadtumlandraum; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE stadtumlandraum (
    surid integer,
    name character varying(50)
);


--
-- TOC entry 11481 (class 2604 OID 891887)
-- Dependencies: 590 589
-- Name: plan_id; Type: DEFAULT; Schema: bauleitplanung; Owner: -
--

ALTER TABLE ONLY b_plan_stammdaten ALTER COLUMN plan_id SET DEFAULT nextval('b_plan_stammdaten_id_seq'::regclass);


--
-- TOC entry 11482 (class 2604 OID 891888)
-- Dependencies: 592 591
-- Name: id; Type: DEFAULT; Schema: bauleitplanung; Owner: -
--

ALTER TABLE ONLY gebietstypen ALTER COLUMN id SET DEFAULT nextval('gebietstypen_id_seq'::regclass);


--
-- TOC entry 11483 (class 2604 OID 891889)
-- Dependencies: 596 595
-- Name: id; Type: DEFAULT; Schema: bauleitplanung; Owner: -
--

ALTER TABLE ONLY konkretisierungen ALTER COLUMN id SET DEFAULT nextval('konkretisierungen_id_seq'::regclass);


--
-- TOC entry 11485 (class 2606 OID 892518)
-- Dependencies: 589 589
-- Name: b_plan_stammdaten_pkey; Type: CONSTRAINT; Schema: bauleitplanung; Owner: -; Tablespace: 
--

ALTER TABLE ONLY b_plan_stammdaten
    ADD CONSTRAINT b_plan_stammdaten_pkey PRIMARY KEY (plan_id);

COMMIT;

