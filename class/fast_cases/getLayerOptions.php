<?
function sanitize(&$value, $type) {
	switch ($type) {
		case 'int' : {
			$value = (int) $value;
		} break;
		case 'text' : {
			$value = pg_escape_string($value);
		} break;
		default : {
			// let $value as it is
		}
	}
	return $value;
}

# ohne Fehlermeldung fehlen die Funktionen
# sql_err_msg aus funktionen/allg_funktionen.php
# add_message in Class GUI aus kvwmap.php

function sql_err_msg($title, $sql, $msg, $div_id) {
	$err_msg = "
		<div style=\"text-align: left;\">" .
		$title . "<br>" .
		$msg . "
		<div style=\"text-align: center\">
			<a href=\"#\" onclick=\"debug_t = this; $('#error_details_" . $div_id . "').toggle(); $(this).children().toggleClass('fa-caret-down fa-caret-up')\"><i class=\"fa fa-caret-down\" aria-hidden=\"true\"></i></a>
		</div>
		<div id=\"error_details_" . $div_id . "\" style=\"display: none\">
			Aufgetreten bei SQL-Anweisung:<br>
			<textarea id=\"sql_statement_" . $div_id . "\" class=\"sql-statement\" type=\"text\" style=\"height: " . round(strlen($sql) / 2) . "px; max-height: 600px\">
				" . $sql . "
			</textarea><br>
			<button type=\"button\" onclick=\"
				copyText = document.getElementById('sql_statement_" . $div_id . "');
				copyText.select();
				document.execCommand('copy');
			\">In Zwischenablage kopieren</button>
		</div>
	</div>";
	return $err_msg;
}

function replace_params($str, $params, $user_id = NULL, $stelle_id = NULL, $hist_timestamp = NULL, $language = NULL, $duplicate_criterion = NULL, $scale = NULL) {
	if (is_array($params)) {
		foreach($params AS $key => $value){
			$str = str_replace('$'.$key, $value, $str);
		}
	}
	if (!is_null($user_id))							$str = str_replace('$user_id', $user_id, $str);
	if (!is_null($stelle_id))						$str = str_replace('$stelle_id', $stelle_id, $str);
	if (!is_null($hist_timestamp))			$str = str_replace('$hist_timestamp', $hist_timestamp, $str);
	if (!is_null($language))						$str = str_replace('$language', $language, $str);
	if (!is_null($duplicate_criterion))	$str = str_replace('$duplicate_criterion', $duplicate_criterion, $str);
	if (!is_null($scale))								$str = str_replace('$scale', $scale, $str);
	return $str;
}

function get_first_word_after($str, $word, $delim1 = ' ', $delim2 = ' ', $last = false){
	if($last)$word_pos = strripos($str, $word);
	else $word_pos = stripos($str, $word);
	if($word_pos !== false){
		$str_from_word_pos = substr($str, $word_pos+strlen($word));
		$parts = explode($delim2, trim($str_from_word_pos, $delim1));
		return $parts[0];
	}
}

function pg_quote($column){
	return ctype_lower($column) ? $column : '"'.$column.'"';
}

function replace_semicolon($text) {
	return str_replace(';', '', $text);
}

function value_of($array, $key) {
	if(!is_array($array))$array = array();
	return (array_key_exists($key, $array) ? $array[$key] :	'');
}

function umlaute_umwandeln($name) {
	$name = str_replace('ä', 'ae', $name);
	$name = str_replace('ü', 'ue', $name);
	$name = str_replace('ö', 'oe', $name);
	$name = str_replace('Ä', 'Ae', $name);
	$name = str_replace('Ü', 'Ue', $name);
	$name = str_replace('Ö', 'Oe', $name);
	$name = str_replace('a?', 'ae', $name);
	$name = str_replace('u?', 'ue', $name);
	$name = str_replace('o?', 'oe', $name);
	$name = str_replace('A?', 'ae', $name);
	$name = str_replace('U?', 'ue', $name);
	$name = str_replace('O?', 'oe', $name);
	$name = str_replace('ß', 'ss', $name);
	$name = str_replace('.', '', $name);
	$name = str_replace(':', '', $name);
	$name = str_replace('(', '', $name);
	$name = str_replace(')', '', $name);
	$name = str_replace('/', '-', $name);
	$name = str_replace(' ', '_', $name);
	$name = str_replace('-', '_', $name);
	$name = str_replace('?', '_', $name);
	$name = str_replace('+', '_', $name);
	$name = str_replace(',', '_', $name);
	$name = str_replace('*', '_', $name);
	$name = str_replace('$', '', $name);
	$name = str_replace('&', '_', $name);
	$name = iconv("UTF-8", "UTF-8//IGNORE", $name);
	return $name;
}

class GUI {

