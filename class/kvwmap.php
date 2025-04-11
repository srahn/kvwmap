<?php
###################################################################
# kvwmap - Kartenserver für die Verwaltung raumbezogener Daten.   #
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Eine deutsche Übersetzung zur Lizenz finden Sie unter:          #
# http://www.gnu.de/gpl-ger.html                                  #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################

###################################################################
# Klasse für die Funktionen der graphische Benutzeroberfläche     #
###################################################################
class GUI {

	var $alert;
	var $classes;
	var $group_has_active_layers;
	var $gui;
	var $go;
	var $layerset;
	var $layout;
	var $sorted_layerset;
	var $style;
	var $mime_type;
	var $menue;
	var $pdf;
	var $addressliste;
	var $debug;
	var $xlog;
	var $database;
	var $mysqli;
	var $flst;
	var $formvars = array();
	var $legende;
	var $map;
	var $mapdb;
	var $img;
	var $FormObject;
	var $StellenForm;
	var $Fehlermeldung;
	var $Hinweis;
	var $Stelle;
	var $ALB;
	var $activeLayer;
	var $nImageWidth;
	var $nImageHeight;
	var $user;
	var $qlayerset;
	var $scaleUnitSwitchScale;
	var $map_scaledenom;
	var $map_factor='';
	var $formatter;
	var $success = true;
	var $login_failed;
	var $only_main = false;
	var $class_load_level;
	var $layer_id_string;
	var $noMinMaxScaling;
	var $stelle_id;
	var $angle_attribute;
	var $titel;
	var $PasswordError;
	var $Meldung;
	var $radiolayers;
	var $show_query_tooltip;
	var $last_query;
	var $querypolygon;
	var $new_entry;
	var $search;
	var $form_field_names;
	var $editable;
	var $groupset;
	var $use_form_data;
	var $stelle;
	var $zoomed;
	var $error_position;
	var $selected_search;
	var $attributes = array();
	var $scrolldown;
	var $queryrect;
	var $notices;
	var $layers_replace_scale = array();
	var $log_mysql;
	var $log_postgres;
	static $messages = array();
	var $t_visible;
	var $log_loginfail;
	var $main;
	var $trigger_functions;
	var $custom_trigger_functions;
	var $goNotExecutedInPlugins;
	var $layer;

	# Konstruktor
	function __construct($main, $style, $mime_type) {
		# Debugdatei setzen
		global $debug;
		$this->debug = $debug;

		# Logdatei für Mysql setzen
		global $log_mysql;
		$this->log_mysql = $log_mysql;

		# Logdatei für PostgreSQL setzten
		global $log_postgres;
		$this->log_postgres = $log_postgres;

		global $log_loginfail;
		$this->log_loginfail = $log_loginfail;

		# layout Templatedatei zur Anzeige der Daten
		if ($main != "") {
			$this->main = $main;
		}

		# Stylesheetdatei
		if (isset($style)) {
			$this->style = $style;
		}

		# mime_type html, pdf
		if (isset ($mime_type)) $this->mime_type=$mime_type;
		$this->scaleUnitSwitchScale = 239210;
		$this->trigger_functions = array();
		$this->custom_trigger_functions = array();

		$this->queryrect = new rectObj();
	}

	public function __call($method, $arguments){
		if (isset($this->{$method}) && is_callable($this->{$method})) {
			return call_user_func_array($this->{$method}, $arguments);
		}
	}

	function write_xlog($msg) {
		if (!empty($this->xlog)) {
			$this->xlog->write($msg);
		}
	}

	/**
	 * function sanitizes all values of $this->formvars array
	 * with names by type given in $vars array
	 * @param	$vars Array of formvars elements and types
	 * 				eg: ['selected_layer_id' => 'int', name' => 'text']
	 * 				allowed types: 'int', 'text'
	 * @param bool $removeTT If true, floats and integers are converted by the function removeTausenderTrenner
	 * @return	$this->formvars is passed by reference to sanitize function,
	 * 				That's why it is changed at the end of this function
	*/
	function sanitize($vars, $removeTT = false) {
		$vars_with_asterisk = array_filter(
			$vars,
			function($key) {
				return substr($key, -1) === '*';
			},
			ARRAY_FILTER_USE_KEY
		);
		foreach ($vars_with_asterisk as $asterisk_key => $asterisk_type) {
			foreach ($this->formvars as $formvar_key => $value) {
				if (strpos($formvar_key, substr($asterisk_key, 0, -1)) === 0) {
					#echo '<br>Set sanitize key: ' . $formvar_key . ' => ' . $asterisk_type;
					$vars[$formvar_key] = $asterisk_type;
				}
			}
			#echo'<br>Remove sanitize key: ' . $asterisk_key;
			unset($vars[$asterisk_key]);
		}
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type, $removeTT);
		}
	}
	
	function saveDrawmode(){
		$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
	}

	function layer_check_oids() {
		$this->main = 'layer_check_oids.php';
		$this->output();
	}

	function login() {
		if ($this->formvars['format'] == 'json') {
			$this->mime_type = 'application/json';
			$this->formvars['content_type'] = 'application/json';
			$this->qlayerset[0]['shape'] = array(
				'success' => false,
				'msg' => 'Login erforderlich'
			);
		}
		else {
			$this->expect = array('login_name', 'passwort', 'mobile');
			if (array_key_exists('go', $this->formvars) AND $this->formvars['go'] == 'logout') {
				$this->expect[] = 'go';
			}
			$this->gui = LOGIN;
		}
		$this->output();
	}

	/**
	 * Check if the login is granted. If not set the login failed reason
	 * @return true if granted, false if not.
	 */
	function is_login_granted($user, $login_name, $password) {
		# check if login_name is locked
		if ($user->login_is_locked()) {
			$this->login_failed_reason = 'login_is_locked';
			return false;
		}

		# check if login_name exists
		if ($user->login_name != $login_name) {
			$this->login_failed_reason = 'wrong_login_name';
			return false;
		}

		# check if user is archived
		if ($user->archived) {
			$this->login_failed_reason = 'archived';
			return false;
		}

		# check if the password ist correct
		if ($user->wrong_password($password)) {
			$this->login_failed_reason = 'authentication';
			return false;
		}

		# check if the login is granted not yet
		if ($user->start != '0000-00-00' AND date('Y-m-d') < $user->start) {
			$this->login_failed_reason = 'not_yet_started';
			return false;
		}

		# check if the login is not granted any more
		if ($user->stop != '0000-00-00' AND date('Y-m-d') > $user->stop) {
			$this->login_failed_reason = 'expired';
			return false;
		}

		return true;
	}

	function login_failed() {
		$this->login_failed = true;
		$this->expect = array('login_name', 'passwort', 'mobile');
		if ($this->formvars['go'] == 'logout') {
			$this->expect[] = 'go';
		}
		$this->log_loginfail->write(
			date("Y:m:d H:i:s", time()) .
			' IP: ' . get_remote_ip() .
			' Port: ' . $_SERVER['REMOTE_PORT'] .
			' User: ' . $login_name .
			' User agent: ' .
			getenv('HTTP_USER_AGENT')
		);
		$this->gui = (file_exists(LOGIN) ? LOGIN : SNIPPETS . 'login.php');
		if (strpos(file_get_contents($this->gui), 'include(LAYOUTPATH . \'languages/login_\'') === false) {
			switch ($this->login_failed_reason) {
				case 'authentication' : {
					$this->add_message('error', 'Passwort ' . ($this->formvars['num_failed'] > 0 ? $this->formvars['num_failed'] . ' mal' : '') . ' falsch eingegeben!');
				} break;
				case 'login_is_locked' : {
					$this->add_message('error', 'Der Zugang ist wegen mehrfacher falscher Eingabe bis<br>' . (new DateTime($this->user->login_locked_until))->format('d.m.Y H:i:s') . ' gesperrt!');
				} break;
				case 'expired' : {
					$this->add_message('error', 'Der zeitlich eingeschränkte Zugang des Nutzers ist abgelaufen.');
				} break;
				case 'not_yet_started' : {
					$this->add_message('error', 'Der zeitlich eingeschränkte Zugang des Nutzers hat noch nicht begonnen.');
				} break;
			}
		}
		$this->output();
	}

	function login_browser_size() {
		$this->gui = SNIPPETS . 'login_browser_size.php';
		$this->output();
	}

	function login_new_password() {
		$this->expect = array('passwort', 'new_password', 'new_password_2');
		if ($this->formvars['go'] == 'logout') {
			# Nicht nochmal go = logout, sonst kommt man da nicht mehr raus.
			$this->expect[] = 'go';
		}
		$this->gui = LOGIN_NEW_PASSWORD;
		$this->output();
	}

	function login_registration() {
		include_once(CLASSPATH . 'Invitation.php');
		$this->invitation = Invitation::find_by_id($this, $this->formvars['token']);
		if ($this->formvars['login_name'] == '') {
			$this->formvars['login_name'] = strToLower(substr($this->invitation->inviter->get('Vorname'), 0, 1) . $this->invitation->inviter->get('Name'));
		}
		$this->expect = array('login_name', 'new_password', 'new_password_2');
		$this->gui = LOGIN_REGISTRATION;
		$this->output();
	}

	function login_agreement() {
		$this->expect = array('agreement_accepted');
		$this->gui = LOGIN_AGREEMENT;
		$this->output();
	}

	/**
	* @param $fired BEFORE/AFTER
	* @param $event INSERT/UPDATE/DELETE
	* @param $layer Array mit den Layerinformationen (Layer-ID, Layername, ...)
	* @param $oid ID des Datensatzes (bei BEFORE INSERT leer)
	* @param $old_dataset Der Datensatz aus der DB vor der Änderung (bei INSERT leer)
	*/
	function exec_trigger_function($fired, $event, $layer, $oid = '', $old_dataset = array()) {
		global $GUI;
		$custom_kvwmap_php = WWWROOT.APPLVERSION.CUSTOM_PATH.'class/kvwmap.php';
		if(file_exists($custom_kvwmap_php)){
			include_once($custom_kvwmap_php);
		}
		$trigger_result = array('executed' => false);
		if (array_key_exists($layer['trigger_function'], $this->trigger_functions)) {
			$trigger_result = $this->trigger_functions[$layer['trigger_function']](
				$fired,
				$event,
				$layer,
				$oid,
				$old_dataset
			);
		}
		if (array_key_exists($layer['trigger_function'], $this->custom_trigger_functions)) {
			$trigger_result = $this->custom_trigger_functions[$layer['trigger_function']](
				$fired,
				$event,
				$layer,
				$oid,
				$old_dataset
			);
		}
		return $trigger_result;
	}

	function geo_name_query() {
		$stellen_extent = $this->Stelle->MaxGeorefExt;
		$projFROM = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
		$projTO = new projectionObj("init=epsg:4326");
		$stellen_extent->project($projFROM, $projTO);
		$url = GEO_NAME_SEARCH_URL
			. urlencode($this->formvars['q'])
			. (strpos(GEO_NAME_SEARCH_URL, 'viewbox=') === false ? '&viewbox=' . $stellen_extent->minx . ',' . $stellen_extent->miny . ',' . $stellen_extent->maxx . ',' . $stellen_extent->maxy : '');
		$result = json_decode(
			url_get_contents(
				$url,
				NULL,
				NULL,
				'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0'
			),
			true
		);
		$show = false;
		if (array_key_exists('features', $result)){
			$features_key = 'features';
		}
		if (array_key_exists('results', $result)){
			$features_key = 'results';
		}
		for ($i = 0; $i < count_or_0($result[$features_key]); $i++) {
			if (array_key_exists('geometry', $result[$features_key][$i])) {
				if (is_array($result[$features_key][$i]['geometry']) AND array_key_exists('coordinates', $result[$features_key][$i]['geometry'])) {		# geojson
					$coord = $result[$features_key][$i]['geometry']['coordinates'];
					if ($stellen_extent->minx < $coord[0] AND $coord[0] < $stellen_extent->maxx AND $stellen_extent->miny < $coord[1] AND $coord[1] < $stellen_extent->maxy) {
						$show = true;
						$name = $result[$features_key][$i]['properties'][GEO_NAME_SEARCH_PROPERTY];
						$output .= '<li><a href="index.php?go=zoom2coord&INPUT_COORD='.$coord[0].','.$coord[1].'&epsg_code=4326&name='.$name.'">'.$name.'</a></li>';
					}
				}
				else {		# json (wie bei search.geobasis-bb.de)
					$show = true;
					$name = $result[$features_key][$i][GEO_NAME_SEARCH_PROPERTY];
					$output .= '<li><a href="index.php?go=zoom2wkt&wkt=' . $result[$features_key][$i]['geometry'] . '&epsg=4326&name='.$name.'">'.$name.'</a></li>';
				}
			}
		}
		if ($show) {
			echo '<div style="position: absolute;top: 0px;right: 0px">
							<a href="javascript:void(0)" onclick="document.getElementById(\'geo_name_search_result_div\').innerHTML=\'\';" title="Schlie&szlig;en">
								<img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img>
							</a>
						</div>
						<ul>'.$output.'</ul>';
		}
	}

	function show_snippet() {
		if (!array_key_exists('snippet', $this->formvars) OR empty($this->formvars['snippet'])) {
			$error_msg = 'Geben Sie im Parameter snippets einen Namen für eine Datei an!';
		}
		else {
			$snippet_path = WWWROOT . APPLVERSION . CUSTOM_PATH . 'layouts/snippets/';
			$snippet_file = $this->formvars['snippet'] . '.php';
			if (!file_exists($snippet_path . $snippet_file)) {
				$error_msg = 'Die Datei ' . $snippet_path . $snippet_file . ' existiert nicht. Geben Sie einen anderen Namen im Parameter snippet an!';
			}
		}
		if (empty($error_msg)) {
			if (strtolower($this->formvars['format']) == 'json' OR strtolower($this->formvars['mime_type']) == 'xml') {
				include_once($snippet_path . $snippet_file);
			}
		}
		else {
			$this->add_message('error', $error_msg);
			$this->loadMap('DataBase');
			// $this->user->rolle->newtime = $this->user->rolle->last_time_id;
			// $this->saveMap('');
			// $this->drawMap();
			$this->legende = $this->create_dynamic_legend();
		}
		$this->main = '../../' . CUSTOM_PATH . 'layouts/snippets/' . $snippet_file;
		$this->output();
	}

	function loadDrawingOrderForm(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayer = true;
		$layerset = $mapDB->read_Layer(0, $this->Stelle->useLayerAliases, NULL);		# class_load_level: 0 = keine Klassen laden
		$layer = array_reverse($layerset['list']);
		echo '<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
		for($i = 0; $i < count($layer); $i++){
			echo '<div class="dragObject" style="height: 16px;" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)"><span>' . $layer[$i]['Name_or_alias'] . '</span><input name="active_layers[]" type="hidden" value="'.$layer[$i]['Layer_ID'].'"></div>';
			echo '<div class="dropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
		}
	}

	function getLayerOptions() {
		global $admin_stellen;
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if ($this->formvars['layer_id'] > 0) {
			$layer = $this->user->rolle->getLayer($this->formvars['layer_id']);
		}
		else {
			$layer = $this->user->rolle->getRollenLayer(-$this->formvars['layer_id']);
		}
		$selectable_layer_groups = $mapDB->read_Groups(true, 'Gruppenname', "`selectable_for_shared_layers`");
		if ($layer[0]['connectiontype'] == 6) {
			$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
			$attributes = $mapDB->getDataAttributes($layerdb, $this->formvars['layer_id'],  array('if_empty_use_query' => true));
			$layer_labelitems = new MyObject($this, 'layer_labelitems');
			$labelitems = $layer_labelitems->find_where('layer_id = ' . ($layer[0]['original_layer_id'] ?: $layer[0]['Layer_ID']), 'order');
			$query_attributes = $mapDB->read_layer_attributes(($layer[0]['original_layer_id'] ?: $layer[0]['Layer_ID']), $layerdb, NULL, false, false, false);
			$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
		}
		$disabled_classes = $mapDB->read_disabled_classes();
		$layer[0]['Class'] = $mapDB->read_Classes($this->formvars['layer_id'], $disabled_classes, false, $layer[0]['classification']);
		echo '		
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0);" onclick="closeLayerOptions(' . $this->formvars['layer_id'] . ')" title="Schlie&szlig;en"><img style="border:none" src="' . GRAPHICSPATH . 'exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding: 0 3px 8px 0">
				<tr>
					<td class="layerOptionsHeader">
						<span class="fett">' . ucfirst($this->layerOptions) . '</span>
					</td>
				</tr>
				<tr>
					<td>
						<ul>';
						if ($this->formvars['layer_id'] < 0) { ?>
							<input type="hidden" name="selected_rollenlayer_id" value="<? echo (-$this->formvars['layer_id']); ?>">
							<li>
								<div style="width: 46px; float: left; padding-top: 3px;"><span><? echo $this->strName; ?>:</span></div>
								<div style="float: left;">
									<input
										type="text"
										name="layer_options_name"
										value="<? echo $layer[0]['Name']; ?>"
										style="width: 199px;"
										onchange="$('#shared_layer_table_name').val(sonderzeichen_umwandeln(this.value.toLowerCase()));"
									>
								</div>
								<div style="clear: both">
							</li><?
							if ($layer[0]['Typ'] == 'import' AND $this->user->share_rollenlayer_allowed AND count($selectable_layer_groups) > 0) { ?>
								<li id="shareRollenlayerLink">
									<a
										href="javascript:void(0);"
										onclick="toggle(document.getElementById('shareRollenlayerDiv'))"
										title="<? echo $this->strShareRollenLayerLong; ?>"
									><? echo  $this->strShareRollenlayer; ?></a>
								</li>
								<div id="shareRollenlayerDiv" style="display: none">
									<div style="margin-bottom: 3px;"><? echo $this->strLayerGroup; ?>:</div><?
									echo implode(
										'<br>',
										array_map(
											function($group) {
												return '<input
													id="shared_layer_group_id"
													type="radio"
													name="shared_layer_group_id"
													value="' . $group['id'] . '"
													style="vertical-align: text-top"
													onchange="$(\'#shared_layer_schema_name\').val(sonderzeichen_umwandeln($(this).next().text().toLowerCase()));"
												><span>' . $group['Gruppenname'] . '</span>';
											},
											array_filter(
												$selectable_layer_groups,
												function($group) {
													return array_key_exists('Gruppenname', $group);
												}
											)
										)
									); 
									if (in_array($this->Stelle->id, $admin_stellen)) {
										?><br>
										<? echo $this->strSchema; ?>.<? echo $this->strTableName; ?>:<br>
										<div style="float: left;padding-top: 3px;">
											<input id="shared_layer_schema_name" type="text" name="shared_layer_schema_name" value="shared" style="width: 80px;">.
										</div>
										<div style="float: left;padding-top: 3px;">
											<input id="shared_layer_table_name" type="text" name="shared_layer_table_name" value="<? echo sonderzeichen_umwandeln(strtolower($layer[0]['Name'])); ?>" style="width: 181px;">
										</div>
									<? } ?>
									<div style="clear: both">
										<select name="layer_options_privileg" style="margin-top: 3px; margin-bottom: 5px">
											<option value="readable"><? echo $this->strReadable; ?></option>
											<option value="editable_only_in_this_stelle" selected><? echo $this->strEditableOnlyInThisStelle; ?></option>
											<option value="editable"><? echo $this->strEditableInAllStellen; ?></option>
										</select><br>
										<input type="button" onclick="shareRollenlayer(<? echo (-$this->formvars['layer_id']); ?>)" value="<? echo $this->strShareRollenlayer; ?>">
										<input type="button" onclick="toggle(document.getElementById('shareRollenlayerDiv'))" value="<? echo $this->strCancel; ?>">
									</div>
								</div><?
							}
							if ($layer[0]['Typ'] == 'search') {
								echo '
								<li>
									<span>' . $this->strAutoDelete.': </span>
									<input type="checkbox" value="1" name="layer_options_autodelete" ' . ($layer[0]['autodelete'] == '1' ? 'checked' : '').' style="vertical-align: bottom;">
								</li>';
							}
						}
						else {
							if ($this->Stelle->isMenueAllowed('Layer_Anzeigen') OR $layer[0]['shared_from'] == $this->user->id) { echo '
								<li>
									<span><a href="javascript:void(0);" onclick="toggle(document.getElementById(\'layer_properties\'))">' . ucfirst($this->properties) . '</a></span>
								</li>
								<div id="layer_properties" style="display: none">
									<ul>' . (!(!$this->Stelle->isMenueAllowed('Layer_Anzeigen') AND $layer[0]['shared_from'] == $this->user->id) ? '
										<li>
											<a href="index.php?go=Layereditor&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->layerDefinition . '</a>
										</li>' : '') . '
										<li>
											<a href="index.php?go=Attributeditor&selected_layer_id='.$this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->attributeditor.'</a>
										</li>
										<li>
											<a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strPrivileges . '</a>
										</li>
										<li>
											<a href="index.php?go=Style_Label_Editor&selected_layer_id='.$this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strStyles . '</a>
										</li>
									</ul>
								</div>';
							}
						}
						if ($layer[0]['metalink'] != '') {
							$href = $layer[0]['metalink'];
							$target = '';
							if (substr($layer[0]['metalink'], 0, 10) != 'javascript') {
								if(strpos(substr($layer[0]['metalink'], -5), '.') !== false) {
									$meta_parts = explode('#', $href);
									$meta_parts[0] .= (strpos($meta_parts[0], '?') === false ? '?' : '&') . 'time=' . time();
									$href = implode('#', $meta_parts);
								}
								$target = '_blank';
							}
							echo '<li><a href="' . $href . '" target="' . $target . '">' . $this->strMetadata . '</a></li>';
						}
						if($layer[0]['connectiontype']==6 OR($layer[0]['Datentyp']==MS_LAYER_RASTER AND $layer[0]['connectiontype']!=7)){
							echo '<li><a href="javascript:void(0);" onclick="zoomToMaxLayerExtent(' . $this->formvars['layer_id'] . ')">' . ucfirst($this->FullLayerExtent) . '</a></li>';
						}
						if (in_array($layer[0]['connectiontype'], [MS_POSTGIS, MS_WFS]) AND $layer[0]['queryable']) {
							if ($layer[0]['Name'] == LAYERNAME_FLURSTUECKE ) {
								echo '<li><a href="index.php?go=Flurstueck_Auswaehlen&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strSearch . '</a></li>';
							}
							else {
								echo '<li><a href="index.php?go=Layer-Suche&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strSearch . '</a></li>';
							}
						}
						if ($this->formvars['layer_id'] > 0 AND $layer[0]['connectiontype'] == MS_POSTGIS) {
							echo '<li><a href="index.php?go=zoomto_selected_datasets&chosen_layer_id=' . $this->formvars['layer_id'] . '">' . $this->strAddToOwnQueries . '</a></li>';
						}
						if ($layer[0]['queryable']) {
							if ($layer[0]['privileg'] > 0 AND $layer[0]['privilegfk'] !== '0') {
								echo '<li><a title="'. $this->strNewDatasetTitle1 . ' ' . $layer[0]['Name'] . ' ' . $this->strCreate . ' " href="index.php?go=neuer_Layer_Datensatz&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->newDataset . '</a></li>';
								if ($this->user->layer_data_import_allowed) {
									echo '<li><a href="index.php?go=Daten_Import&chosen_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strDataImport . '</a></li>';
								}
							}
							if (in_array($layer[0]['connectiontype'], [MS_POSTGIS, MS_WFS]) AND $layer[0]['export_privileg']){
								echo '<li><a href="index.php?go=Daten_Export&chosen_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strDataExport . '</a></li>';
							}
						}
						echo '<li><a href="javascript:void(0);" onclick="compare_view_for_layer(' . $this->formvars['layer_id'] . ');closeLayerOptions(' . $this->formvars['layer_id'] . ');">' . $this->strCompareView . '</a></li>';
						if ($layer[0]['Class'][0]['Name'] != '') {
							if ($layer[0]['showclasses'] != '') {
								echo '<li><a href="javascript:void(0);" onclick="get_layer_legend(\'' . $this->formvars['layer_id'] . '\', \'\', true);closeLayerOptions(' . $this->formvars['layer_id'] . ')">';
								if($layer[0]['showclasses'])echo $this->HideClasses;
								else echo $this->DisplayClasses;
								echo '</a></li>';
							}
							for($c = 0; $c < count($layer[0]['Class']); $c++){
								$class_ids[] = $layer[0]['Class'][$c]['Class_ID'];
							}
							if ($layer[0]['Class'][0]['Status'] == '1' || $layer[0]['Class'][1]['Status'] == '1') {
								$function = "deactivateAllClasses('" . implode(",", $class_ids) . "')";
								$link_text = ucfirst($this->deactivateAllClasses);
							}
							if ($layer[0]['Class'][0]['Status'] == '0' || $layer[0]['Class'][1]['Status'] == '0') {
								$function = "activateAllClasses('" . implode(",", $class_ids) . "')";
								$link_text = ucfirst($this->activateAllClasses);
							}
							echo '<li><a href="javascript:void();" onclick="' . $function . '">' . $link_text . '</a></li>';
						}
						echo '</ul>
						<table class="ul_table">';
						if ($layer[0]['connectiontype'] == 6 AND $this->formvars['layer_id'] < 0) {
							echo '<tr>
												<td>
													<span>' . $this->strAutoClassify . ':</span>
												</td>
												<td>
													<select style="width: 110px" name="klass_' . $this->formvars['layer_id'] . '">
														<option value=""> - </option>';
														for ($i = 0; $i < count($attributes)-2; $i++){
															$index = $query_attributes['indizes'][$attributes[$i]['name']];
															if ($attributes['the_geom'] != $attributes[$i]['name']) {		# Attribut ist nicht das Geometrieattribut
																echo '<option value="'.$attributes[$i]['name'].'">'.($query_attributes['alias'][$index] ?: $attributes[$i]['name']).'</option>';
															}
														}
									echo 	 '</select>
												</td>
											</tr>';
						}
						echo $this->get_layer_params_form(NULL, $this->formvars['layer_id']);
						if ($layer[0]['connectiontype'] == 6) {
							if ($this->formvars['layer_id'] < 0 OR $labelitems != NULL) {
								# für Rollenlayer oder normale Layer mit labelitems
								echo '<tr>
												<td>
													<span>' . $this->label . ':</span>
												</td>
												<td>
													<select style="width: 110px" name="layer_options_labelitem">
														<option value=""> - '.$this->noLabel.' - </option>';
														if ($labelitems != NULL) {
															for ($i = 0; $i < count($labelitems); $i++){
																echo '<option value="' . $labelitems[$i]->get('name') .'" '.($layer[0]['labelitem'] == $labelitems[$i]->get('name') ? 'selected' : '').'>' . ($labelitems[$i]->get('alias') ?: $labelitems[$i]->get('name')) . '</option>';
															}
														}
														else {
															for ($i = 0; $i < count($attributes)-2; $i++) {
																if ($attributes['the_geom'] != $attributes[$i]['name']) {		# Attribut ist nicht das Geometrieattribut
																	echo '<option value="'.$attributes[$i]['name'].'" '.($layer[0]['labelitem'] == $attributes[$i]['name'] ? 'selected' : '').'>' . $attributes[$i]['name'] . '</option>';
																}
															}
														}
									echo 	 '</select>
												</td>
											</tr>';
							}
						}
						if ($this->formvars['layer_id'] < 0 AND $layer[0]['Datentyp'] != MS_LAYER_RASTER) {
							$this->result_colors = $this->database->read_colors();
							for ($i = 0; $i < count($this->result_colors); $i++) {
								$color_rgb = $this->result_colors[$i]['red'].' '.$this->result_colors[$i]['green'].' '.$this->result_colors[$i]['blue'];
								if ($layer[0]['Class'][0]['Style'][0]['color'] == $color_rgb) {
									$bgcolor = $this->result_colors[$i]['red'].', '.$this->result_colors[$i]['green'].', '.$this->result_colors[$i]['blue'];
								}
							}
							echo '
								<tr>
									<td>
										<span>'.$this->strColor.': </span>
									</td>
									<td>
										<select name="layer_options_color" style="background-color: rgb(' . $bgcolor.')" onchange="this.setAttribute(\'style\', this.options[this.selectedIndex].getAttribute(\'style\'));">';
											for($i = 0; $i < count($this->result_colors); $i++){
												$color_rgb = $this->result_colors[$i]['red'].' '.$this->result_colors[$i]['green'].' '.$this->result_colors[$i]['blue'];
												echo '<option ';
												if($layer[0]['Class'][0]['Style'][0]['color'] == $color_rgb){
													echo ' selected';
												}
												echo ' style="background-color: rgb('.$this->result_colors[$i]['red'].', '.$this->result_colors[$i]['green'].', '.$this->result_colors[$i]['blue'].')"';
												echo ' value="'.$color_rgb.'">';
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
												echo "</option>\n";
											}
											echo '
										</select>
									</td>
								</tr>';
								
							echo '
								<tr>
									<td>
										<span>' . $this->strHatching.': </span>
									</td>
									<td>
										<input type="checkbox" value="hatch" name="layer_options_hatching" ' . ($layer[0]['Class'][0]['Style'][0]['symbolname'] == 'hatch' ? 'checked' : '').'>
									</td>
								</tr>';
								
							echo '
								<tr>
									<td>
										<span>' . $this->strBuffer.': </span>
									</td>
									<td>
										<input type="input" style="width: 60px" value="' . $layer[0]['buffer'] . '" name="layer_options_buffer"> m
									</td>
								</tr>';
						}
						echo '<tr>
										<td>
											<span>' . $this->transparency . ':</span>
										</td>
										<td>
											<input name="layer_options_transparency" type="number" min="0" max="100" onkeyup="enforceMinMax(this)" onchange="transparency_slider.value=parseInt(layer_options_transparency.value);" style="width: 53px" value="'.$layer[0]['transparency'].'"><input type="range" id="transparency_slider" name="transparency_slider" style="height: 6px; width: 120px" value="'.$layer[0]['transparency'].'" onchange="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()" oninput="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()">
										</td>
									</tr>';
						if (ROLLENFILTER AND $this->user->rolle->showrollenfilter) {
							echo '	
									<tr>
										<td>
										<a href="javascript:void(0);" onclick="$(\'#rollenfilter, #rollenfilterquestionicon\').toggle()">Filter</a>
										<a href="javascript:void(0);" onclick="message(\'\
											Sie können im Textfeld einen SQL-Ausdruck eintragen, der sich als Filter auf die Kartendarstellung und Sachdatenanzeige des Layers auswirkt.<br>\
											In diesem Thema stehen dafür folgende Attribute zur Verfügung:<br>\
											<ul>';
											for ($i = 0; $i < count_or_0($attributes)-2; $i++) {
												if (
													(
														$this->formvars['layer_id'] < 0 OR
														$privileges[$attributes[$i]['name']] != ''
													) AND
													$attributes['the_geom'] != $attributes[$i]['name']
												) {
													echo '<li>' . $attributes[$i]['name'] . '</li>';
												}
											}
											echo	'</ul>\
											Mehrere Filter werden mit AND oder OR verknüpft.<br>\
											Ist ein Filter gesetzt wird in der Legende neben dem Themanamen ein Filtersymbol angezeigt.<br>\
											Der Filter wird gelöscht indem das Textfeld geleert wird.<p>\
											Beispiele:<br>\
											<ul>\
												<li>id > 10 AND status = 1</li>\
												<li>type = \\\'Brunnen\\\' OR type = \\\'Quelle\\\'</li>\
												<li>status IN (1, 2)</li>\
											</ul>\
											\')">
											<i
												id="rollenfilterquestionicon"
												title="Hilfe zum Filter anzeigen"
												class="fa fa-question-circle button layerOptionsIcon"
												style="
													float: right;
													' . ($layer[0]['rollenfilter'] == '' ? 'display: none' : '') . '
												"
											></i>
										</a><br>
										<textarea
											id="rollenfilter"
											style="
												width: 98%;
												' . ($layer[0]['rollenfilter'] == '' ? 'display: none' : '') . '
											"
											name="layer_options_rollenfilter"
										>' . $layer[0]['rollenfilter'] . '</textarea>
									</td>
								</tr>';
						}
echo '			</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="0">
							<tr>';
								if ($this->formvars['layer_id'] > 0) { echo '
									<td>
										<input type="button" onmouseup="resetLayerOptions(' . $this->formvars['layer_id'] . ')" value="' . $this->strReset . '">
									</td>';
								} echo '
								<td>
									<input type="button" onmouseup="saveLayerOptions(' . $this->formvars['layer_id'] . ')" value="' . $this->strSave . '">
								</td>';
								if ($this->formvars['layer_id'] < 0) { echo '
									<td>
										<input type="button" onmouseup="Bestaetigung(\'index.php?go=delete_rollenlayer&id=' . (-$this->formvars['layer_id']) . '\', \'Importierten Layer wirklich löschen?\')" value="' . ucfirst($this->strDelete) . '">
									</td>';
								}
								if ($layer[0]['shared_from'] == $this->user->id) { echo '
									<td>
										<input type="hidden" name="selected_layer_id" value="' . $this->formvars['layer_id'] . '">
										<input type="button" onmouseup="deleteSharedLayer(' . $this->formvars['layer_id'] . ')" value="' . $this->strDelete . '">
									</td>';
								} echo '
							</tr>
						</table>
					</td>
				</tr>
			</table>
		█
		legend_top = document.getElementById(\'legenddiv\').getBoundingClientRect().top;
		legend_bottom = document.getElementById(\'legenddiv\').getBoundingClientRect().bottom;
		posy = document.getElementById(\'options_'.$this->formvars['layer_id'].'\').getBoundingClientRect().top;
		options_height = document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').getBoundingClientRect().height;
		if(posy > legend_bottom - options_height)posy = legend_bottom - options_height;
		document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').style.top = document.getElementById(\'map\').offsetTop + posy - legend_top;
		';
	}


	function getGroupOptions() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		echo '
		<div class="groupOptions" id="group_options_content_' . $this->formvars['group_id'].'">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:closeGroupOptions(' . $this->formvars['group_id'] . ');" title="Schlie&szlig;en"><img style="border:none" src="' . GRAPHICSPATH . 'exit2.png"></img></a></div>
			<table width="100%" cellspacing="0" cellpadding="0" style="padding-bottom: 8px">
				<tr>
					<td class="groupOptionsHeader">
						<span class="fett">Gruppenoptionen</span>
					</td>
				</tr>
				<tr>
					<td>
						<ul>
							<li><a href="javascript:selectgroupthema(document.GUI.layers_of_group_' . $this->formvars['group_id'] . ', 0)" style="margin-left: -15px">alle Layer ein/ausschalten</a></li>
						</ul>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
								</td>
								<td>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		█
		legend_top = document.getElementById(\'legenddiv\').getBoundingClientRect().top;
		legend_bottom = document.getElementById(\'legenddiv\').getBoundingClientRect().bottom;
		posy = document.getElementById(\'group_options_' . $this->formvars['group_id'] . '\').getBoundingClientRect().top;
		if(posy > legend_bottom - 150) posy = legend_bottom - 150;
		document.getElementById(\'group_options_content_' . $this->formvars['group_id'] . '\').style.top = posy - (13 + legend_top);
		$(\'#group_options_' . $this->formvars['group_id'] . '\').show()
		';
	}

	function saveGeomFromLayer() {
		$this->user->rolle->saveGeomFromLayer($this->formvars['selected_layer_id'], $this->formvars['geom_from_layer']);
	}

	function saveLegendOptions(){
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerset = $mapDB->read_Layer(0, $this->Stelle->useLayerAliases, NULL);     # class_load_level: 0 = keine Klassen laden
		$this->user->rolle->saveLegendOptions($layerset, $this->formvars);
		$this->resizeMap2Window();
		$this->user->rolle->readSettings();
		$this->neuLaden();
		// $this->user->rolle->newtime = $this->user->rolle->last_time_id;
		// $this->drawMap();
		// $this->saveMap('');
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	function resetLegendOptions(){
		$this->user->rolle->removeDrawingOrders();
		$this->neuLaden();
		// $this->user->rolle->newtime = $this->user->rolle->last_time_id;
		// $this->drawMap();
		// $this->saveMap('');
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	function saveLayerOptions() {
		$this->user->rolle->setTransparency($this->formvars);
		$this->user->rolle->setLabelitem($this->formvars);
		$this->user->rolle->setRollenFilter($this->formvars);
		$this->setLayerParams('options_');
		if ($this->formvars['layer_options_open'] < 0) { # Rollenlayer
			$this->user->rolle->setRollenLayerName($this->formvars);
			$this->user->rolle->setRollenLayerAutoDelete($this->formvars);
			$this->user->rolle->setBuffer($this->formvars);
			$dbmap = new db_mapObj($this->Stelle->id, $this->user->id);
			# automatische Klassifizierung
			if ($auto_class_attribute = $this->formvars['klass_' . $this->formvars['layer_options_open']]) {
				$this->formvars['selected_layer_id'] = $this->formvars['layer_options_open'];
				$this->formvars['no_output'] = true;		# damit der Aufruf von output() verhindert wird
				$this->GenerischeSuche_Suchen();
				$result= $this->qlayerset[0]['shape'];
				# alte Klassen löschen
				$old_classes = $dbmap->read_Classes($this->formvars['selected_layer_id']);
				for($i = 0; $i < count($old_classes); $i++){
					$dbmap->delete_Class($old_classes[$i]['Class_ID']);
				}
				for ($i = 0; $i < count($result); $i++) {
					foreach ($result[$i] As $key => $value) {
						if ($auto_class_attribute === $key) {
							$classes[$value] = $value;
						}
					}
				}
				$dbmap->createAutoClasses(array_unique($classes), $auto_class_attribute, -$this->formvars['selected_layer_id'], $this->qlayerset[0]['Datentyp'], $this->database);
			}
			$classes = $dbmap->read_Classes($this->formvars['layer_options_open']);
			if (!empty($classes)) {
				if (!empty($classes[0]['Style'])) {
					$this->user->rolle->setStyle($classes[0]['Style'][0]['Style_ID'], $this->formvars);
				}
				foreach ($classes as $class) {	# bei Bedarf Label anlegen
					if ($class['Label'] == NULL){
						$empty_label = new stdClass();
						$empty_label->font = 'arial';
						$empty_label->size = '8';
						$empty_label->minsize = '6';
						$empty_label->maxsize = '10';
						$empty_label->position = '6';
						$new_label_id = $dbmap->new_Label($empty_label);
						$dbmap->addLabel2Class($class['Class_ID'], $new_label_id, 0);
					}
				}
			}
		}
	}

	function resetLayerOptions(){
		$this->user->rolle->removeTransparency($this->formvars);
		$this->user->rolle->removeLabelitem($this->formvars);
		$this->neuLaden();
		// $this->user->rolle->newtime = $this->user->rolle->last_time_id;
		// $this->drawMap();
		// $this->saveMap('');
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	function switch_gle_view(){
		$this->user->rolle->switch_gle_view($this->formvars['chosen_layer_id'], $this->formvars['mode']);
		if ($this->formvars['reload'] == 1) {
			$this->last_query = $this->user->rolle->get_last_query();
			$this->formvars['go'] = $this->last_query['go'];
			if ($this->formvars['go'] == 'Layer-Suche_Suchen') {
				$this->GenerischeSuche_Suchen();
			}
			else {
				$this->queryMap();
			}
		}
	}

	function setHistTimestamp(){
		$this->user->rolle->setHistTimestamp($this->formvars['timestamp'], $this->formvars['go_next']);
		$this->user->rolle->readSettings();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
	}

	function setLanguage(){
		$this->user->rolle->setLanguage($this->formvars['language']);
		$this->user->rolle->readSettings();
		$this->loadMultiLingualText(rolle::$language);
		$this->loadMap('DataBase');
		#$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		#$this->drawMap();
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	/**
		@param $prefix String dient zur Unterscheidung zwischen den Layer-Parametern im Header und denen in den Optionen
	*/
	function setLayerParams($prefix = '') {
		$layer_params = array();
		if ($prefix == '') {
			# leeren, damit alte Layerparameter entfernt werden
			rolle::$layer_params = array();
		}
		foreach ($this->formvars AS $key => $value) {
			$param_key = str_replace($prefix . 'layer_parameter_', '', $key);
			if ($param_key != $key) {
				rolle::$layer_params[$param_key] = $value;
			}
		}
		foreach (rolle::$layer_params as $param_key => $value) {
			$layer_params[] = '"' . $param_key . '":"' . $value . '"';
		}
		if (!empty($layer_params)) {
			$this->user->rolle->set_layer_params(implode(',', $layer_params));
		}
	}

	function getNBH(){
		if(defined('NBH_PATH')){
			$nbh = file_get_contents(NBH_PATH);
			$nbh = str_replace("NB	", "nbh[", $nbh);
			$nbh = str_replace("	", "]=", $nbh);
			$nbh = str_replace(" ", "", $nbh);
			$nbh = str_replace(chr(13), ";".chr(13), $nbh);
			echo $nbh;
		}
	}

	/**
	* function returns the svg and wkb geometry of an uploaded shape file
	* in json format and an error message and success false when fail
	*/
	// function get_geom_from_shape_file() {
	// 	$success = false;
	// 	$upload_file = $_FILES['file'];
	// 	$geom = '';
	// 	$msg = 'filename: ' . $upload_file['name'] . ' tmp_name: ' . $upload_file['tmp_name'];
	// 	if (strtolower(pathinfo($upload_file['name'], PATHINFO_EXTENSION)) == 'zip') {
	// 		$zip_file = IMAGEPATH . pathinfo($upload_file['tmp_name'], PATHINFO_FILENAME) . '_' . $upload_file['name'];
	// 		$msg .= 'zipfile: ' . $zip_file;

	// 		$importer = new data_import_export();

	// 		if (move_uploaded_file($upload_file['tmp_name'], $zip_file)) {
	// 			# ToDo: Shape in einem untergeordneten Ordner auspacken um Problemm mit gleichen Namen von hochgeladenen Dateien unterschiedlicher Anwender zu vermeiden.
	// 			$shape_files = unzip($zip_file, false, false, true);

	// 			$msg .= 'file unziped';
	// 			# get shape file name
	// 			$first_file = explode('.', $shape_files[0]);
	// 			$shape_file_name = $first_file[0];

	// 			# get EPSG-Code aus prj-Datei
	// 			$file_epsg = $importer->get_shp_epsg(IMAGEPATH . $shape_file_name, $this->pgdatabase);
	// 			if ($file_epsg == '') {
	// 				$file_epsg = '25833';
	// 				# ToDo EPSG-Code konnte nicht aus prj-Datei ermittelt werden, Dateiname merken und EPSG-Code nachfragen
	// 			}
	// 			$msg .= 'EPSG: ' . $file_epsg;

	// 			# get encoding of dbf file
	// 			$encoding = $importer->getEncoding(IMAGEPATH . $shape_file_name . '.dbf');

	// 			# load shapes to custom schema
	// 			$import_result = $importer->load_shp_into_pgsql($this->pgdatabase, IMAGEPATH, $shape_file_name, $file_epsg, CUSTOM_SHAPE_SCHEMA, 'b' . strtolower(sonderzeichen_umwandeln(substr($shape_file_name, 0, 15))) . rand(1, 1000000), $encoding);

	// 			# return name of import table
	// 			$table_name = $import_result[0]['tablename'];

	// 			include_once (CLASSPATH.'multigeomeditor.php');

	// 			$polygoneditor = new polygoneditor($this->pgdatabase, $file_epsg, $this->user->rolle->epsg_code);
	// 			$geom = $polygoneditor->getpolygon(NULL, $table_name, 'the_geom', NULL, CUSTOM_SHAPE_SCHEMA);
	// 			if ($geom['wktgeom'] != '') {
	// 				# use geom on client side to set
	// 				# $this->formvars['newpathwkt'] = $geom['wktgeom'];
	// 				# $this->formvars['pathwkt'] = $geom['wktgeom'];
	// 				# $this->formvars['newpath'] = $geom['svggeom'];
	// 				# $this->formvars['firstpoly'] = 'true';
	// 				# $this->formvars['zoom'] == 'true')

	// 				# drop custom shape table
	// 				$sql = "
	// 					 DROP TABLE " . CUSTOM_SHAPE_SCHEMA . "." . $table_name . "
	// 				";
	// 				$this->pgdatabase->execSQL($sql, 4, 0, true);

	// 				# delete uploaded shape files
	// 				foreach($shape_files AS $shape_file) {
	// 					 unlink(IMAGEPATH . $shape_file);
	// 				}
	// 				unlink(IMAGEPATH . pathinfo($shape_file, PATHINFO_FILENAME) . '.sql');
	// 				unlink($zip_file);

	// 				$msg = 'Geometrie erfolgreich geladen.';
	// 				$success = true;
	// 			}
	// 			else {
	// 				$msg = 'Fehler beim Lesen der Geometrie!';
	// 			}
	// 		}
	// 		else {
	// 			$msg = 'Fehler beim kopieren der Datei auf den Server!';
	// 		}
	// 	}
	// 	else {
	// 		$msg .= 'Datei ist keine Zip-Datei!';
	// 	}

	// 	$response = array(
	// 		"success" => $success,
	// 		"result" => $msg,
	// 		"svggeom" => $geom['svggeom'],
	// 		"wktgeom" => $geom['wktgeom']
	// 	);
	// 	return utf8_decode(json_encode($response));
	// }

	function get_layer_legend() {
		if ($this->formvars['layer_params'] != '') {
			rolle::$layer_params = array_merge(rolle::$layer_params, $this->formvars['layer_params']);
		}
		# Ein- oder Ausblenden der Klassen
		$this->user->rolle->setClassStatus($this->formvars);
		$this->loadMap('DataBase');
		echo $this->create_layername_legend($this->layerset['list'][0]);
		echo $this->create_class_legend($this->layerset['list'][0]);
	}

	function get_group_legend() {
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    $this->loadMap('DataBase');
		for($i = 0; $i < count_or_0($this->layers_replace_scale ?: []); $i++){
			$this->layers_replace_scale[$i]->set('data', str_replace('$SCALE', $this->map_scaledenom, $this->layers_replace_scale[$i]->data));
		}
    echo $this->create_group_legend($this->formvars['group'], $this->formvars['status']);
  }

  function close_group_legend() {
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
  }

  function create_dynamic_legend(){
		if($this->user->rolle->legendtype == 1){ # alphabetische Reihenfolge ohne Gruppen
			$this->sorted_layerset = $this->layerset['list'];
			unset($this->sorted_layerset['layer_ids']);		# layer_ids Array entfernen
			unset($this->sorted_layerset['anzLayer']);		# anzLayer entfernen
			usort($this->sorted_layerset, 'compare_layers');
			$legend = '<table cellspacing="0" cellpadding="0">';
			$layercount = count($this->sorted_layerset);
			for($j = 0; $j < $layercount; $j++){
				$layer = $this->sorted_layerset[$j];
				$legend .= $this->create_layer_legend($layer);
			}
			$legend .= '</table>';
			foreach($this->groupset as $group){
				$legend .= '<input type="hidden" name="radiolayers_'.$group['id'].'" value="'.$this->radiolayers[$group['id']].'">';
			}
		}
		else{		# Layer in Zeichenreihenfolge in Gruppen
			$legend = '';
			foreach($this->groupset as $group){
				if($group['obergruppe'] == ''){
					$legend .= $this->create_group_legend($group['id']);
				}
			}
		}
		$legend .= '<input type="hidden" name="layers" value="'.$this->layer_id_string.'">';
		$legend .= '<input type="hidden" name="zoom_layer_id" value="">';
		return $legend;
  }

	function create_group_legend($group_id, $status = NULL){
		$layerlist = $this->layerset['list'];
		if (@$this->groupset[$group_id]['untergruppen'] == NULL AND @$this->layerset['layers_of_group'][$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
    $groupname = $this->groupset[$group_id]['Gruppenname'];
	  $groupstatus = $this->groupset[$group_id]['status'];
    $legend =  '
	  <div id="groupdiv_'.$group_id.'" style="width:100%">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
				<tr>
					<td>
						<input id="group_' . $group_id . '" name="group_' . $group_id . '" type="hidden" value="' . $groupstatus . '">
						<a href="javascript:getlegend(\'' . $group_id . '\')">
							<img border="0" id="groupimg_' . $group_id . '" src="graphics/' . ($groupstatus == 1 ? 'minus' : 'plus') . '.gif">&nbsp;
						</a>
						<span class="legend_group' . (value_of($this->group_has_active_layers, $group_id) != '' ? '_active_layers' : '') . '">
							<!--a
								href="javascript:getGroupOptions(' . $group_id . ')"
								onmouseover="$(\'#test_' . $group_id . '\').show()"
								onmouseout="$(\'#test_' . $group_id . '\').hide()"
							>' . html_umlaute($groupname) . '
								<i id="test_' . $group_id . '" class="fa fa-bars" style="display: none;"></i>
							</a//-->' .
							html_umlaute($groupname) . '
							'.($groupname == 'eigene Abfragen' ? '<a href="javascript:deleteRollenlayer(\'search\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							'.(($groupname == 'Eigene Importe' OR $groupname == 'WMS-Importe') ? '<a href="javascript:deleteRollenlayer(\'import\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							<div style="position:static;" id="group_options_' . $group_id . '"></div>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<div id="layergroupdiv_'.$group_id.'" style="width:100%; padding-left: 4px;'.(($groupstatus != 1 AND value_of($this->group_has_active_layers, $group_id) != '') ? 'display: none' : '').'"><table cellspacing="0" cellpadding="0">';
		$layercount = count_or_0($this->layerset['layers_of_group'][$group_id] ?: []);
		if($groupstatus == 1 OR value_of($this->group_has_active_layers, $group_id) != ''){		# Gruppe aufgeklappt oder hat aktive Layer
			if(value_of($this->groupset[$group_id], 'untergruppen') != ''){
				for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
					$legend .= '<tr><td colspan="3"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td><img src="'.GRAPHICSPATH.'leer.gif" width="6" height="1" border="0"></td><td style="width: 100%">';
					$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u], $status);
					$legend .= '</td></tr></table></td></tr>';
				}
			}
			if ($layercount > 0) {		# Layer vorhanden
				if (value_of($this->layerset['layer_group_has_legendorder'], $group_id) != ''){			# Gruppe hat Legendenreihenfolge -> sortieren
					usort($this->layerset['layers_of_group'][$group_id], function($a, $b) use ($layerlist) {
						return $layerlist[$a]['legendorder'] - $layerlist[$b]['legendorder'];
					});
				}
				else {
					$this->layerset['layers_of_group'][$group_id] = array_reverse($this->layerset['layers_of_group'][$group_id]);		# umgedrehte Zeichenreihenfolge verwenden
				}
				if (!value_of($this->formvars, 'nurFremdeLayer')) {
					if (!is_array($this->layer_ids_of_group[$group_id])) {
						$this->layer_ids_of_group[$group_id] = [];
					}
					$legend .=  '<tr>
												<td align="center">
													<input name="layers_of_group_'.$group_id.'" type="hidden" value="'.implode(',', $this->layer_ids_of_group[$group_id]).'">';
					if($this->user->rolle->singlequery == 0) {
						$legend .=  '<a href="javascript:selectgroupquery(document.GUI.layers_of_group_'.$group_id.', '.$this->user->rolle->instant_reload.')"><img border="0" src="graphics/pfeil.gif" title="'.$this->strActivateAllQueries.'"></a>';
					}
					$legend .=		'</td>
												<td align="center">
													<a href="javascript:selectgroupthema(document.GUI.layers_of_group_'.$group_id.', '.$this->user->rolle->instant_reload.')"><img border="0" src="graphics/pfeil.gif" title="'.$this->strActivateAllLayers.'"></a>
												</td>
												<td>
													<span class="legend_layer">'.$this->all.'</span>
												</td>
											</tr>';
				}
				for ($j = 0; $j < $layercount; $j++) {
					$layer = $this->layerset['list'][$this->layerset['layers_of_group'][$group_id][$j]];
					$legend .= $this->create_layer_legend($layer);
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.value_of($this->radiolayers, $group_id).'">';
	  $legend .= '</div>';
    return $legend;
  }

	function create_layer_legend($layer){
		if (value_of($layer, 'requires') != '' )return;
		$visible = $this->check_layer_visibility($layer);
		# sichtbare Layer
		if ($visible) {
			$legend = '<tr><td valign="top">';

			$legend.='<div style="position:static; float:right" id="options_'.$layer['Layer_ID'].'"><div class="layerOptions" id="options_content_'.$layer['Layer_ID'].'"></div></div>';

			if (!empty($layer['shared_from'])) {
				$user_daten = $this->user->getUserDaten($layer['shared_from'], '', '');
				$legend .= ' <a
					href="javascript:void(0)"
					onclick="message([{ \'type\': \'info\', \'msg\' : \'' . $this->strLayerSharedFrom . ' ' . $user_daten[0]['Vorname'] . ' ' . $user_daten[0]['Name'] . (!empty($user_daten[0]['organisation']) ? ' (' . $user_daten[0]['organisation'] . ')' : '') . '\'}])"
					style="
						font-size: 10px;
						margin-left: -10px;
						vertical-align: top;
					"
				><i class="fa fa-share-alt" aria-hidden="true"></i></a>';
			}

			if ($layer['queryable'] == 1 AND $this->user->rolle->singlequery < 2 AND !value_of($this->formvars, 'nurFremdeLayer')) {
				$input_attr['id'] = 'qLayer' . $layer['Layer_ID'];
				$input_attr['name'] = 'qLayer' . $layer['Layer_ID'];
				$input_attr['title'] = ($layer['queryStatus'] == 1 ? $this->deactivatequery : $this->activatequery);
				$input_attr['value'] = 1;
				$input_attr['class'] = 'info-select-field';
				$input_attr['type'] = (($this->user->rolle->singlequery == 1 or $layer['selectiontype'] == 'radio') ? 'radio' : 'checkbox');
				$input_attr['style'] = ((
					$this->user->rolle->query or
					$this->user->rolle->touchquery or
					$this->user->rolle->queryradius or
					$this->user->rolle->polyquery
				) ? '' : 'display: none');
				$input_attr['onClick'] = ($input_attr['type'] == 'radio' ?
					"this.checked = this.checked2;" :
					"updateThema(
						event,
						document.getElementById('thema" . $layer['Layer_ID'] . "'),
						document.getElementById('qLayer" . $layer['Layer_ID'] . "'),
						'',
						''," .
						$this->user->rolle->instant_reload . "
					)"
				);
				$input_attr['onMouseUp'] = ($input_attr['type'] == 'radio' ?
					"this.checked = this.checked2;" :
					""
				);

				$input_attr['onMouseDown'] = ($input_attr['type'] == 'radio' ?
					"updateThema(
						event,
						document.getElementById('thema" . $layer['Layer_ID'] . "'),
						document.getElementById('qLayer" . $layer['Layer_ID'] . "')," .
						($layer['selectiontype'] == 'radio' ? "document.GUI.radiolayers_" . $layer['Gruppe'] : "''") . "," .
						($this->user->rolle->singlequery == 1 ? "document.GUI.layers" : "''") . "," .
						$this->user->rolle->instant_reload . "
					)" :
					""
				);

				# die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat,
				# damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
				$legend .= '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'" value="0">';
				$legend .= '<input';
				foreach ($input_attr AS $key => $value) {
					$legend .= ($value != '' ? ' ' . $key . '="' . $value . '"' : '');
				}
				$legend .= ($layer['queryStatus'] == 1 ? ' checked' : '');
				$legend .= '>';
			}
			else{
				#$legend .= '<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">';
			}
			$legend .=  '</td><td valign="top">';
			// die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat, damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
			$legend .=  '<input type="hidden" name="thema'.$layer['Layer_ID'].'" value="0">';

			$legend .=  '<input id="thema'.$layer['Layer_ID'].'" ';
			if(value_of($layer, 'selectiontype') == 'radio'){
				$legend .=  'type="radio" ';
				$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateQuery(event, document.getElementById(\'thema'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$layer['Gruppe'].', '.$this->user->rolle->instant_reload.')"';
				$this->radiolayers[$layer['Gruppe']] = value_of($this->radiolayers, $layer['Gruppe']).$layer['Layer_ID'].'|';
			}
			else{
				$legend .=  'type="checkbox" ';
				$legend .=  ' onClick="updateQuery(event, document.getElementById(\'thema'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', '.$this->user->rolle->instant_reload.')"';
			}
			$legend .=  ' name="thema'.$layer['Layer_ID'].'" value="1" ';
			if($layer['aktivStatus'] == 1){
				$legend .=  'checked title="'.$this->deactivatelayer.'"';
			}
			else{
				$legend .=  ' title="'.$this->activatelayer.'"';
			}
			$legend .= ' ></td><td valign="middle" id="legend_layer_' . $layer['Layer_ID'] . '">';

			$legend .= $this->create_layername_legend($layer);
			$legend .= $this->create_class_legend($layer);

			# requires-Layer
			if(value_of($layer, 'required') != ''){
				foreach($layer['required'] as $require_layer_id){
					$legend .= $this->create_class_legend($this->layerset['layer_ids'][$require_layer_id]);
				}
			}
			$legend .= '</td></tr>';
		}

		# unsichtbare Layer
		if (!$visible) {
			$legend .=  '
						<tr>
							<td valign="top">';
			if($layer['queryable'] == 1 AND $this->user->rolle->singlequery < 2){
				$style = ((
						$this->user->rolle->query or
						$this->user->rolle->touchquery or
						$this->user->rolle->queryradius or
						$this->user->rolle->polyquery
					) ? '' : 'style="display: none"');
				$legend .=  '<input ';
				if($layer['selectiontype'] == 'radio'){
					$legend .=  'type="radio" ';
				}
				else{
					$legend .=  'type="checkbox" ';
				}
				if($layer['queryStatus'] == 1){
					$legend .=  'checked="true"';
				}
				$legend .=' type="checkbox" name="pseudoqLayer'.$layer['Layer_ID'].'" disabled '.$style.'>';
			}
			$legend .=  '</td><td valign="top">';
			// die nicht sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen nur bei Radiolayern, damit sie beim Neuladen ausgeschaltet werden können, denn ein disabledtes input-Feld wird ja nicht übergeben
			$legend .=  '<input type="hidden" name="thema'.$layer['Layer_ID'].'" value="'.$layer['aktivStatus'].'">';
			$legend .=  '<input ';
			if($layer['selectiontype'] == 'radio'){
				$legend .=  'type="radio" ';
				$this->radiolayers[$layer['Gruppe']] .= $layer['Layer_ID'].'|';
			}
			else{
				$legend .=  'type="checkbox" ';
			}
			if($layer['aktivStatus'] == 1){
				$legend .=  'checked="true" ';
			}
			$legend .= 'id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" disabled="true"></td><td id="legend_layer_' . $layer['Layer_ID'] . '">';
			$legend .= '<a ';
			if ($this->user->rolle->showlayeroptions) {
				$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
			}
			$legend .= 'class="invisiblelayerlink boldhover" href="javascript:void(0)">';
			$legend .= '<span class="legend_layer_hidden" id="'.str_replace('-', '_', $layer['Name_or_alias']).'"';
			if($layer['minscale'] != -1 AND $layer['maxscale'] != -1){
				$legend .= 'title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
			}
			$legend .= ' >'.html_umlaute($layer['Name_or_alias']).'</span></a>';
			$legend.='<div style="position:static; float:right" id="options_'.$layer['Layer_ID'].'"> </div>';
			if($layer['status'] != ''){
				$legend .= '&nbsp;<img title="Thema nicht verfügbar: '.$layer['status'].'" src="'.GRAPHICSPATH.'warning.png">';
			}
			if($layer['queryable'] == 1){
				$legend .=  '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'"';
				if($layer['queryStatus'] != 0){
					$legend .=  ' value="1"';
				}
				$legend .=  '>';
			}
			$legend .=  '</td>
					</tr>';
		}
		return $legend;
	}

	function create_layername_legend($layer){
		$legend = '';
		$legend .= '<a';
		# Bei eingeschalteter Rollenoption Layeroptionen anzeigen wird das Optionsfeld mit einem Rechtsklick geöffnet.
		if ($this->user->rolle->showlayeroptions) {
			$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
		}
		if(value_of($layer, 'metalink') != ''){
			$legend .= ' class="metalink boldhover" href="javascript:void(0);">';
		}
		else {
			$legend .= ' class="visiblelayerlink boldhover" href="javascript:void(0)">';
		}
		$legend .= '<span id="'.str_replace('"', '', str_replace("'", '', str_replace('-', '_', $layer['Name_or_alias']))).'"';
		if(value_of($layer, 'minscale') != -1 AND value_of($layer, 'maxscale') > 0){
			$legend .= ' title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
		}
		$legend .= ' >' . html_umlaute($layer['alias_link']) . '</span>';
		$legend .= '</a>';
		# Bei eingeschalteten Layern und eingeschalteter Rollenoption ist ein Optionen-Button sichtbar
		if ($layer['aktivStatus'] == 1 and $this->user->rolle->showlayeroptions) {
			$legend .= '&nbsp';
			if ($layer['rollenfilter'] != '') {
				$legend .= '<a href="javascript:void(0)" onclick="getLayerOptions('.$layer['Layer_ID'] . ')">
					<i class="fa fa-filter button layerOptionsIcon" title="' . $layer['rollenfilter'] . '"></i>
				</a>';
			}
			$legend .= '<a href="javascript:void(0)" onclick="getLayerOptions(' . $layer['Layer_ID'] . ')">
				<i class="fa fa-bars pointer button layerOptionsIcon" title="'.$this->layerOptions.'"></i>
			</a>';
		}
		return $legend;
	}

	function create_class_legend($layer){
		global $legendicon_size;
		$legend = '';
		if($layer['aktivStatus'] == 1 AND isset($layer['Class'][0])){
			if(value_of($layer, 'requires') == '' AND $layer['Layer_ID'] > 0){
				$legend .= '<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
			}
			if ($layer['showclasses'] != 0) {
				if($layer['connectiontype'] == 7){      # WMS
					if($layer['Class'][0]['legendgraphic'] != ''){
						$imagename = $original_class_image = CUSTOM_PATH . 'graphics/' . $layer['Class'][0]['legendgraphic'];
						$legend .=  '<div id="lg'.$j.'_'.$l.'"><img src="'.$imagename.'"></div>';
					}
					else{
						$legend .=  $this->get_legend_graphics($layer);
					}
				}
				else {
					$legend .= '<table border="0" cellspacing="0" cellpadding="0">';
					$maplayer = $this->map->getLayer($layer['layer_index_mapobject']);
					if($layer['Class'][0]['legendorder'] != ''){
						usort($layer['Class'], 'compare_legendorder');
					}
					for($k = 0; $k < $maplayer->numclasses; $k++){
						$class = $maplayer->getClass($layer['Class'][$k]['index']);
						if ($class->name != '') {
							for($s = 0; $s < $class->numstyles; $s++){
								$style = $class->getStyle($s);
								if($maplayer->type > 0){
									if (MAPSERVERVERSION >= 800) {
										$symbol = $this->map->symbolset->getSymbol($style->symbol);
									}
									else {
										$symbol = $this->map->getSymbolObjectById($style->symbol);
									}
									if($symbol->type == 1005){ 	# 1005 == hatch
										$style->size = 2*$style->width;					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
										$style->maxsize = 2*$style->width;
									}
									elseif($style->symbolname == ''){
										$style->size = 2;					# size und width bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
										$style->maxsize = 2;
										$style->width = 2;
										$style->maxwidth = 2;
									}
									if ($maplayer->type == MS_LAYER_CHART) {
										$maplayer->type = MS_LAYER_POLYGON;		# Bug-Workaround Chart-Typ
									}
								}
								else{		# Punktlayer
									if($style->size > 14 OR $style->size == -1){
										$style->size = 14;
									}
									$style->maxsize = $style->size;		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									$style->minsize = $style->size;		# minsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									if($class->numstyles == 1){							# wenn es nur einen Style in der Klasse gibt, die Offsets auf 0 setzen, damit man was in der Legende erkennt
										$style->offsety = 0;
										$style->offsetx = 0;
									}
								}
							}
							$legend .= '<tr style="line-height: 15px"><td style="line-height: 14px">';
							if($s > 0 OR $class->status == MS_OFF){
								$width = $height = '';
								if($layer['Class'][$k]['legendimagewidth'] != '')$width = $layer['Class'][$k]['legendimagewidth'];
								if($layer['Class'][$k]['legendimageheight'] != '')$height = $layer['Class'][$k]['legendimageheight'];
								$padding = 1;
								###### eigenes Klassenbild ######
								if ($layer['Class'][$k]['legendgraphic'] != '') {
									$imagename = $original_class_image = CUSTOM_PATH . 'graphics/' . $layer['Class'][$k]['legendgraphic'];
									if ($width == '') {
										$size = getimagesize($imagename);
										$width = $size[0];
										$height = $size[1];
									}
								}
								###### generiertes Klassenbild ######
								else{
									if($width == '')$width = $legendicon_size['width'][$maplayer->type];
									if($height == '')$height = $legendicon_size['height'][$maplayer->type];
									if($layer['Class'][$k]['Style'][0]['colorrange'] != ''){		# generierte Color-Ramp
										$padding = 0;
										$newname = rand(0, 1000000).'.jpg';
										$this->colorramp(IMAGEPATH.$newname, $width, $height, $layer['Class'][$k]['Style'][0]['colorrange']);
										$imagename = TEMPPATH_REL.$newname;
									}
									else{																												# vom Mapserver generiertes Klassenbild
										if (MAPSERVERVERSION >= 800) {
											$img = $class->createLegendIcon($this->map, $class->layer, $width, $height);
										}
										else {
											$img = $class->createLegendIcon($width, $height);
										}
										if (MAPSERVERVERSION >= 800) {
											$image = $img->getBytes();
										}
										else {
											ob_start();
											$img->saveImage();
											$image = ob_get_clean();
										}
										$imagename = 'data:image/jpg;base64,'.base64_encode($image);
									}
									$original_class_image = $imagename;
								}
								####################################
								$classid = $layer['Class'][$k]['Class_ID'];
								if($this->mapDB->disabled_classes['status'][$classid] == '0'){
									if($height < $width)$height1 = 12;
									else $height1 = 18;
									$imagename = 'graphics/inactive'.$height1.'.jpg';
									$status = 0;
								}
								elseif($this->mapDB->disabled_classes['status'][$classid] == 2){
									$status = 2;
								}
								else{
									$status = 1;
								}
								# $original_class_image ist das eigentliche Klassenbild bei Status 1, $imagename das Bild, welches entsprechend des Status gerade gesetzt ist
								$legend .= '<input type="hidden" size="2" name="class'.$classid.'" value="'.$status.'"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.', ' . $maplayer->type . ')" onmouseout="mouseOutClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.', ' . $maplayer->type . ')" onclick="changeClassStatus('.$classid.',\''.$original_class_image.'\', '.$this->user->rolle->instant_reload.', '.$width.', '.$height.', ' . $maplayer->type . ')"><img style="vertical-align:middle;padding-bottom: '.$padding.'" border="0" name="imgclass'.$classid.'" width="'.$width.'" height="'.$height.'" src="'.$imagename.'"></a>';
							}
							$legend .= '&nbsp;<span class="px13">'.html_umlaute($class->name).'</span></td></tr>';
						}
					}
					$legend .= '</table>';
				}
			}
		}
		return $legend;
	}

	function get_legend_graphics($layer){
		$output = '';
		$url = $layer['connection'];
		$pos = strpos(strtolower($layer['connection']), 'styles=');
		if ($pos !== false) {
			$stylesection = substr($layer['connection'], $pos + 7);
			$pos = strpos($stylesection, '&');
			if ($pos !== false) {
				$stylesection = substr($stylesection, 0, $pos);
			}
		}
		$styles = explode(',', $stylesection);
		if (strpos(strtolower($url), 'format') === false) {
			$url .= '&format=image/png';
		}
		if (strpos(strtolower($url), 'version') === false) {
			$url .= '&version=' . $layer['wms_server_version'];
		}
		$pos = strpos(strtolower($layer['connection']), 'layers');
		if ($pos !== false) {
			$layersection = substr($layer['connection'], $pos + 7);
			$pos = strpos($layersection, '&');
			if ($pos !== false) {
				$layersection = substr($layersection, 0, $pos);
			}
		}
		else {
			$layersection = $layer['wms_name'];
		}
		$layers = explode(',', $layersection);
		for($l = 0; $l < count($layers); $l++){
			$layer_url = $url . '&layer=' . $layers[$l] . '&style=' . $styles[$l] . '&service=WMS&request=GetLegendGraphic';
			if ($layer['wms_auth_username'] != '') {
				$img = url_get_contents($layer_url, $layer['wms_auth_username'], $layer['wms_auth_password']);
				$layer_url = 'data:image/jpg;base64,'.base64_encode($img);
			}
			$output .=  '<div id="lg_'.$layer['Layer_ID'].'_'.$l.'"><img src="' . $layer_url . '" onerror="ImageLoadFailed(this)"></div>';
		}
		return $output;
	}

	function colorramp($path, $width, $height, $colorrange){
		$colors = explode(' ', $colorrange);
		$s[0] = $colors[0];	$s[1] = $colors[1];	$s[2] = $colors[2];
		$e[0] = $colors[3];	$e[1] = $colors[4];	$e[2] = $colors[5];
		$img = imagecreatetruecolor($width, $height);
		for($i = 0; $i < $height; $i++) {
			$r = $s[0] - ((($s[0]-$e[0])/$height)*$i);
			$g = $s[1] - ((($s[1]-$e[1])/$height)*$i);
			$b = $s[2] - ((($s[2]-$e[2])/$height)*$i);
			$color = imagecolorallocate($img,$r,$g,$b);
			imagefilledrectangle($img,0,$i,$width,$i+1,$color);
		}
		imagejpeg($img, $path, 70);
	}

	function changemenue_with_ajax($id, $status){
    $this->changemenue($id, $status);
  }

	function changemenue($id, $status) {
		if ($status == 'on') {
			if ($this->user->rolle->menu_auto_close == 1) {
				# alle Obermenüpunkte schliessen
				$sql = "
					UPDATE
						u_menue2rolle
					SET
						`status` = 0
					WHERE
						`user_id` = " . $this->user->id . "
						AND `stelle_id` = " . $this->Stelle->id . "
				";
				$this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql, 4);
				$ret = $this->database->execSQL($sql);
				if (!$ret['success']) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->database->mysqli->error, 4); return 0; }
			}
			$sql = "
				UPDATE
					u_menue2rolle
				SET
					`status` = 1
				WHERE
					`user_id` = " . $this->user->id . "
					AND `stelle_id` = " . $this->Stelle->id . "
					AND `menue_id` = " . $id . "
			";
			$this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql);
			if (!$ret['success']) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->database->mysqli->error, 4); return 0; }
		}
		elseif ($status == 'off') {
			$sql = "
				UPDATE
					u_menue2rolle
				SET
					`status` = 0
				WHERE
					`user_id` = " . $this->user->id . "
					AND `stelle_id` = " . $this->Stelle->id . "
					" . ($id != '' ? "AND `menue_id` = " . $id : "") . "
			";
			$this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql, 4);
			$ret = $this->database->execSQL($sql);
			if (!$ret['success']) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->database->mysqli->error, 4); return 0; }
		}
	}

	function list_subgroups($groupid){
		$subgroups = '';
		if($groupid != ''){
			$group = $this->groupset[$groupid];
			if(value_of($group, 'untergruppen') != ''){
				foreach($group['untergruppen'] as $untergruppe){
					$subgroups .= ', '.$this->list_subgroups($untergruppe);
				}
				return $groupid.$subgroups;
			}
			else return $groupid;
		}
	}

	function addRedlining(){
		$polygons = explode('||', $this->formvars['free_polygons']);
		for($i = 0; $i < count($polygons)-1; $i++){
			$parts = explode('|', $polygons[$i]);
			if (substr_count($parts[0], ',') > 2) {
				$wkt = "POLYGON((" . $parts[0]."))";
				$style = $parts[1];
				$this->addFeatureLayer('free_polygon'.$i, MS_LAYER_POLYGON, array($wkt), NULL, $style, $this->map_factor);
			}
			else {
				$this->add_message('warning', 'Die Fläche muss aus mindestens drei Eckpunkten bestehen.');
			}
		}
		$texts = explode('||', $this->formvars['free_texts']);
		for($i = 0; $i < count($texts)-1; $i++){
			$parts = explode('|', $texts[$i]);
			$wkt = "POINT(" . $parts[0].")";
			$text = $parts[1];
			$this->addFeatureLayer('free_text'.$i, MS_LAYER_POINT, array($wkt), array($text), $style, $this->map_factor);
		}
	}

	function addFeatureLayer($name, $type, $features, $texts, $css_style_string, $map_factor){
		if($map_factor == '')$map_factor = 1;
		$layer = new LayerObj($this->map);
		$layer->name = $name;
		$layer->status = MS_ON;
    $layer->type = $type;
		$i = 0;
		foreach($features as $feature){
			if (MAPSERVERVERSION >= 800) {
				$shape = shapeObj::fromWKT($feature);
			}
			else {
				$shape = ms_shapeObjFromWkt($feature);
			}
			$shape->text = $texts[$i];
			$layer->addFeature($shape);
			$i++;
		}
		$class = new ClassObj($layer);
		if($texts != NULL){
			$label = new labelObj();
			$label->size = 10 * $map_factor;
			$label->color->setRGB(255, 0, 0);
			#$label->set('type', 'TRUETYPE');
			$label->font = 'arial';
			$label->position = MS_LR;
			$label->offsety = -9;
			$class->addLabel($label);
		}
    $style = new StyleObj($class);
		$css_styles = explode(';', str_replace(' ', '', $css_style_string));
		foreach($css_styles as $css_style){
			$parts = explode(':', $css_style);
			$property = $parts[0];
			$value = $parts[1];
			switch($property){
				case 'opacity' : {
					if (MAPSERVERVERSION > 700) {
						$layer->updateFromString("LAYER COMPOSITE OPACITY ".($value * 100)." END END");
					}
					else{
						$layer->set('opacity', $value * 100);
					}
				}break;
				case 'fill' : {
					$rgb = explode(',', substr($value, 4, -1));
					$style->color->setRGB($rgb[0], $rgb[1], $rgb[2]);
				}break;
				case 'stroke' : {
					$rgb = explode(',', substr($value, 4, -1));
					$style->outlinecolor->setRGB($rgb[0], $rgb[1], $rgb[2]);
				}break;
				case 'stroke-width' : {
					$style->width = $value*$map_factor;
				}break;
			}
		}
	}

	/**
	 * function uses the following formvars to set the filter for reading layers
	 * - nurAktiveLayer
	 * - nurAufgeklappteLayer
	 * - nurFremdeLayer
	 * - nurNameLike
	 * - class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
	 * @param string $loadMapSource von wo die Daten für die map geladen werden sollen Post, File oder DataBase
	 * @param Array $layerset Array der Layer die geladen werden sollen. (optional) mit folgenden keys (siehe result set von function mapDb->read_Layer):
	 *		list Layer[] Liste von Layerobjekten
	 *		anzLayer Integer Anzahl der Layer in list
	 * @param Boolean $strict_layer_name Optional Parameter. Wenn true wird die Layervariable name immer mit dem Layerattribute Name gesetzt
	 *  																 unabhängig ob in der Stelle die Verwendung von alias für Layer gesetzt ist.
	 */
  function loadMap($loadMapSource, $layerset = array(), $strict_layer_name = false) {
		$this->group_has_active_layers = array();
    $this->debug->write("<p>Funktion: loadMap('" . $loadMapSource . ")",4);
    switch ($loadMapSource) {
      # lade Karte aus Post-Parametern
      case 'Post' : {
        if (MAPSERVERVERSION < 600) {
				  $map = ms_newMapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				else {
				  $map = new mapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				#echo '<br>MapServer Version: '.ms_GetVersionInt();
				#echo '<br>Details: '.ms_GetVersion();

        # Allgemeine Parameter
        #var_dump($this->formvars);
        $map->width = $this->formvars['post_width'];
        $map->set('height', $this->formvars['post_height']);
        $map->set('resolution',72);
        $map->set('units',MS_METERS);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->imagecolor->setRGB(255,255,255);
        if($this->formvars['post_minx'] != ''){
          $map->setextent($this->formvars['post_minx'], $this->formvars['post_miny'], $this->formvars['post_maxx'], $this->formvars['post_maxy']);
        }
        else{
          $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }
        $map->setProjection('+init='.strtolower($this->formvars['post_epsg']));

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);

        # OWS Metadaten
        $map->web->metadata->set('ows_title', 'WMS Ausdruck');
        $map->web->metadata->set('wms_extent',$this->formvars['post_minx'].''.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);

        # Legendobject
        $map->legend->set('status', MS_ON);
        #$map->legend->set('transparent', MS_OFF);
        $map->legend->set('keysizex', '16');
        $map->legend->set('keysizey', '16');
        $map->legend->set('template', LAYOUTPATH . 'legend_layer.htm');
        $map->legend->imagecolor -> setRGB(255,255,255);
        $map->legend->outlinecolor -> setRGB(-1,-1,-1);
        $map->legend->label->set('type', MS_TRUETYPE);
        $map->legend->label->set('font', 'arial');
        $map->legend->label->set('size', 12);
        $map->legend->label->color->setRGB(5,30,220);

        # layer
        if (is_array($this->formvars['layer'])) {
          $layerset = array_values($this->formvars['layer']);
        }
        for ($i = 0; $i < count($layerset); $i++) {
					$layer = new layerObj($map);
					$layer->metadata->set('wms_name', $layerset[$i][name]);
          $layer->metadata->set('wms_server_version','1.1.1');
          $layer->metadata->set('wms_format','image/png');
          $layer->metadata->set('wms_extent',$this->formvars['post_minx'].' '.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);
          $layer->metadata->set('ows_title', $layerset[$i][name]);
          if($layerset[$i][epsg_code] != ''){
            $layer->metadata->set('ows_srs', $layerset[$i][epsg_code]);
          }
          else{
            $layer->metadata->set('ows_srs', $this->formvars['post_epsg']);
          }
          $layer->metadata->set('wms_exceptions_format', 'application/vnd.ogc.se_inimage');
          $layer->metadata->set('real_layer_status', 1);
          $layer->metadata->set('off_requires',0);
          $layer->metadata->set('wms_connectiontimeout',60);
          $layer->metadata->set('wms_queryable',0);
          $layer->metadata->set('wms_group_title','WMS');
          $layer->set('type', 3);
          $layer->set('name', $layerset[$i][name]);
          $layer->set('status', 1);
          if($this->map_factor == ''){
            $this->map_factor=1;
          }
          if($layerset[$i]['maxscale'] > 0) {
            $layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
          }
          if($layerset[$i]['minscale'] > 0) {
						$layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
          }
          if($layerset[$i][epsg_code] != ''){
            $layer->setProjection('+init='.strtolower($layerset[$i][epsg_code])); # recommended
          }
          else{
            $layer->setProjection('+init='.strtolower($this->formvars['post_epsg']));
          }

					#$layer->set('connection',"http://www.kartenserver.niedersachsen.de/wmsconnector/com.esri.wms.Esrimap/Biotope?LAYERS=7&REQUEST=GetMap&TRANSPARENT=true&FORMAT=image/png&SERVICE=WMS&VERSION=1.1.1&STYLES=&EXCEPTIONS=application/vnd.ogc.se_xml&SRS=EPSG:31467");
					#echo '<br>Name: '.$layerset[$i][name];
					$layer->set('connection',	$layerset[$i][connection]);
					if (MAPSERVERVERSION < 540) {
						$layer->set('connectiontype', 7);
					}
					else {
						$layer->setConnectionType(7);
					}
          if($layerset[$i]['transparency'] != ''){
            if(MAPSERVERVERSION > 500){
              $layer->set('opacity',$layerset[$i]['transparency']);
            }
            else{
              $layer->set('transparency',$layerset[$i]['transparency']);
            }
          }
        } # end of Schleife layer
        $this->map=$map;
      } break;

      # lade Karte von einer Map-Datei
      case 'File' : {
        $this->debug->write("MapDatei $connStr laden",4);
				if (MAPSERVERVERSION < 600) {
          $this->map = ms_newMapObj(DEFAULTMAPFILE);
        }
				else {
				  $this->map = new mapObj(DEFAULTMAPFILE);
				}
			} break;

      # lade Karte von Datenbank
      case 'DataBase' : {
				$this->debug->write('<br>Lade Defaultmapfile: ' . DEFAULTMAPFILE, 4);
				if (MAPSERVERVERSION < 600) {
					$map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$map = new mapObj(DEFAULTMAPFILE);
				}

        $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
				$num_default_layers = $map->numlayers;

				# Allgemeine Parameter
				define('MINIMAGESIZE', 10); # prevent error in setextent
				$map->resolution = 96;
				#$map->set('transparent', MS_OFF);
				#$map->set('interlace', MS_ON);
				$map->status = MS_ON;
				$map->name = MAPFILENAME;

				if (MS_DEBUG_LEVEL !== NULL) {
					$map->setConfigOption('MS_ERRORFILE', dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log');
					$map->debug = MS_DEBUG_LEVEL;
				}
				$map->imagecolor->setRGB(255,255,255);
				$map->maxsize = 4096;
				$map->setProjection('+init=epsg:' . $this->user->rolle->epsg_code);

				$map->setSize(
					($this->user->rolle->nImageWidth < MINIMAGESIZE ? MINIMAGESIZE : $this->user->rolle->nImageWidth),
					($this->user->rolle->nImageHeight < MINIMAGESIZE ? MINIMAGESIZE : $this->user->rolle->nImageHeight)
				);

				$bb = $this->Stelle->MaxGeorefExt;

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if ($this->user->rolle->oGeorefExt->minx==='') {
				  echo "Richten Sie mit phpMyAdmin in der kvwmap Datenbank eine Referenzkarte, eine Stelle, einen Benutzer und eine Rolle ein ";
				  echo "<br>(Tabellen referenzkarten, stelle, user, rolle) ";
				  echo "<br>oder wenden Sie sich an ihren Systemverwalter.";
				  exit;
				}
				else {
					if ($this->user->rolle->oGeorefExt->minx < $this->Stelle->MaxGeorefExt->minx)			$this->user->rolle->oGeorefExt->minx = $this->Stelle->MaxGeorefExt->minx;
					if ($this->user->rolle->oGeorefExt->miny < $this->Stelle->MaxGeorefExt->miny)			$this->user->rolle->oGeorefExt->miny = $this->Stelle->MaxGeorefExt->miny;
					if ($this->user->rolle->oGeorefExt->maxx > $this->Stelle->MaxGeorefExt->maxx)			$this->user->rolle->oGeorefExt->maxx = $this->Stelle->MaxGeorefExt->maxx;
					if ($this->user->rolle->oGeorefExt->maxy > $this->Stelle->MaxGeorefExt->maxy)			$this->user->rolle->oGeorefExt->maxy = $this->Stelle->MaxGeorefExt->maxy;
					if ($this->user->rolle->oGeorefExt->maxx <= $this->user->rolle->oGeorefExt->minx) $this->user->rolle->oGeorefExt->maxx = $this->user->rolle->oGeorefExt->minx + 1;
					if ($this->user->rolle->oGeorefExt->maxy <= $this->user->rolle->oGeorefExt->miny) $this->user->rolle->oGeorefExt->maxy = $this->user->rolle->oGeorefExt->miny + 1;
					if (value_of($this->formvars, 'go') != 'OWS') {
						$map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
					}
					else {
						$map->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
					}
				}



				# OWS Metadaten
				$map->web->metadata->set("ows_title", $this->Stelle->ows_title ?: OWS_TITLE);
				$map->web->metadata->set("ows_abstract", $this->Stelle->ows_abstract ?: OWS_ABSTRACT);
				$map->web->metadata->set("ows_accessconstraints", $this->Stelle->wms_accessconstraints ?: OWS_ACCESSCONSTRAINTS);
				$map->web->metadata->set("ows_contactorganization", $this->Stelle->ows_contactorganization ?: OWS_CONTACTORGANIZATION);
				$map->web->metadata->set("ows_contactperson", $this->Stelle->ows_contactperson ?: OWS_CONTACTPERSON);
				$map->web->metadata->set("ows_contactposition", $this->Stelle->ows_contactposition ?: OWS_CONTACTPOSITION);
				$map->web->metadata->set("ows_contactelectronicmailaddress", $this->Stelle->ows_contactelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS);
				$map->web->metadata->set("ows_contactvoicetelephone", $this->Stelle->ows_contactvoicephone ?: OWS_CONTACTVOICETELEPHONE);
				$map->web->metadata->set("ows_contactfacsimiletelephone", $this->Stelle->ows_contactfacsimile ?: OWS_CONTACTFACSIMILETELEPHONE);
				$map->web->metadata->set("ows_stateorprovince", $this->Stelle->ows_contactadministrativearea ?: OWS_STATEORPROVINCE);
				$map->web->metadata->set("ows_address", $this->Stelle->ows_contactaddress ?: OWS_ADDRESS);
				$map->web->metadata->set("ows_postcode", $this->Stelle->ows_contactpostalcode ?: OWS_POSTCODE);
				$map->web->metadata->set("ows_city", $this->Stelle->ows_contactcity ?: OWS_CITY);
				$map->web->metadata->set("ows_country", OWS_COUNTRY);
				$map->web->metadata->set("ows_addresstype", 'postal');
				$map->web->metadata->set("ows_fees", $this->Stelle->ows_fees ?: OWS_FEES);
				$map->web->metadata->set("ows_encoding", 'UTF-8');
				$map->web->metadata->set("ows_keywordlist", OWS_KEYWORDLIST);
				$map->web->metadata->set("ows_contactinstructions", OWS_CONTACTINSTRUCTIONS);
				$map->web->metadata->set("ows_hoursofservice", OWS_HOURSOFSERVICE);
				$map->web->metadata->set("ows_role", OWS_ROLE);
				$map->web->metadata->set("ows_srs", $this->Stelle->ows_srs ?: OWS_SRS);
				if (value_of($_REQUEST, 'onlineresource') != '') {
					$ows_onlineresource = $_REQUEST['onlineresource'];
				}
				else {
					if (isset($this->formvars['gast']) AND $this->formvars['gast'] != '') {
						$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&gast=' . $this->formvars['gast'];
					}
					else {
						$ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&Stelle_ID=' . $this->Stelle->id .'&login_name=' . value_of($_REQUEST, 'login_name') . '&passwort=' .  urlencode(value_of($_REQUEST, 'passwort'));
					}
				}
				$map->web->metadata->set("ows_onlineresource", $ows_onlineresource);
				$map->web->metadata->set("ows_service_onlineresource", $ows_onlineresource);

				$map->web->metadata->set("wms_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
				// enable service types
				$map->web->metadata->set("ows_enable_request", '*');

        ///------------------------------////

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->shapepath = SHAPEPATH;

        # Umrechnen des Stellenextents kann hier raus, weil es schon in start.php gemacht wird

        # Webobject
        $map->web->imagepath = IMAGEPATH;
        $map->web->imageurl = IMAGEURL;
        $map->web->log = LOGPATH . 'mapserver.log';
        $map->web->metadata->set('wms_feature_info_mime_type', 'text/html');
        //$map->web->set('ERRORFILE', LOGPATH.'mapserver_error.log');

        # Referenzkarte
				$reference_map = new mapObj(DEFAULTMAPFILE);
				$this->ref=$mapDB->read_ReferenceMap();
				if (!is_array($this->ref)) {
					$this->ref = array(
						'epsg_code' => 25833,
						'refMapImg' => WWWROOT . APPLVERSION . GRAPHICSPATH . 'karte.png'
					);
				}
				else {
					$this->ref['refMapImg'] = REFERENCEMAPPATH.$this->ref['Dateiname'];
				}
				$reference_map->web->imagepath = IMAGEPATH;
				$reference_map->setProjection('+init=epsg:' . $this->ref['epsg_code']);
				$reference_map->reference->extent->minx = round($this->ref['xmin']);
				$reference_map->reference->extent->miny = round($this->ref['ymin']);
				$reference_map->reference->extent->maxx = round($this->ref['xmax']);
				$reference_map->reference->extent->maxy = round($this->ref['ymax']);
				$reference_map->reference->image = $this->ref['refMapImg'];
        $reference_map->reference->width = $this->ref['width'];
        $reference_map->reference->height = $this->ref['height'];
        $reference_map->reference->status = MS_ON;
				$reference_map->reference->color->setRGB(-1,-1,-1);
				$reference_map->reference->outlinecolor->setRGB(255,0,0);

        # Scalebarobject
        $map->scalebar->status = MS_ON;
        $map->scalebar->units =  MS_METERS;
        $map->scalebar->intervals = 4;
        $map->scalebar->color->setRGB(0,0,0);
        $r = substr(BG_MENUETOP, 1, 2);
        $g = substr(BG_MENUETOP, 3, 2);
        $b = substr(BG_MENUETOP, 5, 2);
        $map->scalebar->imagecolor->setRGB(hexdec($r), hexdec($g), hexdec($b));
        $map->scalebar->outlinecolor->setRGB(0,0,0);
				$map->scalebar->label->font = 'SourceSansPro';		# Kommentarzeichen wieder entfernt, da sonst auf Metropolplaner Fehler
				if (MAPSERVERVERSION < 700 ) {
					$map->scalebar->label->type = 'truetype';
				}
				$map->scalebar->label->size = 10.5;

				# Groups
				if (value_of($this->formvars, 'nurAufgeklappteLayer') == '') {
					$this->groupset = $mapDB->read_Groups();
				}

				# Filter für read_Layer
				$mapDB->nurAktiveLayer = value_of($this->formvars, 'nurAktiveLayer');
				$mapDB->nurAufgeklappteLayer = value_of($this->formvars, 'nurAufgeklappteLayer');
				$mapDB->nurFremdeLayer = value_of($this->formvars, 'nurFremdeLayer');
				$mapDB->nurNameLike = value_of($this->formvars, 'nurNameLike');
				$mapDB->nurPostgisLayer = value_of($this->formvars, 'only_postgis_layer');
				$mapDB->keinePostgisLayer = value_of($this->formvars, 'no_postgis_layer');
				$mapDB->nurLayerID = value_of($this->formvars, 'only_layer_id');
				$mapDB->nurLayerIDs = value_of($this->formvars, 'only_layer_ids');
				$mapDB->nichtLayerID = value_of($this->formvars, 'not_layer_id');

        if ($this->class_load_level == '') {
          $this->class_load_level = 1;
        }

				if (count($layerset) == 0) {
					$layerset = $mapDB->read_Layer($this->class_load_level, $this->Stelle->useLayerAliases, $this->list_subgroups(value_of($this->formvars, 'group')));
					$rollenlayer = $mapDB->read_RollenLayer();
					$layerset['list'] = array_merge($layerset['list'], $rollenlayer);
					$layerset['anzLayer'] = count($layerset['list']);
				}
        unset($this->layer_ids_of_group);		# falls loadmap zweimal aufgerufen wird
				$layerset['layer_group_has_legendorder'] = array();
				$this->error_message = '';
				for ($i = 0; $i < $layerset['anzLayer']; $i++) {
					$layerset['layers_of_group'][$layerset['list'][$i]['Gruppe']][] = $i;
					if(value_of($layerset['list'][$i], 'legendorder') != ''){
						$layerset['layer_group_has_legendorder'][$layerset['list'][$i]['Gruppe']] = true;
					}
					if(value_of($layerset['list'][$i], 'requires') == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['Gruppe']][] = $layerset['list'][$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if (value_of($layerset['list'][$i], 'requires') != '') {
						$layerset['list'][$i]['aktivStatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivStatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if ($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivStatus'] != 0)) {
						# nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layerset['list'][$i]['layer_index_mapobject'] = $map->numlayers;

						$this->loadlayer($map, $layerset['list'][$i], $strict_layer_name);
						$error = msGetErrorObj();
						$test = 0;
						while ($test < 100 AND $error && $error->code != MS_NOERR) {
							$this->error_message .= '<br>Fehler beim Laden des Layers mit der Layer-ID: ' . $layerset['list'][$i]['Layer_ID'] . 
							'<br>&nbsp;&nbsp;in der Routine ' . $error->routine . ' Msg="' . $error->message . '" code=' . $error->code;
							$error = $error->next();
							$test++;
						}
						msResetErrorList();
					}
				}
				if ($this->error_message != '') {
					#$this->error_message .= '<br>';
					#throw new ErrorException($this->error_message);
				}
				$this->layerset = $layerset;
				if ($num_default_layers > 0 AND $map->numlayers > $num_default_layers) {
					#$map->setLayersDrawingOrder($this->get_default_layers_top_drawing_order($map->numlayers, $num_default_layers)); geht wohl so in SwigMapscript nicht
				}
				$this->map = $map;
				$this->reference_map = $reference_map;
				if (MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $map->scaledenom;
				}
				else {
					$this->map_scaledenom = $map->scale;
				}
				$this->mapDB = $mapDB;
			} break; # end of lade Karte von Datenbank
		} # end of switch loadMapSource
		return 1;
	}

	function print($id, $x) {
        $error = ms_GetErrorObj();
        if ($error && $error->code != MS_NOERR) {
            while ($error && $error->code != MS_NOERR) {
                error_log($id . '-' . $x . '  ' . $error->routine . ' Msg="' . $error->message . '" code=' . $error->code);
                $error = $error->next();
            }
        } 
        else {
            error_log($x . '   noError');
        }
    }	

	/**
	 * Return a drawing order with default layers to top
	 */
	function get_default_layers_top_drawing_order($num_layers, $num_default_layers) {
		$drawing_order = range($num_default_layers, $num_layers - 1);
		foreach(range(0, $num_default_layers - 1) AS $order) {
			$drawing_order[] = $order;
		}
		return $drawing_order;
	}

	
	function loadlayer($map, $layerset, $strict_layer_name = false) {
		$this->debug->write('<br>Lade Layer: ' . $layerset['Name'], 4);
		if ($strict_layer_name) {
			$layer_name_attribute = 'Name';
		}
		else {
			$layer_name_attribute = 'Name_or_alias';
		}
		$layer = new LayerObj($map);
		$layer->name = $layerset[$layer_name_attribute];
		$layer->metadata->set('wms_name', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('kvwmap_layer_id', $layerset['Layer_ID']);
		$layer->metadata->set('wfs_request_method', 'GET');
		if ($layerset['wms_keywordlist']) {
			$layer->metadata->set('ows_keywordlist', $layerset['wms_keywordlist']);
		}
		$layer->metadata->set('wfs_typename', $layerset['wms_name'] ?: ''); #Mapserver8
		$layer->metadata->set('wms_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		$layer->metadata->set('wfs_title', $layerset['Name_or_alias'] ?: ''); #Mapserver8
		# Umlaute umwandeln weil es in einigen Programmen (masterportal und MapSolution) mit Komma und Leerzeichen in wms_group_title zu problemen kommt.
		$layer->metadata->set('wms_group_title', sonderzeichen_umwandeln($layerset['Gruppenname']));
		$layer->metadata->set('wms_queryable',$layerset['queryable']);
		$layer->metadata->set('wms_format',$layerset['wms_format'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_server_version',$layerset['wms_server_version'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_version',$layerset['wms_server_version'] ?: ''); #Mapserver8
		if ($layerset['metalink']) {
			$layer->metadata->set('ows_metadataurl_href',$layerset['metalink']);
			$layer->metadata->set('ows_metadataurl_type', 'ISO 19115');
			$layer->metadata->set('ows_metadataurl_format', 'text/plain');
		}
		if ($layerset['ows_srs'] == '') {
			$layerset['ows_srs'] = 'EPSG:' . $layerset['epsg_code'];
		}		
		$layer->metadata->set('ows_srs', $layerset['ows_srs']);
		$layer->metadata->set('wms_connectiontimeout',$layerset['wms_connectiontimeout'] ?: ''); #Mapserver8
		$layer->metadata->set('ows_auth_username', $layerset['wms_auth_username'] ?: '');
		$layer->metadata->set('ows_auth_password', $layerset['wms_auth_password'] ?: '');
		$layer->metadata->set('ows_auth_type', 'basic');
		$layer->metadata->set('wms_exceptions_format', ($layerset['wms_server_version'] == '1.3.0' ? 'XML' : 'application/vnd.ogc.se_xml'));
		# ToDo: das Setzen von ows_extent muss in dem System erfolgen, in dem der Layer definiert ist (erstmal rausgenommen)
		#$layer->metadata->set("ows_extent", $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);		# führt beim WebAtlas-WMS zu einem Fehler
		$layer->metadata->set("gml_featureid", $layerset['oid'] ?: ''); #Mapserver8
		$layer->metadata->set("gml_include_items", "all");
		#$layer->metadata->set('wms_abstract', $layerset['kurzbeschreibung']); #Mapserver8
		$layer->dump = 0;
		$layer->type = $layerset['Datentyp'];
		$layer->group = sonderzeichen_umwandeln($layerset['Gruppenname']);

		if(value_of($layerset, 'status') != ''){
			$layerset['aktivStatus'] = 0;
		}


		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if ($layerset['aktivStatus'] == 0) {
			$layer->status = 0;
		}
		else{
			$layer->status = 1;
		}

		# fremde Layer werden auf Verbindung getestet
		if ($layerset['aktivStatus'] != 0 AND $layerset['connectiontype'] == 6) {
			$credentials = $this->pgdatabase->get_credentials($layerset['connection_id']);
			if (!in_array($credentials['host'], array('pgsql', 'localhost'))) {
				$fp = @fsockopen($credentials['host'], $credentials['port'], $errno, $errstr, 5);
				if (!$fp) {			# keine Verbindung --> Layer ausschalten
					$layer->status = 0;
					$layer->metadata->set('queryStatus', 0);
					$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['Name'].'<br>';
				}
			}
		}
		
		if($layerset['aktivStatus'] != 0){
			$collapsed = false;
			if($group = value_of($this->groupset, $layerset['Gruppe'])){				# die Gruppe des Layers
				if($group['status'] == 0){
					$this->group_has_active_layers[$layerset['Gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
					$collapsed = true;
				}
				while($group['obergruppe'] != ''){
					$group = $this->groupset[$group['obergruppe']];
					if($collapsed OR $group['status'] == 0){
						$this->group_has_active_layers[$group['id']] = 1;  	# auch alle Obergruppen durchlaufen
						$collapsed = true;
					}
				}
			}
		}

		if(!$this->noMinMaxScaling AND value_of($layerset, 'minscale') >= '0') {
			if($this->map_factor != ''){
				$layer->minscaledenom = $layerset['minscale']/$this->map_factor*1.414;
			}
			else{
				$layer->minscaledenom = $layerset['minscale'];
			}
		}
		if(!$this->noMinMaxScaling AND value_of($layerset, 'maxscale') > 0) {
			if($this->map_factor != ''){
				$layer->maxscaledenom = $layerset['maxscale']/$this->map_factor*1.414;
			}
			else{
				$layer->maxscaledenom = $layerset['maxscale'];
			}
		}
		$layer->setProjection('+init=epsg:' . $layerset['epsg_code']); # recommended
		if ($layerset['connection']!='') {
			if($layerset['connectiontype'] == 7) { # WMS-Layer
				# $layerset['connection'] .= '&SERVICE=WMS'; # Das kann zu Fehler führen. MapServer setzt selber SERVICE=WMS
				if ($this->map_factor != '') {
					if ($layerset['printconnection']!=''){
						$layerset['connection'] = $layerset['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
					}
					else{
						//$layerset['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
					}
				}
			}
			if ($layerset['connectiontype'] == 6) {
				# z.B. für Klassen mit Umlauten
				$layerset['connection'] .= " options='-c client_encoding=UTF8'";
			}
			$layer->connection = $layerset['connection'];
		}

		if ($layerset['connectiontype'] > 0) {
			$layer->setConnectionType($layerset['connectiontype'], '');
		}

		if ($layerset['connectiontype'] == 6) {
			$layerset['processing'] = 'CLOSE_CONNECTION=ALWAYS;' . value_of($layerset, 'processing');		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
		}

		if ($layerset['processing'] != "") {
			$processings = explode(";",	replace_params_rolle($layerset['processing']));
			foreach ($processings as $processing) {
				if (MAPSERVERVERSION >= 800) {
					$p = explode('=', $processing);
					$layer->setProcessingKey($p[0], $p[1]);
				}
				else {
					$layer->setProcessing($processing);
				}
			}
		}

		if (value_of($layerset, 'postlabelcache') != 0) {
			$layer->postlabelcache = $layerset['postlabelcache'];
		}

		if($layerset['Datentyp'] == MS_LAYER_POINT AND value_of($layerset, 'cluster_maxdistance') != ''){
			$layer->cluster->maxdistance = $layerset['cluster_maxdistance'];
			$layer->cluster->region = 'ellipse';
		}

		if ($layerset['Datentyp']=='3') {
			if($layerset['transparency'] != ''){
				if (MAPSERVERVERSION > 700) {
					$layer->updateFromString("LAYER COMPOSITE OPACITY ".$layerset['transparency']." END END");
				}
				else{
					$layer->opacity = $layerset['transparency'];
				}
			}
			if ($layerset['tileindex']!='') {
				$layer->tileindex = SHAPEPATH.$layerset['tileindex'];
			}
			else {
				$layer->data = $layerset['Data'];
			}
			$layer->tileitem = $layerset['tileitem'];
			if ($layerset['offsite']!='') {
				$RGB=explode(' ',$layerset['offsite']);
				$layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
		}
		else {
			# Vektorlayer
			if ($layerset['Data'] != '') {
				if (strpos($layerset['Data'], '$SCALE') !== false){
					$this->layers_replace_scale[] =& $layer;
				}
				$layer->data = $layerset['Data'];
			}
			
			if (value_of($layerset, 'buffer') != NULL AND value_of($layerset, 'buffer') != 0) {
				$geom = explode(' ', $layer->data)[0];
				$data = substr_replace($layer->data, 'geom1', 0, strlen($geom));
				$geography = (in_array($layerset['epsg_code'], [4326, 4258])? '::geography' : '');
				$layer->data = str_ireplace('select ', 'select st_buffer(' . $geom . $geography . ', ' . $layerset['buffer'] . ') as geom1, ', $data);
				$layer->data;
				$layer->type = 2;
			}

			# Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
			# Template (Body der Anzeige)
			if (value_of($this->formvars, 'go') == 'OWS') {
				$layer->template = 'dummy';
			}
			# Header (Kopfdatei)
			if (value_of($layerset, 'header') != '') {
				$layer->header = $layerset['header'];
			}
			# Footer (Fusszeile)
			if (value_of($layerset, 'footer') != '') {
				$layer->footer = $layerset['footer'];
			}
			# Setzen der Spalte nach der der Layer klassifiziert werden soll
			if ($layerset['classitem']!='') {
				$layer->classitem = $layerset['classitem'];
			}
			# Setzen des Filters
			if ($layerset['Filter'] != '') {
				# 2024-07-28 pk Replace all params in Filter
				// $layerset['Filter'] = str_replace('$USER_ID', $this->user->id, $layerset['Filter']);
				$layerset['Filter'] = replace_params_rolle($layerset['Filter']);
				if (substr($layerset['Filter'], 0, 1) == '(') {
					switch (true) {
						case MAPSERVERVERSION >= 800 : {
							$layer->setProcessingKey('NATIVE_FILTER', $layerset['Filter']);
						}break;
						case MAPSERVERVERSION >= 700 : {
							$layer->setProcessing('NATIVE_FILTER=' . $layerset['Filter']);
						} break;
						default : {
							$layer->setFilter($layerset['Filter']);
						}
					}
			 }
			 else {
				 $expr=buildExpressionString($layerset['Filter']);
				 $layer->setFilter($expr);
			 }
			}
			if ($layerset['styleitem']!='') {
				$layer->styleitem = $layerset['styleitem'];
			}
			# Layerweite Labelangaben
			if ($layerset['labelitem']!='') {
				$layer->labelitem = $layerset['labelitem'];
			}
			if (value_of($layerset, 'labelmaxscale') != '') {
				$layer->labelmaxscaledenom = $layerset['labelmaxscale'];
			}
			if (value_of($layerset, 'labelminscale') != '') {
				$layer->labelminscaledenom = $layerset['labelminscale'];
			}
			if (value_of($layerset, 'labelrequires') != '') {
				$layer->labelrequires = $layerset['labelrequires'];
			}
			if (value_of($layerset, 'tolerance') != '') {
				$layer->tolerance = $layerset['tolerance'];
			}
			if (value_of($layerset, 'toleranceunits') != '') {
				$layer->toleranceunits = constant('MS_' . strtoupper($layerset['toleranceunits']));
			}
			
			if (value_of($layerset, 'sizeunits') != '') {
				$layer->sizeunits = $layerset['sizeunits'];
			}
			if ($layerset['transparency']!=''){
				if ($layerset['transparency']==-1) {
						$layer->opacity = MS_GD_ALPHA;
				}
				else {
					if (MAPSERVERVERSION > 700) {
						$layer->updateFromString("LAYER COMPOSITE OPACITY ".$layerset['transparency']." END END");
					}
					else{
						$layer->opacity = $layerset['transparency'];
					}
				}
			}
			if (value_of($layerset, 'symbolscale') != '') {
				if($this->map_factor != ''){
					$layer->symbolscaledenom = $layerset['symbolscale']/$this->map_factor*1.414;
				}
				else{
					$layer->symbolscaledenom = $layerset['symbolscale'];
				}
			}
		} # ende of Vektorlayer
		$classset=$layerset['Class'];		
		$this->loadclasses($layer, $layerset, $classset, $map);
	}

  function loadclasses($layer, $layerset, $classset, $map){
		$anzClass = count_or_0($classset);
    for ($j = 0; $j < $anzClass; $j++) {
      $klasse = new ClassObj($layer);
      if ($classset[$j]['Name']!='') {
        $klasse->name = $classset[$j]['Name'];
      }
      if ($classset[$j]['Status']=='1'){
      	$klasse->status = MS_ON;
      }
      else {
      	$klasse->status = MS_OFF;
      }
			if (value_of($layerset, 'template') != '') {
				$klasse->template = value_of($layerset, 'template');
			}
			$klasse->setexpression(str_replace([chr(10), chr(13)], '', $classset[$j]['Expression']));
      if ($classset[$j]['text'] != '' AND is_null($layerset['user_labelitem'])) {
				$klasse->settext("'" . trim($classset[$j]['text'], "'") . "'");
      }
      if ($classset[$j]['legendgraphic'] != '') {
				$imagename = WWWROOT . APPLVERSION . CUSTOM_PATH . 'graphics/' . $classset[$j]['legendgraphic'];
				$klasse->keyimage = $imagename;
			}			
      for ($k = 0; $k < count_or_0($classset[$j]['Style']); $k++) {
        $dbStyle = $classset[$j]['Style'][$k];
				$style = new styleObj($klasse);
				if ($dbStyle['geomtransform'] != '') {
					$style->updateFromString('STYLE GEOMTRANSFORM "' . $dbStyle['geomtransform'] . '" END');
				}
				if ($dbStyle['minscale'] != ''){
					$style->minscaledenom = $dbStyle['minscale'];
				}
				if($dbStyle['maxscale'] != ''){
					$style->maxscaledenom = $dbStyle['maxscale'];
				}
				if ($layerset['Datentyp'] == 0 AND value_of($layerset, 'buffer') != NULL AND value_of($layerset, 'buffer') != 0) {
					$dbStyle['symbolname'] = NULL;
					$dbStyle['symbol'] = NULL;
				}
				if (substr($dbStyle['symbolname'], 0, 1) == '[') {
					$style->updateFromString('STYLE SYMBOL ' .$dbStyle['symbolname']. ' END');
				}
				else {
					if ($dbStyle['symbolname']!='') {
						if (MAPSERVERVERSION < 800) {
							$style->symbolname = $dbStyle['symbolname'];
						}
						else {
							$style->setSymbolByName($map, $dbStyle['symbolname']);
						}
					}
					if ($dbStyle['symbol']>0) {
						$style->symbol = $dbStyle['symbol'];
					}
				}				
				if ($dbStyle['geomtransform'] != '') {
					# Es scheint so als würde phpMapScript in dieser Version centroid und andere ignorieren
					# centroid wird zwar gesetzt was man mit getGeomTransform abfragen kann, aber
					# es landet nicht im STYLE Objekt was man mit convertToString abfragen kann.
					# centroid und andere z.B. simplify werden nicht in der MapDatei mit saveMap ausgegeben!
					# Ein workaround wäre irgend etwas anderes einzufügen, was sich auf den Style nicht tauswirkt
					# und das dann nach dem Speichern der MapDatei mit dem richtigen zu ersetzen.
					# Man könnte sich auch alle Symbole merken die als center gesetzt werden sollen
					# und nach dem Speichern des Mapfiles
					# SYMBOL "symbolname"
					# durch
					# SYMBOL "symbolname"
					# GEOMTRANSFORM "centroid"
					# ersetzen. Das hat den Nachteil, dass centroid auch für Styles gesetzt wird
					# die zwar das Symbol verwenden aber nicht centroid sein sollen.
					# Man könnte auch eine bestimmte OUTLINEWITH schreiben. Die wirkt sich nur auf (ellipse, truetype and polygon vector symbols) aus
					# diese OUTLINEWITH wurde hier im Code bisher noch garnicht verwendet könnte aber auch mit einer bestimmten Konstante ergänzt werden
					# z.B. OUTLINEWITH + 0.000001 Das wird dann erstetzt durch das ohne .000001 und die GEOMTRANSFORM centroid
					# Man könnten auch für verschiedene Konstanten unterschiedliche GEOMTRANSFORM, die auch nicht funktionieren ersetzen.
					$style->updateFromString('STYLE GEOMTRANSFORM "' . $dbStyle['geomtransform'] . '" END');
					$style->updateFromString('STYLE OUTLINEWIDTH 1.00001 END');
				}
				if ($dbStyle['pattern'] != '') {
					if ($this->map_factor != '') {
						$pattern = explode(' ', $dbStyle['pattern']);
						foreach($pattern as &$pat){
							$pat = $pat * $this->map_factor;
						}
						$style->updateFromString("STYLE PATTERN " . implode(' ', $pattern) . " END END");
					}
					else {
						$style->updateFromString("STYLE PATTERN " . $dbStyle['pattern']." END END");		
					}
					$style->linecap = 'butt';
				}
				if($dbStyle['gap'] != '') {
					if($this->map_factor != ''){
						$style->gap = $dbStyle['gap']*$this->map_factor/1.414;
					}
					else{
						$style->gap = $dbStyle['gap'];
					}
				}		
				if($dbStyle['initialgap'] != '') {
					$style->initialgap = $dbStyle['initialgap'];
				}
				if($dbStyle['linecap'] != '') {
					$style->linecap = constant('MS_CJC_'.strtoupper($dbStyle['linecap']));
				}
				else {
					$style->linecap = constant('MS_CJC_ROUND');
				}
				if($dbStyle['linejoin'] != '') {
					$style->linejoin = constant('MS_CJC_'.strtoupper($dbStyle['linejoin']));
				}
				if($dbStyle['linejoinmaxsize'] != '') {
					$style->linejoinmaxsize = $dbStyle['linejoinmaxsize'];
				}
				if($dbStyle['polaroffset'] != '') {
					$style->updateFromString("STYLE POLAROFFSET " . $dbStyle['polaroffset']." END");
				}

				if($dbStyle['size'] != ''){
					if ($layerset['Datentyp'] == 8) {
						# Skalierung der Stylegröße when Type Chart
						$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
					}
					else {
						if ($this->map_factor != '') {
							if (is_numeric($dbStyle['size'])) {
								$style->size = $dbStyle['size'] * $this->map_factor/1.414;
							}
							else {
								$style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
							}
						}
						else {
							if (is_numeric($dbStyle['size'])) {
								$style->size = $dbStyle['size'];
							}
							else {
								$style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
							}
						}
					}
				}

        if ($dbStyle['minsize']!='') {
          if ($this->map_factor != ''){
            $style->minsize = $dbStyle['minsize']*$this->map_factor/1.414;
          }
          else {
            $style->minsize = $dbStyle['minsize'];
          }
        }

        if ($dbStyle['maxsize']!='') {
          if($this->map_factor != ''){
            $style->maxsize = $dbStyle['maxsize']*$this->map_factor/1.414;
          }
          else{
            $style->maxsize = $dbStyle['maxsize'];
          }
        }

				if($dbStyle['angle'] != '') {
					$style->updateFromString("STYLE ANGLE " . $dbStyle['angle'] . " END"); 		# wegen AUTO
				}

				if ($dbStyle['angleitem']!=''){
					$style->setbinding(MS_STYLE_BINDING_ANGLE, $dbStyle['angleitem']);
        }
        if ($dbStyle['width']!='') {
					if ($this->map_factor != '') {
						if (is_numeric($dbStyle['width'])) {
							$style->width = $dbStyle['width']*$this->map_factor/1.414;
						}
						else {
							$style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
						}
					}
					else{
						if (is_numeric($dbStyle['width'])) {
							$style->width = $dbStyle['width'];
						}
						else {
							$style->updateFromString("STYLE WIDTH [" . $dbStyle['width']."] END");
						}
					}
        }
		
        if ($dbStyle['minwidth']!='') {
          if ($this->map_factor != '') {
            $style->minwidth = $dbStyle['minwidth']*$this->map_factor/1.414;
          }
          else {
            $style->minwidth = $dbStyle['minwidth'];
          }
        }

        if ($dbStyle['maxwidth']!='') {
          if ($this->map_factor != '') {
            $style->maxwidth = $dbStyle['maxwidth'] * $this->map_factor/1.414;
          }
          else {
            $style->maxwidth = $dbStyle['maxwidth'];
          }
        }

        if ($dbStyle['color']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [" . $dbStyle['color']."] END");
        }
				if ($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					if (MAPSERVERVERSION >= 800) {
						$style->updateFromString("STYLE OPACITY " . $dbStyle['opacity'] . " END");
					}
					else {
						$style->opacity = $dbStyle['opacity'];
					}
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
					if(is_numeric($RGB[0]))$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE OUTLINECOLOR [" . $dbStyle['outlinecolor']."] END");					
        }
				if($dbStyle['colorrange'] != '') {
					$style->updateFromString("STYLE COLORRANGE " . $dbStyle['colorrange']." END");
				}
				if($dbStyle['datarange'] != '') {
					$style->updateFromString("STYLE DATARANGE " . $dbStyle['datarange']." END");
				}
				if($dbStyle['rangeitem'] != '') {
					$style->updateFromString("STYLE RANGEITEM " . $dbStyle['rangeitem']." END");
				}
				$offset_attribute = false;
				if (!is_numeric($dbStyle['offsetx'] ?: 0)){
					$dbStyle['offsetx'] = '[' . $dbStyle['offsetx'] . ']';
					$offset_attribute = true;
				}
				if (!is_numeric($dbStyle['offsety'] ?: 0)){
					$dbStyle['offsety'] = '[' . $dbStyle['offsety'] . ']';
					$offset_attribute = true;
				}
				if ($offset_attribute) {
					$style->updateFromString("STYLE offset " . ($dbStyle['offsetx'] ?: '0') . " " . ($dbStyle['offsety'] ?: '0') . " END");
				}
				else {
					if ($dbStyle['offsetx']!='') {
						$style->offsetx = $dbStyle['offsetx'];
					}
					if ($dbStyle['offsety']!='') {
						$style->offsety = $dbStyle['offsety'];
					}
				}
      } # Ende Schleife für mehrere Styles
	  
      # setzen eines oder mehrerer Labels
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Label']);$k++) {
        $dbLabel=$classset[$j]['Label'][$k];
				$label = new labelObj();
				if (MAPSERVERVERSION < 700 ) {
					$label->type = 'truetype';
				}
				if ($dbLabel['text'] != '') {
					$label->updateFromString("LABEL TEXT '" . $dbLabel['text'] . "' END");
				}				
				$label->font = $dbLabel['font'];
				$RGB=explode(" ",$dbLabel['color']);
				if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
				$label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
				if($dbLabel['outlinecolor'] != ''){
					$RGB=explode(" ",$dbLabel['outlinecolor']);
					$label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
				}
				if ($dbLabel['shadowcolor']!='') {
					$RGB=explode(" ",$dbLabel['shadowcolor']);
					$label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					$label->shadowsizex = $dbLabel['shadowsizex'];
					$label->shadowsizey = $dbLabel['shadowsizey'];
				}
				if($dbLabel['backgroundshadowcolor']!='') {
					$RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
					if (MAPSERVERVERSION < 800 ) {
						$style = new styleObj($label);
					}
					else {
						$style = new styleObj();
					}
					$style->setGeomTransform('labelpoly');
					$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					$style->offsetx = $dbLabel['backgroundshadowsizex'];
					$style->offsety = $dbLabel['backgroundshadowsizey'];
					if ($dbLabel['buffer']!='') {
						$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
						$style->width = $dbLabel['buffer'];
					}
					$label->insertStyle($style);
				}
				if ($dbLabel['backgroundcolor']!='') {
					$RGB=explode(" ",$dbLabel['backgroundcolor']);
					if (MAPSERVERVERSION < 800 ) {
						$style = new styleObj($label);
					}
					else {
						$style = new styleObj();
					}
					$style->setGeomTransform('labelpoly');
					$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					if ($dbLabel['buffer']!='') {
						$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
						$style->width = $dbLabel['buffer'];
					}
					$label->insertStyle($style);
				}

				$label->angle = $dbLabel['angle'];
				if(value_of($layerset, 'labelangleitem') != ''){
					$label->setBinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
				}
				if($dbLabel['anglemode'] != NULL) {
					$label->anglemode = $dbLabel['anglemode'];
				}
				if ($dbLabel['buffer']!='') {
					$label->buffer = $dbLabel['buffer'];
				}
				$label->maxlength = $dbLabel['maxlength'];
				$label->repeatdistance = $dbLabel['repeatdistance'];
				if (MAPSERVERVERSION >= 800) {
					$label->wrap = chr($dbLabel['wrap']);
				}
				else {
					$label->wrap = $dbLabel['wrap'];
				}
				$label->force = $dbLabel['the_force'];
				$label->partials = $dbLabel['partials'];
				$label->size = $dbLabel['size'];
				$label->minsize = $dbLabel['minsize'];
				$label->maxsize = $dbLabel['maxsize'];
				$label->minfeaturesize = $dbLabel['minfeaturesize'];
				if ($dbLabel['maxscale'] != '') {
					$label->maxscaledenom = $dbLabel['maxscale'];
				}
				if ($dbLabel['minscale'] != '') {
					$label->minscaledenom = $dbLabel['minscale'];
				}
				# Skalierung der Labelschriftgröße, wenn map_factor gesetzt
				if($this->map_factor != ''){
					$label->minsize = $dbLabel['minsize']*$this->map_factor/1.414;
					$label->maxsize = $dbLabel['size']*$this->map_factor/1.414;
					$label->size = $dbLabel['size']*$this->map_factor/1.414;
				}
				if ($dbLabel['position']!='') {
					switch ($dbLabel['position']){
						case '0' :{
							$label->position = MS_UL;
						}break;
						case '1' :{
							$label->position = MS_LR;
						}break;
						case '2' :{
							$label->position = MS_UR;
						}break;
						case '3' :{
							$label->position = MS_LL;
						}break;
						case '4' :{
							$label->position = MS_CR;
						}break;
						case '5' :{
							$label->position = MS_CL;
						}break;
						case '6' :{
							$label->position = MS_UC;
						}break;
						case '7' :{
							$label->position = MS_LC;
						}break;
						case '8' :{
							$label->position = MS_CC;
						}break;
						case '9' :{
							$label->position = MS_AUTO;
						}break;
					}
				}
				$offset_attribute = false;
				if (!is_numeric($dbLabel['offsetx'] ?: 0)){
					$dbLabel['offsetx'] = '[' . $dbLabel['offsetx'] . ']';
					$offset_attribute = true;
				}
				if (!is_numeric($dbLabel['offsety'] ?: 0)){
					$dbLabel['offsety'] = '[' . $dbLabel['offsety'] . ']';
					$offset_attribute = true;
				}
				if ($offset_attribute) {
					$label->updateFromString("LABEL offset " . ($dbLabel['offsetx'] ?: '0') . " " . ($dbLabel['offsety'] ?: '0') . " END");
				}
				else {
					if ($dbLabel['offsetx']!='') {
						$label->offsetx = $dbLabel['offsetx'];
					}
					if ($dbLabel['offsety']!='') {
						$label->offsety = $dbLabel['offsety'];
					}
				}
				$klasse->addLabel($label);
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

  function navMap($cmd) {
    switch ($cmd) {
      case "previous" : {
#        $this->user->rolle->set_selected_button('previous');
        $this->setPrevMapExtent($this->user->rolle->last_time_id);
      } break;
      case "next" : {
#        $this->user->rolle->set_selected_button('next');
        $this->setNextMapExtent($this->user->rolle->last_time_id);
      } break;
      case "zoomin" : {
        $this->user->rolle->set_selected_button('zoomin');
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
			case "zoomin_wheel" : {
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
      case "zoomout" : {
        $this->user->rolle->set_selected_button('zoomout');
        $this->zoomMap($this->user->rolle->nZoomFactor*-1);
      } break;
      case "recentre" : {
        $this->user->rolle->set_selected_button('recentre');
        $this->zoomMap(1);
      } break;
      // case "jump_coords" : {
        // $this->user->rolle->set_selected_button('recentre');
        // $this->zoomMap(1);
      // } break;
      case "pquery" : {
        $this->user->rolle->set_selected_button('pquery');
        $this->queryMap();
      } break;
      case "touchquery" : {
        $this->user->rolle->set_selected_button('touchquery');
        $this->queryMap();
      } break;
      case "ppquery" : {
        $this->user->rolle->set_selected_button('ppquery');
        $this->queryMap();
      } break;
      case "polygonquery" : {
        $this->user->rolle->set_selected_button('polygonquery');
        $this->queryMap();
      } break;
      case "Full_Extent" : {
        $this->user->rolle->set_selected_button('zoomin');   # um anschliessend wieder neu zoomen zu koennen!
        $this->setFullExtent();
      } break;
      default : {
      }
    }
  	if (MAPSERVERVERSION > 600) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function scaleMap($nScale) {
    $oPixelPos = new PointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
		if ($this->user->rolle->epsg_code == 4326) {
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			$nScale = $nScale / degree2meter($center_y);
		}
    $this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
  	if (MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomMap($nZoomFactor){
		# Funktion zum Zoomen über die Navigationswerkzeuge; Koordinaten sind Bildkoordinaten
    $corners=explode(';',$this->formvars['INPUT_COORD']);
    # Auslesen der ersten übergebenen Koordinate
    $lo=explode(',',$corners[0]);
    $minx=$lo[0];
    $maxy=$lo[1];
    # Abfrage, ob eine oder zwei Koordinaten übergeben wurden
    if (count($corners)==1) {
      # es wurde nur ein Punkt übergeben zum zoomen
      #echo '<br>Zoom zum Punkt.';
      $zoom='point';
    }
    else {
      # es wurde ein Rechteck gesetzt zum zoomen
      #echo '<br>Zoom to Rechteck.';
      $ru=explode(',',$corners[1]);
      $miny=$ru[1];
      $maxx=$ru[0];
      if ($minx==$maxx AND $miny==$maxy) {
        # Das Rechteck hat die Kantenlänge 0 deshalb zoom auf Punkt
        $zoom='point';
      }
      else {
        # zoom auf Rechteck wegen Kantenlänge > 0
        $zoom='rectangle';
      }
    }
    if ($zoom=='point') {
      # Zoomen auf einen Punkt
      $this->debug->write('<br>Es wird auf einen Punkt gezoomt',4);
      # Erzeugen eines Punktobjektes
      $oPixelPos = new PointObj();
			$oPixelPos->setXY($minx,$maxy);
			$this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf ein Rechteck gezoomt',4);
      if ($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined') {
				$oPixelExt = rectObj($minx,$miny,$maxx,$maxy, MS_TRUE);
				if (MAPSERVERVERSION >= 800) {
					$this->map->zoomrectangle($oPixelExt, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
				}
				else {
					$this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
					# Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
					# verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
					$oPixelPos = new PointObj();
					$oPixelPos->setXY($this->map->width/2,$this->map->height/2);
					$this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				}
      }
    }
  }

	function zoom2coord() {
		# Funktion zum Zoomen auf eine Punktkoordinate oder einen Kartenausschnitt; Koordinaten sind Weltkoordinaten
		$corners = explode(';', $this->formvars['INPUT_COORD']);
		$lu = explode(',', $corners[0]);
		$minx = (float) $lu[0];
		$miny = (float) $lu[1];
		if (count($corners) == 1) { # Zoom auf Punktkoordinate
			$oPixelPos = new PointObj();
			if ($this->formvars['epsg_code'] != '' AND $this->formvars['epsg_code'] != $this->user->rolle->epsg_code) {
				$oPixelPos->setXY($minx, $miny);
				$projFROM = new projectionobj("init=epsg:" . $this->formvars['epsg_code']);
				$projTO = new projectionobj("init=epsg:" . $this->user->rolle->epsg_code);
				$oPixelPos->project($projFROM, $projTO);
				$minx = $oPixelPos->x;
				$miny = $oPixelPos->y;
			}
			#---------- Punkt-Rollenlayer erzeugen --------#
			if ($this->formvars['name'] != '') {
				$legendentext = $this->formvars['name'];
			}
			else {
				$legendentext = "Koordinate: " . $minx . " " . $miny;
			}
			if (strpos($minx, '°') !== false) {
				$minx = dms2dec($minx);
				$miny = dms2dec($miny);
			}
			$datastring = "the_geom from (select st_geomfromtext('POINT(" . $minx . " " . $miny . ")', " . $this->user->rolle->epsg_code . ") as the_geom, 1 as oid) as foo using unique oid using srid=" . $this->user->rolle->epsg_code;
			$group = $this->mapDB->getGroupbyName('eigene Abfragen');
			if ($group != '') {
				$groupid = $group['id'];
			}
			else {
				$groupid = $this->mapDB->newGroup('eigene Abfragen', 0);
			}
			$this->formvars['user_id'] = $this->user->id;
			$this->formvars['stelle_id'] = $this->Stelle->id;
			$this->formvars['aktivStatus'] = 1;
			$this->formvars['Name'] = $legendentext;
			$this->formvars['Gruppe'] = $groupid;
			$this->formvars['Typ'] = 'search';
			$this->formvars['Datentyp'] = 0;
			$this->formvars['Data'] = $datastring;
			$this->formvars['connectiontype'] = 6;
			$this->formvars['connection_id'] = $this->pgdatabase->connection_id;
			$this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
			$this->formvars['transparency'] = 60;

			$layer_id = $this->mapDB->newRollenLayer($this->formvars);

			$classdata['layer_id'] = -$layer_id;
			$class_id = $this->mapDB->new_Class($classdata);

			if (defined('ZOOM2COORD_STYLE_ID') AND ZOOM2COORD_STYLE_ID != '') {
				$style_id = $this->mapDB->copyStyle(ZOOM2COORD_STYLE_ID);
			}
			else {
				$style['colorred'] = 255;
				$style['colorgreen'] = 255;
				$style['colorblue'] = 128;
				$style['outlinecolorred'] = 0;
				$style['outlinecolorgreen'] = 0;
				$style['outlinecolorblue'] = 0;
				$style['size'] = 10;
				$style['symbolname'] = 'circle';
				$style['minsize'] = NULL;
				$style['maxsize'] = 100000;
				$style['angle'] = 360;
				$style_id = $this->mapDB->new_Style($style);
			}

			$this->mapDB->addStyle2Class($class_id, $style_id, 0); # den Style der Klasse zuordnen
			$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1); # der Rolle die Gruppe zuordnen
			$this->loadMap('DataBase');

			# Bildkoordinaten ausrechnen
			$this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx) / $this->map->width;
			$pixel_x = ($minx-$this->map->extent->minx) / $this->pixwidth;
			$pixel_y = ($this->map->extent->maxy-$miny) / $this->pixwidth;
			$oPixelPos->setXY($pixel_x,$pixel_y);
			if ($this->map->scaledenom > COORD_ZOOM_SCALE) {
				$this->map->zoomscale(COORD_ZOOM_SCALE / 4, $oPixelPos, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
			}
			else {
				$this->map->zoompoint(1, $oPixelPos, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
			}
		}
		else {	# Zoom auf Rechteck
			$ru = explode(',',$corners[1]);
			$maxx=$ru[0];
			$maxy=$ru[1];
			$rect = rectObj($minx,$miny,$maxx,$maxy);
			if($this->formvars['epsg_code'] != '' AND $this->formvars['epsg_code'] != $this->user->rolle->epsg_code){
				$projFROM = new projectionObj("init=epsg:" . $this->formvars['epsg_code']);
				$projTO = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
				$rect->project($projFROM, $projTO);
			}
			$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
			# Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
			# verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
			$oPixelPos = new PointObj();
			$oPixelPos->setXY($this->map->width/2,$this->map->height/2);
			$this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
		}
	}

	function zoom2wkt(){
    $rect = $this->pgdatabase->getWKTBBox($this->formvars['wkt'], $this->formvars['epsg'], $this->user->rolle->epsg_code);
    $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
		if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

	function get_position_qrcode() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		$layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL);
		$query_parts = $mapDB->getQueryParts($layerset[0], []);
		$sql = "
			SELECT
				'geo:' || st_y(position) || ',' || st_x(position) as position
			FROM (
				SELECT 
					st_transform(ST_PointOnSurface(" . $layerset[0]['attributes']['the_geom'] . "), 4326) as position
				FROM 
					(SELECT " . $query_parts['query'] . ") as fooo
				WHERE 
					" . pg_quote($layerset[0]['maintable'] . '_oid') . " = '" . $this->formvars['oid'] . "'
				) foo";
		#echo $sql;
		$ret = $layerdb->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		include_once(CLASSPATH . 'phpqrcode.php');
		QRcode::png($rs['position']);
	}

	function get_web_header_template($title) {
		$html = "<!-- MapServer Template -->
<html lang=\"de\">
  <head>
    <title>" . $title . "</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
  </head>
";
		return $html;
	}

	function get_web_footer_template() {
		$html = "<!-- MapServer Template -->
</html>
";
		return $html;
	}

	function save_web_header_template() {
		$template_dir = WMS_MAPFILE_PATH . 'templates/';
		if (!is_dir($template_dir)) {
			mkdir($template_dir, 0770, true);
		}

		$fp = fopen($template_dir . 'header.html', "w");
		fwrite($fp, $this->get_web_header_template($this->Stelle->ows_title ?: OWS_TITLE));
		fclose($fp);
	}

	function save_web_footer_template() {
		$template_dir = WMS_MAPFILE_PATH . 'templates/';
		if (!is_dir($template_dir)) {
			mkdir($template_dir, 0770, true);
		}

		$fp = fopen($template_dir . 'footer.html', "w");
		fwrite($fp, $this->get_web_footer_template());
		fclose($fp);
	}

  # Speichert die Daten des MapObjetes in Datei oder Datenbank und den Extent in die Rolle
  function saveMap($saveMapDestination) {
		if ($saveMapDestination=='') {
      $saveMapDestination = SAVEMAPFILE;
    }
    if ($saveMapDestination != '') {
      $this->map->save($saveMapDestination);
    }
    $this->user->rolle->saveSettings($this->map->extent);
    $this->user->rolle->readSettings();
  }

	/**
	 * transformiert die gegebenen Koordinaten von wgs in das System der Stelle und speichert den Kartenextent für die Rolle
	 */
	function setMapExtent() {
		$extent = rectObj($this->formvars['left'],$this->formvars['bottom'],$this->formvars['right'],$this->formvars['top']);
		$wgsProjection = new projectionObj("init=epsg:4326");
		$userProjection = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
		$extent->project($wgsProjection, $userProjection);
    $this->user->rolle->saveSettings($extent);
		echo '{
						"minx" : '.$extent->minx.',
						"miny" : '.$extent->miny.',
						"maxx" : '.$extent->maxx.',
						"maxy" : '.$extent->maxy.'
				  }';
	}

	function BBoxinExtent($geom){
    $sql = "SELECT st_geomfromtext('POLYGON((" . $this->map->extent->minx." " . $this->map->extent->miny.", " . $this->map->extent->maxx." " . $this->map->extent->miny.", " . $this->map->extent->maxx." " . $this->map->extent->maxy.", " . $this->map->extent->minx." " . $this->map->extent->maxy.", " . $this->map->extent->minx." " . $this->map->extent->miny."))', " . $this->user->rolle->epsg_code.") && st_transform(" . $geom.", " . $this->user->rolle->epsg_code.")";
    #echo $sql;
    $ret = $this->pgdatabase->execSQL($sql,4, 0);
    if(!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      return $rs[0];
    }
  }

	function check_layer_visibility(&$layer){
		if(value_of($layer, 'status') != '' OR ($this->map_scaledenom < value_of($layer, 'minscale') OR (value_of($layer, 'maxscale') > 0 AND $this->map_scaledenom > value_of($layer, 'maxscale')))) {
			return false;
		}
		return true;
	}

	# Zeichnet die Kartenelemente Hauptkarte, Legende, Maßstab und Referenzkarte
  # drawMap #
  function drawMap($img_urls = false) {
    if($this->main == 'map.php' AND $this->user->rolle->epsg_code != 4326 AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
			$this->saveMap('');
    }
		# Parameter $scale in Data ersetzen
		for($i = 0; $i < count($this->layers_replace_scale); $i++){
			$this->layers_replace_scale[$i]->data = str_replace('$SCALE', $this->map_scaledenom, $this->layers_replace_scale[$i]->data);
		}
		try {
			$f = @fopen(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log', 'r+');
			if ($f !== false) {
					ftruncate($f, 0);
					fclose($f);
			}
    	$this->image_map = $this->map->draw();
			# sorgt dafür, dass die mapserver' . $this->user->id . '.log geschrieben und abgeschlossen wird
			# unter Mapserver 7 wird durch drawmap() kein Fehler geschmissen, deswegen steht diese Zeile hier auch nochmal
			$this->map->setConfigOption('MS_ERRORFILE', LOGPATH . 'mapserver.log');
		}
		catch (Exception $ex) {
			# sorgt dafür, dass die mapserver' . $this->user->id . '.log geschrieben und abgeschlossen wird
			$this->map->setConfigOption('MS_ERRORFILE', LOGPATH . 'mapserver.log');
			global $errors;
			$errors[] = file_get_contents(dirname($this->debug->filename) . '/mapserver' . $this->user->id . '.log');
			throw new Exception('Fehler beim Erzeugen des Kartenbildes');
		}
		#if (!$img_urls) { wegen Bug im Firefox
		if (false) {
			if (MAPSERVERVERSION >= 800) {
				$image = $this->image_map->getBytes();
			}
			else {
				ob_start();
				$this->image_map->saveImage();
				$image = ob_get_clean();
			}
			$this->img['hauptkarte'] = 'data:image/jpg;base64,'.base64_encode($image);
		}
		else {
			$filename = $this->user->id.'_'.rand(0, 1000000).'.'.$this->map->outputformat->extension;
			if (MAPSERVERVERSION >= 800) {
				$this->image_map->save(IMAGEPATH . $filename);
			}
			else {
				$this->image_map->saveImage(IMAGEPATH . $filename);
			}
			$this->img['hauptkarte'] = IMAGEURL . $filename;
		}
		if ($this->formvars['go'] == 'getMap'){
			$this->outputfile = $filename;
			return;
		}
		if ($this->formvars['go'] != 'navMap_ajax'){
			$this->legende = $this->create_dynamic_legend();
			$this->debug->write("Legende erzeugt", 4);
		}
		else {
			# Zusammensetzen eines Layerhiddenstrings, in dem die aktuelle Sichtbarkeit aller aufgeklappten Layer gespeichert ist um damit bei Bedarf die Legende neu zu laden
			for($i = 0; $i < $this->layerset['anzLayer']; $i++) {
				$layer=&$this->layerset['list'][$i];
				if($layer['requires'] == ''){
					if($this->check_layer_visibility($layer))$layerhiddenflag = '0';
					else $layerhiddenflag = '1';
					$this->layerhiddenstring .= $layer['Layer_ID'].' '.$layerhiddenflag.' ';
				}
			}
		}

		# Erstellen des Maßstabes
		$this->map_scaledenom = $this->map->scaledenom;
		if ($this->user->rolle->epsg_code == 4326) {
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			$this->map_scaledenom = degree2meter($center_y) * $this->map_scaledenom;
		}
    $this->switchScaleUnitIfNecessary();
		$this->map->selectOutputFormat('png');
    $img_scalebar = $this->map->drawScaleBar();
		if(!$img_urls){
			if (MAPSERVERVERSION >= 800) {
				$image = $img_scalebar->getBytes();
			}
			else {
				ob_start();
				$img_scalebar->saveImage();
				$image = ob_get_clean();
			}
			$this->img['scalebar'] = 'data:image/png;base64,'.base64_encode($image);
		}
		else{
			$filename = $this->user->id.'_'.rand(0, 1000000).'.png';
			if (MAPSERVERVERSION >= 800) {
				$this->img['scalebar'] = $img_scalebar->save(IMAGEPATH.$filename);
			}
			else {
				$this->img['scalebar'] = $img_scalebar->saveImage(IMAGEPATH.$filename);
			}
			$this->img['scalebar'] = IMAGEURL.$filename;
		}
		$this->calculatePixelSize();
		$this->drawReferenceMap();
  }

  function switchScaleUnitIfNecessary() {
		if ($this->map_scaledenom > $this->scaleUnitSwitchScale) $this->map->scalebar->units = MS_KILOMETERS;
  }

	function calculatePixelSize() {
    $this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
    $this->pixheight = ($this->map->extent->maxy - $this->map->extent->miny)/$this->map->height;
    if ($this->pixwidth>$this->pixheight) {
      $this->pixsize=$this->pixwidth;
    }
    else {
      $this->pixsize=$this->pixheight;
    }
	}

	function map_saveWebImage($image, $format) {
		if(MAPSERVERVERSION >= 800 ) {
			$filename = IMAGEPATH . $this->user->id.'_'.rand(0, 1000000) . '.jpg';
			$image->save($filename);
			return $filename;
		}
		else {
			return $image->saveWebImage();
		}
	}

  function drawReferenceMap(){
    # Erstellen der Referenzkarte
    if($this->reference_map->reference->image != NULL){
			$this->reference_map->setextent($this->map->extent->minx,$this->map->extent->miny,$this->map->extent->maxx,$this->map->extent->maxy);
			if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
				$projFROM = new projectionObj('init=epsg:' . $this->user->rolle->epsg_code);
				$projTO = new projectionObj('init=epsg:' . $this->ref['epsg_code']);
				$this->reference_map->extent->project($projFROM, $projTO);
			}
      $img_refmap = $this->reference_map->drawReferenceMap();
			if (MAPSERVERVERSION >= 800) {
				$image = $img_refmap->getBytes();
			}
			else {
				ob_start();
				$img_refmap->saveImage();
				$image = ob_get_clean();
			}
      $this->img['referenzkarte'] = 'data:image/jpg;base64,'.base64_encode($image);
      $this->Lagebezeichung=$this->getLagebezeichnung($this->user->rolle->epsg_code);
    }
	}

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.rolle::$language;
    $this->Stelle->language=$language;
    include(LAYOUTPATH.'languages/'.$language.'.php');
  }

	function getLagebezeichnung($epsgcode) {
		global $GUI;
		if (defined('LAGEBEZEICHNUNGSART')) {
			switch (LAGEBEZEICHNUNGSART) {
				case 'Flurbezeichnung' : {
					include_once(PLUGINS.'alkis/model/kvwmap.php');
					$Lagebezeichnung = $GUI->getFlurbezeichnung($epsgcode);
				} break;
				default : {
					$Lagebezeichnung = '';
				}
			}
		}
		else {
			$Lagebezeichnung = '';
		}
		return $Lagebezeichnung;
	}

	# extrahiert die Daten aus qlayerset in ein Array
	function qlayersetParamStrip() {
		$result = array();
		# Nehme ersten layerset
		$layerset = $this->qlayerset[0]['shape'];
    if (is_array($layerset)) {
  		# Durchlaufe alle gefundenen Datensätze im Layerset
  		foreach ($layerset AS $record) {
  			$data = array();
  			if ($this->formvars['selectors'] != '') {
  				$selectors = explode(',', $this->formvars['selectors']);
  				foreach ($selectors AS $selector) {
  					$selector = trim($selector);
  					$data[$selector] = $record[strtolower($selector)];
  				}
  			}
  			else {
  				$data = $record;
  			}
  			$result[] = $data;
  		}
    }
#		if (count($result) == 1) $result = $result[0];
		return $result;
	}

	/*
	* This function returns the file that sould be included as gui file in output
	* The gui will be retrieved from $this->gui if exists or
	* otherwise from $this->user->rolle->gui
	*/
	function get_guifile() {
		if ($this->gui != '') {
			return $this->gui;
		}
		# Berücksichtigung des alten gui-Pfads
		$guifile = WWWROOT . APPLVERSION . (strpos($this->user->rolle->gui, 'layouts') === false ? 'layouts/' : '') . $this->user->rolle->gui;
		if (!file_exists($guifile)) {
			$guifile = WWWROOT . APPLVERSION . 'layouts/gui.php';
		}
		return $guifile;
	}

	function notifications_anzeigen() {
		include_once(CLASSPATH . 'Notification.php');
		$this->notifications = Notification::find($this);
		$this->main = 'notifications.php';
		$this->output();
	}

	function notification_formular() {
		include_once(CLASSPATH . 'Notification.php');
		include_once(CLASSPATH . 'Nutzer.php');
		include_once(CLASSPATH . 'User2Notification.php');
		if (value_of($this->formvars, 'id') != '') {
			$this->sanitize(['id' => 'int']);
			$this->notification = Notification::find_by_id($this, $this->formvars['id']);
		}
		else {
			$this->notification = new Notification($this);
		}
		$this->allstellen = Stelle::find($this, 'true', 'Bezeichnung');
		$this->allusers = Nutzer::find($this, 'true', 'Name');

		$this->main = 'notification_formular.php';
		$this->output();
	}

	/**
	 * Create a new user notification or update if an id has been sent
	 * Return only success or fail messages.
	 */
	function put_notification() {
		include_once(CLASSPATH . 'Notification.php');
		include_once(CLASSPATH . 'formatter.php');
		$this->notification = new Notification($this);
		#$this->notification->setKeysFromFormvars($this->formvars);
		$this->notification->data = formvars_strip($this->formvars, $this->notification->getKeys(), 'keep');
		$this->notification->create_stellen_filter();
		$results = $this->notification->validate();
		if (empty($results)) {
			$results = (value_of($this->formvars, 'id') != '' ? $this->notification->update_with_users()[0] : $this->notification->create_with_users()[0]);
		}
		else {
			$results = array(
				'success' => false,
				'validation_errors' => $results
			);
		}
		$formatter = new formatter($results, 'json', 'application/json');
		echo $formatter->output();
	}

	function delete_notification() {
		include_once(CLASSPATH . 'Notification.php');
		include_once(CLASSPATH . 'formatter.php');
		$this->notification = Notification::find_by_id($this, $this->formvars['id']);
		$results = $this->notification->delete()[0];
		#echo '<br>habe notification mit id ' . $this->notification->get('id') . ' gefunden.'; exit;
		$formatter = new formatter($results, 'json', 'application/json');
		echo $formatter->output();
	}

	function delete_user2notification() {
		include_once(CLASSPATH . 'User2Notification.php');
		include(CLASSPATH . 'formatter.php');
		$result = User2Notification::delete_for_user($this, $this->formvars['notification_id'], $this->user->id);
		$formatter = new formatter($result, 'json', 'application/json');
		echo $formatter->output();
	}

	function get_user_notifications() {
		include(CLASSPATH . 'Notification.php');
		include(CLASSPATH . 'formatter.php');
		$result = Notification::find_for_user($this);
		$formatter = new formatter(
			$result,
			'json',
			'application/json'
		);
		echo $formatter->output();
	}

	function add_message($type, $msg) {
		GUI::add_message_($type, $msg);
	}

	public static function add_message_($type, $msg) {
		if (is_array($msg) AND array_key_exists('success', $msg) AND is_array($msg)) {
			$type = 'notice';
			$msg = $msg['msg'];
		}
		if ($type == 'array' or is_array($msg)) {
			foreach($msg AS $m) {
				GUI::add_message_($m['type'], $m['msg']);
			}
		}
		else {
			GUI::$messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function output_messages($option = 'with_script_tags') {
		$html = 'message(' . json_encode(GUI::$messages) . ((property_exists($this, 't_visible') AND $this->t_visible != '') ? ', ' . quote($this->t_visible) : '') . ');';
		if ($option == 'with_script_tags') {
			$html = "<script type=\"text/javascript\">" . $html . "</script>";
		}
		echo $html;
		GUI::$messages = array();
	}

	# Ausgabe der Seite
	function output() {
		global $sizes;
		# bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if (array_key_exists('mime_type', $this->formvars) AND $this->formvars['mime_type'] != '') {
			$this->mime_type = $this->formvars['mime_type'];
		}
		switch ($this->mime_type) {
			case 'printversion' : {
				include (LAYOUTPATH . 'snippets/printversion.php');
			} break;
			case 'html' : {
				if ($this->formvars['window_type'] == 'overlay') {
					$this->overlaymain = $this->main;
					include (LAYOUTPATH . 'snippets/overlay.php');
				}
				else {
					if ($this->formvars['only_main']) {
						include_once(SNIPPETS . $this->main);
					}
					else {
						$guifile = $this->get_guifile();
						$this->debug->write("<br>Include <b>" . $guifile . "</b> in kvwmap.php function output()", 4);
						include($guifile);
					}
				}
				if ($this->alert != '') {
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty(GUI::$messages)) {
					$this->output_messages();
				}
      } break;
			case 'map_ajax' : {
				$this->debug->write("Include <b>".LAYOUTPATH."snippets/map_ajax.php</b> in kvwmap.php function output()",4);
				include (LAYOUTPATH . 'snippets/map_ajax.php');
			} break;
			case 'pdf' : {
				$this->formvars['file'] = 1;
				if ($this->formvars['file']) {
					header("Content-type: application/pdf");
					header("Content-Disposition: attachment; filename=\"" . $this->outputfile."\"");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Pragma: public");
					readfile(IMAGEPATH . $this->outputfile);
				}
				else {
					$this->pdf->ezStream();
				}
			} break;
			case 'image/jpeg' : case 'image/png' : {
				header("Content-type: " . $this->mime_type);
				header("Content-Disposition: attachment; filename=\"" . $this->outputfile."\"");
				header("Cache-Control: max-age=31536000, immutable");
				readfile(IMAGEPATH . $this->outputfile);
			} break;			
			default : {
				if ($this->formvars['format'] != '') {
					include('formatter.php');
					$this->formatter = new formatter(
						($this->formvars['format'] == 'json_result' ? $this->data : $this->qlayersetParamStrip()),
						$this->formvars['format'],
						$this->formvars['content_type'],
						$this->formvars['callback']
					);
					echo utf8_encode($this->formatter->output());
				}
			}
		}
	} # end of function output

	function autocomplete_request() { # layer_id, attribute, inputvalue, field_id
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		$attributenames[0] = $this->formvars['attribute'];
		$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		# value und output ermitteln
		$optionen = explode(';', $attributes['options'][0]);
		$sql = $optionen[0];
		if ($optionen[1] != '') {
			$further_options = explode(' ', $optionen[1]); # die weiteren Optionen exploden (opt1 opt2 opt3)
			for ($k = 0; $k < count($further_options); $k++) {
				if ($further_options[$k] == 'anywhere') { # der eingegebene Text kann überall in den Auswahlmöglichkeiten vorkommen
					$wildcard = '%';
				}
			}
		}
		# setze Order Klausel
		if (strpos(strtolower($sql), 'order by') === false) {
			$orderby = "ORDER BY output";	# nur sortieren, wenn noch nicht sortiert
		}

		# setze Where Ausdruck
		if ($this->formvars['listentyp'] == 'zweispaltig' && strpos(' ', $this->formvars['inputvalue']) !== 0) {
			$parts = explode(' ', $this->formvars['inputvalue']);
			for($p = 0; $p < count($parts); $p++){
				$wheres[] = "lower(split_part(output::text, ' ', ".($p+1).")) LIKE lower('" . $wildcard . $parts[$p] . "%')";
			}
			$where = implode(' AND ', $wheres);
		}
		else {
			$where = "lower(output::text) LIKE lower('" . $wildcard . $this->formvars['inputvalue'] . "%')";
		}

		$sql = "
			SELECT *
			FROM (" . $sql . ") as foo
			WHERE
				" . $where . "
			" . $orderby . "
			LIMIT 15
		";
		#echo $sql;
		$ret = $layerdb->execSQL($sql, 4, 1);
		if ($ret['success']) {
			$count = pg_num_rows($ret[1]);
			if ($count == 1) {
				$rs = pg_fetch_array($ret[1]);
			}
			if ($count == 1 AND strtolower($rs['output']) == strtolower($this->formvars['inputvalue'])) {	# wenn nur ein Treffer gefunden wurde und der dem Eingabewert entspricht
				echo '█document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';';
				echo 'document.getElementById(\''.$this->formvars['field_id'].'\').value=\''.$rs['value'].'\';';
			}
			elseif ($count == 0 ) {		# wenn nichts gefunden wurde
				echo '█document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';';
				echo 'document.getElementById(\''.$this->formvars['field_id'].'\').value = document.getElementById(\''.$this->formvars['field_id'].'\').backup_value;';
				echo 'output = document.getElementById(\'output_'.$this->formvars['field_id'].'\').value;';
				echo 'document.getElementById(\'output_'.$this->formvars['field_id'].'\').value = output.substring(0, output.length-1);';
				echo 'document.getElementById(\'output_'.$this->formvars['field_id'].'\').onkeyup();';
			}
			else{
				if ($count == 1) {
					$count = 2;		# weil ein select-Feld bei size 1 anders funktioniert
				}
				pg_result_seek($ret[1], 0);
				echo'<select size="'.$count.'" class="suggests" 
					onmousedown="this.dataset.clicked=true;" 
					onchange="
						document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';
						document.getElementById(\''.$this->formvars['field_id'].'\').value=this.value;
						document.getElementById(\''.$this->formvars['field_id'].'\').onchange();
						document.getElementById(\'output_'.$this->formvars['field_id'].'\').value=this.options[this.selectedIndex].text;
						document.getElementById(\'output_'.$this->formvars['field_id'].'\').onchange();">';
				while($rs=pg_fetch_array($ret[1])) {
					echo '<option onmouseover="this.selected = true;" onclick="this.parentElement.onchange();" value="'.$rs['value'].'">'.$rs['output'].'</option>';
				}
				echo '</select>
				█document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'block\'; document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').scrollIntoViewIfNeeded({block: "end", behavior: "smooth"});';
			}
		}
		else {
			$this->add_message('error', $ret[1]);
			echo '█document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';';
		}
	}

	function go_switch_plugins($go) {
		global $kvwmap_plugins;
		// wenn es keine Plugins gibt, ist diese Var. immer true
		$this->goNotExecutedInPlugins = true;
		for ($i = 0; $i < count($kvwmap_plugins); $i++) {
			if ($this->goNotExecutedInPlugins == true) {
				$this->goNotExecutedInPlugins = false;
				$go_switch_plugin = 'go_switch_' . $kvwmap_plugins[$i];
				$go_switch_plugin($go);
			}
		}
	}

	function plugin_loaded($plugin) {
		global $kvwmap_plugins;
		return in_array($plugin, $kvwmap_plugins);
	}

	/*
	* Function return true if the case is allowed to execute in this stelle and for this user
	* First it will test the permission in relation of the menues the user have in this stelle
	* Second it test if the case is extra allowed as a function in this stelle
	* Third it test if the user have special permissions to execute this case
	*/
	function checkCaseAllowed($case) {
		$this->check_csrf_token();
		if (!(
			$this->Stelle->isMenueAllowed($case) OR
			$this->Stelle->isFunctionAllowed($case) OR
			$this->user->is_case_allowed($case)
		)) {
			$this->add_message('error', $this->TaskChangeWarning . '<br>(' . $case . ')' . '<br>Weder Menü noch Funktion erlaubt.');
			global $log_loginfail;
			$log_loginfail->write(date("Y:m:d H:i:s", time()) . ' case: ' . $case . ' not allowed in Stelle: ' . $this->Stelle->id . ' for User: ' . $this->user->Name);
			$this->goNotExecutedInPlugins = true;
			go_switch('', true);
		}
	}

	/**
	 * This functino check if the $constraint to allow a tool script is fullfilled or not
	 * If the constraint does not fit it or is unknown to the cases in the function it returns false. 
	 * @param string $constraint. Possible values are only_cli, only_web
	 * @return Boolean true = allowed, false = not allowed
	 */
	function is_tool_allowed($constraint) {
		$http_response_code = http_response_code();
		switch ($constraint) {
			case 'only_cli' : {
				return $http_response_code === false;
			} break;
			case 'only_web' : {
				return $http_response_code !== false;
			} break;
			default : {
				return false;
			}
		}
	}

	/**
	 * Function check the csrf_token if the user has not logged in with this request
	 * HTTP 405 Error is sent if csrf_token is not received or does not match the one in the session.
	 */
	function check_csrf_token() {
		#$csrf_token = filter_input(INPUT_GET, 'csrf_token', FILTER_SANITIZE_STRING);
		$csrf_token = $this->formvars['csrf_token'];
		#echo '<br>csrf_token: ' . $csrf_token;
		if (
			!$this->user->has_logged_in AND (
				!$csrf_token OR
				(
					$csrf_token !== $_SESSION['csrf_token'] AND 
					!in_array($csrf_token, explode(',', $this->user->tokens))
				)
			)
		) {
			# return 405 http status code
			header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
			echo $this->strSecurityReason . '<br><br>=> <a href="' . URL . (substr(URL, -1) != '/' ? '/' : '') . APPLVERSION . 'index.php">Fortfahren</a>';
			exit;
		}
	}

	/*
	* Erzeugt eine Fehlermeldung in GUI::$messages und wechselt zum default use case,
	* wenn der Bearbeiter $editor_user nicht zu einer Admin-Stelle gehört aber
	* der $selected_user zu einer Admin-Stelle gehört
	*/
	function als_nutzer_anmelden_allowed($selected_user, $editor_user) {
		if (
			!$this->is_admin_user($editor_user) AND
			$this->is_admin_user($selected_user)
		) {
			$this->add_message('error', 'Sie durfen sich nicht als Nutzer mit der ID: ' . $selected_user . ' anmelden, weil er Admin-Rechte hat.');
			go_switch('', true);
		}
	}

	/*
	* Erzeugt eine Fehlermeldung in GUI::$messages und wechselt zum default use case,
	* wenn der Bearbeiter $editor_user nicht zu einer Admin-Stelle gehört und
	* wenn die $selected_stelle nicht zu den Stellen zählt, die der $editor_user sehen kann.
	*/
	function stelle_bearbeiten_allowed($selected_stelle, $editor_user) {
		if (!$this->is_admin_user($editor_user)) {
			$editor_stellen = $this->Stelle->getStellen('ID', $editor_user);
			if (!in_array($selected_stelle, $editor_stellen['ID'])) {
				$this->add_message('error', 'Sie sind nicht berechtigt die Stelle ' . $selected_stelle . ' zu bearbeiten!');
				go_switch('', true);
			}
		}
	}

	/*
	* Erzeugt eine Fehlermeldung in GUI::$messages und wechselt zum default use case,
	* wenn der Bearbeiter $editor_user nicht zu einer Admin-Stelle gehört und
	* wenn der $selected_user erstens nicht zu den Nutzern zählt, die der $editor_user sehen kann ($editor_users) oder
	* zweitens wenn der $selected_user noch zu anderen Stellen gehört, die der $bearbeiter_user nicht sehen kann.
	* Der $edit_user darf also nur Nutzer bearbeiten wenn er entweder admin ist oder es sich um Nutzer
	* handelt, die ausschließlich in seinen sichtbaren Stellen sind.
	*/
	function user_bearbeiten_allowed($selected_user, $editor_user) {
		if (!$this->is_admin_user($editor_user)) {
			# Fragt user ab, die Bearbeiter sehen kann
			$editor_users = $this->user->getall_Users('Name', $selected_user, $editor_user);
			# Fragt stellen ab, die Bearbeiter sehen kann
			$editor_stellen = $this->Stelle->getStellen('ID', $editor_user);
			if (
				!in_array($selected_user, $editor_users['ID']) OR
				$this->user_is_in_fremden_stellen($selected_user, $editor_stellen['ID'])
			) {
				$this->add_message('error', 'Sie sind nicht berechtigt den Nutzer ' . $selected_user . ' zu bearbeiten!');
				go_switch('', true);
			}
		}
	}

	/*
	* Liefert true wenn der Nutzer mit $user_id zu einer Stelle gehört, die in $admin_stellen
	* registriert ist, also wenn der Nutzer Administrator ist.
	* Liefert ansonsten false und setzt wenn es einen Fehler bei der Abfrage gab,
	* zusätzlich eine Fehlermeldung in GUI::$messages
	*/
	function is_admin_user($user_id) {
		global $admin_stellen;
		$result = false;

		$sql = "
			SELECT DISTINCT
				r.user_id
			FROM
				rolle r JOIN
				user u ON r.user_id = u.ID
			WHERE
				r.stelle_id IN (" . implode(', ', $admin_stellen) . ") AND
				u.id = '" . $this->database->mysqli->real_escape_string($user_id) . "'
		";
		#echo '<br>sql: ' . $sql;

		$ret = $this->database->execSQL($sql, 0, 0);
		if ($ret['success']) {
			if ($this->database->result->num_rows == 1) {
				$result = true;
			}
		}
		else {
			$this->add_message('error', 'Fehler beim Abfragen ob der Nutzer mit der ID: ' . $user_id . ' ein Administrator ist.');
		}
		return $result;
	}

	function user_is_in_fremden_stellen($user_id, $stellen) {
		$ret = false;

		$sql = "
			SELECT
				stelle_id
			FROM
				rolle
			WHERE
				user_id = " . $user_id . " AND
				stelle_id NOT IN (" . implode(', ', $stellen) . ")
		";
		#echo '<br>sql: ' . $sql;

		$result = $this->database->execSQL($sql, 0, 0);
		if ($result['success']) {
			if ($this->database->result->num_rows > 0) {
				$ret = true;
				$this->add_message('error', 'Nutzer mit der ID: ' . $user_id . ' gehört noch zu anderen Stellen und kann daher nur in Adminstellen bearbeitet werden!');
			}
		}
		else {
			$ret = true; # wegen Fehler
			$this->add_message('error', 'Fehler beim Abfragen der Stellenzugehörigkeit des Nutzers mit der id: ' . $user_id);
		}
		return $ret;
	}

	function getSVG_all_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien von allen aktiven Postgis-Layern, die im aktuellen Kartenausschnitt liegen
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayer = true;
		$mapDB->OhneRequires = true;
		$layerset = $mapDB->read_Layer(0);     # 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
		$rollenlayer = $mapDB->read_RollenLayer();
    $layer = array_merge($layerset['list'], $rollenlayer);
		$anzLayer = count($layer);
		for($i = 0; $i < $anzLayer; $i++){
			if($layer[$i]['connectiontype'] == MS_POSTGIS AND in_array($layer[$i]['Datentyp'], array(MS_LAYER_POINT, MS_LAYER_LINE, MS_LAYER_POLYGON))){
				if($this->formvars['scale'] != '' AND ($this->formvars['scale'] < $layer[$i]['minscale'] OR $layer[$i]['maxscale'] > 0 AND $this->formvars['scale'] > $layer[$i]['maxscale'])){
        	continue;
      	}
				$layerdb = $mapDB->getlayerdatabase($layer[$i]['Layer_ID'], $this->Stelle->pgdbhost);
				$select = $mapDB->getSelectFromData($layer[$i]['Data']);
				$data_attributes = $mapDB->getDataAttributes($layerdb, $layer[$i]['Layer_ID']);
				$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer[$i]['epsg_code'].')';
				$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects('.$data_attributes['the_geom'].', '.$extent.')';
				# Filter hinzufügen
				if($layer[$i]['Filter'] != ''){
					$layer[$i]['Filter'] = str_replace('$USER_ID', $this->user->id, $layer[$i]['Filter']);
          $fromwhere .= " AND " . $layer[$i]['Filter'];
        }
				if($data_attributes['the_geom'] != ''){
					$sql = '
						SELECT st_x((dump).geom), st_y((dump).geom)
						FROM (
							SELECT st_dumppoints(intersection) AS dump
							FROM (
								select st_transform(st_intersection('.$data_attributes['the_geom'].', '.$extent.'), '.$this->user->rolle->epsg_code.') as intersection
									'.$fromwhere.'
							) foo1
						) foo2
						LIMIT 10000';
					#echo $sql;
					$ret=$layerdb->execSQL($sql,4, 0);
					if(!$ret[0]){
						while ($rs=pg_fetch_array($ret[1])){
							echo $rs[0].' '.$rs[1].'|';
						}
					}
				}
			}
		}
		echo '█show_vertices();';
	}

	function getSVG_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien des übergebenen Vektor-Layers, die im aktuellen Kartenausschnitt liegen
		if($this->formvars['geom_from_layer'] == 0){		# wenn kein Layer ausgewählt ==> alle aktiven abfragen
			$this->getSVG_all_vertices();
			return;
		}
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($this->formvars['geom_from_layer'] > 0){
			$layer = $this->user->rolle->getLayer($this->formvars['geom_from_layer']);
			$layer = $layer[0];
		}
		else{
			$rollenlayer = $mapDB->read_RollenLayer(-$this->formvars['geom_from_layer'], NULL);
			$layer = $rollenlayer[0];
		}
		$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer['epsg_code'].')';
		if($layer['connectiontype'] == MS_POSTGIS){
			$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
    	$data_attributes = $mapDB->getDataAttributes($layerdb, $layer['Layer_ID']);
			$geom = $data_attributes['the_geom'];
    	$select = $mapDB->getSelectFromData($layer['Data']);
			$select = preg_replace ("/ FROM /", ' from ', $select);
			$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects('.$geom.', '.$extent.') ';
			# Filter hinzufügen
			if($layer['Filter'] != ''){
				$layer['Filter'] = str_replace('$USER_ID', $this->user->id, $layer['Filter']);
				$fromwhere .= " AND " . $layer['Filter'];
			}
		}
		elseif($layer['connectiontype'] == MS_WFS){
			include_(CLASSPATH.'spatial_processor.php');
			$projFROM = new projectionObj("init=epsg:".$this->user->rolle->epsg_code);
			$projTO = new projectionObj("init=epsg:".$layer['epsg_code']);
			$rect = $this->user->rolle->oGeorefExt;
			$rect->project($projFROM, $projTO);
			$request = $layer['connection'].'&service=wfs&version=1.1.0&request=getfeature&srsName=EPSG:'.$layer['epsg_code'].'&typename='.$layer['wms_name'].'&bbox='.$rect->minx.','.$rect->miny.','.$rect->maxx.','.$rect->maxy;
			$request .= ',EPSG:'.$layer['epsg_code'];
			$this->debug->write("<br>WFS-Request: ".$request,4);
			$gml = url_get_contents($request, $layer['wms_auth_username'], $layer['wms_auth_password']);
			#$this->debug->write("<br>WFS-Response: ".$gml,4);
			$spatial_processor = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
			switch($layer['Datentyp']){
				case MS_LAYER_POLYGON : {
					$wkt = $spatial_processor->composePolygonArrayWKTStringFromGML($gml, $layer['wfs_geom'], $layer['epsg_code']);
				}break;

				case MS_LAYER_POINT : {
					$wkt = $spatial_processor->composeMultipointWKTStringFromGML($gml, $layer['wfs_geom']);
				}break;
			}
			#$this->debug->write("<br>WKT von GML-Geometrie: ".$wkt,4);
			$fromwhere = "from (select unnest(".$wkt.") as geom) as foo";
			$geom = 'geom';
			$layerdb = $this->pgdatabase;
		}
		$sql = '
			SELECT st_x((dump).geom), st_y((dump).geom)
			FROM (
				SELECT st_dumppoints(intersection) AS dump
				FROM (
					select 	st_transform(st_intersection('.$geom.', '.$extent.'), '.$this->user->rolle->epsg_code.') as intersection
						'.$fromwhere.'
				) foo1
			) foo2
			LIMIT 10000';
		#echo '<p>SQL zur Abfrage von Vertexes: '. $sql;
		$ret=$layerdb->execSQL($sql,4, 0);
		if(!$ret[0]){
			while ($rs=pg_fetch_array($ret[1])){
				echo $rs[0].' '.$rs[1].'|';
			}
			echo '█show_vertices();';
		}
	}

	function getRoute($formvars) {
		include_(CLASSPATH.'Routing.php');
		$routing = new routing($this->user->rolle, $this->database, $this->pgdatabase);
		echo $routing->getRoute($formvars);
	}

	function reset_layers($layer_id){
		$this->user->rolle->resetLayers($layer_id);
		$this->user->rolle->resetQuerys($layer_id);
	}

	function reset_querys(){
		$this->user->rolle->resetQuerys('');
	}

	function resizeMap2Window() {
		global $sizes;
		$size = $sizes[$this->user->rolle->gui];
		$gui_light = ($this->user->rolle->gui == 'layouts/gui_light.php');

		if (array_key_exists('legenddisplay', $this->formvars) AND $this->formvars['legenddisplay'] !== NULL) {
			$hideLegend = $this->formvars['legenddisplay'];		// falls die Legende gerade ein/ausgeblendet wurde
		}
		else {
			$hideLegend = $this->user->rolle->hideLegend;
		}

		$width = $this->formvars['browserwidth'] -
			$size['margin']['width']
			- ($this->user->rolle->hideMenue == 1 ? $size['menue']['hide_width'] : $size['menue']['width'])
			- ($gui_light? 0 : ($hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width']))
			- ($gui_light ? 0 : 18);	# Breite für möglichen Scrollbalken

		$height = $this->formvars['browserheight'] -
			$size['margin']['height'] -
			$size['header']['height'] -
			$size['scale_bar']['height'] -
			((defined('LAGEBEZEICHNUNGSART') AND LAGEBEZEICHNUNGSART != '') ? $size['lagebezeichnung_bar']['height'] : 0) -
			($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0) -
			$size['footer']['height'];

		if($width  < 0) $width = 1000;
		if($height < 0) $height = 800;
		if($height % 2 != 0)$height = $height - 1;		# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel
		if($width  % 2 != 0)$width = $width - 1;				# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel

		// $this->debug->write('<br>resizeMap2Window for gui: ' . $this->user->rolle->gui . ' to: ' . $width . 'x' . $height, 4, false);
		$this->user->rolle->setSize($width.'x'.$height);
		$this->user->rolle->readSettings();
	}

	function split_multi_geometries(){
		include_(CLASSPATH . 'spatial_processor.php');
		include_(CLASSPATH . 'PgObject.php');
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$spatial_processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
		$single_geoms = $spatial_processor->split_multi_geometries($this->formvars['newpathwkt'], $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $this->attributes['geomtype'][$this->attributes['the_geom']]);
		$this->copy_dataset($mapdb, $this->formvars['selected_layer_id'], array($layerset[0]['oid']), array($this->formvars['oid']), count($single_geoms), array($this->formvars['layer_columnname']), $single_geoms, true);
		$this->loadMap('DataBase');					# Karte anzeigen
		// $currenttime=date('Y-m-d H:i:s',time());
    // $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    // $this->drawMap();
    // $this->saveMap('');
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	function dublicate_dataset() {
		include_(CLASSPATH . 'PgObject.php');
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		$checkbox_names = explode('|', $this->formvars['checkbox_names_' . $this->formvars['chosen_layer_id']]);
		for ($i = 0; $i < count($checkbox_names); $i++) {
			if ($this->formvars[$checkbox_names[$i]] == 'on') {
				$element = explode(';', $checkbox_names[$i]); #  check;table_alias;table;oid
				$oid = $element[3];
				break;
			}
		}
		if ($new_oids = $this->copy_dataset($mapdb, $this->formvars['chosen_layer_id'], array($layerset[0]['oid']), array($oid), 1)) {
			$this->add_message('notice', 'Der Datensatz wurde kopiert.');
			$this->formvars['value_'.$layerset[0]['maintable'].'_oid'] = $new_oids[0];
			$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
			$this->GenerischeSuche_Suchen();
		}
		else {
			$this->add_message('error', 'Kopiervorgang fehlgeschlagen.');
			$this->last_query = $this->user->rolle->get_last_query();
			if ($this->formvars['search']) {
				# man kam von der Suche -> nochmal suchen
				$this->GenerischeSuche_Suchen();
			}
			else {
				# man kam aus einer Sachdatenabfrage -> nochmal abfragen
				$this->last_query_requested = true;
				$this->queryMap();
			}
		}
	}

	function copy_dataset($mapdb, $layer_id, $id_names, $id_values, $count, $update_columns = array(), $update_values = NULL, $delete_original = false){
		# Diese Funktion kopiert einen über die Arrays $id_names und $id_values bestimmten Datensatz in einem Layer $count-mal.
		# $id_names sind die Namen und $id_values die Werte der Attribute, die den Datensatz identifizieren, also in der WHERE-Bedingung verwendet werden.
		# Jeder neu entstandene Datensatz kann sich in den Attributen, die über das Array $update_columns definiert werden von den anderen unterscheiden.
		# D.h. der Datensatz wird $count-mal kopiert und alle Attribute in $update_columns auf den entsprechenden Wert im Array $update_values gesetzt.
		# Wenn $delete_original=true ist, wird der Original-Datensatz nach dem Kopieren gelöscht.
		# Um auch die über SubformPK- oder embeddedPK verknüpften Datensätz zu kopieren, wird die Funktion rekursiv auf diese Datensätze angewendet.
		# In diesem Fall wird durch die Arrays $id_names und $id_values nicht nur genau ein Datensatz bestimmt, sondern mehrere.

		$layerset = $this->user->rolle->getLayer($layer_id);
		$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$layerattributes = $mapdb->read_layer_attributes($layer_id, $layerdb, NULL);

		# Query table constraints
		$table = new PgObject($this, $layerdb->schema, $layerset[0]['maintable']);
		$table->database = $layerdb;
		$table->get_constraints();

		# Attribute, die kopiert werden sollen ermitteln
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_name = '" . $layerset[0]['maintable'] . "' AND
				table_schema = '" . $layerdb->schema . "'
		";
		#echo '<p>SQL zur Abfrage der Attribute, die kopiert werden sollen: ' . $sql;
		$ret = $layerdb->execSQL($sql,4, 0);
		if (!$ret['success']) {
			return array();
		}
		while ($rs = pg_fetch_assoc($ret[1])) {
			# ignoriert das Attribut wenn
			# es in $update_colums ist oder wenn
			# es zu pkey gehärt und $update_columns nicht Bestandteil des pkeys ist
			if (
				!(
						in_array($rs['column_name'], $update_columns) OR
						$rs['column_name'] == $layerset[0]['oid'] OR
						(
							$table->is_part_of_primary_keys($rs['column_name']) AND
							!$table->has_other_constraint_column($rs['column_name'], $update_columns)
						)
					)
			) {
				$attributes[] = $rs['column_name'];
			}

			# Dokument-Attribute sammeln
			if ($layerattributes['form_element_type'][$rs['column_name']] == 'Dokument') {
				$document_attributes[] = $rs['column_name'];
			}
		}

		for ($n = 0; $n < count($id_names); $n++) {
			$where[] = $id_names[$n] . " = '" . $id_values[$n] . "'";
		}

		# Dokument-Pfade abfragen
		if (count_or_0($document_attributes) > 0) {
			$sql = "
				SELECT
					" . implode(',', $document_attributes) . "
				FROM
					" . pg_quote($layerset[0]['maintable']) . "
				WHERE
					" . implode(' AND ', $where) . "
			";
			#echo '<p>SQL zur Abfrage der Dokument-Pfade: ' . $sql;
			$ret = $layerdb->execSQL($sql,4, 0);
			$dokument_paths = array();
			if (!$ret['success']) {
				return array();
			}

			while($rs=pg_fetch_row($ret[1])) { # dieser Schleifendurchlauf entspricht den original Datensätzen, die kopiert werden sollen
				$orig_dataset[]['document_paths'] = $rs;		# jeder dieser Datensätze hat ein Array mit den Dokument-Pfaden der Dokument-Attribute
			}
		}

		# Erzeugen der neuen Datensätze
		for ($i = 0; $i < $count; $i++) { # das ist die Schleife, wie oft insgesamt kopiert werden soll
			# zunächst als reine Kopie
			if (count($update_columns) > 0) {
				$insert_columns = array_merge($attributes, $update_columns);
				$select_columns = array_merge(
					$attributes,
					array_map(
						function($values) {
							return "'" . $values . "'";
						},
						$update_values[$i]
					)
				);
			}
			else {
				$insert_columns = $select_columns = $attributes;
			}
			array_walk($insert_columns, function(&$attributename, $key){$attributename = pg_quote($attributename);});
			array_walk($select_columns, function(&$attributename, $key){$attributename = pg_quote($attributename);});
			$sql = "
				INSERT INTO
					" . pg_quote($layerset[0]['maintable']) . " (" . implode(', ', $insert_columns) . ")
				SELECT
					" . implode(', ', $select_columns) . "
				FROM
					" . pg_quote($layerset[0]['maintable']) . "
				WHERE
					" . implode(' AND ', $where) . "
				RETURNING
					" . $layerset[0]['oid'];
			#echo '<p>SQL zum kopieren eines Datensatzes: ' . $sql;
			$ret = $layerdb->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				return array();
			}

			$new_oids = array();
			$d = 0; # Zähler der kopierten Datensätze pro Kopiervorgang
			while ($rs = pg_fetch_row($ret[1])) { # das ist die Schleife der kopierten Datensätze pro Kopiervorgang
				$new_oids[] = $rs[0];
				$all_new_oids[] = $rs[0];
				# Dokumente kopieren
				for ($p = 0; $p < count_or_0($orig_dataset[$d]['document_paths']); $p++) { # diese Schleife durchläuft alle Dokument-Attribute innerhalb eines kopierten Datensatzes
					if ($orig_dataset[$d]['document_paths'][$p] != '') {
						$complete_new_path = preg_replace_callback(
							'|/[^&]+|',
							function ($orig_path) {
								$name_parts = explode('.', $orig_path[0]);		# Dateiendung ermitteln
								$new_file_name = date('Y-m-d_H_i_s',time()).'-'.rand(100000, 999999).'.'.$name_parts[1];;
								$new_path = dirname($orig_path[0]).'/'.$new_file_name;
								copy($orig_path[0], $new_path);
								return $new_path;
							},
							$orig_dataset[$d]['document_paths'][$p]
						);

						$sql = "
							UPDATE " . pg_quote($layerset[0]['maintable']) . "
							SET " . $document_attributes[$p] . " = '" . $complete_new_path . "'
							WHERE ".$layerset[0]['oid']." = " . quote($rs[0]) . "
						";
						#echo '<p>SQL zum Update der Dokumentattribute: ' . $sql;
						$ret1 = $layerdb->execSQL($sql,4, 0);
					}
				}
				$d++;
			}
			# dann die Attribute updaten, die sich unterscheiden sollen
			if ($new_oids[0] != '') {
				for ($u = 0; $u < count($update_columns); $u++) {
					$sql = "
						UPDATE " . pg_quote($layerset[0]['maintable']) . "
						SET " . $update_columns[$u] . " = '" . $update_values[$i][$u] . "'
						WHERE ".$layerset[0]['oid']." IN ('" . implode("', '", $new_oids) . "')
					";
					#echo '<p>SQL zum Update der Attribute, die sich unterscheiden sollen: ' . $sql;
					$ret = $layerdb->execSQL($sql,4, 0);
				}
			}
		}

		if ($all_new_oids[0] != '') {
			# über SubFormEmbeddedPK oder SubFormPK verknüpfte Datensätze auch rekursiv kopieren
			for($l = 0; $l < count($layerattributes['name']); $l++){
	    	if(in_array($layerattributes['form_element_type'][$l], array('SubFormEmbeddedPK', 'SubFormPK'))){
	    		$subform_pks_realnames = array();
	    		$subform_pks_realnames2 = array();
					$pkvalues = array();
	    		$next_update_values = array();;
					$options = explode(';', $layerattributes['options'][$l]);
	        $subform = explode(',', $options[0]);
	        $subform_layerid = $subform[0];
					if($subform_layerid != $layer_id){			# Subforms auf den selben Layer werden ignoriert
						$subformlayerdb = $mapdb->getlayerdatabase($subform_layerid, $this->Stelle->pgdbhost);
						$subformlayerattributes = $mapdb->read_layer_attributes($subform_layerid, $subformlayerdb, NULL);
						if($layerattributes['form_element_type'][$l] == 'SubFormEmbeddedPK')$minus = 1;
						else $minus = 0;
						for($k = 1; $k < count($subform)-$minus; $k++){
							$subform_pks_realnames[] = $layerattributes['real_name'][$subform[$k]];											# das sind die richtigen Namen der SubformPK-Schlüssel in der übergeordneten Tabelle
							$subform_pks_realnames2[] = $subformlayerattributes['real_name'][$subform[$k]];							# das sind die richtigen Namen der SubformPK-Schlüssel in der untergeordneten Tabelle
						}

						$subformpk_where = array();
						for ($n = 0; $n < count($id_names); $n++) {
							$subformpk_where[] = $id_names[$n] . " = '" . $id_values[$n] . "'";
						}
						$sql = "
							SELECT
								" . implode(',', $subform_pks_realnames) . "
							FROM
								" . pg_quote($layerset[0]['maintable']) . "
							" . (count($subformpk_where) > 0 ? ' WHERE ' . implode(' AND ', $subformpk_where) : '') . "
						";
						#echo '<p>SQL zur Abfrage der Werte der SubformPK-Schlüssel aus dem alten Datensatz: ' . $sql;
						$ret = $layerdb->execSQL($sql,4, 0);
						if(!$ret[0]){
							$pkvalues=pg_fetch_row($ret[1]);
						}

						$sql = "
							SELECT
								" . implode(',', $subform_pks_realnames) . "
							FROM
								" . pg_quote($layerset[0]['maintable']) . "
							WHERE
								" . $layerset[0]['oid'] . " IN (" . implode(',', $all_new_oids) . ")
						";
						#echo '<p>SQL zur Abfrage der SubformPK-Schlüssel aus den neuen Datensätzen: ' . $sql;
						$ret = $layerdb->execSQL($sql,4, 0);
						if(!$ret[0]){
							while ($rs=pg_fetch_row($ret[1])){
								$next_update_values[] = $rs;
							}
						}
						$this->copy_dataset($mapdb, $subform_layerid, $subform_pks_realnames2, $pkvalues, count($next_update_values), $subform_pks_realnames2, $next_update_values, $delete_original);
					}
				}
			}

			# Original löschen
			if ($delete_original) {
				$sql = "
					DELETE FROM " . pg_quote($layerset[0]['maintable']) . "
					WHERE " . implode(' AND ', $where) . "
				";
				#echo $sql.'<br>';
				$ret = $layerdb->execSQL($sql,4, 0);
			}
			return $all_new_oids;
		}
	}

  function import_layer(){
    if($this->formvars['neuladen']){
      $this->neuLaden();
    }
    else{
      $this->formvars['nurFremdeLayer'] = true;
      $this->loadMap('DataBase');
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Client-Daten einlesen';
    $this->main='import_layer_form.php';
    $this->output();
  }

  function import_layer_importieren() {
		include_(CLASSPATH . 'synchronisation.php');
    $this->loadMap('DataBase');
    $this->synchro = new synchro($this->Stelle, $this->user, $this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('');
    for($i = 0; $i < count($layerset); $i++){
      if($this->formvars['thema'.$layerset[$i]['Layer_ID']]==1 AND $layerset[$i]['connectiontype'] == 6){
        $import_layerset[] = $layerset[$i];
      }
    }
    $this->synchro->import_layer_tables($import_layerset, $this->formvars);
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Layer-Import';
    $this->main='import_layer_form.php';
    $this->output();
  }

  function export_layer(){
    if($this->formvars['neuladen']){
      $this->neuLaden();
    }
    else{
      $this->formvars['nurFremdeLayer'] = true;
      $this->loadMap('DataBase');
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Primär-Daten einlesen';
    $this->main='export_layer_form.php';
    $this->output();
  }

  function export_layer_exportieren() {
		include_(CLASSPATH . 'synchronisation.php');
    $this->loadMap('DataBase');
    $this->synchro = new synchro($this->Stelle, $this->user, $this->pgdatabase);
    $layerset = $this->user->rolle->getLayer('');
    for($i = 0; $i < count($layerset); $i++){
      if($this->formvars['thema'.$layerset[$i]['Layer_ID']]==1 AND $layerset[$i]['connectiontype'] == 6){
        $export_layerset[] = $layerset[$i];
      }
    }
    $this->synchro->export_layer_tables($export_layerset, $this->formvars);
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->titel='Layer-Export';
    $this->main='export_layer_form.php';
    $this->output();
  }

  function get_select_list() {
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $attributenames[0] = $this->formvars['attribute'];
		if ($this->formvars['datatype_id'] != '') {
			$attributes = $mapDB->read_datatype_attributes($this->formvars['layer_id'], $this->formvars['datatype_id'], $layerdb, $attributenames);
		}
    else {
			$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		}
		$options = array_shift(explode(';', $attributes['options'][$this->formvars['attribute']]));
    $reqby_start = strpos(strtolower($options), "<required by>");
    if ($reqby_start > 0) {
			$sql = substr($options, 0, $reqby_start);
		}
		else {
			$sql = $options;
		}
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		$sql = str_replace('=<requires>', '= <requires>', $sql);
		for ($i = 0; $i < count($attributenames); $i++) {
			$value = ($attributevalues[$i] != '' ? "'" . $attributevalues[$i] . "'" : 'NULL');
			$sql = str_replace('= <requires>' . $attributenames[$i] . '</requires>', " IN (" . $value . ")", $sql);
			$sql = str_replace('<requires>' . $attributenames[$i] . '</requires>', $value, $sql);	# fallback
		}
		#echo $sql;
		@$ret = $layerdb->execSQL($sql, 4, 0);
		if (!$ret[0]) {
			switch($this->formvars['type']) {
				case 'select-one' : {					# ein Auswahlfeld soll mit den Optionen aufgefüllt werden 
					$html = '>';			# Workaround für dummen IE Bug
					if (pg_num_rows($ret[1]) > 1 OR $reqby_start > 0 OR $this->formvars['auswahl'] == 1) {
						$html .= '<option value="">-- Bitte Auswählen --</option>';
					}
					while($rs = pg_fetch_array($ret[1])){
						$html .= '<option value="'.$rs['value'].'">'.$rs['output'].'</option>';
					}
				}break;
				
				case 'text' : {								#  ein Textfeld soll nur mit dem ersten Wert aufgefüllt werden
					$rs = pg_fetch_array($ret[1]);
					$html = $rs['output'];
				}break;
				
				case 'hidden' : {					# ein Bild-Auswahlfeld soll mit den Optionen aufgefüllt werden 
					while($rs = pg_fetch_array($ret[1])){
						$html .= '						
						<li class="item" data-value="' . $rs['value'] . '" onclick="image_select(this);">
							<img src="data:image/jpg;base64,' . base64_encode(@file_get_contents($rs['image'])) . '">
							<span>' . $rs['output'] . '</span>
						</li>';
					}
				}break;
			}
		}
		echo $html;
  }

	function auto_generate(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $attributenames[0] = $this->formvars['attribute'];
    $attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		$sql = $attributes['options'][0];
		for($i = 0; $i < count($attributenames); $i++){
			if($attributenames[$i] != '')$sql = str_replace('$'.$attributenames[$i], $attributevalues[$i], $sql);
		}
		#echo $sql;
		$ret=$layerdb->execSQL($sql,4,0);
    if ($ret[0]) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
		$rs = pg_fetch_array($ret[1]);
		echo $rs[0];
  }

	function openCustomSubform() {
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		$attributenames[0] = $this->formvars['attribute'];
		$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		$url = $attributes['options'][0];
		for($i = 0; $i < count($attributenames); $i++){
			$url = str_replace('$'.$attributenames[$i], $attributevalues[$i], $url);
		}
		echo $url . '&field_id='.$this->formvars['field_id'];
	}
	function showMapImage() {
		include(LAYOUTPATH . 'languages/mapdiv_' . rolle::$language . '.php');
  	$this->loadMap('DataBase');
  	$this->drawMap(true);
  	$randomnumber = rand(0, 1000000);
  	$svgfile  = $randomnumber.'.svg';
  	$jpgfile = $randomnumber.'.jpg';
  	$fpsvg = fopen(IMAGEPATH . $svgfile, 'w');
  	$svg = '<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg id="svgmap" zoomAndPan="disable" width="' . $this->map->width . '" height="' . $this->map->height . '"
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink">
<title> kvwmap </title><desc> kvwmap - WebGIS application - kvwmap.sourceforge.net </desc>';
		$this->formvars['svg_string'] = preg_replace('/href="data:image[^ ]*"/', '', $this->formvars['svg_string']);
		$this->formvars['svg_string'] = preg_replace('/xlink:href="[^ ]*"/', 'xlink:href="'.$this->img['hauptkarte'].'" ', $this->formvars['svg_string']);
		$this->formvars['svg_string'] = str_replace(IMAGEURL, IMAGEPATH, $this->formvars['svg_string']).'</svg>';
		$svg.= str_replace('points=""', 'points="-1000,-1000 -2000,-2000 -3000,-3000 -1000,-1000"', $this->formvars['svg_string']);
		fputs($fpsvg, $svg);
  	fclose($fpsvg);
  	exec(IMAGEMAGICKPATH . 'convert ' . IMAGEPATH . $svgfile . ' ' . IMAGEPATH . $jpgfile);
		// echo IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile;exit;

    if (function_exists('imagecreatefromjpeg')) {
    	$mainimage = imagecreatefromjpeg(IMAGEPATH.$jpgfile);
    	if (strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'jpg') {
        $scaleimage = imagecreatefromjpeg(IMAGEPATH.basename($this->img['scalebar']));
      }
      elseif (strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'png') {
        $scaleimage = imagecreatefrompng(IMAGEPATH.basename($this->img['scalebar']));
      }
      ImageCopy($mainimage, $scaleimage, imagesx($mainimage)-imagesx($scaleimage), imagesy($mainimage)-imagesy($scaleimage), 0, 0, imagesx($scaleimage), imagesy($scaleimage));
      ob_end_clean();
      ImageJPEG($mainimage, IMAGEPATH.$jpgfile);
    } 
		//readfile(IMAGEPATH . $svgfile);
		include(SNIPPETS . 'map_image.php');
  }

	/**
	 * Find and show documents that are registered in datasets of layer with $selected_layer_id
	 * but can not be found in document_path of this layer.
	 */
	function show_missing_documents() {
		$this->sanitize([
			'selected_layer_id' => 'int'
		]);
		include_once(CLASSPATH . 'Layer.php');
		$this->layer = Layer::find_by_id($this, $this->formvars['selected_layer_id']);
		$this->main = 'layer_missing_documents.php';
		$this->output();
	}

	/**
	 * This function return the image of the referenzkarte with given ID
	 * if refmap not found return alternative not found image
	 */
	function getRefMapImage($id) {
		include_once(CLASSPATH . 'Referenzkarte.php');
		$referenzkarte = Referenzkarte::find_by_id($this, $id);
		if ($referenzkarte->get('Dateiname') != '' AND file_exists(REFERENCEMAPPATH . $referenzkarte->get('Dateiname'))) {
			$path_parts = pathinfo(REFERENCEMAPPATH . $referenzkarte->get('Dateiname'));
			header('Content-type: image/' . $path_parts['extension']);
			header("Pragma: public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header('Content-Disposition: filename=' . $referenzkarte->get('Dateiname'));
			echo file_get_contents(REFERENCEMAPPATH . $referenzkarte->get('Dateiname'));
		}
		else {
			send_image_not_found(REFERENCEMAPPATH . $referenzkarte->get('Dateiname'));
		}
	}

  function get_classes(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
    $this->classdaten = $mapDB->read_Classes($this->formvars['layer_id']);
    echo'
      <select style="width:430px" size="4" class="select" name="class_1" onchange="change_class();"';
    if(count($this->classdaten)==0){
      echo ' disabled';
    }
    echo ' >';
    for($i = 0; $i < count($this->classdaten); $i++){
      echo html_umlaute('<option value="'.$this->classdaten[$i]['Class_ID'].'">'.$this->classdaten[$i]['Name'].'</option>');
    }
    echo'
      </select>';
		$this->create_symbol_list_template($this->layer);
  }

	function create_symbol_list_template($layerset){
		include_once(CLASSPATH.'FormObject.php');
		$this->symbols = $this->get_symbol_list($layerset, 20);
		echo '
		<div style="display: none">
			' . FormObject::createCustomSelectField(
				'style_symbolname',															# name
				$this->symbols,																	# options
				'circle',																				# value
				1,																							# size
				'width: 118px',																	# style
				'',																							# onchange
				'style_symbolname',															# id
				'',																							# multiple
				'styleFormField',																# class
				' ',																						# firstoption
				'width: 200px',																	# option_style
				'', 																						# option_class
				'',																							# onclick
				'',																							# onmouseenter
				''																							# option_onmouseenter
			) . '
		</div>';
	}

	function get_styles() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
		$this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']); ?>
		<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td height="25" valign="top" class="fett">Styles <a href="tools/show_symbol_icons.php" target="_symbol_icons" title="Zeige verfügbare Symbole"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
				<td align="right"><?
					if ($this->layer['editable']) { ?>
						<a href="javascript:add_style();" title="neuer Style"><i style="padding: 6px" class="fa fa-plus buttonlink" aria-hidden="true"></i></a><?
					} ?>
				</td>
			</tr><?
			if (count($this->classdaten[0]['Style']) > 0) {
				$this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
				for ($i = 0; $i < count($this->classdaten[0]['Style']); $i++) { ?>
					<tr><?
						$td_id = 'td1_style_' . $this->classdaten[0]['Style'][$i]['Style_ID'];
						$td_style = ($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID'] ? 'background-color:lightsteelblue;' : '');
						$td_onclick = 'get_style(' . $this->classdaten[0]['Style'][$i]['Style_ID'] . ')'; ?>
						<td id="<? echo $td_id; ?>" style="<? echo $td_style; ?> cursor: pointer; border-top: 1px solid #aaa;" onclick="<? echo $td_onclick; ?>">
							<img src="<? echo IMAGEURL . $this->getlegendimage($this->layer, NULL, $this->classdaten[0]['Style'][$i]['Style_ID']); ?>">
						</td><?
						$td_id = 'td2_style_' . $this->classdaten[0]['Style'][$i]['Style_ID']; ?>
						<td id="<? echo $td_id; ?>" align="right" style="<? echo $td_style; ?> border-top: 1px solid #aaa;"><?
							if ($this->layer['editable']) {
								if ($i < count($this->classdaten[0]['Style']) - 1) { ?>
									<a
										href="javascript:movedown_style(<? echo $this->classdaten[0]['Style'][$i]['Style_ID']; ?>);"
										title="in der Zeichenreihenfolge nach unten verschieben"
									>
										<img src="<? echo GRAPHICSPATH; ?>pfeil.gif" border="0">
									</a><?
								}
								if ($i > 0) { ?>
									&nbsp;<a
										href="javascript:moveup_style(<? echo $this->classdaten[0]['Style'][$i]['Style_ID']; ?>);"
										title="in der Zeichenreihenfolge nach oben verschieben"
									>
										<img src="<? echo GRAPHICSPATH; ?>pfeil2.gif" border="0">
									</a><?
								}
								echo html_umlaute('&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style(' . $this->classdaten[0]['Style'][$i]['Style_ID'] . ');" title="löschen"><i style="padding: 6px" class="fa fa-trash" aria-hidden="true"></i></a>');
							} ?>
						</td>
					</tr><?
				}
			} ?>
		</table><?
	}

  function get_labels(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
      echo'
        <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td height="25" valign="top" class="fett">Labels</td>
						<td colspan="2" align="right">
						'.($this->layer['editable'] ? '<a href="javascript:add_label();" title="neues Label"><i style="padding: 6px" class="fa fa-plus buttonlink" aria-hidden="true"></i></a>' : '').'
						</td>
          </tr>';
      if(count($this->classdaten[0]['Label']) > 0){
        for($i = 0; $i < count($this->classdaten[0]['Label']); $i++){
          echo'
            <tr>
              <td style="';
								if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'background-color:lightsteelblue; ';}
								echo 'cursor: pointer" id="td1_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" onclick="get_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">';
                echo 'Label '.$this->classdaten[0]['Label'][$i]['Label_ID'].'</td>';
                echo '<td align="right" id="td2_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" ';
                if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
								if($this->layer['editable']){
									echo html_umlaute('><a href="javascript:delete_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');" title="löschen"><i style="padding: 6px" class="fa fa-trash" aria-hidden="true"></i></a>');
								}
          echo'
              </td>
            </tr>';
        }
      }
      echo'
        </table>';
  }

  function get_styles_labels(){
    $this->get_styles();
    echo '█';
    $this->get_labels();
  }

  function save_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Style($this->formvars);
    $this->get_styles();
    echo '█';
    $this->get_style();
  }

  function add_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layer = $mapDB->get_layer($this->formvars['layer_id']);
    $style = array();
    switch ($layer['Datentyp']) {
      case 0 : {
        $style['symbolname'] = 'circle';
        $style['size'] = 6;
        $style['maxsize'] = 8;
        $style['color'] = ($this->formvars['style_color'] != '') ? $this->formvars['style_color'] : '255 255 255';
        $style['outlinecolor'] = ($this->formvars['style_outlinecolor'] != '') ? $this->formvars['style_outlinecolor'] : '0 0 0';
      } break;
      case 1 : {
        $style['color'] = ($this->formvars['style_color'] != '') ? $this->formvars['style_color'] : '0 0 0';
        $style['width'] = '2';
      } break;
      case 2 : {
        $style['color'] = ($this->formvars['style_color'] != '') ? $this->formvars['style_color'] : '255 255 255';
        $style['outlinecolor'] = ($this->formvars['style_outlinecolor'] != '') ? $this->formvars['style_outlinecolor'] : '0 0 0';
      } break;
      default : {
        $style['size'] = 2;
        $style['maxsize'] = 2;
        $style['color'] = '0 0 0';
      }
    }
    $new_style_id = $mapDB->new_Style($style);
    $mapDB->addStyle2Class($this->formvars['class_id'], $new_style_id, NULL);
  }

  function delete_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $classes = $mapDB->get_classes2style($this->formvars['style_id']);
    if(count($classes) == 1){
    	$mapDB->delete_Style($this->formvars['style_id']);
    }
    $mapDB->removeStyle2Class($this->formvars['class_id'], $this->formvars['style_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }

  function moveup_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->moveup_Style($this->formvars['style_id'], $this->formvars['class_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }

  function movedown_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->movedown_Style($this->formvars['style_id'], $this->formvars['class_id']);
    $this->formvars['style_id'] = $this->formvars['selected_style_id'];
    $this->get_styles();
  }

  function add_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$empty_label = new stdClass();
    $empty_label->font = 'arial';
    $empty_label->size = '8';
    $empty_label->minsize = '6';
    $empty_label->maxsize = '10';
    $new_label_id = $mapDB->new_Label($empty_label);
    $mapDB->addLabel2Class($this->formvars['class_id'], $new_label_id, 0);
    $this->get_labels();
  }

  function delete_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->delete_Label($this->formvars['label_id']);
    $mapDB->removeLabel2Class($this->formvars['class_id'], $this->formvars['label_id']);
    $this->formvars['label_id'] = $this->formvars['selected_label_id'];
    $this->get_labels();
  }

	function get_style() {
		include_once(CLASSPATH . 'FormObject.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
		$this->styledaten = $mapDB->get_Style($this->formvars['style_id']);
		if (is_array($this->styledaten)) { ?>
			<table align="left" border="0" cellspacing="0" cellpadding="3"><?
				for ($i = 0; $i < count($this->styledaten); $i++) {
					$select_options = [];
					$copy = false;
					$min = $max = $onkeyup = NULL;	?>
					<tr>
						<td class="px13"><?
							echo key($this->styledaten); ?>
						</td>
						<td class="style-value-column"><?
							switch (key($this->styledaten)) {
								case 'Style_ID' : {
									$onkeyup = "if (event.keyCode != 8) { get_style(this.value); }";
								} break;
								
								case 'linecap' : {
									$select_options = [
										['value' => 'butt', 'output' => 'abgeflacht'],
										['value' => 'round', 'output' => 'rund'],
										['value' => 'square', 'output' => 'eckig'],
										['value' => 'triangle', 'output' => 'dreieckig']
									];
								} break;
								
								case 'linejoin' : {
									$select_options = [
										['value' => 'round', 'output' => 'rund'],
										['value' => 'miter', 'output' => 'phasig'],
										['value' => 'bevel', 'output' => 'abgeschrägt']
									];
								} break;
								
								case 'minsize' : case 'maxsize' : case 'gap' : case 'initialgap' : case 'linejoinmaxsize' : {									
									$type = 'number';
									$min = '0';
									$max = '200';
									$onkeyup = 'enforceMinMax(this)';
								} break;
								
								case 'opacity' : { 
									$type = 'number';
									$min = '0';
									$max = '100';
									$onkeyup = 'enforceMinMax(this)';
								} break;
								
								case 'color' : case 'outlinecolor' : { 
									$type = 'text';
									$copy = true;
								} break;
								
								default : { 
									$type = 'text';
								}
							} 
							if (!empty($select_options)) {
								echo FormObject::createSelectField(
									'style_' . key($this->styledaten),
									$select_options,
									$this->styledaten[key($this->styledaten)],
									1,
									"",
									"",
									"",
									"",
									'styleFormField',
									"ohne"
								);
							}
							else {
								echo '
									<input
										class="styleFormField"
										onkeyup="' . $onkeyup . '"
										name="style_' . key($this->styledaten) . '"
										size="' . $size . '"
										type="' . $type . '"
										' . ($min !== NULL? 'min="' . $min . '"' : '') . '
										' . ($max !== NULL? 'max="' . $max . '"' : '') . '
										value="' . $this->styledaten[key($this->styledaten)] . '"
									>
									' . ($copy? '&nbsp;<i class="fa fa-reply fa-flip-vertical" aria-hidden="true" onclick="this.previousElementSibling.value = document.GUI.rgb.value"></i>' : '');
							}
							?>
						</td>
					</tr><?
					next($this->styledaten);
				}
				if ($this->layer['editable']) { ?>
					<tr>
						<td height="30" colspan="2" valign="bottom" align="center">
							<input
								type="button"
								name="style_save"
								value="Speichern"
								onclick="save_style(<? echo $this->styledaten['Style_ID']; ?>)"
							>
						</td>
					</tr>
				</table><?
			}
		}
		echo '█replace_symbolname_field();';
	}

  function save_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Label($this->formvars);
    $this->get_labels();
    echo '█';
    $this->get_label();
  }

  function get_label(){
		include_once(CLASSPATH . 'FormObject.php');
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
    $this->labeldaten = $mapDB->get_Label($this->formvars['label_id']);
    if(count($this->labeldaten) > 0){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->labeldaten); $i++){
        echo'
          <tr>
            <td class="px13">
							'.key($this->labeldaten).'</td><td>';
							switch(key($this->labeldaten)){
								case 'anglemode' : {
									echo FormObject::createSelectField(
											'label_'.key($this->labeldaten),
											array(
												array('value' => NULL, 'output' => ''),
												array('value' => MS_AUTO, 'output' => 'AUTO'),
												array('value' => MS_AUTO2, 'output' => 'AUTO2'),
												array('value' => MS_FOLLOW, 'output' => 'FOLLOW'),
											),
											$this->labeldaten[key($this->labeldaten)],
											1,
											"width: 87px",
											"",
											"",
											"",
											'labelFormField',
											""
										);
								}break;								
								
								case 'position' : {
									echo FormObject::createSelectField(
											'label_'.key($this->labeldaten),
											array(
												array('value' => 0, 'output' => 'oben links'),
												array('value' => 6, 'output' => 'oben mittig'),
												array('value' => 2, 'output' => 'oben rechts'),
												array('value' => 5, 'output' => 'mittig links'),
												array('value' => 8, 'output' => 'mittig'),
												array('value' => 4, 'output' => 'mittig rechts'),
												array('value' => 3, 'output' => 'unten links'),
												array('value' => 7, 'output' => 'unten mittig'),
												array('value' => 1, 'output' => 'unten rechts'),
												array('value' => 9, 'output' => 'auto')
											),
											$this->labeldaten[key($this->labeldaten)],
											1,
											"width: 87px",
											"",
											"",
											"",
											'labelFormField',
											""
										);
							}break;

							case 'the_force' : case 'partials' :{
								echo '<input class="labelFormField" name="label_'.key($this->labeldaten).'" type="checkbox" value="1" '.($this->labeldaten[key($this->labeldaten)] == 1 ? 'checked="true"' : '').'>';
							}break;

              default : echo '<input class="labelFormField" name="label_'.key($this->labeldaten).'" size="11" type="text" value="'.$this->labeldaten[key($this->labeldaten)].'">';
							}
        echo'
            </td>
          </tr>';
        next($this->labeldaten);
      }
			if($this->layer['editable']){
				echo'
          <tr>
            <td height="30" colspan="2" valign="bottom" align="center"><input type="button" name="label_save" value="Speichern" onclick="save_label('.$this->labeldaten['Label_ID'].')"></td>
          </tr>
        </table>';
			}
    }
  }

  function get_sub_menues() {
    $submenues = Menue::getsubmenues($this, $this->formvars['menue_id']);
    echo '<select name="submenues" size="6" multiple style="width:300px">';
    for ($i = 0; $i < count($submenues); $i++) {
      echo '<option
					selected
					title="' . $submenues[$i]->data['name'] . '"
					id="' . $submenues[$i]->data['order'] . '_all_'.$submenues[$i]->data['menueebene'] . '_' . $i . '"
					value="' . $submenues[$i]->data['id'] . '"
				>&nbsp;&nbsp;-->&nbsp;' . $submenues[$i]->data['name'] . '</option>';
    }
    echo '</select>';
  }

	function getlayerfromgroup(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layer = $mapDB->get_layersfromgroup($this->formvars['group_id']);
    echo '<select name="alllayer" size="6" multiple style="width:300px">';
    for($i=0; $i < count($layer["Bezeichnung"]); $i++){
      echo '<option selected title="'.str_replace(' ', '&nbsp;', $layer["Bezeichnung"][$i]).'" value="'.$layer["ID"][$i].'">'.$layer["Bezeichnung"][$i].'</option>';
    }
    echo '</select>';
  }

  function exportWMC(){
    $this->WMCFileName = 'wmc-'.$this->Stelle->id.'-'.$this->user->id.'.xml';

    $this->loadMap('DataBase');

    $wmcMapObject = $this->prepareLayerForWMCExport($this->map);
    $wmcMapObject->saveMapContext(IMAGEPATH.$this->WMCFileName);
    $this->main  = 'wmc_exportiert.php';
    $this->titel = 'WMC-Export';
 	  $this->output();
  }

  function prepareLayerForWMCExport($mapObject) {
    return $mapObject;
	}

  function rewriteLayer() {
    ## in Entwicklung Konzept noch nicht zuende gedacht pk
    # Diese Funktion nimmt folgende Veränderungen in der MySQL Datenbank vor:
    # 1. löscht die vorhandenen Rollenlayer des Benutzers in der aktuellen Stelle
    # 2. trägt die im Formular übersendeten WMS Layer als Rollenlayer ein,
    # 3. ordnet diese der aktuellen Stelle und dem Benutzers zu
    # 4. trägt die im Formular übersendeten Map-Parameter in der Stelle und Rolle ein
    # zu 1:
    $this->LayerLoeschen(0);
    $this->LayerAnzeigen();
  }

  function showStyles() {
    ob_end_flush();
    $this->main='styledaten.php';
    $this->titel='Styles';
  }

  function https_proxy(){
    $params = array_keys($this->formvars);
    for($i = 0; $i < count($this->formvars); $i++){
      if(in_array(strtolower($params[$i]), array('service', 'request', 'version', 'layer', 'layers', 'format', 'username', 'bbox', 'width', 'height', 'srs', 'user', 'pw'))){
        $url.='&'.$params[$i].'='.$this->formvars[$params[$i]];
      }
    }
    ob_end_clean();
    header('content-type:'.$this->formvars['format']);
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Disposition: filename=test.png');
    if(PHPVERSION > 500){
      $ctx = stream_context_create(array('http' => array('timeout' => 3)));
      print(file_get_contents($this->formvars['url'].'?'.$url, 0, $ctx));
    }
    else{                       # wenn php < Version 5, muss curl-Untrstützung da sein
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $this->formvars['url'].'?'.$url);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 3);
      print(curl_exec($ch));
    }
  }

  function createOWSException(){
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    header('Content-type: text/xml');
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Disposition: filename=owsresponse');
    echo '<?xml version=\'1.0\' encoding="ISO-8859-1" standalone="no" ?>
    <!DOCTYPE ServiceExceptionReport SYSTEM "http://www.digitalearth.gov/wmt/xml/exception_1_0_1.dtd">
    <ServiceExceptionReport version="1.0.1">
    <ServiceException>
    '.$this->Fehlermeldung.'
    </ServiceException>
    </ServiceExceptionReport>
    ';
  }

  function owsProxy() {
    # prüft zunächst ob ein Bild schon mal abgefragt wurde
    # wenn ja, liefert der Dienst dieses Bild aus
    # wenn nicht wird der ows-respone neu erzeugt

    # wms Anfragestringobjekt erzeugen
		include_(CLASSPATH.'wms.php');
    $wms_request = new wms_request_obj('');
    # Parameter und Werte in Kleinschreibung zurückgeben
    $wms_param=$wms_request->getKvpsToLower($this->formvars);
    $this->formvars = $wms_param;
    # Folgendes bezieht sich nur auf getMap Anfragen
    # alle anderen Operationen werden als ganz normale OWS-Requests abgearbeitet.
    if ($wms_param['request']=='getmap') {
      # Dateiformat zuweisen
      $imageformat=$wms_param['format'];
      # Dateiendung zuweisen
      $imageextension = substr(strstr($imageformat,'/'),1); # z.B. macht aus image/png png
      # eindeutigen Dateinamen erzeugen aus bbox Parameter
      $bbox=explode(',',$wms_param['bbox']);
      $box=$bbox[0].','.$bbox[1].','.$bbox[2].','.$bbox[3];
      $zoomstufe=round(log(720/($bbox[2]-$bbox[0]),2));
      $sw=round($bbox[0],1).','.round($bbox[1],1);
      $tmpfile = CACHEPATH.
        $wms_param['layers'].'_'.
        $zoomstufe.'-'.
        $sw.'_'.
        $wms_param['width'] . 'x' .
        $wms_param['height'] . '.' . $imageextension;
      # Prüfen ob die Datei schon existiert
      if(file_exists($tmpfile)) {
        # Datei existiert schon, Ausgeben des Bildes an den Browser
        ob_end_clean();
        header('content-type:'.$imageformat);
        echo file_get_contents($tmpfile);
      }
      else {
        $this->tmpfile=$tmpfile;
        $this->writeTmpFile=true;
        //$this->createOWSResponse();
        $this->createBufferOWSResponse(200);
      }
    }
    else {
      $this->createOWSResponse();
    }
  }

  function createBufferOWSResponse($pixelbuffer){     # Angabe in Pixeln, wie groß der Buffer sein soll
    $width = $this->formvars['width'];
    $height = $this->formvars['height'];
    # Parameter um Buffer erweitern
    $buffer = $pixelbuffer/$this->formvars['width'];
    $bbox = explode(',', $this->formvars['bbox']);
    $extent_width = $bbox[2] - $bbox[0];
    $extent_buffer = $extent_width * $buffer;
    $this->formvars['width'] = $this->formvars['width'] + 2*$pixelbuffer;
    $this->formvars['height'] = $this->formvars['height'] + 2*$pixelbuffer;
    $bbox[0] = $bbox[0] - $extent_buffer;
    $bbox[1] = $bbox[1] - $extent_buffer;
    $bbox[2] = $bbox[2] + $extent_buffer;
    $bbox[3] = $bbox[3] + $extent_buffer;
    $this->formvars['bbox'] = $bbox[0].','.$bbox[1].','.$bbox[2].','.$bbox[3];
    # GetMap-Response erzeugen
    $this->class_load_level = 2;    # die Klassen von allen Layern laden
    $this->loadMap('DataBase');
    $requestobject = ms_newOwsRequestObj();
    $params = array_keys($this->formvars);
    for($i = 0; $i < count($this->formvars); $i++){
      $requestobject->setParameter($params[$i],$this->formvars[$params[$i]]);
    }
    ms_ioinstallstdouttobuffer();
    $this->map->owsdispatch($requestobject);
    $contenttype = ms_iostripstdoutbuffercontenttype();
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    ob_start();
    if ($contenttype == 'image/png'){
      header('Content-type: image/png');
    }
    if ($contenttype == 'image/jpeg'){
      header('Content-type: image/jpeg');
    }
    ms_iogetStdoutBufferBytes();
    $contents = ob_get_contents();
    ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
    $image  = imagecreatefromstring($contents);
    # Bild clippen
    $clippedimage = imagecreatetruecolor($width, $height);
    $backgroundColor = imagecolorallocate($clippedimage, 255,255,255);
    imagefill($clippedimage, 0, 0, $backgroundColor);
    imagecolortransparent($clippedimage, $backgroundColor);
    ImageCopy($clippedimage, $image, 0, 0, $pixelbuffer, $pixelbuffer, $width, $height);
    if($contenttype == 'image/png'){
      imagepng($clippedimage);
      if ($this->writeTmpFile) {
        imagepng($clippedimage, $this->tmpfile);
      }
    }
    elseif($contenttype == 'image/jpeg'){
      imagejpeg($clippedimage);
      if ($this->writeTmpFile) {
        imagejpeg($clippedimage, $this->tmpfile);
      }
    }
    ob_end_flush();
    ms_ioresethandlers();
  }

	function createOWSResponse(){
		$request = array_change_key_case($_REQUEST, CASE_UPPER);
		if(strtolower($request['REQUEST']) == 'getfeatureinfo'){
			$extent = explode(',', $request['BBOX']);
			if($request['VERSION'] == '1.3.0'){
				$request['X'] = $request['I'];
				$request['Y'] = $request['J'];
				$request['SRS'] = $request['CRS'];
				if($request['SRS'] == 'EPSG:4326'){
					$save = $extent[1];
					$extent[1] = $extent[0];
					$extent[0] = $save;
					$save = $extent[3];
					$extent[3] = $extent[2];
					$extent[2] = $save;
				}
			}
			$epsg = explode(':', $request['SRS']);
			$this->user->rolle->epsg_code = $epsg[1];
			$query_layers = explode(',', $request['QUERY_LAYERS']);
			$this->user->rolle->oGeorefExt->minx = $extent[0];
			$this->user->rolle->oGeorefExt->miny = $extent[1];
			$this->user->rolle->oGeorefExt->maxx = $extent[2];
			$this->user->rolle->oGeorefExt->maxy = $extent[3];
			$this->user->rolle->nImageWidth = $request['WIDTH'];
			$this->user->rolle->nImageHeight = $request['HEIGHT'];
			$this->formvars['INPUT_COORD'] = $request['X'].','.$request['Y'].';'.$request['X'].','.$request['Y'];
			$this->formvars['printversion'] = 1;
			foreach($query_layers as $query_layer){
				$layer=$this->user->rolle->getLayer($query_layer);
				$this->formvars['qLayer'.$layer[0]['Layer_ID']] = 1;
			}
			$this->queryMap();
		}
		else{
			$this->map_factor = $this->formvars['mapfactor'];   # der durchgeschleifte MapFactor
			$this->class_load_level = 2;    # die Klassen von allen Layern laden
			$this->loadMap('DataBase', array(), true);
			if (MAPSERVERVERSION < 800) {
				$requestobject = ms_newOwsRequestObj();
			}
			else {
				$requestobject = new OWSRequest();
			}
			$params = array_keys($this->formvars);
			for ($i = 0; $i < count($this->formvars); $i++){
				$requestobject->setParameter($params[$i], $this->formvars[$params[$i]]);
			}
			//$requestobject->loadparams();   # geht nur wenn php als cgi läuft
			if (MAPSERVERVERSION < 800) {
				ms_ioinstallstdouttobuffer();
			}
			else {
				msIO_installStdoutToBuffer();
			}			
			$this->map->OWSDispatch($requestobject);
			if (MAPSERVERVERSION < 800) {
				$contenttype = ms_iostripstdoutbuffercontenttype();
			}
			else {
				$contenttype = msIO_stripStdoutBufferContentType();
			}
			ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
			ob_start();
			switch (strtolower($request['REQUEST'])) {
				case 'getmap' : {
					if (strtolower($request['FORMAT']) == '') {
						if ( $contenttype != 'image/png') {
							$contenttype = 'image/jpeg';
						}
					}
					else {
						$contenttype = strtolower($request['FORMAT']);
					}
				} break;
				case 'getfeature': {
					switch ($request['OUTPUTFORMAT']) {
						case 'text/plain' : {
							$contenttype = 'text/plain';
						} break;
						case 'application/x-gzip;subtype=text/xml' : {
							$contenttype = 'application/x-gzip';
						} break;
						default : {
							$contenttype = 'text/xml';
						}
					}
				} break;
				default : {
					$contenttype = 'application/xml';
				}
			}
			header('Content-type: ' . $contenttype);
			if (MAPSERVERVERSION < 800) {
				ms_iogetStdoutBufferBytes();
			}
			else {
				msIO_getStdoutBufferBytes();
			}
			if ($this->writeTmpFile) {
				$wms_response = new wms_response_obj($this->tmpfile);
				$wms_response->save(ob_get_contents());
			}
			ob_end_flush();
			if (MAPSERVERVERSION < 800) {
				ms_ioresethandlers();
			}
			else {
				msIO_resetHandlers();
			}
		}
	}

	function createAtomResponse() {
		// Test URL Service: https://bauleitplaene-mv.de/kvwmap_dev/?go=Atom&type=service
		// Test URL Dataset: https://bauleitplaene-mv.de/kvwmap_dev/?go=Atom&ype=dataset&dataset_id=dataset_feed_b46d87dc-6465-11ea-bebd-f784309c10da
		include(CLASSPATH . 'atom.php');
		$atom = new Atom($this);
		$feed_type = $_GET['type'];
		if ($feed_type == 'service') {
			header('Content-Type: application/xml');
			echo $atom->build_service_feed();
		} else if($feed_type == 'dataset') {
			//$dataset_id = $_GET['dataset_id'];
			//TODO filter by id, if no filter is used, show all datasets
			$dataset_id = $_GET['dataset_id'];
			if (!empty($dataset_id)) {
				header('Content-Type: application/xml');
				$gml_id = str_replace("dataset_feed_","",$dataset_id);
				echo $atom->build_dataset_feed($gml_id);
			}
			else {
				echo 'No dataset_id parameter specified, e.g. ?dataset_id=dataset_feed_578268ba-433f-11e8-88d4-976b915d04de';
			}
			//TODO consider showing all datasets if no ID is entered
		}
		else {
			echo 'No Service type parameter specified, e.g. ?type=service, ?type=dataset';
		}
	}

	function adminFunctions() {
		include_once(CLASSPATH . 'administration.php');
		$this->administration = new administration($this->database, $this->pgdatabase);
		$this->administration->get_database_status();
		$this->administration->get_config_params();
		switch ($this->formvars['func']) {
			case "update_code_and_databases" : {
				$result = $this->administration->update_code();
				$this->administration->get_database_status();
				$err_msgs = $this->administration->update_databases();
				if (count($err_msgs) > 0) {
					$this->add_message('error', implode('<br>', $err_msgs));
				}
				$this->administration->get_database_status();
				$this->administration->get_config_params();
				$this->showAdminFunctions();
			} break;			
			case "update_databases" : {
				$err_msgs = $this->administration->update_databases();
				if (count($err_msgs) > 0) {
					$this->add_message('error', implode('<br>', $err_msgs));
				}
				$this->administration->get_database_status();
				$this->administration->get_config_params();
				$this->showAdminFunctions();
			} break;
			case "save_config" : {
				$result = $this->administration->save_config($this->formvars);
				$this->showAdminFunctions();
			} break;
			case "createRandomPassword" : {
				$this->createRandomPassword();
			} break;
			case "save_all_layer_attributes" : {
				$this->save_all_layer_attributes();
			} break;
			case "custom"	: {
				$admin_function_file = WWWROOT . CUSTOM_PATH . 'layouts/adminfunctions.php';
				if (file_exists($admin_function_file)) {
					$this->main = $admin_function_file;
					$this->titel = 'Eigene Administrationsfunktionen';
				} else {
					$this->showAdminFunctions();
				}
			} break;
			case "create_inserts_from_dataset" : {
				$inserts_file = LOGPATH . 'inserts_from_dataset.sql';
#				echo $this->administration->create_inserts_from_dataset($this->formvars['schema'], $this->formvars['table'], $this->formvars['where']);

				file_put_contents($inserts_file, $this->administration->create_inserts_from_dataset($this->formvars['schema'], $this->formvars['table'], $this->formvars['where']));
				header("Content-type:application/pdf");
				header("Content-Disposition:attachment; filename=" . $this->formvars['schema'] . "-" . $this->formvars['table'] . "-" . $this->formvars['where'] . ".sql");
				readfile($inserts_file);

				exit;
			} break;
			default : {
				$this->showAdminFunctions();
			}
		}
		return $result;
	}

	function save_all_layer_attributes() {
		$this->main='genericTemplate.php';
		$this->title='Speicherung aller Layerattribute';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->layerdaten = $mapDB->get_layers_of_type(MS_POSTGIS, NULL);
		for ($i = 0; $i < count($this->layerdaten['ID']); $i++) {
			$layer = $mapDB->get_Layer($this->layerdaten['ID'][$i]);
			if ($layer['pfad'] != '' AND $layer['connectiontype'] == 6) {
				$this->param['str1'] .= 'Layer: ' . $layer['Name'] . '<br>';
				$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
				$attributes = $mapDB->load_attributes($layerdb, $layer['pfad']);

				$mapDB->save_postgis_attributes($layerdb, $layer['Layer_ID'], $attributes, '', '');
				$mapDB->delete_old_attributes($layer['Layer_ID'], $attributes);
			}
		}
	}

	function createRandomPassword() {
		$this->titel = 'Zufälliges Passwort';
		$this->main = 'genericTemplate.php';
		$this->param['height'] = 400;
		$this->param['str1'] = '<h3>10 sichere und zufällig erzeugte Passwörter</h3>';
		while ($i++ < 10) {
			$this->param['str1'] .= '<br><b>' . createRandomPassword(8) . '</b>';
		}
	}

	function closelogfiles(){
		$dump_rolle =  $this->database->create_update_dump('rolle');
		$dump_rolle2usedlayer =  $this->database->create_update_dump('u_rolle2used_layer');
		$dump_menue2rolle = $this->database->create_update_dump('u_menue2rolle');
		$dump_groups2rolle = $this->database->create_update_dump('u_groups2rolle');
		$this->database->logfile->write($dump_rolle);
		$this->database->logfile->write($dump_rolle2usedlayer);
		$this->database->logfile->write($dump_menue2rolle);
		$this->database->logfile->write($dump_groups2rolle);
		$this->main = 'showadminfunctions.php';
		$this->titel = 'Administrationsfunktionen';
	}

	function showAdminFunctions() {
		$this->main = 'showadminfunctions.php';
		$this->titel = 'Administrationsfunktionen';
	}

	function showConstants() {
		$this->main='showadminfunctions.php';
		$this->administration->get_constants_from_all_configs();
  }

	function getMenueWithAjax() {
		// $this->loadMap('DataBase');
		// $this->drawMap();
		$this->user->rolle->hideMenue(0);
		include(LAYOUTPATH . "snippets/menue.php");
		echo '█if(typeof resizemap2window != "undefined")resizemap2window();';
	}

	function hideMenueWithAjax() {
		$this->user->rolle->hideMenue(1);
		echo '█if(typeof resizemap2window != "undefined")resizemap2window();';
	}

	function changeLegendDisplay(){
		$this->user->rolle->changeLegendDisplay($this->formvars['hide']);
		echo 'hide: ' . $this->formvars['hide'] . '█resizemap2window();';
	}

	function saveOverlayPosition(){
  	$this->user->rolle->saveOverlayPosition($this->formvars['overlayx'],$this->formvars['overlayy']);
  }

	function set_last_query_layer(){
  	$this->user->rolle->set_last_query_layer($this->formvars['layer_id']);
  }

	function PointEditor() {
		include_once(CLASSPATH . 'pointeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->reduce_mapwidth(30);
		$this->main = 'PointEditor.php';
		$this->titel = 'Geometrie bearbeiten';
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		for ($i = 0; $i < count($attributes['name']); $i++) {
			if ($attributes['form_element_type'][$i] == 'Winkel') {
				$this->angle_attribute = $attributes['name'][$i];
			}
		}
		$this->formvars['layer_columnname'] = $attributes['the_geom'];
		$this->formvars['layer_tablename'] = $attributes['table_name'][$attributes['the_geom']];
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
		$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
		if (!$this->formvars['edit_other_object'] AND ($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != '')) {
			$this->neuLaden();
		}
		else {
			$this->loadMap('DataBase');
			if ($this->formvars['oid'] != '') {
				$this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->angle_attribute);
				if ($this->point['pointx'] != '') {
					$this->formvars['loc_x'] = $this->point['pointx'];
					$this->formvars['loc_y'] = $this->point['pointy'];
					$this->formvars['angle'] = $value = str_replace('.', ',', $this->point['angle']);
					$rect = rectObj(
						$this->point['pointx'] - 100,
						$this->point['pointy'] - 100,						
						$this->point['pointx'] + 100,
						$this->point['pointy'] + 100
					);
					$this->map->setextent($rect->minx, $rect->miny, $rect->maxx, $rect->maxy);
					if (MAPSERVERVERSION > 600) {
						$this->map_scaledenom = $this->map->scaledenom;
					}
					else {
						$this->map_scaledenom = $this->map->scale;
					}
				}
			}
		}
		# zoom_to_max_layer_extent
		if ($this->formvars['zoom_layer_id'] != '') {
			$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
		}
		$this->saveMap('');
		if ($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next') {
			$currenttime = date('Y-m-d H:i:s',time());
			$this->user->rolle->setConsumeActivity($currenttime, 'getMap', $this->user->rolle->last_time_id);
		}
		$this->drawMap();
		$this->output();
	}

	function PointEditor_Senden() {
		$this->sanitize([
			'angle' => 'float'
		], true);
		include_once(CLASSPATH . 'pointeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
		# eingeabewerte pruefen:
		$ret = $pointeditor->pruefeEingabedaten($this->formvars['loc_x'], $this->formvars['loc_y']);
		if ($ret[0]) { # fehlerhafte eingabedaten
			$this->add_message('error', $ret[1]);
			$this->PointEditor();
			return;
		}
		else {
			# Before Update trigger
			if (!empty($layerset[0]['trigger_function'])) {
				$where_condition = $layerset[0]['oid'] . " = '" . $this->formvars['oid'] . "'";
				if ($layerset[0]['oid'] == 'oid'){
					$oid_sql = 'oid,';
				}
				$sql_old = "
					SELECT ".
						$oid_sql." *
					FROM
						" . $layerset[0]['schema'] . '.' . pg_quote($layerset[0]['maintable']) . "
					WHERE
						" . $where_condition;
				#echo '<br>sql before update: ' . $sql_old; #pk
				$ret = $layerdb->execSQL($sql_old, 4, 1);
				$old_dataset = ($ret[0] == 0 ? pg_fetch_assoc($ret[1]) : array());
				$this->exec_trigger_function('BEFORE', 'UPDATE', $layerset[0], $this->formvars['oid'], $old_dataset);
			}

			$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
			$ret = $pointeditor->eintragenPunkt(
				$this->formvars['loc_x'],
				$this->formvars['loc_y'],
				$this->formvars['oid'],
				$this->formvars['layer_tablename'],
				$this->formvars['layer_columnname'],
				$this->formvars['dimension'],
				$this->get_auto_attribute_kvps()
			);
			if ($ret['success']) {
				# After Update trigger
				if (!empty($layerset[0]['trigger_function'])) {
					$this->exec_trigger_function('AFTER', 'UPDATE', $layerset[0], $this->formvars['oid'], $old_dataset);
				}
				$this->add_message('notice', 'Eintrag erfolgreich!');
				if ($ret['info_msg']) {
					$this->add_message('info', $ret['info_msg']);
				}
			}
			else {
				$this->add_message('error', $ret['msg']);
			}
			$this->PointEditor();
		}
	}

	function MultiGeomEditor() {
		include_once (CLASSPATH . 'multigeomeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(30);
		$this->main = $this->formvars['go'] . '.php';
		$this->titel='Geometrie bearbeiten';
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		if ($this->formvars['geom_from_layer'] == '') {
			$this->formvars['geom_from_layer'] = $layerset[0]['geom_from_layer'];
		}
		$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['layer_columnname'] = $attributes['the_geom'];
		$this->formvars['layer_tablename'] = $attributes['table_name'][$attributes['the_geom']];
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
		$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true, true);
		$multigeomeditor = new multigeomeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
		if (
			!$this->formvars['edit_other_object'] AND
			($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != '')
		) {
			$this->neuLaden();
		}
		else {
			$this->loadMap('DataBase');
			if($this->formvars['oid'] != '' AND $this->formvars['no_load'] != 'true'){
				# geom abfragen
				$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
				$this->geom = $multigeomeditor->getGeom($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
				if($this->geom['wktgeom'] != ''){
					$this->formvars['newpathwkt'] = $this->geom['wktgeom'];
					$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
					$this->formvars['newpath'] = $this->geom['svggeom'];
					$this->formvars['firstpoly'] = 'true';
					if($this->formvars['zoom'] != 'false'){
						$rect = $multigeomeditor->zoomToGeom($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL);
						$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
						$this->map_scaledenom = $this->map->scaledenom;
					}
				}
			}
		}
		# Zoom auf Geometrie-Fehler-Position
		if ($this->error_position != '') {
			$rect = rectObj($this->error_position[0]-50,$this->error_position[1]-50,$this->error_position[0]+50,$this->error_position[1]+50);
			$this->map_scaledenom = $this->map->scaledenom;
		}
		# zoom_to_max_layer_extent
		if($this->formvars['zoom_layer_id'] != '')$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
		if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
			$currenttime=date('Y-m-d H:i:s',time());
			$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		}
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function MultiGeomEditor_Senden(){
		$this->sanitize([
			'area' => 'float',
			'linelength' => 'float'
		], true);
		include_(CLASSPATH . 'multigeomeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$multigeomeditor = new multigeomeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
		# eingeabewerte pruefen:
		$ret = $multigeomeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
		if ($ret[0]) { # fehlerhafte eingabedaten
			if (strpos($ret[1], '[') !== false) {
				$this->error_position = explode(' ', trim(substr($ret[1], strpos($ret[1], '[')), '[]'));
				$this->formvars['no_load'] = 'true';
			}
			$this->Meldung = $ret[1];
			$this->MultiGeomEditor();
			return;
		}
		else {
			# Before Update trigger
			if (!empty($layerset[0]['trigger_function'])) {
				$where_condition = $layerset[0]['oid'] . " = '" . $this->formvars['oid'] . "'";
				if ($layerset[0]['oid'] == 'oid'){
					$oid_sql = 'oid,';
				}
				$sql_old = "
					SELECT ".
						$oid_sql." *
					FROM
						" . $layerset[0]['schema'] . '.' . pg_quote($layerset[0]['maintable']) . "
					WHERE
						" . $where_condition;
				#echo '<br>sql before update: ' . $sql_old; #pk
				$ret = $layerdb->execSQL($sql_old, 4, 1);
				$old_dataset = ($ret[0] == 0 ? pg_fetch_assoc($ret[1]) : array());
				$this->exec_trigger_function('BEFORE', 'UPDATE', $layerset[0], $this->formvars['oid'], $old_dataset);
			}

			$ret = $multigeomeditor->updateGeom(
				$this->formvars['newpathwkt'], 
				$this->formvars['oid'],
				$this->formvars['layer_tablename'],
				$this->formvars['layer_columnname'],
				$this->attributes['geomtype'][$this->attributes['the_geom']],
				$this->get_auto_attribute_kvps()
			);
			if (!$ret['success']) { # fehler beim eintrag
				$this->Meldung = $ret[1];
				$this->formvars['no_load'] = 'true';
			}
			else { # eintrag erfolgreich
				# After Update trigger
				if (!empty($layerset[0]['trigger_function'])) {
					$this->exec_trigger_function('AFTER', 'UPDATE', $layerset[0], $this->formvars['oid'], $old_dataset);
				}
				$this->formvars['newpath']="";
				$this->formvars['newpathwkt']="";
				$this->formvars['pathwkt']="";
				$this->formvars['firstpoly']="";
				$this->formvars['secondpoly']="";
				$this->formvars['firstline'] = "";
				$this->formvars['secondline'] = "";				
				$this->add_message('notice', 'Eintrag erfolgreich!');
				if ($ret[3]) {
					$this->add_message('info', $ret[3]);
				}
				elseif ($ret['msg'] != '') {
					$this->add_message('info', $ret['msg']);
				}
			}
			$this->formvars['CMD'] = '';
			$this->MultiGeomEditor();
		}
	}

	/**
	 * function collect key value pairs of auto attributes for an update statement
	 * @return array key value pairs as strings for update statements
	 */
	function get_auto_attribute_kvps() {
		$kvps = array();
		for ($i = 0; $i < count($this->attributes['type']); $i++) {
			if ($this->attributes['name'][$i] != 'oid') {
				switch (true) {
					case (
						$this->attributes['form_element_type'][$i] == 'Time' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) : $kvps[] = $this->attributes['name'][$i] . " = '" . date('Y-m-d G:i:s') . "'";
					break;
					case (
						$this->attributes['form_element_type'][$i] == 'User' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) : $kvps[] = $this->attributes['name'][$i] . " = '" . $this->user->Vorname . " " . $this->user->Name . "'";
					break;
					case (
						$this->attributes['form_element_type'][$i] == 'UserID' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) : $kvps[] = $this->attributes['name'][$i] . " = " . $this->user->id;
					break;
					case (
						$this->attributes['form_element_type'][$i] == 'Stelle' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) : $kvps[] = $this->attributes['name'][$i] . " = '" . $this->Stelle->Bezeichnung . "'";
					break;
					case (
						$this->attributes['form_element_type'][$i] == 'StelleID' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) : $kvps[] = $this->attributes['name'][$i] . " = " . $this->Stelle->id;
					break;
					case ( # only for points
						$this->attributes['form_element_type'][$i] == 'Winkel'
					) : $kvps[] = $this->attributes['name'][$i] . " = " . ($this->formvars['angle'] ?: 'NULL');
					break;
					case ( # only for lines
						$this->attributes['form_element_type'][$i] == 'Länge'
					) : $kvps[] = $this->attributes['name'][$i] . " = " . ($this->formvars['linelength'] ?: 'NULL');
					break;
					case ( # only for polygons
						$this->attributes['form_element_type'][$i] == 'Fläche'
					) : $kvps[] = $this->attributes['name'][$i] . " = " . ($this->formvars['area'] ?: 'NULL');
					break;
				}
			}
		}
		return $kvps;
	}

	
	function create_auto_classes_for_rollenlayer(){
		$dbmap = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->formvars['selected_layer_id'] = $this->formvars['layer_options_open'];
		$auto_class_attribute = $this->formvars['klass_'.$this->formvars['selected_layer_id']];
		$this->formvars['no_output'] = true;		# damit der Aufruf von output() verhindert wird
		$this->GenerischeSuche_Suchen();
		$result= $this->qlayerset[0]['shape'];
		# alte Klassen löschen
		$this->classes = $dbmap->read_Classes($this->formvars['selected_layer_id']);
		for($i = 0; $i < count($this->classes); $i++){
			$dbmap->delete_Class($this->classes[$i]['Class_ID']);
		}
		for ($i = 0; $i < count($result); $i++) {
			foreach ($result[$i] As $key => $value) {
				if ($auto_class_attribute === $key) {
					$classes[$value] = $value;
				}
			}
		}
		$dbmap->createAutoClasses(array_unique($classes), $auto_class_attribute, -$this->formvars['selected_layer_id'], $this->qlayerset[0]['Datentyp'], $this->database);
		$this->loadMap('DataBase');
    // $this->saveMap('');
    // $currenttime=date('Y-m-d H:i:s',time());
    // $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    // $this->drawMap();
		$this->legende = $this->create_dynamic_legend();
    $this->output();
	}

	function zoomto_selected_datasets(){
    $dbmap = new db_mapObj($this->Stelle->id, $this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		if ($this->formvars['chosen_layer_id'] > 0) {
			$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		}
		else {
			$layerset = $this->user->rolle->getRollenLayer(-$this->formvars['chosen_layer_id']);
		}
		$layerset[0]['attributes'] = $dbmap->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, NULL);
		if ($layerset[0]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
			$layerset[0]['maintable'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];
		}
		if ($layerset[0]['oid'] == '' AND count_or_0($layerset[0]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
			$layerset[0]['oid'] = $layerset[0]['attributes']['pk'][0];
		}
    $checkbox_names = explode('|', $this->formvars['checkbox_names_' . $this->formvars['chosen_layer_id']]);
		$this->formvars['selektieren'] = 'false';
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $oids[] = $element[3];
      }
    }
		if($this->formvars['klass_'.$this->formvars['chosen_layer_id']] AND $this->formvars['no_query'] != true){
			$this->last_query = $this->user->rolle->get_last_query($this->formvars['chosen_layer_id']);
			$this->formvars['no_output'] = true;		# damit der Aufruf von output() verhindert wird
			$this->GenerischeSuche_Suchen();
			$layerset[0]['attributes'] = $this->qlayerset[0]['attributes'];
			$auto_class_attribute = $this->formvars['klass_'.$this->formvars['chosen_layer_id']];
			$result= $this->qlayerset[0]['shape'];
		}
		$this->createZoomRollenlayer($dbmap, $layerdb, $layerset, $oids, $auto_class_attribute, $result);
		$this->loadMap('DataBase');
    if ($oids != '') {
      # Polygon abfragen und Extent setzen
      $rect = $dbmap->zoomToDatasets($oids, $layerset[0], $this->formvars['layer_columnname'], 10, $layerdb, $this->user->rolle->epsg_code, $this->Stelle);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
	}

	function zoomto_dataset(){
		$dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		if ($this->formvars['layer_id'] > 0) {
			$layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
		}
		else {
			$layerset = $this->user->rolle->getRollenlayer(-$this->formvars['layer_id']);
		}
		$layerset[0]['attributes'] = $dbmap->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL, false, false);
		if ($layerset[0]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
			$layerset[0]['maintable'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];
		}
		if ($layerset[0]['oid'] == '' AND count_or_0($layerset[0]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
			$layerset[0]['oid'] = $layerset[0]['attributes']['pk'][0];
		}
		if ($this->formvars['oid'] != '') {
			if ($this->formvars['selektieren'] != 'zoomonly') {
				$this->createZoomRollenlayer($dbmap, $layerdb, $layerset, array($this->formvars['oid']));
			}

			$this->loadMap('DataBase');
			$rect = $dbmap->zoomToDatasets(array($this->formvars['oid']), $layerset[0], $this->formvars['layer_columnname'], 10, $layerdb, $this->user->rolle->epsg_code, $this->Stelle);
			$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);

			# damit nicht außerhalb des Stellen-Extents gezoomt wird
			$oPixelPos = new PointObj();
	    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
			$this->map->zoomscale($this->map->scaledenom,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
			$this->map_scaledenom = $this->map->scaledenom;
		}
		$this->saveMap('');
		if ($this->formvars['maponly']) {
			$this->drawMap(true);
			$file_name = IMAGEPATH . basename($this->img['hauptkarte']);
			header("Content-Type: image/png");
			header("Content-Length: " . filesize($file_name));
			readfile($file_name);
			exit();
		}
		if ($this->formvars['go_next'] != ''){
			go_switch($this->formvars['go_next']);
			exit();
		}
		$currenttime = date('Y-m-d H:i:s', time());
		$this->user->rolle->setConsumeActivity($currenttime,'getMap', $this->user->rolle->last_time_id);
		$this->drawMap();
		$this->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit eigene Abfragen-Layer angezeigt werden
		$this->output();
	}

	function createZoomRollenlayer($dbmap, $layerdb, $layerset, $oids, $auto_class_attribute = NULL, $result = NULL){
		# Layer erzeugen
		$data = $dbmap->getData($layerset[0]['Layer_ID']);
		$explosion = explode(' ', $data);
		$datageom = $explosion[0];
		$select = $dbmap->getSelectFromData($data);		

		$orderbyposition = strrpos(strtolower($select), 'order by');
		$lastfromposition = strrpos(strtolower($select), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			$select = substr($select, 0, $orderbyposition);
		}
		if($layerset[0]['oid'] == 'oid' AND strpos(strtolower($select), $layerset[0]['oid']) === false){
			$select = str_replace('*', '*, '.$layerset[0]['oid'], $select);
			$select = str_replace_first($datageom, $datageom.', '.$layerset[0]['oid'], $select);
		}
		if($auto_class_attribute != '' AND strpos($select, '*') === false AND strpos($select, $auto_class_attribute) === false){			# Attribut für automatische Klassifizierung mit ins data packen
			$select = str_replace(' from ', ', '.$auto_class_attribute.' from ', strtolower($select));
		}
		if ($oids != NULL) {
			$wherepos = strpos(strtolower($select), 'where');
			if($wherepos === false){
				$select .= " WHERE ";
			}
			else{
				$select .= " AND ";
			}
			$oid = $layerset[0]['oid'];
			$explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
			for($i = 0; $i < count($explosion); $i++){
				if(strpos(strtolower($explosion[$i]), '.'.$layerset[0]['oid']) !== false){
					$oid = str_replace('select', '', strtolower(str_replace([chr(10), chr(13)], '', $explosion[$i])));
					break;
				}
			}		
			$select .= $oid." IN ('" . implode("','", $oids) . "')";
		}
		else {
			$wherepos = strpos(strtolower($select), 'where');
			if($wherepos === false){
				$select .= " WHERE true";
			}
		}

		$datastring = $datageom." from (" . $select;
		$datastring.=") as foo using unique ".$layerset[0]['oid']." using srid=" . $layerset[0]['epsg_code'];
		$legendentext = $layerset[0]['Name_or_alias']." (".date('d.m. H:i',time()).")";

		$group = $dbmap->getGroupbyName('eigene Abfragen');
		if($group != ''){
			$groupid = $group['id'];
		}
		else{
			$groupid = $dbmap->newGroup('eigene Abfragen', 0);
		}
		$this->formvars['original_layer_id'] = $layerset[0]['Layer_ID'];
		$this->formvars['user_id'] = $this->user->id;
		$this->formvars['stelle_id'] = $this->Stelle->id;
		$this->formvars['aktivStatus'] = 1;
		$this->formvars['Name'] = $legendentext;
		$this->formvars['Gruppe'] = $groupid;
		$this->formvars['Typ'] = 'search';
		$this->formvars['Data'] = $datastring;
		$this->formvars['query'] = $select;
		$this->formvars['Datentyp'] = $layerset[0]['Datentyp'];
		$this->formvars['connectiontype'] = 6;
		if ($layerset[0]['labelitem'] != 'Cluster_FeatureCount') {
			$this->formvars['labelitem'] = $layerset[0]['labelitem'];
		}
		$this->formvars['connection_id'] = $layerdb->connection_id;
		$this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
		if ($layerset[0]['Datentyp'] == MS_LAYER_POLYGON) {
			$this->formvars['transparency'] = $this->user->rolle->result_transparency;
		}
		else {
			$this->formvars['transparency'] = 100;
		}

		$layer_id = $dbmap->newRollenLayer($this->formvars);
		$attributes = $dbmap->load_attributes($layerdb, $select);
		$dbmap->save_postgis_attributes($layerdb, -$layer_id, $attributes, '', '');

		if ($this->formvars['selektieren'] == 'false') { # highlighten (mit der ausgewählten Farbe)
			# ------------ automatische Klassifizierung -------------------
			if($auto_class_attribute != ''){
				$attributes = $layerset[0]['attributes'];
				for($i = 0; $i < count($result); $i++){		# Auswahlfelder behandeln
					foreach($result[$i] As $key => $value){
						$name = $value;
						$j = $attributes['indizes'][$key];
						if($attributes['type'][$j] != 'geometry' AND $attributes['name'][$i] != 'lock'){
							if($attributes['form_element_type'][$j] == 'Auswahlfeld'){
								if(is_array($attributes['dependent_options'][$j])){
									$enum = $attributes['enum'][$j][$i];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
								}
								else{
									$enum = $attributes['enum'][$j];
								}
								$name = $enum['output'][$value];
							}
						}
						if($auto_class_attribute === $key){
							$classes[$value] = $name;
						}
					}
				}
				$dbmap->createAutoClasses(array_unique($classes), $auto_class_attribute, $layer_id, $layerset[0]['Datentyp'], $this->database);
			}
			# ------------ automatische Klassifizierung Ende -------------------
			else{
				$dbmap->addRollenLayerStyling($layer_id, $layerset[0]['Datentyp'], $this->formvars['labelitem'], $this->user, 'zoom');
			}
		}
		else{         # selektieren (eigenen Style verwenden)
			$class_id = $dbmap->getClassFromObject($select, $layerset[0]['Layer_ID'], $layerset[0]['classitem']);
			$this->formvars['class'] = $dbmap->copyClass($class_id, -$layer_id);
			$this->user->rolle->setOneLayer($layerset[0]['Layer_ID'], 0);	# richtigen Layer ausschalten
		}
		$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		return $layer_id;
	}
	
  function zoom_toPoint(){
		include_(CLASSPATH.'pointeditor.php');
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    if ($this->formvars['layer_id'] > 0) {
			$layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
		}
		else {
			$layerset=$this->user->rolle->getRollenlayer(-$this->formvars['layer_id']);
		}
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
    if($this->formvars['oid'] != '') {
      $this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL);

			if (defined('ZOOMBUFFER') AND ZOOMBUFFER > 0)
				$rand = ZOOMBUFFER;
			else
				$rand = 100;

			if (defined(ZOOMUNIT) AND !in_array(ZOOMUNIT, array('meter', 'scale')))
				$unit = ZOOMUNIT;
			else
				$unit = 'meter';

			switch ($unit) {
				case 'meter' : {
					if ($this->user->rolle->epsg_code == 4326)
						$rand = $rand / 10000;
					$rect = $this->get_rect_by_buffer($this->point['pointx'], $this->point['pointy'], $rand);
				} break;
				case 'scale' : {
					$rect = $this->get_rect_by_scale($this->point['pointx'], $this->point['pointy'], $rand);
				} break;
			}

			if($this->formvars['selektieren'] != 'zoomonly'){
				$this->createZoomRollenlayer($dbmap, $layerdb, $layerset);
			}
      $this->loadMap('DataBase');
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
		$this->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit eigene Abfragen-Layer angezeigt werden
    $this->output();
  }	

	function get_rect_by_buffer($x, $y, $rand) {
		$rect = rectObj(
			$x - $rand,
			$y - $rand,			
			$x + $rand,
			$y + $rand
		);
		return $rect;
	}

	function get_rect_by_scale($x, $y, $scale) {
		$width_rand  = 0.02535 / 96 * $this->user->rolle->nImageWidth  * $scale / 2;
		$height_rand = 0.02535 / 96 * $this->user->rolle->nImageHeight * $scale / 2;
		$rect = rectObj(
			$x - $width_rand,
			$y - $height_rand,			
			$x + $width_rand,
			$y + $height_rand
		);
		return $rect;
	}

  function bauleitplanung(){
    $this->main='bauleitplanungsaenderung.php';
    $this->titel='Änderung in der Bauleitplanung';
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    if ($this->formvars['CMD']!='') {
      $this->navMap($this->formvars['CMD']);
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function bauleitplanungSenden() {
    $bplanung = new bauleitplanung($this->pgdatabase);
    # eingeabewerte pruefen:
      $ret = $bplanung->pruefeEingabedaten($this->formvars['newpathwkt'],$this->formvars['email'], $this->formvars['user']);
      if ($ret[0]) { # fehlerhafte eingabedaten
        $this->Meldung=$ret[1];
        $this->bauleitplanung();
        return;
      }
      else { # eintraege gueltig
        $this->Meldung='';
        # umring generieren:
        $umring = $this->formvars['newpathwkt'];
        $datum = date('Y-m-d H:i:s',time());
        $ret = $bplanung->eintragenNeueFlaeche($umring, $this->formvars['user'], $this->formvars['hinweis'], $this->formvars['bemerkung'], $datum);
        if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
        }
        else { # eintrag erfolgreich
          mail($this->formvars['email'], 'Bauleitplanungänderung', 'Der Nutzer '.$this->formvars['user'].' hat am '.$datum.' eine Änderung am B-Plan mit der Nummer '.$this->formvars['bplannumber'].' vorgenommen. Bemerkung:'.$this->formvars['bemerkung'].'.', 'From: mail@kvwmap.de');
          $this->formvars['newpath']="";
          $this->formvars['newpathwkt']="";
          $this->formvars['pathwkt']="";
          $this->formvars['firstpoly']="";
          $this->formvars['secondpoly']="";
          $this->add_message('notice', 'Eintrag erfolgreich!');
        }
      }
    $this->bauleitplanung();
  }

  function bauleitplanungLoeschen() {
    $bplanung = new bauleitplanung($this->pgdatabase);
    $loeschdatum = date('Y-m-d H:i:s',time());
    $bplanung->FlaecheLoeschen($this->formvars['id'], $this->user->Name, $loeschdatum);
  }

  function haltestellenSuche() {
    $this->main = 'haltestellensuche.php';
    $this->titel = 'Haltestellensuche';
    if ($this->formvars['defaultAddress'] == '') {
	  	echo $this->formvars['defaultAddress'];
	  	$this->formvars['defaultAddress'] = 'hier eine Adresse eingeben';
		}
  }

  function druckrahmen_init() {
    $Document=new Document($this->database);
    $this->Document=$Document;
  }

  function druckrahmen_load(){
		$this->druckrahmen_load_pdf();
  }

  function druckrahmen_load_pdf(){
    $this->Document->frames = $this->Document->load_frames(NULL, NULL);
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    if (!$this->formvars['aktiverRahmen']){
      $this->formvars['aktiverRahmen'] = $frameid;
    }
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->selectedframe = $this->Document->load_frames(NULL, $this->formvars['aktiverRahmen']);
    if($this->Document->selectedframe != NULL){
      $ratio = $this->Document->selectedframe[0]['mapwidth']/$this->Document->selectedframe[0]['mapheight'];
      $this->formvars['worldprintwidth'] = $this->Document->selectedframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.00035277;
      $this->formvars['worldprintheight'] = $this->Document->selectedframe[0]['mapheight'] * $this->formvars['printscale'] * 0.00035277;
			$this->formvars['map_factor'] = 1;
      if ($this->Document->selectedframe[0]['dhk_call'] == '') {
      	$this->previewfile = $this->createMapPDF($this->formvars['aktiverRahmen'], true, true);
			}

			# Fonts auslesen
			include_(CLASSPATH . 'datendrucklayout.php');
			$ddl = new ddl($this->database);
			$this->Document->fonts = $ddl->get_fonts();
			$this->Document->din_formats = get_din_formats();

			if($this->Document->selectedframe[0]['headsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']))){
        $this->Document->headsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']));
      }
      else{
        $this->Document->headsize[0] = 1;
        $this->Document->headsize[1] = 1;
      }
			if($this->Document->selectedframe[0]['refmapsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']))){
        $this->Document->refmapsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']));
      }
      else{
        $this->Document->refmapsize[0] = 1;
        $this->Document->refmapsize[1] = 1;
      }

      $this->Document->cent = $this->Document->selectedframe[0]['preis']%100;
      $this->Document->euro = ($this->Document->selectedframe[0]['preis'] - $this->Document->cent)/100;
      $this->Document->cent = str_pad ($this->Document->cent, 2, "0", STR_PAD_LEFT);

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->selectedframe[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->Document->selectedframe[0]['texts'][$i]['text']);
      }
    }
    $this->main='druckrahmen.php';
    $this->titel='Kartendruck-Layouteditor';
  }

  function druckrahmen_load_html() {
    $this->Document->frames = $this->Document->load_frames(NULL, NULL);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    if(!$this->formvars['aktiverRahmen']){
      $this->formvars['aktiverRahmen'] = $frameid;
    }
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->selectedframe = $this->Document->load_frames(NULL, $this->formvars['aktiverRahmen']);
    if($this->Document->selectedframe != NULL){
      # Fonts auslesen

			include_(CLASSPATH . 'datendrucklayout.php');
			$ddl = new ddl($this->database);
			$this->Document->fonts = $ddl->get_fonts();
			$this->Document->din_formats = get_din_formats();

      if($this->Document->selectedframe[0]['headsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']))){
        $this->Document->headsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['headsrc']));
      }
      else{
        $this->Document->headsize[0] = 1;
        $this->Document->headsize[1] = 1;
      }
      if($this->Document->selectedframe[0]['refmapsrc'] != '' && file_exists(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']))){
        $this->Document->refmapsize = GetImageSize(DRUCKRAHMEN_PATH.basename($this->Document->selectedframe[0]['refmapsrc']));
      }
      else{
        $this->Document->refmapsize[0] = 1;
        $this->Document->refmapsize[1] = 1;
      }
      $this->Document->cent = $this->Document->selectedframe[0]['preis']%100;
      $this->Document->euro = ($this->Document->selectedframe[0]['preis'] - $this->Document->cent)/100;
      $this->Document->cent = str_pad ($this->Document->cent, 2, "0", STR_PAD_LEFT);

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->selectedframe[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->Document->selectedframe[0]['texts'][$i]['text']);
      }

      switch ($this->Document->selectedframe[0]['format']){
      	case 'A5hoch' : {
          $ratio = 420/595;
          $height = 595;
        } break;
        case 'A5quer' : {
          $ratio = 595/595;
          $height = 420/$ratio;
        } break;
        case 'A4hoch' : {
          $ratio = 595/595;
          $height = 842;
        } break;
        case 'A4quer' : {
          $ratio = 842/595;
          $height = 595/$ratio;
        } break;
        case 'A3hoch' : {
          $ratio = 842/595;
          $height = 842;
        } break;
        case 'A3quer' : {
          $ratio = 1191/595;
          $height = 842/$ratio;
        } break;
        case 'A2hoch' : {
          $ratio = 1191/595;
          $height = 1684/$ratio;
        } break;
        case 'A2quer' : {
          $ratio = 1684/595;
          $height = 1191/$ratio;
        } break;
        case 'A1hoch' : {
          $ratio = 1684/595;
          $height = 2384/$ratio;
        } break;
        case 'A1quer' : {
          $ratio = 2384/595;
          $height = 1684/$ratio;
        } break;
        case 'A0hoch' : {
          $ratio = 2384/595;
          $height = 3370/$ratio;
        } break;
        case 'A0quer' : {
          $ratio = 3370/595;
          $height = 2384/$ratio;
        } break;
      }
      $this->Document->headposx = $this->Document->selectedframe[0]['headposx']/$ratio;
      $this->Document->headposy = $this->Document->selectedframe[0]['headposy']/$ratio;
      $this->Document->headwidth = $this->Document->selectedframe[0]['headwidth']/$ratio;
      $this->Document->headheight = $this->Document->selectedframe[0]['headheight']/$ratio;
      $this->Document->mapposx = $this->Document->selectedframe[0]['mapposx']/$ratio;
      $this->Document->mapposy = $this->Document->selectedframe[0]['mapposy']/$ratio;
      $this->Document->mapwidth = $this->Document->selectedframe[0]['mapwidth']/$ratio;
      $this->Document->mapheight = $this->Document->selectedframe[0]['mapheight']/$ratio;
      $this->Document->refmapposx = $this->Document->selectedframe[0]['refmapposx']/$ratio;
      $this->Document->refmapposy = $this->Document->selectedframe[0]['refmapposy']/$ratio;
      $this->Document->refmapwidth = $this->Document->selectedframe[0]['refmapwidth']/$ratio;
      $this->Document->refmapheight = $this->Document->selectedframe[0]['refmapheight']/$ratio;
      $this->Document->refposx = $this->Document->selectedframe[0]['refposx']/$ratio;
      $this->Document->refposy = $this->Document->selectedframe[0]['refposy']/$ratio;
      $this->Document->refwidth = $this->Document->selectedframe[0]['refwidth']/$ratio;
      $this->Document->refheight = $this->Document->selectedframe[0]['refheight']/$ratio;
      $this->Document->dateposx = $this->Document->selectedframe[0]['dateposx']/$ratio;
      $this->Document->dateposy = $this->Document->selectedframe[0]['dateposy']/$ratio;
      $this->Document->datesize = $this->Document->selectedframe[0]['datesize']/$ratio;
      $this->Document->dateposy = $this->Document->dateposy - $this->Document->datesize/4;
      $this->Document->scaleposx = $this->Document->selectedframe[0]['scaleposx']/$ratio;
      $this->Document->scaleposy = $this->Document->selectedframe[0]['scaleposy']/$ratio;
      $this->Document->scalesize = $this->Document->selectedframe[0]['scalesize']/$ratio;
      $this->Document->scaleposy = $this->Document->scaleposy - $this->Document->scalesize/4;
      $this->Document->oscaleposx = $this->Document->selectedframe[0]['oscaleposx']/$ratio;
      $this->Document->oscaleposy = $this->Document->selectedframe[0]['oscaleposy']/$ratio;
      $this->Document->oscalesize = $this->Document->selectedframe[0]['oscalesize']/$ratio;
      $this->Document->oscaleposy = $this->Document->oscaleposy - $this->Document->oscalesize/4;
      $this->Document->gemarkungposx = $this->Document->selectedframe[0]['gemarkungposx']/$ratio;
      $this->Document->gemarkungposy = $this->Document->selectedframe[0]['gemarkungposy']/$ratio;
      $this->Document->gemarkungsize = $this->Document->selectedframe[0]['gemarkungsize']/$ratio;
      $this->Document->gemarkungposy = $this->Document->gemarkungposy - $this->Document->gemarkungsize/4;
      $this->Document->flurposx = $this->Document->selectedframe[0]['flurposx']/$ratio;
      $this->Document->flurposy = $this->Document->selectedframe[0]['flurposy']/$ratio;
      $this->Document->flursize = $this->Document->selectedframe[0]['flursize']/$ratio;
      $this->Document->flurposy = $this->Document->flurposy - $this->Document->flursize/4;
      $this->Document->userposx = $this->Document->selectedframe[0]['userposx']/$ratio;
      $this->Document->userposy = $this->Document->selectedframe[0]['userposy']/$ratio;
      $this->Document->usersize = $this->Document->selectedframe[0]['usersize']/$ratio;
      $this->Document->userposy = $this->Document->userposy - $this->Document->usersize/4;

      for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){
        $this->Document->textposx[$i] = $this->Document->selectedframe[0]['texts'][$i]['posx']/$ratio;
        $this->Document->textposy[$i] = $this->Document->selectedframe[0]['texts'][$i]['posy']/$ratio;
        $this->Document->textsize[$i] = $this->Document->selectedframe[0]['texts'][$i]['size']/$ratio;
        $this->Document->textposy[$i] = $this->Document->textposy[$i] - $this->Document->textsize[$i]/4;
      }

      $this->Document->legendposx = $this->Document->selectedframe[0]['legendposx']/$ratio;
      $this->Document->legendposy = $this->Document->selectedframe[0]['legendposy']/$ratio;
      $this->Document->legendsize = $this->Document->selectedframe[0]['legendsize']/$ratio;
      $this->Document->legendwidth = $this->Document->legendsize * 13;
      $this->Document->legendheight = $this->Document->legendwidth * 2;

      $this->Document->height = $height;
    }

    $this->main='druckrahmen_html.php';
    $this->titel='Druckrahmenverwaltung';
  }

  function metadaten_uebersicht(){
  	# Abfragen der Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Name';
    }
    $this->layerdaten = $this->Stelle->getLayers(NULL, $this->formvars['order']);
    $this->titel='Metadaten Erfassen/Bearbeiten';
    $this->main='metadaten_layer.php';
    $this->output();
  }

  function metadaten_suche(){
    $this->titel='Metadaten Recherchieren';
    $this->main='metadaten_search.php';
    $this->output();
  }

  function metadaten_generieren($layer_id){
		include_(CLASSPATH.'metadaten_csw.php');
  	$md = new metadata_csw($this->database);
  	$md->make_xml($layer_id);
  	return $md->create_csw_insert();
  }

  function metadatenSuchForm() {
    if ($this->formvars['expertensuche']) {
      $this->titel='Metadaten Expertensuche';
      $this->main='metadatensuchformular.php';
    }
    else {
      $this->titel='Metadatensuche';
      $this->main='metadatensuchformular.php';
    }
    $this->loadMap('DataBase');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

	function druckausschnittswahl($loadmapsource) {
		global $selectable_scales;
		$this->selectable_scales = array_reverse($selectable_scales);
		$saved_scale = $this->reduce_mapwidth(10);
		$this->main = "druckausschnittswahl.php";
		$this->Document = new Document($this->database);
		# aktuellen Kartenausschnitt laden + zeichnen!
		$this->noMinMaxScaling = $this->formvars['no_minmax_scaling'];
		if ($this->formvars['neuladen']) {
			$this->neuLaden();
		}
		else {
			$this->loadMap($loadmapsource);
		}
		# Redlining Polygone
		$this->addRedlining();

		if ($saved_scale != NULL) {
			# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
			$this->scaleMap($saved_scale);
		}
		# zoom_to_max_layer_extent
		if ($this->formvars['zoom_layer_id'] != '') {
			# Kartendrucklayouts laden
			$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
		}
    $this->Document->frames = $this->Document->load_frames($this->Stelle->id, NULL);
		if (count($this->Document->frames) == 0) {
			$this->main = 'map.php';
			$this->add_message('warning', $this->strNoMapPrintFrame);
		}
		else {
			# aktuelles Layout laden
			if($this->formvars['angle'] == ''){
				$this->formvars['angle'] = 0;
			}
			if($this->formvars['aktiverRahmen']){
				$this->Document->save_active_frame($this->formvars['aktiverRahmen'], $this->user->id, $this->Stelle->id);
			}
			$frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
			$this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
	
			if($this->user->rolle->epsg_code == 4326){
				$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
				$this->meter_pro_einheit = degree2meter($center_y);
			}
			else{
				$this->meter_pro_einheit = 1;
			}
			if($this->user->rolle->print_scale != 'auto')$this->formvars['printscale'] = $this->user->rolle->print_scale;
			if($this->formvars['printscale'] == ''){			# einen geeigneten Druckmaßstab berechnen
				$dx = $this->map->extent->maxx-$this->map->extent->minx;
				$dy = $this->map->extent->maxy-$this->map->extent->miny;
				$cal_scale_height = $dy * $this->meter_pro_einheit / 0.00035277 / $this->Document->activeframe[0]["mapheight"];
				$cal_scale_width = $dx * $this->meter_pro_einheit / 0.00035277 / $this->Document->activeframe[0]["mapwidth"];
				if($cal_scale_width > $cal_scale_height)$cal_scale = $cal_scale_height;
				else $cal_scale = $cal_scale_width;
				foreach($this->selectable_scales as $scale){
					if($cal_scale > $scale){
						$this->formvars['printscale'] = $scale;
						break;
					}
				}
			}
	
			# alle Druckausschnitte der Rolle laden
			$this->Document->ausschnitte = $this->Document->load_ausschnitte($this->Stelle->id, $this->user->id, NULL);
			# Ausschnitt laden
			if($this->formvars['go'] == 'Druckausschnitt_laden' AND $this->formvars['druckausschnitt'] != ''){
				$this->Document->ausschnitt = $this->Document->load_ausschnitte($this->Stelle->id, $this->user->id, $this->formvars['druckausschnitt']);
				# Druckrahmen setzen
				$this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $this->Document->ausschnitt[0]['frame_id']);
				# Extent setzen
				$width = $this->Document->activeframe[0]['mapwidth'] * $this->Document->ausschnitt[0]['print_scale']/$this->meter_pro_einheit * 0.00035277;
				$height = $this->Document->activeframe[0]['mapheight'] * $this->Document->ausschnitt[0]['print_scale']/$this->meter_pro_einheit * 0.00035277;
				$rect= rectObj(
					$this->Document->ausschnitt[0]['center_x'] - $width/2,
					$this->Document->ausschnitt[0]['center_y'] - $height/2,
					$this->Document->ausschnitt[0]['center_x'] + $width/2,
					$this->Document->ausschnitt[0]['center_y'] + $height/2
				);
				if($this->Document->ausschnitt[0]['epsg_code'] != '' AND $this->Document->ausschnitt[0]['epsg_code'] != $this->user->rolle->epsg_code){
					$projFROM = new projectionObj("init=epsg:" . $this->Document->ausschnitt[0]['epsg_code']);
					$projTO = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
					$rect->project($projFROM, $projTO);
				}
				if($this->user->rolle->epsg_code == 4326)$rand = 10/10000;
				else $rand = 10;
				$this->map->setextent($rect->minx-$rand,$rect->miny-$rand,$rect->maxx+$rand,$rect->maxy+$rand);
				if(MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $this->map->scaledenom;
				}
				else {
					$this->map_scaledenom = $this->map->scale;
				}
				# Position setzen
				$this->formvars['refpoint_x'] = $this->formvars['center_x'] = $rect->minx + $width/2;
				$this->formvars['refpoint_y'] = $this->formvars['center_y'] = $rect->miny + $height/2;
				# Druckmaßstab setzen
				$this->formvars['printscale'] = $this->Document->ausschnitt[0]['print_scale'];
				# Drehwinkel setzen
				$this->formvars['angle'] = $this->Document->ausschnitt[0]['angle'];
			}
		}

    # Wenn Navigiert werden soll, wird eine eventuell schon gesetzte Position
    # in Weltkoordinaten umgerechnet und danach wieder zurück.
		$currenttime=date('Y-m-d H:i:s',time());
    if ($this->formvars['CMD']!='') {
    	$this->navMap($this->formvars['CMD']);
    }
		$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		$this->saveMap('');
		$this->drawMap();
    $this->output();
  }

  function druckausschnitt_löschen($loadmapsource){
    $this->Document = new Document($this->database);
    $this->Document->delete_ausschnitt($this->Stelle->id, $this->user->id, $this->formvars['druckausschnitt']);
    $this->formvars['druckausschnitt'] = '';
    $this->druckausschnittswahl($loadmapsource);
  }

	function druckausschnitt_speichern($loadmapsource) {
		$this->loadMap($loadmapsource);
		$this->Document = new Document($this->database);
		$this->Document->save_ausschnitt($this->Stelle->id, $this->user->id, $this->formvars['name'], $this->user->rolle->epsg_code, $this->formvars['center_x'], $this->formvars['center_y'], $this->formvars['printscale'], $this->formvars['angle'], $this->formvars['aktiverRahmen']);
		$this->druckausschnittswahl($loadmapsource);
	}

  function druckvorschau(){
    $this->previewfile = $this->createMapPDF($this->formvars['aktiverRahmen'], true);
    $this->main = 'druckvorschau.php';
    $this->titel = 'Druckvorschau';
  }

  function addwatermark($frame) {
    $text = $frame['watermark'];
    $textsize = $frame['watermarksize'];
    $textposx = $frame['watermarkposx'];
    $angle = $frame['watermarkangle'];
    $textposy = $frame['mapheight'] - $frame['watermarkposy'];
    $mapimage = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
    $red = ImageColorAllocatealpha ($mapimage, 255, 0, 0, $frame['watermarktransparency']);
    imagettftext($mapimage, $textsize*$this->map_factor, $angle, $textposx*$this->map_factor, $textposy*$this->map_factor, $red, dirname(FONTSET).'/arial.ttf', $text);
    imagejpeg($mapimage,IMAGEPATH.basename($this->img['hauptkarte']), 100);
  }

  function createlegend($size, $all_active_layers = false){
    $this->map->resolution = 72;
    $this->map->legend->keysizex = $size*1.8*$this->map_factor;
    $this->map->legend->keysizey = $size*1.8*$this->map_factor;
    $this->map->legend->keyspacingx = $size*$this->map_factor;
    $this->map->legend->keyspacingy = $size*0.83*$this->map_factor;
    $this->map->legend->label->size = $size*$this->map_factor;
		$this->map->legend->label->font = 'arial';
    $this->map->legend->label->position = MS_CC;
    #$this->map->legend->label->set("offsetx", $size*-5*$this->map_factor);
    #$this->map->legend->label->set("offsety", -1*$size*$this->map_factor);
    $this->map->legend->label->color->setRGB(0,0,0);
    #$this->map->legend->outlinecolor->setRGB(0,0,0);
    $legendmapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    $legendmapDB->nurAktiveLayer = 1;
    $layerset = $legendmapDB->read_Layer(1, $this->Stelle->useLayerAliases);
		$rollenlayer = $legendmapDB->read_RollenLayer();
		$layerset['list'] = array_merge($layerset['list'], $rollenlayer);
    for($i = 0; $i < $this->map->numlayers; $i++){
      $layer = $this->map->getlayer($i);
      $layer->status = 0;
    }
    $scale = $this->map_scaledenom * $this->map_factor / 1.414;
    $legendimage = imagecreatetruecolor(1,1);
    $backgroundColor = ImageColorAllocate($legendimage, 255, 255, 255);
    imagefill ($legendimage, 0, 0, $backgroundColor);

    for($i = 0; $i < count($layerset['list']); $i++){
      if($layerset['list'][$i]['aktivStatus'] != 0){
        if(($layerset['list'][$i]['minscale'] < $scale OR $layerset['list'][$i]['minscale'] == 0) AND ($layerset['list'][$i]['maxscale'] > $scale OR $layerset['list'][$i]['maxscale'] == 0)){
					if($all_active_layers OR $this->formvars['legendlayer'.$layerset['list'][$i]['Layer_ID']] == 'on'){
						if($layerset['list'][$i]['Name_or_alias'] != '' AND $this->Stelle->useLayerAliases)$name = $layerset['list'][$i]['Name_or_alias'];
						else $name = $layerset['list'][$i]['Name'];
						$layer = $this->map->getLayerByName($name);
						if($layerset['list'][$i]['showclasses']){
							for($j = 0; $j < $layer->numclasses; $j++){
								$class = $layer->getClass($j);
								if ($class->name == '') {
									$class->name = ' ';
								}
								$draw = true;
							}
						}
					}
        }
        if($draw == true){
          $layer->status = 1;
          if($layer->connectiontype != 7){
	          $classimage = $this->map->drawLegend();
	          $filename = $this->map_saveWebImage($classimage,'jpeg');
	          $newname = $this->user->id.basename($filename);
	          rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
	          $classimage = imagecreatefrompng(IMAGEPATH.$newname);
          }
	        else{
						$layer->connection = str_replace('jpeg', 'png', $layer->connection);
          	$layersection = substr($layer->connection, strpos(strtolower($layer->connection), 'layers')+7);
            $layersection = substr($layersection, 0, strpos($layersection, '&'));
            $layers = explode(',', $layersection);
            for($l = 0; $l < count($layers); $l++){
              $classimage = ImageCreateFromPNG($layer->connection.'&service=WMS&layer='.$layers[$l].'&request=GetLegendGraphic');
            }
          }
          $classheight = imagesy($classimage);
          $classwidth = imagesx($classimage);
          $textbox = imagettfbbox($size*$this->map_factor, 0, dirname(FONTSET).'/arial.ttf', $layer->name);
          $textwidth = $textbox[2] - $textbox[0] + $size*0.66*$this->map_factor;
          $layernameimage = imagecreatetruecolor($textwidth,$size*3.3*$this->map_factor);
          $backgroundColor = ImageColorAllocate ($layernameimage, 255, 255, 255);
          $black = ImageColorAllocate ($layernameimage, 0, 0, 0);
          imagefill ($layernameimage, 0, 0, $backgroundColor);
          imagettftext($layernameimage, $size*$this->map_factor, 0, 3, $size*2.55*$this->map_factor, $black, dirname(FONTSET).'/arial.ttf', umlaute_html($layer->name));
          $height = $classheight + imagesy($legendimage) + $size*3.66*$this->map_factor;
          if(imagesx($legendimage) > $textwidth){
            if($classwidth > imagesx($legendimage)){
              $width = $classwidth;
            }
            else{
              $width = imagesx($legendimage);
            }
          }
          else{
            if($textwidth > $classwidth){
              $width = $textwidth;
            }
            else{
              $width = $classwidth;
            }
          }
          $newlegendimage = imagecreatetruecolor($width+$size*1.55*$this->map_factor,$height);
          $backgroundColor = ImageColorAllocate ($newlegendimage, 255, 255, 255);
          imagefilledrectangle($newlegendimage, 0, 0, imagesx($newlegendimage), imagesy($newlegendimage), $backgroundColor);
          ImageCopy($newlegendimage, $layernameimage, 0, 0, 0, 0, imagesx($layernameimage), $size*3.3*$this->map_factor);
          if($layerset['list'][$i]['showclasses']){
            ImageCopy($newlegendimage, $classimage, $size*$this->map_factor, $size*3.3*$this->map_factor, 0, 0, imagesx($classimage), imagesy($classimage));
          }
          ImageCopy($newlegendimage, $legendimage, 0, $size*3.3*$this->map_factor+$classheight, 0, 0, imagesx($legendimage), imagesy($legendimage));
          $legendimage = $newlegendimage;

          $layer->status = 0;
          $draw = false;
          $classheight = 0;
        }
      }
    }
    $newlegendimage = imagecreatetruecolor(imagesx($legendimage)+$size*0.55*$this->map_factor,$size*3*$this->map_factor+imagesy($legendimage)+$size*0.55*$this->map_factor);
    $backgroundColor = ImageColorAllocate ($newlegendimage, 255, 255, 255);
    imagefilledrectangle($newlegendimage, 0, 0, imagesx($newlegendimage), imagesy($newlegendimage), $backgroundColor);
    ImageCopy($newlegendimage, $legendimage, $size*0.55*$this->map_factor, $size*3*$this->map_factor, 0, 0, imagesx($legendimage), imagesy($legendimage));
    $legendimage = $newlegendimage;
    $black = ImageColorAllocate ($legendimage, 0, 0, 0);
    imagettftext($legendimage, $size*1.1*$this->map_factor, 0, $size*0.55*$this->map_factor, $size*2.55*$this->map_factor, $black, dirname(FONTSET).'/arial_bold.ttf', 'Legende');
    imagesetthickness ($legendimage, 1*$this->map_factor);
    imagerectangle($legendimage, $this->map_factor, $this->map_factor, imagesx($legendimage)-$this->map_factor, imagesy($legendimage)-$this->map_factor, $black);
    $legendimagename = IMAGEPATH.rand(0, 1000000).'.jpg';
    imagejpeg($legendimage, $legendimagename, 100);
    $legend['width'] = imagesx($legendimage);
		$legend['height'] = imagesy($legendimage);
    $legend['name'] = $legendimagename;
    return $legend;
  }

  function getlegendimage($layerset, $class, $style_id){
    # liefert eine url zu einem Legendenbild eines Layers mit einem bestimmten Style
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$map = new mapObj(DEFAULTMAPFILE);
    $map->setextent(100,100,200,200);
    $map->width = 10;
    $map->height = 10;
    $map->web->imagepath = IMAGEPATH;
    $map->web->imageurl = IMAGEURL;
    $map->setSymbolSet(SYMBOLSET);
    $map->setFontSet(FONTSET);

    $layer = new layerObj($map);
    $layer->data = $layerset['Data'];
    $layer->status = MS_ON;
    $layer->template = ' ';
    $layer->name = 'test';
    $layer->type = $layerset['Datentyp'];
    $layer->setConnectionType($layerset['connectiontype'], '');
		$layer->connection = $layerset['connection'];
    $klasse = new ClassObj($layer);
    $klasse->status = MS_ON;
		if ($class != NULL) {
			$styles = $class['Style'];
		}
		else {
    	$styles[] = $mapDB->get_Style($style_id);
		}
		foreach ($styles as $dbStyle) {
			$style = new StyleObj($klasse);
			if (substr($dbStyle['symbolname'], 0, 1) == '[') {
				$style->updateFromString('STYLE SYMBOL ' .$dbStyle['symbolname']. ' END');
			}
			else {
				if ($dbStyle['symbolname']!='') {
					if (MAPSERVERVERSION < 800) {
						$style->symbolname = $dbStyle['symbolname'];
					}
					else {
						$style->setSymbolByName($map, $dbStyle['symbolname']);
					}
				}
				if ($dbStyle['symbol']>0) {
					$style->symbol = $dbStyle['symbol'];
				}
			}	
			if($dbStyle['width']!='') {
				$style->width = $dbStyle['width'];
			}
			if($dbStyle['size'] != ''){
				if (is_numeric($dbStyle['size'])) {
					$style->size = $dbStyle['size'];
				}
				else {
					$style->size = 16;		# Dummywert 16 da size-Attribut verwendet wird
				}
			}
			if($dbStyle['angle']!='') {
				$style->angle = $dbStyle['angle'];
			}
			if(MAPSERVERVERSION >= 620) {
				if($dbStyle['geomtransform'] != '') {
					$style->setGeomTransform($dbStyle['geomtransform']);
				}
				if ($dbStyle['pattern'] != '') {
					$style->updateFromString("STYLE PATTERN " . $dbStyle['pattern']." END END");		
					$style->linecap = 'butt';
				}
				if($dbStyle['gap'] != '') {
					$style->gap = $dbStyle['gap'];
				}
				if($dbStyle['initialgap'] != '') {
					$style->initialgap = $dbStyle['initialgap'];
				}
				if($dbStyle['linecap'] != '') {
					$style->linecap = constant('MS_CJC_'.strtoupper($dbStyle['linecap']));
				}
				if($dbStyle['linejoin'] != '') {
					$style->linejoin = constant('MS_CJC_'.strtoupper($dbStyle['linejoin']));
				}
				if($dbStyle['linejoinmaxsize'] != '') {
					$style->linejoinmaxsize = $dbStyle['linejoinmaxsize'];
				}
			}
			#######################################################
			if($layer->type > 0){
				if (MAPSERVERVERSION >= 800) {
					$symbol = $map->symbolset->getSymbol($style->symbol);
				}
				else {
					$symbol = $map->getSymbolObjectById($style->symbol);
				}
				if($symbol->type == 1005){ 	# 1005 == hatch
					$style->size = 2*$style->width;					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
					$style->maxsize = 2*$style->width;
				}
				else{
					if($dbStyle['size'] < 2)$style->size = 2;					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
					if($dbStyle['maxsize'] < 2)$style->maxsize = 2;
				}
			}
			else{
				$style->maxsize = $style->size;		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
			}
			#######################################################
			$RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');
			if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
			if (is_numeric($RGB[0])) {
				$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
			else {
				$style->updateFromString("STYLE COLOR [".$dbStyle['color']."] END");
			}
			if ($dbStyle['outlinecolor']!='') {
				$RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
				if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
				if (is_numeric($RGB[0])) {
					$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
				}
				else {
					$style->updateFromString("STYLE OUTLINECOLOR [" . $dbStyle['outlinecolor']."] END");
				}
			}

			if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
				if (MAPSERVERVERSION >= 800) {
					$style->updateFromString("STYLE OPACITY " . $dbStyle['opacity'] . " END");
				}
				else {
					$style->opacity = $dbStyle['opacity'];
				}
			}
			if($dbStyle['colorrange'] != ''){
				$newname = rand(0, 1000000).'.jpg';
				$this->colorramp(IMAGEPATH.$newname, 25, 18, $dbStyle['colorrange']);
			}
		}
		if ($newname == '') {
			if (MAPSERVERVERSION >= 800) {
				$image = $klasse->createLegendIcon($map, $layer, 25,18);
			}
			else {
				$image = $klasse->createLegendIcon(25,18);
			}
			$filename = $this->map_saveWebImage($image,'jpeg');
			$newname = $this->user->id.basename($filename);
			rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
		}
    return $newname;
  }

	function notizErfassung() {
		include_once (CLASSPATH . 'notiz.php');
		# Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
		if ($this->formvars['oid']=='') {
			$this->titel = 'Neue Notiz';
		}
		else {
			$this->titel = 'Notiz Bearbeiten';
		}
		$this->main = "notizerfassung.php";
		# aktuellen Kartenausschnitt laden + zeichnen!
		$this->loadMap('DataBase');
		$this->notizen = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
		$this->notizen->anlegenKategorien = $this->notizen->getKategorie(NULL, $this->Stelle->id, NULL, 'true', NULL);
		if ($this->formvars['CMD'] != '') {
			$this->navMap($this->formvars['CMD']);
			$this->saveMap('');
			$currenttime=date('Y-m-d H:i:s',time());
			$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
			$this->drawMap();
		}
    else {
      # Wenn nicht navigiert wurde, also kein cmd knopf gedrückt wurde,
      # und eine oid angegeben wurde, werden die Daten der notiz aus der Datenbank gelesen.
      if ($this->formvars['oid']!='') {
        $ret=$this->notizen->getNotizen($this->formvars['oid'],'','','','');
        $this->formvars['notiz']=$ret[1][0]['notiz'];
        $this->formvars['kategorie_id']=$ret[1][0]['kategorie_id'];
        $this->formvars['person']=$ret[1][0]['person'];
        $this->formvars['datum']=$ret[1][0]['datum'];
        $this->notizen->notizKategorie = $this->notizen->getKategorie($ret[1][0]['kategorie_id'], NULL, NULL, NULL, NULL);
        # Bildung der Textposition zur SVG-Ausgabe
        if(strpos($ret[1][0]['textgeom'], 'POINT') === false){    # Polygon
          $PolygonAsSVG = transformCoordsSVG($ret[1][0]['svggeom']);
          $this->formvars['newpath'] = $PolygonAsSVG;
          $this->formvars['newpathwkt'] = $ret[1][0]['textgeom'];
          $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
        }
        else{   # Punkt
          $point_teil=strrpos($ret[1][0]['textgeom'],'(')+1;
          $point_paar=substr($ret[1][0]['textgeom'],$point_teil,count($point_teil)-2);
          $point_xy=explode(' ',$point_paar);
          $this->formvars['loc_x']=$point_xy[0];
          $this->formvars['loc_y']=$point_xy[1];
        }
      }
      $this->saveMap('');
      $this->drawMap();
    }
    if ($this->formvars['person']=='') {
      $this->formvars['person']=$this->user->Name;
    }
    $this->output();
  }

	function notizSpeichern() {
		include_(CLASSPATH.'notiz.php');
		# Zusammensetzen der übergebenen Parameter für die Textposition
		#echo 'formvars[loc_x, loc_y]: '.$this->formvars['loc_x'].', '.$this->formvars['loc_x'];
		if ($this->formvars['loc_x'] > 0 AND $this->formvars['loc_y'] > 0) {
			$location_x = $this->formvars['loc_x'];
			$location_y = $this->formvars['loc_y'];
			$this->formvars['textposition'] = "POINT(" . $location_x . " " . $location_y . ")";
			#echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
		}
		elseif ($this->formvars['newpathwkt'] != '') {
			$this->formvars['textposition'] = $this->formvars['newpathwkt'];
			#echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
		}
		else {
			$this->formvars['textposition'] = "";
		}
		# 2006-06-21 pk
		# aktuellen EPSG Code der Stelle in Variable formvar übergeben
		$this->formvars['epsg_von'] = $this->user->rolle->epsg_code;

		# Notizobjekt erzeugen
		$notiz = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);

		# 1. Prüfen der Eingabewerte
		$this->formvars['stelle_id'] = $this->Stelle->id;
		$ret = $notiz->pruefeEingabedaten($this->formvars);
		if ($ret[0]) {
			# Es wurde ein oder mehrere Fehler bei den Eingabewerten gefunden
			$this->Meldung = $ret[1];
		}
		else {
			# Eingabewerte fehlerfrei
			if ($this->formvars['oid'] == '') {
				# 2. Eintragen Neue Notiz
				$ret = $notiz->eintragenNeueNotiz($this->formvars);
				if ($ret[0]) {
					# 2.1 Eintragung fehlerhaft
					$this->Meldung = $ret[1];
				}
				else {
					#  2.2 Eintragung erfolgreich
					$alertmsg = 'Notiz erfolgreich in die Datenbank eingetragen!';
					$this->formvars['pathx'] = '';
					$this->formvars['loc_x'] = '';
					$this->formvars['pathy'] = '';
					$this->formvars['loc_y'] = '';
					$this->formvars['umring'] = '';
					$this->formvars['textposition'] = '';
				}
			}
			else {
				# 3. Notiz Aktualisieren
				$ret = $notiz->aktualisierenNotiz($this->formvars['oid'],$this->formvars);
				if ($ret[0]) {
					# 3.1 Eintragung fehlerhaft
					$this->Meldung = $ret[1];
				}
				else {
					# 3.2 Aktualisierung erfolgreich
					$alertmsg = 'Notiz erfolgreich in die Datenbank aktualisiert!';
				}
			}
		}
		if ($alertmsg != '') {
			$this->add_message('notice', $alertmsg);
		}
		$this->notizErfassung();
	}

  function notizLoeschen($oid){
		include_(CLASSPATH.'notiz.php');
    $notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $notiz->NotizLoeschen($oid);
  }

	function notizKatVerwaltung() {
		$this->Kat2Stelle = array();
		include_once (CLASSPATH . 'notiz.php');
		$this->Stellen = $this->Stelle->getStellen('Bezeichnung');
		$this->notiz = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
		$this->AllKat = $this->notiz->selectKategorie('', '', '');
		if ($this->formvars['kategorie_id'] != '') {
			$this->Kat = $this->notiz->getKategorie($this->formvars['kategorie_id'], '', '', '', '');
			$this->Kat2Stelle = $this->notiz->selectKat2stelle($this->formvars['kategorie_id']);
		}
		$this->titel = 'Notizkategorienverwaltung';
		$this->main = 'Kat_bearbeiten.php';
		$this->output();
	} # END of funtion notizKatVerwaltung

	function notizKategoriehinzufügen() {
		include_(CLASSPATH . 'notiz.php');
		$this->notiz = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
		$this->notiz->insertKategorie($this->formvars['newKategorie']);
		$kat = $this->notiz->selectKategorie('', $this->formvars['newKategorie'], '');
		$this->formvars['kategorie_id'] = $kat[0]['id'];
		$this->notizKatVerwaltung();
	} # END of function notizKathinzufügen

	function notizKategorieAendern() {
		include_(CLASSPATH . 'notiz.php');
		$this->notiz = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
		if ($this->formvars['kategorie_id'] != '') {
			$this->notiz->notizKategorieAenderung($this->formvars);
		}
		$this->notizKatVerwaltung();
	} # END of function notizKategorieAendern

	function notizKategorieLoeschen() {
		include_(CLASSPATH . 'notiz.php');
		$this->notiz = new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
		$this->notiz->notizKategorieLoeschen($this->formvars['kategorie_id'], $this->formvars['plus_notiz']);
		$max_id = $this->notiz->selectKategorie('', '', '');
		$this->formvars['kategorie_id'] = $max_id[0]['id'];
		$this->notizKatVerwaltung();
	} # END of function notizKategorieLoeschen

  function metadatenblattanzeige() {
		include_(CLASSPATH.'metadaten_csw.php');
    # Zuweisen von Titel und Layoutdatei
    $this->titel='Metadatenblattanzeige';
    $this->main='Metadatenblatt.php';
    # Abfragen der Metadaten in der Datenbank
    $this->metadaten=new metadatensatz('',$this->pgdatabase);
    $ret=$this->metadaten->getMetadaten($this->formvars);
    if ($ret[0]) {
      $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
    }
    else {
      # Zuweisen der Werte des abgefragten Datensatzes zur Variable für die Anzeige in Layoutdatei
      $this->metadataset=$ret[1][0];
    }
    # Ausgabe an den Client
    $this->output();
  }

  function metadatensatzspeichern() {
    include_(CLASSPATH.'metadaten.php');
    $metadatensatz=new metadatensatz($this->formvars['mdfileid'],$this->pgdatabase);
    $ret=$metadatensatz->checkMetadata($this->formvars);
    if ($ret[0]) {
      # Fehler in den Metadaten oder es fehlen welche
      $this->Fehlermeldung='Fehler:'.$ret[1];
    }
    else {
      $ret=$metadatensatz->speichern($ret[1]);
      if ($ret[0]) {
        $this->Fehlermeldung='Der Metadatensatz konnte nicht gespeichert werden.<br>'.$ret[1];
      }
    }
    $this->metadateneingabe();
  }

	function deleteDokument($path, $doc_path, $doc_url, $only_thumb = false){
		if (is_string($path) AND strpos($path, '[') !== false){
			$paths = json_decode($path);
		}
		if (is_array($paths)) {		// Array-Datentyp
			foreach ($paths as $path) {
				$this->deleteDokument($path, $doc_path, $doc_url, $only_thumb);
			}
		}
		else {
			if ($path != '') {
				if ($doc_url != '') {
					$path = url2filepath($path, $doc_path, $doc_url);			# Dokument mit URL
				}
				else {
					$parts = explode('&original_name', $path);
					$path = array_shift($parts);
				}
				if (!$only_thumb AND file_exists($path)) {
					unlink($path);
				}
				$pathinfo = pathinfo($path);
				if (file_exists($pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_thumb.jpg')) {
					unlink($pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_thumb.jpg');
				}
			}
		}
	}

	function is_local_file($pfad) {
		return (strpos($pfad, '&original_name=') !== false);
	}

	function create_dokument_vorschau($doc_type, $pathinfo) {
		if ($doc_type == 'local_img') {
			# für lokale Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '_thumb.jpg';
			if (!file_exists($thumbname)) {
				$command = IMAGEMAGICKPATH . 'convert -filter Hanning "' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'] . '"[0] -quality 75 -background white -flatten -resize ' . PREVIEW_IMAGE_WIDTH . 'x1000\> "' . $thumbname . '"';
				#echo 'Erzeuge Thumbnail mit commando: ' . $command;
				exec($command);
			}
		}
		else {
			#	alle anderen Dokumenttypen oder Dateien auf fremden Servern bekommen entsprechende Dokumentensymbole als Vorschaubild
			$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
			$blue = ImageColorAllocate ($image, 26, 87, 150);
			if (strlen(strtolower($pathinfo['extension'])) > 3) {
				$xoffset = 4;
			}
			imagettftext($image, 12, 0, 23-$xoffset, 34, $blue, WWWROOT.APPLVERSION.'fonts/SourceSansPro-Semibold.ttf', $pathinfo['extension']);
			$thumbname = IMAGEPATH.rand(0,100000).'.gif';
			imagegif($image, $thumbname);
		}
		return $thumbname;
	}

	/**
	 * Function create a preview image of a document or an pictogram for documents
	 * from which we can not create an image depending on the type of the document
	 * and return the infos about this document type and preview file.
	 * @param string $value Wert der in dem Dokumentattribut in der Datenbank steht.
	 * @param string $document_path Pfad für Dokumente in dem Layer.
	 * @param string $document_url URL für den Zugriff auf die Dokumente von außen.
	 * @param string $datatype Datentyp des Dokumentattributs
	 * @param int 	 $layer_id Layer-ID
	 * @param string $oid ID des Datensatzes
	 * @param string $name Name des Dokumentattributs
	 * @return array[ Array with values about doc type and preview with the following elements:
	 *	'doc_src' => string, URL für den Zugriff auf die Datei von außen
	 *	'doc_type' => string, Art des Dokumentes und damit auch der typ der Dokumentenvorschau
	 *	'thumb_src' => string, URL auf das Vorschaubild
	 *	'$original_name' => string, Name der Datei, die original hochgeladen wurde.
	 *	'target' => string, Ob der Link auf das Dokument im gleichen Tab aufgehen soll (leer) oder in einem neuen Tab oder Fenster (_blank)
	 *	'$preview_filesize' => string, Größe der Datei des Dokumentes
	 * ]
	 * Fälle von dokument_vorschau
	 * doc_type = local_img
	 * 	- Datei ist auf dem Server, Thumb vom Bild muss erzeugt werden, doc_src auf document_loader, mit mouseover
	 * 	- Datei ist auf dem Server, Thumb vom Bild muss erzeugt werden, doc_src auf download alias, mit mouseover
	 * doc_type = local_doc
	 * 	- Datei ist auf dem Server, Thumb vom Piktogram mit oder ohne Extension im Text muss erzeugt werden und Kopie nach IMAGEPATH, doc_src auf IMAGEPATH
	 * 	- Datei ist auf dem Server, Thumb vom Piktogram mit oder ohne Extension im Text muss erzeugt werden und Kopie nach IMAGEPATH, doc_src auf download alias
   * doc_type = videostream
	 *  - egal ob lokal oder remote, Es ist ein Video, Anzeige des Videos ohne Vorschau, Link auf die Datei direkt über die URL
	 * doc_type = remote_url
	 *  - Datei ist nicht auf dem Server (Remote URL), Link auf die Datei direkt über die URL
	 * 		- Vorschau als Piktogram mit Extension
	 * 		- Vorschau als Piktogram ohne Extension
	 */
	function get_dokument_vorschau($value, $document_path, $document_url, $datatype = NULL, $layer_id = NULL, $oid = NULL, $name = NULL) {
		$doc_src = $doc_type = $thumb_src = $original_name = $target = $filesize = '';

		if ($datatype != 'bytea') {
			$pfadteil = explode('&original_name=', $value);
			$dateipfad = $pfadteil[0];
			$pathinfo = pathinfo($dateipfad);
			$type = strtolower($pathinfo['extension']);

			if ($document_url != '') {
				if (in_array($type, array('mp4'))) {
					$doc_type = 'videostream';
				}
				else if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url($document_url, PHP_URL_HOST)) {
					# die URL verweist auf einen anderen Server
					$doc_type = 'remote_url';
				}
				$dateipfad = url2filepath($dateipfad, $document_path, $document_url);
			}
			if (file_exists($dateipfad) OR $doc_type != '') {
				$pathinfo = pathinfo($dateipfad);
				if ($doc_type == '') {
					$type = strtolower($pathinfo['extension']);
					if (in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf'))) {
						$doc_type = 'local_img';
					}
					else {
						$doc_type = 'local_doc';
					}
				}

				$thumbname = $this->create_dokument_vorschau($doc_type, $pathinfo);

				if ($document_url != '') {
					$original_name = basename($dateipfad);
					$doc_src = $value; # URL zu der Datei (komplette URL steht schon in $value)
					$target = 'target="_blank"';
					if (dirname($thumbname).'/' == IMAGEPATH){
						$thumbname = IMAGEURL.basename($thumbname);
					}
					else {
						$thumbname = dirname($value).'/'.basename($thumbname);
					}
					$thumb_src = $thumbname;
				}
				else {
					$original_name = $pfadteil[1];
					$this->allowed_documents[] = addslashes($dateipfad);
					$this->allowed_documents[] = addslashes($thumbname);
					$url = IMAGEURL . $this->document_loader_name . '?dokument=';
					$doc_src = $url . $value;
					$thumb_src = $url . $thumbname;
				}
				$filesize = human_filesize($dateipfad);
			}
		}
		else {
			$doc_type = 'local_doc';
			$pathinfo = pathinfo($value);
			$thumbname = $this->create_dokument_vorschau($doc_type, $pathinfo);
			$thumb_src = IMAGEURL . basename($thumbname);
			$original_name = pg_unescape_bytea($value);
			$doc_src = 'index.php?go=get_document&layer_id=' . $layer_id . '&oid=' . $oid . '&name=' . $name . '&csrf_token=' . $_SESSION['csrf_token'];
		}

		return array(
			'doc_src' => $doc_src,
			'doc_type' => $doc_type,
			'thumb_src' => $thumb_src,
			'original_name' => $original_name,
			'target' => $target,
			'filesize' => $filesize
		);
	}

	function write_document_loader(){
		$handle = fopen(IMAGEPATH.$this->document_loader_name, 'w');
		$code = '<?
			error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
			$allowed_documents = array(\''.implode('\',\'', $this->allowed_documents).'\');
			if(in_array($_REQUEST[\'dokument\'], $allowed_documents)){
				if(!array_key_exists(\'original_name\', $_REQUEST))$_REQUEST[\'original_name\'] = basename($_REQUEST[\'dokument\']);
				$type = strtolower(array_pop(explode(\'.\', $_REQUEST[\'dokument\'])));
				if(in_array($type, array(\'jpg\', \'gif\', \'png\')))header("Content-type: image/" . $type);
				else header("Content-type: application/" . $type);
				header("Content-Disposition: attachment; filename=\"" . $_REQUEST[\'original_name\']."\"");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: public");
				readfile($_REQUEST[\'dokument\']);
			}
		?>';
		fwrite($handle, $code);
		fclose($handle);
	}

  function exportMapToPDF() {
    # Abfrage der aktuellen Karte
    $this->loadMap('DataBase');
    $this->map->selectOutputFormat('jpeg');
    # Zeichnen der Karte
    $this->drawMap(true);
    # Einbinden der PDF Klassenbibliotheken
    include (CLASSPATH.'class.ezpdf.php');
    # Erzeugen neue Dokument-Klasse
    $Document=new Document($this->database);
    $this->Docu=$Document;

    # Erzeugen neue pdf-Klasse
    $pdf=new Cezpdf();
    $pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/Helvetica-Bold.afm');

    $massstab = explode('.', $this->map_scaledenom);
    $row = 712;

    $pdf->addText(50,$row,14,utf8_decode('Gemeinde: '.$this->Lagebezeichung['gemeindename'].'   Gemarkung: '.$this->Lagebezeichung['gemkgname'].'   Flur: '.$this->Lagebezeichung['flur']));
    $pdf->addText(50,$row-30,14,utf8_decode('Maßstab ca. 1:'.$massstab[0]));
    $pdf->addJpegFromFile(IMAGEPATH.basename($this->img['hauptkarte']),50,100,500);
    $this->pdf=$pdf;
    $this->mime_type='pdf';

    $dateipfad=IMAGEPATH;
    $currenttime = date('Y-m-d_H_i_s',time());
    $name = str_replace('ä', 'ae', $this->user->Name);
    $name = str_replace('ü', 'ue', $name);
    $name = str_replace('ö', 'oe', $name);
    $name = str_replace('Ä', 'Ae', $name);
    $name = str_replace('Ü', 'Ue', $name);
    $name = str_replace('Ö', 'Oe', $name);
    $name = str_replace('ß', 'ss', $name);
    $dateiname = $name.'-'.$currenttime.'_'.rand(0,99999999).'.pdf';
    $this->outputfile = $dateiname;
    $fp=fopen($dateipfad.$dateiname,'wb');
    fwrite($fp,$this->pdf->ezOutput());
    fclose($fp);

    $this->output();
  }

	function createMapPDF($frame_id, $preview, $fast = false) {
		$this->sanitize([
			'name' => 'text',
			'center_x' => 'float', 'center_y' => 'float',
			'refpoint_x' => 'float', 'refpoint_x' => 'float',
			'printscale' => 'int',
			'angle' => 'int',
			'aktiverRahmen' => 'int',
			'legend_extra' => 'int',
			'selected_layer_ids' => 'int_csv',
		]);
		$Document = new Document($this->database);
		$this->Docu = $Document;
		$this->Docu->activeframe = $this->Docu->load_frames(NULL, $frame_id);
		if ($this->Docu->activeframe[0]['dhk_call'] != ''){
			global $GUI;
			include_once(PLUGINS . 'alkis/model/kvwmap.php');
			$output = $this->ALKIS_Kartenauszug($this->Docu->activeframe[0], $this->formvars);
		}
		else {
			$selected_layer_ids = explode(',', $this->formvars['selected_layer_ids']);
			if (count($selected_layer_ids) > 0) {
				foreach ($selected_layer_ids AS $selected_layer_id) {
					if ($selected_layer_id > 0) {
						$this->formvars['thema' . $selected_layer_id] = 1;
					}
				}
				$this->user->rolle->setAktivLayer($this->formvars, $this->Stelle->id, $this->user->id);
			}

			# Abfrage der aktuellen Karte
			if ($this->formvars['post_map_factor']) {
				$this->map_factor = $this->formvars['post_map_factor'];
			}
			elseif ($this->formvars['map_factor'] != '') {
				$this->map_factor = $this->formvars['map_factor'];
			}
			else {
				$this->map_factor = MAPFACTOR;
			}

			# Wenn in der Anfrage für loadmapsource POST übergeben wurde, werden alle Kartenparameter aus formvars entnommen
			if ($this->formvars['loadmapsource']){
				$this->loadMap($this->formvars['loadmapsource']);
			}
			else {
				$this->loadMap('DataBase');
			}
			if ($this->formvars['INPUT_COORD'] != '') {
				$corners = explode(';', $this->formvars['INPUT_COORD']);
				$lu = sanitize(explode(',', $corners[0]), 'float');
				$ro = sanitize(explode(',', $corners[1]), 'float');
				$this->formvars['INPUT_COORD'] = implode(',', $lu) . ';' . implode(',', $ro);
				if ($this->formvars['epsg_code'] == '') {
					$this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
				}
				$this->zoom2coord();
			}
			# Karte
			if ($this->map->selectOutputFormat('jpeg_print') == 1){
				$this->map->selectOutputFormat('jpeg');
			}
			if ($fast == true) {
				# schnelle Druckausgabe ohne Druckausschnittswahl, beim Schnelldruck und im Druckrahmeneditor
				$this->formvars['referencemap'] = 1;
				$this->formvars['printscale'] = round($this->map_scaledenom);
				$this->formvars['refpoint_x'] = $this->formvars['center_x'] = $this->map->extent->minx + ($this->map->extent->maxx-$this->map->extent->minx)/2;
				$this->formvars['refpoint_y'] = $this->formvars['center_y'] = $this->map->extent->miny + ($this->map->extent->maxy-$this->map->extent->miny)/2;
				$this->formvars['worldprintwidth'] = $this->Docu->activeframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.00035277;
				$this->formvars['worldprintheight'] = $this->Docu->activeframe[0]['mapheight'] * $this->formvars['printscale'] * 0.00035277;
			}
			elseif ($this->user->rolle->print_scale != 'auto') {
				$this->user->rolle->savePrintScale($this->formvars['printscale']);
			}
			#echo $this->formvars['center_x'].'<br>';
			#echo $this->formvars['center_y'].'<br>';
			#echo $this->formvars['worldprintwidth'].'<br>';
			#echo $this->formvars['worldprintheight'].'<br>';
			$breite = $this->formvars['worldprintwidth']/2;
			$höhe = $this->formvars['worldprintheight']/2;

			if ($this->formvars['angle'] != 0){
				$diag = sqrt(pow($breite, 2) + pow($höhe, 2));
				$gamma = asin($breite/$diag);
				$alpha = deg2rad(90) - deg2rad(abs($this->formvars['angle'])) - $gamma;
				$bboxwidth = cos($alpha) * $diag;
				$alpha2 = $gamma - deg2rad(abs($this->formvars['angle']));
				$bboxheight = cos($alpha2) * $diag;
				$minx = $this->formvars['center_x'] - $bboxwidth;
				$miny = $this->formvars['center_y'] - $bboxheight;
				$maxx = $this->formvars['center_x'] + $bboxwidth;
				$maxy = $this->formvars['center_y'] + $bboxheight;
				$widthratio = $bboxwidth / $breite;
				$heightratio = $bboxheight / $höhe;
			}
			else{
				$minx = $this->formvars['center_x'] - $this->formvars['worldprintwidth']/2;
				$miny = $this->formvars['center_y'] - $this->formvars['worldprintheight']/2;
				$maxx = $this->formvars['center_x'] + $this->formvars['worldprintwidth']/2;
				$maxy = $this->formvars['center_y'] + $this->formvars['worldprintheight']/2;
				$widthratio = 1;
				$heightratio = 1;
			}
			$this->map->width = $this->Docu->activeframe[0]['mapwidth'] * $widthratio * $this->map_factor;
			$this->map->height = $this->Docu->activeframe[0]['mapheight'] * $heightratio * $this->map_factor;

			# copyright-layer aus dem Mapfile
			@$creditslayer = $this->map->getLayerByName('credits');
			if($creditslayer != false){
				$newcredits = new layerObj($this->map, $creditslayer);
				$feature = $newcredits->getShape(-1, 0);
				if(MAPSERVERVERSION > 500){
					$feature=$newcredits->getFeature(0,-1);
				}
				else{
					$feature=$newcredits->getShape(-1, 0);
				}
				$line = $feature->line(0);
				$point = $line->point(0);
				$point->setXY(0, $this->map->height - 2);
				$newcredits->addFeature($feature);
			}

			# Koordinatengitter-Layer aus dem Mapfile
			@$gridlayer = $this->map->getLayerByName('grid');
			if($gridlayer != false){
				$gridlayer->status = MS_ON;
			}

			$this->map->setextent($minx,$miny,$maxx,$maxy);

			# Welt-Eckkordinaten
			$this->minx = round($minx, 1);
			$this->miny = round($miny, 1);
			$this->maxx = round($maxx, 1);
			$this->maxy = round($maxy, 1);

			if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}

			$currenttime=date('Y-m-d H:i:s',time());
			# loggen der Druckausgabe
			if($preview == true){
				$this->user->rolle->setConsumeActivity($currenttime,'print_preview',$this->user->rolle->last_time_id);
			}
			else{
				$this->user->rolle->oGeorefExt->minx = $minx;
				$this->user->rolle->oGeorefExt->miny = $miny;
				$this->user->rolle->oGeorefExt->maxx = $maxx;
				$this->user->rolle->oGeorefExt->maxy = $maxy;
				$this->user->rolle->nImageWidth = $this->map->width;
				$this->user->rolle->nImageHeight = $this->map->height;
				$this->user->rolle->setConsumeActivity($currenttime,'print',$this->user->rolle->last_time_id);
				$this->user->rolle->setConsumeALK($currenttime, $this->Docu->activeframe[0]['id']);
			}

			$this->user->rolle->set_print_legend_separate($this->formvars['legend_extra']);

			# Redlining Polygone
			$this->addRedlining();

			/* *
			* Problem: Es gibt WMS, die trotz der Einstellung EXCEPTIONS=application/vnd.ogc.se_inimage kein Bild mit Fehlermeldung
			* schicken, sondern gar kein Bild bzw. nichts.
			* Der Fall und auch andere Fälle bei denen kein Bild zurück kommt müssen abgefangen werden.
			* 1) Es wird für jeden WMS Layer getestet ob der GetMap Request ein Bild liefert
			* 2) Wennn kein Bild geliefert wird, wird an Stelle der WMS online_url eine url zu einem Proxy gesetzt
			*    der die Fehlermeldung in ein Bild integriert und ausliefert
			* Eingefügt am 19.09.2008 von pk

			# Schritt 1)
			$extent=$this->map->extent;
			for ($l=1;$l<=$this->map->numlayers;$l++) {
				$layer=$this->map->getLayer($l);
				if($layer->status == 1 AND $layer->connectiontype == 7 AND $layer->connection!='') {
					$wmsRequestStr=$layer->connection.'&BBOX='.$extent->minx.','.$extent->miny.','.$extent->maxx.','.$extent->maxy.'&WIDTH='.$this->map->width.'&HEIGHT='.$this->map->height;
					if (getimagesize($wmsRequestStr)==false) {
						# Es handelt sich nicht um ein Bild,
						# Schritt 2)
						if (0) {
							echo 'Der Layer <b>'.$layer->name.'</b> kann in der Größe und Auflösung von '.strval(72*$this->map_factor).'dpi nicht für den Druck verwendet werden.';
							echo '<br><font size="-2">Die Anfrage: <a href="'.$wmsRequestStr.'" target="_blank">'.$wmsRequestStr.'</a> liefert kein Bild sondern die folgende Fehlermeldung:</font>';
							echo '<br><b><font color="#FF0000">'.trim(strip_tags(file_get_contents($wmsRequestStr))).'</font></b>';
							echo '<br>Wenden Sie sich an den WMS Anbieter oder drucken Sie die Karte in einem kleineren Format aus.<br><hr><br>';
						}
						$newConnection="http://www.gdi-service.de/wmstileproxy/index.php?online_resource_url=".str_replace("?","&",$layer->connection);
						$layer->set('connection',$newConnection);
					}
				}
			}
	*/
			#$this->saveMap('');		# wenn man das macht, wird der Druckextent als neuer Extent des Users übernommen
			#$this->debug->write("<p>Maßstab des Drucks:" . $this->map_scaledenom,4);
			$this->drawMap('true');

			if($this->formvars['angle'] != 0){
				$angle = -1 * $this->formvars['angle'];
				$image = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
				$rotatedimage = imagerotate($image, $angle, 0);
				$width = imagesx($rotatedimage);
				$height = imagesy($rotatedimage);
				$clipwidth = $this->Docu->activeframe[0]['mapwidth']*$this->map_factor;
				$clipheight = $this->Docu->activeframe[0]['mapheight']*$this->map_factor;
				$clipx = ($width - $clipwidth) / 2;
				$clipy = ($height - $clipheight) / 2;
				$clippedimage = imagecreatetruecolor($clipwidth, $clipheight);
				ImageCopy($clippedimage, $rotatedimage, 0, 0, $clipx, $clipy, $clipwidth, $clipheight);
				imagejpeg($clippedimage, IMAGEPATH.basename($this->img['hauptkarte']), 100);
			}

			# Übersichtskarte
			if($this->Docu->activeframe[0]['refmapfile'] AND $this->formvars['referencemap']){
				$refmapfile = DRUCKRAHMEN_PATH.$this->Docu->activeframe[0]['refmapfile'];
				$zoomfactor = $this->Docu->activeframe[0]['refzoom'];
				$refwidth = $this->Docu->activeframe[0]['refwidth']*$this->map_factor;
				$refheight = $this->Docu->activeframe[0]['refheight']*$this->map_factor;
				$width = $refwidth*$widthratio;
				$height = $refheight*$heightratio;
				$this->Docu->referencemap = $this->createReferenceMap($width, $height, $refwidth, $refheight, $angle, $minx,$miny,$maxx,$maxy, $zoomfactor, $refmapfile);
			}

			# Einbinden der PDF Klassenbibliotheken
			include (CLASSPATH . 'class.ezpdf.php');
			switch ($this->Docu->activeframe[0]['format']) {
				case "A5hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A5', 'portrait');
					$this->Docu->height = 595;
				} break;

				case "A5quer" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A5', 'landscape');
					$this->Docu->height = 420;
				} break;

				case "A4hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf();
					$this->Docu->height = 842;
				} break;

				case "A4quer" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A4', 'landscape');
					$this->Docu->height = 595;
				} break;

				case "A3hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A3', 'portrait');
					$this->Docu->height = 1191;
				} break;

				case "A3quer" : {
				 # Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A3','landscape');
					$this->Docu->height = 842;
				} break;

				case "A2hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A2', 'portrait');
					$this->Docu->height = 1684;
				} break;

				case "A2quer" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A2', 'landscape');
					$this->Docu->height = 1191;
				} break;

				case "A1hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A1', 'portrait');
					$this->Docu->height = 2384;
				} break;

				case "A1quer" : {
				 # Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A1','landscape');
					$this->Docu->height = 1684;
				} break;

				case "A0hoch" : {
					# Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A0', 'portrait');
					$this->Docu->height = 3370;
				} break;

				case "A0quer" : {
				 # Erzeugen neue pdf-Klasse
					$pdf=new Cezpdf('A0','landscape');
					$this->Docu->height = 2384;
				} break;
			}

			# Wasserzeichen hinzufügen
			if($this->Docu->activeframe[0]['watermark'] != ''){
				$this->addwatermark($this->Docu->activeframe[0]);
			}

			# Lagebezeichnung
			if(defined('LAGEBEZEICHNUNGSART') AND LAGEBEZEICHNUNGSART == 'Flurbezeichnung') {
				include_once(PLUGINS.'alkis/model/kataster.php');
				$flur = new Flur('','','',$this->pgdatabase);
				$bildmitte['rw']=$this->formvars['refpoint_x'];
				$bildmitte['hw']=$this->formvars['refpoint_y'];
				$this->lagebezeichnung = $flur->getBezeichnungFromPosition($bildmitte, $this->user->rolle->epsg_code);
			}

			# Hinzufügen des Hintergrundbildes als Druckrahmen
			$pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->Docu->activeframe[0]['headsrc']),$this->Docu->activeframe[0]['headposx'],$this->Docu->activeframe[0]['headposy'],$this->Docu->activeframe[0]['headwidth']);

			# Hinzufügen der vom MapServer produzierten Karte
			$pdf->addJpegFromFile(
				IMAGEPATH . basename($this->img['hauptkarte']),
				$this->Docu->activeframe[0]['mapposx'],
				$this->Docu->activeframe[0]['mapposy'],
				$this->Docu->activeframe[0]['mapwidth'],
				$this->Docu->activeframe[0]['mapheight']
			);

			# Rechteck um die Karte
			$posx1 = $this->Docu->activeframe[0]['mapposx'];
			$posy1 = $this->Docu->activeframe[0]['mapposy'];
			$pdf->rectangle($posx1, $posy1, $this->Docu->activeframe[0]['mapwidth'], $this->Docu->activeframe[0]['mapheight']);

			# Hinzufügen der Referenzkarte, wenn eine angegeben ist.
			if($this->Docu->activeframe[0]['refmapfile'] AND $this->formvars['referencemap']){
				$pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->Docu->activeframe[0]['refmapsrc']),$this->Docu->activeframe[0]['refmapposx'],$this->Docu->activeframe[0]['refmapposy'],$this->Docu->activeframe[0]['refmapwidth']);
				$pdf->addJpegFromFile(IMAGEPATH.basename($this->Docu->referencemap),$this->Docu->activeframe[0]['refposx'],$this->Docu->activeframe[0]['refposy'],$this->Docu->activeframe[0]['refwidth'], $this->Docu->activeframe[0]['refheight']);
			}

			# Attribute
			$this->gemeinde = utf8_decode($this->lagebezeichnung[1]['gemeindename'].' ('.$this->lagebezeichnung[1]['gemeinde'].')');
			$this->gemarkung = utf8_decode($this->lagebezeichnung[1]['gemkgname'].' ('.$this->lagebezeichnung[1]['gemkgschl'].')');
			$this->flur = utf8_decode($this->lagebezeichnung[1]['flur']);
			$this->flurstueck = utf8_decode($this->lagebezeichnung[1]['flurst']);
			$this->lage = utf8_decode($this->lagebezeichnung[1]['strasse']).' '.$this->lagebezeichnung[1]['hausnummer'];
			$this->date = date("d.m.Y");
			$this->scale = $this->formvars['printscale'];

			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_date']);
			if($this->Docu->activeframe[0]['datesize'] > 0)$pdf->addText($this->Docu->activeframe[0]['dateposx'],$this->Docu->activeframe[0]['dateposy'],$this->Docu->activeframe[0]['datesize'], $this->date);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_scale']);
			if($this->Docu->activeframe[0]['scalesize'] > 0)$pdf->addText($this->Docu->activeframe[0]['scaleposx'],$this->Docu->activeframe[0]['scaleposy'],$this->Docu->activeframe[0]['scalesize'],'1: '.$this->scale);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_oscale']);
			if($this->Docu->activeframe[0]['oscalesize'] > 0)$pdf->addText($this->Docu->activeframe[0]['oscaleposx'],$this->Docu->activeframe[0]['oscaleposy'],$this->Docu->activeframe[0]['oscalesize'],'1:xxxx');
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_lage']);
			if($this->Docu->activeframe[0]['lagesize'] > 0)$pdf->addText($this->Docu->activeframe[0]['lageposx'],$this->Docu->activeframe[0]['lageposy'],$this->Docu->activeframe[0]['lagesize'],$this->lage);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_gemeinde']);
			if($this->Docu->activeframe[0]['gemeindesize'] > 0)$pdf->addText($this->Docu->activeframe[0]['gemeindeposx'],$this->Docu->activeframe[0]['gemeindeposy'],$this->Docu->activeframe[0]['gemeindesize'],$this->gemeinde);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_gemarkung']);
			if($this->Docu->activeframe[0]['gemarkungsize'] > 0)$pdf->addText($this->Docu->activeframe[0]['gemarkungposx'],$this->Docu->activeframe[0]['gemarkungposy'],$this->Docu->activeframe[0]['gemarkungsize'],$this->gemarkung);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_flur']);
			if($this->Docu->activeframe[0]['flursize'] > 0)$pdf->addText($this->Docu->activeframe[0]['flurposx'],$this->Docu->activeframe[0]['flurposy'],$this->Docu->activeframe[0]['flursize'], $this->flur);
			$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_flurst']);
			if($this->Docu->activeframe[0]['flurstsize'] > 0)$pdf->addText($this->Docu->activeframe[0]['flurstposx'],$this->Docu->activeframe[0]['flurstposy'],$this->Docu->activeframe[0]['flurstsize'], $this->flurstueck);

			# Freie Graphiken
			for($j = 0; $j < count($this->Docu->activeframe[0]['bilder']); $j++){
				$bild=$this->Docu->activeframe[0]['bilder'][$j];
				#var_dump($bild);
				if ($bild['height']>0) {
					$pdf->addJpegFromFile(WWWROOT . CUSTOM_PATH . 'graphics/' . $bild['src'], $bild['posx'],$bild['posy'],$bild['width'],$bild['height']);
				}
				else {
					$pdf->addJpegFromFile(WWWROOT . CUSTOM_PATH . 'graphics/' . $bild['src'],
					$bild['posx'],$bild['posy'],$bild['width']);
				}
			}

			# Freitexte
			for($j = 0; $j < count($this->Docu->activeframe[0]['texts']); $j++){
				$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['texts'][$j]['font']);
				if($this->Docu->activeframe[0]['texts'][$j]['text'] == '' AND $this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']] != ''){    // ein Freitext hat keinen Text aber in der Druckausschnittswahl wurde ein Text vom Nutzer eingefügt
					$this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']] = str_replace(chr(10), ';', $this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']]);
					$this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']] = str_replace(chr(13), '', $this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']]);
					$this->Docu->activeframe[0]['texts'][$j]['text'] = $this->formvars['freetext_'.$this->Docu->activeframe[0]['texts'][$j]['id']];
				}
				$freitext = explode(';', $this->substituteFreitext(iconv("UTF-8", "CP1252//TRANSLIT", $this->Docu->activeframe[0]['texts'][$j]['text'])));
				$anzahlzeilen = count($freitext);
				$alpha = $this->Docu->activeframe[0]['texts'][$j]['angle'];
				for($i = 0; $i < $anzahlzeilen; $i++){
					$h = $i * $this->Docu->activeframe[0]['texts'][$j]['size'] * 1.25;
					$a = sin(deg2rad($alpha)) * $h;
					$b = cos(deg2rad($alpha)) * $h;
					$posx = $this->Docu->activeframe[0]['texts'][$j]['posx'] + $a;
					$posy = $this->Docu->activeframe[0]['texts'][$j]['posy'] - $b;

					if($posx < 0){		# rechtsbündig
						$posx = $pdf->ez['pageWidth'] + $posx;
						$justification = 'right';
						$orientation = 'left';
						$data = array(array(1 => $freitext[$i]));
						$pdf->ezSetY($posy+$this->Docu->activeframe[0]['texts'][$j]['size']);
						$pdf->ezTable($data, NULL, NULL,
						array('xOrientation'=>$orientation,
									'xPos'=>$posx,
									#'width'=>$this->layout['elements'][$attributes['name'][$j]]['width'],
									#'maxWidth'=>$this->layout['elements'][$attributes['name'][$j]]['width'],
									'fontSize' => $this->Docu->activeframe[0]['texts'][$j]['size'],
									'showHeadings'=>0,
									'shaded'=>0,
									'cols'=>array(1 => array('justification'=>$justification)),
									'showLines'=>0
									)
						);
					}
					else{
						$pdf->addText($posx, $posy, $this->Docu->activeframe[0]['texts'][$j]['size'], $freitext[$i], -1 * $alpha);
					}
				}
			}

			# Nutzer
			if($this->Docu->activeframe[0]['usersize'] > 0){
				$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/'.$this->Docu->activeframe[0]['font_user']);
				$pdf->addText($this->Docu->activeframe[0]['userposx'],$this->Docu->activeframe[0]['userposy'],$this->Docu->activeframe[0]['usersize'], utf8_decode('Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name));
			}

			# Nordpfeil
			if($this->Docu->activeframe[0]['arrowposx'] != 0){
				$arrow_start = rotate(array(0, -1*$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']);
				$arrow_end = rotate(array(0, $this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']);
				$arrow_base_length = $this->Docu->activeframe[0]['arrowlength'] * 0.375;
				$arrow_head_length = $this->Docu->activeframe[0]['arrowlength'] * 0.4625;
				$arrow_head_width = $this->Docu->activeframe[0]['arrowlength'] * 0.1125;
				$pdf->setLineStyle(0.6,'round');
				$pdf->line($this->Docu->activeframe[0]['arrowposx'] + $arrow_start[0], $this->Docu->activeframe[0]['arrowposy'] + $arrow_start[1], $this->Docu->activeframe[0]['arrowposx'] + $arrow_end[0], $this->Docu->activeframe[0]['arrowposy'] + $arrow_end[1]);
				$pdata = translate(rotate(array(0,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_base_length, -1*$arrow_head_width/2,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_head_length,0,$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']),$this->Docu->activeframe[0]['arrowposx'],$this->Docu->activeframe[0]['arrowposy']);
				$pdf->polygon($pdata,3);
				$pdf->polygon($pdata,3,1);
				$pdata = translate(rotate(array(0,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_base_length, $arrow_head_width/2,$this->Docu->activeframe[0]['arrowlength']/2-$arrow_head_length,0,$this->Docu->activeframe[0]['arrowlength']/2), -1*$this->formvars['angle']),$this->Docu->activeframe[0]['arrowposx'],$this->Docu->activeframe[0]['arrowposy']);
				$pdf->polygon($pdata,3);
				$pdf->setColor(1,1,1);
				$pdf->polygon($pdata,3,1);
			}

			# Maßstabsleiste
			if($this->Docu->activeframe[0]['scalebarposx'] != 0){
				$posx = $this->Docu->activeframe[0]['scalebarposx'];
				$posy = $this->Docu->activeframe[0]['scalebarposy'];
				$scalebarwidth = 40; 	# in mm
				$width4 = $scalebarwidth / 0.3529411; 	# in PDF-Pixeln
				$width3 = 3*$width4/4;
				$width2 = $width4/2;
				$width1 = $width4/4;
				$label4 = $scalebarwidth / 1000 * $this->formvars['printscale'];
				$label3 = 3*$label4/4;
				$label2 = $label4/2;
				$label1 = $label4/4;
				$pdf->setColor(0,0,0);
				$pdf->addText($posx-1.5, $posy+6, 8, '0');
				if($label1/1000 >= 1){
					$div = 1000;
					$unit = 'Km';
				}
				else{
					$div = 1;
					$unit = 'm';
				}
				$pdf->addText($posx+$width1-5, $posy+6, 8, round($label1/$div, 2));
				$pdf->addText($posx+$width2-5, $posy+6, 8, round($label2/$div, 2));
				$pdf->addText($posx+$width3-5, $posy+6, 8, round($label3/$div, 2));
				$pdf->addText($posx+$width4-5, $posy+6, 8, round($label4/$div, 2).' '.$unit);
				$pdf->setLineStyle(0.5);
				$pdf->rectangle($posx, $posy, $width1, 4);
				$pdf->rectangle($posx, $posy, $width2, 4);
				$pdf->rectangle($posx, $posy, $width3, 4);
				$pdf->rectangle($posx, $posy, $width4, 4);
				$smallrects = 10;
				$smallrectwidth = $width1 / $smallrects;
				for($s = 0; $s < $smallrects; $s++){
					$pdf->rectangle($posx, $posy, $smallrectwidth*($s+1), 4);
				}
			}

			# variable Freitexte
			for($j = 1; $j <= $this->formvars['last_freetext_id']; $j++){
				$pdf->selectFont(WWWROOT.APPLVERSION.'fonts/PDFClass/Helvetica.afm');
				if(strpos($this->Docu->activeframe[0]['format'], 'quer') !== false)$height = 420;			# das ist die Höhe des Vorschaubildes
				else $height = 842;																																		# das ist die Höhe des Vorschaubildes
				$ratio = $height/$this->Docu->height;
				if($this->formvars['freetext'.$j] != ''){
					$posx = ($this->formvars['freetext_posx'.$j]+1)/$ratio;
					$posy = ($this->formvars['freetext_posy'.$j]+1-$height)/$ratio*-1;
					$boxwidth = ($this->formvars['freetext_width'.$j]+6)/$ratio;
					$boxheight = ($this->formvars['freetext_height'.$j]+8)/$ratio;
					$fontsize = $this->formvars['freetext_fontsize'.$j]/$ratio;
					$pdf->setColor(1,1,1);
					$pdf->filledRectangle($posx, $posy-$boxheight, $boxwidth, $boxheight);
					$pdf->setColor(0,0,0);
					$pdf->Rectangle($posx, $posy-$boxheight, $boxwidth, $boxheight);

					$this->formvars['freetext'.$j] = str_replace(chr(10), ';', $this->formvars['freetext'.$j]);
					$this->formvars['freetext'.$j] = str_replace(chr(13), '', $this->formvars['freetext'.$j]);
					$freitext = explode(';', $this->formvars['freetext'.$j]);
					$anzahlzeilen = count($freitext);
					for($i = 0; $i < $anzahlzeilen; $i++){
						$h = $i * $fontsize * 1.25;
						$pdf->addText($posx+$fontsize*0.3333, $posy-$h-$fontsize*1.18, $fontsize, iconv("UTF-8", "CP1252//TRANSLIT", $freitext[$i]), 0);
					}
				}
			}

			# Legende
			if($this->Docu->activeframe[0]['legendsize'] > 0){
				$legend = $this->createlegend($this->Docu->activeframe[0]['legendsize'], $fast);
				if($this->formvars['legend_extra']){
					$pdf->newPage();
					$this->Docu->activeframe[0]['legendposx'] = 50;
					$this->Docu->activeframe[0]['legendposy'] = $this->Docu->height - $legend['height']/$this->map_factor - 50;
				}
				$pdf->addJpegFromFile(IMAGEPATH.basename($legend['name']),$this->Docu->activeframe[0]['legendposx'],$this->Docu->activeframe[0]['legendposy'],$legend['width']/$this->map_factor);
			}
			$this->pdf=$pdf;
			$output = $this->pdf->ezOutput();
		}
		$dateipfad=IMAGEPATH;
		$currenttime = date('Y-m-d_H_i_s',time());
		$name = sonderzeichen_umwandeln($this->user->Name);
		$dateiname = $name.'-'.$currenttime.'.pdf';
		$this->outputfile = $dateiname;
		$fp=fopen($dateipfad.$dateiname,'wb');
		fwrite($fp, $output);
		fclose($fp);

		if ($preview == true OR $this->formvars['output_filetype'] == 'image/jpeg') {
			if ($preview == true) {
				$resize = '-resize 595x1000';
			}
			exec(IMAGEMAGICKPATH.'convert -density 300x300 '.$dateipfad.$dateiname.'[0] -background white -flatten ' . $resize . ' '.$dateipfad.$name.'-'.$currenttime.'.jpg');
			#echo IMAGEMAGICKPATH.'convert -density 300x300 '.$dateipfad.$dateiname.'[0] -background white -flatten ' . $resize . ' '.$dateipfad.$name.'-'.$currenttime.'.jpg';
			if (!file_exists(IMAGEPATH.$name.'-'.$currenttime.'.jpg')){
				$this->outputfile = $name.'-'.$currenttime.'-0.jpg';;
			}
			else{
				$this->outputfile = $name.'-'.$currenttime.'.jpg';
			}
			if ($preview == true) {
				return TEMPPATH_REL.$this->outputfile;
			}
		}
  }

  function substituteFreitext($text){
  	$text = str_replace('$stelle', utf8_decode($this->Stelle->Bezeichnung), $text);
  	$text = str_replace('$user', utf8_decode($this->user->Name), $text);
		$text = str_replace('$scale', $this->scale, $text);
		$text = str_replace('$gemeinde', $this->gemeinde, $text);
		$text = str_replace('$gemarkung', $this->gemarkung, $text);
		$text = str_replace('$flurstueck', $this->flurstueck, $text);
		$text = str_replace('$flur', $this->flur, $text);
		$text = str_replace('$lage', $this->lage, $text);
		$text = str_replace('$date', $this->date, $text);
		$text = str_replace('$minx', $this->minx, $text);
		$text = str_replace('$miny', $this->miny, $text);
		$text = str_replace('$maxx', $this->maxx, $text);
		$text = str_replace('$maxy', $this->maxy, $text);
  	return $text;
  }

	function ows_export_loeschen() {
		if (unlink(WMS_MAPFILE_PATH . $this->Stelle->id . '/' . $this->formvars['mapfile_name'])) {
			$this->add_message('notice', 'MapDatei ' . WMS_MAPFILE_PATH . $this->Stelle->id . '/' . $this->formvars['mapfile_name'] . ' erfolgreich gelöscht.');
		}
		$this->formvars['mapfile_name'] = '';
		$this->wmsExport();
	}

	function wmsExportSenden() {
		$this->titel = 'MapServer Map-Datei für OGC-Dienste erfolgreich exportiert';
		$this->main = "ows_exportiert.php";
		# laden der aktuellen Karteneinstellungen 1 nur aktive, 2 alle
		$this->class_load_level = ($this->formvars['nurAktiveLayer'] == 1 ? 1 : 2);

		$this->loadMap('DataBase');
		@$gridlayer = $this->map->getLayerByName('grid');
		if ($gridlayer) {
			$this->map->removeLayer($gridlayer->index);
		}
		if ($this->formvars['totalExtent'] == 1) {
			$bb = array($this->Stelle->MaxGeorefExt->minx, $this->Stelle->MaxGeorefExt->miny, $this->Stelle->MaxGeorefExt->maxx, $this->Stelle->MaxGeorefExt->maxy);
		}
		else {
			$bb = array($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
		}
		$this->center = new PointObj();
		$this->center->setXY($bb[0] + ($bb[2] - $bb[0]) / 2, $bb[1] + ($bb[3] - $bb[1]) / 2);
		$projFROM = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
		$projTO = new projectionObj("init=epsg:4326");
		$this->center->project($projFROM, $projTO);

		if (!is_dir(WMS_MAPFILE_PATH . $this->Stelle->id)) {
			mkdir(WMS_MAPFILE_PATH . $this->Stelle->id, 0770, true);
		}
		$this->mapfile = $this->mapfile ?: WMS_MAPFILE_PATH . $this->Stelle->id . '/' . $this->formvars['mapfile_name'];
		# setzen der WMS-Metadaten
		$this->map->web->metadata->set("ows_title", $this->formvars['ows_title']);
		$this->map->web->metadata->set("ows_abstract", $this->formvars['ows_abstract']);
		$this->map->web->metadata->set("wms_extent", implode(' ', $bb));
		$this->map->web->metadata->set("wms_accessconstraints", "none");
		$this->map->web->metadata->set("ows_contactperson", $this->formvars['ows_contactperson']);
		$this->map->web->metadata->set("ows_contactorganization", $this->formvars['ows_contactorganization']);
		$this->map->web->metadata->set("ows_contactelectronicmailaddress", $this->formvars['ows_contactelectronicmailaddress']);
		$this->map->web->metadata->set("ows_contactposition", $this->formvars['ows_contactposition']);
		$this->map->web->metadata->set("ows_fees", $this->formvars['ows_fees']);
		$this->wms_onlineresource = MAPSERV_CGI_BIN . "?map=" . $this->mapfile . "&";
		$this->map->web->metadata->set("wms_onlineresource", $this->wms_onlineresource);
		$this->map->web->metadata->set("ows_srs", OWS_SRS . ' EPSG:3857');
		$this->map->web->metadata->set("wms_enable_request", '*');
		$this->map->web->metadata->set("gdi_filter_attribute_name", $this->formvars['filter_attribute_name']);
		$this->map->web->metadata->set("gdi_filter_attribute_operator", $this->formvars['filter_attribute_operator']);
		$this->map->web->metadata->set("gdi_filter_attribute_value", $this->formvars['filter_attribute_value']);
		$this->map->web->metadata->set("gdi_nurVeroeffentlichte", $this->formvars['nurVeroeffentlichte'] ?: '');
		$this->map->web->metadata->set("gdi_nurAktiveLayer", $this->formvars['nurAktiveLayer']);
		$this->map->web->metadata->set("gdi_totalExtent", $this->formvars['totalExtent']);

		for ($i = 0; $i < $this->map->numlayers; $i++) {
			$layer = $this->map->getLayer($i);
			$layer->name = sonderzeichen_umwandeln($layer->name);
			$layer->metadata->set("ows_title", $layer->name);
			$layer->metadata->set("ows_extent", implode(', ', $bb));
			$layer->metadata->set("ows_srs", OWS_SRS . ' EPSG:3857');
			$this->exportierte_layer[] = $layer->name;
		}

		/*
		* if formvars['nurVeroeffentlichte'] == 1 and connection_type add a filter to the layer definition
		* but only if connectiontype of the layer is postgis and
		* attribute 'veroeffentlicht' is part of the base_expresion or the alias of an attribute in layers data sql
		*/
		if (
			array_key_exists('nurVeroeffentlichte', $this->formvars) AND $this->formvars['nurVeroeffentlichte'] == 1 OR
			(
				array_key_exists('filter_attribute_name', $this->formvars) AND $this->formvars['filter_attribute_name'] != '' AND
				array_key_exists('filter_attribute_value', $this->formvars) AND $this->formvars['filter_attribute_value'] != ''
			)
		) {
			$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$this->gefilterte_layer = array();
			for ($i = 0; $i < $this->map->numlayers; $i++) {
				$layer = $this->map->getLayer($i);
				if ($layer->connectiontype == 6 AND $layer->data != '') {
					$sql = $mapDB->getSelectFromData($layer->data);
					$filters = array();
					$layerdb = $mapDB->getlayerdatabase($layer->metadata->get('kvwmap_layer_id'), $this->Stelle->pgdbhost);
					$attributes = $layerdb->getFieldsfromSelect($sql);
					for ($j = 0; $j < count($attributes[1]) - 2; $j++) {
						if ($attributes[1][$j]['name'] == 'veroeffentlicht') {
							$filters[] = $attributes[1][$j]['name'];
						}
						if ($attributes[1][$j]['name'] == $this->formvars['filter_attribute_name'] AND $this->formvars['filter_attribute_value'] != '') {
							$filters[] = '"[' . $this->formvars['filter_attribute_name'] . ']" ' . $this->formvars['filter_attribute_operator'] . ' "' . $this->formvars['filter_attribute_value'] . '"';
						}
						if (count($filters) > 0) {
							$this->gefilterte_layer[] = $layer->name;
							#echo '<p>filter: (' . implode(' AND ', $filters) . ')'; exit;
							$layer->setFilter('(' . implode(' AND ', $filters) . ')');
							break;
						}
					}
				}
			}
		}
		$this->saveMap($this->mapfile);
		$this->getMapRequestExample = $this->wms_onlineresource
			. 'SERVICE=WMS&'
			. 'REQUEST=GetMap&'
			. 'VERSION=' . SUPORTED_WMS_VERSION . '&'
			. 'LAYERS='.implode(',', $this->exportierte_layer).'&'
			. 'CRS=EPSG:' . $this->user->rolle->epsg_code . '&'
			. 'BBOX=' . implode(',', $bb) .'&'
			. 'WIDTH=' . $this->map->width . '&'
			. 'HEIGHT=' . $this->map->height . '&'
			. 'FORMAT=image/png';
		define('SUPORTED_WFS_VERSION', '1.0.0');
		$this->getFeatureRequestExample = $this->wms_onlineresource
			. 'SERVICE=WFS&'
			. 'REQUEST=GetFeature&'
			. 'VERSION=' . SUPORTED_WFS_VERSION . '&'
			. 'TYPENAME='.implode(',', $this->exportierte_layer).'&'
			. 'CRS=EPSG:' . $this->user->rolle->epsg_code;

		$this->mapfiles_der_stelle = $this->Stelle->get_mapfiles();

		$this->output();
	}

	function wmsExport() {
		$this->titel = 'MapService Map-Datei Export';
		$this->main = "ows_export.php";
		if (
			$this->formvars['mapfile_name'] != '' AND
			in_array($this->formvars['mapfile_name'], $this->Stelle->get_mapfiles())
		) {
			$this->mapfile = WMS_MAPFILE_PATH . $this->Stelle->id . '/' . $this->formvars['mapfile_name'];
			if (MAPSERVERVERSION < 600) {
				$map = ms_newMapObj($this->mapfile);
			}
			else {
				$map = new mapObj($this->mapfile, SHAPEPATH);
			}
			$this->formvars['ows_title'] = $map->getMetaData('ows_title');
			$this->formvars['ows_abstract'] = $map->getMetaData('ows_abstract');
			$this->formvars['wms_extent'] = $map->getMetaData('wms_extent');
			$this->formvars['wms_accessconstraints'] = $map->getMetaData('wms_accessconstraints');
			$this->formvars['ows_contactperson'] = $map->getMetaData('ows_contactperson');
			$this->formvars['ows_contactorganization'] = $map->getMetaData('ows_contactorganization');
			$this->formvars['ows_contactelectronicmailaddress'] = $map->getMetaData('ows_contactelectronicmailaddress');
			$this->formvars['ows_contactelectronicmailaddress'] = $map->getMetaData('ows_contactelectronicmailaddress');
			$this->formvars['ows_contactposition'] = $map->getMetaData('ows_contactposition');
			$this->formvars['ows_fees'] = $map->getMetaData('ows_fees');
			$this->formvars['wms_onlineresource'] = $map->getMetaData('wms_onlineresource');
			$this->formvars['ows_srs'] = $map->getMetaData('ows_srs');
			$this->formvars['wms_enable_request'] = $map->getMetaData('wms_enable_request');
			$this->formvars['filter_attribute_name'] = $map->getMetaData('gdi_filter_attribute_name');
			$this->formvars['filter_attribute_operator'] = $map->getMetaData('gdi_filter_attribute_operator');
			$this->formvars['filter_attribute_value'] = $map->getMetaData('gdi_filter_attribute_value');
			$this->formvars['nurVeroeffentlichte'] = $map->getMetaData('gdi_nurVeroeffentlichte');
			$this->formvars['nurAktiveLayer'] = $map->getMetaData('gdi_nurAktiveLayer');
			$this->formvars['totalExtent'] = $map->getMetaData('gdi_totalExtent');
		}

		$this->mapfiles_der_stelle = $this->Stelle->get_mapfiles();

		$this->output();
  }

	function wmsImportFormular() {
		$this->titel = 'WMS Import';
		$this->main = "wms_import.php";
		if ($this->formvars['wms_url']) {
			include(CLASSPATH . 'wms.php');
			$wms = new wms_request_obj();
			$this->layers = $wms->parseCapabilities($this->formvars['wms_url'], $this->formvars['wms_auth_username'], $this->formvars['wms_auth_password']);
		}
		$this->output();
	}

	function wmsImportieren() {
		if (count($this->formvars['layers']) > 0) {
			$dbmap = new db_mapObj($this->Stelle->id, $this->user->id);
			$group = $dbmap->getGroupbyName('WMS-Importe');
			$groupid = ($group != '' ? $group['id'] : $dbmap->newGroup('WMS-Importe', 0));
			$this->formvars['user_id'] = $this->user->id;
			$this->formvars['stelle_id'] = $this->Stelle->id;
			$this->formvars['aktivStatus'] = 1;
			$this->formvars['Gruppe'] = $groupid;
			$this->formvars['Typ'] = 'import';
			$this->formvars['Datentyp'] = MS_LAYER_RASTER;
			$this->formvars['connectiontype'] = MS_WMS;
			$this->formvars['transparency'] = 100;
			$wms_epsg_codes = array_flip(explode(' ', str_replace('epsg:', '', strtolower($this->formvars['srs'][0]))));
			$this->formvars['epsg_code'] = ($wms_epsg_codes[$this->user->rolle->epsg_code] !== NULL ? $this->user->rolle->epsg_code : 4326);
			$this->formvars['wms_url'] .= (strpos($this->formvars['wms_url'], '?') !== false ? '&' : '?');
			for ($i = 0; $i < count($this->formvars['layers']); $i++) {
				$this->formvars['Name'] = $this->formvars['layers'][$i];
				$this->formvars['connection'] = $this->formvars['wms_url'] . 'VERSION=1.1.1&FORMAT=image/png&transparent=true&styles=&LAYERS=' . $this->formvars['layers'][$i];
				$layer_id = $dbmap->newRollenLayer($this->formvars);
				$classdata['layer_id'] = -$layer_id;
				$classdata['name'] = '_';
				$class_id = $dbmap->new_Class($classdata);
			}
			$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		}
		$this->loadMap('DataBase');
		// $this->user->rolle->newtime = $this->user->rolle->last_time_id;
		// $this->drawMap();
		// $this->saveMap('');
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

  function setSize() {
    $this->user->setSize($this->formars['mapsize']);
    $teil=explode('x',$this->formars['mapsize']);
    $nImageWidth=$teil[0];
    $nImageHeight=$teil[1];
    $this->map->set('width',$this->user->rolle->nImageWidth);
    $this->map->set('height',$this->user->rolle->nImageHeight);
    return 1;
  }

  function Layer2Stelle_EditorSpeichern(){
		$this->sanitize([
			'selected_layer_id' => 'int',
			'use_geom' => 'int',
			'postlabelcache' => 'int',
			'offsite' => 'text',
			'Filter' => 'text',
			'template' => 'text',
			'header' => 'text',
			'footer' => 'text',
			'logconsume' => 'int',
			'queryable' => 'int',
			'start_aktiv' => 'int',
			'group_id' => 'int',
			'transparency' => 'int',
			'minscale' => 'int',
			'maxscale' => 'int',
			'symbolscale' => 'int',
			'requires' => 'int'			
		]);
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $Stelle->updateLayer($this->formvars);
    $this->Layer2Stelle_Editor();
  }

	function Layer2Stelle_Editor() {
		$Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
		$this->titel = 'Layereigenschaften stellenbezogen';
		$this->main = 'layer2stelle_formular.php';
		$result = $Stelle->getLayer($this->formvars['selected_layer_id']);
		$this->grouplayers = $Stelle->getLayers($result[0]['Gruppe'], 'Name');
		$stelle_id = $this->formvars['selected_stelle_id'];
		$layer_id = $this->formvars['selected_layer_id'];
		$stellenname = $this->formvars['stellen_name'];
		$this->formvars = $result[0];
		$this->formvars['selected_stelle_id'] = $stelle_id;
		$this->formvars['selected_layer_id'] = $layer_id;
		$this->formvars['stellen_name'] = $stellenname;
		$this->output();
	}

	function Layer_Zeichenreihenfolge() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->layerdaten = $mapDB->getall_Layer('drawingorder', false, $this->user->id, $this->Stelle->id);
		$this->titel = 'Zeichenreihenfolge';
		$this->main = 'layer_zeichenreihenfolge.php';
		$this->output();
	}

	function Layer_Zeichenreihenfolge_Speichern() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$mapDB->updateDrawingorder($this->formvars);		
		$this->Layer_Zeichenreihenfolge();
	}

	function Layer_Legendenreihenfolge() {
		$this->main = 'layer_legendenreihenfolge.php';
		$this->titel = 'Themenbaum bearbeiten';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->groups = $mapDB->read_Groups(true);
		$this->layers = $mapDB->getall_Layer();
		$this->output();
	}

	function Layer_Legendenreihenfolge_Speichern() {
		include_once(CLASSPATH . 'LayerGroup.php');
		for ($i = 0; $i < count($this->formvars['group_ids']); $i++) {
			$this->layergruppe = new LayerGroup($this);
			$this->layergruppe->data = [
				'id' => $this->formvars['group_ids'][$i],
				'order' => $this->formvars['group_orders'][$i],
				'obergruppe' => $this->formvars['group_uppergroups'][$i]
			];
			$result = $this->layergruppe->update();
		}
		rolle::setGroupsForAll($this->database);
	}

	function Layer2Stelle_Reihenfolge() {
		$this->selected_stelle = new stelle($this->formvars['selected_stelle_id'], $this->user->database);
		$this->main = 'layer2stelle_order.php';
		if ($this->formvars['order'] == '') {
			$this->formvars['order'] = 'ul.legendorder, l.drawingorder desc';
		}
		if ($this->formvars['order'] == 'ul.legendorder, l.drawingorder desc') {
			$this->groups = $this->selected_stelle->getGroups();
		}
		$this->layers = $this->selected_stelle->getLayers(NULL, $this->formvars['order']);
		$this->output();
	}

  function Layer2Stelle_ReihenfolgeSpeichern(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->layers = $Stelle->getLayers(NULL);
    for($i = 0; $i < count($this->layers['ID']); $i++){
      $this->formvars['selected_layer_id'] = $this->layers['ID'][$i];
			$this->formvars['legendorder'] = $this->formvars['legendorder_layer'.$this->layers['ID'][$i]];
      $Stelle->updateLayerOrder($this->formvars);
    }
    $this->Layer2Stelle_Reihenfolge();
  }

  function layer_export(){
  	# Abfragen aller Layer
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    $this->layerdaten = $mapDB->getall_Layer('Name');
    $this->titel='Layer-Export';
    $this->main='layer_export.php';
    $this->output();
  }

	function layer_export_exportieren() {
		if ($this->formvars['layer']) {
			$export_layer_ids = $this->formvars['layer'];
			$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
			$result = $mapDB->create_layer_dumpfile(
				$this->database,
				$export_layer_ids,
				($this->formvars['with_privileges'] != ''),
				($this->formvars['with_datatypes'] != '')
			);
			if ($result['success']) {
				$this->add_message('notice', 'Export erfolgreich.<br>Sie können die Datei jetzt herunterladen.');
				$this->layer_dumpfile = $result['layer_dumpfile'];
			}
			else {
				$this->add_message('error', 'Fehler beim Export: ' . $result['err_msg']);
			}
		}
		else {
			$this->add_message('warning', 'Sie müssen mindestens einen Layer zum Export auswählen!');
		}
		$this->layer_export();
	}

	function layer_generator(){
		$this->titel='Layer-Generator';
		$this->main='layer_generator.php';
		$this->output();
	}

	/**
	* Erzeugt sql zum Anlegen eines Layers in MySQL mit all seinen zugehörigen Elementen wie
	* Layerattribute mit Datentypen und Datentypeattributen falls vorhanden,
	* Klassen, Styles und die Zugehörigkeit zwischen Styles und Klassen.
	*/
	function layer_generator_erzeugen() {
		$this->debug->write("<p>layer_generator_erzeugen:", 4);
		$group_id = ($this->formvars['group_id'] == '') ? 1 : $this->formvars['group_id'];
		$selected_database = $this->formvars['selected_database'];
		$selected_schema = $this->formvars['selected_schema'];
		$selected_tables = $this->formvars['selected_tables'];
		$this->sql = "
-- Generated by kvwmap " . date('d.m.Y H:i:s') . "
SET @group_id = {$group_id};
SET @connection_id = {$this->pgdatabase->connection_id};
";
		if (!empty($selected_schema)) {
			$msg = '<p>Ausgewähltes Schema: ' . $selected_schema;
			$this->schema_tables = $this->pgdatabase->get_tables($selected_schema);
			if (!empty($selected_tables)) {
				$msg .= '<br>Ausgewählte Tabellen: ' . implode(', ', $selected_tables);
				foreach ($this->schema_tables AS $table) {
					if (in_array($table['name'], $selected_tables)) {
						$msg .= '<br>Gebe Definition für Tabelle ' . $table['name'] . ' aus';
						$this->sql .= $this->generate_layer($selected_schema, $table);
						$this->sql .= $this->database->generate_classes($table);
						$this->sql .= $this->database->generate_styles();
						$this->sql .= $this->database->generate_style2classes();
					}
				}
			}
		}
	  $this->debug->write($msg, 4);
	}

	/**
	* This function generate sql for a layer in mysql with all its attributes and
	* if they are from type datatype the datatypes and there attribtes.
	* @params $table associatives Array mit Attribut name.
	*/
	function generate_layer($schema, $table, $group_id = 0, $connection_id = '', $epsg_code = '25832', $geometrie_column = '', $geometrietyp = '', $layertyp = '') {
		$this->debug->write("<p>schema: {$schema}, table: {$table['name']}, group_id: {$group_id}, connection: {$connection}, epsg_code: {$epsg_code}, geometrie_column: {$geometrie_column}, geometrietype: {$geometrietyp}, layertype: {$layertype}", 4);
		$sql = $this->database->generate_layer($schema, $table, $group_id, $connection_id, $epsg_code, $geometrie_column, $geometrietyp, $layertyp);
		$table_attributes = $this->pgdatabase->get_attribute_information($schema, $table['name']);
		$sql .= $this->generate_layer_attributes($schema, $table, $table_attributes);
		return $sql;
	}

	/**
	* This function generate the sql for all attributes of a layer in mysql
	*
	*/
	function generate_layer_attributes($schema, $table, $table_attributes) {
		foreach ($table_attributes AS $table_attribute) {
			$sql .= $this->generate_layer_attribute($schema, $table, $table_attribute);

			if ($table_attribute['type_type'] == 'c') {
				$sql .= $this->generate_datatype($schema, $table_attribute);
			}
		}
		return $sql;
	}

	/**
	* This function generate sql for a layer attribute in mysql
	*
	*/
	function generate_layer_attribute($schema, $table, $table_attribute) {
		$this->debug->write("<p>generate_layer_attribute: {$table_attribute}", 4);
		if ($table_attribute['type_type'] == 'e')
			$enum_options = $this->pgdatabase->get_enum_options($schema, $table_attribute);
		else
			$enum_options = array('option' => '', 'constraint' => '');

		$sql .= $this->database->generate_layer_attribute($table_attribute, $table, $enum_options);
		return $sql;
	}

	/**
	* This function generate sql for a datatype definition and its attributes in mysql
	*/
	function generate_datatype($schema, $table_attribute) {
		$sql .= $this->database->generate_datatype($schema, $table_attribute);

		# generate datatypes attributes
		$datatype_attributes = $this->pgdatabase->get_attribute_information($schema, $table_attribute['type']);
		$sql .= $this->generate_datatype_attributes($schema, $table_attribute, $datatype_attributes);
		return $sql;
	}

	/**
	* This function generate sql for attributes of a datatype
	*/
	function generate_datatype_attributes($schema, $table, $datatype_attributes) {
		$sql = '';
		foreach ($datatype_attributes AS $datatype_attribute) {
			$sql .= $this->generate_datatype_attribute($schema, $table, $datatype_attribute);
		}
		return $sql;
	}

	/**
	* This function generate an attribute of a datatype.
	* If its type is an enum generate it with options and constraints
	* If its type is an datatype generate it
	*/
	function generate_datatype_attribute($schema, $table, $datatype_attribute) {
		if ($datatype_attribute['type_type'] == 'e')
			$enum_options = $this->pgdatabase->get_enum_options($schema, $datatype_attribute);
		else
			$enum_options = array('option' => '', 'constraint' => '');

		$sql = $this->database->generate_datatype_attribute($datatype_attribute, $table, $enum_options);

		if ($datatype_attribute['type_type'] == 'c') {
			$sql .= $this->generate_datatype($schema, $datatype_attribute);
		}
		return $sql;
	}

	function layer_parameter(){
		$this->main = 'layer_parameter.php';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->params = $mapDB->get_all_layer_params();
		$this->params_layer = $mapDB->get_layer_params_layer();
    $this->output();
	}

	function layer_parameter_speichern(){
		$this->main='layer_parameter.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->save_all_layer_params($this->formvars);
		# Ergänze und lösche Layerparameter in Rollen
		$this->update_layer_parameter_in_rollen();
		$this->layer_parameter();
	}

	/**
	* for rollen do
	* delete param if not defined
	* @param $stelle_id if stelle_id is given, only the params and rollen of this stelle_id are checked and params are added with default value if not exists
	* @return null
	*/
	function update_layer_parameter_in_rollen($stelle_id = NULL) {
		$default_params = array();
		$layer_parameter = new MyObject(
			$this,
			'layer_parameter'
		);
		$params = $layer_parameter->find_by_sql(array(
			'select' => '*',
			'where' => ($stelle_id != NULL? 'locate (layer_parameter.id, (select selectable_layer_params from stelle where id = ' . $stelle_id . '))' : '')
		));
		foreach ($params AS $param) {
			$default_params[$param->get('key')] = $param->get('default_value');
		}
		$rolle = new MyObject(
			$this,
			'rolle',
			array(
				array(
					'key' => 'user_id',
					'type' => 'integer'
				),
				array(
					'key' => 'stelle_id',
					'type' => 'integer'
				)
			),
			'array'
		);
		$rollen = $rolle->find_by_sql(array(
			'select' => 'user_id, stelle_id, layer_params',
			'where' => ($stelle_id != NULL? 'stelle_id = ' . $stelle_id : '')
		));
		foreach ($rollen AS $rolle) {
			$rolle_params = (array)json_decode('{' . $rolle->get('layer_params') . '}');

			if ($stelle_id != NULL) {
				# Parameter, die die Rolle noch nicht hat anhängen
				$add = array_diff_key($default_params, $rolle_params);
				$rolle_params = array_merge($rolle_params, $add);
			}

			# Parameter, die es nicht mehr gibt oder die Stelle nicht mehr hat, aber die Rolle noch hat löschen
			$rolle_params = array_intersect_key($rolle_params, $default_params);

			$new_params = array();
			foreach ($rolle_params AS $key => $value) {
				$new_params[] = '"' . $key . '":"' . $value . '"';
			}
			# speicher die neuen parameter
			$rolle->update(array('layer_params' => implode(',', $new_params)), false);
		}
	}

	function Layereditor() {
		include_once(CLASSPATH . 'LayerChart.php');
		include_once(CLASSPATH . 'DataSource.php');
		$this->titel = 'Layer Editor';
		$this->main = 'layer_formular.php';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->layerdaten = $mapDB->getall_Layer('Name', false, $this->user->id, $this->Stelle->id);
		# Abfragen der Layerdaten wenn eine layer_id zur Änderung selektiert ist
		if ($this->formvars['selected_layer_id'] > 0) {
			$this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
			$layer_labelitems = new MyObject($this, 'layer_labelitems');
			$this->layerdata['labelitems'] = $layer_labelitems->find_where('layer_id = ' . $this->formvars['selected_layer_id'], 'order');
			$this->layerdata['charts'] = LayerChart::find($this, '`layer_id` = ' . $this->formvars['selected_layer_id']);
			$this->layerdata['datasources'] = DataSource::find_by_layer_id($this, $this->formvars['selected_layer_id']);
			$this->layerdata['datasource_ids'] = array_map(function($datasource) { return $datasource->get('id'); }, $this->layerdata['datasources']);
			if (!$this->use_form_data) {
				$this->formvars = array_merge($this->formvars, $this->layerdata);
			}
			# Abfragen der Stellen des Layer
			$this->formvars['selstellen'] = $mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
			$this->grouplayers = $mapDB->get_layersfromgroup($this->layerdata['Gruppe']);
		}
		else {
			$this->connections = Connection::find($this, 'id');
			$this->tables = [];
		}
		$this->stellen = $this->Stelle->getStellen('Bezeichnung');
		$this->Groups = $mapDB->get_Groups();
		$this->epsg_codes = read_epsg_codes($this->pgdatabase);
		$this->output();
	}

	function Klasseneditor() {
		$this->main='layer_klasseneditor.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layerdaten = $mapDB->getall_Layer('Name', false, $this->user->id, $this->Stelle->id);
		# Abfragen der Layerdaten wenn eine layer_id zur Änderung selektiert ist
		if ($this->formvars['selected_layer_id'] > 0) {
			$this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
			$this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id'], NULL, true);
		}
		$this->output();
	}

	function Klasseneditor_speichern(){
		global $supportedLanguages;
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->user->rolle->readSettings();
		if ($this->formvars['id'] != '') {
			$this->formvars['selected_layer_id'] = $this->formvars['id'];
		}
		# Klassen
		$name = @array_values($this->formvars['name']);
		foreach($supportedLanguages as $language){
			if($language != 'german'){
				$name_[$language] = @array_values($this->formvars['name_'.$language]);
			}
		}
		$new_class_id = @array_values($this->formvars['new_class_id']);
		$expression = @array_values($this->formvars['expression']);
		$text = @array_values($this->formvars['text']);
		$classification = @array_values($this->formvars['classification']);
		$legendgraphic = @array_values($this->formvars['legendgraphic']);
		$legendimagewidth = @array_values($this->formvars['legendimagewidth']);
		$legendimageheight = @array_values($this->formvars['legendimageheight']);
		$order = @array_values($this->formvars['order']);
		$legendorder = @array_values($this->formvars['classlegendorder']);
		$this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id']);
		for ($i = 0; $i < count($name); $i++) {
			$attrib['name'] = $name[$i];
			foreach ($supportedLanguages as $language){
				if ($language != 'german'){
					$attrib['name_'.$language] = $name_[$language][$i];
				}
			}
			$attrib['layer_id'] = $this->formvars['selected_layer_id'];
			$attrib['new_class_id'] = $new_class_id[$i];
			$attrib['expression'] = $expression[$i];
			$attrib['text'] = $text[$i];
			$attrib['classification'] = $classification[$i];
			$attrib['legendgraphic'] = $legendgraphic[$i];
			$attrib['legendimagewidth'] = $legendimagewidth[$i];
			$attrib['legendimageheight'] = $legendimageheight[$i];
			$attrib['order'] = $order[$i];
			$attrib['legendorder'] = ($legendorder[$i] == '' ? 'NULL' : $legendorder[$i]);
			$attrib['class_id'] = $this->classes[$i]['Class_ID'];
			$mapDB->update_Class($attrib);
		}
		$this->Klasseneditor();
	}

  function Klasseneditor_KlasseLoeschen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->delete_Class($this->formvars['class_id']);
    $this->Klasseneditor();
  }

  function Klasseneditor_KlasseHinzufuegen($output = true){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $attrib['name'] = $this->formvars['class_name'];
    $attrib['layer_id'] = $this->formvars['selected_layer_id'];
		$attrib['classification'] = $this->formvars['classification'];
    $attrib['order'] = ($this->formvars['class_order'] != '') ? $this->formvars['class_order'] : 1;
    $attrib['expression'] = ($this->formvars['class_expression'] != '') ? $this->formvars['class_expression'] : '';
    $new_class = $mapDB->new_Class($attrib);
		if($output)$this->Klasseneditor();
		return $new_class;
  }

  function Klasseneditor_AutoklassenHinzufuegen() {
    $num_classes = (empty($this->formvars['num_classes'])) ? 5 : $this->formvars['num_classes'];
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->formvars['Datentyp'] = $this->layerdata['Datentyp'];
    $begin = strpos($this->layerdata['Data'], '(') + 1;
    $end = strrpos($this->layerdata['Data'], ')');
    $data_sql = substr($this->layerdata['Data'], $begin, $end - $begin);

		$classification_params = $mapDB->get_layer_params_layer(NULL, $this->formvars['selected_layer_id'], 'classification');
		if (!empty($classification_params)) {
			foreach ($classification_params as $classification_param) {
				$params[$classification_param['key']] = $this->formvars['options_layer_parameter_' . $classification_param['key']];
			}
			$data_sql = replace_params($data_sql, $params);
			$this->formvars['classification_name'] = replace_params($this->layerdata['classification'], $params);
		}
		$data_sql = replace_params_rolle(
			$data_sql,
			['duplicate_criterion' => $this->formvars['duplicate_criterion']])
		;  # die restlichen Layer-Parameter ersetzen

    $auto_classes = $this->AutoklassenErzeugen($layerdb, $data_sql, $this->formvars['classification_column'], $this->formvars['classification_method'], $this->formvars['num_classes'], $this->formvars['classification_name'], $this->formvars['classification_color']);

    for ($i = 0; $i < count($auto_classes); $i++) {
			$this->formvars['layer_id'] = $this->formvars['selected_layer_id'];
      $this->formvars['class_name'] = $auto_classes[$i]['name'];
      $this->formvars['classification'] = $auto_classes[$i]['classification'];
      $this->formvars['class_order'] = $auto_classes[$i]['order'];
      $this->formvars['class_expression'] = $auto_classes[$i]['expression'];

      $this->formvars['class_id'] = $this->Klasseneditor_KlasseHinzufuegen(false);
      $this->formvars['style_color'] = $auto_classes[$i]['style_color'];
      $this->formvars['style_outlinecolor'] = $auto_classes[$i]['style_outlinecolor'];
      $this->add_style();
    }
		$this->Klasseneditor();
  }

  function AutoklassenErzeugen($layerdb, $data_sql, $class_item, $method, $num_classes, $classification_name, $classification_color) {
    $classes = array();
    switch ($method) {
			case 1 : {		# für jeden Wert eine Klasse
				$sql = "
					SELECT DISTINCT " . $class_item."
					FROM
						(" . $data_sql.") AS data ORDER BY " . replace_semicolon($class_item) . "
					LIMIT 100
				";
				$ret = $layerdb->execSQL($sql, 4, 0);
				if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
				$order = 1;
        while($rs = pg_fetch_assoc($ret[1])){
          $class['name'] = $rs[$class_item];
					$class['classification'] = $classification_name;
          $class['order'] = $order++;
          $class['expression'] = $rs[$class_item];
          $classes[] = $class;
        }
      } break;

      case 2 : {		# gleiche Klassengröße
        $sql = "
          SELECT
            min(" . $class_item . "),
            max(" . $class_item . ")
          FROM
            (
              " . $data_sql . "
            ) AS data
        ";
        #echo '<br>' . $sql;

        $ret=$layerdb->execSQL($sql, 4, 0);
    		if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
        while($rs = pg_fetch_assoc($ret[1])){
          $min = $rs['min'];
          $max = $rs['max'];
        }
        $round_faktor = pow(10, strlen((string)$min) - 1);
        $range_floor = floor($min / $round_faktor) * $round_faktor;
        $range_ceil = ceil($max / $round_faktor) * $round_faktor;
        $range_step = ($range_ceil - $range_floor) / ($num_classes);

        for ($order = 1; $range_floor < $range_ceil; $range_floor += $range_step) {
          $class['name'] = $range_floor . ' - ' . ($range_floor + $range_step);
					$class['classification'] = $classification_name;
          $class['order'] = $order++;
          $class['expression'] = '([' . $class_item . '] >= ' . $range_floor . ' AND [' . $class_item . '] < ' . ($range_floor + $range_step) . ')';
          $classes[] = $class;
        }
      } break;

      case 3 : {		# gleiche Anzahl Klassenmitglieder
        $sql = "
          SELECT
            " . $class_item . "
          FROM
            (
              " . $data_sql . "
            ) AS data
          ORDER BY
            " . replace_semicolon($class_item) . "
        ";

        $ret=$layerdb->execSQL($sql, 4, 0);
    		if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
        $rows = pg_fetch_all($ret[1]);
        $range_floor = 0;
        $range_ceil = count($rows);
        $range_step = floor($range_ceil / $num_classes);
        for ($order = 1; $range_floor < ($range_ceil - 1); $range_floor += $range_step) {
          if ($order == $num_classes) {
            $range_step = $range_ceil - $range_floor - 1;
          }
          $class['name'] = $rows[$range_floor][$class_item] . ' - ' . $rows[$range_floor + $range_step][$class_item];
					$class['classification'] = $classification_name;
          $class['order'] = $order++;
          $class['expression'] = '([' . $class_item . '] >= ' . $rows[$range_floor][$class_item] . ' AND [' . $class_item . '] < ' . $rows[$range_floor + $range_step][$class_item] . ')';
          $classes[] = $class;
        }
      } break;

      case 4 : {		# Clustering nach Jenk, Initialisierung mit Histogramm-Maxima
        include_(CLASSPATH.'k-Means_clustering.php');
        // fetch data
        $sql = "
          SELECT
            " . $class_item . "
          FROM
            (
              " . $data_sql . "
            ) AS data
          ORDER BY
            " . replace_semicolon($class_item) . "
        ";
        $ret=$layerdb->execSQL($sql, 4, 0);
				if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
        $data = pg_fetch_all($ret[1]);

        // flatten data
        $flatData = array();
        array_walk($data, function($item, $idx, $data) {
          $data[0][] = (float)($item[$data[1]]);
        }, array(&$flatData,$class_item));

        // fetch histogram
        $sql = "
          SELECT
            round(
              (" . $class_item . " - (
                SELECT
                  min(" . $class_item . ")
                FROM
                  (" . $data_sql . ") AS data
                )
              ) * (
                SELECT
                  100 / (max(" . $class_item . ") - min(" . $class_item . "))
                FROM
                  (" . $data_sql . ") AS data
              )
            ) prozent,
            count(*) anzahl
          FROM
            (" . $data_sql . ") AS data
          GROUP BY
            prozent
          ORDER BY
            prozent
        ";
        $ret=$layerdb->execSQL($sql, 4, 0);
				if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
        $histogram = pg_fetch_all($ret[1]);
        // flatten histogram
        $flatHistogram = array_fill(0,101,0);
        array_walk($histogram, function($item, $idx, $data) {
           $data[0][$item['prozent']] = (int)($item['anzahl']);
        }, array(&$flatHistogram));

        // determine the maxima of the smoothened histogram as seeds for k-Means-Clustering
        $seeds = kMeansClustering::seedsFromLocalMaxima($flatHistogram, $flatData);
        // optimize classes (clusters) by k-Means
        kMeansClustering::kMeansWithSeeds($flatData, $seeds);

        // calculate class ranges from cluster centers
        $ranges[] = $data[0][$class_item];
        for ($sIdx = 1; $sIdx < count($seeds); $sIdx++) {
          $ranges[] = ($seeds[$sIdx-1]+ $seeds[$sIdx]) / 2;
        }
        $ranges[]= $data[count($data)-1][$class_item];

        // build classes
        for ($order = $rIdx = 1; $rIdx < count($ranges); $rIdx++) {
          $class['name'] = $ranges[$rIdx-1] . ' - ' . $ranges[$rIdx];
          $class['classification'] = $classification_name;
          $class['order'] = $order++;
          $class['expression'] = '([' . $class_item . '] >= ' . $ranges[$rIdx-1] . ' AND [' . $class_item . '] < ' . $ranges[$rIdx] . ')';
          $classes[] = $class;
        }
      } break;

      case 5 : {		# Clustering nach Jenk, Mininierung Abweichung i.d. Klassen
        include_(CLASSPATH.'k-Means_clustering.php');
        // fetch data
        $sql = "
          SELECT
            " . $class_item . "
          FROM
            (
              " . $data_sql . "
            ) AS data
          ORDER BY
            " . replace_semicolon($class_item) . "
        ";
        #echo '<p>' . $sql;
        $ret=$layerdb->execSQL($sql, 4, 0);
				if ($ret['success']==0) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
        $data = pg_fetch_all($ret[1]);

        if (count($data) > 0) {
          // flatten data
          $flatData = array();
          array_walk($data, function($item, $idx, $data) {
            $data[0][] = (float)($item[$data[1]]);
          }, array(&$flatData,$class_item));

          // divide data into #num_classes# clusters by 'divide and conquer'-approach
          // starting with a single cluster, every iteration the cluster with highest
          // residual energy is iteratively split into two, until the number of
          // clusters equals #num_classes#
          $centers = kMeansClustering::kMeansNoSeeds($flatData, $num_classes);

          // calculate class ranges from cluster centers
          $ranges[] = $data[0][$class_item];
          for ($cIdx = 1; $cIdx < count($centers); $cIdx++) {
            $ranges[] = round(($centers[$cIdx-1]+ $centers[$cIdx]) / 2, 4);
          }
          $ranges[]= $data[count($data)-1][$class_item];

          // build classes
          for ($order = $rIdx = 1; $rIdx < count($ranges); $rIdx++) {
            $class['name'] = $ranges[$rIdx-1] . ' - ' . $ranges[$rIdx];
            $class['classification'] = $classification_name;
            $class['order'] = $order++;
            $class['expression'] = '([' . $class_item . '] >= ' . str_replace(',', '', $ranges[$rIdx-1]) . ' AND [' . $class_item . '] < ' . str_replace(',', '', $ranges[$rIdx]) . ')';
            $classes[] = $class;
          }
        }
      } break;
    }
		if($method == 1){			# für jeden Wert eine Klasse: zufällige Farben
			$colors = read_colors($this->database);
			foreach ($classes AS $key => $class) {
				shuffle($colors);
				$classes[$key]['style_color'] = $colors[0]['red'].' '.$colors[0]['green'].' '.$colors[0]['blue'];
				$classes[$key]['style_outlinecolor'] = '0 0 0';
			}
		}
		else{					# für alle anderen Methoden: kontinuierlicher Farbverlauf der ausgewählten Farbe von hell nach dunkel
			$rgb = explode(' ', $classification_color);
			$hsl = rgb2hsl($rgb[0], $rgb[1], $rgb[2]);
			$lightness = 0.8;
			$lightness_step = ($lightness - $hsl[2]) / (count($classes)-1);
			$half_lightness_step = $lightness_step / 2;
			foreach ($classes AS $key => $class) {
				$rgb1 = hsl2rgb($hsl[0], $hsl[1], $lightness);
				$rgb2 = hsl2rgb($hsl[0], $hsl[1], $lightness-$half_lightness_step);
				$classes[$key]['style_color'] = $rgb1[0].' '.$rgb1[1].' '.$rgb1[2];
				$classes[$key]['style_outlinecolor'] = $rgb2[0].' '.$rgb2[1].' '.$rgb2[2];
				$lightness -= $lightness_step;
			}
		}
    return $classes;
  }

	function LayerAnlegen() {
		global $supportedLanguages;
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		if (trim($this->formvars['id']) !='' and $mapDB->id_exists('layer',$this->formvars['id'])) {
			$table_information = $mapDB->get_table_information($this->Stelle->database->dbName, 'layer');
			$this->add_message('error', 'Die Id: ' . $this->formvars['id'] . ' existiert schon. Nächste freie Layer_ID ist ' . $table_information['AUTO_INCREMENT']);
			$this->use_form_data = true;
		}
		else {
			$this->formvars['selected_layer_id'] = $mapDB->newLayer($this->formvars);
			if ($this->formvars['selected_layer_id'] == 0) {
				return false;
			}

			if ($this->formvars['connectiontype'] == 6 AND $this->formvars['pfad'] != '') {
				#---------- Speichern der Layerattribute -------------------
				$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				$path = $this->formvars['pfad'];
				$duplicate_criterion = $this->formvars['duplicate_criterion'];
				$all_layer_params = $mapDB->get_all_layer_params_default_values();
			  $attributes = $mapDB->load_attributes(
					$layerdb,
					replace_params_rolle(
						$path,
						['duplicate_criterion' => $this->formvars['duplicate_criterion']]
					),
					$this->formvars['sync']
				);
				$mapDB->save_postgis_attributes($layerdb, $this->formvars['selected_layer_id'], $attributes, $this->formvars['maintable'], $this->formvars['schema']);
				$mapDB->delete_old_attributes($this->formvars['selected_layer_id'], $attributes);
				#---------- Speichern der Layerattribute -------------------
			}
			if ($this->formvars['connectiontype'] == MS_WFS) {
				include_(CLASSPATH.'wfs.php');
				$url = $this->formvars['connection'];
				$version = $this->formvars['wms_server_version'];
				$epsg = $this->formvars['epsg_code'];
				$typename = $this->formvars['wms_name'];
				$namespace = substr($typename, 0, strpos($typename, ':'));
				$username = $this->formvars['wms_auth_username'];
				$password = $this->formvars['wms_auth_password'];
				$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
				$wfs->describe_featuretype_request();
				$attributes = $wfs->get_attributes();
				$mapDB->save_attributes(NULL, $this->formvars['selected_layer_id'], $attributes);
			}

			include_once(CLASSPATH . 'Layer.php');
			$layer = Layer::find_by_id($this, $this->formvars['selected_layer_id']);
			if ($layer AND $layer->get('write_mapserver_templates')) {
				$layer->write_mapserver_templates($mapDB, 'Formular');
			}

			# Klassen übernehmen (aber als neue Klassen anlegen)
			if ($this->formvars['old_id'] != ''){
				$classes = $mapDB->read_Classes($this->formvars['old_id']);
				for($i = 0; $i < count($classes); $i++){
					$mapDB->copyClass($classes[$i]['Class_ID'], $this->formvars['selected_layer_id']);
				}
			}
		}
  }

	/**
	 * This function write the mapfile, header und footer templates and a wrapper file for the current map object
	 * If stelle_id is set, the location is in a subdirectory of OWS_ONLINE_RESOURCE.
	 * WMS_MAPFILE_PATH
	 */
	function write_mapfile($mapfile, $wrapperfile) {
		try {
			# Schreibe MapFile-Templates
			$this->save_web_header_template();
			$this->save_web_footer_template();
			$path_parts = pathinfo(WMS_MAPFILE_PATH . $mapfile);

			# Schreibe MapFile
			if (!file_exists($path_parts['dirname'])) {
				mkdir($path_parts['dirname'], 0775, true);
			}
			$this->saveMap(WMS_MAPFILE_PATH . $mapfile);
			// Replacing 'OUTLINEWIDTH 1.00001' is a workaround to the bug that mapserver did not write 'GEOMTRANSFORM "centroid"' to Mapfile.
			file_put_contents(
				WMS_MAPFILE_PATH . $mapfile,
				str_replace(
					'OUTLINEWIDTH 1.00001',
					'GEOMTRANSFORM "centroid"',
					file_get_contents(WMS_MAPFILE_PATH . $mapfile)
				)
			);

			$wrapperpath = str_replace(URL, INSTALLPATH, OWS_SERVICE_ONLINERESOURCE);
			$path_parts = pathinfo($wrapperpath . $wrapperfile);
			if (!file_exists($path_parts['dirname'])) {
				mkdir($path_parts['dirname'], 0775, true);
				$this->write_xlog('MapFile-Pfad ' . $path_parts['dirname'] . ' angelegt.');
			}
			else {
				$this->write_xlog('MapFile-Pfad ' . $path_parts['dirname'] . ' existiert schon.');
			}
			if (!file_exists($wrapperpath . $wrapperfile)) {
				file_put_contents($wrapperpath . $wrapperfile, '#!/bin/sh
MAPSERV="/usr/lib/cgi-bin/mapserv"
MS_MAPFILE="' . WMS_MAPFILE_PATH . $mapfile . '" exec ${MAPSERV}');
				$this->write_xlog('MapFile-Wrapper ' . $wrapperpath . $wrapperfile . ' geschrieben.');
				chmod($wrapperpath . $wrapperfile, 0775);
			}
		} catch (Exception $ex) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Speichern der Map-Datei ' . WMS_MAPFILE_PATH . $mapfile . ', der Templates oder des Wrappers ' . $wrapperpath . $wrapperfile . ' für den Dienst in Funktion write_mapfile. ' . $ex
			);
		}
		return array(
			'success' => true,
			'msg' => 'MapDatei, Templates und Wrapper erfolgreich geschrieben.'
		);
	}

  /**
  * This function update layers settings of $formvars['selected_layer_id'] and
  * duplicate all layer that have the selected_layer_id in duplicate_from_layer_id
  * if $duplicate is false it updates also assignments to stelle for selected_layer
  * @param array $formvars Formular variables used to change the layer
  * @param boolean $duplicate True if the layer is a duplicate from another
  */
	function LayerAendern($formvars, $duplicate = false) {
		include_once(CLASSPATH . 'Layer.php');
		global $supportedLanguages;
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$formvars['labelitem'] = $formvars['labelitems_name'][1];
		$mapDB->updateLayer($formvars, $duplicate);
		$layer = Layer::find_by_id($this, $formvars['selected_layer_id']);
		if ($layer) {
			$layer->update_datasources($this, ($formvars['datasource_ids'] ?: []));
		}

		if ($formvars['connectiontype'] == 6) {
			if ($formvars['connection_id'] != '') {
				$layerdb = $mapDB->getlayerdatabase($formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				if ($formvars['Data'] != '') {
					$data_attributes = $mapDB->getDataAttributes($layerdb, $formvars['selected_layer_id'], ['assoc' => true]);
					foreach ($formvars['labelitems_name'] ?: [] as $labelitem) {
						if ($labelitem != '' AND !array_key_exists($labelitem, $data_attributes)) {
							$this->add_message('error', 'Das Attribut ' . $labelitem . ' wird nicht im Data-Feld abgefragt!');
						}
					}
					$layer->update_labelitems($this, ($formvars['labelitems_name'] ?: []), ($formvars['labelitems_alias'] ?: []));
				}
				if ($formvars['pfad'] != '') {
					#---------- Speichern der Layerattribute -------------------
					$all_layer_params = $mapDB->get_all_layer_params_default_values();
					$attributes = $mapDB->load_attributes(
						$layerdb,
						replace_params_rolle(
							$formvars['pfad'],
							array_merge($all_layer_params, ['duplicate_criterion' => $formvars['duplicate_criterion']])
						),
						$this->formvars['sync']
					);
					$geom_column = $attributes['the_geom'];
					$old_attributes = $mapDB->read_layer_attributes($formvars['selected_layer_id'], $layerdb, NULL, false, false, false, false);
					for ($i = 0; $i < count($attributes)-2; $i++) {
						$attributes[$i]['order'] = $last_order = $old_attributes['order'][$old_attributes['indizes'][$attributes[$i]['name']]] ?: ($last_order +  0.01);
						$attributes[$i]['default'] = $old_attributes['default'][$old_attributes['indizes'][$attributes[$i]['name']]] ?: $attributes[$i]['default'];
					}
					unset($attributes['the_geom']);
					unset($attributes['the_geom_id']);
					usort($attributes, 'compare_orders2');
					$mapDB->save_postgis_attributes($layerdb, $formvars['selected_layer_id'], $attributes, $formvars['maintable'], $formvars['schema']);
					#---------- Speichern der Layerattribute -------------------
					if ($this->plugin_loaded('mobile')) {
						$this->mobile_prepare_sync_table(
							$layerdb,
							$formvars['sync']
						);
						$this->mobile_prepare_layer_sync(
							$layerdb,
							$formvars['selected_layer_id'],
							$formvars['sync']
						);
						$this->mobile_prepare_layer_sync_all(
							$layerdb,
							$formvars['selected_layer_id'],
							$formvars['sync']
						);
						$this->mobile_validate_layer_sync(
							$layerdb,
							$formvars['selected_layer_id'],
							$formvars['sync']
						);
					}
					include_once(CLASSPATH . 'Layer.php');
					$layer = Layer::find_by_id($this, $this->formvars['selected_layer_id']);
					if ($layer AND $layer->get('write_mapserver_templates')) {
						$layer->write_mapserver_templates($mapDB);
					}
					else {
						$layer->remove_mapserver_templates();
					}
					$layer->update(array('geom_column' => $geom_column), false);
				}
				if ($formvars['pfad'] == '' OR $attributes != NULL){
					$mapDB->delete_old_attributes($formvars['selected_layer_id'], $attributes);
				}
			}
			else {
				showAlert('Keine connection angegeben.');
			}
		}
		if ($formvars['connectiontype'] == MS_WFS) {
			include_(CLASSPATH.'wfs.php');
			$url = $formvars['connection'];
			$version = $formvars['wms_server_version'];
			$epsg = $formvars['epsg_code'];
			$typename = $formvars['wms_name'];
			$namespace = substr($typename, 0, strpos($typename, ':'));
			$username = $formvars['wms_auth_username'];
			$password = $formvars['wms_auth_password'];
			$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
			$wfs->describe_featuretype_request();
			$attributes = $wfs->get_attributes();
			$mapDB->save_attributes(NULL, $formvars['selected_layer_id'], $attributes);
			if($attributes != NULL){
				$mapDB->delete_old_attributes($formvars['selected_layer_id'], $attributes);
			}
		}

		$stellen_ids = ($formvars['selstellen'] != '' ? explode(', ', $formvars['selstellen']) : array());

		if (($this->formvars['stellenzuweisung'] == 1 OR $this->formvars['gruppenaenderung'] == 1) AND !$duplicate) {
      # Stellenzuweisung
  		$stellen = $this->addLayersToStellen(
        array($formvars['selected_layer_id']),
        $stellen_ids,
  			NULL,
  			$formvars['assign_default_values']
      );
  		if ($formvars['assign_default_values']) {
  			$this->add_message('notice', 'Die Defaultwerte wurden an die zugeordneten Stellen übertragen.');
  		}
      # Löschen der in der Selectbox entfernten Stellen
      $layerstellen = $mapDB->get_stellen_from_layer($formvars['selected_layer_id']);
      for ($i = 0; $i < count($layerstellen['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($stellen); $j++){
          if($stellen[$j] == $layerstellen['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
					# Stellenzuordnung wurde entfernt
          $deletestellen[] = $layerstellen['ID'][$i];
        }
      }
      if ($deletestellen != 0){
        $this->removeLayerFromStellen($formvars['selected_layer_id'], $deletestellen);
				for ($i = 0; $i < count($deletestellen); $i++) {
					$stelle = new stelle($deletestellen[$i], $this->database);
					$stelle->updateLayerParams();
					$this->update_layer_parameter_in_rollen($deletestellen[$i]);
				}
      }
      # /Löschen der in der Selectbox entfernten Stellen
    }

		for ($i = 0; $i < count($stellen_ids); $i++) {
			$stelle = new stelle($stellen_ids[$i], $this->database);
			$stelle->updateLayerParams();
			$this->update_layer_parameter_in_rollen($stellen_ids[$i]);
		}

		$this->update_duplicate_layers($formvars);
	}

	/**
	* Funktion fragt layer ab, die dupliziert werden sollen und ruft für jeden die update_layer Funktion auf.
	* @param array $formvars Die Werte des Layers, der dupliziert werden sollen
	*/
	function update_duplicate_layers($formvars) {
		foreach (Layer::find_by_duplicate_from_layer_id($this->database, $formvars['selected_layer_id']) AS $duplicate_layer_id) {
			$formvars['id'] = $formvars['selected_layer_id'] = $duplicate_layer_id;
			$this->LayerAendern($formvars, true);
		}
	}

	/**
	* Weist Layer Stellen zu
	* @param array Array von layer_ids, die den Stellen zugewiesen werden sollen.
	* @param array Array von Stellen, denen die Layer zugewiesen werden sollen.
	* @param string (optional) Text der in used_layer im Attribut Filter verwendet werden soll.
	* @param boolean (optional) Flag, ob die Defaultwerte auch an die bereits zugeordneten Stellen übertragen werden sollen
	* @return void
	*/
	function addLayersToStellen($layer_ids, $stellen_ids, $filter = '', $assign_default_values = false, $privileg = 'default') {
		for ($i = 0; $i < count($stellen_ids); $i++) {
			if (!isset($this->already_assigned_stellen[$stellen_ids[$i]])) {
				$stelle = new stelle($stellen_ids[$i], $this->database);
				$stelle->addLayer($layer_ids, $filter, $assign_default_values, (($privileg == 'editable_only_in_this_stelle' AND $stellen_ids[$i] == $this->Stelle->id )? 'editable' : $privileg));
				$users = $stelle->getUser();
				for ($j = 0; $j < count_or_0($users['ID']); $j++) {
					$this->user->rolle->setGroups($users['ID'][$j], $stellen_ids[$i], $stelle->default_user_id, $layer_ids); # Hinzufügen der Layergruppen der selektierten Layer zur Rolle
					$this->user->rolle->setLayer($users['ID'][$j], $stellen_ids[$i], $stelle->default_user_id); # Hinzufügen der Layer zur Rolle
				}
				$this->already_assigned_stellen[$stellen_ids[$i]] = true;
				# Kindstellen
				$children = $stelle->getChildren($stellen_ids[$i], " ORDER BY Bezeichnung", 'only_ids', false);
				$stellen_ids = array_merge($stellen_ids, $this->addLayersToStellen($layer_ids, $children, $filter, $assign_default_values));
			}
		}
		return $stellen_ids;
	}

	function removeLayerFromStellen($layer_id, $deletestellen) {
		for($i = 0; $i < count($deletestellen); $i++){
			$stelle = new stelle($deletestellen[$i], $this->database);
			$stelle->deleteLayer(array($layer_id), $this->pgdatabase);
			$users = $stelle->getUser();
			for($j = 0; $j < count($users['ID']); $j++){
				$this->user->rolle->deleteLayer($users['ID'][$j], array($deletestellen[$i]), array($layer_id));
				$this->user->rolle->updateGroups($users['ID'][$j],$deletestellen[$i], $layer_id);
			}
			# Kindstellen
			$children = $stelle->getChildren($deletestellen[$i], " ORDER BY Bezeichnung", 'only_ids', false);
			$this->removeLayerFromStellen($layer_id, $children);
		}
	}

	function LayerLoeschen($delete_maintable = false){
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$mapDB->deleteLayer($this->formvars['selected_layer_id'], $delete_maintable);

		# auch die Klassen löschen
		$this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id']);
		for($i = 0; $i < count($this->classes); $i++){
			$mapDB->delete_Class($this->classes[$i]['Class_ID']);
		}
		# layer_attributes löschen
		$mapDB->delete_layer_attributes($this->formvars['selected_layer_id']);
		# Filter löschen
		$mapDB->delete_layer_filterattributes($this->formvars['selected_layer_id']);		# kann ab Version 3.5 weg da dann FK vorhanden
	}

  function DatentypenAnzeigen() {
    # Abfragen aller in mysql registrierten Datentypen
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'name';
    }
    $this->datatypes = $mapDB->getall_Datatypes($this->formvars['order']);
    $this->titel='Datentypen';
    $this->main='datatype_list.php';
    $this->output();
  }

	function LayerAnzeigen() {
		# Abfragen aller Layer
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		if (value_of($this->formvars, 'order') == '') {
			$this->formvars['order'] = 'Name';
		}
		$this->layerdaten = $mapDB->getall_Layer($this->formvars['order'], false, $this->user->id, $this->Stelle->id);
		$this->titel = 'Layerdaten';
		$this->main = 'layerdaten.php';
		$this->output();
	}

  /**
   * https://testportal-plandigital.de/kvwmap/index.php?go=get_generic_layer_data_sql&selected_layer_id=71&csrf_token=75c599ad3ff8bbc8f90245a456af9d01
   * Wird zur Zeit nur in xplankonverter verwendet
   * ToDo: Muss noch umgestrickt werden dass das generisch geht, bzw. ein extra xplankonverter case draus machen, der diese methode erweitert.
   */
	function get_generic_layer_data_sql($selected_layer_id) {
		if ($selected_layer_id == '') {
			return array(
				'generic_layer_data_sql' => array(
						'success' => false,
						'msg' => 'Der Parameter selected_layer_id wurde nicht angegeben.'
				),
				'layer' => null
			);
		}
		include_once(CLASSPATH . 'Layer.php');
		$layer = Layer::find_by_id($this, $selected_layer_id);
		if (!$layer) {
			return array(
				'generic_layer_data_sql' => array(
					'success' => false,
					'msg' => 'Layer mit id: ' . $selected_layer_id . ' nicht gefunden.'
				),
				'layer' => $layer
			);
		}

		return array(
      'generic_layer_data_sql' => $layer->get_generic_data_sql(),
      'layer' => $layer
    );
	}

	function Layergruppen_Anzeigen() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$this->layergruppen = LayerGroup::find(
			$this,
			'', # default alle
			($this->formvars['order'] == '' ? 'Gruppenname' : $this->formvars['order'])  # default nach Name
		);
		$this->main = 'layergroups.php';
		$this->output();
	}

	function Layergruppe_Editor() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$this->layergruppe = new LayerGroup($this);
		if ($this->formvars['selected_group_id'] != '') {
			$this->layergruppe = LayerGroup::find_by_id($this, $this->formvars['selected_group_id']);
		}
		else {
			$this->layergruppe->setKeysFromTable();
		}
		$this->main = 'layergruppe_formular.php';
		$this->output();
	}

	function Layergruppe_Speichern() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$this->layergruppe = new LayerGroup($this);
		$this->layergruppe->data = formvars_strip($this->formvars, $this->layergruppe->setKeysFromTable(), 'keep');
		$this->layergruppe->set('Gruppenname', $this->formvars['Gruppenname']);
		if (!$this->layergruppe->get('obergruppe')) {
			$this->layergruppe->set('obergruppe', '');
		}
		if (!$this->layergruppe->get('selectable_for_shared_layers')) {
			$this->layergruppe->set('selectable_for_shared_layers', 0);
		}
		$results = $this->layergruppe->validate();
		if (empty($results)) {
			$results = $this->layergruppe->create();
		}
		if ($results[0]['success']) {
			$this->add_message('notice', 'Layergruppe erfolgreich angelegt.');
			$this->formvars['order'] = 'Gruppenname';
			$this->Layergruppen_Anzeigen();
		}
		else {
			$this->add_message('array', array_values($results));
			$this->Layergruppe_Editor();
		}
	}

	function Layergruppe_Aendern() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$this->layergruppe = LayerGroup::find_by_id($this, $this->formvars['selected_group_id']);
		$this->layergruppe->setData($this->formvars);
		$results = $this->layergruppe->validate();
		if (empty($results)) {
			$results = $this->layergruppe->update();
		}
		if ($results[0]['success']) {
			rolle::setGroupsForAll($this->database);
			$this->add_message('notice', 'Layergruppe erfolgreich aktualisiert.');
		}
		else {
			$this->add_message('array', array_values($results));
		}
		$this->Layergruppe_Editor();
	}

	function Layergruppe_Loeschen(){
		include_once(CLASSPATH . 'LayerGroup.php');
		$this->layergruppe = LayerGroup::find_by_id($this, $this->formvars['selected_group_id']);
		$this->layergruppe->delete();
		$this->add_message('notice', 'Layergruppe erfolgreich gelöscht.');
	}

	function invitations_list() {
		include_once(CLASSPATH . 'Invitation.php');
		global $admin_stellen;
		$this->invitations = Invitation::find(
			$this,
			(in_array($this->Stelle->id, $admin_stellen) ? 'true' : 'inviter_id = ' . $this->user->id),
			$this->formvars['order'] ?: 'email'
		);

		$this->main = 'invitations.php';
		$this->output();
	}

	function invitation_formular() {
		global $admin_stellen;
		include_once(CLASSPATH . 'FormObject.php');
		include_once(CLASSPATH . 'Invitation.php');
		$this->invitation = new Invitation($this);
		if ($this->formvars['selected_invitation_id'] != '') {
			$this->invitation = Invitation::find_by_id($this, $this->formvars['selected_invitation_id']);
			$this->formvars = array_merge($this->formvars, array(
				'token' => $this->invitation->get('token'),
				'email' => $this->invitation->get('email'),
				'name' => $this->invitation->get('name'),
				'vorname' => $this->invitation->get('vorname'),
				'loginname' => $this->invitation->get('loginname'),
				'stelle_id' => $this->invitation->get('stelle_id'),
				'inviter_id' => $this->invitation->get('inviter_id')
			));
		}
		else {
			$this->formvars['token'] = uuid();
			$this->formvars['inviter_id'] = $this->user->id;
			$this->invitation->setKeysFromTable();
		}
		$myobj = new MyObject($this, 'stelle');
		if (in_array($this->Stelle->id, $admin_stellen)) {
			$stellen = $myobj->find_by_sql(
				array(
					'select' => 's.`ID`, s.`Bezeichnung`',
					'from' => 'stelle s',
					'order' => 'bezeichnung'
				)
			);
		}
		else {
			$stellen = $myobj->find_by_sql(
				array(
					'select' => 's.`ID`, s.`Bezeichnung`',
					'from' => 'stelle s, rolle r',
					'where' => 's.ID = r.stelle_id AND r.user_id = ' . $this->user->id,
					'order' => 'bezeichnung'
				)
			);
		}

		$this->invitation->stellen = array_map(
			function($stelle) {
				return array(
					'value' => $stelle->get('ID'),
					'output' => $stelle->get('Bezeichnung')
				);
			},
			$stellen
		);
		$this->main = 'invitation_formular.php';
		$this->output();
	}

	function invitation_save() {
		include_once(CLASSPATH . 'Invitation.php');
		$this->invitation = new Invitation($this);
		$this->invitation->data = formvars_strip($this->formvars, $this->invitation->setKeysFromTable(), 'keep');

		$results = $this->invitation->validate();
		if (empty($results)) {
			$results = $this->invitation->create();
		}
		if ($results[0]['success']) {
			$this->invitation = Invitation::find_by_id($this, $this->invitation->get('token'));
			$this->add_message('info', 'Neuer Nutzer ist vorgemerkt.<br>
				Zum Einladen per E-Mail<br>
				klicken Sie <a href="mailto:' . $this->invitation->mailto_text() . '">hier</a>!<br>
				Die E-Mail enthält den Link zur Einladung.');
			$this->invitations_list();
		}
		else {
			$this->add_message('array', array_values($results));
			$this->invitation_formular();
		}
	}

	function invitation_update() {
		include_once(CLASSPATH . 'Invitation.php');
		$this->invitation = Invitation::find_by_id($this, $this->formvars['selected_invitation_id']);
		$results = $this->invitation->validate();
		if (empty($results)) {
			$results = $this->invitation->update(
				array(
					'token' => $this->formvars['token'],
					'email' => $this->formvars['email'],
					'name' => $this->formvars['name'],
					'vorname' => $this->formvars['vorname'],
					'loginname' => $this->formvars['loginname'],
					'stelle_id' => $this->formvars['stelle_id'],
					'inviter_id' => $this->formvars['inviter_id']
				),
				false
			);
		}
		if ($results[0]['success']) {
			$this->add_message(
				'info',
				'Daten der Einladung aktualisiert.<br><a href="mailto:' . $this->invitation->mailto_text() . '">Einladung noch mal per E-Mail verschicken</a>'
			);
		}
		else {
			$this->add_message('array', array_values($results));
		}
		$this->invitation_formular();
	}

	function invitation_delete() {
		include_once(CLASSPATH . 'Invitation.php');
		$this->invitation = Invitation::find_by_id($this, $this->formvars['selected_invitation_id']);
		$this->invitation->delete();
		$this->add_message('notice', 'Einladung erfolgreich gelöscht.');
	}
	/**
	 * @param array $attributes Attribut-Array des übergeordneten Layers
	 * @param int $j Zähler in diesem Array mit dem SubForm-Attribut
	 * @param string $maintable Hauptabelle des übergeordneten Layers
	 * @param array $result Datensatz im übergeordneten Layer
	 */
	function getSubFormResultSet($attributes, $j, $maintable, $result){
		$layerid_save = $this->formvars['selected_layer_id'];
		$this->formvars['selected_layer_id'] = $attributes['subform_layer_id'][$j];
		$this->formvars['no_output'] = true;
		for ($p = 0; $p < count($attributes['name']); $p++) {			# erstmal alle Suchparameter des übergeordneten Layers für die Layersuche leeren
			$this->formvars['value_'.$attributes['name'][$p]] = '';
			$this->formvars['operator_'.$attributes['name'][$p]] = '';
		}
		$this->formvars['value_' . $maintable . '_oid'] = '';
		for ($p = 0; $p < count($attributes['subform_pkeys'][$j]); $p++) {			# die Suchparameter für die Layersuche
			if (strpos($attributes['subform_pkeys'][$j][$p], ':')) {
				$exp = explode(':', $attributes['subform_pkeys'][$j][$p]);
				$key[$p] = $exp[0];			# Verknüpfungsattribut im Ober-Layer
				$subkey[$p] = $exp[1];	# Verknüpfungsattribut im Sub-Layer
			}
			else {
				$key[$p] = $subkey[$p] = $attributes['subform_pkeys'][$j][$p];
			}		
			$this->formvars['value_' . $subkey[$p]] = $result[$key[$p]];
			$this->formvars['operator_' . $subkey[$p]] = '=';
		}
		$this->GenerischeSuche_Suchen();
		for ($p = 0; $p < count($key); $p++) {			# die Suchparameter für die Layersuche wieder leeren
			$this->formvars['value_' . $subkey[$p]] = '';
		}
		$this->formvars['selected_layer_id'] = $layerid_save;
	}

	/**
	 * Funktion liefert eine Datei, die in der Datenbank in einem bytea-Feld gespeichert ist
	 */
	function get_document() {
		$layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
		$this->formvars['selected_layer_id'] = $this->formvars['layer_id'];
		$this->formvars['value_' . $layerset[0]['maintable'] . '_oid'] = $this->formvars['oid'];
		$this->formvars['operator_' . $layerset[0]['maintable'] . '_oid'] = '=';
		$this->formvars['only_filenames'] = false;
		$this->formvars['no_output'] = true;
		$this->formvars['no_last_query'] = true;
		$this->formvars['no_last_search'] = true;
		$this->GenerischeSuche_Suchen();
		$value = pg_unescape_bytea($this->qlayerset[0]['shape'][0][$this->formvars['name']]);
		$data = explode('&original_name=', $value);
		$type = strtolower(array_pop(explode('.', $data[1])));
		if (in_array($type, array('jpg', 'gif', 'png'))) {
			header("Content-type: image/" . $type);
		}
		else {
			header("Content-type: application/" . $type);
		}
		header("Content-disposition: attachment; filename=" . $data[1]);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		echo $data[0];
	}

	function GenerischeSuche_Suchen() {
		$this->formvars['search'] = true;
		$this->formvars['selected_layer_id'] = $this->formvars['selected_layer_id'];
		if ($this->last_query != '') {
			$this->formvars['selected_layer_id'] = $this->last_query['layer_ids'][0];
		}
		if ($this->formvars['selected_layer_id'] > 0) {
			$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		}
		else {
			$layerset = $this->user->rolle->getRollenlayer(-$this->formvars['selected_layer_id']);
		}
		if ($layerset == NULL) {
			$ret['success'] = false;
			$ret['msg'] = 'Der Layer mit der ID '.$this->formvars['selected_layer_id'].' ist der aktuellen Stelle nicht zugeordnet.';
		}
		if (
			(
				$this->formvars['without_diagramms'] OR
				in_array($this->formvars['format'], array('json', 'json_result')) OR
				$this->formvars['mime_type'] == 'json' OR
				empty($this->formvars['selected_layer_id'])
			)
		) {
			$layerset[0]['charts'] = array();
		}
		else {
			include_once(CLASSPATH . 'LayerChart.php');
			$layerset[0]['charts'] = LayerChart::find($this, '`layer_id` = ' . $this->formvars['selected_layer_id']);
		}
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		switch ($layerset[0]['connectiontype']) {
			case MS_POSTGIS : {
				$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				$path = $layerset[0]['pfad'];
				$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
				$layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames'], false, true);
				if ($layerset[0]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
					$layerset[0]['maintable'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];
				}
				if ($layerset[0]['oid'] == '' AND count_or_0($layerset[0]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
					$layerset[0]['oid'] = $layerset[0]['attributes']['pk'][0];
				}

				$query_parts = $mapDB->getQueryParts($layerset[0], $privileges, $this->formvars['only_filenames']);
				$pfad = $query_parts['query'];

				for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
					$layerset[0]['attributes']['privileg'][$j] = $privileges[$layerset[0]['attributes']['name'][$j]];
					$layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = $privileges[$layerset[0]['attributes']['name'][$j]];
				}
				$sql_where = '';
				$spatial_sql_where = '';
				for ($m = 0; $m <= (value_of($this->formvars, 'searchmask_count') ?: 0); $m++){
					if ($m > 0){				// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
						$prefix = $m . '_';
						$sql_where .= ' ' . $this->formvars['boolean_operator_'.$m].' ';			// mit AND/OR verketten
					}
					else {
						$prefix = '';
						$sql_where .= ' AND (';		// eine äußere Klammer, dadurch die ORs darin eingeschlossen sind
					}
					$sql_where .= ' (1=1';			// klammern
					$value_like = '';
					$operator_like = '';
					for ($i = 0; $i < count($layerset[0]['attributes']['name']); $i++) {
						if (value_of($this->formvars, $prefix . 'operator_' . $layerset[0]['attributes']['name'][$i]) == 'IN') {
							# Sanitize all elements in list delimited with | separately and compose afterward again together to list string
							$value = explode('|', value_of($this->formvars, $prefix . 'value_' . $layerset[0]['attributes']['name'][$i]));
							$value = sanitize(explode('|', value_of($this->formvars, $prefix . 'value_' . $layerset[0]['attributes']['name'][$i])), $layerset[0]['attributes']['type'][$i], true);
							$value = implode('|', $value);
						}
						else {
							$value = sanitize(value_of($this->formvars, $prefix . 'value_' . $layerset[0]['attributes']['name'][$i]), $layerset[0]['attributes']['type'][$i], true);
						}
						$operator = sanitize(value_of($this->formvars, $prefix . 'operator_' . $layerset[0]['attributes']['name'][$i]), 'text');
						if (is_array($value)) {
							# multible-Auswahlfelder
							if (count($value) > 1){
								$value = implode('|', $value);
								if ($operator == '=') {
									$operator = 'IN';
								}
								else {
									$operator = 'NOT IN';
								}
							}
							else {
								$value = $value[0];
							}
						}
						if ($value != '' OR $operator == 'IS NULL' OR $operator == 'IS NOT NULL') {
							if (substr($layerset[0]['attributes']['type'][$i], 0, 1) == '_' AND $operator != 'IS NULL') { # Array-Datentyp
								$sql_where.=' AND (SELECT DISTINCT true FROM (SELECT json_array_elements_text(to_json(query.'.$layerset[0]['attributes']['name'][$i].')) a) foo where true ';
								$attr = 'a';
							}
							else {
								$attr = 'query.'.pg_quote($layerset[0]['attributes']['name'][$i]);		# normaler Datentyp
							}
							if ($value != '') {
								# Entferne Leerzeichen, wenn der Wert danach noch Zeichen enthalten würde
								if (strlen(trim($value)) > 0) {
									$value = trim($value);
								}
								switch ($operator) {
									case 'LIKE' : case 'NOT LIKE' : {
										################  Autovervollständigungsfeld ########################################
										if($layerset[0]['attributes']['form_element_type'][$i] == 'Autovervollständigungsfeld' AND $layerset[0]['attributes']['options'][$i] != ''){
											$optionen = explode(';', $layerset[0]['attributes']['options'][$i]);  # SQL; weitere Optionen
											if(strpos($value, '%') === false)$value2 = '%'.$value.'%';else $value2 = $value;
											$sql = 'SELECT * FROM ('.$optionen[0].') as foo WHERE LOWER(CAST(output AS TEXT)) '.$operator.' LOWER(\''.$value2.'\')';
											$ret=$layerdb->execSQL($sql,4,0);
											if ($ret[0]) { echo err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql); return 0; }
											while($rs = pg_fetch_assoc($ret[1])){
												$keys[] = $rs['value'];
											}
											$value_like = $value;					# Value sichern
											$operator_like = $operator;			# Operator sichern
											if($keys == NULL)$keys[0] = '####';		# Dummy-Wert, damit in der IN-Suche nichts gefunden wird
											$this->formvars[$prefix.'value_'.$layerset[0]['attributes']['name'][$i]] = implode('|', $keys);
											$this->formvars[$prefix.'operator_'.$layerset[0]['attributes']['name'][$i]] = 'IN';
											$i--;
											continue 2;		# dieses Attribut nochmal behandeln aber diesmal mit dem Operator IN und den gefundenen Schlüsseln der LIKE-Suche
										}
										#####################################################################################
										if (strpos($value, '%') === false) {
											$value = '%' . $value . '%';
										}
										$sql_where .= ' AND LOWER(CAST('.$attr.' AS TEXT)) '.$operator.' ';
										$sql_where.='LOWER(\''.$value.'\')';
									} break;

									case 'IN' : case 'NOT IN' : {
										$parts = explode('|', $value);
										for($j = 0; $j < count($parts); $j++){
											if(substr($parts[$j], 0, 1) != '\''){$parts[$j] = '\''.$parts[$j];}
											if(substr($parts[$j], -1) != '\''){$parts[$j] = $parts[$j].'\'';}
										}
										$instring = implode(',', $parts);
										if($layerset[0]['attributes']['type'][$i] != 'bool')$attr = 'LOWER(CAST('.$attr.' AS TEXT))';
										$sql_where .= ' AND '.$attr.' '.$operator.' ';
										$sql_where .= '('.mb_strtolower($instring).')';
										if($value_like != ''){			# Parameter wieder auf die der LIKE-Suche setzen
											$this->formvars[$prefix.'operator_'.$layerset[0]['attributes']['name'][$i]] = $operator_like;
											$this->formvars[$prefix.'value_'.$layerset[0]['attributes']['name'][$i]] = $value_like;
											$value_like = '';
											$operator_like = '';
										}
									} break;

									default : {
										if ($operator != 'IS NULL' AND $operator != 'IS NOT NULL') {
											$sql_where .= ' AND (' . $attr . ' ' . $operator . ' \'' . $value . '\'';
											if ($this->formvars[$prefix . 'value2_' . $layerset[0]['attributes']['name'][$i]] != '') {
												$sql_where.= ' AND \'' . $this->formvars[$prefix . 'value2_' . $layerset[0]['attributes']['name'][$i]] . '\'';
											}
											if ($operator == '!=') {
												$sql_where .= ' OR ' . $attr . ' IS NULL';
											}
											$sql_where .= ')';
										}
									}
								}
							}
							if ($operator == 'IS NULL' OR $operator == 'IS NOT NULL'){
								if (in_array($layerset[0]['attributes']['type'][$i], array('bpchar', 'varchar', 'text'))) {
									if ($operator == 'IS NULL') {
										$sql_where .= ' AND (' . $attr . ' ' . $operator . ' OR ' . $attr . ' = \'\') ';
									}
									else {
										$sql_where .= ' AND ' . $attr . ' ' . $operator . ' AND ' . $attr . ' != \'\' ';
									}
								}
								else {
									$sql_where .= ' AND ' . $attr . ' ' . $operator . ' ';
								}
							}
							if (substr($layerset[0]['attributes']['type'][$i], 0, 1) == '_' AND $operator != 'IS NULL') { # Array-Datentyp
								$sql_where .= ')';
							}
						}
						# räumliche Einschränkung
						if($m == 0 AND $layerset[0]['attributes']['name'][$i] == $layerset[0]['attributes']['the_geom']){		// nur einmal machen, also nur bei $m == 0
							if(value_of($this->formvars, 'newpathwkt') != ''){
								# Suche im Suchpolygon
								if($this->formvars['within'] == 1){
									$spatial_sql_where =' AND st_within('.$layerset[0]['attributes']['the_geom'].', st_buffer(st_transform(st_geomfromtext(\''.$this->formvars['newpathwkt'].'\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].'), 0.0001))';
								}
								else {
									$spatial_sql_where =' AND st_intersects('.$layerset[0]['attributes']['the_geom'].', (st_transform(st_geomfromtext(\''.$this->formvars['newpathwkt'].'\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].')))';
								}
							}
							# Suche nur im Stellen-Extent
							$spatial_sql_where.=' AND ('.$layerset[0]['attributes']['the_geom'].' && st_transform(st_geomfromtext(\'POLYGON(('.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].') OR '.$layerset[0]['attributes']['the_geom'].' IS NULL)';
						}
					}
					$sql_where .= ')';		// Klammer von der boolschen Verkettung wieder zu
				}

				$sql_where .= ')'.$spatial_sql_where;		// äußere Klammer wieder zu und räumliche Einschränkung anhängen

				if ($layerset[0]['maintable'] != '' AND $layerset[0]['oid'] != '') {
					if (value_of($this->formvars, 'operator_' . $layerset[0]['maintable'] . '_oid') == '') {
						$this->formvars['operator_'.$layerset[0]['maintable'].'_oid'] = '=';
					}
					if (value_of($this->formvars, 'value_'.$layerset[0]['maintable'] . '_oid') != '') {
						$sql_where .= ' AND '.pg_quote($layerset[0]['maintable'].'_oid') . ' ' . $this->formvars['operator_' . $layerset[0]['maintable'] . '_oid'] . ' ';
						if ($this->formvars['operator_'.$layerset[0]['maintable'].'_oid'] != 'IN') {
							$sql_where .= quote($this->formvars['value_' . $layerset[0]['maintable'] . '_oid'], $layerset[0]['attributes']['type'][$layerset[0]['attributes']['indizes'][$layerset[0]['oid']]]);
						}
						else {
							$sql_where .= $this->formvars['value_'.$layerset[0]['maintable'] . '_oid'];
						}
					}
				}
				# 2008-10-22 sr Filter zur Where-Klausel hinzugefügt
				# 2024-07-28 pk Replace all params from filter
				if ($layerset[0]['Filter'] != '') {
					$layerset[0]['Filter'] = replace_params_rolle($layerset[0]['Filter']);
					$sql_where .= " AND " . $layerset[0]['Filter'];
				}
				// echo '<br>sql where: ' . $sql_where;
				$sql = "
					SELECT " . $query_parts['select'] . " FROM (
						SELECT " . $pfad . "
					) as query
				WHERE
					1 = 1
					" . $sql_where . "
				";

				# order by
				$sql_order = '';
				if (value_of($this->formvars, 'orderby' . $layerset[0]['Layer_ID']) != '') {
					# Fall 1: im GLE soll nach einem Attribut sortiert werden
					$sql_order = ' ORDER BY '. replace_semicolon($this->formvars['orderby'.$layerset[0]['Layer_ID']]);
				}
				elseif ($query_parts['orderby'] != '') {
					# Fall 2: der Layer hat im Query ein ORDER BY
        	$sql_order = $query_parts['orderby'];
        }
				# standardmäßig wird nach der oid sortiert
				$j = 0;
				foreach ($layerset[0]['attributes']['all_table_names'] as $tablename){
					if ($tablename == $layerset[0]['maintable'] AND $layerset[0]['oid'] != ''){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
						if ($sql_order == '')$sql_order = ' ORDER BY ' . pg_quote(replace_semicolon($layerset[0]['maintable']) . '_oid') . ' ';
						else $sql_order .= ', '.pg_quote($layerset[0]['maintable'].'_oid').' ';
					}
					$j++;
				}

				if ($this->last_query != '') {
					$sql = $this->last_query[$layerset[0]['Layer_ID']]['sql'];
					if (value_of($this->formvars, 'orderby' . $layerset[0]['Layer_ID']) == '') {
						$sql_order = $this->last_query[$layerset[0]['Layer_ID']]['orderby'];
					}
					if (value_of($this->formvars, 'anzahl') == '') {
						$this->formvars['anzahl'] = $this->last_query[$layerset[0]['Layer_ID']]['limit'];
					}
					if (value_of($this->formvars, 'offset_' . $layerset[0]['Layer_ID']) == '') {
						$this->formvars['offset_' . $layerset[0]['Layer_ID']] = $this->last_query[$layerset[0]['Layer_ID']]['offset'];
					}
				}
				$sql_limit = '';
				if (value_of($this->formvars, 'embedded_subformPK') == '' AND value_of($this->formvars, 'embedded') == '' AND value_of($this->formvars, 'no_output') == '') {
					if ($this->formvars['anzahl'] == '') {
						$this->formvars['anzahl'] = $layerset[0]['max_query_rows'] ?: MAXQUERYROWS;
					}
					$sql_limit .= ' LIMIT ' . intval($this->formvars['anzahl']);
					if (value_of($this->formvars, 'offset_' . $layerset[0]['Layer_ID']) != '') {
						$sql_limit .= ' OFFSET ' . intval($this->formvars['offset_' . $layerset[0]['Layer_ID']]);
					}
				}

				$layerset[0]['sql'] = $sql;
				// if ($this->user->id == 1) {
				// 	echo "<p>Abfragestatement: " . $sql . $sql_order . $sql_limit;
				// }
				$this->debug->write("<p>Suchanfrage ausführen: ", 4);
				$ret = $layerdb->execSQL($sql . $sql_order . $sql_limit, 4, 0, true);
				if ($ret['success']) {
					$layerset[0]['shape'] = array();
					while ($rs = pg_fetch_assoc($ret[1])) {
						$layerset[0]['shape'][] = $rs;
						if ($rs[$layerset[0]['attributes']['the_geom']] != '') {
							$geometries_found = true;
						}
					}
					$num_rows = pg_num_rows($ret[1]);
					if (value_of($this->formvars, 'offset_'.$layerset[0]['Layer_ID']) == '' AND $num_rows < $this->formvars['anzahl']) {
						$layerset[0]['count'] = $num_rows;
					}
					else {
						# Anzahl der Datensätze abfragen
						$sql_count = "
							SELECT
								count(*)
							FROM
								(" . $sql . ") as foo
						";
						$ret = $layerdb->execSQL($sql_count,4, 0);
						if (!$ret[0]) {
							$rs = pg_fetch_array($ret[1]);
							$layerset[0]['count'] = $rs[0];
						}
					}
				}
				else {
					$ret['msg'] = sql_err_msg(
						'Fehler bei der Datenbankabfrage von Sachdaten in der Funktion GenerischeSuche_Suchen Zeile ' . __LINE__, $sql. $sql_order . $sql_limit,
						$ret['msg'],
						'error_div_' . rand(1, 99999)
					);
					$this->add_message('error', $ret['msg']);
					#err_msg('Datei: kvwmap.php<br>Funktion: GenerischeSuche_Suchen<br>', __LINE__, $ret['msg']);
				}
				# Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
				# Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
				$layerset[0]['attributes'] = $mapDB->add_attribute_values($layerset[0]['attributes'], $layerdb, $layerset[0]['shape'], true, $this->Stelle->id);
				# Datendrucklayouts abfragen
				include_(CLASSPATH.'datendrucklayout.php');
				$ddl = new ddl($this->database);
				$layerset[0]['layouts'] = $ddl->load_layouts($this->Stelle->id, NULL, $layerset[0]['Layer_ID'], array(0,1));

				# last_search speichern
				if ($this->formvars['no_last_search'] == '' AND $this->last_query == '' AND value_of($this->formvars, 'embedded_subformPK') == '' AND value_of($this->formvars, 'embedded') == '' AND value_of($this->formvars, 'subform_link') == '') {
					$this->formvars['search_name'] = '<last_search>';
					$this->user->rolle->delete_search($this->formvars['search_name']);		# das muss hier stehen bleiben, denn in save_search wird mit der Layer-ID gelöscht
					$this->user->rolle->save_search($layerset[0]['attributes'], $this->formvars);
				}

				if (
					value_of($layerset[0], 'count') != 0 AND
					value_of($this->formvars, 'embedded_subformPK') == '' AND
					value_of($this->formvars, 'embedded') == '' AND
					value_of($this->formvars, 'no_output') == ''
				) {
					if ($this->formvars['no_last_query'] == '') {
						# last_query speichern
						$this->user->rolle->delete_last_query();
						$this->user->rolle->save_last_query('Layer-Suche_Suchen', $this->formvars['selected_layer_id'], $sql, $sql_order, $this->formvars['anzahl'], value_of($this->formvars, 'offset_' . $layerset[0]['Layer_ID']));
					}

					# Querymaps erzeugen
					if (
						value_of($this->formvars, 'format') != 'json' AND
						$layerset[0]['querymap'] == 1 AND
						$layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['the_geom']] >= '0' AND
						(
							$layerset[0]['Datentyp'] == 1 OR
							$layerset[0]['Datentyp'] == 2
						)
					) {
						for ($k = 0; $k < count($layerset[0]['shape']); $k++) {
							$layerset[0]['querymaps'][$k] = $this->createQueryMap($layerset[0], $k);
						}
					}
					# wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
					if(is_array(value_of($this->formvars, 'attributenames'))){
						$attributenames = array_values($this->formvars['attributenames']);
						$values = array_values($this->formvars['values']);
						for($i = 0; $i < count($attributenames); $i++){
							$this->layerset[0]['shape'][0][$attributenames[$i]] = $values[$i];
						}
					}
				}
			} break;

			case MS_WFS : {
				include_(CLASSPATH.'wfs.php');
				$url = $layerset[0]['connection'];
				$version = $layerset[0]['wms_server_version'];
				$epsg = $layerset[0]['epsg_code'];
				$typename = $layerset[0]['wms_name'];
				$namespace = substr($typename, 0, strpos($typename, ':'));
				$username = $layerset[0]['wms_auth_username'];
				$password = $layerset[0]['wms_auth_password'];
				$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
				$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
				$layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], NULL, $privileges['attributenames'], false, true);

				# Filterstring erstellen
				for($i = 0; $i < count($layerset[0]['attributes']['name']); $i++){
				if($this->formvars['value_'.$layerset[0]['attributes']['name'][$i]] != '' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NULL' OR $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]] == 'IS NOT NULL'){
					$attributenames[] = $layerset[0]['attributes']['name'][$i];
					$operators[] = $this->formvars['operator_'.$layerset[0]['attributes']['name'][$i]];
					$values[] = $this->formvars['value_'.$layerset[0]['attributes']['name'][$i]];
				}
				}
				$filter = $wfs->create_filter($attributenames, $operators, $values);
				# Abfrage mit Filter absetzen
				if($this->formvars['anzahl'] == ''){
				$this->formvars['anzahl'] = $layerset[$i]['max_query_rows'] ?: MAXQUERYROWS;
				}
				if ($this->last_query != ''){
					$request = $this->last_query[$layerset[0]['Layer_ID']]['sql'];
					if($this->formvars['anzahl'] == '')$this->formvars['anzahl'] = $this->last_query[$layerset[0]['Layer_ID']]['limit'];
				}
				$wfs->get_feature_request($request, NULL, $filter, $this->formvars['anzahl']);
				$features = $wfs->extract_features();
				$layerset[0]['count'] = count($features);
				for ($j = 0; $j < $layerset[0]['count']; $j++) {
					for ($k = 0; $k < count($layerset[0]['attributes']['name']); $k++) {
						$layerset[0]['shape'][$j][$layerset[0]['attributes']['name'][$k]] = $features[$j]['value'][$layerset[0]['attributes']['name'][$k]];
						$layerset[0]['attributes']['privileg'][$k] = 0;
						$layerset[0]['attributes']['visible'][$k] = true;
					}
					$layerset[0]['shape'][$j]['wfs_geom'] = $features[$j]['geom'];
					$layerset[0]['shape'][$j]['wfs_bbox'] = $features[$j]['bbox'];
					if ($features[$j]['geom'] != '') {
						$geometries_found = true;
					}
				}
				# last_search speichern
				if($this->last_query == ''){
					$this->formvars['search_name'] = '<last_search>';
					$this->user->rolle->delete_search($this->formvars['search_name']);		# das muss hier stehen bleiben, denn in save_search wird mit der Layer-ID gelöscht
					$this->user->rolle->save_search($layerset[0]['attributes'], $this->formvars);
				}
				if(!empty($features)){
					# last_query speichern
					$this->user->rolle->delete_last_query();
					$this->user->rolle->save_last_query('Layer-Suche_Suchen', $this->formvars['selected_layer_id'], $request, NULL, $this->formvars['anzahl'], NULL);
				}
				$layerset[0]['attributes'] = $mapDB->add_attribute_values($layerset[0]['attributes'], $this->pgdatabase, $layerset[0]['shape'], true, $this->Stelle->id);
      		} break;
		}   # Ende switch connectiontype

		$this->qlayerset[0] = $layerset[0];
		$i = 0;
		$this->search = true;

		if (value_of($this->formvars, 'no_output')) {
			# nichts weiter machen
		}
		elseif (value_of($this->formvars, 'embedded_subformPK') != '') {
			header('Content-type: text/html; charset=UTF-8');
			include(LAYOUTPATH . 'snippets/embedded_subformPK.php'); # listenförmige Ausgabe mit Links untereinander
			if (!$ret['success']) {
				// Diese Variante funktioniert nicht wenn in $ret['msg'] Zeilenumbrüche drin sind, wie das hier z.B.
				// 				WHERE
				//   (
				//     $stelle_id = k.stelle_id OR
				//     $stelle_id = 1
				//  ). Das str_replace scheint nicht zu wirken.
				// 				echo "<script>message([{ \"type\" : 'error', \"msg\" : '" . addslashes(str_replace('
				// ', '', $ret['msg'])) . "'}]);</script>";
				echo "<script>message([{ \"type\" : 'error', \"msg\" : " . json_encode($ret['msg']) . "}]);</script>";
			}
		}
		elseif (value_of($this->formvars, 'embedded_subformPK_liste') != '') {
			header('Content-type: text/html; charset=UTF-8');
			include(LAYOUTPATH . 'snippets/embedded_subformPK_list.php'); # listenförmige Ausgabe zum schnellen editieren
		}
		elseif(value_of($this->formvars, 'embedded') != '') {
			ob_end_clean();
			header('Content-type: text/html; charset=UTF-8');
			include(LAYOUTPATH.'snippets/sachdatenanzeige_embedded.php'); # ein aufgeklappter Link
			if (!$ret['success']) {
				ob_end_clean();
				echo $ret['msg'];
			}
		}
		else {
			$this->main = 'sachdatenanzeige.php';
			if (value_of($this->formvars, 'printversion') != ''){
				$this->mime_type = 'printversion';
			}
			if (value_of($this->formvars, 'printversion') == '' AND ($this->user->rolle->querymode == 1 OR $this->formvars['go_next'] != '')) {
				# bei aktivierter Datenabfrage in extra Fenster --> Laden der Karte und zoom auf Treffer (das Zeichnen der Karte passiert in einem separaten Ajax-Request aus dem Overlay heraus)
				$this->loadMap('DataBase');
				if ($geometries_found) {
					# wenn was mit Geometrie gefunden wurde, auf Datensätze zoomen
					$this->zoomed = true;
					switch ($layerset[0]['connectiontype']) {
						case MS_POSTGIS : {
							for ($k = 0; $k < count($this->qlayerset[$i]['shape']); $k++){
								$oids[] = $this->qlayerset[$i]['shape'][$k][$layerset[0]['maintable'].'_oid'];
							}
							$rect = $mapDB->zoomToDatasets($oids, $layerset[0], $layerset[0]['attributes']['the_geom'], 10, $layerdb, $this->user->rolle->epsg_code, $this->Stelle);
							$this->map->setextent($rect->minx, $rect->miny, $rect->maxx, $rect->maxy);
							if (MAPSERVERVERSION > 600) {
								$this->map_scaledenom = $this->map->scaledenom;
							}
							else {
								$this->map_scaledenom = $this->map->scale;
							}
						} break;
						case MS_WFS : {
							$this->formvars['wkt'] = $layerset[0]['shape'][0]['wfs_geom'];
							$this->formvars['epsg'] = $layerset[0]['epsg_code'];
							$this->zoom2wkt();
						} break;
					}
				}
				$this->user->rolle->newtime = $this->user->rolle->last_time_id;
				$this->saveMap('');
			}
			if ($this->formvars['go_next'] != '') {
				if ($this->formvars['go_next'] == 'default') {
					$this->main = 'map.php';
				}
				go_switch($this->formvars['go_next']);
				exit();
			}
			$this->output();
		}
	}

	function get_quicksearch_attributes(){
		if ($this->formvars['layer_id']) {
			$this->layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
			$this->formvars['anzahl'] = $this->layerset[0]['max_query_rows'] ?: MAXQUERYROWS;
			$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
			switch ($this->layerset[0]['connectiontype']){
        case MS_POSTGIS : {
					$layerdb = $mapdb->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
					$path = $mapdb->getPath($this->formvars['layer_id']);
					$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
					$this->attributes = $mapdb->read_layer_attributes($this->formvars['layer_id'], $layerdb, $privileges['attributenames']);
					# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true, $this->Stelle->id);
        }break;

        case MS_WFS : {
					$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
					$this->attributes = $mapdb->read_layer_attributes($this->formvars['layer_id'], NULL, $privileges['attributenames']);
        }break;
      }
			?><table><tr><?
			for ($i = 0; $i < count($this->attributes['name']); $i++){
				if ($this->layerset[0]['connectiontype'] == MS_WFS OR $this->attributes['quicksearch'][$i] == 1){
					?>
						<td width="40%">&nbsp;&nbsp;<?
							if($this->attributes['alias'][$i] != ''){
								echo $this->attributes['alias'][$i];
							}
							else{
								echo $this->attributes['name'][$i];
							}
					?>:</td>
						<td align="left" width="40%"><?
							switch ($this->attributes['form_element_type'][$i]) {
								case 'Auswahlfeld' : {	?>
									<select class="select quicksearch_field"
									<?
										if($this->attributes['req_by'][$i] != ''){
											echo 'onchange="update_require_attribute_(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['layer_id'].', new Array(\''.implode("','", $this->attributes['name']).'\'));" ';
										}
										else echo 'onchange="schnellsuche();" ';
									?>
										id="value_<? echo $this->attributes['name'][$i]; ?>" name="value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
											<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n";
											if(is_array($this->attributes['enum'][$i][0])){
												$this->attributes['enum'][$i] = $this->attributes['enum'][$i][0];
											}
										foreach ($this->attributes['enum'][$i] as $enum_key => $enum) {
											?>
											<option <?
											if ($this->formvars['value_' . $this->attributes['name'][$i]] == $enum_key) {
												echo 'selected';
											} ?> value="<? echo $enum_key; ?>"><? echo $enum['output']; ?></option><? echo "\n";
										} ?>
										</select>
										<input type="hidden" class="quicksearch_field" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="=">
										<?
								} break;

								default : { ?>
                  <input size="24" class="quicksearch_field" onkeydown="keydown(event)" id="attribute_<? echo $i; ?>" name="value_<? echo $this->attributes['name'][$i]; ?>" type="text" value=""><?
									if (
										$this->layerset[0]['connectiontype'] == MS_WFS OR
										!in_array($this->attributes['type'][$i],	array('varchar', 'text'))
									) { ?>
										<input type="hidden" class="quicksearch_field" id="operator_attribute_<? echo $i; ?>" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="="><?
									} else { ?>
										<input type="hidden" class="quicksearch_field" id="operator_attribute_<? echo $i; ?>" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="LIKE"><?
									}
								}
							}
					 ?></td><?
				}
			}
			?></tr></table><?
		}
	}

	function GenerischeSuche_Suchmaske(){
		if($this->formvars['selected_layer_id']){
			if($this->formvars['selected_layer_id'] > 0)$layerset=$this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			else $layerset = $this->user->rolle->getRollenlayer(-$this->formvars['selected_layer_id']);
      switch ($layerset[0]['connectiontype']) {
        case MS_POSTGIS : {
          $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
          $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
          $path = $mapdb->getPath($this->formvars['selected_layer_id']);
          $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
          $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
          # wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
	        for($i = 0; $i < count($this->attributes['name']); $i++){
	          $this->qlayerset['shape'][0][$this->attributes['name'][$i]] = $this->formvars['value_'.$this->attributes['name'][$i]];
	        }
          # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true, $this->Stelle->id);
        }break;
      }
    }
		$searchmask_number = $this->formvars['searchmask_number'];
		include(SNIPPETS.'generic_search_mask.php');
	}

	function GenerischeSuche() {
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->titel = value_of($this->formvars, 'titel');
		$this->main = 'generic_search.php';
		$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, 'import');
		if ($this->layerdaten['Gruppe']) {
			$this->layergruppen['ID'] = array_values(array_unique($this->layerdaten['Gruppe']));
		}
		$this->layergruppen = $mapdb->get_Groups($this->layergruppen); # Gruppen mit Pfaden versehen
		# wenn Gruppe ausgewählt, Einschränkung auf Layer dieser Gruppe
		if (value_of($this->formvars, 'selected_group_id') AND $this->formvars['selected_layer_id'] == '') {
			$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, $this->formvars['selected_group_id']);
		}
		if (value_of($this->formvars, 'selected_layer_id')) {
			$this->formvars['selected_layer_id'] = intval($this->formvars['selected_layer_id']);
			$data = $mapdb->getData($this->formvars['selected_layer_id']);
			$data_explosion = explode(' ', $data);
			$this->formvars['columnname'] = $data_explosion[0];
			if (value_of($this->formvars, 'map_flag') != '') {
				################# Map ###############################################
				if (value_of($this->formvars, 'geom_from_layer') == '') {
					$this->formvars['geom_from_layer'] = $this->formvars['selected_layer_id'];
				}
				$saved_scale = $this->reduce_mapwidth(10);
				$this->loadMap('DataBase');
				if (value_of($this->formvars, 'CMD') == '' AND $saved_scale != NULL) {
					$this->scaleMap($saved_scale);		# nur, wenn nicht navigiert wurde
				}
				$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true, true);
				if (in_array(value_of($this->formvars, 'CMD'), ['Full_Extent', 'recentre', 'zoomin', 'zoomout', 'previous', 'next'])) {
					$this->navMap($this->formvars['CMD']);
				}
				$this->drawMap();
				$this->saveMap('');
				$currenttime=date('Y-m-d H:i:s',time());
				$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
				########################################################################
			}

			if ($this->formvars['selected_layer_id'] > 0) {
				$this->layerset=$this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			}
			else {
				$this->layerset=$this->user->rolle->getRollenlayer(-$this->formvars['selected_layer_id']);
			}

			if (empty($this->formvars['anzahl'])) {
				$this->formvars['anzahl'] = $this->layerset[0]['max_query_rows'] ?: MAXQUERYROWS;
			}

			$this->formvars['selected_group_id'] = $this->layerset[0]['Gruppe'];

			$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, $this->formvars['selected_group_id']);

			switch ($this->layerset[0]['connectiontype']) {
				case MS_POSTGIS : {
					$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
					$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
					$path = $mapdb->getPath($this->formvars['selected_layer_id']);
					$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);

					$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
					# Speichern einer neuen Suchabfrage
					if (value_of($this->formvars, 'go_plus') == 'Suchabfrage_speichern') {
						$this->user->rolle->save_search($this->attributes, $this->formvars);
						$this->formvars['searches'] = $this->formvars['search_name'];
					}
					if (value_of($this->formvars, 'searchmask_count') == '') {
						$this->formvars['searchmask_count'] = 0;		// darf erst nach dem speichern passieren
					}
					# Löschen einer Suchabfrage
					if (value_of($this->formvars, 'go_plus') == 'Suchabfrage_löschen') {
						$this->user->rolle->delete_search($this->formvars['searches'], $this->formvars['selected_layer_id']);
						$this->formvars['searches'] = '';
					}
					# die Namen aller gespeicherten Suchabfragen dieser Rolle zu diesem Layer laden
					$this->searchset=$this->user->rolle->getsearches($this->formvars['selected_layer_id']);
					# die ausgewählte Suchabfrage laden

					for ($m = 0; $m <= $this->formvars['searchmask_count']; $m++) {
						if ($m > 0) {
							# es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
							$prefix = $m . '_';
						}
						else {
							$prefix = '';
						}
						if (value_of($this->formvars, 'searches') != '') {
							# die Suchparameter einer gespeicherten Suchabfrage laden
							$this->selected_search = $this->user->rolle->getsearch($this->formvars['selected_layer_id'], $this->formvars['searches']);
							$this->formvars['searchmask_count'] = $this->selected_search[0]['searchmask_number'];
							# alle Suchparameter leeren
							for ($i = 0; $i < count($this->attributes['name']); $i++) {
								$this->formvars[$prefix . 'operator_' . $this->attributes['name'][$i]] = '';
								$this->formvars[$prefix . 'value_' .    $this->attributes['name'][$i]] = '';
								$this->formvars[$prefix . 'value2_' .   $this->attributes['name'][$i]] = '';
							}
							# die gespeicherten Suchparameter setzen
							for ($i = 0; $i < count($this->selected_search); $i++) {
								if ($this->selected_search[$i]['searchmask_number'] == $m) {
									$this->formvars['searchmask_operator'][$m] = $this->selected_search[$i]['searchmask_operator'];
									$this->formvars[$prefix.'operator_' . $this->selected_search[$i]['attribute']] = $this->selected_search[$i]['operator'];
									$this->formvars[$prefix.'value_' .    $this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value1'];
									$this->formvars[$prefix.'value2_' .   $this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value2'];
									$this->qlayerset['shape'][0][$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value1'];
								}
							}
						}
						else {
							for ($i = 0; $i < count($this->attributes['name']); $i++) {
								$this->qlayerset['shape'][0][$this->attributes['name'][$i]] = value_of($this->formvars, $prefix.'value_'.$this->attributes['name'][$i]);
								$this->attributes['operator'][$i] = value_of($this->formvars, $prefix.'operator_'.$this->attributes['name'][$i]);
							}
						}
						# für jede Suchmaske ein eigenes attributes-Array erzeugen, da z.B. die Auswahllisten ja anders sein können
						$this->{'attributes' . $m} = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true, $this->Stelle->id);
					}
				} break;

				case MS_WFS : {
					$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
					$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], NULL, $privileges['attributenames']);
					for ($i = 0; $i < count($this->attributes['name']); $i++) {
						$this->qlayerset['shape'][0][$this->attributes['name'][$i]] = $this->formvars['value_' . $this->attributes['name'][$i]];
					}
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $this->pgdatabase, $this->qlayerset['shape'], true, $this->Stelle->id);
				} break;
			}
		}
		$this->output();
	}

	function Suchabfragen_auflisten(){
		$this->main='list_searches.php';
		$this->titel='Gespeicherte Suchabfragen';
		$this->searches = $this->user->rolle->getsearches(NULL);
		$this->output();
	}

	function Datensaetze_Merken(){
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		for ($i = 0; $i < count($checkbox_names); $i++) {
			if ($this->formvars[$checkbox_names[$i]] == 'on') {
				$element = explode(';', $checkbox_names[$i]); # check;table_alias;table;oid
				$oid = $element[3];
				if ($oid != '') {
					$sql = "
						INSERT INTO zwischenablage
						VALUES (
							" . $this->user->id . ",
							" . $this->Stelle->id . ",
							" . $this->formvars['chosen_layer_id'] . ",
							'" . $oid . "'
						) ON DUPLICATE KEY
						UPDATE layer_id=layer_id
					";
					$ret = $this->database->execSQL($sql,4, 1);
				}
			}
		}
	}

	function Datensaetze_nicht_mehr_merken(){
    $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $oids[] = $element[3];
			}
		}
		$sql = 'DELETE FROM zwischenablage WHERE user_id = '.$this->user->id.' AND stelle_id = '.$this->Stelle->id;
		if($this->formvars['chosen_layer_id'] != '')$sql.= ' AND layer_id = '.$this->formvars['chosen_layer_id'];
		if(count_or_0($oids) > 0)$sql.= ' AND oid IN ('.implode(', ', $oids).')';
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql,4, 1);
		if(count_or_0($oids) == 0){
			$this->Zwischenablage();
		}
	}

	function Zwischenablage(){
		global $language;
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl = new ddl($this->database);
		if($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
		}
		else{
			$name_column = "l.Name";
		}
		$sql = "SELECT count(z.layer_id) as count, z.layer_id, " . $name_column.", l.alias FROM zwischenablage as z, layer as l WHERE z.layer_id = l.Layer_ID AND user_id = " . $this->user->id." AND stelle_id = " . $this->Stelle->id." GROUP BY z.layer_id, l.Name";
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql,4, 1);
		$result = $this->database->result;
    $this->num_rows = $this->database->result->num_rows;
		while($rs = $result->fetch_assoc()){
			$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$this->Stelle->useLayerAliases) ? 'Name' : 'alias'];
			$rs['layouts'] = $ddl->load_layouts($this->Stelle->id, NULL, $rs['layer_id'], array(0,1), 'only_ids');
			$this->layer[] = $rs;
		}
		// if($this->num_rows == 1){
			// $this->gemerkte_Datensaetze_anzeigen($this->layer[0]['layer_id']);
		// }
		// else{
			$this->titel = 'Zwischenablage';
			$this->main = 'zwischenablage.php';
			$this->output();
		//}
	}

	/**
	 * function requests oid's of features in zwischenablage from Layer with $layer_id
	 * for current user with user_id in stelle stelle_id.
	 * and returns it in an array. If layer_id is empty it returns oids from all features.
	 * @param int layer_id
	 * @return Array
	 */
	function get_gemerkte_oids($layer_id = null) {
		$sql = "
			SELECT
				oid,
				layer_id
			FROM
				zwischenablage
			WHERE
				user_id = " . $this->user->id . " AND
				stelle_id = " . $this->Stelle->id . "
				" . ($layer_id ? 'AND layer_id = ' . $layer_id : '') . "
		";
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql, 4, 1);
		$oids = array();
		while ($rs = $this->database->result->fetch_assoc()){
			$oids[$rs['layer_id']][] = $rs['oid'];
		}
		return $oids;
	}

	function gemerkte_Datensaetze_anzeigen($layer_id){
		$oids = $this->get_gemerkte_oids($layer_id);
		$layerset = $this->user->rolle->getLayer($layer_id);
		$this->formvars['selected_layer_id'] = $layer_id;
		$this->formvars['value_'.$layerset[0]['maintable'] . '_oid'] = "('" . implode("', '", $oids[$layer_id]) . "')";
		$this->formvars['operator_'.$layerset[0]['maintable'] . '_oid'] = 'IN';
		$this->formvars['anzahl'] = 1000;
		$this->GenerischeSuche_Suchen();
	}

	function gemerkte_Datensaetze_drucken($layer_id){
		include_(CLASSPATH . 'datendrucklayout.php');
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$oids = $this->get_gemerkte_oids($layer_id);
		foreach ($oids as $layer_id => $layer_oids) {
			$layerset = $this->user->rolle->getLayer($layer_id);
			$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
			$layerset[0]['attributes'] = $mapdb->read_layer_attributes($layer_id, $layerdb, NULL, false, true);
			# Generiere formvars der checkboxen für die Objekte an Hand $oids
			# check;table_alias;table;oid
			$checkbox_names = array();
			foreach ($layer_oids AS $oid) {
				#check;rechnungen;rechnungen;222792641=on
				$checkbox_name = 'check;' . $layerset[0]['attributes']['table_alias_name'][$layerset[0]['maintable']] . ';' . $layerset[0]['maintable'] . ';' . $oid;
				$this->formvars[$checkbox_name] = 'on';
				$checkbox_names[] = $checkbox_name;
			}
			# checkbox_names_743=check;rechnungen;rechnungen;222792641 mehrere mit | getrennt
			$this->formvars['checkbox_names_' . $layer_id] = implode('|', $checkbox_names);
			$this->formvars['archivieren'] = null;
			$this->formvars['go'] = 'generischer_sachdaten_druck_Drucken';

			$ddl = new ddl($this->database, $this);
			$ddl->selectedlayout = $ddl->load_layouts($this->Stelle->id, NULL, $layer_id, [], 'only_ids');
			# folgendes wird nur ausgeführt, wenn der letzte Layer kein Drucklayout hatte und nicht gedruckt wurde
			if ($this->formvars['aktivesLayout'] = $ddl->selectedlayout[0]) {
				$this->formvars['chosen_layer_id'] = $layer_id;
				$this->qlayerset[0]['shape'] = null;
				$this->generischer_sachdaten_druck_createPDF($this->pdf, null, null, ($layer_id === array_key_last($oids)? true : false), ($this->pdf? true : false));
			}
			$this->outputfile = 'zwischenablage.pdf';
			$fp = fopen(IMAGEPATH . $this->outputfile, 'wb');
			fwrite($fp, $this->pdf->ezOutput());
			fclose($fp);
			$this->mime_type='pdf';
			$this->output();
		}
	}

	function layer_Datensatz_loeschen($layer_id, $oid, $reload_object) {
		$layers = $this->user->rolle->getLayer($layer_id);
		$layer = $layers[0];
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$attributes = $mapdb->read_layer_attributes($layer_id, $layerdb, NULL);
		$results = $this->Datensatz_Loeschen($layerdb, $layer, $attributes, $oid);
		echo '█';
		if($reload_object != '')echo 'reload_subform_list(\''.$reload_object.'\', 0);';
		if(!empty($results[0])){
			echo 'message('.json_encode($results).');';
		}
	}

	function layer_Datensaetze_loeschen($output = true) {
		$this->success = true;
		$layers = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		$layer = $layers[0];
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		$attributes = $mapdb->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, NULL);
		$results = array();
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		for ($i = 0; $i < count($checkbox_names); $i++) {
			if ($this->formvars[$checkbox_names[$i]] == 'on') {
				$element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
				$oids[] = $element[3];
				$results = $results + $this->Datensatz_Loeschen($layerdb, $layer, $attributes, $element[3]);
			}
		}
		if ($output) {
			if ($this->formvars['embedded'] == '') {
				foreach ($results AS $result) {
					$this->add_message($result['type'], $result['msg']);
				}
				$this->last_query = $this->user->rolle->get_last_query();
				if ($this->formvars['search']) {
					# man kam von der Suche -> nochmal suchen
					$this->GenerischeSuche_Suchen();
				}
				else {
					# man kam aus einer Sachdatenabfrage -> nochmal abfragen
					$this->last_query_requested = true;
					$this->queryMap();
				}
			}
			else {
				# wenn es ein Datensatz aus einem embedded-Formular ist,
				# muss das embedded-Formular entfernt werden und
				# das Listen-DIV neu geladen werden (getrennt durch █)
				echo '█reload_subform_list(\''.$this->formvars['targetobject'].'\', 0);';
			}
		}
		else {
			$this->data = array(
				'success' => $this->success,
				'msg' => 'Datensatz erfolgreich gelöscht'
			);

			if ($this->formvars['format'] == 'json_result') {
				header('Content-Type: application/json; charset=utf-8');
				echo utf8_decode(json_encode($this->data));
			}
		}

		return $this->success;
	}

	function Datensatz_Loeschen($layerdb, $layer, $attributes, $oid) {
		$results = array();
		for ($i = 0; $i < count($attributes['name']); $i++) {
			if ($attributes['options'][$i] == 'delete') { # statt zu Löschen soll hier nur ein Auto-Feld gesetzt werden
				$attribute = $attributes['real_name'][$attributes['name'][$i]];
				switch($attributes['form_element_type'][$i]) {
					case 'Time' : {
						$instead_updates[] = $attribute." = '".date('Y-m-d G:i:s')."'";
					} break;
					case 'User' : {
						$instead_updates[] = $attribute." = '".$this->user->Vorname." ".$this->user->Name."'";
					} break;
					case 'UserID' : {
						$instead_updates[] = $attribute.' = '.$this->user->id;
					} break;
					case 'Stelle' : {
						$instead_updates[] = $attribute." = '".$this->Stelle->Bezeichnung."'";
					} break;
					case 'StelleID' : {
						$instead_updates[] = $attribute.' = '.$this->Stelle->id;
					} break;
				}
			}
		}
		if (!empty($instead_updates)) {			# statt zu Löschen sollen hier nur die Auto-Felder gesetzt werden
			$sql = "
				UPDATE
					" . pg_quote($layer['maintable']) . "
				SET
					" . implode(', ', $instead_updates) . "
				WHERE
					" . $layer['oid'] . " = " . quote($oid);
			$ret = $layerdb->execSQL($sql, 4, 1, true);
			if ($ret['success']) {
				$results[] = array('type' => 'notice', 'msg' => 'Löschen erfolgreich<br>');
			}
		}
		else {															# richtig löschen
			if (!empty($layer['trigger_function'])) {
				if ($layer['oid'] == 'oid') {
					$oid_sql = 'oid,';
				}
				$sql_old = "
					SELECT ".
						$oid_sql . " *
					FROM
						" . $layer['schema'] . '.' . pg_quote($layer['maintable']) . "
					WHERE
						" . $layer['oid'] . " = '" . $oid . "'";
				#echo '<br>Sql before delete: ' . $sql_old; #pk
				$ret = $layerdb->execSQL($sql_old, 4, 1, true);
				if ($ret['success']) {
					$old_dataset = pg_fetch_assoc($ret['query']);

					# Rufe Before Delete trigger
					$this->exec_trigger_function('BEFORE', 'DELETE', $layer, $oid, $old_dataset);

					# Rufe Instead Delete trigger auf
					$trigger_result = $this->exec_trigger_function('INSTEAD', 'DELETE', $layer, $oid, $old_dataset);
				}
				else {
					$old_dataset = array();
					$results[] = array('type' => 'error', 'msg' => $ret['msg']);
					$this->success = false;
				}
			}

			if ($trigger_result['executed']) {
				#echo '<br>Delete Trigger Funktion wurde ausgeführt.';
				# Instead Triggerfunktion wurde ausgeführt, übergebe Erfolgsmeldung
				if ($trigger_result['success']) {
					$trigger_result_type = 'notice';
				}
				else {
					$this->success = false;
					$trigger_result_type = 'error';
				}
				if ($trigger_result['message'] != '') {
					$results[] = array('type' => $trigger_result_type, 'msg' => $trigger_result['message']);
				}
			}
			else {
				#echo '<br>Delete Trigger Funktion wurde nicht ausgeführt.';
				# Instead Triggerfuktion wurde nicht ausgeführt
				# Delete the object regularly in database

				# überprüfen ob Dokument-Attribute vorhanden sind, wenn ja deren Datei-Pfade ermitteln und nach erfolgreichem Löschen auch die Dokumente löschen
				$document_attributes = array();
				for ($i = 0; $i < count($attributes['name']); $i++) {
					if($attributes['form_element_type'][$i] == 'Dokument' AND $attributes['table_name'][$i] == $layer['maintable']){
						$document_attributes[] = $attributes['name'][$i];
					}
				}
				if(!empty($document_attributes)){
					$this->formvars['selected_layer_id'] = $layer['Layer_ID'];
					$this->formvars['value_'.$layer['maintable'].'_oid'] = $oid;
					$this->formvars['operator_'.$layer['maintable'].'_oid'] = '=';
					$this->formvars['no_output'] = true;
					$search_save = $this->formvars['search'];
					$this->GenerischeSuche_Suchen();
					$this->formvars['no_output'] = false;
					$this->formvars['search'] = $search_save;
					$this->search = $search_save;
				}

				$sql = "
					DELETE FROM
						" . pg_quote($layer['maintable']) . "
					WHERE
						" . pg_quote($layer['oid']) . " = '" . $oid . "'
				";
				$oids[] = $element[3];
				$ret = $layerdb->execSQL($sql, 4, 1, true);
				if ($ret['success']) {
					if ($ret['msg']) {	# Notice aus Trigger
						$results[] = array('type' => $ret['type'], 'msg' => $ret['msg']);
					}
					$sql_result = pg_fetch_row($ret['query']);	# Select aus Regel
					if ($sql_result[0] != '') {
						$results[] = array('type' => 'info', 'msg' => $sql_result[0]);
					}
					# Prüfe ob Löschung kein Datensatz betroffen hat
					if (pg_affected_rows($ret['query']) == 0) {
						$results[] = array('type' => 'error', 'msg' => '<br>Datensatz wurde nicht gelöscht.<br>');
						$this->success = false;
					}
					else {
						if ($this->user->rolle->upload_only_file_metadata == 1 AND is_array($this->qlayerset) AND is_array($this->qlayerset[0]) AND is_array($this->qlayerset[0]['shape'])) {
							include_once(CLASSPATH . 'BelatedFile.php');
							foreach ($this->qlayerset[0]['shape'] AS $dataset) {
								foreach ($document_attributes AS $document_attribute) {
									$where = "
										`user_id` = " . $this->user->id . " AND
										`layer_id` = " . $layer['Layer_ID'] . " AND
										`attribute_name` = '" . $document_attribute . "' AND
										`file` = '" . $dataset['datei'] . "'
									";
									foreach (BelatedFile::find($this, $where) AS $belated_file) {
										$belated_file->delete();
									}
								}
							}
						}
						$results[] = array('type' => 'notice', 'msg' => 'Löschen erfolgreich<br>');
					}
				}
				else {
					$results[] = array('type' => 'error', 'msg' => $ret['msg']);
					$this->success = false;
				}
			}

			if ($this->success) {
				# Dokumente löschen
				if ($document_attributes AND is_array($document_attributes)) {
					foreach($document_attributes as $document_attribute){
						$this->deleteDokument($this->qlayerset[0]['shape'][0][$document_attribute], $layer['document_path'], $layer['document_url']);
					}
				}
				$this->qlayerset[0]['shape'] = array();
				# After delete trigger
				if (!empty($layer['trigger_function'])) {
					$this->exec_trigger_function('AFTER', 'DELETE', $layer, '', $old_dataset);
				}
			}
		}
		return $results;
	}

	function neuer_Layer_Datensatz_speichern() {
		foreach ($this->formvars as $key => $value) {
			if (is_string($value)) {
				$this->formvars[$key] = replace_tags($value, 'script|embed');
			}
		}
		$_files = $_FILES;
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);		# Rechte
		$attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
		$doc_path = $layerset[0]['document_path'];
		$doc_url = $layerset[0]['document_url'];
		$layer_epsg = $layerset[0]['epsg_code'];
		$client_epsg = $this->user->rolle->epsg_code;
		$form_fields = explode('|', $this->formvars['form_field_names']);

		for ($i = 0; $i < count($form_fields); $i++) {
			if ($form_fields[$i] != '') {
				$element = explode(';', $form_fields[$i]);
				$layer_id = $element[0];
				$attributname = $element[1];
				$table_name = $element[2];
				$formtype = $element[4];
				$saveable = $element[7];
				$tablename[$table_name]['tablename'] = $table_name;
				$tablename[$table_name]['attributname'][] = $attributenames[] = $attributname;
				$tablename[$table_name]['saveable'][] = $saveable;
				$form_field_indizes[$attributname] = $i;
				$attributevalues[] = $this->formvars[$form_fields[$i]];
				if ($this->formvars['embedded'] != '') {
					$formfieldstring .= '&'.$form_fields[$i] . '=' . $this->formvars[$form_fields[$i]];
					$insert_data[0][$attributname] = $this->formvars[$form_fields[$i]];
				}
				$tablename[$table_name]['type'][] = $formtype;
				$tablename[$table_name]['datatype'][] = $element[6];
				$tablename[$table_name]['formfield'][] = $form_fields[$i];
				# Dokumente sammeln
				if ($formtype == 'Dokument') {
					if ($_files[$form_fields[$i]]['name'] OR $this->formvars[$form_fields[$i]]) {
						$document_attributes[$i]['layer_id'] = $layer_id;
						$document_attributes[$i]['attributename'] = $attributname;
						$document_attributes[$i]['datatype'] = $element[6];
					}
				}
			}
		}

		# Dokumente speichern
		if (count_or_0($document_attributes) > 0) {
			if ($this->user->rolle->upload_only_file_metadata == 1) {
				$belated_files = array();
			}
			foreach ($document_attributes as $i => $document_attribute) {
				$options = $attributes['options'][$document_attribute['attributename']];
				if (substr($document_attribute['datatype'], 0, 1) == '_') {
					// ein Array aus Dokumenten, hier enthält der JSON-String eine Mischung aus bereits vorhandenen,
					// nicht geänderten Datei-Pfaden und File-input-Feldnamen, die noch verarbeitet werden müssen
					$insert = $this->processJSON($this->formvars[$form_fields[$i]], $doc_path, $doc_url, $options, $attributenames, $attributevalues, $layerdb);
				}
				else {
					$insert = $this->save_uploaded_file($form_fields[$i], $doc_path, $doc_url, $options, $attributenames, $attributevalues, $layerdb, $document_attributes[$i]['datatype']);	// normales Dokument-Attribut
					if ($this->user->rolle->upload_only_file_metadata == 1) {
						$belated_files[$i] = $this->formvars[$form_fields[$i]];
					}
				}
				$this->formvars[$form_fields[$i]] = $document_attributes[$i]['insert'] = $insert;
			}
		}

		if ($this->formvars['geomtype'] == 'GEOMETRY') {
			$geomtypes = array('POINT', 'MULTILINESTRING', 'MULTIPOLYGON');
			$this->formvars['geomtype'] = $geomtypes[$layerset[0]['Datentyp']];
		}

		if ($this->formvars['newpathwkt'] == '' AND $this->formvars['newpath'] != ''){   # wenn keine WKT-Geoemtrie da ist, muss die WKT-Geometrie aus dem SVG erzeugt werden
			include_(CLASSPATH.'spatial_processor.php');
			$spatial_pro = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
			if ($this->formvars['geomtype'] == 'POLYGON' OR $this->formvars['geomtype'] == 'MULTIPOLYGON') {
				$this->formvars['newpathwkt'] = $spatial_pro->composePolygonWKTStringFromSVGPath($this->formvars['newpath']);
			}
			elseif ($this->formvars['geomtype'] == 'LINESTRING' OR $this->formvars['geomtype'] == 'MULTILINESTRING') {
				$this->formvars['newpathwkt'] = $spatial_pro->composeLineWKTStringFromSVGPath($this->formvars['newpath']);
			}
			elseif ($this->formvars['geomtype'] == 'MULTIPOINT') {
				$this->formvars['newpathwkt'] = $spatial_pro->composeMultipointWKTStringFromSVGPath($this->formvars['newpath']);
			}
		}
		if ($this->formvars['newpathwkt'] != '') {
			include_(CLASSPATH.'multigeomeditor.php');
			$multigeomeditor = new multigeomeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
			$ret = $multigeomeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
			if ($ret[0]) { # fehlerhafte eingabedaten
				$this->error_position = explode(' ', trim(substr($ret[1], strpos($ret[1], '[')), '[]'));
				$this->Meldung1=$ret[1];
				$this->neuer_Layer_Datensatz();
				return;
			}
		}	
		$this->success = true;
		foreach ($tablename as $table) {
			$insert = array();
			$exif_data = array();
			if ($table['tablename'] != '' AND $table['tablename'] == $layerset[0]['maintable']) {		# nur Attribute aus der Haupttabelle werden gespeichert
				for ($i = 0; $i < count($table['attributname']); $i++) {
					if (array_key_exists($table['attributname'][$i], $attributes['constraints'])) { 	# Rechte
						$this->sanitize([$table['formfield'][$i] => $table['datatype'][$i]], true);
						#echo '<br>' . $table['attributname'][$i] . ' type: ' . $table['type'][$i];
						switch (true) {
							case ($table['type'][$i] == 'Time') : {                       # Typ "Time"
								if (in_array($attributes['options'][$table['attributname'][$i]], array('', 'insert'))){
									$insert[$table['attributname'][$i]] = "'" . date("Y-m-d H:i:s") . "'";
								}
							} break;

							case ($table['type'][$i] == 'User') : {                       # Typ "User"
								if (in_array($attributes['options'][$table['attributname'][$i]], array('', 'insert'))){
									$insert[$table['attributname'][$i]] = "'" . $this->user->Vorname . " " . $this->user->Name."'";
								}
							} break;

							case ($table['type'][$i] == 'UserID') : {                       # Typ "UserID"
								if (in_array($attributes['options'][$table['attributname'][$i]], array('', 'insert'))){
									$insert[$table['attributname'][$i]] = "'" . $this->user->id."'";
								}
							} break;

							case ($table['type'][$i] == 'Stelle') : {                       # Typ "Stelle"
								if (in_array($attributes['options'][$table['attributname'][$i]], array('', 'insert'))){
									$insert[$table['attributname'][$i]] = "'" . $this->Stelle->Bezeichnung . "'";
								}
							} break;

							case ($table['type'][$i] == 'StelleID') : {                       # Typ "StelleID"
								if (in_array($attributes['options'][$table['attributname'][$i]], array('', 'insert'))){
									$insert[$table['attributname'][$i]] = "'" . $this->Stelle->id . "'";
								}
							} break;

							case ($table['type'][$i] == 'Dokument' AND $this->formvars[$table['formfield'][$i]] != '') : {
								$insert[$table['attributname'][$i]] = "'" . $this->formvars[$table['formfield'][$i]] . "'";
								$this->formvars[$table['formfield'][$i]] = ''; # leeren, for the case weiter_erfassen angehakt
							} break;

							case (
								$table['saveable'][$i] AND
								$table['type'][$i] != 'SubFormPK' AND
								($this->formvars[$table['formfield'][$i]] != '' OR $table['type'][$i] == 'Checkbox')
							) : {
								if ($table['type'][$i] != 'Dokument' AND (substr($table['datatype'][$i], 0, 1) == '_' OR is_numeric($table['datatype'][$i]))){		// bei Dokumenten wurde das JSON schon weiter oben verarbeitet
									# bei einem custom Datentyp oder Array das JSON in PG-struct umwandeln
									$insert[$table['attributname'][$i]] = "'" . $this->processJSON($this->formvars[$table['formfield'][$i]], $doc_path, $doc_url) . "'";
								}
								else {
									if ($table['type'][$i] == 'Checkbox' AND $this->formvars[$table['formfield'][$i]] == '') {
										$this->formvars[$table['formfield'][$i]] = 'f';
									}
									if ($table['type'][$i] == 'Link') {
										if (substr($this->formvars[$table['formfield'][$i]], 0, 9) != 'index.php' AND strpos($this->formvars[$table['formfield'][$i]], ':') === false) {
											# bei externen Links http davor setzen, wenn kein Protokoll angegeben
											$this->formvars[$table['formfield'][$i]] = 'http://' . $this->formvars[$table['formfield'][$i]];
										}
									}

									if ($table['type'][$i] == 'date') {
										// convert from german notation to english '25.12.1966' to '1966-12-25'
										$insert[$table['attributname'][$i]] = DateTime::createFromFormat('d.m.Y', $insert[$table['attributname'][$i]])->format('Y-m-d');
									};
									if ($table['type'][$i] == 'time') {
										// do nothing format is same in english and german
									}
									if ($table['type'][$i] == 'timestamp') {
										// convert from german notation to english '25.12.1966 08:01:02' to '1966-12-25 08:01:02'
										if (strlen($insert[$table['attributname'][$i]]) > 19) {
											$insert[$table['attributname'][$i]] = DateTime::createFromFormat('d.m.Y H:i:s.u', $insert[$table['attributname'][$i]])->format('Y-m-d H:i:s.u');
										}
										else {
											$insert[$table['attributname'][$i]] = DateTime::createFromFormat('d.m.Y H:i:s', $insert[$table['attributname'][$i]])->format('Y-m-d H:i:s');
										}
									};

									$insert[$table['attributname'][$i]] = "'" . $this->formvars[$table['formfield'][$i]] . "'"; # Typ "normal"
								}
							} break;

							case ($table['type'][$i] == 'ExifLatLng') : {
								$document_attribute_name = $attributes['options'][$table['attributname'][$i]];

								if (!$exif_data[$document_attribute_name]) {
									$exif_data[$document_attribute_name] = get_exif_data(get_document_file_path($document_attributes[$form_field_indizes[$document_attribute_name]]['insert'], $doc_path, $doc_url));
								}
								if ($exif_data[$document_attribute_name]['success']) {
									$insert[$table['attributname'][$i]] = ($exif_data[$document_attribute_name]['LatLng'] ? "'" . $exif_data[$document_attribute_name]['LatLng'] . "'" : "NULL");
								}
							} break;

							case ($table['type'][$i] == 'ExifRichtung') : {
								$document_attribute_name = $attributes['options'][$table['attributname'][$i]];
								if (!$exif_data[$document_attribute_name]) {
									$exif_data[$document_attribute_name] = get_exif_data(get_document_file_path($document_attributes[$form_field_indizes[$document_attribute_name]]['insert'], $doc_path, $doc_url));
								}
								if ($exif_data[$document_attribute_name]['success']) {
									$insert[$table['attributname'][$i]] = ($exif_data[$document_attribute_name]['Richtung'] ? $exif_data[$document_attribute_name]['Richtung'] : "NULL");
								}
							} break;

							case ($table['type'][$i] == 'ExifErstellungszeit') : {
								$document_attribute_name = $attributes['options'][$table['attributname'][$i]];
								if (!$exif_data[$document_attribute_name]) {
									$exif_data[$document_attribute_name] = get_exif_data(get_document_file_path($document_attributes[$form_field_indizes[$document_attribute_name]]['insert'], $doc_path, $doc_url));
								}
								if ($exif_data[$document_attribute_name]['success']) {
									$insert[$table['attributname'][$i]] = ($exif_data[$document_attribute_name]['Erstellungszeit']  ? "'" . $exif_data[$document_attribute_name]['Erstellungszeit'] . "'" : "NULL");
								}
							} break;

							case ($table['type'][$i] == 'Geometrie') : {
								if ($this->formvars['geomtype'] == 'POINT') {
									if ($this->formvars['loc_x'] != '') {
										# ToDo: Test if a new Point can be stored and if the statement contain the wkb_geometrie in stead of the ST_GeomFromGeo Gedöns.
										include_once (CLASSPATH.'pointeditor.php');
										$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
										$result = $pointeditor->get_wkb_geometry(array(
											'loc_x' => $this->formvars['loc_x'],
											'loc_y' => $this->formvars['loc_y'],
											'dimension' => $this->formvars['dimension']
										));
										if ($result['success']) {
											$insert[$table['attributname'][$i]] = "'" . $result['wkb_geometry'] . "'";
										}
										else {
											$this->add_message('error', 'Umwandeln der Punktgeometrie in WKB-Geometry gescheitert! ' . $result['err_msg']);
											$this->success = false;
										}
									}
								}
								elseif ($this->formvars['newpathwkt'] != '') {
									# ToDo: Replace this also with a get_wkb_geometry function from polygoneditor and replace wkb_geometry generation in
									# PointEditor_Senden also by a function and also for Line and Polygon editing cases
									$result = $multigeomeditor->get_wkb_geometry(array(
										'geomtype' => $this->formvars['geomtype'],
										'geom' => $this->formvars['newpathwkt']
									));									
									if ($result['success']) {
										$insert[$table['attributname'][$i]] = "'" . $result['wkb_geometry'] . "'";
									}
									else {
										$this->add_message('error', 'Umwandeln der Liniengeometrie in WKB-Geometry gescheitert! ' . $result['err_msg']);
										$this->success = false;
									}
								}
							} break;
							default : {
								switch ($datatype) {
									case 'numeric':
									case 'float4' :
									case 'float8' : {
										$eintrag = str_replace(',', '.', $this->formvars[$form_fields[$i]]);
									} break;
									case 'date' : {
										// konvert from german notation to english '25.12.1966' to '1966-12-25'
										$eintrag = DateTime::createFromFormat('d.m.Y', $this->formvars[$form_fields[$i]])->format('Y-m-d');
									} break;
									case 'time' : {
										// do nothing format is same in english and german
									} break;
									case 'timestamp' : {
										$eintrag = DateTime::createFromFormat('d.m.Y H:i:s', $this->formvars[$form_fields[$i]])->format('Y-m-d H:i:s');
									} break;
									default : {
										$eintrag = $this->formvars[$form_fields[$i]];
									}
								}
							}
						} # end of switch
					}
				}
				if (!empty($insert)) {
					if (!$layerset[0]['maintable_is_view']) {
						$sql = "LOCK TABLE " . pg_quote($table['tablename']) . " IN SHARE ROW EXCLUSIVE MODE;";
					}
					$attr = array_keys($insert);
					array_walk($attr, function(&$attributename, $key){$attributename = pg_quote($attributename);});
					$sql = "
						INSERT INTO " . pg_quote($table['tablename']) . " (
							" . implode(', ', $attr) . "
						)
						VALUES (
							" . implode(', ', $insert) . "
						)"
						. ($layerset[0]['oid'] != 'oid' ? " RETURNING " . $layerset[0]['oid'] : "") . "
					";

					# Before Insert trigger
					if (!empty($layerset[0]['trigger_function'])) {
						$this->exec_trigger_function('BEFORE', 'INSERT', $layerset[0]);
					}
					$this->debug->write("<p>file:kvwmap class:neuer_Layer_Datensatz_speichern :",4);
					$this->debug->show('<p>SQL zum Anlegen des Datensatzes: ' . $sql);
					#echo '<p>SQL zum Anlegen des Datensatzes: ' . $sql;
					$ret = $layerdb->execSQL($sql, 4, 1, true);

					if ($ret['success']) {
						if ($ret['msg'] != '') {
							$this->add_message('info', $ret['msg']);
						}
						$result = pg_fetch_row($ret['query']);
						if (pg_affected_rows($ret['query']) > 0) {
							# dataset was created
							if (is_array($result) and (!array_key_exists(1, $result) OR $result[1] != 'error') AND $layerset[0]['oid'] == 'oid') {
								$this->add_message('warning', 'Eintrag erfolgreich.<br>' . $result[0]);
							}
							else {
								$this->add_message('notice', 'Eintrag erfolgreich!');
							}

							if ($layerset[0]['oid'] != 'oid') {
								$last_oid = $result[0];
							}
							else {
								$last_oid = pg_last_oid($ret['query']);
							}
							if ($last_oid == '') {
								$last_oid = $notice_result['oid'];
							}
							if ($last_oid != '' AND $this->user->rolle->upload_only_file_metadata == 1 AND is_array($belated_files)) {
								include_once(CLASSPATH . 'BelatedFile.php');
								$results = array();
								foreach ($belated_files AS $i => $belated_file) {
									$file = json_decode($belated_file);
									$results[] = BelatedFile::insert(
										$this, array(
											'user_id' => $this->user->id,
											'layer_id' => $this->formvars['selected_layer_id'],
											'dataset_id' => $last_oid,
											'attribute_name' => $document_attributes[$i]['attributename'],
											'name' => $file->name,
											'size' => $file->size,
											'lastmodified' => $file->lastmodified,
											'file' => $document_attributes[$i]['insert']
										)
									);
								}
							}

							if ($this->formvars['embedded'] == '') {
								$this->formvars['value_' . $table['tablename'] . '_oid'] = $last_oid;
							}

							# After Insert trigger
							if (!empty($layerset[0]['trigger_function'])) {
								$this->exec_trigger_function('AFTER', 'INSERT', $layerset[0], $last_oid);
							}
						}
						else {
							# dataset was not created
							$this->success = false;
							$this->add_message('error', 'Eintrag fehlgeschlagen.<br>' . $result[0]);
						}
					}
					else {
						# query not successfull set query error message
						$this->success = false;
						$this->add_message(
							$ret['type'],
							sql_err_msg('Kann Datensatz nicht speichern:<br>', $sql, $ret['msg'], 'error_div_' . rand(1, 99999))
						);
					}
				}
			}
		}

		if ($this->formvars['embedded'] != '') {
			# wenn es ein neuer Datensatz aus einem embedded-Formular ist,
			# muss das entsprechende Attribut des Hauptformulars aktualisiert werden
			header('Content-type: text/html; charset=UTF-8');
			$attributename[0] = $this->formvars['targetattribute'];
			$attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, NULL);

			switch ($attributes['form_element_type'][$this->formvars['targetattribute']]){
				case 'Auswahlfeld' : {
					# das Auswahlfeld wird ausgetauscht und die Option gleich selektiert
					$attributes = $mapdb->add_attribute_values($attributes, $layerdb, $insert_data, true, $this->Stelle->id);
					$index = $attributes['indizes'][$this->formvars['targetattribute']];
					if(is_array($attributes['dependent_options'][$index])){
						$enums = $attributes['enum'][$index][0];
					}
					else{
						$enums = $attributes['enum'][$index];
					}
					foreach ($enums as $enum_key => $enum){
						$html .= '<option ';
						if ($last_oid == $enum['oid'])
							{ $html .= 'selected ';
						}
						$html .= 'value="'.$enum_key.'">'.$enum['output'].'</option>';
					}
					echo '█'.$html.'█document.getElementById("'.$this->formvars['targetobject'].'").onchange();';
        } break;

        case 'SubFormEmbeddedPK' : {
					if($this->formvars['reload']){			# in diesem Fall wird die komplette Seite neu geladen
						echo '██currentform.go.value=\'get_last_query\';overlay_submit(currentform, false);';
					}
					else{
						echo '██reload_subform_list(\''.$this->formvars['targetobject'].'\', \''.$this->formvars['list_edit'].'\', \''.$this->formvars['weiter_erfassen'].'\', \''.urlencode($formfieldstring).'\');';
					}
					if(!empty(GUI::$messages)){
						echo 'message('.json_encode(GUI::$messages).');';
					}
        } break;
      }
    }
		else {
			if ($this->formvars['only_create']) {
				# Hier wird keine weitere Funktion zum Laden von views aufgerufen
			}
			else {
				if ($this->success == false) {
					$this->neuer_Layer_Datensatz();
				}
				else {
					$this->formvars['newpathwkt'] = '';
	        if ($this->formvars['weiter_erfassen'] == 1) {
	        	$this->formvars['firstpoly'] = '';
	        	$this->formvars['firstline'] = '';
	        	$this->formvars['secondpoly'] = '';
	        	$this->formvars['pathwkt'] = '';
	        	$this->formvars['newpath'] = '';
	        	$this->formvars['last_doing'] = '';
	        	$this->neuer_Layer_Datensatz();
	        }
	        else {
	        	$this->GenerischeSuche_Suchen();
	        }
				}
      }
    }
  }

	function neuer_Layer_Datensatz() {
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->titel = 'neuen Datensatz einfügen';
		$this->main = 'new_layer_data.php';
		if ($this->formvars['chosen_layer_id']) {			# für neuen Datensatz verwenden -> von der Sachdatenanzeige übergebene Formvars
			$this->formvars['CMD'] = '';
			$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
		}

		if ($this->formvars['selected_layer_id'] == '') {
			$this->layerdaten = $this->Stelle->getqueryablePostgisLayers(1, NULL, true); # wenn kein Layer vorausgewählt, Subform-Layer ausschliessen
		}
		else {
			$this->layerdaten = $this->Stelle->getqueryablePostgisLayers(1, NULL, false); # ansonsten nicht	
			$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			if ($layerset[0]['privileg'] > 0) { # überprüfen, ob Recht zum Erstellen von neuen Datensätzen gesetzt ist
				$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
				$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
				$attribute_values = [];
				foreach ($this->formvars['attributenames'] AS $key => $attributename) {
					$attribute_values[$attributename] = $this->formvars['values'][$key];
				}
				$layerset[0]['attributes'] = $mapDB->read_layer_attributes(
					$this->formvars['selected_layer_id'], // layer_id
					$layerdb, // layerdb
					$privileges['attributenames'], // attributenames
					false, // all_languages
					true, // recursive
					true, // get_default
					true, // replace
					array('default', 'options'), // replace_only
					$attribute_values // value_attributes
				);
				if (value_of($this->formvars, 'geom_from_layer') == '') {
					$this->formvars['geom_from_layer'] = $layerset[0]['geom_from_layer'];
				}
				$form_fields = explode('|', $this->formvars['form_field_names']);
				for ($i = 0; $i < count($form_fields); $i++) {
					if ($form_fields[$i] != '') {
						$element = explode(';', $form_fields[$i]);
						$this->sanitize([$form_fields[$i] => $element[6]], true);
						$formElementType = $layerset[0]['attributes']['form_element_type'][$layerset[0]['attributes']['indizes'][$element[1]]];
					}
				}

				######### für neuen Datensatz verwenden -> von der Sachdatenanzeige übergebene Formvars #######
				if ($this->formvars['chosen_layer_id'] OR $this->formvars['weiter_erfassen']) {
					$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
					for ($i = 0; $i < count($checkbox_names); $i++) {
						if (value_of($this->formvars, $checkbox_names[$i]) == 'on') {
							$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
							$oid = $element[3];
						}
					}
					for ($i = 0; $i < count($form_fields); $i++) {
						if ($form_fields[$i] != '') {
							$element = explode(';', $form_fields[$i]);
							$formElementType = $layerset[0]['attributes']['form_element_type'][$layerset[0]['attributes']['indizes'][$element[1]]];
							$dont_use_for_new = $layerset[0]['attributes']['dont_use_for_new'][$layerset[0]['attributes']['indizes'][$element[1]]];
							if (
								$element[3] == $oid AND
								!in_array($layerset[0]['attributes']['constraints'][$element[1]],  array('PRIMARY KEY', 'UNIQUE')) AND  # Primärschlüssel werden nicht mitübergeben
								!in_array($formElementType, array('Time', 'User', 'UserID', 'Stelle', 'StelleID', 'ClientID')) AND # und automatisch generierte Typen auch nicht
								$dont_use_for_new != 1
							) {
								$element[3] = '';
								$this->formvars[implode(';', $element)] = value_of($this->formvars, $form_fields[$i]);
							}
							else $this->formvars[implode(';', $element)] = '';
						}
					}
				}

				######### von einer Sachdatenanzeige übergebene Formvars #######
				for ($j = 0; $j < count($layerset[0]['attributes']['name']); $j++) {
					# Hier auch nur Werte übergeben, die in neues Formular übernommen werden sollen
					$attributes = $layerset[0]['attributes'];
					$attribute_name = $attributes['name'][$j];
					$index = $attributes['indizes'][$attribute_name];

					$layerset[0]['attributes']['privileg'][$j] = $layerset[0]['attributes']['privileg'][$attribute_name] = $privileges[$attribute_name];

					if ($attributes['dont_use_for_new'][$index] == 1) {
						$new_value = '';
					}
					else {
						$new_value =  $this->formvars[$layerset[0]['Layer_ID'].';'.$layerset[0]['attributes']['real_name'][$attribute_name].';'.$layerset[0]['attributes']['table_name'][$attribute_name].';;'.$layerset[0]['attributes']['form_element_type'][$j].';'.$layerset[0]['attributes']['nullable'][$j].';'.$layerset[0]['attributes']['type'][$j].';'.$layerset[0]['attributes']['saveable'][$j]];
					}

					$layerset[0]['shape'][-1][$attribute_name] = $new_value;

					if (
						$layerset[0]['shape'][-1][$attribute_name] == '' AND
						$layerset[0]['attributes']['default'][$j] != ''
					) {
						// Wenn Defaultwert da und Feld leer, Defaultwert setzen
						$layerset[0]['shape'][-1][$attribute_name] = $layerset[0]['attributes']['default'][$j];
					}
					if ($layerset[0]['attributes']['form_element_type'][$j] == 'Winkel') {
						$this->angle_attribute = $attribute_name;
					}
				}
				$this->formvars['layer_columnname'] = $layerset[0]['attributes']['the_geom'];
				$this->formvars['layer_tablename'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];
				$this->qlayerset[0]=$layerset[0];

				# wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
				if (is_array(value_of($this->formvars, 'attributenames'))) {
					$attributenames = array_values($this->formvars['attributenames']);
					$values = array_values($this->formvars['values']);
					for ($i = 0; $i < count($attributenames); $i++) {
						$this->qlayerset[0]['shape'][-1][$attributenames[$i]] = $values[$i];
					}
				}

				# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
				$this->qlayerset[0]['attributes'] = $mapDB->add_attribute_values($this->qlayerset[0]['attributes'], $layerdb, $this->qlayerset[0]['shape'], true, $this->Stelle->id);
				$this->new_entry = true;

				$this->geomtype = $this->qlayerset[0]['attributes']['geomtype'][$this->qlayerset[0]['attributes']['the_geom']];
				if ($this->geomtype != '' AND ($this->formvars['window_type'] != 'overlay' OR $this->formvars['embedded'] == '')) {
					$saved_scale = $this->reduce_mapwidth(40);
					$oldscale=round($this->map_scaledenom);
					if ($oldscale != value_of($this->formvars, 'nScale') OR value_of($this->formvars, 'neuladen') OR $this->formvars['CMD'] != '') {
						$this->neuLaden();
					}
					else {
						$this->loadMap('DataBase');
					}
					if ($saved_scale != NULL) {
						# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
						$this->scaleMap($saved_scale);
					}
					# zoom_to_max_layer_extent
					if (value_of($this->formvars, 'zoom_layer_id') != '') {
						$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
					}
					# Zoom auf Geometrie-Fehler-Position
					if ($this->error_position != '') {
						$rect = rectObj($this->error_position[0]-50,$this->error_position[1]-50,$this->error_position[0]+50,$this->error_position[1]+50);
						$this->map_scaledenom = $this->map->scaledenom;
					}
					# evtl. Zoom auf "Mutter-Layer"
					if ($this->formvars['layer_id_mother'] != '' AND $this->formvars['oid_mother'] != '' AND $this->formvars['tablename_mother'] != '' AND $this->formvars['columnname_mother'] != '') {
						# das sind die Sachen vom "Mutter"-Layer
						$parentlayerset = $this->user->rolle->getLayer($this->formvars['layer_id_mother']);
						$layerdb2 = $this->mapDB->getlayerdatabase($this->formvars['layer_id_mother'], $this->Stelle->pgdbhost);
						$parentlayerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['layer_id_mother'], $layerdb2, NULL, false, true, true);
						$rect = $this->mapDB->zoomToDatasets(array($this->formvars['oid_mother']), $parentlayerset[0], $this->formvars['columnname_mother'], 10, $layerdb2, $this->user->rolle->epsg_code, $this->Stelle);
						if ($rect->minx != '') {
							$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy); # Zoom auf den "Mutter"-Datensatz
							if (MAPSERVERVERSION > 600) {
								$this->map_scaledenom = $this->map->scaledenom;
							}
							else {
								$this->map_scaledenom = $this->map->scale;
							}
						}
					}
					if (in_array($this->geomtype, ['POLYGON', 'MULTIPOLYGON', 'GEOMETRY', 'LINESTRING', 'MULTILINESTRING', 'MULTIPOINT'])) {
						#-----Polygoneditor, Linieneditor oder Multipointeditor ---#
						$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true, true);

						if ($this->formvars['chosen_layer_id'] AND $privileges[$this->qlayerset[0]['attributes']['the_geom']] == 1) {			# für neuen Datensatz verwenden -> Geometrie abfragen
							include_once (CLASSPATH.'multigeomeditor.php');
							$polygoneditor = new multigeomeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
							$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
							$this->polygon = $polygoneditor->getGeom($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->formvars['layer_schemaname']);
							if ($this->polygon['wktgeom'] != '') {
								$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
								$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
								$this->formvars['newpath'] = $this->polygon['svggeom'];
								$this->formvars['firstpoly'] = 'true';
								$this->formvars['firstline'] = 'true';
								if ($this->formvars['zoom'] != 'false') {
									$rect = $polygoneditor->zoomToGeom($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL, $this->formvars['layer_schemaname']);
									$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
									$this->map_scaledenom = $this->map->scaledenom;
								}
							}
						}
						#-----Polygoneditor, Linieneditor oder Multipointeditor ---#
					}
					elseif ($this->geomtype == 'POINT') {
						#-----Pointeditor-----#
						if ($this->formvars['chosen_layer_id'] AND $privileges[$this->qlayerset[0]['attributes']['the_geom']] == 1) {			# für neuen Datensatz verwenden -> Geometrie abfragen
							include_once (CLASSPATH.'pointeditor.php');
							$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code, $layerset[0]['oid']);
							$this->point = $pointeditor->getpoint($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->angle_attribute);
							if ($this->point['pointx'] != '') {
								$this->formvars['loc_x']=$this->point['pointx'];
								$this->formvars['loc_y']=$this->point['pointy'];
								$rect = rectObj(
									$this->point['pointx']-100,
									$this->point['pointy']-100,									
									$this->point['pointx']+100,
									$this->point['pointy']+100
								);
								$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
								$this->map_scaledenom = $this->map->scaledenom;
							}
						}
						#-----Pointeditor-----#
					}
					$this->saveMap('');
					if ($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next') {
						$currenttime=date('Y-m-d H:i:s',time());
						$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
					}
					$this->drawMap();
				}
			}
			else {
				$this->Fehler = 'Das Erstellen von neuen Datensätzen ist für diesen Layer in dieser Stelle nicht erlaubt.';
			}
		}
		if ($this->formvars['embedded'] != '') {
			ob_end_clean();
			header('Content-type: text/html; charset=UTF-8');
			include(LAYOUTPATH.'snippets/new_layer_data_embedded.php');
		}
		else {
			$this->output();
		}
	}

	function sachdaten_druck_editor_autogenerate(){
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['aktivesLayout'] = $this->ddl->autogenerate_layout($this->formvars['selected_layer_id'], $this->attributes, $this->Stelle->id);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor(){
		global $admin_stellen;
		include_once(CLASSPATH . 'datendrucklayout.php');
		$ddl=new ddl($this->database, $this);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    if(in_array($this->Stelle->id, $admin_stellen)){										# eine Admin-Stelle darf alle Layer und Stellen sehen
			$this->layerdaten = $mapdb->get_layers_of_type(MS_POSTGIS, 'Name');
			$this->stellendaten=$this->user->getStellen(0);
		}
		else{																																# eine normale Stelle nur die eigenen Layer und die eigene Stelle
			$this->layerdaten = $this->Stelle->getqueryablePostgisLayers(NULL, NULL);
			$this->stellendaten['ID'][0] = $this->Stelle->id;
			$this->stellendaten['Bezeichnung'][0] = $this->Stelle->Bezeichnung;
		}
    # Fonts auslesen
    $this->ddl->fonts = $this->ddl->get_fonts();
    if($this->formvars['selected_layer_id']){
      $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
      $this->ddl->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
			# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
			$this->ddl->attributes = $mapdb->add_attribute_values($this->ddl->attributes, $layerdb, NULL, true, $this->Stelle->id);
      $this->ddl->layouts = $this->ddl->load_layouts(NULL, NULL, $this->formvars['selected_layer_id'], NULL);
    }
    if($this->formvars['aktivesLayout']){
    	$this->ddl->selectedlayout = $this->ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, NULL);
    }
    if($this->ddl->selectedlayout != NULL){
      $this->previewfile = $this->sachdaten_druck_editor_preview($this->ddl->selectedlayout[0]);
    }
    $this->main='datendrucklayouts.php';
    $this->titel='Datendruck-Layouteditor';
    $this->output();
	}

	function sachdaten_druck_editor_speichern(){
		$_files = $_FILES;
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl = $ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['aktivesLayout'] = $this->ddl->save_layout($this->formvars, $this->attributes, $_files, $this->Stelle->id);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_aendern(){
		$_files = $_FILES;
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
	}

	function sachdaten_druck_editor_loeschen(){
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl = new ddl($this->database);
		$this->ddl = $ddl;
		$this->ddl->delete_layout($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_add2stelle(){
		include_(CLASSPATH . 'datendrucklayout.php');
		$ddl=new ddl($this->database);
    $this->ddl=$ddl;
    $this->ddl->add_layout2stelle($this->formvars['aktivesLayout'], $this->formvars['stelle']);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Freitexthinzufuegen(){
		include_once(CLASSPATH.'datendrucklayout.php');
		$this->ddl = new ddl($this->database, $this);
		$this->ddl->fonts = $this->ddl->get_fonts();
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$this->ddl->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		if($this->formvars['size'] != '')$size = $this->formvars['size'];	else $size = 11;
		$font = ($this->formvars['font'] == '' ? 'Helvetica.afm' : $this->formvars['font']); # font darf nicht leer sein weil beim Export null rauskommen würde. ToDo: Warum kommt beim Layer-Export mit '' eigentlich null raus?
		if($this->formvars['posx'] != '')$posx = $this->formvars['posx']; else $posx = 70;
		if($this->formvars['posy'][$i] != '')$posy = $this->formvars['posy']-20; else $posy = 0;
    $new_freetext_id = $this->ddl->addfreetext($this->formvars['aktivesLayout'], $this->formvars['texttext'.$i], $posx, $posy, $size, $font, $this->formvars['textoffset_attribute'.$i]);
		$text = $this->ddl->load_texts($this->formvars['aktivesLayout'], $new_freetext_id);
		$this->ddl->output_freetext_form($text, $this->formvars['selected_layer_id'], $this->formvars['aktivesLayout']);
	}

	function sachdaten_druck_editor_Freitextloeschen(){
		$this->sachdaten_druck_editor_aendern();
    $this->ddl->removefreetext($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Liniehinzufuegen(){
		$this->sachdaten_druck_editor_aendern();
    $this->ddl->addline($this->formvars);
		$this->scrolldown = true;
		$this->sachdaten_druck_editor();
	}

	/**
	* Function edit settings of free thema print layout lines in table druckfreilinien
	* @param integer $line_id It query for existing print layout with line_id
	* @param string $line_attribute_name Name of the attribute that has to be changed with $line_attribute_value
	* @param string $line_attribute_value Value of the attribute $line_attribute_name
	* @return Json String with result from MyObject update function
	*/
	function sachdaten_druck_editor_linie_aendern($line_id, $line_attribute_name, $line_attribute_value) {
		$myObj = new MyObject($this, 'druckfreilinien');
		$druckfreilinie = $myObj->find_by_ids($line_id);
		$druckfreilinie->set($line_attribute_name, $line_attribute_value);
		$this->qlayerset[0]['shape'] = $druckfreilinie->update();
		$this->mime_type = 'application/json';
		$this->formvars['format'] = 'json';
		$this->formvars['content_type'] = 'application/json';
		$this->output();
	}

	function sachdaten_druck_editor_Linieloeschen(){
		$this->sachdaten_druck_editor_aendern();
    $this->ddl->removeline($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Rechteckhinzufuegen(){
		$this->sachdaten_druck_editor_aendern();
		$i = $this->formvars['rectcount'] - 1;
		if($this->formvars['rectbreite'.$i] != '')$breite = $this->formvars['rectbreite'.$i];	else $breite = 1;
		if($this->formvars['rectposx'.$i] != '')$posx = $this->formvars['rectposx'.$i]; else $posx = 70;
		if($this->formvars['rectposy'.$i] != '')$posy = $this->formvars['rectposy'.$i]-20; else $posy = 50;
		if($this->formvars['rectendposx'.$i] != '')$endposx = $this->formvars['rectendposx'.$i]; else $endposx = 520;
		if($this->formvars['rectendposy'.$i] != '')$endposy = $this->formvars['rectendposy'.$i]-20; else $endposy = 150;
    $this->ddl->addrectangle($this->formvars['aktivesLayout'], $posx, $posy, $endposx, $endposy, $breite, $this->formvars['rectoffset_attribute_start'.$i], $this->formvars['rectoffset_attribute_end'.$i]);
		$this->scrolldown = true;
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Rechteckloeschen(){
		$this->sachdaten_druck_editor_aendern();
    $this->ddl->removerectangle($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_preview($selectedlayout, $pdfobject = NULL, $offsetx = NULL, $offsety = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		include_once (CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true, $this->Stelle->id);
    # Testdaten erzeugen
		if($selectedlayout['type'] != 0)$count = 2;else $count = 1;		# nur beim Untereinandertyp oder eingebettet-Typ mehrere Datensätze erzeugen
    for($i = 0; $i < $count; $i++){
	    for($j = 0; $j < count($attributes['name']); $j++){
	    	if($attributes['type'][$j] != 'geometry' ){
	    		if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
					if(substr($attributes['type'][$j], 0, 1) == '_'){		# Array
						$result[$i][$attributes['name'][$j]] = '["'.$attributes['alias'][$j].'","'.$attributes['alias'][$j].'","'.$attributes['alias'][$j].'"]';
					}
					else{
						$result[$i][$attributes['name'][$j]] = $attributes['alias'][$j];
					}
	    	}
	    }
    }
    $result = $ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['selected_layer_id'], $selectedlayout, $result, $this->Stelle, $this->user, true);
		if ($pdfobject == NULL){		# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			# in jpg umwandeln
			$currenttime = date('Y-m-d_H_i_s',time());
			exec(IMAGEMAGICKPATH.'convert "'.$result['pdf_file'].'[' . ($this->formvars['page'] ? $this->formvars['page'] : 0) . ']" -resize '.$selectedlayout['width'].'x'.$selectedlayout['height'].' "'.dirname($result['pdf_file']).'/'.basename($result['pdf_file'], ".pdf").'-'.$currenttime.'.jpg"');
			#echo IMAGEMAGICKPATH.'convert "'.$result['pdf_file'].'[0]" -resize 595x1000 "'.dirname($result['pdf_file']).'/'.basename($result['pdf_file'], ".pdf").'-'.$currenttime.'.jpg"';
			if(!file_exists(IMAGEPATH.basename($result['pdf_file'], ".pdf").'-'.$currenttime.'.jpg')){
				return TEMPPATH_REL.basename($result['pdf_file'], ".pdf").'-'.$currenttime.'-0.jpg';
			}
			else{
				return TEMPPATH_REL.basename($result['pdf_file'], ".pdf").'-'.$currenttime.'.jpg';
			}
		}
		else{
			return $result['y'];  # das ist der letzte y-Wert, um nachfolgende Elemente darunter zu setzen
		}
	}

	function generischer_sachdaten_druck() {
		include_(CLASSPATH . 'datendrucklayout.php');
		$result = array();
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		$layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, NULL, false, true);
		$path = $mapDB->getPath($this->formvars['chosen_layer_id']);
		$privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
		# Attribute laden
		$attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);

		$query_parts = $mapDB->getQueryParts($layerset[0], $privileges);
		$newpath = $query_parts['query'];

		$geometrie_tabelle = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];

		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		# Daten abfragen
		for ($i = 0; $i < count($checkbox_names); $i++) {
			if ($this->formvars[$checkbox_names[$i]] == 'on') {
				$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
				$oids[] = $element[3];
			}
		}
		$sql = "
			SELECT 
				" . $query_parts['select'] . "
			FROM 
				(SELECT " . $query_parts['query'] . ") as foo
			WHERE
				" . pg_quote($layerset[0]['maintable'] . "_oid") . " IN ('" . implode("','", $oids) . "')
			" . ($this->formvars['orderby' . $this->formvars['chosen_layer_id']] != ''? 'ORDER BY ' . $this->formvars['orderby' . $this->formvars['chosen_layer_id']] : '') . "
		";
		#echo "<br>SQL zur Abfrage von Datensätzen für den Sachdatendruch: " . $sql;
		$this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :",4);
		$ret = $layerdb->execSQL($sql,4, 1);
		if (!$ret[0]) {
			while ($rs=pg_fetch_array($ret[1])) {
				$result[] = $rs;
			}
		}
		# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, $result, true, $this->Stelle->id);
		$this->attributes = $attributes;
		# Layouts abfragen
		$this->ddl->layouts = $this->ddl->load_layouts($this->Stelle->id, NULL, $this->formvars['chosen_layer_id'], array(0,1));

		if (count($this->ddl->layouts) == 1) {
			$this->formvars['aktivesLayout'] = $this->ddl->layouts[0]['id'];
		}
		else {
			if ($this->formvars['aktivesLayout'] == '') {
				$this->formvars['aktivesLayout'] = $result[0][$layerset[0]['ddl_attribute']];
			}
		}

		# aktives Layout abfragen
		if ($this->formvars['aktivesLayout'] != '') {
			$this->ddl->selectedlayout = $this->ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, array(0,1));
			for ($i = 0; $i < count($this->ddl->selectedlayout[0]['texts']); $i++) {
				if (strpos($this->ddl->selectedlayout[0]['texts'][$i]['text'], '$pagenumber') !== false) {
					$this->page_numbering = true;
				}
			}
			# PDF erzeugen
			$result = $this->ddl->createDataPDF(NULL, NULL, NULL, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $this->ddl->selectedlayout[0], $result, $this->Stelle, $this->user, $this->formvars['record_paging']);
			# in jpg umwandeln
			$file_name = basename($result['pdf_file'], ".pdf") . '-' . date('Y-m-d_H_i_s', time()) . '.jpg';
			$command = IMAGEMAGICKPATH . 'convert "' . $result['pdf_file'] . '[0]" -resize 595x1000 "' . dirname($result['pdf_file']) . '/' . $file_name . '"';
			exec($command);
			#echo $command;
			$this->previewfile = TEMPPATH_REL . (file_exists(IMAGEPATH . $file_name) ? $file_name : substr_replace($file_name, '-0.jpg', -4));
		}
		$this->main = 'generischer_sachdaten_druck.php';
		$this->titel = 'Sachdaten-Druck';
		$this->output();
	}

	function generischer_sachdaten_druck_drucken() {
		if ($this->formvars['archivieren']) {
			$oids = array();
			if ($this->formvars['oid'] == '') {
				$oid_uebergeben = false;
				// echo '<br>Frage alle Datensatz-IDs des Layers ab.';
				$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
				$this->formvars['no_output'] = true;
				$this->GenerischeSuche_Suchen();
				foreach ($this->qlayerset[0]['shape'] as $row) {
					$oids[] = $row[$this->qlayerset[0]['maintable'] . '_oid'];
				}
				sort($oids);
			}
			else {
				$oid_uebergeben = true;
				$oids[] = $this->formvars['oid'];
			}
			foreach ($oids AS $oid) {
				if (!$oid_uebergeben) {
					unset($this->qlayerset);
				}
				$this->formvars['oid'] = $oid;
				$result = $this->generischer_sachdaten_druck_createPDF();
				// archivieren und letztes Suchergebnis anzeigen
				$this->pdf_archivieren($this->formvars['chosen_layer_id'], $this->formvars['oid'], $result['pdf_file']);
			}
			$this->formvars['oid'] = '';
			$this->formvars['no_output'] = false;
			$this->formvars['anzahl'] = 10;
			$this->GenerischeSuche_Suchen();
		}
		else {
			$result = $this->generischer_sachdaten_druck_createPDF();
			$this->outputfile = basename($result['pdf_file']);
			// nur pdf ausgeben
			$this->mime_type='pdf';
			$this->output();
		}
	}


	/**
	 * Erzeugt ein PDF-Dokument oder schreibt es fort.
	 * Abgelegt wird das Dokument wenn $output true ist im document_path des Layers $document_path mit dem Namen den die Funktion createDataPDF zurückliefert
	 * Übergebene formvars Daten:
	 * go=generischer_sachdaten_druck_Drucken
	 * aktivesLayout=31
	 * chosen_layer_id=743
	 * @param Object $pdfobject Ein PDF-Objekt welches fortgesetzt werden soll. Wenn keines angegeben ist wird eine neues angelegt.
	 * @param int $offsetx
	 * @param int $offsety
	 * @param boolean $output Legt fest ob die PDF-Datei an den Client ausgegeben wird.
	 * @param boolean $append Legt fest ob der Druck angehängt werden soll oder von vorn begonnen wird.
	 * @return array String[] $return_values Array mit Werten für den pdf Datei Pfad inklusive namen ($output = true) und eimem Wert für y ($output = false).
	 */
	function generischer_sachdaten_druck_createPDF($pdfobject = NULL, $offsetx = NULL, $offsety = NULL, $output = true, $append = false) {
		include_(CLASSPATH . 'datendrucklayout.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		$layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, NULL, false, true);
		$privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
		# Attribute laden
		$attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
		$query_parts = $mapDB->getQueryParts($layerset[0], $privileges);
		$return_values = array(
			'pdf_file' => '',
			'y' => 0
		);
		
		if ($this->qlayerset[0]['shape'] !== null) {
			# entweder gibt es schon ein Result
			$result = $this->qlayerset[0]['shape'];
		}
		else {
			if ($this->formvars['checkbox_names_' . $this->formvars['chosen_layer_id']] != '') {
				$checkbox_names = explode('|', $this->formvars['checkbox_names_' . $this->formvars['chosen_layer_id']]);
				# oder es wurden Checkboxen angehakt
				for ($i = 0; $i < count($checkbox_names); $i++) {
					if ($this->formvars[$checkbox_names[$i]] == 'on') {
						$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
						$oids[] = $element[3];
					}
				}
			}
			else {
				# oder eine einzelne oid übergeben
				$oids[] = $this->formvars['oid'];
			}
			# Daten abfragen
			$sql = "
				SELECT 
					" . $query_parts['select'] . "
				FROM 
					(SELECT " . $query_parts['query'] . ") as foo
				WHERE
					" . pg_quote($layerset[0]['maintable'] . "_oid") . " IN ('" . implode("','", $oids ?: []) . "')
				" . ($this->formvars['orderby' . $this->formvars['chosen_layer_id']] != ''? 'ORDER BY ' . $this->formvars['orderby' . $this->formvars['chosen_layer_id']] : '') . "
			";
			// echo "<br>SQL zur Abfrage von Datensätzen für den Sachdatendruck: " . $sql;
			$this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :", 4);
			$ret = $layerdb->execSQL($sql, 4, 1);
			if (!$ret[0]) {
				while ($rs = pg_fetch_array($ret[1])) {
					$result[] = $rs;
				}
			}
		}
		# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, $result, true, $this->Stelle->id);
		$this->attributes = $attributes;
		# aktives Layout abfragen
		if ($this->formvars['aktivesLayout'] != '') {
			$ddl->selectedlayout = $ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, NULL);
			# PDF erzeugen
			$return_values = $ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $ddl->selectedlayout[0], $result, $this->Stelle, $this->user, NULL, $this->formvars['record_paging'], $output, $append);
		}
		return $return_values;
	}

	/**
	 * Save $pdf_file to document path of Layer with $layer_id and write the document
	 * information in attribute of dataset with $oids
	 * @param integer $layer_id The layer_id.
	 * @param array $oids
	 * @param string $pdf_file
	 */
	function pdf_archivieren($layer_id, $oid, $pdf_file) {
		# Dateiname für Speicherung im Dokumentpfad ermitteln
		$document_file = basename($pdf_file);
		$pathinfo = pathinfo($document_file);
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerset = $this->user->rolle->getLayer($layer_id);
		$layerdb = $mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$layerset[0]['attributes'] = $mapDB->read_layer_attributes($layer_id, $layerdb, NULL, false, true);
		# Attributname ermitteln in dem der Attributwert eingetragen werden soll
		for ($i = 0; $i < count($layerset[0]['attributes']['name']); $i++) {
			if ($layerset[0]['attributes']['form_element_type'][$i] == 'Dokument') {
				$dokument_attribute = $layerset[0]['attributes']['name'][$i];
				$dokument_is_array = substr($layerset[0]['attributes']['type'][$i], 0, 1) == '_';
				$doc_paths = $mapDB->getDocument_Path($layerset[0]['document_path'], $layerset[0]['document_url'], $layerset[0]['attributes']['options'][$i], [], [], $layerdb, $pathinfo['filename']);
				$document_path = dirname($doc_paths['doc_path'] . 'x') . '/';
				break;
			}
		}

		# Wert für den Eintrag in Dokument-Attribut ermitteln
		$attribute_value = $document_path . $document_file . '&original_name=' . $document_file;

		# Datei von tmp in das Ziel verschieben
		if (!is_dir($document_path)) {
			mkdir($document_path, 0755);
		}
		$thumb = get_thumb_from_name($document_path . $document_file);
		if (file_exists($thumb)) {
			unlink($thumb);
		}
		rename($pdf_file, $document_path . $document_file);

		$array_add = ($this->formvars['prepend'] == 1? "array_prepend('" . $attribute_value . "', " . $dokument_attribute . ")" : "array_append(" . $dokument_attribute . ", '" . $attribute_value . "')");

		# Wert in Attribut eintragen
		$sql = "
			UPDATE
				" .  $layerset[0]['schema'] . '.' . pg_quote($layerset[0]['maintable']) . "
			SET
				" . $dokument_attribute . " = " . ($dokument_is_array ? $array_add : "'" . $attribute_value . "'") . "
			WHERE
				" . pg_quote($layerset[0]['oid']) . " = " . quote($oid) . "
		";
		#echo '<p>Sql zum Update des Dokumentattributes:<br>' . $sql;
		$ret = $layerdb->execSQL($sql, 4, 1);
		$this->last_query = $this->user->rolle->get_last_query();
		if (!$ret['success']) {
			$this->add_message('error', $ret[2]);
		}
		else {
			$this->add_message('notice', 'PDF-Dokument erfolgreich erzeugt und hinzugefügt.');
		}
	}

	function layer_charts_Anzeigen() {
		include_once(CLASSPATH . 'Layer.php');
		$this->layer = Layer::find_by_id($this, $this->formvars['layer_id']);
		$this->main = 'layer_chart_list.php';
		$this->output();
	}

	function layer_chart_Editor() {
		include_once(CLASSPATH . 'Layer.php');

		if ($this->formvars['id'] != '') {
			# edit
			$this->layer_chart = LayerChart::find_by_id($this, $this->formvars['id']);
			$this->formvars['layer_id'] = $this->layer_chart->get('layer_id');
		}
		else {
			# new
			$this->layer_chart = new LayerChart($this);
			$this->layer_chart->setKeysFromTable();
			$this->layer_chart->set('layer_id', $this->formvars['layer_id']);
		}
		$this->layer = Layer::find_by_id($this, $this->formvars['layer_id']);
		$this->main = 'layer_chart_formular.php';
		$this->output();
	}

	function layer_chart_Speichern($chart) {
		include(LAYOUTPATH . 'languages/layer_chart_' . rolle::$language . '.php');
		$chart->data = formvars_strip($this->formvars, array('id', 'layer_id', 'title', 'type', 'aggregate_function', 'value_attribute_label', 'value_attribute_name', 'label_attribute_name', 'beschreibung', 'breite'), 'keep');
		$results = $chart->validate();
		if (empty($results)) {
			if ($chart->get_id() == '') {
				$results = $chart->create();
				if ($results[0]['success']) {
					$results[0]['msg'] = 	$strLayerChartSaveSuccessMsg;
				}
			}
			else {
				$results = $chart->update();
				if ($results[0]['success']) {
					$results[0]['msg'] = $strLayerChartUpdateSuccessMsg;
				}
			}
		}
		else {

			$results = array_map(
				function ($a) {
					return array(
						'type' => $a['type'],
						'err_msg' => $a['msg']
					);
				},
				$results
			);
		}

		return $results[0];
	}

	function layer_chart_Loeschen() {
		if ($this->formvars['id'] != '') {
			$chart = LayerChart::find_by_id($this, $this->formvars['id']);
			$response = $chart->delete();
		}
		else {
			$response = array(
				'success' => false,
				'msg' => $strLayerChartMissingLayerId,
				'err_msg' => ''
			);
		}
		return $response;
	}

	function generisches_sachdaten_diagramm($width, $datei = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		$path = $layerset[0]['pfad'];
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $layerset[0]['attributes'] = $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);

		$query_parts = $mapDB->getQueryParts($layerset[0], $privileges);

    $maximum = 0;
    $minimum = 0;
    $maxlabelwidth = 0;
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true, $this->Stelle->id);
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
		$sql = "
			SELECT 	
				" . $query_parts['select'] . "
			FROM
				(SELECT " . $query_parts['query'] . ") as foo
			WHERE
				true";
		if ($this->formvars['all'] != 'true') {
			for ($i = 0; $i < count($checkbox_names); $i++) {
				if ($this->formvars[$checkbox_names[$i]] == 'on') {
					$element = explode(';', $checkbox_names[$i]); # check;table_alias;table;oid
					$oids .= $element[3] . ', ';
				}
			}
			$sql .= " AND " . pg_quote($layerset[0]['maintable'] . '_oid') . " IN (" . $oids . ')';
		}
		if ($this->formvars['orderby'.$this->formvars['chosen_layer_id']] != ''){
			$sql .= ' ORDER BY ' . replace_semicolon($this->formvars['orderby' . $this->formvars['chosen_layer_id']]);
		}
		#echo $sql.'<br><br>';
		$this->debug->write("<p>file:kvwmap class:generisches_sachdaten_diagramm :",4);
		$ret = $layerdb->execSQL($sql,4, 1);
		if (!$ret[0]) {
			while ($rs=pg_fetch_array($ret[1])) {
				$result[] = $rs;
				if ($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] > $maximum){
					$maximum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
				}
				if ($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] < $minimum){
					$minimum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
				}
				$summe += $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
				$maxlabelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $rs[$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]]);
				if ($maxlabelwidth < $maxlabelbox[2] - $maxlabelbox[0]){
					$maxlabelwidth = $maxlabelbox[2] - $maxlabelbox[0];
				}
			}
		}
		# defining colors
		$colors['white'] =			 Array(255, 255, 255);
		$colors['yellowLight'] = Array(255, 255, 200);
		$colors['red'] =				 Array(255,  50,  50);
		$colors['blue'] =				 Array( 80,  80, 255);
		$colors['black'] =  		 Array(  0,   0,   0);

		$result_colors = read_colors($this->database);		# Farben fürs Kreisdiagramm
    for ($i = 0; $i < count($result_colors); $i++) {
    	$piecolors[] = Array($result_colors[$i]['red'], $result_colors[$i]['green'], $result_colors[$i]['blue']);
    }
		$index = $attributes['indizes'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];

    switch($this->formvars['charttype_'.$this->formvars['chosen_layer_id']]){
    	case 'bar' : {
     	  $image = imagecreatetruecolor(2380, 60 * count($result)+170);
		    $chartColors = allocateImageColors($image, $colors);

        $backGroundColor = $chartColors['yellowLight'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 30;
				$barValueColor = $chartColors['black'];
				$barNegativValueColor = $chartColors['red'];
				$barWidth = 20;
    	  $barColor = $chartColors['blue'];
		    $barBorderWidth = 5;
		    $barBorderColor = $chartColors['black'];

		    $y = 90;
			  imagefilledrectangle($image, 0, 0, 2380, 60*count($result)+170, $backGroundColor);
				$label = $value = $attributes['alias'][$index] ?: $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    imagettftext($image, 36, 0, 70, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    if($maxlabelwidth < $labelbox[2] - $labelbox[0]){
    			$maxlabelwidth = $labelbox[2] - $labelbox[0];
        }
		    imagettftext($image, 36, 0, 130+$maxlabelwidth, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $value);
		    $maxbarwidth = 2000-$maxlabelwidth;
		    $y = 110;

		    $maximum = $maximum - $minimum;		# wenn negative Werte dabei sind, -minimum + maximum addieren

		    for($i = 0; $i < count($result); $i++){
					$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
					$y = $y+60;
					imagettftext($image, $barLabelSize, 0, 70, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					$xstart = 130+$maxlabelwidth-($maxbarwidth*$minimum/$maximum);																# Anfang des Rechtecks
					$xstop = 140+$maxlabelwidth-($maxbarwidth*$minimum/$maximum)+($maxbarwidth*$value/$maximum);	# Ende des Rechtecks
					if($xstart > $xstop){		# wenn negativ, dann vertauschen
						$help = $xstart;
						$xstart = $xstop;
						$xstop = $help;
					}
					if ($barBorderWidth > 0) {
					  imagefilledrectangle($image, $xstart-$barBorderWidth,  $y-$barWidth-$barBorderWidth, $xstop+$barBorderWidth,  $y+$barBorderWidth, $barBorderColor);
					}
					imagefilledrectangle($image, $xstart,  $y-$barWidth, $xstop,  $y, $barColor);
					if (intval($value) < 0) {
					  $useBarValueColor = $barNegativValueColor;
					}
					else {
					  $useBarValueColor = $barValueColor;
					}
					imagettftext($image, $barValueSize, 0, 20+$xstop, $y, $useBarValueColor, dirname(FONTSET).'/arial.ttf', $value);
		    }
    	}break;

    	case 'mirrorbar' : {
    		$image = imagecreatetruecolor(2380, 15*count($result)+230);
		    $chartColors = allocateImageColors($image, $colors);

        $backgroundColor = $chartColors['yellowLight'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 20;
				$barValueColor = $chartColors['black'];
				$leftBarWidth = 15;
    	  $leftBarColor = $chartColors['red'];
		    $leftBarBorderWidth = 3;
		    $leftBarBorderColor = $chartColors['black'];
    	  $rightBarWidth = 15;
    	  $rightBarColor = $chartColors['blue'];
		    $rightBarBorderWidth = 3;
		    $rightBarBorderColor = $chartColors['black'];

		    $y = 90;
			  imagefilledrectangle($image, 0, 0, 2380, 15*count($result)+230, $backgroundColor);
		    $label = $value = $attributes['alias'][$index] ?: $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    $maxbarwidth = (1600-$maxlabelwidth)/2;

		    $scalewidth = (1900-$maxlabelwidth)/2;

		    # -------- Überschrift Mittelachse -----------
		    imagettftext($image, 36, 0, 1190-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);

		    # -------- Überschriften der Bars -------------
		     # rechts
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $result[0][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]].'label1');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, 1785-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $result[0][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]]);
		     # links
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $result[1][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]].'label2');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, 595-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $result[1][$this->formvars['chartsplit_'.$this->formvars['chosen_layer_id']]]);

		    $y = 120;
		    # -------------- Gitter ----------------
		    $linewidth = 1;
		    $startright = 1190+130+$maxlabelwidth/2;
		    $endleft = 1190-$maxlabelwidth/2-130;
		    $bottom = 15*count($result)+140;
		    $top = 120;
				$maxscale = str_pad(intval(substr($maximum, 0, 1))+1, strlen($maximum), '0');
					# rechts
		    imagefilledrectangle($image, $startright,  $bottom, $startright+$scalewidth,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright,  $top, $startright+$scalewidth,  $top+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright,  $top, $startright+$linewidth,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright+$scalewidth-$linewidth/2,  $top, $startright+$scalewidth+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $startright+$scalewidth/2-$linewidth/2,  $top, $startright+$scalewidth/2+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    	# links
		    imagefilledrectangle($image, $endleft-$scalewidth,  $bottom, $endleft,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth,  $top, $endleft,  $top+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$linewidth,  $top, $endleft,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth-$linewidth/2,  $top, $endleft-$scalewidth+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);
		    imagefilledrectangle($image, $endleft-$scalewidth/2-$linewidth/2,  $top, $endleft-$scalewidth/2+$linewidth/2,  $bottom+$linewidth, $chartColors['black']);

		    # ------------- Skala --------------
		    	# 0
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', '0');
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', '0');
		    imagettftext($image, 36, 0, $endleft-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', '0');
		    	# Max
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $maxscale);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright+$scalewidth-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale);
		    imagettftext($image, 36, 0, $endleft-$scalewidth-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale);
		    	# Mitte
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $maxscale/2);
		    $labelwidth = $labelbox[2] - $labelbox[0];
		    imagettftext($image, 36, 0, $startright+$scalewidth/2-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale/2);
		    imagettftext($image, 36, 0, $endleft-$scalewidth/2-$labelwidth/2, $bottom+60, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $maxscale/2);

		    # ----------------- Balken ----------------
		    imagesetthickness($image, 12);
		    for($i = 0; $i < count($result); $i=$i+2){
		    	$y = $y+30;

		    	 # Label Mittelachse für jeden zweiten Wert
		    	if($i == 0 or (floor(($i-2)/4)*2 == ($i-2)/2)) {

						$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
						$labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label.'label1');
						$labelwidth = $labelbox[2] - $labelbox[0];
						imagettftext($image, $barLabelSize, 0, 1210-$labelwidth/2, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					}

					 # rechts
					$value = $result[$i+1][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					if ($leftBarWidth > 0) {
					  imagefilledrectangle($image, $endleft-$scalewidth*$value/$maxscale-$leftBarBorderWidth,  $y-14-$leftBarBorderWidth, $endleft,  $y+$leftBarBorderWidth, $leftBarBorderColor);
					}
					imagefilledrectangle($image, $endleft-$scalewidth*$value/$maxscale,  $y-14, $endleft,  $y, $leftBarColor);

           # links
					$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					if ($rightBarWidth > 0) {
					  imagefilledrectangle($image, $startright,  $y-14-$rightBarBorderWidth, $startright+$scalewidth*$value/$maxscale+$rightBarBorderWidth,  $y+$rightBarBorderWidth, $rightBarBorderColor);
					}
					imagefilledrectangle($image, $startright,  $y-14, $startright+$scalewidth*$value/$maxscale,  $y, $rightBarColor);
					# -------------- Linie ----------------
					if($this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']] != ''){
							# rechts
						$value = $result[$i+1][$this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']]];
						if($x_left) imageline($image , $endleft-$scalewidth*$value/$maxscale, $y-7, $x_left, $y_left, $chartColors['black']);
						$x_left = $endleft-$scalewidth*$value/$maxscale;
						$y_left = $y-7;
						  # links
						$value = $result[$i][$this->formvars['chartcomparison_'.$this->formvars['chosen_layer_id']]];
						if($x_right) imageline($image , $startright+$scalewidth*$value/$maxscale, $y-7, $x_right, $y_right, $chartColors['black']);
						$x_right = $startright+$scalewidth*$value/$maxscale;
						$y_right = $y-7;
					}
		    }
    	}break;

    	case 'circle' : {
     	  $image = imagecreatetruecolor(2000, 2000);
		    $chartColors = allocateImageColors($image, $colors);
		    $pieColors = allocateImageColors($image, $piecolors);

        $backGroundColor = $chartColors['white'];
				$barLabelSize = 30;
				$barLabelColor = $chartColors['black'];
				$barValueSize = 30;
				$barValueColor = $chartColors['black'];
				$barNegativValueColor = $chartColors['red'];
				$barWidth = 40;
    	  $barColor = $chartColors['blue'];
		    $barBorderWidth = 4;
		    $barBorderColor = $chartColors['black'];

		    $y = 120;
			  imagefilledrectangle($image, 0, 0, 2000, 2000, $backGroundColor);
			  #Layername als Überschrift
			  $labelbox = imagettfbbox(50, 0, dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $labelwidth = $labelbox[2] - $labelbox[0];
			  imagettftext($image, 50, 0, 1000-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $y = $y + 120;
		    $label = $value = $attributes['alias'][$index] ?: $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    imagettftext($image, 36, 0, 70, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $label);
		    $labelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $label);
		    if($maxlabelwidth < $labelbox[2] - $labelbox[0]){
    			$maxlabelwidth = $labelbox[2] - $labelbox[0];
        }
		    imagettftext($image, 36, 0, 190+$maxlabelwidth, $y, $chartColors['black'], dirname(FONTSET).'/arial.ttf', $value);
		    $maxbarwidth = 2000-$maxlabelwidth;
		    $y = $y + 20;

		    $maximum = $maximum - $minimum;		# wenn negative Werte dabei sind, -minimum + maximum addieren

		    $endwinkel = 0;
		    for($i = 0; $i < count($result); $i++){
		    	$value = $result[$i][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
					$label = $result[$i][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
					$y = $y+60;
					if ($barBorderWidth > 0) {
					  imagefilledrectangle($image, 70-$barBorderWidth,  $y-$barWidth-$barBorderWidth, 110+$barBorderWidth,  $y+$barBorderWidth, $barBorderColor);
					}
					imagefilledrectangle($image, 70,  $y-$barWidth, 110,  $y, $pieColors[$i]);
					imagettftext($image, $barLabelSize, 0, 130, $y, $barLabelColor, dirname(FONTSET).'/arial.ttf', $label);
					$xstop = 190+$maxlabelwidth;
					imagettftext($image, $barValueSize, 0, $xstop, $y, $useBarValueColor, dirname(FONTSET).'/arial.ttf', $value);
					#Piechart
					$offset = count($result)*60+80;
					$startwinkel = $endwinkel;		# das nächste Stück fängt da an, wo das letzte aufhörte
					$endwinkel = $startwinkel + (360*$value/$summe);
					imagesetthickness($image, $barBorderWidth);
					imagefilledarc($image, 1000, (2000-$offset)/2+$offset, 1700-$offset, 1700-$offset, $startwinkel, $endwinkel, $pieColors[$i], IMG_ARC_PIE);
					imagefilledarc($image, 1000, (2000-$offset)/2+$offset, 1700-$offset, 1700-$offset, $startwinkel, $endwinkel, $barValueColor, IMG_ARC_PIE+IMG_ARC_NOFILL+IMG_ARC_EDGED);
		    }
    	}break;
    }

    $imagewidth = imagesx($image);
  	$imageheight = imagesy($image);
		$height = $imageheight * $width/$imagewidth;
  	$finalimage = imagecreatetruecolor($width, $height);
  	imagecopyresampled($finalimage, $image,0, 0, 0, 0, $width, $height, $imagewidth, $imageheight);

    //$imagename = rand(0, 1000000).'.png';
    //imagepng($finalimage, IMAGEPATH.$imagename);
    if($datei == NULL){
    	ob_end_clean();
    	ob_start("output_handler");
    }
    #ImagePNG($finalimage);
    #echo $datei;
    ImageJPEG($finalimage, $datei);
    //return TEMPPATH_REL.$imagename;
    //$this->output();
	}

  function TIFExport(){
    $this->loadMap('DataBase');
    $breite = $this->map->extent->maxx - $this->map->extent->minx;
    $this->formvars['resolution'] = $breite/$this->map->width;
    $this->titel='TIF-Export';
    $this->main='tif_export.php';
    $this->output();
  }

  function TIFExport_erzeugen(){
		include_(CLASSPATH.'tif.php');
    $this->loadMap('DataBase');
    $this->tif = new tif($this->map, $this->formvars['resolution']);
    $this->map = $this->tif->setmap();
    $this->drawMap(true);
    $this->tif->create_tif($this->img['hauptkarte']);
    $this->tif->create_tfw();
    $this->titel='TIF-Export';
    $this->main='tif_export.php';
    $this->output();
  }

	function create_point_rollenlayer(){
    $this->main='create_point_rollenlayer.php';
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
	}

	function create_point_rollenlayer_load(){
		$this->main='create_point_rollenlayer.php';
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$this->data_import_export->pointlist = $this->data_import_export->load_custom_pointlist($this->user);
    $this->output();
	}

	function create_point_rollenlayer_import(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->process_import_file(NULL, NULL, 1, $this->Stelle, $this->user, $this->pgdatabase, $this->formvars['epsg'], 'point', $this->formvars);
		if ($layer_id != NULL) {
			$this->loadMap('DataBase');
			$this->zoom_to_max_layer_extent($layer_id);
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->drawMap();
			$this->saveMap('');
			$this->output();
		}
		else {
			$this->create_point_rollenlayer();
		}    
	}

  function shp_import_speichern(){
		include_once (CLASSPATH.'data_import_export.php');
    $this->titel='Shape-Import';
    $this->main='shape_import.php';
    $this->data_import_export = new data_import_export();
    $this->data_import_export->shp_import_speichern($this->formvars, $this->pgdatabase);
    $this->output();
  }

  function shp_import(){
		include_once (CLASSPATH.'data_import_export.php');
    $this->titel='Shape-Import';
    $this->main='shape_import.php';
		$this->data_import_export = new data_import_export();
    $this->data_import_export->shp_import($this->formvars, $this->pgdatabase);
    $this->output();
  }

	function geojson_import(){
    $this->titel='GeoJSON-Import';
    $this->main='geojson_import.php';
    $this->output();
  }

	function geojson_import_importieren(){
		include_once (CLASSPATH.'data_import_export.php');
    $this->titel='GeoJSON-Import';
    $this->main='geojson_import.php';
		$this->data_import_export = new data_import_export();
    $this->result = $this->data_import_export->import_geojson($this->pgdatabase, $this->formvars['schema_name'], $this->formvars['table_name']);
		if($this->result[0]['error'] != '')$this->add_message('error', $this->result[0]['error']);
    $this->output();
  }

	function daten_import(){
		$this->main = 'data_import.php';
		if ($this->formvars['chosen_layer_id'] != NULL) {
			$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
			if (!$this->user->layer_data_import_allowed OR $layerset[0]['privileg'] == 0) {
				$this->add_message('error', 'Der Daten-Import in diesen Layer ist nicht erlaubt.');
			}
			$this->formvars['after_import_action'] = 'import_into_layer';
		}
		exec('rm ' . UPLOADPATH . $this->user->id . '/*');
		$this->output();
	}

	function daten_import_upload() {
		$_files = $_FILES;
		if ($this->formvars['upload_id'] !== '') {
			if ($_files['uploadfile']['name']) {
				$user_upload_folder = UPLOADPATH . $this->user->id . '/';
				@mkdir($user_upload_folder);
				$nachDatei = $user_upload_folder . $_files['uploadfile']['name'];
				if (move_uploaded_file($_files['uploadfile']['tmp_name'], $nachDatei)) {
					$file_number = 1;
					$dateityp = strtolower(array_pop(explode('.', $nachDatei)));
					if ($dateityp == 'zip') {
						$files = unzip($nachDatei, false, false, true);
						foreach ($files as $file) {
							$dateityp = strtolower(array_pop(explode('.', $file)));
							if (!in_array($dateityp, array('dbf', 'shx'))) { // damit gezippte Shapes nur einmal bearbeitet werden
								$this->daten_import_process($this->formvars['upload_id'], $file_number, $file, NULL, $this->formvars['after_import_action'], $this->formvars['chosen_layer_id']);
								$file_number++;
							}
						}
					}
					else {
						$this->daten_import_process($this->formvars['upload_id'], $file_number, $_files['uploadfile']['name'], NULL, $this->formvars['after_import_action'], $this->formvars['chosen_layer_id']);
					}
					echo '█startNextUpload();';
				}
			}
		}
	}

	function daten_import_process($upload_id, $file_number, $filename, $epsg, $after_import_action, $chosen_layer_id = NULL) {
		sanitize($filename, 'text');
		include_once (CLASSPATH . 'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$user_upload_folder = UPLOADPATH . $this->user->id . '/';
		$layer_id = $this->data_import_export->process_import_file($upload_id, $file_number, $user_upload_folder . $filename, $this->Stelle, $this->user, $this->pgdatabase, $epsg, NULL, NULL);
		$filetype = array_pop(explode('.', $filename));
		if ($layer_id != NULL) {
			#echo $filename . ' importiert mit layer_id: ' . $layer_id;
			switch ($after_import_action) {
				case 'import_into_layer' : {
					if (!in_array($filetype, array('tiff', 'tif', 'geotif'))) {
						echo "<script type=\"javascript\">location = 'index.php?go=import_rollenlayer_into_layer&rollenlayer_id=" . -$layer_id . "&layer_id=" . $chosen_layer_id . "';</script>";
					}
				} break;
				
				case 'use_geometry' : {
					if (!in_array($filetype, array('tiff', 'tif', 'geotif'))) {
						echo '&nbsp;=>&nbsp;<a href="javascript:void(0);" onclick="enclosingForm.last_doing.value=\'add_geom\';enclosingForm.secondpoly.value=\'true\';ahah(\'index.php\', \'go=spatial_processing&path1=\'+enclosingForm.pathwkt.value+\'&operation=add_geometry&resulttype=svgwkt&geom_from_layer='.$layer_id.'&code2execute=zoom_to_max_layer_extent('.$layer_id.');\', new Array(enclosingForm.result, \'\', \'\'), new Array(\'setvalue\', \'execute_function\', \'execute_function\'));">Geometrie&nbsp;übernehmen</a>';
					}
				} break;

				default : {
					if (in_array($filetype, array('csv'))) {
						echo '&nbsp;=>&nbsp;<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id='.$layer_id.'">Datensätze&nbsp;anzeigen</a><br>';
					}
					else {
						echo '&nbsp;=>&nbsp;<a href="index.php?go=zoom_to_max_layer_extent&layer_id='.$layer_id.'">Zoom&nbsp;auf&nbsp;Layer</a><br>';
					}
				}
			}
		}
	}
	
	function import_rollenlayer_into_layer(){
		$this->layer = $this->user->rolle->getLayer($this->formvars['layer_id']);
		if (!$this->user->layer_data_import_allowed OR $this->layer[0]['privileg'] == 0) {
			$this->add_message('error', 'Der Daten-Import in diesen Layer ist nicht erlaubt.');
		}
		else {
			$this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$this->layerdb = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
			$this->rollenlayer = $this->mapDB->read_RollenLayer($this->formvars['rollenlayer_id'], NULL);
			if (!empty($this->rollenlayer)) {
				$this->pgdatabase->schema = ($this->rollenlayer[0]['Typ'] == 'import'? CUSTOM_SHAPE_SCHEMA : 'public');
				$this->rollenlayer_attributes = $this->mapDB->load_attributes($this->pgdatabase, $this->rollenlayer[0]['query']);
				$this->layer_attributes = $this->mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL, true, false, false);
			}
		}
		$this->main = 'import_rollenlayer_into_layer.php';
	}
	
	function import_rollenlayer_into_layer_importieren(){
		$this->import_rollenlayer_into_layer();
		foreach ($this->formvars['rollenlayer_attributes'] as &$attribute) {
			if ($attribute == $this->rollenlayer_attributes['the_geom']) {
				$attribute = 'st_transform(' . $attribute . ', ' . $this->layer[0]['epsg_code'] . ')';
			}
			else {
				$attribute = pg_quote($attribute);
			}
		}
		$sql = '
			INSERT INTO 
				' . $this->layer[0]['maintable'] . '
				("' . implode('", "', $this->formvars['layer_attributes']) . '")
			SELECT 
				' . implode(', ', array_slice($this->formvars['rollenlayer_attributes'], 0, count($this->formvars['layer_attributes']))) . '
			FROM
				' . $this->rollenlayer_attributes[0]['schema_name'] . '.' . $this->rollenlayer_attributes[0]['table_name'];
		$ret = $this->layerdb->execSQL($sql,4, 0);
		if (!$ret[0]){
			if ($this->formvars['remove_rollenlayer']) {
				$this->mapDB->deleteRollenlayer($this->formvars['rollenlayer_id']);
			}
			$this->add_message('info', 'Import erfolgreich');
			$this->neuLaden();
			$this->saveMap('');
			$currenttime=date('Y-m-d H:i:s',time());
			$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
			$this->main='map.php';
			$this->drawMap();
		}
		else {
			$this->add_message('error', 'Import fehlgeschlagen!');
		}
	}

	function daten_export() {
		$this->sanitize([
			'selected_rollenlayer_id' => 'int',
			'setting_name' => 'text',
			'export_format' => 'text',
			'epsg' => 'int',
			'with_metadata_document' => 'bool',
			'export_groupnames' => 'bool',
			'download_documents' => 'bool',
			'newpathwkt' => 'text',
			'within' => 'bool',
			'singlegeom' => 'bool'
		]);
		include_once (CLASSPATH . 'data_import_export.php');
		if ($this->formvars['chosen_layer_id'] != '') {
			$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];		# aus der Sachdatenanzeige des GLE
		}
		$this->main = 'data_export.php';
		$saved_scale = $this->reduce_mapwidth(10);
		$this->loadMap('DataBase');
		if ($saved_scale != NULL) {
			# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
			$this->scaleMap($saved_scale);
		}
		$this->epsg_codes = read_epsg_codes($this->pgdatabase);
		$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true, true);
		$this->data_import_export = new data_import_export();
		if (defined('LAYERNAME_FLURSTUECKE') AND !$this->formvars['geom_from_layer']) {
			$layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
			$this->formvars['geom_from_layer'] = $layerset[0]['Layer_ID'];
		}
		###################### über Checkboxen aus der Sachdatenanzeige des GLE ausgewählt ###############
		if ($this->formvars['all'] == '') {
			$anzahl = 0;
			$checkbox_names = explode('|', $this->formvars['checkbox_names_' . $this->formvars['chosen_layer_id']]);
			# Daten abfragen
			$element = explode(';', $checkbox_names[0]);   #  check;table_alias;table;oid
			$where = " WHERE " . pg_quote($element[2]."_oid") . " IN (";
			for ($i = 0; $i < count($checkbox_names); $i++) {
				if ($this->formvars[$checkbox_names[$i]] == 'on') {
					$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
					$where = $where . "'" . $element[3] . "',";
					$anzahl++;
				}
			}
			$where = substr($where, 0, -1).')';
			if ($anzahl > 0) {
				$this->formvars['sql_' . $this->formvars['selected_layer_id']] = $where . $orderby;
				$this->formvars['anzahl'] = $anzahl;
			}
		}
		####################################################################################################
		if (
			$this->formvars['go_plus'] != '' OR
			$this->formvars['export_setting'] != '' OR
			$this->formvars['CMD'] == 'Full_Extent' OR
			$this->formvars['CMD'] == 'recentre' OR
			$this->formvars['CMD'] == 'zoomin' OR
			$this->formvars['CMD'] == 'zoomout' OR
			$this->formvars['CMD'] == 'previous' OR
			$this->formvars['CMD'] == 'next'
		) {
			$this->navMap($this->formvars['CMD']);
		}
		else {
			$this->formvars['load'] = true;
		}

		$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $user->id, NULL, NULL, NULL, NULL, false, true);
		if ($this->layerdaten['Gruppe']) {
			$this->layergruppen['ID'] = array_values(array_unique($this->layerdaten['Gruppe']));
		}
		$this->layergruppen = $this->mapDB->get_Groups($this->layergruppen); # Gruppen mit Pfaden versehen
		# wenn Gruppe ausgewählt, Einschränkung auf Layer dieser Gruppe
		if (value_of($this->formvars, 'selected_group_id') AND $this->formvars['selected_layer_id'] == '') {
			$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, $this->formvars['selected_group_id']);
		}

		# Export-Einstellungen speichern
		if ($this->formvars['go_plus'] == 'Einstellungen_speichern') {
			$this->user->rolle->saveExportSettings($this->formvars);
		}
		if ($this->formvars['go_plus'] == 'Einstellungen_löschen') {
			$this->user->rolle->deleteExportSettings($this->formvars);
		}
		if ($this->formvars['selected_layer_id'] != '') {
			$this->layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			$layerdb = $this->mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
			$this->attributes = $this->mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
			# die Namen aller gespeicherten Export-Einstellungen dieser Rolle zu diesem Layer laden
			$this->export_settings = $this->user->rolle->getExportSettings($this->formvars['selected_layer_id']);
			if ($this->formvars['export_setting'] != '') {
				# die ausgewählte Export-Einstellung laden
				$this->selected_export_setting = $this->user->rolle->getExportSettings($this->formvars['selected_layer_id'], $this->formvars['export_setting']);
				$this->formvars['export_format'] = $this->selected_export_setting[0]['format'];
				$this->formvars['epsg'] = $this->selected_export_setting[0]['epsg'];
				$this->formvars['with_metadata_document'] = $this->selected_export_setting[0]['metadata'];
				$this->formvars['export_groupnames'] = $this->selected_export_setting[0]['groupnames'];
				$this->formvars['download_documents'] = $this->selected_export_setting[0]['documents'];
				if ($this->selected_export_setting[0]['geom'] != '') {
					$this->formvars['newpathwkt'] = $this->selected_export_setting[0]['geom'];
					$this->formvars['newpath'] = buildsvgpolygonfromwkt($this->formvars['newpathwkt']);
					$this->formvars['firstpoly'] = 'true';
				}
				else {
					$this->formvars['newpathwkt'] = NULL;
					$this->formvars['newpath'] = NULL;
				}
				$this->formvars['within'] = $this->selected_export_setting[0]['within'];
				$this->formvars['singlegeom'] = $this->selected_export_setting[0]['singlegeom'];
				$attributes = explode(',', $this->selected_export_setting[0]['attributes']);
				for ($i = 0; $i < count($this->attributes['name']); $i++) {
					$this->formvars['check_' . $this->attributes['name'][$i]] = 0;
				}
				foreach ($attributes as $attribute) {
					$this->formvars['check_' . $attribute] = 1;
				}
			}
		}

		if ($this->formvars['epsg'] == '') {
			# originäres System
			$this->formvars['epsg'] = $this->layerset[0]['epsg_code'];
		}
		$this->saveMap('');
		$currenttime = date('Y-m-d H:i:s',time());
		$this->user->rolle->setConsumeActivity($currenttime, 'getMap', $this->user->rolle->last_time_id);
		$this->drawMap();
		$this->output();
	}

	function daten_export_exportieren() {
		$this->sanitize([
			'selected_layer_id' => 'int',
			'layer_name' => 'text',
			'epsg' => 'int',
			'newpathwkt' => 'text',
			'precision' => 'int'
		]);
		$this->formvars['sql_' . $this->formvars['selected_layer_id']] = str_ireplace([';', 'union ', 'select '], '', $this->formvars['sql_' . $this->formvars['selected_layer_id']]);
		if (!(array_key_exists('selected_layer_id', $this->formvars) AND $this->formvars['selected_layer_id'] != '')) {
			$this->add_message('error', 'Es muss der Parameter selected_layer_id angegeben werden!');
			$this->loadMap('DataBase');
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->saveMap('');
			$this->drawMap();
			$this->output();
		}
		else {
			include_(CLASSPATH . 'data_import_export.php');
			$this->data_import_export = new data_import_export();
			// $this->formvars['filename'] = $this->data_import_export->export_exportieren($this->formvars, $this->Stelle, $this->user);
			$result = $this->data_import_export->export_exportieren($this->formvars, $this->Stelle, $this->user);
			if (!$result['success']) {
				$this->add_message('error', $result['msg']);
				$this->daten_export();
				return $result;
			}
			ob_end_clean();
			header('Content-type: ' . $result['contenttype']);
			header("Content-disposition:	attachment; filename=" . basename($result['exportfile']));
			#header("Content-Length: ".filesize($exportfile));			# hat bei großen Datenmengen dazu geführt, dass der Download abgeschnitten wird
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			readfile($result['exportfile']);
		}
	}

	function Attributeditor(){
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->titel='Attribut-Editor';
		$this->main='attribut_editor.php';
		$this->layerdaten = $mapdb->get_layers_of_type(MS_POSTGIS.', '.MS_WFS, 'Name');
		if($this->formvars['selected_layer_id']){
			$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL, true, false, false, false);
			$this->datatypes = $mapdb->get_datatypes([$this->formvars['selected_layer_id']]);
			$this->layer = $mapdb->get_Layer($this->formvars['selected_layer_id'], false);
		}
		if(value_of($this->formvars, 'selected_datatype_id')){
			$this->attributes = $mapdb->read_datatype_attributes($this->formvars['selected_layer_id'], $this->formvars['selected_datatype_id'], NULL, NULL, true, false, false);
		}
		$this->output();
	}

	/**
	* Function saves attributes of the selected and its duplicated layers
	* @param array $formvars The attributes including the selected_layer_id
	*/
	function save_layers_attributes($formvars) {
		$this->save_layer_attributes($formvars);
		foreach (Layer::find_by_duplicate_from_layer_id($this->database, $formvars['selected_layer_id']) AS $formvars['selected_layer_id']) {
			$this->save_layer_attributes($formvars);
		}
	}

	/**
	* Function saves attributes of the selected layer
	* @param array $formvars The attributes including the selected_layer_id
	*/
	function save_layer_attributes($formvars) {
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($formvars['selected_layer_id'], $layerdb, NULL, true, false, false, false);
		$mapdb->save_layer_attributes($this->attributes, $this->database, $formvars);
	}

	function Datentypattribute_speichern() {
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->attributes = $mapdb->read_datatype_attributes($this->formvars['selected_layer_id'], $this->formvars['selected_datatype_id'], NULL, NULL, true, false, false);
		$mapdb->save_datatype_attributes($this->attributes, $this->database, $this->formvars);
	}

	/**
	* Funktion speichert die Einstellungen der Attribute aus dem Attributeditor Form für
	* die Attribute von for_attributes_selected_layer_id. Die Einstellungen können von einem anderen Layer stammen.
	* Vor save_layer_attributes wird als selected_layer_id for_attributes_selected_layer_id genommen.
	* Damit werden die Attribute von dem Layer abgefragt, für die die Formularwerte übernommen werden sollen.
	* Nach dem Speichern wird der Attributeditor für den layer mit for_attributes_selected_layer_id geöffnet.
	*/
	function Attributeditor_takeover_attributes() {
		$from_layer_id = $this->formvars['selected_layer_id'];
		$to_layer_id = $this->formvars['for_attributes_selected_layer_id'];
		$this->formvars['selected_layer_id'] = $to_layer_id;
		$this->add_message('info', 'Einstellungen der Attribute von Layer ID: ' . $from_layer_id . ' für die Attribute des Layers ID: ' . $to_layer_id . ' übernommen.<br>Ordne Attribute ohne Einstellungen neu ein!');
		$this->save_layer_attributes($this->formvars);
		$this->Attributeditor();
	}

	function layer_attributes_privileges() {
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->titel = 'Layer-Rechteverwaltung';
		$this->main = 'attribut_privileges_form.php';
		include_once(CLASSPATH . 'FormObject.php');
		$this->layerdaten = $mapdb->get_layers_of_type(MS_POSTGIS . ',' . MS_WFS, 'Name');
		if ($this->formvars['selected_layer_id'] != '') {
			$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
			$this->stellen = $mapdb->get_stellen_from_layer($this->formvars['selected_layer_id']);
			$this->layer[0] = $mapdb->get_Layer($this->formvars['selected_layer_id']);
		}
		$this->output();
	}

	function write_layer_attributes2rolle() {
		$result = array();
		include_once(CLASSPATH . 'LayerAttributeRolleSetting.php');
		$larsObj = new LayerAttributeRolleSetting($this);
		$this->formvars['stelle_id'] = $this->Stelle->id;
		$this->formvars['user_id'] = $this->user->id;
		if (
			array_key_exists('sort_order', $this->formvars) AND
			array_key_exists('sort_other', $this->formvars) AND
			$this->formvars['sort_other'] == 'false'
		) {
			$larsObj->resetSortOrder($this->formvars['layer_id'], $this->formvars['stelle_id'], $this->formvars['user_id']);
		}
		$larsObj->setKeysFromFormvars($this->formvars);
		$larsObj->setData($this->formvars);
		$result = $larsObj->insert_or_update();
		header('Content-Type: application/json; charset=utf-8');
		echo utf8_decode(json_encode($result));
	}

	/**
	* Function saves attribute privileges of the selected and its duplicated layers
	* @param array $formvars The attribute privileges including the selected_layer_id
	*/
	function save_layers_attribute_privileges($formvars) {
		$this->save_layer_attribute_privileges($formvars);
		foreach (Layer::find_by_duplicate_from_layer_id($this->database, $formvars['selected_layer_id']) AS $formvars['selected_layer_id']) {
			$this->save_layer_attribute_privileges($formvars);
		}
	}

	/**
	* Function saves attribute privileges of the selected layer
	* @param array $formvars The attribute privileges including the selected_layer_id
	*/
	function save_layer_attribute_privileges($formvars) {
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($formvars['selected_layer_id'], $layerdb, NULL);
		if ($formvars['stelle'] != '' AND $formvars['selected_layer_id'] != '') {
			$stellen = explode('|', $formvars['stelle']);
			foreach ($stellen as $stelleid) {
				$stelle = new stelle($stelleid, $this->database);
				if ($formvars['use_parent_privileges' . $stelleid] == 1) {
					# wenn Eltern-Stellen für diesen Layer vorhanden, deren Rechte übernehmen
					$formvars['used_layer_parent_ids'] = $this->get_upper_parent_ids($formvars, [$stelleid]);
					$stelleid = $formvars['used_layer_parent_ids'][0];
				}
				$stelle->set_attributes_privileges($formvars, $this->attributes);
				$stelle->set_layer_privileges($formvars);
			}
		}
		elseif ($formvars['selected_layer_id'] != '') {
			$mapdb->set_default_layer_privileges($formvars, $this->attributes);
		}
	}

	/*
	* Funktion sucht nach den obersten Elternstellen 
	*/
	function get_upper_parent_ids($formvars, $parent_ids) {
		$upper_parents = [];
		foreach($parent_ids as $key => $parent_id) {
			if (!isset($formvars['privileg_' . $this->attributes['name'][0] .'_'. $parent_id])) {
				# oberste Elternstelle suchen
				$parent_stelle = new stelle($parent_id, $this->database);
				$layer = $parent_stelle->getLayer($formvars['selected_layer_id']);
				$upper_parents = array_merge($upper_parents, $this->get_upper_parent_ids($formvars, explode(',', $layer[0]['used_layer_parent_id'])));
				unset($parent_ids[$key]);
			}
		}
		return array_merge($parent_ids, $upper_parents);
	}

	/*
	* Funktion übernimmt alle Attributprivilegien von layer $this->formvars['from_layer_id']
	* auf Layer $this->formvars['to_layer_id'] wo die stelle_id und der attributname übereinstimmt
	*/
	function Attributeditor_takeover_layer_attributes_privileges() {
		# take over layer attributes privileges
		$sql = "
			UPDATE
				layer_attributes2stelle AS t2 INNER JOIN
				layer_attributes2stelle AS t1 ON t1.attributename = t2.attributename AND t1.stelle_id = t2.stelle_id
			SET
				t2.privileg = t1.privileg,
				t2.tooltip = t1.tooltip
			WHERE
				t1.layer_id = " . $this->formvars['from_layer_id'] . " AND
				t2.layer_id = " . $this->formvars['to_layer_id'] . "
		";
		#echo '<br>Sql zur Übernahme der Attributrechte: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:gui->Attributeditor_takeover_layer_attributes_privileges - Übernehmen der Stellen-Layerrechte von einem Layer zu einem anderen:<br>" . $sql, 4);
		$this->database->execSQL($sql,4, 1);
		$sql = "
		DELETE FROM 
			layer_attributes2stelle 
		WHERE 
			concat(stelle_id, ' ' , layer_id, ' ' , attributename) IN 
				(
					SELECT 
						concat(t2.stelle_id, ' ', t2.layer_id, ' ', t2.attributename) 
					FROM
						layer_attributes2stelle AS t2 
						LEFT JOIN	layer_attributes2stelle AS t1 ON 
							t1.attributename = t2.attributename AND 
							t1.stelle_id = t2.stelle_id AND 
							t1.layer_id = " . $this->formvars['from_layer_id'] . "
					WHERE
						t2.layer_id = " . $this->formvars['to_layer_id'] . " AND 
						t1.privileg IS NULL
				)
		";
		#echo '<br>Sql zur Übernahme der Attributrechte: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:gui->Attributeditor_takeover_layer_attributes_privileges - Übernehmen der Stellen-Layerrechte von einem Layer zu einem anderen:<br>" . $sql, 4);
		$this->database->execSQL($sql,4, 1);
	}

	/*
	* Funktion übernimmt alle Layerprivilegien von layer $this->formvars['from_layer_id']
	* auf Layer $this->formvars['to_layer_id'] wo die stelle_id übereinstimmt
	*/
	function Attributeditor_takeover_layer_privileges() {
		# take over layer privileges
		$sql = "
			UPDATE
				used_layer AS t2 INNER JOIN
				used_layer AS t1 ON t1.stelle_id = t2.stelle_id
			SET
				t2.privileg = t1.privileg,
				t2.export_privileg = t1.export_privileg
			WHERE
				t1.layer_id = " . $this->formvars['from_layer_id'] . " AND
				t2.layer_id = " . $this->formvars['to_layer_id'] . "
		";
		# echo '<br>Sql zur Übernahme der layer privileges von einem Layer zum anderen
		$this->debug->write("<p>file:stelle.php class:gui->Attributeditor_takeover_attributes_privileges - übernehmen der Layerrechte von einem Layer zu einem anderen:<br>".$sql,4);
		$this->database->execSQL($sql,4, 1);
	}

	/*
	* Funktion übernimmt alle Default-Layerattribute von layer $this->formvars['from_layer_id']
	* auf Layer $this->formvars['to_layer_id'] wo der Attributname (name) übereinstimmt.
	*/
	function Attributeditor_takeover_default_layer_attributes_privileges() {
		# take over default layer attributes
		$sql = "
			UPDATE
				layer_attributes AS t2 INNER JOIN
				layer_attributes AS t1 ON t1.name = t2.name
			SET
				t2.privileg = t1.privileg,
				t2.query_tooltip = t1.query_tooltip
			WHERE
				t1.layer_id = " . $this->formvars['from_layer_id'] . " AND
				t2.layer_id = " . $this->formvars['to_layer_id'] . "
		";
		#echo '<br>Sql zur Übernahme der default layer privileges von einem Layer auf einen anderen: ' . $sql;
		$this->debug->write("<p>file:users.php class:gui->Attributeditor_takeover_default_layer_attributes_privileges - Speichern der Default-Layerrechte der Attribute:<br>" . $sql,4);
		$this->database->execSQL($sql,4, 1);
	}

	/*
	* Funktion übernimmt alle Default-Layer Privilegien von layer $this->formvars['from_layer_id']
	* auf Layer $this->formvars['to_layer_id'] wo der Attributname (name) übereinstimmt.
	*/
	function Attributeditor_takeover_default_layer_privileges() {
		# take over default layer privileges
		$sql = "
			UPDATE
				layer AS t2,
				layer AS t1
			SET
				t2.privileg = t1.privileg,
				t2.export_privileg = t1.export_privileg
			WHERE
				t1.Layer_ID = " . $this->formvars['from_layer_id'] . " AND
				t2.Layer_ID = " . $this->formvars['to_layer_id'] . "
		";
		#echo '<br>Sql zur Übernahme der default layer attribute privileges von einem Layer auf einen anderen: ' . $sql;
		$this->debug->write("<p>file:users.php class:gui->Attributeditor_takeover_default_layer_privileges - Speichern der Default-Layerrechte:<br>" . $sql,4);
		$this->database->execSQL($sql,4, 1);
	}

	/**
	 * $Stelle ist das "alte" Stellenobjekt und $new_stelle das "neue" Stellenobjekt,
	 * denn falls die Stellen-ID geändert wurde, müssen alle Zuordnungen mit der alten ID gelöscht werden und mit der neuen ID zugewiesen werden
	 */
	function Stellenzuweisung($Stelle, $new_stelle, $menues, $functions, $frames,	$layouts,	$layer,	$selectedusers){
		# Menüs
		$Stelle->deleteMenue(0); // erst alle Menüs rausnehmen
		$new_stelle->addMenue($menues); // und dann hinzufügen, damit die Reihenfolge stimmt

		# Layer
		if($layer[0] != NULL) {
			$new_stelle->addLayer($layer, '', false); # Hinzufügen der Layer zur Stelle
		}

		# Funktionen
		$Stelle->removeFunctions();   // Entfernen aller Funktionen
		if($functions[0] != NULL){
			$new_stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
		}

		# Kartendruck-Layouts
		$this->document->removeFrames($Stelle->id);   // Entfernen aller Kartendruck-Layouts der Stelle
		if($frames[0] != NULL){
			for($i = 0; $i < count($frames); $i++){
				$this->document->add_frame2stelle($frames[$i], $new_stelle->id); # Hinzufügen der Kartendruck-Layouts zur Stelle
			}
		}

		# Datendruck-Layouts
		$this->ddl->removelayouts($Stelle->id);   // Entfernen aller Datendruck-Layouts der Stelle
		if ($layouts[0] != NULL){
			for ($i = 0; $i < count($layouts); $i++){
				$this->ddl->add_layout2stelle($layouts[$i], $new_stelle->id); # Hinzufügen der Datendruck-Layouts zur Stelle
			}
		}

		# Rolleneinstellungen
		for ($i = 0; $i < count($selectedusers); $i++) {
			$this->user->rolle->setRolle($selectedusers[$i], $new_stelle->id, $new_stelle->default_user_id);	# Hinzufügen einer neuen Rolle (selektierte User zur Stelle)
			$this->user->rolle->setMenue($selectedusers[$i], $new_stelle->id, $new_stelle->default_user_id);	# Hinzufügen der selektierten Obermenüs zur Rolle
			$this->user->rolle->setLayer($selectedusers[$i], $new_stelle->id, $new_stelle->default_user_id);	# Hinzufügen der Layer zur Rolle
			$this->user->rolle->setGroups($selectedusers[$i], $new_stelle->id, $new_stelle->default_user_id, $layer); 											# Hinzufügen der Layergruppen der selektierten Layer zur Rolle
			$this->user->rolle->setSavedLayersFromDefaultUser($selectedusers[$i], $new_stelle->id, $new_stelle->default_user_id);
			$this->selected_user = new user(NULL,$selectedusers[$i],$this->user->database);
			$this->selected_user->checkstelle();
		}
		// ToDo: Löschen der Einträge in u_menue2rolle, bei denen der Menüpunkt nicht mehr der Stelle zugeordnet ist

		# nicht mehr zugewiesene Layer entfernen
		$users= $Stelle->getUser();
		$stellenlayer = $Stelle->getLayers(NULL);
		$deletelayer = array();
		for($i = 0; $i < count($stellenlayer['ID']); $i++){
			$found = false;
			for($j = 0; $j < count($layer); $j++){
				if($layer[$j] == $stellenlayer['ID'][$i]){
					$found = true;
				}
			}
			if($found == false){
				$deletelayer[] = $stellenlayer['ID'][$i];
			}
		}
		if(count($deletelayer) > 0) {
			$Stelle->deleteLayer($deletelayer, $this->pgdatabase);
			for($i = 0; $i < count($deletelayer); $i++){
				$layerid = $deletelayer[$i];
				$layer_id = explode(',',$layerid);
				for($j = 0; $j < count($users['ID']); $j++){
					$this->user->rolle->deleteLayer($users['ID'][$j], array($Stelle->id), $layer_id);
					$this->user->rolle->updateGroups($users['ID'][$j],$Stelle->id, $layerid);
				}
			}
		}

		# Layerparameter aktualisieren
		$new_stelle->updateLayerParams();

		# nicht mehr zugewiesene Nutzer entfernen
		for($i = 0; $i < count($users['ID']); $i++){
			$found = false;
			for($j = 0; $j < count($selectedusers); $j++){
				if($selectedusers[$j] == $users['ID'][$i]){
					$found = true;
				}
			}
			if($found == false){
				$deleteuser[] = $users['ID'][$i];
			}
		}
		$anzdeleteuser = count_or_0($deleteuser);
		if ($anzdeleteuser > 0) {
			for($i=0; $i<$anzdeleteuser; $i++){
				$this->user->rolle->deleteRollen($deleteuser[$i], array($Stelle->id));
				$this->user->rolle->deleteMenue($deleteuser[$i], array($Stelle->id), 0);
				$this->user->rolle->deleteGroups($deleteuser[$i], array($Stelle->id));
				$this->user->rolle->deleteLayer($deleteuser[$i], array($Stelle->id), 0);
				$this->selected_user = new user('',$deleteuser[$i],$this->user->database);
				$this->selected_user->checkstelle();
			}
		}
		$this->update_layer_parameter_in_rollen($Stelle->id);
	}

	function dienstmetadaten_aendern() {
    $Stelle = new stelle($this->formvars['selected_stelle_id'], $this->user->database);		# das "alte" Stellenobjekt
    $Stelle->language = $this->Stelle->language;
    $Stelle->metadaten_aendern($this->formvars);
		if (
			count(
				array_map(
					function($message) {
						if ($message['type'] == 'error') return $message;
					},
					GUI::$messages
				)
			) == 0
		) {
			$this->add_message('notice', 'Daten der Stelle erfolgreich eingetragen!');
		}
		$this->Stelleneditor();
	}

  function stelle_aendern() {
  	$_files = $_FILES;
		include_(CLASSPATH . 'datendrucklayout.php');
		$this->ddl = new ddl($this->database, $this);
		$this->document = new Document($this->database);
		$results = array();
		$deleteuser = array();

    if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else {
      if ($_files['wappen']['name']){
        $this->formvars['wappen'] = $_files['wappen']['name'];
        $nachDatei = WWWROOT . APPLVERSION . WAPPENPATH . $_files['wappen']['name'];
        if (move_uploaded_file($_files['wappen']['tmp_name'], $nachDatei)) {
            #echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      $Stelle = new stelle($this->formvars['selected_stelle_id'], $this->user->database);		# das "alte" Stellenobjekt
      $Stelle->language = $this->Stelle->language;
      $Stelle->Aendern($this->formvars);
      if ($this->formvars['id'] != '') {
        $new_stelle = new stelle($this->formvars['id'], $this->user->database);		# das "neue" Stellenobjekt, falls die Stellen-ID geändert wird
        $this->formvars['selected_stelle_id'] = $this->formvars['id'];
      }
      else {
        $new_stelle = $Stelle;
      }
			# die alten Zuweisungen der Stelle
			$old_menues = $Stelle->getMenue(0, 'only_ids');
			$old_functions = $Stelle->getFunktionen('only_ids');
			$old_layouts = $this->ddl->load_layouts($Stelle->id, '', '', '', 'only_ids');
			$old_frames = $this->document->load_frames($Stelle->id, false, 'only_ids');
			$old_layer = $Stelle->getLayer('', 'only_ids');

			# die neuen Zuweisungen aus dem Formular
      $menues = ($this->formvars['selmenues'] == '' ? array() : explode(', ',$this->formvars['selmenues']));
      $functions = (trim($this->formvars['selfunctions']) == '' ? array() : explode(', ', $this->formvars['selfunctions']));
      $frames = (trim($this->formvars['selframes']) == '' ? array() : explode(', ', $this->formvars['selframes']));
			$layouts = (trim($this->formvars['sellayouts']) == '' ? array() : explode(', ', $this->formvars['sellayouts']));
      $layer = (trim($this->formvars['sellayer']) == '' ? array() : explode(', ', $this->formvars['sellayer']));
      $selectedusers = array_filter(explode(', ',$this->formvars['selusers']));
			$selectedparents = ($this->formvars['selparents'] == '' ? array() : explode(', ', $this->formvars['selparents']));
			$selectedchildren = ($this->formvars['selchildren'] == '' ? array() : explode(', ', $this->formvars['selchildren']));

			# die menues, functions, frames, layouts, layers und users der Oberstellen zusätzlich zuordnen oder entfernen
			# Parameterübergabe erfolgt per Referenz
			$results = $new_stelle->apply_parent_selection(
				$selectedparents,
				$menues,
				$functions,
				$frames,
				$layouts,
				$layer
			);

			foreach ($results AS $result) {
				$this->add_message($result['type'], $result['message']);
			}

			# Zuweisung in der geänderten Stelle
      $this->Stellenzuweisung(
				$Stelle,
				$new_stelle,
				$menues,
				$functions,
				$frames,
				$layouts,
				$layer,
				$selectedusers,
				$selectedparents[0]
			);

			# Zuweisung in den Kindstellen
			$old_children = $Stelle->getChildren($this->formvars['selected_stelle_id'], " ORDER BY Bezeichnung", 'only_ids', true);
			foreach (array_unique(array_merge($old_children, $selectedchildren)) AS $child_id){
				$drop_child = !in_array($child_id, $selectedchildren) ? true : false;
				if(!in_array($child_id, $old_children)){
					$new_stelle->addChild($child_id);
				}
				$child_stelle = new stelle($child_id, $this->user->database);
				# zunächst alle Zuweisungen der Kindstelle selber holen, abzüglich der alten Zuweisungen der geänderten Stelle
				$menues = array_diff($child_stelle->getMenue(0, 'only_ids'), $old_menues);
				$functions = array_diff($child_stelle->getFunktionen('only_ids'), $old_functions);
				$layouts = array_diff($this->ddl->load_layouts($child_id, '', '', '', 'only_ids'), $old_layouts);
				$frames = array_diff($this->document->load_frames($child_id, false, 'only_ids'), $old_frames);
				$layer = array_diff($child_stelle->getLayer('', 'only_ids'), $old_layer);
				$selectedusers = $child_stelle->getUser('only_ids');
				$parents = $child_stelle->getParents('ORDER BY `ID`', 'only_ids');
				# dann entsprechend der Elternstellen erweitern bzw. reduzieren
				$child_stelle->apply_parent_selection(
					($drop_child ? array_diff($parents, array($Stelle->id)) : $parents),
					$menues,
					$functions,
					$frames,
					$layouts,
					$layer
				);
				# und zuweisen
				$this->Stellenzuweisung(
					$child_stelle,
					$child_stelle,
					$menues,
					$functions,
					$frames,
					$layouts,
					$layer,
					$selectedusers,
					$new_stelle->id
				);
				# Kindstelle entfernen
				if($drop_child){
					$Stelle->dropChild($child_id);
				}
			}
			# Test auf Zirkelbezug
			$children = $Stelle->getChildren($this->formvars['selected_stelle_id'], " ORDER BY Bezeichnung", 'only_ids', true, true);

			if (
				count(
					array_map(
						function($message) {
							if ($message['type'] == 'error') return $message;
						},
						GUI::$messages
					)
				) == 0
			) {
				$this->add_message('notice', 'Daten der Stelle erfolgreich eingetragen!');
			}
    }
    $this->Stelleneditor();
  }

	function StelleAnlegen() {
		include_once(CLASSPATH . 'Referenzkarte.php');
		$_files = $_FILES;
		if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
			# Fehler bei der Formulareingabe
			showAlert('Füllen Sie alle mit * gekennzeichneten Formularfelder aus.');
		}
		else {
			if ($_files['wappen']['name']) {
				$this->formvars['wappen'] = $_files['wappen']['name'];
				$nachDatei = WWWROOT . APPLVERSION . WAPPENPATH . $_files['wappen']['name'];
				if (move_uploaded_file($_files['wappen']['tmp_name'],$nachDatei)) {
					#echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
				}
			}

			if ($this->formvars['create_referencemap']) {
				$refmap_width = 250;
				$refmap_height = $refmap_width * ($this->formvars['maxymax'] - $this->formvars['minymax']) / ($this->formvars['maxxmax'] - $this->formvars['minxmax']);
				$refmap = $this->createReferenceMap($refmap_width, $refmap_height, $refmap_width, $refmap_height, 0, $this->formvars['minxmax'], $this->formvars['minymax'], $this->formvars['maxxmax'], $this->formvars['maxymax'], 1, REFMAPFILE, 'png');
				$refmap_file_name = 'refmap_' . $this->formvars['id'] . '.png';
				rename(IMAGEPATH . basename($refmap), REFERENCEMAPPATH . $refmap_file_name);
				// echo '<br>cp refmap: ' . IMAGEPATH . basename($refmap) . ' nach: ' . REFERENCEMAPPATH . $refmap_file_name;
				$params = array(
					'Name' => 'refmap_' . $this->formvars['id'],
					'Dateiname' => $refmap_file_name,
					'epsg_code' => $this->formvars['epsg_code'],
					'xmin' => $this->formvars['minxmax'],
					'ymin' => $this->formvars['minymax'],
					'xmax' => $this->formvars['maxxmax'],
					'ymax' => $this->formvars['maxymax'],
					'width' => $refmap_width,
					'height' => $refmap_height
				);
				$refmaps = Referenzkarte::find($this, "Dateiname LIKE '" . $refmap_file_name . "'");
				if (count($refmaps) > 0) {
					// reference map exists already, update it
					$refmap = $refmaps[0];
					$result = $refmap->update($params, false);
					$refmap_id = $refmap->get_id();
				}
				else {
					// reference map do not exists, create new
					$refmap = new Referenzkarte($this);
					$result = $refmap->create($params);
					$refmap_id = $result[0]['id'];
				}

				if ($result[0]['success']) {
					$this->formvars['Referenzkarte_ID'] = $refmap_id;
				}
				else {
					$this->add_message('error', $result[0]['msg']);
				}
			}
			$ret = $this->Stelle->NeueStelleAnlegen($this->formvars);
			if ($ret[0]) {
				# Fehler beim Eintragen der Stellendaten
				$this->Meldung = $ret[1];
			}
			else {
				$neue_stelle_id = $ret[1];
				$Stelle = new stelle($neue_stelle_id,$this->user->database);
				$menues = ($this->formvars['selmenues'] == '' ? array() : explode(', ',$this->formvars['selmenues']));
				$functions = (trim($this->formvars['selfunctions']) == '' ? array() : explode(', ', $this->formvars['selfunctions']));
				$frames = (trim($this->formvars['selframes']) == '' ? array() : explode(', ', $this->formvars['selframes']));
				$layouts = (trim($this->formvars['sellayouts']) == '' ? array() : explode(', ', $this->formvars['sellayouts']));
				$layer = (trim($this->formvars['sellayer']) == '' ? array() : explode(', ', $this->formvars['sellayer']));
				$users = array_map(
					function ($user) {
						return intval(trim($user));
					},
					explode(',', $this->formvars['selusers'])
				);
				$selectedparents = ($this->formvars['selparents'] == '' ? array() : explode(', ', $this->formvars['selparents']));
				
				# die menues, functions, frames, layouts, layers und users der Oberstellen zusätzlich zuordnen oder entfernen
				# Parameterübergabe erfolgt per Referenz
				$results = $Stelle->apply_parent_selection(
					$selectedparents,
					$menues,
					$functions,
					$frames,
					$layouts,
					$layer
				);
				
				# wenn Stelle ausgewählt, Daten kopieren
				if ($this->formvars['selected_stelle_id']) {
					$Stelle->copyLayerfromStelle($layer, $this->formvars['selected_stelle_id']);
				}
				$Stelle->addMenue($menues);
				if ($functions[0] != NULL) {
					$Stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
				}
				if ($layer[0] != NULL) {
					$Stelle->addLayer($layer);
				}
				$document = new Document($this->database);
				if ($frames[0] != NULL) {
					for ($i = 0; $i < count($frames); $i++) {
						$document->add_frame2stelle($frames[$i], $neue_stelle_id); # Hinzufügen der Druckrahmen zur Stelle
					}
				}
				if ($Stelle->default_user_id != '') {
					$users = put_value_first($users, $Stelle->default_user_id);
				}
				for ($i = 0; $i < count($users); $i++) {
					$this->user->rolle->setRolle($users[$i], $Stelle->id, $Stelle->default_user_id, (($i == 0 AND count($selectedparents) > 0) ? $selectedparents[0] : null));	# Hinzufügen einer neuen Rolle (selektierte User zur Stelle)
					$this->user->rolle->setMenue($users[$i], $Stelle->id, $Stelle->default_user_id);	# Hinzufügen der selektierten Obermenüs zur Rolle
					$this->user->rolle->setLayer($users[$i], $Stelle->id, $Stelle->default_user_id);	# Hinzufügen der Layer zur Rolle
					$this->user->rolle->setGroups($users[$i], $Stelle->id, $Stelle->default_user_id, $layer);
					$this->user->rolle->setSavedLayersFromDefaultUser($users[$i], $Stelle->id, $Stelle->default_user_id);
					$this->selected_user = new user('',$users[$i],$this->user->database);
					$this->selected_user->checkstelle();
				}
				$Stelle->updateLayerParams();
				$this->update_layer_parameter_in_rollen($Stelle->id);
				if ($ret[0]) {
					$this->Meldung = $ret[1];
				}
				else {
					$this->Meldung = 'Daten der Stelle erfolgreich eingetragen!';
				}
			}
		}
		$this->formvars['selected_stelle_id'] = $ret[1];
		$this->Stelleneditor();
	}

	function StellenAnzeigen() {
		# Abfragen aller Stellen
		if(value_of($this->formvars, 'order') == ''){
			$this->formvars['order'] = 'Bezeichnung';
		}
		$this->stellendaten = $this->Stelle->getStellen($this->formvars['order'], $this->user->id);
		$this->titel = 'Stellendaten';
		$this->main = 'stellendaten.php';
		$this->output();
	}
	
	function Stellenhierarchie(){
		$this->stellendaten = $this->Stelle->getStellen($this->formvars['order'], $this->user->id);
		$this->stellenhierarchie = $this->Stelle->getStellenhierarchie();
		$this->titel='Stellenhierachie';
		$this->main='stellenhierarchie.php';
		$this->output();
	}

  function Stelleneditor() {
		global $language;
		#echo '<p><b>Stelleneditor</b>';
		include_(CLASSPATH . 'datendrucklayout.php');
		include_(CLASSPATH . 'Funktion.php');
		include_(CLASSPATH . 'FormObject.php');
		$document = new Document($this->database);
		$ddl = new ddl($this->database, $this);
		$stelle = new MyObject($this, 'stelle');
		$where = '';
		$this->formvars['selmenues'] = array();
		$this->formvars['selfunctions'] = array();
		$this->formvars['selframes'] = array();
		$this->formvars['sellayouts'] = array();
		$this->formvars['sellayer'] = array();
		$this->formvars['selusers'] = array();		
		$this->formvars['selparents'] = array();
		$this->formvars['selchildren'] = array();
		$children_ids = array();
		$parent_ids = array();

		# Abfragen der Stellendaten wenn eine stelle_id zur Änderung selektiert ist
		if ($this->formvars['selected_stelle_id'] > 0) {
			$Stelle = new stelle($this->formvars['selected_stelle_id'], $this->user->database);
      $Stelle->language = $this->Stelle->language;
      $this->stellendaten = $Stelle->getstellendaten();
			$this->allstellendaten = $this->Stelle->getStellen('');
      $this->formvars['bezeichnung'] = $this->stellendaten['Bezeichnung'];
			if ($language != 'german') {
				$this->formvars['Bezeichnung_' . $language] = $this->stellendaten['Bezeichnung_' . $language];
			}
      $this->formvars['minxmax'] = $this->stellendaten['minxmax'];
      $this->formvars['minymax'] = $this->stellendaten['minymax'];
      $this->formvars['maxxmax'] = $this->stellendaten['maxxmax'];
      $this->formvars['maxymax'] = $this->stellendaten['maxymax'];
      $this->formvars['epsg_code'] = $this->stellendaten['epsg_code'];
      $this->formvars['Referenzkarte_ID'] = $this->stellendaten['Referenzkarte_ID'];
      $this->formvars['start'] = $this->stellendaten['start'];
      $this->formvars['stop'] = $this->stellendaten['stop'];
			$this->formvars['postgres_connection_id'] = $this->stellendaten['postgres_connection_id'];
      $this->formvars['pgdbhost'] = $this->stellendaten['pgdbhost'];
      $this->formvars['pgdbname'] = $this->stellendaten['pgdbname'];
      $this->formvars['pgdbuser'] = $this->stellendaten['pgdbuser'];
      $this->formvars['pgdbpasswd'] = $this->stellendaten['pgdbpasswd'];
      $this->formvars['ows_namespace'] = $this->stellendaten['ows_namespace'];
      $this->formvars['ows_title'] = $this->stellendaten['ows_title'];
      $this->formvars['ows_abstract'] = $this->stellendaten['ows_abstract'];
      $this->formvars['wms_accessconstraints'] = $this->stellendaten['wms_accessconstraints'];
      $this->formvars['ows_contactorganization'] = $this->stellendaten['ows_contactorganization'];
      $this->formvars['ows_contacturl'] = $this->stellendaten['ows_contacturl'];
			$this->formvars['ows_contactemailaddress'] = $this->stellendaten['ows_contactemailaddress'];
      $this->formvars['ows_contactperson'] = $this->stellendaten['ows_contactperson'];
      $this->formvars['ows_contactposition'] = $this->stellendaten['ows_contactposition'];
      $this->formvars['ows_contactvoicephone'] = $this->stellendaten['ows_contactvoicephone'];
			$this->formvars['ows_contactfacsimile'] = $this->stellendaten['ows_contactfacsimile'];
			$this->formvars['ows_contactaddress'] = $this->stellendaten['ows_contactaddress'];
			$this->formvars['ows_contactpostalcode'] = $this->stellendaten['ows_contactpostalcode'];
			$this->formvars['ows_contactcity'] = $this->stellendaten['ows_contactcity'];
			$this->formvars['ows_contactadministrativearea'] = $this->stellendaten['ows_contactadministrativearea'];
			$this->formvars['ows_contentorganization'] = $this->stellendaten['ows_contentorganization'];
			$this->formvars['ows_contenturl'] = $this->stellendaten['ows_contenturl'];
			$this->formvars['ows_contentemailaddress'] = $this->stellendaten['ows_contentemailaddress'];
			$this->formvars['ows_contentperson'] = $this->stellendaten['ows_contentperson'];
			$this->formvars['ows_contentposition'] = $this->stellendaten['ows_contentposition'];
			$this->formvars['ows_contentvoicephone'] = $this->stellendaten['ows_contentvoicephone'];
			$this->formvars['ows_contentfacsimile'] = $this->stellendaten['ows_contentfacsimile'];
			$this->formvars['ows_contentaddress'] = $this->stellendaten['ows_contentaddress'];
			$this->formvars['ows_contentpostalcode'] = $this->stellendaten['ows_contentpostalcode'];
			$this->formvars['ows_contentcity'] = $this->stellendaten['ows_contentcity'];
			$this->formvars['ows_contentadministrativearea'] = $this->stellendaten['ows_contentadministrativearea'];
			$this->formvars['ows_geographicdescription'] = $this->stellendaten['ows_geographicdescription'];
			$this->formvars['ows_distributionorganization'] = $this->stellendaten['ows_distributionorganization'];
			$this->formvars['ows_distributionurl'] = $this->stellendaten['ows_distributionurl'];
			$this->formvars['ows_distributionemailaddress'] = $this->stellendaten['ows_distributionemailaddress'];
			$this->formvars['ows_distributionperson'] = $this->stellendaten['ows_distributionperson'];
			$this->formvars['ows_distributionposition'] = $this->stellendaten['ows_distributionposition'];
			$this->formvars['ows_distributionvoicephone'] = $this->stellendaten['ows_distributionvoicephone'];
			$this->formvars['ows_distributionfacsimile'] = $this->stellendaten['ows_distributionfacsimile'];
			$this->formvars['ows_distributionaddress'] = $this->stellendaten['ows_distributionaddress'];
			$this->formvars['ows_distributionpostalcode'] = $this->stellendaten['ows_distributionpostalcode'];
			$this->formvars['ows_distributioncity'] = $this->stellendaten['ows_distributioncity'];
			$this->formvars['ows_distributionadministrativearea'] = $this->stellendaten['ows_distributionadministrativearea'];
      $this->formvars['ows_fees'] = $this->stellendaten['ows_fees'];
      $this->formvars['ows_srs'] = $this->stellendaten['ows_srs'];
      $this->formvars['wappen'] = $this->stellendaten['wappen'];
			$this->formvars['wappen_link'] = $this->stellendaten['wappen_link'];
			$this->formvars['checkClientIP'] = $this->stellendaten['check_client_ip'];
      $this->formvars['checkPasswordAge'] = $this->stellendaten['check_password_age'];
      $this->formvars['allowedPasswordAge'] = $this->stellendaten['allowed_password_age'];
      $this->formvars['use_layer_aliases'] = $this->stellendaten['use_layer_aliases'];
			$this->formvars['hist_timestamp'] = $this->stellendaten['hist_timestamp'];
      $this->formvars['selmenues'] = $Stelle->getMenue(0);
      $Stelle->getFunktionen();
      $this->formvars['selfunctions'] = $Stelle->funktionen['array'];
      $this->formvars['selframes'] = $document->load_frames($this->formvars['selected_stelle_id'], NULL);
			$this->formvars['sellayouts'] = $ddl->load_layouts($this->formvars['selected_stelle_id'], NULL, NULL, NULL);
      $this->formvars['sellayer'] = $Stelle->getLayers(NULL, 'Name');
      $this->formvars['selusers'] = $Stelle->getUser();
			$this->formvars['selparents'] = $Stelle->getParents("ORDER BY `Bezeichnung`"); // formatted mysql resultset, ordered by Bezeichnung
			$this->formvars['selchildren'] = $Stelle->getChildren($this->formvars['selected_stelle_id'], "ORDER BY Bezeichnung"); // formatted mysql resultset, ordered by Bezeichnung
			$this->formvars['default_user_id'] = $this->stellendaten['default_user_id'];
			$this->formvars['show_shared_layers'] = $this->stellendaten['show_shared_layers'];
			$this->formvars['version'] = $this->stellendaten['version'];
			$this->formvars['reset_password_text'] = $this->stellendaten['reset_password_text'];
			$this->formvars['invitation_text'] = $this->stellendaten['invitation_text'];
			$this->formvars['comment'] = $this->stellendaten['comment'];
			$where = 'ID != '.$this->formvars['selected_stelle_id'];

			$children_ids = array_map(function($child) {return $child['ID'];}, $this->formvars['selchildren']);
			$parent_ids = array_map(function($parent) {return $parent['ID'];}, $this->formvars['selparents']);
    }

		$alle_anderen_stellen = $stelle->find_where($where, 'Bezeichnung');

		# Abfragen aller möglichen Oberstellen. Kindstellen der ausgewählten Stelle werden ausgenommen;
		$this->formvars['parents'] = array();
		foreach ($alle_anderen_stellen AS $parent) {
			if (!in_array($parent->get('ID'), $children_ids)) $this->formvars['parents'][] = $parent;
		}

		# Abfragen aller möglichen Kindstellen. Oberstellen der ausgewählten Stelle werden ausgenommen;
		$this->formvars['children'] = array();
		foreach ($alle_anderen_stellen AS $child) {
			if (!in_array($parent->get('ID'), $parent_ids)) $this->formvars['children'][] = $child;
		}

    # Abfragen aller möglichen Menuepunkte
    $this->formvars['menues'] = Menue::get_all_ober_menues($this);
    # Abfragen aller möglichen Funktionen
    $funktion = new Funktion($this);
    $this->formvars['functions'] = $funktion->getFunktionen(NULL, 'bezeichnung', $this->Stelle->id, $this->user->id);
    # Abfragen aller möglichen Kartendruck-Layouts
    $this->formvars['frames'] = $document->load_frames(NULL, NULL);
		# Abfragen aller möglichen Datendruck-Layouts
    $this->formvars['layouts'] = $ddl->load_layouts(NULL, NULL, NULL, NULL);

		# Abfragen aller verfügbaren Layer der Stelle ab
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->formvars['layer'] = $mapDB->getall_Layer('Name');
		$this->layergruppen = $mapDB->get_Groups();

    # Abfragen aller verfügbaren User der Stelle ab
		$this->formvars['users'] = $this->user->getall_Users('Name', $this->Stelle->id, $this->user->id);

    # Abfragen aller möglichen EPSG-Codes
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
		$this->titel='Stellen Editor';
		$this->main='stelle_formular.php';
    $this->output();
  }

	function StelleLoeschen() {
		$selected_stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
		$selected_stelle->deleteMenue(0);
		$selected_stelle->deleteDruckrahmen();
		$selected_stelle->deleteStelleGemeinden();
		$selected_stelle->deleteFunktionen();
		$selected_stelle->deleteLayer(0, $this->pgdatabase);
		$user = $selected_stelle->getUser();
		$stelle_id = explode(',', $selected_stelle->id);
		for ($i = 0; $i < count($user['ID']); $i++) {
			$this->user->rolle->deleteRollen($user['ID'][$i], $stelle_id);
			$this->user->rolle->deleteMenue($user['ID'][$i], $stelle_id, 0);
			$this->user->rolle->deleteGroups($user['ID'][$i], $stelle_id);
			$this->user->rolle->deleteLayer($user['ID'][$i], $stelle_id, 0);
		}
		$selected_stelle->delete();
		$this->titel = 'Stellendaten';
		$this->main = 'stellendaten.php';
		# Abfragen aller Stellen
		$this->stellendaten = $this->Stelle->getStellen($this->formvars['order']);
		$this->output();
	}

	function filterverwaltung() {
		$this->loadMap('DataBase');
		$this->titel = 'Filterverwaltung';
		$this->main = 'filterverwaltung.php';
		$this->stellendaten = $this->Stelle->getStellen('Bezeichnung');

		$showpolygon = true;
		$setKeys = array();
		$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true, true);

		if (
			defined('LAYERNAME_FLURSTUECKE') AND
			!$this->formvars['geom_from_layer']
		) {
			$layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
			$this->formvars['geom_from_layer'] = $layerset[0]['Layer_ID'];
		}

    if ($this->formvars['stelle'] != '') {
      $stelle = new stelle($this->formvars['stelle'], $this->database);
      $this->layerdaten = $stelle->getLayers(NULL, 'Name');
      if ($this->formvars['selected_layers'] != '') {
        $this->selected_layers = explode(', ', $this->formvars['selected_layers']);
        $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
        $this->attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[0], array('if_empty_use_query' => true));
        $poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[0]);
        for ($i = 1; $i < count($this->selected_layers); $i++) {
          $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
          $attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[$i], array('if_empty_use_query' => true));
          $this->attributes = array_values(array_uintersect($this->attributes, $attributes, "compare_names"));
          $next_poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[$i]);
          if($poly_id != $next_poly_id){
            $showpolygon = false;
          }
          $poly_id = $next_poly_id;
        }
				if (empty($this->attributes)) {
					$this->attributes = array();
				}
				for ($i = 0; $i < count($this->attributes); $i++) {
					$this->formvars['operator_' . $this->attributes[$i]['name']] = '';
					$this->formvars['value_' . $this->attributes[$i]['name']] = '';
				}
				for ($j = 0; $j < count($this->selected_layers); $j++){
					$filter = $this->mapDB->read_attribute_filter($this->formvars['stelle'], $this->selected_layers[$j]);
					for ($i = 0; $i < count_or_0($filter); $i++) {
						if (
							$this->formvars['value_'.$filter[$i]['attributname']] == NULL OR
							(
								$this->formvars['value_'.$filter[$i]['attributname']] == $filter[$i]['attributvalue'] AND
								$this->formvars['operator_'.$filter[$i]['attributname']] == $filter[$i]['operator']
							)
						) {
              $this->formvars['value_'.$filter[$i]['attributname']] = $filter[$i]['attributvalue'];
              $this->formvars['operator_'.$filter[$i]['attributname']] = $filter[$i]['operator'];
              $setKeys[$filter[$i]['attributname']]++;
            }
            else{
              $this->formvars['value_'.$filter[$i]['attributname']] = '---- verschieden ----';
            }
          }
        }
        foreach ($setKeys as $key => $value){
          if ($value < count_or_0($this->selected_layers)){
            $this->formvars['value_'.$key] = '---- verschieden ----';
          }
        }
        if ($this->formvars['CMD']!='') {
          # Es soll navigiert werden
          # Navigieren
          $this->navMap($this->formvars['CMD']);
          $this->user->rolle->saveSettings($this->map->extent);
          $this->user->rolle->readSettings();
        }
        else {
          # Zoom zum Polygon des Filters
          if ($poly_id != '' AND $showpolygon == true){
            $PolygonAsSVG = $this->pgdatabase->selectPolyAsSVG($poly_id, $this->user->rolle->epsg_code);
            $PolygonAsSVG = transformCoordsSVG($PolygonAsSVG);
            $this->zoomToPolygon('u_polygon', $poly_id,20, $this->user->rolle->epsg_code);
            $this->user->rolle->saveSettings($this->map->extent);
            $this->user->rolle->readSettings();
            $this->formvars['newpath'] = $PolygonAsSVG;
            $PolygonAsText = $this->pgdatabase->selectPolyAsText($poly_id, $this->user->rolle->epsg_code);
            $this->formvars['newpathwkt'] = $PolygonAsText;
            $this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
						$this->formvars['map_flag'] = 1;
          }
        }
      }
      else {
        # Es soll navigiert werden
        # Navigieren
        $this->navMap($this->formvars['CMD']);
        $this->user->rolle->saveSettings($this->map->extent);
        $this->user->rolle->readSettings();
      }
    }
    else {
      # Es soll navigiert werden
      # Navigieren
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveSettings($this->map->extent);
      $this->user->rolle->readSettings();
    }
  }

	function Filter_speichern($formvars) {
		include_once(CLASSPATH . 'Layer.php');
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($formvars['selected_layers'] != ''){
      $this->selected_layers = explode(', ', $formvars['selected_layers']);
      $layerdb = $mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
      $this->attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[0],  array('if_empty_use_query' => true));
			for($i = 1; $i < count($this->selected_layers); $i++){
				$layerdb = $mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
				$attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[$i],  array('if_empty_use_query' => true));
				$this->attributes = array_values(array_uintersect($this->attributes, $attributes, "compare_names"));
			}
			for($i = 0; $i < count($this->attributes); $i++){
				if($this->attributes[$i]['type'] != 'geometry'){
					//---------- normales Attribut -------------//
					if($formvars['value_'.$this->attributes[$i]['name']] != '' AND $formvars['value_'.$this->attributes[$i]['name']] != '---- verschieden ----'){
						//---------------- einfügen --------------//
						$formvars['attributname'] = $this->attributes[$i]['name'];
						$formvars['attributvalue'] = $formvars['value_'.$this->attributes[$i]['name']];
						$formvars['operator'] = $formvars['operator_'.$this->attributes[$i]['name']];
						$formvars['type'] = $this->attributes[$i]['type'];
						for($j = 0; $j < count($this->selected_layers); $j++){
							$formvars['layer'] = $this->selected_layers[$j];
							$mapDB->saveAttributeFilter($formvars);
						}
					}
					elseif($formvars['value_'.$this->attributes[$i]['name']] != '---- verschieden ----'){
						//--------------- löschen ----------------//
						for($j = 0; $j < count($this->selected_layers); $j++){
							$mapDB->deleteFilter($formvars['stelle'], $this->selected_layers[$j], $this->attributes[$i]['name']);
						}
					}
				}
				else{   //---------- the_geom -------------//
					if($this->formvars['check_'.$this->attributes[$i]['name']] != ''){
						$polygonWeltkoordinaten = $this->formvars['newpathwkt'];
						if(strpos($polygonWeltkoordinaten,'P') == 0){
							$polygonWeltkoordinaten = str_replace('POLYGON((', 'MULTIPOLYGON(((', $polygonWeltkoordinaten);
							$polygonWeltkoordinaten .= ')';
						}
						if($this->formvars['value_'.$this->attributes[$i]['name']] != '' AND $this->formvars['value_'.$this->attributes[$i]['name']] != '---- verschieden ----'){
							//-------------- update -----------------//
							$this->pgdatabase->updatepolygon($polygonWeltkoordinaten, $this->user->rolle->epsg_code, $this->formvars['value_'.$this->attributes[$i]['name']]);
							$formvars['attributname'] = $this->attributes[$i]['name'];
							$formvars['attributvalue'] = $this->formvars['value_'.$this->attributes[$i]['name']];
							$formvars['operator'] = $formvars['operator_'.$this->attributes[$i]['name']];
							$formvars['type'] = $this->attributes[$i]['type'];
							for($j = 0; $j < count($this->selected_layers); $j++){
								$formvars['layer'] = $this->selected_layers[$j];
								$mapDB->saveAttributeFilter($formvars);
							}
						}
						else{
							//-------------- neu einfügen -----------------//
							$poly_id = $this->pgdatabase->insertpolygon($polygonWeltkoordinaten, $this->user->rolle->epsg_code);
							$formvars['attributname'] = $this->attributes[$i]['name'];
							$formvars['attributvalue'] = $poly_id;
							$formvars['operator'] = $formvars['operator_'.$this->attributes[$i]['name']];
							$formvars['type'] = $this->attributes[$i]['type'];
							for($j = 0; $j < count($this->selected_layers); $j++){
								//-------------- wenn vorhanden, alte Polygone löschen ----------//
								$poly_id = $mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[$j]);
								if($poly_id != NULL){
									$this->pgdatabase->deletepolygon($poly_id);
								}
								$formvars['layer'] = $this->selected_layers[$j];
								$mapDB->saveAttributeFilter($formvars);
							}
						}
					}
					elseif($this->formvars['value_'.$this->attributes[$i]['name']] != '' AND $formvars['value_'.$this->attributes[$i]['name']] != '---- verschieden ----'){
						//-------------- löschen -----------------//
						for($j = 0; $j < count($this->selected_layers); $j++){
							$mapDB->deleteFilter($formvars['stelle'], $this->selected_layers[$j], $this->attributes[$i]['name']);
						}
						if($mapDB->checkPolygon($this->formvars['value_'.$this->attributes[$i]['name']]) == false){
							$this->pgdatabase->deletepolygon($this->formvars['value_'.$this->attributes[$i]['name']]);
						}
						$this->formvars['newpath'] = NULL;
						$this->formvars['pathwkt'] = NULL;
						$this->formvars['newpathwkt'] = NULL;
						$this->formvars['result'] = NULL;
					}
        }
      }
    }
		for ($i = 0; $i < count($this->selected_layers); $i++) {
			$this->filterstring = $this->compose_filter(
				$mapDB->read_attribute_filter($this->formvars['stelle'], $this->selected_layers[$i]),
				Layer::find_by_id($this, $this->selected_layers[$i])->get('epsg_code')
			);
			$result = $mapDB->write_filter(
				$this->filterstring,
				$formvars['stelle'],
				$this->selected_layers[$i]
			);
			if ($result AND $this->filterstring != '') {
				$this->add_message('info', 'Folgenden Filter für Layer ' . $this->selected_layers[$i] . ' in Stelle ' . $formvars['stelle'] . ' geschrieben:<br>' . $this->filterstring);
			}
		}
		$this->filterverwaltung();
	}

	/**
	 * Function shows all rollen in a list
	 */
	function role_list() {
		# Abfragen aller Rollen
		$this->roles = Role::find($this, 'true', ($this->formvars['view_sort'] ? $this->formvars['view_sort'] : 'stelle_id, user_id'), $this->formvars['sort_direction']);
		if ($this->formvars['sort_direction'] == 'DESC') {
			$this->formvars['sort_direction'] = 'ASC';
		}
		else {
			$this->formvars['sort_direction'] = 'DESC';
		}
		$this->titel = 'Nutzerollen';
		$this->main='rolesdaten.php';
		$this->output();
	}

	/*
	* This function get the rolle by formvar user_id and stelle_id,
	* set the titel and page and start the output
	*/
	function role_edit() {
		$this->sanitize([
			'user_id' => 'integer',
			'stelle_id' => 'integer'
		]);
		$this->role = Role::find_by_id($this, $this->formvars['user_id'], $this->formvars['stelle_id']);
		$this->main = 'role_formular.php';
		$this->output();
	}

	function role_update() {
		// ToDo sanitize all formvars
		$this->sanitize([
			'user_id' => 'integer',
			'stelle_id' => 'integer'
		]);
		$this->role = Role::find_by_id($this, $this->formvars['user_id'], $this->formvars['stelle_id']);
		$this->role->setData($this->formvars);
		$results = $this->role->validate();
		if (empty($results)) {
			$results = $this->role->update();
		}
		if ($results[0]['success']) {
			$this->add_message('notice', 'Nutzerrolle erfolgreich aktualisiert.');
		}
		else {
			$this->add_message('array', array_values($results));
		}
		$this->main = 'role_formular.php';
		$this->output();
	}
//----------------
	/**
	 * Function shows all menues in a list
	 */
	function MenuesAnzeigen() {
		# Abfragen aller Menues
		if ($this->formvars['view_sort'] == '') {
			$this->formvars['view_sort'] = 'name';
		}
		$this->menuedaten = Menue::find($this, 'true', $this->formvars['view_sort'], $this->formvars['sort_direction']);
		if ($this->formvars['sort_direction'] == 'DESC') {
			$this->formvars['sort_direction'] = 'ASC';
		}
		else {
			$this->formvars['sort_direction'] = 'DESC';
		}
		$this->titel='Menüdaten';
		$this->main='menuedaten.php';
		$this->output();
	}

	/*
	* this function get the menue by formvar selected_menue_id,
	* set the titel and page and start the output
	*/
	function Menueeditor() {
		$this->menue = new Menue($this);
		if ($this->formvars['selected_menue_id'] != '') {
			$this->menue->find_by('id', $this->formvars['selected_menue_id']);
		}
		else {
			$this->menue->setKeysFromTable();
		}
		$this->titel = 'Menü Editor';
		$this->main = 'menue_formular.php';
		$this->output();
	}

	function MenueSpeichern() {
		$this->menue = new Menue($this);
		$this->menue->data = formvars_strip($this->formvars, $this->menue->setKeysFromTable(), 'keep');
		$this->menue->set('title', $this->formvars['title']);
		$results = $this->menue->validate();
		if (empty($results)) {
			$results = $this->menue->create();
		}
		if ($results[0]['success']) {
			$this->add_message('notice', 'Menü erfolgreich angelegt.');
			$this->menuedaten = Menue::find($this, '', 'name');
			$this->titel='Menüdaten';
			$this->main='menuedaten.php';
		}
		else {
			$this->add_message('array', array_values($results));
			$this->main = 'menue_formular.php';
		}
		$this->output();
	}

	function MenueAendern() {
		$this->menue = new Menue($this);
		$this->menue->find_by('id', $this->formvars['selected_menue_id']);
		$this->menue->setData($this->formvars);
		$results = $this->menue->validate();
		if (empty($results)) {
			$results = $this->menue->update();
		}
		if ($results[0]['success']) {
			$this->add_message('notice', 'Menü erfolgreich aktualisiert.');
		}
		else {
			$this->add_message('array', array_values($results));
		}
		$this->titel = 'Menü Editor';
		$this->main = 'menue_formular.php';
		$this->output();
	}

	function MenueLoeschen(){
		$this->menue = new Menue($this);
		$this->menue->find_by('id', $this->formvars['selected_menue_id']);
		$this->menue->delete();
		$this->menuedaten = Menue::find($this, 'true', $this->formvars['order']);
		$this->titel='Menüdaten';
		$this->main='menuedaten.php';
		$this->output();
	}

  function StatistikAuswahl() {
    # Abfragen aller Stellen für die Statistik oder Abrechnung
    $this->account = new account($this->database);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    if($this->formvars['go'] == 'StatistikAuswahl_Stelle'){
    	$this->stellendaten=$this->user->getStellen('Bezeichnung');
    }
    $this->UserDaten = $this->user->getall_Users('Name');
    $this->titel='Auswahl zur Statistik';
    $this->main='StatistikWaehlen.php';
    $this->output();
  } # END of function StatistikAuswahl

  function StatistikAuswahlErgebnis(){
    # Abfragen und Abfangen von Fehleingaben der Eingabe für Ausgabe der Statistik
    if ($this->formvars['stelle']=='' AND $this->formvars['nutzer']==''){
        $errmsg='Wählen Sie bitte die entsprechende Stelle und/oder den Nutzer aus!';
        $this->Meldung=$errmsg;
        $this->StatistikAuswahl();
        showAlert($this->Meldung);
        return;
    }
    else {
      if($this->Meldung != ''){
        showAlert($this->Meldung);
      }
      if ($this->formvars['stelle']!='' AND $this->formvars['nutzer']=='') {
        $this->formvars['nutzung']='stelle';
      }
      if ($this->formvars['stelle']=='' AND $this->formvars['nutzer']!=''){
        $this->formvars['nutzung']='nutzer';
      }
      if ($this->formvars['stelle']!='' AND $this->formvars['nutzer']!='') {
        $this->formvars['nutzung']='stelle_nutzer';
      }
    }

    if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']=='week' OR $this->formvars['zeitraum']=='day' OR $this->formvars['zeitraum']=='era') {
        if ($this->formvars['zeitraum']=='month') {
          if ($this->formvars['month_m']=='' OR $this->formvars['year_m']==''){
            $errmsg='Wählen Sie bitte Monat und Jahr aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
            return;
          }
        }
        if ($this->formvars['zeitraum']=='week') {
          if ($this->formvars['week_w']=='' OR $this->formvars['year_w']=='') {
            $errmsg='Wählen Sie bitte die gewünschte Kalenderwoche und das Jahr aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
          return;
          }
        }
        if ($this->formvars['zeitraum']=='day') {
          if ($this->formvars['day_d']=='' OR $this->formvars['month_d']=='' OR $this->formvars['year_d']=='' ){
            $errmsg='Wählen Sie bitte Tag, Monat und Jahr für die Ausgabe aus!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
          return;
          }
        }
        if ($this->formvars['zeitraum']=='era') {
          if ($this->formvars['day_e1']=='' OR $this->formvars['month_e1']=='' OR $this->formvars['year_e1']=='' OR $this->formvars['day_e2']=='' OR $this->formvars['month_e2']=='' OR $this->formvars['year_e2']=='') {
            $errmsg='Wählen Sie bitte Tag, Monat und Jahr aus, von wann bis wann die Ausgabe erfolgen soll!';
            $this->Meldung=$errmsg;
            $this->StatistikAuswahl();
            showAlert($this->Meldung);
            return;
          }
        }
    }
    else {
        $errmsg='Wählen Sie bitte den Zeitraum für die Statistik aus!';
        $this->Meldung=$errmsg;
        $this->StatistikAuswahl();
        showAlert($this->Meldung);
        return;
    }
    $this->account = new account($this->database);
    $this->account->getStatistik($this->formvars['nutzer'],$this->formvars['nutzung'],$this->formvars['stelle'],$this->formvars['zeitraum'],$this->formvars['day_d'],$this->formvars['week_w'],$this->formvars['month_d'],$this->formvars['month_w'],$this->formvars['month_m'],$this->formvars['year_m'],$this->formvars['year_w'],$this->formvars['year_d'],$this->formvars['day_e1'],$this->formvars['day_e2'],$this->formvars['month_e1'],$this->formvars['month_e2'],$this->formvars['year_e1'],$this->formvars['year_e2']);

    $this->account->ALKA4 = 0;
    $this->account->ALKA3 = 0;
    for($i = 0; $i < count_or_0($this->account->ALKNumbOfAccess); $i++){
      if(($this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4hoch' OR $this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4quer') AND $this->account->ALKNumbOfAccess[$i]['Preis'] > 0){
        $this->account->ALKA4 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
      else{
        $this->account->ALKA3 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
    }
    $this->account->ALB = 0;
    for($i = 0; $i < count_or_0($this->account->ALBNumbOfAccess); $i++){
        $this->account->ALB += $this->account->ALBNumbOfAccess[$i]['NumberOfAccess'];
    }

    $this->titel='Zugriffsstatistik ';
    switch($this->formvars['nutzung']){
      case 'stelle' : {
        $this->titel .= 'der Stelle '.$this->account->Bezeichnung;
      } break;

      case 'nutzer' : {
        $this->titel .= 'des Nutzers '.$this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'];
      } break;

      case 'stelle_nutzer' : {
        $this->titel .= 'des Nutzers '.$this->account->UName[0]['Vorname'].' '.$this->account->UName[0]['Name'].' in der Stelle '.$this->account->Bezeichnung;
      } break;
    }
    $this->main='StatistikUebersicht.php';
    $this->output();
  }# END of function StatistikAuswahlErgebnis

  function export_georg($formvars){
    $georg = new georg_export();
    $georg->Amt = $georg->get_gemeindedata_from_file($this->formvars['bezeichnung']);
    if($georg->Amt == NULL){
      $this->Meldung = 'Die ausgewählte Stelle entspricht keiner der in der Datei \''.GEORG_AMTS_DATEI.'\' aufgeführten Ämter.';
    }
    else{
      $georg->user = $this->user->Name;
      $georg->ALKA3 = $this->formvars['anzahlA3'];
      $georg->ALKA4 = $this->formvars['anzahlA4'];
      $georg->ALB = $this->formvars['anzahlALB'];
      $document = new Document($this->database);
      $georg->preisALKA4 = $document->get_price('A4hoch');
      $georg->preisALKA3 = $document->get_price('A3hoch');
      $georg->betragALK = ($georg->preisALKA4 * $georg->ALKA4 + $georg->preisALKA3 * $georg->ALKA3)/100;
      $georg->betragALB = ($georg->preisALB * $georg->ALB)/100;
      $georg->endbetrag = $georg->betragALB + $georg->betragALK;
      $faelligtime = time() + (21 * 86400);
      $georg->faellig = date('d.m.Y', $faelligtime);
      if($this->formvars['zeitraum']=='month'){
        $georg->architekt = 'monatliche Abrechnung';
      }
      $georg->write_file();
      $this->Meldung = 'Georg-Datei erzeugt.';
    }

    $this->StatistikAuswahlErgebnis();
  }

  function StyleLabelEditor(){
		$this->formvars['width_reduction'] = $this->user->rolle->nImageWidth - 500;
		$this->formvars['height_reduction'] = $this->user->rolle->nImageHeight - 500;
    $this->user->rolle->nImageWidth = 500;
    $this->user->rolle->nImageHeight = 500;
    if($this->formvars['neuladen']){
      $this->neuLaden();
    }
    else{
      $this->loadMap('DataBase');
    }
    $this->main='style_label_editor.php';
    $this->titel='Style- und Labeleditor';
    $this->fonts = $this->getfonts();
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdaten = $mapDB->getall_Layer('Name');
    if($this->formvars['selected_layer_id'] != ''){
			$this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
      $this->allclassdaten = $mapDB->read_Classes($this->formvars['selected_layer_id']);
      if($this->formvars['selected_class_id'] != ''){
        $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['selected_class_id']);
        if($this->formvars['selected_style_id'] != ''){
          $this->styledaten = $mapDB->get_Style($this->formvars['selected_style_id']);
        }
        if($this->formvars['selected_label_id'] != ''){
          $this->labeldaten = $mapDB->get_Label($this->formvars['selected_label_id']);
        }
      }
    }
    if ($this->formvars['CMD']!='') {
      # Nur Navigieren
      $this->navMap($this->formvars['CMD']);
    }
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

	function get_symbol_list($layerset = ['Datentyp' => ''], $icon_size = 30){
		$map = new mapObj(DEFAULTMAPFILE);
		$map->setSymbolSet(SYMBOLSET);
		$map->setFontSet(FONTSET);
		$numSymbols = $map->getNumSymbols();
		$symbols = array();
		$layer_point	 = new LayerObj($map);
		$layer_line		 = new LayerObj($map);
		$layer_polygon = new LayerObj($map);
		$layer_point->type = MS_LAYER_POINT;
		$layer_line->type = MS_LAYER_LINE;
		$layer_polygon->type = MS_LAYER_POLYGON;
		for ($symbolid = 1; $symbolid < $numSymbols; $symbolid++) {
			if (MAPSERVERVERSION >= 800) {
				$symbol = $map->symbolset->getSymbol($symbolid);
			}
			else {
				$symbol = $map->getSymbolObjectById($symbolid);
			}
			switch (true) {
				case $layerset['Datentyp'] == MS_LAYER_POLYGON OR $symbol->type == 1005 : {
					$class = new classObj($layer_polygon);
					$symbolnr = $symbolid;
					$size = 6;
					$width = 1;
				} break;
				case $layerset['Datentyp'] == '' AND $symbol->type == 1002 : {
					$class = new classObj($layer_line);
					$symbolnr = 0;
					$width = 2;
				} break;
				default : {
					$class = new classObj($layer_point);
					$symbolnr = $symbolid;
					$size = $icon_size - 1;
					$width = 1;
				}
			}
			$class->name = 'testClass' . $symbolid;
			$style = new styleObj($class);
			$style->symbol = $symbolnr;
			if (isset($size)) {
				$style->size = $size;
			}
			$style->width = $width;
			$style->color->setRGB(35, 109, 191);
			$style->outlinecolor->setRGB(0, 0, 0);
			try {
				if (MAPSERVERVERSION >= 800) {
					$img = $class->createLegendIcon($map, $class->layer, $icon_size, $icon_size);
				}
				else {
					$img = $class->createLegendIcon($icon_size, $icon_size);
				}
				if (MAPSERVERVERSION >= 800) {
					$img->save(IMAGEPATH . 'legende_' . $symbolid . '.png');
				}
				else {
					$img->saveImage(IMAGEPATH . 'legende_' . $symbolid . '.png');
				}
				$symbols[] = array(
					'id' => $symbolid,
					'value' => $symbol->name,
					'type' => $symbol->type,
					'bild' => $symbol->imagepath,
					'image' => IMAGEPATH . 'legende_' . $symbolid . '.png'
				);
			}
			catch (Exception $ex) {
				$this->add_message('error', 'Fehler beim Erzeugen des Icons für Symbol ' . $symbol->name);
			}
		}
		array_multisort(
			array_map(
					static function ($symbol) {
							return strtolower($symbol['value']);
					},
					$symbols
			),
			SORT_ASC,
			$symbols
		);
		return $symbols;
	}

  function getfonts(){
    $fontset = file(FONTSET);
    for($i = 0; $i < count($fontset); $i++){
      $explosion = explode(' ', trim($fontset[$i]));
      $first = trim(strtok(trim($fontset[$i]), " \n\t"));
      $second = trim(strtok(" \n\t"));
      $fonts['name'][] = $first;
      $fonts['filename'][] = $second;
    }
    return $fonts;
  }

  function createFontSampleImage($fontfile, $fontname){
    $image = imagecreatetruecolor(180,18);
    $backgroundColor = ImageColorAllocate ($image, 255, 255, 255);
    imagefill ($image, 0, 0, $backgroundColor);
    imagecolortransparent($image, $backgroundColor);
    $black = ImageColorAllocate ($image, 0, 0, 0);
    imagettftext($image, 11, 0, 3, 15, $black, dirname(FONTSET).'/'.$fontfile, $fontname);
    $imagename = rand(0, 1000000).'.png';
    imagepng($image, IMAGEPATH.$imagename);
    return TEMPPATH_REL.$imagename;
  }

  function FunktionenAnzeigen(){
		include_(CLASSPATH . 'Funktion.php');
    $this->main='funktionen.php';
    # Abfragen aller Funktionen
    $this->funktion = new Funktion($this);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

  function FunktionenFormular(){
		include_once (CLASSPATH . 'Funktion.php');
    $this->main='funktionen_formular.php';
    if ($this->formvars['selected_function_id']>0) {
      $this->funktion = new Funktion($this);
      $this->funktionen = $this->funktion->getFunktionen($this->formvars['selected_function_id'], NULL);
      $this->formvars['bezeichnung'] = $this->funktionen[0]['bezeichnung'];
    }
    $this->output();
  }

  function FunktionAnlegen() {
		include_(CLASSPATH . 'Funktion.php');
    $this->funktion = new Funktion($this);
    $ret = $this->funktion->NeuAnlegen($this->formvars);
    if ($ret[0]) {
      # Fehler beim Eintragen der Funktion
      $this->Meldung=$ret[1];
    }
    else {
      $neue_function_id = $ret[1];
      if ($ret[0]) {
        $this->Meldung=$ret[1];
      }
      else {
        $this->Meldung='Daten der Funktion erfolgreich eingetragen!';
      }
    }
    $this->formvars['selected_function_id'] = $neue_function_id;
    $this->FunktionenFormular();
  }

  function FunktionAendern(){
		include_(CLASSPATH . 'Funktion.php');
    $this->funktion = new Funktion($this);
    $ret = $this->funktion->Aendern($this->formvars);
    if($this->formvars['id'] != ''){
      $this->formvars['selected_function_id'] = $this->formvars['id'];
    }
    if ($ret[0]) {
      $this->Meldung=$ret[1];
    }
    else {
      $this->Meldung='Daten der Funktion erfolgreich eingetragen!';
    }
    $this->FunktionenFormular();
  }

  function FunktionLoeschen(){
		include_(CLASSPATH . 'Funktion.php');
    $this->main='funktionen.php';
    $this->funktion = new Funktion($this);
    $ret = $this->funktion->Loeschen($this->formvars);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

	function BenutzerdatenFormular() {
		$this->titel = 'Benutzerdaten Editor';
		$this->main = 'userdaten_formular.php';

		# Abfragen der Benutzerdaten wenn eine user_id zur Änderung selektiert ist
		if ($this->formvars['selected_user_id'] > 0) {
			$this->userdaten = $this->user->getUserDaten($this->formvars['selected_user_id'], '', '', $this->Stelle->id, $this->user->id, true);
			$this->user_stelle = new stelle($this->userdaten[0]['stelle_id'], $this->database);
			$this->formvars['nachname'] 									= $this->userdaten[0]['Name'];
			$this->formvars['vorname'] 										= $this->userdaten[0]['Vorname'];
			$this->formvars['loginname'] 									= $this->userdaten[0]['login_name'];
			$this->formvars['Namenszusatz'] 							= $this->userdaten[0]['Namenszusatz'];
			$this->formvars['password_setting_time'] 			= $this->userdaten[0]['password_setting_time'];
			$this->formvars['password_expired'] 					= $this->userdaten[0]['password_expired'];
			$this->formvars['start'] 											= $this->userdaten[0]['start'];
			$this->formvars['stop'] 											= $this->userdaten[0]['stop'];
			$this->formvars['ips']												= $this->userdaten[0]['ips'];
			$this->formvars['phon'] 											= $this->userdaten[0]['phon'];
			$this->formvars['email'] 											= $this->userdaten[0]['email'];
			$this->formvars['organisation'] 							= $this->userdaten[0]['organisation'];
			$this->formvars['position'] 									= $this->userdaten[0]['position'];
			$this->formvars['share_rollenlayer_allowed'] 	= $this->userdaten[0]['share_rollenlayer_allowed'];
			$this->formvars['archived'] 									= $this->userdaten[0]['archived'];
			$this->formvars['layer_data_import_allowed'] 	= $this->userdaten[0]['layer_data_import_allowed'];
			$this->formvars['agreement_accepted']					= $this->userdaten[0]['agreement_accepted'];
			# Abfragen der Stellen des Nutzers
			$this->selected_user = new user('', $this->formvars['selected_user_id'], $this->user->database, '', true);
			$this->formvars['selstellen'] = $this->selected_user->getStellen(0, true);
			# Abfragen der aktiven Layer des Nutzers
			if ($this->userdaten[0]['stelle_id'] != '') {
				$mapDB = new db_mapObj($this->userdaten[0]['stelle_id'], $this->formvars['selected_user_id']);
				$mapDB->nurAktiveLayer = true;
				$layerset = $mapDB->read_Layer(0, $this->Stelle->useLayerAliases, NULL);
				$this->active_layers = array_reverse($layerset['list']);
			}
		}
		# Abfragen aller möglichen Stellen
		$this->formvars['stellen'] = $this->Stelle->getStellen('Bezeichnung', $this->user->id);
		$this->output();
	}

  function BenutzerLöschen(){
    $this->selected_user = new user('', $this->formvars['selected_user_id'], $this->user->database, '', true);
		if (NUTZER_ARCHIVIEREN) {
			$this->selected_user->archivieren();
		}
		else {
			$stellen = $this->selected_user->getStellen(0);
			$this->user->rolle->deleteRollen($this->formvars['selected_user_id'], $stellen['ID']);
			$this->user->rolle->deleteMenue($this->formvars['selected_user_id'], $stellen['ID'], 0);
			$this->user->rolle->deleteGroups($this->formvars['selected_user_id'], $stellen['ID']);
			$this->user->rolle->deleteLayer($this->formvars['selected_user_id'], $stellen['ID'], 0);
			$this->selected_user->delete($this->formvars['selected_user_id']);
		}
		if ($this->formvars['nutzerstellen']) {
			$this->BenutzerNachStellenAnzeigen();
		}
		else {
			$this->BenutzerdatenAnzeigen();
		}
	}

	function BenutzerdatenAnzeigen() {
		if($this->formvars['order'] == ''){
			$this->formvars['order'] = 'Name,Vorname';
		}
		$this->titel='Benutzerdaten';
		$this->main='userdaten.php';
		# Abfragen aller Benutzer
		$this->userdaten = $this->user->getUserDaten(0, '', $this->formvars['order'], $this->Stelle->id, $this->user->id, true);
		$this->output();
	}

  function BenutzerdatenAnlegen() {
    $ret = $this->user->checkUserDaten($this->formvars);
    if ($ret[0]) {
      # Fehler bei der Formulareingabe
      $this->add_message('error', 'Fehler beim Eintragen in die Datenbank!<br>' . $ret[1]);
    }
    else {
      $ret = $this->user->NeuAnlegen($this->formvars);
      if ($ret[0]) {
        # Fehler beim Eintragen der Benutzerdaten
        $this->add_message('error', 'Fehler beim Eintragen in die Datenbank!<br>' . $ret[1]);
      }
      else {
        $this->formvars['selected_user_id'] = $ret[1];
        $stellen = array_filter(explode(', ', $this->formvars['selstellen']));
				for($i = 0; $i < count($stellen); $i++){
					$stelle = new stelle($stellen[$i], $this->database);
					$this->user->rolle->setRolle($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
					$this->user->rolle->setMenue($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
					$this->user->rolle->setLayer($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
					$layers = $stelle->getLayers(NULL);
					$this->user->rolle->setGroups($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id, $layers['ID']);
					# Layerparameter aktualisieren
					$stelle->updateLayerParams();
					$this->update_layer_parameter_in_rollen($stelle->id);
					$this->user->rolle->setSavedLayersFromDefaultUser($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
				}
        if ($ret[0]) {
          $this->Meldung = $ret[1];
        }
        else {
          $this->Meldung = 'Daten des Benutzers erfolgreich eingetragen!';
        }
      }
    }
  }

	function BenutzerdatenAendern() {
		$ret = $this->user->checkUserDaten($this->formvars);
		if ($ret[0]) {
			# Fehler bei der Formulareingabe
			$this->add_message('error', 'Fehler beim Eintragen in die Datenbank!<br>' . $ret[1]);
		}
		else {
			$stellen = array_filter(explode(', ',$this->formvars['selstellen']));
			$ret = $this->user->Aendern($this->formvars);
			if ($this->formvars['id'] != '') {
				$this->formvars['selected_user_id'] = $this->formvars['id'];
			}
			for ($i = 0; $i < count($stellen); $i++) {
				$stelle = new stelle($stellen[$i], $this->database);
				$this->user->rolle->setRolle($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
				$this->user->rolle->setMenue($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
				$this->user->rolle->setLayer($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
				$layers = $stelle->getLayers(NULL);
				$this->user->rolle->setGroups($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id, $layers['ID']);
				# Layerparameter aktualisieren
				$stelle->updateLayerParams();
				$this->update_layer_parameter_in_rollen($stelle->id);
				$this->user->rolle->setSavedLayersFromDefaultUser($this->formvars['selected_user_id'], $stelle->id, $stelle->default_user_id);
			}
      $this->selected_user = new user('',$this->formvars['selected_user_id'],$this->user->database, '', true);
      # Löschen der in der Selectbox entfernten Stellen
      $userstellen =  $this->selected_user->getStellen(0, true);
			$deletestellen = array();
      for ($i = 0; $i < count_or_0($userstellen['ID']); $i++) {
        $found = false;
        for ($j = 0; $j < count($stellen); $j++) {
          if ($stellen[$j] == $userstellen['ID'][$i]) {
            $found = true;
          }
        }
        if ($found == false) {
          $deletestellen[] = $userstellen['ID'][$i];
        }
      }
      $this->user->rolle->deleteRollen($this->formvars['selected_user_id'], $deletestellen);
      $this->user->rolle->deleteMenue($this->formvars['selected_user_id'], $deletestellen, 0);
      $this->user->rolle->deleteGroups($this->formvars['selected_user_id'], $deletestellen);
      $this->user->rolle->deleteLayer($this->formvars['selected_user_id'], $deletestellen, 0);
      # Überprüfen ob alte Stelle noch gültig ist
      $this->selected_user->checkstelle();
      if ($ret[0]) {
        # Fehler beim Ändern der Benutzerdaten
        $this->Meldung=$ret[1];
      }
      else {
        $this->Meldung='Daten des Benutzers erfolgreich eingetragen!';
      }
    }
    $this->BenutzerdatenFormular();
  }

	function BenutzerdatenLayerDeaktivieren(){
		$user_rolle = new rolle($this->formvars['user_id'], $this->formvars['stelle_id'], $this->database);
		$user_rolle->setOneLayer($this->formvars['layer_id'], 0);
	}

	function BenutzerNachStellenAnzeigen(){
    $this->titel='Benutzer-Stellen-Übersicht';
    $this->main='userstellendaten.php';
    # Abfragen aller Stellen
    $this->stellen = $this->Stelle->getStellen('Bezeichnung');
    for($i = 0; $i < count($this->stellen['ID']); $i++){
    	# Abfragen der Benutzer der Stelle
    	$stelle = new stelle($this->stellen['ID'][$i], $this->database);
    	$this->stellen['user'][$i] = $stelle->getUser();
    }
    $this->unassigned_users = $this->user->get_Unassigned_Users();
		$this->expired_users = $this->user->get_Expired_Users();
    $all_users = $this->user->getall_Users(NULL, $this->Stelle->id, $this->user->id);
    $this->user_count = count($all_users['ID']);
    $this->output();
  }

	function BenutzerderStelleAnzeigen(){
    $this->titel='Benutzer-Stellen-Übersicht';
    $this->main='userstellendaten.php';
		$this->stellen['ID'][0] = $this->Stelle->id;
		$this->stellen['Bezeichnung'][0] = $this->Stelle->Bezeichnung;
		# Abfragen der Benutzer der Stelle
		$this->stellen['user'][0] = $this->Stelle->getUser();
		$this->user_count = count($this->stellen['user'][0]['ID']);
    $this->output();
  }

	function connections_anzeigen() {
		$this->class = 'Connection';
		include_once(CLASSPATH . "{$this->class}.php");
		$this->myobjects = $this->class::find($this, $this->formvars['order'], $this->formvars['sort']);
		$this->main = 'myobject.php';
		$this->output();
	}
	
	function connections_create() {
		include_once(CLASSPATH . 'Connection.php');
		$this->myobject = new Connection($this);
		$this->myobject_create();
	}
	
	function connections_update() {
		include_once(CLASSPATH . 'Connection.php');
		$this->myobject = new Connection($this);
		$this->myobject_update();
	}
	
	function connections_delete() {
		include_once(CLASSPATH . 'Connection.php');
		$this->myobject = new Connection($this);
		$this->myobject_delete();
	}
	
	function datasources_anzeigen() {
		$this->class = 'DataSource';
		include_once(CLASSPATH . "{$this->class}.php");
		$this->myobjects = $this->class::find($this, '', $this->formvars['order'], $this->formvars['sort']);
		$this->main = 'myobject.php';
		$this->output();
	}
	
	function datasources_create() {
		include_once(CLASSPATH . 'DataSource.php');
		$this->myobject = new DataSource($this);
		$this->myobject_create();
	}
	
	function datasources_update() {
		include_once(CLASSPATH . 'DataSource.php');
		$this->myobject = new DataSource($this);
		$this->myobject_update();
	}
	
	function datasources_delete() {
		include_once(CLASSPATH . 'DataSource.php');
		$this->myobject = new DataSource($this);
		$this->myobject_delete();
	}	
	
	function myobject_create() {
		$this->myobject->data = formvars_strip($this->formvars, $this->myobject->getKeys(), 'keep');
		$results = $this->myobject->validate();
		if (count($results) > 0) {
			$result = array(
				'success' => false,
				'err_msg' => implode(
					'<br>',
					array_map(
						function($result) {
							return $result['msg'];
						},
						$results
					)
				)
			);
		}
		else {
			$results = $this->myobject->create();
			$result = $results[0];
		}
		# return success and id of created connection or error and error msg in json format
		$this->mime_type = 'application/json';
		$this->formvars['format'] = 'json';
		$this->qlayerset[0]['shape'] = array($result);
		$this->output();
	}		
	
	function myobject_update() {
		if ($this->formvars['id'] == '') {
			$result = array(
				'success' => false,
				'err_msg' => 'Datensatz kann nicht aktualisiert werden. Es muss eine id angegeben sein!'
			);
		}
		else {
			$this->myobject->find_by('id', $this->formvars['id']);
			if ($this->myobject->get('id') != $this->formvars['id']) {
				$result = array(
					'success' => false,
					'err_msg' => 'Der Datensatz mit der ID: '. $this->formvars['id'] . ' kann nicht aktualisiert werden, weil er in der Datenbank nicht existiert.'
				);
			}
			else {
				$this->myobject->data = formvars_strip($this->formvars, $this->myobject->getKeys(), 'keep');
				$results = $this->myobject->validate();
				if (count($results) > 0) {
					$result = array(
						'success' => false,
						'err_msg' => implode(
							', ',
							array_map(
								function($e) {
									return $e['type'] . ': ' . $e['msg'];
								},
								$results
							)
						)
					);
				}
				else {
					$results = $this->myobject->update($this->myobject->data, false);
					if ($results[0]['success']) {
						$result = array(
							'success' => true,
							'msg' => 'Der Datensatz mit der ID: ' . $this->myobject->get('id') . ' konnte erfolgreich aktualisiert werden.'
						);
					}
					else {
						$result = array(
							'success' => false,
							'err_msg' => 'Fehler beim Aktualisieren des Datensatzes mit der ID: ' . $this->myobject->get('id') . ' Meldung: ' . $results[0]['err_msg']
						);
					}

				}
			}
		}
		# return success or error with error message in json format
		$this->mime_type = 'application/json';
		$this->formvars['format'] = 'json';
		$this->qlayerset[0]['shape'] = array($result);
		$this->output();
	}

	function myobject_delete() {
		if ($this->formvars['id'] == '') {
			$result = array(
				'success' => false,
				'err_msg' => 'Datensatz kann nicht gelöscht werden. Es muss eine id angegeben sein!'
			);
		}
		else {
			$this->myobject->find_by('id', $this->formvars['id']);
			if ($this->myobject->get('id') != $this->formvars['id']) {
				$result = array(
					'success' => false,
					'err_msg' => 'Der Datensatz mit der ID: '. $this->formvars['id'] . ' kann nicht gelöscht werden, weil er in der Datenbank nicht existiert.'
				);
			}
			else {
				$result = $this->myobject->delete();
				if ($result['success']) {
					$num_affected_rows = $this->database->mysqli->affected_rows;
					switch ($num_affected_rows) {
						case (-1) : {
							$result = array(
								'success' => false,
								'err_msg' => 'Fehler beim Löschen des Datensatzes mit der ID: ' . $this->myobject->get('id')
							);
						} break;
						case (0) : {
							$result = array(
								'success' => false,
								'err_msg' => 'Achtung! Es wurde kein Datensatz gelöscht. Der Datensatz mit der ID: ' . $this->myobject->get('id') . ' ist nicht mehr vorhanden.'
							);
						} break;
						case (1) : {
							$result = array(
								'success' => true,
								'msg' => 'Datensatz mit der ID: ' . $this->myobject->get('id') . ' erfolgreich gelöscht.'
							);
						} break;
						default : {
							$result = array(
								'success' => false,
								'err_msg' => 'Achtung! Es wurden ' . $num_affected_rows . ' Datensätze gelöscht statt nur einer.'
							);
						}
					}
				}
			}
		}
		# return success or error with error msg in json format
		$this->mime_type = 'application/json';
		$this->formvars['format'] = 'json';
		$this->qlayerset[0]['shape'] = array($result);
		$this->output();
	}

	function cronjobs_anzeigen() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjobs = CronJob::find($this);
		$this->main = 'cronjobs.php';
		$this->output();
	}

	function cronjob_editieren() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjob = CronJob::find_by_id($this, $this->formvars['selected_cronjob_id']);
		$this->main = 'cronjob_formular.php';
		$this->output();
	}

	function cronjobs_anlegen() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjob = new CronJob($this);
		$this->cronjob->data = formvars_strip($this->formvars, $this->cronjob->getKeys(), 'keep');
		$this->cronjob->set('user_id', $this->user->id);
		$this->cronjob->set('stelle_id', $this->Stelle->id);
		$this->cronjob->set('query', $this->formvars['query']);
		$results = $this->cronjob->validate();
		if (empty($results)) {
			$results = $this->cronjob->create();
		}
		if ($results[0]['success']) {
			$this->add_message('notice', 'Job erfolgreich angelegt.');
			$this->cronjobs = CronJob::find($this);
			$this->main = 'cronjobs.php';
		}
		else {
			$this->add_message('array', array_values($results));
			$this->main = 'cronjob_formular.php';
		}
		$this->output();
	}

	function cronjob_update() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjob = CronJob::find_by_id($this, $this->formvars['id']);
		$this->cronjob->data = formvars_strip($this->formvars, $this->cronjob->getKeys(), 'keep');
		$results = $this->cronjob->update();
		if ($results[0]['success']) {
			#		$this->cronjob->set('query', $this->cronjob->get('query'));
			$this->cronjobs = CronJob::find($this);
			$this->main = 'cronjobs.php';
		}
		else {
			$this->add_message('error', 'Fehler beim Eintragen in die Datenbank!<br>' . $result);
			$this->main = 'cronjob_formular.php';
		}
		$this->output();
	}

	function cronjob_delete() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjob = CronJob::find_by_id($this, $this->formvars['selected_cronjob_id']);
		$this->cronjob->delete();
		$this->cronjobs = CronJob::find($this);
		$this->main = 'cronjobs.php';
		$this->output();
	}

	function crontab_schreiben() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjobs = CronJob::find($this);

		# erzeugt die Zeilen für den crontab
		$crontab_lines = array('gisadmin' => array(), 'root' => array());
		foreach ($this->cronjobs AS $cronjob) {
			if ($cronjob->get('aktiv')) {
				$crontab_lines[$cronjob->get('user')][] = $cronjob->get_crontab_line();
			}
		}
		$crontab_dir = '/var/www/cron/';
		if (!file_exists($crontab_dir . 'gisadmin')) {
			mkdir($crontab_dir . 'gisadmin');
		}
		if (!file_exists($crontab_dir . 'root')) {
			mkdir($crontab_dir . 'root');
		}
		# schreibt die Zeilen in die crontab Dateien von root und gisadmin falls vorhanden
		foreach ($crontab_lines AS $user => $lines) {
			$crontab_file = $crontab_dir . $user . '/' . substr(APPLVERSION, 0, -1); 
			// echo '<br>' . $user . ':' . $crontab_file;
			$fp = fopen($crontab_file, 'w');
			if (count($lines) > 0) {
				foreach($lines AS $line) {
					fwrite($fp, $line . PHP_EOL);
				}
			}
			else {
				fwrite($fp, '# no crontab lines defined in kvwmap!');
			}
			fclose($fp);
		}

		# crontab starten
#		exec('crontab -u www-data ' . $crontab_file);

		# crontab datei löschen
#		unlink($crontab_file);

		# crontab Zeilen anzeigen
		$this->crontab_lines = $crontab_lines;
		$this->main = 'crontab.php';
		$this->output();
	}

	function zweifaktor_authentifizierung() {
		include_once(CLASSPATH . 'ZweiFaktor.php');
		$this->main = 'zweifaktors.php';
		$this->output();
	}	
	
	function get_copyrights(){
		$sql = "
			SELECT 
				GROUP_CONCAT(" . ($this->Stelle->useLayerAliases ? 'COALESCE(l.`alias`, l.`Name`)' : 'l.`Name`') . " SEPARATOR ', ') as layer, 
				d.`beschreibung`
			FROM 
				`datasources` as d JOIN
				`layer_datasources` as ld ON ld.datasource_id = d.id JOIN
				`layer` as l ON l.Layer_ID = ld.layer_id JOIN
				`u_rolle2used_layer` r ON r.layer_id = ld.layer_id AND r.user_id = " . $this->user->id . " AND r.stelle_id = " . $this->Stelle->id . "
			WHERE
				r.`aktivStatus` != '0'
			GROUP BY
				d.`id`
		";
		$ret = $this->database->execSQL($sql, 4, 1);
		if ($ret[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$output = '<table>';
		while ($rs = $this->database->result->fetch_assoc()){
			$rs['layer'] = replace_params_rolle($rs['layer']);
      $output .= '<tr>
							<td>' . $rs['layer'] . '</td>
							<td>' . set_href($rs['beschreibung']) . '</td>
						</tr>';
    }
		$output .= '</table>';
		return $output;
	}

  function LayerUebersicht() {
		$this->sanitize([
			'order' => 'text'
		]);
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Themenübersicht';
    $this->main='layer_uebersicht.php';
    # Abfragen aller Layer
    $this->layers = $mapDB->getall_Layer($this->formvars['order'], true);
		$this->layers_in_stelle = $mapDB->read_Layer(0);
		$this->groups = $mapDB->read_Groups(true, 'Gruppenname');
    $this->output();
  }

	function save_legend_role_parameters(){
		# Scrollposition der Legende wird gespeichert
  	$this->user->rolle->setScrollPosition(value_of($this->formvars, 'scrollposition'));
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Wenn ein Button im Kartenfenster gewählt wurde,
    # werden auch die Einstellungen aus der Legende übernommen
    $this->user->rolle->setAktivLayer($this->formvars, $this->Stelle->id, $this->user->id);
    $this->user->rolle->setQueryStatus($this->formvars);
	}

	function neuLaden() {
		$this->sanitize([
			'selected_rollenlayer_id' => 'int',
			'delete_rollenlayer_type' => 'text'
		]);
		$this->save_legend_role_parameters();
		if (
			in_array(value_of($this->formvars, 'last_button'), array('zoomin', 'zoomout', 'recentre', 'pquery', 'touchquery', 'ppquery', 'polygonquery'))
		) {
			// das ist für den Fall, dass ein Button schon angeklickt wurde, aber die Aktion nicht ausgeführt wurde
			$this->user->rolle->set_selected_button($this->formvars['last_button']);
		}
		if (value_of($this->formvars, 'delete_rollenlayer') != '') {
			$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
			$mapDB->deleteRollenlayer(NULL, $this->formvars['delete_rollenlayer_type']);
		}
		# Karteninformationen lesen
		$this->loadMap('DataBase', array(), ($this->formvars['strict_layer_name'] ? true : false));
		# zwischenspeichern des vorherigen Maßstabs
		$oldscale=round($this->map_scaledenom);
		# zoom_to_max_layer_extent
		if (value_of($this->formvars, 'zoom_layer_id') != '') {
			$this->zoom_to_max_layer_extent($this->formvars['zoom_layer_id']);
		}
		if (value_of($this->formvars, 'nScale') != '' AND $this->formvars['nScale'] != $oldscale) {
			# Zoom auf den in der Maßstabsauswahl ausgewählten Maßstab
			# wenn er sich von der vorherigen Maßstabszahl unterscheidet
			# (das heißt wenn eine andere Zahl eingegeben wurde)
			$this->scaleMap($this->formvars['nScale']);
			$this->user->rolle->saveSettings($this->map->extent);
			$this->user->rolle->readSettings();
		}
    # Zoom auf den in der Referenzkarte ausgewählten Ausschnitt
    if (value_of($this->formvars, 'refmap_x') > 0) {
      $this->zoomToRefExt();
    }
    else {
      # Wenn ein Navigationskommando ausgewählt/übergeben wurde
      # Zoom/Pan auf den in der Karte ausgewählten Ausschnitt
      if (value_of($this->formvars, 'CMD') != '') {
        $this->navMap($this->formvars['CMD']);
      }
    }
  }

	/**
	 * Function activate only the layer with $layer_id if given
	 * and zoom to it for rolle if $zoom_to_layer_extent is true
	 * All other layers has been reseted to status 0.
	 * And it reads rolle settings.
	 * @param int $layer_id
	 * @param bool $zoom_to_layer_extent
	 */
	function activate_layer_only($layer_id, $zoom_to_layer_extent = false) {
		if ($layer_id) {
			$this->reset_layers(NULL);
			$this->user->rolle->setOneLayer($layer_id, 1);
		}
		$this->user->rolle->readSettings();
		$this->loadMap('DataBase');
		if ($zoom_to_layer_extent) {
			$this->zoomToMaxLayerExtent($layer_id);
		}
	}

# 2006-03-20 pk
  function zoomToStoredMapExtent($storetime, $user_id){
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # Abfragen der gespeicherten Kartenausdehnung
    $ret=$this->user->rolle->getConsume($storetime, $user_id);
    if ($ret3[0]) {
      $this->errmsg="Der gespeicherte Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
    }
    else {
			$rect = rectObj($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
			if($ret[1]['epsg_code'] != '' AND $ret[1]['epsg_code'] != $this->user->rolle->epsg_code){
				$projFROM = new projectionObj("init=epsg:" . $ret[1]['epsg_code']);
				$projTO = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
				$rect->project($projFROM, $projTO);
			}
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
    	if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
      #echo '<br>gewechselt auf Einstellung von:'.$this->consumetime;
    }
    $this->saveMap('');
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->output();
  }

  function setPrevMapExtent($consumetime) {
    $currentextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $prevextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND
					round($currentextent->minx, 2) == round($prevextent->minx, 2) AND
					round($currentextent->miny, 2) == round($prevextent->miny, 2) AND
					round($currentextent->maxx, 2) == round($prevextent->maxx, 2) AND
					round($currentextent->maxy, 2) == round($prevextent->maxy, 2)){
      # Setzen des next Wertes des vorherigen Kartenausschnittes
      $prevtime=$ret[1]['prev'];
      $this->user->rolle->newtime = $prevtime;
      if (!($prevtime=='' OR $prevtime=='2006-09-29 12:55:50')) {
        $ret=$this->user->rolle->updateNextConsumeTime($prevtime,$consumetime);
        if ($ret[0]) {
          $this->errmsg="Der Nachfolger für den letzten Kartenausschnitt konnte nicht eingetragen werden.<br>" . $ret[1];
        }
        else {
          # Abfragen der vorherigen Kartenausdehnung
          $ret=$this->user->rolle->getConsume($prevtime);
          if ($ret[0]) {
            $this->errmsg="Der letzte Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
          }
          else {
           $consumetime = $prevtime;
           $prevextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
            #echo '<br>gewechselt auf Einstellung von:'.$consumetime;
          }
        }
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($prevtime);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function setNextMapExtent($consumetime) {
    $currentextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $nextextent = rectObj($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    # Abfragen der nächsten Kartenausdehnung
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND (string)$currentextent->minx == (string)$nextextent->minx AND (string)$currentextent->miny == (string)$nextextent->miny AND (string)$currentextent->maxx == (string)$nextextent->maxx AND (string)$currentextent->maxy == (string)$nextextent->maxy){
      $lasttime = $nexttime;
      $nexttime=$ret[1]['next'];
      if($nexttime == NULL){
        $nexttime = $lasttime;
        $i = 100;
      }
      $this->user->rolle->newtime = $nexttime;
      $ret=$this->user->rolle->getConsume($nexttime);
      if ($ret[0]) {
        $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
      }
      else {
        $nextextent->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
        #echo '<br>gewechselt auf Einstellung von:'.$this->consumetime;
      }
      $i++;
    }
    $this->user->rolle->set_last_time_id($ret[1]['time_id']);
    $this->map->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function mapCommentForm() {
    $this->main='MapCommentForm.php';
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->loadMap('DataBase');
    $this->drawMap();
    $this->output();
  }

	function layerCommentForm() {
		$this->neuLaden();
		$this->saveMap('');
		$currenttime=date('Y-m-d H:i:s',time());
		$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->main='LayerCommentForm.php';
    $this->drawMap();
    $this->output();
  }

  function mapCommentStore() {
    $ret=$this->user->rolle->insertMapComment($this->formvars['consumetime'],$this->formvars['comment'], $this->formvars['public']);
		$this->add_message('notice', 'Ausschnitt gespeichert.');
    $ret=$this->user->rolle->getConsume($this->formvars['consumetime']);
    if ($ret[0]) {
      $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>" . $ret[1];
    }
    else {
      $this->consumetime=$ret[1]['time_id'];
      $this->user->rolle->newtime = $this->consumetime;
    }
    $this->loadMap('DataBase');
    $this->drawMap();
    $this->output();
  }

	function layerCommentStore(){
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->loadMap('DataBase');
    $ret=$this->user->rolle->insertLayerComment($this->layerset, $this->formvars['comment']);
    $this->add_message('notice', 'Themenauswahl gespeichert.');
    $this->drawMap();
    $this->output();
  }

  function DeleteStoredMapExtent(){
    $this->user->rolle->deleteMapComment($this->formvars['storetime']);
    $this->mapCommentSelectForm();
  }

	function DeleteStoredLayers(){
    $this->user->rolle->deleteLayerComment($this->formvars['id']);
    $this->layerCommentSelectForm();
  }

  function mapCommentSelectForm() {
    $this->main='MapCommentSelectForm.php';
		if($this->formvars['order'] == '')$this->formvars['order'] = 'time_id DESC';
    $ret=$this->user->rolle->getMapComments(NULL, true, $this->formvars['order']);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine gespeicherten Kartenausschnitte abgefragt werden.<br>'.$ret[1];
    }
    else {
      $this->mapComments=$ret[1];
    }
    $this->output();
  }

	function layerCommentSelectForm() {
    $this->main='LayerCommentSelectForm.php';
    $ret=$this->user->rolle->getLayerComments(NULL, $this->user->id);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine gespeicherten Themen abgefragt werden.<br>'.$ret[1];
    }
    else {
      $this->layerComments=$ret[1];
    }
    $this->output();
  }

	function layerCommentLoad() {
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$groups = $mapDB->read_Groups();
		$ret = $this->user->rolle->getLayerComments(
			$this->formvars['id'],
			($this->formvars['user_id'] != '' ? $this->formvars['user_id'] : $this->user->id)
		);
		if ($ret[1] == NULL) {
			$this->Fehlermeldung = 'Es konnten keine gespeicherten Themen abgefragt werden.<br>' . $ret[1];
		}
		else {
			$layerset = $this->user->rolle->getLayer('');
			for ($i = 0; $i < count($layerset); $i++){
				$formvars['thema'.$layerset[$i]['Layer_ID']] = 0;		# erstmal alle ausschalten
				$formvars['qLayer'.$layerset[$i]['Layer_ID']] = 0;		# erstmal alle ausschalten
			}
			foreach($groups as $group){
				if($group['obergruppe'] == ''){
					$formvars['group_'.$group['id']] = 0;		# und alle Obergruppen zuklappen
				}
			}
      $layer_ids = explode(',', $ret[1][0]['layers']);
			foreach($layer_ids as $layer_id){
				$formvars['thema' . $layer_id] = 1;
				$groupid = $layerset['layer_ids'][$layer_id]['Gruppe'];
				do{
					$formvars['group_'.$groupid] = 1;
					$groupid = $groups[$groupid]['obergruppe'];
				} while ($groupid != '');
			}
			$query_ids = explode(',', $ret[1][0]['query']);
			foreach($query_ids as $layer_id){
				$formvars['qLayer' . $layer_id] = 1;
			}
			$this->user->rolle->setAktivLayer($formvars, $this->Stelle->id, $this->user->id, true);
			$this->user->rolle->setQueryStatus($formvars);
			$this->user->rolle->setGroupStatus($formvars);
		}
		$this->loadMap('DataBase');
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->output();
	}

  function composePolygonWKTString($pathx,$pathy,$minx,$miny,$scale) {
    # Bildung des WKT-Stings für das Umringpolygon aus den Formularwerten
    $pix_rechts=explode(',',$pathx);
    # Prüfen ob die übergebenen Werte 3 Eckpunkte für ein Polygon beinhalten
    if (count($pix_rechts)<3) { # die Anzahl der übergebenen Rechtswerte ist schon mal zu gering
      # es kann sich nicht um ein Polygon handeln
      $umring='';
    }
    else {
      if($minx AND $miny AND $scale){
        $pix_hoch=explode(',',$pathy);
        $x=round($minx+$pix_rechts[0]*$scale,2);
        $y=round($miny+$pix_hoch[0]*$scale,2);
        $umring ='POLYGON(('.$x.' '.$y;
        for ($i=1;$i<count($pix_rechts);$i++) {
          $x=round($minx+$pix_rechts[$i]*$scale,2);
          $y=round($miny+$pix_hoch[$i]*$scale,2);
          $umring.=','.$x.' '.$y;
        }
        $x=round($minx+$pix_rechts[0]*$scale,2);
        $y=round($miny+$pix_hoch[0]*$scale,2);
        $umring.=','.$x.' '.$y.'))';
      }
      else{
        $pix_hoch=explode(',',$pathy);
        $x = $pix_rechts[0];
        $y = $pix_hoch[0];
        $umring ='POLYGON(('.$x.' '.$y;
        for ($i = 1; $i < count($pix_rechts); $i++) {
          $x = $pix_rechts[$i];
          $y = $pix_hoch[$i];
          $umring.=','.$x.' '.$y;
        }
        $x = $pix_rechts[0];
        $y = $pix_hoch[0];
        $umring.=','.$x.' '.$y.'))';
      }
    }
    return $umring;
  }

  function composePolygon2Array($umring,$minx,$miny,$scale) {
    # Bildung des Polygonarrays zur SVG-Ausgabe aus Umringpolygon
    $ret = NULL;
    $umring_teil=strrpos($umring,'((')+1;
    $umring_path=substr($umring,$umring_teil,count($umring_teil)-3);
    $umring_paare=explode(',',$umring_path);
    if(count($umring_paare) > 2){
      $umring_xy=explode(' ',$umring_paare[0]);
      $pathx=round(($umring_xy[0]-$minx)/$scale,2);
      $pathy=round(($umring_xy[1]-$miny)/$scale,2);
      for ($i=1;$i<count($umring_paare)-1;$i++) {
        $umring_xy=explode(' ',$umring_paare[$i]);
        $pathx.=','.round(($umring_xy[0]-$minx)/$scale,2);
        $pathy.=','.round(($umring_xy[1]-$miny)/$scale,2);
      }
      $ret['pathx'] = $pathx;
      $ret['pathy'] = $pathy;
    }
    return $ret;
  }

  function composePoint2Array($point,$minx,$miny,$scale) {
    # Bildung der Textposition zur SVG-Ausgabe
    $point_teil=strrpos($point,'(')+1;
    $point_paar=substr($point,$point_teil,count($point_teil)-2);
    #echo '$point_paar: '.$point_paar;
    $point_xy=explode(' ',$point_paar);
    $pathx=round(($point_xy[0]-$minx)/$scale,2);
    $pathy=round(($point_xy[1]-$miny)/$scale,2);
    $ret['loc_x'] = $pathx;
    $ret['loc_y'] = $pathy;
    return $ret;
  }

  function composeArrayFromPolygonWKTString($umring) {
    $points=explode(',',$umring);
    for ($i=0;$i<count($points)-1;$i++) {
      $koord=explode(' ',$points[$i]);
      $polyarray[$i]['x']=$koord[0];
      $polyarray[$i]['y']=$koord[1];
    }
    return $polyarray;
  }

  function pixel2weltKoordPath($path, $minx, $miny, $pixsize) {
    $explosion = explode(' ', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != 'M' AND $explosion[$i] != ''){
        $explosion[$i] = ($explosion[$i] * $pixsize) + $minx;
        $explosion[$i+1] = ($explosion[$i+1] * $pixsize) + $miny;
        $i++;
      }
    }
    $path = '';
    for($i = 0; $i < count($explosion); $i++){
      $path .= $explosion[$i].' ';
    }
    return $path;
  }

  function welt2pixelKoordPath($pathWelt, $minx, $miny, $pixsize) {
    # Umrechnung von Weltkoordinaten in Bildkoordinaten
    $explosion = explode(' ', $pathWelt);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != 'M' AND $explosion[$i] != ''){
        $explosion[$i] = round(($explosion[$i] - $minx) / $pixsize);
        $explosion[$i+1] = round(($explosion[$i+1] - $miny) / $pixsize);
        $i++;
      }
    }
    $path = '';
    for($i = 0; $i < count($explosion); $i++){
      $path .= $explosion[$i].' ';
    }
    return $path;
  }

  function welt2pixelKoordWKT($weltwkt, $minx, $miny, $pixsize){
    $ebene1 = explode('(((', $weltwkt);
    if(count($ebene1) == 1){    # POLYGON
      $type = 'POLYGON';
      $ebene1 = explode('((', $weltwkt);
      $pixelwkt = $ebene1[0].'((';
    }
    else{
      $ebene1[1] = str_replace(')))', '', $ebene1[1]);
      $pixelwkt = $ebene1[0].'(((';
    }
    $ebene2 = explode(')),((', $ebene1[1]);
    for($i = 0; $i < count($ebene2); $i++){
      if($i > 0)$pixelwkt .= ')),((';
      $ebene3 = explode('),(', $ebene2[$i]);
      for($j = 0; $j < count($ebene3); $j++){
        if($j > 0)$pixelwkt .= '),(';
        $coordpair = explode(',',$ebene3[$j]);
        for($k = 0; $k < count($coordpair); $k++){
          $coord = explode(' ',$coordpair[$k]);
          $x = round($coord[0] - $minx) / $pixsize;
          $y = round($coord[1] - $miny) / $pixsize;
          if($k > 0){
            $pixelwkt .= ',';
          }
          $pixelwkt .= $x.' '.$y;
        }
      }
    }
    if($type == 'POLYGON'){
      $pixelwkt .= '))';
    }
    else{
      $pixelwkt .= ')))';
    }
    return $pixelwkt;
  }

  function pixel2weltKoordWKT($pixelwkt, $minx, $miny, $pixsize){
    $ebene1 = explode('(((', $pixelwkt);
    if(count($ebene1) == 1){    # POLYGON
      $type = 'POLYGON';
      $ebene1 = explode('((', $pixelwkt);
      $weltwkt = $ebene1[0].'((';
    }
    else{     # MULTIPOYGON
      $ebene1[1] = str_replace(')))', '', $ebene1[1]);
      $weltwkt = $ebene1[0].'(((';
    }
    $ebene2 = explode(')),((', $ebene1[1]);
    for($i = 0; $i < count($ebene2); $i++){
      if($i > 0)$weltwkt .= ')),((';
      $ebene3 = explode('),(', $ebene2[$i]);
      for($j = 0; $j < count($ebene3); $j++){
        if($j > 0)$weltwkt .= '),(';
        $coordpair = explode(',',$ebene3[$j]);
        for($k = 0; $k < count($coordpair); $k++){
          $coord = explode(' ',$coordpair[$k]);
          $x = ($coord[0] * $pixsize) + $minx;
          $y = ($coord[1] * $pixsize) + $miny;
          if($k > 0){
            $weltwkt .= ',';
          }
          $weltwkt .= $x.' '.$y;
        }
      }
    }
    if($type == 'POLYGON'){
      $weltwkt .= '))';
    }
    else{
      $weltwkt .= ')))';
    }
    return $weltwkt;
  }

  function pixel2weltKoord($pathx,$pathy) {
    # Umrechnung von Bildkoordinaten mit Ursprung links unten, hochwert nach oben zählend
    # in Koordinaten des übergeordeten Koordinatensystems
    # die x-Werte der Bildkoordinaten sind als textstrings getrennt mit Kommas in pathPixX
    # dito für y-Werte der Bildkoordinaten
    # Konvertieren des Textes mit Koordinatenwerten in ein Array
    $listePixX=explode(',',$pathx);
    $listePixY=explode(',',$pathy);
    # Umrechnung von Pixelkoordinaten in Weltkoordinaten und Zuweisen in einer Liste
    # Verwendet werden dabei die aktuellen Einstellungen der GUI für minx, miny und pixsize
    for ($i=0;$i<count($listePixX);$i++) {
      $listeWelt[$i]=new point($listePixX[$i],$listePixY[$i]);
      $listeWelt[$i]->pixel2welt($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
    }
    # Übergeben wird eine Liste mit Punktobjekten, die jeweils die x und y Werte im
    # Weltkoordinatensystem haben
    return $listeWelt;
  }

  function welt2pixelKoord($polygonWelt) {
    # Umrechnung von Weltkoordinaten in Bildkoordinaten
    # mit Ursprung links unten, hochwert nach oben zählend
    $anzPunkte=count($polygonWelt);
    # Umrechnen der Punkte und zuweisen der Bildkoordinaten in ein Textstring für jeweils x und y
    # getrennt durch Komma
    $obj=$polygonWelt[0];
    $obj->welt2pixel($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
    $pathxyPixel['x']=$obj->x;
    $pathxyPixel['y']=$obj->y;
    for ($i=1;$i<$anzPunkte;$i++) {
      $obj=$polygonWelt[$i];
      $obj->welt2pixel($this->map->extent->minx,$this->map->extent->miny,$this->user->rolle->pixsize);
      $pathxyPixel['x'].=','.$obj->x;
      $pathxyPixel['y'].=','.$obj->y;
    }
    return $pathxyPixel;
  }

	function reduce_mapwidth($width_reduction, $height_reduction = 0){
		# Diese Funktion reduziert die aktuelle Kartenbildbreite um $width_reduction Pixel (und optional die Kartenbildhöhe um $height_reduction Pixel), damit das Kartenbild in Fachschalen nicht zu groß erscheint.
		# Diese reduzierte Breite wird aber nicht in der Datenbank gespeichert, sondern gilt nur solange man in der Fachschale bleibt.
		# Außerdem wird bei Bedarf der aktuelle Maßstab berechnet und zurückgeliefert (er wird berechnet, weil ein loadmap() ja noch nicht aufgerufen wurde).
		# Mit diesem Maßstab kann dann einmal beim ersten Aufruf der Fachschale von der Hauptkarte aus nach dem loadmap() der Extent wieder so angepasst werden, dass der ursprüngliche Maßstab erhalten bleibt.
		# Dieser verkleinerte Extent wird wiederum in der Datenbank gespeichert. In der Datenbank steht dann also weiterhin die ursprüngliche Kartenbildgröße und der (dazu eigentlich nicht passende) in der Breite verkleinerte Extent.
		# Damit der Extent aber nur dann angepasst wird, wenn es notwendig ist (nämlich wenn man von der Hauptkarte kommt), wird der Maßstab nur berechnet, wenn Kartenbildgröße und Extent zusammenpassen.
		# Am "Nichtzusammenpassen" von Kartenbildgröße und Extent wird also erkannt, dass der Extent schon einmal verkleinert wurde.
		$scale = NULL;
		$this->formvars['width_reduction'] = $width_reduction;
		$this->formvars['height_reduction'] = $height_reduction;
		$width = $this->user->rolle->nImageWidth;
		$height = $this->user->rolle->nImageHeight;
		$extentwidth = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
		$extentheight = $this->user->rolle->oGeorefExt->maxy - $this->user->rolle->oGeorefExt->miny;
		$ratio_image = round($width/$height, 2);
		$ratio_extent = round($extentwidth/$extentheight, 2);
		if($ratio_image == $ratio_extent){
			$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
			if($this->user->rolle->epsg_code == 4326){$unit = MS_DD;} else {$unit = MS_METERS;}
			$md = ($width-1)/(96 * InchesPerUnit($unit, $center_y));
			$gd = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
			$scale = $gd/$md;
		}
		$width = $width - $width_reduction;
		$height = $height - $height_reduction;
		if($this->user->rolle->hideMenue == 1){$width = $width - 195;}
		if($this->user->rolle->hideLegend == 1){$width = $width - 254;}
		$this->user->rolle->nImageWidth = $width;
		$this->user->rolle->nImageHeight = $height;
		return $scale;
	}

  function getFunktionen() {
    $this->Stelle->getFunktionen();
  }

  function rollenwahl($Stelle_ID) {
		include_once(CLASSPATH . 'FormObject.php');
    $this->user->Stellen = $this->user->getStellen(0);
    $this->Hinweis .= 'Aktuelle Stellen_ID: ' . $Stelle_ID;
    $StellenFormObj = new FormObject("Stelle_ID", "select", $this->user->Stellen['ID'], $Stelle_ID, $this->user->Stellen['Bezeichnung'], 'max12', "", "", NULL , NULL, "vertical-align: middle;");
    # hinzufügen von Javascript welches dafür sorgt, dass die Angegebenen Werte abgefragt werden
    # und die genannten Formularobjekte mit diesen Werten bestückt werden
    # übergebene Werte
    # SQL für die Abfrage, es darf nur eine Zeile zurückkommen
    # Liste der Formularelementnamen, die betroffen sind in der Reihenfolge,
    # wie die Spalten in der Abfrage
    $select =  "nZoomFactor,gui, CASE WHEN auto_map_resize = 1 THEN 'auto' ELSE CONCAT(nImageWidth,'x',nImageHeight) END AS mapsize";
    $select .= ",CONCAT(minx,' ',miny,',',maxx,' ',maxy) AS newExtent, epsg_code, tooltipquery, runningcoords, showmapfunctions, showlayeroptions, showrollenfilter, menu_auto_close, menue_buttons, DATE_FORMAT(hist_timestamp,'%d.%m.%Y %T') as hist_timestamp";
    $from = "rolle";
    $where = "stelle_id='+this.form.Stelle_ID.value+' AND user_id=" . $this->user->id;
    $StellenFormObj->addJavaScript(
			"onchange",
			"$('#sign_in_stelle').show(); " . ((array_key_exists('stelle_angemeldet', $_SESSION) AND $_SESSION['stelle_angemeldet'] === true) ? "ahah('index.php','go=getRow&select=".urlencode($select)."&from=" . $from."&where=" . $where."',new Array(nZoomFactor,gui,mapsize,newExtent,epsg_code,tooltipquery,runningcoords,showmapfunctions,showlayeroptions,showrollenfilter,menu_auto_close,menue_buttons,hist_timestamp));" : "")
			. ((value_of($this->formvars, 'show_layer_parameter')) ? "ahah('index.php','go=getLayerParamsForm&stelle_id='+document.GUI.Stelle_ID.value, new Array(document.getElementById('layer_parameters_div')), new Array('sethtml'))" : "")
		);
    #echo URL.APPLVERSION."index.php?go=getRow&select=".urlencode($select)."&from=" . $from."&where=stelle_id=3 AND user_id=7";
    $StellenFormObj->outputHTML();
    $this->StellenForm = $StellenFormObj;
    $this->main = 'rollenwahl.php';
    # Suchen nach verfügbaren Layouts
    # aus dem Stammordner layouts (vom System angebotene)
    $this->layoutfiles = searchdir('layouts/', false);
		for ($i = 0; $i < count($this->layoutfiles); $i++) {
			if (strpos($this->layoutfiles[$i], '.php') > 0 ) {
				$this->guifiles[] = $this->layoutfiles[$i];
			}
		}
		# aus dem Customordner (vom Nutzer hinzugefügte Layouts)
		$this->customlayoutfiles = searchdir(CUSTOM_PATH . 'layouts/gui/', false);
		for ($i = 0; $i < count($this->customlayoutfiles); $i++) {
			if (strpos($this->customlayoutfiles[$i], '.php') > 0) {
				$this->guifiles[] = $this->customlayoutfiles[$i];
			}
		}
    # Abfrage der verfügbaren Kartenprojektionen in PostGIS (Tabelle spatial_ref_sys)
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    # Voreinstellen des aktuellen EPSG-Codes der Rolle
    if (value_of($this->formvars, 'epsg_code') == '') {
      $this->formvars['epsg_code']=$this->user->rolle->epsg_code;
    }
    # Abfragen der Farben für die Suchergebnisse
    $this->result_colors = read_colors($this->database);
		# Speichern des neuen Passworts, falls übergeben
		if ($this->formvars['go'] == 'Stelle_waehlen_Passwort_aendern') {
			if ($this->formvars['passwort'] == '') {
				# Test ob altes Passwort korrekt angegeben
				$this->PasswordError = 'Geben Sie bitte ihr aktuelles Passwort an.';
			}
			else {
				$user = new user($this->user->login_name, 0, $this->database, $this->formvars['passwort']);
				if ($user->login_name != $this->user->login_name) {
					$this->PasswordError = 'Das angegebene Passwort stimmt nicht mit dem aktuellen Passwort überein.';
				}
				else {
					$this->PasswordError = isPasswordValide($this->formvars['passwort'], $this->formvars['new_password'], $this->formvars['new_password_2']);		# Test ob neues Passwort in Ordnung
					if($this->PasswordError == ''){
						$this->user->setNewPassword($this->formvars['new_password']);
						$this->add_message('notice', 'Password ist erfolgreich geändert worden.');
					}
				}
			}
		}
	}

	function belated_file_upload() {
		include_once(CLASSPATH . 'BelatedFile.php');
		$this->belated_files = BelatedFile::find(
			$this,
			'user_id = ' . $this->user->id,
			($this->formvars['order'] == '' ? 'layer_id, dataset_id, name, lastmodified' : '')
		);
		$this->main = 'belated_file_upload.php';
		$this->output();
	}
	
	function belated_file_upload_speichern() {
		include_once(CLASSPATH . 'BelatedFile.php');
		if (isset($_FILES['uploadfile'])) {
			$this->user->rolle->upload_only_file_metadata = 0;
			$filename = $_FILES['uploadfile']['name'];
			$filetype = $_FILES['uploadfile']['type'];
			$filesize = $_FILES['uploadfile']['size'];
			
			$this->belated_files = BelatedFile::find(
				$this,
				"user_id = " . $this->user->id . " AND 
				 name = '" . $filename . "' AND 
				 size = " . $filesize . " AND 
				 lastmodified = " . $this->formvars['lastmodified']
			);
			
			for ($b = 0; $b < count($this->belated_files); $b++) {
				$belated_file = $this->belated_files[$b];
				$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
				$layerset = $this->user->rolle->getLayer($belated_file->data['layer_id']);				
				$this->formvars['selected_layer_id'] = $belated_file->data['layer_id'];
				$this->formvars['value_' . $layerset[0]['maintable'] . '_oid'] = $belated_file->data['dataset_id'];
				$this->formvars['operator_' . $layerset[0]['maintable'] . '_oid'] = '=';
				$this->formvars['no_output'] = true;
				$this->GenerischeSuche_Suchen();
				$attributes = $this->qlayerset[0]['attributes'];
				$fieldnames = array();
				$this->formvars['changed_' . $belated_file->data['layer_id'] . '_' . str_replace('-', '', $belated_file->data['dataset_id'])] = 1;
				$fieldnames[0] = $belated_file->data['layer_id'] . ';' . $belated_file->data['attribute_name'] . ';' . $layerset[0]['maintable'] . ';' . $belated_file->data['dataset_id'] . ';Dokument;1;varchar;1';
				if (count($this->belated_files) > $b + 1) {	# für das nächste belated File sichern, da es nach move_uploaded_file nicht mehr da ist
					$tmp_save = date('Y-m-d_G_i_s');
					copy($_FILES['uploadfile']['tmp_name'], IMAGEPATH . $tmp_save);
				}
				$_FILES[$fieldnames[0]] = $_FILES['uploadfile'];
				for ($i = 0; $i < count($attributes['name']); $i++) {
					$fieldname = $belated_file->data['layer_id'] . ';' . $attributes['name'][$i] . ';' . $attributes['table_name'][$i] . ';' . $belated_file->data['dataset_id'] . ';' . $attributes['form_element_type'][$i] . ';1;' . $attributes['type'][$i] . ';1';
					$this->formvars[$fieldname] = $this->qlayerset[0]['shape'][0][$attributes['name'][$i]];
					$fieldnames[] = $fieldname;
				}
				$this->formvars['form_field_names'] = implode('|', $fieldnames);
				$this->formvars['only_update'] = true;
				$this->Sachdaten_speichern();
				if (count($this->belated_files) > $b + 1) {
					$_FILES['uploadfile']['tmp_name'] = IMAGEPATH . $tmp_save;
					$_FILES['uploadfile']['source_handling'] = 'copy';
				}
				if ($this->success) {
					$belated_file->delete();
					?>
					<div class="s">
						Erfolgreich im Datensatz <? echo $belated_file->data['dataset_id']; ?> gespeichert.<br>
						Typ: <? echo $filetype; ?><br>
						Dateigröße: <? echo $filesize; ?>
					</div><?
				}
				else { ?>
					<div class="s">
						<p>Fehler beim Speichern des Fotos <? echo $filename; ?> im Datensatz <? echo $belated_file->data['dataset_id']; ?>.</p>
						<p>Type: <? echo $filetype; ?></p>
						<p>Dateigröße: <? echo $filesize; ?></p>
						<p>Fehler: <? echo $this->errMsg; ?></p>
					</div><?
				}
			}
			if ($this->success) {
				echo '█startNextUpload();';
			}
		}
	}	

	function sachdaten_speichern() {
		$document_attributes = array();
		foreach($this->formvars as $key => $value) {
			if (is_string($value)) $this->formvars[$key] = replace_tags($value, 'script|embed');
		}
		if ($this->formvars['document_attributename'] != '') {
			$_FILES[$this->formvars['document_attributename']]['name'] = 'delete'; # das zu löschende Dokument
		}
		$_files = $_FILES;
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$form_fields = explode('|', $this->formvars['form_field_names']);
		$this->success = true;
		$old_layer_id = '';
		for ($i = 0; $i < count($form_fields); $i++) {
			if ($form_fields[$i] != '') {
				$eintrag = NULL;
				$element = explode(';', $form_fields[$i]);
				$layer_id = $element[0];
				$attributname = $element[1];
				$tablename = $element[2];
				$oid = $element[3];
				$attributenames[$oid][] = $attributname;
				$attributevalues[$oid][] = $this->formvars[$form_fields[$i]];
				$formtype = $element[4];
				$datatype = $element[6];
				$saveable = $element[7];
				$this->sanitize([$form_fields[$i] => $datatype], true);
				if ($layerset[$layer_id] == NULL) {
					$layerset[$layer_id] = $this->user->rolle->getLayer ($layer_id);
				}
				if ($layer_id != $old_layer_id) {
					$layerdb[$layer_id] = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
					$privileges = $this->Stelle->get_attributes_privileges($layer_id);		# Rechte
					$attributes = $mapdb->read_layer_attributes($layer_id, $layerdb[$layer_id], $privileges['attributenames']);
					#$filter = $mapdb->getFilter ($layer_id, $this->Stelle->id);		# siehe unten
					$old_layer_id = $layer_id;
					if ($layerset[$layer_id][0]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
						$layerset[$layer_id][0]['maintable'] = $attributes['table_name'][$attributes['the_geom']];
					}
					if ($layerset[$layer_id][0]['oid'] == '' AND count_or_0($attributes['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
						$layerset[$layer_id][0]['oid'] = $attributes['pk'][0];
					}
				}
				if (
					(
						$this->formvars['go'] == 'Dokument_Loeschen' OR
						$this->formvars['changed_' . $layer_id . '_' . str_replace(['-', '.'], ['', '_'], $oid)] == 1 OR	# PHP macht diese Ersetzungen selber
						$this->formvars['embedded']
					) AND
					array_key_exists($attributname, $attributes['constraints']) AND 	# Rechte
					$attributname != $layerset[$layer_id][0]['oid'] AND
					$tablename != '' AND
					$saveable AND
					$tablename == $layerset[$layer_id][0]['maintable']
				) {
					# nur Attribute aus der Haupttabelle werden gespeichert
					switch ($formtype) {
						case 'Dokument' : {
							# die Dokument-Attribute werden hier zusammen gesammelt,
							# weil der Datei-Upload gemacht werden muss,
							# nachdem alle Attribute durchlaufen worden sind (wegen dem DocumentPath)
							if ($_files[$form_fields[$i]]['name'] OR $this->formvars[$form_fields[$i]] OR substr($datatype, 0, 1) == '_') {
								$attr_oid['layer_id'] = $layer_id;
								$attr_oid['tablename'] = $tablename;
								$attr_oid['attributename'] = $attributname;
								$attr_oid['datatype'] = $datatype;
								$attr_oid['oid'] = $oid;
								$document_attributes[$i] = $attr_oid;
							}
						} break; # ende case Bild
						case 'Time' : {
							if (in_array($attributes['options'][$attributname], array('', 'update')))$eintrag = date('Y-m-d G:i:s');
						} break;
						case 'User' : {
							if (in_array($attributes['options'][$attributname], array('', 'update')))$eintrag = $this->user->Vorname." " . $this->user->Name;
						} break;
						case 'UserID' : {
							if (in_array($attributes['options'][$attributname], array('', 'update')))$eintrag = $this->user->id;
						} break;
						case 'Stelle' : {
							if (in_array($attributes['options'][$attributname], array('', 'update')))$eintrag = $this->Stelle->Bezeichnung;
						} break;
						case 'StelleID' : {
							if (in_array($attributes['options'][$attributname], array('', 'update')))$eintrag = $this->Stelle->id;
						} break;
						case 'Geometrie' : {
							# nichts machen
						} break;
						case 'Checkbox' : {
							if ($this->formvars[$form_fields[$i]] == '')$this->formvars[$form_fields[$i]] = 'f';
							$eintrag = $this->formvars[$form_fields[$i]];
						} break;
						case 'Link' : {
							$eintrag = $this->formvars[$form_fields[$i]];
							if ($eintrag != '' AND substr($eintrag, 0, 9) != 'index.php' AND strpos($eintrag, ':') === false)	{
								# bei externen Links http davor setzen, wenn kein Protokoll angegeben
								$eintrag = 'http://' . $eintrag;
							}
							if ($eintrag == '')$eintrag = 'NULL';
						} break;
						default : {
							if ($tablename AND $formtype != 'dynamicLink' AND $formtype != 'SubFormPK' AND $formtype != 'SubFormEmbeddedPK' AND $attributname != 'the_geom') {
								if ($this->formvars[$form_fields[$i]] == '') {
									$eintrag = 'NULL';
								}
								else {
									if (POSTGRESVERSION >= 930 AND (substr($datatype, 0, 1) == '_' OR is_numeric($datatype))) {
										$eintrag = $this->processJSON($this->formvars[$form_fields[$i]], $layerset[$layer_id][0]['document_path'], $layerset[$layer_id][0]['document_url']); # bei einem custom Datentyp oder Array das JSON in PG-struct umwandeln
									}
									else {
										switch ($datatype) {
											case 'numeric':
											case 'float4' :
											case 'float8' : {
												$eintrag = str_replace(',', '.', $this->formvars[$form_fields[$i]]);
											} break;
											case 'date' : {
												// convert from german notation to english '25.12.1966' to '1966-12-25'
												$eintrag = DateTime::createFromFormat('d.m.Y', $this->formvars[$form_fields[$i]])->format('Y-m-d');
											} break;
											case 'time' : {
												// do nothing format is same in english and german
												$eintrag = $this->formvars[$form_fields[$i]];
											} break;
											case 'timestamp' : {
												// convert from german notation to english '25.12.1966 08:01:02' to '1966-12-25 08:01:02'
												if (strlen($this->formvars[$form_fields[$i]]) > 19) {
													$eintrag = DateTime::createFromFormat('d.m.Y H:i:s.u', $this->formvars[$form_fields[$i]])->format('Y-m-d H:i:s.u');
												}
												elseif (strlen($this->formvars[$form_fields[$i]]) > 10){
													$eintrag = DateTime::createFromFormat('d.m.Y H:i:s', $this->formvars[$form_fields[$i]])->format('Y-m-d H:i:s');
												}
												else {
													$eintrag = DateTime::createFromFormat('d.m.Y', $this->formvars[$form_fields[$i]])->format('Y-m-d 00:00:00');
												}
											} break;
											default : {
												$eintrag = $this->formvars[$form_fields[$i]];
											}
										}
									}
								}
							}
						} # end of default case
					} # end of switch for type
					if ($eintrag !== NULL) {
						$updates[$layer_id][$tablename][$oid][$attributname]['value'] = $eintrag;
					}
				}
			}
		}

		if (count($document_attributes) > 0) {
			if ($this->user->rolle->upload_only_file_metadata == 1) {
				$belated_files = array();
				include_once(CLASSPATH . 'BelatedFile.php');
			}
			foreach ($document_attributes as $i => $attr_oid) {
				$doc_path = $layerset[$attr_oid['layer_id']][0]['document_path'];
				$doc_url = $layerset[$attr_oid['layer_id']][0]['document_url'];
				$options = $attributes['options'][$attr_oid['attributename']];
				$attribute_names = $attributenames[$attr_oid['oid']];
				$attribute_values = $attributevalues[$attr_oid['oid']];
				$layer_db = $layerdb[$attr_oid['layer_id']];
				if (substr ($attr_oid['datatype'], 0, 1) == '_') {
					# ein Array aus Dokumenten, hier enthält der JSON-String eine Mischung aus bereits vorhandenen,
					# nicht geänderten Datei-Pfaden und File-input-Feldnamen, die noch verarbeitet werden müssen
					$update = $this->processJSON($this->formvars[$form_fields[$i]], $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db);
				}
				else {
					# normales Dokument-Attribut
					$update = $this->save_uploaded_file($form_fields[$i], $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db, $document_attributes[$i]['datatype']);
					if ($this->user->rolle->upload_only_file_metadata == 1) {
						$belated_files[$attr_oid['oid']][$i] = $this->formvars[$form_fields[$i]];
					}
				}
				$updates[$attr_oid['layer_id']][$attr_oid['tablename']][$attr_oid['oid']][$attr_oid['attributename']]['value'] = $document_attributes[$i]['update'] = $update;
			}
		}
		if ($this->formvars['delete_documents'] != '') {
			# in diesem input-Feld stehen die Pfade von Dokumenten, die zu entfernten Array-Elementen gehörten und gelöscht werden müssen
			$documents = explode('|', $this->formvars['delete_documents']);
			foreach ($documents as $path) {
				# geht erstmal nur für einen einzelnen Layer
				$this->deleteDokument($path, $layerset[$layer_id][0]['document_path'], $layerset[$layer_id][0]['document_url']);
			}
		}
		if ($updates != NULL) {
			foreach ($updates as $layer_id => $layer) {
				foreach ($layer as $tablename => $table) {
					foreach ($table as $oid => $attribs) {
						if (count($attribs) > 0) {
							if (!$layerset[$layer_id][0]['maintable_is_view']) {
								$sql_lock = "LOCK TABLE " . pg_quote($tablename)." IN SHARE ROW EXCLUSIVE MODE;";
							}

							$attributes_set = array();
							foreach ($attribs AS $attribute => $properties) {
								if (is_array($properties['value'])) {
									# ist bei Dokumenten in einem Array der Fall
									for ($a = 0; $a < count($properties['value']); $a++) {
										if ($properties['value'][$a] != NULL) {
											 # $a + 1 da postgres-Arrays bei 1 beginnen
											$attributes_set[] = $attribute . "[" . ($a + 1) . "] = '" . $properties['value'][$a] . "'";
										}
									}
								}
								else {
									$attributes_set[] = pg_quote($attribute) . " = " . (in_array($properties['value'], ['NULL', '']) ? "NULL" : "'" . $properties['value'] . "'");
								}
							}

							$where_condition = $layerset[$layer_id][0]['oid'] . " = '" . $oid . "'";

							$sql = $sql_lock . "
								UPDATE
									" . pg_quote($tablename) . "
								SET
									" . implode(', ', $attributes_set) . "
								WHERE
									" . $where_condition . "
							";

							# erstmal wieder rausgenommen, weil der Filter sich auf Attribute beziehen kann, die zu anderen Tabellen gehören
							#if ($filter != '') {
							#	$sql .= " AND " . $filter;
							#}

							# Before Update trigger
							if (!empty($layerset[$layer_id][0]['trigger_function'])) {
								if ($layerset[$layer_id][0]['oid'] == 'oid'){
									$oid_sql = 'oid,';
								}
								$sql_old = "
									SELECT ".
										$oid_sql." *
									FROM
										" . $layerset[$layer_id][0]['schema'] . '.' . pg_quote($layerset[$layer_id][0]['maintable']) . "
									WHERE
										" . $where_condition;
								#echo '<br>sql before update: ' . $sql_old; #pk
								$ret = $layerdb[$layer_id]->execSQL($sql_old, 4, 1);
								$old_dataset = ($ret[0] == 0 ? pg_fetch_assoc($ret[1]) : array());
								$this->exec_trigger_function('BEFORE', 'UPDATE', $layerset[$layer_id][0], $oid, $old_dataset);
							}

							#echo 'SQL zum Update des Datensatzes: ' . $sql;
							$this->debug->show('<br>sql for update: ' . $sql);

							$this->debug->write("<p>file:kvwmap class:sachdaten_speichern :", 4);
							$ret = $layerdb[$layer_id]->execSQL($sql, 4, 9999999, true);
							if ($ret['success']) {
								$result = pg_fetch_row($ret['query']);
								if (pg_affected_rows($ret['query']) > 0) {
									#echo '<br>delete und insert sql: ' . $delete_sql . '; ' . $insert_sql;
									#$ret = $layerdb[$layer_id]->execSQL($delete_sql . '; ' . $insert_sql, 4, 1);
									#if (!$ret['success']) {
									#	$this->add_message('error', $ret[1]);
									#}
									if ($this->user->rolle->upload_only_file_metadata == 1 AND is_array($belated_files) AND array_key_exists($oid, $belated_files)) {
										foreach ($belated_files[$oid] AS $i => $belated_file) {
											if ($belated_file != '') {
												$file = json_decode($belated_file);
												$where = "
													`user_id` = " . $this->user->id . " AND
													`layer_id` = " . $layer_id . " AND
													`attribute_name` = '" . $document_attributes[$i]['attributename'] . "' AND
													`dataset_id` = '" . $oid . "'
												";
												if ($old_belated_file = BelatedFile::find($this, $where)) {	# ein "=" ist richtig, da Zuweisung
													$old_belated_file[0]->set('name', $file->name);
													$old_belated_file[0]->set('size', $file->size);
													$old_belated_file[0]->set('lastmodified', $file->lastmodified);
													$old_belated_file[0]->set('file', $document_attributes[$i]['update']);
													$old_belated_file[0]->update();
												}
												else {
													BelatedFile::insert(
														$this, array(
															'user_id' => $this->user->id,
															'layer_id' => $layer_id,
															'dataset_id' => $oid,
															'attribute_name' => $document_attributes[$i]['attributename'],
															'name' => $file->name,
															'size' => $file->size,
															'lastmodified' => $file->lastmodified,
															'file' => $document_attributes[$i]['update']
														)
													);
												}
											}
										}
									}

									# After Update trigger
									if (!empty($layerset[$layer_id][0]['trigger_function'])) {
										$this->exec_trigger_function('AFTER', 'UPDATE', $layerset[$layer_id][0], $oid, $old_dataset);
									}
								}
								else {
									# dataset was not created
									$this->success = false;
								}
							}
							else {
								# query not successfull set query error message
								$this->success = false;
								$this->add_message(
									$ret['type'],
									sql_err_msg('Kann Datensatz nicht speichern:<br>', $sql, $ret['msg'], 'error_div_' . rand(1, 99999))
								);
							}
						}
					}
				}
			}
			if ($this->success == false) {
				$this->add_message('error', 'Änderung fehlgeschlagen.<br>' . $result[0]);
			}
			else {
				if ($this->formvars['close_window'] == "") {
					$this->add_message('notice', 'Änderung erfolgreich');
					if ($result[0] != '')$this->add_message('warning', $result[0]);
					if ($ret['msg'] != '')$this->add_message('warning', $ret['msg']);
				}
			}
		}
		else {
			$this->add_message('warning', 'Keine Änderung.');
		}
		if ($this->formvars['only_update']) {
			# Hier erfolgt keine weitere Ausgabe
		}
		else {
			if ($this->formvars['embedded'] != '') {
				# es wurde ein Datensatz aus einem embedded-Formular gespeichert
				if ($this->formvars['reload'] == '1') {
					# in diesem Fall wird die komplette Seite neu geladen
					echo 'currentform.go.value=\'get_last_query\';	overlay_submit(currentform, false);';
				}
				else {
					if ($this->formvars['reload'] !== '0') {		# 0 heißt: no_subform_reload - Subform-Liste wird nicht neu geladen
						# ansonsten wird das Listen-DIV neu geladen
						echo 'reload_subform_list(\''.$this->formvars['targetobject'].'\', 0, 0);';
					}
					if (!empty(GUI::$messages)){
						echo 'message('.json_encode(GUI::$messages).');';
					}
				}
			}
			else {
				$this->last_query = $this->user->rolle->get_last_query();
				if ($this->formvars['search']) {
					# man kam von der Suche	 -> nochmal suchen
					$this->GenerischeSuche_Suchen();
				}
				else {
					# man kam aus einer Sachdatenabfrage -> nochmal abfragen
					$this->queryMap();
				}
			}
		}
	}

	/**
	 * Diese Funktion wandelt den übergebenen JSON-String in ein PostgeSQL-Struct um.
	 * Wenn der JSON-String mit "file:" gekennzeichnete File-Input-Feld-Namen von Datei-Uploads enthält,
	 * werden diese Uploads gespeichert und der entstandene Dateipfad an die enstdprechende Stelle im String eingefügt
	 */
	function processJSON($json, $doc_path = NULL, $doc_url = NULL, $options = NULL, $attribute_names = NULL, $attribute_values = NULL, $layer_db = NULL, $quote = '') {
		if (is_string($json) AND (strpos($json, '{') !== false OR strpos($json, '[') !== false)) {			// bei Bedarf den JSON-String decodieren
			$json = json_decode($json);
		}
		if (is_array($json)) {		// Array-Datentyp
			for ($i = 0; $i < count($json); $i++) {
				$elems[] = $this->processJSON($json[$i], $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db, '"');
			}
			$result = '{' . @implode(',', array_filter($elems)) . '}';		# leere Array-Elemente mit array_filter weglassen
		}
		elseif (is_object($json)) { // Nutzer-Datentyp
			if ($quote == '') {
				$new_quote = '"';
			}
			elseif ($quote == '"') {
				$new_quote = '\\' . $quote;		# Hinweis: das ist eigentlich nur ein! Backslash
			}
			else {
				$new_quote = $quote;
			}
			foreach ($json as $elem) {
				$elems[] = $this->processJSON($elem, $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db, $new_quote);
			}
			$result = $quote . '(' . implode(',', $elems) . ')' . $quote;
		}
		else { // normaler Datentyp
			if (substr($json, 0, 5) == 'file:') {
				$json = $this->save_uploaded_file(substr($json, 5), $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db);		// Datei-Uploads verarbeiten
			}
			$json = addslashes($json);
			if ($json != '') {
				$result = ($json == 'NULL' ? '' : (is_numeric($json) ? $json : $quote . $json . $quote));
			}
			else {
				$result = $json;
			}
		}
		return $result;
	}

	/**
	* Function move or copy uploaded files to the document path of the layer and returns the value for document attribute
	* If upload_only_file_metadata of rolle is set, than insteat belated_file.jpg will be copied to layers document path
	* If input field value = delete, the existing document will be deleted.
	* If a file exists with another name, it will be deleted before copy or move the new to the document path
	* @param $input_name Name of the files form field used to upload the file
	* @param $doc_path Document path defined for the layer
	* @param $doc_url Document url defined for the layer
	* @param $options Options defined for the document attribute
	* @param $attribute_names Names of all attributes
	* @param $attribute_values Values of all attributes
	* @param $layer_db The database object of the layer
	* @param $datatype The datatype of the document attribute
	*/
	function save_uploaded_file($input_name, $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db, $datatype = NULL) {
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		if ($this->user->rolle->upload_only_file_metadata == 1) {
			$file = json_decode($this->formvars[$input_name]);
			$_files = array(
				$input_name => array(
					'name' => $file->name,
					'type' => 'image/jpg',
					'tmp_name' => WWWROOT . APPLVERSION . GRAPHICSPATH . 'belated_file.jpg',
					'error' => 0,
					'size' => $file->size,
					'source_handling' => 'copy'
				)
			);
		}
		else {
			$_files = $_FILES;
		}
		if ($_files[$input_name]['name'] != '' OR $this->formvars[$input_name] == 'delete') {
			$pathinfo = pathinfo($_files[$input_name]['name']);
			if ($datatype != 'bytea') {
				$datei_erweiterung = ($this->user->rolle->upload_only_file_metadata == 1 ? 'jpg' : $pathinfo['extension']);
				$doc_paths = $mapdb->getDocument_Path($doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db, $pathinfo['filename']);
				$nachDatei = $doc_paths['doc_path'] . (substr(trim($doc_paths['doc_path']), -1) === '/' ? $pathinfo['filename'] : '') . '.' . $datei_erweiterung;

				if ($doc_paths['doc_url'] != '') {
					$db_input = $doc_paths['doc_url'] . '.' . $datei_erweiterung;			# die URL zu der Datei wird gespeichert (Permalink)
				}
				else {
					$db_input = $nachDatei . "&original_name=" . $_files[$input_name]['name']; # absoluter Dateipfad wird gespeichert
				}
				if ($this->formvars[$input_name] == 'delete') {
					$db_input = 'NULL';
				}
				# Bild in das Datenverzeichnis kopieren
				if ($_files[$input_name]['source_handling'] == 'copy') {
					#echo '<p>copy dummy file: ' . $_files[$input_name]['tmp_name'] . ' to file: ' . $nachDatei;
					$file_copy_success = copy($_files[$input_name]['tmp_name'], $nachDatei);
				}
				else {
					#echo '<p>move uploaded file: ' . $_files[$input_name]['tmp_name'] . ' to file: ' . $nachDatei;
					$file_copy_success = move_uploaded_file($_files[$input_name]['tmp_name'], $nachDatei);
				}
				if ($file_copy_success OR $this->formvars[$input_name] == 'delete') {
					# bei dynamischem Dateipfad das Vorschaubild löschen
					if (strtolower(substr($options, 0, 6)) == 'select') {
						$this->deleteDokument($nachDatei, $doc_path, $doc_url, true);
					}
					# Wenn eine alte Datei existiert, die nicht so heißt wie die neue --> löschen
					$parts = explode('&original_name', $this->formvars[$input_name.'_alt']);
					$old = array_shift($parts);
					if ($old != '' AND $old != $nachDatei) {
						$this->deleteDokument($old, $doc_path, $doc_url);
					}
				}
				else {
					echo '<br>Datei: '.$_files[$input_name]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
				}
			}
			else {
				if ($this->formvars[$input_name] == 'delete') {
					$db_input = 'NULL';
				}
				else {
  				$db_input = pg_escape_bytea(file_get_contents($_files[$input_name]['tmp_name']) . '&original_name=' . $_files[$input_name]['name']);
				}
			}
			return $db_input;
		}
	}

	function queryMap() {
		# scale ausrechnen, da wir uns das loadmap sparen
		$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
		if($this->user->rolle->epsg_code == 4326){$unit = MS_DD;} else {$unit = MS_METERS;}
		$md = ($this->user->rolle->nImageWidth-1)/(96 * InchesPerUnit($unit, $center_y));
		$width = $this->user->rolle->nImageWidth;
		$height = $this->user->rolle->nImageHeight;
		$extentwidth = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
		$extentheight = $this->user->rolle->oGeorefExt->maxy - $this->user->rolle->oGeorefExt->miny;
		$this->map_scaledenom = round($extentwidth/$md);
		$ratio_image = round($width/$height, 5);
		$ratio_extent = round($extentwidth/$extentheight, 5);
		$pixwidth=$extentwidth/$width * $ratio_image/$ratio_extent;
		$pixheight=$extentheight/$height;
		$this->user->rolle->pixsize=($pixwidth+$pixheight)/2;		# pixsize neu berechnen, da die Kartenabfrage aus einer Fachschale mit reduzierter Kartenbreite gemacht worden sein kann

    # Abfragebereich berechnen
		if(value_of($this->formvars, 'querypolygon') != ''){
			$rect = $this->formvars['querypolygon'];
		}
		else{
			$rect = $this->create_query_rect($this->formvars['INPUT_COORD']);
		}
    if ($this->show_query_tooltip == true){
      $this->tooltip_query($rect);
    }
    else {
      $this->SachdatenAnzeige($rect);
			if (
				false AND # deaktiviert wegen Sachdatenanzeige im extra Browser-Fenster
				$this->go == 'Layer_Datensaetze_Loeschen' AND
				array_reduce(
					$this->qlayerset,
					function($sum, $layerset) {
						return $sum + count($layerset['shape']);
					},
					0
				) == 0
			) {
				$this->add_message('warning', 'Keine Datensätze mehr in der Sachdatenanzeige.');
				$this->loadMap('DataBase');
				$this->user->rolle->newtime = $this->user->rolle->last_time_id;
				$this->saveMap('');
				$this->drawMap();
				$this->main = 'map.php';
			}
			else {
				if ($this->formvars['printversion'] != ''){
					$this->mime_type = 'printversion';
				}
			}
			$this->output();
    }
  }

	function SachdatenAnzeige($rect) {
		$this->qlayerset = array();
		$queryfield = ($this->user->rolle->singlequery == 2? 'thema' : 'qLayer');
		$last_query_deleted = false;
		if ($this->last_query != '') {
			foreach($this->last_query['layer_ids'] as $layer_id) {
				$this->formvars[$queryfield . $layer_id] = 1;
			}
		}
		if (is_string($rect)){
			$this->querypolygon = $rect;
		}
		$this->queryrect = $rect;
		# Abfragen der Layer, die zur Stelle gehören
		$layer = $this->user->rolle->getLayer('', true);
		$rollenlayer = $this->user->rolle->getRollenLayer('', 'import');
		$layerset = array_merge($layer, $rollenlayer);
		$anzLayer = count($layerset);
		$disabled_class_expressions = $this->user->rolle->read_disabled_class_expressions($layerset);
		$map = new mapObj('');
		$map->shapepath = SHAPEPATH;
		for ($i = 0; $i < $anzLayer; $i++) {
			$sql_order = '';
			if (
				$layerset[$i]['queryable'] AND
				$layerset[$i]['status']  == '' AND 
				(
					value_of($this->formvars, $queryfield . $layerset[$i]['Layer_ID']) == '1' OR
					value_of($this->formvars, $queryfield . $layerset[$i]['requires']) == '1'
				) AND
				(
					($this->last_query == '' AND $layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] >= $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] <= $this->map_scaledenom) OR 
					($this->last_query[$layerset[$i]['Layer_ID']] != '')
				)
			) {
				# Dieser Layer soll abgefragt werden
				if (value_of($this->formvars, 'anzahl') == '') {
					$this->formvars['anzahl'] = $layerset[$i]['max_query_rows'] ?: MAXQUERYROWS;
				}
				if (
						$this->formvars['without_diagramms'] OR
						in_array($this->formvars['format'], array('json', 'json_result')) OR
						$this->formvars['mime_type'] == 'json'
				) {
					$layerset[$i]['charts'] = array();
				}
				else {
					include_once(CLASSPATH . 'LayerChart.php');
					$layerset[$i]['charts'] = LayerChart::find($this, '`layer_id` = ' . $layerset[$i]['Layer_ID']);
				}
				switch ($layerset[$i]['connectiontype']) {
					case MS_SHAPEFILE : { # Shape File Layer (1)
						if ($this->formvars['searchradius'] > 0 OR $this->querypolygon != '') {
							showAlert('Sie können für die Abfrage von Shape- und Rasterlayern nur die einfache Sachdatenabfrage verwenden.');
						}
						else {
							$layer = new layerObj($map);
							$layer->data = $layerset[$i]['Data'];
							if ($layerset[$i]['tileindex'] != '') {
								$layer->tileindex = SHAPEPATH.$layerset[$i]['tileindex'];
							}
							$layer->status = MS_ON;
							$layer->type = $layerset[$i]['Datentyp'];
							if ($layerset[$i]['template']!='') {
								$layer->template = $layerset[$i]['template'];
							}
							else {
								$layer->template = ' ';		# ohne Template kann der Layer über den Mapserver nicht abgefragt werden
							}
							$projFROM = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
							$projTO = new projectionObj("init=epsg:" . $layerset[$i]['epsg_code']);
							$rect2 = rectObj($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
							$rect2->project($projFROM, $projTO);
							if ($layerset[$i]['Datentyp'] == 3) {
								# bei Rasterlayern nur punktuell abfragen
								$point = new PointObj();
								$point->setXY($rect2->minx+($rect2->maxx-$rect2->minx)/2, $rect2->miny+($rect2->maxy-$rect2->miny)/2);
								if (MAPSERVERVERSION >= 800) {
									@$layer->queryByPoint($map, $point, MS_MULTIPLE, 0);
								}
								else {
									@$layer->queryByPoint($point, MS_MULTIPLE, 0);
								}
							}
							else {
								if (MAPSERVERVERSION >= 800) {
									@$layer->queryByRect($map, $rect2);
								}
								else {
									@$layer->queryByRect($rect2);
								}
							}
							$anzResult=$layer->getNumResults();
							for ($j = 0; $j < $anzResult; $j++) {
								$result = $layer->getResult($j);
								$s = $layer->getShape($result);
								$count = 0;
								if (MAPSERVERVERSION >= 800) {
									$lastvalue = '';
									for ($v = 0; $v < $s->numvalues; $v++) {
										$value = $s->getValue($v);
										if ($value != $lastvalue) {
											$layerset[$i]['shape'][$j][$v] = utf8_encode($value);
											$layerset[$i]['attributes']['name'][$count] = $v;
											$layerset[$i]['attributes']['privileg'][$count] = 0;
											$layerset[$i]['attributes']['visible'][$count] = true;
											$lastvalue = $value;
										}
										$count++;
									}
								}
								else {
									foreach ($s->values as $key => $value) {
										if (
											$layerset[$i]['Datentyp'] != 3 OR
											in_array($key, array('x','y','value_0'))
										) {
											# für Rasterlayer nur diese Daten ausgeben
											$layerset[$i]['shape'][$j][$key] = utf8_encode($value);
											$layerset[$i]['attributes']['name'][$count] = $key;
											$layerset[$i]['attributes']['privileg'][$count] = 0;
											$layerset[$i]['attributes']['visible'][$count] = true;
										}
										$count++;
									}
								}
							}
							$this->qlayerset[] = $layerset[$i];
						}
					} break; # ende Layer ist ein Shapefile

					case MS_OGR : case 5 : { # OGR Layertype 4 und 5 (MS_TILED_OGR))
						$layer=new layerObj($map);
						$layer->setConnectionType($layerset[$i]['connectiontype'], '');
						$layer->connection = $layerset[$i]['connection'];
						$layer->type = $layerset[$i]['Datentyp'];
						$layer->status = MS_ON;
						if ($layerset[$i]['template']!='') {
							$layer->set('template',$layerset[$i]['template']);
						}
						if (MAPSERVERVERSION >= 800) {
							@$layer->queryByRect($map, $rect);
						}
						else {
							@$layer->queryByRect($rect);
						}
						$layer->open();
						$anzResult=$layer->getNumResults();
						for ($j=0;$j<$anzResult;$j++) {
							$result=$layer->getResult($j);
							$shapeindex=$result->shapeindex;
							if(MAPSERVERVERSION > 500){
								$layerset[$i]['shape'][$j]=$layer->getFeature($shapeindex,-1);
							}
							else{
								$layerset[$i]['shape'][$j]=$layer->getShape(-1,$shapeindex);
							}
						}
						$this->qlayerset[]=$layerset[$i];
					} break;

					case MS_POSTGIS : {
						# PostGIS Layer (6)
						if ($layerset[$i]['pfad'] != '') {
							# Für die performante Suche wird immer zunächst ein Suchrechteck (searchbox) gebildet, egal ob punktuell
							# oder in einem Suchfenster gesucht wird
							# Für die Bildung der searchbox wird entweder mit dem angegebenen Suchradius tolerance in der Einheit toleranceunit
							# aus der Tabelle layers gerechnet oder mit dem im Formular eingegebenen Suchradius (searchradius)


							# Datenbankobjekt aus Layerdefinition erzeugen
							# Path laden
							# Rechte abfragen
							# Path auf Basis der Rechte einschränken
							# Attribute aus Path laden
							# Rechte den Attributen zuweisen
							$this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
							$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
							$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
							$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames'], false, true);
							
							if ($layerset[$i]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
								$layerset[$i]['maintable'] = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
							}
							if ($layerset[$i]['oid'] == '' AND count_or_0($layerset[$i]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
								$layerset[$i]['oid'] = $layerset[$i]['attributes']['pk'][0];
							}

							$query_parts = $this->mapDB->getQueryParts($layerset[$i], $privileges);
							$pfad = $query_parts['query'];

							for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++) {
								$layerset[$i]['attributes']['privileg'][$j] = $privileges[$layerset[$i]['attributes']['name'][$j]];
								$layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = $privileges[$layerset[$i]['attributes']['name'][$j]];
							}

							if ($layerset[$i]['Layer_ID'] > 0 AND empty($privileges)) {
								$pfad = 'NULL::geometry as ' . $layerset[$i]['attributes']['the_geom'] . ' ' . $pfad;
							}

							if ($layerset[$i]['attributes']['the_geom'] == '') {					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
								$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
								$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
							}
							$the_geom = $layerset[$i]['attributes']['the_geom'];

							if ($the_geom == NULL) {	# z.B. bei Rollenlayern ohne Geometriespalte
								continue 2;
							}

							# Unterscheidung ob mit Suchradius oder ohne gesucht wird
							if ($this->formvars['searchradius']>0) {
								$layerset[$i]['toleranceunits']='meters';
								$layerset[$i]['tolerance']=$this->formvars['searchradius'];
							}
							switch ($layerset[$i]['toleranceunits']) {
								case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
								case 'meters' : $pixsize=1; break;
								default : $pixsize=$this->user->rolle->pixsize;
							}
							$rand=$layerset[$i]['tolerance']*$pixsize;

							# Aktueller EPSG in der die Abfrage ausgeführt wurde
							$client_epsg=$this->user->rolle->epsg_code;
							# EPSG-Code des Layers der Abgefragt werden soll
							$layer_epsg=$layerset[$i]['epsg_code'];

							$distance = $rand;
							$sphere = '';
							if ($client_epsg == 4326 OR $layer_epsg == 4326) {
								$center_y = ($rect->maxy+$rect->miny)/2;
								$cos_lat = cos(pi() * $center_y/180.0);
								$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
								if($client_epsg == 4326){
									$distance = $layerset[$i]['tolerance'] * $pixsize * $lat_adj * 111000;
								}
								else if ($layer_epsg == 4326) {
									$sphere = 'sphere';
								}
							}

							# Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
							$searchbox_wkt ="POLYGON((";
							$searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny).",";
							$searchbox_wkt.=strval($rect->maxx)." ".strval($rect->miny).",";
							$searchbox_wkt.=strval($rect->maxx)." ".strval($rect->maxy).",";
							$searchbox_wkt.=strval($rect->minx)." ".strval($rect->maxy).",";
							$searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny)."))";

							if($this->querypolygon != ''){
								$searchbox_wkt = $this->querypolygon;
							}

							# ---------- punktuelle Suche ---------- #
							if ($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy AND $this->querypolygon == '') {
								$loosesearchbox_wkt ="POLYGON((";
								$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand).",";
								$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->miny-$rand).",";
								$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->maxy+$rand).",";
								$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->maxy+$rand).",";
								$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand)."))";
								# Behandlung der Suchanfrage mit Punkt, exakte Suche im Kreis
								if ($client_epsg!=$layer_epsg) {
									$sql_where =" AND " . $the_geom." && st_transform(st_geomfromtext('" . $loosesearchbox_wkt."'," . $client_epsg.")," . $layer_epsg.")";
									$sql_where.=" AND st_distance" . $sphere . "(" . $the_geom.",st_transform(st_geomfromtext('POINT(" . $rect->minx." " . $rect->miny.")'," . $client_epsg.")," . $layer_epsg."))";
								}
								else {
									$sql_where =" AND " . $the_geom." && st_geomfromtext('" . $loosesearchbox_wkt."'," . $client_epsg.")";
									$sql_where.=" AND st_distance" . $sphere . "(" . $the_geom.",st_geomfromtext('POINT(" . $rect->minx." " . $rect->miny.")'," . $client_epsg."))";
								}
								$sql_where.=" <= " . $distance;
							}
							# ---------- Suche über Polygon ---------- #
							else {
								# Behandlung der Suchanfrage mit Rechteck, exakte Suche im Rechteck
								if ($client_epsg!=$layer_epsg) {
									$sql_where =" AND st_intersects(" . $the_geom.",st_transform(st_geomfromtext('" . $searchbox_wkt."'," . $client_epsg.")," . $layer_epsg."))";
								}
								else {
									$sql_where =" AND st_intersects(" . $the_geom.",st_geomfromtext('" . $searchbox_wkt."'," . $client_epsg."))";
								}
							}
							# Filter zur Where-Klausel hinzufügen
							# 2024-07-28 pk Replace all params from filter
							$filter = '';
							if ($layerset[$i]['Filter'] != '') {
								$layerset[$i]['Filter'] = replace_params_rolle($layerset[$i]['Filter']);
								$filter = " AND " . $layerset[$i]['Filter'];
							}
							# Filter auf Grund von ausgeschalteten Klassen hinzufügen
							if (QUERY_ONLY_ACTIVE_CLASSES AND array_key_exists($layerset[$i]['Layer_ID'], $disabled_class_expressions)) {
								foreach($disabled_class_expressions[$layerset[$i]['Layer_ID']] as $disabled_class) {
									$disabled_class_filter[$layerset[$i]['Layer_ID']][] = '(' . (mapserverExp2SQL($disabled_class['Expression'], $layerset[$i]['classitem']) ?: 'true') . ')';
								}
								$filter .= " AND COALESCE(NOT (" . implode(' OR ', $disabled_class_filter[$layerset[$i]['Layer_ID']]) . "), true)";
							}							
							if ($this->formvars['CMD'] == 'touchquery') {
								$sql = "SELECT " . $the_geom . " FROM (SELECT " . $pfad.") as query WHERE 1=1 " . $sql_where;
								$ret=$layerdb->execSQL($sql,4, 0);
								if(!$ret[0]){
									while($rs=pg_fetch_array($ret[1])){
										$geoms[]=$rs[0];
									}
								}
								if(count_or_0($geoms) > 0)$sql = '';
								for($g = 0; $g < count_or_0($geoms); $g++){
									if($g > 0)$sql .= " UNION ";
									$sql .= "SELECT " . $pfad . $filter . " AND " . $the_geom." && ('" . $geoms[$g]."') AND (st_intersects(" . $the_geom.", ('" . $geoms[$g]."'::geometry)) OR " . $the_geom." = ('" . $geoms[$g]."'))";
								}
							}
							else{
								$sql = "SELECT " . $query_parts['select'] . " FROM (SELECT " . $pfad.") as query WHERE 1=1 " . $filter . $sql_where;

								# order by
								if(value_of($this->formvars, 'orderby'.$layerset[$i]['Layer_ID']) != ''){									# Fall 1: im GLE soll nach einem Attribut sortiert werden
									$sql_order = ' ORDER BY ' . replace_semicolon($this->formvars['orderby'.$layerset[$i]['Layer_ID']]);
								}
								elseif($query_parts['orderby'] != ''){														# Fall 2: der Layer hat im Pfad ein ORDER BY
									$sql_order = $query_parts['orderby'];
								}
								if($layerset[$i]['template'] == ''){																				# standardmäßig wird nach der oid sortiert
									$j = 0;
									foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
										if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['oid'] != ''){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
											if($sql_order == '')$sql_order = ' ORDER BY ' . pg_quote(replace_semicolon($layerset[$i]['maintable']).'_oid').' ';
											else $sql_order .= ', '.pg_quote($layerset[$i]['maintable'].'_oid').' ';
										}
										$j++;
									}
								}
							}
							if($this->last_query != '' AND $this->last_query[$layerset[$i]['Layer_ID']]['sql'] != ''){
								$sql = $this->last_query[$layerset[$i]['Layer_ID']]['sql'];
								if($this->formvars['orderby'.$layerset[$i]['Layer_ID']] == '')$sql_order = $this->last_query[$layerset[$i]['Layer_ID']]['orderby'];
								if($this->formvars['anzahl'] == '')$this->formvars['anzahl'] = $this->last_query[$layerset[$i]['Layer_ID']]['limit'];
								if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] == '')$this->formvars['offset_'.$layerset[$i]['Layer_ID']] = $this->last_query[$layerset[$i]['Layer_ID']]['offset'];
							}

							# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
							$sql_limit =' LIMIT ' . intval($this->formvars['anzahl']);
							if(value_of($this->formvars, 'offset_'.$layerset[$i]['Layer_ID']) != ''){
								$sql_limit.=' OFFSET ' . intval($this->formvars['offset_'.$layerset[$i]['Layer_ID']]);
							}

							$layerset[$i]['sql'] = $sql;

							$this->suppress_err_msg = 1;
							#echo '<p>Sachdatenanzeige:<br>' . $sql . $sql_order . $sql_limit;
							$ret = $layerdb->execSQL($sql . $sql_order . $sql_limit, 4, 0);
							if ($ret[0]) {
								$this->add_message('error', $ret[1]);
								$this->loadMap('DataBase');
								$this->user->rolle->newtime = $this->user->rolle->last_time_id;
								$this->saveMap('');
								$this->drawMap();
								$this->output();
								exit;
							}
							else {
								while ($rs = pg_fetch_array($ret[1])) {
									$layerset[$i]['shape'][] = $rs;
								}
								$num_rows = pg_num_rows($ret[1]);
								if (
										value_of($this->formvars, 'offset_'.$layerset[$i]['Layer_ID']) == '' AND 
										$num_rows < $this->formvars['anzahl']
									)
								{
									$layerset[$i]['count'] = $num_rows;
								}
								else {
									# Anzahl der Datensätze abfragen
									$sql_count = "SELECT count(*) FROM (" . $sql.") as foo";
									$ret=$layerdb->execSQL($sql_count,4, 0);
									if (!$ret[0]) {
										$rs=pg_fetch_array($ret[1]);
										$layerset[$i]['count'] = $rs[0];
									}
								}
							}

							# Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
							# Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
							$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, $layerset[$i]['shape'], true, $this->Stelle->id);

							if($layerset[$i]['count'] != 0){

								# wenn nur ein Treffer und "anderes Objekt bearbeiten" eingestellt, in die Geometriebearbeitung gehen
								if($num_rows == 1 AND value_of($this->formvars, 'edit_other_object') == 1 AND $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['the_geom']]){
									$this->formvars['oid'] = $layerset[$i]['shape'][0][$layerset[$i]['maintable'].'_oid'];
									$this->formvars['selected_layer_id'] = $layerset[$i]['Layer_ID'];
									$geomtype = $layerset[$i]['attributes']['geomtype'][$layerset[$i]['attributes']['the_geom']];
									if($geomtype == 'POLYGON' OR $geomtype == 'MULTIPOLYGON' OR $geomtype == 'GEOMETRY')$geomtype = 'Polygon';
									elseif($geomtype == 'POINT')$geomtype = 'Point';
									elseif($geomtype == 'MULTILINESTRING' OR $geomtype == 'LINESTRING')$geomtype = 'Line';
									$this->formvars['go'] = $geomtype.'Editor';
									$this->formvars['newpath'] = '';
									$this->formvars['loc_x'] = '';
									$this->formvars['loc_y'] = '';
									if ($geomtype == 'Point') {
										$this->PointEditor();
									}
									else {
										$this->MultiGeomEditor();
									}
									exit();
								}

								if(!$last_query_deleted){			# damit nur die letzte Query gelöscht wird und nicht eine bereits gespeicherte Query eines anderen Layers der aktuellen Abfrage
									$this->user->rolle->delete_last_query();
									$last_query_deleted = true;
								}
								$this->user->rolle->save_last_query('Sachdaten', $layerset[$i]['Layer_ID'], $sql, $sql_order, $this->formvars['anzahl'], value_of($this->formvars, 'offset_'.$layerset[$i]['Layer_ID']));

								# Querymaps erzeugen
								if($layerset[$i]['querymap'] == 1 AND $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['the_geom']] >= '0' AND ($layerset[$i]['Datentyp'] == 1 OR $layerset[$i]['Datentyp'] == 2)){
									for($k = 0; $k < count($layerset[$i]['shape']); $k++) {
										$layerset[$i]['querymaps'][$k] = $this->createQueryMap($layerset[$i], $k);
									}
								}

								# Datendrucklayouts abfragen
								include_(CLASSPATH.'datendrucklayout.php');
								$this->ddl = new ddl($this->database);
								$layerset[$i]['layouts'] = $this->ddl->load_layouts($this->Stelle->id, NULL, $layerset[$i]['Layer_ID'], array(0,1));
							}
							$this->qlayerset[]=$layerset[$i];
						}
          }  break; # ende Layer ist aus postgis

          case MS_WMS : {
            $request=$layerset[$i]['connection'];

            # GetMap durch GetFeatureInfo ersetzen
            $request = str_ireplace('getmap','GetFeatureInfo',$request);
            $request = $request.'&REQUEST=GetFeatureInfo';

						if (strpos(strtolower($request), 'service=wms') === false){
							$request .='&SERVICE=WMS';
						}

						if (strpos(strtolower($request), 'version') === false){
							$request .='&VERSION=' . $layerset[$i]['wms_server_version'];
						}

						if (strpos(strtolower($request), 'styles') === false){
							$request .='&STYLES=';
						}

            # Anzufragenden Layernamen
						if (strpos(strtolower($request), 'query_layers') === false) {
							if (strpos(strtolower($request), 'layers') === false) {
								$request .='&QUERY_LAYERS=' . $layerset[$i]['wms_name'];
								$request .='&LAYERS=' . $layerset[$i]['wms_name'];
							}
							else {
								$reqStr=explode('&',stristr($request,'layers='));
								$layerStr=explode('=',$reqStr[0]);
								$request .='&QUERY_LAYERS='.$layerStr[1];
							}
						}

            # Boundingbox im System des Layers anhängen
            $projFROM = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
            $projTO = new projectionObj("init=epsg:" . $layerset[$i]['epsg_code']);

            $bbox = rectObj($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);

            $bbox = $this->pgdatabase->transformRect($bbox,$this->user->rolle->epsg_code,$layerset[$i]['epsg_code']);
            $bbox = $bbox[1];

            #$bbox->project($projFROM, $projTO);
            #echo $bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy.'<br>';

            $request .='&BBOX='.$bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy;
            $request .='&WIDTH='.$this->user->rolle->nImageWidth.'&HEIGHT='.$this->user->rolle->nImageHeight;

            # Anfrageposition und EPSG-Code anhängen
            $imgxy=explode(';',$this->formvars['INPUT_COORD']);
            $minxy=explode(',',$imgxy[0]);
            $maxxy=explode(',',$imgxy[1]);
            $x=($maxxy[0]+$minxy[0])/2;
            $y=($maxxy[1]+$minxy[1])/2;
						if($layerset[$i]['wms_server_version'] == '1.3.0'){
							$request .='&I='.$x.'&J='.$y;
							$request .='&CRS=EPSG:'.$layerset[$i]['epsg_code'];
						}
						else{
							$request .='&X='.$x.'&Y='.$y;
							$request .='&SRS=EPSG:'.$layerset[$i]['epsg_code'];
						}

            # Ausgabeformat
						if ($layerset[$i]['template'] != '') {		# getfeatureinfo.php
							if(strpos(strtolower($request), 'info_format') === false){
								$request .='&INFO_FORMAT=text/html';
							}
							$layerset[$i]['GetFeatureInfoRequest']=$request;
						}
						else {																		# GLE
							$request .='&INFO_FORMAT=application/vnd.ogc.gml';
							if($this->last_query != '' AND $this->last_query[$layerset[$i]['Layer_ID']]['sql'] != ''){
								$request = $this->last_query[$layerset[$i]['Layer_ID']]['sql'];
								if($this->formvars['anzahl'] == '')$this->formvars['anzahl'] = $this->last_query[$layerset[$i]['Layer_ID']]['limit'];
							}
							$response = url_get_contents($request, $layerset[$i]['wms_auth_username'], $layerset[$i]['wms_auth_password']);
							$url = $layerset[$i]['connection'];
							$version = $layerset[$i]['wms_server_version'];
							$epsg = $layerset[$i]['epsg_code'];
							$typename = $layerset[$i]['wms_name'];
							$namespace = substr($typename, 0, strpos($typename, ':'));
							include_(CLASSPATH.'wfs.php');
							$wfs = new wfs($url, $version, $typename, $namespace, $epsg, NULL, NULL);
							$wfs->gml = $response;
							$wfs->rename_features();
							$features = $wfs->extract_features();
							if (!empty($features)) {
								$f = 0;
								$layerset[$i]['count'] = count($features);
								for($j = 0; $j < $layerset[$i]['count']; $j++){
									if (array_key_exists('value', $features[$j])) {
										foreach($features[$j]['value'] as $attribute => $value){
											$layerset[$i]['shape'][$f][$attribute] = $value;
										}
										if ($features[$j]['geom'] != '') {
											$layerset[$i]['shape'][$f]['wfs_geom'] = $features[$j]['geom'];
										}
										$f++;
									}
								}
								foreach($layerset[$i]['shape'][0] as $attribute => $value){
									$layerset[$i]['attributes']['privileg'][] = 0;
									$layerset[$i]['attributes']['privileg'][$attribute] = 0;
									if ($attribute != 'wfs_geom') {
										$layerset[$i]['attributes']['visible'][] = 1;
									}
									$layerset[$i]['attributes']['name'][] = $attribute;
								}
								if(!$last_query_deleted){			# damit nur die letzte Query gelöscht wird und nicht eine bereits gespeicherte Query eines anderen Layers der aktuellen Abfrage
									$this->user->rolle->delete_last_query();
									$last_query_deleted = true;
								}
								$this->user->rolle->save_last_query('Sachdaten', $layerset[$i]['Layer_ID'], $request, NULL, $this->formvars['anzahl'], NULL);
							}
						}
						$this->qlayerset[]=$layerset[$i];
          }  break;

          case MS_WFS : { # WFS Layer (9)
						include_(CLASSPATH.'wfs.php');
            switch ($layerset[$i]['toleranceunits']) {
              case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
              case 'meters' : $pixsize=1; break;
              default : $pixsize=$this->user->rolle->pixsize;
            }
						$rect2 = rectObj($rect->minx, $rect->miny, $rect->maxx, $rect->maxy);		// rect kopieren, damit das Original nicht umprojeziert wird und beim nächsten Layer einen Fehler verursacht
            if($rect2->minx == $rect2->maxx AND $rect2->miny == $rect2->maxy){
            	$rand=$layerset[$i]['tolerance']*$pixsize;
            }
            $projFROM = new projectionObj("init=epsg:" . $this->user->rolle->epsg_code);
            $projTO = new projectionObj("init=epsg:" . $layerset[$i]['epsg_code']);
            $rect2->project($projFROM, $projTO);
            $searchbox_minx=strval($rect2->minx-$rand);
            $searchbox_miny=strval($rect2->miny-$rand);
            $searchbox_maxx=strval($rect2->maxx+$rand);
            $searchbox_maxy=strval($rect2->maxy+$rand);

						$bbox=$searchbox_minx.','.$searchbox_miny.','.$searchbox_maxx.','.$searchbox_maxy;
            $url = $layerset[$i]['connection'];
						$version = $layerset[$i]['wms_server_version'];
						$epsg = $layerset[$i]['epsg_code'];
            $typename = $layerset[$i]['wms_name'];
						$namespace = substr($typename, 0, strpos($typename, ':'));
						$username = $layerset[$i]['wms_auth_username'];
						$password = $layerset[$i]['wms_auth_password'];
						$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
						$this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
						$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
						$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], NULL, $privileges['attributenames']);
						$request = '';
						if($this->last_query != '' AND $this->last_query[$layerset[$i]['Layer_ID']]['sql'] != ''){
							$request = $this->last_query[$layerset[$i]['Layer_ID']]['sql'];
							if($this->formvars['anzahl'] == '')$this->formvars['anzahl'] = $this->last_query[$layerset[$i]['Layer_ID']]['limit'];
						}
            # Abfrage absetzen
            $request = $wfs->get_feature_request($request, $bbox, NULL, intval($this->formvars['anzahl']));
            $features = $wfs->extract_features();
						$layerset[$i]['count'] = count($features);
            for($j = 0; $j < $layerset[$i]['count']; $j++){
              for($k = 0; $k < count_or_0($layerset[$i]['attributes']['name']); $k++){
								$layerset[$i]['shape'][$j][$layerset[$i]['attributes']['name'][$k]] = $features[$j]['value'][$layerset[$i]['attributes']['name'][$k]];
              }
              $layerset[$i]['shape'][$j]['wfs_geom'] = $features[$j]['geom'];
							$layerset[$i]['shape'][$j]['wfs_bbox'] = $features[$j]['bbox'];
            }
						for($j = 0; $j < count_or_0($layerset[$i]['attributes']['name']); $j++){
							$layerset[$i]['attributes']['privileg'][$j] = $privileges[$layerset[$i]['attributes']['name'][$j]];
							$layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = $privileges[$layerset[$i]['attributes']['name'][$j]];
						}
						if($layerset[$i]['count'] > 0){
							if(!$last_query_deleted){			# damit nur die letzte Query gelöscht wird und nicht eine bereits gespeicherte Query eines anderen Layers der aktuellen Abfrage
								$this->user->rolle->delete_last_query();
								$last_query_deleted = true;
							}
							$this->user->rolle->save_last_query('Sachdaten', $layerset[$i]['Layer_ID'], $request, NULL, $this->formvars['anzahl'], NULL);
						}
						$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $this->pgdatabase, $layerset[$i]['shape'], true, $this->Stelle->id);
            $this->qlayerset[]=$layerset[$i];
          } break;

          default : { # alle anderen Layertypen
            $this->add_message('waring', 'Die Sachdatenabfrage für den connectiontype: ' . $layerset[$i]['connectiontype'] . ' wird nicht unterstützt.');
						$this->loadMap('DataBase');
						$this->user->rolle->newtime = $this->user->rolle->last_time_id;
						$this->saveMap('');
						$this->drawMap();
						$this->main = 'map.php';
						return;
					}
        } # ende Switch
      } # ende der Behandlung der zur Abfrage ausgewählten Layer
    } # ende der Schleife zur Abfrage der Layer der Stelle

/*		if ($this->formvars['printversion'] == '' AND $this->last_query != '' AND $this->user->rolle->querymode == 1) {
			# bei get_last_query (nicht aus Overlay) und aktivierter Datenabfrage in extra Fenster --> Laden der Karte und zoom auf Treffer
			$attributes = $this->qlayerset[0]['attributes'];
			$geometrie_tabelle = $attributes['table_name'][$attributes['the_geom']];
			$this->loadMap('DataBase');
			if(count($this->qlayerset[0]['shape']) > 0 AND ($this->qlayerset[0]['shape'][0][$attributes['the_geom']] != '')){			# wenn was gefunden wurde und der Layer Geometrie hat, auf Datensätze zoomen
				$this->zoomed = true;
				switch ($this->qlayerset[0]['connectiontype']) {
					case MS_POSTGIS : {
						for($k = 0; $k < count($this->qlayerset[0]['shape']); $k++){
							$oids[] = $this->qlayerset[0]['shape'][$k][$geometrie_tabelle.'_oid'];
						}
						$rect = $this->mapDB->zoomToDatasets($oids, $this->qlayerset[0]['oid'], $geometrie_tabelle, $attributes['real_name'][$attributes['the_geom']], 10, $layerdb, $this->qlayerset[0]['epsg_code'], $this->user->rolle->epsg_code);
						$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
						if (MAPSERVERVERSION > 600) {
							$this->map_scaledenom = $this->map->scaledenom;
						}
						else {
							$this->map_scaledenom = $this->map->scale;
						}
					}break;
					case MS_WFS : {
						$this->formvars['wkt'] = $this->qlayerset[0]['shape'][0]['wfs_geom'];
						$this->formvars['epsg'] = $this->qlayerset[0]['epsg_code'];
						$this->zoom2wkt();
					}break;
				}
			}
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->saveMap('');
		}*/
		$this->main = 'sachdatenanzeige.php';
	}

	function createReferenceMap($width, $height, $refwidth, $refheight, $angle, $minx, $miny, $maxx, $maxy, $zoomfactor, $refmapfile, $output_format = '') {
		$refmap = new mapObj($refmapfile);
		$refmap->width = $width;
		$refmap->height = $height;
		$refmap->setprojection("init=epsg:" . $this->user->rolle->epsg_code);
		$refmap->setextent($minx, $miny, $maxx, $maxy);
		# zoomen
		$oPixelPos = new PointObj();
		$oPixelPos->setXY($width / 2, $height / 2);
		//$refmap->zoomscale($scale,$oPixelPos,$width,$height,$refmap->extent,$this->Stelle->MaxGeorefExt);
		$refmap->zoompoint($zoomfactor, $oPixelPos, $width, $height, $refmap->extent, $refmap->extent);
		if ($output_format == '' AND $refmap->selectOutputFormat('jpeg_print') == 1) {
			$refmap->selectOutputFormat('jpeg');
		}
		$image_map = $refmap->draw();
		$filename = $this->map_saveWebImage($image_map,'jpeg');

		$image = imagecreatefromjpeg(IMAGEPATH . basename($filename));
		if ($angle != 0) {
			$rotatedimage = imagerotate($image, $angle, 0);
			$width = imagesx($rotatedimage);
			$height = imagesy($rotatedimage);
			$clipwidth = $refwidth;
			$clipheight = $refheight;
			$clipx = ($width - $clipwidth) / 2;
			$clipy = ($height - $clipheight) / 2;
			$image = imagecreatetruecolor($clipwidth, $clipheight);
			ImageCopy($image, $rotatedimage, 0, 0, $clipx, $clipy, $clipwidth, $clipheight);
		}
		# Rahmen
		$color = imagecolorallocate($image, 0, 0, 0);
		$x1 = ($refwidth + $refwidth/$zoomfactor) / 2;
		$y1 = ($refheight + $refheight/$zoomfactor) / 2;
		$x2 = $x1 - $refwidth / $zoomfactor;
		$y2 = $y1 - $refheight / $zoomfactor;
		imagerectangle($image, $x1, $y1, $x2, $y2, $color);
		imagejpeg($image, IMAGEPATH . basename($filename), 100);
		$newname = $this->user->id . basename($filename);
		rename(IMAGEPATH . basename($filename), IMAGEPATH . $newname);
		$uebersichtskarte = IMAGEURL . $newname;
		return $uebersichtskarte;
	}

	function spatial_processing() {
		include_(CLASSPATH . 'spatial_processor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if (!in_array($this->formvars['operation'], array('add_geometry', 'subtract_geometry', 'add_buffer_within_polygon', 'add_buffered_vertices'))) {
			$layerdb = $this->pgdatabase; 
		}
		else {
			$layerdb = $mapDB->getlayerdatabase($this->formvars['geom_from_layer'], $this->Stelle->pgdbhost);
			if ($layerdb == NULL) {
				$layerdb = $this->pgdatabase;
			}
		}
		$this->processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
		$this->processor->process_query($this->formvars);
	}

  function getRow() {
		$this->formvars['select'] = str_replace("''", "'", $this->formvars['select']);
		$this->formvars['where'] = str_replace("''", "'", $this->formvars['where']);
    $ret = $this->database->getRow($this->formvars['select'],$this->formvars['from'],$this->formvars['where']);
		$delim = '';
		foreach ($ret[1] as $key => $value) {
			$result .= $delim . $value;
			$delim = '█';
		}
		echo $result;
  }

  function layerfromMapfile(){
    $this->titel='Layer aus Mapdatei laden';
    $this->main='layerfrommapfile_formular.php';
  }

  function layerfromMapfile_addlayer($formvars){
    if($formvars['mapfilename'] != ''){
      $this->layercount = 0;
      $this->classcount = 0;
      $this->stylecount = 0;
      $this->labelcount = 0;
      $this->fontfilecount = 0;
      $this->fontsetcount = 0;
      $this->symbolcount = 0;
      $this->groupcount = 0;
      $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$this->mapobject = (MAPSERVERVERSION < 600) ? ms_newMapObj($formvars['mapfilename']) : new mapObj($formvars['mapfilename']);

      # Fonts
      if($this->formvars['checkfont'] AND $this->mapobject->fontsetfilename != ''){
        if(strpos($this->mapobject->fontsetfilename,'.') === 0){
          $fontsetfilename = trim($this->mapobject->fontsetfilename,'.');
          $fontpath = dirname($formvars['mapfilename']).$fontsetfilename;
          $new_fontset = file($fontpath);
          $kvwmap_fontset = fopen(FONTSET, 'a');
          for($i = 0; $i < count($new_fontset); $i++){
            # kvwmap-fontset erweitern
            fwrite($kvwmap_fontset, $new_fontset[$i]);
            $this->fontsetcount++;
            # Fontdateien kopieren
            $explosion = explode(' ', trim($new_fontset[$i]));
            $font = array_pop($explosion);
            if(file_exists(dirname($fontpath).'/'.$font)){  // Datei vorhanden?
              if(!file_exists(dirname(FONTSET).'/'.$font)){ // nichts überschreiben
                copy(dirname($fontpath).'/'.$font, dirname(FONTSET).'/'.$font);
                $this->fontfilecount++;
              }
            }
          }
          fclose($kvwmap_fontset);
        }
        else{
          echo 'Dieses Mapfile verweist zwar auf eine Fontdatei, die Pfadangabe ist jedoch nicht relativ.';
        }
      }

      # Symbole
      if($this->formvars['checksymbol'] AND $this->mapobject->symbolsetfilename != ''){
        if(strpos($this->mapobject->symbolsetfilename,'.') === 0){
          $kvwmap_symbolset = file(SYMBOLSET);
          while(strpos(array_pop($kvwmap_symbolset), 'END') === false){} // letztes END löschen
          $symbolsetfilename = trim($this->mapobject->symbolsetfilename,'.');
          $symbolpath = dirname($formvars['mapfilename']).$symbolsetfilename;
          $new_symbolset = file($symbolpath);
          while(strpos(array_shift($new_symbolset), 'SYMBOLSET') === false){} // SYMBOLSET am Anfang löschen
          $symbols = fopen(SYMBOLSET, 'w');
          for($i = 0; $i < count($kvwmap_symbolset); $i++){
            fwrite($symbols, $kvwmap_symbolset[$i]);
          }
          for($i = 0; $i < count($new_symbolset); $i++){
            fwrite($symbols, $new_symbolset[$i]);
            if(strpos($new_symbolset[$i], 'SYMBOL') !== false){
              $this->symbolcount++;
            }
          }
          fclose($symbols);
        }
        else{
          echo 'Dieses Mapfile verweist zwar auf eine Symboldatei, die Pfadangabe ist jedoch nicht relativ.';
        }
      }

      # Layerarray füllen und nach Gruppen sortieren
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        $layers[] = $this->mapobject->getLayer($i);
        if($layers[$i]->group == ''){
          $layers[$i]->group = 'Gruppe1';
        }
      }
      usort($layers, 'compare_groups');
      # Layer etc. in DB schreiben
      $lastgroup = NULL;
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        if($formvars['layer'.$i] != ''){
          $layer = $layers[$formvars['layer'.$i]];
          if($lastgroup != $layer->group){
            $group_id = $mapDB->newGroup($layer->group, 500);
            $this->groupcount++;
            $lastgroup = $layer->group;
          }
          $layer->group = $group_id;
          $layer_id = $mapDB->newLayer($layer);
          $this->layercount++;
          for($j = 0; $j < $layer->numclasses; $j++){
            $class = $layer->getClass($j);
            $class->layer_id = $layer_id;
            $class->drawingorder = $j;
            $class_id = $mapDB->new_Class($class);
            $this->classcount++;
            for($k = 0; $k < $class->numstyles; $k++){
              $style = $class->getStyle($k);
              $style_id = $mapDB->new_Style($style);
              $mapDB->addStyle2Class($class_id, $style_id, $k);
              $this->stylecount++;
            }
            $label = $class->label;
            if($label != NULL){
              $label_id = $mapDB->new_Label($label);
              $mapDB->addLabel2Class($class_id, $label_id);
              $this->labelcount++;
            }
          }
        }
      }
    }
    $this->layerfromMapfile();
  }

  function layerfromMapfile_load($formvars){
  	$_files = $_FILES;
    if($_files['mapfile']['name']){           # eine einzelne Mapdatei wurde ausgewählt
    $nachDatei = UPLOADPATH.$_files['mapfile']['name'];
    $this->formvars['mapfile'] = $nachDatei;
      if(move_uploaded_file($_files['mapfile']['tmp_name'],$nachDatei)){
        #echo '<br>Lade '.$_files['mapfile']['tmp_name'].' nach '.$nachDatei.' hoch';
				$this->mapobject = (MAPSERVERVERSION < 600) ? ms_newMapObj($nachDatei) : new mapObj($nachDatei);
        for($i = 0; $i < $this->mapobject->numlayers; $i++){
          $this->layers[] = $this->mapobject->getLayer($i);
        }
      }
    }
    elseif($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
    $this->formvars['zipfile'] = $_files['zipfile']['name'];
    $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
        #echo '<br>Lade '.$_files['zipfile']['tmp_name'].' nach '.$nachDatei.' hoch';
        # ersten Ordner im Archiv finden
        exec('unzip -l '.$nachDatei.' -d '.UPLOADPATH, $output);
        $line = $output[3];
        $explosion = explode('/', $line);
        if(count($explosion) > 1){
          # unzip
          exec('unzip '.$nachDatei.' -d '.UPLOADPATH);
          $explosion = explode(' ', $explosion[0]);
          $this->firstfolder = array_pop($explosion);
        }
        else{
          $folder = rand(0,10000);
          mkdir($folder);
          # unzip
          exec('unzip '.$nachDatei.' -d '.UPLOADPATH.$folder);
          $this->firstfolder = $folder;
        }
        $dir = searchdir(UPLOADPATH.$this->firstfolder, true);
        for($i = 0; $i < count($dir); $i++){
          $explosion = explode('.',$dir[$i]);
          if($explosion[count($explosion)-1] == 'map'){
            $this->mapfiles[] = $dir[$i];
          }
        }
      }
    }
    elseif($formvars['zipmapfile']){      # aus dem Zip-Archiv wurde eine Mapdatei ausgewählt
      $this->formvars['mapfile'] = $formvars['zipmapfile'];
			$this->mapobject = (MAPSERVERVERSION < 600) ? ms_newMapObj($formvars['zipmapfile']) : new mapObj($formvars['zipmapfile']);
      # Layerarray füllen und nach Gruppen sortieren
      for($i = 0; $i < $this->mapobject->numlayers; $i++){
        $this->layers[] = $this->mapobject->getLayer($i);
        if($this->layers[$i]->group == ''){
          $this->layers[$i]->group = 'Gruppe1';
        }
      }
      usort($this->layers, 'compare_groups');
      $this->firstfolder = $formvars['firstfolder'];
    }
    $this->layerfromMapfile();
  }

	function tooltip_query($rect){
		$showdata = 'true';
    $this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->queryrect = $rect;
		if ($this->formvars['querylayer_id'] != '' AND $this->formvars['querylayer_id'] != 'undefined') {
			if ($this->formvars['querylayer_id'] < 0) {
				$layerset=$this->user->rolle->getRollenLayer(-$this->formvars['querylayer_id']);
			}
			else {
				$layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
			}
		}
		else{
			$layerset = $this->user->rolle->getLayer('');
		}
    $anzLayer=count($layerset);
		$disabled_class_expressions = $this->user->rolle->read_disabled_class_expressions($layerset);
    #$map = new MapObj('');
    #$map->shapepath = SHAPEPATH;
		$found = false;
		$queryfield = ($this->user->rolle->singlequery == 2? 'thema' : 'qLayer');
    for ($i=0;$i<$anzLayer;$i++) {
			if ($found)break;		# wenn in einem Layer was gefunden wurde, abbrechen
			if ($layerset[$i]['connectiontype'] == 6 AND
					$layerset[$i]['queryable'] AND
					(
						(	# Karte
							(
								$this->formvars[$queryfield . $layerset[$i]['Layer_ID']] == '1' OR 
								$this->formvars[$queryfield . $layerset[$i]['requires']] == '1'
							) AND
							($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] >= $this->map_scaledenom) AND 
							($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] <= $this->map_scaledenom)
						)
						OR
						(	# Datensatz
							$this->formvars['querylayer_id'] != ''
						)
					)
				) {
				# Dieser Layer soll abgefragt werden
				$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);				
				$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
				$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);

				if ($layerset[$i]['maintable'] == '') {		# ist z.B. bei Rollenlayern der Fall
					$layerset[$i]['maintable'] = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
				}
				if ($layerset[$i]['oid'] == '' AND count_or_0($layerset[$i]['attributes']['pk']) == 1) {		# ist z.B. bei Rollenlayern der Fall
					$layerset[$i]['oid'] = $layerset[$i]['attributes']['pk'][0];
				}
				$query_parts = $this->mapDB->getQueryParts($layerset[$i], $privileges);
				$pfad = $query_parts['query'];
				
				if($rect->minx != ''){	####### Kartenabfrage
					$show = false;
					for($j = 0; $j < count_or_0($layerset[$i]['attributes']['name']); $j++){
						$layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
						if($layerset[$i]['attributes']['tooltip'][$j] == 1){
							$show = true;
						}
					}
					if(!$show){
						continue;
					}
				}
				$the_geom = $layerset[$i]['attributes']['the_geom'];
				# Aktueller EPSG in der die Abfrage ausgeführt wurde
				$client_epsg=$this->user->rolle->epsg_code;
				# EPSG-Code des Layers der Abgefragt werden soll
				$layer_epsg=$layerset[$i]['epsg_code'];

				if($rect->minx != ''){		################ Kartenabfrage ################
					switch ($layerset[$i]['toleranceunits']) {
						case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
						case 'meters' : $pixsize=1; break;
						default : $pixsize=$this->user->rolle->pixsize;
					}
					$rand=$layerset[$i]['tolerance']*$pixsize;
					# Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
					$loosesearchbox_wkt ="POLYGON((";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand).",";
					$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->miny-$rand).",";
					$loosesearchbox_wkt.=strval($rect->maxx+$rand)." ".strval($rect->maxy+$rand).",";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->maxy+$rand).",";
					$loosesearchbox_wkt.=strval($rect->minx-$rand)." ".strval($rect->miny-$rand)."))";

					# Wenn das Koordinatenssystem des Views anders ist als vom Layer wird die Suchbox und die Suchgeometrie
					# in epsg des layers transformiert
					if ($client_epsg!=$layer_epsg) {
						$sql_where =" AND ".$the_geom." && st_transform(st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
					}
					else {
						$sql_where =" AND ".$the_geom." && st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg.")";
					}

					# Wenn es sich bei der Suche um eine punktuelle Suche handelt, wird die where Klausel um eine
					if($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy AND $this->querypolygon == ''){
						if ($client_epsg!=$layer_epsg) {
							$sql_where.=" AND st_distance(".$the_geom.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
						}
						else {
							$sql_where.=" AND st_distance(".$the_geom.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
						}
						$sql_where.=" <= ".$rand;
					}
				}
				else{		################ mouseover auf Datensatz in Sachdatenanzeige ################
					$showdata = 'false';
					$sql_where = " AND ".pg_quote($layerset[$i]['maintable'].'_oid')." = '" . $this->formvars['oid'] . "'";
				}

				# SVG-Geometrie abfragen für highlighting
				if($layerset[$i]['attributes']['geomtype'][$the_geom] != 'POINT'){
					$rand = $this->map_scaledenom/1000;
					$tolerance = $this->map_scaledenom/10000;
					if($client_epsg == 4326){
						$tolerance = $tolerance / 60000;		# wegen der Einheit Grad
						$rand = $rand / 60000;		# wegen der Einheit Grad
					}
					$box_wkt ="POLYGON((";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
					$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand)."))";
					$query_parts['select'] .= ", st_assvg(st_transform(st_simplify(st_intersection(" . $the_geom . ", st_transform(st_geomfromtext('".$box_wkt."',".$client_epsg."), ".$layer_epsg.")), ".$tolerance."), ".$client_epsg."), 0, 15) AS highlight_geom";
				}
				else{
					$buffer = $this->map_scaledenom/260;
					$query_parts['select'] .= ", st_assvg(st_buffer(st_transform(" . $the_geom . ", ".$client_epsg."), ".$buffer."), 0, 15) AS highlight_geom";
				}

				if ($layerset[$i]['Filter'] != '') {
					$layerset[$i]['Filter'] = replace_params_rolle($layerset[$i]['Filter']);
					$sql_where .= " AND " . $layerset[$i]['Filter'];
				}
				# Filter auf Grund von ausgeschalteten Klassen hinzufügen
				if (QUERY_ONLY_ACTIVE_CLASSES AND array_key_exists($layerset[$i]['Layer_ID'], $disabled_class_expressions)) {
					foreach($disabled_class_expressions[$layerset[$i]['Layer_ID']] as $disabled_class) {
						$disabled_class_filter[$layerset[$i]['Layer_ID']][] = '(' . (mapserverExp2SQL($disabled_class['Expression'], $layerset[$i]['classitem']) ?: 'true') . ')';
					}
					$sql_where .= " AND COALESCE(NOT (" . implode(' OR ', $disabled_class_filter[$layerset[$i]['Layer_ID']]) . "), true)";
				}	
								
				$sql = "SELECT " . $query_parts['select'] . " FROM (SELECT " . $pfad . ") as query WHERE 1=1 ".$sql_where;

				# order by wieder einbauen
				if ($query_parts['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
					$sql .= $query_parts['orderby'];
				}

				# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
				$sql_limit =' LIMIT '.($layerset[$i]['max_query_rows'] ?: MAXQUERYROWS);

				#echo '<br>sql:<br>'.$sql;
				$ret=$layerdb->execSQL($sql.$sql_limit,4, 0);
				if (!$ret[0]) {
					while ($rs=pg_fetch_assoc($ret[1])) {
						$found = true;
						$layerset[$i]['shape'][]=$rs;
					}
				}

				# Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
				# Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
				$layerset[$i]['attributes'] = $this->mapDB->add_attribute_values($layerset[$i]['attributes'], $layerdb, $layerset[$i]['shape'], true, $this->Stelle->id);

				if($found)$this->qlayerset[]=$layerset[$i];
			}
    } # ende der Schleife zur Abfrage der Layer der Stelle
    # Tooltip-Abfrage
    if($found){
      for($i = 0; $i < count($this->qlayerset); $i++) {
      	$layer = $this->qlayerset[$i];
				$output .= $layer['Name_or_alias'].' : || ';
 				$attributes = $layer['attributes'];
        $anzObj = count($layer['shape']);
        for($k = 0; $k < $anzObj; $k++) {
          $attribcount = 0;
					$links = '';
					$pictures = '';
					$highlight_geom .= $layer['shape'][$k]['highlight_geom'].' ';
          for ($j = 0; $j < count($attributes['name']); $j++){
            if ($attributes['tooltip'][$j]){
							if ($attributes['alias'][$j] == '') {
								$attributes['alias'][$j] = $attributes['name'][$j];
							}
							if (substr($attributes['type'][$j], 0, 1) == '_'){
								$values = json_decode($layer['shape'][$k][$attributes['name'][$j]]);
							}
							else {
								$values = array($layer['shape'][$k][$attributes['name'][$j]]);
							}
							if(is_array($values)){
								foreach($values as $value){
									switch ($attributes['form_element_type'][$j]){
										case 'Dokument' : {
											$preview = $this->get_dokument_vorschau($value, $layer['document_path'], $layer['document_url']);
											$pictures .= '| ' . ($preview['doc_type'] == 'videostream' ? $preview['doc_src'] : $preview['thumb_src']);
										}break;
										case 'Link': {
											$attribcount++;
											if($value!='') {
												$link = 'xlink:'.$value;
												$links .= $link.'##';
											}
										} break;
										case 'Auswahlfeld': {
											$auswahlfeld_output = '';
											if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
												$auswahlfeld_output = $attributes['enum'][$j][$k][$value]['output'];
											}
											else{
												$auswahlfeld_output = $attributes['enum'][$j][$value]['output'];
											}
											$output .=  $attributes['alias'][$j].': ';
											$output .= $auswahlfeld_output;
											$output .= '##';
											$attribcount++;
										} break;
										case 'Radiobutton': {
											$radiobutton_output = $attributes['enum'][$j][$value]['output'];
											$output .=  $attributes['alias'][$j].': ';
											$output .= $radiobutton_output;
											$output .= '##';
											$attribcount++;
										} break;
										case 'Checkbox': {
											$output .=  $attributes['alias'][$j].': ';
											$value = str_replace('f', 'nein',  $value);
											$value = str_replace('t', 'ja',  $value);
											$output .= $value.'  ';
											$output .= '##';
										} break;
										default : {
											$output .=  $attributes['alias'][$j].': ';
											$attribcount++;
											$value = str_replace(chr(10), '##',  $value);
											$output .= $value.'  ';
											$output .= '##';
										}
									}
								}
							}
            }
          }
          # Links und Bild-URLs anfügen
          $output .= $links;
      		$output .= $pictures;
      		$pictures = '';
          $output .= '|| ';
        }
      }
      # highlighting-Geometrie anfügen
      $output .= '||| '.$highlight_geom;
      echo umlaute_javascript(umlaute_html($output)).'█root.showtooltip(root.document.GUI.result.value, '.$showdata.');';
    }
  }
	
	function setFullExtent() {
		$this->map->setextent($this->Stelle->MaxGeorefExt->minx,$this->Stelle->MaxGeorefExt->miny,$this->Stelle->MaxGeorefExt->maxx,$this->Stelle->MaxGeorefExt->maxy);
	}

	function zoomToGeom($geom,$border) {
    # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
    $sql.="SELECT st_xmin(st_extent('" . $geom."')) AS minx,st_ymin(st_extent('" . $geom."')) AS miny";
		$sql.=",st_xmax(st_extent('" . $geom."')) AS maxx,st_ymax(st_extent('" . $geom."')) AS maxy";
		$ret=$this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+1;
        $rs['minx']=$rs['minx']-1;
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+1;
        $rs['miny']=$rs['miny']-1;
      }
			$rect = rectObj(
				$rs['minx'],
				$rs['miny'],
      	$rs['maxx'],
				$rs['maxy']
			);
    	$randx=($rect->maxx-$rect->minx)*$border/100;
    	$randy=($rect->maxy-$rect->miny)*$border/100;
    	# Setzen der neuen Kartenausdehnung.
    	$this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
    }
  }

  function zoomToPolygon($table, $poly_id,$border, $srid) {
    $ret=$this->pgdatabase->getPolygonBBox($table, $poly_id, $srid);
    if ($ret[0]) {
      # Fehler bei der Abfrage der BoundingBox
      # Es erfolgt keine Änderung der aktuellen Ausdehnung
    }
    else {
      $rect=$ret[1];
      # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
      # Setzen der neuen Kartenausdehnung.
      #var_dump($this->map->extent);
      $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

	/**
	 * Function zoom to maximum extent of Layer with $layer_id in current map object $this->map
	 * @param int $layer_id Id of layer or rollenlayer to zoom to
	 * @return void
	 */
	function zoom_to_max_layer_extent($layer_id) {
		sanitize($layer_id, 'int');
		# Abfragen der maximalen Ausdehnung aller Daten eines Layers
		if($layer_id > 0) {
			$layer = $this->user->rolle->getLayer($layer_id);
		}
		else {
			$layer = $this->user->rolle->getRollenLayer(-$layer_id)[0];
		}
		switch ($layer['Datentyp']) {
			case MS_LAYER_POLYGON : case MS_LAYER_LINE : case MS_LAYER_POINT : {
				# Abfragen der Datenbankverbindung des Layers
				$layerdb = $this->mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
				$data = $layer['Data'];
				if ($data != '') {
					# suchen nach dem ersten Vorkommen von using
					$pos = strpos(strtolower($data), 'using ');
					# Abschneiden der using Wörter im Datastatement wenn unique verwendet wurde
					if ($pos !== false) {
						$subquery = substr($data, 0, $pos);
					}
					else {
						# using kommt nicht vor, es handelt sich um ein einfaches Data Statement in der Form
						# the_geom from tabelle, übernehmen wie es ist.
						$subquery = $data;
					}
					$explosion = explode(' ', $data);
					$this->attributes['the_geom'] = $explosion[0];
				}
				else {
					$subquery = substr($layer['pfad'], 7);
					$this->attributes = $this->mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
				}

				# Filter berücksichtigen
				$filter = $this->mapDB->getFilter($layer_id, $this->Stelle->id);
				if ($filter != '') {
					$filter = str_replace('$USER_ID', $this->user->id, $filter);
					$subquery .= ' WHERE ' . $filter;
				}

				# Erzeugen des Abfragestatements für den maximalen Extent aus dem Data String
				$sql = "
					SELECT
						st_xmin(extent) AS minx,
						st_ymin(extent) AS miny,
						st_xmax(extent) AS maxx,
						st_ymax(extent) AS maxy
					FROM
						(
							SELECT
								st_transform(
									st_setsrid(
										st_extent(" . $this->attributes['the_geom'] . "),
										" . $layer['epsg_code'] . "
									),
									" . $this->user->rolle->epsg_code . "
								) AS extent
							FROM
								(
									SELECT
										" . $subquery . "
								) AS fooForMaxLayerExtent
						) as foo
				";
				#echo $sql;

				# Abfragen der Layerausdehnung
				$ret = $layerdb->execSQL($sql, 4, 0);
				if ($ret[0]) {
					err_msg(htmlentities($_SERVER['PHP_SELF']), __LINE__, $sql);
					return 0;
				}
				$rs = pg_fetch_array($ret[1]);
			} break;

			case MS_LAYER_RASTER : {
				if ($layer[0]['Data'] != '') {
					# eine einzelne Rasterdatei
					$raster_file = SHAPEPATH . $layer['Data'];
					if (file_exists($raster_file)) {
						$output = rand(0, 100000);
						$command = OGR_BINPATH . 'gdalinfo "' . $raster_file . '" > ' . IMAGEPATH . $output . '.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH . $output . '.info');
						$ll = explode(', ', trim(get_first_word_after($infotext, 'Lower Left', '', ')'), ' ('));
						$ur = explode(', ', trim(get_first_word_after($infotext, 'Upper Right', '', ')'), ' ('));
					}
				}
				elseif ($layer['tileindex'] != '') {
					# ein Tile-Index
					$shape_file = SHAPEPATH.$layer[0]['tileindex'];
					if (file_exists($shape_file)) {
						$output = rand(0, 100000);
						$command = OGR_BINPATH . 'ogrinfo -al -so ' . $shape_file . ' > ' . IMAGEPATH . $output . '.info';
						exec($command);
						$infotext = file_get_contents(IMAGEPATH . $output . '.info');
						$extent = get_first_word_after($infotext, 'Extent:', ' ', chr(10));
						$corners = explode('-', $extent);
						$ll = explode(', ', trim($corners[0], '() '));
						$ur = explode(', ', trim($corners[1], '() '));
					}
				}
				$extent = new rectObj($ll[0], $ll[1], $ur[0], $ur[1]);
				$rasterProjection = new projectionObj("init=epsg:".$layer[0]['epsg_code']);
				$userProjection = new projectionObj("init=epsg:".$this->user->rolle->epsg_code);
				$extent->project($rasterProjection, $userProjection);
				$rs['minx'] = $extent->minx;
				$rs['maxx'] = $extent->maxx;
				$rs['miny'] = $extent->miny;
				$rs['maxy'] = $extent->maxy;
			} break;
		}
		if ($rs['minx'] != '') {
			if ($this->user->rolle->epsg_code == 4326) {
				$rand = 10/10000;
			}
			else {
				$rand = 10;
			}
			$minx = $rs['minx'] - $rand;
			$maxx = $rs['maxx'] + $rand;
			$miny = $rs['miny'] - $rand;
			$maxy = $rs['maxy'] + $rand;
			#echo 'box:'.$minx.' '.$miny.','.$maxx.' '.$maxy;
			$this->map->setextent($minx, $miny, $maxx, $maxy);
			# damit nicht außerhalb des Stellen-Extents oder des maximalen Layer-Maßstabs gezoomt wird
			$oPixelPos = new PointObj();
			$oPixelPos->setXY($this->map->width / 2, $this->map->height / 2);
			if ($layer['maxscale'] > 0 AND $layer['maxscale'] < $this->map->scaledenom) {
				$nScale = $layer['maxscale'] - 1;
			}
			else {
				$nScale = $this->map->scaledenom;
			}
			$this->map->zoomscale($nScale, $oPixelPos, $this->map->width, $this->map->height, $this->map->extent, $this->Stelle->MaxGeorefExt);
			$this->map_scaledenom = $this->map->scaledenom;
		}
	}

	/**
	* Function erzeugt eine MapServer Query Map vom $k-ten Feature im layerset
	* und liefert den Pfad der Datei zurück
	* @param array $layerset Der Satz des Layers von dem die Karte ausgegeben werden soll
	* @param integer $k Der Indexwert von dem Feature innerhalb des layerset
	* @return string Der Pfad auf die Bilddatei der Querymap. Wenn das Feature keine Geometrie hat,
	* wird der Pfad zur Datei nogeom.png in GRAPHICSPATH zurückgegeben.
	*/
	function createQueryMap($layerset, $k) {
		global $language;
		$geom = $layerset['shape'][$k][$layerset['attributes']['the_geom']];
		if ($geom != '') {
			$layer_id = $layerset['Layer_ID'];
			$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
			$map = new mapObj(NULL);
			$map->debug = 5;
			$layerdb = $mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
			# Auf den Datensatz zoomen
			$sql = "
				SELECT
					st_xmin(bbox) AS minx,
					st_ymin(bbox) AS miny,
					st_xmax(bbox) AS maxx,
					st_ymax(bbox) AS maxy
				FROM
					(
						SELECT
							box2D(st_transform('" . $geom . "'::geometry, " . $this->user->rolle->epsg_code . ")) as bbox
					) AS foo
			";
			$ret = $layerdb->execSQL($sql, 4, 0);
			$rs = pg_fetch_array($ret[1]);
			$rect = rectObj(
				$rs['minx'],
				$rs['miny'],				
				$rs['maxx'],
				$rs['maxy']
			);
			$randx = ($rect->maxx-$rect->minx) * 50 / 100 + 0.01;
			$randy = ($rect->maxy-$rect->miny) * 50 / 100 + 0.01;
			if ($rect->minx != '') {
				$map->setextent($rect->minx - $randx, $rect->miny - $randy, $rect->maxx + $randx, $rect->maxy + $randy);
				# Haupt-Layer erzeugen
				$layer = new LayerObj($map);
				# Parameter $scale in Data ersetzen
				$layerset['Data'] = str_replace('$SCALE', $this->map_scaledenom ?: 1000, $layerset['Data']);
				$layer->data = $layerset['Data'];
				if ($layerset['Filter'] != '') {
					if (substr($layerset['Filter'], 0, 1) == '(') {
						switch (true) {
							case MAPSERVERVERSION >= 800 : {
								$layer->setProcessingKey('NATIVE_FILTER', $layerset['Filter']);
							}break;
							case MAPSERVERVERSION >= 700 : {
								$layer->setProcessing('NATIVE_FILTER='.$layerset['Filter']);
							}break;
							default : {
								$layer->setFilter($layerset['Filter']);
							}
						}
					}
					else {
						$expr = buildExpressionString($layerset['Filter']);
						$layer->setFilter($expr);
					}
				}
				$layer->status = MS_ON;
				$layer->template = ' ';
				$layer->name = 'querymap' . $k;
				$layer->type = $layerset['Datentyp'];
				$layer->setConnectionType(6, '');
				$layer->connection = $layerset['connection'];
				$layer->setProjection('+init=epsg:' . $layerset['epsg_code']);
				$layer->metadata->set('wms_queryable', '0');
				$klasse = new ClassObj($layer);
				$klasse->status = MS_ON;
				$style = new StyleObj($klasse);
				$style->color->setRGB(12, 255, 12);
				$style->width = 1;
				$style->outlinecolor->setRGB(110, 110, 110);
				# Datensatz-Layer erzeugen
				$layer = new LayerObj($map);
				$datastring  = "the_geom from (select	'" . $geom . "'::geometry as the_geom, 1 as id) as foo using unique id using srid=" . $layerset['epsg_code'];
				$layer->data = $datastring;
				$layer->status = MS_ON;
				$layer->template = ' ';
				$layer->name = 'querymap' . $k;
				$layer->type = $layerset['Datentyp'];
				$layer->setConnectionType(6, '');
				$layer->connection = $layerset['connection'];
				$layer->setProjection('+init=epsg:' . $layerset['epsg_code']);
				$layer->metadata->set('wms_queryable', '0');
				$klasse = new ClassObj($layer);
				$klasse->status = MS_ON;
				$style = new StyleObj($klasse);
				$style->color->setRGB(255, 5, 12);
				$style->width = 2;
				$style->outlinecolor->setRGB(0,0,0);
				# Karte rendern
				$map->setProjection('+init=epsg:' . $this->user->rolle->epsg_code);
				$map->web->imagepath = IMAGEPATH;
				$map->web->imageurl = IMAGEURL;
				$map->width = 50;
				$map->height = 50;
				#$map->save('/var/www/logs/test.map');
				$image_map = $map->draw();
				$filename = $this->map_saveWebImage($image_map, 'jpeg');
				$newname = $this->user->id . basename($filename);
				rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
				return IMAGEURL . $newname;
			}
			else {
				return GRAPHICSPATH . 'nogeom.png';
			}
		}
	}

	function create_query_rect($input_coords){
		if($input_coords != ''){
			$corners=explode(';', $input_coords);
			if(count($corners) < 3){
				$lo=explode(',',$corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
				if(count($corners) == 1){		# Polygonquery mit nur einer Koordinate (Doppelklick)
					$minx = $maxx = $lo[0];
					$miny = $maxy = $lo[1];
				}
				else{		# normale Query (Punkt oder Rechteck)
					$ru=explode(',',$corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
					$width=$this->user->rolle->pixsize*($ru[0]-$lo[0]); # Breite des Auswahlbereiches in m
					$height=$this->user->rolle->pixsize*($ru[1]-$lo[1]); # Höhe des Auswahlbereiches in m
					#echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
					# linke obere Ecke im Koordinatensystem in m
					$minx=$this->user->rolle->oGeorefExt->minx+$this->user->rolle->pixsize*$lo[0]; # x Wert
					$miny=$this->user->rolle->oGeorefExt->miny+$this->user->rolle->pixsize*($this->user->rolle->nImageHeight-$ru[1]); # y Wert
					$maxx=$minx+$width;
					$maxy=$miny+$height;
				}
				$rect = rectObj($minx,$miny,$maxx,$maxy);
			}
			else{
				$polygon = 'POLYGON((';
				for($i = 0; $i < count($corners); $i++){
					$coord = explode(',',$corners[$i]);
					$coordx[$i] = $coord[0];
					$coordy[$i] = $coord[1];
					$polygon .= $coordx[$i].' '.$coordy[$i].',';
				}
				$polygon .= $coordx[0].' '.$coordy[0].'))';
				$rect = $polygon;
			}
			return $rect;
		}
	}

  function zoomToRefExt() {
    # Zoomen auf den in der Referenzkarte gesetzten Punkt
    # Berechnen der Koordinaten des angeklickten Punktes in der Referencekarte
    $refmapwidthm=($this->reference_map->reference->extent->maxx-$this->reference_map->reference->extent->minx);
    $refmappixsize=$refmapwidthm/$this->reference_map->reference->width;
    $refmapxposm=$this->reference_map->reference->extent->minx+$refmappixsize*$this->formvars['refmap_x'];
    $refmapyposm=$this->reference_map->reference->extent->maxy-$refmappixsize*$this->formvars['refmap_y'];

		$point = new PointObj();
	  $point->setXY($refmapxposm, $refmapyposm);

		if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
			$projFROM = new projectionObj('init=epsg:' . $this->ref['epsg_code']);
			$projTO = new projectionObj('init=epsg:' . $this->user->rolle->epsg_code);
			$point->project($projFROM, $projTO);
		}

		$halfmapwidthm=($this->map->extent->maxx-$this->map->extent->minx)/2;
    $halfmapheight=($this->map->extent->maxy-$this->map->extent->miny)/2;
    $zoommaxx=$point->x + $halfmapwidthm;
    $zoomminx=$point->x - $halfmapwidthm;
    $zoommaxy=$point->y + $halfmapheight;
    $zoomminy=$point->y - $halfmapheight;

    $this->map->setextent($zoomminx,$zoomminy,$zoommaxx,$zoommaxy);
    $oPixelPos = new PointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    $this->saveMap('');
  }

	function deleteRollenlayer() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$mapDB->deleteRollenlayer($this->formvars['id'], $this->formvars['delete_rollenlayer_type']);
		$this->loadMap('DataBase');
		// $currenttime=date('Y-m-d H:i:s',time());
		// $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		// $this->saveMap('');
		// $this->drawMap();
		$this->legende = $this->create_dynamic_legend();
		$this->output();
	}

	/*
	* create schema shared_tables if not exist
	* copy rollenlayer table to schema shared_tables
	* create new layer based on rollenlayer with layer_id in group with shared_layergroup_id
	* and mark as shared layer through setting shared_from = current_user_id
	* assign this new layer to all stellen that have show_shared_layers = true
	* assign it to all users that are in this stellen and with active for current_user
	* reload the map
	*/
	function share_rollenlayer() {
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		if (!$ret = $mapDB->read_RollenLayer($this->formvars['selected_rollenlayer_id'])) {
			return 0; // do not create the layer
		}
		# Set initial values from rollenlayer
		$layer = $ret[0];

		# overwrite some values for shared layer
		$layer['id'] 					= ''; // it shall become a new layer
		$layer['alias'] 			= '';
		$layer['connection'] 	= '';
		$layer['oid'] 				= 'gid';
		$layer['old_id'] 			= -$this->formvars['selected_rollenlayer_id'];
		$layer['Name'] 				= $this->formvars['layer_options_name'];
		$layer['Name_or_alias'] = $this->formvars['layer_options_name'];
		$layer['Gruppe'] 			= $this->formvars['shared_layer_group_id'];
		$shared_layer_schema_name = ($this->formvars['shared_layer_schema_name'] == '' ? 'shared' : $this->formvars['shared_layer_schema_name']);
		$layer['schema'] 			= $shared_layer_schema_name;
		# Assume that query has the form "SELECT * FROM rollenlayertable"
		# as it should be for rollenlayer
		$rollenlayer_table 		= get_first_word_after($layer['query'], 'FROM');
		$shared_layer_table_name = ($this->formvars['shared_layer_table_name'] == '' ? $rollenlayer_table : $this->formvars['shared_layer_table_name']);
		if ($this->pgdatabase->table_exists($shared_layer_schema_name, $shared_layer_table_name)) {
			$shared_layer_table_name = $shared_layer_table_name  . '_' .  strval(rand(100, 999));
		}
		$layer['maintable'] = $shared_layer_table_name;
		$layer['pfad'] = str_replace(
			$rollenlayer_table,
			$shared_layer_table_name,
			$layer['query']
		);
		unset($layer['query']);
		$layer['Data'] 				= str_replace(
			CUSTOM_SHAPE_SCHEMA . '.' . $rollenlayer_table,
			$shared_layer_schema_name . '.' . $shared_layer_table_name,
			$layer['Data']
		);
		$layer['queryable'] 			= '1';
		$layer['use_geom'] 				= 1;
		$layer['query_map'] 			= ($layer['Datentyp'] == 0 ? '0' : '1'); // not for points
		$layer['privileg'] 				= 0;
		$layer['export_privileg'] = 1;
		$layer['editable'] 				= 1;
		$layer['listed'] 					= 1;
		$layer['shared_from'] 		= $this->user->id;

		# create share schema if not exists and copy rollenlayer table to shared layer table
		$sql = "
			CREATE SCHEMA IF NOT EXISTS " . $shared_layer_schema_name . ";
			CREATE TABLE " . $shared_layer_schema_name . "." . $shared_layer_table_name . " AS
			SELECT * FROM " . CUSTOM_SHAPE_SCHEMA .  "." . $rollenlayer_table . "
		";

		$ret = $this->pgdatabase->execSQL($sql, 4, 0, false);
		if (!$ret['success']) { $this->add_message('error', err_msg('kvwmap.php', __LINE__, $sql)); return 0; }

		# Set formvars from layer
		$this->formvars = array_merge($layer, $this->formvars);
		$this->LayerAnlegen();

		# Assign new layer $this->formvars['selected_layer_id'] to alle stellen that allow shared layers
		$shared_stellen = $this->Stelle->getStellen('', $this->user->id, '`show_shared_layers`');
		$this->addLayersToStellen(
			array($this->formvars['selected_layer_id']),
			$shared_stellen['ID'],
			NULL,
			NULL,
			$this->formvars['layer_options_privileg']
		);

		# ToDo:
		# - limit for the size of shared layers?
	}

	function get_layer_params_form($stelle_id = NULL, $layer_id = NULL, $only_from_attribute = '', $hover_preview = true, $open = false){
		$output = '';
		include_once(CLASSPATH.'FormObject.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($layer_id == NULL){
			if($stelle_id == NULL){			# Parameter der aktuellen Stelle abfragen
				$stelle = $this->Stelle;
				$rolle = $this->user->rolle;
			}
			else{												# Parameter einer anderen Stelle abfragen
				$stelle = new stelle($stelle_id, $this->database);
				$rolle = new rolle($this->user->id, $stelle_id, $this->database);
				$rolle->readSettings();
			}
			$this->params_layer = $mapDB->get_layer_params_layer();
			$selectable_layer_params = $stelle->selectable_layer_params;
		}
		else{		# Parameter abfragen, die nur dieser Layer hat
			$selectable_layer_params = implode(', ', array_keys($mapDB->get_layer_params_layer(NULL, $layer_id, $only_from_attribute)));
			$rolle = $this->user->rolle;
		}
		$params = $rolle->get_layer_params($selectable_layer_params, $this->pgdatabase);
		if ($params['error_message'] != '') {
			$this->add_message('error', $params['error_message']);
		}
		else {
			if (!empty($params)) {
				if($layer_id == NULL){
					$output .= '
						<table style="border: 1px solid #ccc" class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett">&nbsp;'.$this->strLayerParameters.'</span></td>
							</tr>
							<tr>
								<td class="rollenwahl-option-data">
									<table>';
				}
									foreach($params AS $param) {
										$output .= '
										<tr id="layer_parameter_'.$param['key'].'_tr">';
											if (!$open) {
												$output .= '
											<td valign="top" class="rollenwahl-option-header">
												<span>' . $param['alias'] .
												 ((count_or_0($this->params_layer[$param['id']]) == 1 AND $layer_id == NULL)? ' (' . $this->params_layer[$param['id']][0]['Name'] . ')' : '') . ':
												</span>
											</td>';
											}
											$output .= '
											<td>
												'.($layer_id == NULL ?
													FormObject::createSelectField(
														'options_layer_parameter_' . $param['key'],		# name
														$param['options'],										# options
														rolle::$layer_params[$param['key']],	# value
														1,																		# size
														'width: 110px',												# style
														'onLayerParameterChanged(this);',			# onchange
														'layer_parameter_' . $param['key'],		# id
														'',																		# multiple
														'',																		# class
														''																		# firstoption
													)
													:
													FormObject::createCustomSelectField(
														'options_layer_parameter_' . $param['key'],										# name
														$param['options'],																						# options
														rolle::$layer_params[$param['key']],													# value
														1,																														# size
														'',																														# style
														($open? "setLayerParam('" . $param['key'] . "')" : ''),				# onchange
														'layer_parameter_' . $param['key'],														# id
														'',																														# multiple
														'',																														# class
														'',																														# firstoption
														'', 																													# option_style
														'', 																													# option_class
														'',																														# onclick
														($hover_preview ? "if (document.SVG.getSVGDocument().getElementById('mapimg3') == null) {custom_select_register_keydown();add_split_mapimgs();get_map(mapimg3, 'not_layer_id=" . $layer_id . "');}" : ""),				# onmouseenter
														($hover_preview ? "get_map(mapimg0, 'only_layer_id=" . $layer_id . "&layer_params[" . $param['key'] . "]=' + this.dataset.value); get_layer_legend(" . $layer_id . ", '&layer_params[" . $param['key'] . "]=' + this.dataset.value);" : "")		# option_onmouseenter
													)
												).'
											</td>
										</tr>';
									}
				if($layer_id == NULL){
					$output .='	</table>
							</td>
						</tr>
					</table>
					';
				}
				if ($open) {
					$output .= "█";
					if (count($params) == 1) {
						$output .= "document.querySelector('#options_" . $layer_id . " .placeholder').onmouseenter();
											  document.querySelector('#options_" . $layer_id . " .placeholder').onclick();";
					}
				}
			}
		}
		return $output;
	}
	
	function check_class_completenesses($layerdaten = array()) {
		$this->main = 'class_completeness_check.php';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    if (count($layerdaten) == 0) {
  		$layerdaten = $mapDB->get_layers_of_type(MS_POSTGIS, NULL);
    }
		for ($i = 0; $i < count($layerdaten['ID']); $i++) {
			$this->class_completeness_result .= '<a target="_blank" href="index.php?go=Layereditor&selected_layer_id=' . $layerdaten['ID'][$i] . '&csrf_token=' . $_SESSION['csrf_token'] . '">Layer: ' . layer_name_with_alias($layerdaten['Bezeichnung'][$i], $layerdaten['Name_or_alias'][$i], array('alias_first' => true, 'brace_type' => 'round')) . '</a><br>';
			$this->formvars['layer_id'] = $layerdaten['ID'][$i];
			$result = $this->check_class_completeness();
			$this->class_completeness_result .= $result['html'];
		}
		$this->output();
	}
	
	function check_class_completeness($output_html = false) {
    $html = '';
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		if ($this->formvars['layer_id']) {
			$expressions = [];
			$this->show_attribute = [];
			$this->layerdaten = $mapDB->get_Layer($this->formvars['layer_id'], true);
			$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
			$select = $mapDB->getSelectFromData($this->layerdaten['Data']);
			$classes = $mapDB->read_Classes($this->layerdaten['Layer_ID']);
			$anzahl = count($classes);
			for ($i = 0; $i < $anzahl; $i++) {
				if ($classes[$i]['Expression'] != '' AND strpos($classes[$i]['Expression'], 'Cluster_FeatureCount') === false) {
					$expressions[$classes[$i]['classification']][] = mapserverExp2SQL($classes[$i]['Expression'], $this->layerdaten['classitem']);
				}
			}
			if (empty($expressions)) {
				$html .= '<div style="background: lightgray; padding: 2px">Keine Expressions vorhanden.</div><br>';
				if ($output_html) {
					return $html;
				}
				else {
					return array(
						'success' => true,
						'msg' => $html,
						'html' => $html,
						'num_unclassified' => 0
					);
				}
			}
			else {
				foreach ($expressions as $classification => $exps) {
					$sql = "
						SELECT
							*
						FROM
							(
								" . $select . "
							) AS foo
						WHERE
							NOT (" . implode(" OR ", $exps) . ")
					";
					$this->debug->write("<p>file:kvwmap class:db_mapObj->getClassFromObject - Lesen einer Klasse eines Objektes:<br>" . $sql,4);
					$ret = $layerdb->execSQL($sql, 4, 0);
					if ($ret['success']) {
						$num_unclassified = pg_num_rows($ret[1]);
						$html .= '<div style="background: ' . ($num_unclassified == 0 ? 'lightgreen' : '#fd907d') . '; padding: 2px">Klassifizierung ' . $classification;
						if ($num_unclassified == 0) {
							$html .= ' vollständig.</div>';
						}
						else {
							while ($rs = pg_fetch_assoc($ret[1])) {
								$ids[] = "'" . $rs[$this->layerdaten['oid']] . "'";
								if ($this->formvars['show_attribute'] != '') {
									$this->show_attribute[$this->formvars['show_attribute']][$rs[$this->formvars['show_attribute']]] = 1;
								}
							}
							$html .= ' unvollständig. Es gibt ' . $num_unclassified.' Objekte, die keiner Expression entsprechen.</div>';
							$html .= '<a href="javascript:void(0);" onclick="this.nextElementSibling.style.display = \'\'"> ->SQL </a><textarea style="display: none">' . $sql . '</textarea><br>';
							$html .= '<a target="blank1" href="index.php?go=Layer-Suche_Suchen&selected_layer_id=' .$this->layerdaten['Layer_ID']. '&value_' . $this->layerdaten['maintable'] . '_oid=(' . implode(',', $ids) . ')&operator_' . $this->layerdaten['maintable'] . '_oid=IN"> -> Objekte anzeigen</a>';
							foreach ($this->show_attribute as $attributename => $values) {
								$html .= '<br>' . $attributename . ': '. implode(', ', array_keys($values));
							}
							$html .= '<br>';
						}
					}
					else {
						$html .= '<div style="background: #fd907d">' . $ret[1] . '</div>';
						$html .= '<a href="javascript:void(0);" onclick="this.nextElementSibling.style.display = \'\'"> ->SQL </a><textarea style="display: none">' . $sql . '</textarea><br>';
					}
				}
			}
			$html .= '<br>';
		}
		else {
			return array(
				'success' => false,
				'msg' => 'Keine Konvertierung ID angegeben!'
			);
		}
		if ($output_html) {
			return $html;
		}
		else {
			return array(
				'success' => $ret['success'],
				'msg' => $ret['msg'],
				'html' => $html,
				'num_unclassified' => $num_unclassified
			);
		}
	}
	
		/**
	 * Function create the filter statement for layer with $layer_id in stelle with $stelle_id based on $filter settings
	 * and write it to the $database.
	 * @param array $filter The filter settings
	 * @param integer $epsg_code The epsg_code of the geometry of the layer
	 * @return boolean Return 0 in case of error
	 */
	function compose_filter($filter, $epsg_code) {
		$filterstring = '';
		if (!empty($filter)) {
			$filterparts = array();
			for ($i = 0; $i < count($filter); $i++) {
				if ($filter[$i]['type'] == 'geometry') {
					$poly_geom = $this->pgdatabase->getpolygon($filter[$i]['attributvalue'], $epsg_code);
					#$filterparts[] = $filter[$i]['attributname'].' && \''.$poly_geom.'\'';		// ist ja bei within und intersects schon mit drin
					$filterparts[] = $filter[$i]['operator'] . "(" . $filter[$i]['attributname'] . ",'" . $poly_geom . "'::geometry)";
				}
				else {
					if ($filter[$i]['operator'] == 'IS') {
						$filterparts[] = $filter[$i]['attributname'] . ' ' . $filter[$i]['operator'] . ' ' . $filter[$i]['attributvalue'];
					}
					elseif ($filter[$i]['operator'] == 'IN') {
						if ($filter[$i]['type'] == 'varchar' OR $filter[$i]['type'] == 'text') {
							$filter[$i]['attributvalue'] = implode(
								',',
								array_map(
									function($value) {
										return "'" . $value . "'";
									},
									explode(',', $filter[$i]['attributvalue'])
								)
								);
						}
						$filterparts[] = $filter[$i]['attributname'] . " " . $filter[$i]['operator'] . " (" . $filter[$i]['attributvalue'] . ")";
					}
					elseif (strpos($filter[$i]['attributvalue'], '$') !== false) {
						$filterparts[] = "COALESCE(CAST(" . $filter[$i]['attributname'] . " AS text), '') = '" . $filter[$i]['attributvalue'] . "'";
					}
					else {
						$filterparts[] = $filter[$i]['attributname'] . " " . $filter[$i]['operator'] . " " . quote($filter[$i]['attributvalue'], $filter[$i]['type']);
					}
				}
			}
			$filterstring = '(' . implode(' AND ', $filterparts) . ')';
		}
		return $filterstring;
	}
} # end of class GUI

class db_mapObj{
  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
	var $db;
	var $OhneRequires;
	var $disabled_classes;
	var $script_name;
	var $GUI;
	var $rolle;

	function __construct($Stelle_ID, $User_ID) {
		global $debug;
		global $GUI;
		$this->script_name = 'db_MapObj.php';
		$this->debug = $debug;
		$this->GUI = $GUI;
		$this->db = $GUI->database;
		$this->Stelle_ID = $Stelle_ID;
		$this->User_ID = $User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $this->db);
	}

	function read_ReferenceMap() {
    $sql = "
			SELECT
				r.*
			FROM
				referenzkarten AS r,
				stelle AS s
			WHERE
				r.ID = s.Referenzkarte_ID
    		AND s.ID = " . $this->Stelle_ID . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>",4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
		$rs = $this->db->result->fetch_assoc();
    $this->referenceMap = $rs;
#		echo '<br>sql: ' . print_r($sql, true);
#		echo '<br>ref: ' . print_r($this->referenceMap, true);
    return $rs;
  }

	function read_RollenLayer($id = NULL, $typ = NULL, $autodelete = NULL) {
		$sql = "
			SELECT DISTINCT
				l.`id`,
				l.`user_id`,
				l.`stelle_id`,
				l.`aktivStatus`,
				l.`queryStatus`,
				l.`Name`,
				l.`Name` as alias,
				l.`Gruppe`,
				l.`Typ`,
				l.`Datentyp`,
				l.`Data`,
				l.`query`,
				l.`connectiontype`,
				l.connection_id,
				l.wms_auth_username,
				l.wms_auth_password,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', l.user_id)
					ELSE l.connection
				END as connection,
				l.`epsg_code`,
				l.`transparency`,
				l.`buffer`,
				l.`labelitem`,
				l.`classitem`,
				l.`gle_view`,
				l.`rollenfilter`,
				l.`duplicate_from_layer_id`,
				l.`duplicate_criterion`,
				g.Gruppenname,
				-l.id AS Layer_ID,
				1 as showclasses,
				CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable,
				CASE WHEN rollenfilter != '' THEN concat('(', rollenfilter, ')') END as Filter,
				'' as wms_name,
				'' as wms_format,
				'' as wms_server_version,
				'' as wms_connectiontimeout,
				'' as oid
			FROM
				rollenlayer AS l JOIN
				u_groups AS g ON l.Gruppe = g.id LEFT JOIN
				connections AS c ON l.connection_id = c.id
			WHERE
				l.stelle_id=" . $this->Stelle_ID . " AND
				l.user_id = " . $this->User_ID .
				($id != NULL ? " AND l.id = " . $id : '') .
				($typ != NULL ? " AND l.Typ = '" . $typ . "'" : '') . 
				($autodelete != NULL ? " AND l.autodelete = '" . $autodelete . "'" : '') . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>",4);
		#echo '<p>SQL zur Abfrage der Rollenlayer: ' . $sql;
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$Layer = array();
		while ($rs = $ret['result']->fetch_array()) {
			$rs['Class'] = $this->read_Classes(-$rs['id'], $this->disabled_classes);
			foreach (array('Name', 'alias', 'connection', 'classification', 'classitem', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
				);
			}
			$rs['alias_link'] = $rs['alias'];
			$rs['Name_or_alias'] = $rs['alias'];
			$Layer[] = $rs;
		}
		return $Layer;
	}

	function read_Layer($withClasses, $useLayerAliases = false, $groups = NULL) {
		global $language;

		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
			$group_column = '
			CASE
				WHEN `Gruppenname_' . $language . '` IS NOT NULL THEN `Gruppenname_' . $language . '`
				ELSE `Gruppenname`
			END AS Gruppenname';
		}
		else {
			$name_column = "l.Name";
			$group_column = 'Gruppenname';
		}

		$sql = "
			SELECT DISTINCT
				l.oid,
				coalesce(rl.transparency, ul.transparency, 100) as transparency,
				rl.`aktivStatus`,
				rl.`queryStatus`,
				rl.`gle_view`,
				rl.`showclasses`,
				rl.`logconsume`,
				rl.`rollenfilter`,
				ul.`queryable`,
				COALESCE(rl.drawingorder, l.drawingorder) as drawingorder,
				ul.legendorder,
				ul.`minscale`, ul.`maxscale`,
				ul.`offsite`,
				ul.`postlabelcache`,
				ul.`Filter`,
				ul.`template`,
				ul.`header`,
				ul.`footer`,
				ul.`symbolscale`,
				ul.`logconsume`,
				ul.`requires`,
				ul.`privileg`,
				ul.`export_privileg`,
				ul.`group_id`,
				l.Layer_ID," .
				$name_column . ",
				l.alias,
				l.Datentyp, COALESCE(ul.group_id, l.Gruppe) AS Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, coalesce(rl.labelitem, l.labelitem) as labelitem, rl.labelitem as user_labelitem,
				l.labelmaxscale, l.labelminscale, l.labelrequires,
				l.connection_id,
				CASE
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', gr.user_id)
					ELSE l.connection
				END as connection,
				l.printconnection,
				l.connectiontype,
				l.classitem, l.styleitem, l.classification,
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.sizeunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_keywordlist, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume, l.metalink, l.terms_of_use_link, l.status, l.trigger_function,
				l.duplicate_from_layer_id,
				l.duplicate_criterion,
				l.shared_from,
				l.kurzbeschreibung,
				l.dataowner_name,
				l.dataowner_email,
				l.dataowner_tel,
				l.uptodateness,
				l.updatecycle,
				g.id,
				" . $group_column . ",
				g.obergruppe,
				g.order
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`sync`' : '') . "
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`vector_tile_url`' : '') . "
				" . ($this->GUI->plugin_loaded('portal') ? ', l.`cluster_option`' : '') . "
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul JOIN
				layer AS l ON l.Layer_ID = ul.Layer_ID LEFT JOIN
				u_groups AS g ON COALESCE(ul.group_id, l.Gruppe) = g.id LEFT JOIN
				u_groups2rolle AS gr ON g.id = gr.id LEFT JOIN
				connections as c ON l.connection_id = c.id
			WHERE
				rl.stelle_id = ul.Stelle_ID AND
				rl.layer_id = ul.Layer_ID AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND
				l.Datentyp != 5 AND 
				rl.stelle_ID = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID .
				($groups != NULL ? " AND g.id IN (" . $groups . ")" : '') .
				($this->nurAufgeklappteLayer ? " AND (rl.aktivStatus != '0' OR gr.status != '0' OR ul.requires != '')" : '') .
				($this->nurAktiveLayer ? " AND (rl.aktivStatus != '0')" : '') .
				($this->OhneRequires ? " AND (ul.requires IS NULL)" : '') .
				($this->nurFremdeLayer ? " AND (c.host NOT IN ('pgsql', 'localhost') OR l.connectiontype != 6 AND rl.aktivStatus != '0')" : '') .
				($this->nurNameLike ? " AND l.Name LIKE '" . $this->nurNameLike . "'" : '') . 
				($this->nurPostgisLayer ? " AND l.connectiontype = 6" : '') . 
				($this->keinePostgisLayer ? " AND l.connectiontype != 6" : '') . 
				($this->nurLayerID ? " AND l.Layer_ID = " . $this->nurLayerID : '') .
				($this->nurLayerIDs ? " AND l.Layer_ID IN (" . $this->nurLayerIDs . ")" : '') .
				($this->nichtLayerID ? " AND l.Layer_ID != " . $this->nichtLayerID : '') . "
			ORDER BY
				drawingorder
		";
		# echo '<br>SQL zur Abfrage der Layer: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>", 4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { 
			echo err_msg($this->script_name, __LINE__, $sql); 
			return 0;
		}
		$layer = array();
		$layer['list'] = array();
		$this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
		while ($rs = $ret['result']->fetch_assoc()) {
			if ($rs['rollenfilter'] != '') {		// Rollenfilter zum Filter hinzufügen
				if ($rs['Filter'] == '') {
					$rs['Filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['Filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['Filter']);
				}
			}

			$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$useLayerAliases) ? 'Name' : 'alias'];
			$rs['id'] = $i;
			$rs['alias_link'] = replace_params_link(
				$rs['Name_or_alias'],
				rolle::$layer_params,
				$rs['Layer_ID']
			);
			foreach (array('Name', 'alias', 'Name_or_alias', 'connection', 'classification', 'classitem', 'tileindex', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params_rolle(
					$rs[$key],
					['duplicate_criterion' => $rs['duplicate_criterion']]
				);
			}
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class'] = $this->read_Classes($rs['Layer_ID'], $this->disabled_classes, false, $rs['classification']);
			}
			if ($rs['maxscale'] > 0) {
				$rs['maxscale'] = $rs['maxscale'] + 0.3;
			}
			if ($rs['minscale'] > 0) {
				$rs['minscale'] = $rs['minscale'] - 0.3;
			}
			$layer['list'][$i] = $rs;
			# Pointer auf requires-Array
			$layer['list'][$i]['required'] =& $requires_layer[$rs['Layer_ID']];
			if ($rs['requires'] != '') {
				# requires-Array füllen
				$requires_layer[$rs['requires']][] = $rs['Layer_ID'];
				if ($rs['queryable']) {
					# wenn der untergeordnete Layer queryable ist, wird queryable auch beim übergeordneten gesetzt, damit die Checkbox in der Legende erscheint
					$layer['layer_ids'][$rs['requires']]['queryable'] = $rs['queryable'];
				}
			}
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer['list'][$i]; # damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
			$i++;
		}
		return $layer;
	}

	function read_Groups($all = false, $order = '', $where = 'true') {
		global $language;
		if ($language != 'german') {
			$gruppenname_column = "
			CASE
				WHEN g.`Gruppenname_" . $language . "` != \"\" THEN g.`Gruppenname_" . $language . "`
				ELSE g.`Gruppenname`
			END";
		}
		else {
			$gruppenname_column = "g.`Gruppenname`";
		}

		$sql = "
			SELECT
				g.id,
				" . $gruppenname_column . " AS Gruppenname,
				g.obergruppe,
				g.selectable_for_shared_layers " .
				(!$all ? ", g2r.status" : "") . "
			FROM
				u_groups AS g" . ($all ? "" : "
				JOIN u_groups2rolle AS g2r ON g.id = g2r.id") . "
			WHERE
				" . $where . ($all ? "" : " AND
				g2r.stelle_ID = " . $this->Stelle_ID . " AND
				g2r.user_id = " . $this->User_ID) . "
			ORDER BY " .
				($order != '' ? replace_semicolon($order) : "g.`order`") . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>", 4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$groups = array();
		while ($rs = $this->db->result->fetch_assoc()) {
			$groups[$rs['id']]['status'] = value_of($rs, 'status');
			$groups[$rs['id']]['Gruppenname'] = $rs['Gruppenname'];
			$groups[$rs['id']]['obergruppe'] = $rs['obergruppe'];
			$groups[$rs['id']]['id'] = $rs['id'];
			$groups[$rs['id']]['selectable_for_shared_layers'] = $rs['selectable_for_shared_layers'];
			if ($rs['obergruppe']) {
				$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
			}
		}
		$this->anzGroups = count($groups);
		return $groups;
	}

  // function read_Group($id) {
    // $sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
    // $sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID.' AND g2r.id = g.id AND g.id='.$id;
    // $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Lesen einer Gruppe der Rolle:<br>" . $sql,4);
    // $ret = $this->db->execSQL($sql);
    //if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql, $this->connection); return 0; }
    // $rs = $this->db->result->fetch_array();
    // return $rs;
  // }


	function read_ClassesbyClassid($class_id) {
		global $language;

		$sql = "
			SELECT" .
				((!$all_languages AND $language != 'german') ? "
					CASE
						WHEN `Name_" . $language . "` IS NOT NULL THEN `Name_" . $language . "`
						ELSE `Name`
					END" : "`Name`"
				) . " AS Name,
				`Class_ID`,
				`Layer_ID`,
				`Expression`,
				`classification`,
				`legendgraphic`,
				`drawingorder`,
				`legendorder`,
				`text`
			FROM
				`classes`
			WHERE
				`Class_ID` = " . $class_id . "
			ORDER BY
				`classification`,
				`drawingorder`,
				`Class_ID`
		";

		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		while ($rs = $ret['result']->fetch_assoc()) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
			$Classes[] = $rs;
		}
		return $Classes;
	}

	function read_Classes($Layer_ID, $disabled_classes = NULL, $all_languages = false, $classification = '') {
		global $language;
		$Classes = array();

		$sql = "
			SELECT " .
				((!$all_languages AND $language != 'german') ? "
					CASE
						WHEN `Name_" . $language . "`IS NOT NULL THEN `Name_" . $language . "`
						ELSE `Name`
					END" : "
					`Name`"
				) . " AS Name,
				`Name_low-german`,
				`Name_english`,
				`Name_polish`,
				`Name_vietnamese`,
				`Class_ID`,
				`Layer_ID`,
				`Expression`,
				`classification`,
				`legendgraphic`,
				`legendimagewidth`,
				`legendimageheight`,
				`drawingorder`,
				`legendorder`,
				`text`
			FROM
				`classes`
			WHERE
				`Layer_ID` = " . $Layer_ID .
				(
					(!empty($classification)) ? " AND
						(
							classification IS NULL OR classification IN ('', '" . $classification . "')
						)
					" : ""
				) . "
			ORDER BY
				NULLIF(classification, '') IS NULL,
				classification,
				drawingorder,
				Class_ID
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>", 4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name . " Zeile: " . __LINE__ .'<br>'.$sql; return 0; }
		$index = 0;
		while ($rs = $ret['result']->fetch_assoc()) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
			$rs['index'] = $index;
			#Anne
			if($disabled_classes){
				if($disabled_classes['status'][$rs['Class_ID']] == 2) {
					$rs['Status'] = 1;
					for($i = 0; $i < count($rs['Style']); $i++) {
						if ($rs['Style'][$i]['color'] != '' AND $rs['Style'][$i]['color'] != '-1 -1 -1') {
							$rs['Style'][$i]['outlinecolor'] = $rs['Style'][$i]['color'];
							$rs['Style'][$i]['color'] = '-1 -1 -1';
							if($rs['Style'][$i]['width'] == '') $rs['Style'][$i]['width'] = 3;
							if($rs['Style'][$i]['minwidth'] == '') $rs['Style'][$i]['minwidth'] = 2;
							if($rs['Style'][$i]['maxwidth'] == '') $rs['Style'][$i]['maxwidth'] = 4;
							$rs['Style'][$i]['symbolname'] = '';
						}
					}
				}
				elseif ($disabled_classes['status'][$rs['Class_ID']] == '0') {
					$rs['Status'] = 0;
				}
				else $rs['Status'] = 1;
			}
			else $rs['Status'] = 1;

			$Classes[] = $rs;
			$index++;
		}
		return $Classes;
	}

  function read_disabled_classes() {
		$sql = "
			SELECT
				class_id,
				status
			FROM
				u_rolle2used_class
			WHERE
				user_id = " . $this->User_ID . "
				AND stelle_id = " . $this->Stelle_ID . "
		";
		#echo '<p>SQL zur Abfrage von diabled classes: ' . $sql;
		$this->db->execSQL($sql);
    while ($row = $this->db->result->fetch_assoc()) {
  		$classarray['class_id'][] = $row['class_id'];
			$classarray['status'][$row['class_id']] = $row['status'];
		}
		#return $classarray ?? NULL;
		return $classarray;
  }

  function read_Styles($Class_ID) {
		$Styles = array();
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>",4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    while($rs = $this->db->result->fetch_assoc()) {
      $Styles[]=$rs;
    }
    return $Styles;
  }

	# Einer Klasse können mehrere Labels zugeordnet werden
	# Abfrage der Labels nicht aus Tabelle classes sondern aus u_labels2classes
	function read_Label($Class_ID) {
		$Labels = array();
		$sql = "
			SELECT
				*
			FROM
				labels AS l,
				u_labels2classes AS l2c
			WHERE
				l.Label_ID = l2c.label_id
				AND l2c.class_id = " . $Class_ID . "
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>",4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
		while ($rs = $this->db->result->fetch_assoc()) {
			$Labels[]=$rs;
		}
		return $Labels;
	}

	function zoomToDatasets($oids, $layerset, $columnname, $border, $layerdb, $client_epsg, $stelle) {
		$query_parts = $this->getQueryParts($layerset, []);
		$sql = "
			SELECT 
				st_xmin(bbox) AS minx,
				st_ymin(bbox) AS miny,
				st_xmax(bbox) AS maxx,
				st_ymax(bbox) AS maxy
			FROM (
				SELECT 
					st_transform(ST_SetSRID(ST_Extent(" . $columnname . "), " . $layerset['epsg_code'] . "), " . $client_epsg.") as bbox
				FROM 
					(SELECT " . $query_parts['query'] . ") as fooo
				WHERE 
					" . pg_quote($layerset['maintable'] . '_oid') . " IN ('" . implode("','", $oids) . "')
			) AS foo";
		#echo $sql;
    $ret = $layerdb->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = rectObj(
    	$rs['minx'],
    	$rs['miny'],			
    	$rs['maxx'],
    	$rs['maxy']
		);
		if(defined('ZOOMBUFFER') AND ZOOMBUFFER > 0){
			if($client_epsg == 4326)$randx = $randy = ZOOMBUFFER/10000;
			else $randx = $randy = ZOOMBUFFER;
		}
		else{
			if($rect->maxx-$rect->minx < 1){		# bei einem Punktdatensatz
				$randx = $randy = 50;
				if($client_epsg == 4326)$randx = $randy = $randy/10000;
			}
			else{
				$randx=($rect->maxx-$rect->minx)*$border/100;
				$randy=($rect->maxy-$rect->miny)*$border/100;
			}
		}
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }
	
	/**
	* Diese Funktion liefert zu einem Layer die Bestandteile, die für eine Sachdatenabfrage benötigt werden.
	* Es wird ein Array mit 3 Elementen zurückgeliefert:
	* select  - die entsprechend der Rechte gekürzte Attributliste für den SELECT-Teil der Abfrage, Array- und Datentyp-Attribute werden mit to_json() abgefragt, zusätzlich erweitert um die ID-Spalte
	* query   - das SQL aus dem Query-Feld des Layers, zusätzlich erweitert um die ID-Spalte, kann dann in einer Unterabfrage im FROM-Teil eingebaut werden
	* orderby - das ORDER BY aus dem Query-Feld des Layers, falls vorhanden 
	* @param array $layerset enthält die Layerinformationen inkl. den Attributinformationen
	* @param array $privileges enthält die Rechteeinstellungen der Attribute
	* @param bool $only_filenames	wenn true, werden für Dateien, die in bytea-Feldern gespeichert sind, nur die Dateinamen abgefragt
	* @return array with string select and string query and string orderby
	*/
	function getQueryParts($layerset, $privileges, $only_filenames = true){
		$path = $layerset['pfad'];

		if (array_key_exists('attributes', $layerset)) {
			foreach ($layerset['attributes']['name'] as $i => $attributename) {
				if (value_of($privileges, $attributename) != '') {
					$type = $layerset['attributes']['type'][$i];
					switch (true) {
						case (POSTGRESVERSION >= 930 AND substr($type, 0, 1) == '_' OR is_numeric($type)) : {
							$newattributesarray[] = 'to_json(' . $attributename . ')::text as ' . $attributename;					# Array oder Datentyp
						}break;
						case ($only_filenames !== false AND $type == 'bytea') : {
							$newattributesarray[] = "split_part(encode(" . $attributename . ", 'escape'), '&original_name=', 2) as " . $attributename;								# bytea
						}break;
						default : {
							$newattributesarray[] = pg_quote($attributename);					# normal
						}
					}
				}
			}
		}

		# order by rausnehmen
		$orderbyposition = strrpos(strtolower($path), 'order by');
		$lastfromposition = strrpos(strtolower($path), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			$orderby = ' '.substr($path, $orderbyposition);
			$path = substr($path, 0, $orderbyposition);
		}

		# group by rausnehmen
		$groupbyposition = strrpos(strtolower($path), 'group by');
		$lastwhereposition = strrpos(strtolower($path), 'where');
		if($groupbyposition !== false AND $groupbyposition > $lastwhereposition){
			$layerset['attributes']['groupby'] = ' '.substr($path, $groupbyposition);
			$path = substr($path, 0, $groupbyposition);
		}

		$distinctpos = strpos(strtolower($path), 'distinct');
		if($distinctpos !== false && $distinctpos < 10){
			$pfad = substr(trim($path), $distinctpos+8);
			$distinct = true;
		}
		else{
			$pfad = substr(trim($path), 7);
		}
		if ($layerset['maintable'] != '' AND $layerset['oid'] != '') {
			$pfad = pg_quote($layerset['attributes']['table_alias_name'][$layerset['maintable']] ?: $layerset['maintable']).'.'.$layerset['oid'].' AS ' . pg_quote($layerset['maintable'] . '_oid').', ' . $pfad;
			$newattributesarray[] = pg_quote($layerset['maintable'] . '_oid');
		}
		if ($distinct == true) {
			$pfad = 'DISTINCT ' . $pfad;
		}

		$the_geom = $layerset['attributes']['the_geom'];
				
		# group by wieder einbauen
		if($layerset['attributes']['groupby'] != ''){
			$pfad .= $layerset['attributes']['groupby'];
			$j = 0;
			foreach($layerset['attributes']['all_table_names'] as $tablename){
				if($tablename == $layerset['maintable'] AND $layerset['oid'] != ''){		# hat Haupttabelle oids?
					$pfad .= ','.pg_quote($tablename.'_oid').' ';
				}
				$j++;
			}
		}
		return [
				'select' => implode(', ', $newattributesarray),
				'query' => $pfad, 
				'orderby' => $orderby
			];
	}

  function deleteFilter($stelle_id, $layer_id, $attributname){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$stelle_id.' AND Layer_ID = '.$layer_id.' AND attributname = "'.$attributname.'"';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteFilter - Löschen eines Attribut-Filters eines used_layers:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
  }

	/**
	 * Function write the filter for Stelle with $stelle_id and Layer with $layer_id it to the Database.
	 * @param string $filterstring
	 * @param integer $stelle_id Id of the stelle to set the filter for
	 * @param integer $layer_id Id of the layer to set the filter for
	 * @return boolean Return false in case of error
	 */
	function write_filter($filterstring, $stelle_id, $layer_id) {
		$filterstring = pg_escape_string($filterstring);
		$sql = "
			UPDATE
				used_layer
			SET
				`Filter` = '" . $filterstring . "'
			WHERE
				`Stelle_ID` = " . $stelle_id . " AND
				`Layer_ID` = " . $layer_id . "
		";
		// echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->write_filter - Speichern des Filterstrings:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4);
			echo $this->db->mysqli->error;
			return false;
		}
		return true;
	}

  function checkPolygon($poly_id){
    $sql = 'SELECT * FROM u_attributfilter2used_layer WHERE attributvalue = "'.$poly_id.'" AND type = "geometry"';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->checkPolygon - Testen ob Polygon_id noch in einem Filter benutzt wird:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $rs = $this->db->result->fetch_assoc();
    if($rs == NULL){
      return false;
    }
    else{
      return true;
    }
  }

  function getPolygonID($stelle_id,$layer_id) {
    $sql = 'SELECT attributvalue AS id FROM u_attributfilter2used_layer';
    $sql.= ' WHERE stelle_id = "'.$stelle_id.'" AND layer_id = "'.$layer_id.'" AND type = "geometry"';
    #echo $sql;
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $ret = $this->db->result->fetch_row();
    $poly_id = $ret[0];
    return $poly_id;
  }

	function saveAttributeFilter($formvars) {
		$sql = "
			INSERT INTO 
				u_attributfilter2used_layer 
			SET
				attributname = '" . $formvars['attributname'] . "',
				attributvalue = '" . $this->db->mysqli->real_escape_string($formvars['attributvalue']) . "',
				operator = '" . $formvars['operator']. "',
				type = '" . $formvars['type'] . "',
				Stelle_ID = " . $formvars['stelle'] . ",
				Layer_ID = " . $formvars['layer'] . "
			ON DUPLICATE KEY UPDATE  
				attributvalue = '" . $this->db->mysqli->real_escape_string($formvars['attributvalue']) . "', 
				operator = '" . $formvars['operator'] . "'";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->saveAttributeFilter - Speichern der Attribute-Filter-Parameter:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4);
			return 0;
		}
	}

	function read_attribute_filter($Stelle_ID, $Layer_ID) {
		$filter = [];
		$sql = "
			SELECT
				*
			FROM
				u_attributfilter2used_layer
			WHERE
				Stelle_ID = " . $Stelle_ID . " AND
				Layer_ID = " . $Layer_ID . "
		";
		# echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_attribute_filter - Lesen der Attribute-Filter-Parameter:<br>" . $sql, 4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__,4); return 0; }
		while($rs = $this->db->result->fetch_assoc()) {
			$filter[] = $rs;
		}
		return $filter;
	}

	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT Filter FROM used_layer WHERE Layer_ID = '.$layer_id.' AND Stelle_ID = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $rs = $this->db->result->fetch_row();
    $filter = $rs[0];
    return $filter;
  }

	function getData($layer_id){
		global $language;
		$sql = "
			SELECT
				`Data`,
				`duplicate_criterion`
			FROM
				`" . ($layer_id < 0 ? "rollenlayer" : "layer") . "`
			WHERE
				" . ($layer_id < 0 ? "-id" : "`Layer_ID`") . " = " . $layer_id . "
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getData - Lesen des Data-Statements des Layers:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			$this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4);
			return 0;
		}
		$rs = $this->db->result->fetch_assoc();
		$data = replace_params_rolle(
			$rs['Data'],
			['duplicate_criterion' => $rs['duplicate_criterion']]
		);
		return $data;
	}

  function getPath($layer_id){
    $sql = "
			SELECT
				`pfad`,
				`duplicate_criterion`
			FROM
				`layer`
			WHERE
				Layer_ID = " . $layer_id . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getPath - Lesen des Path-Statements des Layers:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $layer = $this->db->result->fetch_assoc();
		$path = replace_params_rolle(
			$layer['pfad'],
			['duplicate_criterion' => $rs['duplicate_criterion']]
		);
		return $path;
  }

	/**
	* Diese Funktion liefert den Pfad des Dokuments, welches hochgeladen werden soll (absoluter Pfad mit Dateiname ohne Dateiendung)
	* sowie die URL des Dokuments, falls eine verwendet werden soll
	* Wenn eine SQL-Anfrage in Option des Dokument-Attributes definiert ist, diese Ausführen und den Dokumentpfad anpassen
	*/
	function getDocument_Path($doc_path, $doc_url, $dynamic_path_sql, $attributenames, $attributevalues, $layerdb, $originalname) {
		if ($doc_path == '') {
			$doc_path = CUSTOM_IMAGE_PATH;
		}
		if (strtolower(substr($dynamic_path_sql, 0, 6)) == 'select') {
			// ist im Optionenfeld eine SQL-Abfrage definiert, diese ausführen und mit dem Ergebnis den Dokumentenpfad erweitern
			$sql = $dynamic_path_sql;
			for ($a = 0; $a < count($attributenames); $a++) {
				if ($attributenames[$a] != '') {
					$sql = str_replace('$' . $attributenames[$a], $attributevalues[$a], $sql);
				}
			}
			# echo '<p>SQL zur Abfrage des Dokumentenpfades: ' . $sql;
			$ret = $layerdb->execSQL($sql, 4, 1);
			$dynamic_path = pg_fetch_row($ret[1]);
			$doc_path .= $dynamic_path[0]; // der ganze Pfad mit Dateiname ohne Endung
			if ($doc_url) {
				$doc_url = $doc_url . $dynamic_path[0];
			}
			$path_parts = explode('/', $doc_path);
			array_pop($path_parts);
			$new_path = implode('/', $path_parts); // der evtl. neu anzulegende Pfad ohne Datei
			@mkdir($new_path, 0777, true);
		}
		else { // andernfalls werden keine weiteren Unterordner generiert und der Dateiname aus Zeitstempel und Zufallszahl zusammengesetzt
			if (!$doc_url) {
				$filename = date('Y-m-d_H_i_s',time()) . '-' . rand(100000, 999999);
			}
			else {
				$filename = $originalname . '-' . rand(100000, 999999);
				$doc_url .= $filename;
			}
			$doc_path .= $filename;
		}
		return array('doc_path' => $doc_path, 'doc_url' => $doc_url);
	}

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (count_or_0($rs) == 0) {
			return null;
		}
		$rs['schema'] = replace_params_rolle($rs['schema']);
		$layerdb->schema = ($rs['schema'] == '' ? 'public' : $rs['schema']);
		if (!$layerdb->open($rs['connection_id'])) {
			echo 'Die Verbindung zur PostGIS-Datenbank konnte mit connection_id: ' . $rs['connection_id'] . ' nicht hergestellt werden:';
		}
		return $layerdb;
	}

	/**
	* Function get the postgres connection_id and the schema of the layer with given layer_id
	* @params integer $layer_id, If layer_id is negativ the connection_id is from table rollen_layer
	* @return array with integer connection_id and string schema name, return an empty array if no connection for layer_id found
	*/
	function get_layer_connection($layer_id) {
		sanitize($layer_id, 'int');
		#echo 'Class db_map Method get_layer_connection';
		# $layer_id < 0 Rollenlayer else normal layer
		$sql = "
			SELECT
				`connection_id`,
				" . ($layer_id < 0 ? "'" . CUSTOM_SHAPE_SCHEMA . "' AS " : "") . "`schema`
			FROM
				" . ($layer_id < 0 ? "rollenlayer" : "layer") . "
			WHERE
				" . ($layer_id < 0 ? "-id" : "Layer_ID") . " = " . $layer_id . " AND
				`connectiontype` = 6
		";
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_layer_connection - Lesen der connection Daten des Layers:<br>" . $sql, 4);
		$this->db->execSQL($sql);
		if ($this->db->success) {
			return $this->db->result->fetch_assoc();
		}
		else {
			$this->debug->write("<br>Abbruch beim Lesen der Layer connection in get_layer_connection, Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4);
			return array();
		}
	}

  function getSelectFromData($data){
    if(strpos($data, '(') === false){
      $from = stristr($data, ' from ');
      $usingposition = strpos($from, 'using');
      if($usingposition > 0){
        $from = substr($from, 0, $usingposition);
      }
      $select = 'select * '.$from.' where 1=1';
    }
    else{
      $select = stristr($data,'(');
      $select = trim($select, '(');
      $select = substr($select, 0, strrpos($select, ')'));
    }
		return replace_params_rolle(
						$select,
						['SCALE' => '1000']
					);
	}

	/**
	 * Liefert eine Liste von Attriubten wie sie im data-Feld des Layers stehen
	 * Wenn Data leer ist werden die Attribute vom Path-Feld des Layers entnommen
	 */
	function getDataAttributes($database, $layer_id, $options = array()) {
		$default_options = array(
			'if_empty_use_query' => false,
			'assoc' => false
		);
		$options = array_merge($default_options, $options);
		$data = $this->getData($layer_id);
		if ($data != '') {
			$select = $this->getSelectFromData($data);
			if ($database->schema != '') {
				$select = str_replace($database->schema . '.', '', $select);
			}
			$ret = $database->getFieldsfromSelect($select, $options['assoc']);
			if ($ret[0]) {
				$this->GUI->add_message('error', $ret[1]);
			}
			return $ret[1];
		}
		elseif ($options['if_empty_use_query']) {
			return $this->getPathAttributes($database, $this->getPath($layer_id));
		}
		else {
			$this->GUI->add_message('warning', 'Das Data-Feld des Layers mit der Layer-ID ' . $layer_id . ' ist leer.');
			return NULL;
		}
	}

	function getPathAttributes($database, $path, $pseudo_realnames = array()) {
		$pathAttributes = array();
		if ($path != '') {
			$ret = $database->getFieldsfromSelect($path, false, $pseudo_realnames);
			if ($ret['success']) {
				$pathAttributes = $ret[1]; # Gebe die Attribute zurück
			}
			else {
				$pathAttributes = array();
				$this->GUI->add_message('waring', 'Der Fehler ist bei der Abfrage der Attribute des Query-Statements aufgetreten. Es sollte geprüft werden ob die Abfrage im Query-Statement korrekt ist.');
			}
		}
		return $pathAttributes;
	}

	/**
	 * Function query and set required attribute values pending on form element type
	 */
	// function add_attribute_values($attributes, $database, &$query_result, $withvalues, $stelle_id, $all_options = false) {
	function add_attribute_values($attributes, $database, $query_result, $withvalues, $stelle_id, $all_options = false) {
		$attributes['req_by'] = $attributes['requires'] = $attributes['enum_requires_value'] = array();
		# Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
		for ($i = 0; $i < count_or_0($attributes['name'] ?: []); $i++) {
			$type = ltrim($attributes['type'][$i], '_');
			if (is_numeric($type) AND $query_result != NULL) {			# Attribut ist ein Datentyp
				$type_attributes = $attributes['type_attributes'][$i];
				foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
					$json = str_replace('}"', '}', str_replace('"{', '{', str_replace("\\", "", $query_result[$k][$attributes['name'][$i]])));	# warum diese Zeichen dort reingekommen sind, ist noch nicht klar...
					@$datatype_query_result = json_decode($json, true);
					if (!empty($datatype_query_result) AND $type == $attributes['type'][$i]) {	# kein Array von Datentypen --> zur weiteren einheitlichen Verarbeitung num. Array draus machen
						$datatype_query_result = [$datatype_query_result];
					}
					$attributes['type_attributes'][$i][$k] = $this->add_attribute_values($type_attributes, $database, $datatype_query_result, $withvalues, $stelle_id, $only_current_enums);
				}
			}
			if (
				$attributes['options'][$i] == '' AND
				$attributes['constraints'][$i] != '' AND
				!in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))
			) {	# das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
				$explosion = explode("','", trim($attributes['constraints'][$i], "'"));
				foreach ($explosion as $option) {
					$attributes['enum'][$i][$option]['output'] = $option;
				}
			}
			if ($withvalues == true) {
				switch ($attributes['form_element_type'][$i]) {
					# Auswahlfelder
					case 'Auswahlfeld' : case 'Auswahlfeld_Bild' : {
						if ($attributes['options'][$i] != '') {
							# das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
							if (strpos($attributes['options'][$i], "'") === 0) {
								# Aufzählung wie 'wert1','wert2','wert3'
								$explosion = explode("','", substr(str_replace(["', ", chr(10), chr(13)], ["',", '', ''], $attributes['options'][$i]), 1, -1));
								foreach ($explosion as $option) {
									$attributes['enum'][$i][$option]['output'] = $option;
								}
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {
								# SQL-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen
								# --------- weitere Optionen -----------
								if (value_of($optionen, 1) != '') {
									# die weiteren Optionen exploden (opt1 opt2 opt3)
									$further_options = explode(' ', $optionen[1]);
									for ($k = 0; $k < count($further_options); $k++) {
										if (strpos($further_options[$k], 'layer_id') !== false) {
											#layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
											$attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
											$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
											$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
										}
										elseif (trim($further_options[$k]) == 'embedded') {
											# Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										}
									}
								}
								# --------- weitere Optionen -----------
								if ($attributes['subform_layer_id'][$i] != '' AND $layer['oid'] != '') {
									 # auch die oid abfragen
									 $attributes['options'][$i] = str_replace(' from ', ', ' . $layer['oid'] . ' as oid from ', strtolower($optionen[0]));
								}
								# ------------ SQL ---------------------
								else {
									$attributes['options'][$i] = $optionen[0];
								}
								# ------<required by>------
								$req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
								if ($req_by_start > 0) {
									$req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
									$req_by = trim(substr($attributes['options'][$i], $req_by_start + 13, $req_by_end - $req_by_start - 13));
									$attributes['req_by'][$i] = $req_by; # das abhängige Attribut
									$attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start); # required-Tag aus SQL entfernen
								}
								# ------<required by>------
								# -----<requires>------
								if (strpos(strtolower($attributes['options'][$i]), "<requires>") > 0) {
									if ($all_options) {
										# alle Auswahlmöglichkeiten -> where abschneiden
										foreach ($attributes['name'] as $attributename) {
											if (strpos($attributes['options'][$i], '<requires>' . $attributename . '</requires>') !== false) {
												$attributes['req'][$i][] = $attributename; # die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
											}
										}
										$attributes['options'][$i] = substr($attributes['options'][$i], 0, stripos($attributes['options'][$i], 'where')) . substr($attributes['options'][$i], stripos($attributes['options'][$i], 'order by'));;
									}
									else {
										if ($query_result != NULL) {
											$attributes['options'][$i] = str_replace('=<requires>', '= <requires>', $attributes['options'][$i]);
											foreach ($attributes['name'] as $attributename) {
												if (strpos($attributes['options'][$i], '<requires>' . $attributename . '</requires>') !== false) {
													$attributes['req'][$i][] = $attributename; # die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
												}
											}
											foreach ($query_result as $k => $record) { # bei Erfassung eines neuen DS hat $k den Wert -1
												$options = $attributes['options'][$i];
												foreach ($attributes['req'][$i] as $attributename) {
													#	Ein <requires>-Tag in Hochkommas kann immer durch den Attributwert in Hochkommas ersetzt werden. 
													# Das ist z.B. dafür notwendig , wenn sich auch bei leerem Attributwert Auswahlmöglichkeiten ergeben sollen.
													$options = str_replace("'<requires>" . $attributename . "</requires>'", "'" . $query_result[$k][$attributename] . "'", $options);
													if ($query_result[$k][$attributename] != '') {
														# Wenn der <requires>-Tag nicht in Hochkommas steht, soll nur ersetzt werden, wenn der Attributwert nicht leer ist.
														if (is_array($query_result[$k][$attributename])) {
															$query_result[$k][$attributename] = implode("','", $query_result[$k][$attributename]);
														}
														$options = str_replace('= <requires>' . $attributename.'</requires>',	" IN ('" . $query_result[$k][$attributename] . "')", $options);
														$options = str_replace('<requires>'.$attributename.'</requires>', "'".$query_result[$k][$attributename]."'", $options);	# fallback
													}
												}
												if (strpos($options, '<requires>') !== false) {
													#$options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
													$attribute_value = $query_result[$k][$attributes['name'][$i]];
													if ($attribute_value != '') {
														$options = "select '" . $attribute_value . "' as value, '" . $attribute_value . "' as output";
													}
													else {
														$options = '';
														# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden) aber das eigentliche Attribut einen Wert hat, wird dieser Wert als value und output genommen, ansonsten sind die Optionen leer
													}
												}
												$attributes['dependent_options'][$i][$k] = $options;
											}
										}
										else {
											$attributes['options'][$i] = '';			# wenn kein Query-Result übergeben wurde, sind die Optionen leer
										}
									}
								}
								# -----<requires>------
								if (
									value_of($attributes, 'dependent_options') AND
									is_array($attributes['dependent_options'][$i])
								) {
									# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									foreach ($query_result as $k => $record) {
										# bei Erfassung eines neuen DS hat $k den Wert -1
										$sql = $attributes['dependent_options'][$i][$k];
										if ($sql != '') {
											#echo '<br>SQL zur Abfrage der Optionen: ' . $sql;
											$ret = $database->execSQL($sql, 4, 0);
											if ($ret[0]) {
												$this->GUI->add_message('error', 'Fehler bei der Abfrage der Optionen für das Attribut "' . $attributes['name'][$i] . '"<br>' . err_msg($this->script_name, __LINE__, $ret[1]));
												return 0;
											}
											$attributes['enum'][$i][$k] = array();
											while ($rs = pg_fetch_array($ret[1])) {
												$attributes['enum'][$i][$k][$rs['value']] = [
													'value' 	=> $rs['value'],
													'output' 	=> $rs['output'],
													'oid'			=> $rs['oid'],
													'image'		=> value_of($rs, 'image')
												];
											}
											$query_result[$k]['enum_' .  $attributes['name'][$i]] = $enum;
										}
									}
								}
								elseif ($attributes['options'][$i] != '') {
									$sql = $attributes['options'][$i];
									#echo '<br>SQL zur Abfrage der Optionen: ' . $sql;
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
									while ($rs = pg_fetch_array($ret[1])) {
										$attributes['enum'][$i][$rs['value']] = [
											'value' 	=> $rs['value'],
											'output' 	=> $rs['output'],
											'oid'			=> $rs['oid'],
											'image'		=> value_of($rs, 'image')
										];
										if ($rs['requires']) {
											$attributes['enum'][$i][$rs['value']]['requires_value'] = $rs['requires'];
										}
									}
								}
							}
						}
					} break;

					case 'Autovervollständigungsfeld' : case 'Autovervollständigungsfeld_zweispaltig' : {
						if ($attributes['options'][$i] != '') {
							if (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								$optionen = explode(';', $attributes['options'][$i]);	# SQL; weitere Optionen
								$attributes['options'][$i] = $optionen[0];

								# ------<required by>------
								$req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
								if ($req_by_start > 0) {
									$req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
									$req_by = trim(substr($attributes['options'][$i], $req_by_start + 13, $req_by_end - $req_by_start - 13));
									$attributes['req_by'][$i] = $req_by; # das abhängige Attribut
									$attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start); # required-Tag aus SQL entfernen
								}
								# ------<required by>------
								# -----<requires>------
								if (strpos(strtolower($attributes['options'][$i]), "<requires>") > 0) {
									if ($all_options) {
										# alle Auswahlmöglichkeiten -> where abschneiden
										foreach ($attributes['name'] as $attributename) {
											if (strpos($attributes['options'][$i], '<requires>' . $attributename . '</requires>') !== false) {
												$attributes['req'][$i][] = $attributename; # die Attribute, die in <requires>-Tags verwendet werden zusammen sammeln
											}
										}
										$attributes['options'][$i] = substr($attributes['options'][$i], 0, stripos($attributes['options'][$i], 'where'));
										$sql = $attributes['options'][$i];
										#echo '<br>SQL zur Abfrage der Optionen: ' . $sql;
										$ret = $database->execSQL($sql, 4, 0);
										if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
										while ($rs = pg_fetch_array($ret[1])) {
											$attributes['enum'][$i][$rs['value']] = [
												'value' 	=> $rs['value'],
												'output' 	=> $rs['output'],
												'oid'			=> $rs['oid']
											];
											if ($rs['requires']) {
												$attributes['enum'][$i][$rs['value']]['requires_value'] = $rs['requires'];
											}
										}
									}
								}
								# -----<requires>------

								if ($query_result != NULL) {
									foreach ($query_result as $k => $record) {	# bei Erfassung eines neuen DS hat $k den Wert -1
										$options_sql = $attributes['options'][$i];
										$value = $query_result[$k][$attributes['name'][$i]];
										if ($value != '' AND !in_array($attributes['operator'][$i], array('LIKE', 'NOT LIKE', 'IN'))) {			# falls eine LIKE-Suche oder eine IN-Suche durchgeführt wurde
											$values = json_decode($value);
											if (is_array($values)) {		# Array-Typ
												foreach ($values as $value) {
													$sql = 'SELECT * FROM ('.$options_sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
													$ret = $database->execSQL($sql, 4, 0);
													if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
													$rs = pg_fetch_array($ret[1]);
													$attributes['enum_output'][$i][$k][] = $rs['output'];
												}
											}
											else {
												$sql = 'SELECT * FROM ('.$options_sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
												$ret = $database->execSQL($sql, 4, 0);
												if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
												$rs = pg_fetch_array($ret[1]);
												$attributes['enum_output'][$i][$k] = $rs['output'];
											}
										}
									}
								}
								# weitere Optionen
								if ($optionen[1] != '') {
									$further_options = explode(' ', $optionen[1]);			# die weiteren Optionen exploden (opt1 opt2 opt3)
									for($k = 0; $k < count($further_options); $k++) {
										if (strpos($further_options[$k], 'layer_id') !== false) {		 #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
											$attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
											$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
											$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
										}
										elseif ($further_options[$k] == 'embedded') {			 # Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										}
										elseif ($further_options[$k] == 'anywhere') {			 # der eingegebene Text kann überall in den Auswahlmöglichkeiten vorkommen
											$attributes['anywhere'][$i] = true;
										}
									}
								}
							}
						}
					} break;

					case 'Radiobutton' : {
						if ($attributes['options'][$i] != '') {		 # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
							$optionen = explode(';', $attributes['options'][$i]);	# Optionen; weitere Optionen
							$attributes['options'][$i] = $optionen[0];
							if (strpos($attributes['options'][$i], "'") === 0) {			# Aufzählung wie 'wert1','wert2','wert3'
								$explosion = explode(',', str_replace("'", "", $attributes['options'][$i]));
								foreach ($explosion as $option) {
									$attributes['enum'][$i][$option]['output'] = $option;
								}
							}
							elseif (strpos(strtolower($attributes['options'][$i]), "select") === 0) {		 # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
								if ($attributes['options'][$i] != '') {
									$sql = $attributes['options'][$i];
									$ret = $database->execSQL($sql, 4, 0);
									if ($ret[0]) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
									while($rs = pg_fetch_array($ret[1])) {
										$attributes['enum'][$i][$rs['value']]['output'] = $rs['output'];
									}
								}
							}
							# weitere Optionen
							if ($optionen[1] != '') {
								$further_options = explode(' ', $optionen[1]);			# die weiteren Optionen exploden (opt1 opt2 opt3)
								for($k = 0; $k < count($further_options); $k++) {
									if ($further_options[$k] == 'embedded') {			 # Subformular soll embedded angezeigt werden
										$attributes['embedded'][$i] = true;
									}
									elseif (strpos($further_options[$k], 'horizontal') !== false) {			 # Radiobuttons nebeneinander anzeigen
										$explosion = explode('=', $further_options[$k]);
										if($explosion[1] != ''){
											$attributes['horizontal'][$i] = $explosion[1];
										}
										else{
											$attributes['horizontal'][$i] = true;
										}
									}
								}
							}
						}
					} break;

					# SubFormulare mit Primärschlüssel(n)
					case 'SubFormPK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,pkey3...; weitere optionen
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for($k = 1; $k < count($subform); $k++) {
								$attributes['subform_pkeys'][$i][] = $subform[$k];
							}
							if ($options[1] != '') {
								if ($options[1] == 'no_new_window') {
									$attributes['no_new_window'][$i] = true;
								}
							}
						}
					} break;

					# SubFormulare mit Fremdschlüssel
					case 'SubFormFK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,fkey1,fkey2,fkey3...; weitere optionen
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for ($k = 1; $k < count($subform); $k++) {
								$attributes['subform_fkeys'][$i][] = $subform[$k];
								$attributes['SubFormFK_hidden'][$attributes['indizes'][$subform[$k]]] = 1;
							}
							if ($options[1] != '') {
								if ($options[1] == 'no_new_window') {
									$attributes['no_new_window'][$i] = true;
								}
							}
						}
					} break;

					# eingebettete SubFormulare mit Primärschlüssel(n)
					case 'SubFormEmbeddedPK' : {
						if ($attributes['options'][$i] != '') {
							$options = explode(';', $attributes['options'][$i]);	# layer_id,pkey1,pkey2,preview_attribute; weitere Optionen
							$subform = explode(',', $options[0]);
							$attributes['subform_layer_id'][$i] = $subform[0];
							$layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
							$attributes['subform_layer_privileg'][$i] = $layer['privileg'];
							for($k = 1; $k < count($subform)-1; $k++) {
								$attributes['subform_pkeys'][$i][] = $subform[$k];
							}
							$attributes['preview_attribute'][$i] = $subform[$k];
							if ($options[1] != '') {
								$further_options = explode(' ', $options[1]);		 # die weiteren Optionen exploden (opt1 opt2 opt3)
								for($k = 0; $k < count($further_options); $k++) {
									switch ($further_options[$k]) {
										case 'no_new_window': {
											$attributes['no_new_window'][$i] = true;
										} break;
										case 'embedded': {														# Subformular soll embedded angezeigt werden
											$attributes['embedded'][$i] = true;
										} break;
										case 'list_edit': {														# nur Listen-Editier-Modus
											$attributes['list_edit'][$i] = true;
										} break;
										case 'reload': {														# die komplette Sachdatenanzeige soll neu geladen werden
											$attributes['reload'][$i] = true;
										} break;
										case 'no_subform_reload': {														# die SubForm-Liste soll nicht neu geladen werden
											$attributes['no_subform_reload'][$i] = true;
										} break;										
										case 'show_count': {														# die Anzahl der Subform-Datensätze anzeigen
											$attributes['show_count'][$i] = true;
										} break;										
									}
								}
							}
						}
					} break;
				}
			}
		}
		return $attributes;
	}

	function load_attributes($database, $path, $pseudo_realnames = array()) {
		# Attributname und Typ aus Pfad-Statement auslesen:
		$attributes = $this->getPathAttributes($database, $path, $pseudo_realnames);
		return $attributes;
	}

	function save_attributes($pgdatabase, $layer_id, $attributes){
		$insert_count = 0;
		for ($i = 0; $i < count_or_0($attributes); $i++) {
			if($attributes[$i] == NULL)continue;
			if($attributes[$i]['nullable'] == '')$attributes[$i]['nullable'] = 'NULL';
			if($attributes[$i]['saveable'] == '')$attributes[$i]['saveable'] = 0;
			if($attributes[$i]['length'] == '')$attributes[$i]['length'] = 'NULL';
			if($attributes[$i]['decimal_length'] == '')$attributes[$i]['decimal_length'] = 'NULL';
			if (intval($attributes[$i]['order']) <= $attributes[$i-1]['order']) {
				$attributes[$i]['order'] = $attributes[$i-1]['order'] + 1;
			}
			if ($attributes[$i]['type_type'] == 'c'){		# custom datatype
				$datatype_id = $pgdatabase->writeCustomType($layer_id, ltrim($attributes[$i]['type'], '_'), $attributes[$i]['type_schema']);
				$attributes[$i]['type'] = ($attributes[$i]['is_array'] == 't'? '_' : '') . $datatype_id; 
			}
			$sql = "
				INSERT INTO
					`layer_attributes`
				SET
					layer_id = " . $layer_id.",
					name = '" . $attributes[$i]['name'] . "',
					real_name = '" . $attributes[$i]['real_name'] . "',
					tablename = '" . $attributes[$i]['table_name'] ."',
					table_alias_name = '" . $attributes[$i]['table_alias_name'] . "',
					`schema` = '" . $attributes[$i]['schema_name'] . "',
					type = '" . $attributes[$i]['type'] . "',
					geometrytype = '" . $attributes[$i]['geomtype'] . "',
					constraints = '".$this->db->mysqli->real_escape_string($attributes[$i]['constraints']) . "',
					saveable = " . $attributes[$i]['saveable'] . ",
					nullable = " . $attributes[$i]['nullable'] . ",
					length = " . $attributes[$i]['length'] . ",
					decimal_length = " . $attributes[$i]['decimal_length'] . ",
					`default` = '".$this->db->mysqli->real_escape_string($attributes[$i]['default']) . "',
					`order` = " . $attributes[$i]['order'] . "
				ON DUPLICATE KEY UPDATE
					real_name = '" . $attributes[$i]['real_name'] . "',
					tablename = '" . $attributes[$i]['table_name'] . "',
					table_alias_name = '" . $attributes[$i]['table_alias_name'] . "',
					`schema` = '" . $attributes[$i]['schema_name'] . "',
					type = '" . $attributes[$i]['type'] . "',
					geometrytype = '" . $attributes[$i]['geomtype'] . "',
					constraints = '".$this->db->mysqli->real_escape_string($attributes[$i]['constraints']) . "',
					saveable = " . $attributes[$i]['saveable'] . ",
					nullable = " . $attributes[$i]['nullable'] . ",
					length = " . $attributes[$i]['length'] . ",
					decimal_length = " . $attributes[$i]['decimal_length'] . ",
					`default` = '" . $this->db->mysqli->real_escape_string($attributes[$i]['default']) . "',
					`order` = " . $attributes[$i]['order'] . "
			";
			#echo '<br>Sql: ' . $sql;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
			$ret = $this->db->execSQL($sql);
			if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		}
	}

	function save_postgis_attributes($pgdatabase, $layer_id, $attributes, $maintable, $schema){
		$this->save_attributes($pgdatabase, $layer_id, $attributes);

		if($maintable == ''){
			$maintable = $attributes[0]['table_name'];
			$sql = "UPDATE layer SET maintable = '" . $maintable."' WHERE (maintable IS NULL OR maintable = '') AND Layer_ID = " . $layer_id;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
			$ret = $this->db->execSQL($sql);
			if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		}

		$sql = "
			select 1 from information_schema.views WHERE table_name = '" . $maintable."' AND table_schema = '" . $schema."'
			UNION
			select 1 from information_schema.foreign_tables WHERE foreign_table_name = '" . $maintable."' AND foreign_table_schema ='" . $schema."'";
		$query = pg_query($sql);
		$is_view = pg_num_rows($query);
		$sql = "UPDATE layer SET maintable_is_view = " . $is_view." WHERE Layer_ID = " . $layer_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }

		# den PRIMARY KEY constraint rausnehmen, falls der tablename nicht der maintable entspricht
		$sql = "
			UPDATE
				`layer_attributes`,
				`layer`
			SET
				`constraints` = ''
			WHERE
				`layer_attributes`.
				`layer_id` = " . $layer_id . " AND
				`layer`.`Layer_ID` = " . $layer_id . " AND
				`constraints` = 'PRIMARY KEY' AND
				`tablename` != maintable
		";
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
	}

  function delete_old_attributes($layer_id, $attributes){
  	$sql = "DELETE FROM layer_attributes WHERE layer_id = " . $layer_id;
  	if($attributes){
  		$sql.= " AND name NOT IN (";
	  	for($i = 0; $i < count($attributes); $i++){
	  		$sql .= "'" . $attributes[$i]['name']."',";
	  	}
	  	$sql = substr($sql, 0, -1);
	  	$sql .=")";
  	}
  	#echo $sql.'<br><br>';
  	$this->debug->write("<p>file:kvwmap class:db_mapObj->delete_old_attributes - Löschen von alten Layerattributen:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
  }

	function create_layer_dumpfile($database, $layer_ids, $with_privileges = false, $with_datatypes = false) {
		$success = true;
		$dump_text .= "-- Layerdump aus kvwmap vom " . date("d.m.Y H:i:s");
		$dump_text .= "\n-- Achtung: Die Datenbank in die der Dump eingespielt wird, sollte die gleiche Migrationsversion haben,";
		$dump_text .= "\n-- wie die Datenbank aus der exportiert wurde! Anderenfalls kann es zu Fehlern bei der Ausführung des SQL kommen.";
		$dump_text .= "\n\nSET @group_id = 1;";
		$dump_text .= "\nSET @connection = '';";
		$dump_text .= "\nSET @connection_id = '1';";

		if ($with_privileges) {
			# Frage Stellen der Layer ab
			$sql = "
				SELECT DISTINCT
					ID,
					Bezeichnung
				FROM
					`stelle` AS s JOIN
					`used_layer` AS ul ON (s.`ID` = ul.`Stelle_ID`)
				WHERE
					ul.`Layer_ID` IN (" . implode(', ', $layer_ids) . ")
			";
			#echo '<br>Sql: ' . $sql;
			$ret = $database->execSQL($sql, 4, 0);
			if ($ret[0]) {
				$success = false;
				$err_msg = $ret[1];
			}
			else {
				$result = $database->result;
				while ($rs = $result->fetch_assoc()) {
					$stelle_id_var = '@stelle_id_' . $rs['ID'];
					$stellen[] = array(
						'id' => $rs['ID'],
						'var' => $stelle_id_var
					);

					$stelle = $database->create_insert_dump(
						'stelle',
						'ID',
						"
							SELECT
								*
							FROM
								`stelle`
							WHERE
								`ID` = " . $rs['ID'] . "
						"
					);
					# Stelle
					$dump_text .= "\n\n-- Stelle " . $rs['Bezeichnung'] . " (id=" . $rs['ID'] . ")";
					$dump_text .= "\n" . $stelle['insert'][0];

					# Variable für Stelle
					$dump_text .= "\n-- Falls Stelle schon existiert, INSERT mit /* */ auskommentieren und statt LAST_INSERT_ID() die vorhandene Stellen-ID eintragen.";
					$dump_text .= "\nSET " . $stelle_id_var . " = LAST_INSERT_ID();";
				}
			}
		}

		for ($i = 0; $i < count($layer_ids); $i++) {
			$layer = $database->create_insert_dump(
				'layer',
				'',
				'SELECT `Name`, `Name_low-german`, `Name_english`, `Name_polish`, `Name_vietnamese`, `alias`, `Datentyp`, \'@group_id\' AS `Gruppe`, `pfad`, `maintable`, `oid`, `identifier_text`, `maintable_is_view`, `Data`, `schema`, `geom_column`, `document_path`, `document_url`, `ddl_attribute`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `postlabelcache`, `connection`, `connection_id`, `printconnection`, `connectiontype`, `classitem`, `styleitem`, `classification`, `cluster_maxdistance`, `tolerance`, `toleranceunits`, `sizeunits`, `epsg_code`, `template`, `max_query_rows`, `queryable`, `use_geom`, `transparency`, `drawingorder`, `legendorder`, `minscale`, `maxscale`, `symbolscale`, `offsite`, `requires`, `ows_srs`, `wms_name`, `wms_keywordlist`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `write_mapserver_templates`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datasource`, `dataowner_name`, `dataowner_email`, `dataowner_tel`, `uptodateness`, `updatecycle`, `metalink`, `terms_of_use_link`, `icon`, `privileg`, `export_privileg`, `status`, `trigger_function`, `sync`, `editable`, `listed`, `duplicate_from_layer_id`, `duplicate_criterion`, `shared_from`, `version`, `comment`, `vector_tile_url`, `cluster_option`
				' . ($this->GUI->plugin_loaded('mobile') ? ', `sync`' : '') . '
				' . ($this->GUI->plugin_loaded('mobile') ? ', `vector_tile_url`' : '') . '
				' . ($this->GUI->plugin_loaded('portal') ? ', `cluster_option`' : '') . '
				FROM layer WHERE Layer_ID=' . $layer_ids[$i]
			);
			$dump_text .= "\n\n-- Layer " . $layer_ids[$i] . "\n" . $layer['insert'][0];
			$last_layer_id = '@last_layer_id'.$layer_ids[$i];
			$dump_text .= "\nSET " . $last_layer_id . "=LAST_INSERT_ID();";

			if ($with_privileges) {
				for ($s = 0; $s < count($stellen); $s++) {
					# Zuordnung des Layers zur Stelle
					$used_layer = $database->create_insert_dump(
						'used_layer',
						'',
						"
							SELECT
								'" . $last_layer_id . "' AS Layer_ID,
								'" . $stellen[$s]['var'] . "' AS Stelle_ID,
								`queryable`,
								`drawingorder`,
								`legendorder`, `minscale`, `maxscale`, `offsite`, `transparency`, `postlabelcache`, `Filter`,
								`template`, `header`, `footer`, `symbolscale`, `requires`, `logconsume`, `privileg`, `export_privileg`,
								`start_aktiv`,
								`use_geom`
							FROM
								`used_layer`
							WHERE
								`Layer_ID` = " . $layer_ids[$i] . " AND
								`Stelle_ID` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($used_layer['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung Layer " . $layer_ids[$i] . " zu Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $used_layer['insert']);
					}

					# Attributfilter des Layers in der Stelle
					$attributfilter2used_layer = $database->create_insert_dump(
						'u_attributfilter2used_layer',
						'',
						"
							SELECT
								'" . $stellen[$s]['var'] . "' AS Stelle_ID,
								'" . $last_layer_id . "' AS Layer_ID,
								`attributname`,
								`attributvalue`,
								`operator`,
								`type`
							FROM
								`u_attributfilter2used_layer`
							WHERE
								`Layer_ID` = " . $layer_ids[$i] . " AND
								`Stelle_ID` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($attributfilter2used_layer['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung der Attributfilter des Layers " . $layer_ids[$i] . " zur Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $attributfilter2used_layer['insert']);
					}
				}
			}

			$layer_attributes = $database->create_insert_dump(
				'layer_attributes', 
				'layer_attribut_id', 
				'SELECT 
					`name` AS layer_attribut_id, 
					\''.$last_layer_id.'\' AS `layer_id`, 
					`name`, 
					`real_name`, 
					`tablename`, 
					`table_alias_name`, 
					`type`, 
					`geometrytype`, 
					`constraints`, 
					`saveable`, 
					`nullable`, 
					`length`, 
					`decimal_length`, 
					`default`, 
					`form_element_type`, 
					`options`, 
					`alias`, 
					`alias_low-german`, 
					`alias_english`, 
					`alias_polish`, 
					`alias_vietnamese`, 
					`tooltip`, 
					`group`, 
					`tab`, 
					`arrangement`, 
					`labeling`, 
					`raster_visibility`, 
					`dont_use_for_new`, 
					`mandatory`, 
					`quicksearch`, 
					`visible`, 
					`vcheck_attribute`, 
					`vcheck_operator`, 
					`vcheck_value`, 
					`order`, 
					`privileg`, 
					`query_tooltip` 
				FROM 
					layer_attributes 
				WHERE 
					layer_id = ' . $layer_ids[$i]
			);
			for($j = 0; $j < count($layer_attributes['insert']); $j++){
				# Attribut des Layers
				$dump_text .= "\n\n-- Attribut " . $layer_attributes['extra'][$j] . " des Layers " . $layer_ids[$i] . "\n" . $layer_attributes['insert'][$j];
			}

			if ($with_privileges) {
				for ($s = 0; $s < count($stellen); $s++) {
					# Attributrechte in der Stelle
					$layer_attributes2stelle = $database->create_insert_dump(
						'layer_attributes2stelle',
						'',
						"
							SELECT
								'". $last_layer_id . "' AS layer_id,
								'" . $stellen[$s]['var'] . "' AS stelle_id,
								`attributename`,
								`privileg`,
								`tooltip`
							FROM
								`layer_attributes2stelle`
							WHERE
								`layer_id` = " . $layer_ids[$i] . " AND
								`stelle_id` = " . $stellen[$s]['id'] . "
						"
					);
					if (count($layer_attributes2stelle['insert']) > 0) {
						$dump_text .= "\n\n-- Zuordnung der Layerattribute des Layers " . $layer_ids[$i] . " zur Stelle " . $stellen[$s]['id'] . "\n" . implode("\n", $layer_attributes2stelle['insert']);
					}
				}
			}

			$classes = $database->create_insert_dump('classes', 'Class_ID', 'SELECT `Class_ID`, `Name`, \''.$last_layer_id.'\' AS `Layer_ID`, `Expression`, `drawingorder`, `text` FROM classes WHERE Layer_ID=' . $layer_ids[$i]);
			for ($j = 0; $j < count_or_0($classes['insert']); $j++) {
				$dump_text .= "\n\n-- Class " . $classes['extra'][$j] . " des Layers " . $layer_ids[$i] . "\n" . $classes['insert'][$j];
				$dump_text .= "\nSET @last_class_id=LAST_INSERT_ID();";

				$styles = $database->create_insert_dump('styles', 'Style_ID', 'SELECT styles.Style_ID, `symbol`,`symbolname`,`size`,`color`,`outlinecolor`, `colorrange`, `datarange`, `rangeitem`, `opacity`, `minsize`,`maxsize`, `minscale`, `maxscale`, `angle`,`angleitem`,`width`,`minwidth`,`maxwidth`, `offsetx`, `offsety`, `polaroffset`, `pattern`, `geomtransform`, `gap`, `initialgap`, `linecap`, `linejoin`, `linejoinmaxsize` FROM styles, u_styles2classes WHERE u_styles2classes.style_id = styles.Style_ID AND Class_ID='.$classes['extra'][$j].' ORDER BY drawingorder');
				for ($k = 0; $k < count_or_0($styles['insert']); $k++) {
					$dump_text .= "\n\n-- Style " . $styles['extra'][$k] . " der Class " . $classes['extra'][$j];
					$dump_text .= "\n" . $styles['insert'][$k] . "\nSET @last_style_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Style " . $styles['extra'][$k] . " zu Class " . $classes['extra'][$j];
					$dump_text .= "\nINSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, " . $k . ");";
				}

				$labels = $database->create_insert_dump('labels', 'Label_ID', 'SELECT labels.Label_ID, `font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`anglemode`,`buffer`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force` FROM labels, u_labels2classes WHERE u_labels2classes.label_id = labels.Label_ID AND Class_ID='.$classes['extra'][$j]);
				for ($k = 0; $k < count_or_0($labels['insert']); $k++) {
					$dump_text .= "\n\n-- Label " . $labels['extra'][$k] . " der Class " . $classes['extra'][$j];
					$dump_text .= "\n" . $labels['insert'][$k] . "\nSET @last_label_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Label " . $labels['extra'][$k] . " zu Class " . $classes['extra'][$j];
					$dump_text .=	"\nINSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);";
				}
			}

			$ddls = $database->create_insert_dump('datendrucklayouts', 'id', 'SELECT `id`, `name`, \''.$last_layer_id.'\' AS `layer_id`, `format`, `bgsrc`, `bgposx`, `bgposy`, `bgwidth`, `bgheight`, `dateposx`, `dateposy`, `datesize`, `userposx`, `userposy`, `usersize`, `font_date`, `font_user`, `type`, `margin_top`, `margin_bottom`, `margin_left`, `margin_right`, `gap`, `no_record_splitting`, `columns`, `filename`, `use_previews` FROM datendrucklayouts WHERE layer_id = ' . $layer_ids[$i]);
			for ($j = 0; $j < count_or_0($ddls['insert']); $j++) {
				$dump_text .= "\n\n-- Datendrucklayout " . $ddls['extra'][$j] . " des Layers " . $layer_ids[$i] . "\n" . $ddls['insert'][$j];
				$dump_text .= "\nSET @last_ddl_id=LAST_INSERT_ID();\n";

				$ddl_elemente = $database->create_insert_dump('ddl_elemente', '', 'SELECT \'@last_ddl_id\' AS ddl_id, `name`, `xpos`, `ypos`, `offset_attribute`, `width`, `border`, `font`, `fontsize` FROM `ddl_elemente` WHERE ddl_id = ' . $ddls['extra'][$j]);
				for ($k = 0; $k < count_or_0($ddl_elemente['insert']); $k++) {
					$dump_text .= "\n" . $ddl_elemente['insert'][$k];
				}

				$druckfreitexte = $database->create_insert_dump('druckfreitexte', 'id', 'SELECT `id`, `text`, `posx`, `posy`, `offset_attribute`, `size`, `width`, `border`, `font`, `angle`, `type` FROM `druckfreitexte`, `ddl2freitexte` WHERE freitext_id = id AND ddl_id = '.$ddls['extra'][$j]);
				for ($k = 0; $k < count_or_0($druckfreitexte['insert']); $k++) {
					$dump_text .= "\n" . $druckfreitexte['insert'][$k] . "\nSET @last_druckfreitexte_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Druckfreitext " . $druckfreitexte['extra'][$k] . " zu DDL " . $ddls['extra'][$j];
					$dump_text .= "\nINSERT INTO ddl2freitexte (ddl_id, freitext_id) VALUES (@last_ddl_id, @last_druckfreitexte_id);";
				}

				$druckfreilinien = $database->create_insert_dump('druckfreilinien', 'id', 'SELECT `id`, `posx`, `posy`, `endposx`, `endposy`, `breite`, `offset_attribute_start`, `offset_attribute_end`, `type` FROM `druckfreilinien`, `ddl2freilinien` WHERE line_id = id AND ddl_id = '.$ddls['extra'][$j]);
				for ($k = 0; $k < count_or_0($druckfreilinien['insert']); $k++) {
					$dump_text .= "\n" . $druckfreilinien['insert'][$k] . "\nSET @last_druckfreilinien_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Druckfreilinie " . $druckfreilinien['extra'][$k] . " zu DDL " . $ddls['extra'][$j];
					$dump_text .= "\nINSERT INTO ddl2freilinien (ddl_id, line_id) VALUES (@last_ddl_id, @last_druckfreilinien_id);";
				}

				$druckfreirechtecke = $database->create_insert_dump('druckfreirechtecke', 'id', 'SELECT `id`, `posx`, `posy`, `endposx`, `endposy`, `breite`, `color`, `offset_attribute_start`, `offset_attribute_end`, `type` FROM `druckfreirechtecke`, `ddl2freirechtecke` WHERE rect_id = id AND ddl_id = '.$ddls['extra'][$j]);
				for ($k = 0; $k < count_or_0($druckfreirechtecke['insert']); $k++) {
					$dump_text .= "\n" . $druckfreirechtecke['insert'][$k] . "\nSET @last_druckfreirechtecke_id=LAST_INSERT_ID();";
					$dump_text .= "\n-- Zuordnung Druckfreirechteck " . $druckfreirechtecke['extra'][$k] . " zu DDL " . $ddls['extra'][$j];
					$dump_text .= "\nINSERT INTO ddl2freirechtecke (ddl_id, rect_id) VALUES (@last_ddl_id, @last_druckfreirechtecke_id);";
				}
			}
		}
		for ($i = 0; $i < count($layer_ids); $i++) {
			$dump_text .= "\n\n-- Replace attribute options for Layer " . $layer_ids[$i];
			$dump_text .= "\nUPDATE layer_attributes SET options = REPLACE(options, 'layer_id=" . $layer_ids[$i]."', CONCAT('layer_id=', @last_layer_id" . $layer_ids[$i].")) WHERE layer_id IN (@last_layer_id" . implode(', @last_layer_id', $layer_ids) . ") AND form_element_type IN ('Autovervollständigungsfeld', 'Auswahlfeld', 'Link','dynamicLink') AND options regexp 'layer_id=" . $layer_ids[$i] . "( |$)';";
			$dump_text .= "\nUPDATE layer_attributes SET options = REPLACE(options, '" . $layer_ids[$i].",', CONCAT(@last_layer_id" . $layer_ids[$i].", ',')) WHERE layer_id IN (@last_layer_id" . implode(', @last_layer_id', $layer_ids) . ") AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK') AND options LIKE '" . $layer_ids[$i] . ",%';";
		}

		if ($with_datatypes) {
			# Frage Datatypes der Layer ab
			$datatypes = $this->get_datatypes($layer_ids);

			foreach ($datatypes AS $datatype) {
				$datatype_dump = $database->create_insert_dump(
					'datatypes',
					'id',
					"
						SELECT
							*
						FROM
							datatypes
						WHERE
							id = " . $datatype['id'] . "
					"
				);

				$dump_text .= "\n\n-- Datatype " . $datatype['id'] . "\n" . $datatype_dump['insert'][0];
				$last_datatype_id = '@last_datatype_id' . $datatype['id'];
				$dump_text .= "\nSET " . $last_datatype_id . "=LAST_INSERT_ID();";

				$datatype_attributes_dump = $database->create_insert_dump(
					'datatype_attributes',
					'',
					"
						SELECT
							'" . $last_datatype_id . "' AS datatype_id,
							`name`, `real_name`, `tablename`, `table_alias_name`, `type`, `geometrytype`, `constraints`, `nullable`, `length`, `decimal_length`, `default`, `form_element_type`,
							`options`, `alias`, `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`, `tooltip`, `group`, `raster_visibility`, `mandatory`, `quicksearch`,
							`order`, `privileg`, `query_tooltip`, `visible`, `vcheck_attribute`, `vcheck_operator`, `vcheck_value`, `arrangement`, `labeling`
						FROM
							datatype_attributes
						WHERE
							datatype_id = " . $datatype['id'] . "
					"
				);

				$dump_text .= "\n\n-- Datatype_attributes " . $datatype['id'] . "\n" . $datatype_attributes_dump['insert'][0];
			}
		}

		$filename = rand(0, 1000000).'.sql';
		$fp = fopen(IMAGEPATH . $filename, 'w');
		fwrite($fp, $dump_text);
		//fwrite($fp, str_replace('
//', '', $dump_text));
		#fwrite($fp, utf8_decode($dump_text));

		return array(
			'success' => $success,
			'layer_dumpfile' => $filename
		);
	}

	/*
	* Delete layer in MySQL-Database and
	* delete also its maintable if delete_maintable is true
	* @param $id integer The Layer id of the layer in MySQL-Database
	* @param $delete_maintable boolean Delete the maintable of the layer or not
	* @return boolean true if maintable has been deleted or false if not deleted or error
	*/
	function deleteLayer($id, $delete_maintable = false) {
		include_once(CLASSPATH . 'Layer.php');
		$layer = Layer::find_by_id($this->GUI, $id);
		if ($layer AND $layer->get('write_mapserver_templates')) {
			$layer->remove_mapserver_templates($mapDB);
		}

		if (
			$delete_maintable AND
			$layer->get('connectiontype') == 6 AND
			$layer->get('maintable') != '' AND
			$layer->get('schema') != '' AND
			!$layer->tableUsedFromOtherLayers()
		) {
			$layerDb = $this->getlayerdatabase($id, 'pgsql');
			$ret = $layerDb->drop_table($layer->get('schema'), $layer->get('maintable'));
			if (!$ret['success']) {
				return false;
			}
		};

		$layer->delete();

		return false;
	}

	function deleteRollenFilter(){
		$sql = "
			UPDATE
				u_rolle2used_layer
			SET
				rollenfilter = NULL
			WHERE
				user_id = " . $this->User_ID . "
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenFilter:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		$sql = "
			UPDATE
				rollenlayer
			SET
				rollenfilter = NULL
			WHERE
				user_id = " . $this->User_ID . "
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenFilter:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
	}

	function deleteRollenLayer($id = NULL, $type = NULL) {
		$rollenlayerset = $this->read_RollenLayer($id, $type);
		for ($i = 0; $i < count($rollenlayerset); $i++){
			if ($rollenlayerset[$i]['Typ'] == 'import') {
				if ($rollenlayerset[$i]['connectiontype'] == 6 ){
					# bei Postgis-Layern die Tabelle löschen
					$explosion = explode(CUSTOM_SHAPE_SCHEMA.'.', $rollenlayerset[$i]['Data']);
					$explosion = explode(' ', $explosion[1]);
					$sql = "
						SELECT
							count(id)
						FROM
							rollenlayer
						WHERE
							Data like '%" . $explosion[0] . "%'
					";
					$this->db->execSQL($sql);
					if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
					$rs = $this->db->result->fetch_array();
					if ($rs[0] == 1) {		# Tabelle nur löschen, wenn das der einzige Layer ist, der sie benutzt
						$sql = 'DROP TABLE IF EXISTS ' . CUSTOM_SHAPE_SCHEMA . '."' . trim($explosion[0], '"') . '";';
						$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>" . $sql,4);
						$query = pg_query($sql);
					}
				}
				elseif ($rollenlayerset[$i]['connectiontype'] == 0) {
					# bei Raster-Layern die Raster-Datei löschen
					unlink(SHAPEPATH . $rollenlayerset[$i]['Data']);
				}
			}
			$sql = "
				DELETE FROM rollenlayer
				WHERE id = " . $rollenlayerset[$i]['id'] . "
			";
			#echo $sql;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>" . $sql,4);
			$this->db->execSQL($sql);
			if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
			$this->delete_layer_attributes(-$rollenlayerset[$i]['id']);
			# auch die Klassen und styles löschen
			if ($rollenlayerset[$i]['Class'] != ''){
				foreach ($rollenlayerset[$i]['Class'] as $class){
					$this->delete_Class($class['Class_ID']);
				}
			}
		}
  }

	function addRollenLayerStyling($layer_id, $datatype, $labelitem, $user, $type) {
		global $supportedLanguages;
		$attrib['name'] = ' ';
		foreach ($supportedLanguages as $language) {
			if ($language != 'german') {
				$attrib['name_' . $language] = ' ';
			}
		}
		$attrib['layer_id'] = -$layer_id;
		$attrib['expression'] = '';
		$attrib['order'] = 0;
		$class_id = $this->new_Class($attrib);
		$this->formvars['class'] = $class_id;
		$color = $user->rolle->readcolor();
		$style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];
		$style['outlinecolorred'] = 0;
		$style['outlinecolorgreen'] = 0;
		$style['outlinecolorblue'] = 0;
		$style['outlinecolor'] = $style['outlinecolorred'] . ' ' . $style['outlinecolorgreen'] . ' ' . $style['outlinecolorblue'];
		switch ($datatype) {
			case 0 : {
				if($type == 'zoom' AND defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
					$style_id = $this->copyStyle(ZOOM2POINT_STYLE_ID);
				}
				if($type == 'import' AND defined('IMPORT_POINT_STYLE_ID') AND IMPORT_POINT_STYLE_ID != ''){
					$style_id = $this->copyStyle(IMPORT_POINT_STYLE_ID);
				}
				else{
					$style['size'] = 8;
					$style['maxsize'] = 8;
					$style['symbolname'] = 'circle';
				}
			} break;
			case 1 : {
				$style['width'] = 2;
				$style['minwidth'] = 1;
				$style['maxwidth'] = 3;
				$style['symbolname'] = NULL;
			} break;
			case 2 :{
				$style['size'] = 1;
				if($user->rolle->result_hatching){
					$style['symbolname'] = 'hatch';
					$style['size'] = 11;
					$style['width'] = 5;
					$style['angle'] = 45;
				}
				else{
					$style['symbolname'] = NULL;
				}
			}
		}
		$style['minsize'] = NULL;
		# echo '<p>neuer Style style_id: ' . $style_id;
		if (!$style_id) {
			$style_id = $this->new_Style($style);
		}
		$this->addStyle2Class($class_id, $style_id, 0); # den Style der Klasse zuordnen
		if ($user->rolle->result_hatching) {
			$style['symbolname'] = NULL;
			$style['width'] = 1;
			$style['colorred'] = -1;
			$style['colorgreen'] = -1;
			$style['colorblue'] = -1;
			$style_id = $this->new_Style($style);
			$this->addStyle2Class($class_id, $style_id, 0); # den Style der Klasse zuordnen
		}
		if ($labelitem != '') {
			$label['font'] = 'arial';
			$label['color'] = '0 0 0';
			$label['outlinecolor'] = '255 255 255';
			$label['size'] = 8;
			$label['minsize'] = 6;
			$label['maxsize'] = 10;
			$label['position'] = 9;
			$new_label_id = $this->new_Label($label);
			$this->addLabel2Class($class_id, $new_label_id, 0);
		}
	}

	function newRollenLayer($formvars) {
		$formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);
		$formvars['query'] = str_replace ( "'", "''", value_of($formvars, 'query'));
		$sql = "
			INSERT INTO rollenlayer (
				`original_layer_id`,
				`user_id`,
				`stelle_id`,
				`aktivStatus`,
				`Name`,
				`Datentyp`,
				`Gruppe`,
				`Typ`,
				`Data`,
				`query`,
				`connectiontype`,
				" . (array_key_exists('connection', $formvars) ? "`connection`," : "") . "
				" . (array_key_exists('connection_id', $formvars) ? "`connection_id`," : "") . "
				`transparency`,
				`epsg_code`,
				`labelitem`,
				`classitem`,
				`wms_auth_username`,
				`wms_auth_password`
			)
			VALUES (
				" . ($formvars['original_layer_id'] ?: 'NULL') . ",
				'" . $formvars['user_id'] . "',
				'" . $formvars['stelle_id'] . "',
				'" . $formvars['aktivStatus'] . "',
				'" . $this->db->mysqli->real_escape_string($formvars['Name']) . "',
				'" . $formvars['Datentyp'] . "',
				'" . $formvars['Gruppe'] . "',
				'" . $formvars['Typ'] . "',
				'" . $formvars['Data'] . "',
				'" . $formvars['query'] . "',
				'" . $formvars['connectiontype'] . "',
				" . (array_key_exists('connection', $formvars) ? "'" . $formvars['connection'] . "'," : "") . "
				" . (array_key_exists('connection_id', $formvars) ? "'" . $formvars['connection_id'] . "'," : "") . "
				'" . $formvars['transparency'] . "',
				'" . $formvars['epsg_code'] . "',
				'" . $formvars['labelitem'] . "',
				'" . $formvars['classitem'] . "',
				'" . $formvars['wms_auth_username'] . "',
				'" . $formvars['wms_auth_password'] . "'
			)
		";
    #echo 'SQL: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->newRollenLayer - Erzeugen eines RollenLayers" . str_replace($formvars['connection'], 'Connection', $sql), 4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql, $formvars['connection']); return 0; }
		return $this->db->mysqli->insert_id;
	}

	function createAutoClasses($classes, $attribute, $layer_id, $datatype, $database){
		global $supportedLanguages;
		$result_colors = read_colors($database);
		shuffle($result_colors);
		$i = 0;
		foreach($classes as $value => $name){
			if($i == count($result_colors))return;				# Anzahl der Klassen ist auf die Anzahl der Colors beschränkt
			$classdata['name'] = $name.' ';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$classdata['name_'.$language] = $name.' ';
				}
			}
      $classdata['layer_id'] = -$layer_id;
			$classdata['classification'] = $attribute;
      $classdata['expression'] = "('[" . $attribute."]' eq '" . $value."')";
      $classdata['order'] = 0;
      $class_id = $this->new_Class($classdata);
    	$style['colorred'] = $result_colors[$i]['red'];
      $style['colorgreen'] = $result_colors[$i]['green'];
      $style['colorblue'] = $result_colors[$i]['blue'];
      $style['outlinecolorred'] = 0;
      $style['outlinecolorgreen'] = 0;
      $style['outlinecolorblue'] = 0;
     	$style['size'] = 3;
			$style['width'] = 2;
     	if($datatype == MS_LAYER_POINT){
      	$style['symbolname'] = 'circle';
      	if($datatype == 0){
      		$style['size'] = 13;
      		$style['minsize'] = 5;
      		$style['maxsize'] = 20;
      	}
     	}
      $style_id = $this->new_Style($style);
      $this->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
			$i++;
		}
	}

	/**
	* Function updateLayer($formvars array, $duplicate boolean)
	* @param array @formvars Das Array mit Attributen des Layers die für den Update verwendet werden sollen
	* @param boolean @duplicate Wenn true werden folgende Attribute nicht!! gespeichert weil es sich um ein
	*		Duplikat von einem anderen Layer handelt:
	*		alias, Gruppe, duplicate_from_layer_id, duplicate_criterion, Name und alle Namen in anderen Sprachen
	*		und die Attribute für Default-Werte für Stellen-Zuweisung
	*		template, queryable, transparency, legendorder, minscale, maxscale, symbolscale, offsite, requires, postlabelcache
	*/
	function updateLayer($formvars, $duplicate = false) {
		global $supportedLanguages;

		# trim attributes to prevent big surprise if layer not work as expected
		# due to spaces in string concatenations with these attributes
		$formvars['maintable'] = trim($formvars['maintable']);
		$formvars['schema'] = trim($formvars['schema']);
		foreach (array('pfad', 'Data', 'metalink', 'terms_of_use_link', 'duplicate_criterion', 'comment') AS $var_name) {
			$formvars[$var_name] = $this->db->mysqli->real_escape_string($formvars[$var_name]);
		}
		if ($formvars['id'] == '') {
			$formvars['selected_layer_id'] = $formvars['id'];		// falls man aus Versehen auf Speichern gedrückt hat, obwohl man einen neuen Layer anlegen wollte
		}
		$formvars['Layer_ID'] = $formvars['id'];
		$attribute_sets = array();

		if (!$duplicate) {
			# Diese Attribute nicht auf Dublikate übertragen
			# Schreibt alle String-Attribute, die NULL sein sollen wenn sie einen Leerstring enthalten.
			foreach(
				array(
					'alias',
					'Gruppe',
					'duplicate_from_layer_id',
					'duplicate_criterion',
					'Name',
					'Datentyp',
					'template',
					'queryable',
					'use_geom',
					'transparency',
					'legendorder',
					'minscale',
					'maxscale',
					'symbolscale',
					'offsite',
					'requires',
					'postlabelcache',
					'kurzbeschreibung',
					'dataowner_name',
					'dataowner_email',
					'dataowner_tel',
					'uptodateness',
					'updatecycle',
					'metalink',
					'terms_of_use_link',
					'comment'
				) AS $key
			) {
				$attribute_sets[] = "`" . $key . "` = " . ($formvars[$key] == '' ? 'NULL' : "'" . $formvars[$key] . "'");
			}

			# Scheibt alle unterstützten Language Attribute, außer german dafür heißt das Attribut nur Name
			foreach($supportedLanguages as $language) {
				if ($language != 'german') {
					$attribute_sets[] = "`Name_" . $language . "` = '" . $formvars['Name_'.$language] . "'";
				}
			}
		}

		# Die nächsten Attribute auch schreiben wenn Daten auf Dublikate übertragen werden.
		# Schreibt alle Attribute, die nur geschrieben werden sollen wenn Wert != '' ist
		foreach(
			array(
				'Layer_ID'
			) AS $key
		) {
			if ($formvars[$key]	!= '') {
				$attribute_sets[] = "`" . $key . "` = '" . $formvars[$key] . "'";
			}
		}

		# Schreibt alle Attribute, die NULL bekommen sollen wenn Wert == '' ist
		$null_attributes = array(
			'cluster_maxdistance',
			'labelmaxscale',
			'labelminscale',
			'sizeunits',
			'connection_id',
			'max_query_rows',
			'shared_from',
			'write_mapserver_templates'
		);

		foreach($null_attributes AS $key
		) {
			$attribute_sets[] = "`" . $key . "` = " . ($formvars[$key] == '' ? 'NULL' : "'" . $formvars[$key] . "'");
		}

		# Schreibt alle Attribute, die '0' bekommen sollen wenn Wert == '' ist
		$zero_if_empty_attributes = array(
			'drawingorder',
			'listed'
		);
		
		if ($this->GUI->plugin_loaded('mobile')) {
			$zero_if_empty_attributes = array_merge(
				$zero_if_empty_attributes,
				array('sync')
			);
		}

		foreach ($zero_if_empty_attributes AS $key) {
			$attribute_sets[] = "`" . $key . "` = '" . ($formvars[$key] == '' ? '0' : $formvars[$key]) . "'";
		}

		# Schreibt alle Attribute, die getrimmt werden sollen
		foreach(
			array(
				'connection'
			) AS $key
		) {
			if ($formvars[$key]	!= '') {
				$attribute_sets[] = "`" . $key . "` = '" . trim($formvars[$key]) . "'";
			}
		}

		# Hängt Character / an Attribute an wenn es am Ende fehlt
		foreach(
			array(
				'document_path'
			) AS $key
		) {
				$attribute_sets[] = "`" . $key . "` = '" . append_slash($formvars[$key]) . "'";
		}

		# Setzt Wert auf default wenn leer
		foreach(
			array(
				'version'
			) AS $key
		) {
				$attribute_sets[] = "`" . $key . "` = '" . ($formvars[$key] == '' ? '1.0.0' : $formvars[$key]) . "'";
		}

		# Schreibt alle Attribute, die immer geschrieben werden sollen, egal wie der Wert ist
		# Besonderheit beim Attribut classification, kommt aus Field layer_classification,
		# weil classification schon belegt ist von den Classes
		$attribute_sets[] = "`classification` = '" . $formvars['layer_classification'] . "'";
		# the rest where column names equal to the field names in layer editor form

		$column_equal_field_attributes = array(
			'pfad',
			'maintable',
			'oid',
			'identifier_text',
			'Data',
			'schema',
			'document_url',
			'ddl_attribute',
			'tileindex',
			'tileitem',
			'labelangleitem',
			'labelitem',
			'labelrequires',
			'printconnection',
			'connectiontype',
			'classitem',
			'styleitem',
			'tolerance',
			'toleranceunits',
			'epsg_code',
			'ows_srs',
			'wms_name',
			'wms_keywordlist',
			'wms_server_version',
			'wms_format',
			'wms_connectiontimeout',
			'wms_auth_username',
			'wms_auth_password',
			'wfs_geom',
			'selectiontype',
			'querymap',
			'processing',
			'status',
			'trigger_function'
		);
		if ($this->GUI->plugin_loaded('mobile')) {
			$column_equal_field_attributes = array_merge(
				$column_equal_field_attributes,
				array('vector_tile_url')
			);
		}

		foreach($column_equal_field_attributes AS $key) {
			$attribute_sets[] = "`" . $key . "` = '" . $formvars[$key] . "'";
		}

		$sql = "
			UPDATE
				layer
			SET
				" . implode(', ', $attribute_sets) . "
			WHERE
				Layer_ID = " . $formvars['selected_layer_id'] . "
		";
		#echo '<br>SQL zum update eines Layers: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->updateLayer - Aktualisieren eines Layers:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$this->GUI->add_message('error', $ret[1]);
			return 0;
		}
	}

	function updateDrawingorder($formvars){
		foreach($formvars['layers'] as $i => $layer_id) {
			$sql = "
				UPDATE
					layer
				SET
					drawingorder = " . $formvars['orders'][$i] . "
				WHERE
					Layer_ID = " . $layer_id;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->updateLayer - Aktualisieren eines Layers:<br>" . $sql, 4);
			$ret = $this->db->execSQL($sql, 4, 1, true);
			if (!$ret['success']) {
				$this->GUI->add_message('error', $ret[1]);
				return 0;
			}
		}
	}

	/**
	* function create a new layer based on layerdata.
	* layerdata can be an array of parameters from a formular or
	* a layer object where the values for the new layer will be taken from the object variables
	* params $layerdata [Array|Object]
	* return Integer The ID of new created layer or in case of error the number 0
	*/
	function newLayer($layerdata) {
		include_once(CLASSPATH . 'LayerDataSource.php');
		global $supportedLanguages;

		$datasource_ids = array();

		# Erzeugt einen neuen Layer (entweder aus formvars oder aus einem Layerobjekt)
		if (is_array($layerdata)) {
			# formvars wurden übergeben
			$formvars = $layerdata;
			$name_columns = '';
			$names = '';
			foreach ($supportedLanguages as $language) {
				if ($language != 'german') {
					$name_columns .= "`Name_" . $language."`, ";
					$names .= quote($formvars['Name_' . $language]) . ", ";
				}
			}

			$sql = "
				INSERT INTO layer (
					" . ($formvars['id'] != '' ? "`Layer_ID`," : '') . "
					`Name`,
					" . $name_columns . "
					`alias`,
					`Datentyp`,
					`Gruppe`,
					`pfad`,
					`maintable`,
					`oid`,
					`identifier_text`,
					`Data`,
					`schema`,
					`document_path`,
					`document_url`,
					`tileindex`,
					`tileitem`,
					`labelangleitem`,
					`labelitem`,
					`labelmaxscale`,
					`labelminscale`,
					`labelrequires`,
					`postlabelcache`,
					`connection`,
					`connection_id`,
					`printconnection`,
					`connectiontype`,
					`classitem`,
					`styleitem`,
					`classification`,
					`cluster_maxdistance`,
					`tolerance`,
					`toleranceunits`,
					`sizeunits`,
					`epsg_code`,
					`template`,
					`queryable`,
					`use_geom`,
					`transparency`,
					`drawingorder`,
					`legendorder`,
					`minscale`,
					`maxscale`,
					`symbolscale`,
					`offsite`,
					`requires`,
					`ows_srs`,
					`wms_name`,
					`wms_keywordlist`,
					`wms_server_version`,
					`wms_format`,
					`wms_connectiontimeout`,
					`wms_auth_username`,
					`wms_auth_password`,
					`wfs_geom`,
					`write_mapserver_templates`,
					`selectiontype`,
					`querymap`,
					`processing`,
					`kurzbeschreibung`,
					`dataowner_name`,
					`dataowner_email`,
					`dataowner_tel`,
					`uptodateness`,
					`updatecycle`,
					`metalink`,
					`terms_of_use_link`,
					`status`,
					`trigger_function`,
					`listed`,
					`duplicate_from_layer_id`,
					`duplicate_criterion`,
					`shared_from`,
					`version`,
					`comment`
					" . ($this->GUI->plugin_loaded('mobile') ? ', `sync`' : '') . "
					" . ($this->GUI->plugin_loaded('mobile') ? ', `vector_tile_url`' : '') . "
					" . ($this->GUI->plugin_loaded('portal') ? ', `cluster_option`' : '') . "
				)
				VALUES (
					" . ($formvars['id'] != '' ? $formvars['id'] . ', ' : '') . "
					" . quote_or_null($formvars['Name']) . ",
					" . $names . "
					" . quote($formvars['alias']) . ",
					" . quote_or_null($formvars['Datentyp']) . ",
					" . quote_or_null($formvars['Gruppe']) . ",
					" . quote_or_null($this->db->mysqli->real_escape_string($formvars['pfad'])) . ",
					" . quote_or_null(trim($formvars['maintable'])) . ",
					" . quote_or_null($formvars['oid']) . ",
					" . quote_or_null($formvars['identifier_text']) . ",
					" . quote_or_null($this->db->mysqli->real_escape_string($formvars['Data'])) . ",
					" . quote_or_null(trim($formvars['schema'])) . ",
					" . quote_or_null(append_slash($formvars['document_path'])) . ",
					" . quote_or_null($formvars['document_url']) . ",
					" . quote($formvars['tileindex']) . ",
					" . quote($formvars['tileitem']) . ",
					" . quote($formvars['labelangleitem']) . ",
					" . quote($formvars['labelitem']) . ", -- labelitem
					" . quote_or_null($formvars['labelmaxscale']) . ",
					" . quote_or_null($formvars['labelminscale']) . ",
					" . quote($formvars['labelrequires']) . ",
					" . ($formvars['postlabelcache'] == '' ? '0' :  $formvars['postlabelcache']) . ",
					" . quote(trim($formvars['connection'])) . ", -- connection
					" . quote_or_null($formvars['connection_id']) . ",
					" . quote($formvars['printconnection']) . ",
					" . ($formvars['connectiontype'] == '' ? '6' : $formvars['connectiontype']) . ",
					" . quote($formvars['classitem']) . ", -- classitem
					" . quote($formvars['styleitem']) . ",
					" . quote($formvars['layer_classification']) . ",
					" . quote_or_null($formvars['cluster_maxdistance']) . ",
					" . ($formvars['tolerance'] == '' ?  '3' : $formvars['tolerance']) . ",
					" . quote($formvars['toleranceunits'] == '' ? 'pixels' : $formvars['toleranceunits']) . ",
					" . quote_or_null($formvars['sizeunits']) . ",
					" . quote($formvars['epsg_code']) . ",
					" . quote($formvars['template']) . ",
					" . quote(($formvars['queryable'] == '' ? '0' : $formvars['queryable']), 'text') . ",
					" . quote($formvars['use_geom'] == '' ? '1' : $formvars['use_geom']) . ",
					" . quote_or_null($formvars['transparency']) . ",
					" . ($formvars['drawingorder'] == '' ? '0' :  $formvars['drawingorder']) . ",
					" . quote_or_null($formvars['legendorder']) . ",
					" . quote_or_null($formvars['minscale']) . ",
					" . quote_or_null($formvars['maxscale']) . ",
					" . quote_or_null($formvars['symbolscale']) . ",
					" . quote($formvars['offsite']) . ",
					" . quote_or_null($formvars['requires']) . ",
					" . quote($formvars['ows_srs'] == '' ? 'EPSG:4326' : $formvars['ows_srs']) . ",
					" . quote($formvars['wms_name']) . ",
					" . quote($formvars['wms_keywordlist']) . ",
					" . quote($formvars['wms_server_version'] == '' ? '1.0.0' : $formvars['wms_server_version']) . ",
					" . quote($formvars['wms_format'] == '' ? 'image/png' : $formvars['wms_format']) . ",
					" . ($formvars['wms_connectiontimeout'] == '' ? '60' :  $formvars['wms_connectiontimeout']) . ",
					" . quote($formvars['wms_auth_username']) . ",
					" . quote($formvars['wms_auth_password']) . ",
					" . quote($formvars['wfs_geom']) . ",
					" . quote(($formvars['write_mapserver_templates'] == '' ? '0' : $formvars['write_mapserver_templates']), 'text') . ",
					" . quote($formvars['selectiontype']) . ", -- selectiontype
					" . quote(($formvars['querymap'] == '' ? '0' : $formvars['querymap']), 'text') . ", -- querymap
					" . quote($formvars['processing']) . ",
					" . quote($this->db->mysqli->real_escape_string($formvars['kurzbeschreibung'])) . ",
					" . quote($formvars['dataowner_name']) . ",
					" . quote($formvars['dataowner_email']) . ",
					" . quote($formvars['dataowner_tel']) . ",
					" . quote($formvars['uptodateness']) . ",
					" . quote($formvars['updatecycle']) . ",
					" . quote($this->db->mysqli->real_escape_string($formvars['metalink'])) . ",
					" . quote($this->db->mysqli->real_escape_string($formvars['terms_of_use_link'])) . ",
					" . quote($formvars['status']) . ",
					" . quote($formvars['trigger_function']) . ",
			 		" . ($formvars['listed'] == '' ? '0' : $formvars['listed']) . ",
					" . quote_or_null($formvars['duplicate_from_layer_id']) . ",
					" . quote($this->db->mysqli->real_escape_string($formvars['duplicate_criterion'])) . ",
					" . quote_or_null($formvars['shared_from']) . ",
					'" . ($formvars['version'] == '' ? '1.0.0' : $formvars['version']) . "',
					" . quote_or_null($this->db->mysqli->real_escape_string($formvars['comment'])) . "
					" . ($this->GUI->plugin_loaded('mobile') ? ", " . quote(($formvars['sync'] == '' ? '0' : $formvars['sync']), 'text') : '') . "
					" . ($this->GUI->plugin_loaded('mobile') ? ", " . quote($formvars['vector_tile_url']) : '') . "
					" . ($this->GUI->plugin_loaded('portal') ? ", " . quote(($formvars['cluster_option'] == '' ? '0' : $formvars['cluster_option']), 'text') : '') . "
				)
			";
    }
		else {
			# ein Layerobject wurde übergeben
			$layer = $layerdata;
			$projection = explode('epsg:', $layer->getProjection());
			$sql = "
				INSERT INTO layer (
					`Name`,
					`Datentyp`,
					`Gruppe`,
					`pfad`,
					`Data`,
					`tileindex`,
					`tileitem`,
					`labelangleitem`,
					`labelitem`,
					`labelmaxscale`,
					`labelminscale`,
					`labelrequires`,
					`connection`,
					`connectiontype`,
					`classitem`,
					`tolerance`,
					`toleranceunits`,
					`epsg_code`,
					`ows_srs`,
					`wms_name`,
					`wms_server_version`,
					`wms_format`,
					`wms_connectiontimeout`,
					`trigger_function`,
					`version`,
					`comment`
					" . ($this->GUI->plugin_loaded('mobile') ? ', `sync`' : '') . "
					" . ($this->GUI->plugin_loaded('mobile') ? ', `vector_tile_url`' : '') . "
					" . ($this->GUI->plugin_loaded('portal') ? ', `cluster_option`' : '') . "
				)
				VALUES (
					'" . $layer->name . "',
					" . quote($layer->type) . ",
					'" . $layer->group . "',
					'',
					'" . $layer->data . "',
					'" . $layer->tileindex . "',
					'" . $layer->tileitem . "',
					'" . $layer->labelangleitem . "',
					'" . $layer->labelitem . "',
					" . $layer->labelmaxscale . ",
					" . $layer->labelminscale . ",
					'" . $layer->labelrequires . "',
					'" . $layer->connection . "',
					" . $layer->connectiontype.",
					'" . $layer->classitem . "',
					" . $layer->tolerance . ",
					'" . $layer->toleranceunits ."',
					'" . $projection[1] . "',
					'',
					'',
					'',
					'',
					60,
					'" . $layer->trigger_function . "',
					'" . $layer->version . "',
					'" . $layer->comment . "'
					" . ($this->GUI->plugin_loaded('mobile') ? ', ' . quote($layer->sync) : '') . "
					" . ($this->GUI->plugin_loaded('mobile') ? ', ' . quote($layer->vector_tile_url) : '') . "
					" . ($this->GUI->plugin_loaded('portal') ? ', ' . quote($layer->cluster_option) : '') . "
				)
			";
		}

		#echo '<p>SQL zum Eintragen eines Layers: '. $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->newLayer - Erzeugen eines Layers:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) {
			return 0;
		}

		if (count($datasource_ids) > 0) {
			$layer_datasource = new LayerDataSource($this->GUI);
			foreach($datasource_ids AS $datasource_id) {
				$layer_datasource->create(
					array(
						'layer_id' => $this->db->mysqli->insert_id,
						'datasource_id' => $datasource_id
					)
				);
			}
		}

		return $this->db->mysqli->insert_id;
	}

	function save_datatype_attributes($attributes, $database, $formvars){
		global $supportedLanguages;
		$language_columns = array();

		foreach ($supportedLanguages as $language){
			if ($language != 'german') {
				$language_columns[] = "`alias_" . $language . "` = '" . $formvars['alias_' . $language . '_' . $attributes['name'][$i]] . "'";
			}
		}
		$language_columns = (count($language_columns) > 0 ? implode(',
					', $language_columns) . ',' : '');

		for ($i = 0; $i < count($attributes['name']); $i++) {
			if($formvars['visible_' . $attributes['name'][$i]] != 2 OR $formvars['vcheck_value_'.$attributes['name'][$i]] == ''){
				$formvars['vcheck_attribute_'.$attributes['name'][$i]] = '';
				$formvars['vcheck_operator_'.$attributes['name'][$i]] = '';
				$formvars['vcheck_value_'.$attributes['name'][$i]] = '';
			}
			$set_values = 
				$language_columns . "
				`name` = '" . $attributes['name'][$i] . "',
				`form_element_type` = '" . ($formvars['form_element_' . $attributes['name'][$i]] ?: 'Text'). "',
				`options` = '" . pg_escape_string($formvars['options_' . $attributes['name'][$i]]) . "',
				`tooltip` = '" . pg_escape_string($formvars['tooltip_' . $attributes['name'][$i]]) . "',
				`alias` = '" . $formvars['alias_'.$attributes['name'][$i]] . "',
				`group` = '" . $formvars['group_' . $attributes['name'][$i]] . "',
				`raster_visibility` = " . ($formvars['raster_visibility_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['raster_visibility_' . $attributes['name'][$i]]) . ",
				`mandatory` = " . ($formvars['mandatory_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['mandatory_' . $attributes['name'][$i]]) . ",
				`quicksearch` = " . ($formvars['quicksearch_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['quicksearch_' . $attributes['name'][$i]]) . ",
				`visible` = " . ($formvars['visible_' . $attributes['name'][$i]] == '' ? "0" : $formvars['visible_' . $attributes['name'][$i]]) . ",
				`vcheck_attribute`= '" . $formvars['vcheck_attribute_'.$attributes['name'][$i]]."',
				`vcheck_operator`= '" . $formvars['vcheck_operator_'.$attributes['name'][$i]]."',
				`vcheck_value`= '" . $formvars['vcheck_value_'.$attributes['name'][$i]]."',
				`arrangement` = " . ($formvars['arrangement_' . $attributes['name'][$i]] == '' ? "0" : $formvars['arrangement_' . $attributes['name'][$i]]) . ",
				`labeling` = " . ($formvars['labeling_' . $attributes['name'][$i]] == '' ? "0" : $formvars['labeling_' . $attributes['name'][$i]]);
				
			if ($formvars['for_all_layers'] != 1) {
				# nur für einen bestimmten Layer
				$sql = "
					INSERT INTO
						datatype_attributes
					SET
						`layer_id` = " . $formvars['selected_layer_id'] . ",
						`datatype_id` = " . $formvars['selected_datatype_id'] . ",
						" . $set_values . "
					ON DUPLICATE KEY UPDATE
						" . $set_values . "
				";
			}
			else {
				# für alle Layer, die diesen Datentyp benutzen
				$sql = "
					UPDATE
						datatype_attributes
					SET
						" . $set_values . "
					WHERE
						`datatype_id` = " . $formvars['selected_datatype_id'] . " AND
						`name` = '" . $attributes['name'][$i] . "'";
			}
			#echo '<br>Save datatype Sql: ' . $sql;
			$this->debug->write("<p>file:kvwmap class:Document->save_datatype_attributes :", 4);
			$ret = $database->execSQL($sql, 4, 1);
			if ($ret[0]) {
				$msg = 'Fehler beim Speichern der Datentypeinstellungen:<br>' . $ret[1];
				$this->debug->write('<p>file:kvwmap class:Document->save_datatype_attributes: ' . $msg, 4);
				$this->GUI->add_message('error', $msg);
			}
		}
	}

	/*
	* Speichert die Einstellungen, die in formvars enthalten sind für die Attribute, die in $attributes angegeben sind.
	* Formvars, die nicht zu Attributen in $attributes passen werden ignoriert.
	*/
	function save_layer_attributes($attributes, $database, $formvars){
		global $supportedLanguages;

		for ($i = 0; $i < count($attributes['name']); $i++) {
			if ($formvars['attribute_' . $attributes['name'][$i]] != '') {
				$alias_rows = "`alias` = '" . $formvars['alias_' . $attributes['name'][$i]] . "',";
				foreach ($supportedLanguages as $language) {
					if ($language != 'german') {
						$alias_rows .= "`alias_" . $language . "` = '" . $formvars['alias_' . $language . '_' . $attributes['name'][$i]] . "',";
					}
				}
				if ($formvars['visible_' . $attributes['name'][$i]] != 2){
					$formvars['vcheck_attribute_'.$attributes['name'][$i]] = '';
					$formvars['vcheck_operator_'.$attributes['name'][$i]] = '';
					$formvars['vcheck_value_'.$attributes['name'][$i]] = '';
				}
				if ($formvars['group_' . $attributes['name'][$i]] == '' AND $last_group != ''){
					$formvars['group_' . $attributes['name'][$i]] = $last_group;
				}
				$last_group = $formvars['group_' . $attributes['name'][$i]];
				if ($formvars['tab_' . $attributes['name'][$i]] == '' AND $last_tab != ''){
					$formvars['tab_' . $attributes['name'][$i]] = $last_tab;
				}
				$last_tab = $formvars['tab_' . $attributes['name'][$i]];
				$rows = "
					`order` = " . ($formvars['order_' . $attributes['name'][$i]] == '' ? 0 : $formvars['order_' . $attributes['name'][$i]]) . ",
					`name` = '" . $attributes['name'][$i] . "', " .
					$alias_rows . "
					`form_element_type` = '" . ($formvars['form_element_' . $attributes['name'][$i]] ?: 'Text'). "',
					`options` = '" . pg_escape_string($formvars['options_' . $attributes['name'][$i]]) . "',
					`default` = '" . pg_escape_string($formvars['default_' . $attributes['name'][$i]]) . "',
					`tooltip` = '" . pg_escape_string($formvars['tooltip_' . $attributes['name'][$i]]) . "',
					`group` = '" . $formvars['group_' . $attributes['name'][$i]] . "',
					`tab` = '" . $formvars['tab_' . $attributes['name'][$i]] . "',
					`arrangement` = " . ($formvars['arrangement_' . $attributes['name'][$i]] == '' ? 0 : $formvars['arrangement_' . $attributes['name'][$i]]) . ",
					`labeling` = " . ($formvars['labeling_' . $attributes['name'][$i]] == '' ? 0 : $formvars['labeling_' . $attributes['name'][$i]]) . ",
					`raster_visibility` = " . ($formvars['raster_visibility_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['raster_visibility_' . $attributes['name'][$i]]) . ",
					`dont_use_for_new`= " . ($formvars['dont_use_for_new_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['dont_use_for_new_' . $attributes['name'][$i]]) . ",
					`mandatory` = " . ($formvars['mandatory_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['mandatory_' . $attributes['name'][$i]]) . ",
					`quicksearch`= " . ($formvars['quicksearch_' . $attributes['name'][$i]] == '' ? "NULL" : $formvars['quicksearch_' . $attributes['name'][$i]]) . ",
					`visible`= ".($formvars['visible_'.$attributes['name'][$i]] == '' ? "0" : $formvars['visible_'.$attributes['name'][$i]]).",
					`vcheck_attribute`= '" . $formvars['vcheck_attribute_'.$attributes['name'][$i]]."',
					`vcheck_operator`= '" . $formvars['vcheck_operator_'.$attributes['name'][$i]]."',
					`vcheck_value`= '" . $formvars['vcheck_value_'.$attributes['name'][$i]]."'
				";
				$sql = "
					INSERT INTO
						`layer_attributes`
					SET
						`layer_id` = " . $formvars['selected_layer_id'] . ", " .
						$rows . "
					ON DUPLICATE KEY UPDATE " .
						$rows . "
				";
				#echo '<br>Sql: ' . $sql;
				$this->debug->write("<p>file:kvwmap class:Document->save_layer_attributes :",4);
				$database->execSQL($sql, 4, 1);
			}
		}
	}

	function delete_layer_filterattributes($layer_id){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_filterattributes:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
  }

  function delete_layer_attributes($layer_id){
    $sql = 'DELETE FROM layer_attributes WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
  }

  function read_datatype_attributes($layer_id, $datatype_id, $datatypedb, $attributenames, $all_languages = false, $recursive = false, $replace = true){
		global $language;

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN `alias_" . $language. "` != '' THEN `alias_" . $language . "`
					ELSE `alias`
				END AS alias
			" :
			"
				`alias`
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT " .
				$alias_column . ", `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`,
				`datatype_id`,
				a.`name`,
				`real_name`,
				`tablename`,
				`table_alias_name`,
				`type`,
				d.`name` as typename,
				`geometrytype`,
				`constraints`,
				`nullable`,
				`length`,
				`decimal_length`,
				`default`,
				`form_element_type`,
				`options`,
				`tooltip`,
				`group`,
				`raster_visibility`,
				`mandatory`,
				`quicksearch`,
				`order`,
				`privileg`,
				`query_tooltip`,
				`visible`,
				`vcheck_attribute`,
				`vcheck_operator`,
				`vcheck_value`,
				`arrangement`,
				`labeling`
			FROM
				`datatype_attributes` as a LEFT JOIN
				`datatypes` as d ON d.`id` = REPLACE(`type`, '_', '')
			WHERE
				`layer_id` = " . $layer_id . " AND 
				`datatype_id` = " . $datatype_id .
				$einschr . "
			ORDER BY
				`order`
		";
		/* Attributes die fehlen im Vergleich zu layer_attributes
		`dont_use_for_new`
		*/
		#echo '<br>Sql read_datatype_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_datatype_attributes:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$i = 0;
		while($rs = $ret['result']->fetch_array()){
			$attributes['datatype_id'][$i] = $rs['datatype_id'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			$attributes['real_name'][$rs['name']]= $rs['real_name'];
			if($rs['tablename']){
				if(strpos($rs['tablename'], '.') !== false){
					$explosion = explode('.', $rs['tablename']);
					$rs['tablename'] = $explosion[1];		# Tabellenname ohne Schema
					$attributes['schema_name'][$rs['tablename']] = $explosion[0];
				}
				$attributes['table_name'][$i]= $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
			}
			if($rs['table_alias_name'])$attributes['table_alias_name'][$i]= $rs['table_alias_name'];
			if($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']]= $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']]= $rs['table_alias_name'];
			$attributes['type'][$i]= $rs['type'];
			$attributes['typename'][$i]= $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($layer_id, $type, $layerdb, NULL, $all_languages, true, $replace);
			}
			if($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
			if($datatypedb != NULL){
				if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
					$ret1 = $datatypedb->execSQL($rs['default'], 4, 0);
					if($ret1[0]==0){
						$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
					}
				}
				else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den datatype in dieser Version einmal gespeichert hat
					$attributes['default'][$i]= $rs['default'];
				}
			}

			if ($replace) {
				if ($attributes['default'][$i] != '')	{					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
					$replaced_default = replace_params_rolle($attributes['default'][$i]);
					$ret1 = $layerdb->execSQL('SELECT ' . $replaced_default, 4, 0);
					if ($ret1[0] == 0) {
						$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
					}
				}
				$rs['options'] = replace_params_rolle($rs['options']);
			}

			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$attributes['options'][$i]= $rs['options'];
			$attributes['options'][$rs['name']]= $rs['options'];
			$attributes['alias'][$i]= $rs['alias'];
			$attributes['alias_low-german'][$i]= $rs['alias_low-german'];
			$attributes['alias_english'][$i]= $rs['alias_english'];
			$attributes['alias_polish'][$i]= $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i]= $rs['alias_vietnamese'];
			$attributes['tooltip'][$i]= $rs['tooltip'];
			$attributes['group'][$i]= $rs['group'];
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$attributes['raster_visibility'][$i]= $rs['raster_visibility'];
			$attributes['mandatory'][$i]= $rs['mandatory'];
			$attributes['quicksearch'][$i]= $rs['quicksearch'];
			$attributes['privileg'][$i]= $rs['privileg'];
			$attributes['query_tooltip'][$i]= $rs['query_tooltip'];
			$attributes['visible'][$i]= $rs['visible'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];
			$attributes['arrangement'][$i]= $rs['arrangement'];
			$attributes['labeling'][$i]= $rs['labeling'];
			$i++;
		}
		return $attributes;
  }

  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false, $get_default = false, $replace = true, $replace_only = array('default', 'options', 'vcheck_value'), $attribute_values = []) {
		global $language;
		$attributes = array(
			'name' => array(),
			'tab' => array()
		);
		$einschr = '';

		$alias_column = (
			(!$all_languages AND $language != 'german') ?
			"
				CASE
					WHEN `alias_" . $language. "` != '' THEN `alias_" . $language . "`
					ELSE `alias`
				END AS alias
			" :
			"
				`alias`
			"
		);

		if ($attributenames != NULL) {
			$einschr = " AND a.name IN ('" . implode("', '", $attributenames) . "')";
		}

		$sql = "
			SELECT
				`order`, " .
				$alias_column . ", `alias_low-german`, `alias_english`, `alias_polish`, `alias_vietnamese`,
				`layer_id`,
				a.`name`,
				`real_name`,
				`tablename`,
				`table_alias_name`,
				a.`schema`,
				`type`,
				d.`name` as typename,
				`geometrytype`,
				`constraints`,
				`saveable`,
				`nullable`,
				`length`,
				`decimal_length`,
				`default`,
				`form_element_type`,
				`options`,
				`tooltip`,
				`group`,
				`tab`,
				`arrangement`,
				`labeling`,
				`raster_visibility`,
				`dont_use_for_new`,
				`mandatory`,
				`quicksearch`,
				`visible`,
				`vcheck_attribute`,
				`vcheck_operator`,
				`vcheck_value`,
				`order`,
				`privileg`,
				`query_tooltip`
			FROM
				`layer_attributes` as a LEFT JOIN
				`datatypes` as d ON d.`id` = REPLACE(`type`, '_', '')
			WHERE
				`layer_id` = " . $layer_id .
				$einschr . "
			ORDER BY
				`order`
		";
		// echo '<br>Sql read_layer_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>",4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$i = 0;
		while ($rs = $ret['result']->fetch_array()){
			$attributes['enum'][$i] = array();
			$attributes['order'][$i] = $rs['order'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			if($rs['real_name'] == '')$rs['real_name'] = $rs['name'];
			$attributes['real_name'][$rs['name']] = $rs['real_name'];
			if ($rs['tablename']) {
				$attributes['table_name'][$i] = $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
				$attributes['schema'][$i] = $rs['schema'];
			}
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$i] = $rs['table_alias_name'];
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']] = $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']] = $rs['table_alias_name'];
			$attributes['type'][$i] = $rs['type'];
			$attributes['typename'][$i] = $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if ($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($layer_id, $type, $layerdb, NULL, $all_languages, true, $replace);
			}
			if ($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			if ($rs['constraints'] == 'PRIMARY KEY') {
				$attributes['pk'][] = $rs['real_name'];
			}
			$attributes['saveable'][$i]= $rs['saveable'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];
			$attributes['default'][$i] = $rs['default'];
			$attributes['options'][$i] = $rs['options'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];			

			if ($replace) {
				foreach($replace_only AS $column) {
					if ($attributes[$column][$i] != '') {
						$attributes[$column][$i] = 	replace_params_rolle(
																					$attributes[$column][$i],
																					((count($attribute_values) > 0 AND $replace_only == 'default') ? $attribute_values : NULL)
																				);
					}
				}
			}

			if ($get_default AND $attributes['default'][$i] != '') {
				# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL('SELECT ' . $attributes['default'][$i], 4, 0);
				if ($ret1[0] == 0) {
					$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
				}
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$attributes['options'][$rs['name']] = $attributes['options'][$i];
			$attributes['alias'][$i] = $rs['alias'];
			$attributes['alias_low-german'][$i] = $rs['alias_low-german'];
			$attributes['alias_english'][$i] = $rs['alias_english'];
			$attributes['alias_polish'][$i] = $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i] = $rs['alias_vietnamese'];
			$attributes['tooltip'][$i] = $rs['tooltip'];
			$attributes['group'][$i] = $rs['group'];
			$attributes['tab'][$i] = $rs['tab'];
			$attributes['arrangement'][$i] = $rs['arrangement'];
			$attributes['labeling'][$i] = $rs['labeling'];
			$attributes['raster_visibility'][$i] = $rs['raster_visibility'];
			$attributes['dont_use_for_new'][$i] = $rs['dont_use_for_new'];
			$attributes['mandatory'][$i] = $rs['mandatory'];
			$attributes['quicksearch'][$i] = $rs['quicksearch'];
			$attributes['visible'][$i] = $rs['visible'];
			$attributes['privileg'][$i] = $rs['privileg'];
			$attributes['query_tooltip'][$i] = $rs['query_tooltip'];
			if ($rs['form_element_type'] == 'Style') {
				$attributes['style'] = $rs['name'];
				$attributes['visible'][$i] = 0;
			}
			if ($rs['form_element_type'] == 'Editiersperre') {
				$attributes['Editiersperre'] = $rs['name'];
			}
			$i++;
		}
		if (value_of($attributes, 'table_name') != NULL) {
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
		}
		else {
			$attributes['all_table_names'] = array();
		}
		$attributes['tabs'] = array_values(array_filter(array_unique($attributes['tab']), 'strlen'));
		return $attributes;
	}

	/*
	* Returns a list of datatypes used by layer, given in layer_ids array
	*/
	function get_datatypes($layer_ids) {
		$datatypes = array();
		$sql = "
			SELECT DISTINCT
				dt.*
			FROM
				`layer_attributes` la JOIN
				`datatypes` dt ON replace(la.type,'_', '') = dt.id
			WHERE
				la.layer_id IN (" . implode(', ', $layer_ids) . ")
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_datatypes - Lesen der Datentypen der Layer mit id (" . implode(', ', $layer_ids) . "):<br>" . $sql , 4);
		$ret = $this->db->execSQL($sql);
		if (!$ret['success']) {
			$this->GUI->add_message('error', err_msg($this->script_name, __LINE__, $sql));
			return $datatypes;
		}
		while ($rs = $this->db->result->fetch_assoc()) {
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Datatypes($order) {
		$datatypes = array();
		$order_sql = ($order != '') ? "ORDER BY " . replace_semicolon($order) : '';
		$sql = "
			SELECT
				*
			FROM
				datatypes
			" . $order_sql;

		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Datatypes - Lesen aller Datentypen:<br>" . $sql , 4);
		$this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		while($rs = $this->db->result->fetch_assoc()) {
			/*
			foreach($rs AS $key => $value) {
				$datatypes[$key][] = $value;
			}
			*/
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Layer($order = 'Gruppenname, Name', $only_listed = false, $user_id = NULL, $stelle_id = NULL) {
		global $language;
		global $admin_stellen;
		$more_from = '';
		$where = array();

		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`name_" . $language . "` != \"\" THEN l.`name_" . $language . "`
				ELSE l.`name`
			END";
		}
		else {
			$name_column = "l.`name`";
		}

		if ($language != 'german') {
			$gruppenname_column = "
			CASE
				WHEN g.`Gruppenname_" . $language . "` != \"\" THEN g.`Gruppenname_" . $language . "`
				ELSE g.`Gruppenname`
			END";
		}
		else {
			$gruppenname_column = "g.`Gruppenname`";
		}

		if ($only_listed) {
			$where[] = "listed = 1";
		}

		if ($user_id != NULL AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				LEFT JOIN used_layer ul ON l.Layer_ID = ul.Layer_id
				LEFT JOIN rolle radm ON ul.Stelle_ID = radm.stelle_id
			";
			$where[] = "(radm.user_id = " . $this->User_ID . " OR ul.Layer_id IS NULL)";
		}

		if (
			# Show non-admin users only layers that they self have shared
			!$this->GUI->is_admin_user($this->GUI->user->id)
		) {
			$where[] = "l.shared_from = " . $this->GUI->user->id;
		}

		if ($order != '') {
			$order = " ORDER BY " . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT " .
				$name_column . " AS Name," .
				$gruppenname_column . " AS Gruppenname,
				l.Layer_ID,
				l.Gruppe,
				l.Datentyp,
				l.connectiontype,
				l.metalink,
				l.kurzbeschreibung,
				l.wms_keywordlist,
				l.dataowner_name,
				l.dataowner_email,
				l.dataowner_tel,
				l.uptodateness,
				l.updatecycle,
				l.drawingorder,
				l.alias,
				l.shared_from
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`sync`' : '') . "
				" . ($this->GUI->plugin_loaded('mobile') ? ', l.`vector_tile_url`' : '') . "
				" . ($this->GUI->plugin_loaded('portal') ? ', l.`cluster_option`' : '') . "
			FROM
				layer l LEFT JOIN
				u_groups g ON l.Gruppe = g.id" .
				$more_from .
			(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";	
		#echo '<br>sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$i = 0;
		$layer = array(
			'ID' => array(),
			'Bezeichnung' => array(),
			'Gruppe' => array(),
			'GruppeID' => array(),
			'Kurzbeschreibung' => array(),
			'metalink' => array(),
			'wms_keywordlist' => array(),
			'datasources' => array(),
			'datasource_ids' => array(),
			'dataowner_name' => array(),
			'dataowner_email' => array(),
			'dataowner_tel' => array(),
			'uptodateness' => array(),
			'updatecycle' => array(),
			'alias' => array(),
			'drawingorder' => array(),
			'layers_of_group' => array(),
			'shared_from' => array()
		);
		if ($this->GUI->plugin_loaded('mobile')) {
			$layer['sync'] = array();
			$layer['vector_tile_url'] = array();
		}
		if ($this->GUI->plugin_loaded('portal')) {
			$layer['cluster_option'] = array();
		}
		while ($rs = $ret['result']->fetch_array()) {
			$layer['ID'][] = $rs['Layer_ID'];
			$layer['Bezeichnung'][] = $rs['Name'];
			$layer['Gruppe'][] = $rs['Gruppenname'];
			$layer['GruppeID'][] = $rs['Gruppe'];
			$layer['Datentyp'][] = $rs['Datentyp'];
			$layer['connectiontype'][] = $rs['connectiontype'];
			$layer['Kurzbeschreibung'][] = $rs['kurzbeschreibung'];
			$layer['metalink'][] = $rs['metalink'];
			$layer['wms_keywordlist'][] = $rs['wms_keywordlist'];
			$layer['dataowner_name'][] = $rs['dataowner_name'];
			$layer['dataowner_email'][] = $rs['dataowner_email'];
			$layer['dataowner_tel'][] = $rs['dataowner_tel'];
			$layer['uptodateness'][] = $rs['uptodateness'];
			$layer['updatecycle'][] = $rs['updatecycle'];
			$layer['alias'][] = $rs['alias'];
			$layer['Name_or_alias'][] = $rs[(($rs['alias'] AND $this->GUI->Stelle->useLayerAliases) ? 'alias' : 'Name')];
			$layer['drawingorder'][] = $rs['drawingorder'];
			$layer['layers_of_group'][$rs['Gruppe']][] = $i;
			$layer['shared_from'][] = $rs['shared_from'];
			if ($this->GUI->plugin_loaded('mobile')) {
				$layer['sync'][] = $rs['sync'];
				$layer['vector_tile_url'] = $rs['vector_tile_url'];
			}
			if ($this->GUI->plugin_loaded('portal')) {
				$layer['cluster_option'][] = $rs['cluster_option'];
			}
			$i++;
		}

		if ($order == 'Bezeichnung') {
			# Sortieren der Layer unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['ID'] = $sorted_arrays['second_array'];
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['GruppeID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['GruppeID'] = $sorted_arrays['second_array'];
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['drawingorder']);
			$layer['drawingorder'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}

	function get_all_layer_params() {
		$layer_params = array();
		$sql = "
			SELECT
				*
			FROM
				layer_parameter
		";
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else {
			while ($rs = $this->db->result->fetch_assoc()) {
				$layer_params[] = $rs;
			}
		}
		return $layer_params;
	}

	function get_layer_params_layer($param_id = NULL, $layer_id = NULL, $only_from_attribute = '') {
		$params = array();
		$sql = "
			SELECT
				p.id, p.key, l.Layer_ID, l.Name, l.alias
			FROM
				`layer_parameter` as p,
				layer as l
			WHERE
				locate(
					concat('$', p.key),
					concat(" . ($only_from_attribute ?: "l.Name, COALESCE(l.alias, ''), l.schema, l.connection, l.Data, l.pfad, l.classitem, l.classification, l.maintable, l.tileindex, COALESCE(l.connection, ''), COALESCE(l.processing, '')") . ")
				) > 0
		";
		if ($param_id != NULL) {
			$sql .= " AND p.id = ".$params_id;
		}
		if ($layer_id != NULL) {
			$sql .= " AND l.Layer_ID = " . $layer_id;
		}
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else {
			while ($rs = $this->db->result->fetch_assoc()) {
				$rs['Name_or_alias'] = $rs[($rs['alias'] == '' OR !$this->Stelle->useLayerAliases) ? 'Name' : 'alias'];
				$rs['Name_or_alias'] = replace_params_rolle($rs['Name_or_alias']);
				$params[$rs['id']]['key'] = $rs['key'];
				$params[$rs['id']][] = ['Layer_ID' => $rs['Layer_ID'], 'Name' => $rs['Name_or_alias']];
			}
		}
		return $params;
	}	

	function save_all_layer_params($formvars) {
		$sql = "TRUNCATE layer_parameter";
		$this->db->execSQL($sql);
		$sql = "INSERT INTO layer_parameter VALUES ";
		for ($i = 0; $i < count($formvars['key']); $i++) {
			if ($formvars['key'][$i] != '') {
				if ($formvars['id'][$i] == '') {
					$formvars['id'][$i] = 'NULL';
				}
				if ($komma) {
					$sql .= ",";
				}
				$sql .= "(
					" . $formvars['id'][$i] . ",
					'" . $formvars['key'][$i] . "',
					'" . $formvars['alias'][$i] . "',
					'" . $this->db->mysqli->real_escape_string($formvars['default_value'][$i]) . "',
					'" . $this->db->mysqli->real_escape_string($formvars['options_sql'][$i]) . "'
				)";
				$komma = true;
			}
		}
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			echo '<br>Fehler beim Speichern der Layerparameter mit SQL: ' . $sql;
		}
	}

	function get_all_layer_params_default_values() {
		$layer_params = array();
		$sql = "
			SELECT
				GROUP_CONCAT(concat('\"', `key`, '\":\"', `default_value`, '\"')) AS params
			FROM
				layer_parameter p
		";
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else {
			$rs = $this->db->result->fetch_assoc();
		}
		$params = $rs['params'];
		$params = str_replace('$USER_ID', $this->User_ID, $params);
		$params = str_replace('$STELLE_ID', $this->Stelle_ID, $params);
		return (array)json_decode('{' . $params . '}');
	}

  function get_stellen_from_layer($layer_id){
    $sql = "
			SELECT
				`ID`,
				`Bezeichnung`
			FROM
				`stelle`,
				`used_layer`
			WHERE
				`used_layer`.`Stelle_ID` = `stelle`.`ID` AND
				`used_layer`.`Layer_ID` = " . $layer_id . "
			ORDER BY
				`Bezeichnung`
		";
		#echo '<br>Sql: ' . $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_stellen_from_layer - Lesen der Stellen eines Layers:<br>" . $sql, 4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
    $stellen['ID'] = array();
    $stellen['Bezeichnung'] = array();
    while ($rs = $this->db->result->fetch_array()) {
      $stellen['ID'][] = $rs['ID'];
      $stellen['Bezeichnung'][] = $rs['Bezeichnung'];
    }
    return $stellen;
  }

  function get_layers_of_type($types, $order) {
		global $language;
		if($language != 'german') {
			$name_column = "
			CASE
				WHEN `Name_" . $language . "` != \"\" THEN `Name_" . $language . "`
				ELSE `Name`
			END AS Name";
		}
		else {
			$name_column = "Name";
		}
    $sql = "
			SELECT
				Layer_ID,
				" . $name_column . ",
				alias
			FROM
				layer
			WHERE
				connectiontype IN (" . $types . ")
			" . ($order != '' ? "ORDER BY " . replace_semicolon($order) : "") . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		while ($rs = $this->db->result->fetch_array()) {
			$layer['ID'][] = $rs['Layer_ID'];
			$layer['Bezeichnung'][] = $rs['Name'];
			$layer['alias'][] = $rs['alias'];
			$layer['Name_or_alias'][] = $rs[($rs['alias'] ? 'alias' : 'Name')];
		}
    if ($order == 'Bezeichnung'){
      // Sortieren der Layer unter Berücksichtigung von Umlauten
      $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
      $layer['Bezeichnung'] = $sorted_arrays['array'];
      $layer['ID'] = $sorted_arrays['second_array'];
    }
    return $layer;
  }

	function get_Layer($id, $replace_params = true) {
		$sql = "
			SELECT
				*
			FROM
				`layer`
			WHERE
				`Layer_ID` = " . $id ."
		";
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layer - Lesen eines Layers:<br>" . $sql, 4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$layer = $this->db->result->fetch_array();
		if ($replace_params) {
			foreach (array('classitem', 'classification', 'Data', 'pfad') AS $key) {
				$layer[$key] = replace_params_rolle(
					$layer[$key],
					['duplicate_criterion' => $layer['duplicate_criterion']]
				);
			}
		}
		return $layer;
	}

	function set_default_layer_privileges($formvars, $attributes){
		for ($i = 0; $i < count($attributes['type']); $i++) {
			if ($formvars['privileg_'.$attributes['name'][$i].'_'] == '') $formvars['privileg_'.$attributes['name'][$i].'_'] = 'NULL';
			$sql = "
				UPDATE
					`layer_attributes`
				SET
					`privileg` = " . $formvars['privileg_' . $attributes['name'][$i].'_'] . ",
					`query_tooltip` = " . ($formvars['tooltip_' . $attributes['name'][$i].'_'] == 'on' ? "1" : "0") ."
				WHERE
					`layer_id` = " . $formvars['selected_layer_id'] . " AND
					`name` = '" . $attributes['name'][$i] . "'
			";
			#echo '<br>Sql zum Speichern der Default-Layerrechte der Attribute: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern des Layerrechte zur Stelle:<br>" . $sql, 4);
			$ret = $this->db->execSQL($sql);
			if (!$this->db->success) { $this->debug->write("<br>Abbruch in " . $this->script_name . " Zeile: " . __LINE__, 4); return 0; }

			$sql = "
				UPDATE
					`layer`
				SET
					`privileg` = '" . $formvars['privileg'] . "',
					`export_privileg` = '" . $formvars['export_privileg'] . "'
				WHERE
					`Layer_ID` = " . $formvars['selected_layer_id'] . "
			";
			#echo '<br>Sql zur Speicherung der Default-Layerrechte: ' . $sql;
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern der Layerrechte zur Stelle:<br>" . $sql,4);
			$ret = $this->db->execSQL($sql);
			if (!$this->db->success) { $this->debug->write("<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__,4); return 0; }
		}
	}

	function get_layersfromgroup($group_id ) {
    $sql ='SELECT * FROM layer';
		if($group_id != '')$sql.=' WHERE Gruppe = '.$group_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layerfromgroup - Lesen der Layer einer Gruppe:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		while ($rs = $this->db->result->fetch_array()) {
			$layer['ID'][]=$rs['Layer_ID'];
			$layer['Bezeichnung'][]=$rs['Name'];
		}
    // Sortieren der Layer unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
    $layer['Bezeichnung'] = $sorted_arrays['array'];
    $layer['ID'] = $sorted_arrays['second_array'];
    return $layer;
  }

	function id_exists($tablename, $id) {
	  $layer = $this->get_Layer($id, false);
		if ($layer) {
		  return true;
		}
		else {
		  return false;
		}
	}

	function get_table_information($dbname, $tablename) {
		$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '" . $dbname."' AND table_name = '" . $tablename."'";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_table_information - Lesen der Metadaten der Tabelle " . $tablename." in db " . $dbname.":<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$metadata = $this->db->result->fetch_array();
		return $metadata;
	}

  function get_used_Layer($id) {
    $sql = '
			SELECT 
				l.oid, 
				ul.* 
			FROM 
				layer as l,
				used_layer as ul 
			WHERE 
				l.Layer_ID = ul.Layer_ID AND
				l.Layer_ID = '.$id.' AND 
				ul.Stelle_ID = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$layer = $this->db->result->fetch_array();
		return $layer;
  }

  function newGroup($groupname, $order){
    $sql = 'INSERT INTO u_groups (Gruppenname, `order`) VALUES ("'.$groupname.'", '.$order.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newGroup - Erstellen einer Gruppe:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
    return $this->db->mysqli->insert_id;
  }

  function get_Groups($layergruppen = NULL) {
		$this->groupset = $this->read_Groups(true, 'Gruppenname');
		if ($layergruppen == NULL) {	# alle abfragen
			$layergruppen['ID'] = array_unique(array_keys($this->groupset));
		}
		foreach ($layergruppen['ID'] as $groupid){
			$uppergroupnames = $this->list_uppergroups($groupid);
			$layergruppen['Bezeichnung'][] = implode('->', array_reverse($uppergroupnames));;
		}
		// Sortieren der Gruppen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layergruppen['Bezeichnung'], $layergruppen['ID']);
    $layergruppen['Bezeichnung'] = $sorted_arrays['array'];
    $layergruppen['ID'] = $sorted_arrays['second_array'];
		$layergruppen['selectable_for_shared_layers'] = array();
		foreach ($layergruppen['ID'] AS $group_id) {
			$layergruppen['selectable_for_shared_layers'][] = $this->groupset[$group_id]['selectable_for_shared_layers'];
		}
		return $layergruppen;
  }

	function list_uppergroups($groupid){
		if($groupid != ''){
			$lastgroupid = '';
			while($groupid != '' AND $lastgroupid != $groupid){
				$uppergroups[] = $this->groupset[$groupid]['Gruppenname'];
				$lastgroupid = $groupid;
				$groupid = $this->groupset[$groupid]['obergruppe'];
			}
			return $uppergroups;
		}
	}

	function getGroupbyName($groupname){
		$sql = "
			SELECT
				*
			FROM
				u_groups
			WHERE
				Gruppenname = '" . $groupname . "'
		";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getGroupbyName - Lesen einer Gruppe:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$rs = $this->db->result->fetch_array();
		return $rs;
	}
	
  function getClassFromObject($select, $layer_id, $classitem){
    # diese Funktion bestimmt für ein über die oid gegebenes Objekt welche Klasse dieses Objekt hat
    $classes = $this->read_Classes($layer_id);
    $anzahl = count($classes);
    if($anzahl == 1){
      return $classes[0]['Class_ID'];
    }
    else{
      for($i = 0; $i < $anzahl; $i++){
				if ($classes[$i]['Expression'] == '') {
          return $classes[$i]['Class_ID'];
        }
				$exp = mapserverExp2SQL($classes[$i]['Expression'], $classitem);
				
				$sql = 'SELECT * FROM ('.$select.") as foo WHERE (" . $exp.")";
        $this->debug->write("<p>file:kvwmap class:db_mapObj->getClassFromObject - Lesen einer Klasse eines Objektes:<br>" . $sql,4);
        $query=pg_query($sql);
    		if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
        $count=pg_num_rows($query);
        if($count > 0){
          return $classes[$i]['Class_ID'];
        }
      }
    }
  }

	function copyStyle($style_id){
		$columns = '
			symbol,
			symbolname,
			size,
			color,
			outlinecolor,
			colorrange,
			datarange,
			rangeitem,
			opacity,
			minsize,
			maxsize,
			minscale,
			maxscale,
			angle,
			angleitem,
			width,
			minwidth,
			maxwidth,
			offsetx,
			offsety,
			polaroffset,
			pattern,
			geomtransform,
			gap,
			initialgap,
			linecap,
			linejoin,
			linejoinmaxsize';
		$sql = '
			INSERT INTO styles 
				(' . $columns . ') 
			SELECT 
				' . $columns . ' 
			FROM 
				styles 
			WHERE 
				Style_ID = ' . $style_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyStyle - Kopieren eines Styles:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		return $this->db->mysqli->insert_id;
	}

	function copyLabel($label_id){
		$sql = "INSERT INTO labels (font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,anglemode,buffer,minfeaturesize,maxfeaturesize,partials,wrap,the_force) SELECT font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,anglemode,buffer,minfeaturesize,maxfeaturesize,partials,wrap,the_force FROM labels WHERE Label_ID = " . $label_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyLabel - Kopieren eines Labels:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		return $this->db->mysqli->insert_id;
	}

  function copyClass($class_id, $layer_id){
    # diese Funktion kopiert eine Klasse mit Styles und Labels und gibt die ID der neuen Klasse zurück
    $class = $this->read_ClassesbyClassid($class_id);
    $sql = "INSERT INTO classes (Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, Layer_ID,Expression,classification,drawingorder,legendorder,text) SELECT Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, " . $layer_id.",Expression,classification,drawingorder,legendorder,text FROM classes WHERE Class_ID = " . $class_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->copyClass - Kopieren einer Klasse:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
    $new_class_id = $this->db->mysqli->insert_id;
    for($i = 0; $i < count($class[0]['Style']); $i++){
      $new_style_id = $this->copyStyle($class[0]['Style'][$i]['Style_ID']);
      $this->addStyle2Class($new_class_id, $new_style_id, $class[0]['Style'][$i]['drawingorder']);
    }
    for($i = 0; $i < count($class[0]['Label']); $i++){
			$new_label_id = $this->copyLabel($class[0]['Label'][$i]['Label_ID']);
      $this->addLabel2Class($new_class_id, $new_label_id);
    }
    return $new_class_id;
  }

	function new_Class($classdata) {
		global $supportedLanguages;
		if (is_array($classdata)) {
			$attrib = $classdata; # Attributarray wurde übergeben
			if (value_of($attrib, 'legendimagewidth') == '') $attrib['legendimagewidth'] = 'NULL';
			if (value_of($attrib, 'legendimageheight') == '') $attrib['legendimageheight'] = 'NULL';
			if (value_of($attrib, 'legendorder') == '') $attrib['legendorder'] = 'NULL';
			# attrib:(Name, Layer_ID, Expression, classification, legendgraphic, legendimagewidth, legendimageheight, drawingorder, legendorder)
			$sql = 'INSERT INTO classes (Name, ';
			foreach ($supportedLanguages as $language) {
				if ($language != 'german') {
					$sql.= '`Name_'.$language.'`, ';
				}
			}
			$sql .= 'Layer_ID, Expression, classification, legendgraphic, legendimagewidth, legendimageheight, drawingorder, legendorder) VALUES (
				"' . $attrib['name'] . '",';
				foreach ($supportedLanguages as $language) {
					if ($language != 'german'){
						$sql .= '"' . $attrib['name_' . $language] . '",';
					}
				}
				$sql .= $attrib['layer_id'] . ",
					'" . $this->db->mysqli->real_escape_string($attrib['expression']) . "',
					'" . value_of($attrib, 'classification') . "',
					'" . value_of($attrib, 'legendgraphic') . "',
					" . $attrib['legendimagewidth'] . ",
					" . $attrib['legendimageheight'] . ",
					" . ($attrib['order'] == '' ? 'NULL' : $attrib['order']) . ",
					" . ($attrib['legendorder'] == '' ? 'NULL' : $attrib['legendorder']) . "
				)";
		}
		else {
			$class = $classdata; # Classobjekt wurde übergeben
			if (MAPSERVERVERSION > 500) {
				$expression = $class->getExpressionString();
			}
			else {
				$expression = $class->getExpression();
			}
			$sql  = "
				INSERT INTO classes (
					Name,
					Layer_ID,
					Expression,
					classification,
					drawingorder
				) VALUES (
					'" . $class->name . "',
					" . $class->layer_id . ",
					'" . $expression . "',
					'" . $class->classification . "',
					'" . $class->drawingorder . "'
				)
			";
		}
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->new_Class - Erstellen einer Klasse zu einem Layer:<br>" . $sql, 4);
		$ret = $this->db->execSQL($sql);
		if ($this->db->logfile != NULL) $this->db->logfile->write($sql . ';');
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		return $this->db->mysqli->insert_id;
	}

	function delete_Class($class_id) {
		$sql = "
			DELETE
				c, rc
			FROM
				classes c LEFT JOIN 
				u_rolle2used_class rc ON c.Class_ID = rc.class_id
			WHERE 
				c.Class_ID = " . $class_id . "
		";
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Class - Löschen einer Klasse:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name . " Zeile: " . __LINE__; return 0; }

		# Einträge in u_styles2classes und evtl. die Styles mitlöschen
		$styles = $this->read_Styles($class_id);
		for ($i = 0; $i < count($styles); $i++){
			$this->removeStyle2Class($class_id, $styles[$i]['style_id']);
			$other_classes = $this->get_classes2style($styles[$i]['style_id']);
			if ($other_classes == NULL) {
				$this->delete_Style($styles[$i]['style_id']);
			}
		}
		# Einträge in u_labels2classes und evtl. die Labels mitlöschen
		$labels = $this->read_Label($class_id);
		for ($i = 0; $i < count($labels); $i++){
			$this->removeLabel2Class($class_id, $labels[$i]['label_id']);
			$other_classes = $this->get_classes2label($labels[$i]['label_id']);
			if ($other_classes == NULL) {
				$this->delete_Label($labels[$i]['label_id']);
			}
		}
	}

	function update_Class($attrib) {
		global $supportedLanguages;
		if($attrib['legendimagewidth'] == '')$attrib['legendimagewidth'] = 'NULL';
		if($attrib['legendimageheight'] == '')$attrib['legendimageheight'] = 'NULL';
		if($attrib['order'] == '')$attrib['order'] = 'NULL';
		if($attrib['legendorder'] == '')$attrib['legendorder'] = 'NULL';
		$names = implode(
			', ',
			array_map(
				function($language) use ($attrib) {
					if($language != 'german')return "`Name_" . $language . "` = '" . $attrib['name_' . $language] . "'";
					else return "`Name` = '" . $attrib['name']."'";
				},
				$supportedLanguages
			)
		);

		$sql = '
			UPDATE
				classes
			SET
				`Class_ID` = ' . $attrib['new_class_id'] . ',
				'.$names.',
				`Layer_ID` = ' . $attrib['layer_id'] . ',
				`Expression` = "' . str_replace('\\', '\\\\', $attrib['expression']) . '",
				`text` = "' . $attrib['text'] . '",
				`classification` = "' . $attrib['classification'] . '",
				`legendgraphic`= "' . $attrib['legendgraphic'] . '",
				`legendimagewidth`= ' . $attrib['legendimagewidth'] . ',
				`legendimageheight`= ' . $attrib['legendimageheight'] . ',
				`drawingorder` = ' . $attrib['order'] . ',
				`legendorder` = '. $attrib['legendorder'] . '
			WHERE
				`Class_ID` = ' . $attrib['class_id'] . '
		';

		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->update_Class - Aktualisieren einer Klasse:<br>" . $sql,4);
		$ret = $this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
	}

  function new_Style($style){
		#echo '<p>style: ' . print_r($style, true);
    if(is_array($style)){
      $sql = "INSERT INTO styles SET ";
			if($style['colorred'] != ''){$sql.= "color = '" . $style['colorred']." " . $style['colorgreen']." " . $style['colorblue']."'";}
      else $sql.= "color = '" . $style['color']."'";
      if(value_of($style, 'symbol')){$sql.= ", symbol = '" . $style['symbol']."'";}
      if(value_of($style, 'symbolname')){$sql.= ", symbolname = '" . $style['symbolname']."'";}
      if(value_of($style, 'size')){$sql.= ", size = '" . $style['size']."'";}      
      if (value_of($style, 'outlinecolor')) {
				$sql.= ", outlinecolor = '" . $style['outlinecolor']."'";
			}
      if(value_of($style, 'outlinecolorred')){$sql.= ", outlinecolor = '" . $style['outlinecolorred']." " . $style['outlinecolorgreen']." " . $style['outlinecolorblue']."'";}
			if(value_of($style, 'colorrange')){$sql.= ", colorrange = '" . $style['colorrange']."'";}
			if(value_of($style, 'datarange')){$sql.= ", datarange = '" . $style['datarange']."'";}
			if(value_of($style, 'opacity')){$sql.= ", opacity = " . $style['opacity'];}
      if(value_of($style, 'minsize')){$sql.= ", minsize = '" . $style['minsize']."'";}
      if(value_of($style, 'maxsize')){$sql.= ", maxsize = '" . $style['maxsize']."'";}
      if(value_of($style, 'angle')){$sql.= ", angle = '" . $style['angle']."'";}
			if(value_of($style, 'angleitem')){$sql.= ", angleitem = '" . $style['angleitem']."'";}
      if(value_of($style, 'width')){$sql.= ", width = '" . $style['width']."'";}
      if(value_of($style, 'minwidth')){$sql.= ", minwidth = '" . $style['minwidth']."'";}
      if(value_of($style, 'maxwidth')){$sql.= ", maxwidth = '" . $style['maxwidth']."'";}
			if(value_of($style, 'offsetx')){$sql.= ", offsetx = '" . $style['offsetx'] . "'";}
			if(value_of($style, 'offsety')){$sql.= ", offsety = '" . $style['offsety'] . "'";}
			if(value_of($style, 'polaroffset')){$sql.= ", polaroffset = '" . $style['polaroffset']."'";}
			if(value_of($style, 'pattern')){$sql.= ", pattern = '" . $style['pattern']."'";}
			if(value_of($style, 'geomtransform')){$sql.= ", geomtransform = '" . $style['geomtransform']."'";}
			if(value_of($style, 'gap')){$sql.= ", gap = " . $style['gap'];}
			if(value_of($style, 'initialgap')){$sql.= ", initialgap = " . $style['initialgap'];}
			if(value_of($style, 'linecap')){$sql.= ", linecap = '" . $style['linecap']."'";}
			if(value_of($style, 'linejoin')){$sql.= ", linejoin = '" . $style['linejoin']."'";}
			if(value_of($style, 'linejoinmaxsize')){$sql.= ", linejoinmaxsize = " . $style['linejoinmaxsize'];}
    }
    else{
    # Styleobjekt wird übergeben
      $sql = "INSERT INTO styles SET ";
      $sql.= "symbol = '" . $style->symbol."', ";
      $sql.= "symbolname = '" . $style->symbolname."', ";
      $sql.= "size = '" . $style->size."', ";
      $sql.= "color = '" . $style->color->red." " . $style->color->green." " . $style->color->blue."', ";
      $sql.= "outlinecolor = '" . $style->outlinecolor->red." " . $style->outlinecolor->green." " . $style->outlinecolor->blue."', ";
      $sql.= "minsize = '" . $style->minsize."', ";
      $sql.= "maxsize = '" . $style->maxsize."'";
    }
    #echo '<p>SQL zum Anlegen eines Styles: '. $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
		if($this->db->logfile != NULL)$this->db->logfile->write($sql.';');
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    return $this->db->mysqli->insert_id;
  }

	function get_classes2style($style_id){
		$classes = array();
		$sql = 'SELECT class_id FROM u_styles2classes WHERE Style_ID = '.$style_id;
		#echo $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2style - Abfragen der Klassen, die einen Style benutzen:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
		while ($rs = $this->db->result->fetch_array()) {
			$classes[] = $rs[0];
		}
		return $classes;
	}

  function delete_Style($style_id){
    $sql = 'DELETE FROM styles WHERE Style_ID = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Style - Löschen eines Styles:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function moveup_Style($style_id, $class_id){
    $sql = "
			SELECT
				*
			FROM
				u_styles2classes
			WHERE
				class_id = " . $class_id . "
			ORDER BY
				drawingorder
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while ($rs = $this->db->result->fetch_array()) {
      $styles[$i] = $rs;
      if ($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = "
			UPDATE
				u_styles2classes
			SET
				drawingorder = " . $styles[$index]['drawingorder'] . "
			WHERE
				class_id = " . $class_id . "
				AND style_id = " . $styles[$index + 1]['style_id'] . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $sql = "
			UPDATE
				u_styles2classes
			SET
				drawingorder = " . $styles[$index + 1]['drawingorder'] . "
			WHERE
				class_id = " . $class_id . "
				AND style_id = " . $styles[$index]['style_id'] . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function movedown_Style($style_id, $class_id){
    $sql = "
			SELECT
				*
			FROM
				u_styles2classes
			WHERE
				class_id = " . $class_id . "
			ORDER BY
				drawingorder
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while ($rs = $this->db->result->fetch_array()) {
      $styles[$i] = $rs;
      if ($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = "
			UPDATE
				u_styles2classes
			SET
				drawingorder = " . $styles[$index]['drawingorder'] . "
			WHERE
				class_id = " . $class_id . "
				AND style_id = " . $styles[$index - 1]['style_id'] . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $sql = "
			UPDATE
				u_styles2classes
			SET
				drawingorder = " . $styles[$index - 1]['drawingorder'] . "
			WHERE
			  class_id = " . $class_id . "
				AND style_id = " . $styles[$index]['style_id'] . "
		";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function delete_Label($label_id){
    $sql = 'DELETE FROM labels WHERE Label_ID = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Label - Löschen eines Labels:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function addStyle2Class($class_id, $style_id, $drawingorder){
    if ($drawingorder == NULL){
      $sql = 'SELECT MAX(drawingorder) FROM u_styles2classes WHERE class_id = '.$class_id;
      $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class :<br>" . $sql,4);
      $this->db->execSQL($sql);
      if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
      $rs = $this->db->result->fetch_array();
      $drawingorder = $rs[0]+1;
    }
    $sql = 'INSERT INTO u_styles2classes VALUES ('.$class_id.', '.$style_id.', "'.$drawingorder.'")';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class - Hinzufügen eines Styles zu einer Klasse:<br>" . $sql,4);
    $this->db->execSQL($sql);
		if ($this->db->logfile != NULL) {
			$this->db->logfile->write($sql.';');
		}
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function removeStyle2Class($class_id, $style_id){
    $sql = 'DELETE FROM u_styles2classes WHERE class_id = '.$class_id.' AND style_id = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeStyle2Class - Löschen eines Styles:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function save_Style($formvars){
  	# wenn der Style nicht der Klasse zugeordnet ist, zuordnen
  	$classes = $this->get_classes2style($formvars["style_id"]);
  	if(!in_array($formvars["class_id"], $classes))$this->addStyle2Class($formvars["class_id"], $formvars["style_id"], NULL);
    $sql ="UPDATE styles SET ";
    if($formvars["style_symbol"]){$sql.="symbol = '" . $formvars["style_symbol"]."',";}else{$sql.="symbol = NULL,";}
    $sql.="symbolname = '" . $formvars["style_symbolname"]."',";
    if($formvars["style_size"] != ''){$sql.="size = '" . $formvars["style_size"]."',";}else{$sql.="size = NULL,";}
    if($formvars["style_color"] != ''){$sql.="color = '" . $formvars["style_color"]."',";}else{$sql.="color = NULL,";}
    if($formvars["style_outlinecolor"] != ''){$sql.="outlinecolor = '" . $formvars["style_outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
		if($formvars["style_colorrange"] != ''){$sql.="colorrange = '" . $formvars["style_colorrange"]."',";}else{$sql.="colorrange = NULL,";}
		if($formvars["style_datarange"] != ''){$sql.="datarange = '" . $formvars["style_datarange"]."',";}else{$sql.="datarange = NULL,";}
		if($formvars["style_rangeitem"] != ''){$sql.="rangeitem = '" . $formvars["style_rangeitem"]."',";}else{$sql.="rangeitem = NULL,";}
    if($formvars["style_minsize"] != ''){$sql.="minsize = '" . $formvars["style_minsize"]."',";}else{$sql.="minsize = NULL,";}
    if($formvars["style_maxsize"] != ''){$sql.="maxsize = '" . $formvars["style_maxsize"]."',";}else{$sql.="maxsize = NULL,";}
		if($formvars["style_minscale"] != ''){$sql.="minscale = '" . $formvars["style_minscale"]."',";}else{$sql.="minscale = NULL,";}
    if($formvars["style_maxscale"] != ''){$sql.="maxscale = '" . $formvars["style_maxscale"]."',";}else{$sql.="maxscale = NULL,";}
    if($formvars["style_angle"] != ''){$sql.="angle = '" . $formvars["style_angle"]."',";}else{$sql.="angle = NULL,";}
    $sql.="angleitem = '" . $formvars["style_angleitem"]."',";
    if($formvars["style_width"] != ''){$sql.="width = '" . $formvars["style_width"]."',";}else{$sql.="width = NULL,";}
    if($formvars["style_minwidth"] != ''){$sql.="minwidth = '" . $formvars["style_minwidth"]."',";}else{$sql.="minwidth = NULL,";}
    if($formvars["style_maxwidth"] != ''){$sql.="maxwidth = '" . $formvars["style_maxwidth"]."',";}else{$sql.="maxwidth = NULL,";}
    if($formvars["style_offsetx"] != ''){$sql.="offsetx = '" . $formvars["style_offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["style_offsety"] != ''){$sql.="offsety = '" . $formvars["style_offsety"]."',";}else{$sql.="offsety = NULL,";}
		if($formvars["style_polaroffset"] != ''){$sql.="polaroffset = '" . $formvars["style_polaroffset"]."',";}else{$sql.="polaroffset = NULL,";}
    if($formvars["style_pattern"] != ''){$sql.="pattern = '" . $formvars["style_pattern"]."',";}else{$sql.="pattern = NULL,";}
  	if($formvars["style_geomtransform"] != ''){$sql.="geomtransform = '" . $formvars["style_geomtransform"]."',";}else{$sql.="geomtransform = NULL,";}
		if($formvars["style_gap"] != ''){$sql.="gap = " . $formvars["style_gap"].",";}else{$sql.="gap = NULL,";}
		if($formvars["style_initialgap"] != ''){$sql.="initialgap = " . $formvars["style_initialgap"].",";}else{$sql.="initialgap = NULL,";}
		if($formvars["style_opacity"] != ''){$sql.="opacity = " . $formvars["style_opacity"].",";}else{$sql.="opacity = NULL,";}
		if($formvars["style_linecap"] != ''){$sql.="linecap = '" . $formvars["style_linecap"]."',";}else{$sql.="linecap = NULL,";}
		if($formvars["style_linejoin"] != ''){$sql.="linejoin = '" . $formvars["style_linejoin"]."',";}else{$sql.="linejoin = NULL,";}
		if($formvars["style_linejoinmaxsize"] != ''){$sql.="linejoinmaxsize = " . $formvars["style_linejoinmaxsize"].",";}else{$sql.="linejoinmaxsize = NULL,";}
    $sql.="Style_ID = " . $formvars["style_Style_ID"];
    $sql.=" WHERE Style_ID = " . $formvars["style_id"];
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Style - Speichern der Styledaten:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function get_Style($style_id){
  	if($style_id){
	    $sql ='SELECT * FROM styles AS s';
	    $sql.=' WHERE s.Style_ID = '.$style_id;
	    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Style - Lesen der Styledaten:<br>" . $sql,4);
	    $this->db->execSQL($sql);
	    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
	    $rs = $this->db->result->fetch_assoc();
	    return $rs;
  	}
  }

  function save_Label($formvars){
    $sql ="UPDATE labels SET ";
    if($formvars["label_font"]){$sql.="font = '" . $formvars["label_font"]."',";}
    if($formvars["label_type"]){$sql.="type = '" . $formvars["label_type"]."',";}
		if($formvars["label_type"]){$sql.="type = '".$formvars["label_type"]."',";}else{$sql.="type = NULL,";}
    if($formvars["label_color"]){$sql.="color = '" . $formvars["label_color"]."',";}
    if($formvars["label_outlinecolor"] != ''){$sql.="outlinecolor = '" . $formvars["label_outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
    if($formvars["label_shadowcolor"] != ''){$sql.="shadowcolor = '" . $formvars["label_shadowcolor"]."',";}else{$sql.="shadowcolor = NULL,";}
    if($formvars["label_shadowsizex"] != ''){$sql.="shadowsizex = '" . $formvars["label_shadowsizex"]."',";}else{$sql.="shadowsizex = NULL,";}
    if($formvars["label_shadowsizey"] != ''){$sql.="shadowsizey = '" . $formvars["label_shadowsizey"]."',";}else{$sql.="shadowsizey = NULL,";}
    if($formvars["label_backgroundcolor"] != ''){$sql.="backgroundcolor = '" . $formvars["label_backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["label_backgroundshadowcolor"] != ''){$sql.="backgroundshadowcolor = '" . $formvars["label_backgroundshadowcolor"]."',";}else{$sql.="backgroundshadowcolor = NULL,";}
    if($formvars["label_backgroundshadowsizex"] != ''){$sql.="backgroundshadowsizex = '" . $formvars["label_backgroundshadowsizex"]."',";}else{$sql.="backgroundshadowsizex = NULL,";}
    if($formvars["label_backgroundshadowsizey"] != ''){$sql.="backgroundshadowsizey = '" . $formvars["label_backgroundshadowsizey"]."',";}else{$sql.="backgroundshadowsizey = NULL,";}
    if($formvars["label_size"]){$sql.="size = '" . $formvars["label_size"]."',";}
    if($formvars["label_minsize"]){$sql.="minsize = '" . $formvars["label_minsize"]."',";}
    if($formvars["label_maxsize"]){$sql.="maxsize = '" . $formvars["label_maxsize"]."',";}
		if($formvars["label_minscale"]){$sql.="minscale = '" . $formvars["label_minscale"]."',";}
		if($formvars["label_maxscale"]){$sql.="maxscale = '" . $formvars["label_maxscale"]."',";}
    if($formvars["label_position"] != ''){$sql.="position = '" . $formvars["label_position"]."',";}
    if($formvars["label_offsetx"] != ''){$sql.="offsetx = '" . $formvars["label_offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["label_offsety"] != ''){$sql.="offsety = '" . $formvars["label_offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["label_angle"] != ''){$sql.="angle = '" . $formvars["label_angle"]."',";}else{$sql.="angle = NULL,";}
    if($formvars["label_anglemode"]){$sql.="anglemode = '" . $formvars["label_anglemode"]."',";}else $sql.="anglemode = NULL,";
    if($formvars["label_buffer"]){$sql.="buffer = '" . $formvars["label_buffer"]."',";}else $sql.="buffer = NULL,";
    if($formvars["label_minfeaturesize"]){$sql.="minfeaturesize = '" . $formvars["label_minfeaturesize"]."',";}else{$sql.="minfeaturesize = NULL,";}
    if($formvars["label_maxfeaturesize"]){$sql.="maxfeaturesize = '" . $formvars["label_maxfeaturesize"]."',";}else{$sql.="maxfeaturesize = NULL,";}
    if($formvars["label_partials"] != ''){$sql.="partials = '" . $formvars["label_partials"]."',";}else{$sql.="partials = NULL,";}
		if($formvars["label_maxlength"] != ''){$sql.="maxlength = '" . $formvars["label_maxlength"]."',";}else{$sql.="maxlength = NULL,";}
		if($formvars["label_repeatdistance"] != ''){$sql.="repeatdistance = '" . $formvars["label_repeatdistance"]."',";}else{$sql.="repeatdistance = NULL,";}
    if($formvars["label_wrap"] != ''){$sql.="wrap = '" . $formvars["label_wrap"]."',";}else{$sql .= "wrap = NULL,";}
		if($formvars["label_text"] != ''){$sql.="text = '" . $formvars["label_text"]."',";}else{$sql .= "text = NULL,";}
    if($formvars["label_the_force"] != ''){$sql.="the_force = '" . $formvars["label_the_force"]."',";}else{$sql.="the_force = NULL,";}
    $sql.="Label_ID = " . $formvars["label_Label_ID"];
    $sql.=" WHERE Label_ID = " . $formvars["label_id"];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Label - Speichern der Labeldaten:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function get_Label($label_id) {
    $sql ='SELECT * FROM labels AS l';
    $sql.=' WHERE l.Label_ID = '.$label_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Label - Lesen der Labeldaten:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $rs = $this->db->result->fetch_assoc();
    return $rs;
  }

  function new_Label($label){
  	if(is_array($label)){
  	$sql = "INSERT INTO labels SET ";
	    if($label['type']){$sql.= "type = '" . $label['type']."', ";}
	    if($label['font']){$sql.= "font = '" . $label['font']."', ";}
	    if($label['size']){$sql.= "size = '" . $label['size']."', ";}
	    if($label['color']){$sql.= "color = '" . $label['color']."', ";}
	    if($label['shadowcolor']){$sql.= "shadowcolor = '" . $label['shadowcolor']."', ";}
	    if($label['shadowsizex']){$sql.= "shadowsizex = '" . $label['shadowsizex']."', ";}
	    if($label['shadowsizey']){$sql.= "shadowsizey = '" . $label['shadowsizey']."', ";}
	    if($label['backgroundcolor']){$sql.= "backgroundcolor = '" . $label['backgroundcolor']."', ";}
	    if($label['backgroundshadowcolor']){$sql.= "backgroundshadowcolor = '" . $label['backgroundshadowcolor']."', ";}
	    if($label['backgroundshadowsizex']){$sql.= "backgroundshadowsizex = '" . $label['backgroundshadowsizex']."', ";}
	    if($label['backgroundshadowsizey']){$sql.= "backgroundshadowsizey = '" . $label['backgroundshadowsizey']."', ";}
	    if($label['outlinecolor']){$sql.= "outlinecolor = '" . $label['outlinecolor']."', ";}
	    if($label['position']){$sql.= "position = '" . $label['position']."', ";}
	    if($label['offsetx']){$sql.= "offsetx = '" . $label['offsetx']."', ";}
	    if($label['offsety']){$sql.= "offsety = '" . $label['offsety']."', ";}
	    if($label['angle']){$sql.= "angle = '" . $label['angle']."', ";}
	    if($label['anglemode']){$sql.= "anglemode = '" . $label['anglemode']."', ";}
	    if($label['buffer']){$sql.= "buffer = '" . $label['buffer']."', ";}
	    if($label['minfeaturesize']){$sql.= "minfeaturesize = '" . $label['minfeaturesize']."', ";}
	    if($label['maxfeaturesize']){$sql.= "maxfeaturesize = '" . $label['maxfeaturesize']."', ";}
	    if($label['partials']){$sql.= "partials = '" . $label['partials']."', ";}
	    if($label['wrap']){$sql.= "wrap = '" . $label['wrap']."', ";}
	    if($label['the_force']){$sql.= "the_force = '" . $label['the_force']."', ";}
	    if($label['minsize']){$sql.= "minsize = '" . $label['minsize']."', ";}
	    if($label['maxsize']){$sql.= "maxsize = '" . $label['maxsize']."'";}
  	}
  	else{
	    # labelobjekt wird übergeben
	    $sql = "INSERT INTO labels SET ";
	    if($label->type){$sql.= "type = '" . $label->type."', ";}
	    if($label->font){$sql.= "font = '" . $label->font."', ";}
	    if($label->size){$sql.= "size = '" . $label->size."', ";}
	    if($label->color){$sql.= "color = '" . $label->color->red." " . $label->color->green." " . $label->color->blue."', ";}
	    if($label->shadowcolor){$sql.= "shadowcolor = '" . $label->shadowcolor->red." " . $label->shadowcolor->green." " . $label->shadowcolor->blue."', ";}
	    if($label->shadowsizex){$sql.= "shadowsizex = '" . $label->shadowsizex."', ";}
	    if($label->shadowsizey){$sql.= "shadowsizey = '" . $label->shadowsizey."', ";}
	    if($label->backgroundcolor){$sql.= "backgroundcolor = '" . $label->backgroundcolor->red." " . $label->backgroundcolor->green." " . $label->backgroundcolor->blue."', ";}
	    if($label->backgroundshadowcolor){$sql.= "backgroundshadowcolor = '" . $label->backgroundshadowcolor->red." " . $label->backgroundshadowcolor->green." " . $label->backgroundshadowcolor->blue."', ";}
	    if($label->backgroundshadowsizex){$sql.= "backgroundshadowsizex = '" . $label->backgroundshadowsizex."', ";}
	    if($label->backgroundshadowsizey){$sql.= "backgroundshadowsizey = '" . $label->backgroundshadowsizey."', ";}
	    if($label->outlinecolor){$sql.= "outlinecolor = '" . $label->outlinecolor->red." " . $label->outlinecolor->green." " . $label->outlinecolor->blue."', ";}
	    if($label->position !== NULL){$sql.= "position = '" . $label->position."', ";}
	    if($label->offsetx){$sql.= "offsetx = '" . $label->offsetx."', ";}
	    if($label->offsety){$sql.= "offsety = '" . $label->offsety."', ";}
	    if($label->angle){$sql.= "angle = '" . $label->angle."', ";}
	    if($label->anglemode){$sql.= "anglemode = '" . $label->anglemode."', ";}
	    if($label->buffer){$sql.= "buffer = '" . $label->buffer."', ";}
	    if($label->minfeaturesize){$sql.= "minfeaturesize = '" . $label->minfeaturesize."', ";}
	    if($label->maxfeaturesize){$sql.= "maxfeaturesize = '" . $label->maxfeaturesize."', ";}
	    if($label->partials){$sql.= "partials = '" . $label->partials."', ";}
	    if($label->wrap){$sql.= "wrap = '" . $label->wrap."', ";}
	    if($label->the_force){$sql.= "the_force = '" . $label->the_force."', ";}
	    if($label->minsize){$sql.= "minsize = '" . $label->minsize."', ";}
	    if($label->maxsize){$sql.= "maxsize = '" . $label->maxsize."'";}
  	}
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    return $this->db->mysqli->insert_id;
  }

	function get_classes2label($label_id){
		$classes = array();
		$sql = 'SELECT class_id FROM u_labels2classes WHERE Label_ID = '.$label_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2label - Abfragen der Klassen, die ein Label benutzen:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    while ($rs = $this->db->result->fetch_array()) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function addLabel2Class($class_id, $label_id){
    $sql = 'INSERT INTO u_labels2classes VALUES ('.$class_id.', '.$label_id.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addLabel2Class - Hinzufügen eines Labels zu einer Klasse:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function removeLabel2Class($class_id, $label_id){
    $sql = 'DELETE FROM u_labels2classes WHERE class_id = '.$class_id.' AND label_id = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeLabels2Class - Löschen eines Labels:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
  }

  function getShapeByAttribute($layer,$attribut,$value) {
    $layer->queryByAttributes($attribut,$value,0);
    $result=$layer->getResult(0);
    if ($layer->getNumResults()==0) {
      return 0;
    }
    else {
      $layer->open();
      if(MAPSERVERVERSION > 500){
        $shape=$layer->getFeature($result->shapeindex,-1);
      }
      else{
        $shape=$layer->getShape(-1,$result->shapeindex);
      }
    }
    return $shape;
  }

  function getMaxMapExtent() {
    $sql ='SELECT MIN(minxmax) AS minxmax, MIN(minymax) AS minymax';
    $sql.=', MAX(maxxmax) AS maxxmax, MAX(maxymax) AS maxymax FROM stelle';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getMaxMapExtent - Lesen der Maximalen Kartenausdehnung:<br>" . $sql,4);
    $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    $rs = $this->db->result->fetch_array();
    return $rs;
  }
}

class Document {
  var $html;
  var $debug;
  var $head;
  var $headquery;

  ###################### Liste der Funktionen ####################################
  #
  # function Document() - Construktor
  # function load_heads()
  # function load_head($headid)
  # function save_head($formvars)
  # function update_head($formvars)
  # function save_active_head($id,$userid, $stelleid)
  # function get_active_headid($userid, $stelleid)
  # function get_head($userid, $stelleid)
  #
  ################################################################################

  function __construct($database){
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

	function delete_ausschnitt($stelle_id, $user_id, $id) {
		$sql = "
			DELETE FROM
				druckausschnitte
			WHERE
				stelle_id = " . $stelle_id . " AND
				user_id = " . $user_id . "
				" . ($id != '' ? " AND id = " . $id : '') . "
		";
		$this->debug->write("<p>file:kvwmap class:Document->delete_ausschnitt :",4);
		$this->database->execSQL($sql,4, 1);
	}

	function save_ausschnitt($stelle_id, $user_id, $name, $epsg_code, $center_x, $center_y, $print_scale, $angle, $frame_id){
		$sql = "
			INSERT INTO
				druckausschnitte
			SET
				id = COALESCE(
					(
						SELECT
							new_id
						FROM
						(
							SELECT
								max(id) + 1 AS new_id
							FROM
								druckausschnitte
							WHERE
								stelle_id = " . $stelle_id . " AND
								user_id = " . $user_id . "
						) as foo
					),
					1
				),
				stelle_id = " . $stelle_id . ",
				user_id = " . $user_id . ",
				name = '" . $name . "',
				epsg_code = '" . $epsg_code . "',
				center_x = " . $center_x . ",
				center_y = " . $center_y . ",
				print_scale = " . $print_scale . ",
				angle = " . $angle . ",
				frame_id = " . $frame_id . "
		";
		$this->debug->write("<p>file:kvwmap class:Document->save_ausschnitt :",4);
		$this->database->execSQL($sql,4, 1);
	}

  function load_ausschnitte($stelle_id, $user_id, $id){
    $sql = 'SELECT * FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->load_ausschnitte :<br>" . $sql,4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		while($rs = $this->database->result->fetch_assoc()){
      $ausschnitte[] = $rs;
    }
    return $ausschnitte;
  }

  function load_frames($stelle_id, $frameid, $return = '') {
		$frames = array();
    $sql = 'SELECT DISTINCT druckrahmen.* FROM druckrahmen';
    if($frameid AND !$stelle_id){$sql .= ' WHERE druckrahmen.id ='.$frameid;}
    if($stelle_id AND !$frameid){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    }
    if($frameid AND $stelle_id){
    	$sql.= ', druckrahmen2stelle WHERE druckrahmen2stelle.druckrahmen_id = druckrahmen.id';
    	$sql .= ' AND druckrahmen2stelle.stelle_id = '.$stelle_id;
    	$sql .= ' AND druckrahmen.id ='.$frameid;
    }
    $sql .= ' ORDER BY Name';
    #echo $sql.'<br>';
		$ret1 = $this->database->execSQL($sql, 4, 1);
  	if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $result = $this->database->result;
    while($rs = $result->fetch_assoc()){
			if ($return == 'only_ids') {
				$frames[] = $rs['id'];
			}
			else {
	      $frames[] = $rs;
	      $frames[0]['bilder'] = $this->load_bilder($rs['id']);
	      $frames[0]['texts'] = $this->load_texts($rs['id']);
			}
    }
    return $frames;
  }

  function load_texts($frame_id){
		$texts = array();
    $sql = 'SELECT druckfreitexte.* FROM druckrahmen, druckfreitexte, druckrahmen2freitexte';
    $sql.= ' WHERE druckrahmen2freitexte.druckrahmen_id = '.$frame_id;
    $sql.= ' AND druckrahmen2freitexte.druckrahmen_id = druckrahmen.id';
    $sql.= ' AND druckrahmen2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->load_texts :<br>" . $sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = $this->database->result->fetch_assoc()){
      $texts[] = $rs;
    }
    return $texts;
  }

  function load_bilder($frame_id){
		$bilder = array();
    $sql = 'SELECT b.src,r2b.posx,r2b.posy,r2b.width,r2b.height,r2b.angle';
    $sql.= ' FROM druckrahmen AS r, druckfreibilder AS b, druckrahmen2freibilder AS r2b';
    $sql.= ' WHERE r.id = r2b.druckrahmen_id';
    $sql.= ' AND b.id = r2b.freibild_id';
    $sql.= ' AND r.id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->load_bilder :<br>" . $sql,4);
		$ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = $this->database->result->fetch_assoc()){
      $bilder[] = $rs;
    }
    return $bilder;
  }

  function addfreetext($formvars){
    $sql = 'INSERT INTO druckfreitexte SET';
    $sql .= ' text = "",';
    $sql .= ' posx = 0,';
    $sql .= ' posy = 0,';
    $sql .= ' size = 0,';
    $sql .= ' font = "Helvetica.afm",'; // Ein Wert muss gesetzt werden, weil beim Layer-Export Null rauskommen würde und das darf für font nicht sein.
    $sql .= ' angle = 0';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = $this->database->mysqli->insert_id;
    $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$formvars['aktiverRahmen'].', '.$lastinsert_id.')';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removefreetext($formvars){
    $sql = 'DELETE FROM druckfreitexte WHERE id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $sql = 'DELETE FROM druckrahmen2freitexte WHERE freitext_id = '.$formvars['freitext_id'];
    $this->debug->write("<p>file:kvwmap class:Document->removefreetext :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_price($format){
    $sql ='SELECT preis FROM druckrahmen WHERE `format` = \''.$format.'\'';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->get_price :<br>" . $sql,4);
    $this->database->execSQL($sql,4, 1);
    $rs = $this->database->result->fetch_row();
    return $rs[0];
  }

  function delete_frame($selected_frame_id){
    $sql ="DELETE FROM druckrahmen WHERE id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
    $sql ="DELETE FROM druckrahmen2stelle WHERE druckrahmen_id = " . $selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_frame($formvars, $_files, $stelle_id){
    if($formvars['Name']){
      $frames = $this->load_frames($this->Stelle->id, NULL);
      for($i = 0; $i < count($frames); $i++){
        if($frames[$i]['Name'] == $formvars['Name']){
          $this->Document->fehlermeldung = 'Name schon vergeben';
        return;
        }
      }
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql = "INSERT INTO `druckrahmen`";
      $sql .= " SET `Name` = '" . $formvars['Name']."'";
			$sql .= ", `dhk_call` = '" . $formvars['dhk_call']."'";
      $sql .= ", `headposx` = " . $formvars['headposx'];
      $sql .= ", `headposy` = " . $formvars['headposy'];
      $sql .= ", `headwidth` = " . $formvars['headwidth'];
      $sql .= ", `headheight` = " . $formvars['headheight'];
      $sql .= ", `mapposx` = " . $formvars['mapposx'];
      $sql .= ", `mapposy` = " . $formvars['mapposy'];
      $sql .= ", `mapwidth` = " . $formvars['mapwidth'];
      $sql .= ", `mapheight` = " . $formvars['mapheight'];
      if($formvars['refmapposx'] != ''){$sql .= ", `refmapposx` = " . $formvars['refmapposx'];}
      if($formvars['refmapposy'] != ''){$sql .= ", `refmapposy` = " . $formvars['refmapposy'];}
      if($formvars['refmapwidth'] != ''){$sql .= ", `refmapwidth` = " . $formvars['refmapwidth'];}
      if($formvars['refmapheight'] != ''){$sql .= ", `refmapheight` = " . $formvars['refmapheight'];}
      if($formvars['refposx'] != ''){$sql .= ", `refposx` = " . $formvars['refposx'];}
      if($formvars['refposy'] != ''){$sql .= ", `refposy` = " . $formvars['refposy'];}
      if($formvars['refwidth'] != ''){$sql .= ", `refwidth` = " . $formvars['refwidth'];}
      if($formvars['refheight'] != ''){$sql .= ", `refheight` = " . $formvars['refheight'];}
      if($formvars['refzoom'] != ''){$sql .= ", `refzoom` = " . $formvars['refzoom'];}
      if($formvars['dateposx'] != ''){$sql .= ", `dateposx` = " . $formvars['dateposx'];}
      if($formvars['dateposy'] != ''){$sql .= ", `dateposy` = " . $formvars['dateposy'];}
      if($formvars['datesize'] != ''){$sql .= ", `datesize` = " . $formvars['datesize'];}
      if($formvars['scaleposx'] != ''){$sql .= ", `scaleposx` = " . $formvars['scaleposx'];}
      if($formvars['scaleposy'] != ''){$sql .= ", `scaleposy` = " . $formvars['scaleposy'];}
      if($formvars['scalesize'] != ''){$sql .= ", `scalesize` = " . $formvars['scalesize'];}
			if($formvars['scalebarposx'] != ''){$sql .= ", `scalebarposx` = " . $formvars['scalebarposx'];}
      if($formvars['scalebarposy'] != ''){$sql .= ", `scalebarposy` = " . $formvars['scalebarposy'];}
      if($formvars['oscaleposx'] != ''){$sql .= ", `oscaleposx` = " . $formvars['oscaleposx'];}
      if($formvars['oscaleposy'] != ''){$sql .= ", `oscaleposy` = " . $formvars['oscaleposy'];}
      if($formvars['oscalesize'] != ''){$sql .= ", `oscalesize` = " . $formvars['oscalesize'];}
			if($formvars['lageposx'] != ''){$sql .= ", `lageposx` = " . $formvars['lageposx'];}
      if($formvars['lageposy'] != ''){$sql .= ", `lageposy` = " . $formvars['lageposy'];}
      if($formvars['lagesize'] != ''){$sql .= ", `lagesize` = " . $formvars['lagesize'];}
			if($formvars['gemeindeposx'] != ''){$sql .= ", `gemeindeposx` = " . $formvars['gemeindeposx'];}
      if($formvars['gemeindeposy'] != ''){$sql .= ", `gemeindeposy` = " . $formvars['gemeindeposy'];}
      if($formvars['gemeindesize'] != ''){$sql .= ", `gemeindesize` = " . $formvars['gemeindesize'];}
      if($formvars['gemarkungposx'] != ''){$sql .= ", `gemarkungposx` = " . $formvars['gemarkungposx'];}
      if($formvars['gemarkungposy'] != ''){$sql .= ", `gemarkungposy` = " . $formvars['gemarkungposy'];}
      if($formvars['gemarkungsize'] != ''){$sql .= ", `gemarkungsize` = " . $formvars['gemarkungsize'];}
      if($formvars['flurposx'] != ''){$sql .= ", `flurposx` = " . $formvars['flurposx'];}
      if($formvars['flurposy'] != ''){$sql .= ", `flurposy` = " . $formvars['flurposy'];}
      if($formvars['flursize'] != ''){$sql .= ", `flursize` = " . $formvars['flursize'];}
			if($formvars['flurstposx'] != ''){$sql .= ", `flurstposx` = " . $formvars['flurstposx'];}
      if($formvars['flurstposy'] != ''){$sql .= ", `flurstposy` = " . $formvars['flurstposy'];}
      if($formvars['flurstsize'] != ''){$sql .= ", `flurstsize` = " . $formvars['flurstsize'];}
      if($formvars['legendposx'] != ''){$sql .= ", `legendposx` = " . $formvars['legendposx'];}
      if($formvars['legendposy'] != ''){$sql .= ", `legendposy` = " . $formvars['legendposy'];}
      if($formvars['legendsize'] != ''){$sql .= ", `legendsize` = " . $formvars['legendsize'];}
      if($formvars['arrowposx'] != ''){$sql .= ", `arrowposx` = " . $formvars['arrowposx'];}
      if($formvars['arrowposy'] != ''){$sql .= ", `arrowposy` = " . $formvars['arrowposy'];}
      if($formvars['arrowlength'] != ''){$sql .= ", `arrowlength` = " . $formvars['arrowlength'];}
      if($formvars['userposx'] != ''){$sql .= ", `userposx` = '" . $formvars['userposx']."'";}
      if($formvars['userposy'] != ''){$sql .= ", `userposy` = '" . $formvars['userposy']."'";}
      if($formvars['usersize'] != ''){$sql .= ", `usersize` = '" . $formvars['usersize']."'";}
      if($formvars['watermark'] != ''){$sql .= ", `watermark` = '" . $formvars['watermark']."'";}
      if($formvars['watermarkposx'] != ''){$sql .= ", `watermarkposx` = " . $formvars['watermarkposx'];}
      if($formvars['watermarkposy'] != ''){$sql .= ", `watermarkposy` = " . $formvars['watermarkposy'];}
      if($formvars['watermarksize'] != ''){$sql .= ", `watermarksize` = " . $formvars['watermarksize'];}
      if($formvars['watermarkangle'] != ''){$sql .= ", `watermarkangle` = " . $formvars['watermarkangle'];}
      if($formvars['watermarktransparency'] != ''){$sql .= ", `watermarktransparency` = '" . $formvars['watermarktransparency']."'";}
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = " . $formvars['variable_freetexts'];
      if($formvars['format']){$sql .= ", `format` = '" . $formvars['format']."'";}
      if($preis){$sql .= ", `preis` = '" . $preis."'";}
      if($formvars['font_date']){$sql .= ", `font_date` = '" . $formvars['font_date']."'";}
      if($formvars['font_scale']){$sql .= ", `font_scale` = '" . $formvars['font_scale']."'";}
			if($formvars['font_lage']){$sql .= ", `font_lage` = '" . $formvars['font_lage']."'";}
			if($formvars['font_gemeinde']){$sql .= ", `font_gemeinde` = '" . $formvars['font_gemeinde']."'";}
      if($formvars['font_gemarkung']){$sql .= ", `font_gemarkung` = '" . $formvars['font_gemarkung']."'";}
      if($formvars['font_flur']){$sql .= ", `font_flur` = '" . $formvars['font_flur']."'";}
			if($formvars['font_flurst']){$sql .= ", `font_flurst` = '" . $formvars['font_flurst']."'";}
      if($formvars['font_legend']){$sql .= ", `font_legend` = '" . $formvars['font_legend']."'";}
      if($formvars['font_user']){$sql .= ", `font_user` = '" . $formvars['font_user']."'";}
      if($formvars['font_watermark']){$sql .= ", `font_watermark` = '" . $formvars['font_watermark']."'";}

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '" . $_files['headsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `headsrc` = '" . $formvars['headsrc_save']."'";
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '" . $_files['refmapsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapsrc` = '" . $formvars['refmapsrc_save']."'";
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '" . $_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapfile` = '" . $formvars['refmapfile_save']."'";
      }
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);
      $lastdruckrahmen_id = $this->database->mysqli->insert_id;

      $sql = 'INSERT INTO druckrahmen2stelle (stelle_id, druckrahmen_id) VALUES('.$stelle_id.', '.$lastdruckrahmen_id.')';
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "INSERT INTO druckfreitexte SET `text` = '" . $formvars['text'.$i]."'";
        $sql .= ", `posx` = " . $formvars['textposx'.$i];
        $sql .= ", `posy` = " . $formvars['textposy'.$i];
        $sql .= ", `size` = " . $formvars['textsize'.$i];
        $sql .= ", `angle` = " . $formvars['textangle'.$i];
        $sql .= ", `font` = '" . $formvars['textfont'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = $this->database->mysqli->insert_id;

        $sql = 'INSERT INTO druckrahmen2freitexte (druckrahmen_id, freitext_id) VALUES('.$lastdruckrahmen_id.', '.$lastfreitext_id.')';
        $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
    return $lastdruckrahmen_id;
  }

  function update_frame($formvars, $_files){
    if($formvars['Name']){
      $formvars['cent'] = str_pad ($formvars['cent'], 2, "0", STR_PAD_RIGHT);
      $preis = $formvars['euro'] * 100 + $formvars['cent'];

      $sql ="UPDATE `druckrahmen`";
      $sql .= " SET `Name` = '" . $formvars['Name']."'";
			$sql .= ", `dhk_call` = '" . $formvars['dhk_call']."'";
      $sql .= ", `headposx` = '" . $formvars['headposx']."'";
      $sql .= ", `headposy` = '" . $formvars['headposy']."'";
      $sql .= ", `headwidth` = '" . $formvars['headwidth']."'";
      $sql .= ", `headheight` = '" . $formvars['headheight']."'";
      $sql .= ", `mapposx` = '" . $formvars['mapposx']."'";
      $sql .= ", `mapposy` = '" . $formvars['mapposy']."'";
      $sql .= ", `mapwidth` = '" . $formvars['mapwidth']."'";
      $sql .= ", `mapheight` = '" . $formvars['mapheight']."'";
      $sql .= ", `refmapposx` = '" . $formvars['refmapposx']."'";
      $sql .= ", `refmapposy` = '" . $formvars['refmapposy']."'";
      $sql .= ", `refmapwidth` = '" . $formvars['refmapwidth']."'";
      $sql .= ", `refmapheight` = '" . $formvars['refmapheight']."'";
      $sql .= ", `refposx` = '" . $formvars['refposx']."'";
      $sql .= ", `refposy` = '" . $formvars['refposy']."'";
      $sql .= ", `refwidth` = '" . $formvars['refwidth']."'";
      $sql .= ", `refheight` = '" . $formvars['refheight']."'";
      $sql .= ", `refzoom` = '" . $formvars['refzoom']."'";
      $sql .= ", `dateposx` = '" . $formvars['dateposx']."'";
      $sql .= ", `dateposy` = '" . $formvars['dateposy']."'";
      $sql .= ", `datesize` = '" . $formvars['datesize']."'";
      $sql .= ", `scaleposx` = '" . $formvars['scaleposx']."'";
      $sql .= ", `scaleposy` = '" . $formvars['scaleposy']."'";
      $sql .= ", `scalesize` = '" . $formvars['scalesize']."'";
			$sql .= ", `scalebarposx` = '" . $formvars['scalebarposx']."'";
      $sql .= ", `scalebarposy` = '" . $formvars['scalebarposy']."'";
      $sql .= ", `oscaleposx` = " . ($formvars['oscaleposx'] ?: 'NULL');
      $sql .= ", `oscaleposy` = " . ($formvars['oscaleposy'] ?: 'NULL');
      $sql .= ", `oscalesize` = " . ($formvars['oscalesize'] ?: 'NULL');
			$sql .= ", `lageposx` = '" . $formvars['lageposx']."'";
      $sql .= ", `lageposy` = '" . $formvars['lageposy']."'";
      $sql .= ", `lagesize` = '" . $formvars['lagesize']."'";
			$sql .= ", `gemeindeposx` = '" . $formvars['gemeindeposx']."'";
      $sql .= ", `gemeindeposy` = '" . $formvars['gemeindeposy']."'";
      $sql .= ", `gemeindesize` = '" . $formvars['gemeindesize']."'";
      $sql .= ", `gemarkungposx` = '" . $formvars['gemarkungposx']."'";
      $sql .= ", `gemarkungposy` = '" . $formvars['gemarkungposy']."'";
      $sql .= ", `gemarkungsize` = '" . $formvars['gemarkungsize']."'";
      $sql .= ", `flurposx` = '" . $formvars['flurposx']."'";
      $sql .= ", `flurposy` = '" . $formvars['flurposy']."'";
      $sql .= ", `flursize` = '" . $formvars['flursize']."'";
			$sql .= ", `flurstposx` = '" . $formvars['flurstposx']."'";
      $sql .= ", `flurstposy` = '" . $formvars['flurstposy']."'";
      $sql .= ", `flurstsize` = '" . $formvars['flurstsize']."'";
      $sql .= ", `legendposx` = '" . $formvars['legendposx']."'";
      $sql .= ", `legendposy` = '" . $formvars['legendposy']."'";
      $sql .= ", `legendsize` = '" . $formvars['legendsize']."'";
      $sql .= ", `arrowposx` = '" . $formvars['arrowposx']."'";
      $sql .= ", `arrowposy` = '" . $formvars['arrowposy']."'";
      $sql .= ", `arrowlength` = '" . $formvars['arrowlength']."'";
      $sql .= ", `userposx` = '" . $formvars['userposx']."'";
      $sql .= ", `userposy` = '" . $formvars['userposy']."'";
      $sql .= ", `usersize` = '" . $formvars['usersize']."'";
      $sql .= ", `watermark` = '" . $formvars['watermark']."'";
      $sql .= ", `watermarkposx` = '" . $formvars['watermarkposx']."'";
      $sql .= ", `watermarkposy` = '" . $formvars['watermarkposy']."'";
      $sql .= ", `watermarksize` = '" . $formvars['watermarksize']."'";
      $sql .= ", `watermarkangle` = '" . $formvars['watermarkangle']."'";
      $sql .= ", `watermarktransparency` = '" . $formvars['watermarktransparency']."'";
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = " . $formvars['variable_freetexts'];
      $sql .= ", `format` = '" . $formvars['format']."'";
      $sql .= ", `preis` = '" . $preis."'";
      $sql .= ", `font_date` = '" . $formvars['font_date']."'";
      $sql .= ", `font_scale` = '" . $formvars['font_scale']."'";
			$sql .= ", `font_lage` = '" . $formvars['font_lage']."'";
			$sql .= ", `font_gemeinde` = '" . $formvars['font_gemeinde']."'";
      $sql .= ", `font_gemarkung` = '" . $formvars['font_gemarkung']."'";
      $sql .= ", `font_flur` = '" . $formvars['font_flur']."'";
			$sql .= ", `font_flurst` = '" . $formvars['font_flurst']."'";
      $sql .= ", `font_legend` = '" . $formvars['font_legend']."'";
      $sql .= ", `font_user` = '" . $formvars['font_user']."'";
      $sql .= ", `font_watermark` = '" . $formvars['font_watermark']."'";

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '" . $_files['headsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '" . $_files['refmapsrc']['name']."'";
          #echo $sql;
        }
        else {
            //echo '<br>Datei: '.$_files['Wappen']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
          }
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '" . $_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      $sql .= " WHERE `id` =".(int)$formvars['aktiverRahmen'];
      $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "UPDATE druckfreitexte SET `text` = '" . $formvars['text'.$i]."'";
        $sql .= ", `posx` = " . $formvars['textposx'.$i];
        $sql .= ", `posy` = " . $formvars['textposy'.$i];
        $sql .= ", `size` = " . $formvars['textsize'.$i];
        $sql .= ", `angle` = " . $formvars['textangle'.$i];
        $sql .= ", `font` = '" . $formvars['textfont'.$i]."'";
        $sql .= " WHERE id = " . $formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
  }

  function add_frame2stelle($id, $stelleid){
    $sql ="INSERT IGNORE INTO druckrahmen2stelle VALUES (" . $stelleid.", " . $id.")";
    $this->debug->write("<p>file:kvwmap class:Document->add_frame2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removeFrames($stelleid){
    $sql ="DELETE FROM druckrahmen2stelle WHERE stelle_id = " . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->removeFrames :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_active_frame($id, $userid, $stelleid){
    $sql ="UPDATE `rolle` SET `active_frame` = '" . $id."' WHERE `user_id` =" . $userid." AND `stelle_id` =" . $stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->save_active_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_active_frameid($userid, $stelleid){
    $sql ='SELECT active_frame from rolle WHERE `user_id` ='.$userid.' AND `stelle_id` ='.$stelleid;
    $this->debug->write("<p>file:kvwmap class:GUI->get_active_frameid :<br>" . $sql,4);
    $this->database->execSQL($sql,4, 1);
		$rs = $this->database->result->fetch_row();
    return $rs[0];
  }
}

class point {
  var $x;
  var $y;

  function __construct($x,$y) {
    $this->x=$x;
    $this->y=$y;
  }

  function pixel2welt($minX,$minY,$pixSize) {
    # Rechnet Pixel- in Weltkoordinaten um mit minx, miny und pixsize
    $this->x=($this->x*$pixSize)+$minX;
    $this->y=($this->y*$pixSize)+$minY;
  }

  function welt2pixel($minX,$minY,$pixSize) {
    # Rechnet Welt- in Pixelkoordinaten um mit minx, miny und pixsize
    $this->x=round(($this->x-$minX)/$pixSize);
    $this->y=round(($this->y-$minY)/$pixSize);
  }
}

?>
