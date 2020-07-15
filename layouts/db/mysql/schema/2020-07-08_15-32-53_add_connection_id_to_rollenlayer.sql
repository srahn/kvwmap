BEGIN;

  ALTER TABLE `layer` ENGINE=INNODB;
  ALTER TABLE `layer` ADD CONSTRAINT fk_layer_connection_id FOREIGN KEY (connection_id) REFERENCES connections(id);


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
		coalesce(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(connection))), ' ', 1), '=', -1), ''), 5432) AS `port`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('dbname', LOWER(connection))), ' ', 1), '=', -1) AS `dbname`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('user', LOWER(connection))), ' ', 1), '=', -1) AS `user`,
    SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('password', LOWER(connection))), ' ', 1), '=', -1) AS `password`
  FROM
    `rollenlayer`
  WHERE
    connectiontype = 6
  GROUP BY host, port, dbname, `user`, `password`;
  
  ALTER TABLE `rollenlayer` ADD COLUMN `connection_id` bigint(20) UNSIGNED AFTER `connection`;

  UPDATE
    `rollenlayer` AS l JOIN
    `connections` c ON (
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('host', LOWER(l.connection))), ' ', 1), '=', -1)  = c.`host` AND
      coalesce(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('port', LOWER(connection))), ' ', 1), '=', -1), ''), 5432) = c.`port` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('dbname', LOWER(l.connection))), ' ', 1), '=', -1) = c.`dbname` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('user', LOWER(l.connection))), ' ', 1), '=', -1) = c.`user` AND
      SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING(connection, LOCATE('password', LOWER(l.connection))), ' ', 1), '=', -1) = c.`password`
    )
  SET
    l.connection_id = c.id
  WHERE
    l.connectiontype = 6;

  ALTER TABLE `rollenlayer` ADD CONSTRAINT fk_rollen_layer_connection_id FOREIGN KEY (connection_id) REFERENCES connections(id);

COMMIT;
