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
	
    /**
     * {@inheritDoc}
     * @see WrPgObject::toString()
     */
    public function toString()
    {
        return parent::toString() . " IBAN: " . $this->getIBAN() . " BIC: " . $this->getBIC() . " Bankname: " . $this->getBankname() . " Verwendungszweck: " . $this->getVerwendungszweck() . " Personenkonto: " . $this->getPersonenkonto() . " Kassenzeichen: " . $this->getKassenzeichen();
    }
}
?>