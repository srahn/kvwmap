BEGIN;

  DROP TABLE IF EXISTS `sicherungsinhalte`;
  DROP TABLE IF EXISTS `sicherungen`;
  DROP TABLE IF EXISTS `connections`;

  CREATE TABLE `connections` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Eindeutige Id der Datenbankverbindungen',
    `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'kvwmapsp' UNIQUE COMMENT 'Name der Datenbankverbindung. Kann frei gewählt werden, muss eindeutig sein und Wird in Auswahlliste für Layer angezeigt.',
    `host` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'pgsql' COMMENT 'Hostname der Datenbank. Default ist pgsql wenn der Zugriff aus dem Web-Container heraus erfolgt, sonst auch die IP-Adresse oder Hostname des Datenbankservers oder Docker-Containers in dem der Server läuft. Kann auch als Befehl aufgeführt werden, z.b. $(docker inspect --format \'{{ .NetworkSettings.IPAddress }}\' mysql-server). Wird ein leer-String eingetragen wird vom Postgres-Client localhost verwendet.',
    `port` integer DEFAULT 5432 COMMENT 'Die Portnummer mit der die Verbindung zur Datenbank hergestellt werden soll. Default ist 5432. Wird ein leerer Text angegeben, verwendet der Datenbankclient 5432.',
    `dbname` varchar(150) NOT NULL DEFAULT 'kvwmapsp' COMMENT 'Der Name der Datenbank zu der die Verbindung hergestellt werden soll.',
    `user` varchar(150) DEFAULT 'kvwmap' COMMENT 'Der Name des Nutzers mit dem die Verbindung zur Datenbank hergestellt werden soll. Default ist kvwmap. Wird ein leerer Text angegeben verwendet der Datenbankclient den Namen des Nutzers des Betriebssystems, welcher den Datenbankclient aufruft.',
    `password` varchar(150) DEFAULT 'KvwMapPW1' COMMENT 'Das Passwort des Datenbanknutzers. Wird hier ein leerer Text angegeben, wird die Option für das Passwort im Datenbankclient weggelassen. Der Datenbankclient versucht dadurch, wenn ein Passwort erforderlich ist das Passwort aus der Umgebungsvariable PGPASSWORD auszulesen. Steht dort nichts drin, versucht der Client das Passwort aus der Datei, die in der Umgebungsvariable PGPASSFILE angegeben ist auszulesen. Ist das Passwort auch dort nicht zu finden, versucht der Client das Passwort aus der Datei ~/.pgpass auszulesen. Ist auch dort nichts passendes zu Host, Datenbankname, Port und Nutzer zu finden, kann keine Verbindung hergestellt werden.'
  );

  INSERT INTO `connections` (`id`, `name`, `host`, `port`, `dbname`, `user`, `password`) VALUES
    (NULL, 'kvwmapsp', 'pgsql', 5432, 'kvwmapsp', 'kvwmap', 'KvwMapPW1');

  SET @row_nr := 1;

  INSERT INTO `connections` (`name`, `host`, `port`, `dbname`, `user`, `password`)
  SELECT
    concat(
      CASE WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('host', LOWER(connection))), ' ', 1), '=', -1) LIKE '' THEN '' ELSE CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('host', LOWER(connection))), ' ', 1), '=', -1), ':') END,
    	CASE WHEN SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(connection))), ' ', 1), '=', -1) LIKE '' THEN '' ELSE CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(connection))), ' ', 1), '=', -1) , ':') END,
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('dbname', LOWER(connection))), ' ', 1), '=', -1), ':',
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('user', LOWER(connection))), ' ', 1), '=', -1), ':',
      @row_nr := @row_nr + 1
    ) AS `name`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('host', LOWER(connection))), ' ', 1), '=', -1) AS `host`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(connection))), ' ', 1), '=', -1) AS `port`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('dbname', LOWER(connection))), ' ', 1), '=', -1) AS `dbname`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('user', LOWER(connection))), ' ', 1), '=', -1) AS `user`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('password', LOWER(connection))), ' ', 1), '=', -1) AS `password`
  FROM
    `layer`
  WHERE
    connectiontype = 6
  GROUP BY host, port, dbname, `user`, `password`;
  
  ALTER TABLE `layer` ADD COLUMN `connection_id` bigint(20) UNSIGNED;

  UPDATE
    `layer` AS l JOIN
    `connections` c ON (
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('host', LOWER(l.connection))), ' ', 1), '=', -1)  = c.`host` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(l.connection))), ' ', 1), '=', -1) = c.`port` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('dbname', LOWER(l.connection))), ' ', 1), '=', -1) = c.`dbname` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('user', LOWER(l.connection))), ' ', 1), '=', -1) = c.`user` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('password', LOWER(l.connection))), ' ', 1), '=', -1) = c.`password`
    )
  SET
    l.connection_id = c.id
  WHERE
    l.connectiontype = 6;

  #ALTER TABLE `layer` ADD CONSTRAINT fk_connection_id FOREIGN KEY (connection_id) REFERENCES connections(id);

  CREATE TABLE `sicherungen` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Eindeutige Id der Sicherungen',
    `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Tagessicherung' COMMENT 'Name der Sicherung. Wird verwendet als Name des Sicherungsscrptes. Darf keine Leer- und Sonderzeichen beinhalten. Muss sich unterscheiden von anderen.',
    `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
    `intervall` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0 1 * * *' COMMENT 'Wann die Sicherung ausgeführt werden soll.',
    `target_dir` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/var/www/backups/$day'
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Durchzuführende Sicherungen';

  INSERT INTO `sicherungen` (`id`, `name`, `beschreibung`, `intervall`, `target_dir`) VALUES
    (1, 'Tagessicherung', 'Wird jeden Tag um 01:00 Uhr ausgeführt.', '0 1 * * *', 'day/$(date +%u)'),
    (2, 'Wochensicherung', 'Wird jede Woche am Sonntag um 02:00 Uhr ausgeführt.', '0 2 * * 0', 'week/$((($(date +%-d)-1)/7+1))'),
    (3, 'Monatssicherung', 'Wird jeden 1. des Monats um 03:00 ausgeführt.', '0 3 1 * *', 'month/$(date +%m)');

  CREATE TABLE `sicherungsinhalte` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Eindeutige Id der Sicherungsinhalte',
    `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name der Sicherungsinhalte',
    `beschreibung` text COLLATE utf8_unicode_ci COMMENT 'Beschreibung der Sicherungsinhalte',
    `methode` enum('Verzeichnissicherung','Verzeichnisinhalte kopieren','Datei kopieren','Postgres Dump','Mysql Dump') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Verzeichnissicherung' COMMENT 'Methode der Sicherung',
    `source` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/var/www/apps/kvwmap' COMMENT 'Quelle des Sicherungsinhaltes. Bei Datenbanksicherungen der Name der Datenbank, sonst das Verzeichnis oder Dateiname mit Verzeichnisangabe.',
    `connection_id` bigint(20) UNSIGNED COMMENT 'Die ID der Datenbankverbindung, die für den Zugriff auf die Datenbank beim Dump verwendet werden soll.',
    `target` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'kvwmap' NOT NULL COMMENT 'Ziel der Sicherung. Ist immer ein Dateiname mit Verzeichnisangabe.',
    `overwrite` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Ob das Ziel überschrieben werden soll wenn es existiert oder nicht.',
    `sicherung_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID der Sicherung in der der Inhalt gesichert werden soll.',
    FOREIGN KEY fk_sicherung_id (`sicherung_id`) REFERENCES `sicherungen` (`id`),
    FOREIGN KEY fk_connection_id (`connection_id`) REFERENCES `connections` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

  INSERT INTO `sicherungsinhalte` (`id`, `name`, `beschreibung`, `methode`, `source`, `connection_id`, `target`, `overwrite`, `sicherung_id`) VALUES
  (1, 'apps', 'Alles in Apps packen und zippen.', 'Verzeichnissicherung', '/var/www/apps', NULL, 'apps', 1, 1),
  (2, 'data', 'Alles im Datenverzeichnis packen und zippen.', 'Verzeichnissicherung', '/var/www/data', NULL, 'data', 1, 1),
  (3, 'kvwmapdb', 'Sicherung der Kartendatenbank kvwmapdb.', 'Mysql Dump', 'kvwmapdb', NULL, 'kvwmapdb.sql', 1, 1),
  (4, 'kvwmapsp', 'Sicherung der Geodatenbank kvwmapsp.', 'Postgres Dump', 'kvwmapsp', (SELECT id FROM connections WHERE dbname LIKE 'kvwmapsp' LIMIT 1), 'kvwmapsp.dump', 1, 1),
  (5, 'Wochensicherung', 'Alle Inhalte der letzten Tagessicherung als Wochensicherung sichern.', 'Verzeichnisinhalte kopieren', 'day/5', '/var/www/backups/week/$((($(date +%-d)-1)/7+1))', 1, 2),
  (6, 'Monatssicherung', 'Alle Inhalte der letzten Tagessicherung als Monatssicherung sichern.', 'Verzeichnisinhalte kopieren', 'day/5', '/var/www/backups/month/$(date +%m)', 1, 3);

COMMIT;
