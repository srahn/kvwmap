BEGIN;
UPDATE
	xplankonverter.mappingtable_gmlas_to_gml
SET
	regel = 'trim(leading ''GML_'' from trim(leading ''Gml_'' from trim(leading ''gml_'' from trim(leading ''#'' from lower(gmlas.href))))) AS dientzurdarstellungvon'
WHERE
	regel = 'trim(leading ''gml_'' from trim(leading ''#'' from lower(gmlas.href))) AS dientzurdarstellungvon';
COMMIT;
