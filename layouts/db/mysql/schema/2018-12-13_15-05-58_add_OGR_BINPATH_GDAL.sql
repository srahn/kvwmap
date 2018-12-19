BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('OGR_BINPATH_GDAL', '', '/usr/local/gdal/bin/', 'Wenn man dem ogr oder gdal Befehl docker exec gdal voranstellt, wird das ogr bzw. gdal in dem gdal Container verwendet statt des ogr bzw. gdal im Web Container. Diese Konstante gibt an wo sich das Bin-Verzeichnis innerhalb des verwendeten GDAL-Containers befindet.', 'string', 'Pfadeinstellungen', NULL, 0);

DELETE FROM `config` WHERE `name` = 'XPLANKONVERTER_XP_BEREICHE_LAYER_ID';
DELETE FROM `config` WHERE `name` = 'XPLANKONVERTER_XP_PLAENE_LAYER_ID';

COMMIT;
