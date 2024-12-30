<?
	if (value_of($this->formvars, 'printversion') == '') { ?>
		<tr id="dataset_operations">
			<td colspan="2"align="left"><?
				if ($layer['connectiontype'] == 6 AND $this->new_entry != true AND $layer['Layer_ID'] > 0){ ?>
					<table border="0" cellspacing="4" cellpadding="0" class="button_background" style="box-shadow: none; border: 1px solid #bbb"><?
						include(SNIPPETS . 'generic_layer_editor_common_part.php'); ?>
							<td>
								<table cellspacing="0" cellpadding="0">
									<tr><?
										if ($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
											<td>
												<a title="<? echo $strDontRememberDataset; ?>" href="javascript:remove_from_clipboard(<? echo $layer['Layer_ID']; ?>);">
													<div class="button nicht_mehr_merken">
														<img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
													</div>
												</a>
											</td><?
										}
										else { ?>
											<td id="merk_link_<? echo $layer['Layer_ID']; ?>">
												<a title="<? echo $strRemember; ?>" href="javascript:add_to_clipboard(<? echo $layer['Layer_ID']; ?>);">
													<div class="button merken"><img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div>
												</a>
											</td><?
										}
										if ($layer['privileg'] == '2') { ?>
											<td id="delete_link_<? echo $layer['Layer_ID']; ?>">
												<a title="<? echo $strdelete; ?>" href="javascript:delete_datasets(<?php echo $layer['Layer_ID']; ?>);">
												<div class="button datensatz_loeschen">
													<img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>">
												</div>
											</td><?
										}
										if ($layer['export_privileg'] != 0) { ?>
											<td>
												<a title="<? echo $strExport; ?>" href="javascript:daten_export(<?php echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);">
													<div class="button datensatz_exportieren"><img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div>
												</a>
											</td><?
										}
										if ($layer['layouts']) { ?>
											<td id="print_link_<? echo $layer['Layer_ID']; ?>">
												<a title="<? echo $strPrint; ?>" href="javascript:print_data(<?php echo $layer['Layer_ID']; ?>);">
													<div class="button drucken"><img	src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div>
												</a>
											</td><?
										}
										if ($privileg != '') { ?>
											<td id="zoom_link_<? echo $layer['Layer_ID']; ?>" style="padding: 0 0 0 15px">
												<a
													title="<? echo $strzoomtodatasets; ?>"
													href="javascript:zoomto_datasets(<?php echo $layer['Layer_ID']; ?>, '<? echo $geom_tablename; ?>', '<? echo $columnname; ?>');"
												><div class="button zoom_highlight"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div>
												</a>
											</td>
											<td id="classify_link_<? echo $layer['Layer_ID']; ?>" style="padding: 0 5px 0 0">
												<select style="width: 130px" name="klass_<?php echo $layer['Layer_ID']; ?>">
													<option value=""><? echo $strClassify; ?>:</option><?
													for($j = 0; $j < count($layer['attributes']['name']); $j++){
														if ($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']) {
															echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
														}
													} ?>
												</select>
											</td><?
										} ?>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="display:none">
							<td height="23" colspan="3">
								&nbsp;&nbsp;&bull;&nbsp;<a href="javascript:showcharts(<?php echo $layer['Layer_ID']; ?>);"><? echo $strCreateChart; ?></a>
							</td>
						</tr>
						<tr id="charts_<?php echo $layer['Layer_ID']; ?>" style="display:none">
							<td></td>
							<td>
								<table>
									<tr>
										<td colspan="2">
											&nbsp;&nbsp;<select name="charttype_<?php echo $layer['Layer_ID']; ?>" onchange="change_charttype(<?php echo $layer['Layer_ID']; ?>);">
												<option value="bar">Balkendiagramm</option>
												<option value="mirrorbar">doppeltes Balkendiagramm</option>
												<option value="circle">Kreisdiagramm</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											&nbsp;&nbsp;Beschriftung:
										</td>
										<td>
											<select style="width:133px" id="" name="chartlabel_<?php echo $layer['Layer_ID']; ?>" ><?
												for ($j = 0; $j < count($layer['attributes']['name']); $j++){
													if ($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
														echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											&nbsp;&nbsp;Wert:
										</td>
										<td>
											<select style="width:133px" name="chartvalue_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
												<option value="">--- Bitte Wählen ---</option><?
												for ($j = 0; $j < count($layer['attributes']['name']); $j++){
													if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
														echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
													}
												} ?>
											</select>
										</td>
									</tr>
									<tr id="split_<?php echo $layer['Layer_ID']; ?>" style="display:none">
										<td>
											&nbsp;&nbsp;Trenn-Attribut:
										</td>
										<td>
											<select style="width:133px" name="chartsplit_<?php echo $layer['Layer_ID']; ?>" onchange="create_chart(<?php echo $layer['Layer_ID']; ?>);">
												<option value="">--- Bitte Wählen ---</option><?
												for ($j = 0; $j < count($layer['attributes']['name']); $j++){
													if($layer['attributes']['name'][$j] != $layer['attributes']['the_geom']){
														echo '<option value="'.$layer['attributes']['name'][$j].'">'.$layer['attributes']['alias'][$j].'</option>';
													}
												} ?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table><?
				} ?>
			</td>
		</tr><?
	}
?>