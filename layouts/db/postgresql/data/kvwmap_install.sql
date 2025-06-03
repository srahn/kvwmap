--
-- PostgreSQL database dump
--

-- Dumped from database version 15.2 (Debian 15.2-1.pgdg110+1)
-- Dumped by pg_dump version 15.13

-- Started on 2025-06-03 14:01:00 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 10 (class 2615 OID 1301795)
-- Name: kvwmap; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA kvwmap;


--
-- TOC entry 1129 (class 1255 OID 1303036)
-- Name: sha1(text); Type: FUNCTION; Schema: kvwmap; Owner: -
--

CREATE FUNCTION kvwmap.sha1(str text) RETURNS text
    LANGUAGE sql
    AS $$

SELECT encode(digest(str::bytea, 'sha1'), 'hex');

$$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 242 (class 1259 OID 1301797)
-- Name: belated_files; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.belated_files (
    id integer NOT NULL,
    user_id integer NOT NULL,
    layer_id integer NOT NULL,
    dataset_id integer NOT NULL,
    attribute_name character varying(70) NOT NULL,
    name character varying(150) NOT NULL,
    size integer NOT NULL,
    lastmodified bigint NOT NULL,
    file text NOT NULL
);


--
-- TOC entry 241 (class 1259 OID 1301796)
-- Name: belated_files_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.belated_files_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6094 (class 0 OID 0)
-- Dependencies: 241
-- Name: belated_files_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.belated_files_id_seq OWNED BY kvwmap.belated_files.id;


--
-- TOC entry 244 (class 1259 OID 1301804)
-- Name: classes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.classes (
    class_id integer NOT NULL,
    name character varying(255) NOT NULL,
    name_low_german character varying(255) DEFAULT NULL::character varying,
    name_english character varying(255) DEFAULT NULL::character varying,
    name_polish character varying(255) DEFAULT NULL::character varying,
    name_vietnamese character varying(255) DEFAULT NULL::character varying,
    layer_id integer DEFAULT 0 NOT NULL,
    expression text,
    drawingorder integer,
    legendorder integer,
    text character varying(255) DEFAULT NULL::character varying,
    classification character varying(255) DEFAULT NULL::character varying,
    legendgraphic character varying(255) DEFAULT NULL::character varying,
    legendimagewidth integer,
    legendimageheight integer
);


--
-- TOC entry 243 (class 1259 OID 1301803)
-- Name: classes_class_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.classes_class_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6095 (class 0 OID 0)
-- Dependencies: 243
-- Name: classes_class_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.classes_class_id_seq OWNED BY kvwmap.classes.class_id;


