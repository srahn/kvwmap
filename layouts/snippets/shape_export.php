<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_export_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function getpoly(){
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);			
			document.GUI.selectstring.value = document.GUI.selectstring_save.value + " AND Transform("+document.GUI.columnname.value+", "+document.GUI.client_epsg.value+") && GeomFromText('"+document.GUI.newpathwkt.value+"', "+document.GUI.client_epsg.value+") AND INTERSECTS(Transform("+document.GUI.columnname.value+", "+document.GUI.client_epsg.value+"), GeomFromText('"+document.GUI.newpathwkt.value+"', "+document.GUI.client_epsg.value+"))";
		}
	}
	else{
		document.GUI.selectstring.value = document.GUI.selectstring_save.value + " AND Transform("+document.GUI.columnname.value+", "+document.GUI.client_epsg.value+") && GeomFromText('"+document.GUI.newpathwkt.value+"', "+document.GUI.client_epsg.value+") AND INTERSECTS(Transform("+document.GUI.columnname.value+", "+document.GUI.client_epsg.value+"), GeomFromText('"+document.GUI.newpathwkt.value+"', "+document.GUI.client_epsg.value+"))";
	}
}

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
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="5"><strong><font size="+1"><?php echo $strTitle; ?></font></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  
  <?if($this->shape->formvars['filename'] != ''){?>
  <tr>
  	<td colspan="5">Shape-Dateien erzeugt. <a href="<? echo $this->shape->formvars['filename'] ?>">Herunterladen</a></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>	
  <?}?>
  
  <tr> 
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="2"><?php echo   $this->strLayer; ?></td>
    <td>&nbsp;</td>
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="2"><?php echo $strSelectStatement; ?></td>
  </tr>
  <tr> 
    <td valign="top" style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="2"> 
      <select style="width:250px" size="1" class="select" name="selected_layer_id" onchange="document.GUI.submit();" <?php if(count($this->shape->layerdaten['ID'])==0){ echo 'disabled';}?>>
      	<option value=""><?php echo $this->strPleaseSelect; ?></option>
        <?
    		for($i = 0; $i < count($this->shape->layerdaten['ID']); $i++){    			
    			echo '<option';
    			if($this->shape->layerdaten['ID'][$i] == $this->shape->formvars['selected_layer_id']){
    				echo ' selected';
    				$selectindex = $i;
    			}
    			echo ' value="'.$this->shape->layerdaten['ID'][$i].'">'.$this->shape->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select> 
     </td>
     <td>&nbsp;</td>
     <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="2"> 
      <textarea class="textarea" cols="35" rows="4" name="selectstring" ><? echo $this->shape->formvars['selectstring'] ?></textarea> 
     </td>
  </tr>
  <tr>
  	<td align="center" colspan="2"><input class="button" type="button" value="<?php echo $strButtonCutByPolygon; ?>" onclick="getpoly()"></td>
  	<td>&nbsp;</td>
  	<td align="center" colspan="2">
  		<input id="go_plus" type="hidden" name="go_plus" value="">
  		<input class="button" name="create" type="button" onclick="submitWithValue('GUI', 'go_plus', 'Shape-Datei erzeugen')" value="<?php echo $strButtonGenerateShapeData; ?>">
  	</td>
  </tr>
  <tr> 
    <td colspan="5" align="center"> 
      <?php
 				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php')
			?>
    </td>
  </tr>
  <tr> 
    <td colspan="5">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="layer_name" value="<? echo umlaute_umwandeln($this->shape->layerdaten['Bezeichnung'][$selectindex]); ?>">
<input type="hidden" name="selectstring_save" value="<? echo $this->shape->formvars['selectstring_save'] ?>">
<input type="hidden" name="client_epsg" value="<? echo $this->user->rolle->epsg_code ?>">
<input type="hidden" name="go" value="SHP_Export">
<input type="hidden" name="area" value="">
<INPUT TYPE="hidden" NAME="layer_id" VALUE="<? echo $this->formvars['selected_layer_id']; ?>">
<INPUT TYPE="hidden" NAME="columnname" VALUE="<? echo $this->shape->formvars['columnname'] ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="<? echo $this->shape->formvars['fromwhere']; ?>">


