<?
	global $selectable_limits;
	$width = $this->map->width +
		($this->user->rolle->hideMenue  == 1 ? $size['menue']['hide_width'] : $size['menue']['width']) +
		($this->user->rolle->hideLegend == 1 ? $size['legend']['hide_width'] : $size['legend']['width']);
?>
				<form name="GUI2" enctype="multipart/form-data" method="post" action="index.php" id="GUI2">
					<div id="overlaydiv" style="display:none;padding:3px;left:150px;top:150px;width:auto;max-width:<? echo $width; ?>px;position:absolute;z-index: 1000;-moz-box-shadow: 12px 10px 14px #777;-webkit-box-shadow: 12px 10px 14px #777;box-shadow: 12px 10px 14px #777;">
						<div style="position:absolute;left:0px;top:0px;width:100%;height:10px;border-top: 1px solid #bbbbbb;background-color: #dddddd;cursor:n-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'n');"></div>
						<div style="position:absolute;left:0px;top:0px;width:10px;height:100%;border-left: 1px solid #bbbbbb;background-color: #dddddd;cursor:w-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'w');"></div>	
						<div style="position:absolute;right:0px;top:0px;width:10px;height:100%;border-right: 1px solid #bbbbbb;background-color: #dddddd;cursor:e-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'e');"></div>
						<div style="position:absolute;left:0px;bottom:0px;width:100%;height:10px;border-bottom: 1px solid #bbbbbb;background-color: #dddddd;cursor:s-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 's');"></div>
						<div style="position:absolute;left:0px;top:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:nw-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'nw');"></div>
						<div style="position:absolute;right:0px;top:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:ne-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'ne');"></div>
						<div style="position:absolute;left:0px;bottom:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:sw-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'sw');"></div>
						<div style="position:absolute;right:0px;bottom:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:se-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'se');"></div>						
						<div align="right" style="display:flex;cursor:default; background-color:<? echo BG_DEFAULT; ?>; border: 1px solid #cccccc;height:20px;position:relative;">
							<div id="dragdiv" align="right" onmousedown="dragstart(document.getElementById('overlaydiv'))" style="width: 100%">
							</div>
							<div>
								<a href="javascript:deactivate_overlay();" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH."exit.png"; ?>"></img></a>
							</div>
						</div>						
						<div id="contentdiv" style="background: url(<? echo GRAPHICSPATH; ?>bg.gif);border: 1px solid #cccccc;max-width:<? echo $width; ?>px;height:100%;max-height:<? echo $this->user->rolle->nImageHeight+30; ?>px;position:relative;overflow-y: scroll;overflow-x: auto;">
						<? if($this->overlaymain != '')include(LAYOUTPATH.'snippets/overlay.php'); ?>
						</div>
						<div id="overlayfooter" style="<? if($this->found == 'false' OR $this->formvars['printversion'] != '')echo 'display:none;'; ?>background: url(<? echo GRAPHICSPATH; ?>bg.gif);border: 1px solid #cccccc;max-width:<? echo $width; ?>px;position:relative;">
							<table style="width:100%">
								<tr>
									<td style="width:49%" class="px13">&nbsp;<? echo $this->strLimit; ?>&nbsp;										
										<select name="anzahl" id="anzahl" onchange="javascript:overlay_submit(currentform, false);">
											<? foreach($selectable_limits as $limit){
											if($this->formvars['anzahl'] != '' AND $custom_limit != true AND !in_array($this->formvars['anzahl'], $selectable_limits) AND $this->formvars['anzahl'] < $limit){
												$custom_limit = true;	?>
												<option value="<? echo $this->formvars['anzahl'];?>" selected><? echo $this->formvars['anzahl']; ?></option>
											<? } ?>
											<option value="<? echo $limit; ?>" <? if($this->formvars['anzahl'] == $limit)echo 'selected'?>><? echo $limit; ?></option>
											<? } ?>
										</select>
									</td>
									<td align="center"><div id="savebutton" <? if($this->editable == '')echo 'style="display:none"'; ?>><input type="button" class="button" name="savebutton" value="<? echo $this->strSave; ?>" onclick="save();"></div></td>
									<td style="width:49%" align="right"><a href="javascript:druck();" class="px13"><? echo $this->printversion; ?></a>&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>
				</form>