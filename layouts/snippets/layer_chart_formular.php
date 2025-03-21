
<?
/**
 * Formular zur Bearbeitung von Diagrammdefinitionen
 */
include(LAYOUTPATH . 'languages/layer_chart_' . rolle::$language . '.php');
include_once(CLASSPATH . 'FormObject.php') ?>

<h2 style="margin-top: 20px"><? echo $strLayerChartTitle; ?></h2><?
if ($this->formvars['layer_id'] == '') {
	$this->Fehlermeldung = $strLayerChartMissingLayerId;
}
elseif ($this->layer->get('name') == '') {
	$this->Fehlermeldung = $strLayerChartMissingLayer;
}
elseif ($this->formvars['id'] != '' AND $this->layer_chart->get_id() == '') {
	$this->Fehlermeldung = $strLayerChartMissingLayerChart;
}
else {
	$this->Fehlermeldung = '';
}
if ($this->Fehlermeldung != '') {
	include('Fehlermeldung.php');
}
else { ?>
	<style>
		.input-form {
			width: 100%;
		}
		.input-form label {
			padding: 5px;
			margin: 2 8 5 0;
			height: auto;
			width: 40%;
		}
		.input-form input[type="text"], .input-form select {
			margin: 5px;
		}
	</style>
	<script>
		function show_layer_editor(event) {
			event.preventDefault();
			window.location = 'index.php?go=Layereditor&selected_layer_id=<? echo $this->layer->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>#query_parameter';
		}

		function show_layer_charts(event) {
			event.preventDefault();
			Check = confirm('<? echo $this->strCancleInput; ?>');
			if (Check == true) {
				window.location = 'index.php?go=layer_charts_Anzeigen&layer_id=<? echo $this->layer->get_id(); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
			}
		}
	</script>
	<div class="center-outerdiv">
		<div class="input-form">
			<em><span class="px13">Werte mit * mussen eingetragen werden</span></em><p>

			<label class="fetter form-label" for="layer_id">Layer:</label>
			<input type="hidden" class="form-field" name="layer_id" value="<? echo $this->layer_chart->get('layer_id'); ?>">
			<span style="float:left; margin: 7px;"><b><? echo $this->layer->get('name'); ?></b> (id: <? echo $this->layer->get_id(); ?>)</span>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="id">Diagramm ID:</label><?php
			if ($this->layer_chart->get_id() == '') { ?>
				<span style="float:left; margin: 7px;">wird beim Speichern automatisch gesetzt</span><?
			}
			else { ?>
				<input type="text" class="form-field" name="id" value="<? echo $this->layer_chart->get('id'); ?>"><?php
			} ?>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="title">Diagrammtitel:</label>
			<input type="text" class="form-field" name="title" value="<? echo $this->layer_chart->get('title'); ?>">
			<div style="clear: both"></div>

			<label class="fetter form-label" for="type">Diagrammtyp:</label>
			<select class="form-field" name="type">
				<option value="bar"<? echo ($this->layer_chart->get('type') == 'bar' ? ' selected' : ''); ?>>Balken</option>
				<option value="pie"<? echo ($this->layer_chart->get('type') == 'pie' ? ' selected' : ''); ?>>Torten</option>
				<option value="doughnut"<? echo ($this->layer_chart->get('type') == 'doughnut' ? ' selected' : ''); ?>>Doughnut</option>
			</select>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="value_attribute_label">Beschriftung Balken:</label>
			<input class="form-field" type="text" name="value_attribute_label" value="<? echo $this->layer_chart->get('value_attribute_label'); ?>">
			<div style="clear: both"></div>

			<label class="fetter form-label" for="aggregate_function">Aggregationsfunktion:</label>
			<select class="form-field" name="aggregate_function">
				<option value="sum"<? echo ($this->layer_chart->get('aggregate_function') == 'sum' ? ' selected' : ''); ?>>Summe</option>
				<option value="average"<? echo ($this->layer_chart->get('aggregate_function') == 'average' ? ' selected' : ''); ?>>Durchschnitt</option>
				<option value="min"<? echo ($this->layer_chart->get('aggregate_function') == 'min' ? ' selected' : ''); ?>>Minimalwert</option>
				<option value="max"<? echo ($this->layer_chart->get('aggregate_function') == 'max' ? ' selected' : ''); ?>>Maximalwert</option>
			</select>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="value_attribute_name">Werteattribut (Hochwert):</label><?
			echo FormObject::createSelectField(
				'value_attribute_name',
				array_map(
					function($attribute) {
						return array(
							'value' => $attribute->get('name'),
							'output' => ($attribute->get('alias') != '' ? $attribute->get('alias') : $attribute->get('name'))
						);
					},
					$this->layer->attributes
				),
				$this->layer_chart->get('value_attribute_name'),
				1, '', '', '', '',
				'diagram_form_elem_' . $id
			); ?>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="label_attribute_name">Beschriftungsattribut (Rechtswert):</label><?
			echo FormObject::createSelectField(
				'label_attribute_name',
				array_map(
					function($attribute) {
						return array(
							'value' => $attribute->get('name'),
							'output' => ($attribute->get('alias') != '' ? $attribute->get('alias') : $attribute->get('name'))
						);
					},
					$this->layer->attributes
				),
				$this->layer_chart->get('label_attribute_name'),
				1, '', '', '', '',
				'diagram_form_elem_' . $id
			); ?>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="label_attribute_name">Beschreibung:</label>
			<textarea class="form-field" name="beschreibung"><? echo $this->layer_chart->get('beschreibung'); ?></textarea>
			<div style="clear: both"></div>

			<label class="fetter form-label" for="label_attribute_name">Breite: * </label>
			<input type="text" class="form-field" name="breite" value="<? echo $this->layer_chart->get('breite'); ?>"/>
			<span data-tooltip="Die Angabe kann in % oder px erfolgen." style="
				float: right;
				position: relative;
				left: 17px;
			"></span>
			<div style="clear: both"></div>
		</div>
		<div style="clear: both"></div>
		<input type="hidden" name="go" value="layer_chart"/>
		<button type="button" onclick="show_layer_editor(event);"><? echo $strLayerChartEditorButton; ?></button>
		<button type="button" onclick="show_layer_charts(event);"><? echo $this->strCancel ?></button>
		<input id="diagram_save_button" type="submit" name="go_plus" style="margin-top: 9px;" value="Speichern"/>
	</div><?
} ?>
