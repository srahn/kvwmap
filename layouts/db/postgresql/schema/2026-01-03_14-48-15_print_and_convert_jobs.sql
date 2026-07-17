BEGIN;
  CREATE TABLE IF NOT EXISTS public.print_jobs (
    id serial4 NOT NULL PRIMARY KEY,
    layer_id int4 NOT NULL,
    table_alias varchar NULL,
    status varchar DEFAULT 'beauftragt'::character varying NOT NULL,
    created_at timestamp DEFAULT now() NOT NULL,
    created_from varchar NULL,
    table_name varchar NULL,
    feature_id varchar NULL,
    pdf_path varchar NULL,
    user_id int4 DEFAULT 1 NOT NULL,
    stelle_id int4 DEFAULT 1 NOT NULL,
    printed_at timestamp NULL,
    msg text NULL,
    ddl_id int4 NULL,
    started_at timestamp NULL
  );

  CREATE TABLE IF NOT EXISTS public.convert_jobs (
    id serial4 NOT NULL PRIMARY KEY,
    status varchar DEFAULT 'beauftragt'::character varying NOT NULL,
    src_file varchar NOT NULL,
    dst_file varchar NOT NULL,
    dst_schema varchar NOT NULL,
    dst_table varchar NOT NULL,
    dst_column varchar NOT NULL,
    define_options text NULL,
    exif_data text NULL,
    created_at timestamp DEFAULT now() NOT NULL,
    created_from varchar NULL,
    user_id int4 DEFAULT 1 NOT NULL,
    stelle_id int4 DEFAULT 1 NOT NULL,
    started_at timestamp NULL,
    finished_at timestamp NULL,
    msg text NULL
  );

COMMIT;