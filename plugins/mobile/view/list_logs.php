<style>
  #log_file_div {
    margin: 10px 20% 10px 30%;
    padding: 5px;
    text-align: left;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
  }
</style>
<h2 style="margin: 10px"><?php echo $this->titel; ?></h2>
<div id="log_file_div"><?php
  $mobile_log_files = array_map(
    function($log_file) {
      $log_name = str_replace('_debug_log.html', '', str_replace(LOGPATH . 'kvmobile/', '', $log_file));
      $log_content = file_get_contents($log_file);
      return '<a href="index.php?go=mobile_show_log&log_name=' . $log_name . '&csrf_token=' . $_SESSION['csrf_token'] . '" title="Zeigt Log-Datei an.">' . $log_name . '</a> ' . human_filesize($log_file) . (strpos($log_content, 'Fehler mit result:') !== false ? ' (enthält Fehlermeldung)' : '') . (filesize($log_file) > 500 * 1024 * 1024 ? ' (<a href="index.php?go=mobile_reset_log&log_name=' . $log_name . '&csrf_token=' . $_SESSION['csrf_token'] . '" title="Die Datei wird mit einem Zeitstempel im Postfix gesichert und anschließend geleert. Anschließend wird diese Seite wieder angezeigt.">sichern und neu</a>)' : '');
    },
    $this->administration->get_mobile_logs()
  );
  echo implode('<br>', $mobile_log_files); ?>
</div>
