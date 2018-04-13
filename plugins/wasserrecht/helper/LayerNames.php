<?php
class LayerNames
{
    //$mapdb = new db_mapObj($this->Stelle->id,$this->user->id);
    //$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
    //$this->log->log_debug(var_dump($layerdb));
    
    /*        $this->loadMap('DataBase');
     $layer_names = array();
     foreach($this->layerset['layer_ids'] AS $id => $layer) {
     $layer_names[$layer['Name']] = $id;
     }
     $this->layer_names = $layer_names;
     */
    // 	    $layer_name = 'Wasserrechtliche_Zulassungen';
    // 	    $this->layers = Layer::find($this, "Name = '" . $layer_name . "'");
    
    private $layers;
    private $layerNames;
    
    function __construct($gui, $layers) {
        $this->log = $gui->log;
        $this->log->log_debug('*** LayerNames Constructor ***');
        $this->setLayers($layers);
        $this->getLayerNamesFromLayers();
    }
    
    protected function getLayerNamesFromLayers() {
        $this->log->log_debug('*** LayerNames->getLayerNamesFromLayers ***');
        if (!empty($this->layers)) {
            
            //var_dump(count($this->layers));
            $layer_names = array();
            for ($i = 0; $i <= count($this->layers); $i++)
            {
               //echo $this->layers[$i]->get('Name');
               //$this->layers = $layers;
               //echo $this->layers[0]->get('Name');
               
              if(!empty($this->layers[$i]))
              {
                  $layer_name = $this->layers[$i]->get('Name');
                  $layer_id = $this->layers[$i]->get('Layer_ID');
                  $layer_names[$layer_name] = $layer_id;
              }
            }
            
            $this->log->log_debug('layer_names: ' . var_export($layer_names, true));
            $this->setLayerNames($layer_names);
            
        }
    }
    
    public function getLayerIdFromLayerName($layerName)
    {
        $this->log->log_debug('layerName: ' . var_export($layerName, true) . ' as input');
        $layerId = $this->getLayerNames()[$layerName];
        $this->log->log_debug('layerId: ' . var_export($layerId, true) . ' returned');
        return $layerId;
    }
    
    //////////////////////////////////////////////////////////
    
    /**
     * @return mixed
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * @param mixed $layers
     */
    public function setLayers($layers)
    {
        $this->layers = $layers;
    }

    /**
     * @return mixed
     */
    public function getLayerNames()
    {
        return $this->layerNames;
    }

    /**
     * @param mixed $layerNames
     */
    public function setLayerNames($layerNames)
    {
        $this->layerNames = $layerNames;
    }
}