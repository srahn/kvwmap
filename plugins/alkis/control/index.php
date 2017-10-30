<?php
$this->goNotExecutedInPlugins = false;
/**
* Anwendungsfälle
*/

switch($this->go){
	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>