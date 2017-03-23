<script type="text/javascript">
	if(typeof(stopwaiting) == "function"){
		stopwaiting();	// wenn man aus der Karte abgefragt hatte, Warteanimation beenden		
<? if($this->formvars['mime_type'] == 'overlay_html' AND $this->zoomed){ ?>		// wenn nicht aus normaler Suchmaske heraus gesucht wurde und (durch die Funktion generischeSuche_Suchen) auf die Treffer gezoomt wurde, Karte neu laden
		startwaiting();
		get_map_ajax('go=navMap_ajax', '', '');
<? } ?>
	}
	activate_overlay();
</script>

<table border="0" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" width="100%" valign="top" >
			<img src="<? echo GRAPHICSPATH.'leer.gif'; ?>" onload="currentform = document.GUI2;">
<?
$this->currentform = 'document.GUI2';
include (LAYOUTPATH.'snippets/'.$this->overlaymain);
?>
		</td>
	</tr>
</table>