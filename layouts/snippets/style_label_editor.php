<script type="text/javascript">
<!--

function change_layer(){
	document.GUI.class_1.disabled = true;
	document.getElementById('style_div').innerHTML = '';
	document.getElementById('label_div').innerHTML = '';
	document.getElementById('selected_style_div').innerHTML = '';
	document.getElementById('selected_label_div').innerHTML = '';
	document.GUI.selected_style_id.value = '';
	document.GUI.selected_label_id.value = '';
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	document.GUI.selected_layer_id.value = layer_id;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=getclasses&layer_id='+layer_id, new Array(document.getElementById('classes_div')), "");
}

function change_class(){
	document.GUI.selected_style_id.value = '';
	document.GUI.selected_label_id.value = '';
	document.getElementById('selected_style_div').innerHTML = '';
	document.getElementById('selected_label_div').innerHTML = '';
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	document.GUI.selected_class_id.value = class_id;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=getstyles_labels&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div'), document.getElementById('label_div')), "");
}

function get_style(style_id){
	if(document.GUI.selected_style_id.value != ''){
		if(document.getElementById('td1_style_'+style_id))document.getElementById('td1_style_'+document.GUI.selected_style_id.value).style.backgroundColor='';
		if(document.getElementById('td2_style_'+style_id))document.getElementById('td2_style_'+document.GUI.selected_style_id.value).style.backgroundColor='';
	}
	if(document.getElementById('td1_style_'+style_id))document.getElementById('td1_style_'+style_id).style.backgroundColor='lightsteelblue';
	if(document.getElementById('td2_style_'+style_id))document.getElementById('td2_style_'+style_id).style.backgroundColor='lightsteelblue';
	document.GUI.selected_style_id.value = style_id;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_style&style_id='+style_id, new Array(document.getElementById('selected_style_div')), "");
}

function get_label(label_id){
	if(document.GUI.selected_label_id.value != ''){
		document.getElementById('td1_label_'+document.GUI.selected_label_id.value).style.backgroundColor='';
		document.getElementById('td2_label_'+document.GUI.selected_label_id.value).style.backgroundColor='';
	}
	document.getElementById('td1_label_'+label_id).style.backgroundColor='lightsteelblue';
	document.getElementById('td2_label_'+label_id).style.backgroundColor='lightsteelblue';
	document.GUI.selected_label_id.value = label_id;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_label&label_id='+label_id, new Array(document.getElementById('selected_label_div')), "");
}

function add_label(){
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	label_id = document.GUI.selected_label_id.value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=add_label&label_id='+label_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('label_div')), "");
}

function delete_label(label_id){
	if(label_id == document.GUI.selected_label_id.value){
		document.getElementById('selected_label_div').innerHTML = '';
		document.GUI.selected_label_id.value = '';	
	}
	selected_label_id = document.GUI.selected_label_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=delete_label&selected_label_id='+selected_label_id+'&label_id='+label_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('label_div')), "");
}

function add_style(){
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	style_id = document.GUI.selected_style_id.value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=add_style&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");
}

function delete_style(style_id){
	if(style_id == document.GUI.selected_style_id.value){
		document.getElementById('selected_style_div').innerHTML = '';
		document.GUI.selected_style_id.value = '';	
	}
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=delete_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");
}

function moveup_style(style_id){
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=moveup_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");	
}

function movedown_style(style_id){
	selected_style_id = document.GUI.selected_style_id.value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value;
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=movedown_style&selected_style_id='+selected_style_id+'&style_id='+style_id+'&class_id='+class_id+'&layer_id='+layer_id, new Array(document.getElementById('style_div')), "");	
}

