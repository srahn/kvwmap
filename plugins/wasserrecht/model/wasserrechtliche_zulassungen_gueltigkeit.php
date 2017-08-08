<?php
class WasserrechtlicheZulassungenGueltigkeit extends WrPgObject {

	protected $tableName = 'wasserrechtliche_zulassungen_gueltigkeit';
	
	public function getHinweis() {
	    
	    /**
	     * abgelaufen
	     */
// 	    $gueltigSeitDate = convertStringToDate($this->getGueltigSeit());
// 	    $gueltigBisDate = $this->convertStringToDate($this->getGueltigBis());
	    $gueltigBisDate = $this->getGueltigBis();
	    $today = date("d.m.Y");
	    
// 	    if(!empty($gueltigSeitDate) && !empty($gueltigBisDate))
        $this->debug->write('gueltigBisDate: ' . var_export($gueltigBisDate, true), 4);
        $this->debug->write('today: ' . var_export($today, true), 4);
        
        if(!empty($gueltigBisDate))
	    {
	        if($gueltigBisDate < $today)
	        {
	            return "abgelaufen";
	        }
	    }
	    
	    /**
	     * freigegeben / nicht freigegeben
	     */
	    
	    /**
	     * geändert
	     */
	    
	    /**
	     * im Jahr neu angelegt
	     */
	    
	    return "";
	}
	    
	public function getGueltigBis() {
	    return $this->data['gueltig_bis'];
	}
	
	public function getGueltigSeit() {
	    return $this->data['gueltig_seit'];
	}
	
	public function convertStringToDate($inputString) {
	    if(!empty($inputString))
	    {
	        return DateTime::createFromFormat("d.m.Y", $inputString);
	    }
	    
	    return null;
	}
}
?>