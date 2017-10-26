<?php
class WasserrechtlicheZulassungen extends WrPgObject {

	protected $tableName = 'fiswrv_wasserrechtliche_zulassungen';
	
	public $gueltigkeitsJahre;
// 	public $gueltigkeit;
	public $behoerde;
	public $adressat;
	public $anlagen;
	public $gewaesserbenutzungen;
	
	public function getGueltigkeitsJahrString()
	{
	    return $this->getToStringFromArray($this->gueltigkeitsJahre);
	}

	public function find_gueltigkeitsjahre(&$gui) {
	    
	    $this->log->log_info('*** WasserrechtlicheZulassungen->find_gueltigkeitsjahre ***');
	    
		$results = $this->find_where('1=1', 'id');
		
		$wrzProGueltigkeitsJahreArray = new WRZProGueltigkeitsJahreArray($gui);
		
		if(!empty($results))
		{
			$wasserrechtlicheZulassungGueltigkeitJahrReturnArray = array();
			
			foreach($results AS $result)
			{
			    if(!empty($result))
			    {
			        $this->log->log_debug('result id: ' . var_export($result->data['id'], true));
			        $wrzProGueltigkeitsJahre = new WRZProGueltigkeitsJahre();
			        
			        $years = $this->getDependentObjects($gui, $result);
			        $this->log->log_debug('years: ' . var_export($years, true));
			        
			        if(!empty($years))
			        {
			            foreach ($years as $year)
			            {
			                if(!in_array($year, $wasserrechtlicheZulassungGueltigkeitJahrReturnArray))
			                {
			                    $wasserrechtlicheZulassungGueltigkeitJahrReturnArray[] = $year;
			                }
			            }
			            
			            $wrzProGueltigkeitsJahre->gueltigkeitsJahre = $years;
			            $wrzProGueltigkeitsJahre->wasserrechtlicheZulassung=$result;
			        }
			        
			        $wrzProGueltigkeitsJahreArray->wrzProGueltigkeitsJahre[] = $wrzProGueltigkeitsJahre;
			    }
			}
			
			//check if all years are added
			$today = new DateTime('now');
			$gui->log->log_debug('today: ' . var_export($today, true));
			$fourYearsAgo = new DateTime('now');
			$fourYearsAgo = $fourYearsAgo->modify('-4 years');
// 			$fourYearsAgo = strtotime("-4 year", time());
// 			$fourYearsAgoString = date("Y-m-d", $fourYearsAgo);
			$gui->log->log_debug('fourYearsAgo: ' . var_export($fourYearsAgo, true));
			$years = WasserrechtlicheZulassungen::addAllYearsBetweenTwoDates($gui, $fourYearsAgo, $today);
			$gui->log->log_debug('years: ' . var_export($years, true));
            foreach ($years as $year)
            {
                if(!in_array($year, $wasserrechtlicheZulassungGueltigkeitJahrReturnArray))
                {
                    $wasserrechtlicheZulassungGueltigkeitJahrReturnArray[] = $year;
                }
            }
            
            //Liste nach Jahre sortieren
            sort($wasserrechtlicheZulassungGueltigkeitJahrReturnArray);
            
            $wrzProGueltigkeitsJahreArray->gueltigkeitsJahre=$wasserrechtlicheZulassungGueltigkeitJahrReturnArray;
            $this->log->log_debug('wrzProGueltigkeitsJahreArray->gueltigkeitsJahre: ' . var_export($wrzProGueltigkeitsJahreArray->gueltigkeitsJahre, true));
			
            return $wrzProGueltigkeitsJahreArray;
		}
		
		return $wrzProGueltigkeitsJahreArray;
	}
	
	public function getDependentObjects(&$gui, &$result)
	{
	    $gui->log->log_info('*** getDependentObjects ***');
	    return $this->getDependentObjectsInteral($gui, $result, true);
	}
	
