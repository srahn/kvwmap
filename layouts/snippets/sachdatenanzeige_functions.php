<?
if($this->formvars['anzahl'] == ''){$this->formvars['anzahl'] = 0;}
?>

<script type="text/javascript">
<!--

	var geom_not_null = false;

	function scrolltop(){
		<? if($this->user->rolle->gui == 'gui2.php'){ ?>
		document.getElementById('contentdiv').scrollTop = 0;
		<? }else{ ?>
		window.scrollTo(0,0);
		<? } ?>
	}
	
	function scrollbottom(){
		<? if($this->user->rolle->gui == 'gui2.php'){ ?>
		document.getElementById('contentdiv').scrollTop = document.getElementById('contentdiv').scrollHeight;
		<? }else{ ?>
		window.scrollTo(0, document.body.scrollHeight);
		<? } ?>
	}
	
	function nextquery(offset){
		currentform.target = '';
		if(currentform.go_backup.value != ''){
			currentform.go.value = currentform.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) + <? echo $this->formvars['anzahl']; ?>;
		overlay_submit(currentform);
	}

	function prevquery(offset){
		currentform.target = '';
		if(currentform.go_backup.value != ''){
			currentform.go.value = currentform.go_backup.value;
		}
		obj = document.getElementById(offset);
		if(obj.value == '' || obj.value == undefined){
			obj.value = 0;
		}
		obj.value = parseInt(obj.value) - <? echo $this->formvars['anzahl']; ?>;
		overlay_submit(currentform);
	}

	function back(){
		currentform.go.value = 'Layer-Suche';
		currentform.submit();
	}

	function druck(){
		currentform.target = '_blank';
		currentform.printversion.value = 'true';
		currentform.submit();
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
  	form_fieldstring = currentform.form_field_names.value+'';
  	form_fields = form_fieldstring.split('|');
  	for(i = 0; i < form_fields.length-1; i++){
  		fieldstring = form_fields[i]+'';
  		field = fieldstring.split(';');
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[4] != 'Dokument' && (document.getElementsByName(fieldstring)[0].readOnly != true) && field[5] == '0' && document.getElementsByName(fieldstring)[0].value == ''){
  			alert('Das Feld '+document.getElementsByName(fieldstring)[0].title+' erfordert eine Eingabe.');
  			return;
  		}
  		if(document.getElementsByName(fieldstring)[0] != undefined && field[6] == 'date' && field[4] != 'Time' && document.getElementsByName(fieldstring)[0].value != '' && !checkDate(document.getElementsByName(fieldstring)[0].value)){
  			alert('Das Datumsfeld '+document.getElementsByName(fieldstring)[0].title+' hat nicht das Format TT.MM.JJJJ.');
  			return;
  		}
  	}
  	currentform.go.value = 'Sachdaten_speichern';
  	<? if($this->formvars['close_after_saving']){ ?>
  		currentform.close_window.value='true';
  	<?}?>
  	overlay_submit(currentform);
	}
	
	function save_new_dataset(){
		if((geom_not_null && currentform.newpath.value == '' && currentform.loc_x == undefined) || (geom_not_null && currentform.loc_x != undefined && currentform.loc_x.value == '')){ 
			alert('Sie haben keine Geometrie angegeben.');
			return;
		}
  	form_fieldstring = currentform.form_field_names.value+'';
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
  	currentform.go.value = 'neuer_Layer_Datensatz_speichern';
  	<? if($this->formvars['close_after_saving']){ ?>
  		currentform.close_window.value='true';
  	<?}?> 
  	overlay_submit(currentform);
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