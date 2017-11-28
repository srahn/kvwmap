<script src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

var counter = 0;

Text[0]=["Hilfe:","In Freitexten können folgende Schlüsselwörter verwendet werden, die dann durch andere Texte ersetzt werden:<ul><li>$stelle: die aktuelle Stellenbezeichung</li><li>$user: der Name des Nutzers</li><li>$pagenumber: die aktuelle Seitennummer<br>(Platzierung \"auf jeder Seite\" erforderlich)</li><li>$pagecount: die Gesamtseitenzahl<br>(Platzierung \"auf jeder Seite\" erforderlich)</li><li>${<i>&lt;attributname&gt;</i>}: der Wert des Attributs</li></ul>"]
Text[1]=["Hilfe:","Hier kann der Name der erzeugten PDF-Datei angegeben werden. Im Dateinamen können auch Attribute in der Form ${<i>&lt;attributname&gt;</i>} verwendet werden, wodurch der Dateiname dynamisch wird. Wird kein Dateiname angegeben, erhält die PDF-Datei einen automatisch generierten Namen."]

function image_coords(event){
	document.getElementById('coords').style.visibility='';
	var pointer_div = document.getElementById("preview_div");
	if(window.ActiveXObject){		//for IE
		pos_x = window.event.offsetX;
		pos_y = window.event.offsetY;
	}
	else{	//for Firefox
		var top = 0, left = 0;
		var elm = pointer_div;
		while(elm){
			left += elm.offsetLeft;
			top += elm.offsetTop;
			elm = elm.offsetParent;
		}
		pos_x = event.pageX - left;
		pos_y = event.pageY - top;
	}
	document.getElementById("coords").style.left = pos_x+7;
	document.getElementById("coords").style.top = pos_y+7;
	document.getElementById("posx").value = pos_x;
	document.getElementById("posy").value = 842-pos_y;
}


function updateheight(imagewidth, imageheight){
	ratio = imageheight/imagewidth;
	document.GUI.headheight.value = Math.round(document.GUI.headwidth.value * ratio); 
}

function updatewidth(imagewidth, imageheight){
	ratio = imagewidth/imageheight;
	document.GUI.headwidth.value = Math.round(document.GUI.headheight.value * ratio); 
}

function update_options(){
	if(document.GUI.type.value > 0)document.getElementById('list_type_options').style.display = '';
	else document.getElementById('list_type_options').style.display = 'none';
}

function addfreetext(){
	document.GUI.go.value = 'sachdaten_druck_editor_Freitexthinzufuegen';
	document.GUI.submit();
}

function addline(){
	document.GUI.go.value = 'sachdaten_druck_editor_Liniehinzufuegen';
	document.GUI.submit();
}

function toggle(attribute){
	if(document.getElementById('tr1_'+attribute).style.display == 'none'){
		document.getElementById('tr1_'+attribute).style.display = '';
		document.getElementById('tr2_'+attribute).style.display = '';
		document.getElementById('img_'+attribute).src = '<? echo GRAPHICSPATH; ?>minus.gif';
		if(document.getElementsByName('posx_'+attribute)[0].value == ''){
			document.getElementsByName('posx_'+attribute)[0].value = 70;
		}
		if(document.getElementsByName('posy_'+attribute)[0].value == ''){
			document.getElementsByName('posy_'+attribute)[0].value = 750-counter*20;
			counter++;
		}
		if(document.getElementsByName('fontsize_'+attribute)[0].value == ''){
			document.getElementsByName('fontsize_'+attribute)[0].value = 13;
		}
	}
	else{
		document.getElementById('tr1_'+attribute).style.display = 'none';
		document.getElementById('tr2_'+attribute).style.display = 'none';
		document.getElementById('img_'+attribute).src = '<? echo GRAPHICSPATH; ?>plus.gif';
	}
}

function save_layout(){
	if(document.GUI.name.value == ''){
		alert('Bitte geben Sie einen Namen für das Layout ein.');
	}
	else{
		check = true;
		for(i = 1; i < document.GUI.aktivesLayout.options.length; i++){
			if(document.GUI.aktivesLayout.options[i].text == document.GUI.name.value){
				check = confirm('Es existiert bereits ein Layout mit diesem Namen. Wollen Sie wirklich ein neues Layout anlegen?');
			}
		}
		if(check){
			document.GUI.go.value = 'sachdaten_druck_editor_als neues Layout speichern';
			document.GUI.submit();
		}
	}
}
  
