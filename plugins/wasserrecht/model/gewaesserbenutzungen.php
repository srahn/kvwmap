<?php
#############################
# Klasse Konvertierung #
#############################

class Gewaesserbenutzungen extends PgObject {

	static $schema = 'wasserrecht';
	static $tableName = 'gewaesserbenutzungen';
	static $write_debug = true;
	
	public $gewaesserbenutzungUmfang;

	function Gewaesserbenutzungen($gui) {
		parent::__construct($gui, Gewaesserbenutzungen::$schema, Gewaesserbenutzungen::$tableName);
	}

	public static function find_by_id($gui, $by, $id) {
		$gewaesserbenutzung = new Gewaesserbenutzungen($gui);
		$gewaesserbenutzung->find_by($by, $id);
		return $gewaesserbenutzung;
	}
	
	public function find_where_with_umfang($where, $order = NULL, $select = '*') {
	    $gewaesserbenutzungen = parent::find_where($where, $order, $select);
	    if(!empty($gewaesserbenutzungen))
	    {
	        $gewaesserbenutzungUmfang = new GewaesserbenutzungenUmfang($this->gui);
	        
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung->data['umfang']))
	            {
// 	                echo 'id=' . $gewaesserbenutzung->data['umfang'];
	                $gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang->find_where('id=' . $gewaesserbenutzung->data['umfang']);
	                if(!empty($gewaesserbenutzungUmfang))
	                {
	                    $gewaesserbenutzung->gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang[0];
	                }
	            }
	        }
	    }
	    return $gewaesserbenutzungen;
	}
}
?>
