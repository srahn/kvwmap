--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.12
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-08-19 10:11:11

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = probaug, pg_catalog;

--
-- TOC entry 11544 (class 0 OID 0)
-- Dependencies: 2845
-- Name: bau_vorhaben_id_seq; Type: SEQUENCE SET; Schema: probaug; Owner: kvwmap
--

SELECT pg_catalog.setval('bau_vorhaben_id_seq', 61, true);


--
-- TOC entry 11541 (class 0 OID 37440508)
-- Dependencies: 2846
-- Data for Name: bau_vorhaben; Type: TABLE DATA; Schema: probaug; Owner: kvwmap
--

INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Einfamilienwohnhaus', 1);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Mehrfamilienwohnhaus', 2);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Wohn- und Geschäftshaus', 3);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anstaltsgebäude', 4);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Büro- und Verwaltungsgebäude', 5);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('landw. Betriebsgebäude', 6);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('nichtlandw. Betriebsgebäude', 7);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('sonst. Nichtwohngebäude', 8);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Wohnheim', 9);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Garagen, Carport, Stellplatz', 10);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anbauten', 11);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Werbeanlagen, Werbetafeln', 12);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('haustechn. Anlagen', 13);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeigen gem. 26. BImSchV', 14);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeigen gem. 31. BImSchV', 15);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Antrag auf Ausnahmegenehmigung', 16);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Ferien-/Wochenendhaus', 17);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Nutzungsänd., Nachtr., Verl., Wiederk. P', 18);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('sonstige Vorhaben', 19);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('kein Vorhaben i.S.d. BauGB / BauO NW', 20);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Tankstelle', 21);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Sanierungssatzung', 22);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Satzung', 23);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Bebauungsplan', 24);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Vorhaben- und Erschließungsplan', 25);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Flächennutzungsplan', 26);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Baudenkmale', 27);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Bodendenkmale', 28);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Denkmalbereiche', 29);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Bewegliche Denkmale', 30);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Planungen', 31);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Sonstige Stellungnahmen 63uD', 32);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Sonstige Stellungnahmen', 33);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeigen Denkmalschutz', 34);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Umgebungsschutz', 35);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anträge Änderung Denkmalliste', 36);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anfragen', 37);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Stellungnahmen', 38);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Beschwerden', 39);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Widerspruch', 40);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Verkaufsstätte', 41);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Gaststätte', 42);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Beherbergungsstätte', 43);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Schule', 44);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Heime', 45);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Kindertagesstätte', 46);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Krankenhaus', 47);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Sportstätten', 48);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Parkplätze', 49);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Neubau/Ersterwerb 2. Förderweg', 50);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Gewährung Instandsetz.-,Modern.-darlehen', 51);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('3. Förderweg-Miet- u. Gen.-Wohn. ...', 52);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('7.1. Prüfung Standsicherheitsnachweis', 53);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('7.2. Prüf.Standsicherh.f.Umb.und Aufst.', 54);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('7.4. Prüfung des Schallschutznachweises', 55);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('7.5 Prüfung des Wärmeschutznachweises', 56);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('7.6 Prüfung Nachweis Feuerwiderstand', 57);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeige BSFM', 58);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeige gem. 20. BImSchV', 59);
INSERT INTO bau_vorhaben (vorhaben, id) VALUES ('Anzeige gem. 21. BImSchV', 60);


-- Completed on 2014-08-19 10:11:13

--
-- PostgreSQL database dump complete
--

