BEGIN;

UPDATE ukos_base.wld_klassifizierung SET sortierreihenfolge = '028' WHERE langtext = 'Baustraße';
INSERT INTO ukos_base.wld_klassifizierung VALUES ('213cccfc-c668-4405-9c3c-3043c4bf818f', 'unbekannt', 'PP', 'Parkplatz', 'Straßenklasse nach RIN', '027', '2019-04-27 12:09:35.655799+02', '2100-01-01 02:00:00+01', '2019-04-27 12:09:35.655799+02', 'unbekannt', '2019-04-27 12:09:35.655799+02', 'unbekannt');


COMMIT;
