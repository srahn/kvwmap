<?php
class Personen extends WrPgObject {

	protected $tableName = 'fiswrv_personen';
	
	public $adresse;
	
	public function isWrzAdressat() {
	    
	    if(empty($this->data['wrzadressat']))
	    {
	        return false;
	    }
	    else
	    {
	        if($this->data['wrzadressat'] === 'ja')
	        {
	            return true;
	        }
	        else
	        {
	            return false;
	        }
	    }
	}
	
	public function getBehoerde() {
	    if(!empty($this->data))
	    {
	        return $this->data['behoerde'];
	    }
	    else
	    {
	        return null;
	    }
	}
	
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
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id = $1;";
	    // 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, array($this->getId()), $fieldname);
	    // 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
	
	public function toString()
	{
	    return parent::toString() . " isWrzAdressat: " . $this->isWrzAdressat() . " Behoerde: " . $this->getBehoerde() . (!empty($this->adresse) ? " " . $this->adresse->toString() : "");
	}
}
?>