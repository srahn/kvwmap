<?

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
		
		default : {
			$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	
?>