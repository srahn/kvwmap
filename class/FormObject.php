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

	###################### Liste der Funktionen ####################################
	#
	# function FormObject($name,$type,$value,$selectedValue,$label,$size,$maxlenght,$multiple) - Construktor
	# function addOption($value,$selected,$label)
	# function changeSize($size)
	# function insertOption($value,$selected,$label,$insertafter)
	# function outputHTML()
	#
	################################################################################

	function FormObject($name, $type, $value, $selectedValue, $label, $size, $maxlenght, $multiple, $width, $disabled = NULL, $style = "") {
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
				if ($size=='Anzahl Werte') {
					$this->select['size']=$this->AnzValues;
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
		$this->outputHTML();
	} # ende constructor

static	function createSelectField($name, $options, $value = '', $size = 1, $style = '', $onchange = '', $id = '', $multiple = '') {
	$id = ($id == '' ? $name : $id);
	if ($multiple != '') $multiple = ' multiple';
	if ($style != '') $style = 'style="' . $style . '"';
	if ($onchange != '') $onchange = 'onchange="' . $onchange . '"';

	$options_html = array();
	foreach($options AS $option) {
		$selected = ($option['value'] == $value ? ' selected' : '');
		$options_html[] = "
			<option
				value=\"{$option['value']}\"{$selected}" .
				(array_key_exists('attribute', $option) ? " {$option['attribute']}=\"{$option['attribute_value']}\"" : '') .
				(array_key_exists('title', $option) ? " title=\"{$option['title']}\"" : '') .
				(array_key_exists('style', $option) ? " style=\"{$option['style']}\"" : '') . "
			>{$option['output']}</option>";
	}

	$html  = "
<select id=\"{$id}\" name=\"{$name}\" size=\"{$size}\" {$style} {$onchange} {$multiple}>
	" . implode('<br>', $options_html) . "
</select>
";
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
		$anzOption=count($this->select['option']);
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
				if($this->width > 0) {
					$this->html.="style='width:".$this->width."px'";
				}
				if($this->disabled) {
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
				for ($i=0;$i<count($this->select[option]);$i++) {
					$this->html.="<option value='".$this->select["option"][$i]["value"]."'";
					if ($this->select["option"][$i]["selected"]) {
						$this->html.=" selected";
					}
					$this->html.=">".$this->select["option"][$i]["label"]."</option>\n";
				}
				$this->html.="</select>";
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
# Classe zum dynamischen Erzeugen von Formularobjekten mit automatischem Abschicken nach #
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
				for ($i=0;$i<count($this->select[option]);$i++) {
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