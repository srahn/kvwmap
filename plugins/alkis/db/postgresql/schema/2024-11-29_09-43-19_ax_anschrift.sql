BEGIN;

ALTER TABLE alkis.ax_anschrift DROP COLUMN herkunft_description;
ALTER TABLE alkis.ax_anschrift DROP COLUMN extent_description;
ALTER TABLE alkis.ax_anschrift DROP COLUMN herkunft_role;
ALTER TABLE alkis.ax_anschrift DROP COLUMN herkunft_stepdatetime;

ALTER TABLE alkis.ax_anschrift
  ADD COLUMN statement character varying,
  ADD COLUMN ax_li_processstep_ohnedatenerhebung_description character varying[],
  ADD COLUMN processstep_rationale character varying[],
  ADD COLUMN processstep_datetime character(20)[],
  ADD COLUMN processstep_individualname character varying[],
  ADD COLUMN processstep_organisationname character varying[],
  ADD COLUMN processstep_positionname character varying[],
  ADD COLUMN processstep_phone character varying[],
  ADD COLUMN processstep_address character varying[],
  ADD COLUMN processstep_onlineresource character varying[],
  ADD COLUMN processstep_hoursofservice character varying[],
  ADD COLUMN processstep_contactinstructions character varying[],
  ADD COLUMN processstep_role character varying[],
  ADD COLUMN processstep_ax_datenerhebung character varying[],
  ADD COLUMN processstep_scaledenominator character varying[],
  ADD COLUMN processstep_sourcereferencesystem character varying[],
  ADD COLUMN processstep_sourceextent character varying[],
  ADD COLUMN processstep_sourcestep character varying[],
  ADD COLUMN herkunft_source_source_ax_datenerhebung character varying[],
  ADD COLUMN herkunft_source_source_scaledenominator character varying[],
  ADD COLUMN herkunft_source_source_sourcereferencesystem character varying[],
  ADD COLUMN herkunft_source_source_sourceextent character varying[],
  ADD COLUMN herkunft_source_source_sourcestep character varying[];

COMMIT;
