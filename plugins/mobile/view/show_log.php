<style>
#log_div, #sql_div {
  margin: 10px 10px 10px 10px;
  padding: 5px;
  text-align: left;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  max-width: 1200px;
}
.client_deltas {
  margin: 10px 0 10px 0;
  padding: 5px;
  text-align: left;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  max-width: 1200px;
}

h3 {
  margin-bottom: 20px;
  float: left;
}
</style>
<h2 style="margin: 10px"><?php echo $this->titel; ?></h2>
<a href="index.php?go=mobile_list_logs&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Zurück zur Liste der Logfiles.</a><br><?
if ($this->filesize_exceeded) { ?>
  Die Größe der Log-Datei Datei <?php echo human_filesize($this->mobile_log_path); ?> übersteigt die maximal zulässige Größe von 100 MB zum Anzeigen der Daten im Browser.<br>Bitte die Datei runterladen und lokal anzeigen.<p><? 
}
else { ?>
  <input type="button" value="Toggle vollständiges Log" onclick="$('#log_div').toggle()" style="margin: 10px;">
  <div id="log_div" style="display:none;"><?php
    echo $this->mobile_log_content; ?>
  </div>
  <input type="button" value="Toggle All SQL" onclick="$('#sql_div').toggle()" style="margin: 10px;">
  <input type="button" value="Toggle Deltas" onclick="$('.client_deltas').toggle()" style="margin: 10px;">
  <input type="button" value="Toggle Datum" onclick="$('.dates').toggle()" style="margin: 10px;">
  <input type="button" value="Toggle Error" onclick="$('.errors').toggle()" style="margin: 10px;"><br>
  Anzahl Client-Deltas: <? echo count($this->mobile_logs); ?><p><?
  if ($this->mobile_sync_reset_needed($this->formvars['log_name'])) { ?>
    <span style="color: red; font-weight: bold;">Für diesen Nutzer ist im Log-File ein Reset der Synchronisation eingestellt.</span><?
  } ?>
  <div id="sql_div"><?php
    rsort($this->mobile_logs);
    foreach ($this->mobile_logs AS $log) { ?>
      <h3><span class="dates"><? echo $log['timestamp']; ?></span></h3>
      <input type="button" value="Toggle Textfield" onclick="$(this).next().next().toggle();" style="margin-top: 13px; float: right;">
      <div style="clear: both"></div>
      <div class="client_deltas"><pre><? echo implode('<br>', array_map(function($l) { return $l->sql . ';'; }, $log['client_deltas'])); ?></pre></div><?
      if ($log['error']) { ?>
        <span class="errors" style="color: red; font-weight: bold">Fehler: <? echo $log['error']; ?></span><br>
        <a href="index.php?go=mobile_set_sync_reset_needed&log_name=<? echo $this->formvars['log_name']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>">Fehler beheben</a><br><?
      }
    } ?>
  </div><?
} ?>