function save_style(style_id){
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value
	data = 'go=save_style&class_id='+class_id;
	data+= '&style_id='+style_id;
	data+= '&layer_id='+layer_id;
	data+= '&new_style_id='+document.GUI.style_Style_ID.value;
	data+= '&symbol='+document.GUI.style_symbol.value;
	data+= '&symbolname='+document.GUI.style_symbolname.value;
	data+= '&size='+document.GUI.style_size.value;
	data+= '&color='+document.GUI.style_color.value;
	data+= '&backgroundcolor='+document.GUI.style_backgroundcolor.value;
	data+= '&outlinecolor='+document.GUI.style_outlinecolor.value;
	data+= '&minsize='+document.GUI.style_minsize.value;
	data+= '&maxsize='+document.GUI.style_maxsize.value;
	data+= '&angle='+document.GUI.style_angle.value;
	data+= '&angleitem='+document.GUI.style_angleitem.value;
	data+= '&width='+document.GUI.style_width.value;
	data+= '&minwidth='+document.GUI.style_minwidth.value;
	data+= '&maxwidth='+document.GUI.style_maxwidth.value;
	data+= '&sizeitem='+document.GUI.style_sizeitem.value;
	data+= '&offsetx='+document.GUI.style_offsetx.value;
	data+= '&offsety='+document.GUI.style_offsety.value;
  data+= '&pattern='+document.GUI.style_pattern.value;
  data+= '&geomtransform='+document.GUI.style_geomtransform.value;  
	data+= '&gap='+document.GUI.style_gap.value;
	data+= '&linecap='+document.GUI.style_linecap.value;
	data+= '&linejoin='+document.GUI.style_linejoin.value;
	data+= '&linejoinmaxsize='+document.GUI.style_linejoinmaxsize.value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', data, new Array(document.getElementById('style_div'), document.getElementById('selected_style_div')), "");
}

