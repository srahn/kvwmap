<?php
	// GUI functions of plugins/grstsich
	// grstsich_create_nutzungsrechte
	// grstsich_create_flurstuecksrechte
	// grstsich_order_eigentuemerdaten
	include_once(CLASSPATH . '/Layer.php');
	include_once(PLUGINS . 'grundstueckssicherung/model/Recht.php');

	// set_error_handler('exceptions_error_handler');

	/**
	 * Die Funktion fragt die Geometrie des Objektes mit feature_id im Layer layer_id ab
	 * verschneidet diese mit den Flurstücken und erzeugt daraus neue Rechteobjekte.
	 */
	$GUI->grstsich_create_nutzungsrechte = function($layer_id, $feature_id, $rechteart_id, $buffer) use ($GUI) {
		$GUI->main = PLUGINS . 'grundstueckssicherung/view/list_nutzungsrechte.php';

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
		$label_column = $feature->get($layer->get('labelitem') ?: $layer->get('oid'));

		// Geometrieteile, für die schon Rechte erzeugt wurden entfernen
		$result = $pg_obj->find_by_sql(array(
			'select' => "
				COALESCE(ST_Difference(ST_Transform(pl." . $layer->get('geom_column') . ", 25833), g.geom), pl." . $layer->get('geom_column') . ") geom",
			'from' => "
				" . $layer->get('schema') . "." . $layer->get('maintable') . " pl LEFT JOIN
				(
					SELECT
						ST_union(r.geom) AS geom 
					FROM
						" . $layer->get('schema') . "." . $layer->get('maintable') . " AS ft JOIN
						grstsich.nutzungsrechte r ON ST_Intersects(ST_Transform(ft." . $layer->get('geom_column') . ", 25833), r.geom)
					WHERE
							r.art_id = " . $rechteart->get_id() . "
					GROUP BY ft." . $layer->get('geom_column') . "
				) g ON true
			",
			'where' => "
				pl." . $layer->get('oid') . " = " . $feature_id
		));
		$geom = $result[0]->get('geom');
		$result = Recht::create_nutzungsrechte_from_geom(
			$GUI,
			$rechteart,
			$label_column,
			$geom,
			$buffer,
			$layer_id,
			$feature_id
		);
		if (!$result['success']) {
			return $result;
		}
		$GUI->rechte = Recht::find_nutzungsrechte_by_layer_feature($GUI, $layer_id, $feature_id);

		return array(
			'success' => true,
			'msg' => 'Nutzungsrechte wurden erfolgreich angelegt.'
		);
	};

	$GUI->grstsich_create_flurstuecksrechte = function($vorhabensgebiet_id) use ($GUI) {
		$GUI->main = PLUGINS . 'grundstueckssicherung/view/flurstuecksrechte.php';

		if (!$vorhabensgebiet_id) {
			$msg = 'Der Parameter vorhabensgebiet_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$pg_obj = new PgObject($GUI, 'grstsich', 'vorhabensgebiete');
		$vorhabensgebiet = $pg_obj->find_by('id', $vorhabensgebiet_id);
		if ($vorhabensgebiet === null) {
			$msg = 'Das Vorhabensgebiet mit der ID ' . $vorhabensgebiet_id . ' wurde nicht gefunden.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}
		$GUI->vorhabensgebiet = $vorhabensgebiet;

		$result = Recht::create_flurstuecksrechte_from_vorhabensgebiet(
			$GUI,
			$vorhabensgebiet
		);

		if (!$result['success']) {
			return $result;
		}

		$GUI->flurstuecksrechte = Recht::find_flurstuecksrechte_in_vorhabensgebiet($GUI, $vorhabensgebiet_id);

		return array(
			'success' => true,
			'msg' => 'Flurstücksrechte wurden erfolgreich angelegt.'
		);
	};

	$GUI->grstsich_order_eigentuemerdaten = function($vorhabensgebiet_id) use ($GUI) {
		$GUI->main = PLUGINS . 'grundstueckssicherung/view/order_eigentuemerdaten.php';

		if (!$vorhabensgebiet_id) {
			$msg = 'Der Parameter vorhabensgebiet_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$pg_obj = new PgObject($GUI, 'grstsich', 'vorhabensgebiete');
		$vorhabensgebiet = $pg_obj->find_by('id', $vorhabensgebiet_id);
		if ($vorhabensgebiet === null) {
			$msg = 'Das Vorhabensgebiet mit der ID ' . $vorhabensgebiet_id . ' wurde nicht gefunden.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}
		$GUI->vorhabensgebiet = $vorhabensgebiet;

	};

?>