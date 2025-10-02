BEGIN;
  UPDATE datatype_attributes
  SET
    options = 'SELECT
wert AS value,
  wert || '' '' || TRIM(TRAILING ''.'' FROM abk)  AS output
FROM
  xplan_gml.enum_xp_externereferenztyp
ORDER BY
  wert',
    tooltip = 'Typ / Inhalt des referierten Dokuments oder Rasterplans <a href="index.php?go=show_snippet&snippet=hilfe#Externereferenztypen">Beschreibungen</a>.'
  WHERE
    datatype_id = (SELECT id FROM datatypes WHERE name = 'xp_spezexternereferenzauslegung' AND "schema" = 'xplankonverter') AND
    name = 'typ';
COMMIT;