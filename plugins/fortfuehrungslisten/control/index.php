<?php
$this->goNotExecutedInPlugins = false;
include_once(CLASSPATH . 'PgObject.php');
include(PLUGINS . 'fortfuehrungslisten/model/loader.php');
include(PLUGINS . 'fortfuehrungslisten/model/ff_auftrag.php');
include(PLUGINS . 'fortfuehrungslisten/model/fortfuehrungsfall.php');
/**
* Anwendungsfälle
*/
switch($go) {
	case 'auftragsdatei_loeschen': {
		$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
		if (empty($ff_auftrag_id)) {
			$this->messages[] = array(
				'msg' => 'Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!',
				'type' => 'error'
			);
		}
		else {
			$ff_auftrag = Fortfuehrungsauftrag::find_by_id($this, 'id', $ff_auftrag_id);
			$result = $ff_auftrag->auftragsdatei_loeschen();
		}
		$this->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
		$this->formvars['operator_ff_auftrag_id'] = '=';
		$this->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
		$this->GenerischeSuche_Suchen();
	} break;

	case 'lade_fortfuehrungsfaelle': {
		$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
		if (empty($ff_auftrag_id)) {
			$this->messages[] = array(
				'type' => 'error',
				'msg' => 'Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!'
			);
		}
		else {
			$ff_auftrag = Fortfuehrungsauftrag::find_by_id($this, 'id', $ff_auftrag_id);
			if ($ff_auftrag->get('auftragsdatei') == '') {
				$this->messages[] = array(
					'type' => 'error',
					'msg' => 'Sie müssen erst eine Auftragsdatei zum Fortführungsauftrag hochladen!'
				);
			}
			else {
				$this->loader = new NASLoader($this);
				$result = $this->loader->load_fortfuehrungsfaelle($ff_auftrag);
				$this->messages = array_merge($this->messages, $this->loader->messages);

				if ($result['success']) {
					$result = $ff_auftrag->auftragsdatei_loeschen();
				}
			}
		}

		$this->messages = array_merge($this->messages, $ff_auftrag->messages);

		$this->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
		$this->formvars['operator_ff_auftrag_id'] = '=';
		$this->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
		$this->GenerischeSuche_Suchen();
	}	break;

	/*
	* Liefert das Formular für die Suche nach Fortfuehrungsnachweisen aus
	*/
	case 'fortfuehrungslisten_fn_suche': {
		$layer_id = LAYER_ID_FF_AUFTRAG;
		$mapdb = new db_mapObj($this->Stelle->id, $this->user->id);
		$layerdb = $mapdb->getlayerdatabase($layer_id, $this->Stelle->pgdbhost);
		$layerdb->setClientEncoding();
		$this->attributes = $mapdb->read_layer_attributes($layer_id, $layerdb, $privileges['attributenames']);
		$this->{'attributes'.$m} = $mapdb->add_attribute_values($this->attributes, $layerdb, array(), true, $this->Stelle->id);
		$this->attributes = fortfuehrungslisten_add_user_names($this);
		$this->attributes = fortfuehrungslisten_add_flurstuecke($this);
		$this->attributes = fortfuehrungslisten_add_fluren($this);

		?><pre style="text-align: left"><?php #print_r($this->attributes); ?></pre><?php
		$this->main = PLUGINS . 'fortfuehrungslisten/view/suche_fortfuehrungsnachweise.php';
		$this->output();
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

		if ($this->formvars['value_flurstueckskennzeichen'] != '') {
			$where[] = "f.flurstueckskennzeichen = '" . $this->formvars['value_flurstueckskennzeichen'] . "'";
		}

		if ($this->formvars['value_altesneues'] != '') {
			$where[] = "zeigtauf = '" . $this->formvars['value_altesneues'] . "'";
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
			$result = $this->pgdatabase->execSQL($sql, 4, 0);
			$ids = array();
			while ($rs = pg_fetch_assoc($result[1])) {
				$ids[] = $rs['id'];
			}

			?><pre style="text-align: left"><?php #print_r($ids); ?></pre><?php

			# Wenn nichts gefunden wurde ($ids leer) wird durch die Suchbedingung
			# ff_auftrag_id IN (-1) erzwungen, dass GenerischeSuche_Suchen auch kein
			# Suchergebnis hervorbringt.
			$this->formvars['operator_ff_auftrag_id'] = 'IN';
			$this->formvars['value_ff_auftrag_id'] = (empty($ids) ? -1 : implode('|', $ids));
		}

		if ($this->formvars['value_gemkgschl'] != '') {
			$this->formvars['operator_gemkgnr'] = '=';
			$this->formvars['value_gemkgnr'] = $this->formvars['value_gemkgschl'];
		}

		?><pre style="text-align: left"><?php #print_r($this->formvars); ?></pre><?php

		$this->GenerischeSuche_Suchen();
	} break;

	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
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