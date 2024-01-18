<?
  include($language_file);
	if (defined('OVERRIDE_LANGUAGE_VARS') AND OVERRIDE_LANGUAGE_VARS) {
		$custom_language_file = str_replace(WWWROOT . APPLVERSION, WWWROOT . APPLVERSION . CUSTOM_PATH, $language_file);
		if (file_exists($custom_language_file)) {
			include($custom_language_file);
		}
	}
?>