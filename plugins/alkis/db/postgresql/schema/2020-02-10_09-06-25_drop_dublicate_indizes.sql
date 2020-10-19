BEGIN;

DROP INDEX IF EXISTS alkis.ap_pto_art;
DROP INDEX IF EXISTS alkis.ap_pto_sn;
DROP INDEX IF EXISTS alkis.ax_flurstueck_kz;
DROP INDEX IF EXISTS alkis.idx_histfsorb_kennz;
DROP INDEX IF EXISTS alkis.idx_histfs_kennz;

COMMIT;
