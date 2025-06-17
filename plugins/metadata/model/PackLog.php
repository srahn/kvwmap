<?php
#############################
# Klasse PackLog #
#############################

class PackLog extends PgObject {

	static $schema = 'metadata';
	static $tableName = 'pack_logs';
	public $layer;
	public $export_format;
	public $datatype;
	public $datatype_icon;

	function __construct($gui) {
		$gui->debug->show('Create new Object from Class PackLog in table ' . PackLog::$schema . '.' .  PackLog::$tableName, $this->write_debug);
		parent::__construct($gui, PackLog::$schema, PackLog::$tableName);
		$this->select = "*";
		$this->from = PackLog::$schema . '.' . PackLog::$tableName;
		$this->where = "";
	}

	public static function write($gui, $package, $msg) {
		$packlog = new PackLog($gui);
    $packlog = $packlog->create(array(
      'msg' => pg_escape_string($msg),
      'package_id' => $package->get_id(),
      'ressource_id' => $package->get('ressource_id')
    ));
    return $packlog;
	}

	public static function find_by_ressource_id($gui, $ressource_id) {
		// echo '<br>PackLog->find_by_ressource_id ressource_id: ' . $ressource_id;
		$packlog = new PackLog($gui);
    $packlogs = $packlog->find_where('ressource_id = ' . $ressource_id);
		$packlogs = array_map(
			function ($packlog) {
				$packlog->get_package();
				return $packlog;
			},
			$packlogs
		);
    return $packlogs;
	}

	function get_package() {
		// echo '<br>PackLog->get_package for package_id: ' . $this->get('package_id');
		$this->package = DataPackage::find_by_id($this->gui, $this->get('package_id'));
		return $this->package;
	}

	public static function fix($gui, $ressource_id) {
		$packlog = new Packlog($gui);
		$sql = "
			UPDATE " . PackLog::$schema . '.' . PackLog::$tableName . "
			SET fixed_at = now()
			WHERE
				fixed_at IS NULL AND
				ressource_id = " . $ressource_id . "
		";
		try {
			$packlog->execSQL($sql);
			return array(
				'success' => true,
				'msg' => 'Packlogs mit ressource_id: ' . $ressource_id . ' auf gefixed gesetzt.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei dem Update der fixed_at in pack_logs für ressource_id: ' . $ressource_id
			);
		}
	}

}