INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Anliegerbeiträge', NULL, NULL, NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 50);

SET @obermenue_id = LAST_INSERT_ID();

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Anliegerbeiträge erfassen', NULL, NULL, NULL, NULL, 'index.php?go=anliegerbeitraege', @obermenue_id, 2, NULL, 0);
