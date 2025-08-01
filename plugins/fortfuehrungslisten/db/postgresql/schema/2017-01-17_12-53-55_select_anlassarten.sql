BEGIN;

ALTER TABLE fortfuehrungslisten.aa_anlassart ADD COLUMN selectable boolean DEFAULT false;

UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010101';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010102';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010302';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010502';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010611';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010612';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010621';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010700';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010501';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010900';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010904';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '200100';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '200300';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010403';
UPDATE fortfuehrungslisten.aa_anlassart SET selectable = true WHERE code = '010205';

COMMIT;