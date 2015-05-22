<?
	include(LAYOUTPATH.'languages/map_'.$this->user->rolle->language.'.php');
	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);
?>


<script language="JavaScript">
<!--

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
	if ($this->Meldung=='') {
	  $bgcolor=BG_FORM;
	}
	else {
	  $bgcolor=BG_FORMFAIL;
		showAlert('Fehler bei der Eingabe:\n'.$this->Meldung);
	}
?>

<table style="border: 1px solid; border-color: #eeeeee; border-left: none; border-right: none" width="760" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr> 
    <td align="center" colspan="5"><a name="geoedit_anchor"><h2><?php echo $this->titel; ?></h2></a></td>
  </tr>
  <tr> 
    <td rowspan="3">&nbsp;</td>
    <td colspan="3" rowspan="3"> 
      <?php
				include(LAYOUTPATH.'snippets/SVG_point.php')
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
  <? if($this->new_entry != true){ ?>
  <tr> 
    <td align="center">
    	<input type="button" name="senden" value="Speichern" onclick="send();"><br>
    </td>
  </tr>
  <? }else{ ?>
  <tr>
  	<td></td>
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
	<td><input type="text" style="border:0px;background-color:transparent" name="runningcoords" value="">&nbsp;EPSG-Code:<?php echo $this->user->rolle->epsg_code; ?></td>
	<? }else{ ?>
	<td colspan="2"></td>
	<? } ?>
	<td align="center">
		<? if($this->new_entry != true){ ?>
		<a href="index.php?go=Layer-Suche&go_plus=Suchen&selected_layer_id=<?php echo $this->formvars['selected_layer_id']; ?>&value_<?php echo $this->formvars['layer_tablename']; ?>_oid=<?php echo $this->formvars['oid']; ?>">Sachdatenanzeige</a>
		<? } ?>&nbsp;
	</td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="dimension" VALUE="<?php echo $this->formvars['dimension']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_id" VALUE="<?php echo $this->formvars['layer_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_columnname" VALUE="<?php echo $this->formvars['layer_columnname']; ?>">
<INPUT TYPE="HIDDEN" NAME="layer_tablename" VALUE="<?php echo $this->formvars['layer_tablename']; ?>">
<INPUT TYPE="hidden" NAME="fromwhere" VALUE="">
<INPUT TYPE="HIDDEN" NAME="geom_nullable" VALUE="<?php echo $this->formvars['geom_nullable']; ?>">
<INPUT TYPE="HIDDEN" NAME="oid" VALUE="<?php echo $this->formvars['oid']; ?>">
<INPUT TYPE="HIDDEN" NAME="oldscale" VALUE="<?php echo round($this->map_scaledenom); ?>"> 
<? if($this->formvars['go'] == 'PointEditor'){ ?>   
	<INPUT TYPE="HIDDEN" NAME="go" VALUE="PointEditor" >
<? } ?>
<INPUT TYPE="HIDDEN" NAME="go_plus" VALUE="" >
    	
