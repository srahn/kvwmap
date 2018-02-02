--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: xplan_gml; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA xplan_gml;


SET search_path = xplan_gml, pg_catalog;

--
-- Name: doublelist; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE doublelist AS (
	list text
);


--
-- Name: TYPE doublelist; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE doublelist IS 'Alias: "doubleList", ISO 19136 GML Type: list';


--
-- Name: COLUMN doublelist.list; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN doublelist.list IS 'list Sequence Sequence 0..1';


--
-- Name: measure; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE measure AS (
	value integer
);


--
-- Name: TYPE measure; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE measure IS 'Alias: "Measure", ISO 19136 GML Type: value';


--
-- Name: COLUMN measure.value; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN measure.value IS 'value DataType Integer 0..1';


--
-- Name: query; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE query AS (
	url character varying
);


--
-- Name: TYPE query; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE query IS 'Alias: "Query", wfs:Query nach Web Feature Service Specifikation, Version 1.0.0: url 0..1';


--
-- Name: COLUMN query.url; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN query.url IS 'url CharacterString CharacterString 0..1';


--
-- Name: rp_abfallentsorgungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_abfallentsorgungtypen AS ENUM (
    '1000',
    '1100',
    '1101',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '9999'
);


--
-- Name: rp_abfalltypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_abfalltypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '9999'
);


--
-- Name: rp_abwassertypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_abwassertypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: rp_achsentypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_achsentypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '3001',
    '3002',
    '3003',
    '4000',
    '5000',
    '6000',
    '9999'
);


--
-- Name: rp_art; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_art AS ENUM (
    '1000',
    '2000',
    '2001',
    '3000',
    '4000',
    '5000',
    '5001',
    '6000',
    '9999'
);


--
-- Name: rp_bedeutsamkeit; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bedeutsamkeit AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000'
);


--
-- Name: rp_bergbaufolgenutzung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bergbaufolgenutzung AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000',
    '9999'
);


--
-- Name: rp_bergbauplanungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bergbauplanungtypen AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '9999'
);


--
-- Name: rp_besondereraumkategorietypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besondereraumkategorietypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: rp_besondererschienenverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besondererschienenverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '2000',
    '3000',
    '3001',
    '4000',
    '4001',
    '5000',
    '6000',
    '6001',
    '7000',
    '7001',
    '8000',
    '8001'
);


--
-- Name: rp_besondererstrassenverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besondererstrassenverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '1003',
    '2000',
    '3000'
);


--
-- Name: rp_besonderetourismuserholungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besonderetourismuserholungtypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: rp_bodenschatztiefen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bodenschatztiefen AS ENUM (
    '1000',
    '2000'
);


--
-- Name: rp_bodenschutztypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bodenschutztypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- Name: rp_einzelhandeltypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_einzelhandeltypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000',
    '9999'
);


--
-- Name: rp_energieversorgungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_energieversorgungtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '2000',
    '2001',
    '3000',
    '3001',
    '3002',
    '4000',
    '4001',
    '4002',
    '5000',
    '6000',
    '7000',
    '9999'
);


--
-- Name: rp_erholungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_erholungtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '5001',
    '6000',
    '7000',
    '9999'
);


--
-- Name: rp_erneuerbareenergietypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_erneuerbareenergietypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: rp_forstwirtschafttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_forstwirtschafttypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '2000',
    '2001',
    '2002',
    '3000',
    '3001',
    '4000',
    '9999'
);


--
-- Name: rp_funktionszuweisungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_funktionszuweisungtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9999'
);


--
-- Name: rp_gebietstyp; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_gebietstyp AS ENUM (
    '1000',
    '1001',
    '1100',
    '1101',
    '1200',
    '1300',
    '1400',
    '1500',
    '1501',
    '1600',
    '1700',
    '1800',
    '9999'
);


--
-- Name: rp_hochwasserschutztypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_hochwasserschutztypen AS ENUM (
    '1000',
    '1001',
    '1100',
    '1101',
    '1102',
    '1200',
    '1300',
    '1301',
    '1302',
    '1303',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1801',
    '9999'
);


--
-- Name: rp_industriegewerbetypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_industriegewerbetypen AS ENUM (
    '2000',
    '1000',
    '1001',
    '2001',
    '2002',
    '2003',
    '3000',
    '3001',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000',
    '9999'
);


--
-- Name: rp_kommunikationtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_kommunikationtypen AS ENUM (
    '1000',
    '2000',
    '2001',
    '2002',
    '9999'
);


--
-- Name: rp_kulturlandschafttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_kulturlandschafttypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: rp_laermschutztypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_laermschutztypen AS ENUM (
    '1000',
    '1001',
    '2000',
    '3000',
    '4000',
    '5000',
    '9999'
);


--
-- Name: rp_landwirtschafttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_landwirtschafttypen AS ENUM (
    '1000',
    '1001',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9999'
);


--
-- Name: rp_lufttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_lufttypen AS ENUM (
    '1000',
    '2000',
    '9999'
);


--
-- Name: rp_luftverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_luftverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '1003',
    '1004',
    '1005',
    '2000',
    '2001',
    '2002',
    '2003',
    '3000',
    '4000',
    '5000',
    '5001',
    '5002',
    '5003',
    '9999'
);


--
-- Name: rp_naturlandschafttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_naturlandschafttypen AS ENUM (
    '1000',
    '1100',
    '1101',
    '1200',
    '1300',
    '1301',
    '1400',
    '1500',
    '1501',
    '1600',
    '1700',
    '1701',
    '1702',
    '1703',
    '1704',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '2400',
    '2500',
    '9999'
);


--
-- Name: rp_primaerenergietypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_primaerenergietypen AS ENUM (
    '1000',
    '2000',
    '2001',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000',
    '9001',
    '9999'
);


--
-- Name: rp_radwegwanderwegtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_radwegwanderwegtypen AS ENUM (
    '1000',
    '1001',
    '2000',
    '2001',
    '3000',
    '4000',
    '9999'
);


--
-- Name: rp_raumkategorietypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_raumkategorietypen AS ENUM (
    '1000',
    '1001',
    '1100',
    '1101',
    '1102',
    '1103',
    '1104',
    '1105',
    '1106',
    '1200',
    '1201',
    '1202',
    '1203',
    '1300',
    '1301',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '2400',
    '2500',
    '9999'
);


--
-- Name: rp_rechtscharakter; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_rechtscharakter AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9000'
);


--
-- Name: rp_rechtsstand; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_rechtsstand AS ENUM (
    '1000',
    '2000',
    '2001',
    '2002',
    '2003',
    '2004',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000'
);


--
-- Name: rp_rohstofftypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_rohstofftypen AS ENUM (
    '5700',
    '5800',
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '2400',
    '2500',
    '2600',
    '2700',
    '2800',
    '2900',
    '3000',
    '3100',
    '3200',
    '3300',
    '3400',
    '3500',
    '3600',
    '3700',
    '3800',
    '3900',
    '4000',
    '4100',
    '4200',
    '4300',
    '4400',
    '4500',
    '4600',
    '4700',
    '4800',
    '4900',
    '5000',
    '5100',
    '5200',
    '5300',
    '5400',
    '5500',
    '5600',
    '5900',
    '6000',
    '6100',
    '6200',
    '6300',
    '6400',
    '6500',
    '6600',
    '6700',
    '6800',
    '6900',
    '7000',
    '7100',
    '9999'
);


--
-- Name: rp_schienenverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_schienenverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '1100',
    '1200',
    '1300',
    '1301',
    '1302',
    '1303',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1801',
    '9999'
);


--
-- Name: rp_sonstverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_sonstverkehrtypen AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '9999'
);


--
-- Name: rp_sozialeinfrastrukturtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_sozialeinfrastrukturtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '3001',
    '4000',
    '4001',
    '5000',
    '9999'
);


--
-- Name: rp_spannungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_spannungtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000'
);


--
-- Name: rp_sperrgebiettypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_sperrgebiettypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '4001',
    '5000',
    '6000',
    '9999'
);


--
-- Name: rp_spezifischegrenzetypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_spezifischegrenzetypen AS ENUM (
    '1000',
    '1001',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000'
);


--
-- Name: rp_sportanlagetypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_sportanlagetypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '9999'
);


--
-- Name: rp_strassenverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_strassenverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '1003',
    '1004',
    '1005',
    '1006',
    '1007',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '9999'
);


--
-- Name: rp_tourismustypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_tourismustypen AS ENUM (
    '1000',
    '2000',
    '9999'
);


--
-- Name: rp_verfahren; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_verfahren AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000'
);


--
-- Name: rp_verkehrstatus; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_verkehrstatus AS ENUM (
    '1000',
    '1001',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9999'
);


--
-- Name: rp_verkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_verkehrtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: rp_wasserschutztypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wasserschutztypen AS ENUM (
    '1000',
    '2000',
    '2001',
    '2002',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '9999'
);


--
-- Name: rp_wasserschutzzonen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wasserschutzzonen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: rp_wasserverkehrtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wasserverkehrtypen AS ENUM (
    '1000',
    '1001',
    '1002',
    '1003',
    '1004',
    '2000',
    '3000',
    '4000',
    '4001',
    '4002',
    '4003',
    '5000',
    '9999'
);


--
-- Name: rp_wasserwirtschafttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wasserwirtschafttypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '9999'
);


--
-- Name: rp_wohnensiedlungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wohnensiedlungtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '3001',
    '3002',
    '3003',
    '3004',
    '4000',
    '5000',
    '9999'
);


--
-- Name: rp_zaesurtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zaesurtypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: rp_zeitstufen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zeitstufen AS ENUM (
    '1000',
    '2000'
);


--
-- Name: rp_zentralerortsonstigetypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zentralerortsonstigetypen AS ENUM (
    '1000',
    '1100',
    '1101',
    '1102',
    '1200',
    '1300',
    '1301',
    '1302',
    '1400',
    '1500',
    '1501',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2101',
    '9999'
);


--
-- Name: rp_zentralerorttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zentralerorttypen AS ENUM (
    '1000',
    '1001',
    '1500',
    '2000',
    '2500',
    '3000',
    '3001',
    '3500',
    '4000',
    '5000',
    '6000',
    '6001',
    '7000',
    '8000',
    '9000',
    '9999'
);


--
-- Name: sc_crs; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE sc_crs AS (
	scope character varying[]
);


--
-- Name: TYPE sc_crs; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE sc_crs IS 'Alias: "SC_CRS", ISO 19136 GML Type: scope 1..*';


--
-- Name: COLUMN sc_crs.scope; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN sc_crs.scope IS 'scope CharacterString CharacterString 1..*';


--
-- Name: transaction; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE transaction AS (
	content text
);


--
-- Name: TYPE transaction; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE transaction IS 'Alias: "Transaction", wfs:Transaction nach Web Feature Service Specifikation, Version 1.0.0: content 0..1';


--
-- Name: COLUMN transaction.content; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN transaction.content IS 'content CharacterString Text 0..1';


--
-- Name: xp_abemassnahmentypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_abemassnahmentypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: xp_abweichungbaunvotypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_abweichungbaunvotypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- Name: xp_allgartderbaulnutzung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_allgartderbaulnutzung AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: xp_anpflanzungbindungerhaltungsgegenstand; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_anpflanzungbindungerhaltungsgegenstand AS ENUM (
    '1000',
    '1100',
    '1200',
    '2000',
    '2050',
    '2100',
    '2200',
    '3000',
    '4000',
    '5000',
    '6000'
);


--
-- Name: xp_arthoehenbezug; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_arthoehenbezug AS ENUM (
    '1000',
    '2000',
    '2500',
    '3000'
);


--
-- Name: xp_arthoehenbezugspunkt; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_arthoehenbezugspunkt AS ENUM (
    '1000',
    '2000',
    '3000',
    '3500',
    '4000',
    '4500',
    '5000',
    '5500',
    '6000'
);


--
-- Name: xp_bedeutungenbereich; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_bedeutungenbereich AS ENUM (
    '1600',
    '1800',
    '9999'
);


--
-- Name: xp_besondereartderbaulnutzung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_besondereartderbaulnutzung AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1550',
    '1600',
    '1700',
    '1800',
    '2000',
    '2100',
    '3000',
    '4000',
    '9999'
);


--
-- Name: xp_bundeslaender; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_bundeslaender AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '2400',
    '2500',
    '3000'
);


--
-- Name: xp_datumattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_datumattribut AS (
	wert date
);


--
-- Name: TYPE xp_datumattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_datumattribut IS 'Alias: "XP_DatumAttribut",  1';


--
-- Name: COLUMN xp_datumattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_datumattribut.wert IS 'wert  Date 1';


--
-- Name: xp_doubleattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_doubleattribut AS (
	wert double precision
);


--
-- Name: TYPE xp_doubleattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_doubleattribut IS 'Alias: "XP_DoubleAttribut",  1';


--
-- Name: COLUMN xp_doubleattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_doubleattribut.wert IS 'wert  Decimal 1';


--
-- Name: xp_externereferenzart; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_externereferenzart AS ENUM (
    'Dokument',
    'PlanMitGeoreferenz'
);


SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: xp_mimetypes; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_mimetypes (
    codespace text,
    id character varying NOT NULL,
    value text
);


--
-- Name: TABLE xp_mimetypes; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_mimetypes IS 'Alias: "XP_MimeTypes", UML-Typ: Code Liste';


--
-- Name: COLUMN xp_mimetypes.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_mimetypes.codespace IS 'codeSpace  text';


--
-- Name: COLUMN xp_mimetypes.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_mimetypes.id IS 'id  character varying';


--
-- Name: COLUMN xp_mimetypes.value; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_mimetypes.value IS 'value text';


--
-- Name: xp_externereferenz; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_externereferenz AS (
	georefurl character varying,
	georefmimetype xp_mimetypes,
	art xp_externereferenzart,
	informationssystemurl character varying,
	referenzname character varying,
	referenzurl character varying,
	referenzmimetype xp_mimetypes,
	beschreibung character varying,
	datum date
);


