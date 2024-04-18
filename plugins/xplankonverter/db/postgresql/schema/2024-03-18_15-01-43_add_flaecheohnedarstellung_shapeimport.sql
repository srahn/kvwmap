BEGIN;
INSERT INTO xplankonverter.mappingtable_standard_shp_to_db (tabelle,db_attribute,shp_attribute,ambiguous_fields,data_type,regel)
VALUES
('fp_flaecheohnedarstellung','gml_id','gml_id',NULL,'uuid','trim(leading ''GML_'' FROM gml_id)::text::uuid AS gml_id'),
('fp_flaecheohnedarstellung','flaechenschluss','flaechensc',NULL,'boolean','flaechensc::int::bool AS flaechenschluss'),
('fp_flaecheohnedarstellung','gehoertzubereich','gehoertzub',NULL,'text','gehoertzub AS gehoertzubereich'),
('fp_flaecheohnedarstellung','vorbehalt','vorbehalt',NULL,'character varying','vorbehalt AS vorbehalt'),
('fp_flaecheohnedarstellung','wirdausgeglichendurchspe','wirdausgeg',NULL,'text','wirdausgeg AS wirdausgeglichendurchspe'),
('fp_flaecheohnedarstellung','gliederung1','gliederung',NULL,'character varying','gliederung AS gliederung1'),
('fp_flaecheohnedarstellung','reftextinhalt','reftextinh',NULL,'text','reftextinh AS reftextinhalt'),
('fp_flaecheohnedarstellung','uuid','uuid',NULL,'character varying','uuid AS uuid'),
('fp_flaecheohnedarstellung','gliederung2','gliederu_1',NULL,'character varying','gliederu_1 AS gliederung2'),
('fp_flaecheohnedarstellung','text','text',NULL,'character varying','text AS text'),
('fp_flaecheohnedarstellung','refbegruendunginhalt','refbegruen',NULL,'text','refbegruen AS refbegruendunginhalt'),
('fp_flaecheohnedarstellung','wirdausgeglichendurchflaeche','wirdausg_3','wirdausg_1 für FP_SchutzPflegeEntwicklung, wirdausg_3 bei BP_SchutzPflegeEntwicklungsFlaeche','text','wirdausg_3 AS wirdausgeglichendurchflaeche'),
('fp_flaecheohnedarstellung','wirddargestelltdurch','wirddarges',NULL,'text','wirddarges AS wirddargestelltdurch'),
('fp_flaecheohnedarstellung','ebene','ebene',NULL,'integer','ebene AS ebene'),
('fp_flaecheohnedarstellung','rechtscharakter','rechtschar',NULL,'fp_rechtscharakter','rechtschar::xplan_gml.fp_rechtscharakter AS rechtscharakter'),
('fp_flaecheohnedarstellung','rechtsstand','rechtsstan',NULL,'xp_rechtsstand','rechtsstan::xplan_gml.xp_rechtsstand AS rechtsstand'),
('fp_flaecheohnedarstellung','gesetzlichegrundlage','gesetzlich',NULL,'xp_gesetzlichegrundlage','gesetzlich::xplan_gml.xp_gesetzlichegrundlage AS gesetzlichegrundlage'),
('fp_flaecheohnedarstellung','spezifischepraegung','spezifisch',NULL,'fp_spezifischepraegungtypen','spezifisch::xplan_gml.fp_spezifischepraegungtypen AS spezifischepraegung'),
('fp_flaecheohnedarstellung','hoehenangabe','hoehenanga',NULL,'xp_hoehenangabe[]','NULLIF(ARRAY[hoehenanga]::xplan_gml.xp_hoehenangabe[],''{NULL}'') AS hoehenangabe');
COMMIT;
