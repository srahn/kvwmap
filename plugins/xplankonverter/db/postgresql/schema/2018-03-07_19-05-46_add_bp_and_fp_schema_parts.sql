BEGIN;


-- Version vom 16.11.2017 10:37
-- gewählte Pakete: 'XPlanGML 5.0', 'Basisklassen', 'Bebauungsplan', 'BP_Aufschuettung_Abgrabung_Bodenschaetze', 'BP__Basisobjekte', 'BP_Bebauung', 'BP_Erhaltungssatzung_und_Denkmalschutz', 'BP_Gemeinbedarf_Spiel_und_Sportanlagen', 'BP_Landwirtschaft, Wald- und Grünflächen', 'BP_Naturschutz_Landschaftsbild_Naturhaushalt', 'BP_Raster', 'BP_Sonstiges', 'BP_Umwelt', 'BP_Verkehr', 'BP_Ver_und_Entsorgung', 'BP_Wasser', 'Flaechennutzungsplan', 'FP_Aufschuettung_Abgrabung_Bodenschaetze', 'FP__Basisobjekte', 'FP_Bebauung', 'FP_Gemeinbedarf_Spiel_und_Sportanlagen', 'FP_Landwirtschaft_Wald_und_Gruen', 'FP_Naturschutz', 'FP_Raster', 'FP_Sonstiges', 'FP_Verkehr', 'FP_Ver- und Entsorgung', 'FP_Wasser', 'SO_Basisobjekte', 'SO_NachrichtlicheUebernahmen', 'SonstigePlanwerke', 'SO_Raster', 'SO_Schutzgebiete', 'SO_SonstigeGebiete', 'SO_Sonstiges', 'XP_Basisobjekte', 'XP_Enumerationen', 'XP_Praesentationsobjekte', 'XP_Raster'
-- gewählte Filter: Ohne Attribute objektkoordinaten.
COMMENT ON SCHEMA xplan_gml IS 'Version vom 16.11.2017 10:37';
SET search_path = xplan_gml, public;

DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_arthoehenbezugspunkt'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_arthoehenbezugspunkt AS ENUM
  ('1000', '2000', '3000', '3500', '4000', '4500', '5000', '5500', '6000');
END IF;
END$$;

ALTER TABLE enum_xp_arthoehenbezugspunkt ADD abkuerzung character varying;

COMMENT ON TABLE enum_xp_arthoehenbezugspunkt IS 'Alias: "enum_XP_ArtHoehenbezugspunkt"';
TRUNCATE enum_xp_arthoehenbezugspunkt;
INSERT INTO enum_xp_arthoehenbezugspunkt (wert,abkuerzung,beschreibung) VALUES
('1000', 'TH', 'Traufhöhe als Höhenbezugspunkt'),
('2000', 'FH', 'Firsthöhe als Höhenbezugspunkt.'),
('3000', 'OK', 'Oberkante als Höhenbezugspunkt.'),
('3500', 'LH', 'Lichte Höhe'),
('4000', 'SH', 'Sockelhöhe'),
('4500', 'EFH', 'Erdgeschoss Fußbodenhöhe'),
('5000', 'HBA', 'Höhe Baulicher Anlagen'),
('5500', 'UK', 'Unterkante'),
('6000', 'GBH', 'Gebäudehöhe');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_externereferenzart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_externereferenzart AS ENUM
  ('Dokument', 'PlanMitGeoreferenz');
END IF;
END$$;

DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_spemassnahmentypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_spemassnahmentypen AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '1700', '1800', '1900', '2000', '2100', '2200', '2300', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_spemassnahmentypen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_spemassnahmentypen IS 'Alias: "enum_XP_SPEMassnahmenTypen"';
TRUNCATE enum_xp_spemassnahmentypen;
INSERT INTO enum_xp_spemassnahmentypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'ArtentreicherGehoelzbestand', 'Artenreicher Gehölzbestand ist aus unterschiedlichen, standortgerechten Gehölzarten aufgebaut und weist einen Strauchanteil auf.'),
('1100', 'NaturnaherWald', 'Naturnahe Wälder zeichnen sich durch eine standortgemäße Gehölzzusammensetzung unterschiedlicher Altersstufen, durch eine Schichtung der Gehölze (z.B. Strauchschicht, sich überlagernder erster Baumschicht in 10-15 m Höhe und zweiter Baumschicht in 20-25 m Höhe) sowie durch eine in der Regeln artenreiche Krautschicht aus. Kennzeichnend sind zudem das gleichzeitige Nebeneinander von aufwachsenden Gehölzen, Altbäumen und Lichtungen in kleinräumigen Wechsel sowie ein gewisser Totholzanteil.'),
('1200', 'ExtensivesGruenland', 'Gegenüber einer intensiven Nutzung sind bei extensiver Grünlandnutzung sowohl Beweidungsintensitäten als auch der Düngereinsatz deutlich geringer. Als Folge finden eine Reihe von eher konkurrenzschwachen, oft auch trittempflindlichen Pflanzenarten Möglichkeiten, sich neben den in der Regel sehr robusten, wuchskräftigen, jedoch sehr nährstoffbedürftigen Pflanzen intensiver Wirtschaftsflächen zu behaupten.  Dadurch kommt es zur Ausprägung von standortbedingt unterschiedlichen Grünlandgesellschaften mit deutlichen höheren Artenzahlen (größere Vielfalt).'),
('1300', 'Feuchtgruenland', 'Artenreiches Feuchtgrünland entwickelt sich bei extensiver Bewirtschaftung auf feuchten bis wechselnassen Standorten. Die geringe Tragfähigkeit des vielfach anstehenden Niedermoorbodens erschwert den Einsatz von Maschinen, so dass die Flächen vorwiegend beweidet bzw. erst spät im Jahr gemäht werden.'),
('1400', 'Obstwiese', 'Obstwiesen umfassen mittel- oder hochstämmige, großkronige Obstbäume auf beweidetem (Obstweide) oder gemähtem (obstwiese) Grünland. Im Optimalfall setzt sich der aufgelockerte Baumbestand aus verschiedenen, möglichst alten, regional-typischen Kultursorten zusammen.'),
('1500', 'NaturnaherUferbereich', 'Naturahne Uferbereiche umfassen unterschiedlich zusammengesetzte Röhrichte und Hochstaudenrieder oder Seggen-Gesellschaften sowie Ufergehölze, die sich vorwiegend aus strauch- oder baumförmigen Weiden, Erlen oder Eschen zusammensetzen.'),
('1600', 'Roehrichtzone', 'Im flachen Wasser oder auf nassen Böden bilden sich hochwüchsige, oft artenarme Bestände aus überwiegend windblütigen Röhrichtarten aus. Naturliche Bestände finden sich im Uferbereich von Still- und Fließgewässern.'),
('1700', 'Ackerrandstreifen', 'Ackerrandstreifen sind breite Streifen im Randbereich eines konventionell oder ökologisch genutzten Ackerschlages.'),
('1800', 'Ackerbrache', 'Als Ackerbrachflächen werden solche Biotope angesprochen, die seit kurzer Zeit aus der Nutzung herausgenommen worden sind. Sie entstehen, indem Ackerflächen mindestens eine Vegetationsperiode nicht mehr bewirtschaftet werden.'),
('1900', 'Gruenlandbrache', 'Als Grünlandbrachen werden solche Biotope angesprochen, die seit kurzer Zeit aus der Nutzung herausgenommen worden sind. Sie entstehen, indem Grünland mindestens eine Vegetationsperiode nicht mehr bewirtschaftet wird.'),
('2000', 'Sukzessionsflaeche', 'Sukzessionsflächen umfassen dauerhaft ungenutzte, der natürlichen Entwicklung überlassene Vegetationsbestände auf trockenen bis feuchten Standorten.'),
('2100', 'Hochstaudenflur', 'Hochwüchsige, zumeist artenreiche Staudenfluren feuchter bis nasser Standorte entwickeln sich in der Regel auf Feuchtgrünland-Brachen, an gehölzfreien Uferstreifen oder an anderen zeitweilig gestörten Standorten mit hohen Grundwasserständen.'),
('2200', 'Trockenrasen', 'Trockenrasen sind durch zumindest zeitweilige extreme Trockenheit (Regelwasser versickert rasch) sowie durch Nährstoffarmut charakterisiert, die nur Arten mit speziell angepassten Lebensstrategien Entwicklungsmöglichkeiten bieten.'),
('2300', 'Heide', 'Heiden sind Zwergstrauchgesellschaften auf nährstoffarmen, sauren, trockenen (Calluna-Heide) oder feuchten (Erica-Heide) Standorten. Im Binnenland haben sie in der Regel nach Entwaldung (Abholzung) und langer Übernutzung (Beweidung) primär nährstoffarmer Standorte entwickelt.'),
('9999', 'Sonstiges', 'Sonstiges');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_rechtsstand'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_rechtsstand AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

