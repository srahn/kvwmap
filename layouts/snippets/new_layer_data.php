<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/new_layer_data_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

	var geom_not_null = false;

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
		if((geom_not_null && document.GUI.newpath.value == '' && document.GUI.loc_x == undefined) || (geom_not_null && document.GUI.loc_x != undefined && document.GUI.loc_x.value == '')){ 
			alert('Sie haben keine Geometrie angegeben.');
			return;
		}
  	form_fieldstring = document.GUI.form_field_names.value+'';
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';'); 
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && field[4] != 'SubFormFK' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			if(field[4] == 'TextFK'){
			  	alert('Neuer Datensatz nicht im abhängigen Layer!\nGeben Sie neue Datensätze nur über den übergeordneten Layer ein.');
				}else{
			  	alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
			  }
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	document.GUI.go.value = 'neuer_Layer_Datensatz_speichern';
  	<? if($this->formvars['close_after_saving']){ ?>
  		document.GUI.close_window.value='true';
  	<?}?> 
  	document.GUI.submit();
	}
	
	function subsave_data(layer_id, fromobject, targetobject, targetlayer_id, targetattribute, data){
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
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly == false && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
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
  		if(document.getElementsByName(fieldstring)[0] != undefined && document.getElementsByName(fieldstring)[0].readOnly == false && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  		data_r += '&'+form_fields[i]+'='+document.getElementsByName(form_fields[i])[0].value;
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

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><strong><font size="+1"><?php echo $strtitle; ?></font></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="5"><?php echo $strLayer;?></td>
  </tr>
  <tr> 
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5"> 
      <select style="width:250px" size="1" class="select" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
      	<option value=""><?php echo $strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){    			
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select> </td>
  </tr>
  <?php if($this->Fehler != ''){
  	?>
  <tr>
  	<td><?php
  	 echo $this->Fehler;
  	 ?></td>
  </tr><?php
   }
   ?></table>

<?
if($this->formvars['selected_layer_id'] AND $this->Fehler == ''){
	$i = 0;	
	if($this->qlayerset[$i]['template']==''){
		if(GLEVIEW == '2'){
			include(SNIPPETS.'generic_layer_editor_2.php');			# Attribute zeilenweise
		}
		else{
			include(SNIPPETS.'generic_layer_editor.php');				# Attribute spaltenweise
		}
	}
	else{
		if(is_file(SNIPPETS.$this->qlayerset[$i]['template'])){
			include(SNIPPETS.$this->qlayerset[$i]['template']);
		}
		else{
			if(file_exists(PLUGINS.$this->qlayerset[$i]['template'])){
				include(PLUGINS.$this->qlayerset[$i]['template']);			# Pluginviews
			}
			else{
	   		#Version 1.6.5 pk 2007-04-17
	   	 	echo '<p>Das in den stellenbezogenen Layereigenschaften angegebene Templatefile:';
	   	 	echo '<br><b>'.SNIPPETS.$this->qlayerset[$i]['template'].'</b>';
	   	 	echo '<br>kann nicht gefunden werden. Überprüfen Sie ob der angegebene Dateiname richtig ist oder eventuell Leerzeichen angegeben sind.';
	   	 	echo ' Die Templatezuordnung für die Sachdatenanzeige ändern Sie über Stellen anzeigen, ändern, Layer bearbeiten, stellenbezogen bearbeiten.';
	   	 	#echo '<p><a href="index.php?go=Layer2Stelle_Editor&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&selected_stelle_id='.$this->Stelle->id.'&stellen_name='.$this->Stelle->Bezeichnung.'">zum Stellenbezogener Layereditor</a> (nur mit Berechtigung möglich)';
			}
		}
	}
		
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr align="center"> 
  	<td>
  		<input type="button" name="go_plus" value="<? echo $strSave; ?>" onclick="save();">&nbsp;&nbsp;&nbsp;&nbsp;
  		<input type="checkbox" name="weiter_erfassen" value="1" <? if($this->formvars['weiter_erfassen'] == 1)echo 'checked="true"'; ?>>und einen weiteren Datensatz erfassen
  	</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<? } ?>

<input type="hidden" name="close_window" value="">
<input type="hidden" name="go" value="neuer_Layer_Datensatz">
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input type="hidden" name="geomtype" value="<? echo $this->geomtype; ?>">

<?
	if ($this->Meldung1!='') {
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung1);
	}
?>  

