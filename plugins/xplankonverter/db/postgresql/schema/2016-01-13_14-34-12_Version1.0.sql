
BEGIN;

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

CREATE SCHEMA xplan_uml;
CREATE SCHEMA xplankonverter;
CREATE SCHEMA gml_classes;

CREATE EXTENSION "uuid-ossp";

COMMIT;

