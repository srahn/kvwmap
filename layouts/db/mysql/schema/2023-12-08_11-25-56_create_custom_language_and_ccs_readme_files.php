<?
	$custom_language_readme_template = INSTALLPATH . 'kvwmap-server/service-templates/web/directory_template/www/apps/kvwmap/custom/layouts/language/README';
	$custom_language_path = WWWROOT . APPLVERSION . CUSTOM_PATH . 'layouts/languages/';
	$custom_language_readme_file = $custom_language_path . 'README';

	if (!file_exists($custom_language_path)) {
		mkdir($custom_language_path, 0770, true);
	}

	if (!file_exists($custom_language_readme_file)) {
		if (file_exists($custom_language_readme_template)) {
			copy($custom_language_readme_template, $custom_language_readme_file);
		}
		else {
			file_put_contents(
				$custom_language_readme_file,
				'Wenn die Konstante OVERRIDE_LANGUAGE_VARS im Abschnitt Layout der Konfiguration auf true gestetzt ist, werden die Variablen aus Language-Dateien im Verzeicnis layouts/languages durch gleichnamige Variablen in gleichnamigen Language-Dateien, die in diesem Verzeichnis custom/layouts/lanugages abgelegt sind, überschrieben. Damit können einzelne Texte in der Anwendung individuell überschrieben werden.'
			);
		}
	}

	$custom_css_readme_template = INSTALLPATH . 'kvwmap-server/service-templates/web/directory_template/www/apps/kvwmap/custom/layouts/css/README';
	$custom_css_path = WWWROOT . APPLVERSION . CUSTOM_PATH . 'layouts/css/';
	$custom_css_readme_file = $custom_css_path . '/README';

	if (!file_exists($custom_css_path)) {
		mkdir($custom_css_path, 0770, true);
	}

	if (!file_exists($custom_css_readme_file)) {
		if (file_exists($custom_css_readme_template)) {
			copy($custom_css_readme_template, $custom_css_readme_file);
		}
		else {
			file_put_contents(
  	  	$custom_css_readme_file,
    		'Wenn die Konstante OVERRIDE_CSS im Abschnitt Layout der Konfiguration auf true gestetzt ist, werden die Style-Definitionen aus Dateien im Verzeichnis layouts/css durch Definitionen aus in diesme Verzeichnis abgelegten gleichnamigen css-Dateien überschrieben. Es werden immer erst die Style-Definitionen aus layouts/css geladen und anschließend falls vorhanden die Dateien aus custom/layouts/css.'
			);
		}
	}
?>