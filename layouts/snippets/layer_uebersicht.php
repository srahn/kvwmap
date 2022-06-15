<?php
	$GUI = $this;
	include(LAYOUTPATH . 'languages/layer_formular_' . $this->user->rolle->language . '.php'); 
	$this->outputGroup = function($group, $indent = 0) use ($GUI) {
		$group_layer_ids = $GUI->layers['layers_of_group'][$group['id']];
		$anzahl_layer = @count($group_layer_ids);
		if ($anzahl_layer > 0 OR $group['untergruppen'] != '') {
			$output = '
				<tr>
					<td bgcolor="' . BG_GLEATTRIBUTE . '" class="px17 fett id-column" style="height: 30px; border:1px solid #C3C7C3;" class="id-column">
						<div style="margin-left: ' . $indent . 'px;"><a href="index.php?go=Layergruppe_Editor&selected_group_id=' . $group['id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $group['id'] . '</a></div>
					</td>
					<td colspan="4" bgcolor="' . BG_GLEATTRIBUTE . '" class="px17 fett" style="height: 30px; border:1px solid #C3C7C3;">
						<div style="margin-left: ' . $indent . 'px;" class="layer-column">
							' . ($indent > 0 ? '<img src="graphics/pfeil_unten-rechts.gif">' : '') . ' ' . $group['Gruppenname'] . '
						</div>
					</td>
				</tr>
			';
			if ($group['untergruppen'] != '') {
				foreach ($group['untergruppen'] as $untergruppe) {
					$output_groups .= $GUI->outputGroup($GUI->groups[$untergruppe], $indent + 10);
				}
			}
			for ($i = 0; $i < $anzahl_layer; $i++) {
				$output_layers .= $GUI->outputLayer($group_layer_ids[$i], $indent + 10);
			}
		}
		if ($output_layers != '' OR $output_groups) {
			return $output.$output_groups.$output_layers;
		}
	};

	$this->outputLayer = function($i, $indent = 0) use ($GUI) {
		$output = '
				<tr>
					<td style="padding-left: ' . $indent . 'px;border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top" class="id-column">
						<a href="index.php?go=Layereditor&selected_layer_id=' . $GUI->layers['ID'][$i] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $GUI->layers['ID'][$i] . '</a>
					</td>
					<td style="padding-left: ' . $indent . 'px;border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top" class="layer-column">
						' . ($GUI->layers['alias'][$i] != '' ? $GUI->layers['alias'][$i] : $GUI->layers['Bezeichnung'][$i]) . '
					</td>
					<td style="padding-left: ' . $indent . 'px;border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top" class="default_drawingorder-column">
						' . $GUI->layers['default_drawingorder'][$i] . '
					</td>
					<td style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top" class="kurzbeschreibung-column">
						<div style="width: 400px">
							' . htmlentities($GUI->layers['Kurzbeschreibung'][$i]) . '
						</div>
					</td>
					<td style="border-bottom:1px solid #C3C7C3" class="dataowner_name-column">
						<div style="width: 200px" valign="top">
							' . $GUI->layers['dataowner_name'][$i] . '
						</div>
					</td>
				</tr>';
		return $output;
	};

	include(LAYOUTPATH.'languages/userdaten_' . $this->user->rolle->language . '.php'); ?>
<script type="text/javascript">
	function Bestaetigung(link,text) {
		Check = confirm(text);
		if (Check == true) {
			window.location.href = link;
		}
	}
</script>
<style>
	.id-column, .default_drawingorder-column {
		display: none;
	}

	#column_options_button {
		float: right;
		margin-right: 5px;
		margin-top: -20px;
		margin-left: 500px;
		font-size: 20px;
	}

	#column_options_div {
		display: none;
		float: right;
		margin-right: 30px;
		margin-top: -20px;
		text-align: left;
		border: 1px solid gray;
		padding-right: 8px;
		background-color: white;
		border-radius: 4px;
	}
</style>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td>
			<h2><?php echo $this->titel; ?></h2>
			<i id="column_options_button" class="fa fa-columns" aria-hidden="true" onclick="$('#column_options_div').toggle()"></i>
			<div id="column_options_div">
				<input type="checkbox" onclick="$('.id-column').toggle(); $('#column_options_div').toggle();"> ID<br>
				<input type="checkbox" onclick="$('.layer-column').toggle(); $('#column_options_div').toggle();" checked> Layer<br>
				<input type="checkbox" onclick="$('.default_drawingorder-column').toggle(); $('#column_options_div').toggle();"> Zeichenreihenfolge<br>
				<input type="checkbox" onclick="$('.kurzbeschreibung-column').toggle(); $('#column_options_div').toggle();" checked> Kurzbeschreibung<br>
				<input type="checkbox" onclick="$('.dataowner_name-column').toggle(); $('#column_options_div').toggle();" checked> <? echo $strDataOwnerName; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td width="">
			<table width="100%" border="0" style="border:2px solid #C3C7C3"cellspacing="0" cellpadding="3">
				<tr>
					<th style="border-right:1px solid #C3C7C3" class="id-column">ID</th>
					<th style="border-right:1px solid #C3C7C3" class="layer-column">Layer</th>
					<th style="border-right:1px solid #C3C7C3" class="default_drawingorder-column">Default<br>Zeichen-<br>reihenfolge</th>
					<th style="border-right:1px solid #C3C7C3" class="kurzbeschreibung-column">Kurzbeschreibung</th>
					<th class="dataowner_name-column"><? echo $strDataOwnerName; ?></th>
				</tr><?
				foreach ($this->groups as $group) {
					if($group['obergruppe'] == '') {
						echo $this->outputGroup($group);
					}
				} ?>
			</table>
		</td>
	</tr>
	<tr> 
		<td align="right">&nbsp;</td>
	</tr>
</table>