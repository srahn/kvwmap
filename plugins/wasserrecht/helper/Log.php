<?php
class Log
{
    private $prefix = "(wasserrecht)";
    
    function __construct($debug) {
        $this->debug = $debug;
    }
    
    function log($log_message, $log_level_name, $log_level)
    {
        if($log_level >= DEBUG_LEVEL) 
        {
            $this->debug->write(date("d-m-Y H:i:s") . " " . $this->prefix . "[". $log_level_name ."] " .  $log_message, $log_level);
        }
    }
    
    function log_trace($log_message)
    {
//         $this->debug->write($this->prefix . "DEBUG_LEVEL: " . DEBUG_LEVEL, 5);
        $this->log($log_message, "trace", 1);
//         $this->debug->write($this->prefix . "[trace] " .  $log_message, 1);
    }
    
    function log_debug($log_message)
    {
        $this->log($log_message, "debug", 4);
//         $this->debug->write($this->prefix . "[debug] " . $log_message, 4);
    }
    
    function log_info($log_message)
    {
        $this->log($log_message, "info", 5);
//         $this->debug->write($this->prefix . "[info] " . $log_message, 5);
    }
    
    function log_success($log_message)
    {
        $this->log($log_message, "success", 5);
//         $this->debug->write("<p><font face='verdana' color='green'>" . $log_message . "</font></p>", 5);
//         $this->debug->write($this->prefix . "[success] " . $log_message, 5);
    }
    
    function log_error($log_message)
    {
        $this->log($log_message, "error", 6);
//         $this->debug->write("<p><font face='verdana' color='red'>" . $log_message . "</font></p>", 6);
//         $this->debug->write($this->prefix . "[error] " . $log_message, 6);
    }
    
    function log_failure($log_message)
    {
        $this->log($log_message, "failure", 7);
//         $this->debug->write("<p><font face='verdana' color='red'>" . $log_message . "</font></p>", 7);
//         $this->debug->write($this->prefix . "[failure] " . $log_message, 7);
    }
}