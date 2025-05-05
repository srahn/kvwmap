<?
	include(LAYOUTPATH.'languages/map_'.rolle::$language.'.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
?>


<script language="JavaScript">
<!--

function toggle_vertices(){	
	SVG.toggle_vertices();
}

function rotate_point_direction(){
	SVG.rotate_point_direction();
}

function send(){
	if(document.GUI.geom_nullable.value == '0' && document.GUI.loc_x.value == ''){
		alert('Geben Sie einen Punkt an.');
	}
	else{
		document.GUI.go_plus.value = 'Senden';
		document.GUI.submit();
	}
}

//-->
</script>

<?php
if (GUI::$messages != '') {
	$bgcolor = BG_FORM;
}
else {
	$bgcolor = BG_FORMFAIL;
}
?>

<table style="border-bottom: 1px solid grey; border-collapse: separate; width: 100%" border="0" cellpadding="0" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="5">
						<? include(LAYOUTPATH.'snippets/SVG_point.php'); ?>
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
					<td><input type="text" style="border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
					<? }else{ ?>
					<td colspan="2"></td>
					<? } ?>
					<td align="right">
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
						<div id="legenddiv" style="height: <? echo $this->map->height-120; ?>px;"	class="normallegend">
							<?
							$this->simple_legend = true;
							include(SNIPPETS . 'legenddiv.php'); 
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td align="center" style="height: 60px"><? if($this->angle_attribute != ''){
						echo $strRotationAngle; ?>: <input type="text" size="3" name="angle" onchange="angle_slider.value=parseInt(angle.value);rotate_point_direction(this.value);" value="<? echo $this->formvars['angle']; ?>">&nbsp;°<br>
						<input type="range" id="angle_slider" min="-180" max="180" style="width: 120px" value="<? echo $this->formvars['angle']; ?>" oninput="angle.value=parseInt(angle_slider.value);angle.onchange();" onchange="angle.value=parseInt(angle_slider.value);angle.onchange();">
						<? } ?>
					</td>
				</tr>
				<? if($this->new_entry != true){ ?>
				<tr> 
					<td align="center" style="height: 50px">
						<input type="button" name="senden" value="Speichern" onclick="send();"><br>
					</td>
				</tr>
				<? }else{ ?>
				<tr>
					<td></td>
				</tr>
		<? } ?>
				<tr>
					<td align="center">
						<? if($this->new_entry != true){ ?>
						<a href="javascript:void(0);" onclick="overlay_link('go=Layer-Suche&go_plus=Suchen&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>', true);">Sachdatenanzeige</a>
						<? } ?>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<INPUT TYPE="HIDDEN" NAME="dimension" VALUE="<?php echo $this->formvars['dimension']; ?>">
<INPUT TYPE="HIDDEN" NAME="selected_layer_id" VALUE="<?php echo $this->formvars['selected_layer_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="geom_from_layer" VALUE="">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="orderby" VALUE="">
<INPUT TYPE="HIDDEN" NAME="geom_nullable" VALUE="<?php echo $this->formvars['geom_nullable']; ?>">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="oldscale" VALUE="<?php echo round($this->map_scaledenom); ?>"> 
<input type="hidden" name="layer_options_open" value="">
<input type="hidden" name="neuladen" value="">
<input type="hidden" name="scrollposition" value="">
<? if($this->formvars['go'] == 'PointEditor'){ ?>   
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PointEditor" >
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	
