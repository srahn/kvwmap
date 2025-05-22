DO $$
	DECLARE 
		obermenue_id integer;
    selected_layer_id integer;
	BEGIN
	
    -- Obermenü Fortführungslisten
    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order")
    VALUES ('Fortführungslisten', 'Fortführungen-Verwalten', NULL, NULL, NULL, 'index.php?go=changemenue', 0, 1, NULL, 40) RETURNING id INTO obermenue_id;

    -- Untermenü FN-Listen drucken
    SELECT layer_id INTO selected_layer_id FROM kvwmap.layer WHERE name = 'Fortführungslisten Gemarkungen';
    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order")
    VALUES ('FN-Listen&nbsp;drucken', 'FN-Listen&nbsp;drucken', NULL, NULL, NULL, concat('index.php?go=Layer-Suche&selected_layer_id=', selected_layer_id), obermenue_id, 2, NULL, 0);

    -- Untermenü FN anlegen
    SELECT layer_id INTO selected_layer_id FROM kvwmap.layer WHERE name = 'Fortführungsnachweise';
    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order")
    VALUES ('FN&nbsp;anlegen', 'FN&nbsp;ingeven', NULL, NULL, NULL, concat('index.php?go=neuer_Layer_Datensatz&selected_layer_id=', selected_layer_id), obermenue_id, 2, NULL, 0);

    -- Untermenü FN suchen/bearbeiten
    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order")
    VALUES ('FN&nbsp;suchen/bearbeiten', 'FN&nbsp;sööke', NULL, NULL, NULL, 'index.php?go=fortfuehrungslisten_fn_suche', obermenue_id, 2, NULL, 0);

END $$
