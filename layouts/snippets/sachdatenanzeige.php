<? 
	global $selectable_limits;
	include(SNIPPETS.'generic_form_parts.php');
  include(LAYOUTPATH.'languages/sachdatenanzeige_'.$this->user->rolle->language.'.php');
	include(SNIPPETS.'sachdatenanzeige_functions.php'); 
 ?>
	<img height="7" src="<? echo GRAPHICSPATH ?>leer.gif">
	<a name="oben"></a>	
<? if($this->user->rolle->querymode == 1){ ?>
	<script type="text/javascript">
		if(document.getElementById('overlayfooter') != undefined)document.getElementById('overlayfooter').style.display = 'none';
		if(document.getElementById('savebutton') != undefined)document.getElementById('savebutton').style.display = 'none';
	</script>
<? }
$this->found = 'false';
$anzLayer=count($this->qlayerset);
if ($anzLayer==0) {
	?>
<span style="font:normal 12px verdana, arial, helvetica, sans-serif; color:#FF0000;"><? echo $strNoLayer; ?></span><br/>
	<?php	
}
for($i=0;$i<$anzLayer;$i++){
	if($i > 0){
		echo '<hr style="width: 100%; height: 3px; margin: 15 0; color: '.BG_GLEHEADER.'; background: '.BG_GLEHEADER.';">';
	}
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

	if($this->qlayerset[$i]['connectiontype'] == MS_WMS){
		$imgxy=explode(';',$this->formvars['INPUT_COORD']);
		if($imgxy[0] != $imgxy[1]){
			echo 'Sie haben ein Rechteck zur Abfrage eines WMS-Themas aufgezogen.<br>Bei WMS-Themen sind nur punktuelle Abfragen möglich,<br>daher wird der Mittelpunkt des Rechtecks verwendet.';
		}
	}
	
	$gesamt = $this->qlayerset[$i]['count'];
  if($this->qlayerset[$i]['connectiontype'] == MS_POSTGIS AND $gesamt > 1){
	   # Blätterfunktion
	   if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] == ''){
		   $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] = 0;
		 }
		 $von = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + 1;
	   $bis = $this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] + $this->formvars['anzahl'];
	   if($bis > $gesamt){
	   	$bis = $gesamt;
	   }
	   echo'
	   <table border="0" cellpadding="2" width="100%" cellspacing="0" id="sachdatenanzeige_paging">

	   	<tr valign="top">
	   		<td align="right" width="38%">';
	   		if($this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']] >= $this->formvars['anzahl'] AND $this->formvars['printversion'] == ''){
					echo '<a href="javascript:firstdatasets('.$this->qlayerset[$i]['Layer_ID'].');"><img src="'.GRAPHICSPATH.'go-first.png" class="hover-border" style="vertical-align:middle" title="'.$strFirstDatasets.'"></a>&nbsp;&nbsp;&nbsp;';
	   			echo '<a href="javascript:prevdatasets('.$this->qlayerset[$i]['Layer_ID'].');"><img src="'.GRAPHICSPATH.'go-previous.png" class="hover-border" style="vertical-align:middle" title="'.$strBackDatasets.'"></a>&nbsp;';
	   		}
	      echo '
				</td>
				<td width="200px" align="center">
					<span class="fett">'.$von.' - '.$bis.' '.$strFromDatasets.' '.$gesamt.'</span>
				</td>
	      <td width="38%">';
	      if($bis < $gesamt AND $this->formvars['printversion'] == ''){
	      	echo '&nbsp;<a href="javascript:nextdatasets('.$this->qlayerset[$i]['Layer_ID'].');"><img src="'.GRAPHICSPATH.'go-next.png" class="hover-border" style="vertical-align:middle" title="'.$strForwardDatasets.'"></a>&nbsp;&nbsp;&nbsp;';
					echo '<a href="javascript:lastdatasets('.$this->qlayerset[$i]['Layer_ID'].', '.$gesamt.');"><img src="'.GRAPHICSPATH.'go-last.png" class="hover-border" style="vertical-align:middle" title="'.$strLastDatasets.'"></a>';
	      }
	      echo '
				</td>
	    </tr>

	   </table>';
   }
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="sachdatenanzeige_footer">
	<tr>
		<td align="right">
    <? if ($this->user->rolle->visually_impaired) { ?>
				<? if($layer['template'] == '' OR $layer['template'] == 'generic_layer_editor_2.php'){ ?>
				<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewColumns; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'columns.png'; ?>"></a>
				<? }else{ ?>
				<a href="javascript:switch_gle_view(<? echo $layer['Layer_ID']; ?>);"><img title="<? echo $strSwitchGLEViewRows; ?>" class="hover-border" src="<? echo GRAPHICSPATH.'rows.png'; ?>"></a>
				<? } ?>
		<? } ?>


			<a href="javascript:scrolltop();"><img class="hover-border" title="nach oben" src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
