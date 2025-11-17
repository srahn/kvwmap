<?php
/**
* Klassen zum dynamischen Erzeugen von Formularobjekten
*
* Diese Datei wird in index.php mit include_() eingebunden.
*	
* FormObject
* selectFormObject extends FormObject
*
*/

########################################################
# Klasse zum dynamischen Erzeugen von Formularobjekten #
########################################################
# Klasse FormObject #
#####################

class FormObject {
	var $type;
	var $html;
	var $selected;
	var $select;
	var $hidden;
	var $text;
	var $anzValues;
	var $JavaScript;

	###################### Liste der Funktionen ####################################
	#
	# function FormObject($name,$type,$value,$selectedValue,$label,$size,$maxlenght,$multiple) - Construktor
	# function addOption($value,$selected,$label)
	# function changeSize($size)
	# function insertOption($value,$selected,$label,$insertafter)
	# function outputHTML()
	#
	################################################################################

	function __construct($name, $type, $value, $selectedValue, $label, $size, $maxlenght, $multiple, $width, $disabled = NULL, $style = "", $output = true) {
		if (!is_array($selectedValue)) { $selectedValue=array($selectedValue); }
		$this->type = $type;
		$this->width = $width;
		$this->disabled = $disabled;
		$this->style = $style;
		switch ($type) {
			case "select" : {
				if($value){
					$this->AnzValues=count($value);
				}
				$this->select['name']=$name;
				if(substr($size, 0, 3) == 'max'){
					$maxsize = substr($size, 3);
					$this->select['size'] = ($this->AnzValues < $maxsize) ? $this->AnzValues : $maxsize;
				}
				else {
					$this->select['size']=$size;
				}
				$this->select['multiple']=$multiple;
				for ($i=0;$i<$this->AnzValues;$i++) {
					$this->select['option'][$i]['value']=$value[$i];
					for ($j=0;$j<count($selectedValue);$j++) {
						if ($selectedValue[$j]==$value[$i]) {
							$this->selected=1;
							$this->select['option'][$i]['selected']=1;
						}
					}
					$this->select['option'][$i]['label']=$label[$i];
				}
			} break;
			case "text" : {
				$this->text['name']=$name;
				$this->text['value']=$value[0];
				$this->text['size']=$size;
				$this->text['maxlength']=$maxlength;
			} break;
			default : { # type hidden
				$this->hidden['name']=$name;
				$this->hidden['value']=$value[0];
			}
		} # ende switch type
		if ($output) {
			$this->outputHTML();
		}
	} # ende constructor

/**
 * Function to create a select field
 * @param $name string name attribute of the select field
 * @param $options array options for the select field (array with value and output keys)
 * @param $value string|int selected value
 * @param $size int size attribute of the select field
 * @param $style string style attribute of the select field
 * @param $onchange string onchange attribute of the select field
 * @param $id string id attribute of the select field
 * @param $multiple string if not empty the multiple attribute is added
 * @param $class string class attribute of the select field
 * @param $first_option string first option to be added with empty value
 * @param $option_style string style attribute for each option
 * @param $option_class string class attribute for each option
 * @param $onclick string onclick attribute for each option
 * @param $onmouseenter string onmouseenter attribute for the select field
 * @param $title string title attribute for the select field
 * @param $data array additional data attributes for the select field (arrays with key => value pairs)
 * @return string HTML code for the select field
 */
static	function createSelectField($name, $options, $value = '', $size = 1, $style = '', $onchange = '', $id = '', $multiple = '', $class = '', $first_option = '-- Bitte Wählen --', $option_style = '', $option_class = '', $onclick = '', $onmouseenter = '', $title = '', $data = array()) {
	$id = ($id == '' ? $name : $id);
	if ($multiple != '') {
		$multiple = ' multiple';
	}
	if ($style != '') $style = 'style="' . $style . '"';
	if ($onchange != '') $onchange = 'onchange="' . $onchange . '"';
	if ($onclick != '') $onclick = 'onclick="' . $onclick . '"';
	if ($class != '') $class = 'class="' . $class . '"';
	if ($option_style != '') $option_style = 'style="' . $option_style . '"';
	if ($option_class != '') $option_class = 'class="' . $option_class . '"';
	if ($onmouseenter != '') $onmouseenter = 'onmouseenter="' . $onmouseenter . '"';
	if ($title != '') $title = 'title="' . $title . '"';
	foreach ($data AS $data_key => $data_value) {
		$data[$data_key] = 'data-' . $data_key . '="' . $data_value . '"';
	}

	$options_html = array();
	if ($first_option != '') {
		$options_html[] = "<option value=\"\">" . $first_option . "</option>";
	}
	foreach($options AS $option) {
		if (is_string($option)) {
			$option = array('value' => $option, 'output' => $option);		// falls die Optionen kein value und output haben
		}
		if ($multiple != '') {
			$selected = (in_array(strval($option['value']), explode(',', $value)) ? ' selected' : '');
		}
		else {
			// echo 'option value: ' . $option['value'] . ' value: ' . $value . '<br>';
			// echo 'option value: ' . $option['value'] . ' value: ' . $value . '<br>';
			$selected = (strval($option['value']) === strval($value) ? ' selected' : '');
		}
		$options_html[] = "
			<option " . $onclick . " " . $option_style . " " . $option_class . " 
				value=\"{$option['value']}\"{$selected}" .
				(array_key_exists('attribute', $option) ? " {$option['attribute']}=\"{$option['attribute_value']}\"" : '') .
				(array_key_exists('title', $option) ? " title=\"{$option['title']}\"" : '') .
				(array_key_exists('style', $option) ? " style=\"{$option['style']}\"" : '') . "
			>{$option['output']}</option>";
	}

	$html  = '
<select id="' . $id . '" name="' . $name . ($multiple != '' ? '[]' : '') . '" size="' . $size . '" ' . $style . ' ' . $onchange . ' ' . $onmouseenter . ' ' . $multiple . ' ' . $class . ' ' . $title . ' ' . implode(' ', $data) . '>
	' . implode("\n", $options_html) . '
</select>';
  return $html;
}

