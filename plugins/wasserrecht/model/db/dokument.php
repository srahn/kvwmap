<?php
class Dokument extends WrPgObject {

    protected $tableName = 'fiswrv_dokument';
    
    private $wrz_ids = array();
	
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
    /**
     * @return multitype:
     */
    public function getWrz_ids()
    {
        return $this->wrz_ids;
    }

    /**
     * @param multitype: $wrz_ids
     */
    public function setWrz_ids($wrz_ids)
    {
        $this->wrz_ids = $wrz_ids;
    }
    
    public function addWrz_id($wrz_id)
    {
        $this->wrz_ids[] = $wrz_id;
    }
    
    public function getWrz_idsString()
    {
        return $this->getToStringFromArray($this->wrz_ids);
    }
}       
?>