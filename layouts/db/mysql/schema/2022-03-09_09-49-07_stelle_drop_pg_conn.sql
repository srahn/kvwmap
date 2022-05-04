BEGIN;

ALTER TABLE `stelle`
  DROP `pgdbhost`,
  DROP `pgdbname`,
  DROP `pgdbuser`,
  DROP `pgdbpasswd`,
	DROP `postgres_connection_id`;

COMMIT;
