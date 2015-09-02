<?php
 # 2008-10-01 sr
  include(LAYOUTPATH.'languages/PolygonEditor_'.$this->user->rolle->language.'.php');
	include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
 ?>
<script language="JavaScript">
<!--

function toggle_vertices(){	
	document.getElementById("vertices").SVGtoggle_vertices();			// das ist ein Trick, nur so kann man aus dem html-Dokument eine Javascript-Funktion aus dem SVG-Dokument aufrufen
}

function send(zoom){
	document.GUI.zoom.value = zoom;
	if(document.GUI.newpathwkt.value == ''){
		if(document.GUI.newpath.value == ''){
			if(document.GUI.geom_nullable.value == '0'){
				alert('Geben Sie ein Polygon an.');
				return 0;
			}
		}
		else document.GUI.newpathwkt.value = buildwktpolygonfromsvgpath(document.GUI.newpath.value);
	}
	document.GUI.go_plus.value = 'Senden';
	document.GUI.submit();
}

function buildwktpolygonfromsvgpath(svgpath){
	var koords;
	var wkt = '';
	if(svgpath != '' && svgpath != undefined){
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
	}
?>

<table style="border: 1px solid; border-collapse: separate; border-color: #eeeeee; border-left: none; border-right: none" width="760" border="0" cellpadding="0" cellspacing="5" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="5"><a name="geoedit_anchor"><h2><?php echo $this->titel; ?></h2></a></td>
  </tr>
  <tr> 
    <td rowspan="8">&nbsp;</td>
    <td colspan="4" rowspan="8"> 
      <?
				include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php');
			?>
    </td>
  </tr>
  <tr>
  	<td>
			<table cellspacing=4 cellpadding=0 border=0 style="border:1px solid #C3C7C3;" background="<? echo GRAPHICSPATH."bg.gif"; ?>">
				<tr align="center">
					<td><?php echo $strAvailableLayer; ?>:</td>
				</tr>
				<tr align="left">
					<td>
					<div align="center"><input type="submit" class="button" name="neuladen" value="<?php echo $strLoadNew; ?>"></div>
					<br>
					<div style="width:260px; height:<?php echo $this->map->height-196; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
						&nbsp;
						<img src="graphics/tool_info_2.png" alt="<? echo $strInfoQuery; ?>" title="<? echo $strInfoQuery; ?>" width="17">&nbsp;
						<img src="graphics/layer.png" alt="<? echo $strLayerControl; ?>" title="<? echo $strLayerControl; ?>" width="20" height="20"><br>
						<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
						<div id="legend_div"><? echo $this->legende; ?></div>
					</div>
					</td>
				</tr>
			</table>
		</td>
  </tr>
	<tr>
		<td></td>
	</tr>
  <tr>
  	<td><? echo $strGeomFrom; ?>:<br>
  		<select name="layer_id" style="width: 260px" onchange="document.GUI.no_load.value='true';document.GUI.submit();">
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
    <td colspan="2" style="border-top:1px solid #999999"><img width="240px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
  </tr>
  <tr>  
  	<td width="160"><? echo $strArea; ?>:<br><input size="12" type="text" name="area" value="<?echo $this->formvars['area']?>">&nbsp;m<SUP>2</SUP></td>
  </tr>
  <tr> 
    <td colspan="2" style="border-top:1px solid #999999"><img width="240px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
  </tr>
  <? if($this->new_entry != true){ ?>
  <tr> 
    <td align="center">
    	<input type="button" name="senden2" value="<? echo $strSaveWithoutZoom; ?>" onclick="send('false');">&nbsp;<input type="button" name="senden" value="<? echo $strSave; ?>" onclick="send('true');"><br>
    </td>
  </tr>
  <? }else{ ?>
  <tr>
  	<td style="height: 24px">&nbsp;</td>
  </tr>
  <? } ?>
  <tr>
  	<td>&nbsp;</td>
  	<td>
			<div style="width:150px;" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
				<div valign="top" style="height:0px; position:relative;">
					<div id="scales" style="display:none; position:absolute; left:66px; bottom:-1px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
						<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="document.GUI.nScale.value=this.value; document.getElementById('scales').style.display='none'; document.GUI.submit();">
							<? 
								foreach($selectable_scales as $scale){
									echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				&nbsp;<span class="fett"><?php echo $this->strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
			</div>
		</td>
		<? if($this->user->rolle->runningcoords != '0'){ ?>
		<td><span class="fett">&nbsp;<?php echo $this->strCoordinates; ?>:</span>&nbsp;</td>
		<td><input type="text" style="width:190px;border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
		<? }else{ ?>
		<td colspan="2"></td>
		<? } ?>
  	<td align="right">
  		<input type="checkbox" name="always_draw" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
  		<input type="checkbox" onclick="toggle_vertices()" name="punktfang">&nbsp;Punktfang
  	</td>
		<td align="center">
			  <? if($this->new_entry != true){ ?>
			<a href="index.php?go=Layer-Suche&go_plus=Suchen&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>">Sachdatenanzeige</a>
				<? } ?>&nbsp;
		</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="zoom" VALUE="">
<INPUT TYPE="HIDDEN" NAME="columnname" VALUE="<?php echo $this->formvars['columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="fromwhere" VALUE="<? echo $this->formvars['fromwhere']; ?>">
<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="<? echo $this->formvars['orderby']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="HIDDEN" NAME="geom_nullable" VALUE="<?php echo $this->formvars['geom_nullable']; ?>">
<INPUT TYPE="HIDDEN" NAME="no_load" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="oldscale" VALUE="<?php echo round($this->map_scaledenom); ?>">    
<? if($this->formvars['go'] == 'PolygonEditor'){ ?>
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PolygonEditor" >
	<INPUT TYPE="HIDDEN" NAME="selected_layer_id" VALUE="<?php echo $this->formvars['selected_layer_id']; ?>">
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >

<?
	if ($this->Meldung!='') {
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
	}
?>  	