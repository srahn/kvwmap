<?php
abstract class Bescheid extends WrPgObject 
{
    public $erhebungsjahr;
    public $datum;
    public $nutzer;
    public $dokument;
    
    public function find_where_with_subtables($where, $order = NULL, $select = '*')
    {
        $bescheide = $this->find_where($where, $order, $select);
        
        if(!empty($bescheide))
        {
            foreach ($bescheide AS $bescheid)
            {
                if(!empty($bescheid))
                {
                    $bescheid->erhebungsjahr=$bescheid->data['erhebungsjahr'];
                    $bescheid->datum=$bescheid->data['datum'];
                    $bescheid->nutzer=$bescheid->data['nutzer'];
                    
                    $dok = new Dokument($this->gui);
                    if(!empty($bescheid->data['dokument']))
                    {
                        $dokument = $dok->find_where('id=' . $bescheid->data['dokument']);
                        if(!empty($dokument))
                        {
                            $bescheid->dokument = $dokument[0];
                        }
                    }
                    
                }
            }
        }
        return $bescheide;
    }
    
    public function getErhebungsjahr() {
        return $this->data['erhebungsjahr'];
    }
    
    public function insertErhebungsjahr($erhebungsjahr) {
        $this->set('erhebungsjahr', $erhebungsjahr);
        $this->update();
    }
    
    public function isFreigegeben()
    {
        $datum = $this->getDatum();
        if(!empty($datum))
        {
            return true;
        }
        
        return false;
    }
    
    
    public function getDatum() {
        return $this->data['datum'];
    }
    
    public function getDatumHTML() {
        $datum = $this->getDatum();
        if(!empty($datum))
        {
            return "<div>" . $datum . "</div>";
        }
        
        return "<div style=\"color: red;\">Nicht aufgefordert<div>";
    }
    
    public function insertDatum($dateValue = NULL) {
        //if date is not set --> set it to today's date
        if(empty($dateValue))
        {
            $dateValue = date("d.m.Y");
        }
        
        $this->set('datum', $dateValue);
        $this->update();
    }
    
    public function getNutzer() {
        return $this->data['nutzer'];
    }
    
    public function insertNutzer($nutzer) {
        $this->set('nutzer', $nutzer);
        $this->update();
    }
    
    public function getDokument() {
        return $this->data['dokument'];
    }
    
    public function insertDokument($id) {
        if(!empty($id))
        {
            $this->set('dokument', $id);
            $this->update();
        }
    }
    
    public function createBescheid($gewaesserbenutzungen, $erhebungsjahr, $dokumentId, $dateVale, $nutzer)
    {
        $this->debug->write('*** createBescheid ***', 4);
        
        if (!empty($gewaesserbenutzungen))
        {
            $bescheid_value_array = array
            (
                'gewaesserbenutzungen' => $gewaesserbenutzungen
            );
            
            $this->addToArray($bescheid_value_array, 'erhebungsjahr', $erhebungsjahr);
            $this->addToArray($bescheid_value_array, 'dokument', $dokumentId);
            $this->addToArray($bescheid_value_array, 'datum', $dateVale);
            $this->addToArray($bescheid_value_array, 'nutzer', $nutzer);
            
            // 	        print_r($bescheid_value_array);
            $this->debug->write('bescheid_value_array: ' . var_export($bescheid_value_array, true), 4);
            
            return $this->create(
                $bescheid_value_array
                );
        }
    }
    
    public function updateBescheid($erhebungsjahr, $dokumentId, $dateVale, $nutzer)
    {
        $this->debug->write('*** updateBescheid ***', 4);
        
        $this->updateData('erhebungsjahr', $erhebungsjahr);
        $this->updateData('dokument', $dokumentId);
        $this->updateData('datum', $dateVale);
        $this->updateData('nutzer', $nutzer);
        
        $this->debug->write('kvp update: ' . var_export($this->getKVP(), true), 4);
        
        $this->update();
        
        return $this->getId();
    }
}
?>