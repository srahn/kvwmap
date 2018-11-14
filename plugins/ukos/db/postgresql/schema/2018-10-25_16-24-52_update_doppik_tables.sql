BEGIN;

/*
select
	sql
from
	(
		SELeCT
			1 as id,
			'ALTER TABLE ' || table_schema || '.' || table_name || ' RENAME ident TO ident_alt;' AS sql
		FROM
			information_schema.columns
		WHERE
			table_schema LIKE 'ukos_%' AND
			column_name = 'ident' AND
			table_name != 'idents'
		UNION
		SELECT
			2 as id,
			'ALTER TABLE ukos_base.basisobjekt ADD COLUMN ident CHARACTER(6);' sql
		UNION
		SELECT
			3 as id,
			'UPDATE ' || table_schema || '.' || table_name || ' SET ident = ident_alt;' sql
		FROM
			information_schema.columns
		WHERE
			table_schema LIKE 'ukos_%' AND
			column_name = 'ident' AND
			table_name != 'idents'
		UNION
		SELECT
			4 as id,
			'ALTER TABLE ' || table_schema || '.' || table_name || ' DROP ident_alt;' sql
		FROM
			information_schema.columns
		WHERE
			table_schema LIKE 'ukos_%' AND
			column_name = 'ident' AND
			table_name != 'idents'
	) as foo
order by
	id
*/
	ALTER TABLE ukos_doppik.tor RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.gehweg RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.sonstige_flaeche RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.schacht RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.verkehrsspiegel RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.spundwand RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.dueker RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.rad_und_gehweg RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.fahrradstaender RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.vorwegweiser RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.schutzplanke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.mauer RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.dammschuettung RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.baum RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.kabelkasten RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.strassengraben RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bank RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.papierkorb RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.gelaender RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bruecke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.kunstwerk RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.stuetzbauwerk RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.dalben RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.sonstiges_punktobjekt RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.lampe RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.parkscheinautomat RENAME ident TO ident_alt;
	ALTER TABLE ukos_okstra.strassenelementpunkt RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bewuchs RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.tunnel RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.ueberdachung_fahrradstaender RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.gruenflaeche RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.parkplatz RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.strasse RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.blumenkuebel RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.leitpfosten RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.rinne RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.ueberfahrt RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.fahrbahn RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.kilometerstein RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.loeschwasserentnahmestelle_saugstutzen RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.wartestelle RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.hinweistafel RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.sportplatz RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.klaeranlage RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.spielplatz RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.platz RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.sonstige_linie RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bankett RENAME ident TO ident_alt;
	ALTER TABLE ukos_okstra.verbindungspunkt RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.hydrant RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.turm RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.markierung RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.medien RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.ueberweg RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.parkstreifen RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.leitung RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.einlauf RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bord_strecke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.laermschutzbauwerk RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.anleger RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.spielgeraet RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.verkehrszeichenbruecke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.schaukasten RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.ampel RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.anschlagsaeule RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.infoterminal RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.mast RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.zaun RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.bord_flaeche RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.hecke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.kabelschacht RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.haltestelle RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.ueberwachungsanlage RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.auslauf RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.leitplanke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.baumscheibe RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.uhr RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.schild RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.wehr RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.strassenablauf RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.poller RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.durchlass RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.brunnen RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.stationaere_geschwindigkeitsueberwachung RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.telefon RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.aufstellvorrichtung_schild RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.fahne RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.denkmal RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.schranke RENAME ident TO ident_alt;
	ALTER TABLE ukos_doppik.radweg RENAME ident TO ident_alt;
	ALTER TABLE ukos_okstra.strassenelement RENAME ident TO ident_alt;
	ALTER TABLE ukos_base.basisobjekt ADD COLUMN ident CHARACTER(6);
	UPDATE ukos_doppik.infoterminal SET ident = ident_alt;
	UPDATE ukos_doppik.bewuchs SET ident = ident_alt;
	UPDATE ukos_doppik.dalben SET ident = ident_alt;
	UPDATE ukos_doppik.stationaere_geschwindigkeitsueberwachung SET ident = ident_alt;
	UPDATE ukos_doppik.sportplatz SET ident = ident_alt;
	UPDATE ukos_doppik.papierkorb SET ident = ident_alt;
	UPDATE ukos_doppik.fahrbahn SET ident = ident_alt;
	UPDATE ukos_okstra.verbindungspunkt SET ident = ident_alt;
	UPDATE ukos_doppik.ueberweg SET ident = ident_alt;
	UPDATE ukos_doppik.vorwegweiser SET ident = ident_alt;
	UPDATE ukos_doppik.anleger SET ident = ident_alt;
	UPDATE ukos_doppik.lampe SET ident = ident_alt;
	UPDATE ukos_doppik.bruecke SET ident = ident_alt;
	UPDATE ukos_doppik.baum SET ident = ident_alt;
	UPDATE ukos_doppik.turm SET ident = ident_alt;
	UPDATE ukos_doppik.schranke SET ident = ident_alt;
	UPDATE ukos_doppik.ampel SET ident = ident_alt;
	UPDATE ukos_doppik.ueberdachung_fahrradstaender SET ident = ident_alt;
	UPDATE ukos_doppik.leitung SET ident = ident_alt;
	UPDATE ukos_doppik.hecke SET ident = ident_alt;
	UPDATE ukos_doppik.spundwand SET ident = ident_alt;
	UPDATE ukos_okstra.strassenelement SET ident = ident_alt;
	UPDATE ukos_doppik.parkscheinautomat SET ident = ident_alt;
	UPDATE ukos_doppik.bord_flaeche SET ident = ident_alt;
	UPDATE ukos_doppik.spielplatz SET ident = ident_alt;
	UPDATE ukos_doppik.ueberwachungsanlage SET ident = ident_alt;
	UPDATE ukos_doppik.wehr SET ident = ident_alt;
	UPDATE ukos_doppik.aufstellvorrichtung_schild SET ident = ident_alt;
	UPDATE ukos_doppik.wartestelle SET ident = ident_alt;
	UPDATE ukos_doppik.gelaender SET ident = ident_alt;
	UPDATE ukos_doppik.tunnel SET ident = ident_alt;
	UPDATE ukos_doppik.denkmal SET ident = ident_alt;
	UPDATE ukos_doppik.poller SET ident = ident_alt;
	UPDATE ukos_doppik.stuetzbauwerk SET ident = ident_alt;
	UPDATE ukos_doppik.tor SET ident = ident_alt;
	UPDATE ukos_doppik.kabelschacht SET ident = ident_alt;
	UPDATE ukos_doppik.fahrradstaender SET ident = ident_alt;
	UPDATE ukos_doppik.gruenflaeche SET ident = ident_alt;
	UPDATE ukos_doppik.parkstreifen SET ident = ident_alt;
	UPDATE ukos_doppik.gehweg SET ident = ident_alt;
	UPDATE ukos_doppik.laermschutzbauwerk SET ident = ident_alt;
	UPDATE ukos_doppik.bankett SET ident = ident_alt;
	UPDATE ukos_doppik.schutzplanke SET ident = ident_alt;
	UPDATE ukos_doppik.uhr SET ident = ident_alt;
	UPDATE ukos_doppik.brunnen SET ident = ident_alt;
	UPDATE ukos_doppik.bord_strecke SET ident = ident_alt;
	UPDATE ukos_doppik.strassengraben SET ident = ident_alt;
	UPDATE ukos_doppik.blumenkuebel SET ident = ident_alt;
	UPDATE ukos_doppik.mauer SET ident = ident_alt;
	UPDATE ukos_okstra.strassenelementpunkt SET ident = ident_alt;
	UPDATE ukos_doppik.ueberfahrt SET ident = ident_alt;
	UPDATE ukos_doppik.schaukasten SET ident = ident_alt;
	UPDATE ukos_doppik.baumscheibe SET ident = ident_alt;
	UPDATE ukos_doppik.spielgeraet SET ident = ident_alt;
	UPDATE ukos_doppik.rad_und_gehweg SET ident = ident_alt;
	UPDATE ukos_doppik.haltestelle SET ident = ident_alt;
	UPDATE ukos_doppik.loeschwasserentnahmestelle_saugstutzen SET ident = ident_alt;
	UPDATE ukos_doppik.bank SET ident = ident_alt;
	UPDATE ukos_doppik.verkehrsspiegel SET ident = ident_alt;
	UPDATE ukos_doppik.schacht SET ident = ident_alt;
	UPDATE ukos_doppik.telefon SET ident = ident_alt;
	UPDATE ukos_doppik.einlauf SET ident = ident_alt;
	UPDATE ukos_doppik.dammschuettung SET ident = ident_alt;
	UPDATE ukos_doppik.zaun SET ident = ident_alt;
	UPDATE ukos_doppik.strasse SET ident = ident_alt;
	UPDATE ukos_doppik.medien SET ident = ident_alt;
	UPDATE ukos_doppik.verkehrszeichenbruecke SET ident = ident_alt;
	UPDATE ukos_doppik.kabelkasten SET ident = ident_alt;
	UPDATE ukos_doppik.rinne SET ident = ident_alt;
	UPDATE ukos_doppik.klaeranlage SET ident = ident_alt;
	UPDATE ukos_doppik.fahne SET ident = ident_alt;
	UPDATE ukos_doppik.kunstwerk SET ident = ident_alt;
	UPDATE ukos_doppik.schild SET ident = ident_alt;
	UPDATE ukos_doppik.parkplatz SET ident = ident_alt;
	UPDATE ukos_doppik.hinweistafel SET ident = ident_alt;
	UPDATE ukos_doppik.auslauf SET ident = ident_alt;
	UPDATE ukos_doppik.leitplanke SET ident = ident_alt;
	UPDATE ukos_doppik.sonstige_flaeche SET ident = ident_alt;
	UPDATE ukos_doppik.anschlagsaeule SET ident = ident_alt;
	UPDATE ukos_doppik.leitpfosten SET ident = ident_alt;
	UPDATE ukos_doppik.platz SET ident = ident_alt;
	UPDATE ukos_doppik.hydrant SET ident = ident_alt;
	UPDATE ukos_doppik.strassenablauf SET ident = ident_alt;
	UPDATE ukos_doppik.markierung SET ident = ident_alt;
	UPDATE ukos_doppik.kilometerstein SET ident = ident_alt;
	UPDATE ukos_doppik.dueker SET ident = ident_alt;
	UPDATE ukos_doppik.durchlass SET ident = ident_alt;
	UPDATE ukos_doppik.radweg SET ident = ident_alt;
	UPDATE ukos_doppik.sonstige_linie SET ident = ident_alt;
	UPDATE ukos_doppik.sonstiges_punktobjekt SET ident = ident_alt;
	UPDATE ukos_doppik.mast SET ident = ident_alt;
	ALTER TABLE ukos_doppik.schacht DROP ident_alt;
	ALTER TABLE ukos_doppik.leitplanke DROP ident_alt;
	ALTER TABLE ukos_doppik.strasse DROP ident_alt;
	ALTER TABLE ukos_doppik.ueberfahrt DROP ident_alt;
	ALTER TABLE ukos_doppik.bruecke DROP ident_alt;
	ALTER TABLE ukos_doppik.kabelschacht DROP ident_alt;
	ALTER TABLE ukos_doppik.hinweistafel DROP ident_alt;
	ALTER TABLE ukos_doppik.vorwegweiser DROP ident_alt;
	ALTER TABLE ukos_doppik.schaukasten DROP ident_alt;
	ALTER TABLE ukos_doppik.schutzplanke DROP ident_alt;
	ALTER TABLE ukos_doppik.einlauf DROP ident_alt;
	ALTER TABLE ukos_doppik.strassenablauf DROP ident_alt;
	ALTER TABLE ukos_doppik.mauer DROP ident_alt;
	ALTER TABLE ukos_doppik.leitpfosten DROP ident_alt;
	ALTER TABLE ukos_doppik.anleger DROP ident_alt;
	ALTER TABLE ukos_doppik.laermschutzbauwerk DROP ident_alt;
	ALTER TABLE ukos_doppik.kilometerstein DROP ident_alt;
	ALTER TABLE ukos_doppik.rad_und_gehweg DROP ident_alt;
	ALTER TABLE ukos_doppik.parkstreifen DROP ident_alt;
	ALTER TABLE ukos_okstra.strassenelement DROP ident_alt;
	ALTER TABLE ukos_doppik.leitung DROP ident_alt;
	ALTER TABLE ukos_doppik.telefon DROP ident_alt;
	ALTER TABLE ukos_doppik.fahrradstaender DROP ident_alt;
	ALTER TABLE ukos_doppik.kabelkasten DROP ident_alt;
	ALTER TABLE ukos_doppik.turm DROP ident_alt;
	ALTER TABLE ukos_doppik.kunstwerk DROP ident_alt;
	ALTER TABLE ukos_doppik.stationaere_geschwindigkeitsueberwachung DROP ident_alt;
	ALTER TABLE ukos_doppik.loeschwasserentnahmestelle_saugstutzen DROP ident_alt;
	ALTER TABLE ukos_doppik.spielgeraet DROP ident_alt;
	ALTER TABLE ukos_doppik.anschlagsaeule DROP ident_alt;
	ALTER TABLE ukos_doppik.sonstige_flaeche DROP ident_alt;
	ALTER TABLE ukos_doppik.verkehrszeichenbruecke DROP ident_alt;
	ALTER TABLE ukos_doppik.hydrant DROP ident_alt;
	ALTER TABLE ukos_doppik.sonstige_linie DROP ident_alt;
	ALTER TABLE ukos_doppik.dalben DROP ident_alt;
	ALTER TABLE ukos_doppik.haltestelle DROP ident_alt;
	ALTER TABLE ukos_doppik.parkscheinautomat DROP ident_alt;
	ALTER TABLE ukos_doppik.brunnen DROP ident_alt;
	ALTER TABLE ukos_doppik.spielplatz DROP ident_alt;
	ALTER TABLE ukos_doppik.auslauf DROP ident_alt;
	ALTER TABLE ukos_doppik.dammschuettung DROP ident_alt;
	ALTER TABLE ukos_doppik.ampel DROP ident_alt;
	ALTER TABLE ukos_doppik.gehweg DROP ident_alt;
	ALTER TABLE ukos_doppik.aufstellvorrichtung_schild DROP ident_alt;
	ALTER TABLE ukos_doppik.bankett DROP ident_alt;
	ALTER TABLE ukos_doppik.bewuchs DROP ident_alt;
	ALTER TABLE ukos_doppik.bord_flaeche DROP ident_alt;
	ALTER TABLE ukos_okstra.strassenelementpunkt DROP ident_alt;
	ALTER TABLE ukos_doppik.poller DROP ident_alt;
	ALTER TABLE ukos_doppik.dueker DROP ident_alt;
	ALTER TABLE ukos_doppik.ueberweg DROP ident_alt;
	ALTER TABLE ukos_doppik.blumenkuebel DROP ident_alt;
	ALTER TABLE ukos_doppik.gruenflaeche DROP ident_alt;
	ALTER TABLE ukos_doppik.tunnel DROP ident_alt;
	ALTER TABLE ukos_doppik.wartestelle DROP ident_alt;
	ALTER TABLE ukos_doppik.bord_strecke DROP ident_alt;
	ALTER TABLE ukos_doppik.durchlass DROP ident_alt;
	ALTER TABLE ukos_doppik.fahrbahn DROP ident_alt;
	ALTER TABLE ukos_doppik.denkmal DROP ident_alt;
	ALTER TABLE ukos_doppik.ueberwachungsanlage DROP ident_alt;
	ALTER TABLE ukos_doppik.papierkorb DROP ident_alt;
	ALTER TABLE ukos_doppik.schranke DROP ident_alt;
	ALTER TABLE ukos_doppik.sportplatz DROP ident_alt;
	ALTER TABLE ukos_doppik.bank DROP ident_alt;
	ALTER TABLE ukos_doppik.sonstiges_punktobjekt DROP ident_alt;
	ALTER TABLE ukos_okstra.verbindungspunkt DROP ident_alt;
	ALTER TABLE ukos_doppik.klaeranlage DROP ident_alt;
	ALTER TABLE ukos_doppik.ueberdachung_fahrradstaender DROP ident_alt;
	ALTER TABLE ukos_doppik.medien DROP ident_alt;
	ALTER TABLE ukos_doppik.spundwand DROP ident_alt;
	ALTER TABLE ukos_doppik.verkehrsspiegel DROP ident_alt;
	ALTER TABLE ukos_doppik.infoterminal DROP ident_alt;
	ALTER TABLE ukos_doppik.zaun DROP ident_alt;
	ALTER TABLE ukos_doppik.strassengraben DROP ident_alt;
	ALTER TABLE ukos_doppik.schild DROP ident_alt;
	ALTER TABLE ukos_doppik.baumscheibe DROP ident_alt;
	ALTER TABLE ukos_doppik.rinne DROP ident_alt;
	ALTER TABLE ukos_doppik.fahne DROP ident_alt;
	ALTER TABLE ukos_doppik.lampe DROP ident_alt;
	ALTER TABLE ukos_doppik.wehr DROP ident_alt;
	ALTER TABLE ukos_doppik.tor DROP ident_alt;
	ALTER TABLE ukos_doppik.gelaender DROP ident_alt;
	ALTER TABLE ukos_doppik.uhr DROP ident_alt;
	ALTER TABLE ukos_doppik.baum DROP ident_alt;
	ALTER TABLE ukos_doppik.hecke DROP ident_alt;
	ALTER TABLE ukos_doppik.radweg DROP ident_alt;
	ALTER TABLE ukos_doppik.platz DROP ident_alt;
	ALTER TABLE ukos_doppik.markierung DROP ident_alt;
	ALTER TABLE ukos_doppik.stuetzbauwerk DROP ident_alt;
	ALTER TABLE ukos_doppik.parkplatz DROP ident_alt;
	ALTER TABLE ukos_doppik.mast DROP ident_alt;

