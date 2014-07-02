<?
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
	include(SNIPPETS.'sachdatenanzeige_functions.php'); 
 ?>
	<a name="oben"></a>
<?php
$anzLayer=count($this->qlayerset);
if ($anzLayer==0) {
	?>
<span style="font:normal 12px verdana, arial, helvetica, sans-serif; color:#FF0000;"><? echo $strNoLayer; ?></span>	<br/>
	<?php
	$this->found = 'false';
}
for($i=0;$i<$anzLayer;$i++){
	if ($this->qlayerset[$i]['template']=='') {
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
   	 	else {
   	 	 #Version 1.6.5 pk 2007-04-17
   	 	 echo '<p>Das in den stellenbezogenen Layereigenschaften angegebene Templatefile:';
   	 	 echo '<br><span class="fett">'.SNIPPETS.$this->qlayerset[$i]['template'].'</span>';
   	 	 echo '<br>kann nicht gefunden werden. Überprüfen Sie ob der angegebene Dateiname richtig ist oder eventuell Leerzeichen angegeben sind.';
   	 	 echo ' Die Templatezuordnung für die Sachdatenanzeige ändern Sie über Stellen anzeigen, Ändern, Layer bearbeiten, stellenbezogen bearbeiten.';
   	 	 #echo '<p><a href="index.php?go=Layer2Stelle_Editor&selected_layer_id='.$this->qlayerset[$i]['Layer_ID'].'&selected_stelle_id='.$this->Stelle->id.'&stellen_name='.$this->Stelle->Bezeichnung.'">zum Stellenbezogener Layereditor</a> (nur mit Berechtigung mÃ¶glich)';
   	 }
   }
	}

   if($this->qlayerset[$i]['connectiontype'] == MS_POSTGIS AND $this->qlayerset[$i]['count'] > 1){
	   # BlÃ¤tterfunktion
	   if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] == ''){
		   $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] = 0;
		 }
		 $von = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + 1;
	   $bis = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + $this->formvars['anzahl'];
	   if($bis > $this->qlayerset[$i]['count']){
	   	$bis = $this->qlayerset[$i]['count'];
	   }
	   echo'
	   <table border="0" cellpadding="10" width="100%" cellspacing="0">

	   	<tr height="40px" valign="top">
	   		<td align="right" width="38%">';
	   		if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] >= $this->formvars['anzahl'] AND $this->formvars['printversion'] == ''){
	   			echo '<a href="javascript:prevquery(\'offset_'.$this->qlayerset[$i]['Layer_ID'].'\');">'.$strBackDatasets.'&nbsp;</a>';
	   		}
	      echo '&nbsp;
				</td>
				<td width="200px" align="center">
					<span class="fett">'.$von.' - '.$bis.' '.$strFromDatasets.' '.$this->qlayerset[$i]['count'].'</span>
				</td>
	      <td width="38%">';
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
    <tr>
    	<td>&nbsp;</td>
      <td align="center" width="100%"><input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();">&nbsp;<input class="button" type="reset" value="Zurücksetzen"></td>
      <td align="right"><a href="javascript:scrolltop();"><img title="nach oben" src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a></td>
    </tr>
		<tr>
			<td height="30" valign="bottom" align="center" colspan="5" id="loader" style="display:none"><img id="loaderimg" src="graphics/ajax-loader.gif"></td>
		</tr>
  </table>
<?
	}
	else{ ?>
		<table width="100%" border="0" cellpadding="10" cellspacing="0">
    <tr>
    	<td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right"><a href="javascript:scrolltop();"><img title="nach oben" src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a></td>
    </tr>
  </table>
<?	}
?>
  <br><div align="center">


  <?
		for($i = 0; $i < $anzLayer; $i++){
			if($this->formvars['qLayer'.$this->qlayerset[$i]['Layer_ID']] == 1){
				echo '<input name="qLayer'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="1">';
				echo '<input id="offset_'.$this->qlayerset[$i]['Layer_ID'].'" name="offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
				echo '<input name="sql_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->qlayerset[$i]['sql'].'">';
			}
		}
  ?>

  <?
  	if($this->search == true){			# wenn man von der Suche kam -> Hidden Felder zum Speichern der Suchparameter
		if($this->last_query != '')echo '<input name="go" type="hidden" value="get_last_query">';
		else echo '<input name="go" type="hidden" value="Layer-Suche_Suchen">';
		echo '		<input name="search" type="hidden" value="true">
  					<input name="selected_layer_id" type="hidden" value="'.$this->formvars['selected_layer_id'].'">
  					<input id="offset_'.$this->formvars['selected_layer_id'].'" name="offset_'.$this->formvars['selected_layer_id'].'" type="hidden" value="'.$this->formvars['offset_'.$this->formvars['selected_layer_id']].'">
					<input name="sql_'.$this->formvars['selected_layer_id'].'" type="hidden" value="'.$this->qlayerset[0]['sql'].'">';

  		if(is_array($this->qlayerset[0]['attributes']['all_table_names'])){
  			foreach($this->qlayerset[0]['attributes']['all_table_names'] as $tablename){
		    	if($this->formvars['value_'.$tablename.'_oid']){
		      	echo '<input name="value_'.$tablename.'_oid" type="hidden" value="'.$this->formvars['value_'.$tablename.'_oid'].'">';
		      }
		    }
  		}

	  	for($j = 0; $j < count($this->qlayerset[0]['attributes']['type']); $j++){
	  		if($this->qlayerset[0]['attributes']['type'][$j] != 'geometry'){
					echo '
						<input name="value_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="value2_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['value2_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="operator_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars['operator_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
					';
	  		}
	  	}
	  	if($this->formvars['printversion'] == '' AND $this->formvars['keinzurueck'] == ''){
	  		echo '<a href="javascript:back();">'.$strbackToSearch.'</a><br><br>';
	  	}
  	}
  	else{
			if($this->last_query != '')echo '<input name="go" type="hidden" value="get_last_query">';
			else echo '<input name="go" type="hidden" value="Sachdaten">';
  	}
  if($this->found != 'false' AND $this->formvars['printversion'] == ''){
  ?>
  <a href="javascript:druck();"><? echo $strDataPrint; ?></a>
  <br><br>
  <?}?>
  <a name="unten"></a>
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
	<? if($this->currentform != 'document.GUI2'){ ?>
  <table width="100%" border="0" cellpadding="2" cellspacing="0">
    <tr bgcolor="<?php echo BG_DEFAULT ?>" align="center">
      <td><a href="index.php?searchradius=<?php echo $this->formvars['searchradius']; ?>"><? echo $strbacktomap;?></a></td>
    </tr>
  </table>
	<? } ?>
</div>
<input type="hidden" name="titel" value="<? echo $this->formvars['titel'] ?>">
<input type="hidden" name="width" value="">
<input type="hidden" name="document_attributename" value="">
<input type="hidden" name="map_flag" value="<? echo $this->formvars['map_flag']; ?>">
<input name="newpath" type="hidden" value="<?php echo $this->formvars['newpath']; ?>">
<input name="pathwkt" type="hidden" value="<?php echo $this->formvars['newpathwkt']; ?>">
<input name="newpathwkt" type="hidden" value="<?php echo $this->formvars['newpathwkt']; ?>">
<input name="result" type="hidden" value="">
<input name="firstpoly" type="hidden" value="<?php echo $this->formvars['firstpoly']; ?>">
