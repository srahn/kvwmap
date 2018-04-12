<?php
class Behoerde extends WrPgObject {

	protected $tableName = 'fiswrv_behoerde';
	
	public $adresse;
	public $art;
	public $konto;
	
	public function toString() 
	{
	    return parent::toString() . (!empty($this->adresse) ? " " . $this->adresse->toString() : "") . (!empty($this->art) ? " " . $this->art->toString() : "") . (!empty($this->konto) ? " " . $this->konto->toString() : "");
	}
}
?>