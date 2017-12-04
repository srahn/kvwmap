DROP TABLE xplan_gml.xp_plan_zu_xp_begruendungabschnitt;

CREATE TABLE xplan_gml.xp_rasterdarstellung(
 gml_id text NOT NULL,
 refscan xp_externereferenz[] NOT NULL,
 reftext xp_externereferenz,
 reflegende xp_externereferenz[],
 user_id integer,
 created_at timestamp without time zone NOT NULL DEFAULT now(),
 updated_at timestamp without time zone NOT NULL DEFAULT now(),
 konvertierung_id integer,
 inverszu_rasterbasis_xp_bereich text[],
 CONSTRAINT xp_rasterdarstellung_pkey PRIMARY KEY (gml_id)
)
WITH (
 OIDS = TRUE
);

COMMENT ON TABLE xplan_gml.xp_rasterdarstellung IS 'FeatureType: "XP_Rasterdarstellung"';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.refscan IS 'refScan DataType XP_ExterneReferenz 1..*';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.reftext IS 'refText DataType XP_ExterneReferenz 0..1';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.reflegende IS 'refLegende DataType XP_ExterneReferenz 0..*';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.user_id IS 'user_id integer';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.created_at IS 'created_at timestamp without time zone ';
COMMENT ON COLUMN xplan_gml.xp_rasterdarstellung.updated_at IS 'updated_at timestamp without time zone ';
COMMENT ON COLUMN xplan_gml.xp_rasterplanbasis.konvertierung_id IS 'konvertierung_id  integer ';
COMMENT ON COLUMN xplan_gml.xp_rasterplanbasis.inverszu_rasterbasis_xp_bereich IS 'Assoziation zu: FeatureType XP_Bereich (xp_bereich) 0..*';

DROP TABLE xplan_gml.xp_rasterplanbasis;

CREATE TABLE xplan_gml.rp_textabschnitt_zu_rp_objekt(
 rp_textabschnitt_gml_id text NOT NULL,
 rp_objekt_gml_id text NOT NULL,
 CONSTRAINT rp_textabschnitt_zu_rp_objekt_pkey PRIMARY KEY (rp_textabschnitt_gml_id, rp_objekt_gml_id)
)
WITH (
 OIDS = TRUE
);

COMMENT ON TABLE xplan_gml.rp_textabschnitt_zu_rp_objekt IS 'Association RP_TextAbschnitt _zu_ RP_Objekt';
COMMENT ON COLUMN xplan_gml.rp_textabschnitt_zu_rp_objekt.rp_objekt_gml_id IS 'refTextInhalt';

DROP TABLE xp_bereich_zu_xp_objekt;

DROP TYPE xplan_gml.xp_externereferenz_alt;
DROP TABLE xplan_gml.xp_externereferenzart_alt;

/*
-- ToDo
Values der Code-Listen auch in enum_... Typen Tabellen ergänzen.
Dazu erstmal die Code-Listentabellen erzeugen und befüllen mit xmi2db => toGML
einige enum_xp_... Tabellen sollen laut diff gelöscht werden obwohl es die enum Typen noch gibt. 

-- ToDo
Prüfen ob zusätzliche Relationentabellen erstellt werden müssen oder noch welche wegfallen können, weil die Assoziationen weggefallen sind.
-- ToDo
Umschreibung der Daten von weggefallenen Spalten in die neuen von externereferenz
*/
--Stopp -- diese Zeile wirft einen Fehler, weil die Datei noch nicht fertig ist.
