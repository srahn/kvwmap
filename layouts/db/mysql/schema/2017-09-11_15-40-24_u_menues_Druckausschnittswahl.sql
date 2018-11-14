BEGIN;

UPDATE `u_menues` SET `links`='#', onclick='printMap();' where links = 'index.php?go=Druckausschnittswahl';

COMMIT;
