DROP FUNCTION IF EXISTS gdi_normalize_unicode_de(text);
CREATE OR REPLACE FUNCTION gdi_normalize_unicode_de(t text)
RETURNS CHARACTER varying
LANGUAGE sql
IMMUTABLE
AS $$
SELECT replace(
       replace(
       replace(
       replace(
       replace(
       replace(
           t,
           'a' || chr(776), 'ä'),
           'o' || chr(776), 'ö'),
           'u' || chr(776), 'ü'),
           'A' || chr(776), 'Ä'),
           'O' || chr(776), 'Ö'),
           'U' || chr(776), 'Ü')
$$;