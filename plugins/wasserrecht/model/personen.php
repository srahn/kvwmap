<?php
class Personen extends WrPgObject {

	protected $tableName = 'fiswrv_personen';
	
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
	
	public function getBezeichnung() {
	    $fieldname = 'bezeichnung';
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id = '" . $this->getId() . "';";
	    // 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, $fieldname);
	    // 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
}
?>