BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('LOGIN_ROUTINE', 'SNIPPETS', '', 'hier kann eine PHP-Datei angegeben werden, welche beim Login-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 0),
('LOGOUT_ROUTINE', 'SNIPPETS', '', 'hier kann eine PHP-Datei angegeben werden, welche beim Logout-Vorgang ausgeführt wird', 'string', 'Administration', NULL, 0);

COMMIT;
