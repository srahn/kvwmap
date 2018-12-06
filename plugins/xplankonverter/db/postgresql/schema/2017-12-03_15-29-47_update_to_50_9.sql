BEGIN;

INSERT INTO xplan_gml.enum_rp_erholungtypen(wert,beschreibung) VALUES (7000,'Kureinrichtung');
INSERT INTO xplan_gml.enum_rp_landwirtschafttypen(wert,beschreibung) VALUES (8000,'Sonderkultur');
INSERT INTO xplan_gml.enum_rp_rechtscharakter(wert,beschreibung) VALUES (9998,'Unbekannt');

-- Erstellt temporaere Relationstabelle rp_rohstofftypen alt -> rp_rohstofftypen(ARRAYS) neu
CREATE TABLE xplan_gml.rp_rohstofftypen_tmp(
  old integer,
  new integer
)
WITH(
  OIDS = TRUE
);
INSERT INTO
  xplan_gml.rp_rohstofftypen_tmp(old, new)
VALUES
  (1000,1000),
  (1100,1100),
  (1200,1200),
  (1300,1300),
  (1400,1400),
  (1500,1500),
  (1600,1600),
  (1700,1800),
  (1800,1900),
  (1900,2000),
  (2000,2100),
  (2100,2200),
  (2200,2300),
  (2300,2400),
  (2400,2500),
  (2500,2600),
  (2600,2700),
  (2700,2800),
  (2800,2900),
  (2900,3000),
  (3000,3200),
  (3100,3300),
  (3200,3400),
  (3300,3500),
  (3400,3600),
  (3500,3700),
  (3600,3800),
  (3700,3900),
  (3800,4000),
  (3900,4100),
  (4000,4200),
  (4100,4300),
  (4200,4400),
  (4300,4500),
  (4400,4600),
  (4500,4700),
  (4600,4800),
  (4700,4900),
  (4800,5000),
  (4900,5100),
  (5000,5200),
  (5100,5300),
  (5200,5400),
  (5300,5500),
  (5400,5600),
  (5500,5700),
  (5600,5800),
  (5700,5900),
  (5800,6000),
  (5900,6100),
  (6000,6200),
  (6100,6300),
  (6200,6400),
  (6300,6500),
  (6400,6600),
  (6500,6700),
  (6600,6800),
  (6700,6900),
  (6800,7000),
  (9999,9999);

-- Fuehrt Update aus
UPDATE
  xplan_gml.rp_rohstoff ro
SET
  rohstofftyp = new_values.new_rohstofftypen
FROM
  (
    SELECT
      gml_id,
      ARRAY_AGG(new) AS new_rohstofftypen
    FROM
      (
        SELECT
          gml_id,
          t.old,
          t.new::text::xplan_gml.rp_rohstofftypen
        FROM
          (
            SELECT
              gml_id,
              UNNEST(rohstofftyp) AS old
             FROM
               xplan_gml.rp_rohstoff AS r
         ) unnested JOIN
         xplan_gml.rp_rohstofftypen_tmp AS t ON unnested.old::text = t.old::text
      ) AS temp
    GROUP BY
      gml_id
  ) AS new_values
WHERE
  ro.gml_id = new_values.gml_id;

-- Loescht temporaere Tabelle
DROP TABLE xplan_gml.rp_rohstofftypen_tmp;

TRUNCATE xplan_gml.enum_rp_rohstofftypen;
INSERT INTO xplan_gml.enum_rp_rohstofftypen(wert,beschreibung) VALUES
  (1000,'Anhydritstein'),
  (1100,'Baryt'),
  (1200,'BasaltDiabas'),
  (1300,'Bentonit'),
  (1400,'Blaehton'),
  (1500,'Braunkohle'),
  (1600,'Buntsandstein'),
  (1700,'Dekostein'),
  (1800,'Diorit'),
  (1900,'Dolomitstein'),
  (2000,'Erdgas'),
  (2100,'Erdoel'),
  (2200,'Erz'),
  (2300,'Feldspat'),
  (2400,'Festgestein'),
  (2500,'Flussspat'),
  (2600,'Gangquarz'),
  (2700,'Gipsstein'),
  (2800,'Gneis'),
  (2900,'Granit'),
  (3000,'Grauwacke'),
  (3100,'Hartgestein'),
  (3200,'KalkKalktuffKreide'),
  (3300,'Kalkmergelstein'),
  (3400,'Kalkstein'),
  (3500,'Kaolin'),
  (3600,'Karbonatgestein'),
  (3700,'Kies'),
  (3800,'Kieselgur'),
  (3900,'KieshaltigerSand'),
  (4000,'KiesSand'),
  (4100,'Klei'),
  (4200,'Kristallin'),
  (4300,'Kupfer'),
  (4400,'Lehm'),
  (4500,'Marmor'),
  (4600,'Mergel'),
  (4700,'Mergelstein'),
  (4800,'MikrogranitGranitporphyr'),
  (4900,'Monzonit'),
  (5000,'Muschelkalk'),
  (5100,'Naturstein'),
  (5200,'Naturwerkstein'),
  (5300,'Oelschiefer'),
  (5400,'Pegmatitsand'),
  (5500,'Quarzit'),
  (5600,'Quarzsand'),
  (5700,'Rhyolith'),
  (5800,'RhyolithQuarzporphyr'),
  (5900,'Salz'),
  (6000,'Sand'),
  (6100,'Sandstein'),
  (6200,'Spezialton'),
  (6300,'SteineundErden'),
  (6400,'Steinkohle'),
  (6500,'Ton'),
  (6600,'Tonstein'),
  (6700,'Torf'),
  (6800,'TuffBimsstein'),
  (6900,'Uran'),
  (7000,'Vulkanit'),
  (7100,'Werkstein'),
  (9999,'Sonstiges')
