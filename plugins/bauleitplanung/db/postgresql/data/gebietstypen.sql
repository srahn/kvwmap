--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.9
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-07-28 10:44:19

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = bauleitplanung, pg_catalog;

--
-- TOC entry 11474 (class 0 OID 0)
-- Dependencies: 592
-- Name: gebietstypen_id_seq; Type: SEQUENCE SET; Schema: bauleitplanung; Owner: kvwmap
--

SELECT pg_catalog.setval('gebietstypen_id_seq', 35, true);


--
-- TOC entry 11471 (class 0 OID 883250)
-- Dependencies: 591
-- Data for Name: gebietstypen; Type: TABLE DATA; Schema: bauleitplanung; Owner: kvwmap
--

INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (4, 'Gewerbegebiet', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (5, 'Industriegebiet', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (6, 'Gemeinbedarf', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (7, 'Ver- und Entsorgungsfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (8, 'Verkehrsfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (9, 'Landwirtschaftsfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (10, 'Waldfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (11, 'Grünfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (12, 'Wasserfläche', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (13, 'Aufschüttung', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (14, 'Abgrabung', true, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (15, 'Sport und Freizeit', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (17, 'Freizeitpark', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (18, 'touristische Versorgung', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (19, 'Wassertourismus', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (21, 'Sozialwesen', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (22, 'Militär', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (23, 'Kulturwesen', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (24, 'Justiz', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (25, 'Wirtschaftshafen', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (30, 'Bildungswesen', false, NULL);
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (1, 'Wohngebiet', true, 'WE');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (2, 'spez. Wohnen', true, 'WE');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (3, 'Mischgebiet', true, 'WE');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (16, 'Golfplatz', false, 'Loch');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (26, 'Hafen', false, 'Plaetze');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (27, 'Gesundheitswesen', false, 'Betten');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (28, 'erneuerbare Energie', false, 'kW');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (29, 'Einzelhandel', false, 'm²');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (31, 'Beherbergung', false, 'Betten');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (33, 'Wohnen+Ferienwohnen', false, 'WE und Betten');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (35, 'Camping und Caravan', false, 'Plaetze');
INSERT INTO gebietstypen (id, typ, art, einheit) VALUES (32, 'Wochenendhaus', false, 'WochWE');


-- Completed on 2014-07-28 10:44:21

--
-- PostgreSQL database dump complete
--

