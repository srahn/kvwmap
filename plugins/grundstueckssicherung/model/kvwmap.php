<?php
	// GUI functions of plugins/grstsich
	// grstsich_create_rechte
	include_once(CLASSPATH . '/Layer.php');
	include_once(PLUGINS . 'grundstueckssicherung/model/Recht.php');

	// set_error_handler('exceptions_error_handler');

	/**
	 * Die Funktion fragt die Geometrie des Objektes mit feature_id im Layer layer_id ab
	 * verschneidet diese mit den Flurstücken und erzeugt daraus neue Rechteobjekte.
	 */
	$GUI->grstsich_create_rechte = function($layer_id, $feature_id, $rechteart_id, $buffer) use ($GUI) {
		$GUI->main = PLUGINS . 'grundstueckssicherung/view/list_rechte.php';

		if (!$layer_id) {
			$msg = 'Der Parameter layer_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		if (!$feature_id) {
			$msg = 'Der Parameter feature_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		if (!$rechteart_id) {
			$msg = 'Der Parameter rechteart_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$pg_obj = new PgObject($GUI, 'grstsich', 'cl_rechtearten');
		$rechteart = $pg_obj->find_by('id', $rechteart_id);

		$layer = Layer::find_by_id($GUI, $layer_id);
		if ($layer === null) {
			$msg = 'Der Layer mit der ID ' . $layer_id . ' wurde nicht gefunden.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}
		$GUI->layer = $layer;

		$pg_obj = new PgObject($GUI, $layer->get('schema'), $layer->get('maintable'));
		$feature = $pg_obj->find_by('id', $feature_id);
		if ($feature === null) {
			$msg = 'Das Objekt mit der ID ' . $feature_id . ' im Layer ' . $layer->get('Name') . ' wurde nicht gefunden.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}
		$GUI->feature = $feature;

		$result = Recht::create_from_geom($GUI, $rechteart, $feature->get('bezeichnung'), $feature->get($layer->get('geom_column')), $buffer, $layer_id, $feature_id);
		if (!$result['success']) {
			return $result;
		}
		$GUI->rechte = Recht::find_by_layer_feature($GUI, $layer_id, $feature_id);

		return array(
			'success' => true,
			'msg' => 'Rechte wurden erfolgreich angelegt.'
		);
	};

?>