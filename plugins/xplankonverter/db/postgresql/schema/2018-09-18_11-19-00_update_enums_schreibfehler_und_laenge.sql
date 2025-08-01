BEGIN;
-- Kuerzung von Beschreibung für Auswahlliste
UPDATE xplan_gml.enum_bp_rechtsstand
SET beschreibung = 'Die frühzeitige Beteiligung bzw. Unterrichtung der Öffentlichkeit (§ 3 Abs. 1 BauGB bzw. § 13a Abs. 3 BauGB) hat stattgefunden.'
WHERE wert = 2200;

-- Grossschreibung Verbesserung
UPDATE xplan_gml.enum_bp_rechtsstand
SET beschreibung = 'Der Plan ist in Kraft getreten'
WHERE wert = 4000;

-- fehlendes g
UPDATE xplan_gml.enum_fp_rechtsstand
SET beschreibung = 'Die frühzeitige Beteiligung der Öffentlichkeit ist abgeschlossen.'
WHERE wert = 2200;

UPDATE xplan_gml.enum_fp_planart
SET beschreibung = 'Regionaler Flächennutzungsplan, Funktion eines Regionalplans und gemeinsamen Flächennutzungsplans nach $ 204 BauGB.'
WHERE wert = 3000;

COMMIT;