BEGIN;

ALTER TABLE  `druckrahmen` ADD  `lageposx` INT( 11 ) NULL AFTER  `oscalesize` ,
ADD  `lageposy` INT( 11 ) NULL AFTER  `lageposx` ,
ADD  `lagesize` INT( 11 ) NULL AFTER  `lageposy` ,
ADD  `gemeindeposx` INT( 11 ) NULL AFTER  `lagesize` ,
ADD  `gemeindeposy` INT( 11 ) NULL AFTER  `gemeindeposx` ,
ADD  `gemeindesize` INT( 11 ) NULL AFTER  `gemeindeposy` ,
ADD  `flurstposx` INT( 11 ) NULL AFTER  `flursize` ,
ADD  `flurstposy` INT( 11 ) NULL AFTER  `flurstposx` ,
ADD  `flurstsize` INT( 11 ) NULL AFTER  `flurstposy` ,
ADD  `font_lage` varchar( 255 ) NULL AFTER  `font_scale` ,
ADD  `font_gemeinde` varchar( 255 ) NULL AFTER  `font_lage` ,
ADD  `font_flurst` varchar( 255 ) NULL AFTER  `font_flur`;

COMMIT;
