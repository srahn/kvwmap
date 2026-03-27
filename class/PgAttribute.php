<?php
class PgAttribute {

	static $write_debug = false;

	function __construct($debug, $name, $type, $value, $validations = array(), $identifier = '', $relation = array()) {
		$this->debug = $debug;
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->validations = $validations;
		$this->is_identifier = $identifier == $name;
		$this->relation = $relation;
		$this->debug->show('<p>New MyAttribut: '. $this->name, PgAttribute::$write_debug);
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
			case ('b') : {
				$input_type = 'checkbox';
			} break;
			case ('tinyint(1)') : {
				$input_type = 'checkbox';
			} break;
			default : {
				$input_type = 'text';
			}
		}
		return $input_type;
	}

	function is_valid() {
		$results = array_reduce(
			$this->validations,
			function($results, $validation) {
				if ($validation['validated'] AND $validation['valid'] == false) {
					$results[] = $validation['result'];
				}
				return $results;
			},
			array()
		);
		return array(
			'success' => (count($results) == 0),
			'results' => $results
		);
	}

	function as_form_html() {
		$html = '';
		if ($this->type == 'fk') {
			$html .= "<label class=\"fetter\" for=\"" . $this->name . "\">" . $this->relation['alias'] . "</label>";
			$html .= "<div style=\"float: left; text-align: left;\">
				<ul>";
				foreach ($this->value AS $child) {
					$html .= "<li><a href=\"index.php?go=Layereditor&selected_layer_id=" . $child->get($child->identifier) . "&csrf_token=" . $_SESSION['csrf_token'] . "\">" . $child->get($this->relation['vorschau']) . "</a></li>";
				}
				$html .= "
				</ul>
			</div>";
		}
		else {
			$is_valid = $this->is_valid();
			if (!($this->is_identifier and $this->value == '')) {
				$html .= "<label class=\"fetter\" for=\"" . $this->name . "\">" . ucfirst($this->name) . ($this->is_mandatory() ? ' *' : '' ) . "</label>";
				if ($this->name == 'id') {
					$html .= "<span style=\"padding-top: 2px; float: left\">" . $this->value . "</span>";
				}
				else {
					switch ($this->type) {
						case 'b' : {
							$true_array = array('t', 'true', '1');
							$false_array = array('f', 'false', '0');
							$class = $is_valid['success'] ? 'valid' : 'alerts-border';
							$oninput = "$(this).removeClass('alerts-border'); $(this).addClass('valid'); if ($(this).next().hasClass('validation-error-msg-div')) { $(this).next().hide(); }";
							$null_option = ($this->is_mandatory() ? '' : '<option value=""' . ($this->value == '' ? ' selected' : '') . '></option>');
							$html .= '
								<select name="' . $this->name . '" class="' . $class . '" oninput="' . $oninput . '">'
									. $null_option . '
									<option value="false"' . (in_array($this->value, $false_array) ? ' selected' : '') . '>Nein</option>
									<option value="true"' . (in_array($this->value, $true_array) ? ' selected' : '') . '>Ja</option>
								</select>
							';
							// $html .= '<input type="checkbox" value="1" name="' . $this->name . '" class="' . $class . '" oninput="' . $oninput . '"' . $selected . '>';
							if (!$is_valid['success']) {
								$html .= "<div class=\"validation-error-msg-div\">" . implode('<br>', $is_valid['results']) . "</div>";
							}
						} break;
						default : {
							$html .= "<input name=\"" . $this->name . "\" type=\"" . $this->get_input_type() . "\" value=\"" . (($this->get_input_type() == 'checkbox' AND $this->value == '') ? 1 : htmlentities($this->value)) . "\" class=\"" . ($is_valid['success'] ? 'valid' : 'alerts-border') . "\" oninput=\"$(this).removeClass('alerts-border'); $(this).addClass('valid'); if ($(this).next().hasClass('validation-error-msg-div')) { $(this).next().hide(); }\">";
							if (!$is_valid['success']) {
								$html .= "<div class=\"validation-error-msg-div\">" . implode('<br>', $is_valid['results']) . "</div>";
							}
						}
					}
				}
			}
		}
		return $html;
	}
}
?>
