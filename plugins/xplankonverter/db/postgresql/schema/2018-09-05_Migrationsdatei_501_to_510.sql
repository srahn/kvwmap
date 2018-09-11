BEGIN;

	-- CR 04
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionbaunvodatum date;
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionbaunvotext character varying;
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionbaugbdatum date;
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionbaugbtext character varying;
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionsonstrechtsgrundlagedatum date;
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN versionsonstrechtsgrundlagetext character varying;

	COMMENT ON COLUMN xplan_gml.bp_plan.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.bp_plan.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.bp_plan.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.bp_plan.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.bp_plan.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.bp_plan.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1';

	COMMENT ON COLUMN xplan_gml.bp_bereich.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_bereich.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_bereich.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_bereich.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_bereich.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_bereich.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';

	-- CR 05
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionbaunvodatum date;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionbaunvotext character varying;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionbaugbdatum date;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionbaugbtext character varying;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionsonstrechtsgrundlagedatum date;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN versionsonstrechtsgrundlagetext character varying;

	COMMENT ON COLUMN xplan_gml.fp_plan.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.fp_plan.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.fp_plan.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.fp_plan.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.fp_plan.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.fp_plan.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1';

	COMMENT ON COLUMN xplan_gml.fp_bereich.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.fp_bereich.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.fp_bereich.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.fp_bereich.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.fp_bereich.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.fp_bereich.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1. veraltet, entfällt in zukünftiger Version';

	-- CR 06
	ALTER TABLE xplan_gml.xp_bereich ADD COLUMN refScan character varying;
	COMMENT ON COLUMN xplan_gml.xp_bereich.refscan IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';
	COMMENT ON COLUMN xplan_gml.xp_bereich.rasterbasis IS 'Assoziation zu: FeatureType XP_Rasterdarstellung (xp_rasterdarstellung) 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON TABLE xplan_gml.xp_rasterdarstellung IS 'FeatureType: "XP_Rasterdarstellung". veraltet, entfällt in zukünftiger Version';

	-- CR 07
	COMMENT ON TABLE xplan_gml.bp_erhaltungsbereichflaeche IS 'FeatureType: "BP_ErhaltungsBereichFlaeche". veraltet, entfällt in zukünftiger Version';
	COMMENT ON TABLE xplan_gml.enum_bp_erhaltungsgrund IS 'Alias: "enum_BP_ErhaltungsGrund". veraltet, entfällt in zukünftiger Version';

	-- CR 08
	ALTER TABLE xplan_gml.bp_abgrabungsflaeche ADD COLUMN abbaugut character varying;
	COMMENT ON COLUMN xplan_gml.bp_abgrabungsflaeche.abbaugut IS 'abbaugut  CharacterString 0..1';
	ALTER TABLE xplan_gml.fp_abgrabungsflaeche ADD COLUMN abbaugut character varying;
	COMMENT ON COLUMN xplan_gml.fp_abgrabungsflaeche.abbaugut IS 'abbaugut  CharacterString 0..1';
	COMMENT ON TABLE xplan_gml.bp_bodenschaetzeflaeche IS 'FeatureType: "BP_BodenschaetzeFlaeche". veraltet, entfällt in zukünftiger Version';
	COMMENT ON TABLE xplan_gml.fp_bodenschaetzeflaeche IS 'FeatureType: "FP_BodenschaetzeFlaeche". veraltet, entfällt in zukünftiger Version';

	-- CR 09
		--Laesst sich nicht implementieren

	-- CR 10
		--Laesst sich nicht implementieren

	-- CR 11
		-- Laesst sich nur teilweise implementieren
	UPDATE xplan_gml.enum_xp_zweckbestimmungverentsorgung
	SET abkuerzung = 'SalzOderSoleleitung' WHERE abkuerzung = 'Salz oder Soleleitungen';

	-- CR 12
	ALTER TABLE xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung NO INHERIT xplan_gml.bp_flaechenschlussobjekt;
	ALTER TABLE xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung INHERIT xplan_gml.bp_geometrieobjekt;

	-- CR 13
	INSERT INTO xplan_gml.enum_fp_zweckbestimmungstrassenverkehr (wert, abkuerzung, beschreibung) VALUES
		(140012, 'Wirtschaftsweg', 'Wirtschaftsweg'),
		(140013, 'LandwirtschaftlicherVerkehr','Landwirtschaftlicher Verkehr'),
		(16002, 'P_RAnlage','Park- and Ride-Anlage'),
		(3000, 'CarSharing','Fläche zum Car-Sharing'),
		(3100, 'BikeSharing','Fläche zum ABstellen gemeinschaftlich genutzter Fahrräder'),
		(3200, 'Bike_RideAnlage','Bike and Ride Anlage'),
		(3300, 'Parkhaus','Parkhaus'),
		(3400, 'Mischverkehrsflaeche','Mischverkehrsfläche'),
		(3500, 'Ladestation','Ladestation für Elektrofahrzeuge');
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '140012' AFTER '140011';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '140013' AFTER '140012';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '16002' AFTER '16001';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3000' AFTER '16002';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3100' AFTER '3000';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3200' AFTER '3100';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3300' AFTER '3200';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3400' AFTER '3300';
	ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '3500' AFTER '3400';

	-- CR 14
	INSERT INTO xplan_gml.enum_xp_externereferenztyp (wert, abkuerzung, beschreibung) VALUES
		(1065,'Verordnung','Elektronische Version des Verordnungstexts'),
		(2600,'StaedtebaulicherVertrag','Elektronische Version des städtebaulichen Vertrags');
	ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '1065' AFTER '1060';
	ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2600' AFTER '2500';

	-- CR 15
	INSERT INTO xplan_gml.enum_bp_verfahren (wert, abkuerzung, beschreibung) VALUES
		(4000,'Parag13b','BPlan Verfahren nach Paragraph 13b BauGB.');
	ALTER TYPE xplan_gml.bp_verfahren ADD VALUE '4000' AFTER '3000';

	-- CR 16
		--Laesst sich nicht implementieren

	-- CR 17
	ALTER TABLE xplan_gml.rp_generischesobjekt ALTER COLUMN typ TYPE xplan_gml.rp_generischesobjekttypen[];
	COMMENT ON COLUMN xplan_gml.rp_generischesobjekt.typ IS 'typ CodeList RP_GenerischesObjektTypen 0..*';

	-- CR 21
	ALTER TABLE xplan_gml.rp_plan ADD COLUMN genehmigungsbehoerde character varying;
	COMMENT ON COLUMN xplan_gml.rp_plan.genehmigungsbehoerde IS 'genehmigungsbehoerde  CharacterString 0..1';

	-- CR 22
	INSERT INTO xplan_gml.enum_rp_zentralerortsonstigetypen (wert, abkuerzung, beschreibung) VALUES
		(2200,'Kongruenzraum','Der Kongruenzraum ist ein Bezugsraum zur Anwendung des Kongruenzgebots. Der Kongruenzraum beschreibt den Raum im Umfeld eines Zentralen Ortes, den Einzelhandelsgroßprojekte, die im Zentralen Ort angesiedelt werden sollen oder bereits bestehen, im Wesentlichen versorgen sollen.');
	ALTER TYPE xplan_gml.rp_zentralerortsonstigetypen ADD VALUE '2200' AFTER '2101';
	INSERT INTO xplan_gml.enum_rp_bodenschutztypen (wert, abkuerzung, beschreibung) VALUES
		(4000,'Torferhalt','Bodenschutz zum Torferhalt dient dem Erhalt von vorhandenen Torfkörpern als natürlichen Speicher von Kohlenstoffe, als Beitrag zum Klimaschutz.');
	ALTER TYPE xplan_gml.rp_bodenschutztypen ADD VALUE '4000' AFTER '3000';
	INSERT INTO xplan_gml.enum_rp_sonstverkehrtypen (wert, abkuerzung, beschreibung) VALUES
		(2001,'Teststrecke','Sicherung von Teststrecken für die Fahrzeugindustrie und Sicherung von Gebieten zur Entwicklung damit verbundener neuer Verkehrssysteme und -techniken');
	ALTER TYPE xplan_gml.rp_sonstverkehrtypen ADD VALUE '2001' AFTER '2000';

	-- CR 23
		--Laesst sich nicht implementieren

	-- CR 24
	INSERT INTO xplan_gml.enum_xp_arthoehenbezug (wert, abkuerzung, beschreibung) VALUES
		(1100,'absolutNN','Absolute Höhenangabe im Bezugssystem NN'),
		(1200,'absolutDHHN','Absolute Höhenangabe im Bezugssystem DHHN');
	ALTER TYPE xplan_gml.xp_arthoehenbezug ADD VALUE '1100' AFTER '1000';
	ALTER TYPE xplan_gml.xp_arthoehenbezug ADD VALUE '1200' AFTER '1100';

	-- CR 26
	INSERT INTO xplan_gml.enum_rp_funktionszuweisungentypen (wert, abkuerzung, beschreibung) VALUES
		(9000,'LaendlicheSiedlung','Ländliche Siedlung');
	ALTER TYPE xplan_gml.rp_funktionszuweisungentypen ADD VALUE '9000' AFTER '8000';
	UPDATE xplan_gml.enum_rp_bergbauplanungtypen
	SET abkuerzung = 'Abbau'
	WHERE abkuerzung = 'Abbaubereich';
	ALTER TABLE xplan_gml.enum_rp_lufttypen RENAME TO xplan_gml.enum_rp_klimaschutztypen;
	ALTER TYPE xplan_gml.rp_lufttypen RENAME TO xplan_gml.rp_klimaschutztypen;
	ALTER TABLE xplan_gml.rp_klimaschutz ALTER COLUMN typ TYPE xplan_gml.rp_klimaschutztypen;
	INSERT INTO xplan_gml.enum_rp_klimaschutztypen (wert, abkuerzung, beschreibung) VALUES
		(3000,'BesondereKlimaschutzfunktion','Besondere Klimaschutzfunktion');
	ALTER TYPE xplan_gml.rp_funktionszuweisungentypen ADD VALUE '3000' AFTER '2000';
	INSERT INTO xplan_gml.enum_rp_erholungtypen (wert, abkuerzung, beschreibung) VALUES
		(2001,'LandschaftsbezogeneErholung','Landschaftsbezogene Erholung'),
		(3001,'InfrastrukturelleErholung','Infrastrukturelle Erholung');
	ALTER TYPE xplan_gml.rp_erholungtypen ADD VALUE '2001' AFTER '2000';
	ALTER TYPE xplan_gml.rp_erholungtypen ADD VALUE '3001' AFTER '3000';
	INSERT INTO xplan_gml.enum_rp_energieversorgungtypen (wert, abkuerzung, beschreibung) VALUES
		(8000,'Korridor','Korridor');
	ALTER TYPE xplan_gml.rp_energieversorgungtypen ADD VALUE '8000' AFTER '7000';
	ALTER TABLE xplan_gml.rp_rohstoff ADD COLUMN detaillierterrohstofftyp character varying[];
	COMMENT ON COLUMN xplan_gml.rp_rohstoff.detaillierterrohstofftyp IS 'detaillierterRohstoffTyp character varying 0..*';
	INSERT INTO xplan_gml.enum_rp_rohstofftypen (wert, abkuerzung, beschreibung) VALUES
		(7200,'Andesit','Andesit'),
		(7300,'Formsand','Formsand'),
		(7400,'Gabbro','Gabbro'),
		(7500,'MikrodioritAndesit','MikrodioritAndesit');
	ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7200' AFTER '7100';
	ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7300' AFTER '7200';
	ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7400' AFTER '7300';
	ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '7500' AFTER '7400';

	-- CR 27
		--Laesst sich nicht implementieren

	-- CR 28
		--Laesst sich nicht implementieren

	-- CR 30
	INSERT INTO xplan_gml.enum_xp_arthoehenbezug (wert, abkuerzung, beschreibung) VALUES
		(6500,'WH','Wandhöhe');
	ALTER TYPE xplan_gml.rp_rohstofftypen ADD VALUE '6500' AFTER '6000';

	-- CR 31
	ALTER TABLE xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung ADD COLUMN zugunstenvon character varying;
	COMMENT ON COLUMN xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung.detaillierterrohstofftyp IS 'zugunstenVon character varying 0..1';

	-- CR 32
	CREATE TYPE xplan_gml.bp_dachgestaltung AS
		(
			dachform xplan_gml.bp_dachform,
			detaillierteDachform xplan_gml.bp_detaildachform,
			dn double precision,
			dnmax double precision,
			dnmin double precision,
			dnzwingend doubple precision
		);
	ALTER TYPE xplan_gml.bp_dachform
		OWNER to kvwmap;

	ALTER TABLE xplan_gml.bp_baugebietsteilflaeche ADD COLUMN dachgestaltung xplan_gml.bp_dachgestaltung[];

	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.dnmin IS 'Ableitung von Type BP_GestaltungBaugebiet DNMin AngleType double precision 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.dnmax IS 'Ableitung von Type BP_GestaltungBaugebiet double precision; DNMin AngleType double precision 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.dn IS 'Ableitung von Type BP_GestaltungBaugebiet double precision; DN AngleType double precision 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.dnzwingend IS 'Ableitung von Type BP_GestaltungBaugebiet DN AngleType double precision 0..1. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.dachform IS 'Ableitung von Type BP_GestaltungBaugebiet xplan_gml.bp_dachform[] dachform enumeration xplan_gml.bp_dachform[] 0..*. veraltet, entfällt in zukünftiger Version';
	COMMENT ON COLUMN xplan_gml.bp_baugebietsteilflaeche.detailliertedachform IS 'Ableitung von Type BP_GestaltungBaugebiet xplan_gml.bp_detaildachform[] detaillierteDachform CodeList BP_DetailDachform 0..*. veraltet, entfällt in zukünftiger Version';
	-- CR 33
	ALTER TABLE xplan_gml.bp_wegerecht ALTER COLUMN typ xplan_gml.bp_wegerechttypen[];
	COMMENT ON COLUMN typ IS 'typ enumeration BP_WegerechtTypen 0..*';

	ALTER TABLE xplan_gml.bp_wegerecht ADD COLUMN istschmal boolean;
	COMMENT ON COLUMN xplan_gml.bp_wegerecht.istschmal IS 'istSchmal boolean 0..1';

	INSERT INTO xplan_gml.enum_bp_wegerechttypen (wert, abkuerzung, beschreibung) VALUES
		(2500,'Radfahrrecht','Radfahrrecht');
	ALTER TYPE xplan_gml.bp_wegerrechttypen ADD VALUE '2500' AFTER '2000';

	UPDATE xplan_gml.enum_bp_wegerechttypen
	SET beschreibung = 'Geh- und Fahrrecht. veraltet, entfällt in zukünftiger Version';
	WHERE wert = 3000;
	UPDATE xplan_gml.enum_bp_wegerechttypen
	SET beschreibung = 'Geh- und Leitungsrecht. veraltet, entfällt in zukünftiger Version';
	WHERE wert = 4100;
	UPDATE xplan_gml.enum_bp_wegerechttypen
	SET beschreibung = 'Fahr- und Leitungsrecht. veraltet, entfällt in zukünftiger Version';
	WHERE wert = 4200;
	UPDATE xplan_gml.enum_bp_wegerechttypen
	SET beschreibung = 'Geh-, Fahr- und Leitungsrecht. veraltet, entfällt in zukünftiger Version';
	WHERE wert = 5000;

	-- CR 34
	-- CR 34 ist zwar akzeptiert,fehlt aber im UML-XSD-Modell
	-- -> deswegen noch nicht implementieren (sonst keine Validierung)
	/*INSERT INTO xplan_gml.enum_bp_speziellebauweisetypen (wert, abkuerzung, beschreibung) VALUES
		(1600,'Bruecke','Brücke'),
		(1700,'Tunnel','Tunnel'),
		(1800,'Rampe','Rampe');
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1600' AFTER '1500';
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1700' AFTER '1600';
	ALTER TYPE xplan_gml.bp_speziellebauweisetypen ADD VALUE '1800' AFTER '1700';*/

	-- CR 36
	INSERT INTO xplan_gml.enum_bp_zweckbestimmungstrassenverkehr (wert, abkuerzung, beschreibung) VALUES
		(3000,'CarSharing','Fläche zum Car-Sharing.'),
		(3100,'BikeSharing','Fläche zum Abstellen gemeinschaftlich genutzter Fahrräder.'),
		(3200,'Bike_RideAnlage','Bike and Ride Anlage.'),
		(3300,'Parkhaus','Parkhaus'),
		(3400,'Mischverkehrsflaeche','Mischverkehrsfläche'),
		(3500,'Ladestation','Ladestation für Elektrofahrzeuge'),
		(1560,'ReitKutschweg','Reit- oder Kutschweg'),
		(2500,'Rastanlage','Rastanlage'),
		(2600,'Busbahnhof','Busbahnhof');
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
	INSERT INTO xplan_gml.enum_bp_zweckbestimmunggemeinschaftsanlagen (wert, abkuerzung, beschreibung) VALUES
		(4100,'Fahrradstellplaetze','Fahrrad-Stellplätze'),
		(4200,'Gemeinschaftsdachgaerten','Gemeinschaftlich genutzter Dachgarten'),
		(4300,'GemeinschaftlichNutzbareDachflaechen','Gemeinschaftlich nutzbare Dachflächen');
	ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4100' AFTER '9999';
	ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4200' AFTER '4100';
	ALTER TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen ADD VALUE '4300' AFTER '4300';

	-- CR 38
	INSERT INTO xplan_gml.enum_bp_zweckbestimmungnebenanlagen (wert, abkuerzung, beschreibung) VALUES
		(3700,'Fahrradstellplaetze','Fahrrad Stellplätze');
	ALTER TYPE xplan_gml.bp_zweckbestimmungnebenanlagen ADD VALUE '3700' AFTER '3600';

	-- CR 39
	ALTER TABLE xplan_gml.vegetationstypen RENAME TO xplan.bp_vegetationsobjekttypen;
	ALTER TABLE xplan_gml.bp_anpflanzungbindungerhaltung ALTER COLUMN baumart TYPE xplan_gml.bp_vegetationsobjekttypen;
	ALTER TABLE xplan_gml.bp_anpflanzungbindungerhaltung ADD COLUMN mindesthoehe double precision;
	COMMENT ON COLUMN xplan_gml.bp_anpflanzungbindungerhaltung.mindesthoehe IS 'mindesthoehe length 0..1';
	ALTER TABLE xplan_gml.bp_anpflanzungbindungerhaltung.anzahl ADD COLUMN anzahl integer;
	COMMENT ON COLUMN xplan_gml.bp_anpflanzungbindungerhaltung.anzahl IS 'anzahl integer 0..1';
	ALTER TABLE xplan_gml.lp_anpflanzungbindungerhaltung.anzahl ADD COLUMN anzahl integer;
	COMMENT ON COLUMN xplan_gml.lp_anpflanzungbindungerhaltung.anzahl IS 'anzahl integer 0..1';

	-- CR 40
	ALTER TABLE xplan_gml.xp_plan ADD COLUMN technischerplanersteller character varying;
	COMMENT ON COLUMN xplan_gml.xp_plan.technischerplanersteller IS 'technischerPlanersteller CharacterString 0..1';

	-- CR 41
	ALTER TABLE xplan_gml.bp_plan ADD COLUMN planaufstellendegemeinde xplan_gml.xp_gemeinde[];
	COMMENT ON COLUMN xplan_gml.bp_plan.technischerplanersteller IS 'planaufstellendeGemeinde xplan_gml.xp_gemeinde 0..*';
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN planaufstellendegemeinde character varying;
	COMMENT ON COLUMN xplan_gml.bp_plan.technischerplanersteller IS 'planaufstellendeGemeinde xplan_gml.xp_gemeinde 0..*';
	ALTER TABLE xplan_gml.so_plan ADD COLUMN planaufstellendegemeinde character varying;
	COMMENT ON COLUMN xplan_gml.bp_plan.technischerplanersteller IS 'planaufstellendeGemeinde xplan_gml.xp_gemeinde 0..*';

	-- CR 42
	INSERT INTO xplan_gml.enum_xp_externereferenztyp (wert, abkuerzung, beschreibung) VALUES
		(2700,'UmweltbezogeneStellungnahmen','Elektronisches Dokument mit umweltbezogenen Stellungnahmen.'),
		(2800,'Beschluss','Dokument mit dem Beschluss des Gemeinderats zur öffentlichen Auslegung.');
	ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2700' AFTER '2600';
	ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2800' AFTER '2700';

	-- CR 43
	ALTER TABLE xplan_gml.so_plan ADD COLUMN gemeinde xp_gemeinde[];
	COMMENT ON COLUMN xplan_gml.so_plan.gemeinde IS 'gemeinde xplan_gml.xp_gemeinde 0..*. Wird in Version 6.0 Pflichtattribut.';

	-- CR 44
	UPDATE xplan_gml.enum_xp_zweckbestimmunggruen
	SET beschreibung = beschreibung || '. veraltet, entfällt in zukünftiger Version'
	WHERE wert = 24002;

	-- CR 46
	COMMENT ON TABLE xplan_gml.bp_rekultivierungsflaeche IS 'FeatureType: "BP_RekultivierungsFlaeche". veraltet, entfällt in zukünftiger Version';

	-- CR 47
	CREATE TABLE xplan_gml.fp_landwirtschaft
	(
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  uuid character varying,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  text character varying,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  rechtsstand xplan_gml.xp_rechtsstand,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  gliederung1 character varying,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  gliederung2 character varying,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  ebene integer,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  hatgenerattribut xplan_gml.xp_generattribut[],
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  hoehenangabe xplan_gml.xp_hoehenangabe[],
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  user_id integer,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  created_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  updated_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  konvertierung_id integer,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  refbegruendunginhalt text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  gehoertzubereich text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  wirddargestelltdurch text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  externereferenz xplan_gml.xp_spezexternereferenz[],
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  startbedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  endebedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  rechtscharakter xplan_gml.fp_rechtscharakter NOT NULL,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  spezifischepraegung xplan_gml.fp_spezifischepraegungtypen,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  wirdausgeglichendurchspe text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  wirdausgeglichendurchflaeche text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  reftextinhalt text,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  "position" geometry(MultiPolygon) NOT NULL,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  flaechenschluss boolean NOT NULL,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  nordwinkel double precision,
	-- Geerbt from table xplan_gml.fp_geometrieobjekt:  flussrichtung boolean,
		detailliertezweckbestimmung xplan_gml.fp_detailzweckbestlandwirtschaftsflaeche[], -- detaillierteZweckbestimmung CodeList FP_DetailZweckbestLandwirtschaftsFlaeche 0..*
		zweckbestimmung xplan_gml.xp_zweckbestimmunglandwirtschaft[] -- zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*
	)
	INHERITS (xplan_gml.fp_geometrieobjekt)
	WITH (
		OIDS=TRUE
	);
	ALTER TABLE xplan_gml.fp_landwirtschaft
		OWNER TO kvwmap;
	COMMENT ON TABLE xplan_gml.fp_landwirtschaft
		IS 'FeatureType: "FP_Landwirtschaft"';
	COMMENT ON COLUMN xplan_gml.fp_landwirtschaft.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestLandwirtschaftsFlaeche 0..*';
	COMMENT ON COLUMN xplan_gml.fp_landwirtschaft.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*';
	COMMENT ON TABLE xplan_gml.fp_landwirtschaftsflaeche IS 'FeatureType: "FP_LandwirtschaftsFlaeche". veraltet, entfällt in zukünftiger Version';

	-- CR 50
	CREATE TABLE xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche
	(
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  uuid character varying,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  text character varying,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  rechtsstand xplan_gml.xp_rechtsstand,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  gliederung1 character varying,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  gliederung2 character varying,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  ebene integer,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  hatgenerattribut xplan_gml.xp_generattribut[],
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  hoehenangabe xplan_gml.xp_hoehenangabe[],
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  user_id integer,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  created_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  updated_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  konvertierung_id integer,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  refbegruendunginhalt text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  gehoertzubereich text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirddargestelltdurch text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  externereferenz xplan_gml.xp_spezexternereferenz[],
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  startbedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  endebedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  rechtscharakter xplan_gml.bp_rechtscharakter NOT NULL,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirdausgeglichendurchspemassnahme text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirdausgeglichendurchmassnahme text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirdausgeglichendurchspeflaeche text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirdausgeglichendurchflaeche text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  wirdausgeglichendurchabe text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  reftextinhalt text,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  "position" geometry(MultiPolygon) NOT NULL,
	-- Geerbt from table xplan_gml.bp_ueberlagerungsobjekt:  flaechenschluss boolean NOT NULL
	)
	INHERITS (xplan_gml.bp_ueberlagerungsobjekt)
	WITH (
		OIDS=TRUE
	);
	ALTER TABLE xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche
		OWNER TO kvwmap;
	COMMENT ON TABLE xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche
		IS 'FeatureType: "BP_NichtUeberbaubareGrundstuecksflaeche"';

	-- CR 51
	INSERT INTO xplan_gml.enum_bp_abgrenzungentypen (wert, abkuerzung, beschreibung) VALUES
		(2000,'UnterschiedlicheHoehen','Abgrenzung von Bereichen mit unterschiedlichen Festsetzungen zur Gebäudehöhe');
	ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '2000' AFTER '1000';

	-- CR 52
