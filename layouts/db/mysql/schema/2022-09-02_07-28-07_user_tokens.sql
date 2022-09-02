BEGIN;

ALTER TABLE `user` CHANGE `tokens` `tokens` TEXT NULL; 

COMMIT;
