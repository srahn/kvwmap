<script type="text/javascript">
	$.fn.optVisible = function(show) {
		if (show) {
			if (this.parent().is('span')) {
				this.unwrap('span');
			}
		}
		else {
			if (!this.parent().is('span')) {
				this.wrap("<span>").parent().hide();
			}
		}
		return this;
	};

	function filterFlurAndFlurstuecke() {
		var gemkgschl = $('#gemkgnr').val(),
				flstOptions = $('#flurstueckskennzeichen option'),
				flurOptions = $('#flur option'),
				fluren = [];
				debug_f = flstOptions;

		$.each(flstOptions, function(index, option) {
			var flstgemkgschl = (parseInt(option.value.substring(2, 6), 10) || '').toString(),
					hide = (gemkgschl != '' && option.value != '' && flstgemkgschl != gemkgschl),
					flur;

			if (hide) {
				if (option.selected) {
					option.selected = false;
				}
				$(option).optVisible(false);
			}
			else {
				if (option.value != '') {
					flur = parseInt(option.value.substring(6, 9), 10)
					fluren[flur] = true;
				}
				$(option).optVisible(true);
			}
		});

		$.each(flurOptions, function(index, option) {
			var flur = option.value,
					hide = (option.value != '' & !(fluren[option.value] ? true : false));

			if (hide) {
				if (option.selected) {
					option.selected = false;
				}
				$(option).optVisible(false);
			}
			else {
				$(option).optVisible(true);
			}
		});
	}

	function filterFlurstuecke() {
		var gemkgschl = $('#gemkgnr').val(),
				flur = $('#flur').val(),
				options = $('#flurstueckskennzeichen option');

		$.each(options, function(index, option) {
			var flstgemkgschl = (parseInt(option.value.substring(2, 6), 10) || '').toString(),
					flstflur = (parseInt(option.value.substring(6, 9), 10) || '').toString(),
					hide;
			
			if (gemkgschl == '') {
				hide = (flur != '' && option.value != '' && flstflur != flur);
			}
			else {
				hide = (flur != '' && option.value != '' && (flstgemkgschl != gemkgschl || flstflur != flur));
			}

			if (hide) {
				if (option.selected) {
					option.selected = false;
				}
				$(option).optVisible(false);
			}
			else {
				$(option).optVisible(true);
			}
		});
	}
</script>
<?php
include_once(CLASSPATH . 'FormObject.php');

function getAttributOptions($field_name, $attributes) {
	$options = array_map(
		function($value, $output) {
			return array('value' => $value, 'output' => $output);
		},
		$attributes['enum_value'][$attributes['indizes'][$field_name]],
		$attributes['enum_output'][$attributes['indizes'][$field_name]]
	);
	array_unshift($options, array('value=' => '', 'output' => '-- Bitte Wählen --'));
	return $options;
}

$operator_options = array(
	array('title' => 'Der Suchbegriff muss exakt so in der Datenbank stehen', 'value' => '=', 'output' => '='),
	array('title' => 'Der Suchbegriff kommt so NICHT in der Datenbank vor', 'value' => '!=', 'output' => '!='),
	array('title' => 'kleiner als: nur bei Zahlen verwenden!', 'value' => '<', 'output' => '&lt;'),
	array('title' => 'größer als: nur bei Zahlen verwenden!', 'value' => '>', 'output' => '&gt;'),
	array('title' => 'Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein', 'value' => 'LIKE', 'output' =>'ähnlich'),
	array('title' => 'Fügen Sie das %-Zeichen vor und/oder nach dem Suchbegriff für beliebige Zeichen ein', 'value' => 'NOT LIKE', 'output' => 'nicht ähnlich'),
	array('title' => 'Sucht nach Datensätzen ohne Eintrag in diesem Attribut', 'value' => 'IS NULL', 'output' => 'ist leer'),
	array('title' => 'Sucht nach Datensätzen mit beliebigem Eintrag in diesem Attribut', 'value' => 'IS NOT NULL', 'output' => 'ist nicht leer'),
	array('title' => 'Sucht nach mehreren exakten Suchbegriffen, zur Trennung \'|\' verwenden:  [Alt Gr] + [<]', 'value' => 'IN', 'output' => 'befindet sich in')
);

$altesneues_options = array(
	array('title' => 'In zeigtaufaltes und zeigtaufneuesflurstueck suchen', 'value' => '', 'output' => '-- Bitte wählen --'),
	array('title' => 'Nur in zeigtaufaltesflurstueck suchen', 'value' => 'altes', 'output' => 'altes'),
	array('title' => 'Nur in zeigtaufneuesflurstueck suchen', 'value' => 'neues', 'output' => 'neues')
);