-- Nicht implementierbar

	-- CR 53
-- Table: xplan_gml.bp_landwirtschaft

-- DROP TABLE xplan_gml.bp_landwirtschaft;

	CREATE TABLE xplan_gml.bp_landwirtschaftsflaeche
	(
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc(),
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  uuid character varying,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  text character varying,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  rechtsstand xplan_gml.xp_rechtsstand,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  gesetzlichegrundlage xplan_gml.xp_gesetzlichegrundlage,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  gliederung1 character varying,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  gliederung2 character varying,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  ebene integer,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  hatgenerattribut xplan_gml.xp_generattribut[],
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  hoehenangabe xplan_gml.xp_hoehenangabe[],
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  user_id integer,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  created_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  updated_at timestamp without time zone NOT NULL DEFAULT now(),
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  konvertierung_id integer,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  refbegruendunginhalt text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  gehoertzubereich text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirddargestelltdurch text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  externereferenz xplan_gml.xp_spezexternereferenz[],
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  startbedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  endebedingung xplan_gml.xp_wirksamkeitbedingung,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  rechtscharakter xplan_gml.bp_rechtscharakter NOT NULL,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirdausgeglichendurchspemassnahme text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirdausgeglichendurchmassnahme text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirdausgeglichendurchspeflaeche text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirdausgeglichendurchflaeche text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  wirdausgeglichendurchabe text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  reftextinhalt text,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  "position" geometry NOT NULL,
	-- Geerbt from table xplan_gml.bp_flaechenschlussobjekt:  flaechenschluss boolean,
		detailliertezweckbestimmung xplan_gml.bp_detailzweckbestlandwirtschaft[], -- detaillierteZweckbestimmung CodeList BP_DetailZweckbesLandwirtschaft 0..*
		zweckbestimmung xplan_gml.xp_zweckbestimmunglandwirtschaft[] -- zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*
	)
	INHERITS (xplan_gml.bp_flaechenschlussobjekt)
	WITH (
		OIDS=TRUE
	);
	ALTER TABLE xplan_gml.bp_landwirtschaftsflaeche
		OWNER TO kvwmap;
	COMMENT ON TABLE xplan_gml.bp_landwirtschaftsflaeche
		IS 'FeatureType: "BP_Landwirtschaft"';
	COMMENT ON COLUMN xplan_gml.bp_landwirtschaftsflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbesLandwirtschaft 0..*';
	COMMENT ON COLUMN xplan_gml.bp_landwirtschaftsflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*';
	COMMENT ON TABLE xplan_gml.bp_landwirtschaft IS 'FeatureType: "BP_LandwirtschaftsFlaeche". veraltet, entfällt in zukünftiger Version';

	-- CR 54
	ALTER TABLE xplan_gml.xp_objekt ADD COLUMN aufschrift character varying;
	COMMENT ON COLUMN xplan_gml.xp_objekt IS 'aufschrift CharacterString 0..1';

	-- CR 55
	ALTER TABLE xplan_gml.bp_aufschuettungsflaeche ADD COLUMN aufschuettungsmaterial character varying;
	COMMENT ON COLUMN xplan_gml.bp_aufschuettungsflaeche.aufschuettungsmaterial IS 'aufschuettungsmaterial CharacterString 0..1';
	ALTER TABLE xplan_gml.fp_aufschuettung ADD COLUMN aufschuettungsmaterial character varying;
	COMMENT ON COLUMN xplan_gml.fp_aufschuettung.aufschuettungsmaterial IS 'aufschuettungsmaterial CharacterString 0..1';

	-- CR 56
	INSERT INTO xplan_gml.enum_so_klsasifiznachsonstigemrecht (wert, abkuerzung, beschreibung) VALUES
		(1500,'Rekultivierungsflaeche','Zu rekultivierende Fläche'),
		(1600,'Renaturierungsflaeche','Zu renaturierende Fläche');
	ALTER TYPE xplan_gml.so_klsasifiznachsonstigemrecht ADD VALUE '1500' AFTER '1400';
	ALTER TYPE xplan_gml.so_klsasifiznachsonstigemrecht ADD VALUE '1600' AFTER '1500';

COMMIT;
