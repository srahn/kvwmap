<h2>Fortführungsfälle</h2><?php
include(LAYOUTPATH."snippets/Fehlermeldung.php");
echo '<table>';
	foreach($this->loader->fortfuehrungsfaelle AS $ff) {
		echo '<tr><td colspan="2">Fortführungsfall: ' . $ff->get('fortfuehrungsfallnummer') . '</td><tr>';
		echo '<tr><td>ff_auftrag_id</td><td>' . $ff->get('ff_auftrag_id') . '</td></tr>';
		echo '<tr><td>laufendeNummer</td><td>' . $ff->get('laufendenummer') . '</td></tr>';
		echo '<tr><td>ueberschriftImFortfuehrungsnachweis</td><td>' . $ff->get('ueberschriftimfortfuehrungsnachweis') . '</td></tr>';
		echo '<tr><td>zeigtaufAltesFlurst</td><td>' . implode(', ', $ff->get('zeigtaufaltesflurstueck')) . '</td></tr>';
		echo '<tr><td>zeigtaufneuesflurst</td><td>' . implode(', ', $ff->get('zeigtaufneuesflurstueck')) . '</td></tr>';
	}
	echo '</table>'
?>