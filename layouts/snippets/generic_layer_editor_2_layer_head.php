<?
if ($this->new_entry != true AND value_of($this->formvars, 'printversion') == '') {
	if (!$this->user->rolle->visually_impaired) { ?>
		<thead class="gle"><?
	} ?>
	<tr>
		<th class="datensatz_header" colspan="20"> 
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr><?
					if ($layer['connectiontype'] == 6 AND $layer['Layer_ID'] > 0) { ?>
						<td style="padding: 3px">
								<input
								id="<? echo $layer['Layer_ID'] . '_' . $k; ?>"
								type="checkbox"
								class="<? if (value_of($layer['attributes'], 'Editiersperre') AND $layer['shape'][$k][$layer['attributes']['Editiersperre']] == 't') { echo 'no_edit'; } ?>"
								name="check;<? echo $layer['attributes']['table_alias_name'][$layer['maintable']].';'.$layer['maintable'].';'.$layer['shape'][$k][$layer['maintable'].'_oid'].';'.$layer['Layer_ID']; ?>"
							>&nbsp;<span style="color:<? echo TXT_GLEHEADER; ?>;"><? echo $strSelectThisDataset; ?></span><?
							if (value_of($layer['shape'][$k], value_of($layer['attributes'], 'Editiersperre')) == 't') { ?>
								<span class="editier_sperre fa-stack" title="Dieser Datensatz ist zur Bearbeitung gesperrt">
									<i class="fa fa-pencil fa-stack-1x" style="font-size:15px"></i>
									<i class="fa fa-ban fa-stack-1x fa-flip-horizontal" style="color: tomato;font-size:29px"></i>
								</span>
	<?					} ?>
						</td><?
					}
					if ($layer['identifier_text'] != '') {	?>
						<td>
							<span class="identifier_text">
							<?
								$identifier_attributes = explode(' ', $layer['identifier_text']);
								for ($p = 0; $p < count($identifier_attributes); $p++) {
									$output[$p] = $identifier_attributes[$p];
									for ($j = 0; $j < count($layer['attributes']['name']); $j++) {
										if ($identifier_attributes[$p] == $layer['attributes']['name'][$j]) {
											$output[$p] = $layer['shape'][$k][$identifier_attributes[$p]];
										}
									}
								}
								echo implode(' ', $output);
							?>
							</span>
						</td>
<?				}
?>				<td align="right">
						<table cellspacing="0" cellpadding="0" class="button_background" style="border-left: 1px solid #bbb">
							<tr><?
								if ($this->formvars['go'] == 'Zwischenablage' OR $this->formvars['go'] == 'gemerkte_Datensaetze_anzeigen'){ ?>
									<td><a title="<? echo $strDontRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);remove_from_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button nicht_mehr_merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><? 
								}
								elseif ($layer['connectiontype'] == 6 AND $layer['Layer_ID'] > 0) { ?>
									<td><a title="<? echo $strRememberDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);add_to_clipboard(<? echo $layer['Layer_ID']; ?>);"><div class="button merken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><?
								}
								if ($layer['privileg'] > '0') { ?>
									<td><a onclick="checkForUnsavedChanges(event);" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);use_for_new_dataset(<? echo $layer['Layer_ID']; ?>)" title="<? echo $strUseForNewDataset; ?>"><div class="button use_for_dataset"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td>
									<td><a onclick="checkForUnsavedChanges(event);" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);dublicate_dataset(<? echo $layer['Layer_ID']; ?>)" title="<? echo $strCopyDataset; ?>"><div class="button copy_dataset"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><?
								}
								if ($layer['connectiontype'] == 6 AND $layer['export_privileg'] != 0) { ?>
									<td><a onclick="checkForUnsavedChanges(event);" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);daten_export(<? echo $layer['Layer_ID']; ?>, <? echo $layer['count']; ?>);" title="<? echo $strExportThis; ?>"><div class="button datensatz_exportieren"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><?
								} 
								if ($layer['layouts']) { ?>
									<td><a onclick="checkForUnsavedChanges(event);" title="<? echo $strPrintDataset; ?>" href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);print_data(<?php echo $layer['Layer_ID']; ?>);"><div class="button drucken"><img  src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></a></td><?
								}
								if ($layer['privileg'] == '2' AND value_of($layer['shape'][$k], value_of($layer['attributes'], 'Editiersperre')) != 't') { ?>
									<td>
										<a
											onclick="checkForUnsavedChanges(event);"
											href="javascript:select_this_dataset(<? echo $layer['Layer_ID']; ?>, <? echo $k; ?>);delete_datasets(<?php echo $layer['Layer_ID']; ?>);"
											title="<? echo $strDeleteThisDataset; ?>"
										><div class="button datensatz_loeschen"><img  src="<? echo GRAPHICSPATH . 'leer.gif'; ?>"></div></a>
									</td><?
								} ?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</th>
	</tr>
	<?
	if (!$this->user->rolle->visually_impaired) { ?>
		</thead><?
	}
}
?>