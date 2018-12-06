<?php

abstract class WrPgObject extends PgObject
{
    protected $schema = 'wasserrecht';
    protected $tableName = null;
    protected $write_debug = true;
    
    function WrPgObject(&$gui) {
        $this->log = $gui->log;
        $this->date = $gui->date;
        $this->isTrue = ["true",1,"t"];
        parent::__construct($gui, $this->schema, $this->tableName);
    }
    
    public function find_by_id($gui, $by, $id) {
        return $this->find_by_id_with_className(get_called_class(), $gui, $by, $id);
    }
    
    public function find_by_id_with_className($className, $gui, $by, $id) {
        $object = new $className($gui);
        $object->find_by($by, $id);
        return $object;
    }
    
    public function getName() {
        return $this->getDataValue('name');
    }
    
    public function getId() {
        return $this->getDataValue('id');
    }
    
    public function getAbkuerzung() {
        return $this->getDataValue('abkuerzung');
    }
    
    public function getBearbeiterName() {
        return $this->getDataValue('bearbeiter_name');
    }
    
    public function getBearbeiterId() {
        return $this->getDataValue('bearbeiter_id');
    }
    
    public function getBearbeitungsDatum() {
        return $this->getDataValue('bearbeitungs_datum');
    }
    
    public function getStelleName() {
        return $this->getDataValue('stelle_name');
    }
    
    public function getStelleId() {
        return $this->getDataValue('stelle_id');
    }
    
    public function getDataValue($dataFieldName)
    {
        if(!empty($this->data))
        {
            return $this->data[$dataFieldName];
        }
        else
        {
            return null;
        }
    }
    
    public function toString() {
        return "[" . get_class($this) . "]" .  " Id: " . $this->getId() . " Name: " . $this->getName();
    }
    
    public function getToStringFromArray(&$array)
    {
        $returnString = "";
        foreach ($array as $element)
        {
            if(empty($returnString))
            {
                $returnString = $element;
            }
            else
            {
                $returnString = $returnString . ", " . $element;
            }
        }
        
        return $returnString;
    }
    
    public function addToArray(&$array, $key, $value) {
        if(!empty($value))
        {
            $array[$key] = $value;
        }
    }
    
    public function updateData($key, $value) {
        if(!empty($value))
        {
            $this->set($key, $value);
        }
    }
    
    function getSQLResult($sql, $sqlreplacements, $fieldName) {
        
        $this->log->log_info('*** WrPgObject->getSQLResult ***');
        $this->log->log_debug('sql: ' . $sql);
        $this->log->log_debug('sqlreplacements: ' . var_export($sqlreplacements, true));
        $this->log->log_debug('fieldName: ' . var_export($fieldName, true));
        
        $query_name = "query_" . uniqid();
        $query = pg_prepare($this->database->dbConn, $query_name, $sql);
        $query = pg_execute($this->database->dbConn, $query_name, $sqlreplacements);
        $results = array();
        while ($rs = pg_fetch_assoc($query)) {
            if(!empty($rs))
            {
                //             var_dump($rs);
                $results[] = $rs[$fieldName];
            }
        }
        return $results;
    }
}