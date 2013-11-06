
<script type="text/javascript">
<!--

function print(){
	document.GUI.target = '_blank';
	document.GUI.go_plus.value = 'Drucken';
	document.GUI.submit();
}

function back(){
	document.GUI.target = '';
	<? if($this->formvars['search'] == 'true'){ ?>	
	document.GUI.go.value = 'Layer-Suche_Suchen';
	<? }else{ ?>
	document.GUI.go.value = 'Sachdaten';
	<? } ?>
	document.GUI.go_plus.value = '';
	document.GUI.submit();
}

//-->
</script>


<input type="hidden" name="go" value="generischer_sachdaten_druck">

<h2><?php echo $this->titel; ?></h2>

<?php 
	if ($this->ddl->fehlermeldung != '') {
  echo "<script type=\"text/javascript\">
      <!--
        alert('".$this->ddl->fehlermeldung."');
      //-->
      </SCRIPT>"
  ;
}

?>       

<table border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>
			 <table width=100% cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr>
          <td class="bold" colspan=3 style="border-bottom:1px solid #C3C7C3">&nbsp;Layout-Auswahl</td>
        </tr>
        <tr>
          <td>
            &nbsp;<select class="select" name="aktivesLayout" onchange="document.GUI.go_plus.value = '';document.GUI.submit()">
            <option value="">--- bitte wählen ---</option>
            <?  
            for($i = 0; $i < count($this->ddl->layouts); $i++){
              echo ($this->formvars['aktivesLayout']<>$this->ddl->layouts[$i]['id']) ? '<option value="'.$this->ddl->layouts[$i]['id'].'">'.$this->ddl->layouts[$i]['name'].'</option>' : '<option value="'.$this->ddl->layouts[$i]['id'].'" selected>'.$this->ddl->layouts[$i]['name'].'</option>';
            }
            ?>
          </select> 
          </td>
          <td align="left"> 
			    	<input class="button" type="submit" name="zurueck" value="zurück" onclick="back();">
			    </td>
          <td align="left"> 
			    	<input class="button" type="submit" name="drucken" value="Drucken" onclick="print();">
			    </td>
        </tr>
      </table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr> 
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <table border="1" width="605" cellspacing="0" cellpadding="0">
        	<td colspan=8 align="left">
        		<? if($this->previewfile){ ?><img width="595" src="<? echo $this->previewfile; ?>"><? } ?>
					</td>
        </tr>
      </table>
      <table width=605 border=0 cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
  			<tr>
          <td style="border-bottom:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>  
 		<? 
    	for($i = 0; $i < count($this->attributes['type']); $i++){
    		if($this->attributes['type'][$i] != 'geometry'){ ?>
	    		<!--tr>
	        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="8"><? echo $this->attributes['name'][$i]; ?></td>
	        </tr-->
 	<? 		}	
    	} ?>
          
      </table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>  
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="chosen_layer_id" value="<? echo $this->formvars['chosen_layer_id']; ?>">
<input type="hidden" name="<? echo 'checkbox_names_'.$this->formvars['chosen_layer_id']; ?>" value="<? echo $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]; ?>">
<? 
	$checkbox_names = explode('|', $this->formvars['checkbox_names_'.$this->formvars['chosen_layer_id']]);
	for($i = 0; $i < count($checkbox_names); $i++){
		if($this->formvars[$checkbox_names[$i]] == 'on'){ ?>
			<input type="hidden" name="<? echo $checkbox_names[$i]; ?>" value="on">			
<?	}
	}

# Hidden-Felder um zurück zur Sachdatenanzeige zu kommen
if($this->formvars['qLayer'.$this->formvars['chosen_layer_id']] == 1){
	echo '<input name="qLayer'.$this->formvars['chosen_layer_id'].'" type="hidden" value="1">';
	echo '<input id="offset_'.$this->formvars['chosen_layer_id'].'" name="offset_'.$this->formvars['chosen_layer_id'].'" type="hidden" value="'.$this->formvars['offset_'.$this->formvars['chosen_layer_id']].'">';
	//echo '<input name="sql_'.$this->qlayerset[$i]['Layer_ID'].'" type="hidden" value="'.$this->qlayerset[$i]['sql'].'">';
}

if($this->formvars['search'] == 'true'){			# wenn man von der Suche kam -> Hidden Felder zum Speichern der Suchparameter
	//echo '<input name="go" type="hidden" value="Layer-Suche_Suchen">';
	echo '<input name="search" type="hidden" value="true">
				<input name="selected_layer_id" type="hidden" value="'.$this->formvars['chosen_layer_id'].'">
				<input id="offset_'.$this->formvars['chosen_layer_id'].'" name="offset_'.$this->formvars['chosen_layer_id'].'" type="hidden" value="'.$this->formvars['offset_'.$this->formvars['chosen_layer_id']].'">';
	
	foreach($this->attributes['all_table_names'] as $tablename){
  	if($this->formvars['value_'.$tablename.'_oid']){
    	echo '<input name="value_'.$tablename.'_oid" type="hidden" value="'.$this->formvars['value_'.$tablename.'_oid'].'">';
    }
  }
				
	for($j = 0; $j < count($this->attributes['type']); $j++){
		if($this->attributes['type'][$j] != 'geometry'){
			echo '
				<input name="value_'.$this->attributes['name'][$j].'" type="hidden" value="'.$this->formvars['value_'.$this->attributes['name'][$j]].'">
				<input name="value2_'.$this->attributes['name'][$j].'" type="hidden" value="'.$this->formvars['value2_'.$this->attributes['name'][$j]].'">
				<input name="operator_'.$this->attributes['name'][$j].'" type="hidden" value="'.$this->formvars['operator_'.$this->attributes['name'][$j]].'">';
			//echo '<input name="sql_'.$this->formvars['chosen_layer_id'].'" type="hidden" value="'.$this->qlayerset[0]['sql'].'">';
		}
	}
}

?>

<input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
<input name="querypolygon" type="hidden" value="<?php echo $this->querypolygon; ?>">
<input name="rectminx" type="hidden" value="<?php echo $this->formvars['rectminx'] ? $this->formvars['rectminx'] : $this->queryrect->minx; ?>">
<input name="rectminy" type="hidden" value="<?php echo $this->formvars['rectminy'] ? $this->formvars['rectminy'] : $this->queryrect->miny; ?>">
<input name="rectmaxx" type="hidden" value="<?php echo $this->formvars['rectmaxx'] ? $this->formvars['rectmaxx'] : $this->queryrect->maxx; ?>">
<input name="rectmaxy" type="hidden" value="<?php echo $this->formvars['rectmaxy'] ? $this->formvars['rectmaxy'] : $this->queryrect->maxy; ?>">
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input type="hidden" name="layer_tablename" value="">
<input type="hidden" name="layer_columnname" value="">
<input type="hidden" name="all" value="">
<input name="INPUT_COORD" type="hidden" value="<?php echo $this->formvars['INPUT_COORD']; ?>">
<INPUT TYPE="HIDDEN" NAME="searchradius" VALUE="<?php echo $this->formvars['searchradius']; ?>">
<input name="CMD" type="hidden" value="<?php echo $this->formvars['CMD']; ?>">
