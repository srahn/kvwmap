<?php
	include(LAYOUTPATH . 'languages/layer_formular_' . $this->user->rolle->language . '.php');
	$write_options = array(
		'data' => $strWriteMapserverTemplatesOption1,
		'generic' => $strWriteMapserverTemplatesOption2
	);
?>
<h2 style="margin-top: 20px; margin-bottom: 10px">MapServer Template-Dateien schreiben</h2><?php
if ($this->formvars['go_plus'] == '') { ?>
	Diese Layer haben eine Einstellung zum Schreiben eines MapServer-Templates:
	<table border="1" cellpadding="2" cellspacing="0" style="margin-top: 20px">
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>write_mapserver_templates</th>
		</tr><?php
	foreach($this->layers AS $layer) { ?>
		<tr>
			<td><? echo $layer->get('Layer_ID'); ?></td>
			<td style="text-align: left"><a href="index.php?go=Layereditor&selected_layer_id=<? echo $layer->get('Layer_ID'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $layer->get('Name'); ?></a></td>
			<td><? echo $write_options[$layer->get('write_mapserver_templates')]; ?></td></tr><?
	} ?>
	</table>
	<input type="checkbox" value="Tabelle" name="ansicht" checked/> Darstellung im Template in Tabellenform
	<input type="submit" value="Erzeugen" name="go_plus" style="margin-top: 20px; margin-left: 5px; margin-bottom: 20px" onclick="$('#waitingdiv').show()"/>
	<input type="hidden" value="write_mapserver_templates" name="go"/><?php
}
else { ?>
	Für diese Layer wurden MapServer-Templates geschrieben:
	<table border="1" cellpadding="2" cellspacing="0" style="margin-top: 20px">
		<tr><th>Layer</th><th>geschrieben</th></tr><?php
		foreach($this->layers AS $layer) { ?>
			<tr><td style="text-align: left"><a href="index.php?go=Layereditor&selected_layer_id=<? echo $layer->get('Layer_ID'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $layer->get('Name'); ?></a></td><td style="text-align: center"><i class="fa fa-check" style="color: darkgreen"></i></td></tr><?
		} ?>
	</table>
	<p><p><?
} ?>