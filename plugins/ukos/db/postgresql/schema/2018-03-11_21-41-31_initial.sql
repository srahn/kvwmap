BEGIN;

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

---------------------------------------------------------------
---------------------------------------------------------------
-- CREATE SCHEMATA
---------------------------------------------------------------
---------------------------------------------------------------
-- Drop Schemata
DROP SCHEMA IF EXISTS base CASCADE;
DROP SCHEMA IF EXISTS doppik CASCADE;
DROP SCHEMA IF EXISTS okstra CASCADE;
DROP SCHEMA IF EXISTS strassennetz CASCADE;
DROP SCHEMA IF EXISTS kataster CASCADE;
  
-- schema doppik
CREATE SCHEMA doppik;
COMMENT ON SCHEMA doppik
	IS 'This schema contains relevant OKSTRA and doppik features. Every non-abstract table ultimately inherits from an abstract table called basisobjekt';

-- schema okstra
CREATE SCHEMA IF NOT EXISTS okstra;
COMMENT ON SCHEMA okstra
	IS 'This schema contains relevant OKSTRA	features and codelists. All tables can be considered abstract and realized through inheritance in Doppik. They likewise contain all relevant information to create okstra-gml.';

CREATE SCHEMA IF NOT EXISTS base;
COMMENT ON SCHEMA base
	IS 'This schema contains basefeatures and codelists that are referenced through objects from other schemata. Every non-abstract table inherits from an abstract table called werteliste or basisobjekt';

CREATE SCHEMA IF NOT EXISTS strassennetz;
COMMENT ON SCHEMA strassennetz
	IS 'This schema contains a street network';

CREATE SCHEMA IF NOT EXISTS kataster;
COMMENT ON SCHEMA kataster
	IS 'This schema contains cadastral parcel data';
---------------------------------------------------------------
---------------------------------------------------------------
-- CREATE BASISCODELISTE
---------------------------------------------------------------
---------------------------------------------------------------
-- Basisobjekt contains data (is a generalization)from doppik and OKSTRA, which are relevant for all other tables
-- Basisobjekt is considered abstract and should not be realized on its own
CREATE TABLE base.basiscodeliste (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	kurztext character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	langtext character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	sortierreihenfolge character varying NOT NULL DEFAULT '001'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_basiscodeliste PRIMARY KEY (id)
)
WITH (
	OIDS=FALSE
);
COMMENT ON TABLE base.basiscodeliste
	IS 'This abstract table containts base information shared by all doppik codelists';

CREATE TABLE base.werteliste (
	kennung character varying NOT NULL,
	langtext character varying,
	CONSTRAINT pk_werteliste PRIMARY KEY (kennung)
);
COMMENT ON TABLE base.werteliste
	IS 'This abstract table is the base for most codelists. A few codelists related to okstra bewuchs and baum do not follow the kennung/langtext columns of werteliste and therefore do not inherit from it';

CREATE TABLE base.idents (
	ident character(6) NOT NULL,
	name_schema character varying NOT NULL,
	name_tabelle character varying NOT NULL,
	CONSTRAINT pk_idents PRIMARY KEY (ident)
);
COMMENT ON TABLE base.idents
	IS 'In this table all idents and their origin (schema and table) are stored.';

