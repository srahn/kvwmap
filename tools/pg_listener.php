<?php
  define('DRY_RUN', true);

  function log_prefix() {
    global $channel;
    return "\n" . date("Y-m-d H:i:s") . ' channel: ' . $channel . ' ';
  }

  function is_cli() {
    return php_sapi_name() === 'cli';
  }

  if (!is_cli())
    exit(log_prefix() . "Script beendet, weil es nicht über Komandozeile aufgerufen wurde.");

  $channel = @$argv[1];
  if ($channel == '')
    exit(log_prefix() . "Script beendet, weil kein Channel angegeben wurde.");

  $conn_string = @$argv[2];
  if ($conn_string == '')
    exit(log_prefix() . "Script beendet, weil kein Connection String übergeben wurde.");

  $log_table = @$argv[3];

  echo log_prefix() . "Listener gestartet mit " . $conn_string . " und Log-Table=" . $log_table;
  $cmd = '';
  $conn = pg_connect($conn_string, PGSQL_CONNECT_FORCE_NEW);
  pg_query($conn, 'LISTEN ' . $channel . ';');

  while ($cmd != 'stop listener') {
    $message = pg_get_notify($conn, PGSQL_ASSOC);
    if ($message == false) {
      echo log_prefix() . "Warte 5 Sekunden";
      sleep(5);
    }
    else {
      echo log_prefix() . "pid: " . $message['pid'] . " Payload: " . $message['payload'];
      if ($message['payload'] == 'stop listener') break;
      
      if (!DRY_RUN) $result = pg_query(
        $conn,
        $message['payload']
      );
      
      if ($log_table != '') {
        $sql = "
          INSERT INTO " .
            $log_table . " (
              pid,
              message,
              channel
            )
          VALUES (" .
            $message['pid'] . ", '" .
            $message['payload'] . "', '" .
            $channel . "'
          );
        ";
        pg_query($conn, $sql);
      }
    }
  }
  pg_close($conn);
  echo log_prefix() . "Listener auf Channel '" . $channel . "' gestopped.";
  exit();
?>