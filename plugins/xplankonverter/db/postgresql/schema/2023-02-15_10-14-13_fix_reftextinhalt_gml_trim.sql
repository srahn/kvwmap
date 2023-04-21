BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET  regel = 'trim(leading ''GML_'' from trim(leading ''Gml_'' from trim(leading ''gml_'' from trim(leading ''#'' from lower(gmlas.href))))) AS reftextinhalt'
WHERE t_column = 'reftextinhalt'
AND regel = 'gmlas.href AS reftextinhalt';
COMMIT;
