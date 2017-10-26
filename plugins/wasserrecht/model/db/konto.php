<?php
class KontoKlasse extends WrPgObject {

	protected $tableName = 'fiswrv_konto';
	
	public function getIBAN() {
	    return $this->data['iban'];
	}
	
	public function getBIC() {
	    return $this->data['bic'];
	}
	
	public function getBankname() {
	    return $this->data['bankname'];
	}
	
	public function getVerwendungszweck() {
	    return $this->data['verwendungszweck'];
	}
	
	public function getPersonenkonto() {
	    return $this->data['personenkonto'];
	}
	
	public function getKassenzeichen() {
	    return $this->data['kassenzeichen'];
	}
}
?>
