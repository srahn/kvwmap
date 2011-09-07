<?php
	####################################################################
	#                                                                  #
	#   Konfigurationsdatei zu Modul Denkmale   											 #
	#                                                                  #
	####################################################################

	include(CLASSPATH.'xml.php');	# Version 1.6.8
	# Speicherort der HIDA XML Exportdatei
	define('DEFAULT_DENKMAL_IMPORT_FILE',SHAPEPATH.'denkmale/hida4-uld-datenexport-als-xml.xml');	# Version 1.6.8
	# Datenbankeinstellungen für die Denkmale
	define('DENKMAL_DB_HOST','localhost');
	define('DENKMAL_DB_DATABASENAME','kvwmapsp');
	define('DENKMAL_DB_USERNAME','kvwmap');
	define('DENKMAL_DB_PASSWORD','kvwmap');
?>
