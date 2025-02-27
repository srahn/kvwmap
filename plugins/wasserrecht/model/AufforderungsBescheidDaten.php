<?php
class AufforderungsBescheidDaten
{
    private $parameter = null;
    private $wrzs = array();
    
    function __construct($gui) {
        $this->log = $gui->log;
    }

    /**
     * @return mixed
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
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
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function toString() {
        $this->log->log_debug('*** AufforderungsBescheidDaten ***');
        $this->log->log_debug('parameter: ' . var_export($this->parameter, true));
        if(!empty($this->wrzs))
        {
            foreach ($this->wrzs as $wrz)
            {
                if(!empty($wrz))
                {
                    $this->log->log_debug($wrz->toString());
                }
            }
        }
    }
}