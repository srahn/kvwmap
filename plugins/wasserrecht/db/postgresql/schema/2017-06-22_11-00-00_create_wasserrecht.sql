BEGIN;

DROP SCHEMA IF EXISTS wasserrecht CASCADE; 
CREATE SCHEMA wasserrecht;

-- GENERELL
CREATE TABLE wasserrecht.behoerde(
	id serial PRIMARY KEY,
	name varchar(255),
  	abkuerzung varchar(100),
  	status varchar(100)
) WITH OIDS;

-------------

CREATE TABLE wasserrecht.koerperschaft_art(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.koerperschaft(
	id serial PRIMARY KEY,
	name varchar(255),
	art integer REFERENCES wasserrecht.koerperschaft_art(id)
) WITH OIDS;

CREATE TABLE wasserrecht.weeerklaerer(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

------------

CREATE TABLE wasserrecht.ort(
	id serial PRIMARY KEY,
	name varchar(255),
	the_geo geometry(Point, 35833)
) WITH OIDS;

CREATE TABLE wasserrecht.adresse(
	id serial PRIMARY KEY,
	strasse varchar(255),
	hausnummer varchar(10),
	plz integer,
	ort varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.aktenzeichen(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.dokument(
	id serial PRIMARY KEY,
	name varchar(255),
	pfad text,
	document bytea
) WITH OIDS;

CREATE TABLE wasserrecht.betriebszustand(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.messtischblatt(
	id serial PRIMARY KEY,
	nummer integer
) WITH OIDS;

CREATE TABLE wasserrecht.archivnummer(
	id serial PRIMARY KEY,
	nummer integer
) WITH OIDS;

CREATE TABLE wasserrecht.wasserbuch(
	id serial PRIMARY KEY,
	nummer integer
) WITH OIDS;

CREATE TABLE wasserrecht.konto(
	id serial PRIMARY KEY,
	name varchar(255),
	iban varchar(22),
	bic varchar(11),
	verwendungszweck varchar(255),
	personenkonto varchar(255),
	kassenzeichen varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.mengenbestimmung(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

--PERSONEN
CREATE TABLE wasserrecht.personen_klasse(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.personen_status(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.personen_typ(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.personen(
	id serial PRIMARY KEY,
	name varchar(255),
	bezeichnung varchar(255),
	klasse integer REFERENCES wasserrecht.personen_klasse(id),
	status integer REFERENCES wasserrecht.personen_status(id),
	adresse integer REFERENCES wasserrecht.adresse(id),
	typ integer REFERENCES wasserrecht.personen_typ(id),
	wrzadressat boolean,
  	wrzrechtsnachfolger boolean,
  	betreiber boolean,
  	bearbeiter boolean,
  	weeerklaerer integer REFERENCES wasserrecht.weeerklaerer(id),
  	telefon varchar(50),
  	fax varchar(50),
  	email varchar(50),
  	abkuerzung varchar(30),
  	wrzaussteller boolean,
  	abwasser_koerperschaft integer REFERENCES wasserrecht.koerperschaft(id),
  	trinkwasser_koerperschaft integer REFERENCES wasserrecht.koerperschaft(id),
  	kommentar varchar(255),
  	zimmer varchar(255),
  	behoerde integer REFERENCES wasserrecht.behoerde(id),
  	register_amtsgericht varchar(255),
  	register_nummer varchar(255),
  	konto integer REFERENCES wasserrecht.konto(id),
  	the_geo geometry(Point, 35833)
)WITH OIDS;

-- ANLAGEN
CREATE TABLE wasserrecht.anlagen_klasse(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.anlagen(
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	klasse integer REFERENCES wasserrecht.anlagen_klasse(id),
	zustaend_uwb integer REFERENCES wasserrecht.personen(id),
  	zustaend_stalu integer REFERENCES wasserrecht.personen(id),
  	bearbeiter integer REFERENCES wasserrecht.personen(id),
  	objektid_geodin varchar(255),
	betreiber integer REFERENCES wasserrecht.personen(id),
	abwasser_koerperschaft integer REFERENCES wasserrecht.koerperschaft(id),
	trinkwasser_koerperschaft integer REFERENCES wasserrecht.koerperschaft(id),
	kommentar text,
	the_geom geometry(Point, 35833)
) WITH OIDS;
COMMENT ON COLUMN wasserrecht.anlagen.id IS 'Primärschlüssel der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.anlagen.name IS 'Name der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.anlagen.klasse IS 'Klasse der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.anlagen.the_geom IS 'Geometrie';

--WASSERRECHTLICHE ZULASSUNGEN
CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_bearbeiterzeichen(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide(
	id serial PRIMARY KEY,
	name varchar(255),
	klasse integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse(id),
	bearbeiterzeichen integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_bearbeiterzeichen(id),
	aktenzeichen integer REFERENCES wasserrecht.aktenzeichen(id),
	datum date,
	ort integer REFERENCES wasserrecht.ort(id),
	regnummer varchar(255),
	ausstellbehoerde integer REFERENCES wasserrecht.behoerde(id)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_fassung_klasse(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_fassung_auswahl(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_fassung(
	id serial PRIMARY KEY,
	auswahl integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_fassung_auswahl(id),
	aktenzeichen integer REFERENCES wasserrecht.aktenzeichen(id),
	klasse integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_fassung_klasse(id),
	datum date,
	ort integer REFERENCES wasserrecht.ort(id),
	nummer integer
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_aenderungsbescheide(
	id serial PRIMARY KEY,
	bearbeiterzeichen integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_bearbeiterzeichen(id),
	aktenzeichen integer REFERENCES wasserrecht.aktenzeichen(id),
	datum_postausgang date,
	datum_bestand_mat date,
	datum_bestand_form date,
	ort integer REFERENCES wasserrecht.ort(id),
	nummer integer
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_status(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen_gueltigkeit(
	id serial PRIMARY KEY,
	gueltig_seit date,
	gueltig_bis date,
	ungueltig_seit date,
	ungueltig_aufgrund integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_ungueltig_aufgrund(id),
	abgelaufen boolean
)WITH OIDS;

CREATE TABLE wasserrecht.wasserrechtliche_zulassungen(
	id serial PRIMARY KEY,
	name varchar(255),
	ausstellbehoerde integer REFERENCES wasserrecht.behoerde(id),
	ausgangsbescheid integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide(id),
	fassung integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_fassung(id),
	status integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_status(id),
	adresse integer REFERENCES wasserrecht.adresse(id),
	aenderungsbescheid integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_aenderungsbescheide(id),
	gueltigkeit integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_gueltigkeit(id),
	aktuell boolean DEFAULT true,
	historisch boolean DEFAULT true,
	bergamt_aktenzeichen integer REFERENCES wasserrecht.aktenzeichen(id),
	dokument integer REFERENCES wasserrecht.dokument(id),
	sachbearbeiter integer REFERENCES wasserrecht.personen(id),
	adressat integer REFERENCES wasserrecht.personen(id),
	aufforderung_datum_absend date,
	aufforderung_dokument integer REFERENCES wasserrecht.dokument(id),
	erklaerung_datum date,
	erklaerung_dokument integer REFERENCES wasserrecht.dokument(id),
	anlage integer REFERENCES wasserrecht.anlagen(id)
)WITH OIDS;

--GEWÄSSERBENUTZUNGEN
CREATE TABLE wasserrecht.gewaesserbenutzungen_art(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen_art_benutzung(
	id serial PRIMARY KEY,
	name varchar(255),
	abkuerzung varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen_zweck(
	id serial PRIMARY KEY,
	nummer integer,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen_umfang(
	id serial PRIMARY KEY,
	name varchar(255),
	max_ent_s numeric(15,3),
	max_ent_h numeric(15,3),
	max_ent_d numeric(15,3),
	max_ent_w numeric(15,3),
	max_ent_m numeric(15,3),
	max_ent_a numeric(15,3),
	max_ent_wee numeric(15,3),
	max_ent_wee_beschreib text,
	max_ent_wb numeric(15,3),
	max_ent_wb_beschreib text,
	max_ent_frei numeric(15,3),
	max_ent_frei_beschreib text,
	freitext text
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen_lage(
	id serial PRIMARY KEY,
	name varchar(255),
	betreiber integer REFERENCES wasserrecht.personen(id),
	wwident varchar(255),
	namelang varchar(255),
	namekurz varchar(100),
	bohrungsname varchar(255),
	baujahr date,
	endteufe numeric(6,2),
	filterok numeric(6,2),
	filteruk numeric(6,2),
	betriebszustand integer REFERENCES wasserrecht.betriebszustand(id),
	messtischblatt integer REFERENCES wasserrecht.messtischblatt(id),
	archivnummer integer REFERENCES wasserrecht.archivnummer(id),
	schichtenverzeichnis boolean,
	invid varchar(255),
	the_geo geometry(Point, 35833)
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen_wee_satz(
	id serial PRIMARY KEY,
	name varchar(255),
	jahr date,
	satz_ow numeric,
	satz_gw numeric
)WITH OIDS;

CREATE TABLE wasserrecht.gewaesserbenutzungen(
	id serial PRIMARY KEY,
	kennnummer varchar(255),
	freitext_art text,
	art integer REFERENCES wasserrecht.gewaesserbenutzungen_art(id),
	wasserbuch integer REFERENCES wasserrecht.wasserbuch(id),
	zweck integer REFERENCES wasserrecht.gewaesserbenutzungen_zweck(id),
	umfang integer REFERENCES wasserrecht.gewaesserbenutzungen_umfang(id),
	gruppe_wee boolean,
	lage integer REFERENCES wasserrecht.gewaesserbenutzungen_lage(id),
	wasserrechtliche_zulassungen integer NOT NULL REFERENCES wasserrecht.wasserrechtliche_zulassungen(id)
)WITH OIDS;

CREATE TABLE wasserrecht.teilgewaesserbenutzungen_art(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.teilgewaesserbenutzungen(
	id serial PRIMARY KEY,
	art integer REFERENCES wasserrecht.gewaesserbenutzungen_art(id),
	zweck integer REFERENCES wasserrecht.gewaesserbenutzungen_zweck(id),
	umfang numeric(15,3),
	wiedereinleitung_nutzer boolean,
	wiedereinleitung_bearbeiter boolean,
	mengenbestimmung integer REFERENCES wasserrecht.mengenbestimmung(id),
	art_benutzung integer REFERENCES wasserrecht.gewaesserbenutzungen_art_benutzung(id),
	befreiungstatbestaende boolean,
	entgeltsatz integer REFERENCES wasserrecht.gewaesserbenutzungen_wee_satz(id),
	teilgewaesserbenutzungen_art integer REFERENCES wasserrecht.teilgewaesserbenutzungen_art(id),
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.gewaesserbenutzungen(id)
)WITH OIDS;

------------------------------------------
--VIEWS

--CREATE VIEW wasserrecht.aktuelle_wasserrechtliche_zulassungen AS SELECT a.id as wrz_id, a.name, COALESCE(c.name,'') ||' (Aktenzeichen: '|| COALESCE(d.name,'') ||')'||' vom '|| COALESCE(b.datum::text,'') AS bezeichnung, a.aktuell, a.historisch, a.anlage AS anlage_id FROM wasserrecht.wasserrechtliche_zulassungen a LEFT JOIN wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide b ON a.ausgangsbescheid = b.id LEFT JOIN wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse c ON b.klasse = c.id LEFT JOIN wasserrecht.aktenzeichen d ON b.aktenzeichen = d.id WHERE a.aktuell = true;
--CREATE VIEW wasserrecht.historische_wasserrechtliche_zulassungen AS SELECT a.id as wrz_id, a.name, COALESCE(c.name,'') ||' (Aktenzeichen: '|| COALESCE(d.name,'') ||')'||' vom '|| COALESCE(b.datum::text,'') AS bezeichnung, a.aktuell, a.historisch, a.anlage AS anlage_id FROM wasserrecht.wasserrechtliche_zulassungen a LEFT JOIN wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide b ON a.ausgangsbescheid = b.id LEFT JOIN wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide_klasse c ON b.klasse = c.id LEFT JOIN wasserrecht.aktenzeichen d ON b.aktenzeichen = d.id WHERE a.historisch = true;
--CREATE VIEW wasserrecht.gewaesserbenutzungen_aktueller_wasserrechtlicher_zulassungen AS SELECT b.id AS gewaesserbenutzungen_id, a.wrz_id AS wrz_id, a.aktuell, a.historisch, a.anlage_id, COALESCE(a.bezeichnung,'') || ' zum ' || COALESCE(c.name,'') || ' von ' || COALESCE(d.max_ent_a::text,'') || ' m³/Jahr' AS bezeichnung, e.namelang AS wrz_ben_lage_namelang
--  FROM wasserrecht.aktuelle_wasserrechtliche_zulassungen a INNER JOIN wasserrecht.gewaesserbenutzungen b ON b.wasserrechtliche_zulassungen = a.wrz_id LEFT JOIN wasserrecht.gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN wasserrecht.gewaesserbenutzungen_umfang d ON b.umfang = d.id LEFT JOIN wasserrecht.gewaesserbenutzungen_lage e ON b.lage = e.id;

--CREATE VIEW wasserrecht.wasserentnamebenutzer AS SELECT c.oid, a.name AS anlage, b.name AS wasserrechtliche_zulassung, c.kennnummer AS benutzungsnummer FROM wasserrecht.anlagen a INNER JOIN wasserrecht.wasserrechtliche_zulassungen b ON b.anlage=a.id INNER JOIN wasserrecht.gewaesserbenutzungen c ON b.id = c.wasserrechtliche_zulassungen;

------------------------------------------

/*
CREATE TABLE wasserrecht.wasserentnahme_messung
(
	id serial PRIMARY KEY,
	gewaesserbenutzungen integer REFERENCES wasserrecht.gewaesserbenutzungen(id),
	chem_datum date,
	chem_temp numeric(5,2),
	chem_farb numeric(6,4),
	chem_smpname text,
	chem_geruch text,
	chem_trueb numeric(5,2),
	chem_ph numeric(4,2),
	chem_leitf numeric(7,2),
	chem_ox numeric(5,2),
	chem_hco3 numeric(5,2),
	chem_ac42 numeric(5,3),
	chem_bk82 numeric(5,3),
	chem_redox numeric(3,0),
	chem_cl numeric(6,1),
	chem_so4 numeric(5,1),
	chem_no3 numeric(6,2),
	chem_no2 numeric(6,3),
	chem_nh4 numeric(5,2),
	chem_ca numeric(5,1),
	chem_mg numeric(6,2),
	chem_na numeric(6,2),
	chem_k numeric(5,1),
	chem_fe numeric(5,2),
	chem_mn numeric(4,2),
	chem_b numeric(7,3),
	chem_toc numeric(5,2),
	chem_cr numeric(8,6),
	chem_ni numeric(8,6),
	chem_pb numeric(8,6),
	chem_cd numeric(8,6),
	chem_as numeric(8,6),
	chem_hg numeric(8,6),
	chem_u numeric(8,6),
	chem_cn numeric(7,5)
 )WITH OIDS;
 
---------
 
CREATE TABLE wasserrecht.erstattung_auswahl(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.erstattung_auswahl VALUES (1, 'Hiermit beantrage ich gemäß § 18 Abs. 4 LWaG M-V die Erstattung des Verwaltungsaufwandes zum Wasserentnahmeentgelt.');

CREATE TABLE wasserrecht.erstattung(
	id serial PRIMARY KEY,
	auswahl integer REFERENCES wasserrecht.erstattung_auswahl(id),
	datum date,
	konto integer REFERENCES wasserrecht.konto(id)
) WITH OIDS;

CREATE TABLE wasserrecht.aufforderung(
	id serial PRIMARY KEY,
	datum_absend date,
	datum_wiedervorlage date,
	dokument integer REFERENCES wasserrecht.dokument(id),
	status varchar(255),
	aktenzeichen integer REFERENCES wasserrecht.aktenzeichen(id)
) WITH OIDS;

CREATE TABLE wasserrecht.erklaerung_eingang_check(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.erklaerung_eingang_check VALUES (1, 'nicht fristgemäß erklärt');
INSERT INTO wasserrecht.erklaerung_eingang_check VALUES (2, 'fristgemäße Erklärung möglich');
INSERT INTO wasserrecht.erklaerung_eingang_check VALUES (3, 'keine fristgemäße Erklärung möglich');
INSERT INTO wasserrecht.erklaerung_eingang_check VALUES (4, 'fristgemäß erklärt');

CREATE TABLE wasserrecht.erklaerung_vollstendig_check(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.erklaerung_vollstendig_check VALUES (1, 'Wasserentnahmeerklärung vollständig eingegangen');
INSERT INTO wasserrecht.erklaerung_vollstendig_check VALUES (2, 'WEE-Erklärung vollständig eingegangen');

CREATE TABLE wasserrecht.erklaerung(
	id serial PRIMARY KEY,
	eingang_check integer REFERENCES wasserrecht.erklaerung_eingang_check(id),
	vollstendig_check integer REFERENCES wasserrecht.erklaerung_vollstendig_check(id),
	dokument integer REFERENCES wasserrecht.dokument(id)
) WITH OIDS;

CREATE TABLE wasserrecht.festsetzung(
	id serial PRIMARY KEY,
	dokument integer REFERENCES wasserrecht.dokument(id),
	status varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.bescheid(
	id serial PRIMARY KEY,
	konto integer REFERENCES wasserrecht.konto(id),
	datum_zustellurkunde date,
	ort integer REFERENCES wasserrecht.ort(id)
) WITH OIDS;

CREATE TABLE wasserrecht.entnahme(
	id serial PRIMARY KEY,
	entnahme_zweck integer REFERENCES wasserrecht.gewaesserbenutzungen_zweck(id),
	entnahme_typ integer REFERENCES wasserrecht.gewaesserbenutzungen_art(id)
) WITH OIDS;
 
CREATE TABLE wasserrecht.fiswrv_wem
(
	id serial PRIMARY KEY,
  	jahr date,
  	ausgangsbescheid integer REFERENCES wasserrecht.wasserrechtliche_zulassungen_ausgangsbescheide(id), 
  	ent_a numeric(11,3),
  	anlage integer REFERENCES wasserrecht.anlagen(id),
  	ein_a numeric(11,3),
  	ent_erfassung text,
  	wem_absend_per_gid integer,
  	wem_nutzer_per_gid integer,
  	bescheid integer REFERENCES wasserrecht.bescheid(id), 
  	wrz_ben_zweck integer REFERENCES wasserrecht.gewaesserbenutzungen_zweck(id),
  	aufforderung integer REFERENCES wasserrecht.aufforderung(id),
  	erklaerung integer REFERENCES wasserrecht.erklaerung(id),
  	datum date,
  	erstattung integer REFERENCES wasserrecht.erstattung(id),
  	festsetzung integer REFERENCES wasserrecht.festsetzung(id),
  	entnahme integer REFERENCES wasserrecht.entnahme(id),
  	wasserrechtliche_zulassungen integer REFERENCES wasserrecht.wasserrechtliche_zulassungen(id),
  	gewaesserbenutzungen integer REFERENCES wasserrecht.gewaesserbenutzungen(id),
  	wem_wee_befreiung numeric(1,0),
  	wee_beschwee_per_gid integer,
  	bearbeiter integer REFERENCES wasserrecht.personen(id),
  	wee_wee_eingegangen_lk text,
  	wee_wee_eingegangen_land text,
  	kommentar text,
  	sachbearbeiter integer REFERENCES wasserrecht.personen(id)
)WITH OIDS;
*/

COMMIT;