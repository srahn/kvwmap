<?php
	# 2007-12-30 pk
	include(LAYOUTPATH . 'languages/stelle_formular_' . rolle::$language . '.php');
?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript">
</script>
<?php 
	global $admin_stellen;
  $is_admin_stelle = in_array($this->Stelle->id, $admin_stellen);
?>
<style>
	input[readonly]
{
    background-color: lightgray;
}
</style>
<script language="JavaScript">
<!--

function toggleGroup(group, show) {
	var img = document.getElementById(group);
	if (show || img.src.split(/[\\/]/).pop() == 'plus.gif') {
		img.src = '<? echo GRAPHICSPATH.'minus.gif'; ?>';
		$('.group-' + group).show();
	}
	else {
		img.src = '<? echo GRAPHICSPATH.'plus.gif'; ?>';		
		$('.group-' + group).hide();
	}
}

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
<style>
	.navigation{
		border-collapse: collapse; 
		width: 940px;
		background:rgb(248, 248, 249);
	}
	.navigation th{
		border: 1px solid #bbb;
		border-collapse: collapse;
		width: 17%;
	}
	.navigation th div{
		padding: 3px;
		padding: 9px 0 9px 0;
		width: 100%;
	}	
	.navigation th:not(.navigation-selected) a{
		color: #888;
	}	
	.navigation th:not(.navigation-selected):hover{
		background-color: rgb(238, 238, 239);
	}
	.navigation-selected{
		background-color: #c7d9e6;
	}
	.navigation-selected div{
		color: #111;
	}
	
	#form input[type="text"], #form select, #form textarea {
		width: 95%;
	}

	#form textarea {
		height: 40px;
	}

	#form input[type="float"] {
		width: 100px;
	}

	#form input[type="number"] {
		width: 41px;
	}

	#stellenzuweisung{
		display: none;
		width: 100%;
	}
	
	.layerform_header{
		background: rgb(199, 217, 230);
	}

	.group {
		background-color: <? echo BG_GLEATTRIBUTE; ?>;
		border-bottom: 2px solid lightgray;
	}

	.group-metadata {<?
		echo ($this->formvars['go'] == 'Dienstmetadaten' ? '' : 'display: none;'); ?>
	}

	span[data-tooltip] {
		position: relative;
		--left: -200px;
		--width: 400px;
	}

</style>
<br>
<table border="0" cellpadding="5" cellspacing="0" style="width: 100%">
	<tr align="center">
		<td><h2><? echo ($this->formvars['go'] == 'Dienstmetadaten' ? $this->strTask . ' ' . $this->Stelle->Bezeichnung : $strTitle); ?></h2></td>
	</tr>
	<? if (count_or_0($this->allstellendaten['ID']) > 0) { ?>
	<tr>
    <td style="text-align: center">
		<span class="px17 fetter"><? echo $this->strTask;?>:</span>
			<select id="selected_stelle_id" style="min-width:250px" size="1" name="selected_stelle_id" onchange="document.GUI.submit();">
				<option value="">--------- <?php echo $this->strPleaseSelect; ?> --------</option><?
				for ($i = 0; $i < count_or_0($this->allstellendaten['ID']); $i++){
					echo '<option';
					if ($this->allstellendaten['ID'][$i] == $this->formvars['selected_stelle_id']){
						echo ' selected';
					}
					echo ' value="'.$this->allstellendaten['ID'][$i].'">' . $this->allstellendaten['Bezeichnung'][$i] . '</option>';
				} ?>
			</select>
		</td>
	</tr>
	<? } ?>
	<tr>
		<td align="center"><?php
