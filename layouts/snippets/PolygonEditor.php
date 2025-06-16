<?php
 # 2008-10-01 sr
  include(LAYOUTPATH.'languages/PolygonEditor_'.rolle::$language.'.php');
	include(LAYOUTPATH.'languages/map_'.rolle::$language.'.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
 ?>
<script language="JavaScript">
<!--

function toggle_vertices(){	
	SVG.toggle_vertices();
}

function split_geometries(){
	var r = confirm("Wollen Sie diesen Datensatz wirklich in mehrere Datensätze aufteilen?");
	if (r) {
		document.GUI.go.value = 'Multi_Geometrien_splitten';
		document.GUI.submit();
	}
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
		else document.GUI.newpathwkt.value = SVG.buildwktpolygonfromsvgpath(document.GUI.newpath.value);
	}
	document.GUI.go_plus.value = 'Senden';
	document.GUI.submit();
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

<table style="border-bottom: 1px solid grey; border-collapse: separate; width: 100%" border="0" cellpadding="0" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td valign="top">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="6">
						<?
							include(LAYOUTPATH.'snippets/SVG_polygon_query_area.php');
						?>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:150px;" onmouseover="document.getElementById('scales').style.display='inline-block';" onmouseout="document.getElementById('scales').style.display='none';">
							<div valign="top" style="height:0px; position:relative;">
								<div id="scales" style="display:none; position:absolute; left:66px; bottom:-1px; width: 78px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
									<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onclick="setScale(this);">
										<? 
											foreach($selectable_scales as $scale){
												echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
											}
										?>
									</select>
								</div>
							</div>
							&nbsp;<span class="fett"><?php echo $this->strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" onkeyup="if (event.keyCode == 13) { setScale(this); }" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
						</div>
					</td>
					<? if($this->user->rolle->runningcoords != '0'){ ?>
					<td><span class="fett">&nbsp;<?php echo $this->strCoordinates; ?>:</span>&nbsp;</td>
					<td><input type="text" style="width:190px;border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
					<? }else{ ?>
					<td colspan="2"></td>
					<? } ?>
					<td align="right">
						<input type="checkbox" name="always_draw" onclick="saveDrawmode();" value="1" <?if($always_draw == 1 OR $always_draw == 'true')echo 'checked'; ?>>&nbsp;weiterzeichnen&nbsp;&nbsp;
						<input id="punktfang" type="checkbox" onclick="toggle_vertices()" name="punktfang" <? if($this->formvars['punktfang'] == 'on')echo 'checked="true"'; ?>>&nbsp;Punktfang
						&nbsp;<img id="scalebar" valign="top"	style="display:none;margin-top: 5px; padding-right:<? echo ($this->user->rolle->hideLegend ? '35' : '5'); ?>px" alt="Maßstabsleiste" src="<? echo $this->img['scalebar']; ?>">
						<div id="lagebezeichnung" style="display:none"></div>
					</td>
				</tr>
			</table>
    </td>
  	<td valign="top" width="100%">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<div id="legenddiv" style="height: <? echo $this->map->height-215; ?>px;"	class="normallegend">
							<?
							$this->simple_legend = true;
							include(SNIPPETS . 'legenddiv.php'); 
							?>
						</div>
					</td>
				</tr>
				<tr><?
					if ($this->new_entry != true) { ?>
						<td style="height: 34px" align="center"><input type="button" <? if($this->polygon['numgeometries'] < 2){ echo 'style="visibility:hidden"';} ?> name="split" value="Geometrie in neue Objekte aufteilen" onclick="split_geometries();"></td><?
					}
					else { ?>
						<td style="height: 34px">&nbsp;</td><?
					} ?>
				</tr>
				<tr>
					<td>
						<div style="padding: 0 3px 0 3px">
							<? echo $strGeomFrom; ?>:<br>
							<select size="1" name="selected_group_id" style="width: 244px" onchange="ahah('index.php', 'go=getqueryableVectorLayers&group_id=' + this.value, [document.GUI.geom_from_layer], ['sethtml']);" <?php if(count($this->layergruppen['ID'])==0){ echo 'disabled';}?>>
								<option value="">  -- <?php echo $this->strGroup; ?> --  </option>
				<?
								for($i = 0; $i < count($this->layergruppen['ID']); $i++){         
									echo '<option';
									if($this->layergruppen['ID'][$i] == $this->formvars['selected_group_id']){
										echo ' selected';
									}
									echo ' value="'.$this->layergruppen['ID'][$i].'">'.$this->layergruppen['Bezeichnung'][$i].'</option>';
								}
							?>
							</select>
							<select name="geom_from_layer" style="width: 244px" onchange="geom_from_layer_change(<? echo $this->formvars['selected_layer_id']; ?>);">
								<option value="0"> - alle - </option>
								<?
								for($i = 0; $i < count($this->queryable_vector_layers['ID']); $i++){
										echo '<option';
										if($this->formvars['geom_from_layer'] == $this->queryable_vector_layers['ID'][$i]){echo ' selected';}
										echo ' value="'.$this->queryable_vector_layers['ID'][$i].'">'.$this->queryable_vector_layers['Bezeichnung'][$i].'</option>';
									}
								?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td style="height: 34px">
						<input type="checkbox" name="singlegeom" value="true" <? if($this->formvars['singlegeom'])echo 'checked="true"'; ?>><? echo $strSingleGeoms; ?>
					</td>
				</tr>			
				<tr>
					<td align="center" height="30">
						<input type="button" onclick="document.getElementById('data_import').style.display='';" value="<? echo $strDataImport; ?>" title="<? echo $strDataImportTitle; ?>">
						<div id="data_import" style="position: fixed; top: 50%; left: 50%; margin-top: -220px; margin-left: -300px;display: none;box-shadow: 6px 5px 7px #777;">
							<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:void(0)" onclick="document.getElementById('data_import').style.display='none';" title="Schlie&szlig;en"><img style="border:none" src="graphics/exit2.png"></img></a></div>
							<? 
								$this->formvars['after_import_action'] = 'use_geometry';
								include(SNIPPETS.'data_import.php'); 
							?>
						</div>
					</td>
				</tr>
				<tr> 
					<td colspan="2" style="border-top:1px solid #999999"></td>
				</tr>
				<tr>  
					<td width="160" style="height: 44px">
						<div style="padding: 0 3px 0 3px">
						<? echo $strArea; ?>:&nbsp;<input size="12" type="text" name="area" value="<?echo $this->formvars['area']?>">&nbsp;m<SUP>2</SUP>
						</div>
					</td>
				</tr>
				<tr> 
					<td colspan="2" style="border-top:1px solid #999999"><img width="240px" height="1px" src="<? echo GRAPHICSPATH; ?>leer.gif"></td>
				</tr>
				<? if($this->new_entry != true){ ?>
				<tr> 
					<td align="center" style="height: 40px">
						<input title="<? echo $strSaveWithoutZoom; ?>" type="button" name="senden2" value="<? echo $strSaveWithoutZoom; ?>" onclick="send('false');">&nbsp;<input title="<? echo $strSaveTitle; ?>" type="button" name="senden" value="<? echo $strSave; ?>" onclick="send('true');"><br>
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td style="height: 24px">&nbsp;</td>
				</tr>
				<? } ?>
				<tr>
					<td align="center">
						<? if($this->new_entry != true){ ?>
						<a href="javascript:void(0);" onclick="overlay_link('go=Layer-Suche&go_plus=Suchen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>', true);">Sachdatenanzeige</a>
						<? } ?>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<INPUT TYPE="HIDDEN" NAME="zoom" VALUE="">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="HIDDEN" NAME="geom_nullable" VALUE="<?php echo $this->formvars['geom_nullable']; ?>">
<INPUT TYPE="HIDDEN" NAME="no_load" VALUE="">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="oldscale" VALUE="<?php echo round($this->map_scaledenom); ?>">    
<input type="hidden" name="layer_options_open" value="">
<input type="hidden" name="neuladen" value="">
<input type="hidden" name="scrollposition" value="">
<? if($this->formvars['go'] == 'PolygonEditor'){ ?>
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PolygonEditor" >
	<INPUT TYPE="HIDDEN" NAME="selected_layer_id" VALUE="<?php echo $this->formvars['selected_layer_id']; ?>">
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >

<?
	if ($this->Meldung!='') {
		$this->add_message('error', 'Fehler bei der Eingabe:<p>' . $this->Meldung);
	}
?>  	