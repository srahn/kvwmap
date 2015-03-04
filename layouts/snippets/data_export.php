<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/data_export_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	wkt = "POLYGON((";
	parts = svgpath.split("M");
	for(j = 1; j < parts.length; j++){
		if(j > 1){
			wkt = wkt + "),("
		}
		koords = ""+parts[j];
		coord = koords.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
	}
	wkt = wkt+"))";
	return wkt;
}

function data_export(){
	if(document.GUI.selected_layer_id.value != ''){
		if(document.GUI.newpath.value != ''){
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
		}
		document.GUI.go_plus.value = 'Exportieren';
		document.GUI.submit();
	}
	else{
		alert('Bitten wählen Sie einen Layer aus.');
	}
}

//-->
</script>

<?
$floor = floor(count($this->data_import_export->attributes['name'])/4);
$rest = count($this->data_import_export->attributes['name']) % 4;
if ($rest % 4 != 0) {
 $r=1;
} else {
 $r=0;
}
$j=0;
?>

<table border="0" cellpadding="1" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" width="100%">
  <tr>
    <td align="center" colspan="8" height="40" valign="middle"><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
	<td>

	<? if($this->formvars['sql_'.$this->formvars['selected_layer_id']] != ''){ ?>
	  <div style="margin-top:30px; text-align:center;">
		<span class="fett"><? echo $this->formvars['anzahl']; ?> <? if ($this->formvars['anzahl']==1) { echo $strRecordFromGLE; } else { echo $strRecordsFromGLE; } ?></span>
		<input type="hidden" name="sql_<? echo $this->formvars['selected_layer_id']; ?>" value="<? echo stripslashes($this->formvars['sql_'.$this->formvars['selected_layer_id']]); ?>">
		<input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
	  </div>
	<? } ?>

      <div class="flexcontainer1" style="border: 1px solid #C3C7C3; padding: 5px; margin-top:30px;">

        <div style="padding-top:5px; padding-bottom:5px;">
          <?php echo $this->strLayer; ?>:<br>
          <select style="width:250px" size="1"  name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->data_import_export->layerdaten['ID'])==0){ echo 'disabled';}?>>
      		<option value=""><?php echo $this->strPleaseSelect; ?></option>
      	  <?
      		for($i = 0; $i < count($this->data_import_export->layerdaten['ID']); $i++){
      			echo '<option';
      			if($this->data_import_export->layerdaten['ID'][$i] == $this->data_import_export->formvars['selected_layer_id']){
      				echo ' selected';
      				$selectindex = $i;
      			}
      			echo ' value="'.$this->data_import_export->layerdaten['ID'][$i].'">'.$this->data_import_export->layerdaten['Bezeichnung'][$i].'</option>';
      		}
      		?>
      	</select>
        </div>
				<? if($this->data_import_export->formvars['selected_layer_id'] != ''){ ?>
        <div style="padding-top:5px; padding-bottom:5px; margin-left: 15px;">
          <?php echo $strFormat; ?>:<br>
      	<select name="export_format">
      		<? if($this->data_import_export->layerdaten['export_privileg'][$selectindex] == 1 AND $this->data_import_export->attributes['the_geom'] != ''){ ?>
      	    <option <? if($this->formvars['export_format'] == 'Shape')echo 'selected '; ?> value="Shape">Shape</option>
      		<option <? if($this->formvars['export_format'] == 'GML')echo 'selected '; ?> value="GML">GML</option>
      		<option <? if($this->formvars['export_format'] == 'KML')echo 'selected '; ?> value="KML">KML</option>
      		<option <? if($this->formvars['export_format'] == 'UKO')echo 'selected '; ?> value="UKO">UKO</option>
      		<? } ?>
      		<option <? if($this->formvars['export_format'] == 'CSV')echo 'selected '; ?> value="CSV">CSV</option>
      	</select>
        </div>
				<? }
        if($this->data_import_export->attributes['the_geom'] != ''){ ?>
        <div style="padding-top:5px; padding-bottom:5px; margin-left: 15px;">
          <?php echo $strTransformInto; ?>:<br>
      	<select name="epsg">
      	  <option value="">-- Auswahl --</option>
      	  <?
      		foreach($this->epsg_codes as $epsg_code){
      				echo '<option ';
      		if($this->formvars['epsg'] == $epsg_code['srid'])echo 'selected ';
      		echo ' value="'.$epsg_code['srid'].'">'.$epsg_code['srid'].': '.$epsg_code['srtext'].'</option>';
      	}
      		?>
      	</select>
        </div>
        <? } ?>

      </div>

      <div style="border-bottom:1px solid #C3C7C3; border-left: 1px solid #C3C7C3; border-right: 1px solid #C3C7C3; padding-top:10px; padding-bottom:5px; padding-left:5px; padding-right:5px;">
        <?php echo $strAttributeSelection; ?>:
        <div class="flexcontainer2">
        	<? for($s = 0; $s < 4; $s++){ ?>
        	<div style="float: left; padding: 4px; min-width:20%;">
          <? for($i = 0; $i < $floor+$r; $i++){ ?>
      	  <div style="padding: 4px;">
      	  <input type="checkbox" <? if($this->formvars['load'] OR $this->formvars['check_'.$this->data_import_export->attributes['name'][$j]] == 1)echo 'checked'; ?> value="1" name="check_<? echo $this->data_import_export->attributes['name'][$j]; ?>">
      	  <?php
      	  if($this->data_import_export->attributes['alias'][$j] != ''){
              echo $this->data_import_export->attributes['alias'][$j];
            } else {
              if($this->data_import_export->attributes['name'][$j] == $this->data_import_export->attributes['the_geom']){
                echo $strNameGeometryField;
              } else {
                echo $this->data_import_export->attributes['name'][$j];
              }
            } ?>
            </div>
          <?
          $j++;
          } ?>
          </div>
          <?
           $rest=$rest-1;
           if ($rest==0) {
            $r=0;
           }
          } ?>
        </div>
      </div>

      <div style="margin-top:30px; margin-bottom:30px; text-align: center;">
		<input class="button" name="create" type="button" onclick="data_export();" value="<?php echo $strButtonGenerateShapeData; ?>">
      </div>

    </td>
  </tr>
  <tr>
  	<td align="center" colspan="2">
  		<input id="go_plus" type="hidden" name="go_plus" value="">
  	</td>
  </tr>
  <tr>
    <td align="right">
    	<? echo $this->strUseGeometryOf; ?>:
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value=""><?php echo $this->strPleaseSelect; ?></option>
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select>
      <?php
 				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="layer_name" value="<? echo umlaute_umwandeln($this->data_import_export->layerdaten['Bezeichnung'][$selectindex]); ?>">
<input type="hidden" name="selectstring_save" value="<? echo $this->data_import_export->formvars['selectstring_save'] ?>">
<input type="hidden" name="client_epsg" value="<? echo $this->user->rolle->epsg_code ?>">
<input type="hidden" name="go" value="Daten_Export">
<input type="hidden" name="area" value="">
<INPUT TYPE="hidden" NAME="columnname" VALUE="<? echo $this->formvars['columnname'] ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="hidden" NAME="export_columnname" VALUE="<? echo $this->data_import_export->formvars['columnname'] ?>">
<input type="hidden" name="always_draw" value="<? echo $always_draw; ?>">


