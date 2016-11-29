BEGIN;

UPDATE ddl_elemente SET font = substring_index(font, 'PDFClass/fonts/', -1) WHERE font like '%PDFClass%';
UPDATE druckfreitexte SET font = substring_index(font, 'PDFClass/fonts/', -1) WHERE font like '%PDFClass%';
UPDATE datendrucklayouts SET font_date = substring_index(font_date, 'PDFClass/fonts/', -1) WHERE font_date like '%PDFClass%';
UPDATE druckrahmen SET font_date = substring_index(font_date, 'PDFClass/fonts/', -1) WHERE font_date like '%PDFClass%';
UPDATE druckrahmen SET font_scale = substring_index(font_scale, 'PDFClass/fonts/', -1) WHERE font_scale like '%PDFClass%';
UPDATE druckrahmen SET font_lage = substring_index(font_lage, 'PDFClass/fonts/', -1) WHERE font_lage like '%PDFClass%';
UPDATE druckrahmen SET font_gemeinde = substring_index(font_gemeinde, 'PDFClass/fonts/', -1) WHERE font_gemeinde like '%PDFClass%';
UPDATE druckrahmen SET font_gemarkung = substring_index(font_gemarkung, 'PDFClass/fonts/', -1) WHERE font_gemarkung like '%PDFClass%';
UPDATE druckrahmen SET font_flur = substring_index(font_flur, 'PDFClass/fonts/', -1) WHERE font_flur like '%PDFClass%';
UPDATE druckrahmen SET font_flurst = substring_index(font_flurst, 'PDFClass/fonts/', -1) WHERE font_flurst like '%PDFClass%';
UPDATE druckrahmen SET font_oscale = substring_index(font_oscale, 'PDFClass/fonts/', -1) WHERE font_oscale like '%PDFClass%';
UPDATE druckrahmen SET font_legend = substring_index(font_legend, 'PDFClass/fonts/', -1) WHERE font_legend like '%PDFClass%';
UPDATE druckrahmen SET font_watermark = substring_index(font_watermark, 'PDFClass/fonts/', -1) WHERE font_watermark like '%PDFClass%';
UPDATE druckrahmen SET font_user = substring_index(font_user, 'PDFClass/fonts/', -1) WHERE font_user like '%PDFClass%';

COMMIT;
