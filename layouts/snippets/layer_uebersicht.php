<?php
	$GUI = $this;
	include(LAYOUTPATH . 'languages/layer_formular_' . rolle::$language . '.php'); 
	
	$this->keywords = [];
	for ($i = 0; $i < count($GUI->layers['wms_keywordlist']); $i++) {
		if ($GUI->layers['wms_keywordlist'][$i] != '') {
			$this->keywords = array_merge($this->keywords, explode(',', $GUI->layers['wms_keywordlist'][$i]));
		}
	}
	$this->keywords = array_unique($this->keywords);
	natcasesort($this->keywords);

	function compare_groups2($a, $b){
		if($a['Gruppenname'] > $b['Gruppenname'])return 1;
		else return 0;
	}
	
	$this->outputGroup = function($group, $indent = 0, $prefix = '', $upper_groups = []) use ($GUI) {
		$group_layer_ids = $GUI->layers['layers_of_group'][$group['id']];
		$anzahl_layer = count_or_0($group_layer_ids);
		$group_keywords = [];
		if ($anzahl_layer > 0 OR $group['untergruppen'] != '') {
			$upper_groups[] = $group['id'];
			if ($group['untergruppen'] != '') {
				foreach ($group['untergruppen'] as $untergruppe) {
					$subgroup = $GUI->outputGroup($GUI->groups[$untergruppe], $indent + 15, $prefix . ' ' . $group['Gruppenname'] . ' / ', $upper_groups);
					$output_groups .= $subgroup['output'];
					$group_keywords = array_merge($group_keywords, $subgroup['keywords']);
				}
			}
			for ($i = 0; $i < $anzahl_layer; $i++) {
				$layer = $GUI->outputLayer($group_layer_ids[$i], $indent + 15, $upper_groups);
				$output_layers .= $layer['output'];
				$group_keywords = array_merge($group_keywords, $layer['keywords']);
			}
			$group_keywords = array_unique($group_keywords);
			$output = '
				<tr id="group_' . $group['id'] . '" class="group_tr ' . implode(' ', $group_keywords) . '">
					<td bgcolor="' . BG_GLEATTRIBUTE . '" class="px17 fett id-column">
						<div style="margin-left: ' . $indent . 'px;"><a href="index.php?go=Layergruppe_Editor&selected_group_id=' . $group['id'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $group['id'] . '</a></div>
					</td>
					<td colspan="5" bgcolor="' . BG_GLEATTRIBUTE . '" class="px17 fett"">
						<div style="margin-left: ' . $indent . 'px;" class="layer-column">
							' . ($indent > 0 ? '<img src="graphics/pfeil_unten-rechts.gif">' : '') . ' ' . $prefix . ' ' . $group['Gruppenname'] . '
						</div>
					</td>
				</tr>
			';			
		}
		return ['output' => $output.$output_groups.$output_layers, 'keywords' => $group_keywords];
	};

	$this->outputLayer = function($i, $indent = 0, $upper_groups = []) use ($GUI) {
		$keywords = explode(',', str_replace(' ', '_', $GUI->layers['wms_keywordlist'][$i]));
		$layer_in_stelle = (array_key_exists($GUI->layers['ID'][$i], $GUI->layers_in_stelle['layer_ids']) ? 'layer_in_stelle' : '');
		$output = '
				<tr class="layer_tr ' . implode(' ', $keywords) . ' ' . $layer_in_stelle . '" data-upper_groups="' . implode(' ', $upper_groups) . '">
					<td style="padding-left: ' . $indent . 'px;" valign="top" class="id-column">
						<a href="index.php?go=Layereditor&selected_layer_id=' . $GUI->layers['ID'][$i] . '&csrf_token=' . $_SESSION['csrf_token'] . '">' . $GUI->layers['ID'][$i] . '</a>
					</td>
					<td style="padding-left: ' . $indent . 'px;" valign="top" class="layer-column">
						' . $GUI->layers['Name_or_alias'][$i] . '
					</td>
					<td style="padding-left: ' . $indent . 'px;" valign="top" class="drawingorder-column">
						' . $GUI->layers['drawingorder'][$i] . '
					</td>
					<td valign="top" class="wms_keywordlist-column">
						<div style="width: 300px">
							' . htmlentities(str_replace(',', ', ', $GUI->layers['wms_keywordlist'][$i])) . '
						</div>
					</td>
					<td valign="top" class="kurzbeschreibung-column">
						<div style="width: 400px">
							' . htmlentities($GUI->layers['Kurzbeschreibung'][$i]) . '
						</div>
					</td>
					<td valign="top" class="metadatenlink-column">
						<div style="width: 150px;text-align: center">
							<a href="' . $GUI->layers['metalink'][$i] . '" target="_blank">' . ($GUI->layers['metalink'][$i]? 'Metadaten' : '') . '</a>
						</div>
					</td>					
					<td class="dataowner_name-column">
						<div style="width: 200px" valign="top">
							' . $GUI->layers['dataowner_name'][$i] . '
						</div>
					</td>
				</tr>';
		return ['output' => $output, 'keywords' => $keywords];
	};

	include(LAYOUTPATH.'languages/userdaten_' . rolle::$language . '.php'); ?>
