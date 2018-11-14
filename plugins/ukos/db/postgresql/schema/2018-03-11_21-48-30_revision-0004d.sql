-- Tabelle ukos_base.wld_material
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_material (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('1', '', '');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('2', 'B', 'Beton');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('3', 'G', 'Gestein');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('4', 'G', 'Granit');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('5', 'H', 'Holz');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('8', 'K', 'Kunststoff');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('11', 'M', 'Metall');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('12', 'M', 'Metall/Granit');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('14', 'P', 'PVC');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('15', 'S', 'Stein');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('16', 'U', 'unbekannt');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('18', 'Glas', 'Glas');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('19', 'Gummi', 'Gummi');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('21', 'Holz/Granit', 'Holz/Granit');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('22', 'Holz/Kunststoff', 'Holz/Kunststoff');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('29', 'Metall/Glas', 'Metall/Glas');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('30', 'Metall/Holz', 'Metall/Holz');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('31', 'Metall/Kunststoff', 'Metall/Kunststoff');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('32', 'Metall/Stein', 'Metall/Stein');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('36', 'Stein/Glas', 'Stein/Glas');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('37', 'Stein/Holz', 'Stein/Holz');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('38', 'N', 'Naturstein');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('39', 'Mix', 'Materialmix');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('40', 'Dr', 'Draht');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('45', 'Pflaster', 'Pflaster');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('46', 'Dr', 'Drainage');
INSERT INTO ukos_base.wld_material (ident_hist, kurztext, langtext)
 VALUES ('47', 'E', 'Erde');

-- Tabelle ukos_base.wld_baulasttraeger
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_baulasttraeger (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('1', 'B', 'Bund');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('2', 'G', 'Gemeinde');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('3', 'K', 'Kreis');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('4', 'L', 'Land');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('5', 'P', 'Privat');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('6', 'U', 'unbekannt');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('7', 'Z', 'ZVG');
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('8', 'WBV', 'Wasser- und Bodenverband');
-- zusätzliche Daten für HRO, benötigt von HRO-Papierkörben
INSERT INTO ukos_base.wld_baulasttraeger (ident_hist, kurztext, langtext)
 VALUES ('9', 'rsag', 'Rostocker Straßenbahn AG');

-- Tabelle ukos_base.wld_eigentuemer
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_eigentuemer (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('1', 'B', 'Bund');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('2', 'G', 'Gemeinde');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('3', 'K', 'Kreis');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('4', 'L', 'Land');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('5', 'P', 'Privat');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('6', 'U', 'unbekannt');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('7', 'Z', 'ZVG');
-- zusätzliche Daten für HRO, benötigt von HRO-Papierkörben
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('8', 'rsag', 'Rostocker Straßenbahn AG');
INSERT INTO ukos_base.wld_eigentuemer (ident_hist, kurztext, langtext)
 VALUES ('9', 'sr', 'Stadtentsorgung Rostock GmbH');

-- Tabelle ukos_base.wld_zustand
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_zustand (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('1', '1', '1');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('2', '2', '2');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('3', '3', '3');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('4', '4', '4');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('5', '5', '5');
INSERT INTO ukos_base.wld_zustand (ident_hist, kurztext, langtext)
 VALUES ('6', 'U', 'unbekannt');

-- Tabelle ukos_base.wld_preisermittlung
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_preisermittlung (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_preisermittlung (ident_hist, kurztext, langtext)
 VALUES ('1', 'G', 'Geschätzt');
INSERT INTO ukos_base.wld_preisermittlung (ident_hist, kurztext, langtext)
 VALUES ('2', 'R', 'Rechnnr. 01');
INSERT INTO ukos_base.wld_preisermittlung (ident_hist, kurztext, langtext)
 VALUES ('3', 'R', 'Rechnnr. 02');
INSERT INTO ukos_base.wld_preisermittlung (ident_hist, kurztext, langtext)
 VALUES ('4', 'E', 'Einzelwert');

-- Tabelle ukos_base.wld_klassifizierung
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('1', 'A', 'Anliegerstraße');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('2', 'H', 'Hauptverkehrsstraße');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('3', 'I', 'Innerortsstraße');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('4', 'U', 'unbekannt');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('5', 'V', 'Verkehrsberuhigter Bereich');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('6', 'BWA', 'Befahrbarer Wanderweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('7', 'BWI', 'Befahrbarer Wiesenweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('8', 'F', 'Feldweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('9', 'G', 'Gartenweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('10', 'W', 'Waldweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('11', 'Z', 'Zeltplatzweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('12', 'WW', 'Wanderweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('13', 'AEW', 'Anliegererschließungsweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('14', 'SBZ', 'Seebrückenzugang');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('15', 'A/F', 'Anliegerstraße/Feldweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('16', 'WSW', 'Wirtschaftsweg');
INSERT INTO ukos_base.wld_klassifizierung (ident_hist, kurztext, langtext)
 VALUES ('17', 'SZ', 'Strandzugang');

-- Tabelle ukos_base.wld_nutzung
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_nutzung (id, ident_hist, kurztext, langtext)
 VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt', 'unbekannt', 'unbekannt');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('3', '54101', 'Gemeindestraße');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('4', '54201', 'Kreisstraße');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('6', '', 'Sonstige');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('7', '', 'unbekannt');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('8', '55101', 'Öffentliche Grünflächen');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('9', '36601', 'Öffentliche Spielplätze');
INSERT INTO ukos_base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('10', '', 'Privat');

-- Tabelle ukos_base.wld_strassennetzlage
-- Daten aus ZVG-Bestand
INSERT INTO ukos_base.wld_strassennetzlage (ident_hist, kurztext, langtext)
 VALUES ('1', '1', '1');
INSERT INTO ukos_base.wld_strassennetzlage (ident_hist, kurztext, langtext)
 VALUES ('2', 'O', 'Ortslage');
INSERT INTO ukos_base.wld_strassennetzlage (ident_hist, kurztext, langtext)
 VALUES ('3', 'F', 'Feldlage');
INSERT INTO ukos_base.wld_strassennetzlage (ident_hist, kurztext, langtext)
 VALUES ('4', 'U', 'unbekannt');