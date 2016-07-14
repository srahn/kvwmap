INSERT INTO `u_groups` (`Gruppenname`, `Gruppenname_low-german`, `Gruppenname_english`, `Gruppenname_polish`, `Gruppenname_vietnamese`, `obergruppe`, `order`) VALUES
('XPlanung', NULL, NULL, NULL, NULL, NULL, 8);

SET @group_id=LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

/*

Insert hier stellen, menü und layer Definitionen

*/