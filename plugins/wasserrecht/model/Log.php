<?php
class Log
{
    private $prefix = "(wasserrecht) ";
    
    function __construct($debug) {
        $this->debug = $debug;
    }
    
    function log_trace($log_message)
    {
        $this->debug->write($this->prefix . $log_message, 1);
    }
    
    function log_debug($log_message)
    {
        $this->debug->write($this->prefix . $log_message, 4);
    }
    
    function log_info($log_message)
    {
        $this->debug->write($this->prefix . $log_message, 5);
    }
    
    function log_success($log_message)
    {
//         $this->debug->write("<p><font face='verdana' color='green'>" . $log_message . "</font></p>", 5);
        $this->debug->write($this->prefix . $log_message, 5);
    }
    
    function log_error($log_message)
    {
//         $this->debug->write("<p><font face='verdana' color='red'>" . $log_message . "</font></p>", 6);
        $this->debug->write($this->prefix . $log_message, 6);
    }
    
    function log_failure($log_message)
    {
//         $this->debug->write("<p><font face='verdana' color='red'>" . $log_message . "</font></p>", 7);
        $this->debug->write($this->prefix . $log_message, 7);
    }
}