BEGIN;

SET GLOBAL innodb_flush_log_at_trx_commit = 2;

COMMIT;