function save_label(label_id){
	layer_id = document.GUI.layer.options[document.GUI.layer.selectedIndex].value;
	class_id = document.GUI.class_1.options[document.GUI.class_1.selectedIndex].value
	data = 'go=save_label&class_id='+class_id;
	data+= '&label_id='+label_id;
	data+= '&layer_id='+layer_id;
	data+= '&new_label_id='+document.GUI.label_Label_ID.value;
	data+= '&font='+document.GUI.label_font.value;
	data+= '&type='+document.GUI.label_type.value;
	data+= '&color='+document.GUI.label_color.value;
	data+= '&outlinecolor='+document.GUI.label_outlinecolor.value;
	data+= '&shadowcolor='+document.GUI.label_shadowcolor.value;
	data+= '&shadowsizex='+document.GUI.label_shadowsizex.value;
	data+= '&shadowsizey='+document.GUI.label_shadowsizey.value;
	data+= '&backgroundcolor='+document.GUI.label_backgroundcolor.value;
	data+= '&backgroundshadowcolor='+document.GUI.label_backgroundshadowcolor.value;
	data+= '&backgroundshadowsizex='+document.GUI.label_backgroundshadowsizex.value;
	data+= '&backgroundshadowsizey='+document.GUI.label_backgroundshadowsizey.value;
	data+= '&size='+document.GUI.label_size.value;
	data+= '&minsize='+document.GUI.label_minsize.value;
	data+= '&maxsize='+document.GUI.label_maxsize.value;
	data+= '&position='+document.GUI.label_position.value;
	data+= '&offsetx='+document.GUI.label_offsetx.value;
	data+= '&offsety='+document.GUI.label_offsety.value;
	data+= '&angle='+document.GUI.label_angle.value;
	data+= '&autoangle='+document.GUI.label_autoangle.value;
	data+= '&buffer='+document.GUI.label_buffer.value;
	data+= '&antialias='+document.GUI.label_antialias.value;
	data+= '&minfeaturesize='+document.GUI.label_minfeaturesize.value;
	data+= '&maxfeaturesize='+document.GUI.label_maxfeaturesize.value;
	data+= '&partials='+document.GUI.label_partials.value;
	data+= '&wrap='+document.GUI.label_wrap.value;
	data+= '&the_force='+document.GUI.label_the_force.value;
	ahah('<? echo URL.APPLVERSION; ?>index.php', data, new Array(document.getElementById('label_div'), document.getElementById('selected_label_div')), "");
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


//-->
</script>

<table border="0" cellpadding="2" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="4" height="30"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr>
  	<td valign="top">
		  <table cellpadding="3" cellspacing="0" style="border:1px solid #C3C7C3;">
			  <tr> 
			  	<td style="border-right:1px solid #C3C7C3;" colspan="2">Layer</td>
			    <td colspan="2">Klassen</td>
			  </tr>
			  <tr>
			  	<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3;">
			      <select style="width:200px" class="select" name="layer" onchange="change_layer();">
			        <option value="">------------------- Bitte wählen ----------------</option>
			        <?
			    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
			    			echo '<option value="'.$this->layerdaten['ID'][$i].'" ';
			    			if($this->formvars['selected_layer_id'] == $this->layerdaten['ID'][$i]){
			    				echo 'selected';
			    			}
			    			echo '>'.$this->layerdaten['Bezeichnung'][$i].'</option>';
			    		}
			    	?>
			      </select>
			    </td>
			    <td style="border-bottom:1px solid #C3C7C3;" colspan="2">
			    	<div id="classes_div"> 
			      <select style="width:200px" size="4" class="select" name="class_1" onchange="change_class();" <?php if(count($this->allclassdaten)==0){ echo 'disabled';}?>>
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
			  		<div id="style_div">
				    	<?
				    	if($this->formvars['selected_class_id']){
					    	echo'
						  		<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
						  			<tr>
											<td height="25" valign="top">Styles</td><td align="right"><a href="javascript:add_style();">neuer Style</a></td>
										</tr>';
								if(count($this->classdaten[0]['Style']) > 0){
									$this->classdaten[0]['Style'] = array_reverse($this->classdaten[0]['Style']);
									for($i = 0; $i < count($this->classdaten[0]['Style']); $i++){
										echo'
							    		<tr>
										  	<td ';
										  	if($this->formvars['selected_style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
										  	echo 'id="td1_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" onclick="get_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">';
										  		echo '<img src="'.IMAGEURL.$this->getlegendimage($this->formvars['selected_layer_id'], $this->classdaten[0]['Style'][$i]['Style_ID']).'"></td>';
										  		echo '<td align="right" id="td2_style_'.$this->classdaten[0]['Style'][$i]['Style_ID'].'" ';
										  		if($this->formvars['selected_style_id'] == $this->classdaten[0]['Style'][$i]['Style_ID']){echo 'style="background-color:lightsteelblue;" ';}
										  		echo '>';
										  		if($i < count($this->classdaten[0]['Style'])-1){echo '<a href="javascript:movedown_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach unten verschieben"><img src="'.GRAPHICSPATH.'pfeil.gif" border="0"></a>';}
													if($i > 0){echo '&nbsp;<a href="javascript:moveup_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');" title="in der Zeichenreihenfolge nach oben verschieben"><img src="'.GRAPHICSPATH.'pfeil2.gif" border="0"></a>';}
										  		echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:delete_style('.$this->classdaten[0]['Style'][$i]['Style_ID'].');">löschen</a>';
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
					<td valign="top" colspan="2" style="border-right:1px solid #C3C7C3;">
						<div id="selected_style_div">
						<?
						if(count($this->styledaten) > 0){
					  	echo'
					  		<table align="left" border="0" cellspacing="0" cellpadding="3">';
							for($i = 0; $i < count($this->styledaten); $i++){
								echo'
					    		<tr>
								  	<td class="verysmall">';
								  		echo key($this->styledaten).'</td><td><input name="style_'.key($this->styledaten).'" size="11" type="text" value="'.$this->styledaten[key($this->styledaten)].'">';
								echo'
										</td>
									</tr>';
								next($this->styledaten);
							}
							echo'
									<tr>
										<td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="style_save" value="Speichern" onclick="save_style('.$this->styledaten['Style_ID'].')"></td>
									</tr>
								</table>';
				  	}
						?>
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
								  	<td class="verysmall">';
								  		echo key($this->labeldaten).'</td><td><input name="label_'.key($this->labeldaten).'" size="11" type="text" value="'.$this->labeldaten[key($this->labeldaten)].'">';
								echo'
										</td>
									</tr>';
								next($this->labeldaten);
							}
							echo'
									<tr>
										<td height="30" colspan="2" valign="bottom" align="center"><input class="button" type="button" name="label_save" value="Speichern" onclick="save_label('.$this->labeldaten['Label_ID'].')"></td>
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
								<td><b>Farbe:</b></td>
							</tr>
							<tr>
								<td><input onmouseover="browser_check();" name="sample1" type="text" style="width: 180px; background-color: rgb(255, 255, 255);" id="sample1"></td>
							</tr>
							<tr>
								<td><input name="sample2" type="text" style="width: 180px; background-color: rgb(255, 255, 255);" id="sample2"></td>
							</tr>
							<tr>
								<td>RGB:&nbsp;<input style="width: 143px;" name="rgb" type="text"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><b>Font:</b></td>
							</tr>
							<tr>
								<td>
								<? if(!function_exists(imagecreatetruecolor)){ ?>
									<select size="1" name="font">
									<? for($i = 0; $i < count($this->fonts['name']); $i++){?>
										<option id="<? echo $this->fonts['name'][$i]; ?>" value="<? echo $this->fonts['name'][$i]; ?>"><? echo $this->fonts['name'][$i]; ?></option>		
									<? } ?>
									</select>
								<? }else{ ?>
									<select size="1" class="imagebacked" name="font" style="background-image:url('<? echo @$this->createFontSampleImage($this->fonts['filename'][0], $this->fonts['name'][0]); ?>');">
									<? for($i = 0; $i < count($this->fonts['name']); $i++){?>
										<option onclick="this.parentNode.setAttribute('style',this.getAttribute('style'));" class="imagebacked" style="background-image:url('<? echo @$this->createFontSampleImage($this->fonts['filename'][$i], $this->fonts['name'][$i]); ?>');" id="<? echo $this->fonts['name'][$i]; ?>" value="<? echo $this->fonts['name'][$i]; ?>"></option>		
									<? } ?>
									</select>
								<? } ?>
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
					</td>
					<td valign="top">
			      <table cellspacing=0 cellpadding=2 border=0 style="border:1px solid #C3C7C3;">
			        <tr align="center">
			          <td>Verfügbare Themen:</td>
			        </tr>
			        <tr align="left">
			          <td>
			          <div align="center"><input type="submit" class="button" name="neuladen" value="neu Laden"></div>
			          <br>
			        	<div style="width:230; height:<?php echo $this->map->height-59; ?>; overflow:auto; scrollbar-base-color:<?php echo BG_DEFAULT ?>">
				          &nbsp;
				          <img src="graphics/tool_info_2.png" alt="Informationsabfrage" title="Informationsabfrage" width="17">&nbsp;
				          <img src="graphics/layer.png" alt="Themensteuerung" title="Themensteuerung" width="20" height="20"><br>
									<input type="hidden" name="nurFremdeLayer" value="<? echo $this->formvars['nurFremdeLayer']; ?>">
				          <div id="legend_div"><? echo $this->legende; ?></div>
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
<input type="hidden" name="selected_layer_id" value="<? echo $this->formvars['selected_layer_id']; ?>">
<input type="hidden" name="selected_class_id" value="<? echo $this->formvars['selected_class_id']; ?>">
<input type="hidden" name="selected_style_id" value="<? echo $this->formvars['selected_style_id']; ?>">
<input type="hidden" name="selected_label_id" value="<? echo $this->formvars['selected_label_id']; ?>">
<input type="hidden" name="go" value="Style_Label_Editor">
<script type="text/javascript">
<!--
browser_check();
-->
</script>


