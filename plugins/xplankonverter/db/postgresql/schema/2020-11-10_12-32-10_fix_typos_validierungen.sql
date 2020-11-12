BEGIN;
-- Fix sondernutung to sondernutzung in functionsargumente
UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''2100'' OR sondernutzung::text NOT IN (''1500'',''1600'',''16000'',''16001'',''16002'',''1700'',''1800'',''1900'',''2000'',''2100'',''2200'',''2300'',''23000'',''2400'',''2500'',''2600'',''2700'',''2720'',''2800'',''2900'',''9999'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '5.3'
AND id = 1383;

-- Also fix bracket issue
UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''2000'' OR sondernutzung::text NOT IN (''1000'',''1100'',''1200'',''1300'',''1400'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '4.1'
AND id = 1479;

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''2100'' OR sondernutzung::text NOT IN (''1500'',''1600'',''16000'',''16001'',''1700'',''1800'',''1900'',''2000'',''2100'',''2200'',''2300'',''23000'',''2400'',''2500'',''2600'',''2700'',''2800'',''2900'',''9999'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '4.1'
AND id = 1480;

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''2100'' OR sondernutzung::text NOT IN (''1500'',''1600'',''16000'',''16001'',''16002'',''1700'',''1800'',''1900'',''2000'',''2100'',''2200'',''2300'',''23000'',''2400'',''2500'',''2600'',''2700'',''2800'',''2900'',''9999'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '5.2'
AND id = 1481;

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''3000'' OR sondernutzung::text NOT IN (''1000'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '4.1'
AND id = 1247;

UPDATE xplankonverter.validierungen
SET functionsargumente[1] = 'besondereArtDerBaulNutzung IS NULL OR besondereArtDerBaulNutzung::text = ''2000'' OR sondernutzung::text NOT IN (''1000'',''1100'',''1200'',''1300'',''1400'')'
WHERE functionsname = 'attribute_is_null_or_has_value_if_other_attribute_has_at_least_one_of_values'
AND konformitaet_nummer = '5.3.1.2'
AND konformitaet_version_von = '5.2'
AND id = 1316;

COMMIT;