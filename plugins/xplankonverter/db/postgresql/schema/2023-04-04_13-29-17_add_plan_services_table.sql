BEGIN;

  CREATE TABLE IF NOT EXISTS xplankonverter.plan_services (
    id serial NOT NULL PRIMARY KEY,
    planart character varying NOT NULL,
    metadata_dataset_uuid uuid,
    metadata_viewservice_uuid uuid,
    metadata_downloadservice_uuid uuid
  );

  COMMENT ON TABLE xplankonverter.plan_services
    IS 'Services that serves all spatial plans of one plan type.';

COMMIT;
