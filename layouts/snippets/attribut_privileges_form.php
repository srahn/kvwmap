
<DIV id="TipLayer" style="visibility:hidden;position:absolute;z-index:1000;top:-100"></DIV>
<SCRIPT src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></SCRIPT>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

Text[1]=["Hilfe:","Auf dieser Seite können Sie festlegen, welche Rechte eine Stelle beim Zugriff auf einen	bestimmten Layer haben soll.<br><br> Auf Layerebene gibt es 3 verschiedene Privilegien, die Sie der Stelle zuordnen können. Die niedrigste ist 'Lesen und bearbeiten'. Mit dieser Stufe kann der Layer nur abgefragt werden. Mit der zweiten Stufe lassen sich neue Datensätze erzeugen und mit der dritten Stufe außerdem vorhandene Datensätze löschen.<br><br> Darüberhinaus können Sie der Stelle attributbezogene Rechte zuweisen. Ist ein Attribut 'nicht sichtbar', so taucht es in der Sachdatenabfrage nicht auf. Ist ein Attribut lesbar, so erscheint es in der Abfrage. Soll ein Attribut editierbar sein, so wählt man hier das Privileg 'editierbar'. Beim Geometrie-Attribut 'the_geom' gilt: Ist dieses Attribut nicht sichtbar, so kann man auch nicht von der Sachdatenanzeige in die Karte auf das Objekt zoomen. Dafür muß es mindestens lesbar sein.<br>Damit ein Attribut in der Layer-Suche als Suchoption zur Verfügung steht, muss es ebenfalls mindestens lesbar sein.<br><br>Wenn Sie keine Stelle auswählen, können Sie Default-Rechte für den Layer festlegen, die dann bei der Stellenzuweisung des Layers verwendet werden."]



