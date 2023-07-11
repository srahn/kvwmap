<script type="text/javascript">
<!--

var nachweise = new Array();

<?
	for($i = 0; $i < @count($this->nachweis->Dokumente); $i++){
		$json = str_replace('\\"', '\\\"', str_replace('\\\"', '"', str_replace("'", "\'", str_replace('\\r', '\\\r', str_replace('\\n', '\\\n', str_replace('\\t', '\\\t', json_encode($this->nachweis->Dokumente[$i])))))));
		echo "nachweise.push(JSON.parse('".$json."'));\n";
	}
?>

function save_selection(){
	var formdata = new FormData(currentform);
	formdata.append('go', 'Nachweisanzeige_auswahl_speichern');
	ahah("index.php", formdata, new Array(''), new Array(''));
}

function update_selection(selection){
	var condition;
	var checked = true;
	var selections = document.getElementsByName('markhauptart[]');
	switch(selection.value){
		case '111': {			// alle
			condition = 'true';
			[].forEach.call(selections, function (s){s.checked = true;});
			selections[0].checked = false;
			selections[1].checked = false;
			selections[2].checked = false;
		}break;
		case '000': {			// keine
			condition = 'true';
			checked = false;
			clear_selections('markhauptart[]', '000');
		}break;
		case '222': {			// alle der Messung
			condition = create_condition();
			clear_selections('markhauptart[]', '222');
		}break;
		default: {				// nach Dokumentart
			checked = selection.checked;
			condition = 'selection.value == nachweis.hauptart';
			selections[0].checked = false;
			selections[1].checked = false;
			selections[2].checked = false;
		}
	}
	[].forEach.call(nachweise, function (nachweis){
		if(eval(condition))document.getElementById('id_'+nachweis.id).checked = checked;
  });
	save_selection();
}

function clear_selections(name, except){		// alle Haken rausnehmen außer einem
	var selections = document.getElementsByName(name);
	[].forEach.call(selections, 
		function (s){
			if(s.value != except)s.checked = false;
		}
	);
}

function set_selections(name, except){			// alle Haken setzen außer einem Array von Ausnahmen
	var selections = document.getElementsByName(name);
	[].forEach.call(selections, 
		function (s){
			if(except.indexOf(s.value) < 0){
					s.checked = true;
			}
			else{
					s.checked = false;
			}
		}
	);
	save_selection();
}

function create_condition(){		// fuer alle der Messung
	var condition;
	var conditions = new Array();
	[].forEach.call(nachweise, function (nachweis){
		if(document.getElementById('id_'+nachweis.id).checked){
			condition = "(nachweis.flurid == '"+nachweis.flurid+"' && nachweis.<? echo NACHWEIS_PRIMARY_ATTRIBUTE; ?> == '"+nachweis.<? echo NACHWEIS_PRIMARY_ATTRIBUTE; ?>+"'";
			<? if(NACHWEIS_SECONDARY_ATTRIBUTE != ''){ ?>
			condition += " && nachweis.<? echo NACHWEIS_SECONDARY_ATTRIBUTE; ?> == '"+nachweis.<? echo NACHWEIS_SECONDARY_ATTRIBUTE; ?>+"'";
			<? } ?>
			condition += ')';
			conditions.push(condition);
		}
  });
	return conditions.join(' || ');
}

function zum_Auftrag_hinzufuegen(){
	currentform.go_plus.value='zum_Auftrag_hinzufuegen';
	overlay_submit(currentform, false);
}

function aus_Auftrag_entfernen(){
	if (window.confirm("Möchten sie wirklich Dokumente von der Antragsnummer: " + currentform.suchantrnr.value + " entfernen!?")){
		currentform.go_plus.value='aus_Auftrag_entfernen';
		overlay_submit(currentform, false);
	}
}

function vorlage(){
	var ids = document.getElementsByName('id[]');
	var count = 0;
	for(i = 0; i < ids.length; i++){
		if(ids[i].checked){
			count++;
		}
	}
	if(count == 0)message([{ 'type': 'warning', 'msg': 'Bitte wählen Sie den Nachweis aus, der als Vorlage verwendet werden soll.' }]);
	else{
		currentform.go.value='Nachweisformular_Vorlage';
		overlay_submit(currentform, false, 'root');
	}
}

function set_ref_geom(){
	if(document.getElementById('updateGeomLink').style.display != ''){
		document.getElementById('updateGeomLink').style.display = '';
		update_selection(document.getElementsByName('markhauptart[]')[2]);
	}
}

