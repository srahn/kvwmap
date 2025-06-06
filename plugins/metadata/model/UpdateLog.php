<?php
#############################
# Klasse UpdateLog #
#############################

class UpdateLog extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'update_logs';
	static $write_debug = false;

  function __construct($gui) {
		$gui->debug->show('Create new Object updateLog', UpdateLog::$write_debug);
		parent::__construct($gui, UpdateLog::$schema, UpdateLog::$tableName);
	}

  public static function write($gui, $ressource, $result, $show = false) {
    $update_log = new UpdateLog($gui);
    $update_log->create(
      array(
        'msg' => $result['msg'],
        'abbruch_status_id' => $ressource->get('status_id'),
        'ressource_id' => $ressource->get_id()
      )
    );
    if ($show) {
      echo '<br>' . $result['msg'] . ($ressource->get('status_id') ? '<br>Letzte status_id: ' . $ressource->get('status_id') : '') . '<br>ressource_id: ' . $ressource->get_id();
    }
  }
} 