BEGIN;

  INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`, `editable`) VALUES
   ('MAILCOPYATTACHMENT', '', 'true', 'Sollen Dateien in E-Mail-Anhängen beim Versenden in den Archiv-Ordner kopiert (true) oder verschoben (false) werden.\r\n', 'string', 'E-Mail Einstellungen', '', 0, 2);

COMMIT;
