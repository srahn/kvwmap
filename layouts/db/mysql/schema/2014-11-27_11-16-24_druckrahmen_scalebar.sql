BEGIN;

ALTER TABLE  `druckrahmen` ADD  `scalebarposx` INT( 11 ) NULL AFTER  `scalesize` ,
ADD  `scalebarposy` INT( 11 ) NULL AFTER  `scalebarposx`;

COMMIT;
