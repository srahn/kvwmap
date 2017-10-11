<?php
class WRZProGueltigkeitsJahre
{
    public $gueltigkeitsJahre = array();
    public $wasserrechtlicheZulassung;
    
    public function toString() {
        return "gueltigkeitsJahre: " . var_export($this->gueltigkeitsJahre, true) . " wrz: " . $this->wasserrechtlicheZulassung->toString();
    }
}
?>