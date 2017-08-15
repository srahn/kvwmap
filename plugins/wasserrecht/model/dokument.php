<?php
class Dokument extends WrPgObject {

	protected $tableName = 'dokument';
	
	public function createDocument($dokumentName, $pfad) {
	    if (!empty($dokumentName) && !empty($pfad))
	    {
	        return $this->create(
	            array(
	                'name' => $dokumentName,
	                'pfad' => $pfad
	            )
	            );
	    }
	}
	
	public function getPfad() {
	    return $this->data['pfad'];
	}
}       
?>
