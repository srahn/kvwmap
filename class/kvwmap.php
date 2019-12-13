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
# Klassenbibliothek für die Benutzeroberfläche im Browser         #
###################################################################
# Liste der Klassen:
########################################
# GUI - Das Programm
# db_MapObj
########################################

###############################################################
# Klasse für die Funktionen der graphische Benutzeroberfläche #
###############################################################
# Klasse GUI #
##############
class GUI {

	var $layout;
	var $style;
	var $mime_type;
	var $menue;
	var $pdf;
	var $addressliste;
	var $debug;
	var $dbConn;
	var $flst;
	var $formvars;
	var $legende;
	var $map;
	var $mapDB;
	var $img;
	var $FormObject;
	var $StellenForm;
	var $Fehlermeldung;
	var $messages = array();
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

	# Konstruktor
	function GUI($main, $style, $mime_type) {
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

	public function __call($method, $arguments){
		if (isset($this->{$method}) && is_callable($this->{$method})) {
			return call_user_func_array($this->{$method}, $arguments);
		}
	}

	function login() {
		$this->expect = array('login_name', 'passwort', 'mobile');
		if ($this->formvars['go'] == 'logout') {
			$this->expect[] = 'go';
		}
		$this->gui = LOGIN;
		$this->output();
	}

	function is_login_granted($user, $login_name) {
		if($user->login_name != $login_name){
			$this->login_failed_reason = 'authentication';
			return false;
		}
		if($user->stop != '0000-00-00' AND date('Y-m-d') > $user->stop){
			$this->login_failed_reason = 'expired';
			return false;
		}
		return true;
	}

	function login_failed() {
		$this->login_failed = $failed;
		$this->expect = array('login_name', 'passwort', 'mobile');
		if ($this->formvars['go'] == 'logout') {
			$this->expect[] = 'go';
		}
		switch ($this->login_failed_reason) {
			case 'authentication' : {
				$this->add_message('error', 'Benutzername oder Passwort ' . ($this->formvars['num_failed'] > 0 ? $this->formvars['num_failed'] . ' mal' : '') . ' falsch eingegeben!<br>Versuchen Sie es noch einmal.');
			}break;
			case 'expired' : {
				$this->add_message('error', 'Der zeitlich eingeschränkte Zugang des Nutzers ist abgelaufen.');
			}break;
		}
		$this->log_loginfail->write(
			date("Y:m:d H:i:s", time()) .
			' IP: ' . $_SERVER['REMOTE_ADDR'] .
			' Port: ' . $_SERVER['REMOTE_PORT'] .
			' User: ' . $login_name .
			' User agent: ' .
			getenv('HTTP_USER_AGENT')
		);
		$this->gui = (file_exists(LOGIN) ? LOGIN : SNIPPETS . 'login.php');
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
	*
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
	*/
	function exec_trigger_function($fired, $event, $layer, $oid = '', $old_dataset = array()) {
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
		return $trigger_result;
	}
	
	function geo_name_query(){
		$result = json_decode(url_get_contents(GEO_NAME_SEARCH_URL.urlencode($this->formvars['q'])), true);
		$stellen_extent = $this->Stelle->MaxGeorefExt;
		$projFROM = ms_newprojectionobj("init=epsg:" . $this->user->rolle->epsg_code);
		$projTO = ms_newprojectionobj("init=epsg:4326");
		$stellen_extent->project($projFROM, $projTO);
		$show = false;
		for($i = 0; $i < count($result['features']); $i++){
			$coord = $result['features'][$i]['geometry']['coordinates'];
			if($stellen_extent->minx < $coord[0] AND $coord[0] < $stellen_extent->maxx AND $stellen_extent->miny < $coord[1] AND $coord[1] < $stellen_extent->maxy){
				$show = true;
				$name = $result['features'][$i]['properties'][GEO_NAME_SEARCH_PROPERTY];
				$output .= '<li><a href="index.php?go=zoom2coord&INPUT_COORD='.$coord[0].','.$coord[1].'&epsg_code=4326&name='.$name.'\'">'.$name.'</a></li>';
			}
		}
		if($show)echo '<div style="position: absolute;top: 0px;right: 0px">
										<a href="javascript:void(0)" onclick="document.getElementById(\'geo_name_search_result_div\').innerHTML=\'\';" title="Schlie&szlig;en">
											<img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img>
										</a>
									</div>
									<ul>'.$output.'</ul>';;
	}

	function show_snippet() {
		if (empty($this->formvars['snippet'])) {
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
			if (strtolower($this->formvars['format']) == 'json' OR $this->formvars['only_main']) {
				include_once(WWWROOT . CUSTOM_PATH . 'layouts/' . $snippet_file);
			}
		}
		else {
			$this->add_message('error', $error_msg);
			$this->loadMap('DataBase');
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->saveMap('');
			$this->drawMap();
		}
		$this->main = '../../' . CUSTOM_PATH . 'layouts/snippets/' . $snippet_file;
		$this->output();
	}

	function loadDrawingOrderForm(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayer = true;
		$layerset = $mapDB->read_Layer(0, $this->Stelle->useLayerAliases, NULL);		# class_load_level: 0 = keine Klassen laden
		$layer = array_reverse($layerset['list']);
		echo '<div class="drawingOrderFormDropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
		for($i = 0; $i < count($layer); $i++){
			echo '<div class="drawingOrderFormLayer" draggable="true" ondragstart="handleDragStart(event)" ondragend="handleDragEnd(event)"><span>'.$layer[$i]['alias'].'</span><input name="active_layers[]" type="hidden" value="'.$layer[$i]['Layer_ID'].'"></div>';
			echo '<div class="drawingOrderFormDropZone" ondragenter="handleDragEnter(event)" ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"></div>';
		}
	}

	function getLayerOptions(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($this->formvars['layer_id'] > 0)$layer = $this->user->rolle->getLayer($this->formvars['layer_id']);
		else $layer = $this->user->rolle->getRollenLayer(-$this->formvars['layer_id']);
		if($layer[0]['connectiontype']==6){
			$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
			$attributes = $mapDB->getDataAttributes($layerdb, $this->formvars['layer_id'], false);
			$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
		}
		$disabled_classes = $mapDB->read_disabled_classes();
		$layer[0]['Class'] = $mapDB->read_Classes($this->formvars['layer_id'], $disabled_classes);
		echo '
		<div class="layerOptions" id="options_content_'.$this->formvars['layer_id'].'">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:closeLayerOptions('.$this->formvars['layer_id'].');" title="Schlie&szlig;en"><img style="border:none" src="'.GRAPHICSPATH.'exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding-bottom: 8px">
				<tr>
					<td class="layerOptionsHeader">
						<span class="fett">'.$this->layerOptions.'</span>
					</td>
				</tr>
				<tr>
					<td>
						<ul>';
						if($this->formvars['layer_id'] < 0){
							echo '<li><a href="index.php?go=delete_rollenlayer&id='.(-$this->formvars['layer_id']).'">'.$this->strRemove.'</a></li>';
							echo '<li><span>'.$this->strName.':</span> <input type="text" name="layer_options_name" value="'.$layer[0]['Name'].'"></li>';
						}
						else{
							if($this->Stelle->isMenueAllowed('Layer_Anzeigen')){
								echo '<li><span><a href="javascript:toggle(document.getElementById(\'layer_properties\'));">'.$this->properties.'</a></span></li>';
								echo '<div id="layer_properties" style="display: none">
												<ul>
													<li><a href="index.php?go=Layereditor&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->layerDefinition.'</a></li>
													<li><a href="index.php?go=Attributeditor&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->attributeditor.'</a></li>
													<li><a href="index.php?go=Layerattribut-Rechteverwaltung&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->strPrivileges.'</a></li>
													<li><a href="index.php?go=Style_Label_Editor&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->strStyles.'</a></li>
												</ul>
											</div>';
							}
						}
						if($layer[0]['connectiontype']==6 OR($layer[0]['Datentyp']==MS_LAYER_RASTER AND $layer[0]['connectiontype']!=7)){
							echo '<li><a href="javascript:zoomToMaxLayerExtent('.$this->formvars['layer_id'].')">'.$this->FullLayerExtent.'</a></li>';
						}
						if(in_array($layer[0]['connectiontype'], [MS_POSTGIS, MS_WFS]) AND $layer[0]['queryable']){
							echo '<li><a href="index.php?go=Layer-Suche&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->strSearch.'</a></li>';
						}
						if($layer[0]['privileg'] > 0){
							echo '<li><a href="index.php?go=neuer_Layer_Datensatz&selected_layer_id='.$this->formvars['layer_id'].'">'.$this->newDataset.'</a></li>';
						}
						if($layer[0]['Class'][0]['Name'] != ''){
							if($layer[0]['showclasses'] != ''){
								echo '<li><a href="javascript:getlegend(\''.$layer[0]['Gruppe'].'\', '.$this->formvars['layer_id'].', document.GUI.nurFremdeLayer.value);closeLayerOptions('.$this->formvars['layer_id'].')">';
								if($layer[0]['showclasses'])echo $this->HideClasses;
								else echo $this->DisplayClasses;
								echo '</a></li>';
							}
							for($c = 0; $c < count($layer[0]['Class']); $c++){
								$class_ids[] = $layer[0]['Class'][$c]['Class_ID'];
							}
							if($layer[0]['Class'][0]['Status'] == '1' || $layer[0]['Class'][1]['Status'] == '1')echo '<li><a href="javascript:deactivateAllClasses(\''.implode(',', $class_ids).'\')">'.$this->deactivateAllClasses.'</a></li>';
							if($layer[0]['Class'][0]['Status'] == '0' || $layer[0]['Class'][1]['Status'] == '0')echo '<li><a href="javascript:activateAllClasses(\''.implode(',', $class_ids).'\')">'.$this->activateAllClasses.'</a></li>';
						}
						if($layer[0]['connectiontype']==6 AND ($this->formvars['layer_id'] < 0 OR $layer[0]['original_labelitem'] != '')){		# für Rollenlayer oder normale Layer mit labelitem
							echo '<li><span>'.$this->label.':</span>
											<select name="layer_options_labelitem">
												<option value=""> - '.$this->noLabel.' - </option>';
												if($this->formvars['layer_id'] > 0){
													echo '<option value="'.$layer[0]['original_labelitem'].'" '.($layer[0]['labelitem'] == $layer[0]['original_labelitem'] ? 'selected' : '').'>'.$layer[0]['original_labelitem'].'</option>';
												}
												for($i = 0; $i < count($attributes)-2; $i++){
													if(($this->formvars['layer_id'] < 0 OR ($privileges[$attributes[$i]['name']] != '' AND $attributes[$i]['name'] != $layer[0]['original_labelitem'])) AND $attributes['the_geom'] != $attributes[$i]['name'])echo '<option value="'.$attributes[$i]['name'].'" '.($layer[0]['labelitem'] == $attributes[$i]['name'] ? 'selected' : '').'>'.$attributes[$i]['name'].'</option>';
												}
							echo 	 '</select>
										</li>';
						}
						echo '<li><span>'.$this->transparency.':</span> <input name="layer_options_transparency" onchange="transparency_slider.value=parseInt(layer_options_transparency.value);" style="width: 30px" value="'.$layer[0]['transparency'].'"><input type="range" id="transparency_slider" name="transparency_slider" style="height: 6px; width: 120px" value="'.$layer[0]['transparency'].'" onchange="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()" oninput="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()"></li>';
						if(ROLLENFILTER AND $this->user->rolle->showrollenfilter){
							echo '	
									<li>
									<a href="javascript:void(0);" onclick="$(\'#rollenfilter, #rollenfilterquestionicon\').toggle()">Filter</a>
									<a href="javascript:void(0);" onclick="message(\'\
										Sie können im Textfeld einen SQL-Ausdruck eintragen, der sich als Filter auf die Kartendarstellung und Sachdatenanzeige des Layers auswirkt.<br>\
										In diesem Thema stehen dafür folgende Attribute zur Verfügung:<br>\
										<ul>';
										for($i = 0; $i < count($attributes)-2; $i++){
											if(($this->formvars['layer_id'] < 0 OR $privileges[$attributes[$i]['name']] != '') AND $attributes['the_geom'] != $attributes[$i]['name'])echo '<li>'.$attributes[$i]['name'].'</li>';
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
												'.($layer[0]['rollenfilter'] == ''? 'display: none' : '').'
											"
										></i>
									</a><br>
									<textarea
										id="rollenfilter"
										style="
											width: 98%;
											'.($layer[0]['rollenfilter'] == ''? 'display: none' : '').'
										"
										name="layer_options_rollenfilter"
									>' . $layer[0]['rollenfilter'] . '</textarea>
								</li>';
						}
echo '			</ul>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="0">
							<tr>';
							if($this->formvars['layer_id'] > 0){
				echo '	<td>
									<input type="button" onmouseup="resetLayerOptions('.$this->formvars['layer_id'].')" value="'.$this->strReset.'">
								</td>';}
				echo '	<td>
									<input type="button" onmouseup="saveLayerOptions('.$this->formvars['layer_id'].')" value="'.$this->strSave.'">
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
		posy = document.getElementById(\'options_'.$this->formvars['layer_id'].'\').getBoundingClientRect().top;
		if(posy > legend_bottom - 150)posy = legend_bottom - 150;
		document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').style.top = document.getElementById(\'map\').offsetTop + posy - (13+legend_top);
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

	function saveLegendOptions(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $mapDB->read_Layer(0, $this->Stelle->useLayerAliases, NULL);     # class_load_level: 0 = keine Klassen laden
		$this->user->rolle->saveLegendOptions($layerset, $this->formvars);
		$this->resizeMap2Window();
		$this->user->rolle->readSettings();
		$this->neuLaden();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function resetLegendOptions(){
		$this->user->rolle->removeDrawingOrders();
		$this->neuLaden();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function saveLayerOptions(){
		$this->user->rolle->setTransparency($this->formvars);
		$this->user->rolle->setLabelitem($this->formvars);
		$this->user->rolle->setRollenFilter($this->formvars);
		if ($this->formvars['layer_options_open'] < 0) { # Rollenlayer 
			$this->user->rolle->setRollenLayerName($this->formvars);
			# bei Bedarf Label anlegen
			$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			$classes = $mapDB->read_Classes($this->formvars['layer_options_open']);
			if($classes[0]['Label'] == NULL){
				$empty_label = new stdClass();
				$empty_label->font = 'arial';
				$empty_label->size = '8';
				$empty_label->minsize = '6';
				$empty_label->maxsize = '10';
				$empty_label->position = '6';
				$new_label_id = $mapDB->new_Label($empty_label);
				$mapDB->addLabel2Class($classes[0]['Class_ID'], $new_label_id, 0);
			}
		}
		$this->neuLaden();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function resetLayerOptions(){
		$this->user->rolle->removeTransparency($this->formvars);
		$this->user->rolle->removeLabelitem($this->formvars);
		$this->neuLaden();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function switch_gle_view(){
		$this->user->rolle->switch_gle_view($this->formvars['chosen_layer_id']);
		$this->last_query = $this->user->rolle->get_last_query();
		$this->formvars['go'] = $this->last_query['go'];
		if($this->formvars['go'] == 'Layer-Suche_Suchen')$this->GenerischeSuche_Suchen();
		else $this->queryMap();
	}

	function setHistTimestamp(){
		$this->user->rolle->setHistTimestamp($this->formvars['timestamp'], $this->formvars['go_next']);
		$this->user->rolle->readSettings();
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->loadMap('DataBase');
		$this->drawMap();
		$this->output();
	}

	function setLanguage(){
		$this->user->rolle->setLanguage($this->formvars['language']);
		$this->user->rolle->readSettings();
		$this->loadMultiLingualText($this->user->rolle->language);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->loadMap('DataBase');
		$this->drawMap();
		$this->output();
	}

	function setLayerParams($prefix = '') {
		$layer_params = array();
		foreach ($this->formvars AS $key => $value) {
			$param_key = str_replace($prefix . 'layer_parameter_', '', $key);		// $prefix dient zur Unterscheidung zwischen den Layer-Parametern im Header und denen in den Optionen
			if ($param_key != $key) {
				$layer_params[] = '"' . $param_key . '":"' . $value . '"';
			}
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

	function get_group_legend() {
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    $this->user->rolle->setClassStatus($this->formvars);
    $this->loadMap('DataBase');
    echo $this->create_group_legend($this->formvars['group']);
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

	function create_group_legend($group_id){
		$layerlist = $this->layerset['list'];
		if($this->groupset[$group_id]['untergruppen'] == NULL AND $this->layerset['layers_of_group'][$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
    $groupname = $this->groupset[$group_id]['Gruppenname'];
	  $groupstatus = $this->groupset[$group_id]['status'];
    $legend .=  '
	  <div id="groupdiv_'.$group_id.'" style="width:100%">
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
				<tr>
					<td>
						<input id="group_' . $group_id . '" name="group_' . $group_id . '" type="hidden" value="' . $groupstatus . '">
						<a href="javascript:getlegend(\'' . $group_id . '\', \'\', document.GUI.nurFremdeLayer.value)">
							<img border="0" id="groupimg_' . $group_id . '" src="graphics/' . ($groupstatus == 1 ? 'minus' : 'plus') . '.gif">&nbsp;
						</a>
						<span class="legend_group' . ($this->group_has_active_layers[$group_id] != '' ? '_active_layers' : '') . '">
							<!--a
								href="javascript:getGroupOptions(' . $group_id . ')"
								onmouseover="$(\'#test_' . $group_id . '\').show()"
								onmouseout="$(\'#test_' . $group_id . '\').hide()"
							>' . html_umlaute($groupname) . '
								<i id="test_' . $group_id . '" class="fa fa-bars" style="display: none;"></i>
							</a//-->' .
							html_umlaute($groupname) . '
							'.($groupname == 'Suchergebnis' ? '<a href="javascript:deleteRollenlayer(\'search\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							'.(($groupname == 'Eigene Importe' OR $groupname == 'WMS-Importe') ? '<a href="javascript:deleteRollenlayer(\'import\');"><i class="fa fa-trash pointer" title="alle entfernen"></i></a>' : '').'
							<div style="position:static;" id="group_options_' . $group_id . '"></div>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<div id="layergroupdiv_'.$group_id.'" style="width:100%;'.(($groupstatus != 1 AND $this->group_has_active_layers[$group_id] != '') ? 'display: none' : '').'"><table cellspacing="0" cellpadding="0">';
		$layercount = count($this->layerset['layers_of_group'][$group_id]);
		if($groupstatus == 1 OR $this->group_has_active_layers[$group_id]){		# Gruppe aufgeklappt oder hat aktive Layer
			for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
				$legend .= '<tr><td colspan="3"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td><img src="'.GRAPHICSPATH.'leer.gif" width="13" height="1" border="0"></td><td style="width: 100%">';
				$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u]);
				$legend .= '</td></tr></table></td></tr>';
			}
			if($layercount > 0){		# Layer vorhanden
				if($this->layerset['list'][$this->layerset['layers_of_group'][$group_id][0]]['legendorder'] != ''){		# erster Layer hat eine Legendenreihenfolge -> sortieren
					usort($this->layerset['layers_of_group'][$group_id], function($a, $b) use ($layerlist) {
						return $layerlist[$a]['legendorder'] - $layerlist[$b]['legendorder'];
					});
				}
				else $this->layerset['layers_of_group'][$group_id] = array_reverse($this->layerset['layers_of_group'][$group_id]);		# umgedrehte Zeichenreihenfolge verwenden
				if(!$this->formvars['nurFremdeLayer']){
					$legend .=  '<tr>
												<td align="center">
													<input name="layers_of_group_'.$group_id.'" type="hidden" value="'.implode(',', $this->layer_ids_of_group[$group_id]).'">';
					if(!$this->user->rolle->singlequery) {
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
				for($j = 0; $j < $layercount; $j++){
					$layer = $this->layerset['list'][$this->layerset['layers_of_group'][$group_id][$j]];
					$legend .= $this->create_layer_legend($layer);
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.$this->radiolayers[$group_id].'">';
	  $legend .= '</div>';
    return $legend;
  }

	function create_layer_legend($layer, $requires = false){
		if(!$requires AND $layer['requires'] != '' OR $requires AND $layer['requires'] == '')return;
		global $legendicon_size;
		$visible = $this->check_layer_visibility($layer);
		# sichtbare Layer
		if ($visible) {
			if ($layer['requires'] == '') {
				$legend .= '<tr><td valign="top">';

				if ($layer['queryable'] == 1 AND !$this->formvars['nurFremdeLayer']) {
					$input_attr['id'] = 'qLayer' . $layer['Layer_ID'];
					$input_attr['name'] = 'qLayer' . $layer['Layer_ID'];
					$input_attr['title'] = ($layer['queryStatus'] == 1 ? $this->deactivatequery : $this->activatequery);
					$input_attr['value'] = 1;
					$input_attr['class'] = 'info-select-field';
					$input_attr['type'] = (($this->user->rolle->singlequery or $layer['selectiontype'] == 'radio') ? 'radio' : 'checkbox');
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
							document.getElementById('thema_" . $layer['Layer_ID'] . "'),
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
							document.getElementById('thema_" . $layer['Layer_ID'] . "'),
							document.getElementById('qLayer" . $layer['Layer_ID'] . "')," .
							($layer['selectiontype'] == 'radio' ? "document.GUI.radiolayers_" . $layer['Gruppe'] : "''") . "," .
							($this->user->rolle->singlequery ? "document.GUI.layers" : "''") . "," .
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
					$legend .= '<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">';
				}
				$legend .=  '</td><td valign="top">';
				// die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat, damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
				$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="0">';

				$legend .=  '<input id="thema_'.$layer['Layer_ID'].'" ';
				if($layer['selectiontype'] == 'radio'){
					$legend .=  'type="radio" ';
					$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$layer['Gruppe'].', '.$this->user->rolle->instant_reload.')"';
					$this->radiolayers[$layer['Gruppe']] .= $layer['Layer_ID'].'|';
				}
				else{
					$legend .=  'type="checkbox" ';
					$legend .=  ' onClick="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', '.$this->user->rolle->instant_reload.')"';
				}
				$legend .=  ' name="thema'.$layer['Layer_ID'].'" value="1" ';
				if($layer['aktivStatus'] == 1){
					$legend .=  'checked title="'.$this->deactivatelayer.'"';
				}
				else{
					$legend .=  ' title="'.$this->activatelayer.'"';
				}
				$legend .= ' ></td><td valign="middle">';

				$legend .= '<a';
				# Bei eingeschalteter Rollenoption Layeroptionen anzeigen wird das Optionsfeld mit einem Rechtsklick geöffnet.
				if ($this->user->rolle->showlayeroptions) {
					$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
				}
				if($layer['metalink'] != ''){
					if(substr($layer['metalink'], 0, 10) != 'javascript'){
						$legend .= ' target="_blank"';
						if(strpos($layer['metalink'], '?') === false)$layer['metalink'] .= '?';
						else $layer['metalink'] .= '&';
						$layer['metalink'] .= 'time='.time();
					}
					$legend .= ' class="metalink boldhover" href="'.$layer['metalink'].'">';
				}
				else
					$legend .= ' class="visiblelayerlink boldhover" href="javascript:void(0)">';
				$legend .= '<span id="'.str_replace('"', '', str_replace("'", '', str_replace('-', '_', $layer['alias']))).'"';
				if($layer['minscale'] != -1 AND $layer['maxscale'] > 0){
					$legend .= ' title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
				}
				$legend .=' >'.html_umlaute($layer['alias']).'</span>';
				$legend .= '</a>';

				# Bei eingeschalteten Layern und eingeschalteter Rollenoption ist ein Optionen-Button sichtbar
				if ($layer['aktivStatus'] == 1 and $this->user->rolle->showlayeroptions) {
					$legend .= '&nbsp';
					if ($layer['rollenfilter'] != '') {
						$legend .= '<a href="javascript:void(0);" onclick="getLayerOptions('.$layer['Layer_ID'].');">
							<i class="fa fa-filter button layerOptionsIcon" title="' . $layer['rollenfilter'] . '"></i>
						</a>';
					}
					$legend .= '<a href="javascript:getLayerOptions('.$layer['Layer_ID'].')">
						<i class="fa fa-bars pointer button layerOptionsIcon" title="'.$this->layerOptions.'"></i>
					</a>';
				}
				$legend.='<div style="position:static" id="options_'.$layer['Layer_ID'].'"> </div>';
			}
			if($layer['aktivStatus'] == 1 AND $layer['Class'][0]['Name'] != ''){
				if($layer['requires'] == '' AND $layer['Layer_ID'] > 0){
					$legend .= '<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
				}
				if ($layer['showclasses'] != 0) {
					if($layer['connectiontype'] == 7){      # WMS
						if($layer['Class'][$k]['legendgraphic'] != ''){
							$imagename = $original_class_image = CUSTOM_PATH . 'graphics/' . $layer['Class'][$k]['legendgraphic'];
							$legend .=  '<div style="display:inline" id="lg'.$j.'_'.$l.'"><img src="'.$imagename.'"></div><br>';
						}
						else{
							$layersection = substr($layer['connection'], strpos(strtolower($layer['connection']), 'layers')+7);
							$pos = strpos($layersection, '&');
							if($pos !== false)$layersection = substr($layersection, 0, $pos);
							$layers = explode(',', $layersection);
							for($l = 0; $l < count($layers); $l++){
								$legend .=  '<div style="display:inline" id="lg'.$j.'_'.$l.'"><img src="'.$layer['connection'].'&layer='.$layers[$l].'&service=WMS&request=GetLegendGraphic" onerror="ImageLoadFailed(this)"></div><br>';
							}
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
							for($s = 0; $s < $class->numstyles; $s++){
								$style = $class->getStyle($s);
								if($maplayer->type > 0){
									$symbol = $this->map->getSymbolObjectById($style->symbol);
									if($symbol->type == 1006){ 	# 1006 == hatch
										$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
										$style->set('maxsize', 2*$style->width);
									}
									elseif($style->symbolname == ''){
										$style->set('size', 2);					# size und width bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
										$style->set('maxsize', 2);
										$style->set('width', 2);
										$style->set('maxwidth', 2);
									}
								}
								else{		# Punktlayer
									if($style->size > 14)$style->set('size', 14);
									$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									$style->set('minsize', $style->size);		# minsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
									if($class->numstyles == 1){							# wenn es nur einen Style in der Klasse gibt, die Offsets auf 0 setzen, damit man was in der Legende erkennt
										$style->set('offsety', 0);
										$style->set('offsetx', 0);
									}
								}
							}
							$legend .= '<tr style="line-height: 15px"><td style="line-height: 14px">';
							if($s > 0){
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
									}
									else{																												# vom Mapserver generiertes Klassenbild
										$image = $class->createLegendIcon($width, $height);
										$filename = $this->map_saveWebImage($image,'jpeg');
										$newname = $this->user->id.basename($filename);
										rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
									}
									$imagename = $original_class_image = TEMPPATH_REL.$newname;
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
								$legend .= '<input type="hidden" size="2" name="class'.$classid.'" value="'.$status.'"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.')" onmouseout="mouseOutClassStatus('.$classid.',\''.$original_class_image.'\', '.$width.', '.$height.')" onclick="changeClassStatus('.$classid.',\''.$original_class_image.'\', '.$this->user->rolle->instant_reload.', '.$width.', '.$height.')"><img style="vertical-align:middle;padding-bottom: '.$padding.'" border="0" name="imgclass'.$classid.'" width="'.$width.'" height="'.$height.'" src="'.$imagename.'"></a>';
							}
							$legend .= '&nbsp;<span class="px13">'.html_umlaute($class->name).'</span></td></tr>';
						}
						$legend .= '</table>';
					}
				}
			}
			if($j+1 < $count AND $current_groupgetMetaData_off_requires != 1){		// todo
				$legend .= '</td></tr>';
			}
		}

		# unsichtbare Layer
		if($layer['requires'] == '' AND !$visible){
			$legend .=  '
						<tr>
							<td valign="top">';
			if($layer['queryable'] == 1){
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
			$legend .=  '<input type="hidden" id="thema'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" value="'.$layer['aktivStatus'].'">';
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
			$legend .= 'id="thema_'.$layer['Layer_ID'].'" name="thema'.$layer['Layer_ID'].'" disabled="true"></td><td>';
			$legend .= '<a ';
			if ($this->user->rolle->showlayeroptions) {
				$legend .= ' oncontextmenu="getLayerOptions(' . $layer['Layer_ID'] . '); return false;"';
			}
			$legend .= 'class="invisiblelayerlink boldhover" href="javascript:void(0)">';
			$legend .= '<span class="legend_layer_hidden" id="'.str_replace('-', '_', $layer['alias']).'"';
			if($layer['minscale'] != -1 AND $layer['maxscale'] != -1){
				$legend .= 'title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
			}
			$legend .= ' >'.html_umlaute($layer['alias']).'</span></a>';
			$legend.='<div style="position:static" id="options_'.$layer['Layer_ID'].'"> </div>';
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
		
		# requires-Layer
		if($layer['required'] != ''){
			foreach($layer['required'] as $require_layer_id){
				$legend .= $this->create_layer_legend($this->layerset['layer_ids'][$require_layer_id], true);
			}
		}		
		return $legend;
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

	function changemenue($id, $status){
    if($status == 'on'){
			if($this->user->rolle->menu_auto_close == 1){		# alle Obermenüpunkte schliessen
				$sql ='UPDATE u_menue2rolle SET `status` = 0 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id;
				$this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql,4);
				$query=mysql_query($sql);
				if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			}
      $sql ='UPDATE u_menue2rolle SET `status` = 1 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
    elseif($status == 'off'){
      $sql ='UPDATE u_menue2rolle SET `status` = 0 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id;
			if($id != '')$sql .= ' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>" . $sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
  }

	function list_subgroups($groupid){
		if($groupid != ''){
			$group = $this->groupset[$groupid];
			if($group['untergruppen'] != ''){
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
			$wkt = "POLYGON((" . $parts[0]."))";
			$style = $parts[1];
			$this->addFeatureLayer('free_polygon'.$i, MS_LAYER_POLYGON, array($wkt), NULL, $style, $this->map_factor);
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
		$layer = ms_newLayerObj($this->map);
		$layer->set('name', $name);
		$layer->set("status", MS_ON);
    $layer->set("type", $type);
		$i = 0;
		foreach($features as $feature){
			$shape = ms_shapeObjFromWkt($feature);
			$shape->set('text', $texts[$i]);
			$layer->addFeature($shape);
			$i++;
		}
		$class = ms_newClassObj($layer);
		if($texts != NULL){
			$label = new labelObj();
			$label->set('size', 10*$map_factor);
			$label->color->setRGB(255, 0, 0);
			$label->set('type', 'TRUETYPE');
			$label->set('font', 'arial');
			$label->set('position', MS_LR);
			$label->set('offsety', -9);
			$class->addLabel($label);
		}
    $style = ms_newStyleObj($class);
		$css_styles = explode(';', str_replace(' ', '', $css_style_string));
		foreach($css_styles as $css_style){
			$parts = explode(':', $css_style);
			$property = $parts[0];
			$value = $parts[1];
			switch($property){
				case 'opacity' : {
					$layer->set('opacity', $value * 100);
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

  function loadMap($loadMapSource) {
    $this->debug->write("<p>Funktion: loadMap('" . $loadMapSource."','" . $connStr."')",4);
    switch ($loadMapSource) {
      # lade Karte aus Post-Parametern
      case 'Post' : {
        if (MAPSERVERVERSION < 600) {
				  $map = ms_newMapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				else {
				  $map = new mapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				echo '<br>MapServer Version: '.ms_GetVersionInt();
				echo '<br>Details: '.ms_GetVersion();

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
        $map->setProjection('+init='.strtolower($this->formvars['post_epsg']),MS_TRUE);

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);

        # OWS Metadaten
        $map->setMetaData('ows_title', 'WMS Ausdruck');
        $map->setMetaData('wms_extent',$this->formvars['post_minx'].''.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);

        # Legendobject
        $map->legend->set('status', MS_ON);
        #$map->legend->set('transparent', MS_OFF);
        $map->legend->set('keysizex', '16');
        $map->legend->set('keysizey', '16');
        $map->legend->set('template', LAYOUTPATH.'legend_layer.htm');
        $map->legend->imagecolor -> setRGB(255,255,255);
        $map->legend->outlinecolor -> setRGB(-1,-1,-1);
        $map->legend->label->set('type', MS_TRUETYPE);
        $map->legend->label->set('font', 'arial');
        $map->legend->label->set('size', 12);
        $map->legend->label->color->setRGB(5,30,220);

        # layer
        if (is_array($this->formvars['layer'])) {
          $layerset=array_values($this->formvars['layer']);
        }
        else {
          $layerset=array();
        }
        for ($i=0; $i<count($layerset); $i++) {
				  if (MAPSERVERVERSION < 600) {
            $layer = ms_newLayerObj($map);
          }
					else {
					  $layer = new layerObj($map);
					}
					$layer->setMetaData('wms_name', $layerset[$i][name]);
          $layer->setMetaData('wms_server_version','1.1.1');
          $layer->setMetaData('wms_format','image/png');
          $layer->setMetaData('wms_extent',$this->formvars['post_minx'].' '.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);
          $layer->setMetaData('ows_title', $layerset[$i][name]);
          if($layerset[$i][epsg_code] != ''){
            $layer->setMetaData('ows_srs', $layerset[$i][epsg_code]);
          }
          else{
            $layer->setMetaData('ows_srs', $this->formvars['post_epsg']);
          }
          $layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_inimage');
          $layer->setMetaData('real_layer_status', 1);
          $layer->setMetaData('off_requires',0);
          $layer->setMetaData('wms_connectiontimeout',60);
          $layer->setMetaData('wms_queryable',0);
          $layer->setMetaData('wms_group_title','WMS');
          $layer->set('type', 3);
          $layer->set('name', $layerset[$i][name]);
          $layer->set('status', 1);
          if($this->map_factor == ''){
            $this->map_factor=1;
          }
          if($layerset[$i]['maxscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('maxscale', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
          }
          if($layerset[$i]['minscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('minscale', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
          }
          if($layerset[$i][epsg_code] != ''){
            $layer->setProjection('+init='.strtolower($layerset[$i][epsg_code])); # recommended
          }
          else{
            $layer->setProjection('+init='.strtolower($this->formvars['post_epsg']));
          }

					#$layer->set('connection',"http://www.kartenserver.niedersachsen.de/wmsconnector/com.esri.wms.Esrimap/Biotope?LAYERS=7&REQUEST=GetMap&TRANSPARENT=true&FORMAT=image/png&SERVICE=WMS&VERSION=1.1.1&STYLES=&EXCEPTIONS=application/vnd.ogc.se_xml&SRS=EPSG:31467");
					#echo '<br>Name: '.$layerset[$i][name];
					$layer->set(
						'connection',
						replace_params(
							$layerset[$i][connection],
							rolle::$layer_params,
							$this->user->id,
							$this->Stelle->id,
							rolle::$hist_timestamp,
							$this->user->rolle->language
						)
					);
					#echo '<br>Connection: ' . replace_params($layerset[$i][connection], rolle::$layer_params);
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
        $debug->write("MapDatei $connStr laden",4);
				if (MAPSERVERVERSION < 600) {
          $this->map = ms_newMapObj(DEFAULTMAPFILE);
        }
				else {
				  $this->map = new mapObj(DEFAULTMAPFILE);
				}
			} break;

      # lade Karte von Datenbank
      case 'DataBase' : {
				if (MAPSERVERVERSION < 600) {
							$map = ms_newMapObj(DEFAULTMAPFILE);
						}
				else {
					$map = new mapObj(DEFAULTMAPFILE, SHAPEPATH);
				}
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);

        # Allgemeine Parameter
        $map->set('width',$this->user->rolle->nImageWidth);
        $map->set('height',$this->user->rolle->nImageHeight);
        $map->set('resolution',96);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->set('debug', MS_ON);
        $map->imagecolor->setRGB(255,255,255);
        $map->maxsize = 4096;
        $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_TRUE);

				$bb=$this->Stelle->MaxGeorefExt;

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if($this->user->rolle->oGeorefExt->minx==='') {
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
					if($this->formvars['go'] != 'OWS'){
						$map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
					}
					else{
						$map->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
					}
				}

        # OWS Metadaten

        if($this->Stelle->ows_title != ''){
          $map->setMetaData("ows_title",$this->Stelle->ows_title);}
        else{
          $map->setMetaData("ows_title",OWS_TITLE);
        }
        if($this->Stelle->ows_abstract != ''){
          $map->setMetaData("ows_abstract", $this->Stelle->ows_abstract);}
        else{
          $map->setMetaData("ows_abstract", OWS_ABSTRACT);
        }
        if($this->Stelle->wms_accessconstraints != ''){
          $map->setMetaData("ows_accessconstraints",$this->Stelle->wms_accessconstraints);}
        else{
          $map->setMetaData("ows_accessconstraints",OWS_ACCESSCONSTRAINTS);
        }
        if($this->Stelle->ows_contactperson != ''){
          $map->setMetaData("ows_contactperson",$this->Stelle->ows_contactperson);}
        else{
          $map->setMetaData("ows_contactperson",OWS_CONTACTPERSON);
        }
        if($this->Stelle->ows_contactorganization != ''){
          $map->setMetaData("ows_contactorganization",$this->Stelle->ows_contactorganization);}
        else{
          $map->setMetaData("ows_contactorganization",OWS_CONTACTORGANIZATION);
        }
        if($this->Stelle->ows_contactelectronicmailaddress != ''){
          $map->setMetaData("ows_contactelectronicmailaddress",$this->Stelle->ows_contactelectronicmailaddress);}
        else{
          $map->setMetaData("ows_contactelectronicmailaddress",OWS_CONTACTELECTRONICMAILADDRESS);
        }
        if($this->Stelle->ows_contactposition != ''){
          $map->setMetaData("ows_contactposition",$this->Stelle->ows_contactposition);}
        else{
          $map->setMetaData("ows_contactposition",OWS_CONTACTPOSITION);
        }

				$map->setMetaData("ows_encoding", 'UTF-8');
				$map->setMetaData("ows_keywordlist", OWS_KEYWORDLIST);
				$map->setMetaData("ows_contactvoicetelephone", OWS_CONTACTVOICETELEPHONE);
				$map->setMetaData("ows_contactfacsimiletelephone", OWS_CONTACTFACSIMILETELEPHONE);
				$map->setMetaData("ows_addresstype", 'postal');
				$map->setMetaData("ows_address", OWS_ADDRESS);
				$map->setMetaData("ows_city", OWS_CITY);
				$map->setMetaData("ows_stateorprovince", OWS_STATEORPROVINCE);
				$map->setMetaData("ows_postcode", OWS_POSTCODE);
				$map->setMetaData("ows_country", OWS_COUNTRY);
				$map->setMetaData("ows_contactinstructions", OWS_CONTACTINSTRUCTIONS);
				$map->setMetaData("ows_hoursofservice", OWS_HOURSOFSERVICE);
				$map->setMetaData("ows_role", OWS_ROLE);

        if($this->Stelle->ows_fees != ''){
          $map->setMetaData("ows_fees",$this->Stelle->ows_fees);}
        else{
          $map->setMetaData("ows_fees",OWS_FEES);
        }
        if($this->Stelle->ows_srs != ''){
          $map->setMetaData("ows_srs",$this->Stelle->ows_srs);}
        else{
          $map->setMetaData("ows_srs",OWS_SRS);
        }
        if($_REQUEST['onlineresource'] != '')$ows_onlineresource = $_REQUEST['onlineresource'];
        else $ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . '&Stelle_ID=' . $this->Stelle->id .'&login_name=' . $_REQUEST['login_name'] . '&passwort=' .  $_REQUEST['passwort'];
        $map->setMetaData("ows_onlineresource", $ows_onlineresource);
				$map->setMetaData("ows_service_onlineresource", $ows_onlineresource);

        $map->setMetaData("wms_extent",$bb->minx.' '.$bb->miny.' '.$bb->maxx.' '.$bb->maxy);
				// enable service types
        $map->setMetaData("ows_enable_request", '*');

        ///------------------------------////

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Umrechnen des Stellenextents kann hier raus, weil es schon in start.php gemacht wird

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);
        $map->web->set('log', LOGPATH.'mapserver.log');
        $map->setMetaData('wms_feature_info_mime_type',  'text/html');
        //$map->web->set('ERRORFILE', LOGPATH.'mapserver_error.log');

        # Referenzkarte
				if(MAPSERVERVERSION < 600){
					$reference_map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$reference_map = new mapObj(DEFAULTMAPFILE);
				}
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
				$reference_map->web->set('imagepath', IMAGEPATH);
				$reference_map->setProjection('+init=epsg:'.$this->ref['epsg_code'], MS_FALSE);
				$reference_map->reference->extent->setextent(round($this->ref['xmin']),round($this->ref['ymin']),round($this->ref['xmax']),round($this->ref['ymax']));
				$reference_map->reference->set('image', $this->ref['refMapImg']);
        $reference_map->reference->set('width',$this->ref['width']);
        $reference_map->reference->set('height',$this->ref['height']);
        $reference_map->reference->set('status','MS_ON');
				if (MAPSERVERVERSION < 600) {
					$extent=ms_newRectObj();
				}
				else {
				  $extent = new rectObj();
				}
				$reference_map->reference->color->setRGB(-1,-1,-1);
				$reference_map->reference->outlinecolor->setRGB(255,0,0);

        # Scalebarobject
        $map->scalebar->set('status', MS_ON);
        $map->scalebar->set('units', MS_METERS);
        $map->scalebar->set('intervals', 4);
        $map->scalebar->color->setRGB(0,0,0);
        $r = substr(BG_MENUETOP, 1, 2);
        $g = substr(BG_MENUETOP, 3, 2);
        $b = substr(BG_MENUETOP, 5, 2);
        $map->scalebar->imagecolor->setRGB(hexdec($r), hexdec($g), hexdec($b));
        $map->scalebar->outlinecolor->setRGB(0,0,0);
				$map->scalebar->label->type = 'truetype';
				$map->scalebar->label->font = 'SourceSansPro';
				$map->scalebar->label->size = 10.5;

        # Groups
        if($this->formvars['nurAufgeklappteLayer'] == ''){
	        $this->groupset=$mapDB->read_Groups();
        }

        # Layer
				$mapDB->nurAktiveLayer = $this->formvars['nurAktiveLayer'];
        $mapDB->nurAufgeklappteLayer=$this->formvars['nurAufgeklappteLayer'];
        $mapDB->nurFremdeLayer=$this->formvars['nurFremdeLayer'];
        if($this->class_load_level == ''){
          $this->class_load_level = 1;
        }
        $layerset = $mapDB->read_Layer($this->class_load_level, $this->Stelle->useLayerAliases, $this->list_subgroups($this->formvars['group']));     # class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
        $rollenlayer = $mapDB->read_RollenLayer();
        $layerset['list'] = array_merge($layerset['list'], $rollenlayer);
        $layerset['anzLayer'] = count($layerset['list']);
        unset($this->layer_ids_of_group);		# falls loadmap zweimal aufgerufen wird
        for($i=0; $i < $layerset['anzLayer']; $i++){
					$layerset['layers_of_group'][$layerset['list'][$i]['Gruppe']][] = $i;
					if($layerset['list'][$i]['requires'] == ''){
						$this->layer_ids_of_group[$layerset['list'][$i]['Gruppe']][] = $layerset['list'][$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset['list'][$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if($layerset['list'][$i]['requires'] != ''){
						$layerset['list'][$i]['aktivStatus'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['aktivStatus'];
						$layerset['list'][$i]['showclasses'] = $layerset['layer_ids'][$layerset['list'][$i]['requires']]['showclasses'];
					}

					if($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset['list'][$i]['aktivStatus'] != 0)){      # nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layerset['list'][$i]['layer_index_mapobject'] = $map->numlayers;
						$this->loadlayer($map, $layerset['list'][$i]);
          }
        }
				$this->layerset = $layerset;
        $this->map=$map;
				$this->reference_map = $reference_map;
				if (MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $map->scaledenom;
				}
				else {
					$this->map_scaledenom = $map->scale;
				}
        $this->mapDB=$mapDB;
      } break; # end of lade Karte von Datenbank
    } # end of switch loadMapSource
    return 1;
  }

	function loadlayer($map, $layerset){
		$layer = ms_newLayerObj($map);
		$layer->setMetaData('wfs_request_method', 'GET');
		$layer->setMetaData('wms_name', $layerset['wms_name']);
		if($layerset['wms_keywordlist'])$layer->setMetaData('ows_keywordlist', $layerset['wms_keywordlist']);
		$layer->setMetaData('wfs_typename', $layerset['wms_name']);
		$layer->setMetaData('ows_title', $layerset['Name']); # required
		$layer->setMetaData('wms_group_title',$layerset['Gruppenname']);
		$layer->setMetaData('wms_queryable',$layerset['queryable']);
		$layer->setMetaData('wms_format',$layerset['wms_format']);
		$layer->setMetaData('ows_server_version',$layerset['wms_server_version']);
		$layer->setMetaData('ows_version',$layerset['wms_server_version']);
		if($layerset['metalink']){
			$layer->setMetaData('ows_metadataurl_href',$layerset['metalink']);
			$layer->setMetaData('ows_metadataurl_type', 'ISO 19115');
			$layer->setMetaData('ows_metadataurl_format', 'text/plain');
		}
		if($layerset['ows_srs'] == '') $layerset['ows_srs'] = 'EPSG:' . $layerset['epsg_code'];
		$layer->setMetaData('ows_srs', $layerset['ows_srs']);
		$layer->setMetaData('wms_connectiontimeout',$layerset['wms_connectiontimeout']);
		$layer->setMetaData('ows_auth_username', $layerset['wms_auth_username']);
		$layer->setMetaData('ows_auth_password', $layerset['wms_auth_password']);
		$layer->setMetaData('ows_auth_type', 'basic');
		$layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_xml');
		# ToDo: das Setzen von ows_extent muss in dem System erfolgen, in dem der Layer definiert ist (erstmal rausgenommen)
		#$layer->setMetaData("ows_extent", $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);		# führt beim WebAtlas-WMS zu einem Fehler
		$layer->setMetaData("gml_featureid", "ogc_fid");
		$layer->setMetaData("gml_include_items", "all");

		$layer->set('dump', 0);
		$layer->set('type',$layerset['Datentyp']);
		$layer->set('group',$layerset['Gruppenname']);

		$layer->set('name', $layerset['alias']);

		if($layerset['status'] != ''){
			$layerset['aktivStatus'] = 0;
		}

		//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
		//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
		//---- der layer_status auf 0 gesetzt werden//
		if($layerset['aktivStatus'] == 0){
		$layer->set('status', 0);
		}
		else{
		$layer->set('status', 1);
		}
		$layer->set('debug',MS_ON);

		# fremde Layer werden auf Verbindung getestet
		if (
			$layerset['aktivStatus'] != 0 AND
			$layerset['connectiontype'] == 6 AND
			strpos($layerset['connection'], 'host') !== false AND
			strpos($layerset['connection'], 'host=localhost') === false AND
			strpos($layerset['connection'], 'host=pgsql') === false
		) {
			$connection = explode(' ', trim($layerset['connection']));
			for ($j = 0; $j < count($connection); $j++) {
				if ($connection[$j] != '') {
					$value = explode('=', $connection[$j]);
					if (strtolower($value[0]) == 'host') {
						$host = $value[1];
					}
					if(strtolower($value[0]) == 'port') {
						$port = $value[1];
					}
				}
			}
			if ($port == '') $port = '5432';
			$fp = @fsockopen($host, $port, $errno, $errstr, 5);
			if(!$fp) {			# keine Verbindung --> Layer ausschalten
				$layer->set('status', 0);
				$layer->setMetaData('queryStatus', 0);
				$this->Fehlermeldung = $errstr.' für Layer: '.$layerset['Name'].'<br>';
			}
		}

		if($layerset['aktivStatus'] != 0){
			$collapsed = false;
			$group = $this->groupset[$layerset['Gruppe']];				# die Gruppe des Layers
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

		if(!$this->noMinMaxScaling AND $layerset['minscale']>=0) {
			if($this->map_factor != ''){
				$layer->set('minscaledenom', $layerset['minscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('minscaledenom', $layerset['minscale']);
			}
		}
		if(!$this->noMinMaxScaling AND $layerset['maxscale']>0) {
			if($this->map_factor != ''){
				$layer->set('maxscaledenom', $layerset['maxscale']/$this->map_factor*1.414);
			}
			else{
				$layer->set('maxscaledenom', $layerset['maxscale']);
			}
		}
		$layer->setProjection('+init=epsg:'.$layerset['epsg_code']); # recommended
		if ($layerset['connection']!='') {
			if ($this->map_factor != '' AND $layerset['connectiontype'] == 7) {		# WMS-Layer
				if ($layerset['printconnection']!=''){
					$layerset['connection'] = $layerset['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
				}
				else{
					//$layerset['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
				}
			}
			if ($layerset['connectiontype'] == 6) {
				# z.B. für Klassen mit Umlauten
				$layerset['connection'] .= " options='-c client_encoding=".MYSQL_CHARSET."'";
			}
			$layer->set('connection', $layerset['connection']);
		}

		if ($layerset['connectiontype'] > 0) {
			$layer->setConnectionType($layerset['connectiontype']);			
		}

		if ($layerset['connectiontype'] == 6) {
			$layerset['processing'] = 'CLOSE_CONNECTION=DEFER;' . $layerset['processing'];		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
		}

		if ($layerset['processing'] != "") {
			$processings = explode(";",$layerset['processing']);
			foreach ($processings as $processing) {
				$layer->setProcessing($processing);
			}
		}

		if ($layerset['postlabelcache'] != 0) {
			$layer->set('postlabelcache',$layerset['postlabelcache']);
		}

		if($layerset['Datentyp'] == MS_LAYER_POINT AND $layerset['cluster_maxdistance'] != ''){
			$layer->cluster->maxdistance = $layerset['cluster_maxdistance'];
			$layer->cluster->region = 'ellipse';
		}

		if ($layerset['Datentyp']=='3') {
			if($layerset['transparency'] != ''){
				$layer->set('opacity',$layerset['transparency']);				
			}
			if ($layerset['tileindex']!='') {
				$layer->set('tileindex',SHAPEPATH.$layerset['tileindex']);
			}
			else {
				$layer->set('data', $layerset['Data']);
			}
			$layer->set('tileitem',$layerset['tileitem']);
			if ($layerset['offsite']!='') {
				$RGB=explode(' ',$layerset['offsite']);
				$layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
			}
		}
		else {
			# Vektorlayer
			if($layerset['Data'] != '') {
				$layerset['Data'] = replace_params(
					$layerset['Data'],
					rolle::$layer_params,
					$this->user->id,
					$this->Stelle->id,
					rolle::$hist_timestamp,
					$this->user->rolle->language
				);
				$data = $layerset['Data'];
				$layer->set('data', $data);
			}

			# Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
			# Template (Body der Anzeige)
			if ($this->formvars['go'] == 'OWS') {
				$layer->set('template', 'dummy');
			}
			# Header (Kopfdatei)
			if ($layerset['header']!='') {
				$layer->set('header',$layerset['header']);
			}
			# Footer (Fusszeile)
			if ($layerset['footer']!='') {
				$layer->set('footer',$layerset['footer']);
			}
			# Setzen der Spalte nach der der Layer klassifiziert werden soll
			if ($layerset['classitem']!='') {
				$layer->set(
					'classitem',
					replace_params(
						$layerset['classitem'],
						rolle::$layer_params,
						$this->user->id,
						$this->Stelle->id,
						rolle::$hist_timestamp,
						$this->user->rolle->language
					)
				);
			}
			else {
				#$layer->set('classitem','id');
			}
			# Setzen des Filters
			if($layerset['Filter'] != ''){
				$layerset['Filter'] = str_replace('$userid', $this->user->id, $layerset['Filter']);
			 if (substr($layerset['Filter'],0,1)=='(') {
				 $expr=$layerset['Filter'];
			 }
			 else {
				 $expr=buildExpressionString($layerset['Filter']);
			 }
			 $layer->setFilter($expr);
			}
			# Layerweite Labelangaben
			if ($layerset['labelitem']!='') {
				$layer->set('labelitem',$layerset['labelitem']);
			}
			if ($layerset['labelmaxscale']!='') {
				$layer->set('labelmaxscaledenom',$layerset['labelmaxscale']);
			}
			if ($layerset['labelminscale']!='') {
				$layer->set('labelminscaledenom',$layerset['labelminscale']);
			}
			if ($layerset['labelrequires']!='') {
				$layer->set('labelrequires',$layerset['labelrequires']);
			}
			if ($layerset['tolerance']!='3') {
				$layer->set('tolerance',$layerset['tolerance']);
			}
			if ($layerset['toleranceunits']!='pixels') {
				$layer->set('toleranceunits',$layerset['toleranceunits']);
			}
			if ($layerset['transparency']!=''){
				if ($layerset['transparency']==-1) {
						$layer->set('opacity',MS_GD_ALPHA);
				}
				else {
						$layer->set('opacity',$layerset['transparency']);
				}
			}
			if ($layerset['symbolscale']!='') {
				if($this->map_factor != ''){
					$layer->set('symbolscaledenom',$layerset['symbolscale']/$this->map_factor*1.414);
				}
				else{
					$layer->set('symbolscaledenom',$layerset['symbolscale']);
				}
			}
		} # ende of Vektorlayer
		$classset=$layerset['Class'];
		$this->loadclasses($layer, $layerset, $classset, $map);
	}

  function loadclasses($layer, $layerset, $classset, $map){
    $anzClass=count($classset);
    for ($j=0;$j<$anzClass;$j++) {
      $klasse = ms_newClassObj($layer);
      if ($classset[$j]['Name']!='') {
        $klasse -> set('name',$classset[$j]['Name']);
      }
      if($classset[$j]['Status']=='1'){
      	$klasse->set('status', MS_ON);
      }
      else{
      	$klasse->set('status', MS_OFF);
      }
      $klasse -> set('template', $layerset['template']);
      $klasse -> setexpression($classset[$j]['Expression']);
      if ($classset[$j]['text']!='') {
        $klasse -> settext($classset[$j]['text']);
      }
      if ($classset[$j]['legendgraphic'] != '') {
				$imagename = '../' . CUSTOM_PATH . 'graphics/' . $classset[$j]['legendgraphic'];
				$klasse->set('keyimage', $imagename);
			}
      for ($k=0;$k<count($classset[$j]['Style']);$k++) {
        $dbStyle=$classset[$j]['Style'][$k];
				if (MAPSERVERVERSION < 600) {
          $style = ms_newStyleObj($klasse);
        }
				else {
				  $style = new styleObj($klasse);
				}
				if($dbStyle['geomtransform'] != ''){
					$style->updateFromString("STYLE GEOMTRANSFORM '" . $dbStyle['geomtransform']."' END");
				}
				if($dbStyle['minscale'] != ''){
					$style->set('minscaledenom', $dbStyle['minscale']);
				}
				if($dbStyle['maxscale'] != ''){
					$style->set('maxscaledenom', $dbStyle['maxscale']);
				}
				if ($dbStyle['symbolname']!='') {
          $style->set('symbolname',$dbStyle['symbolname']);
        }
        if ($dbStyle['symbol']>0) {
          $style->set('symbol',$dbStyle['symbol']);
        }
        if (MAPSERVERVERSION >= 620) {
					if($dbStyle['geomtransform'] != '') {
						$style->setGeomTransform($dbStyle['geomtransform']);
					}
          if ($dbStyle['pattern']!='') {
            $style->setPattern(explode(' ',$dbStyle['pattern']));
            $style->linecap = 'butt';
          }
					if($dbStyle['gap'] != '') {
						if($this->map_factor != ''){
							$style->set('gap', $dbStyle['gap']*$this->map_factor/1.414);
						}
						else{
							$style->set('gap', $dbStyle['gap']);
						}
	        }
					if($dbStyle['initialgap'] != '') {
            $style->set('initialgap', $dbStyle['initialgap']);
          }
					if($dbStyle['linecap'] != '') {
	          $style->set('linecap', constant(MS_CJC_.strtoupper($dbStyle['linecap'])));
	        }
					if($dbStyle['linejoin'] != '') {
	          $style->set('linejoin', constant(MS_CJC_.strtoupper($dbStyle['linejoin'])));
	        }
					if($dbStyle['linejoinmaxsize'] != '') {
	          $style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
	        }
					if($dbStyle['polaroffset'] != '') {
	          $style->updateFromString("STYLE POLAROFFSET " . $dbStyle['polaroffset']." END");
	        }
        }

        if($this->map_factor != ''){
          if (MAPSERVERVERSION >= 620) {
            $pattern = $style->getpatternarray();
            if($pattern){
					    foreach($pattern as &$pat){
					      $pat = $pat * $this->map_factor;
					    }
					    $style->setPattern($pattern);
				    }
          }
          else {
            if($style->symbol > 0){
              $symbol = $map->getSymbolObjectById($style->symbol);
              $pattern = $symbol->getpatternarray();
              if(is_array($pattern) AND $symbol->inmapfile != 1){
                foreach($pattern as &$pat){
                  $pat = $pat * $this->map_factor;
                }
                $symbol->setpattern($pattern);
                $symbol->set('inmapfile', 1);
              }
            }
          }
        }
				if($dbStyle['size'] != ''){
					if ($layerset['Datentyp'] == 8) {
						# Skalierung der Stylegröße when Type Chart
						$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
					}
					else {
						if($this->map_factor != '') {
							if(is_numeric($dbStyle['size']))$style->set('size', $dbStyle['size']*$this->map_factor/1.414);
							else $style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
						}
						else{
							if(is_numeric($dbStyle['size']))$style->set('size', $dbStyle['size']);
							else $style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
						}
					}
				}

        if ($dbStyle['minsize']!='') {
          if($this->map_factor != ''){
            $style -> set('minsize',$dbStyle['minsize']*$this->map_factor/1.414);
          }
          else{
            $style -> set('minsize',$dbStyle['minsize']);
          }
        }

        if ($dbStyle['maxsize']!='') {
          if($this->map_factor != ''){
            $style -> set('maxsize',$dbStyle['maxsize']*$this->map_factor/1.414);
          }
          else{
            $style -> set('maxsize',$dbStyle['maxsize']);
          }
        }

				if($dbStyle['angle'] != '') {
					$style->updateFromString("STYLE ANGLE " . $dbStyle['angle']." END"); 		# wegen AUTO
				}
        if ($dbStyle['angleitem']!=''){
          if(MAPSERVERVERSION < 500){
            $style->set('angleitem',$dbStyle['angleitem']);
          }
          else{
            $style->setbinding(MS_STYLE_BINDING_ANGLE, $dbStyle['angleitem']);
          }
        }
        if ($dbStyle['width']!='') {
          if ($dbStyle['antialias']!='') {
            $style -> set('antialias',$dbStyle['antialias']);
          }
          if($this->map_factor != ''){
            $style -> set('width',$dbStyle['width']*$this->map_factor/1.414);
          }
          else{
            $style->set('width',$dbStyle['width']);
          }
        }

        if ($dbStyle['minwidth']!='') {
          if($this->map_factor != ''){
            $style->set('minwidth',$dbStyle['minwidth']*$this->map_factor/1.414);
          }
          else{
            $style->set('minwidth',$dbStyle['minwidth']);
          }
        }

        if ($dbStyle['maxwidth']!='') {
          if($this->map_factor != ''){
            $style->set('maxwidth',$dbStyle['maxwidth']*$this->map_factor/1.414);
          }
          else{
            $style->set('maxwidth',$dbStyle['maxwidth']);
          }
        }
				
        if ($dbStyle['color']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [" . $dbStyle['color']."] END");
        }
				if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					$style->set('opacity', $dbStyle['opacity']);
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['backgroundcolor']!='') {
          $RGB = array_filter(explode(" ",$dbStyle['backgroundcolor']), 'strlen');
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
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
        if ($dbStyle['offsetx']!='') {
          $style->set('offsetx', $dbStyle['offsetx']);
        }
        if ($dbStyle['offsety']!='') {
          $style->set('offsety', $dbStyle['offsety']);
        }
      } # Ende Schleife für mehrere Styles

      # setzen eines oder mehrerer Labels
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Label']);$k++) {
        $dbLabel=$classset[$j]['Label'][$k];
        if (MAPSERVERVERSION < 600) {
          $klasse->label->set('type',$dbLabel['type']);
          $klasse->label->set('font',$dbLabel['font']);
          $RGB=explode(" ",$dbLabel['color']);
          if ($RGB[0]=='') { $RGB[0]=0; }
          if ($RGB[1]=='') { $RGB[1]=0; }
          if ($RGB[2]=='') { $RGB[2]=0; }
          $klasse->label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          $RGB=explode(" ",$dbLabel['outlinecolor']);
          $klasse->label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          if ($dbLabel['shadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['shadowcolor']);
            $klasse->label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('shadowsizex',$dbLabel['shadowsizex']);
            $klasse->label->set('shadowsizey',$dbLabel['shadowsizey']);
          }
          if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
            $klasse->label->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          }
          if ($dbLabel['backgroundshadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
            $klasse->label->backgroundshadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('backgroundshadowsizex',$dbLabel['backgroundshadowsizex']);
            $klasse->label->set('backgroundshadowsizey',$dbLabel['backgroundshadowsizey']);
          }
          $klasse->label->set('angle',$dbLabel['angle']);
          if(MAPSERVERVERSION > 500 AND $layerset['labelangleitem']!=''){
            $klasse->label->setbinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
	          	$klasse->label->set('anglemode', MS_AUTO);
	          }
	          else{
	          	$klasse->label->set('autoangle',$dbLabel['autoangle']);
            }
          }
          if ($dbLabel['buffer']!='') {
            $klasse->label->set('buffer',$dbLabel['buffer']);
          }
					$klasse->label->set('maxlength',$dbLabel['maxlength']);
          $klasse->label->set('wrap',$dbLabel['wrap']);
          $klasse->label->set('force',$dbLabel['the_force']);
          $klasse->label->set('partials',$dbLabel['partials']);
          $klasse->label->set('size',$dbLabel['size']);
          $klasse->label->set('minsize',$dbLabel['minsize']);
          $klasse->label->set('maxsize',$dbLabel['maxsize']);
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $klasse->label->set('minsize',$dbLabel['minsize']*$this->map_factor/1.414);
            $klasse->label->set('maxsize',$dbLabel['size']*$this->map_factor/1.414);
            $klasse->label->set('size',$dbLabel['size']*$this->map_factor/1.414);
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $klasse->label->set('position', MS_UL);
              }break;
              case '1' :{
                $klasse->label->set('position', MS_LR);
              }break;
              case '2' :{
                $klasse->label->set('position', MS_UR);
              }break;
              case '3' :{
                $klasse->label->set('position', MS_LL);
              }break;
              case '4' :{
                $klasse->label->set('position', MS_CR);
              }break;
              case '5' :{
                $klasse->label->set('position', MS_CL);
              }break;
              case '6' :{
                $klasse->label->set('position', MS_UC);
              }break;
              case '7' :{
                $klasse->label->set('position', MS_LC);
              }break;
              case '8' :{
                $klasse->label->set('position', MS_CC);
              }break;
              case '9' :{
                $klasse->label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $klasse->label->set('offsetx',$dbLabel['offsetx']);
          }
          if ($dbLabel['offsety']!='') {
            $klasse->label->set('offsety',$dbLabel['offsety']);
          }
        } # ende mapserver < 600
        else {
          $label = new labelObj();
          $label->type = $dbLabel['type'];
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
            $style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $style->set('offsetx', $dbLabel['backgroundshadowsizex']);
						$style->set('offsety', $dbLabel['backgroundshadowsizey']);
						if ($dbLabel['buffer']!='') {
							$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
							$style->set('width', $dbLabel['buffer']);
						}
          }
					if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
						$style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
						if ($dbLabel['buffer']!='') {
							$style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
							$style->set('width', $dbLabel['buffer']);
						}
          }

          $label->angle = $dbLabel['angle'];
          if($layerset['labelangleitem']!=''){
            $label->setBinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
            	$label->set('anglemode', MS_AUTO);
            }
            else{
            	$label->autoangle = $dbLabel['autoangle'];
            }
          }
          if ($dbLabel['buffer']!='') {
            $label->buffer = $dbLabel['buffer'];
          }
					$label->set('maxlength',$dbLabel['maxlength']);
          $label->wrap = $dbLabel['wrap'];
          $label->force = $dbLabel['the_force'];
          $label->partials = $dbLabel['partials'];
          $label->size = $dbLabel['size'];
          $label->minsize = $dbLabel['minsize'];
          $label->maxsize = $dbLabel['maxsize'];
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $label->minsize = $dbLabel['minsize']*$this->map_factor/1.414;
            $label->maxsize = $dbLabel['size']*$this->map_factor/1.414;
            $label->size = $dbLabel['size']*$this->map_factor/1.414;
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $label->set('position', MS_UL);
              }break;
              case '1' :{
                $label->set('position', MS_LR);
              }break;
              case '2' :{
                $label->set('position', MS_UR);
              }break;
              case '3' :{
                $label->set('position', MS_LL);
              }break;
              case '4' :{
                $label->set('position', MS_CR);
              }break;
              case '5' :{
                $label->set('position', MS_CL);
              }break;
              case '6' :{
                $label->set('position', MS_UC);
              }break;
              case '7' :{
                $label->set('position', MS_LC);
              }break;
              case '8' :{
                $label->set('position', MS_CC);
              }break;
              case '9' :{
                $label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $label->offsetx = $dbLabel['offsetx'];
          }
          if ($dbLabel['offsety']!='') {
            $label->offsety = $dbLabel['offsety'];
          }
          $klasse->addLabel($label);
        } # ende mapserver >=600
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

  function navMap($cmd) {
    switch ($cmd) {
      case "previous" : {
#        $this->user->rolle->setSelectedButton('previous');
        $this->setPrevMapExtent($this->user->rolle->last_time_id);
      } break;
      case "next" : {
#        $this->user->rolle->setSelectedButton('next');
        $this->setNextMapExtent($this->user->rolle->last_time_id);
      } break;
      case "zoomin" : {
        $this->user->rolle->setSelectedButton('zoomin');
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
			case "zoomin_wheel" : {
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
      case "zoomout" : {
        $this->user->rolle->setSelectedButton('zoomout');
        $this->zoomMap($this->user->rolle->nZoomFactor*-1);
      } break;
      case "recentre" : {
        $this->user->rolle->setSelectedButton('recentre');
        $this->zoomMap(1);
      } break;
      // case "jump_coords" : {
        // $this->user->rolle->setSelectedButton('recentre');
        // $this->zoomMap(1);
      // } break;
      case "pquery" : {
        $this->user->rolle->setSelectedButton('pquery');
        $this->queryMap();
      } break;
      case "touchquery" : {
        $this->user->rolle->setSelectedButton('touchquery');
        $this->queryMap();
      } break;
      case "ppquery" : {
        $this->user->rolle->setSelectedButton('ppquery');
        $this->queryMap();
      } break;
      case "polygonquery" : {
        $this->user->rolle->setSelectedButton('polygonquery');
        $this->queryMap();
      } break;
      case "Full_Extent" : {
        $this->user->rolle->setSelectedButton('zoomin');   # um anschliessend wieder neu zoomen zu koennen!
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
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
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
      $oPixelPos=ms_newPointObj();

			$oPixelPos->setXY($minx,$maxy);
			$this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf ein Rechteck gezoomt',4);
			$oPixelExt=ms_newRectObj();
      if($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined'){
       	$oPixelExt->setextent($minx,$miny,$maxx,$maxy);
        $this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
        # Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
        # verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
        $oPixelPos=ms_newPointObj();
        $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
        $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
    }
  }

	function zoom2coord(){
		# Funktion zum Zoomen auf eine Punktkoordinate oder einen Kartenausschnitt; Koordinaten sind Weltkoordinaten
		$corners=explode(';',$this->formvars['INPUT_COORD']);
    $lo=explode(',',$corners[0]);
    $minx=$lo[0];
    $miny=$lo[1];
    if(count($corners)==1){		# Zoom auf Punktkoordinate
			$oPixelPos=ms_newPointObj();
			if($this->formvars['epsg_code'] != '' AND $this->formvars['epsg_code'] != $this->user->rolle->epsg_code){
				$oPixelPos->setXY($minx,$miny);
				$projFROM = ms_newprojectionobj("init=epsg:" . $this->formvars['epsg_code']);
				$projTO = ms_newprojectionobj("init=epsg:" . $this->user->rolle->epsg_code);
				$oPixelPos->project($projFROM, $projTO);
				$minx = $oPixelPos->x;
				$miny = $oPixelPos->y;
			}
			#---------- Punkt-Rollenlayer erzeugen --------#
			if($this->formvars['name'] != '')$legendentext = $this->formvars['name'];
			else $legendentext ="Koordinate: " . $minx." " . $miny;
			if(strpos($minx, '°') !== false){
				$minx = dms2dec($minx);
				$miny = dms2dec($miny);
			}
			$datastring ="the_geom from (select st_geomfromtext('POINT(" . $minx." " . $miny.")', " . $this->user->rolle->epsg_code.") as the_geom, 1 as oid) as foo using unique oid using srid=" . $this->user->rolle->epsg_code;
			$group = $this->mapDB->getGroupbyName('Suchergebnis');
			if($group != ''){
				$groupid = $group['id'];
			}
			else{
				$groupid = $this->mapDB->newGroup('Suchergebnis', 0);
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
			$connectionstring ='user='.$this->pgdatabase->user;
			if($this->pgdatabase->passwd != ''){
				$connectionstring.=' password='.$this->pgdatabase->passwd;
			}
			if($this->pgdatabase->host != ''){
				$connectionstring.=' host='.$this->pgdatabase->host;
			}
			if($this->pgdatabase->port != ''){
				$connectionstring.=' port='.$this->pgdatabase->port;
			}
			$connectionstring.=' dbname='.$this->pgdatabase->dbName;
			$this->formvars['connection'] = $connectionstring;
			$this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
			$this->formvars['transparency'] = 60;

			$layer_id = $this->mapDB->newRollenLayer($this->formvars);

			$classdata['layer_id'] = -$layer_id;
			$class_id = $this->mapDB->new_Class($classdata);

			if(defined('ZOOM2COORD_STYLE_ID') AND ZOOM2COORD_STYLE_ID != ''){
				$style_id = $this->mapDB->copyStyle(ZOOM2COORD_STYLE_ID);
			}
			else{
				$style['colorred'] = 255;
				$style['colorgreen'] = 255;
				$style['colorblue'] = 128;
				$style['outlinecolorred'] = 0;
				$style['outlinecolorgreen'] = 0;
				$style['outlinecolorblue'] = 0;
				$style['size'] = 10;
				$style['symbolname'] = 'circle';
				$style['backgroundcolor'] = NULL;
				$style['minsize'] = NULL;
				$style['maxsize'] = 100000;
				$style['angle'] = 360;
				$style_id = $this->mapDB->new_Style($style);
			}

			$this->mapDB->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
			$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
			$this->loadMap('DataBase');

			# Bildkoordinaten ausrechnen
			$this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
			$pixel_x = ($minx-$this->map->extent->minx)/$this->pixwidth;
			$pixel_y = ($this->map->extent->maxy-$miny)/$this->pixwidth;
			$oPixelPos->setXY($pixel_x,$pixel_y);
			if($this->map->scaledenom > COORD_ZOOM_SCALE){
				$this->map->zoomscale(COORD_ZOOM_SCALE/4,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
			}
			else{
				$this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
			}
		}
		else{	# Zoom auf Rechteck
			$ru=explode(',',$corners[1]);
      $maxx=$ru[0];
			$maxy=$ru[1];
			$rect=ms_newRectObj();
			$rect->setextent($minx,$miny,$maxx,$maxy);
			if($this->formvars['epsg_code'] != '' AND $this->formvars['epsg_code'] != $this->user->rolle->epsg_code){
				$projFROM = ms_newprojectionobj("init=epsg:" . $this->formvars['epsg_code']);
				$projTO = ms_newprojectionobj("init=epsg:" . $this->user->rolle->epsg_code);
				$rect->project($projFROM, $projTO);
			}
			$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
			# Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
			# verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
			$oPixelPos=ms_newPointObj();
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

  # Speichert die Daten des MapObjetes in Datei oder Datenbank und den Extent in die Rolle
  function saveMap($saveMapDestination) {
		if ($saveMapDestination=='') {
      $saveMapDestination=SAVEMAPFILE;
    }
    if ($saveMapDestination != '') {
      $this->map->save($saveMapDestination);
    }
    $this->user->rolle->saveSettings($this->map->extent);
    # 2006-02-16 pk
    $this->user->rolle->readSettings();
  }

	/**
	 * transformiert die gegebenen Koordinaten von wgs in das System der Stelle und speichert den Kartenextent für die Rolle
	 */
	function setMapExtent() {
    if (MAPSERVERVERSION < 600) {
	    $extent = ms_newRectObj();
		}
    else {
		  $extent = new rectObj();
		}
		$extent->setextent($this->formvars['left'],$this->formvars['bottom'],$this->formvars['right'],$this->formvars['top']);
		$wgsProjection = ms_newprojectionobj("init=epsg:4326");
		$userProjection = ms_newprojectionobj("init=epsg:" . $this->user->rolle->epsg_code);
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
		if($layer['status'] != '' OR ($this->map_scaledenom < $layer['minscale'] OR ($layer['maxscale'] > 0 AND $this->map_scaledenom > $layer['maxscale']))) {
			return false;
		}
		return true;
	}

	# Zeichnet die Kartenelemente Hauptkarte, Legende, Maßstab und Referenzkarte
  # drawMap #
  function drawMap() {
		if($this->formvars['go'] != 'navMap_ajax')set_error_handler("MapserverErrorHandler");		// ist in allg_funktionen.php definiert
    if($this->main == 'map.php' AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
			$this->saveMap('');
    }
    $this->image_map = $this->map->draw() OR die($this->layer_error_handling());
    $filename = $this->user->id.'_'.rand(0, 1000000).'.'.$this->map->outputformat->extension;
    $this->image_map->saveImage(IMAGEPATH.$filename);
    $this->img['hauptkarte'] = IMAGEURL.$filename;
    $this->debug->write("Name der Hauptkarte: " . $this->img['hauptkarte'],4);

		if($this->formvars['go'] != 'navMap_ajax'){
			$this->legende = $this->create_dynamic_legend();
			$this->debug->write("Legende erzeugt",4);
		}
		else{
			# Zusammensetzen eines Layerhiddenstrings, in dem die aktuelle Sichtbarkeit aller aufgeklappten Layer gespeichert ist um damit bei Bedarf die Legende neu zu laden
			for($i = 0; $i < $this->layerset['anzLayer']; $i++) {
				$layer=&$this->layerset[$i];
				if($layer['requires'] == ''){
					if($this->check_layer_visibility($layer))$layerhiddenflag = '0';
					else $layerhiddenflag = '1';
					$this->layerhiddenstring .= $layer['Layer_ID'].' '.$layerhiddenflag.' ';
				}
			}
		}

    # Erstellen des Maßstabes
		$this->map_scaledenom = $this->map->scaledenom;
    $this->switchScaleUnitIfNecessary();
    $img_scalebar = $this->map->drawScaleBar();
    $filename = $this->map_saveWebImage($img_scalebar,'png');
    $newname = $this->user->id.basename($filename);
    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
    $this->img['scalebar'] = IMAGEURL.$newname;
    $this->debug->write("Name des Scalebars: " . $this->img['scalebar'],4);
		$this->calculatePixelSize();
		$this->drawReferenceMap();
  }

  function switchScaleUnitIfNecessary() {
		if ($this->map_scaledenom > $this->scaleUnitSwitchScale) $this->map->scalebar->set('units', MS_KILOMETERS);
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

	function map_saveWebImage($image,$format) {
		if(MAPSERVERVERSION >= 600 ) {
			return $image->saveWebImage();
		}
		else {
			return $image->saveWebImage($format, 1, 1, 0);
		}
	}

  function drawReferenceMap(){
    # Erstellen der Referenzkarte
    if($this->reference_map->reference->image != NULL){
			$this->reference_map->setextent($this->map->extent->minx,$this->map->extent->miny,$this->map->extent->maxx,$this->map->extent->maxy);
			if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
				if(MAPSERVERVERSION < '600'){
					$projFROM = ms_newprojectionobj("init=epsg:" . $this->user->rolle->epsg_code);
					$projTO = ms_newprojectionobj("init=epsg:" . $this->ref['epsg_code']);
				}
				else{
					$projFROM = $this->map->projection;
					$projTO = $this->reference_map->projection;
				}
				$this->reference_map->extent->project($projFROM, $projTO);
			}
      $img_refmap = $this->reference_map->drawReferenceMap();
      $filename = $this->map_saveWebImage($img_refmap,'png');
      $newname = $this->user->id.basename($filename);
      rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
      $this->img['referenzkarte'] = IMAGEURL.$newname;
      $this->debug->write("Name der Referenzkarte: " . $this->img['referenzkarte'],4);
      $this->Lagebezeichung=$this->getLagebezeichnung($this->user->rolle->epsg_code);
    }
	}

	function loadMultiLingualText($language) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language;
    $this->Stelle->language=$language;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'.php');
  }

  function getLagebezeichnung($epsgcode) {
    switch (LAGEBEZEICHNUNGSART) {
      case 'Flurbezeichnung' : {
        $Lagebezeichnung = $this->getFlurbezeichnung($epsgcode);
			} break;
			default : {
			  $Lagebezeichnung = '';
			}
	  }
    return $Lagebezeichnung;
  }

  function getFlurbezeichnung($epsgcode) {
    $Flurbezeichnung = '';
 	  $flur = new Flur('','','',$this->pgdatabase);
		$bildmitte['rw']=($this->map->extent->maxx+$this->map->extent->minx)/2;
		$bildmitte['hw']=($this->map->extent->maxy+$this->map->extent->miny)/2;
		$ret=$flur->getBezeichnungFromPosition($bildmitte, $epsgcode);
		if ($ret[0]) {
		}
		else {
			if ($ret[1]['flur'] != '') {
				$Flurbezeichnung = $ret[1];
			}
		}
		return $Flurbezeichnung;
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
		if(strpos($this->user->rolle->gui, 'layouts') === false){		# Berücksichtigung des alten gui-Pfads
			return WWWROOT . APPLVERSION . 'layouts/' . $this->user->rolle->gui;
		}
		else {
			return WWWROOT . APPLVERSION . $this->user->rolle->gui;
		}
	}

	function add_message($type, $msg) {
		if (is_array($msg) AND array_key_exists('success', $msg) AND is_array($msg)) {
			$type = 'notice';
			$msg = $msg['msg'];
		}
		if ($type == 'array' or is_array($msg)) {
			foreach($msg AS $m) {
				$this->add_message($m['type'], $m['msg']);
			}
		}
		else {
			$this->messages[] = array(
				'type' => $type,
				'msg' => $msg
			);
		}
	}

	function output_messages($option = 'with_script_tags') {
		$html = "message(" . json_encode($this->messages) . ");";
		if ($option == 'with_script_tags') {
			$html = "<script type=\"text/javascript\">" . $html . "</script>";
		}
		echo $html;
		$this->messages = array();
	}

	# Ausgabe der Seite
	function output() {
		global $sizes;
		# bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if ($this->formvars['mime_type'] != '') {
			$this->mime_type = $this->formvars['mime_type'];
		}
		switch ($this->mime_type) {
			case 'printversion' : {
				include (LAYOUTPATH.'snippets/printversion.php');
			} break;
			case 'html' : {
				if ($this->only_main) {
					include_once(SNIPPETS . $this->main);
				}
				else {
					$guifile = $this->get_guifile();
					$this->debug->write("<br>Include <b>" . $guifile . "</b> in kvwmap.php function output()",4);
					include ($guifile);
				}
				if ($this->alert != '') {
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty($this->messages)) {
					$this->output_messages();
				}
			} break;
			case 'overlay_html' : {
				$this->overlaymain = $this->main;
				include (LAYOUTPATH.'snippets/overlay.php');
				if($this->alert != ''){
					echo '<script type="text/javascript">alert("'.$this->alert.'");</script>';			# manchmal machen alert-Ausgaben über die allgemeinde Funktioen showAlert Probleme, deswegen am besten erst hier am Ende ausgeben
				}
				if (!empty($this->messages)) {
					$this->output_messages();
				}
			} break;
			case 'map_ajax' : {
				$this->debug->write("Include <b>".LAYOUTPATH."snippets/map_ajax.php</b> in kvwmap.php function output()",4);
				include (LAYOUTPATH.'snippets/map_ajax.php');
			} break;
			case 'pdf' : {
				$this->formvars['file']=1;
				if ($this->formvars['file']) {
					echo '
						<html>
							<head>
								<title>PDF-Ausgabe</title>
								<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
								<META HTTP-EQUIV=REFRESH CONTENT="0; URL=' . TEMPPATH_REL.$this->outputfile . '">
							</head>
							<body>
								<BR>Folgende Datei wird automatisch aufgerufen: <a href="' . TEMPPATH_REL.$this->outputfile . '">' . $this->outputfile . '</a>
							</body>
						</html>
					';
				}
				else {
					$this->pdf->ezStream();
				}
			} break;
			default : {
				if ($this->formvars['format'] != '') {
					include('formatter.php');
					$this->formatter = new formatter($this->qlayersetParamStrip(), $this->formvars['format'], $this->formvars['content_type'], $this->formvars['callback']);
					echo utf8_encode($this->formatter->output());
				}
			}
		}
	} # end of function output

	function autocomplete_request() { # layer_id, attribute, inputvalue, field_id
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		$layerdb->setClientEncoding();
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
				echo'<select size="'.$count.'" style="width: 450px;padding:4px; margin:-2px -17px -4px -4px;" onclick="document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';document.getElementById(\''.$this->formvars['field_id'].'\').value=this.value;document.getElementById(\''.$this->formvars['field_id'].'\').onchange();document.getElementById(\'output_'.$this->formvars['field_id'].'\').value=this.options[this.selectedIndex].text;document.getElementById(\'output_'.$this->formvars['field_id'].'\').onchange();">';
				while($rs=pg_fetch_array($ret[1])) {
					echo '<option onmouseover="this.selected = true;"  value="'.$rs['value'].'">'.$rs['output'].'</option>';
				}
				echo '</select>
				█document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'block\';';
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

	function checkCaseAllowed($case) {
		if (!($this->Stelle->isMenueAllowed($case) OR $this->Stelle->isFunctionAllowed($case))) {
			$this->add_message('error', $this->TaskChangeWarning . '<br>(' . $case . ')' . '<br>Weder Menü noch Funktion erlaubt.');
			global $log_loginfail;
			$log_loginfail->write(date("Y:m:d H:i:s",time()) . ' case: ' . $case . ' not allowed in Stelle: ' . $this->Stelle->id . ' for User: ' . $this->user->Name);
			go_switch('', true);
		}
	}

	/*
	* Erzeugt eine Fehlermeldung in $this->messages und wechselt zum default use case,
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
	* Erzeugt eine Fehlermeldung in $this->messages und wechselt zum default use case,
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
	* Erzeugt eine Fehlermeldung in $this->messages und wechselt zum default use case,
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
	* zusätzlich eine Fehlermeldung in $this->messages
	*/
	function is_admin_user($user_id) {
		global $admin_stellen;
		$ret = false;

		$sql = "
			SELECT DISTINCT
				r.user_id
			FROM
				rolle r JOIN
				user u ON r.user_id = u.ID
			WHERE
				r.stelle_id IN (" . implode(', ', $admin_stellen) . ") AND
				u.id = '" . $user_id . "'
		";
		#echo '<br>sql: ' . $sql;

		$result = $this->database->execSQL($sql, 0, 0);
		if ($result['success']) {
			if (mysql_num_rows($result['query']) == 1) {
				$ret = true;
			}
		}
		else {
			$this->add_message('error', 'Fehler beim Abfragen ob der Nutzer mit der ID: ' . $user_id . ' ein Administrator ist.');
		}
		return $ret;
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
			if (mysql_num_rows($result['query']) > 0) {
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

	function getSVG_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien von allen aktiven Postgis-Layern, die im aktuellen Kartenausschnitt liegen
		#$this->user->rolle->readSettings();
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
					$layer[$i]['Filter'] = str_replace('$userid', $this->user->id, $layer[$i]['Filter']);
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

	function getSVG_foreign_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien des übergebenen Postgis-Layers, die im aktuellen Kartenausschnitt liegen
		#$this->user->rolle->readSettings();
		if($this->formvars['geom_from_layer'] == 0){		# wenn kein Layer ausgewählt ==> alle aktiven abfragen
			$this->getSVG_vertices();
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
		if($layer['connectiontype'] == MS_POSTGIS){
			$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
    	$data_attributes = $mapDB->getDataAttributes($layerdb, $layer['Layer_ID']);
    	$select = $mapDB->getSelectFromData($layer['Data']);
			$select = str_replace(' FROM ', ' from ', $select);

			if($this->formvars['geom_from_layer'] > 0)$select = str_replace(' from ', ', '.$data_attributes[$data_attributes['the_geom_id']]['table_alias_name'].'.oid as exclude_oid'.' from ', $select);		# bei Rollenlayern nicht machen
			$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer['epsg_code'].')';

			$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects('.$data_attributes['the_geom'].', '.$extent.') ';
			if($layer['Datentyp'] !== '1' AND $this->formvars['geom_from_layer'] > 0 AND $this->formvars['oid']){		# bei Linienlayern werden auch die eigenen Punkte geholt, bei Polygonen nicht
				$fromwhere .= 'AND exclude_oid != '.$this->formvars['oid'];
			}
			# Filter hinzufügen
			if($layer['Filter'] != ''){
				$layer['Filter'] = str_replace('$userid', $this->user->id, $layer['Filter']);
				$fromwhere .= " AND " . $layer['Filter'];
			}
			$sql = '
				SELECT st_x((dump).geom), st_y((dump).geom) 
				FROM (
					SELECT st_dumppoints(intersection) AS dump
					FROM (
						select 	st_transform(st_intersection('.$data_attributes['the_geom'].', '.$extent.'), '.$this->user->rolle->epsg_code.') as intersection
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
				echo '█show_vertices();';
      }
		}
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

		if($this->formvars['legenddisplay'] !== NULL)$hideLegend = $this->formvars['legenddisplay'];		// falls die Legende gerade ein/ausgeblendet wurde
		else $hideLegend = $this->user->rolle->hideLegend;

		$width = $this->formvars['browserwidth'] -
			$size['margin']['width'] -
			($this->user->rolle->hideMenue  == 1 ? $size['menue']['hide_width'] : $size['menue']['width']) -
			($hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width'])
			- 18;	# Breite für möglichen Scrollbalken

		$height = $this->formvars['browserheight'] -
			$size['margin']['height'] -
			$size['header']['height'] -
			$size['scale_bar']['height'] -
			(LAGEBEZEICHNUNGSART != '' ? $size['lagebezeichnung_bar']['height'] : 0) -
			($this->user->rolle->showmapfunctions == 1 ? $size['map_functions_bar']['height'] : 0) -
			$size['footer']['height'];

		if($width  < 0) $width = 10;
		if($height < 0) $height = 10;
		if($height % 2 != 0)$height = $height - 1;		# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel
		if($width  % 2 != 0)$width = $width - 1;				# muss gerade sein, sonst verspringt die Karte beim Panen immer um 1 Pixel

		$this->user->rolle->setSize($width.'x'.$height);
		$this->user->rolle->readSettings();
	}

	function split_multi_geometries(){
		include_(CLASSPATH.'spatial_processor.php');
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$spatial_processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
		$single_geoms = $spatial_processor->split_multi_geometries($this->formvars['newpathwkt'], $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		$this->copy_dataset($mapdb, $this->formvars['selected_layer_id'], array('oid'), array($this->formvars['oid']), count($single_geoms), array($this->formvars['layer_columnname']), $single_geoms, true);
		$this->loadMap('DataBase');					# Karte anzeigen
		$currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
		$this->output();
	}

	function dublicate_dataset() {
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
		if ($new_oids = $this->copy_dataset($mapdb, $this->formvars['chosen_layer_id'], array('oid'), array($oid), 1)) {
			$this->add_message('notice', 'Der Datensatz wurde kopiert.');
			$this->formvars['value_'.$layerset[0]['maintable'].'_oid'] = $new_oids[0];
			$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
			$this->GenerischeSuche_Suchen();
		}
		else{
			$this->add_message('error', 'Kopiervorgang fehlgeschlagen.');
			$this->GenerischeSuche_Suchen();
		}
	}

	function copy_dataset($mapdb, $layer_id, $id_names, $id_values, $count, $update_columns = NULL, $update_values = NULL, $delete_original = false){
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

		# Attribute, die kopiert werden sollen ermitteln
		$sql = "
			SELECT column_name
			FROM information_schema.columns
			WHERE
				table_name = '" . $layerset[0]['maintable'] . "' AND
				table_schema = '" . $layerdb->schema . "'
			";
		$ret = $layerdb->execSQL($sql,4, 0);
		if (!$ret['success']) {
			return array();
		}
		while ($rs = pg_fetch_row($ret[1])) {
			if(!in_array($layerattributes['constraints'][$rs[0]], array('PRIMARY KEY', 'UNIQUE'))) $attributes[] = $rs[0];		# PRIMARY KEY und UNIQUE Attribute auslassen
			if($layerattributes['form_element_type'][$rs[0]] == 'Dokument')$document_attributes[] = $rs[0];				# Dokument-Attribute sammeln
		}

		for ($n = 0; $n < count($id_names); $n++) {
			$where[] = $id_names[$n] . " = '" . $id_values[$n] . "'";
		}

		# Dokument-Pfade abfragen
		if (count($document_attributes) > 0) {
			$sql = "
				SELECT " . implode(',', $document_attributes) . "
				FROM " . $layerset[0]['maintable'] . "
				WHERE " . implode(' AND ', $where) . "
			";
			#echo 'SQL zur Abfrage der Dokument-Pfade: ' . $sql;
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
			$sql = "
				SELECT Coalesce(max(".$layerset[0]['oid']."), 0) AS oid FROM " . $layerset[0]['maintable'] . "
			";
			#echo '<br>SQL zur Abfrage der letzen oid: ' . $sql;
			$ret = $layerdb->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				return array();
			}
			$rs = pg_fetch_assoc($ret[1]);
			$max_oid = $rs['oid'];

			$sql = "
				INSERT INTO " . $layerset[0]['maintable'] . " (" . implode(',', $attributes) . ")
				SELECT " . implode(',', $attributes) . "
				FROM " . $layerset[0]['maintable'] . "
				WHERE " . implode(' AND ', $where) . "
			";
			#echo '<br>SQL zum kopieren eines Datensatzes: ' . $sql;
			$ret = $layerdb->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				return array();
			}
			$sql = "
				SELECT ".$layerset[0]['oid']."
				FROM " . $layerset[0]['maintable'] . "
				WHERE
					".$layerset[0]['oid']." > " . $max_oid . "
			";
			#echo '<br>SQL zum Abfragen der neuen oids: ' . $sql;
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
				for ($p = 0; $p < count($orig_dataset[$d]['document_paths']); $p++) { # diese Schleife durchläuft alle Dokument-Attribute innerhalb eines kopierten Datensatzes
					if ($orig_dataset[$d]['document_paths'][$p] != '') {
						$path_parts = explode('&', $orig_dataset[$d]['document_paths'][$p]);		# &original_name=... abtrennen
						$orig_path = $path_parts[0];
						$name_parts = explode('.', $orig_path);		# Dateiendung ermitteln
						$new_file_name = date('Y-m-d_H_i_s',time()).'-'.rand(100000, 999999).'.'.$name_parts[1];;
						$new_path = dirname($orig_path).'/'.$new_file_name;
						copy($orig_path, $new_path);
						$complete_new_path = $new_path.'&'.$path_parts[1];
						$sql = "
							UPDATE " . $layerset[0]['maintable'] . "
							SET " . $document_attributes[$p] . " = '" . $complete_new_path . "'
							WHERE ".$layerset[0]['oid']." = " . $rs[0] . "
						";
						#echo '<br>SQL zum Update der Dokumentattribute: ' . $sql;
						$ret1 = $layerdb->execSQL($sql,4, 0);
					}
				}
				$d++;
			}
			# dann die Attribute updaten, die sich unterscheiden sollen
			if ($new_oids[0] != '') {
				for ($u = 0; $u < count($update_columns); $u++) {
					$sql = "
						UPDATE " . $layerset[0]['maintable'] . "
						SET " . $update_columns[$u] . " = '" . $update_values[$i][$u] . "'
						WHERE ".$layerset[0]['oid']." IN (" . implode(',', $new_oids) . ")
					";
					#echo '<br>SQL zum Update der Attribute, die sich unterscheiden sollen: ' . $sql;
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
						$sql = "SELECT ".implode(',', $subform_pks_realnames)." FROM " . $layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus dem alten Datensatz abfragen
						for($n = 0; $n < count($id_names); $n++){
							$sql.= $id_names[$n]." = '" . $id_values[$n]."' AND ";
						}
						$sql.= "1=1";
						#echo $sql.'<br>';
						$ret=$layerdb->execSQL($sql,4, 0);
						if(!$ret[0]){
							$pkvalues=pg_fetch_row($ret[1]);
						}
						$sql = "SELECT ".implode(',', $subform_pks_realnames)." FROM " . $layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus den neuen Datensätzen abfragen
						$sql.= "".$layerset[0]['oid']." IN (".implode(',', $all_new_oids).")";
						#echo '<br>'.$sql.'<br>';
						$ret=$layerdb->execSQL($sql,4, 0);
						if(!$ret[0]){
							while($rs=pg_fetch_row($ret[1])){
								$next_update_values[] = $rs;
							}
						}
						$this->copy_dataset($mapdb, $subform_layerid, $subform_pks_realnames2, $pkvalues, count($next_update_values), $subform_pks_realnames2, $next_update_values, $delete_original);
					}
				}
			}

			for ($n = 0; $n < count($id_names); $n++) {
				$where[] = $id_names[$n] . " = '" . $id_values[$n] . "'";
			}

			# Original löschen
			if ($delete_original) {
				$sql = "
					DELETE FROM " . $layerset[0]['maintable'] . "
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

  function import_layer_importieren(){
		include_(CLASSPATH.'synchronisation.php');
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

  function export_layer_exportieren(){
		include_(CLASSPATH.'synchronisation.php');
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

  function get_select_list(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributenames[0] = $this->formvars['attribute'];
		if($this->formvars['datatype_id'] != '')
			$attributes = $mapDB->read_datatype_attributes($this->formvars['datatype_id'], $layerdb, $attributenames);
    else{
			$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		}
		$attributes['options'][$this->formvars['attribute']] = str_replace('$user_id', $this->user->id, $attributes['options'][$this->formvars['attribute']]);
		$attributes['options'][$this->formvars['attribute']] = str_replace('$stelle_id', $this->stelle->id, $attributes['options'][$this->formvars['attribute']]);
		$options = array_shift(explode(';', $attributes['options'][$this->formvars['attribute']]));
    $reqby_start = strpos(strtolower($options), "<required by>");
    if($reqby_start > 0)$sql = substr($options, 0, $reqby_start);else $sql = $options; 
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		for($i = 0; $i < count($attributenames); $i++){
			$sql = str_replace('<requires>'.$attributenames[$i].'</requires>', "'".$attributevalues[$i]."'", $sql);
		}
		#echo $sql;
		@$ret=$layerdb->execSQL($sql,4,0);
		if (!$ret[0]) {
			switch($this->formvars['type']) {
				case 'select-one' : {					# ein Auswahlfeld soll mit den Optionen aufgefüllt werden 
					$html = '>';			# Workaround für dummen IE Bug
					$html .= '<option value="">-- Bitte Auswählen --</option>';
					while($rs = pg_fetch_array($ret[1])){
						$html .= '<option value="'.$rs['value'].'">'.$rs['output'].'</option>';
					}
				}break;
				
				case 'text' : {								#  ein Textfeld soll nur mit dem ersten Wert aufgefüllt werden
					$rs = pg_fetch_array($ret[1]);
					$html = $rs['output'];
				}break;
			}
		}
		echo $html;
  }

	function auto_generate(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
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
    if ($ret[0]) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
		$rs = pg_fetch_array($ret[1]);
		echo $rs[0];
  }

	function openCustomSubform(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributenames[0] = $this->formvars['attribute'];
    $attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		$url = $attributes['options'][0];
		for($i = 0; $i < count($attributenames); $i++){
			$url = str_replace('$'.$attributenames[$i], $attributevalues[$i], $url);
		}
		echo $url.'&field_id='.$this->formvars['field_id'];
	}

  function showMapImage(){
  	$this->loadMap('DataBase');
  	$this->drawMap();
  	$randomnumber = rand(0, 1000000);
  	$svgfile  = $randomnumber.'.svg';
  	$jpgfile = $randomnumber.'.jpg';
  	$fpsvg = fopen(IMAGEPATH.$svgfile,w);
  	$svg='<?xml version="1.0"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
  "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg id="svgmap" zoomAndPan="disable" width="'.$this->map->width.'" height="'.$this->map->height.'"
  xmlns="http://www.w3.org/2000/svg" version="1.1"
  xmlns:xlink="http://www.w3.org/1999/xlink">
<title> kvwmap </title><desc> kvwmap - WebGIS application - kvwmap.sourceforge.net </desc>';
		$this->formvars['svg_string'] = str_replace(IMAGEURL, IMAGEPATH, $this->formvars['svg_string']).'</svg>';
		$svg.= str_replace('points=""', 'points="-1000,-1000 -2000,-2000 -3000,-3000 -1000,-1000"', $this->formvars['svg_string']);
		fputs($fpsvg, $svg);
  	fclose($fpsvg);
  	exec(IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile);
  	#echo IMAGEMAGICKPATH.'convert '.IMAGEPATH.$svgfile.' '.IMAGEPATH.$jpgfile;

    if(function_exists('imagecreatefromjpeg')){
    	$mainimage = imagecreatefromjpeg(IMAGEPATH.$jpgfile);
    	if(strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'jpg'){
        $scaleimage = imagecreatefromjpeg(IMAGEPATH.basename($this->img['scalebar']));
      }
      elseif(strtolower(array_pop(explode('.', basename($this->img['scalebar'])))) == 'png'){
        $scaleimage = imagecreatefrompng(IMAGEPATH.basename($this->img['scalebar']));
      }
      ImageCopy($mainimage, $scaleimage, imagesx($mainimage)-imagesx($scaleimage), imagesy($mainimage)-imagesy($scaleimage), 0, 0, imagesx($scaleimage), imagesy($scaleimage));
      ob_end_clean();
      ImageJPEG($mainimage, IMAGEPATH.$jpgfile);
    }
		echo "
			<html>
				<head>
					<title>Kartenbild</title>
				</head>
				<body style=\"text-align:center\">
					<script>
						function copyImage(){
							var img=document.getElementById('mapimg');
							var r = document.createRange();
							r.setStartBefore(img);
							r.setEndAfter(img);
							r.selectNode(img);
							var sel = window.getSelection();
							sel.addRange(r);
							document.execCommand('Copy');
							sel.removeAllRanges();
						}
					</script>
					<img id=\"mapimg\" src=\"".TEMPPATH_REL.$jpgfile."\" style=\"box-shadow:  0px 0px 14px #777;\"><br><br>
					<input type=\"button\" onclick=\"copyImage();\" value=\"Bild kopieren\">
				</body>
			</html>
			";
  }

	/*
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
  }

  function get_styles(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
    echo'
      <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td height="25" valign="top" class="fett">Styles</td>
					<td align="right">
						'.($this->layer['editable'] ? '<a href="javascript:add_style();" title="neuer Style"><i style="padding: 6px" class="fa fa-plus buttonlink" aria-hidden="true"></i></a>' : '').'
					</td>
        </tr>';
    if(count($this->classdaten[0]['Style']) > 0){
      $this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
      for($i = 0; $i < count($this->classdaten[0]['Style']); $i++){
        echo'
          <tr>
            <td style="';
							if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'background-color:lightsteelblue; ';}
							echo 'cursor: pointer; border-top: 1px solid #aaa;" id="td1_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" onclick="get_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">';
              echo '<img src="'.IMAGEURL.$this->getlegendimage($this->formvars['layer_id'], $this->classdaten[0]['Style'][$i]['Style_ID']).'"></td>';
              echo '<td align="right" id="td2_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" style="';
              if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'background-color:lightsteelblue; ';}
              echo 'border-top: 1px solid #aaa;">';
							if($this->layer['editable']){
								if($i < count($this->classdaten[0]['Style'])-1){echo '<a href="javascript:movedown_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach unten verschieben"><img src="'.GRAPHICSPATH.'pfeil.gif" border="0"></a>';}
								if($i > 0){echo '&nbsp;<a href="javascript:moveup_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach oben verschieben"><img src="'.GRAPHICSPATH.'pfeil2.gif" border="0"></a>';}
								echo html_umlaute('&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="löschen"><i style="padding: 6px" class="fa fa-trash" aria-hidden="true"></i></a>');
							}
        echo'
            </td>
          </tr>
          ';
      }
    }
    echo'
      </table>';
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
    $style = array();
    switch ($this->formvars['Datentyp']) {
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
    if (MAPSERVERVERSION > '500') {
    	$style['angle'] = 360;
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

  function get_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layer = $mapDB->get_Layer($this->formvars['layer_id']);
    $this->styledaten = $mapDB->get_Style($this->formvars['style_id']);
    if(is_array($this->styledaten)){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->styledaten); $i++){
        echo'
          <tr>
            <td class="px13">';
              echo key($this->styledaten).'</td><td><input class="styleFormField"';
              if($i === 0)echo 'onkeyup="if(event.keyCode != 8)get_style(this.value)"';
              echo ' name="style_'.key($this->styledaten).'" size="11" type="text" value="'.$this->styledaten[key($this->styledaten)].'">';
        echo'
            </td>
          </tr>';
        next($this->styledaten);
      }
			if($this->layer['editable']){
				echo'
					<tr>
						<td height="30" colspan="2" valign="bottom" align="center"><input type="button" name="style_save" value="Speichern" onclick="save_style('.$this->styledaten['Style_ID'].')"></td>
					</tr>
				</table>';
			}
    }
  }

  function save_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Label($this->formvars);
    $this->get_labels();
    echo '█';
    $this->get_label();
  }

  function get_label(){
		include_once(CLASSPATH.'FormObject.php');
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
											' '
										);
							}break;
							
							case 'antialias' : case 'the_force' : case 'partials' :{
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

  function get_sub_menues(){
    $submenues = Menue::getsubmenues($this, $this->formvars['menue_id']);
    echo '<select name="submenues" size="6" multiple style="width:300px">';
    for($i=0; $i < count($submenues); $i++){
      echo '<option selected title="'.$submenues[$i]->data['name'].'" id="'.$submenues[$i]->data['order'].'_all_'.$submenues[$i]->data['menueebene'].'_'.$i.'" value="'.$submenues[$i]->data['id'].'">&nbsp;&nbsp;-->&nbsp;'.$submenues[$i]->data['name'].'</option>';
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

  function export_Adressaenderungen(){
    $this->titel='Adressänderungen der Eigentümer exportieren';
    $this->main='Adressaenderungen_Export.php';
    $this->output();
  }

  function export_Adressaenderungen_exportieren(){
		include_(CLASSPATH.'adressaenderungen.php');
    $adressaenderungen = new adressaenderungen($this->pgdatabase);
    $adressaenderungen->delete_old_entries();
    $adressaenderungen->read_anschriften();
		$adressaenderungen->read_personen();
    $this->filename = $adressaenderungen->export_into_file();
    $this->export_Adressaenderungen();
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

  function showFlurstueckKoordinaten() {
    $flurst=new flurstueck($this->formvars['FlurstKennz'],$this->pgdatabase);
    $ret=$flurst->getKoordinaten();
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      echo 'Lfdnr&nbsp;RW&nbsp;HW';
      $Punkte=$ret[1];
      for ($i=0;$i<count($Punkte);$i++) {
        echo '<br>'.$this->formvars['FlurstKennz'].'-'.$Punkte[$i]['lfdnr'];
        echo '&nbsp;'.$Punkte[$i]['x'];
        echo '&nbsp;'.$Punkte[$i]['y'];
      }
    }
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
      $imageextention=substr(strstr($imageformat,'/'),1); # z.B. macht aus image/png png
      # eindeutigen Dateinamen erzeugen aus bbox Parameter
      $bbox=explode(',',$wms_param['bbox']);
      $box=$bbox[0].','.$bbox[1].','.$bbox[2].','.$bbox[3];
      $zoomstufe=round(log(720/($bbox[2]-$bbox[0]),2));
      $sw=round($bbox[0],1).','.round($bbox[1],1);
      $tmpfile = CACHEPATH.
        $wms_param['layers'].'_'.
        $zoomstufe.'-'.
        $sw.'_'.
        $wms_param['width'].'x'.
        $wms_param['height'].'.'.$imageextention;
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
			$this->loadMap('DataBase');
			$requestobject = ms_newOwsRequestObj();
			$params = array_keys($this->formvars);
			for($i = 0; $i < count($this->formvars); $i++){
				$requestobject->setParameter($params[$i],$this->formvars[$params[$i]]);
			}
			//$requestobject->loadparams();   # geht nur wenn php als cgi läuft
			ms_ioinstallstdouttobuffer();
			$this->map->owsdispatch($requestobject);
			$contenttype = ms_iostripstdoutbuffercontenttype();
			ob_end_clean();   //Ausgabepuffer leeren (sonst funktioniert header() nicht)
			ob_start();
			switch (strtolower($request['REQUEST'])) {
				case 'getmap' : {
					if ( $contenttype != 'image/png') {
						$contenttype = 'image/jpeg';
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
			ms_iogetStdoutBufferBytes();
			if ($this->writeTmpFile) {
				$wms_response = new wms_response_obj($this->tmpfile);
				$wms_response->save(ob_get_contents());
			}
			ob_end_flush();
			ms_ioresethandlers();
		}
	}

	function adminFunctions() {
		include_once(CLASSPATH . 'administration.php');
		$this->administration = new administration($this->database, $this->pgdatabase);
		$this->administration->get_database_status();
		$this->administration->get_config_params();
		switch ($this->formvars['func']) {
			case "update_databases" : {
				$err_msgs = $this->administration->update_databases();
				if (count($err_msgs) > 0) {
					$this->add_message('error', implode('<br>', $err_msgs));
				}
				$this->administration->get_database_status();
				$this->administration->get_config_params();
				$this->showAdminFunctions();
			} break;
			case "update_code" : {
				$result = $this->administration->update_code();
				$this->administration->get_database_status();
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
			case "save_sicherungsinhalt" : {
				$this->administration->save_sicherungsinhalt($this->formvars);
			} break;
			case "save_sicherung" : {
				$this->administration->save_sicherung($this->formvars);
			} break;
			case "write_backup_plan" : {
				$this->administration->write_backup_scripts();
				$this->administration->update_backups_in_crontab();
				$this->showAdminFunctions();
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
				$attributes = $mapDB->load_attributes(
					$layerdb,
					replace_params(
						$layer['pfad'],
						rolle::$layer_params,
						$this->user->id,
						$this->Stelle->id,
						rolle::$hist_timestamp,
						$this->user->rolle->language
					)
				);

				$mapDB->save_postgis_attributes($layer['Layer_ID'], $attributes, '', '');
				$mapDB->delete_old_attributes($layer['Layer_ID'], $attributes);
			}
		}
	}

  function createRandomPassword() {
    $this->titel='Zufälliges Passwort';
    $this->main='genericTemplate.php';
    $this->param['height']=400;
    $this->param['str1']='<h3>10 sichere und zufällig erzeugte Passwörter</h3>';
    while($i++ < 10) {
      $this->param['str1'].='<br><b>'.createRandomPassword(8).'</b>';
    }
  }

  function closelogfiles(){
    $dump_rolle =  $this->database->create_update_dump('rolle');
    $dump_rolle2usedlayer =  $this->database->create_update_dump('u_rolle2used_layer');
    $dump_menue2rolle =  $this->database->create_update_dump('u_menue2rolle');
    $dump_groups2rolle =  $this->database->create_update_dump('u_groups2rolle');
    $this->database->logfile->write($dump_rolle);
    $this->database->logfile->write($dump_rolle2usedlayer);
    $this->database->logfile->write($dump_menue2rolle);
    $this->database->logfile->write($dump_groups2rolle);
    $this->main='showadminfunctions.php';
    $this->titel='Administrationsfunktionen';
  }

  function showAdminFunctions() {
    $this->main='showadminfunctions.php';
    $this->titel='Administrationsfunktionen';
  }

  function showConstants() {
		$this->main='showadminfunctions.php';
		$this->administration->get_constants_from_all_configs();
  }

  function grundbuchblattWahl() {
    $this->titel='Suche nach Grundbuchblättern';
    $this->main='grundbuchblattsuchform.php';
    $grundbuch = new grundbuch('', '', $this->pgdatabase);
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    if($GemeindenStelle != ''){   // Stelle ist auf Gemeinden eingeschränkt
      $Gemarkung=new gemarkung('',$this->pgdatabase);
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), NULL);
			$ganze_gemarkungen = array_merge($GemkgListe['GemkgID'], array_keys($GemeindenStelle['ganze_gemarkung']));
      $gbliste = $grundbuch->getGrundbuchbezirkslisteByGemkgIDs($ganze_gemarkungen, $GemeindenStelle['eingeschr_gemarkung']);
    }
    else{
      $gbliste = $grundbuch->getGrundbuchbezirksliste();
    }
    // Sortieren der Grundbuchbezirke unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['schluessel']);
    $gbliste['schluessel'] = $sorted_arrays['second_array'];
    $sorted_arrays = umlaute_sortieren($gbliste['bezeichnung'], $gbliste['beides']);
    $gbliste['bezeichnung'] = $sorted_arrays['array'];
    $gbliste['beides'] = $sorted_arrays['second_array'];
    $this->gbliste = $gbliste;
		####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			$this->formvars['selBlatt'] = implode(', ', $importliste);
			$this->formvars['Bezirk'] = substr($importliste[0], 0, 6);
		}
		##########################
    if($this->formvars['Bezirk'] != ''){
    	if($this->formvars['selBlatt'])$this->selblattliste = explode(', ',$this->formvars['selBlatt']);
			if($GemeindenStelle != ''){   // Stelle ist auf Gemeinden eingeschränkt
				$this->blattliste = $grundbuch->getGrundbuchblattlisteByGemkgIDs($this->formvars['Bezirk'], $ganze_gemarkungen, $GemeindenStelle['eingeschr_gemarkung']);
			}
			else{
				$this->blattliste = $grundbuch->getGrundbuchblattliste($this->formvars['Bezirk']);
			}
    }
    $this->output();
  }

  function grundbuchblattSuchen() {
  	$blaetter = explode(', ', $this->formvars['selBlatt']);
  	for($i = 0; $i < count($blaetter); $i++){
  		$blatt = explode('-', $blaetter[$i]);		# bezirk-blatt
	    # Prüfen der eingegebenen Parameter
	    $grundbuch=new grundbuch($blatt[0],$blatt[1],$this->pgdatabase);
	    $ret=$grundbuch->grundbuchblattSuchParameterPruefen();
	    if ($ret[0]) {
	      $this->Fehlermeldung='Angaben fehlerhaft:'.$ret[1];
	      $this->grundbuchblattWahl();
				return;
	    }
	    else {
	      # Suchparameter sind in Ordnung
	      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
	      $ret=$grundbuch->getBuchungen('','','',1);
	      if ($ret[0]) {
	        # Fehler bei der Abfrage der Flurstücke des Grundbuchblattes
	        $this->Fehlermeldung=$ret[1];
	        $this->grundbuchblattWahl();
					return;
	      }
	      else {
	        $buchungen=$ret[1];
	        # Test ob Flurstücke gefunden wurden
	        $anzFlst=count($buchungen);
	        if ($anzFlst==0) {
	          # Wenn keine Flurstücke gefunden wurden
	          $this->Fehlermeldung.='Es konnten keine Flurstücke zu dem Grundbuchblatt '.$blatt[0].'-'.$blatt[1].' gefunden werden.<br>';
	          $this->grundbuchblattWahl();
						return;
	        }
	        else {
	          # Es wurden Flurstücke gefunden, ins Ergebnisarray aufnehmen
	          $gbblaetter[] = $buchungen;
	        } # Ende mit Flurstücksanzeige
	      } # Ende mit Flurstücke erfolgreich abgefragt
	    } # Ende mit Suchparameter sind in Ordnung
  	}
		$this->user->rolle->delete_last_query();
		$this->user->rolle->save_last_query('Grundbuchblatt_Auswaehlen_Suchen', 0, $this->formvars['selBlatt'], NULL, NULL, NULL);
  	$this->grundbuchblattanzeige($gbblaetter);
  }

  function grundbuchblattanzeige($gbblaetter) {
    $this->main='grundbuchblattanzeige.php';
    $this->titel='Buchungen zum Grundbuchblatt';
    $this->gbblaetter=$gbblaetter;
    $this->output();
  }

  function getMenueWithAjax() {
    $this->loadMap('DataBase');
    $this->drawMap();
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
		$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
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
					$this->formvars['angle'] = $this->point['angle'];
					$rect = ms_newRectObj();
					$rect->minx = $this->point['pointx'] - 100;
					$rect->maxx = $this->point['pointx'] + 100;
					$rect->miny = $this->point['pointy'] - 100;
					$rect->maxy = $this->point['pointy'] + 100;
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
		# zoomToMaxLayerExtent
		if ($this->formvars['zoom_layer_id'] != '') {
			$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
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
		include_once(CLASSPATH . 'pointeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		# eingeabewerte pruefen:
		$ret = $pointeditor->pruefeEingabedaten($this->formvars['loc_x'], $this->formvars['loc_y']);
		if ($ret[0]) { # fehlerhafte eingabedaten
			$this->add_message('error', $ret[1]);
			$this->PointEditor();
			return;
		}
		else {
			$ret = $pointeditor->eintragenPunkt($this->formvars['loc_x'], $this->formvars['loc_y'], $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->formvars['dimension']);
			if ($ret[0]) { # fehler beim eintrag
				$this->add_message('error', $ret[1]);
			}
			else { # eintrag erfolgreich
				# wenn Time-Attribute vorhanden, aktuelle Zeit speichern
				$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
				for ($i = 0; $i < count($this->attributes['type']); $i++) {
					$value = '';
					if ($this->attributes['name'][$i] != 'oid') {
						switch (true) {
							case (
								$this->attributes['form_element_type'][$i] == 'Time' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = "'" . date('Y-m-d G:i:s') . "'";
							break;
							case (
								$this->attributes['form_element_type'][$i] == 'User' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = "'" . $this->user->Vorname . " " . $this->user->Name . "'";
							break;
							case (
								$this->attributes['form_element_type'][$i] == 'UserID' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = $this->user->id;
							break;
							case (
								$this->attributes['form_element_type'][$i] == 'Winkel'
							) : $value = $this->formvars['angle'];
							break;
							default : $value = "";
						}
						if ($value != "") {
							$sql = "
								UPDATE
									" . $this->formvars['layer_tablename'] . "
								SET
									" . $this->attributes['name'][$i] . " = " . $value ."
								WHERE
									oid = '" . $this->formvars['oid'] . "'
							";
							$this->debug->write("<p>file:kvwmap :PointEditor_Senden :", 4);
							$ret2 = $layerdb->execSQL($sql, 4, 1);
						}
					}
				}
				$this->add_message('notice', 'Eintrag erfolgreich!');
				if($ret[3])$this->add_message('info', $ret[3]);
			}
			$this->PointEditor();
		}
	}

  function LineEditor(){
		include_once (CLASSPATH.'lineeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(30);
    $this->main='LineEditor.php';
    $this->titel='Geometrie bearbeiten';
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		if($this->formvars['geom_from_layer'] == '')$this->formvars['geom_from_layer'] = $layerset[0]['geom_from_layer'];
		$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['layer_columnname'] = $attributes['the_geom'];
		$this->formvars['layer_tablename'] = $attributes['table_name'][$attributes['the_geom']];
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		if(!$this->formvars['edit_other_object'] AND ($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != '')){
			$this->neuLaden();
			$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
		}
		else{
			$this->user->rolle->saveGeomFromLayer($this->formvars['selected_layer_id'], $this->formvars['geom_from_layer']);
			$this->loadMap('DataBase');
			if($this->formvars['oid'] != '' AND $this->formvars['no_load'] != 'true'){
				# Linien abfragen
				$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
				$this->lines = $lineeditor->getlines($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
				if($this->lines['wktgeom'] != ''){
					$this->formvars['newpathwkt'] = $this->lines['wktgeom'];
					$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
					$this->formvars['newpath'] = str_replace('-', '', $this->lines['svggeom']);
					$this->formvars['newpath'] = str_replace('L ', '', $this->formvars['newpath']);		# neuere Postgis-Versionen haben ein L mit drin
					$this->formvars['firstline'] = 'true';
					if($this->formvars['zoom'] != 'false'){
						$rect = $lineeditor->zoomToLine($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
						$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
						if (MAPSERVERVERSION > 600) {
							$this->map_scaledenom = $this->map->scaledenom;
						}
						else {
							$this->map_scaledenom = $this->map->scale;
						}
					}
				}
			}
    }
		# Zoom auf Geometrie-Fehler-Position
		if ($this->error_position != '') {
			$rect = ms_newRectObj();
			$this->map->setextent($this->error_position[0]-50,$this->error_position[1]-50,$this->error_position[0]+50,$this->error_position[1]+50);
			if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
		}		
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
    # Spaltenname und from-where abfragen
    $data = $this->mapDB->getData($this->formvars['geom_from_layer']);
    $data_explosion = explode(' ', $data);
    $this->formvars['columnname'] = $data_explosion[0];
    $select = $fromwhere = $this->mapDB->getSelectFromData($data);
		# order by rausnehmen
		$this->formvars['orderby'] = '';
		$orderbyposition = strrpos(strtolower($select), 'order by');
		$lastfromposition = strrpos(strtolower($select), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			$fromwhere = substr($select, 0, $orderbyposition);
			$this->formvars['orderby'] = ' '.substr($select, $orderbyposition);
		}
    $this->formvars['fromwhere'] = 'from ('.$fromwhere.') as foo where 1=1';
    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
      $this->formvars['fromwhere'] .= ' where (1=1)';
    }

    $this->saveMap('');
    if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->output();
  }

	function LineEditor_Senden() {
		include_(CLASSPATH . 'lineeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		# eingeabewerte pruefen:
		$ret = $lineeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
		if ($ret[0]) { # fehlerhafte eingabedaten
			$this->error_position = explode(' ', trim(substr($ret[1], strpos($ret[1], '[')), '[]'));
			$this->formvars['no_load'] = 'true';
			$this->Meldung=$ret[1];
			$this->LineEditor();
			return;
		}
		else {
			$umring = $this->formvars['newpathwkt'];
			$ret = $lineeditor->eintragenLinie($umring, $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->attributes['geomtype'][$this->attributes['the_geom']]);
			if ($ret[0]) { # fehler beim eintrag
				$this->add_message('error', $ret[1]);
#				$this->Meldung=$ret[1];
			}
			else { # eintrag erfolgreich
				# wenn Time-Attribute vorhanden, aktuelle Zeit speichern
				for ($i = 0; $i < count($this->attributes['type']); $i++) {
					$value = '';
					if (
						$this->attributes['name'][$i] != 'oid' AND
						in_array($this->attributes['form_element_type'][$i], array('Time', 'Länge', 'User', 'UserID'))
					) {
						switch (true) {
							case (
								$this->attributes['form_element_type'][$i] == 'Time' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = "'" . date('Y-m-d G:i:s') . "'";
							break;
							case (
								$this->attributes['form_element_type'][$i] == 'Länge'
							) : $value = "'". $this->formvars['linelength'] . "'";
								break;
							case (
								$this->attributes['form_element_type'][$i] == 'User' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = "'" . $this->user->Vorname . " " . $this->user->Name . "'";
							break;
							case (
								$this->attributes['form_element_type'][$i] == 'UserID' AND
								in_array($this->attributes['options'][$i], array('', 'update'))
							) : $value = $this->user->id;
								break;
						}
						if ($value != '') {
							$sql = "
								UPDATE
									" . $this->formvars['layer_tablename'] . "
								SET
									" . $this->attributes['name'][$i] . " = " . $value . "
								WHERE
									oid = " . $this->formvars['oid'] . "
							";
							$this->debug->write("<p>file:kvwmap :LineEditor_Senden :", 4);
							$ret = $layerdb->execSQL($sql, 4, 1);
						}
					}
				}
				$this->formvars['newpath'] = "";
				$this->formvars['newpathwkt'] = "";
				$this->formvars['pathwkt'] = "";
				$this->formvars['firstline'] = "";
				$this->formvars['secondline'] = "";
				$this->formvars['secondpoly'] = "";
				$this->add_message('notice', 'Eintrag erfolgreich!');
			}
			$this->formvars['CMD'] = '';
			$this->LineEditor();
		}
	}

	function PolygonEditor() {
		include_once (CLASSPATH . 'polygoneditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(30);
		$this->main='PolygonEditor.php';
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
		$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
		$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);

		if (
			!$this->formvars['edit_other_object'] AND
			($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != '')
		) {
			$this->neuLaden();
			$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
		}
		else {
			$this->user->rolle->saveGeomFromLayer($this->formvars['selected_layer_id'], $this->formvars['geom_from_layer']);
			$this->loadMap('DataBase');
			if($this->formvars['oid'] != '' AND $this->formvars['no_load'] != 'true'){
				# Polygon abfragen
				$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
				$this->polygon = $polygoneditor->getpolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->map->extent);
				if($this->polygon['wktgeom'] != ''){
					$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
					$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
					$this->formvars['newpath'] = $this->polygon['svggeom'];
					$this->formvars['firstpoly'] = 'true';
					if($this->formvars['zoom'] != 'false'){
						$rect = $polygoneditor->zoomTopolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL);
						$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
						if (MAPSERVERVERSION > 600) {
							$this->map_scaledenom = $this->map->scaledenom;
						}
						else {
							$this->map_scaledenom = $this->map->scale;
						}
					}
				}
			}
		}
		# Zoom auf Geometrie-Fehler-Position
		if ($this->error_position != '') {
			$rect = ms_newRectObj();
			$this->map->setextent($this->error_position[0]-50,$this->error_position[1]-50,$this->error_position[0]+50,$this->error_position[1]+50);
			if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
		}		
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
		# Geometrie-Übernahme-Layer:
		# Spaltenname und from-where abfragen
		$data = $this->mapDB->getData($this->formvars['geom_from_layer']);
		#echo $data;
		$data_explosion = explode(' ', $data);
		$this->formvars['columnname'] = $data_explosion[0];
		$select = $fromwhere = $this->mapDB->getSelectFromData($data);

		# order by rausnehmen
		$this->formvars['orderby'] = '';
		$orderbyposition = strrpos(strtolower($select), 'order by');
		$lastfromposition = strrpos(strtolower($select), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			$fromwhere = substr($select, 0, $orderbyposition);
			$this->formvars['orderby'] = ' '.substr($select, $orderbyposition);
		}

		$this->formvars['fromwhere'] = 'from ('.$fromwhere.') as foo where 1=1';
		if (strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
			$this->formvars['fromwhere'] .= ' where (1=1)';
		}

		if ($this->formvars['newpath'] == '' AND $this->formvars['geom_from_layer'] < 0){	# Suchergebnislayer sofort selektieren
			$rollenlayer = $this->mapDB->read_RollenLayer(-$this->formvars['geom_from_layer']);
			if($rollenlayer[0]['Typ'] == 'search'){
				$layerdb1 = $this->mapDB->getlayerdatabase($this->formvars['geom_from_layer'], $this->Stelle->pgdbhost);
				include_once (CLASSPATH.'polygoneditor.php');
				$polygoneditor = new polygoneditor($layerdb1, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
				$tablename = '('.$fromwhere.') as foo';
				$this->polygon = $polygoneditor->getpolygon(NULL, $tablename, $this->formvars['columnname'], $this->map->extent);
				if($this->polygon['wktgeom'] != '') {
					$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
					$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
					$this->formvars['newpath'] = $this->polygon['svggeom'];
					$this->formvars['firstpoly'] = 'true';
				}
			}
		}
		if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
			$currenttime=date('Y-m-d H:i:s',time());
			$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		}
		$this->drawMap();
		$this->saveMap('');
		$this->output();
	}

	function PolygonEditor_Senden(){
		include_(CLASSPATH.'polygoneditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		# eingeabewerte pruefen:
		$ret = $polygoneditor->pruefeEingabedaten($this->formvars['newpathwkt']);
		if ($ret[0]) { # fehlerhafte eingabedaten
			if (strpos($ret[1], '[') !== false) {
				$this->error_position = explode(' ', trim(substr($ret[1], strpos($ret[1], '[')), '[]'));
				$this->formvars['no_load'] = 'true';
			}
			$this->Meldung = $ret[1];
			$this->PolygonEditor();
			return;
		}
		else {
			$umring = $this->formvars['newpathwkt'];
			$ret = $polygoneditor->eintragenFlaeche(
				$umring, $this->formvars['oid'],
				$this->formvars['layer_tablename'],
				$this->formvars['layer_columnname'],
				$this->attributes['geomtype'][$this->attributes['the_geom']]
			);
			if (!$ret['success']) { # fehler beim eintrag
				$this->Meldung = $ret[1];
			}
			else { # eintrag erfolgreich
				# wenn auto-Attribute vorhanden, auto-Werte eintragen
				for ($i = 0; $i < count($this->attributes['type']); $i++){
					if (
						$this->attributes['name'][$i] != 'oid' AND
						$this->attributes['form_element_type'][$i] == 'Time' AND
						in_array($this->attributes['options'][$i], array('', 'update'))
					) {
						$sql = "UPDATE " . $this->formvars['layer_tablename']." SET " . $this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '" . $this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret2 = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Fläche'){
						$sql = "UPDATE " . $this->formvars['layer_tablename']." SET " . $this->attributes['name'][$i]." = '" . $this->formvars['area']."' WHERE oid = '" . $this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret2 = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User' AND in_array($this->attributes['options'][$i], array('', 'update'))){
						$sql = "UPDATE " . $this->formvars['layer_tablename']." SET " . $this->attributes['name'][$i]." = '" . $this->user->Vorname." " . $this->user->Name."' WHERE oid = '" . $this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret2 = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'UserID'  AND in_array($this->attributes['options'][$i], array('', 'update'))){
						$sql = "UPDATE " . $this->formvars['layer_tablename']." SET " . $this->attributes['name'][$i]." = " . $this->user->id." WHERE oid = '" . $this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret2 = $layerdb->execSQL($sql,4, 1);
					}
				}
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstpoly']="";
        $this->formvars['secondpoly']="";
        $this->add_message('notice', 'Eintrag erfolgreich!');
				if($ret[3])$this->add_message('info', $ret[3]);
      }
      $this->formvars['CMD'] = '';
      $this->PolygonEditor();
    }
  }

	function zoomto_selected_datasets(){
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $oids[] = $element[3];
      }
    }

		if($this->formvars['no_query'] != true){
			$this->last_query = $this->user->rolle->get_last_query($this->formvars['chosen_layer_id']);
			#if($this->formvars['search']){        # man kam von der Suche   -> nochmal suchen
				$this->formvars['embedded_dataPDF'] = true;		# damit der Aufruf von output() verhindert wird
				$this->GenerischeSuche_Suchen();
			#}
			#else{                                 # man kam aus einer Sachdatenabfrage    -> nochmal abfragen			# den Fall kann man wohl ignorieren, weil die Suche bei lastquery auch für die Kartenabfrage funktioniert...
			#	$this->queryMap();
			#}
			$attributes = $this->qlayerset[0]['attributes'];
			$attribute = $this->formvars['klass_'.$this->formvars['chosen_layer_id']];
			$result= $this->qlayerset[0]['shape'];
			for($i = 0; $i < count($result); $i++){
				$geom_oids[] = $result[$i][$this->formvars['layer_tablename'].'_oid'];
			}
		}
		else{			# wird von der Jagdbezirkssuche aus verwendet
			$geom_oids = $oids;
		}

    if($oids != ''){
      # Layer erzeugen
      $data = $dbmap->getData($this->formvars['chosen_layer_id']);
      $select = $dbmap->getSelectFromData($data);

			$orderbyposition = strrpos(strtolower($select), 'order by');
			$lastfromposition = strrpos(strtolower($select), 'from');
			if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
      	$orderby = substr($select, $orderbyposition);
        $select = substr($select, 0, $orderbyposition);
      }
      if(strpos(strtolower($select), 'oid') === false){
      	$select = str_replace($this->formvars['layer_columnname'], 'oid, '.$this->formvars['layer_columnname'], $select);
      	$select = str_replace('*', '*, oid', $select);
      }
      if($attribute != '' AND strpos($select, '*') === false AND strpos($select, $attribute) === false){			# Attribut für automatische Klassifizierung mit ins data packen
      	$select = str_replace(' from ', ', '.$attribute.' from ', strtolower($select));
      }
      if(strpos(strtolower($select), ' where ') === false){
        $select .= " WHERE ";
      }
      else{
        $select .= " AND ";
      }
      $oid = 'oid';
      $explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
      for($i = 0; $i < count($explosion); $i++){
      	if(strpos(strtolower($explosion[$i]), '.oid') !== false){
      		$oid = str_replace('select ', '', strtolower($explosion[$i]));
      		break;
      	}
      }
      $select .= $oid." IN (";
      for($i = 0; $i < count($oids); $i++){
      	$select .= "'" . $oids[$i]."',";
      }
      $select = substr($select, 0, -1);
      $select .= ")";
      $datastring = $this->formvars['layer_columnname']." from (" . $select.' '.$orderby;
      $datastring.=") as foo using unique oid using srid=" . $layerset[0]['epsg_code'];
			if($layerset[0]['alias'] != '' AND $this->Stelle->useLayerAliases){
				$layerset[0]['Name'] = $layerset[0]['alias'];
			}
      $legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";

      $group = $dbmap->getGroupbyName('Suchergebnis');
      if($group != ''){
        $groupid = $group['id'];
      }
      else{
        $groupid = $dbmap->newGroup('Suchergebnis', 0);
      }

      $this->formvars['user_id'] = $this->user->id;
      $this->formvars['stelle_id'] = $this->Stelle->id;
      $this->formvars['aktivStatus'] = 1;
      $this->formvars['Name'] = $legendentext;
      $this->formvars['Gruppe'] = $groupid;
      $this->formvars['Typ'] = 'search';
      $this->formvars['Datentyp'] = $layerset[0]['Datentyp'];;
      $this->formvars['Data'] = $datastring;
      $this->formvars['connectiontype'] = 6;
      $this->formvars['labelitem'] = $layerset[0]['labelitem'];
      $connectionstring ='user='.$layerdb->user;
      if($layerdb->passwd != ''){
        $connectionstring.=' password='.$layerdb->passwd;
      }
      $connectionstring.=' dbname='.$layerdb->dbName;
      if($layerdb->host != ''){
        $connectionstring.=' host='.$layerdb->host;
      }
      if($layerdb->port != ''){
        $connectionstring.=' port='.$layerdb->port;
      }
      $this->formvars['connection'] = $connectionstring;
      $this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
      $this->formvars['transparency'] = 75;

      $layer_id = $dbmap->newRollenLayer($this->formvars);

      if($this->formvars['selektieren'] != '1'){      # highlighten (gelb)
      	# ------------ automatische Klassifizierung -------------------
      	if($attribute != ''){
					for($i = 0; $i < count($result); $i++){		# Auswahlfelder behandeln
						foreach($result[$i] As $key => $value){
							$name = $value;
							$j = $attributes['indizes'][$key];
							if($attributes['type'][$j] != 'geometry' AND $attributes['name'][$i] != 'lock'){
								if($attributes['form_element_type'][$j] == 'Auswahlfeld'){
									if(is_array($attributes['dependent_options'][$j])){
										$enum_value = $attributes['enum_value'][$j][$i];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
										$enum_output = $attributes['enum_output'][$j][$i];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
									}
									else{
										$enum_value = $attributes['enum_value'][$j];
										$enum_output = $attributes['enum_output'][$j];
									}
									for($o = 0; $o < count($enum_value); $o++){
										if($value == $enum_value[$o]){
											$name = $enum_output[$o];
											break;
										}
									}
								}
							}
							if($attribute === $key){
								$classes[$value] = $name;
							}
						}
					}
      		$dbmap->createAutoClasses(array_unique($classes), $attribute, $layer_id, $this->formvars['Datentyp'], $this->database);
      	}
      	# ------------ automatische Klassifizierung -------------------
      	else{
      		$color = $this->user->rolle->readcolor();
	        $classdata['layer_id'] = -$layer_id;
					$classdata['name'] = ' ';
	        $class_id = $dbmap->new_Class($classdata);
	        if($this->formvars['Datentyp'] == 0){			# Punkt
						if(defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
							$style_id = $dbmap->copyStyle(ZOOM2POINT_STYLE_ID);
						}
						else{
							# highlighten (mit der ausgewählten Farbe)
							$color = $this->user->rolle->readcolor();
							$style['colorred'] = $color['red'];
							$style['colorgreen'] = $color['green'];
							$style['colorblue'] = $color['blue'];
							$style['outlinecolorred'] = 0;
							$style['outlinecolorgreen'] = 0;
							$style['outlinecolorblue'] = 0;
							$style['size'] = 10;
							$style['symbolname'] = 'circle';
							$style['backgroundcolor'] = NULL;
							$style['minsize'] = NULL;
							$style['maxsize'] = 100000;
							if (MAPSERVERVERSION > '500') {
								$style['angle'] = 360;
							}
							$style_id = $dbmap->new_Style($style);
						}
	        }
	        else{
	        	$style['colorred'] = $color['red'];
		        $style['colorgreen'] = $color['green'];
		        $style['colorblue'] = $color['blue'];
		        $style['outlinecolorred'] = 0;
		        $style['outlinecolorgreen'] = 0;
		        $style['outlinecolorblue'] = 0;
		       	$style['size'] = 3;
		       	if($this->formvars['Datentyp'] == 1){		# Linie
		       		$style['symbol'] = 9;
		       	}
		       	else{
		       		$style['symbol'] = NULL;
		       	}
		        $style['symbolname'] = NULL;
		        $style['backgroundcolor'] = NULL;
		        $style['minsize'] = 3;
		        $style['maxsize'] = 3;
		        if (MAPSERVERVERSION > '500') {
		        	$style['angle'] = 360;
		        }
		        $style_id = $dbmap->new_Style($style);
	        }
					$dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
      	}
      }
      else{         # selektieren (eigenen Style verwenden)
				$classes = $dbmap->read_Classes($this->formvars['chosen_layer_id']);
				for($i = 0; $i < count($classes); $i++){
					$dbmap->copyClass($classes[$i]['Class_ID'], -$layer_id);
				}
        $this->user->rolle->setOneLayer($this->formvars['chosen_layer_id'], 0);
      }

      $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
      $this->loadMap('DataBase');
      # Polygon abfragen und Extent setzen
      $rect = $dbmap->zoomToDatasets($geom_oids, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10, $layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
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

	function createZoomRollenlayer($dbmap, $layerdb, $layerset){
		# Layer erzeugen
		$data = $dbmap->getData($this->formvars['layer_id']);
		$explosion = explode(' ', $data);
		$datageom = $explosion[0];
		$select = $dbmap->getSelectFromData($data);

		$orderbyposition = strrpos(strtolower($select), 'order by');
		$lastfromposition = strrpos(strtolower($select), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			$select = substr($select, 0, $orderbyposition);
		}
		if(strpos(strtolower($select), 'oid') === false){
			$select = str_replace('*', '*, oid', $select);
			$select = str_replace_first($datageom, $datageom.', oid', $select);
		}

		if(strpos(strtolower($select), 'where') === false){
			$select .= " WHERE ";
		}
		else{
			$select .= " AND ";
		}
		$oid = 'oid';
		$explosion = explode(',', $select);							# wenn im Data sowas wie tabelle.oid vorkommt, soll das anstatt oid verwendet werden
		for($i = 0; $i < count($explosion); $i++){
			if(strpos(strtolower($explosion[$i]), '.oid') !== false){
				$oid = str_replace('select ', '', strtolower($explosion[$i]));
				break;
			}
		}
		$select .= $oid." = '" . $this->formvars['oid']."'";

		$datastring = $datageom." from (" . $select;
		$datastring.=") as foo using unique oid using srid=" . $layerset[0]['epsg_code'];
		if($layerset[0]['alias'] != '' AND $this->Stelle->useLayerAliases){
			$layerset[0]['Name'] = $layerset[0]['alias'];
		}
		$legendentext = $layerset[0]['Name']." (".date('d.m. H:i',time()).")";

		$group = $dbmap->getGroupbyName('Suchergebnis');
		if($group != ''){
			$groupid = $group['id'];
		}
		else{
			$groupid = $dbmap->newGroup('Suchergebnis', 0);
		}
		$this->formvars['user_id'] = $this->user->id;
		$this->formvars['stelle_id'] = $this->Stelle->id;
		$this->formvars['aktivStatus'] = 1;
		$this->formvars['Name'] = $legendentext;
		$this->formvars['Gruppe'] = $groupid;
		$this->formvars['Typ'] = 'search';
		$this->formvars['Datentyp'] = $layerset[0]['Datentyp'];
		$this->formvars['Data'] = $datastring;
		$this->formvars['connectiontype'] = 6;
		if($layerset[0]['labelitem'] != 'Cluster:FeatureCount')$this->formvars['labelitem'] = $layerset[0]['labelitem'];
		$this->formvars['classitem'] = $layerset[0]['classitem'];
		$connectionstring ='user='.$layerdb->user;
		if($layerdb->passwd != ''){
			$connectionstring.=' password='.$layerdb->passwd;
		}
		$connectionstring.=' dbname='.$layerdb->dbName;
		if($layerdb->host != ''){
			$connectionstring.=' host='.$layerdb->host;
		}
		if($layerdb->port != ''){
			$connectionstring.=' port='.$layerdb->port;
		}
		$this->formvars['connection'] = $connectionstring;
		$this->formvars['epsg_code'] = $layerset[0]['epsg_code'];
		if($layerset[0]['Datentyp'] == MS_LAYER_POLYGON)$this->formvars['transparency'] = $this->user->rolle->result_transparency;
		else $this->formvars['transparency'] = 100;

		$layer_id = $dbmap->newRollenLayer($this->formvars);

		$color = $this->user->rolle->readcolor();
		$style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];

		if($this->formvars['selektieren'] == 'false'){      # highlighten (mit der ausgewählten Farbe)
			$classdata['layer_id'] = -$layer_id;
			$class_id = $dbmap->new_Class($classdata);
			$this->formvars['class'] = $class_id;
			switch ($layerset[0]['Datentyp']){
				case MS_LAYER_POINT : {
					if(defined('ZOOM2POINT_STYLE_ID') AND ZOOM2POINT_STYLE_ID != ''){
						$style_id = $dbmap->copyStyle(ZOOM2POINT_STYLE_ID);
					}
					else{
						# highlighten (mit der ausgewählten Farbe)
						$style['outlinecolorred'] = 0;
						$style['outlinecolorgreen'] = 0;
						$style['outlinecolorblue'] = 0;
						$style['size'] = 10;
						$style['symbolname'] = 'circle';
						$style['backgroundcolor'] = NULL;
						$style['minsize'] = NULL;
						$style['maxsize'] = 100000;
						$style['angle'] = 360;
						$style_id = $dbmap->new_Style($style);
					}
				}break;

				case MS_LAYER_LINE : {
					$style['outlinecolorred'] = -1;
					$style['outlinecolorgreen'] = -1;
					$style['outlinecolorblue'] = -1;
					$style['size'] = NULL;
					$style['symbol'] = NULL;
					$style['symbolname'] = NULL;
					$style['backgroundcolor'] = NULL;
					$style['minsize'] = NULL;
					$style['maxsize'] = NULL;
					$style['width'] = 3;
					$style['minwidth'] = 3;
					$style['maxwidth'] = 3;
					$style_id = $dbmap->new_Style($style);
				}break;

				case MS_LAYER_POLYGON : {
					$style['outlinecolorred'] = 0;
					$style['outlinecolorgreen'] = 0;
					$style['outlinecolorblue'] = 0;
					$style['size'] = 1;
					$style['symbol'] = NULL;
					if($this->user->rolle->result_hatching){
						$style['symbolname'] = 'hatch';
						$style['size'] = 11;
						$style['width'] = 5;
						$style['angle'] = 45;
					}
					else{
						$style['symbolname'] = NULL;
					}
					$style['backgroundcolor'] = NULL;
					$style_id = $dbmap->new_Style($style);
				}break;
			}
			$dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
		}
		else{         # selektieren (eigenen Style verwenden)
			$class_id = $dbmap->getClassFromObject($select, $this->formvars['layer_id'], $layerset[0]['classitem']);
			$this->formvars['class'] = $dbmap->copyClass($class_id, -$layer_id);
			$this->user->rolle->setOneLayer($this->formvars['layer_id'], 0);
		}
		$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		return $layer_id;
	}

  function zoom_toLine(){
		include_(CLASSPATH.'lineeditor.php');
    # aktuellen Kartenausschnitt laden
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oid'] != ''){
			if($this->formvars['selektieren'] != 'zoomonly'){
				$this->createZoomRollenlayer($dbmap, $layerdb, $layerset);
			}
      $this->loadMap('DataBase');
      # Linie abfragen und Extent setzen
      $rect = $lineeditor->zoomToLine($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
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
		$this->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit Suchergebnis-Layer angezeigt werden
    $this->output();
  }

  function zoom_toPolygon(){
		include_(CLASSPATH.'polygoneditor.php');
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oid'] != ''){
    	if($this->formvars['selektieren'] != 'zoomonly'){
	      $this->createZoomRollenlayer($dbmap, $layerdb, $layerset);
    	}
      $this->loadMap('DataBase');
      # Polygon abfragen und Extent setzen
      $rect = $polygoneditor->zoomTopolygon($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL);
      $this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);

			# damit nicht außerhalb des Stellen-Extents gezoomt wird
	    $oPixelPos=ms_newPointObj();
	    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
	    if (MAPSERVERVERSION > 600) {
				$this->map->zoomscale($this->map->scaledenom,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map->zoomscale($this->map->scale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scale;
			}
    }
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
		$this->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit Suchergebnis-Layer angezeigt werden
    $this->output();
  }

	function get_rect_by_buffer($x, $y, $rand) {
		$rect = ms_newRectObj();
		$rect->minx = $x - $rand;
		$rect->maxx = $x + $rand;
		$rect->miny = $y - $rand;
		$rect->maxy = $y + $rand;
		return $rect;
	}

	function get_rect_by_scale($x, $y, $scale) {
		$rect = ms_newRectObj();
		$width_rand  = 0.02535 / 96 * $this->user->rolle->nImageWidth  * $scale / 2;
		$height_rand = 0.02535 / 96 * $this->user->rolle->nImageHeight * $scale / 2;
		$rect->minx = $x - $width_rand;
		$rect->maxx = $x + $width_rand;
		$rect->miny = $y - $height_rand;
		$rect->maxy = $y + $height_rand;
		return $rect;
	}

  function zoom_toPoint(){
		include_(CLASSPATH.'pointeditor.php');
    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $layerdb = $dbmap->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
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
		$this->layerhiddenstring = 'reload ';		// Legenden-Reload erzwingen, damit Suchergebnis-Layer angezeigt werden
    $this->output();
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
    $this->main='haltestellensuche.php';
    $this->titel='Haltestellensuche';
    if ($this->formvars['defaultAddress'] == '') {
	  echo $this->formvars['defaultAddress'];
	  $this->formvars['defaultAddress']='hier eine Adresse eingeben';
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
    if(!$this->formvars['aktiverRahmen']){
      $this->formvars['aktiverRahmen'] = $frameid;
    }
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);
    $this->Document->selectedframe = $this->Document->load_frames(NULL, $this->formvars['aktiverRahmen']);
    if($this->Document->selectedframe != NULL){
      $ratio = $this->Document->selectedframe[0]['mapwidth']/$this->Document->selectedframe[0]['mapheight'];
      $this->formvars['worldprintwidth'] = $this->Document->selectedframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.0003526;
      $this->formvars['worldprintheight'] = $this->Document->selectedframe[0]['mapheight'] * $this->formvars['printscale'] * 0.0003526;
			$this->formvars['map_factor'] = 1;
      if($this->Document->selectedframe[0]['dhk_call'] == '')$this->previewfile = $this->createMapPDF($this->formvars['aktiverRahmen'], true, true);

			# Fonts auslesen
			include_(CLASSPATH . 'datendrucklayout.php');
			$ddl = new ddl($this->database);
			$this->Document->fonts = $ddl->get_fonts();
			$this->Document->din_formats = $ddl->get_din_formats();

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
			$this->Document->din_formats = $ddl->get_din_formats();

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

  function druckausschnittswahl($loadmapsource){
		global $selectable_scales;
		$this->selectable_scales = array_reverse($selectable_scales);
		$saved_scale = $this->reduce_mapwidth(10);
    $this->main="druckausschnittswahl.php";
		$this->Document=new Document($this->database);
    # aktuellen Kartenausschnitt laden + zeichnen!
		$this->noMinMaxScaling = $this->formvars['no_minmax_scaling'];
  	if($this->formvars['neuladen']){
      $this->neuLaden();
    }
    else{
      $this->loadMap($loadmapsource);
    }
		# Redlining Polygone
		$this->addRedlining();

		if($saved_scale != NULL)$this->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
		# Kartendrucklayouts laden
    $this->Document->frames = $this->Document->load_frames($this->Stelle->id, NULL);
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
			$zoll_pro_einheit = InchesPerUnit(MS_DD, $center_y);
			$this->meter_pro_einheit = $zoll_pro_einheit / 39.3701;
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
      $rect= ms_newRectObj();
      $rect->minx = $this->Document->ausschnitt[0]['center_x'] - $width/2;
      $rect->miny = $this->Document->ausschnitt[0]['center_y'] - $height/2;
      $rect->maxx = $this->Document->ausschnitt[0]['center_x'] + $width/2;
      $rect->maxy = $this->Document->ausschnitt[0]['center_y'] + $height/2;
			$this->formvars['refpoint_x'] = $this->Document->ausschnitt[0]['center_x'];
			$this->formvars['refpoint_y'] = $this->Document->ausschnitt[0]['center_y'];
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
      $this->formvars['center_x'] = $this->Document->ausschnitt[0]['center_x'];
      $this->formvars['center_y'] = $this->Document->ausschnitt[0]['center_y'];
      # Druckmaßstab setzen
      $this->formvars['printscale'] = $this->Document->ausschnitt[0]['print_scale'];
      # Drehwinkel setzen
      $this->formvars['angle'] = $this->Document->ausschnitt[0]['angle'];
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

  function druckausschnitt_speichern($loadmapsource){
    $this->loadMap($loadmapsource);
    $this->Document = new Document($this->database);
    $this->Document->save_ausschnitt($this->Stelle->id, $this->user->id, $this->formvars['name'], $this->formvars['center_x'], $this->formvars['center_y'], $this->formvars['printscale'], $this->formvars['angle'], $this->formvars['aktiverRahmen']);
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
    $this->map->set('resolution',72);
    $this->map->legend->set("keysizex", $size*1.8*$this->map_factor);
    $this->map->legend->set("keysizey", $size*1.8*$this->map_factor);
    $this->map->legend->set("keyspacingx", $size*$this->map_factor);
    $this->map->legend->set("keyspacingy", $size*0.83*$this->map_factor);
    $this->map->legend->label->set("size", $size*$this->map_factor);
		$this->map->legend->label->set("type", 'truetype');
		$this->map->legend->label->set("font", 'arial');
    $this->map->legend->label->set("position", MS_C);
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
      $layer->set('status', 0);
    }
    $scale = $this->map_scaledenom * $this->map_factor / 1.414;
    $legendimage = imagecreatetruecolor(1,1);
    $backgroundColor = ImageColorAllocate($legendimage, 255, 255, 255);
    imagefill ($legendimage, 0, 0, $backgroundColor);

    for($i = 0; $i < count($layerset['list']); $i++){
      if($layerset['list'][$i]['aktivStatus'] != 0){
        if(($layerset['list'][$i]['minscale'] < $scale OR $layerset['list'][$i]['minscale'] == 0) AND ($layerset['list'][$i]['maxscale'] > $scale OR $layerset['list'][$i]['maxscale'] == 0)){
					if($all_active_layers OR $this->formvars['legendlayer'.$layerset['list'][$i]['Layer_ID']] == 'on'){
						if($layerset['list'][$i]['alias'] != '' AND $this->Stelle->useLayerAliases)$name = $layerset['list'][$i]['alias'];
						else $name = $layerset['list'][$i]['Name'];
						$layer = $this->map->getLayerByName($name);
						if($layerset['list'][$i]['showclasses']){
							for($j = 0; $j < $layer->numclasses; $j++){
								$class = $layer->getClass($j);
								if($class->name != '')$draw = true;
							}
						}
					}
        }
        if($draw == true){
          $layer->set('status', 1);
          if($layer->connectiontype != 7){
	          $classimage = $this->map->drawLegend();
	          $filename = $this->map_saveWebImage($classimage,'jpeg');
	          $newname = $this->user->id.basename($filename);
	          rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
	          $classimage = imagecreatefromjpeg(IMAGEPATH.$newname);
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

          $layer->set('status', 0);
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

  function getlegendimage($layer_id, $style_id){
    # liefert eine url zu einem Legendenbild eines Layers mit einem bestimmten Style
    $mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
    $map = ms_newMapObj(DEFAULTMAPFILE);
    $map->setextent(100,100,200,200);
    $map->set('width',10);
    $map->set('height',10);
    $map->web->set('imagepath', IMAGEPATH);
    $map->web->set('imageurl', IMAGEURL);
    $map->setSymbolSet(SYMBOLSET);
    $map->setFontSet(FONTSET);

    $layer=ms_newLayerObj($map);
    $layerset = $mapDB->get_Layer($layer_id);
    $layer->set('data',$layerset['Data']);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name', 'test');
    $layer->set('type', $layerset['Datentyp']);
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype',$layerset['connectiontype']);
    }
    else {
      $layer->setConnectionType($layerset['connectiontype']);
    }
		$layer->set(
			'connection',
			replace_params(
				$layerset['connection'],
				rolle::$layer_params,
				$this->user->id,
				$this->Stelle->id,
				rolle::$hist_timestamp,
				$this->user->rolle->language
			)
		);
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $dbStyle = $mapDB->get_Style($style_id);
    $style = ms_newStyleObj($klasse);
    if($dbStyle['symbolname']!='') {
      $style -> set('symbolname',$dbStyle['symbolname']);
    }
    if($dbStyle['symbol']>0) {
      $style->set('symbol',$dbStyle['symbol']);
    }
		if($dbStyle['size'] != ''){
			if(is_numeric($dbStyle['size']))$style->set('size', $dbStyle['size']);
			else $style->updateFromString("STYLE SIZE [" . $dbStyle['size']."] END");
		}
    if($dbStyle['width']!='') {
      $style->set('width', $dbStyle['width']);
    }
    if($dbStyle['angle']!='') {
      $style->set('angle', $dbStyle['angle']);
    }
  	if(MAPSERVERVERSION >= 620) {
    	if($dbStyle['geomtransform'] != '') {
      	$style->setGeomTransform($dbStyle['geomtransform']);
      }
      if($dbStyle['pattern']!='') {
      	$style->setPattern(explode(' ',$dbStyle['pattern']));
        $style->linecap = 'butt';
      }
			if($dbStyle['gap'] != '') {
				$style->set('gap', $dbStyle['gap']);
			}
			if($dbStyle['initialgap'] != '') {
        $style->set('initialgap', $dbStyle['initialgap']);
      }
			if($dbStyle['linecap'] != '') {
				$style->set('linecap', constant(MS_CJC_.strtoupper($dbStyle['linecap'])));
			}
			if($dbStyle['linejoin'] != '') {
				$style->set('linejoin', constant(MS_CJC_.strtoupper($dbStyle['linejoin'])));
			}
			if($dbStyle['linejoinmaxsize'] != '') {
				$style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
			}
    }
    #######################################################
    if($layer->type > 0){
    	$symbol = $map->getSymbolObjectById($style->symbol);
    	if($symbol->type == 1006){ 	# 1006 == hatch
    		$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
    		$style->set('maxsize', 2*$style->width);
    	}
    	else{
				if($dbStyle['size'] < 2)$style->set('size', 2);					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
    		if($dbStyle['maxsize'] < 2)$style->set('maxsize', 2);
    	}
    }
    else{
    	$style->set('maxsize', $style->size);		# maxsize auf size setzen bei Punktlayern, damit man was in der Legende erkennt
    }
    #######################################################
    $RGB = array_filter(explode(" ",$dbStyle['color']), 'strlen');		
    if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
    if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
		else $style->updateFromString("STYLE COLOR [".$dbStyle['color']."] END");
    $RGB = array_filter(explode(" ",$dbStyle['outlinecolor']), 'strlen');
    $style->outlinecolor->setRGB(intval($RGB[0]),intval($RGB[1]),intval($RGB[2]));
    if($dbStyle['backgroundcolor']!='') {
      $RGB = array_filter(explode(" ",$dbStyle['backgroundcolor']), 'strlen');
      if($RGB[0] != '')$style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
    }
		if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
			$style->set('opacity', $dbStyle['opacity']);
		}

		if($dbStyle['colorrange'] != ''){
			$newname = rand(0, 1000000).'.jpg';
			$this->colorramp(IMAGEPATH.$newname, 25, 18, $dbStyle['colorrange']);
		}
		else{
			$image = $klasse->createLegendIcon(25,18);
			$filename = $this->map_saveWebImage($image,'jpeg');
			$newname = $this->user->id.basename($filename);
			rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
		}
    return $newname;
  }

  function notizErfassung() {
		include_once (CLASSPATH.'notiz.php');
    # Wenn eine oid in formvars übergeben wurde ist es eine Änderung, sonst Neueingabe
    if ($this->formvars['oid']=='') {
      $this->titel='Neue Notiz';
    }
    else {
      $this->titel='Notiz Bearbeiten';
    }
    $this->main="notizerfassung.php";
    # aktuellen Kartenausschnitt laden + zeichnen!
    $this->loadMap('DataBase');
    $this->notizen=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notizen->anlegenKategorien = $this->notizen->getKategorie(NULL, $this->Stelle->id, NULL, 'true', NULL);
    if ($this->formvars['CMD']!='') {
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
      $this->formvars['textposition']="POINT(" . $location_x." " . $location_y.")";
      #echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
    }
    elseif($this->formvars['newpathwkt'] != ''){
      $this->formvars['textposition'] = $this->formvars['newpathwkt'];
      #echo '<br/>formvars[textposition]: '.$this->formvars['textposition'];
    }
    else {
      $this->formvars['textposition']="";
    }
    # 2006-06-21 pk
    # aktuellen EPSG Code der Stelle in Variable formvar übergeben
    $this->formvars['epsg_von']=$this->user->rolle->epsg_code;

    # Notizobjekt erzeugen
    $notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);

    # 1. Prüfen der Eingabewerte
    #echo '<br>Prüfen der Eingabewerte.';
    $this->formvars['stelle_id'] = $this->Stelle->id;
    $ret=$notiz->pruefeEingabedaten($this->formvars);
    if ($ret[0]) {
      # Es wurde ein oder mehrere Fehler bei den Eingabewerten gefunden
      $this->Meldung=$ret[1];
    }
    else {
      # Eingabewerte fehlerfrei
      #echo 'Eingabe fehlerfrei:';
      if ($this->formvars['oid']=='') {
        # 2. eintragenNeueZone
        $ret=$notiz->eintragenNeueNotiz($this->formvars);
        if ($ret[0]) {
          # 2.1 Eintragung fehlerhaft
          $this->Meldung=$ret[1];
        }
        else {
          #  2.2 Eintragung erfolgreich
          $alertmsg='\nNotiz erfolgreich in die Datenbank eingetragen.'.
          $this->formvars['pathx']='';    $this->formvars['loc_x']='';
          $this->formvars['pathy']='';    $this->formvars['loc_y']='';
          $this->formvars['umring']='';   $this->formvars['textposition']='';
        }
      }
      else {
        # 3. Notiz Aktualisieren
        $ret=$notiz->aktualisierenNotiz($this->formvars['oid'],$this->formvars);
        if ($ret[0]) {
          # 3.1 Eintragung fehlerhaft
          $this->Meldung=$ret[1];
        }
        else {
          # 3.2 Aktualisierung erfolgreich
          $alertmsg='\nNotiz erfolgreich in die Datenbank aktualisiert.';
        }
      }
    }
    $this->notizErfassung();
  }

  function notizLoeschen($oid){
		include_(CLASSPATH.'notiz.php');
    $notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $notiz->NotizLoeschen($oid);
  }

  function notizKatVerwaltung() {
		include_once (CLASSPATH.'notiz.php');
    $this->Stelle=new stelle('',$this->database);
    $this->Stellen=$this->Stelle->getStellen('Bezeichnung');
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->AllKat=$this->notiz->selectKategorie('','','');
    if($this->formvars['kategorie_id'] != ''){
      $this->Kat=$this->notiz->getKategorie($this->formvars['kategorie_id'],'','','','');
      $this->Kat2Stelle=$this->notiz->selectKat2stelle($this->formvars['kategorie_id']);
    }
    $this->titel='Notizkategorienverwaltung';
    $this->main='Kat_bearbeiten.php';
    $this->output();
  } # END of funtion notizKatVerwaltung

  function notizKategoriehinzufügen(){
		include_(CLASSPATH.'notiz.php');
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notiz->insertKategorie($this->formvars['newKategorie']);
    $kat=$this->notiz->selectKategorie('',$this->formvars['newKategorie'],'');
    $this->formvars['kategorie_id']=$kat[0]['id'];
    $this->notizKatVerwaltung();
  } # END of function notizKathinzufügen

  function notizKategorieAendern(){
		include_(CLASSPATH.'notiz.php');
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    if($this->formvars['kategorie_id'] != ''){
      $this->notiz->notizKategorieAenderung($this->formvars);
    }
    $this->notizKatVerwaltung();
  } # END of function notizKategorieAendern

  function notizKategorieLoeschen() {
		include_(CLASSPATH.'notiz.php');
    $this->notiz=new notiz($this->pgdatabase, $this->user->rolle->epsg_code);
    $this->notiz->notizKategorieLoeschen($this->formvars['kategorie_id'],$this->formvars['plus_notiz']);
    $max_id=$this->notiz->selectKategorie('','','');
    $this->formvars['kategorie_id']=$max_id[0]['id'];
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

	function nutzungWahl(){
		include_once(CLASSPATH.'FormObject.php');
    if ($this->formvars['anzahl'] == 0) {
      $this->formvars['anzahl'] = 10;
    }
    $this->titel='Flurstückssuche nach Nutzung';
    $this->main='nutzungensuchform.php';

    # 2006-29-06 sr: Gemarkungsformobjekt nur für Gemeinden der Stelle
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
    $Gemeinde=new gemeinde('',$this->pgdatabase);
    # Auswahl aller Gemeinden der Stelle
    $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');

    # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
    $GemkgID=$this->formvars['GemkgID'];
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $this->GemkgFormObj=new FormObject("GemkgID","select",$GemkgListe['GemkgID'],$GemkgID,$GemkgListe['Bezeichnung'],"1","","",NULL);
    $this->GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->GemkgFormObj->outputHTML();
    $this->output();
  }

  function nutzungsuchen(){
    # 2006-29-06 sr: auf Gemarkungen der Stelle einschränken
    if($this->formvars['GemkgID'] > 0){
      $Liste['GemkgID'][] = $this->formvars['GemkgID'];
      $this->formvars['GemkgID'] = $Liste['GemkgID'];
    }
    else{
      $GemeindenStelle=$this->Stelle->getGemeindeIDs();
      if($GemeindenStelle != NULL){
        $Gemeinde=new gemeinde('',$this->pgdatabase);
        # Auswahl aller Gemeinden der Stelle
        $GemListe=$Gemeinde->getGemeindeListe($GemeindenStelle, 'GemeindeName');
        # Abfragen der Gemarkungen mit dazugehörigen Namen der Gemeinden
        $Gemarkung=new gemarkung('',$this->pgdatabase);
        $GemkgListe=$Gemarkung->getGemarkungListe($GemListe['ID'],'','gmk.GemkgName');
        $this->formvars['GemkgID'] = $GemkgListe['GemkgID'];
      }
    }
    if($this->formvars['GemkgID'][0] != '-'){
      $flurstueck=new flurstueck('',$this->pgdatabase);
      $ret=$flurstueck->getFlurstByNutzungen($this->formvars['GemkgID'][0], $this->formvars['nutzung'], $this->formvars['anzahl']);
      if ($ret[0] == 1) {
        $this->Fehlermeldung='<br>Es konnten keine Flurstücke abgefragt werden'.$ret[1];
      }
      else {
        $this->flurstuecke=$ret[1];
        if (count($this->flurstuecke)==0) {
          $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
        }
        else {
          $ret=$flurstueck->getFlurstByNutzungen($this->formvars['GemkgID'][0], $this->formvars['nutzung'], NULL);
          $this->anzNamenGesamt=count($ret[1]);
        }
      } # ende Abfrage war erfolgreich
    }
    $this->nutzungWahl();
  }

	 function namenWahl() {
		include_once(CLASSPATH.'FormObject.php');
    if ($this->formvars['anzahl']==0) {
      $this->formvars['anzahl']=10;
    }
    $this->main='namensuchform.php';
		$GemeindenStelle=$this->Stelle->getGemeindeIDs();
		$GemkgID=$this->formvars['GemkgID'];
		$Gemarkung=new gemarkung('',$this->pgdatabase);
		if($GemeindenStelle == NULL){
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL, NULL);
		}
		else{
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    $this->GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $this->GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->GemkgFormObj->outputHTML();
    # Abragen der Fluren zur Gemarkung
    if($GemkgID > 0){
    	$Flur=new Flur('','','',$this->pgdatabase);
			$FlurListe=$Flur->getFlurListe($GemkgID, $GemeindenStelle['eingeschr_gemarkung'][$GemkgID], false);
    	# Erzeugen des Formobjektes für die Flurauswahl
    	if (count($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
    }
    $this->FlurFormObj=new FormObject("FlurID","select",$FlurListe['FlurID'],$this->formvars['FlurID'],$FlurListe['Name'],"1","","",NULL);
    $this->FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
    $this->FlurFormObj->outputHTML();
    $this->output();
  }

	function nameSuchen() {
		$GemeindenStelle=$this->Stelle->getGemeindeIDs();
		if(!empty($GemeindenStelle['ganze_gemeinde'])){
			$Gemarkung=new gemarkung('',$this->pgdatabase);
			$GemkgListe = $Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_keys($GemeindenStelle['ganze_gemarkung']));
			$GemeindenStelle['ganze_gemarkung'] = array_flip($GemkgListe['GemkgID']);
		}
    $formvars = $this->formvars;
    $flurstueck=new flurstueck('',$this->pgdatabase);
		$ret=$flurstueck->getNamen($formvars,@array_keys($GemeindenStelle['ganze_gemarkung']), $GemeindenStelle['eingeschr_gemarkung']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $this->namen=$ret[1];
      if (count($this->namen)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
      }
      else {
				$formvars['anzahl'] = '';
				$formvars['offset'] = '';
				$ret=$flurstueck->getNamen($formvars, @array_keys($GemeindenStelle['ganze_gemarkung']), $GemeindenStelle['eingeschr_gemarkung']);
        $this->anzNamenGesamt=count($ret[1]);

				for($i = 0; $i < count($this->namen); $i++){
					$currenttime=date('Y-m-d H:i:s',time());
					$this->user->rolle->setConsumeALB($currenttime, 'Eigentümersuche', array($this->namen[$i]['gml_id']), 0, 'NULL');		# die gml_id aus ax_namensnummer wird geloggt
					if($this->formvars['withflurst'] == 'on'){
            $ret[1] = $flurstueck->getFlurstByGrundbuecher(array($this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt']));
            $this->namen[$i]['flurstuecke'] = $ret[1];
            for($j = 0; $j < count($this->namen[$i]['flurstuecke']); $j++){
              $ret = $this->pgdatabase->getALBData($this->namen[$i]['flurstuecke'][$j]);
              $this->namen[$i]['alb_data'][$j] = $ret[1];
            }
          }
        }

      }
      $this->namenWahl();
    } # ende Abfrage war erfolgreich
  }

  function flurstuecksAnzeigeByGrundbuecher(){
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $gbarray = explode(', ', $this->formvars['selBlatt']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if (count($Flurstuecke)==0) {
      $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
      $this->namenWahl();
    }
    else {
      # Anzeige der Flurstuecke
      $this->zoomToALKFlurst($Flurstuecke,10);
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
      $this->saveMap('');
      $this->output();
    }
  }

  function flurstuecksSucheByGrundbuecher(){
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $gbarray = explode(', ', $this->formvars['selBlatt']);
    $Flurstuecke = $flurstueck->getFlurstByGrundbuecher($gbarray);
    if(count($Flurstuecke)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Flurstücke gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
    }
    else {
      $this->flurstAnzeige($Flurstuecke);
      $this->output();
    }
  }

  function flurstuecksSucheByNamen() {
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $ret=$flurstueck->getFlurstByLfdNrName($this->formvars['lfd_nr_name'],$this->formvars['anzahl']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $FlurstKennz=$ret[1];
      if (count($FlurstKennz)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
      }
      else {
        # Anzeige der Namen
        $this->flurstAnzeige($FlurstKennz);
        $this->output();
      } # ende Ergebnisanzahl größer 0
    } # ende Abfrage war erfolgreich
  }

  function flurstuecksAnzeigeByNamen() {
    $flurstueck=new flurstueck('',$this->database);
    $flurstueck->database=$this->pgdatabase;
    $ret=$flurstueck->getFlurstByLfdNrName($this->formvars['lfd_nr_name'],$this->formvars['anzahl']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
      $this->namenWahl();
    }
    else {
      $FlurstKennz=$ret[1];
      if (count($FlurstKennz)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
        $this->namenWahl();
      }
      else {
        # Anzeige der Flurstuecke
        $this->zoomToALKFlurst($FlurstKennz,10);
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
        $this->output();
      } # ende Ergebnisanzahl größer 0
    } # ende Abfrage war erfolgreich
  }

	function deleteDokument($path, $doc_path, $doc_url, $only_thumb = false){
		if($doc_url != '')$path = url2filepath($path, $doc_path, $doc_url);			# Dokument mit URL
		else $path = array_shift(explode('&original_name', $path));
		$dateinamensteil = explode('.', $path);
		if(!$only_thumb AND file_exists($path))unlink($path);
		if(file_exists($dateinamensteil[0].'_thumb.jpg'))unlink($dateinamensteil[0].'_thumb.jpg');
	}

  function get_dokument_vorschau($dateinamensteil){
		$type = strtolower($dateinamensteil[1]);
  	$dokument = $dateinamensteil[0].'.'.$dateinamensteil[1];
		if(in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ){			// für Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $dateinamensteil[0].'_thumb.jpg';
			if(!file_exists($thumbname)){
				exec(IMAGEMAGICKPATH.'convert -filter Hanning "'.$dokument.'"[0] -quality 75 -background white -flatten -resize '.PREVIEW_IMAGE_WIDTH.'x1000\> "'.$thumbname.'"');
			}
		}
		else{																// alle anderen Dokumenttypen bekommen entsprechende Dokumentensymbole als Vorschaubild
			$dateinamensteil[1] = 'gif';
  		switch ($type) {
  			default : {
  				$image = imagecreatefromgif(GRAPHICSPATH.'document.gif');
          $blue = ImageColorAllocate ($image, 26, 87, 150);
					if(strlen($type) > 3)$xoffset = 4;
          imagettftext($image, 12, 0, 23-$xoffset, 34, $blue, WWWROOT.APPLVERSION.'fonts/SourceSansPro-Semibold.ttf', $type);
          $thumbname = IMAGEPATH.rand(0,100000).'.gif';
          imagegif($image, $thumbname);
  			}
  		}
  	}
		return $thumbname;
  }

	function write_document_loader(){
		$handle = fopen(IMAGEPATH.$this->document_loader_name, 'w');
		$code = '<?
			$allowed_documents = array(\''.implode('\',\'', $this->allowed_documents).'\');
			if(in_array($_REQUEST[\'dokument\'], $allowed_documents)){
				if($_REQUEST[\'original_name\'] == "")$_REQUEST[\'original_name\'] = basename($_REQUEST[\'dokument\']);
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
    $this->drawMap();
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

  function export_flurst_csv(){
		$this->attribute_selections = $this->user->rolle->get_csv_attribute_selections();
    $this->attribute = explode(';', $this->formvars['attributliste']);
    $this->main = 'export_flurstuecke_csv.php';
   	$this->titel = $this->formvars['formnummer'].'-CSV-Export';
    $this->output();
  }

  function export_flurst_csv_auswahl_speichern(){
  	$this->user->rolle->save_csv_attribute_selection($this->formvars['name'], $this->formvars['attributes']);
  	$this->formvars['selection'] = $this->formvars['name'];
  	$this->export_flurst_csv_auswahl_laden();
  }

  function export_flurst_csv_auswahl_laden(){
  	$this->selection = $this->user->rolle->get_csv_attribute_selection($this->formvars['selection']);
  	$attributes = explode('|', $this->selection['attributes']);
  	for($i = 0; $i < count($attributes); $i++){
  		$this->formvars[$attributes[$i]] = 'true';
  	}
  	$this->export_flurst_csv();
  }

  function export_flurst_csv_auswahl_loeschen(){
  	$this->user->rolle->delete_csv_attribute_selection($this->formvars['selection']);
  	$this->export_flurst_csv();
  }

  function export_flurst_csv_exportieren(){
		include_(CLASSPATH.'alb.php');
    $flurstuecke = explode(';', $this->formvars['FlurstKennz']);
    $ret = $this->Stelle->getFlurstueckeAllowed($flurstuecke, $this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      showAlert($ret[1]);
    }
    else {
      $flurstuecke = $ret[1];
      $ALB = new ALB($this->pgdatabase);
      $currenttime=date('Y-m-d H:i:s',time());
      switch ($this->formvars['formnummer']){
      	case 'Flurstück' : {
      		$ALB->export_flurst_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Flurstück',count($flurstuecke));
      	}break;
      	case 'Nutzungsarten' : {
      		$ALB->export_nutzungsarten_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Nutzungsarten',count($flurstuecke));
      	}break;
      	case 'Eigentümer' : {
      		$ALB->export_eigentuemer_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Eigentümer',count($flurstuecke));
      	}break;
      	case 'Klassifizierung' : {
      		$ALB->export_klassifizierung_csv($flurstuecke, $this->formvars);
      		$this->user->rolle->setConsumeCSV($currenttime,'Klassifizierung',count($flurstuecke));
      	}break;
      }
    }
  }


  function createMapPDF($frame_id, $preview, $fast = false) {
    $Document=new Document($this->database);
    $this->Docu=$Document;
    $this->Docu->activeframe = $this->Docu->load_frames(NULL, $frame_id);

		if($this->Docu->activeframe[0]['dhk_call'] != ''){
			$output = $this->ALKIS_Kartenauszug($this->Docu->activeframe[0], $this->formvars);
		}
		else{
			# Abfrage der aktuellen Karte
			if($this->formvars['post_map_factor']){
				$this->map_factor = $this->formvars['post_map_factor'];
			}
			elseif($this->formvars['map_factor'] != ''){
				$this->map_factor = $this->formvars['map_factor'];
			}
			else{
				$this->map_factor = MAPFACTOR;
			}
			# Wenn in der Anfrage für loadmapsource POST übergeben wurde, werden alle Kartenparameter aus formvars entnommen
			if($this->formvars['loadmapsource']){
				$this->loadMap($this->formvars['loadmapsource']);
			}
			else{
				$this->loadMap('DataBase');
			}
			# Karte
			if($this->map->selectOutputFormat('jpeg_print') == 1){
				$this->map->selectOutputFormat('jpeg');
			}
			if($fast == true){			# schnelle Druckausgabe ohne Druckausschnittswahl, beim Schnelldruck und im Druckrahmeneditor
				$this->formvars['referencemap'] = 1;
				$this->formvars['printscale'] = round($this->map_scaledenom);
				$this->formvars['refpoint_x'] = $this->formvars['center_x'] = $this->map->extent->minx + ($this->map->extent->maxx-$this->map->extent->minx)/2;
				$this->formvars['refpoint_y'] = $this->formvars['center_y'] = $this->map->extent->miny + ($this->map->extent->maxy-$this->map->extent->miny)/2;
				$this->formvars['worldprintwidth'] = $this->Docu->activeframe[0]['mapwidth'] * $this->formvars['printscale'] * 0.0003526;
				$this->formvars['worldprintheight'] = $this->Docu->activeframe[0]['mapheight'] * $this->formvars['printscale'] * 0.0003526;
			}
			elseif($this->user->rolle->print_scale != 'auto'){
				$this->user->rolle->savePrintScale($this->formvars['printscale']);
			}
			#echo $this->formvars['center_x'].'<br>';
			#echo $this->formvars['center_y'].'<br>';
			#echo $this->formvars['worldprintwidth'].'<br>';
			#echo $this->formvars['worldprintheight'].'<br>';
			$breite = $this->formvars['worldprintwidth']/2;
			$höhe = $this->formvars['worldprintheight']/2;

			if($this->formvars['angle'] != 0){
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
			$this->map->set('width', $this->Docu->activeframe[0]['mapwidth'] * $widthratio * $this->map_factor);
			$this->map->set('height', $this->Docu->activeframe[0]['mapheight'] * $heightratio * $this->map_factor);

			# copyright-layer aus dem Mapfile
			@$creditslayer = $this->map->getLayerByName('credits');
			if($creditslayer != false){
				$newcredits = ms_newLayerObj($this->map, $creditslayer);
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
				$gridlayer->set('status', MS_ON);
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
			#$this->saveMap('');
			#$this->debug->write("<p>Maßstab des Drucks:" . $this->map_scaledenom,4);
			$this->drawMap();

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
			if(LAGEBEZEICHNUNGSART == 'Flurbezeichnung'){
				$flur = new Flur('','','',$this->pgdatabase);
				$bildmitte['rw']=$this->formvars['refpoint_x'];
				$bildmitte['hw']=$this->formvars['refpoint_y'];
				$this->lagebezeichnung = $flur->getBezeichnungFromPosition($bildmitte, $this->user->rolle->epsg_code);
			}

			# Hinzufügen des Hintergrundbildes als Druckrahmen
			$pdf->addJpegFromFile(DRUCKRAHMEN_PATH.basename($this->Docu->activeframe[0]['headsrc']),$this->Docu->activeframe[0]['headposx'],$this->Docu->activeframe[0]['headposy'],$this->Docu->activeframe[0]['headwidth']);

			# Hinzufügen der vom MapServer produzierten Karte
			$pdf->addJpegFromFile(IMAGEPATH.basename($this->img['hauptkarte']),$this->Docu->activeframe[0]['mapposx'],$this->Docu->activeframe[0]['mapposy'],$this->Docu->activeframe[0]['mapwidth'], $this->Docu->activeframe[0]['mapheight']);

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
				$freitext = explode(';', $this->substituteFreitext(utf8_decode($this->Docu->activeframe[0]['texts'][$j]['text'])));
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
						$pdf->addText($posx,$posy,$this->Docu->activeframe[0]['texts'][$j]['size'],$freitext[$i], -1 * $alpha);
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
						$pdf->addText($posx+$fontsize*0.3333,$posy-$h-$fontsize*1.18, $fontsize,utf8_decode($freitext[$i]), 0);
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
		$name = umlaute_umwandeln($this->user->Name);
		$dateiname = $name.'-'.$currenttime.'.pdf';
		$this->outputfile = $dateiname;
		$fp=fopen($dateipfad.$dateiname,'wb');
		fwrite($fp, $output);
		fclose($fp);

		if($preview == true){
			exec(IMAGEMAGICKPATH.'convert -density 300x300 '.$dateipfad.$dateiname.'[0] -background white -flatten -resize 595x1000 '.$dateipfad.$name.'-'.$currenttime.'.jpg');
			#echo IMAGEMAGICKPATH.'convert -density 300x300 '.$dateipfad.$dateiname.'[0] -background white -flatten -resize 595x1000 '.$dateipfad.$name.'-'.$currenttime.'.jpg';
			if(!file_exists(IMAGEPATH.$name.'-'.$currenttime.'.jpg')){
				return TEMPPATH_REL.$name.'-'.$currenttime.'-0.jpg';
			}
			else{
				return TEMPPATH_REL.$name.'-'.$currenttime.'.jpg';
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

  function wmsExportSenden() {
    $this->titel='WMS Map-Datei erfolgreich exportiert';
    $this->main="ows_exportiert.php";
    # laden der aktuellen Karteneinstellungen
    if($this->formvars['nurAktiveLayer'] == 1)$this->class_load_level = 1;    # die Klassen von aktiven Layern laden
		else $this->class_load_level = 2;    # die Klassen von allen Layern laden
    $this->loadMap('DataBase');
		# grid-Layer rausnehmen
		@$gridlayer = $this->map->getLayerByName('grid');
		if($gridlayer)$this->map->removeLayer($gridlayer->index);
		# Layernamen anpassen
		for($i = 0; $i < $this->map->numlayers; $i++){
      $layer = $this->map->getlayer($i);
      $layer->set('name', umlaute_umwandeln($layer->name));
    }
    # setzen der WMS-Metadaten
    $this->map->setMetaData("ows_title",$this->formvars['ows_title']);
    $this->map->setMetaData("ows_abstract",$this->formvars['ows_abstract']);
    $bb=$this->map->extent;
    $this->map->setMetaData("wms_extent",$bb->minx.' '.$bb->miny.' '.$bb->maxx.'  '.$bb->maxy);
    $this->map->setMetaData("wms_accessconstraints","none");
    $this->map->setMetaData("ows_contactperson",$this->formvars['ows_contactperson']);
    $this->map->setMetaData("ows_contactorganization",$this->formvars['ows_contactorganization']);
    $this->map->setMetaData("ows_contactelectronicmailaddress",$this->formvars['ows_contactelectronicmailaddress']);
    $this->map->setMetaData("ows_contactposition",OWS_CONTACTPOSITION);
    $this->map->setMetaData("ows_fees",$this->formvars['ows_fees']);
    $this->wms_onlineresource=MAPSERV_CGI_BIN."?map=".WMS_MAPFILE_PATH.$this->formvars['mapfile_name']."&";
    $this->map->setMetaData("wms_onlineresource",$this->wms_onlineresource);
    $this->map->setMetaData("ows_srs",OWS_SRS);
		$this->map->setMetaData("wms_enable_request",'*');
    $this->saveMap(WMS_MAPFILE_PATH.$this->formvars['mapfile_name']);
    $getMapRequestExample=$this->wms_onlineresource.'request=getMap&VERSION='.SUPORTED_WMS_VERSION;
    $getMapRequestExample.='&layers='.$layer->name;
    $getMapRequestExample.='&srs=EPSG:'.$this->user->rolle->epsg_code;
    $getMapRequestExample.='&bbox='.$this->map->extent->minx.','.$this->map->extent->miny.','.$this->map->extent->maxx.','.$this->map->extent->maxy;
    $getMapRequestExample.='&width='.$this->map->width.'&height='.$this->map->height;
		$getMapRequestExample.='&format=image/jpeg';
    $this->getMapRequestExample=$getMapRequestExample;
    $this->output();
  }

  function wmsExport() {
    $this->titel='MapService Map-Datei Export';
    $this->main="ows_export.php";
    $this->output();
  }

	function wmsImportFormular(){
		$this->titel='WMS Import';
    $this->main="wms_import.php";
		if($this->formvars['wms_url']){
			include(CLASSPATH.'wms.php');
			$wms = new wms_request_obj();
			$this->layers = $wms->parseCapabilities($this->formvars['wms_url']);
		}
    $this->output();
	}

	function wmsImportieren(){
		if(count($this->formvars['layers']) > 0){
			$dbmap = new db_mapObj($this->Stelle->id,$this->user->id);
			$group = $dbmap->getGroupbyName('WMS-Importe');
			if($group != '')$groupid = $group['id'];
			else $groupid = $dbmap->newGroup('WMS-Importe', 0);
			$this->formvars['user_id'] = $this->user->id;
			$this->formvars['stelle_id'] = $this->Stelle->id;
			$this->formvars['aktivStatus'] = 1;
			$this->formvars['Gruppe'] = $groupid;
			$this->formvars['Typ'] = 'import';
			$this->formvars['Datentyp'] = MS_LAYER_RASTER;
			$this->formvars['connectiontype'] = MS_WMS;
			$this->formvars['transparency'] = 100;
			$wms_epsg_codes = array_flip(explode(' ', str_replace('epsg:', '', strtolower($this->formvars['srs'][0]))));
			if($wms_epsg_codes[$this->user->rolle->epsg_code] !== '')$this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
			else $this->formvars['epsg_code'] = 4326;
			if(strpos($this->formvars['wms_url'], '?') !== false)$this->formvars['wms_url'] .= '&';
			else $this->formvars['wms_url'] .= '?';
			for($i = 0; $i < count($this->formvars['layers']); $i++){
				$this->formvars['Name'] = $this->formvars['layers'][$i];
				$this->formvars['connection'] = $this->formvars['wms_url'].'VERSION=1.1.1&FORMAT=image/png&transparent=true&styles=&LAYERS='.$this->formvars['layers'][$i];
				$layer_id = $dbmap->newRollenLayer($this->formvars);
				$classdata['layer_id'] = -$layer_id;
				$classdata['name'] = '_';
				$class_id = $dbmap->new_Class($classdata);
			}
			$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		}
		$this->loadMap('DataBase');
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
		$this->drawMap();
		$this->saveMap('');
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
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $Stelle->updateLayer($this->formvars);
    $this->Layer2Stelle_Editor();
  }

  function Layer2Stelle_Editor(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->titel='Layereigenschaften stellenbezogen';
    $this->main='layer2stelle_formular.php';
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

  function Layer2Stelle_Reihenfolge(){
    $this->selected_stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->main='layer2stelle_order.php';
		if($this->formvars['order'] == '')$this->formvars['order'] = 'legendorder, drawingorder desc';
		if($this->formvars['order'] == 'legendorder, drawingorder desc')$this->groups = $this->selected_stelle->getGroups();
    $this->layers = $this->selected_stelle->getLayers(NULL, $this->formvars['order']);
    $this->output();
  }

  function Layer2Stelle_ReihenfolgeSpeichern(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->layers = $Stelle->getLayers(NULL);
    for($i = 0; $i < count($this->layers['ID']); $i++){
      $this->formvars['selected_layer_id'] = $this->layers['ID'][$i];
      $this->formvars['drawingorder'] = $this->formvars['drawingorder_layer'.$this->layers['ID'][$i]];
			$this->formvars['legendorder'] = $this->formvars['legendorder_layer'.$this->layers['ID'][$i]];
      $Stelle->updateLayerOrder($this->formvars);
    }
    $this->Layer2Stelle_Reihenfolge();
  }

  function layer_export(){
  	# Abfragen aller Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
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
SET @connection = 'host={$this->pgdatabase->host} user={$this->pgdatabase->user} password={$this->pgdatabase->passwd_} dbname={$this->pgdatabase->dbName}';
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
	function generate_layer($schema, $table, $group_id = 0, $connection = '', $epsg_code = '25832', $geometrie_column = '', $geometrietyp = '', $layertyp = '') {
		$this->debug->write("<p>schema: {$schema}, table: {$table['name']}, group_id: {$group_id}, connection: {$connection}, epsg_code: {$epsg_code}, geometrie_column: {$geometrie_column}, geometrietype: {$geometrietyp}, layertype: {$layertype}", 4);
		$sql = $this->database->generate_layer($schema, $table, $group_id, $connection, $epsg_code, $geometrie_column, $geometrietyp, $layertyp);
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
    $this->main='layer_parameter.php';
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->params = $mapDB->get_all_layer_params();
    $this->output();
	}

	function layer_parameter_speichern(){
		$this->main='layer_parameter.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->save_all_layer_params($this->formvars);
		$this->params = $mapDB->get_all_layer_params();
		# Ergänze und lösche Layerparameter in Rollen
		$this->update_layer_parameter_in_rollen($this->params);
		$this->output();
	}

	/**
	* for all rollen do
	* add param with default value if not exists
	* delete param if not defined in given list $params
	* @param $params Array of layer parameter in structure function get_all_layer_params returns it
	* @return null
	*/
	function update_layer_parameter_in_rollen($params) {
		$default_params = array();
		foreach ($params AS $param) {
			$default_params[$param['key']] = $param['default_value'];
		}
		$myObject = new MyObject(
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
		$rollen = $myObject->find_by_sql(array(
			'select' => 'user_id, stelle_id, layer_params'
		));
		foreach ($rollen AS $rolle) {
			$rolle_params = (array)json_decode('{' . $rolle->get('layer_params') . '}');

			# Parameter, die die Rolle noch nicht hat anhängen
			$add = array_diff_key($default_params, $rolle_params);
			$rolle_params = array_merge($rolle_params, $add);

			# Parameter, die der Layer nicht mehr, aber die Rolle noch hat löschen
			$del = array_diff_key($rolle_params, $default_params);
			$rolle_params = array_intersect_key($rolle_params, $default_params);

			$new_params = array();
			foreach ($rolle_params AS $key => $value) {
				$new_params[] = '"' . $key . '":"' . $value . '"';
			}
			# speicher die neuen parameter
			$rolle->update(array('layer_params' => implode(',', $new_params)));
		}
	}

	function Layereditor() {
		$this->titel='Layer Editor';
		$this->main='layer_formular.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layerdaten = $mapDB->getall_Layer('Name', false, $this->user->id, $this->Stelle->id);
		# Abfragen der Layerdaten wenn eine layer_id zur Änderung selektiert ist
		if ($this->formvars['selected_layer_id'] > 0) {
			$this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
			if(!$this->use_form_data){
				$this->formvars = array_merge($this->formvars, $this->layerdata);
			}
			# Abfragen der Stellen des Layer
			$this->formvars['selstellen']=$mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
			$this->grouplayers = $mapDB->get_layersfromgroup($this->layerdata['Gruppe']);
		}
		$this->stellen=$this->Stelle->getStellen('Bezeichnung');
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
		for($i = 0; $i < count($name); $i++) {
			$attrib['name'] = $name[$i];
			foreach($supportedLanguages as $language){
				if($language != 'german'){
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

  function Klasseneditor_KlasseHinzufuegen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $attrib['name'] = $this->formvars['class_name'];
    $attrib['layer_id'] = $this->formvars['selected_layer_id'];
		$attrib['classification'] = $this->formvars['classification'];
    $attrib['order'] = ($this->formvars['class_order'] != '') ? $this->formvars['class_order'] : 1;
    $attrib['expression'] = ($this->formvars['class_expression'] != '') ? $this->formvars['class_expression'] : '';
    $new_class = $mapDB->new_Class($attrib);
		$this->Klasseneditor();
		return $new_class;
  }

  function Klasseneditor_AutoklassenHinzufuegen() {
    $num_classes = (empty($this->formvars['num_classes'])) ? 5 : $this->formvars['num_classes'];
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], true);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->formvars['Datentyp'] = $this->layerdata['Datentyp'];
    $this->layerdata['Data'] = replace_params(
			$this->layerdata['Data'],
			rolle::$layer_params,
			$this->user->id,
			$this->Stelle->id,
			rolle::$hist_timestamp,
			$this->user->rolle->language
		);
		$this->layerdata['classitem'] = replace_params(
			$this->layerdata['classitem'],
			rolle::$layer_params,
			$this->user->id,
			$this->Stelle->id,
			rolle::$hist_timestamp,
			$this->user->rolle->language
		);
    $begin = strpos($this->layerdata['Data'], '(') + 1;
    $end = strrpos($this->layerdata['Data'], ')');
    $data_sql = substr($this->layerdata['Data'], $begin, $end - $begin);

    $auto_classes = $this->AutoklassenErzeugen($layerdb, $data_sql, $this->formvars['classification_column'], $this->formvars['classification_method'], $this->formvars['num_classes'], $this->formvars['classification_name'], $this->formvars['classification_color']);

    for ($i = 0; $i < count($auto_classes); $i++) {
      $this->formvars['class_name'] = $auto_classes[$i]['name'];
      $this->formvars['classification'] = $auto_classes[$i]['classification'];
      $this->formvars['class_order'] = $auto_classes[$i]['order'];
      $this->formvars['class_expression'] = $auto_classes[$i]['expression'];

      $this->formvars['class_id'] = $this->Klasseneditor_KlasseHinzufuegen();
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
          FROM (" . $data_sql.") AS data ORDER BY " . replace_semicolon($class_item) . " LIMIT 100";

        $ret=$layerdb->execSQL($sql, 4, 0);
				if ($ret['success']==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
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
    		if ($ret['success']==0) { echo err_msg($PHP_SELF, __LINE__, $sql); return 0; }
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

