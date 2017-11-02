<?php
class WRZProGueltigkeitsJahreArray
{
    public $gueltigkeitsJahre = array();
    public $wrzProGueltigkeitsJahre = array();
    
    function __construct($gui) {
        $this->log = $gui->log;
    }
    
    public function getAllWrZs()
    {
        if(!empty($this->wrzProGueltigkeitsJahre) && count($this->wrzProGueltigkeitsJahre) > 0)
        {
            $wrzs = array();
            
            foreach ($this->wrzProGueltigkeitsJahre AS $wrzProGueltigkeitsJahr)
            {
                if(!empty($wrzProGueltigkeitsJahr))
                {
                    $wrz = $wrzProGueltigkeitsJahr->wasserrechtlicheZulassung;
                    if(!empty($wrz))
                    {
                        if(!in_array($wrz->getId(), $wrzs))
                        {
                            $wrzs[$wrz->getId()] = $wrz;
                        }
                    }
                }
            }
            
            if(count($wrzs) > 0)
            {
                return $wrzs;
            }
            else
            {
                return null;
            }
        }
        
        return null;
    }
    
    public function getFirstWrZ(&$wasserrechtlicheZulassungen = null)
    {
        $allWrZs = null;
        if(!empty($wasserrechtlicheZulassungen))
        {
            $allWrZs = $wasserrechtlicheZulassungen;
        }
        else
        {
            $allWrZs = $this->getAllWrZs();
        }
        
        if(!empty($allWrZs))
        {
            foreach ($allWrZs as $wrz)
            {
                if(!empty($wrz))
                {
                    return $wrz;
                }
            }
        }
        
        return null;
    }
    
    public function getAdressatInYearAndBehoerde(&$wasserrechtlicheZulassungen, $getYear, $getBehoerde, $getAdressat, $collectAdressaten = false)
    {
        $this->log->log_info('*** WRZProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde ***');
        $this->log->log_debug('wasserrechtlicheZulassungen count: ' . count($wasserrechtlicheZulassungen));
        $this->log->log_debug('getYear: ' . var_export($getYear, true));
        $this->log->log_debug('getBehoerde: ' . var_export($getBehoerde, true));
        $this->log->log_debug('getAdressat: ' . var_export($getAdressat, true));
        
        $allWrZs = null;
        if(!empty($wasserrechtlicheZulassungen))
        {
            $allWrZs = $wasserrechtlicheZulassungen;
        }
        else
        {
            $allWrZs = $this->getAllWrZs();
        }
        
        if(!empty($allWrZs))
        {
            $adressaten = array();
            
            foreach ($allWrZs as $wrz)
            {
                if(!empty($wrz) && !empty($wrz->adressat) && !empty($wrz->ausstellbehoerde))
                {
                    $this->log->log_trace('wrz id: ' . var_export($wrz->getId(), true));
                    
                    if(empty($getYear) || in_array($getYear, $wrz->gueltigkeitsJahre))
                    {
                        if(empty($getBehoerde) || $getBehoerde === $wrz->ausstellbehoerde->getId())
                        {
                            if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
                            {
                                if($wrz->adressat->isWrzAdressat())
                                {
                                    if(!$collectAdressaten)
                                    {
                                        return $wrz->adressat;
                                    }
                                    else
                                    {
                                        if(!in_array($wrz->adressat->getId(), $adressaten))
                                        {
                                            $adressaten[$wrz->adressat->getId()] = $wrz->adressat;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if(!empty($adressaten))
            {
                return $adressaten;
            }
        }
        
        return null;
    }
    
    public function getWrZForAdressatInYearAndBehoerde(&$wasserrechtlicheZulassungen, $getYear, $getBehoerde, $getAdressat)
    {
        $this->log->log_info('*** WRZProGueltigkeitsJahreArray->getWrZForAdressatInYearAndBehoerde ***');
        $this->log->log_debug('wasserrechtlicheZulassungen count: ' . count($wasserrechtlicheZulassungen));
        $this->log->log_debug('getYear: ' . var_export($getYear, true));
        $this->log->log_debug('getBehoerde: ' . var_export($getBehoerde, true));
        $this->log->log_debug('getAdressat: ' . var_export($getAdressat, true));
        
        $allWrZs = null;
        if(!empty($wasserrechtlicheZulassungen))
        {
            $allWrZs = $wasserrechtlicheZulassungen;
        }
        else
        {
            $allWrZs = $this->getAllWrZs();
        }
        
        if(!empty($allWrZs))
        {
            $returnWRZs = array();
            
            foreach ($allWrZs as $wrz)
            {
                if(!empty($wrz) && !empty($wrz->adressat) && !empty($wrz->ausstellbehoerde))
                {
                    $this->log->log_trace('wrz id: ' . var_export($wrz->getId(), true));
                    
                    if(empty($getYear) || in_array($getYear, $wrz->gueltigkeitsJahre))
                    {
                        if(empty($getBehoerde) || $getBehoerde === $wrz->ausstellbehoerde->getId())
                        {
                            if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
                            {
                                if($wrz->adressat->isWrzAdressat())
                                {
                                    $returnWRZs[] = $wrz;
                                }
                            }
                        }
                    }
                }
            }
            
            return $returnWRZs;
        }
        
        return null;
    }
    
//     public function toString() {
//         return "gueltigkeitsJahre: " . var_export($this->gueltigkeitsJahre, true);
//     }
}
?>