BEGIN;
  CREATE TABLE IF NOT EXISTS xplankonverter.planungsbueros (
    id serial NOT NULL,
    bezeichnung varchar NOT NULL,
    abk varchar,
    anschrift text,
    CONSTRAINT planungsbueros_pk PRIMARY KEY (id)
  );

  CREATE TABLE IF NOT EXISTS xplankonverter.ansprechpartner_der_stellen (
    id serial NOT NULL,
    vorname varchar NOT NULL,
    nachname varchar NOT NULL,
    email varchar NOT NULL,
    stelle_id integer NULL,
    CONSTRAINT ansprechpartner_der_stelle_pk PRIMARY KEY (id)
  );

  ALTER TABLE xplankonverter.ansprechpartner_der_stellen
    DROP CONSTRAINT IF EXISTS fk_stelle_id,
    ADD CONSTRAINT fk_stelle_id FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE CASCADE;

  CREATE TABLE IF NOT EXISTS xplankonverter.planungsbuero_users (
    user_id integer NOT NULL,
    planungsbuero_id integer NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone NULL,
    created_from varchar NULL,
    updated_from varchar NULL,
    CONSTRAINT planungsbuero_users_pk PRIMARY KEY (user_id)
  );
  ALTER TABLE xplankonverter.planungsbuero_users
    DROP CONSTRAINT IF EXISTS fk_planungsbuero_id,
    ADD CONSTRAINT fk_planungsbuero_id FOREIGN KEY (planungsbuero_id) REFERENCES xplankonverter.planungsbueros(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE xplankonverter.planungsbuero_users
    DROP CONSTRAINT IF EXISTS fk_user_id,
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES kvwmap.user(id) ON UPDATE CASCADE ON DELETE CASCADE;

  CREATE TABLE IF NOT EXISTS xplankonverter.planungsbueros2stellen (
    id serial NOT NULL,
    planungsbuero_id int4 NOT NULL,
    stelle_id int4 NOT NULL,
    created_at timestamp DEFAULT now() NOT NULL,
    updated_at timestamp NULL,
    created_from varchar NULL,
    updated_from varchar NULL,
    CONSTRAINT planungsbueros2stellen_pk PRIMARY KEY (id)
  );

  ALTER TABLE xplankonverter.planungsbueros2stellen
    DROP CONSTRAINT IF EXISTS fk_planungsbuero_id,
    ADD CONSTRAINT fk_planungsbuero_id FOREIGN KEY (planungsbuero_id) REFERENCES xplankonverter.planungsbueros(id) ON DELETE CASCADE ON UPDATE CASCADE;
  ALTER TABLE xplankonverter.planungsbueros2stellen
    DROP CONSTRAINT IF EXISTS fk_stelle_id,
    ADD CONSTRAINT fk_stelle_id FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;