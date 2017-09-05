<?php
class AdresseKlasse extends WrPgObject {

	protected $tableName = 'fiswrv_adresse';
	
	public function getStrasse() {
	    return $this->data['strasse'];
	}
	
	public function getHausnummer() {
	    return $this->data['hausnummer'];
	}
	
	public function getPLZ() {
	    return $this->data['plz'];
	}
	
	public function getOrt() {
	    return $this->data['ort'];
	}
}
?>
