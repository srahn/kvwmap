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
    $this->debug=$debug;
    # Logdatei für Mysql setzen
    global $log_mysql;
    $this->log_mysql=$log_mysql;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
    # layout Templatedatei zur Anzeige der Daten
    if ($main!="") $this->main=$main;
    # Stylesheetdatei
    if (isset($style)) $this->style=$style;
    # mime_type html, pdf
    if (isset ($mime_type)) $this->mime_type=$mime_type;
		$this->scaleUnitSwitchScale = 239210;
		$this->trigger_functions = array();
  }

	public function __call($method, $arguments){
		if(isset($this->{$method}) && is_callable($this->{$method})){
			return call_user_func_array($this->{$method}, $arguments);
    }
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

	function show_snippet() {
		if (empty($this->formvars['snippet'])) {
			$error_msg = 'Geben Sie im Parameter snippets einen Namen für eine Datei an!';
		}
		else {
			$snippet_path = SNIPPETS . 'custom/';
			$snippet_file = $this->formvars['snippet'] . '.php';
			if (!file_exists($snippet_path . $snippet_file)) {
				$error_msg = 'Die Datei ' . $snippet_path . $snippet_file . ' existiert nicht. Geben Sie einen anderen Namen im Parameter snippet an!';
			}
		}

		if (empty($error_msg)) {
			$this->main = 'custom/' . $snippet_file;
		}
		else {
			$this->add_message('error', $error_msg);
			$this->loadMap('DataBase');
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->saveMap('');
			$this->drawMap();
		}
		$this->output();
	}

	function getLayerOptions() {
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($this->formvars['layer_id'] > 0)$layer = $this->user->rolle->getLayer($this->formvars['layer_id']);
		else $layer = $this->user->rolle->getRollenLayer(-$this->formvars['layer_id']);
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
						if ($layer[0]['connectiontype']==6) {
							echo '<li><a href="javascript:zoomToMaxLayerExtent('.$this->formvars['layer_id'].')">'.$this->FullLayerExtent.'</a></li>';
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
						echo '<li><span>Transparenz:</span> <input name="layer_options_transparency" onchange="transparency_slider.value=parseInt(layer_options_transparency.value); style="width: 30px" value="'.$layer[0]['transparency'].'"><input type="range" id="transparency_slider" name="transparency_slider" style="width: 120px" value="'.$layer[0]['transparency'].'" onchange="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()" oninput="layer_options_transparency.value=parseInt(transparency_slider.value);layer_options_transparency.onchange()"></li>
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
		~
		legend_top = document.getElementById(\'legenddiv\').getBoundingClientRect().top;
		legend_bottom = document.getElementById(\'legenddiv\').getBoundingClientRect().bottom;
		posy = document.getElementById(\'options_'.$this->formvars['layer_id'].'\').getBoundingClientRect().top;		
		if(posy > legend_bottom - 150)posy = legend_bottom - 150;
		document.getElementById(\'options_content_'.$this->formvars['layer_id'].'\').style.top = posy - (13+legend_top);
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
		~
		legend_top = document.getElementById(\'legenddiv\').getBoundingClientRect().top;
		legend_bottom = document.getElementById(\'legenddiv\').getBoundingClientRect().bottom;
		posy = document.getElementById(\'group_options_' . $this->formvars['group_id'] . '\').getBoundingClientRect().top;		
		if(posy > legend_bottom - 150) posy = legend_bottom - 150;
		document.getElementById(\'group_options_content_' . $this->formvars['group_id'] . '\').style.top = posy - (13 + legend_top);
		$(\'#group_options_' . $this->formvars['group_id'] . '\').show()
		';
	}
	
	function saveLayerOptions(){	
		$this->user->rolle->setTransparency($this->formvars);
	}
	
	function resetLayerOptions(){	
		$this->user->rolle->removeTransparency($this->formvars);
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

	function setLayerParams() {
		$layer_params = array();
		foreach($this->formvars AS $key => $value) {
			$param_key = str_replace('layer_parameter_', '', $key);
			if ($param_key != $key) {
				$layer_params[] = '"' . $param_key . '":"' . $value . '"';
			}
		}
		if (!empty($layer_params))
			$this->user->rolle->set_layer_params(implode(',', $layer_params));
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

	function create_group_legend($group_id){
		if($this->groupset[$group_id]['untergruppen'] == NULL AND $this->groups_with_layers[$group_id] == NULL)return;			# wenns keine Layer oder Untergruppen gibt, nix machen
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
							<div style="position:static;" id="group_options_' . $group_id . '"></div>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<div id="layergroupdiv_'.$group_id.'" style="width:100%"><table cellspacing="0" cellpadding="0">';
		$layercount = count($this->groups_with_layers[$group_id]);
    if($groupstatus == 1){		# Gruppe aufgeklappt
			for($u = 0; $u < count($this->groupset[$group_id]['untergruppen']); $u++){			# die Untergruppen rekursiv durchlaufen
				$legend .= '<tr><td colspan="3"><table cellspacing="0" cellpadding="0" style="width:100%"><tr><td><img src="'.GRAPHICSPATH.'leer.gif" width="13" height="1" border="0"></td><td style="width: 100%">';
				$legend .= $this->create_group_legend($this->groupset[$group_id]['untergruppen'][$u]);
				$legend .= '</td></tr></table></td></tr>';
			}
			if($layercount > 0){		# Layer vorhanden
				$this->groups_with_layers[$group_id] = array_reverse($this->groups_with_layers[$group_id]);		# Layerreihenfolge umdrehen
				if(!$this->formvars['nurFremdeLayer']){
					$legend .=  '<tr>
												<td align="center">
													<input name="layers_of_group_'.$group_id.'" type="hidden" value="'.implode(',', $this->layers_of_group[$group_id]).'">';
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
					$layer = $this->layerset[$this->groups_with_layers[$group_id][$j]];
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
										($layer['selectiontype'] == 'radio' ? "document.GUI.radiolayers_" . $group_id : "''") . "," .
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

/*############################################
							if($layer['queryable'] == 1 AND !$this->formvars['nurFremdeLayer']){
								// die sichtbaren Layer brauchen dieses Hiddenfeld mit dem gleichen Namen, welches immer den value 0 hat, damit sie beim Neuladen ausgeschaltet werden können, denn eine nicht angehakte Checkbox/Radiobutton wird ja nicht übergeben
								$legend .=  '<input type="hidden" name="qLayer'.$layer['Layer_ID'].'" value="0">';
								$legend .=  '<input id="qLayer'.$layer['Layer_ID'].'"';
								if($this->user->rolle->singlequery){			# singlequery-Modus
									$legend .=  'type="radio" ';
									if($layer['selectiontype'] == 'radio') {
										$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.', document.GUI.layers, '.$this->user->rolle->instant_reload.')"';
									}
									else{
										$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', document.GUI.layers, '.$this->user->rolle->instant_reload.')"';
									}
								}
								else{			# normaler Modus
									if($layer['selectiontype'] == 'radio'){
										$legend .=  'type="radio" ';
										$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.', \'\', '.$this->user->rolle->instant_reload.')"';
									}
									else{
										$legend .=  'type="checkbox" ';
										$legend .=  ' onClick="updateThema(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), \'\', \'\', '.$this->user->rolle->instant_reload.')"';
									}
								}
								$legend .=  ' name="qLayer'.$layer['Layer_ID'].'" value="1" ';
								if($layer['queryStatus'] == 1){
									$legend .=  'checked title="'.$this->deactivatequery.'"';
								}
								$legend .=  ' title="'.$this->activatequery.'">';
##################################################################*/

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
								$legend .=  ' onClick="this.checked = this.checked2;" onMouseUp="this.checked = this.checked2;" onMouseDown="updateQuery(event, document.getElementById(\'thema_'.$layer['Layer_ID'].'\'), document.getElementById(\'qLayer'.$layer['Layer_ID'].'\'), document.GUI.radiolayers_'.$group_id.', '.$this->user->rolle->instant_reload.')"';
								$radiolayers[$group_id] .= $layer['Layer_ID'].'|';
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
							if($layer['metalink'] != '' AND substr($layer['metalink'], 0, 10) != 'javascript')
								$legend .= ' target="_blank"';
							if($layer['metalink'] != '')
								$legend .= ' class="metalink boldhover" href="'.$layer['metalink'].'">';
							else
								$legend .= ' class="visiblelayerlink boldhover" href="javascript:void(0)"';
							$legend .= '<span';
							if($layer['minscale'] != -1 AND $layer['maxscale'] > 0){
								$legend .= ' title="'.round($layer['minscale']).' - '.round($layer['maxscale']).'"';
							}			  
							$legend .=' class="legend_layer">'.html_umlaute($layer['alias']).'</span>';
							$legend .= '</a>';

							# Bei eingeschalteten Layern und eingeschalteter Rollenoption ist ein Optionen-Button sichtbar
							if($layer['aktivStatus'] == 1 and $this->user->rolle->showlayeroptions) $legend.='&nbsp;<a href="javascript:getLayerOptions('.$layer['Layer_ID'].')"><img src="graphics/rows.png" border="0" title="'.$this->layerOptions.'"></a>';
							$legend.='<div style="position:static" id="options_'.$layer['Layer_ID'].'"> </div>';
						}
						if($layer['aktivStatus'] == 1 AND $layer['Class'][0]['Name'] != ''){
							if($layer['requires'] == '' AND $layer['Layer_ID'] > 0){
								$legend .= '<input id="classes_'.$layer['Layer_ID'].'" name="classes_'.$layer['Layer_ID'].'" type="hidden" value="'.$layer['showclasses'].'">';
							}
							if($layer['showclasses'] != 0){
								if($layer['connectiontype'] == 7){      # WMS
									$layersection = substr($layer['connection'], strpos(strtolower($layer['connection']), 'layers')+7);
									$pos = strpos($layersection, '&');
									if($pos !== false)$layersection = substr($layersection, 0, $pos);
									$layers = explode(',', $layersection);
									for($l = 0; $l < count($layers); $l++){
										$legend .=  '<div style="display:inline" id="lg'.$j.'_'.$l.'"><br><img src="'.$layer['connection'].'&layer='.$layers[$l].'&service=WMS&request=GetLegendGraphic" onerror="ImageLoadFailed(\'lg'.$j.'_'.$l.'\')"></div>';
									}
								}
								else{
									$legend .= '<table border="0" cellspacing="0" cellpadding="0">';
									$maplayer = $this->map->getLayerByName($layer['alias']);
									for($k = 0; $k < $maplayer->numclasses; $k++){
										$class = $maplayer->getClass($k);
										for($s = 0; $s < $class->numstyles; $s++){
											$style = $class->getStyle($s);
											if($maplayer->type > 0){
												$symbol = $this->map->getSymbolObjectById($style->symbol);
												if($symbol->type == 1006){ 	# 1006 == hatch
													$style->set('size', 2*$style->width);					# size und maxsize beim Typ Hatch auf die doppelte Linienbreite setzen, damit man was in der Legende erkennt
													$style->set('maxsize', 2*$style->width);
												}
												else{
													$style->set('size', 2);					# size und maxsize bei Linien und Polygonlayern immer auf 2 setzen, damit man was in der Legende erkennt
													$style->set('maxsize', 2);
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
											if($layer['Class'][$k]['Style'][0]['colorrange'] != ''){
												$height = 18;
												$newname = rand(0, 1000000).'.jpg';
												$this->colorramp(IMAGEPATH.$newname, 18, $height, $layer['Class'][$k]['Style'][0]['colorrange']);
											}
											else{
												if($maplayer->type == 0)$height = 18;			# Punktlayer
												else $height = 12;
												$padding = 1;
												$image = $class->createLegendIcon(18, $height);
												$filename = $this->map_saveWebImage($image,'jpeg');
												$newname = $this->user->id.basename($filename);
												rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
											}
											#Anne
											$classid = $layer['Class'][$k]['Class_ID'];
											if($this->mapDB->disabled_classes['status'][$classid] == '0'){
												$imagename = 'graphics/inactive'.$height.'.jpg';
												$status = 0;
											}
											elseif($this->mapDB->disabled_classes['status'][$classid] == 2){
												$imagename = TEMPPATH_REL.$newname;												
												$status = 2;
											}
											else{
												$imagename = TEMPPATH_REL.$newname;
												$status = 1;
											}
											if ($layer['Class'][$k]['legendgraphic'] != '') {
												$imagename = GRAPHICSPATH . 'custom/' . $layer['Class'][$k]['legendgraphic'];
												$new_class_image = $imagename;
											}
											else {
												$new_class_image = TEMPPATH_REL . $newname;
											}
											$legend .= '<input type="hidden" size="2" name="class'.$classid.'" value="'.$status.'"><a href="#" onmouseover="mouseOverClassStatus('.$classid.',\''.$new_class_image.'\','.$height.')" onmouseout="mouseOutClassStatus('.$classid.',\''.$new_class_image.'\','.$height.')" onclick="changeClassStatus('.$classid.',\''.$new_class_image.'\', '.$this->user->rolle->instant_reload.','.$height.')"><img style="vertical-align:middle;padding-bottom: '.$padding.'" border="0" name="imgclass'.$classid.'" src="'.$imagename.'"></a>';
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
							$radiolayers[$group_id] .= $layer['Layer_ID'].'|';
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
						$legend .= 'class="invisiblelayerlink boldhover" href="javascript:void(0)"';
						$legend .= '<span class="legend_layer_hidden" ';
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
				}
			}
	  }
    $legend .= '</table></div></td></tr></table>';
    $legend .= '<input type="hidden" name="radiolayers_'.$group_id.'" value="'.$radiolayers[$group_id].'">';
	  $legend .= '</div>';
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
				$this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
				$query=mysql_query($sql);
				if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
			}
      $sql ='UPDATE u_menue2rolle SET `status` = 1 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id.' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    }
    elseif($status == 'off'){
      $sql ='UPDATE u_menue2rolle SET `status` = 0 WHERE `user_id` ='.$this->user->id.' AND `stelle_id` ='.$this->Stelle->id;
			if($id != '')$sql .= ' AND `menue_id` ='.$id;
      $this->debug->write("<p>file:kvwmap class:GUI->changemenue :<br>".$sql,4);
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

  function loadMap($loadMapSource) {
    $this->debug->write("<p>Funktion: loadMap('".$loadMapSource."','".$connStr."')",4);
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
          #echo '<br>Connection: '.$layerset[$i][connection];
          $layer->set('connection', $layerset[$i][connection]);
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

				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if($this->user->rolle->oGeorefExt->minx==='') {
				  echo "Richten Sie mit phpMyAdmin in der kvwmap Datenbank eine Referenzkarte, eine Stelle, einen Benutzer und eine Rolle ein ";
				  echo "<br>(Tabellen referenzkarten, stelle, user, rolle) ";
				  echo "<br>oder wenden Sie sich an ihren Systemverwalter.";
				  exit;
				}
				else {
				  $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }

        # OWS Metadaten

        if($this->Stelle->ows_title != ''){
          $map->setMetaData("ows_title",$this->Stelle->ows_title);}
        else{
          $map->setMetaData("ows_title",OWS_TITLE);
        }
        if($this->Stelle->ows_abstract != ''){
          $map->setMetaData("ows_abstract",$this->Stelle->ows_abstract);}
        else{
          $map->setMetaData("ows_title",OWS_ABSTRACT);
        }
        if($this->Stelle->wms_accessconstraints != ''){
          $map->setMetaData("wms_accessconstraints",$this->Stelle->wms_accessconstraints);}
        else{
          $map->setMetaData("wms_accessconstraints",OWS_ACCESSCONSTRAINTS);
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
        $ows_onlineresource = OWS_SERVICE_ONLINERESOURCE.'&Stelle_ID='.$this->Stelle->id;
        $map->setMetaData("ows_onlineresource",$ows_onlineresource);
        $bb=$this->Stelle->MaxGeorefExt;
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
				$this->ref=$mapDB->read_ReferenceMap();
				if(MAPSERVERVERSION < 600){
					$reference_map = ms_newMapObj(DEFAULTMAPFILE);
				}
				else {
					$reference_map = new mapObj(DEFAULTMAPFILE);
				}
				$reference_map->web->set('imagepath', IMAGEPATH);
				$reference_map->setProjection('+init=epsg:'.$this->ref['epsg_code'],MS_FALSE);
				#$reference_map->extent->setextent in drawreferencemap() ausgelagert, da der Extent sich geändert haben kann nach dem loadmap
				$reference_map->reference->extent->setextent(round($this->ref['xmin']),round($this->ref['ymin']),round($this->ref['xmax']),round($this->ref['ymax']));
        $reference_map->reference->set('image',REFERENCEMAPPATH.$this->ref['Dateiname']);
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
        $mapDB->nurAufgeklappteLayer=$this->formvars['nurAufgeklappteLayer'];
        $mapDB->nurFremdeLayer=$this->formvars['nurFremdeLayer'];
        if($this->class_load_level == ''){
          $this->class_load_level = 1;
        }
        $layer = $mapDB->read_Layer($this->class_load_level, $this->list_subgroups($this->formvars['group']));     # class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
        $rollenlayer = $mapDB->read_RollenLayer();
        $layerset = array_merge($layer, $rollenlayer);
        $layerset['anzLayer'] = count($layerset) - 1; # wegen $layerset['layer_ids']
        unset($this->layers_of_group);		# falls loadmap zweimal aufgerufen wird
				unset($this->groups_with_layers);	# falls loadmap zweimal aufgerufen wird
        for($i=0; $i < $layerset['anzLayer']; $i++){
					if($layerset[$i]['alias'] == '' OR !$this->Stelle->useLayerAliases){
						$layerset[$i]['alias'] = $layerset[$i]['Name'];			# kann vielleicht auch in read_layer gesetzt werden
					}
					$this->groups_with_layers[$layerset[$i]['Gruppe']][] = $i;			# die $i's pro Gruppe im layerset-Array
					if($layerset[$i]['requires'] == ''){
						$this->layers_of_group[$layerset[$i]['Gruppe']][] = $layerset[$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset[$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String

					if($layerset[$i]['requires'] != ''){
						$layerset[$i]['aktivStatus'] = $layerset['layer_ids'][$layerset[$i]['requires']]['aktivStatus'];
						$layerset[$i]['showclasses'] = $layerset['layer_ids'][$layerset[$i]['requires']]['showclasses'];
					}

					if($this->class_load_level == 2 OR ($this->class_load_level == 1 AND $layerset[$i]['aktivStatus'] != 0)){      # nur wenn der Layer aktiv ist, sollen seine Parameter gesetzt werden
						$layer = ms_newLayerObj($map);
						$layer->setMetaData('wfs_request_method', 'GET');
						$layer->setMetaData('wms_name', umlaute_umwandeln($layerset[$i]['wms_name']));
						$layer->setMetaData('wfs_typename', umlaute_umwandeln($layerset[$i]['wms_name']));
						$layer->setMetaData('ows_title', $layerset[$i]['Name']); # required
						$layer->setMetaData('wms_group_title',$layerset[$i]['Gruppenname']);
						$layer->setMetaData('wms_queryable',$layerset[$i]['queryable']);
						$layer->setMetaData('wms_format',$layerset[$i]['wms_format']);
						$layer->setMetaData('ows_server_version',$layerset[$i]['wms_server_version']);
						$layer->setMetaData('ows_version',$layerset[$i]['wms_server_version']);
						if($layerset[$i]['ows_srs'] == '')$layerset[$i]['ows_srs'] = 'EPSG:'.$layerset[$i]['epsg_code'];
						$layer->setMetaData('ows_srs',$layerset[$i]['ows_srs']);
						$layer->setMetaData('wms_connectiontimeout',$layerset[$i]['wms_connectiontimeout']);
						$layer->setMetaData('ows_auth_username', $layerset[$i]['wms_auth_username']);
						$layer->setMetaData('ows_auth_password', $layerset[$i]['wms_auth_password']);
						$layer->setMetaData('ows_auth_type', 'basic');
						$layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_xml');

						$layer->set('dump', 0);
						$layer->set('type',$layerset[$i]['Datentyp']);
						$layer->set('group',$layerset[$i]['Gruppenname']);

						$layer->set('name', $layerset[$i]['alias']);

						if($layerset[$i]['status'] != ''){
							$layerset[$i]['aktivStatus'] = 0;
						}

						//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
						//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
						//---- der layer_status auf 0 gesetzt werden//
						if($layerset[$i]['aktivStatus'] == 0){
						$layer->set('status', 0);
						}
						else{
						$layer->set('status', 1);
						}
						$layer->set('debug',MS_ON);

						# fremde Layer werden auf Verbindung getestet
						if($layerset[$i]['aktivStatus'] != 0 AND $layerset[$i]['connectiontype'] == 6 AND strpos($layerset[$i]['connection'], 'host') !== false AND strpos($layerset[$i]['connection'], 'host=localhost') === false AND strpos($layerset[$i]['connection'], 'host=pgsql') === false){
						$connection = explode(' ', trim($layerset[$i]['connection']));
								for($j = 0; $j < count($connection); $j++){
								if($connection[$j] != ''){
									$value = explode('=', $connection[$j]);
									if(strtolower($value[0]) == 'host'){
									$host = $value[1];
									}
									if(strtolower($value[0]) == 'port'){
									$port = $value[1];
									}
								}
								}
								if($port == '')$port = '5432';
						$fp = @fsockopen($host, $port, $errno, $errstr, 5);
						if(!$fp){			# keine Verbindung --> Layer ausschalten
							$layer->set('status', 0);
							$layer->setMetaData('queryStatus', 0);
									$this->Fehlermeldung = $errstr.' für Layer: '.$layerset[$i]['Name'].'<br>';
						}
						}

						if($layerset[$i]['aktivStatus'] != 0){
							$collapsed = false;
							$group = $this->groupset[$layerset[$i]['Gruppe']];				# die Gruppe des Layers
							if($group['status'] == 0){
								$this->group_has_active_layers[$layerset[$i]['Gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
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

						if(!$this->noMinMaxScaling AND $layerset[$i]['minscale']>=0) {
							if($this->map_factor != ''){
								if(MAPSERVERVERSION > 500){
									$layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
								}
								else{
									$layer->set('minscale', $layerset[$i]['minscale']/$this->map_factor*1.414);
								}
							}
							else{
								if(MAPSERVERVERSION > 500){
									$layer->set('minscaledenom', $layerset[$i]['minscale']);
								}
								else{
									$layer->set('minscale', $layerset[$i]['minscale']);
								}
							}
						}
						if(!$this->noMinMaxScaling AND $layerset[$i]['maxscale']>0) {
							if($this->map_factor != ''){
								if(MAPSERVERVERSION > 500){
									$layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
								}
								else{
									$layer->set('maxscale', $layerset[$i]['maxscale']/$this->map_factor*1.414);
								}
							}
							else{
								if(MAPSERVERVERSION > 500){
									$layer->set('maxscaledenom', $layerset[$i]['maxscale']);
								}
								else{
									$layer->set('maxscale', $layerset[$i]['maxscale']);
								}
							}
						}
            $layer->setProjection('+init=epsg:'.$layerset[$i]['epsg_code']); # recommended
            if ($layerset[$i]['connection']!='') {
              if($this->map_factor != '' AND $layerset[$i]['connectiontype'] == 7){		# WMS-Layer
              	if($layerset[$i]['printconnection']!=''){
              		$layerset[$i]['connection'] = $layerset[$i]['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
              	}
              	else{
                	//$layerset[$i]['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
              	}
              }
              if($layerset[$i]['connectiontype'] == 6)$layerset[$i]['connection'] .= " options='-c client_encoding=".MYSQL_CHARSET."'";		# z.B. für Klassen mit Umlauten
              $layer->set('connection', $layerset[$i]['connection']);
            }
            if ($layerset[$i]['connectiontype']>0) {
              if (MAPSERVERVERSION >= 540) {
                $layer->setConnectionType($layerset[$i]['connectiontype']);
              }
              else {
                $layer->set('connectiontype',$layerset[$i]['connectiontype']);
              }
            }

						if($layerset[$i]['connectiontype'] == 6)$layerset[$i]['processing'] = 'CLOSE_CONNECTION=DEFER;'.$layerset[$i]['processing'];		# DB-Connection erst am Ende schliessen und nicht für jeden Layer neu aufmachen
            if ($layerset[$i]['processing'] != "") {
              $processings = explode(";",$layerset[$i]['processing']);
              foreach ($processings as $processing) {
                $layer->setProcessing($processing);
              }
            }
						if ($layerset[$i]['postlabelcache'] != 0) {
							$layer->set('postlabelcache',$layerset[$i]['postlabelcache']);
						}

						if($layerset[$i]['Datentyp'] == MS_LAYER_POINT AND $layerset[$i]['cluster_maxdistance'] != ''){
							$layer->cluster->maxdistance = $layerset[$i]['cluster_maxdistance'];
							$layer->cluster->region = 'ellipse';
						}

            if ($layerset[$i]['Datentyp']=='3') {
              if($layerset[$i]['transparency'] != ''){
                if(MAPSERVERVERSION > 500){
                  $layer->set('opacity',$layerset[$i]['transparency']);
                }
                else{
                  $layer->set('transparency',$layerset[$i]['transparency']);
                }
              }
              if ($layerset[$i]['tileindex']!='') {
                $layer->set('tileindex',SHAPEPATH.$layerset[$i]['tileindex']);
              }
              else {
                $layer->set('data', $layerset[$i]['Data']);
              }
              $layer->set('tileitem',$layerset[$i]['tileitem']);
              if ($layerset[$i]['offsite']!='') {
                $RGB=explode(' ',$layerset[$i]['offsite']);
                $layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
              }
            }
            else {
              # Vektorlayer
              if($layerset[$i]['Data'] != ''){
								$layerset[$i]['Data'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[$i]['Data']);
								$layerset[$i]['Data'] = str_replace('$language', $this->user->rolle->language, $layerset[$i]['Data']);
								$layerset[$i]['Data'] = replace_params($layerset[$i]['Data'], rolle::$layer_params);
                $layer->set('data', $layerset[$i]['Data']);
              }

              # Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
              # Template (Body der Anzeige)
              if ($layerset[$i]['template']!='') {
                $layer->set('template',$layerset[$i]['template']);
              }
              else {
                $layer->set('template',DEFAULTTEMPLATE);
              }
              # Header (Kopfdatei)
              if ($layerset[$i]['header']!='') {
                $layer->set('header',$layerset[$i]['header']);
              }
              # Footer (Fusszeile)
              if ($layerset[$i]['footer']!='') {
                $layer->set('footer',$layerset[$i]['footer']);
              }
              # Setzen der Spalte nach der der Layer klassifiziert werden soll
              if ($layerset[$i]['classitem']!='') {
                $layer->set('classitem', replace_params($layerset[$i]['classitem'], rolle::$layer_params));
              }
              else {
                #$layer->set('classitem','id');
              }
              # Setzen des Filters
              if($layerset[$i]['Filter'] != ''){
              	$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
               if (substr($layerset[$i]['Filter'],0,1)=='(') {
                 $expr=$layerset[$i]['Filter'];
               }
               else {
                 $expr=buildExpressionString($layerset[$i]['Filter']);
               }
               $layer->setFilter($expr);
              }
              # Layerweite Labelangaben
              if (MAPSERVERVERSION < 500 AND $layerset[$i]['labelangleitem']!='') {
                $layer->set('labelangleitem',$layerset[$i]['labelangleitem']);
              }
              if ($layerset[$i]['labelitem']!='') {
                $layer->set('labelitem',$layerset[$i]['labelitem']);
              }
              if ($layerset[$i]['labelmaxscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelmaxscaledenom',$layerset[$i]['labelmaxscale']);
                }
                else{
                  $layer->set('labelmaxscale',$layerset[$i]['labelmaxscale']);
                }
              }
              if ($layerset[$i]['labelminscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelminscaledenom',$layerset[$i]['labelminscale']);
                }
                else{
                  $layer->set('labelminscale',$layerset[$i]['labelminscale']);
                }
              }
              if ($layerset[$i]['labelrequires']!='') {
                $layer->set('labelrequires',$layerset[$i]['labelrequires']);
              }
              if ($layerset[$i]['tolerance']!='3') {
                $layer->set('tolerance',$layerset[$i]['tolerance']);
              }
              if ($layerset[$i]['toleranceunits']!='pixels') {
                $layer->set('toleranceunits',$layerset[$i]['toleranceunits']);
              }
              if ($layerset[$i]['transparency']!=''){
                if(MAPSERVERVERSION > 500){
                  if ($layerset[$i]['transparency']==-1) {
                      $layer->set('opacity',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('opacity',$layerset[$i]['transparency']);
                  }
                }
                else {
                  if ($layerset[$i]['transparency']==-1) {
                      $layer->set('transparency',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('transparency',$layerset[$i]['transparency']);
                  }
                }
              }
              if ($layerset[$i]['symbolscale']!='') {
                if($this->map_factor != ''){
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset[$i]['symbolscale']/$this->map_factor*1.414);
                  }
                  else{
                    $layer->set('symbolscale',$layerset[$i]['symbolscale']/$this->map_factor*1.414);
                  }
                }
                else{
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset[$i]['symbolscale']);
                  }
                  else{
                    $layer->set('symbolscale',$layerset[$i]['symbolscale']);
                  }
                }
              }
            } # ende of Vektorlayer
            # Klassen
            $classset=$layerset[$i]['Class'];
            $this->loadclasses($layer, $layerset[$i], $classset, $map);
          } # Ende Layer ist aktiv
        } # end of Schleife layer

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
      # setzen eines oder mehrerer Styles
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Style']);$k++) {
        $dbStyle=$classset[$j]['Style'][$k];
				if (MAPSERVERVERSION < 600) {
          $style = ms_newStyleObj($klasse);
        }
				else {
				  $style = new styleObj($klasse);
				}
				if($dbStyle['geomtransform'] != ''){
					$style->updateFromString("STYLE GEOMTRANSFORM '".$dbStyle['geomtransform']."' END"); 
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
				if ($layerset['Datentyp'] == 8) {
					# Skalierung der Stylegröße when Type Chart
					$style->setbinding(MS_STYLE_BINDING_SIZE, $dbStyle['size']);
				}
				else {
					if($this->map_factor != '') {
						$style->set('size', $dbStyle['size']*$this->map_factor/1.414);
					}
					else{
						$style->set('size', $dbStyle['size']);
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
					$style->updateFromString("STYLE ANGLE ".$dbStyle['angle']." END"); 		# wegen AUTO
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

        if (MAPSERVERVERSION < 500 AND $dbStyle['sizeitem']!='') {
          $style->set('sizeitem', $dbStyle['sizeitem']);
        }
        if ($dbStyle['color']!='') {
          $RGB=explode(" ",$dbStyle['color']);
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
					else $style->updateFromString("STYLE COLOR [".$dbStyle['color']."] END");
        }
				if($dbStyle['opacity'] != '') {		# muss nach color gesetzt werden
					$style->set('opacity', $dbStyle['opacity']);
				}
        if ($dbStyle['outlinecolor']!='') {
          $RGB=explode(" ",$dbStyle['outlinecolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['backgroundcolor']!='') {
          $RGB=explode(" ",$dbStyle['backgroundcolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
				if($dbStyle['colorrange'] != '') {
					$style->updateFromString("STYLE COLORRANGE ".$dbStyle['colorrange']." END");
				}
				if($dbStyle['datarange'] != '') {
					$style->updateFromString("STYLE DATARANGE ".$dbStyle['datarange']." END");
				}
				if($dbStyle['rangeitem'] != '') {
					$style->updateFromString("STYLE RANGEITEM ".$dbStyle['rangeitem']." END");
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
      case "jump_coords" : {
        $this->user->rolle->setSelectedButton('recentre');
        $this->zoomMap(1);
      } break;
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

  function zoomMap($nZoomFactor) {
    # Zerlegung der Input Koordinaten in linke obere und rechte untere Ecke
    # echo ('formvars[INPUT_COORD]: '.$this->formvars['INPUT_COORD']);
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

			if($this->formvars['epsg_code'] != '' AND $this->formvars['epsg_code'] != $this->user->rolle->epsg_code){
				$oPixelPos->setXY($minx,$maxy);
				$projFROM = ms_newprojectionobj("init=epsg:".$this->formvars['epsg_code']);
				$projTO = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
				$oPixelPos->project($projFROM, $projTO);
				$minx = $oPixelPos->x;
				$maxy = $oPixelPos->y;
			}

      if($this->formvars['CMD'] != 'jump_coords'){
        $oPixelPos->setXY($minx,$maxy);
        $this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
      else{
        #---------- Punkt-Rollenlayer erzeugen --------#
        $legendentext ="Koordinate: ".$minx." ".$maxy;
        if(strpos($minx, '°') !== false){
	      	$minx = dms2dec($minx);
	      	$maxy = dms2dec($maxy);
	      }
        $datastring ="the_geom from (select st_geomfromtext('POINT(".$minx." ".$maxy.")', ".$this->user->rolle->epsg_code.") as the_geom, 1 as oid) as foo using unique oid using srid=".$this->user->rolle->epsg_code;
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

				if($this->map->scaledenom > COORD_ZOOM_SCALE){
					$this->map->zoomscale(COORD_ZOOM_SCALE/4,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				}
        # hier wurden Weltkoordinaten übergeben
        $this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
        $pixel_x = ($minx-$this->map->extent->minx)/$this->pixwidth;
        $pixel_y = ($this->map->extent->maxy-$maxy)/$this->pixwidth;
        $oPixelPos->setXY($pixel_x,$pixel_y);
        $this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf eine Rechteckgezoomt gezoomt',4);
			if (MAPSERVERVERSION < 600) {
				$oPixelExt=ms_newRectObj();
			}
			else {
				$oPixelExt = new rectObj();
			}
      if($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined'){
       	$oPixelExt->setextent($minx,$miny,$maxx,$maxy);
        $this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
        # Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
        # verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
        # Berechnung der Bildmitte
        $oPixelPos=ms_newPointObj();
        $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
        $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
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
		$userProjection = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
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
    $sql = "SELECT st_geomfromtext('POLYGON((".$this->map->extent->minx." ".$this->map->extent->miny.", ".$this->map->extent->maxx." ".$this->map->extent->miny.", ".$this->map->extent->maxx." ".$this->map->extent->maxy.", ".$this->map->extent->minx." ".$this->map->extent->maxy.", ".$this->map->extent->minx." ".$this->map->extent->miny."))', ".$this->user->rolle->epsg_code.") && st_transform(".$geom.", ".$this->user->rolle->epsg_code.")";
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
    $this->debug->write("Name der Hauptkarte: ".$this->img['hauptkarte'],4);

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
    $this->debug->write("Name des Scalebars: ".$this->img['scalebar'],4);

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
					$projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
					$projTO = ms_newprojectionobj("init=epsg:".$this->ref['epsg_code']);
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
      $this->debug->write("Name der Referenzkarte: ".$this->img['referenzkarte'],4);
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

	function add_message($type, $msg) {
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
		if (!$this->success) {
			header('error: true');
		}
		$html = "message(" . json_encode($this->messages) . ");";
		if ($option == 'with_script_tags') {
			$html = "<script type=\"text/javascript\">" . $html . "</script>";
		}
		echo $html;
	}

  # Ausgabe der Seite
  function output() {
		global $sizes;
	  foreach($this->formvars as $key => $value){
			#if(is_string($value))$this->formvars[$key] = stripslashes($value);
			if(is_string($value))$this->formvars[$key] = strip_pg_escape_string($value);
	  }
    # bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if ($this->formvars['mime_type'] != '') $this->mime_type = $this->formvars['mime_type'];
    switch ($this->mime_type) {
      case 'printversion' : {
        include (LAYOUTPATH.'snippets/printversion.php');
      } break;
      case 'html' : {
        $this->debug->write("<br>Include <b>".LAYOUTPATH.$this->user->rolle->gui."</b> in kvwmap.php function output()",4);
        if (basename($this->user->rolle->gui)=='') {
          $this->user->rolle->gui='gui.php';
        }
        include (LAYOUTPATH . $this->user->rolle->gui);
				if($this->alert != ''){
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
          $htmlstr.='<html><head><title>PDF-Ausgabe</title>';
          $htmlstr.='<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
          $htmlstr.='<META HTTP-EQUIV=REFRESH CONTENT="0; URL='.TEMPPATH_REL.$this->outputfile.'">';
          $htmlstr.='</head><body>';
          $htmlstr.='<BR>Folgende Datei wird automatisch aufgerufen: <a href="'.TEMPPATH_REL.$this->outputfile.'">'.$this->outputfile.'</a>';
          $htmlstr.='</body></html>';
          echo $htmlstr;
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

	function autocomplete_request(){	# layer_id, attribute, inputvalue, field_id
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributenames[0] = $this->formvars['attribute'];
    $attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		# value und output ermitteln
		$options = explode(';', $attributes['options'][0]);
		$sql = $options[0];
		$sql = 'SELECT * FROM ('.$sql.') as foo WHERE';
		$sql .= " lower(output::text) like lower('".$this->formvars['inputvalue']."%') ORDER BY output LIMIT 15";
		#echo $sql;
  	$ret=$layerdb->execSQL($sql,4, 1);
		$count = pg_num_rows($ret[1]);
		if($count == 1)$rs = pg_fetch_array($ret[1]);
		if($count == 1 AND strtolower($rs['output']) == strtolower($this->formvars['inputvalue'])){	# wenn nur ein Treffer gefunden wurde und der dem Eingabewert entspricht
			echo '~document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';';
			echo 'document.getElementById(\''.$this->formvars['field_id'].'\').value=\''.$rs['value'].'\';';
		}
		elseif($count == 0 ){		# wenn nichts gefunden wurde
			echo '~document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';';
			echo 'document.getElementById(\''.$this->formvars['field_id'].'\').value = document.getElementById(\''.$this->formvars['field_id'].'\').backup_value;';
			echo 'output = document.getElementById(\'output_'.$this->formvars['field_id'].'\').value;';
			echo 'document.getElementById(\'output_'.$this->formvars['field_id'].'\').value = output.substring(0, output.length-1);';
			echo 'document.getElementById(\'output_'.$this->formvars['field_id'].'\').onkeyup();';
		}
		else{
			if($count == 1)$count = 2;		# weil ein select-Feld bei size 1 anders funktioniert
			pg_result_seek($ret[1], 0);
			echo'<select size="'.$count.'" style="width: 450px;padding:4px; margin:-2px -17px -4px -4px;" onclick="document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'none\';document.getElementById(\''.$this->formvars['field_id'].'\').value=this.value;document.getElementById(\''.$this->formvars['field_id'].'\').onchange();document.getElementById(\'output_'.$this->formvars['field_id'].'\').value=this.options[this.selectedIndex].text;document.getElementById(\'output_'.$this->formvars['field_id'].'\').onchange();">';
			while($rs=pg_fetch_array($ret[1])) {
				echo '<option onmouseover="this.selected = true;"  value="'.$rs['value'].'">'.$rs['output'].'</option>';
			}
			echo '</select>
			~document.getElementById(\'suggests_'.$this->formvars['field_id'].'\').style.display=\'block\';';
		}
	}

	function loadPlugins(){
  	global $kvwmap_plugins;
	  $this->goNotExecutedInPlugins = true;		// wenn es keine Plugins gibt, ist diese Var. immer true
  	if(count($kvwmap_plugins) > 0){
			$plugins = scandir(PLUGINS, 1);
			for($i = 0; $i < count($plugins)-2; $i++) {
				if($this->goNotExecutedInPlugins == true AND in_array($plugins[$i], $kvwmap_plugins)){
					if (file_exists(PLUGINS.$plugins[$i].'/config/config.php'))
						include(PLUGINS.$plugins[$i].'/config/config.php');
					include(PLUGINS.$plugins[$i].'/control/index.php');
				}
			}
		}
  }

  function checkCaseAllowed($case){
  	if(!$this->Stelle->isMenueAllowed($case) AND !$this->Stelle->isFunctionAllowed($case)) {
      $this->Fehlermeldung=$this->TaskChangeWarning . '<br>(' . $case . ')';
      $this->rollenwahl($this->Stelle->id);
      $this->output();
      exit;
    }
  }

	function getSVG_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien von allen aktiven Postgis-Layern, die im aktuellen Kartenausschnitt liegen
		#$this->user->rolle->readSettings();
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->nurAktiveLayer = true;
		$mapDB->OhneRequires = true;
		$layer = $mapDB->read_Layer(0);     # 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
		$rollenlayer = $mapDB->read_RollenLayer();
    $layer = array_merge($layer, $rollenlayer);
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
          $fromwhere .= " AND ".$layer[$i]['Filter'];
        }
				if($data_attributes['the_geom'] != ''){
					switch($layer[$i]['Datentyp']){
						case MS_LAYER_POINT : {
							$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform('.$data_attributes['the_geom'].', '.$this->user->rolle->epsg_code.') as the_geom '.$fromwhere.') foo LIMIT 10000';
						}break;

						case MS_LAYER_LINE : {
							$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(st_pointn(foo.linestring, foo.count1), '.$this->user->rolle->epsg_code.') AS the_geom
							FROM (SELECT generate_series(0, st_npoints(foo4.linestring)) AS count1, foo4.linestring FROM (
							SELECT st_GeometryN(foo2.linestring, foo2.count2) as linestring FROM (
							SELECT generate_series(1, st_NumGeometries(foo5.linestring)) AS count2, foo5.linestring FROM (SELECT st_multi(st_intersection('.$data_attributes['the_geom'].', '.$extent.')) AS linestring '.$fromwhere.') foo5) foo2
							) foo4) foo
							WHERE (foo.count1) <= st_npoints(foo.linestring)) foo3 LIMIT 10000';
						}break;

						case MS_LAYER_POLYGON : {
							$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(st_pointn(foo.linestring, foo.count1), '.$this->user->rolle->epsg_code.') AS the_geom
							FROM (SELECT generate_series(0, st_npoints(foo4.linestring)) AS count1, foo4.linestring FROM (
							SELECT st_GeometryN(foo2.linestring, foo2.count2) as linestring FROM (
							SELECT generate_series(1, st_NumGeometries(foo5.linestring)) AS count2, foo5.linestring FROM (SELECT st_multi(linefrompoly(st_intersection('.$data_attributes['the_geom'].', '.$extent.'))) AS linestring '.$fromwhere.') foo5) foo2
							) foo4) foo
							WHERE (foo.count1) <= st_npoints(foo.linestring)) foo3 LIMIT 10000';
						}break;
					}
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
		echo '~show_vertices();';
	}

	function getSVG_foreign_vertices(){
		# Diese Funktion liefert die Eckpunkte der Geometrien des übergebenen Postgis-Layers, die im aktuellen Kartenausschnitt liegen
		#$this->user->rolle->readSettings();
		if($this->formvars['layer_id'] == 0){		# wenn kein Layer ausgewählt ==> alle aktiven abfragen
			$this->getSVG_vertices();
			return;
		}
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($this->formvars['layer_id'] > 0){
			$layer = $this->user->rolle->getLayer($this->formvars['layer_id']);
			$layer = $layer[0];
		}
		else{
			$rollenlayer = $mapDB->read_RollenLayer(-$this->formvars['layer_id'], NULL);
			$layer = $rollenlayer[0];
		}
		$offset = 0;
		if($layer['connectiontype'] == MS_POSTGIS){
			$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);
    	$data_attributes = $mapDB->getDataAttributes($layerdb, $layer['Layer_ID']);
    	if(in_array($data_attributes['geomtype'][$data_attributes['the_geom']] , array('MULTIPOLYGON', 'POLYGON', 'GEOMETRY'))){
    		$offset = 1;
    	}
    	$select = $mapDB->getSelectFromData($layer['Data']);
			$select = str_replace(' FROM ', ' from ', $select);

			if($this->formvars['layer_id'] > 0)$select = str_replace(' from ', ', '.$data_attributes[$data_attributes['the_geom_id']]['table_alias_name'].'.oid as exclude_oid'.' from ', $select);		# bei Rollenlayern nicht machen
			$extent = 'st_transform(st_geomfromtext(\'POLYGON(('.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->miny.', '.$this->user->rolle->oGeorefExt->maxx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->maxy.', '.$this->user->rolle->oGeorefExt->minx.' '.$this->user->rolle->oGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layer['epsg_code'].')';

			$fromwhere = 'from ('.$select.') as foo1 WHERE st_intersects('.$data_attributes['the_geom'].', '.$extent.') ';
			if($layer['Datentyp'] !== '1' AND $this->formvars['layer_id'] > 0 AND $this->formvars['oid']){		# bei Linienlayern werden auch die eigenen Punkte geholt, bei Polygonen nicht
				$fromwhere .= 'AND exclude_oid != '.$this->formvars['oid'];
			}
			# Filter hinzufügen
			if($layer['Filter'] != ''){
				$layer['Filter'] = str_replace('$userid', $this->user->id, $layer['Filter']);
				$fromwhere .= " AND ".$layer['Filter'];
			}
			# LINE / POLYGON
			$sql = 'SELECT st_x(the_geom), st_y(the_geom) FROM (SELECT st_transform(st_pointn(foo.linestring, foo.count1), '.$this->user->rolle->epsg_code.') AS the_geom
					FROM (SELECT generate_series(1, st_npoints(foo4.linestring)) AS count1, foo4.linestring FROM (
					SELECT CASE WHEN st_GeometryN(foo2.linestring, foo2.count2) IS NULL THEN foo2.linestring ELSE st_GeometryN(foo2.linestring, foo2.count2) END as linestring FROM (
					SELECT generate_series(1, st_NumGeometries(foo5.linestring)) AS count2, foo5.linestring FROM (SELECT st_multi(linefrompoly(st_intersection('.$data_attributes['the_geom'].', '.$extent.'))) AS linestring '.$fromwhere.') foo5) foo2
					) foo4) foo
					WHERE (foo.count1 + '.$offset.') <= st_npoints(foo.linestring)) foo3 LIMIT 10000';
			#echo $sql;
			$ret=$layerdb->execSQL($sql,4, 0);
      if(!$ret[0]){
      	while ($rs=pg_fetch_array($ret[1])){
        	echo $rs[0].' '.$rs[1].'|';
        }
				echo '~show_vertices();';
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

		$width = $this->formvars['browserwidth'] -
			$size['margin']['width'] -
			($this->user->rolle->hideMenue  == 1 ? $size['menue']['hide_width'] : $size['menue']['width']) -
			($this->user->rolle->hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width'])
			- 10;	# Breite für möglichen Scrollbalken

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
		$this->split_datasets($this->formvars['selected_layer_id'], array('oid'), array($this->formvars['oid']), array($this->formvars['layer_columnname']), $single_geoms, $mapdb);
		$this->loadMap('DataBase');					# Karte anzeigen
		$currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->saveMap('');
		$this->output();
	}

	function split_datasets($layer_id, $id_names, $id_values, $update_columns, $update_values, $mapdb){
		# Diese Funktion teilt einen über die Arrays $id_names und $id_values bestimmten Datensatz in einem Layer auf x neue Datensätze auf.
		# Jeder Datensatz unterscheidet sich in den Attributen, die über das Array $update_columns definiert werden, von den anderen.
		# D.h. der Datensatz wird x-mal kopiert und alle Attribute in $update_columns auf den entsprechenden Wert im Array $update_values gesetzt.
		# Wie oft das passiert, hängt von der Größe des Arrays $update_values ab.
		# Außerdem wird dieses Splitting rekursiv auf den über SubformPK- oder embeddedPK verknüpften Layern durchgeführt.

		$layerset = $this->user->rolle->getLayer($layer_id);
		$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$layerattributes = $mapdb->read_layer_attributes($layer_id, $layerdb, NULL);

		# Attribute, die kopiert werden sollen ermitteln
		$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$layerset[0]['maintable']."' AND table_schema = '".$layerdb->schema."' ";

		$ret=$layerdb->execSQL($sql,4, 0);
		if(!$ret[0]){
			while ($rs=pg_fetch_row($ret[1])){
				if(!in_array($layerattributes['constraints'][$rs[0]], array('PRIMARY KEY', 'UNIQUE'))) $attributes[] = $rs[0];
			}
		}

		for($i = 0; $i < count($update_values); $i++){
			$sql = "INSERT INTO ".$layerset[0]['maintable']." (".implode(',', $attributes).") SELECT ".implode(',', $attributes)." FROM ".$layerset[0]['maintable']." WHERE ";
			for($n = 0; $n < count($id_names); $n++){
				$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
			}
			$sql.= "1=1 RETURNING oid";
			#echo $sql.'<br>';
			$ret = $layerdb->execSQL($sql,4, 0);
			$new_oids = array();
			if(!$ret[0]){
				while($rs=pg_fetch_row($ret[1])){
					$new_oids[] = $rs[0];
					$all_new_oids[] = $rs[0];
				}
			}
			if($new_oids[0] != ''){			# nur updaten, wenn auch was eingetragen wurde
				for($u = 0; $u < count($update_columns); $u++){
					$sql = "UPDATE ".$layerset[0]['maintable']." SET ".$update_columns[$u]." = '".$update_values[$i][$u]."' WHERE oid IN (".implode(',', $new_oids).")";
					$ret = $layerdb->execSQL($sql,4, 0);
				}
			}
		}

		if($all_new_oids[0] != ''){			# nur weitermachen, wenn auch was eingetragen wurde
			$j = 0;
			for($l = 0; $l < count($layerattributes['name']); $l++){
	    	if(in_array($layerattributes['form_element_type'][$l], array('SubFormEmbeddedPK', 'SubFormPK'))){
	    		$subform_pks = array();
	    		$pkvalues = array();
	    		$subform_pks = array();
	    		$next_update_values = array();;
					$options = explode(';', $layerattributes['options'][$l]);
	        $subform = explode(',', $options[0]);
	        $subform_layerid = $subform[0];
	        if($layerattributes['form_element_type'][$l] == 'SubFormEmbeddedPK')$minus = 1;
	        else $minus = 0;
	        for($k = 1; $k < count($subform)-$minus; $k++){
	        	$subform_pks[] = $subform[$k];																																# das sind die Namen der SubformPK-Schlüssel
	        }
	        $sql = "SELECT ".implode(',', $subform_pks)." FROM ".$layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus dem alten Datensatz abfragen
	        for($n = 0; $n < count($id_names); $n++){
						$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
					}
					$sql.= "1=1";
					#echo $sql.'<br>';
	    		$ret=$layerdb->execSQL($sql,4, 0);
					if(!$ret[0]){
						$pkvalues=pg_fetch_row($ret[1]);
					}
	    		$sql = "SELECT ".implode(',', $subform_pks)." FROM ".$layerset[0]['maintable']." WHERE ";			# die Werte der SubformPK-Schlüssel aus den neuen Datensätzen abfragen
	        $sql.= "oid IN (".implode(',', $all_new_oids).")";
	        #echo $sql.'<br>';
	    		$ret=$layerdb->execSQL($sql,4, 0);
					if(!$ret[0]){
						while($rs=pg_fetch_row($ret[1])){
							$next_update_values[] = $rs;
						}
					}
	        $j++;
	        $this->split_datasets($subform_layerid, $subform_pks, $pkvalues, $subform_pks, $next_update_values, $mapdb);
		    }
			}
			$sql = "DELETE FROM ".$layerset[0]['maintable']." WHERE ";
			for($n = 0; $n < count($id_names); $n++){
				$sql.= $id_names[$n]." = '".$id_values[$n]."' AND ";
			}
			$sql.= "1=1";#
			#echo $sql.'<br>';
			$ret = $layerdb->execSQL($sql,4, 0);
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
    $attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, $attributenames);
		$options = array_shift(explode(';', $attributes['options'][$this->formvars['attribute']]));
    $reqby_start = strpos(strtolower($options), "<required by>");
    if($reqby_start > 0)$sql = substr($options, 0, $reqby_start);else $sql = $options;
		$attributenames = explode('|', $this->formvars['attributenames']);
		$attributevalues = explode('|', $this->formvars['attributevalues']);
		for($i = 0; $i < count($attributenames); $i++){
			$sql = str_replace('<requires>'.$attributenames[$i].'</requires>', "'".$attributevalues[$i]."'", $sql);
		}
		#echo $sql;
		$ret=$layerdb->execSQL($sql,4,0);
		if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
		switch($this->formvars['type']) {
			case 'select-one' : {					# ein Auswahlfeld soll mit den Optionen aufgefüllt werden
				$html = '>';			# Workaround für dummen IE Bug
				$html .= '<option value="">-- Auswahl --</option>';
				while($rs = pg_fetch_array($ret[1])){
					$html .= '<option value="'.$rs['value'].'">'.$rs['output'].'</option>';
				}
			}break;

			case 'text' : {								#  ein Textfeld soll nur mit dem ersten Wert aufgefüllt werden
				$rs = pg_fetch_array($ret[1]);
				$html = $rs['output'];
			}break;
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
		if($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
		$this->formvars['svg_string'] = str_replace(IMAGEURL, IMAGEPATH, strip_pg_escape_string($this->formvars['svg_string'])).'</svg>';
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
      ob_start("output_handler");
      header('Content-Disposition: inline; filename="Karte.jpg"');
      ImageJPEG($mainimage);
    }
    else{
    	ob_end_clean();
      header('content-type: image/jpg');
      header('Content-Disposition: inline; filename="Karte.jpg"');
      readfile(IMAGEPATH.$jpgfile);
    }
  }

  # Funktion zu Testzwecken der postgresql-Datenbankanfragens
  function loadDenkmale_laden(){
		include_(CLASSPATH.'xml.php');
    # Erzeugen eines HIDA XML-Export-Dokument-Objektes
    $hidaDoc=new hidaDocument(DEFAULT_DENKMAL_IMPORT_FILE);
    # Einlesen der Felder in die Datenbank
    $hidaDoc->loadDocInDatabase();
    # Übergabe der Felder zur Ausgabe in HTML
    $this->fields=$hidaDoc->fields;
    # Löschen des Objektes
    unset($hidaDoc);
    # Setzen des Ausgabetemplates
    $this->main='denkmale_geladen.php';
  }

  function get_classes(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->classdaten = $mapDB->read_Classes($this->formvars['layer_id']);
    echo'
      <select style="width:200px" size="4" class="select" name="class_1" onchange="change_class();"';
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
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
    echo'
      <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td height="25" valign="top">Styles</td><td align="right"><a href="javascript:add_style();">neuer Style</a></td>
        </tr>';
    if(count($this->classdaten[0]['Style']) > 0){
      $this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
      for($i = 0; $i < count($this->classdaten[0]['Style']); $i++){
        echo'
          <tr>
            <td ';
            if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
            echo 'id="td1_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" onclick="get_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">';
              echo '<img src="'.IMAGEURL.$this->getlegendimage($this->formvars['layer_id'], $this->classdaten[0]['Style'][$i]['Style_ID']).'"></td>';
              echo '<td align="right" id="td2_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" ';
              if($this->formvars['style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
              echo '>';
              if($i < count($this->classdaten[0]['Style'])-1){echo '<a href="javascript:movedown_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach unten verschieben"><img src="'.GRAPHICSPATH.'pfeil.gif" border="0"></a>';}
              if($i > 0){echo '&nbsp;<a href="javascript:moveup_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach oben verschieben"><img src="'.GRAPHICSPATH.'pfeil2.gif" border="0"></a>';}
              echo html_umlaute('&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">löschen</a>');
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
    $this->classdaten = $mapDB->read_ClassesbyClassid($this->formvars['class_id']);
      echo'
        <table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td height="25" valign="top">Labels</td><td colspan="2" align="right"><a href="javascript:add_label();">neues Label</a></td>
          </tr>';
      if(count($this->classdaten[0]['Label']) > 0){
        for($i = 0; $i < count($this->classdaten[0]['Label']); $i++){
          echo'
            <tr>
              <td ';
              if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
              echo' id="td1_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" onclick="get_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">';
                echo 'Label '.$this->classdaten[0]['Label'][$i]['Label_ID'].'</td>';
                echo '<td align="right" id="td2_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" ';
                if($this->formvars['label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
                echo html_umlaute('><a href="javascript:delete_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">löschen</a>');
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
    echo '~';
    $this->get_labels();
  }

  function save_style(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Style($this->formvars);
    $this->get_styles();
    echo '~';
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
    $this->styledaten = $mapDB->get_Style($this->formvars['style_id']);
    if(is_array($this->styledaten)){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->styledaten); $i++){
        echo'
          <tr>
            <td class="px13">';
              echo key($this->styledaten).'</td><td><input ';
              if($i === 0)echo 'onkeyup="if(event.keyCode != 8)get_style(this.value)"';
              echo ' name="style_'.key($this->styledaten).'" size="20" type="text" value="'.$this->styledaten[key($this->styledaten)].'">';
        echo'
            </td>
          </tr>';
        next($this->styledaten);
      }
      echo'
          <tr>
            <td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="style_save" value="Speichern" onclick="save_style('.$this->styledaten['Style_ID'].')"></td>
          </tr>
        </table>';
    }
  }

  function save_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->save_Label($this->formvars);
    $this->get_labels();
    echo '~';
    $this->get_label();
  }

  function get_label(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->labeldaten = $mapDB->get_Label($this->formvars['label_id']);
    if(count($this->labeldaten) > 0){
      echo'
        <table align="left" border="0" cellspacing="0" cellpadding="3">';
      for($i = 0; $i < count($this->labeldaten); $i++){
        echo'
          <tr>
            <td class="px13">';
              echo key($this->labeldaten).'</td><td><input name="label_'.key($this->labeldaten).'" size="11" type="text" value="'.$this->labeldaten[key($this->labeldaten)].'">';
        echo'
            </td>
          </tr>';
        next($this->labeldaten);
      }
      echo'
          <tr>
            <td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="label_save" value="Speichern" onclick="save_label('.$this->labeldaten['Label_ID'].')"></td>
          </tr>
        </table>';
    }
  }

  function get_sub_menues(){
    $this->Menue = new menues($this->user->rolle->language);
    $submenues = $this->Menue->getsubmenues($this->formvars['menue_id']);
    echo '<select name="submenues" size="6" multiple style="width:300px">';
    for($i=0; $i < count($submenues["Bezeichnung"]); $i++){
      echo '<option selected title="'.$submenues["Bezeichnung"][$i].'" id="'.$submenues["ORDER"][$i].'_all_'.$submenues["menueebene"][$i].'_'.$i.'" value="'.$submenues["ID"][$i].'">&nbsp;&nbsp;-->&nbsp;'.$submenues["Bezeichnung"][$i].'</option>';
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

  function create_dynamic_legend(){
		foreach($this->groupset as $group){
			if($group['obergruppe'] == ''){
				$legend .= $this->create_group_legend($group['id']);
			}
		}
		$legend .= '<input type="hidden" name="layers" value="'.$this->layer_id_string.'">';
		$legend .= '<input type="hidden" name="zoom_layer_id" value="">';
		return $legend;
  }

  function get_gps_position(){
    // erzeuge GPS Objekt
		include_(CLASSPATH.'gps.php');
    $gps = new gps(GPSPATH);
    // frage aktuelle GPS-Position
    $gps->readPosition();
    // transformiere in gewünschtes Koordinatensystem
    $point=transform($gps->lon,$gps->lat,'4326',$this->formvars['srs']);
    // Ausgabe der Koordinaten im Format Rechtswert~Hochwert
    echo $point->x.'~'.$point->y;
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
    if ($contenttype == 'image/png'){
      header('Content-type: image/png');
    }
    ms_iogetStdoutBufferBytes();
    if ($this->writeTmpFile) {
      $wms_response = new wms_response_obj($this->tmpfile);
      $wms_response->save(ob_get_contents());
    }
    ob_end_flush();
    ms_ioresethandlers();
  }


  function adminFunctions() {
		include_once(CLASSPATH.'administration.php');
		$this->administration = new administration($this->database, $this->pgdatabase);
		$this->administration->get_database_status();
    switch ($this->formvars['func']) {
			case "update_databases" : {
        $this->administration->update_databases();
				$this->administration->get_database_status();
				$this->showAdminFunctions();
      } break;
			case "update_code" : {
        $this->administration->update_code();
				$this->administration->get_database_status();
				$this->showAdminFunctions();
      } break;
      case "showConstants" : {
        $this->showConstants();
      } break;
      case "createRandomPassword" : {
        $this->createRandomPassword();
      } break;
      case "save_all_layer_attributes" : {
        $this->save_all_layer_attributes();
      } break;
      case "custom"  : {
        $admin_function_file = LAYOUTPATH . 'custom/adminfunctions.php';
        if (file_exists($admin_function_file)) {
          $this->main = $admin_function_file;
          $this->titel = 'Eigene Administrationsfunktionen';
        } else {
          $this->showAdminFunctions();
        }
      } break;
      default : {
        $this->showAdminFunctions();
      }
    }
  }

	function save_all_layer_attributes(){
		$this->main='genericTemplate.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->layerdaten = $mapDB->get_postgis_layers(NULL);
		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
			$layer = $mapDB->get_Layer($this->layerdaten['ID'][$i]);
			if($layer['pfad'] != '' AND strpos($layer['connection'], 'host') === false){
				$this->param['str1'].= 'Layer: '.$layer['Name'].'<br>';
				echo 'Layer: '.$layer['Name'].'<br>';
				$layerdb = $mapDB->getlayerdatabase($layer['Layer_ID'], $this->Stelle->pgdbhost);

				$attributes = $mapDB->load_attributes(
					$layerdb,
					replace_params($layer['pfad'], rolle::$layer_params)
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
    $this->main='showconstants.php';
    $this->titel='Konstanten';
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
    # erzeugen des Menueobjektes
    $this->Menue=new menues($this->user->rolle->language);
    # laden des Menues der Stelle und der Rolle
    $this->Menue->loadMenue($this->Stelle->id, $this->user->id);
    $this->Menue->get_menue_width($this->Stelle->id);
    $this->user->rolle->hideMenue(0);
    include(LAYOUTPATH."snippets/".$this->formvars['menuebodyfile']);
		echo '~if(typeof resizemap2window != "undefined")resizemap2window();';
  }

  function hideMenueWithAjax() {
    $this->user->rolle->hideMenue(1);
		echo '~if(typeof resizemap2window != "undefined")resizemap2window();';
  }

	function changeLegendDisplay(){
		$this->user->rolle->changeLegendDisplay($this->formvars['hide']);
		echo 'hide: ' . $this->formvars['hide'] . '~resizemap2window();';
	}

	function saveOverlayPosition(){
  	$this->user->rolle->saveOverlayPosition($this->formvars['overlayx'],$this->formvars['overlayy']);
  }

  function PointEditor(){
		include_once (CLASSPATH.'pointeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(100);
    $this->main='PointEditor.php';
    $this->titel='Geometrie bearbeiten';
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
		$attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL);
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    if($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != ''){
			$this->neuLaden();
		}
		else{
			$this->loadMap('DataBase');
			if($this->formvars['oid'] != ''){
				$this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
				if($this->point['pointx'] != ''){
					$this->formvars['loc_x']=$this->point['pointx'];
					$this->formvars['loc_y']=$this->point['pointy'];
					$rect = ms_newRectObj();
					$rect->minx = $this->point['pointx']-100;
					$rect->maxx = $this->point['pointx']+100;
					$rect->miny = $this->point['pointy']-100;
					$rect->maxy = $this->point['pointy']+100;
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
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
    $this->saveMap('');
    if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
    	$currenttime=date('Y-m-d H:i:s',time());
    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    }
    $this->drawMap();
    $this->output();
  }

  function PointEditor_Senden(){
		include_(CLASSPATH.'pointeditor.php');
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
    $pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $pointeditor->pruefeEingabedaten($this->formvars['loc_x'], $this->formvars['loc_y']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->PointEditor();
      return;
    }
    else{
      $ret = $pointeditor->eintragenPunkt($this->formvars['loc_x'],$this->formvars['loc_y'], $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->formvars['dimension']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
	      # wenn Time-Attribute vorhanden, aktuelle Zeit speichern      
				$this->attributes = $mapDB->read_layer_attributes($this->formvars['layer_id'], $layerdb, NULL);
				for($i = 0; $i < count($this->attributes['type']); $i++){
					if($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Time'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PointEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PointEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'UserID'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = ".$this->user->id." WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PointEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
				}
        $this->add_message('notice', 'Eintrag erfolgreich!');
      }
      $this->PointEditor();
    }
  }

  function LineEditor(){
		include_once (CLASSPATH.'lineeditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(100);
    $this->main='LineEditor.php';
    $this->titel='Geometrie bearbeiten';
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		if($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != ''){
			$this->neuLaden();
			$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
		}
		else{
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
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
    # Spaltenname und from-where abfragen
    $data = $this->mapDB->getData($this->formvars['layer_id']);
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
    $this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
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

  function LineEditor_Senden(){
		include_(CLASSPATH.'lineeditor.php');
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $lineeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->LineEditor();
      return;
    }
    else {
      $umring = $this->formvars['newpathwkt'];
      $ret = $lineeditor->eintragenLinie($umring, $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
	      # wenn Time-Attribute vorhanden, aktuelle Zeit speichern      
				$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
				for($i = 0; $i < count($this->attributes['type']); $i++){
					if($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Time'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Länge'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->formvars['linelength']."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'UserID'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = ".$this->user->id." WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :LineEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
				}
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstline']="";
        $this->formvars['secondline']="";
        $this->formvars['secondpoly']="";
        $this->add_message('notice', 'Eintrag erfolgreich!');
      }
      $this->formvars['CMD'] = '';
      $this->LineEditor();
    }
  }

  function PolygonEditor(){
		include_once (CLASSPATH.'polygoneditor.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->reduce_mapwidth(100);
   	$this->main='PolygonEditor.php';
    $this->titel='Geometrie bearbeiten';
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$this->formvars['geom_nullable'] = $attributes['nullable'][$attributes['indizes'][$attributes['the_geom']]];
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
		if($this->formvars['oldscale'] != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != ''){
			$this->neuLaden();
			$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
		}
		else{
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
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
    # Geometrie-Übernahme-Layer:
    # Spaltenname und from-where abfragen
    $data = $this->mapDB->getData($this->formvars['layer_id']);
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

    $this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
      $this->formvars['fromwhere'] .= ' where (1=1)';
    }

		if($this->formvars['newpath'] == '' AND $this->formvars['layer_id'] < 0){	# Suchergebnislayer sofort selektieren
			$rollenlayer = $this->mapDB->read_RollenLayer(-$this->formvars['layer_id']);
			if($rollenlayer[0]['Typ'] == 'search'){
				$layerdb1 = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
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
    $polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
    # eingeabewerte pruefen:
    $ret = $polygoneditor->pruefeEingabedaten($this->formvars['newpathwkt']);
    if ($ret[0]) { # fehlerhafte eingabedaten
      $this->Meldung=$ret[1];
      $this->PolygonEditor();
      return;
    }
    else{
      $umring = $this->formvars['newpathwkt'];
      $ret = $polygoneditor->eintragenFlaeche($umring, $this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
      if ($ret[0]) { # fehler beim eintrag
          $this->Meldung=$ret[1];
      }
      else { # eintrag erfolgreich
	      # wenn Time-Attribute vorhanden, aktuelle Zeit speichern
				$this->attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
				for($i = 0; $i < count($this->attributes['type']); $i++){
					if($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Time'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".date('Y-m-d G:i:s')."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'Fläche'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->formvars['area']."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'User'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = '".$this->user->Vorname." ".$this->user->Name."' WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
					elseif($this->attributes['name'][$i] != 'oid' AND $this->attributes['form_element_type'][$i] == 'UserID'){
						$sql = "UPDATE ".$this->formvars['layer_tablename']." SET ".$this->attributes['name'][$i]." = ".$this->user->id." WHERE oid = '".$this->formvars['oid']."'";
						$this->debug->write("<p>file:kvwmap :PolygonEditor_Senden :",4);
						$ret = $layerdb->execSQL($sql,4, 1);
					}
				}
        $this->formvars['newpath']="";
        $this->formvars['newpathwkt']="";
        $this->formvars['pathwkt']="";
        $this->formvars['firstpoly']="";
        $this->formvars['secondpoly']="";
        $this->add_message('notice', 'Eintrag erfolgreich!');
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
      	$select .= "'".$oids[$i]."',";
      }
      $select = substr($select, 0, -1);
      $select .= ")";
      $datastring = $this->formvars['layer_columnname']." from (".$select.' '.$orderby;
      $datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
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
			$select = str_replace($datageom, $datageom.', oid', $select);
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
		$select .= $oid." = '".$this->formvars['oid']."'";

		$datastring = $datageom." from (".$select;
		$datastring.=") as foo using unique oid using srid=".$layerset[0]['epsg_code'];
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
		if($layerset[0]['Datentyp'] == MS_LAYER_POLYGON)$this->formvars['transparency'] = 60;
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
					$style['symbolname'] = NULL;
					$style['backgroundcolor'] = NULL;
					$style['minsize'] = NULL;
					$style['maxsize'] = 100000;
					$style['angle'] = 360;
					$style_id = $dbmap->new_Style($style);
				}break;
			}
			$dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
		}
		else{         # selektieren (eigenen Style verwenden)
			$class_id =  $dbmap->getClassFromObject($select, $this->formvars['layer_id']);
			$this->formvars['class'] = $dbmap->copyClass($class_id, -$layer_id);
			$this->user->rolle->setOneLayer($this->formvars['layer_id'], 0);
		}
		$this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
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
      $this->point = $pointeditor->getpoint($this->formvars['oid'], $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);

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
    if(IMAGEMAGICK == 'true'){
      $this->druckrahmen_load_pdf();
    }
    else{
      $this->druckrahmen_load_html();
    }
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
    $this->titel='Druckausschnitt wählen';
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
    # wenn Druckausschnitts-ID übergeben, Ausschnitt laden
    if($this->formvars['druckausschnitt'] != ''){
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
    if ($this->formvars['CMD']!='') {
    	$this->navMap($this->formvars['CMD']);
      $this->saveMap('');
      $currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
      $this->drawMap();
    }
    else {
      $this->saveMap('');
      $this->drawMap();
    }
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

  function druckvorschau_html(){
    $this->main = 'druckvorschau_html.php';
    $this->titel = 'Druckvorschau';
    $Document=new Document($this->database);
    $this->Document=$Document;
    $frameid = $this->Document->get_active_frameid($this->user->id, $this->Stelle->id);
    $this->Document->activeframe = $this->Document->load_frames($this->Stelle->id, $frameid);

    # Text für die html-Vorschau
    $this->Document->text = str_replace(';', '<br>', $this->Document->activeframe[0]['text']);

    if($this->formvars['vorschauzoom'] == ''){
      $this->formvars['vorschauzoom'] = 1;
    }
    $zoom = $this->formvars['vorschauzoom'];

    switch ($this->Document->activeframe[0]['format']){
    	case 'A5hoch' : {
        $ratio = 420/595/$zoom;
        $height = 595/$ratio;
      } break;
      case 'A5quer' : {
        $ratio = 595/595/$zoom;
        $height = 420/$ratio;
      } break;
      case 'A4hoch' : {
        $ratio = 595/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A4quer' : {
        $ratio = 842/595/$zoom;
        $height = 595/$ratio;
      } break;
      case 'A3hoch' : {
        $ratio = 842/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A3quer' : {
        $ratio = 1191/595/$zoom;
        $height = 842/$ratio;
      } break;
      case 'A2hoch' : {
        $ratio = 1191/595/$zoom;
        $height = 1684/$ratio;
      } break;
      case 'A2quer' : {
        $ratio = 1684/595/$zoom;
        $height = 1191/$ratio;
      } break;
      case 'A1hoch' : {
        $ratio = 1684/595/$zoom;
        $height = 2384/$ratio;
      } break;
      case 'A1quer' : {
        $ratio = 2384/595/$zoom;
        $height = 1684/$ratio;
      } break;
      case 'A0hoch' : {
        $ratio = 2384/595/$zoom;
        $height = 3370/$ratio;
      } break;
      case 'A0quer' : {
        $ratio = 3370/595/$zoom;
        $height = 2384/$ratio;
      } break;
    }
    $this->Document->width = 595*$zoom;
    $this->Document->headposx = $this->Document->activeframe[0]['headposx']/$ratio;
    $this->Document->headposy = $this->Document->activeframe[0]['headposy']/$ratio;
    $this->Document->headwidth = $this->Document->activeframe[0]['headwidth']/$ratio;
    $this->Document->headheight = $this->Document->activeframe[0]['headheight']/$ratio;
    $this->Document->mapposx = $this->Document->activeframe[0]['mapposx']/$ratio;
    $this->Document->mapposy = $this->Document->activeframe[0]['mapposy']/$ratio;
    $this->Document->mapwidth = $this->Document->activeframe[0]['mapwidth']/$ratio;
    $this->Document->mapheight = $this->Document->activeframe[0]['mapheight']/$ratio;
    $this->Document->refmapposx = $this->Document->activeframe[0]['refmapposx']/$ratio;
    $this->Document->refmapposy = $this->Document->activeframe[0]['refmapposy']/$ratio;
    $this->Document->refmapwidth = $this->Document->activeframe[0]['refmapwidth']/$ratio;
    $this->Document->refmapheight = $this->Document->activeframe[0]['refmapheight']/$ratio;
    $this->Document->refposx = $this->Document->activeframe[0]['refposx']/$ratio;
    $this->Document->refposy = $this->Document->activeframe[0]['refposy']/$ratio;
    $this->Document->refwidth = $this->Document->activeframe[0]['refwidth']/$ratio;
    $this->Document->refheight = $this->Document->activeframe[0]['refheight']/$ratio;
    $this->Document->dateposx = $this->Document->activeframe[0]['dateposx']/$ratio;
    $this->Document->dateposy = $this->Document->activeframe[0]['dateposy']/$ratio;
    $this->Document->datesize = $this->Document->activeframe[0]['datesize']/$ratio;
    $this->Document->dateposy = $this->Document->dateposy - $this->Document->datesize/4;
    $this->Document->scaleposx = $this->Document->activeframe[0]['scaleposx']/$ratio;
    $this->Document->scaleposy = $this->Document->activeframe[0]['scaleposy']/$ratio;
    $this->Document->scalesize = round($this->Document->activeframe[0]['scalesize']/$ratio);
    $this->Document->scaleposy = $this->Document->scaleposy - $this->Document->scalesize/4;
    $this->Document->oscaleposx = $this->Document->activeframe[0]['oscaleposx']/$ratio;
    $this->Document->oscaleposy = $this->Document->activeframe[0]['oscaleposy']/$ratio;
    $this->Document->oscalesize = $this->Document->activeframe[0]['oscalesize']/$ratio;
    $this->Document->oscaleposy = $this->Document->oscaleposy - $this->Document->oscalesize/4;
    $this->Document->gemarkungposx = $this->Document->activeframe[0]['gemarkungposx']/$ratio;
    $this->Document->gemarkungposy = $this->Document->activeframe[0]['gemarkungposy']/$ratio;
    $this->Document->gemarkungsize = $this->Document->activeframe[0]['gemarkungsize']/$ratio;
    $this->Document->gemarkungposy = $this->Document->gemarkungposy - $this->Document->gemarkungsize/4;
    $this->Document->flurposx = $this->Document->activeframe[0]['flurposx']/$ratio;
    $this->Document->flurposy = $this->Document->activeframe[0]['flurposy']/$ratio;
    $this->Document->flursize = $this->Document->activeframe[0]['flursize']/$ratio;
    $this->Document->flurposy = $this->Document->flurposy - $this->Document->flursize/4;
    $this->Document->textposx = $this->Document->activeframe[0]['textposx']/$ratio;
    $this->Document->textposy = $this->Document->activeframe[0]['textposy']/$ratio;
    $this->Document->textsize = $this->Document->activeframe[0]['textsize']/$ratio;
    $this->Document->textposy = $this->Document->textposy - $this->Document->textsize/4;
    $this->Document->legendposx = $this->Document->activeframe[0]['legendposx']/$ratio;
    $this->Document->legendposy = $this->Document->activeframe[0]['legendposy']/$ratio;
    $this->Document->legendsize = $this->Document->activeframe[0]['legendsize']/$ratio;
    $this->Document->userposx = $this->Document->activeframe[0]['userposx']/$ratio;
    $this->Document->userposy = $this->Document->activeframe[0]['userposy']/$ratio;
    $this->Document->usersize = $this->Document->activeframe[0]['usersize']/$ratio;
    $this->Document->userposy = $this->Document->userposy - $this->Document->usersize/4;

    $this->Document->height = $height;

    if($this->formvars['map_factor'] != ''){
    	$this->map_factor = $this->formvars['map_factor'];
    }
    else{
      $this->map_factor = MAPFACTOR;
    }

    if($this->formvars['loadmapsource']){
      $this->loadMap($this->formvars['loadmapsource']);
    }
    else{
      $this->loadMap('DataBase');
    }
    $this->map->selectOutputFormat('jpeg');
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
		$this->map->set('width', $this->Document->activeframe[0]['mapwidth'] * $widthratio * $this->map_factor);
    $this->map->set('height', $this->Document->activeframe[0]['mapheight'] * $heightratio * $this->map_factor);

    $this->map->setextent($minx,$miny,$maxx,$maxy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'print_preview',$this->user->rolle->last_time_id);
    $this->drawMap();

    if($this->formvars['angle'] != 0){
      $angle = -1 * $this->formvars['angle'];
      $image = imagecreatefromjpeg(IMAGEPATH.basename($this->img['hauptkarte']));
      $rotatedimage = imagerotate($image, $angle, 0);
      $width = imagesx($rotatedimage);
      $height = imagesy($rotatedimage);
      $clipwidth = $this->Document->activeframe[0]['mapwidth']*$this->map_factor;
      $clipheight = $this->Document->activeframe[0]['mapheight']*$this->map_factor;
      $clipx = ($width - $clipwidth) / 2;
      $clipy = ($height - $clipheight) / 2;
      $clippedimage = imagecreatetruecolor($clipwidth, $clipheight);
      ImageCopy($clippedimage, $rotatedimage, 0, 0, $clipx, $clipy, $clipwidth, $clipheight);
      imagejpeg($clippedimage, IMAGEPATH.basename($this->img['hauptkarte']) , 100);
    }

    #setzen der rollen-Kartenparameter fürs loggen
    $this->user->rolle->oGeorefExt->minx = $minx;
    $this->user->rolle->oGeorefExt->miny = $miny;
    $this->user->rolle->oGeorefExt->maxx = $maxx;
    $this->user->rolle->oGeorefExt->maxy = $maxy;
    $this->user->rolle->nImageWidth = $this->map->width;
    $this->user->rolle->nImageHeight = $this->map->height;
    # Lagebezeichnung
    $flur=new Flur('','','',$this->pgdatabase);
    $bildmitte['rw']=$this->formvars['center_x'];
    $bildmitte['hw']=$this->formvars['center_y'];
    $this->lagebezeichnung = $flur->getBezeichnungFromPosition($bildmitte, $this->user->rolle->epsg_code);
    # Übersichtskarte
    if($this->Document->activeframe[0]['refmapfile']){
      $refmapfile = DRUCKRAHMEN_PATH.$this->Document->activeframe[0]['refmapfile'];
      $zoomfactor = $this->Document->activeframe[0]['refzoom'];
			$refwidth = $this->Document->activeframe[0]['refwidth']*$this->map_factor;
			$refheight = $this->Document->activeframe[0]['refheight']*$this->map_factor;
			$width = $refwidth*$widthratio;
			$height = $refheight*$heightratio;
			$this->Document->referencemap = $this->createReferenceMap($width, $height, $refwidth, $refheight, $angle, $minx,$miny,$maxx,$maxy, $zoomfactor, $refmapfile);
    }
    # Legende rendern
    if($this->Document->activeframe[0]['legendsize'] > 0){
      $legend = $this->createlegend($this->Document->activeframe[0]['legendsize']);
      $this->Document->legend = IMAGEURL.basename($legend['name']);
      $this->Document->legendwidth = $legend['width']/$ratio;
    }
    # Wasserzeichen hinzufügen
    if($this->Document->activeframe[0]['watermark'] != ''){
      $this->addwatermark($this->Document->activeframe[0]);
    }

    # Freitexte
    for($j = 0; $j < count($this->Document->activeframe[0]['texts']); $j++){
      if($this->Document->activeframe[0]['texts'][$j]['text'] == '' AND $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] != ''){    // ein Freitext hat keinen Text aber in der Druckausschnittswahl wurde ein Text vom Nutzer eingefügt
        $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(chr(10), ';', $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]);
        $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']] = str_replace(chr(13), '', $this->formvars['freetext'.$this->Document->activeframe[0]['texts'][$j]['id']]);
      }
    }
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

  function createlegend($size){
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
    $layerset = $legendmapDB->read_Layer(1);
    $rollenlayer = $legendmapDB->read_RollenLayer();
    $layerset = array_merge($layerset, $rollenlayer);
    for($i = 0; $i < $this->map->numlayers; $i++){
      $layer = $this->map->getlayer($i);
      $layer->set('status', 0);
    }
    $scale = $this->map_scaledenom * $this->map_factor / 1.414;
    $legendimage = imagecreatetruecolor(1,1);
    $backgroundColor = ImageColorAllocate($legendimage, 255, 255, 255);
    imagefill ($legendimage, 0, 0, $backgroundColor);

    for($i = 0; $i < count($layerset); $i++){
      if($layerset[$i]['aktivStatus'] != 0){
        if(($layerset[$i]['minscale'] < $scale OR $layerset[$i]['minscale'] == 0) AND ($layerset[$i]['maxscale'] > $scale OR $layerset[$i]['maxscale'] == 0)){
					if($layerset[$i]['alias'] != '')$name = $layerset[$i]['alias'];
					else $name = $layerset[$i]['Name'];
          $layer = $this->map->getLayerByName($name);
          if($layerset[$i]['showclasses']){
            for($j = 0; $j < $layer->numclasses; $j++){
              $class = $layer->getClass($j);
              if($class->name != '')$draw = true;
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
          if($layerset[$i]['showclasses']){
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
    $layer->set('connection', $layerset['connection']);
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
    $style->set('size', $dbStyle['size']);
    if($dbStyle['width']!='') {
      $style->set('width', $dbStyle['width']);
    }
    if($dbStyle['angle']!='') {
      $style->set('angle', $dbStyle['angle']);
    }
    if (MAPSERVERVERSION < 500 AND $dbStyle['sizeitem']!='') {
      $style->sizeitem = $dbStyle['sizeitem'];
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
    $RGB=explode(" ",$dbStyle['color']);
    if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
    if(is_numeric($RGB[0]))$style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
		else $style->updateFromString("STYLE COLOR [".$dbStyle['color']."] END");
    $RGB=explode(" ",$dbStyle['outlinecolor']);
    $style->outlinecolor->setRGB(intval($RGB[0]),intval($RGB[1]),intval($RGB[2]));
    if($dbStyle['backgroundcolor']!='') {
      $RGB=explode(" ",$dbStyle['backgroundcolor']);
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
      $this->formvars['textposition']="POINT(".$location_x." ".$location_y.")";
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
    $this->stelle=new stelle('',$this->database);
    $this->stellen=$this->stelle->getStellen('Bezeichnung');
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

  function metadatenSuchen() {
		include_(CLASSPATH.'metadaten.php');
    # Zuweisen von Titel und Layoutdatei
    $this->titel='Metadaten Suchergebnisse';
    $this->main='Metadaten.php';
    # Abfragen der Metadaten in der Datenbank
    $this->metadaten=new metadatensatz('',$this->pgdatabase);
    $ret=$this->metadaten->getMetadatenQuickSearch($this->formvars);
    if ($ret[0]) {
      $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
    }
    # Zuweisen von Werten zu Variablen in der Layoutdatei
    $i=0;
    $this->qlayerset[0]['Name']=$this->titel;
    $this->qlayerset[0]['shape']=$ret[1];
    # Ausgabe an den Client
    $this->output();
  }

  function metadatenblattanzeige() {
		include_(CLASSPATH.'metadaten.php');
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

  function metadateneingabe() {
    include_once (CLASSPATH.'metadaten.php');
		include_once(CLASSPATH.'FormObject.php');
    $metadatensatz=new metadatensatz($this->formvars['oid'],$this->pgdatabase);
    if ($this->formvars['oid']!='') {
      # Es handelt sich um eine Änderung eines Datensatzes
      # Auslesen der Metadaten aus der Datenbank und Zuweisung zu Formularobjekten
      $ret=$metadatensatz->getMetadaten($this->formvars);
      if ($ret[0]) {
        $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
      }
      else {
        $this->formvars=array_merge($this->formvars,$ret[1][0]);
      }
      $this->titel='Metadatenänderung';
    }
    else {
      # Anzeigen des Metadateneingabeformulars
      $this->titel='Metadateneingabe';
      # Zuweisen von defaultwerten für die Metadatenelemente wenn nicht vorher
      # schon ein Formular ausgefüllt wurde
      if ($this->formvars['mdfileid']=='') {
        $defaultvalues=$metadatensatz->readDefaultValues($this->user);
        $this->formvars=array_merge($this->formvars,$defaultvalues);
      }
      else {
        # Wenn das Formular erfolgreich eingetragen wurde neue mdfileid vergeben
        if ($this->Fehlermeldung=='') {
          $this->formvars['mdfileid']=rand();
        }
      }
    }
    # Erzeugen der Formularobjekte für die Schlagworteingabe
    $ret=$metadatensatz->getKeywords('','','theme','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allthemekeywords']=$ret[1];
    }
    $ret=$metadatensatz->getKeywords('','','place','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $this->formvars['allplacekeywords']=$ret[1];
    }
    $this->allthemekeywordsFormObj=new FormObject("allthemekeywords","select",$this->formvars['allthemekeywords']['id'],explode(", ",$this->formvars['selectedthemekeywordids']),$this->formvars['allthemekeywords']['keyword'],4,0,1,NULL);
    $this->allplacekeywordsFormObj=new FormObject("allplacekeywords","select",$this->formvars['allplacekeywords']['id'],explode(", ",$this->formvars['selectedplacekeywordids']),$this->formvars['allplacekeywords']['keyword'],4,0,1,NULL);
    $this->main='metadateneingabeformular.php';
    $this->loadMap('DataBase');
    if ($this->formvars['refmap_x']!='') {
      $this->zoomToRefExt();
    }
    $this->navMap($this->formvars['CMD']);
    $this->saveMap('');
    $this->drawMap();
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

	function deleteDokument($path){
		$path = array_pop(explode('&dokument=', $path));
		$path = array_shift(explode('&original_name', $path));
		$dateinamensteil = explode('.', $path);
		if(file_exists($path))unlink($path);
		if(file_exists($dateinamensteil[0].'_thumb.jpg'))unlink($dateinamensteil[0].'_thumb.jpg');
	}

  function get_dokument_vorschau($dateinamensteil){
		$type = strtolower($dateinamensteil[1]);
  	$dokument = $dateinamensteil[0].'.'.$dateinamensteil[1];
		if(in_array($type, array('jpg', 'png', 'gif', 'tif', 'pdf')) ){			// für Bilder und PDFs werden automatisch Thumbnails erzeugt
			$thumbname = $dateinamensteil[0].'_thumb.jpg';
			if(!file_exists($thumbname)){
				exec(IMAGEMAGICKPATH.'convert -filter Hanning '.$dokument.'[0] -quality 75 -background white -flatten -resize '.PREVIEW_IMAGE_WIDTH.'x1000\> '.$thumbname);
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
				if(in_array($type, array(\'jpg\', \'gif\', \'png\')))header("Content-type: image/".$type);
				else header("Content-type: application/".$type);
				header("Content-Disposition: attachment; filename=\"".$_REQUEST[\'original_name\']."\"");
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
			#$this->debug->write("<p>Maßstab des Drucks:".$this->map_scaledenom,4);
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
					$pdf->addJpegFromFile(GRAPHICSPATH.'custom/'.$bild['src'],$bild['posx'],$bild['posy'],$bild['width'],$bild['height']);
				}
				else {
					$pdf->addJpegFromFile(GRAPHICSPATH.'custom/'.$bild['src'],$bild['posx'],$bild['posy'],$bild['width']);
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
				$legend = $this->createlegend($this->Docu->activeframe[0]['legendsize']);
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
    $getMapRequestExample.='&layers='.$this->mapDB->Layer[0]['Name'];
    for ($i=1;$i<$this->mapDB->anzLayer;$i++) {
      $getMapRequestExample.=','.$this->mapDB->Layer[$i]['Name'];
    }
    $getMapRequestExample.='&srs=EPSG:'.EPSGCODE;
    $getMapRequestExample.='&bbox='.$this->map->extent->minx.','.$this->map->extent->miny.','.$this->map->extent->maxx.','.$this->map->extent->maxy;
    $getMapRequestExample.='&width='.$this->map->width.'&height='.$this->map->height;
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
			$this->formvars['Name'] = implode(',', $this->formvars['layers']);
			$this->formvars['Gruppe'] = $groupid;
			$this->formvars['Typ'] = 'search';
			$this->formvars['Datentyp'] = MS_LAYER_RASTER;
			$this->formvars['connectiontype'] = MS_WMS;
			$this->formvars['transparency'] = 100;

			$wms_epsg_codes = array_flip(explode(' ', str_replace('epsg:', '', strtolower($this->formvars['srs'][0]))));
			if($wms_epsg_codes[$this->user->rolle->epsg_code] !== '')$this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
			else $this->formvars['epsg_code'] = 4326;

			if(strpos($this->formvars['wms_url'], '?') !== false)$this->formvars['wms_url'] .= '&';
			else $this->formvars['wms_url'] .= '?';
			$this->formvars['connection'] = $this->formvars['wms_url'].'VERSION=1.1.0&FORMAT=image/png&LAYERS='.implode(',', $this->formvars['layers']);
			$layer_id = $dbmap->newRollenLayer($this->formvars);
			$classdata['layer_id'] = -$layer_id;
			$classdata['name'] = '_';
	    $class_id = $dbmap->new_Class($classdata);
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
    $this->layers = $this->selected_stelle->getLayers(NULL, $this->formvars['order']);
    $this->output();
  }

  function Layer2Stelle_ReihenfolgeSpeichern(){
    $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $this->titel='Layer der Stelle '.$Stelle->Bezeichnung;
    $this->main='layer2stelle_order.php';
    $this->layers = $Stelle->getLayers(NULL);
    for($i = 0; $i < count($this->layers['ID']); $i++){
      $this->formvars['selected_layer_id'] = $this->layers['ID'][$i];
      $this->formvars['drawingorder'] = $this->formvars['drawingorder_layer'.$this->layers['ID'][$i]];
      $Stelle->updateLayerdrawingorder($this->formvars);
    }
    $this->layers = $Stelle->getLayers(NULL);
    $this->output();
  }

  function layer_export(){
  	# Abfragen aller Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdaten = $mapDB->getall_Layer('Name');
    $this->titel='Layer-Export';
    $this->main='layer_export.php';
    $this->output();
  }

  function layer_export_exportieren(){
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
  	$export_layer_ids = explode(', ', $this->formvars['selected_layers']);
  	$this->layer_dumpfile = $mapDB->create_layer_dumpfile($this->database, $export_layer_ids);
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
		$group_id = ($this->formvars['group_id'] == '') ? 1 : $this->formvars['group_id']; 
		$pg_schema = $this->formvars['pg_schema'];
		$this->sql = "
-- Generated by kvwmap " . date('d.m.Y H:i:s') . "
SET @group_id = {$group_id};
SET @connection = 'host={$this->pgdatabase->host} user={$this->pgdatabase->user} password={$this->pgdatabase->passwd_} dbname={$this->pgdatabase->dbName}';
";
		if (!empty($pg_schema)) {
			$tables = $this->pgdatabase->get_tables($pg_schema);
			foreach ($tables AS $table) {
				$this->sql .= $this->generate_layer($pg_schema, $table);
				$this->sql .= $this->database->generate_classes($table);
				$this->sql .= $this->database->generate_styles();
				$this->sql .= $this->database->generate_style2classes();
			}
		}
	}

	/**
	* This function generate sql for a layer in mysql with all its attributes and
	* if they are from type datatype the datatypes and there attribtes.
	* @params $table associatives Array mit Attribut name.
	*/
	function generate_layer($schema, $table, $group_id = 0, $connection = '', $epsg_code = '25832', $geometrie_column = '', $geometrietyp = '', $layertyp = '') {
		#echo "schema: $schema, table: {$table['name']}, group_id: {$group_id}, connection: {$connection}, epsg_code: {$epsg_code}, geometrie_column: $geometrie_column, geometrietype: $geometrietyp, layertype: $layertype";
		$sql = $this->database->generate_layer($schema, $table, $group_id, $connection, $epsg_code, $geometrie_column, $geometrietyp, $layertyp);
		$table_attributes = $this->pgdatabase->get_attribute_information($schema, $table['name']);
		$sql .= $this->generate_layer_attributes($schema, $table_attributes);
		return $sql;
	}

	/**
	* This function generate the sql for all attributes of a layer in mysql
	*
	*/
	function generate_layer_attributes($schema, $table_attributes) {
		foreach ($table_attributes AS $table_attribute) {
			$sql .= $this->generate_layer_attribute($schema, $table_attribute);

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
	function generate_layer_attribute($schema, $table_attribute) {
		if ($table_attribute['type_type'] == 'e')
			$enum_options = $this->pgdatabase->get_enum_options($schema, $table_attribute);
		else
			$enum_options = array('option' => '', 'constraint' => '');
		
		$sql .= $this->database->generate_layer_attribute($table_attribute, $enum_options);
		return $sql;
	}

	/**
	* This function generate sql for a datatype definition and its attributes in mysql
	*/
	function generate_datatype($schema, $table_attribute) {
		$sql .= $this->database->generate_datatype($schema, $table_attribute);

		# generate datatypes attributes
		$datatype_attributes = $this->pgdatabase->get_attribute_information($schema, $table_attribute['type']);
		$sql .= $this->generate_datatype_attributes($schema, $datatype_attributes);
		return $sql;
	}

	/**
	* This function generate sql for attributes of a datatype
	*/
	function generate_datatype_attributes($schema, $datatype_attributes) {
		$sql = '';
		foreach ($datatype_attributes AS $datatype_attribute) {
			$sql .= $this->generate_datatype_attribute($schema, $datatype_attribute);
		}
		return $sql;
	}

	/**
	* This function generate an attribute of a datatype.
	* If its type is an enum generate it with options and constraints
	* If its type is an datatype generate it
	*/
	function generate_datatype_attribute($schema, $datatype_attribute) {
		if ($datatype_attribute['type_type'] == 'e')
			$enum_options = $this->pgdatabase->get_enum_options($schema, $datatype_attribute);
		else
			$enum_options = array('option' => '', 'constraint' => '');

		$sql = $this->database->generate_datatype_attribute($datatype_attribute, $enum_options);

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
    $this->output();
	}	

	function Layereditor() {
		$this->titel='Layer Editor';
		$this->main='layer_formular.php';
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		# Abfragen der Layerdaten wenn eine layer_id zur Änderung selektiert ist
		if ($this->formvars['selected_layer_id'] > 0) {
			$this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id'], NULL, true);
			$this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], false);
			# Abfragen der Stellen des Layer
			$this->formvars['selstellen']=$mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
			$this->grouplayers = $mapDB->get_layersfromgroup($this->layerdata['Gruppe']);
		}
		$this->stellen=$this->Stelle->getStellen('Bezeichnung');
		$this->Groups = $mapDB->get_Groups();
		$this->epsg_codes = read_epsg_codes($this->pgdatabase);
		$this->output();
	}

  function Layereditor_KlasseLoeschen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $mapDB->delete_Class($this->formvars['class_id']);
    $this->Layereditor();
  }

  function Layereditor_KlasseHinzufuegen(){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $attrib['name'] = $this->formvars['class_name'];
    $attrib['layer_id'] = $this->formvars['selected_layer_id'];
		$attrib['classification'] = $this->formvars['classification'];
    $attrib['order'] = ($this->formvars['class_order'] != '') ? $this->formvars['class_order'] : 1;
    $attrib['expression'] = ($this->formvars['class_expression'] != '') ? $this->formvars['class_expression'] : '';
    return $mapDB->new_Class($attrib);
  }

  function Layereditor_AutoklassenHinzufuegen() {
    $num_classes = (empty($this->formvars['num_classes'])) ? 5 : $this->formvars['num_classes'];
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->layerdata = $mapDB->get_Layer($this->formvars['selected_layer_id'], true);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->formvars['Datentyp'] = $this->layerdata['Datentyp'];
    $this->layerdata['Data'] = replace_params($this->layerdata['Data'], rolle::$layer_params);
		$this->layerdata['classitem'] = replace_params($this->layerdata['classitem'], rolle::$layer_params);

    $begin = strpos($this->layerdata['Data'], '(') + 1;
    $end = strrpos($this->layerdata['Data'], ')');
    $data_sql = substr($this->layerdata['Data'], $begin, $end - $begin);

    $auto_classes = $this->AutoklassenErzeugen($layerdb, $data_sql, $this->formvars['classification_column'], $this->formvars['classification_method'], $this->formvars['num_classes'], $this->formvars['classification_name'], $this->formvars['classification_color']);

    for ($i = 0; $i < count($auto_classes); $i++) {
      $this->formvars['class_name'] = $auto_classes[$i]['name'];
      $this->formvars['classification'] = $auto_classes[$i]['classification'];
      $this->formvars['class_order'] = $auto_classes[$i]['order'];
      $this->formvars['class_expression'] = $auto_classes[$i]['expression'];

      $this->formvars['class_id'] = $this->Layereditor_KlasseHinzufuegen();
      $this->formvars['style_color'] = $auto_classes[$i]['style_color'];
      $this->formvars['style_outlinecolor'] = $auto_classes[$i]['style_outlinecolor'];
      $this->add_style();
    }
  }

  function AutoklassenErzeugen($layerdb, $data_sql, $class_item, $method, $num_classes, $classification_name, $classification_color) {
    $classes = array();
    switch ($method) {
			case 1 : {		# für jeden Wert eine Klasse
        $sql = "
          SELECT DISTINCT ".$class_item."
          FROM (".$data_sql.") AS data ORDER BY ".$class_item." LIMIT 50";
				
        $ret=$layerdb->execSQL($sql, 4, 0);
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
            " . $class_item . "
        ";

        $ret=$layerdb->execSQL($sql, 4, 0);
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
            " . $class_item . "
        ";
        $ret=$layerdb->execSQL($sql, 4, 0);
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
            " . $class_item . "
        ";
        #echo '<p>' . $sql;
        $ret=$layerdb->execSQL($sql, 4, 0);
        if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
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
            $ranges[] = ($centers[$cIdx-1]+ $centers[$cIdx]) / 2;
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

  function LayerAnlegen(){
		global $supportedLanguages;
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if (trim($this->formvars['id'])!='' and $mapDB->id_exists('layer',$this->formvars['id'])) {
			$table_information = $mapDB->get_table_information($this->Stelle->database->dbName,'layer');
			$this->add_message('error', 'Die Id: ' . $this->formvars['id'] . ' existiert schon. Nächste freie Layer_ID ist ' . $table_information['AUTO_INCREMENT']);
		}
		else {
			$this->formvars['selected_layer_id'] = $mapDB->newLayer($this->formvars);

			if($this->formvars['connectiontype'] == 6 AND $this->formvars['pfad'] != ''){
				#---------- Speichern der Layerattribute -------------------
				$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
				$path = strip_pg_escape_string($this->formvars['pfad']);
				$all_layer_params = $mapDB->get_all_layer_params_default_values();					
			  $attributes = $mapDB->load_attributes($layerdb,	replace_params($path,	$all_layer_params));
				$mapDB->save_postgis_attributes($this->formvars['selected_layer_id'], $attributes, $this->formvars['maintable'], $this->formvars['schema']);
				$mapDB->delete_old_attributes($this->formvars['selected_layer_id'], $attributes);
				#---------- Speichern der Layerattribute -------------------
			}

			# Klassen übernehmen (aber als neue Klassen anlegen)
			$name = @array_values($this->formvars['name']);
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$name_[$language] = @array_values($this->formvars['name_'.$language]);
				}
			}
			$expression = @array_values($this->formvars['expression']);
			$order = @array_values($this->formvars['order']);
			$ID = @array_values($this->formvars['ID']);
			for($i = 0; $i < count($name); $i++){
				$attrib['name'] = $name[$i];
				foreach($supportedLanguages as $language){
					if($language != 'german'){
						$attrib['name_'.$language] = $name_[$language][$i];
					}
				}
				$attrib['layer_id'] = $this->formvars['selected_layer_id'];
				$attrib['expression'] = $expression[$i];
				$attrib['order'] = $order[$i];
				$class_id = $mapDB->new_Class($attrib);
				# Styles übernehmen (in u_styles2classes eintragen)
				$styles = $mapDB->read_Styles($ID[$i]);
				for($j = 0; $j < count($styles); $j++){
					$style_id = $mapDB->new_Style($styles[$j]);
					$mapDB->addStyle2Class($class_id, $style_id, $styles[$j]['drawingorder']);
				}
				# Labels übernehmen (in u_labels2classes eintragen)
				$labels = $mapDB->read_Label($ID[$i]);
				for($j = 0; $j < count($labels); $j++){
					$label_id = $mapDB->new_Label($labels[$j]);
					$mapDB->addLabel2Class($class_id, $label_id);
				}
			}
		}
  }

  function LayerAendern(){
		global $supportedLanguages;
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->user->rolle->readSettings();
    $mapDB->updateLayer($this->formvars);
    $old_layer_id = $this->formvars['selected_layer_id'];
    if($this->formvars['id'] != ''){
      $this->formvars['selected_layer_id'] = $this->formvars['id'];
    }

		if($this->formvars['connectiontype'] == 6){
			if($this->formvars['connection'] != ''){
				if($this->formvars['pfad'] != ''){
					#---------- Speichern der Layerattribute -------------------
			    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			    $layerdb->setClientEncoding();
			    $path = strip_pg_escape_string($this->formvars['pfad']);
					$all_layer_params = $mapDB->get_all_layer_params();					
			    $attributes = $mapDB->load_attributes($layerdb,	replace_params($path,	$all_layer_params));
			    $mapDB->save_postgis_attributes($this->formvars['selected_layer_id'], $attributes, $this->formvars['maintable'], $this->formvars['schema']);
			    #---------- Speichern der Layerattribute -------------------
				}
				if($this->formvars['pfad'] == '' OR $attributes != NULL){
					$mapDB->delete_old_attributes($this->formvars['selected_layer_id'], $attributes);
				}
			}
			else{
				showAlert('Keine connection angegeben.');
			}
		}
    # Stellenzuweisung
		$stellen = $this->Stellenzuweisung(
      array($this->formvars['selected_layer_id']),
      explode(', ', $this->formvars['selstellen'])
    );

    # Löschen der in der Selectbox entfernten Stellen
      $layerstellen = $mapDB->get_stellen_from_layer($this->formvars['selected_layer_id']);
      for($i = 0; $i < count($layerstellen['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($stellen); $j++){
          if($stellen[$j] == $layerstellen['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
          $deletestellen[] = $layerstellen['ID'][$i];
        }
      }
      if($deletestellen != 0){
        for($i = 0; $i < count($deletestellen); $i++){
          $stelle = new stelle($deletestellen[$i], $this->database);
          $stelle->deleteLayer(array($this->formvars['selected_layer_id']), $this->pgdatabase);
          $users = $stelle->getUser();
          for($j = 0; $j < count($users['ID']); $j++){
            $this->user->rolle->deleteLayer($users['ID'][$j], array($deletestellen[$i]), array($this->formvars['selected_layer_id']));
            $this->user->rolle->updateGroups($users['ID'][$j],$deletestellen[$i], $this->formvars['selected_layer_id']);
          }
        }
      }
    # /Löschen der in der Selectbox entfernten Stellen

		# Klassen
		$name = @array_values($this->formvars['name']);
		foreach($supportedLanguages as $language){
			if($language != 'german'){
				$name_[$language] = @array_values($this->formvars['name_'.$language]);
			}
		}
		$expression = @array_values($this->formvars['expression']);
		$text = @array_values($this->formvars['text']);
		$classification = @array_values($this->formvars['classification']);
		$legendgraphic = @array_values($this->formvars['legendgraphic']);
		$order = @array_values($this->formvars['order']);
		$legendorder = @array_values($this->formvars['legendorder']);
		$this->classes = $mapDB->read_Classes($old_layer_id);
		for($i = 0; $i < count($name); $i++) {
			$attrib['name'] = $name[$i];
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$attrib['name_'.$language] = $name_[$language][$i];
				}
			}
			$attrib['layer_id'] = $this->formvars['selected_layer_id'];
			$attrib['expression'] = addslashes($expression[$i]);
			$attrib['text'] = $text[$i];
			$attrib['classification'] = $classification[$i];
			$attrib['legendgraphic'] = $legendgraphic[$i];
			$attrib['order'] = $order[$i];
			$attrib['legendorder'] = ($legendorder[$i] == '' ? 'NULL' : $legendorder[$i]);
			$attrib['class_id'] = $this->classes[$i]['Class_ID'];
			$mapDB->update_Class($attrib);
		}
		$this->Layereditor();
	}

  /*
  * Weist Layer Stellen zu
  * @param array Array von layer_ids, die den Stellen zugewiesen werden sollen.
  * @param array Array von Stellen, denen die Layer zugewiesen werden sollen.
	* @param string (optional) Text der in used_layer im Attribut Filter verwendet werden soll.
  * @return void
  */
  function Stellenzuweisung($layer_ids, $stellen_ids, $filter = '') {
    for($i = 0; $i < count($stellen_ids); $i++) {
      $stelle = new stelle($stellen_ids[$i], $this->database);
      $stelle->addLayer($layer_ids,	0, $filter);
      $users = $stelle->getUser();
      for($j = 0; $j < count($users['ID']); $j++){
        $this->user->rolle->setGroups($users['ID'][$j], array($stellen_ids[$i]), $layer_ids, 0); # Hinzufügen der Layergruppen der selektierten Layer zur Rolle
        $this->user->rolle->setLayer($users['ID'][$j], array($stellen_ids[$i]), 0); # Hinzufügen der Layer zur Rolle
      }
			$stelle->updateLayerParams();
    }
    return $stellen_ids;
  }

	function LayerLoeschen(){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$mapDB->deleteLayer($this->formvars['selected_layer_id']);
		# auch die Klassen löschen
		$this->classes = $mapDB->read_Classes($this->formvars['selected_layer_id']);
		for($i = 0; $i < count($this->classes); $i++){
			$mapDB->delete_Class($this->classes[$i]['Class_ID']);
		}
		# layer_attributes löschen
		$mapDB->delete_layer_attributes($this->formvars['selected_layer_id']);
		$mapDB->delete_layer_attributes2stelle($this->formvars['selected_layer_id'], $this->Stelle->id);
		# Filter löschen
		$mapDB->delete_layer_filterattributes($this->formvars['selected_layer_id']);

		$layer[] = $this->formvars['selected_layer_id'];
		$stelle[] = $this->Stelle->id;
		$this->Stelle->deleteLayer($layer, $this->pgdatabase);
		$this->user->rolle->deleteLayer('', $stelle, $layer);
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
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if($this->formvars['order'] == ''){
			$this->formvars['order'] = 'Name';
		}
		$this->layerdaten = $mapDB->getall_Layer($this->formvars['order']);
		$this->titel='Layerdaten';
		$this->main='layerdaten.php';
		$this->output();
	}

  function GenerischeSuche_Suchen(){
		if($this->last_query != ''){
			$this->formvars['selected_layer_id'] = $this->last_query['layer_ids'][0];
		}
    $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		if($this->formvars['selected_layer_id'] > 0)$layerset=$this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		else $layerset=$this->user->rolle->getRollenlayer(-$this->formvars['selected_layer_id']);
    switch ($layerset[0]['connectiontype']) {
      case MS_POSTGIS : {
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
        $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
        $layerdb->setClientEncoding();
        #$path = $layerset[0]['pfad'];
				$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[0]['pfad']);
				$path = str_replace('$language', $this->user->rolle->language, $path);
				$path = replace_params($path, rolle::$layer_params);

				$privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
				$attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames'], false, true);
				$newpath = $this->Stelle->parse_path($layerdb, $path, $privileges, $attributes);

		    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		   	# $attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true); kann weg, weils weiter unten steht

    		# order by rausnehmen
		  	$orderbyposition = strrpos(strtolower($newpath), 'order by');
				$lastfromposition = strrpos(strtolower($newpath), 'from');
		  	if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
			  	$attributes['orderby'] = ' '.substr($newpath, $orderbyposition);
			  	$newpath = substr($newpath, 0, $orderbyposition);
		  	}

		  	# group by rausnehmen
				$groupbyposition = strpos(strtolower($newpath), 'group by');
				if($groupbyposition !== false){
					$attributes['groupby'] = ' '.substr($newpath, $groupbyposition);
					$newpath = substr($newpath, 0, $groupbyposition);
		  	}

        if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
          for($j = 0; $j < count($attributes['name']); $j++){
            $attributes['privileg'][$j] = '0';
            $attributes['privileg'][$attributes['name'][$j]] = '0';
          }
        }
        else{
          for($j = 0; $j < count($attributes['name']); $j++){
            $attributes['privileg'][$j] = $privileges[$attributes['name'][$j]];
            $attributes['privileg'][$attributes['name'][$j]] = $privileges[$attributes['name'][$j]];
          }
        }

				for($m = 0; $m <= $this->formvars['searchmask_count']; $m++){
					if($m > 0){				// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
						$prefix = $m.'_';
						$sql_where .= ' '.$this->formvars['boolean_operator_'.$m].' ';			// mit AND/OR verketten
					}
					else{
						$prefix = '';
						$sql_where .= ' AND (';		// eine äußere Klammer, dadurch die ORs darin eingeschlossen sind
					}
					$sql_where .= ' (1=1';			// klammern
					$value_like = '';
					$operator_like = '';
					for($i = 0; $i < count($attributes['name']); $i++){
						$value = $this->formvars[$prefix.'value_'.$attributes['name'][$i]];
						$operator = $this->formvars[$prefix.'operator_'.$attributes['name'][$i]];
						if($value != ''){
							if($operator == 'LIKE' OR $operator == 'NOT LIKE'){
								################  Autovervollständigungsfeld ########################################
								if($attributes['form_element_type'][$i] == 'Autovervollständigungsfeld' AND $attributes['options'][$i] != ''){
									$optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
									if(strpos($value, '%') === false)$value2 = '%'.$value.'%';else $value2 = $value;
									$sql = 'SELECT * FROM ('.$optionen[0].') as foo WHERE LOWER(CAST(output AS TEXT)) '.$operator.' LOWER(\''.$value2.'\')';
									$ret=$layerdb->execSQL($sql,4,0);
									if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
									while($rs = pg_fetch_assoc($ret[1])){
										$keys[] = $rs['value'];
									}
									$value_like = $value;					# Value sichern
									$operator_like = $operator;			# Operator sichern
									if($keys == NULL)$keys[0] = '####';		# Dummy-Wert, damit in der IN-Suche nichts gefunden wird
									$this->formvars[$prefix.'value_'.$attributes['name'][$i]] = implode('|', $keys);
									$this->formvars[$prefix.'operator_'.$attributes['name'][$i]] = 'IN';
									$i--;
									continue;		# dieses Attribut nochmal behandeln aber diesmal mit dem Operator IN und den gefundenen Schlüsseln der LIKE-Suche
								}
								#####################################################################################
								if(strpos($value, '%') === false)$value = '%'.$value.'%';
								$sql_where .= ' AND LOWER(CAST(query.'.$attributes['name'][$i].' AS TEXT)) '.$operator.' ';
								$sql_where.='LOWER(\''.$value.'\')';
							}
							else{
								if($operator == 'IN'){
									$parts = explode('|', $value);
									for($j = 0; $j < count($parts); $j++){
										if(substr($parts[$j], 0, 1) != '\''){$parts[$j] = '\''.$parts[$j];}
										if(substr($parts[$j], -1) != '\''){$parts[$j] = $parts[$j].'\'';}
									}
									$instring = implode(',', $parts);
									$sql_where .= ' AND LOWER(CAST(query.'.$attributes['name'][$i].' AS TEXT)) '.$operator.' ';
									$sql_where .= '('.strtolower($instring).')';
									if($value_like != ''){			# Parameter wieder auf die der LIKE-Suche setzen
										$this->formvars[$prefix.'operator_'.$attributes['name'][$i]] = $operator_like;
										$this->formvars[$prefix.'value_'.$attributes['name'][$i]] = $value_like;
										$value_like = '';
										$operator_like = '';
									}
								}
								elseif($operator != 'IS NULL' AND $operator != 'IS NOT NULL'){
									$sql_where .= ' AND query.'.$attributes['name'][$i].' '.$operator.' ';
									$sql_where.='\''.$value.'\'';
								}
							}
						}
						if($operator == 'IS NULL' OR $operator == 'IS NOT NULL'){
							if($attributes['type'][$i] == 'bpchar' OR $attributes['type'][$i] == 'varchar' OR $attributes['type'][$i] == 'text'){
								if($operator == 'IS NULL'){
									$sql_where .= ' AND (query.'.$attributes['name'][$i].' '.$operator.' OR query.'.$attributes['name'][$i].' = \'\') ';
								}
								else{
									$sql_where .= ' AND query.'.$attributes['name'][$i].' '.$operator.' AND query.'.$attributes['name'][$i].' != \'\' ';
								}
							}
							else{
								$sql_where .= ' AND query.'.$attributes['name'][$i].' '.$operator.' ';
							}
						}
						if($this->formvars[$prefix.'value2_'.$attributes['name'][$i]] != ''){
							$sql_where.=' AND \''.$this->formvars[$prefix.'value2_'.$attributes['name'][$i]].'\'';
						}
						# räumliche Einschränkung
						if($m == 0 AND $attributes['name'][$i] == $attributes['the_geom']){		// nur einmal machen, also nur bei $m == 0
							if($this->formvars['newpathwkt'] != ''){
								if(strpos(strtolower($this->formvars['newpathwkt']), 'polygon') !== false){
									# Suche im Suchpolygon
									if($this->formvars['within'] == 1)$sp_op = 'st_within'; else $sp_op = 'st_intersects';
									$spatial_sql_where =' AND '.$sp_op.'('.$attributes['the_geom'].', (st_transform(st_geomfromtext(\''.$this->formvars['newpathwkt'].'\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].')))';
								}
								if(strpos(strtolower($this->formvars['newpathwkt']), 'point') !== false){
									# Suche an Punktkoordinaten mit übergebener SRID
									$spatial_sql_where.=" AND st_within(st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$this->formvars['epsg_code']."), ".$layerset[0]['epsg_code']."), ".$attributes['the_geom'].")";
								}
							}
							# Suche nur im Stellen-Extent
							$spatial_sql_where.=' AND ('.$attributes['the_geom'].' && st_transform(st_geomfromtext(\'POLYGON(('.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->miny.', '.$this->Stelle->MaxGeorefExt->maxx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->maxy.', '.$this->Stelle->MaxGeorefExt->minx.' '.$this->Stelle->MaxGeorefExt->miny.'))\', '.$this->user->rolle->epsg_code.'), '.$layerset[0]['epsg_code'].') OR '.$attributes['the_geom'].' IS NULL)';
						}
					}
					$sql_where .= ')';		// Klammer von der boolschen Verkettung wieder zu
				}

				$sql_where .= ')'.$spatial_sql_where;		// äußere Klammer wieder zu und räumliche Einschränkung anhängen

        $distinctpos = strpos(strtolower($newpath), 'distinct');
        if($distinctpos !== false && $distinctpos < 10){
          $pfad = substr(trim($newpath), $distinctpos+8);
          $distinct = true;
        }
        else{
          $pfad = substr(trim($newpath), 7);
        }
				$geometrie_tabelle = $attributes['table_name'][$attributes['the_geom']];
        $j = 0;
        foreach($attributes['all_table_names'] as $tablename){
					if(($tablename == $layerset[0]['maintable'] OR $tablename == $geometrie_tabelle) AND $attributes['oids'][$j]){		# hat Haupttabelle oder Geometrietabelle oids?
            $pfad = $attributes['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
						if($this->formvars['operator_'.$tablename.'_oid'] == '')$this->formvars['operator_'.$tablename.'_oid'] = '=';
            if($this->formvars['value_'.$tablename.'_oid']){
              $sql_where .= ' AND '.$tablename.'_oid '.$this->formvars['operator_'.$tablename.'_oid'].' '.$this->formvars['value_'.$tablename.'_oid'];
            }
          }
          $j++;
        }

        # 2008-10-22 sr   Filter zur Where-Klausel hinzugefügt
        if($layerset[0]['Filter'] != ''){
        	$layerset[0]['Filter'] = str_replace('$userid', $this->user->id, $layerset[0]['Filter']);
          $sql_where .= " AND ".$layerset[0]['Filter'];
        }

        if($distinct == true){
          $pfad = 'DISTINCT '.$pfad;
        }

        # group by wieder einbauen
				if($attributes['groupby'] != ''){
					$pfad .= $attributes['groupby'];
					$j = 0;
					foreach($attributes['all_table_names'] as $tablename){
								if($tablename == $layerset[0]['maintable'] AND $attributes['oids'][$j]){		# hat Haupttabelle oids?
									$pfad .= ','.$tablename.'_oid ';
								}
								$j++;
					}
  			}
        $sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;

        # order by
        if($this->formvars['orderby'.$layerset[0]['Layer_ID']] != ''){									# Fall 1: im GLE soll nach einem Attribut sortiert werden
          $sql_order = ' ORDER BY '.$this->formvars['orderby'.$layerset[0]['Layer_ID']];
        }
        elseif($attributes['orderby'] != ''){										# Fall 2: der Layer hat im Pfad ein ORDER BY
        	$sql_order = $attributes['orderby'];
        }
        																																						# standardmäßig wird nach der oid sortiert
				$j = 0;
				foreach($attributes['all_table_names'] as $tablename){
					if($tablename == $layerset[0]['maintable'] AND $attributes['oids'][$j]){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
						if($sql_order == '')$sql_order = ' ORDER BY '.$layerset[0]['maintable'].'_oid ';
						else $sql_order .= ', '.$layerset[0]['maintable'].'_oid ';
					}
					$j++;
				}

				if($this->last_query != ''){
					$sql = $this->last_query[$layerset[0]['Layer_ID']]['sql'];
					if($this->formvars['orderby'.$layerset[0]['Layer_ID']] == '')$sql_order = $this->last_query[$layerset[0]['Layer_ID']]['orderby'];
					$this->formvars['anzahl'] = $this->last_query[$layerset[0]['Layer_ID']]['limit'];
					if($this->formvars['offset_'.$layerset[0]['Layer_ID']] == '')$this->formvars['offset_'.$layerset[0]['Layer_ID']] = $this->last_query[$layerset[0]['Layer_ID']]['offset'];
				}

        if($this->formvars['embedded_subformPK'] == '' AND $this->formvars['embedded'] == '' AND $this->formvars['embedded_dataPDF'] == ''){
        	if($this->formvars['anzahl'] == ''){
	          $this->formvars['anzahl'] = MAXQUERYROWS;
	        }
        	$sql_limit.=' LIMIT '.$this->formvars['anzahl'];
        	if($this->formvars['offset_'.$layerset[0]['Layer_ID']] != ''){
          	$sql_limit.=' OFFSET '.$this->formvars['offset_'.$layerset[0]['Layer_ID']];
        	}
        }

				$layerset[0]['sql'] = $sql;
				#echo "<p>Abfragestatement: ".$sql.$sql_order.$sql_limit;
        $ret=$layerdb->execSQL('SET enable_seqscan=off;'.$sql.$sql_order.$sql_limit,4, 0);
        if(!$ret[0]){
          while ($rs=pg_fetch_assoc($ret[1])) {
            $layerset[0]['shape'][]=$rs;
          }
					$num_rows = pg_num_rows($ret[1]);
					if($this->formvars['offset_'.$layerset[0]['Layer_ID']] == '' AND $num_rows < $this->formvars['anzahl'])$layerset[0]['count'] = $num_rows;
					else{
						# Anzahl der Datensätze abfragen
						$sql_count = "SELECT count(*) FROM (".$sql.") as foo";
						$ret=$layerdb->execSQL($sql_count,4, 0);
						if(!$ret[0]){
							$rs=pg_fetch_array($ret[1]);
							$layerset[0]['count'] = $rs[0];
						}
					}
        }
        # Hier nach der Abfrage der Sachdaten die weiteren Attributinformationen hinzufügen
        # Steht an dieser Stelle, weil die Auswahlmöglichkeiten von Auswahlfeldern abhängig sein können
        $attributes = $mapDB->add_attribute_values($attributes, $layerdb, $layerset[0]['shape'], true, $this->Stelle->id);

				if($layerset[0]['count'] != 0 AND $this->formvars['embedded_subformPK'] == '' AND $this->formvars['embedded'] == '' AND $this->formvars['embedded_dataPDF'] == ''){
					#if($this->formvars['go'] != 'neuer_Layer_Datensatz_speichern'){		// wenns nur die Anzeige des gerade angelegten Datensatzes ist, nicht als last_query speichern (wieder rausgenommen)
						# last_query speichern
						$this->user->rolle->delete_last_query();
						$this->user->rolle->save_last_query('Layer-Suche_Suchen', $this->formvars['selected_layer_id'], $sql, $sql_order, $this->formvars['anzahl'], $this->formvars['offset_'.$layerset[0]['Layer_ID']]);
					#}

					# Querymaps erzeugen
					if($layerset[0]['querymap'] == 1 AND $attributes['privileg'][$attributes['the_geom']] >= '0' AND ($layerset[0]['Datentyp'] == 1 OR $layerset[0]['Datentyp'] == 2)){
						$layerset[0]['attributes'] = $attributes;
						for($k = 0; $k < count($layerset[0]['shape']); $k++){
							$layerset[0]['querymaps'][$k] = $this->createQueryMap($layerset[0], $k);
						}
					}

					# Datendrucklayouts abfragen
					include_(CLASSPATH.'datendrucklayout.php');
					$ddl = new ddl($this->database);
					$layerset[0]['layouts'] = $ddl->load_layouts($this->Stelle->id, NULL, $layerset[0]['Layer_ID'], array(0,1));

					# wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
					if(is_array($this->formvars['attributenames'])){
						$attributenames = array_values($this->formvars['attributenames']);
						$values = array_values($this->formvars['values']);
					}
					for($i = 0; $i < count($attributenames); $i++){
						$this->layerset[0]['shape'][0][$attributenames[$i]] = $values[$i];
					}
				}
      }break;

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
        # Attributnamen ermitteln
        $wfs->describe_featuretype_request();
				$wfs->getTargetNamespace();
        $attributes = $wfs->get_attributes();
        # Filterstring erstellen
        for($i = 0; $i < count($attributes['name']); $i++){
          if($this->formvars['value_'.$attributes['name'][$i]] != '' OR $this->formvars['operator_'.$attributes['name'][$i]] == 'IS NULL' OR $this->formvars['operator_'.$attributes['name'][$i]] == 'IS NOT NULL'){
            $attributenames[] = $attributes['name'][$i];
            $operators[] = $this->formvars['operator_'.$attributes['name'][$i]];
            $values[] = $this->formvars['value_'.$attributes['name'][$i]];
          }
        }
        $filter = $wfs->create_filter($attributenames, $operators, $values);
        # Abfrage mit Filter absetzen
        if($this->formvars['anzahl'] == ''){
          $this->formvars['anzahl'] = MAXQUERYROWS;
        }
        $wfs->get_feature_request(NULL, $filter, $this->formvars['anzahl']);
        $features = $wfs->extract_features();
        for($j = 0; $j < count($features); $j++){
          for($k = 0; $k < count($attributes['name']); $k++){
            $layerset[0]['shape'][$j][$attributes['name'][$k]] = $features[$j]['value'][$attributes['name'][$k]];
            $attributes['privileg'][$k] = 0;
          }
          $layerset[0]['shape'][$j]['wfs_geom'] = $features[$j]['geom'];
        }
      }break;
    }   # Ende switch connectiontype

		$layerset[0]['attributes'] = $attributes;
		$this->qlayerset[0]=$layerset[0];

    $i = 0;
    $this->search = true;
		if($this->formvars['embedded_dataPDF']){}		# wenn diese Suche für ein eingebettetes Drucklayout ist und Treffer da sind -> nichts weiter machen
    elseif($this->formvars['embedded_subformPK'] != ''){
      header('Content-type: text/html; charset=UTF-8');
      include(LAYOUTPATH.'snippets/embedded_subformPK.php');			# listenförmige Ausgabe mit Links untereinander
    }
    elseif($this->formvars['embedded'] != ''){
    	ob_end_clean();
      header('Content-type: text/html; charset=UTF-8');
      include(LAYOUTPATH.'snippets/sachdatenanzeige_embedded.php');		# ein aufgeklappter Link
    }
    else{
      $this->main = 'sachdatenanzeige.php';
      if($this->formvars['printversion'] != ''){
        $this->mime_type = 'printversion';
      }
			if($this->formvars['printversion'] == '' AND $this->user->rolle->querymode == 1){		# bei aktivierter Datenabfrage in extra Fenster --> Laden der Karte und zoom auf Treffer (das Zeichnen der Karte passiert in einem separaten Ajax-Request aus dem Overlay heraus)
				$this->loadMap('DataBase');
				if(count($this->qlayerset[$i]['shape']) > 0 AND ($layerset[0]['shape'][0][$attributes['the_geom']] != '' OR $layerset[0]['shape'][0]['wfs_geom'] != '')){			# wenn was gefunden wurde und der Layer Geometrie hat, auf Datensätze zoomen
					$this->zoomed = true;
					switch ($layerset[0]['connectiontype']) {
						case MS_POSTGIS : {
							for($k = 0; $k < count($this->qlayerset[$i]['shape']); $k++){
								$oids[] = $this->qlayerset[$i]['shape'][$k][$geometrie_tabelle.'_oid'];
							}
							$rect = $mapDB->zoomToDatasets($oids, $geometrie_tabelle, $attributes['the_geom'], 10, $layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
							$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
							if (MAPSERVERVERSION > 600) {
								$this->map_scaledenom = $this->map->scaledenom;
							}
							else {
								$this->map_scaledenom = $this->map->scale;
							}
						}break;
						case MS_WFS : {
							$this->formvars['wkt'] = $layerset[0]['shape'][0]['wfs_geom'];
							$this->formvars['epsg'] = $layerset[0]['epsg_code'];
							$this->zoom2wkt();
						}break;
					}
				}
				$this->user->rolle->newtime = $this->user->rolle->last_time_id;
				$this->saveMap('');
				if($this->formvars['mime_type'] != 'overlay_html'){		// bei Suche aus normaler Suchmaske (nicht aus Overlay) heraus --> Zeichnen der Karte und Darstellung der Sachdaten im Overlay
					$this->drawMap();
					$this->main = 'map.php';
					$this->overlaymain = 'sachdatenanzeige.php';
				}
			}
			$this->output();
    }
  }

	function get_quicksearch_attributes(){
		if($this->formvars['layer_id']){
			$this->formvars['anzahl'] = MAXQUERYROWS;
			$this->layerset=$this->user->rolle->getLayer($this->formvars['layer_id']);
			switch ($this->layerset[0]['connectiontype']){
        case MS_POSTGIS : {
          $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
					$layerdb = $mapdb->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
					$layerdb->setClientEncoding();
					$path = $mapdb->getPath($this->formvars['layer_id']);
					$privileges = $this->Stelle->get_attributes_privileges($this->formvars['layer_id']);
					$newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
					$this->attributes = $mapdb->read_layer_attributes($this->formvars['layer_id'], $layerdb, $privileges['attributenames']);
					# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true, $this->Stelle->id);
        }break;

        case MS_WFS : {
					include_(CLASSPATH.'wfs.php');
          $url = $this->layerset[0]['connection'];
					$version = $this->layerset[0]['wms_server_version'];
					$epsg = $this->layerset[0]['epsg_code'];
          $typename = $this->layerset[0]['wms_name'];
					$namespace = substr($typename, 0, strpos($typename, ':'));
					$username = $this->layerset[0]['wms_auth_username'];
					$password = $this->layerset[0]['wms_auth_password'];
          $wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
          $wfs->describe_featuretype_request();
          $this->attributes = $wfs->get_attributes();
        }break;
      }
			?><table><tr><?
			for($i = 0; $i < count($this->attributes['name']); $i++){
				if($this->layerset[0]['connectiontype'] == MS_WFS OR $this->attributes['quicksearch'][$i] == 1){
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
									<select class="select"
									<?
										if($this->attributes['req_by'][$i] != ''){
											echo 'onchange="update_require_attribute_(\''.$this->attributes['req_by'][$i].'\','.$this->formvars['layer_id'].', new Array(\''.implode($this->attributes['name'], "','").'\'));" ';
										}
										else echo 'onchange="schnellsuche();" ';
									?>
										id="value_<? echo $this->attributes['name'][$i]; ?>" name="value_<? echo $this->attributes['name'][$i]; ?>"><?echo "\n"; ?>
											<option value="">-- <? echo $this->strChoose; ?> --</option><? echo "\n";
											if(is_array($this->attributes['enum_value'][$i][0])){
												$this->attributes['enum_value'][$i] = $this->attributes['enum_value'][$i][0];
												$this->attributes['enum_output'][$i] = $this->attributes['enum_output'][$i][0];
											}
										for($o = 0; $o < count($this->attributes['enum_value'][$i]); $o++){
											?>
											<option <? if($this->formvars['value_'.$this->attributes['name'][$i]] == $this->attributes['enum_value'][$i][$o]){ echo 'selected';} ?> value="<? echo $this->attributes['enum_value'][$i][$o]; ?>"><? echo $this->attributes['enum_output'][$i][$o]; ?></option><? echo "\n";
										} ?>
										</select>
										<input type="hidden" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="=">
										<?
								}break;

								default : { ?>
                  <input size="24" onkeydown="keydown(event)" id="attribute_<? echo $i; ?>" name="value_<? echo $this->attributes['name'][$i]; ?>" type="text" value="">
									<? if($this->layerset[0]['connectiontype'] == MS_WFS) { ?>
										<input type="hidden" id="operator_attribute_<? echo $i; ?>" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="=">
									<? }else{ ?>
										<input type="hidden" id="operator_attribute_<? echo $i; ?>" name="operator_<? echo $this->attributes['name'][$i]; ?>" value="LIKE">
									<? }
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
          $layerdb->setClientEncoding();
          $path = $mapdb->getPath($this->formvars['selected_layer_id']);
          $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
          $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
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

  function GenerischeSuche(){
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel=$this->formvars['titel'];
    $this->main='generic_search.php';
    $this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, 'import');
		$this->layergruppen['ID'] = array_values(array_unique($this->layerdaten['Gruppe']));
		$this->layergruppen = $mapdb->get_Groups($this->layergruppen);		# Gruppen mit Pfaden versehen

    # wenn Gruppe ausgewählt, Einschränkung auf Layer dieser Gruppe
    if($this->formvars['selected_group_id'] AND $this->formvars['selected_layer_id'] == ''){
    	$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, $this->formvars['selected_group_id']);
    }
    if($this->formvars['selected_layer_id']){
    	if($this->formvars['layer_id'] == '')$this->formvars['layer_id'] = $this->formvars['selected_layer_id'];
    	$data = $mapdb->getData($this->formvars['layer_id']);
	    $data_explosion = explode(' ', $data);
	    $this->formvars['columnname'] = $data_explosion[0];
    	if($this->formvars['map_flag'] != ''){
	    	################# Map ###############################################
				$saved_scale = $this->reduce_mapwidth(10);
				$this->loadMap('DataBase');
				if($this->formvars['CMD']=='' AND $saved_scale != NULL)$this->scaleMap($saved_scale);		# nur, wenn nicht navigiert wurde
		    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
		    # Geometrie-Übernahme-Layer:
		    # Spaltenname und from-where abfragen
		    $select = $fromwhere = $this->mapDB->getSelectFromData($data);
		    # order by rausnehmen
				$this->formvars['orderby'] = '';
		  	$orderbyposition = strrpos(strtolower($select), 'order by');
				$lastfromposition = strrpos(strtolower($select), 'from');
				if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
					$fromwhere = substr($select, 0, $orderbyposition);
					$this->formvars['orderby'] = ' '.substr($select, $orderbyposition);
				}
				$this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
		    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
		      $this->formvars['fromwhere'] .= ' where (1=1)';
		    }

				if($this->formvars['newpath'] == '' AND $this->formvars['layer_id'] < 0){	# Suchergebnislayer sofort selektieren				
					$rollenlayer = $this->mapDB->read_RollenLayer(-$this->formvars['layer_id']);
					if($rollenlayer[0]['Typ'] == 'search'){
						$layerdb = $mapdb->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
						include_once (CLASSPATH.'polygoneditor.php');
						$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
						$tablename = '('.$fromwhere.') as foo';
						$this->polygon = $polygoneditor->getpolygon(NULL, $tablename, $this->formvars['columnname'], $this->map->extent);
						if($this->polygon['wktgeom'] != ''){
							$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
							$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
							$this->formvars['newpath'] = $this->polygon['svggeom'];
							$this->formvars['firstpoly'] = 'true';
						}
					}
				}

		    if($this->formvars['CMD']== 'Full_Extent' OR $this->formvars['CMD'] == 'recentre' OR $this->formvars['CMD'] == 'zoomin' OR $this->formvars['CMD'] == 'zoomout' OR $this->formvars['CMD'] == 'previous' OR $this->formvars['CMD'] == 'next') {
		      $this->navMap($this->formvars['CMD']);
		    }
		    $this->saveMap('');
		    $currenttime=date('Y-m-d H:i:s',time());
		    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		    $this->drawMap();
	    	########################################################################
    	}
			if (empty($this->formvars['anzahl'])) {
				$this->formvars['anzahl'] = MAXQUERYROWS;
			}

			if($this->formvars['selected_layer_id'] > 0)
				$this->layerset=$this->user->rolle->getLayer($this->formvars['selected_layer_id']);
			else
				$this->layerset=$this->user->rolle->getRollenlayer(-$this->formvars['selected_layer_id']);

			$this->formvars['selected_group_id'] = $this->layerset[0]['Gruppe']; 

			$this->layerdaten = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, $this->formvars['selected_group_id']);	

      switch ($this->layerset[0]['connectiontype']) {
        case MS_POSTGIS : {
          $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
          $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
          $layerdb->setClientEncoding();					
          $path = $mapdb->getPath($this->formvars['selected_layer_id']);
          $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
          $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
          $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);

					# Speichern einer neuen Suchabfrage
					if($this->formvars['go_plus'] == 'Suchabfrage_speichern'){
						$this->user->rolle->save_search($this->attributes, $this->formvars);
						$this->formvars['searches'] = $this->formvars['search_name'];
					}
					if($this->formvars['searchmask_count'] == '')$this->formvars['searchmask_count'] = 0;		// darf erst nach dem speichern passieren
					# Löschen einer Suchabfrage
					if($this->formvars['go_plus'] == 'Suchabfrage_löschen'){
						$this->user->rolle->delete_search($this->formvars['searches'], $this->formvars['selected_layer_id']);
						$this->formvars['searches'] = '';
					}
					# die Namen aller gespeicherten Suchabfragen dieser Rolle zu diesem Layer laden
					$this->searchset=$this->user->rolle->getsearches($this->formvars['selected_layer_id']);
					# die ausgewählte Suchabfrage laden

					for($m = 0; $m <= $this->formvars['searchmask_count']; $m++){
						if($m > 0){				// es ist nicht die erste Suchmaske, sondern eine weitere hinzugefügte
							$prefix = $m.'_';
						}
						else{
							$prefix = '';
						}
						if($this->formvars['searches'] != ''){		# die Suchparameter einer gespeicherten Suchabfrage laden
							$this->selected_search = $this->user->rolle->getsearch($this->formvars['selected_layer_id'], $this->formvars['searches']);
							$this->formvars['searchmask_count'] = $this->selected_search[0]['searchmask_number'];
							# alle Suchparameter leeren
							for($i = 0; $i < count($this->attributes['name']); $i++){
								$this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]] = '';
								$this->formvars[$prefix.'value_'.$this->attributes['name'][$i]] = '';
								$this->formvars[$prefix.'value2_'.$this->attributes['name'][$i]] = '';
							}
							# die gespeicherten Suchparameter setzen
							for($i = 0; $i < count($this->selected_search); $i++){
								if($this->selected_search[$i]['searchmask_number'] == $m){
									$this->formvars['searchmask_operator'][$m] = $this->selected_search[$i]['searchmask_operator'];
									$this->formvars[$prefix.'operator_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['operator'];
									$this->formvars[$prefix.'value_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value1'];
									$this->formvars[$prefix.'value2_'.$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value2'];
									$this->qlayerset['shape'][0][$this->selected_search[$i]['attribute']] = $this->selected_search[$i]['value1'];
								}
							}
						}
						else{
							for($i = 0; $i < count($this->attributes['name']); $i++){
								$this->qlayerset['shape'][0][$this->attributes['name'][$i]] = $this->formvars[$prefix.'value_'.$this->attributes['name'][$i]];
								$this->attributes['operator'][$i] = $this->formvars[$prefix.'operator_'.$this->attributes['name'][$i]];
							}
						}
						# für jede Suchmaske ein eigenes attributes-Array erzeugen, da z.B. die Auswahllisten ja anders sein können
						$this->{'attributes'.$m} = $mapdb->add_attribute_values($this->attributes, $layerdb, $this->qlayerset['shape'], true, $this->Stelle->id);
					}
        }break;

        case MS_WFS : {
					include_(CLASSPATH.'wfs.php');
          $url = $this->layerset[0]['connection'];
					$version = $this->layerset[0]['wms_server_version'];
					$epsg = $this->layerset[0]['epsg_code'];
          $typename = $this->layerset[0]['wms_name'];
					$namespace = substr($typename, 0, strpos($typename, ':'));
					$username = $this->layerset[0]['wms_auth_username'];
					$password = $this->layerset[0]['wms_auth_password'];
					$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
          $wfs->describe_featuretype_request();
          $this->attributes = $wfs->get_attributes();
					for($i = 0; $i < count($this->attributes['name']); $i++){
	          $this->qlayerset['shape'][0][$this->attributes['name'][$i]] = $this->formvars['value_'.$this->attributes['name'][$i]];
	        }
        }break;
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
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
        $oid = $element[3];
				if($oid != ''){
					$sql = 'INSERT INTO zwischenablage VALUES ('.$this->user->id.', '.$this->Stelle->id.', '.$this->formvars['chosen_layer_id'].', '.$oid.') ON DUPLICATE KEY UPDATE layer_id=layer_id';
					#echo $sql.'<br>';
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
		if(count($oids) > 0)$sql.= ' AND oid IN ('.implode(', ', $oids).')';
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql,4, 1);
		if(count($oids) == 0){
			$this->Zwischenablage();
		}
	}

	function Zwischenablage(){
		$sql = "SELECT count(z.layer_id) as count, z.layer_id, layer.Name FROM zwischenablage as z, layer WHERE z.layer_id = layer.Layer_ID AND user_id = ".$this->user->id." AND stelle_id = ".$this->Stelle->id." GROUP BY z.layer_id, Name";
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql,4, 1);
    $this->num_rows=mysql_num_rows($ret[1]);
		while($rs=mysql_fetch_array($ret[1])){
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

	function gemerkte_Datensaetze_anzeigen($layer_id){
		$sql = "SELECT oid FROM zwischenablage WHERE user_id = ".$this->user->id." AND stelle_id = ".$this->Stelle->id." AND layer_id = ".$layer_id;
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql,4, 1);
		while($rs=mysql_fetch_array($ret[1])){
			$oids[] = $rs['oid'];
		}
		$layerset = $this->user->rolle->getLayer($layer_id);
		$this->formvars['selected_layer_id'] = $layer_id;
		$this->formvars['value_'.$layerset[0]['maintable'].'_oid'] = '('.implode(',', $oids).')';
		$this->formvars['operator_'.$layerset[0]['maintable'].'_oid'] = 'IN';
		$this->formvars['anzahl'] = 1000;
		$this->GenerischeSuche_Suchen();
	}

	function dokument_loeschen(){
		$_FILES[$this->formvars['document_attributename']]['name'] = 'delete';
		$this->sachdaten_speichern();
	}

  function layer_Datensaetze_loeschen($output = true) {
		$layers = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
		$layer = $layers[0];
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);

		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		for($i = 0; $i < count($checkbox_names); $i++){
			if($this->formvars[$checkbox_names[$i]] == 'on') {
				$element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid

				# Before Delete trigger
				if (!empty($layer['trigger_function'])) {
					$this->exec_trigger_function('BEFORE', 'DELETE', $layer, $element[3]);
					$sql = "
						SELECT
							oid, *
						FROM
							{$element[2]}
						WHERE
							oid = {$element[3]}
					";
					#echo '<br>sql before delete: ' . $sql; #pk
					$ret = $layerdb->execSQL($sql, 4, 1);
					$old_dataset = ($ret[0] == 0 ? pg_fetch_assoc($ret[1]) : array());
				}

				if (!empty($layer['trigger_function'])) {
					# Rufe Instead Delete trigger auf
					#echo '<br>Rufe Instead Delete trigger auf.';
					$trigger_result = $this->exec_trigger_function('INSTEAD', 'DELETE', $layer, $element[3]);
				}

				if ($trigger_result['executed']) {
					#echo '<br>Delete Trigger Funktion wurde ausgeführt.';
					# Instead Triggerfunktion wurde ausgeführt, übergebe Erfolgsmeldung
					$result = array($trigger_result['message']);
					$this->success = $trigger_result['success'];
				}
				else {
					#echo '<br>Delete Trigger Funktion wurde nicht ausgeführt.';
					# Instead Triggerfuktion wurde nicht ausgeführt
					# Delete the object regularly in database
					$sql = "DELETE FROM ".$element[2]." WHERE oid = ".$element[3];
					$oids[] = $element[3];
					#echo $sql.'<br>';
					$ret = $layerdb->execSQL($sql,4, 1);
					if(!$ret[0]){
						$result = pg_fetch_row($ret[1]);
						if (pg_affected_rows($ret[1]) == 0){
							$ret[0] = 1;
							$this->success = false;
						}
					}
				}
				
				if ($this->success) {
					# After delete trigger
					if (!empty($layer['trigger_function'])) {
						$this->exec_trigger_function('AFTER', 'DELETE', $layer, '', $old_dataset);
					}
				}
			}
		}
		# Dokumente auch löschen
		$form_fields = explode('|', $this->formvars['form_field_names']);
		for($i = 0; $i < count($form_fields); $i++){
			if($form_fields[$i] != ''){
				$element = explode(';', $form_fields[$i]);
				if($element[4] == 'Dokument' AND in_array($element[3], $oids)){
					$this->deleteDokument($this->formvars[str_replace(';Dokument;', ';Dokument_alt;', $form_fields[$i])]);
				}
			}
		}

		if ($output) {
			if($this->formvars['embedded'] == '') {
				if($this->success == false) {
					$this->add_message('error', 'Löschen fehlgeschlagen.<br>' . $result[0]);
				}
				else {
					$this->add_message('notice', 'Löschen erfolgreich');
				}
				$this->last_query = $this->user->rolle->get_last_query();
				if($this->formvars['search']){ # man kam von der Suche -> nochmal suchen
					$this->GenerischeSuche_Suchen();
				}
				else{ # man kam aus einer Sachdatenabfrage -> nochmal abfragen
					$this->last_query_requested = true;
					$this->queryMap();
				}
			}
			else{
				header('Content-type: text/html; charset=UTF-8');
				$attributenames[0] = $this->formvars['targetattribute'];
				$attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, $attributenames);
				switch ($attributes['form_element_type'][0]){
					case 'SubFormEmbeddedPK' : {
						$this->formvars['embedded_subformPK'] = true;
						echo '~';
						$this->GenerischeSuche_Suchen();
					}break;
				}
			}
		}

		return $this->success;
	}

	function neuer_Layer_Datensatz_speichern(){
		$_files = $_FILES;
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$layerdb->setClientEncoding();
		$attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
		$layer_epsg = $layerset[0]['epsg_code'];
		$client_epsg = $this->user->rolle->epsg_code;
		$form_fields = explode('|', $this->formvars['form_field_names']);

		for($i = 0; $i < count($form_fields); $i++){
			if($form_fields[$i] != ''){
				$element = explode(';', $form_fields[$i]);
				$tablename[$element[2]]['tablename'] = $element[2];
				$tablename[$element[2]]['attributname'][] = $attributenames[] = $element[1];
				$attributevalues[] = $this->formvars[$form_fields[$i]];
				if($this->formvars['embedded'] != '')$formfieldstring .= '&'.$form_fields[$i].'='.$this->formvars[$form_fields[$i]];
				$tablename[$element[2]]['type'][] = $element[4];
				$tablename[$element[2]]['datatype'][] = $element[6];
				$tablename[$element[2]]['formfield'][] = $form_fields[$i];
				# Dokumente sammeln
				if($element[4] == 'Dokument'){
					if($_files[$form_fields[$i]]['name']){
						$document_attributes[$i] = $element[1];
					}
				}
			}
		}

		# Dokumente speichern
		if(count($document_attributes) > 0){
			foreach($document_attributes as $i => $attribute_name){
				# Dateiname erzeugen
				$name_array=explode('.',basename($_files[$form_fields[$i]]['name']));
				$datei_name=$name_array[0];
				$datei_erweiterung=array_pop($name_array);
				$doc_path = $mapdb->getDocument_Path($layerset[0]['document_path'], $attributes['options'][$attribute_name], $attributenames, $attributevalues, $layerdb);
				$nachDatei = $doc_path.'.'.$datei_erweiterung;
				# Bild in das Datenverzeichnis kopieren
				if(move_uploaded_file($_files[$form_fields[$i]]['tmp_name'],$nachDatei)){
					$this->formvars[$form_fields[$i]] = $nachDatei."&original_name=".$_files[$form_fields[$i]]['name'];
				}
				else{
					echo '<br>Datei: '.$_files[$form_fields[$i]]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
				}
			}
		}

		if ($this->formvars['geomtype'] == 'GEOMETRY') {
			$geomtypes = array('POINT', 'LINESTRING', 'POLYGON');
			$this->formvars['geomtype'] = $geomtypes[$this->formvars['Datentyp']];
		}

		if($this->formvars['geomtype'] == 'POLYGON' OR $this->formvars['geomtype'] == 'MULTIPOLYGON') {
			if($this->formvars['newpathwkt'] == '' AND $this->formvars['newpath'] != ''){   # wenn keine WKT-Geoemtrie da ist, muss die WKT-Geometrie aus dem SVG erzeugt werden
				include_(CLASSPATH.'spatial_processor.php');
				$spatial_pro = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
				$this->formvars['newpathwkt'] = $spatial_pro->composeMultipolygonWKTStringFromSVGPath($this->formvars['newpath']);
			}
			if($this->formvars['newpathwkt'] != ''){
				include_(CLASSPATH.'polygoneditor.php');
				$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
				$ret = $polygoneditor->pruefeEingabedaten($this->formvars['newpathwkt']);
				if ($ret[0]) { # fehlerhafte eingabedaten
					$this->Meldung1=$ret[1];
					$this->neuer_Layer_Datensatz();
					return;
				}
			}
		}
		elseif($this->formvars['geomtype'] == 'LINESTRING' OR $this->formvars['geomtype'] == 'MULTILINESTRING'){
			if($this->formvars['newpathwkt'] == '' AND $this->formvars['newpath'] != ''){   # wenn keine WKT-Geoemtrie da ist, muss die WKT-Geometrie aus dem SVG erzeugt werden
				include_(CLASSPATH.'spatial_processor.php');
				$spatial_pro = new spatial_processor($this->user->rolle, $this->database, $this->pgdatabase);
				$this->formvars['newpathwkt'] = $spatial_pro->composeMultilineWKTStringFromSVGPath($this->formvars['newpath']);
			}
			if($this->formvars['newpathwkt'] != ''){
				include_(CLASSPATH.'lineeditor.php');
				$lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
				# eingeabewerte pruefen:
				$ret = $lineeditor->pruefeEingabedaten($this->formvars['newpathwkt']);
				if ($ret[0]) { # fehlerhafte eingabedaten
					$this->Meldung1=$ret[1];
					$this->neuer_Layer_Datensatz();
					return;
				}
			}
		}
		$this->success = true;
		foreach($tablename as $table){
			$execute = false;
			if($table['tablename'] != '' AND $table['tablename'] == $layerset[0]['maintable']){		# nur Attribute aus der Haupttabelle werden gespeichert
				if(!$layerset[0]['maintable_is_view'])$sql = "LOCK TABLE ".$table['tablename']." IN SHARE ROW EXCLUSIVE MODE;";
				$sql.= "INSERT INTO ".$table['tablename']." (";
				for($i = 0; $i < count($table['attributname']); $i++){
					if(($table['type'][$i] != 'Text_not_saveable' AND $table['type'][$i] != 'Auswahlfeld_not_saveable' AND $table['type'][$i] != 'SubFormPK' AND $table['type'][$i] != 'SubFormFK' AND $this->formvars[$table['formfield'][$i]] != '')
					OR $table['type'][$i] == 'Checkbox' OR $table['type'][$i] == 'Time' OR $table['type'][$i] == 'User' OR $table['type'][$i] == 'UserID' OR $table['type'][$i] == 'Stelle' OR $table['type'][$i] == 'StelleID' OR $table['type'][$i] == 'Geometrie'){
						if($table['type'][$i] == 'Geometrie'){
							if($this->formvars['geomtype'] == 'POINT' AND $this->formvars['loc_x'] != ''){
								$sql .= $table['attributname'][$i].", ";
								$execute = true;
							}
							elseif($this->formvars['newpathwkt'] != ''){
								$sql .= $table['attributname'][$i].", ";
								$execute = true;
							}
						}
						else{
							$sql .= $table['attributname'][$i].", ";
							$execute = true;
						}
					}
				}
				$sql = substr($sql, 0, strlen($sql)-2);
				$sql.= ") VALUES (";
				for($i = 0; $i < count($table['attributname']); $i++){
					if($table['type'][$i] == 'Time'){                       # Typ "Time"
						$sql.= "(now())::timestamp(0), ";
					}
					elseif($table['type'][$i] == 'User'){                       # Typ "User"
						$sql.= "'".$this->user->Vorname." ".$this->user->Name."', ";
					}
					elseif($table['type'][$i] == 'UserID'){                       # Typ "UserID"
						$sql.= "'".$this->user->id."', ";
					}
					elseif($table['type'][$i] == 'Stelle'){                       # Typ "Stelle"
						$sql.= "'".$this->Stelle->Bezeichnung."', ";
					}
					elseif($table['type'][$i] == 'StelleID'){                       # Typ "StelleID"
						$sql.= "'".$this->Stelle->id."', ";
					}
					elseif($table['type'][$i] == 'Dokument' AND $this->formvars[$table['formfield'][$i]] != '') {
						$sql.= "'".$this->formvars[$table['formfield'][$i]]."', ";
						$this->formvars[$table['formfield'][$i]] = ''; # leeren, falls weiter_erfassen angehakt
					}
					elseif (
						$table['type'][$i] != 'Text_not_saveable' AND
						$table['type'][$i] != 'Auswahlfeld_not_saveable' AND
						$table['type'][$i] != 'SubFormPK' AND
						$table['type'][$i] != 'SubFormFK' AND
						($this->formvars[$table['formfield'][$i]] != '' OR $table['type'][$i] == 'Checkbox')
					) {
						if ($table['type'][$i] == 'Zahl') {
							# bei Zahlen den Punkt (Tausendertrenner) entfernen
							$this->formvars[$table['formfield'][$i]] = removeTausenderTrenner($this->formvars[$table['formfield'][$i]]); # bei Zahlen den Punkt (Tausendertrenner) entfernen
						}
						if ($table['type'][$i] == 'Checkbox' AND $this->formvars[$table['formfield'][$i]] == '') {
							$this->formvars[$table['formfield'][$i]] = 'f';
						}
						if (substr($table['datatype'][$i], 0, 1) == '_' OR is_numeric($table['datatype'][$i])){
							# bei einem custom Datentyp oder Array das JSON in PG-struct umwandeln
							$this->formvars[$table['formfield'][$i]] = JSON_to_PG(json_decode($this->formvars[$table['formfield'][$i]]));
						}
						$sql .= "'" . $this->formvars[$table['formfield'][$i]] . "', "; # Typ "normal"
					}
					elseif($table['type'][$i] == 'Geometrie') {
						if($this->formvars['geomtype'] == 'POINT') {
							if($this->formvars['loc_x'] != '') {
								if($this->formvars['dimension'] == 3) {
									$sql .= "st_transform(st_geomfromtext('POINT(".$this->formvars['loc_x']." ".$this->formvars['loc_y']." 0)', ".$client_epsg."), ".$layer_epsg."), ";
								}
								else{
									$sql .= "st_transform(st_geomfromtext('POINT(".$this->formvars['loc_x']." ".$this->formvars['loc_y'].")', ".$client_epsg."), ".$layer_epsg."), ";
								}
							}
						}
						elseif($this->formvars['newpathwkt'] != ''){
							$sql .= "st_transform(st_multi(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$client_epsg.")), ".$layer_epsg."), ";
						}
					}
				}
				$sql = substr($sql, 0, strlen($sql)-2);
				$sql.= ")";

				if ($execute == true) {
					# Before Insert trigger
					if (!empty($layerset[0]['trigger_function'])) {
						$this->exec_trigger_function('BEFORE', 'INSERT', $layerset[0]);
					}

					$this->debug->write("<p>file:kvwmap class:neuer_Layer_Datensatz_speichern :",4);

					$ret = $layerdb->execSQL($sql, 4, 1, true);

					if ($ret['success']) {

						$result = pg_fetch_row($ret['query']);

						if (pg_affected_rows($ret['query']) > 0) {
							# dataset was created
							if (is_array($result) and (!array_key_exists(1, $result) OR $result[1] != 'error')) {
								$this->add_message('warning', 'Eintrag erfolgreich.<br>' . $result[0]);
							}
							else {
								$this->add_message('notice', 'Eintrag erfolgreich!');
							}

							$last_oid = pg_last_oid($ret['query']);
							if($this->formvars['embedded'] == '')$this->formvars['value_' . $table['tablename'] . '_oid'] = $last_oid;

							# After Insert trigger
							if (!empty($layerset[0]['trigger_function'])) {
								$this->exec_trigger_function('AFTER', 'INSERT', $layerset[0], $last_oid);
							}
						}
						else {
							# dataset was not created
							$this->add_message('error', 'Eintrag fehlgeschlagen.<br>' . $result[0]);
						}
					}
					else {
						# query not successfull set query error message
						$this->success = false;
						$this->add_message($ret['type'], $ret['msg']);
					}
				}
			}
		}

		if ($this->formvars['embedded'] != '') {    # wenn es ein neuer Datensatz aus einem embedded-Formular ist, muss das entsprechende Attribut des Hauptformulars aktualisiert werden
			header('Content-type: text/html; charset=UTF-8');
			$attributename[0] = $this->formvars['targetattribute'];
			$attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $layerdb, $attributename);

			switch ($attributes['form_element_type'][0]){
				case 'Auswahlfeld' : {
					if (strpos($attributes['options'][0], '<requires>') !== false) {
						# wenn <requires> verwendet wird, muss komplett neu geladen werden
						echo "~~currentform.go.value='get_last_query';overlay_submit(currentform, false);";
					}
					else { # andernfalls wird nur das Auswahlfeld ausgetauscht und die Option gleich selektiert
						list($sql) = explode(';', $attributes['options'][0]);
						$sql = str_replace(' from ', ',oid from ', strtolower($sql));    # auch die oid abfragen
						$re=$layerdb->execSQL($sql,4,0);
						if ($re[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; var_dump($layerdb); return 0; }
						while($rs = pg_fetch_array($re[1])){
							$html .= '<option ';
							if($rs['oid'] == $last_oid){$html .= 'selected ';}
							$html .= 'value="'.$rs['value'].'">'.$rs['output'].'</option>';
						}
						echo '~'.$html;
					}
        } break;

        case 'SubFormEmbeddedPK' : {
          $this->formvars['embedded_subformPK'] = true;
          echo '~';
          $this->GenerischeSuche_Suchen();
					echo '~';
					if($this->formvars['weiter_erfassen'] == 1){
						echo 'href_save = document.getElementById("new_'.$this->formvars['targetobject'].'").href;';
						echo 'document.getElementById("new_'.$this->formvars['targetobject'].'").href = document.getElementById("new_'.$this->formvars['targetobject'].'").href.replace("go=neuer_Layer_Datensatz", "go=neuer_Layer_Datensatz&weiter_erfassen=1'.$formfieldstring.'");';
						echo 'document.getElementById("new_'.$this->formvars['targetobject'].'").click();';
						echo 'document.getElementById("new_'.$this->formvars['targetobject'].'").href = href_save;';
					}
					$this->output_messages('without_script_tags');
        } break;
      }

			if($this->formvars['reload']){			# in diesem Fall wird die komplette Seite neu geladen
				echo '~~';
				echo "currentform.go.value='get_last_query';
							overlay_submit(currentform, false);";
			}

    }
    else {
      if ($ret['success'] == false) {
        $this->neuer_Layer_Datensatz();
      }
      else {
        if($this->formvars['weiter_erfassen'] == 1){
        	$this->formvars['firstpoly'] = '';
        	$this->formvars['firstline'] = '';
        	$this->formvars['secondpoly'] = '';
        	$this->formvars['pathwkt'] = '';
        	$this->formvars['newpathwkt'] = '';
        	$this->formvars['newpath'] = '';
        	$this->formvars['last_doing'] = '';
        	$this->neuer_Layer_Datensatz();
        }
        else{
        	$this->GenerischeSuche_Suchen();
        }
      }
    }
  }

  function neuer_Layer_Datensatz(){
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='neuen Datensatz einfügen';
    $this->main='new_layer_data.php';
    if($this->formvars['chosen_layer_id']){			# für neuen Datensatz verwenden -> von der Sachdatenanzeige übergebene Formvars
    	$this->formvars['CMD'] = '';
    	$this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];
    }
    if($this->formvars['selected_layer_id'] == ''){
			$this->layerdaten = $this->Stelle->getqueryablePostgisLayers(1, NULL, true);		# wenn kein Layer vorausgewählt, Subform-Layer ausschliessen
		}
		else{
			$this->layerdaten = $this->Stelle->getqueryablePostgisLayers(1, NULL, false);		# ansonsten nicht
      $layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
      if($layerset[0]['privileg'] > 0){   # überprüfen, ob Recht zum Erstellen von neuen Datensätzen gesetzt ist
        $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
        $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
        $layerdb->setClientEncoding();
        $privileges = $this->Stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
        $layerset[0]['attributes'] = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames'], false, true);
		    $form_fields = explode('|', $this->formvars['form_field_names']);
				for($i = 0; $i < count($form_fields); $i++){
					if($form_fields[$i] != ''){
						$element = explode(';', $form_fields[$i]);
						$formElementType = $layerset[0]['attributes']['form_element_type'][$layerset[0]['attributes']['indizes'][$element[1]]];
						if($formElementType == 'Zahl'){
							$this->formvars[$form_fields[$i]] = removeTausenderTrenner($this->formvars[$form_fields[$i]]);	# beim Typ Zahl Tausendertrenner entfernen
						}
					}
				}				
        ######### für neuen Datensatz verwenden -> von der Sachdatenanzeige übergebene Formvars #######
	      if($this->formvars['chosen_layer_id']){
		    	$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
		      for($i = 0; $i < count($checkbox_names); $i++){
		        if($this->formvars[$checkbox_names[$i]] == 'on'){
		        	$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
		          $oid = $element[3];
		        }
		      }
		      for($i = 0; $i < count($form_fields); $i++){
			      if($form_fields[$i] != ''){
			        $element = explode(';', $form_fields[$i]);
							$formElementType = $layerset[0]['attributes']['form_element_type'][$layerset[0]['attributes']['indizes'][$element[1]]];
			        if($element[3] == $oid
								 AND !in_array($layerset[0]['attributes']['constraints'][$element[1]],  array('PRIMARY KEY', 'UNIQUE'))  # Primärschlüssel werden nicht mitübergeben
								 AND !in_array($formElementType, array('Time', 'User', 'UserID', 'Stelle', 'StelleID')) # und automatisch generierte Typen auch nicht
							){
				        $element[3] = '';
				        $this->formvars[implode(';', $element)] = $this->formvars[$form_fields[$i]];
			        }
			      }
		      }
		    }
		    ######### von einer Sachdatenanzeige übergebene Formvars #######

        if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = '0';
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = '0';
          }
        }
        else{
          for($j = 0; $j < count($layerset[0]['attributes']['name']); $j++){
            $layerset[0]['attributes']['privileg'][$j] = $privileges[$layerset[0]['attributes']['name'][$j]];
            $layerset[0]['attributes']['privileg'][$layerset[0]['attributes']['name'][$j]] = $privileges[$layerset[0]['attributes']['name'][$j]];
            $layerset[0]['shape'][0][$layerset[0]['attributes']['name'][$j]] = $this->formvars[$layerset[0]['Layer_ID'].';'.$layerset[0]['attributes']['real_name'][$layerset[0]['attributes']['name'][$j]].';'.$layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['name'][$j]].';;'.$layerset[0]['attributes']['form_element_type'][$j].';'.$layerset[0]['attributes']['nullable'][$j].';'.$layerset[0]['attributes']['type'][$j]];
						if($layerset[0]['shape'][0][$layerset[0]['attributes']['name'][$j]] == '' AND $layerset[0]['attributes']['default'][$j] != '')$layerset[0]['shape'][0][$layerset[0]['attributes']['name'][$j]] = $layerset[0]['attributes']['default'][$j];  // Wenn Defaultwert da und Feld leer, Defaultwert setzen
          }
        }
        $this->formvars['layer_columnname'] = $layerset[0]['attributes']['the_geom'];
        $this->formvars['layer_tablename'] = $layerset[0]['attributes']['table_name'][$layerset[0]['attributes']['the_geom']];
        $this->qlayerset[0]=$layerset[0];

        # wenn Attributname/Wert-Paare übergeben wurden, diese im Formular einsetzen
        if(is_array($this->formvars['attributenames'])){
          $attributenames = array_values($this->formvars['attributenames']);
          $values = array_values($this->formvars['values']);
        }
        for($i = 0; $i < count($attributenames); $i++){
          $this->qlayerset[0]['shape'][0][$attributenames[$i]] = $values[$i];
        }

        # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
				$this->qlayerset[0]['attributes'] = $mapDB->add_attribute_values($this->qlayerset[0]['attributes'], $layerdb, $this->qlayerset[0]['shape'], true, $this->Stelle->id);
        $this->new_entry = true;

        $this->geomtype = $this->qlayerset[0]['attributes']['geomtype'][$this->qlayerset[0]['attributes']['the_geom']];
        if($this->geomtype != ''){
					$saved_scale = $this->reduce_mapwidth(150);
					$oldscale=round($this->map_scaledenom);
					if($oldscale != $this->formvars['nScale'] OR $this->formvars['neuladen'] OR $this->formvars['CMD'] != ''){
						$this->neuLaden();
						$this->user->rolle->saveDrawmode($this->formvars['always_draw']);
					}
					else {
						$this->loadMap('DataBase');
					}
					if($saved_scale != NULL)$this->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
					# zoomToMaxLayerExtent
					if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
					# evtl. Zoom auf "Mutter-Layer"
					if($this->formvars['layer_id'] != '' AND $this->formvars['oid'] != '' AND $this->formvars['tablename'] != '' AND $this->formvars['columnname'] != ''){			# das sind die Sachen vom "Mutter"-Layer
						$parentlayerset = $this->user->rolle->getLayer($this->formvars['layer_id']);
        		$layerdb2 = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
        		$rect = $this->mapDB->zoomToDatasets(array($this->formvars['oid']), $this->formvars['tablename'], $this->formvars['columnname'], 10, $layerdb2, $parentlayerset[0]['epsg_code'], $this->user->rolle->epsg_code);
						if($rect->minx != ''){
							$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);		# Zoom auf den "Mutter"-Datensatz
							if (MAPSERVERVERSION > 600) {
								$this->map_scaledenom = $this->map->scaledenom;
							}
							else {
								$this->map_scaledenom = $this->map->scale;
							}
						}
        	}
          if($this->geomtype == 'POLYGON' OR $this->geomtype == 'MULTIPOLYGON' OR $this->geomtype == 'GEOMETRY' OR $this->geomtype == 'LINESTRING' OR $this->geomtype == 'MULTILINESTRING'){
            #-----Polygoneditor und Linieneditor---#
            $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
            # Spaltenname und from-where abfragen
            if($this->formvars['layer_id'] == ''){
              $this->formvars['layer_id'] = $this->formvars['selected_layer_id'];
            }
            $data = $mapdb->getData($this->formvars['layer_id']);
            $space_explosion = explode(' ', $data);
            $this->formvars['columnname'] = $space_explosion[0];
            $select = $fromwhere = $mapdb->getSelectFromData($data);
						# order by rausnehmen
						$this->formvars['orderby'] = '';
						$orderbyposition = strrpos(strtolower($select), 'order by');
						$lastfromposition = strrpos(strtolower($select), 'from');
						if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
							$fromwhere = substr($select, 0, $orderbyposition);
							$this->formvars['orderby'] = ' '.substr($select, $orderbyposition);
						}
            $this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
            if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
              $this->formvars['fromwhere'] .= ' where (1=1)';
            }
						
						if($this->formvars['newpath'] == '' AND $this->formvars['layer_id'] < 0){	# Suchergebnislayer sofort selektieren
							$rollenlayer = $this->mapDB->read_RollenLayer(-$this->formvars['layer_id']);
							if($rollenlayer[0]['Typ'] == 'search'){
								$layerdb1 = $mapdb->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
								include_once (CLASSPATH.'polygoneditor.php');
								$polygoneditor = new polygoneditor($layerdb1, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
								$tablename = '('.$fromwhere.') as foo';
								$this->polygon = $polygoneditor->getpolygon(NULL, $tablename, $this->formvars['columnname'], $this->map->extent);
								if($this->polygon['wktgeom'] != ''){
									$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
									$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
									$this->formvars['newpath'] = $this->polygon['svggeom'];
									$this->formvars['firstpoly'] = 'true';
								}
							}
						}
						
						if($this->formvars['chosen_layer_id']){			# für neuen Datensatz verwenden -> Geometrie abfragen
							if($this->geomtype == 'POLYGON' OR $this->geomtype == 'MULTIPOLYGON' OR $this->geomtype == 'GEOMETRY'){		# Polygonlayer
								include_once (CLASSPATH.'polygoneditor.php');
								$polygoneditor = new polygoneditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
								$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
								$this->polygon = $polygoneditor->getpolygon($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], $this->map->extent);
								if($this->polygon['wktgeom'] != ''){
									$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
									$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
									$this->formvars['newpath'] = $this->polygon['svggeom'];
									$this->formvars['firstpoly'] = 'true';
									if($this->formvars['zoom'] != 'false'){
										$rect = $polygoneditor->zoomTopolygon($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], NULL);
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
							else{			# Linienlayer
								include_once (CLASSPATH.'lineeditor.php');
								$lineeditor = new lineeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
								$this->geomload = true;			# Geometrie wird das erste Mal geladen, deshalb nicht in den Weiterzeichnenmodus gehen
								$this->lines = $lineeditor->getlines($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
								if($this->lines['wktgeom'] != ''){
									$this->formvars['newpathwkt'] = $this->lines['wktgeom'];
									$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
									$this->formvars['newpath'] = str_replace('-', '', $this->lines['svggeom']);
									$this->formvars['newpath'] = str_replace('L ', '', $this->formvars['newpath']);		# neuere Postgis-Versionen haben ein L mit drin
									$this->formvars['firstline'] = 'true';
									if($this->formvars['zoom'] != 'false'){
										$rect = $lineeditor->zoomToLine($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname'], 10);
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
            #-----Polygoneditor und Linieneditor---#
          }
          elseif($this->geomtype == 'POINT'){
            #-----Pointeditor-----#
						if($this->formvars['chosen_layer_id']){			# für neuen Datensatz verwenden -> Geometrie abfragen
							include_once (CLASSPATH.'pointeditor.php');
							$pointeditor = new pointeditor($layerdb, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
							$this->point = $pointeditor->getpoint($oid, $this->formvars['layer_tablename'], $this->formvars['layer_columnname']);
							if($this->point['pointx'] != ''){
								$this->formvars['loc_x']=$this->point['pointx'];
								$this->formvars['loc_y']=$this->point['pointy'];
								$rect = ms_newRectObj();
								$rect->minx = $this->point['pointx']-100;
								$rect->maxx = $this->point['pointx']+100;
								$rect->miny = $this->point['pointy']-100;
								$rect->maxy = $this->point['pointy']+100;
								$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
								if (MAPSERVERVERSION > 600) {
									$this->map_scaledenom = $this->map->scaledenom;
								}
								else {
									$this->map_scaledenom = $this->map->scale;
								}
							}
						}
            #-----Pointeditor-----#
          }
          $this->saveMap('');
          if($this->formvars['CMD'] != 'previous' AND $this->formvars['CMD'] != 'next'){
			    	$currenttime=date('Y-m-d H:i:s',time());
			    	$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
			    }
          $this->drawMap();
        }

      }
      else{
        $this->Fehler = 'Das Erstellen von neuen Datensätzen ist für diesen Layer in dieser Stelle nicht erlaubt.';
      }
    }
    if($this->formvars['embedded'] != ''){
    	ob_end_clean();
      header('Content-type: text/html; charset=UTF-8');
      include(LAYOUTPATH.'snippets/new_layer_data_embedded.php');
    }
    else{
      $this->output();
    }
  }

	function sachdaten_druck_editor(){
		global $admin_stellen;
		include_once(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database, $this);
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    if(in_array($this->Stelle->id, $admin_stellen)){										# eine Admin-Stelle darf alle Layer und Stellen sehen
			$this->layerdaten = $mapdb->get_postgis_layers('Name');
			$this->stellendaten=$this->user->getStellen('Bezeichnung');
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
      $layerdb->setClientEncoding();
      $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
			# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
			$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, NULL, true, $this->Stelle->id);
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
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
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
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_loeschen(){
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
    $this->ddl=$ddl;
    $this->ddl->delete_layout($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_add2stelle(){
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
    $this->ddl=$ddl;
    $this->ddl->add_layout2stelle($this->formvars['aktivesLayout'], $this->formvars['stelle']);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Freitexthinzufuegen(){
		$_files = $_FILES;
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
    $this->ddl->addfreetext($this->formvars);
		$this->scrolldown = true;
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_Freitextloeschen(){
		$_files = $_FILES;
		include_(CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database);
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->ddl=$ddl;
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    $this->ddl->update_layout($this->formvars, $this->attributes, $_files);
    $this->ddl->removefreetext($this->formvars);
		$this->sachdaten_druck_editor();
	}

	function sachdaten_druck_editor_preview($selectedlayout, $pdfobject = NULL, $offsetx = NULL, $offsety = NULL){
		include_once (CLASSPATH.'datendrucklayout.php');
		$ddl=new ddl($this->database, $this);
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['selected_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $attributes = $mapDB->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true, $this->Stelle->id);
    # Testdaten erzeugen
		if($selectedlayout['type'] != 0)$count = 2;else $count = 1;		# nur beim Untereinandertyp oder eingebettet-Typ mehrere Datensätze erzeugen
    for($i = 0; $i < $count; $i++){
	    for($j = 0; $j < count($attributes['name']); $j++){
	    	if($attributes['type'][$j] != 'geometry' ){
	    		if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
	    		$result[$i][$attributes['name'][$j]] = $attributes['alias'][$j];
	    	}
	    }
    }
    $output = $ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['selected_layer_id'], $selectedlayout, NULL, $result, $this->Stelle, $this->user, true);
		if($pdfobject == NULL){		# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF erzeugen
			$pdf_file = $output;
			# in jpg umwandeln
			$currenttime = date('Y-m-d_H_i_s',time());
			exec(IMAGEMAGICKPATH.'convert '.$pdf_file.'[0] -resize 595x1000 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg');
			#echo IMAGEMAGICKPATH.'convert '.$pdf_file.'[0] -resize 595x1000 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
			if(!file_exists(IMAGEPATH.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg')){
				return TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'-0.jpg';
			}
			else{
				return TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
			}
		}
		else{
			return $output;  # das ist der letzte y-Wert, um nachfolgende Elemente darunter zu setzen
		}
	}

	function generischer_sachdaten_druck(){
		include_(CLASSPATH.'datendrucklayout.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen

		$orderbyposition = strrpos(strtolower($newpath), 'order by');
		$lastfromposition = strrpos(strtolower($newpath), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
    for($i = 0; $i < count($checkbox_names); $i++){
      if($this->formvars[$checkbox_names[$i]] == 'on'){
        $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
        $sql = $newpath." AND ".$element[1].".oid = ".$element[3];
        $oids[] = $element[3];
        #echo $sql.'<br><br>';
        $this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :",4);
        $ret = $layerdb->execSQL($sql,4, 1);
        if (!$ret[0]) {
          while ($rs=pg_fetch_array($ret[1])) {
            $result[] = $rs;
          }
        }
      }
    }
		# Attribute laden
		$attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, $result, true, $this->Stelle->id);
		$this->attributes = $attributes;
    # Layouts abfragen
    $this->ddl->layouts = $this->ddl->load_layouts($this->Stelle->id, NULL, $this->formvars['chosen_layer_id'], array(0,1));
    if(count($this->ddl->layouts) == 1)$this->formvars['aktivesLayout'] = $this->ddl->layouts[0]['id'];
    # aktives Layout abfragen
    if($this->formvars['aktivesLayout'] != ''){
    	$this->ddl->selectedlayout = $this->ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, array(0,1));
	    # PDF erzeugen
	    $pdf_file = $this->ddl->createDataPDF(NULL, NULL, NULL, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $this->ddl->selectedlayout[0], $oids, $result, $this->Stelle, $this->user);
	    # in jpg umwandeln
	    $currenttime = date('Y-m-d_H_i_s',time());
	    exec(IMAGEMAGICKPATH.'convert '.$pdf_file.'[0] -resize 595x1000 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg');
	    #echo IMAGEMAGICKPATH.'convert '.$pdf_file.'[0] -resize 595x1000 '.dirname($pdf_file).'/'.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
	    if(!file_exists(IMAGEPATH.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg')){
	    	$this->previewfile = TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'-0.jpg';
	    }
	    else{
	    	$this->previewfile = TEMPPATH_REL.basename($pdf_file, ".pdf").'-'.$currenttime.'.jpg';
	    }
    }
    $this->main='generischer_sachdaten_druck.php';
    $this->titel='Sachdaten-Druck';
    $this->output();
	}

	function generischer_sachdaten_druck_drucken($pdfobject = NULL, $offsetx = NULL, $offsety = NULL){
		include_(CLASSPATH.'datendrucklayout.php');
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$ddl = new ddl($this->database, $this);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
    $layerdb->setClientEncoding();
    $path = $mapDB->getPath($this->formvars['chosen_layer_id']);
    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen
  	$orderbyposition = strrpos(strtolower($newpath), 'order by');
		$lastfromposition = strrpos(strtolower($newpath), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
		if($this->qlayerset[0]['shape'] != null){
			$result = $this->qlayerset[0]['shape'];
		}
		else{
			for($i = 0; $i < count($checkbox_names); $i++){
				if($this->formvars[$checkbox_names[$i]] == 'on'){
					$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
					$sql = $newpath." AND ".$element[1].".oid = ".$element[3];
					$oids[] = $element[3];
					#echo $sql.'<br><br>';
					$this->debug->write("<p>file:kvwmap class:generischer_sachdaten_druck :",4);
					$ret = $layerdb->execSQL($sql,4, 1);
					if (!$ret[0]) {
						while ($rs=pg_fetch_array($ret[1])) {
							$result[] = $rs;
						}
					}
				}
			}
		}
		# Attribute laden
		$attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, $result, true, $this->Stelle->id);
		$this->attributes = $attributes;
    # aktives Layout abfragen
    if($this->formvars['aktivesLayout'] != ''){
    	$ddl->selectedlayout = $ddl->load_layouts(NULL, $this->formvars['aktivesLayout'], NULL, NULL);
	    # PDF erzeugen
	    $output = $ddl->createDataPDF($pdfobject, $offsetx, $offsety, $layerdb, $layerset, $attributes, $this->formvars['chosen_layer_id'], $ddl->selectedlayout[0], $oids, $result, $this->Stelle, $this->user);
    }
		if($pdfobject == NULL){			# nur wenn kein PDF-Objekt aus einem übergeordneten Layer übergeben wurde, PDF anzeigen
			$this->outputfile = basename($output);
			$this->mime_type='pdf';
			$this->output();
		}
		else{
			return $output;			# das ist der letzte y-Wert, um nachfolgende Elemente darunter zu setzen
		}
	}

	function generisches_sachdaten_diagramm($width, $datei = NULL){
		$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		$layerset = $this->user->rolle->getLayer($this->formvars['chosen_layer_id']);
    $layerdb = $mapDB->getlayerdatabase($this->formvars['chosen_layer_id'], $this->Stelle->pgdbhost);
		$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[0]['pfad']);
		$path = str_replace('$language', $this->user->rolle->language, $path);
		$path = replace_params($path, rolle::$layer_params);

    $privileges = $this->Stelle->get_attributes_privileges($this->formvars['chosen_layer_id']);
    $newpath = $this->Stelle->parse_path($layerdb, $path, $privileges);
    # order by rausnehmen
  	$orderbyposition = strrpos(strtolower($newpath), 'order by');
		$lastfromposition = strrpos(strtolower($newpath), 'from');
		if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
	  	$newpath = substr($newpath, 0, $orderbyposition);
  	}
    $attributes = $mapDB->read_layer_attributes($this->formvars['chosen_layer_id'], $layerdb, $privileges['attributenames']);
    $maximum = 0;
    $minimum = 0;
    $maxlabelwidth = 0;
    # weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)
		$attributes = $mapDB->add_attribute_values($attributes, $layerdb, NULL, true, $this->Stelle->id);
		$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
    # Daten abfragen
    $sql = $newpath;
    if($this->formvars['all'] != 'true'){
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($this->formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
	        $oids .= $element[3].', ';
	      }
	    }
	    $sql .= " AND ".$element[1].".oid IN (".$oids.'0)';
    }
    if($this->formvars['orderby'.$this->formvars['chosen_layer_id']] != ''){
    	$sql .= ' ORDER BY '.$this->formvars['orderby'.$this->formvars['chosen_layer_id']];
    }
    #echo $sql.'<br><br>';
    $this->debug->write("<p>file:kvwmap class:generisches_sachdaten_diagramm :",4);
    $ret = $layerdb->execSQL($sql,4, 1);
    if (!$ret[0]) {
      while ($rs=pg_fetch_array($ret[1])) {
        $result[] = $rs;
        if($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] > $maximum){
        	$maximum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        }
        if($rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]] < $minimum){
        	$minimum = $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        }
        $summe += $rs[$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
        $maxlabelbox = imagettfbbox(36, 0, dirname(FONTSET).'/arial.ttf', $rs[$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]]);
        if($maxlabelwidth < $maxlabelbox[2] - $maxlabelbox[0]){
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
    for($i = 0; $i < count($result_colors); $i++){
    	$piecolors[] = Array($result_colors[$i]['red'], $result_colors[$i]['green'], $result_colors[$i]['blue']);
    }

    switch($this->formvars['charttype_'.$this->formvars['chosen_layer_id']]){
    	case 'bar' : {
     	  $image = imagecreatetruecolor(2380, 60*count($result)+170);
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
		    #imagefill($image, 0, 0, $backGroundColor);	# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2380, 60*count($result)+170, $backGroundColor);
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]]) $value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
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
		    #imagefill($image, 0, 0, $backgroundColor);		# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2380, 15*count($result)+230, $backgroundColor);
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]])$value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
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
		    #imagefill($image, 0, 0, $backGroundColor);	# geht bei FGS wohl nicht
			  imagefilledrectangle($image, 0, 0, 2000, 2000, $backGroundColor);
			  #Layername als Überschrift
			  $labelbox = imagettfbbox(50, 0, dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $labelwidth = $labelbox[2] - $labelbox[0];
			  imagettftext($image, 50, 0, 1000-$labelwidth/2, $y, $chartColors['black'], dirname(FONTSET).'/arial_bold.ttf', $layerset[0]['Name']);
			  $y = $y + 120;
		    $label = $this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]])$label = $attributes['alias'][$this->formvars['chartlabel_'.$this->formvars['chosen_layer_id']]];
				$value = $this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']];
		    if($attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]]) $value = $attributes['alias'][$this->formvars['chartvalue_'.$this->formvars['chosen_layer_id']]];
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

  function uko_import(){
		$this->titel='UKO-Import';
    $this->main='uko_import.php';
		include_(CLASSPATH.'data_import_export.php');
    $this->data_import_export = new data_import_export();
		$this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
  }

  function uko_import_importieren(){
    $this->titel='UKO-Import';
    $this->main='uko_import.php';
		include_(CLASSPATH.'data_import_export.php');
    $this->data_import_export = new data_import_export();
		$this->data_import_export->get_ukotable_srid($this->pgdatabase);
    $oids = $this->data_import_export->uko_importieren($this->formvars, $this->user->Name, $this->user->id, $this->pgdatabase);
		if($this->data_import_export->success == true){
			$this->main='map.php';
			$this->loadMap('DataBase');
			$rect = $this->mapDB->zoomToDatasets($oids, 'uko_polygon', 'the_geom', 20, $this->pgdatabase, $this->data_import_export->uko_srid, $this->user->rolle->epsg_code);
			$this->map->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
	    if (MAPSERVERVERSION > 600) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->drawMap();
			$this->saveMap('');
		}
    $this->output();
  }

	function gpx_import(){
    $this->titel='GPX-Import';
    $this->main='gpx_import.php';
    $this->output();
  }

  function gpx_import_importieren(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'GPX', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

	function dxf_import(){
    $this->titel='DXF-Import';
    $this->main='dxf_import.php';
    $this->output();
  }

  function dxf_import_importieren(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'DXF', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
  }

	function ovl_import(){
    $this->titel='OVL-Import';
    $this->main='ovl_import.php';
    $this->output();
  }

  function ovl_import_importieren(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'OVL', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
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
    $this->drawMap();
    $this->tif->create_tif($this->img['hauptkarte']);
    $this->tif->create_tfw();
    $this->titel='TIF-Export';
    $this->main='tif_export.php';
    $this->output();
  }
	
	function create_geojson_rollenlayer(){
    $this->main='create_geojson_rollenlayer.php';
    $this->output();
	}
	
	function create_geojson_rollenlayer_load(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'GeoJSON', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
	}	

	function create_shp_rollenlayer(){
		$this->titel='Shape-Datei Anzeigen';
    $this->main='create_shape_rollenlayer.php';
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
	}

	function create_shp_rollenlayer_load(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'Shape', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
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
		$this->data_import_export->pointlist = $this->data_import_export->load_custom_pointlist($this->formvars);
    $this->output();
	}

	function create_point_rollenlayer_import(){
		include_(CLASSPATH.'data_import_export.php');
		$this->data_import_export = new data_import_export();
		$layer_id = $this->data_import_export->create_import_rollenlayer($this->formvars, 'point', $this->Stelle, $this->user, $this->database, $this->pgdatabase);
		$this->loadMap('DataBase');
		$this->zoomToMaxLayerExtent($layer_id);
		$this->user->rolle->newtime = $this->user->rolle->last_time_id;
    $this->drawMap();
    $this->saveMap('');
    $this->output();
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
    $this->data_import_export->shp_import($this->formvars);
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
    $this->result = $this->data_import_export->geojson_import($this->pgdatabase, $this->formvars['schema_name'], $this->formvars['table_name']);
    $this->output();
  }
	
	function daten_import(){	
		$this->main='data_import.php';
		$this->output();
	}

  function daten_export() {
		include_once (CLASSPATH.'data_import_export.php');
    if ($this->formvars['chosen_layer_id'] != '') $this->formvars['selected_layer_id'] = $this->formvars['chosen_layer_id'];		# aus der Sachdatenanzeige des GLE
    $this->main = 'data_export.php';
    $saved_scale = $this->reduce_mapwidth(10);
		$this->loadMap('DataBase');
		if($saved_scale != NULL)$this->scaleMap($saved_scale);		# nur beim ersten Aufruf den Extent so anpassen, dass der alte Maßstab wieder da ist
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
		$this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
    $this->data_import_export = new data_import_export();
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if ($this->formvars['layer_id']) {
	    # Geometrie-Übernahme-Layer:
	    # Spaltenname und from-where abfragen
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
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
			$this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
			if($this->formvars['newpath'] == '' AND $this->formvars['layer_id'] < 0){	# Suchergebnislayer sofort selektieren			
				$rollenlayer = $this->mapDB->read_RollenLayer(-$this->formvars['layer_id']);
				if($rollenlayer[0]['Typ'] == 'search'){
					$layerdb1 = $this->mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
					include_once (CLASSPATH.'polygoneditor.php');
					$polygoneditor = new polygoneditor($layerdb1, $layerset[0]['epsg_code'], $this->user->rolle->epsg_code);
					$tablename = '('.$fromwhere.') as foo';
					$this->polygon = $polygoneditor->getpolygon(NULL, $tablename, $this->formvars['columnname'], $this->map->extent);
					if($this->polygon['wktgeom'] != ''){
						$this->formvars['newpathwkt'] = $this->polygon['wktgeom'];
						$this->formvars['pathwkt'] = $this->formvars['newpathwkt'];
						$this->formvars['newpath'] = $this->polygon['svggeom'];
						$this->formvars['firstpoly'] = 'true';
					}
				}
			}
		}
		###################### über Checkboxen aus der Sachdatenanzeige des GLE ausgewählt ###############
		if ($this->formvars['all'] == '') {
			$anzahl = 0;
			$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
			# Daten abfragen
			$element = explode(';', $checkbox_names[0]);   #  check;table_alias;table;oid
			$where = " WHERE ".$element[2]."_oid IN (";
			for($i = 0; $i < count($checkbox_names); $i++){
				if($this->formvars[$checkbox_names[$i]] == 'on'){
					$element = explode(';', $checkbox_names[$i]);   #  check;table_alias;table;oid
					$where = $where."'".$element[3]."',";
					$anzahl++;
				}
			}
			$where .= "0)";
			if($anzahl > 0){
				$this->formvars['sql_'.$this->formvars['selected_layer_id']] = $where.$orderby;
				$this->formvars['anzahl'] = $anzahl;
			}
		}
		####################################################################################################
    if ($this->formvars['CMD'] == 'Full_Extent' OR $this->formvars['CMD'] == 'recentre' OR $this->formvars['CMD'] == 'zoomin' OR $this->formvars['CMD'] == 'zoomout' OR $this->formvars['CMD'] == 'previous' OR $this->formvars['CMD'] == 'next') {
      $this->navMap($this->formvars['CMD']);
    }
    else{
      $this->formvars['load'] = true;
    }
    $this->data_import_export->export($this->formvars, $this->Stelle, $this->user, $this->mapDB);
		if ($this->formvars['epsg'] == '') $this->formvars['epsg'] = $this->data_import_export->layerset[0]['epsg_code'];		// originäres System
    $this->saveMap('');
    $currenttime=date('Y-m-d H:i:s',time());
    $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
    $this->drawMap();
    $this->output();
  }

  function daten_export_exportieren() {
		include_(CLASSPATH . 'data_import_export.php');
    $this->data_import_export = new data_import_export();
    $this->formvars['filename'] = $this->data_import_export->export_exportieren($this->formvars, $this->Stelle, $this->user);
    $this->daten_export();
  }

	function Attributeditor(){
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$this->titel='Attribut-Editor';
		$this->main='attribut_editor.php';
		$this->layerdaten = $mapdb->get_postgis_layers('Name');
		$this->datatypes = $mapdb->getall_Datatypes('name');
		if($this->formvars['selected_layer_id']){
			$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL, true);
		}
		if($this->formvars['selected_datatype_id']){
			$this->attributes = $mapdb->read_datatype_attributes($this->formvars['selected_datatype_id'], NULL, NULL, true);
		}
		$this->output();
	}

	function Attributeditor_speichern(){
		switch (true) {
			case (!empty($this->formvars['selected_layer_id']) && empty($this->formvars['selected_datatype_id'])) :
				$this->Layerattribute_speichern();
				break;
			case (empty($this->formvars['selected_layer_id']) && !empty($this->formvars['selected_datatype_id'])) :
				$this->Datentypattribute_speichern();
				break;
			default :
				$this->Attributeditor();
		}
	}

	function Layerattribute_speichern() {
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$path = $mapdb->getPath($this->formvars['selected_layer_id']);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
		$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL, true);
		$mapdb->save_layer_attributes($this->attributes, $this->database, $this->formvars);
		$this->Attributeditor();
	}

	function Datentypattribute_speichern() {
		$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
		$path = $mapdb->getPath($this->formvars['selected_datatype_id']);
		$this->attributes = $mapdb->read_datatype_attributes($this->formvars['selected_datatype_id'], NULL, NULL, true);
		$mapdb->save_datatype_attributes($this->attributes, $this->database, $this->formvars);
		$this->Attributeditor();
	}

  function layer_attributes_privileges(){
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Layer-Rechteverwaltung';
    $this->main='attribut_privileges_form.php';
		$this->layerdaten = $mapdb->get_postgis_layers('Name');
    if($this->formvars['selected_layer_id'] != ''){
    	$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    	$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    	$this->stellen = $mapdb->get_stellen_from_layer($this->formvars['selected_layer_id']);
    	$this->layer[0] = $mapdb->get_Layer($this->formvars['selected_layer_id']);
    }
    $this->output();
  }

  function layer_attributes_privileges_save(){
  	$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, NULL);
    if($this->formvars['stelle'] != '' AND $this->formvars['selected_layer_id'] != ''){
			$stellen = explode('|', $this->formvars['stelle']);
			foreach($stellen as $stelleid){
				$stelle = new stelle($stelleid, $this->database);
				$stelle->set_attributes_privileges($this->formvars, $this->attributes);
				$stelle->set_layer_privileges($this->formvars['selected_layer_id'], $this->formvars['privileg'.$stelleid], $this->formvars['export_privileg'.$stelleid]);
			}
    }
    elseif($this->formvars['selected_layer_id'] != ''){
      $mapdb->set_default_layer_privileges($this->formvars, $this->attributes);
    }
    $this->layer_attributes_privileges();
  }

  function StelleAendern() {
  	$_files = $_FILES;
    if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else {
      if ($_files['wappen']['name']){
        $this->formvars['wappen'] = $_files['wappen']['name'];
        $nachDatei = WWWROOT.APPLVERSION.WAPPENPATH.$_files['wappen']['name'];
        if (move_uploaded_file($_files['wappen']['tmp_name'],$nachDatei)) {
            #echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      $stelleid = $this->formvars['selected_stelle_id'];
      $Stelle = new stelle($stelleid,$this->user->database);
      $Stelle->language = $this->Stelle->language;
      $Stelle->Aendern($this->formvars);
      if ($this->formvars['id'] != '') {
        $new_stelle = new stelle($this->formvars['id'], $this->user->database);
        $new_stelleid = $this->formvars['id'];
        $this->formvars['selected_stelle_id'] = $new_stelleid;
      }
      else {
        $new_stelle = $Stelle;
        $new_stelleid = $stelleid;
      }
      $menues = explode(', ',$this->formvars['selmenues']);
      $functions = explode(', ',$this->formvars['selfunctions']);
      $frames = explode(', ',$this->formvars['selframes']);
			$layouts = explode(', ',$this->formvars['sellayouts']);
      $layer = explode(', ',$this->formvars['sellayer']);
      $selectedusers = explode(', ',$this->formvars['selusers']);
      $users= $Stelle->getUser();
      $stelle_id = explode(',',$stelleid);
      $new_stelle_id = explode(',',$new_stelleid);
      $new_stelle->deleteMenue(0); // erst alle Menüs rausnehmen
      $new_stelle->addMenue($menues); // und dann hinzufügen, damit die Reihenfolge stimmt
      if($layer[0] != NULL) {
        $new_stelle->addLayer($layer, 0); # Hinzufügen der Layer zur Stelle
      }
      $new_stelle->removeFunctions();   // Entfernen aller Funktionen
      if($functions[0] != NULL){
        $new_stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
      }
      $document = new Document($this->database);
      $document->removeFrames($new_stelleid);   // Entfernen aller Kartendruck-Layouts der Stelle
      if($frames[0] != NULL){
        for($i = 0; $i < count($frames); $i++){
          $document->add_frame2stelle($frames[$i], $new_stelleid); # Hinzufügen der Kartendruck-Layouts zur Stelle
        }
      }
			include_(CLASSPATH.'datendrucklayout.php');
			$ddl = new ddl($this->database, $this);
      $ddl->removelayouts($new_stelleid);   // Entfernen aller Datendruck-Layouts der Stelle
      if ($layouts[0] != NULL){
        for ($i = 0; $i < count($layouts); $i++){
          $ddl->add_layout2stelle($layouts[$i], $new_stelleid); # Hinzufügen der Datendruck-Layouts zur Stelle
        }
      }
      for ($i = 0; $i < count($selectedusers); $i++) {
        $this->user->rolle->setRollen($selectedusers[$i], $new_stelle_id); # Hinzufügen einer neuen Rolle (selektierte User zur Stelle)
        $this->user->rolle->setMenue($selectedusers[$i], $new_stelle_id); # Hinzufügen der selectierten Obermenüs zur Rolle
        $this->user->rolle->setGroups($selectedusers[$i], $new_stelle_id, $layer, 0); # Hinzufügen der Layergruppen der selektierten Layer zur Rolle
        $this->user->rolle->setLayer($selectedusers[$i], $new_stelle_id, 0); # Hinzufügen der Layer zur Rolle
        $this->selected_user = new user(0,$selectedusers[$i],$this->user->database);
        $this->selected_user->checkstelle();
      }
			$new_stelle->updateLayerParams();
			# Löschen der in der Selectbox entfernten Layer
      $stellenlayer = $Stelle->getLayers(NULL);
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
      if($deletelayer != 0){
        $Stelle->deleteLayer($deletelayer, $this->pgdatabase);
        for($i = 0; $i < count($deletelayer); $i++){
          $layerid = $deletelayer[$i];
          $layer_id = explode(',',$layerid);
          for($j = 0; $j < count($users['ID']); $j++){
            $this->user->rolle->deleteLayer($users['ID'][$j], $stelle_id, $layer_id);
            $this->user->rolle->updateGroups($users['ID'][$j],$stelleid, $layerid);
          }
        }
      }
    # /Löschen der in der Selectbox entfernten Layer

    # Löschen  der User, die nicht mehr zur Stelle gehören sollen
    # Löschen der in der Selectbox entfernten User
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
      $anzdeleteuser = count($deleteuser);
      if ($anzdeleteuser > 0) {
        for($i=0; $i<$anzdeleteuser; $i++){
          $this->user->rolle->deleteRollen($deleteuser[$i], $stelle_id);
          $this->user->rolle->deleteMenue($deleteuser[$i], $stelle_id, 0);
          $this->user->rolle->deleteGroups($deleteuser[$i], $stelle_id);
          $this->user->rolle->deleteLayer($deleteuser[$i], $stelle_id, 0);
          $this->selected_user = new user(0,$deleteuser[$i],$this->user->database);
          $this->selected_user->checkstelle();
        }
      }
			# /Löschen der in der Selectbox entfernten User

      if ($ret[0]) {
				$this->add_message('error', $ret[1]);
      }
      else {
        $this->add_message('notice', 'Daten der Stelle erfolgreich eingetragen!');
      }
    }
    $this->Stelleneditor();
  }

  function StelleAnlegen() {
  	$_files = $_FILES;
    if (!$this->formvars['bezeichnung'] or !$this->formvars['Referenzkarte_ID']) {
      # Fehler bei der Formulareingabe
      showAlert('Füllen Sie alle mit * gekennzeichneten Formularfelder aus.');
    }
    else {
      if($_files['wappen']['name']){
        $this->formvars['wappen'] = $_files['wappen']['name'];
        $nachDatei = WWWROOT.APPLVERSION.WAPPENPATH.$_files['wappen']['name'];
        if (move_uploaded_file($_files['wappen']['tmp_name'],$nachDatei)) {
            #echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
        }
      }
      $ret=$this->Stelle->NeueStelleAnlegen($this->formvars);
      if ($ret[0]) {
          # Fehler beim Eintragen der Stellendaten
          $this->Meldung=$ret[1];
      }
     else {
        $neue_stelle_id=$ret[1];
        $Stelle = new stelle($neue_stelle_id,$this->user->database);
        $menues = explode(', ',$this->formvars['selmenues']);
        $functions = explode(', ',$this->formvars['selfunctions']);
        $frames = explode(', ',$this->formvars['selframes']);
        $layer = explode(', ',$this->formvars['sellayer']);
        $users = explode(', ',$this->formvars['selusers']);
        $neue_stelle_id = explode(',',$neue_stelle_id);
        # wenn Stelle ausgewählt, Daten kopieren
        if($this->formvars['selected_stelle_id']){
          $Stelle->copyLayerfromStelle($layer, $this->formvars['selected_stelle_id']);
        }
        $Stelle->addMenue($menues);
        if($functions[0] != NULL){
          $Stelle->addFunctions($functions, 0); # Hinzufügen der Funktionen zur Stelle
        }
        if($layer[0] != NULL){
          $Stelle->addLayer($layer, 0);
        }
        $document = new Document($this->database);
        if($frames[0] != NULL){
          for($i = 0; $i < count($frames); $i++){
            $document->add_frame2stelle($frames[$i], $neue_stelle_id[0]); # Hinzufügen der Druckrahmen zur Stelle
          }
        }
        for($i=0; $i<count($users); $i++){
          $this->user->rolle->setRollen($users[$i],$neue_stelle_id);
          $this->user->rolle->setMenue($users[$i],$neue_stelle_id);
          $this->user->rolle->setGroups($users[$i], $neue_stelle_id, $layer, 0);
          $this->user->rolle->setLayer($users[$i], $neue_stelle_id, 0);
          $this->selected_user = new user(0,$users[$i],$this->user->database);
          $this->selected_user->checkstelle();
        }
				$Stelle->updateLayerParams();
        if ($ret[0]) {
          $this->Meldung=$ret[1];
        }
        else {
          $this->Meldung='Daten der Stelle erfolgreich eingetragen!';
        }
      }
    }
    $this->formvars['selected_stelle_id'] = $ret[1];
    $this->Stelleneditor();
  }

  function StellenAnzeigen() {
    # Abfragen aller Stellen
    if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Bezeichnung';
    }
    $this->stellendaten=$this->Stelle->getStellen($this->formvars['order']);
    $this->titel='Stellendaten';
    $this->main='stellendaten.php';
    $this->output();
  }

  function Stelleneditor() {
		include_(CLASSPATH.'datendrucklayout.php');
		include_(CLASSPATH.'funktion.php');
    $this->titel='Stellen Editor';
    $this->main='stelle_formular.php';
    $document = new Document($this->database);
		$ddl = new ddl($this->database, $this);
    # Abfragen der Stellendaten wenn eine stelle_id zur Änderung selektiert ist
    if ($this->formvars['selected_stelle_id']>0) {
      $Stelle = new stelle($this->formvars['selected_stelle_id'],$this->user->database);
      $Stelle->language = $this->Stelle->language;
      $this->stellendaten = $Stelle->getstellendaten();
      $this->formvars['bezeichnung'] = $this->stellendaten['Bezeichnung'];
      $this->formvars['minxmax'] = $this->stellendaten['minxmax'];
      $this->formvars['minymax'] = $this->stellendaten['minymax'];
      $this->formvars['maxxmax'] = $this->stellendaten['maxxmax'];
      $this->formvars['maxymax'] = $this->stellendaten['maxymax'];
      $this->formvars['epsg_code'] = $this->stellendaten['epsg_code'];
      $this->formvars['Referenzkarte_ID'] = $this->stellendaten['Referenzkarte_ID'];
      $this->formvars['start'] = $this->stellendaten['start'];
      $this->formvars['stop'] = $this->stellendaten['stop'];
      $this->formvars['pgdbhost'] = $this->stellendaten['pgdbhost'];
      $this->formvars['pgdbname'] = $this->stellendaten['pgdbname'];
      $this->formvars['pgdbuser'] = $this->stellendaten['pgdbuser'];
      $this->formvars['pgdbpasswd'] = $this->stellendaten['pgdbpasswd'];
      $this->formvars['ows_title'] = $this->stellendaten['ows_title'];
      $this->formvars['ows_abstract'] = $this->stellendaten['ows_abstract'];
      $this->formvars['wms_accessconstraints'] = $this->stellendaten['wms_accesscontraints'];
      $this->formvars['ows_contactperson'] = $this->stellendaten['ows_contactperson'];
      $this->formvars['ows_contactorganization'] = $this->stellendaten['ows_contactorganization'];
      $this->formvars['ows_contactemailaddress'] = $this->stellendaten['ows_contactemailaddress'];
      $this->formvars['ows_contactposition'] = $this->stellendaten['ows_contactposition'];
      $this->formvars['ows_fees'] = $this->stellendaten['ows_fees'];
      $this->formvars['ows_srs'] = $this->stellendaten['ows_srs'];
      $this->formvars['wappen'] = $this->stellendaten['wappen'];
      $this->formvars['alb_raumbezug'] = $this->stellendaten['alb_raumbezug'];
      $this->formvars['alb_raumbezug_wert'] = $this->stellendaten['alb_raumbezug_wert'];
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
    }
    # Abfragen aller möglichen Menuepunkte
    $this->formvars['menues'] = Menue::get_all_ober_menues($this);
    # Abfragen aller möglichen Funktionen
    $funktion = new funktion($this->database);
    $this->formvars['functions'] = $funktion->getFunktionen(NULL, 'bezeichnung');
    # Abfragen aller möglichen Kartendruck-Layouts
    $this->formvars['frames'] = $document->load_frames(NULL, NULL);
		# Abfragen aller möglichen Datendruck-Layouts
    $this->formvars['layouts'] = $ddl->load_layouts(NULL, NULL, NULL, NULL);
    # Abfragen aller möglichen Layer
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->formvars['layer']=$mapDB->getall_Layer('Name');
		$this->layergruppen = $mapDB->get_Groups();
    # Abfragen aller möglichen User
    $this->formvars['users']=$this->user->getall_Users('Name');
    # Abfragen aller möglichen EPSG-Codes
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    $this->output();
  }

  function StelleLoeschen(){
    $selected_stelle=new stelle($this->formvars['selected_stelle_id'],$this->user->database);
    $selected_stelle->Löschen();
    $selected_stelle->deleteMenue(0);
		$selected_stelle->deleteDruckrahmen();
		$selected_stelle->deleteStelleGemeinden();
		$selected_stelle->deleteFunktionen();
    $selected_stelle->deleteLayer(0, $this->pgdatabase);
    $user = $selected_stelle->getUser();
    $stelle_id = explode(',',$selected_stelle->id);
    for($i = 0; $i < count($user['ID']); $i++){
      $this->user->rolle->deleteRollen($user['ID'][$i], $stelle_id);
      $this->user->rolle->deleteMenue($user['ID'][$i], $stelle_id, 0);
      $this->user->rolle->deleteGroups($user['ID'][$i], $stelle_id);
      $this->user->rolle->deleteLayer($user['ID'][$i], $stelle_id, 0);
    }
    $this->titel='Stellendaten';
    $this->main='stellendaten.php';
    # Abfragen aller Stellen
    $this->stellendaten=$this->Stelle->getStellen($this->formvars['order']);
    $this->output();
  }

  function Filterverwaltung() {
    $this->loadMap('DataBase');
    $this->titel='Filterverwaltung';
    $this->main='attribut_eingabe_form.php';
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    $showpolygon = true;
    $this->queryable_vector_layers = $this->Stelle->getqueryableVectorLayers(NULL, $this->user->id, NULL, NULL, NULL, true);
  	if(!$this->formvars['layer_id']){
      $layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
      $this->formvars['layer_id'] = $layerset[0]['Layer_ID'];
    }
    if($this->formvars['layer_id']){
	    # Geometrie-Übernahme-Layer:
	    # Spaltenname und from-where abfragen
	    $data = $this->mapDB->getData($this->formvars['layer_id']);
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
			$this->formvars['fromwhere'] = pg_escape_string('from ('.$fromwhere.') as foo where 1=1');
	    if(strpos(strtolower($this->formvars['fromwhere']), ' where ') === false){
	      $this->formvars['fromwhere'] .= ' where (1=1)';
	    }
    }
    if($this->formvars['stelle'] != ''){
      $stelle = new stelle($this->formvars['stelle'], $this->database);
      $this->layerdaten = $stelle->getLayers(NULL, 'Name');
      if($this->formvars['selected_layers'] != ''){
        $this->selected_layers = explode(', ', $this->formvars['selected_layers']);
        $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
        $this->attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[0]);
        $poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[0]);
        for($i = 1; $i < count($this->selected_layers); $i++){
          $layerdb = $this->mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
          $attributes = $this->mapDB->getDataAttributes($layerdb, $this->selected_layers[$i]);
          $this->attributes = array_values(array_uintersect($this->attributes, $attributes, "compare_names"));
          $next_poly_id = $this->mapDB->getPolygonID($this->formvars['stelle'],$this->selected_layers[$i]);
          if($poly_id != $next_poly_id){
            $showpolygon = false;
          }
          $poly_id = $next_poly_id;
        }
				for($i = 0; $i < count($this->attributes); $i++){
					$this->formvars['operator_'.$this->attributes[$i]['name']] = '';
					$this->formvars['value_'.$this->attributes[$i]['name']] = '';
        }
        for($j = 0; $j < count($this->selected_layers); $j++){
          $filter = $this->mapDB->readAttributeFilter($this->formvars['stelle'], $this->selected_layers[$j]);
          for($i = 0; $i < count($filter); $i++){
            if($this->formvars['value_'.$filter[$i]['attributname']] == NULL OR
              ($this->formvars['value_'.$filter[$i]['attributname']] == $filter[$i]['attributvalue'] AND
               $this->formvars['operator_'.$filter[$i]['attributname']] == $filter[$i]['operator'])){
              $this->formvars['value_'.$filter[$i]['attributname']] = pg_escape_string($filter[$i]['attributvalue']);
              $this->formvars['operator_'.$filter[$i]['attributname']] = $filter[$i]['operator'];
              $setAttributes[$filter[$i]['attributname']]++;
            }
            else{
              $this->formvars['value_'.$filter[$i]['attributname']] = '---- verschieden ----';
            }
          }
        }
        for($i = 0; $i < count($setAttributes); $i++){
          $element = each($setAttributes);
          if($element['value'] < count($this->selected_layers)){
            $this->formvars['value_'.$element['key']] = '---- verschieden ----';
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
          }
        }
      }
      else{
        # Es soll navigiert werden
        # Navigieren
        $this->navMap($this->formvars['CMD']);
        $this->user->rolle->saveSettings($this->map->extent);
        $this->user->rolle->readSettings();
      }
    }
    else{
      # Es soll navigiert werden
      # Navigieren
      $this->navMap($this->formvars['CMD']);
      $this->user->rolle->saveSettings($this->map->extent);
      $this->user->rolle->readSettings();
    }
  }

  function Filter_speichern($formvars){
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    if($formvars['selected_layers'] != ''){
      $this->selected_layers = explode(', ', $formvars['selected_layers']);
      $layerdb = $mapDB->getlayerdatabase($this->selected_layers[0], $this->Stelle->pgdbhost);
      $this->attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[0]);
			for($i = 1; $i < count($this->selected_layers); $i++){
				$layerdb = $mapDB->getlayerdatabase($this->selected_layers[$i], $this->Stelle->pgdbhost);
				$attributes = $mapDB->getDataAttributes($layerdb, $this->selected_layers[$i]);
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
    for($i = 0; $i < count($this->selected_layers); $i++){
      $filter = $mapDB->readAttributeFilter($this->formvars['stelle'], $this->selected_layers[$i]);
      $mapDB->writeFilter($this->pgdatabase, $filter, $this->selected_layers[$i], $formvars['stelle']);
    }
    $this->Filterverwaltung();
  }

  function StatistikAuswahl() {
    # Abfragen aller Stellen für die Statistik oder Abrechnung
    $this->account = new account($this->database);
    $this->user2 = new user(0,'',$this->database);
    $this->stellendaten=$this->Stelle->getStellen('Bezeichnung');
    if($this->formvars['go'] == 'StatistikAuswahl_Stelle'){
    	$this->stellendaten=$this->user->getStellen('Bezeichnung');
    }
    $this->UserDaten=$this->user2->getUserDaten('','','Name');
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
    for($i = 0; $i < count($this->account->ALKNumbOfAccess); $i++){
      if(($this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4hoch' OR $this->account->ALKNumbOfAccess[$i]['Druckformat'] == 'A4quer') AND $this->account->ALKNumbOfAccess[$i]['Preis'] > 0){
        $this->account->ALKA4 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
      else{
        $this->account->ALKA3 += $this->account->ALKNumbOfAccess[$i]['NumberOfAccess'];
      }
    }
    $this->account->ALB = 0;
    for($i = 0; $i < count($this->account->ALBNumbOfAccess); $i++){
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
		include_(CLASSPATH.'funktion.php');
    $this->main='funktionen.php';
    # Abfragen aller Funktionen
    $this->funktion = new funktion($this->database);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

  function FunktionenFormular(){
		include_once (CLASSPATH.'funktion.php');
    $this->main='funktionen_formular.php';
    if ($this->formvars['selected_function_id']>0) {
      $this->funktion = new funktion($this->database);
      $this->funktionen = $this->funktion->getFunktionen($this->formvars['selected_function_id'], NULL);
      $this->formvars['bezeichnung'] = $this->funktionen[0]['bezeichnung'];
    }
    $this->output();
  }

  function FunktionAnlegen() {
		include_(CLASSPATH.'funktion.php');
    $this->funktion = new funktion($this->database);
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
		include_(CLASSPATH.'funktion.php');
    $this->funktion = new funktion($this->database);
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
		include_(CLASSPATH.'funktion.php');
    $this->main='funktionen.php';
    $this->funktion = new funktion($this->database);
    $ret = $this->funktion->Loeschen($this->formvars);
    $this->funktionen = $this->funktion->getFunktionen(NULL, $this->formvars['order']);
    $this->output();
  }

 /**
  * Läd das Formular zur Eingabe von Benutzerdaten
  *
  * Die Funktion läd das Template userdaten_formular.php trägt existierende Werte vom Benutzer ein, wenn es um eine Änderung geht und stellt die Stellenname zur Auswahl bereit, zu der der Nutzer Zugang hat
  *
  * Reihenfolge: Übersichtssatz - Kommentar - Tags.
  *
  * @see    BenutzerLöschen(), BenutzerdatenAnzeigen(), BenutzerdatenAnlegen(), BenutzerdatenAendern(), $postgres, $alb
  */
  function BenutzerdatenFormular() {
    $this->titel='Benutzerdaten Editor';
    $this->main='userdaten_formular.php';
    # Abfragen der Benutzerdaten wenn eine user_id zur Änderung selektiert ist
    if ($this->formvars['selected_user_id']>0) {
      $this->userdaten=$this->user->getUserDaten($this->formvars['selected_user_id'],'','');
      $this->formvars['nachname']=$this->userdaten[0]['Name'];
      $this->formvars['vorname']=$this->userdaten[0]['Vorname'];
      $this->formvars['loginname']=$this->userdaten[0]['login_name'];
      $this->formvars['Namenszusatz']=$this->userdaten[0]['Namenszusatz'];
      $this->formvars['password_setting_time']=$this->userdaten[0]['password_setting_time'];
      $this->formvars['start']=$this->userdaten[0]['start'];
      $this->formvars['stop']=$this->userdaten[0]['stop'];
      $this->formvars['ips']=$this->userdaten[0]['ips'];
      $this->formvars['phon']=$this->userdaten[0]['phon'];
      $this->formvars['email']=$this->userdaten[0]['email'];
    # Abfragen der Stellen des Nutzers
      $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
      $this->formvars['selstellen']=$this->selected_user->getStellen(0);
    }
    # Abfragen aller möglichen Stellen
    $this->formvars['stellen']=$this->Stelle->getStellen('Bezeichnung');
    $this->output();
  }

  function BenutzerLöschen(){
    $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
    $stellen = $this->selected_user->getStellen(0);
    $this->selected_user->Löschen($this->formvars['selected_user_id']);
    $this->user->rolle->deleteRollen($this->formvars['selected_user_id'], $stellen['ID']);
    $this->user->rolle->deleteMenue($this->formvars['selected_user_id'], $stellen['ID'], 0);
    $this->user->rolle->deleteGroups($this->formvars['selected_user_id'], $stellen['ID']);
    $this->user->rolle->deleteLayer($this->formvars['selected_user_id'], $stellen['ID'], 0);
    $this->titel='Benutzerdaten';
    $this->main='userdaten.php';
    # Abfragen aller Benutzer
    $this->userdaten=$this->user->getUserDaten(0,'',$this->formvars['order']);
    $this->output();
  }

  function BenutzerdatenAnzeigen() {
  	if($this->formvars['order'] == ''){
      $this->formvars['order'] = 'Name';
    }
    $this->titel='Benutzerdaten';
    $this->main='userdaten.php';
    # Abfragen aller Benutzer
    $this->userdaten=$this->user->getUserDaten(0,'',$this->formvars['order']);
    $this->output();
  }

  function BenutzerdatenAnlegen() {
    $ret=$this->user->checkUserDaten($this->formvars);
    if ($ret[0]) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else{
      $ret=$this->user->NeuAnlegen($this->formvars);
      if ($ret[0]) {
        # Fehler beim Eintragen der Benutzerdaten
        $this->Meldung=$ret[1];
      }
      else {
        $neue_user_id=$ret[1];
        $stellen = explode(', ',$this->formvars['selstellen']);
        $this->user->rolle->setRollen($neue_user_id,$stellen);
        $this->user->rolle->setMenue($neue_user_id,$stellen);
        $this->user->rolle->setLayer($neue_user_id, $stellen, 0);
				for($i = 0; $i < count($stellen); $i++){
					$stelle = new stelle($stellen[$i], $this->database);
					$layers = $stelle->getLayers(NULL);
					$this->user->rolle->setGroups($neue_user_id, array($stellen[$i]), $layers['ID'], 0);
				}
        if ($ret[0]) {
          $this->Meldung=$ret[1];
        }
        else {
          $this->Meldung='Daten des Benutzers erfolgreich eingetragen!';
        }
      }
    }
    $this->formvars['selected_user_id'] = $neue_user_id;
    $this->BenutzerdatenFormular();
  }

  function BenutzerdatenAendern() {
    $ret=$this->user->checkUserDaten($this->formvars);
    if ($ret[0]) {
      # Fehler bei der Formulareingabe
      $this->Meldung=$ret[1];
    }
    else {
      $stellen = explode(', ',$this->formvars['selstellen']);
      $ret=$this->user->Aendern($this->formvars);
      if($this->formvars['id'] != ''){
        $this->formvars['selected_user_id'] = $this->formvars['id'];
      }
      $this->user->rolle->setRollen($this->formvars['selected_user_id'], $stellen);
      $this->user->rolle->setMenue($this->formvars['selected_user_id'], $stellen);
      $this->user->rolle->setLayer($this->formvars['selected_user_id'], $stellen, 0);
			for($i = 0; $i < count($stellen); $i++){
				$stelle = new stelle($stellen[$i], $this->database);
				$layers = $stelle->getLayers(NULL);
				$this->user->rolle->setGroups($this->formvars['selected_user_id'], array($stellen[$i]), $layers['ID'], 0);
			}
      $this->selected_user=new user(0,$this->formvars['selected_user_id'],$this->user->database);
      # Löschen der in der Selectbox entfernten Stellen
      $userstellen =  $this->selected_user->getStellen(0);
      for($i = 0; $i < count($userstellen['ID']); $i++){
        $found = false;
        for($j = 0; $j < count($stellen); $j++){
          if($stellen[$j] == $userstellen['ID'][$i]){
            $found = true;
          }
        }
        if($found == false){
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
    $all_users = $this->user->getall_Users(NULL);
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
		$this->cronjob->data = formvars_strip($this->formvars, $this->cronjob->getAttributes(), 'keep');
		$this->cronjob->set('query', strip_pg_escape_string($this->formvars['query']));
		$results = $this->cronjob->validate();
		if (empty($results)) {
			$results = $this->cronjob->create();
		}
		if (empty($results)) {
			$this->add_message('notice', 'Job erfolgreich angelegt.');
			$this->cronjobs = CronJob::find($this);
			$this->main = 'cronjobs.php';
		}
		else {
			$this->add_message('array', $results);
			$this->main = 'cronjob_formular.php';
		}
		$this->output();
	}

	function cronjob_update() {
		include_once(CLASSPATH . 'CronJob.php');
		$this->cronjob = CronJob::find_by_id($this, $this->formvars['id']);
		$this->cronjob->data = formvars_strip($this->formvars, $this->cronjob->getAttributes(), 'keep');
		$result = $this->cronjob->update();
		if (!empty($result)) {
			$this->add_message('error', 'Fehler beim Eintragen in die Datenbank!<br>' . $result);
			$this->main = 'cronjob_formular.php';
		}
		else {
	#		$this->cronjob->set('query', strip_pg_escape_string($this->cronjob->get('query')));
			$this->cronjobs = CronJob::find($this);
			$this->main = 'cronjobs.php';
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
		$crontab_lines = array();
		foreach($this->cronjobs AS $cronjob) {
			if ($cronjob->get('aktiv')) {
				$crontab_lines[] = $cronjob->get_crontab_line();
			}
		}

		# schreibt die Zeilen in eine Datei
		$crontab_file = '/var/www/cron/crontab_gisadmin';
		$fp = fopen($crontab_file, 'w');
		foreach($crontab_lines AS $line) {
			fwrite($fp, $line . PHP_EOL);
		}
		fclose($fp);

		# crontab starten
#		exec('crontab -u www-data ' . $crontab_file);

		# crontab datei löschen
#		unlink($crontab_file);

		# crontab Zeilen anzeigen
		$this->crontab_lines = $crontab_lines;
		$this->main = 'crontab.php';
		$this->output();
	}

  function LayerUebersicht() {
  	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $this->titel='Themenübersicht';
    $this->main='layer_uebersicht.php';
    # Abfragen aller Layer
    $this->layer = $mapDB->getall_Layer('Gruppenname, Name');
    $this->output();
  }

	function saveLegendRoleParameters(){
		# Scrollposition der Legende wird gespeichert
  	$this->user->rolle->setScrollPosition($this->formvars['scrollposition']);
    # Änderungen in den Gruppen werden gesetzt
    $this->formvars = $this->user->rolle->setGroupStatus($this->formvars);
    # Ein- oder Ausblenden der Klassen
    #$this->user->rolle->setClassStatus($this->formvars);			# kann wahrscheinlich weg
    # Wenn ein Button im Kartenfenster gewählt wurde,
    # werden auch die Einstellungen aus der Legende übernommen
    $this->user->rolle->setAktivLayer($this->formvars,$this->Stelle->id,$this->user->id);
    $this->user->rolle->setQueryStatus($this->formvars);
	}

  function neuLaden() {
		$this->saveLegendRoleParameters();
		if(in_array($this->formvars['last_button'], array('zoomin', 'zoomout', 'recentre', 'pquery', 'touchquery', 'ppquery', 'polygonquery')))$this->user->rolle->setSelectedButton($this->formvars['last_button']);		// das ist für den Fall, dass ein Button schon angeklickt wurde, aber die Aktion nicht ausgeführt wurde
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # zwischenspeichern des vorherigen Maßstabs
    $oldscale=round($this->map_scaledenom);
		# zoomToMaxLayerExtent
		if($this->formvars['zoom_layer_id'] != '')$this->zoomToMaxLayerExtent($this->formvars['zoom_layer_id']);
		if ($oldscale!=$this->formvars['nScale'] AND $this->formvars['nScale'] != '') {
      # Zoom auf den in der Maßstabsauswahl ausgewählten Maßstab
      # wenn er sich von der vorherigen Maßstabszahl unterscheidet
      # (das heißt wenn eine andere Zahl eingegeben wurde)
      $this->scaleMap($this->formvars['nScale']);
			$this->user->rolle->saveSettings($this->map->extent);
			$this->user->rolle->readSettings();
    }
    # Zoom auf den in der Referenzkarte ausgewählten Ausschnitt
    if ($this->formvars['refmap_x'] > 0) {
      $this->zoomToRefExt();
    }
    else {
      # Wenn ein Navigationskommando ausgewählt/übergeben wurde
      # Zoom/Pan auf den in der Karte ausgewählten Ausschnitt
      if ($this->formvars['CMD']!='') {
        $this->navMap($this->formvars['CMD']);
      }
    }
  }

  function zoom2coord(){
  	$this->zoomMap(1);
  	$this->scaleMap(5000);
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

# 2006-03-20 pk
  function zoomToStoredMapExtent($storetime){
    # Karteninformationen lesen
    $this->loadMap('DataBase');
    # Abfragen der gespeicherten Kartenausdehnung
    $ret=$this->user->rolle->getConsume($storetime);
    if ($ret3[0]) {
      $this->errmsg="Der gespeicherte Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
    }
    else {
      $this->user->rolle->set_last_time_id($storetime);
      $this->user->rolle->newtime = $storetime;
			$rect = ms_newRectObj();
			$rect->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
			if($ret[1]['epsg_code'] != '' AND $ret[1]['epsg_code'] != $this->user->rolle->epsg_code){
				$rect = ms_newRectObj();
				$rect->setextent($ret[1]['minx'],$ret[1]['miny'],$ret[1]['maxx'],$ret[1]['maxy']);
				$projFROM = ms_newprojectionobj("init=epsg:".$ret[1]['epsg_code']);
				$projTO = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
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
    $this->drawMap();
    $this->output();
  }

  # 2006-03-20 pk
  function setPrevMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $prevextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $prevextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $ret = $this->user->rolle->getConsume($consumetime);
    $i = 0;
    while($i < 100 AND (string)$currentextent->minx == (string)$prevextent->minx AND (string)$currentextent->miny == (string)$prevextent->miny AND (string)$currentextent->maxx == (string)$prevextent->maxx AND (string)$currentextent->maxy == (string)$prevextent->maxy){
      # Setzen des next Wertes des vorherigen Kartenausschnittes
      $prevtime=$ret[1]['prev'];
      $this->user->rolle->newtime = $prevtime;
      if (!($prevtime=='' OR $prevtime=='2006-09-29 12:55:50')) {
        $ret=$this->user->rolle->updateNextConsumeTime($prevtime,$consumetime);
        if ($ret[0]) {
          $this->errmsg="Der Nachfolger für den letzten Kartenausschnitt konnte nicht eingetragen werden.<br>".$ret[1];
        }
        else {
          # Abfragen der vorherigen Kartenausdehnung
          $ret=$this->user->rolle->getConsume($prevtime);
          if ($ret[0]) {
            $this->errmsg="Der letzte Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
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

  # 2006-03-20 pk
  function setNextMapExtent($consumetime) {
    $currentextent = ms_newRectObj();
    $nextextent = ms_newRectObj();
    $currentextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
    $nextextent->setextent($this->map->extent->minx, $this->map->extent->miny, $this->map->extent->maxx, $this->map->extent->maxy);
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
        $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
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
    $ret=$this->user->rolle->insertMapComment($this->formvars['consumetime'],$this->formvars['comment']);
		$this->add_message('notice', 'Ausschnitt gespeichert.');
    $ret=$this->user->rolle->getConsume($this->formvars['consumetime']);
    if ($ret[0]) {
      $this->errmsg="Der nächste Kartenausschnitt konnte nicht abgefragt werden.<br>".$ret[1];
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
    $ret=$this->user->rolle->getMapComments(NULL);
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
    $ret=$this->user->rolle->getLayerComments(NULL);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine gespeicherten Themen abgefragt werden.<br>'.$ret[1];
    }
    else {
      $this->layerComments=$ret[1];
    }
    $this->output();
  }

	function layerCommentLoad(){
		$ret=$this->user->rolle->getLayerComments($this->formvars['id']);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine gespeicherten Themen abgefragt werden.<br>'.$ret[1];
    }
    else {
			$layerset = $this->user->rolle->getLayer('');
			for($i=0; $i < count($layerset); $i++){
				$formvars['thema'.$layerset[$i]['Layer_ID']] = 0;		# erstmal alle ausschalten
				$formvars['qLayer'.$layerset[$i]['Layer_ID']] = 0;		# erstmal alle ausschalten
			}
      $layer_ids = explode(',', $ret[1][0]['layers']);
			foreach($layer_ids as $layer_id){
				$formvars['thema'.$layer_id] = 1;
			}
			$query_ids = explode(',', $ret[1][0]['query']);
			foreach($query_ids as $layer_id){
				$formvars['qLayer'.$layer_id] = 1;
			}
			$this->user->rolle->setAktivLayer($formvars, $this->Stelle->id, $this->user->id, true);
			$this->user->rolle->setQueryStatus($formvars);
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

	function reduce_mapwidth($reduction){
		# Diese Funktion reduziert die aktuelle Kartenbildbreite um $reduction Pixel, damit das Kartenbild in Fachschalen nicht zu groß erscheint.
		# Diese reduzierte Breite wird aber nicht in der Datenbank gespeichert, sondern gilt nur für den aktuellen Anwendungsfall.
		# Außerdem wird bei Bedarf der aktuelle Maßstab berechnet und zurückgeliefert (er wird berechnet, weil ein loadmap() ja noch nicht aufgerufen wurde). 
		# Mit diesem Maßstab kann dann einmal beim ersten Aufruf der Fachschale von der Hauptkarte aus nach dem loadmap() der Extent wieder so angepasst werden, dass der ursprüngliche Maßstab erhalten bleibt.
		# Dieser verkleinerte Extent wird wiederum in der Datenbank gespeichert. In der Datenbank steht dann also weiterhin die ursprüngliche Kartenbildgröße und der (dazu eigentlich nicht passende) in der Breite verkleinerte Extent.
		# Damit der Extent aber nur dann angepasst wird, wenn es notwendig ist (nämlich wenn man von der Hauptkarte kommt), wird der Maßstab nur berechnet, wenn Kartenbildgröße und Extent zusammenpassen.
		# Am "Nichtzusammenpassen" von Kartenbildgröße und Extent wird also erkannt, dass der Extent schon einmal verkleinert wurde.
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
		$width = $width - $reduction;
		if($this->user->rolle->hideMenue == 1){$width = $width - 195;}
		if($this->user->rolle->hideLegend == 1){$width = $width - 254;}
		$this->user->rolle->nImageWidth = $width;
		return $scale;
	}

  function getFunktionen() {
    $this->Stelle->getFunktionen();
  }

	function ALKIS_Auszug($FlurstKennz,$Grundbuchbezirk,$Grundbuchblatt,$Buchnungstelle,$formnummer){
		include_(CLASSPATH.'alb.php');
    if($FlurstKennz[0] == '' AND ($Grundbuchbezirk != NULL OR $Buchnungstelle != NULL)){
      $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$this->pgdatabase);
      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
      $ret=$grundbuch->getBuchungen('','','',1, $Buchnungstelle);
      $buchungen=$ret[1];
      for ($b=0;$b < count($buchungen);$b++) {
        $FlurstKennz[] = $buchungen[$b]['flurstkennz'];
      }
    }
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennz
    $ret=$this->Stelle->getFlurstueckeAllowed($FlurstKennz,$this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      $this->loadMap('DataBase');
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->drawMap();
			$this->output();
    }
    else{
      $FlurstKennz=$ret[1];
			$this->getFunktionen();
			if(!$this->Stelle->funktionen[$formnummer]['erlaubt']){
				showAlert('Die Anzeige dieses Nachweises ist für diese Stelle nicht erlaubt.');
				exit();
			}
      # Ausgabe der Flurstücksdaten im PDF Format
			$ALB=new ALB($this->pgdatabase);
			$nasfile = $ALB->create_nas_request_xml_file($FlurstKennz, $Grundbuchbezirk, $Grundbuchblatt, $Buchnungstelle, NULL, $formnummer);
			$sessionid = $ALB->dhk_call_login(DHK_CALL_URL, DHK_CALL_USER, DHK_CALL_PASSWORD);

			$currenttime=date('Y-m-d_H-i-s',time());
			switch($formnummer){
				case 'MV0700' : {
					$log_number = array($Grundbuchbezirk.'-'.$Grundbuchblatt);
					$filename = 'Bestandsnachweis_'.$currenttime;
				}break;

				case 'MV0600' : {
					$log_number = array($Buchnungstelle);
					$filename = 'Grundstücksnachweis_'.$currenttime;
				}break;

				default : {
					$log_number = $FlurstKennz;
					$filename = 'Flurstücksnachweis_'.$currenttime;
				}break;
			}
			$output = $ALB->dhk_call_getPDF(DHK_CALL_URL, $sessionid, $nasfile, $filename);
			switch (substr($output, 0, 2)){
				case 'PK' : $type = 'zip'; break;
				case '<?' : $type = 'xml'; break;
				case '%P' : $type = 'pdf'; break;
			}
			$currenttime=date('Y-m-d H:i:s',time());
      $this->user->rolle->setConsumeALB($currenttime, substr($formnummer, 3, 3),$log_number, 0, 'NULL');
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header('Content-Disposition: attachment; filename='.$filename.'.'.$type);
			header("Content-Transfer-Encoding: binary");
			print $output;
		}
	}

	function ALKIS_Kartenauszug($layout, $formvars){
		include_(CLASSPATH.'alb.php');
		$ALB=new ALB($this->pgdatabase);
		$point=ms_newPointObj();
		$point->setXY($formvars['center_x'], $formvars['center_y']);
		$projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
		$projTO = ms_newprojectionobj("init=epsg:".EPSGCODE_ALKIS);
		$point->project($projFROM, $projTO);
		$print_params['coord'] = $point->x.' '.$point->y;
		$print_params['printscale'] = $formvars['printscale'];
		$print_params['format'] = substr($layout['format'], 0, 3);
		$formnummer = $layout['dhk_call'];
		$nasfile = $ALB->create_nas_request_xml_file(NULL, NULL, NULL, NULL, $print_params, $formnummer);
		$sessionid = $ALB->dhk_call_login(DHK_CALL_URL, DHK_CALL_USER, DHK_CALL_PASSWORD);
		$currenttime=date('Y-m-d_H-i-s',time());
		$filename = 'Kartenauszug_'.$currenttime;
		$this->user->rolle->setConsumeALK($currenttime, $this->Docu->activeframe[0]['id']);
		return $ALB->dhk_call_getPDF(DHK_CALL_URL, $sessionid, $nasfile, $filename);
	}

  function ALB_Anzeigen($FlurstKennz,$formnummer,$Grundbuchbezirk,$Grundbuchblatt) {
		include_(CLASSPATH.'alb.php');
    if($FlurstKennz[0] == '' AND ($Grundbuchbezirk != NULL OR $Buchnungstelle != NULL)){
      $grundbuch=new grundbuch($Grundbuchbezirk,$Grundbuchblatt,$this->pgdatabase);
      # Abfrage aller Flurstücke, die auf dem angegebenen Grundbuchblatt liegen.
      $ret=$grundbuch->getBuchungen('','','',1, $Buchnungstelle);
      $buchungen=$ret[1];
      for ($b=0;$b < count($buchungen);$b++) {
        $FlurstKennz[] = $buchungen[$b]['flurstkennz'];
      }
    }
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennz
    $ret=$this->Stelle->getFlurstueckeAllowed($FlurstKennz,$this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      $this->loadMap('DataBase');
			$this->user->rolle->newtime = $this->user->rolle->last_time_id;
			$this->drawMap();
			$this->output();
    }
    else {
      $FlurstKennz=$ret[1];
      $this->getFunktionen();
      # Prüfen ob stelle Formular 30 sehen darf
      if ($formnummer==30) {
        if(!$this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 35 sehen darf
      if ($formnummer==35) {
        if(!$this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 40 sehen darf
      if ($formnummer==40) {
        if(!$this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 20 sehen darf
      if ($formnummer==20) {
        if(!$this->Stelle->funktionen['ALB-Auszug 20']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Prüfen ob stelle Formular 25 sehen darf
      if ($formnummer==25) {
        if(!$this->Stelle->funktionen['ALB-Auszug 25']['erlaubt']) {
          showAlert('Die Anzeige des Eigentümernachweises ist für diese Stelle nicht erlaubt.');
          exit();
        }
      }
      # Ausgabe der Flurstücksdaten im PDF Format
      include (CLASSPATH.'class.ezpdf.php');
      $pdf=new Cezpdf();
      $ALB=new ALB($this->pgdatabase);

      if($formnummer < 26){
        $log_number = array($Grundbuchbezirk.'-'.$Grundbuchblatt);
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$ALB->ALBAuszug_Bestand($Grundbuchbezirk,$Grundbuchblatt,$formnummer);
        $this->user->rolle->setConsumeALB($currenttime,$formnummer,$log_number,$this->formvars['wz'],$pdf->pagecount);
      }
      else{
        $currenttime=date('Y-m-d H:i:s',time());
        $pdf=$ALB->ALBAuszug_Flurstueck($FlurstKennz,$formnummer);
        $this->user->rolle->setConsumeALB($currenttime,$formnummer,$FlurstKennz,$this->formvars['wz'],$pdf->pagecount);
      }
      $this->pdf=$pdf;

      $dateipfad=IMAGEPATH;
      $currenttime = date('Y-m-d_H-i-s',time());
      $name = umlaute_umwandeln($this->user->Name);
      $dateiname = $name.'-'.$currenttime.'.pdf';
      $this->outputfile = $dateiname;
      $fp=fopen($dateipfad.$dateiname,'wb');
      fwrite($fp,$this->pdf->ezOutput());
      fclose($fp);

      $this->mime_type='pdf';
    }
    $this->output();
  }

  function rollenwahl($Stelle_ID) {
		include_once(CLASSPATH.'FormObject.php');
    $this->user->Stellen = $this->user->getStellen(0);
    $this->Hinweis.='Aktuelle Stellen_ID: '.$Stelle_ID;
    $StellenFormObj=new FormObject("Stelle_ID", "select", $this->user->Stellen['ID'], $Stelle_ID, $this->user->Stellen['Bezeichnung'], 'Anzahl Werte', "", "", NULL , NULL, "vertical-align: middle");
    # hinzufügen von Javascript welches dafür sorgt, dass die Angegebenen Werte abgefragt werden
    # und die genannten Formularobjekte mit diesen Werten bestückt werden
    # übergebene Werte
    # SQL für die Abfrage, es darf nur eine Zeile zurückkommen
    # Liste der Formularelementnamen, die betroffen sind in der Reihenfolge,
    # wie die Spalten in der Abfrage
    $select ="nZoomFactor,gui,CONCAT(nImageWidth,'x',nImageHeight) AS mapsize";
    $select.=",CONCAT(minx,' ',miny,',',maxx,' ',maxy) AS newExtent, epsg_code, fontsize_gle, highlighting, runningcoords, showmapfunctions, DATE_FORMAT(hist_timestamp,'%d.%m.%Y %T')";
    $from ='rolle';
    $where ="stelle_id='+this.form.Stelle_ID.value+' AND user_id=".$this->user->id;
    $StellenFormObj->addJavaScript("onchange","$('#sign_in_stelle').show(); ahah('index.php','go=getRow&select=".urlencode($select)."&from=".$from."&where=".$where."',new Array(nZoomFactor,gui,mapsize,newExtent,epsg_code,fontsize_gle,highlighting,runningcoords,showmapfunctions,hist_timestamp));");
    #echo URL.APPLVERSION."index.php?go=getRow&select=".urlencode($select)."&from=".$from."&where=stelle_id=3 AND user_id=7";
    $StellenFormObj->outputHTML();
    $this->StellenForm=$StellenFormObj;
    $this->main='rollenwahl.php';
    # Suchen nach verfügbaren Layouts
    # aus dem Stammordner layouts (vom System angebotene)
    $this->layoutfiles = searchdir(LAYOUTPATH, false);
    for($i = 0; $i < count($this->layoutfiles); $i++){
      if(strpos($this->layoutfiles[$i], '.php') > 0  AND strpos($this->layoutfiles[$i], 'main.css.php') === false){
        $this->guifiles[] = $this->layoutfiles[$i];
      }
    }
    # aus dem Customordner (vom Nutzer hinzugefügte Layouts)
    $this->customlayoutfiles = searchdir(LAYOUTPATH.'custom', true);
    for($i = 0; $i < count($this->customlayoutfiles); $i++){
      if(strpos($this->customlayoutfiles[$i], '.php') > 0){
        $this->customguifiles[] = $this->customlayoutfiles[$i];
      }
    }
    # Abfrage der verfügbaren Kartenprojektionen in PostGIS (Tabelle spatial_ref_sys)
    $this->epsg_codes = read_epsg_codes($this->pgdatabase);
    # Voreinstellen des aktuellen EPSG-Codes der Rolle
    if ($this->formvars['epsg_code']=='') {
      $this->formvars['epsg_code']=$this->user->rolle->epsg_code;
    }
    # Abfragen der Farben für die Suchergebnisse
    $this->result_colors = read_colors($this->database);
  }

  function flurstSuchen() {
    $GemID=$this->formvars['GemID'];
    $GemkgID=$this->formvars['GemkgID'];
    if ($this->formvars['FlurID']!='-1') {
      # dreistelliges auffüllen der Flurnummer mit Nullen
      $FlurID=str_pad($this->formvars['FlurID'],3,"0",STR_PAD_LEFT);
    }
    else {
      $FlurID=$this->formvars['FlurID'];
    }
    #$FlstID=$this->formvars['FlstID'];
    $FlstID=$this->formvars['selFlstID'];
    $FlstNr=$this->formvars['FlstNr'];
    #$this->searchInExtent=$this->formvars['searchInExtent'];
    $Gemarkung=new gemarkung('',$this->pgdatabase);
    # abfragen, ob es sich um eine gültige GemarkungsID handelt
    $GemkgListe=$Gemarkung->getGemarkungListe(array($GemID),array($GemkgID));
    if(count($GemkgListe['GemkgID']) > 0){
      # Die Gemarkung ist ausgewählt und gültig aber Flur leer, zoom auf Gemarkung
      if($FlurID==0 OR $FlurID=='-1'){
				if($this->formvars['ALK_Suche'] == 1){
					$this->loadMap('DataBase');
					$this->zoomToALKGemarkung($GemkgID,10);
					$currenttime=date('Y-m-d H:i:s',time());
					$this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
					$this->drawMap();
					$this->saveMap('');
				}
				else{			# Anzeige der Flurstuecke der Gemarkung
					$FlstNr=new flurstueck('',$this->pgdatabase);
					$FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,'',$this->formvars['historical']);
					$FlstID = $FlstNrListe['FlstID'];
					$FlurstKennz = array_values(array_unique($FlstID));
					$this->flurstAnzeige($FlurstKennz);
				}
      }
      else {
        # ist Gemarkung und Flur ausgefüllt aber keine Angabe zum Flurstück, zoom auf Flur
        if(($FlstID=='' AND $FlstNr=='') OR ($FlstID=='-1')){
        	if($this->formvars['ALK_Suche'] == 1){
	          $this->loadMap('DataBase');
	          $this->zoomToALKFlur($GemID,$GemkgID,$FlurID,10);
	          $currenttime=date('Y-m-d H:i:s',time());
	          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
	          $this->drawMap();
	          $this->saveMap('');
        	}
	        else{			# Anzeige der Flurstuecke der Flur
	      		$FlstNr=new flurstueck('',$this->pgdatabase);
	      		$FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID,$this->formvars['historical']);
		        $FlstID = $FlstNrListe['FlstID'];
	          $FlurstKennz = array_values(array_unique($FlstID));
	          $this->flurstAnzeige($FlurstKennz);
	      	}
        }
        else {
          # es existiert eine Angabe zum Flurstück
          $Flurstueck=new flurstueck('',$this->pgdatabase);
          # wenn keine FlstID angegeben wurde, wird versucht die FlstID aus der FlstNr abzuleiten
          if ($FlstID=='') {
            # ableiten der FlstID aus den Angaben in FlstNr
            $FlurstKennz[0]=$Flurstueck->is_FlurstNr($GemkgID,$FlurID,$FlstNr);
            if ($FlurstKennz[0]==0) {
              # aus FlstNr konnte kein eindeutiges FlurstKennz abgeleitet werden
              # Abfrage ob der Zähler eines Flurstücks mit FlstNr übereinstimmt
              $FlurstKennz=$Flurstueck->is_FlurstZaehler($GemkgID,$FlurID,$FlstNr);
              # wenn im Ergebnis die Anzahl der gefundenen FlurstKennz 0 ist wird weiter unten Suche abgebrochen
            }
          }
          else {
            # wenn FlstID nicht leer ist, wird diese zur Suche übernommen
            $FlurstKennz = explode(', ', $FlstID);
            $FlurstKennz = array_values(array_unique($FlurstKennz));
          }
          $anzFlurst=count($FlurstKennz);
          if ($anzFlurst==0) {
            # es konnten überhaupt keine gültigen Flurstuecke aus den Angaben FlstNr gefunden werden
            # zurück zur Auswahl mit Hinweis, daß Flurstücksauswahl zu keinem Ergebnis führt
            $this->Fehlermeldung='Zu diesem Flurstück wurden keine Angaben gefunden!';
            $this->flurstwahl();
          }
          else {
            # Es wurde mindestens ein eindeutiges FlurstKennz in FlstID ausgewählt, oder ein oder mehrere über FlstNr gefunden
            # Zoom auf Flurstücke
            if($this->formvars['ALK_Suche'] == 1){
		          $this->zoomToALKFlurst($FlurstKennz,10);
							if($this->formvars['go_next'] != '')header('location: index.php?go='.$this->formvars['go_next']);
		          $currenttime=date('Y-m-d H:i:s',time());
		          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
		          $this->drawMap();
		          $this->saveMap('');
            }
            else{	  # Anzeige der ALB-daten in Flurstücksanzeige
            	$this->flurstAnzeige($FlurstKennz);
            }
          }
        } # ende Suche nach Flurstück
      } # ende Suche nach Flur
    }
    else {
      $this->Fehlermeldung='Wählen Sie eine Gemarkung!';
      $this->flurstwahl();
    }
  } # ende function flurstSuchen

	function flurstSuchenByLatLng() {
    $flurstueck = new flurstueck('',$this->pgdatabase);
		if (in_array($this->formvars['version'], array("1.0", "1.0.0"))) {
			$result= $flurstueck->getFlurstByLatLng($this->formvars['latitude'], $this->formvars['longitude']);
			$layerset['landId'] = $result['land'];
			$layerset['kreisId'] = $result['kreis'];
			$layerset['gemeindId'] = $result['gemeinde'];
			$layerset['gemarkungId'] = $result['gemarkungsnummer'];
			$layerset['gemarkungName'] = $result['gemarkungname'];
			$layerset['flurId'] = $result['flurnummer'];
			$layerset['flurstueckId'] = $result['flurstkennz'];
			$layerset['flurstueckNummer'] = $result['flurstuecksnummer'];
			$this->qlayerset[0]['shape'][0] = $layerset;
			$this->mime_type = 'formatter';
		}
		else {
			$this->loadMap('DataBase');
      $this->user->rolle->newtime = $this->user->rolle->last_time_id;
      $this->drawMap();
     	$this->saveMap('');
		}
	} # ende function flurstSuchenByLatLng

	function Flurstueck_GetVersionen(){
		$ret=$this->Stelle->getFlurstueckeAllowed(array($this->formvars['flurstkennz']), $this->pgdatabase);
    if($ret[0]) {
      $this->Fehlermeldung=$ret[1];
    }
    else{
      $flst = new flurstueck($this->formvars['flurstkennz'], $this->pgdatabase);
			$versionen = $flst->getVersionen();
			$timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $this->user->rolle->hist_timestamp);
			$output = '	<table cellspacing="0" cellpadding="3">
										<tr style="background-color: #EDEFEF;">
											<td style="border-bottom: 1px solid '.BG_DEFAULT.'">
												<a href="javascript:hide_versions(\''.$this->formvars['flurstkennz'].'\');"><img src="'.GRAPHICSPATH.'minus.gif"></a>
											</td>
											<td style="border-bottom: 1px solid '.BG_DEFAULT.'">
												<span class="px14">Versionen</span>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>';
												if(count($versionen) > 0){
			$output.= '					<select name="versions_'.$k.'" onchange="location.href=\'index.php?go=setHistTimestamp&timestamp=\'+this.value+\'&go_next=get_last_query\'" style="max-width: 500px">';
													$selected = false;
													$v = 1;
													$count = count($versionen);
													reset($versionen);
													while($version = current($versionen)){
														$version_beginnt = key($versionen);
														$next_version = next($versionen);
														$next_version_beginnt = key($versionen);
														$beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $version_beginnt);
														$next_beginnt = DateTime::createFromFormat('d.m.Y H:i:s', $next_version_beginnt);
														$output.= '<option ';
														if($selected == false AND
															(($timestamp >= $beginnt AND $timestamp < $next_beginnt) OR				# timestamp liegt im Intervall
															$v == $count)																											# letzte Version (aktuell)
														){$selected = true; $output.= 'selected';}
														if($v < $count)$output.= ' value="'.$version_beginnt.'"';
														else $output.= ' value=""';
														$output.= ' title="'.implode(', ', $version['table']).'">';
														$output.= $version_beginnt.' '.implode(' ', $version['anlass']).'</option>';
														$v++;
													}
			$output.= '					</select>';
												}
			$output.= '			</td>
										</tr>
									</table>';
			echo $output;
    }
	}

  function flurstAnzeige($FlurstKennzListe) {
    # 2006-01-26 pk
    # Abfrage der Berechtigung zum Anzeigen der FlurstKennzListe
    $ret=$this->Stelle->getFlurstueckeAllowed($FlurstKennzListe, $this->pgdatabase);
    if ($ret[0]) {
      $this->Fehlermeldung=$ret[1];
      $anzFlurst=0;
    }
    else {
      $FlurstKennzListe=$ret[1];
      $anzFlurst=count($FlurstKennzListe);
    }

    $this->mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
    $layer = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
		$layerdb = $this->mapDB->getlayerdatabase($layer[0]['Layer_ID'], $this->Stelle->pgdbhost);
    $privileges = $this->Stelle->get_attributes_privileges($layer[0]['Layer_ID']);
    $layer[0]['attributes'] = $this->mapDB->read_layer_attributes($layer[0]['Layer_ID'], $layerdb, $privileges['attributenames']);

    if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = '0';
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = '0';
      }
    }
    else{
      for($j = 0; $j < count($layer[0]['attributes']['name']); $j++){
        $layer[0]['attributes']['privileg'][$j] = $privileges[$layer[0]['attributes']['name'][$j]];
        $layer[0]['attributes']['privileg'][$layer[0]['attributes']['name'][$j]] = $privileges[$layer[0]['attributes']['name'][$j]];
      }
    }
    $this->qlayerset[] = $layer[0];
    $this->main = $layer[0]['template'];

		$this->user->rolle->delete_last_query();
		$this->user->rolle->save_last_query('Flurstueck_Anzeigen', $layer[0]['Layer_ID'], implode(';', $FlurstKennzListe), NULL, NULL, NULL);

    for ($i=0;$i<$anzFlurst;$i++) {
      $this->qlayerset[0]['shape'][$i]['flurstkennz'] = $FlurstKennzListe[$i];
    }
    $i = 0;
  }

  function sachdaten_speichern() {
		if($this->formvars['document_attributename'] != '')$_FILES[$this->formvars['document_attributename']]['name'] = 'delete';		# das zu löschende Dokument
  	$_files = $_FILES;
    $mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    $form_fields = explode('|', $this->formvars['form_field_names']);
    $this->success = true;
    $old_layer_id = '';
    for($i = 0; $i < count($form_fields); $i++){
      if($form_fields[$i] != ''){
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
				if($layerset[$layer_id] == NULL){
					$layerset[$layer_id] = $this->user->rolle->getLayer($layer_id);
				}
        if($layer_id != $old_layer_id AND $tablename != ''){
          $layerdb[$layer_id] = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
          $layerdb[$layer_id]->setClientEncoding();
					$attributes = $mapdb->read_layer_attributes($layer_id, $layerdb[$layer_id], NULL);
          #$filter = $mapdb->getFilter($layer_id, $this->Stelle->id);		# siehe unten
          $old_layer_id = $layer_id;
        } 
				if(($this->formvars['go'] == 'Dokument_Loeschen' OR $this->formvars['changed_'.$layer_id.'_'.$oid] == 1 OR $this->formvars['embedded']) AND $attributname != 'oid' AND $tablename != '' AND $datatype != 'not_saveable' AND $tablename == $layerset[$layer_id][0]['maintable']){		# nur Attribute aus der Haupttabelle werden gespeichert
          switch($formtype) {
            case 'Dokument' : {
              # Prüfen ob ein neues Bild angegebeben wurde
              if($_files[$form_fields[$i]]['name']){			# die Dokument-Attribute werden hier zusammen gesammelt, weil der Datei-Upload gemacht werden muss, nachdem alle Attribute durchlaufen worden sind (wegen dem DocumentPath)
								$attr_oid['layer_id'] = $layer_id;
								$attr_oid['tablename'] = $tablename;
								$attr_oid['attributename'] = $attributname;
								$attr_oid['oid'] = $oid;
								$document_attributes[$i] = $attr_oid;
              } # ende vom Fall, dass ein neues Dokument hochgeladen wurde
            } break; # ende case Bild
            case 'Time' : {
							$eintrag = date('Y-m-d G:i:s');
            } break;
            case 'User' : {
							$eintrag = $this->user->Vorname." ".$this->user->Name;
            } break;
            case 'UserID' : {
							$eintrag = $this->user->id;
            } break;
            case 'Stelle' : {
							$eintrag = $this->Stelle->Bezeichnung;
            } break;
						case 'StelleID' : {
							$eintrag = $this->Stelle->id;
            } break;
            case 'Geometrie' : {
              # nichts machen
            } break;
            case 'Checkbox' : {
            	if($this->formvars[$form_fields[$i]] == '')$this->formvars[$form_fields[$i]] = 'f';
							$eintrag = $this->formvars[$form_fields[$i]];
            } break;
						case 'Zahl' : {
							$eintrag = removeTausenderTrenner($this->formvars[$form_fields[$i]]);		# bei Zahlen den Punkt (Tausendertrenner) entfernen
							if($this->formvars[$form_fields[$i]] == '')$eintrag = 'NULL';
						} break;
            default : {
              if($tablename AND $formtype != 'Text_not_saveable' AND $formtype != 'Auswahlfeld_not_saveable' AND $formtype != 'SubFormPK' AND $formtype != 'SubFormFK' AND $formtype != 'SubFormEmbeddedPK' AND $attributname != 'the_geom'){							
								if(POSTGRESVERSION >= 930 AND (substr($datatype, 0, 1) == '_' OR is_numeric($datatype))){
              		$this->formvars[$form_fields[$i]] = JSON_to_PG(json_decode($this->formvars[$form_fields[$i]]));		# bei einem custom Datentyp oder Array das JSON in PG-struct umwandeln									
              	}
                if($this->formvars[$form_fields[$i]] == ''){
									$eintrag = 'NULL';
                }
                else{
									$eintrag = $this->formvars[$form_fields[$i]];
                }
              }
            } # end of default case
          } # end of switch for type
					if($eintrag !== NULL){
						$updates[$layer_id][$tablename][$oid][$attributname] = $eintrag;
					}
        }
      }
    }
		if(count($document_attributes)> 0){
			foreach($document_attributes as $i => $attr_oid){
				# Dateiname erzeugen
				$name_array=explode('.',basename($_files[$form_fields[$i]]['name']));
				$datei_name=$name_array[0];
				$datei_erweiterung=array_pop($name_array);
				$doc_path = $mapdb->getDocument_Path($layerset[$attr_oid['layer_id']][0]['document_path'], $attributes['options'][$attr_oid['attributename']], $attributenames[$attr_oid['oid']], $$attributevalues[$attr_oid['oid']], $layerdb[$attr_oid['layer_id']]);
				$nachDatei = $doc_path.'.'.$datei_erweiterung;
				$eintrag = $nachDatei."&original_name=".$_files[$form_fields[$i]]['name'];
				if($datei_name == 'delete')$eintrag = '';
				# Bild in das Datenverzeichnis kopieren
				if (move_uploaded_file($_files[$form_fields[$i]]['tmp_name'],$nachDatei) OR $datei_name == 'delete') {
					#echo '<br>Lade '.$_files[$form_fields[$i]]['tmp_name'].' nach '.$nachDatei.' hoch';
					# Wenn eine alte Datei existiert, die nicht so heißt wie die neue --> löschen
					$old = $this->formvars[str_replace(';Dokument;', ';Dokument_alt;', $form_fields[$i])];
					if ($old != '' AND $old != $eintrag) {
						$this->deleteDokument($old);
					}
					$updates[$attr_oid['layer_id']][$attr_oid['tablename']][$attr_oid['oid']][$attr_oid['attributename']] = $eintrag;
				} # ende von Datei wurde erfolgreich in Datenverzeichnis kopiert
				else {
					echo '<br>Datei: '.$_files[$form_fields[$i]]['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
				}
			}
		}
		if($updates != NULL){
			foreach($updates as $layer_id => $layer) {
				foreach($layer as $tablename => $table) {
					foreach($table as $oid => $attributes) {
						if(count($attributes) > 0){
							if(!$layerset[$layer_id][0]['maintable_is_view'])$sql = "LOCK TABLE ".$tablename." IN SHARE ROW EXCLUSIVE MODE;";
							else $sql = '';
							$sql .= "UPDATE ".$tablename." SET ";
							$i = 0;
							foreach($attributes as $attribute => $value) {
								if($i > 0)$sql .= ', ';
								$sql .= $attribute." = ";
								if($value == 'NULL')$sql .= 'NULL';
								else $sql .= "'".$value."'";
								$i++;
							}
							$sql .= " WHERE oid = ".$oid;
							#if($filter != ''){							# erstmal wieder rausgenommen, weil der Filter sich auf Attribute beziehen kann, die zu anderen Tabellen gehören
							#  $sql .= " AND ".$filter;
							#}

							# Before Update trigger
							if (!empty($layer['trigger_function'])) {
								$this->exec_trigger_function('BEFORE', 'UPDATE', $layerset[$layer_id][0], $oid);
							}

							#echo '<br>sql for update: ' . $sql;

							$this->debug->write("<p>file:kvwmap class:sachdaten_speichern :",4);
							$ret = $layerdb[$layer_id]->execSQL($sql, 4, 1, true);

							if ($ret['success']) {
								$result = pg_fetch_row($ret['query']);
								if (pg_affected_rows($ret['query']) > 0) {
									# After Update trigger
									if (!empty($layerset[$layer_id][0]['trigger_function'])) {
										$this->exec_trigger_function('AFTER', 'UPDATE', $layerset[$layer_id][0], $oid);
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
								$this->add_message($ret['type'], $ret['msg']);
							}
						}
					}
				}
			}
			if ($this->success == false) {
				$this->add_message('error', 'Änderung fehlgeschlagen.<br>' . $result[0]);
			}
			else {
				if($this->formvars['close_window'] == ""){
					$this->add_message('notice', 'Änderung erfolgreich');
					if($result[0] != '')$this->add_message('warning', $result[0]);
				}
			}
		}
		else {
			$this->add_message('warning', 'Keine Änderung.');
		}
    if ($this->formvars['embedded'] != ''){    # wenn es ein Datensatz aus einem embedded-Formular ist, muss das entsprechende Attribut des Hauptformulars aktualisiert werden
      header('Content-type: text/html; charset=UTF-8');
      $attributenames[0] = $this->formvars['targetattribute'];
			$targetlayerdb = $mapdb->getlayerdatabase($this->formvars['targetlayer_id'], $this->Stelle->pgdbhost);
      $attributes = $mapdb->read_layer_attributes($this->formvars['targetlayer_id'], $targetlayerdb, $attributenames);
      switch ($attributes['form_element_type'][0]){
        case 'SubFormEmbeddedPK' : {
          $this->formvars['embedded_subformPK'] = true;
          echo '~';
          $this->GenerischeSuche_Suchen();
        }break;
      }
			echo '~';
			$this->output_messages('without_script_tags');
			if($this->formvars['reload']){			# in diesem Fall wird die komplette Seite neu geladen
				echo '~~';
				echo "document.GUI.go.value='get_last_query';
							document.GUI.submit();";
			}
    }
    else{
			$this->last_query = $this->user->rolle->get_last_query();
      if($this->formvars['search']){        # man kam von der Suche   -> nochmal suchen
        $this->GenerischeSuche_Suchen();
      }
      else{                                 # man kam aus einer Sachdatenabfrage    -> nochmal abfragen
        $this->queryMap();
      }
    }
  }

	function queryMap() {
		# scale ausrechnen, da wir uns das loadmap sparen
		$center_y = ($this->user->rolle->oGeorefExt->maxy + $this->user->rolle->oGeorefExt->miny) / 2;
		if($this->user->rolle->epsg_code == 4326){$unit = MS_DD;} else {$unit = MS_METERS;}
		$md = ($this->user->rolle->nImageWidth-1)/(96 * InchesPerUnit($unit, $center_y));
		$gd = $this->user->rolle->oGeorefExt->maxx - $this->user->rolle->oGeorefExt->minx;
		$this->map_scaledenom = round($gd/$md);
    # Abfragebereich berechnen
		if($this->formvars['querypolygon'] != ''){
			$rect = $this->formvars['querypolygon'];
		}
		else{
			if($this->formvars['rectminx'] != ''){			// ?????????
				$rect = ms_newRectObj();										// ?????????
				$rect->setextent($this->formvars['rectminx'],$this->formvars['rectminy'],$this->formvars['rectmaxx'],$this->formvars['rectmaxy']);		// ?????????
			}
			else{
				$rect = $this->create_query_rect($this->formvars['INPUT_COORD']);
			}
		}
    if($this->show_query_tooltip == true){
      $this->tooltip_query($rect);
    }
    else{
      $this->SachdatenAnzeige($rect);
			if($this->formvars['printversion'] != ''){
				$this->mime_type = 'printversion';
			}
			$this->output();
    }
  }

 # 2006-07-26 pk
	function SachdatenAnzeige($rect){
		$last_query_deleted = false;
		if($this->last_query != ''){
			foreach($this->last_query['layer_ids'] as $layer_id){
				$this->formvars['qLayer'.$layer_id] = 1;
			}
		}
    if(is_string($rect)){
      $this->querypolygon = $rect;
    }
    $this->queryrect = $rect;
    # Abfragen der Layer, die zur Stelle gehören
    $layer=$this->user->rolle->getLayer('');
		$rollenlayer=$this->user->rolle->getRollenLayer('', 'import');
		$layerset = array_merge($layer, $rollenlayer);
    $anzLayer=count($layerset);
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
    for ($i=0;$i<$anzLayer;$i++) {

    	$sql_order = ''; 
      if($layerset[$i]['queryable'] AND 
				($this->formvars['qLayer'.$layerset[$i]['Layer_ID']]=='1' OR $this->formvars['qLayer'.$layerset[$i]['requires']]=='1') 	AND 
				(($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] >= $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] <= $this->map_scaledenom)
				OR $this->last_query != '')) {
        # Dieser Layer soll abgefragt werden
        switch ($layerset[$i]['connectiontype']) {
          case MS_SHAPEFILE : { # Shape File Layer (1)
            if ($this->formvars['searchradius'] > 0 OR $this->querypolygon != '') {
              showAlert('Sie können für die Abfrage von Shape- und Rasterlayern nur die einfache Sachdatenabfrage verwenden.');
            }
            else{
              $layer=ms_newLayerObj($map);
              $layer->set('data', $layerset[$i]['Data']);
              $layer->set('status',MS_ON);
							$layer->set('type',$layerset[$i]['Datentyp']);
              if ($layerset[$i]['template']!='') {
                $layer->set('template',$layerset[$i]['template']);
              }
              else {
                $layer->set('template',DEFAULTTEMPLATE);
              }
              $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
    					$projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);
							$rect2=ms_newRectObj();
							$rect2->setextent($rect->minx,$rect->miny,$rect->maxx,$rect->maxy);
							$rect2->project($projFROM, $projTO);
							if($layerset[$i]['Datentyp'] == 3){		# bei Rasterlayern nur punktuell abfragen
								$point=ms_newPointObj();
								$point->setXY($rect2->minx+($rect2->maxx-$rect2->minx)/2, $rect2->miny+($rect2->maxy-$rect2->miny)/2);
								@$layer->queryByPoint($point, MS_MULTIPLE, 0);
							}
							else{
								@$layer->queryByRect($rect2);
							}
              $anzResult=$layer->getNumResults();
              for ($j=0;$j<$anzResult;$j++){
								$result = $layer->getResult($j);
								if(MAPSERVERVERSION < '600') $s = $layer->getFeature($result->shapeindex);
								else $s = $layer->getShape($result);
								$count = 0;
								foreach($s->values as $key => $value){
									if($layerset[$i]['Datentyp'] != 3 OR in_array($key, array('x','y','value_0'))){		# für Rasterlayer nur diese Daten ausgeben
										$layerset[$i]['shape'][$j][$key] = utf8_encode($value);
										$layerset[$i]['attributes']['name'][$count] = $key;
										$layerset[$i]['attributes']['privileg'][$count] = 0;
									}
									$count++;
								}
              }
              $this->qlayerset[]=$layerset[$i];
            }
          } break; # ende Layer ist ein Shapefile
          case MS_OGR : { # OGR Layer (4)
            $layer=ms_newLayerObj($map);
            if (MAPSERVERVERSION < '540') {
				      $layer->set('connectiontype',$layerset[$i]['connectiontype']);
				    }
				    else {
				      $layer->setConnectionType($layerset[$i]['connectiontype']);
				    }
            $layer->set('connection', $layerset[$i]['connection']);
            $layer->set('type',$layerset[$i]['Datentyp']);
            $layer->set('status',MS_ON);
            if ($layerset[$i]['template']!='') {
              $layer->set('template',$layerset[$i]['template']);
            }
            else {
              $layer->set('template',DEFAULTTEMPLATE);
            }
            @$layer->queryByRect($rect);
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
          case MS_POSTGIS : { # PostGIS Layer (6)
						if($layerset[$i]['pfad'] != ''){
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
							$layerdb->setClientEncoding();
							#$path = $layerset[$i]['pfad'];
							$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[$i]['pfad']);
							$path = str_replace('$language', $this->user->rolle->language, $path);
							$path = replace_params($path, rolle::$layer_params);

							$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
							$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames'], false, true);
							$newpath = $this->Stelle->parse_path($layerdb, $path, $privileges, $layerset[$i]['attributes']);
							
							# weitere Informationen hinzufügen (Auswahlmöglichkeiten, usw.)  ---> steht weiter unten

							# order by rausnehmen
							$orderbyposition = strrpos(strtolower($newpath), 'order by');
							$lastfromposition = strrpos(strtolower($newpath), 'from');
							if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
								$layerset[$i]['attributes']['orderby'] = ' '.substr($newpath, $orderbyposition);
								$newpath = substr($newpath, 0, $orderbyposition);
							}

							# group by rausnehmen
							$groupbyposition = strrpos(strtolower($newpath), 'group by');
							if($groupbyposition !== false){
								$layerset[$i]['attributes']['groupby'] = ' '.substr($newpath, $groupbyposition);
								$newpath = substr($newpath, 0, $groupbyposition);
							}

							if($privileges == NULL){    # kein Eintrag -> alle Attribute lesbar
								for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
									$layerset[$i]['attributes']['privileg'][$j] = '0';
									$layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = '0';
								}
							}
							else{
								for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
									$layerset[$i]['attributes']['privileg'][$j] = $privileges[$layerset[$i]['attributes']['name'][$j]];
									$layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['name'][$j]] = $privileges[$layerset[$i]['attributes']['name'][$j]];
								}
							}
							# Wenn kein Template --> generischer Layereditor: Pfad um oids der verwendeten Tabellen erweitern (erstmal testweise rausgenommen)
							#if($layerset[$i]['template'] == ''){
								$distinctpos = strpos(strtolower($newpath), 'distinct');
								if($distinctpos !== false && $distinctpos < 10){
									$pfad = substr(trim($newpath), $distinctpos+8);
									$distinct = true;
								}
								else{
									$pfad = substr(trim($newpath), 7);
								}
								$geometrie_tabelle = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
								$j = 0;
								foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
									if(($tablename == $layerset[$i]['maintable'] OR $tablename == $geometrie_tabelle) AND $layerset[$i]['attributes']['oids'][$j]){		# hat Haupttabelle oder Geometrietabelle oids?
										$pfad = $layerset[$i]['attributes']['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
									}
									$j++;
								}
								if($distinct == true){
									$pfad = 'DISTINCT '.$pfad;
								}
							#}
							#else{
							#  $pfad = substr(trim($newpath), 7);
							#}

							/*
							if(strpos(strtolower($pfad), 'as the_geom') !== false){
								$the_geom = 'query.the_geom';
							}
							else{
							*/
								if($layerset[$i]['attributes']['the_geom'] == ''){					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
									$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
									$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
								}
								/*if($layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']]){
									$the_geom = $layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$layerset[$i]['attributes']['the_geom'];
								}
								else{*/
									$the_geom = $layerset[$i]['attributes']['the_geom'];
							//  }
							//}
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
							
							if($client_epsg == 4326){
								$center_y = ($rect->maxy+$rect->miny)/2;
								$cos_lat = cos(pi() * $center_y/180.0);
								$lat_adj = sqrt(1 + $cos_lat * $cos_lat)/sqrt(2);
								$rand_in_metern = $layerset[$i]['tolerance'] * $pixsize * $lat_adj * 111000;
							}
							else $rand_in_metern = $rand;
							
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
									$sql_where =" AND ".$the_geom." && st_transform(st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
									$sql_where.=" AND st_distance(".$the_geom.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
								}
								else {
									$sql_where =" AND ".$the_geom." && st_geomfromtext('".$loosesearchbox_wkt."',".$client_epsg.")";
									$sql_where.=" AND st_distance(".$the_geom.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
								}
								$sql_where.=" <= ".$rand_in_metern;
							}
							# ---------- Suche über Polygon ---------- #
							else {
								# Behandlung der Suchanfrage mit Rechteck, exakte Suche im Rechteck
								if ($client_epsg!=$layer_epsg) {
									$sql_where =" AND st_intersects(".$the_geom.",st_transform(st_geomfromtext('".$searchbox_wkt."',".$client_epsg."),".$layer_epsg."))";
								}
								else {
									$sql_where =" AND st_intersects(".$the_geom.",st_geomfromtext('".$searchbox_wkt."',".$client_epsg."))";
								}
							}
							# Filter zur Where-Klausel hinzufügen
							if($layerset[$i]['Filter'] != ''){
								$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
								$sql_where .= " AND ".$layerset[$i]['Filter'];
							}
							if($this->formvars['CMD'] == 'touchquery'){
								if(substr_count(strtolower($pfad), ' from ') > 1){			# mehrere froms -> das FROM der Hauptabfrage muss groß geschrieben sein
									$fromposition = strpos($pfad, ' FROM ');
								}
								else{
									$fromposition = strpos(strtolower($pfad), ' from ');
								}
								$new_pfad = $the_geom." ".substr($pfad, $fromposition);
								#if($the_geom == 'query.the_geom'){
									$sql = "SELECT * FROM (SELECT ".$new_pfad.") as query WHERE 1=1 ".$sql_where;
								#}
								#else{
								#  $sql = "SELECT ".$new_pfad." ".$sql_where;
								#}
								$ret=$layerdb->execSQL($sql,4, 0);
								if(!$ret[0]){
									while($rs=pg_fetch_array($ret[1])){
										$geoms[]=$rs[0];
									}
								}
								if(count($geoms) > 0)$sql = '';
								for($g = 0; $g < count($geoms); $g++){
									if($g > 0)$sql .= " UNION ";
									$sql .= "SELECT ".$pfad." AND ".$the_geom." && ('".$geoms[$g]."') AND (st_intersects(".$the_geom.", ('".$geoms[$g]."'::geometry)) OR ".$the_geom." = ('".$geoms[$g]."'))";
								}
							}
							else{
								#if($the_geom == 'query.the_geom'){
									# group by wieder einbauen
									if($layerset[$i]['attributes']['groupby'] != ''){
										$pfad .= $layerset[$i]['attributes']['groupby'];
										$j = 0;
										foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
													if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['attributes']['oids'][$j]){		# hat Haupttabelle oids?
														$pfad .= ','.$tablename.'_oid ';
													}
													$j++;
										}
									}
									$sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
								#}
								/*else{
									$sql = "SELECT ".$pfad." ".$sql_where;
									# group by wieder einbauen
									if($layerset[$i]['attributes']['groupby'] != ''){
										$sql .= $layerset[$i]['attributes']['groupby'];
										$j = 0;
										foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
													if($layerset[$i]['attributes']['oids'][$j]){      # hat Tabelle oids?
														$sql .= ','.$tablename.'_oid ';
													}
													$j++;
										}
									}
								}*/
							}

							# order by
							if($this->formvars['orderby'.$layerset[$i]['Layer_ID']] != ''){									# Fall 1: im GLE soll nach einem Attribut sortiert werden
								$sql_order = ' ORDER BY '.$this->formvars['orderby'.$layerset[$i]['Layer_ID']];
							}
							elseif($layerset[$i]['attributes']['orderby'] != ''){														# Fall 2: der Layer hat im Pfad ein ORDER BY
								$sql_order = $layerset[$i]['attributes']['orderby'];
							}
							if($layerset[$i]['template'] == ''){																				# standardmäßig wird nach der oid sortiert
								$j = 0;
								foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
									if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['attributes']['oids'][$j]){      # hat die Haupttabelle oids, dann wird immer ein order by oid gemacht, sonst ist die Sortierung nicht eindeutig
										if($sql_order == '')$sql_order = ' ORDER BY '.$layerset[$i]['maintable'].'_oid ';
										else $sql_order .= ', '.$layerset[$i]['maintable'].'_oid ';
									}
									$j++;
								}
							}
							if($this->last_query != '' AND $this->last_query[$layerset[$i]['Layer_ID']]['sql'] != ''){
								$sql = $this->last_query[$layerset[$i]['Layer_ID']]['sql'];
								if($this->formvars['orderby'.$layerset[$i]['Layer_ID']] == '')$sql_order = $this->last_query[$layerset[$i]['Layer_ID']]['orderby'];
								$this->formvars['anzahl'] = $this->last_query[$layerset[$i]['Layer_ID']]['limit'];
								if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] == '')$this->formvars['offset_'.$layerset[$i]['Layer_ID']] = $this->last_query[$layerset[$i]['Layer_ID']]['offset'];
							}

							# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
							if($this->formvars['anzahl'] == ''){
								$this->formvars['anzahl'] = MAXQUERYROWS;
							}
							$sql_limit =' LIMIT '.$this->formvars['anzahl'];
							if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] != ''){
								$sql_limit.=' OFFSET '.$this->formvars['offset_'.$layerset[$i]['Layer_ID']];
							}

							$layerset[$i]['sql'] = $sql;

							$ret=$layerdb->execSQL($sql.$sql_order.$sql_limit,4, 0);
							#echo $sql.$sql_order.$sql_limit;
							if (!$ret[0]) {
								while ($rs=pg_fetch_assoc($ret[1])) {
									$layerset[$i]['shape'][]=$rs;
								}
								$num_rows = pg_num_rows($ret[1]);
								if($this->formvars['offset_'.$layerset[$i]['Layer_ID']] == '' AND $num_rows < $this->formvars['anzahl'])$layerset[$i]['count'] = $num_rows;
								else{
									# Anzahl der Datensätze abfragen
									$sql_count = "SELECT count(*) FROM (".$sql.") as foo";
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
								if(!$last_query_deleted){			# damit nur die letzte Query gelöscht wird und nicht eine bereits gespeicherte Query eines anderen Layers der aktuellen Abfrage
									$this->user->rolle->delete_last_query();
									$last_query_deleted = true;
								}
								$this->user->rolle->save_last_query('Sachdaten', $layerset[$i]['Layer_ID'], $sql, $sql_order, $this->formvars['anzahl'], $this->formvars['offset_'.$layerset[$i]['Layer_ID']]);

								# Querymaps erzeugen
								if($layerset[$i]['querymap'] == 1 AND $layerset[$i]['attributes']['privileg'][$layerset[$i]['attributes']['the_geom']] >= '0' AND ($layerset[$i]['Datentyp'] == 1 OR $layerset[$i]['Datentyp'] == 2)){
									for($k = 0; $k < count($layerset[$i]['shape']); $k++){
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
            $request = str_replace('getmap','GetFeatureInfo',strtolower($request));
            $request = $request.'&REQUEST=GetFeatureInfo&SERVICE=WMS';

            # Anzufragenden Layernamen
						if(strpos(strtolower($request), 'query_layers') === false){
							$reqStr=explode('&',strstr(strtolower($request),'layers='));
							$layerStr=explode('=',$reqStr[0]);
							$request .='&QUERY_LAYERS='.$layerStr[1];
						}

            # Boundingbox im System des Layers anhängen
            $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
            $projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);

            $bbox=ms_newRectObj();
            $bbox->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);

            $bbox = $this->pgdatabase->transformRect($bbox,$this->user->rolle->epsg_code,$layerset[$i]['epsg_code']);
            $bbox = $bbox[1];

            #$bbox->project($projFROM, $projTO);
            #echo $bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy.'<br>';

            $request .='&BBOX='.$bbox->minx.','.$bbox->miny.','.$bbox->maxx.','.$bbox->maxy;
            $request .='&WIDTH='.$this->user->rolle->nImageWidth.'&HEIGHT='.$this->user->rolle->nImageHeight;

            # EPSG-Code anhängen
            $request .='&SRS=EPSG:'.$layerset[$i]['epsg_code'];

            # Anfrageposition anhängen
            $imgxy=explode(';',$this->formvars['INPUT_COORD']);
            $minxy=explode(',',$imgxy[0]);
            $maxxy=explode(',',$imgxy[1]);
            $x=($maxxy[0]+$minxy[0])/2;
            $y=($maxxy[1]+$minxy[1])/2;
            $request .='&X='.$x.'&Y='.$y;

            # Ausgabeformat
            if(strpos(strtolower($request), 'info_format') === false){
            	$request .='&INFO_FORMAT=text/html';
            }

            $layerset[$i]['GetFeatureInfoRequest']=$request;
            #echo $request;

            $this->qlayerset[]=$layerset[$i];
          }  break;

          case MS_WFS : { # WFS Layer (9)
						include_(CLASSPATH.'wfs.php');
            switch ($layerset[$i]['toleranceunits']) {
              case 'pixels' : $pixsize=$this->user->rolle->pixsize; break;
              case 'meters' : $pixsize=1; break;
              default : $pixsize=$this->user->rolle->pixsize;
            }
            if($rect->minx == $rect->maxx AND $rect->miny == $rect->maxy){
            	$rand=$layerset[$i]['tolerance']*$pixsize;
            }
            $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
            $projTO = ms_newprojectionobj("init=epsg:".$layerset[$i]['epsg_code']);
            $rect->project($projFROM, $projTO);
            $searchbox_minx=strval($rect->minx-$rand);
            $searchbox_miny=strval($rect->miny-$rand);
            $searchbox_maxx=strval($rect->maxx+$rand);
            $searchbox_maxy=strval($rect->maxy+$rand);

						$bbox=$searchbox_minx.','.$searchbox_miny.','.$searchbox_maxx.','.$searchbox_maxy;
            $url = $layerset[$i]['connection'];
						$version = $layerset[$i]['wms_server_version'];
						$epsg = $layerset[$i]['epsg_code'];
            $typename = $layerset[$i]['wms_name'];
						$namespace = substr($typename, 0, strpos($typename, ':'));
						$username = $layerset[$i]['wms_auth_username'];
						$password = $layerset[$i]['wms_auth_password'];
						$wfs = new wfs($url, $version, $typename, $namespace, $epsg, $username, $password);
            # Attributnamen ermitteln
            $wfs->describe_featuretype_request();
            $layerset[$i]['attributes'] = $wfs->get_attributes();
            # Abfrage absetzen
            $wfs->get_feature_request($bbox, NULL, MAXQUERYROWS);
            $features = $wfs->extract_features();
            for($j = 0; $j < count($features); $j++){
              for($k = 0; $k < count($layerset[$i]['attributes']['name']); $k++){
								$layerset[$i]['shape'][$j][$layerset[$i]['attributes']['name'][$k]] = $features[$j]['value'][$layerset[$i]['attributes']['name'][$k]];
                $layerset[$i]['attributes']['privileg'][$k] = 0;
              }
              $layerset[$i]['shape'][$j]['wfs_geom'] = $features[$j]['geom'];
            }
            $this->qlayerset[]=$layerset[$i];
          } break;

          default : { # alle anderen Layertypen
            echo 'Die Sachdatenabfrage für den connectiontype: '.$layerset[$i]['connectiontype'].' wird nicht unterstützt.';
          }
        } # ende Switch
      } # ende der Behandlung der zur Abfrage ausgewählten Layer
    } # ende der Schleife zur Abfrage der Layer der Stelle

		if($this->last_query_requested AND $this->last_query != '' AND $this->user->rolle->querymode == 1){		# bei get_last_query und aktivierter Datenabfrage in extra Fenster --> Laden der Karte und zoom auf Treffer (das Zeichnen der Karte passiert in einem separaten Ajax-Request aus dem Overlay heraus)
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
						$rect = $this->mapDB->zoomToDatasets($oids, $geometrie_tabelle, $attributes['the_geom'], 10, $layerdb, $this->qlayerset[0]['epsg_code'], $this->user->rolle->epsg_code);
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
			if($this->formvars['mime_type'] != 'overlay_html'){		// bei Suche aus normaler Suchmaske (nicht aus Overlay) heraus --> Zeichnen der Karte und Darstellung der Sachdaten im Overlay
				$this->drawMap();
				$this->main = 'map.php';
				$this->overlaymain = 'sachdatenanzeige.php';
			}
		}
		else $this->main='sachdatenanzeige.php';
  }

  function WLDGE_Auswaehlen() {
    $this->debug->write("kvwmap.php WLDGE_Auswaehlen",4);
    $this->titel='WLDGE Datei auswählen';
    $this->main='wldgedateiauswahl.php';
  }

  function createReferenceMap($width, $height, $refwidth, $refheight, $angle, $minx, $miny, $maxx, $maxy, $zoomfactor, $refmapfile){
    $refmap = ms_newMapObj($refmapfile);
    $refmap->set('width', $width);
    $refmap->set('height', $height);
    $refmap->setextent($minx,$miny,$maxx,$maxy);
    $projFROM = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
    $projTO = ms_newprojectionobj("init=epsg:".EPSGCODE);
    $refmap->extent->project($projFROM, $projTO);
    # zoomen
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($width/2,$height/2);
    //$refmap->zoomscale($scale,$oPixelPos,$width,$height,$refmap->extent,$this->Stelle->MaxGeorefExt);
    $refmap->zoompoint($zoomfactor,$oPixelPos,$width,$height,$refmap->extent);

    if($refmap->selectOutputFormat('jpeg_print') == 1){
      $refmap->selectOutputFormat('jpeg');
    }
    $image_map = $refmap->draw();
    $filename = $this->map_saveWebImage($image_map,'jpeg');


    $image = imagecreatefromjpeg(IMAGEPATH.basename($filename));
		if($angle != 0){
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
		$color = imagecolorallocate($image, 0,0,0);
		$x1 = ($refwidth + $refwidth/$zoomfactor)/2;
		$y1 = ($refheight + $refheight/$zoomfactor)/2;
		$x2 = $x1 - $refwidth/$zoomfactor;
		$y2 = $y1 - $refheight/$zoomfactor;
		imagerectangle($image, $x1, $y1, $x2, $y2, $color);
    imagejpeg($image, IMAGEPATH.basename($filename), 100);
    $newname = $this->user->id.basename($filename);
    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
    $uebersichtskarte = IMAGEURL.$newname;
    return $uebersichtskarte;
  }

  function spatial_processing(){
		include_(CLASSPATH.'spatial_processor.php');
    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
		if(in_array($this->formvars['operation'], array('area', 'length'))){
			$layerdb = $this->pgdatabase;				# wegen st_area_utm und st_length_utm die eigene Datenbank nehmen
		}
		else{
			$layerdb = $mapDB->getlayerdatabase($this->formvars['layer_id'], $this->Stelle->pgdbhost);
			if($layerdb == NULL){
				$layerdb = $this->pgdatabase;
			}
		}
    $this->processor = new spatial_processor($this->user->rolle, $this->database, $layerdb);
    $this->processor->process_query($this->formvars);
  }

  function getRow() {
		$this->formvars['select'] = str_replace("''", "'", $this->formvars['select']);
		$this->formvars['where'] = str_replace("''", "'", $this->formvars['where']);
    $ret=$this->database->getRow($this->formvars['select'],$this->formvars['from'],$this->formvars['where']);
    $first=1;
    while (list($key, $val) = each($ret[1])) {
      if (!$first) {
        echo "~";
      }
      echo $val;
      $first=0;
    }
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
      $this->mapobject = ms_newMapObj($formvars['mapfilename']);

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
        $this->mapobject = ms_newMapObj($nachDatei);
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
      $this->mapobject = ms_newMapObj($formvars['zipmapfile']);
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
		if($this->formvars['querylayer_id'] != '' AND $this->formvars['querylayer_id'] != 'undefined'){
			if($this->formvars['querylayer_id'] < 0)$layerset=$this->user->rolle->getRollenLayer(-$this->formvars['querylayer_id']);
			else $layerset = $this->user->rolle->getLayer($this->formvars['querylayer_id']);
			$this->formvars['qLayer'.$this->formvars['querylayer_id']] = '1';
		}
		else{
			$layerset = $this->user->rolle->getLayer('');
		}
		$layerset = array_reverse($layerset);
    $anzLayer=count($layerset);
    $map=ms_newMapObj('');
    $map->set('shapepath', SHAPEPATH);
		$found = false;
    for ($i=0;$i<$anzLayer;$i++) {
			if($found)break;		# wenn in einem Layer was gefunden wurde, abbrechen
			if($this->formvars['qLayer'.$layerset[$i]['Layer_ID']]=='1' AND ($layerset[$i]['maxscale'] == 0 OR $layerset[$i]['maxscale'] > $this->map_scaledenom) AND ($layerset[$i]['minscale'] == 0 OR $layerset[$i]['minscale'] < $this->map_scaledenom)){
				# Dieser Layer soll abgefragt werden
				if($layerset[$i]['alias'] != '' AND $this->Stelle->useLayerAliases){
					$layerset[$i]['Name'] = $layerset[$i]['alias'];
				}
				$layerdb = $this->mapDB->getlayerdatabase($layerset[$i]['Layer_ID'], $this->Stelle->pgdbhost);
				#$path = $layerset[$i]['pfad'];
				$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[$i]['pfad']);
				$path = str_replace('$language', $this->user->rolle->language, $path);
				$path = replace_params($path, rolle::$layer_params);

				$privileges = $this->Stelle->get_attributes_privileges($layerset[$i]['Layer_ID']);
				#$path = $this->Stelle->parse_path($layerdb, $path, $privileges);
				$layerset[$i]['attributes'] = $this->mapDB->read_layer_attributes($layerset[$i]['Layer_ID'], $layerdb, $privileges['attributenames']);

				# order by rausnehmen
				$orderbyposition = strrpos(strtolower($path), 'order by');
				$lastfromposition = strrpos(strtolower($path), 'from');
				if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
					$layerset[$i]['attributes']['orderby'] = ' '.substr($path, $orderbyposition);
					$path = substr($path, 0, $orderbyposition);
				}

				# group by rausnehmen
				$groupbyposition = strrpos(strtolower($path), 'group by');
				if($groupbyposition !== false){
					$layerset[$i]['attributes']['groupby'] = ' '.substr($path, $groupbyposition);
					$path = substr($path, 0, $groupbyposition);
				}

				if($rect->minx != ''){	####### Kartenabfrage
					$show = false;
					for($j = 0; $j < count($layerset[$i]['attributes']['name']); $j++){
						$layerset[$i]['attributes']['tooltip'][$j] = $privileges['tooltip_'.$layerset[$i]['attributes']['name'][$j]];
						if($layerset[$i]['attributes']['tooltip'][$j] == 1){
							$show = true;
						}
					}
					if(!$show){
						return NULL;
					}
				}

				$distinctpos = strpos(strtolower($path), 'distinct');
				if($distinctpos !== false && $distinctpos < 10){
					$pfad = substr(trim($path), $distinctpos+8);
					$distinct = true;
				}
				else{
					$pfad = substr(trim($path), 7);
				}
				$geometrie_tabelle = $layerset[$i]['attributes']['table_name'][$layerset[$i]['attributes']['the_geom']];
				$j = 0;
				foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
					if(($tablename == $layerset[$i]['maintable'] OR $tablename == $geometrie_tabelle) AND $layerset[$i]['attributes']['oids'][$j]){		# hat Haupttabelle oder Geometrietabelle oids?
						$pfad = $layerset[$i]['attributes']['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
					}
					$j++;
				}
				if($distinct == true){
					$pfad = 'DISTINCT '.$pfad;
				}

				/*if(strpos(strtolower($pfad), 'as the_geom') !== false){
					$the_geom = 'query.the_geom';
				}
				else{*/
					if($layerset[$i]['attributes']['the_geom'] == ''){					# Geometriespalte ist nicht geladen, da auf "nicht sichtbar" gesetzt --> aus Data holen
						$data_attributes = $this->mapDB->getDataAttributes($layerdb, $layerset[$i]['Layer_ID']);
						$layerset[$i]['attributes']['the_geom'] = $data_attributes['the_geom'];
					}
					/*if($layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']]){
						$the_geom = $layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$layerset[$i]['attributes']['the_geom'];
					}
					else{*/
						$the_geom = $layerset[$i]['attributes']['the_geom'];
				//  }
				//}

				//$the_geom = $layerset[$i]['attributes']['the_geom'];

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
					$sql_where = " AND ".$geometrie_tabelle."_oid = ".$this->formvars['oid'];
				}

				# SVG-Geometrie abfragen für highlighting
				if($this->user->rolle->highlighting == '1'){
					if($layerset[$i]['attributes']['geomtype'][$the_geom] != 'POINT'){
						$rand = $this->map_scaledenom/1000;
						$tolerance = $this->map_scaledenom/10000;
						if($layer_epsg == 4326)$tolerance = $tolerance / 60000;		# wegen der Einheit Grad
						$box_wkt ="POLYGON((";
						$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
						$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand).",";
						$box_wkt.=strval($this->user->rolle->oGeorefExt->maxx+$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
						$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->maxy+$rand).",";
						$box_wkt.=strval($this->user->rolle->oGeorefExt->minx-$rand)." ".strval($this->user->rolle->oGeorefExt->miny-$rand)."))";
						$pfad = "st_assvg(st_transform(st_simplify(st_intersection(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", st_transform(st_geomfromtext('".$box_wkt."',".$client_epsg."), ".$layer_epsg.")), ".$tolerance."), ".$client_epsg."), 0, 8) AS highlight_geom, ".$pfad;
					}
					else{
						$buffer = $this->map_scaledenom/260;
						$pfad = "st_assvg(st_buffer(st_transform(".$layerset[$i]['attributes']['table_alias_name'][$layerset[$i]['attributes']['the_geom']].'.'.$the_geom.", ".$client_epsg."), ".$buffer."), 0, 8) AS highlight_geom, ".$pfad;
					}
				}

				# 2006-06-12 sr   Filter zur Where-Klausel hinzugefügt
				if($layerset[$i]['Filter'] != ''){
					$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
					$sql_where .= " AND ".$layerset[$i]['Filter'];
				}
				#if($the_geom == 'query.the_geom'){
					$sql = "SELECT * FROM (SELECT ".$pfad.") as query WHERE 1=1 ".$sql_where;
				/*}
				else{
					$sql = "SELECT ".$pfad." ".$sql_where;
				}
				*/

				# group by wieder einbauen
				if($layerset[$i]['attributes']['groupby'] != ''){
					$sql .= $layerset[$i]['attributes']['groupby'];
					$j = 0;
					foreach($layerset[$i]['attributes']['all_table_names'] as $tablename){
						if($tablename == $layerset[$i]['maintable'] AND $layerset[$i]['attributes']['oids'][$j]){		# hat Haupttabelle oids?
							$sql .= ','.$tablename.'_oid ';
						}
						$j++;
					}
				}

				# order by wieder einbauen
				if($layerset[$i]['attributes']['orderby'] != ''){										#  der Layer hat im Pfad ein ORDER BY
					$sql .= $layerset[$i]['attributes']['orderby'];
				}

				# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
				$sql_limit =' LIMIT '.MAXQUERYROWS;

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
    if($found AND $this->show_query_tooltip == true){
      for($i = 0; $i < count($this->qlayerset); $i++) {
      	$layer = $this->qlayerset[$i];
				$output .= $layer['Name'].' : || ';
 				$attributes = $layer['attributes'];
        $anzObj = count($layer['shape']);
        for($k = 0; $k < $anzObj; $k++) {
          $attribcount = 0;
					$highlight_geom .= $layer['shape'][$k]['highlight_geom'].' ';
          for($j = 0; $j < count($attributes['name']); $j++){
            if($attributes['tooltip'][$j]){
							if($attributes['alias'][$j] == '')$attributes['alias'][$j] = $attributes['name'][$j];
            	switch ($attributes['form_element_type'][$j]){
				        case 'Dokument' : {
									$dokumentpfad = $layer['shape'][$k][$attributes['name'][$j]];
									$pfadteil = explode('&original_name=', $dokumentpfad);
									$dateiname = $pfadteil[0];
									$original_name = $pfadteil[1];
									$dateinamensteil=explode('.', $dateiname);
									$type = $dateinamensteil[1];
									$thumbname = $this->get_dokument_vorschau($dateinamensteil);
									$this->allowed_documents[] = addslashes($dateiname);
									$this->allowed_documents[] = addslashes($thumbname);
									$url = IMAGEURL.$this->document_loader_name.'?dokument=';
									$pictures .= '| '.$url.$thumbname;
				        }break;
				        case 'Link': {
		              $attribcount++;
									if($layer['shape'][$k][$attributes['name'][$j]]!='') {
										$link = 'xlink:'.$layer['shape'][$k][$attributes['name'][$j]];
										$links .= $link.'##';
									}
								} break;
								case 'Auswahlfeld': {
									if(is_array($attributes['dependent_options'][$j])){		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
										for($e = 0; $e < count($attributes['enum_value'][$j][$k]); $e++){
											if($attributes['enum_value'][$j][$k][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
												$auswahlfeld_output = $attributes['enum_output'][$j][$k][$e];
												break;
											}
										}
									}
									else{
										for($e = 0; $e < count($attributes['enum_value'][$j]); $e++){
											if($attributes['enum_value'][$j][$e] == $layer['shape'][$k][$attributes['name'][$j]]){
												$auswahlfeld_output = $attributes['enum_output'][$j][$e];
												break;
											}
										}
									}
									$output .=  $attributes['alias'][$j].': ';
									$output .= $auswahlfeld_output;
									$output .= '##';
									$attribcount++;
								} break;
								case 'Checkbox': {
		              $layer['shape'][$k][$attributes['name'][$j]] = str_replace('f', 'nein',  $layer['shape'][$k][$attributes['name'][$j]]);
									$layer['shape'][$k][$attributes['name'][$j]] = str_replace('t', 'ja',  $layer['shape'][$k][$attributes['name'][$j]]);
								}
				        default : {
		              $output .=  $attributes['alias'][$j].': ';
		              $attribcount++;
									$layer['shape'][$k][$attributes['name'][$j]] = str_replace(chr(10), '##',  $layer['shape'][$k][$attributes['name'][$j]]);
		              $output .= $layer['shape'][$k][$attributes['name'][$j]].'  ';
		              $output .= '##';
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
      echo umlaute_javascript(umlaute_html($output)).'~showtooltip(top.document.GUI.result.value, '.$showdata.');';
    }
  }

	function setFullExtent() { 		$this->map->setextent($this->Stelle->MaxGeorefExt->minx,$this->Stelle->MaxGeorefExt->miny,$this->Stelle->MaxGeorefExt->maxx,$this->Stelle->MaxGeorefExt->maxy);
	}

  function zoomToALKGemeinde($Gemeinde,$border) {
    # 2006-01-31 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemeinde aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemeinde in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_(CLASSPATH.'alk.php');
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGemeinde($Gemeinde, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Gemeinde gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
      $this->adresswahl();
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemObj=new Gemeinde($Gemeinde,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflur as fl";
    $datastring.=",alb_v_gemarkungen AS g WHERE o.objnr=fl.objnr AND fl.gemkgschl::integer=g.gemkgschl";
    $datastring.=" AND g.gemeinde=".(int)$Gemeinde;
    $datastring.=") as foo using unique oid using srid=".EPSGCODE;
    $legendentext ="Gemeinde: ".$GemObj->getGemeindeName($Gemeinde);
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    $connectionstring.=' password='.$this->pgdatabase->passwd;
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0'); #2005-11-30_pk
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToGemeinde($GemID,$border) {
    $Gemeinde=new Gemeinde($GemID);
    # 1. Anlegen eines neuen Layers für die Suche nach Gemeinde
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gemeinde->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name','Gemeinde: '.$Gemeinde->getGemeindeName());
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression('([GEMEINDE_L]='.$GemID.')');
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(200,0,0);
    # 2. zoom auf eine Gemeinde
    $this->setFullExtent();
    $rect=$Gemeinde->getMER($layer);
    if ($rect==0) {
      $this->Fehlermeldung='Diese Gemeinde konnte nicht gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToGemarkung($GemID,$GemkgID,$border) {
    $Gemarkung=new Gemarkung($GemkgID,$this->database);
    # 1. Anlegen eines neuen Layers für die Suche nach Gemarkung
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gemarkung->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name','Gemarkung: '.$Gemarkung->getGemkgName());
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression('([GEMARKUNG_]='.$GemkgID.')');
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(200,0,0);
    # 2. zoom auf eine Gemarkung
    $this->setFullExtent();
    $rect=$Gemarkung->getMER($layer);
    if ($rect==0) {
      $this->Fehlermeldung='Diese Gemarkung konnte nicht gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToALKGemarkung($Gemkgschl,$border) {
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_(CLASSPATH.'alk.php');
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGemarkung($Gemkgschl, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Gemarkung gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
      $this->flurstwahl();
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemkgObj=new Gemarkung($Gemkgschl,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (SELECT 1 as id, st_multi(st_buffer(st_union(wkb_geometry), 0.1)) as the_geom FROM alkis.ax_flurstueck ";
    $datastring.="WHERE land*10000 + gemarkungsnummer = ".$Gemkgschl;
		$datastring.=" AND CASE WHEN '\$hist_timestamp' = '' THEN endet IS NULL ELSE beginnt::text <= '\$hist_timestamp' and ('\$hist_timestamp' <= endet::text or endet IS NULL) END";
    $datastring.=") as foo using unique id using srid=".EPSGCODE_ALKIS;
    $legendentext ="Gemarkung: ".$GemkgObj->getGemkgName($Gemkgschl);
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    $connectionstring.=' password='.$this->pgdatabase->passwd;
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0'); #2005-11-30_pk
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToALKFlur($GemID,$GemkgID,$FlurID,$border) {
    # 2006-02-01 pk
    # 1. Funktion ermittelt das umschließende Rechteck der $Gemarkung aus der postgis Datenbank
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gemarkung in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_(CLASSPATH.'alk.php');
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromFlur($GemkgID,$FlurID, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnte keine Flur gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # zu 3)
    $GemkgObj=new Gemarkung($GemkgID,$this->pgdatabase);
    $layer=ms_newLayerObj($this->map);
    $datastring ="the_geom from (SELECT 1 as id, st_multi(st_buffer(st_union(wkb_geometry), 0.1)) as the_geom FROM alkis.ax_flurstueck ";
    $datastring.="WHERE land*10000 + gemarkungsnummer = ".$GemkgID;
    $datastring.=" AND flurnummer = ".(int)$FlurID;
		$datastring.=" AND CASE WHEN '\$hist_timestamp' = '' THEN endet IS NULL ELSE beginnt::text <= '\$hist_timestamp' and ('\$hist_timestamp' <= endet::text or endet IS NULL) END";
    $datastring.=") as foo using unique id using srid=".EPSGCODE_ALKIS;
    $legendentext ="Gemarkung: ".$GemkgObj->getGemkgName($GemkgID);
    $legendentext .="<br>Flur: ".$FlurID;
    $layer->set('data',$datastring);
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$legendentext);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    if (MAPSERVERVERSION < '540') {
      $layer->set('connectiontype', 6);
    }
    else {
      $layer->setConnectionType(6);
    }
    $connectionstring ='user='.$this->pgdatabase->user;
    if($this->pgdatabase->passwd != ''){
      $connectionstring.=' password='.$this->pgdatabase->passwd;
    }
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $connectionstring.=' port=5432';
    $layer->set('connection',$connectionstring);
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    $style->outlinecolor->setRGB(0,0,0);
  }

  function zoomToALKFlurst($FlurstListe,$border){
		include_(CLASSPATH.'alk.php');
		$dbmap = new db_mapObj($this->Stelle->id,$this->user->id, $this->database);
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromFlurstuecke($FlurstListe, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine Flurstücke gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
		$epsg = EPSGCODE_ALKIS;
		$layerset = $this->user->rolle->getLayer(LAYERNAME_FLURSTUECKE);
		$data = $layerset[0]['Data'];
		if($data == '')$data ="the_geom from (select f.gml_id as oid, wkb_geometry as the_geom from alkis.ax_flurstueck as f where 1=1) as foo using unique oid using srid=".$epsg;
		$explosion = explode(' ', $data);
		$datageom = $explosion[0];
		$explosion = explode('using unique ', strtolower($data));
		$end = $explosion[1];
		$select = $dbmap->getSelectFromData($data);
		$whereposition = strpos(strtolower($select), ' where ');
		$withoutwhere = substr($select, 0, $whereposition);
		$fromposition = strpos(strtolower($withoutwhere), ' from ');
		$alias = $this->pgdatabase->get_table_alias('alkis.ax_flurstueck', $fromposition, $withoutwhere);
		$orderbyposition = strpos(strtolower($select), ' order by ');
		if($orderbyposition > 0)$select = substr($select, 0, $orderbyposition);
		if(strpos(strtolower($select), ' where ') === false)$select .= " WHERE ";
		else $select .= " AND ";
		$datastring = $datageom." from (".$select;
		$datastring.=" ".$alias.".flurstueckskennzeichen IN ('".$FlurstListe[0]."' ";
    $legendentext="Flurstück";
    if(count($FlurstListe) > 1)$legendentext .= "e";
    $legendentext .= " (".date('d.m. H:i',time())."):<br>".$FlurstListe[0];
    for ($i=1;$i<count($FlurstListe);$i++) {
      $datastring.=",'".$FlurstListe[$i]."'";
      $legendentext.=",<br>".$FlurstListe[$i];
    }
   	$datastring.=") ";
		$datastring.=" AND CASE WHEN '\$hist_timestamp' = '' THEN endet IS NULL ELSE beginnt::text <= '\$hist_timestamp' and ('\$hist_timestamp' <= endet::text or endet IS NULL) END";
		# Filter
		$filter = $dbmap->getFilter($layerset[0]['Layer_ID'], $this->Stelle->id);
		if($filter != '')$datastring.= ' AND '.$filter;
		$datastring.=") as foo using unique ".$end;
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
    $this->formvars['Datentyp'] = 2;
    $this->formvars['Data'] = $datastring;
    $this->formvars['connectiontype'] = 6;
    $connectionstring ='user='.$this->pgdatabase->user;
    if($this->pgdatabase->passwd != ''){
      $connectionstring.=' password='.$this->pgdatabase->passwd;
    }
    if($this->pgdatabase->host != ''){
      $connectionstring.=' host='.$this->pgdatabase->host;
    }
    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
    $this->formvars['connection'] = $connectionstring;
    $this->formvars['epsg_code'] = $epsg;
    $this->formvars['transparency'] = 60;

    $layer_id = $dbmap->newRollenLayer($this->formvars);

    $classdata['layer_id'] = -$layer_id;
    $class_id = $dbmap->new_Class($classdata);

		$color = $this->user->rolle->readcolor();
    $style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];
    $style['outlinecolorred'] = 0;
    $style['outlinecolorgreen'] = 0;
    $style['outlinecolorblue'] = 0;
    $style['size'] = 1;
    $style['symbol'] = NULL;
    $style['symbolname'] = NULL;
    $style['backgroundcolor'] = NULL;
    $style['minsize'] = NULL;
    $style['maxsize'] = 100000;
    if (MAPSERVERVERSION > '500') {
    	$style['angle'] = 360;
    }
    $style_id = $dbmap->new_Style($style);

    $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
    $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen

    $this->loadMap('DataBase');
    # zu 2)
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomToALKGebaeude($Gemeinde,$Strasse,$StrName,$Hausnr,$border) {
    # 2006-01-31 pk
    # 1. Funktion ermittelt das umschließende Rechteck der mit $Gemeinde,$Strasse und $Hausnr übergebenen
    # Gebaeude aus der postgis Datenbank mit Rand entsprechend dem Faktor $border
    # 2. zoom auf diese Rechteck
    # 3. und stellt die Gebaeude in einem gesonderten Layer in Gelb dar
    # zu 1)
		include_(CLASSPATH.'alk.php');
    $alk=new ALK();
    $alk->database=$this->pgdatabase;
    $ret=$alk->getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $this->user->rolle->epsg_code);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine Gebäude gefunden werden.<br>'.$ret[1];
      $rect=$this->user->rolle->oGeorefExt;
    }
    else {
      $rect=$ret[1];
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;

	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
	    # zu 3)
			$epsg = EPSGCODE_ALKIS;
			$datastring ="the_geom from (select g.gml_id as oid, wkb_geometry as the_geom FROM alkis.ax_gemeinde gem, alkis.ax_gebaeude g";
			$datastring.=" LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = any(g.zeigtauf)";
			$datastring.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
			$datastring.=" AND l.lage = lpad(s.lage,5,'0')";
			$datastring.=" WHERE gem.gemeinde = l.gemeinde";
			if ($Hausnr!='') {
				$Hausnr = str_replace(", ", ",", $Hausnr);
				$Hausnr = strtolower(str_replace(",", "','", $Hausnr));
				$datastring.=" AND gem.schluesselgesamt||'-'||l.lage||'-'||TRIM(LOWER(l.hausnummer)) IN ('".$Hausnr."')";
			}
			else{
				$datastring.=" AND gem.schluesselgesamt=".(int)$Gemeinde;
				if ($Strasse!='') {
					$datastring.=" AND l.lage='".$Strasse."'";
				}
			}
			$datastring.=" AND CASE WHEN '\$hist_timestamp' = '' THEN g.endet IS NULL ELSE g.beginnt::text <= '\$hist_timestamp' and ('\$hist_timestamp' <= g.endet::text or g.endet IS NULL) END";
			$datastring.=" AND CASE WHEN '\$hist_timestamp' = '' THEN gem.endet IS NULL ELSE gem.beginnt::text <= '\$hist_timestamp' and ('\$hist_timestamp' <= gem.endet::text or gem.endet IS NULL) END";
	    $datastring.=") as foo using unique oid using srid=".$epsg;
	    $legendentext ="Geb&auml;ude<br>";
	    if ($Hausnr!='') {
	      $legendentext.="HausNr: ".str_replace(',', '<br>', $Hausnr);
	    }
	    else{
	    	$legendentext.=$StrName;
	    }

	    $dbmap = new db_mapObj($this->Stelle->id,$this->user->id);

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
	    $this->formvars['Datentyp'] = 2;
	    $this->formvars['Data'] = $datastring;
	    $this->formvars['connectiontype'] = 6;
	    $connectionstring ='user='.$this->pgdatabase->user;
	    if($this->pgdatabase->passwd != ''){
	      $connectionstring.=' password='.$this->pgdatabase->passwd;
	    }
	    if($this->pgdatabase->host != ''){
	      $connectionstring.=' host='.$this->pgdatabase->host;
	    }
	    $connectionstring.=' dbname='.$this->pgdatabase->dbName;
	    $this->formvars['connection'] = $connectionstring;
	    $this->formvars['epsg_code'] = $epsg;
	    $this->formvars['transparency'] = 60;

	    $layer_id = $dbmap->newRollenLayer($this->formvars);

	    $classdata['layer_id'] = -$layer_id;
	    $class_id = $dbmap->new_Class($classdata);

			$color = $this->user->rolle->readcolor();
	    $style['colorred'] = $color['red'];
			$style['colorgreen'] = $color['green'];
			$style['colorblue'] = $color['blue'];
	    $style['outlinecolorred'] = 0;
	    $style['outlinecolorgreen'] = 0;
	    $style['outlinecolorblue'] = 0;
	    $style['size'] = 1;
	    $style['symbol'] = NULL;
	    $style['symbolname'] = NULL;
	    $style['backgroundcolor'] = NULL;
	    $style['minsize'] = NULL;
	    $style['maxsize'] = 100000;
	    if (MAPSERVERVERSION > '500') {
	    	$style['angle'] = 360;
	    }
	    $style_id = $dbmap->new_Style($style);

	    $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
	    $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen

	    $this->loadMap('DataBase');
	    # zu 2)
	    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
	    if(MAPSERVERVERSION >= 600 ) {
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				$this->map_scaledenom = $this->map->scale;
			}
    }
    return $ret;
  }

  function zoomToGebaeude($GebaeudeListe,$border) {
    $expression='("[ID]" eq "'.$GebaeudeListe['ID'][0].'"';
    $LegendeText='Gemeinde: '.$GebaeudeListe['GemeindeSchl'][0].'<br>Strasse: '.$GebaeudeListe['StrassenSchl'][0].'<br>Gebäude Nr: '.$GebaeudeListe['HausNr'][0];
    for ($i=1;$i<count($GebaeudeListe['ID']);$i++) {
      $expression.=' OR "[ID]" eq "'.$GebaeudeListe['ID'][$i].'"';
      $LegendeText.=', '.$GebaeudeListe['HausNr'][$i];
    }
    $expression.=')';
    $Gebaeude=new Gebaeude('');
    # 1. Anlegen eines neuen Layers für die Suche nach Flurstücken
    $layer=ms_newLayerObj($this->map);
    $layer->set('data',SHAPEPATH.$Gebaeude->getDataSourceName());
    $layer->set('status',MS_ON);
    $layer->set('template', ' ');
    $layer->set('name',$LegendeText);
    $layer->set('type',2);
    $layer->set('group','Suchergebnis');
    $layer->setMetaData('off_requires',0);
    $layer->setMetaData('layer_has_classes',0);
    $this->map->setMetaData('group_status_Suchergebnis','0');
    $this->map->setMetaData('group_Suchergebnis_has_active_layers','0');
    $layer->setMetaData('queryStatus','2');
    $layer->setMetaData('wms_queryable','0');
    $layer->setMetaData('layer_hidden','0');
    $klasse=ms_newClassObj($layer);
    $klasse->set('status', MS_ON);
    $klasse->setexpression($expression);
    $style=ms_newStyleObj($klasse);
    $style->color->setRGB(255,255,128);
    # 2. zoom auf ein oder mehrere Gebaeude
    $this->setFullExtent();
    $rect=$Gebaeude->getRectByGebaeudeListe($GebaeudeListe['ID'],$layer);
    if ($rect==0) {
      $this->Fehlermeldung='Es konnten keine Gebäude gefunden werden.';
      $rect=$this->Stelle->MaxGeorefExt;
    }
    else {
      $randx=($rect->maxx-$rect->minx)*$border/100;
      $randy=($rect->maxy-$rect->miny)*$border/100;
    }
    $this->map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
  	if(MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
    # Aktiviere Gebäude und Flurstückslayer
    $geblayer=$this->map->getLayerByName('Gebaeude');
    $geblayer->set('status',MS_ON);
    $flstlayer=$this->map->getLayerByName('Flurstuecke');
    $flstlayer->set('status',MS_ON);
    $this->Stelle->addAktivLayer(array(2,3));
  }

	function zoomToGeom($geom,$border) {
    # Berechnen des Randes in Abhängigkeit vom Parameter border gegeben in Prozent
    $sql.="SELECT st_xmin(st_extent('".$geom."')) AS minx,st_ymin(st_extent('".$geom."')) AS miny";
		$sql.=",st_xmax(st_extent('".$geom."')) AS maxx,st_ymax(st_extent('".$geom."')) AS maxy";
		$ret=$this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Erzeugen eines RectObject
      $rect= ms_newRectObj();
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
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
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

  function zoomToMaxLayerExtent($layer_id) {
    # Abfragen der maximalen Ausdehnung aller Daten eines Layers
		$layer = $this->user->rolle->getLayer($layer_id);
		if($layer == NULL)$layer = $this->user->rolle->getRollenLayer(-$layer_id);
		# Abfragen der Datenbankverbindung des Layers
    $layerdb=$this->mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);

		$data = replace_params($layer[0]['Data'], rolle::$layer_params);
		if($data != ''){
			# suchen nach dem ersten Vorkommen von using
			$pos = strpos(strtolower($data),'using ');
			# Abschneiden der uing Wörter im Datastatement wenn unique verwendet wurde
			if ($pos !== false) {
				$subquery=substr($data,0,$pos);
			}
			else {
				# using kommt nicht vor, es handelt sich um ein einfaches Data Statement in der Form
				# the_geom from tabelle, übernehmen wie es ist.
				$subquery = $data;
			}
			$explosion = explode(' ', $data);
			$this->attributes['the_geom'] = $explosion[0];
		}
		else{
			$subquery = substr($layer[0]['pfad'], 7);
			$this->attributes = $this->mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
		}

		# Filter berücksichtigen
		$filter = $this->mapDB->getFilter($layer_id, $this->Stelle->id);
		if($filter != ''){
			$filter = str_replace('$userid', $this->user->id, $filter);
			$subquery .= ' WHERE '.$filter;
		}

    # Erzeugen des Abfragestatements für den maximalen Extent aus dem Data String
    $sql ='SELECT st_xmin(extent) AS minx,st_ymin(extent) AS miny,st_xmax(extent) AS maxx,st_ymax(extent) AS maxy FROM (SELECT st_transform(st_setsrid(st_extent('.$this->attributes['the_geom'].'), '.$layer[0]['epsg_code'].'), '.$this->user->rolle->epsg_code.') AS extent FROM (SELECT ';
    $sql.=$subquery;
    $sql.=') AS fooForMaxLayerExtent) as foo';
    #echo $sql;

    # Abfragen der Layerausdehnung
    $ret=$layerdb->execSQL($sql,4,0);
    if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
    $rs = pg_fetch_array($ret[1]);
    if($rs['minx'] != ''){
			if($this->user->rolle->epsg_code == 4326)$rand = 10/10000;
			else $rand = 10;
			$minx=$rs['minx']-$rand;
			$maxx=$rs['maxx']+$rand;
			$miny=$rs['miny']-$rand;
			$maxy=$rs['maxy']+$rand;
	    #echo 'box:'.$minx.' '.$miny.','.$maxx.' '.$maxy;
	    $this->map->setextent($minx,$miny,$maxx,$maxy);
	    # damit nicht außerhalb des Stellen-Extents oder des maximalen Layer-Maßstabs gezoomt wird
	    $oPixelPos=ms_newPointObj();
	    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
	    if (MAPSERVERVERSION > 600) {
				if($layer[0]['maxscale'] > 0)$nScale = $layer[0]['maxscale']-1;
				else $nScale = $this->map->scaledenom;
				$this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scaledenom;
			}
			else {
				if($layer[0]['maxscale'] > 0)$nScale = $layer[0]['maxscale']-1;
				else $nScale = $this->map->scale;
				$this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
				$this->map_scaledenom = $this->map->scale;
			}
    }
  }

  function createQueryMap($layerset, $k){
  	if($layerset['attributes']['the_geom'] != ''){
	    $layer_id = $layerset['Layer_ID'];
	    $tablename = $layerset['attributes']['table_name'][$layerset['attributes']['the_geom']];
	    $oid = $layerset['shape'][$k][$tablename.'_oid'];
	    $mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
			if(MAPSERVERVERSION < 600){
				$map = ms_newMapObj(NULL);
			}
			else {
				$map = new mapObj(NULL);
			}
			$map->set('debug', 5);
	    $layerdb = $mapDB->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
	    # Auf den Datensatz zoomen
	    $sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
	    $sql.=" FROM (SELECT box2D(st_transform(".$layerset['attributes']['the_geom'].", ".$this->user->rolle->epsg_code.")) as bbox";
	    $sql.=" FROM ".$tablename." WHERE oid = '".$oid."') AS foo";
	    $ret = $layerdb->execSQL($sql, 4, 0);
	    $rs = pg_fetch_array($ret[1]);
	    $rect = ms_newRectObj();
	    $rect->minx=$rs['minx'];
	    $rect->maxx=$rs['maxx'];
	    $rect->miny=$rs['miny'];
	    $rect->maxy=$rs['maxy'];
	    $randx=($rect->maxx-$rect->minx)*50/100 + 0.01;
	    $randy=($rect->maxy-$rect->miny)*50/100 + 0.01;
	    if($rect->minx != ''){
	    	$map->setextent($rect->minx-$randx,$rect->miny-$randy,$rect->maxx+$randx,$rect->maxy+$randy);
		    # Haupt-Layer erzeugen
		    $layer=ms_newLayerObj($map);
		    $layer->set('data',$layerset['Data']);
				if($layerset['Filter'] != ''){
					$layerset['Filter'] = str_replace('$userid', $this->user->id, $layerset['Filter']);
					if (substr($layerset['Filter'],0,1)=='(') {
						$expr=$layerset['Filter'];
					}
					else{
						$expr=buildExpressionString($layerset['Filter']);
					}
					$layer->setFilter($expr);
				}
		    $layer->set('status',MS_ON);
		    $layer->set('template', ' ');
		    $layer->set('name','querymap'.$k);
		    $layer->set('type',$layerset['Datentyp']);
		    if (MAPSERVERVERSION < '540') {
		      $layer->set('connectiontype', 6);
		    }
		    else {
		      $layer->setConnectionType(6);
		    }
		    $layer->set('connection',$layerset['connection']);
		    $layer->setProjection('+init=epsg:'.$layerset['epsg_code']);
		    $layer->setMetaData('wms_queryable','0');
		    $klasse=ms_newClassObj($layer);
		    $klasse->set('status', MS_ON);
		    $style=ms_newStyleObj($klasse);
		    $style->color->setRGB(12,255,12);
		    if (MAPSERVERVERSION > '500') {
		    	$style->set('width', 1);
		    }
		    $style->outlinecolor->setRGB(110,110,110);
		    # Datensatz-Layer erzeugen
		    $layer=ms_newLayerObj($map);
				if($layerset['attributes']['schema_name'][$tablename] != ''){
					$tablename = $layerset['attributes']['schema_name'][$tablename].'.'.$tablename;
				}
		    elseif($layerset['schema'] != ''){
		    	$tablename = $layerset['schema'].'.'.$tablename;
		    }
		    $datastring = $layerset['attributes']['the_geom']." from (select oid as id, ".$layerset['attributes']['the_geom']." from ".$tablename;
		    $datastring.=" WHERE oid = '".$oid."'";
		    $datastring.=") as foo using unique id using srid=".$layerset['epsg_code'];
		    $layer->set('data',$datastring);
		    $layer->set('status',MS_ON);
		    $layer->set('template', ' ');
		    $layer->set('name','querymap'.$k);
		    $layer->set('type',$layerset['Datentyp']);
		    if (MAPSERVERVERSION < '540') {
		      $layer->set('connectiontype', 6);
		    }
		    else {
		      $layer->setConnectionType(6);
		    }
		    $layer->set('connection',$layerset['connection']);
		    $layer->setProjection('+init=epsg:'.$layerset['epsg_code']);
		    $layer->setMetaData('wms_queryable','0');
		    $klasse=ms_newClassObj($layer);
		    $klasse->set('status', MS_ON);
		    $style=ms_newStyleObj($klasse);
		    $style->color->setRGB(255,5,12);
		    if (MAPSERVERVERSION > '500') {
		    	$style->set('width', 2);
		    }
		    $style->outlinecolor->setRGB(0,0,0);
		    # Karte rendern
		    $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_TRUE);
		    $map->web->set('imagepath', IMAGEPATH);
		    $map->web->set('imageurl', IMAGEURL);
		    $map->set('width', 50);
		    $map->set('height', 50);
		    $image_map = $map->draw();
		    $filename = $this->map_saveWebImage($image_map,'jpeg');
		    $newname = $this->user->id.basename($filename);
		    rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
		    return IMAGEURL.$newname;
	    }
	    else{
	    	return GRAPHICSPATH.'nogeom.png';
	    }
  	}
  }

	function create_query_rect($input_coords){
		if($input_coords != ''){
			$corners=explode(';', $input_coords);
			if(count($corners) < 3){
				$lo=explode(',',$corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
				$ru=explode(',',$corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
				$width=$this->user->rolle->pixsize*($ru[0]-$lo[0]); # Breite des Auswahlbereiches in m
				$height=$this->user->rolle->pixsize*($ru[1]-$lo[1]); # Höhe des Auswahlbereiches in m
				#echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
				# linke obere Ecke im Koordinatensystem in m
				$minx=$this->user->rolle->oGeorefExt->minx+$this->user->rolle->pixsize*$lo[0]; # x Wert
				$miny=$this->user->rolle->oGeorefExt->miny+$this->user->rolle->pixsize*($this->user->rolle->nImageHeight-$ru[1]); # y Wert
				$maxx=$minx+$width;
				$maxy=$miny+$height;
				$rect=ms_newRectObj();
				$rect->setextent($minx,$miny,$maxx,$maxy);
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
    # Zoomen auf den in der Referenckarte gesetzten Punkt
    # Berechnen der Koordinaten des angeklickten Punktes in der Referencekarte
    $refmapwidthm=($this->reference_map->reference->extent->maxx-$this->reference_map->reference->extent->minx);
    $refmappixsize=$refmapwidthm/$this->reference_map->reference->width;
    $refmapxposm=$this->reference_map->reference->extent->minx+$refmappixsize*$this->formvars['refmap_x'];
    $refmapyposm=$this->reference_map->reference->extent->maxy-$refmappixsize*$this->formvars['refmap_y'];

		$point = ms_newPointObj();
	  $point->setXY($refmapxposm, $refmapyposm);

		#$newextent=ms_newRectObj();
		#$newextent->setextent($zoomminx,$zoomminy,$zoommaxx,$zoommaxy);
		if($this->ref['epsg_code'] != $this->user->rolle->epsg_code){
			if(MAPSERVERVERSION < '600'){
				$projFROM = ms_newprojectionobj("init=epsg:".$this->ref['epsg_code']);
				$projTO = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
			}
			else{
				$projFROM = $this->reference_map->projection;
				$projTO = $this->map->projection;
			}
			$point->project($projFROM, $projTO);
		}

		$halfmapwidthm=($this->map->extent->maxx-$this->map->extent->minx)/2;
    $halfmapheight=($this->map->extent->maxy-$this->map->extent->miny)/2;
    $zoommaxx=$point->x + $halfmapwidthm;
    $zoomminx=$point->x - $halfmapwidthm;
    $zoommaxy=$point->y + $halfmapheight;
    $zoomminy=$point->y - $halfmapheight;

    $this->map->setextent($zoomminx,$zoomminy,$zoommaxx,$zoommaxy);
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
    $this->saveMap('');
  }

	function layer_error_handling(){
		global $errors;
		for($i = 0; $i < 2; $i++){		// es wird nach den ersten beiden Fehlern abgebrochen, da die Fehlermeldungen bei mehrmaligem Aufruf immer mehr werden...
			$error_details .= $errors[$i].chr(10);
			if(strpos($errors[$i], 'named') !== false){
				$start = strpos($errors[$i], '\'')+1;
				$end = strpos($errors[$i], '\'', $start);
				$length = $end - $start;
				$error_layername = substr($errors[$i], $start, $length);
				$layer = $this->user->rolle->getLayer($error_layername);
				if($layer == NULL)$layer = $this->user->rolle->getRollenLayer($error_layername);
				$error_layer_id = $layer[0]['Layer_ID'];
			}
		}
		restore_error_handler();
		include(SNIPPETS . LAYER_ERROR_PAGE);
	}

  # Flurstücksauswahl
  function flurstwahl() {
		include_once(CLASSPATH.'FormObject.php');
    if($this->formvars['historical'] == 1){
      $this->titel='historische Flurstückssuche';
			$this->formvars['without_temporal_filter'] = 1;
    }
    elseif($this->formvars['ALK_Suche'] == 1){
      $this->titel='Flurstückssuche (zur Karte)';
    }
    else{
    	$this->titel='Flurstückssuche';
    }
		if($this->formvars['titel'] != '')$this->titel = $this->formvars['titel'];
    $this->main='flurstueckssuche.php';
    ####### Import ###########
		$_files = $_FILES;
    if($_files['importliste']['name']){
			$importliste = file($_files['importliste']['tmp_name'], FILE_IGNORE_NEW_LINES);
			if(strpos($importliste[0], '/') !== false){
				$importliste_string = implode('; ', $importliste);
				$importliste_string = formatFlurstkennzALKIS($importliste_string);
				$importliste = explode(';', $importliste_string);
			}
			$this->formvars['selFlstID'] = implode(', ', $importliste);
			$this->formvars['GemkgID'] = substr($importliste[0], 0, 6);
			$this->formvars['FlurID'] = substr($importliste[0], 6, 3);
			$this->formvars['without_temporal_filter'] = 1;
		}
		##########################
		# Übernahme der Formularwerte für die Einstellung der Auswahlmaske
		$GemID=$this->formvars['GemID'];
		$GemkgID=$this->formvars['GemkgID'];
		$FlurID=$this->formvars['FlurID'];
		$FlstID=$this->formvars['FlstID'];
		$FlstNr=$this->formvars['FlstNr'];
		$selFlstID = explode(', ',$this->formvars['selFlstID']);
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();
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
    if (count($GemkgListe['GemkgID'])>0) {
      if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    }
    else {
      $GemkgFormObj=new selectFormObject("GemkgID","text","","","","25","25","",NULL);
    }
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();
    # Wenn Gemarkung gewählt wurde, oder nur eine Gemarkung zur Wahl steht, Auswahlliste für Flur erzeugen
    if ($GemkgFormObj->selected) {
      # Abragen der Fluren zur Gemarkung
      if ($GemkgID==0) { $GemkgID=$GemkgListe['GemkgID'][0]; }
      $Flur=new Flur('','','',$this->pgdatabase);
    	$FlurListe=$Flur->getFlurListe($GemkgID, $GemeindenStelle['eingeschr_gemarkung'][$GemkgID], $this->formvars['historical']);
      # Erzeugen des Formobjektes für die Flurauswahl
      if (count($FlurListe['FlurID'])==1) { $FlurID=$FlurListe['FlurID'][0]; }
      $FlurFormObj=new selectFormObject("FlurID","select",$FlurListe['FlurID'],array($FlurID),$FlurListe['Name'],"1","","",NULL);
      $FlurFormObj->insertOption(-1,0,'--Auswahl--',0);
      $FlurFormObj->outputHTML();
      # Wenn Flur gewählt wurde, oder nur eine Flur zur Auswahl steht, Auswahllist für Flurstuecke erzeugen
      if ($FlurFormObj->selected) {
        # Abfragen der Flurstücke zur Flur
        $FlstNr=new flurstueck('',$this->pgdatabase);
        if ($FlurID==0) { $FlurID=$FlurListe['FlurID'][0]; }
        $FlstNrListe=$FlstNr->getFlstListe($GemID,$GemkgID,$FlurID, $this->formvars['historical']);
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count($FlstNrListe['FlstID'])==1){
          $FLstID=$FlstNrListe['FlstID'][0];
          $FlstID = array($FLstID);
        }
        $FlstNrFormObj=new FormObject("FlstID","select",$FlstNrListe['FlstID'],array($FlstID),$FlstNrListe['FlstNr'],"12","","multiple",100);
				$FlstNrFormObj->insertOption('alle',false,' -- alle -- ', 0);
				$FlstNrFormObj->addJavaScript('onclick', 'if(this.value==\'alle\'){this.options[0].selected = false; for(var i=1; i<this.options.length; i++){this.options[i].selected = true;}}');
        $FlstNrFormObj->outputHTML();
        if($this->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",170);
        }
        else{
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedFlstNrFormObj->outputHTML();
      }
      else {
        if($this->formvars['selFlstID'] != ''){
          $SelectedFlstNrFormObj=new FormObject("selectedFlstID","select", $selFlstID, NULL, $selFlstID,"12","","multiple",100);
          $SelectedFlstNrFormObj->outputHTML();
        }
        else{
          $FlstNrFormObj=new FormObject("FlstNr","text","","","","5","5","multiple",NULL);
        }
      }
    }
    else {
      $FlurFormObj=new FormObject("FlurID","text","","","","25","25","multiple",NULL);
      $FlstNrFormObj=new FormObject("FlstNr","text","","","","5","5","multiple",NULL);
    }
    $this->FormObject["Gemeinden"]=$GemFormObj;
    $this->FormObject["Gemarkungen"]=$GemkgFormObj;
    $this->FormObject["GemkgSchl"]=$GemkgSchlFormObj;
    $this->FormObject["Fluren"]=$FlurFormObj;
    $this->FormObject["FlstNr"]=$FlstNrFormObj;
    $this->FormObject["selectedFlstNr"]=$SelectedFlstNrFormObj;
  }

  # adressenauswahl
  function adresswahl() {
		include_once(CLASSPATH.'FormObject.php');
    $Adresse=new adresse('','','',$this->pgdatabase);
    $this->main='adresssuche.php';
    if($this->formvars['ALK_Suche'] == 1){
    	$this->titel='Adresssuche (zur Karte)';
    }
    else{
    	$this->titel='Adressensuche';
    }
		if($this->formvars['titel'] != '')$this->titel = $this->formvars['titel'];
    if ($this->formvars['aktualisieren']=='Neu') {
      $GemID=0; $StrID=0; $StrName=''; $HausID=0; $HausNr='';
    }
    else {
      $GemID=$this->formvars['GemID'];
      $GemkgID=$this->formvars['GemkgID'];
      $StrID=$this->formvars['StrID'];
      $StrName=$this->formvars['StrName'];
      if ($StrName!='') {
        $StrID=$Adresse->getStrIDfromName($GemID,$StrName);
      }
      $HausID=$this->formvars['HausID'];
      $HausNr=$this->formvars['HausNr'];
      $selHausID = explode(', ',$this->formvars['selHausID']);
    }
    $Gemeinde=new gemeinde('',$this->pgdatabase);
		$Gemarkung=new gemarkung('',$this->pgdatabase);
    $GemeindenStelle=$this->Stelle->getGemeindeIDs();

		if($GemeindenStelle == NULL){
			$GemListe=$Gemeinde->getGemeindeListe(NULL);
			$GemkgListe=$Gemarkung->getGemarkungListe(NULL,'');
		}
		else{
			$GemListe=$Gemeinde->getGemeindeListe(array_merge(array_keys($GemeindenStelle['ganze_gemeinde']), array_keys($GemeindenStelle['eingeschr_gemeinde'])));
			$GemkgListe=$Gemarkung->getGemarkungListe(array_keys($GemeindenStelle['ganze_gemeinde']), array_merge(array_keys($GemeindenStelle['ganze_gemarkung']), array_keys($GemeindenStelle['eingeschr_gemarkung'])));
		}
		# Wenn nur eine Gemeinde zur Auswahl steht, wird diese gewählt; Verhalten so, als würde die Gemeinde vorher gewählt worden sein.
		if(count($GemListe['ID'])==1)$GemID=$GemListe['ID'][0];
    // Sortieren der Gemarkungen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemkgListe['Bezeichnung'], $GemkgListe['GemkgID']);
    $GemkgListe['Bezeichnung'] = $sorted_arrays['array'];
    $GemkgListe['GemkgID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemarkungsauswahl
    if (count($GemkgListe['GemkgID'])==1) { $GemkgID=$GemkgListe['GemkgID'][0]; }
    $GemkgFormObj=new selectFormObject("GemkgID","select",$GemkgListe['GemkgID'],array($GemkgID),$GemkgListe['Bezeichnung'],"1","","",NULL);
    $GemkgFormObj->addJavaScript('onclick', 'document.GUI.GemID.disabled = true');
    $GemkgFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemkgFormObj->outputHTML();

    // Sortieren der Gemeinden unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($GemListe['Name'], $GemListe['ID']);
    $GemListe['Name'] = $sorted_arrays['array'];
    $GemListe['ID'] = $sorted_arrays['second_array'];
    # Erzeugen des Formobjektes für die Gemeindeauswahl
    $GemFormObj=new selectFormObject("GemID","select",$GemListe['ID'],array($GemID),$GemListe['Name'],"1","","",NULL);
    $GemFormObj->addJavaScript('onclick', 'document.GUI.GemkgID.disabled = true');
    $GemFormObj->insertOption(-1,0,'--Auswahl--',0);
    $GemFormObj->outputHTML();
    # Wenn Gemeinde gewählt wurde, oder nur eine zur Auswahl stand, Auswahlliste für Strassen erzeugen
    if ($GemFormObj->selected OR $GemkgFormObj->selected){
    	if($GemFormObj->selected)$StrassenListe=$Adresse->getStrassenListe($GemID,'', '');
    	elseif($GemkgFormObj->selected)$StrassenListe=$Adresse->getStrassenListe('', $GemkgID,'');
      $StrSelected[0]=$StrID;
      # Erzeugen des Formobjektes für die Strassenauswahl
      $StrFormObj=new selectFormObject("StrID","select",$StrassenListe['StrID'],$StrSelected,$StrassenListe['Name'],"1","","",NULL);
      # Unterscheidung ob Strasse ausgewählt wurde
      if ($StrFormObj->selected){
      	if($GemID == -1 OR $GemID == ''){
					$Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($this->formvars['GemkgID']), NULL);
		    	$GemID = $Gemeinde['gemeinde'][0];
		    }
        $HausNrListe=$Adresse->getHausNrListe($GemID,$StrID,'','','hausnr*1,ASCII(REVERSE(hausnr)),quelle');
        # Erzeugen des Formobjektes für die Flurstücksauswahl
        if (count($HausNrListe['HausID'])==1){
          $HausID=$HausNrListe['HausID'][0];
          $HausID = array($HausID);
        }
        $HausNrFormObj=new FormObject("HausID","select",$HausNrListe['HausID'],array($HausID),$HausNrListe['HausNr'],"12","","multiple",100);
        $HausNrFormObj->outputHTML();
        if($this->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",170);
        }
        else{
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select",NULL,NULL,"","12","","multiple",170);
        }
        $SelectedHausNrFormObj->outputHTML();
      }

    	else {
        if($this->formvars['selHausID'] != ''){
          $SelectedHausNrFormObj=new FormObject("selectedHausID","select", $selHausID, NULL, $selHausID,"12","","multiple",100);
          $SelectedHausNrFormObj->outputHTML();
        }
        else{
          $HausNrFormObj=new FormObject("HausNr","text","","","","5","5","multiple",NULL);
        }
      }
    }
    else {
      # Es wurde noch keine Gemeinde ausgewählt, Strasse und Hausnummer als Textfelder
      $StrFormObj=new selectFormObject("StrName","text","","","","25","25","",NULL);
      $HausNrFormObj=new selectFormObject("HausNr","text","","","","5","5","",NULL);
    }
    $this->FormObject["Gemeinden"]=$GemFormObj;
    $this->FormObject["Gemarkungen"]=$GemkgFormObj;
    $this->FormObject["Strassen"]=$StrFormObj;
    $this->FormObject["HausNr"]=$HausNrFormObj;
    $this->FormObject["selectedHausNr"]=$SelectedHausNrFormObj;
  }

  function adresseSuchen() {
    # 2006-01-31 pk
    #echo 'GemeindeID'.$this->formvars['GemID'];
    #echo '<br>StrasseID'.$this->formvars['StrID'];
    #echo '<br>HausID'.$this->formvars['selHausID'];
    $GemID=$this->formvars['GemID'];
    if($GemID == -1){
    	$Gemarkung=new gemarkung('',$this->pgdatabase);
    	$Gemeinde = $Gemarkung->getGemarkungListe(NULL, array($this->formvars['GemkgID']));
    	$GemID = $Gemeinde['gemeinde'][0];
    }
    if ($GemID!='-1') {
      $Adresse=new adresse($GemID,'','',$this->pgdatabase);
      $StrID=$this->formvars['StrID'];
      $StrName=$this->formvars['StrName'];
      if($StrName!='') {
        $StrID=$Adresse->getStrIDfromName($GemID,$StrName);
      }
    	else{
        $StrName=$Adresse->getStrNamefromID($GemID,$StrID);
      }
      $Adresse->StrassenSchl=$StrID;
      $HausID=$this->formvars['selHausID'];
      $HausNr=$this->formvars['HausNr'];
      if ($HausNr!='') {
        $HausID=$HausNr;
      }
      if ($HausID=='-1') {
        $HausID='';
      }
      $Adresse->HausNr=$HausID;
      # $this->searchInExtent=$this->formvars['searchInExtent'];
      # Wenn keine Strasse angegeben ist zoom auf die ganze Gemeinde
      if ($StrID<'1') {
        $this->loadMap('DataBase');
        $this->zoomToALKGemeinde($GemID,10);
        $currenttime=date('Y-m-d H:i:s',time());
        $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
        $this->drawMap();
        $this->saveMap('');
      }
      else {
        # StrassenID ist angegeben
        # Abfrage der Flurstücks aus dem ALB über die Adresse
        $FlurstKennz=$Adresse->getFlurstKennzListe();
        if($this->formvars['ALK_Suche'] == 1){
	        $ret = $this->zoomToALKGebaeude($GemID,$StrID,$StrName,$HausID,100);
	        if($ret[0]){
	        	$this->zoomToALKFlurst($FlurstKennz,100);
	        }
					if($this->formvars['go_next'] != '')header('location: index.php?go='.$this->formvars['go_next']);
	        $currenttime=date('Y-m-d H:i:s',time());
          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
          $this->drawMap();
          $this->saveMap('');
        }
        else{
	        if ($FlurstKennz > 0) {
	          # Anzeige der ALB-daten in Flurstücksanzeige
	          $this->flurstAnzeige($FlurstKennz);
	        }
	        else {
	          # Anzeige der Gebaeude in der ALK
	          # Karte laden, auf die Gebaeude zoomen, Karte Zeichnen und speichern für späteren gebrauch
	          $this->zoomToALKGebaeude($GemID,$StrID,$StrName,$HausID,100);
	          $currenttime=date('Y-m-d H:i:s',time());
	          $this->user->rolle->setConsumeActivity($currenttime,'getMap',$this->user->rolle->last_time_id);
	          $this->drawMap();
	          $this->saveMap('');
	        }
        }
      }
    }
    else {
      $this->Fehlermeldung='Wählen Sie eine Gemeinde aus!';
      $this->adresswahl();
    }
  }

} # end of class GUI

##############################################################
# Klasse MapObject zum laden der Map-Daten aus der Datenbank #
##############################################################
# Klasse db_mapObj #
####################

class db_mapObj{
  var $debug;
  var $referenceMap;
  var $Layer;
  var $anzLayer;
  var $nurAufgeklappteLayer;
  var $Stelle_ID;
  var $User_ID;
	var $database;

  function db_mapObj($Stelle_ID, $User_ID, $database = NULL) {
    global $debug;
    $this->debug=$debug;
    $this->Stelle_ID=$Stelle_ID;
    $this->User_ID=$User_ID;
		$this->rolle = new rolle($User_ID, $Stelle_ID, $database);
		$this->database=$database;
  }

	function read_ReferenceMap() {
    $sql ='SELECT r.* FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->referenceMap=$rs;
    return $rs;
  }

  function read_RollenLayer($id = NULL, $typ = NULL){
		$sql = "SELECT DISTINCT l.*, g.Gruppenname, -l.id AS Layer_ID, 1 as showclasses, CASE WHEN Typ = 'import' THEN 1 ELSE 0 END as queryable from rollenlayer AS l, u_groups AS g";
    $sql.= ' WHERE l.Gruppe = g.id AND l.stelle_id='.$this->Stelle_ID.' AND l.user_id='.$this->User_ID;
    if($id != NULL){
    	$sql .= ' AND l.id = '.$id;
    }
  	if($typ != NULL){
    	$sql .= ' AND l.Typ = \''.$typ.'\'';
    }
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $Layer = array();
    while ($rs=mysql_fetch_array($query)) {
      $rs['Class']=$this->read_Classes(-$rs['id'], $this->disabled_classes);
      $Layer[]=$rs;
    }
    return $Layer;
  }

  function read_Layer($withClasses, $groups = NULL){
		global $language;

		if($language != 'german') {
			$name_column = "
			CASE
				WHEN l.`Name_" . $language . "` != \"\" THEN l.`Name_" . $language . "`
				ELSE l.`Name`
			END AS Name";
		}
		else
			$name_column = "l.Name";

		$sql = "
			SELECT DISTINCT
				coalesce(rl.transparency, ul.transparency, 100) as transparency, rl.`aktivStatus`, rl.`queryStatus`, rl.`gle_view`, rl.`showclasses`, rl.`logconsume`, 
				ul.`queryable`, ul.`drawingorder`, ul.`minscale`, ul.`maxscale`, ul.`offsite`, ul.`postlabelcache`, ul.`Filter`, ul.`template`, ul.`header`, ul.`footer`, ul.`symbolscale`, ul.`logconsume`, ul.`requires`, ul.`privileg`, ul.`export_privileg`,
				l.Layer_ID," .
				$name_column . ",
				l.alias,
				l.Datentyp, l.Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, l.labelitem,
				l.labelmaxscale, l.labelminscale, l.labelrequires, l.connection, l.printconnection, l.connectiontype, l.classitem, l.classification, l.filteritem,
				l.cluster_maxdistance, l.tolerance, l.toleranceunits, l.processing, l.epsg_code, l.ows_srs, l.wms_name, l.wms_server_version,
				l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, l.status,
				g.*
			FROM
				u_rolle2used_layer AS rl,
				used_layer AS ul,
				layer AS l,
				u_groups AS g,
				u_groups2rolle as gr
			WHERE
				rl.stelle_id = ul.Stelle_ID AND
				rl.layer_id = ul.Layer_ID AND
				l.Layer_ID = ul.Layer_ID AND
				(ul.minscale != -1 OR ul.minscale IS NULL) AND l.Gruppe = g.id AND rl.stelle_ID = " . $this->Stelle_ID . " AND rl.user_id = " . $this->User_ID . " AND
				gr.id = g.id AND
				gr.stelle_id = " . $this->Stelle_ID . " AND
				gr.user_id = " . $this->User_ID;

		if($groups != NULL){
			$sql.=' AND g.id IN ('.$groups.')';
		}
    if($this->nurAufgeklappteLayer){
      $sql.=' AND (rl.aktivStatus != "0" OR gr.status != "0" OR ul.requires != "")';
    }
    if($this->nurAktiveLayer){
      $sql.=' AND (rl.aktivStatus != "0")';
    }
		if($this->OhneRequires){
      $sql.=' AND (ul.requires IS NULL)';
    }
    if($this->nurFremdeLayer){			# entweder fremde (mit host=...) Postgis-Layer oder aktive nicht-Postgis-Layer
    	$sql.=' AND (l.connection like "%host=%" AND l.connection NOT like "%host=localhost%" OR l.connectiontype != 6 AND rl.aktivStatus != "0")';
    }
    $sql.=' ORDER BY ul.drawingorder';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $this->Layer = array();
    $this->disabled_classes = $this->read_disabled_classes();
		$i = 0;
    while ($rs=mysql_fetch_assoc($query)) {
			$rs['classification'] = replace_params($rs['classification'], rolle::$layer_params);
			if ($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')) {
				# bei withclasses == 2 werden für alle Layer die Klassen geladen,
				# bei withclasses == 1 werden Klassen nur dann geladen, wenn der Layer aktiv ist
				$rs['Class']=$this->read_Classes($rs['Layer_ID'], $this->disabled_classes, false, $rs['classification']);
			}
			if($rs['maxscale'] > 0)$rs['maxscale'] = $rs['maxscale']+0.3;
			if($rs['minscale'] > 0)$rs['minscale'] = $rs['minscale']-0.3;
      $this->Layer[$i]=$rs;
			$this->Layer['layer_ids'][$rs['Layer_ID']] =& $this->Layer[$i];		# damit man mit einer Layer-ID als Schlüssel auf dieses Array zugreifen kann
			$i++;
    }
    return $this->Layer;
  }

  function read_Groups($all = false, $order = '') {
		global $language;
		$sql = 'SELECT ';
		if($all == false) $sql .= 'g2r.status, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` IS NOT NULL THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname, obergruppe, g.id FROM u_groups AS g';
		if($all == false){
			$sql.=', u_groups2rolle AS g2r ';
			$sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID;
			$sql.=' AND g2r.id = g.id';
		}
		if($order != '')$sql.=' ORDER BY '.$order;
		else $sql.=' ORDER BY `order`';
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $groups[$rs['id']]=$rs;
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    $this->anzGroups=count($groups);
    return $groups;
  }


  // function read_Group($id) {
    // $sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
    // $sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID.' AND g2r.id = g.id AND g.id='.$id;
    // $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Lesen einer Gruppe der Rolle:<br>".$sql,4);
    // $query=mysql_query($sql);
    // if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    // $rs=mysql_fetch_array($query);
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
		$query=mysql_query($sql);
		if ($query == 0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
		while ($rs = mysql_fetch_array($query)) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
			$Classes[] = $rs;
		}
		return $Classes;
	}

	function read_Classes($Layer_ID, $disabled_classes = NULL, $all_languages = false, $classification = '') {
		global $language;

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
				classification,
				drawingorder,
				Class_ID
		";
		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>" . $sql, 4);
		$query = mysql_query($sql);
		if ($query == 0) { echo "<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__; return 0; }
		while ($rs = mysql_fetch_assoc($query)) {
			$rs['Style'] = $this->read_Styles($rs['Class_ID']);
			$rs['Label'] = $this->read_Label($rs['Class_ID']);
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
		}
		return $Classes;
	}

  function read_disabled_classes(){
  	#Anne
    $sql_classes = 'SELECT class_id, status FROM u_rolle2used_class WHERE user_id='.$this->User_ID.' AND stelle_id='.$this->Stelle_ID.';';
    $query_classes=mysql_query($sql_classes);
    while($row = mysql_fetch_assoc($query_classes)){
  		$classarray['class_id'][] = $row['class_id'];
			$classarray['status'][$row['class_id']] = $row['status'];
		}
		return $classarray;
  }

  function read_Styles($Class_ID) {
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_assoc($query)) {
      $Styles[]=$rs;
    }
    return $Styles;
  }


  # Änderung am 12.07.2005 von 1.4.4 nach 1.4.5, Korduan
  # Einer Klasse können nun mehrere Labels zugeordnet werden
  # Abfrage der Labels nicht mehr aus Tabelle classes sondern aus u_labels2classes
  function read_Label($Class_ID) {
    $sql ='SELECT * FROM labels AS l,u_labels2classes AS l2c';
    $sql.=' WHERE l.Label_ID=l2c.label_id AND l2c.class_id='.$Class_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while ($rs=mysql_fetch_assoc($query)) {
      $Labels[]=$rs;
    }
    return $Labels;
  }

	function zoomToDatasets($oids, $tablename, $columnname, $border, $layerdb, $layer_epsg, $client_epsg) {
  	$sql ="SELECT st_xmin(bbox) AS minx,st_ymin(bbox) AS miny,st_xmax(bbox) AS maxx,st_ymax(bbox) AS maxy";
  	$sql.=" FROM (SELECT st_transform(ST_SetSRID(ST_Extent(".$columnname."), ".$layer_epsg."), ".$client_epsg.") as bbox";
  	$sql.=" FROM ".$tablename." WHERE oid IN (";
  	for($i = 0; $i < count($oids); $i++){
    	$sql .= "'".$oids[$i]."',";
    }
    $sql = substr($sql, 0, -1);
		$sql.=")) AS foo";
		#echo $sql;
    $ret = $layerdb->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
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

  function deleteFilter($stelle_id, $layer_id, $attributname){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$stelle_id.' AND Layer_ID = '.$layer_id.' AND attributname = "'.$attributname.'"';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteFilter - Löschen eines Attribut-Filters eines used_layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function writeFilter($database, $filter, $layer, $stelle){
    if($filter != ''){
      $layerdata = $this->get_Layer($layer);
      $filterstring = '(1 = 1';
      for($i = 0; $i < count($filter); $i++){
        if($filter[$i]['type'] == 'geometry'){
          $poly_geom = $database->getpolygon($filter[$i]['attributvalue'], $layerdata['epsg_code']);
          #$filterstring .= ' AND '.$filter[$i]['attributname'].' && \''.$poly_geom.'\'';		// ist ja bei within und intersects schon mit drin
          $filterstring .= ' AND '.$filter[$i]['operator'].'('.$filter[$i]['attributname'].',\''.$poly_geom.'\'::geometry)';
        }
        else{
          if($filter[$i]['operator'] == 'IS'){
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' '.$filter[$i]['attributvalue'];
          }
          elseif($filter[$i]['operator'] == 'IN'){
            if($filter[$i]['type'] == 'varchar' OR $filter[$i]['type'] == 'text'){
              $values = explode(',', $filter[$i]['attributvalue']);
              $filter[$i]['attributvalue'] = "'".implode("','", $values)."'";
            }
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' ('.$filter[$i]['attributvalue'].')';
          }
          else{
            $filterstring .= ' AND '.$filter[$i]['attributname'].' '.$filter[$i]['operator'].' \''.$filter[$i]['attributvalue'].'\'';
          }
        }
      }
      $filterstring .= ')';
    }
    $sql = 'UPDATE used_layer SET Filter = "'.$filterstring.'" WHERE Stelle_ID = '.$stelle.' AND Layer_ID = '.$layer;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->writeFilter - Speichern des Filterstrings:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function checkPolygon($poly_id){
    $sql = 'SELECT * FROM u_attributfilter2used_layer WHERE attributvalue = "'.$poly_id.'" AND type = "geometry"';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->checkPolygon - Testen ob Polygon_id noch in einem Filter benutzt wird:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
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
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret=mysql_fetch_row($query);
    $poly_id = $ret[0];
    return $poly_id;
  }

  function saveAttributeFilter($formvars){
    if(MYSQLVERSION > 410){
      $sql = 'INSERT INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= " attributvalue = '".$formvars['attributvalue']."',";
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
      $sql .= " ON DUPLICATE KEY UPDATE  attributvalue = '".$formvars['attributvalue']."', operator = '".$formvars['operator']."'";
    }
    else{
      $sql = 'REPLACE INTO u_attributfilter2used_layer SET';
      $sql .= ' attributname = "'.$formvars['attributname'].'",';
      $sql .= " attributvalue = '".$formvars['attributvalue']."',";
      $sql .= ' operator = "'.$formvars['operator'].'",';
      $sql .= ' type = "'.$formvars['type'].'",';
      $sql .= ' Stelle_ID = '.$formvars['stelle'].',';
      $sql .= ' Layer_ID = '.$formvars['layer'];
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->saveAttributeFilter - Speichern der Attribute-Filter-Parameter:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
  }

  function readAttributeFilter($Stelle_ID, $Layer_ID){
    $sql ='SELECT * FROM u_attributfilter2used_layer WHERE Stelle_ID = '.$Stelle_ID.' AND Layer_ID = '.$Layer_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->readAttributeFilter - Lesen der Attribute-Filter-Parameter:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs = mysql_fetch_array($query)){
      $filter[] = $rs;
    }
    return $filter;
  }

	function getFilter($layer_id, $stelle_id){
    $sql ='SELECT Filter FROM used_layer WHERE Layer_ID = '.$layer_id.' AND Stelle_ID = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getFilter - Lesen des Filter-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $filter = $rs[0];
    return $filter;
  }

  function getData($layer_id){
  	if($layer_id < 0){	# Rollenlayer
  		$sql = "
				SELECT
					Data
				FROM
					rollenlayer
				WHERE
					-id = " . $layer_id . "
			";
  	}
  	else{
    	$sql = "
				SELECT
					Data
				FROM
					layer
				WHERE
					Layer_ID = " . $layer_id . "
			";
  	}
  	#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getData - Lesen des Data-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_assoc($query);
    $data = replace_params($rs['Data'], rolle::$layer_params);
    return $data;
  }

  function getPath($layer_id){
    $sql ='SELECT Pfad FROM layer WHERE Layer_ID = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getPath - Lesen des Path-Statements des Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs = mysql_fetch_array($query);
    $pfad = $rs[0];
    return $pfad;
  }

  function getDocument_Path($doc_path, $option, $attributenames, $attributevalues, $layerdb){
		// diese Funktion liefert den Pfad des Dokuments, welches hochgeladen werden soll (absoluter Pfad mit Dateiname ohne Dateiendung)
    if($doc_path == '')$doc_path = CUSTOM_IMAGE_PATH;
		if(strtolower(substr($option, 0, 6)) == 'select'){		// ist im Optionenfeld eine SQL-Abfrage definiert, diese ausführen und mit dem Ergebnis den Dokumentenpfad erweitern
			$sql = $option;
			for($a = 0; $a < count($attributenames); $a++){
				if($attributenames[$a] != '')$sql = str_replace('$'.$attributenames[$a], $attributevalues[$a], $sql);
			}
			$ret = $layerdb->execSQL($sql,4, 1);
			$dynamic_path = pg_fetch_row($ret[1]);
			$doc_path .= $dynamic_path[0];		// der ganze Pfad mit Dateiname ohne Endung
			$path_parts = explode('/', $doc_path);
			array_pop($path_parts);
			$new_path = implode('/', $path_parts);		// der evtl. neu anzulegende Pfad ohne Datei
			@mkdir($new_path, 0777, true);
		}
		else{
			$currenttime = date('Y-m-d_H_i_s',time());			// andernfalls werden keine weiteren Unterordner generiert und der Dateiname aus Zeitstempel und Zufallszahl zusammengesetzt
      $doc_path .= $currenttime.'-'.rand(100000, 999999);
		}
    return $doc_path;
  }

	function getlayerdatabase($layer_id, $host){
		if($layer_id < 0){	# Rollenlayer
			$sql ='SELECT `connection`, "'.CUSTOM_SHAPE_SCHEMA.'" as `schema` FROM rollenlayer WHERE -id = '.$layer_id.' AND connectiontype = 6';
		}
		else{
			$sql ='SELECT `connection`, `schema` FROM layer WHERE Layer_ID = '.$layer_id.' AND connectiontype = 6';
		}
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Lesen des connection-Strings des Layers:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs = mysql_fetch_array($query);
		$connectionstring = $rs[0];
#		$this->debug->write("<p>file:kvwmap class:db_mapObj->getlayerdatabase - Gefundener Connection String des Layers:<br>" . $connectionstring, 4);
		if($connectionstring != ''){
			$layerdb = new pgdatabase();
			if($rs[1] == ''){
				$rs[1] = 'public';
			}
			$layerdb->schema = $rs[1];
			$connection = explode(' ', trim($connectionstring));
			for($j = 0; $j < count($connection); $j++){
				if($connection[$j] != ''){
					$value = explode('=', $connection[$j]);
					if(strtolower($value[0]) == 'user'){
						$layerdb->user = $value[1];
					}
					if(strtolower($value[0]) == 'dbname'){
						$layerdb->dbName = $value[1];
					}
					if(strtolower($value[0]) == 'password'){
						$layerdb->passwd = $value[1];
					}
					if(strtolower($value[0]) == 'host'){
						$layerdb->host = $value[1];
					}
					if(strtolower($value[0]) == 'port'){
						$layerdb->port = $value[1];
					}
				}
			}
			if (!isset($layerdb->host)) {
				$layerdb->host = $host;
			}
			if (!$layerdb->open()) {
				echo 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden:';
				echo '<br>Host: '.$layerdb->host;
				echo '<br>User: '.$layerdb->user;
				echo '<br>Datenbankname: '.$layerdb->dbName;
				exit;
			}
		}
		return $layerdb;
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
      if(strpos($select, 'select') != false){
        $select = stristr($select, 'select');
      }
    }
    return $select;
  }

  function getDataAttributes($database, $layer_id){
    $data = $this->getData($layer_id);
    if($data != ''){
      $select = $this->getSelectFromData($data);
      if($database->schema != ''){
      	$select = str_replace($database->schema.'.', '', $select);
      }
      $attribute = $database->getFieldsfromSelect($select);
      return $attribute;
    }
    else{
      echo 'Das Data-Feld des Layers mit der Layer-ID '.$layer_id.' ist leer.';
      return NULL;
    }
  }

  function getPathAttributes($database, $path){
    if($path != ''){
      $attribute = $database->getFieldsfromSelect($path);
      return $attribute;
    }
  }

  function add_attribute_values($attributes, $database, $query_result, $withvalues = true, $stelle_id, $only_current_enums = false){
    # Diese Funktion fügt den Attributen je nach Attributtyp zusätzliche Werte hinzu. Z.B. bei Auswahlfeldern die Auswahlmöglichkeiten.
    for($i = 0; $i < count($attributes['name']); $i++){
			$type = ltrim($attributes['type'][$i], '_');
			if(is_numeric($type)){
				$attributes['type_attributes'][$i] = $this->add_attribute_values($attributes['type_attributes'][$i], $database, $query_result, $withvalues, $stelle_id, $only_current_enums);
			}
			if($attributes['options'][$i] == '' AND $attributes['constraints'][$i] != '' AND !in_array($attributes['constraints'][$i], array('PRIMARY KEY', 'UNIQUE'))){  # das sind die Auswahlmöglichkeiten, die durch die Tabellendefinition in Postgres fest vorgegeben sind
      	$attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['constraints'][$i]));
      	$attributes['enum_output'][$i] = $attributes['enum_value'][$i];
      }
      if($withvalues == true){
        switch($attributes['form_element_type'][$i]){
          # Auswahlfelder
          case 'Auswahlfeld' : {
            if($attributes['options'][$i] != ''){     # das sind die Auswahlmöglichkeiten, die man im Attributeditor selber festlegen kann
              if(strpos($attributes['options'][$i], "'") === 0){      # Aufzählung wie 'wert1','wert2','wert3'
                $attributes['enum_value'][$i] = explode(',', str_replace("'", "", $attributes['options'][$i]));
                $attributes['enum_output'][$i] = $attributes['enum_value'][$i];
              }
              elseif(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0];
                # ------<required by>------
                $req_by_start = strpos(strtolower($attributes['options'][$i]), "<required by>");
                if($req_by_start > 0){
                  $req_by_end = strpos(strtolower($attributes['options'][$i]), "</required by>");
                  $req_by = trim(substr($attributes['options'][$i], $req_by_start+13, $req_by_end-$req_by_start-13));
                  $attributes['req_by'][$i] = $req_by;    # das abhängige Attribut
                  $attributes['options'][$i] = substr($attributes['options'][$i], 0, $req_by_start);    # required-Tag aus SQL entfernen
                }
                # ------<required by>------
                # -----<requires>------
                if(strpos(strtolower($attributes['options'][$i]), "<requires>") > 0){
									if($only_current_enums){		# Ermittlung der Spalte, die als value dient
										$explo1 = explode(' as value', strtolower($attributes['options'][$i]));
										$attribute_value_column = array_pop(explode(' ', $explo1[0]));
									}
                  if($query_result != NULL){
                    for($k = 0; $k < count($query_result); $k++){
											$options = $attributes['options'][$i];
											foreach($attributes['name'] as $attributename){
												if(strpos($options, '<requires>'.$attributename.'</requires>') !== false AND $query_result[$k][$attributename] != ''){
													if($only_current_enums){	# in diesem Fall werden nicht alle Auswahlmöglichkeiten abgefragt, sondern nur die aktuellen Werte des Datensatzes (wird z.B. beim Daten-Export verwendet, da hier nur lesend zugegriffen wird und die Datenmengen sehr groß sein können)
														$options = str_ireplace('where', 'where '.$attribute_value_column.'::text = \''.$query_result[$k][$attributes['name'][$i]].'\' AND ', $options);
													}
													$options = str_replace('<requires>'.$attributename.'</requires>', "'".$query_result[$k][$attributename]."'", $options);
												}
											}
											if(strpos($options, '<requires>') !== false){
												#$options = '';    # wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden), sind die abhängigen Optionen für diesen Datensatz leer
												$attribute_value = $query_result[$k][$attributes['name'][$i]];
												if($attribute_value != '')$options = "select '".$attribute_value."' as value, '".$attribute_value."' as output";
												else $options = '';		# wenn in diesem Datensatz des Query-Results ein benötigtes Attribut keinen Wert hat (also nicht alle <requires>-Einträge ersetzt wurden) aber das eigentliche Attribut einen Wert hat, wird dieser Wert als value und output genommen, ansonsten sind die Optionen leer
											}
											$attributes['dependent_options'][$i][$k] = $options;
                    }
                  }
                  else{
                    $attributes['options'][$i] = '';      # wenn kein Query-Result übergeben wurde, sind die Optionen leer
                  }
                }
                # -----<requires>------
                if(is_array($attributes['dependent_options'][$i])){   # mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
                  for($k = 0; $k < count($query_result); $k++){
                    $sql = $attributes['dependent_options'][$i][$k];
                    if($sql != ''){
                      $ret=$database->execSQL($sql,4,0);
                      if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                      while($rs = pg_fetch_array($ret[1])){
                        $attributes['enum_value'][$i][$k][] = $rs['value'];
                        $attributes[$attributes['name'][$i]]['enum_value'][$k][] = $rs['value'];
                        $attributes['enum_output'][$i][$k][] = $rs['output'];
                      }
                    }
                  }
                }
                elseif($attributes['options'][$i] != ''){
                  $sql = str_replace('$stelleid', $stelle_id, $attributes['options'][$i]);
                  $ret=$database->execSQL($sql,4,0);
                  if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
                  while($rs = pg_fetch_array($ret[1])){
                    $attributes['enum_value'][$i][] = $rs['value'];
                    $attributes[$attributes['name'][$i]]['enum_value'][] = $rs['value'];
                    $attributes['enum_output'][$i][] = $rs['output'];
                  }
                }
								# weitere Optionen
                if($optionen[1] != ''){
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
                    if(strpos($further_options[$k], 'layer_id') !== false){     #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
                      $attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
                      $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
                      $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
                    }
                    elseif($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;

					case 'Autovervollständigungsfeld' : {
            if($attributes['options'][$i] != ''){
              if(strpos(strtolower($attributes['options'][$i]), "select") === 0){     # SQl-Abfrage wie select attr1 as value, atrr2 as output from table1
                $optionen = explode(';', $attributes['options'][$i]);  # SQL; weitere Optionen
                $attributes['options'][$i] = $optionen[0];
								if($query_result != NULL){
									for($k = 0; $k < count($query_result); $k++){
										$sql = $attributes['options'][$i];
										$value = $query_result[$k][$attributes['name'][$i]];
										if($value != '' AND !in_array($attributes['operator'][$i], array('LIKE', 'NOT LIKE', 'IN'))){			# falls eine LIKE-Suche oder eine IN-Suche durchgeführt wurde
											$sql = 'SELECT * FROM ('.$sql.') as foo WHERE value = \''.pg_escape_string($value).'\'';
											$ret=$database->execSQL($sql,4,0);
											if ($ret[0]) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1."<p>"; return 0; }
											$rs = pg_fetch_array($ret[1]);
											$attributes['enum_output'][$i][$k] = $rs['output'];
										}
									}
								}
								# weitere Optionen
                if($optionen[1] != ''){
                  $further_options = explode(' ', $optionen[1]);      # die weiteren Optionen exploden (opt1 opt2 opt3)
                  for($k = 0; $k < count($further_options); $k++){
                    if(strpos($further_options[$k], 'layer_id') !== false){     #layer_id=XX bietet die Möglichkeit hier eine Layer_ID zu definieren, für die man einen neuen Datensatz erzeugen kann
                      $attributes['subform_layer_id'][$i] = array_pop(explode('=', $further_options[$k]));
                      $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
                      $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
                    }
                    elseif($further_options[$k] == 'embedded'){       # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }
                  }
                }
              }
            }
          }break;

          # SubFormulare mit Primärschlüssel(n)
          case 'SubFormPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,pkey3...; weitere optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;

          # SubFormulare mit Fremdschlüssel
          case 'SubFormFK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,fkey1,fkey2,fkey3...; weitere optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform); $k++){
                $attributes['subform_fkeys'][$i][] = $subform[$k];
                $attributes['invisible'][$subform[$k]] = 'true';
              }
              if($options[1] != ''){
                if($options[1] == 'no_new_window'){
                  $attributes['no_new_window'][$i] = true;
                }
              }
            }
          }break;

          # eingebettete SubFormulare mit Primärschlüssel(n)
          case 'SubFormEmbeddedPK' : {
            if($attributes['options'][$i] != ''){
              $options = explode(';', $attributes['options'][$i]);  # layer_id,pkey1,pkey2,preview_attribute; weitere Optionen
              $subform = explode(',', $options[0]);
              $attributes['subform_layer_id'][$i] = $subform[0];
              $layer = $this->get_used_Layer($attributes['subform_layer_id'][$i]);
              $attributes['subform_layer_privileg'][$i] = $layer['privileg'];
              for($k = 1; $k < count($subform)-1; $k++){
                $attributes['subform_pkeys'][$i][] = $subform[$k];
              }
              $attributes['preview_attribute'][$i] = $subform[$k];
              if($options[1] != ''){
                $further_options = explode(' ', $options[1]);     # die weiteren Optionen exploden (opt1 opt2 opt3)
                for($k = 0; $k < count($further_options); $k++){
                  switch ($further_options[$k]){
                    case 'no_new_window': {
                      $attributes['no_new_window'][$i] = true;
                    }break;
                    case 'embedded': {                            # Subformular soll embedded angezeigt werden
                      $attributes['embedded'][$i] = true;
                    }break;
                  }
                }
              }
            }
          }break;
        }
      }
    }
    return $attributes;
  }

  function load_attributes($database, $path){
    # Attributname und Typ aus Pfad-Statement auslesen:
    $attributes = $this->getPathAttributes($database, $path);
    return $attributes;
  }

	function save_postgis_attributes($layer_id, $attributes, $maintable, $schema){
		for($i = 0; $i < count($attributes); $i++){
			if($attributes[$i] == NULL)continue;
			if($attributes[$i]['nullable'] == '')$attributes[$i]['nullable'] = 'NULL';
			if($attributes[$i]['length'] == '')$attributes[$i]['length'] = 'NULL';
			if($attributes[$i]['decimal_length'] == '')$attributes[$i]['decimal_length'] = 'NULL';
			$sql = "INSERT INTO layer_attributes SET ";
			$sql.= "layer_id = ".$layer_id.", ";
			$sql.= "name = '".$attributes[$i]['name']."', ";
			$sql.= "real_name = '".$attributes[$i]['real_name']."', ";
			$sql.= "tablename = '".$attributes[$i]['table_name']."', ";
			$sql.= "table_alias_name = '".$attributes[$i]['table_alias_name']."', ";
			$sql.= "type = '".$attributes[$i]['type']."', ";
			$sql.= "geometrytype = '".$attributes[$i]['geomtype']."', ";
			$sql.= "constraints = '".addslashes($attributes[$i]['constraints'])."', ";
			$sql.= "nullable = ".$attributes[$i]['nullable'].", ";
			$sql.= "length = ".$attributes[$i]['length'].", ";			
			$sql.= "decimal_length = ".$attributes[$i]['decimal_length'].", ";
			$sql.= "`default` = '".addslashes($attributes[$i]['default'])."', ";
			$sql.= "`order` = ".$i;
			$sql.= " ON DUPLICATE KEY UPDATE ";
			$sql.= "real_name = '".$attributes[$i]['real_name']."', ";
			$sql.= "tablename = '".$attributes[$i]['table_name']."', ";
			$sql.= "table_alias_name = '".$attributes[$i]['table_alias_name']."', ";
			$sql.= "type = '".$attributes[$i]['type']."', ";
			$sql.= "geometrytype = '".$attributes[$i]['geomtype']."', ";
			$sql.= "constraints = '".addslashes($attributes[$i]['constraints'])."', ";
			$sql.= "nullable = ".$attributes[$i]['nullable'].", ";
			$sql.= "length = ".$attributes[$i]['length'].", ";
			$sql.= "decimal_length = ".$attributes[$i]['decimal_length'].", ";
			$sql.= "`default` = '".addslashes($attributes[$i]['default'])."', ";
			$sql.= "`order` = ".$i;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		}
    	
		if($maintable == ''){
			$maintable = $attributes[0]['table_name'];
			$sql = "UPDATE layer SET maintable = '".$maintable."' WHERE (maintable IS NULL OR maintable = '') AND Layer_ID = ".$layer_id;
			$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		}
			
		$sql = "select 1 from information_schema.views WHERE table_name = '".$maintable."' AND table_schema = '".$schema."'";
		$query = pg_query($sql);
		$is_view = pg_num_rows($query);
		$sql = "UPDATE layer SET maintable_is_view = ".$is_view." WHERE Layer_ID = ".$layer_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    	
		# den PRIMARY KEY constraint rausnehmen, falls der tablename nicht der maintable entspricht
		$sql = "UPDATE layer_attributes, layer SET constraints = '' WHERE layer_attributes.layer_id = ".$layer_id." AND layer.Layer_ID = ".$layer_id." AND constraints = 'PRIMARY KEY' AND tablename != maintable";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->save_postgis_attributes - Speichern der Layerattribute:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }	
	}

  function delete_old_attributes($layer_id, $attributes){
  	$sql = "DELETE FROM layer_attributes WHERE layer_id = ".$layer_id;
  	if($attributes){
  		$sql.= " AND name NOT IN (";
	  	for($i = 0; $i < count($attributes); $i++){
	  		$sql .= "'".$attributes[$i]['name']."',";
	  	}
	  	$sql = substr($sql, 0, -1);
	  	$sql .=")";
  	}
  	#echo $sql.'<br><br>';
  	$this->debug->write("<p>file:kvwmap class:db_mapObj->delete_old_attributes - Löschen von alten Layerattributen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

	function create_layer_dumpfile($database, $layer_ids){
		$sql .= 'SET @group_id = 1;'.chr(10);
		$sql .= 'SET @connection = \'user=xxxx password=xxxx dbname=kvwmapsp\';'.chr(10).chr(10);
		for($i = 0; $i < count($layer_ids); $i++){
			$layer = $database->create_insert_dump('layer', '', 'SELECT `Name`, `alias`, `Datentyp`, \'@group_id\' AS `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `printconnection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `offsite`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, wms_auth_username, wms_auth_password, `wfs_geom`, `selectiontype`, `querymap`, `logconsume`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `privileg` FROM layer WHERE Layer_ID='.$layer_ids[$i]);
			$sql .= $layer['insert'][0];
			$last_layer_id = '@last_layer_id'.$layer_ids[$i];
			$sql .= chr(10).'SET '.$last_layer_id.'=LAST_INSERT_ID();'.chr(10);
			$classes = $database->create_insert_dump('classes', 'Class_ID', 'SELECT `Class_ID`, `Name`, \''.$last_layer_id.'\' AS `Layer_ID`, `Expression`, `drawingorder`, `text` FROM classes WHERE Layer_ID='.$layer_ids[$i]);
			$layer_attributes = $database->create_insert_dump('layer_attributes', '', 'SELECT \''.$last_layer_id.'\' AS `layer_id`, `name`, real_name, tablename, table_alias_name, `type`, geometrytype, constraints, nullable, length, decimal_length, `default`, form_element_type, options, alias, `alias_low-german`, alias_english, alias_polish, alias_vietnamese, tooltip, `group`, `raster_visibility`, `mandatory`, `order`, `privileg`, query_tooltip FROM layer_attributes WHERE layer_id = '.$layer_ids[$i]);
			for($j = 0; $j < count($layer_attributes['insert']); $j++){
				$sql .= $layer_attributes['insert'][$j].chr(10);
			}
			for($j = 0; $j < count($classes['insert']); $j++){
				$sql .= $classes['insert'][$j];
				$sql .= chr(10).'SET @last_class_id=LAST_INSERT_ID();'.chr(10);
				$styles = $database->create_insert_dump('styles', '', 'SELECT `symbol`,`symbolname`,`size`,`color`,`backgroundcolor`,`outlinecolor`,`minsize`,`maxsize`,`angle`,`angleitem`,`antialias`,`width`,`minwidth`,`maxwidth`,`sizeitem` FROM styles, u_styles2classes WHERE u_styles2classes.style_id = styles.Style_ID AND Class_ID='.$classes['extra'][$j].' ORDER BY drawingorder');
				for($k = 0; $k < count($styles['insert']); $k++){
					$sql .= $styles['insert'][$k];
					$sql .= chr(10).' SET @last_style_id=LAST_INSERT_ID();'.chr(10);
					$sql .= 'INSERT INTO u_styles2classes (style_id, class_id, drawingorder) VALUES (@last_style_id, @last_class_id, '.$k.');'.chr(10);
				}
				$labels = $database->create_insert_dump('labels', '', 'SELECT `font`,`type`,`color`,`outlinecolor`,`shadowcolor`,`shadowsizex`,`shadowsizey`,`backgroundcolor`,`backgroundshadowcolor`,`backgroundshadowsizex`,`backgroundshadowsizey`,`size`,`minsize`,`maxsize`,`position`,`offsetx`,`offsety`,`angle`,`autoangle`,`buffer`,`antialias`,`minfeaturesize`,`maxfeaturesize`,`partials`,`wrap`,`the_force` FROM labels, u_labels2classes WHERE u_labels2classes.label_id = labels.Label_ID AND Class_ID='.$classes['extra'][$j]);
				for($k = 0; $k < count($labels['insert']); $k++){
					$sql .= $labels['insert'][$k];
					$sql .= chr(10).' SET @last_label_id=LAST_INSERT_ID();'.chr(10);
					$sql .= 'INSERT INTO u_labels2classes (label_id, class_id) VALUES (@last_label_id, @last_class_id);'.chr(10);
				}
			}
			$sql .= chr(10);
		}
		for($i = 0; $i < count($layer_ids); $i++){
			$sql .= 'UPDATE layer_attributes SET options = REPLACE(options, \''.$layer_ids[$i].'\', @last_layer_id'.$layer_ids[$i].') WHERE layer_id IN(@last_layer_id'.implode(', @last_layer_id', $layer_ids).') AND form_element_type IN (\'SubFormPK\', \'SubFormFK\', \'SubFormEmbeddedPK\', \'Autovervollständigungsfeld\', \'Auswahlfeld\');'.chr(10);
		}
		$filename = rand(0, 1000000).'.sql';
		$fp = fopen(IMAGEPATH.$filename, 'w');
		fwrite($fp, utf8_decode($sql));
		return $filename;
	}

  function deleteLayer($id){
    $sql = 'DELETE FROM layer WHERE Layer_ID = '.$id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Löschen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    if(MYSQLVERSION > 412){
      # Den Autowert für die Layer_id zurücksetzen
      $sql ="ALTER TABLE layer AUTO_INCREMENT = 1";
      $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteLayer - Zurücksetzen des Auto_Incrementwertes:<br>".$sql,4);
      #echo $sql;
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    }
  }

  function deleteRollenLayer($id){
  	$sql = 'SELECT Typ, Data FROM rollenlayer WHERE id = '.$id;
  	$query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $rs=mysql_fetch_array($query);
    if($rs['Typ'] == 'import'){		# beim Shape-Import-Layern die Tabelle löschen
    	$explosion = explode(CUSTOM_SHAPE_SCHEMA.'.', $rs['Data']);
			$explosion = explode(' ', $explosion[1]);
			$sql = "SELECT count(id) FROM rollenlayer WHERE Data like '%".$explosion[0]."%'";
			$query=mysql_query($sql);
			if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
			$rs=mysql_fetch_array($query);
			if($rs[0] == 1){		# Tabelle nur löschen, wenn das der einzige Layer ist, der sie benutzt
				$sql = 'DROP TABLE IF EXISTS '.CUSTOM_SHAPE_SCHEMA.'.'.$explosion[0].';';
				$sql.= 'DELETE FROM geometry_columns WHERE f_table_schema = \''.CUSTOM_SHAPE_SCHEMA.'\' AND f_table_name = \''.$explosion[0].'\'';
				$this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>".$sql,4);
				$query=pg_query($sql);
			}
    }
    $sql = 'DELETE FROM rollenlayer WHERE id = '.$id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Löschen eines RollenLayers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    if(MYSQLVERSION > 412){
      # Den Autowert für die Layer_id zurücksetzen
      $sql ="ALTER TABLE rollenlayer AUTO_INCREMENT = 1";
      $this->debug->write("<p>file:kvwmap class:db_mapObj->deleteRollenLayer - Zurücksetzen des Auto_Incrementwertes:<br>".$sql,4);
      #echo $sql;
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    }
  }

  function newRollenLayer($formvars){
    $formvars['Data'] = str_replace ( "'", "''", $formvars['Data']);
		$formvars['query'] = str_replace ( "'", "''", $formvars['query']);

    $sql = "INSERT INTO rollenlayer (`user_id`, `stelle_id`, `aktivStatus`, `Name`, `Datentyp`, `Gruppe`, `Typ`, `Data`, `query`, `connection`, `connectiontype`, `transparency`, `epsg_code`, `labelitem`) VALUES(";
    $sql .= "'".$formvars['user_id']."', ";
    $sql .= "'".$formvars['stelle_id']."', ";
    $sql .= "'".$formvars['aktivStatus']."', ";
    $sql .= "'".addslashes($formvars['Name'])."', ";
    $sql .= "'".$formvars['Datentyp']."', ";
    $sql .= "'".$formvars['Gruppe']."', ";
    $sql .= "'".$formvars['Typ']."', ";
    $sql .= "'".$formvars['Data']."', ";
		$sql .= "'".$formvars['query']."', ";
    $sql .= "'".$formvars['connection']."', ";
    $sql .= "'".$formvars['connectiontype']."', ";
    $sql .= "'".$formvars['transparency']."', ";
    $sql .= "'".$formvars['epsg_code']."', ";
    $sql .= "'".$formvars['labelitem']."'";
    $sql .= ")";
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newRollenLayer - Erzeugen eines RollenLayers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    return mysql_insert_id();
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
      $classdata['expression'] = "('[".$attribute."]' eq '".$value."')";
      $classdata['order'] = 0;
      $class_id = $this->new_Class($classdata);
    	$style['colorred'] = $result_colors[$i]['red'];
      $style['colorgreen'] = $result_colors[$i]['green'];
      $style['colorblue'] = $result_colors[$i]['blue'];
      $style['outlinecolorred'] = 0;
      $style['outlinecolorgreen'] = 0;
      $style['outlinecolorblue'] = 0;
     	$style['size'] = 3;
     	if($datatype < 2){
      	$style['symbolname'] = 'circle';
      	if($datatype == 0){
      		$style['size'] = 13;
      		$style['minsize'] = 5;
      		$style['maxsize'] = 20;
      	}
     	}
      $style['backgroundcolor'] = NULL;
      if (MAPSERVERVERSION > '500') {
      	$style['angle'] = 360;
      }
      $style_id = $this->new_Style($style);
      $this->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
			$i++;
		}
	}

  function updateLayer($formvars){
		global $supportedLanguages;
  	$formvars['pfad'] = str_replace(array("\r\n", "\n"), '', $formvars['pfad']);

    $sql = 'UPDATE layer SET ';
    if($formvars['id'] != ''){
      $sql.="Layer_ID = ".$formvars['id'].", ";
    }
    $sql .= "Name = '".$formvars['Name']."', ";
		foreach($supportedLanguages as $language){
			if($language != 'german'){
				$sql .= "`Name_".$language."` = '".$formvars['Name_'.$language]."', ";
			}
		}
    $sql .= "alias = '".$formvars['alias']."', ";
    $sql .= "Datentyp = '".$formvars['Datentyp']."', ";
    $sql .= "Gruppe = '".$formvars['Gruppe']."', ";
    $sql .= "pfad = '".$formvars['pfad']."', ";
    $sql .= "maintable = '".$formvars['maintable']."', ";
    $sql .= "Data = '".$formvars['Data']."', ";
    $sql .= "`schema` = '".$formvars['schema']."', ";
    $sql .= "document_path = '".$formvars['document_path']."', ";
    $sql .= "tileindex = '".$formvars['tileindex']."', ";
    $sql .= "tileitem = '".$formvars['tileitem']."', ";
    $sql .= "labelangleitem = '".$formvars['labelangleitem']."', ";
    $sql .= "labelitem = '".$formvars['labelitem']."', ";
    if ($formvars['labelmaxscale']!='') {
      $sql .= "labelmaxscale = ".$formvars['labelmaxscale'].", ";
    }
    if ($formvars['labelminscale']!='') {
      $sql .= "labelminscale = ".$formvars['labelminscale'].", ";
    }
    $sql .= "labelrequires = '".$formvars['labelrequires']."', ";
		$sql .= "postlabelcache = '".$formvars['postlabelcache']."', ";
    $sql .= "`connection` = '".$formvars['connection']."', ";
    $sql .= "`printconnection` = '".$formvars['printconnection']."', ";
    $sql .= "connectiontype = '".$formvars['connectiontype']."', ";
    $sql .= "classitem = '".$formvars['classitem']."', ";
		$sql .= "classification = '".$formvars['layer_classification']."', ";		
    $sql .= "filteritem = '".$formvars['filteritem']."', ";
		if($formvars['cluster_maxdistance'] == '')$formvars['cluster_maxdistance'] = 'NULL';
		$sql .= "cluster_maxdistance = ".$formvars['cluster_maxdistance'].", ";
    $sql .= "tolerance = '".$formvars['tolerance']."', ";
    $sql .= "toleranceunits = '".$formvars['toleranceunits']."', ";
    $sql .= "epsg_code = '".$formvars['epsg_code']."', ";
    $sql .= "template = '".$formvars['template']."', ";
    $sql .= "queryable = '".$formvars['queryable']."', ";
    if($formvars['transparency'] == ''){$formvars['transparency'] = 'NULL';}
    $sql .= "transparency = ".$formvars['transparency'].", ";
    if($formvars['drawingorder'] == ''){$formvars['drawingorder'] = 'NULL';}
    $sql .= "drawingorder = ".$formvars['drawingorder'].", ";
    if($formvars['minscale'] == ''){$formvars['minscale'] = 'NULL';}
    $sql .= "minscale = ".$formvars['minscale'].", ";
    if($formvars['maxscale'] == ''){$formvars['maxscale'] = 'NULL';}
    $sql .= "maxscale = ".$formvars['maxscale'].", ";
		if($formvars['symbolscale'] == ''){$formvars['symbolscale'] = 'NULL';}
    $sql .= "symbolscale = ".$formvars['symbolscale'].", ";
    $sql .= "offsite = '".$formvars['offsite']."', ";
		if($formvars['requires'] == ''){$formvars['requires'] = 'NULL';}
		$sql .= "requires = ".$formvars['requires'].", ";
    $sql .= "ows_srs = '".$formvars['ows_srs']."', ";
    $sql .= "wms_name = '".$formvars['wms_name']."', ";
    $sql .= "wms_server_version = '".$formvars['wms_server_version']."', ";
    $sql .= "wms_format = '".$formvars['wms_format']."', ";
    $sql .= "wms_connectiontimeout = '".$formvars['wms_connectiontimeout']."', ";
    $sql .= "wms_auth_username = '".$formvars['wms_auth_username']."', ";
    $sql .= "wms_auth_password = '".$formvars['wms_auth_password']."', ";
    $sql .= "wfs_geom = '".$formvars['wfs_geom']."', ";
    $sql .= "selectiontype = '".$formvars['selectiontype']."',";
    $sql .= "querymap = '".$formvars['querymap']."',";
    $sql .= "processing = '".$formvars['processing']."',";
    $sql .= "kurzbeschreibung = '".$formvars['kurzbeschreibung']."',";
    $sql .= "datenherr = '".$formvars['datenherr']."',";
    $sql .= "metalink = '".$formvars['metalink']."', ";
		$sql .= "status = '".$formvars['status']."', ";
		$sql .= "trigger_function = '" . $formvars['trigger_function'] . "'";
    $sql .= " WHERE Layer_ID = ".$formvars['selected_layer_id'];
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->updateLayer - Aktualisieren eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function newLayer($layerdata) {
		global $supportedLanguages;
    # Erzeugt einen neuen Layer (entweder aus formvars oder aus einem Layerobjekt)
    if(is_array($layerdata)){
      $formvars = $layerdata;   # formvars wurden übergeben

      $sql = "INSERT INTO layer (";
      if($formvars['id'] != ''){
        $sql.="`Layer_ID`, ";
      }
      $sql.= "`Name`, ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql .= "`Name_".$language."`, ";
				}
			}
			$sql.="`alias`, `Datentyp`, `Gruppe`, `pfad`, `maintable`, `Data`, `schema`, `document_path`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `postlabelcache`, `connection`, `printconnection`, `connectiontype`, `classitem`, `classification`, `filteritem`, `cluster_maxdistance`, `tolerance`, `toleranceunits`, `epsg_code`, `template`, `queryable`, `transparency`, `drawingorder`, `minscale`, `maxscale`, `symbolscale`, `offsite`, `requires`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`, `wms_auth_username`, `wms_auth_password`, `wfs_geom`, `selectiontype`, `querymap`, `processing`, `kurzbeschreibung`, `datenherr`, `metalink`, `status`) VALUES(";
      if($formvars['id'] != ''){
        $sql.="'".$formvars['id']."', ";
      }
      $sql .= "'".$formvars['Name']."', ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql .= "'".$formvars['Name_'.$language]."', ";
				}
			}
      $sql .= "'".$formvars['alias']."', ";
      $sql .= "'".$formvars['Datentyp']."', ";
      $sql .= "'".$formvars['Gruppe']."', ";
      if($formvars['pfad'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['pfad']."', ";
      }
    	if($formvars['maintable'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['maintable']."', ";
      }
      if($formvars['Data'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['Data']."', ";
      }
      if($formvars['schema'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['schema']."', ";
      }
      if($formvars['document_path'] == ''){
        $sql .= "NULL, ";
      }
      else{
        $sql .= "'".$formvars['document_path']."', ";
      }
      $sql .= "'".$formvars['tileindex']."', ";
      $sql .= "'".$formvars['tileitem']."', ";
      $sql .= "'".$formvars['labelangleitem']."', ";
      $sql .= "'".$formvars['labelitem']."', ";
      if($formvars['labelmaxscale']==''){$formvars['labelmaxscale']='NULL';}
      $sql .= $formvars['labelmaxscale'].", ";
      if($formvars['labelminscale']==''){$formvars['labelminscale']='NULL';}
      $sql .= $formvars['labelminscale'].", ";
      $sql .= "'".$formvars['labelrequires']."', ";
			$sql .= "'".$formvars['postlabelcache']."', ";
      $sql .= "'".$formvars['connection']."', ";
      $sql .= "'".$formvars['printconnection']."', ";
      $sql .= $formvars['connectiontype'].", ";
      $sql .= "'".$formvars['classitem']."', ";
			$sql .= "'".$formvars['layer_classification']."', ";
      $sql .= "'".$formvars['filteritem']."', ";
			if($formvars['cluster_maxdistance'] == '')$formvars['cluster_maxdistance'] = 'NULL';
			$sql .= $formvars['cluster_maxdistance'].", ";
      if($formvars['tolerance']==''){$formvars['tolerance']='3';}
      $sql .= $formvars['tolerance'].", ";
      if($formvars['toleranceunits']==''){$formvars['toleranceunits']='pixels';}
      $sql .= "'".$formvars['toleranceunits']."', ";
      $sql .= "'".$formvars['epsg_code']."', ";
      $sql .= "'".$formvars['template']."', ";
      $sql .= "'".$formvars['queryable']."', ";
      if($formvars['transparency']==''){$formvars['transparency']='NULL';}
      $sql .= $formvars['transparency'].", ";
      if($formvars['drawingorder']==''){$formvars['drawingorder']='NULL';}
      $sql .= $formvars['drawingorder'].", ";
      if($formvars['minscale']==''){$formvars['minscale']='NULL';}
      $sql .= $formvars['minscale'].", ";
      if($formvars['maxscale']==''){$formvars['maxscale']='NULL';}
      $sql .= $formvars['maxscale'].", ";
			if($formvars['symbolscale']==''){$formvars['symbolscale']='NULL';}
      $sql .= $formvars['symbolscale'].", ";
      $sql .= "'".$formvars['offsite']."', ";
			if($formvars['requires']==''){$formvars['requires']='NULL';}
      $sql .= $formvars['requires'].", ";
      $sql .= "'".$formvars['ows_srs']."', ";
      $sql .= "'".$formvars['wms_name']."', ";
      $sql .= "'".$formvars['wms_server_version']."', ";
      $sql .= "'".$formvars['wms_format']."', ";
      if ($formvars['wms_connectiontimeout']=='') {
        $formvars['wms_connectiontimeout']='60';
      }
      $sql .= $formvars['wms_connectiontimeout'].", ";
      $sql .= "'".$formvars['wms_auth_username']."', ";
      $sql .= "'".$formvars['wms_auth_password']."', ";
      $sql .= "'".$formvars['wfs_geom']."', ";
      $sql .= "'".$formvars['selectiontype']."', ";
      $sql .= "'".$formvars['querymap']."', ";
      $sql .= "'".$formvars['processing']."', ";
      $sql .= "'".$formvars['kurzbeschreibung']."', ";
      $sql .= "'".$formvars['datenherr']."', ";
      $sql .= "'".$formvars['metalink']."', ";
			$sql .= "'".$formvars['status']."'";
      $sql .= ")";

    }
    else{
      $layer = $layerdata;      # ein Layerobject wurde übergeben
      $projection = explode('epsg:', $layer->getProjection());
      $sql = "INSERT INTO layer (`Name`, `Datentyp`, `Gruppe`, `pfad`, `Data`, `tileindex`, `tileitem`, `labelangleitem`, `labelitem`, `labelmaxscale`, `labelminscale`, `labelrequires`, `connection`, `connectiontype`, `classitem`, `filteritem`, `tolerance`, `toleranceunits`, `epsg_code`, `ows_srs`, `wms_name`, `wms_server_version`, `wms_format`, `wms_connectiontimeout`) VALUES(";
      $sql .= "'".$layer->name."', ";
      $sql .= "'".$layer->type."', ";
      $sql .= "'".$layer->group."', ";
      $sql .= "'', ";                 # pfad
      $sql .= "'".$layer->data."', ";
      $sql .= "'".$layer->tileindex."', ";
      $sql .= "'".$layer->tileitem."', ";
      $sql .= "'".$layer->labelangleitem."', ";
      $sql .= "'".$layer->labelitem."', ";
      $sql .= $layer->labelmaxscale.", ";
      $sql .= $layer->labelminscale.", ";
      $sql .= "'".$layer->labelrequires."', ";
      $sql .= "'".$layer->connection."', ";
      $sql .= $layer->connectiontype.", ";
      $sql .= "'".$layer->classitem."', ";
      $sql .= "'".$layer->filteritem."', ";
      $sql .= $layer->tolerance.", ";
      $sql .= "'".$layer->toleranceunits."', ";
      $sql .= "'".$projection[1]."', ";               # epsg_code
      $sql .= "'', ";               # ows_srs
      $sql .= "'', ";               # wms_name
      $sql .= "'', ";               # wms_server_version
      $sql .= "'', ";               # wms_format
      $sql .= "60";                 # wms_connectiontimeout
      $sql .= ")";
    }

    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newLayer - Erzeugen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }

    return mysql_insert_id();
  }

  function save_datatype_attributes($attributes, $database, $formvars){
		global $supportedLanguages;
    for($i = 0; $i < count($attributes['name']); $i++){
      $sql = 'INSERT INTO datatype_attributes SET ';
      $sql.= 'datatype_id = '.$formvars['selected_datatype_id'].', ';
      $sql.= 'name = "'.$attributes['name'][$i].'", ';
      $sql.= 'form_element_type = "'.$formvars['form_element_'.$attributes['name'][$i]].'", ';
      $sql.= "options = '".$formvars['options_'.$attributes['name'][$i]]."', ";
      $sql.= 'tooltip = "'.$formvars['tooltip_'.$attributes['name'][$i]].'", ';
      $sql.= '`group` = "'.$formvars['group_'.$attributes['name'][$i]].'", ';
			if($formvars['raster_visibility_'.$attributes['name'][$i]] == '')$formvars['raster_visibility_'.$attributes['name'][$i]] = 'NULL';
      $sql.= 'raster_visibility = '.$formvars['raster_visibility_'.$attributes['name'][$i]].', ';
      if($formvars['mandatory_'.$attributes['name'][$i]] == '')$formvars['mandatory_'.$attributes['name'][$i]] = 'NULL';
      $sql.= 'mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].', ';
      $sql.= 'alias = "'.$formvars['alias_'.$attributes['name'][$i]].'", ';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '`alias_'.$language.'` = "'.$formvars['alias_'.$language.'_'.$attributes['name'][$i]].'", ';
				}
			}
			if($formvars['quicksearch_'.$attributes['name'][$i]] == '')$formvars['quicksearch_'.$attributes['name'][$i]] = 'NULL';
			$sql.= 'quicksearch = '.$formvars['quicksearch_'.$attributes['name'][$i]];
      $sql.= " ON DUPLICATE KEY UPDATE name = '".$attributes['name'][$i]."', form_element_type = '".$formvars['form_element_'.$attributes['name'][$i]]."', options = '".$formvars['options_'.$attributes['name'][$i]]."', tooltip = '".$formvars['tooltip_'.$attributes['name'][$i]]."', `group` = '".$formvars['group_'.$attributes['name'][$i]]."', alias = '".$formvars['alias_'.$attributes['name'][$i]]."', ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '`alias_'.$language.'` = "'.$formvars['alias_'.$language.'_'.$attributes['name'][$i]].'", ';
				}
			}
			$sql.= ' raster_visibility = '.$formvars['raster_visibility_'.$attributes['name'][$i]].', mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].' , quicksearch = '.$formvars['quicksearch_'.$attributes['name'][$i]];
			#echo '<br>' . $sql;
      $this->debug->write("<p>file:kvwmap class:Document->save_datatype_attributes :",4);
      $database->execSQL($sql,4, 1);
    }
  }

  function save_layer_attributes($attributes, $database, $formvars){
		global $supportedLanguages;
    for($i = 0; $i < count($attributes['name']); $i++){
      $sql = 'INSERT INTO layer_attributes SET ';
      $sql.= 'layer_id = '.$formvars['selected_layer_id'].', ';
      $sql.= 'name = "'.$attributes['name'][$i].'", ';
      $sql.= 'form_element_type = "'.$formvars['form_element_'.$attributes['name'][$i]].'", ';
      $sql.= "options = '".$formvars['options_'.$attributes['name'][$i]]."', ";
      $sql.= 'tooltip = "'.$formvars['tooltip_'.$attributes['name'][$i]].'", ';
      $sql.= '`group` = "'.$formvars['group_'.$attributes['name'][$i]].'", ';
			$sql.= 'arrangement = '.$formvars['arrangement_'.$attributes['name'][$i]].', ';
			$sql.= 'labeling = '.$formvars['labeling_'.$attributes['name'][$i]].', ';
			if($formvars['raster_visibility_'.$attributes['name'][$i]] == '')$formvars['raster_visibility_'.$attributes['name'][$i]] = 'NULL';
      $sql.= 'raster_visibility = '.$formvars['raster_visibility_'.$attributes['name'][$i]].', ';
      if($formvars['mandatory_'.$attributes['name'][$i]] == '')$formvars['mandatory_'.$attributes['name'][$i]] = 'NULL';
      $sql.= 'mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].', ';
      $sql.= 'alias = "'.$formvars['alias_'.$attributes['name'][$i]].'", ';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '`alias_'.$language.'` = "'.$formvars['alias_'.$language.'_'.$attributes['name'][$i]].'", ';
				}
			}
			if($formvars['quicksearch_'.$attributes['name'][$i]] == '')$formvars['quicksearch_'.$attributes['name'][$i]] = 'NULL';
			$sql.= 'quicksearch = '.$formvars['quicksearch_'.$attributes['name'][$i]];
      $sql.= " ON DUPLICATE KEY UPDATE name = '".$attributes['name'][$i]."', form_element_type = '".$formvars['form_element_'.$attributes['name'][$i]]."', options = '".$formvars['options_'.$attributes['name'][$i]]."', tooltip = '".$formvars['tooltip_'.$attributes['name'][$i]]."', `group` = '".$formvars['group_'.$attributes['name'][$i]]."', arrangement = ".$formvars['arrangement_'.$attributes['name'][$i]].", labeling = ".$formvars['labeling_'.$attributes['name'][$i]].", alias = '".$formvars['alias_'.$attributes['name'][$i]]."', ";
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '`alias_'.$language.'` = "'.$formvars['alias_'.$language.'_'.$attributes['name'][$i]].'", ';
				}
			}
			$sql.= ' raster_visibility = '.$formvars['raster_visibility_'.$attributes['name'][$i]].', mandatory = '.$formvars['mandatory_'.$attributes['name'][$i]].' , quicksearch = '.$formvars['quicksearch_'.$attributes['name'][$i]];
      $this->debug->write("<p>file:kvwmap class:Document->save_layer_attributes :",4);
      $database->execSQL($sql,4, 1);
    }
  }

	function delete_layer_filterattributes($layer_id){
    $sql = 'DELETE FROM u_attributfilter2used_layer WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_filterattributes:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function delete_layer_attributes($layer_id){
    $sql = 'DELETE FROM layer_attributes WHERE layer_id = '.$layer_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function delete_layer_attributes2stelle($layer_id, $stelle_id){
    $sql = 'DELETE FROM layer_attributes2stelle WHERE layer_id = '.$layer_id.' AND stelle_id = '.$stelle_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_layer_attributes2stelle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
  }

  function read_datatype_attributes($datatype_id, $datatypedb, $attributenames, $all_languages = false, $recursive = false){
		global $language;
		if($attributenames != NULL){
			$einschr = ' AND a.name IN (\'';
			$einschr.= implode('\', \'', $attributenames);
			$einschr.= '\')';
		}
		$sql = 'SELECT ';
		if(!$all_languages AND $language != 'german') {
			$sql.='CASE WHEN `alias_'.$language.'` != "" THEN `alias_'.$language.'` ELSE `alias` END AS ';
		}
		$sql.='alias, `alias_low-german`, alias_english, alias_polish, alias_vietnamese, datatype_id, a.name, real_name, tablename, table_alias_name, type, d.name as typename, geometrytype, constraints, nullable, length, decimal_length, `default`, form_element_type, options, tooltip, `group`, raster_visibility, mandatory, quicksearch, `order`, privileg, query_tooltip ';
		$sql.='FROM datatype_attributes as a ';
		$sql.='LEFT JOIN datatypes as d ON d.id = REPLACE(type, \'_\', \'\') ';
		$sql.='WHERE datatype_id = '.$datatype_id.$einschr.' ORDER BY `order`';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_datatype_attributes:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$i = 0;
		while($rs=mysql_fetch_array($query)){
			$attributes['name'][$i]= $rs['name'];
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
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($type, $layerdb, NULL, $all_languages, true);
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
			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $this->user->rolle->language, $rs['options']);
			$attributes['options'][$i]= $rs['options'];
			$attributes['options'][$rs['name']]= $rs['options'];
			$attributes['alias'][$i]= $rs['alias'];
			$attributes['alias'][$attributes['name'][$i]]= $rs['alias'];
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
			$i++;
		}
		return $attributes;
  }
  
  function read_layer_attributes($layer_id, $layerdb, $attributenames, $all_languages = false, $recursive = false){
		global $language;
		if($attributenames != NULL){
			$einschr = ' AND a.name IN (\'';
			$einschr.= implode('\', \'', $attributenames);
			$einschr.= '\')';
		}
		$sql = 'SELECT ';
		if(!$all_languages AND $language != 'german') {
			$sql.='CASE WHEN `alias_'.$language.'` != "" THEN `alias_'.$language.'` ELSE `alias` END AS ';
		}
		$sql.='alias, `alias_low-german`, alias_english, alias_polish, alias_vietnamese, layer_id, a.name, real_name, tablename, table_alias_name, type, d.name as typename, geometrytype, constraints, nullable, length, decimal_length, `default`, form_element_type, options, tooltip, `group`, arrangement, labeling, raster_visibility, mandatory, quicksearch, `order`, privileg, query_tooltip ';
		$sql.='FROM layer_attributes as a ';
		$sql.='LEFT JOIN datatypes as d ON d.id = REPLACE(type, \'_\', \'\') ';
		$sql.='WHERE layer_id = '.$layer_id.$einschr.' ORDER BY `order`';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->read_layer_attributes:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		$i = 0;
		while($rs=mysql_fetch_array($query)){
			$attributes['name'][$i]= $rs['name'];
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
				$attributes['type_attributes'][$i] = $this->read_datatype_attributes($type, $layerdb, NULL, $all_languages, true);
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

			if(substr($rs['default'], 0, 6) == 'SELECT'){					# da Defaultvalues auch dynamisch sein können (z.B. 'now'::date) wird der Defaultwert erst hier ermittelt
				$ret1 = $layerdb->execSQL($rs['default'], 4, 0);
				if($ret1[0]==0){
					$attributes['default'][$i] = array_pop(pg_fetch_row($ret1[1]));
				}
			}
			else{															# das sind die alten Defaultwerte ohne 'SELECT ' davor, ab Version 1.13 haben Defaultwerte immer ein SELECT, wenn man den Layer in dieser Version einmal gespeichert hat
				$attributes['default'][$i]= $rs['default'];
			}
			$attributes['form_element_type'][$i]= $rs['form_element_type'];
			$attributes['form_element_type'][$rs['name']]= $rs['form_element_type'];
			$rs['options'] = str_replace('$hist_timestamp', rolle::$hist_timestamp, $rs['options']);
			$rs['options'] = str_replace('$language', $this->user->rolle->language, $rs['options']);
			$attributes['options'][$i]= $rs['options'];
			$attributes['options'][$rs['name']]= $rs['options'];
			$attributes['alias'][$i]= $rs['alias'];
			$attributes['alias'][$attributes['name'][$i]]= $rs['alias'];
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
			$i++;
		}
		if($attributes['table_name'] != NULL){
			$attributes['all_table_names'] = array_unique($attributes['table_name']);
			//$attributes['all_alias_table_names'] = array_values(array_unique($attributes['table_alias_name']));
			foreach($attributes['all_table_names'] as $tablename){
				$attributes['oids'][] = $layerdb->check_oid($tablename);   # testen ob Tabelle oid hat
			}
		}
		else{
			$attributes['all_table_names'] = array();
		}
		return $attributes;
  }

	function getall_Datatypes($order) {
		$datatypes = array();
		$order_sql = ($order != '') ? "ORDER BY " . $order : '';
		$sql = "
			SELECT
				*
			FROM
				datatypes
			" . $order_sql;

		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Datatypes - Lesen aller Datentypen:<br>" . $sql , 4);
		$query = mysql_query($sql);
		if ($query == 0) {
			echo "<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>" . INFO1; return 0;
		}
		while($rs = mysql_fetch_assoc($query)) {
			/*
			foreach($rs AS $key => $value) {
				$datatypes[$key][] = $value;
			}
			*/
			$datatypes[] = $rs;
		}
		return $datatypes;
	}

	function getall_Layer($order) {
		global $language;
		$sql ='SELECT ';
		if($language != 'german') {
			$sql.='CASE WHEN `Name_'.$language.'` != "" THEN `Name_'.$language.'` ELSE `Name` END AS ';
		}
		$sql.='Name, Layer_ID, Gruppe, kurzbeschreibung, datenherr, alias, ';
		if($language != 'german') {
			$sql.='CASE WHEN `Gruppenname_'.$language.'` != "" THEN `Gruppenname_'.$language.'` ELSE `Gruppenname` END AS ';
		}
		$sql.='Gruppenname FROM layer, u_groups';
		$sql.=' WHERE layer.Gruppe = u_groups.id';
		if($order != ''){$sql .= ' ORDER BY '.$order;}
		$this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
			$layer['ID'][]=$rs['Layer_ID'];
			$layer['Bezeichnung'][]=$rs['Name'];
			$layer['Gruppe'][]=$rs['Gruppenname'];
			$layer['GruppeID'][]=$rs['Gruppe'];
			$layer['Kurzbeschreibung'][]=$rs['kurzbeschreibung'];
			$layer['Datenherr'][]=$rs['datenherr'];
			$layer['alias'][]=$rs['alias'];
		}
		if($order == 'Bezeichnung'){
			// Sortieren der Layer unter Berücksichtigung von Umlauten
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
			$layer['ID'] = $sorted_arrays['second_array'];
			$sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['GruppeID']);
			$layer['Bezeichnung'] = $sorted_arrays['array'];
			$layer['GruppeID'] = $sorted_arrays['second_array'];
		}
		return $layer;
	}
	
	function get_all_layer_params() {
		$layer_params = array();
		$sql = "SELECT * FROM layer_parameter";
		$params_result = mysql_query($sql);
		if($params_result==0) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else{
			while($rs = mysql_fetch_assoc($params_result)){
				$params[] = $rs;
			}
		}
		return $params;
	}
	
	function save_all_layer_params($formvars){
		$sql = "TRUNCATE layer_parameter";
		$result = mysql_query($sql);
		$sql = "INSERT INTO layer_parameter VALUES ";
		for($i = 0; $i < count($formvars['key']); $i++){
			if($formvars['key'][$i] != ''){
				if($formvars['id'][$i] == '')$formvars['id'][$i] = 'NULL';
				if($komma)$sql .= ",";
				$sql .= "(".$formvars['id'][$i].", '".$formvars['key'][$i]."', '".$formvars['alias'][$i]."', '".$formvars['default_value'][$i]."', '".mysql_real_escape_string($formvars['options_sql'][$i])."')";
				$komma = true;
			}
		}
		$result = mysql_query($sql);
		if($result==0)echo '<br>Fehler beim Speichern der Layerparameter mit SQL: ' . $sql;
	}
	
	function get_all_layer_params_default_values() {
		$layer_params = array();
		$sql = "SELECT GROUP_CONCAT(concat('\"', `key`, '\":\"', default_value, '\"')) as params FROM layer_parameter p";
		$params_result = mysql_query($sql);
		if($params_result==0) {
			echo '<br>Fehler bei der Abfrage der Layerparameter mit SQL: ' . $sql;
		}
		else{
			$rs = mysql_fetch_assoc($params_result);
		}
		return (array)json_decode('{' . $rs['params'] . '}');
	}

  function get_stellen_from_layer($layer_id){
    $sql = 'SELECT ID, Bezeichnung FROM stelle, used_layer WHERE used_layer.Stelle_ID = stelle.ID AND used_layer.Layer_ID = '.$layer_id.' ORDER BY Bezeichnung';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_stellen_from_layer - Lesen der Stellen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $stellen['ID'][]=$rs['ID'];
      $stellen['Bezeichnung'][]=$rs['Bezeichnung'];
    }
    return $stellen;
  }

  function get_postgis_layers($order) {
    $sql ='SELECT * FROM layer, u_groups';
    $sql.=' WHERE layer.Gruppe = u_groups.id AND connectiontype = 6';
    if($order != ''){$sql .= ' ORDER BY '.$order;}
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getall_Layer - Lesen aller Layer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while($rs=mysql_fetch_array($query)) {
          $layer['ID'][]=$rs['Layer_ID'];
          $layer['Bezeichnung'][]=$rs['Name'];
      }
    if($order == 'Bezeichnung'){
      // Sortieren der Layer unter Berücksichtigung von Umlauten
      $sorted_arrays = umlaute_sortieren($layer['Bezeichnung'], $layer['ID']);
      $layer['Bezeichnung'] = $sorted_arrays['array'];
      $layer['ID'] = $sorted_arrays['second_array'];
    }
    return $layer;
  }

  function get_Layer($id, $replace_class_item = false) {
    $sql ='SELECT * FROM layer WHERE Layer_ID = '.$id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layer - Lesen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = mysql_fetch_array($query);
		if ($replace_class_item) {
			$layer['classitem'] = replace_params($layer['classitem'],	rolle::$layer_params);
			$layer['classification'] = replace_params($layer['classification'],	rolle::$layer_params);
		}
    return $layer;
  }

	function set_default_layer_privileges($formvars, $attributes){
		for($i = 0; $i < count($attributes['type']); $i++){
			if($formvars['privileg_'.$attributes['name'][$i]] == '')$formvars['privileg_'.$attributes['name'][$i]] = 'NULL';
			$sql = 'UPDATE layer_attributes SET ';
			$sql.= 'privileg = '.$formvars['privileg_'.$attributes['name'][$i]];
			if($formvars['tooltip_'.$attributes['name'][$i]] == 'on'){
				$sql.= ', query_tooltip = 1';
			}
			else{
				$sql.= ', query_tooltip = 0';
			}
			$sql.= ' WHERE layer_id = '.$formvars['selected_layer_id'].' AND name = "'.$attributes['name'][$i].'"';
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern des Layerrechte zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
			$sql = 'UPDATE layer SET privileg = "'.$formvars['privileg'].'", export_privileg = "'.$formvars['export_privileg'].'" WHERE ';
			$sql.= 'Layer_ID = '.$formvars['selected_layer_id'];
			$this->debug->write("<p>file:users.php class:stelle->set_default_layer_privileges - Speichern der Layerrechte zur Stelle:<br>".$sql,4);
			$query=mysql_query($sql);
			if ($query==0) { $this->debug->write("<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__,4); return 0; }
		}
	}

	function get_layersfromgroup($group_id ){
    $sql ='SELECT * FROM layer';
		if($group_id != '')$sql.=' WHERE Gruppe = '.$group_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Layerfromgroup - Lesen der Layer einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		while($rs=mysql_fetch_array($query)) {
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
	  $layer = $this->get_Layer($id);
		if ($layer) {
		  return true;
		}
		else {
		  return false;
		}
	}

	function get_table_information($dbname, $tablename) {
		$sql = "SELECT * FROM information_schema.tables WHERE table_schema = '".$dbname."' AND table_name = '".$tablename."'";
		$this->debug->write("<p>file:kvwmap class:db_mapObj->get_table_information - Lesen der Metadaten der Tabelle ".$tablename." in db ".$dbname.":<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $metadata = mysql_fetch_array($query);
    return $metadata;
	}

  function get_used_Layer($id) {
    $sql ='SELECT * FROM used_layer WHERE Layer_ID = '.$id.' AND Stelle_ID = '.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_used_Layer - Lesen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $layer = mysql_fetch_array($query);
    return $layer;
  }

  function newGroup($groupname, $order){
    $sql = 'INSERT INTO u_groups (Gruppenname, `order`) VALUES ("'.$groupname.'", '.$order.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->newGroup - Erstellen einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    return mysql_insert_id();
  }

  function get_Groups($layergruppen = NULL) {
		$this->groupset = $this->read_Groups(true, 'Gruppenname');
		if($layergruppen == NULL){	# alle abfragen
			$layergruppen['ID'] = array_unique(array_keys($this->groupset));
		}
		foreach($layergruppen['ID'] as $groupid){
			$uppergroupnames = $this->list_uppergroups($groupid);
			$layergruppen['Bezeichnung'][] = implode('->', array_reverse($uppergroupnames));;
		}
		// Sortieren der Gruppen unter Berücksichtigung von Umlauten
    $sorted_arrays = umlaute_sortieren($layergruppen['Bezeichnung'], $layergruppen['ID']);
    $layergruppen['Bezeichnung'] = $sorted_arrays['array'];
    $layergruppen['ID'] = $sorted_arrays['second_array'];
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
    $sql ="SELECT * FROM u_groups WHERE Gruppenname = '".$groupname."'";
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getGroupbyName - Lesen einer Gruppe:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }

  function getClassFromObject($select, $layer_id){
    # diese Funktion bestimmt für ein über die oid gegebenes Objekt welche Klasse dieses Objekt hat
    $classes = $this->read_Classes($layer_id);
    $anzahl = count($classes);
    if($anzahl == 1){
      return $classes[0]['Class_ID'];
    }
    else{
      for($i = 0; $i < $anzahl; $i++){
        $exp = str_replace(array("'[", "]'", '[', ']', ')', '('), '', $classes[$i]['Expression']);
        $exp = str_replace(' eq ', '=', $exp);
        $exp = str_replace(' ne ', '!=', $exp);

				# wenn im Data sowas wie "tabelle.attribut" vorkommt, soll das anstatt dem "attribut" aus der Expression verwendet werden
        //$attributes = explode(',', substr($select, 0, strpos(strtolower($select), ' from ')));
        $attributes = get_select_parts(substr($select, 0, strpos(strtolower($select), ' from ')));
        $exp_parts = explode(' ', $exp);
        for($k = 0; $k < count($exp_parts); $k++){
	      	for($j = 0; $j < count($attributes); $j++){
	      		if($exp_parts[$k] != '' AND strpos(strtolower($attributes[$j]), '.'.$exp_parts[$k]) !== false){
	      			$exp_parts[$k] = str_replace('select ', '', strtolower($attributes[$j]));
	      		}
	      	}
	      }
	      $exp = implode(' ', $exp_parts);
				$sql = $select." AND (".$exp.")";
        $this->debug->write("<p>file:kvwmap class:db_mapObj->getClassFromObject - Lesen einer Klasse eines Objektes:<br>".$sql,4);
        $query=pg_query($sql);
        if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
        $count=pg_num_rows($query);
        if($count == 1){
          return $classes[$i]['Class_ID'];
        }
      }
    }
  }

	function copyStyle($style_id){
		$sql = "INSERT INTO styles (symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,sizeitem) SELECT symbol,symbolname,size,color,backgroundcolor,outlinecolor,minsize,maxsize,angle,angleitem,antialias,width,minwidth,maxwidth,sizeitem FROM styles WHERE Style_ID = ".$style_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyStyle - Kopieren eines Styles:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		return mysql_insert_id();
	}

	function copyLabel($label_id){
		$sql = "INSERT INTO labels (font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force) SELECT font,type,color,outlinecolor,shadowcolor,shadowsizex,shadowsizey,backgroundcolor,backgroundshadowcolor,backgroundshadowsizex,backgroundshadowsizey,size,minsize,maxsize,position,offsetx,offsety,angle,autoangle,buffer,antialias,minfeaturesize,maxfeaturesize,partials,wrap,the_force FROM labels WHERE Label_ID = ".$label_id;
		$this->debug->write("<p>file:kvwmap class:db_mapObj->copyLabel - Kopieren eines Labels:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
		return mysql_insert_id();
	}

  function copyClass($class_id, $layer_id){
    # diese Funktion kopiert eine Klasse mit Styles und Labels und gibt die ID der neuen Klasse zurück
    $class = $this->read_ClassesbyClassid($class_id);
    $sql = "INSERT INTO classes (Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, Layer_ID,Expression,classification,drawingorder,text) SELECT Name, `Name_low-german`, Name_english, Name_polish, Name_vietnamese, ".$layer_id.",Expression,classification,drawingorder,text FROM classes WHERE Class_ID = ".$class_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->copyClass - Kopieren einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $new_class_id = mysql_insert_id();
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
    if(is_array($classdata)){
      $attrib = $classdata;         # Attributarray wurde übergeben
      # attrib:(Name, Layer_ID, Expression, drawingorder)
      $sql = 'INSERT INTO classes (Name, ';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '`Name_'.$language.'`, ';
				}
			}
			$sql.= 'Layer_ID, Expression, classification, drawingorder) VALUES ("'.$attrib['name'].'",';
			foreach($supportedLanguages as $language){
				if($language != 'german'){
					$sql.= '"'.$attrib['name_'.$language].'",';
				}
			}
			$sql.= $attrib['layer_id'].', "'.$attrib['expression'].'", "' . $attrib['classification'] . '", "'.$attrib['order'].'")';
    }
    else{
      $class = $classdata;        # Classobjekt wurde übergeben
      if(MAPSERVERVERSION > 500){
        $expression = $class->getExpressionString();
      }
      else{
        $expression = $class->getExpression();
      }
      $sql = 'INSERT INTO classes (Name, Layer_ID, Expression, classification, drawingorder) VALUES ';
      $sql.= '("'.$class->name.'", '.$class->layer_id.', "'.$expression.'", "' . $class->classification . '", "'.$class->drawingorder.'")';
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Class - Erstellen einer Klasse zu einem Layer:<br>".$sql,4);
    $query=mysql_query($sql);
		if($this->database->logfile != NULL)$this->database->logfile->write($sql.';');
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }

    return mysql_insert_id();
  }

  function delete_Class($class_id){
    $sql = 'DELETE FROM classes WHERE Class_ID = '.$class_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Class - Löschen einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }

    # Einträge in u_styles2classes und evtl. die Styles mitlöschen
    $styles = $this->read_Styles($class_id);
    for($i = 0; $i < count($styles); $i++){
    	$this->removeStyle2Class($class_id, $styles[$i]['style_id']);
      $other_classes = $this->get_classes2style($styles[$i]['style_id']);
      if($other_classes == NULL){
      	$this->delete_Style($styles[$i]['style_id']);
      }
    }
    # Einträge in u_labels2classes und evtl. die Labels mitlöschen
    $labels = $this->read_Label($class_id);
    for($i = 0; $i < count($labels); $i++){
    	$this->removeLabel2Class($class_id, $labels[$i]['label_id']);
    	$other_classes = $this->get_classes2label($labels[$i]['label_id']);
    	if($other_classes == NULL){
      	$this->delete_Label($labels[$i]['label_id']);
    	}
    }
  }

	function update_Class($attrib) {
		global $supportedLanguages;

		$names = implode(
			', ',
			array_map(
				function($language) use ($attrib) {
					if($language != 'german')return "`Name_" . $language . "` = '" . $attrib['Name_' . $language] . "'";
					else return "`Name` = '".$attrib['name']."'";
				},
				$supportedLanguages
			)
		);

		$sql = "
			UPDATE
				classes
			SET
				".$names.",
				`Layer_ID` = " . $attrib['layer_id'] . ",
				`Expression` = '" . $attrib['expression'] . "',
				`text` = '" . $attrib['text'] . "',
				`classification` = '" . $attrib['classification'] . "',
				`legendgraphic`= '" . $attrib['legendgraphic'] . "',
				`drawingorder` = " . $attrib['order'] . ",
				`legendorder` = ". $attrib['legendorder'] . "
			WHERE
				`Class_ID` = " . $attrib['class_id'] . "
		";

		#echo $sql.'<br>';
		$this->debug->write("<p>file:kvwmap class:db_mapObj->update_Class - Aktualisieren einer Klasse:<br>".$sql,4);
		$query=mysql_query($sql);
		if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
	}

  function new_Style($style){
    if(is_array($style)){
      $sql = "INSERT INTO styles SET ";
      if($style['color']){$sql.= "color = '".$style['color']."'";}
			if($style['colorred'] != ''){$sql.= "color = '".$style['colorred']." ".$style['colorgreen']." ".$style['colorblue']."'";}
      if($style['symbol']){$sql.= ", symbol = '".$style['symbol']."'";}
      if($style['symbolname']){$sql.= ", symbolname = '".$style['symbolname']."'";}
      if($style['size']){$sql.= ", size = '".$style['size']."'";}
      if($style['backgroundcolor'] !== NULL){$sql.= ", backgroundcolor = '".$style['backgroundcolor']."'";}
      if($style['backgroundcolorred'] !== NULL){$sql.= ", backgroundcolor = '".$style['backgroundcolorred']." ".$style['backgroundcolorgreen']." ".$style['backgroundcolorblue']."'";}
      if($style['outlinecolor'] !== NULL){$sql.= ", outlinecolor = '".$style['outlinecolor']."'";}
      if($style['outlinecolorred'] !== NULL){$sql.= ", outlinecolor = '".$style['outlinecolorred']." ".$style['outlinecolorgreen']." ".$style['outlinecolorblue']."'";}
			if($style['colorrange'] !== NULL){$sql.= ", colorrange = '".$style['colorrange']."'";}
			if($style['datarange'] !== NULL){$sql.= ", datarange = '".$style['datarange']."'";}
			if($style['opacity'] !== NULL){$sql.= ", opacity = ".$style['opacity'];}
      if($style['minsize']){$sql.= ", minsize = '".$style['minsize']."'";}
      if($style['maxsize']){$sql.= ", maxsize = '".$style['maxsize']."'";}
      if($style['angle']){$sql.= ", angle = '".$style['angle']."'";}
			if($style['angleitem']){$sql.= ", angleitem = '".$style['angleitem']."'";}
			if($style['antialias']){$sql.= ", antialias = ".$style['antialias'];}
      if($style['width']){$sql.= ", width = '".$style['width']."'";}
      if($style['minwidth']){$sql.= ", minwidth = '".$style['minwidth']."'";}
      if($style['maxwidth']){$sql.= ", maxwidth = '".$style['maxwidth']."'";}
			if($style['sizeitem']){$sql.= ", sizeitem = '".$style['sizeitem']."'";}
			if($style['offsetx']){$sql.= ", offsetx = ".$style['offsetx'];}
			if($style['offsety']){$sql.= ", offsety = ".$style['offsety'];}
			if($style['pattern']){$sql.= ", pattern = '".$style['pattern']."'";}
			if($style['geomtransform']){$sql.= ", geomtransform = '".$style['geomtransform']."'";}
			if($style['gap']){$sql.= ", gap = ".$style['gap'];}
			if($style['initialgap']){$sql.= ", initialgap = ".$style['initialgap'];}
			if($style['linecap']){$sql.= ", linecap = '".$style['linecap']."'";}
			if($style['linejoin']){$sql.= ", linejoin = '".$style['linejoin']."'";}
			if($style['linejoinmaxsize']){$sql.= ", linejoinmaxsize = ".$style['linejoinmaxsize'];}
    }
    else{
    # Styleobjekt wird übergeben
      $sql = "INSERT INTO styles SET ";
      $sql.= "symbol = '".$style->symbol."', ";
      $sql.= "symbolname = '".$style->symbolname."', ";
      $sql.= "size = '".$style->size."', ";
      $sql.= "color = '".$style->color->red." ".$style->color->green." ".$style->color->blue."', ";
      $sql.= "backgroundcolor = '".$style->backgroundcolor->red." ".$style->backgroundcolor->green." ".$style->backgroundcolor->blue."', ";
      $sql.= "outlinecolor = '".$style->outlinecolor->red." ".$style->outlinecolor->green." ".$style->outlinecolor->blue."', ";
      $sql.= "minsize = '".$style->minsize."', ";
      $sql.= "maxsize = '".$style->maxsize."'";
    }
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
		if($this->database->logfile != NULL)$this->database->logfile->write($sql.';');
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2style($style_id){
		$sql = 'SELECT class_id FROM u_styles2classes WHERE Style_ID = '.$style_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2style - Abfragen der Klassen, die einen Style benutzen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function delete_Style($style_id){
    $sql = 'DELETE FROM styles WHERE Style_ID = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Style - Löschen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function moveup_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index+1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index+1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function movedown_Style($style_id, $class_id){
    $sql = 'SELECT * FROM u_styles2classes WHERE class_id = '.$class_id.' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $i = 0;
    while($rs=mysql_fetch_array($query)) {
      $styles[$i]=$rs;
      if($rs['style_id'] == $style_id){
        $index = $i;
      }
      $i++;
    }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index-1]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $sql = 'UPDATE u_styles2classes SET drawingorder = '.$styles[$index-1]['drawingorder'].' WHERE class_id = '.$class_id.' AND style_id = '.$styles[$index]['style_id'];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->moveup_Style :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function delete_Label($label_id){
    $sql = 'DELETE FROM labels WHERE Label_ID = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->delete_Label - Löschen eines Labels:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function addStyle2Class($class_id, $style_id, $drawingorder){
    if($drawingorder == NULL){
      $sql = 'SELECT MAX(drawingorder) FROM u_styles2classes WHERE class_id = '.$class_id;
      $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class :<br>".$sql,4);
      $query=mysql_query($sql);
      if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
      $rs = mysql_fetch_array($query);
      $drawingorder = $rs[0]+1;
    }
    $sql = 'INSERT INTO u_styles2classes VALUES ('.$class_id.', '.$style_id.', "'.$drawingorder.'")';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addStyle2Class - Hinzufügen eines Styles zu einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
		if($this->database->logfile != NULL)$this->database->logfile->write($sql.';');
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeStyle2Class($class_id, $style_id){
    $sql = 'DELETE FROM u_styles2classes WHERE class_id = '.$class_id.' AND style_id = '.$style_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeStyle2Class - Löschen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function save_Style($formvars){
  	# wenn der Style nicht der Klasse zugeordnet ist, zuordnen
  	$classes = $this->get_classes2style($formvars["style_id"]);
  	if(!in_array($formvars["class_id"], $classes))$this->addStyle2Class($formvars["class_id"], $formvars["style_id"], NULL);
    $sql ="UPDATE styles SET ";
    if($formvars["symbol"]){$sql.="symbol = '".$formvars["symbol"]."',";}else{$sql.="symbol = NULL,";}
    $sql.="symbolname = '".$formvars["symbolname"]."',";
    if($formvars["size"] != ''){$sql.="size = '".$formvars["size"]."',";}else{$sql.="size = NULL,";}
    if($formvars["color"] != ''){$sql.="color = '".$formvars["color"]."',";}else{$sql.="color = NULL,";}
    if($formvars["backgroundcolor"] != ''){$sql.="backgroundcolor = '".$formvars["backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["outlinecolor"] != ''){$sql.="outlinecolor = '".$formvars["outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
		if($formvars["colorrange"] != ''){$sql.="colorrange = '".$formvars["colorrange"]."',";}else{$sql.="colorrange = NULL,";}
		if($formvars["datarange"] != ''){$sql.="datarange = '".$formvars["datarange"]."',";}else{$sql.="datarange = NULL,";}
		if($formvars["rangeitem"] != ''){$sql.="rangeitem = '".$formvars["rangeitem"]."',";}else{$sql.="rangeitem = NULL,";}
    if($formvars["minsize"] != ''){$sql.="minsize = '".$formvars["minsize"]."',";}else{$sql.="minsize = NULL,";}
    if($formvars["maxsize"] != ''){$sql.="maxsize = '".$formvars["maxsize"]."',";}else{$sql.="maxsize = NULL,";}
		if($formvars["minscale"] != ''){$sql.="minscale = '".$formvars["minscale"]."',";}else{$sql.="minscale = NULL,";}
    if($formvars["maxscale"] != ''){$sql.="maxscale = '".$formvars["maxscale"]."',";}else{$sql.="maxscale = NULL,";}
    if($formvars["angle"] != ''){$sql.="angle = '".$formvars["angle"]."',";}else{$sql.="angle = NULL,";}
    $sql.="angleitem = '".$formvars["angleitem"]."',";
    if($formvars["antialias"] != ''){$sql.="antialias = '".$formvars["antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["width"] != ''){$sql.="width = '".$formvars["width"]."',";}else{$sql.="width = NULL,";}
    if($formvars["minwidth"] != ''){$sql.="minwidth = '".$formvars["minwidth"]."',";}else{$sql.="minwidth = NULL,";}
    if($formvars["maxwidth"] != ''){$sql.="maxwidth = '".$formvars["maxwidth"]."',";}else{$sql.="maxwidth = NULL,";}
    $sql.="sizeitem = '".$formvars["sizeitem"]."',";
    if($formvars["offsetx"] != ''){$sql.="offsetx = '".$formvars["offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["offsety"] != ''){$sql.="offsety = '".$formvars["offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["pattern"] != ''){$sql.="pattern = '".$formvars["pattern"]."',";}else{$sql.="pattern = NULL,";}
  	if($formvars["geomtransform"] != ''){$sql.="geomtransform = '".$formvars["geomtransform"]."',";}else{$sql.="geomtransform = NULL,";}
		if($formvars["gap"] != ''){$sql.="gap = ".$formvars["gap"].",";}else{$sql.="gap = NULL,";}
		if($formvars["initialgap"] != ''){$sql.="initialgap = ".$formvars["initialgap"].",";}else{$sql.="initialgap = NULL,";}
		if($formvars["opacity"] != ''){$sql.="opacity = ".$formvars["opacity"].",";}else{$sql.="opacity = NULL,";}
		if($formvars["linecap"] != ''){$sql.="linecap = '".$formvars["linecap"]."',";}else{$sql.="linecap = NULL,";}
		if($formvars["linejoin"] != ''){$sql.="linejoin = '".$formvars["linejoin"]."',";}else{$sql.="linejoin = NULL,";}
		if($formvars["linejoinmaxsize"] != ''){$sql.="linejoinmaxsize = ".$formvars["linejoinmaxsize"].",";}else{$sql.="linejoinmaxsize = NULL,";}
    $sql.="Style_ID = ".$formvars["new_style_id"];
    $sql.=" WHERE Style_ID = ".$formvars["style_id"];
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Style - Speichern der Styledaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Style($style_id){
  	if($style_id){
	    $sql ='SELECT * FROM styles AS s';
	    $sql.=' WHERE s.Style_ID = '.$style_id;
	    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Style - Lesen der Styledaten:<br>".$sql,4);
	    $query=mysql_query($sql);
	    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
	    $rs=mysql_fetch_assoc($query);
	    return $rs;
  	}
  }

  function save_Label($formvars){
    $sql ="UPDATE labels SET ";
    if($formvars["font"]){$sql.="font = '".$formvars["font"]."',";}
    if($formvars["type"]){$sql.="type = '".$formvars["type"]."',";}
    if($formvars["color"]){$sql.="color = '".$formvars["color"]."',";}
    if($formvars["outlinecolor"] != ''){$sql.="outlinecolor = '".$formvars["outlinecolor"]."',";}else{$sql.="outlinecolor = NULL,";}
    if($formvars["shadowcolor"] != ''){$sql.="shadowcolor = '".$formvars["shadowcolor"]."',";}else{$sql.="shadowcolor = NULL,";}
    if($formvars["shadowsizex"] != ''){$sql.="shadowsizex = '".$formvars["shadowsizex"]."',";}else{$sql.="shadowsizex = NULL,";}
    if($formvars["shadowsizey"] != ''){$sql.="shadowsizey = '".$formvars["shadowsizey"]."',";}else{$sql.="shadowsizey = NULL,";}
    if($formvars["backgroundcolor"] != ''){$sql.="backgroundcolor = '".$formvars["backgroundcolor"]."',";}else{$sql.="backgroundcolor = NULL,";}
    if($formvars["backgroundshadowcolor"] != ''){$sql.="backgroundshadowcolor = '".$formvars["backgroundshadowcolor"]."',";}else{$sql.="backgroundshadowcolor = NULL,";}
    if($formvars["backgroundshadowsizex"] != ''){$sql.="backgroundshadowsizex = '".$formvars["backgroundshadowsizex"]."',";}else{$sql.="backgroundshadowsizex = NULL,";}
    if($formvars["backgroundshadowsizey"] != ''){$sql.="backgroundshadowsizey = '".$formvars["backgroundshadowsizey"]."',";}else{$sql.="backgroundshadowsizey = NULL,";}
    if($formvars["size"]){$sql.="size = '".$formvars["size"]."',";}
    if($formvars["minsize"]){$sql.="minsize = '".$formvars["minsize"]."',";}
    if($formvars["maxsize"]){$sql.="maxsize = '".$formvars["maxsize"]."',";}
    if($formvars["position"]){$sql.="position = '".$formvars["position"]."',";}
    if($formvars["offsetx"] != ''){$sql.="offsetx = '".$formvars["offsetx"]."',";}else{$sql.="offsetx = NULL,";}
    if($formvars["offsety"] != ''){$sql.="offsety = '".$formvars["offsety"]."',";}else{$sql.="offsety = NULL,";}
    if($formvars["angle"] != ''){$sql.="angle = '".$formvars["angle"]."',";}else{$sql.="angle = NULL,";}
    if($formvars["autoangle"]){$sql.="autoangle = '".$formvars["autoangle"]."',";}
		else $sql.="autoangle = NULL,";
    if($formvars["buffer"]){$sql.="buffer = '".$formvars["buffer"]."',";}
    if($formvars["antialias"] != ''){$sql.="antialias = '".$formvars["antialias"]."',";}else{$sql.="antialias = NULL,";}
    if($formvars["minfeaturesize"]){$sql.="minfeaturesize = '".$formvars["minfeaturesize"]."',";}
    if($formvars["maxfeaturesize"]){$sql.="maxfeaturesize = '".$formvars["maxfeaturesize"]."',";}
    if($formvars["partials"] != ''){$sql.="partials = '".$formvars["partials"]."',";}
    if($formvars["wrap"] != ''){$sql.="wrap = '".$formvars["wrap"]."',";}
    if($formvars["the_force"] != ''){$sql.="the_force = '".$formvars["the_force"]."',";}
    $sql.="Label_ID = ".$formvars["new_label_id"];
    $sql.=" WHERE Label_ID = ".$formvars["label_id"];
    $this->debug->write("<p>file:kvwmap class:db_mapObj->save_Label - Speichern der Labeldaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function get_Label($label_id) {
    $sql ='SELECT * FROM labels AS l';
    $sql.=' WHERE l.Label_ID = '.$label_id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_Label - Lesen der Labeldaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_assoc($query);
    return $rs;
  }

  function new_Label($label){
  	if(is_array($label)){
  	$sql = "INSERT INTO labels SET ";
	    if($label[type]){$sql.= "type = '".$label[type]."', ";}
	    if($label[font]){$sql.= "font = '".$label[font]."', ";}
	    if($label[size]){$sql.= "size = '".$label[size]."', ";}
	    if($label[color]){$sql.= "color = '".$label[color]."', ";}
	    if($label[shadowcolor]){$sql.= "shadowcolor = '".$label[shadowcolor]."', ";}
	    if($label[shadowsizex]){$sql.= "shadowsizex = '".$label[shadowsizex]."', ";}
	    if($label[shadowsizey]){$sql.= "shadowsizey = '".$label[shadowsizey]."', ";}
	    if($label[backgroundcolor]){$sql.= "backgroundcolor = '".$label[backgroundcolor]."', ";}
	    if($label[backgroundshadowcolor]){$sql.= "backgroundshadowcolor = '".$label[backgroundshadowcolor]."', ";}
	    if($label[backgroundshadowsizex]){$sql.= "backgroundshadowsizex = '".$label[backgroundshadowsizex]."', ";}
	    if($label[backgroundshadowsizey]){$sql.= "backgroundshadowsizey = '".$label[backgroundshadowsizey]."', ";}
	    if($label[outlinecolor]){$sql.= "outlinecolor = '".$label[outlinecolor]."', ";}
	    if($label[position]){$sql.= "position = '".$label[position]."', ";}
	    if($label[offsetx]){$sql.= "offsetx = '".$label[offsetx]."', ";}
	    if($label[offsety]){$sql.= "offsety = '".$label[offsety]."', ";}
	    if($label[angle]){$sql.= "angle = '".$label[angle]."', ";}
	    if($label[autoangle]){$sql.= "autoangle = '".$label[autoangle]."', ";}
	    if($label[buffer]){$sql.= "buffer = '".$label[buffer]."', ";}
	    if($label[antialias]){$sql.= "antialias = '".$label[antialias]."', ";}
	    if($label[minfeaturesize]){$sql.= "minfeaturesize = '".$label[minfeaturesize]."', ";}
	    if($label[maxfeaturesize]){$sql.= "maxfeaturesize = '".$label[maxfeaturesize]."', ";}
	    if($label[partials]){$sql.= "partials = '".$label[partials]."', ";}
	    if($label[wrap]){$sql.= "wrap = '".$label[wrap]."', ";}
	    if($label[the_force]){$sql.= "the_force = '".$label[the_force]."', ";}
	    if($label[minsize]){$sql.= "minsize = '".$label[minsize]."', ";}
	    if($label[maxsize]){$sql.= "maxsize = '".$label[maxsize]."'";}
  	}
  	else{
	    # labelobjekt wird übergeben
	    $sql = "INSERT INTO labels SET ";
	    if($label->type){$sql.= "type = '".$label->type."', ";}
	    if($label->font){$sql.= "font = '".$label->font."', ";}
	    if($label->size){$sql.= "size = '".$label->size."', ";}
	    if($label->color){$sql.= "color = '".$label->color->red." ".$label->color->green." ".$label->color->blue."', ";}
	    if($label->shadowcolor){$sql.= "shadowcolor = '".$label->shadowcolor->red." ".$label->shadowcolor->green." ".$label->shadowcolor->blue."', ";}
	    if($label->shadowsizex){$sql.= "shadowsizex = '".$label->shadowsizex."', ";}
	    if($label->shadowsizey){$sql.= "shadowsizey = '".$label->shadowsizey."', ";}
	    if($label->backgroundcolor){$sql.= "backgroundcolor = '".$label->backgroundcolor->red." ".$label->backgroundcolor->green." ".$label->backgroundcolor->blue."', ";}
	    if($label->backgroundshadowcolor){$sql.= "backgroundshadowcolor = '".$label->backgroundshadowcolor->red." ".$label->backgroundshadowcolor->green." ".$label->backgroundshadowcolor->blue."', ";}
	    if($label->backgroundshadowsizex){$sql.= "backgroundshadowsizex = '".$label->backgroundshadowsizex."', ";}
	    if($label->backgroundshadowsizey){$sql.= "backgroundshadowsizey = '".$label->backgroundshadowsizey."', ";}
	    if($label->outlinecolor){$sql.= "outlinecolor = '".$label->outlinecolor->red." ".$label->outlinecolor->green." ".$label->outlinecolor->blue."', ";}
	    if($label->position){$sql.= "position = '".$label->position."', ";}
	    if($label->offsetx){$sql.= "offsetx = '".$label->offsetx."', ";}
	    if($label->offsety){$sql.= "offsety = '".$label->offsety."', ";}
	    if($label->angle){$sql.= "angle = '".$label->angle."', ";}
	    if($label->autoangle){$sql.= "autoangle = '".$label->autoangle."', ";}
	    if($label->buffer){$sql.= "buffer = '".$label->buffer."', ";}
	    if($label->antialias){$sql.= "antialias = '".$label->antialias."', ";}
	    if($label->minfeaturesize){$sql.= "minfeaturesize = '".$label->minfeaturesize."', ";}
	    if($label->maxfeaturesize){$sql.= "maxfeaturesize = '".$label->maxfeaturesize."', ";}
	    if($label->partials){$sql.= "partials = '".$label->partials."', ";}
	    if($label->wrap){$sql.= "wrap = '".$label->wrap."', ";}
	    if($label->the_force){$sql.= "the_force = '".$label->the_force."', ";}
	    if($label->minsize){$sql.= "minsize = '".$label->minsize."', ";}
	    if($label->maxsize){$sql.= "maxsize = '".$label->maxsize."'";}
  	}
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->new_Style - Erzeugen eines Styles:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    return mysql_insert_id();
  }

	function get_classes2label($label_id){
		$sql = 'SELECT class_id FROM u_labels2classes WHERE Label_ID = '.$label_id;
		#echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->get_classes2label - Abfragen der Klassen, die ein Label benutzen:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $classes[]=$rs[0];
    }
    return $classes;
	}

  function addLabel2Class($class_id, $label_id){
    $sql = 'INSERT INTO u_labels2classes VALUES ('.$class_id.', '.$label_id.')';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->addLabel2Class - Hinzufügen eines Labels zu einer Klasse:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
  }

  function removeLabel2Class($class_id, $label_id){
    $sql = 'DELETE FROM u_labels2classes WHERE class_id = '.$class_id.' AND label_id = '.$label_id;
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->removeLabels2Class - Löschen eines Labels:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
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
    $rect=ms_newRectObj();
    $sql ='SELECT MIN(minxmax) AS minxmax, MIN(minymax) AS minymax';
    $sql.=', MAX(maxxmax) AS maxxmax, MAX(maxymax) AS maxymax FROM stelle';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->getMaxMapExtent - Lesen der Maximalen Kartenausdehnung:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }
}

###########################################
# Dokumentenklasse                        #
###########################################
# Klasse Document #
################

# functions of class Document

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

  function Document ($database){
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function delete_ausschnitt($stelle_id, $user_id, $id){
    $sql = 'DELETE FROM druckausschnitte WHERE ';
    $sql.= 'stelle_id = '.$stelle_id.' AND ';
    $sql.= 'user_id = '.$user_id;
    if($id != ''){
      $sql.= ' AND id = '.$id;
    }
    $this->debug->write("<p>file:kvwmap class:Document->delete_ausschnitt :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_ausschnitt($stelle_id, $user_id, $name, $center_x, $center_y, $print_scale, $angle, $frame_id){
    $sql = 'INSERT INTO druckausschnitte SET ';
    $sql.= 'stelle_id = '.$stelle_id.', ';
    $sql.= 'user_id = '.$user_id.', ';
    $sql.= 'name = "'.$name.'", ';
    $sql.= 'center_x = '.$center_x.', ';
    $sql.= 'center_y = '.$center_y.', ';
    $sql.= 'print_scale = '.$print_scale.', ';
    $sql.= 'angle = '.$angle.', ';
    $sql.= 'frame_id = '.$frame_id;
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
    $this->debug->write("<p>file:kvwmap class:Document->load_ausschnitte :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $ausschnitte[] = $rs;
    }
    return $ausschnitte;
  }

  function load_frames($stelle_id, $frameid){
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
    $i = 0;
    while($rs=mysql_fetch_array($ret1[1])){
      $frames[] = $rs;
      $frames[0]['bilder'] = $this->load_bilder($rs['id']);
      $frames[0]['texts'] = $this->load_texts($rs['id']);
      $i++;
    }
    return $frames;
  }

  function load_texts($frame_id){
    $sql = 'SELECT druckfreitexte.* FROM druckrahmen, druckfreitexte, druckrahmen2freitexte';
    $sql.= ' WHERE druckrahmen2freitexte.druckrahmen_id = '.$frame_id;
    $sql.= ' AND druckrahmen2freitexte.druckrahmen_id = druckrahmen.id';
    $sql.= ' AND druckrahmen2freitexte.freitext_id = druckfreitexte.id';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:Document->load_texts :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
      $texts[] = $rs;
    }
    return $texts;
  }

  function load_bilder($frame_id){
    $sql = 'SELECT b.src,r2b.posx,r2b.posy,r2b.width,r2b.height,r2b.angle';
    $sql.= ' FROM druckrahmen AS r, druckfreibilder AS b, druckrahmen2freibilder AS r2b';
    $sql.= ' WHERE r.id = r2b.druckrahmen_id';
    $sql.= ' AND b.id = r2b.freibild_id';
    $sql.= ' AND r.id = '.$frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->load_bilder :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=mysql_fetch_array($query)){
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
    $sql .= ' font = "",';
    $sql .= ' angle = 0';
    $this->debug->write("<p>file:kvwmap class:Document->addfreetext :",4);
    $this->database->execSQL($sql,4, 1);
    $lastinsert_id = mysql_insert_id();
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
    $this->debug->write("<p>file:kvwmap class:Document->get_price :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);

    return $rs[0];
  }

  function delete_frame($selected_frame_id){
    $sql ="DELETE FROM druckrahmen WHERE id = ".$selected_frame_id;
    $this->debug->write("<p>file:kvwmap class:Document->delete_frame :",4);
    $this->database->execSQL($sql,4, 1);
    $sql ="DELETE FROM druckrahmen2stelle WHERE druckrahmen_id = ".$selected_frame_id;
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
      $sql .= " SET `Name` = '".$formvars['Name']."'";
			$sql .= ", `dhk_call` = '".$formvars['dhk_call']."'";
      $sql .= ", `headposx` = ".$formvars['headposx'];
      $sql .= ", `headposy` = ".$formvars['headposy'];
      $sql .= ", `headwidth` = ".$formvars['headwidth'];
      $sql .= ", `headheight` = ".$formvars['headheight'];
      $sql .= ", `mapposx` = ".$formvars['mapposx'];
      $sql .= ", `mapposy` = ".$formvars['mapposy'];
      $sql .= ", `mapwidth` = ".$formvars['mapwidth'];
      $sql .= ", `mapheight` = ".$formvars['mapheight'];
      if($formvars['refmapposx']){$sql .= ", `refmapposx` = ".$formvars['refmapposx'];}
      if($formvars['refmapposy']){$sql .= ", `refmapposy` = ".$formvars['refmapposy'];}
      if($formvars['refmapwidth']){$sql .= ", `refmapwidth` = ".$formvars['refmapwidth'];}
      if($formvars['refmapheight']){$sql .= ", `refmapheight` = ".$formvars['refmapheight'];}
      if($formvars['refposx']){$sql .= ", `refposx` = ".$formvars['refposx'];}
      if($formvars['refposy']){$sql .= ", `refposy` = ".$formvars['refposy'];}
      if($formvars['refwidth']){$sql .= ", `refwidth` = ".$formvars['refwidth'];}
      if($formvars['refheight']){$sql .= ", `refheight` = ".$formvars['refheight'];}
      if($formvars['refzoom']){$sql .= ", `refzoom` = ".$formvars['refzoom'];}
      if($formvars['dateposx']){$sql .= ", `dateposx` = ".$formvars['dateposx'];}
      if($formvars['dateposy']){$sql .= ", `dateposy` = ".$formvars['dateposy'];}
      if($formvars['datesize']){$sql .= ", `datesize` = ".$formvars['datesize'];}
      if($formvars['scaleposx']){$sql .= ", `scaleposx` = ".$formvars['scaleposx'];}
      if($formvars['scaleposy']){$sql .= ", `scaleposy` = ".$formvars['scaleposy'];}
      if($formvars['scalesize']){$sql .= ", `scalesize` = ".$formvars['scalesize'];}
			if($formvars['scalebarposx']){$sql .= ", `scalebarposx` = ".$formvars['scalebarposx'];}
      if($formvars['scalebarposy']){$sql .= ", `scalebarposy` = ".$formvars['scalebarposy'];}
      if($formvars['oscaleposx']){$sql .= ", `oscaleposx` = ".$formvars['oscaleposx'];}
      if($formvars['oscaleposy']){$sql .= ", `oscaleposy` = ".$formvars['oscaleposy'];}
      if($formvars['oscalesize']){$sql .= ", `oscalesize` = ".$formvars['oscalesize'];}
			if($formvars['lageposx']){$sql .= ", `lageposx` = ".$formvars['lageposx'];}
      if($formvars['lageposy']){$sql .= ", `lageposy` = ".$formvars['lageposy'];}
      if($formvars['lagesize']){$sql .= ", `lagesize` = ".$formvars['lagesize'];}
			if($formvars['gemeindeposx']){$sql .= ", `gemeindeposx` = ".$formvars['gemeindeposx'];}
      if($formvars['gemeindeposy']){$sql .= ", `gemeindeposy` = ".$formvars['gemeindeposy'];}
      if($formvars['gemeindesize']){$sql .= ", `gemeindesize` = ".$formvars['gemeindesize'];}
      if($formvars['gemarkungposx']){$sql .= ", `gemarkungposx` = ".$formvars['gemarkungposx'];}
      if($formvars['gemarkungposy']){$sql .= ", `gemarkungposy` = ".$formvars['gemarkungposy'];}
      if($formvars['gemarkungsize']){$sql .= ", `gemarkungsize` = ".$formvars['gemarkungsize'];}
      if($formvars['flurposx']){$sql .= ", `flurposx` = ".$formvars['flurposx'];}
      if($formvars['flurposy']){$sql .= ", `flurposy` = ".$formvars['flurposy'];}
      if($formvars['flursize']){$sql .= ", `flursize` = ".$formvars['flursize'];}
			if($formvars['flurstposx']){$sql .= ", `flurstposx` = ".$formvars['flurstposx'];}
      if($formvars['flurstposy']){$sql .= ", `flurstposy` = ".$formvars['flurstposy'];}
      if($formvars['flurstsize']){$sql .= ", `flurstsize` = ".$formvars['flurstsize'];}
      if($formvars['legendposx']){$sql .= ", `legendposx` = ".$formvars['legendposx'];}
      if($formvars['legendposy']){$sql .= ", `legendposy` = ".$formvars['legendposy'];}
      if($formvars['legendsize']){$sql .= ", `legendsize` = ".$formvars['legendsize'];}
      if($formvars['arrowposx']){$sql .= ", `arrowposx` = ".$formvars['arrowposx'];}
      if($formvars['arrowposy']){$sql .= ", `arrowposy` = ".$formvars['arrowposy'];}
      if($formvars['arrowlength']){$sql .= ", `arrowlength` = ".$formvars['arrowlength'];}
      if($formvars['userposx']){$sql .= ", `userposx` = '".$formvars['userposx']."'";}
      if($formvars['userposy']){$sql .= ", `userposy` = '".$formvars['userposy']."'";}
      if($formvars['usersize']){$sql .= ", `usersize` = '".$formvars['usersize']."'";}
      if($formvars['watermark']){$sql .= ", `watermark` = '".$formvars['watermark']."'";}
      if($formvars['watermarkposx']){$sql .= ", `watermarkposx` = ".$formvars['watermarkposx'];}
      if($formvars['watermarkposy']){$sql .= ", `watermarkposy` = ".$formvars['watermarkposy'];}
      if($formvars['watermarksize']){$sql .= ", `watermarksize` = ".$formvars['watermarksize'];}
      if($formvars['watermarkangle']){$sql .= ", `watermarkangle` = ".$formvars['watermarkangle'];}
      if($formvars['watermarktransparency']){$sql .= ", `watermarktransparency` = '".$formvars['watermarktransparency']."'";}
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = ".$formvars['variable_freetexts'];
      if($formvars['format']){$sql .= ", `format` = '".$formvars['format']."'";}
      if($preis){$sql .= ", `preis` = '".$preis."'";}
      if($formvars['font_date']){$sql .= ", `font_date` = '".$formvars['font_date']."'";}
      if($formvars['font_scale']){$sql .= ", `font_scale` = '".$formvars['font_scale']."'";}
			if($formvars['font_lage']){$sql .= ", `font_lage` = '".$formvars['font_lage']."'";}
			if($formvars['font_gemeinde']){$sql .= ", `font_gemeinde` = '".$formvars['font_gemeinde']."'";}
      if($formvars['font_gemarkung']){$sql .= ", `font_gemarkung` = '".$formvars['font_gemarkung']."'";}
      if($formvars['font_flur']){$sql .= ", `font_flur` = '".$formvars['font_flur']."'";}
			if($formvars['font_flurst']){$sql .= ", `font_flurst` = '".$formvars['font_flurst']."'";}
      if($formvars['font_legend']){$sql .= ", `font_legend` = '".$formvars['font_legend']."'";}
      if($formvars['font_user']){$sql .= ", `font_user` = '".$formvars['font_user']."'";}
      if($formvars['font_watermark']){$sql .= ", `font_watermark` = '".$formvars['font_watermark']."'";}

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '".$_files['headsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `headsrc` = '".$formvars['headsrc_save']."'";
      }
      if($_files['refmapsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapsrc']['name'];
        if (move_uploaded_file($_files['refmapsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapsrc` = '".$_files['refmapsrc']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapsrc` = '".$formvars['refmapsrc_save']."'";
      }
      if($_files['refmapfile']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['refmapfile']['name'];
        if (move_uploaded_file($_files['refmapfile']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['headsrc']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `refmapfile` = '".$_files['refmapfile']['name']."'";
        }
        else {
            //echo '<br>Datei: '.$_files['headsrc']['tmp_name'].' konnte nicht nach '.$nachDatei.' hochgeladen werden!';
        }
      }
      else{
        $sql .= ", `refmapfile` = '".$formvars['refmapfile_save']."'";
      }
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);
      $lastdruckrahmen_id = mysql_insert_id();

      $sql = 'INSERT INTO druckrahmen2stelle (stelle_id, druckrahmen_id) VALUES('.$stelle_id.', '.$lastdruckrahmen_id.')';
      $this->debug->write("<p>file:kvwmap class:Document->save_frame :",4);
      $this->database->execSQL($sql,4, 1);

      for($i = 0; $i < $formvars['textcount']; $i++){
        $formvars['text'.$i] = str_replace(chr(10), ';', $formvars['text'.$i]);
        $formvars['text'.$i] = str_replace(chr(13), '', $formvars['text'.$i]);
        $sql = "INSERT INTO druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
        $lastfreitext_id = mysql_insert_id();

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
      $sql .= " SET `Name` = '".$formvars['Name']."'";
			$sql .= ", `dhk_call` = '".$formvars['dhk_call']."'";
      $sql .= ", `headposx` = '".$formvars['headposx']."'";
      $sql .= ", `headposy` = '".$formvars['headposy']."'";
      $sql .= ", `headwidth` = '".$formvars['headwidth']."'";
      $sql .= ", `headheight` = '".$formvars['headheight']."'";
      $sql .= ", `mapposx` = '".$formvars['mapposx']."'";
      $sql .= ", `mapposy` = '".$formvars['mapposy']."'";
      $sql .= ", `mapwidth` = '".$formvars['mapwidth']."'";
      $sql .= ", `mapheight` = '".$formvars['mapheight']."'";
      $sql .= ", `refmapposx` = '".$formvars['refmapposx']."'";
      $sql .= ", `refmapposy` = '".$formvars['refmapposy']."'";
      $sql .= ", `refmapwidth` = '".$formvars['refmapwidth']."'";
      $sql .= ", `refmapheight` = '".$formvars['refmapheight']."'";
      $sql .= ", `refposx` = '".$formvars['refposx']."'";
      $sql .= ", `refposy` = '".$formvars['refposy']."'";
      $sql .= ", `refwidth` = '".$formvars['refwidth']."'";
      $sql .= ", `refheight` = '".$formvars['refheight']."'";
      $sql .= ", `refzoom` = '".$formvars['refzoom']."'";
      $sql .= ", `dateposx` = '".$formvars['dateposx']."'";
      $sql .= ", `dateposy` = '".$formvars['dateposy']."'";
      $sql .= ", `datesize` = '".$formvars['datesize']."'";
      $sql .= ", `scaleposx` = '".$formvars['scaleposx']."'";
      $sql .= ", `scaleposy` = '".$formvars['scaleposy']."'";
      $sql .= ", `scalesize` = '".$formvars['scalesize']."'";
			$sql .= ", `scalebarposx` = '".$formvars['scalebarposx']."'";
      $sql .= ", `scalebarposy` = '".$formvars['scalebarposy']."'";
      $sql .= ", `oscaleposx` = '".$formvars['oscaleposx']."'";
      $sql .= ", `oscaleposy` = '".$formvars['oscaleposy']."'";
      $sql .= ", `oscalesize` = '".$formvars['oscalesize']."'";
			$sql .= ", `lageposx` = '".$formvars['lageposx']."'";
      $sql .= ", `lageposy` = '".$formvars['lageposy']."'";
      $sql .= ", `lagesize` = '".$formvars['lagesize']."'";
			$sql .= ", `gemeindeposx` = '".$formvars['gemeindeposx']."'";
      $sql .= ", `gemeindeposy` = '".$formvars['gemeindeposy']."'";
      $sql .= ", `gemeindesize` = '".$formvars['gemeindesize']."'";
      $sql .= ", `gemarkungposx` = '".$formvars['gemarkungposx']."'";
      $sql .= ", `gemarkungposy` = '".$formvars['gemarkungposy']."'";
      $sql .= ", `gemarkungsize` = '".$formvars['gemarkungsize']."'";
      $sql .= ", `flurposx` = '".$formvars['flurposx']."'";
      $sql .= ", `flurposy` = '".$formvars['flurposy']."'";
      $sql .= ", `flursize` = '".$formvars['flursize']."'";
			$sql .= ", `flurstposx` = '".$formvars['flurstposx']."'";
      $sql .= ", `flurstposy` = '".$formvars['flurstposy']."'";
      $sql .= ", `flurstsize` = '".$formvars['flurstsize']."'";
      $sql .= ", `legendposx` = '".$formvars['legendposx']."'";
      $sql .= ", `legendposy` = '".$formvars['legendposy']."'";
      $sql .= ", `legendsize` = '".$formvars['legendsize']."'";
      $sql .= ", `arrowposx` = '".$formvars['arrowposx']."'";
      $sql .= ", `arrowposy` = '".$formvars['arrowposy']."'";
      $sql .= ", `arrowlength` = '".$formvars['arrowlength']."'";
      $sql .= ", `userposx` = '".$formvars['userposx']."'";
      $sql .= ", `userposy` = '".$formvars['userposy']."'";
      $sql .= ", `usersize` = '".$formvars['usersize']."'";
      $sql .= ", `watermark` = '".$formvars['watermark']."'";
      $sql .= ", `watermarkposx` = '".$formvars['watermarkposx']."'";
      $sql .= ", `watermarkposy` = '".$formvars['watermarkposy']."'";
      $sql .= ", `watermarksize` = '".$formvars['watermarksize']."'";
      $sql .= ", `watermarkangle` = '".$formvars['watermarkangle']."'";
      $sql .= ", `watermarktransparency` = '".$formvars['watermarktransparency']."'";
      if($formvars['variable_freetexts'] != 1)$formvars['variable_freetexts'] = 0;
      $sql .= ", `variable_freetexts` = ".$formvars['variable_freetexts'];
      $sql .= ", `format` = '".$formvars['format']."'";
      $sql .= ", `preis` = '".$preis."'";
      $sql .= ", `font_date` = '".$formvars['font_date']."'";
      $sql .= ", `font_scale` = '".$formvars['font_scale']."'";
			$sql .= ", `font_lage` = '".$formvars['font_lage']."'";
			$sql .= ", `font_gemeinde` = '".$formvars['font_gemeinde']."'";
      $sql .= ", `font_gemarkung` = '".$formvars['font_gemarkung']."'";
      $sql .= ", `font_flur` = '".$formvars['font_flur']."'";
			$sql .= ", `font_flurst` = '".$formvars['font_flurst']."'";
      $sql .= ", `font_legend` = '".$formvars['font_legend']."'";
      $sql .= ", `font_user` = '".$formvars['font_user']."'";
      $sql .= ", `font_watermark` = '".$formvars['font_watermark']."'";

      if($_files['headsrc']['name']){
        $nachDatei = DRUCKRAHMEN_PATH.$_files['headsrc']['name'];
        if (move_uploaded_file($_files['headsrc']['tmp_name'],$nachDatei)) {
            //echo '<br>Lade '.$_files['Wappen']['tmp_name'].' nach '.$nachDatei.' hoch';
          $sql .= ", `headsrc` = '".$_files['headsrc']['name']."'";
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
          $sql .= ", `refmapsrc` = '".$_files['refmapsrc']['name']."'";
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
          $sql .= ", `refmapfile` = '".$_files['refmapfile']['name']."'";
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
        $sql = "UPDATE druckfreitexte SET `text` = '".$formvars['text'.$i]."'";
        $sql .= ", `posx` = ".$formvars['textposx'.$i];
        $sql .= ", `posy` = ".$formvars['textposy'.$i];
        $sql .= ", `size` = ".$formvars['textsize'.$i];
        $sql .= ", `angle` = ".$formvars['textangle'.$i];
        $sql .= ", `font` = '".$formvars['textfont'.$i]."'";
        $sql .= " WHERE id = ".$formvars['text_id'.$i];
        #echo $sql;
        $this->debug->write("<p>file:kvwmap class:Document->update_frame :",4);
        $this->database->execSQL($sql,4, 1);
      }
    }
  }

  function add_frame2stelle($id, $stelleid){
    $sql ="INSERT IGNORE INTO druckrahmen2stelle VALUES (".$stelleid.", ".$id.")";
    $this->debug->write("<p>file:kvwmap class:Document->add_frame2stelle :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function removeFrames($stelleid){
    $sql ="DELETE FROM druckrahmen2stelle WHERE stelle_id = ".$stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->removeFrames :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function save_active_frame($id, $userid, $stelleid){
    $sql ="UPDATE `rolle` SET `active_frame` = '".$id."' WHERE `user_id` =".$userid." AND `stelle_id` =".$stelleid;
    $this->debug->write("<p>file:kvwmap class:Document->save_active_frame :",4);
    $this->database->execSQL($sql,4, 1);
  }

  function get_active_frameid($userid, $stelleid){
    $sql ='SELECT active_frame from rolle WHERE `user_id` ='.$userid.' AND `stelle_id` ='.$stelleid;
    $this->debug->write("<p>file:kvwmap class:GUI->get_active_frameid :<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    return $rs[0];
  }
}

?>
