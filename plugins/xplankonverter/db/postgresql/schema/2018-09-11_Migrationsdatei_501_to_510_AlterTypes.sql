-- exec statements separated
-- CR 13
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '140012' AFTER '140011';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '140013' AFTER '140012';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '16002' AFTER '16001';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3000' AFTER '16002';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3100' AFTER '3000';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3200' AFTER '3100';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3300' AFTER '3200';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3400' AFTER '3300';
ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3500' AFTER '3400';

--CR 14
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '1065' AFTER '1060';
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2600' AFTER '2500';

-- CR 15
ALTER TYPE xplan_gml.bp_verfahren ADD VALUE '4000' AFTER '3000';

-- CR 22
ALTER TYPE xplan_gml.rp_zentralerortsonstigetypen ADD VALUE '2200' AFTER '2101';
ALTER TYPE xplan_gml.rp_bodenschutztypen ADD VALUE '4000' AFTER '3000';
ALTER TYPE xplan_gml.rp_sonstverkehrtypen ADD VALUE '2001' AFTER '2000';

-- CR 24
ALTER TYPE xplan_gml.xp_arthoehenbezug ADD VALUE '1100' AFTER '1000';
ALTER TYPE xplan_gml.xp_arthoehenbezug ADD VALUE '1200' AFTER '1100';

-- CR 26
ALTER TYPE xplan_gml.rp_funktionszuweisungentypen ADD VALUE '9000' AFTER '8000';
ALTER TYPE xplan_gml.rp_lufttypen RENAME TO rp_klimaschutztypen;
ALTER TABLE xplan_gml.rp_klimaschutz ALTER COLUMN typ TYPE xplan_gml.rp_klimaschutztypen;
ALTER TYPE xplan_gml.rp_funktionszuweisungentypen ADD VALUE '3000' AFTER '2000';
ALTER TYPE xplan_gml.rp_erholungtypen ADD VALUE '2001' AFTER '2000';
ALTER TYPE xplan_gml.rp_erholungtypen ADD VALUE '3001' AFTER '3000';
ALTER TYPE xplan_gml.rp_energieversorgungtypen ADD VALUE '8000' AFTER '7000';
ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7200' AFTER '7100';
ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7300' AFTER '7200';
ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7400' AFTER '7300';
ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7500' AFTER '7400';

-- CR 30
ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '6500' AFTER '6000';

-- CR 33
ALTER TYPE xplan_gml.bp_wegerrechttypen ADD VALUE '2500' AFTER '2000';

-- CR 36
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3000' AFTER '2600';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3100' AFTER '3000';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3200' AFTER '3100';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3300' AFTER '3200';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3400' AFTER '3300';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '3500' AFTER '3400';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '1560' AFTER '1550';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '2500' AFTER '2400';
ALTER TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr ADD VALUE '2600' AFTER '2500';

-- CR 37
ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4100' AFTER '9999';
ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4200' AFTER '4100';
ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4300' AFTER '4300';

-- CR 38
ALTER TYPE xplan_gml.bp_zweckbestimmungnebenanlagen ADD VALUE '3700' AFTER '3600';

-- CR 42
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2700' AFTER '2600';
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2800' AFTER '2700';

-- CR 51
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2000' AFTER '1000';

-- CR 56
ALTER TYPE xplan_gml.so_klassifiznachsonstigemrecht ADD VALUE '1500' AFTER '1400';
ALTER TYPE xplan_gml.so_klassifiznachsonstigemrecht ADD VALUE '1600' AFTER '1500';