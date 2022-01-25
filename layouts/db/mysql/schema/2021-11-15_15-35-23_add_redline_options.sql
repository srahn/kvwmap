BEGIN;
	ALTER TABLE rolle ADD COLUMN redline_text_color varchar(7) NOT NULL DEFAULT '#ff0000';
	ALTER TABLE rolle ADD COLUMN redline_font_family varchar(25) NOT NULL DEFAULT 'Arial';
	ALTER TABLE rolle ADD COLUMN redline_font_size integer NOT NULL DEFAULT '16';
	ALTER TABLE rolle ADD COLUMN redline_font_weight varchar(25) NOT NULL DEFAULT 'bold';
COMMIT;
