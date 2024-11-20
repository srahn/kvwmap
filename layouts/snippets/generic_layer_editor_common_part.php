<tr>
	<td colspan="2"><?php $maxRows = ($this->formvars['anzahl'] > MAXQUERYROWS ? $this->formvars['anzahl'] : MAXQUERYROWS); ?>
		<i><? echo $layer['Name']; ?></i>:
		<a href="javascript:selectall(<? echo $layer['Layer_ID']; ?>);">
			<span id="selectDatasetsLinkText_<? echo $layer['Layer_ID']; ?>"><?
			if ($layer['count'] > $maxRows) {
				echo $strSelectAllShown;
			} else {
				echo $strSelectAll;
			} ?>
		</span>
		<span id="deselectDatasetsLinkText_<? echo $layer['Layer_ID']; ?>" class="hidden"><?
			if ($layer['count'] > $maxRows) {
				echo $strDeselectAllShown;
			} else {
				echo $strDeselectAll;
			} ?>
		</span>
		</a>
	</td>
</tr>
<tr><?
	if ($layer['export_privileg'] != 0) {
		 ?>
		<td style="padding: 5 0 0 0;"><?
			$numSearchResultsText = ($layer['count'] > $maxRows ? '&nbsp;(' . $layer['count'] . ')' : ''); ?>
			<select
				id="all_<? echo $layer['Layer_ID']; ?>"
				name="all_<? echo $layer['Layer_ID']; ?>"
				onchange="update_buttons(this.value, <? echo $layer['Layer_ID']; ?>);"
			>
				<option value=""><? echo $strSelectedDatasets; ?>:</option>
				<option value="true"><? echo $strAllDatasets . ':' . $numSearchResultsText; ?></option>
			</select>
		</td><?
	}
	else { ?>
		<td style="padding: 5 0 0 0;"><? echo $strSelectedDatasets . ':'; ?></td><?
	} ?>
</tr>