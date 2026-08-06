BEGIN;
  CREATE TABLE IF NOT EXISTS plandigitalisierung.uploads (
    id serial NOT NULL,
    stelle_id integer NOT NULL,
    dateiname varchar NOT NULL,
    uploaded_at timestamp without time zone DEFAULT now() NOT NULL,
    status varchar DEFAULT 'hochgeladen'::character varying NOT NULL,
    ags integer NULL,
    konvertierung_id_new integer NULL,
    konvertierung_id_origin integer NULL,
    message varchar NULL,
    CONSTRAINT uploads_pk PRIMARY KEY (id)
  );

  ALTER TABLE xplankonverter.import_jobs ADD COLUMN IF NOT EXISTS upload_file varchar NULL;

  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN IF NOT EXISTS ersetzt_konvertierung_id integer;

  ALTER TABLE xplankonverter.konvertierungen_log
    DROP CONSTRAINT IF EXISTS fk_konvertierung_id,
    ADD CONSTRAINT fk_konvertierung_id FOREIGN KEY (konvertierung_id) REFERENCES xplankonverter.konvertierungen(id) ON UPDATE CASCADE ON DELETE CASCADE;

  INSERT INTO xplankonverter.errors (error_id, name, beschreibung) VALUES (
    '10',
    'Profilvalidierungsfehler',
    'Die Validierung des eingelesenen Planes gegen ein Profil wurde nicht bestanden.'
  );

  ALTER TABLE xplankonverter.konformitaetsbedingungen ADD COLUMN IF NOT EXISTS profil character varying;

  INSERT INTO xplankonverter.konformitaetsbedingungen (nummer, version_von, version_bis, inhalt, bezeichnung, profil) VALUES
	 ('0.1','5.4','6.0.2','Es muss ein generisches Attribut mit dem Namen fassungsbezeichnzung geben und darf nur bestimmte Werte haben.','Es muss eine der folgenden Fassungsbezeichnungbezeichnungen angegeben worden sein: Ursprungsplan, Änderung, Ergänzung, Neuaufstellung, Erweiterung', 'MV'),
	 ('0.2','5.4',NULL,'Es muss ein generisches Attribut ALKIS_Stand geben und es darf nicht leer sein.','Es muss ein generisches Attribut ALKIS_Stand geben und es darf nicht leer sein.','MV');

  INSERT INTO xplankonverter.validierungen ("name",beschreibung,functionsname,msg_success,msg_warning,msg_error,msg_correcture,konformitaet_nummer,konformitaet_version_von,functionsargumente) VALUES
	 ('Fassungsbezeichnung vorhanden und gültig','Generisches Attribut fassungsbezeichnzung muss vorhanden sein und darf nur bestimmte Werte haben.','generisches_stringattribute_has_value','Der Plan hat eine gültige Fassungsbezeichnung.',NULL,'Das generische Attribut fassungsbezeichnung fehlt oder hat einen ungültigen Wert.','Es muss eine der folgenden Fassungsbezeichnungbezeichnungen angegeben worden sein: Ursprungsplan, Änderung, Ergänzung, Neuaufstellung, Erweiterung','0.1','5.4','{"name=''fassungsbezeichnung''","wert ~* ''\m(Ursprungsplan|Neuaufstellung|([0-9]+\.)?\s*(Änderung|Ergänzung|Erweiterung))\M''"}'),
	 ('ALKIS_Stand vorhanden','Generisches Attribut ALKIS_Stand muss belegt sein und darf nicht leer sein.','generisches_datumattribute_has_value','Der Plan hat einen ALKIS-Stand',NULL,'Das generische Attribut ALKIS_Stand fehlt oder ist leer.','Es muss ein generisches Attribut mit dem Namen ALKIS_Stand geben und darf nicht leer sein.','0.1','5.4', '{"name=''ALKIS_Stand''","wert IS NOT NULL"}');

  CREATE OR REPLACE FUNCTION xplankonverter.check_fassungsbezeichnung()
  RETURNS trigger
  LANGUAGE plpgsql
  AS $function$
    DECLARE
      msg text = '';
    BEGIN
      IF (NEW.fassungsbezeichnung IS NULL OR NEW.fassungsbezeichnung = '') THEN
        msg = E'\nEs wurde keine Fassungsbezeichnung wie Ursprungsplan oder 1. Änderung angegeben!';
        RAISE EXCEPTION '%', msg;
      ELSE
        IF (NOT NEW.fassungsbezeichnung ~* '\m(Ursprungsplan|Neuaufstellung|([0-9]+\.)?\s*(Änderung|Ergänzung|Erweiterung))\M') THEN 
          msg = E'\nEs muss einer der Begriffe Ursprungsplan, Änderung, Ergänzung, Neuaufstellung oder Erweiterung in der Fassungsbezeichnung vorkommen!';
          RAISE EXCEPTION '%', msg;
        END IF;
      END IF;
      RETURN NEW;
    END;
    $function$
  ;

  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Planupload abgebrochen' AFTER 'Angaben vollständig';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Plan hochgeladen' AFTER 'Planupload abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Planimport abgebrochen' AFTER 'Plan hochgeladen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Plan importiert' AFTER 'Planimport abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Reindizierung abgebrochen' AFTER 'Plan importiert';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Plan reindiziert' AFTER 'Reindizierung abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Import reindizierter Plan abgebrochen' AFTER 'Plan reindiziert';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Reindizierter Plan importiert' AFTER 'Import reindizierter Plan abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Validierung importierter Plan abgebrochen' AFTER 'Reindizierter Plan importiert';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Importierter Plan validiert' AFTER 'Validierung importierter Plan abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Planerzeugung abgebrochen' AFTER 'Importierter Plan validiert';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Plan angelegt' AFTER 'Planerzeugung abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Überprüfung der Klassifizierung abgebrochen' AFTER 'Plan angelegt';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Klassifizierung überprüft' AFTER 'Überprüfung der Klassifizierung abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Erzeugung von Metadaten abgebrochen' AFTER 'Klassifizierung überprüft';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Metadaten erzeugt' AFTER 'Erzeugung von Metadaten abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Erzeugung Geowebservice abgebrochen' AFTER 'Erzeugung von Metadaten abgebrochen';
  ALTER TYPE xplankonverter.enum_konvertierungsstatus ADD VALUE 'Geowebservice erzeugt' AFTER 'Erzeugung Geowebservice abgebrochen';

COMMIT;