--
-- Name: TYPE xp_externereferenz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_externereferenz IS 'Alias: "XP_ExterneReferenz",  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1]';


--
-- Name: xp_externereferenztyp; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_externereferenztyp AS ENUM (
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


--
-- Name: xp_flaechengeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_flaechengeometrie AS (
	flaeche public.geometry(Polygon),
	multiflaeche public.geometry(MultiPolygon)
);


--
-- Name: TYPE xp_flaechengeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_flaechengeometrie IS 'Alias: "XP_Flaechengeometrie", UML-DataType: GM_Surface 1, UML-DataType: GM_MultiSurface 1';


--
-- Name: COLUMN xp_flaechengeometrie.flaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_flaechengeometrie.flaeche IS 'Flaeche  GM_Surface 1';


--
-- Name: COLUMN xp_flaechengeometrie.multiflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_flaechengeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';


--
-- Name: xp_gemeinde; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_gemeinde AS (
	ags character varying,
	rs character varying,
	gemeindename character varying,
	ortsteilname character varying
);


--
-- Name: TYPE xp_gemeinde; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_gemeinde IS 'Alias: "XP_Gemeinde",  [0..1],  [0..1],  [0..1],  [0..1]';


--
-- Name: COLUMN xp_gemeinde.ags; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.ags IS 'ags  CharacterString 0..1';


--
-- Name: COLUMN xp_gemeinde.rs; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.rs IS 'rs  CharacterString 0..1';


--
-- Name: COLUMN xp_gemeinde.gemeindename; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.gemeindename IS 'gemeindeName  CharacterString 0..1';


--
-- Name: COLUMN xp_gemeinde.ortsteilname; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.ortsteilname IS 'ortsteilName  CharacterString 0..1';


--
-- Name: xp_generattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_generattribut AS (
	name character varying
);


--
-- Name: TYPE xp_generattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_generattribut IS 'Alias: "XP_GenerAttribut",  1';


--
-- Name: COLUMN xp_generattribut.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_generattribut.name IS 'name  CharacterString 1';


--
-- Name: xp_grenzetypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_grenzetypen AS ENUM (
    '1000',
    '1100',
    '1200',
    '1250',
    '1300',
    '1400',
    '1450',
    '1500',
    '1510',
    '1550',
    '1600',
    '2000',
    '2100',
    '9999'
);


--
-- Name: xp_hoehenangabe; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_hoehenangabe AS (
	abweichenderhoehenbezug character varying,
	hoehenbezug xp_arthoehenbezug,
	bezugspunkt xp_arthoehenbezugspunkt,
	hmin double precision,
	hmax double precision,
	hzwingend double precision,
	h double precision,
	abweichenderbezugspunkt character varying
);


--
-- Name: TYPE xp_hoehenangabe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_hoehenangabe IS 'Alias: "XP_Hoehenangabe",  [0..1], UML-Classifier: XP_ArtHoehenbezug Stereotyp: enumeration [0..1], UML-Classifier: XP_ArtHoehenbezugspunkt Stereotyp: enumeration [0..1],  [0..1],  [0..1],  [0..1],  [0..1]';


--
-- Name: COLUMN xp_hoehenangabe.abweichenderhoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.abweichenderhoehenbezug IS 'abweichenderHoehenbezug  CharacterString 0..1';


--
-- Name: COLUMN xp_hoehenangabe.hoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hoehenbezug IS 'hoehenbezug enumeration XP_ArtHoehenbezug 0..1';


--
-- Name: COLUMN xp_hoehenangabe.bezugspunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.bezugspunkt IS 'bezugspunkt enumeration XP_ArtHoehenbezugspunkt 0..1';


--
-- Name: COLUMN xp_hoehenangabe.hmin; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hmin IS 'hMin  Length 0..1';


--
-- Name: COLUMN xp_hoehenangabe.hmax; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hmax IS 'hMax  Length 0..1';


--
-- Name: COLUMN xp_hoehenangabe.hzwingend; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hzwingend IS 'hZwingend  Length 0..1';


--
-- Name: COLUMN xp_hoehenangabe.h; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.h IS 'h  Length 0..1';


--
-- Name: COLUMN xp_hoehenangabe.abweichenderbezugspunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.abweichenderbezugspunkt IS 'abweichenderBezugspunkt  CharacterString 0..1';


--
-- Name: xp_horizontaleausrichtung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_horizontaleausrichtung AS ENUM (
    'linksbündig',
    'rechtsbündig',
    'zentrisch'
);


--
-- Name: xp_integerattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_integerattribut AS (
	wert integer
);


--
-- Name: TYPE xp_integerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_integerattribut IS 'Alias: "XP_IntegerAttribut",  1';


--
-- Name: COLUMN xp_integerattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_integerattribut.wert IS 'wert  Integer 1';


--
-- Name: xp_klassifizschutzgebietnaturschutzrecht; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_klassifizschutzgebietnaturschutzrecht AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '18000',
    '18001',
    '2000',
    '9999'
);


--
-- Name: xp_liniengeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_liniengeometrie AS (
	linie public.geometry(LineString),
	multilinie public.geometry(MultiLineString)
);


--
-- Name: TYPE xp_liniengeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_liniengeometrie IS 'Alias: "XP_Liniengeometrie", UML-DataType: GM_Curve 1, UML-DataType: GM_MultiCurve 1';


--
-- Name: COLUMN xp_liniengeometrie.linie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_liniengeometrie.linie IS 'Linie  GM_Curve 1';


--
-- Name: COLUMN xp_liniengeometrie.multilinie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_liniengeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';


--
-- Name: xp_nutzungsform; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_nutzungsform AS ENUM (
    '1000',
    '2000'
);


--
-- Name: xp_plangeber; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_plangeber AS (
	name character varying,
	kennziffer character varying
);


--
-- Name: TYPE xp_plangeber; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_plangeber IS 'Alias: "XP_Plangeber",  1,  [0..1]';


--
-- Name: COLUMN xp_plangeber.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plangeber.name IS 'name  CharacterString 1';


--
-- Name: COLUMN xp_plangeber.kennziffer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plangeber.kennziffer IS 'kennziffer  CharacterString 0..1';


--
-- Name: xp_punktgeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_punktgeometrie AS (
	punkt public.geometry(Point),
	multipunkt public.geometry(MultiPoint)
);


--
-- Name: TYPE xp_punktgeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_punktgeometrie IS 'Alias: "XP_Punktgeometrie", UML-DataType: GM_Point 1, UML-DataType: GM_MultiPoint 1';


--
-- Name: COLUMN xp_punktgeometrie.punkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_punktgeometrie.punkt IS 'Punkt  GM_Point 1';


--
-- Name: COLUMN xp_punktgeometrie.multipunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_punktgeometrie.multipunkt IS 'MultiPunkt  GM_MultiPoint 1';


--
-- Name: xp_rechtscharakterplanaenderung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_rechtscharakterplanaenderung AS ENUM (
    '1000',
    '1100',
    '2000'
);


--
-- Name: xp_rechtsstand; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_rechtsstand AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: xp_sondernutzungen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_sondernutzungen AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '16000',
    '16001',
    '16002',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '2400',
    '2500',
    '2600',
    '2700',
    '2800',
    '2900',
    '9999'
);


--
-- Name: xp_spemassnahmentypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_spemassnahmentypen AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '1800',
    '1900',
    '2000',
    '2100',
    '2200',
    '2300',
    '9999'
);


--
-- Name: xp_spemassnahmendaten; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_spemassnahmendaten AS (
	klassifizmassnahme xp_spemassnahmentypen,
	massnahmetext character varying,
	massnahmekuerzel character varying
);


--
-- Name: TYPE xp_spemassnahmendaten; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_spemassnahmendaten IS 'Alias: "XP_SPEMassnahmenDaten", UML-Classifier: XP_SPEMassnahmenTypen Stereotyp: enumeration [0..1],  [0..1],  [0..1]';


--
-- Name: COLUMN xp_spemassnahmendaten.klassifizmassnahme; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.klassifizmassnahme IS 'klassifizMassnahme enumeration XP_SPEMassnahmenTypen 0..1';


--
-- Name: COLUMN xp_spemassnahmendaten.massnahmetext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.massnahmetext IS 'massnahmeText  CharacterString 0..1';


--
-- Name: COLUMN xp_spemassnahmendaten.massnahmekuerzel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.massnahmekuerzel IS 'massnahmeKuerzel  CharacterString 0..1';


--
-- Name: xp_spezexternereferenz; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_spezexternereferenz AS (
	georefurl character varying,
	georefmimetype xp_mimetypes,
	art xp_externereferenzart,
	informationssystemurl character varying,
	referenzname character varying,
	referenzurl character varying,
	referenzmimetype xp_mimetypes,
	beschreibung character varying,
	datum date,
	typ xp_externereferenztyp
);


--
-- Name: TYPE xp_spezexternereferenz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_spezexternereferenz IS 'Alias: "XP_SpezExterneReferenz",  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [1]';


--
-- Name: xp_speziele; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_speziele AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '9999'
);


--
-- Name: xp_stringattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_stringattribut AS (
	wert character varying
);


--
-- Name: TYPE xp_stringattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_stringattribut IS 'Alias: "XP_StringAttribut",  1';


--
-- Name: COLUMN xp_stringattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stringattribut.wert IS 'wert  CharacterString 1';


--
-- Name: xp_urlattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_urlattribut AS (
	wert character varying
);


--
-- Name: TYPE xp_urlattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_urlattribut IS 'Alias: "XP_URLAttribut",  1';


--
-- Name: COLUMN xp_urlattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_urlattribut.wert IS 'wert  URI 1';


--
-- Name: xp_variablegeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_variablegeometrie AS (
	punkt public.geometry(Point),
	multipunkt public.geometry(MultiPoint),
	linie public.geometry(LineString),
	multilinie public.geometry(MultiLineString),
	flaeche public.geometry(Polygon),
	multiflaeche public.geometry(MultiPolygon),
	abweichenderbezugspunkt character varying
);


--
-- Name: TYPE xp_variablegeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_variablegeometrie IS 'Alias: "XP_VariableGeometrie", UML-DataType: GM_Point 1, UML-DataType: GM_MultiPoint 1, UML-DataType: GM_Curve 1, UML-DataType: GM_MultiCurve 1, UML-DataType: GM_Surface 1, UML-DataType: GM_MultiSurface 1';


--
-- Name: COLUMN xp_variablegeometrie.punkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.punkt IS 'Punkt  GM_Point 1';


--
-- Name: COLUMN xp_variablegeometrie.multipunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multipunkt IS 'MultiPunkt  GM_MultiPoint 1';


--
-- Name: COLUMN xp_variablegeometrie.linie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.linie IS 'Linie  GM_Curve 1';


--
-- Name: COLUMN xp_variablegeometrie.multilinie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';


--
-- Name: COLUMN xp_variablegeometrie.flaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.flaeche IS 'Flaeche  GM_Surface 1';


--
-- Name: COLUMN xp_variablegeometrie.multiflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';


--
-- Name: xp_verbundenerplan; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verbundenerplan AS (
	planname character varying,
	rechtscharakter xp_rechtscharakterplanaenderung,
	nummer character varying
);


--
-- Name: TYPE xp_verbundenerplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_verbundenerplan IS 'Alias: "XP_VerbundenerPlan",  1, UML-Classifier: XP_RechtscharakterPlanaenderung Stereotyp: enumeration 1,  [0..1]';


--
-- Name: COLUMN xp_verbundenerplan.planname; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.planname IS 'planName  CharacterString 1';


--
-- Name: COLUMN xp_verbundenerplan.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.rechtscharakter IS 'rechtscharakter enumeration XP_RechtscharakterPlanaenderung 1';


--
-- Name: COLUMN xp_verbundenerplan.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.nummer IS 'nummer  CharacterString 0..1';


--
-- Name: xp_verfahrensmerkmal; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verfahrensmerkmal AS (
	vermerk character varying,
	datum date,
	signatur character varying,
	signiert boolean
);


--
-- Name: TYPE xp_verfahrensmerkmal; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_verfahrensmerkmal IS 'Alias: "XP_VerfahrensMerkmal",  1,  1,  1,  1';


--
-- Name: COLUMN xp_verfahrensmerkmal.vermerk; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.vermerk IS 'vermerk  CharacterString 1';


--
-- Name: COLUMN xp_verfahrensmerkmal.datum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.datum IS 'datum  Date 1';


--
-- Name: COLUMN xp_verfahrensmerkmal.signatur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.signatur IS 'signatur  CharacterString 1';


--
-- Name: COLUMN xp_verfahrensmerkmal.signiert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.signiert IS 'signiert  Boolean 1';


--
-- Name: xp_verlaengerungveraenderungssperre; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verlaengerungveraenderungssperre AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- Name: xp_vertikaleausrichtung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_vertikaleausrichtung AS ENUM (
    'Basis',
    'Mitte',
    'Oben'
);


--
-- Name: xp_wirksamkeitbedingung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_wirksamkeitbedingung AS (
	datumrelativ interval,
	datumabsolut date,
	bedingung character varying
);


--
-- Name: TYPE xp_wirksamkeitbedingung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_wirksamkeitbedingung IS 'Alias: "XP_WirksamkeitBedingung",  [0..1],  [0..1],  [0..1]';


--
-- Name: COLUMN xp_wirksamkeitbedingung.datumrelativ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_wirksamkeitbedingung.datumrelativ IS 'datumRelativ  interval 0..1';


--
-- Name: COLUMN xp_wirksamkeitbedingung.datumabsolut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_wirksamkeitbedingung.datumabsolut IS 'datumAbsolut  Date 0..1';


--
-- Name: COLUMN xp_wirksamkeitbedingung.bedingung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_wirksamkeitbedingung.bedingung IS 'bedingung  CharacterString 0..1';


