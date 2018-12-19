BEGIN;

UPDATE `layer` SET Pfad = REPLACE(LOWER(Pfad), "case when '$hist_timestamp' = '' then endet is null else beginnt::text <= '$hist_timestamp' and ('$hist_timestamp' < endet::text or endet is null) end", "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE to_char(beginnt, 'YYYY-MM-DD HH:MI:SS') <= '$hist_timestamp' and ('$hist_timestamp' < to_char(endet, 'YYYY-MM-DD HH:MI:SS') or endet IS NULL) END"),
Data = REPLACE(LOWER(DATA), "case when '$hist_timestamp' = '' then endet is null else beginnt::text <= '$hist_timestamp' and ('$hist_timestamp' < endet::text or endet is null) end", "CASE WHEN '$hist_timestamp' = '' THEN endet IS NULL ELSE to_char(beginnt, 'YYYY-MM-DD HH:MI:SS') <= '$hist_timestamp' and ('$hist_timestamp' < to_char(endet, 'YYYY-MM-DD HH:MI:SS') or endet IS NULL) END");

COMMIT;
