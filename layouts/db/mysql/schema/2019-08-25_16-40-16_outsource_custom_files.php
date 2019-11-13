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

/*
	Rückgängig machen
	mv custom/graphics/ graphics/custom && \
	mv custom/wappen/ graphics/wappen && \
	mv custom/layouts/snippets layouts/snippets/custom && \
	mv custom/layouts layouts/custom && \
	rm -R custom && \
	chown -R pkorduan.gisadmin * && \
	chown -R pkorduan.gisadmin .* && \
	chmod -R g+w * && \
	chmod -R g+w .*
*/

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
		)
	";
	$this->database->execSQL($sql, 4, 1);

	# Add attribute to config to define what is editable
	# 0 nothing
	# 1 only prefix
	# 2 only value (default)
	# 3 prefix and value
	$sql = "
		ALTER TABLE `config` ADD `editable` INTEGER DEFAULT 2
	";
	$this->database->execSQL($sql, 4, 1);

	# Set nothing editable => 0
	$sql = "
		UPDATE
			`config`
		SET
			`editable` = 0
		WHERE
			`name` IN (
				'CUSTOMPATH'
				'CLASSPATH',
				'GRAPHICSPATH',
				'LAYOUTPATH',
				'SNIPPETS'
			)
	";
	$this->database->execSQL($sql, 4, 1);

	# Set prefix and value editable => 3
	$sql = "
		UPDATE
			`config`
		SET
			`editable` = 3
		WHERE
			`name` IN (
				'CUSTOM_PATH',
				'FONTSET',
				'SYMBOLSET',
				'FOOTER',
				'HEADER',
				'LAYER_ERROR_PAGE',
				'WMS_MAPFILE_PATH'
			)
	";
	$this->database->execSQL($sql, 4, 1);

	# default folder is custom, if custom already exists add random number
	$constants['CUSTOM_PATH'] = array(
		'prefix' => 'WWWROOT.APPLVERSION',
		'value' => 'custom' . (file_exists(WWWROOT . APPLVERSION . 'custom/') ? '_' . rand(1, 3) : '') . '/'
	);

/*
	$constants['CUSTOM_PATH'] = array(
		'prefix' => 'WWWROOT.APPLVERSION',
		'value' => 'custom/'
	);
*/
	define(CUSTOM_PATH, WWWROOT . APPLVERSION . $constants['CUSTOM_PATH']['value']);

	# create a new custom_folder
	echo "<p>create custom_path: " . CUSTOM_PATH;
	mkdir(CUSTOM_PATH);
	chgrp(CUSTOM_PATH, 'gisadmin');
	chmod(CUSTOM_PATH, 0775);

	# Backing up alle files application folder
