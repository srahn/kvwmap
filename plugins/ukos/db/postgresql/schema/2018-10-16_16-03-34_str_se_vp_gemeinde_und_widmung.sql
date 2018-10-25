BEGIN;

	-- Tabelle widmung in Schema okstra anlegen und erweitern
	-- muss vor tabelle ukos_strassennetz.strasse angelegt werden
	CREATE TABLE ukos_okstra.widmung (
		geometrie_streckenobjekt geometry('LineString', 25833),
		rechtsgueltig_ab date,
		widmung character varying NOT NULL DEFAULT 'nicht gewidmet',
		CONSTRAINT pk_widmung PRIMARY KEY (id)
	)
	INHERITS (ukos_base.basisobjekt)
	WITH (OIDS = TRUE);

	INSERT INTO ukos_okstra.widmung (id, ident_hist, widmung, bemerkung, gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von)
	SELECT id, ident_hist, widmung, bemerkung, gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von
	FROM ukos_strassennetz.widmung
	WHERE id = '00000000-0000-0000-0000-000000000000';

	-- Tabelle strasse in Schema okstra anlegen und erweitern
	-- muss vor ALTER ukos_okstra.strassenelement angelegt werden, wegen fk_strassenelement_strasse
	CREATE TABLE ukos_okstra.strasse (
		id_gemeinde character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		id_widmung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		hat_strassenbezeichnung_strassenklasse character varying, -- part of complex datatype strassenbezeichnung. If strassennummer, zusatzbuchstabe or identifizierungskennzeichen have a value, strassenklasse must have a value as well
		hat_strassenbezeichnung_strassennummer character varying, -- part of complex datatype strassenbezeichnung. If strassenklasse, zusatzbuchstabe or identifizierungskennzeichen have a value, strassennummer must have a value as well
		hat_strassenbezeichnung_zusatzbuchstabe character varying, -- part of complex datatype strassenbezeichnung
		hat_strassenbezeichnung_identifizierungskennzeichen character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying, -- part of complex datatype strassenbezeichnung
		bezeichnung character varying NOT NULL DEFAULT 'nicht zugewiesen'::character varying,
		schluessel character(5) NOT NULL DEFAULT '00000'::character varying,
		nachrichtlich boolean NOT NULL DEFAULT false,
		kennung character varying,
		CONSTRAINT pk_strasse PRIMARY KEY (id),
		CONSTRAINT fk_strasse_gemeinde FOREIGN KEY (id_gemeinde)
				REFERENCES ukos_kataster.gemeinde (id) MATCH SIMPLE
				ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_strasse_widmung FOREIGN KEY (id_widmung)
				REFERENCES ukos_okstra.widmung (id) MATCH SIMPLE
				ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT uk_strasse UNIQUE (id_gemeinde, schluessel, gueltig_bis),
		CONSTRAINT fk_strasse_hat_strassenbezeichnung_strassenklasse FOREIGN KEY (hat_strassenbezeichnung_strassenklasse)
			REFERENCES ukos_okstra.wlo_strassenklasse (kennung) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_base.basisobjekt)
	WITH (OIDS = TRUE);

	INSERT INTO ukos_okstra.strasse (id, id_gemeinde, id_widmung, ident_hist, bezeichnung, schluessel, bemerkung, gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von, nachrichtlich, kennung)
	SELECT id, id_gemeinde, id_widmung, ident_hist, bezeichnung, schluessel, bemerkung, gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von, nachrichtlich, kennung
	FROM ukos_strassennetz.strasse
	WHERE id = '00000000-0000-0000-0000-000000000000';

	-- Tabelle verbindungspunkt in Schema okstra erweitern
	-- wkb_geometry ist punktgeometrie
	-- muss vor ALTER ukos_okstra.strassenelement angelegt werden, wegen fk_strassenelement_verbindungspunkt_oben und _unten
	ALTER TABLE ukos_okstra.verbindungspunkt
		ADD COLUMN ident character(6) NOT NULL,
		ADD COLUMN nachrichtlich boolean NOT NULL DEFAULT false,
		ADD COLUMN id_strasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD CONSTRAINT pk_verbindungspunkt PRIMARY KEY (id);

	-- Trigger: tr_idents_add_ident on ukos_okstra.verbindungspunkt
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT
		ON ukos_okstra.verbindungspunkt
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_add_ident();

	-- Trigger: tr_idents_remove_ident on okstra.verbindungspunkt
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE
		ON ukos_okstra.verbindungspunkt
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	INSERT INTO ukos_okstra.verbindungspunkt (id, ident, ident_hist, bemerkung, gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von, nachrichtlich)
	SELECT '00000000-0000-0000-0000-000000000000', ident, ident_hist, 'default Netzknoten', gueltig_von, gueltig_bis, angelegt_am, angelegt_von, geaendert_am, geaendert_von, nachrichtlich
	FROM ukos_strassennetz.verbindungspunkt
	WHERE bemerkung IN ('default Netzknoten oben');

	-- Tabelle strassenelement in Schema okstra erweitern
	-- id_verbindungspunkt_oben ist beginnt_bei_vp
	-- id_verbindungspunkt_unten ist endet_bei_vp
	-- wkb_geometry ist liniengeometrie
	ALTER TABLE ukos_okstra.strassenelement
		ADD COLUMN id_strasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD COLUMN ident character(6) NOT NULL,
		ADD COLUMN id_nutzung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD COLUMN id_klassifizierung character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD COLUMN id_strassennetzlage character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD COLUMN nachrichtlich boolean NOT NULL DEFAULT false,
		ALTER COLUMN beginnt_bei_vp SET NOT NULL,
		ALTER COLUMN beginnt_bei_vp SET DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ALTER COLUMN endet_bei_vp SET NOT NULL,
		ALTER COLUMN endet_bei_vp SET DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD CONSTRAINT pk_strassenelement PRIMARY KEY (id),
		ADD CONSTRAINT fk_strassenelement_klassifizierung FOREIGN KEY (id_klassifizierung)
			REFERENCES ukos_base.wld_klassifizierung (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		ADD CONSTRAINT fk_strassenelement_nutzung FOREIGN KEY (id_nutzung)
			REFERENCES ukos_base.wld_nutzung (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		ADD CONSTRAINT fk_strassenelement_strasse FOREIGN KEY (id_strasse)
			REFERENCES ukos_okstra.strasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		ADD CONSTRAINT fk_strassenelement_strassennetzlage FOREIGN KEY (id_strassennetzlage)
			REFERENCES ukos_base.wld_strassennetzlage (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		ADD CONSTRAINT fk_strassenelement_beginnt_bei_vp FOREIGN KEY (beginnt_bei_vp)
			REFERENCES ukos_okstra.verbindungspunkt (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		ADD CONSTRAINT fk_strassenelement_endet_bei_vp FOREIGN KEY (endet_bei_vp)
			REFERENCES ukos_okstra.verbindungspunkt (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION;

	-- Trigger: tr_idents_add_ident on ukos_okstra.strassenelement
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT
		ON ukos_okstra.strassenelement
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_add_ident();

	-- Trigger: tr_idents_remove_ident on okstra.strassenelement
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE
		ON ukos_okstra.strassenelement
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	-- Tabelle strassenelementpunkt in Schema okstra erweitern
	ALTER TABLE ukos_okstra.strassenelementpunkt
		ADD COLUMN id_strasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ADD COLUMN ident character(6) NOT NULL,
		ADD CONSTRAINT fk_strassenelementpunkt_strasse FOREIGN KEY (id_strasse)
			REFERENCES ukos_okstra.strasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,

	-- Trigger: tr_idents_add_ident on ukos_okstra.strassenelementpunkt
	CREATE TRIGGER tr_idents_add_ident
		BEFORE INSERT
		ON ukos_okstra.strassenelementpunkt
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_add_ident();

	-- Trigger: tr_idents_remove_ident on okstra.strassenelementpunkt
	CREATE TRIGGER tr_idents_remove_ident
		AFTER DELETE
		ON ukos_okstra.strassenelementpunkt
		FOR EACH ROW
		EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	CREATE TABLE ukos_base.config (
		key character varying,
		value text,
		default_value text,
		type character varying,
		description text,
		CONSTRAINT pk_config PRIMARY KEY (key)
	);

	INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES
		('Toplogietolerance', 0.1, 0.1, 'numeric', 'Legt die Toleranz bei der Erzeugung der Topologie fest. Gilt auch als Fangradius für Punkte und Punkte auf Linien. Einheit in Meter');
	INSERT INTO ukos_base.config (key, value, default_value, type, description) VALUES
		('Koordinatengenauigkeit', 0.0001, 0.0001, 'numeric', 'Legt die Genauigkeit der im System verwendeten Koordinaten fest. Die Geometrien aller erzeugten Objekte werden vor dem Speichern mit ST_SnapToGrid auf diese Genauigkeit gerundet. Dadurch wird ein exakter vergleich von Koordinaten in binärer und Textschreibweise möglich. ST_Equals(ST_MakePoint(500000, 6000000), ST_MakePoint(500000.00000000001, 6000000) ist true, ST_Equals(ST_MakePoint(500000, 6000000), ST_MakePoint(500000.0000000001, 6000000) ist false, Ist der Wert NULL, wird ST_SnapToGrid nicht angewendet, Einheit in Meter');

	-- DROP schema strassennetz
	DROP SCHEMA ukos_strassennetz CASCADE;

COMMIT;