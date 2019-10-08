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


	# entferne anschließend #pk und die kompletten zeilen mit #rmpk
	# /*pk Kommentar wieder rausnehmen bis pk*/

	$constants = array ();

	$sql = "
		INSERT INTO `config` (
			`name`,
			`value`,
			`description`,
			`type`,
			`group`,
			`saved`
		) VALUES (
			'CUSTOM_PATH',
			'custom/',
			'Pfad in dem sich Dateien befinden, die nicht vom kvwmap Repository getrackt werden.',
			'string',
			'Pfadeinstellungen',
			'0'
		);
	";
	$this->database->execSQL($sql, 4, 1);

	# default folder is custom, if custom already exists add random number
	$constants['CUSTOM_PATH'] = array(
		'prefix' => 'WWWROOT.APPLVERSION',
		'wert' => 'custom' . (file_exists(WWWROOT . APPLVERSION . 'custom/') ? '_' . rand(1, 3) : '') . '/'
	);

/*
	$constants['CUSTOM_PATH'] = array(
		'prefix' => 'WWWROOT.APPLVERSION',
		'wert' => 'custom/'
	);
*/
	define(CUSTOM_PATH, WWWROOT . APPLVERSION . $constants['CUSTOM_PATH']['wert']);

	# create a new custom_folder
	echo "<p>create custom_path: " . CUSTOM_PATH;
	mkdir(CUSTOM_PATH);
	chgrp(CUSTOM_PATH, 'gisadmin');
	chmod(CUSTOM_PATH, 0775);

	# Backing up alle files application folder
