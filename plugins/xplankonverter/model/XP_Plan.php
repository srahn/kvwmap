<?php
#############################
# Klasse XP_Plan #
#############################

class XP_Plan extends PgObject {

	static $schema = 'xplan_gml';

	function XP_Plan($gui, $planart, $select = '*') {
		$this->planart = $planart;
		$this->planartAbk = strtolower(substr($planart, 0, 2));
		$this->tableName = $this->planartAbk . '_plan';
		$this->umlName = strtoupper($this->planartAbk) . '_Plan';
		$this->bereichTableName = $this->planartAbk . '_bereich';
		$this->bereichUmlName = strtoupper($this->planartAbk) . '_Bereich';
		$this->PgObject($gui, XP_Plan::$schema, $this->tableName);
		$this->bereiche = array();
		$this->select = $select;
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
		$this->debug->show('Objekt XP_Plan created with planart: ' . $this->planart . ' tableName: ' . $this->tableName, true);
	}

	public static	function find_by_id($gui, $by, $id, $planart) {
		$xp_plan = new XP_Plan($gui, $planart);
		$xp_plan->find_by($by, $id);
		return $xp_plan;
	}

	function get_anzeige_name() {
		return $this->get_first_planart_name() . ' ' . $this->get_first_gemeinde_name() . ' ' . $this->get('name') . ' Nr. ' . $this->get('nummer');
	}

	/*
	* Get the name of first planart
	* @return string
	*/
	function get_first_planart_name() {
		$planart_table = $this->get_planart_table();
		$planart_obj = new PgObject($this->gui, $this->schema, $planart_table['table_name']);
		$planart = $planart_obj->find_by($planart_table['value_attribute'], $this->get_first_planart_value());
		return $planart->get('name_attribute');
	}

	/*
	* Return the first value of planart attribute of xp_plan
	* @return string
	*/
	function get_first_planart_value() {
		return trim(explode(',', trim($this->get('planart'),'{}'))[0]);
	}

	/*
	* Return table name as well as value and name attribute for specified planart (BP-Plan, FP-Plan, SO-Plan or RP-Plan)
	* @return array()
	*/
	function get_planart_table() {
		switch ($this->planart) {
			case ('BP-Plan') : { $table_name = 'enum_bp_planart'; $value_attribute = 'wert'; $name_attribute = 'abkuerzung'; } break;
			case ('FP-Plan') : { $table_name = 'enum_fp_planart'; $value_attribute= 'wert'; $name_attribute = 'abkuerzung'; } break;
			case ('SO-Plan') : { $table_name = 'so_planart'; $value_attribute= 'id'; $name_attribute = 'value'; } break;
			case ('RP-Plan') : { $table_name = 'enum_rp_art'; $value_attribute= 'wert'; $name_attribute = 'beschreibung'; } break;
		}
		return array(
			'table_name' => $table_name,
			'value_attribute' => $value_attribute,
			'name_attribute' => $name_attribute
		);
	}

	/*
	* Return the name of the first gemeinde from gemeinde attribute
	* - if gemeinde is an array
	* 	- Replace {} by [] brackets
	* 	- Convert string as json to array
	* 	- Extract the first element of the array
	* - Replace () brackes
	* - Explode string by ,
	* - Extract the 3. Element of the array
	* - Strip empty Spaces
	* @return string
	*/
	function get_first_gemeinde_name() {
		$g = $this->get('gemeinde');
		if (strpos($g, '{') !== false) {
		  $g = json_decode(str_replace(array( '{', '}' ), array('[',']'), $g))[0];
		}
		return trim(explode(',',trim($g,'()'))[2]);
	}

	function get_bereiche() {
		$bereiche = array();
		$bereich = new XP_Bereich($this->gui, $this->planart);
		$bereiche = $bereich->find_where("
			gehoertzuplan = '{$this->get('gml_id')}'
		");
		return $bereiche;
	}
	
	/*
	* Löscht den Plan und alles was damit verbunden ist
	* Löscht die Bereiche
	*/
	function destroy() {
		$bereiche = $this->get_bereiche();
		foreach($bereiche AS $bereich) {
			$bereich->destroy();
		}
	}
}
?>
