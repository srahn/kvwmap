BEGIN;

ALTER TABLE alkis.n_nutzungsartenschluessel ADD COLUMN attributart1 character(3);
ALTER TABLE alkis.n_nutzungsartenschluessel ADD COLUMN attributart2 character(3);

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe = 12 AND werteart1 > 0;
UPDATE alkis.n_nutzungsartenschluessel SET attributart2 = 'LGT' WHERE nutzungsartengruppe::text||nutzungsart::text||untergliederung1::text||untergliederung2::text between '12141' AND '12148';
UPDATE alkis.n_nutzungsartenschluessel SET attributart2 = 'FGT' WHERE nutzungsartengruppe::text||nutzungsart::text||untergliederung1::text||untergliederung2::text between '12311' AND '12315';

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'LGT' WHERE nutzungsartengruppe = 13 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'AGT' WHERE nutzungsartengruppe = 14 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'AGT' WHERE nutzungsartengruppe = 15 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe IN (16, 17, 18, 19, 21, 22, 23, 41, 42, 43, 44) AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe = 24 AND untergliederung1 = 0 AND werteart1 > 0;
UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'BKT' WHERE nutzungsartengruppe = 24 AND untergliederung1 > 0 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe = 25 AND untergliederung1 = 0 AND werteart1 > 0;
UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'ART' WHERE nutzungsartengruppe = 25 AND untergliederung1 > 0 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe = 26 AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'VEG' WHERE nutzungsartengruppe IN (31, 32, 33) AND werteart1 > 0;

UPDATE alkis.n_nutzungsartenschluessel SET attributart1 = 'FKT' WHERE nutzungsartengruppe = 37 AND werteart1 > 0;
UPDATE alkis.n_nutzungsartenschluessel SET attributart2 = 'OFM' WHERE nutzungsartengruppe = 37 AND werteart2 > 0;


COMMIT;
