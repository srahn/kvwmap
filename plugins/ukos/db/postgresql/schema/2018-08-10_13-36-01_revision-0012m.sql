BEGIN;

	DROP TABLE ukos_doppik.abfallbehaelter CASCADE;

	CREATE TABLE ukos_doppik.abfallbehaelter
	(
		ident character(6) NOT NULL,
		CONSTRAINT pk_abfallbehaelter_id PRIMARY KEY (id)
	)
	INHERITS(ukos_okstra.strassenausstattung_punkt);

	-- Trigger: tr_idents_add_ident on doppik.abfallbehaelter
	CREATE TRIGGER tr_idents_add_ident
	BEFORE INSERT
	ON ukos_doppik.abfallbehaelter
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.idents_add_ident();

	-- Trigger: tr_idents_remove_ident on doppik.abfallbehaelter
	CREATE TRIGGER tr_idents_remove_ident
	AFTER DELETE
	ON ukos_doppik.abfallbehaelter
	FOR EACH ROW
	EXECUTE PROCEDURE ukos_base.idents_remove_ident();

COMMIT;