function updategeoms(){
	var ids = document.getElementsByName('id[]');
	var count = 0;
	for(i = 0; i < ids.length; i++){
		if(ids[i].checked){
			count++;
		}
	}
	if(count == 0)message([{ 'type': 'warning', 'msg': 'Bitte wählen Sie die Nachweise aus, deren Geometrie überschrieben werden soll.' }]);
	else{
		if (window.confirm("Wollen Sie wirklich "+count+" Nachweisgeometrien überschreiben?")) {
			currentform.go.value='Nachweisanzeige_Geometrieuebernahme';
			overlay_submit(currentform, false);
		}
	}
}

function bearbeiten(){
	selected_ids = new Array();
	ids = document.getElementsByName('id[]');
	for(i = 0; i < ids.length; i++){
		if(ids[i].checked)selected_ids.push(ids[i].value);
	}
	currentform.go.value='Layer-Suche_Suchen';
	currentform.value_id.value = selected_ids.join('|');
	overlay_submit(currentform, true);
}

function loeschen(id){
	if (id != null) {
		if (window.confirm("Möchten Sie den Nachweis wirklich löschen?")) {
			currentform.go.value='Nachweisloeschen';
			overlay_link('go=Nachweisloeschen&id=' + id, false);
		}
	}
	else {
		if (window.confirm("Möchten Sie die Nachweise wirklich löschen?")) {
			currentform.go.value='Nachweisloeschen';
			overlay_submit(currentform, false);
		}
	}
}

function add_to_order(order){
	if(currentform.order.value != '')currentform.order.value = currentform.order.value + ',';
	currentform.order.value = currentform.order.value + order;
	overlay_submit(currentform, false);
}

function remove_from_order(order){
	var before = currentform.order.value;
	before = before.replace(order+',', '');
	before = before.replace(','+order, '');
	var after = before.replace(order, '');
	currentform.order.value = after;
	overlay_submit(currentform, false);
}

function set_richtung(richtung){
	currentform.richtung.value = richtung;
	overlay_submit(currentform, false);
}

function set_magnifier(evt, magnifier){
	mousex = evt.clientX - document.getElementById('vorschau_nwv').offsetLeft;
	mousey = evt.clientY - document.getElementById('vorschau_nwv').offsetTop;
	width = magnifier.offsetWidth;
	height = magnifier.offsetHeight;
	magnifier.style.left = mousex - (width/2);
	magnifier.style.top = mousey - (height/2);
	img = magnifier.firstElementChild;
	img.style.transform = 'translate('+(-1*((mousex*3)-(width/2)))+'px, '+(-1*((mousey*3)-(height/2)))+'px)';
}

function getvorschau(url){
	img = '\
		<div onmousemove="set_magnifier(event, this.nextElementSibling);">\
			<img style="width: 600px; border: 1px solid black" src="'+url+'">\
		</div>\
		<div class="magnifier" style="left: -500px; top: -500px;">\
			<img style="width: 1800px; transform: translate(-300px, -300px);" src="'+url+'">\
		</div>\
	';
	document.getElementById('vorschau_nwv').innerHTML = img;
}

function getGeomPreview(id){
	img = '<img id="preview_img_nwv" style="border: 1px solid black" src="">';
	document.getElementById('vorschau_nwv').innerHTML = img;
	ahah("index.php", "go=get_geom_preview&id="+id, new Array(document.getElementById('preview_img_nwv')), new Array("src"));
}

function clearVorschau(){
	document.getElementById('vorschau_nwv').innerHTML = '';
}

function select(row){
	var current_selected = document.querySelector('.selected');
	if(current_selected != null)current_selected.className = '';
	row.className='selected';
}

function open_bearbeitungshinweise_form(id){	
	close_all_bearbeitungshinweise();
	document.getElementById('bearbeitungshinweise_div_'+id).style.display = 'inline-block';
}

function close_all_bearbeitungshinweise(){
	all_divs = document.getElementsByClassName('bearbeitungshinweise_div');
	[].forEach.call(all_divs, function (s){s.style.display = 'none';});
}

function save_bearbeitungshinweis(id){
	currentform.bearbeitungshinweis_id.value = id;
	currentform.bearbeitungshinweis_text.value = document.getElementById('bearbeitungshinweis_'+id).value;
	overlay_submit(currentform, false);
}

//-->
</script>

