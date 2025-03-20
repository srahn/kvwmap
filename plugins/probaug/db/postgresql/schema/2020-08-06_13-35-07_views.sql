BEGIN;

DROP TABLE probaug.bau_vorhaben;
DROP TABLE probaug.bau_verfahrensart;

create view probaug.bau_vorhaben as
select distinct feld8 as vorhaben from probaug.bau_akten;

create view probaug.bau_verfahrensart as
select distinct feld9 as verfahrensart from probaug.bau_akten;

COMMIT;
