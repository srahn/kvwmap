BEGIN;

  # Create a new constant for postgres connection id and set the value from config pg connections settings
	INSERT INTO `config` (
    `name`,
    `prefix`,
    `value`,
    `description`,
    `type`,
    `group`,
    `saved`,
    `editable`
  ) 
      SELECT
        'POSTGRES_CONNECTION_ID',
				'',
        id
        ,
				'ID der Postgresql-Datenbankverbindung aus Tabelle connections',
				'numeric',
				'Datenbanken',
				0,
				2
      FROM
        connections c
      WHERE
        c.host     = (SELECT CAST(value AS CHAR(50)) FROM config WHERE name = 'POSTGRES_HOST') AND
        c.dbname   = (SELECT value FROM config WHERE name = 'POSTGRES_DBNAME') AND
        c.user     = (SELECT value FROM config WHERE name = 'POSTGRES_USER') AND
        c.password = (SELECT value FROM config WHERE name = 'POSTGRES_PASSWORD');

  # Noch nicht löschen weil das von Scripten verwendet wird, die nicht zu kvwmap gehören und noch nicht umgestellt sind.
  #DELETE FROM `config` WHERE name IN ('POSTGRES_HOST', 'POSTGRES_DBNAME', 'POSTGRES_USER', 'POSTGRES_PASSWORD');

  # Create a postgres_connection_id for stellen and set from connections table
  ALTER TABLE stelle ADD COLUMN postgres_connection_id int(11);

  INSERT INTO connections(
		name,
    host,
    port,
    dbname,
    user,
    password
  )
  SELECT
			CONCAT(pgdbname, '_', s.ID),
      pgdbhost,
      5432,
      pgdbname,
      pgdbuser,
      pgdbpasswd
    FROM
      stelle s LEFT JOIN
      connections c ON (
        c.host = CAST(s.pgdbhost AS char(50)) AND
        c.dbname = s.pgdbname AND
        c.user = s.pgdbuser AND
        c.password = s.pgdbpasswd
      )
    WHERE
      NOT (NULLIF(s.pgdbhost, '') IS NULL OR NULLIF(s.pgdbname, '') IS NULL OR NULLIF(s.pgdbuser, '') IS NULL OR NULLIF(s.pgdbpasswd, '') IS NULL) AND
      c.id IS NULL
		GROUP BY
			pgdbhost,
      pgdbname,
      pgdbuser,
      pgdbpasswd;

  UPDATE
    stelle s JOIN
    connections c ON (
      c.host = CAST(s.pgdbhost AS CHAR(50)) AND
      c.dbname = s.pgdbname AND
      c.user = s.pgdbuser AND
      c.password = s.pgdbpasswd
    )
  SET
    postgres_connection_id = c.id;

  #ALTER TABLE stelle
  #  DROP COLUMN pgdbhost,
  #  DROP COLUMN pgdbname,
  #  DROP COLUMN pgdbuser,
  #  DROP COLUMN pgdbpasswd;

COMMIT;
