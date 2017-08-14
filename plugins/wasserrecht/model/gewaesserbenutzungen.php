<?php
class Gewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'gewaesserbenutzungen';
	
	public $gewaesserbenutzungUmfang;
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;

	public function find_where_with_umfang($where, $order = NULL, $select = '*') {
	    $gewaesserbenutzungen = $this->find_where($where, $order, $select);
	    if(!empty($gewaesserbenutzungen))
	    {
	        $gwu = new GewaesserbenutzungenUmfang($this->gui);
	        
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung->data['umfang']))
	            {
// 	                echo 'id=' . $gewaesserbenutzung->data['umfang'];
	                $gewaesserbenutzungUmfang = $gwu->find_where('id=' . $gewaesserbenutzung->data['umfang']);
	                if(!empty($gewaesserbenutzungUmfang))
	                {
	                    $gewaesserbenutzung->gewaesserbenutzungUmfang = $gewaesserbenutzungUmfang[0];
	                }
	            }
	        }
	        
	        $gwa = new GewaesserbenutzungenArt($this->gui);
	        
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung->data['art']))
	            {
	                $gewaesserbenutzungArt = $gwa->find_where('id=' . $gewaesserbenutzung->data['art']);
	                if(!empty($gewaesserbenutzungArt))
	                {
	                    $gewaesserbenutzung->gewaesserbenutzungArt = $gewaesserbenutzungArt[0];
	                }
	            }
	        }
	        
	        $gwz = new GewaesserbenutzungenZweck($this->gui);
	        
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung->data['zweck']))
	            {
	                $gewaesserbenutzungZweck = $gwz->find_where('id=' . $gewaesserbenutzung->data['zweck']);
	                if(!empty($gewaesserbenutzungZweck))
	                {
	                    $gewaesserbenutzung->gewaesserbenutzungZweck = $gewaesserbenutzungZweck[0];
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