<?

$folder = WWWROOT.APPLVERSION;
exec('cd '.$folder.' && git status -uno', $ausgabe);
#$ausgabe[1] = '# Your branch is behind \'origin/develop\' by 1 commit, and can be fast-forwarded.';

echo '<table>
				<tr>
					<td>';
if(strpos($ausgabe[1], '# Your branch is behind') === 0){
	$explosion = explode(' ', $ausgabe[1]);
	for($i = 0; $i < count($explosion); $i++){
		if($explosion[$i] == 'by')$num_commits_behind = $explosion[$i+1];
	}
	if($num_commits_behind != ''){
		echo 'Anzahl neuer commits auf github: '.$num_commits_behind;
	}
}
elseif(substr($ausgabe[1], 0, 1) == '#'){
	echo 'Quellcode aktuell';
}
else echo 'Fehler bei der Ausführung von "git status"';

echo '</td>
		</tr>
	</table>';

?>