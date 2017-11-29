<?php
class FestsetzungsSammelbescheidDaten
{
    use CommonClassTrait;
    
    private $erhebungsjahr = null;
    
    private $wrzs = array();
    private $gewaesserbenutzungen = array();
    
    private $freitext = null;
    
    function __construct($gui) {
        $this->log = $gui->log;
    }
    
    /**
     * @return mixed
     */
    public function getErhebungsjahr()
    {
        return $this->erhebungsjahr;
    }

    /**
     * @param mixed $erhebungsjahr
     */
    public function setErhebungsjahr($erhebungsjahr)
    {
        $this->erhebungsjahr = $erhebungsjahr;
    }

    /**
     * @return mixed
     */
    public function getWrzs()
    {
        return $this->wrzs;
    }
    
    /**
     * @param mixed $wrz
     */
    public function setWrzs($wrz)
    {
        $this->wrzs = $wrz;
    }
    
    public function addWrz($wrz)
    {
        $this->wrzs[] = $wrz;
    }

    /**
     * @return multitype:
     */
    public function getGewaesserbenutzungen()
    {
        return $this->gewaesserbenutzungen;
    }
    
//     public function getGewaesserbenutzungenById($gewaesserbenutzungsId)
//     {
//         if(!empty($gewaesserbenutzungsId))
//         {
//             $gewaesserbenutzungen = $this->gewaesserbenutzungen;
            
//             if(!empty($gewaesserbenutzungen))
//             {
//                 foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
//                 {
//                     if(!empty($gewaesserbenutzung) && $gewaesserbenutzung->getId() === $gewaesserbenutzungsId)
//                     {
//                         return $gewaesserbenutzung;
//                     }
//                 }
//             }
//         }
        
//         return null;
//     }

    /**
     * @param multitype: $gewaesserbenutzungen
     */
    public function setGewaesserbenutzungen($gewaesserbenutzungen)
    {
        $this->gewaesserbenutzungen = $gewaesserbenutzungen;
    }
    
    public function addGewaesserbenutzungen($gewaesserbenutzung)
    {
        $this->gewaesserbenutzungen[] = $gewaesserbenutzung;
    }
    
//     public function getTeilGewaesserbenutzungenByGewaesserbenutzungsId($gewaesserbenutzungsId)
//     {
//         $gewaesserbentzung = $this->getGewaesserbenutzungenById($gewaesserbenutzungsId);
        
//         if(!empty($gewaesserbentzung))
//         {
//             $teilgewaesserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($this->erhebungsjahr);
            
//             if(!empty($teilgewaesserbenutzungen) && count($teilgewaesserbenutzungen) > 0)
//             {
//                 $teilgewaesserbenutzungenReturnArray = array();
                
//                 foreach ($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
//                 {
//                     if(!empty($teilgewaesserbenutzung))
//                     {
//                         $teilgewaesserbenutzungenReturnArray[] = $teilgewaesserbenutzung;
//                     }
//                 }
                
//                 return $teilgewaesserbenutzungenReturnArray;
//             }
//         }
        
//         return null;
//     }
    
    public function getAllTeilGewaesserbenutzungen()
    {
        $gewaesserbenutzungen = $this->getGewaesserbenutzungen();
        
        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0)
        {
            $teilgewaesserbenutzungenReturnArray = array();
            
            foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
            {
                if(!empty($gewaesserbenutzung))
                {
                    $teilgewaesserbenutzungen = $gewaesserbenutzung->getTeilgewaesserbenutzungenByErhebungsjahr($this->erhebungsjahr);
                    
                    if(!empty($teilgewaesserbenutzungen) && count($teilgewaesserbenutzungen) > 0)
                    {
                        foreach ($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
                        {
                            if(!empty($teilgewaesserbenutzung))
                            {
                                $teilgewaesserbenutzungenReturnArray[] = $teilgewaesserbenutzung;
                            }
                        }
                    }
                }
            }
            
            return $teilgewaesserbenutzungenReturnArray;
        }
        
        return null;
    }

