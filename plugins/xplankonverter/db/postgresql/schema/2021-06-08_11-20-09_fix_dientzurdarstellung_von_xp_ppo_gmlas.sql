BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = 'trim(leading ''gml_'' from trim(leading ''#'' from lower(gmlas.href))) AS dientzurdarstellungvon'
WHERE regel = 'gmlas.href AS dientzurdarstellungvon';
COMMIT;