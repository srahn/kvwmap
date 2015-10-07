<?

$folder = WWWROOT.APPLVERSION;
exec('cd '.$folder.' && git status -uno', $ausgabe, $ret);
if($ret != 0){
	echo 'Fehler bei der Ausführung von "git status"';
}
else{	
	$explosion = explode(' ', $ausgabe[0]);
	$branch = array_pop($explosion);
	echo '<table><tr><td>Branch:</td><td>'.$branch.'</td></tr>';
	echo '<tr><td>Status:</td><td>';
	if(strpos($ausgabe[1], 'Your branch is behind') !== false){
		$explosion = explode(' ', $ausgabe[1]);
		for($i = 0; $i < count($explosion); $i++){
			if($explosion[$i] == 'by')$num_commits_behind = $explosion[$i+1];
		}
		if($num_commits_behind != ''){
			echo 'Anzahl neuer commits auf <a target="_blank" href="https://github.com/srahn/kvwmap/commits/'.$branch.'">github</a>: '.$num_commits_behind;
		}
	}
	else echo 'Quellcode aktuell';
	echo '</td></tr></table>';
}

?>