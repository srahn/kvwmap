SET @obermenue_id = LAST_INSERT_ID();
SET @layer_id_ff_auftraege = 782;
SET @layer_id_ff_faelle = 783;

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Fortführungsaufträge', NULL, NULL, NULL, NULL, concat('index.php?go=Layer-Suche_Suchen&selected_layer_id=', @layer_id_ff_aufraege, '&anzahl=30'), @obermenue_id, 2, NULL, 0),
('Fortführungsfälle', NULL, NULL, NULL, NULL, concat('index.php?go=Layer-Suche_Suchen&selected_layer_id=', @layer_id_ff_faelle, '&anzahl=30'), @obermenue_id, 2, NULL, 0);
