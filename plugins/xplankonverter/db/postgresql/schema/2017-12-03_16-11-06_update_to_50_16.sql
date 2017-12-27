BEGIN;

ALTER TABLE xplan_gml.xp_plan DROP COLUMN rechtsverbindlich;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN informell;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN refbeschreibung;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN refbegruendung;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN refexternalcodelist;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN reflegende;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN refrechtsplan;
ALTER TABLE xplan_gml.xp_plan DROP COLUMN refplangrundlage;
ALTER TABLE xplan_gml.rp_plan DROP COLUMN refumweltbericht;
ALTER TABLE xplan_gml.rp_plan DROP COLUMN refsatzung;
ALTER TABLE xplan_gml.rp_plan DROP COLUMN refkarte;

ALTER TABLE xplan_gml.xp_objekt DROP COLUMN textschluessel;
ALTER TABLE xplan_gml.xp_objekt DROP COLUMN textschluesselbegruendung;
ALTER TABLE xplan_gml.xp_objekt DROP COLUMN rechtsverbindlich;
ALTER TABLE xplan_gml.xp_objekt DROP COLUMN informell;
ALTER TABLE xplan_gml.xp_objekt DROP COLUMN reftextinhalt;

-- Aenderungen fuer ExterneReferenzen in Plan, Objekt, Raster, und TextAbschnitt
ALTER TABLE xplan_gml.xp_plan ADD COLUMN externereferenz xplan_gml.xp_spezexternereferenz[];
COMMENT ON COLUMN xplan_gml.xp_plan.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';

-- Hier müsste man jetzt noch die Daten von den alten Attributen in das Array des neuen Typ externe Referenz aufnehmen.
-- Da aber hier kaum Daten vorhanden sind, wird auf eine händische Anpassung gesetzt.
/*
UPDATE
  xplan_gml.xp_plan t
SET
  externereferenz = array_append(t.externereferenz, p.rechtsverbindlich::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.informell::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.refbeschreibung::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.refbegruendung::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.refexternalcodelist::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.reflegende::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.refrechtsplan::xplan_gml.xp_spezexternereferenz[]),
  externereferenz = array_append(t.externereferenz, p.refplangrundlage::xplan_gml.xp_spezexternereferenz[])
FROM
  xplan_gml.rp_plan AS p
WHERE
  gml_id = gml_id.p
*/

ALTER TABLE xplan_gml.xp_objekt ADD COLUMN externereferenz xplan_gml.xp_spezexternereferenz[];
COMMENT ON COLUMN xplan_gml.xp_objekt.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';

ALTER TABLE xplan_gml.xp_mimetypes ADD COLUMN value text;
COMMENT ON TABLE xplan_gml.xp_mimetypes IS 'Alias: "XP_MimeTypes", UML-Typ: Code Liste';
COMMENT ON COLUMN xplan_gml.xp_mimetypes.codespace IS 'codeSpace  text';
COMMENT ON COLUMN xplan_gml.xp_mimetypes.id IS 'id  character varying';
COMMENT ON COLUMN xplan_gml.xp_mimetypes.value IS 'value text';

COMMENT ON COLUMN xplan_gml.xp_hoehenangabe.abweichenderbezugspunkt IS 'abweichenderBezugspunkt  CharacterString 0..1';

INSERT INTO xplan_gml.enum_xp_zweckbestimmunggemeinbedarf(wert,beschreibung) VALUES
  (10000,'KommunaleEirnichtung'),
  (10001,'BetriebOeffentlZweckbestimmung'),
  (10002,'AnlageBundLand'),
  (10003,'SonstigeOeffentlicheVerwaltung'),
  (12000,'Schule'),
  (12001,'Hochschule'),
  (12002,'BerufsbildendeSchule'),
  (12003,'Forschungseinrichtung'),
  (12004,'SonstigeBildungForschung'),
  (14000,'Sakralgebaeude'),
  (14001,'KirchlicheVerwaltung'),
  (14002,'Kirchengemeinde'),
  (14003,'SonstigeKirche'),
  (16000,'EinrichtungKinder'),
  (16001,'EinrichtungJugendliche'),
  (16002,'EinrichtungFamilienErwachsene'),
  (16003,'EinrichtungSenioren'),
  (18000,'Krankenhaus'),
  (18001,'SonstigeGesundheit'),
  (20000,'MusikTheater'),
  (20001,'Bildung'),
  (20002,'SonstigeKultur'),
  (22000,'Bad'),
  (22001,'SportplatzSporthalle'),
  (24000,'Feuerwehr'),
  (24001,'Schutzbauwerk'),
  (24002,'Justiz'),
  (24003,'SonstigeSicherheitOrdnung'),
  (26000,'Post'),
  (26001,'SonstigeInfrastruktur');