    /**
     * @return multitype:
     */
    public function getAnlagen()
    {
        return $this->getWrZArray("getAnlagen");
    }

    /**
     * @return multitype:
     */
    public function getEntnahmemengen()
    {
        return $this->getGewaesserbenutzungsArrayWithErhebungsjahr("getFestsetzungSummeEntnahmemengen");
    }

    /**
     * @return multitype:
     */
    public function getEntgelte()
    {
        return $this->getGewaesserbenutzungsArrayWithErhebungsjahr("getFestsetzungSummeEntgelt");
    }

    /**
     * @return mixed
     */
    public function getFreitext()
    {
        return $this->freitext;
    }

    /**
     * @param mixed $freitext
     */
    public function setFreitext($freitext)
    {
        $this->freitext = $freitext;
    }
    
    public function setFreitextFromAllEntries()
    {
        $this->log->log_info('*** FestsetzungsSammelbescheidDaten->setFreitextFromAllEntries ***');
        
        $teilgewaesserbenutzungen = $this->getAllTeilGewaesserbenutzungen();
        if(!empty($teilgewaesserbenutzungen) && count($teilgewaesserbenutzungen) > 0)
        {
            $this->log->log_debug('teilgewaesserbenutzungen: ' . count($teilgewaesserbenutzungen));
            
            foreach ($teilgewaesserbenutzungen as $teilgewaesserbenutzung)
            {
                if(!empty($teilgewaesserbenutzung))
                {
                    $freitext = $teilgewaesserbenutzung->getFreitext();
                    if(!empty($freitext))
                    {
                        $this->setFreitext($freitext);
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * @return multitype:
     */
    public function getErklaerung_datum()
    {
        return $this->getGewaesserbenutzungsArrayWithErhebungsjahr("getErklaerungDatum");
    }

    public function getErklaerung_datum_String()
    {
        $daten = $this->getErklaerung_datum();
        if(!empty($daten))
        {
            $returnString = null;
            
            foreach ($daten as $date)
            {
                if(!empty($date))
                {
                    if(!empty($returnString))
                    {
                        $returnString = $returnString . " " . $date;
                    }
                    else
                    {
                        $returnString = $date;
                    }
                }
            }
            
            return $returnString;
        }
        
        return null;
    }

    /**
     * @return multitype:
     */
    public function getZugelassene_entgelte()
    {
        return $this->getGewaesserbenutzungsArrayWithErhebungsjahr("getFestsetzungSummeZugelassenesEntgelt");
    }

    /**
     * @return multitype:
     */
    public function getNicht_zugelassene_entgelte()
    {
        return $this->getGewaesserbenutzungsArrayWithErhebungsjahr("getFestsetzungSummeNichtZugelassenesEntgelt");
    }

    /**
     * @return mixed
     */
    public function getErlaubterUmfang()
    {
        $gewaesserbenutzungen = $this->gewaesserbenutzungen;
        
        if(!empty($gewaesserbenutzungen))
        {
            $returnArray = array();
            
            foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
            {
                if(!empty($gewaesserbenutzung))
                {
                    $erlaubterUmfang = $gewaesserbenutzung->getErlaubterOderReduzierterUmfang();
                    $returnArray[] = $erlaubterUmfang;
                }
            }
            
            return $returnArray;
        }
        
        return null;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function toString() {
        $this->log->log_info('*** FestsetzungsSammelbescheidDaten ***');
        $this->log->log_debug('count anlagen: ' . count($this->getAnlagen()));
        $this->log->log_debug('entnahmemengen: ' . var_export($this->getEntnahmemengen(), true));
        $this->log->log_debug('entgelte: ' . var_export($this->getEntgelte(), true));
        $this->log->log_debug('zugelassene entgelte: ' . var_export($this->getZugelassene_entgelte(), true));
        $this->log->log_debug('nicht zugelassene entgelte: ' . var_export($this->getNicht_zugelassene_entgelte(), true));
        $this->log->log_debug('erlaubterUmfang: ' . var_export($this->getErlaubterUmfang(), true));
        $this->log->log_debug('freitext: ' . $this->getFreitext());
        $this->log->log_debug('Erklärung Datum String: ' . $this->getErklaerung_datum_String());
    }
    
    public function isValid() 
    {
        if(!empty($this->getWrzs()) && !empty($this->getWrzs()[0]))
        {
            if(!empty($this->getAnlagen()) && !empty($this->getEntnahmemengen()) && !empty($this->getEntgelte()))
            {
                $countAnlagen = count($this->getAnlagen());
                $this->log->log_debug('countAnlagen: ' . $countAnlagen);
                $countEntnahmemengen = count($this->getEntnahmemengen());
                $this->log->log_debug('countEntnahmemengen: ' . $countEntnahmemengen);
                $countEntgelte = count($this->getEntgelte());
                $this->log->log_debug('countEntgelte: ' . $countEntgelte);
                
//                 $countZugelasseneEntgelte = count($this->getZugelassene_entgelte());
//                 $this->log->log_debug('countZugelasseneEntgelte: ' . $countZugelasseneEntgelte, 4);
//                 $countNichtZugelasseneEntgelte = count($this->getNicht_zugelassene_entgelte());
//                 $this->log->log_debug('countNichtZugelasseneEntgelte: ' . $countNichtZugelasseneEntgelte, 4);
                
                if($countAnlagen === $countEntnahmemengen && $countAnlagen === $countEntgelte && $countEntnahmemengen === $countEntgelte)
//                     && $countEntgelte === $countZugelasseneEntgelte && $countEntgelte === $countNichtZugelasseneEntgelte)
                {
                    return true;
                }
                else
                {
                    $this->log->log_error('Counts are not equal');
                }
            }
            else
            {
                $this->log->log_error('Arrays are empty');
            }
        }
        else
        {
            $this->log->log_error('WrZs are null');
        }
        
        return false;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function getSummeEntgelte()
    {
        $entgelte = $this->getEntgelte();
        return $this->getSumme($entgelte);
    }
    
    public function getSummeNichtZugelasseneEntgelte()
    {
        $nicht_zugelassene_entgelte = $this->getNicht_zugelassene_entgelte();
        return $this->getSumme($nicht_zugelassene_entgelte);
    }
    
    public function getSummeZugelasseneEntgelte()
    {
        $zugelassene_entgelte = $this->getZugelassene_entgelte();
        return $this->getSumme($zugelassene_entgelte);
    }
    
    public function getSumme(&$entgelte)
    {
        $this->log->log_debug('entgelte: ' . var_export($entgelte, true));
        
        if(!empty($entgelte) && count($entgelte) > 0)
        {
            $summe_entgelte = 0;
            
            foreach ($entgelte as $entgelt)
            {
                $this->log->log_debug('summe_entgelte: ' . $summe_entgelte . ' + entgelt: ' . $entgelt);
                $summe_entgelte = $summe_entgelte + $entgelt;
            }
            
            $this->log->log_debug('summe_entgelte return: ' . $summe_entgelte);
            return $summe_entgelte;
        }
        
        return null;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public static function formatNumber(&$number)
    {
        return number_format($number, 0, '', ' ');
    }
    
    public static function formatCurrencyNumber(&$number)
    {
        return number_format($number, 2, ',', ' ');
    }
    
//     public function getGewaesserbenutzungFirstEntry($functionName, $erhebungsjahr = NULL)
//     {
//         return $this->getFirstEntry('getGewaesserbenutzungen', $functionName, $erhebungsjahr);
//     }
    
    public function getGewaesserbenutzungsArrayWithErhebungsjahr($functionName)
    {
        return $this->getArray('getGewaesserbenutzungen', $functionName, $this->erhebungsjahr);
    }
    
    public function getWrZArray($functionName)
    {
        return $this->getArray('getWrzs', $functionName, null);
    }
}