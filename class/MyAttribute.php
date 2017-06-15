<?php
class MyAttribute {

	static $write_debug = false;

	function MyAttribute($debug, $name, $type, $value) {
		$this->debug = $debug;
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->debug->show('<p>New MyAttribut: '. $this->name, MyAttribute::$write_debug);
	}

	function as_form_html() {
		$html = "<label for=\"" . $this->name . "\">" . $this->name ."</label>";
		if ($this->name == 'id') {
			$html .= "<span style=\"float: left\">" . $this->value . "</span>";
		}
		else {
			$html .= "<input name=\"" . $this->name . "\" type=\"text\" value=\"" . $this->value . "\">";
		}
		return $html;
	}
}
?>
