BEGIN;

SET search_path = xplan_gml, pg_catalog;

--
-- TOC entry 9101 (class 1247 OID 896761)
-- Name: doublelist; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE doublelist AS (
	list text
);


--
-- TOC entry 12672 (class 0 OID 0)
-- Dependencies: 9101
-- Name: TYPE doublelist; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE doublelist IS 'Alias: "doubleList", ISO 19136 GML Type: list';


--
-- TOC entry 12673 (class 0 OID 0)
-- Dependencies: 9101
-- Name: COLUMN doublelist.list; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN doublelist.list IS 'list Sequence Sequence 0..1';


--
-- TOC entry 9104 (class 1247 OID 896764)
-- Name: measure; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE measure AS (
	value integer
);

COMMIT;

--
-- TOC entry 12674 (class 0 OID 0)
-- Dependencies: 9104
-- Name: TYPE measure; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE measure IS 'Alias: "Measure", ISO 19136 GML Type: value';


--
-- TOC entry 12675 (class 0 OID 0)
-- Dependencies: 9104
-- Name: COLUMN measure.value; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN measure.value IS 'value DataType Integer 0..1';


--
-- TOC entry 9095 (class 1247 OID 896755)
-- Name: query; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE query AS (
	url character varying
);


--
-- TOC entry 12676 (class 0 OID 0)
-- Dependencies: 9095
-- Name: TYPE query; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE query IS 'Alias: "Query", wfs:Query nach Web Feature Service Specifikation, Version 1.0.0: url 0..1';


--
-- TOC entry 12677 (class 0 OID 0)
-- Dependencies: 9095
-- Name: COLUMN query.url; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN query.url IS 'url CharacterString CharacterString 0..1';


--
-- TOC entry 8852 (class 1247 OID 895499)
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
-- TOC entry 8922 (class 1247 OID 895900)
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
-- TOC entry 8908 (class 1247 OID 895842)
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
-- TOC entry 8971 (class 1247 OID 896159)
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
-- TOC entry 8656 (class 1247 OID 894399)
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
-- TOC entry 8649 (class 1247 OID 894362)
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
-- TOC entry 8691 (class 1247 OID 894585)
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
-- TOC entry 8761 (class 1247 OID 895055)
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
-- TOC entry 9013 (class 1247 OID 896468)
-- Name: rp_besondereraumkategorietypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besondereraumkategorietypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8845 (class 1247 OID 895444)
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
-- TOC entry 8915 (class 1247 OID 895873)
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
-- TOC entry 8810 (class 1247 OID 895332)
-- Name: rp_besonderetourismuserholungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_besonderetourismuserholungtypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8796 (class 1247 OID 895276)
-- Name: rp_bodenschatztiefen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bodenschatztiefen AS ENUM (
    '1000',
    '2000'
);


--
-- TOC entry 8775 (class 1247 OID 895112)
-- Name: rp_bodenschutztypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_bodenschutztypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- TOC entry 8985 (class 1247 OID 896265)
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
-- TOC entry 8887 (class 1247 OID 895696)
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
-- TOC entry 8712 (class 1247 OID 894683)
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
    '9999'
);


--
-- TOC entry 8705 (class 1247 OID 894658)
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
-- TOC entry 8803 (class 1247 OID 895292)
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
-- TOC entry 8992 (class 1247 OID 896304)
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
-- TOC entry 8663 (class 1247 OID 894435)
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
-- TOC entry 8782 (class 1247 OID 895134)
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
-- TOC entry 9027 (class 1247 OID 896526)
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
-- TOC entry 8943 (class 1247 OID 896019)
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
-- TOC entry 8747 (class 1247 OID 895013)
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
-- TOC entry 8936 (class 1247 OID 895989)
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
-- TOC entry 8740 (class 1247 OID 894977)
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
    '9999'
);


--
-- TOC entry 8754 (class 1247 OID 895037)
-- Name: rp_lufttypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_lufttypen AS ENUM (
    '1000',
    '2000',
    '9999'
);


--
-- TOC entry 8929 (class 1247 OID 895928)
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
-- TOC entry 8789 (class 1247 OID 895195)
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
-- TOC entry 8894 (class 1247 OID 895751)
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
-- TOC entry 8733 (class 1247 OID 894946)
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
-- TOC entry 9006 (class 1247 OID 896374)
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
-- TOC entry 8684 (class 1247 OID 894549)
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
-- TOC entry 8670 (class 1247 OID 894483)
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
-- TOC entry 8719 (class 1247 OID 894716)
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
    '9999'
);


--
-- TOC entry 8957 (class 1247 OID 896076)
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
-- TOC entry 8901 (class 1247 OID 895796)
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
-- TOC entry 8873 (class 1247 OID 895610)
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
-- TOC entry 8866 (class 1247 OID 895589)
-- Name: rp_spannungtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_spannungtypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '4000'
);


--
-- TOC entry 8999 (class 1247 OID 896341)
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
-- TOC entry 9041 (class 1247 OID 896638)
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
-- TOC entry 8698 (class 1247 OID 894624)
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
-- TOC entry 8880 (class 1247 OID 895644)
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
-- TOC entry 8824 (class 1247 OID 895369)
-- Name: rp_tourismustypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_tourismustypen AS ENUM (
    '1000',
    '2000',
    '9999'
);


--
-- TOC entry 8677 (class 1247 OID 894525)
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
-- TOC entry 8838 (class 1247 OID 895405)
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
-- TOC entry 8964 (class 1247 OID 896134)
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
-- TOC entry 8726 (class 1247 OID 894906)
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
-- TOC entry 8817 (class 1247 OID 895351)
-- Name: rp_wasserschutzzonen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_wasserschutzzonen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8859 (class 1247 OID 895541)
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
-- TOC entry 8950 (class 1247 OID 896043)
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
-- TOC entry 9020 (class 1247 OID 896487)
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
-- TOC entry 8831 (class 1247 OID 895387)
-- Name: rp_zaesurtypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zaesurtypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8768 (class 1247 OID 895097)
-- Name: rp_zeitstufen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE rp_zeitstufen AS ENUM (
    '1000',
    '2000'
);


--
-- TOC entry 8978 (class 1247 OID 896198)
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
-- TOC entry 9034 (class 1247 OID 896581)
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
-- TOC entry 9092 (class 1247 OID 896752)
-- Name: sc_crs; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE sc_crs AS (
	scope character varying[]
);


--
-- TOC entry 12678 (class 0 OID 0)
-- Dependencies: 9092
-- Name: TYPE sc_crs; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE sc_crs IS 'Alias: "SC_CRS", ISO 19136 GML Type: scope 1..*';


--
-- TOC entry 12679 (class 0 OID 0)
-- Dependencies: 9092
-- Name: COLUMN sc_crs.scope; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN sc_crs.scope IS 'scope CharacterString CharacterString 1..*';


--
-- TOC entry 9098 (class 1247 OID 896758)
-- Name: transaction; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE transaction AS (
	content text
);


--
-- TOC entry 12680 (class 0 OID 0)
-- Dependencies: 9098
-- Name: TYPE transaction; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE transaction IS 'Alias: "Transaction", wfs:Transaction nach Web Feature Service Specifikation, Version 1.0.0: content 0..1';


--
-- TOC entry 12681 (class 0 OID 0)
-- Dependencies: 9098
-- Name: COLUMN transaction.content; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN transaction.content IS 'content CharacterString Text 0..1';


--
-- TOC entry 8559 (class 1247 OID 893856)
-- Name: xp_abemassnahmentypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_abemassnahmentypen AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8601 (class 1247 OID 894090)
-- Name: xp_abweichungbaunvotypen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_abweichungbaunvotypen AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- TOC entry 8608 (class 1247 OID 894112)
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
-- TOC entry 8517 (class 1247 OID 893486)
-- Name: xp_anpflanzungbindungerhaltungsgegenstand; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_anpflanzungbindungerhaltungsgegenstand AS ENUM (
    '1000',
    '1100',
    '1200',
    '2000',
    '2100',
    '2200',
    '3000',
    '4000',
    '5000',
    '6000'
);


--
-- TOC entry 8454 (class 1247 OID 893129)
-- Name: xp_arthoehenbezug; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_arthoehenbezug AS ENUM (
    '1000',
    '2000',
    '2500',
    '3000'
);


--
-- TOC entry 8433 (class 1247 OID 893020)
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
-- TOC entry 8468 (class 1247 OID 893169)
-- Name: xp_bedeutungenbereich; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_bedeutungenbereich AS ENUM (
    '1000',
    '1500',
    '1600',
    '1650',
    '1700',
    '1800',
    '2000',
    '2500',
    '3000',
    '3500',
    '4000',
    '9999'
);


