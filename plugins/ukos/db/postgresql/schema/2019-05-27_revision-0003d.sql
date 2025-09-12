BEGIN;

DELETE FROM ukos_base.wld_baulasttraeger WHERE langtext = 'Rostocker Straßenbahn AG';
DELETE FROM ukos_base.wld_baulasttraeger WHERE langtext = 'Warnowquerung GmbH & Co. KG';
UPDATE ukos_base.wld_baulasttraeger SET sortierreihenfolge = '002' WHERE kurztext = 'B';
UPDATE ukos_base.wld_baulasttraeger SET sortierreihenfolge = '003' WHERE kurztext = 'L';
UPDATE ukos_base.wld_baulasttraeger SET sortierreihenfolge = '004' WHERE kurztext = 'K';
UPDATE ukos_base.wld_baulasttraeger SET sortierreihenfolge = '005' WHERE kurztext = 'G';
UPDATE ukos_base.wld_baulasttraeger SET langtext = 'privat', sortierreihenfolge = '006' WHERE langtext = 'Privat';
UPDATE ukos_base.wld_baulasttraeger SET sortierreihenfolge = '007' WHERE kurztext = 'WBV';
UPDATE ukos_base.wld_baulasttraeger SET kurztext = 'ZVG', langtext = 'Zweckverband Grevesmühlen', sortierreihenfolge = '008' WHERE langtext = 'ZVG';

UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'SV', langtext = 'SV', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '002' WHERE langtext = 'Bundesautobahn, Schnellstraße';
UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'I', langtext = 'I', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '003' WHERE langtext = 'Hauptverkehrsstraße, Bundesstraße';
UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'II', langtext = 'II', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '004' WHERE langtext = 'Wohnsammelstraße, Busverkehrsfläche';
UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'III', langtext = 'III', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '005' WHERE langtext = 'Fußgängerzone mit Ladeverkehr';
UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'IV', langtext = 'IV', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '006' WHERE langtext = 'Anliegerstraße';
UPDATE ukos_base.wld_bauklasse SET ident_hist = 'unbekannt', kurztext = 'V', langtext = 'V', bemerkung = 'Bauklasse nach RStO', sortierreihenfolge = '007' WHERE langtext = 'Fuß- und Radweg';
INSERT INTO ukos_base.wld_bauklasse VALUES ('94f38003-c084-4466-9569-9c35880cb47c', 'unbekannt', 'VI', 'VI', 'Bauklasse nach RStO', '008', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_bauklasse VALUES ('fd16ede6-0ef2-4e6b-9d18-fa174ff31c6c', 'unbekannt', 'SO', 'sonstige Bauklasse', 'Bauklasse nach RStO', '009', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');

DELETE FROM ukos_base.wld_nutzung WHERE langtext = 'Öffentliche Grünflächen';
DELETE FROM ukos_base.wld_nutzung WHERE langtext = 'Öffentliche Spielplätze';
UPDATE ukos_base.wld_nutzung SET kurztext = 'A', langtext = 'Bundesautobahn', sortierreihenfolge = '002' WHERE langtext = 'Autobahn';
UPDATE ukos_base.wld_nutzung SET kurztext = 'B', sortierreihenfolge = '003' WHERE langtext = 'Bundesstraße';
UPDATE ukos_base.wld_nutzung SET kurztext = 'L', sortierreihenfolge = '004' WHERE langtext = 'Landesstraße';
UPDATE ukos_base.wld_nutzung SET kurztext = 'K', sortierreihenfolge = '005' WHERE langtext = 'Kreisstraße';
UPDATE ukos_base.wld_nutzung SET kurztext = 'G', sortierreihenfolge = '006' WHERE langtext = 'Gemeindestraße';
UPDATE ukos_base.wld_nutzung SET kurztext = 'SÖ', langtext = 'sonstige öffentliche Straße', sortierreihenfolge = '007' WHERE langtext = 'Sonstige';
UPDATE ukos_base.wld_nutzung SET kurztext = 'N', langtext = 'nicht öffentliche Straße', sortierreihenfolge = '008' WHERE langtext = 'Privat';

UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AS 0', langtext = 'Autobahn kontinental', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '002' WHERE langtext = 'Anliegerstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AS I', langtext = 'Autobahn großräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '003' WHERE langtext = 'Hauptverkehrsstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AS II', langtext = 'Autobahn überregional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '004' WHERE langtext = 'Innerortsstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'LS I', langtext = 'Landstraße großräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '005' WHERE langtext = 'Verkehrsberuhigter Bereich';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'LS II', langtext = 'Landstraße überregional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '006' WHERE langtext = 'Befahrbarer Wanderweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'LS III', langtext = 'Landstraße regional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '007' WHERE langtext = 'Befahrbarer Wiesenweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'LS IV', langtext = 'Landstraße nahräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '008' WHERE langtext = 'Feldweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'LS V', langtext = 'Landstraße kleinräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '009' WHERE langtext = 'Gartenweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'VS II', langtext = 'anbaufreie Hauptverkehrsstraße überregional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '010' WHERE langtext = 'Waldweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'VS III', langtext = 'anbaufreie Hauptverkehrsstraße regional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '011' WHERE langtext = 'Zeltplatzweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'HS III', langtext = 'angebaute Hauptverkehrsstraße regional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '012' WHERE langtext = 'Wanderweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'HS IV', langtext = 'angebaute Hauptverkehrsstraße nahräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '013' WHERE langtext = 'Anliegererschließungsweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'ES IV', langtext = 'Erschließungsstraße nahräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '014' WHERE langtext = 'Seebrückenzugang';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'ES V', langtext = 'Erschließungsstraße kleinräumig/Anliegerstraße', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '015' WHERE langtext = 'Anliegerstraße/Feldweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AR II', langtext = 'Radweg außerhalb bebauter Gebiete überregional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '016' WHERE langtext = 'Wirtschaftsweg';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AR III', langtext = 'Radweg außerhalb bebauter Gebiete regional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '017' WHERE langtext = 'Strandzugang';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AR IV', langtext = 'Radweg außerhalb bebauter Gebiete nahräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '018' WHERE langtext = 'Verbindungsstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'IR II', langtext = 'Radweg innerhalb bebauter Gebiete überregional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '019' WHERE langtext = 'sonstige öffentliche Straße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'IR III', langtext = 'Radweg innerhalb bebauter Gebiete regional', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '020' WHERE langtext = 'Zubringerstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'IR IV', langtext = 'Radweg innerhalb bebauter Gebiete nahräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '021' WHERE langtext = 'Haupterschließungsstraße';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'IR V', langtext = 'Radweg innerhalb bebauter Gebiete kleinräumig', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '022' WHERE langtext = 'öffentlicher Parkplatz';
UPDATE ukos_base.wld_klassifizierung SET ident_hist = 'unbekannt', kurztext = 'AF', langtext = 'Fußgängerverkehrsanlage außerhalb bebauter Gebiete', bemerkung = 'Straßenklasse nach RIN', sortierreihenfolge = '023' WHERE langtext = 'selbständiger gemeinsamer Geh- und Radweg';
INSERT INTO ukos_base.wld_klassifizierung VALUES ('74d925b4-7283-4d48-a902-a75197188931', 'unbekannt', 'IF', 'Fußgängerverkehrsanlage innerhalb bebauter Gebiete', 'Straßenklasse nach RIN', '024', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_klassifizierung VALUES ('30eed0ec-3ea0-482d-b80d-8c2a4f445b23', 'unbekannt', 'WW', 'Wirtschaftsweg', 'Straßenklasse nach RIN', '025', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_klassifizierung VALUES ('8349063f-7f1c-495e-8f13-155e4868e4a6', 'unbekannt', 'PL', 'Platz', 'Straßenklasse nach RIN', '026', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');
INSERT INTO ukos_base.wld_klassifizierung VALUES ('c9f97347-fb1d-440c-8a5e-6ecc9a343e24', 'unbekannt', 'BS', 'Baustraße', 'Straßenklasse nach RIN', '027', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');

COMMIT;