	public function getDependentObjectsInteral(&$gui, &$result, $addGewaesserbenutzungen) 
	{
	    $gui->log->log_info('*** getDependentObjects Internal ***');
	    $gui->log->log_debug('addGewaesserbenutzungen: ' . var_export($addGewaesserbenutzungen, true));
	    
	    $years = null;
	    
	    if(!empty($result))
	    {
// 	        $wrzGueltigkeit = new WasserrechtlicheZulassungenGueltigkeit($gui);
// 	        $wasserrechtlicheZulassungGueltigkeit = $wrzGueltigkeit->find_by_id($gui, 'id', $result->data['gueltigkeit']);
// 	        $result->gueltigkeit = $wasserrechtlicheZulassungGueltigkeit;
// 	        if(!empty($wasserrechtlicheZulassungGueltigkeit))
// 	        {
                $gueltigSeit = $result->getGueltigSeit();
                $gui->log->log_debug('gueltigSeit: ' . var_export($gueltigSeit, true));
//                 $gui->addYearToArray($gueltigSeit, $result->gueltigkeitsJahr);
                $befristetBis = $result->getBefristetBis();
                $gui->log->log_debug('befristetBis: ' . var_export($befristetBis, true));
//                
                $years = WasserrechtlicheZulassungen::addAllYearsBetweenTwoDates($gui, WasserrechtlicheZulassungen::convertStringToDate($gueltigSeit), WasserrechtlicheZulassungen::convertStringToDate($befristetBis));
                $gui->log->log_debug('years: ' . var_export($years, true));
                
                //backup, falls andere Dates nicht gesetzt wurden
                if(empty($years))
                {
                    $getFassungDatum = $result->getFassungDatum();
                    if(empty($getFassungDatum))
                    {
                        $getDatum = $result->getDatum();
                        WasserrechtlicheZulassungen::addYearToArray($getDatum, $years);
                    }
                    else
                    {
                        WasserrechtlicheZulassungen::addYearToArray($getFassungDatum, $years);
                    }
                }
                
                $result->gueltigkeitsJahre = $years;
                
// 	        }
	        
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
	            $gui->log->log_debug('adressat id: ' . var_export($adressat->getId(), true));
	            $result->adressat = $adressat;
	        }
	        
	        //get the 'Behoerde'
	        if(!empty($result->data['ausstellbehoerde']))
	        {
	            $bh = new Behoerde($gui);
	            $behoerde = $bh->find_by_id($gui, 'id', $result->data['ausstellbehoerde']);
	            if(!empty($behoerde->data['adresse']))
	            {
	                $adress = new AdresseKlasse($gui);
	                $adresse = $adress->find_by_id($gui, 'id', $behoerde->data['adresse']);
	                $behoerde->adresse = $adresse;
	            }
	            if(!empty($behoerde->data['art']))
	            {
	                $behoerdeArt = new BehoerdeArt($gui);
	                $art = $behoerdeArt->find_by_id($gui, 'id', $behoerde->data['art']);
	                $behoerde->art = $art;
	            }
	            if(!empty($behoerde->data['konto']))
	            {
	                $account = new KontoKlasse($gui);
	                $konto = $account->find_by_id($gui, 'id', $behoerde->data['konto']);
	                $behoerde->konto = $konto;
	            }
	            $gui->log->log_debug('behoerde id: ' . var_export($behoerde->getId(), true));
	            $result->behoerde = $behoerde;
	        }
	        
	        //get the 'Anlage'
	        if(!empty($result->data['anlage']))
	        {
	            $anlage = new Anlage($gui);
	            $anlagen = $anlage->find_where('id=' . $result->data['anlage']);
	            if(!empty($anlagen) && count($anlagen) > 0 && !empty($anlagen[0]))
	            {
	                $gui->log->log_debug('anlagen[0] id: ' . var_export($anlagen[0]->getId(), true));
	                $result->anlagen = $anlagen[0];
	            }
	        }
	        
	        //get the 'Gewaesserbenutzungen'
	        if($addGewaesserbenutzungen)
	        {
	            $gewaesserbenutzung = new Gewaesserbenutzungen($gui);
	            $gewaesserbenutzungen = $gewaesserbenutzung->find_where_with_subtables('wasserrechtliche_zulassungen=' . $result->getId(), 'id');
	            // 	        $gewaesserbenutzungen = $gewaesserbenutzung->find_where_with_subtables('wasserrechtliche_zulassungen=' . $result->getId() . ' AND (art = 1 OR art = 2)', 'id');
	            $result->gewaesserbenutzungen = $gewaesserbenutzungen;
	            $gui->log->log_debug('gewaesserbenutzungen count: ' . count( $result->gewaesserbenutzungen));
	        }
	        
	        if(empty($result->gewaesserbenutzungen) || empty($result->gewaesserbenutzungen[0]))
	        {
	            return null;
	        }
	    }
	    
