<?php
include(LAYOUTPATH.'languages/namensuche_'.rolle::$language.'.php');
include(LAYOUTPATH.'languages/generic_search_'.rolle::$language.'.php');
include('funktionen/input_check_functions.php');
  
$anzNamen = count_or_0($this->namen);

# Blätterfunktion
if($this->formvars['offset'] == ''){
	$this->formvars['offset'] = 0;
}
$von = $this->formvars['offset'] + 1;
$bis = $this->formvars['offset'] + $this->formvars['anzahl'];
if($bis > $this->anzNamenGesamt){
	$bis = $this->anzNamenGesamt;
}
  
?>

<script type="text/javascript">
<!--

	function checkall(name){
		var flurstkennz = "";
		var flurstarray = document.getElementsByName(name);
		if(flurstarray[0].checked){
			check = false;
		}
		else{
			check = true;
		}
		for(i = 0; i < flurstarray.length; i++){
			flurstarray[i].checked = check;
		}
	}

	function changeorder(orderby){
		document.GUI.order.value = orderby;
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function nextquery(offset){
		if(offset.value == ''){
			offset.value = 0;
		}
		offset.value = parseInt(offset.value) + parseInt(document.GUI.anzahl.value);
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function prevquery(offset){
		if(parseInt(offset.value) < parseInt(document.GUI.anzahl.value)){
			offset.value = 0;
		}
		else{
			offset.value = parseInt(offset.value) - parseInt(document.GUI.anzahl.value);
		}
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function save(){
		gdatum = document.getElementsByName('name4')[0].value;
		if(gdatum != '' && !checkDate(gdatum)){
  		alert('Das Geburtsdatum hat nicht das Format TT.MM.JJJJ.');
  		return;
  	}
		if (document.GUI.map_flag.value == 1) {
			if (document.GUI.newpathwkt.value == '') {
				if (document.GUI.newpath.value != '') {
					document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
				}
			}
		}
		document.GUI.offset.value = 0;
		document.GUI.go.value = 'Namen_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function send_selected_flurst(go, i, formnummer, wz, target){
		currentform.go_backup.value=currentform.go.value;
		var semi = false;
		var flurstkennz = "";
		var flurstarray = document.getElementsByName("check_flurstueck_"+i);
		for(i = 0; i < flurstarray.length; i++){
			if(flurstarray[i].checked == true){
				if(semi == true){
					flurstkennz += ';';
				}
				flurstkennz += flurstarray[i].value;
				semi = true;
			}
		}
		if(semi == true){
			currentform.target = '';
			if(target == '_blank'){
				currentform.target = '_blank';
			}
			currentform.go.value=go;
			currentform.FlurstKennz.value=flurstkennz;
			currentform.formnummer.value=formnummer;
			currentform.wz.value=wz;
			currentform.submit();
		}
		else{
			alert('Es wurden keine Flurstücke ausgewählt.');
		}
	}

	function send_selected_grundbuecher(go){
		var semi = false;
		var grundbuecher = "";
		var gbarray = document.getElementsByName("check_grundbuch");
		for(i = 0; i < gbarray.length; i++){
	  	if(gbarray[i].checked == true){
	  		if(semi == true){
	    		grundbuecher += ', ';
	    	}
	    	grundbuecher += gbarray[i].value;
	    	semi = true;
	    }
	  }
	  if(semi == true){
		  currentform.selBlatt.value = grundbuecher;
			currentform.go.value = go;
		 	currentform.submit();
		}
		else{
			alert('Es wurden keine Grundbuchblätter ausgewählt.');
		}
	}

	function grundbuchsuche(bezirk, blatt){
		document.GUI.selBlatt.value = bezirk+'-'+blatt;
		document.GUI.go.value = 'Grundbuchblatt_Auswaehlen_Suchen';
		document.GUI.submit();
	}

	function flurstsuche(bezirk, blatt){
		document.GUI.selBlatt.value = bezirk+'-'+blatt;
		document.GUI.go.value = 'Suche_Flurstuecke_zu_Grundbuechern';
		document.GUI.submit();
	}
	
	function flurstanzeige(flurstkennz){
		document.GUI.FlurstKennz.value = flurstkennz;
		document.GUI.go.value = 'Flurstueck_Anzeigen';
		document.GUI.submit();
	}	

	function toggle_visibility(id) {
		var e = document.getElementById(id);
		if(e.style.display == 'block')
			e.style.display = 'none';
		else
			e.style.display = 'block';
	}

	function showmap(){
		if(document.GUI.map_flag.value == 0){
			document.GUI.map_flag.value = 1;
		}
		else{
			document.GUI.map_flag.value = '';
		}
		document.GUI.submit();
	}
	
//-->
</script>

<style>
body {
  overflow-y:scroll;
}
#nsf_formular_oeffnen_wrap {
	position: absolute;
	top: 10px;
	right: 10px;
}
#nsf_formular_oeffnen {
	background: #345;
	width: 40px;
	height: 40px;
	border-radius: 0 0 0 40px;
}
#nsf_formular_oeffnen_label {
	color: white;
	cursor: pointer;
	width: 36px;
	height: 32px;
	border-radius: 0 0 0 40px;
	display: inline-block;
	padding-top: 8px;
	padding-left: 6px;
	font-size: 200%;
}
#nsf_formular_oeffnen_sign {
	display: none;
}
#nsf_formular_oeffnen_sign:checked + #nsf_formular_oeffnen_label:after {
	content: "-";
	position: absolute;
	top: 6px;
	left: 9px;
	right: 0px;
	bottom: 0px;
	width: 30px;
	height: 30px;
	color: white;
	background: #345;
	border-radius: 0 0 0 30px;
}
#form_formular-main {
	display: <? if ($anzNamen>0) { echo 'none'; } else { echo 'block'; } ?>;
}
#nsf_person, #nsf_gebiet, #nsf_platzhalterhinweis, #nsf_treffer {
	width: 100%;
}
#nsf_platzhalterhinweis {
	margin: 20px 0px;
	font-style: italic;
	font-size: 0.8em;
	width: 90%;
}
#nsf_person input[type="text"], #nsf_gebiet input[type="text"], #nsf_gebiet select, #nsf_parameter input[type="text"], #nsf_flurstuecke select {
	border-radius: 2px;
	border: 1px solid #777;
	height: 25px;
	padding-left: 5px;
}
#nsf_person input[type="text"] {
	width: 200px;
}
#nsf_gebiet input[type="text"] {
	width: 60px;
}
.nsf_titel {
	margin: 20px 10px 20px 10px;
	padding-left: 5px;
	font-family: SourceSansPro2;
}
.nsf_name {
	display: flex;
}
.nsf_suche {
	padding-left: 20px;
}
.form_formular-input {
	width:100%;
	height: 28px;
	margin: 0px 0px 10px 0px;
	display: flex;
}
.nsf_name>div:last-child {
	margin-left: 20px;
}
.nsf_name>div:last-child div {
	position: relative;
	top: 21px;
	display: flex;
}
.nsf_name>div:last-child div span[data-tooltip], #nsf_parameter span[data-tooltip] {
	margin-left: 10px;
}
.nsf_name>div:last-child div span[data-tooltip]:before, #nsf_parameter span[data-tooltip]:before {
	top: 2px;
	position: relative;
}
.nsf_name>div:last-child span {
	margin-left: 10px;
}
.nsf_suche_child_cal div:last-child {
	position: relative;
	margin-left: 5px;
	display: flex;
}
#nsf_parameter {
	margin: 30px 0px 0px 0px;
	display: flex;
	flex-flow: row nowrap;
	justify-content: center;
}
#nsf_parameter div {
	display: flex;
}
.nsf_suche_child_cal div:last-child img {
	align-self: center;
	margin: auto;
}
#nsf_parameter div:first-child div:last-child {
	margin-left: 10px;
	margin-right: 15px;
}
#nsf_parameter div:last-child div:first-child {
	margin-left: 15px;
	margin-right: 10px;
}
#nsf_suchen {
	margin: 20px 0px 10px 0px;
}
#nsf_treffer .nsf_titel {
	text-align: center;
	font-weight: bold;
}
#nsf_treffer_anzeige {
	margin: 20px 10px 0px 10px;
}
#nsf_treffer_anzeige table {
	width: 100%;
	background: #fff;
}
.nsf_treffer_anzeige_header {
	height: 40px;
}
.nsf_treffer_anzeige_header td:first-child:hover, .nsf_treffer_anzeige_header td:last-child:hover {
	background: linear-gradient(#ECF1F5 0%, #dee9f0 100%);
}
.nsf_treffer_anzeige_header div, .nsf_treffer_anzeige_header span {
	margin: 0px 5px;
	padding: 0px;
	cursor: pointer;
}
.nsf_treffer_anzeige_header span {
	cursor: default;
}
.nsf_treffer_anzeige_daten {
	height: 25px;
}
.nsf_treffer_anzeige_daten td {
	background: #fbfbfb;
	padding: 0px 5px;
}
#nsf_grundbuch {
	margin: 20px 10px;
	text-align: left;
}
#nsf_offset {
	margin-bottom: 50px;
	display: flex;
	justify-content: center;
}
#nsf_offset div:not(nth-child(2)) {
	width: 60px;
}
#nsf_offset div:nth-child(2) {
	margin: 0px 20px;
	min-width: 120px;
}
.nsf_button_small {
	width: 32px;
	height: 32px;
	transform: scale(0.8,0.8);
}
#nsf_flurstuecke, #nsf_flurstuecke td, #nsf_flurstuecke select {
	font-size: 13px;
}
#nsf_flurstuecke select {
	width: 130px;
}
.nsf_sort {
	position: absolute;
	bottom: 2px;
	right: -4px;
	width: 0px;
	height: 0px;
	-webkit-transform:rotate(360deg);
	border-style: solid;
	border-width: 4px 5px 0 5px;
	border-color: rgb(200, 50, 50) transparent transparent transparent;
}

