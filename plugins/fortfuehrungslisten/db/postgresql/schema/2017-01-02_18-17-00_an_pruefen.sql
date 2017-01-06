UPDATE fortfuehrungslisten.ff_auftraege SET antragsnr = 0 WHERE antragsnr IS NULL;

ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER COLUMN antragsnr SET NOT NULL;

ALTER TABLE fortfuehrungslisten.ff_auftraege ADD COLUMN an_pruefen boolean NOT NULL DEFAULT true;

CREATE OR REPLACE RULE an_pruefen AS
	ON INSERT TO fortfuehrungslisten.ff_auftraege
	WHERE ( (SELECT (new.an_pruefen AND count(ff_auftraege.antragsnr) > 0)
		FROM fortfuehrungslisten.ff_auftraege
		WHERE antragsnr = new.antragsnr)) DO INSTEAD  SELECT '<br><br>Antragsnummer schon vergeben. In Fällen von<br>gemarkungsübergreifenden Messungen tritt der Fall auf! Sonst nicht.<br>Soll die angegebene Nummer dennoch verwendet werden,<br>nehmen Sie den Haken aus der Checkbox prüfen', 'error' AS msg_type;