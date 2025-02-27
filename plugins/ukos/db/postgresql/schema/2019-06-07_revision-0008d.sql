BEGIN;

UPDATE ukos_base.wld_strassennetzlage SET sortierreihenfolge = '002' WHERE kurztext = 'O';
UPDATE ukos_base.wld_strassennetzlage SET sortierreihenfolge = '003' WHERE kurztext = 'F';

COMMIT;
