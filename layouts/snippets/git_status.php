<?

$folder = WWWROOT.APPLVERSION;
exec('cd '.$folder.' && git status -uno', $ausgabe, $ret);
if($ret != 0){
	echo 'Fehler bei der Ausführung von "git status"';
}
else{
	if(strpos($ausgabe[1], '# Your branch is behind') === 0){
		$explosion = explode(' ', $ausgabe[1]);
		for($i = 0; $i < count($explosion); $i++){
			if($explosion[$i] == 'by')$num_commits_behind = $explosion[$i+1];
		}
		if($num_commits_behind != ''){
			echo 'Anzahl neuer commits auf github: '.$num_commits_behind;
		}
	}
	else echo 'Quellcode aktuell';
}

?>