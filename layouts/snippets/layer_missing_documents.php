<?
	include_once(CLASSPATH . 'PgObject.php');
	include_once(CLASSPATH . 'LayerAttribute.php');
?>
<h2 style="margin-top: 20px">Fehlende Dokumente im Layer <?php echo $this->formvars['selected_layer_id']; ?></h2><?
// echo '<br>Layer-Attribute: ' . print_r($this->layer->data, true); ?>
<div style="text-align: left; margin-left: 25px; width: 1000px; padding: 20px;"><?
	echo '<b>Dokument-Verzeichnis:</b> ' . $this->layer->get('document_path');
	$this->layer->document_attributes = LayerAttribute::find($this, "layer_id = " . $this->formvars['selected_layer_id'] . " AND form_element_type = 'Dokument'");
	foreach ($this->layer->document_attributes AS $document_attribute) {
		$pg_obj = new PgObject($this, $this->layer->get('schema'), $this->layer->get('maintable'));
		$features = $pg_obj->find_where(
			$document_attribute->get('name') . " IS NOT NULL", // where
			$document_attribute->get('name'), // order
			$this->layer->get('oid') . " AS oid, SPLIT_PART(" . (strpos($document_attribute->get('type'), '_') === 0 ? "UNNEST(" . $document_attribute->get('name') . ")" : $document_attribute->get('name')) . ",'&', 1) AS document" // select
		);
		// echo '<p>Dokument-Einträge in Datenbank:<br>';
		// foreach ($features AS $feature) {
		// 	echo '<br>' . $feature->get('document');
		// }
		$files = glob($this->layer->get('document_path') . '*[!thumb].*');
		// foreach ($files AS $file) {
		// 	echo '<br>' . $file;
		// }
		$missing_documents = array_diff(
			array_map(function($feature) { return $feature->get('document'); }, $features), // documents of features in database
			$files // files in document_path
		); ?>
		<p>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="2" style="text-align: left;"><? echo '<p>Dokument-Attribut: ' . $document_attribute->get('name'); ?></th>
			</tr>
			</tr>
			<tr>
				<th><? echo $this->layer->get('oid'); ?></th>
				<th>Datei</th>
			</tr><?
			foreach($missing_documents AS $key => $missing_document) { ?>
			<tr>
				<td><? echo $features[$key]->get('oid'); ?></td>
				<td style="padding-left: 20px"><? echo str_replace($this->layer->get('document_path'), '', $missing_document); ?></td>
			</tr><?
		} ?>
		</table><?
	}
?>
</div>