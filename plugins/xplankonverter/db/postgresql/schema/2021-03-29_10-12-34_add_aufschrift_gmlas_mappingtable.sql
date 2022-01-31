BEGIN;

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4349,
TRUE,
'rp_achse',
'aufschrift',
'character varying',
'rp_achse',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_achse'
	AND o_column = 'aufschrift'
	AND id = 4349
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4350,
TRUE,
'rp_freiraum',
'aufschrift',
'character varying',
'rp_freiraum',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_freiraum'
	AND o_column = 'aufschrift'
	AND id = 4350
);


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4351,
TRUE,
'rp_siedlung',
'aufschrift',
'character varying',
'rp_siedlung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_siedlung'
	AND o_column = 'aufschrift'
	AND id = 4351
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4352,
TRUE,
'rp_energieversorgung',
'aufschrift',
'character varying',
'rp_energieversorgung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_energieversorgung'
	AND o_column = 'aufschrift'
	AND id = 4352
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4353,
TRUE,
'rp_entsorgung',
'aufschrift',
'character varying',
'rp_entsorgung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_entsorgung'
	AND o_column = 'aufschrift'
	AND id = 4353
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4354,
TRUE,
'rp_funktionszuweisung',
'aufschrift',
'character varying',
'rp_funktionszuweisung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_funktionszuweisung'
	AND o_column = 'aufschrift'
	AND id = 4354
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4355,
TRUE,
'rp_generischesobjekt',
'aufschrift',
'character varying',
'rp_generischesobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_generischesobjekt'
	AND o_column = 'aufschrift'
	AND id = 4355
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4356,
TRUE,
'rp_grenze',
'aufschrift',
'character varying',
'rp_grenze',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_grenze'
	AND o_column = 'aufschrift'
	AND id = 4356
);


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4357,
TRUE,
'rp_kommunikation',
'aufschrift',
'character varying',
'rp_kommunikation',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_kommunikation'
	AND o_column = 'aufschrift'
	AND id = 4357
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4358,
TRUE,
'rp_laermschutzbauschutz',
'aufschrift',
'character varying',
'rp_laermschutzbauschutz',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_laermschutzbauschutz'
	AND o_column = 'aufschrift'
	AND id = 4358
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4359,
TRUE,
'rp_verkehr',
'aufschrift',
'character varying',
'rp_verkehr',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_verkehr'
	AND o_column = 'aufschrift'
	AND id = 4359
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4360,
TRUE,
'rp_planungsraum',
'aufschrift',
'character varying',
'rp_planungsraum',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_planungsraum'
	AND o_column = 'aufschrift'
	AND id = 4360
);


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4361,
TRUE,
'rp_raumkategorie',
'aufschrift',
'character varying',
'rp_raumkategorie',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_raumkategorie'
	AND o_column = 'aufschrift'
	AND id = 4361
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4362,
TRUE,
'rp_sonstigeinfrastruktur',
'aufschrift',
'character varying',
'rp_sonstigeinfrastruktur',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_sonstigeinfrastruktur'
	AND o_column = 'aufschrift'
	AND id = 4362
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4363,
TRUE,
'rp_sozialeinfrastruktur',
'aufschrift',
'character varying',
'rp_sozialeinfrastruktur',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_sozialeinfrastruktur'
	AND o_column = 'aufschrift'
	AND id = 4363
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4364,
TRUE,
'rp_sperrgebiet',
'aufschrift',
'character varying',
'rp_sperrgebiet',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_sperrgebiet'
	AND o_column = 'aufschrift'
	AND id = 4364
);


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4365,
TRUE,
'rp_wasserwirtschaft',
'aufschrift',
'character varying',
'rp_wasserwirtschaft',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_wasserwirtschaft'
	AND o_column = 'aufschrift'
	AND id = 4365
);


INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4366,
TRUE,
'rp_zentralerort',
'aufschrift',
'character varying',
'rp_zentralerort',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'rp_zentralerort'
	AND o_column = 'aufschrift'
	AND id = 4366
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4367,
TRUE,
'bp_flaechenschlussobjekt',
'aufschrift',
'character varying',
'bp_flaechenschlussobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_flaechenschlussobjekt'
	AND o_column = 'aufschrift'
	AND id = 4367
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4368,
TRUE,
'bp_ueberlagerungsobjekt',
'aufschrift',
'character varying',
'bp_ueberlagerungsobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_ueberlagerungsobjekt'
	AND o_column = 'aufschrift'
	AND id = 4368
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4369,
TRUE,
'bp_abgrabungsflaeche',
'aufschrift',
'character varying',
'bp_abgrabungsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_abgrabungsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4369
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4370,
TRUE,
'bp_bodenschaetzeflaeche',
'aufschrift',
'character varying',
'bp_bodenschaetzeflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_bodenschaetzeflaeche'
	AND o_column = 'aufschrift'
	AND id = 4370
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4371,
TRUE,
'bp_rekultivierungsflaeche',
'aufschrift',
'character varying',
'bp_rekultivierungsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_rekultivierungsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4371
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4372,
TRUE,
'bp_aufschuettungsflaeche',
'aufschrift',
'character varying',
'bp_aufschuettungsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_aufschuettungsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4372
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4373,
TRUE,
'bp_besonderernutzungszweckflaeche',
'aufschrift',
'character varying',
'bp_besonderernutzungszweckflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_besonderernutzungszweckflaeche'
	AND o_column = 'aufschrift'
	AND id = 4373
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4374,
TRUE,
'bp_ausgleichsflaeche',
'aufschrift',
'character varying',
'bp_ausgleichsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_ausgleichsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4374
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4375,
TRUE,
'bp_schutzpflegeentwicklungsflaeche',
'aufschrift',
'character varying',
'bp_schutzpflegeentwicklungsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_schutzpflegeentwicklungsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4375
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4376,
TRUE,
'bp_kennzeichnungsflaeche',
'aufschrift',
'character varying',
'bp_kennzeichnungsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_kennzeichnungsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4376
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4377,
TRUE,
'bp_wasserwirtschaftsflaeche',
'aufschrift',
'character varying',
'bp_wasserwirtschaftsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_wasserwirtschaftsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4377
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4378,
TRUE,
'bp_einfahrtpunkt',
'aufschrift',
'character varying',
'bp_einfahrtpunkt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_einfahrtpunkt'
	AND o_column = 'aufschrift'
	AND id = 4378
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4379,
TRUE,
'bp_gemeinschaftsanlagenzuordnung',
'aufschrift',
'character varying',
'bp_gemeinschaftsanlagenzuordnung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_gemeinschaftsanlagenzuordnung'
	AND o_column = 'aufschrift'
	AND id = 4379
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4380,
TRUE,
'bp_ausgleichsmassnahme',
'aufschrift',
'character varying',
'bp_ausgleichsmassnahme',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_ausgleichsmassnahme'
	AND o_column = 'aufschrift'
	AND id = 4380
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4381,
TRUE,
'bp_anpflanzungbindungerhaltung',
'aufschrift',
'character varying',
'bp_anpflanzungbindungerhaltung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_anpflanzungbindungerhaltung'
	AND o_column = 'aufschrift'
	AND id = 4381
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4382,
TRUE,
'bp_schutzpflegeentwicklungsmassnahme',
'aufschrift',
'character varying',
'bp_schutzpflegeentwicklungsmassnahme',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_schutzpflegeentwicklungsmassnahme'
	AND o_column = 'aufschrift'
	AND id = 4382
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4383,
TRUE,
'bp_unverbindlichevormerkung',
'aufschrift',
'character varying',
'bp_unverbindlichevormerkung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_unverbindlichevormerkung'
	AND o_column = 'aufschrift'
	AND id = 4383
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4384,
TRUE,
'bp_abstandsmass',
'aufschrift',
'character varying',
'bp_abstandsmass',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_abstandsmass'
	AND o_column = 'aufschrift'
	AND id = 4384
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4385,
TRUE,
'bp_generischesobjekt',
'aufschrift',
'character varying',
'bp_generischesobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_generischesobjekt'
	AND o_column = 'aufschrift'
	AND id = 4385
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4386,
TRUE,
'bp_wegerecht',
'aufschrift',
'character varying',
'bp_wegerecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_wegerecht'
	AND o_column = 'aufschrift'
	AND id = 4386
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4387,
TRUE,
'bp_hoehenmass',
'aufschrift',
'character varying',
'bp_hoehenmass',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_hoehenmass'
	AND o_column = 'aufschrift'
	AND id = 4387
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4388,
TRUE,
'bp_festsetzungnachlandesrecht',
'aufschrift',
'character varying',
'bp_festsetzungnachlandesrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_festsetzungnachlandesrecht'
	AND o_column = 'aufschrift'
	AND id = 4388
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4389,
TRUE,
'bp_immissionsschutz',
'aufschrift',
'character varying',
'bp_immissionsschutz',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_immissionsschutz'
	AND o_column = 'aufschrift'
	AND id = 4389
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4390,
TRUE,
'bp_verentsorgung',
'aufschrift',
'character varying',
'bp_verentsorgung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_verentsorgung'
	AND o_column = 'aufschrift'
	AND id = 4390
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4391,
TRUE,
'bp_strassenkoerper',
'aufschrift',
'character varying',
'bp_strassenkoerper',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_strassenkoerper'
	AND o_column = 'aufschrift'
	AND id = 4391
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4392,
TRUE,
'fp_flaechenschlussobjekt',
'aufschrift',
'character varying',
'fp_flaechenschlussobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_flaechenschlussobjekt'
	AND o_column = 'aufschrift'
	AND id = 4392
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4393,
TRUE,
'fp_ueberlagerungsobjekt',
'aufschrift',
'character varying',
'fp_ueberlagerungsobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_ueberlagerungsobjekt'
	AND o_column = 'aufschrift'
	AND id = 4393
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4394,
TRUE,
'fp_keinezentrabwasserbeseitigungflaeche',
'aufschrift',
'character varying',
'fp_keinezentrabwasserbeseitigungflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_keinezentrabwasserbeseitigungflaeche'
	AND o_column = 'aufschrift'
	AND id = 4394
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4395,
TRUE,
'fp_ausgleichsflaeche',
'aufschrift',
'character varying',
'fp_ausgleichsflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_ausgleichsflaeche'
	AND o_column = 'aufschrift'
	AND id = 4395
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4396,
TRUE,
'fp_vorbehalteflaeche',
'aufschrift',
'character varying',
'fp_vorbehalteflaeche',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_vorbehalteflaeche'
	AND o_column = 'aufschrift'
	AND id = 4396
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4397,
TRUE,
'fp_aufschuettung',
'aufschrift',
'character varying',
'fp_aufschuettung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_aufschuettung'
	AND o_column = 'aufschrift'
	AND id = 4397
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4398,
TRUE,
'fp_bodenschaetze',
'aufschrift',
'character varying',
'fp_bodenschaetze',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_bodenschaetze'
	AND o_column = 'aufschrift'
	AND id = 4398
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4399,
TRUE,
'fp_abgrabung',
'aufschrift',
'character varying',
'fp_abgrabung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_abgrabung'
	AND o_column = 'aufschrift'
	AND id = 4399
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4400,
TRUE,
'fp_anpassungklimawandel',
'aufschrift',
'character varying',
'fp_anpassungklimawandel',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_anpassungklimawandel'
	AND o_column = 'aufschrift'
	AND id = 4400
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4401,
TRUE,
'fp_spielsportanlage',
'aufschrift',
'character varying',
'fp_spielsportanlage',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_spielsportanlage'
	AND o_column = 'aufschrift'
	AND id = 4401
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4402,
TRUE,
'fp_gemeinbedarf',
'aufschrift',
'character varying',
'fp_gemeinbedarf',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_gemeinbedarf'
	AND o_column = 'aufschrift'
	AND id = 4402
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4403,
TRUE,
'fp_gruen',
'aufschrift',
'character varying',
'fp_gruen',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_gruen'
	AND o_column = 'aufschrift'
	AND id = 4403
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4404,
TRUE,
'fp_schutzpflegeentwicklung',
'aufschrift',
'character varying',
'fp_schutzpflegeentwicklung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_schutzpflegeentwicklung'
	AND o_column = 'aufschrift'
	AND id = 4404
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4405,
TRUE,
'fp_unverbindlichevormerkung',
'aufschrift',
'character varying',
'fp_unverbindlichevormerkung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_unverbindlichevormerkung'
	AND o_column = 'aufschrift'
	AND id = 4405
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4406,
TRUE,
'fp_privilegiertesvorhaben',
'aufschrift',
'character varying',
'fp_privilegiertesvorhaben',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_privilegiertesvorhaben'
	AND o_column = 'aufschrift'
	AND id = 4406
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4407,
TRUE,
'fp_kennzeichnung',
'aufschrift',
'character varying',
'fp_kennzeichnung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_kennzeichnung'
	AND o_column = 'aufschrift'
	AND id = 4407
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4408,
TRUE,
'fp_generischesobjekt',
'aufschrift',
'character varying',
'fp_generischesobjekt',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_generischesobjekt'
	AND o_column = 'aufschrift'
	AND id = 4408
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4409,
TRUE,
'fp_strassenverkehr',
'aufschrift',
'character varying',
'fp_strassenverkehr',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_strassenverkehr'
	AND o_column = 'aufschrift'
	AND id = 4409
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4410,
TRUE,
'fp_gewaesser',
'aufschrift',
'character varying',
'fp_gewaesser',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_gewaesser'
	AND o_column = 'aufschrift'
	AND id = 4410
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4411,
TRUE,
'fp_wasserwirtschaft',
'aufschrift',
'character varying',
'fp_wasserwirtschaft',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_wasserwirtschaft'
	AND o_column = 'aufschrift'
	AND id = 4411
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4412,
TRUE,
'so_gebiet',
'aufschrift',
'character varying',
'so_gebiet',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_gebiet'
	AND o_column = 'aufschrift'
	AND id = 4412
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4413,
TRUE,
'so_bodenschutzrecht',
'aufschrift',
'character varying',
'so_bodenschutzrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_bodenschutzrecht'
	AND o_column = 'aufschrift'
	AND id = 4413
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4414,
TRUE,
'so_sonstigesrecht',
'aufschrift',
'character varying',
'so_sonstigesrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_sonstigesrecht'
	AND o_column = 'aufschrift'
	AND id = 4414
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4415,
TRUE,
'so_luftverkehrsrecht',
'aufschrift',
'character varying',
'so_luftverkehrsrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_luftverkehrsrecht'
	AND o_column = 'aufschrift'
	AND id = 4415
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4416,
TRUE,
'so_strassenverkehrsrecht',
'aufschrift',
'character varying',
'so_strassenverkehrsrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_strassenverkehrsrecht'
	AND o_column = 'aufschrift'
	AND id = 4416
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4417,
TRUE,
'so_forstrecht',
'aufschrift',
'character varying',
'so_forstrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_forstrecht'
	AND o_column = 'aufschrift'
	AND id = 4417
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4418,
TRUE,
'so_wasserrecht',
'aufschrift',
'character varying',
'so_wasserrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_wasserrecht'
	AND o_column = 'aufschrift'
	AND id = 4418
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4419,
TRUE,
'so_denkmalschutzrecht',
'aufschrift',
'character varying',
'so_denkmalschutzrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_denkmalschutzrecht'
	AND o_column = 'aufschrift'
	AND id = 4419
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4420,
TRUE,
'so_schienenverkehrsrecht',
'aufschrift',
'character varying',
'so_schienenverkehrsrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_schienenverkehrsrecht'
	AND o_column = 'aufschrift'
	AND id = 4420
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4421,
TRUE,
'so_schutzgebietsonstigesrecht',
'aufschrift',
'character varying',
'so_schutzgebietsonstigesrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_schutzgebietsonstigesrecht'
	AND o_column = 'aufschrift'
	AND id = 4421
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4422,
TRUE,
'so_schutzgebietnaturschutzrecht',
'aufschrift',
'character varying',
'so_schutzgebietnaturschutzrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_schutzgebietnaturschutzrecht'
	AND o_column = 'aufschrift'
	AND id = 4422
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4423,
TRUE,
'so_schutzgebietwasserrecht',
'aufschrift',
'character varying',
'so_schutzgebietwasserrecht',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_schutzgebietwasserrecht'
	AND o_column = 'aufschrift'
	AND id = 4423
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4424,
TRUE,
'so_grenze',
'aufschrift',
'character varying',
'so_grenze',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_grenze'
	AND o_column = 'aufschrift'
	AND id = 4424
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4425,
TRUE,
'bp_landwirtschaft',
'aufschrift',
'character varying',
'bp_landwirtschaft',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_landwirtschaft'
	AND o_column = 'aufschrift'
	AND id = 4425
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4426,
TRUE,
'fp_verentsorgung',
'aufschrift',
'character varying',
'fp_verentsorgung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_verentsorgung'
	AND o_column = 'aufschrift'
	AND id = 4426
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4427,
TRUE,
'bp_verkehrsflaechebesondererzweckbestimmung',
'aufschrift',
'character varying',
'bp_verkehrsflaechebesondererzweckbestimmung',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'bp_verkehrsflaechebesondererzweckbestimmung'
	AND o_column = 'aufschrift'
	AND id = 4427
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4428,
TRUE,
'fp_landwirtschaft',
'aufschrift',
'character varying',
'fp_landwirtschaft',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'fp_landwirtschaft'
	AND o_column = 'aufschrift'
	AND id = 4428
);

INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(id,feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel) SELECT
4429,
TRUE,
'so_gewaesser',
'aufschrift',
'character varying',
'so_gewaesser',
'aufschrift',
'character varying',
NULL,
NULL,
'gmlas.aufschrift AS aufschrift'
WHERE NOT exists (
	SELECT id 
	FROM xplankonverter.mappingtable_gmlas_to_gml
	WHERE o_table = 'so_gewaesser'
	AND o_column = 'aufschrift'
	AND id = 4429
);


COMMIT;