.zoom_flurstuecksdaten {
	background-image: url(graphics/zoom_flurstuecksdaten.png);
}

#nsf_suche_raeumlich {
	margin-bottom: 10px;
	cursor: pointer;
}
.gsl_suche_raeumlich_x {
	position: absolute;
	margin-left: 5px;
	margin-top: -2px;
	padding: 0;
	display: inline-block;
	width: 17px;
	height: 17px;
	font-size: 17px;
	font-weight: bold;
	background: #fff;
	color: #f21e28;
	border: 2px solid #555;
	border-radius: 50%;
}

.gsl_suche_raeumlich_map {
	margin: 30px 10px 10px 10px;
	padding: 20px;
	border:  1px solid #CCC;
	box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
}
#gsl_suche_raeumlich_params {
	display: flex;
	flex-direction:row;
	align-items: center;
	height: 40px;
}
.gsl_suche_raeumlich_param {
	display: flex;
	flex-direction:row;
	align-items: center;
	gap: 0 5px;
}
#gsl_suche_raeumlich_params>div:not(:last-child) {
	margin-right: 10px;
}
#gsl_suche_raeumlich_params>div:last-child {
	position: absolute;
	right: 30px;
}
.gsl_suche_raeumlich_param input[type="checkbox"] {
	margin: auto;
}