ALTER TABLE enum_xp_rechtsstand ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_rechtsstand IS 'Alias: "enum_XP_Rechtsstand"';
TRUNCATE enum_xp_rechtsstand;
INSERT INTO enum_xp_rechtsstand (wert,abkuerzung,beschreibung) VALUES
('1000', 'Geplant', 'Der Planinhalt bezieht sich auf eine Planung'),
('2000', 'Bestehend', 'Der Planinhalt stellt den aktuellen Zustand dar.'),
('3000', 'Fortfallend', 'Der Planinhalt beschreibt einen zukünftig fortfallenden Zustand.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_arthoehenbezug'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_arthoehenbezug AS ENUM
  ('1000', '2000', '2500', '3000');
END IF;
END$$;

ALTER TABLE enum_xp_arthoehenbezug ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_arthoehenbezug IS 'Alias: "enum_XP_ArtHoehenbezug"';
TRUNCATE enum_xp_arthoehenbezug;
INSERT INTO enum_xp_arthoehenbezug (wert,abkuerzung,beschreibung) VALUES
('1000', 'absolutNHN', 'Absolute Höhenangabe'),
('2000', 'relativGelaendeoberkante', 'Höhenangabe relativ zur Geländeoberkante an der Position des Planinhalts.'),
('2500', 'relativGehwegOberkante', 'Höhenangabe relativ zur Gehweg-Oberkante an der Position des Planinhalts.'),
('3000', 'relativBezugshoehe', 'Höhenangabe relativ zu der auf Planebene festgelegten absoluten Bezugshöhe (Attribut <i>bezugshoehe </i>von <i>XP_Plan</i>).');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_rechtscharakterplanaenderung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_rechtscharakterplanaenderung AS ENUM
  ('1000', '1100', '2000');
END IF;
END$$;

ALTER TABLE enum_xp_rechtscharakterplanaenderung ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_rechtscharakterplanaenderung IS 'Alias: "enum_XP_RechtscharakterPlanaenderung"';
TRUNCATE enum_xp_rechtscharakterplanaenderung;
INSERT INTO enum_xp_rechtscharakterplanaenderung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Aenderung', '<b>Änderung </b>eines Planes: Der Geltungsbereich des neueren Plans überdeckt nicht den gesamten Geltungsbereich des Ausgangsplans. Im Überlappungsbereich gilt das neuere Planrecht.'),
('1100', 'Ergaenzung', '<b>Ergänzung </b>eines Plans: Die Inhalte des neuen Plans ergänzen die alten Inhalte, z.B. durch zusätzliche textliche Planinhalte oder Überlagerungsobjekte. Die Inhalte des älteren Plans bleiben aber gültig.'),
('2000', 'Aufhebung', '<b>Aufhebung </b>des Plans: Der Geltungsbereich des neuen Plans überdeckt den alten Plan, und die Inhalte des neuen Plans ersetzen die alten Inhalte  vollständig.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_bedeutungenbereich'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_bedeutungenbereich AS ENUM
  ('1600', '1800', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_bedeutungenbereich ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_bedeutungenbereich IS 'Alias: "enum_XP_BedeutungenBereich"';
TRUNCATE enum_xp_bedeutungenbereich;
INSERT INTO enum_xp_bedeutungenbereich (wert,abkuerzung,beschreibung) VALUES
('1600', 'Teilbereich', 'Räumliche oder sachliche Aufteilung der Planinhalte.'),
('1800', 'Kompensationsbereich', 'Aggregation von Objekten außerhalb des Geltungsbereiches gemäß Eingriffsregelung.'),
('9999', 'Sonstiges', 'Bereich, für den keine der aufgeführten Bedeutungen zutreffend ist. In dem Fall kann die Bedeutung über das Textattribut "<i>detaillierteBedeutung"</i> angegeben werden.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmungwasserwirtschaft'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmungwasserwirtschaft AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmungwasserwirtschaft ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmungwasserwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungWasserwirtschaft"';
TRUNCATE enum_xp_zweckbestimmungwasserwirtschaft;
INSERT INTO enum_xp_zweckbestimmungwasserwirtschaft (wert,abkuerzung,beschreibung) VALUES
('1000', 'HochwasserRueckhaltebecken', 'Hochwasser-Rückhaltebecken'),
('1100', 'Ueberschwemmgebiet', 'Überschwemmungs-gefährdetes Gebiet'),
('1200', 'Versickerungsflaeche', 'Versickerungsfläche'),
('1300', 'Entwaesserungsgraben', 'Entwässerungsgraben'),
('1400', 'Deich', 'Deich'),
('9999', 'Sonstiges', 'Sonstige Wasserwirtschaftsfläche, sofern keiner der anderen Codes zutreffend ist.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_grenzetypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_grenzetypen AS ENUM
  ('1000', '1100', '1200', '1250', '1300', '1400', '1450', '1500', '1510', '1550', '1600', '2000', '2100', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_grenzetypen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_grenzetypen IS 'Alias: "enum_XP_GrenzeTypen"';
TRUNCATE enum_xp_grenzetypen;
INSERT INTO enum_xp_grenzetypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Bundesgrenze', 'Bundesgrenze'),
('1100', 'Landesgrenze', 'Grenze eines Bundeslandes'),
('1200', 'Regierungsbezirksgrenze', 'Grenze eines Regierungsbezirks'),
('1250', 'Bezirksgrenze', 'Grenze eines Bezirks.'),
('1300', 'Kreisgrenze', 'Grenze eines Kreises.'),
('1400', 'Gemeindegrenze', 'Grenze einer Gemeinde.'),
('1450', 'Verbandsgemeindegrenze', 'Grenze einer Verbandsgemeinde'),
('1500', 'Samtgemeindegrenze', 'Grenze einer Samtgemeinde'),
('1510', 'Mitgliedsgemeindegrenze', 'Mitgliedsgemeindegrenze'),
('1550', 'Amtsgrenze', 'Amtsgrenze'),
('1600', 'Stadtteilgrenze', 'Stadtteilgrenze'),
('2000', 'VorgeschlageneGrundstuecksgrenze', 'Hinweis auf eine vorgeschlagene Grundstücksgrenze im BPlan.'),
('2100', 'GrenzeBestehenderBebauungsplan', 'Hinweis auf den Geltungsbereich eines bestehenden BPlan.'),
('9999', 'SonstGrenze', 'Sonstige Grenze');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_nutzungsform'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_nutzungsform AS ENUM
  ('1000', '2000');
END IF;
END$$;

ALTER TABLE enum_xp_nutzungsform ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_nutzungsform IS 'Alias: "enum_XP_Nutzungsform"';
TRUNCATE enum_xp_nutzungsform;
INSERT INTO enum_xp_nutzungsform (wert,abkuerzung,beschreibung) VALUES
('1000', 'Privat', 'Private Nutzung'),
('2000', 'Oeffentlich', 'Öffentliche Nutzung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmungkennzeichnung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmungkennzeichnung AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmungkennzeichnung ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmungkennzeichnung IS 'Alias: "enum_XP_ZweckbestimmungKennzeichnung"';
TRUNCATE enum_xp_zweckbestimmungkennzeichnung;
INSERT INTO enum_xp_zweckbestimmungkennzeichnung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Naturgewalten', 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen Naturgewalten erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).'),
('2000', 'Abbauflaeche', 'Flächen, unter denen der Bergbau umgeht oder die für den Abbau von Mineralien bestimmt sind (§5, Abs. 3, Nr. 2 BauGB).'),
('3000', 'AeussereEinwirkungen', 'Flächen, bei deren Bebauung besondere bauliche Sicherungsmaßnahmen gegen äußere Einwirkungen erforderlich sind (§5, Abs. 3, Nr. 1 BauGB).'),
('4000', 'SchadstoffBelastBoden', 'Für bauliche Nutzung vorgesehene Flächen, deren Böden erheblich mit umweltgefährdenden Stoffen belastet sind (§5, Abs. 3, Nr. 3 BauGB).'),
('5000', 'LaermBelastung', 'Für bauliche Nutzung vorgesehene Flächen, die erheblicher Lärmbelastung ausgesetzt sind.'),
('6000', 'Bergbau', 'Flächen für den Bergbau'),
('7000', 'Bodenordnung', 'Bodenordnung'),
('8000', 'Vorhabensgebiet', 'Räumlich besonders gekennzeichnetes Vorhabengebiets, das kleiner als der Geltungsbereich ist, innerhalb eines vorhabenbezogenen BPlans.'),
('9999', 'AndereGesetzlVorschriften', 'Kennzeichnung nach anderen gesetzlichen Vorschriften.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmunglandwirtschaft'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmunglandwirtschaft AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '1700', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmunglandwirtschaft ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmunglandwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungLandwirtschaft"';
TRUNCATE enum_xp_zweckbestimmunglandwirtschaft;
INSERT INTO enum_xp_zweckbestimmunglandwirtschaft (wert,abkuerzung,beschreibung) VALUES
('1000', 'LandwirtschaftAllgemein', 'Allgemeine Landwirtschaft'),
('1100', 'Ackerbau', 'Ackerbau'),
('1200', 'WiesenWeidewirtschaft', 'Wiesen- und Weidewirtschaft'),
('1300', 'GartenbaulicheErzeugung', 'Gartenbauliche Erzeugung'),
('1400', 'Obstbau', 'Obstbau'),
('1500', 'Weinbau', 'Weinbau'),
('1600', 'Imkerei', 'Imkerei'),
('1700', 'Binnenfischerei', 'Binnenfischerei'),
('9999', 'Sonstiges', 'Sonstiges');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmunggemeinbedarf'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmunggemeinbedarf AS ENUM
  ('1000', '10000', '10001', '10002', '10003', '1200', '12000', '12001', '12002', '12003', '12004', '1400', '14000', '14001', '14002', '14003', '1600', '16000', '16001', '16002', '16003', '16004', '1800', '18000', '18001', '2000', '20000', '20001', '20002', '2200', '22000', '22001', '22002', '2400', '24000', '24001', '24002', '24003', '2600', '26000', '26001', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmunggemeinbedarf ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmunggemeinbedarf IS 'Alias: "enum_XP_ZweckbestimmungGemeinbedarf"';
TRUNCATE enum_xp_zweckbestimmunggemeinbedarf;
INSERT INTO enum_xp_zweckbestimmunggemeinbedarf (wert,abkuerzung,beschreibung) VALUES
('1000', 'OeffentlicheVerwaltung', 'Einrichtungen und Anlagen für öffentliche Verwaltung'),
('10000', 'KommunaleEinrichtung', 'Kommunale Einrichtung wie z. B. Rathaus, Gesundheitsamt, Gesundheitsfürsorgestelle, Gartenbauamt, Gartenarbeitsstützpunkt, Fuhrpark.'),
('10001', 'BetriebOeffentlZweckbestimmung', 'Betrieb mit öffentlicher Zweckbestimmung wie z.B. ein Stadtreinigungsbetrieb, Autobusbetriebshof, Omnibusbahnhof.'),
('10002', 'AnlageBundLand', 'Eine Anlage des Bundes oder eines Bundeslandes wie z. B.  Arbeitsamt, Autobahnmeisterei, Brückenmeisterei, Patentamt, Wasserbauhof, Finanzamt.'),
('10003', 'SonstigeOeffentlicheVerwaltung', 'Sonstige Einrichtung oder Anlage der öffentlichen Verwaltung wie z. B. die Industrie und Handelskammer oder Handwerkskammer.'),
('1200', 'BildungForschung', 'Einrichtungen und Anlagen für Bildung und Forschung'),
('12000', 'Schule', 'Schulische Einrichtung. Darunter fallen u. a. Allgemeinbildende Schule, Oberstufenzentrum, Sonderschule, Fachschule, Volkshochschule,
Konservatorium.'),
('12001', 'Hochschule', 'Hochschule, Fachhochschule, Berufsakademie, o. Ä.'),
('12002', 'BerufsbildendeSchule', 'Berufsbildende Schule'),
('12003', 'Forschungseinrichtung', 'Forschungseinrichtung, Forschungsinstitut.'),
('12004', 'SonstigesBildungForschung', 'Sonstige Anlage oder Einrichtung aus Bildung und Forschung.'),
('1400', 'Kirche', 'Kirchliche Einrichtungen.'),
('14000', 'Sakralgebaeude', 'Religiösen Zwecken dienendes Gebäude wie z. B. Kirche, 
 Kapelle, Moschee, Synagoge, Gebetssaal.'),
('14001', 'KirchlicheVerwaltung', 'Kirchliches Verwaltungsgebäude, z. B. Pfarramt, Bischöfliches Ordinariat, Konsistorium.'),
('14002', 'Kirchengemeinde', 'Religiöse Gemeinde- oder Versammlungseinrichtung, z. B. Gemeindehaus, Gemeindezentrum.'),
('14003', 'SonstigesKirche', 'Sonstige religiösen Zwecken dienende Anlage oder Einrichtung.'),
('1600', 'Sozial', 'Einrichtungen und Anlagen für soziale Zwecke.'),
('16000', 'EinrichtungKinder', 'Soziale Einrichtung für Kinder, wie z. B. Kinderheim, Kindertagesstätte, Kindergarten.'),
('16001', 'EinrichtungJugendliche', 'Soziale Einrichtung für Jugendliche, wie z. B. Jugendfreizeitheim/-stätte, Jugendgästehaus, Jugendherberge, Jugendheim.'),
('16002', 'EinrichtungFamilienErwachsene', 'Soziale Einrichtung für Familien und Erwachsene, wie z. B. Bildungszentrum, Volkshochschule, Kleinkinderfürsorgestelle, Säuglingsfürsorgestelle, Nachbarschaftsheim.'),
('16003', 'EinrichtungSenioren', 'Soziale Einrichtung für Senioren, wie z. B. Alten-/Seniorentagesstätte, Alten-/Seniorenheim, Alten-/Seniorenwohnheim, Altersheim.'),
('16004', 'SonstigeSozialeEinrichtung', 'Sonstige soziale Einrichtung, z. B. Pflegeheim, Schwesternwohnheim, Studentendorf, Studentenwohnheim. Tierheim, Übergangsheim.'),
('1800', 'Gesundheit', 'Einrichtungen und Anlagen für gesundheitliche Zwecke.'),
('18000', 'Krankenhaus', 'Krankenhaus oder vergleichbare Einrichtung (z. B. Klinik, Hospital, Krankenheim, Heil- und Pflegeanstalt),'),
('18001', 'SonstigesGesundheit', 'Sonstige Gesundheits-Einrichtung, z. B. Sanatorium, Kurklinik, Desinfektionsanstalt.'),
('2000', 'Kultur', 'Einrichtungen und Anlagen für kulturelle Zwecke.'),
('20000', 'MusikTheater', 'Kulturelle Einrichtung aus dem Bereich Musik oder Theater (z. B. Theater, Konzerthaus, Musikhalle, Oper).'),
('20001', 'Bildung', 'Kulturelle Einrichtung mit Bildungsfunktion ( z. B. Museum, Bibliothek, Bücherei, Stadtbücherei, Volksbücherei).'),
('20002', 'SonstigeKultur', 'Sonstige kulturelle Einrichtung, wie z. B. Archiv, Landesbildstelle, Rundfunk und Fernsehen, Kongress- und Veranstaltungshalle, Mehrzweckhalle..'),
('2200', 'Sport', 'Einrichtungen und Anlagen für sportliche Zwecke.'),
('22000', 'Bad', 'Schwimmbad, Freibad, Hallenbad, Schwimmhalle o. Ä..'),
('22001', 'SportplatzSporthalle', 'Sportplatz, Sporthalle, Tennishalle o. Ä.'),
('22002', 'SonstigerSport', 'Sonstige Sporteinrichtung.'),
('2400', 'SicherheitOrdnung', 'Einrichtungen und Anlagen für Sicherheit und Ordnung.'),
('24000', 'Feuerwehr', 'Einrichtung oder Anlage der Feuerwehr.'),
('24001', 'Schutzbauwerk', 'Schutzbauwerk'),
('24002', 'Justiz', 'Einrichtung der Justiz, wie z. B. Justizvollzug, Gericht, Haftanstalt.'),
('24003', 'SonstigeSicherheitOrdnung', 'Sonstige Anlage oder Einrichtung für Sicherheit und Ordnung, z. B. Polizei, Zoll, Feuerwehr, Zivilschutz, Bundeswehr, Landesverteidigung.'),
('2600', 'Infrastruktur', 'Einrichtungen und Anlagen der Infrastruktur.'),
('26000', 'Post', 'Einrichtung der Post.'),
('26001', 'SonstigeInfrastruktur', 'Sonstige Anlage oder Einrichtung der Infrastruktur.'),
('9999', 'Sonstiges', 'Sonstige Einrichtungen und Anlagen, die keiner anderen Kategorie zuzuordnen sind.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_anpflanzungbindungerhaltungsgegenstand'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_anpflanzungbindungerhaltungsgegenstand AS ENUM
  ('1000', '1100', '1200', '2000', '2050', '2100', '2200', '3000', '4000', '5000', '6000');
END IF;
END$$;

ALTER TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand IS 'Alias: "enum_XP_AnpflanzungBindungErhaltungsGegenstand"';
TRUNCATE enum_xp_anpflanzungbindungerhaltungsgegenstand;
INSERT INTO enum_xp_anpflanzungbindungerhaltungsgegenstand (wert,abkuerzung,beschreibung) VALUES
('1000', 'Baeume', 'Bäume'),
('1100', 'Kopfbaeume', 'Kopfbäume'),
('1200', 'Baumreihe', 'Baumreihe'),
('2000', 'Straeucher', 'Sträucher'),
('2050', 'BaeumeUndStraeucher', 'Bäume und Sträucher'),
('2100', 'Hecke', 'Hecke'),
('2200', 'Knick', 'Knick'),
('3000', 'SonstBepflanzung', 'Sonstige Bepflanzung'),
('4000', 'Gewaesser', 'Gewässer (nur Erhaltung)'),
('5000', 'Fassadenbegruenung', 'Fassadenbegrünung'),
('6000', 'Dachbegruenung', 'Dachbegrünung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmungverentsorgung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmungverentsorgung AS ENUM
  ('1000', '10000', '10001', '100010', '10002', '10003', '10004', '10005', '10006', '10007', '10008', '10009', '1200', '12000', '12001', '12002', '12003', '12004', '12005', '1300', '13000', '13001', '13002', '13003', '1400', '14000', '14001', '14002', '1600', '16000', '16001', '16002', '16003', '16004', '16005', '1800', '18000', '18001', '18002', '18003', '18004', '18005', '18006', '2000', '20000', '20001', '2200', '22000', '22001', '22002', '22003', '2400', '24000', '24001', '24002', '24003', '24004', '24005', '2600', '26000', '26001', '26002', '2800', '3000', '9999', '99990');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmungverentsorgung ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmungverentsorgung IS 'Alias: "enum_XP_ZweckbestimmungVerEntsorgung"';
TRUNCATE enum_xp_zweckbestimmungverentsorgung;
INSERT INTO enum_xp_zweckbestimmungverentsorgung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Elektrizitaet', 'Elektrizität allgemein'),
('10000', 'Hochspannungsleitung', 'Hochspannungsleitung'),
('10001', 'TrafostationUmspannwerk', 'Trafostation, auch Umspannwerk'),
('100010', 'Leitungsmast', 'Leitungsmast'),
('10002', 'Solarkraftwerk', 'Solarkraftwerk'),
('10003', 'Windkraftwerk', 'Windkraftwerk, Windenergieanlage, Windrad.'),
('10004', 'Geothermiekraftwerk', 'Geothermie Kraftwerk'),
('10005', 'Elektrizitaetswerk', 'Elektrizitätswerk allgemein'),
('10006', 'Wasserkraftwerk', 'Wasserkraftwerk'),
('10007', 'BiomasseKraftwerk', 'Biomasse-Kraftwerk'),
('10008', 'Kabelleitung', 'Kabelleitung'),
('10009', 'Niederspannungsleitung', 'Niederspannungsleitung'),
('1200', 'Gas', 'Gas allgemein'),
('12000', 'Ferngasleitung', 'Ferngasleitung'),
('12001', 'Gaswerk', 'Gaswerk'),
('12002', 'Gasbehaelter', 'Gasbehälter'),
('12003', 'Gasdruckregler', 'Gasdruckregler'),
('12004', 'Gasstation', 'Gasstation'),
('12005', 'Gasleitung', 'Gasleitung'),
('1300', 'Erdoel', 'Erdöl allgemein'),
('13000', 'Erdoelleitung', 'Erdölleitung'),
('13001', 'Bohrstelle', 'Bohrstelle'),
('13002', 'Erdoelpumpstation', 'Erdölpumpstation'),
('13003', 'Oeltank', 'Öltank'),
('1400', 'Waermeversorgung', 'Wärmeversorgung allgemein'),
('14000', 'Blockheizkraftwerk', 'Blockheizkraftwerk'),
('14001', 'Fernwaermeleitung', 'Fernwärmeleitung'),
('14002', 'Fernheizwerk', 'Fernheizwerk'),
('1600', 'Wasser', 'Trink- und Brauchwasser allgemein'),
('16000', 'Wasserwerk', 'Wasserwerk'),
('16001', 'Wasserleitung', 'Trinkwasserleitung'),
('16002', 'Wasserspeicher', 'Wasserspeicher'),
('16003', 'Brunnen', 'Brunnen'),
('16004', 'Pumpwerk', 'Pumpwerk'),
('16005', 'Quelle', 'Quelle'),
('1800', 'Abwasser', 'Abwasser allgemein'),
('18000', 'Abwasserleitung', 'Abwasserleitung'),
('18001', 'Abwasserrueckhaltebecken', 'Abwasserrückhaltebecken'),
('18002', 'Abwasserpumpwerk', 'Abwasserpumpwerk, auch Abwasserhebeanlage'),
('18003', 'Klaeranlage', 'Kläranlage'),
('18004', 'AnlageKlaerschlamm', 'Anlage zur Speicherung oder Behandlung von Klärschlamm.'),
('18005', 'SonstigeAbwasserBehandlungsanlage', 'Sonstige Abwasser-Behandlungsanlage.'),
('18006', 'Salz oder Soleleitungen', 'Salz- oder Sole-Leitungen'),
('2000', 'Regenwasser', 'Regenwasser allgemein'),
('20000', 'RegenwasserRueckhaltebecken', 'Regenwasser Rückhaltebecken'),
('20001', 'Niederschlagswasserleitung', 'Niederschlagswasser-Leitung'),
('2200', 'Abfallentsorgung', 'Abfallentsorgung allgemein'),
('22000', 'Muellumladestation', 'Müll-Umladestation'),
('22001', 'Muellbeseitigungsanlage', 'Müllbeseitigungsanlage'),
('22002', 'Muellsortieranlage', 'Müllsortieranlage'),
('22003', 'Recyclinghof', 'Recyclinghof'),
('2400', 'Ablagerung', 'Ablagerung allgemein'),
('24000', 'Erdaushubdeponie', 'Erdaushub-Deponie'),
('24001', 'Bauschuttdeponie', 'Bauschutt-Deponie'),
('24002', 'Hausmuelldeponie', 'Hausmüll-Deponie'),
('24003', 'Sondermuelldeponie', 'Sondermüll-Deponie'),
('24004', 'StillgelegteDeponie', 'Stillgelegte Deponie'),
('24005', 'RekultivierteDeponie', 'Rekultivierte Deponie'),
('2600', 'Telekommunikation', 'Telekommunikation allgemein'),
('26000', 'Fernmeldeanlage', 'Fernmeldeanlage'),
('26001', 'Mobilfunkstrecke', 'Mobilfunkstrecke'),
('26002', 'Fernmeldekabel', 'Fernmeldekabel'),
('2800', 'ErneuerbareEnergien', 'Erneuerbare Energien allgemein'),
('3000', 'KraftWaermeKopplung', 'Fläche oder Anlage für Kraft-Wärme Kopplung'),
('9999', 'Sonstiges', 'Sonstige, durch keinen anderen Code abbildbare Ver- oder Entsorgungsfläche bzw. -Anlage.'),
('99990', 'Produktenleitung', 'Produktenleitung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_verlaengerungveraenderungssperre'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_verlaengerungveraenderungssperre AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

ALTER TABLE enum_xp_verlaengerungveraenderungssperre ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_verlaengerungveraenderungssperre IS 'Alias: "enum_XP_VerlaengerungVeraenderungssperre"';
TRUNCATE enum_xp_verlaengerungveraenderungssperre;
INSERT INTO enum_xp_verlaengerungveraenderungssperre (wert,abkuerzung,beschreibung) VALUES
('1000', 'Keine', 'Veränderungssperre wurde noch nicht verlängert.'),
('2000', 'ErsteVerlaengerung', 'Veränderungssperre wurde einmal verlängert.'),
('3000', 'ZweiteVerlaengerung', 'Veränderungssperre wurde zweimal verlängert.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_bundeslaender'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_bundeslaender AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '1700', '1800', '1900', '2000', '2100', '2200', '2300', '2400', '2500', '3000');
END IF;
END$$;

ALTER TABLE enum_xp_bundeslaender ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_bundeslaender IS 'Alias: "enum_XP_Bundeslaender"';
TRUNCATE enum_xp_bundeslaender;
INSERT INTO enum_xp_bundeslaender (wert,abkuerzung,beschreibung) VALUES
('1000', 'BB', 'Brandenburg'),
('1100', 'BE', 'Berlin'),
('1200', 'BW', 'Baden-Württemberg'),
('1300', 'BY', 'Bayern'),
('1400', 'HB', 'Bremen'),
('1500', 'HE', 'Hessen'),
('1600', 'HH', 'Hamburg'),
('1700', 'MV', 'Mecklenburg-Vorpommern'),
('1800', 'NI', 'Niedersachsen'),
('1900', 'NW', 'Nordrhein-Westfalen'),
('2000', 'RP', 'Rheinland-Pfalz'),
('2100', 'SH', 'Schleswig-Holstein'),
('2200', 'SL', 'Saarland'),
('2300', 'SN', 'Sachsen'),
('2400', 'ST', 'Sachsen-Anhalt'),
('2500', 'TH', 'Thüringen'),
('3000', 'Bund', 'Der Bund.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmungwald'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmungwald AS ENUM
  ('1000', '1200', '1400', '1600', '1800', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmungwald ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmungwald IS 'Alias: "enum_XP_ZweckbestimmungWald"';
TRUNCATE enum_xp_zweckbestimmungwald;
INSERT INTO enum_xp_zweckbestimmungwald (wert,abkuerzung,beschreibung) VALUES
('1000', 'Naturwald', 'Naturwald'),
('1200', 'Nutzwald', 'Nutzwald'),
('1400', 'Erholungswald', 'Erholungswald'),
('1600', 'Schutzwald', 'Schutzwald'),
('1800', 'FlaecheForstwirtschaft', 'Fläche für die Forstwirtschaft.'),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_abemassnahmentypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_abemassnahmentypen AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

ALTER TABLE enum_xp_abemassnahmentypen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_abemassnahmentypen IS 'Alias: "enum_XP_ABEMassnahmenTypen"';
TRUNCATE enum_xp_abemassnahmentypen;
INSERT INTO enum_xp_abemassnahmentypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'BindungErhaltung', 'Bindung und Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen, sowie von Gewässern.'),
('2000', 'Anpflanzung', 'Anpflanzung von Bäumen, Sträuchern oder sonstigen Bepflanzungen.'),
('3000', 'AnpflanzungBindungErhaltung', 'Bindung und Erhaltung von Bäumen, Sträuchern und sonstigen Bepflanzungen.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_sondernutzungen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_sondernutzungen AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '16000', '16001', '16002', '1700', '1800', '1900', '2000', '2100', '2200', '2300', '2400', '2500', '2600', '2700', '2800', '2900', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_sondernutzungen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_sondernutzungen IS 'Alias: "enum_XP_Sondernutzungen"';
TRUNCATE enum_xp_sondernutzungen;
INSERT INTO enum_xp_sondernutzungen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Wochenendhausgebiet', 'Wochenendhausgebiet'),
('1100', 'Ferienhausgebiet', 'Ferienhausgebiet'),
('1200', 'Campingplatzgebiet', 'Campingplatzgebiet'),
('1300', 'Kurgebiet', 'Kurgebiet'),
('1400', 'SonstSondergebietErholung', 'Sonstiges Sondergebiet für Erholung'),
('1500', 'Einzelhandelsgebiet', 'Einzelhandelsgebiet'),
('1600', 'GrossflaechigerEinzelhandel', 'Gebiet für großflächigen Einzelhandel'),
('16000', 'Ladengebiet', 'Ladengebiet'),
('16001', 'Einkaufszentrum', 'Einkaufszentrum'),
('16002', 'SonstGrossflEinzelhandel', 'Sonstiges Gebiet für großflächigen Einzelhandel'),
('1700', 'Verkehrsuebungsplatz', 'Verkehrsübungsplatz'),
('1800', 'Hafengebiet', 'Hafengebiet'),
('1900', 'SondergebietErneuerbareEnergie', 'Sondergebiet für Erneuerbare Energien'),
('2000', 'SondergebietMilitaer', 'Militärisches Sondergebiet'),
('2100', 'SondergebietLandwirtschaft', 'Sondergebiet Landwirtschaft'),
('2200', 'SondergebietSport', 'Sondergebiet Sport'),
('2300', 'SondergebietGesundheitSoziales', 'Sondergebiet für Gesundheit und Soziales'),
('2400', 'Golfplatz', 'Golfplatz'),
('2500', 'SondergebietKultur', 'Sondergebiet für Kultur'),
('2600', 'SondergebietTourismus', 'Sondergebiet Tourismus'),
('2700', 'SondergebietBueroUndVerwaltung', 'Sondergebiet für Büros und Verwaltung'),
('2800', 'SondergebietHochschuleEinrichtungen', 'Sondergebiet Hochschule'),
('2900', 'SondergebietMesse', 'Sondergebiet für Messe'),
('9999', 'SondergebietAndereNutzungen', 'Sonstiges Sondergebiet');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_speziele'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_speziele AS ENUM
  ('1000', '2000', '3000', '4000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_speziele ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_speziele IS 'Alias: "enum_XP_SPEZiele"';
TRUNCATE enum_xp_speziele;
INSERT INTO enum_xp_speziele (wert,abkuerzung,beschreibung) VALUES
('1000', 'SchutzPflege', 'Schutz und Pflege'),
('2000', 'Entwicklung', 'Entwicklung'),
('3000', 'Anlage', 'Neu-Anlage'),
('4000', 'SchutzPflegeEntwicklung', 'Schutz, Pflege und Entwicklung'),
('9999', 'Sonstiges', 'Sonstiges Ziel');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_klassifizschutzgebietnaturschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_klassifizschutzgebietnaturschutzrecht AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '1700', '1800', '18000', '18001', '2000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_klassifizschutzgebietnaturschutzrecht ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_klassifizschutzgebietnaturschutzrecht IS 'Alias: "enum_XP_KlassifizSchutzgebietNaturschutzrecht"';
TRUNCATE enum_xp_klassifizschutzgebietnaturschutzrecht;
INSERT INTO enum_xp_klassifizschutzgebietnaturschutzrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Naturschutzgebiet', 'Naturschutzgebiet gemäß <font color="#00000a">§23 BNatSchG.</font>'),
('1100', 'Nationalpark', 'Nationalpark <font color="#00000a">gemäß §24 BNatSchG</font>'),
('1200', 'Biosphaerenreservat', 'Biosphärenreservat <font color="#00000a">gemäß §25 BNatSchG.</font>'),
('1300', 'Landschaftsschutzgebiet', 'Landschaftsschutzgebiet <font color="#00000a">gemäß §65 BNatSchG.</font>'),
('1400', 'Naturpark', 'Naturpark <font color="#00000a">gemäß §27 BNatSchG.</font>'),
('1500', 'Naturdenkmal', 'Naturdenkmal <font color="#00000a">gemäß §28 BNatSchG.</font>'),
('1600', 'GeschuetzterLandschaftsBestandteil', 'Geschützter Bestandteil der Landschaft <font color="#00000a">gemäß §29 BNatSchG.</font>'),
('1700', 'GesetzlichGeschuetztesBiotop', 'Gesetzlich geschützte Biotope <font color="#00000a">gemäß §30 BNatSchG.</font>'),
('1800', 'Natura2000', 'Schutzgebiet nach Europäischem Recht. Dies umfasst das "Gebiet Gemeinschaftlicher Bedeutung" (FFH-Gebiet) und das "Europäische Vogelschutzgebiet"'),
('18000', 'GebietGemeinschaftlicherBedeutung', 'Gebiete von gemeinschaftlicher Bedeutung'),
('18001', 'EuropaeischesVogelschutzgebiet', 'Europäische Vogelschutzgebiete'),
('2000', 'NationalesNaturmonument', 'Nationales Naturmonument <font color="#00000a">gemäß §24 Abs. (4)  BNatSchG.</font>'),
('9999', 'Sonstiges', 'Sonstiges Naturschutzgebiet');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmunggewaesser'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmunggewaesser AS ENUM
  ('1000', '1100', '1200', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmunggewaesser ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmunggewaesser IS 'Alias: "enum_XP_ZweckbestimmungGewaesser"';
TRUNCATE enum_xp_zweckbestimmunggewaesser;
INSERT INTO enum_xp_zweckbestimmunggewaesser (wert,abkuerzung,beschreibung) VALUES
('1000', 'Hafen', 'Hafen'),
('1100', 'Wasserflaeche', 'Stehende Wasserfläche, auch See, Teich.'),
('1200', 'Fliessgewaesser', 'Fließgewässer, auch Fluss, Bach'),
('9999', 'Sonstiges', 'Sonstiges Gewässer, sofern keiner der anderen Codes zutreffend ist.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_abweichungbaunvotypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_abweichungbaunvotypen AS ENUM
  ('1000', '2000', '3000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_abweichungbaunvotypen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_abweichungbaunvotypen IS 'Alias: "enum_XP_AbweichungBauNVOTypen"';
TRUNCATE enum_xp_abweichungbaunvotypen;
INSERT INTO enum_xp_abweichungbaunvotypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'EinschraenkungNutzung', 'Einschränkung einer generell erlaubten Nutzung.'),
('2000', 'AusschlussNutzung', 'Ausschluss einer generell erlaubten Nutzung.'),
('3000', 'AusweitungNutzung', 'Eine nur ausnahmsweise zulässige Nutzung wird generell zulässig.'),
('9999', 'SonstAbweichung', 'Sonstige Abweichung.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_allgartderbaulnutzung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_allgartderbaulnutzung AS ENUM
  ('1000', '2000', '3000', '4000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_allgartderbaulnutzung ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_allgartderbaulnutzung IS 'Alias: "enum_XP_AllgArtDerBaulNutzung"';
TRUNCATE enum_xp_allgartderbaulnutzung;
INSERT INTO enum_xp_allgartderbaulnutzung (wert,abkuerzung,beschreibung) VALUES
('1000', 'WohnBauflaeche', 'Wohnbaufläche nach §1 Abs. (1) BauNVO'),
('2000', 'GemischteBauflaeche', 'Gemischte Baufläche nach §1 Abs. (1) BauNVO.'),
('3000', 'GewerblicheBauflaeche', 'Gewerbliche Baufläche nach §1 Abs. (1) BauNVO.'),
('4000', 'SonderBauflaeche', 'Sonderbaufläche nach §1 Abs. (1) BauNVO.'),
('9999', 'SonstigeBauflaeche', 'Sonstige Baufläche');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmungspielsportanlage'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmungspielsportanlage AS ENUM
  ('1000', '2000', '3000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmungspielsportanlage ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmungspielsportanlage IS 'Alias: "enum_XP_ZweckbestimmungSpielSportanlage"';
TRUNCATE enum_xp_zweckbestimmungspielsportanlage;
INSERT INTO enum_xp_zweckbestimmungspielsportanlage (wert,abkuerzung,beschreibung) VALUES
('1000', 'Sportanlage', 'Sportanlage'),
('2000', 'Spielanlage', 'Spielanlage'),
('3000', 'SpielSportanlage', 'Spiel- und/oder Sportanlage.'),
('9999', 'Sonstiges', 'Sonstiges');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_zweckbestimmunggruen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_zweckbestimmunggruen AS ENUM
  ('1000', '10000', '10001', '10002', '10003', '1200', '12000', '1400', '14000', '14001', '14002', '14003', '14004', '14005', '14006', '14007', '1600', '16000', '16001', '1800', '18000', '2000', '2200', '22000', '22001', '2400', '24000', '24001', '24002', '24003', '24004', '24005', '24006', '2600', '9999', '99990');
END IF;
END$$;

ALTER TABLE enum_xp_zweckbestimmunggruen ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_zweckbestimmunggruen IS 'Alias: "enum_XP_ZweckbestimmungGruen"';
TRUNCATE enum_xp_zweckbestimmunggruen;
INSERT INTO enum_xp_zweckbestimmunggruen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Parkanlage', 'Parkanlage; auch: Erholungsgrün, Grünanlage, Naherholung.'),
('10000', 'ParkanlageHistorisch', 'Historische Parkanlage'),
('10001', 'ParkanlageNaturnah', 'Naturnahe Parkanlage'),
('10002', 'ParkanlageWaldcharakter', 'Parkanlage mit Waldcharakter'),
('10003', 'NaturnaheUferParkanlage', 'Ufernahe Parkanlage'),
('1200', 'Dauerkleingarten', 'Dauerkleingarten; auch: Gartenfläche, Hofgärten, Gartenland.'),
('12000', 'ErholungsGaerten', 'Erholungsgarten'),
('1400', 'Sportplatz', 'Sportplatz'),
('14000', 'Reitsportanlage', 'Reitsportanlage'),
('14001', 'Hundesportanlage', 'Hundesportanlage'),
('14002', 'Wassersportanlage', 'Wassersportanlage'),
('14003', 'Schiessstand', 'Schießstand'),
('14004', 'Golfplatz', 'Golfplatz'),
('14005', 'Skisport', 'Anlage für Skisport'),
('14006', 'Tennisanlage', 'Tennisanlage'),
('14007', 'SonstigerSportplatz', 'Sonstiger Sportplatz'),
('1600', 'Spielplatz', 'Spielplatz'),
('16000', 'Bolzplatz', 'Bolzplatz'),
('16001', 'Abenteuerspielplatz', 'Abenteuerspielplatz'),
('1800', 'Zeltplatz', 'Zeltplatz'),
('18000', 'Campingplatz', 'Campingplatz'),
('2000', 'Badeplatz', 'Badeplatz, auch Schwimmbad, Liegewiese.'),
('2200', 'FreizeitErholung', 'Anlage für Freizeit und Erholung.'),
('22000', 'Kleintierhaltung', 'Anlage für Kleintierhaltung'),
('22001', 'Festplatz', 'Festplatz'),
('2400', 'SpezGruenflaeche', 'Spezielle Grünfläche'),
('24000', 'StrassenbegleitGruen', 'Straßenbegleitgrün'),
('24001', 'BoeschungsFlaeche', 'Böschungsfläche'),
('24002', 'FeldWaldWiese', 'Feld, Wald, Wiese allgemein'),
('24003', 'Uferschutzstreifen', 'Uferstreifen'),
('24004', 'Abschirmgruen', 'Abschirmgrün'),
('24005', 'UmweltbildungsparkSchaugatter', 'Umweltbildungspark, Schaugatter'),
('24006', 'RuhenderVerkehr', 'Fläche für den ruhenden Verkehr.'),
('2600', 'Friedhof', 'Friedhof'),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung, falls keine der aufgeführten Klassifikationen anwendbar ist.'),
('99990', 'Gaertnerei', 'Gärtnerei');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_besondereartderbaulnutzung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_besondereartderbaulnutzung AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1550', '1600', '1700', '1800', '2000', '2100', '3000', '4000', '9999');
END IF;
END$$;

ALTER TABLE enum_xp_besondereartderbaulnutzung ADD COLUMN abkuerzung character varying;

COMMENT ON TABLE enum_xp_besondereartderbaulnutzung IS 'Alias: "enum_XP_BesondereArtDerBaulNutzung"';
TRUNCATE enum_xp_besondereartderbaulnutzung;
INSERT INTO enum_xp_besondereartderbaulnutzung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Kleinsiedlungsgebiet', 'Kleinsiedlungsgebiet nach § 2 BauNVO.'),
('1100', 'ReinesWohngebiet', 'Reines Wohngebiet nach § 3 BauNVO.'),
('1200', 'AllgWohngebiet', 'Allgemeines Wohngebiet nach § 4 BauNVO.'),
('1300', 'BesonderesWohngebiet', 'Gebiet zur Erhaltung und Entwicklung der Wohnnutzung (Besonderes Wohngebiet) nach § 4a BauNVO.'),
('1400', 'Dorfgebiet', 'Dorfgebiet nach $ 5 BauNVO.'),
('1500', 'Mischgebiet', 'Mischgebiet nach $ 6 BauNVO.'),
('1550', 'UrbanesGebiet', 'Urbanes Gebiet nach § 6a BauNVO'),
('1600', 'Kerngebiet', 'Kerngebiet nach § 7 BauNVO.'),
('1700', 'Gewerbegebiet', 'Gewerbegebiet nach § 8 BauNVO.'),
('1800', 'Industriegebiet', 'Industriegebiet nach § 9 BauNVO.'),
('2000', 'SondergebietErholung', 'Sondergebiet, das der Erholung dient nach § 10 BauNVO von 1977 und 1990.'),
('2100', 'SondergebietSonst', 'Sonstiges Sondergebiet nach§ 11 BauNVO 1977 und 1990; z.B. Klinikgebiet'),
('3000', 'Wochenendhausgebiet', 'Wochenendhausgebiet nach §10 der BauNVO von 1962 und 1968'),
('4000', 'Sondergebiet', 'Sondergebiet nach §11der BauNVO von 1962 und 1968'),
('9999', 'SonstigesGebiet', 'Sonstiges Gebiet');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_horizontaleausrichtung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_horizontaleausrichtung AS ENUM
  ('linksbündig', 'rechtsbündig', 'zentrisch');
END IF;
END$$;

DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_vertikaleausrichtung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.xp_vertikaleausrichtung AS ENUM
  ('Basis', 'Mitte', 'Oben');
END IF;
END$$;

DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_rechtscharakter'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_rechtscharakter AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '9998');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_rechtscharakter (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_rechtscharakter IS 'Alias: "enum_BP_Rechtscharakter"';
INSERT INTO enum_bp_rechtscharakter (wert,abkuerzung,beschreibung) VALUES
('1000', 'Festsetzung', 'Festsetzung in Bebauungsplan.'),
('2000', 'NachrichtlicheUebernahme', 'Nachrichtliche Übernahme aus anderen Planwerken.'),
('3000', 'Hinweis', 'Hinweis nach BauGB'),
('4000', 'Vermerk', 'Vermerk nach § 5 BauGB'),
('5000', 'Kennzeichnung', 'Kennzeichnung von Flächen nach $9 Absatz 5 BauGB. Kennzeichnungen sind keine rechtsverbindlichen Festsetzungen, sondern Hinweise auf Besonderheiten (insbesondere der Baugrundverhältnisse), deren Kenntnis für das Verständnis des Bebauungsplans und seiner Festsetzungen wie auch für die Vorbereitung und Genehmigung von Vorhaben notwendig sind.'),
('9998', 'Unbekannt', 'Der Rechtscharakter des BPlan-Inhaltes ist unbekannt.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_planart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_planart AS ENUM
  ('1000', '10000', '10001', '3000', '4000', '40000', '40001', '40002', '5000', '7000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_planart (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_planart IS 'Alias: "enum_BP_PlanArt"';
INSERT INTO enum_bp_planart (wert,abkuerzung,beschreibung) VALUES
('1000', 'BPlan', 'Planwerk der verbindlichen Bauleitplanung auf kommunaler Ebene'),
('10000', 'EinfacherBPlan', 'Einfacher BPlan, §30 Abs. 3 BauGB.'),
('10001', 'QualifizierterBPlan', 'Qualifizierter BPlan nach §30 Abs. 1 BauGB.'),
('3000', 'VorhabenbezogenerBPlan', 'Vorhabensbezogener Bebauungsplan nach §12 BauGB'),
('4000', 'InnenbereichsSatzung', 'Kommunale Satzung gemäß §34 BauGB'),
('40000', 'KlarstellungsSatzung', 'Klarstellungssatzung nach  § 34 Abs.4 Nr.1 BauGB.'),
('40001', 'EntwicklungsSatzung', 'Entwicklungssatzung nach  § 34 Abs.4 Nr. 2 BauGB.'),
('40002', 'ErgaenzungsSatzung', 'Ergänzungssatzung nach  § 34 Abs.4 Nr. 3 BauGB.'),
('5000', 'AussenbereichsSatzung', 'Außenbereichssatzung nach § 35 Abs. 6 BauGB.'),
('7000', 'OertlicheBauvorschrift', 'Örtliche Bauvorschrift.'),
('9999', 'Sonstiges', 'Sonstige Planart.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_verfahren'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_verfahren AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_verfahren (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_verfahren IS 'Alias: "enum_BP_Verfahren"';
INSERT INTO enum_bp_verfahren (wert,abkuerzung,beschreibung) VALUES
('1000', 'Normal', 'Normales BPlan Verfahren.'),
('2000', 'Parag13', 'BPlan Verfahren nach Paragraph 13 BauGB.'),
('3000', 'Parag13a', 'BPlan Verfahren nach Paragraph 13a BauGB.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_rechtsstand'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_rechtsstand AS ENUM
  ('1000', '2000', '2100', '2200', '2300', '2400', '3000', '4000', '4500', '5000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_rechtsstand (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_rechtsstand IS 'Alias: "enum_BP_Rechtsstand"';
INSERT INTO enum_bp_rechtsstand (wert,abkuerzung,beschreibung) VALUES
('1000', 'Aufstellungsbeschluss', 'Ein Aufstellungsbeschluss der Gemeinde liegt vor.'),
('2000', 'Entwurf', 'Ein Planentwurf liegt vor.'),
('2100', 'FruehzeitigeBehoerdenBeteiligung', 'Die frühzeitige Beteiligung der Behörden (§ 4 Abs. 1 BauGB) hat stattgefunden.'),
('2200', 'FruehzeitigeOeffentlichkeitsBeteiligung', 'Die frühzeitige Beteiligung der Öffentlichkeit (§ 3 Abs. 1 BauGB), bzw. bei einem Verfahren nach § 13a BauGB die Unterrichtung der Öffentlichkeit (§ 13a Abs. 3 BauGB) hat stattgefunden.'),
('2300', 'BehoerdenBeteiligung', 'Die Beteiligung der Behörden hat stattgefunden (§ 4 Abs. 2 BauGB).'),
('2400', 'OeffentlicheAuslegung', 'Der Plan hat öffentlich ausgelegen. (§ 3 Abs. 2 BauGB).'),
('3000', 'Satzung', 'Die Satzung wurde durch Beschluss der Gemeinde verabschiedet.'),
('4000', 'InkraftGetreten', 'Der Plan ist in kraft getreten.'),
('4500', 'TeilweiseUntergegangen', 'Der Plan ist, z. B. durch einen Gerichtsbeschluss oder neuen Plan, teilweise untergegangen.'),
('5000', 'Untergegangen', 'Der Plan wurde aufgehoben oder für nichtig erklärt.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_grenzbebauung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_grenzbebauung AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_grenzbebauung (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_grenzbebauung IS 'Alias: "enum_BP_GrenzBebauung"';
INSERT INTO enum_bp_grenzbebauung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Verboten', 'Eine Bebauung der Grenze ist verboten.'),
('2000', 'Erlaubt', 'Eine Bebauung der Grenze ist erlaubt.'),
('3000', 'Erzwungen', 'Eine Bebauung der Grenze ist vorgeschrieben.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_bebauungsart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_bebauungsart AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '6000', '7000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_bebauungsart (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_bebauungsart IS 'Alias: "enum_BP_BebauungsArt"';
INSERT INTO enum_bp_bebauungsart (wert,abkuerzung,beschreibung) VALUES
('1000', 'Einzelhaeuser', 'Nur Einzelhäuser zulässig.'),
('2000', 'Doppelhaeuser', 'Nur Doppelhäuser zulässig.'),
('3000', 'Hausgruppen', 'Nur Hausgruppen zulässig.'),
('4000', 'EinzelDoppelhaeuser', 'Nur Einzel- oder Doppelhäuser zulässig.'),
('5000', 'EinzelhaeuserHausgruppen', 'Nur Einzelhäuser oder Hausgruppen zulässig.'),
('6000', 'DoppelhaeuserHausgruppen', 'Nur Doppelhäuser oder Hausgruppen zulässig.'),
('7000', 'Reihenhaeuser', 'Nur Reihenhäuser zulässig.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_zulaessigkeit'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_zulaessigkeit AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_zulaessigkeit (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_zulaessigkeit IS 'Alias: "enum_BP_Zulaessigkeit"';
INSERT INTO enum_bp_zulaessigkeit (wert,abkuerzung,beschreibung) VALUES
('1000', 'Zulaessig', 'Generelle Zulässigkeit'),
('2000', 'NichtZulaessig', 'Generelle Nicht-Zulässigkeit.'),
('3000', 'AusnahmsweiseZulaessig', 'Ausnahmsweise Zulässigkeit');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_dachform'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_dachform AS ENUM
  ('1000', '2100', '2200', '3100', '3200', '3300', '3400', '3500', '3600', '3700', '3800', '3900', '4000', '5000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_dachform (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_dachform IS 'Alias: "enum_BP_Dachform"';
INSERT INTO enum_bp_dachform (wert,abkuerzung,beschreibung) VALUES
('1000', 'Flachdach', 'Flachdach'),
('2100', 'Pultdach', 'Pultdach'),
('2200', 'VersetztesPultdach', 'Versetztes Pultdach'),
('3100', 'Satteldach', 'Satteldach'),
('3200', 'Walmdach', 'Walmdach'),
('3300', 'Krueppelwalmdach', 'Krüppelwalmdach'),
('3400', 'Mansarddach', 'Mansardendach'),
('3500', 'Zeltdach', 'Zeltdach'),
('3600', 'Kegeldach', 'Kegeldach'),
('3700', 'Kuppeldach', 'Kuppeldach'),
('3800', 'Sheddach', 'Sheddach'),
('3900', 'Bogendach', 'Bogendach'),
('4000', 'Turmdach', 'Turmdach'),
('5000', 'Mischform', 'Gemischte Dachform'),
('9999', 'Sonstiges', 'Sonstige Dachform');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_nebenanlagenausschlusstyp'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_nebenanlagenausschlusstyp AS ENUM
  ('1000', '2000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_nebenanlagenausschlusstyp (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_nebenanlagenausschlusstyp IS 'Alias: "enum_BP_NebenanlagenAusschlussTyp"';
INSERT INTO enum_bp_nebenanlagenausschlusstyp (wert,abkuerzung,beschreibung) VALUES
('1000', 'Einschraenkung', 'Die Errichtung bestimmter Nebenanlagen ist eingeschränkt.'),
('2000', 'Ausschluss', 'Die Errichtung bestimmter Nebenanlagen ist ausgeschlossen.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_zweckbestimmungnebenanlagen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_zweckbestimmungnebenanlagen AS ENUM
  ('1000', '2000', '3000', '3100', '3200', '3300', '3400', '3500', '3600', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_zweckbestimmungnebenanlagen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_zweckbestimmungnebenanlagen IS 'Alias: "enum_BP_ZweckbestimmungNebenanlagen"';
INSERT INTO enum_bp_zweckbestimmungnebenanlagen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Stellplaetze', 'Stellplätze'),
('2000', 'Garagen', 'Garagen'),
('3000', 'Spielplatz', 'Spielplatz'),
('3100', 'Carport', 'Carport'),
('3200', 'Tiefgarage', 'Tiefgarage'),
('3300', 'Nebengebaeude', 'Nebengebäude'),
('3400', 'AbfallSammelanlagen', 'Sammelanlagen für Abfall.'),
('3500', 'EnergieVerteilungsanlagen', 'Energie-Verteilungsanlagen'),
('3600', 'AbfallWertstoffbehaelter', 'Abfall-Wertstoffbehälter'),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_speziellebauweisetypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_speziellebauweisetypen AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_speziellebauweisetypen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_speziellebauweisetypen IS 'Alias: "enum_BP_SpezielleBauweiseTypen"';
INSERT INTO enum_bp_speziellebauweisetypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Durchfahrt', 'Durchfahrt'),
('1100', 'Durchgang', 'Durchgang'),
('1200', 'DurchfahrtDurchgang', 'Durchfahrt oder Durchgang'),
('1300', 'Auskragung', 'Auskragung'),
('1400', 'Arkade', 'Arkade'),
('1500', 'Luftgeschoss', 'Luftgeschoss'),
('9999', 'Sonstiges', 'Sonstige spezielle Bauweise.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_bauweise'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_bauweise AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_bauweise (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_bauweise IS 'Alias: "enum_BP_Bauweise"';
INSERT INTO enum_bp_bauweise (wert,abkuerzung,beschreibung) VALUES
('1000', 'OffeneBauweise', 'Offene Bauweise'),
('2000', 'GeschlosseneBauweise', 'Geschlossene Bauweise'),
('3000', 'AbweichendeBauweise', 'Abweichende Bauweise');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_zweckbestimmunggemeinschaftsanlagen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen AS ENUM
  ('1000', '2000', '3000', '3100', '3200', '3300', '3400', '3500', '3600', '3700', '3800', '3900', '4000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_zweckbestimmunggemeinschaftsanlagen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_zweckbestimmunggemeinschaftsanlagen IS 'Alias: "enum_BP_ZweckbestimmungGemeinschaftsanlagen"';
INSERT INTO enum_bp_zweckbestimmunggemeinschaftsanlagen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Gemeinschaftsstellplaetze', 'Gemeinschaftliche Stellplätze'),
('2000', 'Gemeinschaftsgaragen', 'Gemeinschaftsgaragen'),
('3000', 'Spielplatz', 'Spielplatz'),
('3100', 'Carport', 'Carport'),
('3200', 'GemeinschaftsTiefgarage', 'Gemeinschafts-Tiefgarage'),
('3300', 'Nebengebaeude', 'Nebengebäude'),
('3400', 'AbfallSammelanlagen', 'Abfall-Sammelanlagen'),
('3500', 'EnergieVerteilungsanlagen', 'Energie-Verteilungsanlagen'),
('3600', 'AbfallWertstoffbehaelter', 'Abfall-Wertstoffbehälter'),
('3700', 'Freizeiteinrichtungen', 'Freizeiteinrichtungen'),
('3800', 'Laermschutzanlagen', 'Lärmschutz-Anlagen'),
('3900', 'AbwasserRegenwasser', 'Anlagen für Abwasser oder Regenwasser'),
('4000', 'Ausgleichsmassnahmen', 'Fläche für Ausgleichsmaßnahmen'),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_erhaltungsgrund'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_erhaltungsgrund AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_erhaltungsgrund (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_erhaltungsgrund IS 'Alias: "enum_BP_ErhaltungsGrund"';
INSERT INTO enum_bp_erhaltungsgrund (wert,abkuerzung,beschreibung) VALUES
('1000', 'StaedtebaulicheGestalt', 'Erhaltung der städtebaulichen Eigenart des Gebiets auf Grund seiner städtebaulichen Gestalt'),
('2000', 'Wohnbevoelkerung', 'Erhaltung der Zusammensetzung der Wohnbevölkerung'),
('3000', 'Umstrukturierung', 'Erhaltung bei städtebaulichen Umstrukturierungen');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_strassenkoerperherstellung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_strassenkoerperherstellung AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_strassenkoerperherstellung (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_strassenkoerperherstellung IS 'Alias: "enum_BP_StrassenkoerperHerstellung"';
INSERT INTO enum_bp_strassenkoerperherstellung (wert,abkuerzung,beschreibung) VALUES
('1000', 'Aufschuettung', 'Aufschüttung'),
('2000', 'Abgrabung', 'Abgrabung'),
('3000', 'Stuetzmauer', 'Stützmauer');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_abgrenzungentypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_abgrenzungentypen AS ENUM
  ('1000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_abgrenzungentypen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_abgrenzungentypen IS 'Alias: "enum_BP_AbgrenzungenTypen"';
INSERT INTO enum_bp_abgrenzungentypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Nutzungsartengrenze', 'Nutzungsarten-Grenze zur Abgrenzung von Baugebieten mit unterschiedlicher Art oder unterschiedlichem Maß der baulichen Nutzung.'),
('9999', 'SonstigeAbgrenzung', 'Sonstige Abgrenzung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_wegerechttypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_wegerechttypen AS ENUM
  ('1000', '2000', '3000', '4000', '4100', '4200', '5000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_wegerechttypen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_wegerechttypen IS 'Alias: "enum_BP_WegerechtTypen"';
INSERT INTO enum_bp_wegerechttypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'Gehrecht', 'Gehrecht'),
('2000', 'Fahrrecht', 'Fahrrecht'),
('3000', 'GehFahrrecht', 'Geh- und Fahrrecht'),
('4000', 'Leitungsrecht', 'Leitungsrecht'),
('4100', 'GehLeitungsrecht', 'Geh- und Leitungsrecht'),
('4200', 'FahrLeitungsrecht', 'Fahr- und Leitungsrecht'),
('5000', 'GehFahrLeitungsrecht', 'Geh-, Fahr- und Leitungsrecht');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_laerrmpegelbereich'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_laerrmpegelbereich AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_laerrmpegelbereich (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_laerrmpegelbereich IS 'Alias: "enum_BP_Laerrmpegelbereich"';
INSERT INTO enum_bp_laerrmpegelbereich (wert,abkuerzung,beschreibung) VALUES
('1000', 'I', 'Lärmpegelbereich I nach  DIN 4109.'),
('1100', 'II', 'Lärmpegelbereich II nach  DIN 4109.'),
('1200', 'III', 'Lärmpegelbereich III nach  DIN 4109.'),
('1300', 'IV', 'Lärmpegelbereich IV nach  DIN 4109.'),
('1400', 'V', 'Lärmpegelbereich V nach  DIN 4109.'),
('1500', 'VI', 'Lärmpegelbereich VI nach  DIN 4109.'),
('1600', 'VII', 'Lärmpegelbereich VII nach  DIN 4109.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_zweckbestimmungstrassenverkehr'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_zweckbestimmungstrassenverkehr AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1550', '1580', '1600', '1700', '1800', '2000', '2100', '2200', '2300', '2400', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_zweckbestimmungstrassenverkehr (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_zweckbestimmungstrassenverkehr IS 'Alias: "enum_BP_ZweckbestimmungStrassenverkehr"';
INSERT INTO enum_bp_zweckbestimmungstrassenverkehr (wert,abkuerzung,beschreibung) VALUES
('1000', 'Parkierungsflaeche', 'Fläche für das Parken von Fahrzeugen'),
('1100', 'Fussgaengerbereich', 'Fußgängerbereich'),
('1200', 'VerkehrsberuhigterBereich', 'Verkehrsberuhigte Zone'),
('1300', 'RadFussweg', 'Rad- und Fußweg'),
('1400', 'Radweg', 'Reiner Radweg'),
('1500', 'Fussweg', 'Reiner Fußweg'),
('1550', 'Wanderweg', 'Wanderweg'),
('1580', 'Wirtschaftsweg', 'Wirtschaftsweg'),
('1600', 'FahrradAbstellplatz', 'Abstellplatz für Fahrräder'),
('1700', 'UeberfuehrenderVerkehrsweg', 'Brückenbereich, hier der überführende Verkehrsweg.'),
('1800', 'UnterfuehrenderVerkehrsweg', 'Brückenbereich, hier der unterführende Verkehrsweg.'),
('2000', 'P_RAnlage', 'Park-and-Ride Anlage'),
('2100', 'Platz', 'Platz'),
('2200', 'Anschlussflaeche', 'Anschlussfläche'),
('2300', 'LandwirtschaftlicherVerkehr', 'Landwirtschaftlicher Verkehr'),
('2400', 'Verkehrsgruen', 'Verkehrsgrün'),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung Straßenverkehr.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_bereichohneeinausfahrttypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.bp_bereichohneeinausfahrttypen AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_bp_bereichohneeinausfahrttypen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_bp_bereichohneeinausfahrttypen IS 'Alias: "enum_BP_BereichOhneEinAusfahrtTypen"';
INSERT INTO enum_bp_bereichohneeinausfahrttypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'KeineEinfahrt', 'Bereich ohne Einfahrt'),
('2000', 'KeineAusfahrt', 'Bereich ohne Ausfahrt'),
('3000', 'KeineEinAusfahrt', 'Bereich ohne Ein- und Ausfahrt.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_rechtsstand'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_rechtsstand AS ENUM
  ('1000', '2000', '2100', '2200', '2300', '2400', '3000', '4000', '5000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_rechtsstand (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_rechtsstand IS 'Alias: "enum_FP_Rechtsstand"';
INSERT INTO enum_fp_rechtsstand (wert,abkuerzung,beschreibung) VALUES
('1000', 'Aufstellungsbeschluss', 'Der Aufstellungsbeschluss liegt vor.'),
('2000', 'Entwurf', 'Ein Planentwurf liegt vor.'),
('2100', 'FruehzeitigeBehoerdenBeteiligung', 'Die frühzeitige Bürgerbeteiligung ist abgeschlossen.'),
('2200', 'FruehzeitigeOeffentlichkeitsBeteiligung', 'Die frühzeitige Beteiligun der Öffentlichkeit ist abgeschlossen.'),
('2300', 'BehoerdenBeteiligung', 'Die Behördenbeteiligung ist abgeschlossen.'),
('2400', 'OeffentlicheAuslegung', 'Die öffentliche Auslegung ist beendet.'),
('3000', 'Plan', 'Der Plan ist technisch erstellt worden.'),
('4000', 'Wirksamkeit', 'Der Plan ist rechtswirksam.'),
('5000', 'Untergegangen', 'Der Plan ist untergegangen.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_rechtscharakter'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_rechtscharakter AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '9998');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_rechtscharakter (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_rechtscharakter IS 'Alias: "enum_FP_Rechtscharakter"';
INSERT INTO enum_fp_rechtscharakter (wert,abkuerzung,beschreibung) VALUES
('1000', 'Darstellung', 'Darstellung im Flächennutzungsplan'),
('2000', 'NachrichtlicheUebernahme', 'Nachrichtliche Übernahme aus anderen Planwerken.'),
('3000', 'Hinweis', 'Hinweis nach BauGB'),
('4000', 'Vermerk', 'Vermerk nach §9 BauGB'),
('5000', 'Kennzeichnung', 'Kennzeichnung nach §5 Abs. (3) BauGB.'),
('9998', 'Unbekannt', 'Der Rechtscharakter des FPlan-Inhaltes ist unbekannt.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_planart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_planart AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_planart (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_planart IS 'Alias: "enum_FP_PlanArt"';
INSERT INTO enum_fp_planart (wert,abkuerzung,beschreibung) VALUES
('1000', 'FPlan', 'Flächennutzungsplan nach § 5 BauGB.'),
('2000', 'GemeinsamerFPlan', 'Gemeinsamer Flächennutzungsplan nach § 204 BauGB'),
('3000', 'RegFPlan', 'Regionaler Flächennutzungsplan, der zugleich die Funktion eines Regionalplans als auch eines gemeinsamen Flächennutzungsplans nach § 204 BauGB erfüllt'),
('4000', 'FPlanRegPlan', 'Flächennutzungsplan mit regionalplanerischen Festlegungen (nur in HH, HB, B).'),
('5000', 'SachlicherTeilplan', 'Sachlicher Teilflächennutzungsplan nach §5 Abs. 2b BauGB.'),
('9999', 'Sonstiges', 'Sonstiger Flächennutzungsplan');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_verfahren'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_verfahren AS ENUM
  ('1000', '2000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_verfahren (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_verfahren IS 'Alias: "enum_FP_Verfahren"';
INSERT INTO enum_fp_verfahren (wert,abkuerzung,beschreibung) VALUES
('1000', 'Normal', 'Normales FPlan Verfahren.'),
('2000', 'Parag13', 'FPlan Verfahren nach Parag 13 BauGB.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_zweckbestimmungprivilegiertesvorhaben'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_zweckbestimmungprivilegiertesvorhaben AS ENUM
  ('1000', '10000', '10001', '10002', '10003', '10004', '1200', '12000', '12001', '12002', '12003', '12004', '12005', '1400', '1600', '16000', '16001', '16002', '1800', '18000', '18001', '18002', '18003', '2000', '20000', '20001', '9999', '99990', '99991');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_zweckbestimmungprivilegiertesvorhaben (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_zweckbestimmungprivilegiertesvorhaben IS 'Alias: "enum_FP_ZweckbestimmungPrivilegiertesVorhaben"';
INSERT INTO enum_fp_zweckbestimmungprivilegiertesvorhaben (wert,abkuerzung,beschreibung) VALUES
('1000', 'LandForstwirtschaft', 'Allgemeines Vorhaben nach §35 Abs. 1 Nr. 1 oder 2 BauGB: Vorhaben, dass "einem land- oder forstwirtschaftlichen Betrieb dient und nur einen untergeordneten Teil der Betriebsfläche einnimmt", oder "einem Betrieb der gartenbaulichen Erzeugung dient".'),
('10000', 'Aussiedlerhof', 'Aussiedlerhof'),
('10001', 'Altenteil', 'Altenteil'),
('10002', 'Reiterhof', 'Reiterhof'),
('10003', 'Gartenbaubetrieb', 'Gartenbaubetrieb'),
('10004', 'Baumschule', 'Baumschule'),
('1200', 'OeffentlicheVersorgung', 'Allgemeines Vorhaben nach § 35 Abs. 1 Nr. 3 BauBG: Vorhaben dass "der öffentlichen Versorgung mit Elektrizität, Gas,
Telekommunikationsdienstleistungen, Wärme und Wasser, der Abwasserwirtschaft" ... dient.'),
('12000', 'Wasser', 'Öffentliche Wasserversorgung'),
('12001', 'Gas', 'Gasversorgung'),
('12002', 'Waerme', 'Versorgung mit Fernwärme'),
('12003', 'Elektrizitaet', 'Versorgung mit Elektrizität.'),
('12004', 'Telekommunikation', 'Versorgung mit Telekommunikations-Dienstleistungen.'),
('12005', 'Abwasser', 'Abwasser Entsorgung'),
('1400', 'OrtsgebundenerGewerbebetrieb', 'Vorhaben nach §35 Abs. 1 Nr. 3 BauGB: Vorhaben das ...."einem ortsgebundenen gewerblichen Betrieb dient".'),
('1600', 'BesonderesVorhaben', 'Vorhaben nach §35 Abs. 1 Nr. 4 BauGB: Vorhaben, dass "wegen seiner besonderen Anforderungen an die Umgebung, wegen seiner nachteiligen Wirkung auf die Umgebung oder wegen seiner besonderen Zweckbestimmung nur im Außenbereich ausgeführt werden soll".'),
('16000', 'BesondereUmgebungsAnforderung', 'Vorhaben dass wegen seiner besonderen Anforderungen an die Umgebung nur im Außenbereich durchgeführt werden soll.'),
('16001', 'NachteiligeUmgebungsWirkung', 'Vorhaben dass wegen seiner nachteiligen Wirkung auf die Umgebung nur im Außenbereich durchgeführt werden soll.'),
('16002', 'BesondereZweckbestimmung', 'Vorhaben dass wegen seiner besonderen Zweckbestimmung nur im Außenbereich durchgeführt werden soll.'),
('1800', 'ErneuerbareEnergien', 'Allgemeine Vorhaben nach §35 Abs. 1 Nr. 4 BauGB: Vorhaben, dass "wegen seiner besonderen Anforderungen an die Umgebung, wegen seiner nachteiligen Wirkung auf die Umgebung oder wegen seiner besonderen Zweckbestimmung nur im Außenbereich ausgeführt werden soll".'),
('18000', 'Windenergie', 'Vorhaben zur Erforschung, Entwicklung oder Nutzung der Windenergie.'),
('18001', 'Wasserenergie', 'Vorhaben zur Erforschung, Entwicklung oder Nutzung der Wasserenergie.'),
('18002', 'Solarenergie', 'Vorhaben zur Erforschung, Entwicklung oder Nutzung der Solarenergie.'),
('18003', 'Biomasse', 'Vorhaben zur energetischen Nutzung der Biomasse.'),
('2000', 'Kernenergie', 'Vorhaben nach §35 Abs. 1 Nr. 7 BauGB: Vorhaben das "der Erforschung, Entwicklung oder Nutzung der Kernenergie zu friedlichen Zwecken oder der Entsorgung radioaktiver Abfälle dient".'),
('20000', 'NutzungKernerergie', 'Vorhaben der Erforschung, Entwicklung oder Nutzung der Kernenergie zu friedlichen Zwecken.'),
('20001', 'EntsorgungRadioaktiveAbfaelle', 'Vorhaben zur Entsorgung radioaktiver Abfälle.'),
('9999', 'Sonstiges', 'Sonstiges Vorhaben im Aussenbereich nach §35 Abs. 2 BauGB.'),
('99990', 'StandortEinzelhof', 'Einzelhof'),
('99991', 'BebauteFlaecheAussenbereich', 'Bebaute Fläche im Außenbereich');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_zweckbestimmungstrassenverkehr'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr AS ENUM
  ('1000', '1200', '1400', '14000', '14001', '140010', '140011', '14002', '14003', '14004', '14005', '14006', '14007', '14008', '14009', '1600', '16000', '16001', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_fp_zweckbestimmungstrassenverkehr (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_fp_zweckbestimmungstrassenverkehr IS 'Alias: "enum_FP_ZweckbestimmungStrassenverkehr"';
INSERT INTO enum_fp_zweckbestimmungstrassenverkehr (wert,abkuerzung,beschreibung) VALUES
('1000', 'Autobahn', 'Autobahn und autobahnähnliche Straße.'),
('1200', 'Hauptverkehrsstrasse', 'Sonstige örtliche oder überörtliche Hauptverkehrsstraße bzw. Weg.'),
('1400', 'SonstigerVerkehrswegAnlage', 'Sonstiger Verkehrsweg oder Anlage.'),
('14000', 'VerkehrsberuhigterBereich', 'Verkehrsberuhigter Bereich'),
('14001', 'Platz', 'Platz'),
('140010', 'UeberfuehrenderVerkehrsweg', 'Brückenbereich, hier: Überführender Verkehrsweg.'),
('140011', 'UnterfuehrenderVerkehrsweg', 'Brückenbereich, hier: Unterführender Verkehrsweg.'),
('14002', 'Fussgaengerbereich', 'Fußgängerbereich'),
('14003', 'RadFussweg', 'Rad- und Fußweg'),
('14004', 'Radweg', 'Radweg'),
('14005', 'Fussweg', 'Fußweg'),
('14006', 'Wanderweg', 'Wanderweg'),
('14007', 'ReitKutschweg', 'Reit- und Kutschweg'),
('14008', 'Rastanlage', 'Rastanlage'),
('14009', 'Busbahnhof', 'Busbahnhof'),
('1600', 'RuhenderVerkehr', 'Fläche oder Anlage für den ruhenden Verkehr.'),
('16000', 'Parkplatz', ''),
('16001', 'Fahrradabstellplatz', ''),
('9999', 'Sonstiges', 'Sonstige Zweckbestimmung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_rechtscharakter'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_rechtscharakter AS ENUM
  ('1000', '1500', '1800', '2000', '3000', '4000', '5000', '9998', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_rechtscharakter (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_rechtscharakter IS 'Alias: "enum_SO_Rechtscharakter"';
INSERT INTO enum_so_rechtscharakter (wert,abkuerzung,beschreibung) VALUES
('1000', 'FestsetzungBPlan', 'Festsetzung im Bebauungsplan'),
('1500', 'DarstellungFPlan', 'Darstellung im Flächennutzungsplan'),
('1800', 'InhaltLPlan', 'Inhalt eines Landschaftsplans'),
('2000', 'NachrichtlicheUebernahme', 'Nachrichtliche Übernahme aus anderen Planwerken.'),
('3000', 'Hinweis', 'Hinweis nach BauGB'),
('4000', 'Vermerk', 'Vermerk nach BauGB'),
('5000', 'Kennzeichnung', 'Kennzeichnung nach BauGB'),
('9998', 'Unbekannt', 'Der Rechtscharakter des Planinhalts ist unbekannt'),
('9999', 'Sonstiges', 'Sonstiger Rechtscharakter');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachdenkmalschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachdenkmalschutzrecht AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachdenkmalschutzrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachdenkmalschutzrecht IS 'Alias: "enum_SO_KlassifizNachDenkmalschutzrecht"';
INSERT INTO enum_so_klassifiznachdenkmalschutzrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'DenkmalschutzEnsemble', 'Denkmalschutz Ensemble'),
('1100', 'DenkmalschutzEinzelanlage', 'Denkmalschutz Einzelanlage'),
('1200', 'Grabungsschutzgebiet', 'Grabungsschutzgebiet'),
('1300', 'PufferzoneWeltkulturerbeEnger', 'Engere Pufferzone um eine Welterbestätte'),
('1400', 'PufferzoneWeltkulturerbeWeiter', 'Weitere Pufferzone um eine Welterbestätte'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung nach Denkmalschutzrecht.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachstrassenverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachstrassenverkehrsrecht AS ENUM
  ('1000', '1100', '1200', '1300', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachstrassenverkehrsrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachstrassenverkehrsrecht IS 'Alias: "enum_SO_KlassifizNachStrassenverkehrsrecht"';
INSERT INTO enum_so_klassifiznachstrassenverkehrsrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Bundesautobahn', 'Bundesautobahn'),
('1100', 'Bundesstrasse', 'Bundesstraße'),
('1200', 'LandesStaatsstrasse', 'Landes- oder Staatsstraße'),
('1300', 'Kreisstrasse', 'Kreisstraße'),
('9999', 'SonstOeffentlStrasse', 'Sonstige öffentliche Straße');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachsonstigemrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachsonstigemrecht AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachsonstigemrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachsonstigemrecht IS 'Alias: "enum_SO_KlassifizNachSonstigemRecht"';
INSERT INTO enum_so_klassifiznachsonstigemrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Bauschutzbereich', 'Bauschutzbereich nach anderen Rechtsverordnungen als dem LuftVG'),
('1100', 'Berggesetz', 'Beschränkung nach Berggesetz'),
('1200', 'Richtfunkverbindung', 'Baubeschränkungen durch Richtfunkverbindungen'),
('1300', 'Truppenuebungsplatz', 'Truppenübungsplatz'),
('1400', 'VermessungsKatasterrecht', 'Beschränkungen nach Vermessungs- und Katasterrecht'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachluftverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachluftverkehrsrecht AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '5200', '5400', '6000', '7000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachluftverkehrsrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachluftverkehrsrecht IS 'Alias: "enum_SO_KlassifizNachLuftverkehrsrecht"';
INSERT INTO enum_so_klassifiznachluftverkehrsrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Flughafen', 'Flughafen'),
('2000', 'Landeplatz', 'Landeplatz'),
('3000', 'Segelfluggelaende', 'Segelfluggelände'),
('4000', 'HubschrauberLandeplatz', 'Hubschrauber Landeplatz'),
('5000', 'Ballonstartplatz', 'Ballon Startplatz'),
('5200', 'Haengegleiter', 'Startplatz für Hängegleiter'),
('5400', 'Gleitsegler', 'Startplatz für Gleitsegler'),
('6000', 'Laermschutzbereich', 'Lärmschutzbereich nach LuftVG'),
('7000', 'Baubeschraenkungsbereich', 'Höhenbeschränkung nach §12 LuftVG'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung nach Luftverkehrsrecht.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachwasserrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachwasserrecht AS ENUM
  ('1000', '10000', '10001', '10002', '2000', '20000', '20001', '20002', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachwasserrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachwasserrecht IS 'Alias: "enum_SO_KlassifizNachWasserrecht"';
INSERT INTO enum_so_klassifiznachwasserrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Gewaesser', 'Allgemeines Gewässer'),
('10000', 'Gewaesser1Ordnung', 'Gewässer 1. Ordnung.'),
('10001', 'Gewaesser2Ordnung', 'Gewässer 2. Ordnung.'),
('10002', 'Gewaesser3Ordnung', 'Gewässer 3. Ordnung'),
('2000', 'Ueberschwemmungsgebiet', 'Überschwemmungsgebiet nach . § 31b Abs. 1 WHG  ist ein durch Rechtsverordnung festgesetztes oder natürliches Gebiet, das bei Hochwasser überschwemmt werden kann bzw. überschwemmt wird.'),
('20000', 'FestgesetztesUeberschwemmungsgebiet', 'Festgesetztes Überschwemmungsgebiet ist ein per Verordnung festgesetzte Überschwemmungsgebiete auf Basis HQ100'),
('20001', 'NochNichtFestgesetztesUeberschwemmungsgebiet', 'Noch nicht festgesetztes Überschwemmungsgebiet nach §31b Abs. 5 WHG.'),
('20002', 'UeberschwemmGefaehrdetesGebiet', 'Überschwemmungsgefährdetes Gebiet gemäß §31 c WHG.'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung nach Wasserrecht.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachbodenschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachbodenschutzrecht AS ENUM
  ('1000', '2000', '20000', '20001', '20002');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachbodenschutzrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachbodenschutzrecht IS 'Alias: "enum_SO_KlassifizNachBodenschutzrecht"';
INSERT INTO enum_so_klassifiznachbodenschutzrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'SchaedlicheBodenveraenderung', 'Schädliche Bodenveränderung'),
('2000', 'Altlast', 'Altlast'),
('20000', 'Altablagerung', 'Altablagerung'),
('20001', 'Altstandort', 'Altstandort'),
('20002', 'AltstandortAufAltablagerung', 'Altstandort einer Altablagerung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachforstrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachforstrecht AS ENUM
  ('1000', '2000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachforstrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachforstrecht IS 'Alias: "enum_SO_KlassifizNachForstrecht"';
INSERT INTO enum_so_klassifiznachforstrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'OeffentlicherWald', 'Öffentlicher wald'),
('2000', 'Privatwald', 'Privatwald'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung nach Forstrecht');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_laermschutzzonetypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_laermschutzzonetypen AS ENUM
  ('1000', '2000', '3000');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_laermschutzzonetypen (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_laermschutzzonetypen IS 'Alias: "enum_SO_LaermschutzzoneTypen"';
INSERT INTO enum_so_laermschutzzonetypen (wert,abkuerzung,beschreibung) VALUES
('1000', 'TagZone1', 'Tag-Zone 1'),
('2000', 'TagZone2', 'Tag-Zone 2'),
('3000', 'Nacht', 'Nacht-Zone');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifiznachschienenverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifiznachschienenverkehrsrecht AS ENUM
  ('1000', '10000', '10001', '10002', '10003', '1200', '12000', '12001', '12002', '12003', '12004', '12005', '1400', '14000', '14001', '14002', '14003', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifiznachschienenverkehrsrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifiznachschienenverkehrsrecht IS 'Alias: "enum_SO_KlassifizNachSchienenverkehrsrecht"';
INSERT INTO enum_so_klassifiznachschienenverkehrsrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Bahnanlage', 'Bahnanlage allgemein'),
('10000', 'DB_Bahnanlage', 'Bahnanlage der DB'),
('10001', 'Personenbahnhof', 'Personenbahnhof'),
('10002', 'Fernbahnhof', 'Fernbahnhof'),
('10003', 'Gueterbahnhof', 'Güterbahnhof'),
('1200', 'Bahnlinie', 'Bahnlinie allgemein'),
('12000', 'Personenbahnlinie', 'Personenbahnlinie'),
('12001', 'Regionalbahn', 'Regionalbahn'),
('12002', 'Kleinbahn', 'Kleinbahn'),
('12003', 'Gueterbahnlinie', 'Güterbahnlinie'),
('12004', 'WerksHafenbahn', 'Werks- oder Hafenbahnlinie.'),
('12005', 'Seilbahn', 'Seilbahn'),
('1400', 'OEPNV', 'Schienengebundener ÖPNV allgemein.'),
('14000', 'Strassenbahn', 'Straßenbahn'),
('14001', 'UBahn', 'U-Bahn'),
('14002', 'SBahn', 'S-Bahn'),
('14003', 'OEPNV_Haltestelle', 'Haltestelle im ÖPNV'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung nach Schienenverkehrsrecht.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_schutzzonenwasserrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_schutzzonenwasserrecht AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_schutzzonenwasserrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_schutzzonenwasserrecht IS 'Alias: "enum_SO_SchutzzonenWasserrecht"';
INSERT INTO enum_so_schutzzonenwasserrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Zone_1', 'Zone 1'),
('1100', 'Zone_2', 'Zone 2'),
('1200', 'Zone_3', 'Zone 3'),
('1300', 'Zone_3a', 'Zone 3a e(xistiert nur bei Wasserschutzgebieten).'),
('1400', 'Zone_3b', 'Zone 3b (existiert nur bei Wasserschutzgebieten).'),
('1500', 'Zone_4', 'Zone 4 e(xistiert nur bei Heilquellen).');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifizschutzgebietsonstrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifizschutzgebietsonstrecht AS ENUM
  ('1000', '2000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifizschutzgebietsonstrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifizschutzgebietsonstrecht IS 'Alias: "enum_SO_KlassifizSchutzgebietSonstRecht"';
INSERT INTO enum_so_klassifizschutzgebietsonstrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Laermschutzbereich', 'Lärmschutzbereich nach anderen gesetzlichen Regelungen als dem Luftverkehrsrecht.'),
('2000', 'SchutzzoneLeitungstrasse', 'Schutzzone um eine Leitungstrasse nach Bundes-Immissionsschutzgesetz.'),
('9999', 'Sonstiges', 'Sonstige Klassifizierung');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_klassifizschutzgebietwasserrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_klassifizschutzgebietwasserrecht AS ENUM
  ('1000', '10000', '10001', '2000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_klassifizschutzgebietwasserrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_klassifizschutzgebietwasserrecht IS 'Alias: "enum_SO_KlassifizSchutzgebietWasserrecht"';
INSERT INTO enum_so_klassifizschutzgebietwasserrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Wasserschutzgebiet', 'Wasserschutzgebiet'),
('10000', 'QuellGrundwasserSchutzgebiet', 'Ausgewiesenes Schutzgebiet für Quell- und Grundwasser'),
('10001', 'OberflaechengewaesserSchutzgebiet', 'Ausgewiesenes Schutzgebiet für Oberflächengewässer'),
('2000', 'Heilquellenschutzgebiet', 'Heilquellen Schutzgebiet'),
('9999', 'Sonstiges', 'Sonstiges Schutzgebiet nach Wasserrecht.');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_schutzzonennaturschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_schutzzonennaturschutzrecht AS ENUM
  ('1000', '1100', '1200', '2000', '2100', '2200', '2300');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_schutzzonennaturschutzrecht (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_schutzzonennaturschutzrecht IS 'Alias: "enum_SO_SchutzzonenNaturschutzrecht"';
INSERT INTO enum_so_schutzzonennaturschutzrecht (wert,abkuerzung,beschreibung) VALUES
('1000', 'Schutzzone_1', 'Schutzzone 1'),
('1100', 'Schutzzone_2', 'Schutzzone 2'),
('1200', 'Schutzzone_3', 'Schutzzone 3'),
('2000', 'Kernzone', 'Kernzone'),
('2100', 'Pflegezone', 'Pflegezone'),
('2200', 'Entwicklungszone', 'Entwicklungszone'),
('2300', 'Regenerationszone', 'Regenerationszone');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_gebietsart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_gebietsart AS ENUM
  ('1000', '1100', '1200', '1300', '1400', '1500', '1600', '1999', '2000', '2100', '2200', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_gebietsart (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_gebietsart IS 'Alias: "enum_SO_GebietsArt"';
INSERT INTO enum_so_gebietsart (wert,abkuerzung,beschreibung) VALUES
('1000', 'Umlegungsgebiet', 'Umlegungsgebiet (§ 45 ff BauGB).'),
('1100', 'StaedtebaulicheSanierung', 'Gebiet nach § 136 ff BauGB'),
('1200', 'StaedtebaulicheEntwicklungsmassnahme', 'Gebiet nach § 165 ff BauGB'),
('1300', 'Stadtumbaugebiet', 'Gebiet nach § 171 a-d BauGB'),
('1400', 'SozialeStadt', 'Gebiet nach § 171 e BauGB'),
('1500', 'BusinessImprovementDestrict', 'Gebiet nach §171 f BauGB'),
('1600', 'HousingImprovementDestrict', 'Gebiet nach §171 f BauGB'),
('1999', 'Erhaltungsverordnung', 'Allgemeine Erhaltungsverordnung'),
('2000', 'ErhaltungsverordnungStaedebaulicheGestalt', 'Gebiet einer Satzung nach § 172 Abs. 1.1 BauGB'),
('2100', 'ErhaltungsverordnungWohnbevoelkerung', 'Gebiet einer Satzung nach § 172 Abs. 1.2 BauGB'),
('2200', 'ErhaltungsverordnungUmstrukturierung', 'Gebiet einer Satzung nach § 172 Abs. 1.2 BauGB'),
('9999', 'Sonstiges', 'Sonstiger Gebietstyp');
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_rechtsstandgebiettyp'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xplan_gml.so_rechtsstandgebiettyp AS ENUM
  ('1000', '2000', '3000', '4000', '5000', '9999');
END IF;
END$$;

CREATE TABLE IF NOT EXISTS enum_so_rechtsstandgebiettyp (
  wert integer,
  abkuerzung character varying,
  beschreibung character varying,
  PRIMARY KEY (wert)
) WITH OIDS;

COMMENT ON TABLE enum_so_rechtsstandgebiettyp IS 'Alias: "enum_SO_RechtsstandGebietTyp"';
INSERT INTO enum_so_rechtsstandgebiettyp (wert,abkuerzung,beschreibung) VALUES
('1000', 'VorbereitendeUntersuchung', 'Vorbereitende Untersuchung'),
('2000', 'Aufstellung', 'Aufstellung'),
('3000', 'Festlegung', 'Festlegung'),
('4000', 'Abgeschlossen', 'Abgeschlossen'),
('5000', 'Verstetigung', 'Verstetigung'),
('9999', 'Sonstiges', 'Sonstiges');
CREATE TABLE IF NOT EXISTS xp_gesetzlichegrundlage (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE xp_gesetzlichegrundlage IS 'Alias: "XP_GesetzlicheGrundlage", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_gesetzlichegrundlage'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_gesetzlichegrundlage AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_gesetzlichegrundlage IS 'Alias: "XP_GesetzlicheGrundlage"';
COMMENT ON COLUMN xp_gesetzlichegrundlage.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xp_gesetzlichegrundlage.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS xp_mimetypes (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE xp_mimetypes IS 'Alias: "XP_MimeTypes", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_mimetypes'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_mimetypes AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_mimetypes IS 'Alias: "XP_MimeTypes"';
COMMENT ON COLUMN xp_mimetypes.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xp_mimetypes.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS xp_stylesheetliste (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE xp_stylesheetliste IS 'Alias: "XP_StylesheetListe", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_stylesheetliste'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_stylesheetliste AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_stylesheetliste IS 'Alias: "XP_StylesheetListe"';
COMMENT ON COLUMN xp_stylesheetliste.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN xp_stylesheetliste.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_sonstplanart (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_sonstplanart IS 'Alias: "BP_SonstPlanArt", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_sonstplanart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_sonstplanart AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_sonstplanart IS 'Alias: "BP_SonstPlanArt"';
COMMENT ON COLUMN bp_sonstplanart.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_sonstplanart.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_status (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_status IS 'Alias: "BP_Status", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_status'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_status AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_status IS 'Alias: "BP_Status"';
COMMENT ON COLUMN bp_status.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_status.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestgemeinschaftsanlagen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestgemeinschaftsanlagen IS 'Alias: "BP_DetailZweckbestGemeinschaftsanlagen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestgemeinschaftsanlagen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestgemeinschaftsanlagen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestgemeinschaftsanlagen IS 'Alias: "BP_DetailZweckbestGemeinschaftsanlagen"';
COMMENT ON COLUMN bp_detailzweckbestgemeinschaftsanlagen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestgemeinschaftsanlagen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_speziellebauweisesonsttypen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_speziellebauweisesonsttypen IS 'Alias: "BP_SpezielleBauweiseSonstTypen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_speziellebauweisesonsttypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_speziellebauweisesonsttypen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_speziellebauweisesonsttypen IS 'Alias: "BP_SpezielleBauweiseSonstTypen"';
COMMENT ON COLUMN bp_speziellebauweisesonsttypen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_speziellebauweisesonsttypen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detaildachform (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detaildachform IS 'Alias: "BP_DetailDachform", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detaildachform'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detaildachform AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detaildachform IS 'Alias: "BP_DetailDachform"';
COMMENT ON COLUMN bp_detaildachform.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detaildachform.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestnebenanlagen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestnebenanlagen IS 'Alias: "BP_DetailZweckbestNebenanlagen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestnebenanlagen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestnebenanlagen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestnebenanlagen IS 'Alias: "BP_DetailZweckbestNebenanlagen"';
COMMENT ON COLUMN bp_detailzweckbestnebenanlagen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestnebenanlagen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_abweichendebauweise (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_abweichendebauweise IS 'Alias: "BP_AbweichendeBauweise", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_abweichendebauweise'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_abweichendebauweise AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_abweichendebauweise IS 'Alias: "BP_AbweichendeBauweise"';
COMMENT ON COLUMN bp_abweichendebauweise.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_abweichendebauweise.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailartderbaulnutzung (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailartderbaulnutzung IS 'Alias: "BP_DetailArtDerBaulNutzung", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailartderbaulnutzung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailartderbaulnutzung AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailartderbaulnutzung IS 'Alias: "BP_DetailArtDerBaulNutzung"';
COMMENT ON COLUMN bp_detailartderbaulnutzung.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailartderbaulnutzung.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestgemeinbedarf (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestgemeinbedarf IS 'Alias: "BP_DetailZweckbestGemeinbedarf", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestgemeinbedarf'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestgemeinbedarf AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestgemeinbedarf IS 'Alias: "BP_DetailZweckbestGemeinbedarf"';
COMMENT ON COLUMN bp_detailzweckbestgemeinbedarf.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestgemeinbedarf.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestspielsportanlage (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestspielsportanlage IS 'Alias: "BP_DetailZweckbestSpielSportanlage", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestspielsportanlage'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestspielsportanlage AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestspielsportanlage IS 'Alias: "BP_DetailZweckbestSpielSportanlage"';
COMMENT ON COLUMN bp_detailzweckbestspielsportanlage.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestspielsportanlage.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS vegetationsobjekttypen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE vegetationsobjekttypen IS 'Alias: "VegetationsobjektTypen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'vegetationsobjekttypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE vegetationsobjekttypen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE vegetationsobjekttypen IS 'Alias: "VegetationsobjektTypen"';
COMMENT ON COLUMN vegetationsobjekttypen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN vegetationsobjekttypen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailabgrenzungentypen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailabgrenzungentypen IS 'Alias: "BP_DetailAbgrenzungenTypen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailabgrenzungentypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailabgrenzungentypen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailabgrenzungentypen IS 'Alias: "BP_DetailAbgrenzungenTypen"';
COMMENT ON COLUMN bp_detailabgrenzungentypen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailabgrenzungentypen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_zweckbestimmunggenerischeobjekte (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_zweckbestimmunggenerischeobjekte IS 'Alias: "BP_ZweckbestimmungGenerischeObjekte", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_zweckbestimmunggenerischeobjekte'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_zweckbestimmunggenerischeobjekte AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_zweckbestimmunggenerischeobjekte IS 'Alias: "BP_ZweckbestimmungGenerischeObjekte"';
COMMENT ON COLUMN bp_zweckbestimmunggenerischeobjekte.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_zweckbestimmunggenerischeobjekte.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestverentsorgung (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestverentsorgung IS 'Alias: "BP_DetailZweckbestVerEntsorgung", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestverentsorgung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestverentsorgung AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestverentsorgung IS 'Alias: "BP_DetailZweckbestVerEntsorgung"';
COMMENT ON COLUMN bp_detailzweckbestverentsorgung.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestverentsorgung.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbeststrassenverkehr (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbeststrassenverkehr IS 'Alias: "BP_DetailZweckbestStrassenverkehr", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbeststrassenverkehr'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbeststrassenverkehr AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbeststrassenverkehr IS 'Alias: "BP_DetailZweckbestStrassenverkehr"';
COMMENT ON COLUMN bp_detailzweckbeststrassenverkehr.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbeststrassenverkehr.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestwasserwirtschaft (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestwasserwirtschaft IS 'Alias: "BP_DetailZweckbestWasserwirtschaft", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestwasserwirtschaft'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestwasserwirtschaft AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestwasserwirtschaft IS 'Alias: "BP_DetailZweckbestWasserwirtschaft"';
COMMENT ON COLUMN bp_detailzweckbestwasserwirtschaft.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestwasserwirtschaft.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS bp_detailzweckbestgewaesser (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE bp_detailzweckbestgewaesser IS 'Alias: "BP_DetailZweckbestGewaesser", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'bp_detailzweckbestgewaesser'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE bp_detailzweckbestgewaesser AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE bp_detailzweckbestgewaesser IS 'Alias: "BP_DetailZweckbestGewaesser"';
COMMENT ON COLUMN bp_detailzweckbestgewaesser.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN bp_detailzweckbestgewaesser.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_status (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_status IS 'Alias: "FP_Status", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_status'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_status AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_status IS 'Alias: "FP_Status"';
COMMENT ON COLUMN fp_status.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_status.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_spezifischepraegungtypen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_spezifischepraegungtypen IS 'Alias: "FP_SpezifischePraegungTypen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_spezifischepraegungtypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_spezifischepraegungtypen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_spezifischepraegungtypen IS 'Alias: "FP_SpezifischePraegungTypen"';
COMMENT ON COLUMN fp_spezifischepraegungtypen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_spezifischepraegungtypen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_sonstplanart (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_sonstplanart IS 'Alias: "FP_SonstPlanArt", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_sonstplanart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_sonstplanart AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_sonstplanart IS 'Alias: "FP_SonstPlanArt"';
COMMENT ON COLUMN fp_sonstplanart.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_sonstplanart.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailartderbaulnutzung (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailartderbaulnutzung IS 'Alias: "FP_DetailArtDerBaulNutzung", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailartderbaulnutzung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailartderbaulnutzung AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailartderbaulnutzung IS 'Alias: "FP_DetailArtDerBaulNutzung"';
COMMENT ON COLUMN fp_detailartderbaulnutzung.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailartderbaulnutzung.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestspielsportanlage (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestspielsportanlage IS 'Alias: "FP_DetailZweckbestSpielSportanlage", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestspielsportanlage'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestspielsportanlage AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestspielsportanlage IS 'Alias: "FP_DetailZweckbestSpielSportanlage"';
COMMENT ON COLUMN fp_detailzweckbestspielsportanlage.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestspielsportanlage.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestgemeinbedarf (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestgemeinbedarf IS 'Alias: "FP_DetailZweckbestGemeinbedarf", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestgemeinbedarf'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestgemeinbedarf AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestgemeinbedarf IS 'Alias: "FP_DetailZweckbestGemeinbedarf"';
COMMENT ON COLUMN fp_detailzweckbestgemeinbedarf.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestgemeinbedarf.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestgruen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestgruen IS 'Alias: "FP_DetailZweckbestGruen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestgruen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestgruen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestgruen IS 'Alias: "FP_DetailZweckbestGruen"';
COMMENT ON COLUMN fp_detailzweckbestgruen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestgruen.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestwaldflaeche (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestwaldflaeche IS 'Alias: "FP_DetailZweckbestWaldFlaeche", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestwaldflaeche'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestwaldflaeche AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestwaldflaeche IS 'Alias: "FP_DetailZweckbestWaldFlaeche"';
COMMENT ON COLUMN fp_detailzweckbestwaldflaeche.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestwaldflaeche.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestlandwirtschaftsflaeche (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestlandwirtschaftsflaeche IS 'Alias: "FP_DetailZweckbestLandwirtschaftsFlaeche", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestlandwirtschaftsflaeche'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestlandwirtschaftsflaeche AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestlandwirtschaftsflaeche IS 'Alias: "FP_DetailZweckbestLandwirtschaftsFlaeche"';
COMMENT ON COLUMN fp_detailzweckbestlandwirtschaftsflaeche.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestlandwirtschaftsflaeche.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_zweckbestimmunggenerischeobjekte (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_zweckbestimmunggenerischeobjekte IS 'Alias: "FP_ZweckbestimmungGenerischeObjekte", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_zweckbestimmunggenerischeobjekte'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_zweckbestimmunggenerischeobjekte AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_zweckbestimmunggenerischeobjekte IS 'Alias: "FP_ZweckbestimmungGenerischeObjekte"';
COMMENT ON COLUMN fp_zweckbestimmunggenerischeobjekte.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_zweckbestimmunggenerischeobjekte.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbeststrassenverkehr (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbeststrassenverkehr IS 'Alias: "FP_DetailZweckbestStrassenverkehr", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbeststrassenverkehr'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbeststrassenverkehr AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbeststrassenverkehr IS 'Alias: "FP_DetailZweckbestStrassenverkehr"';
COMMENT ON COLUMN fp_detailzweckbeststrassenverkehr.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbeststrassenverkehr.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestgewaesser (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestgewaesser IS 'Alias: "FP_DetailZweckbestGewaesser", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestgewaesser'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestgewaesser AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestgewaesser IS 'Alias: "FP_DetailZweckbestGewaesser"';
COMMENT ON COLUMN fp_detailzweckbestgewaesser.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestgewaesser.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS fp_detailzweckbestwasserwirtschaft (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE fp_detailzweckbestwasserwirtschaft IS 'Alias: "FP_DetailZweckbestWasserwirtschaft", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'fp_detailzweckbestwasserwirtschaft'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE fp_detailzweckbestwasserwirtschaft AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE fp_detailzweckbestwasserwirtschaft IS 'Alias: "FP_DetailZweckbestWasserwirtschaft"';
COMMENT ON COLUMN fp_detailzweckbestwasserwirtschaft.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN fp_detailzweckbestwasserwirtschaft.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_sonstrechtscharakter (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_sonstrechtscharakter IS 'Alias: "SO_SonstRechtscharakter", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_sonstrechtscharakter'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_sonstrechtscharakter AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_sonstrechtscharakter IS 'Alias: "SO_SonstRechtscharakter"';
COMMENT ON COLUMN so_sonstrechtscharakter.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_sonstrechtscharakter.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_planart (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_planart IS 'Alias: "SO_PlanArt", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_planart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_planart AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_planart IS 'Alias: "SO_PlanArt"';
COMMENT ON COLUMN so_planart.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_planart.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachluftverkehrsrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachluftverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachLuftverkehrsrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachluftverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachluftverkehrsrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachluftverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachLuftverkehrsrecht"';
COMMENT ON COLUMN so_detailklassifiznachluftverkehrsrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachluftverkehrsrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachsonstigemrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachsonstigemrecht IS 'Alias: "SO_DetailKlassifizNachSonstigemRecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachsonstigemrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachsonstigemrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachsonstigemrecht IS 'Alias: "SO_DetailKlassifizNachSonstigemRecht"';
COMMENT ON COLUMN so_detailklassifiznachsonstigemrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachsonstigemrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachstrassenverkehrsrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachstrassenverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachStrassenverkehrsrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachstrassenverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachstrassenverkehrsrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachstrassenverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachStrassenverkehrsrecht"';
COMMENT ON COLUMN so_detailklassifiznachstrassenverkehrsrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachstrassenverkehrsrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachforstrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachforstrecht IS 'Alias: "SO_DetailKlassifizNachForstrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachforstrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachforstrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachforstrecht IS 'Alias: "SO_DetailKlassifizNachForstrecht"';
COMMENT ON COLUMN so_detailklassifiznachforstrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachforstrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachdenkmalschutzrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachdenkmalschutzrecht IS 'Alias: "SO_DetailKlassifizNachDenkmalschutzrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachdenkmalschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachdenkmalschutzrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachdenkmalschutzrecht IS 'Alias: "SO_DetailKlassifizNachDenkmalschutzrecht"';
COMMENT ON COLUMN so_detailklassifiznachdenkmalschutzrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachdenkmalschutzrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachbodenschutzrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachbodenschutzrecht IS 'Alias: "SO_DetailKlassifizNachBodenschutzrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachbodenschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachbodenschutzrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachbodenschutzrecht IS 'Alias: "SO_DetailKlassifizNachBodenschutzrecht"';
COMMENT ON COLUMN so_detailklassifiznachbodenschutzrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachbodenschutzrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachschienenverkehrsrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachschienenverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachSchienenverkehrsrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachschienenverkehrsrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachschienenverkehrsrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachschienenverkehrsrecht IS 'Alias: "SO_DetailKlassifizNachSchienenverkehrsrecht"';
COMMENT ON COLUMN so_detailklassifiznachschienenverkehrsrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachschienenverkehrsrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifiznachwasserrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifiznachwasserrecht IS 'Alias: "SO_DetailKlassifizNachWasserrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifiznachwasserrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifiznachwasserrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifiznachwasserrecht IS 'Alias: "SO_DetailKlassifizNachWasserrecht"';
COMMENT ON COLUMN so_detailklassifiznachwasserrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifiznachwasserrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifizschutzgebietsonstrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifizschutzgebietsonstrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietSonstRecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifizschutzgebietsonstrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifizschutzgebietsonstrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifizschutzgebietsonstrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietSonstRecht"';
COMMENT ON COLUMN so_detailklassifizschutzgebietsonstrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifizschutzgebietsonstrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifizschutzgebietwasserrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifizschutzgebietwasserrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietWasserrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifizschutzgebietwasserrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifizschutzgebietwasserrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifizschutzgebietwasserrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietWasserrecht"';
COMMENT ON COLUMN so_detailklassifizschutzgebietwasserrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifizschutzgebietwasserrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_detailklassifizschutzgebietnaturschutzrecht (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_detailklassifizschutzgebietnaturschutzrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietNaturschutzrecht", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_detailklassifizschutzgebietnaturschutzrecht'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_detailklassifizschutzgebietnaturschutzrecht AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_detailklassifizschutzgebietnaturschutzrecht IS 'Alias: "SO_DetailKlassifizSchutzgebietNaturschutzrecht"';
COMMENT ON COLUMN so_detailklassifizschutzgebietnaturschutzrecht.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_detailklassifizschutzgebietnaturschutzrecht.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_sonstrechtsstandgebiettyp (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_sonstrechtsstandgebiettyp IS 'Alias: "SO_SonstRechtsstandGebietTyp", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_sonstrechtsstandgebiettyp'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_sonstrechtsstandgebiettyp AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_sonstrechtsstandgebiettyp IS 'Alias: "SO_SonstRechtsstandGebietTyp"';
COMMENT ON COLUMN so_sonstrechtsstandgebiettyp.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_sonstrechtsstandgebiettyp.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_sonstgebietsart (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_sonstgebietsart IS 'Alias: "SO_SonstGebietsArt", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_sonstgebietsart'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_sonstgebietsart AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_sonstgebietsart IS 'Alias: "SO_SonstGebietsArt"';
COMMENT ON COLUMN so_sonstgebietsart.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_sonstgebietsart.id IS 'id  character varying ';
CREATE TABLE IF NOT EXISTS so_sonstgrenzetypen (
  codespace text,
  id character varying,
  value text,
  PRIMARY KEY (id)
) WITH OIDS;

COMMENT ON TABLE so_sonstgrenzetypen IS 'Alias: "SO_SonstGrenzeTypen", UML-Typ: Code Liste';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'so_sonstgrenzetypen'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE so_sonstgrenzetypen AS (
  codespace text,
  id character varying
);
END IF;
END$$;
COMMENT ON TYPE so_sonstgrenzetypen IS 'Alias: "SO_SonstGrenzeTypen"';
COMMENT ON COLUMN so_sonstgrenzetypen.codespace IS 'codeSpace  text ';
COMMENT ON COLUMN so_sonstgrenzetypen.id IS 'id  character varying ';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_liniengeometrie'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_liniengeometrie AS (
  linie geometry,
  multilinie geometry
);
END IF;
END$$;
COMMENT ON TYPE xp_liniengeometrie IS 'Alias: "XP_Liniengeometrie",  1,  1';
COMMENT ON COLUMN xp_liniengeometrie.linie IS 'Linie  GM_Curve 1';
COMMENT ON COLUMN xp_liniengeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_punktgeometrie'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_punktgeometrie AS (
  punkt geometry(POINT),
  multipunkt geometry(MULTIPOINT)
);
END IF;
END$$;
COMMENT ON TYPE xp_punktgeometrie IS 'Alias: "XP_Punktgeometrie",  1,  1';
COMMENT ON COLUMN xp_punktgeometrie.punkt IS 'Punkt  GM_Point 1';
COMMENT ON COLUMN xp_punktgeometrie.multipunkt IS 'MultiPunkt  GM_MultiPoint 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_variablegeometrie'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_variablegeometrie AS (
  linie geometry,
  multipunkt geometry,
  punkt geometry(POINT),
  multilinie geometry,
  flaeche geometry(POLYGON),
  multiflaeche geometry(MULTIPOLYGON)
);
END IF;
END$$;
COMMENT ON TYPE xp_variablegeometrie IS 'Alias: "XP_VariableGeometrie",  1,  1,  1,  1,  1,  1';
COMMENT ON COLUMN xp_variablegeometrie.linie IS 'Linie  GM_Curve 1';
COMMENT ON COLUMN xp_variablegeometrie.multipunkt IS 'MultiPunkt  GM_MultiCurve 1';
COMMENT ON COLUMN xp_variablegeometrie.punkt IS 'Punkt  GM_Point 1';
COMMENT ON COLUMN xp_variablegeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';
COMMENT ON COLUMN xp_variablegeometrie.flaeche IS 'Flaeche  GM_Surface 1';
COMMENT ON COLUMN xp_variablegeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_flaechengeometrie'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_flaechengeometrie AS (
  flaeche geometry(POLYGON),
  multiflaeche geometry(MULTIPOLYGON)
);
END IF;
END$$;
COMMENT ON TYPE xp_flaechengeometrie IS 'Alias: "XP_Flaechengeometrie",  1,  1';
COMMENT ON COLUMN xp_flaechengeometrie.flaeche IS 'Flaeche  GM_Surface 1';
COMMENT ON COLUMN xp_flaechengeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'sc_crs'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE sc_crs AS (
  scope character varying[]
);
END IF;
END$$;
COMMENT ON TYPE sc_crs IS 'Alias: "SC_CRS", ISO 19136 GML Type: scope 1..*';
COMMENT ON COLUMN sc_crs.scope IS 'scope CharacterString CharacterString 1..*';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'query'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE query AS (
  url character varying
);
END IF;
END$$;
COMMENT ON TYPE query IS 'Alias: "Query", wfs:Query nach Web Feature Service Specifikation, Version 1.0.0: url 0..1';
COMMENT ON COLUMN query.url IS 'url CharacterString CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'transaction'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE transaction AS (
  content text
);
END IF;
END$$;
COMMENT ON TYPE transaction IS 'Alias: "Transaction", wfs:Transaction nach Web Feature Service Specifikation, Version 1.0.0: content 0..1';
COMMENT ON COLUMN transaction.content IS 'content CharacterString Text 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'doublelist'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE doublelist AS (
  list character varying
);
END IF;
END$$;
COMMENT ON TYPE doublelist IS 'Alias: "doubleList", ISO 19136 GML Type: list';
COMMENT ON COLUMN doublelist.list IS 'list Sequence Sequence 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'measure'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE measure AS (
  value integer
);
END IF;
END$$;
COMMENT ON TYPE measure IS 'Alias: "Measure", ISO 19136 GML Type: value';
COMMENT ON COLUMN measure.value IS 'value DataType Integer 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_spemassnahmendaten'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_spemassnahmendaten AS (
  klassifizmassnahme xp_spemassnahmentypen,
  massnahmekuerzel character varying,
  massnahmetext character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_spemassnahmendaten IS 'Alias: "XP_SPEMassnahmenDaten", UML-Classifier: XP_SPEMassnahmenTypen Stereotyp: enumeration [0..1],  [0..1],  [0..1]';
COMMENT ON COLUMN xp_spemassnahmendaten.klassifizmassnahme IS 'klassifizMassnahme enumeration XP_SPEMassnahmenTypen 0..1';
COMMENT ON COLUMN xp_spemassnahmendaten.massnahmekuerzel IS 'massnahmeKuerzel  CharacterString 0..1';
COMMENT ON COLUMN xp_spemassnahmendaten.massnahmetext IS 'massnahmeText  CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_verfahrensmerkmal'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_verfahrensmerkmal AS (
  signatur character varying,
  vermerk character varying,
  datum date,
  signiert character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_verfahrensmerkmal IS 'Alias: "XP_VerfahrensMerkmal",  1,  1,  1,  1';
COMMENT ON COLUMN xp_verfahrensmerkmal.signatur IS 'signatur  CharacterString 1';
COMMENT ON COLUMN xp_verfahrensmerkmal.vermerk IS 'vermerk  CharacterString 1';
COMMENT ON COLUMN xp_verfahrensmerkmal.datum IS 'datum  Date 1';
COMMENT ON COLUMN xp_verfahrensmerkmal.signiert IS 'signiert  Boolean 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_generattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_generattribut AS (
  name character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_generattribut IS 'Alias: "XP_GenerAttribut",  1';
COMMENT ON COLUMN xp_generattribut.name IS 'name  CharacterString 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_urlattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_urlattribut AS (
  wert character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_urlattribut IS 'Alias: "XP_URLAttribut",  1';
COMMENT ON COLUMN xp_urlattribut.wert IS 'wert  URI 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_integerattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_integerattribut AS (
  wert integer
);
END IF;
END$$;
COMMENT ON TYPE xp_integerattribut IS 'Alias: "XP_IntegerAttribut",  1';
COMMENT ON COLUMN xp_integerattribut.wert IS 'wert  Integer 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_doubleattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_doubleattribut AS (
  wert double precision
);
END IF;
END$$;
COMMENT ON TYPE xp_doubleattribut IS 'Alias: "XP_DoubleAttribut",  1';
COMMENT ON COLUMN xp_doubleattribut.wert IS 'wert  Decimal 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_datumattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_datumattribut AS (
  wert date
);
END IF;
END$$;
COMMENT ON TYPE xp_datumattribut IS 'Alias: "XP_DatumAttribut",  1';
COMMENT ON COLUMN xp_datumattribut.wert IS 'wert  Date 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_stringattribut'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_stringattribut AS (
  wert character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_stringattribut IS 'Alias: "XP_StringAttribut",  1';
COMMENT ON COLUMN xp_stringattribut.wert IS 'wert  CharacterString 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_plangeber'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_plangeber AS (
  name character varying,
  kennziffer character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_plangeber IS 'Alias: "XP_Plangeber",  1,  [0..1]';
COMMENT ON COLUMN xp_plangeber.name IS 'name  CharacterString 1';
COMMENT ON COLUMN xp_plangeber.kennziffer IS 'kennziffer  CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_wirksamkeitbedingung'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_wirksamkeitbedingung AS (
  datumrelativ date,
  datumabsolut date,
  bedingung character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_wirksamkeitbedingung IS 'Alias: "XP_WirksamkeitBedingung",  [0..1],  [0..1],  [0..1]';
COMMENT ON COLUMN xp_wirksamkeitbedingung.datumrelativ IS 'datumRelativ  TM_Duration 0..1';
COMMENT ON COLUMN xp_wirksamkeitbedingung.datumabsolut IS 'datumAbsolut  Date 0..1';
COMMENT ON COLUMN xp_wirksamkeitbedingung.bedingung IS 'bedingung  CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_hoehenangabe'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_hoehenangabe AS (
  hmax double precision,
  hoehenbezug xp_arthoehenbezug,
  abweichenderbezugspunkt character varying,
  hzwingend double precision,
  abweichenderhoehenbezug character varying,
  h double precision,
  bezugspunkt xp_arthoehenbezugspunkt,
  hmin double precision
);
END IF;
END$$;
COMMENT ON TYPE xp_hoehenangabe IS 'Alias: "XP_Hoehenangabe",  [0..1], UML-Classifier: XP_ArtHoehenbezug Stereotyp: enumeration [0..1],  [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_ArtHoehenbezugspunkt Stereotyp: enumeration [0..1],  [0..1]';
COMMENT ON COLUMN xp_hoehenangabe.hmax IS 'hMax  Length 0..1';
COMMENT ON COLUMN xp_hoehenangabe.hoehenbezug IS 'hoehenbezug enumeration XP_ArtHoehenbezug 0..1';
COMMENT ON COLUMN xp_hoehenangabe.abweichenderbezugspunkt IS 'abweichenderBezugspunkt  CharacterString 0..1';
COMMENT ON COLUMN xp_hoehenangabe.hzwingend IS 'hZwingend  Length 0..1';
COMMENT ON COLUMN xp_hoehenangabe.abweichenderhoehenbezug IS 'abweichenderHoehenbezug  CharacterString 0..1';
COMMENT ON COLUMN xp_hoehenangabe.h IS 'h  Length 0..1';
COMMENT ON COLUMN xp_hoehenangabe.bezugspunkt IS 'bezugspunkt enumeration XP_ArtHoehenbezugspunkt 0..1';
COMMENT ON COLUMN xp_hoehenangabe.hmin IS 'hMin  Length 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_verbundenerplan'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_verbundenerplan AS (
  planname character varying,
  nummer character varying,
  rechtscharakter xp_rechtscharakterplanaenderung
);
END IF;
END$$;
COMMENT ON TYPE xp_verbundenerplan IS 'Alias: "XP_VerbundenerPlan",  [0..1],  [0..1], UML-Classifier: XP_RechtscharakterPlanaenderung Stereotyp: enumeration 1';
COMMENT ON COLUMN xp_verbundenerplan.planname IS 'planName  CharacterString 0..1';
COMMENT ON COLUMN xp_verbundenerplan.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN xp_verbundenerplan.rechtscharakter IS 'rechtscharakter enumeration XP_RechtscharakterPlanaenderung 1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_gemeinde'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_gemeinde AS (
  gemeindename character varying,
  ortsteilname character varying,
  rs character varying,
  ags character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_gemeinde IS 'Alias: "XP_Gemeinde",  [0..1],  [0..1],  [0..1],  [0..1]';
COMMENT ON COLUMN xp_gemeinde.gemeindename IS 'gemeindeName  CharacterString 0..1';
COMMENT ON COLUMN xp_gemeinde.ortsteilname IS 'ortsteilName  CharacterString 0..1';
COMMENT ON COLUMN xp_gemeinde.rs IS 'rs  CharacterString 0..1';
COMMENT ON COLUMN xp_gemeinde.ags IS 'ags  CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_externereferenz'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_externereferenz AS (
  referenzname character varying,
  art xp_externereferenzart,
  georefmimetype xp_mimetypes,
  georefurl character varying,
  referenzurl character varying,
  datum date,
  referenzmimetype xp_mimetypes,
  informationssystemurl character varying,
  beschreibung character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_externereferenz IS 'Alias: "XP_ExterneReferenz",  [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: enumeration [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1]';
COMMENT ON COLUMN xp_externereferenz.referenzname IS 'referenzName  CharacterString 0..1';
COMMENT ON COLUMN xp_externereferenz.art IS 'art enumeration XP_ExterneReferenzArt 0..1';
COMMENT ON COLUMN xp_externereferenz.georefmimetype IS 'georefMimeType CodeList XP_MimeTypes 0..1';
COMMENT ON COLUMN xp_externereferenz.georefurl IS 'georefURL  URI 0..1';
COMMENT ON COLUMN xp_externereferenz.referenzurl IS 'referenzURL  URI 0..1';
COMMENT ON COLUMN xp_externereferenz.datum IS 'datum  Date 0..1';
COMMENT ON COLUMN xp_externereferenz.referenzmimetype IS 'referenzMimeType CodeList XP_MimeTypes 0..1';
COMMENT ON COLUMN xp_externereferenz.informationssystemurl IS 'informationssystemURL  URI 0..1';
COMMENT ON COLUMN xp_externereferenz.beschreibung IS 'beschreibung  CharacterString 0..1';
DO $$
BEGIN
IF NOT EXISTS (
  SELECT
    1
  FROM
    pg_type t JOIN
    pg_namespace ns ON (t.typnamespace = ns.oid)
  WHERE
    t.typname = 'xp_spezexternereferenz'
    AND ns.nspname = 'xplan_gml'
) THEN
CREATE TYPE xp_spezexternereferenz AS (
  typ character varying
);
END IF;
END$$;
COMMENT ON TYPE xp_spezexternereferenz IS 'Alias: "XP_SpezExterneReferenz",  1';
COMMENT ON COLUMN xp_spezexternereferenz.typ IS 'typ 1';
CREATE TABLE IF NOT EXISTS xp_textabschnitt (
 gml_id text,
  gesetzlichegrundlage character varying,
  text character varying,
  schluessel character varying,
  reftext xp_externereferenz,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  inverszu_texte_xp_plan character(16),
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_textabschnitt IS 'FeatureType: "XP_TextAbschnitt"';
COMMENT ON COLUMN xp_textabschnitt.gesetzlichegrundlage IS 'gesetzlicheGrundlage  CharacterString 0..1';
COMMENT ON COLUMN xp_textabschnitt.text IS 'text  CharacterString 0..1';
COMMENT ON COLUMN xp_textabschnitt.schluessel IS 'schluessel  CharacterString 0..1';
COMMENT ON COLUMN xp_textabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN xp_textabschnitt.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_textabschnitt.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_textabschnitt.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_textabschnitt.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_textabschnitt.inverszu_texte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..1';
CREATE TABLE IF NOT EXISTS bp_textabschnitt (
  rechtscharakter bp_rechtscharakter NOT NULL,
  inverszu_reftextinhalt_bp_objekt character(16)[],
  inverszu_abweichungtext_bp_baugebietsteilflaeche character(16)[],
  inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche character(16)[]
) INHERITS (xp_textabschnitt) WITH OIDS;


COMMENT ON TABLE bp_textabschnitt IS 'FeatureType: "BP_TextAbschnitt"';
COMMENT ON COLUMN bp_textabschnitt.rechtscharakter IS 'rechtscharakter enumeration BP_Rechtscharakter 1';
COMMENT ON COLUMN bp_textabschnitt.inverszu_reftextinhalt_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
COMMENT ON COLUMN bp_textabschnitt.inverszu_abweichungtext_bp_baugebietsteilflaeche IS 'Assoziation zu: FeatureType BP_BaugebietsTeilFlaeche (bp_baugebietsteilflaeche) 0..*';
COMMENT ON COLUMN bp_textabschnitt.inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche IS 'Assoziation zu: FeatureType BP_NebenanlagenAusschlussFlaeche (bp_nebenanlagenausschlussflaeche) 0..*';
CREATE TABLE IF NOT EXISTS fp_textabschnitt (
  rechtscharakter fp_rechtscharakter NOT NULL,
  inverszu_reftextinhalt_fp_objekt character(16)[]
) INHERITS (xp_textabschnitt) WITH OIDS;


COMMENT ON TABLE fp_textabschnitt IS 'FeatureType: "FP_TextAbschnitt"';
COMMENT ON COLUMN fp_textabschnitt.rechtscharakter IS 'rechtscharakter enumeration FP_Rechtscharakter 1';
COMMENT ON COLUMN fp_textabschnitt.inverszu_reftextinhalt_fp_objekt IS 'Assoziation zu: FeatureType FP_Objekt (fp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS so_textabschnitt (
  rechtscharakter so_rechtscharakter NOT NULL,
  inverszu_reftextinhalt_so_objekt character(16)[]
) INHERITS (xp_textabschnitt) WITH OIDS;


COMMENT ON TABLE so_textabschnitt IS 'FeatureType: "SO_TextAbschnitt"';
COMMENT ON COLUMN so_textabschnitt.rechtscharakter IS 'rechtscharakter enumeration SO_Rechtscharakter 1';
COMMENT ON COLUMN so_textabschnitt.inverszu_reftextinhalt_so_objekt IS 'Assoziation zu: FeatureType SO_Objekt (so_objekt) 0..*';
CREATE TABLE IF NOT EXISTS xp_begruendungabschnitt (
 gml_id text,
  text character varying,
  schluessel character varying,
  reftext xp_externereferenz,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  inverszu_refbegruendunginhalt_xp_objekt character(16)[],
  inverszu_begruendungstexte_xp_plan character(16),
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_begruendungabschnitt IS 'FeatureType: "XP_BegruendungAbschnitt"';
COMMENT ON COLUMN xp_begruendungabschnitt.text IS 'text  CharacterString 0..1';
COMMENT ON COLUMN xp_begruendungabschnitt.schluessel IS 'schluessel  CharacterString 0..1';
COMMENT ON COLUMN xp_begruendungabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN xp_begruendungabschnitt.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_begruendungabschnitt.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_begruendungabschnitt.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_begruendungabschnitt.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_refbegruendunginhalt_xp_objekt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';
COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..1';
CREATE TABLE IF NOT EXISTS xp_objekt (
 gml_id text,
  externereferenz xp_spezexternereferenz[],
  gesetzlichegrundlage xp_gesetzlichegrundlage,
  ebene integer,
  gliederung1 character varying,
  text character varying,
  uuid character varying,
  endebedingung xp_wirksamkeitbedingung,
  hoehenangabe xp_hoehenangabe[],
  gliederung2 character varying,
  startbedingung xp_wirksamkeitbedingung,
  hatgenerattribut xp_generattribut[],
  rechtsstand xp_rechtsstand,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  refbegruendunginhalt character(16)[],
  wirddargestelltdurch character(16)[],
  gehoertzubereich character(16),
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_objekt IS 'FeatureType: "XP_Objekt"';
COMMENT ON COLUMN xp_objekt.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';
COMMENT ON COLUMN xp_objekt.gesetzlichegrundlage IS 'gesetzlicheGrundlage CodeList XP_GesetzlicheGrundlage 0..1';
COMMENT ON COLUMN xp_objekt.ebene IS 'ebene  Integer 0..1';
COMMENT ON COLUMN xp_objekt.gliederung1 IS 'gliederung1  CharacterString 0..1';
COMMENT ON COLUMN xp_objekt.text IS 'text  CharacterString 0..1';
COMMENT ON COLUMN xp_objekt.uuid IS 'uuid  CharacterString 0..1';
COMMENT ON COLUMN xp_objekt.endebedingung IS 'endeBedingung DataType XP_WirksamkeitBedingung 0..1';
COMMENT ON COLUMN xp_objekt.hoehenangabe IS 'hoehenangabe DataType XP_Hoehenangabe 0..*';
COMMENT ON COLUMN xp_objekt.gliederung2 IS 'gliederung2  CharacterString 0..1';
COMMENT ON COLUMN xp_objekt.startbedingung IS 'startBedingung DataType XP_WirksamkeitBedingung 0..1';
COMMENT ON COLUMN xp_objekt.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';
COMMENT ON COLUMN xp_objekt.rechtsstand IS 'rechtsstand enumeration XP_Rechtsstand 0..1';
COMMENT ON COLUMN xp_objekt.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_objekt.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_objekt.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_objekt.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_objekt.refbegruendunginhalt IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';
COMMENT ON COLUMN xp_objekt.wirddargestelltdurch IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';
COMMENT ON COLUMN xp_objekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';
CREATE TABLE IF NOT EXISTS bp_objekt (
  rechtscharakter bp_rechtscharakter NOT NULL,
  wirdausgeglichendurchspemassnahme character(16)[],
  wirdausgeglichendurchmassnahme character(16)[],
  wirdausgeglichendurchspeflaeche character(16)[],
  wirdausgeglichendurchflaeche character(16)[],
  wirdausgeglichendurchabe character(16)[],
  reftextinhalt character(16)[]
) INHERITS (xp_objekt) WITH OIDS;


COMMENT ON TABLE bp_objekt IS 'FeatureType: "BP_Objekt"';
COMMENT ON COLUMN bp_objekt.rechtscharakter IS 'rechtscharakter enumeration BP_Rechtscharakter 1';
COMMENT ON COLUMN bp_objekt.wirdausgeglichendurchspemassnahme IS 'Assoziation zu: FeatureType BP_SchutzPflegeEntwicklungsMassnahme (bp_schutzpflegeentwicklungsmassnahme) 0..*';
COMMENT ON COLUMN bp_objekt.wirdausgeglichendurchmassnahme IS 'Assoziation zu: FeatureType BP_AusgleichsMassnahme (bp_ausgleichsmassnahme) 0..*';
COMMENT ON COLUMN bp_objekt.wirdausgeglichendurchspeflaeche IS 'Assoziation zu: FeatureType BP_SchutzPflegeEntwicklungsFlaeche (bp_schutzpflegeentwicklungsflaeche) 0..*';
COMMENT ON COLUMN bp_objekt.wirdausgeglichendurchflaeche IS 'Assoziation zu: FeatureType BP_AusgleichsFlaeche (bp_ausgleichsflaeche) 0..*';
COMMENT ON COLUMN bp_objekt.wirdausgeglichendurchabe IS 'Assoziation zu: FeatureType BP_AnpflanzungBindungErhaltung (bp_anpflanzungbindungerhaltung) 0..*';
COMMENT ON COLUMN bp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType BP_TextAbschnitt (bp_textabschnitt) 0..*';
CREATE TABLE IF NOT EXISTS bp_flaechenobjekt (
  position geometry(MULTIPOLYGON) NOT NULL,
  flaechenschluss character varying NOT NULL
) INHERITS (bp_objekt) WITH OIDS;


COMMENT ON TABLE bp_flaechenobjekt IS 'FeatureType: "BP_Flaechenobjekt"';
COMMENT ON COLUMN bp_flaechenobjekt.position IS 'position Union XP_Flaechengeometrie 1';
COMMENT ON COLUMN bp_flaechenobjekt.flaechenschluss IS 'flaechenschluss  Boolean 1';
CREATE TABLE IF NOT EXISTS bp_flaechenschlussobjekt (

) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_flaechenschlussobjekt IS 'FeatureType: "BP_Flaechenschlussobjekt"';
CREATE TABLE IF NOT EXISTS bp_baugebietsteilflaeche (
  bauweise bp_bauweise,
  abweichendebauweise bp_abweichendebauweise,
  vertikaledifferenzierung character varying,
  sondernutzung xp_sondernutzungen,
  bebauungrueckwaertigegrenze bp_grenzbebauung,
  zugunstenvon character varying,
  refgebaeudequerschnitt xp_externereferenz[],
  detaillierteartderbaulnutzung bp_detailartderbaulnutzung,
  allgartderbaulnutzung xp_allgartderbaulnutzung,
  bebauungsart bp_bebauungsart,
  nutzungtext character varying,
  bebauungseitlichegrenze bp_grenzbebauung,
  bebauungvorderegrenze bp_grenzbebauung,
  besondereartderbaulnutzung xp_besondereartderbaulnutzung,
  abweichungbaunvo xp_abweichungbaunvotypen,
  abweichungtext character(16)[],
  inverszu_eigentuemer_bp_gemeinschaftsanlagenflaeche character(16)[]
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_baugebietsteilflaeche IS 'FeatureType: "BP_BaugebietsTeilFlaeche"';
COMMENT ON COLUMN bp_baugebietsteilflaeche.bauweise IS 'bauweise enumeration BP_Bauweise 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.abweichendebauweise IS 'abweichendeBauweise CodeList BP_AbweichendeBauweise 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.vertikaledifferenzierung IS 'vertikaleDifferenzierung  Boolean 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.sondernutzung IS 'sondernutzung enumeration XP_Sondernutzungen 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.bebauungrueckwaertigegrenze IS 'bebauungRueckwaertigeGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.zugunstenvon IS 'zugunstenVon  CharacterString 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.refgebaeudequerschnitt IS 'refGebaeudequerschnitt DataType XP_ExterneReferenz 0..*';
COMMENT ON COLUMN bp_baugebietsteilflaeche.detaillierteartderbaulnutzung IS 'detaillierteArtDerBaulNutzung CodeList BP_DetailArtDerBaulNutzung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.allgartderbaulnutzung IS 'allgArtDerBaulNutzung enumeration XP_AllgArtDerBaulNutzung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.bebauungsart IS 'bebauungsArt enumeration BP_BebauungsArt 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.nutzungtext IS 'nutzungText  CharacterString 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.bebauungseitlichegrenze IS 'bebauungSeitlicheGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.bebauungvorderegrenze IS 'bebauungVordereGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.besondereartderbaulnutzung IS 'besondereArtDerBaulNutzung enumeration XP_BesondereArtDerBaulNutzung 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.abweichungbaunvo IS 'abweichungBauNVO enumeration XP_AbweichungBauNVOTypen 0..1';
COMMENT ON COLUMN bp_baugebietsteilflaeche.abweichungtext IS 'Assoziation zu: FeatureType BP_TextAbschnitt (bp_textabschnitt) 0..*';
COMMENT ON COLUMN bp_baugebietsteilflaeche.inverszu_eigentuemer_bp_gemeinschaftsanlagenflaeche IS 'Assoziation zu: FeatureType BP_GemeinschaftsanlagenFlaeche (bp_gemeinschaftsanlagenflaeche) 0..*';
CREATE TABLE IF NOT EXISTS bp_spielsportanlagenflaeche (
  zugunstenvon character varying,
  zweckbestimmung xp_zweckbestimmungspielsportanlage[],
  detailliertezweckbestimmung bp_detailzweckbestspielsportanlage[]
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_spielsportanlagenflaeche IS 'FeatureType: "BP_SpielSportanlagenFlaeche"';
COMMENT ON COLUMN bp_spielsportanlagenflaeche.zugunstenvon IS 'zugunstenVon  CharacterString 0..1';
COMMENT ON COLUMN bp_spielsportanlagenflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungSpielSportanlage 0..*';
COMMENT ON COLUMN bp_spielsportanlagenflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestSpielSportanlage 0..*';
CREATE TABLE IF NOT EXISTS bp_gemeinbedarfsflaeche (
  detailliertezweckbestimmung bp_detailzweckbestgemeinbedarf[],
  zugunstenvon character varying,
  zweckbestimmung xp_zweckbestimmunggemeinbedarf[]
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_gemeinbedarfsflaeche IS 'FeatureType: "BP_GemeinbedarfsFlaeche"';
COMMENT ON COLUMN bp_gemeinbedarfsflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestGemeinbedarf 0..*';
COMMENT ON COLUMN bp_gemeinbedarfsflaeche.zugunstenvon IS 'zugunstenVon  CharacterString 0..1';
COMMENT ON COLUMN bp_gemeinbedarfsflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGemeinbedarf 0..*';
CREATE TABLE IF NOT EXISTS bp_strassenverkehrsflaeche (
  nutzungsform xp_nutzungsform,
  begrenzungslinie character(16)[]
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_strassenverkehrsflaeche IS 'FeatureType: "BP_StrassenVerkehrsFlaeche"';
COMMENT ON COLUMN bp_strassenverkehrsflaeche.nutzungsform IS 'nutzungsform enumeration XP_Nutzungsform 0..1';
COMMENT ON COLUMN bp_strassenverkehrsflaeche.begrenzungslinie IS 'Assoziation zu: FeatureType BP_StrassenbegrenzungsLinie (bp_strassenbegrenzungslinie) 0..*';
CREATE TABLE IF NOT EXISTS bp_verkehrsflaechebesondererzweckbestimmung (
  detailliertezweckbestimmung bp_detailzweckbeststrassenverkehr,
  zweckbestimmung bp_zweckbestimmungstrassenverkehr,
  nutzungsform xp_nutzungsform,
  begrenzungslinie character(16)[]
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_verkehrsflaechebesondererzweckbestimmung IS 'FeatureType: "BP_VerkehrsflaecheBesondererZweckbestimmung"';
COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestStrassenverkehr 0..1';
COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung.zweckbestimmung IS 'zweckbestimmung enumeration BP_ZweckbestimmungStrassenverkehr 0..1';
COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung.nutzungsform IS 'nutzungsform enumeration XP_Nutzungsform 0..1';
COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung.begrenzungslinie IS 'Assoziation zu: FeatureType BP_StrassenbegrenzungsLinie (bp_strassenbegrenzungslinie) 0..*';
CREATE TABLE IF NOT EXISTS bp_gewaesserflaeche (
  zweckbestimmung xp_zweckbestimmunggewaesser,
  detailliertezweckbestimmung bp_detailzweckbestgewaesser
) INHERITS (bp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE bp_gewaesserflaeche IS 'FeatureType: "BP_GewaesserFlaeche"';
COMMENT ON COLUMN bp_gewaesserflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGewaesser 0..1';
COMMENT ON COLUMN bp_gewaesserflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestGewaesser 0..1';
CREATE TABLE IF NOT EXISTS bp_ueberlagerungsobjekt (

) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_ueberlagerungsobjekt IS 'FeatureType: "BP_Ueberlagerungsobjekt"';
CREATE TABLE IF NOT EXISTS bp_regelungvergnuegungsstaetten (
  zulaessigkeit bp_zulaessigkeit
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_regelungvergnuegungsstaetten IS 'FeatureType: "BP_RegelungVergnuegungsstaetten"';
COMMENT ON COLUMN bp_regelungvergnuegungsstaetten.zulaessigkeit IS 'zulaessigkeit enumeration BP_Zulaessigkeit 0..1';
CREATE TABLE IF NOT EXISTS bp_abstandsflaeche (
  tiefe double precision
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_abstandsflaeche IS 'FeatureType: "BP_AbstandsFlaeche"';
COMMENT ON COLUMN bp_abstandsflaeche.tiefe IS 'tiefe  Length 0..1';
CREATE TABLE IF NOT EXISTS bp_speziellebauweise (
  sonsttyp bp_speziellebauweisesonsttypen,
  tmin double precision,
  tmax double precision,
  typ bp_speziellebauweisetypen,
  bmin double precision,
  bmax double precision
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_speziellebauweise IS 'FeatureType: "BP_SpezielleBauweise"';
COMMENT ON COLUMN bp_speziellebauweise.sonsttyp IS 'sonstTyp CodeList BP_SpezielleBauweiseSonstTypen 0..1';
COMMENT ON COLUMN bp_speziellebauweise.tmin IS 'Tmin  Length 0..1';
COMMENT ON COLUMN bp_speziellebauweise.tmax IS 'Tmax  Length 0..1';
COMMENT ON COLUMN bp_speziellebauweise.typ IS 'typ enumeration BP_SpezielleBauweiseTypen 0..1';
COMMENT ON COLUMN bp_speziellebauweise.bmin IS 'Bmin  Length 0..1';
COMMENT ON COLUMN bp_speziellebauweise.bmax IS 'Bmax  Length 0..1';
CREATE TABLE IF NOT EXISTS bp_persgruppenbestimmteflaeche (

) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_persgruppenbestimmteflaeche IS 'FeatureType: "BP_PersGruppenBestimmteFlaeche"';
CREATE TABLE IF NOT EXISTS bp_gemeinschaftsanlagenflaeche (
  detailliertezweckbestimmung bp_detailzweckbestgemeinschaftsanlagen[],
  zweckbestimmung bp_zweckbestimmunggemeinschaftsanlagen[],
  zmax integer,
  inverszu_zuordnung_bp_gemeinschaftsanlagenzuordnung character(16)[],
  eigentuemer character(16)[]
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_gemeinschaftsanlagenflaeche IS 'FeatureType: "BP_GemeinschaftsanlagenFlaeche"';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestGemeinschaftsanlagen 0..*';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche.zweckbestimmung IS 'zweckbestimmung enumeration BP_ZweckbestimmungGemeinschaftsanlagen 0..*';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche.zmax IS 'Zmax  Integer 0..1';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche.inverszu_zuordnung_bp_gemeinschaftsanlagenzuordnung IS 'Assoziation zu: FeatureType BP_GemeinschaftsanlagenZuordnung (bp_gemeinschaftsanlagenzuordnung) 0..*';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche.eigentuemer IS 'Assoziation zu: FeatureType BP_BaugebietsTeilFlaeche (bp_baugebietsteilflaeche) 0..*';
CREATE TABLE IF NOT EXISTS bp_nebenanlagenflaeche (
  zmax integer,
  zweckbestimmung bp_zweckbestimmungnebenanlagen[],
  detailliertezweckbestimmung bp_detailzweckbestnebenanlagen[]
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_nebenanlagenflaeche IS 'FeatureType: "BP_NebenanlagenFlaeche"';
COMMENT ON COLUMN bp_nebenanlagenflaeche.zmax IS 'Zmax  Integer 0..1';
COMMENT ON COLUMN bp_nebenanlagenflaeche.zweckbestimmung IS 'zweckbestimmung enumeration BP_ZweckbestimmungNebenanlagen 0..*';
COMMENT ON COLUMN bp_nebenanlagenflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestNebenanlagen 0..*';
CREATE TABLE IF NOT EXISTS bp_ueberbaubaregrundstuecksflaeche (
  bauweise bp_bauweise,
  geschossmax integer,
  abweichendebauweise bp_abweichendebauweise,
  bebauungsart bp_bebauungsart,
  bebauungseitlichegrenze bp_grenzbebauung,
  vertikaledifferenzierung character varying,
  bebauungvorderegrenze bp_grenzbebauung,
  bebauungrueckwaertigegrenze bp_grenzbebauung,
  refgebaeudequerschnitt xp_externereferenz[],
  geschossmin integer,
  baulinie character(16)[],
  baugrenze character(16)[]
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_ueberbaubaregrundstuecksflaeche IS 'FeatureType: "BP_UeberbaubareGrundstuecksFlaeche"';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.bauweise IS 'bauweise enumeration BP_Bauweise 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.geschossmax IS 'geschossMax  Integer 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.abweichendebauweise IS 'abweichendeBauweise CodeList BP_AbweichendeBauweise 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.bebauungsart IS 'bebauungsArt enumeration BP_BebauungsArt 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.bebauungseitlichegrenze IS 'bebauungSeitlicheGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.vertikaledifferenzierung IS 'vertikaleDifferenzierung  Boolean 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.bebauungvorderegrenze IS 'bebauungVordereGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.bebauungrueckwaertigegrenze IS 'bebauungRueckwaertigeGrenze enumeration BP_GrenzBebauung 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.refgebaeudequerschnitt IS 'refGebaeudequerschnitt DataType XP_ExterneReferenz 0..*';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.geschossmin IS 'geschossMin  Integer 0..1';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.baulinie IS 'Assoziation zu: FeatureType BP_BauLinie (bp_baulinie) 0..*';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche.baugrenze IS 'Assoziation zu: FeatureType BP_BauGrenze (bp_baugrenze) 0..*';
CREATE TABLE IF NOT EXISTS bp_foerderungsflaeche (

) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_foerderungsflaeche IS 'FeatureType: "BP_FoerderungsFlaeche"';
CREATE TABLE IF NOT EXISTS bp_gebaeudeflaeche (

) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_gebaeudeflaeche IS 'FeatureType: "BP_GebaeudeFlaeche"';
CREATE TABLE IF NOT EXISTS bp_nebenanlagenausschlussflaeche (
  typ bp_nebenanlagenausschlusstyp,
  abweichungtext character(16)[]
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_nebenanlagenausschlussflaeche IS 'FeatureType: "BP_NebenanlagenAusschlussFlaeche"';
COMMENT ON COLUMN bp_nebenanlagenausschlussflaeche.typ IS 'typ enumeration BP_NebenanlagenAusschlussTyp 0..1';
COMMENT ON COLUMN bp_nebenanlagenausschlussflaeche.abweichungtext IS 'Assoziation zu: FeatureType BP_TextAbschnitt (bp_textabschnitt) 0..*';
CREATE TABLE IF NOT EXISTS bp_erhaltungsbereichflaeche (
  grund bp_erhaltungsgrund NOT NULL
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_erhaltungsbereichflaeche IS 'FeatureType: "BP_ErhaltungsBereichFlaeche"';
COMMENT ON COLUMN bp_erhaltungsbereichflaeche.grund IS 'grund enumeration BP_ErhaltungsGrund 1';
CREATE TABLE IF NOT EXISTS bp_eingriffsbereich (

) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_eingriffsbereich IS 'FeatureType: "BP_EingriffsBereich"';
CREATE TABLE IF NOT EXISTS bp_veraenderungssperre (
  refbeschluss xp_externereferenz,
  verlaengerung xp_verlaengerungveraenderungssperre NOT NULL,
  gueltigkeitsdatum date NOT NULL
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_veraenderungssperre IS 'FeatureType: "BP_Veraenderungssperre"';
COMMENT ON COLUMN bp_veraenderungssperre.refbeschluss IS 'refBeschluss DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_veraenderungssperre.verlaengerung IS 'verlaengerung enumeration XP_VerlaengerungVeraenderungssperre 1';
COMMENT ON COLUMN bp_veraenderungssperre.gueltigkeitsdatum IS 'gueltigkeitsDatum  Date 1';
CREATE TABLE IF NOT EXISTS bp_textlichefestsetzungsflaeche (

) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_textlichefestsetzungsflaeche IS 'FeatureType: "BP_TextlicheFestsetzungsFlaeche"';
CREATE TABLE IF NOT EXISTS bp_freiflaeche (
  nutzung character varying
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_freiflaeche IS 'FeatureType: "BP_FreiFlaeche"';
COMMENT ON COLUMN bp_freiflaeche.nutzung IS 'nutzung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_technischemassnahmenflaeche (
  zweckbestimmung character varying NOT NULL,
  technischemassnahme character varying
) INHERITS (bp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE bp_technischemassnahmenflaeche IS 'FeatureType: "BP_TechnischeMassnahmenFlaeche"';
COMMENT ON COLUMN bp_technischemassnahmenflaeche.zweckbestimmung IS 'zweckbestimmung 1';
COMMENT ON COLUMN bp_technischemassnahmenflaeche.technischemassnahme IS 'technischeMassnahme  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_abgrabungsflaeche (

) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_abgrabungsflaeche IS 'FeatureType: "BP_AbgrabungsFlaeche"';
CREATE TABLE IF NOT EXISTS bp_bodenschaetzeflaeche (
  abbaugut character varying
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_bodenschaetzeflaeche IS 'FeatureType: "BP_BodenschaetzeFlaeche"';
COMMENT ON COLUMN bp_bodenschaetzeflaeche.abbaugut IS 'abbaugut  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_rekultivierungsflaeche (

) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_rekultivierungsflaeche IS 'FeatureType: "BP_RekultivierungsFlaeche"';
CREATE TABLE IF NOT EXISTS bp_aufschuettungsflaeche (

) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_aufschuettungsflaeche IS 'FeatureType: "BP_AufschuettungsFlaeche"';
CREATE TABLE IF NOT EXISTS bp_besonderernutzungszweckflaeche (
  zweckbestimmung character varying
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_besonderernutzungszweckflaeche IS 'FeatureType: "BP_BesondererNutzungszweckFlaeche"';
COMMENT ON COLUMN bp_besonderernutzungszweckflaeche.zweckbestimmung IS 'zweckbestimmung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_ausgleichsflaeche (
  reflandschaftsplan xp_externereferenz,
  ziel xp_speziele,
  massnahme xp_spemassnahmendaten[],
  refmassnahmentext xp_externereferenz,
  sonstziel character varying,
  inverszu_wirdausgeglichendurchflaeche_bp_objekt character(16)[]
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_ausgleichsflaeche IS 'FeatureType: "BP_AusgleichsFlaeche"';
COMMENT ON COLUMN bp_ausgleichsflaeche.reflandschaftsplan IS 'refLandschaftsplan DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_ausgleichsflaeche.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN bp_ausgleichsflaeche.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN bp_ausgleichsflaeche.refmassnahmentext IS 'refMassnahmenText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_ausgleichsflaeche.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN bp_ausgleichsflaeche.inverszu_wirdausgeglichendurchflaeche_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_schutzpflegeentwicklungsflaeche (
  reflandschaftsplan xp_externereferenz,
  ziel xp_speziele,
  istausgleich character varying,
  massnahme xp_spemassnahmendaten[],
  refmassnahmentext xp_externereferenz,
  sonstziel character varying,
  inverszu_wirdausgeglichendurchspeflaeche_bp_objekt character(16)[]
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_schutzpflegeentwicklungsflaeche IS 'FeatureType: "BP_SchutzPflegeEntwicklungsFlaeche"';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.reflandschaftsplan IS 'refLandschaftsplan DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.istausgleich IS 'istAusgleich  Boolean 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.refmassnahmentext IS 'refMassnahmenText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsflaeche.inverszu_wirdausgeglichendurchspeflaeche_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_kennzeichnungsflaeche (
  zweckbestimmung xp_zweckbestimmungkennzeichnung[]
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_kennzeichnungsflaeche IS 'FeatureType: "BP_KennzeichnungsFlaeche"';
COMMENT ON COLUMN bp_kennzeichnungsflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungKennzeichnung 0..*';
CREATE TABLE IF NOT EXISTS bp_wasserwirtschaftsflaeche (
  zweckbestimmung xp_zweckbestimmungwasserwirtschaft,
  detailliertezweckbestimmung bp_detailzweckbestwasserwirtschaft
) INHERITS (bp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE bp_wasserwirtschaftsflaeche IS 'FeatureType: "BP_WasserwirtschaftsFlaeche"';
COMMENT ON COLUMN bp_wasserwirtschaftsflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungWasserwirtschaft 0..1';
COMMENT ON COLUMN bp_wasserwirtschaftsflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestWasserwirtschaft 0..1';
CREATE TABLE IF NOT EXISTS bp_punktobjekt (
  nordwinkel double precision,
  position geometry(MULTIPOINT) NOT NULL
) INHERITS (bp_objekt) WITH OIDS;


COMMENT ON TABLE bp_punktobjekt IS 'FeatureType: "BP_Punktobjekt"';
COMMENT ON COLUMN bp_punktobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN bp_punktobjekt.position IS 'position Union XP_Punktgeometrie 1';
CREATE TABLE IF NOT EXISTS bp_einfahrtpunkt (
  typ character varying
) INHERITS (bp_punktobjekt) WITH OIDS;


COMMENT ON TABLE bp_einfahrtpunkt IS 'FeatureType: "BP_EinfahrtPunkt"';
COMMENT ON COLUMN bp_einfahrtpunkt.typ IS 'typ 0..1';
CREATE TABLE IF NOT EXISTS bp_linienobjekt (
  position geometry NOT NULL
) INHERITS (bp_objekt) WITH OIDS;


COMMENT ON TABLE bp_linienobjekt IS 'FeatureType: "BP_Linienobjekt"';
COMMENT ON COLUMN bp_linienobjekt.position IS 'position Union XP_Liniengeometrie 1';
CREATE TABLE IF NOT EXISTS bp_baulinie (
  geschossmax integer,
  bautiefe double precision,
  geschossmin integer,
  inverszu_baulinie_bp_ueberbaubaregrundstuecksflaeche character(16)[]
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_baulinie IS 'FeatureType: "BP_BauLinie"';
COMMENT ON COLUMN bp_baulinie.geschossmax IS 'geschossMax  Integer 0..1';
COMMENT ON COLUMN bp_baulinie.bautiefe IS 'bautiefe  Length 0..1';
COMMENT ON COLUMN bp_baulinie.geschossmin IS 'geschossMin  Integer 0..1';
COMMENT ON COLUMN bp_baulinie.inverszu_baulinie_bp_ueberbaubaregrundstuecksflaeche IS 'Assoziation zu: FeatureType BP_UeberbaubareGrundstuecksFlaeche (bp_ueberbaubaregrundstuecksflaeche) 0..*';
CREATE TABLE IF NOT EXISTS bp_baugrenze (
  geschossmax integer,
  bautiefe double precision,
  geschossmin integer,
  inverszu_baugrenze_bp_ueberbaubaregrundstuecksflaeche character(16)[]
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_baugrenze IS 'FeatureType: "BP_BauGrenze"';
COMMENT ON COLUMN bp_baugrenze.geschossmax IS 'geschossMax  Integer 0..1';
COMMENT ON COLUMN bp_baugrenze.bautiefe IS 'bautiefe  Length 0..1';
COMMENT ON COLUMN bp_baugrenze.geschossmin IS 'geschossMin  Integer 0..1';
COMMENT ON COLUMN bp_baugrenze.inverszu_baugrenze_bp_ueberbaubaregrundstuecksflaeche IS 'Assoziation zu: FeatureType BP_UeberbaubareGrundstuecksFlaeche (bp_ueberbaubaregrundstuecksflaeche) 0..*';
CREATE TABLE IF NOT EXISTS bp_firstrichtungslinie (

) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_firstrichtungslinie IS 'FeatureType: "BP_FirstRichtungsLinie"';
CREATE TABLE IF NOT EXISTS bp_nutzungsartengrenze (
  typ bp_abgrenzungentypen,
  detailtyp bp_detailabgrenzungentypen
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_nutzungsartengrenze IS 'FeatureType: "BP_NutzungsartenGrenze"';
COMMENT ON COLUMN bp_nutzungsartengrenze.typ IS 'typ enumeration BP_AbgrenzungenTypen 0..1';
COMMENT ON COLUMN bp_nutzungsartengrenze.detailtyp IS 'detailTyp CodeList BP_DetailAbgrenzungenTypen 0..1';
CREATE TABLE IF NOT EXISTS bp_strassenbegrenzungslinie (
  bautiefe double precision,
  inverszu_begrenzungslinie_bp_strassenverkehrsflaeche character(16)[],
  inverszu_begrenzungslinie_bp_verkehrsflaechebesondererzwec character(16)[]
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_strassenbegrenzungslinie IS 'FeatureType: "BP_StrassenbegrenzungsLinie"';
COMMENT ON COLUMN bp_strassenbegrenzungslinie.bautiefe IS 'bautiefe  Length 0..1';
COMMENT ON COLUMN bp_strassenbegrenzungslinie.inverszu_begrenzungslinie_bp_strassenverkehrsflaeche IS 'Assoziation zu: FeatureType BP_StrassenVerkehrsFlaeche (bp_strassenverkehrsflaeche) 0..*';
COMMENT ON COLUMN bp_strassenbegrenzungslinie.inverszu_begrenzungslinie_bp_verkehrsflaechebesondererzwec IS 'Assoziation zu: FeatureType BP_VerkehrsflaecheBesondererZweckbestimmung (bp_verkehrsflaechebesondererzweckbestimmung) 0..*';
CREATE TABLE IF NOT EXISTS bp_einfahrtsbereichlinie (
  typ character varying
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_einfahrtsbereichlinie IS 'FeatureType: "BP_EinfahrtsbereichLinie"';
COMMENT ON COLUMN bp_einfahrtsbereichlinie.typ IS 'typ 0..1';
CREATE TABLE IF NOT EXISTS bp_bereichohneeinausfahrtlinie (
  typ bp_bereichohneeinausfahrttypen
) INHERITS (bp_linienobjekt) WITH OIDS;


COMMENT ON TABLE bp_bereichohneeinausfahrtlinie IS 'FeatureType: "BP_BereichOhneEinAusfahrtLinie"';
COMMENT ON COLUMN bp_bereichohneeinausfahrtlinie.typ IS 'typ enumeration BP_BereichOhneEinAusfahrtTypen 0..1';
CREATE TABLE IF NOT EXISTS bp_geometrieobjekt (
  nordwinkel double precision,
  flussrichtung character varying,
  position geometry NOT NULL,
  flaechenschluss character varying
) INHERITS (bp_objekt) WITH OIDS;


COMMENT ON TABLE bp_geometrieobjekt IS 'FeatureType: "BP_Geometrieobjekt"';
COMMENT ON COLUMN bp_geometrieobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN bp_geometrieobjekt.flussrichtung IS 'flussrichtung  Boolean 0..1';
COMMENT ON COLUMN bp_geometrieobjekt.position IS 'position Union XP_VariableGeometrie 1';
COMMENT ON COLUMN bp_geometrieobjekt.flaechenschluss IS 'flaechenschluss  Boolean 0..1';
CREATE TABLE IF NOT EXISTS bp_gemeinschaftsanlagenzuordnung (
  zuordnung character(16)[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_gemeinschaftsanlagenzuordnung IS 'FeatureType: "BP_GemeinschaftsanlagenZuordnung"';
COMMENT ON COLUMN bp_gemeinschaftsanlagenzuordnung.zuordnung IS 'Assoziation zu: FeatureType BP_GemeinschaftsanlagenFlaeche (bp_gemeinschaftsanlagenflaeche) 0..*';
CREATE TABLE IF NOT EXISTS bp_ausgleichsmassnahme (
  reflandschaftsplan xp_externereferenz,
  ziel xp_speziele,
  massnahme xp_spemassnahmendaten[],
  refmassnahmentext xp_externereferenz,
  sonstziel character varying,
  inverszu_wirdausgeglichendurchmassnahme_bp_objekt character(16)[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_ausgleichsmassnahme IS 'FeatureType: "BP_AusgleichsMassnahme"';
COMMENT ON COLUMN bp_ausgleichsmassnahme.reflandschaftsplan IS 'refLandschaftsplan DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_ausgleichsmassnahme.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN bp_ausgleichsmassnahme.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN bp_ausgleichsmassnahme.refmassnahmentext IS 'refMassnahmenText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_ausgleichsmassnahme.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN bp_ausgleichsmassnahme.inverszu_wirdausgeglichendurchmassnahme_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_anpflanzungbindungerhaltung (
  gegenstand xp_anpflanzungbindungerhaltungsgegenstand[],
  baumart vegetationsobjekttypen,
  pflanztiefe double precision,
  istausgleich character varying,
  kronendurchmesser double precision,
  massnahme xp_abemassnahmentypen,
  inverszu_wirdausgeglichendurchabe_bp_objekt character(16)[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_anpflanzungbindungerhaltung IS 'FeatureType: "BP_AnpflanzungBindungErhaltung"';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.gegenstand IS 'gegenstand enumeration XP_AnpflanzungBindungErhaltungsGegenstand 0..*';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.baumart IS 'baumArt CodeList VegetationsobjektTypen 0..1';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.pflanztiefe IS 'pflanztiefe  Length 0..1';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.istausgleich IS 'istAusgleich  Boolean 0..1';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.kronendurchmesser IS 'kronendurchmesser  Length 0..1';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.massnahme IS 'massnahme enumeration XP_ABEMassnahmenTypen 0..1';
COMMENT ON COLUMN bp_anpflanzungbindungerhaltung.inverszu_wirdausgeglichendurchabe_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_schutzpflegeentwicklungsmassnahme (
  reflandschaftsplan xp_externereferenz,
  ziel xp_speziele,
  istausgleich character varying,
  massnahme xp_spemassnahmendaten[],
  refmassnahmentext xp_externereferenz,
  sonstziel character varying,
  inverszu_wirdausgeglichendurchspemassnahme_bp_objekt character(16)[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_schutzpflegeentwicklungsmassnahme IS 'FeatureType: "BP_SchutzPflegeEntwicklungsMassnahme"';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.reflandschaftsplan IS 'refLandschaftsplan DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.istausgleich IS 'istAusgleich  Boolean 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.refmassnahmentext IS 'refMassnahmenText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN bp_schutzpflegeentwicklungsmassnahme.inverszu_wirdausgeglichendurchspemassnahme_bp_objekt IS 'Assoziation zu: FeatureType BP_Objekt (bp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_unverbindlichevormerkung (
  vormerkung character varying
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_unverbindlichevormerkung IS 'FeatureType: "BP_UnverbindlicheVormerkung"';
COMMENT ON COLUMN bp_unverbindlichevormerkung.vormerkung IS 'vormerkung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_abstandsmass (
  startwinkel double precision,
  endwinkel double precision,
  wert double precision NOT NULL
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_abstandsmass IS 'FeatureType: "BP_AbstandsMass"';
COMMENT ON COLUMN bp_abstandsmass.startwinkel IS 'startWinkel  Angle 0..1';
COMMENT ON COLUMN bp_abstandsmass.endwinkel IS 'endWinkel  Angle 0..1';
COMMENT ON COLUMN bp_abstandsmass.wert IS 'wert  Length 1';
CREATE TABLE IF NOT EXISTS bp_generischesobjekt (
  zweckbestimmung bp_zweckbestimmunggenerischeobjekte[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_generischesobjekt IS 'FeatureType: "BP_GenerischesObjekt"';
COMMENT ON COLUMN bp_generischesobjekt.zweckbestimmung IS 'zweckbestimmung CodeList BP_ZweckbestimmungGenerischeObjekte 0..*';
CREATE TABLE IF NOT EXISTS bp_wegerecht (
  thema character varying,
  zugunstenvon character varying,
  typ bp_wegerechttypen,
  breite double precision
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_wegerecht IS 'FeatureType: "BP_Wegerecht"';
COMMENT ON COLUMN bp_wegerecht.thema IS 'thema  CharacterString 0..1';
COMMENT ON COLUMN bp_wegerecht.zugunstenvon IS 'zugunstenVon  CharacterString 0..1';
COMMENT ON COLUMN bp_wegerecht.typ IS 'typ enumeration BP_WegerechtTypen 0..1';
COMMENT ON COLUMN bp_wegerecht.breite IS 'breite  Length 0..1';
CREATE TABLE IF NOT EXISTS bp_hoehenmass (

) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_hoehenmass IS 'FeatureType: "BP_HoehenMass"';
CREATE TABLE IF NOT EXISTS bp_festsetzungnachlandesrecht (
  kurzbeschreibung character varying
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_festsetzungnachlandesrecht IS 'FeatureType: "BP_FestsetzungNachLandesrecht"';
COMMENT ON COLUMN bp_festsetzungnachlandesrecht.kurzbeschreibung IS 'kurzbeschreibung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_immissionsschutz (
  laermpegelbereich bp_laerrmpegelbereich,
  nutzung character varying
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_immissionsschutz IS 'FeatureType: "BP_Immissionsschutz"';
COMMENT ON COLUMN bp_immissionsschutz.laermpegelbereich IS 'laermpegelbereich enumeration BP_Laerrmpegelbereich 0..1';
COMMENT ON COLUMN bp_immissionsschutz.nutzung IS 'nutzung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS bp_verentsorgung (
  detailliertezweckbestimmung bp_detailzweckbestverentsorgung[],
  zugunstenvon character varying,
  textlicheergaenzung character varying,
  zweckbestimmung xp_zweckbestimmungverentsorgung[]
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_verentsorgung IS 'FeatureType: "BP_VerEntsorgung"';
COMMENT ON COLUMN bp_verentsorgung.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList BP_DetailZweckbestVerEntsorgung 0..*';
COMMENT ON COLUMN bp_verentsorgung.zugunstenvon IS 'zugunstenVon  CharacterString 0..1';
COMMENT ON COLUMN bp_verentsorgung.textlicheergaenzung IS 'textlicheErgaenzung  CharacterString 0..1';
COMMENT ON COLUMN bp_verentsorgung.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungVerEntsorgung 0..*';
CREATE TABLE IF NOT EXISTS bp_strassenkoerper (
  typ bp_strassenkoerperherstellung NOT NULL
) INHERITS (bp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE bp_strassenkoerper IS 'FeatureType: "BP_Strassenkoerper"';
COMMENT ON COLUMN bp_strassenkoerper.typ IS 'typ enumeration BP_StrassenkoerperHerstellung 1';
CREATE TABLE IF NOT EXISTS fp_objekt (
  rechtscharakter fp_rechtscharakter NOT NULL,
  spezifischepraegung fp_spezifischepraegungtypen,
  wirdausgeglichendurchspe character(16)[],
  wirdausgeglichendurchflaeche character(16)[],
  reftextinhalt character(16)[]
) INHERITS (xp_objekt) WITH OIDS;


COMMENT ON TABLE fp_objekt IS 'FeatureType: "FP_Objekt"';
COMMENT ON COLUMN fp_objekt.rechtscharakter IS 'rechtscharakter enumeration FP_Rechtscharakter 1';
COMMENT ON COLUMN fp_objekt.spezifischepraegung IS 'spezifischePraegung CodeList FP_SpezifischePraegungTypen 0..1';
COMMENT ON COLUMN fp_objekt.wirdausgeglichendurchspe IS 'Assoziation zu: FeatureType FP_SchutzPflegeEntwicklung (fp_schutzpflegeentwicklung) 0..*';
COMMENT ON COLUMN fp_objekt.wirdausgeglichendurchflaeche IS 'Assoziation zu: FeatureType FP_AusgleichsFlaeche (fp_ausgleichsflaeche) 0..*';
COMMENT ON COLUMN fp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType FP_TextAbschnitt (fp_textabschnitt) 0..*';
CREATE TABLE IF NOT EXISTS fp_flaechenobjekt (
  position geometry(MULTIPOLYGON) NOT NULL,
  flaechenschluss character varying NOT NULL
) INHERITS (fp_objekt) WITH OIDS;


COMMENT ON TABLE fp_flaechenobjekt IS 'FeatureType: "FP_Flaechenobjekt"';
COMMENT ON COLUMN fp_flaechenobjekt.position IS 'position Union XP_Flaechengeometrie 1';
COMMENT ON COLUMN fp_flaechenobjekt.flaechenschluss IS 'flaechenschluss  Boolean 1';
CREATE TABLE IF NOT EXISTS fp_flaechenschlussobjekt (

) INHERITS (fp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE fp_flaechenschlussobjekt IS 'FeatureType: "FP_Flaechenschlussobjekt"';
CREATE TABLE IF NOT EXISTS fp_bebauungsflaeche (
  detaillierteartderbaulnutzung fp_detailartderbaulnutzung,
  grz double precision,
  bmz double precision,
  nutzungtext character varying,
  gfzmax double precision,
  besondereartderbaulnutzung xp_besondereartderbaulnutzung,
  sondernutzung xp_sondernutzungen,
  gfz double precision,
  allgartderbaulnutzung xp_allgartderbaulnutzung,
  gfzmin double precision
) INHERITS (fp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE fp_bebauungsflaeche IS 'FeatureType: "FP_BebauungsFlaeche"';
COMMENT ON COLUMN fp_bebauungsflaeche.detaillierteartderbaulnutzung IS 'detaillierteArtDerBaulNutzung CodeList FP_DetailArtDerBaulNutzung 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.grz IS 'GRZ  Decimal 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.bmz IS 'BMZ  Decimal 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.nutzungtext IS 'nutzungText  CharacterString 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.gfzmax IS 'GFZmax  Decimal 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.besondereartderbaulnutzung IS 'besondereArtDerBaulNutzung enumeration XP_BesondereArtDerBaulNutzung 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.sondernutzung IS 'sonderNutzung enumeration XP_Sondernutzungen 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.gfz IS 'GFZ  Decimal 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.allgartderbaulnutzung IS 'allgArtDerBaulNutzung enumeration XP_AllgArtDerBaulNutzung 0..1';
COMMENT ON COLUMN fp_bebauungsflaeche.gfzmin IS 'GFZmin  Decimal 0..1';
CREATE TABLE IF NOT EXISTS fp_landwirtschaftsflaeche (
  detailliertezweckbestimmung fp_detailzweckbestlandwirtschaftsflaeche[],
  zweckbestimmung xp_zweckbestimmunglandwirtschaft[]
) INHERITS (fp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE fp_landwirtschaftsflaeche IS 'FeatureType: "FP_LandwirtschaftsFlaeche"';
COMMENT ON COLUMN fp_landwirtschaftsflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestLandwirtschaftsFlaeche 0..*';
COMMENT ON COLUMN fp_landwirtschaftsflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungLandwirtschaft 0..*';
CREATE TABLE IF NOT EXISTS fp_waldflaeche (
  zweckbestimmung xp_zweckbestimmungwald[],
  detailliertezweckbestimmung fp_detailzweckbestwaldflaeche[]
) INHERITS (fp_flaechenschlussobjekt) WITH OIDS;


COMMENT ON TABLE fp_waldflaeche IS 'FeatureType: "FP_WaldFlaeche"';
COMMENT ON COLUMN fp_waldflaeche.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungWald 0..*';
COMMENT ON COLUMN fp_waldflaeche.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestWaldFlaeche 0..*';
CREATE TABLE IF NOT EXISTS fp_ueberlagerungsobjekt (

) INHERITS (fp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE fp_ueberlagerungsobjekt IS 'FeatureType: "FP_Ueberlagerungsobjekt"';
CREATE TABLE IF NOT EXISTS fp_nutzungsbeschraenkungsflaeche (

) INHERITS (fp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE fp_nutzungsbeschraenkungsflaeche IS 'FeatureType: "FP_NutzungsbeschraenkungsFlaeche"';
CREATE TABLE IF NOT EXISTS fp_textlichedarstellungsflaeche (

) INHERITS (fp_ueberlagerungsobjekt) WITH OIDS;


COMMENT ON TABLE fp_textlichedarstellungsflaeche IS 'FeatureType: "FP_TextlicheDarstellungsFlaeche"';
CREATE TABLE IF NOT EXISTS fp_keinezentrabwasserbeseitigungflaeche (

) INHERITS (fp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE fp_keinezentrabwasserbeseitigungflaeche IS 'FeatureType: "FP_KeineZentrAbwasserBeseitigungFlaeche"';
CREATE TABLE IF NOT EXISTS fp_ausgleichsflaeche (
  reflandschaftsplan xp_externereferenz,
  ziel xp_speziele,
  massnahme xp_spemassnahmendaten[],
  refmassnahmentext xp_externereferenz,
  sonstziel character varying,
  inverszu_wirdausgeglichendurchflaeche_fp_objekt character(16)[]
) INHERITS (fp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE fp_ausgleichsflaeche IS 'FeatureType: "FP_AusgleichsFlaeche"';
COMMENT ON COLUMN fp_ausgleichsflaeche.reflandschaftsplan IS 'refLandschaftsplan DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN fp_ausgleichsflaeche.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN fp_ausgleichsflaeche.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN fp_ausgleichsflaeche.refmassnahmentext IS 'refMassnahmenText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN fp_ausgleichsflaeche.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN fp_ausgleichsflaeche.inverszu_wirdausgeglichendurchflaeche_fp_objekt IS 'Assoziation zu: FeatureType FP_Objekt (fp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS fp_vorbehalteflaeche (
  vorbehalt character varying
) INHERITS (fp_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE fp_vorbehalteflaeche IS 'FeatureType: "FP_VorbehalteFlaeche"';
COMMENT ON COLUMN fp_vorbehalteflaeche.vorbehalt IS 'vorbehalt  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS fp_punktobjekt (
  nordwinkel double precision,
  position geometry(MULTIPOINT) NOT NULL
) INHERITS (fp_objekt) WITH OIDS;


COMMENT ON TABLE fp_punktobjekt IS 'FeatureType: "FP_Punktobjekt"';
COMMENT ON COLUMN fp_punktobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN fp_punktobjekt.position IS 'position Union XP_Punktgeometrie 1';
CREATE TABLE IF NOT EXISTS fp_linienobjekt (
  position geometry NOT NULL
) INHERITS (fp_objekt) WITH OIDS;


COMMENT ON TABLE fp_linienobjekt IS 'FeatureType: "FP_Linienobjekt"';
COMMENT ON COLUMN fp_linienobjekt.position IS 'position Union XP_Liniengeometrie 1';
CREATE TABLE IF NOT EXISTS fp_geometrieobjekt (
  nordwinkel double precision,
  flussrichtung character varying,
  position geometry NOT NULL,
  flaechenschluss character varying
) INHERITS (fp_objekt) WITH OIDS;


COMMENT ON TABLE fp_geometrieobjekt IS 'FeatureType: "FP_Geometrieobjekt"';
COMMENT ON COLUMN fp_geometrieobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN fp_geometrieobjekt.flussrichtung IS 'flussrichtung  Boolean 0..1';
COMMENT ON COLUMN fp_geometrieobjekt.position IS 'position Union XP_VariableGeometrie 1';
COMMENT ON COLUMN fp_geometrieobjekt.flaechenschluss IS 'flaechenschluss  Boolean 0..1';
CREATE TABLE IF NOT EXISTS fp_aufschuettung (

) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_aufschuettung IS 'FeatureType: "FP_Aufschuettung"';
CREATE TABLE IF NOT EXISTS fp_bodenschaetze (
  abbaugut character varying
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_bodenschaetze IS 'FeatureType: "FP_Bodenschaetze"';
COMMENT ON COLUMN fp_bodenschaetze.abbaugut IS 'abbaugut  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS fp_abgrabung (

) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_abgrabung IS 'FeatureType: "FP_Abgrabung"';
CREATE TABLE IF NOT EXISTS fp_anpassungklimawandel (

) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_anpassungklimawandel IS 'FeatureType: "FP_AnpassungKlimawandel"';
CREATE TABLE IF NOT EXISTS fp_spielsportanlage (
  zweckbestimmung xp_zweckbestimmungspielsportanlage[],
  detailliertezweckbestimmung fp_detailzweckbestspielsportanlage[]
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_spielsportanlage IS 'FeatureType: "FP_SpielSportanlage"';
COMMENT ON COLUMN fp_spielsportanlage.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungSpielSportanlage 0..*';
COMMENT ON COLUMN fp_spielsportanlage.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestSpielSportanlage 0..*';
CREATE TABLE IF NOT EXISTS fp_gemeinbedarf (
  zweckbestimmung xp_zweckbestimmunggemeinbedarf[],
  detailliertezweckbestimmung fp_detailzweckbestgemeinbedarf[]
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_gemeinbedarf IS 'FeatureType: "FP_Gemeinbedarf"';
COMMENT ON COLUMN fp_gemeinbedarf.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGemeinbedarf 0..*';
COMMENT ON COLUMN fp_gemeinbedarf.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestGemeinbedarf 0..*';
CREATE TABLE IF NOT EXISTS fp_gruen (
  zweckbestimmung xp_zweckbestimmunggruen[],
  detailliertezweckbestimmung fp_detailzweckbestgruen[],
  nutzungsform xp_nutzungsform
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_gruen IS 'FeatureType: "FP_Gruen"';
COMMENT ON COLUMN fp_gruen.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGruen 0..*';
COMMENT ON COLUMN fp_gruen.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestGruen 0..*';
COMMENT ON COLUMN fp_gruen.nutzungsform IS 'nutzungsform enumeration XP_Nutzungsform 0..1';
CREATE TABLE IF NOT EXISTS fp_schutzpflegeentwicklung (
  ziel xp_speziele,
  istausgleich character varying,
  massnahme xp_spemassnahmendaten[],
  sonstziel character varying,
  inverszu_wirdausgeglichendurchspe_fp_objekt character(16)[]
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_schutzpflegeentwicklung IS 'FeatureType: "FP_SchutzPflegeEntwicklung"';
COMMENT ON COLUMN fp_schutzpflegeentwicklung.ziel IS 'ziel enumeration XP_SPEZiele 0..1';
COMMENT ON COLUMN fp_schutzpflegeentwicklung.istausgleich IS 'istAusgleich  Boolean 0..1';
COMMENT ON COLUMN fp_schutzpflegeentwicklung.massnahme IS 'massnahme DataType XP_SPEMassnahmenDaten 0..*';
COMMENT ON COLUMN fp_schutzpflegeentwicklung.sonstziel IS 'sonstZiel  CharacterString 0..1';
COMMENT ON COLUMN fp_schutzpflegeentwicklung.inverszu_wirdausgeglichendurchspe_fp_objekt IS 'Assoziation zu: FeatureType FP_Objekt (fp_objekt) 0..*';
CREATE TABLE IF NOT EXISTS fp_unverbindlichevormerkung (
  vormerkung character varying
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_unverbindlichevormerkung IS 'FeatureType: "FP_UnverbindlicheVormerkung"';
COMMENT ON COLUMN fp_unverbindlichevormerkung.vormerkung IS 'vormerkung  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS fp_privilegiertesvorhaben (
  zweckbestimmung fp_zweckbestimmungprivilegiertesvorhaben[],
  vorhaben character varying
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_privilegiertesvorhaben IS 'FeatureType: "FP_PrivilegiertesVorhaben"';
COMMENT ON COLUMN fp_privilegiertesvorhaben.zweckbestimmung IS 'zweckbestimmung enumeration FP_ZweckbestimmungPrivilegiertesVorhaben 0..*';
COMMENT ON COLUMN fp_privilegiertesvorhaben.vorhaben IS 'vorhaben  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS fp_kennzeichnung (
  zweckbestimmung xp_zweckbestimmungkennzeichnung[]
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_kennzeichnung IS 'FeatureType: "FP_Kennzeichnung"';
COMMENT ON COLUMN fp_kennzeichnung.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungKennzeichnung 0..*';
CREATE TABLE IF NOT EXISTS fp_generischesobjekt (
  zweckbestimmung fp_zweckbestimmunggenerischeobjekte[]
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_generischesobjekt IS 'FeatureType: "FP_GenerischesObjekt"';
COMMENT ON COLUMN fp_generischesobjekt.zweckbestimmung IS 'zweckbestimmung CodeList FP_ZweckbestimmungGenerischeObjekte 0..*';
CREATE TABLE IF NOT EXISTS fp_strassenverkehr (
  nutzungsform xp_nutzungsform,
  detailliertezweckbestimmung fp_detailzweckbeststrassenverkehr,
  zweckbestimmung fp_zweckbestimmungstrassenverkehr
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_strassenverkehr IS 'FeatureType: "FP_Strassenverkehr"';
COMMENT ON COLUMN fp_strassenverkehr.nutzungsform IS 'nutzungsform enumeration XP_Nutzungsform 0..1';
COMMENT ON COLUMN fp_strassenverkehr.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestStrassenverkehr 0..1';
COMMENT ON COLUMN fp_strassenverkehr.zweckbestimmung IS 'zweckbestimmung enumeration FP_ZweckbestimmungStrassenverkehr 0..1';
CREATE TABLE IF NOT EXISTS fp_gewaesser (
  zweckbestimmung xp_zweckbestimmunggewaesser,
  detailliertezweckbestimmung fp_detailzweckbestgewaesser
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_gewaesser IS 'FeatureType: "FP_Gewaesser"';
COMMENT ON COLUMN fp_gewaesser.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungGewaesser 0..1';
COMMENT ON COLUMN fp_gewaesser.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestGewaesser 0..1';
CREATE TABLE IF NOT EXISTS fp_wasserwirtschaft (
  zweckbestimmung xp_zweckbestimmungwasserwirtschaft,
  detailliertezweckbestimmung fp_detailzweckbestwasserwirtschaft
) INHERITS (fp_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE fp_wasserwirtschaft IS 'FeatureType: "FP_Wasserwirtschaft"';
COMMENT ON COLUMN fp_wasserwirtschaft.zweckbestimmung IS 'zweckbestimmung enumeration XP_ZweckbestimmungWasserwirtschaft 0..1';
COMMENT ON COLUMN fp_wasserwirtschaft.detailliertezweckbestimmung IS 'detaillierteZweckbestimmung CodeList FP_DetailZweckbestWasserwirtschaft 0..1';
CREATE TABLE IF NOT EXISTS so_objekt (
  rechtscharakter so_rechtscharakter NOT NULL,
  sonstrechtscharakter so_sonstrechtscharakter,
  reftextinhalt character(16)[]
) INHERITS (xp_objekt) WITH OIDS;


COMMENT ON TABLE so_objekt IS 'FeatureType: "SO_Objekt"';
COMMENT ON COLUMN so_objekt.rechtscharakter IS 'rechtscharakter enumeration SO_Rechtscharakter 1';
COMMENT ON COLUMN so_objekt.sonstrechtscharakter IS 'sonstRechtscharakter CodeList SO_SonstRechtscharakter 0..1';
COMMENT ON COLUMN so_objekt.reftextinhalt IS 'Assoziation zu: FeatureType SO_TextAbschnitt (so_textabschnitt) 0..*';
CREATE TABLE IF NOT EXISTS so_punktobjekt (
  nordwinkel double precision,
  position geometry(MULTIPOINT) NOT NULL
) INHERITS (so_objekt) WITH OIDS;


COMMENT ON TABLE so_punktobjekt IS 'FeatureType: "SO_Punktobjekt"';
COMMENT ON COLUMN so_punktobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN so_punktobjekt.position IS 'position Union XP_Punktgeometrie 1';
CREATE TABLE IF NOT EXISTS so_flaechenobjekt (
  position geometry(MULTIPOLYGON) NOT NULL,
  flaechenschluss character varying NOT NULL
) INHERITS (so_objekt) WITH OIDS;


COMMENT ON TABLE so_flaechenobjekt IS 'FeatureType: "SO_Flaechenobjekt"';
COMMENT ON COLUMN so_flaechenobjekt.position IS 'position Union XP_Flaechengeometrie 1';
COMMENT ON COLUMN so_flaechenobjekt.flaechenschluss IS 'flaechenschluss  Boolean 1';
CREATE TABLE IF NOT EXISTS so_gebiet (
  gemeinde xp_gemeinde,
  aufstellungsbeschhlussdatum date,
  rechtsstandgebiet so_rechtsstandgebiettyp,
  sonstrechtsstandgebiet so_sonstrechtsstandgebiettyp,
  gebietsart so_gebietsart,
  durchfuehrungenddatum date,
  traegermassnahme character varying,
  durchfuehrungstartdatum date,
  sonstgebietsart so_sonstgebietsart
) INHERITS (so_flaechenobjekt) WITH OIDS;


COMMENT ON TABLE so_gebiet IS 'FeatureType: "SO_Gebiet"';
COMMENT ON COLUMN so_gebiet.gemeinde IS 'gemeinde DataType XP_Gemeinde 0..1';
COMMENT ON COLUMN so_gebiet.aufstellungsbeschhlussdatum IS 'aufstellungsbeschhlussDatum  Date 0..1';
COMMENT ON COLUMN so_gebiet.rechtsstandgebiet IS 'rechtsstandGebiet enumeration SO_RechtsstandGebietTyp 0..1';
COMMENT ON COLUMN so_gebiet.sonstrechtsstandgebiet IS 'sonstRechtsstandGebiet CodeList SO_SonstRechtsstandGebietTyp 0..1';
COMMENT ON COLUMN so_gebiet.gebietsart IS 'gebietsArt enumeration SO_GebietsArt 0..1';
COMMENT ON COLUMN so_gebiet.durchfuehrungenddatum IS 'durchfuehrungEndDatum  Date 0..1';
COMMENT ON COLUMN so_gebiet.traegermassnahme IS 'traegerMassnahme  CharacterString 0..1';
COMMENT ON COLUMN so_gebiet.durchfuehrungstartdatum IS 'durchfuehrungStartDatum  Date 0..1';
COMMENT ON COLUMN so_gebiet.sonstgebietsart IS 'sonstGebietsArt CodeList SO_SonstGebietsArt 0..1';
CREATE TABLE IF NOT EXISTS so_geometrieobjekt (
  nordwinkel double precision,
  flussrichtung character varying,
  position geometry NOT NULL,
  flaechenschluss character varying
) INHERITS (so_objekt) WITH OIDS;


COMMENT ON TABLE so_geometrieobjekt IS 'FeatureType: "SO_Geometrieobjekt"';
COMMENT ON COLUMN so_geometrieobjekt.nordwinkel IS 'nordwinkel  Angle 0..1';
COMMENT ON COLUMN so_geometrieobjekt.flussrichtung IS 'flussrichtung  Boolean 0..1';
COMMENT ON COLUMN so_geometrieobjekt.position IS 'position Union XP_VariableGeometrie 1';
COMMENT ON COLUMN so_geometrieobjekt.flaechenschluss IS 'flaechenschluss  Boolean 0..1';
CREATE TABLE IF NOT EXISTS so_bodenschutzrecht (
  detailartderfestlegung so_detailklassifiznachbodenschutzrecht,
  istverdachtsflaeche character varying,
  nummer character varying,
  artderfestlegung so_klassifiznachbodenschutzrecht,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_bodenschutzrecht IS 'FeatureType: "SO_Bodenschutzrecht"';
COMMENT ON COLUMN so_bodenschutzrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachBodenschutzrecht 0..1';
COMMENT ON COLUMN so_bodenschutzrecht.istverdachtsflaeche IS 'istVerdachtsflaeche  Boolean 0..1';
COMMENT ON COLUMN so_bodenschutzrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_bodenschutzrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachBodenschutzrecht 0..1';
COMMENT ON COLUMN so_bodenschutzrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_sonstigesrecht (
  artderfestlegung so_klassifiznachsonstigemrecht,
  nummer character varying,
  name character varying,
  detailartderfestlegung so_detailklassifiznachsonstigemrecht
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_sonstigesrecht IS 'FeatureType: "SO_SonstigesRecht"';
COMMENT ON COLUMN so_sonstigesrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachSonstigemRecht 0..1';
COMMENT ON COLUMN so_sonstigesrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_sonstigesrecht.name IS 'name  CharacterString 0..1';
COMMENT ON COLUMN so_sonstigesrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachSonstigemRecht 0..1';
CREATE TABLE IF NOT EXISTS so_luftverkehrsrecht (
  detailartderfestlegung so_detailklassifiznachluftverkehrsrecht,
  nummer character varying,
  artderfestlegung so_klassifiznachluftverkehrsrecht,
  laermschutzzone so_laermschutzzonetypen,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_luftverkehrsrecht IS 'FeatureType: "SO_Luftverkehrsrecht"';
COMMENT ON COLUMN so_luftverkehrsrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachLuftverkehrsrecht 0..1';
COMMENT ON COLUMN so_luftverkehrsrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_luftverkehrsrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachLuftverkehrsrecht 0..1';
COMMENT ON COLUMN so_luftverkehrsrecht.laermschutzzone IS 'laermschutzzone enumeration SO_LaermschutzzoneTypen 0..1';
COMMENT ON COLUMN so_luftverkehrsrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_strassenverkehrsrecht (
  artderfestlegung so_klassifiznachstrassenverkehrsrecht,
  nummer character varying,
  detailartderfestlegung so_detailklassifiznachstrassenverkehrsrecht,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_strassenverkehrsrecht IS 'FeatureType: "SO_Strassenverkehrsrecht"';
COMMENT ON COLUMN so_strassenverkehrsrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachStrassenverkehrsrecht 0..1';
COMMENT ON COLUMN so_strassenverkehrsrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_strassenverkehrsrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachStrassenverkehrsrecht 0..1';
COMMENT ON COLUMN so_strassenverkehrsrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_forstrecht (
  detailartderfestlegung so_detailklassifiznachforstrecht,
  artderfestlegung so_klassifiznachforstrecht,
  nummer character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_forstrecht IS 'FeatureType: "SO_Forstrecht"';
COMMENT ON COLUMN so_forstrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachForstrecht 0..1';
COMMENT ON COLUMN so_forstrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachForstrecht 0..1';
COMMENT ON COLUMN so_forstrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_forstrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_wasserrecht (
  detailartderfestlegung so_detailklassifiznachwasserrecht,
  artderfestlegung so_klassifiznachwasserrecht,
  nummer character varying,
  istnatuerlichesuberschwemmungsgebiet character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_wasserrecht IS 'FeatureType: "SO_Wasserrecht"';
COMMENT ON COLUMN so_wasserrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachWasserrecht 0..1';
COMMENT ON COLUMN so_wasserrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachWasserrecht 0..1';
COMMENT ON COLUMN so_wasserrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_wasserrecht.istnatuerlichesuberschwemmungsgebiet IS 'istNatuerlichesUberschwemmungsgebiet  Boolean 0..1';
COMMENT ON COLUMN so_wasserrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_denkmalschutzrecht (
  weltkulturerbe character varying,
  detailartderfestlegung so_detailklassifiznachdenkmalschutzrecht,
  artderfestlegung so_klassifiznachdenkmalschutzrecht,
  nummer character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_denkmalschutzrecht IS 'FeatureType: "SO_Denkmalschutzrecht"';
COMMENT ON COLUMN so_denkmalschutzrecht.weltkulturerbe IS 'weltkulturerbe  Boolean 0..1';
COMMENT ON COLUMN so_denkmalschutzrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachDenkmalschutzrecht 0..1';
COMMENT ON COLUMN so_denkmalschutzrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachDenkmalschutzrecht 0..1';
COMMENT ON COLUMN so_denkmalschutzrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_denkmalschutzrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_schienenverkehrsrecht (
  artderfestlegung so_klassifiznachschienenverkehrsrecht,
  detailartderfestlegung so_detailklassifiznachschienenverkehrsrecht,
  nummer character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_schienenverkehrsrecht IS 'FeatureType: "SO_Schienenverkehrsrecht"';
COMMENT ON COLUMN so_schienenverkehrsrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizNachSchienenverkehrsrecht 0..1';
COMMENT ON COLUMN so_schienenverkehrsrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizNachSchienenverkehrsrecht 0..1';
COMMENT ON COLUMN so_schienenverkehrsrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_schienenverkehrsrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_schutzgebietsonstigesrecht (
  artderfestlegung so_klassifizschutzgebietsonstrecht,
  nummer character varying,
  detailartderfestlegung so_detailklassifizschutzgebietsonstrecht,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_schutzgebietsonstigesrecht IS 'FeatureType: "SO_SchutzgebietSonstigesRecht"';
COMMENT ON COLUMN so_schutzgebietsonstigesrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizSchutzgebietSonstRecht 0..1';
COMMENT ON COLUMN so_schutzgebietsonstigesrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_schutzgebietsonstigesrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizSchutzgebietSonstRecht 0..1';
COMMENT ON COLUMN so_schutzgebietsonstigesrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_schutzgebietnaturschutzrecht (
  artderfestlegung xp_klassifizschutzgebietnaturschutzrecht,
  zone so_schutzzonennaturschutzrecht,
  detailartderfestlegung so_detailklassifizschutzgebietnaturschutzrecht,
  nummer character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_schutzgebietnaturschutzrecht IS 'FeatureType: "SO_SchutzgebietNaturschutzrecht"';
COMMENT ON COLUMN so_schutzgebietnaturschutzrecht.artderfestlegung IS 'artDerFestlegung enumeration XP_KlassifizSchutzgebietNaturschutzrecht 0..1';
COMMENT ON COLUMN so_schutzgebietnaturschutzrecht.zone IS 'zone enumeration SO_SchutzzonenNaturschutzrecht 0..1';
COMMENT ON COLUMN so_schutzgebietnaturschutzrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizSchutzgebietNaturschutzrecht 0..1';
COMMENT ON COLUMN so_schutzgebietnaturschutzrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_schutzgebietnaturschutzrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_schutzgebietwasserrecht (
  detailartderfestlegung so_detailklassifizschutzgebietwasserrecht,
  artderfestlegung so_klassifizschutzgebietwasserrecht,
  zone so_schutzzonenwasserrecht,
  nummer character varying,
  name character varying
) INHERITS (so_geometrieobjekt) WITH OIDS;


COMMENT ON TABLE so_schutzgebietwasserrecht IS 'FeatureType: "SO_SchutzgebietWasserrecht"';
COMMENT ON COLUMN so_schutzgebietwasserrecht.detailartderfestlegung IS 'detailArtDerFestlegung CodeList SO_DetailKlassifizSchutzgebietWasserrecht 0..1';
COMMENT ON COLUMN so_schutzgebietwasserrecht.artderfestlegung IS 'artDerFestlegung enumeration SO_KlassifizSchutzgebietWasserrecht 0..1';
COMMENT ON COLUMN so_schutzgebietwasserrecht.zone IS 'zone enumeration SO_SchutzzonenWasserrecht 0..1';
COMMENT ON COLUMN so_schutzgebietwasserrecht.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN so_schutzgebietwasserrecht.name IS 'name  CharacterString 0..1';
CREATE TABLE IF NOT EXISTS so_linienobjekt (
  position geometry NOT NULL
) INHERITS (so_objekt) WITH OIDS;


COMMENT ON TABLE so_linienobjekt IS 'FeatureType: "SO_Linienobjekt"';
COMMENT ON COLUMN so_linienobjekt.position IS 'position Union XP_Liniengeometrie 1';
CREATE TABLE IF NOT EXISTS so_grenze (
  sonsttyp so_sonstgrenzetypen,
  typ xp_grenzetypen
) INHERITS (so_linienobjekt) WITH OIDS;


COMMENT ON TABLE so_grenze IS 'FeatureType: "SO_Grenze"';
COMMENT ON COLUMN so_grenze.sonsttyp IS 'sonstTyp CodeList SO_SonstGrenzeTypen 0..1';
COMMENT ON COLUMN so_grenze.typ IS 'typ enumeration XP_GrenzeTypen 0..1';
CREATE TABLE IF NOT EXISTS xp_bereich (
 gml_id text,
  detailliertebedeutung character varying,
  erstellungsmassstab integer,
  geltungsbereich geometry(MULTIPOLYGON),
  nummer integer NOT NULL,
  bedeutung xp_bedeutungenbereich,
  name character varying,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  planinhalt character(16)[],
  rasterbasis character(16),
  praesentationsobjekt character(16)[],
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_bereich IS 'FeatureType: "XP_Bereich"';
COMMENT ON COLUMN xp_bereich.detailliertebedeutung IS 'detaillierteBedeutung  CharacterString 0..1';
COMMENT ON COLUMN xp_bereich.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';
COMMENT ON COLUMN xp_bereich.geltungsbereich IS 'geltungsbereich Union XP_Flaechengeometrie 0..1';
COMMENT ON COLUMN xp_bereich.nummer IS 'nummer  Integer 1';
COMMENT ON COLUMN xp_bereich.bedeutung IS 'bedeutung enumeration XP_BedeutungenBereich 0..1';
COMMENT ON COLUMN xp_bereich.name IS 'name  CharacterString 0..1';
COMMENT ON COLUMN xp_bereich.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_bereich.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_bereich.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_bereich.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_bereich.planinhalt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';
COMMENT ON COLUMN xp_bereich.rasterbasis IS 'Assoziation zu: FeatureType XP_Rasterdarstellung (xp_rasterdarstellung) 0..1';
COMMENT ON COLUMN xp_bereich.praesentationsobjekt IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';
CREATE TABLE IF NOT EXISTS bp_bereich (
  versionbaunvodatum date,
  versionbaugbtext character varying,
  versionsonstrechtsgrundlagetext character varying,
  versionbaunvotext character varying,
  versionsonstrechtsgrundlagedatum date,
  versionbaugbdatum date,
  gehoertzuplan character(16) NOT NULL
) INHERITS (xp_bereich) WITH OIDS;


COMMENT ON TABLE bp_bereich IS 'FeatureType: "BP_Bereich"';
COMMENT ON COLUMN bp_bereich.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1';
COMMENT ON COLUMN bp_bereich.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1';
COMMENT ON COLUMN bp_bereich.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1';
COMMENT ON COLUMN bp_bereich.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1';
COMMENT ON COLUMN bp_bereich.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1';
COMMENT ON COLUMN bp_bereich.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1';
COMMENT ON COLUMN bp_bereich.gehoertzuplan IS 'Assoziation zu: FeatureType BP_Plan (bp_plan) 1';
CREATE TABLE IF NOT EXISTS fp_bereich (
  versionbaunvodatum date,
  versionbaugbtext character varying,
  versionsonstrechtsgrundlagetext character varying,
  versionbaunvotext character varying,
  versionsonstrechtsgrundlagedatum date,
  versionbaugbdatum date,
  gehoertzuplan character(16) NOT NULL
) INHERITS (xp_bereich) WITH OIDS;


COMMENT ON TABLE fp_bereich IS 'FeatureType: "FP_Bereich"';
COMMENT ON COLUMN fp_bereich.versionbaunvodatum IS 'versionBauNVODatum  Date 0..1';
COMMENT ON COLUMN fp_bereich.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1';
COMMENT ON COLUMN fp_bereich.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1';
COMMENT ON COLUMN fp_bereich.versionbaunvotext IS 'versionBauNVOText  CharacterString 0..1';
COMMENT ON COLUMN fp_bereich.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1';
COMMENT ON COLUMN fp_bereich.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1';
COMMENT ON COLUMN fp_bereich.gehoertzuplan IS 'Assoziation zu: FeatureType FP_Plan (fp_plan) 1';
CREATE TABLE IF NOT EXISTS so_bereich (
  gehoertzuplan character(16) NOT NULL
) INHERITS (xp_bereich) WITH OIDS;


COMMENT ON TABLE so_bereich IS 'FeatureType: "SO_Bereich"';
COMMENT ON COLUMN so_bereich.gehoertzuplan IS 'Assoziation zu: FeatureType SO_Plan (so_plan) 1';
CREATE TABLE IF NOT EXISTS xp_plan (
 gml_id text,
  erstellungsmassstab integer,
  kommentar character varying,
  technherstelldatum date,
  externereferenz xp_spezexternereferenz[],
  wurdegeaendertvon xp_verbundenerplan[],
  nummer character varying,
  genehmigungsdatum date,
  untergangsdatum date,
  beschreibung character varying,
  verfahrensmerkmale xp_verfahrensmerkmal[],
  bezugshoehe double precision,
  internalid character varying,
  aendert xp_verbundenerplan[],
  hatgenerattribut xp_generattribut[],
  name character varying NOT NULL,
  raeumlichergeltungsbereich geometry(MULTIPOLYGON) NOT NULL,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  texte character(16)[],
  begruendungstexte character(16)[],
  inverszu_verbundenerplan_xp_verbundenerplan character(16)[],
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_plan IS 'FeatureType: "XP_Plan"';
COMMENT ON COLUMN xp_plan.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';
COMMENT ON COLUMN xp_plan.kommentar IS 'kommentar  CharacterString 0..1';
COMMENT ON COLUMN xp_plan.technherstelldatum IS 'technHerstellDatum  Date 0..1';
COMMENT ON COLUMN xp_plan.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';
COMMENT ON COLUMN xp_plan.wurdegeaendertvon IS 'wurdeGeaendertVon DataType XP_VerbundenerPlan 0..*';
COMMENT ON COLUMN xp_plan.nummer IS 'nummer  CharacterString 0..1';
COMMENT ON COLUMN xp_plan.genehmigungsdatum IS 'genehmigungsDatum  Date 0..1';
COMMENT ON COLUMN xp_plan.untergangsdatum IS 'untergangsDatum  Date 0..1';
COMMENT ON COLUMN xp_plan.beschreibung IS 'beschreibung  CharacterString 0..1';
COMMENT ON COLUMN xp_plan.verfahrensmerkmale IS 'verfahrensMerkmale DataType XP_VerfahrensMerkmal 0..*';
COMMENT ON COLUMN xp_plan.bezugshoehe IS 'bezugshoehe  Length 0..1';
COMMENT ON COLUMN xp_plan.internalid IS 'internalId  CharacterString 0..1';
COMMENT ON COLUMN xp_plan.aendert IS 'aendert DataType XP_VerbundenerPlan 0..*';
COMMENT ON COLUMN xp_plan.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';
COMMENT ON COLUMN xp_plan.name IS 'name  CharacterString 1';
COMMENT ON COLUMN xp_plan.raeumlichergeltungsbereich IS 'raeumlicherGeltungsbereich Union XP_Flaechengeometrie 1';
COMMENT ON COLUMN xp_plan.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_plan.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_plan.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_plan.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_plan.texte IS 'Assoziation zu: FeatureType XP_TextAbschnitt (xp_textabschnitt) 0..*';
COMMENT ON COLUMN xp_plan.begruendungstexte IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';
COMMENT ON COLUMN xp_plan.inverszu_verbundenerplan_xp_verbundenerplan IS 'Assoziation zu: FeatureType XP_VerbundenerPlan (xp_verbundenerplan) 0..*';
CREATE TABLE IF NOT EXISTS bp_plan (
  veraenderungssperredatum date,
  gemeinde xp_gemeinde[] NOT NULL,
  verfahren bp_verfahren,
  inkrafttretensdatum date,
  durchfuehrungsvertrag character varying,
  staedtebaulichervertrag character varying,
  rechtsverordnungsdatum date,
  rechtsstand bp_rechtsstand,
  hoehenbezug character varying,
  aufstellungsbeschlussdatum date,
  ausfertigungsdatum date,
  satzungsbeschlussdatum date,
  veraenderungssperre character varying,
  auslegungsenddatum date[],
  sonstplanart bp_sonstplanart,
  gruenordnungsplan character varying,
  plangeber xp_plangeber,
  auslegungsstartdatum date[],
  traegerbeteiligungsstartdatum date[],
  aenderungenbisdatum date,
  status bp_status,
  traegerbeteiligungsenddatum date[],
  planart bp_planart[] NOT NULL,
  erschliessungsvertrag character varying,
  bereich character(16)[]
) INHERITS (xp_plan) WITH OIDS;


COMMENT ON TABLE bp_plan IS 'FeatureType: "BP_Plan"';
COMMENT ON COLUMN bp_plan.veraenderungssperredatum IS 'veraenderungssperreDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.gemeinde IS 'gemeinde DataType XP_Gemeinde 1..*';
COMMENT ON COLUMN bp_plan.verfahren IS 'verfahren enumeration BP_Verfahren 0..1';
COMMENT ON COLUMN bp_plan.inkrafttretensdatum IS 'inkrafttretensDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.durchfuehrungsvertrag IS 'durchfuehrungsVertrag  Boolean 0..1';
COMMENT ON COLUMN bp_plan.staedtebaulichervertrag IS 'staedtebaulicherVertrag  Boolean 0..1';
COMMENT ON COLUMN bp_plan.rechtsverordnungsdatum IS 'rechtsverordnungsDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.rechtsstand IS 'rechtsstand enumeration BP_Rechtsstand 0..1';
COMMENT ON COLUMN bp_plan.hoehenbezug IS 'hoehenbezug  CharacterString 0..1';
COMMENT ON COLUMN bp_plan.aufstellungsbeschlussdatum IS 'aufstellungsbeschlussDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.ausfertigungsdatum IS 'ausfertigungsDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.satzungsbeschlussdatum IS 'satzungsbeschlussDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.veraenderungssperre IS 'veraenderungssperre  Boolean 0..1';
COMMENT ON COLUMN bp_plan.auslegungsenddatum IS 'auslegungsEndDatum  Date 0..*';
COMMENT ON COLUMN bp_plan.sonstplanart IS 'sonstPlanArt CodeList BP_SonstPlanArt 0..1';
COMMENT ON COLUMN bp_plan.gruenordnungsplan IS 'gruenordnungsplan  Boolean 0..1';
COMMENT ON COLUMN bp_plan.plangeber IS 'plangeber DataType XP_Plangeber 0..1';
COMMENT ON COLUMN bp_plan.auslegungsstartdatum IS 'auslegungsStartDatum  Date 0..*';
COMMENT ON COLUMN bp_plan.traegerbeteiligungsstartdatum IS 'traegerbeteiligungsStartDatum  Date 0..*';
COMMENT ON COLUMN bp_plan.aenderungenbisdatum IS 'aenderungenBisDatum  Date 0..1';
COMMENT ON COLUMN bp_plan.status IS 'status CodeList BP_Status 0..1';
COMMENT ON COLUMN bp_plan.traegerbeteiligungsenddatum IS 'traegerbeteiligungsEndDatum  Date 0..*';
COMMENT ON COLUMN bp_plan.planart IS 'planArt enumeration BP_PlanArt 1..*';
COMMENT ON COLUMN bp_plan.erschliessungsvertrag IS 'erschliessungsVertrag  Boolean 0..1';
COMMENT ON COLUMN bp_plan.bereich IS 'Assoziation zu: FeatureType BP_Bereich (bp_bereich) 0..*';
CREATE TABLE IF NOT EXISTS fp_plan (
  auslegungsenddatum date[],
  gemeinde xp_gemeinde[] NOT NULL,
  status fp_status,
  sachgebiet character varying,
  plangeber xp_plangeber,
  rechtsstand fp_rechtsstand,
  wirksamkeitsdatum date,
  auslegungsstartdatum date[],
  traegerbeteiligungsstartdatum date[],
  entwurfsbeschlussdatum date,
  aenderungenbisdatum date,
  traegerbeteiligungsenddatum date[],
  verfahren fp_verfahren,
  sonstplanart fp_sonstplanart,
  planart fp_planart NOT NULL,
  planbeschlussdatum date,
  aufstellungsbeschlussdatum date,
  bereich character(16)[]
) INHERITS (xp_plan) WITH OIDS;


COMMENT ON TABLE fp_plan IS 'FeatureType: "FP_Plan"';
COMMENT ON COLUMN fp_plan.auslegungsenddatum IS 'auslegungsEndDatum  Date 0..*';
COMMENT ON COLUMN fp_plan.gemeinde IS 'gemeinde DataType XP_Gemeinde 1..*';
COMMENT ON COLUMN fp_plan.status IS 'status CodeList FP_Status 0..1';
COMMENT ON COLUMN fp_plan.sachgebiet IS 'sachgebiet  CharacterString 0..1';
COMMENT ON COLUMN fp_plan.plangeber IS 'plangeber DataType XP_Plangeber 0..1';
COMMENT ON COLUMN fp_plan.rechtsstand IS 'rechtsstand enumeration FP_Rechtsstand 0..1';
COMMENT ON COLUMN fp_plan.wirksamkeitsdatum IS 'wirksamkeitsDatum  Date 0..1';
COMMENT ON COLUMN fp_plan.auslegungsstartdatum IS 'auslegungsStartDatum  Date 0..*';
COMMENT ON COLUMN fp_plan.traegerbeteiligungsstartdatum IS 'traegerbeteiligungsStartDatum  Date 0..*';
COMMENT ON COLUMN fp_plan.entwurfsbeschlussdatum IS 'entwurfsbeschlussDatum  Date 0..1';
COMMENT ON COLUMN fp_plan.aenderungenbisdatum IS 'aenderungenBisDatum  Date 0..1';
COMMENT ON COLUMN fp_plan.traegerbeteiligungsenddatum IS 'traegerbeteiligungsEndDatum  Date 0..*';
COMMENT ON COLUMN fp_plan.verfahren IS 'verfahren enumeration FP_Verfahren 0..1';
COMMENT ON COLUMN fp_plan.sonstplanart IS 'sonstPlanArt CodeList FP_SonstPlanArt 0..1';
COMMENT ON COLUMN fp_plan.planart IS 'planArt enumeration FP_PlanArt 1';
COMMENT ON COLUMN fp_plan.planbeschlussdatum IS 'planbeschlussDatum  Date 0..1';
COMMENT ON COLUMN fp_plan.aufstellungsbeschlussdatum IS 'aufstellungsbeschlussDatum  Date 0..1';
COMMENT ON COLUMN fp_plan.bereich IS 'Assoziation zu: FeatureType FP_Bereich (fp_bereich) 0..*';
CREATE TABLE IF NOT EXISTS so_plan (
  plangeber xp_plangeber,
  planart so_planart NOT NULL,
  bereich character(16)[]
) INHERITS (xp_plan) WITH OIDS;


COMMENT ON TABLE so_plan IS 'FeatureType: "SO_Plan"';
COMMENT ON COLUMN so_plan.plangeber IS 'plangeber DataType XP_Plangeber 0..1';
COMMENT ON COLUMN so_plan.planart IS 'planArt CodeList SO_PlanArt 1';
COMMENT ON COLUMN so_plan.bereich IS 'Assoziation zu: FeatureType SO_Bereich (so_bereich) 0..*';
CREATE TABLE IF NOT EXISTS xp_abstraktespraesentationsobjekt (
 gml_id text,
  index integer[],
  stylesheetid xp_stylesheetliste,
  darstellungsprioritaet integer,
  art character varying[],
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  dientzurdarstellungvon character(16)[],
  gehoertzubereich character(16),
  inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt character(16),
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_abstraktespraesentationsobjekt IS 'FeatureType: "XP_AbstraktesPraesentationsobjekt"';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.index IS 'index  Integer 0..*';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.stylesheetid IS 'stylesheetId CodeList XP_StylesheetListe 0..1';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.darstellungsprioritaet IS 'darstellungsprioritaet  Integer 0..1';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.art IS 'art  CharacterString 0..*';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.dientzurdarstellungvon IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';
COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt IS 'Assoziation zu: FeatureType RP_Legendenobjekt (rp_legendenobjekt) 0..1';
CREATE TABLE IF NOT EXISTS xp_praesentationsobjekt (

) INHERITS (xp_abstraktespraesentationsobjekt) WITH OIDS;


COMMENT ON TABLE xp_praesentationsobjekt IS 'FeatureType: "XP_Praesentationsobjekt"';
CREATE TABLE IF NOT EXISTS xp_ppo (
  drehwinkel double precision,
  position geometry(MULTIPOINT) NOT NULL,
  skalierung double precision,
  hat character(16)
) INHERITS (xp_abstraktespraesentationsobjekt) WITH OIDS;


COMMENT ON TABLE xp_ppo IS 'FeatureType: "XP_PPO"';
COMMENT ON COLUMN xp_ppo.drehwinkel IS 'drehwinkel  Angle 0..1';
COMMENT ON COLUMN xp_ppo.position IS 'position Union XP_Punktgeometrie 1';
COMMENT ON COLUMN xp_ppo.skalierung IS 'skalierung  Decimal 0..1';
COMMENT ON COLUMN xp_ppo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';
CREATE TABLE IF NOT EXISTS xp_lpo (
  position geometry NOT NULL,
  inverszu_hat_xp_ppo character(16)[],
  inverszu_hat_xp_tpo character(16)[]
) INHERITS (xp_abstraktespraesentationsobjekt) WITH OIDS;


COMMENT ON TABLE xp_lpo IS 'FeatureType: "XP_LPO"';
COMMENT ON COLUMN xp_lpo.position IS 'position Union XP_Liniengeometrie 1';
COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_ppo IS 'Assoziation zu: FeatureType XP_PPO (xp_ppo) 0..*';
COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_tpo IS 'Assoziation zu: FeatureType XP_TPO (xp_tpo) 0..*';
CREATE TABLE IF NOT EXISTS xp_tpo (
  vertikaleausrichtung xp_vertikaleausrichtung,
  schriftinhalt character varying,
  horizontaleausrichtung xp_horizontaleausrichtung,
  skalierung double precision,
  fontsperrung double precision,
  hat character(16)
) INHERITS (xp_abstraktespraesentationsobjekt) WITH OIDS;


COMMENT ON TABLE xp_tpo IS 'FeatureType: "XP_TPO"';
COMMENT ON COLUMN xp_tpo.vertikaleausrichtung IS 'vertikaleAusrichtung enumeration XP_VertikaleAusrichtung 0..1';
COMMENT ON COLUMN xp_tpo.schriftinhalt IS 'schriftinhalt  CharacterString 0..1';
COMMENT ON COLUMN xp_tpo.horizontaleausrichtung IS 'horizontaleAusrichtung enumeration XP_HorizontaleAusrichtung 0..1';
COMMENT ON COLUMN xp_tpo.skalierung IS 'skalierung  Decimal 0..1';
COMMENT ON COLUMN xp_tpo.fontsperrung IS 'fontSperrung  Decimal 0..1';
COMMENT ON COLUMN xp_tpo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';
CREATE TABLE IF NOT EXISTS xp_lto (
  position geometry NOT NULL
) INHERITS (xp_tpo) WITH OIDS;


COMMENT ON TABLE xp_lto IS 'FeatureType: "XP_LTO"';
COMMENT ON COLUMN xp_lto.position IS 'position Union XP_Liniengeometrie 1';
CREATE TABLE IF NOT EXISTS xp_pto (
  drehwinkel double precision,
  position geometry(MULTIPOINT) NOT NULL
) INHERITS (xp_tpo) WITH OIDS;


COMMENT ON TABLE xp_pto IS 'FeatureType: "XP_PTO"';
COMMENT ON COLUMN xp_pto.drehwinkel IS 'drehwinkel  Angle 0..1';
COMMENT ON COLUMN xp_pto.position IS 'position Union XP_Punktgeometrie 1';
CREATE TABLE IF NOT EXISTS xp_nutzungsschablone (
  spaltenanz integer NOT NULL,
  zeilenanz integer NOT NULL
) INHERITS (xp_pto) WITH OIDS;


COMMENT ON TABLE xp_nutzungsschablone IS 'FeatureType: "XP_Nutzungsschablone"';
COMMENT ON COLUMN xp_nutzungsschablone.spaltenanz IS 'spaltenAnz  Integer 1';
COMMENT ON COLUMN xp_nutzungsschablone.zeilenanz IS 'zeilenAnz  Integer 1';
CREATE TABLE IF NOT EXISTS xp_fpo (
  position geometry(MULTIPOLYGON) NOT NULL
) INHERITS (xp_abstraktespraesentationsobjekt) WITH OIDS;


COMMENT ON TABLE xp_fpo IS 'FeatureType: "XP_FPO"';
COMMENT ON COLUMN xp_fpo.position IS 'position Union XP_Flaechengeometrie 1';
CREATE TABLE IF NOT EXISTS xp_rasterdarstellung (
 gml_id text,
  refscan xp_externereferenz[] NOT NULL,
  reflegende xp_externereferenz[],
  reftext xp_externereferenz,
  user_id integer,
  created_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  updated_at timestamp without time zone NOT NULL DEFAULT current_timestamp,
  konvertierung_id integer,
  inverszu_rasterbasis_xp_bereich character(16)[],
  PRIMARY KEY (gml_id)
) WITH OIDS;


COMMENT ON TABLE xp_rasterdarstellung IS 'FeatureType: "XP_Rasterdarstellung"';
COMMENT ON COLUMN xp_rasterdarstellung.refscan IS 'refScan DataType XP_ExterneReferenz 1..*';
COMMENT ON COLUMN xp_rasterdarstellung.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';
COMMENT ON COLUMN xp_rasterdarstellung.reftext IS 'refText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN xp_rasterdarstellung.user_id IS 'user_id  integer ';
COMMENT ON COLUMN xp_rasterdarstellung.created_at IS 'created_at  timestamp without time zone ';
COMMENT ON COLUMN xp_rasterdarstellung.updated_at IS 'updated_at  timestamp without time zone ';
COMMENT ON COLUMN xp_rasterdarstellung.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xp_rasterdarstellung.inverszu_rasterbasis_xp_bereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..*';

CREATE TABLE IF NOT EXISTS xp_objekt_zu_xp_begruendungabschnitt (
  xp_objekt_gml_id text,
  xp_begruendungabschnitt_gml_id text,
  PRIMARY KEY (xp_objekt_gml_id, xp_begruendungabschnitt_gml_id)
);

COMMENT ON TABLE xp_objekt_zu_xp_begruendungabschnitt IS 'Association XP_Objekt _zu_ XP_BegruendungAbschnitt';

COMMENT ON COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_objekt_gml_id IS 'refBegruendungInhalt';
COMMENT ON COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_begruendungabschnitt_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS xp_objekt_zu_xp_abstraktespraesentationsobjekt (
  xp_objekt_gml_id text,
  xp_abstraktespraesentationsobjekt_gml_id text,
  PRIMARY KEY (xp_objekt_gml_id, xp_abstraktespraesentationsobjekt_gml_id)
);

COMMENT ON TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt IS 'Association XP_Objekt _zu_ XP_AbstraktesPraesentationsobjekt';

COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_objekt_gml_id IS 'wirdDargestelltDurch';
COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_abstraktespraesentationsobjekt_gml_id IS 'dientZurDarstellungVon';

CREATE TABLE IF NOT EXISTS bp_nebenanlagenausschlussflaeche_zu_bp_textabschnitt (
  bp_nebenanlagenausschlussflaeche_gml_id text,
  bp_textabschnitt_gml_id text,
  PRIMARY KEY (bp_nebenanlagenausschlussflaeche_gml_id, bp_textabschnitt_gml_id)
);

COMMENT ON TABLE bp_nebenanlagenausschlussflaeche_zu_bp_textabschnitt IS 'Association BP_NebenanlagenAusschlussFlaeche _zu_ BP_TextAbschnitt';

COMMENT ON COLUMN bp_nebenanlagenausschlussflaeche_zu_bp_textabschnitt.bp_nebenanlagenausschlussflaeche_gml_id IS 'abweichungText';
COMMENT ON COLUMN bp_nebenanlagenausschlussflaeche_zu_bp_textabschnitt.bp_textabschnitt_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_baugebietsteilflaeche_zu_bp_textabschnitt (
  bp_baugebietsteilflaeche_gml_id text,
  bp_textabschnitt_gml_id text,
  PRIMARY KEY (bp_baugebietsteilflaeche_gml_id, bp_textabschnitt_gml_id)
);

COMMENT ON TABLE bp_baugebietsteilflaeche_zu_bp_textabschnitt IS 'Association BP_BaugebietsTeilFlaeche _zu_ BP_TextAbschnitt';

COMMENT ON COLUMN bp_baugebietsteilflaeche_zu_bp_textabschnitt.bp_baugebietsteilflaeche_gml_id IS 'abweichungText';
COMMENT ON COLUMN bp_baugebietsteilflaeche_zu_bp_textabschnitt.bp_textabschnitt_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_textabschnitt (
  bp_objekt_gml_id text,
  bp_textabschnitt_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_textabschnitt_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_textabschnitt IS 'Association BP_Objekt _zu_ BP_TextAbschnitt';

COMMENT ON COLUMN bp_objekt_zu_bp_textabschnitt.bp_objekt_gml_id IS 'refTextInhalt';
COMMENT ON COLUMN bp_objekt_zu_bp_textabschnitt.bp_textabschnitt_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae (
  bp_gemeinschaftsanlagenzuordnung_gml_id text,
  bp_gemeinschaftsanlagenflaeche_gml_id text,
  PRIMARY KEY (bp_gemeinschaftsanlagenzuordnung_gml_id, bp_gemeinschaftsanlagenflaeche_gml_id)
);

COMMENT ON TABLE bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae IS 'Association BP_GemeinschaftsanlagenZuordnung _zu_ BP_GemeinschaftsanlagenFlaeche';

COMMENT ON COLUMN bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae.bp_gemeinschaftsanlagenzuordnung_gml_id IS 'zuordnung';
COMMENT ON COLUMN bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae.bp_gemeinschaftsanlagenflaeche_gml_id IS '<undefined>';
ALTER TABLE bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae ADD COLUMN bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae character varying(255);

COMMENT ON COLUMN bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae.bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflae
IS 'bp_gemeinschaftsanlagenzuordnung_zu_bp_gemeinschaftsanlagenflaeche';


CREATE TABLE IF NOT EXISTS bp_ueberbaubaregrundstuecksflaeche_zu_bp_baulinie (
  bp_ueberbaubaregrundstuecksflaeche_gml_id text,
  bp_baulinie_gml_id text,
  PRIMARY KEY (bp_ueberbaubaregrundstuecksflaeche_gml_id, bp_baulinie_gml_id)
);

COMMENT ON TABLE bp_ueberbaubaregrundstuecksflaeche_zu_bp_baulinie IS 'Association BP_UeberbaubareGrundstuecksFlaeche _zu_ BP_BauLinie';

COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche_zu_bp_baulinie.bp_ueberbaubaregrundstuecksflaeche_gml_id IS 'baulinie';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche_zu_bp_baulinie.bp_baulinie_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_ueberbaubaregrundstuecksflaeche_zu_bp_baugrenze (
  bp_ueberbaubaregrundstuecksflaeche_gml_id text,
  bp_baugrenze_gml_id text,
  PRIMARY KEY (bp_ueberbaubaregrundstuecksflaeche_gml_id, bp_baugrenze_gml_id)
);

COMMENT ON TABLE bp_ueberbaubaregrundstuecksflaeche_zu_bp_baugrenze IS 'Association BP_UeberbaubareGrundstuecksFlaeche _zu_ BP_BauGrenze';

COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche_zu_bp_baugrenze.bp_ueberbaubaregrundstuecksflaeche_gml_id IS 'baugrenze';
COMMENT ON COLUMN bp_ueberbaubaregrundstuecksflaeche_zu_bp_baugrenze.bp_baugrenze_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_gemeinschaftsanlagenflaeche_zu_bp_baugebietsteilflaeche (
  bp_gemeinschaftsanlagenflaeche_gml_id text,
  bp_baugebietsteilflaeche_gml_id text,
  PRIMARY KEY (bp_gemeinschaftsanlagenflaeche_gml_id, bp_baugebietsteilflaeche_gml_id)
);

COMMENT ON TABLE bp_gemeinschaftsanlagenflaeche_zu_bp_baugebietsteilflaeche IS 'Association BP_GemeinschaftsanlagenFlaeche _zu_ BP_BaugebietsTeilFlaeche';

COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche_zu_bp_baugebietsteilflaeche.bp_gemeinschaftsanlagenflaeche_gml_id IS 'eigentuemer';
COMMENT ON COLUMN bp_gemeinschaftsanlagenflaeche_zu_bp_baugebietsteilflaeche.bp_baugebietsteilflaeche_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_ausgleichsmassnahme (
  bp_objekt_gml_id text,
  bp_ausgleichsmassnahme_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_ausgleichsmassnahme_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_ausgleichsmassnahme IS 'Association BP_Objekt _zu_ BP_AusgleichsMassnahme';

COMMENT ON COLUMN bp_objekt_zu_bp_ausgleichsmassnahme.bp_objekt_gml_id IS 'wirdAusgeglichenDurchMassnahme';
COMMENT ON COLUMN bp_objekt_zu_bp_ausgleichsmassnahme.bp_ausgleichsmassnahme_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_anpflanzungbindungerhaltung (
  bp_objekt_gml_id text,
  bp_anpflanzungbindungerhaltung_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_anpflanzungbindungerhaltung_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_anpflanzungbindungerhaltung IS 'Association BP_Objekt _zu_ BP_AnpflanzungBindungErhaltung';

COMMENT ON COLUMN bp_objekt_zu_bp_anpflanzungbindungerhaltung.bp_objekt_gml_id IS 'wirdAusgeglichenDurchABE';
COMMENT ON COLUMN bp_objekt_zu_bp_anpflanzungbindungerhaltung.bp_anpflanzungbindungerhaltung_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_ausgleichsflaeche (
  bp_objekt_gml_id text,
  bp_ausgleichsflaeche_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_ausgleichsflaeche_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_ausgleichsflaeche IS 'Association BP_Objekt _zu_ BP_AusgleichsFlaeche';

COMMENT ON COLUMN bp_objekt_zu_bp_ausgleichsflaeche.bp_objekt_gml_id IS 'wirdAusgeglichenDurchFlaeche';
COMMENT ON COLUMN bp_objekt_zu_bp_ausgleichsflaeche.bp_ausgleichsflaeche_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_schutzpflegeentwicklungsmassnahme (
  bp_objekt_gml_id text,
  bp_schutzpflegeentwicklungsmassnahme_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_schutzpflegeentwicklungsmassnahme_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_schutzpflegeentwicklungsmassnahme IS 'Association BP_Objekt _zu_ BP_SchutzPflegeEntwicklungsMassnahme';

COMMENT ON COLUMN bp_objekt_zu_bp_schutzpflegeentwicklungsmassnahme.bp_objekt_gml_id IS 'wirdAusgeglichenDurchSPEMassnahme';
COMMENT ON COLUMN bp_objekt_zu_bp_schutzpflegeentwicklungsmassnahme.bp_schutzpflegeentwicklungsmassnahme_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_objekt_zu_bp_schutzpflegeentwicklungsflaeche (
  bp_objekt_gml_id text,
  bp_schutzpflegeentwicklungsflaeche_gml_id text,
  PRIMARY KEY (bp_objekt_gml_id, bp_schutzpflegeentwicklungsflaeche_gml_id)
);

COMMENT ON TABLE bp_objekt_zu_bp_schutzpflegeentwicklungsflaeche IS 'Association BP_Objekt _zu_ BP_SchutzPflegeEntwicklungsFlaeche';

COMMENT ON COLUMN bp_objekt_zu_bp_schutzpflegeentwicklungsflaeche.bp_objekt_gml_id IS 'wirdAusgeglichenDurchSPEFlaeche';
COMMENT ON COLUMN bp_objekt_zu_bp_schutzpflegeentwicklungsflaeche.bp_schutzpflegeentwicklungsflaeche_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre (
  bp_verkehrsflaechebesondererzweckbestimmung_gml_id text,
  bp_strassenbegrenzungslinie_gml_id text,
  PRIMARY KEY (bp_verkehrsflaechebesondererzweckbestimmung_gml_id, bp_strassenbegrenzungslinie_gml_id)
);

COMMENT ON TABLE bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre IS 'Association BP_VerkehrsflaecheBesondererZweckbestimmung _zu_ BP_StrassenbegrenzungsLinie';

COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre.bp_verkehrsflaechebesondererzweckbestimmung_gml_id IS 'begrenzungslinie';
COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre.bp_strassenbegrenzungslinie_gml_id IS '<undefined>';
ALTER TABLE bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre ADD COLUMN bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre character varying(255);

COMMENT ON COLUMN bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre.bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegre
IS 'bp_verkehrsflaechebesondererzweckbestimmung_zu_bp_strassenbegrenzungslinie';


CREATE TABLE IF NOT EXISTS bp_strassenverkehrsflaeche_zu_bp_strassenbegrenzungslinie (
  bp_strassenverkehrsflaeche_gml_id text,
  bp_strassenbegrenzungslinie_gml_id text,
  PRIMARY KEY (bp_strassenverkehrsflaeche_gml_id, bp_strassenbegrenzungslinie_gml_id)
);

COMMENT ON TABLE bp_strassenverkehrsflaeche_zu_bp_strassenbegrenzungslinie IS 'Association BP_StrassenVerkehrsFlaeche _zu_ BP_StrassenbegrenzungsLinie';

COMMENT ON COLUMN bp_strassenverkehrsflaeche_zu_bp_strassenbegrenzungslinie.bp_strassenverkehrsflaeche_gml_id IS 'begrenzungslinie';
COMMENT ON COLUMN bp_strassenverkehrsflaeche_zu_bp_strassenbegrenzungslinie.bp_strassenbegrenzungslinie_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS fp_objekt_zu_fp_textabschnitt (
  fp_objekt_gml_id text,
  fp_textabschnitt_gml_id text,
  PRIMARY KEY (fp_objekt_gml_id, fp_textabschnitt_gml_id)
);

COMMENT ON TABLE fp_objekt_zu_fp_textabschnitt IS 'Association FP_Objekt _zu_ FP_TextAbschnitt';

COMMENT ON COLUMN fp_objekt_zu_fp_textabschnitt.fp_objekt_gml_id IS 'refTextInhalt';
COMMENT ON COLUMN fp_objekt_zu_fp_textabschnitt.fp_textabschnitt_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS fp_objekt_zu_fp_ausgleichsflaeche (
  fp_objekt_gml_id text,
  fp_ausgleichsflaeche_gml_id text,
  PRIMARY KEY (fp_objekt_gml_id, fp_ausgleichsflaeche_gml_id)
);

COMMENT ON TABLE fp_objekt_zu_fp_ausgleichsflaeche IS 'Association FP_Objekt _zu_ FP_AusgleichsFlaeche';

COMMENT ON COLUMN fp_objekt_zu_fp_ausgleichsflaeche.fp_objekt_gml_id IS 'wirdAusgeglichenDurchFlaeche';
COMMENT ON COLUMN fp_objekt_zu_fp_ausgleichsflaeche.fp_ausgleichsflaeche_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS fp_objekt_zu_fp_schutzpflegeentwicklung (
  fp_objekt_gml_id text,
  fp_schutzpflegeentwicklung_gml_id text,
  PRIMARY KEY (fp_objekt_gml_id, fp_schutzpflegeentwicklung_gml_id)
);

COMMENT ON TABLE fp_objekt_zu_fp_schutzpflegeentwicklung IS 'Association FP_Objekt _zu_ FP_SchutzPflegeEntwicklung';

COMMENT ON COLUMN fp_objekt_zu_fp_schutzpflegeentwicklung.fp_objekt_gml_id IS 'wirdAusgeglichenDurchSPE';
COMMENT ON COLUMN fp_objekt_zu_fp_schutzpflegeentwicklung.fp_schutzpflegeentwicklung_gml_id IS '<undefined>';

CREATE TABLE IF NOT EXISTS so_objekt_zu_so_textabschnitt (
  so_objekt_gml_id text,
  so_textabschnitt_gml_id text,
  PRIMARY KEY (so_objekt_gml_id, so_textabschnitt_gml_id)
);

COMMENT ON TABLE so_objekt_zu_so_textabschnitt IS 'Association SO_Objekt _zu_ SO_TextAbschnitt';

COMMENT ON COLUMN so_objekt_zu_so_textabschnitt.so_objekt_gml_id IS 'refTextInhalt';
COMMENT ON COLUMN so_objekt_zu_so_textabschnitt.so_textabschnitt_gml_id IS '<undefined>';

COMMIT;
