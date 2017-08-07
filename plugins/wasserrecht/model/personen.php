<?php
class Personen extends WrPgObject {

	protected $tableName = 'personen';
	
	public $adresse;
	
	public function getAdresseStrasse() {
	    
// 	    var_dump($this->adresse);
	    
	    if(empty($this->adresse))
	    {
	        return "";
	    }
	    else
	    {
	        return $this->adresse->getStrasse();
	    }
	}
	
	public function getAdresseHausnummer() {
	    
	    if(empty($this->adresse))
	    {
	        return "";
	    }
	    else
	    {
	        return $this->adresse->getHausnummer();
	    }
	}
	
	public function getAdressePLZ() {
	    
	    if(empty($this->adresse))
	    {
	        return "";
	    }
	    else
	    {
	        return $this->adresse->getPLZ();
	    }
	}
	
	public function getAdresseOrt() {
	    
	    if(empty($this->adresse))
	    {
	        return "";
	    }
	    else
	    {
	        return $this->adresse->getOrt();
	    }
	}
}
?>