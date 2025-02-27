BEGIN;

ALTER TABLE rolle_nachweise ENGINE=INNODB;
ALTER TABLE rolle_nachweise_dokumentauswahl ENGINE=INNODB;

DELETE rolle_nachweise, rolle FROM rolle_nachweise LEFT JOIN rolle ON rolle_nachweise.user_id = rolle.user_id AND rolle_nachweise.stelle_id = rolle.stelle_id WHERE rolle.user_id IS NULL AND rolle.stelle_id IS NULL;
ALTER TABLE rolle_nachweise ADD FOREIGN KEY (user_id, stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE;

DELETE rolle_nachweise_dokumentauswahl, rolle FROM rolle_nachweise_dokumentauswahl LEFT JOIN rolle ON rolle_nachweise_dokumentauswahl.user_id = rolle.user_id AND rolle_nachweise_dokumentauswahl.stelle_id = rolle.stelle_id WHERE rolle.user_id IS NULL AND rolle.stelle_id IS NULL;
ALTER TABLE rolle_nachweise_dokumentauswahl ADD FOREIGN KEY (user_id, stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE;

COMMIT;
