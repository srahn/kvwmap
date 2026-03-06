BEGIN;

  update
    kvwmap.user u
  set
    stelle_id = null
  where
    id in (
      select 
        u.id
      from
        kvwmap.user u
        left join kvwmap.stelle on u.stelle_id = stelle.id
      where
        stelle.id IS null
    );

  ALTER TABLE kvwmap.user
    DROP CONSTRAINT IF EXISTS fk_stelle_id,
    ADD CONSTRAINT fk_stelle_id FOREIGN KEY (stelle_id) REFERENCES kvwmap.stelle(id) ON UPDATE CASCADE ON DELETE NO ACTION;
COMMIT;