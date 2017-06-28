<?php
class MyAttribute {

	static $write_debug = false;

	function MyAttribute($debug, $name, $type, $value, $validations = array(), $identifier = '') {
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

	function as_form_html() {
		$html = '';
		if (!($this->is_identifier and $this->value == '')) {
			$html .= "<label class=\"fetter\" for=\"" . $this->name . "\">" . $this->name . ($this->is_mandatory() ? ' *' : '' ) . "</label>";
			if ($this->name == 'id') {
				$html .= "<span style=\"padding-top: 2px; float: left\">" . $this->value . "</span>";
			}
			else {
				$html .= "<input name=\"" . $this->name . "\" type=\"text\" value=\"" . $this->value . "\">";
			}
		}
		return $html;
	}
}
?>
