<?php
class WasserrechtlicheZulassungen extends WrPgObject {

	protected $tableName = 'wasserrechtliche_zulassungen';
	
	public $gueltigkeitsJahr;
	public $gueltigkeit;
	public $behoerde;
	public $adressat;
	public $anlagen;
	public $aufforderung_dokument;
	public $gewaesserbenutzungen;

	public function find_gueltigkeitsjahre($gui) {
	    
		$results = $this->find_where('gueltigkeit IS NOT NULL', 'id');
		$wrzProGueltigkeitsJahr = new WRZProGueltigkeitsJahr();
		
		if(!empty($results))
		{
			$wasserrechtlicheZulassungGueltigkeitJahrReturnArray = array();
			foreach($results AS $result)
			{
// 				var_dump($this->debug);
			    $this->debug->write('result: ' . var_export($result->data, true), 4);
				
			    $year = $this->getDependentObjects($gui, $result);
			    
			    if (!in_array($year, $wasserrechtlicheZulassungGueltigkeitJahrReturnArray))
			    {
			        $wasserrechtlicheZulassungGueltigkeitJahrReturnArray[] = $year;
			    }
					
			    $wrzProGueltigkeitsJahr->wasserrechtlicheZulassungen[]=$result;
			}
			$wrzProGueltigkeitsJahr->gueltigkeitsJahre=$wasserrechtlicheZulassungGueltigkeitJahrReturnArray;
			return $wrzProGueltigkeitsJahr;
// 			return $wasserrechtlicheZulassungGueltigkeitJahrReturnArray;
		}
		
		$wrzProGueltigkeitsJahr->gueltigkeitsJahre=array('n/a');
		return $wrzProGueltigkeitsJahr;
// 		return array('n/a');
	}
	
	public function getDependentObjects($gui, &$result) 
	{
	    $year = null;
	    
	    if(!empty($result))
	    {
	        $wrzGueltigkeit = new WasserrechtlicheZulassungenGueltigkeit($gui);
	        $wasserrechtlicheZulassungGueltigkeit = $wrzGueltigkeit->find_by_id($gui, 'id', $result->data['gueltigkeit']);
	        $result->gueltigkeit = $wasserrechtlicheZulassungGueltigkeit;
	        if(!empty($wasserrechtlicheZulassungGueltigkeit))
	        {
	            $datum = $wasserrechtlicheZulassungGueltigkeit->getGueltigBis();
	            //var_dump($datum);
	            $date = DateTime::createFromFormat("d.m.Y", $datum);
	            $year = $date->format("Y");
	            $result->gueltigkeitsJahr=$year;
	        }
	        
	        //get the 'Adressat'
	        if(!empty($result->data['adressat']))
	        {
	            $person = new Personen($gui);
	            $adressat = $person->find_by_id($gui, 'id', $result->data['adressat']);
	            if(!empty($adressat->data['adresse']))
	            {
	                $adress = new AdresseKlasse($gui);
	                $adresse = $adress->find_by_id($gui, 'id', $adressat->data['adresse']);
	                $adressat->adresse = $adresse;
	            }
	            $result->adressat = $adressat;
	        }
	        
	        //get the 'Behoerde'
	        if(!empty($result->data['ausstellbehoerde']))
	        {
	            $bh = new Behoerde($gui);
	            $behoerde = $bh->find_by_id($gui, 'id', $result->data['ausstellbehoerde']);
	            $result->behoerde = $behoerde;
	        }
	        
	        //get the 'Anlage'
	        if(!empty($result->data['anlage']))
	        {
	            $anlage = new Anlage($gui);
	            $anlagen = $anlage->find_where('id=' . $result->data['anlage']);
	            if(!empty($anlagen) && count($anlagen) > 0 && !empty($anlagen[0]))
	            {
	                $result->anlagen = $anlagen[0];
	            }
	        }
	        
	        //get the 'Aufforderung Dokument'
	        if(!empty($result->getAufforderungDokument()))
	        {
	            $dokument = new Dokument($gui);
	            $dokumente = $dokument->find_where('id=' . $result->getAufforderungDokument());
	            if(!empty($dokumente))
	            {
	                $result->aufforderung_dokument = $dokumente[0];
	            }
	        }
	        
	        //get the 'Aufforderung'
	        // 					if(!empty($result->data['aufforderung']))
	        // 					{
	        // 					    $aufforderungen = new Aufforderung($gui);
	        // 					    $aufforderung = $aufforderungen->find_where('id=' . $result->data['aufforderung']);
	        // 					    if(!empty($aufforderung) && count($aufforderung) > 0 && !empty($aufforderung[0]))
	        // 					    {
	        // 					        $result->aufforderung = $aufforderung[0];
	        // 					    }
	        // 					}
	        
	        //get the 'Gewaesserbenutzungen'
	        $gewaesserbenutzung = new Gewaesserbenutzungen($gui);
	        $gewaesserbenutzungen = $gewaesserbenutzung->find_where_with_subtables('wasserrechtliche_zulassungen=' . $result->getId());
	        $result->gewaesserbenutzungen = $gewaesserbenutzungen;
	    }
	    
	    return $year;
	}
	
