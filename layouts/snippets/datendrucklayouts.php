
<script type="text/javascript">
<!--

var counter = 0;

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

function addfreetext(){
	document.GUI.go.value = 'sachdaten_druck_editor_Freitexthinzufuegen';
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
      <table width=100% cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
      	<tr>
          <td class="bold" colspan=3 style="border-bottom:1px solid #C3C7C3">&nbsp;Themen-Auswahl</td>
        </tr>
      	<tr>
		      <td colspan="5"> 
			      <select style="width:250px" size="1" class="select" name="selected_layer_id" onchange="if(document.GUI.aktivesLayout != undefined)document.GUI.aktivesLayout.value='';document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
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
			 <table width=100% cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr>
          <td class="bold" colspan=2 style="border-bottom:1px solid #C3C7C3">&nbsp;Layout-Auswahl</td>
          <td class="bold" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;Stelle</td>
        </tr>
        <tr>
          <td colspan=1>
            &nbsp;<select class="select" name="aktivesLayout" onchange="document.GUI.submit()">
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
          	&nbsp;<select class="select" name="stelle">
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
      <table width=600 border=0 cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr>
          <td class="bold" style="border-bottom:1px solid #C3C7C3" colspan=8 >&nbsp;Layoutdaten</td>
        </tr>
        <tr>
          <td  colspan=4 style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<b>Name:</b> 
          	<input type="text" class="input" name="name" value="<? echo $this->ddl->selectedlayout[0]['name'] ?>" size="27">
          </td>
          <td  colspan=4 style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<b>Typ:</b> 
          	<select name="type">
          		<option value="0" <? if($this->ddl->selectedlayout[0]['type'] == 0)echo 'selected' ?>>pro Datensatz eine Seite</option>
          		<option value="1" <? if($this->ddl->selectedlayout[0]['type'] == 1)echo 'selected' ?>>Datensätze untereinander</option>
          	</select>	
          </td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-bottom:1px solid #C3C7C3" colspan="8">&nbsp;Hintergrundbild&nbsp;</td>
        </tr>
        <tr>
        	<td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;<? echo $this->ddl->selectedlayout[0]['bgsrc'] ?></td>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="bgposx" value="<? echo $this->ddl->selectedlayout[0]['bgposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td><input class="input" type="text" name="bgwidth" value="<? echo $this->ddl->selectedlayout[0]['bgwidth'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="bgsrc" size="20"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="bgposy" value="<? echo $this->ddl->selectedlayout[0]['bgposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input class="input" type="text" name="bgheight" value="<? echo $this->ddl->selectedlayout[0]['bgheight'] ?>" size="5"></td>
        </tr>
        <tr>
      </table>
      <table border="1" width="595" cellspacing="0" cellpadding="0">
      	<tr>
        	<td colspan=8 align="left">
        		<? if($this->previewfile){ ?>
        			<div id="preview_div" onmouseout="document.getElementById('coords').style.visibility='hidden';" onmousemove="image_coords(event)" style="width:595px;height:842px;background-image:url('<? echo $this->previewfile; ?>');">
        				<div id="coords" style="background-color: white;width:65px;visibility: hidden;position:relative;border: 1px solid black">
        					&nbsp;x:&nbsp;<input type="text" id="posx" size="2" style="border:none"><br>
        					&nbsp;y:&nbsp;<input type="text" id="posy" size="2" style="border:none">
        				</div>
        			</div>
        		<? } ?>
					</td>
        </tr>
      </table>
      <table width=600 border=0 cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
  			<tr>
          <td style="border-bottom:1px solid #C3C7C3" colspan=8><b>&nbsp;Attribute</b></td>
        </tr>
        
 <? if($this->formvars['selected_layer_id'] != ''){
    	for($i = 0; $i < count($this->attributes['type']); $i++){
    		if($this->attributes['type'][$i] != 'geometry'){
    			if($this->attributes['alias'][$i] == '')$this->attributes['alias'][$i] = $this->attributes['name'][$i]; ?>
    		<tr>
        	<td class="bold" align="left" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->attributes['name'][$i]; ?>');">
        	<? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){ ?>
        		<img id="img_<? echo $this->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;<? echo $this->attributes['alias'][$i].' ($'.$this->attributes['name'][$i].')'; ?>
        	<? }else{ ?>
        		<img id="img_<? echo $this->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;<? echo $this->attributes['alias'][$i].' ($'.$this->attributes['name'][$i].')'; ?>
        	<? } ?>
        	</td>
        </tr>
        <tr id="tr1_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;x:</td>
        	<td><input type="text" class="input" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos']; ?>" size="5"></td>
					<td width="60px">&nbsp;Breite:</td>
					<td><input class="input"  type="text" name="width_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['width']; ?>" size="5"></td>
					<td align="left" colspan="3" align="center">
        		<select title="Schriftart" name="font_<? echo $this->attributes['name'][$i]; ?>">
	        		<?
	        		for($j = 0; $j < count($this->ddl->fonts); $j++){
	        			echo '<option ';
	        			if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['font'] == $this->ddl->fonts[$j]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->ddl->fonts[$j].'">'.basename($this->ddl->fonts[$j]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        </tr>
        <tr id="tr2_<? echo $this->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;y:</td>
        	<td><input type="text" class="input" name="posy_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['ypos']; ?>" size="5"></td>
        	<td>&nbsp;Rahmen:</td>
        	<td><input type="checkbox" name="border_<? echo $this->attributes['name'][$i]; ?>" value="1" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['border'] == '1'){echo 'checked="true"';} ?> size="5"></td>
        	<td align="left" align="center" colspan="2"><input type="text" class="input" title="Schriftgröße" name="fontsize_<? echo $this->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['name'][$i]]['fontsize']; ?>" size="5">&nbsp;pt</td>
        </tr>
 <?  		}	
    	}
    	if($this->attributes['the_geom'] != ''){ ?>
    		<tr>
        	<td class="bold" align="left" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->attributes['the_geom']; ?>');">
        	<? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){ ?>
        		<img id="img_<? echo $this->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;<? echo $this->attributes['the_geom']; ?>
        	<? }else{ ?>
        		<img id="img_<? echo $this->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;<? echo $this->attributes['the_geom']; ?>
        	<? } ?>
        	</td>
        </tr>
        <tr id="tr1_<? echo $this->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;x:</td>
        	<td><input type="text" class="input" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos']; ?>" size="5"></td>
					<td width="60px">&nbsp;Breite:</td>
					<td><input class="input"  type="text" name="width_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['width']; ?>" size="5"></td>
					<td colspan="3"></td>
        </tr>
        <tr id="tr2_<? echo $this->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
        	<td>&nbsp;&nbsp;&nbsp;y:</td>
        	<td><input type="text" class="input" name="posy_<? echo $this->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->attributes['the_geom']]['ypos']; ?>" size="5"></td>
        	<td colspan="5">&nbsp;</td>
        </tr>	
<?   	}
 		} ?>
        
        <tr>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Datum&nbsp;</td>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Nutzer&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="dateposx" value="<? echo $this->ddl->selectedlayout[0]['dateposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
        		<select title="Schriftart" name="font_date">
        			<option value="">--- bitte wählen ---</option>
	        		<?
	        		for($i = 0; $i < count($this->ddl->fonts); $i++){
	        			echo '<option ';
	        			if($this->ddl->selectedlayout[0]['font_date'] == $this->ddl->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->ddl->fonts[$i].'">'.basename($this->ddl->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        	<td width="100px" style="border-right:1px solid #C3C7C3">
        		&nbsp;x:&nbsp;<input type="text" class="input" name="userposx" value="<? echo $this->ddl->selectedlayout[0]['userposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
        		<select title="Schriftart" name="font_user">
        			<option value="">--- bitte wählen ---</option>
	        		<?
	        		for($i = 0; $i < count($this->ddl->fonts); $i++){
	        			echo '<option ';
	        			if($this->ddl->selectedlayout[0]['font_user'] == $this->ddl->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->ddl->fonts[$i].'">'.basename($this->ddl->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="dateposy" value="<? echo $this->ddl->selectedlayout[0]['dateposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" align="center" colspan="2"><input type="text" class="input" name="datesize" value="<? echo $this->ddl->selectedlayout[0]['datesize'] ?>" size="5">&nbsp;pt</td>
        	<td style="border-right:1px solid #C3C7C3">
        		&nbsp;y:&nbsp;<input type="text" class="input" name="userposy" value="<? echo $this->ddl->selectedlayout[0]['userposy'] ?>" size="5"></td>
        	<td align="center" colspan="2"><input type="text" class="input" title="Schriftgröße" name="usersize" value="<? echo $this->ddl->selectedlayout[0]['usersize'] ?>" size="5">&nbsp;pt</td>
        </tr>        
        <tr>
          <td class="bold" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Freitexte</td>
        </tr>
 
        <? for($i = 0; $i < count($this->ddl->selectedlayout[0]['texts']); $i++){
        		$this->ddl->selectedlayout[0]['texts'][$i]['text'] = str_replace(';', chr(10), $this->ddl->selectedlayout[0]['texts'][$i]['text']);
        	 ?>
	        <tr>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;">&nbsp;</td>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;</td>
	        	<td rowspan="4" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=3>
	        		<textarea class="input" name="text<? echo $i ?>" cols="31" rows="4"><? echo $this->ddl->selectedlayout[0]['texts'][$i]['text'] ?></textarea>
	        		<? if($this->ddl->selectedlayout[0]['type'] == 1){ ?>
	        		<input type="checkbox" value="1" name="texttype<? echo $i ?>" <? if($this->ddl->selectedlayout[0]['texts'][$i]['type'] == 1)echo 'checked="true"'; ?>> fixiert
	        		<? } ?>
	        	</td>
	        	<td style="border-top:2px solid #C3C7C3;" colspan=2 align="center">
	        		<select title="Schriftart" name="textfont<? echo $i ?>">
	        			<option value="">--- bitte wählen ---</option>
		        		<?
		        		for($j = 0; $j < count($this->ddl->fonts); $j++){
		        			echo '<option ';
		        			if($this->ddl->selectedlayout[0]['texts'][$i]['font'] == $this->ddl->fonts[$j]){
		        				echo 'selected ';
		        			}
		        			echo 'value="'.$this->ddl->fonts[$j].'">'.basename($this->ddl->fonts[$j]).'</option>';
		        		}
		        		?>
	        		</select>
	        	</td>
	        </tr>
	        <tr>
	        	<td>&nbsp;x:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="textposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['posx'] ?>" size="5"></td>	        	
	        	<td colspan="2"><input type="text" class="input" title="Schriftgröße" name="textsize<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['size'] ?>" size="5">&nbsp;pt</td>
	        </tr>
	       	<tr>
	       		<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="textposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['posy'] ?>" size="5"><input type="hidden" name="text_id<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['id'] ?>"></td>
	       		<td colspan="2"><input type="text" class="input" title="Drehwinkel" name="textangle<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['texts'][$i]['angle'] ?>" size="5">°</td>
	        </tr>
	        <tr>
	        	<td style="border-right:1px solid #C3C7C3" colspan="2">&nbsp;</td>
	        	<td colspan="2" align="right"><a href="javascript:Bestaetigung('index.php?go=sachdaten_druck_editor_Freitextloeschen&freitext_id=<? echo $this->ddl->selectedlayout[0]['texts'][$i]['id'] ?>&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&aktivesLayout=<? echo $this->formvars['aktivesLayout']; ?>', 'Wollen Sie den Freitext wirklich löschen?');">löschen</a></td>
	        </tr>
	      <? } ?>
	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfreetext();">Freitext hinzufügen</a></td>
        </tr>        
        <tr>
          <td style="border-top:1px solid #C3C7C3" colspan=8>&nbsp;</td>
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
<input type="hidden" name="bgsrc_save" value="<? echo $this->ddl->selectedlayout[0]['bgsrc'] ?>">


