BEGIN;

ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER created_at type date;
ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER created_at set default current_date;

ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER updated_at type date;
ALTER TABLE fortfuehrungslisten.ff_auftraege ALTER updated_at set default current_date;

COMMIT;