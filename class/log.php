<?
##########################
# Klasse für Debug-Datei #
##########################
# Klasse debugfile #
####################

class Debugger {
	public $filename, $fp, $level;

	###################### Liste der Funktionen ####################################
	#
	# function debugfile($filename) - Construktor
	# function write($msg,$level)
	# function close()
	#
	################################################################################

	function __construct($filename, $mime_type = 'text/html') {
		if ($_SESSION == null) {
			$_SESSION = array();
		}
		$this->filename = LOGPATH . (dirname($filename) != '.' ? dirname($filename) . '/' : '') . (array_key_exists('login_name', $_SESSION) ? $_SESSION['login_name'] : '') . basename($filename);
		$this->fp=fopen($this->filename,'w');
		$this->mime_type = $mime_type;
		$this->level;

		if ($this->mime_type == 'text/html') {
			fwrite($this->fp,"<html>\n<head>\n  <title>kvwmap Debug-Datei</title>\n<META http-equiv=Content-Type content='text/html; charset=UTF-8'>\n</head>\n<body>");
			fwrite($this->fp,"<h2>Debug Datei</h2>");
		}
		else {
			fwrite($this->fp, 'Starte Debugging am ' . date("Y-m-d H:i:s") . PHP_EOL);
		}
	}

	function write($msg, $level = 4, $echo = false) {
		$this->level = $level;
		if ($echo) {
			echo '<br>' . $msg;
		}
		if ($level >= DEBUG_LEVEL) {
			if ($this->mime_type == 'text/html') {
				fwrite($this->fp, "\n<br>");
			}
			else {
				fwrite($this->fp, PHP_EOL);
			}
			$ret = @fwrite($this->fp, $msg);
			if (!$ret) {
				$this->Fehlermeldung  ='In die Debugdatei ' . $this->filename . ' läßt sich nicht schreiben.';
				$this->Fehlermeldung .='<br>Das kann daran liegen, dass für den WebServer, in dem kvwmap läuft, keine Schreibrechte gesetzt sind.';
				$this->Fehlermeldung .='<br>Prüfen Sie die Rechte der Datei!';
				include(LAYOUTPATH . "snippets/Fehlermeldung.php");
				exit;
			}
		}
	}

	function show($msg, $debug = false) {
		if ($debug) echo '<br>' . $msg;
	}

	function close() {
		if ($this->mime_type == 'text/html') {
			fwrite($this->fp,"\n</body>\n</html>");
		}
		fclose($this->fp);
	}
}
############################################
# Klasse für das Loggen von SQL-Statements #
############################################
# Klasse LogFile #
##################

class LogFile {
	var $filename; # Dateiname in der gelogt wird
	var $fp; # filepointer
	var $format; # Ausgabeformat

	###################### Liste der Funktionen ####################################
	#
	# function LogFile($filename,$format,$title,$headline) - Construktor
	# function write($msg)
	# function close()
	#
	################################################################################

	# öffnet die Logdatei
	function __construct($filename, $format, $title, $headline, $with_timestamp = false) {
		$this->name = $filename;
		$this->fp = fopen($filename,"a");
		$this->format = $format;
		$this->with_timestamp = $with_timestamp;
    $ret = true;
		if ($format == "html") {
			# fügt HEML header ein zum loggen in einer HTML-Datei
			# Wenn title gesetzt ist wird er als Titel im header gesetzt, sonst default.
			if ($title == "") { $title=="Logdatei"; }
			fwrite($this->fp, "<html>\n<head>\n<title>" . $title . "</title>\n</head>\n<body>");
			if ($headline != "") {
				$ret = @fwrite($this->fp, "<h1>" . $headline . "</h1>");
			}
		}
		if ($format == "text") {
			if ($headline != "") {
				$ret = @fwrite($this->fp, "\n" . $headline);
			}
		}
		if (!$ret) {
			$this->Fehlermeldung  = 'In die Logdatei ' . $this->name . ' läßt sich nicht schreiben.';
			$this->Fehlermeldung .= '<br>Das kann daran liegen, dass für den WebServer, in dem kvwmap läuft, keine Schreibrechte gesetzt sind.';
			$this->Fehlermeldung .= '<br>Prüfen Sie die Rechte der Datei!';
			include(LAYOUTPATH . "snippets/Fehlermeldung.php");
			exit;
		}
	}

	function write($msg) {
		if ($this->with_timestamp) {
			$msg = date("Y-m-d H:i:s") . ': ' . $msg;
		}
		if ($this->format == "html") {
			fwrite($this->fp, "\n<br>" . $msg);
		}
		if ($this->format == "text") {
			fwrite($this->fp, "\n" . $msg);
		}
	}

	function close() {
		if ($this->format == "html") {
			fwrite($this->fp, "\n</body>\n</html>");
		}
		fclose($this->fp);
	}

	function delete() {
		unlink($this->name);
	}
}
?>
