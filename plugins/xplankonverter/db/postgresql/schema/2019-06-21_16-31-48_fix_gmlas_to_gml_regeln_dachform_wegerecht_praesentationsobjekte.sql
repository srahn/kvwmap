BEGIN;
    UPDATE
        xplankonverter.mappingtable_gmlas_to_gml
    SET
        regel = 'gmlas.art::character varying[] AS art'
    WHERE
        regel = 'gmlas.art::xplan_gml.character varying[] AS art';

    UPDATE
        xplankonverter.mappingtable_gmlas_to_gml
    SET
        regel = 'gmlas.typ::xplan_gml.bp_wegerechttypen[] AS typ'
    WHERE
        regel = 'gmlas.typ::xplan_gml.bp_wegerechttypen AS typ';

    UPDATE
        xplankonverter.mappingtable_gmlas_to_gml
    SET
        regel = 'gmlas.dachform::xplan_gml.bp_dachform[] AS dachform'
    WHERE
        regel = 'gmlas.dachform::xplan_gml.bp_dachform AS dachform';
COMMIT;