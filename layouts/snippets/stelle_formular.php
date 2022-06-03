<?php
  # 2007-12-30 pk
  include(LAYOUTPATH . 'languages/stelle_formular_' . $this->user->rolle->language . '.php');

?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript">
</script>
<script language="JavaScript">
<!--

function gotoStelle(event, option_obj) {
	if (event.layerX > 300){
		location.href = 'index.php?go=Stelleneditor&selected_stelle_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
}

function gotoLayer(event, option_obj) {
	if(event.layerX > 300){
		location.href = 'index.php?go=Layereditor&selected_layer_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
}

function gotoUser(event, option_obj) {
	if(event.layerX > 300){
		location.href = 'index.php?go=Benutzerdaten_Formular&selected_user_id=' + option_obj.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
	}
}

function getsubmenues() {
	menue_id = document.GUI.allmenues.options[document.GUI.allmenues.selectedIndex].value;
	ahah('index.php', 'go=getsubmenues&menue_id=' + menue_id, new Array(document.getElementById('submenue_div')), '');
}

function getlayer() {
	group_id = document.GUI.allgroups.options[document.GUI.allgroups.selectedIndex].value;
	ahah('index.php', 'go=getlayerfromgroup&group_id=' + group_id, new Array(document.getElementById('alllayer_div')), '');
}

function select_layer(){
	groupid = document.GUI.allgroups.options[document.GUI.allgroups.selectedIndex].value;
	selectObj = document.GUI.selectedlayer;
	for(i = 0; i < selectObj.length; i++){
		id_string = selectObj.options[i].id + "";
		id_split = id_string.split('_');
		if(id_split[1] == groupid)selectObj.options[i].selected = true;
		else selectObj.options[i].selected = false;
	}
}

function select_submenues(){
	selectObj = document.GUI.selectedmenues;
	index = selectObj.selectedIndex;
	id_string = selectObj.options[index].id + "";
	id_split = id_string.split('_');
	if(id_split[2] == '1'){
		for(i = index+1; i < selectObj.length; i++){
			id_string = selectObj.options[i].id + "";
			id_split = id_string.split('_');
			if(id_split[2] == '2')selectObj.options[i].selected = true;
			if(id_split[2] == '1')return;
		}
	}
}

function getInsertIndex(insertObj, id, order, start){
	// diese Funktion ermittelt den index, an dem ein Element aus einem anderen Selectfeld mit der Reihenfolge 'order' eingefügt werden muss
	// (die Order wird hier in Selectfeldern im Attribut 'id' gespeichert)
	// (Man muss hier unterscheiden zwischen 1. der Menüorder - die wird in der id gespeichert und
	// 																			 2. dem eigentlichen index i im Selectfeld)	
	// start ist der index i, bei dem die Suche startet
	ordersplit = order.split('_');
	order_to_be_inserted = parseInt(ordersplit[0]);
	menueebene_to_be_inserted = parseInt(ordersplit[2]);
	for(i=start; i<insertObj.length; i++) {
		if(insertObj.options[i].value == id){
			return -i - 100;			// Menü ist bereits vorhanden -> index negieren und 100 abziehen fuer den Fall dass i = 0 ist
		}
		options_order_string = insertObj.options[i].id + "";
		options_order_split = options_order_string.split('_');
		order_in_list = parseInt(options_order_split[0]);
		menueebene_in_list = parseInt(options_order_split[2]);
		if((menueebene_in_list == menueebene_to_be_inserted && order_in_list >= order_to_be_inserted) ||
		(menueebene_in_list == 1 && menueebene_to_be_inserted == 2)){			//naechster Obermenuepunkt
			return i;
		}
	}
	return insertObj.length;		// am Ende einfügen
}

function addMenues(){
	// index ermitteln
	index = getInsertIndex(document.GUI.selectedmenues, document.GUI.allmenues.options[document.GUI.allmenues.selectedIndex].value, document.GUI.allmenues.options[document.GUI.allmenues.selectedIndex].id, 0);
	if(index >= 0){
		addOptionsWithIndex(document.GUI.allmenues,document.GUI.selectedmenues,document.GUI.selmenues,'value', index);		// Obermenü hinzufügen
		if(document.GUI.submenues.length > 0){
			addOptionsWithIndex(document.GUI.submenues,document.GUI.selectedmenues,document.GUI.selmenues,'value', index+1);	// wenn vorhanden, Untermenüs hinzufügen
		}
	}
	else{					// Obermenue ist bereits vorhanden
		index = -1 * index - 99;				// index für die Untermenüs ermitteln, beginnend beim index des Obermenues
		submenueindex = getInsertIndex(document.GUI.selectedmenues, document.GUI.submenues.options[document.GUI.submenues.selectedIndex].value, document.GUI.submenues.options[document.GUI.submenues.selectedIndex].id, index);
		if(submenueindex > 0){
			addOptionsWithIndex(document.GUI.submenues,document.GUI.selectedmenues,document.GUI.selmenues,'value', submenueindex);		// Untermenüs hinzufügen
		}
	} 
}

//-->
</script>
<br>
<table border="0" cellpadding="5" cellspacing="0">
  <tr align="center">
    <td><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
    <td align="center"><?php
if ($this->Meldung=='Daten der Stelle erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $this->Fehlermeldung=$this->Meldung;
  include('Fehlermeldung.php');
  $bgcolor=BG_FORMFAIL;
}
 ?>
    <table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
        <tr align="center">
          <td colspan="3" style="border-bottom:1px solid #C3C7C3"><em><span class="px13"><?php echo $strAsteriskRequired; ?> </span></em></td>
        </tr><?php if ($this->formvars['selected_stelle_id']>0) {?>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><div style="width: 220px;"><?php echo $strDataBankID; ?></div></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
          	<input name="id" type="text" value="<?php echo $this->formvars['selected_stelle_id']; ?>" size="25" maxlength="11">
          </td>
        </tr><?php } ?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strLabel; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3">
						<input name="bezeichnung" type="text" value="<?php echo $this->formvars['bezeichnung']; ?>" size="25" maxlength="100">
					</td>
				</tr>
				<tr><?
				if ($this->user->rolle->language != 'german') { ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strLabel . ' ' . ucfirst($this->user->rolle->language); ?></th>
						<td colspan=2 style="border-bottom:1px solid #C3C7C3">
							<input name="Bezeichnung_<? echo $this->user->rolle->language; ?>" type="text" value="<?php echo $this->formvars['Bezeichnung_' . $this->user->rolle->language]; ?>" size="25" maxlength="100">
						</td>
					</tr><?
				} ?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strReferenceMap; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3"><?
						include_once(CLASSPATH . 'Referenzkarte.php');
						include_once(CLASSPATH . 'FormObject.php');
						$referenzkarten = array_map(
							function($referenzkarte) {
								return array(
									'value' => $referenzkarte->get('ID'),
									'output' => $referenzkarte->get('Name')
								);
							},
							Referenzkarte::find($this, '', 'Name')
						);
						echo FormObject::createSelectField(
							'Referenzkarte_ID',
							$referenzkarten,
							$this->formvars['Referenzkarte_ID'],
							1,
							'',
							'$(\'#ref_map_img_prev\').attr(\'src\', \'index.php?go=showRefMapImage&ID=\' + this.value + \'&csrf_token=' . $_SESSION['csrf_token'] . '\')'
						);
						if($this->formvars['Referenzkarte_ID']){
							$referenzkarte = Referenzkarte::find_by_id($this, $this->formvars['Referenzkarte_ID']); ?>
							<img id="ref_map_img_prev" src="index.php?go=showRefMapImage&ID=<? echo $referenzkarte->get('ID'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" style="vertical-align: middle" onchange="this.src=">
						<? } ?>
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMinExtent; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              x&nbsp;<input name="minxmax" type="text" value="<?php echo $this->formvars['minxmax']; ?>" size="15" maxlength="100">
              y&nbsp;<input name="minymax" type="text" value="<?php echo $this->formvars['minymax']; ?>" size="15" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strMaxExtent;  ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              x&nbsp;<input name="maxxmax" type="text" value="<?php echo $this->formvars['maxxmax']; ?>" size="15" maxlength="100">
              y&nbsp;<input name="maxymax" type="text" value="<?php echo $this->formvars['maxymax']; ?>" size="15" maxlength="100">
          </td>
        </tr>
        <tr>
        	<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEpsgCode; ?></th>
					<td colspan=2 style="border-bottom:1px solid #C3C7C3">
						<select name="epsg_code">
							<option value=""><?php echo $this->strPleaseSelect; ?></option><? 
							foreach ($this->epsg_codes as $epsg_code) {
								echo '
									<option' .
										($this->formvars['epsg_code'] == $epsg_code['srid'] ? ' selected ' : '') . '
										value="' . $epsg_code['srid'] . '"
									>' . $epsg_code['srid'] . ': ' . $epsg_code['srtext'] . '</option>';
							} ?>
						</select>
					</td>
				</tr>
				<tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStart; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="start" type="text" value="<?php echo $this->formvars['start']; ?>" size="25" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStop; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="stop" type="text" value="<?php echo $this->formvars['stop']; ?>" size="25" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsTitle; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_title" type="text" value="<?php echo $this->formvars['ows_title']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsAbstract; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_abstract" type="text" value="<?php echo $this->formvars['ows_abstract']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWmsAccessConstraints; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="wms_accessconstraints" type="text" value="<?php echo $this->formvars['wms_accessconstraints']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsContactPerson; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_contactperson" type="text" value="<?php echo $this->formvars['ows_contactperson']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsContactOrganization; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_contactorganization" type="text" value="<?php echo $this->formvars['ows_contactorganization']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsContactEmailAddress; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_contactemailaddress" type="text" value="<?php echo $this->formvars['ows_contactemailaddress']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsContactPosition; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_contactposition" type="text" value="<?php echo $this->formvars['ows_contactposition']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsFees; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_fees" type="text" value="<?php echo $this->formvars['ows_fees']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strOwsSrs; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="ows_srs" type="text" value="<?php echo $this->formvars['ows_srs']; ?>" size="50" maxlength="100"></td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLogo; ?></th>
          <td style="border-bottom:1px solid #C3C7C3">
            &nbsp;<input type="file" name="wappen" size="15"><br>
            &nbsp;<? echo $this->formvars['wappen'] ?>
          </td>

					<td style="border-bottom:1px solid #C3C7C3"><?
						if ($this->formvars['wappen'] != '') { ?>
							&nbsp;<img src="<? echo WAPPENPATH.basename($this->formvars['wappen']); ?>" width="100" 
alt="<?php echo $strNoLogoSelected; ?>"><?
						} ?>
						<input type="hidden" name="wappen_save" value="<? echo $this->formvars['wappen']; ?>">
					</td>
				</tr>

				<tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWappenLink; ?></th>
          <td colspan=2 style="border-bottom:1px solid #C3C7C3">
              <input name="wappen_link" type="text" value="<?php echo $this->formvars['wappen_link']; ?>" size="50" maxlength="100">
          </td>
        </tr>
        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <th class="fetter" align="right"><?php echo $strMenuPoint; ?></th>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                </tr>
            </table>
          </th>
          <td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td><?php echo $strAssigned; ?><br>

                      <select name="selectedmenues" size="12" onchange="select_submenues();" multiple style="width: 340px">
                      <?
                      for($i=0; $i < count($this->formvars['selmenues']["Bezeichnung"]); $i++){
                        echo '<option id="'.$this->formvars['selmenues']["ORDER"][$i].'_sel_'.$this->formvars['selmenues']["menueebene"][$i].'_'.$i.'" title="'.str_replace(' ', '&nbsp;', $this->formvars['selmenues']["Bezeichnung"][$i]).'" value="'.$this->formvars['selmenues']["ID"][$i].'">'.$this->formvars['selmenues']["Bezeichnung"][$i].'</option>';
                      }
                      ?>
                      </select>
                    </td>
                    <td align="center" valign="middle" width="1">
                      <input type="button" name="addPlaces" value="&laquo;" onClick="addMenues()">
                      <input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedmenues,document.GUI.selmenues,'value')>
                    </td>
                    <td>
                      <?php echo $strAvailable; ?><br>
                      <select name="allmenues" size="6" onchange="getsubmenues();" style="width: 340px">
                      <? for($i=0; $i < count($this->formvars['menues']); $i++){
                          echo '<option id="'.$this->formvars['menues'][$i]->data['order'].'_all_'.$this->formvars['menues'][$i]->data['menueebene'].'_'.$i.'" title="'.str_replace(' ', '&nbsp;', $this->formvars['menues'][$i]->data['name']).'" value="'.$this->formvars['menues'][$i]->data['id'].'">'.$this->formvars['menues'][$i]->data['name'].'</option>';
                           }
                      ?>
                      </select>
                      <div id="submenue_div">
                      	<select name="submenues" size="6" multiple style="width: 340px">
                      	</select>
                      </div>
                    </td>
                </tr>
              </table>
          </td>
        </tr>

				<tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <th class="fetter" align="right">Funktionen</th>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                </tr>
            </table>
          </th>
          <td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td>
                      <select name="selectedfunctions" size="6" multiple style="width: 340px">
                      <?
                      for($i=0; $i < count($this->formvars['selfunctions']); $i++){
                          echo '<option title="'.str_replace(' ', '&nbsp;', $this->formvars['selfunctions'][$i]["bezeichnung"]).'" value="'.$this->formvars['selfunctions'][$i]["id"].'">'.$this->formvars['selfunctions'][$i]["bezeichnung"].'</option>';
                         }
                      ?>
                      </select>
                    </td>
                    <td align="center" valign="middle" width="1">
                      <input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.allfunctions,document.GUI.selectedfunctions,document.GUI.selfunctions,'value')>
                      <input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedfunctions,document.GUI.selfunctions,'value')>
                    </td>
                    <td>
                      <select name="allfunctions" size="6" multiple style="width: 340px">
                      <? for($i=0; $i < count($this->formvars['functions']); $i++){
                          echo '<option title="'.str_replace(' ', '&nbsp;', $this->formvars['functions'][$i]["bezeichnung"]).'" value="'.$this->formvars['functions'][$i]["id"].'">'.$this->formvars['functions'][$i]["bezeichnung"].'</option>';
                           }
                      ?>
                      </select>
                    </td>
                </tr>
              </table>
          </td>
        </tr>

				<tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <th class="fetter" align="right">Kartendruck-Layouts</th>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                </tr>
            </table>
          </th>
          <td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td>
                      <select name="selectedframes" size="6" multiple style="width: 340px">
                      <?
                      for($i=0; $i < count($this->formvars['selframes']); $i++){
                          echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['selframes'][$i]["Name"]).' value="'.$this->formvars['selframes'][$i]["id"].'">'.$this->formvars['selframes'][$i]["Name"].'</option>';
                         }
                      ?>
                      </select>
                    </td>
                    <td align="center" valign="middle" width="1">
                      <input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.allframes,document.GUI.selectedframes,document.GUI.selframes,'value')>
                      <input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedframes,document.GUI.selframes,'value')>
                    </td>
                    <td>
                      <select name="allframes" size="6" multiple style="width: 340px">
                      <? for($i=0; $i < count($this->formvars['frames']); $i++){
                          echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['frames'][$i]["Name"]).'  value="'.$this->formvars['frames'][$i]["id"].'">'.$this->formvars['frames'][$i]["Name"].'</option>';
                           }
                      ?>
                      </select>
                    </td>
                </tr>
              </table>
          </td>
        </tr>
				
				<tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <th class="fetter" align="right">Datendruck-Layouts</th>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                </tr>
            </table>
          </th>
          <td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td>
                      <select name="selectedlayouts" size="6" multiple style="width: 340px">
                      <?
                      for($i=0; $i < count($this->formvars['sellayouts']); $i++){
                          echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['sellayouts'][$i]["name"]).' value="'.$this->formvars['sellayouts'][$i]["id"].'">['.$this->formvars['sellayouts'][$i]["layer_id"].'] '.$this->formvars['sellayouts'][$i]["name"].'</option>';
                         }
                      ?>
                      </select>
                    </td>
                    <td align="center" valign="middle" width="1">
                      <input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.alllayouts,document.GUI.selectedlayouts,document.GUI.sellayouts,'value')>
                      <input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedlayouts,document.GUI.sellayouts,'value')>
                    </td>
                    <td>
                      <select name="alllayouts" size="6" multiple style="width: 340px">
                      <? for($i=0; $i < count($this->formvars['layouts']); $i++){
                          echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['layouts'][$i]["name"]).'  value="'.$this->formvars['layouts'][$i]["id"].'">['.$this->formvars['layouts'][$i]["layer_id"].'] '.$this->formvars['layouts'][$i]["name"].'</option>';
                           }
                      ?>
                      </select>
                    </td>
                </tr>
              </table>
          </td>
        </tr>

        <tr>
          <th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <th class="fetter" align="right">
                  <? if(count($this->formvars['sellayer']["Bezeichnung"]) > 0){?>
                    <a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strEdit; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?}?>
                  <?php echo $strLayer; ?></th>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                </tr>
            </table>
          </th>
          <td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                    <td>
                      <select name="selectedlayer" size="12" multiple style="position: relative; width: 340px">
                      <?
                      for($i=0; $i < count($this->formvars['sellayer']["Bezeichnung"]); $i++){
                          echo '<option class="select_option_link" onclick="gotoLayer(event, this)" title='.str_replace(' ', '&nbsp;', $this->formvars['sellayer']["Bezeichnung"][$i]).' id="'.$this->formvars['sellayer']["ID"][$i].'_'.$this->formvars['sellayer']["Gruppe"][$i].'" value="'.$this->formvars['sellayer']["ID"][$i].'">'.$this->formvars['sellayer']["Bezeichnung"][$i].'</option>';
                         }
                      ?>
                      </select>
                    </td>
                    <td align="center" valign="middle" width="1">
                      <input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.alllayer,document.GUI.selectedlayer,document.GUI.sellayer,'value')>
                      <input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedlayer,document.GUI.sellayer,'value')>
                    </td>
                    <td>
											<select name="allgroups" size="6" onchange="getlayer();select_layer();" style="width: 340px">
												<option value=""> - alle - </option>
                      <? for($i = 0; $i < count($this->layergruppen['ID']); $i++){
                          echo '<option title="'.str_replace(' ', '&nbsp;', $this->layergruppen['Bezeichnung'][$i]).'" value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
                         }
                      ?>
                      </select>                    
                    	<div id="alllayer_div">
                      <select name="alllayer" size="6" multiple style="position: relative; width: 340px">
                      <? for($i=0; $i < count($this->formvars['layer']["Bezeichnung"]); $i++){
                          echo '<option class="select_option_link" onclick="gotoLayer(event, this)" title='.str_replace(' ', '&nbsp;', $this->formvars['layer']["Bezeichnung"][$i]).' id="'.$this->formvars['layer']["ID"][$i].'_'.$this->formvars['layer']["GruppeID"][$i].'" value="'.$this->formvars['layer']["ID"][$i].'">'.$this->formvars['layer']["Bezeichnung"][$i].'</option>';
                           }
                      ?>
                      </select>
                      </div>                    
                    </td>
                </tr>
              </table>
          </td>
        </tr>

				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<th class="fetter" align="right"><?php echo $strUser;?></th>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</th>
					<td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td>
									<select name="selectedusers" size="6" multiple style="position: relative; width: 340px"><?
										$seluseroptions[] = array('value' => '', 'output' => '-- Auswahl --');
										for ($i = 0; $i < count($this->formvars['selusers']["Bezeichnung"]); $i++) {
											$seluseroptions[] = array(
												'value'  => $this->formvars['selusers']["ID"][$i],
												'output' => $this->formvars['selusers']["Bezeichnung"][$i]
											); ?>
											<option
												class="select_option_link" onclick="gotoUser(event, this)" 
												title="<?php echo str_replace(' ', '&nbsp;', $this->formvars['selusers']["Bezeichnung"][$i]); ?>"
												value="<?php echo $this->formvars['selusers']["ID"][$i]; ?>"
											><?php echo $this->formvars['selusers']["Bezeichnung"][$i]; ?></option><?php
										}?>
									</select>
								</td>
								<td align="center" valign="middle" width="1">
									<input type="button" name="addPlaces" value="&laquo;" onClick=addOptions(document.GUI.allusers,document.GUI.selectedusers,document.GUI.selusers,'value')>
									<input type="button" name="substractPlaces" value="&raquo;" onClick=substractOptions(document.GUI.selectedusers,document.GUI.selusers,'value')>
								</td>
								<td>
									<select name="allusers" size="6" multiple style="position: relative; width: 340px"><?
										for ($i = 0; $i < count($this->formvars['users']["Bezeichnung"]); $i++) { ?>
											<option
												class="select_option_link" onclick="gotoUser(event, this)" 
												title="<?php echo str_replace(' ', '&nbsp;', $this->formvars['users']["Bezeichnung"][$i]); ?>"
												value="<?php echo $this->formvars['users']["ID"][$i]; ?>"
											><?php echo $this->formvars['users']["Bezeichnung"][$i]; ?></option><?php
										}?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<th class="fetter" align="right">Übergeordnete Stellen</th>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</th>
					<td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td><?php
									$options = array_map(
										function($parent) {
											return array(
												'value' => $parent['ID'],
												'title' => str_replace(' ', '&nbsp;', $parent["Bezeichnung"]),
												'output' => $parent['Bezeichnung']
											);
										},
										$this->formvars['selparents']
									);
									echo FormObject::createSelectField('selectedparents', $options, '', 6, 'position: relative; width: 340px', '', '', 'multiple', '', '', '', 'select_option_link', 'gotoStelle(event, this);');?>
								</td>
								<td align="center" valign="middle" width="1">
									<input
										type="button"
										name="addPlaces"
										value="&laquo;"
										onClick="addOptions(document.GUI.allparents, document.GUI.selectedparents, document.GUI.selparents, 'value')"
									>
									<input
										type="button"
										name="substractPlaces"
										value="&raquo;"
										onClick="substractOptions(document.GUI.selectedparents, document.GUI.selparents, 'value')"
									>
								</td>
								<td><?php
									$options = array_map(
										function($parent) {
											return array(
												'value' => $parent->get('ID'),
												'title' => str_replace(' ', '&nbsp;', $parent->get("Bezeichnung")),
												'output' => $parent->get('Bezeichnung')
											);
										},
										$this->formvars['parents']
									);
									echo FormObject::createSelectField('allparents', $options, '', 6, 'position: relative; width: 340px', '', '', 'multiple', '', '', '', 'select_option_link', 'gotoStelle(event, this);');?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<th class="fetter" align="right">Untergeordnete Stellen</th>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</th>
					<td colspan=2 valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td><?php
									$options = array_map(
										function($child) {
											return array(
												'value' => $child['ID'],
												'title' => str_replace(' ', '&nbsp;', $child["Bezeichnung"]),
												'output' => $child['Bezeichnung']
											);
										},
										$this->formvars['selchildren']
									);
									echo FormObject::createSelectField('selectedchildren', $options, '', 6, 'position: relative; width: 340px', '', '', 'multiple', '', '', '', 'select_option_link', 'gotoStelle(event, this);');?>
								</td>
								<td align="center" valign="middle" width="1">
									<input
										type="button"
										name="addPlaces"
										value="&laquo;"
										onClick="addOptions(document.GUI.allchildren, document.GUI.selectedchildren, document.GUI.selchildren, 'value')"
									>
									<input
										type="button"
										name="substractPlaces"
										value="&raquo;"
										onClick="substractOptions(document.GUI.selectedchildren, document.GUI.selchildren, 'value')"
									>
								</td>
								<td><?php
									$options = array_map(
										function($child) {
											return array(
												'value' => $child->get('ID'),
												'title' => str_replace(' ', '&nbsp;', $child->get("Bezeichnung")),
												'output' => $child->get('Bezeichnung')
											);
										},
										$this->formvars['children']
									);
									echo FormObject::createSelectField('allchildren', $options, '', 6, 'position: relative; width: 340px', '', '', 'multiple', '', '', '', 'select_option_link', 'gotoStelle(event, this);');?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strDefaultUserID; ?>
					</td>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php
						echo FormObject::createSelectField('default_user_id', $seluseroptions, $this->formvars['default_user_id'], 1); ?>
					</td>
				</tr>

				<tr>
					<td align="right" style="border-bottom:1px solid #C3C7C3">
						<input
							name="show_shared_layers"
							type="checkbox"
							value="1"
							<?php echo ($this->formvars['show_shared_layers'] ? ' checked' : ''); ?>
						>
					</td>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strShowSharedLayersText; ?>
						<span data-tooltip="<?php echo $strShowSharedLayersDescription; ?>"></span>
					</td>
				</tr>
        <tr>
          <td align="right" style="border-bottom:1px solid #C3C7C3">
            <input name="checkClientIP" type="checkbox" value="1" <?php if ($this->formvars['checkClientIP']) { ?> checked<?php } ?>>
          </td>
          <td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strcheckClientIP; ?></td>
        </tr>
        <tr>
          <td align="right" style="border-bottom:1px solid #C3C7C3">
            <input name="checkPasswordAge" type="checkbox" value="1" <?php if ($this->formvars['checkPasswordAge']) { ?> checked<?php } ?>>
          </td>
          <td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strCheckPasswordAge; ?></td>
        </tr>
        <tr>
          <td align="right" style="border-bottom:1px solid #C3C7C3">
            <input name="allowedPasswordAge" type="text" size="1" value="<?php echo $this->formvars['allowedPasswordAge']; ?>">
          </td>
          <td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strAllowedPasswordAge; ?></td>
        </tr>
        <tr>
          <td align="right" style="border-bottom:1px solid #C3C7C3">
            <input name="use_layer_aliases" type="checkbox" value="1" <? if ($this->formvars['use_layer_aliases']) { ?> checked<? } ?>>
          </td>
          <td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strUseLayerAliases; ?></td>
        </tr>
        <tr>
          <td align="right" style="border-bottom:1px solid #C3C7C3">
            <input name="hist_timestamp" type="checkbox" value="1" <?php if ($this->formvars['hist_timestamp']) { ?> checked<?php } ?>>
          </td>
          <td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strhist_timestamp; ?></td>
        </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td align="center">
    	<input type="hidden" name="go_plus" id="go_plus" value="">
    	<input type="button" onclick="location.href='index.php?go=Stellen_Anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>'" value="<?php echo $this->strButtonBack; ?>">&nbsp;<?php
     if ($this->formvars['selected_stelle_id']>0) {
     	?><input type="hidden" name="selected_stelle_id" value="<?php echo $this->formvars['selected_stelle_id']; ?>"><?php
     	 	# 2007-12-30 pk
     	 	# Wenn kein Button gewählt wurde, wird go_plus ohne Wert gesendet
      ?><input type="button" name="dummy" value="<?php echo $strButtonUpdate; ?>" onclick="submitWithValue('GUI','go_plus','Ändern')"><?php
     } ?>&nbsp;<input type="button" name="dummy" value="<?php echo $strButtonInsert; ?>" onclick="submitWithValue('GUI','go_plus','Als neue Stelle eintragen')">
  </td>
  </tr>
