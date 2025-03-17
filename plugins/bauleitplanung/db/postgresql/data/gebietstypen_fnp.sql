--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.12
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-12-15 13:36:22

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = bauleitplanung, pg_catalog;

--
-- TOC entry 11681 (class 0 OID 0)
-- Dependencies: 2880
-- Name: gebietstypen_fnp_id_seq; Type: SEQUENCE SET; Schema: bauleitplanung; Owner: kvwmap
--

SELECT pg_catalog.setval('gebietstypen_fnp_id_seq', 14, true);


--
-- TOC entry 11678 (class 0 OID 45271336)
-- Dependencies: 2881
-- Data for Name: gebietstypen_fnp; Type: TABLE DATA; Schema: bauleitplanung; Owner: kvwmap
--

INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (1, 'Grün', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (2, 'Wohnen', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (3, 'Mischbau', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (4, 'Gewerbe', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (5, 'Gemeinbedarf', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (6, 'Fläche für Landwirtschaft', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (7, 'Wald', true, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (8, 'Einzelhandel', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (9, 'Bund und Land', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (10, 'Erholung', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (11, 'Hafen', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (12, 'Gesundheit', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (13, 'Militär', false, NULL);
INSERT INTO gebietstypen_fnp (id, typ, art, einheit) VALUES (14, 'sonstige', false, NULL);


-- Completed on 2014-12-15 13:36:25

--
-- PostgreSQL database dump complete
--

