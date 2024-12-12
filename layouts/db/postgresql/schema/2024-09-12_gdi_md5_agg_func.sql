BEGIN;
  CREATE OR REPLACE FUNCTION gdi_md5_agg_sfunc(
    text,
    anyelement
  )
  RETURNS text
  LANGUAGE sql
  AS $BODY$
    SELECT md5($1 || $2::text);
  $BODY$;
  COMMENT ON FUNCTION gdi_md5_agg_sfunc(text, anyelement)
    IS 'Function that takes the first argument, concatenate it with the second and returns a md5 hash from that. It will be used in the aggregate function gdi_md5_agg to aggregate content from rows to one md5 hash. It can be used to generate a checksum over the content of the whole table content.';

  DROP AGGREGATE IF EXISTS public.gdi_md5_agg(anyelement);
  CREATE OR REPLACE AGGREGATE public.gdi_md5_agg(ORDER BY anyelement) (
      SFUNC = gdi_md5_agg_sfunc,
      STYPE = text ,
      FINALFUNC_MODIFY = READ_WRITE,
      MFINALFUNC_MODIFY = READ_WRITE
  );
  COMMENT ON AGGREGATE public.gdi_md5_agg(anyelement)
    IS 'Aggregat to generate a checksum over the content of text or the the whole table content. Use: SELECT gdi_md5_agg() WITHIN GROUP (ORDER BY mytab) FROM myschema.mytab';

COMMIT;