BEGIN;
ALTER TYPE xplan_gml.xp_wirksamkeitbedingung DROP ATTRIBUTE datumabsolut;
ALTER TYPE xplan_gml.xp_wirksamkeitbedingung DROP ATTRIBUTE datumrelativ;
ALTER TYPE xplan_gml.xp_wirksamkeitbedingung ADD ATTRIBUTE datumabsolut date;
ALTER TYPE xplan_gml.xp_wirksamkeitbedingung ADD ATTRIBUTE datumrelativ interval;
COMMIT;
