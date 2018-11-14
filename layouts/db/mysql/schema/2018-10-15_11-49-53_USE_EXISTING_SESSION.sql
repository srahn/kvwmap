BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('USE_EXISTING_SESSION', '', 'false', 'Wenn man auf einem Server mehrere kvwmap-Instanzen laufen hat und möchte, dass ein Nutzer sich nur einmal an einer Instanz anmelden muss, kann man diesen Parameter auf true setzen. Voraussetzung ist natürlich, dass die kvwmap-Instanzen die gleichen Nutzerdaten verwenden.', 'boolean', 'Administration', NULL, 0);

COMMIT;
