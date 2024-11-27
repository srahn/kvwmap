<?
  error_reporting(E_ALL & ~(E_STRICT|E_NOTICE|E_WARNING));

  include('../../../config.php');
  include(PLUGINS . 'metadata/config/config.php');
  include(WWWROOT . APPLVERSION . 'funktionen/allg_funktionen.php');
  include(CLASSPATH . 'kvwmap.php');
  include(CLASSPATH . 'log.php');
  include(CLASSPATH . 'postgresql.php');
  include(PLUGINS . 'metadata/model/Ressource.php');
  $debug = new Debugger(DEBUGFILE);
  $GUI = new stdClass();
  $GUI->pgdatabase = new pgdatabase();
  $GUI->pgdatabase->open(1);

  /**
   * Request for the first outdated ressource and update it
   *  status:
   * -1 - Abbruch wegen Fehler
   *  0 - Uptodate
   *  1 - Update gestartet
   *  2 - Download gestartet
   *  3 - Download fertig
   *  4 - Auspacken gestartet
   *  5 - Auspacken fertig
   *  6 - Import gestartet
   *  7 - Import fertig
   *  8 - Transformation gestartet
   *  9 - Transformation fertig
   */
  Ressource::update_outdated($GUI);
?>