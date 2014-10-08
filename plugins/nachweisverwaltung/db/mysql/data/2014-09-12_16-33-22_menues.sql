INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Nachweisverwaltung', 'Nahwies-Verwalten', 'Nachweisverwaltung', NULL, 'Quáº£n lÃ½ tÃ i liá»‡u', 'index.php?go=changemenue', 0, 1, NULL, 40);

SET @obermenue_id = LAST_INSERT_ID();

INSERT INTO `u_menues` (`name`, `name_low-german`, `name_english`, `name_polish`, `name_vietnamese`, `links`, `obermenue`, `menueebene`, `target`, `order`) VALUES
('Dokumentenrecherche', 'Oorkunn-Sööke', 'document retrieval', NULL, NULL, 'index.php?go=Nachweisrechercheformular', @obermenue_id, 2, NULL, 0),
('Dokument&nbsp;einf&uuml;gen', 'Oorkunn&nbsp;inf&ouml;gen', 'Dokument&nbsp;einf&uuml;gen', NULL, 'Dokument&nbsp;einf&uuml;gen', 'index.php?go=Nachweisformular', @obermenue_id, 2, NULL, 0),
('Antragsnummer&nbsp;eingeben', 'Andragsnummer&nbsp;ingeven', 'Antragsnummer&nbsp;eingeben', NULL, 'Antragsnummer&nbsp;eingeben', 'index.php?go=Nachweis_antragsnr_form_aufrufen', @obermenue_id, 2, NULL, 0),
('Antr&auml;ge anzeigen', 'Andrage ankieken', 'Antr&auml;ge anzeigen', NULL, 'Antr&auml;ge anzeigen', 'index.php?go=Antraege_Anzeigen', @obermenue_id, 2, NULL, 0);