	static function createCustomSelectField($name, $options, $value = '', $size = 1, $style = '', $onchange = '', $id = '', $multiple = '', $class = '', $first_option = '-- Bitte Wählen --', $option_style = '', $option_class = '', $onclick = '', $onmouseenter = '', $option_onmouseenter = '') {
		$id = ($id == '' ? $name : $id);
		if ($multiple != '') $multiple = ' multiple';
		if ($style != '') $style = 'style="' . $style . '"';
		if ($onchange != '') $onchange = 'onchange="' . $onchange . '"';
		if ($onclick != '') $onclick = 'onclick="' . $onclick . '"';
		if ($class != '') $class = 'class="' . $class . '"';
		if ($option_style != '') $option_style = 'style="' . $option_style . '"';
		if ($onmouseenter != '') $onmouseenter = 'onmouseenter="' . $onmouseenter . '"';

		$options_html = array();
		if ($first_option != '') {
			$options = array_merge([0 => ['value' => '', 'output' => $first_option]], $options);
		}
		foreach($options AS $option) {
			if (is_string($option)) {
				$option = array('value' => $option, 'output' => $option);		// falls die Optionen kein value und output haben
			}
			if (!array_key_exists('output', $option)) {
				$option['output'] = $option['value'];
			}
			if (strval($option['value']) === strval($value)) {
				$selected = ' selected';
				$output = $option['output'];
				$image = $option['image'];
			}
			else {
				$selected = '';
			}
			$options_html[] = "
				<li onclick=\"custom_select_click(this)\" onmouseenter=\"custom_select_hover(this);" . $option_onmouseenter . "\" " . $option_style . " class=\"" . $option_class . $selected . "\" 
					data-value=\"" . $option['value'] . "\"" .
					(array_key_exists('attribute', $option) ? " " . $option['attribute'] . "=\"" . $option['attribute_value'] . "\"" : '') .
					(array_key_exists('title', $option) ? " title=\"" . $option['title'] ."\"" : '') .
					(array_key_exists('style', $option) ? " style=\"" . $option['style'] . "\"" : '') . "
				>
					<img src=\"" . ($option['image']? 'data:image/' . pathinfo($option['image'])['extension'] . ';base64,' . base64_encode(@file_get_contents($option['image'])) : 'graphics/leer.gif') . "\">
					<span>" . $option['output'] ."</span>
				</li>";
		}

		$html  = '
			<div class="custom-select" id="custom_select_' . $id . '" ' . $style . '>
				<input type="hidden" ' . $onchange . ' ' . $class . ' id="' . $id . '" name="' . $name . '" value="' . $value . '">
				<div class="placeholder editable" onclick="toggle_custom_select(\'' . $id . '\');" '.$onmouseenter.'>
					<img src="' . ($image? 'data:image/' . pathinfo($image)['extension'] . ';base64,' . base64_encode(@file_get_contents($image)) : 'graphics/leer.gif') . '">
					<span>' . $output . '</span>
				</div>
				<div style="position:relative">
					<ul class="dropdown" id="dropdown">
						'.implode('', $options_html).'
					</ul>
				</div>
			</div>';
		return $html;
	}

