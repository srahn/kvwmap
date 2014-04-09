<?php
 # 2008-10-01 sr
  include(LAYOUTPATH.'languages/PolygonEditor_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function split_geometries(){
	document.GUI.go.value = 'Multi_Geometrien_splitten';
	document.GUI.submit();
}

function send(zoom){
	document.GUI.zoom.value = zoom;
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			alert('Geben Sie ein Polygon an.');
		}
		else{
			document.GUI.newpathwkt.value = buildwktlinefromsvgpath(document.GUI.newpath.value);
			document.GUI.go_plus.value = 'Senden';
			document.GUI.submit();
		}
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

function buildwktlinefromsvgpath(svgpath){
	var koords;
	var wkt = '';
	if(svgpath != '' && svgpath != undefined){
		wkt = "MULTILINESTRING((";
		coord = svgpath.split(" ");
		wkt = wkt+coord[1]+" "+coord[2];
		for(var i = 3; i < coord.length-1; i++){
			if(coord[i] != ""){
				wkt = wkt+","+coord[i]+" "+coord[i+1];
			}
			i++;
		}
		wkt = wkt+"))";
	}
	return wkt;
}

//-->
</script>

<?php
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
	}
?>

<table style="border:1px solid gray;" width="760" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="5"><strong><font size="+1"><a name="geoedit_anchor"><?php echo $this->titel; ?></a></font></strong></td>
  </tr>
  <tr> 
    <td rowspan="7">&nbsp;</td>
    <td colspan="4" rowspan="7"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_line_query.php')
			?>
    </td>
  </tr>
  <tr>
  	<td height="380">&nbsp;</td>
  </tr>
  <tr>
  	<td align="center"><input type="button" style="visibility:hidden" name="split" value="Geometrie in neue Datensätze aufteilen" onclick="split_geometries();"></td>
  </tr>
  <tr>
  	<td>Geometrie übernehmen von:<br>
  		<select name="layer_id" onchange="document.GUI.no_load.value='true';document.GUI.submit();">
  			<?
  				for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
  					echo '<option';
  					if($this->formvars['layer_id'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
  					echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
  				}
  			?>
  		</select> 
  	</td>
  </tr>
  <tr>  
  	<td width="160">Länge:<br><input size="12" type="text" name="linelength" value="<?echo $this->formvars['linelength']?>">&nbsp;m</td>
  </tr>
  <tr> 
    <td><hr align="center" noshade></td>
  </tr>
  <? if($this->new_entry != true){ ?>
  <tr> 
  	<td align="center">
    	<input type="button" name="senden2" value="<? echo $strSaveWithoutZoom; ?>" onclick="send('false');">&nbsp;<input type="button" name="senden" value="<? echo $strSave; ?>" onclick="send('true');"><br><br>
    	<a href="index.php?go=Layer-Suche&go_plus=Suchen&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>">Sachdatenanzeige</a>
    </td>
  </tr>
  <? }else{ ?>
  <tr>
  	<td></td>
  </tr>
  <? } ?>
  <tr>
  	<td>&nbsp;</td>
  	<td>&nbsp;<b>Maßstab&nbsp;1:&nbsp;</b><input type="text" id="scale" name="nScale" size="5" value="<?php echo round($this->map_scaledenom); ?>"></td>
	<? if($this->user->rolle->runningcoords != '0'){ ?>
	<td><b>&nbsp;<?php echo $this->strCoordinates; ?>:</b>&nbsp;</td>
	<td><input type="text" style="width:190px;border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
	<? }else{ ?>
	<td colspan="2"></td>
	<? } ?>
  	<td align="right">
  		<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
  		<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
  	</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="zoom" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="HIDDEN" NAME="no_load" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="scale" VALUE="<?php echo $scale; ?>">    
<? if($this->formvars['go'] == 'LineEditor'){ ?>
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="LineEditor" >
	<INPUT TYPE="HIDDEN" NAME="selected_layer_id" VALUE="<?php echo $this->formvars['selected_layer_id']; ?>">
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	
