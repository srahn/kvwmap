
BEGIN;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE SCHEMA xplankonverter;


SET search_path = xplankonverter, public;

SET default_tablespace = '';

SET default_with_oids = true;

CREATE TABLE plaene (
  id serial primary key,
  name character varying(255),
  beschreibung text,
  bundesland_id integer
);

COMMIT;

