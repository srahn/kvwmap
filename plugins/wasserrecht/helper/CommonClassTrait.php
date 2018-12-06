<?php
trait CommonClassTrait 
{
    public function getArray($fieldName, $functionName, $parameter)
    {
        //         $this->log->log_trace('fieldName: ' . $fieldName);
        //         $this->log->log_trace('functionName: ' . $functionName);
        //         $this->log->log_trace('parameter: ' . $parameter);
        
        $fields = $this->$fieldName();
        
        if(!empty($fields))
        {
            $returnArray = array();
            
            foreach ($fields as $field)
            {
                if(!empty($field))
                {
                    $result = $field->$functionName($parameter);
                    //                     if(is_numeric($result))
                        //                     {
                        //                         $this->log->log_trace('result: ' . $result);
                        //                     }
                            $returnArray[] = $result;
                }
            }
            
            return $returnArray;
        }
        
        return null;
    }
    
    //     public function getFirstEntry($fieldName, $functionName, $parameter = NULL)
    //     {
    //         $fields = $this->$fieldName;
        
    //         if(!empty($fields))
        //         {
        //             foreach ($fields as $field)
            //             {
            //                 if(!empty($field))
                //                 {
                //                     $result = $field->$functionName($parameter);
                //                     if(!empty($result))
                    //                     {
                //                         return $result;
                //                     }
                //                 }
                //             }
                //         }
                //         return null;
                //     }
}