if ($this->Meldung=='Daten der Stelle erfolgreich eingetragen!' OR $this->Meldung=='') {
	$bgcolor=BG_FORM;
}
else {
	$this->Fehlermeldung=$this->Meldung;
	include('Fehlermeldung.php');
	$bgcolor=BG_FORMFAIL;
} ?>
		<table id="form" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3; width: 100%">
			<tr align="center">
				<td colspan="3" style="border-bottom:1px solid #C3C7C3"><em><span class="px13"><?php echo $strAsteriskRequired; ?> </span></em></td>
			</tr><?
			if ($this->formvars['go'] != 'Dienstmetadaten') {?>
				<tr>
					<th class="group" colspan="3" align="left"><a href="javascript:toggleGroup('stammdaten', false);"><img id="stammdaten" src="<? echo GRAPHICSPATH . 'minus.gif'; ?>"></a>&nbsp;Stammdaten</td>
				</tr><?
				if ($this->formvars['selected_stelle_id'] > 0) { ?>
					<tr class="group-stammdaten">
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><div style="width: 220px;"><?php echo $strDataBankID; ?></div></th>
						<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							<input name="id" type="text" value="<?php echo $this->formvars['selected_stelle_id']; ?>" size="25" maxlength="11" style="width: 75px">
						</td>
					</tr><?
				} ?>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strLabel; ?>*</th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="bezeichnung" type="text" value="<?php echo $this->formvars['bezeichnung']; ?>" size="25" maxlength="100">
					</td>
				</tr>

				<tr class="group-stammdaten"><?
				if (rolle::$language != 'german') { ?>
					<tr>
						<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $this->strLabel . ' ' . ucfirst(rolle::$language); ?></th>
						<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							<input name="bezeichnung_<? echo rolle::$language; ?>" type="text" value="<?php echo $this->formvars['bezeichnung_' . rolle::$language]; ?>" size="25" maxlength="100">
						</td>
					</tr><?
				} ?>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strReferenceMap; ?>*
					</th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3"><?
						include_once(CLASSPATH . 'Referenzkarte.php');
						include_once(CLASSPATH . 'FormObject.php');
						$referenzkarten = array_map(
							function($referenzkarte) {
								return array(
									'value' => $referenzkarte->get('id'),
									'output' => $referenzkarte->get('name')
								);
							},
							Referenzkarte::find($this, '', 'name')
						);
						echo FormObject::createSelectField(
							'referenzkarte_id',
							$referenzkarten,
							$this->formvars['referenzkarte_id'],
							1,
							'width: auto',
							'$(\'#ref_map_img_prev\').attr(\'src\', \'index.php?go=showRefMapImage&ID=\' + this.value + \'&csrf_token=' . $_SESSION['csrf_token'] . '\')'
						);
						if($this->formvars['referenzkarte_id']){
							$referenzkarte = Referenzkarte::find_by_id($this, $this->formvars['referenzkarte_id']); ?>
							<img id="ref_map_img_prev" src="index.php?go=showRefMapImage&ID=<? echo $referenzkarte->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" style="vertical-align: middle" onchange="this.src=">
						<? } ?>
					</td>
				</tr>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strMinExtent; ?>*
					</th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							x&nbsp;<input name="minxmax" type="float" value="<?php echo $this->formvars['minxmax']; ?>" size="15" maxlength="100">
							y&nbsp;<input name="minymax" type="float" value="<?php echo $this->formvars['minymax']; ?>" size="15" maxlength="100">
					</td>
				</tr>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strMaxExtent;	?>*
					</th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							x&nbsp;<input name="maxxmax" type="float" value="<?php echo $this->formvars['maxxmax']; ?>" size="15" maxlength="100">
							y&nbsp;<input name="maxymax" type="float" value="<?php echo $this->formvars['maxymax']; ?>" size="15" maxlength="100">
					</td>
				</tr>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEpsgCode; ?>*</th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<select name="epsg_code" style="width: auto">
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

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStart; ?></th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							<input name="start" type="text" value="<?php echo $this->formvars['start']; ?>" size="15" maxlength="10">
					</td>
				</tr>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStop; ?></th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							<input name="stop" type="text" value="<?php echo $this->formvars['stop']; ?>" size="15" maxlength="10">
					</td>
				</tr>

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strLogo; ?>*
					</th>
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

				<tr class="group-stammdaten">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strWappenLink; ?></th>
					<td colspan="2" style="border-bottom:1px solid #C3C7C3">
							<input name="wappen_link" type="text" value="<?php echo $this->formvars['wappen_link']; ?>" size="50" maxlength="100">
					</td>
				</tr>

				<tr>
					<th colspan="3" class="group" align="left"><a href="javascript:toggleGroup('zuordnungen', false);"><img id="zuordnungen" src="<? echo GRAPHICSPATH . 'minus.gif'; ?>"></a>&nbsp;Zuordnungen</td>
				</tr>

				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr valign="top">
										<td><?php echo $strAssigned; ?><br>

											<select name="selectedmenues" size="12" onchange="select_submenues();" multiple style="width: 340px">
											<?
											for($i=0; $i < count_or_0($this->formvars['selmenues']["Bezeichnung"]); $i++){
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

				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr valign="top">
										<td>
											<select name="selectedfunctions" size="6" multiple style="width: 340px">
											<?
											for($i=0; $i < count_or_0($this->formvars['selfunctions']); $i++){
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

				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr valign="top">
										<td>
											<select name="selectedframes" size="6" multiple style="width: 340px">
											<?
											for($i=0; $i < count_or_0($this->formvars['selframes']); $i++){
													echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['selframes'][$i]["name"]).' value="'.$this->formvars['selframes'][$i]["id"].'">'.$this->formvars['selframes'][$i]["name"].'</option>';
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
													echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['frames'][$i]["name"]).'	value="'.$this->formvars['frames'][$i]["id"].'">'.$this->formvars['frames'][$i]["name"].'</option>';
													 }
											?>
											</select>
										</td>
								</tr>
							</table>
					</td>
				</tr>
			
				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr valign="top">
										<td>
											<select name="selectedlayouts" size="6" multiple style="width: 340px">
											<?
											for($i=0; $i < count_or_0($this->formvars['sellayouts']); $i++){
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
													echo '<option title='.str_replace(' ', '&nbsp;', $this->formvars['layouts'][$i]["name"]).'	value="'.$this->formvars['layouts'][$i]["id"].'">['.$this->formvars['layouts'][$i]["layer_id"].'] '.$this->formvars['layouts'][$i]["name"].'</option>';
													 }
											?>
											</select>
										</td>
								</tr>
							</table>
					</td>
				</tr>

				<tr class="group-zuordnungen">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
									<th class="fetter" align="right">
									<? if(count_or_0($this->formvars['sellayer']["Bezeichnung"]) > 0){?>
										<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strEdit; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?}?>
									<?php echo $strLayer; ?></th>
								</tr>
								<tr>
									<td align="right">&nbsp;</td>
								</tr>
						</table>
					</th>
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr valign="top">
										<td>
											<select name="selectedlayer" size="12" multiple style="position: relative; width: 340px">
											<?
											for($i=0; $i < count_or_0($this->formvars['sellayer']["Bezeichnung"]); $i++){
													echo '<option class="select_option_link" onclick="gotoLayer(event, this)" title='.str_replace([' ', '<br>'], '&nbsp;', $this->formvars['sellayer']["Bezeichnung"][$i]).' id="'.$this->formvars['sellayer']["ID"][$i].'_'.$this->formvars['sellayer']["Gruppe"][$i].'" value="'.$this->formvars['sellayer']["ID"][$i].'">'.$this->formvars['sellayer']["Bezeichnung"][$i].'</option>';
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

				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td>
									<select name="selectedusers" size="6" multiple style="position: relative; width: 340px"><?
										for ($i = 0; $i < count_or_0($this->formvars['selusers']["Bezeichnung"]); $i++) {
											$seluseroptions[] = array(
												'value'	=> $this->formvars['selusers']["ID"][$i],
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
			
				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td><?php
									$options = array_map(
										function($parent) {
											return array(
												'value' => $parent['id'],
												'title' => str_replace(' ', '&nbsp;', $parent["bezeichnung"]),
												'output' => $parent['bezeichnung']
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
												'value' => $parent->get('id'),
												'title' => str_replace(' ', '&nbsp;', $parent->get("bezeichnung")),
												'output' => $parent->get('bezeichnung')
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

				<tr class="group-zuordnungen">
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
					<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td><?php
									$options = array_map(
										function($child) {
											return array(
												'value' => $child['id'],
												'title' => str_replace(' ', '&nbsp;', $child["bezeichnung"]),
												'output' => $child['bezeichnung']
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
												'value' => $child->get('id'),
												'title' => str_replace(' ', '&nbsp;', $child->get("bezeichnung")),
												'output' => $child->get('bezeichnung')
											);
										},
										$this->formvars['children']
									);
									echo FormObject::createSelectField('allchildren', $options, '', 6, 'position: relative; width: 340px', '', '', 'multiple', '', '', '', 'select_option_link', 'gotoStelle(event, this);');?>
								</td>
							</tr>
						</table>
					</td>
				</tr><?
			} ?>

			<tr>
				<th class="group" colspan="3" align="left"><a href="javascript:toggleGroup('metadata', false);"><img id="metadata" src="<? echo GRAPHICSPATH . ($this->formvars['go'] == 'Dienstmetadaten' ? 'minus' : 'plus') . '.gif'; ?>"></a>&nbsp;<? echo $strOwsMetadata; ?></th>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsTitle; ?>*&nbsp;<span data-tooltip="<?php echo $strOwsTitleHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_title" type="text" value="<?php echo $this->formvars['ows_title']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsAbstract; ?>*&nbsp;<span data-tooltip="<?php echo $strOwsAbstractHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
					<textarea name="ows_abstract" rows="2"><?php echo $this->formvars['ows_abstract']; ?></textarea>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsNameSpace; ?>&nbsp;<span data-tooltip="<?php echo $strOwsNameSpaceHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
					<input name="ows_namespace" type="text" value="<?php echo $this->formvars['ows_namespace']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strWmsAccessConstraints; ?>&nbsp;<span data-tooltip="<?php echo $strWmsAccessConstraintsHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="wms_accessconstraints" type="text" value="<?php echo $this->formvars['wms_accessconstraints']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsFees; ?>&nbsp;<span data-tooltip="<?php echo $strOwsFeesHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_fees" type="text" value="<?php echo $this->formvars['ows_fees']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th align="right" style="border-bottom:1px solid #C3C7C3">
					<input name="ows_inspireidentifiziert" type="checkbox" value="1" <?php echo ($this->formvars['ows_inspireidentifiziert']) ? ' checked' : ''; ?>>
				</th>
				<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsInspireidentifiziert; ?>
					<span data-tooltip="<?php echo $strOwsInspireidentifiziertHint; ?>"></span>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsSrs; ?>&nbsp;<span data-tooltip="<?php echo $strOwsSrsHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_srs" type="text" value="<?php echo $this->formvars['ows_srs']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr>
				<th class="group-metadata" colspan="3" align="center"><? echo $strOwsContactMetadata; ?></th>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactOrganization; ?>*
					<span data-tooltip="<?php echo $strOwsContactOrganizationHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactorganization" type="text" value="<?php echo $this->formvars['ows_contactorganization']; ?>" size="50" maxlength="100" <? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactEmailAddress; ?>*
					<span data-tooltip="<?php echo $strOwsContactEmailAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactemailaddress" type="text" value="<?php echo $this->formvars['ows_contactemailaddress']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPerson; ?>
					<span data-tooltip="<?php echo $strOwsContactPersonHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactperson" type="text" value="<?php echo $this->formvars['ows_contactperson']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPosition; ?>
					<span data-tooltip="<?php echo $strOwsContactPositionHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactposition" type="text" value="<?php echo $this->formvars['ows_contactposition']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactVoicephone; ?>
					<span data-tooltip="<?php echo $strOwsContactVoicephoneHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactvoicephone" type="text" value="<?php echo $this->formvars['ows_contactvoicephone']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactFacsimile; ?>
					<span data-tooltip="<?php echo $strOwsContactFacsimileHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactfacsimile" type="text" value="<?php echo $this->formvars['ows_contactfacsimile']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAddress; ?>
					<span data-tooltip="<?php echo $strOwsContactAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactaddress" type="text" value="<?php echo $this->formvars['ows_contactaddress']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPostalcode; ?>
					<span data-tooltip="<?php echo $strOwsContactPostalcodeHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactpostalcode" type="text" value="<?php echo $this->formvars['ows_contactpostalcode']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactCity; ?>
					<span data-tooltip="<?php echo $strOwsContactCityHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactcity" type="text" value="<?php echo $this->formvars['ows_contactcity']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAdministrativeArea; ?>
					<span data-tooltip="<?php echo $strOwsContactAdministrativeAreaHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contactadministrativearea" type="text" value="<?php echo $this->formvars['ows_contactadministrativearea']; ?>" size="50" maxlength="100"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactUrl; ?>
										<span data-tooltip="<?php echo $strOwsContactUrlHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contacturl" type="text" value="<?php echo $this->formvars['ows_contacturl']; ?>"<? echo ($is_admin_stelle ? '' : ' readonly'); ?>>
				</td>
			</tr>

			<tr>
				<th class="group-metadata" colspan="3" align="center"><? echo $strOwsContactContent; ?></th>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactOrganization; ?>*
					<span data-tooltip="<?php echo $strOwsContactOrganizationHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentorganization" type="text" value="<?php echo $this->formvars['ows_contentorganization']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactEmailAddress; ?>*
					<span data-tooltip="<?php echo $strOwsContactEmailAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentemailaddress" type="text" value="<?php echo $this->formvars['ows_contentemailaddress']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsGeographicDescription; ?>&nbsp;<span data-tooltip="<?php echo $strOwsGeographicDescriptionHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_geographicdescription" type="text" value="<?php echo $this->formvars['ows_geographicdescription']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPerson; ?>
					<span data-tooltip="<?php echo $strOwsContactPersonHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentperson" type="text" value="<?php echo $this->formvars['ows_contentperson']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPosition; ?>
					<span data-tooltip="<?php echo $strOwsContactPositionHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentposition" type="text" value="<?php echo $this->formvars['ows_contentposition']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactVoicephone; ?>
					<span data-tooltip="<?php echo $strOwsContactVoicephoneHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentvoicephone" type="text" value="<?php echo $this->formvars['ows_contentvoicephone']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactFacsimile; ?>
					<span data-tooltip="<?php echo $strOwsContactFacsimileHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentfacsimile" type="text" value="<?php echo $this->formvars['ows_contentfacsimile']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAddress; ?>
					<span data-tooltip="<?php echo $strOwsContactAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentaddress" type="text" value="<?php echo $this->formvars['ows_contentaddress']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPostalcode; ?>
					<span data-tooltip="<?php echo $strOwsContactPostalcodeHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentpostalcode" type="text" value="<?php echo $this->formvars['ows_contentpostalcode']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactCity; ?>
					<span data-tooltip="<?php echo $strOwsContactCityHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentcity" type="text" value="<?php echo $this->formvars['ows_contentcity']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAdministrativeArea; ?>
					<span data-tooltip="<?php echo $strOwsContactAdministrativeAreaHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contentadministrativearea" type="text" value="<?php echo $this->formvars['ows_contentadministrativearea']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactUrl; ?>
										<span data-tooltip="<?php echo $strOwsContactUrlHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_contenturl" type="text" value="<?php echo $this->formvars['ows_contenturl']; ?>">
				</td>
			</tr>

			<tr>
				<th class="group-metadata" colspan="3" align="center"><? echo $strOwsContactDistribution; ?></th>
			</tr>
			
			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactOrganization; ?>
					<span data-tooltip="<?php echo $strOwsContactOrganizationHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionorganization" type="text" value="<?php echo $this->formvars['ows_distributionorganization']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactEmailAddress; ?>
					<span data-tooltip="<?php echo $strOwsContactEmailAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionemailaddress" type="text" value="<?php echo $this->formvars['ows_distributionemailaddress']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPerson; ?>
					<span data-tooltip="<?php echo $strOwsContactPersonHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionperson" type="text" value="<?php echo $this->formvars['ows_distributionperson']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPosition; ?>
					<span data-tooltip="<?php echo $strOwsContactPositionHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionposition" type="text" value="<?php echo $this->formvars['ows_distributionposition']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactVoicephone; ?>
					<span data-tooltip="<?php echo $strOwsContactVoicephoneHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionvoicephone" type="text" value="<?php echo $this->formvars['ows_distributionvoicephone']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactFacsimile; ?>
					<span data-tooltip="<?php echo $strOwsContactFacsimileHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionfacsimile" type="text" value="<?php echo $this->formvars['ows_distributionfacsimile']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAddress; ?>
					<span data-tooltip="<?php echo $strOwsContactAddressHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionaddress" type="text" value="<?php echo $this->formvars['ows_distributionaddress']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactPostalcode; ?>
					<span data-tooltip="<?php echo $strOwsContactPostalcodeHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionpostalcode" type="text" value="<?php echo $this->formvars['ows_distributionpostalcode']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactCity; ?>
					<span data-tooltip="<?php echo $strOwsContactCityHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributioncity" type="text" value="<?php echo $this->formvars['ows_distributioncity']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactAdministrativeArea; ?>
					<span data-tooltip="<?php echo $strOwsContactAdministrativeAreaHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionadministrativearea" type="text" value="<?php echo $this->formvars['ows_distributionadministrativearea']; ?>" size="50" maxlength="100">
				</td>
			</tr>

			<tr class="group-metadata">
				<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
					<?php echo $strOwsContactUrl; ?>
										<span data-tooltip="<?php echo $strOwsContactUrlHint; ?>"></span>
				</th>
				<td colspan="2" style="border-bottom:1px solid #C3C7C3">
						<input name="ows_distributionurl" type="text" value="<?php echo $this->formvars['ows_distributionurl']; ?>">
				</td>
			</tr>
			<?
			
			if ($this->formvars['go'] != 'Dienstmetadaten') {?>

				<tr>
					<th class="group" colspan="3" align="left"><a href="javascript:toggleGroup('sonstiges', false);"><img id="sonstiges" src="<? echo GRAPHICSPATH . 'minus.gif'; ?>"></a>&nbsp;Sonstiges</td>
				</tr>

				<tr class="group-sonstiges">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strDefaultUserID; ?>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php
						echo FormObject::createSelectField('default_user_id', $seluseroptions ?: [], $this->formvars['default_user_id'], 1, 'width: auto'); ?>
					</td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input
							name="show_shared_layers"
							type="checkbox"
							value="1"
							<?php echo ($this->formvars['show_shared_layers'] ? ' checked' : ''); ?>
						>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strShowSharedLayersText; ?>
						<span data-tooltip="<?php echo $strShowSharedLayersDescription; ?>"></span>
					</td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input name="checkClientIP" type="checkbox" value="1" <?php if ($this->formvars['checkClientIP']) { ?> checked<?php } ?>>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strcheckClientIP; ?></td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input name="checkPasswordAge" type="checkbox" value="1" <?php if ($this->formvars['checkPasswordAge']) { ?> checked<?php } ?>>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strCheckPasswordAge; ?></td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input name="allowedPasswordAge" type="number" size="1" value="<?php echo $this->formvars['allowedPasswordAge']; ?>">
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strAllowedPasswordAge; ?></td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input name="use_layer_aliases" type="checkbox" value="1" <? if ($this->formvars['use_layer_aliases']) { ?> checked<? } ?>>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strUseLayerAliases; ?></td>
				</tr>

				<tr class="group-sonstiges">
					<th align="right" style="border-bottom:1px solid #C3C7C3">
						<input name="hist_timestamp" type="checkbox" value="1" <?php if ($this->formvars['hist_timestamp']) { ?> checked<?php } ?>>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3"><?php echo $strhist_timestamp; ?></td>
				</tr>

				<tr class="group-sonstiges">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $this->strVersion; ?>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3;">
						<input name="version" type="text" value="<?php echo $this->formvars['version']; ?>" size="10" maxlength="10" style="width: 50px">
					</td>
				</tr>

				<tr class="group-sonstiges">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strResetPasswordText; ?>
					</th>
					<td colspan="2" valign="top" align="left" style="border-bottom:1px solid #C3C7C3">
						<textarea name="reset_password_text" colls="30" rows="10"><? echo $this->formvars['reset_password_text']; ?></textarea>
						<span style="vertical-align: top; margin-left: 3px" data-tooltip="<?php echo $strResetPasswordTextDescription; ?>"></span>
					</td>
				</tr>

				<tr class="group-sonstiges">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $strInvitationText; ?>
					</th>
					<td colspan="2" valign="top" align="left" style="border-bottom:1px solid #C3C7C3">
						<textarea name="invitation_text" colls="30" rows="10"><? echo $this->formvars['invitation_text']; ?></textarea>
						<span style="vertical-align: top; margin-left: 3px" data-tooltip="<?php echo $strInvitationTextDescription; ?>"></span>
					</td>
				</tr>

				<tr class="group-sonstiges">
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<?php echo $this->strComment; ?>
					</th>
					<td colspan="2" align="left" style="border-bottom:1px solid #C3C7C3">
						<textarea name="comment" colls="30" rows="2"><? echo $this->formvars['comment']; ?></textarea>
					</td>
				</tr><?
			} ?>
		</table>
	</td>
	</tr>
	<tr>
		<td align="center">
			<input type="hidden" name="go_plus" id="go_plus" value=""><?
			if ($this->formvars['go'] != 'Dienstmetadaten') { ?>
				<input type="button" onclick="location.href='index.php?go=Stellen_Anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>'" value="<?php echo $this->strButtonBack; ?>">&nbsp;<?
			}
			if ($this->formvars['selected_stelle_id'] > 0) {
				?><input type="button" name="dummy" value="<?php echo $strButtonUpdate; ?>" onclick="submitWithValue('GUI','go_plus','Ändern')"><?php
			}
			if ($this->formvars['go'] != 'Dienstmetadaten') {
				?>&nbsp;<input type="button" name="dummy" value="<?php echo $strButtonInsert; ?>" onclick="submitWithValue('GUI','go_plus','Als neue Stelle eintragen')"><?
			} ?>
		</td>
	</tr>
</table>

<input type="hidden" name="go" value="<? echo ($this->formvars['go'] == 'Dienstmetadaten' ? 'Dienstmetadaten' : 'Stelleneditor'); ?>"><?
if ($this->formvars['go'] != 'Dienstmetadaten') { ?>
	<input type="hidden" name="selmenues" value="<?
					echo $this->formvars['selmenues']["ID"][0];
					for($i=1; $i < count_or_0($this->formvars['selmenues']["Bezeichnung"]); $i++){
						echo ', '.$this->formvars['selmenues']["ID"][$i];
					}
				?>">
	<input type="hidden" name="selfunctions" value="<?
					echo $this->formvars['selfunctions'][0]["id"];
					for($i=1; $i < count_or_0($this->formvars['selfunctions']); $i++){
						echo ', '.$this->formvars['selfunctions'][$i]["id"];
					}
				?>">
	<input type="hidden" name="selframes" value="<?
					echo $this->formvars['selframes'][0]["id"];
					for($i=1; $i < count_or_0($this->formvars['selframes']); $i++){
						echo ', '.$this->formvars['selframes'][$i]["id"];
					}
				?>">			
	<input type="hidden" name="sellayouts" value="<?
					echo $this->formvars['sellayouts'][0]["id"];
					for($i=1; $i < count_or_0($this->formvars['sellayouts']); $i++){
						echo ', '.$this->formvars['sellayouts'][$i]["id"];
					}
				?>">

	<input
		name="sellayer"
		value="<?php echo implode(', ', $this->formvars['sellayer']['ID'] ?: []); ?>"
		type="hidden"
	>

	<input
		name="selusers"
		value="<?php if(!empty($this->formvars['selusers']['ID']))echo implode(', ', $this->formvars['selusers']['ID']); ?>"
		type="hidden"
	>

	<input
		name="selparents"
		value="<?php echo implode(', ', array_map(function($parent) { return $parent['id']; }, $this->formvars['selparents'])); ?>"
		type="hidden"
	>

	<input
		name="selchildren"
		value="<?php echo implode(', ', array_map(function($child) { return $child['id']; }, $this->formvars['selchildren'])); ?>"
		type="hidden"
	><?
} ?>