BEGIN;

CREATE OR REPLACE RULE r1_auftragsdatei_pruefen AS
	ON UPDATE TO fortfuehrungslisten.ff_auftraege
	WHERE ( SELECT new.auftragsdatei != '' AND new.antragsnr::text <> "substring"(split_part(new.auftragsdatei::text, 'original_name='::text, 2), 1, 10)) DO INSTEAD  SELECT 'Die Antragsnummer im Dateinamen entspricht nicht der Antragsnummer im Formular oben.', 'error' AS msg_type;
COMMENT ON RULE r1_auftragsdatei_pruefen ON fortfuehrungslisten.ff_auftraege IS 'Die Regel prüft ob die ersten 10 Zeichen des Dateinamen der Auftragsdatei (auftragsdatei) mit der Antragsnummer (antragsnr) übereinstimmen. Überprüft wird beim Update und der Dateiname wird aus dem String auftragsdatei hinter dem Textbestandteil original_name= extrahiert.';

COMMIT;