BEGIN;

#  ALTER TABLE `datatypes` DROP FOREIGN KEY fk_datatypes_connection_id;
#  ALTER TABLE `datatypes` DROP COLUMN `connection_id`;
  ALTER TABLE `datatypes` ADD COLUMN `connection_id` bigint(20) UNSIGNED;
  ALTER TABLE `datatypes` ADD CONSTRAINT fk_datatypes_connection_id FOREIGN KEY (connection_id) REFERENCES connections(id);
  
  UPDATE
    `datatypes` dt,
    (
      SELECT
				id,
				host,
				port,
				dbname
			FROM
				`connections`, `layer`
			WHERE 
				`layer`.connection_id = `connections`.id
			GROUP BY
				host, port, dbname, password
			HAVING 
				count(layer.Layer_ID) > 5
    ) AS cn
  SET
    dt.connection_id = cn.id
  WHERE
    dt.host = cn.host AND
    dt.port = cn.port AND
    dt.dbname = cn.dbname;

COMMIT;