</table>
<?
	if($this->found != 'false' AND $this->formvars['printversion'] == ''){	?>		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" id="sachdatenanzeige_footer">
    <tr>
    	<td width="49%" class="px13">
				<? if($this->user->rolle->querymode == 1){ ?>
					<script type="text/javascript">
						if(document.getElementById('overlayfooter') != undefined){
							document.getElementById('overlayfooter').style.display = 'block';
							document.getElementById('anzahl').value = '<? echo $this->formvars['anzahl']; ?>';
						}
					</script>
				<? }else{
							echo '&nbsp;'.$strLimit; ?>&nbsp;
							<select name="anzahl" id="anzahl" onchange="javascript:overlay_submit(currentform, false);">
								<? foreach($selectable_limits as $limit){
								if($this->formvars['anzahl'] != '' AND $custom_limit != true AND !in_array($this->formvars['anzahl'], $selectable_limits) AND $this->formvars['anzahl'] < $limit){
									$custom_limit = true;	?>
									<option value="<? echo $this->formvars['anzahl'];?>" selected><? echo $this->formvars['anzahl']; ?></option>
								<? } ?>
								<option value="<? echo $limit; ?>" <? if($this->formvars['anzahl'] == $limit)echo 'selected'?>><? echo $limit; ?></option>
								<? } ?>
							</select>
					<? } ?>
			</td>
      <td align="center">
			<?  if($this->editable != ''){
						if($this->user->rolle->querymode == 1){ ?>
							<script type="text/javascript">
								if(document.getElementById('savebutton') != undefined)document.getElementById('savebutton').style.display = 'block';
							</script>
				<?  }else{ ?>
							<input type="button" class="button" name="savebutton" value="<? echo $strSave; ?>" onclick="save();">
				<? 	}
					}?>
			</td>
			<td align="right" width="49%">
		<? if($this->user->rolle->querymode == 1){ ?>
					<script type="text/javascript">
						if(document.getElementById('overlayfooter') != undefined)document.getElementById('overlayfooter').style.display = 'block';
					</script>
		<? }else{ ?>
			<a href="javascript:druck();" class="px13"><? echo $this->printversion; ?></a>&nbsp;
			<? } ?>
			</td>
    </tr>
		<tr>
			<td height="30" valign="bottom" align="center" colspan="5" id="loader" style="display:none"><img id="loaderimg" src="graphics/ajax-loader.gif"></td>
		</tr>
  </table>
<? } ?>
  <br><div align="center">

  <?
  	if($this->search == true){			# wenn man von der Suche kam -> Hidden Felder zum Speichern der Suchparameter
		echo '<input name="go" type="hidden" value="Layer-Suche_Suchen">';
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
			for($m = 0; $m <= $this->formvars['searchmask_count']; $m++){
				if($m > 0){
					$prefix = $m.'_';
					echo '<input name="boolean_operator_'.$m.'" type="hidden" value="'.$this->formvars['boolean_operator_'.$m].'">';
				}
				for($j = 0; $j < count($this->qlayerset[0]['attributes']['type']); $j++){
					echo '
						<input name="'.$prefix.'value_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars[$prefix.'value_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="'.$prefix.'value2_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars[$prefix.'value2_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
						<input name="'.$prefix.'operator_'.$this->qlayerset[0]['attributes']['name'][$j].'" type="hidden" value="'.$this->formvars[$prefix.'operator_'.$this->qlayerset[0]['attributes']['name'][$j]].'">
					';
				}
			}
	  	if($this->formvars['printversion'] == '' AND $this->formvars['keinzurueck'] == '' AND $this->formvars['subform_link'] == ''){
	  		echo '<a href="javascript:currentform.go.value=\'Layer-Suche\';currentform.submit();" id="sachdatenanzeige_footer">'.$strbackToSearch.'</a><br><br>';
	  	}
  	}
  	else{
			for($i = 0; $i < $anzLayer; $i++){
				if($this->formvars['qLayer'.$this->qlayerset[$i]['Layer_ID']] == 1){
					echo '<input name="qLayer'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="1">';
					echo '<input id="offset_'.$this->qlayerset[$i]['Layer_ID'].'" name="offset_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->formvars['offset_'.$this->qlayerset[$i]['Layer_ID']].'">';
					echo '<input name="sql_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->qlayerset[$i]['sql'].'">';
				}
			}
			echo '<input name="go" type="hidden" value="Sachdaten">';
  	}
  ?>
  <a name="unten"></a>
  <input type="hidden" name="printversion" value="">
  <input type="hidden" name="go_backup" value="">
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
  <table width="100%" border="0" cellpadding="2" cellspacing="0" id="sachdatenanzeige_footer">
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
<input type="hidden" name="searchmask_count" value="<? echo $this->formvars['searchmask_count']; ?>">
<input type="hidden" name="within" value="<? echo $this->formvars['within']; ?>">

<div id="vorschau" style="pointer-events:none; box-shadow: 12px 10px 14px #777;z-index: 1000; position: fixed; right:20px; top:20px; ">
	<img id="preview_img" src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
</div>
