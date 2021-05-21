<?php
class MyAttribute {

	static $write_debug = false;

	function __construct($debug, $name, $type, $value, $validations = array(), $identifier = '') {
		$this->debug = $debug;
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->validations = $validations;
		$this->is_identifier = $identifier == $name;
		$this->debug->show('<p>New MyAttribut: '. $this->name, MyAttribute::$write_debug);
	}

	function is_mandatory() {
		return count(
			array_filter(
				$this->validations,
				function ($validation) {
					return $validation['condition'] == 'not_null';
				}
			)
		) > 0;
	}

	function get_input_type() {
		switch ($this->type) {
			case ('tinyint(1)') : {
				$input_type = 'checkbox';
			} break;
			default : {
				$input_type = 'text';
			}
		}
		return $input_type;
	}

	function as_form_html() {
		$html = '';
		if (!($this->is_identifier and $this->value == '')) {
			$html .= "<label class=\"fetter\" for=\"" . $this->name . "\">" . ucfirst($this->name) . ($this->is_mandatory() ? ' *' : '' ) . "</label>";
			if ($this->name == 'id') {
				$html .= "<span style=\"padding-top: 2px; float: left\">" . $this->value . "</span>";
			}
			else {
				$html .= "<input name=\"" . $this->name . "\" type=\"" . $this->get_input_type() . "\" value=\"" . $this->value . "\">";
			}
		}
		return $html;
	}
}
?>
