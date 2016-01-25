
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


SET search_path = bauleitplanung, public;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 586 (class 1259 OID 883230)
-- Dependencies: 8
-- Name: aemter; Type: TABLE; Schema: bauleitplanung; Owner: -; Tablespace: 
--

CREATE TABLE plaene (
  id serial primary key,
  name character varying(255),
  beschreibung text,
  bundesland_id integer
);

COMMIT;

