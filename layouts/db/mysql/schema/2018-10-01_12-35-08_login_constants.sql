BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('LOGIN_AGREEMENT', '', 'login_agreement.php', 'PHP-Seite, welche die Agreement-Message anzeigt', 'string', 'Layouts', NULL, 0),
('LOGIN_NEW_PASSWORD', '', 'login_new_password.php', 'PHP-Seite, auf der man ein neues Passwort vergeben kann', 'string', 'Layouts', NULL, 0),
('LOGIN_REGISTRATION', '', 'login_registration.php', 'PHP-Seite, auf der man sich registrieren kann', 'string', 'Layouts', NULL, 0);

COMMIT;
