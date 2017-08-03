<?php
#############################
# Klasse Konvertierung #
#############################

class GewaesserbenutzungenUmfang extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'gewaesserbenutzungen_umfang';
	static $write_debug = true;

	function GewaesserbenutzungenUmfang($gui) {
	    parent::__construct($gui, GewaesserbenutzungenUmfang::$schema, GewaesserbenutzungenUmfang::$tableName);
	}
	
	public static function find_by_id($gui, $by, $id) {
	    $gewaesserbenutzungUmfang = new GewaesserbenutzungenUmfang($gui);
	    $gewaesserbenutzungUmfang->find_by($by, $id);
	    return $gewaesserbenutzungUmfang;
	}
	
	public function getUmfang()
	{
	    if(!empty($this->data['max_ent_a']))
	    {
	        return $this->data['max_ent_a'] . " m³/a";
	    }
	    
	    return "";
// 	    return "m3/a";
	}
}
?>