--
-- TOC entry 246 (class 1259 OID 1301819)
-- Name: colors; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.colors (
    id integer NOT NULL,
    name character varying(30) DEFAULT NULL::character varying,
    red smallint DEFAULT 0 NOT NULL,
    green smallint DEFAULT 0 NOT NULL,
    blue smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 245 (class 1259 OID 1301818)
-- Name: colors_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.colors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6096 (class 0 OID 0)
-- Dependencies: 245
-- Name: colors_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.colors_id_seq OWNED BY kvwmap.colors.id;


--
-- TOC entry 248 (class 1259 OID 1301828)
-- Name: config; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.config (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    prefix character varying(100) NOT NULL,
    value text NOT NULL,
    description text,
    type character varying(20) NOT NULL,
    "group" character varying(50) NOT NULL,
    plugin character varying(50) DEFAULT NULL::character varying,
    saved smallint NOT NULL,
    editable integer DEFAULT 2
);


--
-- TOC entry 247 (class 1259 OID 1301827)
-- Name: config_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.config_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6097 (class 0 OID 0)
-- Dependencies: 247
-- Name: config_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.config_id_seq OWNED BY kvwmap.config.id;


--
-- TOC entry 250 (class 1259 OID 1301837)
-- Name: connections; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.connections (
    id integer NOT NULL,
    name character varying(150) DEFAULT 'kvwmapsp'::character varying NOT NULL,
    host character varying(50) DEFAULT 'pgsql'::character varying,
    port integer DEFAULT 5432,
    dbname character varying(150) DEFAULT 'kvwmapsp'::character varying NOT NULL,
    "user" character varying(150) DEFAULT 'kvwmap'::character varying,
    password character varying(150) DEFAULT 'KvwMapPW1'::character varying
);


--
-- TOC entry 249 (class 1259 OID 1301836)
-- Name: connections_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.connections_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6098 (class 0 OID 0)
-- Dependencies: 249
-- Name: connections_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.connections_id_seq OWNED BY kvwmap.connections.id;


--
-- TOC entry 252 (class 1259 OID 1301850)
-- Name: cron_jobs; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.cron_jobs (
    id integer NOT NULL,
    bezeichnung character varying(255) NOT NULL,
    beschreibung text,
    "time" character varying(25) DEFAULT '0 6 1 * *'::character varying NOT NULL,
    query text,
    function character varying(255) DEFAULT NULL::character varying,
    url character varying(1000) DEFAULT NULL::character varying,
    user_id integer,
    stelle_id integer,
    aktiv smallint DEFAULT 0 NOT NULL,
    dbname character varying(68) DEFAULT NULL::character varying,
    "user" character varying DEFAULT 'gisadmin'::character varying NOT NULL
);


--
-- TOC entry 251 (class 1259 OID 1301849)
-- Name: cron_jobs_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.cron_jobs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6099 (class 0 OID 0)
-- Dependencies: 251
-- Name: cron_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.cron_jobs_id_seq OWNED BY kvwmap.cron_jobs.id;


--
-- TOC entry 254 (class 1259 OID 1301863)
-- Name: datasources; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.datasources (
    id integer NOT NULL,
    name character varying(100) DEFAULT NULL::character varying,
    beschreibung text NOT NULL
);


--
-- TOC entry 253 (class 1259 OID 1301862)
-- Name: datasources_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.datasources_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6100 (class 0 OID 0)
-- Dependencies: 253
-- Name: datasources_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.datasources_id_seq OWNED BY kvwmap.datasources.id;


--
-- TOC entry 257 (class 1259 OID 1301877)
-- Name: datatype_attributes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.datatype_attributes (
    layer_id integer NOT NULL,
    datatype_id integer NOT NULL,
    name character varying(255) NOT NULL,
    real_name character varying(255) DEFAULT NULL::character varying,
    tablename character varying(100) DEFAULT NULL::character varying,
    table_alias_name character varying(100) DEFAULT NULL::character varying,
    type character varying(30) DEFAULT NULL::character varying,
    geometrytype character varying(20) DEFAULT NULL::character varying,
    constraints character varying(255) DEFAULT NULL::character varying,
    nullable smallint,
    length integer,
    decimal_length integer,
    "default" character varying(255) DEFAULT NULL::character varying,
    form_element_type character varying DEFAULT 'Text'::character varying NOT NULL,
    options text,
    alias character varying(255) DEFAULT NULL::character varying,
    alias_low_german character varying(100) DEFAULT NULL::character varying,
    alias_english character varying(100) DEFAULT NULL::character varying,
    alias_polish character varying(100) DEFAULT NULL::character varying,
    alias_vietnamese character varying(100) DEFAULT NULL::character varying,
    tooltip character varying(255) DEFAULT NULL::character varying,
    "group" character varying(255) DEFAULT NULL::character varying,
    raster_visibility smallint,
    mandatory smallint,
    quicksearch smallint,
    "order" integer,
    privileg smallint DEFAULT 0,
    query_tooltip smallint DEFAULT 0,
    visible smallint DEFAULT 1 NOT NULL,
    vcheck_attribute character varying(255) DEFAULT NULL::character varying,
    vcheck_operator character varying(4) DEFAULT NULL::character varying,
    vcheck_value text,
    arrangement smallint DEFAULT 0 NOT NULL,
    labeling smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 256 (class 1259 OID 1301871)
-- Name: datatypes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.datatypes (
    id integer NOT NULL,
    name character varying(58) DEFAULT NULL::character varying,
    schema character varying(58) DEFAULT 'public'::character varying NOT NULL,
    connection_id bigint
);


--
-- TOC entry 255 (class 1259 OID 1301870)
-- Name: datatypes_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.datatypes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6101 (class 0 OID 0)
-- Dependencies: 255
-- Name: datatypes_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.datatypes_id_seq OWNED BY kvwmap.datatypes.id;


--
-- TOC entry 259 (class 1259 OID 1301905)
-- Name: datendrucklayouts; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.datendrucklayouts (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    layer_id integer NOT NULL,
    format character varying(10) DEFAULT 'A4 hoch'::character varying NOT NULL,
    bgsrc character varying(255) DEFAULT NULL::character varying,
    bgposx integer,
    bgposy integer,
    bgwidth integer,
    bgheight integer,
    dateposx integer,
    dateposy integer,
    datesize integer,
    userposx integer,
    userposy integer,
    usersize integer,
    font_date character varying(255) DEFAULT NULL::character varying,
    font_user character varying(255) DEFAULT NULL::character varying,
    type smallint DEFAULT 0 NOT NULL,
    margin_top integer DEFAULT 40 NOT NULL,
    margin_bottom integer DEFAULT 30 NOT NULL,
    margin_left integer DEFAULT 0 NOT NULL,
    margin_right integer DEFAULT 0 NOT NULL,
    dont_print_empty smallint,
    gap integer DEFAULT 20 NOT NULL,
    no_record_splitting smallint DEFAULT 0 NOT NULL,
    columns smallint DEFAULT 0 NOT NULL,
    filename character varying(255) DEFAULT NULL::character varying,
    use_previews smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 258 (class 1259 OID 1301904)
-- Name: datendrucklayouts_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.datendrucklayouts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6102 (class 0 OID 0)
-- Dependencies: 258
-- Name: datendrucklayouts_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.datendrucklayouts_id_seq OWNED BY kvwmap.datendrucklayouts.id;


--
-- TOC entry 260 (class 1259 OID 1301925)
-- Name: ddl2freilinien; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl2freilinien (
    ddl_id integer NOT NULL,
    line_id integer NOT NULL
);


--
-- TOC entry 261 (class 1259 OID 1301928)
-- Name: ddl2freirechtecke; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl2freirechtecke (
    ddl_id integer NOT NULL,
    rect_id integer NOT NULL
);


--
-- TOC entry 262 (class 1259 OID 1301931)
-- Name: ddl2freitexte; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl2freitexte (
    ddl_id integer NOT NULL,
    freitext_id integer NOT NULL
);


--
-- TOC entry 263 (class 1259 OID 1301934)
-- Name: ddl2stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl2stelle (
    stelle_id integer NOT NULL,
    ddl_id integer NOT NULL
);


--
-- TOC entry 265 (class 1259 OID 1301938)
-- Name: ddl_colors; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl_colors (
    id integer NOT NULL,
    red smallint DEFAULT 0 NOT NULL,
    green smallint DEFAULT 0 NOT NULL,
    blue smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 264 (class 1259 OID 1301937)
-- Name: ddl_colors_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.ddl_colors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6103 (class 0 OID 0)
-- Dependencies: 264
-- Name: ddl_colors_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.ddl_colors_id_seq OWNED BY kvwmap.ddl_colors.id;


--
-- TOC entry 266 (class 1259 OID 1301945)
-- Name: ddl_elemente; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.ddl_elemente (
    ddl_id integer NOT NULL,
    name character varying(255) NOT NULL,
    xpos real,
    ypos real,
    offset_attribute character varying(255) DEFAULT NULL::character varying,
    width integer,
    border smallint,
    font character varying(255) DEFAULT NULL::character varying,
    fontsize integer,
    label text,
    margin text
);


--
-- TOC entry 268 (class 1259 OID 1301953)
-- Name: druckausschnitte; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckausschnitte (
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    epsg_code integer,
    center_x double precision NOT NULL,
    center_y double precision NOT NULL,
    print_scale integer NOT NULL,
    angle integer NOT NULL,
    frame_id integer NOT NULL
);


--
-- TOC entry 267 (class 1259 OID 1301952)
-- Name: druckausschnitte_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckausschnitte_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6104 (class 0 OID 0)
-- Dependencies: 267
-- Name: druckausschnitte_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckausschnitte_id_seq OWNED BY kvwmap.druckausschnitte.id;


--
-- TOC entry 270 (class 1259 OID 1301958)
-- Name: druckfreibilder; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckfreibilder (
    id integer NOT NULL,
    src character varying(255) DEFAULT ''::character varying NOT NULL
);


--
-- TOC entry 269 (class 1259 OID 1301957)
-- Name: druckfreibilder_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckfreibilder_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6105 (class 0 OID 0)
-- Dependencies: 269
-- Name: druckfreibilder_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckfreibilder_id_seq OWNED BY kvwmap.druckfreibilder.id;


--
-- TOC entry 272 (class 1259 OID 1301964)
-- Name: druckfreilinien; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckfreilinien (
    id integer NOT NULL,
    posx integer NOT NULL,
    posy integer NOT NULL,
    endposx integer NOT NULL,
    endposy integer NOT NULL,
    breite double precision NOT NULL,
    offset_attribute_start character varying(255) DEFAULT NULL::character varying,
    offset_attribute_end character varying(255) DEFAULT NULL::character varying,
    type smallint
);


--
-- TOC entry 271 (class 1259 OID 1301963)
-- Name: druckfreilinien_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckfreilinien_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6106 (class 0 OID 0)
-- Dependencies: 271
-- Name: druckfreilinien_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckfreilinien_id_seq OWNED BY kvwmap.druckfreilinien.id;


--
-- TOC entry 274 (class 1259 OID 1301973)
-- Name: druckfreirechtecke; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckfreirechtecke (
    id integer NOT NULL,
    posx integer NOT NULL,
    posy integer NOT NULL,
    endposx integer NOT NULL,
    endposy integer NOT NULL,
    breite double precision NOT NULL,
    color integer,
    offset_attribute_start character varying(255) DEFAULT NULL::character varying,
    offset_attribute_end character varying(255) DEFAULT NULL::character varying,
    type smallint
);


--
-- TOC entry 273 (class 1259 OID 1301972)
-- Name: druckfreirechtecke_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckfreirechtecke_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6107 (class 0 OID 0)
-- Dependencies: 273
-- Name: druckfreirechtecke_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckfreirechtecke_id_seq OWNED BY kvwmap.druckfreirechtecke.id;


--
-- TOC entry 276 (class 1259 OID 1301982)
-- Name: druckfreitexte; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckfreitexte (
    id integer NOT NULL,
    text text,
    posx integer NOT NULL,
    posy integer NOT NULL,
    offset_attribute character varying(255) DEFAULT NULL::character varying,
    size integer NOT NULL,
    width integer,
    border smallint,
    font character varying(255) NOT NULL,
    angle integer,
    type smallint
);


--
-- TOC entry 275 (class 1259 OID 1301981)
-- Name: druckfreitexte_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckfreitexte_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6108 (class 0 OID 0)
-- Dependencies: 275
-- Name: druckfreitexte_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckfreitexte_id_seq OWNED BY kvwmap.druckfreitexte.id;


--
-- TOC entry 278 (class 1259 OID 1301990)
-- Name: druckrahmen; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckrahmen (
    name character varying(255) NOT NULL,
    id integer NOT NULL,
    dhk_call character varying(10) DEFAULT NULL::character varying,
    headsrc character varying(255) NOT NULL,
    headposx integer NOT NULL,
    headposy integer NOT NULL,
    headwidth integer NOT NULL,
    headheight integer NOT NULL,
    mapposx integer NOT NULL,
    mapposy integer NOT NULL,
    mapwidth integer NOT NULL,
    mapheight integer NOT NULL,
    refmapsrc character varying(255) DEFAULT NULL::character varying,
    refmapfile character varying(255) DEFAULT NULL::character varying,
    refmapposx integer,
    refmapposy integer,
    refmapwidth integer,
    refmapheight integer,
    refposx integer,
    refposy integer,
    refwidth integer,
    refheight integer,
    refzoom integer,
    dateposx integer,
    dateposy integer,
    datesize integer,
    scaleposx integer,
    scaleposy integer,
    scalesize integer,
    scalebarposx integer,
    scalebarposy integer,
    oscaleposx integer,
    oscaleposy integer,
    oscalesize integer,
    lageposx integer,
    lageposy integer,
    lagesize integer,
    gemeindeposx integer,
    gemeindeposy integer,
    gemeindesize integer,
    gemarkungposx integer,
    gemarkungposy integer,
    gemarkungsize integer,
    flurposx integer,
    flurposy integer,
    flursize integer,
    flurstposx integer,
    flurstposy integer,
    flurstsize integer,
    legendposx integer,
    legendposy integer,
    legendsize integer,
    arrowposx integer,
    arrowposy integer,
    arrowlength integer,
    userposx integer,
    userposy integer,
    usersize integer,
    watermarkposx integer,
    watermarkposy integer,
    watermark character varying(255) DEFAULT ''::character varying,
    watermarksize integer,
    watermarkangle integer,
    watermarktransparency integer,
    variable_freetexts smallint,
    format character varying(10) DEFAULT 'A4hoch'::character varying NOT NULL,
    preis integer,
    font_date character varying(255) DEFAULT NULL::character varying,
    font_scale character varying(255) DEFAULT NULL::character varying,
    font_lage character varying(255) DEFAULT NULL::character varying,
    font_gemeinde character varying(255) DEFAULT NULL::character varying,
    font_gemarkung character varying(255) DEFAULT NULL::character varying,
    font_flur character varying(255) DEFAULT NULL::character varying,
    font_flurst character varying(255) DEFAULT NULL::character varying,
    font_oscale character varying(255) DEFAULT NULL::character varying,
    font_legend character varying(255) DEFAULT NULL::character varying,
    font_watermark character varying(255) DEFAULT NULL::character varying,
    font_user character varying(255) DEFAULT NULL::character varying
);


--
-- TOC entry 279 (class 1259 OID 1302012)
-- Name: druckrahmen2freibilder; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckrahmen2freibilder (
    druckrahmen_id integer NOT NULL,
    freibild_id integer NOT NULL,
    posx integer NOT NULL,
    posy integer NOT NULL,
    width integer,
    height integer,
    angle integer
);


--
-- TOC entry 280 (class 1259 OID 1302015)
-- Name: druckrahmen2freitexte; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckrahmen2freitexte (
    druckrahmen_id integer NOT NULL,
    freitext_id integer NOT NULL
);


--
-- TOC entry 281 (class 1259 OID 1302018)
-- Name: druckrahmen2stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.druckrahmen2stelle (
    stelle_id integer NOT NULL,
    druckrahmen_id integer NOT NULL
);


--
-- TOC entry 277 (class 1259 OID 1301989)
-- Name: druckrahmen_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.druckrahmen_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6109 (class 0 OID 0)
-- Dependencies: 277
-- Name: druckrahmen_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.druckrahmen_id_seq OWNED BY kvwmap.druckrahmen.id;


--
-- TOC entry 282 (class 1259 OID 1302021)
-- Name: invitations; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.invitations (
    token character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL,
    anrede character varying(10) DEFAULT NULL::character varying,
    name character varying(255) NOT NULL,
    vorname character varying(255) NOT NULL,
    loginname character varying(100) NOT NULL,
    inviter_id integer,
    completed timestamp without time zone
);


--
-- TOC entry 284 (class 1259 OID 1302029)
-- Name: labels; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.labels (
    label_id integer NOT NULL,
    font character varying(25) DEFAULT 'arial'::character varying NOT NULL,
    type smallint,
    color character varying(11) DEFAULT ''::character varying NOT NULL,
    outlinecolor character varying(11) DEFAULT NULL::character varying,
    shadowcolor character varying(11) DEFAULT NULL::character varying,
    shadowsizex smallint,
    shadowsizey smallint,
    backgroundcolor character varying(11) DEFAULT NULL::character varying,
    backgroundshadowcolor character varying(11) DEFAULT NULL::character varying,
    backgroundshadowsizex smallint,
    backgroundshadowsizey smallint,
    size smallint,
    minsize smallint,
    maxsize smallint,
    minscale integer,
    maxscale integer,
    "position" smallint,
    offsetx character varying(50) DEFAULT NULL::character varying,
    offsety character varying(50) DEFAULT NULL::character varying,
    angle character varying(50) DEFAULT NULL::character varying,
    anglemode smallint,
    buffer smallint,
    minfeaturesize integer,
    maxfeaturesize integer,
    partials smallint,
    maxlength smallint,
    repeatdistance integer,
    wrap smallint,
    the_force smallint,
    text character varying(50) DEFAULT NULL::character varying
);


--
-- TOC entry 283 (class 1259 OID 1302028)
-- Name: labels_label_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.labels_label_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6110 (class 0 OID 0)
-- Dependencies: 283
-- Name: labels_label_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.labels_label_id_seq OWNED BY kvwmap.labels.label_id;


--
-- TOC entry 286 (class 1259 OID 1302044)
-- Name: layer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer (
    layer_id integer NOT NULL,
    name character varying(255) NOT NULL,
    name_low_german character varying(100) DEFAULT NULL::character varying,
    name_english character varying(100) DEFAULT NULL::character varying,
    name_polish character varying(100) DEFAULT NULL::character varying,
    name_vietnamese character varying(100) DEFAULT NULL::character varying,
    alias character varying(255) DEFAULT NULL::character varying,
    datentyp smallint DEFAULT 2 NOT NULL,
    gruppe integer DEFAULT 0 NOT NULL,
    pfad text,
    maintable character varying(255) DEFAULT NULL::character varying,
    oid character varying(63) DEFAULT 'id'::character varying,
    identifier_text character varying(50) DEFAULT NULL::character varying,
    maintable_is_view smallint DEFAULT 0 NOT NULL,
    data text,
    schema character varying(50) DEFAULT NULL::character varying,
    geom_column character varying(68) DEFAULT NULL::character varying,
    document_path text,
    document_url text,
    ddl_attribute character varying(255) DEFAULT NULL::character varying,
    tileindex character varying(100) DEFAULT NULL::character varying,
    tileitem character varying(100) DEFAULT NULL::character varying,
    labelangleitem character varying(25) DEFAULT NULL::character varying,
    labelitem character varying(100) DEFAULT NULL::character varying,
    labelmaxscale integer,
    labelminscale integer,
    labelrequires character varying(255) DEFAULT NULL::character varying,
    postlabelcache smallint DEFAULT 0 NOT NULL,
    connection text NOT NULL,
    connection_id bigint,
    printconnection text,
    connectiontype smallint DEFAULT 0,
    classitem character varying(100) DEFAULT NULL::character varying,
    styleitem character varying(100) DEFAULT NULL::character varying,
    classification character varying(50) DEFAULT NULL::character varying,
    cluster_maxdistance integer,
    tolerance integer DEFAULT 3 NOT NULL,
    toleranceunits character varying DEFAULT 'pixels'::character varying NOT NULL,
    sizeunits smallint,
    epsg_code character varying(6) DEFAULT '2398'::character varying,
    template character varying(255) DEFAULT NULL::character varying,
    max_query_rows integer,
    queryable boolean DEFAULT false NOT NULL,
    use_geom smallint DEFAULT 1 NOT NULL,
    transparency smallint,
    drawingorder integer DEFAULT 0 NOT NULL,
    legendorder integer,
    minscale integer,
    maxscale integer,
    symbolscale integer,
    offsite character varying(11) DEFAULT NULL::character varying,
    requires integer,
    ows_srs character varying(255) DEFAULT 'EPSG:2398'::character varying NOT NULL,
    wms_name character varying(255) DEFAULT NULL::character varying,
    wms_keywordlist text,
    wms_server_version character varying(8) DEFAULT '1.1.0'::character varying NOT NULL,
    wms_format character varying(50) DEFAULT 'image/png'::character varying NOT NULL,
    wms_connectiontimeout integer DEFAULT 60 NOT NULL,
    wms_auth_username character varying(50) DEFAULT NULL::character varying,
    wms_auth_password character varying(50) DEFAULT NULL::character varying,
    wfs_geom character varying(100) DEFAULT NULL::character varying,
    write_mapserver_templates character varying,
    selectiontype character varying(20) DEFAULT NULL::character varying,
    querymap boolean DEFAULT false NOT NULL,
    logconsume boolean DEFAULT false NOT NULL,
    processing character varying(255) DEFAULT NULL::character varying,
    kurzbeschreibung text,
    datasource integer,
    dataowner_name text,
    dataowner_email character varying(100) DEFAULT NULL::character varying,
    dataowner_tel character varying(50) DEFAULT NULL::character varying,
    uptodateness character varying(100) DEFAULT NULL::character varying,
    updatecycle character varying(100) DEFAULT NULL::character varying,
    metalink character varying(255) DEFAULT NULL::character varying,
    terms_of_use_link character varying(255) DEFAULT NULL::character varying,
    icon character varying(255) DEFAULT NULL::character varying,
    privileg smallint DEFAULT 0 NOT NULL,
    export_privileg smallint DEFAULT 1 NOT NULL,
    status character varying(255) DEFAULT NULL::character varying,
    trigger_function character varying(255) DEFAULT NULL::character varying,
    sync boolean DEFAULT false NOT NULL,
    editable smallint DEFAULT 1 NOT NULL,
    listed smallint DEFAULT 1 NOT NULL,
    duplicate_from_layer_id integer,
    duplicate_criterion character varying(255) DEFAULT NULL::character varying,
    shared_from integer,
    version character varying(10) DEFAULT '1.0.0'::character varying NOT NULL,
    comment text,
    vector_tile_url character varying(255) DEFAULT NULL::character varying,
    cluster_option smallint DEFAULT 1 NOT NULL
);


--
-- TOC entry 287 (class 1259 OID 1302112)
-- Name: layer_attributes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_attributes (
    layer_id integer NOT NULL,
    name character varying(255) NOT NULL,
    real_name text,
    tablename character varying(100) DEFAULT NULL::character varying,
    table_alias_name character varying(100) DEFAULT NULL::character varying,
    schema character varying(100) DEFAULT NULL::character varying,
    type character varying(30) DEFAULT NULL::character varying,
    geometrytype character varying(20) DEFAULT NULL::character varying,
    constraints text,
    saveable smallint,
    nullable smallint,
    length integer,
    decimal_length integer,
    "default" character varying(255) DEFAULT NULL::character varying,
    form_element_type character varying DEFAULT 'Text'::character varying NOT NULL,
    options text,
    alias character varying(255) DEFAULT NULL::character varying,
    alias_low_german character varying(100) DEFAULT NULL::character varying,
    alias_english character varying(100) DEFAULT NULL::character varying,
    alias_polish character varying(100) DEFAULT NULL::character varying,
    alias_vietnamese character varying(100) DEFAULT NULL::character varying,
    tooltip text,
    "group" character varying(255) DEFAULT NULL::character varying,
    tab character varying(255) DEFAULT NULL::character varying,
    arrangement smallint DEFAULT 0 NOT NULL,
    labeling smallint DEFAULT 0 NOT NULL,
    raster_visibility smallint,
    dont_use_for_new smallint,
    mandatory smallint,
    quicksearch smallint,
    visible smallint DEFAULT 1 NOT NULL,
    kvp smallint DEFAULT 0 NOT NULL,
    vcheck_attribute character varying(255) DEFAULT NULL::character varying,
    vcheck_operator character varying(4) DEFAULT NULL::character varying,
    vcheck_value text,
    "order" integer,
    privileg smallint DEFAULT 0,
    query_tooltip smallint DEFAULT 0
);


--
-- TOC entry 288 (class 1259 OID 1302139)
-- Name: layer_attributes2rolle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_attributes2rolle (
    layer_id integer NOT NULL,
    attributename character varying(255) NOT NULL,
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    switchable smallint DEFAULT 1 NOT NULL,
    switched_on smallint DEFAULT 1 NOT NULL,
    sortable smallint DEFAULT 1 NOT NULL,
    sort_order integer DEFAULT 1 NOT NULL,
    sort_direction character varying DEFAULT 'asc'::character varying NOT NULL
);


--
-- TOC entry 289 (class 1259 OID 1302149)
-- Name: layer_attributes2stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_attributes2stelle (
    layer_id integer NOT NULL,
    attributename character varying(255) NOT NULL,
    stelle_id integer NOT NULL,
    privileg smallint NOT NULL,
    tooltip smallint DEFAULT 0
);


--
-- TOC entry 291 (class 1259 OID 1302154)
-- Name: layer_charts; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_charts (
    id integer NOT NULL,
    layer_id integer NOT NULL,
    title character varying(255) DEFAULT NULL::character varying,
    type character varying DEFAULT 'bar'::character varying NOT NULL,
    aggregate_function character varying,
    value_attribute_label character varying(100) DEFAULT NULL::character varying,
    value_attribute_name character varying(65) DEFAULT NULL::character varying,
    label_attribute_name character varying(65) DEFAULT NULL::character varying,
    beschreibung text NOT NULL,
    breite character varying(255) DEFAULT '100%'::character varying NOT NULL
);


--
-- TOC entry 290 (class 1259 OID 1302153)
-- Name: layer_charts_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.layer_charts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6111 (class 0 OID 0)
-- Dependencies: 290
-- Name: layer_charts_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.layer_charts_id_seq OWNED BY kvwmap.layer_charts.id;


--
-- TOC entry 292 (class 1259 OID 1302166)
-- Name: layer_datasources; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_datasources (
    layer_id integer NOT NULL,
    datasource_id integer NOT NULL,
    sortorder integer
);


--
-- TOC entry 293 (class 1259 OID 1302169)
-- Name: layer_labelitems; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_labelitems (
    layer_id integer NOT NULL,
    name character varying(100) NOT NULL,
    alias character varying(100) DEFAULT NULL::character varying,
    "order" integer NOT NULL
);


--
-- TOC entry 285 (class 1259 OID 1302043)
-- Name: layer_layer_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.layer_layer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6112 (class 0 OID 0)
-- Dependencies: 285
-- Name: layer_layer_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.layer_layer_id_seq OWNED BY kvwmap.layer.layer_id;


--
-- TOC entry 294 (class 1259 OID 1302173)
-- Name: layer_parameter; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.layer_parameter (
    id integer NOT NULL,
    key character varying(255) NOT NULL,
    alias character varying(255) NOT NULL,
    default_value character varying(255) NOT NULL,
    options_sql text NOT NULL
);


--
-- TOC entry 295 (class 1259 OID 1302178)
-- Name: migrations; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.migrations (
    component character varying(50) NOT NULL,
    type character varying NOT NULL,
    filename character varying(255) NOT NULL,
    comment text
);


--
-- TOC entry 297 (class 1259 OID 1302184)
-- Name: notifications; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.notifications (
    id integer NOT NULL,
    notification text,
    stellen_filter text,
    user_filter text,
    veroeffentlichungsdatum date,
    ablaufdatum date
);


--
-- TOC entry 296 (class 1259 OID 1302183)
-- Name: notifications_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.notifications_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6113 (class 0 OID 0)
-- Dependencies: 296
-- Name: notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.notifications_id_seq OWNED BY kvwmap.notifications.id;


--
-- TOC entry 299 (class 1259 OID 1302191)
-- Name: referenzkarten; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.referenzkarten (
    id integer NOT NULL,
    name character varying(100) DEFAULT ''::character varying NOT NULL,
    dateiname character varying(100) DEFAULT ''::character varying NOT NULL,
    epsg_code integer DEFAULT 2398 NOT NULL,
    minx real DEFAULT 0 NOT NULL,
    miny real DEFAULT 0 NOT NULL,
    maxx real DEFAULT 0 NOT NULL,
    maxy real DEFAULT 0 NOT NULL,
    width smallint DEFAULT 0 NOT NULL,
    height smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 298 (class 1259 OID 1302190)
-- Name: referenzkarten_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.referenzkarten_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6114 (class 0 OID 0)
-- Dependencies: 298
-- Name: referenzkarten_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.referenzkarten_id_seq OWNED BY kvwmap.referenzkarten.id;


--
-- TOC entry 300 (class 1259 OID 1302204)
-- Name: rolle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle (
    user_id integer DEFAULT 0 NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL,
    nimagewidth smallint DEFAULT 800 NOT NULL,
    nimageheight smallint DEFAULT 600 NOT NULL,
    auto_map_resize smallint DEFAULT 1 NOT NULL,
    minx real DEFAULT 201165 NOT NULL,
    miny real DEFAULT 5867815 NOT NULL,
    maxx real DEFAULT 77900 NOT NULL,
    maxy real DEFAULT 6081068 NOT NULL,
    nzoomfactor integer DEFAULT 2 NOT NULL,
    selectedbutton character varying(20) DEFAULT 'zoomin'::character varying NOT NULL,
    epsg_code character varying(6) DEFAULT '25833'::character varying,
    epsg_code2 character varying(6) DEFAULT NULL::character varying,
    coordtype character varying DEFAULT 'dec'::character varying NOT NULL,
    active_frame integer,
    last_time_id timestamp without time zone,
    gui character varying(100) DEFAULT 'layouts/gui.php'::character varying NOT NULL,
    language character varying DEFAULT 'german'::character varying NOT NULL,
    hidemenue boolean DEFAULT false NOT NULL,
    hidelegend boolean DEFAULT false NOT NULL,
    tooltipquery smallint DEFAULT 0 NOT NULL,
    buttons character varying(255) DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,punktfang'::character varying,
    geom_buttons character varying(255) DEFAULT 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure'::character varying,
    scrollposition integer DEFAULT 0 NOT NULL,
    result_color integer DEFAULT 1,
    result_hatching smallint DEFAULT 0 NOT NULL,
    result_transparency smallint DEFAULT 60 NOT NULL,
    always_draw smallint,
    runningcoords smallint DEFAULT 0 NOT NULL,
    showmapfunctions smallint DEFAULT 1 NOT NULL,
    showlayeroptions smallint DEFAULT 1 NOT NULL,
    showrollenfilter smallint DEFAULT 0 NOT NULL,
    singlequery smallint DEFAULT 1 NOT NULL,
    querymode smallint DEFAULT 0 NOT NULL,
    geom_edit_first smallint DEFAULT 0 NOT NULL,
    overlayx integer DEFAULT 400 NOT NULL,
    overlayy integer DEFAULT 150 NOT NULL,
    hist_timestamp timestamp without time zone,
    instant_reload smallint DEFAULT 1 NOT NULL,
    menu_auto_close smallint DEFAULT 0 NOT NULL,
    visually_impaired smallint DEFAULT 0 NOT NULL,
    font_size_factor real DEFAULT 1 NOT NULL,
    layer_params text,
    menue_buttons smallint DEFAULT 0 NOT NULL,
    legendtype smallint DEFAULT 0 NOT NULL,
    print_legend_separate smallint DEFAULT 0 NOT NULL,
    print_scale character varying(11) DEFAULT 'auto'::character varying NOT NULL,
    immer_weiter_erfassen smallint DEFAULT 0,
    upload_only_file_metadata smallint DEFAULT 0,
    redline_text_color character varying(7) DEFAULT '#ff0000'::character varying NOT NULL,
    redline_font_family character varying(25) DEFAULT 'Arial'::character varying NOT NULL,
    redline_font_size integer DEFAULT 16 NOT NULL,
    redline_font_weight character varying(25) DEFAULT 'bold'::character varying NOT NULL,
    dataset_operations_position character varying DEFAULT 'unten'::character varying NOT NULL,
    last_query_layer integer
);


--
-- TOC entry 303 (class 1259 OID 1302274)
-- Name: rolle_csv_attributes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_csv_attributes (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    name character varying(50) NOT NULL,
    attributes text NOT NULL
);


--
-- TOC entry 304 (class 1259 OID 1302279)
-- Name: rolle_export_settings; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_export_settings (
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    layer_id integer NOT NULL,
    name character varying(100) NOT NULL,
    format character varying(11) NOT NULL,
    epsg integer,
    attributes text NOT NULL,
    metadata smallint,
    groupnames smallint,
    documents smallint,
    geom text,
    within smallint,
    singlegeom smallint
);


--
-- TOC entry 305 (class 1259 OID 1302284)
-- Name: rolle_last_query; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_last_query (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    go character varying(50) NOT NULL,
    layer_id integer NOT NULL,
    sql text NOT NULL,
    orderby text,
    "limit" integer,
    "offset" integer
);


--
-- TOC entry 306 (class 1259 OID 1302289)
-- Name: rolle_nachweise; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_nachweise (
    user_id integer DEFAULT 0 NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL,
    suchhauptart character varying(50) DEFAULT NULL::character varying,
    suchunterart character varying(255) DEFAULT NULL::character varying,
    abfrageart character varying(10) NOT NULL,
    suchgemarkung character varying(10) DEFAULT ''::character varying NOT NULL,
    suchflur character varying(3) DEFAULT NULL::character varying,
    suchstammnr character varying(15) DEFAULT NULL::character varying,
    suchstammnr2 character varying(15) DEFAULT NULL::character varying,
    suchrissnummer character varying(20) DEFAULT NULL::character varying,
    suchrissnummer2 character varying(20) DEFAULT NULL::character varying,
    suchfortfuehrung smallint,
    suchfortfuehrung2 smallint,
    suchpolygon text,
    suchantrnr character varying(23) DEFAULT ''::character varying NOT NULL,
    sdatum character varying(10) DEFAULT NULL::character varying,
    sdatum2 character varying(10) DEFAULT NULL::character varying,
    svermstelle integer,
    suchbemerkung text,
    showhauptart character varying(50) DEFAULT NULL::character varying,
    markhauptart character varying(50) DEFAULT NULL::character varying,
    flur_thematisch smallint DEFAULT 0 NOT NULL,
    alle_der_messung smallint DEFAULT 0 NOT NULL,
    "order" character varying(255) DEFAULT NULL::character varying
);


--
-- TOC entry 308 (class 1259 OID 1302313)
-- Name: rolle_nachweise_dokumentauswahl; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_nachweise_dokumentauswahl (
    id integer NOT NULL,
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    name character varying(100) NOT NULL,
    suchhauptart character varying(50) DEFAULT NULL::character varying,
    suchunterart text NOT NULL
);


--
-- TOC entry 307 (class 1259 OID 1302312)
-- Name: rolle_nachweise_dokumentauswahl_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.rolle_nachweise_dokumentauswahl_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6115 (class 0 OID 0)
-- Dependencies: 307
-- Name: rolle_nachweise_dokumentauswahl_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.rolle_nachweise_dokumentauswahl_id_seq OWNED BY kvwmap.rolle_nachweise_dokumentauswahl.id;


--
-- TOC entry 309 (class 1259 OID 1302320)
-- Name: rolle_nachweise_rechercheauswahl; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_nachweise_rechercheauswahl (
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    nachweis_id integer NOT NULL
);


--
-- TOC entry 311 (class 1259 OID 1302324)
-- Name: rolle_saved_layers; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rolle_saved_layers (
    id integer NOT NULL,
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    name character varying(255) NOT NULL,
    layers text NOT NULL,
    query text
);


--
-- TOC entry 310 (class 1259 OID 1302323)
-- Name: rolle_saved_layers_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.rolle_saved_layers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6116 (class 0 OID 0)
-- Dependencies: 310
-- Name: rolle_saved_layers_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.rolle_saved_layers_id_seq OWNED BY kvwmap.rolle_saved_layers.id;


--
-- TOC entry 302 (class 1259 OID 1302259)
-- Name: rollenlayer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.rollenlayer (
    id integer NOT NULL,
    original_layer_id integer,
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    aktivstatus smallint NOT NULL,
    querystatus smallint NOT NULL,
    name character varying(255) NOT NULL,
    gruppe integer NOT NULL,
    typ character varying DEFAULT 'search'::character varying NOT NULL,
    datentyp integer NOT NULL,
    data text NOT NULL,
    query text,
    connectiontype integer NOT NULL,
    connection character varying(255) DEFAULT NULL::character varying,
    connection_id bigint,
    epsg_code integer NOT NULL,
    transparency integer NOT NULL,
    buffer integer,
    labelitem character varying(100) DEFAULT NULL::character varying,
    classitem character varying(100) DEFAULT NULL::character varying,
    gle_view smallint DEFAULT 1 NOT NULL,
    rollenfilter text,
    duplicate_from_layer_id integer,
    duplicate_criterion character varying(255) DEFAULT NULL::character varying,
    wms_auth_username character varying(100) DEFAULT NULL::character varying,
    wms_auth_password character varying(50) DEFAULT NULL::character varying,
    autodelete smallint DEFAULT 1 NOT NULL
);


--
-- TOC entry 301 (class 1259 OID 1302258)
-- Name: rollenlayer_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.rollenlayer_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6117 (class 0 OID 0)
-- Dependencies: 301
-- Name: rollenlayer_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.rollenlayer_id_seq OWNED BY kvwmap.rollenlayer.id;


--
-- TOC entry 312 (class 1259 OID 1302330)
-- Name: search_attributes2rolle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.search_attributes2rolle (
    name character varying(50) NOT NULL,
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    layer_id integer NOT NULL,
    attribute character varying(50) NOT NULL,
    operator character varying(11) NOT NULL,
    value1 text,
    value2 text,
    searchmask_number integer DEFAULT 0 NOT NULL,
    searchmask_operator character varying
);


--
-- TOC entry 314 (class 1259 OID 1302337)
-- Name: stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.stelle (
    id integer NOT NULL,
    bezeichnung character varying(255) DEFAULT ''::character varying NOT NULL,
    bezeichnung_low_german character varying(255) DEFAULT NULL::character varying,
    bezeichnung_english character varying(255) DEFAULT NULL::character varying,
    bezeichnung_polish character varying(255) DEFAULT NULL::character varying,
    bezeichnung_vietnamese character varying(255) DEFAULT NULL::character varying,
    start date,
    stop date,
    minxmax real,
    minymax real,
    maxxmax real,
    maxymax real,
    minzoom integer DEFAULT 8 NOT NULL,
    epsg_code integer DEFAULT 2398 NOT NULL,
    referenzkarte_id integer,
    authentifizierung boolean DEFAULT true NOT NULL,
    alb_status smallint DEFAULT 30 NOT NULL,
    wappen character varying(255) DEFAULT NULL::character varying,
    wappen_link character varying(255) DEFAULT NULL::character varying,
    logconsume boolean,
    ows_namespace character varying(100) DEFAULT NULL::character varying,
    ows_title character varying(255) DEFAULT NULL::character varying,
    wms_accessconstraints character varying(255) DEFAULT NULL::character varying,
    ows_abstract text,
    ows_contactperson character varying(255) DEFAULT NULL::character varying,
    ows_contactorganization character varying(255) DEFAULT NULL::character varying,
    ows_contenturl text,
    ows_contacturl text,
    ows_distributionurl text,
    ows_contactemailaddress character varying(255) DEFAULT NULL::character varying,
    ows_contactposition character varying(255) DEFAULT NULL::character varying,
    ows_contactvoicephone character varying(100) DEFAULT NULL::character varying,
    ows_contactfacsimile character varying(100) DEFAULT NULL::character varying,
    ows_contactaddress character varying(100) DEFAULT NULL::character varying,
    ows_contactpostalcode character varying(100) DEFAULT NULL::character varying,
    ows_contactcity character varying(100) DEFAULT NULL::character varying,
    ows_contactadministrativearea character varying(100) DEFAULT NULL::character varying,
    ows_contentorganization character varying(150) DEFAULT NULL::character varying,
    ows_contentemailaddress character varying(100) DEFAULT NULL::character varying,
    ows_distributionperson character varying(100) DEFAULT NULL::character varying,
    ows_updatesequence character varying(100) DEFAULT NULL::character varying,
    ows_distributionposition character varying(100) DEFAULT NULL::character varying,
    ows_distributionvoicephone character varying(100) DEFAULT NULL::character varying,
    ows_distributionfacsimile character varying(100) DEFAULT NULL::character varying,
    ows_distributionaddress character varying(100) DEFAULT NULL::character varying,
    ows_distributionpostalcode character varying(100) DEFAULT NULL::character varying,
    ows_distributioncity character varying(100) DEFAULT NULL::character varying,
    ows_distributionadministrativearea character varying(100) DEFAULT NULL::character varying,
    ows_contentperson character varying(100) DEFAULT NULL::character varying,
    ows_contentposition character varying(100) DEFAULT NULL::character varying,
    ows_contentvoicephone character varying(100) DEFAULT NULL::character varying,
    ows_contentfacsimile character varying(100) DEFAULT NULL::character varying,
    ows_contentaddress character varying(100) DEFAULT NULL::character varying,
    ows_contentpostalcode character varying(100) DEFAULT NULL::character varying,
    ows_contentcity character varying(100) DEFAULT NULL::character varying,
    ows_contentadministrativearea character varying(100) DEFAULT NULL::character varying,
    ows_geographicdescription character varying(100) DEFAULT NULL::character varying,
    ows_distributionorganization character varying(150) DEFAULT NULL::character varying,
    ows_distributionemailaddress character varying(100) DEFAULT NULL::character varying,
    ows_fees character varying(255) DEFAULT NULL::character varying,
    ows_srs character varying(255) DEFAULT NULL::character varying,
    protected boolean DEFAULT false NOT NULL,
    check_client_ip boolean DEFAULT false NOT NULL,
    check_password_age boolean DEFAULT false NOT NULL,
    allowed_password_age smallint DEFAULT 6 NOT NULL,
    use_layer_aliases boolean DEFAULT false NOT NULL,
    hist_timestamp boolean DEFAULT false NOT NULL,
    selectable_layer_params text,
    default_user_id integer,
    style character varying(100) DEFAULT NULL::character varying,
    show_shared_layers boolean DEFAULT false NOT NULL,
    version character varying(10) DEFAULT '1.0.0'::character varying NOT NULL,
    reset_password_text text,
    invitation_text text,
    comment text
);


--
-- TOC entry 316 (class 1259 OID 1302405)
-- Name: stelle_gemeinden; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.stelle_gemeinden (
    stelle_id integer DEFAULT 0 NOT NULL,
    gemeinde_id integer DEFAULT 0 NOT NULL,
    gemarkung integer,
    flur smallint,
    flurstueck character varying(10) DEFAULT NULL::character varying
);


--
-- TOC entry 313 (class 1259 OID 1302336)
-- Name: stelle_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.stelle_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6118 (class 0 OID 0)
-- Dependencies: 313
-- Name: stelle_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.stelle_id_seq OWNED BY kvwmap.stelle.id;


--
-- TOC entry 315 (class 1259 OID 1302400)
-- Name: stellen_hierarchie; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.stellen_hierarchie (
    parent_id integer DEFAULT 0 NOT NULL,
    child_id integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 318 (class 1259 OID 1302412)
-- Name: styles; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.styles (
    style_id integer NOT NULL,
    symbol smallint,
    symbolname text,
    size character varying(50) DEFAULT NULL::character varying,
    color character varying(255) DEFAULT NULL::character varying,
    backgroundcolor character varying(11) DEFAULT NULL::character varying,
    outlinecolor character varying(11) DEFAULT NULL::character varying,
    colorrange character varying(23) DEFAULT NULL::character varying,
    datarange character varying(255) DEFAULT NULL::character varying,
    rangeitem character varying(50) DEFAULT NULL::character varying,
    opacity integer,
    minsize character varying(50) DEFAULT NULL::character varying,
    maxsize character varying(50) DEFAULT NULL::character varying,
    minscale integer,
    maxscale integer,
    angle character varying(11) DEFAULT NULL::character varying,
    angleitem character varying(255) DEFAULT NULL::character varying,
    width character varying(50) DEFAULT NULL::character varying,
    minwidth numeric(5,2) DEFAULT NULL::numeric,
    maxwidth numeric(5,2) DEFAULT NULL::numeric,
    offsetx character varying(50) DEFAULT NULL::character varying,
    offsety character varying(50) DEFAULT NULL::character varying,
    polaroffset character varying(255) DEFAULT NULL::character varying,
    pattern character varying(255) DEFAULT NULL::character varying,
    geomtransform character varying(20) DEFAULT NULL::character varying,
    gap integer,
    initialgap numeric(5,2) DEFAULT NULL::numeric,
    linecap character varying(8) DEFAULT NULL::character varying,
    linejoin character varying(5) DEFAULT NULL::character varying,
    linejoinmaxsize integer
);


--
-- TOC entry 317 (class 1259 OID 1302411)
-- Name: styles_style_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.styles_style_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6119 (class 0 OID 0)
-- Dependencies: 317
-- Name: styles_style_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.styles_style_id_seq OWNED BY kvwmap.styles.style_id;


--
-- TOC entry 323 (class 1259 OID 1302484)
-- Name: u_attributfilter2used_layer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_attributfilter2used_layer (
    stelle_id integer NOT NULL,
    layer_id integer NOT NULL,
    attributname character varying(255) NOT NULL,
    attributvalue text NOT NULL,
    operator character varying NOT NULL,
    type character varying(255) NOT NULL
);


--
-- TOC entry 324 (class 1259 OID 1302489)
-- Name: u_consume; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consume (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    activity character varying(255) DEFAULT NULL::character varying,
    nimagewidth integer,
    nimageheight integer,
    epsg_code character varying(6) DEFAULT NULL::character varying,
    minx real,
    miny real,
    maxx real,
    maxy real,
    prev timestamp without time zone,
    next timestamp without time zone
);


--
-- TOC entry 325 (class 1259 OID 1302494)
-- Name: u_consume2comments; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consume2comments (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    comment text,
    public smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 326 (class 1259 OID 1302500)
-- Name: u_consume2layer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consume2layer (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    layer_id integer NOT NULL
);


--
-- TOC entry 327 (class 1259 OID 1302503)
-- Name: u_consumealb; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consumealb (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    format character varying(50) NOT NULL,
    log_number character varying(255) NOT NULL,
    wz boolean,
    numpages integer
);


--
-- TOC entry 328 (class 1259 OID 1302506)
-- Name: u_consumealk; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consumealk (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    druckrahmen_id integer NOT NULL
);


--
-- TOC entry 329 (class 1259 OID 1302509)
-- Name: u_consumecsv; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consumecsv (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    art character varying(20) NOT NULL,
    numdatasets integer
);


--
-- TOC entry 330 (class 1259 OID 1302512)
-- Name: u_consumenachweise; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consumenachweise (
    antrag_nr character varying(11) NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    suchhauptart character varying(50) DEFAULT NULL::character varying,
    suchunterart character varying(255) DEFAULT NULL::character varying,
    abfrageart character varying(10) NOT NULL,
    suchgemarkung character varying(10) DEFAULT NULL::character varying,
    suchflur character varying(3) DEFAULT NULL::character varying,
    suchstammnr character varying(15) DEFAULT NULL::character varying,
    suchstammnr2 character varying(15) DEFAULT NULL::character varying,
    suchrissnr character varying(20) DEFAULT NULL::character varying,
    suchrissnr2 character varying(20) DEFAULT NULL::character varying,
    suchfortf smallint,
    suchpolygon text,
    suchantrnr character varying(23) DEFAULT NULL::character varying,
    sdatum character varying(10) DEFAULT NULL::character varying,
    sdatum2 character varying(10) DEFAULT NULL::character varying,
    svermstelle integer,
    flur_thematisch smallint
);


--
-- TOC entry 331 (class 1259 OID 1302528)
-- Name: u_consumeshape; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_consumeshape (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    time_id timestamp without time zone NOT NULL,
    layer_id integer NOT NULL,
    numdatasets integer
);


--
-- TOC entry 332 (class 1259 OID 1302531)
-- Name: u_funktion2stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_funktion2stelle (
    funktion_id integer DEFAULT 0 NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 334 (class 1259 OID 1302537)
-- Name: u_funktionen; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_funktionen (
    id integer NOT NULL,
    bezeichnung character varying(255) DEFAULT ''::character varying NOT NULL,
    link character varying(255) DEFAULT NULL::character varying
);


--
-- TOC entry 333 (class 1259 OID 1302536)
-- Name: u_funktionen_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.u_funktionen_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6120 (class 0 OID 0)
-- Dependencies: 333
-- Name: u_funktionen_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.u_funktionen_id_seq OWNED BY kvwmap.u_funktionen.id;


--
-- TOC entry 336 (class 1259 OID 1302546)
-- Name: u_groups; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_groups (
    id integer NOT NULL,
    gruppenname character varying(255) NOT NULL,
    gruppenname_low_german character varying(100) DEFAULT NULL::character varying,
    gruppenname_english character varying(100) DEFAULT NULL::character varying,
    gruppenname_polish character varying(100) DEFAULT NULL::character varying,
    gruppenname_vietnamese character varying(100) DEFAULT NULL::character varying,
    obergruppe integer,
    "order" integer,
    selectable_for_shared_layers boolean DEFAULT false NOT NULL,
    icon character varying(255) DEFAULT NULL::character varying
);


--
-- TOC entry 337 (class 1259 OID 1302558)
-- Name: u_groups2rolle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_groups2rolle (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    id integer NOT NULL,
    status smallint NOT NULL
);


--
-- TOC entry 335 (class 1259 OID 1302545)
-- Name: u_groups_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.u_groups_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6121 (class 0 OID 0)
-- Dependencies: 335
-- Name: u_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.u_groups_id_seq OWNED BY kvwmap.u_groups.id;


--
-- TOC entry 338 (class 1259 OID 1302561)
-- Name: u_labels2classes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_labels2classes (
    class_id integer DEFAULT 0 NOT NULL,
    label_id integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 339 (class 1259 OID 1302566)
-- Name: u_menue2rolle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_menue2rolle (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    menue_id integer NOT NULL,
    status smallint NOT NULL
);


--
-- TOC entry 340 (class 1259 OID 1302569)
-- Name: u_menue2stelle; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_menue2stelle (
    stelle_id integer DEFAULT 0 NOT NULL,
    menue_id integer DEFAULT 0 NOT NULL,
    menue_order integer DEFAULT 0 NOT NULL
);


--
-- TOC entry 342 (class 1259 OID 1302576)
-- Name: u_menues; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_menues (
    id integer NOT NULL,
    name character varying(100) DEFAULT ''::character varying NOT NULL,
    name_low_german character varying(100) DEFAULT NULL::character varying,
    name_english character varying(100) DEFAULT NULL::character varying,
    name_polish character varying(100) DEFAULT NULL::character varying,
    name_vietnamese character varying(100) DEFAULT NULL::character varying,
    links character varying(2000) DEFAULT NULL::character varying,
    onclick text,
    obermenue integer DEFAULT 0 NOT NULL,
    menueebene smallint DEFAULT 1 NOT NULL,
    target character varying(10) DEFAULT NULL::character varying,
    "order" integer DEFAULT 0 NOT NULL,
    title text,
    button_class character varying(30) DEFAULT NULL::character varying
);


--
-- TOC entry 341 (class 1259 OID 1302575)
-- Name: u_menues_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.u_menues_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6122 (class 0 OID 0)
-- Dependencies: 341
-- Name: u_menues_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.u_menues_id_seq OWNED BY kvwmap.u_menues.id;


--
-- TOC entry 343 (class 1259 OID 1302593)
-- Name: u_rolle2used_class; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_rolle2used_class (
    user_id integer DEFAULT 0 NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL,
    class_id integer DEFAULT 0 NOT NULL,
    status smallint DEFAULT 0 NOT NULL
);


--
-- TOC entry 344 (class 1259 OID 1302600)
-- Name: u_rolle2used_layer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_rolle2used_layer (
    user_id integer DEFAULT 0 NOT NULL,
    stelle_id integer DEFAULT 0 NOT NULL,
    layer_id integer DEFAULT 0 NOT NULL,
    aktivstatus smallint DEFAULT 0 NOT NULL,
    querystatus smallint DEFAULT 0 NOT NULL,
    gle_view smallint DEFAULT 1 NOT NULL,
    showclasses smallint DEFAULT 1 NOT NULL,
    logconsume boolean,
    transparency smallint,
    drawingorder integer,
    labelitem character varying(100) DEFAULT NULL::character varying,
    geom_from_layer integer NOT NULL,
    rollenfilter text
);


--
-- TOC entry 345 (class 1259 OID 1302613)
-- Name: u_styles2classes; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.u_styles2classes (
    class_id integer DEFAULT 0 NOT NULL,
    style_id integer DEFAULT 0 NOT NULL,
    drawingorder integer
);


--
-- TOC entry 319 (class 1259 OID 1302440)
-- Name: used_layer; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.used_layer (
    stelle_id integer DEFAULT 0 NOT NULL,
    layer_id integer DEFAULT 0 NOT NULL,
    group_id integer,
    queryable boolean DEFAULT true NOT NULL,
    legendorder integer,
    minscale integer,
    maxscale integer,
    offsite character varying(11) DEFAULT NULL::character varying,
    transparency smallint,
    postlabelcache smallint DEFAULT 0 NOT NULL,
    filter text,
    template character varying(255) DEFAULT NULL::character varying,
    header character varying(255) DEFAULT NULL::character varying,
    footer character varying(255) DEFAULT NULL::character varying,
    symbolscale integer,
    logconsume boolean,
    requires integer,
    privileg smallint DEFAULT 0 NOT NULL,
    export_privileg smallint DEFAULT 1 NOT NULL,
    use_parent_privileges boolean DEFAULT true NOT NULL,
    start_aktiv boolean DEFAULT false NOT NULL,
    use_geom smallint DEFAULT 1 NOT NULL
);


--
-- TOC entry 321 (class 1259 OID 1302459)
-- Name: user; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap."user" (
    id integer NOT NULL,
    login_name character varying(100) DEFAULT ''::character varying NOT NULL,
    name character varying(100) DEFAULT ''::character varying NOT NULL,
    vorname character varying(100) DEFAULT NULL::character varying,
    namenszusatz character varying(50) DEFAULT NULL::character varying,
    passwort character varying(32) DEFAULT NULL::character varying,
    password character varying(40) DEFAULT NULL::character varying,
    password_expired smallint DEFAULT 0 NOT NULL,
    password_setting_time timestamp without time zone DEFAULT now(),
    userdata_checking_time timestamp without time zone,
    start date,
    stop date,
    ips text,
    tokens text,
    funktion character varying DEFAULT 'user'::character varying NOT NULL,
    stelle_id integer,
    phon character varying(25) DEFAULT NULL::character varying,
    email character varying(100) DEFAULT NULL::character varying,
    agreement_accepted smallint DEFAULT 0 NOT NULL,
    num_login_failed integer DEFAULT 0 NOT NULL,
    login_locked_until timestamp without time zone,
    organisation character varying(255) DEFAULT NULL::character varying,
    "position" character varying(255) DEFAULT NULL::character varying,
    share_rollenlayer_allowed smallint DEFAULT 0,
    layer_data_import_allowed smallint,
    archived date
);


--
-- TOC entry 322 (class 1259 OID 1302481)
-- Name: user2notifications; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.user2notifications (
    notification_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 320 (class 1259 OID 1302458)
-- Name: user_id_seq; Type: SEQUENCE; Schema: kvwmap; Owner: -
--

CREATE SEQUENCE kvwmap.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6123 (class 0 OID 0)
-- Dependencies: 320
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: kvwmap; Owner: -
--

ALTER SEQUENCE kvwmap.user_id_seq OWNED BY kvwmap."user".id;


--
-- TOC entry 346 (class 1259 OID 1302618)
-- Name: zwischenablage; Type: TABLE; Schema: kvwmap; Owner: -
--

CREATE TABLE kvwmap.zwischenablage (
    user_id integer NOT NULL,
    stelle_id integer NOT NULL,
    layer_id integer NOT NULL,
    oid character varying(50) NOT NULL
);


--
-- TOC entry 5152 (class 2604 OID 1301800)
-- Name: belated_files id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.belated_files ALTER COLUMN id SET DEFAULT nextval('kvwmap.belated_files_id_seq'::regclass);


--
-- TOC entry 5153 (class 2604 OID 1301807)
-- Name: classes class_id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.classes ALTER COLUMN class_id SET DEFAULT nextval('kvwmap.classes_class_id_seq'::regclass);


--
-- TOC entry 5162 (class 2604 OID 1301822)
-- Name: colors id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.colors ALTER COLUMN id SET DEFAULT nextval('kvwmap.colors_id_seq'::regclass);


--
-- TOC entry 5167 (class 2604 OID 1301831)
-- Name: config id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.config ALTER COLUMN id SET DEFAULT nextval('kvwmap.config_id_seq'::regclass);


--
-- TOC entry 5170 (class 2604 OID 1301840)
-- Name: connections id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.connections ALTER COLUMN id SET DEFAULT nextval('kvwmap.connections_id_seq'::regclass);


--
-- TOC entry 5177 (class 2604 OID 1301853)
-- Name: cron_jobs id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.cron_jobs ALTER COLUMN id SET DEFAULT nextval('kvwmap.cron_jobs_id_seq'::regclass);


--
-- TOC entry 5184 (class 2604 OID 1301866)
-- Name: datasources id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datasources ALTER COLUMN id SET DEFAULT nextval('kvwmap.datasources_id_seq'::regclass);


--
-- TOC entry 5186 (class 2604 OID 1301874)
-- Name: datatypes id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datatypes ALTER COLUMN id SET DEFAULT nextval('kvwmap.datatypes_id_seq'::regclass);


--
-- TOC entry 5211 (class 2604 OID 1301908)
-- Name: datendrucklayouts id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datendrucklayouts ALTER COLUMN id SET DEFAULT nextval('kvwmap.datendrucklayouts_id_seq'::regclass);


--
-- TOC entry 5226 (class 2604 OID 1301941)
-- Name: ddl_colors id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl_colors ALTER COLUMN id SET DEFAULT nextval('kvwmap.ddl_colors_id_seq'::regclass);


--
-- TOC entry 5232 (class 2604 OID 1301956)
-- Name: druckausschnitte id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckausschnitte ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckausschnitte_id_seq'::regclass);


--
-- TOC entry 5233 (class 2604 OID 1301961)
-- Name: druckfreibilder id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreibilder ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckfreibilder_id_seq'::regclass);


--
-- TOC entry 5235 (class 2604 OID 1301967)
-- Name: druckfreilinien id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreilinien ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckfreilinien_id_seq'::regclass);


--
-- TOC entry 5238 (class 2604 OID 1301976)
-- Name: druckfreirechtecke id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreirechtecke ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckfreirechtecke_id_seq'::regclass);


--
-- TOC entry 5241 (class 2604 OID 1301985)
-- Name: druckfreitexte id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreitexte ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckfreitexte_id_seq'::regclass);


--
-- TOC entry 5243 (class 2604 OID 1301993)
-- Name: druckrahmen id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen ALTER COLUMN id SET DEFAULT nextval('kvwmap.druckrahmen_id_seq'::regclass);


--
-- TOC entry 5262 (class 2604 OID 1302032)
-- Name: labels label_id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.labels ALTER COLUMN label_id SET DEFAULT nextval('kvwmap.labels_label_id_seq'::regclass);


--
-- TOC entry 5273 (class 2604 OID 1302047)
-- Name: layer layer_id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer ALTER COLUMN layer_id SET DEFAULT nextval('kvwmap.layer_layer_id_seq'::regclass);


--
-- TOC entry 5364 (class 2604 OID 1302157)
-- Name: layer_charts id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_charts ALTER COLUMN id SET DEFAULT nextval('kvwmap.layer_charts_id_seq'::regclass);


--
-- TOC entry 5372 (class 2604 OID 1302187)
-- Name: notifications id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.notifications ALTER COLUMN id SET DEFAULT nextval('kvwmap.notifications_id_seq'::regclass);


--
-- TOC entry 5373 (class 2604 OID 1302194)
-- Name: referenzkarten id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.referenzkarten ALTER COLUMN id SET DEFAULT nextval('kvwmap.referenzkarten_id_seq'::regclass);


--
-- TOC entry 5460 (class 2604 OID 1302316)
-- Name: rolle_nachweise_dokumentauswahl id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise_dokumentauswahl ALTER COLUMN id SET DEFAULT nextval('kvwmap.rolle_nachweise_dokumentauswahl_id_seq'::regclass);


--
-- TOC entry 5462 (class 2604 OID 1302327)
-- Name: rolle_saved_layers id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_saved_layers ALTER COLUMN id SET DEFAULT nextval('kvwmap.rolle_saved_layers_id_seq'::regclass);


--
-- TOC entry 5432 (class 2604 OID 1302262)
-- Name: rollenlayer id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rollenlayer ALTER COLUMN id SET DEFAULT nextval('kvwmap.rollenlayer_id_seq'::regclass);


--
-- TOC entry 5464 (class 2604 OID 1302340)
-- Name: stelle id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stelle ALTER COLUMN id SET DEFAULT nextval('kvwmap.stelle_id_seq'::regclass);


--
-- TOC entry 5527 (class 2604 OID 1302415)
-- Name: styles style_id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.styles ALTER COLUMN style_id SET DEFAULT nextval('kvwmap.styles_style_id_seq'::regclass);


--
-- TOC entry 5596 (class 2604 OID 1302540)
-- Name: u_funktionen id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_funktionen ALTER COLUMN id SET DEFAULT nextval('kvwmap.u_funktionen_id_seq'::regclass);


--
-- TOC entry 5599 (class 2604 OID 1302549)
-- Name: u_groups id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_groups ALTER COLUMN id SET DEFAULT nextval('kvwmap.u_groups_id_seq'::regclass);


--
-- TOC entry 5611 (class 2604 OID 1302579)
-- Name: u_menues id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menues ALTER COLUMN id SET DEFAULT nextval('kvwmap.u_menues_id_seq'::regclass);


--
-- TOC entry 5563 (class 2604 OID 1302462)
-- Name: user id; Type: DEFAULT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap."user" ALTER COLUMN id SET DEFAULT nextval('kvwmap.user_id_seq'::regclass);


--
-- TOC entry 5984 (class 0 OID 1301797)
-- Dependencies: 242
-- Data for Name: belated_files; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 5986 (class 0 OID 1301804)
-- Dependencies: 244
-- Data for Name: classes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 5988 (class 0 OID 1301819)
-- Dependencies: 246
-- Data for Name: colors; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.colors VALUES (1, NULL, 166, 206, 227);
INSERT INTO kvwmap.colors VALUES (2, NULL, 31, 120, 180);
INSERT INTO kvwmap.colors VALUES (3, NULL, 178, 223, 138);
INSERT INTO kvwmap.colors VALUES (4, NULL, 51, 160, 44);
INSERT INTO kvwmap.colors VALUES (5, NULL, 251, 154, 153);
INSERT INTO kvwmap.colors VALUES (6, NULL, 227, 26, 28);
INSERT INTO kvwmap.colors VALUES (7, NULL, 253, 191, 111);
INSERT INTO kvwmap.colors VALUES (8, NULL, 255, 127, 0);
INSERT INTO kvwmap.colors VALUES (9, NULL, 202, 178, 214);
INSERT INTO kvwmap.colors VALUES (10, NULL, 106, 61, 154);
INSERT INTO kvwmap.colors VALUES (11, NULL, 0, 0, 0);
INSERT INTO kvwmap.colors VALUES (12, NULL, 122, 12, 45);


--
-- TOC entry 5990 (class 0 OID 1301828)
-- Dependencies: 248
-- Data for Name: config; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.config VALUES (10, 'HEADER', 'SNIPPETS', 'header.php', '', 'string', 'Layout', '', 1, 3);
INSERT INTO kvwmap.config VALUES (11, 'FOOTER', 'SNIPPETS', 'footer.php', '', 'string', 'Layout', '', 1, 3);
INSERT INTO kvwmap.config VALUES (12, 'LOGIN', 'SNIPPETS', 'login.php', 'login.php
', 'string', 'Layout', '', 1, 3);
INSERT INTO kvwmap.config VALUES (13, 'LAYER_ERROR_PAGE', 'SNIPPETS', 'layer_error_page.php', 'Seite zur Fehlerbehandlung, die durch fehlerhafte Layer verursacht werden; unterhalb von /snippets
', 'string', 'Layout', '', 1, 3);
INSERT INTO kvwmap.config VALUES (14, 'AGREEMENT_MESSAGE', 'CUSTOM_PATH', '', 'Seite mit der Datenschutzerklärung, die einmalig beim Login angezeigt wird
z.B. custom/ds_gvo.htm', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (15, 'CUSTOM_STYLE', 'CUSTOM_PATH', '', 'hier kann eine eigene css-Datei angegeben werden
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (16, 'ZOOM2COORD_STYLE_ID', '', '3244', 'hier können eigene Styles für den Koordinatenzoom und Punktzoom definiert werden
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (17, 'ZOOM2POINT_STYLE_ID', '', '3244', '', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (18, 'GLEVIEW', '', '2', 'Schalter für eine zeilen- oder spaltenweise Darstellung der Attribute im generischen Layereditor  # Version 1.6.5
', 'numeric', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (19, 'sizes', '', '{
    "layouts/gui.php": {
        "margin": {
            "width": 10,
            "height": 10
        },
        "header": {
            "height": 38
        },
        "scale_bar": {
            "height": 30
        },
        "lagebezeichnung_bar": {
            "height": 30
        },
        "map_functions_bar": {
            "height": 36
        },
        "footer": {
            "height": 20
        },
        "menue": {
            "width": 240,
            "hide_width": 22
        },
        "legend": {
            "width": 250,
            "hide_width": 27
        }
    },
    "gui_button.php": {
        "margin": {
            "width": 10,
            "height": 22
        },
        "header": {
            "height": 25
        },
        "footer": {
            "height": 107
        },
        "menue": {
            "width": 209
        },
        "legend": {
            "width": 250
        }
    }
}', 'Höhen und Breiten von Browser, Rand, Header, Footer, Menü und Legende																# Version 2.7
', 'array', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (20, 'LEGEND_GRAPHIC_FILE', '', '', 'zusätzliche Legende; muss unterhalb von snippets liegen
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (21, 'legendicon_size', '', '{
    "width": [
        18,
        18,
        18,
        18
    ],
    "height": [
        18,
        12,
        12,
        18
    ]
}', 'Höhe und Breite der generierten Legendenbilder für verschiedene Layertypen
-> Punktlayer
-> Linienlayer
-> Flächenlayer
-> Rasterlayer
', 'array', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (22, 'PREVIEW_IMAGE_WIDTH', '', '800', 'Vorschaubildgröße
', 'numeric', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (23, 'TITLE', '', 'WebGIS kvwmap', 'Titel, welcher im Browser angezeigt wird
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (24, 'MENU_WAPPEN', '', 'kein', 'Position des Wappens (oben/unten/kein)
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (25, 'MENU_REFMAP', '', 'unten', 'Position der Referenzkarte in der Menüleiste. (oben/unten/ohne)', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (26, 'BG_TR', '', 'lightsteelblue', 'Hintergrundfarbe Zeile bei Listen
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (27, 'BG_MENUETOP', '', '#DAE4EC', 'Hintergrundfarbe Top-Menüzeilen
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (28, 'BG_MENUESUB', '', '#EDEFEF', 'Hintergrundfarbe Sub-Menüzeilen
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (29, 'BG_DEFAULT', '', 'lightsteelblue', 'Hintergrundfarbe (Kopf-/Fusszeile)
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (30, 'BG_FORM', '', 'lightsteelblue', 'Hintergrundfarbe (Eingabeformulare)
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (31, 'BG_FORMFAIL', '', 'lightpink', 'Hintergrundfarbe (Formularfehler)
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (32, 'BG_GLEHEADER', '', 'lightsteelblue', 'Hintergrundfarbe GLE Datensatzheader
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (33, 'TXT_GLEHEADER', '', '#000000', 'Schriftfarbe GLE Datensatzheader
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (34, 'BG_GLEATTRIBUTE', '', '#DAE4EC', 'Hintergrundfarbe GLE Attributnamen
', 'string', 'Layout', '', 1, 2);
INSERT INTO kvwmap.config VALUES (35, 'POSTGRESVERSION', '', '1520', 'PostgreSQL Server Version                         # Version 1.6.4
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (36, 'MYSQLVERSION', '', '550', 'MySQLSQL Server Version                         # Version 1.6.4
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (37, 'MAPSERVERVERSION', '', '761', 'Mapserver Version                             # Version 1.6.8
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (38, 'PHPVERSION', '', '730', 'PHP-Version
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (39, 'MYSQL_CHARSET', '', 'UTF8', 'Character Set der MySQL-Datenbank
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (40, 'POSTGRES_CHARSET', '', 'UTF8', '', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (41, 'PUBLISHERNAME', '', 'WebGIS', 'Bezeichung des Datenproviders
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (42, 'CHECK_CLIENT_IP', '', 'true', 'Erweiterung der Authentifizierung um die IP Adresse des Nutzers
Testet ob die IP des anfragenden Clientrechners dem Nutzer zugeordnet ist
', 'boolean', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (43, 'PASSWORD_MAXLENGTH', '', '25', 'maximale Länge der Passwörter
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (44, 'PASSWORD_MINLENGTH', '', '12', 'minimale Länge der Passwörter
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (45, 'PASSWORD_CHECK', '', '01010', 'Prüfung neues Passwort
Auskommentiert, wenn das Passwort vom Admin auf "unendlichen" Zeitraum vergeben wird
erste Stelle  0 = Prüft die Stärke des Passworts (3 von 4 Kriterien müssen erfüllt sein) - die weiteren Stellen werden ignoriert
erste Stelle  1 = Prüft statt Stärke die nachfolgenden Kriterien:
zweite Stelle 1 = Es müssen Kleinbuchstaben enthalten sein
dritte Stelle 1 = Es müssen Großbuchstaben enthalten sein
vierte Stelle 1 = Es müssen Zahlen enthalten sein
fünfte Stelle 1 = Es müssen Sonderzeichen enthalten sein
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (46, 'GIT_USER', '', 'gisadmin', 'Wenn das kvwmap-Verzeichnis ein git-Repository ist, kann diese Konstante auf den User gesetzt werden, der das Repository angelegt hat.
Damit der Apache-User dann die git-Befehle als dieser User ausführen kann, muss man als root über den Befehl "visudo" die /etc/sudoers editieren.
Dort muss dann eine Zeile in dieser Form hinzugefügt werden: 
www-data        ALL=(fgs) NOPASSWD: /usr/bin/git
Dann kann man die Aktualität des Quellcodes in der Administrationsoberfläche überprüfen und ihn aktualisieren.
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (47, 'MAXQUERYROWS', '', '100', 'maximale Anzahl der in einer Sachdatenabfrage zurückgelieferten Zeilen.
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (48, 'ALWAYS_DRAW', '', 'true', 'definiert, ob der Polygoneditor nach einem Neuladen
der Seite immer in den Modus "Polygon zeichnen" wechselt
', 'boolean', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (49, 'EARTH_RADIUS', '', '6384000', 'Parameter für die Strecken- und Flächenreduktion
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (50, 'admin_stellen', '', '[
    1
]', 'Adminstellen
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (51, 'gast_stellen', '', '[
  
]', 'Gast-Stellen
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (52, 'selectable_limits', '', '[
    10,
    25,
    50,
    100,
    200
]', 'auswählbare Treffermengen
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (53, 'selectable_scales', '', '[
    500,
    1000,
    2500,
    5000,
    7500,
    10000,
    25000,
    50000,
    100000,
    250000,
    500000,
    1000000
]', 'auswählbare Maßstäbe
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (54, 'supportedSRIDs', '', '[
    4326,
    2397,
    2398,
    2399,
    3857,
    5650,
    31466,
    31467,
    31468,
    31469,
    32648,
    25832,
    25833,
    35833,
    32633,
    325833,
    15833,
    900913,
    28992
]', 'Unterstützte SRIDs, nur diese stehen zur Auswahl bei der Stellenwahl
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (55, 'supportedLanguages', '', '[
    "german",
  "english"
]', 'Unterstützte Sprachen, nur diese stehen zur Auswahl bei der Stellenwahl (''german'', ''low-german'', ''english'', ''polish'', ''vietnamese'')
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (56, 'supportedExportFormats', '', '[
    "Shape",
    "GML",
    "KML",
    "GeoJSON",
    "UKO",
    "OVL",
    "CSV"
]', 'Unterstützte Exportformate
', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (57, 'MAPFACTOR', '', '3', 'Faktor für die Einstellung der Druckqualität (MAPFACTOR * 72 dpi)     # Version 1.6.0
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (58, 'DEFAULT_DRUCKRAHMEN_ID', '', '42', 'Standarddrucklayout für den schnellen Kartendruck						# Version 1.7.4
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (59, 'MAXUPLOADSIZE', '', '200', 'maximale Datenmenge in MB, die beim Datenimport hochgeladen werden darf
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (60, 'MINSCALE', '', '1', 'Minmale Maßstabszahl
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (61, 'COORD_ZOOM_SCALE', '', '50000', 'Maßstab ab dem bei einem Koordinatensprung auch gezoomt wird
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (62, 'ZOOMBUFFER', '', '100', 'Puffer in der Einheit (ZOOMUNIT) der beim Zoom auf ein Objekt hinzugegeben wird
', 'numeric', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (63, 'ZOOMUNIT', '', 'meter', 'Einheit des Puffer der beim Zoom auf ein Objekt hinzugegeben wird
''meter'' oder ''scale''
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (65, 'SHOW_MAP_IMAGE', '', 'true', 'Definiert, ob das aktuelle Kartenbild separat angezeigt werden darf oder nicht
', 'boolean', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (66, 'kvwmap_plugins', '', '[
]', '', 'array', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (67, 'INFO1', '', 'Prüfen Sie ob Ihr Datenbankmodell aktuell ist.', 'Festlegung von Fehlermeldungen und Hinweisen
', 'string', 'Administration', '', 1, 2);
INSERT INTO kvwmap.config VALUES (68, 'APPLVERSION', '', 'kvwmap/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (69, 'INSTALLPATH', '', '/var/www/', 'Installationspfad
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (70, 'WWWROOT', 'INSTALLPATH', 'apps/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (71, 'IMAGEPATH', 'INSTALLPATH', 'tmp/', 'Verzeichnis, in dem die temporären Bilder usw. abgelegt werden
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (72, 'URL', '', 'https://dev.gdi-service.de/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (73, 'NBH_PATH', 'WWWROOT.APPLVERSION', 'tools/UTM33_NBH.lst', 'Datei mit den Nummerierungsbezirkshöhen
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (74, 'MAPSERV_CGI_BIN', 'URL', 'cgi-bin/mapserv', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (75, 'LOGPATH', 'INSTALLPATH', 'logs/kvwmap/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (76, 'SHAPEPATH', 'INSTALLPATH', 'data/', 'Shapepath [Pfad zum Shapefileverzeichnis]
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (77, 'CUSTOM_SHAPE_SCHEMA', '', 'custom_shapes', 'ein extra Schema in der PG-DB, in der die Tabellen der Nutzer Shapes angelegt werden
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (78, 'REFERENCEMAPPATH', 'SHAPEPATH', 'referencemaps/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (79, 'DRUCKRAHMEN_PATH', 'SHAPEPATH', 'druckrahmen/', 'Pfad zum Speichern der Kartendruck-Layouts
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (80, 'THIRDPARTY_PATH', '', '../3rdparty/', '3rdparty Pfad
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (81, 'FONTAWESOME_PATH', 'THIRDPARTY_PATH', 'font-awesome-4.6.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (82, 'JQUERY_PATH', 'THIRDPARTY_PATH', 'jQuery-3.6.0/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (83, 'BOOTSTRAP_PATH', 'THIRDPARTY_PATH', 'bootstrap-4.6.1/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (84, 'BOOTSTRAPTABLE_PATH', 'THIRDPARTY_PATH', 'bootstrap-table-1.20.2/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (85, 'PROJ4JS_PATH', 'THIRDPARTY_PATH', 'proj4js-2.4.3/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (86, 'POSTGRESBINPATH', '', '/usr/bin/', 'Bin-Pfad der Postgres-tools (shp2pgsql, pgsql2shp)
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (87, 'OGR_BINPATH', '', '/usr/bin/', 'Bin-Pfad der OGR-tools (ogr2ogr, ogrinfo)
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (88, 'ZIP_PATH', '', 'zip', 'Pfad zum Zip-Programm (unter Linux: ''zip -j'', unter Windows z.B. ''c:/programme/Zip/bin/zip.exe'')
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (89, 'CUSTOM_IMAGE_PATH', 'SHAPEPATH', 'bilder/', 'Pfad für selbst gemachte Bilder
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (90, 'CACHEPATH', 'INSTALLPATH', 'cache/', 'Cachespeicherort
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (91, 'CACHETIME', '', '168', 'Cachezeit Nach welcher Zeit in Stunden sollen gecachte Dateien aktualisiert werden
wird derzeit noch nicht berücksichtigt
', 'numeric', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (92, 'TEMPPATH_REL', '', '../tmp/', 'relative Pfadangabe zum Webverzeichnis mit temprären Dateien
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (93, 'IMAGEURL', '', '/tmp/', 'Imageurl
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (94, 'SYMBOLSET', 'WWWROOT.APPLVERSION', 'symbols/symbole.sym', 'Symbolset
', 'string', 'Pfadeinstellungen', '', 1, 3);
INSERT INTO kvwmap.config VALUES (95, 'FONTSET', 'WWWROOT.APPLVERSION', 'fonts/fonts.txt', 'Fontset
', 'string', 'Pfadeinstellungen', '', 1, 3);
INSERT INTO kvwmap.config VALUES (96, 'GRAPHICSPATH', '', 'graphics/', 'Graphics
', 'string', 'Pfadeinstellungen', '', 1, 0);
INSERT INTO kvwmap.config VALUES (97, 'WAPPENPATH', 'CUSTOM_PATH', 'wappen/', 'Wappen
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (98, 'LAYOUTPATH', 'WWWROOT.APPLVERSION', 'layouts/', 'Layouts
', 'string', 'Pfadeinstellungen', '', 1, 0);
INSERT INTO kvwmap.config VALUES (99, 'SNIPPETS', 'LAYOUTPATH', 'snippets/', '', 'string', 'Pfadeinstellungen', '', 1, 0);
INSERT INTO kvwmap.config VALUES (100, 'CLASSPATH', 'WWWROOT.APPLVERSION', 'class/', '', 'string', 'Pfadeinstellungen', '', 1, 0);
INSERT INTO kvwmap.config VALUES (101, 'PLUGINS', 'WWWROOT.APPLVERSION', 'plugins/', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (102, 'SYNC_PATH', 'SHAPEPATH', 'synchro/', 'Synchronisationsverzeichnis                         # Version 1.7.0
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (103, 'IMAGEMAGICKPATH', '', '/usr/bin/', 'Pfad zum Imagemagick convert
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (104, 'UPLOADPATH', 'SHAPEPATH', 'upload/', 'Pfad zum Ordner für Datei-Uploads
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (105, 'DEFAULTMAPFILE', 'SHAPEPATH', 'mapfiles/defaultmapfile.map', 'Mapfile, mit dem das Mapobjekt gebildet wird
', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (106, 'REFMAPFILE', 'SHAPEPATH', 'mapfiles/refmapfile.map', '', 'string', 'Pfadeinstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (107, 'MAILMETHOD', '', 'sendmail', 'Methode zum Versenden von E-Mails. Mögliche Optionen:
sendmail: E-Mails werden direkt mit sendmail versendet. (default)
sendEmail async: E-Mails werden erst in einem temporären Verzeichnis MAILQUEUEPATH
abgelegt und können später durch das Script tools/sendEmailAsync.sh
versendet werden. Dort muss auch MAILQUEUEPATH eingestellt werden.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (108, 'MAILSMTPSERVER', '', '', 'SMTP-Server, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (109, 'MAILSMTPPORT', '', '25', 'SMTP-Port, Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
', 'numeric', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (110, 'MAILQUEUEPATH', '', '/var/www/logs/kvwmap/mail_queue/', 'Verzeichnis für die JSON-Dateien mit denzu versendenen E-Mails.
Muss nur angegeben werden, wenn Methode sendEmail async verwendet wird.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (111, 'MAILARCHIVPATH', '', '/var/www/logs/kvwmap/mail_archiv/', '', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (112, 'LAYER_IDS_DOP', '', '', '', 'string', 'Layer-IDs', '', 1, 2);
INSERT INTO kvwmap.config VALUES (113, 'LAYER_ID_SCHNELLSPRUNG', '', 'NULL', '', 'numeric', 'Layer-IDs', '', 1, 2);
INSERT INTO kvwmap.config VALUES (114, 'quicksearch_layer_ids', '', '[
 
]', '', 'array', 'Layer-IDs', '', 1, 2);
INSERT INTO kvwmap.config VALUES (115, 'DEBUGFILE', '', '_debug.htm', 'Ort der Datei, in der die Meldungen beim Debugen geschrieben werden
', 'string', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (116, 'DEBUG_LEVEL', '', '1', 'Level der Fehlermeldungen beim debuggen
3 nur Ausgaben die für Admin bestimmt sind
2 nur Datenbankanfragen
1 nur wichtige Fehlermeldungen
5 keine Ausgaben
', 'numeric', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (117, 'LOGFILE_MYSQL', 'LOGPATH', '_log_mysql.sql', 'mySQL-Log-Datei zur Speicherung der SQL-Statements              # Version 1.6.0
', 'string', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (118, 'LOGFILE_POSTGRES', 'LOGPATH', '_log_postgres.sql', 'postgreSQL-Log-Datei zur Speicherung der SQL-Statements         # Version 1.6.0
', 'string', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (119, 'LOGFILE_LOGIN', 'LOGPATH', 'login_fail.log', 'Log-Datei zur Speicherung der Login Vorgänge
', 'string', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (120, 'LOG_LEVEL', '', '2', 'Log-Level zur Speicherung der SQL-Statements                    # Version 1.6.0
Loglevel
0 niemals loggen
1 immer loggen
2 nur loggen wenn loglevel in execSQL 1 ist.
', 'numeric', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (121, 'SAVEMAPFILE', 'LOGPATH', 'save_mapfile.map', 'Wenn SAVEMAPFILE leer ist, wird sie nicht gespeichert.
Achtung, wenn die cgi-bin/mapserv ohne Authentifizierung und der Pfad zu save_mapfile.map bekannt ist, kann jeder die Karten des letzten Aufrufs in kvwmap über mapserv?map=<pfad zu save_map.map abfragen. Und wenn wfs zugelassen ist auch die Sachdaten dazu runterladen. Diese Konstante sollte nur zu debug-Zwecken eingeschaltet bleiben.
', 'string', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (122, 'DEFAULTDBWRITE', '', '1', 'Ermöglicht die Ausführung der SQL-Statements in der Datenbank zu unterdrücken.
In dem Fall werden die Statements nur in die Log-Datei geschrieben.
Die Definition von DBWRITE ist umgezogen nach start.php, damit das Unterdrücken
des Schreiben in die Datenbank auch mit Formularwerten eingestellt werden kann.
das übernimmt in dem Falle die Formularvariable disableDbWrite.
Hier kann jedoch noch der Defaultwert gesetzt werden
', 'numeric', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (123, 'LOG_CONSUME_ACTIVITY', '', '1', 'Einstellungen zur Speicherung der Zugriffe
', 'numeric', 'Logging', '', 1, 2);
INSERT INTO kvwmap.config VALUES (128, 'MAPFILENAME', '', 'kvwmap', '', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (129, 'WMS_MAPFILE_REL_PATH', '', 'ows/', 'Voreinstellungen für Metadaten zu Web Map Services (WMS-Server)
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (130, 'WMS_MAPFILE_PATH', 'INSTALLPATH.WMS_MAPFILE_REL_PATH', 'mapfiles/', '', 'string', 'OWS-METADATEN', '', 1, 3);
INSERT INTO kvwmap.config VALUES (131, 'SUPORTED_WMS_VERSION', '', '1.3.0', '', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (132, 'OWS_SCHEMAS_LOCATION', '', 'http://schemas.opengeospatial.net', 'Metadaten zur Ausgabe im Capabilities Dokument gelten für WMS, WFS und WCS
sets base URL for OGC Schemas so the root element in the
Capabilities document points to the correct schema location
to produce valid XML
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (133, 'OWS_TITLE', '', 'MapServer kvwmap', 'An Stelle von WMS_TITLE
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (134, 'OWS_ABSTRACT', '', 'Kartenserver', 'An Stelle von WMS_Abstract
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (135, 'OWS_KEYWORDLIST', '', 'GIS,Landkreis,Kataster,Geoinformation', 'WMT_MS_Capabilities/Service/KeywordList/Keyword[]
WFS_Capabilities/Service/Keywords
WCS_Capabilities/Service/keywords/keyword[]
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (136, 'OWS_SERVICE_ONLINERESOURCE', 'URL.APPLVERSION', 'index.php?go=OWS', 'WMT_MS_Capabilities/Service/OnlineResource
WFS_Capabilities/Service/OnlineResource
WCS_Capabilities/Service/responsibleParty/onlineResource/@xlink:href
', 'string', 'OWS-METADATEN', '', 1, 3);
INSERT INTO kvwmap.config VALUES (137, 'OWS_FEES', '', 'zu Testzwecken frei', 'An Stelle WMS_FEES
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (138, 'OWS_ACCESSCONSTRAINTS', '', 'keine', 'WMT_MS_Capabilities/Service/AccessConstraints
WFS_Capabilities/Service/AccessConstraints
WCS_Capabilities/Service/accessConstraints
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (139, 'OWS_CONTACTPERSON', '', 'Stefan Rahn', 'An Stelle von WMS_CONTACTPERSON
WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactPerson
WCS_Capabilities/Service/responsibleParty/individualName
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (140, 'OWS_CONTACTORGANIZATION', '', 'GDI-Service', 'An Stelle von WMS_CONTACTORGANIZATION
WMT_MS_Capabilities/Service/ContactInformation/ContactPersonPrimary/ContactOrganization
WCS_Capabilities/Service/responsibleParty/organisationName
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (141, 'OWS_CONTACTPOSITION', '', 'Softwareentwickler', 'An Stelle von WMS_CONTACTPOSITION
WMT_MS_Capabilities/Service/ContactInformation/ContactPosition
WCS_Capabilities/Service/responsibleParty/positionName
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (142, 'OWS_ADDRESSTYPE', '', 'postal', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/AddressType
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (143, 'OWS_ADDRESS', '', 'Friedrichstr. 16', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Address
WCS_Capabilities/Service/contactInfo/address/deliveryPoint
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (144, 'OWS_CITY', '', 'Rostock', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/City
WCS_Capabilities/Service/contactInfo/address/city
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (145, 'OWS_STATEORPROVINCE', '', 'Mecklenburg-Vorpommern', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/StateOrProvince
WCS_Capabilities/Service/contactInfo/address/administrativeArea
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (146, 'OWS_POSTCODE', '', '18059', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/PostCode
WCS_Capabilities/Service/contactInfo/address/postalCode
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (147, 'OWS_COUNTRY', '', 'Deutschland', 'WMT_MS_Capabilities/Service/ContactInformation/ContactAddress/Country
WCS_Capabilities/Service/contactInfo/address/country
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (148, 'OWS_CONTACTVOICETELEPHONE', '', '0049-381-403 44445', 'WMT_MS_Capabilities/Service/ContactInformation/ContactVoiceTelephone
WCS_Capabilities/Service/contactInfo/phone/voice
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (149, 'OWS_CONTACTFACSIMILETELEPHONE', '', '+49 381-3378-9527', 'WMT_MS_Capabilities/Service/ContactInformation/ContactFacsimileTelephone
WCS_Capabilities/Service/contactInfo/phone/facsimile
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (150, 'OWS_CONTACTELECTRONICMAILADDRESS', '', 'stefan.rahn@gdi-service.de', 'An Stelle von WMS_CONTACTELECTRONICMAILADDRESS
WMT_MS_Capabilities/Service/ContactInformation/ContactElectronicMailAddress
WCS_Capabilities/Service/contactInfo/address/eletronicMailAddress
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (182, 'MAILREPLYADDRESS', '', 'no-reply@kvwmap.de', 'E-Mail-Adresse, die als Absender in von kvwmap versandten E-Mails angegeben werden soll.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (183, 'MAILCOPYATTACHMENT', '', 'true', 'Sollen Dateien in E-Mail-Anhängen beim Versenden in den Archiv-Ordner kopiert (true) oder verschoben (false) werden.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (151, 'OWS_SRS', '', 'EPSG:25832 EPSG:25833 EPSG:5650 EPSG:4326', 'An Stelle von WMS_SRS
WMT_MS_Capabilities/Capability/Layer/SRS
WMT_MS_Capabilities/Capability/Layer/Layer[*]/SRS
WFS_Capabilities/FeatureTypeList/FeatureType[*]/SRS
unless differently defined in LAYER object
if you are setting > 1 SRS for WMS, you need to define "wms_srs" and "wfs_srs"
seperately because OGC:WFS only accepts one OUTPUT SRS
', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (152, 'WFS_SRS', '', 'EPSG:25833', '', 'string', 'OWS-METADATEN', '', 1, 2);
INSERT INTO kvwmap.config VALUES (153, 'METADATA_AUTH_LINK', '', 'http://berg.preagro.de:8088/geonetwork/srv/en/xml.user.login?username=admin&password=!admin!', 'URL zum Authentifizieren am CSW-Metadatensystem
', 'string', 'z CSW-Metadatensystem', '', 1, 2);
INSERT INTO kvwmap.config VALUES (154, 'METADATA_ONLINE_RESOURCE', '', 'http://berg.preagro.de:8088/geonetwork/srv/en/csw', 'URL zum CSW-Server
', 'string', 'z CSW-Metadatensystem', '', 1, 2);
INSERT INTO kvwmap.config VALUES (155, 'METADATA_EDIT_LINK', '', 'http://berg:8088/geonetwork/srv/en/metadata.edit?id=', 'URL zum Editieren von Metadaten im CSW-Metadatensystem
', 'string', 'z CSW-Metadatensystem', '', 1, 2);
INSERT INTO kvwmap.config VALUES (156, 'METADATA_EDIT_LINK', '', 'http://berg:8088/geonetwork/srv/en/metadata.edit?id=', 'URL zum Editieren von Metadaten im CSW-Metadatensystem
', 'string', 'z CSW-Metadatensystem', '', 1, 2);
INSERT INTO kvwmap.config VALUES (157, 'LOGIN_AGREEMENT', 'SNIPPETS', 'login_agreement.php', 'PHP-Seite, welche die Agreement-Message anzeigt', 'string', 'Layout', NULL, 1, 3);
INSERT INTO kvwmap.config VALUES (158, 'LOGIN_NEW_PASSWORD', 'SNIPPETS', 'login_new_password.php', 'PHP-Seite, auf der man ein neues Passwort vergeben kann', 'string', 'Layout', NULL, 1, 3);
INSERT INTO kvwmap.config VALUES (159, 'LOGIN_REGISTRATION', 'SNIPPETS', 'login_registration.php', 'PHP-Seite, auf der man sich registrieren kann', 'string', 'Layout', NULL, 1, 3);
INSERT INTO kvwmap.config VALUES (160, 'LOGIN_ROUTINE', 'CUSTOM_PATH', 'layouts/snippets/login_routine.php', 'hier kann eine PHP-Datei angegeben werden, welche beim Login-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (161, 'LOGOUT_ROUTINE', 'CUSTOM_PATH', 'layouts/snippets/logout_routine.php', 'hier kann eine PHP-Datei angegeben werden, welche beim Logout-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (162, 'USE_EXISTING_SESSION', '', 'false', 'Wenn man auf einem Server mehrere kvwmap-Instanzen laufen hat und möchte, dass ein Nutzer sich nur einmal an einer Instanz anmelden muss, kann man diesen Parameter auf true setzen. Voraussetzung ist natürlich, dass die kvwmap-Instanzen die gleichen Nutzerdaten verwenden.', 'boolean', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (163, 'OWS_HOURSOFSERVICE', '', 'Wochentags 8:00 - 16:00 Uhr', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (164, 'OWS_CONTACTINSTRUCTIONS', '', 'Telefon oder E-Mail', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (165, 'OWS_ROLE', '', 'GIS-Administrator', 'Metadatenelement zur Beschreibung von Webdiensten im Anwendungsfall go=OWS', 'string', 'OWS-METADATEN', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (166, 'CUSTOM_RASTER', 'SHAPEPATH', 'custom_raster/', 'Das Verzeichnis, in dem die von den Nutzern hochgeladenen Rasterdateien abgelegt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (167, 'OGR_BINPATH_GDAL', '', '/usr/local/gdal/bin/', 'Wenn man dem ogr oder gdal Befehl docker exec gdal voranstellt, wird das ogr bzw. gdal in dem gdal Container verwendet statt des ogr bzw. gdal im Web Container. Diese Konstante gibt an wo sich das Bin-Verzeichnis innerhalb des verwendeten GDAL-Containers befindet.', 'string', 'Pfadeinstellungen', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (168, 'PASSWORD_INFO', '', '', 'Hier kann ein Hinweistext eingetragen werden, welcher bei der Passwortvergabe erscheint.', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (169, 'GEO_NAME_SEARCH_URL', '', 'https://nominatim.openstreetmap.org/search.php?format=geojson&q=', 'URL eines Geo-Namen-Such-Dienstes. Der Dienst muss GeoJSON zurückliefern.', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (170, 'GEO_NAME_SEARCH_PROPERTY', '', 'display_name', 'Das Attribut welches als Suchergebnis bei der Geo-Namen-Suche angezeigt werden soll.', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (171, 'CUSTOM_PATH', '', 'custom/', 'Pfad in dem sich Dateien befinden, die nicht vom kvwmap Repository getrackt werden.', 'string', 'Pfadeinstellungen', NULL, 1, 0);
INSERT INTO kvwmap.config VALUES (172, 'BG_IMAGE', 'GRAPHICSPATH', 'bg.gif', 'Hintergrundbild für die Oberfläche', 'string', 'Layout', NULL, 1, 3);
INSERT INTO kvwmap.config VALUES (173, 'ROLLENFILTER', '', 'false', 'Legt fest, ob Nutzer eigene Filter für Layer erstellen können.', 'boolean', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (174, 'NORMALIZE_AREA_THRESHOLD', '', '0.5', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden mit dem Winkel im mittleren Stützpunkt kleiner als NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.5. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (175, 'NORMALIZE_ANGLE_THRESHOLD', '', '0.5', 'Maximale Winkelgröße im mittleren Stützpunkt von 3 benachbarten Stützpunkten, deren Fläche kleiner als NORMALIZE_AREA_THRESHOLD ist. Zentralpunkte in denen der Winkel kleiner ist werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Angegeben in Dezimalgrad. Default 0.5 Grad.  Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (176, 'NORMALIZE_POINT_DISTANCE_THRESHOLD', '', '0.005', 'Maximaler Abstand von benachbarten Punkten in einem Dreieck welches kleiner ist als NORMALIZE_AREA_THRESHOLD unter Berücksichtigung von NORMALIZE_ANGLE_THRESHOLD verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Meter. Ein Punkt bei dem der Abstand zum anderen kleiner wird bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.005. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (177, 'NORMALIZE_NULL_AREA', '', '0.0001', 'Maximale Flächengröße von Dreiecken, die durch 3 benachbarte Stützpunkte gebildet werden unabhängig von den Winkeln verwendet in der Einheit des verwendeten Koordinatensystems im Client. In der Regel Quadatmeter. Zentralpunkte, deren Flächen kleiner sind, werden bei der Differenzfunktion gelöscht und die Ergebnisgeometrie dadurch normalisiert. Default 0.0001. Verwendet wird der Parameter in der Funktion gdi_normalize_geometry übernommen von Gaspare Sganga, siehe https://gasparesganga.com/labs/postgis-normalize-geometry/', 'double precision', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (178, 'POSTGRES_CONNECTION_ID', '', '1', 'ID der Postgresql-Datenbankverbindung aus Tabelle connections', 'numeric', 'Datenbanken', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (179, 'MS_DEBUG_LEVEL', '', '0', 'Legt den Debug-Level für MapServer fest. Werte von 0 bis 5 sind möglich.', 'integer', 'Logging', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (180, 'MAILSMTPUSER', '', 'kvwmap', 'Nutzername für den Zugang zum SMTP-Server.
', 'string', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (181, 'MAILSMTPPASSWORD', '', 'secret', 'Passwort für den Zugang zum SMTP-Server.
', 'password', 'E-Mail Einstellungen', '', 1, 2);
INSERT INTO kvwmap.config VALUES (184, 'IMPORT_POINT_STYLE_ID', '', '3128', 'Hier kann ein eigener Style für den Datenimport von Punkt-Objekten eingetragen werden.', 'integer', 'Layout', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (185, 'NUTZER_ARCHIVIEREN', '', 'false', 'Ist dieser Parameter auf true gesetzt, werden Nutzer nicht gelöscht sondern archiviert.', 'boolean', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (208, 'QUERY_ONLY_ACTIVE_CLASSES', '', 'true', 'Wenn aktiviert, dann werden bei der Kartenabfrage nur aktive Klassen berücksichtigt.', 'boolean', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (209, 'OVERRIDE_LANGUAGE_VARS', '', 'false', 'Wenn mit true aktiviert, werden Variablen mit Texten der unterschiedlichen Sprachen durch Variablen in gleichnamigen custom-Dateien überschrieben falls vorhanden.', 'boolean', 'Layout', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (210, 'ROUTING_URL', '', '', 'URL eines Routing-Dienstes. Der Dienst muss GeoJSON zurückliefern. $start und $end sind die Platzhalter für den Start- bzw. Endpunkt der Route.', 'string', 'Administration', NULL, 1, 2);
INSERT INTO kvwmap.config VALUES (211, 'TXT_SCALEBAR', '', '#000000', 'Schriftfarbe der Maßstabsleiste', 'string', 'Layout', '', 1, 2);


--
-- TOC entry 5992 (class 0 OID 1301837)
-- Dependencies: 250
-- Data for Name: connections; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.connections VALUES (1, 'kvwmapsp', 'pgsql', 5432, 'kvwmapsp', 'kvwmap', 'ujxT4cmIrbZLkfsJKznhYgI5');


--
-- TOC entry 5994 (class 0 OID 1301850)
-- Dependencies: 252
-- Data for Name: cron_jobs; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.cron_jobs VALUES (1, 'Lösche MapServer tmp Dateien', 'Löscht jeden Tag Dateien die älter als 1 Tag sind aus Verzeichnis /var/www/tmp', '1 1 * * *', '', 'find /var/www/tmp -mtime +1 ! -path /var/www/tmp -exec rm -rf {} +', NULL, 0, 0, 1, '', 'gisadmin');
INSERT INTO kvwmap.cron_jobs VALUES (2, 'Lösche Gastnutzer', 'Jeden Tag 01:01', '1 1 * * *', NULL, '/var/www/apps/kvwmap_intern/tools/deleteGastUser.sh', NULL, 2, 54, 1, NULL, 'gisadmin');


--
-- TOC entry 5996 (class 0 OID 1301863)
-- Dependencies: 254
-- Data for Name: datasources; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 5999 (class 0 OID 1301877)
-- Dependencies: 257
-- Data for Name: datatype_attributes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 5998 (class 0 OID 1301871)
-- Dependencies: 256
-- Data for Name: datatypes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6001 (class 0 OID 1301905)
-- Dependencies: 259
-- Data for Name: datendrucklayouts; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6002 (class 0 OID 1301925)
-- Dependencies: 260
-- Data for Name: ddl2freilinien; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6003 (class 0 OID 1301928)
-- Dependencies: 261
-- Data for Name: ddl2freirechtecke; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6004 (class 0 OID 1301931)
-- Dependencies: 262
-- Data for Name: ddl2freitexte; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6005 (class 0 OID 1301934)
-- Dependencies: 263
-- Data for Name: ddl2stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6007 (class 0 OID 1301938)
-- Dependencies: 265
-- Data for Name: ddl_colors; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.ddl_colors VALUES (1, 200, 200, 200);
INSERT INTO kvwmap.ddl_colors VALUES (2, 215, 215, 215);
INSERT INTO kvwmap.ddl_colors VALUES (3, 230, 230, 230);
INSERT INTO kvwmap.ddl_colors VALUES (4, 181, 217, 255);
INSERT INTO kvwmap.ddl_colors VALUES (5, 218, 255, 149);
INSERT INTO kvwmap.ddl_colors VALUES (6, 255, 203, 172);


--
-- TOC entry 6008 (class 0 OID 1301945)
-- Dependencies: 266
-- Data for Name: ddl_elemente; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6010 (class 0 OID 1301953)
-- Dependencies: 268
-- Data for Name: druckausschnitte; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6012 (class 0 OID 1301958)
-- Dependencies: 270
-- Data for Name: druckfreibilder; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6014 (class 0 OID 1301964)
-- Dependencies: 272
-- Data for Name: druckfreilinien; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6016 (class 0 OID 1301973)
-- Dependencies: 274
-- Data for Name: druckfreirechtecke; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6018 (class 0 OID 1301982)
-- Dependencies: 276
-- Data for Name: druckfreitexte; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6020 (class 0 OID 1301990)
-- Dependencies: 278
-- Data for Name: druckrahmen; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.druckrahmen VALUES ('A4 hoch', 1, NULL, 'A4-hoch.jpg', 0, 0, 595, 842, 46, 50, 279, 400, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 303, 724, 0, 422, 74, 9, NULL, NULL, 422, 87, 9, NULL, NULL, NULL, NULL, NULL, NULL, 238, 54, 9, 238, 64, 9, NULL, NULL, NULL, 58, 50, 0, 530, 710, 75, 0, 0, 0, 155, 155, '', 120, 45, 77, NULL, 'A5hoch', 1050, 'Helvetica.afm', 'Helvetica.afm', NULL, NULL, 'Helvetica.afm', 'Helvetica.afm', NULL, NULL, 'Helvetica.afm', 'Times-Italic.afm', 'Helvetica.afm');


--
-- TOC entry 6021 (class 0 OID 1302012)
-- Dependencies: 279
-- Data for Name: druckrahmen2freibilder; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6022 (class 0 OID 1302015)
-- Dependencies: 280
-- Data for Name: druckrahmen2freitexte; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6023 (class 0 OID 1302018)
-- Dependencies: 281
-- Data for Name: druckrahmen2stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6024 (class 0 OID 1302021)
-- Dependencies: 282
-- Data for Name: invitations; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6026 (class 0 OID 1302029)
-- Dependencies: 284
-- Data for Name: labels; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6028 (class 0 OID 1302044)
-- Dependencies: 286
-- Data for Name: layer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.layer VALUES (1, 'BaseMap DE farbig', NULL, '', NULL, NULL, 'BaseMap DE farbig', 3, 32, '', '', '', '', 0, '', '', NULL, '', '', '', '', '', '', '', NULL, NULL, '', 0, 'https://sgx.geodatenzentrum.de/wms_basemapde?VERSION=1.1.0&FORMAT=image/png&STYLES=&LAYERS=de_basemapde_web_raster_farbe', NULL, '', 7, '', '', '', NULL, 3, 'pixels', NULL, '25833', NULL, NULL, false, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:25833 EPSG:25832 EPSG:4326', 'de_basemapde_web_raster_farbe', '', '1.1.1', 'image/png', 60, '', '', '', NULL, 'radio', false, false, '1.3.0', 'Der WMS DE basemap.de Web Raster hat als Datengrundlage die basemap.de Web Vektor. Die Darstellung dieser beruht auf einer bundesweit einheitlichen Definition des Webkarten-Signaturenkataloges (basemap.de Web-SK) der AdV. Es wird die basemap.de Web-SK Version in der jeweils aktuellen Fassung verwendet. Informationen zur Aktualität der Daten und zur jeweiligen Version können unter https://www.basemap.de/data/produkte/web_raster/meta/bm_web_raster_datenaktualitaet.html eingesehen werden. ', NULL, 'Dienstleistungszentrum des Bundes für Geoinformation und Geodäsie', 'dlz@bkg.bund.de', '+49 (0) 341 5634 333', NULL, NULL, 'https://sgx.geodatenzentrum.de/wms_basemapde?Service=WMS&Request=GetCapabilities', NULL, NULL, 0, 1, '', '', false, 1, 1, NULL, NULL, NULL, '1.1.1', NULL, NULL, 1);
INSERT INTO kvwmap.layer VALUES (2, 'BaseMap DE grau', NULL, '', NULL, NULL, 'BaseMap DE grau', 3, 32, '', '', '', '', 0, '', '', NULL, '', '', '', '', '', '', '', NULL, NULL, '', 0, 'https://sgx.geodatenzentrum.de/wms_basemapde?VERSION=1.1.0&FORMAT=image/png&STYLES=&LAYERS=de_basemapde_web_raster_grau', NULL, '', 7, '', '', '', NULL, 3, 'pixels', NULL, '25833', NULL, NULL, false, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'EPSG:25833 EPSG:25832 EPSG:4326', 'de_basemapde_web_raster_grau', '', '1.1.1', 'image/png', 60, '', '', '', NULL, 'radio', false, false, '1.3.0', 'Der WMS DE basemap.de Web Raster hat als Datengrundlage die basemap.de Web Vektor. Die Darstellung dieser beruht auf einer bundesweit einheitlichen Definition des Webkarten-Signaturenkataloges (basemap.de Web-SK) der AdV. Es wird die basemap.de Web-SK Version in der jeweils aktuellen Fassung verwendet. Informationen zur Aktualität der Daten und zur jeweiligen Version können unter https://www.basemap.de/data/produkte/web_raster/meta/bm_web_raster_datenaktualitaet.html eingesehen werden. ', NULL, 'Dienstleistungszentrum des Bundes für Geoinformation und Geodäsie', 'dlz@bkg.bund.de', '+49 (0) 341 5634 333', NULL, NULL, 'https://sgx.geodatenzentrum.de/wms_basemapde?Service=WMS&Request=GetCapabilities', NULL, NULL, 0, 1, '', '', false, 1, 1, NULL, NULL, NULL, '1.1.1', NULL, NULL, 1);


--
-- TOC entry 6029 (class 0 OID 1302112)
-- Dependencies: 287
-- Data for Name: layer_attributes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6030 (class 0 OID 1302139)
-- Dependencies: 288
-- Data for Name: layer_attributes2rolle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6031 (class 0 OID 1302149)
-- Dependencies: 289
-- Data for Name: layer_attributes2stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6033 (class 0 OID 1302154)
-- Dependencies: 291
-- Data for Name: layer_charts; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6034 (class 0 OID 1302166)
-- Dependencies: 292
-- Data for Name: layer_datasources; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6035 (class 0 OID 1302169)
-- Dependencies: 293
-- Data for Name: layer_labelitems; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6036 (class 0 OID 1302173)
-- Dependencies: 294
-- Data for Name: layer_parameter; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6037 (class 0 OID 1302178)
-- Dependencies: 295
-- Data for Name: migrations; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-09-04_11-32-14_alter_datasources_name.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-08-21_14-14-15_add_client_id.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-02-04_14-26-51_bug_st_length_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-11-14_16-17-17_fix_line_interpolate_functions.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-10-21_09-10-12_line_interpolate_functions.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-07-20_10-22-56_add_filter_rings_function.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-03-08_14-10-31_uko_polygon_dateiname.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-02-20_20-36-34_add_func_replace_line_feeds.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-02-20_20-42-06_add_func_insert_str_before_and_after.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-06-07_14-22-48_classes_classification.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-05-29_09-16-26_params_uppercase2.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-05-29_08-31-54_params_uppercase.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-05-14_11-49-21_add_use_preview_in_ddl.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-05-08_11-00-24_last_query_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-04-08_14-12-08_drop_fontsize_gle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2014-09-12_16-33-22_Version2.0.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2014-11-07_11-37-59_layer_attributes_Autovervollstaendigungsfeld.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2014-11-24_10-29-21_druckrahmen_lage_gemeinde_flurst.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2014-11-27_11-16-24_druckrahmen_scalebar.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2014-12-03_10-25-40_zwischenablage.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-01-14_16-44-46_styles_initialgap_opacity.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-02-20_11-11-00_rolle_instant_reload_menu_auto_close.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-03-12_15-03-13_styles_colorrange_datarange.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-03-16_15-15-35_rollenlayer_query.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-03-17_10-29-08_rollenlayer_queryStatus.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-03-27_11-36-34_rollenlayer_Data_longtext.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-05-05_13-53-32_u_polygon2used_layer_loeschen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-05-07_11-29-06_search_attributes2rolle_PK.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-05-08_14-03-33_rolle_last_query_sql_longtext.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-05-22_14-34-59_layer_postlabelcache.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-05-28_11-08-55_rolle_auto_map_resize.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-06-16_16-16-14_u_rolle2usedlayer_gle_view.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-06-23_16-29-07_layer_requires.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-08-05_14-02-01_rolle_saved_layers.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-09-09_15-10-25_rolle_saved_layers_query.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-10-07_09-19-25_layer_attributes_StelleID.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-10-29_13-50-26_u_consumeALB_format.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2015-12-08_12-01-43_user_loginname.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-01-07_11-26-53_datendrucklayouts_gap.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-01-18_11-43-24_u_consume_epsg_code.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-01-26_14-04-28_druckrahmen_dhk_call.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-02-09_15-34-14_layer_cluster_maxdistance.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-02-17_13-55-03_rolle_coord_query.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-04-21_10-42-03_stelle_gemeinden_gemarkung_flur.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-04-28_16-35-05_add_data_query_params_to_u_rolle2used.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-04-28_16-35-05_add_layer_params.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-05-31_11-22-56_stelle_hist_timestamp.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-05-31_13-18-54_stelle_wasserzeichen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-06-22_16-24-48_layer_attributes_arrangement_labeling.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-06-29_10-50-56_layer_maintable_is_view.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-06-30_10-54-02_u_rolle2used_layer_transparency.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-07-12_15-46-25_used_layer_use_geom.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-07-29_14-24-25_add_visually_impaired_to_rolle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-08-17_13-57-20_add_datatypes_and_datatype_attributes.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-08-29_11-45-00_datatypes_dbname_host_port.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-08-30_14-11-10_add_trigger_function_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-10-05_11-09-37_styles_symbolname.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-10-05_12-11-57_styles_rangeitem.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-11-22_15-23-00_set_relative_fonts_pfade.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-11-23_11-47-01_layer_classification.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2016-11-24_10-56-43_styles_minscale_maxscale.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-01-10_13-46-46_cronjobs.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-01-12_13-19-18_datendrucklayouts_no_record_splitting.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-02-02_15-57-57_add_db_to_cronjobs.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-03-24_14-56-40_add_custom_legend_graphic_and_order.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-03-29_16-48-03_add_title_to_menues.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-05_16-14-15_add_default_rolle_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-06_09-59-50_add_showmapfunctions_to_rolle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-07_12-17-10_add_showlayeroptions.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-13_17-00-45_add_onclick_on_menues.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-20_15-17-29_u_menues_button_class.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-20_15-59-03_rolle_menue_buttons.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-04-21_10-49-49_u_menues_logout.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-06-07_16-01-20_add_dont_use_for_new.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-04_14-37-35_rolle_gui_button.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-11_10-20-03_layer_attributes_radiobutton.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-11_14-57-05_druckfreilinien.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-13_10-37-26_layer_attributes_Winkel.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-14_13-51-46_classes_legendimageheight_width.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-07-17_09-31-55_label_maxlength.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-08-31_14-16-16_delete_legend_order_in_group_and_used_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-09-11_15-40-24_u_menues_Druckausschnittswahl.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-09-13_10-39-04_add_stellen_hierarchie.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-09-19_12-04-37_styles_polaroffset.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-10-08_09-51-31_add_sync_attr_to_layers.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-11-02_12-14-38_rolle_legendtype.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-11-14_10-08-33_u_rolle2used_layer_drawingorder.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2017-11-16_14-01-29_datendrucklayouts_filename.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-01-04_14-11-01_layer_legendorder.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-01-08_14-09-22_add_style_attribute_type.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-02-01_16-14-00_layer_attributes_invisible.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-03-22_16-22-21_fk_for_users.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-03-26_12-48-46_improve_innodb_performance.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-04-03_17-01-06_add_num_login_failed_to_users.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-04-06_18-15-25_add_invitations.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-04-11_14-08-25_editable.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-04-16_11-37-08_layer_listed.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-05-16_11-23-39_user_agreement_accepted.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-05-25_16-26-55_layer_document_url.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-06-25_16-40-37_add_visible_and_arrangement_to_datatype_attributes.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-07-03_11-49-35_layer_attributes_vcheck.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-07-23_15-45-15_datatype_foreign_keys.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-08-23_16-47-03_fk_for_menues.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-08-24_11-14-04_rollenlayer_classitem.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-09-07_14-34-53_config.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-09-19_14-34-53_config.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-01_12-35-08_login_constants.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-11_16-50-18_login-logout_routine.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-12_15-30-58_druckfreilinien_offset.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-15_10-01-29_druckfreilinien_breite.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-15_11-49-53_USE_EXISTING_SESSION.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-19_16-08-48_print_legend_separate.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-10-25_13-11-16_custom_labelitem.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-11-07_13-42-05_metadata_constants.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-11-09_11-30-36_sizeitem_entfernt.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-11-26_11-29-39_custom_raster.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-12-13_15-05-58_add_OGR_BINPATH_GDAL.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2018-12-18_15-31-29_layer_hist_timestamp.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-02-07_11-42-12_alb_raumbezug.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-02-08_09-16-51_u_rolle2used_layer_geom_from_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-02-14_17-30-53_add_protected_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-02-28_10-35-41_config_PASSWORD_INFO.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-05_09-22-46_add_editiersperre_attribute_type.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-06_15-31-28_geo_name_search.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-07_14-13-59_Menue_Stelle_waehlen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-11_10-06-54_rolle_print_scale.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-13_09-23-22_public_comments.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-03-18_10-33-37_rollenlayer_gle_view.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-04-01_11-20-10_add_kvp_to_layer_attrb.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-04-09_17-10-26_change_menue_link_length.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-04-14_13-09-22_add_rolle2used_layer_filter.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-04-25_13-11-02_change_user_phon_and_mail_length.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-04-28_16-44-58_add_further_attribute_table_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-02_17-14-53_geom_buttons.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-03_11-36-09_change_rolle_default_values.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-11_10-29-24_add_zweispaltiges_autovervollstaendigungsfeld.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-14_13-02-51_wms_keywordlist.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-16_16-29-24_bug_zweispaltiges_autovervollstaendigungsfeld.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-05-17_11-41-14_drop_further_attributes.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-06-03_10-35-43_showrollenfilter.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-06-25_12-09-43_add_org_and_pos_to_user.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-07-25_14-31-16_set_rollenlayer_gle_view_default.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-07-29_11-38-48_u_menues_schnelle_Druckausgabe.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-07-31_08-59-09_gle_view_not_null.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-08-25_16-40-16_outsource_custom_files.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-08-29_16-17-17_datendrucklayouts_margins.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-09-23_10-03-03_ddl_columns.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-10-01_14-16-57_sicherungen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-10-04_15-35-07_add_ddl_attibute_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-10-18_14-08-32_layer_identifier.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-11-01_13-04-10_config_name.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-11-14_13-45-06_add_stellen_style.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-11-19_14-25-41_rolle_gui_default.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-12-04_11-49-03_rolle_result_style.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-12-05_09-36-56_layer_attributes_constraints.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-12-06_09-48-49_SubformPK_privileg.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-12-06_10-01-59_config_bg_image.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2019-12-06_10-19-31_config_rollenfilter.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-01-07_14-51-45_ddl_format.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-01-29_16-10-32_styleitem.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-02-21_09-55-59_styles_width_attribute.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-02-28_15-26-23_layer_attributes_saveable.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-03-19_11-22-21_druckfreitexte_width_border.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-04-02_19-52-03_config_normalize_geometry.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-04-14_13-30-54_epsg_code_druckausschnitte.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-04-16_11-02-51_add_column_user_to_cron_jobs.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-04-21_10-25-24_add_duplicate_columns_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-05-12_12-09-27_add_exif_attribute_types.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-05-25_10-41-36_druckfreirechtecke.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-05-28_10-57-58_ddl_colors.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-06-02_10-16-50_change_layer_attributes_tooltip_type.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-06-04_10-29-54_NORMALIZE_Parameter_verschieben.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-03_11-12-57_change_collations.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-03_11-12-58_add_layer_attributes2rolle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-03_14-40-28_config_postgres_connection_id.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-08_15-32-53_add_connection_id_to_rollenlayer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-15_12-59-27_label_REPEATDISTANCE.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-15_14-28-12_add_connection_id_to_datatyps.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-07-19_17-29-52_add_php_sql_parser.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-08-18_09-10-17_layer_use_geom.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-08-19_09-20-04_layer_max_query_rows.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-08-25_12-13-32_drop_filteritem.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-10-02_13-56-39_Indizes.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-11-06_10-00-00_migrate_to_3.0.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-11-06_11-42-05_Version3.0.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-11-24_11-11-29_add_const_ms_debug_level.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2020-12-10_23-01-33_add_rolle_attribut_immer_weiter_erfassen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-01-20_16-15-36_add_mailsmtpuser_passwd_constants.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-01-25_13-07-32_add_rollenlayer_freigabe_attribute.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-02-16_10-50-18_rolle_export_settings.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-02-18_09-41-33_layer_attributes_schema.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-01_13-24-21_adapt_sicherung_schema.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-01_15-15-35_adapt_sicherung_schema2.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-03_13-51-07_sicherungen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-08_13-14-45_drop_antialias.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-15_12-21-50_add_const_copy_mail_attachment.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-15_13-00-50_use_parent_privileges.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-03-26_13-52-17_add_wms_auth_to_rollenlayer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-05-19_10-03-58_layer_attributes_Farbauswahl.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-05-20_12-04-49_layer_attributes_tab.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-05-25_09-38-14_config_IMPORT_POINT_STYLE_ID.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-06-28_20-41-13_belated_files.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-09-22_10-13-14_belated_files_lastmodified.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-09-22_11-34-48_drop_sicherungen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-11-15_15-35-23_add_redline_options.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-11-23_11-00-05_layer_sizeunits.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-11-24_10-00-19_add_layer_metadata.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-12-21_11-11-21_invitations_login_name.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2021-12-23_14-49-19_change_enum_columns.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-01-11_15-13-08_zwischenablage_oid.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-01-16_11-38-45_add_icon_to_layer_groups.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-01-18_14-24-10_change_color_attribute.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-03-08_13-44-15_layer_oid_null.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-03-09_09-49-07_stelle_drop_pg_conn.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-03-15_13-57-47_postlabelcache.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-04-28_09-08-38_invitation_anrede.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-05-16_07-49-58_identifier_text.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-05-31_11-37-31_config_NUTZER_ARCHIVIEREN.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-05-31_11-48-07_user_archived.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-09_12-52-40_add_layer_version_and_comment_attribute.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-15_08-16-53_Auswahlfeld_Bild.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-16_08-28-51_layer_data_import_allowed.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-23_06-39-33_rollenlayer_connection.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-28_19-10-12_add_metadata_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-06-30_10-12-34_add_more_metadata_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-07-05_10-49-10_update_jquery_and_bootstrap.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-07-19_14-25-29_add_ows_namespace_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-08-09_14-23-43_styles_angleitem_null.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-08-17_09-41-48_user_tokens.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-08-22_11-11-31_rolle_tooltipquery.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-09-02_07-28-07_user_tokens.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-09-04_22-24-27_sha1_user_password.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-10_14-14-48_add_login_locked_until.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-17_08-11-28_drop_DELETE_ROLLENLAYER.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-17_13-45-40_label_text.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-17_14-18-57_label_minscale_maxscale.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-21_13-11-42_rollenlayer_buffer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-11-25_13-11-42_rename_Suchergebnis_to_eigene_Abfragen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-12-14_00-19-18_add_font_size_factor_to_user_settings.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-12-19_14-43-11_add_multiple_notifications.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2022-22-29_14-13-17_change_layer_oid_default.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-01-02_13-35-17_add_write_mapserver_templates_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-02-03_15-14-42_add_password_expired_to_users.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-02-23_15-18-47_anglemode.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-04-20_09-10-42_stelle_foreign_keys.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-03_15-02-27_default_ohne_select.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-09_13-50-46_layer_drawingorder.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-11_09-59-51_FKs_used_layer_layer_attributes2stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-23_10-25-19_rollenlayer_autodelete.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-23_13-53-55_datasources.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-05-25_05-12-58_add_ows_updatesequence_to_stelle.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-01_15-52-14_add_dataset_operations_position.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-06_13-53-51_layercharts.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-12_10-59-37_user_userdata_checking_time.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-19_17-57-12_change_write_mapserver_templates_to_layer.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-27_12-46-51_datatypes_drop_dbname_host_port.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-06-30_15-26-14_datatype_attributes_layer_id.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-07-06_12-33-57_QUERY_ONLY_ACTIVE_CLASSES.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-07-06_15-05-03_add_geom_column.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-07-17_09-49-57_Change_OWS_SERVICE_ONLINERESOURCE_write_permission.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-07-17_14-10-12_change_wappen_default.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-09-04_19-48-12_add_chartjs_to_3rdparty.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-10-05_21-57-32_add_user_to_notification.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-10-09_15-38-33_add_label_to_dll.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-03-22_20-10-31_alter_real_name_length.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-03-20_10-20-13_layer_charts_beschreibung_breite.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-03-01_11-40-30_add_layer_datasources.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-01-31_08-14-04_styles_minwith_maxwidth_decimal.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-09-05_14-04-12_change_class_name_length.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2024-01-18_17-01-23_gdi_conditional_nextval.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-11-25_12-31-24_privilege_backup.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-10-14_13-04-07_update_gdi_normalize_geometry.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-08-31_18-20-37_add_get_deltas_function.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-08-09_14-28-05_convert_column_names.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-06-27_12-24-31_add_function_in_date_ranges.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-05-31_11-59-17_id_for_q_notizen.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2022-05-18_11-37-14_u_polygon.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2021-11-11_15-26-30_function_gdi_MakeValidPolygons.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2020-11-17_10-19-40_change_filter_rings_function.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2020-11-13_10-12-13_change_filter_rings_function.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2020-04-02_22-12-42_add_function_normalize_geometry.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2020-01-13_11-23-26_drop_uko_polygon.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-11-12_10-34-27_bug_convert_column_names.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-04-25_17-02-24_bug_convert_column_names.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-04-24_11-34-36_convert_column_names.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-02-14_15-57-39_func_linelocatepointwithoffset.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-02-14_15-54-35_func_gdi_lineinterpolatepointwithoffset.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2023-05-22_18-53-17_json_to_text_functions.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2019-02-14_15-49-15_function_extend_line.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2014-09-12_16-33-22_Version2.0.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2015-05-06_08-47-01_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2015-05-28_14-07-36_bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2015-05-29_09-33-07_bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2017-04-28_14-42-46_Bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2017-04-28_14-49-32_Bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2017-05-03_09-08-07_Bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2017-05-03_12-13-08_Bug_st_area_utm.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2017-05-15_15-28-45_spatial_ref_sys_srs_params.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2018-02-08_11-30-00_rename_if_exisits.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-11-27_12-59-19_reset_password_text.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-12-05_09-35-40_invitation_text.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-01-01_19-39-23_add_used_layer_group_id.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-10-30_09-28-37_add_menue_fk.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-11-24_11-29-11_offsetxy_text.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-12-08_11-22-35_config_OWERRIDE_LANGUAGE_VARS.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2023-12-08_11-25-56_create_custom_language_and_ccs_readme_files.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-01-08_17-52-40_add_primary_key_user2notifications.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-11-20_10-05-04_add_commend_to_migrations.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-11-27_10-13-17_drop_used_layer_drawingorder.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2023-08-31_14-27-17_gdi_codelist_json_to_text_type.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2024-02-20_18-26-07_gdi_parent_val.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2024-03-22_19-46-14_gdi_conditional_val_und_gdi_current_date.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2024-09-12_gdi_md5_agg_func.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-12-18_08-36-20_layer_terms_of_use_link.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-01-16_13-38-31_ROUTING_URL.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-02-27_11-29-37_layer_labelitems.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-02-27_11-44-26_fill_layer_labelitems.php', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-02-28_10-47-31_rollenlayer_original_layer_id.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-03-26_09-42-01_layer_labelitems_Cluster_FeatureCount.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2025-01-31_11-59-14_change_gdi_conditional_nextval.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2025-04-01_11-43-29_pgcrypto.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2025-04-25_12-32-19_sha1.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2024-10-09_13-21-14_add_onlineressource.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'postgresql', '2025-04-25_12-15-47_schema_kvwmap.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-04-26_11-48-24_extend_MENU_REFMAP.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-04-30_10-13-04_TXT_SCALEBAR.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-05-02_11-14-25_alter_ows_abstract_type.sql', NULL);
INSERT INTO kvwmap.migrations VALUES ('kvwmap', 'mysql', '2025-05-14_09-56-04_routing_button_optional.sql', NULL);


--
-- TOC entry 6039 (class 0 OID 1302184)
-- Dependencies: 297
-- Data for Name: notifications; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6041 (class 0 OID 1302191)
-- Dependencies: 299
-- Data for Name: referenzkarten; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.referenzkarten VALUES (1, 'Landkreis Doberan', 'uebersicht_kreise.png', 2398, 4.463906e+06, 5.966941e+06, 4.543877e+06, 6.018614e+06, 200, 135);
INSERT INTO kvwmap.referenzkarten VALUES (2, 'Elmenhorst-Lichtenhagen', 'referenz13051019.png', 2398, 4.498014e+06, 6.000026e+06, 4.5036585e+06, 6.0056705e+06, 200, 200);
INSERT INTO kvwmap.referenzkarten VALUES (3, 'Mecklenburg-Vorpommern', 'uebersicht_mv.png', 2398, 4.405e+06, 5.88e+06, 4.662e+06, 6.07e+06, 205, 146);
INSERT INTO kvwmap.referenzkarten VALUES (4, 'Neubrandenbrug', 'uebersicht_ndbg.png', 2398, 4.561155e+06, 5.9125e+06, 4.607345e+06, 5.96069e+06, 180, 172);
INSERT INTO kvwmap.referenzkarten VALUES (5, 'Thueringen', 'thueringen.png', 2398, 4.316874e+06, 5.522397e+06, 4.564766e+06, 5.783646e+06, 100, 87);
INSERT INTO kvwmap.referenzkarten VALUES (6, 'Rendsburg-Eckernförde', 'uebersicht_rdeck.png', 2398, 3.53856e+06, 6.01315e+06, 3.54768e+06, 6.02415e+06, 200, 239);
INSERT INTO kvwmap.referenzkarten VALUES (7, 'Mecklenburg-Strelitz', 'MecklenburgStrelitz.png', 2398, 4.5503e+06, 5.893e+06, 4.6188e+06, 5.9593e+06, 200, 194);
INSERT INTO kvwmap.referenzkarten VALUES (8, 'Brandenburg', 'brandenburg.jpg', 2398, 4.43878e+06, 5.686966e+06, 4.702954e+06, 5.936743e+06, 211, 200);
INSERT INTO kvwmap.referenzkarten VALUES (9, 'Brandenburg', 'uebersicht-ahnatal_klein.png', 2398, 4.312573e+06, 5.691992e+06, 4.324245e+06, 5.701082e+06, 210, 202);
INSERT INTO kvwmap.referenzkarten VALUES (10, 'Zentraleuropa', 'central_europe_200x244.png', 2398, 3.259673e+06, 4.305062e+06, 5.504438e+06, 7.024297e+06, 200, 244);
INSERT INTO kvwmap.referenzkarten VALUES (11, 'Schleswig-Holstein', 'refmap-schleswig-holstein.png', 2398, 3.425602e+06, 5.914214e+06, 3.657022e+06, 6.104734e+06, 200, 165);


--
-- TOC entry 6042 (class 0 OID 1302204)
-- Dependencies: 300
-- Data for Name: rolle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.rolle VALUES (1, 1, 1402, 820, 1, 27837.492, 5.8321865e+06, 445228.6, 6.076186e+06, 2, 'zoomin', '25833', '4326', 'dec', 49, '2025-06-03 09:44:34', 'layouts/gui.php', 'german', false, false, 1, 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,freepolygon,freearrow,freetext,', 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure', 2, 6, 0, 60, 0, 1, 1, 1, 0, 0, 0, 0, 400, 150, NULL, 1, 1, 0, 1, '"jahr":"15","geschlecht":"g","datenreihe":"summe","umzuege":"bw_zu"', 0, 0, 0, 'auto', NULL, NULL, '#ff0000', 'Arial', 16, 'bold', 'unten', NULL);


--
-- TOC entry 6045 (class 0 OID 1302274)
-- Dependencies: 303
-- Data for Name: rolle_csv_attributes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6046 (class 0 OID 1302279)
-- Dependencies: 304
-- Data for Name: rolle_export_settings; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6047 (class 0 OID 1302284)
-- Dependencies: 305
-- Data for Name: rolle_last_query; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6048 (class 0 OID 1302289)
-- Dependencies: 306
-- Data for Name: rolle_nachweise; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6050 (class 0 OID 1302313)
-- Dependencies: 308
-- Data for Name: rolle_nachweise_dokumentauswahl; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6051 (class 0 OID 1302320)
-- Dependencies: 309
-- Data for Name: rolle_nachweise_rechercheauswahl; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6053 (class 0 OID 1302324)
-- Dependencies: 311
-- Data for Name: rolle_saved_layers; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6044 (class 0 OID 1302259)
-- Dependencies: 302
-- Data for Name: rollenlayer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6054 (class 0 OID 1302330)
-- Dependencies: 312
-- Data for Name: search_attributes2rolle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6056 (class 0 OID 1302337)
-- Dependencies: 314
-- Data for Name: stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.stelle VALUES (1, 'Administration', NULL, NULL, NULL, NULL, NULL, NULL, -174807, 5.230803e+06, 537558, 6.170173e+06, 8, 25833, 10, true, 30, 'Logo_GDI-Service_200x47.png', '', NULL, 'gdi', 'kvwmap-Demo Server', 'keine', 'Demoversion eines Web Service zur Bereitstellung von Geodaten aus den Bereichen des Katasters, der Landkreise', 'Peter Korduan', 'GDI-Service Rostock', NULL, NULL, NULL, 'peter.korduan@gdi-service.de', 'Geschäftsführer', '+49 381 403 44444', '+49 381 3378 9527', 'Friedrichstraße 16', '18057', 'Rostock', 'Mecklenburg-Vorpommern', '21', '22', '33', NULL, '34', '35', '36', '37', '38', '39', '3.10', '24', '25', '26', '27', '28', '29', '2.10', '2.11', '23', '31', '32', 'für Testzwecke frei', 'EPSG:25832 EPSG:25833 EPSG:4326 EPSG:2398', false, false, true, 6, true, false, '', NULL, NULL, false, '1.0.0', '', '', 'Test 2');


--
-- TOC entry 6058 (class 0 OID 1302405)
-- Dependencies: 316
-- Data for Name: stelle_gemeinden; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6057 (class 0 OID 1302400)
-- Dependencies: 315
-- Data for Name: stellen_hierarchie; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6060 (class 0 OID 1302412)
-- Dependencies: 318
-- Data for Name: styles; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6065 (class 0 OID 1302484)
-- Dependencies: 323
-- Data for Name: u_attributfilter2used_layer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6066 (class 0 OID 1302489)
-- Dependencies: 324
-- Data for Name: u_consume; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_consume VALUES (1, 1, '2024-01-14 17:40:56', 'getMap', 886, 580, '25833', -536536.8, 5.230803e+06, 899287.8, 6.170173e+06, '2024-01-14 17:24:48', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2024-01-14 22:14:08', 'getMap', 886, 580, '25833', -536536.8, 5.230803e+06, 899287.8, 6.170173e+06, '2024-01-14 17:40:56', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-01-29 14:56:46', 'getMap', 1288, 950, '25833', -455594.56, 5.230803e+06, 818345.56, 6.170173e+06, '2024-01-14 22:14:08', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-02-18 13:36:52', 'getMap', 1402, 834, '25833', -608574.9, 5.230803e+06, 971325.9, 6.170173e+06, '2025-01-29 14:56:46', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-02-18 13:37:50', 'getMap', 1320, 920, '25833', -492742.6, 5.230803e+06, 855493.56, 6.170173e+06, '2025-02-18 13:36:52', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-02-18 13:45:07', 'getMap', 1402, 834, '25833', -534651.3, 5.274756e+06, 897402.3, 6.12622e+06, '2025-02-18 13:37:50', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-02-18 13:45:48', 'getMap', 1402, 820, '25833', -534651.3, 5.2819115e+06, 897402.3, 6.1190645e+06, '2025-02-18 13:45:07', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-02 14:07:16', 'getMap', 1402, 820, '25833', -534651.3, 5.2819115e+06, 897402.3, 6.1190645e+06, '2025-02-18 13:45:48', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 08:57:45', 'getMap', 1402, 820, '25833', -534651.3, 5.2819115e+06, 897402.3, 6.1190645e+06, '2025-06-02 14:07:16', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 09:10:07', 'getMap', 1402, 820, '25833', -534651.3, 5.2819115e+06, 897402.3, 6.1190645e+06, '2025-06-03 08:57:45', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 09:33:59', 'getMap', 1402, 820, '25833', -534651.3, 5.2819115e+06, 897402.3, 6.1190645e+06, '2025-06-03 09:10:07', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 09:41:32', 'getMap', 1402, 820, '25833', -534650.94, 5.2819115e+06, 897401.94, 6.1190645e+06, '2025-06-03 09:33:59', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 09:41:36', 'getMap', 1402, 820, '25833', 27837.512, 5.8321865e+06, 445228.56, 6.076186e+06, '2025-06-03 09:41:32', NULL);
INSERT INTO kvwmap.u_consume VALUES (1, 1, '2025-06-03 09:44:34', 'getMap', 1402, 820, '25833', 27837.492, 5.8321865e+06, 445228.6, 6.076186e+06, '2025-06-03 09:41:36', NULL);


--
-- TOC entry 6067 (class 0 OID 1302494)
-- Dependencies: 325
-- Data for Name: u_consume2comments; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6068 (class 0 OID 1302500)
-- Dependencies: 326
-- Data for Name: u_consume2layer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6069 (class 0 OID 1302503)
-- Dependencies: 327
-- Data for Name: u_consumealb; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6070 (class 0 OID 1302506)
-- Dependencies: 328
-- Data for Name: u_consumealk; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6071 (class 0 OID 1302509)
-- Dependencies: 329
-- Data for Name: u_consumecsv; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6072 (class 0 OID 1302512)
-- Dependencies: 330
-- Data for Name: u_consumenachweise; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6073 (class 0 OID 1302528)
-- Dependencies: 331
-- Data for Name: u_consumeshape; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6074 (class 0 OID 1302531)
-- Dependencies: 332
-- Data for Name: u_funktion2stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_funktion2stelle VALUES (67, 1);


--
-- TOC entry 6076 (class 0 OID 1302537)
-- Dependencies: 334
-- Data for Name: u_funktionen; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_funktionen VALUES (1, 'ALB-Auszug 35', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (68, 'BplanAenderungLoeschen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (4, 'FestpunktDateiAktualisieren', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (5, 'FestpunktDateiUebernehmen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (6, 'Antrag_loeschen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (66, 'Haltestellen_Suche', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (8, 'Nachweisanzeige_zum_Auftrag_hinzufuegen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (9, 'Antrag_Aendern', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (10, 'FestpunkteSkizzenZuordnung_Senden', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (12, 'Nachweisanzeige_aus_Auftrag_entfernen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (13, 'ohneWasserzeichen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (14, 'Flurstueck_Anzeigen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (15, 'Bauakteneinsicht', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (16, 'Namensuche', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (69, 'migrationGewaesser', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (18, 'ALB-Auszug 40', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (67, 'Stelle_waehlen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (21, 'Nachweisloeschen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (22, 'ALB-Auszug 20', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (23, 'ALB-Auszug 25', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (24, 'Externer_Druck', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (26, 'Adressaenderungen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (29, 'sendeFestpunktskizze', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (65, 'Jagdkataster', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (59, 'Nachweise_bearbeiten', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (60, 'ALB-Auszug 30', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (70, 'upload_temp_file', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (71, 'pack_and_mail', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (2, 'Daten_Export', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (72, 'cronjobs_anzeigen', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (73, 'mobile_delete_images', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (74, 'mobile_download_image', NULL);
INSERT INTO kvwmap.u_funktionen VALUES (75, 'mobile_upload_image', NULL);


--
-- TOC entry 6078 (class 0 OID 1302546)
-- Dependencies: 336
-- Data for Name: u_groups; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_groups VALUES (5, 'Kataster', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (2, 'Test', '', '', '', '', NULL, NULL, false, '');
INSERT INTO kvwmap.u_groups VALUES (13, 'Gebietskarten', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (14, 'Orthophotos', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (31, 'eigene Abfragen', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (32, 'Übersichtskarten', '', '', '', '', NULL, 100, false, '');
INSERT INTO kvwmap.u_groups VALUES (45, 'Eigene Shapes', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (54, 'Freizeit', '', '', '', '', NULL, NULL, false, '');
INSERT INTO kvwmap.u_groups VALUES (1, 'Umwelt', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (60, 'Eigene Importe', NULL, NULL, NULL, NULL, NULL, 1, false, NULL);
INSERT INTO kvwmap.u_groups VALUES (61, 'Facilities', '', '', '', '', NULL, NULL, false, '');
INSERT INTO kvwmap.u_groups VALUES (63, 'Pläne', NULL, NULL, NULL, NULL, NULL, NULL, false, NULL);


--
-- TOC entry 6079 (class 0 OID 1302558)
-- Dependencies: 337
-- Data for Name: u_groups2rolle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 1, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 2, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 5, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 10, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 13, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 14, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 20, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 31, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 32, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 54, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 57, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 59, 0);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 60, 1);
INSERT INTO kvwmap.u_groups2rolle VALUES (1, 1, 61, 1);


--
-- TOC entry 6080 (class 0 OID 1302561)
-- Dependencies: 338
-- Data for Name: u_labels2classes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_labels2classes VALUES (4517, 294);
INSERT INTO kvwmap.u_labels2classes VALUES (4518, 295);


--
-- TOC entry 6081 (class 0 OID 1302566)
-- Dependencies: 339
-- Data for Name: u_menue2rolle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 17, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 50, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 30, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 64, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 21, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 22, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 27, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 28, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 35, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 39, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 42, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 46, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 47, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 49, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 51, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 63, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 65, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 72, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 73, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 74, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 76, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 77, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 79, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 126, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 142, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 143, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 144, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 147, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 148, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 149, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 151, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 152, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 154, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 174, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 186, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 215, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 216, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 239, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 251, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 269, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 274, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 301, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 303, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 305, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 306, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 312, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 314, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 315, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 316, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 7, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 20, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 45, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 141, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 78, 0);
INSERT INTO kvwmap.u_menue2rolle VALUES (1, 1, 241, 1);


--
-- TOC entry 6082 (class 0 OID 1302569)
-- Dependencies: 340
-- Data for Name: u_menue2stelle; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_menue2stelle VALUES (1, 7, 58);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 17, 1);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 20, 12);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 21, 13);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 22, 14);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 27, 9);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 28, 4);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 30, 50);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 35, 56);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 39, 44);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 42, 59);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 45, 20);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 46, 22);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 47, 23);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 49, 10);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 50, 24);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 51, 30);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 63, 48);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 64, 46);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 65, 21);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 72, 33);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 73, 34);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 74, 54);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 76, 5);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 77, 6);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 78, 41);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 79, 43);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 126, 27);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 141, 38);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 142, 40);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 143, 39);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 144, 35);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 147, 7);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 148, 15);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 149, 36);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 151, 3);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 152, 2);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 154, 45);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 174, 42);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 186, 25);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 215, 53);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 216, 55);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 239, 32);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 241, 18);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 251, 47);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 269, 17);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 274, 19);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 301, 31);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 303, 11);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 305, 26);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 306, 49);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 312, 16);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 314, 29);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 315, 37);
INSERT INTO kvwmap.u_menue2stelle VALUES (1, 316, 0);


--
-- TOC entry 6084 (class 0 OID 1302576)
-- Dependencies: 342
-- Data for Name: u_menues; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_menues VALUES (1, 'Gesamtansicht', 'Samtansicht', 'Full Extent', '', 'Gesamtansicht', 'index.php?go=Full_Extent', '', 0, 1, '', 20, '', 'gesamtansicht');
INSERT INTO kvwmap.u_menues VALUES (2, 'Karte anzeigen', 'Koort ankieken', 'Show Map', '', 'Hiá»ƒn thá»‹ báº£n Ä‘á»“', 'index.php?', '', 0, 1, '', 1, '', 'karte');
INSERT INTO kvwmap.u_menues VALUES (6, 'Dokumentenrecherche', 'Oorkunn-Sööke', 'document retrieval', NULL, NULL, 'index.php?go=Nachweisrechercheformular', NULL, 16, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (7, 'Hilfe', 'Hülp', 'Hilfe', '', 'Trá»£ giÃºp', 'index.php?go=changemenue', '', 0, 1, '', 1000, '', '');
INSERT INTO kvwmap.u_menues VALUES (8, 'Dokument&nbsp;einf&uuml;gen', 'Oorkunn&nbsp;inf&ouml;gen', 'Dokument&nbsp;einf&uuml;gen', NULL, 'Dokument&nbsp;einf&uuml;gen', 'index.php?go=Nachweisformular', NULL, 16, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (17, 'Import/Export', 'Import/Export', 'Import/Export', NULL, 'Nháº­p/Xuáº¥t', 'index.php?go=changemenue', NULL, 0, 1, NULL, 60, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (20, 'Nutzerverwaltung', 'Nutzerverwaltung', 'User Management', NULL, 'Quáº£n trá»‹ ngÆ°á»i dÃ¹ng', 'index.php?go=changemenue', NULL, 0, 1, NULL, 70, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (21, 'Nutzer anlegen', 'Nutzer anlegen', 'Create new User', NULL, 'Nutzer anlegen', 'index.php?go=Benutzerdaten_Formular', NULL, 20, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (22, 'Nutzer anzeigen', 'Nutzer anzeigen', 'List all Users', NULL, 'Danh sÃ¡ch ngÆ°á»i dÃ¹ng', 'index.php?go=Benutzerdaten_Anzeigen&order=ID', NULL, 20, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (24, 'Aktualisieren', 'Aktualisieren', 'Aktualisieren', NULL, 'Aktualisieren', 'index.php?go=FestpunktDateiAktualisieren', NULL, 23, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (27, 'WMS-Export', 'WMS-Export', 'WMS-Export', NULL, 'Xuáº¥t WMS', 'index.php?go=WMS_Export', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (28, 'PDF-Export', 'PDF-Export', 'PDF-Export', NULL, 'Xuáº¥t PDF', 'index.php?go=ExportMapToPDF', NULL, 17, 2, '_blank', 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (30, 'Suche', 'Suche', 'Search', NULL, 'TÃ¬m kiáº¿m', 'index.php?go=changemenue', NULL, 0, 1, NULL, 150, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (34, 'Metadateneingabe', 'Metadateneingabe', 'Metadateneingabe', NULL, 'Metadateneingabe', 'index.php?go=Metadateneingabe', NULL, 13, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (35, 'Metadaten', 'Metadaten', 'Metadata', NULL, 'Metadaten', 'index.php?go=Metadaten_Auswaehlen', NULL, 30, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (37, 'Suche', 'Suche', 'Suche', NULL, 'TÃ¬m kiáº¿m', 'index.php?go=Bauauskunft_Suche', NULL, 36, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (39, 'Druckrahmeneditor', 'Druckrahmeneditor', 'Print Layout Editor', NULL, 'Druckrahmen', 'index.php?go=Druckrahmen', NULL, 78, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (42, 'Dokumentationen', 'Dokumentationen', 'Documentation', '', '', 'https://kvwmap.de/wiki/index.php/dokumentation', '', 7, 2, '_blank', 10, 'Dokumentation', '');
INSERT INTO kvwmap.u_menues VALUES (44, 'neue Notiz', 'neue Notiz', 'neue Notiz', NULL, 'neue Notiz', 'index.php?go=Notizenformular', NULL, 67, 2, NULL, 0, NULL, 'notiz');
INSERT INTO kvwmap.u_menues VALUES (45, 'Stellenverwaltung', 'Stellenverwaltung', 'Task&nbsp;Management', NULL, 'Quáº£n lÃ½ tÃ¡c vá»¥', 'index.php?go=changemenue', NULL, 0, 1, NULL, 80, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (46, 'Stelle anlegen', 'Stelle anlegen', 'Create new Task', NULL, 'Stelle anlegen', 'index.php?go=Stelleneditor', NULL, 45, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (47, 'Stellen anzeigen', 'Stellen anzeigen', 'List all Tasks', NULL, 'Danh sÃ¡ch tÃ¡c vá»¥', 'index.php?go=Stellen_Anzeigen', NULL, 45, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (49, 'WMS-Import', 'WMS-Import', 'WMS-Import', NULL, 'WMS-Import', 'index.php?go=WMS_Import', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (50, 'Layerverwaltung', 'Layerverwaltung', 'Layer Management', NULL, 'Quáº£n lÃ½ cÃ¡c lá»›p thÃ´ng tin', 'index.php?go=changemenue', NULL, 0, 1, NULL, 82, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (51, 'Layer erstellen', 'Layer erstellen', 'Create new Layer', NULL, 'Layer erstellen', 'index.php?go=Layereditor', NULL, 50, 2, NULL, 12, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (63, 'Programmverwaltung', 'Programmverwaltung', 'Programmverwaltung', '', 'Programmverwaltung', 'index.php?go=Administratorfunktionen', '', 64, 2, '', 0, '', '');
INSERT INTO kvwmap.u_menues VALUES (64, 'Admin-Funktionen', 'Admin-Funktionen', 'Admin-Functions', '', '', 'index.php?go=changemenue', '', 0, 1, '', 120, '', '');
INSERT INTO kvwmap.u_menues VALUES (65, 'Filterverwaltung', 'Filterverwaltung', 'Filter&nbsp;Management', NULL, 'Quáº£n lÃ½ chiáº¿t lá»c thÃ´ng tin', 'index.php?go=Filterverwaltung', NULL, 45, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (67, 'Notizen', 'Notizen', 'Notizen', NULL, 'ThÃ´ng bÃ¡o', 'index.php?go=changemenue', NULL, 0, 1, NULL, 110, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (72, 'Layereditor', '', '', '', '', 'index.php?go=Layereditor', '', 50, 2, '', 16, '', '');
INSERT INTO kvwmap.u_menues VALUES (73, 'Attribut-Editor', 'Attribut-Editor', 'Attribute-Editor', '', 'Attribut-Editor', 'index.php?go=Attributeditor', '', 50, 2, '', 17, '', '');
INSERT INTO kvwmap.u_menues VALUES (74, 'Layer-Suche', 'Layer-Suche', 'Layer-Search', NULL, 'TÃ¬m lá»›p thÃ´ng tin', 'index.php?go=Layer-Suche&titel=Layer-Suche', NULL, 30, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (76, 'Shape-Export', 'Shape-Export', 'Shape-Export', NULL, 'Xuáº¥t file Shape', 'index.php?go=SHP_Export', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (77, 'Shape-Import', 'Shape-Import', 'Shape-Import', NULL, 'Nháº­p file Shape', 'index.php?go=SHP_Import', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (78, 'Drucken', 'Drucken', 'Print', NULL, 'In', 'index.php?go=changemenue', NULL, 0, 1, NULL, 100, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (79, 'Druckausschnittswahl', 'Druckausschnittswahl', 'Select Print Extent', NULL, 'Druckausschnittswahl', '#', 'printMap();', 78, 2, NULL, 0, NULL, 'drucken');
INSERT INTO kvwmap.u_menues VALUES (126, 'Datensatz hinzuf&uuml;gen', NULL, '', NULL, NULL, 'index.php?go=neuer_Layer_Datensatz', NULL, 50, 2, NULL, 6, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (138, 'Polygon zeichnen', 'Polygon zeichnen', 'Draw Polygon', NULL, 'Polygon zeichnen', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=159', NULL, 13, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (141, 'Funktionenverwaltung', 'Funktionenverwaltung', NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 90, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (142, 'Funktionen anzeigen', 'Funktionen anzeigen', '', '', '', 'index.php?go=Funktionen_Anzeigen', '', 141, 2, '', 20, '', '');
INSERT INTO kvwmap.u_menues VALUES (143, 'Funktion anlegen', 'Funktion anlegen', '', '', '', 'index.php?go=Funktionen_Formular', '', 141, 2, '', 10, '', '');
INSERT INTO kvwmap.u_menues VALUES (144, 'Style-u.Labeleditor', 'Style-u.Labeleditor', 'Style&Label Editor', '', '', 'index.php?go=Style_Label_Editor', '', 50, 2, '', 18, '', '');
INSERT INTO kvwmap.u_menues VALUES (145, 'Synchronisation', 'Synchronisation', NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 190, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (147, 'TIF-Export', 'TIF-Export', 'TIF-Export', NULL, NULL, 'index.php?go=TIF_Export', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (148, 'Nutzer&uuml;bersicht', 'Nutzer&uuml;bersicht', NULL, NULL, NULL, 'index.php?go=BenutzerStellen_Anzeigen', NULL, 20, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (149, 'Layer-Export', 'Layer-Export', NULL, NULL, NULL, 'index.php?go=Layer_Export', NULL, 50, 2, NULL, 20, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (150, 'Optionen', 'Optionen', 'Options', '', 'Options', 'index.php?go=Stelle_waehlen', '', 0, 1, '', 1, '', 'optionen');
INSERT INTO kvwmap.u_menues VALUES (151, 'GPX-Import', NULL, 'GPX-Import', NULL, NULL, 'index.php?go=GPX_Import', NULL, 17, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (152, 'Daten Import', '', '', '', '', 'index.php?go=Daten_Import', '', 17, 2, '', 0, '', '');
INSERT INTO kvwmap.u_menues VALUES (154, 'schnelle Druckausgabe', NULL, NULL, NULL, NULL, '#', 'printMapFast();', 78, 2, NULL, 0, NULL, 'schnelldruck');
INSERT INTO kvwmap.u_menues VALUES (163, 'neuer Datensatz', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 100, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (174, 'Datendruck-Layouteditor', NULL, NULL, NULL, NULL, 'index.php?go=sachdaten_druck_editor', NULL, 78, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (175, 'Metadaten', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', NULL, 0, 1, NULL, 90, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (176, 'Metadatenerfassung', NULL, NULL, NULL, NULL, 'index.php?go=Metadaten_Uebersicht', NULL, 175, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (177, 'Metadatenrecherche', NULL, NULL, NULL, NULL, 'index.php?go=Metadaten_Recherche', NULL, 175, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (186, 'Themen&uuml;bersicht', NULL, NULL, NULL, NULL, 'index.php?go=Layer_Uebersicht', NULL, 50, 2, NULL, 1, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (209, 'letzte Abfrage aufrufen', NULL, NULL, NULL, NULL, 'javascript:void(0)', 'overlay_link(''go=get_last_query'', true)', 0, 1, NULL, 25, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (215, 'Gespeicherte Suchanfragen', 'Gespeicherte Suchanfragen', 'Saved search requests', NULL, '', 'index.php?go=Suchabfragen_auflisten', NULL, 30, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (216, 'Letztes Suchergebnis', 'Letztes Suchergebnis', 'Last search result', NULL, '', 'javascript:void(0)', 'overlay_link(''go=get_last_query'', true)', 30, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (239, 'Datentypen anzeigen', 'Datentypen anzeigen', 'List all Datatypes', 'Wyświetl typy danych', 'Hiển thị dữ liệu loại', 'index.php?go=Datentypen_Anzeigen', '', 50, 2, '', 14, '', '');
INSERT INTO kvwmap.u_menues VALUES (241, 'Menüverwaltung', 'Menüverwaltung', 'Menu Management', '', '', 'index.php?go=Menueverwaltung', '', 0, 1, '', 80, '', '');
INSERT INTO kvwmap.u_menues VALUES (251, 'Cron Jobs', NULL, NULL, NULL, NULL, 'index.php?go=cronjobs_anzeigen', NULL, 64, 2, NULL, 0, NULL, NULL);
INSERT INTO kvwmap.u_menues VALUES (256, 'Logout', '', '', '', '', 'index.php?go=logout', '', 0, 1, '', -1, '', 'logout');
INSERT INTO kvwmap.u_menues VALUES (269, 'Benachrichtigungen anzeigen', '', '', '', '', 'index.php?go=notifications_anzeigen', '', 20, 2, '', 15, '', '');
INSERT INTO kvwmap.u_menues VALUES (274, 'Menüs anzeigen', 'Menüs anzeigen', 'List all Menues', '', '', 'index.php?go=Menues_Anzeigen', '', 241, 2, '', 0, '', '');
INSERT INTO kvwmap.u_menues VALUES (301, 'Layerparameter', 'Layerattribut-Rechte', 'Layer Parameter', '', '', 'index.php?go=Layer_Parameter', '', 50, 2, '', 13, '', '');
INSERT INTO kvwmap.u_menues VALUES (303, 'Daten Export', '', '', '', '', 'index.php?go=Daten_Export', '', 17, 2, '', 20, '', '');
INSERT INTO kvwmap.u_menues VALUES (305, 'Layergruppen', '', '', '', '', 'index.php?go=Layergruppen_Anzeigen', '', 50, 2, '', 4, '', '');
INSERT INTO kvwmap.u_menues VALUES (306, 'Dienstmetadaten', '', '', '', '', 'index.php?go=Dienstmetadaten', '', 64, 2, '', 10, '', '');
INSERT INTO kvwmap.u_menues VALUES (312, 'Einladungen anzeigen', NULL, NULL, NULL, NULL, 'index.php?go=Einladungen_Anzeigen', '', 20, 2, NULL, 40, '', NULL);
INSERT INTO kvwmap.u_menues VALUES (314, 'Layer anzeigen', '', '', '', '', 'index.php?go=Layer_Anzeigen', '', 50, 2, '', 10, '', '');
INSERT INTO kvwmap.u_menues VALUES (315, 'Postgre-Datenbankverbindungen', NULL, NULL, NULL, NULL, 'index.php?go=connections_anzeigen', NULL, 50, 2, NULL, 100, 'Zeigt die Postgres-Datenbankverbindungen', NULL);
INSERT INTO kvwmap.u_menues VALUES (316, 'Zeitstempel einstellen', NULL, NULL, NULL, NULL, NULL, 'set_hist_timestamp()', 0, 1, NULL, 10, 'Zeitstempel einstellen', 'timetravel');


--
-- TOC entry 6085 (class 0 OID 1302593)
-- Dependencies: 343
-- Data for Name: u_rolle2used_class; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6086 (class 0 OID 1302600)
-- Dependencies: 344
-- Data for Name: u_rolle2used_layer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.u_rolle2used_layer VALUES (1, 1, 1, 1, 0, 1, 1, false, NULL, NULL, NULL, 0, NULL);
INSERT INTO kvwmap.u_rolle2used_layer VALUES (1, 1, 2, 0, 0, 1, 1, false, NULL, NULL, NULL, 0, NULL);


--
-- TOC entry 6087 (class 0 OID 1302613)
-- Dependencies: 345
-- Data for Name: u_styles2classes; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6061 (class 0 OID 1302440)
-- Dependencies: 319
-- Data for Name: used_layer; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap.used_layer VALUES (1, 1, NULL, false, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, true, false, 0);
INSERT INTO kvwmap.used_layer VALUES (1, 2, NULL, false, NULL, NULL, NULL, NULL, NULL, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, true, false, 0);


--
-- TOC entry 6063 (class 0 OID 1302459)
-- Dependencies: 321
-- Data for Name: user; Type: TABLE DATA; Schema: kvwmap; Owner: -
--

INSERT INTO kvwmap."user" VALUES (1, 'pkorduan', 'Korduan', 'Peter', '', '', 'f17050aeb2010f75818043c68a0837935443f1ba', 0, '2024-12-14 13:55:14', NULL, NULL, NULL, '', 'cc929cba10541b552a7ff62d69a400a4,b9defa7c3905f15affe5467c0fe59442,8f4dd3b3955f58ac601973dfcf943073,db2f97e71240dd330b0d678ca830ae95,6bc695c80b700a178e39ab2ffd7f3c45', 'admin', 1, '038140344445', 'peter.korduan@gdi-service.de', 1, 0, '2025-06-03 06:17:52', 'GDI-Service', 'Web-GIS Admin', 0, 0, NULL);


--
-- TOC entry 6064 (class 0 OID 1302481)
-- Dependencies: 322
-- Data for Name: user2notifications; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6088 (class 0 OID 1302618)
-- Dependencies: 346
-- Data for Name: zwischenablage; Type: TABLE DATA; Schema: kvwmap; Owner: -
--



--
-- TOC entry 6124 (class 0 OID 0)
-- Dependencies: 241
-- Name: belated_files_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.belated_files_id_seq', 1, true);


--
-- TOC entry 6125 (class 0 OID 0)
-- Dependencies: 243
-- Name: classes_class_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.classes_class_id_seq', 1, true);


--
-- TOC entry 6126 (class 0 OID 0)
-- Dependencies: 245
-- Name: colors_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.colors_id_seq', 12, true);


--
-- TOC entry 6127 (class 0 OID 0)
-- Dependencies: 247
-- Name: config_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.config_id_seq', 211, true);


--
-- TOC entry 6128 (class 0 OID 0)
-- Dependencies: 249
-- Name: connections_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.connections_id_seq', 1, true);


--
-- TOC entry 6129 (class 0 OID 0)
-- Dependencies: 251
-- Name: cron_jobs_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.cron_jobs_id_seq', 2, true);


--
-- TOC entry 6130 (class 0 OID 0)
-- Dependencies: 253
-- Name: datasources_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.datasources_id_seq', 1, true);


--
-- TOC entry 6131 (class 0 OID 0)
-- Dependencies: 255
-- Name: datatypes_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.datatypes_id_seq', 1, true);


--
-- TOC entry 6132 (class 0 OID 0)
-- Dependencies: 258
-- Name: datendrucklayouts_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.datendrucklayouts_id_seq', 1, true);


--
-- TOC entry 6133 (class 0 OID 0)
-- Dependencies: 264
-- Name: ddl_colors_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.ddl_colors_id_seq', 6, true);


--
-- TOC entry 6134 (class 0 OID 0)
-- Dependencies: 267
-- Name: druckausschnitte_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckausschnitte_id_seq', 1, true);


--
-- TOC entry 6135 (class 0 OID 0)
-- Dependencies: 269
-- Name: druckfreibilder_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckfreibilder_id_seq', 1, true);


--
-- TOC entry 6136 (class 0 OID 0)
-- Dependencies: 271
-- Name: druckfreilinien_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckfreilinien_id_seq', 1, true);


--
-- TOC entry 6137 (class 0 OID 0)
-- Dependencies: 273
-- Name: druckfreirechtecke_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckfreirechtecke_id_seq', 1, true);


--
-- TOC entry 6138 (class 0 OID 0)
-- Dependencies: 275
-- Name: druckfreitexte_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckfreitexte_id_seq', 1, true);


--
-- TOC entry 6139 (class 0 OID 0)
-- Dependencies: 277
-- Name: druckrahmen_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.druckrahmen_id_seq', 1, true);


--
-- TOC entry 6140 (class 0 OID 0)
-- Dependencies: 283
-- Name: labels_label_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.labels_label_id_seq', 1, true);


--
-- TOC entry 6141 (class 0 OID 0)
-- Dependencies: 290
-- Name: layer_charts_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.layer_charts_id_seq', 1, true);


--
-- TOC entry 6142 (class 0 OID 0)
-- Dependencies: 285
-- Name: layer_layer_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.layer_layer_id_seq', 2, true);


--
-- TOC entry 6143 (class 0 OID 0)
-- Dependencies: 296
-- Name: notifications_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.notifications_id_seq', 1, true);


--
-- TOC entry 6144 (class 0 OID 0)
-- Dependencies: 298
-- Name: referenzkarten_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.referenzkarten_id_seq', 11, true);


--
-- TOC entry 6145 (class 0 OID 0)
-- Dependencies: 307
-- Name: rolle_nachweise_dokumentauswahl_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.rolle_nachweise_dokumentauswahl_id_seq', 1, true);


--
-- TOC entry 6146 (class 0 OID 0)
-- Dependencies: 310
-- Name: rolle_saved_layers_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.rolle_saved_layers_id_seq', 1, true);


--
-- TOC entry 6147 (class 0 OID 0)
-- Dependencies: 301
-- Name: rollenlayer_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.rollenlayer_id_seq', 1, true);


--
-- TOC entry 6148 (class 0 OID 0)
-- Dependencies: 313
-- Name: stelle_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.stelle_id_seq', 1, true);


--
-- TOC entry 6149 (class 0 OID 0)
-- Dependencies: 317
-- Name: styles_style_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.styles_style_id_seq', 1, true);


--
-- TOC entry 6150 (class 0 OID 0)
-- Dependencies: 333
-- Name: u_funktionen_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.u_funktionen_id_seq', 75, true);


--
-- TOC entry 6151 (class 0 OID 0)
-- Dependencies: 335
-- Name: u_groups_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.u_groups_id_seq', 63, true);


--
-- TOC entry 6152 (class 0 OID 0)
-- Dependencies: 341
-- Name: u_menues_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.u_menues_id_seq', 316, true);


--
-- TOC entry 6153 (class 0 OID 0)
-- Dependencies: 320
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: kvwmap; Owner: -
--

SELECT pg_catalog.setval('kvwmap.user_id_seq', 1, true);


--
-- TOC entry 5638 (class 2606 OID 1302622)
-- Name: belated_files belated_files_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.belated_files
    ADD CONSTRAINT belated_files_pkey PRIMARY KEY (id);


--
-- TOC entry 5641 (class 2606 OID 1302624)
-- Name: classes classes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.classes
    ADD CONSTRAINT classes_pkey PRIMARY KEY (class_id);


--
-- TOC entry 5643 (class 2606 OID 1302627)
-- Name: colors colors_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.colors
    ADD CONSTRAINT colors_pkey PRIMARY KEY (id);


--
-- TOC entry 5645 (class 2606 OID 1302629)
-- Name: config config_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.config
    ADD CONSTRAINT config_pkey PRIMARY KEY (id);


--
-- TOC entry 5647 (class 2606 OID 1302631)
-- Name: connections connections_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.connections
    ADD CONSTRAINT connections_pkey PRIMARY KEY (id);


--
-- TOC entry 5653 (class 2606 OID 1302637)
-- Name: datasources datasources_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datasources
    ADD CONSTRAINT datasources_pkey PRIMARY KEY (id);


--
-- TOC entry 5658 (class 2606 OID 1302642)
-- Name: datatype_attributes datatype_attributes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datatype_attributes
    ADD CONSTRAINT datatype_attributes_pkey PRIMARY KEY (layer_id, datatype_id, name);


--
-- TOC entry 5656 (class 2606 OID 1302639)
-- Name: datatypes datatypes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datatypes
    ADD CONSTRAINT datatypes_pkey PRIMARY KEY (id);


--
-- TOC entry 5660 (class 2606 OID 1302644)
-- Name: datendrucklayouts datendrucklayouts_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.datendrucklayouts
    ADD CONSTRAINT datendrucklayouts_pkey PRIMARY KEY (id);


--
-- TOC entry 5662 (class 2606 OID 1302646)
-- Name: ddl2freilinien ddl2freilinien_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl2freilinien
    ADD CONSTRAINT ddl2freilinien_pkey PRIMARY KEY (ddl_id, line_id);


--
-- TOC entry 5664 (class 2606 OID 1302648)
-- Name: ddl2freitexte ddl2freitexte_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl2freitexte
    ADD CONSTRAINT ddl2freitexte_pkey PRIMARY KEY (ddl_id, freitext_id);


--
-- TOC entry 5666 (class 2606 OID 1302650)
-- Name: ddl2stelle ddl2stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl2stelle
    ADD CONSTRAINT ddl2stelle_pkey PRIMARY KEY (stelle_id, ddl_id);


--
-- TOC entry 5668 (class 2606 OID 1302652)
-- Name: ddl_colors ddl_colors_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl_colors
    ADD CONSTRAINT ddl_colors_pkey PRIMARY KEY (id);


--
-- TOC entry 5670 (class 2606 OID 1302654)
-- Name: ddl_elemente ddl_elemente_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl_elemente
    ADD CONSTRAINT ddl_elemente_pkey PRIMARY KEY (ddl_id, name);


--
-- TOC entry 5672 (class 2606 OID 1302656)
-- Name: druckausschnitte druckausschnitte_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckausschnitte
    ADD CONSTRAINT druckausschnitte_pkey PRIMARY KEY (stelle_id, user_id, id);


--
-- TOC entry 5674 (class 2606 OID 1302658)
-- Name: druckfreibilder druckfreibilder_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreibilder
    ADD CONSTRAINT druckfreibilder_pkey PRIMARY KEY (id);


--
-- TOC entry 5676 (class 2606 OID 1302660)
-- Name: druckfreilinien druckfreilinien_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreilinien
    ADD CONSTRAINT druckfreilinien_pkey PRIMARY KEY (id);


--
-- TOC entry 5678 (class 2606 OID 1302662)
-- Name: druckfreirechtecke druckfreirechtecke_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreirechtecke
    ADD CONSTRAINT druckfreirechtecke_pkey PRIMARY KEY (id);


--
-- TOC entry 5680 (class 2606 OID 1302664)
-- Name: druckfreitexte druckfreitexte_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckfreitexte
    ADD CONSTRAINT druckfreitexte_pkey PRIMARY KEY (id);


--
-- TOC entry 5684 (class 2606 OID 1302668)
-- Name: druckrahmen2freibilder druckrahmen2freibilder_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen2freibilder
    ADD CONSTRAINT druckrahmen2freibilder_pkey PRIMARY KEY (druckrahmen_id, freibild_id);


--
-- TOC entry 5686 (class 2606 OID 1302670)
-- Name: druckrahmen2freitexte druckrahmen2freitexte_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen2freitexte
    ADD CONSTRAINT druckrahmen2freitexte_pkey PRIMARY KEY (druckrahmen_id, freitext_id);


--
-- TOC entry 5688 (class 2606 OID 1302672)
-- Name: druckrahmen2stelle druckrahmen2stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen2stelle
    ADD CONSTRAINT druckrahmen2stelle_pkey PRIMARY KEY (stelle_id, druckrahmen_id);


--
-- TOC entry 5682 (class 2606 OID 1302666)
-- Name: druckrahmen druckrahmen_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen
    ADD CONSTRAINT druckrahmen_pkey PRIMARY KEY (id);


--
-- TOC entry 5651 (class 2606 OID 1302635)
-- Name: cron_jobs id; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.cron_jobs
    ADD CONSTRAINT id UNIQUE (id);


--
-- TOC entry 5690 (class 2606 OID 1302674)
-- Name: invitations invitations_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.invitations
    ADD CONSTRAINT invitations_pkey PRIMARY KEY (token, email, stelle_id);


--
-- TOC entry 5692 (class 2606 OID 1302676)
-- Name: labels labels_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.labels
    ADD CONSTRAINT labels_pkey PRIMARY KEY (label_id);


--
-- TOC entry 5699 (class 2606 OID 1302683)
-- Name: layer_attributes2rolle layer_attributes2rolle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2rolle
    ADD CONSTRAINT layer_attributes2rolle_pkey PRIMARY KEY (layer_id, attributename, stelle_id, user_id);


--
-- TOC entry 5701 (class 2606 OID 1302685)
-- Name: layer_attributes2stelle layer_attributes2stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2stelle
    ADD CONSTRAINT layer_attributes2stelle_pkey PRIMARY KEY (layer_id, attributename, stelle_id);


--
-- TOC entry 5697 (class 2606 OID 1302681)
-- Name: layer_attributes layer_attributes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes
    ADD CONSTRAINT layer_attributes_pkey PRIMARY KEY (layer_id, name);


--
-- TOC entry 5703 (class 2606 OID 1302687)
-- Name: layer_charts layer_charts_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_charts
    ADD CONSTRAINT layer_charts_pkey PRIMARY KEY (id);


--
-- TOC entry 5705 (class 2606 OID 1302689)
-- Name: layer_datasources layer_datasources_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_datasources
    ADD CONSTRAINT layer_datasources_pkey PRIMARY KEY (layer_id, datasource_id);


--
-- TOC entry 5707 (class 2606 OID 1302691)
-- Name: layer_parameter layer_parameter_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_parameter
    ADD CONSTRAINT layer_parameter_pkey PRIMARY KEY (id);


--
-- TOC entry 5695 (class 2606 OID 1302678)
-- Name: layer layer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer
    ADD CONSTRAINT layer_pkey PRIMARY KEY (layer_id);


--
-- TOC entry 5649 (class 2606 OID 1302633)
-- Name: connections name; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.connections
    ADD CONSTRAINT name UNIQUE (name);


--
-- TOC entry 5709 (class 2606 OID 1302693)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 5711 (class 2606 OID 1302695)
-- Name: referenzkarten referenzkarten_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.referenzkarten
    ADD CONSTRAINT referenzkarten_pkey PRIMARY KEY (id);


--
-- TOC entry 5717 (class 2606 OID 1302701)
-- Name: rolle_csv_attributes rolle_csv_attributes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_csv_attributes
    ADD CONSTRAINT rolle_csv_attributes_pkey PRIMARY KEY (user_id, stelle_id, name);


--
-- TOC entry 5719 (class 2606 OID 1302703)
-- Name: rolle_export_settings rolle_export_settings_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_export_settings
    ADD CONSTRAINT rolle_export_settings_pkey PRIMARY KEY (stelle_id, user_id, layer_id, name);


--
-- TOC entry 5723 (class 2606 OID 1302707)
-- Name: rolle_nachweise_dokumentauswahl rolle_nachweise_dokumentauswahl_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise_dokumentauswahl
    ADD CONSTRAINT rolle_nachweise_dokumentauswahl_pkey PRIMARY KEY (id);


--
-- TOC entry 5721 (class 2606 OID 1302705)
-- Name: rolle_nachweise rolle_nachweise_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise
    ADD CONSTRAINT rolle_nachweise_pkey PRIMARY KEY (user_id, stelle_id);


--
-- TOC entry 5713 (class 2606 OID 1302697)
-- Name: rolle rolle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle
    ADD CONSTRAINT rolle_pkey PRIMARY KEY (user_id, stelle_id);


--
-- TOC entry 5725 (class 2606 OID 1302709)
-- Name: rolle_saved_layers rolle_saved_layers_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_saved_layers
    ADD CONSTRAINT rolle_saved_layers_pkey PRIMARY KEY (id);


--
-- TOC entry 5715 (class 2606 OID 1302699)
-- Name: rollenlayer rollenlayer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rollenlayer
    ADD CONSTRAINT rollenlayer_pkey PRIMARY KEY (id);


--
-- TOC entry 5727 (class 2606 OID 1302711)
-- Name: search_attributes2rolle search_attributes2rolle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.search_attributes2rolle
    ADD CONSTRAINT search_attributes2rolle_pkey PRIMARY KEY (name, user_id, stelle_id, layer_id, attribute, searchmask_number);


--
-- TOC entry 5729 (class 2606 OID 1302713)
-- Name: stelle stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stelle
    ADD CONSTRAINT stelle_pkey PRIMARY KEY (id);


--
-- TOC entry 5731 (class 2606 OID 1302715)
-- Name: stellen_hierarchie stellen_hierarchie_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stellen_hierarchie
    ADD CONSTRAINT stellen_hierarchie_pkey PRIMARY KEY (parent_id, child_id);


--
-- TOC entry 5733 (class 2606 OID 1302717)
-- Name: styles styles_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.styles
    ADD CONSTRAINT styles_pkey PRIMARY KEY (style_id);


--
-- TOC entry 5741 (class 2606 OID 1302725)
-- Name: u_attributfilter2used_layer u_attributfilter2used_layer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_attributfilter2used_layer
    ADD CONSTRAINT u_attributfilter2used_layer_pkey PRIMARY KEY (stelle_id, layer_id, attributname);


--
-- TOC entry 5745 (class 2606 OID 1302729)
-- Name: u_consume2comments u_consume2comments_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume2comments
    ADD CONSTRAINT u_consume2comments_pkey PRIMARY KEY (user_id, stelle_id, time_id);


--
-- TOC entry 5747 (class 2606 OID 1302731)
-- Name: u_consume2layer u_consume2layer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume2layer
    ADD CONSTRAINT u_consume2layer_pkey PRIMARY KEY (user_id, stelle_id, time_id, layer_id);


--
-- TOC entry 5743 (class 2606 OID 1302727)
-- Name: u_consume u_consume_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume
    ADD CONSTRAINT u_consume_pkey PRIMARY KEY (user_id, stelle_id, time_id);


--
-- TOC entry 5749 (class 2606 OID 1302733)
-- Name: u_consumealb u_consumealb_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumealb
    ADD CONSTRAINT u_consumealb_pkey PRIMARY KEY (user_id, stelle_id, time_id, log_number);


--
-- TOC entry 5751 (class 2606 OID 1302735)
-- Name: u_consumealk u_consumealk_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumealk
    ADD CONSTRAINT u_consumealk_pkey PRIMARY KEY (user_id, stelle_id, time_id);


--
-- TOC entry 5753 (class 2606 OID 1302737)
-- Name: u_consumecsv u_consumecsv_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumecsv
    ADD CONSTRAINT u_consumecsv_pkey PRIMARY KEY (user_id, stelle_id, time_id);


--
-- TOC entry 5755 (class 2606 OID 1302739)
-- Name: u_consumenachweise u_consumenachweise_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumenachweise
    ADD CONSTRAINT u_consumenachweise_pkey PRIMARY KEY (antrag_nr, stelle_id, time_id);


--
-- TOC entry 5757 (class 2606 OID 1302741)
-- Name: u_consumeshape u_consumeshape_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumeshape
    ADD CONSTRAINT u_consumeshape_pkey PRIMARY KEY (user_id, stelle_id, time_id);


--
-- TOC entry 5759 (class 2606 OID 1302743)
-- Name: u_funktion2stelle u_funktion2stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_funktion2stelle
    ADD CONSTRAINT u_funktion2stelle_pkey PRIMARY KEY (funktion_id, stelle_id);


--
-- TOC entry 5761 (class 2606 OID 1302745)
-- Name: u_funktionen u_funktionen_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_funktionen
    ADD CONSTRAINT u_funktionen_pkey PRIMARY KEY (id);


--
-- TOC entry 5765 (class 2606 OID 1302749)
-- Name: u_groups2rolle u_groups2rolle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_groups2rolle
    ADD CONSTRAINT u_groups2rolle_pkey PRIMARY KEY (user_id, stelle_id, id);


--
-- TOC entry 5763 (class 2606 OID 1302747)
-- Name: u_groups u_groups_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_groups
    ADD CONSTRAINT u_groups_pkey PRIMARY KEY (id);


--
-- TOC entry 5767 (class 2606 OID 1302751)
-- Name: u_labels2classes u_labels2classes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_labels2classes
    ADD CONSTRAINT u_labels2classes_pkey PRIMARY KEY (class_id, label_id);


--
-- TOC entry 5769 (class 2606 OID 1302753)
-- Name: u_menue2rolle u_menue2rolle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2rolle
    ADD CONSTRAINT u_menue2rolle_pkey PRIMARY KEY (user_id, stelle_id, menue_id);


--
-- TOC entry 5771 (class 2606 OID 1302755)
-- Name: u_menue2stelle u_menue2stelle_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2stelle
    ADD CONSTRAINT u_menue2stelle_pkey PRIMARY KEY (stelle_id, menue_id);


--
-- TOC entry 5773 (class 2606 OID 1302757)
-- Name: u_menues u_menues_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menues
    ADD CONSTRAINT u_menues_pkey PRIMARY KEY (id);


--
-- TOC entry 5775 (class 2606 OID 1302759)
-- Name: u_rolle2used_class u_rolle2used_class_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_rolle2used_class
    ADD CONSTRAINT u_rolle2used_class_pkey PRIMARY KEY (user_id, stelle_id, class_id);


--
-- TOC entry 5777 (class 2606 OID 1302761)
-- Name: u_rolle2used_layer u_rolle2used_layer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_rolle2used_layer
    ADD CONSTRAINT u_rolle2used_layer_pkey PRIMARY KEY (user_id, stelle_id, layer_id);


--
-- TOC entry 5779 (class 2606 OID 1302763)
-- Name: u_styles2classes u_styles2classes_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_styles2classes
    ADD CONSTRAINT u_styles2classes_pkey PRIMARY KEY (class_id, style_id);


--
-- TOC entry 5735 (class 2606 OID 1302719)
-- Name: used_layer used_layer_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.used_layer
    ADD CONSTRAINT used_layer_pkey PRIMARY KEY (stelle_id, layer_id);


--
-- TOC entry 5739 (class 2606 OID 1302723)
-- Name: user2notifications user2notifications_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.user2notifications
    ADD CONSTRAINT user2notifications_pkey PRIMARY KEY (notification_id, user_id);


--
-- TOC entry 5737 (class 2606 OID 1302721)
-- Name: user user_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- TOC entry 5781 (class 2606 OID 1302765)
-- Name: zwischenablage zwischenablage_pkey; Type: CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.zwischenablage
    ADD CONSTRAINT zwischenablage_pkey PRIMARY KEY (user_id, stelle_id, layer_id, oid);


--
-- TOC entry 5639 (class 1259 OID 1302625)
-- Name: classes_layer_id; Type: INDEX; Schema: kvwmap; Owner: -
--

CREATE INDEX classes_layer_id ON kvwmap.classes USING btree (layer_id);


--
-- TOC entry 5654 (class 1259 OID 1302640)
-- Name: datatypes_connection_id; Type: INDEX; Schema: kvwmap; Owner: -
--

CREATE INDEX datatypes_connection_id ON kvwmap.datatypes USING btree (connection_id);


--
-- TOC entry 5693 (class 1259 OID 1302679)
-- Name: layer_gruppe; Type: INDEX; Schema: kvwmap; Owner: -
--

CREATE INDEX layer_gruppe ON kvwmap.layer USING btree (gruppe);


--
-- TOC entry 5782 (class 2606 OID 1302766)
-- Name: ddl2freirechtecke ddl2freirechtecke_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl2freirechtecke
    ADD CONSTRAINT ddl2freirechtecke_ibfk_1 FOREIGN KEY (rect_id) REFERENCES kvwmap.druckfreirechtecke(id) ON DELETE CASCADE;


--
-- TOC entry 5783 (class 2606 OID 1302771)
-- Name: ddl2stelle ddl2stelle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.ddl2stelle
    ADD CONSTRAINT ddl2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5784 (class 2606 OID 1302776)
-- Name: druckausschnitte druckausschnitte_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckausschnitte
    ADD CONSTRAINT druckausschnitte_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5785 (class 2606 OID 1302781)
-- Name: druckrahmen2stelle druckrahmen2stelle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.druckrahmen2stelle
    ADD CONSTRAINT druckrahmen2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5792 (class 2606 OID 1302816)
-- Name: layer_charts fk_layer_charts_label_attribute_name; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_charts
    ADD CONSTRAINT fk_layer_charts_label_attribute_name FOREIGN KEY (layer_id, label_attribute_name) REFERENCES kvwmap.layer_attributes(layer_id, name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5793 (class 2606 OID 1302821)
-- Name: layer_charts fk_layer_charts_value_attribute_name; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_charts
    ADD CONSTRAINT fk_layer_charts_value_attribute_name FOREIGN KEY (layer_id, value_attribute_name) REFERENCES kvwmap.layer_attributes(layer_id, name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5787 (class 2606 OID 1302791)
-- Name: layer fk_layer_connection_id; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer
    ADD CONSTRAINT fk_layer_connection_id FOREIGN KEY (connection_id) REFERENCES kvwmap.connections(id);


--
-- TOC entry 5825 (class 2606 OID 1302981)
-- Name: u_menue2rolle fk_menue2rolle_menue2stelle; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2rolle
    ADD CONSTRAINT fk_menue2rolle_menue2stelle FOREIGN KEY (menue_id, stelle_id) REFERENCES kvwmap.u_menue2stelle(menue_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5826 (class 2606 OID 1302986)
-- Name: u_menue2rolle fk_menue2rolle_rolle; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2rolle
    ADD CONSTRAINT fk_menue2rolle_rolle FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5828 (class 2606 OID 1302996)
-- Name: u_menue2stelle fk_menue2stelle_meune; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2stelle
    ADD CONSTRAINT fk_menue2stelle_meune FOREIGN KEY (menue_id) REFERENCES kvwmap.u_menues(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5829 (class 2606 OID 1303001)
-- Name: u_menue2stelle fk_menue2stelle_stelle; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2stelle
    ADD CONSTRAINT fk_menue2stelle_stelle FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5798 (class 2606 OID 1302846)
-- Name: rollenlayer fk_rollen_layer_connection_id; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rollenlayer
    ADD CONSTRAINT fk_rollen_layer_connection_id FOREIGN KEY (connection_id) REFERENCES kvwmap.connections(id);


--
-- TOC entry 5786 (class 2606 OID 1302786)
-- Name: invitations invitations_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.invitations
    ADD CONSTRAINT invitations_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5788 (class 2606 OID 1302796)
-- Name: layer_attributes2rolle layer_attributes2rolle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2rolle
    ADD CONSTRAINT layer_attributes2rolle_ibfk_1 FOREIGN KEY (layer_id, attributename, stelle_id) REFERENCES kvwmap.layer_attributes2stelle(layer_id, attributename, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5789 (class 2606 OID 1302801)
-- Name: layer_attributes2rolle layer_attributes2rolle_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2rolle
    ADD CONSTRAINT layer_attributes2rolle_ibfk_2 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5790 (class 2606 OID 1302806)
-- Name: layer_attributes2stelle layer_attributes2stelle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2stelle
    ADD CONSTRAINT layer_attributes2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5791 (class 2606 OID 1302811)
-- Name: layer_attributes2stelle layer_attributes2stelle_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_attributes2stelle
    ADD CONSTRAINT layer_attributes2stelle_ibfk_2 FOREIGN KEY (layer_id) REFERENCES kvwmap.layer(layer_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5794 (class 2606 OID 1302826)
-- Name: layer_datasources layer_datasource_fk_datasource_id; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_datasources
    ADD CONSTRAINT layer_datasource_fk_datasource_id FOREIGN KEY (datasource_id) REFERENCES kvwmap.datasources(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5795 (class 2606 OID 1302831)
-- Name: layer_datasources layer_datasource_fk_layer_id; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.layer_datasources
    ADD CONSTRAINT layer_datasource_fk_layer_id FOREIGN KEY (layer_id) REFERENCES kvwmap.layer(layer_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5813 (class 2606 OID 1302921)
-- Name: user2notifications notification_id_fk; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.user2notifications
    ADD CONSTRAINT notification_id_fk FOREIGN KEY (notification_id) REFERENCES kvwmap.notifications(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5814 (class 2606 OID 1302926)
-- Name: user2notifications notification_user_id_fk; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.user2notifications
    ADD CONSTRAINT notification_user_id_fk FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5800 (class 2606 OID 1302856)
-- Name: rolle_csv_attributes rolle_csv_attributes_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_csv_attributes
    ADD CONSTRAINT rolle_csv_attributes_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5801 (class 2606 OID 1302861)
-- Name: rolle_export_settings rolle_export_settings_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_export_settings
    ADD CONSTRAINT rolle_export_settings_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5796 (class 2606 OID 1302836)
-- Name: rolle rolle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle
    ADD CONSTRAINT rolle_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5797 (class 2606 OID 1302841)
-- Name: rolle rolle_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle
    ADD CONSTRAINT rolle_ibfk_2 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5802 (class 2606 OID 1302866)
-- Name: rolle_last_query rolle_last_query_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_last_query
    ADD CONSTRAINT rolle_last_query_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5804 (class 2606 OID 1302876)
-- Name: rolle_nachweise_dokumentauswahl rolle_nachweise_dokumentauswahl_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise_dokumentauswahl
    ADD CONSTRAINT rolle_nachweise_dokumentauswahl_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5803 (class 2606 OID 1302871)
-- Name: rolle_nachweise rolle_nachweise_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise
    ADD CONSTRAINT rolle_nachweise_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5805 (class 2606 OID 1302881)
-- Name: rolle_nachweise_rechercheauswahl rolle_nachweise_rechercheauswahl_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_nachweise_rechercheauswahl
    ADD CONSTRAINT rolle_nachweise_rechercheauswahl_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5806 (class 2606 OID 1302886)
-- Name: rolle_saved_layers rolle_saved_layers_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rolle_saved_layers
    ADD CONSTRAINT rolle_saved_layers_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5799 (class 2606 OID 1302851)
-- Name: rollenlayer rollenlayer_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.rollenlayer
    ADD CONSTRAINT rollenlayer_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5807 (class 2606 OID 1302891)
-- Name: search_attributes2rolle search_attributes2rolle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.search_attributes2rolle
    ADD CONSTRAINT search_attributes2rolle_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5810 (class 2606 OID 1302906)
-- Name: stelle_gemeinden stelle_gemeinden_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stelle_gemeinden
    ADD CONSTRAINT stelle_gemeinden_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5808 (class 2606 OID 1302896)
-- Name: stellen_hierarchie stellen_hierarchie_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stellen_hierarchie
    ADD CONSTRAINT stellen_hierarchie_ibfk_1 FOREIGN KEY (parent_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5809 (class 2606 OID 1302901)
-- Name: stellen_hierarchie stellen_hierarchie_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.stellen_hierarchie
    ADD CONSTRAINT stellen_hierarchie_ibfk_2 FOREIGN KEY (child_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5815 (class 2606 OID 1302931)
-- Name: u_attributfilter2used_layer u_attributfilter2used_layer_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_attributfilter2used_layer
    ADD CONSTRAINT u_attributfilter2used_layer_ibfk_1 FOREIGN KEY (stelle_id, layer_id) REFERENCES kvwmap.used_layer(stelle_id, layer_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5817 (class 2606 OID 1302941)
-- Name: u_consume2comments u_consume2comments_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume2comments
    ADD CONSTRAINT u_consume2comments_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5818 (class 2606 OID 1302946)
-- Name: u_consume2layer u_consume2layer_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume2layer
    ADD CONSTRAINT u_consume2layer_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5816 (class 2606 OID 1302936)
-- Name: u_consume u_consume_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consume
    ADD CONSTRAINT u_consume_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5819 (class 2606 OID 1302951)
-- Name: u_consumealb u_consumealb_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumealb
    ADD CONSTRAINT u_consumealb_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5820 (class 2606 OID 1302956)
-- Name: u_consumealk u_consumealk_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumealk
    ADD CONSTRAINT u_consumealk_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5821 (class 2606 OID 1302961)
-- Name: u_consumecsv u_consumecsv_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumecsv
    ADD CONSTRAINT u_consumecsv_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5822 (class 2606 OID 1302966)
-- Name: u_consumeshape u_consumeshape_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_consumeshape
    ADD CONSTRAINT u_consumeshape_ibfk_1 FOREIGN KEY (user_id) REFERENCES kvwmap."user"(id) ON DELETE CASCADE;


--
-- TOC entry 5823 (class 2606 OID 1302971)
-- Name: u_funktion2stelle u_funktion2stelle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_funktion2stelle
    ADD CONSTRAINT u_funktion2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5824 (class 2606 OID 1302976)
-- Name: u_groups2rolle u_groups2rolle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_groups2rolle
    ADD CONSTRAINT u_groups2rolle_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5827 (class 2606 OID 1302991)
-- Name: u_menue2rolle u_menue2rolle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2rolle
    ADD CONSTRAINT u_menue2rolle_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5830 (class 2606 OID 1303006)
-- Name: u_menue2stelle u_menue2stelle_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2stelle
    ADD CONSTRAINT u_menue2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5831 (class 2606 OID 1303011)
-- Name: u_menue2stelle u_menue2stelle_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_menue2stelle
    ADD CONSTRAINT u_menue2stelle_ibfk_2 FOREIGN KEY (menue_id) REFERENCES kvwmap.u_menues(id) ON DELETE CASCADE;


--
-- TOC entry 5832 (class 2606 OID 1303016)
-- Name: u_rolle2used_class u_rolle2used_class_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_rolle2used_class
    ADD CONSTRAINT u_rolle2used_class_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5833 (class 2606 OID 1303021)
-- Name: u_rolle2used_layer u_rolle2used_layer_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_rolle2used_layer
    ADD CONSTRAINT u_rolle2used_layer_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5834 (class 2606 OID 1303026)
-- Name: u_rolle2used_layer u_rolle2used_layer_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.u_rolle2used_layer
    ADD CONSTRAINT u_rolle2used_layer_ibfk_2 FOREIGN KEY (layer_id) REFERENCES kvwmap.layer(layer_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5811 (class 2606 OID 1302911)
-- Name: used_layer used_layer_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.used_layer
    ADD CONSTRAINT used_layer_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5812 (class 2606 OID 1302916)
-- Name: used_layer used_layer_ibfk_2; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.used_layer
    ADD CONSTRAINT used_layer_ibfk_2 FOREIGN KEY (layer_id) REFERENCES kvwmap.layer(layer_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5835 (class 2606 OID 1303031)
-- Name: zwischenablage zwischenablage_ibfk_1; Type: FK CONSTRAINT; Schema: kvwmap; Owner: -
--

ALTER TABLE ONLY kvwmap.zwischenablage
    ADD CONSTRAINT zwischenablage_ibfk_1 FOREIGN KEY (user_id, stelle_id) REFERENCES kvwmap.rolle(user_id, stelle_id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2025-06-03 14:01:01 UTC

--
-- PostgreSQL database dump complete
--

