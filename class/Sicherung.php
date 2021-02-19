<?php
include_once(CLASSPATH . 'MyObject.php');
class Sicherung extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'sicherungen');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"name",
				"beschreibung",
				"target_dir",
				"intervall_typ",
				"intervall_start_time",
				"intervall_parameter_1",
				"intervall_parameter_2",
				"keep_for_n_days"
			)
		);
	}

	public function validate($on = '') {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
		$results[] = $this->validates('target_dir', 'not_null', 'Es muss ein Zielverzeichnis angegeben sein.');
		$results[] = $this->validates('intervall_typ', 'not_null', 'Es muss ein Intervall-Typ angegeben sein.');
		$results[] = $this->validates('beschreibung','not_null','Es muss eine Beschreibung angegeben sein.');
		$results[] = $this->validates('intervall_parameter_1','not_null','Es muss ein Ausführungstag angegeben sein.');
		$results[] = $this->validates('intervall_start_time','not_null','Es muss eine Ausführungsuhrzeit angegeben sein.');
		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$s = new Sicherung($gui);
		include_once(CLASSPATH . 'Sicherungsinhalt.php');
		if ( !$id == NULL ) {
			$s->inhalte = Sicherungsinhalt::find($gui, 'sicherung_id = ' . $id);
		}
		return $s->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$Sicherung = new Sicherung($gui);
		$sicherungen = $Sicherung->find_where($where, 'name');
		include_once(CLASSPATH . 'Sicherungsinhalt.php');
		foreach ($sicherungen AS $sicherung) {
			$sicherung->inhalte = Sicherungsinhalt::find($gui, 'sicherung_id = ' . $sicherung->get('id'));
		}
		return $sicherungen;
	}

	public function write_config_files($gui, $dir){
		if ( $dir == "" ){
			return;
		}

		$dir=rtrim($dir, '/') . '/';
		//delete + create folder in $dir
		$folder='sicherung_' . $this->get('id') . '/';
		if (is_dir($dir . $folder)){
			foreach (new DirectoryIterator($dir . $folder) as $fileInfo) {
				if ($fileInfo->isFile()) {
					unlink($fileInfo->getPathname());
				}
			}
		} else {
			mkdir($dir . $folder);
		}

		//backup.confd
		$sf = fopen($dir.$folder.'backup.conf', "w");
		fwrite($sf, "BACKUP_PATH=". $this->get('target_dir') ."\n");
		fwrite($sf, "BACKUP_FOLDER=\n");
		fwrite($sf, "KEEP_FOR_N_DAYS=7\n");
		fwrite($sf, "INTERVAL=". $this->get('intervall') ."\n");
		fclose($sf);

		//Sicherungsinhalte
		//Verzeichnissicherungen
		include_once(CLASSPATH . 'Sicherungsinhalt.php');
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Verzeichnissicherung" AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'dirs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source').';'.$inhalt->get('target') . PHP_EOL);
			}
			fclose($fh);
		}
		//mySQL
		// Anwendung fehlt in Tabelle
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Mysql Dump"  AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'mysql_dbs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source').';'.$inhalt->get('target').';'.$inhalt->get('app') . PHP_EOL);
			}
			fclose($fh);
		}
		//PGSQL
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Postgres Dump" AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'pg_dbs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source').';'.$inhalt->get('target') . PHP_EOL);
			}
			fclose($fh);
		}
		//rsync
		//Parameter fehlt in Tabelle
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Verzeichnisinhalte kopieren" AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'rsync.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source').';'.$inhalt->get('target') . PHP_EOL);
			}
			fclose($fh);
		}
		// chgrp($dir.$folder, 'gisadmin');
		// chmod($dir.$folder, 0775);
	}


	function get_intervall_start_time_for_html_input(){
		//$t = $this->get('intervall_start_time');
		//return substr( $t, 0, strpos( $t, ':', -1)
		return date('H:i', strtotime($this->get('intervall_start_time')));
	}

	public static function decode_day_of_week($dow){
		$dow_array = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
		return $dow_array[$dow];
	}

	function get_intervall_as_plaintext(){
		switch ($this->get('intervall_typ')) {
			case 'daily':
				$ret = 'täglich von ' . $this->decode_day_of_week($this->get('intervall_parameter_1')) . ' bis ' . $this->decode_day_of_week($this->get('intervall_parameter_2')) . ' um ' . $this->get_intervall_start_time_for_html_input();
				break;
			case 'weekly':
				$ret = 'jede Woche am ' . $this->decode_day_of_week($this->get('intervall_parameter_1')) . ' um ' . $this->get_intervall_start_time_for_html_input();
				break;
			case 'monthly':
				$ret = 'monatlich am ' . ($this->get('intervall_parameter_1')+1) . '. um ' . $this->get_intervall_start_time_for_html_input();
				break;
		}
		return $ret;
	}

	function get_folder_date_notation(){
		switch ($this->get('intervall_typ')) {
			case 'daily':
				$ret = '$(date +%V)/$(date +%u)';	//Woche[01..53]/Tag[1..7]
				break;
			case 'weekly':
				$ret = '$(date +%U)';							//Woche[01..53]
				break;
			case 'monthly':
				$ret = '$(date +%)';								//Monat 1-12
				break;
			default:
				$ret = '$(date +%Y_%m_%d)';
				break;
		}
		return $ret;
	}

}
?>
