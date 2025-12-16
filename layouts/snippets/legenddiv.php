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
	<div class="button_background" style="box-shadow: none; border-bottom: 1px solid #bbb">		<?	
		if (!$this->simple_legend AND defined('LAYER_ID_SCHNELLSPRUNG') AND LAYER_ID_SCHNELLSPRUNG != '') {
			include(SNIPPETS.'schnellsprung.php');
		}
		if ($this->user->rolle->layer_selection) {
			include_once(CLASSPATH.'FormObject.php');
			$ret = $this->user->rolle->getLayerComments(NULL, $this->user->id);
      $layer_selections = $ret[1];
			echo '<div id="layer_selection_div">' . $this->strLayerSelection . ':';
			echo FormObject::createSelectField(
				'layer_selection',
				array_map(
					function($layer_selection) {
						return array(
							'value' => $layer_selection['id'],
							'output' => $layer_selection['name']
						);
					},
					$layer_selections
				),
				$this->user->rolle->layer_selection,
				1,
				'', 
				"window.location.href='index.php?go=Layerauswahl_Laden&id=' + this.value",
				'',
				'',
				'',
				' - alle Themen - '
			);
			echo '</div>';
		}
		?>
	<div id="legendcontrol">
		<? if(!$this->simple_legend){
					if ($this->user->rolle->singlequery < 2) { ?>
		<a href="index.php?go=reset_querys">
			<div>
				<div class="button tool_info" title="<? echo $strClearAllQuerys; ?>"></div>
			</div>
		</a>
				<? } ?>
		<a href="index.php?go=reset_layers">
			<div>
				<div class="button layer" title="<? echo $strDeactivateAllLayer; ?>"></div>
			</div>
		</a>
		<? } ?>

		<div title="<?php echo $strLoadNew; ?>" style="flex-grow: 3; text-align: -webkit-center;">
			<a href="javascript:void(0)" name="neuladen_button" onclick="neuLaden();">
				<div class="button"><i class="fa fa-refresh" style="font-size: 28px; margin: 5px; color: #5c88a8;" aria-hidden="true"></i></div>
			</a>
		</div>

		<? if(!$this->simple_legend){ ?>

			<div title="<? echo $strLegendTypeSwitch; ?>">
				<a href="javascript:void(0)" onclick="changeLegendType();">
					<div id="legendtype_switch" class="button <? echo ($this->user->rolle->legendtype == 0 ? 'in_groups' : 'alphabetical'); ?>"></div>
				</a>
			</div>

		<div id="legendOptionsIcon" title="<? echo $strDrawingOrder; ?>">
			<a href="javascript:void(0)" onclick="toggleDrawingOrderForm();">
				<div class="button drawingorder"></div>
			</a>
		</div>
		
		<div id="legendOptions">
			<div style="position: absolute;top: 0px;right: 0px"><a href="javascript:toggleDrawingOrderForm();" title="Schlie&szlig;en"><img style="border:none" src="graphics/exit2.png"></img></a></div>
			<table cellspacing="0" cellpadding="0" style="padding: 0 5 8 0">
				<tr>
					<td id="legendOptionsHeader">
						<span class="fett"><? echo $strDrawingOrder; ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<div style="overflow-y: auto; max-height: <? echo ($legend_height - 80); ?>px">
							<ul>
								<li>
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
									<input type="button" onmouseup="resetDrawingorder()" value="<? echo $this->strReset; ?>">
								</td>
								<td>
									<input type="button" onmouseup="saveDrawingorder()" value="<? echo $this->strSave; ?>">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<? } ?>		
	</div>
	<div id="layersearchdiv" class="<? if($this->user->rolle->legendtype == 0){echo 'hidden';} ?>">
		<? echo $strLayerSearch; ?>
		<input type="text" autocomplete="off" id="layer_search" onkeyup="jumpToLayer(this.value);" value="">
	</div>
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
<input type="hidden" name="legendtype" value="<? echo $this->user->rolle->legendtype; ?>">