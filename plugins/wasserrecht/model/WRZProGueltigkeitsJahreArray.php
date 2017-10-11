<?php
class WRZProGueltigkeitsJahreArray
{
    public $gueltigkeitsJahre = array();
    public $wrzProGueltigkeitsJahre = array();
    
    function __construct($gui) {
        $this->debug = $gui->debug;
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
    
    public function getAdressatInYearAndBehoerde(&$wasserrechtlicheZulassungen = null, $getYear = null, $getBehoerde = null, $getAdressat = null)
    {
        $this->debug->write('*** WRZProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde ***', 4);
        $this->debug->write('wasserrechtlicheZulassungen count: ' . count($wasserrechtlicheZulassungen), 4);
        $this->debug->write('getYear: ' . var_export($getYear, true), 4);
        $this->debug->write('getBehoerde: ' . var_export($getBehoerde, true), 4);
        $this->debug->write('getAdressat: ' . var_export($getAdressat, true), 4);
        
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
                if(!empty($wrz) && !empty($wrz->adressat) && !empty($wrz->behoerde))
                {
//                     $this->debug->write('1');
                    
                    if(empty($getYear) || in_array($getYear, $wrz->gueltigkeitsJahre))
                    {
//                         $this->debug->write('2');
                        
                        if(empty($getBehoerde) || $getBehoerde === $wrz->behoerde->getId())
                        {
//                             $this->debug->write('3');
                            
                            if(empty($getAdressat) || $getAdressat === $wrz->adressat->getId())
                            {
//                                 $this->debug->write('4');
                                
                                if($wrz->adressat->isWrzAdressat())
                                {
                                    $this->debug->write('5');
                                    return $wrz->adressat;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return null;
    }
    
//     public function toString() {
//         return "gueltigkeitsJahre: " . var_export($this->gueltigkeitsJahre, true);
//     }
}
?>