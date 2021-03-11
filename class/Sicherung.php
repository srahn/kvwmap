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
				"keep_for_n_days",
				"active"
			)
		);
	}

	public function validate($on = '') {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
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

	/**
	*
	*	Set not set values to default.
	*
	*	@author Georg Kämmert
	**/
	public function disable_options($formvars){
		if (!isset($formvars['active'])) {
			$this->set('active', false);
		}
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

	/**
	*	Writes out the config files for $this Sicherung into given directory $dir
	*
	*	@param	$gui	instance of GUI-Class
	* @param	$dir	directory to write into
	* @author	Georg Kämmert
	**/
	public function write_config_files($gui, $dir, $app){
		if ( $dir == "" ){
			return;
		}

		$dir=rtrim($dir, '/') . '/';

		$folder=$app . '_' . $this->get('id') . '/';
		mkdir($dir . $folder);

		//backup.conf
		$sf = fopen($dir.$folder.'backup.conf', "w");
		fwrite($sf, "BACKUP_PATH=". $this->get('target_dir') . PHP_EOL);
		fwrite($sf, "BACKUP_FOLDER=" . $this->get_folder_date_notation() . PHP_EOL);
		fwrite($sf, "KEEP_FOR_N_DAYS=" . $this->get('keep_for_n_days') . PHP_EOL);
		fwrite($sf, "PROD_APP=" . $app);
		fclose($sf);

		//Sicherungsinhalte
		//Verzeichnissicherungen
		include_once(CLASSPATH . 'Sicherungsinhalt.php');
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Verzeichnissicherung" AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir . $folder . 'dirs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source') . ';'. $inhalt->get('target') . ';' . $inhalt->get_tar_options() . PHP_EOL);
			}
			fclose($fh);
		}
		//mySQL
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Mysql Dump"  AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'mysql_dbs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get('source') . ';'.$inhalt->get('target') .  PHP_EOL);
			}
			fclose($fh);
		}
		//PGSQL
		$Inhalte = Sicherungsinhalt::find($gui, 'methode = "Postgres Dump" AND sicherung_id=' . $this->get('id'));
		if ( !empty($Inhalte) ){
			$fh = fopen($dir.$folder . 'pg_dbs.conf', "w");
			foreach ($Inhalte as $inhalt){
				fwrite($fh, $inhalt->get_connection_dbname() . ';' . $inhalt->get('target') . ';' . $inhalt->get_pg_dump_options() . PHP_EOL);
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
	}


	/**
	*	Retuns the value of key "intervall_start_time" that is menant to be the inputvalue of an html5 time-input element.
	* 24h format with leading zeros, minutes with leading zeros.
	*
	*	@return	value of key intervall_start_time
	* @author Georg Kämmert
	**/
	function get_intervall_start_time_for_html_input(){
		//$t = $this->get('intervall_start_time');
		//return substr( $t, 0, strpos( $t, ':', -1)
		return date('H:i', strtotime($this->get('intervall_start_time')));
	}

	/**
	*	Translates the day of the week from 0 to 6 into plaintext
	*
	* @param	$dow	number 0..6
	* @return	day of week Sonntag..Samstag
	* @author	Georg Kämmert
	*
	**/
	public static function decode_day_of_week($dow){
		$dow_array = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
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

	/**
	*	Returns the linux date notation for the folder name each Sicherung has to be safed into. Depending on the intervall_typ
	*
	* @return	name of the folder the backup has to be safed in
	* @author Georg Kämmert
	**/
	function get_folder_date_notation(){
		switch ($this->get('intervall_typ')) {
			case 'daily':
				$ret = '$(date +%m)/$(date +%u)';	//Monat[01..12]/Tag[1..7]
				break;
			case 'weekly':
				$ret = '$(date +%m)/$(date +%u)';	//Monat[01..12]/Tag[1..7]
				break;
			case 'monthly':
				$ret = '$(date +%Y)/$(date +%m)';	//Jahr[YYYY]/Monat[01..12]
				break;
			default:
				$ret = '$(date +%Y_%m_%d)';
				break;
		}
		return $ret;
	}

	/**
	*
	*	returns execution time for backup in cronjob notation
	*
	*	@return execution time in cron format
	* @author Georg Kämmert
	**/
	function get_cronjob_interval(){
		$intervall = date('i H',strtotime($this->get('intervall_start_time')));
		switch ($this->get('intervall_typ')) {
			case 'daily':
				$intervall = $intervall . ' * * ' . $this->get('intervall_parameter_1') . '-' . $this->get('intervall_parameter_2');
				break;
			case 'weekly':
				$intervall = $intervall . ' * * ' . $this->get('intervall_parameter_1');
				break;
			case 'monthly':
				$intervall = $intervall . ' * ' . $this->get('intervall_parameter_1') . ' *';
				break;
		}
		return $intervall;
	}

	/**
	*
	*	Checks if the backup is valid for file export
	*
	*	@return true/false
	* @author Georg Kämmert
	**/
	function get_valid_for_fileexport(){

		$return = true;

		//export only if target_dir is not null or
		// if null only rsync jobs are defined
		if ($this->get('target_dir') == '') {
			foreach ($this->inhalte as $inhalt) {
				if ($inhalt->get('methode') == 'Verzeichnisinhalte kopieren'){
					$return = true;
				}
			}
			foreach ($this->inhalte as $inhalt) {
				if ($inhalt->get('methode') != 'Verzeichnisinhalte kopieren'){
					$return = false;
				}
			}
		}

		//export only if active Sicherungsinhalte exist
		foreach ($this->inhalte as $inhalt) {
			if ($inhalt->get('active') == 0){
				$return = false;
			}
		}

		$return = !empty($this->inhalte);
		//is Sicherung active?
		$return = $this->get('active');

		return $return;
	}

}
?>