-- postgres interval statt TM_duration für xplan_gml.xp_wirksamkeitbedingung.datumrelativ
-- Ausgabe ueber set intervalstyle = is0_8601; SELECT * FROM xplan_gml.test
CREATE TYPE xplan_gml.xp_wirksamkeitbedingung AS (
  datumrelativ interval,
  datumabsolut date,
  bedingung character varying
);
COMMENT ON TYPE xplan_gml.xp_wirksamkeitbedingung IS 'Alias: "XP_WirksamkeitBedingung",  [0..1],  [0..1],  [0..1]';
COMMENT ON COLUMN xplan_gml.xp_wirksamkeitbedingung.datumrelativ IS 'datumRelativ  interval 0..1';
COMMENT ON COLUMN xplan_gml.xp_wirksamkeitbedingung.datumabsolut IS 'datumAbsolut  Date 0..1';
COMMENT ON COLUMN xplan_gml.xp_wirksamkeitbedingung.bedingung IS 'bedingung  CharacterString 0..1';

ALTER TABLE xplan_gml.xp_objekt ADD COLUMN startbedingung xplan_gml.xp_wirksamkeitbedingung;
COMMENT ON COLUMN xplan_gml.xp_objekt.startbedingung IS 'startBedingung DataType XP_WirksamkeitBedingung 0..1';
ALTER TABLE xplan_gml.xp_objekt ADD COLUMN endebedingung xplan_gml.xp_wirksamkeitbedingung;
COMMENT ON COLUMN xplan_gml.xp_objekt.endebedingung IS 'endeBedingung DataType XP_WirksamkeitBedingung 0..1';
ALTER TABLE xplan_gml.xp_objekt ALTER COLUMN gehoertzubereich TYPE text USING gehoertzubereich[1];
COMMENT ON COLUMN xplan_gml.xp_objekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';

ALTER TABLE xplan_gml.rp_objekt ADD COLUMN reftextinhalt text[];
COMMENT ON COLUMN xplan_gml.rp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType RP_TextAbschnitt (rp_textabschnitt) 0..*';

ALTER TABLE xplan_gml.rp_geometrieobjekt ADD COLUMN flussrichtung boolean;
COMMENT ON COLUMN xplan_gml.rp_geometrieobjekt.flussrichtung IS 'flussrichtung  Boolean 0..1';

ALTER TABLE xplan_gml.rp_bereich DROP COLUMN rasteraenderung;
DROP TABLE xplan_gml.rp_bereich_zu_rp_rasterplanaenderung;
DROP TABLE xplan_gml.rp_rasterplanaenderung;
DROP TABLE xplan_gml.xp_rasterplanaenderung;

ALTER TABLE xplan_gml.rp_generischesobjekttypen ADD COLUMN value text;

ALTER TABLE xplan_gml.rp_sonstgrenzetypen ADD COLUMN value text;

ALTER TABLE xplan_gml.xp_plan ADD COLUMN inverszu_verbundenerplan_xp_verbundenerplan text[];
COMMENT ON COLUMN xplan_gml.xp_plan.inverszu_verbundenerplan_xp_verbundenerplan IS 'Assoziation zu: FeatureType XP_VerbundenerPlan (xp_verbundenerplan) 0..*';

ALTER TABLE xplan_gml.rp_plan ALTER COLUMN auslegungstartdatum TYPE date[] USING CASE WHEN auslegungstartdatum IS NULL THEN NULL ELSE ARRAY[auslegungstartdatum]::date[] END;
ALTER TABLE xplan_gml.rp_plan ALTER COLUMN auslegungenddatum TYPE date[] USING CASE WHEN auslegungenddatum IS NULL THEN NULL ELSE ARRAY[auslegungenddatum]::date[] END;
ALTER TABLE xplan_gml.rp_plan ALTER COLUMN traegerbeteiligungsstartdatum TYPE date[] USING CASE WHEN traegerbeteiligungsstartdatum IS NULL THEN NULL ELSE ARRAY[traegerbeteiligungsstartdatum]::date[] END;
ALTER TABLE xplan_gml.rp_plan ALTER COLUMN traegerbeteiligungsenddatum TYPE date[] USING CASE WHEN traegerbeteiligungsenddatum IS NULL THEN NULL ELSE ARRAY[traegerbeteiligungsenddatum]::date[] END;

