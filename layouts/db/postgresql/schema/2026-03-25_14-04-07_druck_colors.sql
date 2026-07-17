BEGIN;

ALTER TABLE kvwmap.druckrahmen ADD lagecolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD gemeindecolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD gemarkungcolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD flurcolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD flurstcolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD datecolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD usercolor int2 NULL;
ALTER TABLE kvwmap.druckrahmen ADD scalecolor int2 NULL;

ALTER TABLE kvwmap.druckfreitexte ADD color int2 NULL;

COMMIT;