--
-- Name: xp_zweckbestimmunggemeinbedarf; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggemeinbedarf AS ENUM (
    '1000',
    '10000',
    '10001',
    '10002',
    '10003',
    '1200',
    '12000',
    '12001',
    '12002',
    '12003',
    '12004',
    '1400',
    '14000',
    '14001',
    '14002',
    '14003',
    '1600',
    '16000',
    '16001',
    '16002',
    '16003',
    '16004',
    '1800',
    '18000',
    '18001',
    '2000',
    '20000',
    '20001',
    '20002',
    '2200',
    '22000',
    '22001',
    '22002',
    '2400',
    '24000',
    '24001',
    '24002',
    '24003',
    '2600',
    '26000',
    '26001',
    '9999'
);


--
-- Name: xp_zweckbestimmunggewaesser; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggewaesser AS ENUM (
    '1000',
    '1100',
    '1200',
    '9999'
);


--
-- Name: xp_zweckbestimmunggruen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggruen AS ENUM (
    '1000',
    '10000',
    '10001',
    '10002',
    '10003',
    '1200',
    '12000',
    '1400',
    '14000',
    '14001',
    '14002',
    '14003',
    '14004',
    '14005',
    '14006',
    '14007',
    '1600',
    '16000',
    '16001',
    '1800',
    '18000',
    '2000',
    '2200',
    '22000',
    '22001',
    '2400',
    '24000',
    '24001',
    '24002',
    '24003',
    '24004',
    '24005',
    '24006',
    '2600',
    '9999',
    '99990'
);


--
-- Name: xp_zweckbestimmungkennzeichnung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungkennzeichnung AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000',
    '5000',
    '6000',
    '7000',
    '8000',
    '9999'
);


--
-- Name: xp_zweckbestimmunglandwirtschaft; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunglandwirtschaft AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
    '1600',
    '1700',
    '9999'
);


--
-- Name: xp_zweckbestimmungspielsportanlage; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungspielsportanlage AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- Name: xp_zweckbestimmungverentsorgung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungverentsorgung AS ENUM (
    '1000',
    '10000',
    '10001',
    '10002',
    '10003',
    '10004',
    '10005',
    '10006',
    '10007',
    '10008',
    '10009',
    '100010',
    '1200',
    '12000',
    '12001',
    '12002',
    '12003',
    '12004',
    '12005',
    '1300',
    '13000',
    '13001',
    '13002',
    '13003',
    '1400',
    '14000',
    '14001',
    '14002',
    '1600',
    '16000',
    '16001',
    '16002',
    '16003',
    '16004',
    '16005',
    '1800',
    '18000',
    '18001',
    '18002',
    '18003',
    '18004',
    '18005',
    '18006',
    '2000',
    '20000',
    '20001',
    '2200',
    '22000',
    '22001',
    '22002',
    '22003',
    '2400',
    '24000',
    '24001',
    '24002',
    '24003',
    '24004',
    '24005',
    '2600',
    '26000',
    '26001',
    '26002',
    '2800',
    '3000',
    '9999',
    '99990'
);


--
-- Name: xp_zweckbestimmungwald; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungwald AS ENUM (
    '1000',
    '1200',
    '1400',
    '1600',
    '1800',
    '9999'
);


--
-- Name: xp_zweckbestimmungwasserwirtschaft; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungwasserwirtschaft AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '9999'
);


--
-- Name: enum_rp_abfallentsorgungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abfallentsorgungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_abfallentsorgungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abfallentsorgungtypen IS 'Alias: "enum_RP_AbfallentsorgungTypen"';


--
-- Name: enum_rp_abfalltypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abfalltypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_abfalltypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abfalltypen IS 'Alias: "enum_RP_AbfallTypen"';


--
-- Name: enum_rp_abwassertypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abwassertypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_abwassertypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abwassertypen IS 'Alias: "enum_RP_AbwasserTypen"';


--
-- Name: enum_rp_achsentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_achsentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_achsentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_achsentypen IS 'Alias: "enum_RP_AchsenTypen"';


