BEGIN;

UPDATE `u_menues` 
SET links = "javascript:void(0)", onclick = "overlay_link('go=get_last_query', true)"
WHERE `links` = 'index.php?go=get_last_query';

COMMIT;
