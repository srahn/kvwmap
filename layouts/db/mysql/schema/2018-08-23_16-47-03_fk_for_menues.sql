BEGIN;

ALTER TABLE u_menues ENGINE=INNODB;
ALTER TABLE u_menue2stelle ENGINE=INNODB;
ALTER TABLE stelle ENGINE=INNODB;

DELETE u_menue2stelle, stelle FROM u_menue2stelle LEFT JOIN stelle ON u_menue2stelle.stelle_id = stelle.ID WHERE stelle.ID IS NULL;
DELETE u_menue2stelle, u_menues FROM u_menue2stelle LEFT JOIN u_menues ON u_menue2stelle.menue_id = u_menues.id WHERE u_menues.id IS NULL;
ALTER TABLE u_menue2stelle ADD FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE;
ALTER TABLE u_menue2stelle ADD FOREIGN KEY (menue_id) REFERENCES u_menues (id) ON DELETE CASCADE;

COMMIT;
