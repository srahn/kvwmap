<?php
class DateHelper
{
    function __construct($gui) {
        $this->log = $gui->log;
    }
    
    public function convertStringToDate($inputString) {
        if(!empty($inputString))
        {
            return DateTime::createFromFormat("d.m.Y", $inputString);
        }
        
        return null;
    }
    
    public function getYearFromDate($date) {
        if(!empty($date))
        {
            return $year = $date->format("Y");
        }
        
        return null;
    }
    
    public function getYearFromDateString($dateString) {
        if(!empty($dateString))
        {
            $date = $this->convertStringToDate($dateString);
            if(!empty($date))
            {
                return $this->getYearFromDate($date);
            }
        }
        
        return null;
    }
    
    public function getNextYear() {
        return date('Y', strtotime('+1 year'));
    }
    
    public function getThisYear() {
        return date("Y");
    }
    
    public function getToday() {
        return date("d.m.Y");
    }
    
    public function getLastYear() {
        return date("Y", strtotime("-1 year"));
    }
    
    public function addYearToArray($dateString, &$arrayToFill)
    {
        if(!empty($dateString))
        {
            $date = $this->convertStringToDate($dateString);
            $year = $this->getYearFromDate($date);
            if(!empty($year) && !in_array($year, $arrayToFill))
            {
                $arrayToFill[]=$year;
            }
        }
    }
    
    public function addAllYearsBetweenTwoDates($date1, $date2)
    {
        $years = array();
        
        if(!empty($date1) && !empty($date2))
        {
            // 	        print_r($date1->format("Y"));
            // 	        print_r($date2->format("Y"));
            $diff = $date1->diff($date2);
            // 	        print_r($diff->y);
            $diffY = $diff->y;
            if($diffY === 0)
            {
                $years[] = $date1->format("Y");
            }
            elseif($diffY > 0)
            {
                $diffY = $diffY + 1;
                $years[] =  $date1->format("Y");
                for ($i = 1; $i < $diffY; $i++)
                {
                    $interval = new DateInterval('P1Y');
                    $nextYear = $date1->add($interval)->format('Y');
                    $this->log->log_debug('nextYear: ' . var_export($nextYear, true));
                    $years[] = $nextYear;
                }
            }
        }
        elseif(!empty($date1))
        {
            $years[] = $date1->format("Y");
        }
        elseif(!empty($date2))
        {
            $years[] = $date2->format("Y");
        }
        
        return $years;
    }
}