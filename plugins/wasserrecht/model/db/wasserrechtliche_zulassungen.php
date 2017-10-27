<?php
class WasserrechtlicheZulassungen extends WrPgObject {

	protected $tableName = 'fiswrv_wasserrechtliche_zulassungen';
	
	public $gueltigkeitsJahre;
// 	public $gueltigkeit;
	public $behoerde;
	public $adressat;
	public $anlagen;
	public $gewaesserbenutzungen;
	public $vorgaenger;
	public $nachfolger;
	
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
			        
			        $resultYears = $this->getDependentObjects($gui, $result);
			        $this->log->log_debug('result years: ' . var_export($resultYears, true));
			        
			        if(!empty($resultYears))
			        {
// 			            foreach ($resultYears as $year)
// 			            {
// 			                if(!in_array($year, $wasserrechtlicheZulassungGueltigkeitJahrReturnArray))
// 			                {
// 			                    $wasserrechtlicheZulassungGueltigkeitJahrReturnArray[] = $year;
// 			                }
// 			            }
			            
			            $wrzProGueltigkeitsJahre->gueltigkeitsJahre = $resultYears;
			            $wrzProGueltigkeitsJahre->wasserrechtlicheZulassung=$result;
			        }
			        
			        $wrzProGueltigkeitsJahreArray->wrzProGueltigkeitsJahre[] = $wrzProGueltigkeitsJahre;
			    }
			}
			
			//check if all years are added
			$today = new DateTime('now');
			$this->log->log_debug('today: ' . var_export($today, true));
			$fourYearsAgo = new DateTime('now');
			$fourYearsAgo = $fourYearsAgo->modify('-4 years');
			$this->log->log_debug('fourYearsAgo: ' . var_export($fourYearsAgo, true));
			$years = $this->date->addAllYearsBetweenTwoDates($fourYearsAgo, $today);
			$this->log->log_debug('years: ' . var_export($years, true));
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
	    $this->log->log_info('*** getDependentObjects ***');
	    return $this->getDependentObjectsInternal($gui, $result, true);
	}
	
	public function getDependentObjectsInternal(&$gui, &$result, $addGewaesserbenutzungen) 
	{
	    $this->log->log_info('*** getDependentObjects Internal ***');
	    $this->log->log_debug('addGewaesserbenutzungen: ' . var_export($addGewaesserbenutzungen, true));
	    
	    $years = null;
	    
	    if(!empty($result))
	    {
	        $this->log->log_debug('result id: ' . var_export($result->getId(), true));
	        
// 	        $wrzGueltigkeit = new WasserrechtlicheZulassungenGueltigkeit($gui);
// 	        $wasserrechtlicheZulassungGueltigkeit = $wrzGueltigkeit->find_by_id($gui, 'id', $result->data['gueltigkeit']);
// 	        $result->gueltigkeit = $wasserrechtlicheZulassungGueltigkeit;
// 	        if(!empty($wasserrechtlicheZulassungGueltigkeit))
// 	        {
                $gueltigSeit = $result->getGueltigSeit();
                $this->log->log_debug('gueltigSeit: ' . var_export($gueltigSeit, true));
                //backup, falls andere Dates nicht gesetzt wurden
                if(empty($gueltigSeit))
                {
                    $getFassungDatum = $result->getFassungDatum();
                    $this->log->log_debug('getFassungDatum: ' . var_export($getFassungDatum, true));
                    if(empty($getFassungDatum))
                    {
                        $gueltigSeit = $result->getDatum();
                        $this->log->log_debug('gueltigSeit set for datum: ' . var_export($gueltigSeit, true));
//                         $this->date->addYearToArray($getDatum, $years);
                    }
                    else
                    {
                        $gueltigSeit = $getFassungDatum;
                        $this->log->log_debug('gueltigSeit set for Fassung datum: ' . var_export($gueltigSeit, true));
//                         $this->date->addYearToArray($getFassungDatum, $years);
                    }
                }
                
                $befristetBis = $result->getBefristetBis();
                $this->log->log_debug('befristetBis: ' . var_export($befristetBis, true));
                if(empty($befristetBis))
                {
                    $befristetBis = $this->date->getToday();
                    $this->log->log_debug('befristetBis set for today: ' . var_export($befristetBis, true));
                }
//                
                $years = $this->date->addAllYearsBetweenTwoDates($this->date->convertStringToDate($gueltigSeit), $this->date->convertStringToDate($befristetBis));
                $this->log->log_debug('years: ' . var_export($years, true));
                
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
	            $this->log->log_debug('adressat id: ' . var_export($adressat->getId(), true));
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
	            $this->log->log_debug('behoerde id: ' . var_export($behoerde->getId(), true));
	            $result->behoerde = $behoerde;
	        }
	        
	        //get the 'Anlage'
	        if(!empty($result->data['anlage']))
	        {
	            $anlage = new Anlage($gui);
	            $anlagen = $anlage->find_where('id=' . $result->data['anlage']);
	            if(!empty($anlagen) && count($anlagen) > 0 && !empty($anlagen[0]))
	            {
	                $this->log->log_debug('anlagen[0] id: ' . var_export($anlagen[0]->getId(), true));
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
	            $this->log->log_debug('gewaesserbenutzungen count: ' . count( $result->gewaesserbenutzungen));
	        }
	        
	        //get the Vorgaenger
	        if(!empty($result->data['vorgaenger']))
	        {
	            $wasserrechtlicheZulassungClass = new WasserrechtlicheZulassungen($gui);
	            $wasserrechtlicheZulassung = $wasserrechtlicheZulassungClass->find_by_id($gui, 'id', $result->data['vorgaenger']);
	            if(!empty($wasserrechtlicheZulassung))
	            {
	                $result->vorgaenger=$wasserrechtlicheZulassung;
	                $this->log->log_debug('Vorgänger ' . $result->vorgaenger->getId());
	            }
	        }
	        
	        //get the Nachfolger
	        if(!empty($result->data['nachfolger']))
	        {
	            $wasserrechtlicheZulassungClass = new WasserrechtlicheZulassungen($gui);
	            $wasserrechtlicheZulassungen = $wasserrechtlicheZulassungClass->find_by_id($gui, 'id', $result->data['nachfolger']);
	            if(!empty($wasserrechtlicheZulassung))
	            {
	                $result->nachfolger=$wasserrechtlicheZulassung;
	                $this->log->log_debug('Nachfolger ' . $result->nachfolger->getId());
	            }
	        }
	        
	        if(empty($result->gewaesserbenutzungen) || empty($result->gewaesserbenutzungen[0]))
	        {
	            return null;
	        }
	    }
	    
	    return $years;
	}
	
	public function getHinweis() 
	{
	    $this->log->log_info('*** getHinweis ***');
	    
	    $hinweise = array();
	    
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
	            $hinweise[] = "abgelaufen";
	        }
	    }
	    
	    /**
	     * freigegeben / nicht freigegeben
	     */
	    if(!$this->isFreigegeben())
	    {
	        $hinweise[] = "nicht freigegeben";
	    }
	    
	    /**
	     * geändert
	     */
	    $vorgaengerYear = null;
	    if(!empty($this->getVorgaenger()) && !empty($this->vorgaenger))
	    {
	        $vorgaengerDatum = $this->vorgaenger->getDatum();
	        if(!empty($vorgaengerDatum))
	        {
	            $vorgaengerYear = $this->date->getYearFromDateString($vorgaengerDatum);
	        }
	    }
	    
	    $nachfolgerYear = null;
	    if(!empty($this->getNachfolger()) && !empty($this->nachfolger))
	    {
	        $nachfolgerDatum = $this->nachfolger->getDatum();
	        if(!empty($nachfolgerDatum))
	        {
	            $nachfolgerYear = $this->date->getYearFromDateString($nachfolgerDatum);
	        }
	    }
	    
	    if(!empty($vorgaengerYear) || !empty($nachfolgerYear))
	    {
	        $erhebungsYear = $this->date->getYearFromDateString($this->getDatum());
	        if(!empty($erhebungsYear))
	        {
	            if((!empty($vorgaengerYear) && $vorgaengerYear === $erhebungsYear) || (!empty($nachfolgerYear) && $nachfolgerYear === $erhebungsYear))
	            {
	                $this->log->log_debug('WrZ id: ' . var_export($this->getId(), true));
	                $this->log->log_debug('erhebungsYear: ' . var_export($erhebungsYear, true));
	                $this->log->log_debug('vorgaengerYear: ' . var_export($vorgaengerYear, true));
	                $this->log->log_debug('nachfolgerYear: ' . var_export($nachfolgerYear, true));
	                
	                $hinweise[] = "geändert";
	            }
	        }
	    }
	    
	    /**
	     * im Jahr neu angelegt
	     */
	    $gueltigSeit = $this->getGueltigSeit();
	    $this->log->log_debug('gueltigSeit: ' . var_export($gueltigSeit, true));