</style>

<div id="form-titel"><?php echo $strTitle; ?></div>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?><p>

<? if($this->formvars['gml_id'] == ''){ ?>

<?
if ($anzNamen>0) {
?>
<div id="nsf_formular_oeffnen_wrap">
	<div id="nsf_formular_oeffnen">
		<input id="nsf_formular_oeffnen_sign" type="checkbox" onclick="toggle_visibility('form_formular-main');" /><label for="nsf_formular_oeffnen_sign" id="nsf_formular_oeffnen_label">+</label>
	</div>
</div>
<? } ?>

<div id="form_formular-main">

	<div id="nsf_platzhalterhinweis">
		<?php echo $strHintWildcard; ?>.
	</div>
	<div id="nsf_person">
		<div class="nsf_titel menu"><?php echo $strPerson; ?></div>
		<div class="nsf_suche">
			<div class="nsf_name">
				<div>
					<div class="form_formular-input form_formular-aic">
						<div><?php echo $strName1; ?></div>
						<div class="float_right"><input name="name1" type="text" value="<? echo htmlentities($this->formvars['name1'], NULL, 'UTF-8'); ?>" autofocus tabindex="1"></div>
					</div>
					<div class="form_formular-input form_formular-aic">
						<div><?php echo $strName2; ?></div>
						<div class="float_right"><input name="name2" type="text" value="<?php echo $this->formvars['name2']; ?>" tabindex="2"></div>
					</div>
				</div>
				<div>
					<div class="form_formular-aic">
						<input style="cursor: pointer;" type="checkbox" name="exakt" value="1" tabindex="3" <? if($this->formvars['exakt']) echo 'checked'; ?>>
						<span><?php echo $strExactSearch; ?></span>
						<span data-tooltip="Name/Firma und Vorname:
Suche genau nach der Eingabe"></span>
					</div>
					<br>
					<div class="form_formular-aic">
						<input style="cursor: pointer;" type="checkbox" name="alleiniger_eigentuemer" value="1" tabindex="3" <? if($this->formvars['alleiniger_eigentuemer']) echo 'checked'; ?>>
						<span>alleiniger Eigentümer</span>
						<span data-tooltip="Die Person ist der einzige Eigentümer des Flurstücks."></span>
					</div>
				</div>				
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName3; ?></div>
				<div><input name="name9" type="text" value="<?php echo $this->formvars['name9']; ?>" tabindex="4"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName4; ?></div>
				<div><input name="name10" type="text" value="<?php echo $this->formvars['name10']; ?>" tabindex="5"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName5; ?></div>
				<div><input name="name3" type="text" value="<?php echo $this->formvars['name3']; ?>" tabindex="6"></div>
			</div>
			<div class="form_formular-input form_formular-aic nsf_suche_child_cal">
				<div><?php echo $strName6; ?></div>
				<div><input name="name4" type="text" value="<?php echo $this->formvars['name4']; ?>" tabindex="7"></div>
				<div><img title="TT.MM.JJJJ" src="<? echo GRAPHICSPATH; ?>calendarsheet.png" border="0"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName7; ?></div>
				<div><input name="name5" type="text" value="<?php echo $this->formvars['name5']; ?>" tabindex="8"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName8; ?></div>
				<div><input name="name6" type="text" value="<?php echo $this->formvars['name6']; ?>" tabindex="9"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName9; ?></div>
				<div><input name="name7" type="text" value="<?php echo $this->formvars['name7']; ?>" tabindex="10"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strName10; ?></div>
				<div><input name="name8" type="text" value="<?php echo $this->formvars['name8']; ?>" tabindex="11"></div>
			</div>			
		</div>
	</div>
	<div id="nsf_gebiet">
		<div class="nsf_titel menu"><?php echo $strGebiet; ?></div>
		<div class="nsf_suche">

				<div id="nsf_suche_raeumlich">
					<div>
						<a onclick="showmap();">
							<? echo $strSpatialFiltering; ?>
							<? 
							if(value_of($this->formvars, 'map_flag') != '') { 
							?>
								<span class="gsl_suche_raeumlich_x" title="<? echo $strSpatialFilteringClose; ?>">×</span>
							<?php					
							}
							?>
						</a>
					</div>
				</div>

		<?
			if(value_of($this->formvars, 'map_flag') != '') {
		?> 
			<div class="gsl_suche_raeumlich_map generic_search_defaults">
				<div id="gsl_suche_raeumlich_params">
					<div class="gsl_suche_raeumlich_param">
						<div><input type="checkbox" name="within" value="1" <?php if($this->formvars['within'] == 1)echo 'checked'; ?>></div>
						<div><?php echo $strWithin; ?></div>
					</div>
					<div class="gsl_suche_raeumlich_param">
						<div><input type="checkbox" name="singlegeom" value="true" <?php if($this->formvars['singlegeom'])echo 'checked="true"'; ?>></div>
						<div><?php echo $strSingleGeoms; ?></div>
					</div>
					<div class="gsl_suche_raeumlich_param">
						<div><?php echo $this->strUseGeometryOf; ?>:</div> 
						<div>
							<select name="geom_from_layer" onchange="geom_from_layer_change();">		<?
							for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
								echo '<option';
								if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
								echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
							}	?>
							</select>
						</div>
					</div>
				</div>
				<?php include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php') ?>		
			</div>
		<?php
			}

			?>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strGbbez; ?></div>
				<div><input name="bezirk" type="text" value="<?php echo $this->formvars['bezirk']; ?>" tabindex="12"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strGbbl; ?></div>
				<div><input name="blatt" type="text" value="<?php echo $this->formvars['blatt']; ?>" tabindex="13"></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strGemkg; ?></div>
				<div tabindex="14"><?php echo $this->GemkgFormObj->html; ?></div>
			</div>
			<div class="form_formular-input form_formular-aic">
				<div><?php echo $strFlur; ?></div>
				<div tabindex="15"><?php echo $this->FlurFormObj->html; ?></div>
			</div>
		</div>
	</div>