</table>

<input type="hidden" name="go" value="Stelleneditor">
<input type="hidden" name="selmenues" value="<?
        echo $this->formvars['selmenues']["ID"][0];
        for($i=1; $i < @count($this->formvars['selmenues']["Bezeichnung"]); $i++){
          echo ', '.$this->formvars['selmenues']["ID"][$i];
        }
      ?>">
<input type="hidden" name="selfunctions" value="<?
        echo $this->formvars['selfunctions'][0]["id"];
        for($i=1; $i < @count($this->formvars['selfunctions']); $i++){
          echo ', '.$this->formvars['selfunctions'][$i]["id"];
        }
      ?>">
<input type="hidden" name="selframes" value="<?
        echo $this->formvars['selframes'][0]["id"];
        for($i=1; $i < @count($this->formvars['selframes']); $i++){
          echo ', '.$this->formvars['selframes'][$i]["id"];
        }
      ?>">      
<input type="hidden" name="sellayouts" value="<?
        echo $this->formvars['sellayouts'][0]["id"];
        for($i=1; $i < @count($this->formvars['sellayouts']); $i++){
          echo ', '.$this->formvars['sellayouts'][$i]["id"];
        }
      ?>">

<input
	name="sellayer"
	value="<?php echo implode(', ', $this->formvars['sellayer']['ID']); ?>"
	type="hidden"
>

<input
	name="selusers"
	value="<?php if(!empty($this->formvars['selusers']['ID']))echo implode(', ', $this->formvars['selusers']['ID']); ?>"
	type="hidden"
>

<input
	name="selparents"
	value="<?php echo implode(', ', array_map(function($parent) { return $parent['ID']; }, $this->formvars['selparents'])); ?>"
	type="hidden"
>

<input
	name="selchildren"
	value="<?php echo implode(', ', array_map(function($child) { return $child['ID']; }, $this->formvars['selchildren'])); ?>"
	type="hidden"
>