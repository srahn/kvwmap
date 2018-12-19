BEGIN;

UPDATE `layer` SET Pfad = REPLACE(Pfad, "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE beginnt::text <= '$hist_timestamp' and ('$hist_timestamp' < endet::text or endet IS NULL) END", "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE to_char(beginnt, 'YYYY-MM-DD HH:MI:SS') <= '$hist_timestamp' and ('$hist_timestamp' < to_char(endet, 'YYYY-MM-DD HH:MI:SS') or endet IS NULL) END"),
Data = REPLACE(DATA, "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE beginnt::text <= '$hist_timestamp' and ('$hist_timestamp' < endet::text or endet IS NULL) END", "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE to_char(beginnt, 'YYYY-MM-DD HH:MI:SS') <= '$hist_timestamp' and ('$hist_timestamp' < to_char(endet, 'YYYY-MM-DD HH:MI:SS') or endet IS NULL) END");

COMMIT;