#  exec('tar cvzf ' . WWWROOT . 'kvwmap_before_custom_migration.tar.gz ' . WWWROOT . APPLVERSION);

	# Move all custom files to the new custom folder if exists and change Config parameter
	echo '<p>FONTSET';
	if (file_exists(WWWROOT . APPLVERSION . 'fonts/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'fonts/custom to: ' . CUSTOM_PATH . 'fonts';
		rename(WWWROOT . APPLVERSION . 'fonts/custom', CUSTOM_PATH . 'fonts');
	}
	if (strpos($this->config_params['FONTSET']['value'], 'fonts/custom/') !== false) {
		echo '<br>prefix: ' . $this->config_params['FONTSET']['prefix'] . ' => CUSTOM_PATH';
		echo '<br>value: ' . $this->config_params['FONTSET']['value'] . ' => ' . str_replace('fonts/custom/', 'fonts/', $this->config_params['FONTSET']['value']);
		$constants['FONTSET'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('fonts/custom', 'fonts', $this->config_params['FONTSET']['value'])
		);
	}

	echo '<p>SYMBOLSET';
	if (file_exists(WWWROOT . APPLVERSION . 'symbols/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'symbols/custom to: ' . CUSTOM_PATH . 'symbols';
		rename(WWWROOT . APPLVERSION . 'symbols/custom', CUSTOM_PATH . 'symbols');
	}
	if (strpos($this->config_params['SYMBOLSET']['value'], 'symbols/custom/') !== false) {
		echo '<br>prefix: ' . $this->config_params['SYMBOLSET']['prefix'] . ' => CUSTOM_PATH';
		echo '<br>value: ' . $this->config_params['SYMBOLSET']['value'] . ' => ' . str_replace('symbols/custom/', 'symbols/', $this->config_params['SYMBOLSET']['value']);
		$constants['SYMBOLSET'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('symbols/custom/', 'symbols/', $this->config_params['SYMBOLSET']['value'])
		);
	}

	echo '<p>GRAPHICSPATH';
	if (file_exists(WWWROOT . APPLVERSION . 'graphics/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'graphics/custom to: ' . CUSTOM_PATH . 'graphics';
		rename(WWWROOT . APPLVERSION . 'graphics/custom', CUSTOM_PATH . 'graphics');
	}

	echo '<p>WAPPENPATH';
	if (file_exists(WWWROOT . APPLVERSION . WAPPENPATH)) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . WAPPENPATH . ' to: ' . CUSTOM_PATH . 'wappen';
		rename(WWWROOT . APPLVERSION . WAPPENPATH, CUSTOM_PATH . 'wappen');
	}
	echo '<br>value: ' . $this->config_params['WAPPENPATH']['value'] . ' => ' . $this->config_params['WAPPENPATH']['value'];
	$constants['WAPPENPATH'] = array(
		'value' => 'custom/' . $this->config_params['WAPPENPATH']['value']
	);

	# Set deleted wappen files as untracked as they sould in .gitignore
	exec("git update-index --assume-unchanged $(git ls-files | grep 'graphics/wappen')");

	echo '<p>LAYOUTS ' . $this->config_params['LAYOUTS']['value'];
	if (file_exists(WWWROOT . APPLVERSION . 'layouts/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'layouts/custom to: ' . CUSTOM_PATH . 'layouts';
		rename(WWWROOT . APPLVERSION . 'layouts/custom', CUSTOM_PATH . 'layouts');
	}

	echo '<p>SNIPPETS ' . $this->config_params['SNIPPETS']['value'];
	if (file_exists(WWWROOT . APPLVERSION . 'layouts/snippets/custom')) {
		echo '<br>move: ' . WWWROOT . APPLVERSION . 'layouts/snippets/custom to: ' . CUSTOM_PATH . 'layouts/snippets';
		rename(WWWROOT . APPLVERSION . 'layouts/snippets/custom', CUSTOM_PATH . 'layouts/snippets');
	}

	echo '<p>LOGIN ' . $this->config_params['LOGIN']['value'];
	if (strpos(LOGIN, 'custom/') !== false) {
		echo '<br>set: ' . LOGIN . ' prefix to: CUSTOM_PATH and value to: ' . str_replace('custom/', 'layouts/snippets/', LOGIN);
		$constants['LOGIN'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', LOGIN)
		);
	}
	else {
		echo '<br>set: ' . LOGIN . ' prefix to: LAYOUTPATH and value to: ' . LOGIN;
		$constants['LOGIN'] = array(
			'prefix' => 'SNIPPETS',
			'value' => LOGIN
		);
	}

	echo '<p>LOGIN_AGREEMENT ' . $this->config_params['LOGIN_AGREEMENT']['value'];
	if (strpos(LOGIN_AGREEMENT, 'custom/') !== false) {
		echo '<br>set: ' . LOGIN_AGREEMENT . ' prefix to: CUSTOM_PATH and value to: ' . str_replace('custom/', 'layouts/snippets/', LOGIN_AGREEMENT);
		$constants['LOGIN_AGREEMENT'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', LOGIN_AGREEMENT)
		);
	}
	else {
		echo '<br>set: ' . LOGIN_AGREEMENT . ' prefix to: LAYOUTPATH and value to: ' . LOGIN_AGREEMENT;
		$constants['LOGIN_AGREEMENT'] = array(
			'prefix' => 'SNIPPETS',
			'value' => LOGIN_AGREEMENT
		);
	}
	$cmd = 'sed -i -e "s|SNIPPETS.AGREEMENT_MESSAGE|AGREEMENT_MESSAGE|g" ' . WWWROOT. APPLVERSION . CUSTOM_PATH . 'layouts/snippets/*';
	echo '<br>Replace cmd: ' . $cmd;
	exec($cmd);

	echo '<p>LOGIN_NEW_PASSWORD ' . $this->config_params['LOGIN_NEW_PASSWORD']['value'];
	if (strpos(LOGIN_NEW_PASSWORD, 'custom/') !== false) {
		echo '<br>set: ' . LOGIN_NEW_PASSWORD . ' prefix to: CUSTOM_PATH and value to: ' . str_replace('custom/', 'layouts/snippets/', LOGIN_NEW_PASSWORD);
		$constants['LOGIN_NEW_PASSWORD'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', LOGIN_NEW_PASSWORD)
		);
	}
	else {
		echo '<br>set: ' . LOGIN_NEW_PASSWORD . ' prefix to: LAYOUTPATH and value to: ' . LOGIN_NEW_PASSWORD;
		$constants['LOGIN_NEW_PASSWORD'] = array(
			'prefix' => 'SNIPPETS',
			'value' => LOGIN_NEW_PASSWORD
		);
	}

	echo '<p>LOGIN_REGISTRATION ' . $this->config_params['LOGIN_REGISTRATION']['value'];
	if (strpos(LOGIN_REGISTRATION, 'custom/') !== false) {
		echo '<br>set: ' . LOGIN_REGISTRATION . ' prefix to: CUSTOM_PATH and value to: ' . str_replace('custom/', 'layouts/snippets/', LOGIN_REGISTRATION);
		$constants['LOGIN_REGISTRATION'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', LOGIN_REGISTRATION)
		);
	}
	else {
		echo '<br>set: ' . LOGIN_REGISTRATION . ' prefix to: LAYOUTPATH and value to: ' . LOGIN_REGISTRATION;
		$constants['LOGIN_REGISTRATION'] = array(
			'prefix' => 'SNIPPETS',
			'value' => LOGIN_REGISTRATION
		);
	}

	echo '<p>LOGIN_ROUTINE ' . $this->config_params['LOGIN_ROUTINE']['value'];
	$constants['LOGIN_ROUTINE'] = array(
		'prefix' => 'CUSTOM_PATH',
		'value' => str_replace('custom/', 'layouts/snippets/', LOGIN_ROUTINE)
	);

	echo '<p>LOGOUT_ROUTINE ' . $this->config_params['LOGOUT_ROUTINE']['value'];
	$constants['LOGOUT_ROUTINE'] = array(
		'prefix' => 'CUSTOM_PATH',
		'value' => str_replace('custom/', 'layouts/snippets/', LOGOUT_ROUTINE)
	);

	echo '<br>set AGREEMENT_MESSAGE prefix to: CUSTOM_PATH and value to: ' . str_replace('custom/', 'layouts/snippets/', AGREEMENT_MESSAGE);
	$constants['AGREEMENT_MESSAGE'] = array(
		'prefix' => 'CUSTOM_PATH',
		'value' => (strpos(LOGIN_REGISTRATION, 'custom/') !== false ? str_replace('custom/', 'layouts/snippets/', LOGIN_REGISTRATION) : 'layouts/snippets/' . LOGIN_REGISTRATION)
	);

	echo '<p>CUSTOM_STYLE';
	$constants['CUSTOM_STYLE'] = array(
		'prefix' => 'CUSTOM_PATH',
		'value' => str_replace('custom/', 'layouts/', $this->config_params['CUSTOM_STYLE']['value'])
	);

	echo '<p>FOOTER: ' . $this->config_params['FOOTER']['value'];
	if (strpos(FOOTER, 'custom/') !== false) {
		echo '<br>prefix: ' . $this->config_params['FOOTER']['prefix'] . ' => CUSTOM_PATH';
		echo '<br>value: ' . $this->config_params['FOOTER']['value'] . ' => ' . $this->config_params['FOOTER']['value'];
		$constants['FOOTER'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', $this->config_params['FOOTER']['value'])
		);
	}
	else {
		echo '<br>prefix: ' . $this->config_params['FOOTER']['prefix'] . ' => LAYOUTPATH';
		$constants['FOOTER'] = array(
			'prefix' => 'SNIPPETS'
		);
	}
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".FOOTER|FOOTER|g" ' . WWWROOT. APPLVERSION . CUSTOM_PATH . 'layouts/*';
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".FOOTER|FOOTER|g" ' . WWWROOT. APPLVERSION . CUSTOM_PATH . 'layouts/snippets/*';
	echo '<br>Replace cmd: ' . $cmd;
	exec($cmd);

	echo '<p>HEADER: ' . $this->config_params['HEADER']['value'];
	if (strpos(HEADER, 'custom/') !== false) {
		echo '<br>prefix: ' . $this->config_params['HEADER']['prefix'] . ' => CUSTOM_PATH';
		echo '<br>value: ' . $this->config_params['HEADER']['value'] . ' => ' . $this->config_params['HEADER']['value'];
		$constants['HEADER'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', $this->config_params['HEADER']['value'])
		);
	}
	else {
		echo '<br>prefix: ' . $this->config_params['HEADER']['prefix'] . ' => LAYOUTPATH';
		$constants['HEADER'] = array(
			'prefix' => 'SNIPPETS'
		);
	}
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".HEADER|HEADER|g" ' . CUSTOM_PATH . 'layouts/*';
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".HEADER|HEADER|g" ' . CUSTOM_PATH . 'layouts/snippets/*';
	echo '<br>Replace cmd: ' . $cmd;
	exec($cmd);

	echo '<p>LAYER_ERROR_PAGE: ' . $this->config_params['LAYER_ERROR_PAGE']['value'];
	if (strpos(LAYER_ERROR_PAGE, 'custom/') !== false) {
		echo '<br>prefix: ' . $this->config_params['LAYER_ERROR_PAGE']['prefix'] . ' => CUSTOM_PATH';
		echo '<br>value: ' . $this->config_params['LAYER_ERROR_PAGE']['value'] . ' => ' . $this->config_params['LAYER_ERROR_PAGE']['value'];
		$constants['LAYER_ERROR_PAGE'] = array(
			'prefix' => 'CUSTOM_PATH',
			'value' => str_replace('custom/', 'layouts/snippets/', $this->config_params['LAYER_ERROR_PAGE']['value'])
		);
	}
	else {
		echo '<br>prefix: ' . $this->config_params['LAYER_ERROR_PAGE']['prefix'] . ' => LAYOUTPATH';
		$constants['LAYER_ERROR_PAGE'] = array(
			'prefix' => 'SNIPPETS'
		);
	}
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".LAYER_ERROR_PAGE|LAYER_ERROR_PAGE|g" ' . CUSTOM_PATH . 'layouts/*';
	$cmd = 'sed -i -e "s|LAYOUTPATH.\"snippets/\".LAYER_ERROR_PAGE|LAYER_ERROR_PAGE|g" ' . CUSTOM_PATH . 'layouts/snippets/*';
	echo '<br>Replace cmd: ' . $cmd;
	exec($cmd);

	echo '<p>sizes' . $this->config_params['sizes']['value'];
	echo '<br>Replace "gui.php" by "layouts/gui.php"';
	echo '<br>Replace "custom/" by "custom/layouts/"';
	$constants['sizes'] = array(
		'value' => str_replace('"custom/"', '"custom/layouts/"', str_replace('"gui.php"', '"layouts/gui.php"', $this->config_params['sizes']['value']))
	);

	foreach ($constants AS $name => $constant) {
		$sql = "
			UPDATE
				config
			SET
				" . (array_key_exists('prefix', $constant) ? "prefix = '" . $constant['prefix'] . "'," : "") . "
				" . (array_key_exists('value', $constant) ? "value = '" . $constant['value'] . "'," : "") . "
				saved = 0
			WHERE
				name = '" . $name . "'
		";
		echo '<br>Update Konstante mit SQL: ' . $sql;
		$this->database->execSQL($sql, 4, 1);
	}

	$sql = "
		UPDATE
			u_menues
		SET
			links = REPLACE(links, 'layouts/snippets/custom/', '" . $constants['CUSTOM_PATH']['value'] . "layouts/snippets/')
		WHERE
			links LIKE '%layouts/snippets/custom/%'
	";
	$this->database->execSQL($sql, 4, 1);

	$sql = "
		UPDATE
			rolle
		SET
			gui = CASE WHEN gui LIKE '%custom/%' THEN concat('" . $constants['CUSTOM_PATH']['value'] . "layouts/', SUBSTRING_INDEX(gui, '/', -1)) ELSE concat('layouts/', gui) END
	";
	$this->database->execSQL($sql, 4, 1);

	$cmd = 'sed -i -e "s|layouts/snippets/custom/|' . $constants['CUSTOM_PATH']['value'] . '/layouts/snippets/|g" ' . WWWROOT. APPLVERSION . 'custom/layouts/snippets/*';
	echo '<br>Replace path by cmd: ' . $cmd;
	exec($cmd);
	$cmd = 'sed -i -e "s|layouts/custom/|' . $constants['CUSTOM_PATH']['value'] . '/layouts/|g" ' . WWWROOT. APPLVERSION . 'custom/layouts/*';
	echo '<br>Replace path by cmd: ' . $cmd;
	exec($cmd);

	$this->get_config_params();
	$result = $this->write_config_file('');
?>