<?

$folder = getcwd();
if(defined('HTTP_PROXY'))putenv('https_proxy='.HTTP_PROXY);
exec('cd '.$folder.' && sudo -u '.GIT_USER.' git remote update', $test, $ret);
if($ret != 0)echo 'Fehler bei der Ausführung von "git remote update"';

?>