/*
-- Abfragen aller Spalten, die Listen haben ('deckschicht', 'material', 'bauklasse', 'ausbauzustand', 'baumart')
SELECT
	'ALTER TABLE ' || table_schema || '.' || table_name || '
	ALTER ' || column_name || ' SET DEFAULT ''00000000-0000-0000-0000-000000000000'',
	ALTER '|| column_name || ' SET NOT NULL,
	ADD CONSTRAINT fk_' || table_name || '_' || column_name || ' FOREIGN KEY (' || column_name || ')
		REFERENCES ukos_base.wld_' || CASE WHEN column_name = 'ausbauzustand' THEN 'zustand' ELSE column_name END || ' (id) MATCH SIMPLE;
' AS sql
FROM
	information_schema.columns
WHERE
	table_schema LIKE 'ukos_doppik' AND
	column_name IN ('deckschicht', 'material', 'bauklasse', 'ausbauzustand', 'baumart')
ORDER BY table_name

-- Abfragen aller Spalten, die nicht geerbt sind und Listen haben ('deckschicht', 'material', 'bauklasse', 'ausbauzustand', 'baumart')
-- Columns aus Schema ukos_okstra separat angepasst
SELECT
	'ALTER TABLE ' || c.table_schema || '.' || c.table_name || '
	ALTER ' || c.column_name || ' SET DEFAULT ''00000000-0000-0000-0000-000000000000'',
	ALTER '|| c.column_name || ' SET NOT NULL,
	ADD CONSTRAINT fk_' || c.table_name || '_' || c.column_name || ' FOREIGN KEY (' || c.column_name || ')
		REFERENCES ukos_base.wld_' || CASE WHEN c.column_name = 'ausbauzustand' THEN 'zustand' ELSE c.column_name END || ' (id) MATCH SIMPLE;
' AS sql
FROM
(
SELECT
	table_schema,
	table_name,
	column_name
FROM
	information_schema.columns
WHERE
	table_schema LIKE 'ukos_doppik' AND
	column_name IN ('deckschicht', 'material', 'bauklasse', 'ausbauzustand', 'baumart', 'durchlass')
) c JOIN
(
SELECT
	a.table_schema,
	a.table_name,
	a.column_name
FROM
	information_schema.columns a
	LEFT JOIN (
		SELECT
			n.nspname AS table_schema,
			c.relname as table_name,
			a.attname as column_name
		FROM pg_class c
			JOIN pg_inherits i on c.oid = i.inhrelid
			JOIN pg_attribute a on i.inhparent = a.attrelid
			JOIN pg_namespace n ON n.oid = c.relnamespace
		WHERE
			n.nspname LIKE 'ukos_%' AND
			a.attnum > 0
	) inherited ON a.table_schema = inherited.table_schema AND a.table_name = inherited.table_name AND a.column_name = inherited.column_name
WHERE
	a.table_schema LIKE 'ukos_%' AND
	inherited.column_name IS NULL
) n ON c.table_schema = n.table_schema AND c.table_name = n.table_name AND c.column_name = n.column_name
ORDER BY
	c.table_schema,
	c.table_name,
	c.column_name

-- Attribute, die eventuelle auch Aufzählungstypen sind, aber zu denen keine Listen existieren:
	"gelaender";"bauart"
	"lampe";"masttyp"
	"strasse";"zweck"
	"wartestelle";"art_der_haltestelle"
*/

	ALTER TABLE ukos_doppik.ampel
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_ampel_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.anleger
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_anleger_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.anschlagsaeule
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_anschlagsaeule_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.auslauf
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_auslauf_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bank
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_bank_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bankett
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_bankett_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.baumscheibe
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_baumscheibe_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.blumenkuebel
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_blumenkuebel_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bord_flaeche
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_bord_flaeche_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bord_flaeche
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_bord_flaeche_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bord_flaeche
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_bord_flaeche_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bord_strecke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_bord_strecke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.bruecke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_bruecke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.brunnen
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_brunnen_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.dalben
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_dalben_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.dammschuettung
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_dammschuettung_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.denkmal
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_denkmal_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.dueker
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_dueker_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.durchlass
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_durchlass_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.einlauf
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_einlauf_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.fahne
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_fahne_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.fahrbahn
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_fahrbahn_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.fahrbahn
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_fahrbahn_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.fahrbahn
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_fahrbahn_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.fahrradstaender
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_fahrradstaender_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gehweg
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_gehweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gehweg
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_gehweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gelaender
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_gelaender_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gruenflaeche
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_gruenflaeche_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gruenflaeche
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_gruenflaeche_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.gruenflaeche
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_gruenflaeche_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.haltestelle
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_haltestelle_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.hinweistafel
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_hinweistafel_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.hydrant
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_hydrant_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.infoterminal
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_infoterminal_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.kabelkasten
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_kabelkasten_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.kabelschacht
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_kabelschacht_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.kilometerstein
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_kilometerstein_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.klaeranlage
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_klaeranlage_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.kunstwerk
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_kunstwerk_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.laermschutzbauwerk
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_laermschutzbauwerk_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.lampe
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_lampe_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.leitpfosten
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_leitpfosten_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.leitplanke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_leitplanke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.leitung
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_leitung_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.loeschwasserentnahmestelle_saugstutzen
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_loeschwasserentnahmestelle_saugstutzen_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.markierung
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_markierung_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.mast
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_mast_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.mauer
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_mauer_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.medien
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_medien_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.papierkorb
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_papierkorb_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkplatz
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_parkplatz_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkplatz
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_parkplatz_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkplatz
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_parkplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkscheinautomat
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_parkscheinautomat_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkstreifen
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_parkstreifen_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkstreifen
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_parkstreifen_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.parkstreifen
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_parkstreifen_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.platz
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_platz_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.platz
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_platz_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.platz
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_platz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.poller
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_poller_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.rad_und_gehweg
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_rad_und_gehweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.rad_und_gehweg
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_rad_und_gehweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.rad_und_gehweg
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_rad_und_gehweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.radweg
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_radweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.radweg
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_radweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.radweg
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_radweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.rinne
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_rinne_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.schacht
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_schacht_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.schaukasten
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_schaukasten_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.schild
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_schild_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.schranke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_schranke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.schutzplanke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_schutzplanke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sonstige_flaeche
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_sonstige_flaeche_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sonstige_flaeche
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_sonstige_flaeche_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sonstige_flaeche
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_sonstige_flaeche_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sonstige_linie
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_sonstige_linie_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sonstiges_punktobjekt
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_sonstiges_punktobjekt_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.spielgeraet
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_spielgeraet_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.spielplatz
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_spielplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.spielplatz
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_spielplatz_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sportplatz
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_sportplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.sportplatz
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_sportplatz_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.spundwand
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_spundwand_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.stationaere_geschwindigkeitsueberwachung
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_stationaere_geschwindigkeitsueberwachung_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strasse
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_strasse_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strasse
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_strasse_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strasse
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_strasse_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strassenablauf
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_strassenablauf_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strassengraben
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_strassengraben_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strassengraben
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_strassengraben_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.strassengraben
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_strassengraben_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.stuetzbauwerk
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_stuetzbauwerk_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.telefon
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_telefon_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.tor
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_tor_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.tunnel
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_tunnel_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.turm
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_turm_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberdachung_fahrradstaender
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_ueberdachung_fahrradstaender_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberfahrt
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_ueberfahrt_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberfahrt
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_ueberfahrt_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberfahrt
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_ueberfahrt_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberwachungsanlage
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_ueberwachungsanlage_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberweg
		ALTER ausbauzustand SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER ausbauzustand SET NOT NULL,
		ADD CONSTRAINT fk_ueberweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberweg
		ALTER bauklasse SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER bauklasse SET NOT NULL,
		ADD CONSTRAINT fk_ueberweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.ueberweg
		ALTER deckschicht SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER deckschicht SET NOT NULL,
		ADD CONSTRAINT fk_ueberweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.uhr
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_uhr_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.verkehrsspiegel
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_verkehrsspiegel_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.verkehrszeichenbruecke
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_verkehrszeichenbruecke_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.vorwegweiser
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_vorwegweiser_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.wartestelle
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_wartestelle_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.wehr
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_wehr_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

	ALTER TABLE ukos_doppik.zaun
		ALTER material SET DEFAULT '00000000-0000-0000-0000-000000000000',
		ALTER material SET NOT NULL,
		ADD CONSTRAINT fk_zaun_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE;

