BEGIN;
/*
adds columns abkuerzung for rp_enums. necessary for gdi_enum_json_to_text function
*/
ALTER TABLE IF EXISTS xplan_gml.enum_rp_abfallentsorgungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_abfalltypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_abwassertypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_achsentypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_art ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_bedeutsamkeit ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_bergbaufolgenutzung ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_bergbauplanungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_besondereraumkategorietypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_besondererschienenverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_besondererstrassenverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_besonderetourismuserholungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_bodenschatztiefen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_bodenschutztypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_einzelhandeltypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_energieversorgungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_erholungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_erneuerbareenergietypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_forstwirtschafttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_funktionszuweisungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_gebietstyp ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_hochwasserschutztypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_industriegewerbetypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_klimaschutztypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_kommunikationtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_kulturlandschafttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_laermschutztypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_landwirtschafttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_luftverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_naturlandschafttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_primaerenergietypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_radwegwanderwegtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_raumkategorietypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_rechtscharakter ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_rechtsstand ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_rohstofftypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_schienenverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_sonstverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_sozialeinfrastrukturtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_spannungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_sperrgebiettypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_spezifischegrenzetypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_sportanlagetypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_strassenverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_tourismustypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_verfahren ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_verkehrstatus ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_verkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_wasserschutztypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_wasserschutzzonen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_wasserverkehrtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_wasserwirtschafttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_wohnensiedlungtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_zaesurtypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_zeitstufen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_zentralerortsonstigetypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;
ALTER TABLE IF EXISTS xplan_gml.enum_rp_zentralerorttypen ADD COLUMN IF NOT EXISTS abkuerzung character varying;

UPDATE xplan_gml.enum_rp_abfallentsorgungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_abfalltypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_abwassertypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_achsentypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_art SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_bedeutsamkeit SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_bergbaufolgenutzung SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_bergbauplanungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_besondereraumkategorietypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_besondererschienenverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_besondererstrassenverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_besonderetourismuserholungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_bodenschatztiefen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_bodenschutztypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_einzelhandeltypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_energieversorgungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_erholungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_erneuerbareenergietypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_forstwirtschafttypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_funktionszuweisungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_gebietstyp SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_hochwasserschutztypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_industriegewerbetypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_klimaschutztypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_kommunikationtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_kulturlandschafttypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_laermschutztypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_landwirtschafttypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_luftverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_naturlandschafttypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_primaerenergietypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_radwegwanderwegtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_raumkategorietypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_rechtscharakter SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_rechtsstand SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_rohstofftypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_schienenverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_sonstverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_sozialeinfrastrukturtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_spannungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_sperrgebiettypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_spezifischegrenzetypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_sportanlagetypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_strassenverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_tourismustypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_verfahren SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_verkehrstatus SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_verkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_wasserschutztypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_wasserschutzzonen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_wasserverkehrtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_wasserwirtschafttypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_wohnensiedlungtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_zaesurtypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_zeitstufen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_zentralerortsonstigetypen SET abkuerzung = beschreibung;
UPDATE xplan_gml.enum_rp_zentralerorttypen SET abkuerzung = beschreibung;
COMMIT;