?>
<pre style="text-align: left"><?php #print_r($this->attributes); ?></pre>
<h2 style="margin-top: 20px">Fortführungsnachweise Suchen</h2>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="" style="margin-top: 20px">
	<tr>
		<th>Attribut</th>
		<th>Operator</th>
		<th>Wert</th>
	</tr>
	<tr>
		<?php $field_name = 'gemkgnr'; ?>
		<th style="text-align: left">Gemarkung</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left">
			<?php echo FormObject::createSelectField('value_'. $field_name, getAttributOptions($field_name, $this->attributes), '', 1, '', 'filterFlurAndFlurstuecke()', $field_name); ?>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'gemkgschl'; ?>
		<th style="text-align: left">Gemarkungsschlüssel</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<input name="value_<?php echo $field_name; ?>" value="" size="4">
		</td>
	</tr>

	<tr>
		<?php $field_name = 'jahr'; ?>
		<th style="text-align: left">Fortführungsjahr</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<?php echo FormObject::createSelectField('value_'. $field_name, getAttributOptions($field_name, $this->attributes)); ?>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'fnnr'; ?>
		<th style="text-align: left">FN-Nr.</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<input name="value_<?php echo $field_name; ?>" value="" size="12">
		</td>
	</tr>

	<tr>
		<?php $field_name = 'antragsnr'; ?>
		<th style="text-align: left">Antragsnr.</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<input name="value_<?php echo $field_name; ?>" value="" size="12">
		</td>
	</tr>

	<tr>
		<?php $field_name = 'flur'; ?>
		<th style="text-align: left">Flur</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<?php echo FormObject::createSelectField('value_'. $field_name, getAttributOptions($field_name, $this->attributes), '', 1, '', 'filterFlurstuecke()', $field_name); ?>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'flurstueckskennzeichen'; ?>
		<th style="text-align: left">Flurstueck</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<?php echo FormObject::createSelectField('value_'. $field_name, getAttributOptions($field_name, $this->attributes), '', 1, '', '', $field_name); ?>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'altesneues'; ?>
		<th style="text-align: left" colspan="2">zeigt auf altes / neue Flurstück</td>
		<td align="left" width="40%">
			<?php echo FormObject::createSelectField('value_'. $field_name, $altesneues_options); ?>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'bemerkung'; ?>
		<th style="text-align: left">Bemerkung</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<textarea name="value_<?php echo $field_name; ?>"></textarea>
		</td>
	</tr>

	<tr>
		<?php $field_name = 'created_at'; ?>
		<th style="text-align: left">erstellt am</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<input name="value_<?php echo $field_name; ?>" value="" size="12">
		</td>
	</tr>

	<tr>
		<?php $field_name = 'updated_at'; ?>
		<th style="text-align: left">geändert am</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<input name="value_<?php echo $field_name; ?>" value="" size="12">
		</td>
	</tr>

	<tr>
		<?php $field_name = 'user_name'; ?>
		<th style="text-align: left">bearbeitet von</td>
		<td align="center">
			<?php echo FormObject::createSelectField('operator_' . $field_name, $operator_options, '', 1, 'width: 45px'); ?>
		</td>
		<td align="left" width="40%">
			<?php echo FormObject::createSelectField('value_'. $field_name, getAttributOptions($field_name, $this->attributes)); ?>
		</td>
	</tr>

	<tr>
		<td style="text-align: left" colspan="3">
			<i>Zur nicht exakten Suche verwenden Sie den 
			Operator "ähnlich" und den Platzhalter %.<br>
			Für Datumsangaben verwenden Sie bitte das 
			Format "TT.MM.JJJJ".</i>
		</td>
	</tr>
	<tr>
		<th style="text-align: left" colspan="2">
			<input size="2" onkeyup="checknumbers(this, 'int2', '', '');" type="text" name="anzahl" value="10" style="margin-right: 10px">Treffer anzeigen
		</td>
		<td>
			<input class="button" type="submit" name="go_plus" value="Suchen" style="margin: 10px;">
			<input type="hidden" name="selected_layer_id" value="<?php echo LAYER_ID_FF_AUFTRAG; ?>">
			<input type="hidden" name="go" value="fortfuehrungslisten_fn_suche">
		</td>
	</tr>
</table>

<img id="loaderimg" src="graphics/ajax-loader.gif" style="display: none;">