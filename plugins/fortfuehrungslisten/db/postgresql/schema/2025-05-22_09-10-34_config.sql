BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, "group", plugin, saved, editable) VALUES
('LAYER_ID_FF_AUFTRAG', '', '782', '', 'numeric', 'Plugins/fortfuehrungslisten', 'fortfuehrungslisten', 0, 2);

COMMIT;
