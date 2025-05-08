<?php
# Script wird zum Ende von index.php abgearbeitet

if(!empty($GUI->allowed_documents)){
	$GUI->write_document_loader();
}

# Schließen der offenen Datenbankverbindungen

if (isset($GUI->pgdatabase) and $GUI->pgdatabase->dbConn > 0) { 
	$GUI->pgdatabase->close();
}

if (DEBUG_LEVEL > 0) {
	$GUI->debug->close();
}

# Schließen des Postgres Logfiles
if (LOG_LEVEL> 0) {
	$GUI->log_postgres->close();
}
?>
