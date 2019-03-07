BEGIN;

UPDATE `u_menues` SET `links`= replace(`links`, 'Stelle Wählen', 'Stelle_waehlen');

COMMIT;
