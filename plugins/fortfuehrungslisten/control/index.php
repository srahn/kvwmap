<?php
include_once(CLASSPATH . 'PgObject.php');
include(PLUGINS . 'fortfuehrungslisten/model/loader.php');
include(PLUGINS . 'fortfuehrungslisten/model/ff_auftrag.php');
include(PLUGINS . 'fortfuehrungslisten/model/fortfuehrungsfall.php');

function go_switch_fortfuehrungslisten($go){
	global $GUI;
	switch($go) {
		case 'auftragsdatei_loeschen': {
			$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
			if (empty($ff_auftrag_id)) {
				$GUI->messages[] = array(
					'msg' => 'Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!',
					'type' => 'error'
				);
			}
			else {
				$ff_auftrag = Fortfuehrungsauftrag::find_by_id($GUI, 'id', $ff_auftrag_id);
				$result = $ff_auftrag->auftragsdatei_loeschen();
			}
			$GUI->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
			$GUI->formvars['operator_ff_auftrag_id'] = '=';
			$GUI->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
			$GUI->GenerischeSuche_Suchen();
		} break;

		case 'lade_fortfuehrungsfaelle': {
			$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
			if (empty($ff_auftrag_id)) {
				$GUI->messages[] = array(
					'type' => 'error',
					'msg' => 'Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!'
				);
			}
			else {
				$ff_auftrag = Fortfuehrungsauftrag::find_by_id($GUI, 'id', $ff_auftrag_id);
				if ($ff_auftrag->get('auftragsdatei') == '') {
					$GUI->messages[] = array(
						'type' => 'error',
						'msg' => 'Sie müssen erst eine Auftragsdatei zum Fortführungsauftrag hochladen!'
					);
				}
				else {
					$GUI->loader = new NASLoader($GUI);
					$result = $GUI->loader->load_fortfuehrungsfaelle($ff_auftrag);
					$GUI->messages = array_merge($GUI->messages, $GUI->loader->messages);

					if ($result['success']) {
						$result = $ff_auftrag->auftragsdatei_loeschen();
					}
				}
			}

			$GUI->messages = array_merge($GUI->messages, $ff_auftrag->messages);

			$GUI->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
			$GUI->formvars['operator_ff_auftrag_id'] = '=';
			$GUI->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
			$GUI->GenerischeSuche_Suchen();
		}	break;

		/*
		* Liefert das Formular für die Suche nach Fortfuehrungsnachweisen aus
		*/
		case 'fortfuehrungslisten_fn_suche': {
			$layer_id = LAYER_ID_FF_AUFTRAG;
			$mapdb = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
			$layerdb = $mapdb->getlayerdatabase($layer_id, $GUI->Stelle->pgdbhost);
			$layerdb->setClientEncoding();
			$GUI->attributes = $mapdb->read_layer_attributes($layer_id, $layerdb, $privileges['attributenames']);
			$GUI->{'attributes'.$m} = $mapdb->add_attribute_values($GUI->attributes, $layerdb, array(), true, $GUI->Stelle->id);
			$GUI->attributes = fortfuehrungslisten_add_user_names($GUI);
			$GUI->attributes = fortfuehrungslisten_add_flurstuecke($GUI);
			$GUI->attributes = fortfuehrungslisten_add_fluren($GUI);

			?><pre style="text-align: left"><?php #print_r($GUI->attributes); ?></pre><?php
			$GUI->main = PLUGINS . 'fortfuehrungslisten/view/suche_fortfuehrungsnachweise.php';
			$GUI->output();
		} break;

		/*
		* Sucht nach Fortführungsnachweisen mit den Attributen
		* - gemkgnr
		* - flurstueckskennzeichen
		* - zeigtauf
		* Verwendet anschließend die gefundenen ff_auftrag_ids
		* als Bedingung für die Suche mit den weiteren Suchparametern
		*/
		case 'fortfuehrungslisten_fn_suche_Suchen': {
			$where = array();

			if ($GUI->formvars['value_flurstueckskennzeichen'] != '') {
				$where[] = "f.flurstueckskennzeichen = '" . $GUI->formvars['value_flurstueckskennzeichen'] . "'";
			}

			if ($GUI->formvars['value_altesneues'] != '') {
				$where[] = "zeigtauf = '" . $GUI->formvars['value_altesneues'] . "'";
			}

			# Wenn keine Where-Bedingung gesetzt wurde, weiter mit Suche nach den anderen Parametern
			if (!empty($where)) {
				$sql = "
					SELECT DISTINCT
						a.id
					FROM
						fortfuehrungslisten.ff_auftraege a JOIN
						fortfuehrungslisten.ff_flurstuecke_der_auftraege f ON (a.id = f.ff_auftrag_id)
					WHERE
						" . implode(' AND ', $where) . "
				";
				$result = $GUI->pgdatabase->execSQL($sql, 4, 0);
				$ids = array();
				while ($rs = pg_fetch_assoc($result[1])) {
					$ids[] = $rs['id'];
				}

				?><pre style="text-align: left"><?php #print_r($ids); ?></pre><?php

				# Wenn nichts gefunden wurde ($ids leer) wird durch die Suchbedingung
				# ff_auftrag_id IN (-1) erzwungen, dass GenerischeSuche_Suchen auch kein
				# Suchergebnis hervorbringt.
				$GUI->formvars['operator_ff_auftrag_id'] = 'IN';
				$GUI->formvars['value_ff_auftrag_id'] = (empty($ids) ? -1 : implode('|', $ids));
			}

			if ($GUI->formvars['value_gemkgschl'] != '') {
				$GUI->formvars['operator_gemkgnr'] = '=';
				$GUI->formvars['value_gemkgnr'] = $GUI->formvars['value_gemkgschl'];
			}

			?><pre style="text-align: left"><?php #print_r($GUI->formvars); ?></pre><?php

			$GUI->GenerischeSuche_Suchen();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

function fortfuehrungslisten_add_user_names($GUI) {
	$field_name = 'user_name';
	$sql = "
		SELECT DISTINCT
			user_name AS {$field_name}
		FROM
			fortfuehrungslisten.ff_auftraege
		ORDER BY
			{$field_name}
	";
	$result = $GUI->pgdatabase->execSQL($sql, 4, 0);
	$user_names = array_map(
		function($row) {
			return $row[array_keys($row)[0]];
		},
		pg_fetch_all($result[1])
	);
	$GUI->attributes['enum_value'][$GUI->attributes['indizes'][$field_name]] = $user_names;
	$GUI->attributes['enum_output'][$GUI->attributes['indizes'][$field_name]] = $user_names;
	return $GUI->attributes;
}

function fortfuehrungslisten_add_flurstuecke($GUI) {
	$field_name = 'flurstueckskennzeichen';
	$GUI->attributes['indizes'][$field_name] = count($GUI->attributes['indizes']);
	$sql = "
		SELECT DISTINCT
			flurstkennz AS value,
			alkis.asflurstkennz(flurstkennz) AS output,
			substr(flurstkennz, 1, 2) land,
			substr(flurstkennz, 3, 4) gemarkung,
			ltrim(substr(flurstkennz, 7, 3), '0') flur,
			ltrim(substr(flurstkennz, 10, 5), '0') zaehler,
			ltrim(substr(flurstkennz, 16, 3), '0') nenner
		FROM
			(
				SELECT
					unnest(zeigtaufaltesflurstueck) AS flurstkennz
				FROM
					ff_faelle

	  		UNION

	  		SELECT
					unnest(zeigtaufneuesflurstueck) AS flurstkennz
				FROM
					ff_faelle
		) AS foo ORDER BY flurstkennz
	";
	$result = $GUI->pgdatabase->execSQL($sql, 4, 0);
	while ($rs = pg_fetch_assoc($result[1])) {
		$GUI->attributes['enum_value'][$GUI->attributes['indizes'][$field_name]][] = $rs['value'];
		$GUI->attributes['enum_output'][$GUI->attributes['indizes'][$field_name]][] = $rs['output'];
	}

	return $GUI->attributes;
};

function fortfuehrungslisten_add_fluren($GUI) {
	$field_name = 'flur';
	$GUI->attributes['indizes'][$field_name] = count($GUI->attributes['indizes']);
	$sql = "
		SELECT DISTINCT
			ltrim(substr(flurstkennz, 7, 3), '0') AS flur
		FROM
			(
				SELECT
					unnest(zeigtaufaltesflurstueck) AS flurstkennz
				FROM
					ff_faelle

	  		UNION

	  		SELECT
					unnest(zeigtaufneuesflurstueck) AS flurstkennz
				FROM
					ff_faelle
		) AS foo ORDER BY flur
	";
	$result = $GUI->pgdatabase->execSQL($sql, 4, 0);
	while ($rs = pg_fetch_assoc($result[1])) {
		$GUI->attributes['enum_value'][$GUI->attributes['indizes'][$field_name]][] = $rs['flur'];
		$GUI->attributes['enum_output'][$GUI->attributes['indizes'][$field_name]][] = $rs['flur'];
	}

	return $GUI->attributes;
};
?>