</div>

<div id="nsf_parameter">
	<div class="form_formular-aic">
		<div>
			<input name="withflurst" type="checkbox" <? if($this->formvars['withflurst'] == 'on'){echo 'checked';} ?> tabindex="16">
		</div>		
		<div>
			<span><?php echo $strShowWithFst; ?></span>
			<span data-tooltip="Zeige auch die zum Grundbuchblatt gehörenden Flurstücke an"></span>
		</div>
	</div>
	<div class="form_formular-aic">
		<div><input name="anzahl" size="2" onkeyup="checknumbers(this, 'int2', '', '');" type="text" value="<?php echo $this->formvars['anzahl']; ?>" tabindex="17"></div>
		<div><?php echo $strShowHits; ?></div>
	</div>
</div>
<div id="nsf_suchen">
	<input type="button" name="go_plus" onclick="save();" value="<?php echo $strSearch; ?>" tabindex="18">
</div>


	
<? }
		else{ ?>
			<input name="anzahl" size="2" onkeyup="checknumbers(this, 'int2', '', '');" type="text" value="<?php echo $this->formvars['anzahl']; ?>" tabindex="17">
			<?php echo $strShowHits; ?>
<? }
if ($anzNamen>0) {
?>
<div id="nsf_treffer">
	<div class="nsf_titel menu"><?php echo $strTotalHits; ?>: <?php echo $this->anzNamenGesamt; ?></div>
	<div id="nsf_treffer_anzeige">
		<table>
			<tr class="nsf_treffer_anzeige_header">
				<td class="menu"><span></span></td>
				<td class="menu"><a href="javascript:changeorder('bezirk');" title="nach <?php echo $strGbbezShort; ?> sortieren"><div><?php echo $strGbbezShort; ?></div><? if($this->formvars['order'] == 'bezirk')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu"><a href="javascript:changeorder('blatt');" title="nach <?php echo $strGbblShort; ?> sortieren"><div><?php echo $strGbblShort; ?></div><? if($this->formvars['order'] == 'blatt')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu"><a href="javascript:changeorder('nachnameoderfirma, vorname');" title="nach <?php echo $strName1Short; ?> sortieren"><div><?php echo $strName1Short; ?></div><? if($this->formvars['order'] == 'nachnameoderfirma, vorname')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu"><a href="javascript:changeorder('geburtsdatum');" title="nach <?php echo $strName2Short; ?> sortieren"><div><?php echo $strName2Short; ?></div><? if($this->formvars['order'] == 'geburtsname')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu"><a href="javascript:changeorder('strasse,hausnummer');" title="nach <?php echo $strName3Short; ?> sortieren"><div><?php echo $strName3Short; ?></div><? if($this->formvars['order'] == 'strasse,hausnummer')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu"><a href="javascript:changeorder('postleitzahlpostzustellung, ort_post');" title="nach <?php echo $strName4Short; ?> sortieren"><div><?php echo $strName4Short; ?></div><? if($this->formvars['order'] == 'postleitzahlpostzustellung, ort_post')echo '<div class="nsf_sort"></div>'; ?></a></td>
				<td class="menu" colspan="2"><span><?php echo $strFst; ?></span></td>
			</tr>
<?php
	for ($i=0;$i<count($this->namen);$i++) {

		$this->namen[$i]['name1'] = str_replace(',,,', '', $this->namen[$i]['name1']);
		$this->namen[$i]['name1'] = str_replace(',,', ',', $this->namen[$i]['name1']);
		if(substr($this->namen[$i]['name1'], strlen($this->namen[$i]['name1'])-1) == ',') {
			$this->namen[$i]['name1'] = substr($this->namen[$i]['name1'], 0, strlen($this->namen[$i]['name1'])-1);
		}

  ?>
			<tr class="nsf_treffer_anzeige_daten">
				<td><input type="checkbox" name="check_grundbuch" value="<? echo $this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt']; ?>"></td>
				<td><?php echo $this->namen[$i]['bezirk']; ?></td>
				<td><a href="javascript:grundbuchsuche(<?php echo '\''.$this->namen[$i]['bezirk'].'\',\''.$this->namen[$i]['blatt'].'\''; ?>);" title="Grundbuchblatt"><?php echo $this->namen[$i]['blatt']; ?></a></td>
				<td><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name1']); if ($this->namen[$i]['name1']=='') { ?>&nbsp;<?php } ?></td>
				<td><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name2']); if ($this->namen[$i]['name2']=='') { ?>&nbsp;<?php } ?></td>
				<td><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name3']); if ($this->namen[$i]['name3']=='') { ?>&nbsp;<?php } ?></td>
				<td><?php echo str_replace(' ','&nbsp;',$this->namen[$i]['name4']); if ($this->namen[$i]['name4']=='') { ?>&nbsp;<?php } ?></td>
				<td>
					<div style="display: flex">
						<a href="javascript:flurstsuche('<?php echo $this->namen[$i]['bezirk'].'\',\''.$this->namen[$i]['blatt']; ?>');" title="<?php echo $strShowFst; ?>"><div class="button zoom_flurstuecksdaten"><img src="graphics/leer.gif"></div></a>
						<a href="index.php?go=Zeige_Flurstuecke_zu_Grundbuechern&selBlatt=<?php echo $this->namen[$i]['bezirk'].'-'.$this->namen[$i]['blatt'];?>" title="<?php echo $strToMap; ?>"><div class="button zoom_highlight"><img src="graphics/leer.gif"></div></a>
					</div>
				</td>
			</tr>
<?		if($this->formvars['withflurst'] == 'on'){ ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="6">

					<table id="nsf_flurstuecke">
						<tr>
								<td></td>
								<td><span class="fett"><?php echo $strParcelNo; ?></span></td>
								<td><span class="fett"><?php echo $strGemkgName; ?></span></td>
								<td><span class="fett"><?php echo $strAreaALB; ?></span></td>
								<td><span class="fett"><?php echo $strDoPrintoutsALB; ?></span></td>
								<td></td>
						</tr>
<?			for($j = 0; $j < count($this->namen[$i]['flurstuecke']); $j++){ ?>
						<tr>
								<td width="36px">
								<? if(count($this->namen[$i]['flurstuecke']) > 1){ ?>
									<input type="checkbox" name="check_flurstueck_<? echo $i; ?>" value="<? echo $this->namen[$i]['flurstuecke'][$j]; ?>">
								<? } ?>
								</td>
								<td><? echo formatFlurstkennzALK($this->namen[$i]['flurstuecke'][$j]); ?></td>
								<td><? echo $this->namen[$i]['alb_data'][$j]['gemkgname']; ?></td>
								<td><? echo $this->namen[$i]['alb_data'][$j]['flaeche']; ?> m²</td>
								<td>
								<? $this->getFunktionen(); ?>
									<select>
										<option>-- Auswahl --</option>
										<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0510&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurstücksnachweis</option><? } ?>
										<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0550&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurstücks- und Eigentumsnachweis</option><? } ?>
										<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0520&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
										<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALKIS_Auszug&formnummer=MV0560&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>
	
										<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALB_Anzeige&formnummer=30&wz=1&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurst&uuml;cksdaten</option><? } ?>
										<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALB_Anzeige&formnummer=35&wz=1&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
										<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onclick="window.open('index.php?go=ALB_Anzeige&formnummer=40&wz=1&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>','_blank')">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
									</select>
								</td>
								<td>
									<div style="display: flex">
										<a href="javascript:flurstanzeige('<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>');" title="<?php echo $strShowFst; ?>"><div class="button zoom_flurstuecksdaten nsf_button_small"><img src="graphics/leer.gif"></div></a>
										<a href="index.php?go=ZoomToFlst&FlurstKennz=<?php echo $this->namen[$i]['flurstuecke'][$j]; ?>" title="<?php echo $strToMap; ?>"><div class="button zoom_highlight nsf_button_small"><img src="graphics/leer.gif"></div></a>
									</div>
								</td>
						</tr>
<?			}
	    	if(count($this->namen[$i]['flurstuecke']) > 1 AND $this->Stelle->funktionen['ohneWasserzeichen']['erlaubt']){ ?>
						<tr>
								<td colspan="6">&nbsp;&nbsp;<? echo '<a href="javascript:checkall(\'check_flurstueck_'.$i.'\');" title="alle auswählen"><img src="'.GRAPHICSPATH.'pfeil_unten-rechts.gif" width="10" height="20" border="0"></a>'; ?>&nbsp;<?php echo $strSelFst; ?>:
										<select>
											<option>-- Auswahl --</option>
											<? if($this->Stelle->funktionen['MV0510']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', '<? echo $i; ?>', 'MV0510', 1, '_blank');">Flurstücksnachweis</option><? } ?>
											<? if($this->Stelle->funktionen['MV0550']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', '<? echo $i; ?>', 'MV0550', 1, '_blank');">Flurstücks- und Eigentumsnachweis</option><? } ?>
											<? if($this->Stelle->funktionen['MV0520']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', '<? echo $i; ?>', 'MV0520', 1, '_blank');">Flurstücksnachweis mit Bodenschätzung</option><? } ?>
											<? if($this->Stelle->funktionen['MV0560']['erlaubt']){ ?><option onclick="send_selected_flurst('ALKIS_Auszug', '<? echo $i; ?>' 'MV0560', 1, '_blank');">Flurstücks- und Eigentumsnachweis mit Bodenschätzung</option><? } ?>
			
											<? if($this->Stelle->funktionen['ALB-Auszug 30']['erlaubt']){ ?><option onclick="send_selected_flurst('ALB_Anzeige', '<? echo $i; ?>', '30', 1, '_blank');">Flurst&uuml;cksdaten</option><? } ?>
											<? if($this->Stelle->funktionen['ALB-Auszug 35']['erlaubt']){ ?><option onclick="send_selected_flurst('ALB_Anzeige', '<? echo $i; ?>', '35', 1, '_blank');">Flurst&uuml;cksdaten&nbsp;mit&nbsp;Eigent&uuml;mer</option><? } ?>
											<? if($this->Stelle->funktionen['ALB-Auszug 40']['erlaubt']){ ?><option onclick="send_selected_flurst('ALB_Anzeige', '<? echo $i; ?>', '40', 1, '_blank');">Eigent&uuml;merdaten&nbsp;zum&nbsp;Flurst&uuml;ck</option><? } ?>
										</select>
										<br>
								</td>
						</tr>
<?			} ?>
						<tr><td><br></td></tr>
					</table>
				</td>
				<td>&nbsp;</td>
			</tr>
<?		}
	}
?>
		</table>
	</div>
</div>
<div id="nsf_grundbuch">
	<div><? echo '<a href="javascript:checkall(\'check_grundbuch\');">'.$strCheckAll.' (' . count($this->namen) . ')</a>'; ?></div>
	<div><? echo '<a href="javascript:checkall(\'check_grundbuch\');"><img src="'.GRAPHICSPATH.'pfeil_unten-rechts.gif" width="10" height="20" border="0"></a>'; ?>&nbsp;<?php echo $strSelGbbl; ?>: <a href="javascript:send_selected_grundbuecher('Grundbuchblatt_Auswaehlen_Suchen');">anzeigen</a>&nbsp;|&nbsp;<a href="javascript:send_selected_grundbuecher('Suche_Flurstuecke_zu_Grundbuechern');"><?php echo $strShowFst; ?></a>&nbsp;|&nbsp;<a href="javascript:send_selected_grundbuecher('Zeige_Flurstuecke_zu_Grundbuechern');"><?php echo $strShowFstInMap; ?></a></div>
</div>
<div id="nsf_offset">

	<div>
<?
if($this->formvars['offset'] > 0){
?>
		<a href="javascript:prevquery(document.GUI.offset);"><img src="graphics/go-previous.png" class="hover-border" style="vertical-align:middle" title="<? echo $strBack; ?>"></a>
<?	} ?>
	</div>
	<div>
		<? echo $von.' - '.$bis.' von '.$this->anzNamenGesamt; ?>
	</div>	
	<div>
<?
	if($bis < $this->anzNamenGesamt){
?>
		<a href="javascript:nextquery(document.GUI.offset);"><img src="graphics/go-next.png" class="hover-border" style="vertical-align:middle" title="<? echo $strNext; ?>"></a>
<?	} ?>
	</div>
</div>

<?php
}
?>

