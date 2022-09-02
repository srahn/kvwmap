BEGIN;

  -- Fehlerkorrekturen der Version 5.1
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET beschreibung = 'Parkierungsflaeche -> Parkplatz' WHERE wert = 1000;

  -- Fehlerkorrekturen der Version 5.1.1
  -- Die folgenden Änderungen werden erst durchgeführt mit der Fehlerkorrektur der Version 5.2 weil die Attribute abgeleitet sind.
  -- Klasse BP_BaugebietsTeilFlaeche: Umbenennung Attribut DNzwingend > DNZwingend, abgeleitet von BP_GestaltungBaugebiet
  -- Klasse BP_BesondererNutzungszweckFlaeche: Umbenennung Attribut DNzwingend > DNZwingend, abgeleitet von BP_GestaltungBaugebiet
  -- Klasse BP_UeberbaubareGrundstuecksFlaeche: Umbenennung Attribut DNzwingend > DNZwingend, abgeleitet von BP_GestaltungBaugebiet
  -- Klasse BP_Dachgestaltung: Umbenennung Attribut DNZwingend > DNzwingend, wird direkt in Fehlerkorrektur 5.2 gemacht.

  -- Fehlerkorrekturen der Version 5.2, https://xleitstelle.de/downloads/xplanung/releases/XPlanung%20Version%205.2.1/%C3%84nderungen%20in%20Version%205.2.1.pdf
  UPDATE xplan_uml.uml_attributes a SET name = 'DNZwingend' FROM xplan_uml.uml_classes c WHERE a.uml_class_id = c.id AND c.name LIKE 'BP_GestaltungBaugebiet' AND a.name LIKE 'DNzwingend';
  UPDATE xplan_uml.uml_attributes a SET name = 'DNzwingend' FROM xplan_uml.uml_classes c WHERE a.uml_class_id = c.id AND c.name LIKE 'BP_Dachgestaltung' AND a.name LIKE 'DNZwingend';
  UPDATE xplan_uml.uml_classes c SET name = 'BP_Laermpegelbereich' WHERE name LIKE 'BP_Laerrmpegelbereich';
  ALTER TABLE IF EXISTS xplan_gml.enum_bp_laerrmpegelbereich RENAME TO enum_bp_laermpegelbereich;
