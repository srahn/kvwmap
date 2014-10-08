INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Jagdkataster', NULL, 'Jagdkataster', NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 0);

SET @obermenue_id = LAST_INSERT_ID();

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Jagdbezirk erfassen', NULL, 'Jagdkataster', NULL, NULL, 'index.php?go=jagdkatastereditor', @obermenue_id, 2, NULL, 0),
('Jagdbezirke suchen', 'Jagdbezirke suchen', 'Hunting Areas', NULL, NULL, 'index.php?go=jagdbezirke_auswaehlen', @obermenue_id, 2, NULL, 0);
