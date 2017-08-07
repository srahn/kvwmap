<?php
class Gewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'gewaesserbenutzungen';
	
	public $gewaesserbenutzungUmfang;

	public function find_where_with_umfang($where, $order = NULL, $select = '*') {
	    $gewaesserbenutzungen = $this->find_where($where, $order, $select);
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
	
	public function getKennummer() {
	    return $this->data['kennnummer'];
	}
}
?>