<?php

class Funktion extends PgObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'kvwmap', 'u_funktionen');
	}

	public static	function find($gui, $where) {
		$funktion = new Funktion($gui);
		return $funktion->find_where($where);
	}

	function getFunktionen($id, $order, $stelle_id = 0, $admin_id = 0) {
		global $admin_stellen;
		$where = array();
		$more_from = '';

		if ($admin_id > 0 AND !in_array($stelle_id, $admin_stellen)) {
			$more_from = "
				JOIN kvwmap.u_funktion2stelle f2s ON f.id = f2s.funktion_id
				JOIN kvwmap.rolle r ON r.stelle_id = f2s.stelle_id
			";
			$where[] = "r.user_id = " . $admin_id;
		}

		if ($id > 0) {
			$where[] = 'f.id = ' . $id;
		}

		if ($order != '') {
			$order = ' ORDER BY ' . replace_semicolon($order);
		}

		$sql = "
			SELECT DISTINCT
				f.*
			FROM
				kvwmap.u_funktionen f" .
				$more_from .
				(count($where) > 0 ? " WHERE " . implode(' AND ', $where) : "") .
			$order . "
		";
		#echo '<br>sql: ' . $sql;

		/*
		$sql ='SELECT * FROM u_funktionen WHERE 1=1';
		if ($id>0) {
		$sql.=' AND id='.$id;
		}
		if ($order!='') {
		$sql.=' ORDER BY ' . replace_semicolon($order);
		}
		*/
		$this->debug->write("<p>file:users.php class:funktion->getFunktionen - Abfragen einer oder aller Funktionen:<br>".$sql,4);
    $ret1 = $this->database->execSQL($sql, 4, 1);
    if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs = pg_fetch_assoc($ret1[1])){
			$funktionen[]=$rs;
		}
		return $funktionen;
	}

	/**
	 * ToDo: Use PgObject functions for CRUD
	 */
	function NeuAnlegen($formvars){
		$sql = "
			INSERT INTO kvwmap.u_funktionen
				(bezeichnung)
			VALUES 
				('" . $formvars['bezeichnung'] . "')
			RETURNING id";
		$result = $this->database->execSQL($sql,4, 1);
		$rs = pg_fetch_assoc($result[1]);
		$ret[1] = $rs['id'];
		return $ret;
	}

	function Aendern($formvars){
		$sql = "UPDATE kvwmap.u_funktionen SET id = ".(int)$formvars['id'].", bezeichnung = '".$formvars['bezeichnung']."' ";
		$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
	}

	function Loeschen($formvars){
		$sql = "DELETE FROM kvwmap.u_funktionen ";
		$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
		$sql = "DELETE FROM kvwmap.u_funktion2stelle ";
		$sql.= "WHERE funktion_id = ".(int)$formvars['selected_function_id'];
		$ret=$this->database->execSQL($sql,4, 1);
	}
}
?>
