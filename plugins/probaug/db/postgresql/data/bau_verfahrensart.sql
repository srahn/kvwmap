--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.12
-- Dumped by pg_dump version 9.1.3
-- Started on 2014-08-19 10:10:45

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = probaug, pg_catalog;

--
-- TOC entry 11544 (class 0 OID 0)
-- Dependencies: 2843
-- Name: bau_verfahrensart_id_seq; Type: SEQUENCE SET; Schema: probaug; Owner: kvwmap
--

SELECT pg_catalog.setval('bau_verfahrensart_id_seq', 59, true);


--
-- TOC entry 11541 (class 0 OID 37440499)
-- Dependencies: 2844
-- Data for Name: bau_verfahrensart; Type: TABLE DATA; Schema: probaug; Owner: kvwmap
--

INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Voranfrage', 1);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Neubau', 2);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Umbau/Änderung', 3);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Nutzungsänderung', 4);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Abbruch', 5);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Nachtrag', 6);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Verlängerung', 7);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Zustimmungs-Verfahren', 8);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Erlaubnis', 9);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Wiederkehrende Prüfung', 10);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('BImSch-/Planfeststellungs-Verfahren', 11);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Immissionsschutz', 12);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Antrag auf Ausnahmegenehmigung', 13);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Teilung', 14);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Konzession', 15);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Finanzierung', 16);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bescheinigung WEG', 17);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Brandschau', 18);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Weiterleitung Planungsanzeige', 19);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Beteiligung Träger öffentlicher Belange', 20);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Zust.zur Herstellg.von Erschließungsanl.', 21);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bestätigung Planreife § 33 BauGB', 22);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Genehmigung Planung', 23);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bestätigung Auflagenerfüllung', 24);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Weiterleitung an BLUM - Schwerin', 25);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anzeige Planung', 26);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anzeigen gem. BImSchV', 27);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Baufachliche Prüfung', 28);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bauherren-Widerspruch', 29);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Dritt-Widerspruch', 30);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Ordnungsverfügung', 31);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bußgeld', 32);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Baulast', 33);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Beschwerden', 34);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Denkmalliste', 35);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Genehmigungen Denkmalschutz', 36);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Stellungnahmen Denkmalschutz', 37);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Ordnungswidrigkeiten Denkmalschutz', 38);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Sonstiges', 39);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anfragen Denkmalschutz', 40);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Einspruch Denkmalschutz', 41);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anzeigen Denkmalschutz', 42);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Änderungen der Denkmalliste', 43);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anforderungen Denkmalwertbegründungen', 44);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Einspruch', 45);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Baulast (ohne HA)', 46);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Beratung/Besprechung', 47);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Unbewohnbarkeitserklärung', 48);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Sanierungsgenehmigung', 49);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('genehmigungsfreie Vorhaben § 64 LBauO', 50);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Befreiung genehmigungsfreie Vorhaben', 51);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Befreiung', 52);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Ausnahme  genehmigungsfreie Vorhaben', 53);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Ausnahme Bauantrag', 54);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Prüfung Standsicherheit', 55);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Bauzustandsbesichtigungen', 56);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Antrag Fördermittel', 57);
INSERT INTO bau_verfahrensart (verfahrensart, id) VALUES ('Anzeige Feuerstätte', 58);


-- Completed on 2014-08-19 10:10:47

--
-- PostgreSQL database dump complete
--

