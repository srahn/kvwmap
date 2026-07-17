<style>
  #log_file_div {
    margin: 10px 10px 10px 10px;
    padding: 5px;
    text-align: left;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
  }
</style>
<h2 style="margin: 10px"><?php echo $this->titel; ?></h2>
<div id="log_file_div">
  <br><? echo $this->msg; ?>.
	<br>Beim nächsten Synchronisationsversuch mit dem Aufruf mobile_sync_all werden die Layer für die App des Nutzers neu geladen.<br><br>
	Achtung! Die auf der App evtl. noch vorhandenen Deltas werden nur in die Datenbank eingespielt, wenn es keine Fehler dabei gibt. In jedem Fall werde sie aber in das Logfile übernommen.<br>
	Nach dem sync_reset wird sync_reset_proceeded eingetragen. Wenn noch etwas zwischen sync_reset_needed und sync_reset_proceeded steht was Fehler hat, muss das nachträglich manuell mit der Datenbank abgeglichen werden!<p>
	<a href="index.php?go=mobile_show_log&log_name=<? echo $this->log_name; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">zurück zur Anzeige der Log-Datei.</a>
</div>