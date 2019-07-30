BEGIN;

UPDATE `u_menues` SET `links`='#', onclick='printMapFast();', target=NULL where links = 'index.php?go=Schnelle_Druckausgabe';

COMMIT;
