<?php
# Script wird zum Ende von index.php abgearbeitet

# Schließen der offenen Datenbankverbindungen
if (isset($user)) {
  if ($user->database->dbConn>0) { $user->database->close(); }
}  
if ($GUI->database->dbConn>0) { $GUI->database->close(); }
if ($GUI->pgdatabase->dbConn>0) { $GUI->pgdatabase->close(); }
if (isset($GUI->ALKISdb)) {
  if ($GUI->ALKISdb->dbConn>0) { $GUI->ALKISdb->close(); }
}  
if (isset($GUI->Gazdb)) {
  if ($GUI->Gazdb->dbConn>0) { $GUI->Gazdb->close(); }
}  
if (DEBUG_LEVEL>0) { $debug->close(); }
# Schließen des Postgres Logfiles
if (LOG_LEVEL>0){
  $log_postgres->close();
}
?>
