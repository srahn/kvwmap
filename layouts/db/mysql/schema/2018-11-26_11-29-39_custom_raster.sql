BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('CUSTOM_RASTER', 'SHAPEPATH', 'custom_raster/', 'Das Verzeichnis, in dem die von den Nutzern hochgeladenen Rasterdateien abgelegt werden.', 'string', 'Pfadeinstellungen', NULL, 0);


COMMIT;
