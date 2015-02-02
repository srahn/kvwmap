<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_export_'.$this->user->rolle->language.'.php');
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

<table border="0" cellpadding="1" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" width="100%">
  <tr> 
    <td align="center" colspan="8" height="40" valign="middle"><h2><?php echo $strTitle; ?></h2></td>
  </tr>  
  <tr>
  	<td>&nbsp;</td>
  	<td colspan="6" width="100%">
  		<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" width="100%">
			  <tr>
			    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="2"><?php echo $this->strLayer; ?></td>
			    <td style="border-top:1px solid #C3C7C3">
						<? if($this->data_import_export->attributes['the_geom'] != ''){ ?>
					  transformieren nach:
						<? } ?>
						&nbsp;
			    </td>
					<td style="border-top:1px solid #C3C7C3">
					  Format:
			    </td>
			    <td style="border-bottom:1px solid #C3C7C3;border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3" rowspan="2">
			     	<input class="button" name="create" type="button" onclick="data_export();" value="<?php echo $strButtonGenerateShapeData; ?>">
			    </td>
			  </tr>
			  <tr>
			    <td valign="top" style="border-bottom:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="2"> 
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
			    </td>
			    <td style="border-bottom:1px solid #C3C7C3">
						<? if($this->data_import_export->attributes['the_geom'] != ''){ ?>
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
						<? } ?>
						&nbsp;
			    </td>
					<td style="border-bottom:1px solid #C3C7C3">
					  <select name="export_format">
							<? if($this->data_import_export->attributes['the_geom'] != ''){ ?>
					    <option <? if($this->formvars['export_format'] == 'Shape')echo 'selected '; ?> value="Shape">Shape</option>
							<option <? if($this->formvars['export_format'] == 'GML')echo 'selected '; ?> value="GML">GML</option>
							<option <? if($this->formvars['export_format'] == 'KML')echo 'selected '; ?> value="KML">KML</option>
							<option <? if($this->formvars['export_format'] == 'UKO')echo 'selected '; ?> value="UKO">UKO</option>
							<? } ?>
							<option <? if($this->formvars['export_format'] == 'CSV')echo 'selected '; ?> value="CSV">CSV</option>
					  </select>
			    </td>
			  </tr>
			  <tr>
		     	<td style="border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5" width="100%">
		     		Attributauswahl:
		     	</td>
		    </tr>
		    <tr>
		    	<td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5" width="100%"> 
		      	<table cellpadding="1" cellspacing="0">
		      		<tr>
			      		<?
				      		for($i = 0; $i < count($this->data_import_export->attributes['name']); $i++){
				      			if($i % 6 == 0){ echo '</tr><tr>';}	
				      	?>
			            <td>
			            	<input type="checkbox" <? if($this->formvars['load'] OR $this->formvars['check_'.$this->data_import_export->attributes['name'][$i]] == 1)echo 'checked'; ?> value="1" name="check_<? echo $this->data_import_export->attributes['name'][$i]; ?>">
			            	<?php
			              if($this->data_import_export->attributes['alias'][$i] != ''){
			                echo $this->data_import_export->attributes['alias'][$i];
			              }
			              else{
			                echo $this->data_import_export->attributes['name'][$i];
			              }
			          ?></td>
			          	<td>&nbsp;&nbsp;</td>
				          <?
				      	}
			      	?>
			      	</tr>
			      </table> 
			    </td>
			 	</tr>
			 	<? if($this->formvars['sql_'.$this->formvars['selected_layer_id']] != ''){ ?>
			 	<tr>
			 		<td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="5" width="100%">
			 			<span class="fett"><? echo $this->formvars['anzahl']; ?> Datensätze aus Sachdatenanzeige</span>
			 			<input type="hidden" name="sql_<? echo $this->formvars['selected_layer_id']; ?>" value="<? echo stripslashes($this->formvars['sql_'.$this->formvars['selected_layer_id']]); ?>">
			 			<input type="hidden" name="anzahl" value="<? echo $this->formvars['anzahl']; ?>">
			 		</td>
			 	</tr>
			 	<? } ?>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td></td>
  	<td>&nbsp;</td>
  	<td align="center" colspan="2">
  		<input id="go_plus" type="hidden" name="go_plus" value="">
  	</td>
  </tr>
  <tr>
  	<td></td> 
    <td colspan="6" align="right">
    	Geometrie übernehmen von: 
  		<select name="layer_id" onchange="document.GUI.submit();">
  			<option value="">--- Auswahl ---</option>
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
    <td colspan="6">&nbsp;</td>
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


