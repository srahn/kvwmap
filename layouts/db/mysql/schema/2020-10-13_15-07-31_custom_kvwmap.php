<?

mkdir(WWWROOT.APPLVERSION.CUSTOM_PATH.'class');
file_put_contents(WWWROOT.APPLVERSION.CUSTOM_PATH.'class/kvwmap.php', "<? 
	/* 
	Hier können eigene Triggerfunktionen definiert werden, die beim Speichern eines Layer ausgeführt werden 
	Z.B.:
	
	\$GUI->custom_trigger_functions['do_something'] = function(\$fired, \$event, \$layer = '', \$oid = 0, \$old_dataset = array()) use (\$GUI) {
		\$executed = true;
		\$success = true;

		switch(true) {
			case (\$fired == 'AFTER' AND \$event == 'UPDATE') : {
				\$this->add_message('notice', 'Danke für die Änderung');
			} break;

			default : {
				\$executed = false;
			}
		}
		return array('executed' => \$executed, 'success' => \$success);
	};
	*/
?>");

?>
