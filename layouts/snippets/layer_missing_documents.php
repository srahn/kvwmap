<?
	include_once(CLASSPATH . 'PgObject.php');
	include_once(CLASSPATH . 'LayerAttribute.php');
	/**
	 * Prüfen ob es ein 
	 */


?>
<h2 style="margin-top: 20px">Fehlende Dokumente im Layer <?php echo $this->layer->get('alias'); ?> [<?php echo $this->layer->get('name'); ?>] id: <? echo $this->formvars['selected_layer_id']; ?></h2><?
// echo '<br>Layer-Attribute: ' . print_r($this->layer->data, true); ?>
<div style="text-align: left; margin-left: 25px; width: 1000px; padding: 20px;"><?
	echo '<b>Dokument-Verzeichnis:</b> ' . $this->layer->get('document_path');
	$pg_obj = new PgObject($this, $this->layer->get('schema'), $this->layer->get('maintable'));
	$this->layer->attributes = LayerAttribute::find($this, "layer_id = " . $this->formvars['selected_layer_id']);
	$attributes_with_missing_docs = array();
	foreach ($this->layer->attributes AS $attribute) {
		$missing_documents = array();
		if ($attribute->is_document_attribute() AND $attribute->get('type') != 'bytea') {
			$features = $pg_obj->find_where_ohne_clone(
				$attribute->get('name') . " IS NOT NULL", // where
				$attribute->get('name'), // order
				$this->layer->get('oid') . " AS oid, SPLIT_PART(" . ($attribute->is_array_type() ? "UNNEST(" . $attribute->get('name') . ")" : $attribute->get('name')) . ",'&', 1) AS document" // select
			);
			if (count($features) > 0) {
				echo '<p>Dokument-Einträge in Datenbank:<br>';
				foreach ($features AS $key => $feature) {
					if (!file_exists($feature['document'])) {
						$missing_documents[$key] =  $feature['document'];
					}
				}
				// ToDo: Es muss geprüft werden ob in Options eine Regel zur Bildung des document_path gibt und in diesem Verzeichnis gesucht werden.
				// $files = glob($this->layer->get('document_path') . '*[!thumb].*');
				// foreach ($files AS $file) {
				// 	echo '<br>' . $file;
				// }
				// $missing_documents = array_diff(
				// 	array_map(function($feature) { return $feature['document']; }, $features), // documents of features in database
				// 	$files // files in document_path
				// );
	
				if (count($missing_documents) > 0) {
					$attributes_with_missing_docs[] = array(
						'attribute' => $attribute,
						'features' => $features,
						'missing_documents' => $missing_documents
					);
				}
			}
		}

		if ($attribute->is_datatype_attribute()) {
			$datatype_attributes = $attribute->get_datatype_attributes();
			foreach ($datatype_attributes AS $datatype_attribute) {
				if ($datatype_attribute->is_document_attribute()) {
					$datatype = $attribute->get_datatype();
					// echo '<br>Attribut: ' . $attribute->get('name') . ' Datentyp: ' . $datatype->get('name') . ' Datentypattribut: ' . $datatype_attribute->get('name');
					// Das extrahieren der Dokumentnamen der Feature Datentyp und Array berücksichtigen.
					$features = $pg_obj->find_where(
						$datatype_attribute->get_document_where($this->layer, $attribute), // where
						'document', // order
					  $this->layer->get('oid') . " AS oid," .
						$datatype_attribute->get_document_select($this->layer, $attribute) . " AS document" // select
					);
					$features = array_filter(
						$features,
						function($feature) {
							return !is_null($feature->get('document'));
						}
					);
					// echo '<p>Dokument-Einträge in Datenbank:<br>';
					// foreach ($features AS $feature) {
					// 	echo '<br>' . $feature->get('document');
					// }

					if (count($features) > 0) {
						$files = glob($this->layer->get('document_path') . '*[!thumb].*');
						// foreach ($files AS $file) {
						// 	echo '<br>' . $file;
						// }
						$missing_documents = array_diff(
							array_map(function($feature) { return $feature->get('document'); }, $features), // documents of features in database
							$files // files in document_path
						);
						if (count($missing_documents) > 0) {
							$attributes_with_missing_docs[] = array(
								'attribute' => $attribute,
								'features' => $features,
								'missing_documents' => $missing_documents
							);
						}
					}	
				}
			}
		}
	}

	if (count($attributes_with_missing_docs) > 0) {
		foreach ($attributes_with_missing_docs AS $attribute_with_missing_docs) { ?>
			<p>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="2" style="text-align: left;"><? echo '<p>Dokument-Attribut: ' . $attribute_with_missing_docs['attribute']->get('name') . ': ' .  count($attribute_with_missing_docs['missing_documents']); ?></th>
				</tr>
				</tr>
				<tr>
					<th><? echo $this->layer->get('oid'); ?></th>
					<th>Datei</th>
				</tr><?
				foreach($attribute_with_missing_docs['missing_documents'] AS $key => $missing_document) { ?>
					<tr>
						<td>
							<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $this->layer->get_id(); ?>&value_<? echo $this->layer->get('maintable'); ?>_oid=<? echo $attribute_with_missing_docs['features'][$key]['oid']; ?>&operator_<? echo $this->layer->get('maintable'); ?>_oid==" title="Zeigt den Datensatz an" target="_Datensätze_mit_fehlenden_Dokumenten"><? echo $attribute_with_missing_docs['features'][$key]['oid']; ?></a>
						</td>
						<td style="padding-left: 20px">
							<? echo str_replace($this->layer->get('document_path'), '', $missing_document); ?>
						</td>
					</tr><?
				} ?>
			</table><?php
		}
	}
	else {
		?><br>Es wurden keine fehlenden Dokumente gefunden.<?
	} ?>
</div>