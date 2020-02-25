BEGIN;

	CREATE TABLE xplankonverter.enum_az_anzeigepflicht(
		wert integer NOT NULL,
		abkuerzung character varying,
		beschreibung text,
		CONSTRAINT enum_az_anzeigepflicht_pkey PRIMARY KEY (wert)
	) WITH ( OIDS = TRUE);

	INSERT INTO xplankonverter.enum_az_anzeigepflicht (wert, abkuerzung, beschreibung) VALUES (
		1000,
		'Gemäß § 21 LPlG und § 246 a Abs. 1 Nr. 1 BauGB',
		'Gemäß § 21 LPlG und § 246 a Abs. 1 Nr. 1 BauGB haben die Gemeinden der Landesplanungsbehörde die beabsichtigte Aufstellung eines Bauleitplanes oder Vorhaben- und Erschließungsplanes anzuzeigen'
	), (
		2000,
		'Gemäß § 24 LPlG',
		'Gemäß § 24 LPlG sind der Landesplanungsbehörde alle raumbeanspruchenden oder raumbeeinflussenden Planungen, Maßnahmen und Einzelvorhaben mitzuteilen'
	);

	CREATE TYPE xplankonverter.az_anzeigepflicht AS ENUM
	('1000', '2000');

	CREATE TABLE xplankonverter.enum_az_anzeigeverfahren(
		wert integer NOT NULL,
		abkuerzung character varying,
		beschreibung text,
		CONSTRAINT enum_az_anzeigeverfahren_pkey PRIMARY KEY (wert)
	) WITH ( OIDS = TRUE);

	INSERT INTO xplankonverter.enum_az_anzeigeverfahren (wert, abkuerzung, beschreibung) VALUES (
		1000,
		'Anzeige von Bauleitplänen oder Vorhaben- und Erschließungsplänen',
		'Die Gemeinde teilt die Absicht, einen Bauleitplan oder Vorhaben- und Erschließungsplan aufzustellen, nach § 21 LPlG und § 246 a Abs. 1 Nr. 1 BauGB auf dem Dienstweg über den Landrat dem örtlich zuständigen Amt für Raumordnung und Landesplanung und nachrichtlich dem Ministerium für Energie-, Infrastruktur- und Digitalisierung (Landesplanungsbehörde) mit. Die Amtsbereiche und die Adressen ergeben sich aus der Anlage 2 des Anzeigenerlasses M-V.'
	), (
		2000,
		'Mitteilung von Planungen, Maßnahmen und Einzelvorhaben',
		'Raumbeanspruchende oder raumbeeinflussende Planungen, Maßnahmen und Einzelvorhaben sind der Landesplanungsbehörde nach § 24 LPlG anzuzeigen.'
	);

	CREATE TYPE xplankonverter.az_anzeigeverfahren AS ENUM
	('1000', '2000');

	CREATE TABLE xplankonverter.az_planungsabsichtsart (
		codespace text,
		id character varying NOT NULL,
		value text,
		CONSTRAINT az_planungsabsichtsart_pkey PRIMARY KEY (id)
	) WITH ( OIDS = TRUE);

	INSERT INTO xplankonverter.az_planungsabsichtsart (id, value) VALUES
	(1000, 'Erläuterung zu den Entwicklungszielen der Gemeinde für das Plangebiet'),
	(2000, 'maßstäblicher Plan des Baugebietes einschließlich räumlichem Bezug der Planungsflächen'),
	(3000, 'Art und Umfang der Planungsfläche'),
	(4000, 'Verkehrserschließung sowie Ver- und Entsorgung');

	CREATE TABLE xplankonverter.az_anzeigereferenztyp (
		codespace text,
		id character varying NOT NULL,
		value text,
		CONSTRAINT az_anzeigereferenztyp_pkey PRIMARY KEY (id)
	) WITH ( OIDS = TRUE);

	INSERT INTO xplankonverter.az_anzeigereferenztyp (id, value) VALUES
	(1000, 'Gebiete zum Schutz der natürlichen Lebensgrundlagen'),
	(1010, 'Ausweisung und einstweilige Sicherstellung von Schutzgebieten nach dem Naturschutzrecht'),
	(1020, 'Vorschläge für Schutzgebiete mit europäischem Schutzstatus'),
	(1030, 'Pflege- und Entwicklungspläne für Großschutzgebiete'),
	(1040, 'Festsetzung und Aufhebung von Wasserschutzgebieten und Heilquellenschutzgebieten'),
	(2000, 'Siedlungswesen'),
	(2010, 'Kreis- und Stadtentwicklungspläne'),
	(2020, 'Städtebauliche Rahmenpläne'),
	(2030, 'Einzelhandelskonzepte gemäß Nr. 5.5.3. Abs. 2 Landesraumordnungsprogramm'),
	(3000, 'Wirtschaft/Land- und Forstwirtschaft'),
	(3010, 'Agrarstrukturelle Rahmenplanung'),
	(3020, 'Flurbereinigungsverfahren'),
	(3030, 'Aufforstungsvorhaben mit einer Gesamtfläche von fünf Hektar oder mehr'),
	(3040, 'Räumlich differenzierende oder teilräumliche Förderungs- und Aktionsprogramme'),
	(3050, 'Einzelvorhaben und Rahmenpläne zum Abbau von oberflächennahen Rohstoffen mit einer beanspruchten Gesamtfläche von 5 Hektar oder mehr'),
	(3060, 'Errichtung oder wesentliche Änderung einer Anlage, die der Genehmigung in einem Verfahren unter Einbeziehung der Öffentlichkeit nach § 4 Bundes-Immissionsschutzgesetz bedarf, soweit nicht bereits an anderer Stelle dieser Liste erfaßt'),
	(3070, 'Errichtung von Einzelhandelseinrichtungen mit mehr als 700 Quadratmetern Verkaufsfläche oder mehr als 1.000 Quadratmetern Geschoßfläche sowie Nutzungsänderungen von Gebäuden zu Einzelhandelseinrichtungen dieser Größe'),
	(4000, 'Tourismus und Naherholung'),
	(4010, 'Errichtung und Erweiterung von größeren Freizeiteinrichtungen wie Golfplätzen, Reitanlagen, Wassersportanlagen, Spaß- und Freizeitbädern, Zelt- und Campingplätzen sowie Wochenend- und Ferienhausgebieten'),
	(4020, 'Errichtung und Erweiterung größerer Beherbergungsbetriebe wie Hotels oder Pensionen mit mehr als 100 Betten'),
	(5000, 'Soziale und kulturelle Infrastruktur'),
	(5010, 'Schulentwicklungspläne'),
	(5020, 'Neubau oder wesentliche Kapazitätsveränderung von Krankenhäusern und sonstigen Sozialeinrichtungen (Altenheime, Behinderteneinrichtungen'),
	(5030, 'Neubau oder wesentliche Kapazitätsveränderung von Schulen, Theatern, Bibliotheken, Volkshochschulen, Volksbildungswerken und Museen'),
	(5040, 'Neubau oder wesentliche Kapazitätsveränderung von großen Sporteinrichtungen wie Mehrfachsporthallen, Schwimmbädern, Leistungszentren'),
	(6000, 'Verkehr'),
	(6010, 'Generalverkehrspläne'),
	(6020, 'Nahverkehrspläne'),
	(6030, 'Bau und wesentliche Änderung von Anlagen und Einrichtungen des Straßen-, Schienen-, Wasser- oder Luftverkehrs'),
	(6040, 'Festlegung und Aufhebung von Lärm-, Sicherheits- und Bauschutzbereichen für Flugplätze'),
	(7000, 'Sonstige technische Infrastruktur'),
	(7010, 'Festsetzung und Aufhebung von Überschwemmungsgebieten'),
	(7020, 'Neubau und wesentliche Veränderung von Richtfunk- und Fernsehsendeanlagen, Richtfunkstrecken, Telekommunikationsanlagen'),
	(7030, 'Mobilfunk- und Fernmeldetürme ab 20 Metern Höhe'),
	(7040, 'Überörtliche Wasserversorgungsanlagen und Abwasserbehandlungsanlagen'),
	(7050, 'Herstellung, Beseitigung und wesentliche Umgestaltung eines Gewässers oder seiner Ufer, die einer Planfeststellung nach § 31 Wasserhaushaltsgesetz bedürfen, sowie von Häfen, Deich- und Dammbauten und Anlagen zur Landgewinnung am Meer'),
	(7060, 'Errichtung und wesentliche Trassenänderung einer Rohrleitungsanlage zum Befördern wassergefährdender Stoffe, die der Genehmigung nach § 19 a Wasserhaushaltsgesetz bedürfen'),
	(7070, 'Errichtung von Freileitungen mit 110 Kilovolt und mehr Nennspannung, von Gasleitungen mit einem Betriebsüberdruck von 16 bar und mehr sowie von überörtlichen Fernwärmeleitungen'),
	(7080, 'Errichtung von Windenergieanlagen mit einer Gesamthöhe (einschließlich Rotorspitze) von mehr als 35 m'),
	(7090, 'Errichtung und wesentliche Änderung einer Abfallentsorgungsanlage, die der Genehmigung in einem Verfahren unter Einbeziehung der Öffentlichkeit nach § 4 Bundes-Immissionsschutzgesetz bedarf'),
	(7100, 'Errichtung und wesentliche Kapazitätsveränderung einer Deponie'),
	(7110, 'Standortplanungen für Behörden mit mehr als 50 Mitarbeitern'),
	(8000, 'Verteidigung und Konversion'),
	(8010, 'Festlegung und Aufhebung von militärischen Schutzbereichen'),
	(8020, 'Neubau und wesentliche Änderung von Anlagen der öffentlichen Sicherheit und der Verteidigung');

	CREATE TYPE xplankonverter.az_anzeigeexternereferenz AS (
		georefurl character varying,
		georefmimetype xplan_gml.xp_mimetypes,
		art xplan_gml.xp_externereferenzart,
		informationssystemurl character varying,
		referenzname character varying,
		referenzurl character varying,
		referenzmimetype xplan_gml.xp_mimetypes,
		beschreibung character varying,
		datum date,
		typ xplankonverter.az_anzeigereferenztyp
	);
	COMMENT ON COLUMN xplankonverter.az_anzeigeexternereferenz.typ IS 'Der Typ der Planungsunterlagen. Zur Auswahl stehen die Werte aus der Aufzählung az_anzeigereferenztyp nach Anlage 1 Anzeigenerlass';

	CREATE TABLE xplankonverter.az_anzeige (
		gml_id uuid NOT NULL DEFAULT uuid_generate_v1mc() Primary Key,
		konvertierung_id integer,
		created_at timestamp without time zone NOT NULL DEFAULT now(),
		updated_at timestamp without time zone NOT NULL DEFAULT now(),
		name character varying NOT NULL,
		beschreibung text,
		gemeinde xplan_gml.xp_gemeinde[] NOT NULL,
		anzeigepflicht xplankonverter.az_anzeigepflicht NOT NULL,
		verfahren xplankonverter.az_anzeigeverfahren NOT NULL,
		planungsabsichtsart xplankonverter.az_planungsabsichtsart NOT NULL,
		externereferenz xplankonverter.az_anzeigeexternereferenz[] NOT NULL,
		raeumlichergeltungsbereich geometry(MultiPolygon) NOT NULL,
		zeitpunkt date NOT NULL,
		privatervorhabentraeger boolean
	) WITH ( OIDS = TRUE );
	COMMENT ON COLUMN xplankonverter.az_anzeige.name IS 'Bezeichnung der Anzeige. Der Name wird als Überschrift bei Veröffentlichungen und für die Suche verwendet. Der Text ist frei sollte aber möglichst keine Angaben der anderen Attribute enthalten';
	COMMENT ON COLUMN xplankonverter.az_anzeige.beschreibung IS 'Freitext zur Beschreibung der Anzeige oder des Vorhabens';
	COMMENT ON COLUMN xplankonverter.az_anzeige.gemeinde IS 'Eine oder mehrere anzeigende Gemeinden';
	COMMENT ON COLUMN xplankonverter.az_anzeige.anzeigepflicht IS 'Art der Anzeigepflicht. Enthält einen Wert aus der Aufzählung az_anzeigepflicht §21 oder §24';
	COMMENT ON COLUMN xplankonverter.az_anzeige.verfahren IS 'Name des Anzeigeverfahrens. Zur Auswahl stehen zwei Verfahren aus der Aufzählung az_anzeigeverfahren.';
	COMMENT ON COLUMN xplankonverter.az_anzeige.planungsabsichtsart IS 'Art der Planungsabsicht. Danach richtet sich welche Unterlagen eingereicht werden müssen. Zur Auswahl stehen die Werte aus der Aufzählung az_planungsabsichtsart';
	COMMENT ON COLUMN xplankonverter.az_anzeige.externereferenz IS 'Verweis auf ein oder mehrere Planungsunterlagen vom Typ az_anzeigeexternereferenz[].';
	COMMENT ON COLUMN xplankonverter.az_anzeige.raeumlichergeltungsbereich IS 'Die Geometrie von dem räumlichen Gebiet für die die Anzeige gilt. Das kann ein Polygon oder Multipolygon sein.';
	COMMENT ON COLUMN xplankonverter.az_anzeige.zeitpunkt IS 'Das Datum zu dem die Anzeige veröffentlicht wurde bzw. gelten soll.';
COMMIT;
