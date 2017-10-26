<?php
class Aufforderung extends Bescheid {

	protected $tableName = 'fiswrv_aufforderung';
	
	public function createAufforderung($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $dateVale)
	{
	    $bescheid_value_array = parent::createBescheid($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $dateVale, null);
        return $this->create($bescheid_value_array);
	}
	
	public function updateAufforderung($erhebungsjahr, $dokumentId, $dateVale)
	{
	    parent::updateBescheid($erhebungsjahr, $dokumentId, $dateVale, null);
	    $this->update();
	    return $this->getId();
	}
}
?>