;
INSERT INTO xplan_gml.enum_xp_anpflanzungbindungerhaltungsgegenstand(wert,beschreibung) VALUES(2050,'BaeumeUndStraeucher');

CREATE TYPE xplan_gml.xp_bedeutungenbereich AS ENUM (
  '1600',
  '1800',
  '9999'
);

UPDATE xplan_gml.xp_bereich SET bedeutung = '9999' WHERE bedeutung IN ('1000', '1500', '1650', '1700', '2000', '2500', '3000', '3500', '4000');
ALTER TABLE xplan_gml.xp_bereich ALTER COLUMN bedeutung TYPE xplan_gml.xp_bedeutungenbereich USING bedeutung::text::xplan_gml.xp_bedeutungenbereich;
DROP TYPE xplan_gml.xp_bedeutungenbereich_old CASCADE;
TRUNCATE xplan_gml.enum_xp_bedeutungenbereich;
INSERT INTO xplan_gml.enum_xp_bedeutungenbereich(wert,beschreibung) VALUES
  (1000,'Teilbereich'),
  (1100,'Kompensationsbereich'),
  (1200,'Sonstiges');

INSERT INTO xplan_gml.enum_xp_besondereartderbaulnutzung(wert,beschreibung) VALUES
(1550,'UrbanesGebiet');

DROP TABLE xplan_gml.enum_xp_besonderezweckbestgemeinbedarf;
DROP TYPE xplan_gml.xp_besonderezweckbestgemeinbedarf;

DROP TABLE xplan_gml.enum_xp_besonderezweckbestimmunggruen;
DROP TYPE xplan_gml.xp_besonderezweckbestimmunggruen;

DROP TABLE xplan_gml.enum_xp_besonderezweckbestimmungverentsorgung;
DROP TYPE xplan_gml.xp_besonderezweckbestimmungverentsorgung;

------------------------------ START EXTERNE REFERENZ -----------

--xp_externereferenz: typ von attribut art aendern von xp_externereferenz code list table auf enumerations typ
-- XP_ExterneReferenzArt
ALTER TABLE xplan_gml.xp_externereferenzart RENAME TO xp_externereferenzart_alt;
CREATE TYPE xplan_gml.xp_externereferenzart AS ENUM (
  'Dokument',
  'PlanMitGeoreferenz'
);
CREATE TABLE xplan_gml.enum_xp_externereferenzart(
  wert character varying NOT NULL,
	beschreibung character varying,
	CONSTRAINT enum_xp_externereferenzart_pkey PRIMARY KEY (wert)
)
WITH (
  OIDS = TRUE
);
INSERT INTO xplan_gml.enum_xp_externereferenzart(wert, beschreibung) VALUES
  ('Dokument', 'Dokument'),
  ('PlanMitGeoreferenz', 'PlanMitGeoreferenz')
;

