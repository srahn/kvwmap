<?php

function go_switch_alkis($go){
	global $GUI;
	switch($go){
		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}
?>