BEGIN;

  ALTER TABLE IF EXISTS public.q_notizen
    ADD COLUMN notiz_id serial NOT NULL,
    ADD CONSTRAINT q_notizen_pkey PRIMARY KEY (notiz_id);

COMMIT;
