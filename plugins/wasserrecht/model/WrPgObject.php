<?php

abstract class WrPgObject extends PgObject
{
    protected $schema = 'wasserrecht';
    protected $tableName = null;
    protected $write_debug = true;
    
    function WrPgObject($gui) {
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
        if(!empty($this->data))
        {
            return $this->data['name'];
        }
        else
        {
            return null;
        }
    }
    
    public function getId() {
        if(!empty($this->data))
        {
            return $this->data['id'];
        }
        else
        {
            return null;
        }
    }
    
    public function toString() {
        return "id: " . $this->getId() . " name: " . $this->getName();
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
    
    function getSQLResult($sql, $fieldName) {
        $query = pg_query($this->database->dbConn, $sql);
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