DO $$
	DECLARE 
		obermenue_id integer;
	BEGIN

    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, onclick, obermenue, menueebene, target, "order", title, button_class) VALUES
    ('Pläne', '', '', '', '', 'index.php?go=changemenue', '', 0, 1, '', 20, '', '') RETURNING id INTO obermenue_id;

    INSERT INTO kvwmap.u_menues (name, name_low_german, name_english, name_polish, name_vietnamese, links, onclick, obermenue, menueebene, target, "order", title, button_class) VALUES
    ('B-Pläne', '', '', '', '', 'index.php?go=xplankonverter_plaene_index&planart=BP-Plan', '', obermenue_id, 2, '', 20, '', ''),
    ('F-Pläne', '', '', '', '', 'index.php?go=xplankonverter_plaene_index&planart=FP-Plan', '', obermenue_id, 2, '', 22, '', ''),
    ('SO-Pläne', '', '', '', '', 'index.php?go=xplankonverter_plaene_index&planart=SO-Plan', '', obermenue_id, 2, '', 24, '', ''),
    ('R-Pläne', '', '', '', '', 'index.php?go=xplankonverter_plaene_index&planart=RP-Plan', '', obermenue_id, 2, '', 26, '', '');

END $$
