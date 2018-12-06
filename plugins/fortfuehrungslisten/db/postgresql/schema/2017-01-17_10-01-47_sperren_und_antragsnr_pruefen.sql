BEGIN;

DROP RULE r1_auftragsdatei_pruefen ON fortfuehrungslisten.ff_auftraege;
DROP RULE r2_auftrag_sperren ON fortfuehrungslisten.ff_auftraege;

CREATE OR REPLACE RULE sperren_und_antragsnr_pruefen AS
  ON UPDATE TO fortfuehrungslisten.ff_auftraege
  WHERE (
		SELECT (old.gesperrt AND new.gesperrt) OR (new.an_pruefen AND (new.auftragsdatei != '' AND new.antragsnr::text <> substring(split_part(new.auftragsdatei::text, 'original_name='::text, 2) from 1 for length(new.antragsnr::text))))) DO INSTEAD
		SELECT
			CASE
				WHEN (old.gesperrt AND new.gesperrt) THEN 'Die Bearbeitung des Fortführungsnachweises ist gesperrt.'
				WHEN (new.an_pruefen AND new.auftragsdatei != '' AND new.antragsnr::text <> substring(split_part(new.auftragsdatei::text, 'original_name='::text, 2) from 1 for length(new.antragsnr::text))) THEN 'Die Antragsnummer im Dateinamen entspricht nicht der Antragsnummer im Formular oben. Wenn Sie dennoch speichern wollen, nehmen Sie den Haken bei prüfen raus.'
				ELSE 'Prüfbedingung wird nicht erfüllt.'
			END AS msg,
			CASE
				WHEN (old.gesperrt AND new.gesperrt) THEN 'error'
				WHEN (new.an_pruefen AND new.auftragsdatei != '' AND new.antragsnr::text <> substring(split_part(new.auftragsdatei::text, 'original_name='::text, 2) from 1 for length(new.antragsnr::text))) THEN 'waring'
				ELSE 'waring'
			END AS msg_type;

COMMENT ON RULE sperren_und_antragsnr_pruefen ON fortfuehrungslisten.ff_auftraege IS 'Die Regel prüft ob das Attribut an_sperren auf true steht. Wenn ja, wird die Änderung nicht gespeichert und statt dessen eine Fehlermeldung ausgeliefert. Wenn nein wird mit dem Attribut an_pruefen geprüft ob die Antragsnr mit der Nummer im Dateinamen der Auftragsdatei verglichen werden soll. Wenn ja, werden die ersten x Zeichen des Dateinamen der Auftragsdatei (auftragsdatei) mit dem Attribut antragsnr verglichen. Stimmen diese überein, wird gespeichert, wenn nicht, wird eine Warnung ausgegeben, dass sie nicht übereinstimmen. x ist die Zeichenlänge des Attributes antragsnr.';


COMMIT;