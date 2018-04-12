<?php
class GewaesserbenutzungenWeeSatz extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen_wee_satz';
	
	public function getSatzGW_Befreit() {
	    return $this->data['satz_gw_befreit'];
	}
	
	public function getSatzGW_Zugelassen() {
	    return $this->data['satz_gw_zugelassen'];
	}
	
	public function getSatzGW_NichtZugelassen() {
	    return $this->data['satz_gw_nicht_zugelassen'];
	}
	
	public function getSatzGW_ZugelassenErmaessigt() {
	    return $this->data['satz_gw_zugelassen_ermaessigt'];
	}
	
	public function getSatzGW_NichtZugelassenErmaessigt() {
	    return $this->data['satz_gw_nicht_zugelassen_ermaessigt'];
	}
	
	public function getSatzOW_Befreit() {
	    return $this->data['satz_ow_befreit'];
	}
	
	public function getSatzOW_Zugelassen() {
	    return $this->data['satz_ow_zugelassen'];
	}
	
	public function getSatzOW_NichtZugelassen() {
	    return $this->data['satz_ow_nicht_zugelassen'];
	}
	
	public function getSatzOW_ZugelassenErmaessigt() {
	    return $this->data['satz_ow_zugelassen_ermaessigt'];
	}
	
	public function getSatzOW_NichtZugelassenErmaessigt() {
	    return $this->data['satz_ow_nicht_zugelassen_ermaessigt'];
	}
}
?>