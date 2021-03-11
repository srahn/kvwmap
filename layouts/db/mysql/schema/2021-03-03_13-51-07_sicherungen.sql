BEGIN;
  ##################################
  ### Insert default/templates for Sicherungen ###
  ##################################

  # Tagessicherung
  INSERT INTO `sicherungen` (`name`, `beschreibung`, `target_dir`, `intervall_typ`, `intervall_start_time`, `intervall_parameter_1`, `intervall_parameter_2`, `keep_for_n_days`, `active`) VALUES
  ('Tagessicherung', 'sichert mySQL, Postgres und Anwendungsdaten', '/home/gisadmin/Sicherungen/taeglich', 'daily', '23:30:00', '1', '5', 7, 0);
  SET @last_sicherung_id=LAST_INSERT_ID();

  INSERT INTO `sicherungsinhalte` (`name`, `beschreibung`, `methode`, `source`, `connection_id`, `target`, `overwrite`, `sicherung_id`, `tar_compress`, `pgdump_insert`, `pgdump_columninserts`, `pgdump_in_exclude_schemas`, `pgdump_schema_list`, `pgdump_in_exclude_tables`, `pgdump_table_list`, `active`) VALUES
  ('Anwendungsdaten', 'nur Verzeichnisse, keine Datenbanken', 'Verzeichnissicherung', '/home/gisadmin/www/data', NULL, 'data.tar.gz', 1, @last_sicherung_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
  ('Anwendungen', NULL, 'Verzeichnissicherung', '/home/gisadmin/www/apps', NULL, 'apps.tar.gz', 1, @last_sicherung_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
  ('kvwmap mySQL', NULL, 'Mysql Dump', 'kvwmapdb_intern', NULL, 'kvwmapdb_intern.dump', 1, @last_sicherung_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
  ('Postgres DB', NULL, 'Postgres Dump', 'kvwmapsp', 1, 'kvwmapsp.dump', 1, @last_sicherung_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);


  # Wochensicherung
  INSERT INTO `sicherungen` (`name`, `beschreibung`, `target_dir`, `intervall_typ`, `intervall_start_time`, `intervall_parameter_1`, `intervall_parameter_2`, `keep_for_n_days`, `active`) VALUES
  ('Wochensicherung', 'sichert die letzte Tagessicherung', '/home/gisadmin/Sicherungen/woechentlich', 'weekly', '01:00:00', '0', '', 365, 0);
  SET @last_sicherung_id=LAST_INSERT_ID();
  INSERT INTO `sicherungsinhalte` (`name`, `beschreibung`, `methode`, `source`, `connection_id`, `target`, `overwrite`, `sicherung_id`, `tar_compress`, `pgdump_insert`, `pgdump_columninserts`, `pgdump_in_exclude_schemas`, `pgdump_schema_list`, `pgdump_in_exclude_tables`, `pgdump_table_list`, `active`) VALUES
  ('Wochensicherung kopieren', 'wöchentliche Sicherung der letzten Wochensicherung', 'Verzeichnissicherung', '/home/gisadmin/Sicherungen/taeglich/latest', NULL, 'wochensicherung.tar.gz', 1, @last_sicherung_id, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0);

  # Monatssicherung
  INSERT INTO `sicherungen` (`name`, `beschreibung`, `target_dir`, `intervall_typ`, `intervall_start_time`, `intervall_parameter_1`, `intervall_parameter_2`, `keep_for_n_days`, `active`) VALUES
  ('Monatssicherung', 'schreibt die letzte Wochensicherung auf ein entferntes Medium', '', 'monthly', '04:00:00', '27', '4', 0, 0);
  SET @last_sicherung_id=LAST_INSERT_ID();
  INSERT INTO `sicherungsinhalte` (`name`, `beschreibung`, `methode`, `source`, `connection_id`, `target`, `overwrite`, `sicherung_id`, `tar_compress`, `pgdump_insert`, `pgdump_columninserts`, `pgdump_in_exclude_schemas`, `pgdump_schema_list`, `pgdump_in_exclude_tables`, `pgdump_table_list`, `active`) VALUES
  ('rsync auf entfernten Host', 'letzte Wochensicherung sichern', 'Verzeichnisinhalte kopieren', '/home/gisadmin/Sicherungen/woechentlich/latest', NULL, 'gisadmin@host:/pfad/zum/backup', 1, @last_sicherung_id, NULL, NULL, NULL, '', '', '', '', 0);
COMMIT;
