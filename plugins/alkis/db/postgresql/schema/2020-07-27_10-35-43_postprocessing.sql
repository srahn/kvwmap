BEGIN;

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (12, 1, 4, 0, 41002, 1740, 9999, 'FKT', 'LGT');

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (13, 0, 0, 0, 41003, 9999, 0, 'LGT', NULL);

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (15, 0, 0, 0, 41005, 9999, 0, 'AGT', NULL);

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (18, 0, 0, 0, 41008, 9999, 0, 'FKT', NULL);

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (22, 0, 0, 0, 42006, 9999, 0, 'FKT', NULL);

INSERT INTO alkis.n_nutzungsartenschluessel (nutzungsartengruppe, nutzungsart, untergliederung1, untergliederung2, objektart, werteart1, werteart2, attributart1, attributart2) VALUES (43, 0, 0, 0, 44006, 9999, 0, 'FKT', NULL);


INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (12, 1, 4, 0, 'Sonstiges');

INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (13, 0, 0, 0, 'Sonstiges');

INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (15, 0, 0, 0, 'Sonstiges');

INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (18, 0, 0, 0, 'Sonstiges');

INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (22, 0, 0, 0, 'Sonstiges');

INSERT INTO alkis.n_untergliederung2 (nutzungsartengruppe, nutzungsart, untergliederung1, schluessel, untergliederung2) VALUES (43, 0, 0, 0, 'Soll');

COMMIT;