#  exec('tar cvzf ' . WWWROOT . 'kvwmap_before_custom_migration.tar.gz ' . WWWROOT . APPLVERSION);

	# Move all custom files to the new custom folder if exists and change Config parameter

	# FONTSET
	if (file_exists(WWWROOT . APPLVERSION . 'fonts/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'fonts/custom to: ' . CUSTOM_PATH . 'fonts';
		rename(WWWROOT . APPLVERSION . 'fonts/custom', CUSTOM_PATH . 'fonts');
	}
	if (strpos(FONTSET, 'fonts/custom') !== false) {
		echo '<br>set: ' . FONTSET . ' to: ' . str_replace('fonts/custom', $constants['CUSTOM_PATH']['wert'] . 'fonts', str_replace(WWWROOT . APPLVERSION, '', FONTSET));
		$constants['FONTSET'] = array(
			'prefix' => 'WWWROOT.APPLVERSION',
			'wert' => str_replace('fonts/custom', $constants['CUSTOM_PATH']['wert'] . 'fonts', str_replace(WWWROOT . APPLVERSION, '', FONTSET))
		);
	}

	# SYMBOLSET
	if (file_exists(WWWROOT . APPLVERSION . 'symbols/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'symbols/custom to: ' . CUSTOM_PATH . 'symbols';
		rename(WWWROOT . APPLVERSION . 'symbols/custom', CUSTOM_PATH . 'symbols');
	}
	if (strpos(SYMBOLSET, 'symbols/custom') !== false) {
		echo '<br>set: ' . SYMBOLSET . ' to: ' . str_replace('symbols/custom', $constants['CUSTOM_PATH']['wert'] . 'symobls', str_replace(WWWROOT . APPLVERSION, '', SYMBOLSET));
		$constants['SYMBOLSET'] = array(
			'prefix' => 'WWWROOT.APPLVERSION',
			'wert' => str_replace('symbols/custom', $constants['CUSTOM_PATH']['wert'] . 'symobls', str_replace(WWWROOT . APPLVERSION, '', SYMBOLSET))
		);
	}

	# GRAPHICSPATH
	if (file_exists(WWWROOT . APPLVERSION . 'graphics/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'graphics/custom to: ' . CUSTOM_PATH . 'graphics';
		rename(WWWROOT . APPLVERSION . 'graphics/custom', CUSTOM_PATH . 'graphics');
	}
	if (strpos(GRAPHICSPATH, 'graphics/custom') !== false) {
		echo '<br>set: ' . GRAPHICSPATH . ' to: ' . str_replace('graphics/custom', $constants['CUSTOM_PATH']['wert'] . 'graphics', GRAPHICSPATH);
		$constants['GRAPHICSPATH'] = array(
			'prefix' => '',
			'wert' => str_replace('graphics/custom', $constants['CUSTOM_PATH']['wert'] . 'graphics', GRAPHICSPATH)
		);
	}

	# WAPPENPATH
	if (file_exists(WWWROOT . APPLVERSION . WAPPENPATH)) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . WAPPENPATH . ' to: ' . CUSTOM_PATH . 'wappen';
		rename(WWWROOT . APPLVERSION . WAPPENPATH, CUSTOM_PATH . 'wappen');
	}
	$constants['WAPPENPATH'] = array(
		'prefix' => '',
		'wert' => str_replace('graphics/wappen/', 'custom/wappen/', WAPPENPATH)
	);

	# Set deleted wappen files as untracked as they sould in .gitignore
	exec("git update-index --assume-unchanged $(git ls-files | grep 'graphics/wappen')");

	# LAYOUTS
	if (file_exists(WWWROOT . APPLVERSION . 'layouts/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'layouts/custom to: ' . CUSTOM_PATH . 'layouts';
		rename(WWWROOT . APPLVERSION . 'layouts/custom', CUSTOM_PATH . 'layouts');
	}
	if (strpos(LAYOUTPATH, 'layouts/custom') !== false) {
		echo '<br>set: ' . LAYOUTPATH . ' to: ' . str_replace('layouts/custom', $constants['CUSTOM_PATH']['wert'] . 'layouts', LAYOUTPATH);
		$constants['LAYOUTPATH'] = array(
			'prefix' => 'WWWROOT.APPLVERSION',
			'wert' => str_replace('layouts/custom', $constants['CUSTOM_PATH']['wert'] . 'layouts', LAYOUTPATH)
		);
	}

	# SNIPPETS
	if (file_exists(WWWROOT . APPLVERSION . 'layouts/snippets/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'layouts/snippets/custom to: ' . CUSTOM_PATH . 'layouts/snippets';
		rename(WWWROOT . APPLVERSION . 'layouts/snippets/custom', CUSTOM_PATH . 'layouts/snippets');
	}
	if (strpos(SNIPPETS, 'snippets/custom') !== false) {
		echo '<br>set: ' . SNIPPETS . ' to: ' . str_replace('snippets/custom', '../' . $constants['CUSTOM_PATH']['wert'] . 'layouts/snippets', SNIPPETS);
		$constants['SNIPPETS'] = array(
			'prefix' => 'CUSTOM_PATH',
			'wert' => str_replace('snippets/custom', $constants['CUSTOM_PATH']['wert'] . 'layouts/snippets', LAYOUTPATH)
		);
	}

	$sql = '';
	foreach ($constants AS $key => $value) {
		$sql .= "
			UPDATE
				config
			SET
				value = '" . $value['wert'] . "',
				prefix = '" . $value['prefix'] . "',
				saved = 0
			WHERE
				name = '" . $key . "';
		";
	}
	echo '<br>Update Konstants mit SQL: ' . $sql;
	$this->database->exec_commands($sql, NULL, NULL);

	$sql = "
		UPDATE
			u_menues
		SET
			link = REPLACE(link, 'layouts/snippets/custom/', '" . CUSTOM_PATH . "layouts/snippets/')
		WHERE
			link LIKE '%layouts/snippets/custom/%'
	";
	$this->database->execSQL($sql, 4, 1);
	
	$cmd = 'sed -i -e "s|layouts/snippets/custom/|' . CUSTOM_PATH . 'layouts/snippets/|g" ' . WWWROOT. APPLVERSION . CUSTOM_PATH . '*';
	echo '<br>Replace path by cmd: ' . $cmd;
	exec($cmd);
	$cmd = 'sed -i -e "s|layouts/custom/|' . CUSTOM_PATH . 'layouts/|g" ' . WWWROOT. APPLVERSION . CUSTOM_PATH . '*';
	echo '<br>Replace path by cmd: ' . $cmd;
	exec($cmd);

	$this->get_config_params();
	$result = $this->write_config_file('');
?>