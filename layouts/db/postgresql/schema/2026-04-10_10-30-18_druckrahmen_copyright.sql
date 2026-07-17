BEGIN;

ALTER TABLE kvwmap.druckrahmen ADD watermarkcolor int2 NULL;

ALTER TABLE kvwmap.druckrahmen ADD copyrightcolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD copyrightposx int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD copyrightposy int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD copyrightwidth int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD copyrightsize int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD copyrighttransparency int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD font_copyright varchar(255) DEFAULT NULL::character varying NULL;

COMMIT;
