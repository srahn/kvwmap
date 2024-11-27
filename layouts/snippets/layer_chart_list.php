<?php
	include(LAYOUTPATH . 'languages/layer_chart_' . $this->user->rolle->language . '.php');
?>
<h2 style="margin: 20px"><? echo $strLayerChartTitle; ?></h2><?

if ($this->formvars['layer_id'] == '' AND $this->formvars['selected_layer_id'] == '') {
	$this->Fehlermeldung = $strLayerChartMissingLayerId;
}
elseif (!$this->layer) {
	$this->Fehlermeldung = $strLayerChartMissingLayer;
}
else {
	$this->Fehlermeldung = '';
}

if ($this->Fehlermeldung != '') {
	include('Fehlermeldung.php');
}
else { ?>
	<h3>Layer: <? echo (empty($this->layer->get('Name_' . $this->user->rolle->language)) ? $this->layer->get('Name') : $this->layer->get('Name_' . $this->user->rolle->language)); ?></h3>
	<script>
		function show_layer_editor(event) {
			event.preventDefault();
			window.location = 'index.php?go=Layereditor&selected_layer_id=<? echo $this->layer->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>#query_parameter';
		}

		function layer_chart_delete(id) {
			Check = confirm('<? echo $strDeleteWarningMessage; ?>');
			if (Check == true) {
				$.ajax({
					url: 'index.php',
					data: {
						go: 'layer_chart_Loeschen',
						id: id,
						csrf_token: '<? echo $_SESSION['csrf_token']; ?>'
					},
					error: function(response) {
						message(response.msg);
					},
					success: function(response) {
						$('#layer_chart_' + id).fadeOut(1000);
					}
				});
			}
		}
	</script>
	<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<th><a href="index.php?go=layer_charts_Anzeigen&order=id&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strID; ?></a></th>
						<th><a href="index.php?go=layer_charts_Anzeigen&order=title&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Titel</a></th>
						<th><a href="index.php?go=layer_charts_Anzeigen&order=type&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Typ</th>
						<th><a href="index.php?go=layer_charts_Anzeigen&order=aggregate_function&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Funktion</a></th>
						<td colspan="2" align="right"><a class="btn btn-new" href="index.php?go=layer_chart_Editor&layer_id=<? echo $this->layer->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i titel="<? echo $strCreateNewLayerChartTitle; ?>" class="fa fa-plus" style="color: white; margin-bottom: 10px"></i></a></td>
					</tr><?php
					foreach ($this->layer->charts AS $chart) { ?>
						<tr id="layer_chart_<?php echo $chart->get('id'); ?>" onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
							<td>
								<?php echo $chart->get('id'); ?>
							</td>
							<td>
								<?php echo $chart->get('title') ?>
							</td>
							<td>
								<span><?php echo $chart->get('type'); ?></span>
							</td>
							<td>
								<span><?php echo $chart->get('aggregate_function'); ?></span>
							</td>
							<td align="right">
								<a
									title="<?php echo $this->strChange; ?>"
									href="index.php?go=layer_chart_Editor&id=<?php echo $chart->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"
								><i class="fa fa-pencil" style="padding: 3px"></i></a>
							</td>
							<td>
								<span
									title="<?php echo $this->strDelete; ?>"
									onclick="layer_chart_delete(<?php echo $chart->get('id'); ?>);"
									style="padding: 3px"
								><i class="fa fa-trash"></i></span>
							</td>
						</tr><?php
					} ?>
				</table>
			</td>
		</tr>
	</table>
	<button type="button" onclick="show_layer_editor(event);"><? echo $strLayerChartEditorButton; ?></button><?
} ?>