--  ALTER TYPE xplan_gml.bp_laerrmpegelbereich RENAME TO bp_laermpegelbereich;
  COMMENT ON COLUMN xplan_gml.bp_immissionsschutz.laermpegelbereich IS 'laermpegelbereich enumeration BP_Laermpegelbereich 0..1';

  -- Änderung von XPlanGML 5.1.2 nach 5.2.1
  CREATE TYPE xplan_gml.bp_immissionsschutztypen AS ENUM
  ('1000', '2000');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_immissionsschutztypen (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_immissionsschutztypen_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_immissionsschutztypen IS 'Alias: "enum_BP_ImmissionsschutzTypen"';

  INSERT INTO xplan_gml.enum_bp_immissionsschutztypen (wert, abkuerzung, beschreibung) VALUES
  (1000, 'Schutzflaeche', 'Von der Bebauung freizuhaltende Schutzfläche" nach §9 Abs. 1 Nr. 24 BauGB'),
  (2000, 'Besondere Anlagen Vorkehrungen', 'Fläche für besondere Anlagen und Vorkehrungen zum Schutz vor schädlichen Umwelteinwirkungen" nach §9 Abs. 1 Nr. 24 BauGB');

  CREATE TYPE xplan_gml.bp_technvorkehrungenimmissionsschutz AS ENUM
  ('1000', '10000', '10001', '10002', '9999');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_technvorkehrungenimmissionsschutz (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_technvorkehrungenimmissionsschutz_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_technvorkehrungenimmissionsschutz IS 'Alias: "enum_BP_TechnVorkehrungenImmissionsschutz"';

  INSERT INTO xplan_gml.enum_bp_technvorkehrungenimmissionsschutz (wert, abkuerzung, beschreibung) VALUES
  (1000, 'Lärmschutzvorkehrung', 'Allgemeine Lärmschutzvorkehrung'),
  (10000, 'Fassaden mit Schallschutzmassnahmen', 'Fassaden mit Schallschutzmaßnahmen'),
  (10001, 'Lärmschutzwand', 'Lärmschutzwand'),
  (10002, 'Lärmschutzwall', 'Lärmschutzwall'),
  (9999,  'Sonstige Vorkehrung', 'Sonstige Vorkehrung zum Immissionsschutz');

  CREATE TABLE IF NOT EXISTS xplan_gml.bp_detailtechnvorkehrungimmissionsschutz (
    codespace text COLLATE pg_catalog."default",
    id character varying COLLATE pg_catalog."default" NOT NULL,
    value text COLLATE pg_catalog."default",
    CONSTRAINT bp_detailtechnvorkehrungimmissionsschutz_pkey PRIMARY KEY (id)
  );
  COMMENT ON TABLE xplan_gml.bp_detailtechnvorkehrungimmissionsschutz IS 'Alias: "BP_TechnVorkehrungenImmissionsschutz", UML-Typ: Code Liste';

  ALTER TABLE xplan_gml.bp_immissionsschutz
    ADD COLUMN typ xplan_gml.bp_immissionsschutztypen,
    ADD COLUMN technvorkehrung xplan_gml.bp_technvorkehrungenimmissionsschutz,
    ADD COLUMN detailliertetechnvorkehrungtyp xplan_gml.bp_detailtechnvorkehrungimmissionsschutz;

  -- Änderungen XPlanGML 5.3 > 5.4
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET beschreibung = 'Flächen für Ladeinfrastruktur elektrisch betriebener Fahrzeuge.' WHERE wert = 3500; -- Auch wenn der Wert schon so drin steht
  -- Alle anderen Änderungen wurden schon vorgenommen in Migration 2021-10-26_11-58-03_add_5_4_enums.sql
  -- Damit sind wir auf Version 5.4

  -- Änderungen XPlanGML 5.1.2 nach 5.4 entsprechend des Diff der xsd-Dateien
  -- BP_Objekt ist nicht mehr mit BP_TextAbschnitt verknüpft, sondern mit XP_TextAbschnitt
  ALTER TABLE xplan_gml.xp_textabschnitt ADD COLUMN inverszu_reftextinhalt_bp_objekt_neu text;
  UPDATE xplan_gml.bp_textabschnitt SET inverszu_reftextinhalt_bp_objekt_neu = inverszu_reftextinhalt_bp_objekt;
  ALTER TABLE xplan_gml.bp_textabschnitt DROP COLUMN inverszu_reftextinhalt_bp_objekt;
  ALTER TABLE xplan_gml.xp_textabschnitt RENAME COLUMN inverszu_reftextinhalt_bp_objekt_neu TO inverszu_reftextinhalt_bp_objekt;
  COMMENT ON COLUMN xplan_gml.bp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType XP_TextAbschnitt (xp_textabschnitt) 0..*';
  --Reverse SQL
  --ALTER TABLE xplan_gml.bp_textabschnitt ADD COLUMN inverszu_reftextinhalt_bp_objekt_alt text;
  --UPDATE xplan_gml.bp_textabschnitt SET inverszu_reftextinhalt_bp_objekt_alt = inverszu_reftextinhalt_bp_objekt;
  --ALTER TABLE xplan_gml.xp_textabschnitt DROP COLUMN inverszu_reftextinhalt_bp_objekt;
  --ALTER TABLE xplan_gml.bp_textabschnitt RENAME COLUMN inverszu_reftextinhalt_bp_objekt_alt TO inverszu_reftextinhalt_bp_objekt;

  -- Zusätzliches Packet BP_Laerm
  -- Neuer FeatureType BP_RichtungssektorGrenze
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_richtungssektorgrenze (
    winkel double precision,
    inverszu_richtungssektorgrenze_bp_objekt text
  )
  INHERITS (xplan_gml.bp_linienobjekt);
  COMMENT ON TABLE xplan_gml.bp_richtungssektorgrenze IS 'FeatureType: "BP_RichtungssektorGrenze", Linienhafte Repräsentation einer Richtungssektor-Grenze';
  COMMENT ON COLUMN xplan_gml.bp_richtungssektorgrenze.winkel IS 'winkel 0..1';
  COMMENT ON COLUMN xplan_gml.bp_richtungssektorgrenze.inverszu_richtungssektorgrenze_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';

  -- Neuer Datentyp BP_Richtungssektor
  CREATE TYPE xplan_gml.bp_richtungssektor AS (
    winkelanfang double precision,
    winkelende double precision,
    zkwerttag xplan_gml.measure,
    zkwertnacht xplan_gml.measure
  );
  COMMENT ON TYPE xplan_gml.bp_richtungssektor IS 'Alias: "BP_Richtungssektor", UML-Typ: DataType, Spezifikation von Zusatzkontingenten Tag/Nacht der Lärmemission für einen Richtungssektor';

  -- Neuer FeatureType BP_ZusatzkontingentLaerm
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_zusatzkontingentlaerm (
    bezeichnung character varying,
    richtungssektor xplan_gml.bp_richtungssektor[],
    inverszu_zusatzkontingent_bp_objekt text
  )
  INHERITS (xplan_gml.bp_punktobjekt);
  COMMENT ON TABLE xplan_gml.bp_zusatzkontingentlaerm IS 'FeatureType: "BP_ZusatzkontingentLaerm, Parametrische Spezifikation von zusätzlichen Lärmemissionskontingenten für einzelne Richtungssektoren (DIN 45691, Anhang 2)."';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaerm.bezeichnung IS 'bezeichnung 0..1';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaerm.richtungssektor IS 'richtungssektor FeatureType BP_Richtungssektor 0..*';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaerm.inverszu_zusatzkontingent_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';

  -- Neuer FeatureType BP_ZusatzkontingentLaermFlaeche
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_zusatzkontingentlaermflaeche (
    bezeichnung character varying,
    richtungssektor xplan_gml.bp_richtungssektor NOT NULL,
    inverszu_zusatzkontingentflaeche_bp_objekt text
  )
  INHERITS (xplan_gml.bp_ueberlagerungsobjekt);
  COMMENT ON TABLE xplan_gml.bp_zusatzkontingentlaermflaeche IS 'FeatureType: "BP_ZusatzkontingentLaermFlaeche, Flächenhafte Spezifikation von zusätzlichen Lärmemissionskontingenten für einzelne Richtungssektoren (DIN 45691, Anhang 2)."';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaermflaeche.bezeichnung IS 'bezeichnung 0..1';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaermflaeche.richtungssektor IS 'richtungssektor FeatureType BP_Richtungssektor 1';
  COMMENT ON COLUMN xplan_gml.bp_zusatzkontingentlaermflaeche.inverszu_zusatzkontingentflaeche_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';

  -- Neuer Datentyp BP_EmissionskontingentLaerm
  CREATE TYPE xplan_gml.bp_emissionskontingentlaerm AS (
    ekwerttag xplan_gml.measure,
    ekwertnacht xplan_gml.measure,
    erlaeuterung text
  );
  COMMENT ON TYPE xplan_gml.bp_emissionskontingentlaerm IS 'Alias: "BP_EmissionskontingentLaerm", UML-Typ: DataType, Lärmemissionskontingent eines Teilgebietes nach DIN 45691, Abschnitt 4.6';

  -- Neuer Datentyp BP_EmissionskontingentLaermGebiet
  CREATE TYPE xplan_gml.bp_emissionskontingentlaermgebiet AS (
    ekwerttag xplan_gml.measure,
    ekwertnacht xplan_gml.measure,
    erlaeuterung text,
    gebietsbezeichnung character varying
  );
  COMMENT ON TYPE xplan_gml.bp_emissionskontingentlaerm IS 'Alias: "BP_EmissionskontingentLaermGebiet", UML-Typ: DataType, Abgeleitet von Datentyp BP_EmissionskontingentLaerm, Lärmemissionskontingent eines Teilgebietes, das einem bestimmten Immissionsgebiet außerhalb des Geltungsbereiches des BPlans zugeordnet ist (Anhang A4 von DIN 45691)';

  -- Zusätzliche Attribute in Basisklasse BP_Objekt
  ALTER TABLE xplan_gml.bp_objekt
    ADD COLUMN laermkontingent xplan_gml.bp_emissionskontingentlaerm,
    ADD COLUMN laermkontingentgebiet xplan_gml.bp_emissionskontingentlaermgebiet[],
    ADD COLUMN zusatzkontingent text,
    ADD COLUMN zusatzkontingentflaeche text,
    ADD COLUMN richtungssektorgrenze text;
  COMMENT ON COLUMN xplan_gml.bp_objekt.zusatzkontingent IS 'Assoziation zu: FeatureType BP_ZusatzkontingentLaerm (bp_zusatzkontingentlaerm) 0..*';
  COMMENT ON COLUMN xplan_gml.bp_objekt.zusatzkontingentflaeche IS 'Assoziation zu: FeatureType BP_ZusatzkontingentLaermFlaeche (bp_zusatzkontingentlaermflaeche) 0..*';
  COMMENT ON COLUMN xplan_gml.bp_objekt.richtungssektorgrenze IS 'Assoziation zu: FeatureType BP_RichtungssektorGrenze (bp_richtungssektorgrenze) 0..*';

  -- Zusätzliche Attribute für BP_Plan
  ALTER TABLE xplan_gml.bp_plan
    ADD COLUMN veraenderungssperrebeschlussdatum date,
    ADD COLUMN veraenderungssperreenddatum date,
    ADD COLUMN verlaengerungveraenderungssperre xplan_gml.xp_verlaengerungveraenderungssperre;
  COMMENT ON COLUMN xplan_gml.bp_plan.veraenderungssperrebeschlussdatum IS 'veraenderungssperreBeschlussDatum Date 0..1';
  COMMENT ON COLUMN xplan_gml.bp_plan.veraenderungssperreenddatum IS 'veraenderungssperreEndDatum Date 0..1';
  COMMENT ON COLUMN xplan_gml.bp_plan.verlaengerungveraenderungssperre IS 'verlaengerungveraenderungssperre XP_VerlaengerungVeraenderungssperre 0..1';

  -- Geänderte Beschreibung zum Code-Wert 5000 und zusätzliche Codes in Aufzählung BP_Rechtsstand
  UPDATE xplan_gml.enum_bp_rechtsstand SET beschreibung = 'Der Plan wurde außer Kraft gesetzt.' WHERE wert = 5000;
  ALTER TYPE xplan_gml.bp_rechtsstand ADD VALUE '50000' AFTER '5000';
  ALTER TYPE xplan_gml.bp_rechtsstand ADD VALUE '50001' AFTER '50000';
  INSERT INTO xplan_gml.enum_bp_rechtsstand (wert, abkuerzung, beschreibung) VALUES
  (50000, 'Aufgehoben', 'Der Plan wurde durch ein förmliches Verfahren aufgehoben'),
  (50001, 'Ausser Kraft', 'Der Plan ist ohne förmliches Verfahren z.B. durch Überplanung außer Kraft getreten');

  -- Neuer FeatureType BP_AbweichungVonBaugrenze
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_abweichungvonbaugrenze (
  )
  INHERITS (xplan_gml.bp_linienobjekt);
  COMMENT ON TABLE xplan_gml.bp_abweichungvonbaugrenze IS 'FeatureType: "BP_AbweichungVonBaugrenze, Linienhafte Festlegung des Umfangs der Abweichung von der Baugrenze (§23 Abs. 3 Satz 3 BauNVO).';

  -- Neuer FeatureType BP_AbweichungVonUeberbaubererGrundstuecksFlaeche
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_abweichungvonueberbauberergrundstuecksflaeche (
  )
  INHERITS (xplan_gml.bp_ueberlagerungsobjekt);
  COMMENT ON TABLE xplan_gml.bp_abweichungvonueberbauberergrundstuecksflaeche IS 'FeatureType: "BP_AbweichungVonUeberbaubererGrundstuecksFlaeche, Flächenhafte Festlegung des Umfangs der Abweichung von der überbaubaren Grundstücksfläche (§23 Abs. 3 Satz 3 BauNVO).';

  -- Geänderter Kommentar an FeatureType BP_BaugebietsTeilFlaeche
  COMMENT ON TABLE xplan_gml.bp_baugebietsteilflaeche IS 'FeatureType: "BP_BaugebietsTeilFlaeche", Teil eines Baugebiets mit einheitlicher Art der baulichen Nutzung. Das Maß der baulichen Nutzung sowie Festsetzungen zur Bauweise oder Grenzbebauung können innerhalb einer BP_BaugebietsTeilFlaeche unterschiedlich sein (BP_UeberbaubareGrundstueckeFlaeche). Dabei sollte die gleichzeitige Belegung desselben Attributs in BP_BaugebietsTeilFlaeche und einem überlagernden Objekt BP_UeberbaubareGrunsdstuecksFlaeche verzichtet werden. Ab Version 6.0 wird dies evtl. durch eine Konformitätsregel erzwungen.';

  -- Neue Codeliste BP_DetailSondernutzung
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_detailsondernutzung (
    codespace text,
    id character varying NOT NULL,
    value text,
    CONSTRAINT bp_detailsondernutzung_pkey PRIMARY KEY (id)
  );
  COMMENT ON TABLE xplan_gml.bp_detailsondernutzung IS 'Alias: "BP_DetailSondernutzung", UML-Typ: Code Liste';

  COMMENT ON COLUMN xplan_gml.bp_abweichendebauweise.codespace
      IS 'codeSpace  text ';

  COMMENT ON COLUMN xplan_gml.bp_abweichendebauweise.id
      IS 'id  character varying ';

  -- Zusätzliche und geänderte Attribute an FeatureType BP_BaugebietsTeilFlaeche
  ALTER TABLE xplan_gml.bp_baugebietsteilflaeche
    ADD COLUMN mingrwohneinheit double precision,
    ADD COLUMN vf double precision,
    ALTER COLUMN sondernutzung TYPE xplan_gml.xp_sondernutzungen[] USING CASE WHEN sondernutzung IS NULL THEN NULL ELSE array[sondernutzung] END,
    ADD COLUMN detaillierteSondernutzung xplan_gml.bp_detailsondernutzung[];

  -- Add Enum Value to BP_BebauungsArt
  ALTER TYPE xplan_gml.bp_bebauungsart ADD VALUE '80000' AFTER '7000';
  INSERT INTO xplan_gml.enum_bp_bebauungsart (wert, abkuerzung, beschreibung) VALUES
  (8000, 'EinzelhaeuserDoppelhaeuserHausgruppen', 'Es sind Einzelhäuser, Doppelhäuser und Hausgruppen zulässig.');

  -- Add Attribute in Type BP_BesondererNutzungszweckFlaeche
  ALTER TABLE xplan_gml.bp_besonderernutzungszweckflaeche
    ADD COLUMN mingrwohneinheit double precision,
    ADD COLUMN bauweise xplan_gml.bp_bauweise,
    ADD COLUMN abweichendebauweise xplan_gml.bp_abweichendebauweise,
    ADD COLUMN bebauungsart xplan_gml.bp_bebauungsart;

  -- Anpassung Aufzählung Dachformen
  COMMENT ON TABLE xplan_gml.enum_bp_dachform IS 'Alias: "enum_BP_Dachform", Aufzählung verschiedener Dachformen.';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Flachdach, Empfohlene Abkürzung: FD' WHERE abkuerzung = 'Flachdach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Pultdach, Empfohlene Abkürzung: PD' WHERE abkuerzung = 'Pultdach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Versetztes Pultdach, Empfohlene Abkürzung: VPD' WHERE abkuerzung = 'Versetztes Pultdach';
  ALTER TYPE xplan_gml.bp_dachform ADD VALUE '3000' AFTER '2200';
  INSERT INTO xplan_gml.enum_bp_dachform (wert, abkuerzung, beschreibung) VALUES (3000, 'Geneigtes Dach', 'Kein Flachdach, Empfohlene Abkürzung: GD');
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Satteldach, Empfohlene Abkürzung: SD' WHERE abkuerzung = 'Satteldach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Walmdach, Empfohlene Abkürzung: WD' WHERE abkuerzung = 'Walmdach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Krüppelwalmdach, Empfohlene Abkürzung: KWD' WHERE abkuerzung = 'Krueppelwalmdach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Mansardendach, Empfohlene Abkürzung: MD' WHERE abkuerzung = 'Mansardendach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Zeltdach, Empfohlene Abkürzung: ZD' WHERE abkuerzung = 'Zeltdach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Kegeldach, Empfohlene Abkürzung: KeD' WHERE abkuerzung = 'Kegeldach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Kuppeldach, Empfohlene Abkürzung: KuD' WHERE abkuerzung = 'Kuppeldach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Sheddach, Empfohlene Abkürzung: ShD' WHERE abkuerzung = 'Sheddach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Bogendach, Empfohlene Abkürzung: BD' WHERE abkuerzung = 'Mansardendach';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Turmdach, Empfohlene Abkürzung: TuD' WHERE abkuerzung = 'Turmdach';
  ALTER TYPE xplan_gml.bp_dachform ADD VALUE '4100' AFTER '4000';
  INSERT INTO xplan_gml.enum_bp_dachform (wert, abkuerzung, beschreibung) VALUES (4100, 'Tonnendach', 'Tonnendach, Empfohlene Abkürzung: ToD');
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Gemischte Dachform, Empfohlene Abkürzung: GDF' WHERE abkuerzung = 'Mischform';
  UPDATE xplan_gml.enum_bp_dachform SET beschreibung = 'Sonstige Dachform, Empfohlene Abkürzung: SDF' WHERE abkuerzung = 'Sonstiges';

  -- Zusätzlicher Kommentar an FeatureType BP_NichtUeberbaubareGrundstuecksflaeche
  COMMENT ON TABLE xplan_gml.bp_nichtueberbaubaregrundstuecksflaeche IS 'FeatureType: "BP_NichtUeberbaubareGrundstuecksflaeche", Festlegung der nicht-überbaubaren Grundstücksfläche';

  -- Neue Codeliste BP_NutzungNichUueberbaubGrundstFlaeche
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_nutzungnichuueberbaubgrundstflaeche (
    codespace text,
    id character varying NOT NULL,
    value text,
    CONSTRAINT bp_nutzungnichuueberbaubgrundstflaeche_pkey PRIMARY KEY (id)
  );
  COMMENT ON TABLE xplan_gml.bp_nutzungnichuueberbaubgrundstflaeche IS 'Alias: "BP_NutzungNichUueberbaubGrundstFlaeche", UML-Typ: Code Liste';

  -- Zusätzliches Attribut im FeatureType BP_NichtUeberbaubareGrundstuecksflaeche
  ALTER TABLE xplan_gml.BP_NichtUeberbaubareGrundstuecksflaeche ADD COLUMN nutzung xplan_gml.bp_nutzungnichuueberbaubgrundstflaeche;

  -- Neue Enumeration BP_TypWohngebaeudeFlaeche
  CREATE TYPE xplan_gml.bp_typwohngebaeudeflaeche AS ENUM
  ('1000', '2000', '3000');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_typwohngebaeudeflaeche (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_typwohngebaeudeflaeche_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_typwohngebaeudeflaeche IS 'Alias: "enum_BP_TypWohngebaeudeFlaeche", Aufzählung verschiedener Typen von Wohngebäude-Flächen gemäß §9 Abs. 2d BauGB.';
  INSERT INTO xplan_gml.enum_bp_typwohngebaeudeflaeche (wert, abkuerzung, beschreibung) VALUES
  (1000, 'Wohngebäude', 'Flächen, auf denen Wohngebäude errichtet werden dürfen.'),
  (2000, 'Gebäudeförderung', 'Flächen, auf denen nur Gebäude errichtet werden dürfen, bei denen einzelne oder alle Wohnungen die baulichen Voraussetzungen für eine Förderung mit Mitteln der sozialen Wohnraumförderung erfüllen'),
  (3000, 'Gebäude städtebaulicher Vertrag', 'Flächen, auf denen nur Gebäude errichtet werden dürfen, bei denen sich ein Vorhabenträger hinsichtlich einzelner oder aller Wohnungen in einem städtebaulichen Vertrag verpflichtet, zum Zeitpunkt des Vertragsschlusses geltende Förderbedingungen der sozialen Wohnraumförderung, insbesondere die Mietpreisbindung, einzuhalten und die Einhaltung dieser Verpflichtung in geeigneter Weise sichergestellt wird');

  -- Geänderter Kommentar an FeatureType xplan_gml.bp_ueberbaubaregrundstuecksflaeche
  COMMENT ON TABLE xplan_gml.bp_ueberbaubaregrundstuecksflaeche IS 'FeatureType: "BP_UeberbaubareGrundstuecksFlaeche", Festsetzung der überbaubaren Grundstücksfläche (§9, Abs. 1, Nr. 2 BauGB). Über die Attribute geschossMin und geschossMax kann die Festsetzung auf einen Bereich von Geschossen beschränkt werden. Wenn eine Einschränkung der Festsetzung durch expliziter Höhenangaben erfolgen soll, ist dazu die Oberklassen-Relation hoehenangabe auf den komplexen Datentyp XP_Hoehenangabe zu verwenden. Die gleichzeitige Belegung desselben Attributs in BP_BaugebietsTeilFlaeche und einem überlagernden Objekt BP_UeberbaubareGrunsdstuecksFlaeche sollte verzichtet werden. Ab Version 6.0 wird dies evtl. durch eine Konformitätsregel erzwungen.';

  -- zusätzliche Attribute in FeatureType BP_UeberbaubareGrundstuecksFlaeche durch Typ BP_FestsetzungenBaugebiet
  ALTER TABLE xplan_gml.bp_ueberbaubaregrundstuecksflaeche
    ADD COLUMN mingrwohneinheit double precision,
    ADD COLUMN vf double precision;

  -- zusätzliches Attribut im FeatureType BP_BesondererNutzungszweckFlaeche
  ALTER TABLE xplan_gml.bp_besonderernutzungszweckflaeche ADD dachgestaltung xplan_gml.bp_dachgestaltung[];

  -- Neuer FeatureType BP_WohngebaeudeFlaeche
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_wohngebaeudeflaeche (
    dachgestaltung xplan_gml.bp_dachgestaltung[],
    dnmin double precision,
    dnmax double precision,
    dn double precision,
    dnzwingend double precision,
    fr double precision,
    dachform xplan_gml.bp_dachform[],
    detailliertedachform xplan_gml.bp_detaildachform[],
    maxzahlwohnungen integer,
    mingrwohneinheit double precision,
    fmin double precision,
    fmax double precision,
    bmin double precision,
    bmax double precision,
    tmin double precision,
    tmax double precision,
    gfzmin double precision,
    gfzmax double precision,
    gfz double precision,
    gfz_ausn double precision,
    gfmin double precision,
    gfmax double precision,
    gf double precision,
    gf_ausn double precision,
    bmz double precision,
    bmz_ausn double precision,
    bm double precision,
    bm_ausn double precision,
    grzmin double precision,
    grzmax double precision,
    grz double precision,
    grz_ausn double precision,
    grmin double precision,
    grmax double precision,
    gr double precision,
    gr_ausn double precision,
    zmin integer,
    zmax integer,
    zzwingend integer,
    z integer,
    z_ausn integer,
    z_staffel integer,
    z_dach integer,
    zumin integer,
    zumax integer,
    zuzwingend integer,
    zu integer,
    zu_ausn integer,
    wohnnutzungegstrasse xplan_gml.bp_zulaessigkeit,
    zwohn integer,
    gfantwohnen double precision,
    gfwohnen double precision,
    gfantgewerbe double precision,
    gfgewerbe double precision,
    vf double precision,
    typ xplan_gml.bp_typwohngebaeudeflaeche,
    abweichungbaunvo xplan_gml.xp_abweichungbaunvotypen,
    bauweise xplan_gml.bp_bauweise,
    abweichendeBauweise xplan_gml.bp_abweichendebauweise,
    vertikaledifferenzierung boolean DEFAULT false,
    bebauungsart xplan_gml.bp_bebauungsart,
    bebauungvorderegrenze xplan_gml.bp_grenzbebauung,
    bebauungrueckwaertigegrenze xplan_gml.bp_grenzbebauung,
    bebauungseitlichegrenze xplan_gml.bp_grenzbebauung,
    refgebaeudequerschnitt xplan_gml.xp_externereferenz[],
    zugunstenvon character varying
  )
  INHERITS (xplan_gml.bp_flaechenschlussobjekt);
  COMMENT ON TABLE xplan_gml.bp_wohngebaeudeflaeche IS 'FeatureType: "BP_UeberbaubareGrundstuecksFlaeche", Fläche für die Errichtung von Wohngebäuden in einem Bebauungsplan zur Wohnraumversorgung gemäß §9 Absatz 2d BauGB. Das Maß der baulichen Nutzung sowie Festsetzungen zur Bauweise oder Grenzbebauung können innerhalb einer BP_WohngebaeudeFlaeche unterschiedlich sein (BP_UeberbaubareGrundstueckeFlaech). Dabei sollte die gleichzeitige Belegung desselben Attributs in BP_WohngebaeudeFlaeche und einem überlagernden Objekt BP_UeberbaubareGrunsdstuecksFlaeche verzichtet werden. Ab Version 6.0 wird dies evtl. durch eine Konformitätsregel erzwungen.';

  -- Zusätzliche Attribute in FeatureType BP_GemeinbedarfsFlaeche
  ALTER TABLE xplan_gml.bp_gemeinbedarfsflaeche
    ADD COLUMN dachgestaltung xplan_gml.bp_dachgestaltung[],
    ADD COLUMN dnmin double precision,
    ADD COLUMN dnmax double precision,
    ADD COLUMN dn double precision,
    ADD COLUMN dnzwingend double precision,
    ADD COLUMN fr double precision,
    ADD COLUMN dachform xplan_gml.bp_dachform[],
    ADD COLUMN detailliertedachform xplan_gml.bp_detaildachform[],
    ADD COLUMN mingrwohneinheit double precision,
    ADD COLUMN bauweise xplan_gml.bp_bauweise,
    ADD COLUMN abweichendeBauweise xplan_gml.bp_abweichendebauweise,
    ADD COLUMN bebauungsart xplan_gml.bp_bebauungsart;

  -- Zusätzliche Attribute in FeatureType BP_SpielSportanlagenFlaeche
  ALTER TABLE xplan_gml.bp_spielsportanlagenflaeche
    ADD COLUMN mingrwohneinheit double precision;

  -- Zusätzliche Attribute in FeatureType BP_GruenFlaeche
  ALTER TABLE xplan_gml.bp_gruenflaeche
    ADD COLUMN mingrwohneinheit double precision;

  -- Neue Enumeration XP_EigentumsartWald
  CREATE TYPE xplan_gml.xp_eigentumsartwald AS ENUM
  ('1000', '1100', '1200', '12000', '12001', '2000', '20000', '20001', '3000', '9999');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_xp_eigentumsartwald (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_xp_eigentumsartwald_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_xp_eigentumsartwald IS 'Alias: "enum_xp_EigentumsartWald", Aufzählung der Waldeigentumsarten.';
  INSERT INTO xplan_gml.enum_xp_eigentumsartwald (wert, abkuerzung, beschreibung) VALUES
    (1000, 'OeffentlicherWald', 'Öffentlicher Wald allgemein'),
    (1100, 'Staatswald', 'Staatswald'),
    (1200, 'Koerperschaftswald', 'Körperschaftswald'),
    (12000, 'Kommunalwald', 'Kommunalwald'),
    (12001, 'Stiftungswald', 'Stiftungswald'),
    (2000, 'Privatwald', 'Privatwald allgemein'),
    (20000, 'Gemeinschaftswald', 'Gemeinschaftswald'),
    (20001, 'Genossenschaftswald', 'Genossenschaftswald'),
    (3000, 'Kirchenwald', 'Kirchenwald'),
    (9999, 'Sonstiges', 'Sonstiger Wald');

  -- Neue Enumeration XP_WaldbetretungTyp
  CREATE TYPE xplan_gml.xp_waldbetretungtyp AS ENUM
  ('1000', '2000', '3000', '4000');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_xp_waldbetretungtyp (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_xp_waldbetretungtyp_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_xp_waldbetretungtyp IS 'Alias: "enum_xp_waldbetretungtyp", Aufzählung der Waldbetretungstypen.';
  INSERT INTO xplan_gml.enum_xp_waldbetretungtyp (wert, abkuerzung, beschreibung) VALUES
    (1000, 'Radfahren', 'Radfahren'),
    (2000, 'Reiten', 'Reiten'),
    (3000, 'Fahren', 'Fahren'),
    (4000, 'Hundesport', 'Hundesport');

  -- Zusätzliche Attribute in FeatureType BP_WaldFlaeche
  ALTER TABLE xplan_gml.bp_waldflaeche
    ADD COLUMN eigentumsart xplan_gml.xp_eigentumsartwald,
    ADD COLUMN betreten xplan_gml.xp_waldbetretungtyp[];
    COMMENT ON TABLE xplan_gml.bp_waldflaeche IS 'FeatureType: "BP_WaldFlaeche", Festsetzung von Waldflächen  (§ 9, Abs. 1, Nr. 18b BauGB).';

    -- Änderung der Beschreibung von BP_AnpflanzungBindungErhaltung
  COMMENT ON TABLE xplan_gml.bp_anpflanzungbindungerhaltung IS 'FeatureType: "BP_AnpflanzungBindungErhaltung", Festsetzung des Anpflanzens von Bäumen, Sträuchern und sonstigen Bepflanzungen, Festsetzung von Bindungen für Bepflanzungen und für die Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen sowie von Gewässern, (§9 Abs. 1 Nr. 25 und Abs. 4 BauGB)';

  -- Änderung der Beschreibung BP_SchutzPflegeEntwicklungsFlaeche
  COMMENT ON TABLE xplan_gml.bp_schutzpflegeentwicklungsflaeche IS 'FeatureType: "BP_SchutzPflegeEntwicklungsFlaeche", Umgrenzung von Flächen für Maßnahmen zum Schutz, zur Pflege und zur Entwicklung von Natur und Landschaft (§9 Abs. 1 Nr. 20 und Abs. 4 BauGB)';

  -- Änderung der Beschreibung BP_SchutzPflegeEntwicklungsMassnahme
  COMMENT ON TABLE xplan_gml.bp_schutzpflegeentwicklungsmassnahme IS 'FeatureType: "BP_SchutzPflegeEntwicklungsMassnahme", Maßnahmen zum Schutz, zur Pflege und zur Entwicklung von Natur und Landschaft (§9 Abs. 1 Nr. 20 und Abs. 4 BauGB).';

  -- Änderung der Aufzählung BP_AbgrenzungenTypen
  UPDATE xplan_gml.enum_bp_abgrenzungentypen SET beschreibung = 'Abgrenzung von Bereichen mit unterschiedlichen Festsetzungen zur Gebäudehöhe und/oder Zahl der Vollgeschosse.' WHERE wert = 2000;

  -- Änderung der Beschreibung BP_AbstandsMass
  COMMENT ON TABLE xplan_gml.bp_abstandsmass IS 'FeatureType: "BP_AbstandsMass", Darstellung von Maßpfeilen oder Maßkreisen in BPlänen, um eine eindeutige Vermassung einzelner Festsetzungen zu erreichen. Bei Masspfeilen (typ == 1000) sollte das Geometrie-Attribut position nur eine einfache Linien (gml:LineString mit 2 Punkten) enthalten. Bei Maßkreisen (typ == 2000) sollte position nur einen einfachen Kreisbogen (gml:Curve mit genau einem gml:Arc enthalten. In der nächsten Hauptversion von XPlanGML werden diese Empfehlungen zu verpflichtenden Konformitätsbedingungen.';

  -- zusätzlicher Aufzählungstyp BP_AbstandsMassTypen
  -- Neue Enumeration bp_abstandsmasstypen
  CREATE TYPE xplan_gml.bp_abstandsmasstypen AS ENUM
  ('1000', '2000');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_abstandsmasstypen (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_abstandsmasstypen_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_abstandsmasstypen IS 'Alias: "enum_bp_abstandsmasstypen", Aufzählung der Abstandsmasstypen.';
  INSERT INTO xplan_gml.enum_bp_abstandsmasstypen (wert, abkuerzung, beschreibung) VALUES
    (1000, 'Masspfeil', 'Das Objekt definiert einen Maßpfeil'),
    (2000, 'Masskreis', 'Das Objekt definiert einen Maßkreis');

  -- zusätzliches Attribut beim FeatureType typ: BP_AbstandsMass
  ALTER TABLE xplan_gml.bp_abstandsmass
    ADD COLUMN typ xplan_gml.bp_abstandsmasstypen,
    ALTER COLUMN wert TYPE xplan_gml.measure USING ROW(wert)::xplan_gml.measure,
    ALTER COLUMN wert DROP NOT NULL;

  -- Neuer FeatureType BP_FlaecheOhneFestsetzung
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_flaecheohnefestsetzung (
  )
  INHERITS (xplan_gml.bp_flaechenschlussobjekt);
  COMMENT ON TABLE xplan_gml.bp_flaecheohnefestsetzung IS 'FeatureType: "BP_FlaecheOhneFestsetzung", Fläche, für die keine geplante Nutzung angegeben werden kann';

  -- Zusätzliche Attribute in FeatureType BP_KennzeichnungsFlaeche
  ALTER TABLE xplan_gml.bp_kennzeichnungsflaeche
    ADD COLUMN istVerdachtsflaeche boolean DEFAULT false,
    ADD COLUMN nummer character varying;

  -- zusätzlicher Aufzählungstyp BP_SichtflaecheArt
  -- Neue Enumeration bp_sichtflaecheart
  CREATE TYPE xplan_gml.bp_sichtflaecheart AS ENUM
  ('1000', '2000', '3000', '4000', '9999');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_sichtflaecheart (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_sichtflaecheart_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_sichtflaecheart IS 'Alias: "enum_bp_sichtflaecheart", Aufzählung der Typen von Sichtflächen.';
  INSERT INTO xplan_gml.enum_bp_sichtflaecheart (wert, abkuerzung, beschreibung) VALUES
    (1000, 'Haltesichtweite', 'Haltesichtweite'),
    (2000, 'Anfahrsichtfeld', 'Anfahrsichtfeld'),
    (3000, 'Annäherungssichtfeld', 'Annaeherungssichtfeld'),
    (4000, 'Überquerung', 'Sichtfeld an Überquerungsstellen'),
    (9999, 'Sonstige Sichtfläche', 'Sonstige Sichtfläche');

  -- Neue Enumeration bp_sichtflaecheknotenpunkttypen
  CREATE TYPE xplan_gml.bp_sichtflaecheknotenpunkttypen AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '6000', '9999');

  CREATE TABLE IF NOT EXISTS xplan_gml.enum_bp_sichtflaecheknotenpunkttypen (
    wert integer NOT NULL,
    abkuerzung character varying,
    beschreibung character varying,
    CONSTRAINT enum_bp_sichtflaecheknotenpunkttypen_pkey PRIMARY KEY (wert)
  );
  COMMENT ON TABLE xplan_gml.enum_bp_sichtflaecheknotenpunkttypen IS 'Alias: "enum_bp_sichtflaecheknotenpunkttypen", Aufzählung der Typen von Sichtflächenknotenpunkttypen.';
  INSERT INTO xplan_gml.enum_bp_sichtflaecheknotenpunkttypen (wert, abkuerzung, beschreibung) VALUES
    (1000, 'AnlgStr-AnlgWeg', 'Knotenpunkt Anliegerstraße - Anliegerweg'),
    (2000, 'AnlgStr-AnlgStr', 'Knotenpunkt Anliegerstraße - Anliegerstraße'),
    (3000, 'SammelStr-AnlgStr', 'Knotenpunkt Sammelstraße - Anliegerstraße'),
    (4000, 'HauptSammelStr', 'Knotenpunkt mit einer Haupt-Sammelstraße'),
    (5000, 'HauptVerkStrAngeb', 'Knotenpunkt mit einer angebaute Hauptverkehrsstraße (Bebauung parallel zur Straße ist vorhanden)'),
    (6000, 'HauptVerkStrNichtAngeb', 'Knotenpunkt mit einer nicht angebaute Hauptverkehrsstraße (Keine Bebauung parallel zur Straße)'),
    (9999, 'SonstigerKnotenpunkt', 'Sonstiger Knotenpunkt');

  -- Neuer FeatureType BP_Sichtflaeche
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_sichtflaeche (
    art xplan_gml.bp_sichtflaecheart,
    knotenpunkt xplan_gml.bp_sichtflaecheknotenpunkttypen,
    geschwindigkeit xplan_gml.measure,
    schenkellaenge double precision
  )
  INHERITS (xplan_gml.bp_ueberlagerungsobjekt);
  COMMENT ON TABLE xplan_gml.bp_sichtflaeche IS 'FeatureType: "BP_Sichtflaeche", Flächenhafte Festlegung einer Sichtfläche bzw. eines Sichtdreiecks. In Version 6.0 wird diese Klasse evtl. in der Modellbereich "Sonstige Planwerke" transferiert.';

  -- Zusätzliche Attribute für BP_Veraenderungssperre
  ALTER TABLE xplan_gml.BP_Veraenderungssperre
    ADD COLUMN veraenderungssperrebeschlussdatum date,
    ADD COLUMN veraenderungssperreStartDatum date;

  -- geänderte Beschreibungen im Aufzählungstyp BP_WegerechtTypen
  UPDATE xplan_gml.enum_bp_wegerechttypen SET beschreibung = 'Radfahrrecht' WHERE wert = 2500;
  UPDATE xplan_gml.enum_bp_wegerechttypen SET beschreibung = 'Sonstiges Nutzungsrecht' WHERE wert = 9999;

  -- zusätzliches Attibut für FeatureType BP_VerEntsorgung
  ALTER TABLE xplan_gml.bp_verentsorgung
    ADD COLUMN MinGRWohneinheit double precision;

  -- Neuer FeatureType BP_ZentralerVersorgungsbereich
  CREATE TABLE IF NOT EXISTS xplan_gml.bp_zentralerversorgungsbereich (
  )
  INHERITS (xplan_gml.bp_ueberlagerungsobjekt);
  COMMENT ON TABLE xplan_gml.bp_zentralerversorgungsbereich IS 'FeatureType: "BP_ZentralerVersorgungsbereich", Zentraler Versorgungsbereich gem. § 9 Abs. 2a BauGB';

  -- zusätzliches Attibut für FeatureType BP_StrassenVerkehrsFlaeche
  ALTER TABLE xplan_gml.bp_strassenverkehrsflaeche
    ADD COLUMN MinGRWohneinheit double precision;

  -- zusätzliches Attibut für FeatureType BP_VerkehrsflaecheBesondererZweckbestimmung
  ALTER TABLE xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung
    ADD COLUMN MinGRWohneinheit double precision;

  -- geänderte Attribute für FeatureType BP_VerkehrsflaecheBesondererZweckbestimmung
  ALTER TABLE xplan_gml.bp_verkehrsflaechebesondererzweckbestimmung
    ALTER COLUMN zweckbestimmung TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr[] USING CASE WHEN zweckbestimmung IS NULL THEN NULL ELSE array[zweckbestimmung] END,
    ALTER COLUMN detaillierteZweckbestimmung TYPE xplan_gml.bp_detailzweckbeststrassenverkehr[] USING CASE WHEN detaillierteZweckbestimmung IS NULL THEN NULL ELSE array[detaillierteZweckbestimmung] END;

  -- geänderte Werte für Aufzählung BP_ZweckbestimmungStrassenverkehr
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET abkuerzung = 'Parkierungsflaeche' WHERE wert = 1000;
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET abkuerzung = 'RadGehweg' WHERE wert = 1300;
  UPDATE xplan_gml.enum_bp_zweckbestimmungstrassenverkehr SET abkuerzung = 'Gehweg' WHERE wert = 1500;

  -- Geänderte Beschreibung des FeatureType BP_GewaesserFlaeche
  COMMENT ON TABLE xplan_gml.bp_gewaesserflaeche IS 'FeatureType: "BP_GewaesserFlaeche", Festsetzung neuer Wasserflächen nach §9 Abs. 1 Nr. 16a BauGB. Diese Klasse wird in der nächsten Hauptversion des Standards eventuell wegfallen und durch SO_Gewaesser ersetzt werden.';

  -- Geänderte Beschreibung des FeatureType BP_WasserwirtschaftsFlaeche
  COMMENT ON TABLE xplan_gml.bp_wasserwirtschaftsflaeche IS 'FeatureType: "BP_WasserwirtschaftsFlaeche", Flächen für die Wasserwirtschaft (§9 Abs. 1 Nr. 16a BauGB), sowie Flächen für Hochwasserschutz-anlagen und für die Regelung des Wasserabflusses (§9 Abs. 1 Nr. 16b BauGB).';


  -- Zusätzliche Aufzählung in enum XP_ArtHoehenbezug
  ALTER TYPE xplan_gml.xp_arthoehenbezug ADD VALUE '3500' AFTER '3000';
  INSERT INTO xplan_gml.enum_xp_arthoehenbezug (wert, abkuerzung, beschreibung) VALUES
  (3500, 'relativStrasse', 'Höhenangabe relativ zur Strassenoberkante an der Position des Planinhalts');

  -- Zusätzliche Aufzählung in enum XP_ArtHoehenbezugspunkt
  ALTER TYPE xplan_gml.xp_arthoehenbezugspunkt ADD VALUE '6500' AFTER '6000';
  ALTER TYPE xplan_gml.xp_arthoehenbezugspunkt ADD VALUE '6600' AFTER '6500';
  INSERT INTO xplan_gml.enum_xp_arthoehenbezugspunkt (wert, abkuerzung, beschreibung) VALUES
  (6500, 'WH', 'Wandhöhe'),
  (6600, 'GOK' , 'Geländeoberkante');

  -- Zusätzliche Aufzählung in enum XP_RechtscharakterPlanaenderung
  ALTER TYPE xplan_gml.xp_rechtscharakterplanaenderung ADD VALUE '20000' AFTER '2000';
  ALTER TYPE xplan_gml.xp_rechtscharakterplanaenderung ADD VALUE '20001' AFTER '20000';
  INSERT INTO xplan_gml.enum_xp_rechtscharakterplanaenderung (wert, abkuerzung, beschreibung) VALUES
  (20000, 'Aufhebungsverfahren', 'Das altes Planrecht wurde durch ein förmliches Verfahren aufgehoben'),
  (20001, 'Ueberplanung' , 'Der alte Plan tritt ohne förmliches Verfahren außer Kraft');

  -- geänderte Beschreibung in enum XP_ABEMassnahmenTypen
  UPDATE xplan_gml.enum_xp_abemassnahmentypen SET beschreibung = 'Bindungen für Bepflanzungen und für die Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen sowie von Gewässern. Dies entspricht dem Planzeichen 13.2.2 der PlanzV 1990.' WHERE wert = 1000;
  UPDATE xplan_gml.enum_xp_abemassnahmentypen SET beschreibung = 'Anpflanzung von Bäumen, Sträuchern oder sonstigen Bepflanzungen. Dies entspricht dem Planzeichen 13.2.1 der PlanzV 1990.' WHERE wert = 2000;
  UPDATE xplan_gml.enum_xp_abemassnahmentypen SET beschreibung = 'Anpflanzen von Bäumen, Sträuchern und sonstigen Bepflanzungen, sowie Bindungen für Bepflanzungen und für die Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen sowie von Gewässern' WHERE wert = 3000;

  -- geänderte Beschreibungen in enum XP_Sondernutzungen
  UPDATE xplan_gml.enum_xp_sondernutzungen SET beschreibung = replace(beschreibung, ' nach §10 der BauNVO 1977 und 1990', '');
  UPDATE xplan_gml.enum_xp_sondernutzungen SET beschreibung = replace(beschreibung, ' nach §11 der BauNVO 1977 und 1990', '');
  -- Neue Aufzählung in enum XP_Sondernutzungen
  ALTER TYPE xplan_gml.xp_sondernutzungen ADD VALUE '23000' AFTER '2300';
  ALTER TYPE xplan_gml.xp_sondernutzungen ADD VALUE '2720' AFTER '2700';
  INSERT INTO xplan_gml.enum_xp_sondernutzungen (wert, abkuerzung, beschreibung) VALUES
    (23000, 'Klinikgebiet', 'Klinikgebiet'),
    (2720, 'SondergebietJustiz', 'Sondergebiet für Einrichtungen der Justiz');

  -- geänderte Beschreibung in enum XP_ZweckbestimmungGemeinbedarf
  UPDATE xplan_gml.enum_xp_zweckbestimmunggemeinbedarf SET beschreibung = 'Religiöse Einrichtung' WHERE wert = 1400;
  UPDATE xplan_gml.enum_xp_zweckbestimmunggemeinbedarf SET beschreibung = 'Religiöses Verwaltungsgebäude, z. B. Pfarramt, Bischöfliches Ordinariat, Konsistorium.' WHERE wert = 14001;
  ALTER TYPE xplan_gml.xp_zweckbestimmunggemeinbedarf ADD VALUE '16005' AFTER '16004';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmunggemeinbedarf (wert, abkuerzung, beschreibung) VALUES
    (16005, 'EinrichtungBehinderte', 'Soziale Einrichtung für Menschen mit Beeinträchtigung, wie z. B. Behindertentagesstätte, Behindertenwohnheim, Behindertenwerkstatt');

  -- zusätzliche Aufzählung in enum XP_ZweckbestimmungGewaesser
  ALTER TYPE xplan_gml.xp_zweckbestimmunggewaesser ADD VALUE '10000' AFTER '1000';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmunggewaesser (wert, abkuerzung, beschreibung) VALUES
    (10000, 'Sportboothafen', 'Sportboothafen');

  -- geänderte Beschreibungen in enum XP_ZweckbestimmungKennzeichnung
  UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung SET beschreibung = 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen Naturgewalten erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).' WHERE wert = 1000;
  UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung SET beschreibung = 'Flächen, die für den Abbau von Mineralien bestimmt sind (§5, Abs. 3, Nr. 2 und §9, Abs. 5, Nr. 2. BauGB).' WHERE wert = 2000;
  UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung SET beschreibung = 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen äußere Einwirkungen erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).' WHERE wert = 3000;
  UPDATE xplan_gml.enum_xp_zweckbestimmungkennzeichnung SET beschreibung = 'Für bauliche Nutzung vorgesehene Flächen, deren Böden erheblich mit umweltgefährdenden Stoffen belastet sind (§5, Abs. 3, Nr. 3 BauGB).' WHERE wert = 4000;

  -- zusätzliche Aufzählungen in XP_ZweckbestimmungVerEntsorgung
  ALTER TYPE xplan_gml.xp_zweckbestimmungverentsorgung ADD VALUE '100011' AFTER '100010';
  ALTER TYPE xplan_gml.xp_zweckbestimmungverentsorgung ADD VALUE '100012' AFTER '100011';
  ALTER TYPE xplan_gml.xp_zweckbestimmungverentsorgung ADD VALUE '100013' AFTER '100012';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmungverentsorgung (wert, abkuerzung, beschreibung) VALUES
    (100011, 'Kernkraftwerk', 'Kernkraftwerk'),
    (100012, 'Kohlekraftwerk', 'Kohlekraftwerk'),
    (100013, 'Gaskraftwerk', 'Gaskraftwerk');
  UPDATE xplan_gml.enum_xp_zweckbestimmungverentsorgung SET abkuerzung = 'Mobilfunkanlage', beschreibung = 'Mobilfunkanlage' WHERE wert = 26001;

  -- Neue werte für Aufzählung XP_ZweckbestimmungWald
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '10000' AFTER '1000';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '16000' AFTER '1600';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '16001' AFTER '16000';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '16002' AFTER '16001';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '16003' AFTER '16002';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '1700' AFTER '1600';
  ALTER TYPE xplan_gml.xp_zweckbestimmungwald ADD VALUE '1900' AFTER '1800';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmungwald (wert, abkuerzung, beschreibung) VALUES
    (10000, 'Waldschutzgebiet', 'Waldschutzgebiet'),
    (16000, 'Bodenschutzwald', 'Bodenschutzwald'),
    (16001, 'Biotopschutzwald', 'Biotopschutzwald'),
    (16002, 'NaturnaherWald', 'Naturnaher Wald'),
    (16003, 'SchutzwaldSchaedlicheUmwelteinwirkungen', 'Wald zum Schutz vor schädlichen Umwelteinwirkungen'),
    (16004, 'Schonwald', 'Schonwald'),
    (1700, 'Bannwald', 'Fläche für die Forstwirtschaft.'),
    (1900, 'ImmissionsgeschaedigterWald', 'Immissionsgeschädigter Wald');
  UPDATE xplan_gml.enum_xp_zweckbestimmungwald SET beschreibung = 'Sonstigr Wald' WHERE wert = 9999;

  -- geänderte Beschreibung enum XP_ZweckbestimmungWasserwirtschaft
  UPDATE xplan_gml.enum_xp_zweckbestimmungwasserwirtschaft SET beschreibung = 'Überschwemmungsgefährdetes Gebiet nach §31c des vor dem 1.10.2010 gültigen WHG' WHERE wert = 1100;
  ALTER TYPE xplan_gml.xp_zweckbestimmungwasserwirtschaft ADD VALUE '1500' AFTER '1400';
  INSERT INTO xplan_gml.enum_xp_zweckbestimmungwasserwirtschaft (wert, abkuerzung, beschreibung) VALUES
    (1500, 'RegenRueckhaltebecken', 'Regen-Rückhaltebecken');

COMMIT;