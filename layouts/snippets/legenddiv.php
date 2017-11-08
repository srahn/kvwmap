<table width="100%" border="0" cellpadding="0" cellspacing="0" class="legend-switch">
	<tr>
		<td bgcolor="<?php echo BG_DEFAULT ?>" align="left"><?php
			if ($this->user->rolle->hideLegend) {
				if (ie_check()){$display = 'none';}
				?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende zeigen" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_legend.png" border="0"></a><?php
			}
			else {
				?><a id="linkLegend" href="javascript:switchlegend()"><img title="Legende verstecken" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize_legend.png" border="0"></a><?php
			}
		?></td>
	</tr>
</table><?php
if ($show_legend_graphic) { ?>
	<div style="padding-top: 1px;" class="table1">
		<div class="half-width"><div
			id="legend_layer_tab"
			class="legend-tab activ-legend-tab"
			onclick="$('.legend-tab').toggleClass('activ-legend-tab'); $('#legend_graphic').hide(); $('#legend_layer').show();"
		>Themen</div></div><div class="half-width"><div
			id="legend_graphic_tab"
			class="legend-tab"
			onclick="$('.legend-tab').toggleClass('activ-legend-tab'); $('#legend_layer').hide(); $('#legend_graphic').show()"
		>Legende</div>
	</div>
</div><?php
} ?>
<div id="legend_layer">
	<?	if(defined('LAYER_ID_SCHNELLSPRUNG') AND LAYER_ID_SCHNELLSPRUNG != ''){
				include(SNIPPETS.'schnellsprung.php');
			} ?>
	<div id="legendcontrol">
		<a href="index.php?go=reset_querys"><img src="graphics/tool_info.png" border="0" alt="<? echo $strInfoQuery; ?>" title="<? echo $strClearAllQuerys; ?>" width="17" style="vertical-align: bottom;"></a>
		<a href="index.php?go=reset_layers"><img src="graphics/layer.png" border="0" alt="<? echo $strLayerControl; ?>" title="<? echo $strDeactivateAllLayer; ?>" width="20" height="20" style="vertical-align: bottom;"></a>
		<input type="submit" name="neuladen_button" onclick="if(checkForUnsavedChanges()){startwaiting(true);document.GUI.go.value='neu Laden';}" value="<?php echo $strLoadNew; ?>" tabindex="1" style="vertical-align: top; margin-left: 30px">
		<i id="legendOptionsIcon" class="fa fa-bars pointer" style="font-size: 1.1em;margin: 5 5 5 45" title="<? echo $strLegendOptions; ?>" onclick="openLegendOptions();"></i>
		<div id="legendOptions">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:closeLegendOptions(159);" title="Schlie&szlig;en"><img style="border:none" src="graphics/exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding-bottom: 8px">
				<tr>
					<td id="legendOptionsHeader">
						<span class="fett"><? echo $strLegendOptions; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<ul>
							<li><a href="index.php?go=reset_querys"><? echo $strClearAllQuerys; ?></a></li>
							<li><a href="index.php?go=reset_layers"><? echo $strDeactivateAllLayer; ?></a></li>
							<li>
								<span><? echo $strLegendType; ?>:</span><br>
								<label><input type="radio" name="legendtype" value="0" <? if($this->user->rolle->legendtype == 0)echo 'checked'; ?>><? echo $strLegendTypeGroups; ?></label><br>
								<label><input type="radio" name="legendtype" value="1" <? if($this->user->rolle->legendtype == 1)echo 'checked'; ?>><? echo $strLegendTypeAlphabetical; ?></label>
							</li>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<input type="button" onmouseup="saveLegendOptions()" value="<? echo $this->strSave; ?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<? if($this->user->rolle->legendtype == 1){ # alphabetisch sortierte Legende ?>
	<div id="layersearchdiv">
		<? echo $strLayerSearch; ?>
		<input type="text" autocomplete="off" id="layer_search" onkeyup="jumpToLayer(this.value);" value="">
	</div>
	<? } ?>
	<div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop; scrollLayerOptions();">
		<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
		<div onclick="document.GUI.legendtouched.value = 1;" id="legend">
			<? echo $this->legende; ?>
		</div>
		<script type="text/javascript">
			document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
		</script>
	</div>
</div>
<?php
if ($show_legend_graphic) { ?>
	<div id="legend_graphic" style="height:<?php echo $legend_height; ?>"><?php include(SNIPPETS . LEGEND_GRAPHIC_FILE); ?></div><?php
} ?>