<script type="text/javascript">

	function Bestaetigung(link,text) {
		Check = confirm(text);
		if (Check == true) {
			window.location.href = link;
		}
	}
	
	function filter_by_keyword(){
		var filter = document.GUI.keyword_filter.value;
		var layer_trs = document.querySelectorAll('.layer_tr, .group_tr');
		[].forEach.call(layer_trs, function (layer_tr) {
			if (filter == '' || layer_tr.classList.contains(filter)) {
				layer_tr.style.display = '';
			}
			else {
				layer_tr.style.display = 'none';
			}
		});
	}

	function filter_by_full_text_search(){
		var search = document.GUI.search.value;
		var layer_trs = document.querySelectorAll('.layer_tr');
		var group_trs = document.querySelectorAll('.group_tr');
		[].forEach.call(group_trs, function (group_tr) {
			group_tr.style.display = 'none';
		});
		[].forEach.call(layer_trs, function (layer_tr) {
			if (search == '' || layer_tr.innerHTML.toLowerCase().includes(search.toLowerCase())) {
				layer_tr.style.display = '';
				let group_ids = layer_tr.dataset.upper_groups.split(' ');
				[].forEach.call(group_ids, function (group_id) {
					if (document.getElementById('group_' + group_id) != undefined) {
						document.getElementById('group_' + group_id).style.display = '';
					}
				});
			}
			else {
				layer_tr.style.display = 'none';
			}
		});
	}	
	
</script>
<style>

	#gui-table, #main{
		width: 100%
	}

<? if (!$this->Stelle->isMenueAllowed('Layer_Anzeigen')) { ?>
	.id-column {
		display: none;
	}
<? } ?>

	.drawingorder-column {
		display: none;
	}
	
	.group_tr td {
		height: 30px; 
		border:1px solid #C3C7C3;
		border-left: none;
	}
	
	.layer_tr td {
		border-right:1px solid #C3C7C3; 
		border-bottom:1px solid #C3C7C3;
	}

	.layer_in_stelle {
		background: #d5f2d0;
	}

	#column_options_button {
		float: right;
		margin-right: 5px;
		margin-top: -20px;
		margin-left: 500px;
		font-size: 20px;
	}

	#column_options_div {
		position: fixed;
		display: none;
		right: 2px;
		margin-right: 30px;
		margin-top: -20px;
		text-align: left;
		border: 1px solid gray;
		padding-right: 8px;
		background-color: white;
		border-radius: 4px;
	}
</style>
<table id="main" border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center"> 
		<td>
			<br>
			<h2><?php echo $this->titel; ?></h2>
			<br>
			<div style="text-align: left; margin-left: 5px">
				Suche: <input type="text" name="search" onkeyup="filter_by_full_text_search();">
				<span class="layer_in_stelle" style="margin: 0 50px 0 0; padding: 0 20px 0 20px; float: right;">Thema ist in aktueller Stelle enthalten</span>
			</div>
			<i id="column_options_button" class="fa fa-columns" aria-hidden="true" onclick="$('#column_options_div').toggle()"></i>
			<div id="column_options_div">
				<? if ($this->Stelle->isMenueAllowed('Layer_Anzeigen')) { ?>
				<input type="checkbox" onclick="$('.id-column').toggle(); $('#column_options_div').toggle();" checked> ID<br>
				<? } ?>
				<input type="checkbox" onclick="$('.layer-column').toggle(); $('#column_options_div').toggle();" checked> Thema<br>
				<input type="checkbox" onclick="$('.drawingorder-column').toggle(); $('#column_options_div').toggle();"> Zeichenreihenfolge<br>
				<input type="checkbox" onclick="$('.wms_keywordlist-column').toggle(); $('#column_options_div').toggle();" checked> Stichworte<br>
				<input type="checkbox" onclick="$('.kurzbeschreibung-column').toggle(); $('#column_options_div').toggle();" checked> Kurzbeschreibung<br>
				<input type="checkbox" onclick="$('.metadatenlink-column').toggle(); $('#column_options_div').toggle();" checked> Metadatenlink<br>
				<input type="checkbox" onclick="$('.dataowner_name-column').toggle(); $('#column_options_div').toggle();" checked> <? echo $strDataOwnerName; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td width="">
			<table width="100%" border="0" style="border:2px solid #C3C7C3"cellspacing="0" cellpadding="3">
				<tr>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="id-column"><a href="index.php?go=Layer_Uebersicht&order=Layer_ID">ID</a></th>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="layer-column"><a href="index.php?go=Layer_Uebersicht">Thema</a></th>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="drawingorder-column">Default<br>Zeichen-<br>reihenfolge</th>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="wms_keywordlist-column">
						Stichworte&nbsp;
						<select name="keyword_filter" onchange="filter_by_keyword();">
							<option value="">alle</option>
							<?
								foreach($this->keywords as $keyword){
									echo '<option value="' . str_replace(' ', '_', $keyword) . '">' . $keyword . '</option>';
								}
							?>
						</select>
					</th>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="kurzbeschreibung-column">Kurzbeschreibung</th>
					<th style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" class="metadatenlink-column">Metadatenlink</th>
					<th class="dataowner_name-column"><? echo $strDataOwnerName; ?></th>
				</tr><?
				if ($this->formvars['order'] == 'Layer_ID') {
					foreach ($this->layers['ID'] as $l => $layer) {
						echo $this->outputLayer($l, 10)['output'];
					}
				}
				else {
					uasort($this->groups, 'compare_groups2');
					foreach ($this->groups as $group) {
						if($group['obergruppe'] == '') {
							echo $this->outputGroup($group)['output'];
						}
					} 
				}
				?>
			</table>
		</td>
	</tr>
	<tr> 
		<td align="right">&nbsp;</td>
	</tr>
</table>
<script>
	window.addEventListener("pageshow", function() {
    filter_by_full_text_search();
});
</script>