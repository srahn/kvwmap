BEGIN;

INSERT INTO u_menues (name, links, obermenue, menueebene, target, `order`) VALUES ('Logout', 'index.php?go=logout', 0, 1, NULL, -1);
SET @menue_id=LAST_INSERT_ID();

INSERT INTO u_menue2stelle (stelle_id,menue_id,menue_order) 
SELECT ID, @menue_id, -1 FROM stelle;

INSERT INTO u_menue2rolle (user_id,stelle_id,menue_id,status) 
SELECT user_id, stelle_id, @menue_id, 0 FROM rolle;

COMMIT;
