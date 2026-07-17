BEGIN;

ALTER TABLE kvwmap.rolle ADD layer_selection integer NULL;

ALTER TABLE kvwmap.rolle ADD CONSTRAINT rolle_fk_saved_layers FOREIGN KEY (layer_selection) REFERENCES kvwmap.rolle_saved_layers(id) ON UPDATE CASCADE ON DELETE cascade;

ALTER TABLE kvwmap.rolle ADD layer_selection_mode int2 DEFAULT 0 NOT NULL;

COMMIT;
