<?php
class Aufforderung extends Bescheid {

	protected $tableName = 'fiswrv_aufforderung';
	
	public function find_where_with_subtables($where, $order = NULL, $select = '*')
	{
	    return parent::find_where_with_subtables($where, $order, $select);
	}
	
	public function createAufforderung($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $dateVale, $nutzer)
	{
        return parent::createBescheid($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $dateVale, $nutzer);
	}
	
	public function updateAufforderung($erhebungsjahr, $dokumentId, $dateVale, $nutzer)
	{
	    return parent::updateBescheid($erhebungsjahr, $dokumentId, $dateVale, $nutzer);
	}
}
?>