<? 
	function build_order_links($orderstring, $richtung){
		if($orderstring != ''){
			$orderaliases = array('flurid' => 'Flur', 'stammnr' => 'Antragsnr.', 'rissnummer' => 'Rissnr.', 'art' => 'Dokumentart', 'blattnummer' => 'Blattnr.', 'datum' => 'Datum', 'fortfuehrung' => 'Fortfuehrung', 'vermst' => 'Vermstelle', 'gueltigkeit' => 'Gueltigkeit', 'geprueft' => 'geprueft', 'format' => 'Format');
			$orders = explode(',', $orderstring);
			foreach($orders as $order){
				$orderlinks[] = '<a href="javascript:remove_from_order(\''.$order.'\');" title="'.$orderaliases[$order].' aus Sortierung entfernen">'.$orderaliases[$order].'</a>';
			}
			if($richtung == 'DESC')$richtungslink = '&nbsp;<a href="javascript:set_richtung(\'ASC\');" title="absteigend"><img src="'.GRAPHICSPATH.'pfeil.gif"></a>';
			else $richtungslink = '&nbsp;<a href="javascript:set_richtung(\'DESC\');" title="aufsteigend"><img src="'.GRAPHICSPATH.'pfeil2.gif"></a>';
			return implode(', ', $orderlinks).$richtungslink;
		}
	}

	$explosion = explode('~', $this->formvars['suchantrnr']);
	$suchantrnr = $explosion[0];
	
?>

<style>

	.nw_treffer_table tr:hover {
		background-color: lightgrey;
	}

	.nw_treffer_table td {
		padding: 5 0 5 0;
	}
	
	.nw_treffer_table td:first-child {
		padding: 5 0 5 3;
	}
	
	.nw_treffer_table td:last-child {
		padding: 5 3 5 0;
	}

	.bearbeitungshinweise{
		float:right;
		margin-right: 15px;
		font-size: 19px;
		cursor: pointer;
	}
	
	.bearbeitungshinweise:hover{
		color: red;
	}
	
	.bearbeitungshinweise_div{
		min-width: 200px;
		background-color: #EDEFEF;;
		position: absolute;
		color: black;
		display: none;
		border: 1px solid grey;
		padding: 5px;
		left: 45px;
		box-shadow: 3px 0px 4px #bbb;
		z-index: 1;
	}
	
	.bearbeitungshinweise_div div{
		white-space: pre-wrap;		
		background-color: white;
		padding: 3px;
		margin: 6px 0 4px 0;
	}
	
	.bearbeitungshinweise_div textarea{
		min-width: 200px;
	}
	
	.magnifier{
		position: absolute;
		width: 250px;
		height: 250px;
		overflow: hidden;
		pointer-events: none;
		border: 1px solid grey;
		box-shadow: 3px 0px 4px rgba(0, 0, 0, 0.2);;
		border-radius: 125px;
	}
</style>

