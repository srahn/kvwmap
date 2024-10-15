<?php
#############################
# Klasse PackLog #
#############################

class PackLog extends PgObject {

	public $write_debug = false;
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

	public static function write($GUI, $package, $msg) {
		$packlog = new PackLog($GUI);
    $packlog = $packlog->create(array(
      'msg' => pg_escape_string($msg),
      'package_id' => $package->get_id(),
      'ressource_id' => $package->get('ressource_id')
    ));
    return $packlog;
	}
}