INSERT INTO xplan_gml.enum_xp_zweckbestimmunggruen(wert,beschreibung) VALUES
  (10000,'ParkanlageHistorisch'),
  (10001,'ParkanlageNaturnah'),
  (10002,'ParkanlageWaldcharakter'),
  (10003,'NaturnaheUferParkanlage'),
  (12000,'ErholungsGaerten'),
  (14000,'Reitsportanlage'),
  (14001,'Hundesportanlage'),
  (14002,'Wassersportanlage'),
  (14003,'Schiessstand'),
  (14004,'Golfsport'),
  (14005,'Skisport'),
  (14006,'Tennisanlage'),
  (14007,'SonstigerSportplatz'),
  (16000,'Bolzplatz'),
  (16001,'Abenteuerspielplatz'),
  (18000,'Campingplatz'),
  (22000,'Kleintierhaltung'),
  (22001,'Festplatz'),
  (24000,'StrassenbegleitGruen'),
  (24001,'BoeschungsFlaeche'),
  (24002,'FeldWaldWiese'),
  (24003,'Uferschutzstreifen'),
  (24004,'Abschirmgruen'),
  (24005,'UmweltbildungsparkSchaugatter'),
  (24006,'RuhenderVerkehr'),
  (99990,'Gaertnerei');

INSERT INTO xplan_gml.enum_xp_zweckbestimmungkennzeichnung(wert,beschreibung) VALUES
  (8000,'Vorhabensgebiet');

INSERT INTO xplan_gml.enum_xp_zweckbestimmungverentsorgung(wert,beschreibung) VALUES
  (10000,'Hochspannugnsleitung'),
  (10001,'TrafostationUmspannwerk'),
  (10002,'Solarkraftwerk'),
  (10003,'Windkraftwerk'),
  (10004,'Geothermiekraftwerk'),
  (10005,'Elektrizitaetswerk'),
  (10006,'Wasserkraftwerk'),
  (10007,'BiomasseKraftwerk'),
  (10008,'Kabelleitung'),
  (10009,'Niederspannungsleitung'),
  (100010,'Leitungsmast'),
  (12000,'Ferngasleitung'),
  (12001,'Gaswerk'),
  (12002,'Gasbehaelter'),
  (12003,'Gasdruckregler'),
  (12004,'Gasstation'),
  (12005,'Gasleitung'),
  (13000,'Erdoelleitung'),
  (13001,'Bohrstelle'),
  (13002,'Erdoelpumptstation'),
  (13003,'Oeltank'),
  (14000,'Blockheizkraftwerk'),
  (14001,'Fernwaermeleitung'),
  (14002,'Fernheizwerk'),
  (16000,'Wasserwerk'),
  (16001,'Wasserleitung'),
  (16002,'Wasserspeicher'),
  (16003,'Brunnen'),
  (16004,'Pumpwerk'),
  (16005,'Quelle'),
  (18000,'Abwasser'),
  (18001,'Abwasserrueckhaltebecken'),
  (18002,'Abwasserpumpwerk'),
  (18003,'Klaeranlage'),
  (18004,'AnlageKlaerschlamm'),
  (18005,'SonstigeAbwasserBehandlungsanlage'),
  (18006,'SalzSoleeinleitungen'),
  (20000,'RegenwasserRueckhaltebecken'),
  (20001,'Niederschlagswasserleitung'),
  (22000,'Muellumladestation'),
  (22001,'Muellbeseitigungsanlage'),
  (22002,'Muellsortieranlage'),
  (22003,'Recyclinghof'),
  (24000,'Erdaushubdeponie'),
  (24001,'Bauschuttdeponie'),
  (24002,'Hausmuelldeponie'),
  (24003,'Sondermuelldeponie'),
  (24004,'StillgelegteDeponie'),
  (24005,'RekultivierteDeponie'),
  (26000,'Fernmeldeanlage'),
  (26001,'Mobilfunkstrecke'),
  (26002,'Fernmeldekabel'),
  (99990,'Produktenleitung');

ALTER TABLE xplan_gml.xp_gesetzlichegrundlage ADD COLUMN value text;

ALTER TABLE xplan_gml.xp_textabschnitt DROP COLUMN inverszu_reftextinhalt_xp_objekt;
ALTER TABLE xplan_gml.xp_textabschnitt DROP COLUMN inverszu_texte_xp_plan;
ALTER TABLE xplan_gml.xp_textabschnitt DROP COLUMN inverszu_abweichungtext_bp_baugebietsteilflaeche;
ALTER TABLE xplan_gml.xp_textabschnitt DROP COLUMN inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche;
ALTER TABLE xplan_gml.xp_textabschnitt ADD COLUMN inverszu_texte_xp_plan text;

ALTER TABLE xplan_gml.rp_textabschnitt ADD COLUMN inverszu_reftextinhalt_rp_objekt text[];
COMMENT ON COLUMN xplan_gml.rp_textabschnitt.inverszu_reftextinhalt_rp_objekt IS 'Assoziation zu: FeatureType RP_Objekt (rp_objekt) 0..*';

ALTER TABLE xplan_gml.xp_stylesheetliste ADD COLUMN value text;

ALTER TABLE xplan_gml.xp_begruendungabschnitt DROP COLUMN inverszu_begruendungstexte_xp_plan;
ALTER TABLE xplan_gml.xp_begruendungabschnitt ADD COLUMN inverszu_begruendungstexte_xp_plan text;
COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..1';

COMMIT;
