BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('nachweis_unset_attributes','','[]','die Attribute, die nicht belegt sind und nicht geprüft werden sollen','array','nachweisverwaltung', 'Plugins/nachweisverwaltung', 0, 2);

COMMIT;
