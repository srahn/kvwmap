BEGIN;

	DROP TABLE ukos_doppik.bankett;
	CREATE TABLE ukos_doppik.bankett (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_bankett_id PRIMARY KEY (id),
		CONSTRAINT fk_bankett_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.bankett
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.bankett
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.baumscheibe;
	CREATE TABLE ukos_doppik.baumscheibe (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_baumscheibe_id PRIMARY KEY (id),
		CONSTRAINT fk_baumscheibe_deckschicht FOREIGN KEY (deckschicht)
		REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.baumscheibe
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.baumscheibe
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.bord_flaeche;
	CREATE TABLE ukos_doppik.bord_flaeche (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_bord_flaeche_id PRIMARY KEY (id),
		CONSTRAINT fk_bord_flaeche_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_bord_flaeche_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_bord_flaeche_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.bord_flaeche
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.bord_flaeche
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.dammschuettung;
	CREATE TABLE ukos_doppik.dammschuettung (
		flaecheninhalt NUMERIC,
		standort character varying,
		material character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		laenge numeric,
		dammfussbreite numeric,
		dammkronenbreite numeric,
		hoehe numeric,
		zweck character varying,
	-- Geerbt from table :	ident character(6),
		CONSTRAINT pk_dammschuettung_id PRIMARY KEY (id),
		CONSTRAINT fk_dammschuettung_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.dammschuettung
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.dammschuettung
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.fahrbahn;
	CREATE TABLE ukos_doppik.fahrbahn (
		flaecheninhalt NUMERIC,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		von_netzknoten character varying,
		nach_netzknoten character varying,
		anzahl_fahrspuren_in_fahrtrichtung integer,
		anzahl_fahrspuren_in_gegenrichtung integer,
		CONSTRAINT pk_fahrbahn_id PRIMARY KEY (id),
		CONSTRAINT fk_fahrbahn_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_fahrbahn_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_fahrbahn_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.dammschuettung
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.dammschuettung
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.gehweg;
	CREATE TABLE ukos_doppik.gehweg (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_gehweg_id PRIMARY KEY (id),
		CONSTRAINT fk_gehweg_bauklasse FOREIGN KEY (bauklasse)
		REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_gehweg_deckschicht FOREIGN KEY (deckschicht)
		REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
		ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.gehweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.gehweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.hecke;
	CREATE TABLE ukos_doppik.hecke (
		flaecheninhalt numeric,
		laenge numeric,
		breite numeric,
		hoehe numeric,
		CONSTRAINT pk_hecke_id PRIMARY KEY (id)
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.hecke
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.hecke
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.parkplatz;
	CREATE TABLE ukos_doppik.parkplatz (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_parkplatz_id PRIMARY KEY (id),
		CONSTRAINT fk_parkplatz_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_parkplatz_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_parkplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.parkplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.parkplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.platz;
	CREATE TABLE ukos_doppik.platz (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_platz_id PRIMARY KEY (id),
		CONSTRAINT fk_platz_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_platz_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_platz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.platz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.platz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.parkstreifen;
	CREATE TABLE ukos_doppik.parkstreifen (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_parkstreifen_id PRIMARY KEY (id),
		CONSTRAINT fk_parkstreifen_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_parkstreifen_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_parkstreifen_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.parkstreifen
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.parkstreifen
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.rad_und_gehweg;
	CREATE TABLE ukos_doppik.rad_und_gehweg (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_rad_und_gehweg_id PRIMARY KEY (id),
		CONSTRAINT fk_rad_und_gehweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_rad_und_gehweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_rad_und_gehweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.rad_und_gehweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.rad_und_gehweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.radweg;
	CREATE TABLE ukos_doppik.radweg (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_radweg_id PRIMARY KEY (id),
		CONSTRAINT fk_radweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_radweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_radweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.radweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.radweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.strassengraben;
	CREATE TABLE ukos_doppik.strassengraben (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_strassengraben_id PRIMARY KEY (id),
		CONSTRAINT fk_strassengraben_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_strassengraben_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_strassengraben_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.strassengraben
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.strassengraben
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.sonstige_flaeche;
	CREATE TABLE ukos_doppik.sonstige_flaeche (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_sonstige_flaeche_id PRIMARY KEY (id),
		CONSTRAINT fk_sonstige_flaeche_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_sonstige_flaeche_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_sonstige_flaeche_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.sonstige_flaeche
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.sonstige_flaeche
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.spielplatz;
	CREATE TABLE ukos_doppik.spielplatz (
		flaecheninhalt NUMERIC,
		standort character varying,
		material character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		zweck character varying,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_spielplatz_id PRIMARY KEY (id),
		CONSTRAINT fk_spielplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_spielplatz_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.spielplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.spielplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.sportplatz;
	CREATE TABLE ukos_doppik.sportplatz (
		flaecheninhalt NUMERIC,
		standort character varying,
		material character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		zweck character varying,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_sportplatz_id PRIMARY KEY (id),
		CONSTRAINT fk_sportplatz_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_sportplatz_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.sportplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.sportplatz
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.strasse;
	CREATE TABLE ukos_doppik.strasse (
		flaecheninhalt NUMERIC,
		standort character varying,
		material character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		zweck character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_strasse_id PRIMARY KEY (id),
		CONSTRAINT fk_strasse_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_strasse_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_strasse_material FOREIGN KEY (material)
			REFERENCES ukos_base.wld_material (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.strasse
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.strasse
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.ueberfahrt;
	CREATE TABLE ukos_doppik.ueberfahrt (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_ueberfahrt_id PRIMARY KEY (id),
		CONSTRAINT fk_ueberfahrt_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_ueberfahrt_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_ueberfahrt_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.ueberfahrt
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.ueberfahrt
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

	DROP TABLE ukos_doppik.ueberweg;
	CREATE TABLE ukos_doppik.ueberweg (
		flaecheninhalt numeric,
		deckschicht character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		ausbauzustand character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		bauklasse character varying NOT NULL DEFAULT '00000000-0000-0000-0000-000000000000'::character varying,
		CONSTRAINT pk_ueberweg_id PRIMARY KEY (id),
		CONSTRAINT fk_ueberweg_ausbauzustand FOREIGN KEY (ausbauzustand)
			REFERENCES ukos_base.wld_zustand (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_ueberweg_bauklasse FOREIGN KEY (bauklasse)
			REFERENCES ukos_base.wld_bauklasse (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION,
		CONSTRAINT fk_ueberweg_deckschicht FOREIGN KEY (deckschicht)
			REFERENCES ukos_base.wld_deckschicht (id) MATCH SIMPLE
			ON UPDATE NO ACTION ON DELETE NO ACTION
	)
	INHERITS (ukos_okstra.verkehrsflaeche)
	WITH (
		OIDS=TRUE
	);
	CREATE TRIGGER tr_idents_add_ident
	  BEFORE INSERT
	  ON ukos_doppik.ueberweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_add_ident();
	CREATE TRIGGER tr_idents_remove_ident
	  AFTER DELETE
	  ON ukos_doppik.ueberweg
	  FOR EACH ROW
	  EXECUTE PROCEDURE ukos_base.idents_remove_ident();

COMMIT;