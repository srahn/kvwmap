<? include(LAYOUTPATH.'languages/layer_formular_'.$this->user->rolle->language.'.php');

	global $selectable_scales;
	$selectable_scales = array_reverse($selectable_scales);

?>
<script type="text/javascript">
<!--

function change_layer(){
	layer_id = document.GUI.selected_layer_id.value;
	if(layer_id != ''){
		document.getElementById('form').style.display = 'inline-block';
		document.GUI.class_1.disabled = true;
		document.getElementById('style_div').innerHTML = '';
		document.getElementById('label_div').innerHTML = '';
		document.getElementById('selected_style_div').innerHTML = '';
		document.getElementById('selected_label_div').innerHTML = '';
		document.GUI.selected_style_id.value = '';
		document.GUI.selected_label_id.value = '';
		ahah('index.php', 'go=getclasses&layer_id=' + layer_id, new Array(document.getElementById('classes_div')), "");
		document.getElementById('toLayerLink').style='display:inline';
	}
}

function change_class(){
	document.GUI.selected_style_id.value = '';
	document.GUI.selected_label_id.value = '';
	document.getElementById('selected_style_div').innerHTML = '';
	document.getElementById('selected_label_div').innerHTML = '';
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	document.GUI.selected_class_id.value = class_id;
	ahah('index.php', 'go=getstyles_labels&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div'), document.getElementById('label_div')), "");
}

function get_style(style_id){
	if(document.GUI.selected_style_id.value != ''){
		if(document.getElementById('td1_style_'+style_id))document.getElementById('td1_style_'+document.GUI.selected_style_id.value).style.backgroundColor='';
		if(document.getElementById('td2_style_'+style_id))document.getElementById('td2_style_'+document.GUI.selected_style_id.value).style.backgroundColor='';
	}
	if(document.getElementById('td1_style_'+style_id))document.getElementById('td1_style_'+style_id).style.backgroundColor='lightsteelblue';
	if(document.getElementById('td2_style_'+style_id))document.getElementById('td2_style_'+style_id).style.backgroundColor='lightsteelblue';
	layer_id = document.GUI.selected_layer_id.value;
	document.GUI.selected_style_id.value = style_id;
	ahah('index.php', 'go=get_style&style_id='+style_id+'&layer_id='+layer_id, new Array(document.getElementById('selected_style_div')), "");
}

function get_label(label_id){
	if(document.GUI.selected_label_id.value != ''){
		document.getElementById('td1_label_'+document.GUI.selected_label_id.value).style.backgroundColor='';
		document.getElementById('td2_label_'+document.GUI.selected_label_id.value).style.backgroundColor='';
	}
	document.getElementById('td1_label_'+label_id).style.backgroundColor='lightsteelblue';
	document.getElementById('td2_label_'+label_id).style.backgroundColor='lightsteelblue';
	layer_id = document.GUI.selected_layer_id.value;
	document.GUI.selected_label_id.value = label_id;
	ahah('index.php', 'go=get_label&label_id='+label_id+'&layer_id='+layer_id, new Array(document.getElementById('selected_label_div')), "");
}

function add_label(){
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	label_id = document.GUI.selected_label_id.value;
	ahah('index.php', 'go=add_label&label_id='+label_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('label_div')), "");
}

function delete_label(label_id){
	if(label_id == document.GUI.selected_label_id.value){
		document.getElementById('selected_label_div').innerHTML = '';
		document.GUI.selected_label_id.value = '';	
	}
	selected_label_id = document.GUI.selected_label_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	ahah('index.php', 'go=delete_label&selected_label_id=' + selected_label_id + '&label_id=' + label_id + '&class_id=' + class_id + '&layer_id=' + layer_id, new Array(document.getElementById('label_div')), "");
}

