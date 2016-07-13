<?php

	$this->goNotExecutedInPlugins = false;
		
	switch($this->go){
		case 'bevoelkerung_bericht' : {
			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$this->output();
		}break;

		case 'bevoelkerung_bericht_Bericht erstellen' : {
			$this->main = PLUGINS.'bevoelkerung/view/bevoelkerung_bericht.php';
			$this->output();
		}break;

		case 'bevoelkerung_import_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($this->pgdatabase);
			$prog->import();
		} break;

		case 'bevoelkerung_transpose_prognose' : {
			include(PLUGINS.'bevoelkerung/model/prognose.php');
			$prog = new prognose($this->pgdatabase);
			$prog->transpose();
		} break;

		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>