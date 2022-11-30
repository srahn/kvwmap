<?php
	/*
	Sendet Dateien ohne Anmeldung falls die Konvertierungen den Status veröffentlicht haben z.B.
	XPlanGML-Dokument: https://bauleitplaene-mv.de/download_dev/xplan_97005.gml
	XPlan PDF: https://bauleitplaene-mv.de/download_dev/130735351046_00000000_EBP_004_AP_001_0_R_G_000_5003.pdf
	*/
	include('../../../credentials.php');
	include('../../../config.php');
	include('../../../class/mysql.php');
	include('../../../class/postgresql.php');
	include('../../../class/PgObject.php');
	include('../../../class/log.php');
	include('../../../funktionen/allg_funktionen.php');
	include('../model/konvertierung.php');

	class GUI {
		function __construct($debug, $database) {
			$this->debug = $debug;
			$this->database = $database;
		}
	}
	$debug = new Debugger(DEBUGFILE);
	$userDb = new database(true);
	$GUI = new GUI($debug, $userDb);
	$gisDb = new pgdatabase();
	$gisDb->open(POSTGRES_CONNECTION_ID);
	$GUI->pgdatabase = $gisDb;
	$GUI->sanitize(['document' => 'text']);
	if ($konvertierung = Konvertierung::find_by_document($GUI, $GUI->formvars['document'])) {
		$konvertierung->send_export_file($konvertierung->exportfile, $konvertierung->contenttype);
	}
	else {
		echo 'Dokument nicht gefunden oder Zugriff nicht erlaubt!';
	}
?>