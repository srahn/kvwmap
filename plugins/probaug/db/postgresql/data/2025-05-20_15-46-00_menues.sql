DO $$
	DECLARE 
		obermenue_id integer;
	BEGIN
	
    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order") VALUES
    ('Bauauskunft', 'Bauauskunft', 'Bauauskunft', NULL, 'ThÃ´ng tin xÃ¢y dá»±ng', 'index.php?go=changemenue', 0, 1, NULL, 140) RETURNING id INTO obermenue_id;

    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, obermenue, menueebene, target, "order") VALUES
    ('Suche', 'Suche', 'Suche', NULL, 'TÃ¬m kiáº¿m', 'index.php?go=Bauauskunft_Suche', obermenue_id, 2, NULL, 0);

END $$
