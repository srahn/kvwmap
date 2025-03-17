BEGIN;

# Neuer Obermenuepunkt fuer Wasserrecht
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
('Wasserrecht', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 0, '', '');
SET @menue_id_wasserrecht = LAST_INSERT_ID();

# Neue Untermenuepunkte zu Wasserrecht
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
('Neue FisWrV-WRe Anlage', '', '', '', '', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', (SELECT `Layer_ID` FROM `layer` WHERE `Name` LIKE 'FisWrV-WRe Anlagen' ORDER BY `Layer_ID` LIMIT 1)), '', @menue_id_wasserrecht, 2, '', 0, '', ''),
('Wasserentnahmebenutzer', '', '', '', '', 'index.php?go=wasserentnahmebenutzer', '', @menue_id_wasserrecht, 2, '', 1, '', ''),
('Wasserentnahmeentgelt', '', '', '', '', 'index.php?go=wasserentnahmeentgelt', '', @menue_id_wasserrecht, 2, '', 2, '', ''),
('Neue FisWrV-WRe Gewässerbenutzung', '', '', '', '', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', (SELECT `Layer_ID` FROM `layer` WHERE `Name` LIKE 'FisWrV-WRe Gewässerbenutzungen' ORDER BY `Layer_ID` LIMIT 1)), '', @menue_id_wasserrecht, 2, '', 3, '', ''),
('Neue FisWrV-WRe Person', '', '', '', '', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', (SELECT `Layer_ID` FROM `layer` WHERE `Name` LIKE 'FisWrV-WRe Personen' ORDER BY `Layer_ID` LIMIT 1)), '', @menue_id_wasserrecht, 2, '', 4, '', ''),
('Neue FisWrV-WRe WrZ', '', '', '', '', concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', (SELECT `Layer_ID` FROM `layer` WHERE `Name` LIKE 'FisWrV-WRe WrZ' ORDER BY `Layer_ID` LIMIT 1)), '', @menue_id_wasserrecht, 2, '', 5, '', ''),
('Zentrale Stelle', '', '', '', '', 'index.php?go=zentrale_stelle', '', @menue_id_wasserrecht, 2, '', 6, '', ''),
('Erstattung des Verwaltungsaufwands', '', '', '', '', 'index.php?go=erstattung_des_verwaltungsaufwands', '', @menue_id_wasserrecht, 2, '', 7, '', '');

# Zuordnung der Menuepunkte zur Stelle Dateneingeber
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `menue_order`) VALUES
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout' ORDER BY id LIMIT 1), 0),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Wasserrecht' ORDER BY id LIMIT 1),  10),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage' ORDER BY id LIMIT 1),  11),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer' ORDER BY id LIMIT 1),  12),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt' ORDER BY id LIMIT 1),  13),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent' ORDER BY id LIMIT 1),  20),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php' ORDER BY id LIMIT 1),  30),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Suchen' ORDER BY id LIMIT 1),  40),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen' ORDER BY id LIMIT 1),  41),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen' ORDER BY id LIMIT 1),  42),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen' ORDER BY id LIMIT 1),  43),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen' ORDER BY id LIMIT 1),  44),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche' ORDER BY id LIMIT 1),  45),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query' ORDER BY id LIMIT 1),  46),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung' ORDER BY id LIMIT 1),  50),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz' ORDER BY id LIMIT 1),  51),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Import/Export' ORDER BY id LIMIT 1),  60),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export' ORDER BY id LIMIT 1),  61),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen' ORDER BY id LIMIT 1),  62);

# Zuordnung der Menuepunkte zur Stelle Entscheider
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `menue_order`) VALUES
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout' ORDER BY id LIMIT 1),  0),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Wasserrecht' ORDER BY id LIMIT 1),  10),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage' ORDER BY id LIMIT 1),  11),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer' ORDER BY id LIMIT 1),  12),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt' ORDER BY id LIMIT 1),  13),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent' ORDER BY id LIMIT 1),  20),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php' ORDER BY id LIMIT 1),  30),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Suchen' ORDER BY id LIMIT 1),  40),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen' ORDER BY id LIMIT 1),  41),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen' ORDER BY id LIMIT 1),  42),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen' ORDER BY id LIMIT 1),  43),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen' ORDER BY id LIMIT 1),  44),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche' ORDER BY id LIMIT 1),  45),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query' ORDER BY id LIMIT 1),  46),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung' ORDER BY id LIMIT 1),  50),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz' ORDER BY id LIMIT 1),  51),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Import/Export' ORDER BY id LIMIT 1),  60),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export' ORDER BY id LIMIT 1),  61),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen' ORDER BY id LIMIT 1),  62);

# Zuordnung der Menuepunkte zur Stelle Administration
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `menue_order`) VALUES
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout' ORDER BY id LIMIT 1),  0),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Stelle_waehlen' ORDER BY id LIMIT 1),  5),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Wasserrecht' ORDER BY id LIMIT 1),  10),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Gewässerbenutzung' ORDER BY id LIMIT 1),  11),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Person' ORDER BY id LIMIT 1),  12),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe WrZ' ORDER BY id LIMIT 1),  13),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage' ORDER BY id LIMIT 1),  14),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer' ORDER BY id LIMIT 1),  15),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt' ORDER BY id LIMIT 1),  16),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=zentrale_stelle' ORDER BY id LIMIT 1),  17),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=erstattung_des_verwaltungsaufwands' ORDER BY id LIMIT 1),  18),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent' ORDER BY id LIMIT 1),  20),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php' ORDER BY id LIMIT 1),  30),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Suchen' ORDER BY id LIMIT 1),  40),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen' ORDER BY id LIMIT 1),  41),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen' ORDER BY id LIMIT 1),  42),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen' ORDER BY id LIMIT 1),  43),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen' ORDER BY id LIMIT 1),  44),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche' ORDER BY id LIMIT 1),  45),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query' ORDER BY id LIMIT 1),  46),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung' ORDER BY id LIMIT 1),  50),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz' ORDER BY id LIMIT 1),  51),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Import/Export' ORDER BY id LIMIT 1),  60),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export' ORDER BY id LIMIT 1),  61),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen' ORDER BY id LIMIT 1),  62);

COMMIT;
