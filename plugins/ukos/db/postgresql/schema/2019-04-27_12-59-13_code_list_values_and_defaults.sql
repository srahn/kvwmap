BEGIN;

-- Data for Name: basiscodeliste; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_baulasttraeger; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_eigentuemer; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_preisermittlung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_zustand; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: basisobjekt; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Name: basisobjekt_objekt_id_seq; Type: SEQUENCE SET; Schema: ukos_base; Owner: -
--

SELECT pg_catalog.setval('ukos_base.basisobjekt_objekt_id_seq', 1, false);


--
-- Data for Name: idents; Type: TABLE DATA; Schema: ukos_base; Owner: -
--

--
-- Data for Name: punktobjekt; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: punktundstreckenobjekt; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: streckenobjekt; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: werteliste; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_bauklasse; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_baumart; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_deckschicht; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_fertigstellung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_klassifizierung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--

INSERT INTO ukos_base.wld_klassifizierung VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: wld_material; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_nutzung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--
INSERT INTO ukos_base.wld_nutzung VALUES ('00000000-0000-0000-0000-000000000000', '0', '', 'unbekannt', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_nutzung VALUES ('ef5dd3f4-5ef4-4557-b0b7-369081d8b2e7', '1', '', 'Autobahn', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_nutzung VALUES ('9efe045d-9ed2-4bb1-ab73-6be41e25313f', '2', '54401', 'Bundesstraße', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_nutzung VALUES ('0cdf57ca-e243-4444-80df-8c86b9bafd48', '5', '54301', 'Landesstraße', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: wld_objektbezeichnung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_strassennetzlage; Type: TABLE DATA; Schema: ukos_base; Owner: -
--

INSERT INTO ukos_base.wld_strassennetzlage VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt', 'noch keine Bemerkung', '001', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: wld_stvonr; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: wld_zustandsbewertung; Type: TABLE DATA; Schema: ukos_base; Owner: -
--



--
-- Data for Name: kreis; Type: TABLE DATA; Schema: ukos_kataster; Owner: -
--

INSERT INTO ukos_kataster.kreis VALUES ('00000000-0000-0000-0000-000000000000', 'nicht zugewiesen', '00000', 'noch keine Bemerkung', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: gemeindeverband; Type: TABLE DATA; Schema: ukos_kataster; Owner: -
--

INSERT INTO ukos_kataster.gemeindeverband VALUES ('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', 'nicht zugewiesen', '0000', 'noch keine Bemerkung', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: gemeinde; Type: TABLE DATA; Schema: ukos_kataster; Owner: -
--

INSERT INTO ukos_kataster.gemeinde VALUES ('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', 'nicht zugewiesen', '000', 'noch keine Bemerkung', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: gemeindeteil; Type: TABLE DATA; Schema: ukos_kataster; Owner: -
--

INSERT INTO ukos_kataster.gemeindeteil VALUES ('00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000', 'nicht zugewiesen', '0000', 'noch keine Bemerkung', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


--
-- Data for Name: wlo_art_abfall; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('01', 'Restmüll/Reiseabfall');
INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('02', 'Wertstoff');
INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('03', 'Papier');
INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('04', 'Glas');
INSERT INTO ukos_okstra.wlo_art_abfall VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_art_der_erfassung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('10', 'vor Ort gemessen');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('11', 'aus kinematischer Erfassung');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('12', 'eigene Digitalisierung');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('13', 'Fremddigitalisierung');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('14', 'aus Bauunterlagen');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('15', 'aus Entwurfsunterlagen');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('16', 'geschätzt');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('17', 'ATKIS');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('18', 'ALK');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('19', 'SIB-Bauwerke');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('20', 'Sonstiges Fachinformationssystem');
INSERT INTO ukos_okstra.wlo_art_der_erfassung VALUES ('99', 'sonstige Art der Erfassung');


--
-- Data for Name: wlo_art_der_erfassung_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_lagetyp_abfallentsorgung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lagetyp_abfallentsorgung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_lagetyp_abfallentsorgung VALUES ('01', 'unterirdisch');
INSERT INTO ukos_okstra.wlo_lagetyp_abfallentsorgung VALUES ('02', 'oberirdisch');
INSERT INTO ukos_okstra.wlo_lagetyp_abfallentsorgung VALUES ('03', 'Sonstige');


--
-- Data for Name: wlo_material_abfallentsorgung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('01', 'Kunststoff');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('02', 'Recycling');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('03', 'Holz');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('04', 'Stein');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('05', 'Beton');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('06', 'Stahlblech');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('07', 'Stahl');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('08', 'Verzinkter / beschichteter Draht');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('09', 'Metallgitter');
INSERT INTO ukos_okstra.wlo_material_abfallentsorgung VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_quelle_der_information; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('01', 'Ingenieurbüro');
INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('02', 'Straßenbauverwaltung');
INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('03', 'Bund');
INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('04', 'Kreise');
INSERT INTO ukos_okstra.wlo_quelle_der_information VALUES ('99', 'sonstige Quelle der Information');


--
-- Data for Name: wlo_quelle_der_information_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_typ_abfallentsorgung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_typ_abfallentsorgung VALUES ('01', 'Abfallbehälter auf der Anlage des ruhenden Verkehrs, ohne Spezifizierung');
INSERT INTO ukos_okstra.wlo_typ_abfallentsorgung VALUES ('02', 'Behälter');
INSERT INTO ukos_okstra.wlo_typ_abfallentsorgung VALUES ('03', 'Behälter mit Aschenbecher');
INSERT INTO ukos_okstra.wlo_typ_abfallentsorgung VALUES ('06', 'Abfallcontainer');


--
-- Data for Name: wlo_unterhaltungspflicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('01', 'Land');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('02', 'Kreis / kreisfreie Stadt');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('03', 'Gemeinde');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('04', 'Straßenbauamt/Niederlassung');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('05', 'Meisterei');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('09', 'Sonstige Partner');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht VALUES ('10', 'keine Unterhaltungspflicht');


--
-- Data for Name: abfallentsorgung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: anzahl_fahrstreifen; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('1', 'Untergrund / Unterbau');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('2', 'Ungebundene Schicht des Oberbaues');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('3', 'Schicht mit bitumenhaltigem Bindemittel');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('4', 'Schicht mit pechhaltigem Bindemittel');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('5', 'Schicht mit hydraulischem Bindemittel');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('6', 'Gebundene Schicht mit sonstigem Bindemittel');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('7', 'Pflaster');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('8', 'Platten');
INSERT INTO ukos_okstra.wlo_art_aufbauschicht VALUES ('9', 'Sonstige Schichten');


--
-- Data for Name: wlo_bindemittel_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_detail_a_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_detail_b_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_detail_c_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_detail_d_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_dreiwertige_logik; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_dreiwertige_logik VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_dreiwertige_logik VALUES ('1', 'ja');
INSERT INTO ukos_okstra.wlo_dreiwertige_logik VALUES ('2', 'nein');


--
-- Data for Name: wlo_herkunft_angaben_aufbau; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('01', 'aus Bauunterlagen');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('02', 'von Straßenunterhaltungspersonal');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('03', 'aus örtlichen Erfassungsblättern der bisherigen Straßenbestandsaufnahme');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('04', 'aus Straßenbüchern');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('05', 'örtlich erfasste Daten (z.B. Bohrkerne, Aufbrüche)');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('06', 'aus Eignungsprüfung');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('07', 'Georadar in Verbindung mit Bohrkern');
INSERT INTO ukos_okstra.wlo_herkunft_angaben_aufbau VALUES ('08', 'von Bauüberwacher');


--
-- Data for Name: wlo_material_aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: aufbauschicht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_der_aufstellvorrichtung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('01', 'Rohrpfosten');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('02', 'Gabelrohrständer');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('03', 'Kragarm');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('04', 'Verkehrszeichenbrücke');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('05', 'Hauswand');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('07', 'Brücke');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('08', 'Mast/Straßenlaterne');
INSERT INTO ukos_okstra.wlo_art_der_aufstellvorrichtung VALUES ('99', 'sonstiges');


--
-- Data for Name: wlo_detaillierungsgrad_asb; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_detaillierungsgrad_asb VALUES ('01', 'hoch');
INSERT INTO ukos_okstra.wlo_detaillierungsgrad_asb VALUES ('02', 'mittel');
INSERT INTO ukos_okstra.wlo_detaillierungsgrad_asb VALUES ('03', 'niedrig');


--
-- Data for Name: wlo_kreuzungszuordnung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_kreuzungszuordnung VALUES ('1', 'liegt in nicht aufzunehmender Straße');
INSERT INTO ukos_okstra.wlo_kreuzungszuordnung VALUES ('2', 'liegt in aufzunehmender Straße, abweichende Unterhaltungszuordnung vorhanden');


--
-- Data for Name: wlo_lage; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lage VALUES ('00', 'gesamte Fahrbahn(en) (ein- und zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('01', 'linker Fahrbahnrand (einbahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('02', 'linke Fahrbahn, linker Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('03', 'linke Fahrbahn (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('04', 'linke Fahrbahn, rechter Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('05', 'Mitte/Bestandsachse');
INSERT INTO ukos_okstra.wlo_lage VALUES ('06', 'rechte Fahrbahn, linker Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('07', 'rechte Fahrbahn (zweibahnig');
INSERT INTO ukos_okstra.wlo_lage VALUES ('08', 'rechte Fahrbahn, rechter Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('09', 'rechter Fahrbahnrand (einbahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('10', 'Hauptfahrstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('11', 'Hauptfahrstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('12', 'Hauptfahrstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('13', 'Hauptfahrstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('20', 'Hauptfahrstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('21', 'Hauptfahrstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('22', 'Hauptfahrstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('23', 'Hauptfahrstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('30', '1. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('31', '1. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('32', '1. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('33', '1. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('40', '1. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('41', '1. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('42', '1. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('43', '1. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('50', '2. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('51', '2. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('52', '2. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('53', '2. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('60', '2. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('61', '2. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('62', '2. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('63', '2. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('70', '3. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('71', '3. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('72', '3. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('73', '3. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('77', 'linke Fahrbahn, Fahrbahnachse (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('80', '3. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage VALUES ('81', '3. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage VALUES ('82', '3. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage VALUES ('83', '3. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage VALUES ('88', 'rechte Fahrbahn, Fahrbahnachse (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage VALUES ('94', 'Punkt im Querprofil auf keiner Achse');
INSERT INTO ukos_okstra.wlo_lage VALUES ('95', 'links außerhalb');
INSERT INTO ukos_okstra.wlo_lage VALUES ('96', 'rechts außerhalb');
INSERT INTO ukos_okstra.wlo_lage VALUES ('97', 'Straße liegt innerhalb');
INSERT INTO ukos_okstra.wlo_lage VALUES ('98', 'beidseitig');
INSERT INTO ukos_okstra.wlo_lage VALUES ('99', 'unbekannte Lage');


--
-- Data for Name: wlo_material_aufstellvorrichtung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_material_aufstellvorrichtung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_material_aufstellvorrichtung VALUES ('01', 'Metall');
INSERT INTO ukos_okstra.wlo_material_aufstellvorrichtung VALUES ('02', 'Holz');
INSERT INTO ukos_okstra.wlo_material_aufstellvorrichtung VALUES ('99', 'Sonstiges');


--
-- Data for Name: aufstellvorrichtung_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_kennzeichen_bahnigkeit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_kennzeichen_bahnigkeit VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_kennzeichen_bahnigkeit VALUES ('1', 'einbahnig, Straße mit/ohne Gegenverkehr');
INSERT INTO ukos_okstra.wlo_kennzeichen_bahnigkeit VALUES ('2', 'zweibahnig, Straße mit baulich getrennten Richtungsfahrbahnen');


--
-- Data for Name: bahnigkeit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_baumart; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_baumart VALUES ('1337', '100', 'Feldahorn', 'Acer campestre');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1338', '100', 'Roter Schlangenhautahorn', 'Acer capillipes');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1339', '100', 'Weinahorn', 'Acer circinatum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1345', '100', 'Französischer Ahorn', 'Acer monspessulanum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1347', '100', 'Eschenahorn', 'Acer negundo');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1348', '100', 'Goldbunter Eschenahorn', 'Acer negundo "Aureovariegatum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1349', '100', 'Gelber Eschenahorn', 'Acer negundo "Odessanum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1350', '100', 'Silberbunter Eschenahorn', 'Acer negundo "Variegatum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1351', '100', 'Fächerahorn', 'acer palmatum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1352', '100', 'Roter Fächerahorn', 'Acer palmatum "Atropurpureum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1355', '100', 'Roter Schlitzahorn', 'Acer palmatum "Dissecum Atropurpureum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1356', '100', 'Roter Schlitzahorn "Garnet"', 'Acer palmatum "Dissecum Garnet"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1357', '100', 'Roter Schlitzahorn "Nigrum"', 'Acer palmatum "Dissecum Nigrum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1359', '100', 'Grüner Schlitzahorn', 'Acer palmatum Dissecum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1362', '100', 'Spitzahorn', 'Acer platanoides');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1365', '100', 'Blutahorn, Roter Spitzahorn "Faassen"s Black"', 'Acer platanoides "Faassen"s Black"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1366', '100', 'Kugelahorn', 'Acer platanoides "Globosum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1367', '100', 'Vogelkrallenahorn "Laciniatum"', 'Acer platanoides "Laciniatum"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1368', '100', 'Spitzahorn "Reitenbachii"', 'Acer platanoides "Reitenbachii"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1369', '100', 'Kegelförmiger Spitzahorn "Emerald Queen"', 'Acer platanoides "Emerald Queen"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1370', '100', 'Bergahorn', 'Acer pseudoplatanus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1371', '100', 'Schmalkroniger Bergahorn "Erectum"', 'Acer pseudoplatanus "ErectuM"');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1375', '100', 'Rotahorn', 'Acer rubrum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1376', '100', 'Rostbartahorn', 'Acer rufinerve');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1377', '100', 'Silberahorn', 'Acer saccharinum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1382', '100', 'Geschlitzter Silberahorn ''Wieri''', 'Acer saccharinum ''Wieri''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1385', '110', 'Rotblühende Rosskastanie, Purpurkastanie', 'Aesculus carnea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1387', '110', 'Gemeine Rosskastanie', 'Aesculus hippocastanum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1388', '110', 'Gefülltblühende Rosskastanie', 'Aesculus hippocastanum ''Baumannii''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1390', '120', 'Götterbaum', 'Ailanthus altissima');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1391', '130', 'Schwarzerle, Roterle', 'Alnus glutinosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1392', '130', 'Kaisererle ''Imperialis''', 'Alnus glutinosa ''Imperialis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1393', '130', 'Grauerle, Weißerle', 'Alnus incana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1394', '130', 'Geschlitzblättrige Grauerle', 'Alnus incana ''Laciniata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1395', '130', 'Golderle', 'Alnus incana ''Aurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1396', '130', 'Grünerle, Alpenerle', 'Alnus viridis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1398', '000', 'Kupfer-Felsenbirne', 'Amelanchier lamarckii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1399', '000', 'Hängende Felsenbirne', 'Amelanchier laevis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1400', '000', 'Echte Felsenbirne', 'Amelanchier ovalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1405', '140', 'Jap. Angelikabaum, Jap. Aralie', 'Aralia elata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1406', '140', 'Goldaralie', 'Aralia elata ''Aureovariegata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1407', '140', 'Silberaralie', 'Aralia elata ''Variegata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1422', '150', 'Grüne Heckenberberitze', 'Berberis thunbergii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1443', '160', 'Schwarzbirke, Flussbirke', 'Betula nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1444', '160', 'Papierbirke', 'Betula papyrifera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1448', '160', 'Moor-Birke', 'Betula pubescens');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1450', '160', 'Himalayabirke', 'Betula jacquemontii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1451', '160', 'Sandbirke, Weißbirke', 'Betula pendula');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1452', '160', 'Schlitzblättrige Birke', 'Betula pendula ''Dalecarlica''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1453', '160', 'Säulenbirke', 'Betula pendula ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1455', '160', 'Blutbirke, Purpurbirke', 'Betula pendula ''Purpurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1456', '160', 'Hängebirke', 'Betula pendula ''Tristis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1457', '160', 'Trauerbirke', 'Betula pendula ''Youngii''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1510', '170', 'Hainbuche, Weißbuche', 'Carpinus betulus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1511', '170', 'Gemeine Weißbuche (Säulenform)', 'Carpinus betulus ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1514', '190', 'Esskastanie', 'Castanea sativa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1515', '200', 'Gew. Trompetenbaum', 'Catalpa bignonioides');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1523', '220', 'Judasblattbaum', 'Cercidiphyllum japonicum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1524', '230', 'Gemeiner Judasbaum', 'Cercis siliquastrum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1557', '240', 'Weißer Hartriegel', 'Cornus alba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1565', '240', 'Hoher Etagenhartriegel', 'Cornus controversa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1566', '240', 'Amerikanischer Blumen-Hartriegel', 'Cornus florida');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1568', '240', 'Japanischer Blumen-Hartriegel', 'Cornus kousa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1570', '240', 'Kornelkirsche', 'Cornus mas');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1571', '240', 'Westamerikanischer Blumen-Hartriegel', 'Cornus nutallii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1572', '240', 'Roter Hartriegel', 'Cornus sanguinea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1576', '260', 'Haselnuss', 'Corylus avellana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1577', '260', 'Goldhasel', 'Corylus avellana ''Aurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1578', '260', 'Korkenzieherhasel', 'Corylus avellana ''Contorta''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1583', '400', 'Morgenländische Platane', 'Platanus orientalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1590', '260', 'Baumhasel', 'Corylus colurna');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1591', '260', 'Bluthasel', 'Corylus maxima ''Purpurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1635', '270', 'Lavalles Weißdorn, Apfeldorn', 'Crataegus lavallei ''Carrierei''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1638', '270', 'Eingriffliger Weißdorn', 'Crataegus monogyna');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1639', '270', 'Rotdorn', 'Crataegus laevigata ''Paul''s Scarlet''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1641', '270', 'Zweigriffliger Weißdorn', 'Crataegus laevigata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1642', '270', 'Pflaumenbl. Weißdorn, Pflaumendorn', 'Crataegus prunifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1697', '280', 'Schmalblättrige Ölweide', 'Elaeagnus angustifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1698', '280', 'Silberölweide', 'Elaeagnus commutata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1699', '280', 'Essbare Ölweide', 'Elaeagnus muliflora');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1726', '290', 'Pfaffenhütchen', 'Euonymus europaeus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1736', '770', 'Murray''s-Drehkiefer, Murraykiefer', 'Pinus contorta murrayana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1739', '300', 'Rotbuche', 'Fagus sylvatica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1741', '300', 'Veredelte Blutbuche', 'Fagus sylvatica ''Purpurea Latifolia''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1744', '300', 'Säulen-Rotbuche', 'Fagus sylvatica ''Dawyck''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1747', '300', 'Grüne Hängebuche', 'Fagus sylvatica ''Pendula''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1748', '300', 'Blutbuche-Sämling, Purpurbuche', 'Fagus sylvatica ''Purpurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1749', '300', 'Trauerblutbuche, Schwarzrote Hängebuche', 'Fagus sylvatica ''Pupurea-Pendula''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1761', '310', 'Gemeine Esche', 'Fraxinus excelsior');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1762', '770', 'Schwarzkiefer', 'Pinus nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1764', '310', 'Hänge-Esche', 'Fraxinus excelsior ''Pendula''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1765', '310', 'Nichtfruchtende Straßenesche', 'Fraxinus excelsior ''Westhof''s Glorie''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1766', '310', 'Blumenesche', 'Fraxinus ornus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1782', '320', 'Lederhülsenbaum', 'Gleditsia triacanthos');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1783', '000', 'Geweihbaum', 'Gymnocladus dioicus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1784', '000', 'Schneeglöckchenbaum', 'Halesia carolina');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1788', '330', 'Japanische Zaubernuss', 'Hamamelis japonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1793', '330', 'Lichtmess-Zaubernuss', 'Hamamelis mollis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1798', '330', 'Herbstblühende Zaubernuss', 'Hamamelis virginiana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1827', '340', 'Sanddorn', 'Hippophae rhamnoides');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1847', '000', 'Gemeine Stechpalme, Hülse', 'Ilex aquifolium');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1869', '350', 'Schwarznuss', 'Juglans nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1870', '350', 'Walnuss', 'Juglans regia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1876', '000', 'Blasenbaum', 'Koelreuteria paniculata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1880', '360', 'Alpen-Goldregen', 'Laburnum alpinum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1881', '360', 'Gemeiner Goldregen', 'Laburnum anagyroidis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1882', '360', 'Edel-Goldregen', 'Laburnum watereri ''Vossii''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1883', '000', 'Amberbaum', 'Liquidambar styraciflua');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1894', '000', 'Amerikanischer Tulpenbaum', 'Liriodendron tulpifera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1919', '370', 'Sommermagnolie', 'Magnolia sieboldii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1920', '370', 'Tulpenmagnolie', 'Magnolia soulangiana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1927', '370', 'Sternmagnolie', 'Magnolia stellata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1938', '380', 'Wildapfel', 'Malus sylvestris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1972', '380', 'Zierapfel (alle)', 'Malus ''Professor Sprenger''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1973', '390', 'Weiße Maulbeere', 'Morus alba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1976', '000', 'Scheinbuche', 'Nothofagus antarctica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1985', '000', 'Eisenbaum', 'Parrotia persica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1986', '000', 'Blauglockenbaum', 'Paulownia tomentosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('1992', '000', 'Echter Korkbaum', 'Phellodendron amurense');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2019', '400', 'Ahornblättrige Platane', 'Platanus acerifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2023', '410', 'Balsampappel', 'Populus balsamifera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2024', '410', 'Berliner Lorbeerpappel', 'Populus berolinensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2027', '410', 'Graupappel', 'Populus canescens');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2036', '410', 'Pyramidenpappel', 'Populus nigra ''Italica''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2037', '410', 'Birkenpappel', 'Populus simonii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2039', '410', 'Zitterpappel, Espe', 'Populus tremula');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2040', '410', 'Säulen-Zitterpappel', 'Populus tremula ''Erecta''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2042', '410', 'Hänge-Zitterpappel', 'Populus tremula ''Pendula''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2043', '410', 'Westliche Balsampappel', 'Populus trichocarpa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2061', '420', 'Vogelkirsche, Wildkirsche', 'Prunus avium');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2062', '420', 'Süßkirsche', 'Prunus avium C.');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2064', '420', 'Wildpflaume', 'Prunus cerasifera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2065', '420', 'Blutpflaume', 'Prunus cerasifera ''Nigra''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2076', '420', 'Steinweichsel', 'Prunus mahaleb');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2077', '420', 'Traubenkirsche', 'Prunus padus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2078', '420', 'Pfirsisch', 'Prunus persica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2080', '420', 'Spätbl. Traubenkirsche', 'Prunus serotina');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2092', '420', 'Schlehe / Schwarzdorn', 'Prunus spinosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2122', '440', 'Holzbirne, Gemeine Birne', 'Pyrus communis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2125', '450', 'Zerreiche', 'Quercus cerris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2126', '450', 'Scharlach-Eiche', 'Quercus coccinea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2127', '450', 'Ungarische Eiche', 'Quercus frainetto');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2129', '450', 'Sumpfeiche', 'Quercus palustris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2130', '450', 'Stieleiche, Sommereiche', 'Quercus robur');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2131', '450', 'Pyramideneiche', 'Quercus robur ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2132', '450', 'Traubeneiche, Wintereiche', 'Quercus petraea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2134', '450', 'Amerikanische Roteiche', 'Quercus rubra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2135', '450', 'Wintergrüne Eiche', 'Quercus turneri ''Pseudoturneri''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2136', '460', 'Purgier-Kreuzdorn', 'Rhamnus catharticus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2137', '460', 'Faulbaum', 'Rhamnus frangula');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2139', '470', 'Essigbaum', 'Rhus glabra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2141', '470', 'Hirschkolben-Sumach, Essigbaum', 'Rhus typhina');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2156', '480', 'Robinie, Scheinakazie', 'Robinia pseudoacacia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2157', '480', 'Kegel-Robinie, Kegel-Akazie', 'Robinia pseudoacacia ''Bessoniana''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2160', '480', 'Straßen-Robinie, Straßen-Akazie', 'Robinia pseudoacacia ''Monophylla''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2162', '480', 'Korkenzieher-Robinie, Korkenzieher-Akazie', 'Robinia pseudoacacia ''Tortuosa''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2163', '480', 'Kugel-Robinie, Kugel-Akazie', 'Robinia pseudoacacia ''Umbraculifera''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2182', '490', 'Silberweide', 'Salix alba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2184', '490', 'Silberweide ''Liempde''', 'Salix alba ''Liempde''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2185', '490', 'Straßenweide', 'Salix alba ''Sericea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2186', '490', 'Trauerweide', 'Salix alba ''Tristis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2190', '490', 'Ohrweide', 'Salix aurita');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2194', '490', 'Salweide', 'Salix caprea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2198', '490', 'Graue Weide, Aschweide', 'Salix cinerea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2204', '490', 'Bruchweide, Knackweide', 'Salix fragilis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2211', '490', 'Korkenzieherweide', 'Salix matsudana ''Tortuosa''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2223', '490', 'Korbweide', 'Salix viminalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2227', '500', 'Schwarzer Holunder', 'Sambucus nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2232', '500', 'Roter Holunder, Traubenholunder', 'Sambucus racemosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2237', '000', 'Schnurbaum', 'Sophora japonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2242', '510', 'Amerikanische Eberesche', 'Sorbus americana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2243', '510', 'Mehlbeere', 'Sorbus aria');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2247', '510', 'Vogelbeere, Eberesche', 'Sorbus aucuparia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2248', '510', 'Säulen-Eberesche, Pyramiden-Eberesche', 'Sorbus aucuparia ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2255', '510', 'Essbare Eberesche', 'Sorbus aucuparia ''Edulis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2266', '510', 'Schwedische Mehlbeere', 'Sorbus intermedia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2268', '510', 'Park-Mehlbeere, Breitblättrige Mehlbeere', 'Sorbus latifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2272', '510', 'Elsbeere', 'Sorbus torminalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2273', '510', 'Vielfiedrige Eberesche', 'Sorbus vilmorinii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2298', '000', 'Japanischer Storaxbaum', 'Styrax japonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2324', '520', 'Wild-Flieder', 'Syringa vulgaris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2352', '530', 'Riesenblättrige Linde', 'Tilia americana ''Nova''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2353', '530', 'Winterlinde', 'Tilia cordata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2354', '530', 'Krimlinde', 'Tilia euchlora');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2355', '530', 'Holländische Linde', 'Tilia europaea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2357', '530', 'Kaiserlinde', 'Tilia europaea ''Pallida''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2358', '530', 'Sommerlinde', 'Tilia platyphyllos');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2359', '530', 'Silberlinde', 'Tilia tomentosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2361', '540', 'Feldulme', 'Ulmus carpinifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2362', '540', 'Goldulme', 'Ulmus carpinifolia ''Wredei''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2363', '540', 'Bergulme', 'Ulmus glabra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2365', '540', 'Stadt-Ulme, Holländische Ulme', 'Ulmus hollandica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2402', '550', 'Kaukasus-Zelkove', 'Zelkova carpinifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2403', '550', 'Keaki-Zelkove', 'Zelkova serrata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2404', '700', 'Weißtanne', 'Abies alba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2405', '700', 'Purpurtanne', 'Abies amabilis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2407', '700', 'Balsamtanne', 'Abies balsamea');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2410', '700', 'Griechische Tanne', 'Abies cephalonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2411', '700', 'Coloradotanne, Grautanne, Blautanne', 'Abies concolor');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2415', '700', 'Küstentanne', 'Abies grandis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2417', '700', 'Nikkotanne', 'Abies homolepis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2419', '700', 'Koreatanne', 'Abies koreana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2426', '700', 'Adelstanne', 'Abies procera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2428', '700', 'Nordmannstanne', 'Abies nordmanniana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2432', '700', 'Veitch''s-Tanne', 'Abies veitchii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2433', '000', 'Araukarie, Schmucktanne', 'Araucaria araucana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2434', '780', 'Morgenländischer Lebensbaum', 'Thuja orientalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2435', '710', 'Atlaszeder', 'Cedrus atlantica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2441', '710', 'Himalaya-Zeder', 'Cedrus deodara');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2442', '710', 'Libanon-Zeder', 'Cedrus libani');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2443', '000', 'Kopfeibe', 'Cephalotaxus fortunei');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2444', '720', 'Lawsons Scheinzypresse', 'Chamaecyparis lawsoniana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2446', '720', 'Blaue Säulenzypresse', 'Chamaecyparis lawsoniana ''Columnaris''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2471', '720', 'Nutka Scheinzypresse', 'Chamaecyparis nootkatensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2475', '720', 'Hinoki-Scheinzypresse', 'Chamaecyparis obtusa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2484', '720', 'Silberzypresse', 'Chamaecyparis pisifera');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2503', '000', 'Sicheltanne', 'Cryptomeria japonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2508', '730', 'Fächerblattbaum, Ginkgo', 'Ginkgo biloba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2509', '730', 'Säulen-Fächerblattbaum', 'Ginkgo biloba ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2527', '740', 'Chinesischer Wacholder', 'Juniperus chinensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2533', '740', 'Gemeiner Wacholder', 'Juniperus communis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2559', '740', 'Zypressen-Wacholder', 'Juniperus virginiana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2568', '750', 'Europäische Lärche', 'Larix decidua');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2570', '750', 'Japanische Lärche', 'Larix kaempferi');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2574', '000', 'Chinesisches Rotholz, Urwelt-Mammutbaum', 'Metasequoia glyptostroboides');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2577', '760', 'Mähnenfichte', 'Picea breweriana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2578', '760', 'Engelmann-Fichte', 'Picea engelmannii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2579', '760', 'Gemeine Fichte, Rottanne', 'Picea abies');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2582', '760', 'Säulenfichte', 'Picea abies ''Columnaris''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2589', '760', 'Trauer-Hänge-Fichte', 'Picea abies ''Inversa''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2606', '760', 'Weißfichte', 'Picea glauca');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2611', '760', 'Schwarzfichte', 'Picea mariana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2614', '760', 'Serbische Fichte', 'Picea omorica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2617', '760', 'Kaukasusfichte', 'Picea orientalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2621', '760', 'Stechfichte', 'Picea pungens');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2622', '760', 'Blaue Stechfichte, Blaufichte', 'Picea pungens glauca');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2635', '760', 'Sitkafichte', 'Picea sitchensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2636', '770', 'Fuchsschwanzkiefer', 'Pinus aristata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2638', '770', 'Zirbelkiefer, Arve', 'Pinus cembra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2643', '770', 'Sibirische Kiefer', 'Pinus sibirica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2644', '770', 'Drehkiefer', 'Pinus contorta');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2648', '770', 'Tränenkiefer', 'Pinus wallichiana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2650', '770', 'Schlangenhautkiefer', 'Pinus leucodermis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2651', '770', 'Jeffrey''s Kiefer', 'Pinus jeffreyi');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2654', '770', 'Bergkiefer, Latsche', 'Pinus mugo');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2660', '770', 'Österreichische Schwarzkiefer', 'Pinus nigra austriaca');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2662', '770', 'Mädchenkiefer', 'Pinus parviflora');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2664', '770', 'Rumelische Kiefer, Mazedonische Kiefer', 'Pinus peuce');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2665', '770', 'Gelbkiefer', 'Pinus ponderosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2668', '770', 'Zapfenkiefer', 'Pinus schwerinii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2669', '770', 'Gemeine Kiefer', 'Pinus sylvestris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2672', '770', 'Weymouthskiefer, Strobe', 'Pinus strobus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2676', '000', 'Douglasie, Douglasfichte, Mirbel', 'Pseudotsuga menziesii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2680', '000', 'Kalifornischer Mammutbaum', 'Sequoiadendron giganteum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2681', '000', 'Sumpfzypresse', 'Taxodium districhum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2682', '000', 'Eibe', 'Taxus baccata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2718', '780', 'Abendländischer Lebensbaum', 'Thuja occidentalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2742', '780', 'Riesenlebensbaum', 'Thuja plicata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2747', '780', 'Japanischer Lebensbaum', 'Thuja standishii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2751', '790', 'Kanadische Hemlocktanne', 'Tsuga canadensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2756', '790', 'Grüne Hemlocktanne', 'Tsuga heterophylla');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2762', '310', 'Einblättrige Esche', 'Fraxinus excelsior ''Diversifolia''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2777', '530', 'Hänge-Silber-Linde, Großblättrige Silberlinde', 'Tilia petiolaris');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2785', '000', 'Leyland-Zypresse', 'Cupressucyparis leclandii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2822', '530', 'Kleinblättrige Winterlinde', 'Tilia cordata ''Sheridan''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2829', '510', 'Speierling', 'Sorbus domestica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2844', '490', 'Kegelförmige Silberweide', 'Salix alba ''Chermesina''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2854', '100', 'Kegelförmiger Bergahorn ''Negenia''', 'Acer pseudoplatanus ''Negenia''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2855', '100', 'Breitkegelförmiger Bergahorn ''Rotterdam''', 'Acer pseudoplatanus ''Rotterdam''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2858', '170', 'Rotlaubige Hainbuche', 'Carpinus betulus ''Purpurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2864', '100', 'Spitzahorn ''Olmsted''', 'Acer platanoides ''Olmsted''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2867', '310', 'Goldesche', 'Fraxinus excelsior ''Aurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2869', '310', 'Kegelförmige Esche', 'Fraxinus excelsior ''Eureka''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2870', '420', 'Lorbeerkirsche, Kirschlorbeer', 'Prunus laurocerasus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2872', '310', 'Schmalkronige Esche', 'Fraxinus excelsior ''Geessink''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2931', '100', 'Oregon-Blutahorn', 'Acer platanoides ''Royal Red''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2960', '540', 'Exter-Ulme', 'Ulmus glabra ''Exoniensis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2961', '310', 'Kleinkronige Esche ''Raywood''', 'Fraxinus angustifolia ''Raywood''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2964', '440', 'Stadtbirne', 'Pyrus calleryana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2968', '110', 'Kugel-Rosskastanie', 'Aesculus hippocastanum ''Umbraculifera''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2969', '420', 'Sandkirsche', 'Prunus fruticosa');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('2978', '270', 'Säulen Weißdorn', 'Crataegus monogyna ''Stricta''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3047', '100', 'Purpurblättriger Bergahorn', 'Acer pseudoplatanus ''Atropurpureum''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3061', '130', 'Italienische Erle', 'Alnus cordata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3114', '410', 'Schwarzpappel', 'Populus nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3115', '420', 'Sauerkirsche', 'Prunus cerasus');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3116', '420', 'Haus-Pflaume', 'Prunus domestica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3136', '540', 'Flatter-Ulme', 'Ulmus laevis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3257', '000', 'Spießtanne', 'Cunninghamia lanceolata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3258', '730', 'Hängender Fächerblattbaum', 'Ginkgo biloba ''Pendula''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3288', '100', 'Davidsahorn', 'Acer davidii');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3292', '100', 'Zuckerahorn', 'Acer saccharum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3301', '170', 'Eichenblättrige Hainbuche', 'Carpinus betulus ''Quercifolia''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3317', '300', 'Orientalische Buche', 'Fagus orientalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3344', '450', 'Steineiche', 'Quercus ilex');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3348', '450', 'Amerikanische Goldeiche', 'Quercus rubra ''Aurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3371', '530', 'Großblättrige Sommerlinde', 'Tilia platyphyllos ''Laciniata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3385', '320', 'Lederhülsenbaum ''Pyramidalis''', 'Gleditsia triacanthos ''Pyramidalis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3398', '530', 'Gold-Sommerlinde', 'Tilia platyphyllos ''Aurea''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3513', '530', 'Kleinkronige Winterlinde', 'Tilia cordata ''Müllerklein''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3747', '540', 'Resistente Ulme', 'Ulmus ''Resista''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3810', '100', 'Spitzahorn ''Farlake''s Green''', 'Acer platanoides ''Farlake''s Green''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('3886', '110', 'Säulen-Rosskastanie', 'Aesculus hippocastanum ''Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4323', '770', 'Hakenkiefer', 'Pinus uncinata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4475', '450', 'Japan. Kaisereiche, Daimio-Eiche', 'Quercus dentata');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4510', '480', 'Pyramiden-Robinie, Pyramiden-Akazie', 'Robinia pseudoacacia ''Pyramidalis''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4520', '100', 'Kegelförmiger Spitzahorn', 'Acer platanoides ''Cleveland''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4524', '000', 'Arizona-Zypresse', 'Cupressus arizonica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4571', '100', 'Säulenförmiger Spitzahorn', 'Acer platanoides ''Columnare''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4573', '100', 'Schattenahorn ''Summershade''', 'Acer platanoides ''Summershade''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4580', '530', 'Amerikanische Stadtlinde', 'Tilia cordata ''Greenspire''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4674', '210', 'Südlicher Zürgelbaum', 'Celtis australis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4676', '210', 'Amerikanischer Zürgelbaum', 'Celtis occidentalis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4757', '100', 'Roter Spitzahorn ''Crimson King''', 'Acer platanoides ''Crimson King''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4760', '320', 'Dornenloser Lederhülsenbaum', 'Gleditsia triacanthos inermis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4762', '390', 'Schwarze Maulbeere', 'Morus nigra');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4791', '100', 'Kolchischer Spitzahorn', 'Acer cappadocicum');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4795', '110', 'Appalachen-Rosskastanie', 'Aesculus flava');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4799', '310', 'Schmalblättrige Esche', 'Fraxinus angustifolia');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4800', '310', 'Rotesche', 'Fraxinus pennsylvanica');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4802', '410', 'Silberpappel', 'Populus alba');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4803', '410', 'Kanadische Holzpappel', 'Populus canadensis');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4807', '450', 'Flaumeiche', 'Quercus pubescens');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4810', '510', 'Thüringische Eberesche', 'Sorbus thuringiaca');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('4811', '530', 'Amerikanische Linde', 'Tilia americana');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('5439', '490', 'Silberweide ''Taucha''', 'Salix alba ''Taucha''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('5549', '530', 'Säulenförmige Krimlinde', 'Tilia euchlora ''Pallida Fastigiata''');
INSERT INTO ukos_okstra.wlo_baumart VALUES ('9999', '000', 'Baumart nicht bestimmt', 'nicht bestimmt');


--
-- Data for Name: wlo_baumgattung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('000', 'Baum (allgemein)', '');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('030', 'Laubbaum', '');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('040', 'Nadelbaum', '');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('100', 'Ahorn', 'Acer');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('110', 'Rosskastanie', 'Aesculus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('120', 'Götterbaum', 'Ailanthus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('130', 'Erle', 'Alnus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('140', 'Aralie', 'Aralia');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('150', 'Berberitze', 'Berberis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('160', 'Birke', 'Betula');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('170', 'Hainbuche', 'Carpinus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('180', 'Hickory', 'Carya');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('190', 'Kastanie', 'Castanea');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('200', 'Trompetenbaum', 'Catalpa');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('210', 'Zürgelbaum', 'Celtis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('220', 'Kasurabaum', 'Cercidiphyllum');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('230', 'Judasbaum', 'Cercis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('240', 'Hartriegel', 'Cornus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('250', 'Scheinhasel', 'Corylopsis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('260', 'Haselnuss', 'Corylus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('270', 'Weißdort', 'Crataegus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('280', 'Ölweide', 'Elaeagnus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('290', 'Spindelstrauch', 'Euonymus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('300', 'Buche', 'Fagus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('310', 'Esche', 'Fraxinus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('320', 'Gleditschie Lederhülsenbaum', 'Gleditsia');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('330', 'Zaubernuss', 'Hamamelis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('340', 'Sanddort', 'Hippophae');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('350', 'Nussbaum', 'Juglans');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('360', 'Goldregen', 'Laburnum');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('370', 'Magnolie', 'Magnolia');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('380', 'Kultur-Apfel', 'Malus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('390', 'Maulbeere', 'Morus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('400', 'Platane', 'Platanus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('410', 'Pappel', 'Populus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('420', 'Pflaume, Krische, Pfirsich', 'Prunus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('430', 'Flügelnuss', 'Pterocaria');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('440', 'Birne', 'Pyrus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('450', 'Eiche', 'Quercus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('460', 'Kreuzdorn', 'Rhamnus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('470', 'Sumach', 'Rhus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('480', 'Robinie', 'Robinia');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('490', 'Weide', 'Salix');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('500', 'Holunder', 'Sambucus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('510', 'Eberesche', 'Sorbus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('520', 'Flieder', 'Syringa');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('530', 'Linde', 'Tilia');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('540', 'Ulme', 'Ulmus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('550', 'Zelkove', 'Zelkova');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('700', 'Tanne', 'Abies');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('710', 'Zeder', 'Cedrus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('720', 'Scheinzypresse', 'Chamaecyparis');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('730', 'Ginkgo, Fächerblattbaum', 'Ginkgo');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('740', 'Wachholder', 'Juniperus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('750', 'Lärche', 'Larix');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('760', 'Fichte', 'Picea');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('770', 'Kiefer', 'Pinus');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('780', 'Lebensbaum', 'Thuja');
INSERT INTO ukos_okstra.wlo_baumgattung VALUES ('790', 'Hemlocktanne', 'Tsuga');


--
-- Data for Name: wlo_lagebeschreibung_baum; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('1', 'Baum auf Trennstreifen zwischen Radweg und Straße');
INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('2', 'Baum zwischen Radweg und Graben');
INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('3', 'Baum zwischen Radweg und benachbartem Grundstück');
INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('4', 'Baum im Geh- oder Radweg');
INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('5', 'Baum in Pflasterfläche');
INSERT INTO ukos_okstra.wlo_lagebeschreibung_baum VALUES ('6', 'Baum hinter Gehweg');


--
-- Data for Name: wlo_schiefstand_baum; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_schiefstand_baum VALUES ('0', 'kein');
INSERT INTO ukos_okstra.wlo_schiefstand_baum VALUES ('1', 'ja, ohne Angabe');
INSERT INTO ukos_okstra.wlo_schiefstand_baum VALUES ('2', 'zur Fahrbahn');
INSERT INTO ukos_okstra.wlo_schiefstand_baum VALUES ('3', 'von der Fahrbahn');
INSERT INTO ukos_okstra.wlo_schiefstand_baum VALUES ('4', 'parallel zur Fahrbahn');


--
-- Data for Name: wlo_zustandsbeurteilung_baum; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_zustandsbeurteilung_baum VALUES ('1', 'gesund');
INSERT INTO ukos_okstra.wlo_zustandsbeurteilung_baum VALUES ('2', 'sehr schwach geschädigt');
INSERT INTO ukos_okstra.wlo_zustandsbeurteilung_baum VALUES ('3', 'mittelstark geschädigt');
INSERT INTO ukos_okstra.wlo_zustandsbeurteilung_baum VALUES ('4', 'stark geschädigt');
INSERT INTO ukos_okstra.wlo_zustandsbeurteilung_baum VALUES ('5', 'absterbend bis tot');


--
-- Data for Name: baum; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_belastungsklasse; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_belastungsklasse VALUES ('01', 'Soll-Belastungsklasse');
INSERT INTO ukos_okstra.wlo_art_belastungsklasse VALUES ('02', 'Ist-Belastungsklasse');


--
-- Data for Name: wlo_belastungsklasse_rsto; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('01', 'Bk 32');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('02', 'Bk 10');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('03', 'Bk 3,2');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('04', 'Bk 1,8');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('05', 'Bk 1,0');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('06', 'Bk 0,3');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('07', 'Bk 100');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('98', 'sonstige Belastungsklasse');
INSERT INTO ukos_okstra.wlo_belastungsklasse_rsto VALUES ('99', 'keine Zuordnung möglich');


--
-- Data for Name: wlo_belastungsklasse_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: belastungsklasse; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_bestandsstatus; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('1', 'Bestand_erfasst');
INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('2', 'Bestand_amtlich');
INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('3', 'geplant/neu');
INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('4', 'geplant/Entfall');
INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('5', 'zerstört');
INSERT INTO ukos_okstra.wlo_bestandsstatus VALUES ('6', 'unbekannt');


--
-- Data for Name: wlo_erfassung_verfahren; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('1', 'photogrammetrisch');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('2', 'photogrammetrisch mit Feldvergleich');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('3', 'terrestrisch aufgemessen');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('4', 'digitalisiert');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('5', 'eingeschritten');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('6', 'Übernahme aus Liegenschaftskarte');
INSERT INTO ukos_okstra.wlo_erfassung_verfahren VALUES ('99', 'sonstige');


--
-- Data for Name: wlo_schutzstatus_bewuchs; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_schutzstatus_bewuchs VALUES ('1', 'Landschaftsschutzgebiet (LSG)');
INSERT INTO ukos_okstra.wlo_schutzstatus_bewuchs VALUES ('2', 'Naturschutzgebiet (NSG)');
INSERT INTO ukos_okstra.wlo_schutzstatus_bewuchs VALUES ('3', 'Naturdenkmal (ND)');
INSERT INTO ukos_okstra.wlo_schutzstatus_bewuchs VALUES ('4', 'Fauna/Flora/Habitat (FFH)');
INSERT INTO ukos_okstra.wlo_schutzstatus_bewuchs VALUES ('5', 'geschützter Landschaftsbestandteil');


--
-- Data for Name: wlo_tab_biotoptyp; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: bewuchs; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: bruecke; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_funktion_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('01', 'Gewässer 2. Ordnung');
INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('02', 'Grundstücksentwässerung (fremd)');
INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('03', 'Straßenentwässerung');
INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('97', 'verschüttet');
INSERT INTO ukos_okstra.wlo_funktion_durchlass VALUES ('98', 'verpresst');


--
-- Data for Name: wlo_lage_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('01', 'links, längs');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('02', 'links, quer (andere Streifen)');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('03', 'unter linker Fahrbahn');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('04', 'unter beiden Fahrbahnen');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('05', 'unter rechter Fahrbahn');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('06', 'rechts, quer (andere Streifen)');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('07', 'rechts, längs');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('08', 'Mitte längs');
INSERT INTO ukos_okstra.wlo_lage_durchlass VALUES ('09', 'unter einbahniger Fahrbahn');


--
-- Data for Name: wlo_material_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('01', 'Holz');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('02', 'Beton');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('03', 'Mauerwerk');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('04', 'Stahl/Metall');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('05', 'Kunststoff');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('06', 'Steinzeug');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('07', 'Natursteinmauerwerk');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('08', 'Ton');
INSERT INTO ukos_okstra.wlo_material_durchlass VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_profil_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('01', 'Rechteck');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('02', 'Rechteck mit Gewölbe');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('03', 'Kreis');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('04', 'Ei');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('05', 'Fünfeck (Rinne mit Rechteck)');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('06', 'Maul-/Haubenquerschnitt');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('07', 'Mehrfachrechteck');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('08', 'Mehrfachkreis');
INSERT INTO ukos_okstra.wlo_profil_durchlass VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_schutzeinrichtung_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_schutzeinrichtung_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_schutzeinrichtung_durchlass VALUES ('01', 'Schutzplanke');
INSERT INTO ukos_okstra.wlo_schutzeinrichtung_durchlass VALUES ('02', 'Geländer');
INSERT INTO ukos_okstra.wlo_schutzeinrichtung_durchlass VALUES ('03', 'Mauer/Brüstung');


--
-- Data for Name: wlo_sonstige_unterhaltspflichtige; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_stadium_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_stadium_durchlass VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_stadium_durchlass VALUES ('01', 'in Betrieb');
INSERT INTO ukos_okstra.wlo_stadium_durchlass VALUES ('02', 'nicht in Betrieb');


--
-- Data for Name: wlo_zustand_durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_zustand_durchlass VALUES ('01', 'gut');
INSERT INTO ukos_okstra.wlo_zustand_durchlass VALUES ('02', 'mittel');
INSERT INTO ukos_okstra.wlo_zustand_durchlass VALUES ('03', 'schlecht');


--
-- Data for Name: durchlass; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_fahrzeugart; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('nk Kfz', 'nicht klassifizierbare Fahrzeuge (Sonstige)');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Krad', 'Motorräder');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Pkw(grund)', 'Pkw');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Lfw', 'Lieferwagen');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Pkw', 'Krad + Pkw(grund) + Lfw');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('PkwÄ', 'Pkw + nk Kfz');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('PkwA', 'Pkw und Lfw mit Anhänger');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Lkw', 'Lkw mit einem zulässigen Gesamtgewicht von mehr als 3,5 t');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('LkwA(grund)', 'Lkw mit Anhänger');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Sattel-Kfz', 'Sattelkraftfahrzeuge');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('LkwA', 'LkwA(grund) + Sattel-Kfz');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Bus', 'Busse mit mehr als 9 Sitzplätzen');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('LkwÄ', 'PkwA + Lkw + LkwA + Bus');
INSERT INTO ukos_okstra.wlo_fahrzeugart VALUES ('Kfz', 'PkwÄ + LkwÄ');


--
-- Data for Name: wlo_verkehrsrichtung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_verkehrsrichtung VALUES ('B', 'Verkehr in beiden Richtungen');
INSERT INTO ukos_okstra.wlo_verkehrsrichtung VALUES ('R', 'Einbahnverkehr in Stationierungsrichtung');
INSERT INTO ukos_okstra.wlo_verkehrsrichtung VALUES ('G', 'Einbahnverkehr gegen Stationierungsrichtung');


--
-- Data for Name: durchschnittsgeschwindigkeit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: fahrstreifen_nummer; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: fkt_d_verb_im_knotenpktber; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: gebuehrenpflichtig; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: hausnummer; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: kommunikationsobjekt; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_komplexer_knoten; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_komplexer_knoten VALUES ('1', 'plangleicher Knoten');
INSERT INTO ukos_okstra.wlo_art_komplexer_knoten VALUES ('2', 'planfreier Knoten');
INSERT INTO ukos_okstra.wlo_art_komplexer_knoten VALUES ('3', 'teilplanfreier Knoten');
INSERT INTO ukos_okstra.wlo_art_komplexer_knoten VALUES ('4', 'Kreisverkehr');


--
-- Data for Name: komplexer_knoten; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: laermschutzbauwerk; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_leitung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('01', 'Elektrizität');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('02', 'Gas');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('03', 'Wasser');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('04', 'Abwasser');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('05', 'Telekommunikation');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('06', 'Fernwärme');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('07', 'Öl');
INSERT INTO ukos_okstra.wlo_art_leitung VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_art_leitung_detail; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0101', 'Elektrizität Niedrigspannung');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0102', 'Elektrizität Mittelspannung');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0103', 'Elektrizität Hochspannung');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0104', 'Elektrizität unbekannter Spannung');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0201', 'Erdgas (Hochdruck)');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0202', 'Erdgas (Mitteldruck)');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0301', 'Trinkwasser / Brauchwasser');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0401', 'Schmutzwasser (Gefälle)');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0402', 'Schmutzwasser (Druck)');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0403', 'Regenwasser / Niederschlagwasser');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0404', 'Mischwasser');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0501', 'TV Breitband');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0502', 'TV Freileitung');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0503', 'Fernmeldekabel');
INSERT INTO ukos_okstra.wlo_art_leitung_detail VALUES ('0701', 'Mineralöl');


--
-- Data for Name: wlo_betreiber_leitung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_lage_leitung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('01', 'links, längs');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('03', 'unter linker Fahrbahn');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('04', 'unter beiden Fahrbahnen');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('05', 'unter rechter Fahrbahn');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('07', 'rechts, längs');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('08', 'Mitte längs');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('09', 'unter einbahniger Fahrbahn');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('91', 'befestigter Seitenstreifen links');
INSERT INTO ukos_okstra.wlo_lage_leitung VALUES ('92', 'befestigter Seitenstreifen rechts');


--
-- Data for Name: wlo_material_leitung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('01', 'PVC (Polyvinylchlorid)');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('02', 'PE (Polyethylen)');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('03', 'GFK (glasfaserverstärkte Kunststoffe)');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('04', 'Stahl');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('05', 'Grauguss');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('06', 'Asbestzement');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('07', 'Steinzeug');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('08', 'Beton');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('09', 'GGG (Duktiles Gussrohr)');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('10', 'LWL (Lichtwellenleiter)');
INSERT INTO ukos_okstra.wlo_material_leitung VALUES ('11', 'KG (Kanalgrundrohr-PVC)');


--
-- Data for Name: wlo_material_schutzrohr; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('01', 'PVC Polyvinylchlorid (schwer entflammbar)');
INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('02', 'PE Polyethylen');
INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('03', 'Stahl');
INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('04', 'Steinzeug');
INSERT INTO ukos_okstra.wlo_material_schutzrohr VALUES ('05', 'HDPE Polyethylen (sehr dicht)');


--
-- Data for Name: leitung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_anschriftstyp; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_anschriftstyp VALUES ('1', 'Postadresse');
INSERT INTO ukos_okstra.wlo_anschriftstyp VALUES ('2', 'Büroadresse');


--
-- Data for Name: wlo_dienstlich_privat; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_dienstlich_privat VALUES ('1', 'dienstlich');
INSERT INTO ukos_okstra.wlo_dienstlich_privat VALUES ('2', 'privat');


--
-- Data for Name: wlo_kommunikationstyp; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_kommunikationstyp VALUES ('1', 'Telefonnummer');
INSERT INTO ukos_okstra.wlo_kommunikationstyp VALUES ('2', 'Faxnummer');
INSERT INTO ukos_okstra.wlo_kommunikationstyp VALUES ('3', 'Mobiltelefonnummer');
INSERT INTO ukos_okstra.wlo_kommunikationstyp VALUES ('4', 'Emailadresse');
INSERT INTO ukos_okstra.wlo_kommunikationstyp VALUES ('9', 'Sonstiges');


--
-- Data for Name: wlo_organisationsart; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('1', 'Bundesministerium');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('2', 'Landesministerium');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('3', 'Landesverwaltung');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('4', 'Landesbetrieb');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('5', 'Regierungspräsidium');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('6', 'Kreisverwaltung');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('7', 'Stadtverwaltung');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('8', 'Bezirksverwaltung');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('9', 'Straßen- oder Autobahnmeisterei');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('50', 'AG');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('51', 'GmbH');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('52', 'GmbH & Co. KG');
INSERT INTO ukos_okstra.wlo_organisationsart VALUES ('99', 'Sonstiges');


--
-- Data for Name: organisation; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: organisationseinheit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_personenklasse; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('?', 'unbekannt');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('G', 'Gemeindeverwaltung');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('J', 'juristische Person');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('L', 'Landwirtschaftsamt');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('N', 'natürliche Person');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('Ö', 'öffentlicher Bedarfsträger');
INSERT INTO ukos_okstra.wlo_personenklasse VALUES ('V', 'verstorben');


--
-- Data for Name: person; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_anzahl_gleise_laengs; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_anzahl_gleise_laengs VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_anzahl_gleise_laengs VALUES ('1', 'ein Gleis');
INSERT INTO ukos_okstra.wlo_anzahl_gleise_laengs VALUES ('2', 'zwei Gleise');
INSERT INTO ukos_okstra.wlo_anzahl_gleise_laengs VALUES ('3', 'drei oder mehr Gleise');


--
-- Data for Name: wlo_art_der_oberflaeche; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('01', 'Grasfläche mit Intensivpflege');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('02', 'Grasfläche mit Extensivpflege');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('03', 'Grasfläche, Pflege nicht spezifiziert');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('04', 'Gehölz mit Intensivpflege');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('05', 'Gehölz mit Extensivpflege');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('06', 'Gehölz, Pflege nicht spezifiziert');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('11', 'versiegelt');
INSERT INTO ukos_okstra.wlo_art_der_oberflaeche VALUES ('12', 'befestigt, unversiegelt');


--
-- Data for Name: wlo_art_part_baulasttraeger; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('01', 'Land');
INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('02', 'Kreis / kreisfreie Stadt');
INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('03', 'Gemeinde');
INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('09', 'Dritter');
INSERT INTO ukos_okstra.wlo_art_part_baulasttraeger VALUES ('10', 'keine Unterhaltungspflicht');


--
-- Data for Name: wlo_sonstiger_ui_partner_land; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_streifenart; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_streifenart VALUES ('100', 'Fahrbahn');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('110', 'Hauptfahrstreifen (HFS)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('111', '1. Überholstreifen (UE1)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('112', '2. Überholstreifen (UE2)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('113', '3. Überholstreifen (UE3)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('114', 'Zusatzfahrstreifen (ZFS)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('115', 'Sonderfahrstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('116', 'Rechtsabbiegefahrstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('117', 'Linksabbiegefahrstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('120', 'offene Rinne');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('121', 'Kastenrinne');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('122', 'Schlitzrinne');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('130', 'Beschleunigungsstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('131', 'Verzögerungsstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('132', 'Verflechtungsstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('135', 'Bedarfsfahrstreifen im Kreisverkehr');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('140', 'Fahrbahnteil, der dem Schienenverkehr vorbehalten ist');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('160', 'Mehrzweckstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('161', 'Mehrzweckstreifen ohne Fahrradbenutzung');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('162', 'Mehrzweckstreifen mit Fahrradbenutzung');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('170', 'Standstreifen, Parkstreifen (nicht Parkplatz)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('171', 'Seitenstreifen, befestigt');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('172', 'Seitenstreifen, befestigt, temporär als Fahrstreifen genutzt');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('174', 'Haltebucht allgemein');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('175', 'Haltebucht');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('176', 'Bushaltebucht');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('177', 'Nothaltebucht');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('180', 'Parkstreifen (nicht Parkplatz)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('181', 'Parkstreifen mit Grünflächen zwischen den Parkfeldern');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('210', 'Gehweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('220', 'paralleler Wirtschaftsweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('230', 'sonstiger paralleler Weg ohne Kfz-Verkehr');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('240', 'Radweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('241', 'Radweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('242', 'anderer Radweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('243', 'Radfahrstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('250', 'Rad- und Gehweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('251', 'Gemeinsamer Rad- und Gehweg');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('300', 'unbefestigter Seitenstreifen (Bankett), ebenes Gelände');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('301', 'Bankett');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('302', 'Seitenstreifen, unbefestigt; ebenes Gelände');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('310', 'unbefestigter Trennstreifen (z.B. Mittel-, Schutzstreifen)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('311', 'Mittelstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('312', 'Mittelstreifenüberfahrt');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('313', 'Seitentrennstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('314', 'Verkehrsinsel/Querungshilfe ');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('315', 'Haltestelleninsel');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('320', 'befestigter Trennstreifen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('330', 'Trennschwelle (Trennbord), Trennplanke, Trennbauwerk');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('340', 'eigener Gleiskörper');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('400', 'Randstreifen (Leitstreifen), konstruktiv von der Fahrbahn getrennt');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('410', 'Randstreifen (Leitstreifen), nicht konstruktiv von der Fahrbahn getrennt');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('420', 'Markierungs- und Sperrfläche');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('430', 'Markierte Doppeltrennlinie');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('500', 'offene Vollrinne (Regelform)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('510', 'Rasenmulde, befestigte Mulde');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('511', 'Mulde');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('520', 'Straßengraben');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('600', 'Kantenstein (Rabattenstein)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('610', 'Tiefbord (Flachbord)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('620', 'Schrägbord');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('630', 'Hochbord (Steilbord), Hohlbord');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('640', 'Bordstein allgemein');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('700', 'Dammböschung (abfallendes Gelände)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('701', 'Steinschlag auslösende Hänge (Dammlage)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('710', 'Einschnittböschung (ansteigendes Gelände)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('711', 'Steinschlag auslösende Hänge (Einschnitt)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('715', 'Sichtflächen an Kreuzungsbereichen');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('720', 'Sonstiger Querschnittstreifen im Seitenraum');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('730', 'Anliegerflächen (Flächen Dritter)');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('750', 'Kreisinsel');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('751', 'Baumscheibe');
INSERT INTO ukos_okstra.wlo_streifenart VALUES ('999', 'sonstige Streifenart');


--
-- Data for Name: wlo_streifenart_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: querschnittstreifen; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: querschnittstreifen_to_teilelement; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_angaben_zum_konus; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_angaben_zum_konus VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_angaben_zum_konus VALUES ('01', 'flach');
INSERT INTO ukos_okstra.wlo_angaben_zum_konus VALUES ('02', 'hoch');


--
-- Data for Name: wlo_art_schacht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('01', 'Prüfschacht');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('02', 'Ablaufschacht');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('03', 'Absturzschacht');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('04', 'Absetzschacht');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('05', 'Sickerschacht');
INSERT INTO ukos_okstra.wlo_art_schacht VALUES ('99', 'sonstiges');


--
-- Data for Name: wlo_lage_schacht_strassenablauf; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('01', 'linker Fahrbahnrand (einbahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('02', 'linke Fahrbahn, linker Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('04', 'linke Fahrbahn, rechter Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('05', 'Mitte/Bestandsachse');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('06', 'rechte Fahrbahn, linker Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('08', 'rechte Fahrbahn, rechter Fahrbahnrand (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('09', 'rechter Fahrbahnrand (einbahnig)');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('10', 'Hauptfahrstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('11', 'Hauptfahrstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('12', 'Hauptfahrstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('13', 'Hauptfahrstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('20', 'Hauptfahrstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('21', 'Hauptfahrstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('22', 'Hauptfahrstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('23', 'Hauptfahrstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('30', '1. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('31', '1. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('32', '1. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('33', '1. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('40', '1. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('41', '1. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('42', '1. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('43', '1. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('50', '2. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('51', '2. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('52', '2. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('53', '2. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('60', '2. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('61', '2. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('62', '2. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('63', '2. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('70', '3. Überholstreifen gegen Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('71', '3. Überholstreifen gegen Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('72', '3. Überholstreifen gegen Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('73', '3. Überholstreifen gegen Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('80', '3. Überholstreifen in Stat.-Richtung');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('81', '3. Überholstreifen in Stat.-Richtung, rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('82', '3. Überholstreifen in Stat.-Richtung, Mitte');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('83', '3. Überholstreifen in Stat.-Richtung, links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('91', 'befestigter Seitenstreifen links');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('92', 'befestigter Seitenstreifen rechts');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('95', 'links außerhalb');
INSERT INTO ukos_okstra.wlo_lage_schacht_strassenablauf VALUES ('96', 'rechts außerhalb');


--
-- Data for Name: schacht; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_schild_asb; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_schild_asb VALUES ('01', 'amtlicher Wegweiser');
INSERT INTO ukos_okstra.wlo_art_schild_asb VALUES ('02', 'amtliches Verkehrszeichen');
INSERT INTO ukos_okstra.wlo_art_schild_asb VALUES ('03', 'nichtamtliches Schild');


--
-- Data for Name: wlo_art_schild_nichtamtlich_asb; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_schild_nichtamtlich_asb VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_schild_nichtamtlich_asb VALUES ('01', 'militärische Tragfähigkeitsschilder');
INSERT INTO ukos_okstra.wlo_art_schild_nichtamtlich_asb VALUES ('02', 'private Wegweiser');
INSERT INTO ukos_okstra.wlo_art_schild_nichtamtlich_asb VALUES ('99', 'sonstige');


--
-- Data for Name: wlo_art_schild_ok; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_schild_ok VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_schild_ok VALUES ('01', 'amtliches Schild');
INSERT INTO ukos_okstra.wlo_art_schild_ok VALUES ('02', 'privates Schild');
INSERT INTO ukos_okstra.wlo_art_schild_ok VALUES ('03', 'militärisches Tragfähigkeitsschild');
INSERT INTO ukos_okstra.wlo_art_schild_ok VALUES ('99', 'sonstiges');


--
-- Data for Name: wlo_befestigung_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('01', 'Schelle');
INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('02', 'Kabelbinder');
INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('03', 'Aluminiumnägel');
INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('04', 'Stahlnägel');
INSERT INTO ukos_okstra.wlo_befestigung_schild VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_beleuchtung_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_beleuchtung_schild VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_beleuchtung_schild VALUES ('01', 'ohne Beleuchtung');
INSERT INTO ukos_okstra.wlo_beleuchtung_schild VALUES ('02', 'außenbeleuchtet');
INSERT INTO ukos_okstra.wlo_beleuchtung_schild VALUES ('03', 'innenbeleuchtet');


--
-- Data for Name: wlo_einzel_mehrfach_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_einzel_mehrfach_schild VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_einzel_mehrfach_schild VALUES ('01', 'Einzelschild');
INSERT INTO ukos_okstra.wlo_einzel_mehrfach_schild VALUES ('02', 'Bestandteil eines Mehrfachschildes');


--
-- Data for Name: wlo_groessenklasse_vz; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_groessenklasse_vz VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_groessenklasse_vz VALUES ('01', 'Klasse 1 (70%)');
INSERT INTO ukos_okstra.wlo_groessenklasse_vz VALUES ('02', 'Klasse 2 (100%)');
INSERT INTO ukos_okstra.wlo_groessenklasse_vz VALUES ('03', 'Klasse 3 (140%)');


--
-- Data for Name: wlo_lage_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_lage_schild VALUES ('01', 'wie Aufstellvorrichtung');
INSERT INTO ukos_okstra.wlo_lage_schild VALUES ('02', 'über gesamter Fahrbahn(en)(ein- und zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schild VALUES ('03', 'über linker Fahrbahn (zweibahnig)');
INSERT INTO ukos_okstra.wlo_lage_schild VALUES ('04', 'über rechter Fahrbahn (zweibahnig)');


--
-- Data for Name: wlo_strassenbezug_asb; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_strassenbezug_asb VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_strassenbezug_asb VALUES ('1', 'aktuelle Straße');
INSERT INTO ukos_okstra.wlo_strassenbezug_asb VALUES ('2', 'nachgeordnetes Netz');


--
-- Data for Name: wlo_unterhaltungspflicht_schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('01', 'Land');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('02', 'Kreis / kreisfreie Stadt');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('03', 'Gemeinde');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('04', 'Straßenbauamt/Niederlassung');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('05', 'Meisterei');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('09', 'Sonstige Partner');
INSERT INTO ukos_okstra.wlo_unterhaltungspflicht_schild VALUES ('99', 'noch unbekannt');


--
-- Data for Name: schild; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_aek_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_pfostenbefestigung_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl VALUES ('01', 'gerammt');
INSERT INTO ukos_okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl VALUES ('02', 'geschraubt');
INSERT INTO ukos_okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl VALUES ('03', 'gesteckt');


--
-- Data for Name: wlo_holmform_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_holmform_schutzeinr_stahl VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_holmform_schutzeinr_stahl VALUES ('01', 'Profil A');
INSERT INTO ukos_okstra.wlo_holmform_schutzeinr_stahl VALUES ('02', 'Profil B');
INSERT INTO ukos_okstra.wlo_holmform_schutzeinr_stahl VALUES ('03', 'sonstige Konstruktion');


--
-- Data for Name: wlo_modulbezeichnung_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M01', 'einfache Schutzplanke (ESP)');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M02', 'einfache Distanzschutzplanke (EDSP)');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M03', 'Super-Rail Eco/light');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M04', 'Super-Rail');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M05a', 'Mega Rail sl');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M05b', 'Mega Rail s');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('M07', 'Easy Rail');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('A01', 'doppelte Schutzplanke');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('A02', 'doppelte Distanzschutzplanke');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('A03', 'Absturzsicherung Safety Rail');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('A04', 'kurze Schutzplanke');
INSERT INTO ukos_okstra.wlo_modulbezeichnung_schutzeinr_stahl VALUES ('99', 'sonstige');


--
-- Data for Name: wlo_pfostenform_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_pfostenform_schutzeinr_stahl VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_pfostenform_schutzeinr_stahl VALUES ('01', 'Sigma 100 - Pfosten');
INSERT INTO ukos_okstra.wlo_pfostenform_schutzeinr_stahl VALUES ('02', 'IPE 100 - Pfosten');
INSERT INTO ukos_okstra.wlo_pfostenform_schutzeinr_stahl VALUES ('03', 'sonstige Konstruktion');


--
-- Data for Name: wlo_standort_rueckhaltesystem; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('01', 'neben Fahrbahn');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('02', 'im Mittelstreifen');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('03', 'neben Notrufsäule');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('04', 'neben seitlichem Hindernis');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('05', 'neben Schilderbrücke');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('06', 'vor Brücke');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('07', 'auf Brücke');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('08', 'im Bereich von Lärmschutzwand');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('09', 'auf Trenninsel');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('10', 'im Bereich eines Dammes');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('11', 'im Bereich einer Absenkung/Einschnittes');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('12', 'auf Stützmauer');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('13', 'im Bereich eines Gewässers');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('14', 'neben Fußgängerweg / Fußgängerpfad');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('15', 'neben Radweg');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('16', 'neben untergeordnetem Verkehrsweg');
INSERT INTO ukos_okstra.wlo_standort_rueckhaltesystem VALUES ('17', 'vor Einzelbaum / Einzelbäumen');


--
-- Data for Name: wlo_systemname_schutzeinr_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: schutzeinrichtung_aus_stahl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: segment_kommunale_strasse; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: spur_fuer_rettungsfahrzeuge; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: stadium; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_aufsatz; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('01', 'Pultaufsatz');
INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('02', 'Rinnenaufsatz');
INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('03', 'Kombiaufsatz');
INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('04', 'Seitenablauf');
INSERT INTO ukos_okstra.wlo_art_aufsatz VALUES ('05', 'Bergeinlauf');


--
-- Data for Name: wlo_art_unterteil; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_unterteil VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_unterteil VALUES ('01', 'Unterteil für Trockenschlamm');
INSERT INTO ukos_okstra.wlo_art_unterteil VALUES ('02', 'Unterteil für Nassschlamm');
INSERT INTO ukos_okstra.wlo_art_unterteil VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_art_unterteil_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: strassenablauf; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_strassenausst_punkt; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('01', 'Glättemeldeanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('02', 'Streugutbehälter');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('03', 'Taumittelsprühanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('04', 'Geschwindigkeitswarnanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('05', 'Verkehrsbeeinflussungsanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('06', 'Lichtsignalanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('07', 'Nebelwarnanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('08', 'Geschwindigkeitsüberwachungsanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('09', 'Stauwarnanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('10', 'Verkehrsspiegel');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('11', 'Notrufsäule');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('12', 'SOS-Telefon');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('14', 'Leitpfosten');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('15', 'Kilometerstein, Kilometertafel');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('16', 'historischer Kilometerstein');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('17', 'Abfallbehälter (nur an der Strecke)');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('18', 'Flucht- / Schlupftür in Wänden / Zäunen');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('19', 'Beleuchtung');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('20', 'Bauwerkstafel');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('21', 'Schneezeichen');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('22', 'Ortsdurchfahrtszeichen');
INSERT INTO ukos_okstra.wlo_art_strassenausst_punkt VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_art_strausst_punkt_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: strassenausstattung_punkt; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_strassenausst_strecke; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('01', 'Strecke mit Glättemeldeanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('02', 'Strecke mit Taumittelsprühanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('03', 'Strecke mit Verkehrsbeeinflussungsanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('04', 'Strecke mit Nebelwarnanlage');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('05', 'Schneefangzaun');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('06', 'Blendschutz');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('07', 'Hangsicherung');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('08', 'Geröllfangzaun');
INSERT INTO ukos_okstra.wlo_art_strassenausst_strecke VALUES ('99', 'Sonstiges');


--
-- Data for Name: wlo_art_strausst_strecke_sonst; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: strassenausstattung_strecke; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: strassenbeschreibung_verkehrl; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_stufe_strassenelement; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_stufe_strassenelement VALUES ('1', 'Hauptverbindung');
INSERT INTO ukos_okstra.wlo_stufe_strassenelement VALUES ('2', 'Nebenverbindung');


--
-- Data for Name: wlo_verkehrsrichtung_se; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_verkehrsrichtung_se VALUES ('R', 'Verkehrsrichtung von Von-VP nach Nach-VP');
INSERT INTO ukos_okstra.wlo_verkehrsrichtung_se VALUES ('G', 'Verkehrsrichtung von Nach-VP nach Von-VP');
INSERT INTO ukos_okstra.wlo_verkehrsrichtung_se VALUES ('B', 'In beiden Richtungen');
INSERT INTO ukos_okstra.wlo_verkehrsrichtung_se VALUES ('K', 'In keiner Richtung');

--
-- Data for Name: strassenelementpunkt; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: strassenfunktion; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: stuetzbauwerk; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_strassenklasse; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('A', 'Bundesautobahn');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('B', 'Bundesstraße');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('L', 'Landesstraße');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('S', 'Staatsstraße');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('K', 'Kreisstraße');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('G', 'Gemeindestraße');
INSERT INTO ukos_okstra.wlo_strassenklasse VALUES ('N', 'Nicht öffentliche Straße');


--
-- Data for Name: teilbauwerk; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: teilelement; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: tunnel_trogbauwerk; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: verbindungspunkt; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_verkehrsteilnehmergruppe; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('01', 'alle Kraftfahrzeuge');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('02', 'alle Fahrzeuge');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('03', 'Lkw');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('04', 'Pkw');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('05', 'Krafträder');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('06', 'Kraftomnibusse');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('07', 'Radfahrer');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('08', 'Gefahrguttransport');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('09', 'Fußgänger');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('10', 'Straßenbahn');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('11', 'Taxi');
INSERT INTO ukos_okstra.wlo_verkehrsteilnehmergruppe VALUES ('99', 'Sonstige');


--
-- Data for Name: verbotene_fahrbeziehung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_ves; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_art_ves VALUES ('00', 'unbekannt');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('01', 'Geschwindigkeitsbeschränkung');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('02', 'Durchfahrtsverbot');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('03', 'Maximale Achslast');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('04', 'Maximales Gesamtgewicht');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('05', 'Maßbeschränkung in der Höhe');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('06', 'Maßbeschränkung in der Breite');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('07', 'Maßbeschränkung in der Länge');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('08', 'Überholverbot');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('09', 'Mindestgeschwindigkeit');
INSERT INTO ukos_okstra.wlo_art_ves VALUES ('99', 'Sonstige Verbote (z.B. Halteverbot)');


--
-- Data for Name: wlo_bezugsrichtung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_bezugsrichtung VALUES ('0', 'unbekannt');
INSERT INTO ukos_okstra.wlo_bezugsrichtung VALUES ('B', 'beide Richtungen');
INSERT INTO ukos_okstra.wlo_bezugsrichtung VALUES ('R', 'in Stationierungsrichtung');
INSERT INTO ukos_okstra.wlo_bezugsrichtung VALUES ('G', 'gegen Stationierungsrichtung');


--
-- Data for Name: wlo_gueltigkeit_ves; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('01', 'permanent');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('02', 'bei Nässe');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('03', 'Eis');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('04', 'bei Dunkelheit');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('05', 'Zeitangabe');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('06', 'Verbotsstrecke');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('07', 'VBA');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('08', 'bei Bedarf (verdeckbar)');
INSERT INTO ukos_okstra.wlo_gueltigkeit_ves VALUES ('99', 'sonstiges');


--
-- Data for Name: wlo_querschnitt_streifenart_ves; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('110', 'Hauptfahrstreifen (HFS)');
INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('111', '1. Überholstreifen (UE1)');
INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('112', '2. Überholstreifen (UE2)');
INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('113', '3. Überholstreifen (UE3)');
INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('114', 'Zusatzfahrstreifen (ZFS)');
INSERT INTO ukos_okstra.wlo_querschnitt_streifenart_ves VALUES ('115', 'Sonderfahrstreifen (z. B. Busse)');


--
-- Data for Name: wlo_wochentag_ves; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('00', 'permanent');
INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('01', 'Werktags');
INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('02', 'Montag bis Freitag');
INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('03', 'Sonn- und Feiertags');
INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('04', 'Samstag und Sonntag');
INSERT INTO ukos_okstra.wlo_wochentag_ves VALUES ('99', 'sonstiges');


--
-- Data for Name: verkehrseinschraenkung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: verkehrsflaeche; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_orientierungsrichtung; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_orientierungsrichtung VALUES ('R', 'in Definitionsrichtung');
INSERT INTO ukos_okstra.wlo_orientierungsrichtung VALUES ('G', 'gegen Definitionsrichtung');
INSERT INTO ukos_okstra.wlo_orientierungsrichtung VALUES ('B', 'beide Richtungen');


--
-- Data for Name: verkehrsnutzungsbereich; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: verkehrszeichenbruecke; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_art_zustaendigkeit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- Data for Name: wlo_tab_funktion; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('01', 'Ausfahrt');
INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('02', 'Einfahrt');
INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('03', 'Parallelfahrbahn (baulich getrennt)');
INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('04', 'Verflechtungsspur');
INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('05', 'Verzögerungsspur');
INSERT INTO ukos_okstra.wlo_tab_funktion VALUES ('06', 'Beschleunigungsspur');


--
-- Data for Name: wlo_tab_stadium; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--

INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('000', 'unbekannt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('VP', 'Vorplanung hat begonnen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('UVA', 'Umweltverträglichkeitsstudie bzw. Variantenuntersuchung hat begonnen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('UVE', 'Umweltverträglichkeitsstudie bzw. Variantenuntersuchung ist abgeschlossen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('LBV', 'Unterlagen für Linienbestimmung/Trassenfestlegung werden aufgestellt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('LBE', 'Linie bestimmt/Trassenführung festgelegt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('VE', 'Vorentwurf hat begonnen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('VEG', 'Vorentwurf genehmigt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('PA', 'Planfeststellungsverfahren beantragt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('PB', 'Planfeststellungsbeschluss ergangen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('PU', 'Planfeststellungsbeschluss bestandskräftig');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('BAU', 'Durchführung der Bauarbeiten begonnen');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('VFV', 'Verkehrsfreigabe der Gesamtstrecke der Verkehrseinheit ist erfolgt');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('EPL', 'Erneuerung/Ersatzneubau in Planung');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('EAU', 'Erneuerung/Ersatzneubau in Ausführung');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('IPL', 'Instandsetzung in Planung');
INSERT INTO ukos_okstra.wlo_tab_stadium VALUES ('IAU', 'Instandsetzung in Ausführung');


--
-- Data for Name: zustaendigkeit; Type: TABLE DATA; Schema: ukos_okstra; Owner: -
--



--
-- PostgreSQL database dump complete
--

INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Koordinatengenauigkeit', '0.0001', '0.0001', 'numeric', 'Legt die Genauigkeit der im System verwendeten Koordinaten fest. Die Geometrien aller erzeugten Objekte werden vor dem Speichern mit ST_SnapToGrid auf diese Genauigkeit gerundet. Dadurch wird ein exakter vergleich von Koordinaten in binärer und Textschreibweise möglich. ST_Equals(ST_MakePoint(500000, 6000000), ST_MakePoint(500000.00000000001, 6000000) ist true, ST_Equals(ST_MakePoint(500000, 6000000), ST_MakePoint(500000.0000000001, 6000000) ist false, Ist der Wert NULL, wird ST_SnapToGrid nicht angewendet, Einheit in Meter''');
INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Topologietolerance', '0.1', '0.1', 'numeric', 'Legt die Toleranz bei der Erzeugung der Topologie fest. Gilt auch als Fangradius für Punkte und Punkte auf Linien. Einheit in Meter');
INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Strassenelementsuchabstand', '200', '200', 'integer', 'Der maximale Abstand in dem die Zugehörigkeit eines Objektes zu einem Strassenelement gesucht wird. Ist der Abstand des dichtesten Punktes eines Querschnittstreifens zu einem Strassenelement größer als der hier angegebene Wert, wird bei der Zuordnung zum dichtesten Strassenelement ein Fehler ausgegeben.');
INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Löschsperre', 'true', 'true', 'boolean', 'true Verhindert das Löschen von noch gültigen Objekten bei der Benutzung der Triggerfunktion stop(). Bei false werden Werte in Tabellen auch gelöscht wenn Sie die Triggerfunktion stop() benutzen.');
INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Triggerlog', 'true', 'false', 'boolean', 'Schaltet das Loggen von Meldungen der Trigger in der Tabelle trigger_logs ein oder aus.');
INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES ('Debugmodus', 'true', 'false', 'boolean', 'Schaltet den Debugmodus ein. Dadurch werden zusätzliche Meldungen über RAISE NOTICE in den Triggerfunktionen ausgegeben.');

INSERT INTO ukos_okstra.verbindungspunkt (id, stelle_id, punktgeometrie) values ('00000000-0000-0000-0000-000000000000', 0, st_geomfromtext('point(0 0)', 25833));

INSERT INTO ukos_okstra.widmung (id) VALUES('00000000-0000-0000-0000-000000000000');

INSERT INTO ukos_okstra.strasse (id) values('00000000-0000-0000-0000-000000000000');

insert into ukos_okstra.strassenelement (stelle_id, liniengeometrie, beginnt_bei_vp, endet_bei_vp) values (0, st_geomfromtext('LINESTRING(0 0, 1 0, 1 1, 0 0)', 25833), '00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000000');

COMMIT;