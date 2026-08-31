BEGIN;
  ALTER TABLE kvwmap.layer DROP CONSTRAINT IF EXISTS layer_group_fk;
  ALTER TABLE kvwmap.layer ADD CONSTRAINT layer_group_fk FOREIGN KEY (gruppe) REFERENCES kvwmap.u_groups(id) ON UPDATE CASCADE ON DELETE SET NULL;

  ALTER TABLE kvwmap.rolle_saved_layers ALTER COLUMN user_id DROP NOT NULL;
  ALTER TABLE kvwmap.rolle_saved_layers ALTER COLUMN stelle_id DROP NOT NULL;

  CREATE TABLE IF NOT EXISTS kvwmap.collections (
    id serial NOT NULL,
    bezeichnung varchar,
    group_id integer,
    "filter" varchar,
    extent varchar
  );
  ALTER TABLE kvwmap.collections ADD CONSTRAINT collections_pk PRIMARY KEY (id);
  ALTER TABLE kvwmap.collections ADD CONSTRAINT collections_group_fk FOREIGN KEY (group_id) REFERENCES kvwmap.u_groups(id) ON UPDATE CASCADE ON DELETE SET NULL;

  CREATE TABLE IF NOT EXISTS kvwmap.collection_layer (
    id serial NOT NULL,
    collection_id integer NOT NULL,
    layer_id integer NOT NULL
  );
  ALTER TABLE kvwmap.collection_layer ADD CONSTRAINT collection_layer_pk PRIMARY KEY (id);
  ALTER TABLE kvwmap.collection_layer ADD CONSTRAINT collection_layer_collection_fk FOREIGN KEY (collection_id) REFERENCES kvwmap.collections(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE kvwmap.collection_layer ADD CONSTRAINT collection_layer_layer_fk FOREIGN KEY (layer_id) REFERENCES kvwmap.layer(layer_id) ON UPDATE CASCADE ON DELETE CASCADE;
  
  CREATE TABLE IF NOT EXISTS kvwmap.collections2rolle (
    collection_id integer NOT NULL,
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    status int2 NOT NULL DEFAULT 2
  );
  ALTER TABLE kvwmap.collections2rolle ADD CONSTRAINT collections2rolle_pk PRIMARY KEY (collection_id, stelle_id, user_id);
  ALTER TABLE kvwmap.collections2rolle ADD CONSTRAINT collections2rolle_collection_fk FOREIGN KEY (collection_id) REFERENCES kvwmap.collections(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE kvwmap.collections2rolle ADD CONSTRAINT collections2rolle_rolle_fk FOREIGN KEY (stelle_id, user_id) REFERENCES kvwmap.rolle(stelle_id, user_id) ON UPDATE CASCADE ON DELETE CASCADE;
    
  CREATE TABLE IF NOT EXISTS kvwmap.collection_layer2rolle (
    collection_layer_id integer NOT NULL,
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    aktivstatus int2 NOT NULL DEFAULT 1,
    querystatus int2 NOT NULL DEFAULT 0,
    gle_view int2 NOT NULL DEFAULT 1,
    showclasses int2 NOT NULL DEFAULT 1,
    labelitem varchar(100)
  );
  ALTER TABLE kvwmap.collection_layer2rolle ADD CONSTRAINT collection_layer2rolle_pk PRIMARY KEY (collection_layer_id, user_id, stelle_id);
  ALTER TABLE kvwmap.collection_layer2rolle ADD CONSTRAINT collection_layer2rolle_collection_layer_fk FOREIGN KEY (collection_layer_id) REFERENCES kvwmap.collection_layer(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE kvwmap.collection_layer2rolle ADD CONSTRAINT collection_layer2rolle_rolle_fk FOREIGN KEY (stelle_id, user_id) REFERENCES kvwmap.rolle(stelle_id, user_id) ON UPDATE CASCADE ON DELETE CASCADE;
  
    CREATE TABLE IF NOT EXISTS kvwmap.collection_groups (
    id serial NOT NULL,
    collection_id integer NOT NULL,
    group_id integer NOT NULL,
    "order" integer
  );
  ALTER TABLE kvwmap.collection_groups ADD CONSTRAINT collection_groups_pk PRIMARY KEY (id);
  ALTER TABLE kvwmap.collection_groups ADD CONSTRAINT collection_groups_collection_fk FOREIGN KEY (collection_id) REFERENCES kvwmap.collections(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE kvwmap.collection_groups ADD CONSTRAINT collection_groups_group_fk FOREIGN KEY (group_id) REFERENCES kvwmap.u_groups(id) ON UPDATE CASCADE ON DELETE CASCADE;

  CREATE TABLE IF NOT EXISTS kvwmap.collection_groups2rolle (
    collection_group_id integer NOT NULL,
    stelle_id integer NOT NULL,
    user_id integer NOT NULL,
    status int2 NOT NULL DEFAULT 1
  );
  ALTER TABLE kvwmap.collection_groups2rolle ADD CONSTRAINT collection_groups2rolle_pk PRIMARY KEY (collection_group_id, user_id, stelle_id);
  ALTER TABLE kvwmap.collection_groups2rolle ADD CONSTRAINT collection_groups2rolle_collection_group_fk FOREIGN KEY (collection_group_id) REFERENCES kvwmap.collection_groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
  ALTER TABLE kvwmap.collection_groups2rolle ADD CONSTRAINT collection_groups2rolle_rolle_fk FOREIGN KEY (stelle_id, user_id) REFERENCES kvwmap.rolle(stelle_id, user_id) ON UPDATE CASCADE ON DELETE CASCADE;

  /*
  DROP TABLE IF EXISTS kvwmap.collection_layer2rolle;
  DROP TABLE IF EXISTS kvwmap.collection_layer;
  DROP TABLE IF EXISTS kvwmap.collections2rolle;
  DROP TABLE IF EXISTS kvwmap.collections;
  DROP TABLE IF EXISTS kvwmap.collection_groups;
  DROP TABLE IF EXISTS kvwmap.collection_groups2rolle;
  */

COMMIT;