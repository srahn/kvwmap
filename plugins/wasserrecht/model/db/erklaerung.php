<?php
class Erklaerung extends Bescheid {

	protected $tableName = 'fiswrv_erklaerung';
	
	public function createErklaerung($gewaesserbenutzungen, $erhebungsjahr, $dateVale, $nutzer)
	{
	    $bescheid_value_array = parent::createBescheid($gewaesserbenutzungen, $erhebungsjahr, null, $dateVale, $nutzer);
	    return $this->create($bescheid_value_array);
	}
	
	public function updateErklaerung($erhebungsjahr, $dateVale, $nutzer)
	{
	    parent::updateBescheid($erhebungsjahr, null, $dateVale, $nutzer);
	    $this->update();
	    return $this->getId();
	}
}
?>