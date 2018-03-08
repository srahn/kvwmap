

DO
$$
BEGIN
IF not EXISTS (SELECT column_name 
               FROM information_schema.columns 
               WHERE table_schema='public' and table_name='uko_polygon' and column_name='dateiname') THEN
alter table uko_polygon add column dateiname varchar(100);
END IF;
END
$$


