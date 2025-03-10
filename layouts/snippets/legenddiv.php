<? if(!$this->simple_legend){ ?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="legend_switch">
		<tr>
			<td align="left"><?php
				if ($this->user->rolle->hideLegend) {	?>
					<a id="linkLegend" href="javascript:switchlegend()"><img title="Legende zeigen" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>maximize_legend.png" border="0"></a><?php
				}
				else { ?>
					<a id="linkLegend" href="javascript:switchlegend()"><img title="Legende verstecken" id="LegendMinMax" src="<?php  echo GRAPHICSPATH; ?>minimize_legend.png" border="0"></a><?php
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
	}
} ?>
<div id="legend_layer">
	<div class="button_background" style="box-shadow: none; border-bottom: 1px solid #bbb">
	<?	if(!$this->simple_legend AND defined('LAYER_ID_SCHNELLSPRUNG') AND LAYER_ID_SCHNELLSPRUNG != ''){
				include(SNIPPETS.'schnellsprung.php');
			} ?>
	<div id="legendcontrol">
		<? if(!$this->simple_legend){
					if ($this->user->rolle->singlequery < 2) { ?>
		<a href="index.php?go=reset_querys">
			<div>
				<div class="button tool_info" style="width: 26px; height: 26px" title="<? echo $strClearAllQuerys; ?>"></div>
			</div>
		</a>
				<? } ?>
		<a href="index.php?go=reset_layers">
			<div>
				<div class="button layer" style="width: 26px; height: 26px" title="<? echo $strDeactivateAllLayer; ?>"></div>
			</div>
		</a>
		<? } ?>
		<input type="button" name="neuladen_button" onclick="neuLaden();" value="<?php echo $strLoadNew; ?>" tabindex="1" style="height: 27px; vertical-align: top; margin: auto; transform: <? if(!$this->simple_legend){ echo 'translateX(15px);'; } ?>">
		<? if(!$this->simple_legend){ ?>
		<i id="legendOptionsIcon" class="fa fa-bars pointer button" title="<? echo $strLegendOptions; ?>" onclick="openLegendOptions();"></i>
		<div id="legendOptions">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:closeLegendOptions(159);" title="Schlie&szlig;en"><img style="border:none" src="graphics/exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding: 0 5 8 0">
				<tr>
					<td id="legendOptionsHeader">
						<span class="fett"><? echo $strLegendOptions; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<div style="overflow-y: auto; max-height: <? echo ($legend_height - 80); ?>px">
							<ul>
								<li>
									<span><? echo $strLegendType; ?>:</span><br>
									<label><input type="radio" name="legendtype" value="0" <? if($this->user->rolle->legendtype == 0)echo 'checked'; ?>><? echo $strLegendTypeGroups; ?></label><br>
									<label><input type="radio" name="legendtype" value="1" <? if($this->user->rolle->legendtype == 1)echo 'checked'; ?>><? echo $strLegendTypeAlphabetical; ?></label>
								</li>
								<li>
									<a href="index.php?go=delete_rollenlayer&type=import"><? echo $strRemoveAllImportLayers; ?></a>
								</li>
								<li>
									<a href="index.php?go=delete_rollenlayer&type=search"><? echo $strRemoveAllSearchLayers; ?></a>
								</li>
								<li>
									<a href="javascript:toggleDrawingOrderForm();"><? echo $strDrawingOrder; ?></a>
									<div id="drawingOrderForm"></div>
								</li>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<input type="button" onmouseup="resetLegendOptions()" value="<? echo $this->strReset; ?>">
								</td>
								<td>
									<input type="button" onmouseup="saveLegendOptions()" value="<? echo $this->strSave; ?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<? } ?>		
	</div>
	<? if($this->user->rolle->legendtype == 1){ # alphabetisch sortierte Legende ?>
	<div id="layersearchdiv">
		<? echo $strLayerSearch; ?>
		<input type="text" autocomplete="off" id="layer_search" onkeyup="jumpToLayer(this.value);" value="">
	</div>
	<? } ?>
	</div>
	<div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop; scrollLayerOptions();">
		<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
		<div onmousedown="document.GUI.legendtouched.value = 1;" id="legend">
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

<input type="hidden" name="delete_rollenlayer" value="">
<input type="hidden" name="delete_rollenlayer_type" value="">