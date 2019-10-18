BEGIN;

ALTER TABLE `layer` ADD `oid` varchar(63) NOT NULL DEFAULT 'oid' AFTER `maintable`;

COMMIT;
