BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('ANTRAGSNUMMERMAXLENGTH','','10','Erlaubte maximale Länge der Antragsnummer in der Fachschale Nachweisverwaltung
','numeric','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('BLATTNUMMERMAXLENGTH','','4','Erlaubte maximale Länge der Blattnummer in der Fachschale Nachweisverwaltung
','numeric','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('KVZAUSGABEDATEINAME','','festpunkte.kvz','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('KVZKOPF','','# Datenaustauschformat Landkreis Rostock#KST PKN             VMA  RECHTSWERT   HOCHWERT    HOEHE    GST  VWL  DES  ART# ','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('NACHWEIS_PRIMARY_ATTRIBUTE','','stammnr','das primäre Ordnungskriterium der Nachweisverwaltung: rissnummer/stammnr
','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('NACHWEIS_SECONDARY_ATTRIBUTE','','fortfuehrung','das zusätzliche Ordnungskriterium der Nachweisverwaltung (kann bei eindeutigem primärem leer gelassen werden): fortfuehrung
','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('nachweis_unique_attributes','','[
    "gemarkung",
    "flur",
    "rissnummer",
    "art",
    "blattnr"
]','die Attribute, die einen Nachweis eindeutig identifizieren
','array','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('NACHWEISDOCPATH','SHAPEPATH','nachweise/','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('PUNKTDATEIARCHIVPATH','PUNKTDATEIPATH','archiv/','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('PUNKTDATEIPATH','SHAPEPATH','festpunkte/','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('RECHERCHEERGEBNIS_PATH','SHAPEPATH','recherchierte_antraege/','Pfad zum Speichern der Nachweisrecherche
','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('RISSNUMMERMAXLENGTH','','20','Erlaubte maximale Länge der Rissnummer in der Fachschale Nachweisverwaltung
','numeric','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('SKIZZEN_DATEI_TYP','','tif','','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('LAYER_ID_NACHWEISE','','786','','numeric','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('LAYER_ID_NACHWEISE','','786','','numeric','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('ZUSATZ_UEBERGABEPROTOKOLL','','Die aufgeführte Attributierung ist als vorläufig zu betrachten.','Hier kann ein zusätzlicher Text definiert werden, der im Übergabeprotokoll unterhalb des Titels erscheint.','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('NACHWEISE_EMAIL','','test@test.com','Hier kann die Email-Adresse angegeben werden, an die die Emails mit den Bearbeitungshinweisen gesendet werden.','string','Plugins/nachweisverwaltung','nachweisverwaltung',1, 2);
		

COMMIT;
