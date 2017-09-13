<?php
class Dokument extends WrPgObject {

	protected $tableName = 'fiswrv_dokument';
	
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
	
	public function deleteDocument($id) {
	    if(!empty($id))
	    {
	        return $this->delete_by('id', $id);
	    }
	}
	
	public function getPfad() {
	    return $this->data['pfad'];
	}
}       
?>