<? if($this->formvars['gml_id'] != ''){ ?>
		<a href="index.php?go=get_last_query&csrf_token=<? echo $_SESSION['csrf_token']; ?>">zurück</a>
		<input name="withflurst" type="hidden" value="<?php echo $this->formvars['withflurst']; ?>">
<? } ?>
<input type="submit" onclick="save();" style="width: 0px; height: 0px; border: none">
<input type="hidden" name="go" value="<? echo $this->formvars['go']; ?>">
<input name="gml_id" type="hidden" value="<? echo $this->formvars['gml_id']; ?>">
<input type="hidden" name="go_backup" value="">
<input name="namensuche" type="hidden" value="true">
<input name="selBlatt" type="hidden" value="">
<input name="Grundbuecher" type="hidden" value="">
<input name="lfd_nr_name" type="hidden" value="">
<input name="offset" type="hidden" value="<? echo $this->formvars['offset']; ?>">
<input type="hidden" name="order" value="<? echo $this->formvars['order'] ?>">
<input type="hidden" name="FlurstKennz" value="">
<input type="hidden" name="formnummer" value="">
<input type="hidden" name="wz" value="">
<input type="hidden" name="map_flag" value="<? echo value_of($this->formvars, 'map_flag'); ?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">
<input type="hidden" name="area" value="">

