BEGIN;

INSERT INTO `config` (
  `name`, 
  `prefix`, 
  `value`, 
  `description`, 
  `type`, 
  `group`, 
  `plugin`, 
  `saved`, 
  `editable`
) VALUES (
  'TXT_SCALEBAR', 
  '', 
  '#000000', 
  'Schriftfarbe der Maßstabsleiste', 
  'string', 
  'Layout', 
  '', 
  0, 
  2);

COMMIT;
