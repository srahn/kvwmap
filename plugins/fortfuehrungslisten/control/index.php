<?php
$this->goNotExecutedInPlugins = false;
include_once(CLASSPATH . 'PgObject.php');
include(PLUGINS . 'fortfuehrungslisten/model/loader.php');
include(PLUGINS . 'fortfuehrungslisten/model/ff_auftrag.php');
include(PLUGINS . 'fortfuehrungslisten/model/fortfuehrungsfall.php');
/**
* Anwendungsfälle
*/
switch($this->go) {
	case 'auftragsdatei_loeschen': {
		$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
		if (empty($ff_auftrag_id)) {
			$this->Fehlermeldung = '<br>Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!';
		}
		else {
			$ff_auftrag = Fortfuehrungsauftrag::find_by_id($this, 'id', $ff_auftrag_id);
			$result = $ff_auftrag->auftragsdatei_loeschen();
			if (!$result['succes']) {
				$this->Fehlermeldung = '<br>' . $result['err_msg'];
			}
		}
		$this->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
		$this->formvars['operator_ff_auftrag_id'] = '=';
		$this->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
		$this->GenerischeSuche_Suchen();
	} break;

	case 'lade_fortfuehrungsfaelle': {
		$ff_auftrag_id = $_REQUEST['ff_auftrag_id'];
		if (empty($ff_auftrag_id)) {
			$this->Fehlermeldung = 'Sie müssen eine Fortführungsauftrags Id angeben im Parameter ff_auftrag_id!';
		}
		else {
			$ff_auftrag = Fortfuehrungsauftrag::find_by_id($this, 'id', $ff_auftrag_id);
			if (empty($ff_auftrag->get('auftragsdatei'))) {
				$this->Fehlermeldung = 'Sie müssen erst eine Auftragsdatei zum Fortführungsauftrag hochladen!';
			}
			else {
				$this->loader = new NASLoader($this);
				$result = $this->loader->load_fortfuehrungsfaelle($ff_auftrag);
				if ($result['success']) {
					$result = $ff_auftrag->auftragsdatei_loeschen();
				}
				if (!$result['success']) {
					$this->Fehlermeldung = '<br>' . $result['err_msg'];
				}
			}
		}
		if (!empty($this->Fehlermeldung)) {
			showMessage($this->Fehlermeldung, false, 'error');
		}
		$this->formvars['selected_layer_id'] = LAYER_ID_FF_AUFTRAG;
		$this->formvars['operator_ff_auftrag_id'] = '=';
		$this->formvars['value_ff_auftrag_id'] = $ff_auftrag_id;
		$this->GenerischeSuche_Suchen();
	}	break;

	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>