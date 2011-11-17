<?php
 # 2008-09-30 sr
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
  if($this->formvars['anzahl'] == ''){$this->formvars['anzahl'] = 0;}
 ?>
<script type="text/javascript">
<!--

	function nextquery(offset){
		document.GUI.target = '';
		if(document.GUI.go_backup.value != ''){
			document.GUI.go.value = document.GUI.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
		document.GUI.submit();
	}

	function prevquery(offset){
		document.GUI.target = '';
		if(document.GUI.go_backup.value != ''){
			document.GUI.go.value = document.GUI.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
		document.GUI.submit();
	}

	function back(){
		document.GUI.go.value = 'Layer-Suche';
		document.GUI.submit();
	}

	function druck(){
		document.GUI.target = '_blank';
		document.GUI.printversion.value = 'true';
		document.GUI.submit();
	}

	function checkDate(string){
    var split = string.split(".");
    var day = parseInt(split[0], 10);
    var month = parseInt(split[1], 10);
    var year = parseInt(split[2], 10);
    var check = new Date(year, month-1, day);
    var day2 = check.getDate();
    var year2 = check.getFullYear();
    var month2 = check.getMonth()+1;
    if(year2 == year && month == month2 && day == day2){
    	return true;
    }
    else{
    	return false;
    }
	}

	function save(){
  	form_fieldstring = document.GUI.form_field_names.value+'';
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && (document.getElementsByName(fieldstring)[0].readOnly != true || field[4] == 'TextFK') && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	document.GUI.go.value = 'Sachdaten_speichern';
  	<? if($this->formvars['close_after_saving']){ ?>
  		document.GUI.close_window.value='true';
  	<?}?>
  	document.GUI.submit();
	}

	function subdelete_data(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data){
		// layer_id ist die von dem Layer, in dem der Datensatz geloescht werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Loeschung des Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		data_r = data.replace(/<und>/g, "&");
  	data = 'go=Layer_Datensaetze_Loeschen&chosen_layer_id='+layer_id+'&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&embedded=true' + data_r;
  	data += '&checkbox_names_'+layer_id+'='+document.getElementsByName('checkbox_names_'+layer_id)[0].value;
		data += '&'+document.getElementsByName('checkbox_names_'+layer_id)[0].value+'=on';
		ahah('<? echo URL.APPLVERSION; ?>index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject)), 'sethtml');
	}

	function subsave_data(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data){
		// layer_id ist die von dem Layer, in dem der Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular des Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		data_r = data.replace(/<und>/g, "&");
  	form_fieldstring = document.getElementById('sub_'+layer_id+'_form_field_names').value;
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  		data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;
  	}
  	data = 'go=Sachdaten_speichern&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&form_field_names='+form_fieldstring+'&embedded=true' + data_r;
		ahah('<? echo URL.APPLVERSION; ?>index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject)), 'sethtml');
	}

	function subsave_new_layer_data(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data){
		// layer_id ist die von dem Layer, in dem ein neuer Datensatz gespeichert werden soll
		// fromobject ist die id von dem div, welches das Formular zur Eingabe des neuen Datensatzes enthaelt
		// targetobject ist die id von dem Objekt im Hauptformular, welches nach Speicherung des neuen Datensatzes aktualisiert werden soll
		// targetlayer_id ist die von dem Layer, zu dem das targetobject gehoert
		// targetattribute ist das Attribut, zu dem das targetobject gehoert
		// data ist ein string, der weitere benötigte KVPs enthalten kann (durch <und> getrennt)
		data_r = data.replace(/<und>/g, "&");
  	form_fieldstring = document.getElementById('sub_'+layer_id+'_form_field_names').value;
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly != true && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  		if(document.getElementsByName(form_fields[i])[0] != undefined){
  			data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;
  		}
  	}
  	data = 'go=neuer_Layer_Datensatz_speichern&selected_layer_id='+layer_id+'&fromobject='+fromobject+'&targetobject='+targetobject+'&targetlayer_id='+targetlayer_id+'&targetattribute='+targetattribute+'&data='+data+'&form_field_names='+form_fieldstring+'&embedded=true' + data_r;
		ahah('<? echo URL.APPLVERSION; ?>index.php', data, new Array(document.getElementById(fromobject), document.getElementById(targetobject)), 'sethtml');
	}

	function clearsubforms(){
		alldivs = document.getElementsByTagName('div');
		for(i = 0; i < alldivs.length; i++){
			id = alldivs[i].id + '';
			if(id.substr(0, 7) == 'subform'){
				alldivs[i].innerHTML = '';
			}
		}
	}

	function clearsubform(subformid){
		document.getElementById(subformid).innerHTML = '';
	}

//-->
</script>

  <br/>
  <h2><u>Sachdaten:</u></h2>
<?php
$anzLayer=count($this->qlayerset);
if ($anzLayer==0) {
	?>
<br/>
<br/>
<span style="font:normal 12px verdana, arial, helvetica, sans-serif; color:#FF0000;"><? echo $strNoLayer; ?></span>	<br/>
	<?php
	$this->found = 'false';
}
for ($i=0;$i<$anzLayer;$i++) {
 #echo '<br>includiere:'.SNIPPETS.$this->qlayerset[$i]['Name'].'.php';
   if ($this->qlayerset[$i]['template']=='') {
   	if(GLEVIEW == '2'){
    	include(SNIPPETS.'generic_layer_editor_2.php');			# Attribute zeilenweise
   	}
   	else{
   		include(SNIPPETS.'generic_layer_editor.php');				# Attribute spaltenweise
   	}
   }
   else {
   	 if (is_file(SNIPPETS.$this->qlayerset[$i]['template'])) {
   	 	 include(SNIPPETS.$this->qlayerset[$i]['template']);
   	 }
   	 else {
   	 	 #Version 1.6.5 pk 2007-04-17
   	 	 echo '<p>Das in den stellenbezogenen Layereigenschaften angegebene Templatefile:';
   	 	 echo '<br><b>'.SNIPPETS.$this->qlayerset[$i]['template'].'</b>';
   	 	 echo '<br>kann nicht gefunden werden. Überprüfen Sie ob der angegebene Dateiname richtig ist oder eventuell Leerzeichen angegeben sind.';
   	 	 echo ' Die Templatezuordnung für die Sachdatenanzeige ändern Sie über Stellen anzeigen, ändern, Layer bearbeiten, stellenbezogen bearbeiten.';
   	 	 #echo '<p><a href="index.php?go=Layer2Stelle_Editor&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&selected_stelle_id='.$this->Stelle->id.'&stellen_name='.$this->Stelle->Bezeichnung.'">zum Stellenbezogener Layereditor</a> (nur mit Berechtigung möglich)';
   	 }
   }

   if($this->qlayerset[$i]['connectiontype'] == MS_POSTGIS AND $this->qlayerset[$i]['count'] > 1){
	   # Blätterfunktion
	   if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] == ''){
		   $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] = 0;
		 }
		 $von = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + 1;
	   $bis = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + $this->formvars['anzahl'];
	   if($bis > $this->qlayerset[$i]['count']){
	   	$bis = $this->qlayerset[$i]['count'];
	   }
	   echo'
	   <table border="0" cellpadding="10" cellspacing="0">

	   	<tr height="50px" valign="top">
	   		<td align="right">';
	   		if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] >= $this->formvars['anzahl'] AND $this->formvars['printversion'] == ''){
	   			echo '<a href="javascript:prevquery(\'offset_'.$this->qlayerset[$i]['Layer_ID'].'\');">'.$strBackDatasets.'&nbsp;</a>';
	   		}
	      echo '&nbsp;
				</td>
				<td align="center">
					<b>'.$von.' - '.$bis.' '.$strFromDatasets.' '.$this->qlayerset[$i]['count'].'</b>
				</td>
	      <td>';
	      if($bis < $this->qlayerset[$i]['count'] AND $this->formvars['printversion'] == ''){
	      	echo '<a href="javascript:nextquery(\'offset_'.$this->qlayerset[$i]['Layer_ID'].'\');">&nbsp;&nbsp;'.$strForwardDatasets.'</a>';
	      }
	      echo '
				</td>
	    </tr>

	   </table>';
   }
}
?>
<?
	if($this->editable == 'true' AND $this->formvars['printversion'] == ''){ ?>
		<table width="100%" border="0" cellpadding="10" cellspacing="0">
    <tr align="center">
      <td><input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();"></td>
    </tr>
  </table>
<?
	}
