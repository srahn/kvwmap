<?php
include_once(CLASSPATH . 'MyObject.php');

class Sicherungsinhalt extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'sicherungsinhalte');
		$this->identifier = 'id';
		$this->setKeys(
			array(
				"id",
				"name",
				"beschreibung",
				"methode",
				"source",
				"connection_id",
				"target",
				"overwrite",
				"sicherung_id",
				"tar_compress",
				"pgdump_insert",
				"pgdump_columninserts",
				"pgdump_in_exclude_schemas",
				"pgdump_schema_list",
				"pgdump_in_exclude_tables",
				"pgdump_table_list"
			)
		);
	}

	public function validate($on = '') {
		$results = array();
		$results[] = $this->validates('name', 'not_null', 'Es muss ein Name angegeben werden.');
		$results[] = $this->validates('methode', 'not_null', 'Wähle eine Methode aus.');
		//$results[] = $this->validates('target', 'not_null', 'Wähle ein Ziel der Sicherung aus.');
		$results[] = $this->validates('sicherung_id', 'not_null', 'Wähle eine Sicherung aus mit der der Inhalt gesichert werden soll.');

		switch ($this->get('methode')) {
			case 'Postgres Dump':
				$results[] = $this->validates('connection_id', 'not_null', 'Wähle eine Quelle der Sicherung aus.');
				$this->set('source', $this->get_connection_name());
				break;
			default:
				$results[] = $this->validates('source', 'not_null', 'Wähle eine Quelle der Sicherung aus.');
				break;
		}

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public static function find_by_id($gui, $id) {
		$s = new Sicherungsinhalt($gui);
		return $s->find_by('id', $id);
	}

	public static	function find($gui, $where = '1=1') {
		$Sicherungsinhalt = new Sicherungsinhalt($gui);
		return $Sicherungsinhalt->find_where($where, 'name');
	}

	/**
	*
	*	Lists all available mysql-databases on current server. For selection in GUI.
	*
	* @return	array with databases
	* @author Georg Kämmert
	**/
	function get_mysql_database_names(){
		$sql = "SHOW DATABASES";
		$this->database->execSQL($sql);
		if ($this->database->success){
			while ($row = $this->database->result->fetch_assoc()){
				$result[] = $row;
			}
		}
		return $result;
	}

	/**
	*
	*	List all available postgres-Connections. For selection in GUI.
	*
	* @return	array with databases
	* @author Georg Kämmert
	**/
	function get_pgsql_database_names(){
		include_once(CLASSPATH . 'Connection.php');
		$connections = Connection::find($this->gui);
		foreach ($connections as $connection) {
			$results[] = array($connection->get('id'), $connection->get('name'));
		}
		return $results;
	}

	/**
	*
	*	Returns the name of the connection
	*
	* @return	name of pgsql-connection
	* @author Georg Kämmert
	**/
	function get_connection_name(){
		include_once(CLASSPATH . 'Connection.php');
		$connection = Connection::find_by_id($this->gui, $this->get('connection_id'));
		return $connection->get('name');
	}

	/**
	*	Empty all keys that aren't set
	*
	* @param	$formvars	current formvars to evaluate
	* @author Georg Kämmert
	**/
	function disable_options($formvars){
		switch ($this->get('methode')) {
			case 'Postgres Dump':
				$this->set('pgdump_insert', isset($formvars['pgdump_insert']) );
				$this->set('pgdump_columninserts', isset($formvars['pgdump_columninserts']) );
				$this->set('tar_compress', null);
				$this->set('source', null);
				break;

			case 'Verzeichnissicherung':
				$this->set('tar_compress', isset($formvars['tar_compress']) );

				break;
			default:
				$this->set('connection_id', null);
				$this->set('pgdump_insert', null);
				$this->set('pgdump_columninserts', null );
				$this->set('pgdump_in_exclude_schemas', null);
				$this->set('pgdump_schema_list', null);
				$this->set('pgdump_in_exclude_tables', null);
				$this->set('pgdump_table_list', null);
				$this->set('tar_compress',null);
				break;
		}
	}

	/**
	*
	*	retruns options for tar
	*
	* @return	optionflags for tar
	* @author Georg Kämmert
	**/
	function get_tar_options(){
		if ($this->get('tar_compress') == '1'){
			$return = '-z';
		}
		return $return;
	}

	/**
	*
	*	returns options for pg_dump
	*
	* @return optionflags for pg_dump
	* @author Georg Kämmert
	**/
	function get_pg_dump_options(){
		if ($this->get('pgdump_insert')){
			$options = $options . ' --insert';
		}
		if ($this->get('pgdump_columninserts')){
			$options = $options . ' --column-inserts';
		}
		if ($this->get('pgdump_in_exclude_schemas') != '' &&
	 			$this->get('pgdump_schema_list') != ''){
			$list = explode(';', $this->get('pgdump_schema_list'));
			foreach ($list as $schema) {
				$options = $options . ' -' . $this->get('pgdump_in_exclude_schemas') . ' ' . $schema;
			}
		}
		if ($this->get('pgdump_in_exclude_tables') != '' &&
	 			$this->get('pgdump_table_list') != ''){
			$list = explode(';', $this->get('pgdump_table_list'));
			foreach ($list as $schema) {
				$options = $options . ' -' . $this->get('pgdump_in_exclude_tables') . ' ' . $schema;
			}
		}
		return $options;
	}

	/**
	*
	*	Does the parent Sicherung have a target directory specified?
	*
	*	@return boolean
	* @author Georg Kämmert
	**/
	function get_sicherung_has_target_dir(){
		include_once(CLASSPATH . 'Sicherung.php');
		return Sicherung::find_by_id($this, $this->gui->formvars['sicherung_id'])->get('target_dir') == '' ? false : true ;
	}

	/**
	*
	*	List of all possible options for Sicherungsinhalt
	*
	*	@return array of options; if Sicherungsinhalt is already saved current method has selected flag
	* @author Georg Kämmert
	**/
	function get_option_list_for_methods(){
		include(LAYOUTPATH . 'languages/sicherungsinhalte_' . $this->gui->user->rolle->language . '.php');
		$optionen = array( 'TAR' 				=> array('value' => 'Verzeichnissicherung', 				'label' => $strVerzeichnissicherung)
											,'RSYNC'			=> array('value' => 'Verzeichnisinhalte kopieren', 	'label' => $strVerzeichnskopieren)
											,'PG_DUMP'		=> array('value' => 'Postgres Dump', 								'label' => $strPostgresDump)
											,'MYSQLDUMP'	=> array('value' => 'Mysql Dump', 									'label' => $strMysqlDump)
										);
		if (!$this->get_sicherung_has_target_dir()){
			unset($optionen['TAR']);
			unset($optionen['PG_DUMP']);
			unset($optionen['MYSQLDUMP']);
		}
		return $optionen;
	}

}
?>
