BEGIN;

  ALTER TABLE `rolle` add `font_size_factor` double NOT NULL DEFAULT 1.0 AFTER `visually_impaired`;

COMMIT;
