BEGIN;

DROP SCHEMA IF EXISTS wasserrecht CASCADE; 
CREATE SCHEMA wasserrecht;

-- GENERELL
CREATE TABLE wasserrecht.fiswrv_ort(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_adresse(
	id serial PRIMARY KEY,
	strasse varchar(255),
	hausnummer varchar(10),
	plz integer,
	ort varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_dokument(
	id serial PRIMARY KEY,
	name varchar(255),
	pfad text
	/* document bytea */
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_betriebszustand(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_messtischblatt(
	id serial PRIMARY KEY,
	nummer integer
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_archivnummer(
	id serial PRIMARY KEY,
	nummer integer
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_konto(
	id serial PRIMARY KEY,
	name varchar(255),
	iban varchar(22),
	bic varchar(11),
	bankname varchar(255),
	verwendungszweck varchar(255),
	personenkonto varchar(255),
	kassenzeichen varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_mengenbestimmung(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

-------------

CREATE TABLE wasserrecht.fiswrv_behoerde_art(
	id serial PRIMARY KEY,
	name varchar(255),
  	abkuerzung varchar(100)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_behoerde(
	id serial PRIMARY KEY,
	name varchar(255),
  	abkuerzung varchar(100),
  	aktuell boolean DEFAULT true,
  	adresse integer REFERENCES wasserrecht.fiswrv_adresse(id),
  	art integer REFERENCES wasserrecht.fiswrv_behoerde_art(id),
  	konto integer REFERENCES wasserrecht.fiswrv_konto(id)
) WITH OIDS;

-------------

CREATE TABLE wasserrecht.fiswrv_koerperschaft_art(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_koerperschaft(
	id serial PRIMARY KEY,
	name varchar(255),
	art integer REFERENCES wasserrecht.fiswrv_koerperschaft_art(id)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_weeerklaerer(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;

--PERSONEN
CREATE TABLE wasserrecht.fiswrv_personen_klasse(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_personen_status(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_personen_typ(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_personen(
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	abkuerzung varchar(30) NOT NULL,
	namenszusatz varchar(255),
	klasse integer REFERENCES wasserrecht.fiswrv_personen_klasse(id),
	status integer REFERENCES wasserrecht.fiswrv_personen_status(id),
	adresse integer REFERENCES wasserrecht.fiswrv_adresse(id),
	typ integer REFERENCES wasserrecht.fiswrv_personen_typ(id),
	wrzadressat varchar(10),
  	wrzrechtsnachfolger varchar(10),
  	betreiber varchar(10),
  	bearbeiter varchar(10),
  	weeerklaerer integer REFERENCES wasserrecht.fiswrv_weeerklaerer(id),
  	telefon varchar(50),
  	fax varchar(50),
  	email varchar(50),
  	wrzaussteller varchar(10),
  	abwasser_koerperschaft integer REFERENCES wasserrecht.fiswrv_koerperschaft(id),
  	trinkwasser_koerperschaft integer REFERENCES wasserrecht.fiswrv_koerperschaft(id),
  	kommentar varchar(255),
  	zimmer varchar(255),
  	verwendungszweck_wee varchar(255),
  	behoerde integer REFERENCES wasserrecht.fiswrv_behoerde(id),
  	register_amtsgericht varchar(255),
  	register_nummer varchar(255),
  	konto integer REFERENCES wasserrecht.fiswrv_konto(id)
)WITH OIDS;

-- ANLAGEN
CREATE TABLE wasserrecht.fiswrv_anlagen_klasse(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_anlagen(
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	klasse integer NOT NULL REFERENCES wasserrecht.fiswrv_anlagen_klasse(id),
	zustaend_uwb integer NOT NULL REFERENCES wasserrecht.fiswrv_behoerde(id),
  	zustaend_stalu integer NOT NULL REFERENCES wasserrecht.fiswrv_behoerde(id),
  	betreiber integer REFERENCES wasserrecht.fiswrv_personen(id),
  	anlage_bearbeiter_name varchar(255),
  	anlage_bearbeiter_datum varchar(255),
  	anlage_bearbeiter_stelle varchar(255),
  	objektid_geodin varchar(255),
	abwasser_koerperschaft integer REFERENCES wasserrecht.fiswrv_koerperschaft(id),
	trinkwasser_koerperschaft integer REFERENCES wasserrecht.fiswrv_koerperschaft(id),
	kommentar text,
	the_geom geometry(Point, 35833)
) WITH OIDS;
COMMENT ON COLUMN wasserrecht.fiswrv_anlagen.id IS 'Primärschlüssel der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.fiswrv_anlagen.name IS 'Name der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.fiswrv_anlagen.klasse IS 'Klasse der Fis-WrV Objekte';
COMMENT ON COLUMN wasserrecht.fiswrv_anlagen.the_geom IS 'Geometrie';

--WASSERRECHTLICHE ZULASSUNGEN
CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen_status(
	id serial PRIMARY KEY,
	name varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_wasserrechtliche_zulassungen(
	id serial PRIMARY KEY,
	anlage integer NOT NULL REFERENCES wasserrecht.fiswrv_anlagen(id),
	ausstellbehoerde integer REFERENCES wasserrecht.fiswrv_behoerde(id),
	zustaendige_behoerde integer REFERENCES wasserrecht.fiswrv_behoerde(id),
	adressat integer REFERENCES wasserrecht.fiswrv_personen(id),
	bearbeiter integer REFERENCES wasserrecht.fiswrv_personen(id),
	typus integer NOT NULL REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus(id), --Ausgangsbescheid
	bearbeiterzeichen varchar(255), --Aktenzeichen
	aktenzeichen varchar(255) NOT NULL, --Aktenzeichen
	regnummer varchar(255), --Ausgangsbescheid
	bergamt_aktenzeichen varchar(255), --Aktenzeichen
	ort integer REFERENCES wasserrecht.fiswrv_ort(id), --Ausgangsbescheid
	datum date NOT NULL,
	fassung_auswahl integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_auswahl(id), --Änderungsbescheid / Fassung
	fassung_nummer integer, --Änderungsbescheid / Fassung
	fassung_typus integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_fassung_typus(id), --Änderungsbescheid / Fassung
	fassung_bearbeiterzeichen varchar(255),
	fassung_aktenzeichen varchar(255),
	fassung_datum date, --Änderungsbescheid / Fassung
	gueltig_seit date, --Gültigkeit
	befristet_bis date, --Gültigkeit
	status integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_status(id), --Gültigkeit
	aktuell boolean, --Gültigkeit
	ungueltig_seit date, --Gültigkeit
	ungueltig_aufgrund integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_ungueltig_aufgrund(id), --Gültigkeit
	datum_postausgang date, --Ausgangsbescheid
	datum_bestand_mat date, --Ausgangsbescheid
	datum_bestand_form date, --Ausgangsbescheid,
	dokument varchar(255),
	nachfolger integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen(id),
	vorgaenger integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen(id),
	freigegeben boolean DEFAULT false
)WITH OIDS;

--GEWÄSSERBENUTZUNGEN
CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_art(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung(
	id serial PRIMARY KEY,
	name varchar(255),
	abkuerzung varchar(100)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_zweck(
	id serial PRIMARY KEY,
	nummer integer,
	name varchar(255)
)WITH OIDS;

/*CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_umfang_entnahme(
	id serial PRIMARY KEY,
	name varchar(255),
	max_ent_s numeric,
	max_ent_h numeric,
	max_ent_d numeric,
	max_ent_w numeric,
	max_ent_m numeric,
	max_ent_a numeric,
	max_ent_wee numeric,
	max_ent_wee_beschreib text,
	max_ent_wb numeric,
	max_ent_wb_beschreib text,
	max_ent_frei numeric,
	max_ent_frei_beschreib text,
	freitext text
)WITH OIDS;*/

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz(
	id serial PRIMARY KEY,
	name varchar(255),
	jahr date,
	satz_gw_befreit numeric,
	satz_gw_zugelassen numeric,
	satz_gw_nicht_zugelassen numeric,
	satz_gw_zugelassen_ermaessigt numeric,
	satz_gw_nicht_zugelassen_ermaessigt numeric,
	satz_ow_befreit numeric,
	satz_ow_zugelassen numeric,
	satz_ow_nicht_zugelassen numeric,
	satz_ow_zugelassen_ermaessigt numeric,
	satz_ow_nicht_zugelassen_ermaessigt numeric
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen(
	id serial PRIMARY KEY,
	kennnummer varchar(255),
	wasserbuchnummer varchar (255),
	freitext_art text,
	art integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_art(id),
	freitext_zweck text,
	zweck integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_zweck(id),
	ent_wb_alt text,
	ent_datum_von date,
	ent_datum_bis date,
	max_ent_wee numeric,
	max_ent_wee_beschreib text,
	max_ent_wee_reduziert numeric,
	max_ent_wb numeric,
	max_ent_wb_beschreib text,
	wasserrechtliche_zulassungen integer NOT NULL REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen(id),
	freigegeben boolean DEFAULT false
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_lage(
	id serial PRIMARY KEY,
	name varchar(255),
	/*
	betreiber integer REFERENCES wasserrecht.fiswrv_personen(id),
	wwident varchar(255),
	namelang varchar(255),
	namekurz varchar(100),
	bohrungsname varchar(255),
	baujahr date,
	endteufe numeric,
	filterok numeric,
	filteruk numeric,
	betriebszustand integer REFERENCES wasserrecht.fiswrv_betriebszustand(id),
	messtischblatt integer REFERENCES wasserrecht.fiswrv_messtischblatt(id),
	archivnummer integer REFERENCES wasserrecht.fiswrv_archivnummer(id),
	schichtenverzeichnis boolean,
	invid varchar(255),
	*/
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id),
	freigegeben boolean DEFAULT false,
	the_geo geometry(Point, 35833)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name(
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	abkuerzung varchar(100) NOT NULL,
	beschreibung text
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten(
	id serial PRIMARY KEY,
	name varchar(255) NOT NULL,
	abkuerzung varchar (100) NOT NULL
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_gewaesserbenutzungen_umfang(
	id serial PRIMARY KEY,
	name integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name(id),
	wert numeric,
	einheit integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_umfang_einheiten(id),
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_teilgewaesserbenutzungen_art(
	id serial PRIMARY KEY,
	name varchar(255)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_teilgewaesserbenutzungen(
	id serial PRIMARY KEY,
	erhebungsjahr varchar(10),
	art integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_art(id),
	zweck integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_zweck(id),
	umfang numeric,
	wiedereinleitung_nutzer boolean,
	wiedereinleitung_bearbeiter boolean,
	mengenbestimmung integer REFERENCES wasserrecht.fiswrv_mengenbestimmung(id),
	art_benutzung integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_art_benutzung(id),
	befreiungstatbestaende boolean,
	entgeltsatz integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_wee_satz(id),
	teilgewaesserbenutzungen_art integer REFERENCES wasserrecht.fiswrv_teilgewaesserbenutzungen_art(id),
	berechneter_entgeltsatz_zugelassen numeric,
	berechneter_entgeltsatz_nicht_zugelassen numeric,
	berechnetes_entgelt_zugelassen numeric,
	berechnetes_entgelt_nicht_zugelassen numeric,
	freitext text,
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_festsetzung(
	id serial PRIMARY KEY,
	erhebungsjahr varchar(10),
	datum date,
	nutzer text,
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id),
	dokument_datum date,
	summe_nicht_zugelassene_entnahmemengen numeric,
	summe_zugelassene_entnahmemengen numeric,
	summe_entnahmemengen numeric,
	summe_zugelassenes_entgelt numeric,
	summe_nicht_zugelassenes_entgelt numeric,
	summe_entgelt numeric,
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_erklaerung(
	id serial PRIMARY KEY,
	erhebungsjahr varchar(10),
	datum date,
	nutzer text,
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id),
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id)
)WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_aufforderung(
	id serial PRIMARY KEY,
	erhebungsjahr varchar(10),
	datum date,
	nutzer text,
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id),
	gewaesserbenutzungen integer NOT NULL REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id)
)WITH OIDS;

------------------------------------------
--VIEWS

CREATE VIEW wasserrecht.fiswrv_wasserrechtliche_zulassungen_bezeichnung AS SELECT a.id AS "id", a.anlage AS "anlage", COALESCE(b.name,'') ||' (Aktenzeichen: '|| COALESCE(a.aktenzeichen,'') ||')'||' vom '|| COALESCE(a.datum::text,'') || CASE WHEN a.fassung_nummer IS NOT NULL THEN ' in der Fassung vom ' || COALESCE(a.fassung_datum::text,'') ELSE '' END AS "bezeichnung" FROM wasserrecht.fiswrv_wasserrechtliche_zulassungen a LEFT JOIN wasserrecht.fiswrv_wasserrechtliche_zulassungen_typus b ON a.typus = b.id ORDER BY a.id;

/*CREATE VIEW wasserrecht.fiswrv_gewaesserbenutzungen_bezeichnung AS SELECT a.id AS "id", a.wasserrechtliche_zulassungen as "wasserrechtliche_zulassungen", COALESCE(c.name,'') || ' für ' || COALESCE(b.name::text,'') || ' von ' || COALESCE(d.max_ent_a::text,'') || ' m³/Jahr' AS "bezeichnung" FROM wasserrecht.fiswrv_gewaesserbenutzungen a LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_zweck b ON b.id = a.zweck LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_art c ON c.id = a.art LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_umfang_entnahme d ON a.umfang_entnahme = d.id ORDER BY a.id; */ 
CREATE VIEW wasserrecht.fiswrv_gewaesserbenutzungen_bezeichnung AS SELECT a.id AS "id", a.wasserrechtliche_zulassungen as "wasserrechtliche_zulassungen", COALESCE(c.name,'') || ' für ' || COALESCE(b.name::text,'') || ' von ' || COALESCE(d.wert::text,'') || ' m³/Jahr' AS "bezeichnung" FROM wasserrecht.fiswrv_gewaesserbenutzungen a LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_zweck b ON b.id = a.zweck LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_art c ON c.id = a.art LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_umfang d ON a.id = d.gewaesserbenutzungen LEFT JOIN wasserrecht.fiswrv_gewaesserbenutzungen_umfang_name e ON d.name = e.id WHERE e.abkuerzung = 'max_ent_a' ORDER BY a.id; 

CREATE VIEW wasserrecht.fiswrv_personen_bezeichnung AS SELECT a.id AS "id", COALESCE(a.name,'') ||' '|| COALESCE(a.abkuerzung,'') ||' '|| COALESCE(b.ort,'') AS "bezeichnung" FROM wasserrecht.fiswrv_personen a LEFT JOIN wasserrecht.fiswrv_adresse b ON a.adresse=b.id ORDER BY a.id;

------------------------------------------

/*
CREATE TABLE wasserrecht.fiswrv_wasserentnahme_messung
(
	id serial PRIMARY KEY,
	gewaesserbenutzungen integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id),
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
 
CREATE TABLE wasserrecht.fiswrv_erstattung_auswahl(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.fiswrv_erstattung_auswahl VALUES (1, 'Hiermit beantrage ich gemäß § 18 Abs. 4 LWaG M-V die Erstattung des Verwaltungsaufwandes zum Wasserentnahmeentgelt.');

CREATE TABLE wasserrecht.fiswrv_erstattung(
	id serial PRIMARY KEY,
	auswahl integer REFERENCES wasserrecht.fiswrv_erstattung_auswahl(id),
	datum date,
	konto integer REFERENCES wasserrecht.fiswrv_konto(id)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_aufforderung(
	id serial PRIMARY KEY,
	datum_absend date,
	datum_wiedervorlage date,
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id),
	status varchar(255),
	aktenzeichen integer REFERENCES wasserrecht.fiswrv_aktenzeichen(id)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_erklaerung_eingang_check(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.fiswrv_erklaerung_eingang_check VALUES (1, 'nicht fristgemäß erklärt');
INSERT INTO wasserrecht.fiswrv_erklaerung_eingang_check VALUES (2, 'fristgemäße Erklärung möglich');
INSERT INTO wasserrecht.fiswrv_erklaerung_eingang_check VALUES (3, 'keine fristgemäße Erklärung möglich');
INSERT INTO wasserrecht.fiswrv_erklaerung_eingang_check VALUES (4, 'fristgemäß erklärt');

CREATE TABLE wasserrecht.fiswrv_erklaerung_vollstendig_check(
	id serial PRIMARY KEY,
	name varchar(255)
) WITH OIDS;
INSERT INTO wasserrecht.fiswrv_erklaerung_vollstendig_check VALUES (1, 'Wasserentnahmeerklärung vollständig eingegangen');
INSERT INTO wasserrecht.fiswrv_erklaerung_vollstendig_check VALUES (2, 'WEE-Erklärung vollständig eingegangen');

CREATE TABLE wasserrecht.fiswrv_erklaerung(
	id serial PRIMARY KEY,
	eingang_check integer REFERENCES wasserrecht.fiswrv_erklaerung_eingang_check(id),
	vollstendig_check integer REFERENCES wasserrecht.fiswrv_erklaerung_vollstendig_check(id),
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_festsetzung(
	id serial PRIMARY KEY,
	dokument integer REFERENCES wasserrecht.fiswrv_dokument(id),
	status varchar(255)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_bescheid(
	id serial PRIMARY KEY,
	konto integer REFERENCES wasserrecht.fiswrv_konto(id),
	datum_zustellurkunde date,
	ort integer REFERENCES wasserrecht.fiswrv_ort(id)
) WITH OIDS;

CREATE TABLE wasserrecht.fiswrv_entnahme(
	id serial PRIMARY KEY,
	entnahme_zweck integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_zweck(id),
	entnahme_typ integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_art(id)
) WITH OIDS;
 
CREATE TABLE wasserrecht.fiswrv_fiswrv_wem
(
	id serial PRIMARY KEY,
  	jahr date,
  	ausgangsbescheid integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen_ausgangsbescheide(id), 
  	ent_a numeric(11,3),
  	anlage integer REFERENCES wasserrecht.fiswrv_anlagen(id),
  	ein_a numeric(11,3),
  	ent_erfassung text,
  	wem_absend_per_gid integer,
  	wem_nutzer_per_gid integer,
  	bescheid integer REFERENCES wasserrecht.fiswrv_bescheid(id), 
  	wrz_ben_zweck integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen_zweck(id),
  	aufforderung integer REFERENCES wasserrecht.fiswrv_aufforderung(id),
  	erklaerung integer REFERENCES wasserrecht.fiswrv_erklaerung(id),
  	datum date,
  	erstattung integer REFERENCES wasserrecht.fiswrv_erstattung(id),
  	festsetzung integer REFERENCES wasserrecht.fiswrv_festsetzung(id),
  	entnahme integer REFERENCES wasserrecht.fiswrv_entnahme(id),
  	wasserrechtliche_zulassungen integer REFERENCES wasserrecht.fiswrv_wasserrechtliche_zulassungen(id),
  	gewaesserbenutzungen integer REFERENCES wasserrecht.fiswrv_gewaesserbenutzungen(id),
  	wem_wee_befreiung numeric(1,0),
  	wee_beschwee_per_gid integer,
  	bearbeiter integer REFERENCES wasserrecht.fiswrv_personen(id),
  	wee_wee_eingegangen_lk text,
  	wee_wee_eingegangen_land text,
  	kommentar text,
  	sachbearbeiter integer REFERENCES wasserrecht.fiswrv_personen(id)
)WITH OIDS;
*/

--------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION wasserrecht.wrz_freigegeben_copy_function()
RETURNS trigger AS '
BEGIN
  IF NEW.freigegeben IS NOT NULL THEN
    NEW.freigegeben := false;
  END IF;
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER wrz_freigegeben_copy_trigger
BEFORE INSERT ON wasserrecht.fiswrv_wasserrechtliche_zulassungen
FOR EACH ROW
EXECUTE PROCEDURE wasserrecht.wrz_freigegeben_copy_function();

CREATE OR REPLACE FUNCTION wasserrecht.wrz_vorgaenger_nachfolger_function()
RETURNS trigger AS '
BEGIN
  IF NEW.vorgaenger IS NOT NULL THEN
    UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET nachfolger = NEW.id WHERE id = NEW.vorgaenger;
  ELSIF OLD.vorgaenger IS NOT NULL AND NEW.vorgaenger IS NULL THEN
    UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET nachfolger = NULL WHERE id = OLD.vorgaenger;
  END IF;

  IF NEW.nachfolger IS NOT NULL THEN
	UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET vorgaenger = NEW.id WHERE id = NEW.nachfolger;
  ELSIF OLD.nachfolger IS NOT NULL AND NEW.nachfolger IS NULL THEN
	UPDATE wasserrecht.fiswrv_wasserrechtliche_zulassungen SET vorgaenger = NULL WHERE id = OLD.nachfolger;
  END IF;

  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER wrz_vorgaenger_nachfolger_trigger
BEFORE UPDATE ON wasserrecht.fiswrv_wasserrechtliche_zulassungen
FOR EACH ROW
WHEN ((OLD.vorgaenger IS DISTINCT FROM NEW.vorgaenger OR OLD.nachfolger IS DISTINCT FROM NEW.nachfolger) AND pg_trigger_depth() = 0)
EXECUTE PROCEDURE wasserrecht.wrz_vorgaenger_nachfolger_function();

-------

CREATE OR REPLACE FUNCTION wasserrecht.gewaesserbenutzungen_freigegeben_copy_function()
RETURNS trigger AS '
BEGIN
  IF NEW.freigegeben IS NOT NULL THEN
    NEW.freigegeben := false;
  END IF;
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER gewaesserbenutzungen_freigegeben_copy_trigger
BEFORE INSERT ON wasserrecht.fiswrv_gewaesserbenutzungen
FOR EACH ROW
EXECUTE PROCEDURE wasserrecht.gewaesserbenutzungen_freigegeben_copy_function();

-------

CREATE OR REPLACE FUNCTION wasserrecht.gewaesserbenutzungen_lage_freigegeben_copy_function()
RETURNS trigger AS '
BEGIN
  IF NEW.freigegeben IS NOT NULL THEN
    NEW.freigegeben := false;
  END IF;
  RETURN NEW;
END' LANGUAGE 'plpgsql';

CREATE TRIGGER gewaesserbenutzungen_lage_freigegeben_copy_trigger
BEFORE INSERT ON wasserrecht.fiswrv_gewaesserbenutzungen_lage
FOR EACH ROW
EXECUTE PROCEDURE wasserrecht.gewaesserbenutzungen_lage_freigegeben_copy_function();

COMMIT;