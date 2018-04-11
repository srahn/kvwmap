<?php
$this->goNotExecutedInPlugins = false;
include(PLUGINS . 'ukos/model/kvwmap.php');

switch($go) {
	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}
}
?>