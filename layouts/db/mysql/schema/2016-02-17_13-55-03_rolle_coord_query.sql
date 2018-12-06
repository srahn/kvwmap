BEGIN;

ALTER TABLE `rolle` CHANGE `buttons` `buttons` VARCHAR( 255 ) NULL DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure';

UPDATE `rolle` SET buttons = concat(buttons, ',coord_query') WHERE buttons like '%jumpto%';

COMMIT;
