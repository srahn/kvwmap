--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.9
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-07-28 10:44:58

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = bauleitplanung, pg_catalog;

--
-- TOC entry 11474 (class 0 OID 0)
-- Dependencies: 596
-- Name: konkretisierungen_id_seq; Type: SEQUENCE SET; Schema: bauleitplanung; Owner: kvwmap
--

SELECT pg_catalog.setval('konkretisierungen_id_seq', 114, true);


--
-- TOC entry 11471 (class 0 OID 883264)
-- Dependencies: 595
-- Data for Name: konkretisierungen; Type: TABLE DATA; Schema: bauleitplanung; Owner: kvwmap
--

INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (99, 7, 'sonst. Versorgung', NULL);
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (24, 8, 'Umschlagplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (93, 31, 'Pension', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (69, 26, 'Marina', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (25, 8, 'Verkehrseinrichtung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (1, 2, 'betreutes Wohnen', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (7, 2, 'Altenstift', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (105, 31, 'Jugendherberge', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (81, 28, 'Windenergie', 'kW');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (2, 2, 'altersgerechtes Wohnen', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (72, 26, 'Anleger', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (46, 16, 'Golfspielfeld', 'Loch');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (100, 29, 'EKZ/Fachmarktzentrum', 'm²');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (3, 2, 'Mehrgenerationenhaus', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (94, 31, 'Gasthof/Gastronomie', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (8, 6, 'öffentliche Verwaltung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (51, 21, 'Altenheim', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (4, 2, 'Altenwohnheim', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (73, 27, 'Klinik', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (82, 28, 'Biomasse', 'kW');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (9, 6, 'Kirche/Religion', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (5, 2, 'Seniorenwohnheim', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (52, 21, 'Altenpflegeheim', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (10, 6, 'Post', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (74, 27, 'Krankenhaus', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (6, 2, 'Seniorenresidenz', 'WE');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (95, 31, 'Hostel', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (67, 26, 'Sportboothafen', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (83, 28, 'sonstige Erneuerbare Energie', 'kW');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (101, 29, 'Lebensmittelmarkt', 'm²');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (75, 27, 'Kureinrichtung', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (68, 26, 'Yachthafen', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (11, 6, 'Feuerwehr', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (12, 6, 'Rettungswache', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (91, 31, 'Hotel', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (96, 35, 'Campingplatz', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (76, 27, 'Reha', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (13, 7, 'Stromversorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (102, 29, 'Möbelmarkt', 'm²');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (92, 31, 'Ferienhäuser', 'Betten');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (80, 28, 'Photovoltaik/Solar', 'kW');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (14, 7, 'Gasversorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (97, 35, 'Zeltplatz', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (15, 7, 'Wärmeversorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (16, 7, 'Wasserversorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (103, 29, 'Baumarkt/Gartencenter', 'm²');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (17, 7, 'Abwasserentsorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (98, 35, 'Caravanplatz/Wohnmobil', 'Plaetze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (18, 7, 'Abfallbehandlung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (19, 7, 'Abfallentsorgung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (20, 8, 'Straßenverkehrsfläche', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (104, 29, 'Sonst. Einzelhandel', 'm²');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (26, 11, 'Grünflächen', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (107, 9, 'Rinderhaltung', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (22, 8, 'Schienenverkehrsfläche', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (23, 8, 'Luftverkehrsfläche', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (27, 11, 'Ausgleichs-, Kompensations- und Entwicklungsflächen', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (28, 15, 'Sportplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (29, 15, 'Sporthalle', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (30, 15, 'Spielplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (31, 15, 'Dressurplatz, Reitplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (32, 15, 'Schwimmhalle', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (33, 15, 'Badeanstalt, -platz, Strand', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (34, 15, 'Schießsportanlage', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (35, 15, 'Motocross', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (36, 15, 'Jugendclub', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (37, 15, 'Tennisanlage', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (38, 15, 'Wildpark, Tierpark, Haustierpark', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (39, 15, 'Zoo', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (40, 15, 'Reiterhof', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (41, 15, 'Erlebnishof', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (42, 15, 'Minigolf', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (43, 15, 'Spielpark', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (44, 15, 'Freilichtbühne/Veranstaltungsplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (45, 16, 'Golfinfrastruktur', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (47, 19, 'Wassersportzentrum', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (48, 19, 'Kanuverleih', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (49, 19, 'Wasserwanderrastplatz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (50, 19, 'Seebrücke', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (53, 21, 'Behinderteneinrichtung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (55, 21, 'soziale Zwecke allg.', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (54, 21, 'Hospiz', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (56, 23, 'Theater', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (57, 23, 'Oper', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (58, 23, 'Musical', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (59, 23, 'Kino', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (60, 23, 'Ausstellung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (61, 23, 'Messe', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (62, 23, 'Kongress', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (64, 23, 'Kultur allg.', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (63, 23, 'Galerie', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (65, 23, 'Museum', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (66, 26, 'Hafengebiet', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (70, 26, 'Werft', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (71, 26, 'Bootsservice', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (77, 27, 'Therapie', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (78, 27, 'Wellness', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (79, 27, 'Gesundheitshaus', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (84, 30, 'Schule', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (85, 30, 'Hochschule', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (86, 30, 'Fachhochschule', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (87, 30, 'Fortbildungseinrichtung', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (88, 30, 'Kita', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (89, 30, 'Krippe', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (90, 30, 'Hort', ' ');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (106, 9, 'Landwirtschaftsfläche', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (108, 9, 'Schweinehaltung', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (109, 9, 'Geflügelhaltung', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (110, 9, 'Schafhaltung', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (111, 9, 'Ziegenhaltung', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (112, 9, 'Pferdehaltung/Gestüt', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (113, 9, 'Fischzucht', 'Plätze');
INSERT INTO konkretisierungen (id, gebiets_id, bezeichnung, einheit) VALUES (114, 9, 'Sonstige Tierhaltung/Zucht', 'Plätze');


-- Completed on 2014-07-28 10:45:00

--
-- PostgreSQL database dump complete
--

