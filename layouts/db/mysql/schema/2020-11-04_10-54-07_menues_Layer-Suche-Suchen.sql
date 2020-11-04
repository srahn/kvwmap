BEGIN;

UPDATE `u_menues` 
SET links = "javascript:void(0)", onclick = CONCAT("overlay_link('", replace(`links`, 'index.php?', ''), "', true)") 
WHERE `links` like 'index.php?go=Layer-Suche_Suchen%';

COMMIT;
