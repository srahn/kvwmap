<?php

	$GUI = $this;
		
	$this->outputGroup = function($group, $indent = 0) use ($GUI){
		$group_layer_ids = $GUI->layers['layers_of_group'][$group['id']];
		$anzahl_layer = count($group_layer_ids);
		if($anzahl_layer > 0 OR $group['untergruppen'] != ''){
			echo '
						<tr>
							<td colspan="3" bgcolor="'.BG_GLEATTRIBUTE.'" class="px17 fett" style="height: 30px; border:1px solid #C3C7C3;"><div style="margin-left: '.$indent.'px;">';
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
	
	$this->outputLayer = function($i, $indent = 0) use ($GUI){
		echo '
      	<tr>
		        <td style="padding-left: '.$indent.'px;border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top">';
							if($GUI->layers['alias'][$i] != '')echo $GUI->layers['alias'][$i];
							else echo $GUI->layers['Bezeichnung'][$i];
		echo '	</td>
		        <td style="border-right:1px solid #C3C7C3; border-bottom:1px solid #C3C7C3" valign="top"><div style="width: 400px">'.htmlentities($GUI->layers['Kurzbeschreibung'][$i]).'</div></td>
		        <td style="border-bottom:1px solid #C3C7C3"><div style="width: 200px" valign="top">'.$GUI->layers['Datenherr'][$i].'</div></td>
		      </tr>
      	';
	};

 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td width="">
    	<table width="100%" border="0" style="border:2px solid #C3C7C3"cellspacing="0" cellpadding="3">
	      <tr>
	        <th style="border-right:1px solid #C3C7C3">Layer</th>
	        <th style="border-right:1px solid #C3C7C3">Kurzbeschreibung</th>
	        <th>Datenherr</th>
	      </tr>
	      <?
				foreach($this->groups as $group){
					if($group['obergruppe'] == ''){
						$this->outputGroup($group);
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
