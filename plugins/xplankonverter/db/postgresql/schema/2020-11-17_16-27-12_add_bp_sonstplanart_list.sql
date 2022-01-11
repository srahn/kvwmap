BEGIN;
TRUNCATE xplan_gml.bp_sonstplanart;
INSERT INTO xplan_gml.bp_sonstplanart (codespace,id,value)
VALUES
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','1000','Sanierungssatzung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','2000','Erhaltungssatzung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','3000','Stadtumbaugebietssatzung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','4000','sonstige Bausatzung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','5000','städtebauliche Entwicklungssatzung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','6000','städtebauliches Entwicklungskonzept'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','7000','Bebauungsplan der Innenentwicklung'),
('http://bauleitplaene-mv.de/codelist/BP_SonstPlanArt/BP_SonstPlanArt.xml','8000','Vorhaben- und Erschließungsplan');
COMMIT;