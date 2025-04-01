BEGIN;

ALTER TYPE xplan_gml.xp_verbundenerplan ADD ATTRIBUTE verbundenerplan text;
COMMENT ON TYPE xplan_gml.xp_verbundenerplan IS 'Alias: "XP_VerbundenerPlan",  [0..1],  [0..1], UML-Classifier: XP_RechtscharakterPlanaenderung Stereotyp: enumeration 1, Assoziation verbundenerPlan -> XP_Plan [0..1]';
COMMENT ON TYPE xplan_gml.bp_gruenflaeche IS 'Ableitung von Type BP_FestsetzungenBaugebiet integer maxzahlwohnungen integer 0..1';

COMMIT;
