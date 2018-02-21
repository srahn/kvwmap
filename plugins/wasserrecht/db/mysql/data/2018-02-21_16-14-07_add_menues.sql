BEGIN;

# Neuer Obermenuepunkt fuer Wasserrecht
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
('Wasserrecht', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 0, '', '');
SET @menue_id_wasserrecht = LAST_INSERT_ID();

# Neue Untermenuepunkte zu Wasserrecht
INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `onclick`, `obermenue`, `menueebene`, `target`, `order`, `title`, `button_class`) VALUES
('Neue FisWrV-WRe Anlage', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=$ANLAGEN_LAYER_ID', '', @menue_id_wasserrecht, 2, '', 0, '', ''),
('Wasserentnahmebenutzer', '', '', '', '', 'index.php?go=wasserentnahmebenutzer', '', @menue_id_wasserrecht, 2, '', 1, '', ''),
('Wasserentnahmeentgelt', '', '', '', '', 'index.php?go=wasserentnahmeentgelt', '', @menue_id_wasserrecht, 2, '', 2, '', ''),
('Neue FisWrV-WRe Gewässerbenutzung', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=$GEWAESSERBENUTZUNGEN_LAYER_ID', '', @menue_id_wasserrecht, 2, '', 3, '', ''),
('Neue FisWrV-WRe Person', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=$PERSONEN_LAYER_ID', '', @menue_id_wasserrecht, 2, '', 4, '', ''),
('Neue FisWrV-WRe WrZ', '', '', '', '', 'index.php?go=neuer_Layer_Datensatz&selected_layer_id=$WRZ_LAYER_ID', '', @menue_id_wasserrecht, 2, '', 5, '', ''),
('Zentrale Stelle', '', '', '', '', 'index.php?go=zentrale_stelle', '', @menue_id_wasserrecht, 2, '', 6, '', ''),
('Erstattung des Verwaltungsaufwands', '', '', '', '', 'index.php?go=erstattung_des_verwaltungsaufwands', '', @menue_id_wasserrecht, 2, '', 7, '', '');

# Zuordnung der Menuepunkte zur Stelle Dateneingeber
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `order`) VALUES
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout'), 0),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Wasserrecht'), 10),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage'), 11),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer'), 12),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt'), 13),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent'), 20),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php'), 30),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Suchen'), 40),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen'), 41),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen'), 42),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen'), 43),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen'), 44),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche'), 45),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query'), 46),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung'), 50),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz'), 51),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE name like 'Import/Export'), 60),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export'), 61),
($WASSERRECHT_STELLE_DATENEINGEBER, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen'), 62);

# Zuordnung der Menuepunkte zur Stelle Entscheider
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `order`) VALUES
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout'), 0),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Wasserrecht'), 10),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage'), 11),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer'), 12),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt'), 13),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent'), 20),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php'), 30),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Suchen'), 40),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen'), 41),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen'), 42),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen'), 43),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen'), 44),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche'), 45),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query'), 46),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung'), 50),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz'), 51),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE name like 'Import/Export'), 60),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export'), 61),
($WASSERRECHT_STELLE_ENTSCHEIDER, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen'), 62);

# Zuordnung der Menuepunkte zur Stelle Administration
INSERT INTO `u_menue2stelle` (`stelle_id`, `menue_id`, `order`) VALUES
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=logout'), 0),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Stelle Wählen'), 5),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Wasserrecht'), 10),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Gewässerbenutzung'), 11),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Person'), 12),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe WrZ'), 13),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Neue FisWrV-WRe Anlage'), 14),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmebenutzer'), 15),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=wasserentnahmeentgelt'), 16),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=zentrale_stelle'), 17),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=erstattung_des_verwaltungsaufwands'), 18),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Full_Extent'), 20),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php'), 30),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Suchen'), 40),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Adresse_Auswaehlen'), 41),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Flurstueck_Auswaehlen'), 42),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Grundbuchblatt_Auswaehlen'), 43),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Namen_Auswaehlen'), 44),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Layer-Suche'), 45),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=get_last_query'), 46),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Layerverwaltung'), 50),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=neuer_Layer_Datensatz'), 51),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE name like 'Import/Export'), 60),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=Daten_Export'), 61),
($WASSERRECHT_STELLE_ADMINISTRATION, (SELECT id FROM u_menues WHERE links like 'index.php?go=SHP_Anzeigen'), 62);

COMMIT;