	static function createCheckboxList($name, $options, $values = array(), $onchange = '') {
		$html = '';
		$check_all = empty($values) OR count($options) === count($values);
		if (count($options) > 5) {
			$html .= '<label><input id="select-all" type="checkbox" name="" value="" ' . ($check_all ? ' checked' : '') . ' onchange="' . $onchange . '"> --- Alle ---</label><br>';
		}
		$html .= implode('<br>', array_map(
			function ($option, $index) use ($name, $values, $onchange, $check_all) {
				$checked = $check_all || in_array($option['value'], $values) ? ' checked' : '';
				return '<label><input class="' . $name . '" type="checkbox" name="' . $name . '[' . $index . ']" value="' . htmlspecialchars($option['value']) . '" ' . $checked . ' onchange="' . $onchange . '"> ' . htmlspecialchars($option['output']) . '</label>';
			},
			$options,
			array_keys($options)
		));
		$html .= '
			<script>
				document.getElementById(\'select-all\').addEventListener(\'change\', function () {
				const checkboxes = document.querySelectorAll(\'.' . $name . '\');
				checkboxes.forEach(cb => cb.checked = this.checked);
			});
			</script>
		';
		return $html;
	}

	function addJavaScript($event,$script){
		$this->JavaScript.=' '.$event.'="'.$script.'"';
	}

	function addOption($value,$selected,$label) {
		$anzOption=count($this->select['option']);
		$this->select[option][$anzOption]['value']=$value;
		$this->select[option][$anzOption]['selected']=$selected;
		$this->select[option][$anzOption]['label']=$label;
	}

	function insertOption($value,$selected,$label,$insertafter) {
		# insertafter ist die Nummer der Option, nach der die neue Option eingefügt werden soll
		# die Zählung beginnt mit 1. Wenn z.B. eine Option an den Anfang gestellt werden soll
		# muss insertafter = 0 sein.
		$anzOption = count_or_0($this->select['option']);
		$oldvalue=$value;
		$oldselected=$selected;
		$oldlabel=$label;
		for($i=$insertafter;$i<$anzOption;$i++) {
			$tmpvalue=$this->select['option'][$i]['value'];
			$tmpselected=$this->select['option'][$i]['selected'];
			$tmplabel=$this->select['option'][$i]['label'];
			$this->select['option'][$i]['value']=$oldvalue;
			$this->select['option'][$i]['selected']=$oldselected;
			$this->select['option'][$i]['label']=$oldlabel;
			$oldvalue=$tmpvalue;
			$oldselected=$tmpselected;
			$oldlabel=$tmplabel;
		}
		$this->select['option'][$anzOption]['value']=$oldvalue;
		$this->select['option'][$anzOption]['selected']=$oldselected;
		$this->select['option'][$anzOption]['label']=$oldlabel;
	}

	function changeSize($size) {
		switch ($this->type) {
			case 'select' : {
				$this->select['size']=$size;
			} break;
			case 'text' : {
				$this->text['size']=$size;
			} break;
		}
	}

