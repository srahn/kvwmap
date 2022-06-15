<?php
include_once(CLASSPATH . 'MyObject.php');
class BelatedFile extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'belated_files');
	}

	public static	function find_by_id($gui, $id) {
		$belated_file = new BelatedFile($gui);
		$belated_files = $belated_file->find_by(BelatedFile::$identifier, $id);
		return $belated_files;
	}

	public static	function find($gui, $where, $order = '') {
		$belated_file = new BelatedFile($gui);
		return $belated_file->find_where($where, $order);
	}

	public static function insert($gui, $data) {
		$belated_file = new BelatedFile($gui);
		return $belated_file->create($data);
	}

}
?>