	    return $years;
	}
	
	public function getHinweis() {
	    
	    /**
	     * abgelaufen
	     */
	    // 	    $gueltigSeitDate = convertStringToDate($this->getGueltigSeit());
	    // 	    $befristetBisDate = $this->convertStringToDate($this->getBefristetBis());
	    $befristetBisDate = $this->getBefristetBis();
	    $today = date("d.m.Y");
	    
	    // 	    if(!empty($gueltigSeitDate) && !empty($befristetBisDate))
	    $this->log->log_debug('befristetBisDate: ' . var_export($befristetBisDate, true));
	    $this->log->log_debug('today: ' . var_export($today, true));
	    
	    if(!empty($befristetBisDate))
	    {
	        if($befristetBisDate < $today)
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
	    
	    return "keine";
	}
	
	public static function convertStringToDate($inputString) {
	    if(!empty($inputString))
	    {
	        return DateTime::createFromFormat("d.m.Y", $inputString);
	    }
	    
	    return null;
	}
	
	public static function getYearFromDate($date) {
	    if(!empty($date))
	    {
	        return $year = $date->format("Y");
	    }
	    
	    return null;
	}
	
	public static function getNextYear() {
	    return date('Y', strtotime('+1 year'));
	}
	
	public static function getThisYear() {
	    return date("Y");
	}
	
	public static function getLastYear() {
	    return date("Y", strtotime("-1 year"));
	}
	
	public static function addYearToArray($dateString, &$arrayToFill)
	{
	    if(!empty($dateString))
	    {
	        $date = WasserrechtlicheZulassungen::convertStringToDate($dateString);
	        $year = WasserrechtlicheZulassungen::getYearFromDate($date);
	        if(!empty($year) && !in_array($year, $arrayToFill))
	        {
	            $arrayToFill[]=$year;
	        }
	    }
	}
	
	public static function addAllYearsBetweenTwoDates(&$gui, $date1, $date2)
	{
	    $years = array();
	    
	    if(!empty($date1) && !empty($date2))
	    {
// 	        print_r($date1->format("Y"));
// 	        print_r($date2->format("Y"));
	        $diff = $date1->diff($date2);
// 	        print_r($diff->y);
	        $diffY = $diff->y;
	        if($diffY === 0)
	        {
	            $years[] = $date1->format("Y");
	        }
	        elseif($diffY > 0)
	        {
	            $diffY = $diffY + 1;
	            $years[] =  $date1->format("Y");
	            for ($i = 1; $i < $diffY; $i++)
	            {
	                $interval = new DateInterval('P1Y');
	                $nextYear = $date1->add($interval)->format('Y');
	                $gui->log->log_debug('nextYear: ' . var_export($nextYear, true));
	                $years[] = $nextYear;
	            }
	        }
	    }
	    elseif(!empty($date1))
	    {
	        $years[] = $date1->format("Y");
	    }
	    elseif(!empty($date2))
	    {
	        $years[] = $date2->format("Y");
	    }
	    
	    return $years;
	}
	
	public function getBehoerdeName() {
	    return !empty($this->behoerde) ?  $this->behoerde->getName() : null;
	}
	
	public function getBehoerdeId() {
	    return !empty($this->behoerde) ?  $this->behoerde->getId() : null;
	}
	
	public function getDatum() {
	    return $this->data['datum'];
	}
	
	public function getBefristetBis() {
	    return $this->data['befristet_bis'];
	}
	
	public function getGueltigSeit() {
	    return $this->data['gueltig_seit'];
	}
	
	public function getFassungDatum() {
	    return $this->data['fassung_datum'];
	}
	
	/**
     * @return mixed
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * @param mixed $anlagen
     */
    public function setAnlagen($anlagen)
    {
        $this->anlagen = $anlagen;
    }

    ////////////////////////////////////////////////////////////////////
	
	public function toString() {
	    return "gueltigkeitsJahre: " . print_r($this->gueltigkeitsJahre) . (!empty($this->behoerde) ? " behoerde: " . $this->behoerde->data['id'] : "" ) . (!empty($this->adressat) ? " adressat: " . $this->adressat->data['id'] : "");
	}
	
	public function getBezeichnung() {
	    $fieldname = 'bezeichnung';
// 	    $sql = "SELECT COALESCE(c.name,'') ||' (Aktenzeichen: '|| COALESCE(a.aktenzeichen,'') ||')'||' vom '|| COALESCE(a.datum_postausgang::text,'') AS " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . " a LEFT JOIN " . $this->schema . '.' . "wasserrechtliche_zulassungen_ausgangsbescheide_klasse c ON a.klasse = c.id WHERE a.id = '" . $this->getId() . "';";
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id =  $1;";
// 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, array($this->getId()), $fieldname);
// 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
	
    /**
     * {@inheritDoc}
     * @see WrPgObject::getName()
     */
    public function getName()
    {
        return $this->getBezeichnung();
    }
}
?>