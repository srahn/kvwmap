ALTER TABLE fortfuehrungslisten.ff_auftraege ADD COLUMN an_pruefen boolean;
ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER COLUMN an_pruefen SET NOT NULL;
ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER COLUMN an_pruefen SET DEFAULT true;

CREATE OR REPLACE RULE an_pruefen AS
	ON INSERT TO fortfuehrungslisten.ff_auftraege
  WHERE ( (SELECT (new.an_pruefen AND count(ff_auftraege.antragsnr) > 0)
    FROM fortfuehrungslisten.ff_auftraege
    WHERE antragsnr = new.antragsnr)) DO INSTEAD  SELECT 'Antragsnummer schon vorhanden. Soll die angegebene Nummer dennoch verwendet werden nehmen Sie den Haken aus der Checkbox prüfen';