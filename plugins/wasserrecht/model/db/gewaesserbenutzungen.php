<?php
class Gewaesserbenutzungen extends WrPgObject {

	protected $tableName = 'fiswrv_gewaesserbenutzungen';
	
	public $gewaesserbenutzungenUmfang;
	public $gewaesserbenutzungArt;
	public $gewaesserbenutzungZweck;
	public $teilgewaesserbenutzungen;
	public $aufforderungen;
	public $erklaerungen;
	public $festsetzungen;

	public function find_where_with_subtables($where, $order = NULL, $select = '*') {
	    $this->log->log_info('*** Gewaesserbenutzungen->find_where_with_subtables ***');
	    $this->log->log_debug('where: ' . $where);
	    $gewaesserbenutzungen = $this->find_where($where, $order, $select);
	    if(!empty($gewaesserbenutzungen))
	    {
	        foreach ($gewaesserbenutzungen AS $gewaesserbenutzung)
	        {
	            if(!empty($gewaesserbenutzung))
	            {
	                $this->log->log_trace('gewaesserbenutzung id: ' . $gewaesserbenutzung->getId());
	                $this->log->log_trace('gewaesserbenutzung kennummer: ' . var_export($gewaesserbenutzung->getKennummer(), true));
	                
	                $gwa = new GewaesserbenutzungenArt($this->gui);
	                if(!empty($gewaesserbenutzung->data['art']))
	                {
	                    $gewaesserbenutzungArt = $gwa->find_where('id=' . $gewaesserbenutzung->data['art']);
	                    if(!empty($gewaesserbenutzungArt))
	                    {
	                        $gewaesserbenutzung->gewaesserbenutzungArt = $gewaesserbenutzungArt[0];
	                    }
	                }
	                
	                $gwz = new GewaesserbenutzungenZweck($this->gui);
	                if(!empty($gewaesserbenutzung->data['zweck']))
	                {
	                    $gewaesserbenutzungZweck = $gwz->find_where('id=' . $gewaesserbenutzung->data['zweck']);
	                    if(!empty($gewaesserbenutzungZweck))
	                    {
	                        $gewaesserbenutzung->gewaesserbenutzungZweck = $gewaesserbenutzungZweck[0];
	                    }
	                }
	                
	                $gewaesserbenutzungUmfang = new GewaesserbenutzungenUmfang($this->gui);
	                $gewaesserbenutzungenUmfang = $gewaesserbenutzungUmfang->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	                $this->log->log_debug('gewaesserbenutzungenUmfang size: ' . count($gewaesserbenutzungenUmfang));
	                $gewaesserbenutzung->gewaesserbenutzungenUmfang = $gewaesserbenutzungenUmfang;
	                
	                $teilgewaesserbenutzung = new Teilgewaesserbenutzungen($this->gui);
	                $teilgewaesserbenutzungen = $teilgewaesserbenutzung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	                $gewaesserbenutzung->teilgewaesserbenutzungen = $teilgewaesserbenutzungen;
	                
	                //get the Aufforderungen
	                Gewaesserbenutzungen::getAufforderungen($this->gui, $gewaesserbenutzung);
	                
	                //get the Erklaerungen
	                Gewaesserbenutzungen::getErklaerungen($this->gui, $gewaesserbenutzung);
	                
	                //get the Festsetzungen
	                Gewaesserbenutzungen::getFestsetzungen($this->gui, $gewaesserbenutzung);
	            }
	        }
	        
	        return $gewaesserbenutzungen;
	    }
	    
	    return null;
	}
	
	/**
	 * get the Aufforderungen
	 * @param $gewaesserbenutzung
	 */
	public static function getAufforderungen(&$gui, &$gewaesserbenutzung)
	{
	    $aufforderung = new Aufforderung($gui);
	    $aufforderungen = $aufforderung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->aufforderungen = $aufforderungen;
	}
	
	/**
	 * get the Erklaerungen
	 * @param $gui
	 * @param $gewaesserbenutzung
	 */
	public static function getErklaerungen(&$gui, &$gewaesserbenutzung)
	{
	    $erklaerung = new Erklaerung($gui);
	    $erklaerungen = $erklaerung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->erklaerungen = $erklaerungen;
	}
	