CREATE OR REPLACE FUNCTION base.idents_add_ident()
  RETURNS trigger AS
  $BODY$
    DECLARE
      chars char[];
    BEGIN
        IF (NEW.ident IS NULL OR NEW.ident = '') THEN
            chars := ARRAY['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
            LOOP
                NEW.ident = (SELECT array_to_string(ARRAY(SELECT chars[(1 + round(random() * 62))::integer] FROM generate_series(1, 6)), ''));
                EXIT WHEN NEW.ident NOT IN (SELECT ident FROM base.idents);
            END LOOP;
            INSERT INTO base.idents (ident, name_schema, name_tabelle) VALUES (NEW.ident, TG_TABLE_SCHEMA, TG_TABLE_NAME);
            RETURN NEW;
        ELSIF (NEW.ident NOT IN (SELECT ident FROM base.idents)) THEN
            INSERT INTO base.idents (ident, name_schema, name_tabelle) VALUES (NEW.ident, TG_TABLE_SCHEMA, TG_TABLE_NAME);
            RETURN NEW;
        ELSIF (NEW.gueltig_bis != '2100-01-01 02:00:00+01'::timestamp with time zone) THEN
            RAISE EXCEPTION 'Es wird versucht ein lebendes Objekt mit bereits vorhandenem Attribut ident zu erzeugen.';
        END IF;
    END
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

CREATE OR REPLACE FUNCTION base.idents_remove_ident()
  RETURNS trigger AS
  $BODY$
    BEGIN
        IF (OLD.ident IN (SELECT ident FROM base.idents)) THEN
            DELETE FROM base.idents WHERE ident = OLD.ident;
        END IF;
        RETURN NEW;
    END
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;

---------------------------------------------------------------
-- CODELISTEN OKSTRA
---------------------------------------------------------------
CREATE TABLE okstra.wlo_kreuzungszuordnung (
	CONSTRAINT pk_wlo_kreuzungszuordnung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_kreuzungszuordnung
VALUES
('1', 'liegt in nicht aufzunehmender Straße'),
('2', 'liegt in aufzunehmender Straße, abweichende Unterhaltungszuordnung vorhanden');

CREATE TABLE okstra.wlo_erfassung_verfahren (
	CONSTRAINT pk_wlo_erfassung_verfahren PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_erfassung_verfahren
VALUES
('1', 'photogrammetrisch'),
('2', 'photogrammetrisch mit Feldvergleich'),
('3', 'terrestrisch aufgemessen'),
('4', 'digitalisiert'),
('5', 'eingeschritten'),
('6', 'Übernahme aus Liegenschaftskarte'),
('99', 'sonstige');

CREATE TABLE okstra.wlo_schutzstatus_bewuchs (
	CONSTRAINT pk_wlo_schutzstatus_bewuchs PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_schutzstatus_bewuchs
VALUES
('1', 'Landschaftsschutzgebiet (LSG)'),
('2', 'Naturschutzgebiet (NSG)'),
('3', 'Naturdenkmal (ND)'),
('4', 'Fauna/Flora/Habitat (FFH)'),
('5', 'geschützter Landschaftsbestandteil');

CREATE TABLE okstra.wlo_tab_biotoptyp (
	CONSTRAINT pk_wlo_tab_biotoptyp PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_bestandsstatus (
	CONSTRAINT pk_wlo_bestandsstatus PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_bestandsstatus
VALUES
('1', 'Bestand_erfasst'),
('2', 'Bestand_amtlich'),
('3', 'geplant/neu'),
('4', 'geplant/Entfall'),
('5', 'zerstört'),
('6', 'unbekannt');

CREATE TABLE okstra.wlo_lage (
	CONSTRAINT pk_wlo_lage PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lage
VALUES
('00', 'gesamte Fahrbahn(en) (ein- und zweibahnig)'),
('01', 'linker Fahrbahnrand (einbahnig)'),
('02', 'linke Fahrbahn, linker Fahrbahnrand (zweibahnig)'),
('03', 'linke Fahrbahn (zweibahnig)'),
('04', 'linke Fahrbahn, rechter Fahrbahnrand (zweibahnig)'),
('05', 'Mitte/Bestandsachse'),
('06', 'rechte Fahrbahn, linker Fahrbahnrand (zweibahnig)'),
('07', 'rechte Fahrbahn (zweibahnig'),
('08', 'rechte Fahrbahn, rechter Fahrbahnrand (zweibahnig)'),
('09', 'rechter Fahrbahnrand (einbahnig)'),
('10', 'Hauptfahrstreifen gegen Stat.-Richtung'),
('11', 'Hauptfahrstreifen gegen Stat.-Richtung, links'),
('12', 'Hauptfahrstreifen gegen Stat.-Richtung, Mitte'),
('13', 'Hauptfahrstreifen gegen Stat.-Richtung, rechts'),
('20', 'Hauptfahrstreifen in Stat.-Richtung'),
('21', 'Hauptfahrstreifen in Stat.-Richtung, rechts'),
('22', 'Hauptfahrstreifen in Stat.-Richtung, Mitte'),
('23', 'Hauptfahrstreifen in Stat.-Richtung, links'),
('30', '1. Überholstreifen gegen Stat.-Richtung'),
('31', '1. Überholstreifen gegen Stat.-Richtung, links'),
('32', '1. Überholstreifen gegen Stat.-Richtung, Mitte'),
('33', '1. Überholstreifen gegen Stat.-Richtung, rechts'),
('40', '1. Überholstreifen in Stat.-Richtung'),
('41', '1. Überholstreifen in Stat.-Richtung, links'),
('42', '1. Überholstreifen in Stat.-Richtung, Mitte'),
('43', '1. Überholstreifen in Stat.-Richtung, rechts'),
('50', '2. Überholstreifen gegen Stat.-Richtung'),
('51', '2. Überholstreifen gegen Stat.-Richtung, links'),
('52', '2. Überholstreifen gegen Stat.-Richtung, Mitte'),
('53', '2. Überholstreifen gegen Stat.-Richtung, rechts'),
('60', '2. Überholstreifen in Stat.-Richtung'),
('61', '2. Überholstreifen in Stat.-Richtung, rechts'),
('62', '2. Überholstreifen in Stat.-Richtung, Mitte'),
('63', '2. Überholstreifen in Stat.-Richtung, links'),
('70', '3. Überholstreifen gegen Stat.-Richtung'),
('71', '3. Überholstreifen gegen Stat.-Richtung, links'),
('72', '3. Überholstreifen gegen Stat.-Richtung, Mitte'),
('73', '3. Überholstreifen gegen Stat.-Richtung, rechts'),
('77', 'linke Fahrbahn, Fahrbahnachse (zweibahnig)'),
('80', '3. Überholstreifen in Stat.-Richtung'),
('81', '3. Überholstreifen in Stat.-Richtung, rechts'),
('82', '3. Überholstreifen in Stat.-Richtung, Mitte'),
('83', '3. Überholstreifen in Stat.-Richtung, links'),
('88', 'rechte Fahrbahn, Fahrbahnachse (zweibahnig)'),
('94', 'Punkt im Querprofil auf keiner Achse'),
('95', 'links außerhalb'),
('96', 'rechts außerhalb'),
('97', 'Straße liegt innerhalb'),
('98', 'beidseitig'),
('99', 'unbekannte Lage');

-- The structure of baumgattung diverts from other codelists, insofar that it has 3 columns (Kennung, deutscher_Name, botanischer_Name)
-- Therefore, it does not inherit from the base codelist but defines its own columns
CREATE TABLE okstra.wlo_baumgattung (
	kennung character varying NOT NULL,
	deutscher_name character varying,
	botanischer_name character varying, 
	CONSTRAINT pk_wlo_baumgattung PRIMARY KEY (kennung)
);
INSERT INTO okstra.wlo_baumgattung
VALUES
('000', 'Baum (allgemein)', ''),
('030', 'Laubbaum', ''),
('040', 'Nadelbaum', ''),
('100', 'Ahorn', 'Acer'),
('110', 'Rosskastanie', 'Aesculus'),
('120', 'Götterbaum', 'Ailanthus'),
('130', 'Erle', 'Alnus'),
('140', 'Aralie', 'Aralia'),
('150', 'Berberitze', 'Berberis'),
('160', 'Birke', 'Betula'),
('170', 'Hainbuche', 'Carpinus'),
('180', 'Hickory', 'Carya'),
('190', 'Kastanie', 'Castanea'),
('200', 'Trompetenbaum', 'Catalpa'),
('210', 'Zürgelbaum', 'Celtis'),
('220', 'Kasurabaum', 'Cercidiphyllum'),
('230', 'Judasbaum', 'Cercis'),
('240', 'Hartriegel', 'Cornus'),
('250', 'Scheinhasel', 'Corylopsis'),
('260', 'Haselnuss', 'Corylus'),
('270', 'Weißdort', 'Crataegus'),
('280', 'Ölweide', 'Elaeagnus'),
('290', 'Spindelstrauch', 'Euonymus'),
('300', 'Buche', 'Fagus'),
('310', 'Esche', 'Fraxinus'),
('320', 'Gleditschie Lederhülsenbaum', 'Gleditsia'),
('330', 'Zaubernuss', 'Hamamelis'),
('340', 'Sanddort', 'Hippophae'),
('350', 'Nussbaum', 'Juglans'),
('360', 'Goldregen', 'Laburnum'),
('370', 'Magnolie', 'Magnolia'),
('380', 'Kultur-Apfel', 'Malus'),
('390', 'Maulbeere', 'Morus'),
('400', 'Platane', 'Platanus'),
('410', 'Pappel', 'Populus'),
('420', 'Pflaume, Krische, Pfirsich', 'Prunus'),
('430', 'Flügelnuss', 'Pterocaria'),
('440', 'Birne', 'Pyrus'),
('450', 'Eiche', 'Quercus'),
('460', 'Kreuzdorn', 'Rhamnus'),
('470', 'Sumach', 'Rhus'),
('480', 'Robinie', 'Robinia'),
('490', 'Weide', 'Salix'),
('500', 'Holunder', 'Sambucus'),
('510', 'Eberesche', 'Sorbus'),
('520', 'Flieder', 'Syringa'),
('530', 'Linde', 'Tilia'),
('540', 'Ulme', 'Ulmus'),
('550', 'Zelkove', 'Zelkova'),
('700', 'Tanne', 'Abies'),
('710', 'Zeder', 'Cedrus'),
('720', 'Scheinzypresse', 'Chamaecyparis'),
('730', 'Ginkgo, Fächerblattbaum', 'Ginkgo'),
('740', 'Wachholder', 'Juniperus'),
('750', 'Lärche', 'Larix'),
('760', 'Fichte', 'Picea'),
('770', 'Kiefer', 'Pinus'),
('780', 'Lebensbaum', 'Thuja'),
('790', 'Hemlocktanne', 'Tsuga');

-- The structure of baumgattung diverts from other codelists, insofar that it has 4 columns (Kennung, deutscher_Name, Gattungskennung, botanischer_Name)
-- Therefore, it does not inherit from the base codelist but defines its own columns
CREATE TABLE okstra.wlo_baumart (
	kennung character varying NOT NULL,
	deutscher_name character varying,
	gattungskennung character varying,
	botanischer_name character varying,
	CONSTRAINT pk_wlo_baumart PRIMARY KEY (kennung)
);
INSERT INTO okstra.wlo_baumart
VALUES
('1337', '100', 'Feldahorn', 'Acer campestre'),
('1338', '100', 'Roter Schlangenhautahorn', 'Acer capillipes'),
('1339', '100', 'Weinahorn', 'Acer circinatum'),
('1345', '100', 'Französischer Ahorn', 'Acer monspessulanum'),
('1347', '100', 'Eschenahorn', 'Acer negundo'),
('1348', '100', 'Goldbunter Eschenahorn', 'Acer negundo "Aureovariegatum"'),
('1349', '100', 'Gelber Eschenahorn', 'Acer negundo "Odessanum"'),
('1350', '100', 'Silberbunter Eschenahorn', 'Acer negundo "Variegatum"'),
('1351', '100', 'Fächerahorn', 'acer palmatum'),
('1352', '100', 'Roter Fächerahorn', 'Acer palmatum "Atropurpureum"'),
('1355', '100', 'Roter Schlitzahorn', 'Acer palmatum "Dissecum Atropurpureum"'),
('1356', '100', 'Roter Schlitzahorn "Garnet"', 'Acer palmatum "Dissecum Garnet"'),
('1357', '100', 'Roter Schlitzahorn "Nigrum"', 'Acer palmatum "Dissecum Nigrum"'),
('1359', '100', 'Grüner Schlitzahorn', 'Acer palmatum Dissecum'),
('1362', '100', 'Spitzahorn', 'Acer platanoides'),
('1365', '100', 'Blutahorn, Roter Spitzahorn "Faassen"s Black"', 'Acer platanoides "Faassen"s Black"'),
('1366', '100', 'Kugelahorn', 'Acer platanoides "Globosum"'),
('1367', '100', 'Vogelkrallenahorn "Laciniatum"', 'Acer platanoides "Laciniatum"'),
('1368', '100', 'Spitzahorn "Reitenbachii"', 'Acer platanoides "Reitenbachii"'),
('1369', '100', 'Kegelförmiger Spitzahorn "Emerald Queen"', 'Acer platanoides "Emerald Queen"'),
('1370', '100', 'Bergahorn', 'Acer pseudoplatanus'),
('1371', '100', 'Schmalkroniger Bergahorn "Erectum"', 'Acer pseudoplatanus "ErectuM"'),
('1375', '100', 'Rotahorn', 'Acer rubrum'),
('1376', '100', 'Rostbartahorn', 'Acer rufinerve'),
('1377', '100', 'Silberahorn', 'Acer saccharinum'),
('1382', '100', 'Geschlitzter Silberahorn ''Wieri''', 'Acer saccharinum ''Wieri'''),
('1385', '110', 'Rotblühende Rosskastanie, Purpurkastanie', 'Aesculus carnea'),
('1387', '110', 'Gemeine Rosskastanie', 'Aesculus hippocastanum'),
('1388', '110', 'Gefülltblühende Rosskastanie', 'Aesculus hippocastanum ''Baumannii'''),
('1390', '120', 'Götterbaum', 'Ailanthus altissima'),
('1391', '130', 'Schwarzerle, Roterle', 'Alnus glutinosa'),
('1392', '130', 'Kaisererle ''Imperialis''', 'Alnus glutinosa ''Imperialis'''),
('1393', '130', 'Grauerle, Weißerle', 'Alnus incana'),
('1394', '130', 'Geschlitzblättrige Grauerle', 'Alnus incana ''Laciniata'''),
('1395', '130', 'Golderle', 'Alnus incana ''Aurea'''),
('1396', '130', 'Grünerle, Alpenerle', 'Alnus viridis'),
('1398', '000', 'Kupfer-Felsenbirne', 'Amelanchier lamarckii'),
('1399', '000', 'Hängende Felsenbirne', 'Amelanchier laevis'),
('1400', '000', 'Echte Felsenbirne', 'Amelanchier ovalis'),
('1405', '140', 'Jap. Angelikabaum, Jap. Aralie', 'Aralia elata'),
('1406', '140', 'Goldaralie', 'Aralia elata ''Aureovariegata'''),
('1407', '140', 'Silberaralie', 'Aralia elata ''Variegata'''),
('1422', '150', 'Grüne Heckenberberitze', 'Berberis thunbergii'),
('1443', '160', 'Schwarzbirke, Flussbirke', 'Betula nigra'),
('1444', '160', 'Papierbirke', 'Betula papyrifera'),
('1448', '160', 'Moor-Birke', 'Betula pubescens'),
('1450', '160', 'Himalayabirke', 'Betula jacquemontii'),
('1451', '160', 'Sandbirke, Weißbirke', 'Betula pendula'),
('1452', '160', 'Schlitzblättrige Birke', 'Betula pendula ''Dalecarlica'''),
('1453', '160', 'Säulenbirke', 'Betula pendula ''Fastigiata'''),
('1455', '160', 'Blutbirke, Purpurbirke', 'Betula pendula ''Purpurea'''),
('1456', '160', 'Hängebirke', 'Betula pendula ''Tristis'''),
('1457', '160', 'Trauerbirke', 'Betula pendula ''Youngii'''),
('1510', '170', 'Hainbuche, Weißbuche', 'Carpinus betulus'),
('1511', '170', 'Gemeine Weißbuche (Säulenform)', 'Carpinus betulus ''Fastigiata'''),
('1514', '190', 'Esskastanie', 'Castanea sativa'),
('1515', '200', 'Gew. Trompetenbaum', 'Catalpa bignonioides'),
('1523', '220', 'Judasblattbaum', 'Cercidiphyllum japonicum'),
('1524', '230', 'Gemeiner Judasbaum', 'Cercis siliquastrum'),
('1557', '240', 'Weißer Hartriegel', 'Cornus alba'),
('1565', '240', 'Hoher Etagenhartriegel', 'Cornus controversa'),
('1566', '240', 'Amerikanischer Blumen-Hartriegel', 'Cornus florida'),
('1568', '240', 'Japanischer Blumen-Hartriegel', 'Cornus kousa'),
('1570', '240', 'Kornelkirsche', 'Cornus mas'),
('1571', '240', 'Westamerikanischer Blumen-Hartriegel', 'Cornus nutallii'),
('1572', '240', 'Roter Hartriegel', 'Cornus sanguinea'),
('1576', '260', 'Haselnuss', 'Corylus avellana'),
('1577', '260', 'Goldhasel', 'Corylus avellana ''Aurea'''),
('1578', '260', 'Korkenzieherhasel', 'Corylus avellana ''Contorta'''),
('1583', '400', 'Morgenländische Platane', 'Platanus orientalis'),
('1590', '260', 'Baumhasel', 'Corylus colurna'),
('1591', '260', 'Bluthasel', 'Corylus maxima ''Purpurea'''),
('1635', '270', 'Lavalles Weißdorn, Apfeldorn', 'Crataegus lavallei ''Carrierei'''),
('1638', '270', 'Eingriffliger Weißdorn', 'Crataegus monogyna'),
('1639', '270', 'Rotdorn', 'Crataegus laevigata ''Paul''s Scarlet'''),
('1641', '270', 'Zweigriffliger Weißdorn', 'Crataegus laevigata'),
('1642', '270', 'Pflaumenbl. Weißdorn, Pflaumendorn', 'Crataegus prunifolia'),
('1697', '280', 'Schmalblättrige Ölweide', 'Elaeagnus angustifolia'),
('1698', '280', 'Silberölweide', 'Elaeagnus commutata'),
('1699', '280', 'Essbare Ölweide', 'Elaeagnus muliflora'),
('1726', '290', 'Pfaffenhütchen', 'Euonymus europaeus'),
('1736', '770', 'Murray''s-Drehkiefer, Murraykiefer', 'Pinus contorta murrayana'),
('1739', '300', 'Rotbuche', 'Fagus sylvatica'),
('1741', '300', 'Veredelte Blutbuche', 'Fagus sylvatica ''Purpurea Latifolia'''),
('1744', '300', 'Säulen-Rotbuche', 'Fagus sylvatica ''Dawyck'''),
('1747', '300', 'Grüne Hängebuche', 'Fagus sylvatica ''Pendula'''),
('1748', '300', 'Blutbuche-Sämling, Purpurbuche', 'Fagus sylvatica ''Purpurea'''),
('1749', '300', 'Trauerblutbuche, Schwarzrote Hängebuche', 'Fagus sylvatica ''Pupurea-Pendula'''),
('1761', '310', 'Gemeine Esche', 'Fraxinus excelsior'),
('1762', '770', 'Schwarzkiefer', 'Pinus nigra'),
('1764', '310', 'Hänge-Esche', 'Fraxinus excelsior ''Pendula'''),
('1765', '310', 'Nichtfruchtende Straßenesche', 'Fraxinus excelsior ''Westhof''s Glorie'''),
('1766', '310', 'Blumenesche', 'Fraxinus ornus'),
('1782', '320', 'Lederhülsenbaum', 'Gleditsia triacanthos'),
('1783', '000', 'Geweihbaum', 'Gymnocladus dioicus'),
('1784', '000', 'Schneeglöckchenbaum', 'Halesia carolina'),
('1788', '330', 'Japanische Zaubernuss', 'Hamamelis japonica'),
('1793', '330', 'Lichtmess-Zaubernuss', 'Hamamelis mollis'),
('1798', '330', 'Herbstblühende Zaubernuss', 'Hamamelis virginiana'),
('1827', '340', 'Sanddorn', 'Hippophae rhamnoides'),
('1847', '000', 'Gemeine Stechpalme, Hülse', 'Ilex aquifolium'),
('1869', '350', 'Schwarznuss', 'Juglans nigra'),
('1870', '350', 'Walnuss', 'Juglans regia'),
('1876', '000', 'Blasenbaum', 'Koelreuteria paniculata'),
('1880', '360', 'Alpen-Goldregen', 'Laburnum alpinum'),
('1881', '360', 'Gemeiner Goldregen', 'Laburnum anagyroidis'),
('1882', '360', 'Edel-Goldregen', 'Laburnum watereri ''Vossii'''),
('1883', '000', 'Amberbaum', 'Liquidambar styraciflua'),
('1894', '000', 'Amerikanischer Tulpenbaum', 'Liriodendron tulpifera'),
('1919', '370', 'Sommermagnolie', 'Magnolia sieboldii'),
('1920', '370', 'Tulpenmagnolie', 'Magnolia soulangiana'),
('1927', '370', 'Sternmagnolie', 'Magnolia stellata'),
('1938', '380', 'Wildapfel', 'Malus sylvestris'),
('1972', '380', 'Zierapfel (alle)', 'Malus ''Professor Sprenger'''),
('1973', '390', 'Weiße Maulbeere', 'Morus alba'),
('1976', '000', 'Scheinbuche', 'Nothofagus antarctica'),
('1985', '000', 'Eisenbaum', 'Parrotia persica'),
('1986', '000', 'Blauglockenbaum', 'Paulownia tomentosa'),
('1992', '000', 'Echter Korkbaum', 'Phellodendron amurense'),
('2019', '400', 'Ahornblättrige Platane', 'Platanus acerifolia'),
('2023', '410', 'Balsampappel', 'Populus balsamifera'),
('2024', '410', 'Berliner Lorbeerpappel', 'Populus berolinensis'),
('2027', '410', 'Graupappel', 'Populus canescens'),
('2036', '410', 'Pyramidenpappel', 'Populus nigra ''Italica'''),
('2037', '410', 'Birkenpappel', 'Populus simonii'),
('2039', '410', 'Zitterpappel, Espe', 'Populus tremula'),
('2040', '410', 'Säulen-Zitterpappel', 'Populus tremula ''Erecta'''),
('2042', '410', 'Hänge-Zitterpappel', 'Populus tremula ''Pendula'''),
('2043', '410', 'Westliche Balsampappel', 'Populus trichocarpa'),
('2061', '420', 'Vogelkirsche, Wildkirsche', 'Prunus avium'),
('2062', '420', 'Süßkirsche', 'Prunus avium C.'),
('2064', '420', 'Wildpflaume', 'Prunus cerasifera'),
('2065', '420', 'Blutpflaume', 'Prunus cerasifera ''Nigra'''),
('2076', '420', 'Steinweichsel', 'Prunus mahaleb'),
('2077', '420', 'Traubenkirsche', 'Prunus padus'),
('2078', '420', 'Pfirsisch', 'Prunus persica'),
('2080', '420', 'Spätbl. Traubenkirsche', 'Prunus serotina'),
('2092', '420', 'Schlehe / Schwarzdorn', 'Prunus spinosa'),
('2122', '440', 'Holzbirne, Gemeine Birne', 'Pyrus communis'),
('2125', '450', 'Zerreiche', 'Quercus cerris'),
('2126', '450', 'Scharlach-Eiche', 'Quercus coccinea'),
('2127', '450', 'Ungarische Eiche', 'Quercus frainetto'),
('2129', '450', 'Sumpfeiche', 'Quercus palustris'),
('2130', '450', 'Stieleiche, Sommereiche', 'Quercus robur'),
('2131', '450', 'Pyramideneiche', 'Quercus robur ''Fastigiata'''),
('2132', '450', 'Traubeneiche, Wintereiche', 'Quercus petraea'),
('2134', '450', 'Amerikanische Roteiche', 'Quercus rubra'),
('2135', '450', 'Wintergrüne Eiche', 'Quercus turneri ''Pseudoturneri'''),
('2136', '460', 'Purgier-Kreuzdorn', 'Rhamnus catharticus'),
('2137', '460', 'Faulbaum', 'Rhamnus frangula'),
('2139', '470', 'Essigbaum', 'Rhus glabra'),
('2141', '470', 'Hirschkolben-Sumach, Essigbaum', 'Rhus typhina'),
('2156', '480', 'Robinie, Scheinakazie', 'Robinia pseudoacacia'),
('2157', '480', 'Kegel-Robinie, Kegel-Akazie', 'Robinia pseudoacacia ''Bessoniana'''),
('2160', '480', 'Straßen-Robinie, Straßen-Akazie', 'Robinia pseudoacacia ''Monophylla'''),
('2162', '480', 'Korkenzieher-Robinie, Korkenzieher-Akazie', 'Robinia pseudoacacia ''Tortuosa'''),
('2163', '480', 'Kugel-Robinie, Kugel-Akazie', 'Robinia pseudoacacia ''Umbraculifera'''),
('2182', '490', 'Silberweide', 'Salix alba'),
('2184', '490', 'Silberweide ''Liempde''', 'Salix alba ''Liempde'''),
('2185', '490', 'Straßenweide', 'Salix alba ''Sericea'''),
('2186', '490', 'Trauerweide', 'Salix alba ''Tristis'''),
('2190', '490', 'Ohrweide', 'Salix aurita'),
('2194', '490', 'Salweide', 'Salix caprea'),
('2198', '490', 'Graue Weide, Aschweide', 'Salix cinerea'),
('2204', '490', 'Bruchweide, Knackweide', 'Salix fragilis'),
('2211', '490', 'Korkenzieherweide', 'Salix matsudana ''Tortuosa'''),
('2223', '490', 'Korbweide', 'Salix viminalis'),
('2227', '500', 'Schwarzer Holunder', 'Sambucus nigra'),
('2232', '500', 'Roter Holunder, Traubenholunder', 'Sambucus racemosa'),
('2237', '000', 'Schnurbaum', 'Sophora japonica'),
('2242', '510', 'Amerikanische Eberesche', 'Sorbus americana'),
('2243', '510', 'Mehlbeere', 'Sorbus aria'),
('2247', '510', 'Vogelbeere, Eberesche', 'Sorbus aucuparia'),
('2248', '510', 'Säulen-Eberesche, Pyramiden-Eberesche', 'Sorbus aucuparia ''Fastigiata'''),
('2255', '510', 'Essbare Eberesche', 'Sorbus aucuparia ''Edulis'''),
('2266', '510', 'Schwedische Mehlbeere', 'Sorbus intermedia'),
('2268', '510', 'Park-Mehlbeere, Breitblättrige Mehlbeere', 'Sorbus latifolia'),
('2272', '510', 'Elsbeere', 'Sorbus torminalis'),
('2273', '510', 'Vielfiedrige Eberesche', 'Sorbus vilmorinii'),
('2298', '000', 'Japanischer Storaxbaum', 'Styrax japonica'),
('2324', '520', 'Wild-Flieder', 'Syringa vulgaris'),
('2352', '530', 'Riesenblättrige Linde', 'Tilia americana ''Nova'''),
('2353', '530', 'Winterlinde', 'Tilia cordata'),
('2354', '530', 'Krimlinde', 'Tilia euchlora'),
('2355', '530', 'Holländische Linde', 'Tilia europaea'),
('2357', '530', 'Kaiserlinde', 'Tilia europaea ''Pallida'''),
('2358', '530', 'Sommerlinde', 'Tilia platyphyllos'),
('2359', '530', 'Silberlinde', 'Tilia tomentosa'),
('2361', '540', 'Feldulme', 'Ulmus carpinifolia'),
('2362', '540', 'Goldulme', 'Ulmus carpinifolia ''Wredei'''),
('2363', '540', 'Bergulme', 'Ulmus glabra'),
('2365', '540', 'Stadt-Ulme, Holländische Ulme', 'Ulmus hollandica'),
('2402', '550', 'Kaukasus-Zelkove', 'Zelkova carpinifolia'),
('2403', '550', 'Keaki-Zelkove', 'Zelkova serrata'),
('2404', '700', 'Weißtanne', 'Abies alba'),
('2405', '700', 'Purpurtanne', 'Abies amabilis'),
('2407', '700', 'Balsamtanne', 'Abies balsamea'),
('2410', '700', 'Griechische Tanne', 'Abies cephalonica'),
('2411', '700', 'Coloradotanne, Grautanne, Blautanne', 'Abies concolor'),
('2415', '700', 'Küstentanne', 'Abies grandis'),
('2417', '700', 'Nikkotanne', 'Abies homolepis'),
('2419', '700', 'Koreatanne', 'Abies koreana'),
('2426', '700', 'Adelstanne', 'Abies procera'),
('2428', '700', 'Nordmannstanne', 'Abies nordmanniana'),
('2432', '700', 'Veitch''s-Tanne', 'Abies veitchii'),
('2433', '000', 'Araukarie, Schmucktanne', 'Araucaria araucana'),
('2434', '780', 'Morgenländischer Lebensbaum', 'Thuja orientalis'),
('2435', '710', 'Atlaszeder', 'Cedrus atlantica'),
('2441', '710', 'Himalaya-Zeder', 'Cedrus deodara'),
('2442', '710', 'Libanon-Zeder', 'Cedrus libani'),
('2443', '000', 'Kopfeibe', 'Cephalotaxus fortunei'),
('2444', '720', 'Lawsons Scheinzypresse', 'Chamaecyparis lawsoniana'),
('2446', '720', 'Blaue Säulenzypresse', 'Chamaecyparis lawsoniana ''Columnaris'''),
('2471', '720', 'Nutka Scheinzypresse', 'Chamaecyparis nootkatensis'),
('2475', '720', 'Hinoki-Scheinzypresse', 'Chamaecyparis obtusa'),
('2484', '720', 'Silberzypresse', 'Chamaecyparis pisifera'),
('2503', '000', 'Sicheltanne', 'Cryptomeria japonica'),
('2508', '730', 'Fächerblattbaum, Ginkgo', 'Ginkgo biloba'),
('2509', '730', 'Säulen-Fächerblattbaum', 'Ginkgo biloba ''Fastigiata'''),
('2527', '740', 'Chinesischer Wacholder', 'Juniperus chinensis'),
('2533', '740', 'Gemeiner Wacholder', 'Juniperus communis'),
('2559', '740', 'Zypressen-Wacholder', 'Juniperus virginiana'),
('2568', '750', 'Europäische Lärche', 'Larix decidua'),
('2570', '750', 'Japanische Lärche', 'Larix kaempferi'),
('2574', '000', 'Chinesisches Rotholz, Urwelt-Mammutbaum', 'Metasequoia glyptostroboides'),
('2577', '760', 'Mähnenfichte', 'Picea breweriana'),
('2578', '760', 'Engelmann-Fichte', 'Picea engelmannii'),
('2579', '760', 'Gemeine Fichte, Rottanne', 'Picea abies'),
('2582', '760', 'Säulenfichte', 'Picea abies ''Columnaris'''),
('2589', '760', 'Trauer-Hänge-Fichte', 'Picea abies ''Inversa'''),
('2606', '760', 'Weißfichte', 'Picea glauca'),
('2611', '760', 'Schwarzfichte', 'Picea mariana'),
('2614', '760', 'Serbische Fichte', 'Picea omorica'),
('2617', '760', 'Kaukasusfichte', 'Picea orientalis'),
('2621', '760', 'Stechfichte', 'Picea pungens'),
('2622', '760', 'Blaue Stechfichte, Blaufichte', 'Picea pungens glauca'),
('2635', '760', 'Sitkafichte', 'Picea sitchensis'),
('2636', '770', 'Fuchsschwanzkiefer', 'Pinus aristata'),
('2638', '770', 'Zirbelkiefer, Arve', 'Pinus cembra'),
('2643', '770', 'Sibirische Kiefer', 'Pinus sibirica'),
('2644', '770', 'Drehkiefer', 'Pinus contorta'),
('2648', '770', 'Tränenkiefer', 'Pinus wallichiana'),
('2650', '770', 'Schlangenhautkiefer', 'Pinus leucodermis'),
('2651', '770', 'Jeffrey''s Kiefer', 'Pinus jeffreyi'),
('2654', '770', 'Bergkiefer, Latsche', 'Pinus mugo'),
('2660', '770', 'Österreichische Schwarzkiefer', 'Pinus nigra austriaca'),
('2662', '770', 'Mädchenkiefer', 'Pinus parviflora'),
('2664', '770', 'Rumelische Kiefer, Mazedonische Kiefer', 'Pinus peuce'),
('2665', '770', 'Gelbkiefer', 'Pinus ponderosa'),
('2668', '770', 'Zapfenkiefer', 'Pinus schwerinii'),
('2669', '770', 'Gemeine Kiefer', 'Pinus sylvestris'),
('2672', '770', 'Weymouthskiefer, Strobe', 'Pinus strobus'),
('2676', '000', 'Douglasie, Douglasfichte, Mirbel', 'Pseudotsuga menziesii'),
('2680', '000', 'Kalifornischer Mammutbaum', 'Sequoiadendron giganteum'),
('2681', '000', 'Sumpfzypresse', 'Taxodium districhum'),
('2682', '000', 'Eibe', 'Taxus baccata'),
('2718', '780', 'Abendländischer Lebensbaum', 'Thuja occidentalis'),
('2742', '780', 'Riesenlebensbaum', 'Thuja plicata'),
('2747', '780', 'Japanischer Lebensbaum', 'Thuja standishii'),
('2751', '790', 'Kanadische Hemlocktanne', 'Tsuga canadensis'),
('2756', '790', 'Grüne Hemlocktanne', 'Tsuga heterophylla'),
('2762', '310', 'Einblättrige Esche', 'Fraxinus excelsior ''Diversifolia'''),
('2777', '530', 'Hänge-Silber-Linde, Großblättrige Silberlinde', 'Tilia petiolaris'),
('2785', '000', 'Leyland-Zypresse', 'Cupressucyparis leclandii'),
('2822', '530', 'Kleinblättrige Winterlinde', 'Tilia cordata ''Sheridan'''),
('2829', '510', 'Speierling', 'Sorbus domestica'),
('2844', '490', 'Kegelförmige Silberweide', 'Salix alba ''Chermesina'''),
('2854', '100', 'Kegelförmiger Bergahorn ''Negenia''', 'Acer pseudoplatanus ''Negenia'''),
('2855', '100', 'Breitkegelförmiger Bergahorn ''Rotterdam''', 'Acer pseudoplatanus ''Rotterdam'''),
('2858', '170', 'Rotlaubige Hainbuche', 'Carpinus betulus ''Purpurea'''),
('2864', '100', 'Spitzahorn ''Olmsted''', 'Acer platanoides ''Olmsted'''),
('2867', '310', 'Goldesche', 'Fraxinus excelsior ''Aurea'''),
('2869', '310', 'Kegelförmige Esche', 'Fraxinus excelsior ''Eureka'''),
('2870', '420', 'Lorbeerkirsche, Kirschlorbeer', 'Prunus laurocerasus'),
('2872', '310', 'Schmalkronige Esche', 'Fraxinus excelsior ''Geessink'''),
('2931', '100', 'Oregon-Blutahorn', 'Acer platanoides ''Royal Red'''),
('2960', '540', 'Exter-Ulme', 'Ulmus glabra ''Exoniensis'''),
('2961', '310', 'Kleinkronige Esche ''Raywood''', 'Fraxinus angustifolia ''Raywood'''),
('2964', '440', 'Stadtbirne', 'Pyrus calleryana'),
('2968', '110', 'Kugel-Rosskastanie', 'Aesculus hippocastanum ''Umbraculifera'''),
('2969', '420', 'Sandkirsche', 'Prunus fruticosa'),
('2978', '270', 'Säulen Weißdorn', 'Crataegus monogyna ''Stricta'''),
('3047', '100', 'Purpurblättriger Bergahorn', 'Acer pseudoplatanus ''Atropurpureum'''),
('3061', '130', 'Italienische Erle', 'Alnus cordata'),
('3114', '410', 'Schwarzpappel', 'Populus nigra'),
('3115', '420', 'Sauerkirsche', 'Prunus cerasus'),
('3116', '420', 'Haus-Pflaume', 'Prunus domestica'),
('3136', '540', 'Flatter-Ulme', 'Ulmus laevis'),
('3257', '000', 'Spießtanne', 'Cunninghamia lanceolata'),
('3258', '730', 'Hängender Fächerblattbaum', 'Ginkgo biloba ''Pendula'''),
('3288', '100', 'Davidsahorn', 'Acer davidii'),
('3292', '100', 'Zuckerahorn', 'Acer saccharum'),
('3301', '170', 'Eichenblättrige Hainbuche', 'Carpinus betulus ''Quercifolia'''),
('3317', '300', 'Orientalische Buche', 'Fagus orientalis'),
('3344', '450', 'Steineiche', 'Quercus ilex'),
('3348', '450', 'Amerikanische Goldeiche', 'Quercus rubra ''Aurea'''),
('3371', '530', 'Großblättrige Sommerlinde', 'Tilia platyphyllos ''Laciniata'''),
('3385', '320', 'Lederhülsenbaum ''Pyramidalis''', 'Gleditsia triacanthos ''Pyramidalis'''),
('3398', '530', 'Gold-Sommerlinde', 'Tilia platyphyllos ''Aurea'''),
('3513', '530', 'Kleinkronige Winterlinde', 'Tilia cordata ''Müllerklein'''),
('3747', '540', 'Resistente Ulme', 'Ulmus ''Resista'''),
('3810', '100', 'Spitzahorn ''Farlake''s Green''', 'Acer platanoides ''Farlake''s Green'''),
('3886', '110', 'Säulen-Rosskastanie', 'Aesculus hippocastanum ''Fastigiata'''),
('4323', '770', 'Hakenkiefer', 'Pinus uncinata'),
('4475', '450', 'Japan. Kaisereiche, Daimio-Eiche', 'Quercus dentata'),
('4510', '480', 'Pyramiden-Robinie, Pyramiden-Akazie', 'Robinia pseudoacacia ''Pyramidalis'''),
('4520', '100', 'Kegelförmiger Spitzahorn', 'Acer platanoides ''Cleveland'''),
('4524', '000', 'Arizona-Zypresse', 'Cupressus arizonica'),
('4571', '100', 'Säulenförmiger Spitzahorn', 'Acer platanoides ''Columnare'''),
('4573', '100', 'Schattenahorn ''Summershade''', 'Acer platanoides ''Summershade'''),
('4580', '530', 'Amerikanische Stadtlinde', 'Tilia cordata ''Greenspire'''),
('4674', '210', 'Südlicher Zürgelbaum', 'Celtis australis'),
('4676', '210', 'Amerikanischer Zürgelbaum', 'Celtis occidentalis'),
('4757', '100', 'Roter Spitzahorn ''Crimson King''', 'Acer platanoides ''Crimson King'''),
('4760', '320', 'Dornenloser Lederhülsenbaum', 'Gleditsia triacanthos inermis'),
('4762', '390', 'Schwarze Maulbeere', 'Morus nigra'),
('4791', '100', 'Kolchischer Spitzahorn', 'Acer cappadocicum'),
('4795', '110', 'Appalachen-Rosskastanie', 'Aesculus flava'),
('4799', '310', 'Schmalblättrige Esche', 'Fraxinus angustifolia'),
('4800', '310', 'Rotesche', 'Fraxinus pennsylvanica'),
('4802', '410', 'Silberpappel', 'Populus alba'),
('4803', '410', 'Kanadische Holzpappel', 'Populus canadensis'),
('4807', '450', 'Flaumeiche', 'Quercus pubescens'),
('4810', '510', 'Thüringische Eberesche', 'Sorbus thuringiaca'),
('4811', '530', 'Amerikanische Linde', 'Tilia americana'),
('5439', '490', 'Silberweide ''Taucha''', 'Salix alba ''Taucha'''),
('5549', '530', 'Säulenförmige Krimlinde', 'Tilia euchlora ''Pallida Fastigiata'''),
('9999', '000', 'Baumart nicht bestimmt', 'nicht bestimmt');

CREATE TABLE okstra.wlo_schiefstand_baum (
	CONSTRAINT pk_wlo_schiefstand_baum PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_schiefstand_baum
VALUES
('0', 'kein'),
('1', 'ja, ohne Angabe'),
('2', 'zur Fahrbahn'),
('3', 'von der Fahrbahn'),
('4', 'parallel zur Fahrbahn');

CREATE TABLE okstra.wlo_zustandsbeurteilung_baum (
	CONSTRAINT pk_wlo_zustandsbeurteilung_baum PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_zustandsbeurteilung_baum
VALUES
('1', 'gesund'),
('2', 'sehr schwach geschädigt'),
('3', 'mittelstark geschädigt'),
('4', 'stark geschädigt'),
('5', 'absterbend bis tot');

CREATE TABLE okstra.wlo_lagebeschreibung_baum (
	CONSTRAINT pk_wlo_lagebeschreibung_baum PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lagebeschreibung_baum
VALUES
('1', 'Baum auf Trennstreifen zwischen Radweg und Straße'),
('2', 'Baum zwischen Radweg und Graben'),
('3', 'Baum zwischen Radweg und benachbartem Grundstück'),
('4', 'Baum im Geh- oder Radweg'),
('5', 'Baum in Pflasterfläche'),
('6', 'Baum hinter Gehweg');

CREATE TABLE okstra.wlo_detaillierungsgrad_asb (
	CONSTRAINT pk_wlo_detaillierungsgad_asb PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_detaillierungsgrad_asb
VALUES
('01', 'hoch'),
('02', 'mittel'),
('03', 'niedrig');

CREATE TABLE okstra.wlo_art_der_erfassung (
	CONSTRAINT pk_wlo_art_der_erfassung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_der_erfassung
VALUES
('00', 'unbekannt'),
('10', 'vor Ort gemessen'),
('11', 'aus kinematischer Erfassung'),
('12', 'eigene Digitalisierung'),
('13', 'Fremddigitalisierung'),
('14', 'aus Bauunterlagen'),
('15', 'aus Entwurfsunterlagen'),
('16', 'geschätzt'),
('17', 'ATKIS'),
('18', 'ALK'),
('19', 'SIB-Bauwerke'),
('20', 'Sonstiges Fachinformationssystem'),
('99', 'sonstige Art der Erfassung');

CREATE TABLE okstra.wlo_art_der_erfassung_sonst (
	CONSTRAINT pk_wlo_art_der_erfassung_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_quelle_der_information (
	CONSTRAINT pk_wlo_quelle_der_information PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_quelle_der_information
VALUES
('00', 'unbekannt'),
('01', 'Ingenieurbüro'),
('02', 'Straßenbauverwaltung'),
('03', 'Bund'),
('04', 'Kreise'),
('99', 'sonstige Quelle der Information');

CREATE TABLE okstra.wlo_quelle_der_information_sonst (
	CONSTRAINT pk_wlo_quelle_der_information_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_strassenklasse (
	CONSTRAINT pk_wlo_strassenklasse PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_strassenklasse
VALUES
('A', 'Bundesautobahn'),
('B', 'Bundesstraße'),
('L', 'Landesstraße'),
('S', 'Staatsstraße'),
('K', 'Kreisstraße'),
('G', 'Gemeindestraße'),
('N', 'Nicht öffentliche Straße');

CREATE TABLE okstra.wlo_art_strassenausst_punkt (
	CONSTRAINT pk_wlo_art_strassenausst_punkt PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_strassenausst_punkt
VALUES
('01', 'Glättemeldeanlage'),
('02', 'Streugutbehälter'),
('03', 'Taumittelsprühanlage'),
('04', 'Geschwindigkeitswarnanlage'),
('05', 'Verkehrsbeeinflussungsanlage'),
('06', 'Lichtsignalanlage'),
('07', 'Nebelwarnanlage'),
('08', 'Geschwindigkeitsüberwachungsanlage'),
('09', 'Stauwarnanlage'),
('10', 'Verkehrsspiegel'),
('11', 'Notrufsäule'),
('12', 'SOS-Telefon'),
('14', 'Leitpfosten'),
('15', 'Kilometerstein, Kilometertafel'),
('16', 'historischer Kilometerstein'),
('17', 'Abfallbehälter (nur an der Strecke)'),
('18', 'Flucht- / Schlupftür in Wänden / Zäunen'),
('19', 'Beleuchtung'),
('20', 'Bauwerkstafel'),
('21', 'Schneezeichen'),
('22', 'Ortsdurchfahrtszeichen'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_strausst_punkt_sonst (
	CONSTRAINT pk_wlo_art_strausst_punkt_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_dreiwertige_logik (
	CONSTRAINT pk_wlo_dreiwertige_logik PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_dreiwertige_logik
VALUES
('0', 'unbekannt'),
('1', 'ja'),
('2', 'nein');

CREATE TABLE okstra.wlo_art_strassenausst_strecke (
	CONSTRAINT pk_wlo_art_straussenausst_strecke PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_strassenausst_strecke
VALUES
('01', 'Strecke mit Glättemeldeanlage'),
('02', 'Strecke mit Taumittelsprühanlage'),
('03', 'Strecke mit Verkehrsbeeinflussungsanlage'),
('04', 'Strecke mit Nebelwarnanlage'),
('05', 'Schneefangzaun'),
('06', 'Blendschutz'),
('07', 'Hangsicherung'),
('08', 'Geröllfangzaun'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_strausst_strecke_sonst (
	CONSTRAINT pk_wlo_art_strausst_strecke_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_art_der_aufstellvorrichtung (
	CONSTRAINT pk_wlo_art_der_aufstellvorrichtung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_der_aufstellvorrichtung
VALUES
('00', 'unbekannt'),
('01', 'Rohrpfosten'),
('02', 'Gabelrohrständer'),
('03', 'Kragarm'),
('04', 'Verkehrszeichenbrücke'),
('05', 'Hauswand'),
('07', 'Brücke'),
('08', 'Mast/Straßenlaterne'),
('99', 'sonstiges');

CREATE TABLE okstra.wlo_material_aufstellvorrichtung (
	CONSTRAINT pk_wlo_material_aufstellvorrichtung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_material_aufstellvorrichtung
VALUES
('00', 'unbekannt'),
('01', 'Metall'),
('02', 'Holz'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_schild_ok (
	CONSTRAINT pk_wlo_art_schild_ok PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_schild_ok
VALUES
('00', 'unbekannt'),
('01', 'amtliches Schild'),
('02', 'privates Schild'),
('03', 'militärisches Tragfähigkeitsschild'),
('99', 'sonstiges');

CREATE TABLE okstra.wlo_art_schild_asb (
	CONSTRAINT pk_wlo_art_schild_asb PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_schild_asb
VALUES
('01', 'amtlicher Wegweiser'),
('02', 'amtliches Verkehrszeichen'),
('03', 'nichtamtliches Schild');

CREATE TABLE okstra.wlo_art_schild_nichtamtlich_asb (
	CONSTRAINT pk_wlo_art_schild_nichtamtlich_asb PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_schild_nichtamtlich_asb
VALUES
('00', 'unbekannt'),
('01', 'militärische Tragfähigkeitsschilder'),
('02', 'private Wegweiser'),
('99', 'sonstige');

CREATE TABLE okstra.wlo_lage_schild (
	CONSTRAINT pk_wlo_lage_schild PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lage_schild
VALUES
('01', 'wie Aufstellvorrichtung'),
('02', 'über gesamter Fahrbahn(en)(ein- und zweibahnig)'),
('03', 'über linker Fahrbahn (zweibahnig)'),
('04', 'über rechter Fahrbahn (zweibahnig)');

CREATE TABLE okstra.wlo_strassenbezug_asb (
	CONSTRAINT pk_wlo_strassenbezug_asb PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_strassenbezug_asb
VALUES
('0', 'unbekannt'),
('1', 'aktuelle Straße'),
('2', 'nachgeordnetes Netz');

CREATE TABLE okstra.wlo_befestigung_schild (
	CONSTRAINT pk_wlo_befestigung_schild PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_befestigung_schild
VALUES
('00', 'unbekannt'),
('01', 'Schelle'),
('02', 'Kabelbinder'),
('03', 'Aluminiumnägel'),
('04', 'Stahlnägel'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_beleuchtung_schild (
	CONSTRAINT pk_wlo_beleuchtung_schild PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_beleuchtung_schild
VALUES
('00', 'unbekannt'),
('01', 'ohne Beleuchtung'),
('02', 'außenbeleuchtet'),
('03', 'innenbeleuchtet');

CREATE TABLE okstra.wlo_groessenklasse_vz (
	CONSTRAINT pk_wlo_groessenklasse_vz PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_groessenklasse_vz
VALUES
('00', 'unbekannt'),
('01', 'Klasse 1 (70%)'),
('02', 'Klasse 2 (100%)'),
('03', 'Klasse 3 (140%)');

CREATE TABLE okstra.wlo_einzel_mehrfach_schild (
	CONSTRAINT pk_wlo_einzel_mehrfach_schild PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_einzel_mehrfach_schild
VALUES
('00', 'unbekannt'),
('01', 'Einzelschild'),
('02', 'Bestandteil eines Mehrfachschildes');

CREATE TABLE okstra.wlo_unterhaltungspflicht_schild (
	CONSTRAINT pk_wlo_unterhaltungspflicht_schild PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_unterhaltungspflicht_schild
VALUES
('01', 'Land'),
('02', 'Kreis / kreisfreie Stadt'),
('03', 'Gemeinde'),
('04', 'Straßenbauamt/Niederlassung'),
('05', 'Meisterei'),
('09', 'Sonstige Partner'),
('99', 'noch unbekannt');

CREATE TABLE okstra.wlo_sonstige_unterhaltspflichtige (
	CONSTRAINT pk_wlo_sonstige_unterhaltspflichtige PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_art_aufsatz (
	CONSTRAINT pk_wlo_art_aufsatz PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_aufsatz
VALUES
('00', 'unbekannt'),
('01', 'Pultaufsatz'),
('02', 'Rinnenaufsatz'),
('03', 'Kombiaufsatz'),
('04', 'Seitenablauf'),
('05', 'Bergeinlauf');

CREATE TABLE okstra.wlo_art_unterteil (
	CONSTRAINT pk_wlo_art_unterteil PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_unterteil
VALUES
('00', 'unbekannt'),
('01', 'Unterteil für Trockenschlamm'),
('02', 'Unterteil für Nassschlamm'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_unterteil_sonst (
	CONSTRAINT pk_wlo_art_unterteil_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_unterhaltungspflicht (
	CONSTRAINT pk_wlo_unterhaltungspflicht PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_unterhaltungspflicht
VALUES
('00', 'unbekannt'),
('01', 'Land'),
('02', 'Kreis / kreisfreie Stadt'),
('03', 'Gemeinde'),
('04', 'Straßenbauamt/Niederlassung'),
('05', 'Meisterei'),
('09', 'Sonstige Partner'),
('10', 'keine Unterhaltungspflicht');

CREATE TABLE okstra.wlo_typ_abfallentsorgung (
	CONSTRAINT pk_wlo_typ_abfallentsorgung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_typ_abfallentsorgung
VALUES
('01', 'Abfallbehälter auf der Anlage des ruhenden Verkehrs, ohne Spezifizierung'),
('02', 'Behälter'),
('03', 'Behälter mit Aschenbecher'),
('06', 'Abfallcontainer');

CREATE TABLE okstra.wlo_art_abfall (
	CONSTRAINT pk_wlo_art_abfall PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_abfall
VALUES
('00', 'unbekannt'),
('01', 'Restmüll/Reiseabfall'),
('02', 'Wertstoff'),
('03', 'Papier'),
('04', 'Glas'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_lagetyp_abfallentsorgung (
	CONSTRAINT pk_wlo_lagetyp_abfallentsorgung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lagetyp_abfallentsorgung
VALUES
('00', 'unbekannt'),
('01', 'unterirdisch'),
('02', 'oberirdisch'),
('03', 'Sonstige');

CREATE TABLE okstra.wlo_material_abfallentsorgung (
	CONSTRAINT pk_wlo_material_abfallentsorgung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_material_abfallentsorgung
VALUES
('00', 'unbekannt'),
('01', 'Kunststoff'),
('02', 'Recycling'),
('03', 'Holz'),
('04', 'Stein'),
('05', 'Beton'),
('06', 'Stahlblech'),
('07', 'Stahl'),
('08', 'Verzinkter / beschichteter Draht'),
('09', 'Metallgitter'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_schacht (
	CONSTRAINT pk_wlo_art_schacht PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_schacht
VALUES
('00', 'unbekannt'),
('01', 'Prüfschacht'),
('02', 'Ablaufschacht'),
('03', 'Absturzschacht'),
('04', 'Absetzschacht'),
('05', 'Sickerschacht'),
('99', 'sonstiges');

CREATE TABLE okstra.wlo_lage_schacht_strassenablauf (
	CONSTRAINT pk_wlo_lage_schacht_strassenablauf PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lage_schacht_strassenablauf
VALUES
('00', 'unbekannt'),
('01', 'linker Fahrbahnrand (einbahnig)'),
('02', 'linke Fahrbahn, linker Fahrbahnrand (zweibahnig)'),
('04', 'linke Fahrbahn, rechter Fahrbahnrand (zweibahnig)'),
('05', 'Mitte/Bestandsachse'),
('06', 'rechte Fahrbahn, linker Fahrbahnrand (zweibahnig)'),
('08', 'rechte Fahrbahn, rechter Fahrbahnrand (zweibahnig)'),
('09', 'rechter Fahrbahnrand (einbahnig)'),
('10', 'Hauptfahrstreifen gegen Stat.-Richtung'),
('11', 'Hauptfahrstreifen gegen Stat.-Richtung, links'),
('12', 'Hauptfahrstreifen gegen Stat.-Richtung, Mitte'),
('13', 'Hauptfahrstreifen gegen Stat.-Richtung, rechts'),
('20', 'Hauptfahrstreifen in Stat.-Richtung'),
('21', 'Hauptfahrstreifen in Stat.-Richtung, rechts'),
('22', 'Hauptfahrstreifen in Stat.-Richtung, Mitte'),
('23', 'Hauptfahrstreifen in Stat.-Richtung, links'),
('30', '1. Überholstreifen gegen Stat.-Richtung'),
('31', '1. Überholstreifen gegen Stat.-Richtung, links'),
('32', '1. Überholstreifen gegen Stat.-Richtung, Mitte'),
('33', '1. Überholstreifen gegen Stat.-Richtung, rechts'),
('40', '1. Überholstreifen in Stat.-Richtung'),
('41', '1. Überholstreifen in Stat.-Richtung, rechts'),
('42', '1. Überholstreifen in Stat.-Richtung, Mitte'),
('43', '1. Überholstreifen in Stat.-Richtung, links'),
('50', '2. Überholstreifen gegen Stat.-Richtung'),
('51', '2. Überholstreifen gegen Stat.-Richtung, links'),
('52', '2. Überholstreifen gegen Stat.-Richtung, Mitte'),
('53', '2. Überholstreifen gegen Stat.-Richtung, rechts'),
('60', '2. Überholstreifen in Stat.-Richtung'),
('61', '2. Überholstreifen in Stat.-Richtung, rechts'),
('62', '2. Überholstreifen in Stat.-Richtung, Mitte'),
('63', '2. Überholstreifen in Stat.-Richtung, links'),
('70', '3. Überholstreifen gegen Stat.-Richtung'),
('71', '3. Überholstreifen gegen Stat.-Richtung, links'),
('72', '3. Überholstreifen gegen Stat.-Richtung, Mitte'),
('73', '3. Überholstreifen gegen Stat.-Richtung, rechts'),
('80', '3. Überholstreifen in Stat.-Richtung'),
('81', '3. Überholstreifen in Stat.-Richtung, rechts'),
('82', '3. Überholstreifen in Stat.-Richtung, Mitte'),
('83', '3. Überholstreifen in Stat.-Richtung, links'),
('91', 'befestigter Seitenstreifen links'),
('92', 'befestigter Seitenstreifen rechts'),
('95', 'links außerhalb'),
('96', 'rechts außerhalb');

CREATE TABLE okstra.wlo_angaben_zum_konus (
	CONSTRAINT pk_wlo_angaben_zum_konus PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_angaben_zum_konus
VALUES
('00', 'unbekannt'),
('01', 'flach'),
('02', 'hoch');

CREATE TABLE okstra.wlo_lage_durchlass (
	CONSTRAINT pk_wlo_lage_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lage_durchlass
VALUES
('00', 'unbekannt'),
('01', 'links, längs'),
('02', 'links, quer (andere Streifen)'),
('03', 'unter linker Fahrbahn'),
('04', 'unter beiden Fahrbahnen'),
('05', 'unter rechter Fahrbahn'),
('06', 'rechts, quer (andere Streifen)'),
('07', 'rechts, längs'),
('08', 'Mitte längs'),
('09', 'unter einbahniger Fahrbahn');

CREATE TABLE okstra.wlo_profil_durchlass (
	CONSTRAINT pk_wlo_profil_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_profil_durchlass
VALUES
('00', 'unbekannt'),
('01', 'Rechteck'),
('02', 'Rechteck mit Gewölbe'),
('03', 'Kreis'),
('04', 'Ei'),
('05', 'Fünfeck (Rinne mit Rechteck)'),
('06', 'Maul-/Haubenquerschnitt'),
('07', 'Mehrfachrechteck'),
('08', 'Mehrfachkreis'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_material_durchlass (
	CONSTRAINT pk_wlo_material_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_material_durchlass
VALUES
('00', 'unbekannt'),
('01', 'Holz'),
('02', 'Beton'),
('03', 'Mauerwerk'),
('04', 'Stahl/Metall'),
('05', 'Kunststoff'),
('06', 'Steinzeug'),
('07', 'Natursteinmauerwerk'),
('08', 'Ton'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_funktion_durchlass (
	CONSTRAINT pk_wlo_funktion_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_funktion_durchlass
VALUES
('00', 'unbekannt'),
('01', 'Gewässer 2. Ordnung'),
('02', 'Grundstücksentwässerung (fremd)'),
('03', 'Straßenentwässerung'),
('97', 'verschüttet'),
('98', 'verpresst');

CREATE TABLE okstra.wlo_zustand_durchlass (
	CONSTRAINT pk_wlo_zustand_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_zustand_durchlass
VALUES
('01', 'gut'),
('02', 'mittel'),
('03', 'schlecht');

CREATE TABLE okstra.wlo_schutzeinrichtung_durchlass (
	CONSTRAINT pk_wlo_schutzeinrichtung_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_schutzeinrichtung_durchlass
VALUES
('00', 'unbekannt'),
('01', 'Schutzplanke'),
('02', 'Geländer'),
('03', 'Mauer/Brüstung');

CREATE TABLE okstra.wlo_stadium_durchlass (
	CONSTRAINT pk_wlo_stadium_durchlass PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_stadium_durchlass
VALUES
('00', 'unbekannt'),
('01', 'in Betrieb'),
('02', 'nicht in Betrieb');

CREATE TABLE okstra.wlo_lage_leitung (
	CONSTRAINT pk_wlo_lage_leitung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_lage_leitung
VALUES
('00', 'unbekannt'),
('01', 'links, längs'),
('03', 'unter linker Fahrbahn'),
('04', 'unter beiden Fahrbahnen'),
('05', 'unter rechter Fahrbahn'),
('07', 'rechts, längs'),
('08', 'Mitte längs'),
('09', 'unter einbahniger Fahrbahn'),
('91', 'befestigter Seitenstreifen links'),
('92', 'befestigter Seitenstreifen rechts');

CREATE TABLE okstra.wlo_art_leitung (
	CONSTRAINT pk_wlo_art_leitung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_leitung
VALUES
('00', 'unbekannt'),
('01', 'Elektrizität'),
('02', 'Gas'),
('03', 'Wasser'),
('04', 'Abwasser'),
('05', 'Telekommunikation'),
('06', 'Fernwärme'),
('07', 'Öl'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_art_leitung_detail (
	CONSTRAINT pk_wlo_art_leitung_detail PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_leitung_detail
VALUES
('0101', 'Elektrizität Niedrigspannung'),
('0102', 'Elektrizität Mittelspannung'),
('0103', 'Elektrizität Hochspannung'),
('0104', 'Elektrizität unbekannter Spannung'),
('0201', 'Erdgas (Hochdruck)'),
('0202', 'Erdgas (Mitteldruck)'),
('0301', 'Trinkwasser / Brauchwasser'),
('0401', 'Schmutzwasser (Gefälle)'),
('0402', 'Schmutzwasser (Druck)'),
('0403', 'Regenwasser / Niederschlagwasser'),
('0404', 'Mischwasser'),
('0501', 'TV Breitband'),
('0502', 'TV Freileitung'),
('0503', 'Fernmeldekabel'),
('0701', 'Mineralöl');

CREATE TABLE okstra.wlo_material_leitung (
	CONSTRAINT pk_wlo_material_leitung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_material_leitung
VALUES
('00', 'unbekannt'),
('01', 'PVC (Polyvinylchlorid)'),
('02', 'PE (Polyethylen)'),
('03', 'GFK (glasfaserverstärkte Kunststoffe)'),
('04', 'Stahl'),
('05', 'Grauguss'),
('06', 'Asbestzement'),
('07', 'Steinzeug'),
('08', 'Beton'),
('09', 'GGG (Duktiles Gussrohr)'),
('10', 'LWL (Lichtwellenleiter)'),
('11', 'KG (Kanalgrundrohr-PVC)');

CREATE TABLE okstra.wlo_material_schutzrohr (
	CONSTRAINT pk_wlo_material_schutzrohr PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_material_schutzrohr
VALUES
('00', 'unbekannt'),
('01', 'PVC Polyvinylchlorid (schwer entflammbar)'),
('02', 'PE Polyethylen'),
('03', 'Stahl'),
('04', 'Steinzeug'),
('05', 'HDPE Polyethylen (sehr dicht)');

CREATE TABLE okstra.wlo_betreiber_leitung (
	CONSTRAINT pk_wlo_betreiber_leitung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_streifenart (
	CONSTRAINT pk_wlo_streifenart PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_streifenart
VALUES
('100', 'Fahrbahn'),
('110', 'Hauptfahrstreifen (HFS)'),
('111', '1. Überholstreifen (UE1)'),
('112', '2. Überholstreifen (UE2)'),
('113', '3. Überholstreifen (UE3)'),
('114', 'Zusatzfahrstreifen (ZFS)'),
('115', 'Sonderfahrstreifen'),
('116', 'Rechtsabbiegefahrstreifen'),
('117', 'Linksabbiegefahrstreifen'),
('120', 'offene Rinne'),
('121', 'Kastenrinne'),
('122', 'Schlitzrinne'),
('130', 'Beschleunigungsstreifen'),
('131', 'Verzögerungsstreifen'),
('132', 'Verflechtungsstreifen'),
('135', 'Bedarfsfahrstreifen im Kreisverkehr'),
('140', 'Fahrbahnteil, der dem Schienenverkehr vorbehalten ist'),
('160', 'Mehrzweckstreifen'),
('161', 'Mehrzweckstreifen ohne Fahrradbenutzung'),
('162', 'Mehrzweckstreifen mit Fahrradbenutzung'),
('170', 'Standstreifen, Parkstreifen (nicht Parkplatz)'),
('171', 'Seitenstreifen, befestigt'),
('172', 'Seitenstreifen, befestigt, temporär als Fahrstreifen genutzt'),
('174', 'Haltebucht allgemein'),
('175', 'Haltebucht'),
('176', 'Bushaltebucht'),
('177', 'Nothaltebucht'),
('180', 'Parkstreifen (nicht Parkplatz)'),
('181', 'Parkstreifen mit Grünflächen zwischen den Parkfeldern'),
('210', 'Gehweg'),
('220', 'paralleler Wirtschaftsweg'),
('230', 'sonstiger paralleler Weg ohne Kfz-Verkehr'),
('240', 'Radweg'),
('241', 'Radweg'),
('242', 'anderer Radweg'),
('243', 'Radfahrstreifen'),
('250', 'Rad- und Gehweg'),
('251', 'Gemeinsamer Rad- und Gehweg'),
('300', 'unbefestigter Seitenstreifen (Bankett), ebenes Gelände'),
('301', 'Bankett'),
('302', 'Seitenstreifen, unbefestigt; ebenes Gelände'),
('310', 'unbefestigter Trennstreifen (z.B. Mittel-, Schutzstreifen)'),
('311', 'Mittelstreifen'),
('312', 'Mittelstreifenüberfahrt'),
('313', 'Seitentrennstreifen'),
('314', 'Verkehrsinsel/Querungshilfe '),
('315', 'Haltestelleninsel'),
('320', 'befestigter Trennstreifen'),
('330', 'Trennschwelle (Trennbord), Trennplanke, Trennbauwerk'),
('340', 'eigener Gleiskörper'),
('400', 'Randstreifen (Leitstreifen), konstruktiv von der Fahrbahn getrennt'),
('410', 'Randstreifen (Leitstreifen), nicht konstruktiv von der Fahrbahn getrennt'),
('420', 'Markierungs- und Sperrfläche'),
('430', 'Markierte Doppeltrennlinie'),
('500', 'offene Vollrinne (Regelform)'),
('510', 'Rasenmulde, befestigte Mulde'),
('511', 'Mulde'),
('520', 'Straßengraben'),
('600', 'Kantenstein (Rabattenstein)'),
('610', 'Tiefbord (Flachbord)'),
('620', 'Schrägbord'),
('630', 'Hochbord (Steilbord), Hohlbord'),
('640', 'Bordstein allgemein'),
('700', 'Dammböschung (abfallendes Gelände)'),
('701', 'Steinschlag auslösende Hänge (Dammlage)'),
('710', 'Einschnittböschung (ansteigendes Gelände)'),
('711', 'Steinschlag auslösende Hänge (Einschnitt)'),
('715', 'Sichtflächen an Kreuzungsbereichen'),
('720', 'Sonstiger Querschnittstreifen im Seitenraum'),
('730', 'Anliegerflächen (Flächen Dritter)'),
('750', 'Kreisinsel'),
('751', 'Baumscheibe'),
('999', 'sonstige Streifenart');

CREATE TABLE okstra.wlo_streifenart_sonst (
	CONSTRAINT pk_wlo_streifenart_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_anzahl_gleise_laengs (
	CONSTRAINT pk_wlo_anzahl_gleise_laengs PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_anzahl_gleise_laengs
VALUES
('0', 'unbekannt'),
('1', 'ein Gleis'),
('2', 'zwei Gleise'),
('3', 'drei oder mehr Gleise');

CREATE TABLE okstra.wlo_art_der_oberflaeche (
	CONSTRAINT pk_wlo_art_der_oberflaeche PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_der_oberflaeche
VALUES
('00', 'unbekannt'),
('01', 'Grasfläche mit Intensivpflege'),
('02', 'Grasfläche mit Extensivpflege'),
('03', 'Grasfläche, Pflege nicht spezifiziert'),
('04', 'Gehölz mit Intensivpflege'),
('05', 'Gehölz mit Extensivpflege'),
('06', 'Gehölz, Pflege nicht spezifiziert'),
('11', 'versiegelt'),
('12', 'befestigt, unversiegelt');

CREATE TABLE okstra.wlo_art_part_baulasttraeger (
	CONSTRAINT pk_wlo_art_part_baulasttraeger PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_part_baulasttraeger
VALUES
('00', 'unbekannt'),
('01', 'Land'),
('02', 'Kreis / kreisfreie Stadt'),
('03', 'Gemeinde'),
('09', 'Dritter'),
('10', 'keine Unterhaltungspflicht');

CREATE TABLE okstra.wlo_sonstiger_ui_partner_land (
	CONSTRAINT pk_wlo_sonstiger_ui_partner_land PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_standort_rueckhaltesystem (
	CONSTRAINT pk_wlo_standort_rueckhaltesystem PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_standort_rueckhaltesystem
VALUES
('00', 'unbekannt'),
('01', 'neben Fahrbahn'),
('02', 'im Mittelstreifen'),
('03', 'neben Notrufsäule'),
('04', 'neben seitlichem Hindernis'),
('05', 'neben Schilderbrücke'),
('06', 'vor Brücke'),
('07', 'auf Brücke'),
('08', 'im Bereich von Lärmschutzwand'),
('09', 'auf Trenninsel'),
('10', 'im Bereich eines Dammes'),
('11', 'im Bereich einer Absenkung/Einschnittes'),
('12', 'auf Stützmauer'),
('13', 'im Bereich eines Gewässers'),
('14', 'neben Fußgängerweg / Fußgängerpfad'),
('15', 'neben Radweg'),
('16', 'neben untergeordnetem Verkehrsweg'),
('17', 'vor Einzelbaum / Einzelbäumen');

CREATE TABLE okstra.wlo_modulbezeichnung_schutzeinr_stahl (
	CONSTRAINT pk_wlo_modulbezeichnung_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_modulbezeichnung_schutzeinr_stahl
VALUES
('M01', 'einfache Schutzplanke (ESP)'),
('M02', 'einfache Distanzschutzplanke (EDSP)'),
('M03', 'Super-Rail Eco/light'),
('M04', 'Super-Rail'),
('M05a', 'Mega Rail sl'),
('M05b', 'Mega Rail s'),
('M07', 'Easy Rail'),
('A01', 'doppelte Schutzplanke'),
('A02', 'doppelte Distanzschutzplanke'),
('A03', 'Absturzsicherung Safety Rail'),
('A04', 'kurze Schutzplanke'),
('99', 'sonstige');

CREATE TABLE okstra.wlo_systemname_schutzeinr_stahl (
	CONSTRAINT pk_wlo_systemname_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_holmform_schutzeinr_stahl (
	CONSTRAINT pk_wlo_holmform_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_holmform_schutzeinr_stahl
VALUES
('00', 'unbekannt'),
('01', 'Profil A'),
('02', 'Profil B'),
('03', 'sonstige Konstruktion');

CREATE TABLE okstra.wlo_pfostenform_schutzeinr_stahl (
	CONSTRAINT pk_wlo_pfostenform_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_pfostenform_schutzeinr_stahl
VALUES
('00', 'unbekannt'),
('01', 'Sigma 100 - Pfosten'),
('02', 'IPE 100 - Pfosten'),
('03', 'sonstige Konstruktion');

CREATE TABLE okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl (
	CONSTRAINT pk_wlo_art_pfostenbefestigung_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl
VALUES
('00', 'unbekannt'),
('01', 'gerammt'),
('02', 'geschraubt'),
('03', 'gesteckt');

CREATE TABLE okstra.wlo_art_aek_schutzeinr_stahl (
	CONSTRAINT pk_wlo_art_aek_schutzeinr_stahl PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

-- Codelists that were not originally in the infrastructure tables, but can be found in the datenmodell.sql
CREATE TABLE okstra.wlo_art_aufbauschicht (
	CONSTRAINT pk_wlo_art_aufbauschicht PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_aufbauschicht
VALUES
('0', 'unbekannt'),
('1', 'Untergrund / Unterbau'),
('2', 'Ungebundene Schicht des Oberbaues'),
('3', 'Schicht mit bitumenhaltigem Bindemittel'),
('4', 'Schicht mit pechhaltigem Bindemittel'),
('5', 'Schicht mit hydraulischem Bindemittel'),
('6', 'Gebundene Schicht mit sonstigem Bindemittel'),
('7', 'Pflaster'),
('8', 'Platten'),
('9', 'Sonstige Schichten');

CREATE TABLE okstra.wlo_material_aufbauschicht (
	CONSTRAINT pk_wlo_material_aufbauschicht PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_herkunft_angaben_aufbau (
	CONSTRAINT pk_wlo_herkunft_angaben_aufbau PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_herkunft_angaben_aufbau
VALUES
('00', 'unbekannt'),
('01', 'aus Bauunterlagen'),
('02', 'von Straßenunterhaltungspersonal'),
('03', 'aus örtlichen Erfassungsblättern der bisherigen Straßenbestandsaufnahme'),
('04', 'aus Straßenbüchern'),
('05', 'örtlich erfasste Daten (z.B. Bohrkerne, Aufbrüche)'),
('06', 'aus Eignungsprüfung'),
('07', 'Georadar in Verbindung mit Bohrkern'),
('08', 'von Bauüberwacher');

-- Doesn't inherit from Werteliste due to different column-structure
CREATE TABLE okstra.wlo_detail_a_aufbauschicht (
	kennung character varying NOT NULL,
	bedeutung character varying,
	langtext character varying,
	CONSTRAINT pk_wlo_detail_a_aufbauschicht PRIMARY KEY (kennung)
) ;
-- Open Codelist

-- Doesn't inherit from Werteliste due to different column-structure
CREATE TABLE okstra.wlo_detail_b_aufbauschicht (
	kennung character varying NOT NULL,
	bedeutung character varying,
	langtext character varying,
	CONSTRAINT pk_wlo_detail_b_aufbauschicht PRIMARY KEY (kennung)
);
-- Open Codelist

-- Doesn't inherit from Werteliste due to different column-structure
CREATE TABLE okstra.wlo_detail_c_aufbauschicht (
	kennung character varying NOT NULL,
	bedeutung character varying,
	langtext character varying,
	CONSTRAINT pk_wlo_detail_c_aufbauschicht PRIMARY KEY (kennung)
);
-- Open Codelist

-- Doesn't inherit from Werteliste due to different column-structure
CREATE TABLE okstra.wlo_detail_d_aufbauschicht (
	kennung character varying NOT NULL,
	bedeutung character varying,
	langtext character varying,
	CONSTRAINT pk_wlo_detail_d_aufbauschicht PRIMARY KEY (kennung)
);
-- Open Codelist

CREATE TABLE okstra.wlo_bindemittel_aufbauschicht (
	CONSTRAINT pk_wlo_bindemittel_aufbauschicht PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_kennzeichen_bahnigkeit (
	CONSTRAINT pk_wlo_kennzeichen_bahnigkeit PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_kennzeichen_bahnigkeit
VALUES
('0', 'unbekannt'),
('1', 'einbahnig, Straße mit/ohne Gegenverkehr'),
('2', 'zweibahnig, Straße mit baulich getrennten Richtungsfahrbahnen');

CREATE TABLE okstra.wlo_art_belastungsklasse (
	CONSTRAINT pk_wlo_art_belastungsklasse PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_belastungsklasse
VALUES
('01', 'Soll-Belastungsklasse'),
('02', 'Ist-Belastungsklasse');

CREATE TABLE okstra.wlo_belastungsklasse_rsto (
	CONSTRAINT pk_wlo_belastungsklasse_rsto PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_belastungsklasse_rsto
VALUES
('00', 'unbekannt'),
('01', 'Bk 32'),
('02', 'Bk 10'),
('03', 'Bk 3,2'),
('04', 'Bk 1,8'),
('05', 'Bk 1,0'),
('06', 'Bk 0,3'),
('07', 'Bk 100'),
('98', 'sonstige Belastungsklasse'),
('99', 'keine Zuordnung möglich');

CREATE TABLE okstra.wlo_belastungsklasse_sonst (
	CONSTRAINT pk_wlo_belastungsklasse_sonst PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- Open Codelist

CREATE TABLE okstra.wlo_verkehrsrichtung (
	CONSTRAINT pk_wlo_verkehrsrichtung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_verkehrsrichtung
VALUES
('B', 'Verkehr in beiden Richtungen'),
('R', 'Einbahnverkehr in Stationierungsrichtung'),
('G', 'Einbahnverkehr gegen Stationierungsrichtung');

CREATE TABLE okstra.wlo_fahrzeugart (
	CONSTRAINT pk_wlo_fahrzeugart PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_fahrzeugart
VALUES
('nk Kfz', 'nicht klassifizierbare Fahrzeuge (Sonstige)'),
('Krad', 'Motorräder'),
('Pkw(grund)', 'Pkw'),
('Lfw', 'Lieferwagen'),
('Pkw', 'Krad + Pkw(grund) + Lfw'),
('PkwÄ', 'Pkw + nk Kfz'),
('PkwA', 'Pkw und Lfw mit Anhänger'),
('Lkw', 'Lkw mit einem zulässigen Gesamtgewicht von mehr als 3,5 t'),
('LkwA(grund)', 'Lkw mit Anhänger'),
('Sattel-Kfz', 'Sattelkraftfahrzeuge'),
('LkwA', 'LkwA(grund) + Sattel-Kfz'),
('Bus', 'Busse mit mehr als 9 Sitzplätzen'),
('LkwÄ', 'PkwA + Lkw + LkwA + Bus'),
('Kfz', 'PkwÄ + LkwÄ');

CREATE TABLE okstra.wlo_tab_funktion (
	CONSTRAINT pk_wlo_tab_funktion PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_tab_funktion
VALUES
('01', 'Ausfahrt'),
('02', 'Einfahrt'),
('03', 'Parallelfahrbahn (baulich getrennt)'),
('04', 'Verflechtungsspur'),
('05', 'Verzögerungsspur'),
('06', 'Beschleunigungsspur');

CREATE TABLE okstra.wlo_art_komplexer_knoten (
	CONSTRAINT pk_wlo_art_komplexer_knoten PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_komplexer_knoten
VALUES
('1', 'plangleicher Knoten'),
('2', 'planfreier Knoten'),
('3', 'teilplanfreier Knoten'),
('4', 'Kreisverkehr');

CREATE TABLE okstra.wlo_organisationsart (
	CONSTRAINT pk_wlo_organisationsart PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_organisationsart
VALUES
('1', 'Bundesministerium'),
('2', 'Landesministerium'),
('3', 'Landesverwaltung'),
('4', 'Landesbetrieb'),
('5', 'Regierungspräsidium'),
('6', 'Kreisverwaltung'),
('7', 'Stadtverwaltung'),
('8', 'Bezirksverwaltung'),
('9', 'Straßen- oder Autobahnmeisterei'),
('50', 'AG'),
('51', 'GmbH'),
('52', 'GmbH & Co. KG'),
('99', 'Sonstiges');

CREATE TABLE okstra.wlo_kommunikationstyp (
	CONSTRAINT pk_wlo_kommunikationstyp PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_kommunikationstyp
VALUES
('1', 'Telefonnummer'),
('2', 'Faxnummer'),
('3', 'Mobiltelefonnummer'),
('4', 'Emailadresse'),
('9', 'Sonstiges');

CREATE TABLE okstra.wlo_dienstlich_privat (
	CONSTRAINT pk_wlo_dienstlich_privat PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_dienstlich_privat
VALUES
('1', 'dienstlich'),
('2', 'privat');

CREATE TABLE okstra.wlo_anschriftstyp (
	CONSTRAINT pk_wlo_anschriftstyp PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_anschriftstyp
VALUES
('1', 'Postadresse'),
('2', 'Büroadresse');

CREATE TABLE okstra.wlo_personenklasse (
	CONSTRAINT pk_wlo_personenklasse PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_personenklasse
VALUES
('?', 'unbekannt'),
('G', 'Gemeindeverwaltung'),
('J', 'juristische Person'),
('L', 'Landwirtschaftsamt'),
('N', 'natürliche Person'),
('Ö', 'öffentlicher Bedarfsträger'),
('V', 'verstorben');

CREATE TABLE okstra.wlo_tab_stadium (
	CONSTRAINT pk_wlo_tab_stadium PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_tab_stadium
VALUES
('000', 'unbekannt'),
('VP', 'Vorplanung hat begonnen'),
('UVA', 'Umweltverträglichkeitsstudie bzw. Variantenuntersuchung hat begonnen'),
('UVE', 'Umweltverträglichkeitsstudie bzw. Variantenuntersuchung ist abgeschlossen'),
('LBV', 'Unterlagen für Linienbestimmung/Trassenfestlegung werden aufgestellt'),
('LBE', 'Linie bestimmt/Trassenführung festgelegt'),
('VE', 'Vorentwurf hat begonnen'),
('VEG', 'Vorentwurf genehmigt'),
('PA', 'Planfeststellungsverfahren beantragt'),
('PB', 'Planfeststellungsbeschluss ergangen'),
('PU', 'Planfeststellungsbeschluss bestandskräftig'),
('BAU', 'Durchführung der Bauarbeiten begonnen'),
('VFV', 'Verkehrsfreigabe der Gesamtstrecke der Verkehrseinheit ist erfolgt'),
('EPL', 'Erneuerung/Ersatzneubau in Planung'),
('EAU', 'Erneuerung/Ersatzneubau in Ausführung'),
('IPL', 'Instandsetzung in Planung'),
('IAU', 'Instandsetzung in Ausführung');

CREATE TABLE okstra.wlo_verkehrsrichtung_se (
	CONSTRAINT pk_wlo_verkehrsrichtung_se PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_verkehrsrichtung_se
VALUES
('R', 'Verkehrsrichtung von Von-VP nach Nach-VP'),
('G', 'Verkehrsrichtung von Nach-VP nach Von-VP'),
('B', 'In beiden Richtungen'),
('K', 'In keiner Richtung');

CREATE TABLE okstra.wlo_stufe_strassenelement (
	CONSTRAINT pk_wlo_stufe_strassenelement PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_stufe_strassenelement
VALUES
('1', 'Hauptverbindung'),
('2', 'Nebenverbindung');

CREATE TABLE okstra.wlo_verkehrsteilnehmergruppe (
	CONSTRAINT pk_wlo_verkehrsteilnehmergruppe PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_verkehrsteilnehmergruppe
VALUES
('01', 'alle Kraftfahrzeuge'),
('02', 'alle Fahrzeuge'),
('03', 'Lkw'),
('04', 'Pkw'),
('05', 'Krafträder'),
('06', 'Kraftomnibusse'),
('07', 'Radfahrer'),
('08', 'Gefahrguttransport'),
('09', 'Fußgänger'),
('10', 'Straßenbahn'),
('11', 'Taxi'),
('99', 'Sonstige');

CREATE TABLE okstra.wlo_querschnitt_streifenart_ves (
	CONSTRAINT pk_wlo_querschnitt_streifenart_ves PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_querschnitt_streifenart_ves
VALUES
('110', 'Hauptfahrstreifen (HFS)'),
('111', '1. Überholstreifen (UE1)'),
('112', '2. Überholstreifen (UE2)'),
('113', '3. Überholstreifen (UE3)'),
('114', 'Zusatzfahrstreifen (ZFS)'),
('115', 'Sonderfahrstreifen (z. B. Busse)');

CREATE TABLE okstra.wlo_art_ves (
	CONSTRAINT pk_wlo_art_ves PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_art_ves
VALUES
('00', 'unbekannt'),
('01', 'Geschwindigkeitsbeschränkung'),
('02', 'Durchfahrtsverbot'),
('03', 'Maximale Achslast'),
('04', 'Maximales Gesamtgewicht'),
('05', 'Maßbeschränkung in der Höhe'),
('06', 'Maßbeschränkung in der Breite'),
('07', 'Maßbeschränkung in der Länge'),
('08', 'Überholverbot'),
('09', 'Mindestgeschwindigkeit'),
('99', 'Sonstige Verbote (z.B. Halteverbot)');

CREATE TABLE okstra.wlo_bezugsrichtung (
	CONSTRAINT pk_wlo_bezugsrichtung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_bezugsrichtung
VALUES
('0', 'unbekannt'),
('B', 'beide Richtungen'),
('R', 'in Stationierungsrichtung'),
('G', 'gegen Stationierungsrichtung');

CREATE TABLE okstra.wlo_gueltigkeit_ves (
	CONSTRAINT pk_wlo_gueltigkeit_ves PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_gueltigkeit_ves
VALUES
('01', 'permanent'),
('02', 'bei Nässe'),
('03', 'Eis'),
('04', 'bei Dunkelheit'),
('05', 'Zeitangabe'),
('06', 'Verbotsstrecke'),
('07', 'VBA'),
('08', 'bei Bedarf (verdeckbar)'),
('99', 'sonstiges');

CREATE TABLE okstra.wlo_orientierungsrichtung (
	CONSTRAINT pk_wlo_orientierungsrichtung PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_orientierungsrichtung
VALUES
('R', 'in Definitionsrichtung'),
('G', 'gegen Definitionsrichtung'),
('B', 'beide Richtungen');

CREATE TABLE okstra.wlo_art_zustaendigkeit (
	CONSTRAINT pk_wlo_art_zustaendigkeit PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
-- open codelist

CREATE TABLE okstra.wlo_wochentag_ves (
	CONSTRAINT pk_wlo_wochentag_ves PRIMARY KEY (kennung)
) INHERITS (base.werteliste);
INSERT INTO okstra.wlo_wochentag_ves
VALUES
('00', 'permanent'),
('01', 'Werktags'),
('02', 'Montag bis Freitag'),
('03', 'Sonn- und Feiertags'),
('04', 'Samstag und Sonntag'),
('99', 'sonstiges');

---------------------------------------------------------------
-- CODELISTEN DOPPIK
---------------------------------------------------------------
CREATE TABLE base.wld_klassifizierung (
	CONSTRAINT pk_wld_klassifizierung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);
INSERT INTO base.wld_klassifizierung (id, langtext) VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt');

CREATE TABLE base.wld_nutzung (
	CONSTRAINT pk_wld_nutzung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);
INSERT INTO base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('1', '', 'Autobahn');
INSERT INTO base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('2', '54401', 'Bundesstraße');
INSERT INTO base.wld_nutzung (ident_hist, kurztext, langtext)
 VALUES ('5', '54301', 'Landesstraße');

CREATE TABLE base.wld_strassennetzlage
(
	CONSTRAINT pk_wld_strassennetzlage PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);
INSERT INTO base.wld_strassennetzlage (id, langtext) VALUES ('00000000-0000-0000-0000-000000000000', 'unbekannt');

CREATE TABLE base.wld_bauklasse (
	CONSTRAINT pk_wld_bauklasse PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_baulasttraeger (
	CONSTRAINT pk_wld_baulasttraeger PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_baumart (
	CONSTRAINT pk_wld_baumart PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_deckschicht (
	CONSTRAINT pk_wld_deckschicht PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_eigentuemer (
	CONSTRAINT pk_wld_eigentuemer PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_fertigstellung (
	CONSTRAINT pk_wld_fertigstellung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_material (
	CONSTRAINT pk_wld_material PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_objektbezeichnung (
	CONSTRAINT pk_wld_objektbezeichnung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_preisermittlung (
	CONSTRAINT pk_wld_preisermittlung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_zustand (
	CONSTRAINT pk_wld_zustand PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_zustandsbewertung (
	CONSTRAINT pk_wld_zustandsbewertung PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

CREATE TABLE base.wld_stvonr (
	CONSTRAINT pk_wld_stvonr PRIMARY KEY (id)
)
INHERITS (base.basiscodeliste);

---------------------------------------------------------------
-- KATASTER
---------------------------------------------------------------
CREATE TABLE kataster.kreis (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
    schluessel character(5) NOT NULL DEFAULT '00000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_kreis PRIMARY KEY (id),
	CONSTRAINT uk1_kreis UNIQUE (schluessel)
)
WITH (
	OIDS=TRUE
);
INSERT INTO kataster.kreis (id) VALUES ('00000000-0000-0000-0000-000000000000');

CREATE TABLE kataster.gemeindeverband (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_kreis character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
    schluessel character(4) NOT NULL DEFAULT '0000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_gemeindeverband PRIMARY KEY (id),
	CONSTRAINT fk_gemeindeverband_kreis FOREIGN KEY (id_kreis)
		REFERENCES kataster.kreis (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT uk1_gemeindeverband UNIQUE (id_kreis, schluessel)
)
WITH (
	OIDS=TRUE
);
INSERT INTO kataster.gemeindeverband (id) VALUES ('00000000-0000-0000-0000-000000000000');

CREATE TABLE kataster.gemeinde (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_gemeindeverband character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
    schluessel character(3) NOT NULL DEFAULT '000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_gemeinde PRIMARY KEY (id),
	CONSTRAINT fk_gemeinde_gemeindeverband FOREIGN KEY (id_gemeindeverband)
		REFERENCES kataster.gemeindeverband (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT uk1_gemeinde UNIQUE (id_gemeindeverband, schluessel)
)
WITH (
	OIDS=TRUE
);
INSERT INTO kataster.gemeinde (id) VALUES ('00000000-0000-0000-0000-000000000000');

CREATE TABLE kataster.gemeindeteil (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_gemeinde character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
    schluessel character(4) NOT NULL DEFAULT '0000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_gemeindeteil PRIMARY KEY (id),
	CONSTRAINT fk_gemeindeteil_gemeinde FOREIGN KEY (id_gemeinde)
		REFERENCES kataster.gemeinde (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT uk1_gemeindeteil UNIQUE (id_gemeinde, schluessel)
)
WITH (
	OIDS=TRUE
);
INSERT INTO kataster.gemeindeteil (id) VALUES ('00000000-0000-0000-0000-000000000000');

---------------------------------------------------------------
-- Strassennetz (Part 1)
---------------------------------------------------------------
CREATE TABLE strassennetz.widmung (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	widmung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	wkb_geometry geometry(MultiPolygon,25833),
	CONSTRAINT pk_widmung PRIMARY KEY (id),
	CONSTRAINT uk1_widmung UNIQUE (widmung)
)
WITH (
	OIDS=TRUE
);
INSERT INTO strassennetz.widmung (id) VALUES ('00000000-0000-0000-0000-000000000000');

CREATE INDEX widmung_s
	ON strassennetz.widmung
	USING gist
	(wkb_geometry);

CREATE TABLE strassennetz.gvm_netz (
	id serial NOT NULL,
	geom geometry(LineString,25833),
	"AMT" character varying(64),
	"FID" double precision,
	"LENGTH" numeric,
	"FEATID" character varying(32),
	"C02" character varying(64),
	"C01" character varying(64),
	"NUTZUNG" character varying(64),
	"GEMEINDE" character varying(64),
	"GEMEINDEKZ" character varying(64),
	"ORT" character varying(64),
	"STRASSE" character varying(64),
	"GEOGRAT_GIS_ID" character varying(5),
	"STRASSENNETZLAGE" character varying(255),
	"ALB_STRSL" character varying(64),
	"ALB_STR" character varying(1),
	"LAGEBESCHR_NETZ" character varying(64),
	"KLASSIFIZ" character varying(64),
	"FAHRSTREIF" character varying(64),
	"ENDABNAHME" timestamp without time zone,
	"BEMERKUNG" character varying(2000),
	"ERFASSER" character varying(64),
	"ERFASSER2" character varying(64),
	"EXPORTIERT" character varying(64),
	"CAD_TEXT" character varying(64),
	"SYMB_NAME" character varying(64),
	"LAGEBESCHREIBUNG" character varying(64),
	"GEMARKUNG" character varying(255),
	"FKNOTENU" double precision,
	"FKNOTENO" double precision,
	"NUTZUNG1" character varying(255),
	"PRODUKT" character varying(30),
	"KLASSIFIZ1" character varying(255),
	"FAHRSTREIF1" character varying(255),
	"ERFASSER21" character varying(255),
	"SG_INSRTUSER" character varying(50),
	"SG_INSRTDAT" timestamp without time zone,
	"SG_UPDTUSER" character varying(50),
	"SG_UPDTDAT" timestamp without time zone,
	CONSTRAINT gvm_netz_pkey PRIMARY KEY (id)
)
WITH (
	OIDS=TRUE
);
	
CREATE INDEX sidx_gvm_netz_geom
	ON strassennetz.gvm_netz
	USING gist
	(geom);

CREATE TABLE strassennetz.gvm_netzknoten (
	id serial NOT NULL,
	geom geometry(Point,25833),
	"FID" double precision,
	"ORIENTATION" double precision,
	"FEATID" character varying(32),
	"C02" character varying(64),
	"C01" character varying(64),
	"EXPORTIERT" character varying(64),
	"STATUS_AKT" character varying(64),
	"SYMB_NAME" character varying(64),
	"LAGEBESCHR_OBJ" character varying(64),
	"DATUM" timestamp without time zone,
	"ERFASSER2" character varying(64),
	"ERFASSER" character varying(64),
	"BEMERKUNG" character varying(2000),
	"KLASSIFIZ" character varying(64),
	"PRUEFVERME" character varying(64),
	"STATUS_GEP" character varying(64),
	"OBJEKTBEZ" character varying(64),
	"STATUS_AKT1" character varying(255),
	"ERFASSER21" character varying(255),
	"KLASSIFIZ1" character varying(255),
	"PRUEFVERME1" character varying(255),
	"STATUS_GEP1" character varying(255),
	"SG_INSRTUSER" character varying(50),
	"SG_INSRTDAT" timestamp without time zone,
	"SG_UPDTUSER" character varying(50),
	"SG_UPDTDAT" timestamp without time zone,
	"AMT" character varying(16),
	CONSTRAINT gvm_netzknoten_pkey PRIMARY KEY (id)
)
WITH (
	OIDS=FALSE
);
	
CREATE INDEX sidx_gvm_netzknoten_geom
	ON strassennetz.gvm_netzknoten
	USING gist
	(geom);

CREATE TABLE strassennetz.strasse (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_gemeinde character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_widmung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying,
    bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
	schluessel character(5) NOT NULL DEFAULT '00000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	CONSTRAINT pk_strasse PRIMARY KEY (id),
	CONSTRAINT fk_strasse_gemeinde FOREIGN KEY (id_gemeinde)
		REFERENCES kataster.gemeinde (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strasse_widmung FOREIGN KEY (id_widmung)
		REFERENCES strassennetz.widmung (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT uk1_strasse UNIQUE (id_gemeinde, schluessel)
)
WITH (
	OIDS=TRUE
);
INSERT INTO strassennetz.strasse (id) VALUES ('00000000-0000-0000-0000-000000000000');

CREATE TABLE strassennetz.verbindungspunkt (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_strasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	ident character(6) NOT NULL,
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	wkb_geometry geometry(Point,25833),
	CONSTRAINT pk_verbindungspunkt PRIMARY KEY (id),
	CONSTRAINT fk_verbindungspunkt_strasse FOREIGN KEY (id_strasse)
		REFERENCES strassennetz.strasse (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
	OIDS=TRUE
);

-- Trigger: tr_idents_add_ident on strassennetz.verbindungspunkt
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON strassennetz.verbindungspunkt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on strassennetz.verbindungspunkt
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON strassennetz.verbindungspunkt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

INSERT INTO strassennetz.verbindungspunkt (bemerkung) VALUES ('default Netzknoten oben');
INSERT INTO strassennetz.verbindungspunkt (bemerkung) VALUES ('default Netzknoten unten');

CREATE INDEX verbindungspunkt_s
	ON strassennetz.verbindungspunkt
	USING gist
	(wkb_geometry);
---------------------------------------------------------------
-- TRIGGERFUNKTIONEN
---------------------------------------------------------------
---------------------------------------------------------------
-- tf_ai_strassenelement()
---------------------------------------------------------------
-- Function: strassennetz.tf_ai_strassenelement()
-- DROP FUNCTION strassennetz.tf_ai_strassenelement();
CREATE OR REPLACE FUNCTION strassennetz.tf_ai_strassenelement()
	RETURNS trigger AS
$BODY$
DECLARE
	--vsrid												NUMERIC;
	vmin_abstand								 NUMERIC;
	vuuid_verbindungspunkt_oben	character varying;
	vuuid_verbindungspunkt_unten character varying;
	vwkb_geometry_oben					 geometry(Point,25833);
	vwkb_geometry_unten					geometry(Point,25833);
	vz													 NUMERIC;
	c														RECORD;
BEGIN
	IF NEW.wkb_geometry IS NOT NULL THEN
		--vsrid												:= 25833;
		vmin_abstand								 := .1;	--wird noch nicht verwendet
		vwkb_geometry_oben					 := ST_StartPoint(NEW.wkb_geometry);
		vwkb_geometry_unten					:= ST_EndPoint(NEW.wkb_geometry);

		--------------------------------------------------------------------------------------------------------
		FOR c in (select count(*) A from strassennetz.verbindungspunkt where wkb_geometry = vwkb_geometry_oben) loop
			vz := c.A; 
		END LOOP;
		
		IF vz = 0 THEN --wenn kein Knoten an der Stelle gefunden wird 
			vuuid_verbindungspunkt_oben := uuid_generate_v4(); --dann uuid neu generieren
			EXECUTE 'INSERT INTO strassennetz.verbindungspunkt (id, id_strasse, wkb_geometry) VALUES ( $1, $2, $3 )' USING vuuid_verbindungspunkt_oben, NEW.id_strasse, vwkb_geometry_oben; --und Datensatz anlegen
			NEW.id_verbindungspunkt_oben := vuuid_verbindungspunkt_oben; --und die id im neuen strassenelement eintragen
		ELSE
		FOR c in (select id from strassennetz.verbindungspunkt where wkb_geometry = vwkb_geometry_oben) loop
			vuuid_verbindungspunkt_oben := c.id; -- id ermitteln
		END LOOP;
		 NEW.id_verbindungspunkt_oben := vuuid_verbindungspunkt_oben; --sonst nur die ermittelte id im neuen strassenelement eintragen
		END IF;
		--------------------------------------------------------------------------------------------------------
		FOR c in (select count(*) A from strassennetz.verbindungspunkt where wkb_geometry = vwkb_geometry_unten) loop
			vz := c.A; 
		END LOOP;

		IF vz = 0 then --wenn kein Knoten an der Stelle gefunden wird 
			vuuid_verbindungspunkt_unten := uuid_generate_v4(); --dann uuid neu generieren
			EXECUTE 'INSERT INTO strassennetz.verbindungspunkt (id, id_strasse, wkb_geometry) VALUES ( $1, $2, $3 )' USING vuuid_verbindungspunkt_unten, NEW.id_strasse, vwkb_geometry_unten; --und Datensatz anlegen
			NEW.id_verbindungspunkt_unten := vuuid_verbindungspunkt_unten; --und die id im neuen strassenelement eintragen
		ELSE
		FOR c in (select id from strassennetz.verbindungspunkt where wkb_geometry = vwkb_geometry_unten) loop
			vuuid_verbindungspunkt_unten := c.id; -- id ermitteln
		END LOOP;
			NEW.id_verbindungspunkt_unten := vuuid_verbindungspunkt_unten; --sonst nur die ermittelte id im neuen strassenelement eintragen
		END IF;
		--------------------------------------------------------------------------------------------------------
	END IF;
	RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
COST 100;

---------------------------------------------------------------
-- Strassennetz (Part 2)
---------------------------------------------------------------
CREATE TABLE strassennetz.strassenelement (
	id character varying NOT NULL DEFAULT uuid_generate_v4(),
	id_strasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_verbindungspunkt_oben character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_verbindungspunkt_unten character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000001'::character varying,
	ident character(6) NOT NULL,
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	id_nutzung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_klassifizierung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_strassennetzlage character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying,
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone,
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()),
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying,
	wkb_geometry geometry(LineString,25833),
	CONSTRAINT pk_strassenelement PRIMARY KEY (id),
	CONSTRAINT fk_strassenelement_strasse FOREIGN KEY (id_strasse)
		REFERENCES strassennetz.strasse (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_verbindungspunkt_oben FOREIGN KEY (id_verbindungspunkt_oben)
		REFERENCES strassennetz.verbindungspunkt (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_verbindungspunkt_unten FOREIGN KEY (id_verbindungspunkt_unten)
		REFERENCES strassennetz.verbindungspunkt (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_klassifizierung FOREIGN KEY (id_klassifizierung)
		REFERENCES base.wld_klassifizierung (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_nutzung FOREIGN KEY (id_nutzung)
		REFERENCES base.wld_nutzung (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_strassennetzlage FOREIGN KEY (id_strassennetzlage)
		REFERENCES base.wld_strassennetzlage (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
	OIDS=TRUE
);

CREATE INDEX strassenelement_s
	ON strassennetz.strassenelement
	USING gist
	(wkb_geometry);

-- Trigger: tr_ai_strassenelement on strassennetz.strassenelement
CREATE TRIGGER tr_ai_strassenelement
	AFTER INSERT
	ON strassennetz.strassenelement
	FOR EACH ROW
	EXECUTE PROCEDURE strassennetz.tf_ai_strassenelement();

-- Trigger: tr_idents_add_ident on strassennetz.strassenelement
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON strassennetz.strassenelement
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on strassennetz.strassenelement
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON strassennetz.strassenelement
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();


---------------------------------------------------------------
---------------------------------------------------------------
-- CREATE ABSTRACT BASISOBJEKT
---------------------------------------------------------------
---------------------------------------------------------------
-- Basisobjekt contains data (is a generalization)from doppik and OKSTRA, which are relevant for all other tables
-- Basisobjekt is considered abstract and should not be realized on its own
CREATE TABLE base.basisobjekt (
	id character varying NOT NULL DEFAULT uuid_generate_v4(), --origin doppik, equals OKSTRA_id (datatype GUID), can be NULL in OKSTRA
	gueltig_von timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()), --origin doppik, equals OKSTRA gueltig_von (datatype Date), can be NULL in OKSTRA
	gueltig_bis timestamp with time zone NOT NULL DEFAULT '2100-01-01 02:00:00+01'::timestamp with time zone, --origin doppik, equals OKSTRA gueltig_bis (datatype Date), can be NULL in OKSTRA
	id_strassenelement character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying, --origin doppik
	id_preisermittlung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying, --origin doppik
	id_zustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying, --origin doppik
	id_zustandsbewertung_01 character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_zustandsbewertung_02 character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_zustandsbewertung_03 character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_zustandsbewertung_04 character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_zustandsbewertung_05 character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
	id_eigentuemer character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying, --origin doppik
	id_baulasttraeger character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying, --origin doppik
	ahk numeric(10,2) NOT NULL DEFAULT 1, --origin doppik
	baujahr timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()), --origin doppik,	also equal to baujahr of several okstra element
	angelegt_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()), --origin doppik
	angelegt_von character varying NOT NULL DEFAULT 'unbekannt'::character varying, --origin doppik
	geaendert_am timestamp with time zone NOT NULL DEFAULT timezone('utc-1'::text, now()), --origin doppik
	geaendert_von character varying NOT NULL DEFAULT 'unbekannt'::character varying, --origin doppik
	ident_hist character varying NOT NULL DEFAULT 'unbekannt'::character varying, --origin doppik
	bemerkung character varying NOT NULL DEFAULT 'noch keine Bemerkung'::character varying, --origin doppik
	objektname character varying,
	zusatzbezeichnung character varying,
	objekt_id serial NOT NULL,
	objektart character varying,
	objektart_kurz character varying,
	objektnummer integer,
	zustandsnote character varying, -- overlaps with OKSTRA attribute in a few classes
	datum_der_benotung date,
	pauschalpreis numeric,
	baulasttraeger character varying,
	baulasttraeger_dritter character varying,
	abschreibung numeric,
	art_der_preisermittlung character varying,
	eroeffnungsbilanzwert numeric,
	zeitwert numeric,
	fremdobjekt character varying, --origin OKSTRA association, also equal to bemerkung of several okstra elements
	fremddatenbestand character varying, -- origin OKSTRA association
	kommunikationsobjekt character varying, -- origin OKSTRA association
	erzeugt_von_ereignis character varying, --origin OKSTRA association
	geloescht_von_ereignis character varying, --origin OKSTRA association
	hat_vorgaenger_hist_objekt character varying, -- origin OKSTRA association
	hat_nachfolger_hist_objekt character varying, -- origin OKSTRA association
	CONSTRAINT pk_basisobjekt_id PRIMARY KEY (id),
	CONSTRAINT fk_basisobjekt_id_strassenelement FOREIGN KEY (id_strassenelement)
		REFERENCES strassennetz.strassenelement (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_basisobjekt_id_baulasttraeger FOREIGN KEY (id_baulasttraeger)
		REFERENCES base.wld_baulasttraeger (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_basisobjekt_id_eigentuemer FOREIGN KEY (id_eigentuemer)
		REFERENCES base.wld_eigentuemer (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_basisobjekt_preisermittlung FOREIGN KEY (id_preisermittlung)
		REFERENCES base.wld_preisermittlung (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_basisobjekt_id_zustand FOREIGN KEY (id_zustand)
		REFERENCES base.wld_zustand (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
	OIDS = TRUE
);
COMMENT ON COLUMN base.basisobjekt.id IS 'is equivalent to OKSTRA_ID from the superobject OKSTRA_Objekt (that is inherited by all realized OKSTRA classes)';
COMMENT ON COLUMN base.basisobjekt.gueltig_von IS 'is equivalent to gueltig_von from the superobject historisches_Objekt (that is inherited by all realized OKSTRA classes)';
COMMENT ON COLUMN base.basisobjekt.gueltig_bis IS 'is equivalent to gueltig_bis from the superobject historisches_Objekt (that is inherited by all realized OKSTRA classes)';
COMMENT ON COLUMN base.basisobjekt.hat_vorgaenger_hist_objekt IS 'is equivalent to association hat_vorgaenger_hist_objekt from the suberobject historisches Objekt in OKSTRA (that is inherited by all realized OKSTRA classes)';
COMMENT ON COLUMN base.basisobjekt.hat_nachfolger_hist_objekt IS 'is equivalent to association hat_nachfolger_hist_objekt from the suberobject historisches Objekt in OKSTRA (that is inherited by all realized OKSTRA classes)';

CREATE TABLE base.punktobjekt (
	bei_strassenpunkt_station numeric,-- bei_strassenpunkt holds the the complex data type strassenpunkt. if any value of strassenpunkt is filled, station must be filled
	bei_strassenpunkt_abstand_zur_bestandsachse numeric, -- bei_strassenpunkt holds the the complex data type strassenpunkt. if any value of strassenpunkt is filled, station must be filled
	bei_strassenpunkt_abstand_zur_fahrbahnoberkante numeric, -- bei_strassenpunkt holds the the complex data type strassenpunkt. if any value of strassenpunkt is filled, station must be filled
	bei_strassenpunkt_auf_abschnitt_oder_ast character varying, -- Association. If any value of strassenpunkt is filled, auf_abschnitt_oder_ast must also be filled
	bei_strassenelementpunkt_station numeric,-- bei_strassenpunkt holds the the complex data type strassenelementpunkt. if any value of strassenelementpunkt is filled, station must be filled
	bei_strassenelementpunkt_abstand_zur_bestandsachse numeric, -- bei_strassenpunkt holds the the complex data type strassenelementpunkt. if any value of strassenelementpunkt is filled, station must be filled
	bei_strassenelementpunkt_abstand_zur_fahrbahnoberkante numeric, -- bei_strassenpunkt holds the the complex data type strassenelementpunkt. if any value of strassenelementpunkt is filled, station must be filled
	bei_strassenelementpunkt_auf_strassenelement character varying, -- Association. If any value of strassenpunkt is filed, auf_strassenelement must also be filled
	geometrie_punktobjekt geometry(MultiPoint, 25833)
) INHERITS(base.basisobjekt);

CREATE TABLE base.streckenobjekt (
	geometrie_streckenobjekt geometry(MultiLineString, 25833)
) INHERITS(base.basisobjekt);

CREATE TABLE base.punktundstreckenobjekt (
	geometrie_streckenobjekt geometry(MultiLineString, 25833)
) INHERITS(base.punktobjekt);

---------------------------------------------------------------
---------------------------------------------------------------
-- CREATE-ABSTRACT OKSTRA-TABLES
---------------------------------------------------------------
---------------------------------------------------------------
-- Tables are abstract and contain different okstra possiblities
CREATE TABLE okstra.bewuchs (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	hat_objekt_id character varying,
	biotoptyp_schluessel character varying, --part of complex datatype biotoptyp. If schluessel has a value, version_schluessel and biotoptypangabe must have a value as well
	biotoptyp_version_schluessel character varying, -- part of complex datatype biotoptyp. If version_schluessel has a value, schluessel and biotoptypangabe must have a value as well
	biotoptyp_biotoptypangabe character varying, -- part of complex datatype biotoptyp. if biotoptypangabe has a value, schluessel and version_schluessel must have a value as well
	flaechengroesse numeric,
	laenge numeric,
	multigeometrie geometry(geometry,25833),
	bestandsstatus character varying NOT NULL default '6',
	beschreibung character varying,
	schutzstatus character varying,
	zustaendigkeit character varying, 
	verkehrsraumeinschraenkung boolean,
	erfassungsqualitaet_erfassung_verfahren character varying, -- part of complex datatype Erfassungsqualitaet. If erfassung_verfahren has a value, standardabweichung must have a value as well
	erfassungsqualitaet_standardabweichung numeric, -- part of complex datatype Erfassungsqualitaet . If standardabweichung has a value, erfassung_verfahren must have a value as well
	gehoert_zu_massnahme character varying,--Association
	gehoert_zu_biotopkomplex character varying,--Association
	ausgangsbiotop_von character varying,--Association
	zielbiotop_von character varying,--Association
	hat_lpf_teilelement character varying,--Association
	zu_konfliktbestandteil character varying,--Association
	hat_leistungsbeschreibung character varying,--Association
	hat_pflegemassnahme character varying,--Association
	hat_dokument character varying,--Association
	CONSTRAINT fk_bewuchs_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bewuchs_bestandsstatus FOREIGN KEY (bestandsstatus)
		REFERENCES okstra.wlo_bestandsstatus (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bewuchs_schutzstatus FOREIGN KEY (schutzstatus)
		REFERENCES okstra.wlo_schutzstatus_bewuchs (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bewuchs_erfassungsqualitaet_erfassung_verfahren FOREIGN KEY (erfassungsqualitaet_erfassung_verfahren)
		REFERENCES okstra.wlo_erfassung_verfahren (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bewuchs_biotoptyp_biotoptypangabe FOREIGN KEY (biotoptyp_biotoptypangabe)
		REFERENCES okstra.wlo_tab_biotoptyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.baum(
	lage character varying DEFAULT '99'::character varying,
	baumgattung character varying NOT NULL default '000',
	baumart character varying,
	stammumfang numeric,
	stammdurchmesser numeric,
	kronendurchmesser numeric,
	wurzelhalsdurchmesser numeric,
	stammhoehe numeric,
	baumhoehe numeric,
	baumscheibe numeric,
	pflanzjahr date,
	gefaellt boolean,
	datum_der_faellung date,
	letzte_baumschau character varying,
	schiefstand character varying,
	zustandsbeurteilung character varying,
	lagebeschreibung character varying,
	detaillierungsgrad character varying,
	stellt_teilhindernis_dar character varying,--Association
	hat_baumschaeden character varying,--Association
	zu_baumreihenabschnitt character varying, --Association
	CONSTRAINT fk_baum_baumgattung FOREIGN KEY (baumgattung)
		REFERENCES okstra.wlo_baumgattung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_baumart FOREIGN KEY (baumart)
		REFERENCES okstra.wlo_baumart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_schiefstand FOREIGN KEY (schiefstand)
		REFERENCES okstra.wlo_schiefstand_baum (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_zustandsbeurteilung FOREIGN KEY (zustandsbeurteilung)
		REFERENCES okstra.wlo_zustandsbeurteilung_baum (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_lagebeschreibung FOREIGN KEY (lagebeschreibung)
		REFERENCES okstra.wlo_lagebeschreibung_baum (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage(kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_baum_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.bewuchs);

CREATE TABLE okstra.teilbauwerk (
	hat_objekt_id character varying,
	punktgeometrie_gauss_krueger geometry(point, 25833),
	punktgeometrie_utm geometry(point, 25833),
	koordinaten_vom_system_berechnet boolean,
	strassenbezeichnung_strassenklasse character varying, -- part of complex datatype strassenbezeichnung. If strassennummer, zusatzbuchstabe or identifizierungskennzeichen have a value, strassenklasse must have a value as well
	strassenbezeichnung_strassennummer character varying, -- part of complex datatype strassenbezeichnung. If strassenklasse, zusatzbuchstabe or identifizierungskennzeichen have a value, strassennummer must have a value as well
	strassenbezeichnung_zusatzbuchstabe character varying, -- part of complex datatype strassenbezeichnung
	strassenbezeichnung_identifizierungskennzeichen character varying, -- part of complex datatype strassenbezeichnung
	teilbauwerksnummer character varying NOT NULL,
	interne_teilbauwerksnummer character varying,
	name_des_teilbauwerks character varying,
	interner_sortierschluessel character varying,
	unterhaltung_instandsetzung character varying,
	bauwerksart character varying,
	stadium_teilbauwerk character varying,
	stationierung character varying,
	bauwerksakte_nummer character varying,
	baulast_konstruktion character varying,
	anderes_bauwerk_nach_din1076 character varying,
	-- baujahr integer, --already contained in basisobjekt
	denkmalschutz character varying,
	unterlagen character varying,
	datenerfassung_abgeschlossen character varying,
	unterhaltungslast_ueberbau character varying,
	konkretisierung_ueberbau character varying,
	unterhaltungslast_unterbau character varying,
	konkretisierung_unterbau character varying,
	konstruktion character varying,
	bauwerksrichtung_text character varying,
	massgebendes_teilbauwerk boolean,
	-- bemerkungen character varying, -- already contained in basisobjekt
	bemerkungen_zuordnung character varying,
	name_ui_ua_partner character varying,
	sachverhaltsnummer character varying,
	tragfaehigkeit character varying,
	stat_system_in_bauwerksachse character varying,
	stat_system_quer_zu_bauw_achse character varying,
	sperrung_fuer_schwertransporte boolean,
	statistischer_auslastungsgrad numeric,
	-- zustandsnote numeric, -- bereits in doppik vorhanden
	max_schadbw_standsicherheit character varying,
	anzahl_der_fahrstr_in_stat integer,
	anzahl_der_fahrstr_gegen_stat integer,
	min_breite_in_stationierung numeric,
	min_breite_gegen_stationierung numeric,
	hat_strecke character varying, -- Association
	hat_abdichtungen character varying, -- Association
	hat_abgeschlossene_pruefung character varying, -- Association
	hat_anlagen_bauwerksbuch character varying, -- Association
	ist_aufstellvorrichtung character varying, -- Association
	hat_ausstattung character varying, -- Association
	hat_bau_und_erhaltungsmassn character varying, -- Association
	hat_bauwerkseinzelheiten character varying, -- Association
	hat_bauwerksueberfahrt character varying, -- Association
	hat_betonersatzsystem character varying, -- Association
	hat_brueckenseile_und_kabel character varying, -- Association
	hat_durchgef_pruefungen_messgn character varying, -- Association
	ist_durchlass character varying, -- Association
	hat_entwuerfe_und_berechnungen character varying, -- Association
	hat_erd_und_felsanker character varying, -- Association
	hat_fahrbahnuebergang character varying, -- Association
	hat_gegenw_dok_bauwerkszustand character varying, -- Association
	hat_gestaltungen character varying, -- Association
	hat_gruendungen character varying, -- Association
	ist_hindernis character varying, -- Association
	hat_kappe character varying, -- Association
	von_kreuzung_strasse_weg character varying, -- Association
	auf_laermschutzwall character varying, -- Association
	hat_leitungen_an_bauwerken character varying, -- Association
	hat_oberflaechenschutzsystem character varying, -- Association
	hat_pruefanweisungen character varying, -- Association
	hat_prueffahrzeuge_pruefger character varying, -- Association
	hat_reaktionsharzgeb_duennbel character varying, -- Association
	hat_sachverhalt character varying, -- Association
	hat_schutzeinrichtungen character varying, -- Association
	hat_statistisches_system_tragfgkt character varying, -- Association
	hat_strassenausstattung_punkt character varying, -- Association
	hat_strategie_bms character varying, -- Association
	hat_teilmassnahme_bwk character varying, -- Association
	hat_verfuellungen character varying, -- Association
	hat_verwaltungsmassnahme character varying, -- Association
	ist_vorschalteinrichtung character varying, -- Association
	hat_vorspannungen character varying, -- Association
	bauwerk character varying, -- Association
	baudienststelle character varying, -- Association
	CONSTRAINT fk_teilbauwerk_strassenbezeichnung_strassenklasse FOREIGN KEY (strassenbezeichnung_strassenklasse)
		REFERENCES okstra.wlo_strassenklasse (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktundstreckenobjekt);

CREATE TABLE okstra.bruecke(
	gesamtlaenge_bruecke numeric,
	breite_bruecke numeric,
	gesamtbreite_bruecke numeric,
	brueckenflaeche_quadratmeter numeric,
	zwischenraum_ueberbauten numeric,
	konstruktionshoehe_min numeric,
	konstruktionshoehe_max numeric,
	anzahl_felder_bruecke integer,
	anzahl_ueberbauten_bruecke integer,
	anzahl_stege_bruecke integer,
	laengsneigung_max numeric,
	querneigung_max numeric,
	kruemmung character varying,
	bauwerkswinkel numeric,
	winkelrichtung character varying,
	querschnitt_ueberbau character varying,
	querschnitt_haupttragwerk character varying,
	bauverfahren_ueberbau character varying,
	konstr_massn_nachtr_verstaerk character varying,
	koppelfugen character varying,
	maximale_ueberschuettungshoehe numeric,
	minimale_ueberschuettungshoehe numeric,
	lichte_hoehe numeric,
	lichte_weite numeric,
	bemerkungen_zum_baugrund character varying,
	bemerkungen_zur_bruecke character varying,
	baustoff character varying,
	zu_nachberechnung_bruecke character varying, -- Association
	hat_baustoff_bauwerk character varying, -- Association
	hat_brueckenfeld_stuetzung character varying -- Association
)
INHERITS (okstra.teilbauwerk);

CREATE TABLE okstra.tunnel_trogbauwerk(
	gradiente character varying,
	rundungshalbmesser numeric,
	minimale_laengsneigung numeric,
	maximale_laengsneigung numeric,
	minimaler_radius_im_grundriss numeric,
	minimale_ueberdeckungshoehe numeric,
	maximale_ueberdeckungshoehe numeric,
	hoehe_rel_nn_in_tunnelmitte numeric,
	bauwerkslaenge numeric,
	geschl_laenge_der_tunnelroehre numeric,
	tunnelflaeche numeric,
	bauweise character varying,
	querschnitt character varying,
	lichte_weite_sonderquerschnitt numeric,
	ausbruchsflaeche numeric,
	sicherung character varying,
	geologie character varying,
	grund_und_gebirgswaserverh character varying,
	bauverfahren character varying,
	vortriebsverfahren character varying,
	entwaesserungsart character varying,
	entwaesserungsart_laenge character varying,
	anzahl_segmente integer,
	bemerkungen_zum_tunnel_trogbau character varying,
	hat_baustoff_bauwerk character varying, -- Association
	baut_brueckenfeld_stuetzung character varying, -- Association
	hat_segmente_tunnel_trogbw character varying, -- Association
	hat_tunnel_verkehrseinrichtgn character varying, -- Association
	hat_tunnel_zentrale_anlagen character varying, -- Association
	hat_tunnelbeleuchtung character varying, -- Association
	hat_tunnellueftung character varying, -- Association
	hat_tunnelsicherheit character varying -- Association
	
)
INHERITS (okstra.teilbauwerk);

CREATE TABLE okstra.stuetzbauwerk(
	gesamtlaenge_stuetzbauwerk numeric,
	flaeche_stuetzbauwerk numeric,
	anzahl_segmente integer,
	segmentbaustoffklasse_1 character varying,
	flaeche_segmentbaustoffklasse_1 character varying,
	segmentbaustoffklasse_2 character varying,
	flaeche_segmentbaustoffklasse_2 character varying,
	segmentbaustoffklasse_3 character varying,
	flaeche_segmentbaustoffklasse_3 character varying,
	segmentbaustoffklasse_4 character varying,
	flaeche_segmentbaustoffklasse_4 character varying,
	maximale_segmenthoehe numeric,
	durchschnittliche_segmenthoehe numeric,
	bemerkungen_zum_stuetzbauwerk character varying,
	hat_segment_stuetzbauwerk character varying -- Association
)
INHERITS (okstra.teilbauwerk);

CREATE TABLE okstra.laermschutzbauwerk(
	gesamtlaenge integer,
	flaeche character varying,
	anzahl_segmente integer,
	segmentbaustoffklasse_1 character varying,
	flaeche_segmentbaustoffklasse_1 character varying,
	segmentbaustoffklasse_2 character varying,
	flaeche_segmentbaustoffklasse_2 character varying,
	segmentbaustoffklasse_3 character varying,
	flaeche_segmentbaustoffklasse_3 character varying,
	segmentbaustoffklasse_4 character varying,
	flaeche_segmentbaustoffklasse_4 character varying,
	maximale_segmentheohe numeric,
	durchschnittliche_segmenthoehe numeric,
	hat_segment_laermschutzbw character varying -- Association
)
INHERITS (okstra.teilbauwerk);

CREATE TABLE okstra.verkehrszeichenbruecke(
	querschnitt_stiel character varying,
	querschnitt_riegel character varying,
	gesamtlaenge_des_riegels numeric,
	besichtigungs_wartungsoeffnung character varying,
	befest_konstr_schilder_signalg character varying,
	bemerkung_verkehrsz_bruecke character varying,
	hat_baustoff_bauwerk character varying, -- Association
	hat_brueckenfeld_stuetzung character varying -- Association
)
INHERITS (okstra.teilbauwerk);

CREATE TABLE okstra.strassenausstattung_strecke (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	lage character varying DEFAULT '99'::character varying,
	art character varying NOT NULL default '99',
	art_sonst character varying,
	dauereinrichtung character varying,
	tatsaechliche_laenge numeric,
	multigeometrie geometry(geometry,25833),
	dokument character varying, -- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummerbereich character varying, -- Association
	hat_strecke character varying, -- Association
	hat_strassenausstattung_punkt character varying, -- Association
	CONSTRAINT fk_strassenausstattung_strecke_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_strassenausst_strecke (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_art_sonst FOREIGN KEY (art_sonst)
		REFERENCES okstra.wlo_art_strausst_strecke_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_strecke_dauereinrichtung FOREIGN KEY (dauereinrichtung)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.strassenausstattung_punkt (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf character varying,
	lage character varying DEFAULT '99'::character varying,
	art character varying NOT NULL default '99',
	art_sonst character varying,
	dauereinrichtung character varying, 
	detaillierungsgrad character varying,
	multigeometrie geometry(geometry, 25833),
	zu_betriebseinrichtung character varying, -- association
	zu_schutzeinr_tiere character varying, -- association
	zu_strassenaus_str character varying, -- association
	stellt_teilhindernis_dar character varying, -- association
	zu_teilbauwerk character varying, -- association
	dokument character varying, -- association
	hat_rechtliches_Ereignis character varying, -- association
	hat_Zustaendigkeit character varying, -- association
	zu_hausnummernblock character varying, -- association
	zu_hausnummernbereich character varying, -- association
	CONSTRAINT fk_strassenausstattung_punkt_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_punkt_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_strassenausst_punkt (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_punkt_art_sonst FOREIGN KEY (art_sonst)
		REFERENCES okstra.wlo_art_strausst_punkt_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_punkt_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_punkt_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenausstattung_punkt_dauereinrichtung FOREIGN KEY (dauereinrichtung)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.aufstellvorrichtung_schild (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	geometrie_netzbezugsobjekt_vpunkt geometry(point, 25833),
	geometrie_netzbezugsobjekt_kompknoten geometry(polygon, 25833),
	nummer_aufstellvorrichtung character varying,
	abstand_linker_pfosten numeric,
	abstand_rechter_pfosten numeric,
	position_linker_pfosten geometry(point,25833),
	position_rechter_pfosten geometry(point,25833),
	lage character varying DEFAULT '99'::character varying,
	art character varying,
	durchmesser numeric,
	hoehe numeric,
	material character varying,
	detaillierungsgrad character varying,
	dokument character varying, -- Association
	hat_rechtliches_Ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummerbereich character varying, -- Association
	an_verbindungspunkt character varying, -- Association
	an_komplexem_knoten character varying, -- Association
	hat_schild character varying, -- Association
	stellt_teilhindernis_dar character varying, -- Association
	ist_teilbauwerk character varying, -- Association
	CONSTRAINT fk_aufstellvorrichtung_schild_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_der_aufstellvorrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_material FOREIGN KEY (material)
		REFERENCES okstra.wlo_material_aufstellvorrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufstellvorrichtung_schild_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.schild(
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	art_schild_ok character varying,
	art_schild_asb character varying,
	art_schild_nichtamtlich_asb character varying,
	schildnummer integer,
	lage_schild character varying,
	breite numeric,
	hoehe numeric,
	hoehe_unterkante numeric,
	normalenrichtung numeric,
	lesbarkeit character varying,
	strassenbezug_asb character varying,
	befestigung character varying,
	beleuchtung character varying,
	verdeckbar character varying,
	groessenklasse character varying,
	einzel_mehrfach_schild character varying,
	unterhaltungspflicht character varying DEFAULT '00'::character varying,
	sonstige_unterhaltspflicht character varying,
	dokument character varying, -- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummernbereich character varying, -- Association
	ist_zusatzschild_von character varying, -- Association
	haeng_ueber character varying, -- Association
	hat_weigweisung_info character varying, -- Association
	zu_verkehrseinschraenkung character varying, -- Association
	hat_aufstellvorrichtung character varying, -- Association
	CONSTRAINT fk_schild_art_schild_ok FOREIGN KEY (art_schild_ok)
		REFERENCES okstra.wlo_art_schild_ok (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_art_schild_asb FOREIGN KEY (art_schild_asb)
		REFERENCES okstra.wlo_art_schild_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_art_schild_nichtamtlich_asb FOREIGN KEY (art_schild_nichtamtlich_asb)
		REFERENCES okstra.wlo_art_schild_nichtamtlich_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_lage_schild FOREIGN KEY (lage_schild)
		REFERENCES okstra.wlo_lage_schild (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_strassenbezug_asb FOREIGN KEY (strassenbezug_asb)
		REFERENCES okstra.wlo_strassenbezug_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_befestigung FOREIGN KEY (befestigung)
		REFERENCES okstra.wlo_befestigung_schild (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_beleuchtung FOREIGN KEY (beleuchtung)
		REFERENCES okstra.wlo_beleuchtung_schild (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_groessenklasse FOREIGN KEY (groessenklasse)
		REFERENCES okstra.wlo_groessenklasse_vz (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_einzel_mehrfach_schild FOREIGN KEY (einzel_mehrfach_schild)
		REFERENCES okstra.wlo_einzel_mehrfach_schild (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_unterhaltungspflicht FOREIGN KEY (unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht_schild (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_sonstige_unterhaltspflicht FOREIGN KEY (sonstige_unterhaltspflicht)
		REFERENCES okstra.wlo_sonstige_unterhaltspflichtige (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schild_verdeckbar FOREIGN KEY (verdeckbar)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.strassenablauf (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	lage character varying DEFAULT '00'::character varying,
	aufsatz character varying DEFAULT '00'::character varying,
	unterteil character varying DEFAULT '00'::character varying,
	art_unterteil_sonst character varying,
	unterhaltungspflicht character varying DEFAULT '00'::character varying,
	sonstige_unterhaltspflichtige character varying,
	detaillierungsgrad character varying,
	multigeometrie geometry(geometry,25833),
	dokument character varying, -- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummernbereich character varying, -- Association
	CONSTRAINT fk_strassenablauf_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_aufsatz FOREIGN KEY (aufsatz)
		REFERENCES okstra.wlo_art_aufsatz (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_unterteil FOREIGN KEY (unterteil)
		REFERENCES okstra.wlo_art_unterteil (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_art_unterteil_sonst FOREIGN KEY (art_unterteil_sonst)
		REFERENCES okstra.wlo_art_unterteil_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_unterhaltungspflicht FOREIGN KEY (unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenablauf_sonstige_unterhaltspflichtige FOREIGN KEY (sonstige_unterhaltspflichtige)
		REFERENCES okstra.wlo_sonstige_unterhaltspflichtige (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.abfallentsorgung(
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	ausstattungstyp character varying,
	groesse_ausstattungstyp numeric,
	anzahl_ausstattungstyp integer,
	abfall character varying,
	lagetyp character varying,
	material character varying,
	aufstellungsjahr date,
	unterhaltungspflicht character varying DEFAULT '00'::character varying,
	sonstige_unterhaltungspflicht character varying,
	vertragsnummer character varying,
	dokument character varying, -- Association
	anlage_des_ruhenden_verkehrs character varying NOT NULL, --Association
	CONSTRAINT fk_abfallentsorgung_ausstattungstyp FOREIGN KEY (ausstattungstyp)
		REFERENCES okstra.wlo_typ_abfallentsorgung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_abfall FOREIGN KEY (abfall)
		REFERENCES okstra.wlo_art_abfall (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_lagetyp FOREIGN KEY (lagetyp)
		REFERENCES okstra.wlo_lagetyp_abfallentsorgung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_material FOREIGN KEY (material)
		REFERENCES okstra.wlo_material_abfallentsorgung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_unterhaltungspflicht FOREIGN KEY (unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_abfallentsorgung_sonstige_unterhaltungspflicht FOREIGN KEY (sonstige_unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.schacht (
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	art character varying NOT NULL DEFAULT '99',
	lage character varying DEFAULT '99'::character varying,
	angaben_zum_konus character varying,
	schachttiefe numeric,
	unterhaltungspflicht character varying DEFAULT '00'::character varying,
	sonstige_unterhaltspflicht character varying,
	detaillierungsgrad character varying,
	multigeometrie geometry(geometry,25833),
	dokument character varying, -- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummernbereich character varying, -- Association
	stellt_teilhindernis_dar character varying, -- Association
	CONSTRAINT fk_schacht_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_schacht(kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage_schacht_strassenablauf (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_angaben_zum_konus FOREIGN KEY (angaben_zum_konus)
		REFERENCES okstra.wlo_angaben_zum_konus MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_unterhaltungspflicht FOREIGN KEY (unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schacht_sonstige_unterhaltspflicht FOREIGN KEY (sonstige_unterhaltspflicht)
		REFERENCES okstra.wlo_sonstige_unterhaltspflichtige (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.querschnittstreifen(
 flaechengeometrie geometry(MultiPolygon, 25833),
 kreuzungszuordnung character varying,
 unterhaltsbezug_sp character varying,
 erfassungsdatum date,
 systemdatum date,
 textfeld character varying,
 art_der_erfassung character varying DEFAULT '00'::character varying,
 art_der_erfassung_sonst character varying,
 quelle_der_information character varying DEFAULT '00'::character varying,
 quelle_der_information_sonst character varying,
 rfid character varying,
 migrationshinweise character varying,
 unscharf boolean,
 x_wert_von_station_links numeric,
 x_wert_von_station_rechts numeric,
 x_wert_bis_station_links numeric,
 x_wert_bis_station_rechts numeric,
 lage character varying DEFAULT '99'::character varying,
 abgewickelte_breite numeric,
 mittlere_breite numeric,
 streifenart character varying NOT NULL DEFAULT '999',
 streifenart_sonst character varying,
 laengs_verlaufende_gleise character varying,
 art_der_oberflaeche character varying,
 unscharfe_breite boolean,
 tatsaechliche_laenge numeric,
 tatsaechliche_flaeche numeric,
 partielle_baulast character varying,
 partielle_ui_partner character varying,
 partielle_ui_sonstiger_partner character varying,
 detaillierungsgrad character varying,
 dokument	character varying, -- Associaton
 hat_leistungsbeschreibung character varying, -- Associaton
 hat_strecke character varying, -- Associaton
 partieller_baulasttraeger_dr character varying, -- Associaton
 zu_zielangabe_von character varying, -- Associaton
 zu_zielangabe_nach character varying, -- Associaton
 in_zeb_objekt character varying, -- Associaton
 in_verkehrsflaeche character varying, -- Associaton
 hat_flaechenbezugsobjekt character varying, -- Associaton
 hat_fahrstreifen character varying, -- Associaton
 CONSTRAINT fk_querschnittstreifen_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_streifenart FOREIGN KEY (streifenart)
		REFERENCES okstra.wlo_streifenart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_streifenart_sonst FOREIGN KEY (streifenart_sonst)
		REFERENCES okstra.wlo_streifenart_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_laengs_verlaufende_gleise FOREIGN KEY (laengs_verlaufende_gleise)
		REFERENCES okstra.wlo_anzahl_gleise_laengs (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_art_der_oberflaeche FOREIGN KEY (art_der_oberflaeche)
		REFERENCES okstra.wlo_art_der_oberflaeche (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_partielle_baulast FOREIGN KEY (partielle_baulast)
		REFERENCES okstra.wlo_art_part_baulasttraeger (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_partielle_ui_partner FOREIGN KEY (partielle_ui_partner)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_partielle_ui_sonstiger_partner FOREIGN KEY (partielle_ui_sonstiger_partner)
		REFERENCES okstra.wlo_sonstiger_ui_partner_land (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_querschnittstreifen_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.durchlass (
	geometrie_streckenobjekt geometry(MultiLineString,25833),
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	abstand_von_station numeric,
	abstand_bis_station numeric,
	lage character varying DEFAULT '99'::character varying,
	ueberdeckung_von_station numeric,
	ueberdeckung_bis_station numeric,
	mittlere_ueberdeckung numeric,
	profil character varying,
	hauptsaechliches_material character varying,
	lichte_hoehe_durchmesser numeric,
	lichte_weite numeric,
	flaeche_der_verblendung numeric,
	tatsaechliche_laenge numeric,
	unterhaltungspflicht character varying DEFAULT '00'::character varying,
	sonstige_unterhaltspflichtige character varying,
	funktion character varying,
	-- zustandsnote character varying,--bereits in doppik vorhanden
	-- datum_der_benotung date, -- bereits in doppik vorhanden
	permanente_nutzungseinschr character varying,
	schutzeinrichtung character varying,
	stadium character varying,
	-- baujahr date, --already contained in basisobjekt
	-- objektnummer character varying, --already contained in basisobjekt
	detaillierungsgrad character varying,
	multigeometrie geometry(geometry,25833),
	dokument character varying, -- Associaton
	hat_rechtliches_ereignis character varying, -- Associaton
	hat_zustaendigkeit character varying, -- Associaton
	zu_hausnummernblock character varying, -- Associaton
	zu_hausnummernbereich character varying, -- Associaton
	hat_strecke character varying, -- Associaton
	CONSTRAINT fk_durchlass_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_profil FOREIGN KEY (profil)
		REFERENCES okstra.wlo_profil_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_hauptsaechliches_material FOREIGN KEY (hauptsaechliches_material)
		REFERENCES okstra.wlo_material_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_unterhaltungspflicht FOREIGN KEY (unterhaltungspflicht)
		REFERENCES okstra.wlo_unterhaltungspflicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_sonstige_unterhaltspflichtige FOREIGN KEY (sonstige_unterhaltspflichtige)
		REFERENCES okstra.wlo_sonstige_unterhaltspflichtige (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_funktion FOREIGN KEY (funktion)
		REFERENCES okstra.wlo_funktion_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_zustandsnote FOREIGN KEY (zustandsnote)
		REFERENCES okstra.wlo_zustand_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_schutzeinrichtung FOREIGN KEY (schutzeinrichtung)
		REFERENCES okstra.wlo_schutzeinrichtung_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_stadium FOREIGN KEY (stadium)
		REFERENCES  okstra.wlo_stadium_durchlass (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchlass_permanente_nutzungseinschr FOREIGN KEY (permanente_nutzungseinschr)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
	)
INHERITS (base.punktobjekt);

CREATE TABLE okstra.leitung(
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	liniengeometrie geometry(linestring,25833),
	abstand_von_station numeric,
	abstand_bis_station numeric,
	lage_leitung character varying NOT NULL DEFAULT '00',
	hoehe_von_station numeric,
	hoehe_bis_station numeric,
	durchschnittliche_hoehe numeric,
	art character varying NOT NULL DEFAULT '00',
	art_detail character varying,
	material character varying,
	schutzrohr character varying,
	material_schutzrohr character varying,
	beschilderung character varying,
	in_betrieb character varying,
	betreiber character varying,
	bestandsplan_vorhanden character varying,
	durchmesser numeric,
	tatsaechliche_laenge numeric,
	datum_des_vertrages date,
	vertragsnummer character varying,
	detaillierungsgrad character varying,
	dokument character varying, --- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummerbereich character varying, -- Association
	hat_strecke character varying ,-- Association
	CONSTRAINT fk_leitung_lage_leitung FOREIGN KEY (lage_leitung)
		REFERENCES okstra.wlo_lage_leitung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_leitung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_art_detail FOREIGN KEY (art_detail)
		REFERENCES okstra.wlo_art_leitung_detail (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_material FOREIGN KEY (material)
		REFERENCES okstra.wlo_material_leitung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_material_schutzrohr FOREIGN KEY (material_schutzrohr)
		REFERENCES okstra.wlo_material_schutzrohr (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_betreiber FOREIGN KEY (betreiber)
		REFERENCES okstra.wlo_betreiber_leitung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_schutzrohr FOREIGN KEY (schutzrohr)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_beschilderung FOREIGN KEY (beschilderung)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_in_betrieb FOREIGN KEY (in_betrieb)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_leitung_bestandsplan_vorhanden FOREIGN KEY (bestandsplan_vorhanden)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.schutzeinrichtung_aus_stahl(
	kreuzungszuordnung character varying,
	unterhaltsbezug_sp character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	detaillierungsgrad character varying,
	multigeometrie geometry(geometry,25833),
	abstand_von_station numeric,
	abstand_bis_station numeric,
	lage character varying DEFAULT '99'::character varying,
	standort character varying,
	modulbezeichnung character varying NOT NULL default '99',
	systemname character varying NOT NULL default '99',
	gelaender character varying,
	mitwirkung_gelaender character varying,
	unterfahrschutz character varying,
	holmform character varying,
	pfostenform character varying,
	art_pfostenbefestigung character varying,
	ce_kennzeichnung integer,
	pfostenabstand numeric,
	schutzplankenpfostenummantelung character varying,
	blendschutzzaun character varying,
	grasstopplatten character varying,
	leitmale character varying,
	art_anfangs_endkonstruktion character varying,
	anzahl_aeks integer,
	herausnehmbar character varying,
	anordnungsdatum date,
	aufstelldatum date,
	tatsaechliche_laenge numeric,
	dokument character varying, --- Association
	hat_rechtliches_ereignis character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	zu_hausnummernblock character varying, -- Association
	zu_hausnummerbereich character varying, -- Association
	liegt_vor_uebergang character varying, -- Association
	liegt_hinter_uebergang character varying, -- Association
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_kreuzungszuordnung FOREIGN KEY (kreuzungszuordnung)
		REFERENCES okstra.wlo_kreuzungszuordnung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_detaillierungsgrad FOREIGN KEY (detaillierungsgrad)
		REFERENCES okstra.wlo_detaillierungsgrad_asb (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_standort FOREIGN KEY (standort)
		REFERENCES okstra.wlo_standort_rueckhaltesystem (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_modulbezeichnung FOREIGN KEY (modulbezeichnung)
		REFERENCES okstra.wlo_modulbezeichnung_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_systemname FOREIGN KEY (systemname)
		REFERENCES okstra.wlo_systemname_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_holmform FOREIGN KEY (holmform)
		REFERENCES okstra.wlo_holmform_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_pfostenform FOREIGN KEY (pfostenform)
		REFERENCES okstra.wlo_pfostenform_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_art_pfostenbefestigung FOREIGN KEY (art_pfostenbefestigung)
		REFERENCES okstra.wlo_art_pfostenbefestigung_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_art_anfangs_endkonstruktion FOREIGN KEY (art_anfangs_endkonstruktion)
		REFERENCES okstra.wlo_art_aek_schutzeinr_stahl (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_gelaender FOREIGN KEY (gelaender)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_mitwirkung_gelaender FOREIGN KEY (mitwirkung_gelaender)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_unterfahrschutz FOREIGN KEY (unterfahrschutz)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_schutzplankenpfostenummantelung FOREIGN KEY (schutzplankenpfostenummantelung)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_blendschutzzaun FOREIGN KEY (blendschutzzaun)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_grasstopplatten FOREIGN KEY (grasstopplatten)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_leitmale FOREIGN KEY (leitmale)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_schutzeinrichtung_aus_stahl_herausnehmbar FOREIGN KEY (herausnehmbar)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

-- The following tables are not found in the original db infrastruktur, but are found in the accompanying sql
CREATE TABLE okstra.anzahl_fahrstreifen(
	fahrstreifen_richtung integer NOT NULL DEFAULT 0,
	fahrstreifen_gegenrichtung integer NOT NULL DEFAULT 0,
	fahrstreifen_beide_richtungen integer NOT NULL DEFAULT 0,
	hat_objekt_id character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_anzahl_fahrstreifen_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.aufbauschicht(
	flaechengeometrie geometry(MultiPolygon, 25833),
	x_wert_von_station_links numeric,
	x_wert_bis_station_links numeric,
	x_wert_von_station_rechts numeric,
	x_wert_bis_station_rechts numeric,
	z_wert numeric NOT NULL DEFAULT 0,
	art character varying NOT NULL DEFAULT '0',
	material character varying,
	bindemittel_aufbauschicht character varying,
	detail_a character varying,
	detail_b character varying,
	detail_c character varying,
	detail_d character varying,
	zusatzschluessel character varying,
	dicke numeric NOT NULL,
	unscharfe_dicke boolean,
	oberste_deckschicht character varying,
	unvollstaendiger_aufbau boolean,
	abgefraeste_deckschicht character varying,
	verknuepfungsnummer character varying,
	einbaudatum date,
	herkunft_der_angaben character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_objekt_id character varying,
	dokument character varying, --- Association
	unter_schicht character varying, -- Association
	Schicht character varying, -- Association
	zu_querschnittstreifen character varying, -- Association
	zu_verkehrsflaeche character varying, -- Association
	CONSTRAINT fk_aufbauschicht_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_material FOREIGN KEY (material)
		REFERENCES okstra.wlo_material_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_bindemittel_aufbauschicht FOREIGN KEY (bindemittel_aufbauschicht)
		REFERENCES okstra.wlo_bindemittel_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_detail_a FOREIGN KEY (detail_a)
		REFERENCES okstra.wlo_detail_a_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_detail_b FOREIGN KEY (detail_b)
		REFERENCES okstra.wlo_detail_b_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_detail_c FOREIGN KEY (detail_c)
		REFERENCES okstra.wlo_detail_c_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_detail_d FOREIGN KEY (detail_d)
		REFERENCES okstra.wlo_detail_d_aufbauschicht (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_oberste_deckschicht FOREIGN KEY (oberste_deckschicht)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_abgefraeste_deckschicht FOREIGN KEY (abgefraeste_deckschicht)
		REFERENCES okstra.wlo_dreiwertige_logik (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_aufbauschicht_herkunft_der_angaben FOREIGN KEY (herkunft_der_angaben)
		REFERENCES okstra.wlo_herkunft_angaben_aufbau (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_anzahl_fahrstreifen_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.bahnigkeit(
	kennzeichen_bahnigkeit character varying NOT NULL DEFAULT '0',
	hat_objekt_id character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_bahnigkeit_kennzeichen_bahnigkeit FOREIGN KEY (kennzeichen_bahnigkeit)
		REFERENCES okstra.wlo_kennzeichen_bahnigkeit (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bahnigkeit_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bahnigkeit_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bahnigkeit_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_bahnigkeit_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.belastungsklasse(
	art character varying NOT NULL DEFAULT '01',
	belastungsklasse_gemaess_rsto character varying NOT NULL DEFAULT '00',
	belastungsklasse_sonst character varying,
	lage character varying DEFAULT '99'::character varying,
	verkehrsbelastungszahl numeric,
	ausgabejahr_der_richtinie date,
	datum_der_berechnung date,
	zu_befestiger_flaeche character varying, -- Association, possible spelling mistake is inside uml model and is therefore copied for model conformance
	hat_objekt_id character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_belastungsklasse_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_belastungsklasse (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_belastungsklasse_gemaess_rsto FOREIGN KEY (belastungsklasse_gemaess_rsto)
		REFERENCES okstra.wlo_belastungsklasse_rsto (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_belastungsklasse_sonst FOREIGN KEY (belastungsklasse_sonst)
		REFERENCES okstra.wlo_belastungsklasse_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_belastungsklasse_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.fahrstreifen_nummer(
	flaechengeometrie geometry(MultiPolygon, 25833),
	fahrstreifennummer integer,
	verkehrsrichtung character varying,
	auf_querschnittstreifen character varying, -- Association
	von_strassenbeschr_verkehrlich character varying, -- Association
	von_wegweisung_info character varying, -- Association
	von_zeb_objekt character varying, -- Association
	zu_verkehrsstau character varying, -- Association
	zu_verkehrslage character varying DEFAULT '99'::character varying, -- Association
	von_verkehrseinschraenkung character varying, -- Association
	zu_streckenbild character varying, -- Association
	zu_ganglinie character varying, -- Association
	zu_dtv character varying, -- Association
	CONSTRAINT fk_fahrstreifen_nummer_verkehrsrichtung FOREIGN KEY (verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- strassenbeschreibung_verkehrl is generalization of other classes, such as durchschnittsgeschwindigkeit, fkt_d_verb_im_knotenpktber, spur_fuer_rettungsfahrzeuge, strassenfunktion, gebuehrenpflichtig
CREATE TABLE okstra.strassenbeschreibung_verkehrl (
	im_zeitraum character varying,
	gilt_fuer_verkehrsrichtung character varying,
	gilt_fuer_fahrzeugart character varying,
	gilt_fuer_fahrstreifen character varying, -- Association
	hat_objekt_id character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_strassenbeschreibung_verkehrl_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenbeschreibung_verkehrl_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenbeschreibung_verkehrl_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenbeschreibung_verkehrl_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenbeschreibung_verkehrl_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenbeschreibung_verkehrl_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

-- inherits from strassenbeschreibung_verkehrl
CREATE TABLE okstra.durchschnittsgeschwindigkeit(
	km_h numeric NOT NULL,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_durchschnittsgeschwindigkeit_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.strassenbeschreibung_verkehrl);

-- inherits from strassenbeschreibung_verkehrl
CREATE TABLE okstra.fkt_d_verb_im_knotenpktber(
	funktion character varying NOT NULL,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_fkt_d_verb_im_knotenpktber_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.strassenbeschreibung_verkehrl);

-- inherits from strassenbeschreibung_verkehrl
CREATE TABLE okstra.spur_fuer_rettungsfahrzeuge(
	spur_fuer_rettungsfahrzeuge boolean NOT NULL,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_spur_fuer_rettungsfahrzeuge_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.strassenbeschreibung_verkehrl);

-- inherits from strassenbeschreibung_verkehrl
CREATE TABLE okstra.strassenfunktion(
	strassenfunktion character varying NOT NULL DEFAULT '',
	CONSTRAINT fk_strassenfunktion_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenfunktion_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenfunktion_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenfunktion_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenfunktion_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenfunktion_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.strassenbeschreibung_verkehrl);

-- inherits from strassenbeschreibung_verkehrl
CREATE TABLE okstra.gebuehrenpflichtig(
	gebuehrenpflicht boolean NOT NULL,
	CONSTRAINT fk_gebuehrenpflichtig_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_gebuehrenpflichtig_gilt_fuer_fahrzeugart FOREIGN KEY (gilt_fuer_fahrzeugart)
		REFERENCES okstra.wlo_fahrzeugart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_gebuehrenpflichtig_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_gebuehrenpflichtig_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_gebuehrenpflichtig_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_gebuehrenpflichtig_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (okstra.strassenbeschreibung_verkehrl);

-- hausnummer in OKSTRA technically inherits only directly from okstra_objekt. To unify the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.hausnummer(
	nummer integer NOT NULL,
	zusatzbuchstabe character varying,
	hat_hsnrbezugsobjekt character varying, -- Association
	beginn_von_hausnummernbereich character varying, -- Association
	in_hausnummernbereich character varying, -- Association
	ende_von_hausnummernbereich character varying, -- Association
	zu_unfallort character varying, -- Association
	zu_unfallort_einmuendend character varying, -- Association
	zu_strasse character varying, -- Association
	zu_kommunale_strasse character varying, -- Association
	zu_segment_kommunale_strasse character varying -- Association
)
INHERITS (base.basisobjekt);

-- kommunikationsobjekt in OKSTRA technically inherits only directly from okstra_objekt. To unify the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.kommunikationsobjekt(
	multigeometrie geometry(geometry,25833),
	beschreibung character varying NOT NULL DEFAULT ''::character varying,
	art_des_betroffenen_objekts character varying,
	datum date NOT NULL,
	uhrzeit time with time zone NOT NULL,
	sachbearbeiter character varying NOT NULL,
	status character varying,
	zu_okstra_objekt character varying -- Association
)
INHERITS (base.basisobjekt);

-- komplexer_knoten in OKSTRA technically inherits only directly from okstra_objekt. To unify the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.komplexer_knoten(
	art_komplexer_knoten character varying NOT NULL,
	hat_verbindungspunkt character varying NOT NULL, -- Association
	hat_strassenelement character varying NOT NULL, -- Association
	zu_netzbezugsobjekt_kompknoten character varying, -- Association
	CONSTRAINT fk_komplexer_knoten_art_komplexer_knoten FOREIGN KEY (art_komplexer_knoten)
		REFERENCES okstra.wlo_art_komplexer_knoten (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- Organisation in OKSTRA technically inherits only from okstra_objekt (indirectly over ansprechpartner). To unifty the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.organisation(
	name character varying NOT NULL DEFAULT ''::character varying,
	behoerdenkennung character varying,
	registernummer character varying,
	organisationsart character varying NOT NULL DEFAULT '99',
	anschrift_adresszeile_2 character varying, -- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_3 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_4 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_strasse character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_postfach character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_land_postalischer_code character varying,-- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_land_land character varying, -- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_postleitzahl character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_ort character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_nuts_code character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_kommunikationsdaten_kommunikationsadresse character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used kommunikationsadresse and kommunikationstyp be filled
	anschrift_kommunikationsdaten_kommunikationstyp character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_kommunikationsdaten_dienstlich_privat character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_typ_der_anschrift character varying,-- the attribute anschrift holds the complex data type adressdaten
	untergeordnete_organisation character varying, -- Association
	uebergeordnete_organisation character varying, -- Association
	hat_organisationseinheit character varying, -- Association
	hat_mitarbeiter character varying, -- Association
	ist_behoerde_in character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	CONSTRAINT fk_organisation_organisationsart FOREIGN KEY (organisationsart)
		REFERENCES okstra.wlo_organisationsart (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisation_anschrift_kommunikationsdaten_kommunikationstyp FOREIGN KEY (anschrift_kommunikationsdaten_kommunikationstyp)
		REFERENCES okstra.wlo_kommunikationstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisation_anschrift_kommunikationsdaten_dienstlich_privat FOREIGN KEY (anschrift_kommunikationsdaten_dienstlich_privat)
		REFERENCES okstra.wlo_dienstlich_privat (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisation_anschrift_typ_der_anschrift FOREIGN KEY (anschrift_typ_der_anschrift)
		REFERENCES okstra.wlo_anschriftstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- Organisationseinheit in OKSTRA technically inherits only from okstra_objekt (indirectly over ansprechpartner). To unifty the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.organisationseinheit(
	bezeichnung character varying NOT NULL DEFAULT ''::character varying,
	anschrift_adresszeile_2 character varying, -- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_3 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_4 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_strasse character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_postfach character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_land_postalischer_code character varying,-- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_land_land character varying, -- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_postleitzahl character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_ort character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_nuts_code character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_kommunikationsdaten_kommunikationsadresse character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used kommunikationsadresse and kommunikationstyp be filled
	anschrift_kommunikationsdaten_kommunikationstyp character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_kommunikationsdaten_dienstlich_privat character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_typ_der_anschrift character varying,-- the attribute anschrift holds the complex data type adressdaten
	untergeordnete_oe character varying, -- Association
	uebergeordnete_oe character varying, -- Association
	hat_organisationseinheit_von character varying, -- Association
	hat_person character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	CONSTRAINT fk_organisationseinheit_anschrift_kommunikationsdaten_kommunikationstyp FOREIGN KEY (anschrift_kommunikationsdaten_kommunikationstyp)
		REFERENCES okstra.wlo_kommunikationstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisationseinheit__anschrift_kommunikationsdaten_dienstlich_privat FOREIGN KEY (anschrift_kommunikationsdaten_dienstlich_privat)
		REFERENCES okstra.wlo_dienstlich_privat (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisationseinheit__anschrift_typ_der_anschrift FOREIGN KEY (anschrift_typ_der_anschrift)
		REFERENCES okstra.wlo_anschriftstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- Organisationseinheit in OKSTRA technically inherits only from okstra_objekt (indirectly over ansprechpartner). To unifty the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.person(
	personenklasse character varying,
	titel character varying,
	name character varying NOT NULL DEFAULT ''::character varying,
	vorname character varying,
	firma character varying,
	abteilung character varying,
	geburtsdatum date,
	geburtsname character varying,
	anrede character varying,
	bankverbindung_kontonummer character varying,-- the attribute bankverbindung holds the complex data type bankverbindung. If bankverbindung is used, kontonummer and bankleitzahl must be filled
	bankverbindung_bankleitzahl character varying,-- the attribute bankverbindung holds the complex data type bankverbindung. If bankverbindung is used, kontonummer and bankleitzahl must be filled
	bankverbindung_bankname character varying,-- the attribute bankverbindung holds the complex data type bankverbindung. If bankverbindung is used, kontonummer and bankleitzahl must be filled
	kommunikationsdaten_kommunikationsadresse character varying,-- the attribute kommunikationsdaten holds the complex data type kommunikationsdaten. If kommunkationsdaten is used kommunikationsadresse and kommunikationstyp be filled
	kommunikationsdaten_kommunikationstyp character varying,-- the attribute kommunikationsdaten holds the complex data type kommunikationsdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	kommunikationsdaten_dienstlich_privat character varying,-- the attribute kommunikationsdaten holds the complex data type kommunikationsdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	-- bemerkung character varying, -- already inherited from basisobjekt
	anschrift_adresszeile_2 character varying, -- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_3 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_adresszeile_4 character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_strasse character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_postfach character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_land_postalischer_code character varying,-- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_land_land character varying, -- the attribute anschrift holds the complex data type adressdaten. If land is used, land_land must be filled
	anschrift_postleitzahl character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_ort character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_nuts_code character varying,-- the attribute anschrift holds the complex data type adressdaten
	anschrift_kommunikationsdaten_kommunikationsadresse character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used kommunikationsadresse and kommunikationstyp be filled
	anschrift_kommunikationsdaten_kommunikationstyp character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_kommunikationsdaten_dienstlich_privat character varying,-- the attribute anschrift holds the complex data type adressdaten. If kommunkationsdaten is used, kommunikationsadresse and kommunikationstyp must be filled
	anschrift_typ_der_anschrift character varying,-- the attribute anschrift holds the complex data type adressdaten
	von_organisationseinheit character varying, -- Association
	ist_mitarbeiter_von character varying, -- Association
	hat_zustaendigkeit character varying, -- Association
	ist_belastungsberechtigter character varying, -- Association
	ist_eigentuemer_grundbuch character varying, -- Association
	ist_paechter_mieter character varying, -- Association
	hat_stat_arbeitsst_erfasst character varying, -- Association
	zu_rolle_arbeitsstelle character varying, -- Association
	zu_flurstueck character varying, -- Association
	CONSTRAINT fk_person_personenklasse FOREIGN KEY (personenklasse)
		REFERENCES okstra.wlo_personenklasse (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisationseinheit_anschrift_kommunikationsdaten_kommunikationstyp FOREIGN KEY (anschrift_kommunikationsdaten_kommunikationstyp)
		REFERENCES okstra.wlo_kommunikationstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisationseinheit__anschrift_kommunikationsdaten_dienstlich_privat FOREIGN KEY (anschrift_kommunikationsdaten_dienstlich_privat)
		REFERENCES okstra.wlo_dienstlich_privat (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_organisationseinheit__anschrift_typ_der_anschrift FOREIGN KEY (anschrift_typ_der_anschrift)
		REFERENCES okstra.wlo_anschriftstyp (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- segment_kommunale_strasse in OKSTRA inherits only from okstra_objekt. To unifty the model, additional values (e.g. historical dates) can still be represented.
CREATE TABLE okstra.segment_kommunale_strasse (
	segmentschluessel character varying NOT NULL DEFAULT ''::character varying,
	zu_kommunale_strasse character varying NOT NULL, -- Association
	hat_strassenbezugsobjekt character varying -- ASSOCIATION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.stadium (
	unter_verkehr character varying NOT NULL DEFAULT '0',
	stadium character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_stadium_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_stadium_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_stadium_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_stadium_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.strassenelement (
	liniengeometrie geometry(linestring,25833),
	gdf_id character varying,
	verkehrsrichtung character varying,
	laenge numeric,
	stufe character varying,
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_objekt_id character varying,
	zwischen_kreuzungsbereichen character varying, --Association
	im_kreuzungsbereich character varying, --Association
	hat_teilelement character varying, --Association
	beginnt_bei_vp character varying, --Association
	endet_bei_vp character varying, --Association
	in_nullpunkt character varying, --Association
	in_komplexem_knoten character varying, --Association
	CONSTRAINT fk_strassenelement_verkehrsrichtung FOREIGN KEY (verkehrsrichtung)
		REFERENCES okstra.wlo_verkehrsrichtung_se (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_strassenelement_stufe FOREIGN KEY (stufe)
		REFERENCES okstra.wlo_stufe_strassenelement (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

-- Strassenelementpunkt is a datatype in OKSTRA. Due to its importance for the geometry of streets for possible expansion, it is modelled as its own table
CREATE TABLE okstra.strassenelementpunkt (
	punktgeometrie geometry(point, 25833), -- Not in the original model, but added here
	station numeric NOT NULL,
	abstand_zur_bestandsachse numeric,
	abstand_zur_fahrbahnoberkante numeric,
	auf_strassenelement character varying NOT NULL,--Association
	CONSTRAINT pk_strassenelementpunkt_id PRIMARY KEY (id)
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.teilelement (
	beginnt_bei_strassenelempkt character varying NOT NULL, --- beginnt_bei_strassenelempkt is a complex data type. Due to its importance in visualization and its possible expansion, it is held in its own table
	endet_bei_strassenelempkt character varying NOT NULL, -- endet_bei_strassenelempkt is a complex data type. Due to its importance in visualization and its possible expansion, it is held in its own table
	--beginnt_bei_strassenelempkt_station numeric NOT NULL, -- beginnt_bei_strassenelempkt holds the the complex data type strassenelementpunkt. Since beginnt_bei_strassenelempkt must always be filled, station must also always be filled
	--beginnt_bei_strassenelempkt_abstand_zur_bestandsachse numeric, -- beginnt_bei_strassenelempkt holds the complex data type strassenelementpunkt.
	--beginnt_bei_strassenelempkt_abstand_zur_fahrbahnoberkante numeric, -- beginnt_bei_strassenelempkt holds the complex data type strassenelementpunkt.
	--beginnt_bei_strassenelempkt_auf_strassenelement character varying NOT NULL,  -- Association
	--endet_bei_strassenelempkt_station numeric NOT NULL, -- beginnt_bei_strassenelempkt holds the the complex data type strassenelementpunkt. Since beginnt_bei_strassenelempkt must always be filled, station must also always be filled
	--endet_bei_strassenelempkt_abstand_zur_bestandsachse numeric, -- beginnt_bei_strassenelempkt holds the complex data type strassenelementpunkt.
	--endet_bei_strassenelempkt_abstand_zur_fahrbahnoberkante numeric, -- beginnt_bei_strassenelempkt holds the complex data type strassenelementpunkt.
	--endet_bei_strassenelempkt_auf_strassenelement character varying NOT NULL,  -- Association
	auf_strasselement character varying NOT NULL, -- Association
	altes_netzteil character varying, -- Association
	neues_netzteil character varying, -- Association
	zu_streckenobjekt character varying, -- Association
	in_strecke character varying, -- Association
	in_netzbereich character varying, -- Association
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_teilelement_beginnt_bei_strassenelempkt FOREIGN KEY (beginnt_bei_strassenelempkt)
		REFERENCES okstra.strassenelementpunkt (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_endet_bei_strassenelempkt FOREIGN KEY (endet_bei_strassenelempkt)
		REFERENCES okstra.strassenelementpunkt (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.verbindungspunkt (
	punktgeometrie geometry(point, 25833),
	nummerierungsbezirk character varying,
	kennung_gemeinde character varying,
	nummer integer,
	gdf_id character varying,
	ist_strassenpunkt_station numeric, -- ist_strassenpunkt refers to the complex datatype strassenpunkt. If strassenpunkt is used, station must be filled
	ist_strassenpunkt_abstand_zur_bestandsachse numeric, -- ist_strassenpunkt refers to the complex datatype strassenpunkt. If strassenpunkt is used, station must be filled
	ist_strassenpunkt_abstand_zur_fahrbahnoberkante numeric, -- ist_strassenpunkt refers to the complex datatype strassenpunkt. If strassenpunkt is used, station must be filled
	ende_von_strassenelement character varying, -- Association
	beginn_von_strassenelement character varying, -- Association
	in_nullpunkt character varying, -- Association
	zu_netzbezugsobjekt_vpunkt character varying, -- Association
	in_komplexem_knoten character varying, -- Association
	beginn_von_verbotener_fahrbez character varying, -- Association
	in_verbotener_fahrbez character varying, -- Association
	ende_von_verbotener_fahrbez character varying, -- Association
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_verbindungspunkt_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbindungspunkt_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbindungspunkt_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbindungspunkt_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.verbotene_fahrbeziehung (
	fuer_art_verkehrsnutzung character varying,
	ueber_strassenknoten character varying NOT NULL, --Association
	von_strassenknoten character varying NOT NULL, --Association
	nach_strassenknoten character varying NOT NULL, --Association
	ueber_strassenkante character varying, --Association
	von_strassenkante character varying, --Association
	nach_strassenkante character varying, --Association
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_verbotene_fahrbeziehung_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbotene_fahrbeziehung_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbotene_fahrbeziehung_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbotene_fahrbeziehung_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verbotene_fahrbeziehung_fuer_art_verkehrsnutzung FOREIGN KEY (fuer_art_verkehrsnutzung)
		REFERENCES okstra.wlo_verkehrsteilnehmergruppe (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.verkehrseinschraenkung (
	lage character varying DEFAULT '99'::character varying,
	querschnitt_streifenart character varying,
	art character varying not null,
	stvo_znr_art character varying,
	verkehrsrichtung character varying,
	verkehrsteilnehmergruppe character varying,
	stvo_znr_gruppe character varying,
	umfang_der_einschraenkung_hoechs_mind_geschwindigkeit numeric, -- umfang_der_einschraenkung holds complex type umfang_ves. if any attribute of umfang_der_einschraenkung ist filled, all attributs of umfang_der_einschraenkung must be filled
	umfang_der_einschraenkung_lastbeschraenkung numeric, -- umfang_der_einschraenkung holds complex type umfang_ves. if any attribute of umfang_der_einschraenkung ist filled, all attributs of umfang_der_einschraenkung must be filled
	umfang_der_einschraenkung_massbeschraenkung numeric, -- umfang_der_einschraenkung holds complex type umfang_ves. if any attribute of umfang_der_einschraenkung ist filled, all attributs of umfang_der_einschraenkung must be filled
	umfang_der_einschraenkung_laenge_verbotsstrecke numeric, -- umfang_der_einschraenkung holds complex type umfang_ves. if any attribute of umfang_der_einschraenkung ist filled, all attributs of umfang_der_einschraenkung must be filled
	stvo_znr_einschraenkung character varying,
	gueltigkeit character varying,
	stvo_znr_gueltigkeit character varying,
	wochentag character varying,
	einschraenkung_gueltig_von timestamp,
	einschraenkung_gueltig_bis timestamp,
	gilt_fuer_fahrstreifen character varying, -- Association
	hat_schild character varying, -- Association
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	hat_dokument character varying, --Association
	CONSTRAINT fk_verkehrseinschraenkung_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_lage FOREIGN KEY (lage)
		REFERENCES okstra.wlo_lage (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_querschnitt_streifenart FOREIGN KEY (querschnitt_streifenart)
		REFERENCES okstra.wlo_querschnitt_streifenart_ves (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_art FOREIGN KEY (art)
		REFERENCES okstra.wlo_art_ves (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_verkehrsrichtung FOREIGN KEY (verkehrsrichtung)
		REFERENCES okstra.wlo_bezugsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_verkehrsteilnehmergruppe FOREIGN KEY (verkehrsteilnehmergruppe)
		REFERENCES okstra.wlo_verkehrsteilnehmergruppe (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_gueltigkeit FOREIGN KEY (gueltigkeit)
		REFERENCES okstra.wlo_gueltigkeit_ves (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrseinschraenkung_wochentag FOREIGN KEY (wochentag)
		REFERENCES okstra.wlo_wochentag_ves (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.verkehrsflaeche (
	flaeche geometry(MultiPolygon,25833),
	hat_flaechenbezugsobjekt character varying, -- Association
	besteht_aus_querschnittstreifen character varying, -- Association
	hat_verkehrsnutzungsflaeche character varying, -- Association
	zu_kommunale_strasse character varying, -- Association
	zu_segment_kommunale_strasse character varying, -- Association
	zu_strasse character varying -- Association
)
INHERITS (base.basisobjekt);

CREATE TABLE okstra.verkehrsnutzungsbereich (
	gilt_fuer_verkehrsrichtung character varying,
	entspricht_nutzungsflaeche character varying, -- Association
	art_der_verkehrsnutzung character varying NOT NULL DEFAULT '99',
	zu_hausnummer character varying, -- Association,
	zu_hausnummernbereich character varying, -- Association
	zu_hausnummernblock character varying, -- Associaton
	CONSTRAINT fk_verkehrsnutzungsbereich_gilt_fuer_verkehrsrichtung FOREIGN KEY (gilt_fuer_verkehrsrichtung)
		REFERENCES okstra.wlo_orientierungsrichtung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_verkehrsnutzungsbereich_art_der_verkehrsnutzung FOREIGN KEY (art_der_verkehrsnutzung)
		REFERENCES okstra.wlo_verkehrsteilnehmergruppe (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.streckenobjekt);

CREATE TABLE okstra.zustaendigkeit (
	art_zustaendigkeit character varying NOT NULL,
	beginn_der_zustaendigkeit date,
	ende_der_zustaendigkeit date,
	--bemerkung character varying, -- bemerkung is already inherited from basisobjekt. both are functionally equivalent, though basisobjekt.bemerkung is technically a doppik element.
	multigeometrie geometry(geometry,25833),
	hat_zustaendigen character varying NOT NULL, -- Association
	fuer_objekt character varying, -- Association
	hat_dokument character varying, -- Association
	geometrie_bereichsobjekt geometry(MultiLineString, 25833),
	hat_netzbereich character varying, -- Associaton
	zu_querschnittstreifen character varying, -- Association
	zu_verkehrsflaeche character varying, -- Association
	erfassungsdatum date,
	systemdatum date,
	textfeld character varying,
	art_der_erfassung character varying DEFAULT '00'::character varying,
	art_der_erfassung_sonst character varying,
	quelle_der_information character varying DEFAULT '00'::character varying,
	quelle_der_information_sonst character varying,
	rfid character varying,
	migrationshinweise character varying,
	unscharf boolean,
	-- hat_dokument character varying, --Association (commented out, because it exists more than once (inherited from asb_objekt and flaechenbezugsobjekt)
	CONSTRAINT fk_teilelement_art_der_erfassung FOREIGN KEY (art_der_erfassung)
		REFERENCES okstra.wlo_art_der_erfassung (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_art_der_erfassung_sonst FOREIGN KEY (art_der_erfassung_sonst)
		REFERENCES okstra.wlo_art_der_erfassung_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_quelle_der_information FOREIGN KEY (quelle_der_information)
		REFERENCES okstra.wlo_quelle_der_information (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_teilelement_quelle_der_information_sonst FOREIGN KEY (quelle_der_information_sonst)
		REFERENCES okstra.wlo_quelle_der_information_sonst (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
	CONSTRAINT fk_zustaendigkeit_art_zustaendigkeit FOREIGN KEY (art_zustaendigkeit)
		REFERENCES okstra.wlo_art_zustaendigkeit (kennung) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
)
INHERITS (base.basisobjekt);

-- OKSTRA Zuordnungstables
-- Is equivalent to associations zu_streckenobjekt / hat_strecke between querschnittstreifen and teilelement (inherited from streckenobjekt and verallgemeinerte_strecke)
CREATE TABLE okstra.querschnittstreifen_to_teilelement (
	querschnittstreifen_id character varying NOT NULL,
	teilelement_id character varying NOT NULL
);


---------------------------------------------------------------
---------------------------------------------------------------
-- CREATE CONCRETE OBJECTS
---------------------------------------------------------------
---------------------------------------------------------------
-- concrete objects inherit from abstract okstra tables, which in turn inherit from the base.basisobjekt
CREATE TABLE doppik.lampe
(
	material character varying NOT NULL,
	masttyp character varying,
	mastlaenge numeric,
	hersteller character varying,
	leistung integer,
	vorschaltgeraet character varying,
	abschaltung character varying,
	leuchtmittel character varying,
	anzahl_leuchtmittel integer,
	stromkreis integer,
	dimmungssystem character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_lampe_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.lampe
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.lampe
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.lampe
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.lampe
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.baum
(
	--lage character varying DEFAULT '99'::character varying,
	--baumgattung character varying,
	--baumart character varying,
	--stammumfang numeric,
	--stammdurchmesser numeric,
	--kronendurchmesser numeric,
	--wurzelhalsdurchmesser numeric,
	--stammhoehe numeric,
	--baumhoehe numeric,
	--baumscheibe character varying,
	--pflanzjahr date,
	--gefaellt boolean,
	--datum_der_faellung date,
	--letzte_baumschau date,
	--schiefstand numeric,
	--zustandsbeurteilung character varying, -- already available in okstra.baum, will be inherited
	--lagebeschreibung character varying,
	--detaillierungsgrad character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_baum_id PRIMARY KEY (id)
)
INHERITS(okstra.baum);

-- Trigger: tr_idents_add_ident on doppik.baum
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.baum
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.baum
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.baum
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bewuchs
(
	-- laenge numeric,
	breite numeric,
	hoehe numeric,
	-- schutzstatus character varying,
	-- zustaendigkeit character varying,
	-- verkehrsraumeinschraenkung character varying,
	--erfassungsqualitaet character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bewuchs_id PRIMARY KEY (id)
)
INHERITS(okstra.bewuchs);

-- Trigger: tr_idents_add_ident on doppik.bewuchs
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bewuchs
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bewuchs
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.blumenkuebel
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_blumenkuebel_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.blumenkuebel
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.blumenkuebel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.blumenkuebel
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.blumenkuebel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.stationaere_geschwindigkeitsueberwachung
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_stationaere_geschwindigkeitsueberwachung_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.stationaere_geschwindigkeitsueberwachung
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.stationaere_geschwindigkeitsueberwachung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.stationaere_geschwindigkeitsueberwachung
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.stationaere_geschwindigkeitsueberwachung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.verkehrsspiegel
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_verkehrsspiegel_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.verkehrsspiegel
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.verkehrsspiegel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.verkehrsspiegel
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.verkehrsspiegel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.leitpfosten
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_leitpfosten_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.leitpfosten
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.leitpfosten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.leitpfosten
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.leitpfosten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.kilometerstein
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_kilometerstein_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.kilometerstein
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.kilometerstein
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.kilometerstein
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.kilometerstein
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.anleger
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_anleger_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.anleger
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.anleger
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.anleger
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.anleger
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.anschlagsaeule
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_anschlagsaeule_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.anschlagsaeule
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.anschlagsaeule
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.anschlagsaeule
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.anschlagsaeule
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bank
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bank_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.bank
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bank
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bank
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bank
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bruecke
(
	material character varying,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bruecke_id PRIMARY KEY (id)
)
INHERITS(okstra.bruecke);

-- Trigger: tr_idents_add_ident on doppik.bruecke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bruecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bruecke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bruecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.dalben
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_dalben_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.dalben
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.dalben
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.dalben
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.dalben
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.denkmal
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_denkmale_id PRIMARY KEY (id)
)
INHERITS(okstra.teilbauwerk);

-- Trigger: tr_idents_add_ident on doppik.denkmal
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.denkmal
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.denkmal
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.fahne
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_fahne_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.fahne
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.fahne
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.fahne
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.fahne
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.infoterminal
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_infoterminal_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.infoterminal
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.infoterminal
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.infoterminal
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.infoterminal
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.kunstwerk
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_kunstwerk_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.kunstwerk
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.kunstwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.kunstwerk
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.kunstwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.schaukasten
(
	material character varying,
	standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_schaukasten_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.schaukasten
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.schaukasten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.schaukasten
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.schaukasten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.spielgeraet
(
	material character varying,
	standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_spielgeraet_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.spielgeraet
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.spielgeraet
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.spielgeraet
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.spielgeraet
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.tunnel
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_tunnel_id PRIMARY KEY (id)
)
INHERITS(okstra.tunnel_trogbauwerk);

-- Trigger: tr_idents_add_ident on doppik.tunnel
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.tunnel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.tunnel
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.tunnel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.uhr
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_uhr_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.uhr
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.uhr
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.uhr
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.uhr
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.ampel
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_ampel_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.ampel
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.ampel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.ampel
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.ampel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.fahrradstaender
(
	material character varying,
	anzahl_der_buegel integer,
	buegel_mit_querholm boolean,
	laenge_der_anlage numeric,
	breite_der_anlage numeric,
	analge_ueberdacht boolean,
	ident character(6) NOT NULL,
	CONSTRAINT pk_fahrradstaender_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.fahrradstaender
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.fahrradstaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.fahrradstaender
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.fahrradstaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.hinweistafel
(
	material character varying,
	standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_hinweistafel_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.hinweistafel
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.hinweistafel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.hinweistafel
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.hinweistafel
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.parkscheinautomat
(
	material character varying,
	standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_parkscheinautomat_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.parkscheinautomat
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.parkscheinautomat
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.parkscheinautomat
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.parkscheinautomat
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.poller
(
	material character varying,
	standort character varying,
	klappbare_art boolean,
	elektrische_funktion character varying,
	herausnehmbare_installation boolean,
	ident character(6) NOT NULL,
	CONSTRAINT pk_poller_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.poller
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.poller
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.poller
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.poller
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.schranke
(
	material character varying,
	standort character varying,
	funktion character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_schranke_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.schranke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.schranke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.schranke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.schranke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.aufstellvorrichtung_schild
(
	--material character varying,
	--nummer_aufstellvorrichtung integer,
	--abstand_linker_pfosten numeric,
	--abstand_rechter_pfosten numeric,
	--position_linker_pfosten geometry(Point,25833),
	--position_rechter_pfosten geometry(Point,25833),
	--lage character varying DEFAULT '99'::character varying,
	--art character varying,
	--durchmesser numeric,
	--hoehe numeric,
	--detaillierungsgrad character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_aufstellvorrichtung_schild_id PRIMARY KEY (id)
)
INHERITS(okstra.aufstellvorrichtung_schild);

-- Trigger: tr_idents_add_ident on doppik.aufstellvorrichtung_schild
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.aufstellvorrichtung_schild
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.aufstellvorrichtung_schild
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.aufstellvorrichtung_schild
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.schild
(
	material character varying,
	stvo_znr character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_schild_id PRIMARY KEY (id)
)
INHERITS(okstra.schild);

-- Trigger: tr_idents_add_ident on doppik.schild
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.schild
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.schild
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.schild
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.wartestelle
(
	material character varying,
	fahrtrichtung character varying,
	art_der_haltestelle character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_wartestelle_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.wartestelle
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.wartestelle
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.wartestelle
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.wartestelle
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.brunnen
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_brunnen_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.brunnen
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.brunnen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.brunnen
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.brunnen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.strassenablauf
(
	material character varying,
	art_des_ablaufes character varying,
	abmasse_abdeckung character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_strassenablauf_id PRIMARY KEY (id)
)
INHERITS(okstra.strassenablauf);

-- Trigger: tr_idents_add_ident on doppik.strassenablauf
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.strassenablauf
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.strassenablauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.kabelkasten
(
	material character varying,
	zugehoeriges_kabelnetz character varying,
	standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_kabelkasten_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.kabelkasten
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.kabelkasten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.kabelkasten
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.kabelkasten
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.kabelschacht
(
    material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_kabelschacht_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.kabelschacht
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.kabelschacht
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.kabelschacht
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.kabelschacht
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.abfallbehaelter
(
	ident character(6) NOT NULL,
	CONSTRAINT pk_abfallbehaelter_id PRIMARY KEY (id)
)
INHERITS(okstra.abfallentsorgung);

-- Trigger: tr_idents_add_ident on doppik.abfallbehaelter
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.abfallbehaelter
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.abfallbehaelter
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.abfallbehaelter
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.schacht
(
	material character varying,
	medium character varying,
	bauform character varying,
	abmasse character varying,
	sonstige_unterhaltungspflichtige character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_schacht_id PRIMARY KEY (id)
)
INHERITS(okstra.schacht);

-- Trigger: tr_idents_add_ident on doppik.schacht
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.schacht
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.schacht
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.schacht
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.sonstiges_punktobjekt
(
	material character varying,
	kurzbeschreibung_punktobjekt character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_sonstiges_punktobjekt_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.sonstiges_punktobjekt
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.sonstiges_punktobjekt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.sonstiges_punktobjekt
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.sonstiges_punktobjekt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.durchlass
(
	material character varying,
	pflasterflaeche numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_durchlass_id PRIMARY KEY (id)
)
INHERITS(okstra.durchlass);

-- Trigger: tr_idents_add_ident on doppik.durchlass
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.durchlass
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.durchlass
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bord_strecke
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bord_strecke_id PRIMARY KEY (id)
)
INHERITS(okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.bord_strecke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bord_strecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bord_strecke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bord_strecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.gelaender
(
	material character varying,
	breite numeric,
	hoehe numeric,
	bauart character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_gelaender_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.gelaender
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.gelaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.gelaender
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.gelaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.schutzplanke
(
	material character varying,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_schutzplanke_id PRIMARY KEY (id)
)
INHERITS(okstra.schutzeinrichtung_aus_stahl);

-- Trigger: tr_idents_add_ident on doppik.schutzplanke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.schutzplanke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.schutzplanke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.schutzplanke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.leitung
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_leitung_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.leitung
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.leitung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.leitung
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.leitung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.mauer
(
	material character varying,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_mauer_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.mauer
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.mauer
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.mauer
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.mauer
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.rinne
(
	material character varying,
	laenge numeric,
	breite numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_rinne_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.rinne
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.rinne
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.rinne
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.rinne
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.zaun
(
	laenge numeric,
	breite numeric,
	hoehe numeric,
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_zaun_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.zaun
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.zaun
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.zaun
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.zaun
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.sonstige_linie
(
	material character varying,
	laenge numeric,
	breite numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_sonstige_linie_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.sonstige_linie
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.sonstige_linie
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.sonstige_linie
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.sonstige_linie
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bankett
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '301', --aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bankett_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.bankett ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.bankett ALTER COLUMN streifenart SET DEFAULT '301';

-- Trigger: tr_idents_add_ident on doppik.bankett
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bankett
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bankett
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bankett
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.baumscheibe
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '751', --aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_baumscheibe_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.baumscheibe ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.baumscheibe ALTER COLUMN streifenart SET DEFAULT '751';

-- Trigger: tr_idents_add_ident on doppik.baumscheibe
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.baumscheibe
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.baumscheibe
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.baumscheibe
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.gehweg
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '210', --aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_gehweg_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.gehweg ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.gehweg ALTER COLUMN streifenart SET DEFAULT '210';

-- Trigger: tr_idents_add_ident on doppik.gehweg
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.gehweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.gehweg
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.gehweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.gruenflaeche
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_gruenflaeche_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.gruenflaeche
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.gruenflaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.gruenflaeche
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.gruenflaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.hecke
(
	flaeche numeric,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	ident character(6) NOT NULL,
	CONSTRAINT pk_hecke_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.hecke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.hecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.hecke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.hecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.platz
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_platz_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.platz
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.platz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.platz
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.platz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.parkplatz
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_parkplatz_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.parkplatz
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.parkplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.parkplatz
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.parkplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.parkstreifen
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_parkstreifen_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.parkstreifen
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.parkstreifen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.parkstreifen
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.parkstreifen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.rad_und_gehweg
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '210', --aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_rad_und_gehweg_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.rad_und_gehweg ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.rad_und_gehweg ALTER COLUMN streifenart SET DEFAULT '210';

-- Trigger: tr_idents_add_ident on doppik.rad_und_gehweg
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.rad_und_gehweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.rad_und_gehweg
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.rad_und_gehweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.radweg
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '240',---aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_radweg_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.radweg ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.radweg ALTER COLUMN streifenart SET DEFAULT '240';

-- Trigger: tr_idents_add_ident on doppik.radweg
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.radweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.radweg
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.radweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.fahrbahn
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '100',--aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	von_netzknoten character varying,
	nach_netzknoten character varying,
	anzahl_fahrspuren_in_fahrtrichtung integer,
	anzahl_fahrspuren_in_gegenrichtung integer,
	ident character(6) NOT NULL,
	CONSTRAINT pk_fahrbahn_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.fahrbahn ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.fahrbahn ALTER COLUMN streifenart SET DEFAULT '100';

-- Trigger: tr_idents_add_ident on doppik.fahrbahn
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.fahrbahn
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.fahrbahn
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.fahrbahn
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.strassengraben
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '520',---aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_strassengraben_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.strassengraben ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.strassengraben ALTER COLUMN streifenart SET DEFAULT '520';

-- Trigger: tr_idents_add_ident on doppik.strassengraben
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.strassengraben
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.strassengraben
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.strassengraben
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.ueberfahrt
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_ueberfahrt_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.ueberfahrt
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.ueberfahrt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.ueberfahrt
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.ueberfahrt
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.bord_flaeche
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '640',--aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_bord_flaeche_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.bord_flaeche ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.bord_flaeche ALTER COLUMN streifenart SET DEFAULT '640';

-- Trigger: tr_idents_add_ident on doppik.bord_flaeche
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.bord_flaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.bord_flaeche
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.bord_flaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.dammschuettung
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '700', --aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	standort character varying,
	material character varying,
	laenge numeric,
	dammfussbreite numeric,
	dammkronenbreite numeric,
	hoehe numeric,
	zweck character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_dammschuettung_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.dammschuettung ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.dammschuettung ALTER COLUMN streifenart SET DEFAULT '700';

-- Trigger: tr_idents_add_ident on doppik.dammschuettung
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.dammschuettung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.dammschuettung
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.dammschuettung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.spielplatz
(
	standort character varying,
	material character varying,
	zweck character varying,
	deckschicht character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_spielplatz_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.spielplatz
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.spielplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.spielplatz
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.spielplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.sportplatz
(
	standort character varying,
	material character varying,
	zweck character varying,
	deckschicht character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_sportplatz_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.sportplatz
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.sportplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.sportplatz
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.sportplatz
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.strasse
(
	-- streifenart okstra.wlo_streifenart NOT NULL default '100',--aready an attribute in querschnittstreifen, therefore setting the CONSTRAINT with an alter table
	standort character varying,
	material character varying,
	zweck character varying,
	bauklasse character varying,
	deckschicht character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_strasse_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);
ALTER TABLE doppik.strasse ALTER COLUMN streifenart SET NOT NULL;
ALTER TABLE doppik.strasse ALTER COLUMN streifenart SET DEFAULT '100';

-- Trigger: tr_idents_add_ident on doppik.strasse
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.strasse
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.strasse
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.strasse
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.sonstige_flaeche
(
	flaeche numeric,
	deckschicht character varying,
	ausbauzustand character varying,
	bauklasse character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_sonstige_flaeche_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.sonstige_flaeche
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.sonstige_flaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.sonstige_flaeche
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.sonstige_flaeche
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.ueberwachungsanlage
(
    material character varying,
    standort character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_ueberwachungsanlage_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.ueberwachungsanlage
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.ueberwachungsanlage
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.ueberwachungsanlage
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.ueberwachungsanlage
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.ueberweg
(
    flaeche numeric,
    deckschicht character varying,
    ausbauzustand character varying,
    bauklasse character varying,
    ident character(6) NOT NULL,
	CONSTRAINT pk_ueberweg_id PRIMARY KEY (id)
)
INHERITS (okstra.querschnittstreifen);

-- Trigger: tr_idents_add_ident on doppik.ueberweg
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.ueberweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.ueberweg
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.ueberweg
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.verkehrszeichenbruecke
(
	material character varying,
	durchfahrtsweite numeric,
	standort character varying,
	durchfahrtshohe numeric,
	fahrtrichtung character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_verkehrszeichenbruecke_id PRIMARY KEY (id)
)
INHERITS (okstra.verkehrszeichenbruecke);

-- Trigger: tr_idents_add_ident on doppik.verkehrszeichenbruecke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.verkehrszeichenbruecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.verkehrszeichenbruecke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.verkehrszeichenbruecke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.vorwegweiser
(
	breite_schild numeric,
	material character varying,
	standort character varying,
	laenge_schild numeric,
	fahrtrichtung character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_vorwegweiser_id PRIMARY KEY (id)
)
INHERITS (okstra.verkehrszeichenbruecke);

-- Trigger: tr_idents_add_ident on doppik.vorwegweiser
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.vorwegweiser
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.vorwegweiser
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.vorwegweiser
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.ueberdachung_fahrradstaender
(
	material character varying,
	standort character varying,
	laenge numeric,
	fahrtrichtung character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_ueberdachung_fahrradstaender_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.ueberdachung_fahrradstaender
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.ueberdachung_fahrradstaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.ueberdachung_fahrradstaender
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.ueberdachung_fahrradstaender
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.stuetzbauwerk
(
	material character varying,
	standort character varying,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	sichtbare_bauwerksflaeche character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_stuetzbauwerk_id PRIMARY KEY (id)
)
INHERITS(okstra.stuetzbauwerk);

-- Trigger: tr_idents_add_ident on doppik.stuetzbauwerk
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.stuetzbauwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.stuetzbauwerk
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.stuetzbauwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.laermschutzbauwerk
(
	material character varying,
	standort character varying,
	laenge numeric,
	breite numeric,
	hoehe numeric,
	sichtbare_bauwerksflaeche character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_laermschutzbauwerk_id PRIMARY KEY (id)
)
INHERITS(okstra.laermschutzbauwerk);

-- Trigger: tr_idents_add_ident on doppik.laermschutzbauwerk
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.laermschutzbauwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.laermschutzbauwerk
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.laermschutzbauwerk
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.hydrant
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_hydrant_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.hydrant
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.hydrant
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.hydrant
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.hydrant
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.loeschwasserentnahmestelle_saugstutzen
(
	material character varying,
	dimension_saugstutzen character varying,
	gewaesser character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_loeschwasserentnahmestelle_saugstutzen_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.loeschwasserentnahmestelle_saugstutzen
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.loeschwasserentnahmestelle_saugstutzen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.loeschwasserentnahmestelle_saugstutzen
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.loeschwasserentnahmestelle_saugstutzen
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.auslauf
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_auslauf_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.auslauf
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.auslauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.auslauf
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.auslauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.dueker
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_dueker_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_strecke);

-- Trigger: tr_idents_add_ident on doppik.dueker
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.dueker
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.dueker
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.dueker
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.einlauf
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_einlauf_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.einlauf
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.einlauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.einlauf
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.einlauf
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.klaeranlage
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_klaeranlage_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.klaeranlage
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.klaeranlage
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.klaeranlage
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.klaeranlage
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.leitplanke
(
	laenge numeric,
	breite numeric,
	hoehe numeric,
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_leitplanke_id PRIMARY KEY (id)
)
INHERITS(okstra.schutzeinrichtung_aus_stahl);

-- Trigger: tr_idents_add_ident on doppik.leitplanke
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.leitplanke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.leitplanke
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.leitplanke
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.markierung
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_markierung_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.markierung
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.markierung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.markierung
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.markierung
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.mast
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_mast_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.mast
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.mast
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.mast
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.mast
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.medien
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_medien_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.medien
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.medien
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.medien
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.medien
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.papierkorb
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_papierkorb_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.papierkorb
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.papierkorb
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.papierkorb
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.papierkorb
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.spundwand
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_spundwand_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.spundwand
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.spundwand
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.spundwand
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.spundwand
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.telefon
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_telefon_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.telefon
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.telefon
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.telefon
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.telefon
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.tor
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_tor_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.tor
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.tor
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.tor
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.tor
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.turm
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_turm_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.turm
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.turm
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.turm
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.turm
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.haltestelle
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_haltestelle_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.haltestelle
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.haltestelle
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.haltestelle
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.haltestelle
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

CREATE TABLE doppik.wehr
(
	material character varying,
	ident character(6) NOT NULL,
	CONSTRAINT pk_wehr_id PRIMARY KEY (id)
)
INHERITS (okstra.strassenausstattung_punkt);

-- Trigger: tr_idents_add_ident on doppik.wehr
CREATE TRIGGER tr_idents_add_ident
    BEFORE INSERT
    ON doppik.wehr
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_add_ident();

-- Trigger: tr_idents_remove_ident on doppik.wehr
CREATE TRIGGER tr_idents_remove_ident
    AFTER DELETE
    ON doppik.wehr
    FOR EACH ROW
    EXECUTE PROCEDURE base.idents_remove_ident();

COMMIT;
