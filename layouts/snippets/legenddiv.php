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
						<div style="padding-top: 1px;">
							<div class="half-width"><div
								id="legend_layer_tab"
								class="legend-tab activ-legend-tab"
								onclick="$('.legend-tab').toggleClass('activ-legend-tab'); $('#legend_graphic').hide(); $('#legend_layer').show();"
							>Layer</div></div><div class="half-width"><div
								id="legend_graphic_tab"
								class="legend-tab"
								onclick="$('.legend-tab').toggleClass('activ-legend-tab'); $('#legend_layer').hide(); $('#legend_graphic').show()"
							>Legende</div>
						</div>
					</div><?php
					} ?>
					<div id="legend_layer">
						<table class="table1" id="legendTable" style="display: <? echo $display; ?>" cellspacing=0 cellpadding=2 border=0>
						<tr align="left">
							<td><?php
								if(defined('LAYER_ID_SCHNELLSPRUNG') AND LAYER_ID_SCHNELLSPRUNG != ''){
									include(SNIPPETS.'schnellsprung.php');
								} ?>&nbsp;
							<div id="legendcontrol">
								<a href="index.php?go=reset_querys"><img src="graphics/tool_info.png" border="0" alt="<? echo $strInfoQuery; ?>" title="<? echo $strInfoQuery.' | '.$strClearAllQuerys; ?>" width="17"></a>
								<a href="index.php?go=reset_layers"><img src="graphics/layer.png" border="0" alt="<? echo $strLayerControl; ?>" title="<? echo $strLayerControl.' | '.$strDeactivateAllLayer; ?>" width="20" height="20"></a>
								<a
									title="Themensteuerung | Hier klicken um Karte mit gewählten Themen neu zu laden."
									href="#" onclick="startwaiting();document.GUI.go.value='neu Laden';document.GUI.submit();"
								><i class="fa fa-refresh" style="font-size: 22px; color: #a82e2e; margin-left: 2px"></i></a>
							</div>
						<div id="scrolldiv" onscroll="document.GUI.scrollposition.value = this.scrollTop; scrollLayerOptions();" style="height:<?php echo $legendheight; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
							<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
							<div onclick="document.GUI.legendtouched.value = 1;" id="legend">
								<? echo $this->legende; ?>
							</div>
							<script type="text/javascript">
								document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
							</script>
						</div>
							</td>
						</tr>
					</table>
					</div><?php
					if ($show_legend_graphic) { ?>
						<div id="legend_graphic" style="height:<?php echo $legendheight + 25; ?>"><?php include(SNIPPETS . LEGEND_GRAPHIC_FILE); ?></div><?php
					} ?>