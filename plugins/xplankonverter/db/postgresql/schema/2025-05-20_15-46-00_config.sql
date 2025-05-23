BEGIN;

INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FILE_PATH','SHAPEPATH','xplankonverter/','','string','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID','','2','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_SHAPEFILES_LAYER_ID','','247','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_BP_PLAENE_LAYER_ID','','3','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FP_PLAENE_LAYER_ID','','235','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_SO_PLAENE_LAYER_ID','','242','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_RP_PLAENE_LAYER_ID','','200','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_XP_PLAENE_LAYER_ID','','0','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_BP_BEREICHE_LAYER_ID','','233','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FP_BEREICHE_LAYER_ID','','236','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_SO_BEREICHE_LAYER_ID','','241','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_RP_BEREICHE_LAYER_ID','','239','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_XP_BEREICHE_LAYER_ID','','0','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_REGELN_LAYER_ID','','246','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_VALIDIERUNGSERGEBNISSE_LAYER_ID','','249','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('GML_LAYER_TEMPLATE_GROUP','','10004','','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_CONTENT_SCHEMA','','xplan_gml','','string','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_INSPIRE_KONVERTER','','true','','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLAN_NS_PREFIX','','xplan','Konstanten fuer GML-Builder
XML-namespace
','string','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLAN_NS_URI','','http://www.xplanung.de/xplangml/5/4','','string','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLAN_NS_SCHEMA_LOCATION','','http://www.xplanungwiki.de/upload/XPlanGML/5.4/Schema/XPlanung-Operationen.xsd','','string','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLAN_MAX_NESTING_DEPTH','','3','max Rekursionstiefe für Nested Composite Types
','numeric','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_CREATE_UPLOAD_SHAPE_LAYER','','false','Erzeugt Layer für hochgeladene Shape-Dateien.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_ENABLE_PUBLISH','','true','Stellt ein ob die Pläne über eine extra Schaltfläche für die Sichtbarkeit in Diensten freigeschaltet werden sollen.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_XPLANVALIDATOR_SEMANTIC_REPORT_LAYER_ID','','4910','ID des Layers, der die Berichte über die semantische Prüfung des XPlanValidators der Leitstelle beinhaltet.','integer','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_DEFAULT_EPSG','','25832','Bevorzugter EPSG-Code für XPlanGML.','integer','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FUNC_VALIDATOR','','0','Funktionsbutton zum Aufruf des XPlanValidators der Leitstelle in der Planliste anzeigen.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FUNC_SERVICE','','0','Funktionsbutton zum Erzeugen eines Dienstes zum Plan in der Planliste anzeigen.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_FUNC_INSPIRE','','0','Funktionsbutton zum Erzeugen einer INSPIRE-GML-Datei in der Planliste anzeigen.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_XPLANVALIDATOR_REPORT_LAYER_ID','','4909','ID des Layers, der die Berichte des XPlanValidators der Leitstelle beinhaltet.','integer','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_SEND_NOTIFICATION','','true','Standardwert für das Versenden von Benachrichtigungen im Fehlerfall. Sendet E-Mail-Benachrichtigung wenn nicht anders beim Aufruf der Funktion send_error angegeben.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_CREATE_TICKET','','true','Standardwert für das Erzeugen eines Tickets im Fehlerfall. Erzeugt Ticket wenn nicht anders beim Aufruf der Funktion send_error angegeben.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);
			
INSERT INTO kvwmap.config (name, prefix, value, description, type, plugin, "group", saved, editable) VALUES ('XPLANKONVERTER_CREATE_SERVICE','','true','Erzeugt oder aktualisiert GeoWeb-Dienst und Metadaten nach Upload von XPlanGml.','boolean','Plugins/xplankonverter','xplankonverter',1, 2);

COMMIT;
