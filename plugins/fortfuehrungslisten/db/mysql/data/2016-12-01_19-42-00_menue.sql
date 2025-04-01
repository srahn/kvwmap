# Obermenü Fortführungslisten
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`)
VALUES ('Fortführungslisten', 'Fortführungen-Verwalten', NULL, NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 40);
SET @obermenue_id = LAST_INSERT_ID();

# Untermenü FN-Listen drucken
SELECT @selected_layer_id := `Layer_ID` FROM `layer` WHERE `name` = 'Fortführungslisten Gemarkungen';
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`)
VALUES ('FN-Listen&nbsp;drucken', 'FN-Listen&nbsp;drucken', NULL, NULL, NULL, concat('index.php?go=Layer-Suche&selected_layer_id=', @selected_layer_id), @obermenue_id, 2, NULL, 0);

# Untermenü FN anlegen
SELECT @selected_layer_id := `Layer_ID` FROM `layer` WHERE `name` = 'Fortführungsnachweise';
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`)
VALUES ('FN&nbsp;anlegen', 'FN&nbsp;ingeven', NULL, NULL, NULL, concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @selected_layer_id), @obermenue_id, 2, NULL, 0);

# Untermenü FN suchen/bearbeiten
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`)
VALUES ('FN&nbsp;suchen/bearbeiten', 'FN&nbsp;sööke', NULL, NULL, NULL, 'index.php?go=fortfuehrungslisten_fn_suche', @obermenue_id, 2, NULL, 0);
