<?php
class WRZProGueltigkeitsJahre
{
    public $gueltigkeitsJahre;
    public $wasserrechtlicheZulassungen = array();
    
    public function toString() {
        return "gueltigkeitsJahre: " . var_export($this->gueltigkeitsJahre, true);
    }
}
?>