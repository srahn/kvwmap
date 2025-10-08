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
<input type="button" value="Toggle vollständiges Log" onclick="$('#log_div').toggle()" style="margin: 10px;">
<div id="log_div" style="display:none;"><?php
  echo $this->mobile_log_content; ?>
</div>

<input type="button" value="Toggle All SQL" onclick="$('#sql_div').toggle()" style="margin: 10px;">
<input type="button" value="Toggle Deltas" onclick="$('.client_deltas').toggle()" style="margin: 10px;">
<input type="button" value="Toggle Datum" onclick="$('.dates').toggle()" style="margin: 10px;">
<input type="button" value="Toggle Error" onclick="$('.errors').toggle()" style="margin: 10px;"><br><?
echo 'Anzahl Client-Deltas: ' . count($this->mobile_logs) . '<p>'; ?>
<div id="sql_div"><?php
  rsort($this->mobile_logs);
	foreach ($this->mobile_logs AS $log) { ?>
		<h3><span class="dates"><? echo $log['timestamp']; ?></span></h3>
    <input type="button" value="Toggle Textfield" onclick="$(this).next().next().toggle();" style="margin-top: 13px; float: right;">
    <div style="clear: both"></div>
    <div class="client_deltas"><pre><? echo implode('<br>', array_map(function($l) { return $l->sql; }, $log['client_deltas'])); ?></pre></div><?
    if ($log['error']) { ?>
      <span class="errors" style="color: red; font-weight: bold">Fehler: <? echo $log['error']; ?></span><br>
      <a href="index.php?go=mobile_fix_sync_delta&timestamp=<? echo $log['timestamp']; ?>&client_delta=<? echo urlencode($log['client_deltas'][0]->sql); ?>&csrf_token<? echo $_SESSION['CSRF_TOKEN']; ?>">Fehler beheben</a><br><?
    }
	} ?>
</div>