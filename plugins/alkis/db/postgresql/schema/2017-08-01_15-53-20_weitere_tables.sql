BEGIN;

-- Tabelle zum Loggen der Import-Vorgänge

CREATE TABLE alkis.import
(
 id serial NOT NULL,
 datum timestamp without time zone DEFAULT now(),
 datei text,
 status text
)
WITH(
 OIDS=TRUE
);

-- Tabellen für Adressänderungsfunktionalität

CREATE TABLE alkis.ax_person_temp(											-- am 03.06.2015 hinzugefügt
  gml_id character varying(16) NOT NULL,								-- am 03.06.2015 hinzugefügt
  hat character varying NOT NULL,												-- am 03.06.2015 hinzugefügt
	datum timestamp without time zone,										-- am 03.06.2015 hinzugefügt
	user_id integer,																			-- am 03.06.2015 hinzugefügt
  CONSTRAINT ax_person_temp_pk PRIMARY KEY (gml_id)			-- am 03.06.2015 hinzugefügt
)WITH OIDS;																							-- am 03.06.2015 hinzugefügt

CREATE SEQUENCE alkis.ax_anschrift_temp_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE alkis.ax_anschrift_temp(
  gml_id character varying(16) NOT NULL DEFAULT ('DE_'::text || lpad((nextval(('alkis.ax_anschrift_temp_seq'::text)::regclass))::text, 13, '0'::text)),			-- am 08.06.2015 geändert
  ort_post character varying NOT NULL,
  postleitzahlpostzustellung character varying NOT NULL,
  strasse character varying NOT NULL,
  hausnummer character varying NOT NULL,
	ortsteil character varying,						-- am 02.06.2015 hinzugefügt
	datum timestamp without time zone,
	user_id integer,
  CONSTRAINT ax_anschrift_temp_pk PRIMARY KEY (gml_id)
)WITH OIDS;

COMMIT;
