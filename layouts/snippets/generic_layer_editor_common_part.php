<tr>
	<td colspan="2"><?php $maxRows = ($this->formvars['anzahl'] > MAXQUERYROWS ? $this->formvars['anzahl'] : MAXQUERYROWS); ?>
		<i><? echo $layer['Name']; ?></i>:
		<a href="javascript:selectall(<? echo $layer['layer_id']; ?>);">
			<span id="selectDatasetsLinkText_<? echo $layer['layer_id']; ?>"><?
			if ($layer['count'] > $maxRows) {
				echo $strSelectAllShown;
			} else {
				echo $strSelectAll;
			} ?>
		</span>
		<span id="deselectDatasetsLinkText_<? echo $layer['layer_id']; ?>" class="hidden"><?
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
		<td style="padding: 5 10 0 0;"><?
			$numSearchResultsText = $layer['count']; ?>
			<input type="radio"
				id="all_<? echo $layer['layer_id']; ?>_1"
				name="all_<? echo $layer['layer_id']; ?>"
				onchange="update_buttons(this.value, <? echo $layer['layer_id']; ?>);"
				value=""
				checked="true"
			>
			<label for="all_<? echo $layer['layer_id']; ?>_1"><? echo $strSelectedDatasets; ?></label> (<label id="selected_count_<? echo $layer['layer_id']; ?>">0</label>)
			<br>
			<input type="radio"
				id="all_<? echo $layer['layer_id']; ?>_2"
				name="all_<? echo $layer['layer_id']; ?>"
				onchange="update_buttons(this.value, <? echo $layer['layer_id']; ?>);"
				value="true"
			>
			<label for="all_<? echo $layer['layer_id']; ?>_2"><? echo $strAllDatasets . ' (' . $numSearchResultsText . ')'; ?></label>
		</td><?
	}
	else { ?>
		<td style="padding: 5 0 0 0;"><? echo $strSelectedDatasets . ':'; ?></td><?
	} ?>