  var $alert;
  var $gui;
  var $layout;
  var $style;
  var $mime_type;
  var $menue;
  var $pdf;
  var $addressliste;
  var $debug;
  var $mysqli;
  var $flst;
  var $formvars;
  var $legende;
  var $map;
  var $mapDB;
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
  var $map_factor;
  var $formatter;
  var $success;
  var $login_failed;
  var $only_main;
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
	var $notices;
	static $messages = array();

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
	}

	function sanitize($vars) {
		foreach ($vars as $name => $type) {
			sanitize($this->formvars[$name], $type);
		}
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
				GUI::add_message($m['type'], $m['msg']);
			}
		}
		else {
			GUI::$messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }
	
	function get_layer_params_form($stelle_id = NULL, $layer_id = NULL){
		include_once(CLASSPATH.'FormObject.php');
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
			$selectable_layer_params = $stelle->selectable_layer_params;
		}
		else{		# Parameter abfragen, die nur dieser Layer hat
			$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$selectable_layer_params = implode(', ', array_keys($mapDB->get_layer_params_layer(NULL, $layer_id)));
			$rolle = $this->user->rolle;
		}
		$params = $rolle->get_layer_params($selectable_layer_params, $this->pgdatabase);
		if ($params['error_message'] != '') {
			$this->add_message('error', $params['error_message']);
		}
		else {
			if (!empty($params)) {
				if($layer_id == NULL){
					echo '
						<table style="border: 1px solid #ccc" class="rollenwahl-table" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="2" class="rollenwahl-gruppen-header"><span class="fett">'.$this->strLayerParameters.'</span></td>
							</tr>
							<tr>
								<td class="rollenwahl-option-data">
									<table>';
				}
									foreach($params AS $param) {
										echo '
										<tr id="layer_parameter_'.$param['key'].'_tr">
											<td valign="top" class="rollenwahl-option-header">
												<span>'.$param['alias'].':</span>
											</td>
											<td>
												'.FormObject::createSelectField(
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
												).'
											</td>
										</tr>';
									}
				if($layer_id == NULL){
					echo'	</table>
							</td>
						</tr>
					</table>
					';
				}
			}
		}
	}

	function getLayerOptions() {
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
			$attributes = $mapDB->getDataAttributes($layerdb, $this->formvars['layer_id'], false);
			$query_attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL);
			$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
		}
		$disabled_classes = $mapDB->read_disabled_classes();
		$layer[0]['Class'] = $mapDB->read_Classes($this->formvars['layer_id'], $disabled_classes, false, $layer[0]['classification']);
		echo '
		<div class="layerOptions" id="options_content_'.$this->formvars['layer_id'].'">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0);" onclick="closeLayerOptions(' . $this->formvars['layer_id'] . ')" title="Schlie&szlig;en"><img style="border:none" src="' . GRAPHICSPATH . 'exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding-bottom: 8px">
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
										onchange="$('#shared_layer_table_name').val(umlaute_umwandeln(this.value.toLowerCase()));"
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
													onchange="$(\'#shared_layer_schema_name\').val(umlaute_umwandeln($(this).next().text().toLowerCase()));"
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
											<input id="shared_layer_table_name" type="text" name="shared_layer_table_name" value="<? echo umlaute_umwandeln(strtolower($layer[0]['Name'])); ?>" style="width: 181px;">
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
							echo '<li><a href="javascript:void();" onclick="zoomToMaxLayerExtent(' . $this->formvars['layer_id'] . ')">' . ucfirst($this->FullLayerExtent) . '</a></li>';
						}
						if(in_array($layer[0]['connectiontype'], [MS_POSTGIS, MS_WFS]) AND $layer[0]['queryable']){
							echo '<li><a href="index.php?go=Layer-Suche&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strSearch . '</a></li>';
						}
						if ($layer[0]['queryable'] AND $layer[0]['privileg'] > 0 AND $layer[0]['privilegfk'] !== '0') {
							echo '<li><a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->newDataset . '</a></li>';
							if ($this->user->layer_data_import_allowed) {
								echo '<li><a href="index.php?go=Daten_Import&chosen_layer_id=' . $this->formvars['layer_id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $this->strDataImport . '</a></li>';
							}
						}
						if ($layer[0]['Class'][0]['Name'] != '') {
							if ($layer[0]['showclasses'] != '') {
								echo '<li><a href="javascript:void();" onclick="getlegend(\'' . $layer[0]['Gruppe'] . '\', ' . $this->formvars['layer_id'] . ', document.GUI.nurFremdeLayer.value);closeLayerOptions(' . $this->formvars['layer_id'] . ')">';
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
						$this->get_layer_params_form(NULL, $this->formvars['layer_id']);
						if ($layer[0]['connectiontype'] == 6) {
							if ($this->formvars['layer_id'] < 0 OR $layer[0]['original_labelitem'] != '') {
								# für Rollenlayer oder normale Layer mit labelitem
								echo '<tr>
												<td>
													<span>' . $this->label . ':</span>
												</td>
												<td>
													<select style="width: 110px" name="layer_options_labelitem">
														<option value=""> - '.$this->noLabel.' - </option>';
														if ($this->formvars['layer_id'] > 0) {
															echo '<option value="'.$layer[0]['original_labelitem'].'" '.($layer[0]['labelitem'] == $layer[0]['original_labelitem'] ? 'selected' : '').'>'.($query_attributes['alias'][$layer[0]['original_labelitem']] != ''? $query_attributes['alias'][$layer[0]['original_labelitem']] : $layer[0]['original_labelitem']).'</option>';
														}
														for($i = 0; $i < count($attributes)-2; $i++){
															if(
																	(	$this->formvars['layer_id'] < 0 		# entweder Rollenlayer oder
																		OR 
																		(
																			$privileges[$attributes[$i]['name']] != '' 									# mind. Leserecht
																			AND 
																			!in_array($attributes[$i]['name'], [$layer[0]['original_labelitem'], 'oid'])	# und Attribut ist nicht das Originallabelitem oder die oid
																		)
																	)
																	AND 
																	$attributes['the_geom'] != $attributes[$i]['name']		# Attribut ist nicht das Geometrieattribut
																) {
																	echo '<option value="'.$attributes[$i]['name'].'" '.($layer[0]['labelitem'] == $attributes[$i]['name'] ? 'selected' : '').'>'.($query_attributes['alias'][$attributes[$i]['name']] != ''? $query_attributes['alias'][$attributes[$i]['name']] : $attributes[$i]['name']).'</option>';
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
						}
						echo '<tr>
										<td>
											<span>' . $this->transparency . ':</span>
										</td>
										<td>
											<input name="layer_options_transparency" onchange="transparency_slider.value=parseInt(layer_options_transparency.value);" style="width: 30px" value="'.$layer[0]['transparency'].'"><input type="range" id="transparency_slider" name="transparency_slider" style="height: 6px; width: 120px" value="'.$layer[0]['transparency'].'" onchange="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()" oninput="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()">
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
											for ($i = 0; $i < @count($attributes)-2; $i++) {
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
		</div>
		█
		legend_top = document.getElementById(\'legenddiv\').getBoundingClientRect().top;
		legend_bottom = document.getElementById(\'legenddiv\').getBoundingClientRect().bottom;
		posy = document.getElementById(\'options_'.$this->formvars['layer_id'].'\').getBoundingClientRect().top;
		options_height = document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').getBoundingClientRect().height;
		if(posy > legend_bottom - options_height)posy = legend_bottom - options_height;
		document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').style.top = document.getElementById(\'map\').offsetTop + posy - legend_top;
		';
	}
}

class database {

  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $logfile;
  var $commentsign;
  var $blocktransaction;
  var $success;
  var $errormessage;

  function __construct() {
    global $debug;
		global $GUI;
		$this->gui = $GUI;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_mysql;
    $this->logfile=$log_mysql;
 		$this->defaultlogfile=$log_mysql;
    $this->ist_Fortfuehrung=1;
    $this->type="MySQL";
    $this->commentsign='#';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # BEGIN TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
    # Anweisungen nicht in Transactionsblöcken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
  }

	function open() {
		$this->debug->write("<br>MySQL Verbindung öffnen mit Host: " . $this->host . " User: " . $this->user . " Datenbbank: " . $this->dbName, 4);
		$this->mysqli = mysqli_init();
		$ret = $this->mysqli->real_connect($this->host, $this->user, $this->passwd, $this->dbName, 3306, null, MYSQLI_CLIENT_FOUND_ROWS);
	  $this->debug->write("<br>MySQL VerbindungsID: " . $this->mysqli->thread_id, 4);
		$this->debug->write("<br>MySQL Fehlernummer: " . mysqli_connect_errno(), 4);
		$this->debug->write("<br>MySQL Fehler: " . mysqli_connect_error(), 4);
		return $ret;
	}
	
  function read_colors(){	
  	$sql = "SELECT * FROM colors";
  	#echo $sql;
  	$this->execSQL($sql, 4, 0);
    if (!$this->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs = $this->result->fetch_assoc()) {
      $colors[] = $rs;
    }
    return $colors;
  }

	function execSQL($sql, $debuglevel = 4, $loglevel = 0, $suppress_error_msg = false) {
		switch ($this->loglevel) {
			case 0 : {
				$logsql=0;
			} break;
			case 1 : {
				$logsql=1;
			} break;
			case 2 : {
				$logsql=$loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
			#echo '<br>sql in execSQL: ' . $sql;
			if ($result = $this->mysqli->query($sql)) {
				$ret[0] = 0;
				$ret['success'] = $this->success = true;
				$ret[1] = $ret['query'] = $ret['result'] = $this->result = $result;
				$this->errormessage = '';
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
				$this->debug->write(date('H:i:s')."<br>" . $sql, $debuglevel);
			}
			else {
				$ret[0] = 1;
				$ret['success'] = $this->success = false;
				$div_id = rand(1, 99999);
				$errormessage = $this->mysqli->error;
				$ret[1] = $this->errormessage = sql_err_msg('MySQL', $sql, $errormessage, $div_id);
				if ($logsql) {
					$this->logfile->write("#" . $errormessage);
				}
				if (!$suppress_error_msg) {
					if (gettype($this->gui) == 'object') {
						$this->gui->add_message('error', $this->errormessage);
					}
					else {
						echo '<br>error: ' . $this->errormessage;
					}
				}
			}
			$ret[2] = $sql;
		}
		else {
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}
		return $ret;
	}

	function close() {
		$this->debug->write("<br>MySQL Verbindung ID: " . $this->mysqli->thread_id . " schließen.", 4);
		if (LOG_LEVEL > 0) {
			$this->logfile->close();
		}
		return $this->mysqli->close();
	}
}

class user {

  var $id;
  var $Name;
  var $Vorname;
  var $login_name;
  var $funktion;
  var $dbConn;
  var $Stellen;
  var $nZoomFactor;
  var $nImageWidth;
  var $nImageHeight;
  var $database;
  var $remote_addr;

	function __construct($login_name, $id, $database, $passwort = '') {
		global $debug;
		$this->debug = $debug;
		$this->database = $database;
		if ($login_name) {
			$this->login_name = $login_name;
			$this->readUserDaten(0, $login_name, $passwort);
			$this->remote_addr = getenv('REMOTE_ADDR');
		}
		else {
			$this->id = $id;
			$this->readUserDaten($id, 0);
		}
	}

	function readUserDaten($id, $login_name, $password = '') {
		$where = array();
		if ($id > 0) array_push($where, "ID = " . $id);
		if ($login_name != '') array_push($where, "login_name LIKE '" . $this->database->mysqli->real_escape_string($login_name) . "'");
		if ($password != '') array_push($where, "password = SHA1('" . $this->database->mysqli->real_escape_string($password) . "')");
		$sql = "
			SELECT
				*
			FROM
				user
			WHERE
				" . implode(" AND ", $where) . "
		";
		#echo '<br>SQL to read user data: ' . $sql;

		$this->debug->write("<p>file:users.php class:user->readUserDaten - Abfragen des Namens des Benutzers:<br>", 3);
		$this->database->execSQL($sql, 4, 0, true);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . '<br>' . $this->database->mysqli->error, 4); return 0; }
		$rs = $this->database->result->fetch_array();
		$this->id = $rs['ID'];
		$this->login_name = $rs['login_name'];
		$this->Namenszusatz = $rs['Namenszusatz'];
		$this->Name = $rs['Name'];
		$this->Vorname = $rs['Vorname'];
		$this->stelle_id = $rs['stelle_id'];
		$this->phon = $rs['phon'];
		$this->email = $rs['email'];
		if (CHECK_CLIENT_IP) {
			$this->ips = $rs['ips'];
		}
		$this->funktion = $rs['Funktion'];
		$this->password_setting_time = $rs['password_setting_time'];
		$this->agreement_accepted = $rs['agreement_accepted'];
		$this->start = $rs['start'];
		$this->stop = $rs['stop'];
		$this->share_rollenlayer_allowed = $rs['share_rollenlayer_allowed'];
		$this->layer_data_import_allowed = $rs['layer_data_import_allowed'];
		$this->tokens = $rs['tokens'];
	}

	function setRolle($stelle_id) {
		# Abfragen und zuweisen der Einstellungen für die Rolle
		$rolle = new rolle($this->id, $stelle_id, $this->database);
		if ($rolle->readSettings()) {
			$this->rolle=$rolle;
			return 1;
		}
		return 0;
	}
}

class stelle {

  var $id;
  var $Bezeichnung;
  var $debug;
  var $nImageWidth;
  var $nImageHeight;
  var $oGeorefExt;
  var $pixsize;
  var $selectedButton;
  var $database;
  var $language;

	function __construct($id, $database) {
		global $debug;
		global $log_mysql;
		$this->debug = $debug;
		$this->log = $log_mysql;
		$this->id = $id;
		$this->database = $database;
		$this->Bezeichnung = $this->getName();
		$this->readDefaultValues();
	}

  function getName() {
    $sql ='SELECT ';
    if ($this->language != 'german' AND $this->language != ''){
      $sql.='`Bezeichnung_'.$this->language.'` AS ';
    }
    $sql.='Bezeichnung FROM stelle WHERE ID='.$this->id;
    #echo $sql;
    $this->debug->write("<p>file:stelle.php class:stelle->getName - Abfragen des Namens der Stelle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
    $this->Bezeichnung=$rs['Bezeichnung'];
    return $rs['Bezeichnung'];
  }

	function readDefaultValues() {
		$sql = "
			SELECT
				*
			FROM
				stelle
			WHERE
				ID = " . $this->id . "
		";
		$this->debug->write('<p>file:stelle.php class:stelle->readDefaultValues - Abfragen der Default Parameter der Karte zur Stelle:<br>' . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0;
		}
		$rs = $this->database->result->fetch_array();
		$this->MaxGeorefExt = ms_newRectObj();
		$this->MaxGeorefExt->setextent($rs['minxmax'], $rs['minymax'], $rs['maxxmax'], $rs['maxymax']);
		$this->epsg_code = $rs['epsg_code'];
		$this->postgres_connection_id = $rs['postgres_connection_id'];
		# ---> deprecated
			$this->pgdbhost = ($rs['pgdbhost'] == 'PGSQL_PORT_5432_TCP_ADDR' ? getenv('PGSQL_PORT_5432_TCP_ADDR') : $rs['pgdbhost']);
			$this->pgdbname = $rs['pgdbname'];
			$this->pgdbuser = $rs['pgdbuser'];
			$this->pgdbpasswd = $rs['pgdbpasswd'];
		# <---
		$this->protected = $rs['protected'];
		//---------- OWS Metadaten ----------//
		$this->ows_title = $rs['ows_title'];
		$this->ows_abstract = $rs['ows_abstract'];
		$this->wms_accessconstraints = $rs['wms_accessconstraints'];
		$this->ows_contactperson = $rs['ows_contactperson'];
		$this->ows_contactorganization = $rs['ows_contactorganization'];
		$this->ows_contactelectronicmailaddress = $rs['ows_contactemailaddress'];
		$this->ows_contactposition = $rs['ows_contactposition'];
		$this->ows_fees = $rs['ows_fees'];
		$this->ows_srs = $rs['ows_srs'];
		$this->check_client_ip = $rs['check_client_ip'];
		$this->checkPasswordAge = $rs['check_password_age'];
		$this->allowedPasswordAge = $rs['allowed_password_age'];
		$this->useLayerAliases = $rs['use_layer_aliases'];
		$this->selectable_layer_params = $rs['selectable_layer_params'];
		$this->hist_timestamp = $rs['hist_timestamp'];
		$this->default_user_id = $rs['default_user_id'];
		$this->style = $rs['style'];
	}

	function get_attributes_privileges($layer_id) {
		$sql = "
			SELECT
				`attributename`,
				`privileg`,
				`tooltip`
			FROM
				`layer_attributes2stelle`
			WHERE
				`stelle_id` = " . $this->id . " AND
				`layer_id` = " . $layer_id;
		#echo '<br>Sql: ' . $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->get_attributes_privileges - Abfragen der Layerrechte zur Stelle:<br>" . $sql, 4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__, 4); return 0; }
		while ($rs = $this->database->result->fetch_array()) {
			$privileges[$rs['attributename']] = $rs['privileg'];
			$privileges['tooltip_' . $rs['attributename']] = $rs['tooltip'];
			$privileges['attributenames'][] = $rs['attributename'];
		}
		return $privileges;
	}

	function isMenueAllowed($menuename){
		$sql = "SELECT distinct a.* from u_menues as a, u_menue2stelle as b ";
		$sql.= "WHERE links LIKE 'index.php?go=".$menuename."%' AND b.menue_id = a.id AND b.stelle_id = ".$this->id;
		#echo $sql;
		$this->debug->write("<p>file:stelle.php class:stelle->isMenueAllowed - Guckt ob der Menuepunkt der Stelle zugeordnet ist:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) {
			$this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4);
			$errmsg='Fehler bei der Ueberpruefung des Menuepunkts für die Stelle';
		}
		else{
			$rs=$this->database->result->fetch_array();
		}
		if($rs[0] != '') {
			return 1;
		}
		else {
			return 0;
		}
	}
}

class rolle {

  var $user_id;
  var $stelle_id;
  var $debug;
  var $database;
  var $loglevel;
  var $hist_timestamp_de;
  static $hist_timestamp;
  static $layer_params;
  var $minx;
  var $language;
  var $newtime;

	function __construct($user_id, $stelle_id, $database) {
		global $debug;
		global $GUI;
		$this->gui_object = $GUI;
		$this->debug=$debug;
		$this->user_id=$user_id;
		$this->stelle_id=$stelle_id;
		$this->database=$database;
		#$this->layerset=$this->getLayer('');
		#$this->groupset=$this->getGroups('');
		$this->loglevel = 0;
	}
	
	function get_layer_params($selectable_layer_params, $pgdatabase) {
		$layer_params = array();
		if (!empty($selectable_layer_params)) {
			$sql = "
				SELECT
					*
				FROM
					layer_parameter
				WHERE
					id IN (" . $selectable_layer_params . ")
			";
			#echo '<br>Sql: ' . $sql;
			$this->database->execSQL($sql, 4, 0);
			if (!$this->database->success) {
				echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
			}
			else {
				while ($param = $this->database->result->fetch_assoc()) {
					$sql = $param['options_sql'];
					$sql = str_replace('$user_id', $this->user_id, $sql);
					$sql = str_replace('$stelle_id', $this->stelle_id, $sql);
					#echo '<br>sql: ' . $sql;
					$options_result = $pgdatabase->execSQL($sql, 4, 0, false);
					if ($options_result['success']) {
						$param['options'] = array();
						while ($option = pg_fetch_assoc($options_result[1])) {
							$param['options'][] = $option;
						}
						$layer_params[$param['key']] = $param;
					}
					else {
						$layer_params['error_message'] = 'Fehler bei der Abfrage der Parameteroptionen: ' . $options_result[1];
					}
				}
			}
		}
		return $layer_params;
	}	
	
	function getRollenLayer($LayerName, $typ = NULL) {
		$sql ="
			SELECT l.*, 4 as tolerance, -l.id as Layer_ID, l.query as pfad, CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable, gle_view,
				concat('(', rollenfilter, ')') as Filter
			FROM rollenlayer AS l";
    $sql.=' WHERE l.stelle_id = '.$this->stelle_id.' AND l.user_id = '.$this->user_id;
    if ($LayerName!='') {
      $sql.=' AND (l.Name LIKE "'.$LayerName.'" ';
      if(is_numeric($LayerName)){
        $sql.='OR l.id = "'.$LayerName.'")';
      }
      else{
        $sql.=')';
      }
    }
		if($typ != NULL){
			$sql .= " AND Typ = '".$typ."'";
		}
    #echo $sql.'<br>';
    $this->debug->write("<p>file:rolle.php class:rolle->getRollenLayer - Abfragen der Rollenlayer zur Rolle:<br>".$sql,4);
    $this->database->execSQL($sql);
    if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$layer = array();
    while ($rs = $this->database->result->fetch_assoc()) {
      $layer[]=$rs;
    }
    return $layer;
  }		

  function readSettings() {
		global $language;
    # Abfragen und Zuweisen der Einstellungen der Rolle
    $sql = "
			SELECT
				*
			FROM
				rolle
			WHERE
				user_id = " . $this->user_id . " AND
				stelle_id = " . $this->stelle_id . "
		";
		#echo 'Read rolle settings mit sql: ' . $sql;
    $this->debug->write("<p>file:rolle.php class:rolle function:readSettings - Abfragen der Einstellungen der Rolle:<br>".$sql,4);
    $this->database->execSQL($sql);
    if (!$this->database->success) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
		if ($this->database->result->num_rows > 0){
			$rs = $this->database->result->fetch_assoc();
			$this->oGeorefExt=ms_newRectObj();
			$this->oGeorefExt->setextent($rs['minx'],$rs['miny'],$rs['maxx'],$rs['maxy']);
			$this->nImageWidth=$rs['nImageWidth'];
			$this->nImageHeight=$rs['nImageHeight'];			
			$this->mapsize=$this->nImageWidth.'x'.$this->nImageHeight;
			$this->auto_map_resize=$rs['auto_map_resize'];
			@$this->pixwidth=($rs['maxx']-$rs['minx'])/$rs['nImageWidth'];
			@$this->pixheight=($rs['maxy']-$rs['miny'])/$rs['nImageHeight'];
			$this->pixsize=($this->pixwidth+$this->pixheight)/2;
			$this->nZoomFactor=$rs['nZoomFactor'];
			$this->epsg_code=$rs['epsg_code'];
			$this->epsg_code2=$rs['epsg_code2'];
			$this->coordtype=$rs['coordtype'];
			$this->last_time_id=$rs['last_time_id'];
			$this->gui=$rs['gui'];
			$this->language=$rs['language'];
			$language = $this->language;
			$this->hideMenue=$rs['hidemenue'];
			$this->hideLegend=$rs['hidelegend'];
			$this->fontsize_gle=$rs['fontsize_gle'];
			$this->tooltipquery=$rs['tooltipquery'];
			$this->scrollposition=$rs['scrollposition'];
			$this->result_color=$rs['result_color'];
			$this->result_hatching=$rs['result_hatching'];
			$this->result_transparency=$rs['result_transparency'];
			$this->always_draw=$rs['always_draw'];
			$this->runningcoords=$rs['runningcoords'];
			$this->showmapfunctions=$rs['showmapfunctions'];
			$this->showlayeroptions=$rs['showlayeroptions'];
			$this->showrollenfilter=$rs['showrollenfilter'];
			$this->menue_buttons=$rs['menue_buttons'];
			$this->singlequery=$rs['singlequery'];
			$this->querymode=$rs['querymode'];
			$this->geom_edit_first=$rs['geom_edit_first'];
			$this->immer_weiter_erfassen = $rs['immer_weiter_erfassen'];
			$this->upload_only_file_metadata = $rs['upload_only_file_metadata'];
			$this->overlayx=$rs['overlayx'];
			$this->overlayy=$rs['overlayy'];
			$this->instant_reload=$rs['instant_reload'];
			$this->menu_auto_close=$rs['menu_auto_close'];
			rolle::$layer_params = (array)json_decode('{' . $rs['layer_params'] . '}');
			$this->visually_impaired = $rs['visually_impaired'];
			$this->legendtype = $rs['legendtype'];
			$this->print_legend_separate = $rs['print_legend_separate'];
			$this->print_scale = $rs['print_scale'];
			if ($rs['hist_timestamp'] != '') {
				$this->hist_timestamp_de = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('d.m.Y H:i:s');			# der wird zur Anzeige des Timestamps benutzt
				rolle::$hist_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $rs['hist_timestamp'])->format('Y-m-d\TH:i:s\Z');	# der hat die Form, wie der timestamp in der PG-DB steht und wird für die Abfragen benutzt
			}
			else {
				rolle::$hist_timestamp = $this->hist_timestamp_de = '';
				#rolle::$hist_timestamp = '';
			}
			$this->selectedButton=$rs['selectedButton'];
			$buttons = explode(',', $rs['buttons']);
			$this->back = in_array('back', $buttons);
			$this->forward = in_array('forward', $buttons);
			$this->zoomin = in_array('zoomin', $buttons);
			$this->zoomout = in_array('zoomout', $buttons);
			$this->zoomall = in_array('zoomall', $buttons);
			$this->recentre = in_array('recentre', $buttons);
			$this->jumpto = in_array('jumpto', $buttons);
			$this->coord_query = in_array('coord_query', $buttons);
			$this->query = in_array('query', $buttons);
			$this->queryradius = in_array('queryradius', $buttons);
			$this->polyquery = in_array('polyquery', $buttons);
			$this->touchquery = in_array('touchquery', $buttons);
			$this->measure = in_array('measure', $buttons);
			$this->freepolygon = in_array('freepolygon', $buttons);
			$this->freetext = in_array('freetext', $buttons);
			$this->freearrow = in_array('freearrow', $buttons);
			$this->gps = in_array('gps', $buttons);
			$this->geom_buttons = explode(',', str_replace(' ', '', $rs['geom_buttons']));
			return 1;
		}
		else {
			return 0;
		}
	}

	function getLayer($LayerName) {
		global $language;
		$layer_name_filter = '';
		
		# Abfragen der Layer in der Rolle
		if ($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
		}
		else {
			$name_column = "l.Name";
		}

		if ($LayerName != '') {
			if (is_numeric($LayerName)) {
				$layer_name_filter .= " AND l.Layer_ID = " . $LayerName;
			}
			else {
				$layer_name_filter = " AND (l.Name LIKE '" . $LayerName . "' OR l.alias LIKE '" . $LayerName . "')";
			}
		}

		$sql = "
				SELECT " .
				$name_column . ",
				l.Layer_ID,
				l.alias, Datentyp, Gruppe, pfad, maintable, oid, maintable_is_view, Data, tileindex, l.`schema`, max_query_rows, document_path, document_url, classification, ddl_attribute, 
				CASE 
					WHEN connectiontype = 6 THEN concat('host=', c.host, ' port=', c.port, ' dbname=', c.dbname, ' user=', c.user, ' password=', c.password, ' application_name=kvwmap_user_', r2ul.User_ID)
					ELSE l.connection 
				END as connection, 
				printconnection, classitem, connectiontype, epsg_code, tolerance, toleranceunits, sizeunits, wms_name, wms_auth_username, wms_auth_password, wms_server_version, ows_srs,
				wfs_geom, selectiontype, querymap, processing, `kurzbeschreibung`, `datasource`, `dataowner_name`, `dataowner_email`, `dataowner_tel`, `uptodateness`, `updatecycle`, metalink, status, trigger_function,
				sync,
				ul.`queryable`, ul.`drawingorder`,
				ul.`minscale`, ul.`maxscale`,
				ul.`offsite`,
				coalesce(r2ul.transparency, ul.transparency, 100) as transparency,
				coalesce(r2ul.labelitem, l.labelitem) as labelitem,
				l.labelitem as original_labelitem,
				l.`duplicate_from_layer_id`,
				l.`duplicate_criterion`,
				l.`shared_from`,
				ul.`postlabelcache`,
				`Filter`,
				r2ul.gle_view,
				ul.`template`,
				`header`,
				`footer`,
				ul.`symbolscale`,
				ul.`logconsume`,
				ul.`requires`,
				ul.`privileg`,
				ul.`export_privileg`,
				`start_aktiv`,
				r2ul.showclasses,
				r2ul.rollenfilter,
				r2ul.geom_from_layer,				
				(select 
					max(las.privileg) 
				from 
					layer_attributes as la, 
					layer_attributes2stelle as las
				where 
					la.layer_id = ul.Layer_ID AND 
					form_element_type = 'SubformFK' AND
					las.stelle_id = ul.Stelle_ID AND 
					ul.Layer_ID = las.layer_id AND 
					las.attributename = SUBSTRING_INDEX(SUBSTRING_INDEX(la.options, ';', 1) , ',', -1) 
				) as privilegfk
			FROM
				layer AS l 
				JOIN used_layer AS ul ON l.Layer_ID=ul.Layer_ID 
				JOIN u_rolle2used_layer as r2ul ON r2ul.Stelle_ID=ul.Stelle_ID AND r2ul.Layer_ID=ul.Layer_ID 
				LEFT JOIN connections as c ON l.connection_id = c.id 
			WHERE
				ul.Stelle_ID= " . $this->stelle_id . " AND
				r2ul.User_ID= " . $this->user_id .
				$layer_name_filter . "
			ORDER BY
				ul.drawingorder desc
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:rolle.php class:rolle->getLayer - Abfragen der Layer zur Rolle:<br>".$sql,4);
		$this->database->execSQL($sql);
		if (!$this->database->success) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		$i = 0;
		while ($rs = $this->database->result->fetch_assoc()) {
			if($rs['rollenfilter'] != ''){		// Rollenfilter zum Filter hinzufügen
				if($rs['Filter'] == ''){
					$rs['Filter'] = '('.$rs['rollenfilter'].')';
				}
				else {
					$rs['Filter'] = str_replace(' AND ', ' AND ('.$rs['rollenfilter'].') AND ', $rs['Filter']);
				}
			}
			foreach(array('Name', 'alias', 'connection', 'classification', 'pfad', 'Data') AS $key) {
				$rs[$key] = replace_params(
					$rs[$key],
					rolle::$layer_params,
					$this->user_id,
					$this->stelle_id,
					rolle::$hist_timestamp,
					$language,
					$rs['duplicate_criterion']
				);
			}
			$layer[$i]=$rs;
			$layer['layer_ids'][$rs['Layer_ID']] =& $layer[$i];
			$layer['layer_ids'][$layer[$i]['requires']]['required'] = $rs['Layer_ID'];
			$i++;
		}
		return $layer;
	}
}

class db_mapObj {

  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
  var $db;
  var $OhneRequires;

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

	function getlayerdatabase($layer_id, $host) {
		#echo '<br>GUI->getlayerdatabase layer_id: ' . $layer_id;
		$layerdb = new pgdatabase();
		$rs = $this->get_layer_connection($layer_id);
		if (count($rs) == 0) {
			return null;
		}
		$layerdb->schema = ($rs['schema'] == '' ? 'public' : $rs['schema']);
		$layerdb->host = $host; # depricated since host is allways in connection table
		if (!$layerdb->open($rs['connection_id'])) {
			echo 'Die Verbindung zur PostGIS-Datenbank konnte mit connection_id: ' . $rs['connection_id'] . ' nicht hergestellt werden:';
			exit;
		}
		return $layerdb;
	}

	/**
	* Function get the postgres connection_id and the schema of the layer with given layer_id
	* @params integer $layer_id, If layer_id is negativ the connection_id is from table rollen_layer
	* @return array with integer connection_id and string schema name, return an empty array if no connection for layer_id found
	*/
	function get_layer_connection($layer_id) {
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
	
	function get_layer_params_layer($param_id = NULL, $layer_id = NULL){
		$params = array();
		$sql = "
			SELECT
				p.id, l.Layer_ID, l.Name
			FROM
				`layer_parameter` as p,
				layer as l
			WHERE
				locate(
					concat('$', p.key),
					concat(l.Name, COALESCE(l.alias, ''), l.connection, l.Data, l.pfad, l.classitem, l.classification, COALESCE(l.connection, ''), COALESCE(l.processing, ''))
				) > 0
		";
		if($param_id != NULL){
			$sql .= " AND p.id = ".$params_id;
		}
		if($layer_id != NULL){
			$sql .= " 
			AND l.Layer_ID NOT IN (
					SELECT 
						SUBSTRING_INDEX(options, ';', 1) 
					FROM 
						layer_attributes as a
					WHERE 
						a.layer_id = " . $layer_id . " AND 
						a.form_element_type = 'SubformEmbeddedPK'
			) 
			GROUP BY 
				p.id
      HAVING 
				count(l.Layer_ID) = 1 AND 
				l.Layer_ID = " . $layer_id;
		}
		$this->db->execSQL($sql);
		if (!$this->db->success) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else {
			while ($rs = $this->db->result->fetch_assoc()) {
				$params[$rs['id']][] = ['Layer_ID' => $rs['Layer_ID'], 'Name' => $rs['Name']];
			}
		}
		return $params;
	}	
	
  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false){
		global $language;
		$attributes = array();
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
		#echo '<br>Sql read_layer_attributes: ' . $sql;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>",4);
		$ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo err_msg($this->script_name, __LINE__, $sql); return 0; }
		$i = 0;
		while ($rs = $ret['result']->fetch_array()){
			$attributes['order'][$i] = $rs['order'];
			$attributes['name'][$i] = $rs['name'];
			$attributes['indizes'][$rs['name']] = $i;
			if($rs['real_name'] == '')$rs['real_name'] = $rs['name'];
			$attributes['real_name'][$rs['name']] = $rs['real_name'];
			if ($rs['tablename']){
				if (strpos($rs['tablename'], '.') !== false){
					$explosion = explode('.', $rs['tablename']);
					$rs['tablename'] = $explosion[1];		# Tabellenname ohne Schema
					$attributes['schema_name'][$rs['tablename']] = $explosion[0];
				}
				$attributes['table_name'][$i]= $rs['tablename'];
				$attributes['table_name'][$rs['name']] = $rs['tablename'];
			}
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$i] = $rs['table_alias_name'];
			if ($rs['table_alias_name'])$attributes['table_alias_name'][$rs['name']] = $rs['table_alias_name'];
			$attributes['table_alias_name'][$rs['tablename']] = $rs['table_alias_name'];
			$attributes['type'][$i] = $rs['type'];
			$attributes['typename'][$i] = $rs['typename'];
			$type = ltrim($rs['type'], '_');
			if ($recursive AND is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($type, $layerdb, NULL, $all_languages, true);
			}
			if ($rs['type'] == 'geometry'){
				$attributes['the_geom'] = $rs['name'];
			}
			$attributes['geomtype'][$i]= $rs['geometrytype'];
			$attributes['geomtype'][$rs['name']]= $rs['geometrytype'];
			$attributes['constraints'][$i]= $rs['constraints'];
			$attributes['constraints'][$rs['real_name']]= $rs['constraints'];
			$attributes['saveable'][$i]= $rs['saveable'];
			$attributes['nullable'][$i]= $rs['nullable'];
			$attributes['length'][$i]= $rs['length'];
			$attributes['decimal_length'][$i]= $rs['decimal_length'];

			if (substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL($rs['default'], 4, 0);
				if ($ret1[0] == 0) {
					$attributes['default'][$i] = @array_pop(pg_fetch_row($ret1[1]));
				}
			}
			else {															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
				$attributes['default'][$i] = $rs['default'];
			}
			$attributes['form_element_type'][$i] = $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']] = $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $language, $rs['options']);
			$attributes['options'][$i] = $rs['options'];
			$attributes['options'][$rs['name']] = $rs['options'];
			$attributes['alias'][$i] = $rs['alias'];
			$attributes['alias'][$attributes['name'][$i]] = $rs['alias'];
			$attributes['alias_low-german'][$i] = $rs['alias_low-german'];
			$attributes['alias_english'][$i] = $rs['alias_english'];
			$attributes['alias_polish'][$i] = $rs['alias_polish'];
			$attributes['alias_vietnamese'][$i] = $rs['alias_vietnamese'];
			$attributes['tooltip'][$i] = $rs['tooltip'];
			$attributes['group'][$i] = $rs['group'];
			$attributes['arrangement'][$i] = $rs['arrangement'];
			$attributes['labeling'][$i] = $rs['labeling'];
			$attributes['raster_visibility'][$i] = $rs['raster_visibility'];
			$attributes['dont_use_for_new'][$i] = $rs['dont_use_for_new'];
			$attributes['mandatory'][$i] = $rs['mandatory'];
			$attributes['quicksearch'][$i] = $rs['quicksearch'];
			$attributes['visible'][$i] = $rs['visible'];
			$attributes['vcheck_attribute'][$i] = $rs['vcheck_attribute'];
			$attributes['vcheck_operator'][$i] = $rs['vcheck_operator'];
			$attributes['vcheck_value'][$i] = $rs['vcheck_value'];
			$attributes['dependents'][$i] = &$dependents[$rs['name']];
			$dependents[$rs['vcheck_attribute']][] = $rs['name'];
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
		if ($attributes['table_name'] != NULL) {
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
		}
		else {
			$attributes['all_table_names'] = array();
		}
		return $attributes;
  }	

	function getDataAttributes($database, $layer_id, $ifEmptyUseQuery = false) {
		global $language;
		$data = $this->getData($layer_id);
		if ($data != '') {
			$data = replace_params(
				$data,
				rolle::$layer_params,
				$this->User_ID,
				$this->Stelle_ID,
				rolle::$hist_timestamp,
				$language,
				NULL,
				1000
			);
			$select = $this->getSelectFromData($data);
			if ($database->schema != '') {
				$select = str_replace($database->schema.'.', '', $select);
			}
			$ret = $database->getFieldsfromSelect($select);
			if ($ret[0]) {
				$this->GUI->add_message('error', $ret[1]);
			}
			return $ret[1];
		}
		elseif ($ifEmptyUseQuery){
			$path = replace_params(
				$this->getPath($layer_id),
				rolle::$layer_params,
				$this->User_ID,
				$this->Stelle_ID,
				rolle::$hist_timestamp,
				$language
			);
			return $this->getPathAttributes($database, $path);
		}
		else {
			echo 'Das Data-Feld des Layers mit der Layer-ID ' . $layer_id . ' ist leer.';
			return NULL;
		}
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
    if (!$this->db->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__ . "<br>" . $this->db->mysqli->error, 4); return 0; }
    $rs = $this->db->result->fetch_assoc();
    $data = replace_params(
			$rs['Data'],
			rolle::$layer_params,
			$this->User_ID,
			$this->Stelle_ID,
			rolle::$hist_timestamp,
			$language,
			$rs['duplicate_criterion']
		);
    return $data;
  }

  function getSelectFromData($data){
		global $language;
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
		return replace_params(
						$select,
						rolle::$layer_params,
						$this->User_ID,
						$this->Stelle_ID,
						rolle::$hist_timestamp,
						$language
					);
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
		return $classarray;
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
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
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

  function read_Styles($Class_ID) {
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>" . $sql,4);
    $ret = $this->db->execSQL($sql);
    if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
    while($rs = $this->db->result->fetch_assoc()) {
      $Styles[]=$rs;
    }
    return $Styles;
  }

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
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>" . $sql,4);
		$this->db->execSQL($sql);
		if (!$this->db->success) { echo "<br>Abbruch in " . $this->script_name." Zeile: ".__LINE__; return 0; }
		while ($rs = $this->db->result->fetch_assoc()) {
			$Labels[]=$rs;
		}
		return $Labels;
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
		#echo $sql;

		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>", 4, 0);
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
}

class pgdatabase {
	var $ist_Fortfuehrung;
	var $debug;
	var $loglevel;
	var $defaultloglevel;
	var $logfile;
	var $defaultlogfile;
	var $commentsign;
	var $blocktransaction;
	var $host;
	var $port;
	var $schema;
	var $pg_text_attribute_types = array('character', 'character varying', 'text', 'timestamp without time zone', 'timestamp with time zone', 'date', 'USER-DEFINED');
	var $version = POSTGRESVERSION;
	var $connection_id;

	function __construct() {
		global $debug;
		global $GUI;
		$this->gui = $GUI;
		$this->debug=$debug;
		$this->loglevel=LOG_LEVEL;
		$this->defaultloglevel=LOG_LEVEL;
		global $log_postgres;
		$this->logfile=$log_postgres;
		$this->defaultlogfile=$log_postgres;
		$this->ist_Fortfuehrung=1;
		$this->type='postgresql';
		$this->commentsign='--';
		$this->err_msg = '';
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction=0;
	}

	/**
	* Open the database connection based on the given connection_id
	* @param integer, $connection_id The id of the connection defined in mysql connections table, if 0 default connection will be used
	* @return boolean, True if success or set an error message in $this->err_msg and return false when fail to find the credentials or open the connection
	*/
  function open($connection_id = 0) {
		if ($connection_id == 0) {
			# get credentials from object variables
			$connection_string = $this->format_pg_connection_string($this->get_object_credentials());
		}
		else {
			$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
			$this->connection_id = $connection_id;
			$connection_string = $this->get_connection_string();
		}
		$this->dbConn = pg_connect($connection_string);
		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden: ' . str_replace($credentials['password'], '********', $connection_string);
			return false;
		}
		else {
			$this->debug->write("Database connection: " . $this->dbConn . " successfully opend.", 4);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id;
			return true;
		}
	}
	
  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }	

	/**
	* return the credential details as array from connections_table
	* or default values if no exists for connection_id
	* @param integer $connection_id The id of connection information in connection mysql table
	* @return array $credentials array with connection details
	*/
	function get_credentials($connection_id) {
		#echo '<p>get_credentials with connection_id: ' . $connection_id;
		include_once(CLASSPATH . 'Connection.php');
		$conn = Connection::find_by_id($this->gui, $connection_id);
		return array(
			'host' => 		($conn->get('host')     != '' ? $conn->get('host')     : 'pgsql'),
			'port' => 		($conn->get('port')     != '' ? $conn->get('port')     : '5432'),
			'dbname' => 	($conn->get('dbname')   != '' ? $conn->get('dbname')   : 'kvwmapsp'),
			'user' => 		($conn->get('user')     != '' ? $conn->get('user')     : 'kvwmap'),
			'password' => ($conn->get('password') != '' ? $conn->get('password') : KVWMAP_INIT_PASSWORD)
		);
	}

	/**
	* returns a postgres connection string used to connect to postgres with pg_connect
	* @param array $credentials array with connection details
	* @return string the postgres connection string
	*/
	function format_pg_connection_string($credentials) {
		$connection_string = '' .
			'host=' . 		$credentials['host'] 		. ' ' .
			'port=' . 		$credentials['port'] 		. ' ' .
			'dbname=' . 	$credentials['dbname'] 	. ' ' .
			'user=' . 		$credentials['user'] 		. ' ' .
			'password=' .	$credentials['password'];
		return $connection_string;
	}

	function get_connection_string() {
		return $this->format_pg_connection_string($this->get_credentials($this->connection_id));
	}

	/**
	* Set credentials to postgres object variables
	*/
	function set_object_credentials($credentials) {
		$this->host = 	$credentials['host'];
		$this->port = 	$credentials['port'];
		$this->dbName = $credentials['dbname'];
		$this->user = 	$credentials['user'];
		$this->passwd = $credentials['password'];
	}

	/**
	* Get credentials from postgres object variables
	*/
	function get_object_credentials() {
		return array(
			'host'     => $this->host,
			'port'     => $this->port,
			'dbname'   => $this->dbName,
			'user'     => $this->user,
			'password' => $this->passwd
		);
	}

  function setClientEncodingAndDateStyle() {
    $sql = "
			SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';
			SET datestyle TO 'German';
			";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];
  }

	function getEnumElements($name, $schema){
		$sql = "SELECT array_to_string(array_agg(''''||e.enumlabel||''''), ',') as enum_string ";
		$sql.= "FROM pg_enum e ";
		$sql.= "JOIN pg_type t ON e.enumtypid = t.oid ";
		$sql.= "JOIN pg_namespace ns ON (t.typnamespace = ns.oid) ";
		$sql.= "WHERE t.typname = '".$name."' ";
		$sql.= "AND ns.nspname = '".$schema."'";
		$ret1 = $this->execSQL($sql, 4, 0);
		if($ret1[0]==0){
			$result = pg_fetch_assoc($ret1[1]);
		}
		return $result['enum_string'];
	}
	
	function writeCustomType($typname, $schema) {
		$datatype_id = $this->getDatatypeId($typname, $schema, $this->connection_id);
		$this->writeDatatypeAttributes($datatype_id, $typname, $schema);
		return $datatype_id;
	}
	
	function getDatatypeId($typname, $schema, $connection_id){
		$sql = "
			SELECT
				id
			FROM
				datatypes
			WHERE
				`name` = '" . $typname . "' AND
				`schema` = '" . $schema . "' AND
				`connection_id` = '" . $connection_id . "'
		";
		$ret1 = $this->gui->database->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs = $this->gui->database->result->fetch_assoc();
		if ($rs == NULL) {
			$sql = "
				INSERT INTO datatypes (
					`name`,
					`schema`,
					`connection_id`
				)
				VALUES (
					'" . $typname . "',
					'" . $schema . "',
					'" . $connection_id . "'
				)
			";
			$ret2 = $this->gui->database->execSQL($sql, 4, 1);
			$datatype_id = $this->gui->database->mysqli->insert_id;
		}
		else{	
			$datatype_id = $rs['id'];
		}
		return $datatype_id;
	}
	
	function writeDatatypeAttributes($datatype_id, $typname, $schema){
		$attr_info = $this->get_attribute_information($schema, $typname);
		for($i = 0; $i < count($attr_info); $i++){
			$fields[$i]['real_name'] = $attr_info[$i]['name'];
			$fields[$i]['name'] = $attr_info[$i]['name'];
			$fieldtype = $attr_info[$i]['type_name'];
			$fields[$i]['nullable'] = $attr_info[$i]['nullable']; 
			$fields[$i]['length'] = $attr_info[$i]['length'];
			$fields[$i]['decimal_length'] = $attr_info[$i]['decimal_length'];
			$fields[$i]['default'] = $attr_info[$i]['default'];					
			if($attr_info[$i]['is_array'] == 't')$prefix = '_'; else $prefix = '';
			if($attr_info[$i]['type_type'] == 'c'){		# custom datatype
				$sub_datatype_id = $this->writeCustomType($attr_info[$i]['type'], $attr_info[$i]['type_schema']);
				$fieldtype = $prefix.$sub_datatype_id; 
			}
			$constraintstring = '';
			if($attr_info[$i]['type_type'] == 'e'){		# enum
				$fieldtype = $prefix.'text';
				$constraintstring = $this->getEnumElements($attr_info[$i]['type'], $attr_info[$i]['type_schema']);
			}
			$fields[$i]['constraints'] = $constraintstring;
			$fields[$i]['type'] = $fieldtype;
			if($fields[$i]['nullable'] == '')$fields[$i]['nullable'] = 'NULL';
			if($fields[$i]['length'] == '')$fields[$i]['length'] = 'NULL';
			if($fields[$i]['decimal_length'] == '')$fields[$i]['decimal_length'] = 'NULL';
			$sql = "INSERT INTO datatype_attributes SET
								datatype_id = ".$datatype_id.", 
								name = '".$fields[$i]['name']."', 
								real_name = '".$fields[$i]['real_name']."', 
								type = '".$fields[$i]['type']."', 
								constraints = '".$this->gui->database->mysqli->real_escape_string($fields[$i]['constraints'])."', 
								nullable = ".$fields[$i]['nullable'].", 
								length = ".$fields[$i]['length'].", 
								decimal_length = ".$fields[$i]['decimal_length'].", 
								`default` = '".$this->gui->database->mysqli->real_escape_string($fields[$i]['default'])."', 
								`order` = ".$i." 
							ON DUPLICATE KEY UPDATE
								real_name = '".$fields[$i]['real_name']."', 
								type = '".$fields[$i]['type']."', 
								constraints = '".$this->gui->database->mysqli->real_escape_string($fields[$i]['constraints'])."', 
								nullable = ".$fields[$i]['nullable'].", 
								length = ".$fields[$i]['length'].", 
								decimal_length = ".$fields[$i]['decimal_length'].", 
								`default` = '".$this->gui->database->mysqli->real_escape_string($fields[$i]['default'])."', 
								`order` = ".$i;
			$ret1 = $this->gui->database->execSQL($sql, 4, 1);
			if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		}
	}		

	function getFieldsfromSelect($select, $assoc = false) {
		$err_msgs = array();
		$error_reporting = error_reporting();
		error_reporting(E_NOTICE);
		ini_set("pgsql.log_notice", '1');
		ini_set("pgsql.ignore_notice", '0');
		ini_set("display_errors", '0');
		$error_list = array();
		$myErrorHandler = function ($error_level, $error_message, $error_file, $error_line, $error_context) use (&$error_list) {
			if(strpos($error_message, "\n      :resno") !== false){
				$error_list[] = $error_message;
			}
			return false;
		};
		set_error_handler($myErrorHandler);
		# den Queryplan als Notice mitabfragen um an Infos zur Query zu kommen
		$sql = "
			SET client_min_messages='log';
			SET log_min_messages='fatal';
			SET debug_print_parse=true;" . 
			$select . " LIMIT 0;";
		$ret = $this->execSQL($sql, 4, 0);
		$sql = "
			SET client_min_messages = 'NOTICE';
			SET log_min_messages='error';
			SET debug_print_parse = false;";
		$this->execSQL($sql, 4, 0);
		error_reporting($error_reporting);
		if ($ret['success']) {
			$query_plan = $error_list[0];
			$table_alias_names = $this->get_table_alias_names($query_plan);
			$field_plan_info = explode("\n      :resno", $query_plan);
			
			for ($i = 0; $i < pg_num_fields($ret[1]); $i++) {
				# Attributname
				$fields[$i]['name'] = $fieldname = pg_field_name($ret[1], $i);
				
				# Spaltennummer in der Tabelle
				$col_num = get_first_word_after($field_plan_info[$i+1], ':resorigcol');
				
				# Tabellen-oid des Attributs
				$table_oid = pg_field_table($ret[1], $i, true);

				# wenn das Attribut eine Tabellenspalte ist -> weitere Attributeigenschaften holen
				if ($table_oid > 0){					
					# Tabellenname des Attributs
					$fields[$i]['table_name'] = $tablename = pg_field_table($ret[1], $i);
					if ($tablename != NULL) {
						$all_table_names[] = $tablename;
					}
										
					# Tabellenaliasname des Attributs
					$fields[$i]['table_alias_name'] = $table_alias_names[$table_oid];

					# Schemaname der Tabelle des Attributs
					$schemaname = $this->pg_field_schema($table_oid);		# der Schemaname kann hiermit aus der Query ermittelt werden; evtl. in layer_attributes speichern?	
					
					$constraintstring = '';
					$attr_info = $this->get_attribute_information($schemaname, $tablename, $col_num);
					if($attr_info[0]['relkind'] == 'v'){		# wenn View, dann Attributinformationen aus View-Definition holen
						if($view_defintion_attributes[$tablename] == NULL) {
							$ret2 = $this->getFieldsfromSelect(substr($attr_info[0]['view_definition'], 0, -1), true);
							if ($ret2['success']) {
								$view_defintion_attributes[$tablename] = $ret2[1];
							}
							else {
								# Füge Fehlermeldung hinzu und setze leeres Array
								$err_msgs[] = $ret2[1];
								$view_defintion_attributes[$tablename] = array();
							}
						}
						if ($view_defintion_attributes[$tablename][$fieldname]['nullable'] != NULL)$attr_info[0]['nullable'] = $view_defintion_attributes[$tablename][$fieldname]['nullable'];
						if ($view_defintion_attributes[$tablename][$fieldname]['default'] != NULL)$attr_info[0]['default'] = $view_defintion_attributes[$tablename][$fieldname]['default'];
					}
					# realer Name der Spalte in der Tabelle
					$fields[$i]['real_name'] = $attr_info[0]['name'];
					$fieldtype = $attr_info[0]['type_name'];
					$fields[$i]['nullable'] = $attr_info[0]['nullable']; 
					$fields[$i]['length'] = $attr_info[0]['length'];
					$fields[$i]['decimal_length'] = $attr_info[0]['decimal_length'];
					$fields[$i]['default'] = $attr_info[0]['default'];
					if($attr_info[0]['is_array'] == 't')$prefix = '_'; else $prefix = '';
					if($attr_info[0]['type_type'] == 'c'){		# custom datatype
						$datatype_id = $this->writeCustomType($attr_info[0]['type'], $attr_info[0]['type_schema']);
						$fieldtype = $prefix.$datatype_id; 
					}
					if($attr_info[0]['type_type'] == 'e'){		# enum
						$fieldtype = $prefix.'text';
						$constraintstring = $this->getEnumElements($attr_info[0]['type'], $attr_info[0]['type_schema']);
					}
					if($attr_info[0]['indisunique'] == 't')$constraintstring = 'UNIQUE';
					if($attr_info[0]['indisprimary'] == 't')$constraintstring = 'PRIMARY KEY';
					$constraints = $this->pg_table_constraints($tablename);		# todo
					if($fieldtype != 'geometry'){
						# testen ob es für ein Attribut ein constraint gibt, das wie enum wirkt
						for($j = 0; $j < @count($constraints); $j++){
							if(strpos($constraints[$j], '('.$fieldname.')')){
								$options = explode("'", $constraints[$j]);
								for($k = 0; $k < count($options); $k++){
									if($k%2 == 1){
										if($k > 1){
											$constraintstring.= ",";
										}
										$constraintstring.= "'".$options[$k]."'";
									}
								}
							}
						}
					}
					$fields[$i]['constraints'] = $constraintstring;
					$fields[$i]['saveable'] = 1;
				}
				else { # Attribut ist keine Tabellenspalte -> nicht speicherbar
					$fieldtype = pg_field_type($ret[1], $i);			# Typ aus Query ermitteln
					$fields[$i]['saveable'] = 0;
				}
				$fields[$i]['type'] = $fieldtype;

				# Geometrietyp
				if ($fieldtype == 'geometry') {
					$fields[$i]['geomtype'] = $this->get_geom_type($schemaname, $fields[$i]['real_name'], $tablename);
					$fields['the_geom'] = $fieldname;
					$fields['the_geom_id'] = $i;
				}
				if ($assoc) {
					$fields_assoc[$fieldname] = $fields[$i];
				}
			}
			$ret[1] = ($assoc ? $fields_assoc : $fields);
		}
		else {
			# Füge Fehlermeldung hinzu
			$err_msgs[] = $ret[1];
		}

		if (count($err_msgs) > 0) {
			# Wenn Fehler auftraten liefer nur die Fehler zurück
			$ret[0] = 1;
			$ret[1] = implode('<br>', $err_msgs);
		}
		return $ret;
	}

	function execSQL($sql, $debuglevel, $loglevel, $suppress_err_msg = false) {
		$ret = array(); // Array with results to return
		$strip_context = true;

		switch ($this->loglevel) {
			case 0 : {
				$logsql = 0;
			} break;
			case 1 : {
				$logsql = 1;
			} break;
			case 2 : {
				$logsql = $loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
			#echo "<br>SQL in execSQL: " . $sql;
			if ($this->schema != '') {
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			#echo "<br>SQL in execSQL: " . $sql;
			$query = pg_query($this->dbConn, $sql);
			//$query=0;
			if ($query == 0) {
				$ret['success'] = false;
				# erzeuge eine Fehlermeldung;
				$last_error = pg_last_error($this->dbConn);
				if ($strip_context AND strpos($last_error, 'CONTEXT: ') !== false) {
					$ret['msg'] = substr($last_error, 0, strpos($last_error, 'CONTEXT: '));
				}
				else {
					$ret['msg'] = $last_error;
				}

				if (strpos($last_error, '{') !== false AND strpos($last_error, '}') !== false) {
					# Parse als JSON String;
					$error_obj = json_decode(substr($last_error, strpos($last_error, '{'), strpos($last_error, '}') - strpos($last_error, '{') + 1), true);
					if (array_key_exists('msg_type', $error_obj)) {
						$ret['type'] = $error_obj['msg_type'];
					}
					if (array_key_exists('msg', $error_obj) AND $error_obj['msg'] != '') {
						$ret['msg'] = $error_obj['msg'];
					}
				}
				else {
					$ret['type'] = 'error';
				}
				$this->debug->write("<br><b>" . $last_error . "</b>", $debuglevel);
				if ($logsql) {
					$this->logfile->write($this->commentsign . ' ' . $sql . ' ' . $last_error);
				}
			}
			else {
				# Abfrage wurde zunächst erfolgreich ausgeführt
				$ret[0] = 0;
				$ret['success'] = true;
				$ret[1] = $ret['query'] = $query;

				# Prüfe ob eine Fehlermeldung in der Notice steckt
				$last_notice = pg_last_notice($this->dbConn);
				if ($strip_context AND strpos($last_notice, 'CONTEXT: ') !== false) {
					$last_notice = substr($last_notice, 0, strpos($last_notice, 'CONTEXT: '));
				}
				# Verarbeite Notice nur, wenn sie nicht schon mal vorher ausgewertet wurde
				if ($last_notice != '' AND ($this->gui->notices == NULL OR !in_array($last_notice, $this->gui->notices))) {
					$this->gui->notices[] = $last_notice;
					if (strpos($last_notice, '{') !== false AND strpos($last_notice, '}') !== false) {
						# Parse als JSON String
						$notice_obj = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true);
						if ($notice_obj AND array_key_exists('success', $notice_obj)) {
							if (!$notice_obj['success']) {
								$ret['success'] = false;
							}
							if (array_key_exists('msg_type', $notice_obj)) {
								$ret['type'] = $notice_obj['msg_type'];
							}
							if (array_key_exists('msg', $notice_obj) AND $notice_obj['msg'] != '') {
								$ret['msg'] = $notice_obj['msg'];
							}
						}
					}
					else {
						# Gebe Noticetext wie er ist zurück
						$ret['msg'] = $last_notice;
					}
				}
				# Schreibe Meldungen in Log und Debugfile
				$this->debug->write("<br>" . $sql, $debuglevel);
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
			}
			$ret[2] = $sql;
		}
		else {
			# Es werden keine SQL-Kommandos ausgeführt
			# Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
			$ret[0] = 0;
			$ret['success'] = true;
			# jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
			$ret[1] = 0;
			# Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
			# zusätzlich immer in die debugdatei
			# 2006-07-04 pk $logfile ersetzt durch $this->logfile
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}

		if ($ret['success']) {
			# alles ok mach nichts weiter
		}
		else {
			# Fehler setze entsprechende Fags und Fehlermeldung
			$ret[0] = 1;
			$ret[1] = $ret['msg'];
			if ($suppress_err_msg) {
				# mache nichts, den die Fehlermeldung wird unterdrückt
			}
			else {
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:', $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		return $ret;
	}

	function get_table_alias_names($query_plan){
		$table_info = explode(":eref \n         {ALIAS \n         ", $query_plan);
		for($i = 1; $i < count($table_info); $i++){
			$table_alias = get_first_word_after($table_info[$i], ':aliasname');
			$table_oid = get_first_word_after($table_info[$i], ':relid');
			$table_alias_names[$table_oid] = $table_alias;
		}
		return $table_alias_names;
	}

	function pg_field_schema($table_oid){
		if($table_oid != ''){
			$sql = "select nspname as schema from pg_class c, pg_namespace ns
						where c.relnamespace = ns.oid 
						and c.oid = ".$table_oid;
			$ret = $this->execSQL($sql, 4, 0);
			if($ret[0]==0)$ret = pg_fetch_assoc($ret[1]);
			return $ret['schema'];
		}
	}

	function get_attribute_information($schema, $table, $col_num = NULL) {
		if ($col_num != NULL) {
			$and_column = " a.attnum = " . $col_num . " ";
		}
		else {
			$and_column = " a.attnum > 0 ";
		}
		$attributes = array();
		$sql = "
			SELECT
				ns.nspname as schema,
				c.relname AS table_name,
				c.relkind,
				a.attname AS name,
				NOT a.attnotnull AS nullable,
				a.attnum AS ordinal_position,
				pg_get_expr(ad.adbin, ad.adrelid) as default,
				t.typname AS type_name,
				tns.nspname as type_schema,
				CASE WHEN t.typarray = 0 THEN eat.typname ELSE t.typname END AS type,
				t.oid AS attribute_type_oid,
				coalesce(eat.typtype, t.typtype) as type_type,
				case when t.typarray = 0 THEN true ELSE false END AS is_array,
				CASE WHEN t.typname = 'varchar' AND a.atttypmod > 0 THEN a.atttypmod - 4 ELSE NULL END as character_maximum_length,
				CASE a.atttypid
				 WHEN 21 /*int2*/ THEN 16
				 WHEN 23 /*int4*/ THEN 32
				 WHEN 20 /*int8*/ THEN 64
				 WHEN 1700 /*numeric*/ THEN
				      CASE WHEN atttypmod = -1
					   THEN null
					   ELSE ((atttypmod - 4) >> 16) & 65535
					   END
				 WHEN 700 /*float4*/ THEN 24 /*FLT_MANT_DIG*/
				 WHEN 701 /*float8*/ THEN 53 /*DBL_MANT_DIG*/
				 ELSE null
				END   AS numeric_precision,
				CASE 
				    WHEN atttypid IN (21, 23, 20) THEN 0
				    WHEN atttypid IN (1700) THEN
					CASE 
					    WHEN atttypmod = -1 THEN null
					    ELSE (atttypmod - 4) & 65535
					END
				       ELSE null
				  END AS decimal_length,
				i.indisunique,
				i.indisprimary,
				v.definition as view_definition
			FROM
				pg_catalog.pg_class c JOIN
				pg_catalog.pg_attribute a ON (c.oid = a.attrelid) JOIN
				pg_catalog.pg_namespace ns ON (c.relnamespace = ns.oid) JOIN
				pg_catalog.pg_type t ON (a.atttypid = t.oid) LEFT JOIN
				pg_catalog.pg_namespace tns ON (t.typnamespace = tns.oid) LEFT JOIN
				pg_catalog.pg_type eat ON (t.typelem = eat.oid) LEFT JOIN 
				pg_index i ON i.indrelid = c.oid AND a.attnum = ANY(i.indkey)	LEFT JOIN 
				pg_catalog.pg_attrdef ad ON a.attrelid = ad.adrelid AND ad.adnum = a.attnum LEFT JOIN 
				pg_catalog.pg_views v ON v.viewname = c.relname AND v.schemaname = ns.nspname
			WHERE
				ns.nspname IN ('" .  implode("','", array_map(function($schema) { return trim($schema); }, explode(',', $schema)))  .  "') AND
				c.relname = '" . $table . "' AND
				" . $and_column . "
			ORDER BY a.attnum, indisunique desc, indisprimary desc
		";
		#echo '<br><br>' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if($ret[0]==0){
			while($attr_info = pg_fetch_assoc($ret[1])){
				if($attr_info['nullable'] == 'f' AND substr($attr_info['default'], 0, 7) != 'nextval'){$attr_info['nullable'] = '0';}else{$attr_info['nullable'] = '1';}
        if($attr_info['numeric_precision'] != '')$attr_info['length'] = $attr_info['numeric_precision'];
        else $attr_info['length'] = $attr_info['character_maximum_length'];
	      if($attr_info['decimal_length'] == ''){$attr_info['decimal_length'] = 'NULL';}	      
	      if($attr_info['default'] != '' AND substr($attr_info['default'], 0, 7) != 'nextval')$attr_info['default'] = 'SELECT '.$attr_info['default'];
	  		else $attr_info['default'] = '';
				$attributes[] = $attr_info;
			}
		}
		return $attributes;
	}

  function pg_table_constraints($table){
  	if($table != ''){
	    $sql = "SELECT pg_get_expr(conbin, conrelid) FROM pg_constraint, pg_class WHERE contype = 'check'";
	    $sql.= " AND pg_class.oid = pg_constraint.conrelid AND pg_class.relname = '".$table."'";
	    $ret = $this->execSQL($sql, 4, 0);
	    if($ret[0]==0){
	      while($row = pg_fetch_assoc($ret[1])){
	        $constraints[] = $row['consrc'];
	      }
	    }
	    return $constraints;
  	}
  }

	function get_geom_type($schema, $geomcolumn, $tablename){
		if ($schema == '') {
			$schema = 'public';
		}
		$schema = str_replace(',', "','", $schema);
		if ($geomcolumn != '' AND $tablename != '') {
			#-- search_path ist zwar gesetzt, aber nur auf custom_shapes, daher ist das Schema der Tabelle erforderlich
			$sql = "
				SELECT coalesce(
					(select geometrytype(" . $geomcolumn . ") FROM " . $schema . "." . pg_quote($tablename) . " limit 1)
					,  
					(select type from geometry_columns WHERE 
					 f_table_schema IN ('" . $schema . "') and 
					 f_table_name = '" . $tablename . "' AND 
					 f_geometry_column = '" . $geomcolumn . "')
				) as type
			";
			$ret1 = $this->execSQL($sql, 4, 0);
			if($ret1[0] == 0) {
				$result = pg_fetch_assoc($ret1[1]);
				$geom_type = $result['type'];
			}
			else {
				$geom_type = 'GEOMETRY';
			}
		}
		else{
			$geom_type = NULL;
		}
		return $geom_type;
	}
}
?>