/*
SELECT
	'DROP TRIGGER IF EXISTS tr_idents_add_ident ON ' || table_schema || '.' || table_name || ';
DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ' || table_schema || '.' || table_name || ';
CREATE TRIGGER tr_idents_add_ident
	BEFORE INSERT ON ' || table_schema || '.' || table_name || '
	FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
CREATE TRIGGER tr_idents_remove_ident
	AFTER DELETE ON ' || table_schema || '.' || table_name || '
	FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();'
FROM
	information_schema.columns
WHERE
	table_schema like 'ukos_%' AND
	column_name = 'ident' AND
	table_name not in ('idents')
ORDER BY
  table_schema,
  table_name
*/

	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_base.basisobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_base.basisobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_base.basisobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_base.basisobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_base.punktobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_base.punktobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_base.punktobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_base.punktobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_base.punktundstreckenobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_base.punktundstreckenobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_base.punktundstreckenobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_base.punktundstreckenobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_base.streckenobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_base.streckenobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_base.streckenobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_base.streckenobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.abfallbehaelter;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.abfallbehaelter;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.abfallbehaelter
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.abfallbehaelter
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.ampel;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.ampel;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.ampel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.ampel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.anleger;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.anleger;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.anleger
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.anleger
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.anschlagsaeule;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.anschlagsaeule;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.anschlagsaeule
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.anschlagsaeule
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.aufstellvorrichtung_schild;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.aufstellvorrichtung_schild;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.aufstellvorrichtung_schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.aufstellvorrichtung_schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.auslauf;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.auslauf;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.auslauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.auslauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bank;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bank;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bank
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bank
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bankett;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bankett;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bankett
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bankett
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.baum;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.baum;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.baum
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.baum
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.baumscheibe;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.baumscheibe;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.baumscheibe
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.baumscheibe
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bewuchs;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bewuchs;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bewuchs
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bewuchs
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.blumenkuebel;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.blumenkuebel;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.blumenkuebel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.blumenkuebel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bord_flaeche;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bord_flaeche;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bord_flaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bord_flaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bord_strecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bord_strecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bord_strecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bord_strecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.bruecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.bruecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.bruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.bruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.brunnen;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.brunnen;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.brunnen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.brunnen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.dalben;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.dalben;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.dalben
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.dalben
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.dammschuettung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.dammschuettung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.dammschuettung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.dammschuettung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.denkmal;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.denkmal;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.denkmal
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.denkmal
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.dueker;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.dueker;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.dueker
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.dueker
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.durchlass;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.durchlass;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.durchlass
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.durchlass
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.einlauf;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.einlauf;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.einlauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.einlauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.fahne;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.fahne;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.fahne
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.fahne
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.fahrbahn;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.fahrbahn;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.fahrbahn
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.fahrbahn
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.fahrradstaender;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.fahrradstaender;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.fahrradstaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.fahrradstaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.gehweg;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.gehweg;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.gehweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.gehweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.gelaender;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.gelaender;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.gelaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.gelaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.gruenflaeche;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.gruenflaeche;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.gruenflaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.gruenflaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.haltestelle;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.haltestelle;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.haltestelle
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.haltestelle
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.hecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.hecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.hecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.hecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.hinweistafel;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.hinweistafel;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.hinweistafel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.hinweistafel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.hydrant;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.hydrant;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.hydrant
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.hydrant
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.infoterminal;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.infoterminal;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.infoterminal
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.infoterminal
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.kabelkasten;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.kabelkasten;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.kabelkasten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.kabelkasten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.kabelschacht;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.kabelschacht;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.kabelschacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.kabelschacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.kilometerstein;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.kilometerstein;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.kilometerstein
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.kilometerstein
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.klaeranlage;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.klaeranlage;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.klaeranlage
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.klaeranlage
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.kunstwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.kunstwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.kunstwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.kunstwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.laermschutzbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.laermschutzbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.laermschutzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.laermschutzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.lampe;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.lampe;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.lampe
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.lampe
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.leitpfosten;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.leitpfosten;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.leitpfosten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.leitpfosten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.leitplanke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.leitplanke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.leitplanke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.leitplanke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.leitung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.leitung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.leitung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.leitung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.loeschwasserentnahmestelle_saugstutzen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.markierung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.markierung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.markierung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.markierung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.mast;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.mast;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.mast
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.mast
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.mauer;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.mauer;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.mauer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.mauer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.medien;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.medien;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.medien
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.medien
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.papierkorb;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.papierkorb;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.papierkorb
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.papierkorb
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.parkplatz;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.parkplatz;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.parkplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.parkplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.parkscheinautomat;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.parkscheinautomat;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.parkscheinautomat
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.parkscheinautomat
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.parkstreifen;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.parkstreifen;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.parkstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.parkstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.platz;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.platz;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.platz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.platz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.poller;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.poller;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.poller
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.poller
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.rad_und_gehweg;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.rad_und_gehweg;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.rad_und_gehweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.rad_und_gehweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.radweg;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.radweg;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.radweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.radweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.rinne;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.rinne;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.rinne
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.rinne
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.schacht;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.schacht;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.schacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.schacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.schaukasten;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.schaukasten;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.schaukasten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.schaukasten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.schild;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.schild;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.schranke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.schranke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.schranke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.schranke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.schutzplanke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.schutzplanke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.schutzplanke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.schutzplanke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.sonstige_flaeche;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.sonstige_flaeche;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.sonstige_flaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.sonstige_flaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.sonstige_linie;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.sonstige_linie;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.sonstige_linie
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.sonstige_linie
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.sonstiges_punktobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.sonstiges_punktobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.sonstiges_punktobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.sonstiges_punktobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.spielgeraet;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.spielgeraet;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.spielgeraet
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.spielgeraet
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.spielplatz;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.spielplatz;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.spielplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.spielplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.sportplatz;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.sportplatz;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.sportplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.sportplatz
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.spundwand;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.spundwand;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.spundwand
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.spundwand
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.stationaere_geschwindigkeitsueberwachung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.stationaere_geschwindigkeitsueberwachung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.stationaere_geschwindigkeitsueberwachung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.stationaere_geschwindigkeitsueberwachung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.strasse;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.strasse;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.strassenablauf;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.strassenablauf;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.strassenablauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.strassenablauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.strassengraben;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.strassengraben;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.strassengraben
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.strassengraben
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.stuetzbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.stuetzbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.stuetzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.stuetzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.telefon;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.telefon;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.telefon
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.telefon
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.tor;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.tor;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.tor
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.tor
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.tunnel;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.tunnel;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.tunnel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.tunnel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.turm;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.turm;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.turm
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.turm
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.ueberdachung_fahrradstaender;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.ueberdachung_fahrradstaender;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.ueberdachung_fahrradstaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.ueberdachung_fahrradstaender
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.ueberfahrt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.ueberfahrt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.ueberfahrt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.ueberfahrt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.ueberwachungsanlage;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.ueberwachungsanlage;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.ueberwachungsanlage
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.ueberwachungsanlage
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.ueberweg;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.ueberweg;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.ueberweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.ueberweg
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.uhr;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.uhr;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.uhr
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.uhr
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.verkehrsspiegel;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.verkehrsspiegel;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.verkehrsspiegel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.verkehrsspiegel
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.verkehrszeichenbruecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.verkehrszeichenbruecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.verkehrszeichenbruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.verkehrszeichenbruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.vorwegweiser;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.vorwegweiser;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.vorwegweiser
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.vorwegweiser
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.wartestelle;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.wartestelle;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.wartestelle
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.wartestelle
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.wehr;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.wehr;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.wehr
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.wehr
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_doppik.zaun;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_doppik.zaun;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_doppik.zaun
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_doppik.zaun
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.abfallentsorgung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.abfallentsorgung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.abfallentsorgung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.abfallentsorgung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.anzahl_fahrstreifen;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.anzahl_fahrstreifen;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.anzahl_fahrstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.anzahl_fahrstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.aufbauschicht;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.aufbauschicht;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.aufbauschicht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.aufbauschicht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.aufstellvorrichtung_schild;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.aufstellvorrichtung_schild;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.aufstellvorrichtung_schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.aufstellvorrichtung_schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.bahnigkeit;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.bahnigkeit;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.bahnigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.bahnigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.baum;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.baum;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.baum
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.baum
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.belastungsklasse;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.belastungsklasse;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.belastungsklasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.belastungsklasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.bewuchs;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.bewuchs;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.bewuchs
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.bewuchs
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.bruecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.bruecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.bruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.bruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.durchlass;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.durchlass;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.durchlass
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.durchlass
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.durchschnittsgeschwindigkeit;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.durchschnittsgeschwindigkeit;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.durchschnittsgeschwindigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.durchschnittsgeschwindigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.fahrstreifen_nummer;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.fahrstreifen_nummer;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.fahrstreifen_nummer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.fahrstreifen_nummer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.fkt_d_verb_im_knotenpktber;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.fkt_d_verb_im_knotenpktber;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.fkt_d_verb_im_knotenpktber
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.fkt_d_verb_im_knotenpktber
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.gebuehrenpflichtig;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.gebuehrenpflichtig;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.gebuehrenpflichtig
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.gebuehrenpflichtig
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.hausnummer;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.hausnummer;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.hausnummer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.hausnummer
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.kommunikationsobjekt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.kommunikationsobjekt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.kommunikationsobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.kommunikationsobjekt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.komplexer_knoten;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.komplexer_knoten;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.komplexer_knoten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.komplexer_knoten
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.laermschutzbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.laermschutzbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.laermschutzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.laermschutzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.leitung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.leitung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.leitung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.leitung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.organisation;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.organisation;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.organisation
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.organisation
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.organisationseinheit;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.organisationseinheit;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.organisationseinheit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.organisationseinheit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.person;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.person;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.person
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.person
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.querschnittstreifen;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.querschnittstreifen;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.querschnittstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.querschnittstreifen
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.schacht;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.schacht;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.schacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.schacht
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.schild;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.schild;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.schild
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.schutzeinrichtung_aus_stahl;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.schutzeinrichtung_aus_stahl;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.schutzeinrichtung_aus_stahl
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.schutzeinrichtung_aus_stahl
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.segment_kommunale_strasse;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.segment_kommunale_strasse;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.segment_kommunale_strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.segment_kommunale_strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.spur_fuer_rettungsfahrzeuge;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.spur_fuer_rettungsfahrzeuge;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.spur_fuer_rettungsfahrzeuge
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.spur_fuer_rettungsfahrzeuge
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.stadium;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.stadium;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.stadium
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.stadium
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strasse;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strasse;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strasse
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenablauf;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenablauf;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenablauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenablauf
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenausstattung_punkt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenausstattung_punkt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenausstattung_punkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenausstattung_punkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenausstattung_strecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenausstattung_strecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenausstattung_strecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenausstattung_strecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenbeschreibung_verkehrl;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenbeschreibung_verkehrl;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenbeschreibung_verkehrl
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenbeschreibung_verkehrl
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenelement;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenelement;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenelement
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenelement
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenelementpunkt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenelementpunkt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenelementpunkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenelementpunkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.strassenfunktion;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.strassenfunktion;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.strassenfunktion
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.strassenfunktion
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.stuetzbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.stuetzbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.stuetzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.stuetzbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.teilbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.teilbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.teilbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.teilbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.teilelement;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.teilelement;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.teilelement
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.teilelement
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.tunnel_trogbauwerk;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.tunnel_trogbauwerk;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.tunnel_trogbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.tunnel_trogbauwerk
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verbindungspunkt;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verbindungspunkt;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verbindungspunkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verbindungspunkt
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verbotene_fahrbeziehung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verbotene_fahrbeziehung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verbotene_fahrbeziehung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verbotene_fahrbeziehung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verkehrseinschraenkung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verkehrseinschraenkung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verkehrseinschraenkung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verkehrseinschraenkung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verkehrsflaeche;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verkehrsflaeche;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verkehrsflaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verkehrsflaeche
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verkehrsnutzungsbereich;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verkehrsnutzungsbereich;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verkehrsnutzungsbereich
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verkehrsnutzungsbereich
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.verkehrszeichenbruecke;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.verkehrszeichenbruecke;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.verkehrszeichenbruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.verkehrszeichenbruecke
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.widmung;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.widmung;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.widmung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.widmung
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();
	DROP TRIGGER IF EXISTS tr_idents_add_ident ON ukos_okstra.zustaendigkeit;
	DROP TRIGGER IF EXISTS tr_idents_remove_ident ON ukos_okstra.zustaendigkeit;
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT ON ukos_okstra.zustaendigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE ON ukos_okstra.zustaendigkeit
		FOR EACH ROW EXECUTE PROCEDURE ukos_base.idents_remove_ident();

COMMIT