	function outputHTML() {
		#2005-11-29_pk
		switch ($this->type) {
			case "select" : {
				$this->html ="<select name='".$this->select["name"]."' size='".$this->select["size"]."' ";
				if ($this->width > 0) {
					$this->style .= (substr(trim($this->style), -1) != ';' ? ';' : '') . ' width: ' . $this->width . 'px;';
				}
				if ($this->disabled) {
					$this->html.=' disabled="true" ';
				}
				if ($this->select["multiple"]) {
					$this->html.=" multiple";
				}
				if ($this->JavaScript!='') {
					$this->html.=$this->JavaScript;
				}
				if ($this->style != '') {
					$this->html .= ' style="' . $this->style . '"';
				}
				$this->html.=">\n";
				for ($i = 0; $i < count_or_0($this->select['option']); $i++) {
					$this->html .= "<option value='" . $this->select["option"][$i]["value"] . "'";
					if (value_of($this->select["option"][$i], 'selected')) {
						$this->html .= " selected";
					}
					$this->html .= ">" . $this->select["option"][$i]["label"] . "</option>\n";
				}
				$this->html .= "</select>";
			} break;
			case "text" : {
				$this->html ="<input type='text' name='".$this->text["name"]."' value='".$this->text["value"]."'";
				$this->html.=" size='".$this->text["size"]."' maxlength='".$this->text["size"]."'>";
			} break;
			case "hidden" : {
				$this->html ="<input type='hidden' name='".$this->hidden["name"]."' value='".$this->hidden["value"]."'";
			}
		}
	}

	/**
	* Funktion zum Erzeugen von Radiobuttons aus einem Array mit der Möglichkeit ein gecheckten zu unchecken.
	* Label und Values der Optionen sind identisch
	* Ein Aufruf sieht folgendermaßen aus:
	* radio_fields('position', $this->formvars['position'], array('links', 'rechts', 'mitte'))
	*
	* @params $name String Der Name der Formularvariable
	* @params $value String oder Integer Der Wert, der voreingestellt werden soll. Wenn Null oder '' oder nicht in $options vorhanden wird nichts vorselektiert
	* @params $options Array(String oder Integer) Ein Array von Werten, die in den Optionen verwendet werden sollen. 
	*/
	function radio_fields($name, $value, $options) {
		$options_checked = array();
		foreach ($options AS $option) {
			$options_checked[] = "document.getElementById('{$name}_option_{$option}').checked";
		}
		$if_options_checked = implode(' || ', $options_checked);

		$html = "
			<fieldset
				style=\"border: none;\"
				>";
				foreach($options AS $option) {
					$checked = $value == $option ? 'true' : 'false';
					$html .= "
					<input
					type = \"radio\"
					id = \"{$name}_option_{$option}\"
					name = \"{$name}\"
					value = \"{$option}\"
					onclick = \"
					this.checked = this.checked2;
					\"
					onmousedown = \"
					if (this.checked2 == undefined) document.getElementById('{$name}_option_{$option}').checked2 = {$checked};
					this.checked2 = !this.checked;
					\"
					checked2 = \"{checked}\"
					" . ($checked == 'true' ? 'checked' : '') . "
					>
					<label for=\"{$name}_option_{$option}\">{$option}</label>";
				}
				$html .= "
			</fieldset>
		";
		return $html;
	}
} # end of Classe FormObject

##########################################################################################
# Klasse zum dynamischen Erzeugen von Formularobjekten mit automatischem Abschicken      #
# des Formulars nach Änderung der Auswahl über                                           #
# Java Script Funktionen (onchange='...' Erweiterung von Classe FormObject               #
##########################################################################################
# Klasse selectFormObject #
###########################
class selectFormObject extends FormObject{

	###################### Liste der Funktionen ####################################
	#
	# function outputHTML()
	#
	################################################################################

	function outputHTML() {
		$this->onchange=$onchange;
		switch ($this->type) {
			case 'select' : {
				$this->html ="<select name=\"".$this->select['name']."\" size=\"".$this->select['size']."\"";
				if ($this->select['multiple']) {
					$this->html.=' multiple';
				}
				if ($this->JavaScript!='') {
					$this->html.=$this->JavaScript;
				}
				if($this->nochange != true){
					$this->html.=" onchange=\"document.GUI.submit()\">\n";
				}
				for ($i = 0; $i < count_or_0($this->select['option']); $i++) {
					$this->html.="<option value=\"".$this->select['option'][$i]['value']."\"";
					if ($this->select['option'][$i]['selected']) {
						$this->html.=' selected';
					}
					$this->html.=">".$this->select['option'][$i]['label']."</option>\n";
				}
				$this->html.="</select>\n";
			} break;

			case 'text' : {
				$this->html ='<input type="text" name="'.$this->text['name'].'" value="'.$this->text['value'].'"';
				$this->html.=' size="'.$this->text['size'].'" maxlength="'.$this->text['size'].'">';
			} break;

			case 'hidden' : {
				$this->html ='<input type="hidden" name="'.$this->hidden['name'].'" value="'.$this->hidden['value'].'"';
			}
		}
	}
}
?>