	/**
	 * get the Festsetzungen
	 * @param $gui
	 * @param $gewaesserbenutzung
	 */
	public static function getFestsetzungen(&$gui, &$gewaesserbenutzung)
	{
	    $festsetzung = new Festsetzung($gui);
	    $festsetzungen = $festsetzung->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
	    $gewaesserbenutzung->festsetzungen = $festsetzungen;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function getUmfang()
	{
	    $gewaesserbenutzungenUmfang = $this->gewaesserbenutzungenUmfang;
	    if(!empty($gewaesserbenutzungenUmfang))
	    {
	        foreach ($gewaesserbenutzungenUmfang as $gewaesserbenutzungUmfang)
	        {
	            if(!empty($gewaesserbenutzungUmfang))
	            {
	                if(!empty($gewaesserbenutzungUmfang->name))
	                {
	                    if($gewaesserbenutzungUmfang->name->getAbkuerzung() === "max_ent_a")
	                    {
	                        return $gewaesserbenutzungUmfang->getWert();
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	public function getUmfangHTML()
	{
	    if(!empty($this->getUmfang()))
	    {
	        return number_format($this->getUmfang(), 0, '', ' ')  . " m³/a";
	    }
	    
	    return "";
	}
	
	public function getErlaubterOderReduzierterUmfang()
	{
	    $this->log->log_info('*** Gewaesserbenutzungen->getErlaubterOderReduzierterUmfang ***');
	    
	    $erlaubterUmfang = $this->getErlaubterUmfang();
	    $erlaubterUmfangReduziert = $this->getErlaubterUmfangReduziert();
	    
	    $this->log->log_debug('erlaubterUmfang: ' . var_export($erlaubterUmfang, true));
	    $this->log->log_debug('erlaubterUmfangReduziert: ' . var_export($erlaubterUmfangReduziert, true));
	    
	    if(!empty($erlaubterUmfangReduziert))
	    {
	        $this->log->log_debug('erlaubterUmfangReduziert returned');
	        return $erlaubterUmfangReduziert;
	    }
	    else
	    {
	        $this->log->log_debug('erlaubterUmfang returned');
	        return $erlaubterUmfang;
	    }
	}
	
	public function getErlaubterOderReduzierterUmfangHTML()
	{
	    if(!empty($this->getErlaubterOderReduzierterUmfang()))
	    {
	        return number_format($this->getErlaubterOderReduzierterUmfang(), 0, '', ' ')  . " m³/a";
	    }
	    
	    return "";
	}
	
	public function getErlaubterUmfang()
	{
	    return $this->getDataValue('max_ent_wee');
	}
	
	public function getErlaubterUmfangReduziert()
	{
	    return $this->getDataValue('max_ent_wee_reduziert');
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr)
	{
	    $teilgewaesserbenutzungen = $this->teilgewaesserbenutzungen;
	    
	    if(!empty($teilgewaesserbenutzungen) && !empty($erhebungsjahr))
	    {
	        $returnArray = array();
	        
	        foreach($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
	        {
	            if(!empty($teilgewaesserbenutzung))
	            {
	                if($teilgewaesserbenutzung->getErhebungsjahr() === $erhebungsjahr)
	                {
	                    $returnArray[] = $teilgewaesserbenutzung;
	                }
	            }
	        }
	        
	        return $returnArray;
	    }
	    
	    return null;
	}
	
	public function getUmfangAllerTeilbenutzungen($erhebungsjahr)
	{
	    $teilgewaesserbenutzungen = $this->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
	    return $this->getTeilgewaesserbenutzungUmfang($teilgewaesserbenutzungen);
	}
	
	public function getTeilgewaesserbenutzungUmfang(&$teilgewaesserbenutzungen)
	{
	    $gesamtUmfang = 0;
	    
	    if(!empty($teilgewaesserbenutzungen))
	    {
	        foreach($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
	        {
	            if(!empty($teilgewaesserbenutzung))
	            {
	                $gesamtUmfang = $gesamtUmfang + $teilgewaesserbenutzung->getUmfang();
	            }
	        }
	    }
	    
	    return $gesamtUmfang;
	}
	
	public function getTeilgewaesserbenutzungNichtZugelasseneMenge($erhebungsjahr, $teilgewaesserbenutzungId, &$zugelassenerUmfang, $alwaysRecalculateZugelassenerUmfang = false)
	{
	    $this->log->log_info('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungNichtZugelasseneMenge ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('teilgewaesserbenutzungId: ' . var_export($teilgewaesserbenutzungId, true));
	    $this->log->log_debug('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true));
	    $this->log->log_debug('alwaysRecalculateZugelassenerUmfang: ' . var_export($alwaysRecalculateZugelassenerUmfang, true));
	    
	    if(!empty($teilgewaesserbenutzungId))
	    {
	        $teilgewaesserbenutzungen = $this->getTeilgewaesserbenutzungenByErhebungsjahr($erhebungsjahr);
	        $gesamtUmfang = $this->getTeilgewaesserbenutzungUmfang($teilgewaesserbenutzungen);
	        
	        if(!empty($teilgewaesserbenutzungen))
	        {
	            foreach($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
	            {
	                if(!empty($teilgewaesserbenutzung))
	                {
	                    $teilgewaesserbenutzungUmfang = $teilgewaesserbenutzung->getUmfang();
	                    
	                    if($teilgewaesserbenutzung->getId() === $teilgewaesserbenutzungId)
	                    {
	                        if($gesamtUmfang <= $zugelassenerUmfang)
	                        {
	                            $this->log->log_trace('gesamtUmfang <= zugelassenerUmfang');
	                            if($alwaysRecalculateZugelassenerUmfang)
	                            {
	                                $zugelassenerUmfang = $zugelassenerUmfang - $teilgewaesserbenutzungUmfang;
	                                $this->log->log_trace('new zugelassenerUmfang : ' . var_export($zugelassenerUmfang, true));
	                            }
	                            return 0;
	                        }
	                        elseif ($zugelassenerUmfang === 0)
	                        {
	                            $this->log->log_trace('zugelassenerUmfang === 0');
	                            return $teilgewaesserbenutzungUmfang;
	                        }
	                        // 	                        elseif($bisZuDieserTeilgewaesserbenutzungKumulierterUmfang <= $zugelassenerUmfang)
	                        // 	                        {
	                        // 	                            return 0;
	                        // 	                        }
                            elseif($teilgewaesserbenutzungUmfang <= $zugelassenerUmfang)
                            {
                                $this->log->log_trace('teilgewaesserbenutzungUmfang <= zugelassenerUmfang');
                                $zugelassenerUmfang = $zugelassenerUmfang - $teilgewaesserbenutzungUmfang;
                                $this->log->log_trace('new zugelassenerUmfang : ' . var_export($zugelassenerUmfang, true));
                                return 0;
                            }
                            elseif($teilgewaesserbenutzungUmfang > $zugelassenerUmfang)
                            {
                                $this->log->log_trace('teilgewaesserbenutzungUmfang > zugelassenerUmfang');
                                $returnValue = $teilgewaesserbenutzungUmfang - $zugelassenerUmfang;
                                $zugelassenerUmfang = 0;
                                $this->log->log_trace('new zugelassenerUmfang : ' . var_export($zugelassenerUmfang, true));
                                $this->log->log_trace('returnValue: ' . var_export(returnValue, true));
                                return $returnValue;
                            }
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	public function getTeilgewaesserbenutzungEntgeltsatz($erhebungsjahr, $teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, &$zugelassenesEntnahmeEntgelt, &$nichtZugelassenesEntnahmeEntgelt, &$zugelassenerUmfang)
	{
	    $this->log->log_info('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungEntgeltsatz ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('getArtBenutzung: ' . var_export($getArtBenutzung, true));
	    $this->log->log_debug('getBefreiungstatbestaende: ' . var_export($getBefreiungstatbestaende, true));
	    $this->log->log_debug('getWiedereinleitungBearbeiter: ' . var_export($getWiedereinleitungBearbeiter, true));
	    $this->log->log_debug('zugelassenesEntnahmeEntgelt: ' . var_export($zugelassenesEntnahmeEntgelt, true));
	    $this->log->log_debug('nichtZugelassenesEntnahmeEntgelt: ' . var_export($nichtZugelassenesEntnahmeEntgelt, true));
	    $this->log->log_debug('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true));
	    
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($erhebungsjahr, $teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
// 	        echo "teilbenutzungNichtZugelasseneMenge: " . $teilbenutzungNichtZugelasseneMenge . " <br>";

	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                $entgeltsatz_nicht_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $returnArray = array(null, $entgeltsatz_nicht_zugelassen);
	                $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	                return $returnArray;
	            }
	            else
	            {
	                $entgeltsatz_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	                $entgeltsatz_nicht_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $returnArray = array($entgeltsatz_zugelassen, $entgeltsatz_nicht_zugelassen);
	                $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	                return $returnArray;
	            }
	        }
	        else
	        {
	            $entgeltsatz_zugelassen = $teilgewaesserbenutzung->getEntgeltsatz($getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	            $returnArray = array($entgeltsatz_zugelassen);
	            $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	            return $returnArray;
	        }
	    }
	    
	    $returnArray = array(null, null, "Error");
	    $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	    return $returnArray;
	}
	
	public function getTeilgewaesserbenutzungEntgelt($erhebungsjahr, $teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, &$zugelassenesEntnahmeEntgelt, &$nichtZugelassenesEntnahmeEntgelt, &$zugelassenerUmfang)
	{
	    $this->log->log_info('*** Gewaesserbenutzungen->getTeilgewaesserbenutzungEntgelt ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('getArtBenutzung: ' . var_export($getArtBenutzung, true));
	    $this->log->log_debug('getBefreiungstatbestaende: ' . var_export($getBefreiungstatbestaende, true));
	    $this->log->log_debug('getWiedereinleitungBearbeiter: ' . var_export($getWiedereinleitungBearbeiter, true));
	    $this->log->log_debug('zugelassenesEntnahmeEntgelt: ' . var_export($zugelassenesEntnahmeEntgelt, true));
	    $this->log->log_debug('nichtZugelassenesEntnahmeEntgelt: ' . var_export($nichtZugelassenesEntnahmeEntgelt, true));
	    $this->log->log_debug('zugelassenerUmfang: ' . var_export($zugelassenerUmfang, true));
	    
	    if(!empty($teilgewaesserbenutzung))
	    {
	        $teilbenutzungNichtZugelasseneMenge = $this->getTeilgewaesserbenutzungNichtZugelasseneMenge($erhebungsjahr, $teilgewaesserbenutzung->getId(), $zugelassenerUmfang);
	        $this->log->log_debug('teilbenutzungNichtZugelasseneMenge: ' . var_export($teilbenutzungNichtZugelasseneMenge, true));
	        
	        if($teilbenutzungNichtZugelasseneMenge > 0)
	        {
	            if($teilbenutzungNichtZugelasseneMenge === $teilgewaesserbenutzung->getUmfang())
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt = $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                $returnArray = array(null, $entnahmeEntgeltNichtErlaubt);
	                $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	                return $returnArray;
	            }
	            else
	            {
	                $entnahmeEntgeltNichtErlaubt = $teilgewaesserbenutzung->getEntgelt($teilbenutzungNichtZugelasseneMenge, $getArtBenutzung, $getBefreiungstatbestaende, false, $getWiedereinleitungBearbeiter);
	                $nichtZugelassenesEntnahmeEntgelt =  $nichtZugelassenesEntnahmeEntgelt + $entnahmeEntgeltNichtErlaubt;
	                
	                $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang() - $teilbenutzungNichtZugelasseneMenge, $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	                $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	                
	                $returnArray = array($entnahmeEntgeltErlaubt, $entnahmeEntgeltNichtErlaubt);
	                $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	                return $returnArray;
	            }
	        }
	        else
	        {
	            $entnahmeEntgeltErlaubt = $teilgewaesserbenutzung->getEntgelt($teilgewaesserbenutzung->getUmfang(), $getArtBenutzung, $getBefreiungstatbestaende, true, $getWiedereinleitungBearbeiter);
	            $zugelassenesEntnahmeEntgelt = $zugelassenesEntnahmeEntgelt + $entnahmeEntgeltErlaubt;
	            
	            $returnArray = array($entnahmeEntgeltErlaubt);
	            $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	            return $returnArray;
	        }
	    }
	    
	    $returnArray = array(null, null, "Error");
	    $this->log->log_debug('returnArray: ' . var_export($returnArray, true));
	    return $returnArray;
	}
	
	public function getEntnahmemenge($erhebungsjahr, $zugelassen)
	{
	    $gesamtUmfang = $this->getUmfangAllerTeilbenutzungen($erhebungsjahr);
	    
	    $zugelassenerUmfang = 0;
	    if(!empty($this->getErlaubterOderReduzierterUmfang()))
	    {
	        $zugelassenerUmfang = $this->getErlaubterOderReduzierterUmfang();
	    }
	    
	    if(!empty($gesamtUmfang) && !empty($zugelassenerUmfang))
	    {
	        if($zugelassen)
	        {
	            if($gesamtUmfang > $zugelassenerUmfang)
	            {
	                return $zugelassenerUmfang;
	            }
	            else
	            {
	                return $gesamtUmfang;
	            }
	        }
	        else
	        {
	            if($gesamtUmfang > $zugelassenerUmfang)
	            {
	                return $gesamtUmfang - $zugelassenerUmfang;
	            }
	            else
	            {
	                return 0;
	            }
	        }
	    }
	   
	    return null;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function insertAufforderung($dokumentId, $erhebungsjahr, $datum)
	{
	    $this->log->log_info('*** insertAufforderung ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('datum: ' . var_export($datum, true));
	    
	    $aufforderungen = $this->aufforderungen;
	    if(!empty($aufforderungen))
	    {
	        foreach ($aufforderungen as $aufforderung)
	        {
	            if(!empty($aufforderung))
	            {
	                if($aufforderung->compare($erhebungsjahr))
	                {
	                    $this->log->log_debug('aufforderung mit id: ' . $aufforderung->getId() . ' existiert schon: update');
	                    
	                    //if date is not set --> set it to today's date
	                    if(empty($datum))
	                    {
	                        $datum = $this->date->getToday();
	                    }
	                    
	                    $aufforderung_id = $aufforderung->updateAufforderung($erhebungsjahr, $dokumentId, $datum);
	                    Gewaesserbenutzungen::getAufforderungen($this->gui, $this);
	                    return $aufforderung_id;
	                }
	            }
	        }
	    }
	    
	    $this->log->log_debug('aufforderung wird neu angelegt');
	    
	    if(!empty($dokumentId))
	    {
	        //if date is not set --> set it to today's date
	        if(empty($datum))
	        {
	            $datum = $this->date->getToday();
	        }
	        
	        $aufforderung = new Aufforderung($this->gui);
	        $aufforderung_id = $aufforderung->createAufforderung($this->getId(), $erhebungsjahr, $dokumentId, $datum);
	        
	        Gewaesserbenutzungen::getAufforderungen($this->gui, $this);
	        
	        return $aufforderung_id;
	    }
	    
	    return null;
	}
	
	public function getAufforderungDatum($erhebungsjahr) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        return $aufforderung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getAufforderungDatumHTML($erhebungsjahr) {
	    $datumAufforderung = $this->getAufforderungDatum($erhebungsjahr);
	    if(!empty($datumAufforderung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumAufforderung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht aufgefordert</div>";
	}
	
	public function isAufforderungFreigegeben($erhebungsjahr) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        return $aufforderung->isFreigegeben();
	    }
	    
	    return false;
	}
	
	public function getAufforderungDokument($erhebungsjahr) {
	    $aufforderung = $this->getAufforderungForErhebungsjahr($erhebungsjahr);
	    if(!empty($aufforderung))
	    {
	        if(!empty($aufforderung->dokument))
	        {
	            return $aufforderung->dokument;
	        }
	    }
	    
	    return null;
	}
	
	public function getAufforderungForErhebungsjahr($erhebungsjahr)
	{
	    $this->log->log_info('*** getAufforderungForErhebungsjahr ***');
	    
	    if(!empty($erhebungsjahr))
	    {
	        $aufforderungen = $this->aufforderungen;
	        
	        if(!empty($aufforderungen))
	        {
	            foreach ($aufforderungen as $aufforderung)
	            {
	                if(!empty($aufforderung))
	                {
	                    if($aufforderung->getErhebungsjahr() === $erhebungsjahr)
	                    {
	                        return $aufforderung;
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function insertErklaerung($erhebungsjahr, $dateValue, $erklaerungNutzer) {
	    $this->log->log_info('*** insertErklaerung ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('erklaerungNutzer: ' . var_export($erklaerungNutzer, true));
	    $this->log->log_debug('dateValue: ' . var_export($dateValue, true));
	    
	    $erklaerungen = $this->erklaerungen;
	    if(!empty($erklaerungen))
	    {
	        foreach ($erklaerungen as $erklaerung)
	        {
	            if(!empty($erklaerung))
	            {
	                if($erklaerung->compare($erhebungsjahr))
	                {
	                    $this->log->log_info('erklaerung mit id: ' . $erklaerung->getId() . ' existiert schon: update');
	                    
	                    //if date is not set --> set it to today's date
	                    if(empty($dateValue))
	                    {
	                        $dateValue = $this->date->getToday();
	                    }
	                    
	                    $erklaerung_id = $erklaerung->updateErklaerung($erhebungsjahr, $dateVale, $erklaerungNutzer);
	                    Gewaesserbenutzungen::getErklaerungen($this->gui, $this);
	                    return $erklaerung_id;
	                }
	            }
	        }
	    }
	    
	    $this->log->log_info('erklaerung wird neu angelegt');
	    
	    //if date is not set --> set it to today's date
	    if(empty($dateValue))
	    {
	        $dateValue = $this->date->getToday();
	    }
	    
	    $erklaerung = new Erklaerung($this->gui);
	    $erklaerung_id = $erklaerung->createErklaerung($this->getId(), $erhebungsjahr, $dateValue, $erklaerungNutzer);
	    
	    Gewaesserbenutzungen::getErklaerungen($this->gui, $this);
	    
	    return $erklaerung_id;
	}
	
	public function isErklaerungFreigegeben($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        $datumErklaerung = $erklaerung->getDatum();
	        
	        if(!empty($datumErklaerung))
	        {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public function getErklaerungDatum($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        return $erklaerung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getErklaerungDatumHTML($erhebungsjahr) {
	    $datumErklaerung = $this->getErklaerungDatum($erhebungsjahr);
	    if(!empty($datumErklaerung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumErklaerung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht erklärt</div>";
	}
	
	public function getErklaerungNutzer($erhebungsjahr) {
	    $erklaerung = $this->getErklaerungForErhebungsjahr($erhebungsjahr);
	    if(!empty($erklaerung))
	    {
	        return $erklaerung->getNutzer();
	    }
	    
	    return null;
	}
	
	public function getErklaerungNutzerHTML($erhebungsjahr) {
	    $nutzerErklaerung = $this->getErklaerungNutzer($erhebungsjahr);
	    if(!empty($nutzerErklaerung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $nutzerErklaerung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht erklärt</div>";
	}
	
	public function getErklaerungForErhebungsjahr($erhebungsjahr)
	{
	    $this->log->log_info('*** getErklaerungForErhebungsjahr ***');
	    
	    if(!empty($erhebungsjahr))
	    {
	        $erklaerungen = $this->erklaerungen;
	        
	        if(!empty($erklaerungen))
	        {
	            foreach ($erklaerungen as $erklaerung)
	            {
	                if(!empty($erklaerung))
	                {
	                    if($erklaerung->getErhebungsjahr() === $erhebungsjahr)
	                    {
	                        return $erklaerung;
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function deleteFestsetzungDokument($erhebungsjahr) 
	{
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        $festsetzungId = $festsetzung->deleteFestsetzungDokument();
	        Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	        return $festsetzungId;
	    }
	}
	
	public function insertFestsetzungWithoutDokument($erhebungsjahr, $datum, $festsetzungNutzer,
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt) {
	    
        //if date is not set --> set it to today's date
        if(empty($datum))
        {
            $datum = $this->date->getToday();
        }
	    
	    return $this->insertFestsetzung($erhebungsjahr, null, $datum, null, $festsetzungNutzer, 
	        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen, 
	        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	}
	
	public function insertFestsetzungDokument($erhebungsjahr, $dokumentId, $dokumentDatum) {
	    
	    //if date is not set --> set it to today's date
	    if(empty($dokumentDatum))
	    {
	        $dokumentDatum = $this->date->getToday();
	    }
	     
	    return $this->insertFestsetzung($erhebungsjahr, $dokumentId, null, $dokumentDatum, null, 
	        null, null, null, 
	        null, null, null);
	}
	
	public function insertFestsetzung($erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer, 
	    $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	    $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt) {
	    $this->log->log_info('*** insertFestsetzung ***');
	    
	    $this->log->log_debug('erhebungsjahr: ' . var_export($erhebungsjahr, true));
	    $this->log->log_debug('dokumentId: ' . var_export($dokumentId, true));
	    $this->log->log_debug('datum: ' . var_export($datum, true));
	    $this->log->log_debug('dokumentDatum: ' . var_export($dokumentDatum, true));
	    $this->log->log_debug('festsetzungNutzer: ' . var_export($festsetzungNutzer, true));
	    
	    $this->log->log_debug('summeNichtZugelasseneEntnahmemengen: ' . var_export($summeNichtZugelasseneEntnahmemengen, true));
	    $this->log->log_debug('summeZugelasseneEntnahmemengen: ' . var_export($summeZugelasseneEntnahmemengen, true));
	    $this->log->log_debug('summeEntnahmemengen: ' . var_export($summeEntnahmemengen, true));
	    
	    $this->log->log_debug('summeNichtZugelassenesEntgelt: ' . var_export($summeNichtZugelassenesEntgelt, true));
	    $this->log->log_debug('summeZugelassenesEntgelt: ' . var_export($summeZugelassenesEntgelt, true));
	    $this->log->log_debug('summeEntgelt: ' . var_export($summeEntgelt, true));
	    
	    $festsetzungen = $this->festsetzungen;
	    if(!empty($festsetzungen))
	    {
	        foreach ($festsetzungen as $festsetzung)
	        {
	            if(!empty($festsetzung))
	            {
	                if($festsetzung->compare($erhebungsjahr))
	                {
	                    $this->log->log_info('Festsetzung mit id: ' . $festsetzung->getId() . ' existiert schon: update');
	                    
	                    $festsetzung_id = $festsetzung->updateFestsetzung($erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer,
	                        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	                        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	                    Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	                    return $festsetzung_id;
	                }
	            }
	        }
	    }
	    
	    $this->log->log_info('Festsetzung wird neu angelegt');
	    
	    $festsetzung = new Festsetzung($this->gui);
	    $festsetzung_id = $festsetzung->createFestsetzung($this->getId(), $erhebungsjahr, $dokumentId, $datum, $dokumentDatum, $festsetzungNutzer,
	        $summeNichtZugelasseneEntnahmemengen, $summeZugelasseneEntnahmemengen, $summeEntnahmemengen,
	        $summeNichtZugelassenesEntgelt, $summeZugelassenesEntgelt, $summeEntgelt);
	    
	    Gewaesserbenutzungen::getFestsetzungen($this->gui, $this);
	    
	    return $festsetzung_id;
	}
	
	public function isFestsetzungFreigegeben($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        $datumFestsetzung = $festsetzung->getDatum();
	        
	        if(!empty($datumFestsetzung))
	        {
	            return true;
	        }
	    }
	    
	    return false;
	}
	
	public function getFestsetzungDatum($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getDatum();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungDatumHTML($erhebungsjahr) {
	    $datumFestsetzung = $this->getFestsetzungDatum($erhebungsjahr);
	    if(!empty($datumFestsetzung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $datumFestsetzung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht festgesetzt</div>";
	}
	
	public function getFestsetzungNutzer($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getNutzer();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungNutzerHTML($erhebungsjahr) {
	    $nutzerFestsetzung = $this->getFestsetzungNutzer($erhebungsjahr);
	    if(!empty($nutzerFestsetzung))
	    {
	        // 	        $dateString = DateTime::createFromFormat("d.m.Y", $datumAbsend);
	        return "<div>" . $nutzerFestsetzung . "</div>";
	    }
	    
	    return "<div style=\"color: red;\">Nicht festgesetzt</div>";
	}
	
	public function getFestsetzungDokument($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        if(!empty($festsetzung->dokument))
	        {
	            return $festsetzung->dokument;
	        }
	    }
	    
	    return null;
	}
	
	public function isFestsetzungDokumentErstellt($erhebungsjahr)
	{
	    $festsetzungDokument = $this->getFestsetzungDokumentDatum($erhebungsjahr);
	    if(!empty($festsetzungDokument))
	    {
	        return true;
	    }
	    
	    return false;
	}
	
	public function getFestsetzungDokumentDatum($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->getDokumentDatum();
	    }
	    
	    return null;
	}
	
	public function getFestsetzungForErhebungsjahr($erhebungsjahr)
	{
	    $this->log->log_info('*** getFestsetzungForErhebungsjahr ***');
	    
	    if(!empty($erhebungsjahr))
	    {
	        $festsetzungen = $this->festsetzungen;
	        
	        if(!empty($festsetzungen))
	        {
	            foreach ($festsetzungen as $festsetzung)
	            {
	                if(!empty($festsetzung))
	                {
	                    if($festsetzung->getErhebungsjahr() === $erhebungsjahr)
	                    {
	                        return $festsetzung;
	                    }
	                }
	            }
	        }
	    }
	    
	    return null;
	}
	
	public function getFestsetzungSummeZugelasseneEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_zugelassene_entnahmemengen;
	    }
	}
	
	public function getFestsetzungSummeNichtZugelasseneEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_nicht_zugelassene_entnahmemengen;
	    }
	}
	
	public function getFestsetzungSummeEntnahmemengen($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_entnahmemengen;
	    }
	}
	
	
	public function getFestsetzungSummeZugelassenesEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_zugelassenes_entgelt;
	    }
	}
	
	public function getFestsetzungSummeNichtZugelassenesEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_nicht_zugelassenes_entgelt;
	    }
	}
	
	public function getFestsetzungSummeEntgelt($erhebungsjahr) {
	    $festsetzung = $this->getFestsetzungForErhebungsjahr($erhebungsjahr);
	    if(!empty($festsetzung))
	    {
	        return $festsetzung->summe_entgelt;
	    }
	}
	
	////////////////////////////////////////////////////////////////////
	
	public function getKennummer() {
	    return $this->getDataValue('kennnummer');
	}
	
	public function getWasserbuchnummer() {
	    return $this->getDataValue('wasserbuchnummer');
	}
	
	public function getBezeichnung() {
	    $fieldname = 'bezeichnung';
	    // 	    $sql = "SELECT COALESCE(e.name,'') ||' (Aktenzeichen: '|| COALESCE(a.aktenzeichen,'') ||')'||' vom '|| COALESCE(a.datum_postausgang::text,'') || ' zum ' || COALESCE(c.name,'') || ' von ' || COALESCE(d.max_ent_a::text,'') || ' m³/Jahr' AS " . $fieldname ." FROM " . $this->schema . '.' . "wasserrechtliche_zulassungen a LEFT JOIN " . $this->schema . '.' . $this->tableName . " b ON b.wasserrechtliche_zulassungen = a.id LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_art c ON c.id = b.art LEFT JOIN " . $this->schema . '.' . "gewaesserbenutzungen_umfang_entnahme d ON b.umfang = d.id LEFT JOIN " . $this->schema . '.' . "wasserrechtliche_zulassungen_ausgangsbescheide_klasse e ON a.klasse = e.id WHERE b.id = '" . $this->getId() . "';";
	    $sql = "SELECT " . $fieldname ." FROM " . $this->schema . '.' . $this->tableName . "_bezeichnung WHERE id = $1;";
	    // 	    echo "sql: " . $sql;
	    $bezeichnung = $this->getSQLResult($sql, array($this->getId()), $fieldname);
	    // 	    echo "bezeichnung: " . $bezeichnung;
	    if(!empty($bezeichnung) && count($bezeichnung) > 0 && !empty($bezeichnung[0]))
	    {
	        return $bezeichnung[0];
	    }
	    
	    return null;
	}
}
?>