<?
	include_once(CLASSPATH . 'PgObject.php');
	class AlTeilflaeche extends PgObject {

		static $schema = 'verdachtsflaechen';
		static $tableName = 'al_teilflaechen';
		static $write_debug = false;
		public $identifier;

		function __construct($gui) {
			$gui->debug->show('Create new Object AlTeilflaeche', AlTeilflaeche::$write_debug);
			parent::__construct($gui, AlTeilflaeche::$schema, AlTeilflaeche::$tableName);
			$this->identifier = 'id_tf';
		}

		public static function find_by_id($gui, $id) {
			$al_teilflaeche = new AlTeilflaeche($gui);
			return $al_teilflaeche->find_by($al_teilflaeche->identifier, $id);
		}
	}
?>