-- XP_ExterneReferenzTyp
CREATE TYPE xplan_gml.xp_externereferenztyp AS ENUM (
  '1000',
  '1010',
  '1020',
  '1030',
  '1040',
  '1050',
  '1060',
  '1070',
  '1080',
  '1090',
  '2000',
  '2100',
  '2200',
  '2300',
  '2400',
  '2500',
  '9998',
  '9999'
);
CREATE TABLE xplan_gml.enum_xp_externereferenztyp(
  wert integer NOT NULL,
	beschreibung character varying,
	CONSTRAINT enum_xp_externereferenztyp_pkey PRIMARY KEY (wert)
)
WITH (
  OIDS = TRUE
);
INSERT INTO xplan_gml.enum_xp_externereferenztyp(wert,beschreibung) VALUES
  (1000,'Beschreibung'),
  (1010,'Begruendung'),
  (1020,'Legende'),
  (1030,'Rechtsplan'),
  (1040,'Plangrundlage'),
  (1050,'Umweltbericht'),
  (1060,'Satzung'),
  (1070,'Karte'),
  (1080,'Erlaeuterung'),
  (1090,'ZusammenfassendeErklaerung'),
  (2000,'Koordinatenliste'),
  (2100,'Grundstuecksverzeichnis'),
  (2200,'Pflanzliste'),
  (2300,'Gruenordnungsplan'),
  (2400,'Erschliessungsvertrag'),
  (2500,'Durchfuehrungsvertrag'),
  (9998,'Rechtsverbindlich'),
  (9999,'Informell')
;

-- DataType XP_ExterneReferenz
ALTER Type xplan_gml.xp_externereferenz RENAME TO xp_externereferenz_alt;
CREATE TYPE xplan_gml.xp_externereferenz AS (
  georefurl character varying,
  georefmimetype xplan_gml.xp_mimetypes,
  art  xplan_gml.xp_externereferenzart,
  informationssystemurl character varying,
  referenzname character varying,
  referenzurl character varying,
  referenzmimetype xplan_gml.xp_mimetypes,
  beschreibung character varying,
  datum date
);
COMMENT ON TYPE xplan_gml.xp_externereferenz
  IS 'Alias: "XP_ExterneReferenz",  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1]';
CREATE OR REPLACE FUNCTION xplan_gml.to_xp_externereferenz(alt xplan_gml.xp_externereferenz_alt)
  RETURNS xplan_gml.xp_externereferenz AS
$$
DECLARE
  ref_new xplan_gml.xp_externereferenz;
BEGIN
  ref_new.georefurl = alt.georefurl;
  ref_new.georefmimetype = alt.georefmimetype;
  ref_new.art = CASE WHEN (alt.art).id::text = any (enum_range(NULL::xplan_gml.xp_externereferenzart)::text[]) THEN (alt.art).id::text ELSE NULL END;
  ref_new.informationssystemurl = alt.informationssystemurl;
  ref_new.referenzname = alt.referenzname;
  ref_new.referenzurl = alt.referenzurl;
  ref_new.referenzmimetype = alt.referenzmimetype;
  ref_new.beschreibung = alt.beschreibung;
  ref_new.datum = alt.datum;
  RETURN ref_new;
END
$$
LANGUAGE plpgsql;
CREATE CAST (xplan_gml.xp_externereferenz_alt AS xplan_gml.xp_externereferenz)
  WITH FUNCTION xplan_gml.to_xp_externereferenz(xplan_gml.xp_externereferenz_alt);

-- DataType XP_SpezExterneReferenz
CREATE TYPE xplan_gml.xp_spezexternereferenz AS (
  georefurl character varying,
  georefmimetype  xplan_gml.xp_mimetypes,
  art  xplan_gml.xp_externereferenzart,
  informationssystemurl character varying,
  referenzname character varying,
  referenzurl character varying,
  referenzmimetype  xplan_gml.xp_mimetypes,
  beschreibung character varying,
  datum date,
  typ xplan_gml.xp_externereferenztyp
);
COMMENT ON TYPE  xplan_gml.xp_spezexternereferenz
  IS 'Alias: "XP_SpezExterneReferenz",  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [1]';

-- aendere alle attribute vom type xp_externereferenz_alt (code list table) nach xp_externereferenz (enum type)
ALTER TABLE xplan_gml.xp_objekt ALTER COLUMN rechtsverbindlich TYPE xplan_gml.xp_externereferenz[] using rechtsverbindlich::xplan_gml.xp_externereferenz[];
ALTER TABLE xplan_gml.rp_legendenobjekt ALTER COLUMN reflegendenbild TYPE xplan_gml.xp_externereferenz using reflegendenbild::xplan_gml.xp_externereferenz;
ALTER TABLE xplan_gml.xp_textabschnitt ALTER COLUMN reftext TYPE xplan_gml.xp_externereferenz using reftext::xplan_gml.xp_externereferenz;
ALTER TABLE xplan_gml.xp_begruendungabschnitt ALTER COLUMN reftext TYPE xplan_gml.xp_externereferenz using reftext::xplan_gml.xp_externereferenz;

DROP CAST (xplan_gml.xp_externereferenz_alt AS xplan_gml.xp_externereferenz);
DROP FUNCTION xplan_gml.to_xp_externereferenz(xplan_gml.xp_externereferenz_alt);

COMMIT;
