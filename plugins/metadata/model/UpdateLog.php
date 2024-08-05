<?php
#############################
# Klasse UpdateLog #
#############################

class UpdateLog extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'update_log';
	static $write_debug = false;

  function __construct($gui) {
		$gui->debug->show('Create new Object update_log', UpdateLog::$write_debug);
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
      echo '<br>UpdateLog:<br>msg: ' . $result['msg'] . '<br>abbruch_status_id: ' . $ressource->get('status_id') . '<br>ressource_id: ' . $ressource->get_id();
    }
  }
} 