function add_style(){
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	style_id = document.GUI.selected_style_id.value;
	ahah('index.php', 'go=add_style&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");
}

function delete_style(style_id){
	if(style_id == document.GUI.selected_style_id.value){
		document.getElementById('selected_style_div').innerHTML = '';
		document.GUI.selected_style_id.value = '';	
	}
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	ahah('index.php', 'go=delete_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");
}

function moveup_style(style_id){
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	ahah('index.php', 'go=moveup_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");	
}

function movedown_style(style_id){
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.selected_layer_id.value;
	ahah('index.php', 'go=movedown_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");	
}

function save_style(style_id){
	form_fields = Array.prototype.slice.call(document.querySelectorAll('.styleFormField'));
	var formData = new FormData();
	for(i = 0; i < form_fields.length; i++){
		if(form_fields[i].type != 'checkbox' || form_fields[i].checked){
			formData.append(form_fields[i].name, form_fields[i].value);
		}
	}
	formData.append('go', 'save_style');
	formData.append('layer_id', document.GUI.selected_layer_id.value);
	formData.append('class_id', document.GUI.class_1.value);
	formData.append('style_id', style_id);	
	ahah('index.php', formData, new Array(document.getElementById('style_div'), document.getElementById('selected_style_div')), "");
}

function save_label(label_id){
	form_fields = Array.prototype.slice.call(document.querySelectorAll('.labelFormField'));
	var formData = new FormData();
	for(i = 0; i < form_fields.length; i++){
		if(form_fields[i].type != 'checkbox' || form_fields[i].checked){
			formData.append(form_fields[i].name, form_fields[i].value);
		}
	}
	formData.append('go', 'save_label');
	formData.append('layer_id', document.GUI.selected_layer_id.value);
	formData.append('class_id', document.GUI.class_1.value);
	formData.append('label_id', label_id);
	ahah('index.php', formData, new Array(document.getElementById('label_div'), document.getElementById('selected_label_div')), "");
}

function applyfont(){
	if(document.GUI.label_font != undefined){
		document.GUI.label_font.value = document.GUI.font.value;
	}
	else{
		alert("Bitte erst ein Label auswählen.");
	}
}

function browser_check(){
	if(navigator.appName == 'Microsoft Internet Explorer'){
		selobj = document.GUI.font;
		for(i=0; i < selobj.length; i++){
			selobj.options[i].innerHTML = selobj.options[i].id;
		}
	}
}

function navigate(params) {
	location.href = 'index.php?' + params + '&selected_layer_id=' + document.GUI.selected_layer_id.value + '&csrf_token=<? echo $_SESSION['csrf_token']; ?>';
}

function setScale(select){
	if(select.value != ''){
		document.GUI.nScale.value=select.value;
		document.getElementById('scales').style.display='none';
		document.GUI.legendtouched.value = 1;
		neuLaden();
	}
}


//-->
</script>

<style>
	.navigation{
		border-collapse: collapse; 
		width: 940px;
		background:rgb(248, 248, 249);
	}
	.navigation th{
		border: 1px solid #bbb;
		border-collapse: collapse;
		width: 17%;
	}
	.navigation th div{
		padding: 3px;
		padding: 9px 0 9px 0;
		width: 100%;
	}	
	.navigation th:not(.navigation-selected) a{
		color: #888;
	}	
	.navigation th:not(.navigation-selected):hover{
		background-color: rgb(238, 238, 239);
	}
	.navigation-selected{
		background-color: #c7d9e6;
	}
	.navigation-selected div{
		color: #111;
	}
	#selected_style_div {
		width: 218px;
	}
	.fa-clipboard:hover {
		cursor: pointer;
	}
</style>

<table>
	<tr>
    <td style="">
			<span class="px17 fetter"><? echo $strLayer;?>:</span>
      <select id="selected_layer_id" style="width:250px" size="1" name="selected_layer_id" onchange="change_layer();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
      <option value="">--------- <?php echo $this->strPleaseSelect; ?> --------</option>
        <?
    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
    			echo '<option';
    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
    				echo ' selected';
    			}
    			echo ' value="'.$this->layerdaten['ID'][$i].'">' . $this->layerdaten['Bezeichnung'][$i] . ($this->layerdaten['alias'][$i] != '' ? ' [' . $this->layerdaten['alias'][$i] . ']' : '') . '</option>';
    		}
    	?>
      </select>
		</td>
  </tr>
</table>

<div id="form" style="<? if($this->formvars['selected_layer_id'] == '')echo 'display: none'; ?>">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" style="margin: 10px">
	<tr align="center"> 
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" class="navigation">
				<tr>
					<th><a href="javascript:navigate('go=Layereditor');"><div><? echo $strCommonData; ?></div></a></th>
					<th><a href="javascript:navigate('go=Klasseneditor');"><div><? echo $strClasses; ?></div></a></th>
					<th class="navigation-selected"><a href="index.php?go=Style_Label_Editor&selected_layer_id=<? echo $this->formvars['selected_layer_id'] ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><div><? echo $strStylesLabels; ?></div></a></th>
					<? if(in_array($this->layerdata['connectiontype'], [MS_POSTGIS, MS_WFS])){ ?>
					<th><a href="javascript:navigate('go=Attributeditor');"><div><? echo $strAttributes; ?></div></a></th>
					<? } ?>
					<th><a href="javascript:navigate('go=Layereditor&stellenzuweisung=1');"><div><? echo $strStellenAsignment; ?></div></a></th>
					<? if(in_array($this->layerdata['connectiontype'], [MS_POSTGIS, MS_WFS])){ ?>
					<th><a href="javascript:navigate('go=Layerattribut-Rechteverwaltung');"><div><? echo $strPrivileges; ?></div></a></th>
					<? } ?>
				</tr>
			</table>
		</td>
	</tr>	
</table>

<table border="0" cellpadding="2" cellspacing="2" bgcolor="#f8f8f9">
  <tr>
  	<td valign="top">
		  <table cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3;">
			  <tr> 
			    <td colspan="4" class="fett">Klassen</td>
			  </tr>
			  <tr>
			    <td style="border-bottom:1px solid #C3C7C3;" colspan="4">
			    	<div id="classes_div"> 
			      <select style="width:430px" size="4"  name="class_1" onchange="change_class();" <?php if(count($this->allclassdaten)==0){ echo 'disabled';}?>>
			        <?
			    		for($i = 0; $i < count($this->allclassdaten); $i++){
			    			echo '<option';
			    			if($this->allclassdaten[$i]['Class_ID'] == $this->formvars['selected_class_id']){
			    				echo ' selected';
			    			}
			    			echo ' value="'.$this->allclassdaten[$i]['Class_ID'].'">'.$this->allclassdaten[$i]['Name'].'</option>';
			    		}
			    		?>
			      </select>
			      </div> 
			  	</td>
			  </tr>
			  <tr>
					<td valign="top" colspan="2" style="border-right:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3;">
						<div id="style_div"><?
							if ($this->formvars['selected_class_id']) { ?>
								<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
						  		<tr>
										<td height="25" valign="top">Styles</td><td align="right"><a href="javascript:add_style();">neuer Style</a></td>
									</tr><?
									if (count($this->classdaten[0]['Style']) > 0) {
										$this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
										for ($i = 0; $i < count($this->classdaten[0]['Style']); $i++) { ?>
											<tr><?
												$td_id = 'td1_style_' . $this->classdaten[0]['Style'][$i]['Style_ID'];
												$td_style = ($this->formvars['selected_style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID'] ? 'background-color:lightsteelblue;' : '');
												$td_onclick = 'get_style(' . $this->classdaten[0]['Style'][$i]['Style_ID'] . ');';
											?>
												<td id="<? echo $td_id; ?>" style="<? echo $td_style; ?>" onclick="<? echo $td_onclick; ?>">
													<img src="<?php echo IMAGEURL . $this->getlegendimage($this->formvars['selected_layer_id'], $this->classdaten[0]['Style'][$i]['Style_ID']); ?>">
												</td><?
													$td_id = 'td2_style_' . $this->classdaten[0]['Style'][$i]['Style_ID'];
												?>
												<td id="<? echo $td_id; ?>" align="right" style="<? echo $td_style; ?>"><?
												if ($i < count($this->classdaten[0]['Style']) - 1) { ?>
													<a
														href="javascript:movedown_style(<? echo $this->classdaten[0]['Style'][$i]['Style_ID']; ?>);"
														title="in der Zeichenreihenfolge nach unten verschieben"
													>
														<img src="<? echo GRAPHICSPATH; ?>pfeil.gif" border="0">
													</a><?
												}
												if ($i > 0) { ?>
													&nbsp;<a
														href="javascript:moveup_style(<? echo $this->classdaten[0]['Style'][$i]['Style_ID']; ?>);"
														title="in der Zeichenreihenfolge nach oben verschieben"
													>
														<img src="<? echo GRAPHICSPATH; ?>pfeil2.gif" border="0">
													</a><?
												} ?>
												&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style(<? echo $this->classdaten[0]['Style'][$i]['Style_ID']; ?>);">löschen</a>
											</td>
										</tr><?
									}
								}
								echo'
									</table>';
				    	}
				    	?>
						</div>
					</td>
					<td valign="top" colspan="2" style="border-bottom:1px solid #C3C7C3;">
			  		<div id="label_div">
				    	<?
				    	if($this->formvars['selected_class_id']){
					    	echo'
						  		<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
										<tr>
											<td height="25" valign="top">Labels</td>
										</tr>';
							if(count($this->classdaten[0]['Label']) > 0){
								for($i = 0; $i < count($this->classdaten[0]['Label']); $i++){
									echo'
						    		<tr>
									  	<td ';
									  	if($this->formvars['selected_label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
									  	echo' id="td1_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" onclick="get_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">';
									  		echo 'Label '.$this->classdaten[0]['Label'][$i]['Label_ID'].'</td>';
									  		echo '<td align="right" id="td2_label_'.$this->classdaten[0]['Label'][$i]['Label_ID'].'" ';
										  	if($this->formvars['selected_label_id'] == $this->classdaten[0]['Label'][$i]['Label_ID']){echo 'style="background-color:lightsteelblue;" ';}
										  	echo '><a href="javascript:delete_label('.$this->classdaten[0]['Label'][$i]['Label_ID'].');">löschen</a>';
									echo'
											</td>
										</tr>';
								}
							}
								echo'
									</table>';
				    	}
				    	?>
						</div>
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="2" style="width: 50%; border-right:1px solid #C3C7C3;">
						<div id="selected_style_div"><?
							if (count($this->styledaten) > 0) { ?>
								<table align="left" border="0" cellspacing="0" cellpadding="3"><?
									for ($i = 0; $i < count($this->styledaten); $i++) { ?>
										<tr>
											<td class="px13"><?
												echo key($this->styledaten); ?>
											</td>
											<td>
												<input name="style_<? echo key($this->styledaten); ?>" size="20" type="text" value="<? echo $this->styledaten[key($this->styledaten)]; ?>">
											</td>
										</tr><?
										next($this->styledaten);
									} ?>
									<tr>
										<td height="30" colspan="2" valign="bottom" align="center">
											<input type="button" name="style_save" value="Speichern" onclick="save_style(<? echo $this->styledaten['Style_ID']; ?>)">
										</td>
									</tr>
								</table><?
							} ?>
						</div>
					</td>
					<td valign="top" colspan="2">
						<div id="selected_label_div">
				    <?
				    if(count($this->labeldaten) > 0){
				  		echo'
					  		<table align="left" border="0" cellspacing="0" cellpadding="3">';
							for($i = 0; $i < count($this->labeldaten); $i++){
								echo'
					    		<tr>
								  	<td class="px13">';
								  		echo key($this->labeldaten).'</td><td><input name="label_'.key($this->labeldaten).'" size="11" type="text" value="'.$this->labeldaten[key($this->labeldaten)].'">';
								echo'
										</td>
									</tr>';
								next($this->labeldaten);
							}
							echo'
									<tr>
										<td height="30" colspan="2" valign="bottom" align="center"><input type="button" name="label_save" value="Speichern" onclick="save_label('.$this->labeldaten['Label_ID'].')"></td>
									</tr>
								</table>';
				  	}
				    ?>	
						</div>
					</td>
				</tr>
		  </table>
		</td>
		<td valign="top">
			<table>
				<tr>
					<td valign="top">
						<table cellpadding="2" cellspacing="0">
							<tr>
								<td><span class="fett">Farbe:</span></td>
							</tr>
							<tr>
								<td><input onmouseover="browser_check();" name="sample1" type="text" style="width: 180px; background-color: rgb(255, 255, 255);" id="sample1"></td>
							</tr>
							<tr>
								<td><input name="sample2" type="text" style="width: 180px; background-color: rgb(255, 255, 255);" id="sample2"></td>
							</tr>
							<tr>
								<td><div style="float: left; margin-top: 3px">RGB:</div><div style="float: right"><input style="width: 143px;" name="rgb" type="text" onkeyup="$('input[name=hex]').val(rgbToHex(this.value));"></div></td>
							</tr>
							<tr>
								<td><div style="float: left; margin-top: 3px">Hex:</div><div style="float:right"><input style="width: 143px;" name="hex" type="text"></div></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><span class="fett">Font:</span></td>
							</tr>
							<tr>
								<td><?
									if (!function_exists(imagecreatetruecolor)) { ?>
										<select size="1" name="font"><?
											for ($i = 0; $i < count($this->fonts['name']); $i++) { ?>
												<option id="<? echo $this->fonts['name'][$i]; ?>" value="<? echo $this->fonts['name'][$i]; ?>"><? echo $this->fonts['name'][$i]; ?></option><?
											} ?>
										</select><?
									}
									else { ?>
										<select size="1" class="imagebacked" name="font" style="background-image:url('<? echo @$this->createFontSampleImage($this->fonts['filename'][0], $this->fonts['name'][0]); ?>');"><?
											for ($i = 0; $i < count($this->fonts['name']); $i++) { ?>
												<option
													onclick="this.parentNode.setAttribute('style',this.getAttribute('style'));"
													class="imagebacked"
													style="background-image:url('<? echo @$this->createFontSampleImage($this->fonts['filename'][$i], $this->fonts['name'][$i]); ?>');"
													id="<? echo $this->fonts['name'][$i]; ?>"
													value="<? echo $this->fonts['name'][$i]; ?>"
												><? echo $this->fonts['name'][$i]; ?></option><?
											} ?>
										</select><?
									} ?>
								</td>
							</tr>
							<tr>
								<td><a href="javascript:applyfont();">Font übernehmen</a></td>
							</tr>
						</table>
					</td>
					<td>
						<div>
						 <?php include(LAYOUTPATH.'snippets/SVG_ColorChooser.php');  ?>
						</div>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>
						<div id="map_div" style="border:1px solid #C3C7C3;">
						 <?php include(LAYOUTPATH.'snippets/SVG_style_preview.php');  ?>
						</div>
						<div id="scale_selector_div" style="margin-top: 4px;">
							<div style="width:145px;" onmouseenter="document.getElementById('scales').style.display='inline-block';" onmouseleave="document.getElementById('scales').style.display='none';">
								<div valign="top" style="height:0px; position:relative;">
									<div id="scales" style="display:none; position:absolute; left:66px; bottom:-1px; width: 70px; vertical-align:top; overflow:hidden; border:solid grey 1px;">
										<select size="<? echo count($selectable_scales); ?>" style="padding:4px; margin:-2px -17px -4px -4px;" onmousedown="setScale(this);" onclick="setScale(this);">
											<? 
												foreach($selectable_scales as $scale){
													echo '<option onmouseover="this.selected = true;" value="'.$scale.'">1:&nbsp;&nbsp;'.$scale.'</option>';
												}
											?>
										</select>
									</div>
								</div>
								&nbsp;&nbsp;<span class="fett"><?php echo $this->strMapScale; ?>&nbsp;1:&nbsp;</span><input type="text" id="scale" autocomplete="off" name="nScale" style="width:58px" value="<?php echo round($this->map_scaledenom); ?>">
							</div>
						</div>
					</td>
					<td valign="top">
			      <table cellspacing=0 cellpadding=2 border=0 style="border:1px solid #C3C7C3;">
			        <tr align="center">
			          <td>Verfügbare Themen:</td>
			        </tr>
			        <tr align="left">
			          <td>
			          <div align="center"><input type="button" name="neuladen_button" onclick="document.GUI.legendtouched.value = 1;neuLaden();" value="neu Laden"></div>
			          <br>
			        	<div style="width:230; height:<?php echo $this->map->height-59; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
				          &nbsp;
				          <img src="graphics/tool_info_2.png" alt="Informationsabfrage" title="Informationsabfrage" width="17">&nbsp;
				          <img src="graphics/layer.png" alt="Themensteuerung" title="Themensteuerung" width="20" height="20"><br>
									<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
				          <div id="legend" onclick="document.GUI.legendtouched.value = 1;">
										<? echo $this->legende; ?>
									</div>
				        </div>
			          </td>
			        </tr>
			      </table>
			     </td>
				 </tr>
			</table>
		</td>
	</tr>
</table>

</div>

<input type="hidden" name="selected_class_id" value="<? echo $this->formvars['selected_class_id']; ?>">
<input type="hidden" name="selected_style_id" value="<? echo $this->formvars['selected_style_id']; ?>">
<input type="hidden" name="selected_label_id" value="<? echo $this->formvars['selected_label_id']; ?>">
<input type="hidden" name="go" value="Style_Label_Editor">
<input type="hidden" name="neuladen" value="">
<script type="text/javascript">
<!--
browser_check();
-->
</script>


