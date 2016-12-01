INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Fortführungslisten', 'Fortführungen-Verwalten', NULL, NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 40);

SET @obermenue_id = LAST_INSERT_ID();
SELECT @selected_layer_id := `Layer_ID` FROM `layer` WHERE `name` = 'Fortführungsaufträge';

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Auftrag&nbsp;anlegen', 'Auftrag&nbsp;ingeven', NULL, NULL, NULL, concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', @selected_layer_id), @obermenue_id, 2, NULL, 0),
('Auftrag&nbsp;suchen', 'Auftrag&nbsp;sööke', NULL, NULL, NULL, concat('index.php?go=Layer-Suche&selected_layer_id=', @selected_layer_id), @obermenue_id, 2, NULL, 0);