function set_all(attribute_names, value){
	attribute_names = attribute_names+'';
	names = attribute_names.split('|');
	for(i = 0; i < names.length-1; i++){
		element = document.getElementsByName('privileg_'+names[i]);
		element[0].value = value;
	}
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="4"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
  	<td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="2">Stelle</td>
    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="2">Layer</td>
  </tr>
  <tr>
  	<td colspan="2" valign="top" style="border-bottom:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3">
      <select class="select" name="stelle" onchange="document.GUI.submit()">
        <option value="">------------------- Bitte wählen ----------------</option>
        <?
    		for($i = 0; $i < count($this->stellendaten['ID']); $i++){
    			echo '<option value="'.$this->stellendaten['ID'][$i].'" ';
    			if($this->formvars['stelle'] == $this->stellendaten['ID'][$i]){
    				echo 'selected';
    			}
    			echo '>'.$this->stellendaten['Bezeichnung'][$i].'</option>';
    		}
    	?>
      </select>
    </td>
    <td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3" colspan="2"> 
      <select style="width:250px" size="1" class="select" name="selected_layer_id" onchange="document.GUI.submit();">
      	<option value="">----------- Bitte wählen -----------</option>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
    		}
    		?>
      </select> 
  	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <? if($this->layer[0]['Name'] != ''){ ?>
  <tr>
  	<td colspan="4" width="100%">
  		<table width="100%">
  			<tr>
  				<td>
  					<img src="<?php echo GRAPHICSPATH;?>ikon_i.gif" onMouseOver="stm(Text[1],Style[0])" onmouseout="htm()">
  				</td>
  				<? if($this->formvars['stelle'] != '' AND $this->layer[0]['Name'] != ''){ ?>
			  	<td align="center"><b><span style="font-size:15px">Rechte der Stelle <? echo $this->stelle->Bezeichnung; ?> im Layer <? echo $this->layer[0]['Name']?></span></b></td>
			  	<? }elseif($this->layer[0]['Name'] != ''){ ?>
			  	<td align="center"><b><span style="font-size:15px">Default-Rechte im Layer <? echo $this->layer[0]['Name']?></span></b></td>
			  	<? } ?>
  			</tr>
  		</table>
  	</td>
  </tr>
  <tr>
  	<td colspan="4">
    	<table align="center" border="0" cellspacing="2" cellpadding="2">
    		<tr>
			  	<td align="center"><b>Layerzugriffsrechte</b></td>
			  </tr>
			  <tr>
			  	<td>
			  		<select name="privileg">
			  			<option <? if($this->layer[0]['privileg'] == '0'){echo 'selected';} ?> value="0">lesen und bearbeiten</option>
			  			<option <? if($this->layer[0]['privileg'] == '1'){echo 'selected';} ?> value="1">neue Datensätze erzeugen</option>
			  			<option <? if($this->layer[0]['privileg'] == '2'){echo 'selected';} ?> value="2">Datensätze erzeugen und löschen</option>
			  		</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<? } ?>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4">
    	<table align="center" border="0" cellspacing="0" cellpadding="0">
        <?
		if ($this->layer[0]['Name'] != '' AND count($this->attributes) != 0) {
			echo '
					<tr>
						<td align="center">
							<b>Attribut</b>
						</td>
						<td>&nbsp;</td>
						<td align="center">
							<b>Privileg</b>
						</td>
						<td>&nbsp;</td>
						<td align="center">
							<b>Tooltip</b>
						</td>
					</tr>
			';
			if($this->formvars['stelle'] != '' AND $this->attributes_privileges == NULL){				# zu diesem Layer und Stelle gibt es keinen Eintrag -> alle Attribute sind lesbar
				$noentry = true;
			}
			else{
				$noentry = false;
			}
    	for($i = 0; $i < count($this->attributes['type']); $i++){
    		if($this->formvars['stelle'] == '')$this->attributes_privileges[$this->attributes['name'][$i]] = $this->attributes['privileg'][$i]; 	# die default-Rechte kommen aus layer_attributes
    		$attribute_names .= $this->attributes['name'][$i].'|';
				echo '
				<tr>
				  <td align="center">
				  	<input class="input" type="text" name="attribute_'.$this->attributes['name'][$i].'" value="'.$this->attributes['name'][$i].'" readonly>
				  </td>
				  <td>&nbsp;</td>
				  <td align="center">
				  	<select class="select" style="width:130px" name="privileg_'.$this->attributes['name'][$i].'">';
				  		echo '
				  		<option value="" ';
				  		if($this->attributes_privileges[$this->attributes['name'][$i]] == '' AND !$noentry){echo 'selected';}
				  		echo ' >nicht sichtbar</option>
				  		<option value="0" ';
				  		if($this->attributes_privileges[$this->attributes['name'][$i]] == '0' OR $noentry){echo 'selected';}
				  		echo ' >lesen</option>
				  		<option value="1" ';
				  		if($this->attributes_privileges[$this->attributes['name'][$i]] == 1 AND !$noentry){echo 'selected';}
				  		echo ' >editieren</option>
				  	</select>
				  </td>
				  <td>&nbsp;</td>
				  <td align="center"><input type="checkbox" name="tooltip_'.$this->attributes['name'][$i].'" ';
				  if($this->attributes_privileges['tooltip_'.$this->attributes['name'][$i]] == 1){
				  	echo 'checked';
				  }
					echo ' ></td>
        </tr>
        ';
    	}
    	echo '
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
				  <td align="center">
				  	<input class="input" type="text" name="" value="alle" readonly>
				  </td>
				  <td>&nbsp;</td>
				  <td align="center">
				  	<select class="select" style="width:130px" name="" onchange="set_all(\''.$attribute_names.'\', this.value);"">
				  		<option value="">nicht sichtbar</option>
				  		<option value="0">lesen</option>
				  		<option value="1">editieren</option>
				  	</select>
				  </td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
        </tr>
        ';
			if(count($this->attributes) > 0){
				echo '
				<!--			<tr>
			        	<td colspan="5" align="right">
			        		<a href="javascript:set_all(\''.$attribute_names.'\');">alle</a>
			        	</td>
							</tr>-->
							<tr>
			 					<td align="center" colspan="4"><br><br><input class="button" type="submit" name="go_plus" value="speichern">
			 					</td>
			 				</tr>';
			}
		} 
			?>
      </table></td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" >&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="Layerattribut-Rechteverwaltung">


