INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Gutachterausschuss', 'Gutachterausschuss', 'Gutachterausschuss', NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 170);

SET @obermenue_id = LAST_INSERT_ID();

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Bodenrichtwerterfassung', 'Boddenrichtweert-Upnahme', 'Bodenrichtwerterfassung', NULL, 'Bodenrichtwerterfassung', 'index.php?go=Bodenrichtwertformular', @obermenue_id, 2, NULL, 0),
('Zonen kopieren', 'Zonen kopieren', 'Zonen kopieren', NULL, 'Zonen kopieren', 'index.php?go=BodenrichtwertzonenKopieren', @obermenue_id, 2, NULL, 0);