// 	    $gueltigSeitDate = $this->date->convertStringToDate($gueltigSeit);
// 	    $this->log->log_debug('gueltigSeit Date: ' . var_export($gueltigSeit, true));
	    if(!empty($gueltigSeit))
	    {
	        if($gueltigSeit < $today)
	        {
	            $hinweise[] = "Im Jahr neu angelegt";
	        }
	    }
	    
	    $this->log->log_debug('hinweise: ' . var_export($hinweise, true));
	    
	    return $hinweise;
	}
	
	public function getHinweisHTML()
	{
	    $hinweise = $this->getHinweis();
	    if(!empty($hinweise))
	    {
	        $hinweisString = "";
	        foreach ($hinweise as $hinweis)
	        {
	            if(!empty($hinweis))
	            {
	                $hinweisString = $hinweisString . $hinweis . "\n";
	            }
	        }
	        return "<div style='color: red; text-decoration: underline;' title='" . $hinweisString ."' >vorhanden</div>";
	    }
	    else
	    {
	        return "keine";
	    }
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
	
	public function getVorgaenger() {
	    return $this->data['vorgaenger'];
	}
	
	public function getNachfolger() {
	    return $this->data['nachfolger'];
	}
	
	public function getFreigegeben() {
	    return $this->data['freigegeben'];
	}
	
	public function isFreigegeben() {
	    $freigegeben = $this->getFreigegeben();
	    if(empty($freigegeben))
	    {
	        return false;
	    }
	    else
	    {
	        if(in_array(strtolower($freigegeben), $this->isTrue))
	        {
	            return true;
	        }
	        else
	        {
	            return false;
	        }
	    }
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