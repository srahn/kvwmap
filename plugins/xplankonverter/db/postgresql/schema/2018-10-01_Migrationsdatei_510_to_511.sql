BEGIN;
	-- KORREKTUREN AM DATENMODELL
	-- Ergänzung der Enumeration BP_WegerechtTypen um Sonstiges(9999)


	INSERT INTO xplan_gml.enum_bp_wegerechttypen (wert, abkuerzung, beschreibung)
	VALUES (9999, 'Sonstiges', 'Sonstige Wegerechttypen');

	-- Umbenennung der Langform des Codes 1000 in BP_ZweckbestimmungStrassenverekhr Parkierungsflaeche -> Parkplatz
	UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr
	SET abkuerzung = 'Parkplatz'
	WHERE wert = 1000;

	-- KORREKTUREN IM OBJEKTARTENKATALOG
	-- Korrektur in der Defintion von FP_Bodenschaetze
	-- Korrektur der Kardinalität der Relation gehoertzuBereich in den Klassen BP_Bereich, FP_Bereich, RP_Bereich, LP_Bereich und SO_Bereich [1..]->[1]. Die Korrektur betrifft ausschließlich den Objektartenkatalog, in den Schema-Dateien war die korrekte Kardinalität spezifiziert.
	-- Korrektur der Defintiionend es Attributs anzahl von LP_AnpflanzungBindungErhaltung
	-- KOrrektur der Definitionen von BP_SchutzPflegeEntwicklugngsFlaeche und BP_SchutzPflegeEntwicklungsMassnahme (Verweis auf den richtigen Abschnitt des BauGB)
	-- Korrektur der Definition von Enumerationswerten in XP_ZweckbestimmungKennzeichnung (Gesetzl. Grundlage für die Verwendung im BPlan)
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen Naturgewalten erforderlich sind (§5, Abs. 3, Nr. 1 und §9, Abs. 5, Nr. 1 BauGB).'
	WHERE wert = 1000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Flächen, die für den Abbau von Mineralien bestimmt sind (§5, Abs. 3, Nr. 2 und §9, Abs. 5, Nr. 2 BauGB).'
	WHERE wert = 2000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen äußere Einwirkungen erforderlich sind (§5, Abs. 3, Nr. 1 und §9, Abs. 5, Nr. 1 BauGB).'
	WHERE wert = 3000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Für bauliche Nutzung vorgesehene Flächen, deren Böden erheblich mit umweltgefährdenden Stoffen belastet sind (§5, Abs. 3, Nr. 3 und §9, Abs. 5, Nr. 3 BauGB).'
	WHERE wert = 4000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Für bauliche Nutzung vorgesehene Flächen, die erheblicher Lärmbelastung ausgesetzt sind.'
	WHERE wert = 5000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Flächen, unter denen der Bergbau umgeht  (§5, Abs. 3, Nr. 2 und §9, Abs. 5, Nr. 2. BauGB).'
	WHERE wert = 6000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Für Bodenordnungsmaßnahmen vorgesehene Gebiete, z.B. Gebiete für Umlegungen oder Flurbereinigung'
	WHERE wert = 7000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Räumlich besonders gekennzeichnetes Vorhabengebiets, das kleiner als der Geltungsbereich ist, innerhalb eines vorhabenbezogenen BPlans.'
	WHERE wert = 8000;
	UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung
	SET beschreibung = 'Kennzeichnung nach anderen gesetzlichen Vorschriften.'
	WHERE wert = 9999;

	-- Korrektur der Definition des Attributs sondernutzung von BP_BaugebietsTeilFlaeche
	-- Korrektur der Definition des Attributs nutzungText von BP_BaugebietsTeilFlaeche
	-- Ergänzung der Definition von Enumerationswerten in XP_Sondernutzungen (Verweise auf die relevanten Versionen und Paragraphen der BauNVO)
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Wochenendhausgebiet nach §10 der BauNVO 1977 und 1990'
	WHERE wert = 1000;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Ferienhausgebiet nach §10 der BauNVO 1977 und 1990'
	WHERE wert = 1100;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Campingplatzgebiet nach §10 der BauNVO 1977 und 1990'
	WHERE wert = 1200;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Kurgebiet nach §10 der BauNVO 1977 und 1990'
	WHERE wert = 1300;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sonstiges Sondergebiet für Erholung nach §10 der BauNVO 1977 und 1990'
	WHERE wert = 1400;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Einzelhandelsgebiet nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 1500;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Gebiet für großflächigen Einzelhandel nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 1600;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Ladengebiet nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 16000;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Einkaufszentrum nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 16001;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sonstiges Gebiet für großflächigen Einzelhandel nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 16002;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Verkehrsübungsplatz nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 1700;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Hafengebiet nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 1800;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet für Erneuerbare Energien nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 1900;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Militärisches Sondergebiet nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2000;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet Landwirtschaft nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2100;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet Sport nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2200;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet für Gesundheit und Soziales nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2300;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Golfplatz nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2400;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet für Kultur nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2500;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet Tourismus nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2600;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet für Büros und Verwaltung nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2700;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet Hochschule nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2800;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sondergebiet für Messe nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 2900;
	UPDATE xplan_gml.enum_xp_sondernutzungen
	SET beschreibung = 'Sonstiges Sondergebiet nach §11 der BauNVO 1977 und 1990'
	WHERE wert = 9999;


	-- SONSTIGES
	-- Komplettierung der Dokumentation von CR-013 (Neue Enumerations-Einträge in BP_Strassenverkehr und FP_Strassenverkehr (bereits umgesetzt)
	UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr
	SET abkuerzung = 'B_RAnlage'
	WHERE wert = 3200;
COMMIT;