?>
  <br><div align="center">


  <?
  	for($i = 0; $i < $anzLayer; $i++){
  		if($this->formvars['qLayer'.$this->qlayerset[$i]['Layer_ID']] == 1){
  			echo '<input name="qLayer'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="1">';
  			//echo '<input name="offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
  			echo '<input id="offset_'.$this->qlayerset[$i]['Layer_ID'].'" name="offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
  			echo '<input name="sql_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->qlayerset[$i]['sql'].'">';
  		}
  	}
  ?>

  <?
  	if($this->search == true){			# wenn man von der Suche kam -> Hidden Felder zum Speichern der Suchparameter
  		echo '<input name="go" type="hidden" value="Layer-Suche_Suchen">
  					<input name="search" type="hidden" value="true">
  					<input name="selected_layer_id" type="hidden" value="'.$this->formvars['selected_layer_id'].'">
  					<input id="offset_'.$this->formvars['selected_layer_id'].'" name="offset_'.$this->formvars['selected_layer_id'].'" type="hidden" value="'.$this->formvars['offset_'.$this->formvars['selected_layer_id']].'">';

  		foreach($this->qlayerset[0]['attributes']['all_table_names'] as $tablename){
	    	if($this->formvars['value_'.$tablename.'_oid']){
	      	echo '<input name="value_'.$tablename.'_oid" type="hidden" value="'.$this->formvars['value_'.$tablename.'_oid'].'">';
	      }
	    }

	  	for($j = 0; $j < count($this->qlayerset[0]['attributes']['type']); $j++){
	  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
					echo '
						<input name="value_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="value2_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value2_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="operator_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['operator_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="sql_'.$this->formvars['selected_layer_id'].'" type="hidden" value="'.$this->qlayerset[0]['sql'].'">
					';
	  		}
	  	}
	  	if($this->formvars['printversion'] == '' AND $this->formvars['keinzurueck'] == ''){
	  		echo '<a href="javascript:back();">'.$strbackToSearch.'</a><br><br>';
	  	}
  	}
  	else{
  		echo '<input name="go" type="hidden" value="Sachdaten">';
  	}

  if($this->found != 'false' AND $this->formvars['printversion'] == ''){
  ?>
  <a href="javascript:druck();"><? echo $strDataPrint; ?></a><br><br><br><br>
  <?}?>
  <input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
  <input type="hidden" name="printversion" value="">
  <input type="hidden" name="go_backup" value="">
  <input type="hidden" name="close_window" value="">
  <input name="querypolygon" type="hidden" value="<?php echo $this->querypolygon; ?>">
  <input name="rectminx" type="hidden" value="<?php echo $this->formvars['rectminx'] ? $this->formvars['rectminx'] : $this->queryrect->minx; ?>">
  <input name="rectminy" type="hidden" value="<?php echo $this->formvars['rectminy'] ? $this->formvars['rectminy'] : $this->queryrect->miny; ?>">
  <input name="rectmaxx" type="hidden" value="<?php echo $this->formvars['rectmaxx'] ? $this->formvars['rectmaxx'] : $this->queryrect->maxx; ?>">
  <input name="rectmaxy" type="hidden" value="<?php echo $this->formvars['rectmaxy'] ? $this->formvars['rectmaxy'] : $this->queryrect->maxy; ?>">
  <input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
  <input type="hidden" name="chosen_layer_id" value="">
  <input type="hidden" name="layer_tablename" value="">
  <input type="hidden" name="layer_columnname" value="">
  <input type="hidden" name="all" value="">
  <input name="INPUT_COORD" type="hidden" value="<?php echo $this->formvars['INPUT_COORD']; ?>">
  <INPUT TYPE="HIDDEN" NAME="searchradius" VALUE="<?php echo $this->formvars['searchradius']; ?>">
  <input name="CMD" type="hidden" value="<?php echo $this->formvars['CMD']; ?>">
  <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr bgcolor="<?php echo BG_DEFAULT ?>" align="center">
      <td><a href="index.php?searchradius=<?php echo $this->formvars['searchradius']; ?>"><? echo $strbacktomap;?></a></td>
    </tr>
  </table>
</div>
<input type="hidden" name="titel" value="<? echo $this->formvars['titel'] ?>">
<input type="hidden" name="width" value="">
<input type="hidden" name="document_attributename" value="">
