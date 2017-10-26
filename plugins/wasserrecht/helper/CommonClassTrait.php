<?php
trait CommonClassTrait 
{
    public function getArray($fieldName, $functionName, $parameter)
    {
        //         $this->debug->write('fieldName: ' . $fieldName, 4);
        //         $this->debug->write('functionName: ' . $functionName, 4);
        //         $this->debug->write('parameter: ' . $parameter, 4);
        
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
                        //                         $this->debug->write('result: ' . $result, 4);
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