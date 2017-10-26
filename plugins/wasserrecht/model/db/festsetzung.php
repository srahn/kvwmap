<?php
class Festsetzung extends Bescheid {

	protected $tableName = 'fiswrv_festsetzung';
	
	public $dokument_datum;
	public $summe_nicht_zugelassene_entnahmemengen;
	public $summe_zugelassene_entnahmemengen;
	public $summe_entnahmemengen;
	public $summe_zugelassenes_entgelt;
	public $summe_nicht_zugelassenes_entgelt;
	public $summe_entgelt;
	
	public function find_where_with_subtables($where, $order = NULL, $select = '*')
	{
	    $this->log->log_info('*** Festsetzung->find_where_with_subtables ***');
	    $this->log->log_debug('where: ' . $where);
	    
	    $bescheide = parent::find_where_with_subtables($where, $order, $select);
	    
	    if(!empty($bescheide))
	    {
	        foreach ($bescheide AS $bescheid)
	        {
	            if(!empty($bescheid))
	            {
	                $bescheid->dokument_datum=$bescheid->data['dokument_datum'];
	                
	                $bescheid->summe_nicht_zugelassene_entnahmemengen=$bescheid->data['summe_nicht_zugelassene_entnahmemengen'];
	                $bescheid->summe_zugelassene_entnahmemengen=$bescheid->data['summe_zugelassene_entnahmemengen'];
	                $bescheid->summe_entnahmemengen=$bescheid->data['summe_entnahmemengen'];
	                
	                $bescheid->summe_zugelassenes_entgelt=$bescheid->data['summe_zugelassenes_entgelt'];
	                $bescheid->summe_nicht_zugelassenes_entgelt=$bescheid->data['summe_nicht_zugelassenes_entgelt'];
	                $bescheid->summe_entgelt=$bescheid->data['summe_entgelt'];
	            }
	        }
	    }
	    
	    return $bescheide;
	}
	
	public function getDokumentDatum() {
	    return $this->data['dokument_datum'];
	}

	public function createFestsetzung($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $nutzer,
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt)
	{
	    $bescheid_value_array = parent::createBescheid($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $datum, $nutzer);
	    
	    $this->addToArray($bescheid_value_array, 'dokument_datum', $dokumentDatum);
	    
	    $this->addToArray($bescheid_value_array, 'summe_nicht_zugelassene_entnahmemengen', $summeNichtZugelasseneEntnahmemengen);
	    $this->addToArray($bescheid_value_array, 'summe_zugelassene_entnahmemengen', $summeZugelasseneEntnahmemengen);
	    $this->addToArray($bescheid_value_array, 'summe_entnahmemengen', $summeEntnahmemengen);
	    
	    $this->addToArray($bescheid_value_array, 'summe_nicht_zugelassenes_entgelt', $summeNichtZugelassenesEntgelt);
	    $this->addToArray($bescheid_value_array, 'summe_zugelassenes_entgelt', $summeZugelassenesEntgelt);
	    $this->addToArray($bescheid_value_array, 'summe_entgelt', $summeEntgelt);
	    
	    return $this->create($bescheid_value_array);
	}
	
	public function updateFestsetzung($erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $nutzer,
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt)
	{
	    parent::updateBescheid($erhebungsjahr, $dokumentId, $datum, $nutzer);
	    
	    $this->updateData('dokument_datum', $dokumentDatum);
	    
	    $this->updateData('summe_nicht_zugelassene_entnahmemengen', $summeNichtZugelasseneEntnahmemengen);
	    $this->updateData('summe_zugelassene_entnahmemengen', $summeZugelasseneEntnahmemengen);
	    $this->updateData('summe_entnahmemengen', $summeEntnahmemengen);
	    
	    $this->updateData('summe_nicht_zugelassenes_entgelt', $summeNichtZugelassenesEntgelt);
	    $this->updateData('summe_zugelassenes_entgelt', $summeZugelassenesEntgelt);
	    $this->updateData('summe_entgelt', $summeEntgelt);
	    
	    $this->update();
	    return $this->getId();
	}
	
	public function deleteFestsetzungDokument()
	{
	    $this->set('dokument', '');
	    $this->update();
	    return $this->getId();
	}
}
?>
