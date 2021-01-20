BEGIN;

UPDATE `layer` SET template = REPLACE(template, 'Flurstuecke_customALKIS.php', '../../plugins/alkis/view/Flurstuecke_customALKIS.php');

UPDATE `used_layer` SET template = REPLACE(template, 'Flurstuecke_customALKIS.php', '../../plugins/alkis/view/Flurstuecke_customALKIS.php');

COMMIT;