<input type="hidden" name="go" value="Nachweisanzeige">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="order" value="<? echo $this->formvars['order']; ?>">
<input type="hidden" name="richtung" value="<? echo $this->formvars['richtung']; ?>">
<input type="hidden" name="selected_layer_id" value="<? echo LAYER_ID_NACHWEISE; ?>">
<input type="hidden" name="value_id" value="">
<input type="hidden" name="operator_id" value="IN">
<input type="hidden" name="keinzurueck" value="true">
<input type="hidden" name="suchgueltigkeit" value="<? echo $this->formvars['suchgueltigkeit']; ?>">
<input type="hidden" name="suchgeprueft" value="<? echo $this->formvars['suchgeprueft']; ?>">
<input type="hidden" name="bearbeitungshinweis_id" value="">
<input type="hidden" name="bearbeitungshinweis_text" value="">
<input type="hidden" name="geom_from_layer" value="<? echo $this->formvars['geom_from_layer']; ?>">

	
<table width="0%" border="0" cellpadding="8" cellspacing="0">
  <tr onmouseenter="clearVorschau();"> 
    <td bgcolor="<? echo BG_FORM ?>"><table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr> 
          <td><div align="center"><h2><? echo $this->titel; ?></h2></div></td>
        </tr>
        <tr> 
          <td><hr><?
		    if ($this->Fehlermeldung!='') {

include(LAYOUTPATH."snippets/Fehlermeldung.php");

}

		  ?>
	  </td>
				<tr>
					<td><a target="root" href="index.php?go=Nachweisrechercheformular&zurueck=1&VermStelle=<? echo $this->formvars['VermStelle']; ?>&geom_from_layer=<? echo $this->formvars['geom_from_layer']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><span style="font-size: 140%">&laquo;</span> Nachweisrecherche</a></td>
				</tr>
        </tr>
        <tr> 
          <td>Gesucht nach:<span class="fett"> 
            <?
						if($this->formvars['suchgueltigkeit'] == 1){ echo ' nur gültige '; }
						if($this->formvars['suchgueltigkeit'] == '0'){ echo ' nur ungültige '; }
						if($this->formvars['suchgueltigkeit'] == ''){ echo ' gültige und ungültige '; }
						if($this->formvars['suchgeprueft'] == 1){ echo ' nur geprüfte '; }
						if($this->formvars['suchgeprueft'] == '0'){ echo ' nur ungeprüfte '; }
						if($this->formvars['suchgeprueft'] == ''){ echo ' geprüfte und ungeprüfte '; }
						foreach($this->formvars['suchhauptart'] as $suchart){
							echo $this->hauptdokumentarten[$suchart]['abkuerzung'].', ';
						}
						if ($this->formvars['suchffr']){ echo ' FFR, '; }
						if ($this->formvars['suchkvz']){ echo ' KVZ, '; }
						if ($this->formvars['suchgn']){ echo ' GN, '; }
						if ($this->formvars['suchan']){ echo ' andere'; }
            switch ($this->formvars['abfrageart']) {
              case 'indiv_nr' : { 
								if($this->formvars['flur_thematisch']) echo ' thematisch'; else echo ' räumlich';
								if($this->formvars['suchgemarkung'] != '') echo ' in Gemarkung: '.$this->formvars['suchgemarkung'];
								if($this->formvars['suchflur'] != '') echo ' in Flur: '.str_pad($this->formvars['suchflur'],3,'0',STR_PAD_LEFT);
                if($this->formvars['suchstammnr'] != '')echo ' mit Antragsnummer: '.$this->formvars['suchstammnr'];
								if($this->formvars['suchstammnr2'] != '')echo ' bis '.$this->formvars['suchstammnr2'];
                if($this->formvars['suchrissnummer'] != '')echo ' mit Rissnummer: '.$this->formvars['suchrissnummer'];
								if($this->formvars['suchrissnummer2'] != '')echo ' bis '.$this->formvars['suchrissnummer2'];
                if($this->formvars['suchfortfuehrung'] != '')echo ' mit Fortführung: '.$this->formvars['suchfortfuehrung'];
								if($this->formvars['suchfortfuehrung2'] != '')echo ' bis '.$this->formvars['suchfortfuehrung2'];
								if($this->formvars['datum'] != '')echo ' von '.$this->formvars['datum'];
								if($this->formvars['datum2'] != '')echo ' bis '.$this->formvars['datum2'];
								if($this->formvars['VermStelle'] != '')echo ' von Vermessungsstelle '.$this->formvars['VermStelle'];
              } break;
              case 'antr_nr' : { 
                ?> aus Vorbereitungsnummer - <? echo $suchantrnr;
              } break;
              case 'poly' : {
                ?> in Suchpolygon<? 
              } break;
            }
              ?></span>                </td>
        </tr>
		<tr> 
			<td>Sortiert nach:
				<span class="fett"><? echo build_order_links($this->formvars['order'], $this->formvars['richtung']); ?></span>
			</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="<? echo BG_FORM ?>"><?
	 if ($this->nachweis->erg_dokumente > 0) { ie_check();?>
		<table id="nachweisanzeige_ergebnis" class="<? if (!ie_check()){ ?>scrolltable <? } ?>nw_treffer_table" style="width: 1282px" border="0" cellspacing="0" cellpadding="0">
			<thead>
        <tr style="outline: 1px solid grey;" bgcolor="#FFFFFF"> 
          <th height="40" style="width: 80"><div align="center"><span class="fett">Auswahl</span></div></th>
          <th style="width:45"><span class="fett">ID</span></th>
			<? if(strpos($this->formvars['order'], 'flurid') === false){ ?>
				<th align="center" style="width: 80"><a href="javascript:add_to_order('flurid');" title="nach individueller Nummer sortieren"><span class="fett">Flur</span></a></th>
			<? }else{echo '<th align="center" style="width: 80"><span class="fett">Flur</span></th>';} ?>
			
      <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){			
				if(strpos($this->formvars['order'], 'stammnr') === false){ ?>
					<th align="center" style="width: 90"><a href="javascript:add_to_order('stammnr');" title="nach Antragsnr. sortieren"><span class="fett">Antragsnr.</span></a></th>
				<? }else{echo '<th align="center" style="width: 90"><span class="fett">Antragsnr.</span></th>';}
				if(strpos($this->formvars['order'], 'blattnummer') === false){ ?>
				<th align="center" style="width: 70"><a href="javascript:add_to_order('blattnummer');" title="nach Blattnummer sortieren"><span class="fett">Blattnr.</span></a></th>
			<? }else{echo '<th align="center" style="width: 70"><span class="fett">Blattnr.</span></th>';}
				} ?>
			<? if(strpos($this->formvars['order'], 'rissnummer') === false){ ?>
				<th align="center" style="width: 70"><a href="javascript:add_to_order('rissnummer');" title="nach Rissnr. sortieren"><span class="fett">Rissnr.</span></a></th>
			<? }else{echo '<th align="center" style="width: 70"><span class="fett">Rissnr.</span></th>';} ?>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){
						if(strpos($this->formvars['order'], 'blattnummer') === false){ ?>
				<th align="center" style="width: 70"><a href="javascript:add_to_order('blattnummer');" title="nach Blattnummer sortieren"><span class="fett">Blattnr.</span></a></th>
			<? }else{echo '<th align="center" style="width: 70"><span class="fett">Blattnr.</span></th>';} 
				if(strpos($this->formvars['order'], 'stammnr') === false){ ?>
					<th align="center" style="width: 90"><a href="javascript:add_to_order('stammnr');" title="nach Antragsnr. sortieren"><span class="fett">Antragsnr.</span></a></th>
				<? }else{echo '<th align="center" style="width: 90"><span class="fett">Antragsnr.</span></th>';}
			} ?>            
			
			<? if(strpos($this->formvars['order'], 'art') === false){ ?>
				<th align="center" style="width: 137"><a href="javascript:add_to_order('art');" title="nach Dokumentenart sortieren"><span class="fett">Dokumentart</span></a></th>
			<? }else{echo '<th align="center" style="width: 137"><span class="fett">Dokumentart</span></th>';} ?>
			<? if(strpos($this->formvars['order'], 'datum') === false){ ?>
				<th align="center" style="width: 80"><a href="javascript:add_to_order('datum');" title="nach Datum sortieren"><span class="fett">Datum</span></a></th>
			<? }else{echo '<th align="center" style="width: 80"><span class="fett">Datum</span></th>';} ?>
			<? if(strpos($this->formvars['order'], 'fortfuehrung') === false){ ?>
				<th align="center" style="width: 120"><a href="javascript:add_to_order('fortfuehrung');" title="nach Fortführung sortieren"><span class="fett">Fortführung</span></a></th>
			<? }else{echo '<th align="center" style="width: 120"><span class="fett">Fortführung</span></th>';} ?>
			<? if(strpos($this->formvars['order'], 'vermst') === false){ ?>
				<th align="center" style="width: 120"><a href="javascript:add_to_order('vermst');" title="nach Vermessungsstelle sortieren"><span class="fett">VermStelle</span></a></th>
			<? }else{echo '<th align="center" style="width: 120"><span class="fett">VermStelle</span></th>';} ?>
			
			<? if (!$this->plugin_loaded('lenris')) { ?>
			
			<? if(strpos($this->formvars['order'], 'gueltigkeit') === false){ ?>
				<th align="center" style="width: 80"><a href="javascript:add_to_order('gueltigkeit');" title="nach Gültigkeit sortieren"><span class="fett">gültig</span></a></th>
			<? }else{echo '<th align="center" style="width: 80"><span class="fett">Gültigkeit</span></th>';} ?>
			<? if(strpos($this->formvars['order'], 'geprueft') === false){ ?>
				<th align="center" style="width: 80"><a href="javascript:add_to_order('geprueft');" title="nach geprüft sortieren"><span class="fett">geprüft</span></a></th>
			<? }else{echo '<th align="center" style="width: 80"><span class="fett">geprüft</span></th>';} ?>
			
			<? } ?>
			
			<? if(strpos($this->formvars['order'], 'format') === false){ ?>
				<th align="center" style="width: 80"><a href="javascript:add_to_order('format');" title="nach Blattformat sortieren"><span class="fett">Format</span></a></th>
			<? }else{echo '<th align="center" style="width: 80"><span class="fett">Format</span></th>';} ?>	
			
			<? if ($this->plugin_loaded('lenris')) { ?>
				
				<th style="width: 80"></th>
				<th style="width: 80"></th>
				
			<? } ?>
			
          <th colspan="3" style="width: 150"><div align="center"><?    echo $this->nachweis->erg_dokumente.' Treffer';   ?></div></th>
        </tr>
			</thead>
			<tbody style="outline: 1px solid gray; max-height: 590px; min-height: 300px;">
        <?
		$bgcolor = '#FFFFFF';
     for ($i=0;$i<$this->nachweis->erg_dokumente;$i++) {
        ?>
        <tr style="min-height: 0px; outline: 1px dotted grey;" <? if($this->formvars['selected_nachweis'] == $this->nachweis->Dokumente[$i]['id'])echo 'class="selected"'; ?> onclick="select(this);" onmouseenter="if (window.name != 'root')highlight_object(<? echo LAYER_ID_NACHWEISE; ?>, <? echo $this->nachweis->Dokumente[$i]['id']; ?>)" bgcolor="
			<? $orderelem = explode(',', $this->formvars['order']);
			if ($this->nachweis->Dokumente[$i][$orderelem[0]] != $this->nachweis->Dokumente[$i-1][$orderelem[0]]){
				if($bgcolor == '#EBEBEB'){
					echo '#FFFFFF';
					$bgcolor = '#FFFFFF';
				}
				else{
					echo '#EBEBEB';
					$bgcolor = '#EBEBEB';
				}
			}else echo $bgcolor;
            ?>
			"> 
				<td align="left" style="width: 80">
					<a name="<? echo $this->nachweis->Dokumente[$i]['id']; ?>">
					<input type="checkbox" name="id[]" id="id_<? echo $this->nachweis->Dokumente[$i]['id']; ?>" onchange="save_selection(); clear_selections('markhauptart[]', '');" value="<? echo $this->nachweis->Dokumente[$i]['id']; ?>"<? 
        # Püfen ob das Dokument markiert werden soll
                				
				if (
					$this->formvars['markhauptart'][0] != '000' AND 
					($this->formvars['id'] == NULL OR @in_array($this->nachweis->Dokumente[$i]['id'], $this->formvars['id']))
				){
					echo ' checked';
				}
				
        ?>>	
				<? if($this->nachweis->Dokumente[$i]['bemerkungen'] != ''){ ?>
					<i class="fa fa-exclamation-circle" style="font-size: 19px; color: orange"  title="Bemerkungen: <? echo htmlentities($this->nachweis->Dokumente[$i]['bemerkungen']); ?>"></i>
				<? } ?>
					<i class="fa fa-exclamation-circle bearbeitungshinweise" style="<? if($this->nachweis->Dokumente[$i]['bemerkungen_intern'] != '')echo 'color: red'; ?>" onclick="open_bearbeitungshinweise_form(<? echo $this->nachweis->Dokumente[$i]['id']; ?>);" title="Bearbeitungshinweise:&#13;<? echo $this->nachweis->Dokumente[$i]['bemerkungen_intern']; ?>"></i>
					<div style="position:relative">
						<div id="bearbeitungshinweise_div_<? echo $this->nachweis->Dokumente[$i]['id']; ?>" class="bearbeitungshinweise_div">
							<div style="position: absolute;top: 0px;right: 0px;padding: 0px; margin: 0px"><a href="javascript:close_all_bearbeitungshinweise();" title="Schließen"><img style="border:none" src="graphics/exit2.png"></a></div>
							<span class="fett">Bearbeitungshinweise:</span><br>
							<? if($this->nachweis->Dokumente[$i]['bemerkungen_intern'] != ''){ ?>
								<div><? echo $this->nachweis->Dokumente[$i]['bemerkungen_intern']; ?></div>
							<? } ?>
							<span><? echo $this->user->Vorname.' '.$this->user->Name.':'; ?></span>
							<textarea id="bearbeitungshinweis_<? echo $this->nachweis->Dokumente[$i]['id']; ?>"></textarea>
							<input type="button" style="margin:auto; display:table;" onclick="save_bearbeitungshinweis(<? echo $this->nachweis->Dokumente[$i]['id']; ?>)" value="Speichern">
						</div>
					</div>
          </td>
          <td style="width: 45"><? echo $this->nachweis->Dokumente[$i]['id']; ?></td>
          <td style="width: 80"><div align="center"><? echo $this->formvars['flurid']=$this->nachweis->Dokumente[$i]['flurid']; ?></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE != 'rissnummer'){ ?>  
          <td style="width: 90"><div align="center"><? echo $this->formvars['stammnr']=$this->nachweis->Dokumente[$i]['stammnr']; ?></div></td>
					<td style="width: 70"><div align="center"><? echo $this->formvars['blattnummer']=str_pad($this->nachweis->Dokumente[$i]['blattnummer'],BLATTNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <? } ?>
          <td style="width: 70"><div align="center"><? echo $this->formvars['rissnummer']=$this->nachweis->Dokumente[$i]['rissnummer']; ?></div></td>
          <? if(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer'){ ?>
					<td style="width: 70"><div align="center"><? echo $this->formvars['blattnummer']=str_pad($this->nachweis->Dokumente[$i]['blattnummer'],BLATTNUMMERMAXLENGTH,'0',STR_PAD_LEFT); ?></div></td>
          <td style="width: 90"><div align="center"><? echo $this->formvars['stammnr']=$this->nachweis->Dokumente[$i]['stammnr']; ?></div></td>
          <? } ?>
          <td style="width: 137"><div align="center"><? echo ($this->nachweis->Dokumente[$i]['unterart_name']? $this->nachweis->Dokumente[$i]['unterart_name'] : $this->hauptdokumentarten[$this->nachweis->Dokumente[$i]['art']]['abkuerzung']); ?></div></td>          
          <td style="width: 80"><div align="center"><? echo $this->nachweis->Dokumente[$i]['datum']; ?></div></td>
          <td style="width: 120"><div align="center"><? echo $this->formvars['fortf']=$this->nachweis->Dokumente[$i]['fortfuehrung']; ?></div></td>
          <td style="width: 120"><div align="center"><? echo $this->formvars['vermstelle']=$this->nachweis->Dokumente[$i]['vermst']; ?></div></td>
					
					<? if (!$this->plugin_loaded('lenris')) { ?>
					
					<td style="width: 80"><div align="center"><? if($this->nachweis->Dokumente[$i]['gueltigkeit']){echo 'ja';} else {echo 'nein';} ?></div></td>
					<td style="width: 80"><div align="center"><? if($this->nachweis->Dokumente[$i]['geprueft']){echo 'ja';} else {echo 'nein';} ?></div></td>
					
					<? } ?>
					
          <td style="width: 80"><div align="center"><? echo $this->formvars['format']=$this->nachweis->Dokumente[$i]['format']; ?></div></td>
					
					<? if ($this->plugin_loaded('lenris')) { ?>
					
					<td style="width: 80"></td>
					<td style="width: 80"></td>
					
					<? } ?>
					
					<td style="width: 30">
					<? 
						$dateiname = $this->nachweis->Dokumente[$i]['link_datei'];
						$dateinamensteil=explode('.',$dateiname);
						$thumbname = $dateinamensteil[0].'_thumb.jpg';
						$this->allowed_documents[] = addslashes($thumbname);
						$url = IMAGEURL.$this->document_loader_name.'?dokument='.$thumbname;
					?>
						<a target="_blank" onmouseover="getvorschau('<? echo $url; ?>');" href="index.php?go=document_anzeigen&ohnesession=1&id=<? echo $this->nachweis->Dokumente[$i]['id']; ?>&file=1" title="Dokument anzeigen"><img src="graphics/button_ansicht.png" border="0"></a>
					</td>
					<td style="width: 30">
						<a href="javascript:void(0);" title="Geltungsbereich" onmouseenter="getGeomPreview(<? echo $this->nachweis->Dokumente[$i]['id']; ?>);" onmouseleave=""><img src="graphics/umring.png" border="0"></a>
					</td>
          <td style="width: 30">
          	<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){
									if($this->nachweis->Dokumente[$i]['geprueft'] == 0 OR $this->Stelle->isFunctionAllowed('gepruefte_Nachweise_bearbeiten')){	?>
										<a target="root" href="index.php?go=Nachweisformular&id=<? echo $this->nachweis->Dokumente[$i]['id'];?>&suchgueltigkeit=<? echo $this->formvars['suchgueltigkeit'] ?>&suchgeprueft=<? echo $this->formvars['suchgeprueft'] ?>&order=<? echo $this->formvars['order'] ?>&richtung=<? echo $this->formvars['richtung'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>" title="bearbeiten"><img src="graphics/button_edit.png" border="0"></a>
							<? 	} 
							 } ?>
					</td>
          <td style="width: 30">
          	<? if($this->Stelle->isFunctionAllowed('Nachweisloeschen')){ ?>
          	<a href="javascript:void()0;" onclick="loeschen(<? echo $this->nachweis->Dokumente[$i]['id']; ?>);"  title="löschen"><img src="graphics/button_drop.png" border="0"></a>
          	<? } ?>
          </td>
					<td style="width: 24">
					<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten') AND $this->Stelle->isFunctionAllowed('Nachweise_Geometrie_uebernehmen')){ ?>
								<input type="checkbox" title="Geometrie für Geometrieübernahme verwenden" onmousedown="set_ref_geom();" value="<? echo $this->nachweis->Dokumente[$i]['id'];?>" name="ref_geom[]" <? if($this->formvars['ref_geom'] == $this->nachweis->Dokumente[$i]['id'])echo 'checked'; ?>>
					<? } ?>
					</td>
        </tr>
        <?
    }
    ?>
				</tbody>
      </table>
			<div onmouseenter="clearVorschau();" style="width: 100%">
				<table width="0%" border="1" cellspacing="0" cellpadding="0" id="nachweisanzeige_optionen">
					<tr>
						<td valign="top" style="padding: 5px;">
							<table cellspacing="4">
								<tr>
									<td colspan="2" align="center"><span class="fett">Einblenden</span></td>
								</tr>
								<tr>
									<td>
										<input type="checkbox" name="showhauptart[]" onchange="set_selections('showhauptart[]', ['2222', '']);" value=""> alle<br>
										<input type="checkbox" name="showhauptart[]" onchange="clear_selections('showhauptart[]', '2222');" value="2222"<? if(in_array(2222, $this->formvars['showhauptart']))echo ' checked="true" '; ?>> alle ausgewählten<br>
						<? 			foreach($this->hauptdokumentarten as $hauptart){  ?>
											<input type="checkbox" name="showhauptart[]" value="<? echo $hauptart['id']; ?>"<? if(in_array($hauptart['id'], $this->formvars['showhauptart']))echo ' checked="true" '; ?>> <? echo $hauptart['abkuerzung']; ?><br>
						<?			}		?>
									</td>
								</tr>
								<tr>
									<td align="center">
										<input type="button" value="Aktualisieren" onclick="overlay_submit(currentform, false);">
									</td>
								</tr>
							</table>
						</td>
						<td valign="top" style="padding: 5px;">
							<table cellspacing="4">
								<tr> 
									<td colspan="2" align="center"><span class="fett">Markieren</span></td>
								</tr>
								<tr>
									<td>
										<input type="checkbox" name="markhauptart[]" onchange="update_selection(this);" value="111"<? if(in_array(111, $this->formvars['markhauptart']))echo ' checked="true" '; ?>> alle<br>
										<input type="checkbox" name="markhauptart[]" onchange="update_selection(this);" value="222"<? if(in_array(222, $this->formvars['markhauptart']))echo ' checked="true" '; ?>> alle der Messung<br>
										<input type="checkbox" name="markhauptart[]" onchange="update_selection(this);" value="000"<? if(in_array(000, $this->formvars['markhauptart']))echo ' checked="true" '; ?>> keine<br>
						<? 			foreach($this->hauptdokumentarten as $hauptart){  ?>
											<input type="checkbox" name="markhauptart[]" onchange="update_selection(this);" value="<? echo $hauptart['id']; ?>"<? if(in_array($hauptart['id'], $this->formvars['markhauptart']))echo ' checked="true" '; ?>> <? echo $hauptart['abkuerzung']; ?><br>
						<?			}		?>
									</td>							
									<td valign="bottom">
										<br>
										<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>
											<a href="javascript:updategeoms();" id="updateGeomLink" <? if($this->formvars['ref_geom'] == '')echo 'style="display: none"'; ?>><span class="fett">Geometrie übernehmen</span></a>
										<? } ?>
										<br><br>
										<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>
											<a href="javascript:vorlage();"><span class="fett">als Vorlage verwenden</span></a>
										<? } ?>
										<br><br>
										<? if($this->Stelle->isFunctionAllowed('Nachweise_bearbeiten')){ ?>          		
											<a href="javascript:bearbeiten();"><span class="fett">bearbeiten</span></a>
										<? } ?>
										<br><br>
										<? if($this->Stelle->isFunctionAllowed('Nachweisloeschen')){ ?>
											<a href="javascript:loeschen(null);"><span class="fett">löschen</span></a>
										<? } ?>
									</td>		
								</tr>
							</table>
						</td>
						<td valign="top" style="padding: 5px;">
							<table cellspacing="4">
								<tr> 
									<td colspan="2" align="center"><span class="fett">Vorbereitungsnummer</span></td>
								</tr>
								<tr>
									<td>
										<span class="fett">
										<? $this->FormObjAntr_nr->outputHTML();
											echo $this->FormObjAntr_nr->html;?>
										</span>
									</td>
									<td valign="top">
										<br>
										<a href="javascript:zum_Auftrag_hinzufuegen();"><span class="fett">zu Auftrag hinzufügen</span></a>
										<br><br>
										<a href="javascript:aus_Auftrag_entfernen();"><span class="fett">aus Auftrag entfernen</span></a>
									</td>
								</tr>							
							</table>
						</td>
					</tr>
				</table>
			</div>
	  <? 
	  } else {
	  ?>
	  <span class="fett">Es konnten keine Dokumente zu der Auswahl gefunden werden.<br>
Wählen Sie neue Suchparameter.</span><br>
	  <? } ?>			
		</td>
  </tr>
  <tr> 
    <td bgcolor="<? echo BG_FORM ?>"> 
    </td>
  </tr>
</table>


<!--[IF !IE]> -->
<div id="vorschau_nwv"  onmouseleave="clearVorschau();" style="z-index: 1000; position: fixed; left:400px;  top:0px; box-shadow: 12px 10px 14px rgba(0, 0, 0, 0.3);"></div>
<!-- <![ENDIF]-->
 <!--[IF IE]>
<div id="vorschau" style="position: absolute; left:50%; margin-left:-150px; top: expression((190 + (ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px');"></div>
<![ENDIF]-->
