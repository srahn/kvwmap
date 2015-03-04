<?

$folder = getcwd();
exec('cd '.$folder.' && sudo -u '.GIT_USER.' git remote update', $test, $ret);
if($ret != 0)echo 'Fehler bei der Ausführung von "git remote update"';

?>