--
-- Name: enum_rp_art; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_art (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_art; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_art IS 'Alias: "enum_RP_Art"';


--
-- Name: enum_rp_bedeutsamkeit; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bedeutsamkeit (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_bedeutsamkeit; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bedeutsamkeit IS 'Alias: "enum_RP_Bedeutsamkeit"';


--
-- Name: enum_rp_bergbaufolgenutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bergbaufolgenutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_bergbaufolgenutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bergbaufolgenutzung IS 'Alias: "enum_RP_BergbauFolgenutzung"';


--
-- Name: enum_rp_bergbauplanungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bergbauplanungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_bergbauplanungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bergbauplanungtypen IS 'Alias: "enum_RP_BergbauplanungTypen"';


--
-- Name: enum_rp_besondereraumkategorietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondereraumkategorietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_besondereraumkategorietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondereraumkategorietypen IS 'Alias: "enum_RP_BesondereRaumkategorieTypen"';


--
-- Name: enum_rp_besondererschienenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondererschienenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_besondererschienenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondererschienenverkehrtypen IS 'Alias: "enum_RP_BesondererSchienenverkehrTypen"';


--
-- Name: enum_rp_besondererstrassenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondererstrassenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_besondererstrassenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondererstrassenverkehrtypen IS 'Alias: "enum_RP_BesondererStrassenverkehrTypen"';


--
-- Name: enum_rp_besonderetourismuserholungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besonderetourismuserholungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_besonderetourismuserholungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besonderetourismuserholungtypen IS 'Alias: "enum_RP_BesondereTourismusErholungTypen"';


--
-- Name: enum_rp_bodenschatztiefen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bodenschatztiefen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_bodenschatztiefen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bodenschatztiefen IS 'Alias: "enum_RP_BodenschatzTiefen"';


--
-- Name: enum_rp_bodenschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bodenschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_bodenschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bodenschutztypen IS 'Alias: "enum_RP_BodenschutzTypen"';


--
-- Name: enum_rp_einzelhandeltypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_einzelhandeltypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_einzelhandeltypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_einzelhandeltypen IS 'Alias: "enum_RP_EinzelhandelTypen"';


--
-- Name: enum_rp_energieversorgungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_energieversorgungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_energieversorgungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_energieversorgungtypen IS 'Alias: "enum_RP_EnergieversorgungTypen"';


--
-- Name: enum_rp_erholungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_erholungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_erholungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_erholungtypen IS 'Alias: "enum_RP_ErholungTypen"';


--
-- Name: enum_rp_erneuerbareenergietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_erneuerbareenergietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_erneuerbareenergietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_erneuerbareenergietypen IS 'Alias: "enum_RP_ErneuerbareEnergieTypen"';


--
-- Name: enum_rp_forstwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_forstwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_forstwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_forstwirtschafttypen IS 'Alias: "enum_RP_ForstwirtschaftTypen"';


--
-- Name: enum_rp_funktionszuweisungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_funktionszuweisungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_funktionszuweisungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_funktionszuweisungtypen IS 'Alias: "enum_RP_FunktionszuweisungTypen"';


--
-- Name: enum_rp_gebietstyp; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_gebietstyp (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_gebietstyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_gebietstyp IS 'Alias: "enum_RP_GebietsTyp"';


--
-- Name: enum_rp_hochwasserschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_hochwasserschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_hochwasserschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_hochwasserschutztypen IS 'Alias: "enum_RP_HochwasserschutzTypen"';


--
-- Name: enum_rp_industriegewerbetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_industriegewerbetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_industriegewerbetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_industriegewerbetypen IS 'Alias: "enum_RP_IndustrieGewerbeTypen"';


--
-- Name: enum_rp_kommunikationtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_kommunikationtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_kommunikationtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_kommunikationtypen IS 'Alias: "enum_RP_KommunikationTypen"';


--
-- Name: enum_rp_kulturlandschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_kulturlandschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_kulturlandschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_kulturlandschafttypen IS 'Alias: "enum_RP_KulturlandschaftTypen"';


--
-- Name: enum_rp_laermschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_laermschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_laermschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_laermschutztypen IS 'Alias: "enum_RP_LaermschutzTypen"';


--
-- Name: enum_rp_landwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_landwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_landwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_landwirtschafttypen IS 'Alias: "enum_RP_LandwirtschaftTypen"';


--
-- Name: enum_rp_lufttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_lufttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_lufttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_lufttypen IS 'Alias: "enum_RP_LuftTypen"';


--
-- Name: enum_rp_luftverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_luftverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_luftverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_luftverkehrtypen IS 'Alias: "enum_RP_LuftverkehrTypen"';


--
-- Name: enum_rp_naturlandschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_naturlandschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_naturlandschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_naturlandschafttypen IS 'Alias: "enum_RP_NaturLandschaftTypen"';


--
-- Name: enum_rp_primaerenergietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_primaerenergietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_primaerenergietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_primaerenergietypen IS 'Alias: "enum_RP_PrimaerenergieTypen"';


--
-- Name: enum_rp_radwegwanderwegtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_radwegwanderwegtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_radwegwanderwegtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_radwegwanderwegtypen IS 'Alias: "enum_RP_RadwegWanderwegTypen"';


--
-- Name: enum_rp_raumkategorietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_raumkategorietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_raumkategorietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_raumkategorietypen IS 'Alias: "enum_RP_RaumkategorieTypen"';


--
-- Name: enum_rp_rechtscharakter; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rechtscharakter (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rechtscharakter IS 'Alias: "enum_RP_Rechtscharakter"';


--
-- Name: enum_rp_rechtsstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rechtsstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rechtsstand IS 'Alias: "enum_RP_Rechtsstand"';


--
-- Name: enum_rp_rohstofftypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rohstofftypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_rohstofftypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rohstofftypen IS 'Alias: "enum_RP_RohstoffTypen"';


--
-- Name: enum_rp_schienenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_schienenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_schienenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_schienenverkehrtypen IS 'Alias: "enum_RP_SchienenverkehrTypen"';


--
-- Name: enum_rp_sonstverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sonstverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_sonstverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sonstverkehrtypen IS 'Alias: "enum_RP_SonstVerkehrTypen"';


--
-- Name: enum_rp_sozialeinfrastrukturtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sozialeinfrastrukturtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_sozialeinfrastrukturtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sozialeinfrastrukturtypen IS 'Alias: "enum_RP_SozialeInfrastrukturTypen"';


--
-- Name: enum_rp_spannungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_spannungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_spannungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_spannungtypen IS 'Alias: "enum_RP_SpannungTypen"';


--
-- Name: enum_rp_sperrgebiettypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sperrgebiettypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_sperrgebiettypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sperrgebiettypen IS 'Alias: "enum_RP_SperrgebietTypen"';


--
-- Name: enum_rp_spezifischegrenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_spezifischegrenzetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_spezifischegrenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_spezifischegrenzetypen IS 'Alias: "enum_RP_SpezifischeGrenzeTypen"';


--
-- Name: enum_rp_sportanlagetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sportanlagetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_sportanlagetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sportanlagetypen IS 'Alias: "enum_RP_SportanlageTypen"';


--
-- Name: enum_rp_strassenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_strassenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_strassenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_strassenverkehrtypen IS 'Alias: "enum_RP_StrassenverkehrTypen"';


--
-- Name: enum_rp_tourismustypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_tourismustypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_tourismustypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_tourismustypen IS 'Alias: "enum_RP_TourismusTypen"';


--
-- Name: enum_rp_verfahren; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verfahren (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_verfahren; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verfahren IS 'Alias: "enum_RP_Verfahren"';


--
-- Name: enum_rp_verkehrstatus; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verkehrstatus (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_verkehrstatus; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verkehrstatus IS 'Alias: "enum_RP_VerkehrStatus"';


--
-- Name: enum_rp_verkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_verkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verkehrtypen IS 'Alias: "enum_RP_VerkehrTypen"';


--
-- Name: enum_rp_wasserschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_wasserschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserschutztypen IS 'Alias: "enum_RP_WasserschutzTypen"';


--
-- Name: enum_rp_wasserschutzzonen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserschutzzonen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_wasserschutzzonen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserschutzzonen IS 'Alias: "enum_RP_WasserschutzZonen"';


--
-- Name: enum_rp_wasserverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_wasserverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserverkehrtypen IS 'Alias: "enum_RP_WasserverkehrTypen"';


--
-- Name: enum_rp_wasserwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_wasserwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserwirtschafttypen IS 'Alias: "enum_RP_WasserwirtschaftTypen"';


--
-- Name: enum_rp_wohnensiedlungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wohnensiedlungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_wohnensiedlungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wohnensiedlungtypen IS 'Alias: "enum_RP_WohnenSiedlungTypen"';


--
-- Name: enum_rp_zaesurtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zaesurtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_zaesurtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zaesurtypen IS 'Alias: "enum_RP_ZaesurTypen"';


--
-- Name: enum_rp_zeitstufen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zeitstufen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_zeitstufen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zeitstufen IS 'Alias: "enum_RP_Zeitstufen"';


--
-- Name: enum_rp_zentralerortsonstigetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zentralerortsonstigetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_zentralerortsonstigetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zentralerortsonstigetypen IS 'Alias: "enum_RP_ZentralerOrtSonstigeTypen"';


--
-- Name: enum_rp_zentralerorttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zentralerorttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_rp_zentralerorttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zentralerorttypen IS 'Alias: "enum_RP_ZentralerOrtTypen"';


--
-- Name: enum_xp_abemassnahmentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_abemassnahmentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_abemassnahmentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_abemassnahmentypen IS 'Alias: "enum_XP_ABEMassnahmenTypen"';


--
-- Name: enum_xp_abweichungbaunvotypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_abweichungbaunvotypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_abweichungbaunvotypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_abweichungbaunvotypen IS 'Alias: "enum_XP_AbweichungBauNVOTypen"';


--
-- Name: enum_xp_allgartderbaulnutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_allgartderbaulnutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_allgartderbaulnutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_allgartderbaulnutzung IS 'Alias: "enum_XP_AllgArtDerBaulNutzung"';


--
-- Name: enum_xp_anpflanzungbindungerhaltungsgegenstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand IS 'Alias: "enum_XP_AnpflanzungBindungErhaltungsGegenstand"';


--
-- Name: enum_xp_arthoehenbezug; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_arthoehenbezug (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_arthoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_arthoehenbezug IS 'Alias: "enum_XP_ArtHoehenbezug"';


--
-- Name: enum_xp_arthoehenbezugspunkt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_arthoehenbezugspunkt (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_arthoehenbezugspunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_arthoehenbezugspunkt IS 'Alias: "enum_XP_ArtHoehenbezugspunkt"';


--
-- Name: enum_xp_bedeutungenbereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_bedeutungenbereich (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_bedeutungenbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_bedeutungenbereich IS 'Alias: "enum_XP_BedeutungenBereich"';


--
-- Name: enum_xp_besondereartderbaulnutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_besondereartderbaulnutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_besondereartderbaulnutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_besondereartderbaulnutzung IS 'Alias: "enum_XP_BesondereArtDerBaulNutzung"';


--
-- Name: enum_xp_bundeslaender; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_bundeslaender (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_bundeslaender; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_bundeslaender IS 'Alias: "enum_XP_Bundeslaender"';


--
-- Name: enum_xp_externereferenzart; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_externereferenzart (
    wert character varying NOT NULL,
    beschreibung character varying
);


--
-- Name: enum_xp_externereferenztyp; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_externereferenztyp (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: enum_xp_grenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_grenzetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_grenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_grenzetypen IS 'Alias: "enum_XP_GrenzeTypen"';


--
-- Name: enum_xp_klassifizschutzgebietnaturschutzrecht; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_klassifizschutzgebietnaturschutzrecht (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_klassifizschutzgebietnaturschutzrecht; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_klassifizschutzgebietnaturschutzrecht IS 'Alias: "enum_XP_KlassifizSchutzgebietNaturschutzrecht"';


--
-- Name: enum_xp_nutzungsform; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_nutzungsform (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_nutzungsform; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_nutzungsform IS 'Alias: "enum_XP_Nutzungsform"';


--
-- Name: enum_xp_rechtscharakterplanaenderung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_rechtscharakterplanaenderung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_rechtscharakterplanaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_rechtscharakterplanaenderung IS 'Alias: "enum_XP_RechtscharakterPlanaenderung"';


--
-- Name: enum_xp_rechtsstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_rechtsstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_rechtsstand IS 'Alias: "enum_XP_Rechtsstand"';


--
-- Name: enum_xp_sondernutzungen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_sondernutzungen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_sondernutzungen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_sondernutzungen IS 'Alias: "enum_XP_Sondernutzungen"';


--
-- Name: enum_xp_spemassnahmentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_spemassnahmentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_spemassnahmentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_spemassnahmentypen IS 'Alias: "enum_XP_SPEMassnahmenTypen"';


--
-- Name: enum_xp_speziele; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_speziele (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_speziele; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_speziele IS 'Alias: "enum_XP_SPEZiele"';


--
-- Name: enum_xp_verlaengerungveraenderungssperre; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_verlaengerungveraenderungssperre (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_verlaengerungveraenderungssperre; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_verlaengerungveraenderungssperre IS 'Alias: "enum_XP_VerlaengerungVeraenderungssperre"';


--
-- Name: enum_xp_zweckbestimmunggemeinbedarf; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggemeinbedarf (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmunggemeinbedarf; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggemeinbedarf IS 'Alias: "enum_XP_ZweckbestimmungGemeinbedarf"';


--
-- Name: enum_xp_zweckbestimmunggewaesser; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggewaesser (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmunggewaesser; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggewaesser IS 'Alias: "enum_XP_ZweckbestimmungGewaesser"';


--
-- Name: enum_xp_zweckbestimmunggruen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggruen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmunggruen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggruen IS 'Alias: "enum_XP_ZweckbestimmungGruen"';


--
-- Name: enum_xp_zweckbestimmungkennzeichnung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungkennzeichnung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmungkennzeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungkennzeichnung IS 'Alias: "enum_XP_ZweckbestimmungKennzeichnung"';


--
-- Name: enum_xp_zweckbestimmunglandwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunglandwirtschaft (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmunglandwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunglandwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungLandwirtschaft"';


--
-- Name: enum_xp_zweckbestimmungspielsportanlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungspielsportanlage (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmungspielsportanlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungspielsportanlage IS 'Alias: "enum_XP_ZweckbestimmungSpielSportanlage"';


--
-- Name: enum_xp_zweckbestimmungverentsorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungverentsorgung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmungverentsorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungverentsorgung IS 'Alias: "enum_XP_ZweckbestimmungVerEntsorgung"';


--
-- Name: enum_xp_zweckbestimmungwald; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungwald (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmungwald; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungwald IS 'Alias: "enum_XP_ZweckbestimmungWald"';


--
-- Name: enum_xp_zweckbestimmungwasserwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungwasserwirtschaft (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- Name: TABLE enum_xp_zweckbestimmungwasserwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungwasserwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungWasserwirtschaft"';


--
-- Name: xp_gesetzlichegrundlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_gesetzlichegrundlage (
    codespace text,
    id character varying NOT NULL
);


--
-- Name: TABLE xp_gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_gesetzlichegrundlage IS 'Alias: "XP_GesetzlicheGrundlage", UML-Typ: Code Liste';


--
-- Name: COLUMN xp_gesetzlichegrundlage.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gesetzlichegrundlage.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN xp_gesetzlichegrundlage.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gesetzlichegrundlage.id IS 'id  character varying ';


--
-- Name: xp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    uuid character varying,
    text character varying,
    rechtsstand xp_rechtsstand,
    gesetzlichegrundlage xp_gesetzlichegrundlage,
    gliederung1 character varying,
    gliederung2 character varying,
    ebene integer,
    hatgenerattribut xp_generattribut[],
    hoehenangabe xp_hoehenangabe[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    refbegruendunginhalt text[],
    gehoertzubereich text,
    wirddargestelltdurch text[],
    externereferenz xp_spezexternereferenz[],
    startbedingung xp_wirksamkeitbedingung,
    endebedingung xp_wirksamkeitbedingung
);


--
-- Name: TABLE xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt IS 'FeatureType: "XP_Objekt"';


--
-- Name: COLUMN xp_objekt.uuid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.uuid IS 'uuid  CharacterString 0..1';


--
-- Name: COLUMN xp_objekt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.text IS 'text  CharacterString 0..1';


--
-- Name: COLUMN xp_objekt.rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.rechtsstand IS 'rechtsstand enumeration XP_Rechtsstand 0..1';


--
-- Name: COLUMN xp_objekt.gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gesetzlichegrundlage IS 'gesetzlicheGrundlage CodeList XP_GesetzlicheGrundlage 0..1';


--
-- Name: COLUMN xp_objekt.gliederung1; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gliederung1 IS 'gliederung1  CharacterString 0..1';


--
-- Name: COLUMN xp_objekt.gliederung2; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gliederung2 IS 'gliederung2  CharacterString 0..1';


--
-- Name: COLUMN xp_objekt.ebene; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.ebene IS 'ebene  Integer 0..1';


--
-- Name: COLUMN xp_objekt.hatgenerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';


--
-- Name: COLUMN xp_objekt.hoehenangabe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.hoehenangabe IS 'hoehenangabe DataType XP_Hoehenangabe 0..*';


--
-- Name: COLUMN xp_objekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_objekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_objekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_objekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_objekt.refbegruendunginhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.refbegruendunginhalt IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';


--
-- Name: COLUMN xp_objekt.gehoertzubereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';


--
-- Name: COLUMN xp_objekt.wirddargestelltdurch; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.wirddargestelltdurch IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';


--
-- Name: COLUMN xp_objekt.externereferenz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';


--
-- Name: COLUMN xp_objekt.startbedingung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.startbedingung IS 'startBedingung DataType XP_WirksamkeitBedingung 0..1';


--
-- Name: COLUMN xp_objekt.endebedingung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.endebedingung IS 'endeBedingung DataType XP_WirksamkeitBedingung 0..1';


--
-- Name: rp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_objekt (
    rechtscharakter rp_rechtscharakter NOT NULL,
    konkretisierung character varying,
    gebietstyp rp_gebietstyp[],
    kuestenmeer boolean,
    bedeutsamkeit rp_bedeutsamkeit[],
    istzweckbindung boolean,
    gid integer,
    reftextinhalt text[]
)
INHERITS (xp_objekt);


--
-- Name: TABLE rp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_objekt IS 'FeatureType: "RP_Objekt"';


--
-- Name: COLUMN rp_objekt.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.rechtscharakter IS 'rechtscharakter enumeration RP_Rechtscharakter 1';


--
-- Name: COLUMN rp_objekt.konkretisierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.konkretisierung IS 'konkretisierung  CharacterString 0..1';


--
-- Name: COLUMN rp_objekt.gebietstyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.gebietstyp IS 'gebietsTyp enumeration RP_GebietsTyp 0..*';


--
-- Name: COLUMN rp_objekt.kuestenmeer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.kuestenmeer IS 'kuestenmeer  Boolean 0..1';


--
-- Name: COLUMN rp_objekt.bedeutsamkeit; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.bedeutsamkeit IS 'bedeutsamkeit enumeration RP_Bedeutsamkeit 0..*';


--
-- Name: COLUMN rp_objekt.istzweckbindung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.istzweckbindung IS 'istZweckbindung  Boolean 0..1';


--
-- Name: COLUMN rp_objekt.reftextinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType RP_TextAbschnitt (rp_textabschnitt) 0..*';


--
-- Name: rp_geometrieobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_geometrieobjekt (
    "position" public.geometry NOT NULL,
    flaechenschluss boolean,
    flussrichtung boolean
)
INHERITS (rp_objekt);


--
-- Name: TABLE rp_geometrieobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_geometrieobjekt IS 'FeatureType: "RP_Geometrieobjekt"';


--
-- Name: COLUMN rp_geometrieobjekt."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_geometrieobjekt."position" IS 'position Union XP_VariableGeometrie 1';


--
-- Name: COLUMN rp_geometrieobjekt.flaechenschluss; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_geometrieobjekt.flaechenschluss IS 'flaechenschluss  Boolean 0..1';


--
-- Name: COLUMN rp_geometrieobjekt.flussrichtung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_geometrieobjekt.flussrichtung IS 'flussrichtung  Boolean 0..1';


--
-- Name: rp_achse; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_achse (
    typ rp_achsentypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_achse; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_achse IS 'FeatureType: "RP_Achse"';


--
-- Name: COLUMN rp_achse.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_achse.typ IS 'typ enumeration RP_AchsenTypen 0..*';


--
-- Name: xp_bereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_bereich (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    nummer integer NOT NULL,
    name character varying,
    bedeutung xp_bedeutungenbereich,
    detailliertebedeutung character varying,
    erstellungsmassstab integer,
    geltungsbereich public.geometry(MultiPolygon),
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    planinhalt text[],
    praesentationsobjekt text[],
    rasterbasis text
);


--
-- Name: TABLE xp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_bereich IS 'FeatureType: "XP_Bereich"';


--
-- Name: COLUMN xp_bereich.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.nummer IS 'nummer  Integer 1';


--
-- Name: COLUMN xp_bereich.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.name IS 'name  CharacterString 0..1';


--
-- Name: COLUMN xp_bereich.bedeutung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.bedeutung IS 'bedeutung enumeration XP_BedeutungenBereich 0..1';


--
-- Name: COLUMN xp_bereich.detailliertebedeutung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.detailliertebedeutung IS 'detaillierteBedeutung  CharacterString 0..1';


--
-- Name: COLUMN xp_bereich.erstellungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';


--
-- Name: COLUMN xp_bereich.geltungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.geltungsbereich IS 'geltungsbereich Union XP_Flaechengeometrie 0..1';


--
-- Name: COLUMN xp_bereich.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_bereich.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_bereich.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_bereich.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_bereich.planinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.planinhalt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- Name: COLUMN xp_bereich.praesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.praesentationsobjekt IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';


--
-- Name: COLUMN xp_bereich.rasterbasis; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.rasterbasis IS 'Assoziation zu: FeatureType XP_RasterplanBasis (xp_rasterplanbasis) 0..1';


--
-- Name: rp_bereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_bereich (
    versionbrog date,
    versionbrogtext character varying,
    versionlplg date,
    versionlplgtext character varying,
    geltungsmassstab integer,
    gehoertzuplan text NOT NULL
)
INHERITS (xp_bereich);


--
-- Name: TABLE rp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_bereich IS 'FeatureType: "RP_Bereich"';


--
-- Name: COLUMN rp_bereich.versionbrog; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionbrog IS 'versionBROG  Date 0..1';


--
-- Name: COLUMN rp_bereich.versionbrogtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionbrogtext IS 'versionBROGText  CharacterString 0..1';


--
-- Name: COLUMN rp_bereich.versionlplg; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionlplg IS 'versionLPLG  Date 0..1';


--
-- Name: COLUMN rp_bereich.versionlplgtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionlplgtext IS 'versionLPLGText  CharacterString 0..1';


--
-- Name: COLUMN rp_bereich.geltungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.geltungsmassstab IS 'geltungsmassstab  Integer 0..1';


--
-- Name: COLUMN rp_bereich.gehoertzuplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.gehoertzuplan IS 'Assoziation zu: FeatureType RP_Plan (rp_plan) 1';


--
-- Name: rp_freiraum; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_freiraum (
    istausgleichsgebiet boolean,
    imverbund boolean
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_freiraum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_freiraum IS 'FeatureType: "RP_Freiraum"';


--
-- Name: COLUMN rp_freiraum.istausgleichsgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_freiraum.istausgleichsgebiet IS 'istAusgleichsgebiet  boolean 0..1';


--
-- Name: COLUMN rp_freiraum.imverbund; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_freiraum.imverbund IS 'imVerbund  boolean 0..1';


--
-- Name: rp_bodenschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_bodenschutz (
    typ rp_bodenschutztypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_bodenschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_bodenschutz IS 'FeatureType: "RP_Bodenschutz"';


--
-- Name: COLUMN rp_bodenschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bodenschutz.typ IS 'typ enumeration RP_BodenschutzTypen 0..1';


--
-- Name: rp_siedlung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_siedlung (
    bauhoehenbeschraenkung integer,
    istsiedlungsbeschraenkung boolean
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_siedlung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_siedlung IS 'FeatureType: "RP_Siedlung"';


--
-- Name: COLUMN rp_siedlung.bauhoehenbeschraenkung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_siedlung.bauhoehenbeschraenkung IS 'bauhoehenbeschraenkung  Integer 0..1';


--
-- Name: COLUMN rp_siedlung.istsiedlungsbeschraenkung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_siedlung.istsiedlungsbeschraenkung IS 'istSiedlungsbeschraenkung  Boolean 0..1';


--
-- Name: rp_einzelhandel; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_einzelhandel (
    typ rp_einzelhandeltypen[]
)
INHERITS (rp_siedlung);


--
-- Name: TABLE rp_einzelhandel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_einzelhandel IS 'FeatureType: "RP_Einzelhandel"';


--
-- Name: COLUMN rp_einzelhandel.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_einzelhandel.typ IS 'typ enumeration RP_EinzelhandelTypen 0..*';


--
-- Name: rp_energieversorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_energieversorgung (
    typ rp_energieversorgungtypen[],
    primaerenergietyp rp_primaerenergietypen[],
    spannung rp_spannungtypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_energieversorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_energieversorgung IS 'FeatureType: "RP_Energieversorgung"';


--
-- Name: COLUMN rp_energieversorgung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.typ IS 'typ enumeration RP_EnergieversorgungTypen 0..*';


--
-- Name: COLUMN rp_energieversorgung.primaerenergietyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.primaerenergietyp IS 'primaerenergieTyp enumeration RP_PrimaerenergieTypen 0..*';


--
-- Name: COLUMN rp_energieversorgung.spannung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.spannung IS 'spannung enumeration RP_SpannungTypen 0..1';


--
-- Name: rp_entsorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_entsorgung (
    typae rp_abfallentsorgungtypen[],
    abfalltyp rp_abfalltypen[],
    typaw rp_abwassertypen[],
    istaufschuettungablagerung boolean
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_entsorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_entsorgung IS 'FeatureType: "RP_Entsorgung"';


--
-- Name: COLUMN rp_entsorgung.typae; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.typae IS 'typAE enumeration RP_AbfallentsorgungTypen 0..*';


--
-- Name: COLUMN rp_entsorgung.abfalltyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.abfalltyp IS 'abfallTyp enumeration RP_AbfallTypen 0..*';


--
-- Name: COLUMN rp_entsorgung.typaw; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.typaw IS 'typAW enumeration RP_AbwasserTypen 0..*';


--
-- Name: COLUMN rp_entsorgung.istaufschuettungablagerung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.istaufschuettungablagerung IS 'istAufschuettungAblagerung  Boolean 0..1';


--
-- Name: rp_erholung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_erholung (
    typerholung rp_erholungtypen[],
    typtourismus rp_tourismustypen[],
    besonderertyp rp_besonderetourismuserholungtypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_erholung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_erholung IS 'FeatureType: "RP_Erholung"';


--
-- Name: COLUMN rp_erholung.typerholung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.typerholung IS 'typErholung enumeration RP_ErholungTypen 0..*';


--
-- Name: COLUMN rp_erholung.typtourismus; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.typtourismus IS 'typTourismus enumeration RP_TourismusTypen 0..*';


--
-- Name: COLUMN rp_erholung.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.besonderertyp IS 'besondererTyp enumeration RP_BesondereTourismusErholungTypen 0..1';


--
-- Name: rp_erneuerbareenergie; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_erneuerbareenergie (
    typ rp_erneuerbareenergietypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_erneuerbareenergie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_erneuerbareenergie IS 'FeatureType: "RP_ErneuerbareEnergie"';


--
-- Name: COLUMN rp_erneuerbareenergie.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erneuerbareenergie.typ IS 'typ enumeration RP_ErneuerbareEnergieTypen 0..1';


--
-- Name: rp_forstwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_forstwirtschaft (
    typ rp_forstwirtschafttypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_forstwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_forstwirtschaft IS 'FeatureType: "RP_Forstwirtschaft"';


--
-- Name: COLUMN rp_forstwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_forstwirtschaft.typ IS 'typ enumeration RP_ForstwirtschaftTypen 0..1';


--
-- Name: rp_funktionszuweisung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_funktionszuweisung (
    typ rp_funktionszuweisungtypen[] NOT NULL,
    bezeichnung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_funktionszuweisung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_funktionszuweisung IS 'FeatureType: "RP_Funktionszuweisung"';


--
-- Name: COLUMN rp_funktionszuweisung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_funktionszuweisung.typ IS 'typ enumeration RP_FunktionszuweisungTypen 1..*';


--
-- Name: COLUMN rp_funktionszuweisung.bezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_funktionszuweisung.bezeichnung IS 'bezeichnung  CharacterString 0..1';


--
-- Name: rp_generischesobjekttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_generischesobjekttypen (
    codespace text,
    id character varying NOT NULL,
    value text
);


--
-- Name: TABLE rp_generischesobjekttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_generischesobjekttypen IS 'Alias: "RP_GenerischesObjektTypen", UML-Typ: Code Liste';


--
-- Name: COLUMN rp_generischesobjekttypen.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekttypen.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN rp_generischesobjekttypen.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekttypen.id IS 'id  character varying ';


--
-- Name: rp_generischesobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_generischesobjekt (
    typ rp_generischesobjekttypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_generischesobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_generischesobjekt IS 'FeatureType: "RP_GenerischesObjekt"';


--
-- Name: COLUMN rp_generischesobjekt.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekt.typ IS 'typ CodeList RP_GenerischesObjektTypen 0..1';


--
-- Name: rp_gewaesser; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_gewaesser (
    gewaessertyp character varying
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_gewaesser; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_gewaesser IS 'FeatureType: "RP_Gewaesser"';


--
-- Name: COLUMN rp_gewaesser.gewaessertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_gewaesser.gewaessertyp IS 'gewaesserTyp  CharacterString 0..1';


--
-- Name: rp_sonstgrenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstgrenzetypen (
    codespace text,
    id character varying NOT NULL,
    value text
);


--
-- Name: TABLE rp_sonstgrenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstgrenzetypen IS 'Alias: "RP_SonstGrenzeTypen", UML-Typ: Code Liste';


--
-- Name: COLUMN rp_sonstgrenzetypen.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstgrenzetypen.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN rp_sonstgrenzetypen.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstgrenzetypen.id IS 'id  character varying ';


--
-- Name: rp_grenze; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_grenze (
    typ xp_grenzetypen[],
    spezifischertyp rp_spezifischegrenzetypen,
    sonsttyp rp_sonstgrenzetypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_grenze; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_grenze IS 'FeatureType: "RP_Grenze"';


--
-- Name: COLUMN rp_grenze.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.typ IS 'typ enumeration XP_GrenzeTypen 0..*';


--
-- Name: COLUMN rp_grenze.spezifischertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.spezifischertyp IS 'spezifischerTyp enumeration RP_SpezifischeGrenzeTypen 0..1';


--
-- Name: COLUMN rp_grenze.sonsttyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.sonsttyp IS 'sonstTyp CodeList RP_SonstGrenzeTypen 0..1';


--
-- Name: rp_gruenzuggruenzaesur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_gruenzuggruenzaesur (
    typ rp_zaesurtypen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_gruenzuggruenzaesur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_gruenzuggruenzaesur IS 'FeatureType: "RP_GruenzugGruenzaesur"';


--
-- Name: COLUMN rp_gruenzuggruenzaesur.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_gruenzuggruenzaesur.typ IS 'typ enumeration RP_ZaesurTypen 0..*';


--
-- Name: rp_hochwasserschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_hochwasserschutz (
    typ rp_hochwasserschutztypen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_hochwasserschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_hochwasserschutz IS 'FeatureType: "RP_Hochwasserschutz"';


--
-- Name: COLUMN rp_hochwasserschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_hochwasserschutz.typ IS 'typ enumeration RP_HochwasserschutzTypen 0..*';


--
-- Name: rp_industriegewerbe; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_industriegewerbe (
    typ rp_industriegewerbetypen[]
)
INHERITS (rp_siedlung);


--
-- Name: TABLE rp_industriegewerbe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_industriegewerbe IS 'FeatureType: "RP_IndustrieGewerbe"';


--
-- Name: COLUMN rp_industriegewerbe.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_industriegewerbe.typ IS 'typ enumeration RP_IndustrieGewerbeTypen 0..*';


--
-- Name: rp_klimaschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_klimaschutz (
    typ rp_lufttypen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_klimaschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_klimaschutz IS 'FeatureType: "RP_Klimaschutz"';


--
-- Name: COLUMN rp_klimaschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_klimaschutz.typ IS 'typ enumeration RP_LuftTypen 0..*';


--
-- Name: rp_kommunikation; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_kommunikation (
    typ rp_kommunikationtypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_kommunikation; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_kommunikation IS 'FeatureType: "RP_Kommunikation"';


--
-- Name: COLUMN rp_kommunikation.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_kommunikation.typ IS 'typ enumeration RP_KommunikationTypen 0..1';


--
-- Name: rp_kulturlandschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_kulturlandschaft (
    typ rp_kulturlandschafttypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_kulturlandschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_kulturlandschaft IS 'FeatureType: "RP_Kulturlandschaft"';


--
-- Name: COLUMN rp_kulturlandschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_kulturlandschaft.typ IS 'typ enumeration RP_KulturlandschaftTypen 0..1';


--
-- Name: rp_laermschutzbauschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_laermschutzbauschutz (
    typ rp_laermschutztypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_laermschutzbauschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_laermschutzbauschutz IS 'FeatureType: "RP_LaermschutzBauschutz"';


--
-- Name: COLUMN rp_laermschutzbauschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_laermschutzbauschutz.typ IS 'typ enumeration RP_LaermschutzTypen 0..1';


--
-- Name: rp_landwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_landwirtschaft (
    typ rp_landwirtschafttypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_landwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_landwirtschaft IS 'FeatureType: "RP_Landwirtschaft"';


--
-- Name: COLUMN rp_landwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_landwirtschaft.typ IS 'typ enumeration RP_LandwirtschaftTypen 0..1';


--
-- Name: rp_legendenobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_legendenobjekt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    legendenbezeichnung character varying NOT NULL,
    reflegendenbild xp_externereferenz NOT NULL,
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    gehoertzupraesentationsobjekt text
);


--
-- Name: TABLE rp_legendenobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_legendenobjekt IS 'FeatureType: "RP_Legendenobjekt"';


--
-- Name: COLUMN rp_legendenobjekt.legendenbezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.legendenbezeichnung IS 'legendenBezeichnung  CharacterString 1';


--
-- Name: COLUMN rp_legendenobjekt.reflegendenbild; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.reflegendenbild IS 'reflegendenBild DataType XP_ExterneReferenz 1';


--
-- Name: COLUMN rp_legendenobjekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.user_id IS 'user_id  integer ';


--
-- Name: COLUMN rp_legendenobjekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN rp_legendenobjekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN rp_legendenobjekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN rp_legendenobjekt.gehoertzupraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.gehoertzupraesentationsobjekt IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..1';


--
-- Name: rp_verkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_verkehr (
    allgemeinertyp rp_verkehrtypen[],
    status rp_verkehrstatus[],
    bezeichnung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_verkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_verkehr IS 'FeatureType: "RP_Verkehr"';


--
-- Name: COLUMN rp_verkehr.allgemeinertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.allgemeinertyp IS 'allgemeinerTyp enumeration RP_VerkehrTypen 0..*';


--
-- Name: COLUMN rp_verkehr.status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.status IS 'status enumeration RP_VerkehrStatus 0..*';


--
-- Name: COLUMN rp_verkehr.bezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.bezeichnung IS 'bezeichnung  CharacterString 0..1';


--
-- Name: rp_luftverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_luftverkehr (
    typ rp_luftverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- Name: TABLE rp_luftverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_luftverkehr IS 'FeatureType: "RP_Luftverkehr"';


--
-- Name: COLUMN rp_luftverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_luftverkehr.typ IS 'typ enumeration RP_LuftverkehrTypen 0..*';


--
-- Name: rp_naturlandschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_naturlandschaft (
    typ rp_naturlandschafttypen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_naturlandschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_naturlandschaft IS 'FeatureType: "RP_NaturLandschaft"';


--
-- Name: COLUMN rp_naturlandschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturlandschaft.typ IS 'typ enumeration RP_NaturLandschaftTypen 0..*';


--
-- Name: rp_naturschutzrechtlichesschutzgebiet; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_naturschutzrechtlichesschutzgebiet (
    typ xp_klassifizschutzgebietnaturschutzrecht[],
    istkernzone boolean
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_naturschutzrechtlichesschutzgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_naturschutzrechtlichesschutzgebiet IS 'FeatureType: "RP_NaturschutzrechtlichesSchutzgebiet"';


--
-- Name: COLUMN rp_naturschutzrechtlichesschutzgebiet.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturschutzrechtlichesschutzgebiet.typ IS 'typ enumeration XP_KlassifizSchutzgebietNaturschutzrecht 0..*';


--
-- Name: COLUMN rp_naturschutzrechtlichesschutzgebiet.istkernzone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturschutzrechtlichesschutzgebiet.istkernzone IS 'istKernzone  Boolean 0..1';


--
-- Name: rp_sonstplanart; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstplanart (
    codespace text,
    id character varying NOT NULL
);


--
-- Name: TABLE rp_sonstplanart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstplanart IS 'Alias: "RP_SonstPlanArt", UML-Typ: Code Liste';


--
-- Name: COLUMN rp_sonstplanart.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstplanart.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN rp_sonstplanart.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstplanart.id IS 'id  character varying ';


--
-- Name: rp_status; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_status (
    codespace text,
    id character varying NOT NULL
);


--
-- Name: TABLE rp_status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_status IS 'Alias: "RP_Status", UML-Typ: Code Liste';


--
-- Name: COLUMN rp_status.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_status.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN rp_status.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_status.id IS 'id  character varying ';


--
-- Name: xp_plan; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_plan (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    name character varying NOT NULL,
    nummer character varying,
    internalid character varying,
    beschreibung character varying,
    kommentar character varying,
    technherstelldatum date,
    genehmigungsdatum date,
    untergangsdatum date,
    aendert xp_verbundenerplan[],
    wurdegeaendertvon xp_verbundenerplan[],
    erstellungsmassstab integer,
    bezugshoehe double precision,
    raeumlichergeltungsbereich public.geometry(MultiPolygon) NOT NULL,
    verfahrensmerkmale xp_verfahrensmerkmal[],
    hatgenerattribut xp_generattribut[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    texte text[],
    begruendungstexte text[],
    externereferenz xp_spezexternereferenz[],
    inverszu_verbundenerplan_xp_verbundenerplan text[]
);


--
-- Name: TABLE xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_plan IS 'FeatureType: "XP_Plan"';


--
-- Name: COLUMN xp_plan.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.name IS 'name  CharacterString 1';


--
-- Name: COLUMN xp_plan.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.nummer IS 'nummer  CharacterString 0..1';


--
-- Name: COLUMN xp_plan.internalid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.internalid IS 'internalId  CharacterString 0..1';


--
-- Name: COLUMN xp_plan.beschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.beschreibung IS 'beschreibung  CharacterString 0..1';


--
-- Name: COLUMN xp_plan.kommentar; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.kommentar IS 'kommentar  CharacterString 0..1';


--
-- Name: COLUMN xp_plan.technherstelldatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.technherstelldatum IS 'technHerstellDatum  Date 0..1';


--
-- Name: COLUMN xp_plan.genehmigungsdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.genehmigungsdatum IS 'genehmigungsDatum  Date 0..1';


--
-- Name: COLUMN xp_plan.untergangsdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.untergangsdatum IS 'untergangsDatum  Date 0..1';


--
-- Name: COLUMN xp_plan.aendert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.aendert IS 'aendert DataType XP_VerbundenerPlan 0..*';


--
-- Name: COLUMN xp_plan.wurdegeaendertvon; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.wurdegeaendertvon IS 'wurdeGeaendertVon DataType XP_VerbundenerPlan 0..*';


--
-- Name: COLUMN xp_plan.erstellungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';


--
-- Name: COLUMN xp_plan.bezugshoehe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.bezugshoehe IS 'bezugshoehe  Length 0..1';


--
-- Name: COLUMN xp_plan.raeumlichergeltungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.raeumlichergeltungsbereich IS 'raeumlicherGeltungsbereich Union XP_Flaechengeometrie 1';


--
-- Name: COLUMN xp_plan.verfahrensmerkmale; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.verfahrensmerkmale IS 'verfahrensMerkmale DataType XP_VerfahrensMerkmal 0..*';


--
-- Name: COLUMN xp_plan.hatgenerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';


--
-- Name: COLUMN xp_plan.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_plan.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_plan.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_plan.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_plan.texte; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.texte IS 'Assoziation zu: FeatureType XP_TextAbschnitt (xp_textabschnitt) 0..*';


--
-- Name: COLUMN xp_plan.begruendungstexte; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.begruendungstexte IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';


--
-- Name: COLUMN xp_plan.externereferenz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.externereferenz IS 'externeReferenz DataType XP_SpezExterneReferenz 0..*';


--
-- Name: COLUMN xp_plan.inverszu_verbundenerplan_xp_verbundenerplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.inverszu_verbundenerplan_xp_verbundenerplan IS 'Assoziation zu: FeatureType XP_VerbundenerPlan (xp_verbundenerplan) 0..*';


--
-- Name: rp_plan; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_plan (
    bundesland xp_bundeslaender[] NOT NULL,
    planart rp_art NOT NULL,
    sonstplanart rp_sonstplanart,
    planungsregion integer,
    teilabschnitt integer,
    rechtsstand rp_rechtsstand,
    status rp_status,
    aufstellungsbeschlussdatum date,
    auslegungstartdatum date,
    auslegungenddatum date,
    traegerbeteiligungsstartdatum date,
    traegerbeteiligungsenddatum date,
    aenderungenbisdatum date,
    entwurfsbeschlussdatum date,
    planbeschlussdatum date,
    datumdesinkrafttretens date,
    verfahren rp_verfahren,
    amtlicherschluessel integer,
    bereich text[]
)
INHERITS (xp_plan);


--
-- Name: TABLE rp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_plan IS 'FeatureType: "RP_Plan"';


--
-- Name: COLUMN rp_plan.bundesland; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.bundesland IS 'bundesland enumeration XP_Bundeslaender 1..*';


--
-- Name: COLUMN rp_plan.planart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planart IS 'planArt enumeration RP_Art 1';


--
-- Name: COLUMN rp_plan.sonstplanart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.sonstplanart IS 'sonstPlanArt CodeList RP_SonstPlanArt 0..1';


--
-- Name: COLUMN rp_plan.planungsregion; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planungsregion IS 'planungsregion  Integer 0..1';


--
-- Name: COLUMN rp_plan.teilabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.teilabschnitt IS 'teilabschnitt  Integer 0..1';


--
-- Name: COLUMN rp_plan.rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.rechtsstand IS 'rechtsstand enumeration RP_Rechtsstand 0..1';


--
-- Name: COLUMN rp_plan.status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.status IS 'status CodeList RP_Status 0..1';


--
-- Name: COLUMN rp_plan.aufstellungsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.aufstellungsbeschlussdatum IS 'aufstellungsbeschlussDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.auslegungstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.auslegungstartdatum IS 'auslegungStartDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.auslegungenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.auslegungenddatum IS 'auslegungEndDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.traegerbeteiligungsstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.traegerbeteiligungsstartdatum IS 'traegerbeteiligungsStartDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.traegerbeteiligungsenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.traegerbeteiligungsenddatum IS 'traegerbeteiligungsEndDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.aenderungenbisdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.aenderungenbisdatum IS 'aenderungenBisDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.entwurfsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.entwurfsbeschlussdatum IS 'entwurfsbeschlussDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.planbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planbeschlussdatum IS 'planbeschlussDatum  Date 0..1';


--
-- Name: COLUMN rp_plan.datumdesinkrafttretens; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.datumdesinkrafttretens IS 'datumDesInkrafttretens  Date 0..1';


--
-- Name: COLUMN rp_plan.verfahren; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.verfahren IS 'verfahren enumeration RP_Verfahren 0..1';


--
-- Name: COLUMN rp_plan.amtlicherschluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.amtlicherschluessel IS 'amtlicherSchluessel  Integer 0..1';


--
-- Name: COLUMN rp_plan.bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.bereich IS 'Assoziation zu: FeatureType RP_Bereich (rp_bereich) 0..*';


--
-- Name: rp_planungsraum; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_planungsraum (
    planungsraumbeschreibung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_planungsraum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_planungsraum IS 'FeatureType: "RP_Planungsraum"';


--
-- Name: COLUMN rp_planungsraum.planungsraumbeschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_planungsraum.planungsraumbeschreibung IS 'planungsraumBeschreibung  CharacterString 0..1';


--
-- Name: rp_radwegwanderweg; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_radwegwanderweg (
    typ rp_radwegwanderwegtypen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_radwegwanderweg; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_radwegwanderweg IS 'FeatureType: "RP_RadwegWanderweg"';


--
-- Name: COLUMN rp_radwegwanderweg.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_radwegwanderweg.typ IS 'typ enumeration RP_RadwegWanderwegTypen 0..*';


--
-- Name: rp_raumkategorie; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_raumkategorie (
    besonderertyp rp_besondereraumkategorietypen,
    typ rp_raumkategorietypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_raumkategorie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_raumkategorie IS 'FeatureType: "RP_Raumkategorie"';


--
-- Name: COLUMN rp_raumkategorie.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_raumkategorie.besonderertyp IS 'besondererTyp enumeration RP_BesondereRaumkategorieTypen 0..1';


--
-- Name: COLUMN rp_raumkategorie.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_raumkategorie.typ IS 'typ enumeration RP_RaumkategorieTypen 0..*';


--
-- Name: rp_rohstoff; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_rohstoff (
    rohstofftyp rp_rohstofftypen[],
    folgenutzung rp_bergbaufolgenutzung[],
    folgenutzungtext character varying,
    zeitstufe rp_zeitstufen,
    zeitstufetext character varying,
    tiefe rp_bodenschatztiefen,
    bergbauplanungtyp rp_bergbauplanungtypen[],
    istaufschuettungablagerung boolean
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_rohstoff; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_rohstoff IS 'FeatureType: "RP_Rohstoff"';


--
-- Name: COLUMN rp_rohstoff.rohstofftyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.rohstofftyp IS 'rohstoffTyp enumeration RP_RohstoffTypen 0..*';


--
-- Name: COLUMN rp_rohstoff.folgenutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.folgenutzung IS 'folgenutzung enumeration RP_BergbauFolgenutzung 0..*';


--
-- Name: COLUMN rp_rohstoff.folgenutzungtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.folgenutzungtext IS 'folgenutzungText  CharacterString 0..1';


--
-- Name: COLUMN rp_rohstoff.zeitstufe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.zeitstufe IS 'zeitstufe enumeration RP_Zeitstufen 0..1';


--
-- Name: COLUMN rp_rohstoff.zeitstufetext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.zeitstufetext IS 'zeitstufeText  CharacterString 0..1';


--
-- Name: COLUMN rp_rohstoff.tiefe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.tiefe IS 'tiefe enumeration RP_BodenschatzTiefen 0..1';


--
-- Name: COLUMN rp_rohstoff.bergbauplanungtyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.bergbauplanungtyp IS 'bergbauplanungTyp enumeration RP_BergbauplanungTypen 0..*';


--
-- Name: COLUMN rp_rohstoff.istaufschuettungablagerung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.istaufschuettungablagerung IS 'istAufschuettungAblagerung  Boolean 0..1';


--
-- Name: rp_schienenverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_schienenverkehr (
    typ rp_schienenverkehrtypen[],
    besonderertyp rp_besondererschienenverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- Name: TABLE rp_schienenverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_schienenverkehr IS 'FeatureType: "RP_Schienenverkehr"';


--
-- Name: COLUMN rp_schienenverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_schienenverkehr.typ IS 'typ enumeration RP_SchienenverkehrTypen 0..*';


--
-- Name: COLUMN rp_schienenverkehr.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_schienenverkehr.besonderertyp IS 'besondererTyp enumeration RP_BesondererSchienenverkehrTypen 0..*';


--
-- Name: rp_sonstigeinfrastruktur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigeinfrastruktur (
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_sonstigeinfrastruktur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigeinfrastruktur IS 'FeatureType: "RP_SonstigeInfrastruktur"';


--
-- Name: rp_sonstigerfreiraumschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigerfreiraumschutz (
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_sonstigerfreiraumschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigerfreiraumschutz IS 'FeatureType: "RP_SonstigerFreiraumschutz"';


--
-- Name: rp_sonstigersiedlungsbereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigersiedlungsbereich (
)
INHERITS (rp_siedlung);


--
-- Name: TABLE rp_sonstigersiedlungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigersiedlungsbereich IS 'FeatureType: "RP_SonstigerSiedlungsbereich"';


--
-- Name: rp_sonstverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstverkehr (
    typ rp_sonstverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- Name: TABLE rp_sonstverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstverkehr IS 'FeatureType: "RP_SonstVerkehr"';


--
-- Name: COLUMN rp_sonstverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstverkehr.typ IS 'typ enumeration RP_SonstVerkehrTypen 0..*';


--
-- Name: rp_sozialeinfrastruktur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sozialeinfrastruktur (
    typ rp_sozialeinfrastrukturtypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_sozialeinfrastruktur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sozialeinfrastruktur IS 'FeatureType: "RP_SozialeInfrastruktur"';


--
-- Name: COLUMN rp_sozialeinfrastruktur.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sozialeinfrastruktur.typ IS 'typ enumeration RP_SozialeInfrastrukturTypen 0..*';


--
-- Name: rp_sperrgebiet; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sperrgebiet (
    typ rp_sperrgebiettypen
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_sperrgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sperrgebiet IS 'FeatureType: "RP_Sperrgebiet"';


--
-- Name: COLUMN rp_sperrgebiet.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sperrgebiet.typ IS 'typ enumeration RP_SperrgebietTypen 0..1';


--
-- Name: rp_sportanlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sportanlage (
    typ rp_sportanlagetypen
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_sportanlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sportanlage IS 'FeatureType: "RP_Sportanlage"';


--
-- Name: COLUMN rp_sportanlage.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sportanlage.typ IS 'typ enumeration RP_SportanlageTypen 0..1';


--
-- Name: rp_strassenverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_strassenverkehr (
    typ rp_strassenverkehrtypen[],
    besonderertyp rp_besondererstrassenverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- Name: TABLE rp_strassenverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_strassenverkehr IS 'FeatureType: "RP_Strassenverkehr"';


--
-- Name: COLUMN rp_strassenverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_strassenverkehr.typ IS 'typ enumeration RP_StrassenverkehrTypen 0..*';


--
-- Name: COLUMN rp_strassenverkehr.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_strassenverkehr.besonderertyp IS 'besondererTyp enumeration RP_BesondererStrassenverkehrTypen 0..*';


--
-- Name: xp_textabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_textabschnitt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    schluessel character varying,
    gesetzlichegrundlage character varying,
    text character varying,
    reftext xp_externereferenz,
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    inverszu_reftextinhalt_xp_objekt text,
    inverszu_texte_xp_plan text,
    inverszu_abweichungtext_bp_baugebietsteilflaeche text[],
    inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche text[]
);


--
-- Name: TABLE xp_textabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_textabschnitt IS 'FeatureType: "XP_TextAbschnitt"';


--
-- Name: COLUMN xp_textabschnitt.schluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.schluessel IS 'schluessel  CharacterString 0..1';


--
-- Name: COLUMN xp_textabschnitt.gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.gesetzlichegrundlage IS 'gesetzlicheGrundlage  CharacterString 0..1';


--
-- Name: COLUMN xp_textabschnitt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.text IS 'text  CharacterString 0..1';


--
-- Name: COLUMN xp_textabschnitt.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- Name: COLUMN xp_textabschnitt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_textabschnitt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_textabschnitt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_textabschnitt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_textabschnitt.inverszu_reftextinhalt_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_reftextinhalt_xp_objekt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..1';


--
-- Name: COLUMN xp_textabschnitt.inverszu_texte_xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_texte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..1';


--
-- Name: COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_baugebietsteilflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_baugebietsteilflaeche IS 'Assoziation zu: FeatureType BP_BaugebietsTeilFlaeche (bp_baugebietsteilflaeche) 0..*';


--
-- Name: COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche IS 'Assoziation zu: FeatureType BP_NebenanlagenAusschlussFlaeche (bp_nebenanlagenausschlussflaeche) 0..*';


--
-- Name: rp_textabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_textabschnitt (
    rechtscharakter rp_rechtscharakter NOT NULL
)
INHERITS (xp_textabschnitt);


--
-- Name: TABLE rp_textabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_textabschnitt IS 'FeatureType: "RP_TextAbschnitt"';


--
-- Name: COLUMN rp_textabschnitt.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_textabschnitt.rechtscharakter IS 'rechtscharakter enumeration RP_Rechtscharakter 1';


--
-- Name: rp_textabschnitt_zu_rp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_textabschnitt_zu_rp_objekt (
    rp_textabschnitt_gml_id text NOT NULL,
    rp_objekt_gml_id text NOT NULL
);


--
-- Name: TABLE rp_textabschnitt_zu_rp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_textabschnitt_zu_rp_objekt IS 'Association RP_TextAbschnitt _zu_ RP_Objekt';


--
-- Name: COLUMN rp_textabschnitt_zu_rp_objekt.rp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_textabschnitt_zu_rp_objekt.rp_objekt_gml_id IS 'refTextInhalt';


--
-- Name: rp_wasserschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserschutz (
    typ rp_wasserschutztypen,
    zone rp_wasserschutzzonen[]
)
INHERITS (rp_freiraum);


--
-- Name: TABLE rp_wasserschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserschutz IS 'FeatureType: "RP_Wasserschutz"';


--
-- Name: COLUMN rp_wasserschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserschutz.typ IS 'typ enumeration RP_WasserschutzTypen 0..1';


--
-- Name: COLUMN rp_wasserschutz.zone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserschutz.zone IS 'zone enumeration RP_WasserschutzZonen 0..*';


--
-- Name: rp_wasserverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserverkehr (
    typ rp_wasserverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- Name: TABLE rp_wasserverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserverkehr IS 'FeatureType: "RP_Wasserverkehr"';


--
-- Name: COLUMN rp_wasserverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserverkehr.typ IS 'typ enumeration RP_WasserverkehrTypen 0..*';


--
-- Name: rp_wasserwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserwirtschaft (
    typ rp_wasserwirtschafttypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_wasserwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserwirtschaft IS 'FeatureType: "RP_Wasserwirtschaft"';


--
-- Name: COLUMN rp_wasserwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserwirtschaft.typ IS 'typ enumeration RP_WasserwirtschaftTypen 0..*';


--
-- Name: rp_wohnensiedlung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wohnensiedlung (
    typ rp_wohnensiedlungtypen[]
)
INHERITS (rp_siedlung);


--
-- Name: TABLE rp_wohnensiedlung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wohnensiedlung IS 'FeatureType: "RP_WohnenSiedlung"';


--
-- Name: COLUMN rp_wohnensiedlung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wohnensiedlung.typ IS 'typ enumeration RP_WohnenSiedlungTypen 0..*';


--
-- Name: rp_zentralerort; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_zentralerort (
    typ rp_zentralerorttypen[] NOT NULL,
    sonstigertyp rp_zentralerortsonstigetypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- Name: TABLE rp_zentralerort; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_zentralerort IS 'FeatureType: "RP_ZentralerOrt"';


--
-- Name: COLUMN rp_zentralerort.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_zentralerort.typ IS 'typ enumeration RP_ZentralerOrtTypen 1..*';


--
-- Name: COLUMN rp_zentralerort.sonstigertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_zentralerort.sonstigertyp IS 'sonstigerTyp enumeration RP_ZentralerOrtSonstigeTypen 0..*';


--
-- Name: xp_stylesheetliste; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_stylesheetliste (
    codespace text,
    id character varying NOT NULL
);


--
-- Name: TABLE xp_stylesheetliste; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_stylesheetliste IS 'Alias: "XP_StylesheetListe", UML-Typ: Code Liste';


--
-- Name: COLUMN xp_stylesheetliste.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stylesheetliste.codespace IS 'codeSpace  text ';


--
-- Name: COLUMN xp_stylesheetliste.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stylesheetliste.id IS 'id  character varying ';


--
-- Name: xp_abstraktespraesentationsobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_abstraktespraesentationsobjekt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    stylesheetid xp_stylesheetliste,
    darstellungsprioritaet integer,
    art character varying[],
    index integer[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    dientzurdarstellungvon text[],
    gehoertzubereich text,
    inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt text
);


--
-- Name: TABLE xp_abstraktespraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_abstraktespraesentationsobjekt IS 'FeatureType: "XP_AbstraktesPraesentationsobjekt"';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.stylesheetid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.stylesheetid IS 'stylesheetId CodeList XP_StylesheetListe 0..1';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.darstellungsprioritaet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.darstellungsprioritaet IS 'darstellungsprioritaet  Integer 0..1';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.art; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.art IS 'art  CharacterString 0..*';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.index; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.index IS 'index  Integer 0..*';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.dientzurdarstellungvon; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.dientzurdarstellungvon IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.gehoertzubereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';


--
-- Name: COLUMN xp_abstraktespraesentationsobjekt.inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt IS 'Assoziation zu: FeatureType RP_Legendenobjekt (rp_legendenobjekt) 0..1';


--
-- Name: xp_begruendungabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_begruendungabschnitt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    schluessel character varying,
    text character varying,
    reftext xp_externereferenz,
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    inverszu_refbegruendunginhalt_xp_objekt text[],
    inverszu_begruendungstexte_xp_plan text[]
);


--
-- Name: TABLE xp_begruendungabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_begruendungabschnitt IS 'FeatureType: "XP_BegruendungAbschnitt"';


--
-- Name: COLUMN xp_begruendungabschnitt.schluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.schluessel IS 'schluessel  CharacterString 0..1';


--
-- Name: COLUMN xp_begruendungabschnitt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.text IS 'text  CharacterString 0..1';


--
-- Name: COLUMN xp_begruendungabschnitt.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- Name: COLUMN xp_begruendungabschnitt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.user_id IS 'user_id  integer ';


--
-- Name: COLUMN xp_begruendungabschnitt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.created_at IS 'created_at  timestamp without time zone ';


--
-- Name: COLUMN xp_begruendungabschnitt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- Name: COLUMN xp_begruendungabschnitt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- Name: COLUMN xp_begruendungabschnitt.inverszu_refbegruendunginhalt_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_refbegruendunginhalt_xp_objekt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- Name: COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..*';


SET default_with_oids = false;

--
-- Name: xp_bereich_zu_xp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_bereich_zu_xp_objekt (
    xp_bereich_gml_id uuid NOT NULL,
    xp_objekt_gml_id uuid NOT NULL
);


--
-- Name: TABLE xp_bereich_zu_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_bereich_zu_xp_objekt IS 'Association XP_Bereich _zu_ XP_Objekt';


--
-- Name: COLUMN xp_bereich_zu_xp_objekt.xp_bereich_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich_zu_xp_objekt.xp_bereich_gml_id IS 'planinhalt';


--
-- Name: COLUMN xp_bereich_zu_xp_objekt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich_zu_xp_objekt.xp_objekt_gml_id IS 'gehoertZuBereich';


SET default_with_oids = true;

--
-- Name: xp_fpo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_fpo (
    "position" public.geometry(MultiPolygon) NOT NULL
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- Name: TABLE xp_fpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_fpo IS 'FeatureType: "XP_FPO"';


--
-- Name: COLUMN xp_fpo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_fpo."position" IS 'position Union XP_Flaechengeometrie 1';


--
-- Name: xp_lpo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_lpo (
    "position" public.geometry(MultiLineString) NOT NULL,
    inverszu_hat_xp_ppo text[],
    inverszu_hat_xp_tpo text[]
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- Name: TABLE xp_lpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_lpo IS 'FeatureType: "XP_LPO"';


--
-- Name: COLUMN xp_lpo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo."position" IS 'position Union XP_Liniengeometrie 1';


--
-- Name: COLUMN xp_lpo.inverszu_hat_xp_ppo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_ppo IS 'Assoziation zu: FeatureType XP_PPO (xp_ppo) 0..*';


--
-- Name: COLUMN xp_lpo.inverszu_hat_xp_tpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_tpo IS 'Assoziation zu: FeatureType XP_TPO (xp_tpo) 0..*';


--
-- Name: xp_tpo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_tpo (
    schriftinhalt character varying,
    fontsperrung double precision,
    skalierung double precision,
    horizontaleausrichtung xp_horizontaleausrichtung,
    vertikaleausrichtung xp_vertikaleausrichtung,
    hat text
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- Name: TABLE xp_tpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_tpo IS 'FeatureType: "XP_TPO"';


--
-- Name: COLUMN xp_tpo.schriftinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.schriftinhalt IS 'schriftinhalt  CharacterString 0..1';


--
-- Name: COLUMN xp_tpo.fontsperrung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.fontsperrung IS 'fontSperrung  Decimal 0..1';


--
-- Name: COLUMN xp_tpo.skalierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.skalierung IS 'skalierung  Decimal 0..1';


--
-- Name: COLUMN xp_tpo.horizontaleausrichtung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.horizontaleausrichtung IS 'horizontaleAusrichtung enumeration XP_HorizontaleAusrichtung 0..1';


--
-- Name: COLUMN xp_tpo.vertikaleausrichtung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.vertikaleausrichtung IS 'vertikaleAusrichtung enumeration XP_VertikaleAusrichtung 0..1';


--
-- Name: COLUMN xp_tpo.hat; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';


--
-- Name: xp_lto; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_lto (
    "position" public.geometry(MultiLineString) NOT NULL
)
INHERITS (xp_tpo);


--
-- Name: TABLE xp_lto; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_lto IS 'FeatureType: "XP_LTO"';


--
-- Name: COLUMN xp_lto."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lto."position" IS 'position Union XP_Liniengeometrie 1';


--
-- Name: xp_pto; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_pto (
    "position" public.geometry(MultiPoint) NOT NULL,
    drehwinkel double precision
)
INHERITS (xp_tpo);


--
-- Name: TABLE xp_pto; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_pto IS 'FeatureType: "XP_PTO"';


--
-- Name: COLUMN xp_pto."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_pto."position" IS 'position Union XP_Punktgeometrie 1';


--
-- Name: COLUMN xp_pto.drehwinkel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_pto.drehwinkel IS 'drehwinkel  Angle 0..1';


--
-- Name: xp_nutzungsschablone; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_nutzungsschablone (
    spaltenanz integer NOT NULL,
    zeilenanz integer NOT NULL
)
INHERITS (xp_pto);


--
-- Name: TABLE xp_nutzungsschablone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_nutzungsschablone IS 'FeatureType: "XP_Nutzungsschablone"';


--
-- Name: COLUMN xp_nutzungsschablone.spaltenanz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_nutzungsschablone.spaltenanz IS 'spaltenAnz  Integer 1';


--
-- Name: COLUMN xp_nutzungsschablone.zeilenanz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_nutzungsschablone.zeilenanz IS 'zeilenAnz  Integer 1';


SET default_with_oids = false;

--
-- Name: xp_objekt_zu_xp_abstraktespraesentationsobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt (
    xp_objekt_gml_id uuid NOT NULL,
    xp_abstraktespraesentationsobjekt_gml_id uuid NOT NULL
);


--
-- Name: TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt IS 'Association XP_Objekt _zu_ XP_AbstraktesPraesentationsobjekt';


--
-- Name: COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_objekt_gml_id IS 'wirdDargestelltDurch';


--
-- Name: COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_abstraktespraesentationsobjekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_abstraktespraesentationsobjekt_gml_id IS 'dientZurDarstellungVon';


--
-- Name: xp_objekt_zu_xp_begruendungabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt_zu_xp_begruendungabschnitt (
    xp_objekt_gml_id uuid NOT NULL,
    xp_begruendungabschnitt_gml_id uuid NOT NULL
);


--
-- Name: TABLE xp_objekt_zu_xp_begruendungabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt_zu_xp_begruendungabschnitt IS 'Association XP_Objekt _zu_ XP_BegruendungAbschnitt';


--
-- Name: COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_objekt_gml_id IS 'refBegruendungInhalt';


SET default_with_oids = true;

--
-- Name: xp_ppo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_ppo (
    "position" public.geometry(MultiPoint) NOT NULL,
    drehwinkel double precision,
    skalierung double precision,
    hat text
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- Name: TABLE xp_ppo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_ppo IS 'FeatureType: "XP_PPO"';


--
-- Name: COLUMN xp_ppo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo."position" IS 'position Union XP_Punktgeometrie 1';


--
-- Name: COLUMN xp_ppo.drehwinkel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.drehwinkel IS 'drehwinkel  Angle 0..1';


--
-- Name: COLUMN xp_ppo.skalierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.skalierung IS 'skalierung  Decimal 0..1';


--
-- Name: COLUMN xp_ppo.hat; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';


--
-- Name: xp_praesentationsobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_praesentationsobjekt (
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- Name: TABLE xp_praesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_praesentationsobjekt IS 'FeatureType: "XP_Praesentationsobjekt"';


--
-- Name: xp_rasterdarstellung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_rasterdarstellung (
    gml_id text NOT NULL,
    refscan public.xp_externereferenz[] NOT NULL,
    reftext public.xp_externereferenz,
    reflegende public.xp_externereferenz[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    inverszu_rasterbasis_xp_bereich text[]
);


--
-- Name: TABLE xp_rasterdarstellung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_rasterdarstellung IS 'FeatureType: "XP_Rasterdarstellung"';


--
-- Name: COLUMN xp_rasterdarstellung.refscan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.refscan IS 'refScan DataType XP_ExterneReferenz 1..*';


--
-- Name: COLUMN xp_rasterdarstellung.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- Name: COLUMN xp_rasterdarstellung.reflegende; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';


--
-- Name: COLUMN xp_rasterdarstellung.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.user_id IS 'user_id integer';


--
-- Name: COLUMN xp_rasterdarstellung.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.created_at IS 'created_at timestamp without time zone ';


--
-- Name: COLUMN xp_rasterdarstellung.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterdarstellung.updated_at IS 'updated_at timestamp without time zone ';


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: enum_rp_abfallentsorgungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abfallentsorgungtypen
    ADD CONSTRAINT enum_rp_abfallentsorgungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_abfalltypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abfalltypen
    ADD CONSTRAINT enum_rp_abfalltypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_abwassertypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abwassertypen
    ADD CONSTRAINT enum_rp_abwassertypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_achsentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_achsentypen
    ADD CONSTRAINT enum_rp_achsentypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_art_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_art
    ADD CONSTRAINT enum_rp_art_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_bedeutsamkeit_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bedeutsamkeit
    ADD CONSTRAINT enum_rp_bedeutsamkeit_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_bergbaufolgenutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bergbaufolgenutzung
    ADD CONSTRAINT enum_rp_bergbaufolgenutzung_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_bergbauplanungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bergbauplanungtypen
    ADD CONSTRAINT enum_rp_bergbauplanungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_besondereraumkategorietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondereraumkategorietypen
    ADD CONSTRAINT enum_rp_besondereraumkategorietypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_besondererschienenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondererschienenverkehrtypen
    ADD CONSTRAINT enum_rp_besondererschienenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_besondererstrassenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondererstrassenverkehrtypen
    ADD CONSTRAINT enum_rp_besondererstrassenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_besonderetourismuserholungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besonderetourismuserholungtypen
    ADD CONSTRAINT enum_rp_besonderetourismuserholungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_bodenschatztiefen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bodenschatztiefen
    ADD CONSTRAINT enum_rp_bodenschatztiefen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_bodenschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bodenschutztypen
    ADD CONSTRAINT enum_rp_bodenschutztypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_einzelhandeltypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_einzelhandeltypen
    ADD CONSTRAINT enum_rp_einzelhandeltypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_energieversorgungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_energieversorgungtypen
    ADD CONSTRAINT enum_rp_energieversorgungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_erholungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_erholungtypen
    ADD CONSTRAINT enum_rp_erholungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_erneuerbareenergietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_erneuerbareenergietypen
    ADD CONSTRAINT enum_rp_erneuerbareenergietypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_forstwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_forstwirtschafttypen
    ADD CONSTRAINT enum_rp_forstwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_funktionszuweisungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_funktionszuweisungtypen
    ADD CONSTRAINT enum_rp_funktionszuweisungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_gebietstyp_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_gebietstyp
    ADD CONSTRAINT enum_rp_gebietstyp_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_hochwasserschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_hochwasserschutztypen
    ADD CONSTRAINT enum_rp_hochwasserschutztypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_industriegewerbetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_industriegewerbetypen
    ADD CONSTRAINT enum_rp_industriegewerbetypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_kommunikationtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_kommunikationtypen
    ADD CONSTRAINT enum_rp_kommunikationtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_kulturlandschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_kulturlandschafttypen
    ADD CONSTRAINT enum_rp_kulturlandschafttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_laermschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_laermschutztypen
    ADD CONSTRAINT enum_rp_laermschutztypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_landwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_landwirtschafttypen
    ADD CONSTRAINT enum_rp_landwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_lufttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_lufttypen
    ADD CONSTRAINT enum_rp_lufttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_luftverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_luftverkehrtypen
    ADD CONSTRAINT enum_rp_luftverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_naturlandschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_naturlandschafttypen
    ADD CONSTRAINT enum_rp_naturlandschafttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_primaerenergietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_primaerenergietypen
    ADD CONSTRAINT enum_rp_primaerenergietypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_radwegwanderwegtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_radwegwanderwegtypen
    ADD CONSTRAINT enum_rp_radwegwanderwegtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_raumkategorietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_raumkategorietypen
    ADD CONSTRAINT enum_rp_raumkategorietypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_rechtscharakter_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rechtscharakter
    ADD CONSTRAINT enum_rp_rechtscharakter_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_rechtsstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rechtsstand
    ADD CONSTRAINT enum_rp_rechtsstand_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_rohstofftypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rohstofftypen
    ADD CONSTRAINT enum_rp_rohstofftypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_schienenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_schienenverkehrtypen
    ADD CONSTRAINT enum_rp_schienenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_sonstverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sonstverkehrtypen
    ADD CONSTRAINT enum_rp_sonstverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_sozialeinfrastrukturtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sozialeinfrastrukturtypen
    ADD CONSTRAINT enum_rp_sozialeinfrastrukturtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_spannungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_spannungtypen
    ADD CONSTRAINT enum_rp_spannungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_sperrgebiettypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sperrgebiettypen
    ADD CONSTRAINT enum_rp_sperrgebiettypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_spezifischegrenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_spezifischegrenzetypen
    ADD CONSTRAINT enum_rp_spezifischegrenzetypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_sportanlagetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sportanlagetypen
    ADD CONSTRAINT enum_rp_sportanlagetypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_strassenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_strassenverkehrtypen
    ADD CONSTRAINT enum_rp_strassenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_tourismustypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_tourismustypen
    ADD CONSTRAINT enum_rp_tourismustypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_verfahren_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verfahren
    ADD CONSTRAINT enum_rp_verfahren_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_verkehrstatus_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verkehrstatus
    ADD CONSTRAINT enum_rp_verkehrstatus_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_verkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verkehrtypen
    ADD CONSTRAINT enum_rp_verkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_wasserschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserschutztypen
    ADD CONSTRAINT enum_rp_wasserschutztypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_wasserschutzzonen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserschutzzonen
    ADD CONSTRAINT enum_rp_wasserschutzzonen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_wasserverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserverkehrtypen
    ADD CONSTRAINT enum_rp_wasserverkehrtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_wasserwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserwirtschafttypen
    ADD CONSTRAINT enum_rp_wasserwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_wohnensiedlungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wohnensiedlungtypen
    ADD CONSTRAINT enum_rp_wohnensiedlungtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_zaesurtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zaesurtypen
    ADD CONSTRAINT enum_rp_zaesurtypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_zeitstufen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zeitstufen
    ADD CONSTRAINT enum_rp_zeitstufen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_zentralerortsonstigetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zentralerortsonstigetypen
    ADD CONSTRAINT enum_rp_zentralerortsonstigetypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_rp_zentralerorttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zentralerorttypen
    ADD CONSTRAINT enum_rp_zentralerorttypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_abemassnahmentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_abemassnahmentypen
    ADD CONSTRAINT enum_xp_abemassnahmentypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_abweichungbaunvotypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_abweichungbaunvotypen
    ADD CONSTRAINT enum_xp_abweichungbaunvotypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_allgartderbaulnutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_allgartderbaulnutzung
    ADD CONSTRAINT enum_xp_allgartderbaulnutzung_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_anpflanzungbindungerhaltungsgegenstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_anpflanzungbindungerhaltungsgegenstand
    ADD CONSTRAINT enum_xp_anpflanzungbindungerhaltungsgegenstand_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_arthoehenbezug_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_arthoehenbezug
    ADD CONSTRAINT enum_xp_arthoehenbezug_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_arthoehenbezugspunkt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_arthoehenbezugspunkt
    ADD CONSTRAINT enum_xp_arthoehenbezugspunkt_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_bedeutungenbereich_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_bedeutungenbereich
    ADD CONSTRAINT enum_xp_bedeutungenbereich_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_besondereartderbaulnutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_besondereartderbaulnutzung
    ADD CONSTRAINT enum_xp_besondereartderbaulnutzung_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_bundeslaender_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_bundeslaender
    ADD CONSTRAINT enum_xp_bundeslaender_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_externereferenzart_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_externereferenzart
    ADD CONSTRAINT enum_xp_externereferenzart_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_externereferenztyp_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_externereferenztyp
    ADD CONSTRAINT enum_xp_externereferenztyp_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_grenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_grenzetypen
    ADD CONSTRAINT enum_xp_grenzetypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_klassifizschutzgebietnaturschutzrecht_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_klassifizschutzgebietnaturschutzrecht
    ADD CONSTRAINT enum_xp_klassifizschutzgebietnaturschutzrecht_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_nutzungsform_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_nutzungsform
    ADD CONSTRAINT enum_xp_nutzungsform_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_rechtscharakterplanaenderung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_rechtscharakterplanaenderung
    ADD CONSTRAINT enum_xp_rechtscharakterplanaenderung_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_rechtsstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_rechtsstand
    ADD CONSTRAINT enum_xp_rechtsstand_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_sondernutzungen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_sondernutzungen
    ADD CONSTRAINT enum_xp_sondernutzungen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_spemassnahmentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_spemassnahmentypen
    ADD CONSTRAINT enum_xp_spemassnahmentypen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_speziele_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_speziele
    ADD CONSTRAINT enum_xp_speziele_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_verlaengerungveraenderungssperre_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_verlaengerungveraenderungssperre
    ADD CONSTRAINT enum_xp_verlaengerungveraenderungssperre_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmunggemeinbedarf_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggemeinbedarf
    ADD CONSTRAINT enum_xp_zweckbestimmunggemeinbedarf_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmunggewaesser_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggewaesser
    ADD CONSTRAINT enum_xp_zweckbestimmunggewaesser_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmunggruen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggruen
    ADD CONSTRAINT enum_xp_zweckbestimmunggruen_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmungkennzeichnung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungkennzeichnung
    ADD CONSTRAINT enum_xp_zweckbestimmungkennzeichnung_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmunglandwirtschaft_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunglandwirtschaft
    ADD CONSTRAINT enum_xp_zweckbestimmunglandwirtschaft_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmungspielsportanlage_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungspielsportanlage
    ADD CONSTRAINT enum_xp_zweckbestimmungspielsportanlage_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmungverentsorgung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungverentsorgung
    ADD CONSTRAINT enum_xp_zweckbestimmungverentsorgung_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmungwald_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungwald
    ADD CONSTRAINT enum_xp_zweckbestimmungwald_pkey PRIMARY KEY (wert);


--
-- Name: enum_xp_zweckbestimmungwasserwirtschaft_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungwasserwirtschaft
    ADD CONSTRAINT enum_xp_zweckbestimmungwasserwirtschaft_pkey PRIMARY KEY (wert);


--
-- Name: rp_generischesobjekttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_generischesobjekttypen
    ADD CONSTRAINT rp_generischesobjekttypen_pkey PRIMARY KEY (id);


--
-- Name: rp_legendenobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_legendenobjekt
    ADD CONSTRAINT rp_legendenobjekt_pkey PRIMARY KEY (gml_id);


--
-- Name: rp_sonstgrenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_sonstgrenzetypen
    ADD CONSTRAINT rp_sonstgrenzetypen_pkey PRIMARY KEY (id);


--
-- Name: rp_sonstplanart_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_sonstplanart
    ADD CONSTRAINT rp_sonstplanart_pkey PRIMARY KEY (id);


--
-- Name: rp_status_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_status
    ADD CONSTRAINT rp_status_pkey PRIMARY KEY (id);


--
-- Name: rp_textabschnitt_zu_rp_objekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_textabschnitt_zu_rp_objekt
    ADD CONSTRAINT rp_textabschnitt_zu_rp_objekt_pkey PRIMARY KEY (rp_textabschnitt_gml_id, rp_objekt_gml_id);


--
-- Name: xp_abstraktespraesentationsobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_abstraktespraesentationsobjekt
    ADD CONSTRAINT xp_abstraktespraesentationsobjekt_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_begruendungabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_begruendungabschnitt
    ADD CONSTRAINT xp_begruendungabschnitt_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_bereich_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_bereich
    ADD CONSTRAINT xp_bereich_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_bereich_zu_xp_objekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_bereich_zu_xp_objekt
    ADD CONSTRAINT xp_bereich_zu_xp_objekt_pkey PRIMARY KEY (xp_bereich_gml_id, xp_objekt_gml_id);


--
-- Name: xp_gesetzlichegrundlage_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_gesetzlichegrundlage
    ADD CONSTRAINT xp_gesetzlichegrundlage_pkey PRIMARY KEY (id);


--
-- Name: xp_mimetypes_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_mimetypes
    ADD CONSTRAINT xp_mimetypes_pkey PRIMARY KEY (id);


--
-- Name: xp_objekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt
    ADD CONSTRAINT xp_objekt_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_objekt_zu_xp_abstraktespraesentationsobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt_zu_xp_abstraktespraesentationsobjekt
    ADD CONSTRAINT xp_objekt_zu_xp_abstraktespraesentationsobjekt_pkey PRIMARY KEY (xp_objekt_gml_id, xp_abstraktespraesentationsobjekt_gml_id);


--
-- Name: xp_objekt_zu_xp_begruendungabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt_zu_xp_begruendungabschnitt
    ADD CONSTRAINT xp_objekt_zu_xp_begruendungabschnitt_pkey PRIMARY KEY (xp_objekt_gml_id, xp_begruendungabschnitt_gml_id);


--
-- Name: xp_plan_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_plan
    ADD CONSTRAINT xp_plan_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_rasterdarstellung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_rasterdarstellung
    ADD CONSTRAINT xp_rasterdarstellung_pkey PRIMARY KEY (gml_id);


--
-- Name: xp_stylesheetliste_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_stylesheetliste
    ADD CONSTRAINT xp_stylesheetliste_pkey PRIMARY KEY (id);


--
-- Name: xp_textabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_textabschnitt
    ADD CONSTRAINT xp_textabschnitt_pkey PRIMARY KEY (gml_id);


--
-- PostgreSQL database dump complete
--

