BEGIN;

	ALTER TABLE `rolle` CHANGE `buttons` `buttons` VARCHAR(255) NULL DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,punktfang';
	UPDATE `rolle` SET `buttons` = concat(`buttons`, CASE WHEN substr(trim(`buttons`), -1) = ',' THEN '' ELSE ',' END,  'punktfang') WHERE `buttons` NOT LIKE '%punktfang%';

COMMIT;