--
-- TOC entry 8636 (class 1247 OID 894295)
-- Name: xp_besondereartderbaulnutzung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_besondereartderbaulnutzung AS ENUM (
    '1000',
    '1100',
    '1200',
    '1300',
    '1400',
    '1500',
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
-- TOC entry 8510 (class 1247 OID 893381)
-- Name: xp_besonderezweckbestgemeinbedarf; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_besonderezweckbestgemeinbedarf AS ENUM (
    '10000',
    '10001',
    '10002',
    '10003',
    '12000',
    '12001',
    '12002',
    '12003',
    '12004',
    '14000',
    '14001',
    '14002',
    '14003',
    '16000',
    '16001',
    '16002',
    '16003',
    '16004',
    '18000',
    '18001',
    '20000',
    '20001',
    '20002',
    '22000',
    '22001',
    '22002',
    '24000',
    '24001',
    '24002',
    '24003',
    '26000',
    '26001'
);


--
-- TOC entry 8622 (class 1247 OID 894158)
-- Name: xp_besonderezweckbestimmunggruen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_besonderezweckbestimmunggruen AS ENUM (
    '10000',
    '10001',
    '10002',
    '10003',
    '12000',
    '14000',
    '14001',
    '14002',
    '14003',
    '14004',
    '14005',
    '14006',
    '14007',
    '16000',
    '16001',
    '18000',
    '22000',
    '22001',
    '24000',
    '24001',
    '24002',
    '24003',
    '24004',
    '24005',
    '24006',
    '99990'
);


--
-- TOC entry 8531 (class 1247 OID 893566)
-- Name: xp_besonderezweckbestimmungverentsorgung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_besonderezweckbestimmungverentsorgung AS ENUM (
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
    '10010',
    '12000',
    '12001',
    '12002',
    '12003',
    '12004',
    '12005',
    '13000',
    '13001',
    '13002',
    '13003',
    '14000',
    '14001',
    '14002',
    '16000',
    '16001',
    '16002',
    '16003',
    '16004',
    '16005',
    '18000',
    '18001',
    '18002',
    '18003',
    '18004',
    '18005',
    '18006',
    '20000',
    '20001',
    '22000',
    '22001',
    '22002',
    '22003',
    '24000',
    '24001',
    '24002',
    '24003',
    '24004',
    '24005',
    '26000',
    '26001',
    '26002',
    '28000',
    '28001',
    '28002',
    '28003',
    '28004',
    '99990'
);


--
-- TOC entry 8545 (class 1247 OID 893769)
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
-- TOC entry 9125 (class 1247 OID 896785)
-- Name: xp_datumattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_datumattribut AS (
	wert date
);


--
-- TOC entry 12682 (class 0 OID 0)
-- Dependencies: 9125
-- Name: TYPE xp_datumattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_datumattribut IS 'Alias: "XP_DatumAttribut",  1';


--
-- TOC entry 12683 (class 0 OID 0)
-- Dependencies: 9125
-- Name: COLUMN xp_datumattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_datumattribut.wert IS 'wert  Date 1';


--
-- TOC entry 9122 (class 1247 OID 896782)
-- Name: xp_doubleattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_doubleattribut AS (
	wert double precision
);


--
-- TOC entry 12684 (class 0 OID 0)
-- Dependencies: 9122
-- Name: TYPE xp_doubleattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_doubleattribut IS 'Alias: "XP_DoubleAttribut",  1';


--
-- TOC entry 12685 (class 0 OID 0)
-- Dependencies: 9122
-- Name: COLUMN xp_doubleattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_doubleattribut.wert IS 'wert  Decimal 1';


SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 926 (class 1259 OID 896674)
-- Name: xp_externereferenzart; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_externereferenzart (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12686 (class 0 OID 0)
-- Dependencies: 926
-- Name: TABLE xp_externereferenzart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_externereferenzart IS 'Alias: "XP_ExterneReferenzArt", UML-Typ: Code Liste';


--
-- TOC entry 12687 (class 0 OID 0)
-- Dependencies: 926
-- Name: COLUMN xp_externereferenzart.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenzart.codespace IS 'codeSpace  text ';


--
-- TOC entry 12688 (class 0 OID 0)
-- Dependencies: 926
-- Name: COLUMN xp_externereferenzart.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenzart.id IS 'id  character varying ';


--
-- TOC entry 928 (class 1259 OID 896690)
-- Name: xp_mimetypes; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_mimetypes (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12689 (class 0 OID 0)
-- Dependencies: 928
-- Name: TABLE xp_mimetypes; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_mimetypes IS 'Alias: "XP_MimeTypes", UML-Typ: Code Liste';


--
-- TOC entry 12690 (class 0 OID 0)
-- Dependencies: 928
-- Name: COLUMN xp_mimetypes.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_mimetypes.codespace IS 'codeSpace  text ';


--
-- TOC entry 12691 (class 0 OID 0)
-- Dependencies: 928
-- Name: COLUMN xp_mimetypes.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_mimetypes.id IS 'id  character varying ';


--
-- TOC entry 9143 (class 1247 OID 896803)
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
-- TOC entry 12692 (class 0 OID 0)
-- Dependencies: 9143
-- Name: TYPE xp_externereferenz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_externereferenz IS 'Alias: "XP_ExterneReferenz",  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1], UML-Classifier: XP_ExterneReferenzArt Stereotyp: CodeList [0..1],  [0..1],  [0..1],  [0..1], UML-Classifier: XP_MimeTypes Stereotyp: CodeList [0..1],  [0..1],  [0..1]';


--
-- TOC entry 12693 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.georefurl; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.georefurl IS 'georefURL  URI 0..1';


--
-- TOC entry 12694 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.georefmimetype; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.georefmimetype IS 'georefMimeType CodeList XP_MimeTypes 0..1';


--
-- TOC entry 12695 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.art; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.art IS 'art CodeList XP_ExterneReferenzArt 0..1';


--
-- TOC entry 12696 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.informationssystemurl; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.informationssystemurl IS 'informationssystemURL  URI 0..1';


--
-- TOC entry 12697 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.referenzname; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.referenzname IS 'referenzName  CharacterString 0..1';


--
-- TOC entry 12698 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.referenzurl; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.referenzurl IS 'referenzURL  URI 0..1';


--
-- TOC entry 12699 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.referenzmimetype; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.referenzmimetype IS 'referenzMimeType CodeList XP_MimeTypes 0..1';


--
-- TOC entry 12700 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.beschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.beschreibung IS 'beschreibung  CharacterString 0..1';


--
-- TOC entry 12701 (class 0 OID 0)
-- Dependencies: 9143
-- Name: COLUMN xp_externereferenz.datum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_externereferenz.datum IS 'datum  Date 0..1';


--
-- TOC entry 9089 (class 1247 OID 896749)
-- Name: xp_flaechengeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_flaechengeometrie AS (
	flaeche public.geometry(Polygon),
	multiflaeche public.geometry(MultiPolygon)
);


--
-- TOC entry 12702 (class 0 OID 0)
-- Dependencies: 9089
-- Name: TYPE xp_flaechengeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_flaechengeometrie IS 'Alias: "XP_Flaechengeometrie", UML-DataType: GM_Surface 1, UML-DataType: GM_MultiSurface 1';


--
-- TOC entry 12703 (class 0 OID 0)
-- Dependencies: 9089
-- Name: COLUMN xp_flaechengeometrie.flaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_flaechengeometrie.flaeche IS 'Flaeche  GM_Surface 1';


--
-- TOC entry 12704 (class 0 OID 0)
-- Dependencies: 9089
-- Name: COLUMN xp_flaechengeometrie.multiflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_flaechengeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';


--
-- TOC entry 9140 (class 1247 OID 896800)
-- Name: xp_gemeinde; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_gemeinde AS (
	ags character varying,
	rs character varying,
	gemeindename character varying,
	ortsteilname character varying
);


--
-- TOC entry 12705 (class 0 OID 0)
-- Dependencies: 9140
-- Name: TYPE xp_gemeinde; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_gemeinde IS 'Alias: "XP_Gemeinde",  [0..1],  [0..1],  [0..1],  [0..1]';


--
-- TOC entry 12706 (class 0 OID 0)
-- Dependencies: 9140
-- Name: COLUMN xp_gemeinde.ags; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.ags IS 'ags  CharacterString 0..1';


--
-- TOC entry 12707 (class 0 OID 0)
-- Dependencies: 9140
-- Name: COLUMN xp_gemeinde.rs; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.rs IS 'rs  CharacterString 0..1';


--
-- TOC entry 12708 (class 0 OID 0)
-- Dependencies: 9140
-- Name: COLUMN xp_gemeinde.gemeindename; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.gemeindename IS 'gemeindeName  CharacterString 0..1';


--
-- TOC entry 12709 (class 0 OID 0)
-- Dependencies: 9140
-- Name: COLUMN xp_gemeinde.ortsteilname; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gemeinde.ortsteilname IS 'ortsteilName  CharacterString 0..1';


--
-- TOC entry 9113 (class 1247 OID 896773)
-- Name: xp_generattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_generattribut AS (
	name character varying
);


--
-- TOC entry 12710 (class 0 OID 0)
-- Dependencies: 9113
-- Name: TYPE xp_generattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_generattribut IS 'Alias: "XP_GenerAttribut",  1';


--
-- TOC entry 12711 (class 0 OID 0)
-- Dependencies: 9113
-- Name: COLUMN xp_generattribut.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_generattribut.name IS 'name  CharacterString 1';


--
-- TOC entry 8482 (class 1247 OID 893242)
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
-- TOC entry 9134 (class 1247 OID 896794)
-- Name: xp_hoehenangabe; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_hoehenangabe AS (
	abweichenderhoehenbezug character varying,
	hoehenbezug xp_arthoehenbezug,
	bezugspunkt xp_arthoehenbezugspunkt,
	hmin double precision,
	hmax double precision,
	hzwingend double precision,
	h double precision
);


--
-- TOC entry 12712 (class 0 OID 0)
-- Dependencies: 9134
-- Name: TYPE xp_hoehenangabe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_hoehenangabe IS 'Alias: "XP_Hoehenangabe",  [0..1], UML-Classifier: XP_ArtHoehenbezug Stereotyp: enumeration [0..1], UML-Classifier: XP_ArtHoehenbezugspunkt Stereotyp: enumeration [0..1],  [0..1],  [0..1],  [0..1],  [0..1]';


--
-- TOC entry 12713 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.abweichenderhoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.abweichenderhoehenbezug IS 'abweichenderHoehenbezug  CharacterString 0..1';


--
-- TOC entry 12714 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.hoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hoehenbezug IS 'hoehenbezug enumeration XP_ArtHoehenbezug 0..1';


--
-- TOC entry 12715 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.bezugspunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.bezugspunkt IS 'bezugspunkt enumeration XP_ArtHoehenbezugspunkt 0..1';


--
-- TOC entry 12716 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.hmin; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hmin IS 'hMin  Length 0..1';


--
-- TOC entry 12717 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.hmax; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hmax IS 'hMax  Length 0..1';


--
-- TOC entry 12718 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.hzwingend; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.hzwingend IS 'hZwingend  Length 0..1';


--
-- TOC entry 12719 (class 0 OID 0)
-- Dependencies: 9134
-- Name: COLUMN xp_hoehenangabe.h; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_hoehenangabe.h IS 'h  Length 0..1';


--
-- TOC entry 8643 (class 1247 OID 894346)
-- Name: xp_horizontaleausrichtung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_horizontaleausrichtung AS ENUM (
    'linksbündig',
    'rechtsbündig',
    'zentrisch'
);


--
-- TOC entry 9119 (class 1247 OID 896779)
-- Name: xp_integerattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_integerattribut AS (
	wert integer
);


--
-- TOC entry 12720 (class 0 OID 0)
-- Dependencies: 9119
-- Name: TYPE xp_integerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_integerattribut IS 'Alias: "XP_IntegerAttribut",  1';


--
-- TOC entry 12721 (class 0 OID 0)
-- Dependencies: 9119
-- Name: COLUMN xp_integerattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_integerattribut.wert IS 'wert  Integer 1';


--
-- TOC entry 8580 (class 1247 OID 893981)
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
-- TOC entry 9080 (class 1247 OID 896740)
-- Name: xp_liniengeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_liniengeometrie AS (
	linie public.geometry(LineString),
	multilinie public.geometry(MultiLineString)
);


--
-- TOC entry 12722 (class 0 OID 0)
-- Dependencies: 9080
-- Name: TYPE xp_liniengeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_liniengeometrie IS 'Alias: "XP_Liniengeometrie", UML-DataType: GM_Curve 1, UML-DataType: GM_MultiCurve 1';


--
-- TOC entry 12723 (class 0 OID 0)
-- Dependencies: 9080
-- Name: COLUMN xp_liniengeometrie.linie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_liniengeometrie.linie IS 'Linie  GM_Curve 1';


--
-- TOC entry 12724 (class 0 OID 0)
-- Dependencies: 9080
-- Name: COLUMN xp_liniengeometrie.multilinie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_liniengeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';


--
-- TOC entry 8489 (class 1247 OID 893294)
-- Name: xp_nutzungsform; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_nutzungsform AS ENUM (
    '1000',
    '2000'
);


--
-- TOC entry 9131 (class 1247 OID 896791)
-- Name: xp_plangeber; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_plangeber AS (
	name character varying,
	kennziffer character varying
);


--
-- TOC entry 12725 (class 0 OID 0)
-- Dependencies: 9131
-- Name: TYPE xp_plangeber; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_plangeber IS 'Alias: "XP_Plangeber",  1,  [0..1]';


--
-- TOC entry 12726 (class 0 OID 0)
-- Dependencies: 9131
-- Name: COLUMN xp_plangeber.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plangeber.name IS 'name  CharacterString 1';


--
-- TOC entry 12727 (class 0 OID 0)
-- Dependencies: 9131
-- Name: COLUMN xp_plangeber.kennziffer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plangeber.kennziffer IS 'kennziffer  CharacterString 0..1';


--
-- TOC entry 9083 (class 1247 OID 896743)
-- Name: xp_punktgeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_punktgeometrie AS (
	punkt public.geometry(Point),
	multipunkt public.geometry(MultiPoint)
);


--
-- TOC entry 12728 (class 0 OID 0)
-- Dependencies: 9083
-- Name: TYPE xp_punktgeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_punktgeometrie IS 'Alias: "XP_Punktgeometrie", UML-DataType: GM_Point 1, UML-DataType: GM_MultiPoint 1';


--
-- TOC entry 12729 (class 0 OID 0)
-- Dependencies: 9083
-- Name: COLUMN xp_punktgeometrie.punkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_punktgeometrie.punkt IS 'Punkt  GM_Point 1';


--
-- TOC entry 12730 (class 0 OID 0)
-- Dependencies: 9083
-- Name: COLUMN xp_punktgeometrie.multipunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_punktgeometrie.multipunkt IS 'MultiPunkt  GM_MultiPoint 1';


--
-- TOC entry 8461 (class 1247 OID 893150)
-- Name: xp_rechtscharakterplanaenderung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_rechtscharakterplanaenderung AS ENUM (
    '1000',
    '1100',
    '2000'
);


--
-- TOC entry 8447 (class 1247 OID 893111)
-- Name: xp_rechtsstand; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_rechtsstand AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8566 (class 1247 OID 893875)
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
-- TOC entry 8440 (class 1247 OID 893057)
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
-- TOC entry 9107 (class 1247 OID 896767)
-- Name: xp_spemassnahmendaten; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_spemassnahmendaten AS (
	klassifizmassnahme xp_spemassnahmentypen,
	massnahmetext character varying,
	massnahmekuerzel character varying
);


--
-- TOC entry 12731 (class 0 OID 0)
-- Dependencies: 9107
-- Name: TYPE xp_spemassnahmendaten; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_spemassnahmendaten IS 'Alias: "XP_SPEMassnahmenDaten", UML-Classifier: XP_SPEMassnahmenTypen Stereotyp: enumeration [0..1],  [0..1],  [0..1]';


--
-- TOC entry 12732 (class 0 OID 0)
-- Dependencies: 9107
-- Name: COLUMN xp_spemassnahmendaten.klassifizmassnahme; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.klassifizmassnahme IS 'klassifizMassnahme enumeration XP_SPEMassnahmenTypen 0..1';


--
-- TOC entry 12733 (class 0 OID 0)
-- Dependencies: 9107
-- Name: COLUMN xp_spemassnahmendaten.massnahmetext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.massnahmetext IS 'massnahmeText  CharacterString 0..1';


--
-- TOC entry 12734 (class 0 OID 0)
-- Dependencies: 9107
-- Name: COLUMN xp_spemassnahmendaten.massnahmekuerzel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_spemassnahmendaten.massnahmekuerzel IS 'massnahmeKuerzel  CharacterString 0..1';


--
-- TOC entry 8573 (class 1247 OID 893956)
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
-- TOC entry 9128 (class 1247 OID 896788)
-- Name: xp_stringattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_stringattribut AS (
	wert character varying
);


--
-- TOC entry 12735 (class 0 OID 0)
-- Dependencies: 9128
-- Name: TYPE xp_stringattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_stringattribut IS 'Alias: "XP_StringAttribut",  1';


--
-- TOC entry 12736 (class 0 OID 0)
-- Dependencies: 9128
-- Name: COLUMN xp_stringattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stringattribut.wert IS 'wert  CharacterString 1';


--
-- TOC entry 9116 (class 1247 OID 896776)
-- Name: xp_urlattribut; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_urlattribut AS (
	wert character varying
);


--
-- TOC entry 12737 (class 0 OID 0)
-- Dependencies: 9116
-- Name: TYPE xp_urlattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_urlattribut IS 'Alias: "XP_URLAttribut",  1';


--
-- TOC entry 12738 (class 0 OID 0)
-- Dependencies: 9116
-- Name: COLUMN xp_urlattribut.wert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_urlattribut.wert IS 'wert  URI 1';


--
-- TOC entry 9086 (class 1247 OID 896746)
-- Name: xp_variablegeometrie; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_variablegeometrie AS (
	punkt public.geometry(Point),
	multipunkt public.geometry(MultiPoint),
	linie public.geometry(LineString),
	multilinie public.geometry(MultiLineString),
	flaeche public.geometry(Polygon),
	multiflaeche public.geometry(MultiPolygon)
);


--
-- TOC entry 12739 (class 0 OID 0)
-- Dependencies: 9086
-- Name: TYPE xp_variablegeometrie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_variablegeometrie IS 'Alias: "XP_VariableGeometrie", UML-DataType: GM_Point 1, UML-DataType: GM_MultiPoint 1, UML-DataType: GM_Curve 1, UML-DataType: GM_MultiCurve 1, UML-DataType: GM_Surface 1, UML-DataType: GM_MultiSurface 1';


--
-- TOC entry 12740 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.punkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.punkt IS 'Punkt  GM_Point 1';


--
-- TOC entry 12741 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.multipunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multipunkt IS 'MultiPunkt  GM_MultiPoint 1';


--
-- TOC entry 12742 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.linie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.linie IS 'Linie  GM_Curve 1';


--
-- TOC entry 12743 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.multilinie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multilinie IS 'MultiLinie  GM_MultiCurve 1';


--
-- TOC entry 12744 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.flaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.flaeche IS 'Flaeche  GM_Surface 1';


--
-- TOC entry 12745 (class 0 OID 0)
-- Dependencies: 9086
-- Name: COLUMN xp_variablegeometrie.multiflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_variablegeometrie.multiflaeche IS 'MultiFlaeche  GM_MultiSurface 1';


--
-- TOC entry 9137 (class 1247 OID 896797)
-- Name: xp_verbundenerplan; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verbundenerplan AS (
	planname character varying,
	rechtscharakter xp_rechtscharakterplanaenderung,
	nummer character varying
);


--
-- TOC entry 12746 (class 0 OID 0)
-- Dependencies: 9137
-- Name: TYPE xp_verbundenerplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_verbundenerplan IS 'Alias: "XP_VerbundenerPlan",  1, UML-Classifier: XP_RechtscharakterPlanaenderung Stereotyp: enumeration 1,  [0..1]';


--
-- TOC entry 12747 (class 0 OID 0)
-- Dependencies: 9137
-- Name: COLUMN xp_verbundenerplan.planname; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.planname IS 'planName  CharacterString 1';


--
-- TOC entry 12748 (class 0 OID 0)
-- Dependencies: 9137
-- Name: COLUMN xp_verbundenerplan.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.rechtscharakter IS 'rechtscharakter enumeration XP_RechtscharakterPlanaenderung 1';


--
-- TOC entry 12749 (class 0 OID 0)
-- Dependencies: 9137
-- Name: COLUMN xp_verbundenerplan.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verbundenerplan.nummer IS 'nummer  CharacterString 0..1';


--
-- TOC entry 9110 (class 1247 OID 896770)
-- Name: xp_verfahrensmerkmal; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verfahrensmerkmal AS (
	vermerk character varying,
	datum date,
	signatur character varying,
	signiert boolean
);


--
-- TOC entry 12750 (class 0 OID 0)
-- Dependencies: 9110
-- Name: TYPE xp_verfahrensmerkmal; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TYPE xp_verfahrensmerkmal IS 'Alias: "XP_VerfahrensMerkmal",  1,  1,  1,  1';


--
-- TOC entry 12751 (class 0 OID 0)
-- Dependencies: 9110
-- Name: COLUMN xp_verfahrensmerkmal.vermerk; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.vermerk IS 'vermerk  CharacterString 1';


--
-- TOC entry 12752 (class 0 OID 0)
-- Dependencies: 9110
-- Name: COLUMN xp_verfahrensmerkmal.datum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.datum IS 'datum  Date 1';


--
-- TOC entry 12753 (class 0 OID 0)
-- Dependencies: 9110
-- Name: COLUMN xp_verfahrensmerkmal.signatur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.signatur IS 'signatur  CharacterString 1';


--
-- TOC entry 12754 (class 0 OID 0)
-- Dependencies: 9110
-- Name: COLUMN xp_verfahrensmerkmal.signiert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_verfahrensmerkmal.signiert IS 'signiert  Boolean 1';


--
-- TOC entry 8538 (class 1247 OID 893750)
-- Name: xp_verlaengerungveraenderungssperre; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_verlaengerungveraenderungssperre AS ENUM (
    '1000',
    '2000',
    '3000'
);


--
-- TOC entry 8646 (class 1247 OID 894354)
-- Name: xp_vertikaleausrichtung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_vertikaleausrichtung AS ENUM (
    'Basis',
    'Mitte',
    'Oben'
);


--
-- TOC entry 8594 (class 1247 OID 894050)
-- Name: xp_zweckbestimmunggemeinbedarf; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggemeinbedarf AS ENUM (
    '1000',
    '1200',
    '1400',
    '1600',
    '1800',
    '2000',
    '2200',
    '2400',
    '2600',
    '9999'
);


--
-- TOC entry 8587 (class 1247 OID 894029)
-- Name: xp_zweckbestimmunggewaesser; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggewaesser AS ENUM (
    '1000',
    '1100',
    '1200',
    '9999'
);


--
-- TOC entry 8524 (class 1247 OID 893526)
-- Name: xp_zweckbestimmunggruen; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmunggruen AS ENUM (
    '1000',
    '1200',
    '1400',
    '1600',
    '1800',
    '2000',
    '2200',
    '2400',
    '2600',
    '9999'
);


--
-- TOC entry 8496 (class 1247 OID 893310)
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
    '9999'
);


--
-- TOC entry 8503 (class 1247 OID 893344)
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
-- TOC entry 8615 (class 1247 OID 894137)
-- Name: xp_zweckbestimmungspielsportanlage; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungspielsportanlage AS ENUM (
    '1000',
    '2000',
    '3000',
    '9999'
);


--
-- TOC entry 8629 (class 1247 OID 894246)
-- Name: xp_zweckbestimmungverentsorgung; Type: TYPE; Schema: xplan_gml; Owner: -
--

CREATE TYPE xp_zweckbestimmungverentsorgung AS ENUM (
    '1000',
    '1200',
    '1300',
    '1400',
    '1600',
    '1800',
    '2000',
    '2200',
    '2400',
    '2600',
    '2800',
    '3000',
    '9999'
);


--
-- TOC entry 8552 (class 1247 OID 893829)
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
-- TOC entry 8475 (class 1247 OID 893214)
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
-- TOC entry 898 (class 1259 OID 895521)
-- Name: enum_rp_abfallentsorgungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abfallentsorgungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12755 (class 0 OID 0)
-- Dependencies: 898
-- Name: TABLE enum_rp_abfallentsorgungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abfallentsorgungtypen IS 'Alias: "enum_RP_AbfallentsorgungTypen"';


--
-- TOC entry 908 (class 1259 OID 895913)
-- Name: enum_rp_abfalltypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abfalltypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12756 (class 0 OID 0)
-- Dependencies: 908
-- Name: TABLE enum_rp_abfalltypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abfalltypen IS 'Alias: "enum_RP_AbfallTypen"';


--
-- TOC entry 906 (class 1259 OID 895857)
-- Name: enum_rp_abwassertypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_abwassertypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12757 (class 0 OID 0)
-- Dependencies: 906
-- Name: TABLE enum_rp_abwassertypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_abwassertypen IS 'Alias: "enum_RP_AbwasserTypen"';


--
-- TOC entry 915 (class 1259 OID 896179)
-- Name: enum_rp_achsentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_achsentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12758 (class 0 OID 0)
-- Dependencies: 915
-- Name: TABLE enum_rp_achsentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_achsentypen IS 'Alias: "enum_RP_AchsenTypen"';


--
-- TOC entry 870 (class 1259 OID 894417)
-- Name: enum_rp_art; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_art (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12759 (class 0 OID 0)
-- Dependencies: 870
-- Name: TABLE enum_rp_art; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_art IS 'Alias: "enum_RP_Art"';


--
-- TOC entry 869 (class 1259 OID 894381)
-- Name: enum_rp_bedeutsamkeit; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bedeutsamkeit (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12760 (class 0 OID 0)
-- Dependencies: 869
-- Name: TABLE enum_rp_bedeutsamkeit; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bedeutsamkeit IS 'Alias: "enum_RP_Bedeutsamkeit"';


--
-- TOC entry 875 (class 1259 OID 894605)
-- Name: enum_rp_bergbaufolgenutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bergbaufolgenutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12761 (class 0 OID 0)
-- Dependencies: 875
-- Name: TABLE enum_rp_bergbaufolgenutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bergbaufolgenutzung IS 'Alias: "enum_RP_BergbauFolgenutzung"';


--
-- TOC entry 885 (class 1259 OID 895077)
-- Name: enum_rp_bergbauplanungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bergbauplanungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12762 (class 0 OID 0)
-- Dependencies: 885
-- Name: TABLE enum_rp_bergbauplanungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bergbauplanungtypen IS 'Alias: "enum_RP_BergbauplanungTypen"';


--
-- TOC entry 921 (class 1259 OID 896475)
-- Name: enum_rp_besondereraumkategorietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondereraumkategorietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12763 (class 0 OID 0)
-- Dependencies: 921
-- Name: TABLE enum_rp_besondereraumkategorietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondereraumkategorietypen IS 'Alias: "enum_RP_BesondereRaumkategorieTypen"';


--
-- TOC entry 897 (class 1259 OID 895475)
-- Name: enum_rp_besondererschienenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondererschienenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12764 (class 0 OID 0)
-- Dependencies: 897
-- Name: TABLE enum_rp_besondererschienenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondererschienenverkehrtypen IS 'Alias: "enum_RP_BesondererSchienenverkehrTypen"';


--
-- TOC entry 907 (class 1259 OID 895885)
-- Name: enum_rp_besondererstrassenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besondererstrassenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12765 (class 0 OID 0)
-- Dependencies: 907
-- Name: TABLE enum_rp_besondererstrassenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besondererstrassenverkehrtypen IS 'Alias: "enum_RP_BesondererStrassenverkehrTypen"';


--
-- TOC entry 892 (class 1259 OID 895339)
-- Name: enum_rp_besonderetourismuserholungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_besonderetourismuserholungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12766 (class 0 OID 0)
-- Dependencies: 892
-- Name: TABLE enum_rp_besonderetourismuserholungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_besonderetourismuserholungtypen IS 'Alias: "enum_RP_BesondereTourismusErholungTypen"';


--
-- TOC entry 890 (class 1259 OID 895281)
-- Name: enum_rp_bodenschatztiefen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bodenschatztiefen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12767 (class 0 OID 0)
-- Dependencies: 890
-- Name: TABLE enum_rp_bodenschatztiefen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bodenschatztiefen IS 'Alias: "enum_RP_BodenschatzTiefen"';


--
-- TOC entry 887 (class 1259 OID 895121)
-- Name: enum_rp_bodenschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_bodenschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12768 (class 0 OID 0)
-- Dependencies: 887
-- Name: TABLE enum_rp_bodenschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_bodenschutztypen IS 'Alias: "enum_RP_BodenschutzTypen"';


--
-- TOC entry 917 (class 1259 OID 896285)
-- Name: enum_rp_einzelhandeltypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_einzelhandeltypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12769 (class 0 OID 0)
-- Dependencies: 917
-- Name: TABLE enum_rp_einzelhandeltypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_einzelhandeltypen IS 'Alias: "enum_RP_EinzelhandelTypen"';


--
-- TOC entry 903 (class 1259 OID 895727)
-- Name: enum_rp_energieversorgungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_energieversorgungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12770 (class 0 OID 0)
-- Dependencies: 903
-- Name: TABLE enum_rp_energieversorgungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_energieversorgungtypen IS 'Alias: "enum_RP_EnergieversorgungTypen"';


--
-- TOC entry 878 (class 1259 OID 894699)
-- Name: enum_rp_erholungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_erholungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12771 (class 0 OID 0)
-- Dependencies: 878
-- Name: TABLE enum_rp_erholungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_erholungtypen IS 'Alias: "enum_RP_ErholungTypen"';


--
-- TOC entry 877 (class 1259 OID 894669)
-- Name: enum_rp_erneuerbareenergietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_erneuerbareenergietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12772 (class 0 OID 0)
-- Dependencies: 877
-- Name: TABLE enum_rp_erneuerbareenergietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_erneuerbareenergietypen IS 'Alias: "enum_RP_ErneuerbareEnergieTypen"';


--
-- TOC entry 891 (class 1259 OID 895313)
-- Name: enum_rp_forstwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_forstwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12773 (class 0 OID 0)
-- Dependencies: 891
-- Name: TABLE enum_rp_forstwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_forstwirtschafttypen IS 'Alias: "enum_RP_ForstwirtschaftTypen"';


--
-- TOC entry 918 (class 1259 OID 896323)
-- Name: enum_rp_funktionszuweisungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_funktionszuweisungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12774 (class 0 OID 0)
-- Dependencies: 918
-- Name: TABLE enum_rp_funktionszuweisungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_funktionszuweisungtypen IS 'Alias: "enum_RP_FunktionszuweisungTypen"';


--
-- TOC entry 871 (class 1259 OID 894461)
-- Name: enum_rp_gebietstyp; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_gebietstyp (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12775 (class 0 OID 0)
-- Dependencies: 871
-- Name: TABLE enum_rp_gebietstyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_gebietstyp IS 'Alias: "enum_RP_GebietsTyp"';


--
-- TOC entry 888 (class 1259 OID 895169)
-- Name: enum_rp_hochwasserschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_hochwasserschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12776 (class 0 OID 0)
-- Dependencies: 888
-- Name: TABLE enum_rp_hochwasserschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_hochwasserschutztypen IS 'Alias: "enum_RP_HochwasserschutzTypen"';


--
-- TOC entry 923 (class 1259 OID 896557)
-- Name: enum_rp_industriegewerbetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_industriegewerbetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12777 (class 0 OID 0)
-- Dependencies: 923
-- Name: TABLE enum_rp_industriegewerbetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_industriegewerbetypen IS 'Alias: "enum_RP_IndustrieGewerbeTypen"';


--
-- TOC entry 911 (class 1259 OID 896029)
-- Name: enum_rp_kommunikationtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_kommunikationtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12778 (class 0 OID 0)
-- Dependencies: 911
-- Name: TABLE enum_rp_kommunikationtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_kommunikationtypen IS 'Alias: "enum_RP_KommunikationTypen"';


--
-- TOC entry 883 (class 1259 OID 895023)
-- Name: enum_rp_kulturlandschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_kulturlandschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12779 (class 0 OID 0)
-- Dependencies: 883
-- Name: TABLE enum_rp_kulturlandschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_kulturlandschafttypen IS 'Alias: "enum_RP_KulturlandschaftTypen"';


--
-- TOC entry 910 (class 1259 OID 896003)
-- Name: enum_rp_laermschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_laermschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12780 (class 0 OID 0)
-- Dependencies: 910
-- Name: TABLE enum_rp_laermschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_laermschutztypen IS 'Alias: "enum_RP_LaermschutzTypen"';


--
-- TOC entry 882 (class 1259 OID 894995)
-- Name: enum_rp_landwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_landwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12781 (class 0 OID 0)
-- Dependencies: 882
-- Name: TABLE enum_rp_landwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_landwirtschafttypen IS 'Alias: "enum_RP_LandwirtschaftTypen"';


--
-- TOC entry 884 (class 1259 OID 895043)
-- Name: enum_rp_lufttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_lufttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12782 (class 0 OID 0)
-- Dependencies: 884
-- Name: TABLE enum_rp_lufttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_lufttypen IS 'Alias: "enum_RP_LuftTypen"';


--
-- TOC entry 909 (class 1259 OID 895963)
-- Name: enum_rp_luftverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_luftverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12783 (class 0 OID 0)
-- Dependencies: 909
-- Name: TABLE enum_rp_luftverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_luftverkehrtypen IS 'Alias: "enum_RP_LuftverkehrTypen"';


--
-- TOC entry 889 (class 1259 OID 895243)
-- Name: enum_rp_naturlandschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_naturlandschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12784 (class 0 OID 0)
-- Dependencies: 889
-- Name: TABLE enum_rp_naturlandschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_naturlandschafttypen IS 'Alias: "enum_RP_NaturLandschaftTypen"';


--
-- TOC entry 904 (class 1259 OID 895775)
-- Name: enum_rp_primaerenergietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_primaerenergietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12785 (class 0 OID 0)
-- Dependencies: 904
-- Name: TABLE enum_rp_primaerenergietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_primaerenergietypen IS 'Alias: "enum_RP_PrimaerenergieTypen"';


--
-- TOC entry 881 (class 1259 OID 894961)
-- Name: enum_rp_radwegwanderwegtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_radwegwanderwegtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12786 (class 0 OID 0)
-- Dependencies: 881
-- Name: TABLE enum_rp_radwegwanderwegtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_radwegwanderwegtypen IS 'Alias: "enum_RP_RadwegWanderwegTypen"';


--
-- TOC entry 920 (class 1259 OID 896431)
-- Name: enum_rp_raumkategorietypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_raumkategorietypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12787 (class 0 OID 0)
-- Dependencies: 920
-- Name: TABLE enum_rp_raumkategorietypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_raumkategorietypen IS 'Alias: "enum_RP_RaumkategorieTypen"';


--
-- TOC entry 874 (class 1259 OID 894567)
-- Name: enum_rp_rechtscharakter; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rechtscharakter (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12788 (class 0 OID 0)
-- Dependencies: 874
-- Name: TABLE enum_rp_rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rechtscharakter IS 'Alias: "enum_RP_Rechtscharakter"';


--
-- TOC entry 872 (class 1259 OID 894505)
-- Name: enum_rp_rechtsstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rechtsstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12789 (class 0 OID 0)
-- Dependencies: 872
-- Name: TABLE enum_rp_rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rechtsstand IS 'Alias: "enum_RP_Rechtsstand"';


--
-- TOC entry 879 (class 1259 OID 894837)
-- Name: enum_rp_rohstofftypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_rohstofftypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12790 (class 0 OID 0)
-- Dependencies: 879
-- Name: TABLE enum_rp_rohstofftypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_rohstofftypen IS 'Alias: "enum_RP_RohstoffTypen"';


--
-- TOC entry 913 (class 1259 OID 896109)
-- Name: enum_rp_schienenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_schienenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12791 (class 0 OID 0)
-- Dependencies: 913
-- Name: TABLE enum_rp_schienenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_schienenverkehrtypen IS 'Alias: "enum_RP_SchienenverkehrTypen"';


--
-- TOC entry 905 (class 1259 OID 895821)
-- Name: enum_rp_sonstverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sonstverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12792 (class 0 OID 0)
-- Dependencies: 905
-- Name: TABLE enum_rp_sonstverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sonstverkehrtypen IS 'Alias: "enum_RP_SonstVerkehrTypen"';


--
-- TOC entry 901 (class 1259 OID 895627)
-- Name: enum_rp_sozialeinfrastrukturtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sozialeinfrastrukturtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12793 (class 0 OID 0)
-- Dependencies: 901
-- Name: TABLE enum_rp_sozialeinfrastrukturtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sozialeinfrastrukturtypen IS 'Alias: "enum_RP_SozialeInfrastrukturTypen"';


--
-- TOC entry 900 (class 1259 OID 895597)
-- Name: enum_rp_spannungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_spannungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12794 (class 0 OID 0)
-- Dependencies: 900
-- Name: TABLE enum_rp_spannungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_spannungtypen IS 'Alias: "enum_RP_SpannungTypen"';


--
-- TOC entry 919 (class 1259 OID 896357)
-- Name: enum_rp_sperrgebiettypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sperrgebiettypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12795 (class 0 OID 0)
-- Dependencies: 919
-- Name: TABLE enum_rp_sperrgebiettypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sperrgebiettypen IS 'Alias: "enum_RP_SperrgebietTypen"';


--
-- TOC entry 925 (class 1259 OID 896657)
-- Name: enum_rp_spezifischegrenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_spezifischegrenzetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12796 (class 0 OID 0)
-- Dependencies: 925
-- Name: TABLE enum_rp_spezifischegrenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_spezifischegrenzetypen IS 'Alias: "enum_RP_SpezifischeGrenzeTypen"';


--
-- TOC entry 876 (class 1259 OID 894641)
-- Name: enum_rp_sportanlagetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_sportanlagetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12797 (class 0 OID 0)
-- Dependencies: 876
-- Name: TABLE enum_rp_sportanlagetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_sportanlagetypen IS 'Alias: "enum_RP_SportanlageTypen"';


--
-- TOC entry 902 (class 1259 OID 895673)
-- Name: enum_rp_strassenverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_strassenverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12798 (class 0 OID 0)
-- Dependencies: 902
-- Name: TABLE enum_rp_strassenverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_strassenverkehrtypen IS 'Alias: "enum_RP_StrassenverkehrTypen"';


--
-- TOC entry 894 (class 1259 OID 895375)
-- Name: enum_rp_tourismustypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_tourismustypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12799 (class 0 OID 0)
-- Dependencies: 894
-- Name: TABLE enum_rp_tourismustypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_tourismustypen IS 'Alias: "enum_RP_TourismusTypen"';


--
-- TOC entry 873 (class 1259 OID 894535)
-- Name: enum_rp_verfahren; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verfahren (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12800 (class 0 OID 0)
-- Dependencies: 873
-- Name: TABLE enum_rp_verfahren; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verfahren IS 'Alias: "enum_RP_Verfahren"';


--
-- TOC entry 896 (class 1259 OID 895425)
-- Name: enum_rp_verkehrstatus; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verkehrstatus (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12801 (class 0 OID 0)
-- Dependencies: 896
-- Name: TABLE enum_rp_verkehrstatus; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verkehrstatus IS 'Alias: "enum_RP_VerkehrStatus"';


--
-- TOC entry 914 (class 1259 OID 896145)
-- Name: enum_rp_verkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_verkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12802 (class 0 OID 0)
-- Dependencies: 914
-- Name: TABLE enum_rp_verkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_verkehrtypen IS 'Alias: "enum_RP_VerkehrTypen"';


--
-- TOC entry 880 (class 1259 OID 894927)
-- Name: enum_rp_wasserschutztypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserschutztypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12803 (class 0 OID 0)
-- Dependencies: 880
-- Name: TABLE enum_rp_wasserschutztypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserschutztypen IS 'Alias: "enum_RP_WasserschutzTypen"';


--
-- TOC entry 893 (class 1259 OID 895357)
-- Name: enum_rp_wasserschutzzonen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserschutzzonen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12804 (class 0 OID 0)
-- Dependencies: 893
-- Name: TABLE enum_rp_wasserschutzzonen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserschutzzonen IS 'Alias: "enum_RP_WasserschutzZonen"';


--
-- TOC entry 899 (class 1259 OID 895567)
-- Name: enum_rp_wasserverkehrtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserverkehrtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12805 (class 0 OID 0)
-- Dependencies: 899
-- Name: TABLE enum_rp_wasserverkehrtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserverkehrtypen IS 'Alias: "enum_RP_WasserverkehrTypen"';


--
-- TOC entry 912 (class 1259 OID 896059)
-- Name: enum_rp_wasserwirtschafttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wasserwirtschafttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12806 (class 0 OID 0)
-- Dependencies: 912
-- Name: TABLE enum_rp_wasserwirtschafttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wasserwirtschafttypen IS 'Alias: "enum_RP_WasserwirtschaftTypen"';


--
-- TOC entry 922 (class 1259 OID 896507)
-- Name: enum_rp_wohnensiedlungtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_wohnensiedlungtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12807 (class 0 OID 0)
-- Dependencies: 922
-- Name: TABLE enum_rp_wohnensiedlungtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_wohnensiedlungtypen IS 'Alias: "enum_RP_WohnenSiedlungTypen"';


--
-- TOC entry 895 (class 1259 OID 895393)
-- Name: enum_rp_zaesurtypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zaesurtypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12808 (class 0 OID 0)
-- Dependencies: 895
-- Name: TABLE enum_rp_zaesurtypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zaesurtypen IS 'Alias: "enum_RP_ZaesurTypen"';


--
-- TOC entry 886 (class 1259 OID 895101)
-- Name: enum_rp_zeitstufen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zeitstufen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12809 (class 0 OID 0)
-- Dependencies: 886
-- Name: TABLE enum_rp_zeitstufen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zeitstufen IS 'Alias: "enum_RP_Zeitstufen"';


--
-- TOC entry 916 (class 1259 OID 896237)
-- Name: enum_rp_zentralerortsonstigetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zentralerortsonstigetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12810 (class 0 OID 0)
-- Dependencies: 916
-- Name: TABLE enum_rp_zentralerortsonstigetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zentralerortsonstigetypen IS 'Alias: "enum_RP_ZentralerOrtSonstigeTypen"';


--
-- TOC entry 924 (class 1259 OID 896613)
-- Name: enum_rp_zentralerorttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_rp_zentralerorttypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12811 (class 0 OID 0)
-- Dependencies: 924
-- Name: TABLE enum_rp_zentralerorttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_rp_zentralerorttypen IS 'Alias: "enum_RP_ZentralerOrtTypen"';


--
-- TOC entry 857 (class 1259 OID 893863)
-- Name: enum_xp_abemassnahmentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_abemassnahmentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12812 (class 0 OID 0)
-- Dependencies: 857
-- Name: TABLE enum_xp_abemassnahmentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_abemassnahmentypen IS 'Alias: "enum_XP_ABEMassnahmenTypen"';


--
-- TOC entry 863 (class 1259 OID 894099)
-- Name: enum_xp_abweichungbaunvotypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_abweichungbaunvotypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12813 (class 0 OID 0)
-- Dependencies: 863
-- Name: TABLE enum_xp_abweichungbaunvotypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_abweichungbaunvotypen IS 'Alias: "enum_XP_AbweichungBauNVOTypen"';


--
-- TOC entry 864 (class 1259 OID 894123)
-- Name: enum_xp_allgartderbaulnutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_allgartderbaulnutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12814 (class 0 OID 0)
-- Dependencies: 864
-- Name: TABLE enum_xp_allgartderbaulnutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_allgartderbaulnutzung IS 'Alias: "enum_XP_AllgArtDerBaulNutzung"';


--
-- TOC entry 851 (class 1259 OID 893507)
-- Name: enum_xp_anpflanzungbindungerhaltungsgegenstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12815 (class 0 OID 0)
-- Dependencies: 851
-- Name: TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_anpflanzungbindungerhaltungsgegenstand IS 'Alias: "enum_XP_AnpflanzungBindungErhaltungsGegenstand"';


--
-- TOC entry 842 (class 1259 OID 893137)
-- Name: enum_xp_arthoehenbezug; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_arthoehenbezug (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12816 (class 0 OID 0)
-- Dependencies: 842
-- Name: TABLE enum_xp_arthoehenbezug; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_arthoehenbezug IS 'Alias: "enum_XP_ArtHoehenbezug"';


--
-- TOC entry 839 (class 1259 OID 893039)
-- Name: enum_xp_arthoehenbezugspunkt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_arthoehenbezugspunkt (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12817 (class 0 OID 0)
-- Dependencies: 839
-- Name: TABLE enum_xp_arthoehenbezugspunkt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_arthoehenbezugspunkt IS 'Alias: "enum_XP_ArtHoehenbezugspunkt"';


--
-- TOC entry 844 (class 1259 OID 893193)
-- Name: enum_xp_bedeutungenbereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_bedeutungenbereich (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12818 (class 0 OID 0)
-- Dependencies: 844
-- Name: TABLE enum_xp_bedeutungenbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_bedeutungenbereich IS 'Alias: "enum_XP_BedeutungenBereich"';


--
-- TOC entry 868 (class 1259 OID 894323)
-- Name: enum_xp_besondereartderbaulnutzung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_besondereartderbaulnutzung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12819 (class 0 OID 0)
-- Dependencies: 868
-- Name: TABLE enum_xp_besondereartderbaulnutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_besondereartderbaulnutzung IS 'Alias: "enum_XP_BesondereArtDerBaulNutzung"';


--
-- TOC entry 850 (class 1259 OID 893445)
-- Name: enum_xp_besonderezweckbestgemeinbedarf; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_besonderezweckbestgemeinbedarf (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12820 (class 0 OID 0)
-- Dependencies: 850
-- Name: TABLE enum_xp_besonderezweckbestgemeinbedarf; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_besonderezweckbestgemeinbedarf IS 'Alias: "enum_XP_BesondereZweckbestGemeinbedarf"';


--
-- TOC entry 866 (class 1259 OID 894211)
-- Name: enum_xp_besonderezweckbestimmunggruen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_besonderezweckbestimmunggruen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12821 (class 0 OID 0)
-- Dependencies: 866
-- Name: TABLE enum_xp_besonderezweckbestimmunggruen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_besonderezweckbestimmunggruen IS 'Alias: "enum_XP_BesondereZweckbestimmungGruen"';


--
-- TOC entry 853 (class 1259 OID 893683)
-- Name: enum_xp_besonderezweckbestimmungverentsorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_besonderezweckbestimmungverentsorgung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12822 (class 0 OID 0)
-- Dependencies: 853
-- Name: TABLE enum_xp_besonderezweckbestimmungverentsorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_besonderezweckbestimmungverentsorgung IS 'Alias: "enum_XP_BesondereZweckbestimmungVerEntsorgung"';


--
-- TOC entry 855 (class 1259 OID 893803)
-- Name: enum_xp_bundeslaender; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_bundeslaender (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12823 (class 0 OID 0)
-- Dependencies: 855
-- Name: TABLE enum_xp_bundeslaender; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_bundeslaender IS 'Alias: "enum_XP_Bundeslaender"';


--
-- TOC entry 846 (class 1259 OID 893271)
-- Name: enum_xp_grenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_grenzetypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12824 (class 0 OID 0)
-- Dependencies: 846
-- Name: TABLE enum_xp_grenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_grenzetypen IS 'Alias: "enum_XP_GrenzeTypen"';


--
-- TOC entry 860 (class 1259 OID 894007)
-- Name: enum_xp_klassifizschutzgebietnaturschutzrecht; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_klassifizschutzgebietnaturschutzrecht (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12825 (class 0 OID 0)
-- Dependencies: 860
-- Name: TABLE enum_xp_klassifizschutzgebietnaturschutzrecht; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_klassifizschutzgebietnaturschutzrecht IS 'Alias: "enum_XP_KlassifizSchutzgebietNaturschutzrecht"';


--
-- TOC entry 847 (class 1259 OID 893299)
-- Name: enum_xp_nutzungsform; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_nutzungsform (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12826 (class 0 OID 0)
-- Dependencies: 847
-- Name: TABLE enum_xp_nutzungsform; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_nutzungsform IS 'Alias: "enum_XP_Nutzungsform"';


--
-- TOC entry 843 (class 1259 OID 893157)
-- Name: enum_xp_rechtscharakterplanaenderung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_rechtscharakterplanaenderung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12827 (class 0 OID 0)
-- Dependencies: 843
-- Name: TABLE enum_xp_rechtscharakterplanaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_rechtscharakterplanaenderung IS 'Alias: "enum_XP_RechtscharakterPlanaenderung"';


--
-- TOC entry 841 (class 1259 OID 893117)
-- Name: enum_xp_rechtsstand; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_rechtsstand (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12828 (class 0 OID 0)
-- Dependencies: 841
-- Name: TABLE enum_xp_rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_rechtsstand IS 'Alias: "enum_XP_Rechtsstand"';


--
-- TOC entry 858 (class 1259 OID 893923)
-- Name: enum_xp_sondernutzungen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_sondernutzungen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12829 (class 0 OID 0)
-- Dependencies: 858
-- Name: TABLE enum_xp_sondernutzungen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_sondernutzungen IS 'Alias: "enum_XP_Sondernutzungen"';


--
-- TOC entry 840 (class 1259 OID 893087)
-- Name: enum_xp_spemassnahmentypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_spemassnahmentypen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12830 (class 0 OID 0)
-- Dependencies: 840
-- Name: TABLE enum_xp_spemassnahmentypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_spemassnahmentypen IS 'Alias: "enum_XP_SPEMassnahmenTypen"';


--
-- TOC entry 859 (class 1259 OID 893967)
-- Name: enum_xp_speziele; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_speziele (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12831 (class 0 OID 0)
-- Dependencies: 859
-- Name: TABLE enum_xp_speziele; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_speziele IS 'Alias: "enum_XP_SPEZiele"';


--
-- TOC entry 854 (class 1259 OID 893757)
-- Name: enum_xp_verlaengerungveraenderungssperre; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_verlaengerungveraenderungssperre (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12832 (class 0 OID 0)
-- Dependencies: 854
-- Name: TABLE enum_xp_verlaengerungveraenderungssperre; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_verlaengerungveraenderungssperre IS 'Alias: "enum_XP_VerlaengerungVeraenderungssperre"';


--
-- TOC entry 862 (class 1259 OID 894071)
-- Name: enum_xp_zweckbestimmunggemeinbedarf; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggemeinbedarf (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12833 (class 0 OID 0)
-- Dependencies: 862
-- Name: TABLE enum_xp_zweckbestimmunggemeinbedarf; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggemeinbedarf IS 'Alias: "enum_XP_ZweckbestimmungGemeinbedarf"';


--
-- TOC entry 861 (class 1259 OID 894037)
-- Name: enum_xp_zweckbestimmunggewaesser; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggewaesser (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12834 (class 0 OID 0)
-- Dependencies: 861
-- Name: TABLE enum_xp_zweckbestimmunggewaesser; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggewaesser IS 'Alias: "enum_XP_ZweckbestimmungGewaesser"';


--
-- TOC entry 852 (class 1259 OID 893547)
-- Name: enum_xp_zweckbestimmunggruen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunggruen (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12835 (class 0 OID 0)
-- Dependencies: 852
-- Name: TABLE enum_xp_zweckbestimmunggruen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunggruen IS 'Alias: "enum_XP_ZweckbestimmungGruen"';


--
-- TOC entry 848 (class 1259 OID 893327)
-- Name: enum_xp_zweckbestimmungkennzeichnung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungkennzeichnung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12836 (class 0 OID 0)
-- Dependencies: 848
-- Name: TABLE enum_xp_zweckbestimmungkennzeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungkennzeichnung IS 'Alias: "enum_XP_ZweckbestimmungKennzeichnung"';


--
-- TOC entry 849 (class 1259 OID 893363)
-- Name: enum_xp_zweckbestimmunglandwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmunglandwirtschaft (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12837 (class 0 OID 0)
-- Dependencies: 849
-- Name: TABLE enum_xp_zweckbestimmunglandwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmunglandwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungLandwirtschaft"';


--
-- TOC entry 865 (class 1259 OID 894145)
-- Name: enum_xp_zweckbestimmungspielsportanlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungspielsportanlage (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12838 (class 0 OID 0)
-- Dependencies: 865
-- Name: TABLE enum_xp_zweckbestimmungspielsportanlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungspielsportanlage IS 'Alias: "enum_XP_ZweckbestimmungSpielSportanlage"';


--
-- TOC entry 867 (class 1259 OID 894273)
-- Name: enum_xp_zweckbestimmungverentsorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungverentsorgung (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12839 (class 0 OID 0)
-- Dependencies: 867
-- Name: TABLE enum_xp_zweckbestimmungverentsorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungverentsorgung IS 'Alias: "enum_XP_ZweckbestimmungVerEntsorgung"';


--
-- TOC entry 856 (class 1259 OID 893841)
-- Name: enum_xp_zweckbestimmungwald; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungwald (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12840 (class 0 OID 0)
-- Dependencies: 856
-- Name: TABLE enum_xp_zweckbestimmungwald; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungwald IS 'Alias: "enum_XP_ZweckbestimmungWald"';


--
-- TOC entry 845 (class 1259 OID 893227)
-- Name: enum_xp_zweckbestimmungwasserwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE enum_xp_zweckbestimmungwasserwirtschaft (
    wert integer NOT NULL,
    beschreibung character varying
);


--
-- TOC entry 12841 (class 0 OID 0)
-- Dependencies: 845
-- Name: TABLE enum_xp_zweckbestimmungwasserwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE enum_xp_zweckbestimmungwasserwirtschaft IS 'Alias: "enum_XP_ZweckbestimmungWasserwirtschaft"';


--
-- TOC entry 927 (class 1259 OID 896682)
-- Name: xp_gesetzlichegrundlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_gesetzlichegrundlage (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12842 (class 0 OID 0)
-- Dependencies: 927
-- Name: TABLE xp_gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_gesetzlichegrundlage IS 'Alias: "XP_GesetzlicheGrundlage", UML-Typ: Code Liste';


--
-- TOC entry 12843 (class 0 OID 0)
-- Dependencies: 927
-- Name: COLUMN xp_gesetzlichegrundlage.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gesetzlichegrundlage.codespace IS 'codeSpace  text ';


--
-- TOC entry 12844 (class 0 OID 0)
-- Dependencies: 927
-- Name: COLUMN xp_gesetzlichegrundlage.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_gesetzlichegrundlage.id IS 'id  character varying ';


--
-- TOC entry 959 (class 1259 OID 896835)
-- Name: xp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    uuid character varying,
    text character varying,
    rechtsstand xp_rechtsstand,
    gesetzlichegrundlage xp_gesetzlichegrundlage,
    textschluessel character varying[],
    textschluesselbegruendung character varying[],
    gliederung1 character varying,
    gliederung2 character varying,
    ebene integer,
    rechtsverbindlich xp_externereferenz[],
    informell xp_externereferenz[],
    hatgenerattribut xp_generattribut[],
    hoehenangabe xp_hoehenangabe[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    reftextinhalt text[],
    refbegruendunginhalt text[],
    gehoertzubereich text[],
    wirddargestelltdurch text[]
);


--
-- TOC entry 12845 (class 0 OID 0)
-- Dependencies: 959
-- Name: TABLE xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt IS 'FeatureType: "XP_Objekt"';


--
-- TOC entry 12846 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.uuid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.uuid IS 'uuid  CharacterString 0..1';


--
-- TOC entry 12847 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.text IS 'text  CharacterString 0..1';


--
-- TOC entry 12848 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.rechtsstand IS 'rechtsstand enumeration XP_Rechtsstand 0..1';


--
-- TOC entry 12849 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gesetzlichegrundlage IS 'gesetzlicheGrundlage CodeList XP_GesetzlicheGrundlage 0..1';


--
-- TOC entry 12850 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.textschluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.textschluessel IS 'textSchluessel  CharacterString 0..*';


--
-- TOC entry 12851 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.textschluesselbegruendung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.textschluesselbegruendung IS 'textSchluesselBegruendung  CharacterString 0..*';


--
-- TOC entry 12852 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.gliederung1; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gliederung1 IS 'gliederung1  CharacterString 0..1';


--
-- TOC entry 12853 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.gliederung2; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gliederung2 IS 'gliederung2  CharacterString 0..1';


--
-- TOC entry 12854 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.ebene; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.ebene IS 'ebene  Integer 0..1';


--
-- TOC entry 12855 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.rechtsverbindlich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.rechtsverbindlich IS 'rechtsverbindlich DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 12856 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.informell; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.informell IS 'informell DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 12857 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.hatgenerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';


--
-- TOC entry 12858 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.hoehenangabe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.hoehenangabe IS 'hoehenangabe DataType XP_Hoehenangabe 0..*';


--
-- TOC entry 12859 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.user_id IS 'user_id  integer ';


--
-- TOC entry 12860 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 12861 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 12862 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 12863 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.reftextinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.reftextinhalt IS 'Assoziation zu: FeatureType XP_TextAbschnitt (xp_textabschnitt) 0..*';


--
-- TOC entry 12864 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.refbegruendunginhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.refbegruendunginhalt IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';


--
-- TOC entry 12865 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.gehoertzubereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..*';


--
-- TOC entry 12866 (class 0 OID 0)
-- Dependencies: 959
-- Name: COLUMN xp_objekt.wirddargestelltdurch; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt.wirddargestelltdurch IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';


--
-- TOC entry 960 (class 1259 OID 896846)
-- Name: rp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_objekt (
    rechtscharakter rp_rechtscharakter NOT NULL,
    konkretisierung character varying,
    gebietstyp rp_gebietstyp[],
    kuestenmeer boolean,
    bedeutsamkeit rp_bedeutsamkeit[],
    istzweckbindung boolean,
    gid integer
)
INHERITS (xp_objekt);


--
-- TOC entry 12867 (class 0 OID 0)
-- Dependencies: 960
-- Name: TABLE rp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_objekt IS 'FeatureType: "RP_Objekt"';


--
-- TOC entry 12868 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.rechtscharakter IS 'rechtscharakter enumeration RP_Rechtscharakter 1';


--
-- TOC entry 12869 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.konkretisierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.konkretisierung IS 'konkretisierung  CharacterString 0..1';


--
-- TOC entry 12870 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.gebietstyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.gebietstyp IS 'gebietsTyp enumeration RP_GebietsTyp 0..*';


--
-- TOC entry 12871 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.kuestenmeer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.kuestenmeer IS 'kuestenmeer  Boolean 0..1';


--
-- TOC entry 12872 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.bedeutsamkeit; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.bedeutsamkeit IS 'bedeutsamkeit enumeration RP_Bedeutsamkeit 0..*';


--
-- TOC entry 12873 (class 0 OID 0)
-- Dependencies: 960
-- Name: COLUMN rp_objekt.istzweckbindung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_objekt.istzweckbindung IS 'istZweckbindung  Boolean 0..1';


--
-- TOC entry 961 (class 1259 OID 896855)
-- Name: rp_geometrieobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_geometrieobjekt (
    "position" public.geometry NOT NULL,
    flaechenschluss boolean
)
INHERITS (rp_objekt);


--
-- TOC entry 12874 (class 0 OID 0)
-- Dependencies: 961
-- Name: TABLE rp_geometrieobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_geometrieobjekt IS 'FeatureType: "RP_Geometrieobjekt"';


--
-- TOC entry 12875 (class 0 OID 0)
-- Dependencies: 961
-- Name: COLUMN rp_geometrieobjekt."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_geometrieobjekt."position" IS 'position Union XP_VariableGeometrie 1';


--
-- TOC entry 12876 (class 0 OID 0)
-- Dependencies: 961
-- Name: COLUMN rp_geometrieobjekt.flaechenschluss; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_geometrieobjekt.flaechenschluss IS 'flaechenschluss  Boolean 0..1';


--
-- TOC entry 995 (class 1259 OID 897161)
-- Name: rp_achse; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_achse (
    typ rp_achsentypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12877 (class 0 OID 0)
-- Dependencies: 995
-- Name: TABLE rp_achse; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_achse IS 'FeatureType: "RP_Achse"';


--
-- TOC entry 12878 (class 0 OID 0)
-- Dependencies: 995
-- Name: COLUMN rp_achse.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_achse.typ IS 'typ enumeration RP_AchsenTypen 0..*';


--
-- TOC entry 1006 (class 1259 OID 897260)
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
-- TOC entry 12879 (class 0 OID 0)
-- Dependencies: 1006
-- Name: TABLE xp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_bereich IS 'FeatureType: "XP_Bereich"';


--
-- TOC entry 12880 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.nummer IS 'nummer  Integer 1';


--
-- TOC entry 12881 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.name IS 'name  CharacterString 0..1';


--
-- TOC entry 12882 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.bedeutung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.bedeutung IS 'bedeutung enumeration XP_BedeutungenBereich 0..1';


--
-- TOC entry 12883 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.detailliertebedeutung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.detailliertebedeutung IS 'detaillierteBedeutung  CharacterString 0..1';


--
-- TOC entry 12884 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.erstellungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';


--
-- TOC entry 12885 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.geltungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.geltungsbereich IS 'geltungsbereich Union XP_Flaechengeometrie 0..1';


--
-- TOC entry 12886 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.user_id IS 'user_id  integer ';


--
-- TOC entry 12887 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 12888 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 12889 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 12890 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.planinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.planinhalt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- TOC entry 12891 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.praesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.praesentationsobjekt IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..*';


--
-- TOC entry 12892 (class 0 OID 0)
-- Dependencies: 1006
-- Name: COLUMN xp_bereich.rasterbasis; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich.rasterbasis IS 'Assoziation zu: FeatureType XP_RasterplanBasis (xp_rasterplanbasis) 0..1';


--
-- TOC entry 1007 (class 1259 OID 897271)
-- Name: rp_bereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_bereich (
    versionbrog date,
    versionbrogtext character varying,
    versionlplg date,
    versionlplgtext character varying,
    geltungsmassstab integer,
    gehoertzuplan text NOT NULL,
    rasteraenderung text[]
)
INHERITS (xp_bereich);


--
-- TOC entry 12893 (class 0 OID 0)
-- Dependencies: 1007
-- Name: TABLE rp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_bereich IS 'FeatureType: "RP_Bereich"';


--
-- TOC entry 12894 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.versionbrog; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionbrog IS 'versionBROG  Date 0..1';


--
-- TOC entry 12895 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.versionbrogtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionbrogtext IS 'versionBROGText  CharacterString 0..1';


--
-- TOC entry 12896 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.versionlplg; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionlplg IS 'versionLPLG  Date 0..1';


--
-- TOC entry 12897 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.versionlplgtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.versionlplgtext IS 'versionLPLGText  CharacterString 0..1';


--
-- TOC entry 12898 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.geltungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.geltungsmassstab IS 'geltungsmassstab  Integer 0..1';


--
-- TOC entry 12899 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.gehoertzuplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.gehoertzuplan IS 'Assoziation zu: FeatureType RP_Plan (rp_plan) 1';


--
-- TOC entry 12900 (class 0 OID 0)
-- Dependencies: 1007
-- Name: COLUMN rp_bereich.rasteraenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich.rasteraenderung IS 'Assoziation zu: FeatureType RP_RasterplanAenderung (rp_rasterplanaenderung) 0..*';


SET default_with_oids = false;

--
-- TOC entry 1027 (class 1259 OID 897445)
-- Name: rp_bereich_zu_rp_rasterplanaenderung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_bereich_zu_rp_rasterplanaenderung (
    rp_bereich_gml_id uuid NOT NULL,
    rp_rasterplanaenderung_gml_id uuid NOT NULL
);


--
-- TOC entry 12901 (class 0 OID 0)
-- Dependencies: 1027
-- Name: TABLE rp_bereich_zu_rp_rasterplanaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_bereich_zu_rp_rasterplanaenderung IS 'Association RP_Bereich _zu_ RP_RasterplanAenderung';


--
-- TOC entry 12902 (class 0 OID 0)
-- Dependencies: 1027
-- Name: COLUMN rp_bereich_zu_rp_rasterplanaenderung.rp_bereich_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bereich_zu_rp_rasterplanaenderung.rp_bereich_gml_id IS 'rasterAenderung';


SET default_with_oids = true;

--
-- TOC entry 962 (class 1259 OID 896864)
-- Name: rp_freiraum; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_freiraum (
    istausgleichsgebiet boolean,
    imverbund boolean
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12903 (class 0 OID 0)
-- Dependencies: 962
-- Name: TABLE rp_freiraum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_freiraum IS 'FeatureType: "RP_Freiraum"';


--
-- TOC entry 12904 (class 0 OID 0)
-- Dependencies: 962
-- Name: COLUMN rp_freiraum.istausgleichsgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_freiraum.istausgleichsgebiet IS 'istAusgleichsgebiet  boolean 0..1';


--
-- TOC entry 12905 (class 0 OID 0)
-- Dependencies: 962
-- Name: COLUMN rp_freiraum.imverbund; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_freiraum.imverbund IS 'imVerbund  boolean 0..1';


--
-- TOC entry 978 (class 1259 OID 897008)
-- Name: rp_bodenschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_bodenschutz (
    typ rp_bodenschutztypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12906 (class 0 OID 0)
-- Dependencies: 978
-- Name: TABLE rp_bodenschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_bodenschutz IS 'FeatureType: "RP_Bodenschutz"';


--
-- TOC entry 12907 (class 0 OID 0)
-- Dependencies: 978
-- Name: COLUMN rp_bodenschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_bodenschutz.typ IS 'typ enumeration RP_BodenschutzTypen 0..1';


--
-- TOC entry 997 (class 1259 OID 897179)
-- Name: rp_siedlung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_siedlung (
    bauhoehenbeschraenkung integer,
    istsiedlungsbeschraenkung boolean
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12908 (class 0 OID 0)
-- Dependencies: 997
-- Name: TABLE rp_siedlung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_siedlung IS 'FeatureType: "RP_Siedlung"';


--
-- TOC entry 12909 (class 0 OID 0)
-- Dependencies: 997
-- Name: COLUMN rp_siedlung.bauhoehenbeschraenkung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_siedlung.bauhoehenbeschraenkung IS 'bauhoehenbeschraenkung  Integer 0..1';


--
-- TOC entry 12910 (class 0 OID 0)
-- Dependencies: 997
-- Name: COLUMN rp_siedlung.istsiedlungsbeschraenkung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_siedlung.istsiedlungsbeschraenkung IS 'istSiedlungsbeschraenkung  Boolean 0..1';


--
-- TOC entry 1000 (class 1259 OID 897206)
-- Name: rp_einzelhandel; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_einzelhandel (
    typ rp_einzelhandeltypen[]
)
INHERITS (rp_siedlung);


--
-- TOC entry 12911 (class 0 OID 0)
-- Dependencies: 1000
-- Name: TABLE rp_einzelhandel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_einzelhandel IS 'FeatureType: "RP_Einzelhandel"';


--
-- TOC entry 12912 (class 0 OID 0)
-- Dependencies: 1000
-- Name: COLUMN rp_einzelhandel.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_einzelhandel.typ IS 'typ enumeration RP_EinzelhandelTypen 0..*';


--
-- TOC entry 981 (class 1259 OID 897035)
-- Name: rp_energieversorgung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_energieversorgung (
    typ rp_energieversorgungtypen[],
    primaerenergietyp rp_primaerenergietypen[],
    spannung rp_spannungtypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12913 (class 0 OID 0)
-- Dependencies: 981
-- Name: TABLE rp_energieversorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_energieversorgung IS 'FeatureType: "RP_Energieversorgung"';


--
-- TOC entry 12914 (class 0 OID 0)
-- Dependencies: 981
-- Name: COLUMN rp_energieversorgung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.typ IS 'typ enumeration RP_EnergieversorgungTypen 0..*';


--
-- TOC entry 12915 (class 0 OID 0)
-- Dependencies: 981
-- Name: COLUMN rp_energieversorgung.primaerenergietyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.primaerenergietyp IS 'primaerenergieTyp enumeration RP_PrimaerenergieTypen 0..*';


--
-- TOC entry 12916 (class 0 OID 0)
-- Dependencies: 981
-- Name: COLUMN rp_energieversorgung.spannung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_energieversorgung.spannung IS 'spannung enumeration RP_SpannungTypen 0..1';


--
-- TOC entry 991 (class 1259 OID 897125)
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
-- TOC entry 12917 (class 0 OID 0)
-- Dependencies: 991
-- Name: TABLE rp_entsorgung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_entsorgung IS 'FeatureType: "RP_Entsorgung"';


--
-- TOC entry 12918 (class 0 OID 0)
-- Dependencies: 991
-- Name: COLUMN rp_entsorgung.typae; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.typae IS 'typAE enumeration RP_AbfallentsorgungTypen 0..*';


--
-- TOC entry 12919 (class 0 OID 0)
-- Dependencies: 991
-- Name: COLUMN rp_entsorgung.abfalltyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.abfalltyp IS 'abfallTyp enumeration RP_AbfallTypen 0..*';


--
-- TOC entry 12920 (class 0 OID 0)
-- Dependencies: 991
-- Name: COLUMN rp_entsorgung.typaw; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.typaw IS 'typAW enumeration RP_AbwasserTypen 0..*';


--
-- TOC entry 12921 (class 0 OID 0)
-- Dependencies: 991
-- Name: COLUMN rp_entsorgung.istaufschuettungablagerung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_entsorgung.istaufschuettungablagerung IS 'istAufschuettungAblagerung  Boolean 0..1';


--
-- TOC entry 972 (class 1259 OID 896954)
-- Name: rp_erholung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_erholung (
    typerholung rp_erholungtypen[],
    typtourismus rp_tourismustypen[],
    besonderertyp rp_besonderetourismuserholungtypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12922 (class 0 OID 0)
-- Dependencies: 972
-- Name: TABLE rp_erholung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_erholung IS 'FeatureType: "RP_Erholung"';


--
-- TOC entry 12923 (class 0 OID 0)
-- Dependencies: 972
-- Name: COLUMN rp_erholung.typerholung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.typerholung IS 'typErholung enumeration RP_ErholungTypen 0..*';


--
-- TOC entry 12924 (class 0 OID 0)
-- Dependencies: 972
-- Name: COLUMN rp_erholung.typtourismus; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.typtourismus IS 'typTourismus enumeration RP_TourismusTypen 0..*';


--
-- TOC entry 12925 (class 0 OID 0)
-- Dependencies: 972
-- Name: COLUMN rp_erholung.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erholung.besonderertyp IS 'besondererTyp enumeration RP_BesondereTourismusErholungTypen 0..1';


--
-- TOC entry 979 (class 1259 OID 897017)
-- Name: rp_erneuerbareenergie; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_erneuerbareenergie (
    typ rp_erneuerbareenergietypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12926 (class 0 OID 0)
-- Dependencies: 979
-- Name: TABLE rp_erneuerbareenergie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_erneuerbareenergie IS 'FeatureType: "RP_ErneuerbareEnergie"';


--
-- TOC entry 12927 (class 0 OID 0)
-- Dependencies: 979
-- Name: COLUMN rp_erneuerbareenergie.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_erneuerbareenergie.typ IS 'typ enumeration RP_ErneuerbareEnergieTypen 0..1';


--
-- TOC entry 969 (class 1259 OID 896927)
-- Name: rp_forstwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_forstwirtschaft (
    typ rp_forstwirtschafttypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12928 (class 0 OID 0)
-- Dependencies: 969
-- Name: TABLE rp_forstwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_forstwirtschaft IS 'FeatureType: "RP_Forstwirtschaft"';


--
-- TOC entry 12929 (class 0 OID 0)
-- Dependencies: 969
-- Name: COLUMN rp_forstwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_forstwirtschaft.typ IS 'typ enumeration RP_ForstwirtschaftTypen 0..1';


--
-- TOC entry 994 (class 1259 OID 897152)
-- Name: rp_funktionszuweisung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_funktionszuweisung (
    typ rp_funktionszuweisungtypen[] NOT NULL,
    bezeichnung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12930 (class 0 OID 0)
-- Dependencies: 994
-- Name: TABLE rp_funktionszuweisung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_funktionszuweisung IS 'FeatureType: "RP_Funktionszuweisung"';


--
-- TOC entry 12931 (class 0 OID 0)
-- Dependencies: 994
-- Name: COLUMN rp_funktionszuweisung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_funktionszuweisung.typ IS 'typ enumeration RP_FunktionszuweisungTypen 1..*';


--
-- TOC entry 12932 (class 0 OID 0)
-- Dependencies: 994
-- Name: COLUMN rp_funktionszuweisung.bezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_funktionszuweisung.bezeichnung IS 'bezeichnung  CharacterString 0..1';


--
-- TOC entry 932 (class 1259 OID 896722)
-- Name: rp_generischesobjekttypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_generischesobjekttypen (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12933 (class 0 OID 0)
-- Dependencies: 932
-- Name: TABLE rp_generischesobjekttypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_generischesobjekttypen IS 'Alias: "RP_GenerischesObjektTypen", UML-Typ: Code Liste';


--
-- TOC entry 12934 (class 0 OID 0)
-- Dependencies: 932
-- Name: COLUMN rp_generischesobjekttypen.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekttypen.codespace IS 'codeSpace  text ';


--
-- TOC entry 12935 (class 0 OID 0)
-- Dependencies: 932
-- Name: COLUMN rp_generischesobjekttypen.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekttypen.id IS 'id  character varying ';


--
-- TOC entry 1003 (class 1259 OID 897233)
-- Name: rp_generischesobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_generischesobjekt (
    typ rp_generischesobjekttypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12936 (class 0 OID 0)
-- Dependencies: 1003
-- Name: TABLE rp_generischesobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_generischesobjekt IS 'FeatureType: "RP_GenerischesObjekt"';


--
-- TOC entry 12937 (class 0 OID 0)
-- Dependencies: 1003
-- Name: COLUMN rp_generischesobjekt.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_generischesobjekt.typ IS 'typ CodeList RP_GenerischesObjektTypen 0..1';


--
-- TOC entry 967 (class 1259 OID 896909)
-- Name: rp_gewaesser; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_gewaesser (
    gewaessertyp character varying
)
INHERITS (rp_freiraum);


--
-- TOC entry 12938 (class 0 OID 0)
-- Dependencies: 967
-- Name: TABLE rp_gewaesser; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_gewaesser IS 'FeatureType: "RP_Gewaesser"';


--
-- TOC entry 12939 (class 0 OID 0)
-- Dependencies: 967
-- Name: COLUMN rp_gewaesser.gewaessertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_gewaesser.gewaessertyp IS 'gewaesserTyp  CharacterString 0..1';


--
-- TOC entry 933 (class 1259 OID 896730)
-- Name: rp_sonstgrenzetypen; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstgrenzetypen (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12940 (class 0 OID 0)
-- Dependencies: 933
-- Name: TABLE rp_sonstgrenzetypen; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstgrenzetypen IS 'Alias: "RP_SonstGrenzeTypen", UML-Typ: Code Liste';


--
-- TOC entry 12941 (class 0 OID 0)
-- Dependencies: 933
-- Name: COLUMN rp_sonstgrenzetypen.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstgrenzetypen.codespace IS 'codeSpace  text ';


--
-- TOC entry 12942 (class 0 OID 0)
-- Dependencies: 933
-- Name: COLUMN rp_sonstgrenzetypen.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstgrenzetypen.id IS 'id  character varying ';


--
-- TOC entry 1005 (class 1259 OID 897251)
-- Name: rp_grenze; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_grenze (
    typ xp_grenzetypen[],
    spezifischertyp rp_spezifischegrenzetypen,
    sonsttyp rp_sonstgrenzetypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12943 (class 0 OID 0)
-- Dependencies: 1005
-- Name: TABLE rp_grenze; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_grenze IS 'FeatureType: "RP_Grenze"';


--
-- TOC entry 12944 (class 0 OID 0)
-- Dependencies: 1005
-- Name: COLUMN rp_grenze.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.typ IS 'typ enumeration XP_GrenzeTypen 0..*';


--
-- TOC entry 12945 (class 0 OID 0)
-- Dependencies: 1005
-- Name: COLUMN rp_grenze.spezifischertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.spezifischertyp IS 'spezifischerTyp enumeration RP_SpezifischeGrenzeTypen 0..1';


--
-- TOC entry 12946 (class 0 OID 0)
-- Dependencies: 1005
-- Name: COLUMN rp_grenze.sonsttyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_grenze.sonsttyp IS 'sonstTyp CodeList RP_SonstGrenzeTypen 0..1';


--
-- TOC entry 971 (class 1259 OID 896945)
-- Name: rp_gruenzuggruenzaesur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_gruenzuggruenzaesur (
    typ rp_zaesurtypen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 12947 (class 0 OID 0)
-- Dependencies: 971
-- Name: TABLE rp_gruenzuggruenzaesur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_gruenzuggruenzaesur IS 'FeatureType: "RP_GruenzugGruenzaesur"';


--
-- TOC entry 12948 (class 0 OID 0)
-- Dependencies: 971
-- Name: COLUMN rp_gruenzuggruenzaesur.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_gruenzuggruenzaesur.typ IS 'typ enumeration RP_ZaesurTypen 0..*';


--
-- TOC entry 963 (class 1259 OID 896873)
-- Name: rp_hochwasserschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_hochwasserschutz (
    typ rp_hochwasserschutztypen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 12949 (class 0 OID 0)
-- Dependencies: 963
-- Name: TABLE rp_hochwasserschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_hochwasserschutz IS 'FeatureType: "RP_Hochwasserschutz"';


--
-- TOC entry 12950 (class 0 OID 0)
-- Dependencies: 963
-- Name: COLUMN rp_hochwasserschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_hochwasserschutz.typ IS 'typ enumeration RP_HochwasserschutzTypen 0..*';


--
-- TOC entry 1001 (class 1259 OID 897215)
-- Name: rp_industriegewerbe; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_industriegewerbe (
    typ rp_industriegewerbetypen[]
)
INHERITS (rp_siedlung);


--
-- TOC entry 12951 (class 0 OID 0)
-- Dependencies: 1001
-- Name: TABLE rp_industriegewerbe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_industriegewerbe IS 'FeatureType: "RP_IndustrieGewerbe"';


--
-- TOC entry 12952 (class 0 OID 0)
-- Dependencies: 1001
-- Name: COLUMN rp_industriegewerbe.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_industriegewerbe.typ IS 'typ enumeration RP_IndustrieGewerbeTypen 0..*';


--
-- TOC entry 977 (class 1259 OID 896999)
-- Name: rp_klimaschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_klimaschutz (
    typ rp_lufttypen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 12953 (class 0 OID 0)
-- Dependencies: 977
-- Name: TABLE rp_klimaschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_klimaschutz IS 'FeatureType: "RP_Klimaschutz"';


--
-- TOC entry 12954 (class 0 OID 0)
-- Dependencies: 977
-- Name: COLUMN rp_klimaschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_klimaschutz.typ IS 'typ enumeration RP_LuftTypen 0..*';


--
-- TOC entry 983 (class 1259 OID 897053)
-- Name: rp_kommunikation; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_kommunikation (
    typ rp_kommunikationtypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12955 (class 0 OID 0)
-- Dependencies: 983
-- Name: TABLE rp_kommunikation; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_kommunikation IS 'FeatureType: "RP_Kommunikation"';


--
-- TOC entry 12956 (class 0 OID 0)
-- Dependencies: 983
-- Name: COLUMN rp_kommunikation.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_kommunikation.typ IS 'typ enumeration RP_KommunikationTypen 0..1';


--
-- TOC entry 965 (class 1259 OID 896891)
-- Name: rp_kulturlandschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_kulturlandschaft (
    typ rp_kulturlandschafttypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12957 (class 0 OID 0)
-- Dependencies: 965
-- Name: TABLE rp_kulturlandschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_kulturlandschaft IS 'FeatureType: "RP_Kulturlandschaft"';


--
-- TOC entry 12958 (class 0 OID 0)
-- Dependencies: 965
-- Name: COLUMN rp_kulturlandschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_kulturlandschaft.typ IS 'typ enumeration RP_KulturlandschaftTypen 0..1';


--
-- TOC entry 982 (class 1259 OID 897044)
-- Name: rp_laermschutzbauschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_laermschutzbauschutz (
    typ rp_laermschutztypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12959 (class 0 OID 0)
-- Dependencies: 982
-- Name: TABLE rp_laermschutzbauschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_laermschutzbauschutz IS 'FeatureType: "RP_LaermschutzBauschutz"';


--
-- TOC entry 12960 (class 0 OID 0)
-- Dependencies: 982
-- Name: COLUMN rp_laermschutzbauschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_laermschutzbauschutz.typ IS 'typ enumeration RP_LaermschutzTypen 0..1';


--
-- TOC entry 975 (class 1259 OID 896981)
-- Name: rp_landwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_landwirtschaft (
    typ rp_landwirtschafttypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 12961 (class 0 OID 0)
-- Dependencies: 975
-- Name: TABLE rp_landwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_landwirtschaft IS 'FeatureType: "RP_Landwirtschaft"';


--
-- TOC entry 12962 (class 0 OID 0)
-- Dependencies: 975
-- Name: COLUMN rp_landwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_landwirtschaft.typ IS 'typ enumeration RP_LandwirtschaftTypen 0..1';


--
-- TOC entry 1022 (class 1259 OID 897414)
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
-- TOC entry 12963 (class 0 OID 0)
-- Dependencies: 1022
-- Name: TABLE rp_legendenobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_legendenobjekt IS 'FeatureType: "RP_Legendenobjekt"';


--
-- TOC entry 12964 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.legendenbezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.legendenbezeichnung IS 'legendenBezeichnung  CharacterString 1';


--
-- TOC entry 12965 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.reflegendenbild; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.reflegendenbild IS 'reflegendenBild DataType XP_ExterneReferenz 1';


--
-- TOC entry 12966 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.user_id IS 'user_id  integer ';


--
-- TOC entry 12967 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 12968 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 12969 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 12970 (class 0 OID 0)
-- Dependencies: 1022
-- Name: COLUMN rp_legendenobjekt.gehoertzupraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_legendenobjekt.gehoertzupraesentationsobjekt IS 'Assoziation zu: FeatureType XP_AbstraktesPraesentationsobjekt (xp_abstraktespraesentationsobjekt) 0..1';


--
-- TOC entry 984 (class 1259 OID 897062)
-- Name: rp_verkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_verkehr (
    allgemeinertyp rp_verkehrtypen[],
    status rp_verkehrstatus[],
    bezeichnung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 12971 (class 0 OID 0)
-- Dependencies: 984
-- Name: TABLE rp_verkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_verkehr IS 'FeatureType: "RP_Verkehr"';


--
-- TOC entry 12972 (class 0 OID 0)
-- Dependencies: 984
-- Name: COLUMN rp_verkehr.allgemeinertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.allgemeinertyp IS 'allgemeinerTyp enumeration RP_VerkehrTypen 0..*';


--
-- TOC entry 12973 (class 0 OID 0)
-- Dependencies: 984
-- Name: COLUMN rp_verkehr.status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.status IS 'status enumeration RP_VerkehrStatus 0..*';


--
-- TOC entry 12974 (class 0 OID 0)
-- Dependencies: 984
-- Name: COLUMN rp_verkehr.bezeichnung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_verkehr.bezeichnung IS 'bezeichnung  CharacterString 0..1';


--
-- TOC entry 987 (class 1259 OID 897089)
-- Name: rp_luftverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_luftverkehr (
    typ rp_luftverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- TOC entry 12975 (class 0 OID 0)
-- Dependencies: 987
-- Name: TABLE rp_luftverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_luftverkehr IS 'FeatureType: "RP_Luftverkehr"';


--
-- TOC entry 12976 (class 0 OID 0)
-- Dependencies: 987
-- Name: COLUMN rp_luftverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_luftverkehr.typ IS 'typ enumeration RP_LuftverkehrTypen 0..*';


--
-- TOC entry 964 (class 1259 OID 896882)
-- Name: rp_naturlandschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_naturlandschaft (
    typ rp_naturlandschafttypen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 12977 (class 0 OID 0)
-- Dependencies: 964
-- Name: TABLE rp_naturlandschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_naturlandschaft IS 'FeatureType: "RP_NaturLandschaft"';


--
-- TOC entry 12978 (class 0 OID 0)
-- Dependencies: 964
-- Name: COLUMN rp_naturlandschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturlandschaft.typ IS 'typ enumeration RP_NaturLandschaftTypen 0..*';


--
-- TOC entry 968 (class 1259 OID 896918)
-- Name: rp_naturschutzrechtlichesschutzgebiet; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_naturschutzrechtlichesschutzgebiet (
    typ xp_klassifizschutzgebietnaturschutzrecht[],
    istkernzone boolean
)
INHERITS (rp_freiraum);


--
-- TOC entry 12979 (class 0 OID 0)
-- Dependencies: 968
-- Name: TABLE rp_naturschutzrechtlichesschutzgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_naturschutzrechtlichesschutzgebiet IS 'FeatureType: "RP_NaturschutzrechtlichesSchutzgebiet"';


--
-- TOC entry 12980 (class 0 OID 0)
-- Dependencies: 968
-- Name: COLUMN rp_naturschutzrechtlichesschutzgebiet.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturschutzrechtlichesschutzgebiet.typ IS 'typ enumeration XP_KlassifizSchutzgebietNaturschutzrecht 0..*';


--
-- TOC entry 12981 (class 0 OID 0)
-- Dependencies: 968
-- Name: COLUMN rp_naturschutzrechtlichesschutzgebiet.istkernzone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_naturschutzrechtlichesschutzgebiet.istkernzone IS 'istKernzone  Boolean 0..1';


--
-- TOC entry 931 (class 1259 OID 896714)
-- Name: rp_sonstplanart; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstplanart (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12982 (class 0 OID 0)
-- Dependencies: 931
-- Name: TABLE rp_sonstplanart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstplanart IS 'Alias: "RP_SonstPlanArt", UML-Typ: Code Liste';


--
-- TOC entry 12983 (class 0 OID 0)
-- Dependencies: 931
-- Name: COLUMN rp_sonstplanart.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstplanart.codespace IS 'codeSpace  text ';


--
-- TOC entry 12984 (class 0 OID 0)
-- Dependencies: 931
-- Name: COLUMN rp_sonstplanart.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstplanart.id IS 'id  character varying ';


--
-- TOC entry 930 (class 1259 OID 896706)
-- Name: rp_status; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_status (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 12985 (class 0 OID 0)
-- Dependencies: 930
-- Name: TABLE rp_status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_status IS 'Alias: "RP_Status", UML-Typ: Code Liste';


--
-- TOC entry 12986 (class 0 OID 0)
-- Dependencies: 930
-- Name: COLUMN rp_status.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_status.codespace IS 'codeSpace  text ';


--
-- TOC entry 12987 (class 0 OID 0)
-- Dependencies: 930
-- Name: COLUMN rp_status.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_status.id IS 'id  character varying ';


--
-- TOC entry 1008 (class 1259 OID 897280)
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
    rechtsverbindlich xp_externereferenz[],
    informell xp_externereferenz[],
    hatgenerattribut xp_generattribut[],
    refbeschreibung xp_externereferenz[],
    refbegruendung xp_externereferenz[],
    refexternalcodelist xp_externereferenz,
    reflegende xp_externereferenz[],
    refrechtsplan xp_externereferenz[],
    refplangrundlage xp_externereferenz[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    texte text[],
    begruendungstexte text[]
);


--
-- TOC entry 12988 (class 0 OID 0)
-- Dependencies: 1008
-- Name: TABLE xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_plan IS 'FeatureType: "XP_Plan"';


--
-- TOC entry 12989 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.name; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.name IS 'name  CharacterString 1';


--
-- TOC entry 12990 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.nummer; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.nummer IS 'nummer  CharacterString 0..1';


--
-- TOC entry 12991 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.internalid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.internalid IS 'internalId  CharacterString 0..1';


--
-- TOC entry 12992 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.beschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.beschreibung IS 'beschreibung  CharacterString 0..1';


--
-- TOC entry 12993 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.kommentar; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.kommentar IS 'kommentar  CharacterString 0..1';


--
-- TOC entry 12994 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.technherstelldatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.technherstelldatum IS 'technHerstellDatum  Date 0..1';


--
-- TOC entry 12995 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.genehmigungsdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.genehmigungsdatum IS 'genehmigungsDatum  Date 0..1';


--
-- TOC entry 12996 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.untergangsdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.untergangsdatum IS 'untergangsDatum  Date 0..1';


--
-- TOC entry 12997 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.aendert; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.aendert IS 'aendert DataType XP_VerbundenerPlan 0..*';


--
-- TOC entry 12998 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.wurdegeaendertvon; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.wurdegeaendertvon IS 'wurdeGeaendertVon DataType XP_VerbundenerPlan 0..*';


--
-- TOC entry 12999 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.erstellungsmassstab; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.erstellungsmassstab IS 'erstellungsMassstab  Integer 0..1';


--
-- TOC entry 13000 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.bezugshoehe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.bezugshoehe IS 'bezugshoehe  Length 0..1';


--
-- TOC entry 13001 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.raeumlichergeltungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.raeumlichergeltungsbereich IS 'raeumlicherGeltungsbereich Union XP_Flaechengeometrie 1';


--
-- TOC entry 13002 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.verfahrensmerkmale; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.verfahrensmerkmale IS 'verfahrensMerkmale DataType XP_VerfahrensMerkmal 0..*';


--
-- TOC entry 13003 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.rechtsverbindlich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.rechtsverbindlich IS 'rechtsverbindlich DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13004 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.informell; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.informell IS 'informell DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13005 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.hatgenerattribut; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.hatgenerattribut IS 'hatGenerAttribut DataType XP_GenerAttribut 0..*';


--
-- TOC entry 13006 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.refbeschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.refbeschreibung IS 'refBeschreibung DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13007 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.refbegruendung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.refbegruendung IS 'refBegruendung DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13008 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.refexternalcodelist; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.refexternalcodelist IS 'refExternalCodeList DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13009 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.reflegende; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13010 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.refrechtsplan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.refrechtsplan IS 'refRechtsplan DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13011 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.refplangrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.refplangrundlage IS 'refPlangrundlage DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13012 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.user_id IS 'user_id  integer ';


--
-- TOC entry 13013 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13014 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13015 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 13016 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.texte; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.texte IS 'Assoziation zu: FeatureType XP_TextAbschnitt (xp_textabschnitt) 0..*';


--
-- TOC entry 13017 (class 0 OID 0)
-- Dependencies: 1008
-- Name: COLUMN xp_plan.begruendungstexte; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan.begruendungstexte IS 'Assoziation zu: FeatureType XP_BegruendungAbschnitt (xp_begruendungabschnitt) 0..*';


--
-- TOC entry 1009 (class 1259 OID 897291)
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
    refumweltbericht xp_externereferenz,
    refsatzung xp_externereferenz,
    verfahren rp_verfahren,
    refkarte xp_externereferenz[],
    amtlicherschluessel integer,
    bereich text[]
)
INHERITS (xp_plan);


--
-- TOC entry 13018 (class 0 OID 0)
-- Dependencies: 1009
-- Name: TABLE rp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_plan IS 'FeatureType: "RP_Plan"';


--
-- TOC entry 13019 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.bundesland; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.bundesland IS 'bundesland enumeration XP_Bundeslaender 1..*';


--
-- TOC entry 13020 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.planart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planart IS 'planArt enumeration RP_Art 1';


--
-- TOC entry 13021 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.sonstplanart; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.sonstplanart IS 'sonstPlanArt CodeList RP_SonstPlanArt 0..1';


--
-- TOC entry 13022 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.planungsregion; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planungsregion IS 'planungsregion  Integer 0..1';


--
-- TOC entry 13023 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.teilabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.teilabschnitt IS 'teilabschnitt  Integer 0..1';


--
-- TOC entry 13024 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.rechtsstand; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.rechtsstand IS 'rechtsstand enumeration RP_Rechtsstand 0..1';


--
-- TOC entry 13025 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.status; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.status IS 'status CodeList RP_Status 0..1';


--
-- TOC entry 13026 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.aufstellungsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.aufstellungsbeschlussdatum IS 'aufstellungsbeschlussDatum  Date 0..1';


--
-- TOC entry 13027 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.auslegungstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.auslegungstartdatum IS 'auslegungStartDatum  Date 0..1';


--
-- TOC entry 13028 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.auslegungenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.auslegungenddatum IS 'auslegungEndDatum  Date 0..1';


--
-- TOC entry 13029 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.traegerbeteiligungsstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.traegerbeteiligungsstartdatum IS 'traegerbeteiligungsStartDatum  Date 0..1';


--
-- TOC entry 13030 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.traegerbeteiligungsenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.traegerbeteiligungsenddatum IS 'traegerbeteiligungsEndDatum  Date 0..1';


--
-- TOC entry 13031 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.aenderungenbisdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.aenderungenbisdatum IS 'aenderungenBisDatum  Date 0..1';


--
-- TOC entry 13032 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.entwurfsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.entwurfsbeschlussdatum IS 'entwurfsbeschlussDatum  Date 0..1';


--
-- TOC entry 13033 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.planbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.planbeschlussdatum IS 'planbeschlussDatum  Date 0..1';


--
-- TOC entry 13034 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.datumdesinkrafttretens; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.datumdesinkrafttretens IS 'datumDesInkrafttretens  Date 0..1';


--
-- TOC entry 13035 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.refumweltbericht; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.refumweltbericht IS 'refUmweltbericht DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13036 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.refsatzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.refsatzung IS 'refSatzung DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13037 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.verfahren; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.verfahren IS 'verfahren enumeration RP_Verfahren 0..1';


--
-- TOC entry 13038 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.refkarte; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.refkarte IS 'refKarte DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13039 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.amtlicherschluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.amtlicherschluessel IS 'amtlicherSchluessel  Integer 0..1';


--
-- TOC entry 13040 (class 0 OID 0)
-- Dependencies: 1009
-- Name: COLUMN rp_plan.bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_plan.bereich IS 'Assoziation zu: FeatureType RP_Bereich (rp_bereich) 0..*';


--
-- TOC entry 1004 (class 1259 OID 897242)
-- Name: rp_planungsraum; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_planungsraum (
    planungsraumbeschreibung character varying
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13041 (class 0 OID 0)
-- Dependencies: 1004
-- Name: TABLE rp_planungsraum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_planungsraum IS 'FeatureType: "RP_Planungsraum"';


--
-- TOC entry 13042 (class 0 OID 0)
-- Dependencies: 1004
-- Name: COLUMN rp_planungsraum.planungsraumbeschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_planungsraum.planungsraumbeschreibung IS 'planungsraumBeschreibung  CharacterString 0..1';


--
-- TOC entry 976 (class 1259 OID 896990)
-- Name: rp_radwegwanderweg; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_radwegwanderweg (
    typ rp_radwegwanderwegtypen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 13043 (class 0 OID 0)
-- Dependencies: 976
-- Name: TABLE rp_radwegwanderweg; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_radwegwanderweg IS 'FeatureType: "RP_RadwegWanderweg"';


--
-- TOC entry 13044 (class 0 OID 0)
-- Dependencies: 976
-- Name: COLUMN rp_radwegwanderweg.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_radwegwanderweg.typ IS 'typ enumeration RP_RadwegWanderwegTypen 0..*';


--
-- TOC entry 1019 (class 1259 OID 897383)
-- Name: xp_rasterplanaenderung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_rasterplanaenderung (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    nameaenderung character varying,
    nummeraenderung integer,
    beschreibung character varying,
    refbeschreibung xp_externereferenz,
    refbegruendung xp_externereferenz,
    refscan xp_externereferenz[] NOT NULL,
    reftext xp_externereferenz,
    reflegende xp_externereferenz[],
    geltungsbereichaenderung public.geometry(MultiPolygon),
    besonderheit character varying,
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer
);


--
-- TOC entry 13045 (class 0 OID 0)
-- Dependencies: 1019
-- Name: TABLE xp_rasterplanaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_rasterplanaenderung IS 'FeatureType: "XP_RasterplanAenderung"';


--
-- TOC entry 13046 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.nameaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.nameaenderung IS 'nameAenderung  CharacterString 0..1';


--
-- TOC entry 13047 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.nummeraenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.nummeraenderung IS 'nummerAenderung  Integer 0..1';


--
-- TOC entry 13048 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.beschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.beschreibung IS 'beschreibung  CharacterString 0..1';


--
-- TOC entry 13049 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.refbeschreibung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.refbeschreibung IS 'refBeschreibung DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13050 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.refbegruendung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.refbegruendung IS 'refBegruendung DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13051 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.refscan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.refscan IS 'refScan DataType XP_ExterneReferenz 1..*';


--
-- TOC entry 13052 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13053 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.reflegende; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13054 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.geltungsbereichaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.geltungsbereichaenderung IS 'geltungsbereichAenderung Union XP_Flaechengeometrie 0..1';


--
-- TOC entry 13055 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.besonderheit; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.besonderheit IS 'besonderheit  CharacterString 0..1';


--
-- TOC entry 13056 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.user_id IS 'user_id  integer ';


--
-- TOC entry 13057 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13058 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13059 (class 0 OID 0)
-- Dependencies: 1019
-- Name: COLUMN xp_rasterplanaenderung.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanaenderung.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 1020 (class 1259 OID 897394)
-- Name: rp_rasterplanaenderung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_rasterplanaenderung (
    aufstellungsbeschlussdatum date,
    auslegungstartdatum date,
    auslegungenddatum date,
    traegerbeteiligungsstartdatum date,
    traegerbeteiligungsenddatum date,
    aenderungenbisdatum date,
    entwurfsbeschlussdatum date,
    satzungsbeschlussdatum date,
    datumdesinkrafttretens date,
    inverszu_rasteraenderung_rp_bereich text[]
)
INHERITS (xp_rasterplanaenderung);


--
-- TOC entry 13060 (class 0 OID 0)
-- Dependencies: 1020
-- Name: TABLE rp_rasterplanaenderung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_rasterplanaenderung IS 'FeatureType: "RP_RasterplanAenderung"';


--
-- TOC entry 13061 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.aufstellungsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.aufstellungsbeschlussdatum IS 'aufstellungsbeschlussDatum  Date 0..1';


--
-- TOC entry 13062 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.auslegungstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.auslegungstartdatum IS 'auslegungStartDatum  Date 0..1';


--
-- TOC entry 13063 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.auslegungenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.auslegungenddatum IS 'auslegungEndDatum  Date 0..1';


--
-- TOC entry 13064 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.traegerbeteiligungsstartdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.traegerbeteiligungsstartdatum IS 'traegerbeteiligungsStartDatum  Date 0..1';


--
-- TOC entry 13065 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.traegerbeteiligungsenddatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.traegerbeteiligungsenddatum IS 'traegerbeteiligungsEndDatum  Date 0..1';


--
-- TOC entry 13066 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.aenderungenbisdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.aenderungenbisdatum IS 'aenderungenBisDatum  Date 0..1';


--
-- TOC entry 13067 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.entwurfsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.entwurfsbeschlussdatum IS 'entwurfsbeschlussDatum  Date 0..1';


--
-- TOC entry 13068 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.satzungsbeschlussdatum; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.satzungsbeschlussdatum IS 'satzungsbeschlussDatum  Date 0..1';


--
-- TOC entry 13069 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.datumdesinkrafttretens; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.datumdesinkrafttretens IS 'datumDesInkrafttretens  Date 0..1';


--
-- TOC entry 13070 (class 0 OID 0)
-- Dependencies: 1020
-- Name: COLUMN rp_rasterplanaenderung.inverszu_rasteraenderung_rp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rasterplanaenderung.inverszu_rasteraenderung_rp_bereich IS 'Assoziation zu: FeatureType RP_Bereich (rp_bereich) 0..*';


--
-- TOC entry 993 (class 1259 OID 897143)
-- Name: rp_raumkategorie; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_raumkategorie (
    besonderertyp rp_besondereraumkategorietypen,
    typ rp_raumkategorietypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13071 (class 0 OID 0)
-- Dependencies: 993
-- Name: TABLE rp_raumkategorie; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_raumkategorie IS 'FeatureType: "RP_Raumkategorie"';


--
-- TOC entry 13072 (class 0 OID 0)
-- Dependencies: 993
-- Name: COLUMN rp_raumkategorie.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_raumkategorie.besonderertyp IS 'besondererTyp enumeration RP_BesondereRaumkategorieTypen 0..1';


--
-- TOC entry 13073 (class 0 OID 0)
-- Dependencies: 993
-- Name: COLUMN rp_raumkategorie.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_raumkategorie.typ IS 'typ enumeration RP_RaumkategorieTypen 0..*';


--
-- TOC entry 973 (class 1259 OID 896963)
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
-- TOC entry 13074 (class 0 OID 0)
-- Dependencies: 973
-- Name: TABLE rp_rohstoff; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_rohstoff IS 'FeatureType: "RP_Rohstoff"';


--
-- TOC entry 13075 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.rohstofftyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.rohstofftyp IS 'rohstoffTyp enumeration RP_RohstoffTypen 0..*';


--
-- TOC entry 13076 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.folgenutzung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.folgenutzung IS 'folgenutzung enumeration RP_BergbauFolgenutzung 0..*';


--
-- TOC entry 13077 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.folgenutzungtext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.folgenutzungtext IS 'folgenutzungText  CharacterString 0..1';


--
-- TOC entry 13078 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.zeitstufe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.zeitstufe IS 'zeitstufe enumeration RP_Zeitstufen 0..1';


--
-- TOC entry 13079 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.zeitstufetext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.zeitstufetext IS 'zeitstufeText  CharacterString 0..1';


--
-- TOC entry 13080 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.tiefe; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.tiefe IS 'tiefe enumeration RP_BodenschatzTiefen 0..1';


--
-- TOC entry 13081 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.bergbauplanungtyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.bergbauplanungtyp IS 'bergbauplanungTyp enumeration RP_BergbauplanungTypen 0..*';


--
-- TOC entry 13082 (class 0 OID 0)
-- Dependencies: 973
-- Name: COLUMN rp_rohstoff.istaufschuettungablagerung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_rohstoff.istaufschuettungablagerung IS 'istAufschuettungAblagerung  Boolean 0..1';


--
-- TOC entry 986 (class 1259 OID 897080)
-- Name: rp_schienenverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_schienenverkehr (
    typ rp_schienenverkehrtypen[],
    besonderertyp rp_besondererschienenverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- TOC entry 13083 (class 0 OID 0)
-- Dependencies: 986
-- Name: TABLE rp_schienenverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_schienenverkehr IS 'FeatureType: "RP_Schienenverkehr"';


--
-- TOC entry 13084 (class 0 OID 0)
-- Dependencies: 986
-- Name: COLUMN rp_schienenverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_schienenverkehr.typ IS 'typ enumeration RP_SchienenverkehrTypen 0..*';


--
-- TOC entry 13085 (class 0 OID 0)
-- Dependencies: 986
-- Name: COLUMN rp_schienenverkehr.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_schienenverkehr.besonderertyp IS 'besondererTyp enumeration RP_BesondererSchienenverkehrTypen 0..*';


--
-- TOC entry 992 (class 1259 OID 897134)
-- Name: rp_sonstigeinfrastruktur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigeinfrastruktur (
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13086 (class 0 OID 0)
-- Dependencies: 992
-- Name: TABLE rp_sonstigeinfrastruktur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigeinfrastruktur IS 'FeatureType: "RP_SonstigeInfrastruktur"';


--
-- TOC entry 970 (class 1259 OID 896936)
-- Name: rp_sonstigerfreiraumschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigerfreiraumschutz (
)
INHERITS (rp_freiraum);


--
-- TOC entry 13087 (class 0 OID 0)
-- Dependencies: 970
-- Name: TABLE rp_sonstigerfreiraumschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigerfreiraumschutz IS 'FeatureType: "RP_SonstigerFreiraumschutz"';


--
-- TOC entry 999 (class 1259 OID 897197)
-- Name: rp_sonstigersiedlungsbereich; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstigersiedlungsbereich (
)
INHERITS (rp_siedlung);


--
-- TOC entry 13088 (class 0 OID 0)
-- Dependencies: 999
-- Name: TABLE rp_sonstigersiedlungsbereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstigersiedlungsbereich IS 'FeatureType: "RP_SonstigerSiedlungsbereich"';


--
-- TOC entry 989 (class 1259 OID 897107)
-- Name: rp_sonstverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sonstverkehr (
    typ rp_sonstverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- TOC entry 13089 (class 0 OID 0)
-- Dependencies: 989
-- Name: TABLE rp_sonstverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sonstverkehr IS 'FeatureType: "RP_SonstVerkehr"';


--
-- TOC entry 13090 (class 0 OID 0)
-- Dependencies: 989
-- Name: COLUMN rp_sonstverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sonstverkehr.typ IS 'typ enumeration RP_SonstVerkehrTypen 0..*';


--
-- TOC entry 990 (class 1259 OID 897116)
-- Name: rp_sozialeinfrastruktur; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sozialeinfrastruktur (
    typ rp_sozialeinfrastrukturtypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13091 (class 0 OID 0)
-- Dependencies: 990
-- Name: TABLE rp_sozialeinfrastruktur; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sozialeinfrastruktur IS 'FeatureType: "RP_SozialeInfrastruktur"';


--
-- TOC entry 13092 (class 0 OID 0)
-- Dependencies: 990
-- Name: COLUMN rp_sozialeinfrastruktur.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sozialeinfrastruktur.typ IS 'typ enumeration RP_SozialeInfrastrukturTypen 0..*';


--
-- TOC entry 996 (class 1259 OID 897170)
-- Name: rp_sperrgebiet; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sperrgebiet (
    typ rp_sperrgebiettypen
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13093 (class 0 OID 0)
-- Dependencies: 996
-- Name: TABLE rp_sperrgebiet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sperrgebiet IS 'FeatureType: "RP_Sperrgebiet"';


--
-- TOC entry 13094 (class 0 OID 0)
-- Dependencies: 996
-- Name: COLUMN rp_sperrgebiet.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sperrgebiet.typ IS 'typ enumeration RP_SperrgebietTypen 0..1';


--
-- TOC entry 966 (class 1259 OID 896900)
-- Name: rp_sportanlage; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_sportanlage (
    typ rp_sportanlagetypen
)
INHERITS (rp_freiraum);


--
-- TOC entry 13095 (class 0 OID 0)
-- Dependencies: 966
-- Name: TABLE rp_sportanlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_sportanlage IS 'FeatureType: "RP_Sportanlage"';


--
-- TOC entry 13096 (class 0 OID 0)
-- Dependencies: 966
-- Name: COLUMN rp_sportanlage.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_sportanlage.typ IS 'typ enumeration RP_SportanlageTypen 0..1';


--
-- TOC entry 988 (class 1259 OID 897098)
-- Name: rp_strassenverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_strassenverkehr (
    typ rp_strassenverkehrtypen[],
    besonderertyp rp_besondererstrassenverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- TOC entry 13097 (class 0 OID 0)
-- Dependencies: 988
-- Name: TABLE rp_strassenverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_strassenverkehr IS 'FeatureType: "RP_Strassenverkehr"';


--
-- TOC entry 13098 (class 0 OID 0)
-- Dependencies: 988
-- Name: COLUMN rp_strassenverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_strassenverkehr.typ IS 'typ enumeration RP_StrassenverkehrTypen 0..*';


--
-- TOC entry 13099 (class 0 OID 0)
-- Dependencies: 988
-- Name: COLUMN rp_strassenverkehr.besonderertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_strassenverkehr.besonderertyp IS 'besondererTyp enumeration RP_BesondererStrassenverkehrTypen 0..*';


--
-- TOC entry 956 (class 1259 OID 896804)
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
-- TOC entry 13100 (class 0 OID 0)
-- Dependencies: 956
-- Name: TABLE xp_textabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_textabschnitt IS 'FeatureType: "XP_TextAbschnitt"';


--
-- TOC entry 13101 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.schluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.schluessel IS 'schluessel  CharacterString 0..1';


--
-- TOC entry 13102 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.gesetzlichegrundlage; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.gesetzlichegrundlage IS 'gesetzlicheGrundlage  CharacterString 0..1';


--
-- TOC entry 13103 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.text IS 'text  CharacterString 0..1';


--
-- TOC entry 13104 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13105 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.user_id IS 'user_id  integer ';


--
-- TOC entry 13106 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13107 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13108 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 13109 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.inverszu_reftextinhalt_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_reftextinhalt_xp_objekt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..1';


--
-- TOC entry 13110 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.inverszu_texte_xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_texte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..1';


--
-- TOC entry 13111 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_baugebietsteilflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_baugebietsteilflaeche IS 'Assoziation zu: FeatureType BP_BaugebietsTeilFlaeche (bp_baugebietsteilflaeche) 0..*';


--
-- TOC entry 13112 (class 0 OID 0)
-- Dependencies: 956
-- Name: COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_textabschnitt.inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche IS 'Assoziation zu: FeatureType BP_NebenanlagenAusschlussFlaeche (bp_nebenanlagenausschlussflaeche) 0..*';


--
-- TOC entry 957 (class 1259 OID 896815)
-- Name: rp_textabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_textabschnitt (
    rechtscharakter rp_rechtscharakter NOT NULL
)
INHERITS (xp_textabschnitt);


--
-- TOC entry 13113 (class 0 OID 0)
-- Dependencies: 957
-- Name: TABLE rp_textabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_textabschnitt IS 'FeatureType: "RP_TextAbschnitt"';


--
-- TOC entry 13114 (class 0 OID 0)
-- Dependencies: 957
-- Name: COLUMN rp_textabschnitt.rechtscharakter; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_textabschnitt.rechtscharakter IS 'rechtscharakter enumeration RP_Rechtscharakter 1';


--
-- TOC entry 974 (class 1259 OID 896972)
-- Name: rp_wasserschutz; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserschutz (
    typ rp_wasserschutztypen,
    zone rp_wasserschutzzonen[]
)
INHERITS (rp_freiraum);


--
-- TOC entry 13115 (class 0 OID 0)
-- Dependencies: 974
-- Name: TABLE rp_wasserschutz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserschutz IS 'FeatureType: "RP_Wasserschutz"';


--
-- TOC entry 13116 (class 0 OID 0)
-- Dependencies: 974
-- Name: COLUMN rp_wasserschutz.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserschutz.typ IS 'typ enumeration RP_WasserschutzTypen 0..1';


--
-- TOC entry 13117 (class 0 OID 0)
-- Dependencies: 974
-- Name: COLUMN rp_wasserschutz.zone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserschutz.zone IS 'zone enumeration RP_WasserschutzZonen 0..*';


--
-- TOC entry 985 (class 1259 OID 897071)
-- Name: rp_wasserverkehr; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserverkehr (
    typ rp_wasserverkehrtypen[]
)
INHERITS (rp_verkehr);


--
-- TOC entry 13118 (class 0 OID 0)
-- Dependencies: 985
-- Name: TABLE rp_wasserverkehr; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserverkehr IS 'FeatureType: "RP_Wasserverkehr"';


--
-- TOC entry 13119 (class 0 OID 0)
-- Dependencies: 985
-- Name: COLUMN rp_wasserverkehr.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserverkehr.typ IS 'typ enumeration RP_WasserverkehrTypen 0..*';


--
-- TOC entry 980 (class 1259 OID 897026)
-- Name: rp_wasserwirtschaft; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wasserwirtschaft (
    typ rp_wasserwirtschafttypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13120 (class 0 OID 0)
-- Dependencies: 980
-- Name: TABLE rp_wasserwirtschaft; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wasserwirtschaft IS 'FeatureType: "RP_Wasserwirtschaft"';


--
-- TOC entry 13121 (class 0 OID 0)
-- Dependencies: 980
-- Name: COLUMN rp_wasserwirtschaft.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wasserwirtschaft.typ IS 'typ enumeration RP_WasserwirtschaftTypen 0..*';


--
-- TOC entry 998 (class 1259 OID 897188)
-- Name: rp_wohnensiedlung; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_wohnensiedlung (
    typ rp_wohnensiedlungtypen[]
)
INHERITS (rp_siedlung);


--
-- TOC entry 13122 (class 0 OID 0)
-- Dependencies: 998
-- Name: TABLE rp_wohnensiedlung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_wohnensiedlung IS 'FeatureType: "RP_WohnenSiedlung"';


--
-- TOC entry 13123 (class 0 OID 0)
-- Dependencies: 998
-- Name: COLUMN rp_wohnensiedlung.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_wohnensiedlung.typ IS 'typ enumeration RP_WohnenSiedlungTypen 0..*';


--
-- TOC entry 1002 (class 1259 OID 897224)
-- Name: rp_zentralerort; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE rp_zentralerort (
    typ rp_zentralerorttypen[] NOT NULL,
    sonstigertyp rp_zentralerortsonstigetypen[]
)
INHERITS (rp_geometrieobjekt);


--
-- TOC entry 13124 (class 0 OID 0)
-- Dependencies: 1002
-- Name: TABLE rp_zentralerort; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE rp_zentralerort IS 'FeatureType: "RP_ZentralerOrt"';


--
-- TOC entry 13125 (class 0 OID 0)
-- Dependencies: 1002
-- Name: COLUMN rp_zentralerort.typ; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_zentralerort.typ IS 'typ enumeration RP_ZentralerOrtTypen 1..*';


--
-- TOC entry 13126 (class 0 OID 0)
-- Dependencies: 1002
-- Name: COLUMN rp_zentralerort.sonstigertyp; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN rp_zentralerort.sonstigertyp IS 'sonstigerTyp enumeration RP_ZentralerOrtSonstigeTypen 0..*';


--
-- TOC entry 929 (class 1259 OID 896698)
-- Name: xp_stylesheetliste; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_stylesheetliste (
    codespace text,
    id character varying NOT NULL
);


--
-- TOC entry 13127 (class 0 OID 0)
-- Dependencies: 929
-- Name: TABLE xp_stylesheetliste; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_stylesheetliste IS 'Alias: "XP_StylesheetListe", UML-Typ: Code Liste';


--
-- TOC entry 13128 (class 0 OID 0)
-- Dependencies: 929
-- Name: COLUMN xp_stylesheetliste.codespace; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stylesheetliste.codespace IS 'codeSpace  text ';


--
-- TOC entry 13129 (class 0 OID 0)
-- Dependencies: 929
-- Name: COLUMN xp_stylesheetliste.id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_stylesheetliste.id IS 'id  character varying ';


--
-- TOC entry 1010 (class 1259 OID 897300)
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
-- TOC entry 13130 (class 0 OID 0)
-- Dependencies: 1010
-- Name: TABLE xp_abstraktespraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_abstraktespraesentationsobjekt IS 'FeatureType: "XP_AbstraktesPraesentationsobjekt"';


--
-- TOC entry 13131 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.stylesheetid; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.stylesheetid IS 'stylesheetId CodeList XP_StylesheetListe 0..1';


--
-- TOC entry 13132 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.darstellungsprioritaet; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.darstellungsprioritaet IS 'darstellungsprioritaet  Integer 0..1';


--
-- TOC entry 13133 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.art; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.art IS 'art  CharacterString 0..*';


--
-- TOC entry 13134 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.index; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.index IS 'index  Integer 0..*';


--
-- TOC entry 13135 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.user_id IS 'user_id  integer ';


--
-- TOC entry 13136 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13137 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13138 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 13139 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.dientzurdarstellungvon; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.dientzurdarstellungvon IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- TOC entry 13140 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.gehoertzubereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.gehoertzubereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..1';


--
-- TOC entry 13141 (class 0 OID 0)
-- Dependencies: 1010
-- Name: COLUMN xp_abstraktespraesentationsobjekt.inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_abstraktespraesentationsobjekt.inverszu_gehoertzupraesentationsobjekt_rp_legendenobjekt IS 'Assoziation zu: FeatureType RP_Legendenobjekt (rp_legendenobjekt) 0..1';


--
-- TOC entry 958 (class 1259 OID 896824)
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
-- TOC entry 13142 (class 0 OID 0)
-- Dependencies: 958
-- Name: TABLE xp_begruendungabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_begruendungabschnitt IS 'FeatureType: "XP_BegruendungAbschnitt"';


--
-- TOC entry 13143 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.schluessel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.schluessel IS 'schluessel  CharacterString 0..1';


--
-- TOC entry 13144 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.text; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.text IS 'text  CharacterString 0..1';


--
-- TOC entry 13145 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13146 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.user_id IS 'user_id  integer ';


--
-- TOC entry 13147 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13148 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13149 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 13150 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.inverszu_refbegruendunginhalt_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_refbegruendunginhalt_xp_objekt IS 'Assoziation zu: FeatureType XP_Objekt (xp_objekt) 0..*';


--
-- TOC entry 13151 (class 0 OID 0)
-- Dependencies: 958
-- Name: COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_begruendungabschnitt.inverszu_begruendungstexte_xp_plan IS 'Assoziation zu: FeatureType XP_Plan (xp_plan) 0..*';


SET default_with_oids = false;

--
-- TOC entry 1025 (class 1259 OID 897435)
-- Name: xp_bereich_zu_xp_objekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_bereich_zu_xp_objekt (
    xp_bereich_gml_id uuid NOT NULL,
    xp_objekt_gml_id uuid NOT NULL
);


--
-- TOC entry 13152 (class 0 OID 0)
-- Dependencies: 1025
-- Name: TABLE xp_bereich_zu_xp_objekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_bereich_zu_xp_objekt IS 'Association XP_Bereich _zu_ XP_Objekt';


--
-- TOC entry 13153 (class 0 OID 0)
-- Dependencies: 1025
-- Name: COLUMN xp_bereich_zu_xp_objekt.xp_bereich_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich_zu_xp_objekt.xp_bereich_gml_id IS 'planinhalt';


--
-- TOC entry 13154 (class 0 OID 0)
-- Dependencies: 1025
-- Name: COLUMN xp_bereich_zu_xp_objekt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_bereich_zu_xp_objekt.xp_objekt_gml_id IS 'gehoertZuBereich';


SET default_with_oids = true;

--
-- TOC entry 1018 (class 1259 OID 897374)
-- Name: xp_fpo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_fpo (
    "position" public.geometry(MultiPolygon) NOT NULL
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- TOC entry 13155 (class 0 OID 0)
-- Dependencies: 1018
-- Name: TABLE xp_fpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_fpo IS 'FeatureType: "XP_FPO"';


--
-- TOC entry 13156 (class 0 OID 0)
-- Dependencies: 1018
-- Name: COLUMN xp_fpo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_fpo."position" IS 'position Union XP_Flaechengeometrie 1';


--
-- TOC entry 1013 (class 1259 OID 897329)
-- Name: xp_lpo; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_lpo (
    "position" public.geometry(MultiLineString) NOT NULL,
    inverszu_hat_xp_ppo text[],
    inverszu_hat_xp_tpo text[]
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- TOC entry 13157 (class 0 OID 0)
-- Dependencies: 1013
-- Name: TABLE xp_lpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_lpo IS 'FeatureType: "XP_LPO"';


--
-- TOC entry 13158 (class 0 OID 0)
-- Dependencies: 1013
-- Name: COLUMN xp_lpo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo."position" IS 'position Union XP_Liniengeometrie 1';


--
-- TOC entry 13159 (class 0 OID 0)
-- Dependencies: 1013
-- Name: COLUMN xp_lpo.inverszu_hat_xp_ppo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_ppo IS 'Assoziation zu: FeatureType XP_PPO (xp_ppo) 0..*';


--
-- TOC entry 13160 (class 0 OID 0)
-- Dependencies: 1013
-- Name: COLUMN xp_lpo.inverszu_hat_xp_tpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lpo.inverszu_hat_xp_tpo IS 'Assoziation zu: FeatureType XP_TPO (xp_tpo) 0..*';


--
-- TOC entry 1014 (class 1259 OID 897338)
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
-- TOC entry 13161 (class 0 OID 0)
-- Dependencies: 1014
-- Name: TABLE xp_tpo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_tpo IS 'FeatureType: "XP_TPO"';


--
-- TOC entry 13162 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.schriftinhalt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.schriftinhalt IS 'schriftinhalt  CharacterString 0..1';


--
-- TOC entry 13163 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.fontsperrung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.fontsperrung IS 'fontSperrung  Decimal 0..1';


--
-- TOC entry 13164 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.skalierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.skalierung IS 'skalierung  Decimal 0..1';


--
-- TOC entry 13165 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.horizontaleausrichtung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.horizontaleausrichtung IS 'horizontaleAusrichtung enumeration XP_HorizontaleAusrichtung 0..1';


--
-- TOC entry 13166 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.vertikaleausrichtung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.vertikaleausrichtung IS 'vertikaleAusrichtung enumeration XP_VertikaleAusrichtung 0..1';


--
-- TOC entry 13167 (class 0 OID 0)
-- Dependencies: 1014
-- Name: COLUMN xp_tpo.hat; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_tpo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';


--
-- TOC entry 1015 (class 1259 OID 897347)
-- Name: xp_lto; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_lto (
    "position" public.geometry(MultiLineString) NOT NULL
)
INHERITS (xp_tpo);


--
-- TOC entry 13168 (class 0 OID 0)
-- Dependencies: 1015
-- Name: TABLE xp_lto; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_lto IS 'FeatureType: "XP_LTO"';


--
-- TOC entry 13169 (class 0 OID 0)
-- Dependencies: 1015
-- Name: COLUMN xp_lto."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_lto."position" IS 'position Union XP_Liniengeometrie 1';


--
-- TOC entry 1016 (class 1259 OID 897356)
-- Name: xp_pto; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_pto (
    "position" public.geometry(MultiPoint) NOT NULL,
    drehwinkel double precision
)
INHERITS (xp_tpo);


--
-- TOC entry 13170 (class 0 OID 0)
-- Dependencies: 1016
-- Name: TABLE xp_pto; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_pto IS 'FeatureType: "XP_PTO"';


--
-- TOC entry 13171 (class 0 OID 0)
-- Dependencies: 1016
-- Name: COLUMN xp_pto."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_pto."position" IS 'position Union XP_Punktgeometrie 1';


--
-- TOC entry 13172 (class 0 OID 0)
-- Dependencies: 1016
-- Name: COLUMN xp_pto.drehwinkel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_pto.drehwinkel IS 'drehwinkel  Angle 0..1';


--
-- TOC entry 1017 (class 1259 OID 897365)
-- Name: xp_nutzungsschablone; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_nutzungsschablone (
    spaltenanz integer NOT NULL,
    zeilenanz integer NOT NULL
)
INHERITS (xp_pto);


--
-- TOC entry 13173 (class 0 OID 0)
-- Dependencies: 1017
-- Name: TABLE xp_nutzungsschablone; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_nutzungsschablone IS 'FeatureType: "XP_Nutzungsschablone"';


--
-- TOC entry 13174 (class 0 OID 0)
-- Dependencies: 1017
-- Name: COLUMN xp_nutzungsschablone.spaltenanz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_nutzungsschablone.spaltenanz IS 'spaltenAnz  Integer 1';


--
-- TOC entry 13175 (class 0 OID 0)
-- Dependencies: 1017
-- Name: COLUMN xp_nutzungsschablone.zeilenanz; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_nutzungsschablone.zeilenanz IS 'zeilenAnz  Integer 1';


SET default_with_oids = false;

--
-- TOC entry 1026 (class 1259 OID 897440)
-- Name: xp_objekt_zu_xp_abstraktespraesentationsobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt (
    xp_objekt_gml_id uuid NOT NULL,
    xp_abstraktespraesentationsobjekt_gml_id uuid NOT NULL
);


--
-- TOC entry 13176 (class 0 OID 0)
-- Dependencies: 1026
-- Name: TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt_zu_xp_abstraktespraesentationsobjekt IS 'Association XP_Objekt _zu_ XP_AbstraktesPraesentationsobjekt';


--
-- TOC entry 13177 (class 0 OID 0)
-- Dependencies: 1026
-- Name: COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_objekt_gml_id IS 'wirdDargestelltDurch';


--
-- TOC entry 13178 (class 0 OID 0)
-- Dependencies: 1026
-- Name: COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_abstraktespraesentationsobjekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_abstraktespraesentationsobjekt.xp_abstraktespraesentationsobjekt_gml_id IS 'dientZurDarstellungVon';


--
-- TOC entry 1024 (class 1259 OID 897430)
-- Name: xp_objekt_zu_xp_begruendungabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_objekt_zu_xp_begruendungabschnitt (
    xp_objekt_gml_id uuid NOT NULL,
    xp_begruendungabschnitt_gml_id uuid NOT NULL
);


--
-- TOC entry 13179 (class 0 OID 0)
-- Dependencies: 1024
-- Name: TABLE xp_objekt_zu_xp_begruendungabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_objekt_zu_xp_begruendungabschnitt IS 'Association XP_Objekt _zu_ XP_BegruendungAbschnitt';


--
-- TOC entry 13180 (class 0 OID 0)
-- Dependencies: 1024
-- Name: COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_objekt_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_objekt_zu_xp_begruendungabschnitt.xp_objekt_gml_id IS 'refBegruendungInhalt';


--
-- TOC entry 1023 (class 1259 OID 897425)
-- Name: xp_plan_zu_xp_begruendungabschnitt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_plan_zu_xp_begruendungabschnitt (
    xp_plan_gml_id uuid NOT NULL,
    xp_begruendungabschnitt_gml_id uuid NOT NULL
);


--
-- TOC entry 13181 (class 0 OID 0)
-- Dependencies: 1023
-- Name: TABLE xp_plan_zu_xp_begruendungabschnitt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_plan_zu_xp_begruendungabschnitt IS 'Association XP_Plan _zu_ XP_BegruendungAbschnitt';


--
-- TOC entry 13182 (class 0 OID 0)
-- Dependencies: 1023
-- Name: COLUMN xp_plan_zu_xp_begruendungabschnitt.xp_plan_gml_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_plan_zu_xp_begruendungabschnitt.xp_plan_gml_id IS 'begruendungsTexte';


SET default_with_oids = true;

--
-- TOC entry 1012 (class 1259 OID 897320)
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
-- TOC entry 13183 (class 0 OID 0)
-- Dependencies: 1012
-- Name: TABLE xp_ppo; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_ppo IS 'FeatureType: "XP_PPO"';


--
-- TOC entry 13184 (class 0 OID 0)
-- Dependencies: 1012
-- Name: COLUMN xp_ppo."position"; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo."position" IS 'position Union XP_Punktgeometrie 1';


--
-- TOC entry 13185 (class 0 OID 0)
-- Dependencies: 1012
-- Name: COLUMN xp_ppo.drehwinkel; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.drehwinkel IS 'drehwinkel  Angle 0..1';


--
-- TOC entry 13186 (class 0 OID 0)
-- Dependencies: 1012
-- Name: COLUMN xp_ppo.skalierung; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.skalierung IS 'skalierung  Decimal 0..1';


--
-- TOC entry 13187 (class 0 OID 0)
-- Dependencies: 1012
-- Name: COLUMN xp_ppo.hat; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_ppo.hat IS 'Assoziation zu: FeatureType XP_LPO (xp_lpo) 0..1';


--
-- TOC entry 1011 (class 1259 OID 897311)
-- Name: xp_praesentationsobjekt; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_praesentationsobjekt (
)
INHERITS (xp_abstraktespraesentationsobjekt);


--
-- TOC entry 13188 (class 0 OID 0)
-- Dependencies: 1011
-- Name: TABLE xp_praesentationsobjekt; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_praesentationsobjekt IS 'FeatureType: "XP_Praesentationsobjekt"';


--
-- TOC entry 1021 (class 1259 OID 897403)
-- Name: xp_rasterplanbasis; Type: TABLE; Schema: xplan_gml; Owner: -; Tablespace: 
--

CREATE TABLE xp_rasterplanbasis (
    gml_id uuid DEFAULT public.uuid_generate_v1mc() NOT NULL,
    refscan xp_externereferenz[] NOT NULL,
    reftext xp_externereferenz,
    reflegende xp_externereferenz[],
    user_id integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    konvertierung_id integer,
    inverszu_rasterbasis_xp_bereich text[]
);


--
-- TOC entry 13189 (class 0 OID 0)
-- Dependencies: 1021
-- Name: TABLE xp_rasterplanbasis; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON TABLE xp_rasterplanbasis IS 'FeatureType: "XP_RasterplanBasis"';


--
-- TOC entry 13190 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.refscan; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.refscan IS 'refScan DataType XP_ExterneReferenz 1..*';


--
-- TOC entry 13191 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.reftext; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.reftext IS 'refText DataType XP_ExterneReferenz 0..1';


--
-- TOC entry 13192 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.reflegende; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';


--
-- TOC entry 13193 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.user_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.user_id IS 'user_id  integer ';


--
-- TOC entry 13194 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.created_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.created_at IS 'created_at  timestamp without time zone ';


--
-- TOC entry 13195 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.updated_at; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.updated_at IS 'updated_at  timestamp without time zone ';


--
-- TOC entry 13196 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.konvertierung_id; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.konvertierung_id IS 'konvertierung_id  integer ';


--
-- TOC entry 13197 (class 0 OID 0)
-- Dependencies: 1021
-- Name: COLUMN xp_rasterplanbasis.inverszu_rasterbasis_xp_bereich; Type: COMMENT; Schema: xplan_gml; Owner: -
--

COMMENT ON COLUMN xp_rasterplanbasis.inverszu_rasterbasis_xp_bereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..*';


--
-- TOC entry 12241 (class 2604 OID 897164)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12242 (class 2604 OID 897165)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12243 (class 2604 OID 897166)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_achse ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12277 (class 2604 OID 897274)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12278 (class 2604 OID 897275)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12279 (class 2604 OID 897276)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bereich ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12190 (class 2604 OID 897011)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12191 (class 2604 OID 897012)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12192 (class 2604 OID 897013)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_bodenschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12256 (class 2604 OID 897209)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12257 (class 2604 OID 897210)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12258 (class 2604 OID 897211)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_einzelhandel ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12199 (class 2604 OID 897038)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12200 (class 2604 OID 897039)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12201 (class 2604 OID 897040)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_energieversorgung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12229 (class 2604 OID 897128)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12230 (class 2604 OID 897129)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12231 (class 2604 OID 897130)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_entsorgung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12172 (class 2604 OID 896957)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12173 (class 2604 OID 896958)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12174 (class 2604 OID 896959)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erholung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12193 (class 2604 OID 897020)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12194 (class 2604 OID 897021)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12195 (class 2604 OID 897022)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_erneuerbareenergie ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12163 (class 2604 OID 896930)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12164 (class 2604 OID 896931)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12165 (class 2604 OID 896932)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_forstwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12142 (class 2604 OID 896867)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12143 (class 2604 OID 896868)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12144 (class 2604 OID 896869)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_freiraum ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12238 (class 2604 OID 897155)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12239 (class 2604 OID 897156)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12240 (class 2604 OID 897157)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_funktionszuweisung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12265 (class 2604 OID 897236)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12266 (class 2604 OID 897237)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12267 (class 2604 OID 897238)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_generischesobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12139 (class 2604 OID 896858)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12140 (class 2604 OID 896859)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12141 (class 2604 OID 896860)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_geometrieobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12157 (class 2604 OID 896912)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12158 (class 2604 OID 896913)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12159 (class 2604 OID 896914)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gewaesser ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12271 (class 2604 OID 897254)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12272 (class 2604 OID 897255)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12273 (class 2604 OID 897256)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_grenze ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12169 (class 2604 OID 896948)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12170 (class 2604 OID 896949)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12171 (class 2604 OID 896950)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_gruenzuggruenzaesur ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12145 (class 2604 OID 896876)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12146 (class 2604 OID 896877)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12147 (class 2604 OID 896878)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_hochwasserschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12259 (class 2604 OID 897218)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12260 (class 2604 OID 897219)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12261 (class 2604 OID 897220)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_industriegewerbe ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12187 (class 2604 OID 897002)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12188 (class 2604 OID 897003)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12189 (class 2604 OID 897004)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_klimaschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12205 (class 2604 OID 897056)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12206 (class 2604 OID 897057)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12207 (class 2604 OID 897058)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kommunikation ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12151 (class 2604 OID 896894)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12152 (class 2604 OID 896895)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12153 (class 2604 OID 896896)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_kulturlandschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12202 (class 2604 OID 897047)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12203 (class 2604 OID 897048)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12204 (class 2604 OID 897049)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_laermschutzbauschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12181 (class 2604 OID 896984)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12182 (class 2604 OID 896985)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12183 (class 2604 OID 896986)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_landwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12217 (class 2604 OID 897092)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12218 (class 2604 OID 897093)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12219 (class 2604 OID 897094)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_luftverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12148 (class 2604 OID 896885)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12149 (class 2604 OID 896886)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12150 (class 2604 OID 896887)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturlandschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12160 (class 2604 OID 896921)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12161 (class 2604 OID 896922)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12162 (class 2604 OID 896923)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_naturschutzrechtlichesschutzgebiet ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12136 (class 2604 OID 896849)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12137 (class 2604 OID 896850)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12138 (class 2604 OID 896851)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_objekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12283 (class 2604 OID 897294)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12284 (class 2604 OID 897295)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12285 (class 2604 OID 897296)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_plan ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12268 (class 2604 OID 897245)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12269 (class 2604 OID 897246)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12270 (class 2604 OID 897247)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_planungsraum ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12184 (class 2604 OID 896993)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12185 (class 2604 OID 896994)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12186 (class 2604 OID 896995)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_radwegwanderweg ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12316 (class 2604 OID 897397)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rasterplanaenderung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12317 (class 2604 OID 897398)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rasterplanaenderung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12318 (class 2604 OID 897399)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rasterplanaenderung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12235 (class 2604 OID 897146)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12236 (class 2604 OID 897147)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12237 (class 2604 OID 897148)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_raumkategorie ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12175 (class 2604 OID 896966)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12176 (class 2604 OID 896967)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12177 (class 2604 OID 896968)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_rohstoff ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12214 (class 2604 OID 897083)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12215 (class 2604 OID 897084)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12216 (class 2604 OID 897085)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_schienenverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12247 (class 2604 OID 897182)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12248 (class 2604 OID 897183)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12249 (class 2604 OID 897184)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_siedlung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12232 (class 2604 OID 897137)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12233 (class 2604 OID 897138)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12234 (class 2604 OID 897139)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigeinfrastruktur ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12166 (class 2604 OID 896939)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12167 (class 2604 OID 896940)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12168 (class 2604 OID 896941)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigerfreiraumschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12253 (class 2604 OID 897200)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12254 (class 2604 OID 897201)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12255 (class 2604 OID 897202)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstigersiedlungsbereich ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12223 (class 2604 OID 897110)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12224 (class 2604 OID 897111)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12225 (class 2604 OID 897112)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sonstverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12226 (class 2604 OID 897119)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12227 (class 2604 OID 897120)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12228 (class 2604 OID 897121)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sozialeinfrastruktur ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12244 (class 2604 OID 897173)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12245 (class 2604 OID 897174)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12246 (class 2604 OID 897175)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sperrgebiet ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12154 (class 2604 OID 896903)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12155 (class 2604 OID 896904)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12156 (class 2604 OID 896905)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_sportanlage ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12220 (class 2604 OID 897101)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12221 (class 2604 OID 897102)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12222 (class 2604 OID 897103)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_strassenverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12127 (class 2604 OID 896818)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12128 (class 2604 OID 896819)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12129 (class 2604 OID 896820)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_textabschnitt ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12208 (class 2604 OID 897065)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12209 (class 2604 OID 897066)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12210 (class 2604 OID 897067)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_verkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12178 (class 2604 OID 896975)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12179 (class 2604 OID 896976)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12180 (class 2604 OID 896977)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserschutz ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12211 (class 2604 OID 897074)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12212 (class 2604 OID 897075)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12213 (class 2604 OID 897076)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserverkehr ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12196 (class 2604 OID 897029)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12197 (class 2604 OID 897030)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12198 (class 2604 OID 897031)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wasserwirtschaft ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12250 (class 2604 OID 897191)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12251 (class 2604 OID 897192)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12252 (class 2604 OID 897193)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_wohnensiedlung ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12262 (class 2604 OID 897227)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12263 (class 2604 OID 897228)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12264 (class 2604 OID 897229)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY rp_zentralerort ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12310 (class 2604 OID 897377)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12311 (class 2604 OID 897378)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12312 (class 2604 OID 897379)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_fpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12295 (class 2604 OID 897332)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12296 (class 2604 OID 897333)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12297 (class 2604 OID 897334)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12301 (class 2604 OID 897350)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12302 (class 2604 OID 897351)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12303 (class 2604 OID 897352)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_lto ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12307 (class 2604 OID 897368)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12308 (class 2604 OID 897369)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12309 (class 2604 OID 897370)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_nutzungsschablone ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12292 (class 2604 OID 897323)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12293 (class 2604 OID 897324)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12294 (class 2604 OID 897325)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_ppo ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12289 (class 2604 OID 897314)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12290 (class 2604 OID 897315)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12291 (class 2604 OID 897316)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_praesentationsobjekt ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12304 (class 2604 OID 897359)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12305 (class 2604 OID 897360)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12306 (class 2604 OID 897361)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_pto ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12298 (class 2604 OID 897341)
-- Name: gml_id; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN gml_id SET DEFAULT public.uuid_generate_v1mc();


--
-- TOC entry 12299 (class 2604 OID 897342)
-- Name: created_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 12300 (class 2604 OID 897343)
-- Name: updated_at; Type: DEFAULT; Schema: xplan_gml; Owner: -
--

ALTER TABLE ONLY xp_tpo ALTER COLUMN updated_at SET DEFAULT now();


--
-- TOC entry 12444 (class 2606 OID 895528)
-- Name: enum_rp_abfallentsorgungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abfallentsorgungtypen
    ADD CONSTRAINT enum_rp_abfallentsorgungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12464 (class 2606 OID 895920)
-- Name: enum_rp_abfalltypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abfalltypen
    ADD CONSTRAINT enum_rp_abfalltypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12460 (class 2606 OID 895864)
-- Name: enum_rp_abwassertypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_abwassertypen
    ADD CONSTRAINT enum_rp_abwassertypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12478 (class 2606 OID 896186)
-- Name: enum_rp_achsentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_achsentypen
    ADD CONSTRAINT enum_rp_achsentypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12388 (class 2606 OID 894424)
-- Name: enum_rp_art_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_art
    ADD CONSTRAINT enum_rp_art_pkey PRIMARY KEY (wert);


--
-- TOC entry 12386 (class 2606 OID 894388)
-- Name: enum_rp_bedeutsamkeit_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bedeutsamkeit
    ADD CONSTRAINT enum_rp_bedeutsamkeit_pkey PRIMARY KEY (wert);


--
-- TOC entry 12398 (class 2606 OID 894612)
-- Name: enum_rp_bergbaufolgenutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bergbaufolgenutzung
    ADD CONSTRAINT enum_rp_bergbaufolgenutzung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12418 (class 2606 OID 895084)
-- Name: enum_rp_bergbauplanungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bergbauplanungtypen
    ADD CONSTRAINT enum_rp_bergbauplanungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12490 (class 2606 OID 896482)
-- Name: enum_rp_besondereraumkategorietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondereraumkategorietypen
    ADD CONSTRAINT enum_rp_besondereraumkategorietypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12442 (class 2606 OID 895482)
-- Name: enum_rp_besondererschienenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondererschienenverkehrtypen
    ADD CONSTRAINT enum_rp_besondererschienenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12462 (class 2606 OID 895892)
-- Name: enum_rp_besondererstrassenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besondererstrassenverkehrtypen
    ADD CONSTRAINT enum_rp_besondererstrassenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12432 (class 2606 OID 895346)
-- Name: enum_rp_besonderetourismuserholungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_besonderetourismuserholungtypen
    ADD CONSTRAINT enum_rp_besonderetourismuserholungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12428 (class 2606 OID 895288)
-- Name: enum_rp_bodenschatztiefen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bodenschatztiefen
    ADD CONSTRAINT enum_rp_bodenschatztiefen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12422 (class 2606 OID 895128)
-- Name: enum_rp_bodenschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_bodenschutztypen
    ADD CONSTRAINT enum_rp_bodenschutztypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12482 (class 2606 OID 896292)
-- Name: enum_rp_einzelhandeltypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_einzelhandeltypen
    ADD CONSTRAINT enum_rp_einzelhandeltypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12454 (class 2606 OID 895734)
-- Name: enum_rp_energieversorgungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_energieversorgungtypen
    ADD CONSTRAINT enum_rp_energieversorgungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12404 (class 2606 OID 894706)
-- Name: enum_rp_erholungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_erholungtypen
    ADD CONSTRAINT enum_rp_erholungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12402 (class 2606 OID 894676)
-- Name: enum_rp_erneuerbareenergietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_erneuerbareenergietypen
    ADD CONSTRAINT enum_rp_erneuerbareenergietypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12430 (class 2606 OID 895320)
-- Name: enum_rp_forstwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_forstwirtschafttypen
    ADD CONSTRAINT enum_rp_forstwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12484 (class 2606 OID 896330)
-- Name: enum_rp_funktionszuweisungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_funktionszuweisungtypen
    ADD CONSTRAINT enum_rp_funktionszuweisungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12390 (class 2606 OID 894468)
-- Name: enum_rp_gebietstyp_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_gebietstyp
    ADD CONSTRAINT enum_rp_gebietstyp_pkey PRIMARY KEY (wert);


--
-- TOC entry 12424 (class 2606 OID 895176)
-- Name: enum_rp_hochwasserschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_hochwasserschutztypen
    ADD CONSTRAINT enum_rp_hochwasserschutztypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12494 (class 2606 OID 896564)
-- Name: enum_rp_industriegewerbetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_industriegewerbetypen
    ADD CONSTRAINT enum_rp_industriegewerbetypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12470 (class 2606 OID 896036)
-- Name: enum_rp_kommunikationtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_kommunikationtypen
    ADD CONSTRAINT enum_rp_kommunikationtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12414 (class 2606 OID 895030)
-- Name: enum_rp_kulturlandschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_kulturlandschafttypen
    ADD CONSTRAINT enum_rp_kulturlandschafttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12468 (class 2606 OID 896010)
-- Name: enum_rp_laermschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_laermschutztypen
    ADD CONSTRAINT enum_rp_laermschutztypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12412 (class 2606 OID 895002)
-- Name: enum_rp_landwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_landwirtschafttypen
    ADD CONSTRAINT enum_rp_landwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12416 (class 2606 OID 895050)
-- Name: enum_rp_lufttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_lufttypen
    ADD CONSTRAINT enum_rp_lufttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12466 (class 2606 OID 895970)
-- Name: enum_rp_luftverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_luftverkehrtypen
    ADD CONSTRAINT enum_rp_luftverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12426 (class 2606 OID 895250)
-- Name: enum_rp_naturlandschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_naturlandschafttypen
    ADD CONSTRAINT enum_rp_naturlandschafttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12456 (class 2606 OID 895782)
-- Name: enum_rp_primaerenergietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_primaerenergietypen
    ADD CONSTRAINT enum_rp_primaerenergietypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12410 (class 2606 OID 894968)
-- Name: enum_rp_radwegwanderwegtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_radwegwanderwegtypen
    ADD CONSTRAINT enum_rp_radwegwanderwegtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12488 (class 2606 OID 896438)
-- Name: enum_rp_raumkategorietypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_raumkategorietypen
    ADD CONSTRAINT enum_rp_raumkategorietypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12396 (class 2606 OID 894574)
-- Name: enum_rp_rechtscharakter_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rechtscharakter
    ADD CONSTRAINT enum_rp_rechtscharakter_pkey PRIMARY KEY (wert);


--
-- TOC entry 12392 (class 2606 OID 894512)
-- Name: enum_rp_rechtsstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rechtsstand
    ADD CONSTRAINT enum_rp_rechtsstand_pkey PRIMARY KEY (wert);


--
-- TOC entry 12406 (class 2606 OID 894844)
-- Name: enum_rp_rohstofftypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_rohstofftypen
    ADD CONSTRAINT enum_rp_rohstofftypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12474 (class 2606 OID 896116)
-- Name: enum_rp_schienenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_schienenverkehrtypen
    ADD CONSTRAINT enum_rp_schienenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12458 (class 2606 OID 895828)
-- Name: enum_rp_sonstverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sonstverkehrtypen
    ADD CONSTRAINT enum_rp_sonstverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12450 (class 2606 OID 895634)
-- Name: enum_rp_sozialeinfrastrukturtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sozialeinfrastrukturtypen
    ADD CONSTRAINT enum_rp_sozialeinfrastrukturtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12448 (class 2606 OID 895604)
-- Name: enum_rp_spannungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_spannungtypen
    ADD CONSTRAINT enum_rp_spannungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12486 (class 2606 OID 896364)
-- Name: enum_rp_sperrgebiettypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sperrgebiettypen
    ADD CONSTRAINT enum_rp_sperrgebiettypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12498 (class 2606 OID 896664)
-- Name: enum_rp_spezifischegrenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_spezifischegrenzetypen
    ADD CONSTRAINT enum_rp_spezifischegrenzetypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12400 (class 2606 OID 894648)
-- Name: enum_rp_sportanlagetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_sportanlagetypen
    ADD CONSTRAINT enum_rp_sportanlagetypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12452 (class 2606 OID 895680)
-- Name: enum_rp_strassenverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_strassenverkehrtypen
    ADD CONSTRAINT enum_rp_strassenverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12436 (class 2606 OID 895382)
-- Name: enum_rp_tourismustypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_tourismustypen
    ADD CONSTRAINT enum_rp_tourismustypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12394 (class 2606 OID 894542)
-- Name: enum_rp_verfahren_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verfahren
    ADD CONSTRAINT enum_rp_verfahren_pkey PRIMARY KEY (wert);


--
-- TOC entry 12440 (class 2606 OID 895432)
-- Name: enum_rp_verkehrstatus_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verkehrstatus
    ADD CONSTRAINT enum_rp_verkehrstatus_pkey PRIMARY KEY (wert);


--
-- TOC entry 12476 (class 2606 OID 896152)
-- Name: enum_rp_verkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_verkehrtypen
    ADD CONSTRAINT enum_rp_verkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12408 (class 2606 OID 894934)
-- Name: enum_rp_wasserschutztypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserschutztypen
    ADD CONSTRAINT enum_rp_wasserschutztypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12434 (class 2606 OID 895364)
-- Name: enum_rp_wasserschutzzonen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserschutzzonen
    ADD CONSTRAINT enum_rp_wasserschutzzonen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12446 (class 2606 OID 895574)
-- Name: enum_rp_wasserverkehrtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserverkehrtypen
    ADD CONSTRAINT enum_rp_wasserverkehrtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12472 (class 2606 OID 896066)
-- Name: enum_rp_wasserwirtschafttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wasserwirtschafttypen
    ADD CONSTRAINT enum_rp_wasserwirtschafttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12492 (class 2606 OID 896514)
-- Name: enum_rp_wohnensiedlungtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_wohnensiedlungtypen
    ADD CONSTRAINT enum_rp_wohnensiedlungtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12438 (class 2606 OID 895400)
-- Name: enum_rp_zaesurtypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zaesurtypen
    ADD CONSTRAINT enum_rp_zaesurtypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12420 (class 2606 OID 895108)
-- Name: enum_rp_zeitstufen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zeitstufen
    ADD CONSTRAINT enum_rp_zeitstufen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12480 (class 2606 OID 896244)
-- Name: enum_rp_zentralerortsonstigetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zentralerortsonstigetypen
    ADD CONSTRAINT enum_rp_zentralerortsonstigetypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12496 (class 2606 OID 896620)
-- Name: enum_rp_zentralerorttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_rp_zentralerorttypen
    ADD CONSTRAINT enum_rp_zentralerorttypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12362 (class 2606 OID 893870)
-- Name: enum_xp_abemassnahmentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_abemassnahmentypen
    ADD CONSTRAINT enum_xp_abemassnahmentypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12374 (class 2606 OID 894106)
-- Name: enum_xp_abweichungbaunvotypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_abweichungbaunvotypen
    ADD CONSTRAINT enum_xp_abweichungbaunvotypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12376 (class 2606 OID 894130)
-- Name: enum_xp_allgartderbaulnutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_allgartderbaulnutzung
    ADD CONSTRAINT enum_xp_allgartderbaulnutzung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12350 (class 2606 OID 893514)
-- Name: enum_xp_anpflanzungbindungerhaltungsgegenstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_anpflanzungbindungerhaltungsgegenstand
    ADD CONSTRAINT enum_xp_anpflanzungbindungerhaltungsgegenstand_pkey PRIMARY KEY (wert);


--
-- TOC entry 12332 (class 2606 OID 893144)
-- Name: enum_xp_arthoehenbezug_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_arthoehenbezug
    ADD CONSTRAINT enum_xp_arthoehenbezug_pkey PRIMARY KEY (wert);


--
-- TOC entry 12326 (class 2606 OID 893046)
-- Name: enum_xp_arthoehenbezugspunkt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_arthoehenbezugspunkt
    ADD CONSTRAINT enum_xp_arthoehenbezugspunkt_pkey PRIMARY KEY (wert);


--
-- TOC entry 12336 (class 2606 OID 893200)
-- Name: enum_xp_bedeutungenbereich_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_bedeutungenbereich
    ADD CONSTRAINT enum_xp_bedeutungenbereich_pkey PRIMARY KEY (wert);


--
-- TOC entry 12384 (class 2606 OID 894330)
-- Name: enum_xp_besondereartderbaulnutzung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_besondereartderbaulnutzung
    ADD CONSTRAINT enum_xp_besondereartderbaulnutzung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12348 (class 2606 OID 893452)
-- Name: enum_xp_besonderezweckbestgemeinbedarf_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_besonderezweckbestgemeinbedarf
    ADD CONSTRAINT enum_xp_besonderezweckbestgemeinbedarf_pkey PRIMARY KEY (wert);


--
-- TOC entry 12380 (class 2606 OID 894218)
-- Name: enum_xp_besonderezweckbestimmunggruen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_besonderezweckbestimmunggruen
    ADD CONSTRAINT enum_xp_besonderezweckbestimmunggruen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12354 (class 2606 OID 893690)
-- Name: enum_xp_besonderezweckbestimmungverentsorgung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_besonderezweckbestimmungverentsorgung
    ADD CONSTRAINT enum_xp_besonderezweckbestimmungverentsorgung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12358 (class 2606 OID 893810)
-- Name: enum_xp_bundeslaender_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_bundeslaender
    ADD CONSTRAINT enum_xp_bundeslaender_pkey PRIMARY KEY (wert);


--
-- TOC entry 12340 (class 2606 OID 893278)
-- Name: enum_xp_grenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_grenzetypen
    ADD CONSTRAINT enum_xp_grenzetypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12368 (class 2606 OID 894014)
-- Name: enum_xp_klassifizschutzgebietnaturschutzrecht_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_klassifizschutzgebietnaturschutzrecht
    ADD CONSTRAINT enum_xp_klassifizschutzgebietnaturschutzrecht_pkey PRIMARY KEY (wert);


--
-- TOC entry 12342 (class 2606 OID 893306)
-- Name: enum_xp_nutzungsform_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_nutzungsform
    ADD CONSTRAINT enum_xp_nutzungsform_pkey PRIMARY KEY (wert);


--
-- TOC entry 12334 (class 2606 OID 893164)
-- Name: enum_xp_rechtscharakterplanaenderung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_rechtscharakterplanaenderung
    ADD CONSTRAINT enum_xp_rechtscharakterplanaenderung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12330 (class 2606 OID 893124)
-- Name: enum_xp_rechtsstand_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_rechtsstand
    ADD CONSTRAINT enum_xp_rechtsstand_pkey PRIMARY KEY (wert);


--
-- TOC entry 12364 (class 2606 OID 893930)
-- Name: enum_xp_sondernutzungen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_sondernutzungen
    ADD CONSTRAINT enum_xp_sondernutzungen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12328 (class 2606 OID 893094)
-- Name: enum_xp_spemassnahmentypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_spemassnahmentypen
    ADD CONSTRAINT enum_xp_spemassnahmentypen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12366 (class 2606 OID 893974)
-- Name: enum_xp_speziele_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_speziele
    ADD CONSTRAINT enum_xp_speziele_pkey PRIMARY KEY (wert);


--
-- TOC entry 12356 (class 2606 OID 893764)
-- Name: enum_xp_verlaengerungveraenderungssperre_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_verlaengerungveraenderungssperre
    ADD CONSTRAINT enum_xp_verlaengerungveraenderungssperre_pkey PRIMARY KEY (wert);


--
-- TOC entry 12372 (class 2606 OID 894078)
-- Name: enum_xp_zweckbestimmunggemeinbedarf_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggemeinbedarf
    ADD CONSTRAINT enum_xp_zweckbestimmunggemeinbedarf_pkey PRIMARY KEY (wert);


--
-- TOC entry 12370 (class 2606 OID 894044)
-- Name: enum_xp_zweckbestimmunggewaesser_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggewaesser
    ADD CONSTRAINT enum_xp_zweckbestimmunggewaesser_pkey PRIMARY KEY (wert);


--
-- TOC entry 12352 (class 2606 OID 893554)
-- Name: enum_xp_zweckbestimmunggruen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunggruen
    ADD CONSTRAINT enum_xp_zweckbestimmunggruen_pkey PRIMARY KEY (wert);


--
-- TOC entry 12344 (class 2606 OID 893334)
-- Name: enum_xp_zweckbestimmungkennzeichnung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungkennzeichnung
    ADD CONSTRAINT enum_xp_zweckbestimmungkennzeichnung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12346 (class 2606 OID 893370)
-- Name: enum_xp_zweckbestimmunglandwirtschaft_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmunglandwirtschaft
    ADD CONSTRAINT enum_xp_zweckbestimmunglandwirtschaft_pkey PRIMARY KEY (wert);


--
-- TOC entry 12378 (class 2606 OID 894152)
-- Name: enum_xp_zweckbestimmungspielsportanlage_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungspielsportanlage
    ADD CONSTRAINT enum_xp_zweckbestimmungspielsportanlage_pkey PRIMARY KEY (wert);


--
-- TOC entry 12382 (class 2606 OID 894280)
-- Name: enum_xp_zweckbestimmungverentsorgung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungverentsorgung
    ADD CONSTRAINT enum_xp_zweckbestimmungverentsorgung_pkey PRIMARY KEY (wert);


--
-- TOC entry 12360 (class 2606 OID 893848)
-- Name: enum_xp_zweckbestimmungwald_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungwald
    ADD CONSTRAINT enum_xp_zweckbestimmungwald_pkey PRIMARY KEY (wert);


--
-- TOC entry 12338 (class 2606 OID 893234)
-- Name: enum_xp_zweckbestimmungwasserwirtschaft_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY enum_xp_zweckbestimmungwasserwirtschaft
    ADD CONSTRAINT enum_xp_zweckbestimmungwasserwirtschaft_pkey PRIMARY KEY (wert);


--
-- TOC entry 12542 (class 2606 OID 897449)
-- Name: rp_bereich_zu_rp_rasterplanaenderung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_bereich_zu_rp_rasterplanaenderung
    ADD CONSTRAINT rp_bereich_zu_rp_rasterplanaenderung_pkey PRIMARY KEY (rp_bereich_gml_id, rp_rasterplanaenderung_gml_id);


--
-- TOC entry 12512 (class 2606 OID 896729)
-- Name: rp_generischesobjekttypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_generischesobjekttypen
    ADD CONSTRAINT rp_generischesobjekttypen_pkey PRIMARY KEY (id);


--
-- TOC entry 12532 (class 2606 OID 897424)
-- Name: rp_legendenobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_legendenobjekt
    ADD CONSTRAINT rp_legendenobjekt_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12514 (class 2606 OID 896737)
-- Name: rp_sonstgrenzetypen_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_sonstgrenzetypen
    ADD CONSTRAINT rp_sonstgrenzetypen_pkey PRIMARY KEY (id);


--
-- TOC entry 12510 (class 2606 OID 896721)
-- Name: rp_sonstplanart_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_sonstplanart
    ADD CONSTRAINT rp_sonstplanart_pkey PRIMARY KEY (id);


--
-- TOC entry 12508 (class 2606 OID 896713)
-- Name: rp_status_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rp_status
    ADD CONSTRAINT rp_status_pkey PRIMARY KEY (id);


--
-- TOC entry 12526 (class 2606 OID 897310)
-- Name: xp_abstraktespraesentationsobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_abstraktespraesentationsobjekt
    ADD CONSTRAINT xp_abstraktespraesentationsobjekt_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12518 (class 2606 OID 896834)
-- Name: xp_begruendungabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_begruendungabschnitt
    ADD CONSTRAINT xp_begruendungabschnitt_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12522 (class 2606 OID 897270)
-- Name: xp_bereich_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_bereich
    ADD CONSTRAINT xp_bereich_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12538 (class 2606 OID 897439)
-- Name: xp_bereich_zu_xp_objekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_bereich_zu_xp_objekt
    ADD CONSTRAINT xp_bereich_zu_xp_objekt_pkey PRIMARY KEY (xp_bereich_gml_id, xp_objekt_gml_id);


--
-- TOC entry 12500 (class 2606 OID 896681)
-- Name: xp_externereferenzart_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_externereferenzart
    ADD CONSTRAINT xp_externereferenzart_pkey PRIMARY KEY (id);


--
-- TOC entry 12502 (class 2606 OID 896689)
-- Name: xp_gesetzlichegrundlage_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_gesetzlichegrundlage
    ADD CONSTRAINT xp_gesetzlichegrundlage_pkey PRIMARY KEY (id);


--
-- TOC entry 12504 (class 2606 OID 896697)
-- Name: xp_mimetypes_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_mimetypes
    ADD CONSTRAINT xp_mimetypes_pkey PRIMARY KEY (id);


--
-- TOC entry 12520 (class 2606 OID 896845)
-- Name: xp_objekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt
    ADD CONSTRAINT xp_objekt_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12540 (class 2606 OID 897444)
-- Name: xp_objekt_zu_xp_abstraktespraesentationsobjekt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt_zu_xp_abstraktespraesentationsobjekt
    ADD CONSTRAINT xp_objekt_zu_xp_abstraktespraesentationsobjekt_pkey PRIMARY KEY (xp_objekt_gml_id, xp_abstraktespraesentationsobjekt_gml_id);


--
-- TOC entry 12536 (class 2606 OID 897434)
-- Name: xp_objekt_zu_xp_begruendungabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_objekt_zu_xp_begruendungabschnitt
    ADD CONSTRAINT xp_objekt_zu_xp_begruendungabschnitt_pkey PRIMARY KEY (xp_objekt_gml_id, xp_begruendungabschnitt_gml_id);


--
-- TOC entry 12524 (class 2606 OID 897290)
-- Name: xp_plan_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_plan
    ADD CONSTRAINT xp_plan_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12534 (class 2606 OID 897429)
-- Name: xp_plan_zu_xp_begruendungabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_plan_zu_xp_begruendungabschnitt
    ADD CONSTRAINT xp_plan_zu_xp_begruendungabschnitt_pkey PRIMARY KEY (xp_plan_gml_id, xp_begruendungabschnitt_gml_id);


--
-- TOC entry 12528 (class 2606 OID 897393)
-- Name: xp_rasterplanaenderung_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_rasterplanaenderung
    ADD CONSTRAINT xp_rasterplanaenderung_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12530 (class 2606 OID 897413)
-- Name: xp_rasterplanbasis_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_rasterplanbasis
    ADD CONSTRAINT xp_rasterplanbasis_pkey PRIMARY KEY (gml_id);


--
-- TOC entry 12506 (class 2606 OID 896705)
-- Name: xp_stylesheetliste_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_stylesheetliste
    ADD CONSTRAINT xp_stylesheetliste_pkey PRIMARY KEY (id);


--
-- TOC entry 12516 (class 2606 OID 896814)
-- Name: xp_textabschnitt_pkey; Type: CONSTRAINT; Schema: xplan_gml; Owner: -; Tablespace: 
--

ALTER TABLE ONLY xp_textabschnitt
    ADD CONSTRAINT xp_textabschnitt_pkey PRIMARY KEY (gml_id);


-- Completed on 2017-03-03 11:41:03

--
-- PostgreSQL database dump complete
--

COMMIT;