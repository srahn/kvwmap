<?
##########################
# Klasse für Debug-Datei #
##########################
# Klasse debugfile #
####################

class Debugger {
	public $filename, $fp, $level;
	var $mime_type;
	var $user_funktion;

	###################### Liste der Funktionen ####################################
	#
	# function debugfile($filename) - Construktor
	# function write($msg,$level)
	# function close()
	#
	################################################################################

	function __construct($filename, $mime_type = 'text/html', $mode = 'w') {
		if (!in_array($mode, array('w', 'a'))) {
			$mode = 'w';
		};
		if (!isset($_SESSION) OR $_SESSION == null) {
			$_SESSION = array();
		}
		$this->filename = LOGPATH . (dirname($filename) != '.' ? dirname($filename) . '/' : '') . (array_key_exists('login_name', $_SESSION) ? $_SESSION['login_name'] : '') . basename($filename);
		if (!file_exists($this->filename)) {
			touch($this->filename);
			chmod($this->filename, 0775);
		}
		$this->fp = fopen($this->filename, $mode);
		$this->mime_type = $mime_type;
		$this->level;
		$this->user_funktion = '';
		$this->timestamp =  date('Y-m-d H:i:s', time());

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
		if ($debug AND $this->user_funktion == 'admin') {
			$nl = ($this->mime_type == 'text/html' ? '<br>' : "\n" . date('Y-m-d H:i:s') . ': ');
			echo $nl . $msg;
		}
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
	var $name; # Filename of logfile
	var $with_timestamp; # Output with timestamp or not

	###################### Liste der Funktionen ####################################
	#
	# function LogFile($filename,$format,$title,$headline) - Construktor
	# function write($msg)
	# function close()
	#
	################################################################################

	# öffnet die Logdatei
	function __construct($filename, $format, $title, $headline = '', $with_timestamp = false) {
		$file_info = pathinfo($filename);
		if ($file_info['dirname'] != '.' AND !is_dir($file_info['dirname'])) {
			mkdir($file_info['dirname'], 0775, true);
		}
		$file_is_new = !file_exists($filename);
		$this->fp = fopen($filename, "a");
		$this->name = $filename;
		$this->format = $format;
		$this->with_timestamp = $with_timestamp;
		$title = $title ?: 'Logdatei';
    $ret = true;
		if ($format == "html") {
			if ($file_is_new) {
				fwrite($this->fp, "<html>\n<head>\n<title>" . $title . "</title>\n</head>\n<body>");
			}
			if ($headline != "") {
				$ret = @fwrite($this->fp, "\n<h1>" . $headline . "</h1>");
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

	function get_content() {
		return file_get_contents($this->name);
	}

	function delete() {
		unlink($this->name);
	}
}
?>
