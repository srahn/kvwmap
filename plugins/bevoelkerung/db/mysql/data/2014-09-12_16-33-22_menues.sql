INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Bev&ouml;lkerungsprognose', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 90);

SET @obermenue_id = LAST_INSERT_ID();

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Einwohner/Landkreis', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=465&anzahl=30', @obermenue_id, 2, NULL, 0),
('Durchschnittsalter/Landkreis', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=466&anzahl=30', @obermenue_id, 2, NULL, 0),
('Einwohner/Alter', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=464&anzahl=310&orderby464=nr', @obermenue_id, 2, NULL, 0),
('Bev&ouml;lkerungsdichte', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=92&anzahl=300&orderby92=kennung', @obermenue_id, 2, NULL, 0),
('Bewegung 2009 - 2030', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=524&anzahl=30&orderby524=rueckgang', @obermenue_id, 2, NULL, 0),
('Zusmgf. Geburtenziff. 2009', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=94&anzahl=30&orderby94=geburtenziffer', @obermenue_id, 2, NULL, 0),
('Bev&ouml;lkerung abgest. 2009-2030', NULL, NULL, NULL, NULL, 'index.php?go=Layer-Suche_Suchen&selected_layer_id=95&anzahl=30&orderby95=kennung', @obermenue_id, 2, NULL, 0),
('Bericht erstellen', NULL, NULL, NULL, NULL, 'index.php?go=bevoelkerung_bericht', @obermenue_id, 2, NULL, 0);