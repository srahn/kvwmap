<style>
  #log_file_div {
    margin-left: 25px;
    margin-right: 25px;
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
      $html = '<a href="index.php?go=mobile_show_log&log_name=' . $log_name . '&csrf_token=' . $_SESSION['csrf_token'] . '" title="Zeigt Log-Datei an.">' . $log_name . '</a> ' . human_filesize($log_file) . (filesize($log_file) > 500 * 1024 * 1024 ? ' (<a href="index.php?go=mobile_reset_log&log_name=' . $log_name . '&csrf_token=' . $_SESSION['csrf_token'] . '" title="Die Datei wird mit einem Zeitstempel im Postfix gesichert und anschließend geleert. Anschließend wird diese Seite wieder angezeigt.">sichern und neu</a>)' : '');
      $log_content = file_get_contents($log_file);
      if (strpos($log_content, 'Fehler: ') !== false) {
        $handle = fopen($log_file, 'r');
        $result = [];
        $ignore = true;
        while (($line = fgets($handle)) !== false) {
          if (count($result) == 0) {
            if (strpos($line, '<h1>Debug: ') !== false) {
              $parts = explode(' ',trim($line));
              if ($parts[1] > '2026-04-15') {
                $ignore = false;
              }
            }
            if ($ignore) {
              continue;
            }
            if (strpos($line, 'Fehler: ') !== false) {
              $result[] = trim($line);
            }
          }
          else {
            if (strpos($line, '<h1>Debug: ') !== false) {
              break;
            }
            else {
              $result[] = trim($line);
            }
          }
        }
        fclose($handle);
        if (count($result) > 0) {
          $fehler = implode("\n", $result);
          $html .= ' enthält Fehlermeldung:<br><textarea rows="3" style="width: 100%;">' . $fehler . '</textarea>';
        }
      }
      return $html;
    },
    $this->administration->get_mobile_logs()
  );
  echo implode('<br>', $mobile_log_files); ?>
</div>