//-->
</script>

<br>
<input type="hidden" name="go" value="sachdaten_druck_editor">

<h2><?php echo $this->titel; ?></h2>

<?php 
	if ($this->ddl->fehlermeldung != '') {
  echo "<script type=\"text/javascript\">
      <!--
        alert('".$this->ddl->fehlermeldung."');
      //-->
      </SCRIPT>"
  ;
}

?>       
<table border="0" cellspacing="2" cellpadding="0">
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td >
      <table width="597" cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
      	<tr>
          <td class="fett" colspan=3 style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3">&nbsp;Themen-Auswahl</td>
        </tr>
      	<tr>
		      <td colspan="5"> 
			      <select style="width:250px" size="1"  name="selected_layer_id" onchange="if(document.GUI.aktivesLayout != undefined)document.GUI.aktivesLayout.value='';document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
			      <option value="">--- bitte wählen ---</option>
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
			</table>
		</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
<? if($this->formvars['selected_layer_id']){ ?> 
	<tr>
		<td colspan=3>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>
			 <table width="597" cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
        <tr>
          <td class="fett" colspan=2 style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3">&nbsp;Layout-Auswahl</td>
          <td class="fett" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;Stelle</td>
        </tr>
        <tr>
          <td colspan=1>
            &nbsp;<select  name="aktivesLayout" onchange="document.GUI.submit()">
            <option value="">--- bitte wählen ---</option>
            <?  
            for($i = 0; $i < count($this->ddl->layouts); $i++){
              echo ($this->formvars['aktivesLayout']<>$this->ddl->layouts[$i]['id']) ? '<option value="'.$this->ddl->layouts[$i]['id'].'">'.$this->ddl->layouts[$i]['name'].'</option>' : '<option value="'.$this->ddl->layouts[$i]['id'].'" selected>'.$this->ddl->layouts[$i]['name'].'</option>';
            }
            ?>
          </select> 
          </td>
          <td>
            <input class="button" type="submit" name="go_plus" value="übernehmen >>">
          </td>
          <td style="border-left:1px solid #C3C7C3">
          	&nbsp;<select  name="stelle">
          	<option value="">--- bitte wählen ---</option>
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
        </tr>
      </table> 
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr> 
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
        <tr>
          <td class="fett" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3" colspan=8 >&nbsp;Layoutdaten</td>
        </tr>
        <tr>
          <td style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<span class="fett">Name:</span> 
					</td>
					<td colspan=7 style="border-bottom:1px solid #C3C7C3">
          	<input type="text" name="name" value="<? echo $this->ddl->selectedlayout[0]['name'] ?>" size="35">
          	&nbsp;&nbsp;&nbsp;<span class="fett">Typ:</span> 
          	<select name="type" onchange="update_options();">
          		<option value="0" <? if($this->ddl->selectedlayout[0]['type'] == 0)echo 'selected' ?>>neue Seite für jeden Datensatz</option>
          		<option value="1" <? if($this->ddl->selectedlayout[0]['type'] == 1)echo 'selected' ?>>Datensätze fortlaufend</option>
							<option value="2" <? if($this->ddl->selectedlayout[0]['type'] == 2)echo 'selected' ?>>eingebettet</option>
          	</select>
					</td>
				</tr>
        <tr>
          <td style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<span class="fett">Dateiname:</span>
					</td>
					<td colspan=7 style="border-bottom:1px solid #C3C7C3">
          	<input type="text" name="filename" value="<? echo $this->ddl->selectedlayout[0]['filename'] ?>" size="35">
						<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[1], Style[0], document.getElementById('Tip2'))" onmouseout="htm()">
						<div style="position:relative">
							<div id="Tip2" style="visibility:hidden;position:absolute;bottom:20px;z-index:1000;"></div>
						</div>
					</td>
				</tr>				
				<tr id="list_type_options" style="display:<? if($this->ddl->selectedlayout[0]['type'] == 0)echo 'none' ?>">
          <td colspan=8 style="border-bottom:1px solid #C3C7C3">
						&nbsp;<span class="fett">Datensätze:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fett">Abstand:</span>
						<input type="text" name="gap" title="Der Abstand zwischen den Datensätzen." value="<? echo $this->ddl->selectedlayout[0]['gap'] ?>" size="2">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fett">nicht durch Seitenumbruch unterbrechen:</span>
						<input type="checkbox" name="no_record_splitting" title="Wenn angehakt, wird ein Seitenumbruch nicht innerhalb eines Datensatzes gemacht, sondern davor." value="1" <? if($this->ddl->selectedlayout[0]['no_record_splitting']) echo 'checked'; ?>>
					</td>
        </tr>
      </table>
      <table border="0" width="597" cellspacing="0" cellpadding="0">
      	<tr>
        	<td colspan=8 align="center">
        		<? if($this->previewfile){ ?>
        			<div id="preview_div" align="left" onmouseout="document.getElementById('coords').style.visibility='hidden';" onmousemove="image_coords(event)" style="border:1px solid black;width:595px;height:842px;background-image:url('<? echo $this->previewfile; ?>');">
        				<div id="coords" style="background-color: white;width:65px;visibility: hidden;position:relative;border: 1px solid black">
        					&nbsp;x:&nbsp;<input type="text" id="posx" size="2" style="border:none"><br>
        					&nbsp;y:&nbsp;<input type="text" id="posy" size="2" style="border:none">
        				</div>
        			</div>
        		<? } ?>
					</td>
        </tr>
      </table>
			<br>
			<table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
				<tr>
        	<td class="fett" align="center" style="border-bottom:2px solid #C3C7C3;border-top:2px solid #C3C7C3" colspan="8">&nbsp;Grafik&nbsp;</td>
        </tr>
        <tr>
        	<td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;<? echo $this->ddl->selectedlayout[0]['bgsrc'] ?></td>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="bgposx" value="<? echo $this->ddl->selectedlayout[0]['bgposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td><input type="text" name="bgwidth" value="<? echo $this->ddl->selectedlayout[0]['bgwidth'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4><input class="button" type="file" name="bgsrc" size="10"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="bgposy" value="<? echo $this->ddl->selectedlayout[0]['bgposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input type="text" name="bgheight" value="<? echo $this->ddl->selectedlayout[0]['bgheight'] ?>" size="5"></td>
        </tr>
			</table>
			<br>
      <table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
  			<tr>
          <td align="center" style="border-top:2px solid #C3C7C3" colspan=8><span class="fett">&nbsp;Attribute</span></td>
        </tr>
        
 <? if($this->formvars['selected_layer_id'] != ''){
    	for($i = 0; $i < count($this->attributes['type']); $i++){
    		if($this->attributes['type'][$i] != 'geometry'){
    			if($this->attributes['alias'][$i] == '')$this->attributes['alias'][$i] = $this->attributes['name'][$i]; ?>
					<tr>
						<td class="fett" align="left" style="border-top:2px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->attributes['name'][$i]; ?>');">
						<? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){ ?>
							<img id="img_<? echo $this->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;<? echo $this->attributes['alias'][$i].' ($'.$this->attributes['name'][$i].')'; ?>
						<? }else{ ?>
							<img id="img_<? echo $this->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;<? echo $this->attributes['alias'][$i].' ($'.$this->attributes['name'][$i].')'; ?>
						<? } ?>
						</td>
					</tr>		
<?				switch ($this->attributes['form_element_type'][$i]){ 
						case 'SubFormPK' : case 'SubFormEmbeddedPK' : {
							$subformlayouts = $this->ddl->load_layouts(NULL, NULL, $this->attributes['subform_layer_id'][$i], array(2));
						?>
							<tr id="tr1_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td style="border-top:1px solid #C3C7C3">&nbsp;&nbsp;&nbsp;x:</td>
								<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="posx_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos']; ?>" size="5"></td>
								<td style="border-top:1px solid #C3C7C3" align="left" colspan="5" align="center">
									&nbsp;Druckrahmen:&nbsp;
									<select title="Druckrahmen" name="font_<? echo $this->attributes['name'][$i]; ?>">
										<option value=""> - Bitte wählen - </option>
										<?																																											# die font-Spalte wird hier zum Speichern des eingebetteten Layouts genutzt
										for($j = 0; $j < count($subformlayouts); $j++){
											echo '<option ';
											if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['font'] == $subformlayouts[$j]['id']){
												echo 'selected ';
											}
											echo 'value="'.$subformlayouts[$j]['id'].'">'.$subformlayouts[$j]['name'].'</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr id="tr2_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td>&nbsp;&nbsp;&nbsp;y:</td>
								<td style="border-right:1px solid #C3C7C3"><input type="text" name="posy_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['ypos']; ?>" size="5"></td>							
								<td width="60px">&nbsp;unterhalb&nbsp;von:</td>
								<td>
									<select name="offset_attribute_<? echo $this->attributes['name'][$i]; ?>">
										<option value="">- Auswahl -</option>
										<?
										for($j = 0; $j < count($this->attributes['name']); $j++){
											if($this->attributes['name'][$j] != $this->attributes['name'][$i]){
												echo '<option ';
												if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['offset_attribute'] == $this->attributes['name'][$j]){
													echo 'selected ';
												}
												echo 'value="'.$this->attributes['name'][$j].'">'.$this->attributes['name'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
	 <?				} break;
	 
						case 'Dokument' : { ?>
							<tr id="tr1_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td>&nbsp;&nbsp;&nbsp;x:</td>
								<td><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos']; ?>" size="5"></td>
								<td width="60px">&nbsp;Breite:</td>
								<td><input  type="text" name="width_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['width']; ?>" size="5"></td>
								<td colspan="3"></td>
							</tr>
							<tr id="tr2_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td>&nbsp;&nbsp;&nbsp;y:</td>
								<td><input type="text" name="posy_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['ypos']; ?>" size="5"></td>
								<td colspan="5">&nbsp;</td>
							</tr>	
			<?			}break;
					
						default : {	?>
							<tr id="tr1_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td style="border-top:1px solid #C3C7C3">&nbsp;x:</td>
								<td style="border-top:1px solid #C3C7C3"><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos']; ?>" size="5"></td>
								<td style="border-top:1px solid #C3C7C3" width="60px">&nbsp;Breite:</td>
								<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><input  type="text" name="width_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['width']; ?>" size="5"></td>
								<td style="border-top:1px solid #C3C7C3" align="left" colspan="2" align="center">
									<?php echo output_select(
										'font_' . $this->attributes['name'][$i],
										$this->ddl->fonts,
										$this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['font'],
										null,
										'Schriftart',
										' - Bitte wählen - '
									); ?>
								</td>
								<td style="border-top:1px solid #C3C7C3" align="left" align="center"><input type="text" title="Schriftgröße" name="fontsize_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['fontsize']; ?>" size="2">&nbsp;pt</td>
							</tr>
							<tr id="tr2_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
								<td>&nbsp;y:</td>
								<td><input type="text" name="posy_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['ypos']; ?>" size="5"></td>
								<td width="60px">&nbsp;unterhalb&nbsp;von:</td>
								<td style="border-right:1px solid #C3C7C3">
									<select name="offset_attribute_<? echo $this->attributes['name'][$i]; ?>">
										<option value="">- Auswahl -</option>
										<?
										for($j = 0; $j < count($this->attributes['name']); $j++){
											if($this->attributes['name'][$j] != $this->attributes['name'][$i]){
												echo '<option ';
												if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['offset_attribute'] == $this->attributes['name'][$j]){
													echo 'selected ';
												}
												echo 'value="'.$this->attributes['name'][$j].'">'.$this->attributes['name'][$j].'</option>';
											}
										}
										?>
									</select>
								</td>
								<td>&nbsp;Rahmen:</td>
								<td><input type="checkbox" name="border_<? echo $this->attributes['name'][$i]; ?>" value="1" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['border'] == '1'){echo 'checked="true"';} ?> size="5"></td>
							</tr>
	 <?				}
					}
				}	
    	}
    	if($this->attributes['the_geom'] != ''){ ?>
    		<tr>
        	<td class="fett" align="left" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->attributes['the_geom']; ?>');">
        	<? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){ ?>
        		<img id="img_<? echo $this->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;Geometrie
        	<? }else{ ?>
        		<img id="img_<? echo $this->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;Geometrie
        	<? } ?>
        	</td>
        </tr>
        <tr id="tr1_<? echo $this->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;x:</td>
        	<td><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos']; ?>" size="5"></td>
					<td width="60px">&nbsp;Breite:</td>
					<td><input  type="text" name="width_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['width']; ?>" size="5"></td>
					<td colspan="3"></td>
        </tr>
        <tr id="tr2_<? echo $this->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;y:</td>
        	<td><input type="text" name="posy_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['ypos']; ?>" size="5"></td>
        	<td width="60px">&nbsp;Rand:</td>
					<td><input  type="text" name="fontsize_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['fontsize']; ?>" size="5">m</td>
					<td colspan="3"></td>
        </tr>	
<?   	}
 		} ?>
      </table>
			<br>
			<table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">  
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Datum&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Nutzer&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposx" value="<? echo $this->ddl->selectedlayout[0]['dateposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
						<?php echo output_select(
							'font_date',
							$this->ddl->fonts,
							$this->ddl->selectedlayout[0]['font_date'],
							null,
							'Schriftart',
							'--- bitte wählen ---'
						); ?>
        	</td>
        	<td width="100px" style="border-right:1px solid #C3C7C3">
        		&nbsp;x:&nbsp;<input type="text" name="userposx" value="<? echo $this->ddl->selectedlayout[0]['userposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
						<?php echo output_select(
							'font_user',
							$this->ddl->fonts,
							$this->ddl->selectedlayout[0]['font_user'],
							null,
							'Schriftnutzer',
							'--- bitte wählen ---'
						); ?>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposy" value="<? echo $this->ddl->selectedlayout[0]['dateposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" align="center" colspan="2"><input type="text" name="datesize" value="<? echo $this->ddl->selectedlayout[0]['datesize'] ?>" size="5">&nbsp;pt</td>
        	<td style="border-right:1px solid #C3C7C3">
        		&nbsp;y:&nbsp;<input type="text" name="userposy" value="<? echo $this->ddl->selectedlayout[0]['userposy'] ?>" size="5"></td>
        	<td align="center" colspan="2"><input type="text" title="Schriftgröße" name="usersize" value="<? echo $this->ddl->selectedlayout[0]['usersize'] ?>" size="5">&nbsp;pt</td>
        </tr>
			</table>
			<br>
			<table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
        <tr>
          <td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">
						Freitexte
						<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[0], Style[0], document.getElementById('Tip1'))" onmouseout="htm()">
						<div style="position:relative">
							<div id="Tip1" style="visibility:hidden;position:absolute;bottom:20px;z-index:1000;"></div>
						</div>
					</td>
        </tr>
        <? for($i = 0; $i < count($this->ddl->selectedlayout[0]['texts']); $i++){
        		$this->ddl->selectedlayout[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->ddl->selectedlayout[0]['texts'][$i]['text']);
        	 ?>
	        <tr>
	        	<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
	        	<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="textposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['posx'] ?>" size="5"></td>	        	
	        	<td rowspan="4" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=3>
	        		<textarea name="text<? echo $i ?>" cols="37" rows="6"><? echo $this->ddl->selectedlayout[0]['texts'][$i]['text'] ?></textarea>
	        	</td>
	        	<td style="border-top:2px solid #C3C7C3;" colspan=2 align="left">
							<?php echo output_select(
								'textfont' . $i,
								$this->ddl->fonts,
								$this->ddl->selectedlayout[0]['texts'][$i]['font'],
								null,
								'Schriftart',
								'--- bitte wählen ---'
							); ?>
	        	</td>
	        </tr>
	        <tr>
	        	<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="textposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['posy'] ?>" size="5"><input type="hidden" name="text_id<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['id'] ?>"></td>
	        	<td colspan="2"><input type="text" title="Schriftgröße" name="textsize<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['size'] ?>" size="5">&nbsp;pt</td>
	        </tr>
	       	<tr>
	       		<td colspan="2" valign="bottom" style="border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
	       		<td colspan="2" valign="bottom">&nbsp;Platzierung:</td>
	        </tr>
	        <tr>
						<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
							<select name="textoffset_attribute<? echo $i ?>" style="width: 100px">
								<option value="">- Auswahl -</option>
								<?
								for($j = 0; $j < count($this->attributes['name']); $j++){
									echo '<option ';
									if($this->ddl->selectedlayout[0]['texts'][$i]['offset_attribute'] == $this->attributes['name'][$j]){
										echo 'selected ';
									}
									echo 'value="'.$this->attributes['name'][$j].'">'.$this->attributes['name'][$j].'</option>';
								}
								?>
							</select>
						</td>
	        	<td align="left" valign="top">
							<select style="width: 110px" name="texttype<? echo $i ?>">
								<option value="0">normal</option>
								<? if($this->ddl->selectedlayout[0]['type'] != 0){ ?>
								<option value="1" <? if($this->ddl->selectedlayout[0]['texts'][$i]['type'] == 1)echo ' selected '; ?>>fixiert</option>
								<? } ?>
								<option value="2" <? if($this->ddl->selectedlayout[0]['texts'][$i]['type'] == 2)echo ' selected '; ?>>auf jeder Seite</option>
							</select>
						</td>
						<td align="right">
							<a href="javascript:Bestaetigung('index.php?go=sachdaten_druck_editor_Freitextloeschen&freitext_id=<? echo $this->ddl->selectedlayout[0]['texts'][$i]['id'] ?>&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&aktivesLayout=<? echo $this->formvars['aktivesLayout']; ?>', 'Wollen Sie den Freitext wirklich löschen?');">löschen&nbsp;</a>
						</td>
	        </tr>
	      <? } ?>
	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;<a href="javascript:addfreetext();">Freitext hinzufügen</a></td>
        </tr>        
      </table>
			<br>
			<table width="597" border=0 cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3">
        <tr>
          <td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">
						Linien
					</td>
        </tr>
        <? for($i = 0; $i < count($this->ddl->selectedlayout[0]['lines']); $i++){
        	 ?>
					<tr>
						<td colspan="2" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">Start<input type="hidden" name="line_id<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id'] ?>"></td>
						<td colspan="2" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">Ende</td>
						<td colspan="2" style="border-top:2px solid #C3C7C3"></td>
					</tr>
	        <tr>
	        	<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
	        	<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="lineposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['posx'] ?>" size="5"></td>
						<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
	        	<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="lineendposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['endposx'] ?>" size="5"></td>
						<td>Breite:&nbsp;<input type="text" name="breite<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['breite'] ?>" size="5"></td>
	        </tr>
	        <tr>
	        	<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="lineposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['posy'] ?>" size="5"></td>
						<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="lineendposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['endposy'] ?>" size="5"></td>
	        </tr>
	       	<tr>
	       		<td colspan="4" valign="bottom" style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
	       		<td colspan="2" valign="bottom">&nbsp;Platzierung:</td>
	        </tr>
	        <tr>
						<td colspan="4" valign="top" style="border-right:1px solid #C3C7C3">
							<select name="lineoffset_attribute<? echo $i ?>" style="width: 200px">
								<option value="">- Auswahl -</option>
								<?
								for($j = 0; $j < count($this->attributes['name']); $j++){
									echo '<option ';
									if($this->ddl->selectedlayout[0]['lines'][$i]['offset_attribute'] == $this->attributes['name'][$j]){
										echo 'selected ';
									}
									echo 'value="'.$this->attributes['name'][$j].'">'.$this->attributes['name'][$j].'</option>';
								}
								?>
							</select>
						</td>
	        	<td align="left" valign="top">
							<select style="width: 110px" name="linetype<? echo $i ?>">
								<option value="0">normal</option>
								<? if($this->ddl->selectedlayout[0]['type'] != 0){ ?>
								<option value="1" <? if($this->ddl->selectedlayout[0]['lines'][$i]['type'] == 1)echo ' selected '; ?>>fixiert</option>
								<? } ?>
								<option value="2" <? if($this->ddl->selectedlayout[0]['lines'][$i]['type'] == 2)echo ' selected '; ?>>auf jeder Seite</option>
							</select>
						</td>
						<td align="right">
							<a href="javascript:Bestaetigung('index.php?go=sachdaten_druck_editor_Linieloeschen&line_id=<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id'] ?>&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&aktivesLayout=<? echo $this->formvars['aktivesLayout']; ?>', 'Wollen Sie die Linie wirklich löschen?');">löschen&nbsp;</a>
						</td>
	        </tr>
	      <? } ?>
	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;<a href="javascript:addline();">Linie hinzufügen</a></td>
        </tr>        
      </table>			
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>  
  
  <tr align="center"> 
    <td colspan="3"> 
    <input class="button" type="button" name="go_plus" value="Layout löschen" onclick="Bestaetigung('index.php?go=sachdaten_druck_editor_Löschen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&selected_layout_id=<? echo $this->ddl->selectedlayout[0]['id']; ?>', 'Wollen Sie dieses Layout wirklich löschen?');">&nbsp;
    <input class="button" type="submit" name="go_plus" value="Änderungen Speichern">&nbsp;
    <input class="button" type="button" name="go_plus" onclick="save_layout();" value="als neues Layout speichern">
    </td>
  </tr>
<? } ?>  
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="textcount" value="<? echo count($this->ddl->selectedlayout[0]['texts']); ?>">
<input type="hidden" name="linecount" value="<? echo count($this->ddl->selectedlayout[0]['lines']); ?>">
<input type="hidden" name="bgsrc_save" value="<? echo $this->ddl->selectedlayout[0]['bgsrc'] ?>">


