<table border="0" height="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" width="100%" valign="top" >
			<img src="<? echo GRAPHICSPATH.'/leer.gif'; ?>" onload="currentform = document.GUI2;">
<?
$this->currentform = 'document.GUI2';
include (LAYOUTPATH.'snippets/'.$this->overlaymain);
?>
		</td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
	stopwaiting();	// wenn man aus der Karte abgefragt hatte, Warteanimation beenden
	activate_overlay();
</script>