<?
	# The following new constraints must be fullfilled since this migration
	# - AGGREEMENT_MESSAGE file must be located in CUSTOM_PATH . 'layouts/snippets/'
	# - CUSTOM_STYLES files must be located in CUSTOM_PATH . 'layouts/'
	# - custom Font sets must be located in CUSTOM_PATH . 'fonts/'
	# - custom Graphics must be located in CUSTOM_PATH . 'graphics/'
	# - custom Wappen must be located in CUSTOM_PATH . 'graphics/wappen/'
	# - custom Styles must be located in CUSTOM_PATH . 'layouts/'
	# - custom Snippets must be located in CUSTOM_PATH . 'layouts/snippets/'
	# - custom Symbols must be located in CUSTOM_PATH . 'symbols/'

	$constants = array ();

	# check if custom folder already exists, append random number to default name if exists
	$constants['CUSTOM_PATH'] = (dir_exists(WWWROOT . 'kvwmap_custom/') ? 'kvwmap_custom' . random(3) . '/' : 'kvwmap_custom/');

	# create a new custom_folder from kvwmap_custom repository
	#cd ..
	$command = 'git clone https://github.com/srahn/kvwmap_custom ' . WWWROOT . $constants['CUSTOM_PATH'];
	exec($command);

	# Backing up alle files application folder
	exec('tar cvzf ' . WWWROOT . $constants['CUSTOM_PATH'] . 'kvwmap_before_custom_migration.tar.gz ' . WWWROOT . APPLVERSION);

  # Move all custom files in the new custom folder if exists and change Config parameter

	# Fonts
	if (dir_exists(WWWROOT . APPLVERSION . 'fonts/custom')) {
		rename(WWWROOT . APPLVERSION . 'fonts/custom/*', WWWROOT . $constants['CUSTOM_PATH'] . 'fonts/');
	}
	if (defined('FONTSET') AND FONTSET != 'fonts/fonts.txt') {
		rename(WWWROOT . APPLVERSION . FONTSET, WWWROOT . $constants['CUSTOM_PATH'] . 'fonts/' . basename(FONTSET));
		$constants['FONTSET'] = '../' . $constants['CUSTOM_PATH'] . 'fonts/' . basename(FONTSET);
	}

	# Graphics
	if (dir_exists(WWWROOT . APPLVERSION . 'graphics/custom')) {
		rename(WWWROOT . APPLVERSION . 'graphics/custom/*', WWWROOT . $constants['CUSTOM_PATH'] . 'graphics/');
	}
	if (defined('GRAPHICSPATH') AND GRAPHICSPATH != 'graphics/') {
		rename(WWWROOT . APPLVERSION . GRAPHICSPATH, WWWROOT . $constants['CUSTOM_PATH'] . 'graphics/');
		$constants['GRAPHICSPATH'] = '../' . $constants['CUSTOM_PATH'] . 'graphics/';
	}

	# Wappen
	if (dir_exists(WWWROOT . APPLVERSION . 'graphics/wappen')) {
		rename(WWWROOT . APPLVERSION . 'graphics/wappen/*', WWWROOT . $constants['CUSTOM_PATH'] . 'graphics/wappen/');
	}
	if (defined('WAPPENPATH') AND WAPPENPATH != 'wappen/') {
		rename(WWWROOT . APPLVERSION . WAPPENPATH, WWWROOT . $constants['CUSTOM_PATH'] . 'graphics/wappen/');
		$constants['WAPPENPATH'] = '../../' . $constants['CUSTOM_PATH'] . 'graphics/wappen/';
	}

	# Styles
	if (dir_exists(WWWROOT . APPLVERSION . 'layouts/custom')) {
		rename(WWWROOT . APPLVERSION . 'layouts/custom/*', WWWROOT . $constants['CUSTOM_PATH'] . 'layouts/');
	}
	if (defined('CUSTOM_STYLE') AND CUSTOM_STYLE != '') {
		rename(WWWROOT . APPLVERSION . 'layouts/custom/' . CUSTOM_STYLE, WWWROOT . $constants['CUSTOM_PATH'] . 'layouts/' . basename(CUSTOM_STYLE));
		$constants['CUSTOM_STYLE'] = basename(CUSTOM_STYLE);
	}

	# Snippets
	if (dir_exists(WWWROOT . APPLVERSION . 'layouts/snippets/custom')) {
		rename(WWWROOT . APPLVERSION . 'layouts/snippets/custom/*', WWWROOT . $constants['CUSTOM_PATH'] . 'layouts/snippets/');
	}
	if (defined('AGREEMENT_MESSAGE') AND AGREEMENT_MESSAGE != '') {
		$constants['AGREEMENT_MESSAGE'] = basename(AGGREEMENT_MESSAGE);
	}

	# Symbols
	if (dir_exists(WWWROOT . APPLVERSION . 'symbols/custom')) {
		rename(WWWROOT . APPLVERSION . 'symbols/custom/*', WWWROOT . $constants['CUSTOM_PATH'] . 'symbols/');
	}
	if (defined('SYMBOLSET') AND SYMBOLSET != 'symvols/symbole.sym') {
		rename(WWWROOT . APPLVERSION . SYMBOLSET, WWWROOT . $constants['CUSTOM_PATH'] . 'symbols/' . basename(SYMBOLSET));
		$constants['SYMBOLSET'] = basename(SYMBOLSET);
	}

	$sql = '';
	foreach ($constants AS $key => $value) {
		$sql .= "
			UPDATE
				config
			SET
				value = '" . $value . "'
			WHERE
				name = '" . $key "';
		";
	}
	$result = $this->database->exec_commands($sql, NULL, NULL);
	$result = $this->write_config_file('');

	# ToDos
	# replace mit sed in all custom files in layouts/custom and layouts/snippets/custom
	# layouts/custom/ => '../' . CUSTOM_PATH . 'layouts/'


?>