BEGIN;

ALTER TABLE `classes` ADD `legendimagewidth` INT(11) NULL AFTER `legendgraphic`, ADD `legendimageheight` INT(11) NULL AFTER `legendimagewidth`;

COMMIT;
