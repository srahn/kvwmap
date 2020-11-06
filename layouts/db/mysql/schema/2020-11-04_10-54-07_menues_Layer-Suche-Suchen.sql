BEGIN;

UPDATE `u_menues` 
SET onclick = CONCAT("overlay_link('", replace(`links`, 'index.php?', ''), "', true)") , links = "javascript:void(0)"
WHERE `links` like 'index.php?go=Layer-Suche_Suchen%';

COMMIT;
