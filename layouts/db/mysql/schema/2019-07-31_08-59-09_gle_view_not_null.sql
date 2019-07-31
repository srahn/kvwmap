BEGIN;

UPDATE u_rolle2used_layer SET gle_view = 1 WHERE gle_view IS NULL;

ALTER TABLE `u_rolle2used_layer` CHANGE `gle_view` `gle_view` TINYINT(1) NOT NULL DEFAULT '1';

COMMIT;
