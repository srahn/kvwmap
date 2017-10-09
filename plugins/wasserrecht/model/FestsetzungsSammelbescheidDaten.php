<?php
class FestsetzungsSammelbescheidDaten
{
    private $wrzs = array();
    private $anlagen = array();
    private $entnahmemengen = array();
    private $entgelte = array();
    private $zugelassene_entgelte = array();
    private $nicht_zugelassene_entgelte = array();
    private $erlaubterUmfang = null;
    private $freitext = null;
    
    function __construct($gui) {
        $this->debug = $gui->debug;
    }
    
    /**
     * @return mixed
     */
    public function getWrzs()
    {
        return $this->wrzs;
    }

    /**
     * @return multitype:
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * @return multitype:
     */
    public function getEntnahmemengen()
    {
        return $this->entnahmemengen;
    }

    /**
     * @return multitype:
     */
    public function getEntgelte()
    {
        return $this->entgelte;
    }

    /**
     * @return mixed
     */
    public function getFreitext()
    {
        return $this->freitext;
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
     * @param multitype: $anlagen
     */
    public function setAnlagen($anlagen)
    {
        $this->anlagen = $anlagen;
    }
    
    public function addAnlage($anlage)
    {
        if(!empty($anlage))
        {
            $this->debug->write('Anlage ID: ' . $anlage->getId(), 4);
            $this->debug->write('Anlage Name: ' . $anlage->getName(), 4);
            
            $this->anlagen[] = $anlage;
        }
        else
        {
            $this->debug->write('Anlage is Null!', 1);
        }
    }

    /**
     * @param multitype: $entnahmemengen
     */
    public function setEntnahmemengen($entnahmemengen)
    {
        $this->entnahmemengen = $entnahmemengen;
    }
    
    public function addEntnahmemenge($entnahmemenge)
    {
        if(!empty($entnahmemenge))
        {
            $this->entnahmemengen[] = $entnahmemenge;
        }
        else
        {
            $this->debug->write('Entnahmemenge is Null!', 1);
        }
    }

    /**
     * @param multitype: $entgelte
     */
    public function setEntgelte($entgelte)
    {
        $this->entgelte = $entgelte;
    }
    
    public function addEntgelt($entgelt)
    {
        if(!empty($entgelt))
        {
            $this->entgelte[] = $entgelt;
        }
        else
        {
            $this->debug->write('Entgelt is Null!', 1);
        }
    }

    /**
     * @param mixed $freitext
     */
    public function setFreitext($freitext)
    {
        $this->freitext = $freitext;
    }
    
    /**
     * @return multitype:
     */
    public function getZugelassene_entgelte()
    {
        return $this->zugelassene_entgelte;
    }

    /**
     * @return multitype:
     */
    public function getNicht_zugelassene_entgelte()
    {
        return $this->nicht_zugelassene_entgelte;
    }

    /**
     * @param multitype: $zugelassene_entgelte
     */
    public function setZugelassene_entgelte($zugelassene_entgelte)
    {
        $this->zugelassene_entgelte = $zugelassene_entgelte;
    }
    
    public function addZugelassenes_entgelt($zugelassenes_entgelt)
    {
        if(!empty($zugelassenes_entgelt))
        {
            $this->zugelassene_entgelte[] = $zugelassenes_entgelt;
        }
        else
        {
            $this->debug->write('Zugelassenes Entgelt is Null!', 1);
        }
    }

    /**
     * @param multitype: $nicht_zugelassene_entgelte
     */
    public function setNicht_zugelassene_entgelte($nicht_zugelassene_entgelte)
    {
        $this->nicht_zugelassene_entgelte = $nicht_zugelassene_entgelte;
    }
    
    public function addNicht_zugelassenes_entgelt($nicht_zugelassenes_entgelt)
    {
        if(!empty($nicht_zugelassenes_entgelt))
        {
            $this->nicht_zugelassene_entgelte[] = $nicht_zugelassenes_entgelt;
        }
        else
        {
            $this->debug->write('Nicht Zugelassenes Entgelt is Null!', 1);
        }
    }

    /**
     * @return mixed
     */
    public function getErlaubterUmfang()
    {
        return $this->erlaubterUmfang;
    }

    /**
     * @param mixed $erlaubterUmfang
     */
    public function setErlaubterUmfang($erlaubterUmfang)
    {
        $this->erlaubterUmfang = $erlaubterUmfang;
    }

    public function toString() {
        $this->debug->write('*** FestsetzungsSammelbescheidDaten ***', 4);
        $this->debug->write('count anlagen: ' . count($this->getAnlagen()), 4);
        $this->debug->write('entnahmemengen: ' . var_export($this->getEntnahmemengen(), true), 4);
        $this->debug->write('entgelte: ' . var_export($this->getEntgelte(), true), 4);
        $this->debug->write('zugelassene entgelte: ' . var_export($this->getZugelassene_entgelte(), true), 4);
        $this->debug->write('nicht zugelassene entgelte: ' . var_export($this->getNicht_zugelassene_entgelte(), true), 4);
        $this->debug->write('freitext: ' . $this->getFreitext(), 4);
        $this->debug->write('erlaubterUmfang: ' . $this->getErlaubterUmfang(), 4);
    }
    
    public function isValid() 
    {
        if(!empty($this->getWrzs()) && !empty($this->getWrzs()[0]))
        {
            if(!empty($this->getAnlagen()) && !empty($this->getEntnahmemengen()) && !empty($this->getEntgelte()))
            {
                $countAnlagen = count($this->getAnlagen());
                $this->debug->write('countAnlagen: ' . $countAnlagen, 4);
                $countEntnahmemengen = count($this->getEntnahmemengen());
                $this->debug->write('countEntnahmemengen: ' . $countEntnahmemengen, 4);
                $countEntgelte = count($this->getEntgelte());
                $this->debug->write('countEntgelte: ' . $countEntgelte, 4);
                
//                 $countZugelasseneEntgelte = count($this->getZugelassene_entgelte());
//                 $this->debug->write('countZugelasseneEntgelte: ' . $countZugelasseneEntgelte, 4);
//                 $countNichtZugelasseneEntgelte = count($this->getNicht_zugelassene_entgelte());
//                 $this->debug->write('countNichtZugelasseneEntgelte: ' . $countNichtZugelasseneEntgelte, 4);
                
                if($countAnlagen === $countEntnahmemengen && $countAnlagen === $countEntgelte && $countEntnahmemengen === $countEntgelte)
//                     && $countEntgelte === $countZugelasseneEntgelte && $countEntgelte === $countNichtZugelasseneEntgelte)
                {
                    return true;
                }
                else
                {
                    $this->debug->write('Counts are not equal', 1);
                }
            }
            else
            {
                $this->debug->write('Arrays are empty', 1);
            }
        }
        else
        {
            $this->debug->write('WrZs are null', 1);
        }
        
        return false;
    }
    
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
        $this->debug->write('entgelte: ' . var_export($entgelte, true), 4);
        
        if(!empty($entgelte) && count($entgelte) > 0)
        {
            $summe_entgelte = 0;
            
            foreach ($entgelte as $entgelt)
            {
                $this->debug->write('summe_entgelte: ' . $summe_entgelte . ' + entgelt: ' . $entgelt, 4);
                $summe_entgelte = $summe_entgelte + $entgelt;
            }
            
            $this->debug->write('summe_entgelte return: ' . $summe_entgelte, 4);
            return $summe_entgelte;
        }
        
        return null;
    }

}