	public function toString() {
	    return "gueltigkeitsJahr: " . $this->gueltigkeitsJahr . (!empty($this->behoerde) ? " behoerde: " . $this->behoerde->data['id'] : "" ) . (!empty($this->adressat) ? " adressat: " . $this->adressat->data['id'] : "");
	}
	
	public function getBehoerdeName() {
	    return !empty($this->behoerde) ?  $this->behoerde->getName() : null;
	}
	
	public function getBehoerdeId() {
	    return !empty($this->behoerde) ?  $this->behoerde->getId() : null;
	}
	
// 	public function insertAufforderungId($aufforderungsId) {
// 	    if(!empty($aufforderungsId))
// 	    {
// 	        $this->set('aufforderung', $aufforderungsId);
// 	        $this->update();
// 	    }
// 	}

    public function getAufforderungDatumAbsend() {
        return $this->data['aufforderung_datum_absend'];
    }
	
	public function getAufforderungDatumAbsendHTML() {
	    $datumAbsend = $this->getAufforderungDatumAbsend();
	    if(!empty($datumAbsend))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumAbsend . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht aufgefordert<div>";
	}
	
	public function insertAufforderungDatumAbsend($dateValue = NULL) {
	    //if date is not set --> set it to today's date
	    if(empty($dateValue))
	    {
	        $dateValue = date("d.m.Y");
	    }
	    
	    $this->set('aufforderung_datum_absend', $dateValue);
	    $this->update();
	    
// 	    $this->create(
// 	        array(
// 	            'aufforderung_datum_absend' => $dateValue
// 	        )
// 	        );
	}
	
	public function getAufforderungDokument() {
	    return $this->data['aufforderung_dokument'];
	}
	
	public function insertAufforderungDokument($id) {
	    if(!empty($id))
	    {
	        $this->set('aufforderung_dokument', $id);
	        $this->update();
	    }
	}
	
	public function getErklaerungDatum() {
	    return $this->data['erklaerung_datum'];
	}
	
	public function getErklaerungDatumHTML() {
	    $datumErklaerung = $this->getErklaerungDatum();
	    if(!empty($datumErklaerung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumErklaerung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht erklärt</div>";
	}
	
	public function insertErklaerungDatum($dateValue = NULL) {
	    //if date is not set --> set it to today's date
	    if(empty($dateValue))
	    {
	        $dateValue = date("d.m.Y");
	    }
	    
	    $this->set('erklaerung_datum', $dateValue);
	    $this->update();
	    
	    // 	    $this->create(
	    // 	        array(
	    // 	            'aufforderung_datum_absend' => $dateValue
	    // 	        )
	    // 	        );
	}
}
?>
