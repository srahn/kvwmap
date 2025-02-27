<?php

	$GUI = $this;

	$this->outputGroup = function($group, $indent = 0) use ($GUI){	
		$group_layer_ids = $GUI->layers['layers_of_group'][$group['id']];
		$anzahl_layer = count_or_0($group_layer_ids);
		if($anzahl_layer > 0 OR $group['untergruppen'] != ''){
			echo '
						<tr>
							<td colspan="5" class="px17 fett" style="height: 30px; border-bottom:1px solid #C3C7C3;"><div style="margin-left: '.$indent.'px;">';
			if($indent > 0)echo '<img src="graphics/pfeil_unten-rechts.gif">';
			echo $group['Gruppenname'].'</div></td>
						</tr>
					';
			if($group['untergruppen'] != ''){
				foreach($group['untergruppen'] as $untergruppe){
					$GUI->outputGroup($GUI->groups[$untergruppe], $indent + 10);
				}
			}
			for($i = 0; $i < $anzahl_layer; $i++){
				$GUI->outputLayer($group_layer_ids[$i], $indent + 10);
			}
		}
	};
	
	$this->outputLayer = function($i, $indent = 0) use ($GUI) { ?>
		<tr>
			<td style="padding-left: <? echo $indent; ?>px; border-bottom: 1px solid #C3C7C3; border-left: 1px solid #C3C7C3">
				&nbsp;<? echo $GUI->layers['Bezeichnung'][$i]; ?>
			</td>
			<td style="border-bottom: 1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center">
				<input size="7" type="text" name="legendorder_layer<? echo $GUI->layers['ID'][$i]; ?>" value="<? echo $GUI->layers['legendorder'][$i]; ?>">
			</td>
			<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center">
				<a href="index.php?go=Layer2Stelle_Editor&selected_layer_id=<? echo $GUI->layers['ID'][$i]; ?>&selected_stelle_id=<? echo $GUI->formvars['selected_stelle_id']; ?>&stellen_name=<? echo $GUI->selected_stelle->Bezeichnung; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $GUI->strChange; ?></a>
			</td>
			<td style="border-bottom: 1px solid #C3C7C3; border-left: 1px solid #C3C7C3; border-right: 1px solid #C3C7C3" align="center">
				<a href="index.php?go=Layereditor&selected_layer_id=<? echo $GUI->layers['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><? echo $GUI->strChange; ?></a>
			</td>
		</tr><?
	};

  # 2007-12-30 pk
  include(LAYOUTPATH.'languages/layer2stelle_order_'.$this->user->rolle->language.'.php');
?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $strTitle.' '.$this->selected_stelle->Bezeichnung; ?></h2></td>
  </tr>
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
    	<tr>
    		<td rowspan="2" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-top:1px solid #C3C7C3" class="fett">
					&nbsp;<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&order=Name&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $this->strLayer; ?></a>
				</td>
				<td rowspan="2" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-top:1px solid #C3C7C3" class="fett">
					&nbsp;<a href="index.php?go=Layer2Stelle_Reihenfolge&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&order=ul.legendorder, l.drawingorder desc&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?php echo $strLegendOrder; ?></a>
				</td>
    		<td colspan="2" style="border-top:1px solid #C3C7C3; border-right:1px solid #C3C7C3; border-left:1px solid #C3C7C3" align="center" class="fett">
					<?php echo $strProperties; ?></td>
    	</tr>
    	<tr>
    		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3" class="fett"><?php echo $strTask; ?></td>
    		<td style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3; border-right:1px solid #C3C7C3" class="fett"><?php echo $strGlobal; ?></td>
    	</tr>
      <?
			if($this->formvars['order'] == 'ul.legendorder, l.drawingorder desc'){
				foreach($this->groups as $group){
					if($group['obergruppe'] == ''){
						$this->outputGroup($group);
					}
				}
			}
			else{
				for($i = 0; $i < count($this->layers['ID']); $i++){
					$this->outputLayer($i);
				}
			}
      ?> 
    </table>
    </td>
  </tr>

	<tr>
		<td align="center">
			<input type="hidden" id="go_plus" name="go_plus" value="">
			<input
				type="button"
				name="zurueck"
				value="<?php echo $this->strButtonBack; ?>"
				onclick="document.location.href = 'index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->formvars['selected_stelle_id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"
			>
			<input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Speichern')"><?
				if ($this->plugin_loaded('portal')) { ?>
					<input type="button" name="dummy" value="Layerdef" onclick="submitWithValue('GUI','go_plus','Layerdef')"><?
				} ?>
		</td>
	</tr>

  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="go" value="Layer2Stelle_Reihenfolge">
<input type="hidden" name="selected_stelle_id" value="<? echo $this->formvars['selected_stelle_id'];?>">
