<?php
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
?>