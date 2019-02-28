BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('PASSWORD_INFO', '', '', 'Hier kann ein Hinweistext eingetragen werden, welcher bei der Passwortvergabe erscheint.', 'string', 'Administration', NULL, 0);

COMMIT;
