BEGIN;

  INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`, `editable`) VALUES
   ('MAILSMTPUSER', '', 'kvwmap', 'Nutzername für den Zugang zum SMTP-Server.\r\n', 'string', 'E-Mail Einstellungen', '', 0, 2),
   ('MAILSMTPPASSWORD', '', 'secret', 'Passwort für den Zugang zum SMTP-Server.\r\n', 'password', 'E-Mail Einstellungen', '', 0, 2),
   ('MAILREPLYADDRESS', '', 'no-reply@kvwmap.de', 'E-Mail-Adresse, die als Absender in von kvwmap versandten E-Mails angegeben werden soll.\r\n', 'string', 'E-Mail Einstellungen', '', 0, 2);

  UPDATE config SET type = 'password' WHERE name LIKE 'DHK_CALL_PASSWORD';

COMMIT;
