<?php
	include(LAYOUTPATH . 'languages/datendrucklayouts_' . rolle::$language . '.php');
	include(SNIPPETS . 'sachdatenanzeige_functions.php');
	include_once(CLASSPATH . 'FormObject.php');	
?>

<style>
	select{
		width: 150px;
	}
</style>

<script type="text/javascript">
	var counter = 0;
	var fonts = ['<? echo implode("','", array_map(function ($entry) {return $entry["value"];}, $this->ddl->fonts)); ?>'];
	var attributes = ['', '<? echo implode("','", $this->ddl->attributes["name"] ?: []); ?>'];

	function input_check_num(field){
		field.value = field.value.replace(/[^(0-9| |\.|,|\-)]/g, '');
		field.value = field.value.replace(/,/g, '.');
	}

	function show_select(input, options){
		var parent = input.parentNode;
		var value = input.value;
		var select = '<select name="'+input.name+'" onchange="hide_select(this, \''+options+'\')">';
		if(options == 'fonts'){
			options_array = fonts;
		}
		else{
			options_array = attributes;
		}
		options_array.forEach(function(value){select = select + '<option value="'+value+'">'+value+'</option>'});
		select = select + '</select>';
		parent.innerHTML = select;
		parent.firstChild.value = value;
	}

	function hide_select(select, options){
		var parent = select.parentNode;
		parent.innerHTML = '<input type="text" onmouseenter="show_select(this, \''+options+'\')" name="'+select.name+'" value="'+select.value+'">';
	}

	function highlight_line(id){
		var form = document.getElementById('line_form_'+id);
		form.classList.add('short_highlight');
		svg_line = document.getElementById('line_'+id)
		if(svg_line){	
			svg_line.classList.add('line_highlight');
		}
	}

	function de_highlight_line(id){
		var form = document.getElementById('line_form_'+id);
		form.classList.remove('short_highlight');
		svg_line = document.getElementById('line_'+id)
		if(svg_line){	
			svg_line.classList.remove('line_highlight');
		}
	}

	function jump_to_line(id){
		var form = document.getElementById('line_form_'+id);
		form.scrollIntoView({behavior: 'smooth'});
	}

	function highlight_rect(id){
		var form = document.getElementById('rect_form_'+id);
		form.classList.add('short_highlight');
		svg_rect = document.getElementById('rect_'+id)
		if(svg_rect){	
			svg_rect.classList.add('line_highlight');
		}
	}

	function de_highlight_rect(id){
		var form = document.getElementById('rect_form_'+id);
		form.classList.remove('short_highlight');
		svg_rect = document.getElementById('rect_'+id)
		if(svg_rect){	
			svg_rect.classList.remove('line_highlight');
		}
	}

	function jump_to_rect(id){
		var form = document.getElementById('rect_form_'+id);
		form.scrollIntoView({behavior: 'smooth'});
	}

	function image_coords(event){
		document.getElementById('coords').style.visibility='';
		var offset = 0;
		var pointer_div = document.getElementById("preview_div");
		var height = parseInt(pointer_div.style.height);
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
		if(pos_y > height - 140){
			offset = 130;
		}
		document.getElementById("coords").style.left = pos_x+7;
		document.getElementById("coords").style.top = pos_y-offset;
		document.getElementById("posx").value = pos_x;
		document.getElementById("posy").value = height - pos_y;
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

	function addfreetext(layer_id, ddl_id){
		var posx = '', posy = '', font = '', size = '';
		if(document.getElementsByName('textposx[]').length > 0){
			posx = [].slice.call(document.getElementsByName('textposx[]')).pop().value;
			posy = [].slice.call(document.getElementsByName('textposy[]')).pop().value;
			font = [].slice.call(document.getElementsByName('textfont[]')).pop().value;
			size = [].slice.call(document.getElementsByName('textsize[]')).pop().value;
		}
		ahah('index.php?go=sachdaten_druck_editor_Freitexthinzufuegen&selected_layer_id='+layer_id+'&aktivesLayout='+ddl_id+'&posx='+posx+'&posy='+posy+'&size='+size+'&font='+font, '', new Array(document.getElementById('add_freetext')), new Array('prependhtml'));
		document.GUI.textcount.value = document.GUI.textcount.value + 1;
	}

	function addline(){
		document.GUI.go.value = 'sachdaten_druck_editor_Liniehinzufuegen';
		document.GUI.submit();
	}

	function addrect(){
		document.GUI.go.value = 'sachdaten_druck_editor_Rechteckhinzufuegen';
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

	function toggle_margin(label_elm, abstand_elm_id) {
		let elms = $('#margin_' + abstand_elm_id + ', label[for="margin_' + abstand_elm_id + '"]');
		if (label_elm.value.length > 0) {
			elms.show();
		}
		else {
			elms.hide();
		}
	}

	function for_other_layer(){
		other_layer_select = document.getElementById('other_layer_select');
		other_layer_select.classList.toggle('hidden');
		other_layer_select.disabled = !other_layer_select.disabled;
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

	function scrolltop(){
		document.getElementById('datendrucklayouteditor_formular_scroll').scrollTop = 0;
	}
</script>

<br>
<input type="hidden" name="go" value="sachdaten_druck_editor">

<h2><?php echo $this->titel; ?></h2><?
	if ($this->formvars['selected_layer_id']) { ?>
		<a
			style="float: right; margin-top: -20px; margin-right: 8px;"
			href="javascript:document.getElementById('linien').scrollIntoView({block: 'start', behavior: 'smooth'});"
			title="zu den Linien"
		>
			<i style="padding: 6px;" class="fa fa-minus buttonlink" aria-hidden="true"></i>
		</a>
		<a
			style="float: right; margin-top: -20px; margin-right: 8px;"
			href="javascript:document.getElementById('freitexte').scrollIntoView({block: 'start', behavior: 'smooth'});"
			title="zu den Freitexten"
		>
			<i style="padding: 6px;" class="fa fa-font buttonlink" aria-hidden="true"></i>
		</a>
		<a style="float: right; margin-top: -20px; margin-right: 8px;" href="javascript:scrolltop();"	title="nach oben">
			<i style="padding: 6px;" class="fa fa-arrow-up buttonlink" aria-hidden="true"></i>
		</a><?php
	}
	if ($this->ddl->fehlermeldung != '') { ?>
		<script>
			message([{ type: 'error', msg: '<? echo $this->ddl->fehlermeldung; ?>' }]);
		</script><?
	}
	
	if($this->formvars['page'] == NULL)$this->formvars['page'] = 0;
?>
<div id="datendrucklayouteditor">
	<div id="datendrucklayouteditor_vorschau">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="8" align="center">
					<? if($this->previewfile){ ?>
						<div id="preview_div" align="left" onmouseenter="document.getElementById('preview_page').style.visibility='';" onmouseleave="document.getElementById('preview_page').style.visibility='hidden';document.getElementById('coords').style.visibility='hidden';" onmousemove="image_coords(event)" style="position: relative; border:1px solid black;width:<? echo $this->ddl->selectedlayout[0]['width']; ?>px;height:<? echo $this->ddl->selectedlayout[0]['height']; ?>px;background-repeat: no-repeat;background-image:url('<? echo $this->previewfile; ?>');">
							<div id="preview_page" style="background-color: white; visibility: hidden; margin: auto; width: 150px; padding: 10px; position: relative; z-index: 2">
									<span class="fett">Vorschau:</span>
								<?
									echo FormObject::createSelectField(
										'page',
										array(
											array('value' => 0, 'output' => 'Seite 1'),
											array('value' => 1, 'output' => 'Seite 2'),
											array('value' => 2, 'output' => 'Seite 3')
										),
										$this->formvars['page'],
										1,
										'',
										'document.getElementById(\'save_submit_button\').click();'
									);
								?>
							</div>
							<div style="position: absolute; top: 0px; z-index: 1">
								<svg xmlns="http://www.w3.org/2000/svg" width="<? echo $this->ddl->selectedlayout[0]['width']; ?>" height="<? echo $this->ddl->selectedlayout[0]['height']; ?>">
									<style type="text/css"><![CDATA[
										.line{
											stroke: steelblue;
											fill: steelblue;
											stroke-width: 6;
											opacity: 0.01;
										}

										.line_highlight{
											opacity: 0.6;
										}

									]]></style>
									<g transform="translate(0, <? echo $this->ddl->selectedlayout[0]['height']; ?>) scale(1, -1)">
								<?
									if($this->lines){
										$this->lines = array_values($this->lines);
										$lines = $this->lines[$this->formvars['page']];
										for($l = 0; $l < count($lines); $l++){
											echo '<line id="line_'.$lines[$l]['id'].'" x1="'.$lines[$l]['x1'].'" y1="'.$lines[$l]['y1'].'" x2="'.$lines[$l]['x2'].'" y2="'.$lines[$l]['y2'].'" class="line" onmouseenter="highlight_line('.$lines[$l]['id'].')" onmouseleave="de_highlight_line('.$lines[$l]['id'].')" onclick="jump_to_line('.$lines[$l]['id'].')"/>';
										}
									}
									
									if($this->rectangles){
										$this->rectangles = array_values($this->rectangles);
										$rectangles = $this->rectangles[$this->formvars['page']];
										if (is_array($rectangles)) {
											for ($l = 0; $l < count($rectangles); $l++){
												echo '<rect id="rect_'.$rectangles[$l]['id'].'" x="'.$rectangles[$l]['x1'].'" y="'.$rectangles[$l]['y1'].'" width="'.$rectangles[$l]['x2'].'" height="'.$rectangles[$l]['y2'].'" class="line" onmouseenter="highlight_rect('.$rectangles[$l]['id'].')" onmouseleave="de_highlight_rect('.$rectangles[$l]['id'].')" onclick="jump_to_rect('.$rectangles[$l]['id'].')"/>';
											}
										}
									}
								?>
									</g>
								</svg>
							</div>
							<div id="coords" style="background-color: white;width:65px;visibility: hidden;position:relative;border: 1px solid black">
								&nbsp;x:&nbsp;<input type="text" id="posx" size="2" style="border:none"><br>
								&nbsp;y:&nbsp;<input type="text" id="posy" size="2" style="border:none">
							</div>
						</div>
					<? } ?>
			</tr>
				</td>
			</tr>
		</table>
	</div>
	<div id="datendrucklayouteditor_formular">
		<div id="datendrucklayouteditor_formular_scroll">
			<table border="0" cellspacing="0" cellpadding="0" style="width: 100%">
				<tr>
					<td>
						<table width="597" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
							<tr>
								<td colspan="2" class="fett" align="center" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3">&nbsp;Themen-Auswahl</td>
							</tr>
							<tr>
								<td> 
									<select style="width:250px" size="1"	name="selected_layer_id" onchange="if(document.GUI.aktivesLayout != undefined)document.GUI.aktivesLayout.value='';document.GUI.submit();" <?php if(count($this->layerdaten['ID'])==0){ echo 'disabled';}?>>
										<option value="">--- bitte wählen ---</option><?
										for($i = 0; $i < count($this->layerdaten['ID']); $i++){
											echo '<option';
											if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
												echo ' selected';
											}
											echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
										} ?>
									</select>
								</td>
								<td align="center">
								<? if($this->formvars['selected_layer_id'] != ''){ ?>
									<input type="submit" name="go_plus" value="Layout automatisch erzeugen">
								<? } ?>
								</td>
							</tr>
						</table>
					</td>
				</tr><?
				if ($this->formvars['selected_layer_id']) { ?>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							 <table width="597" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td class="fett" colspan="2" align="center" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3">&nbsp;Layout-Auswahl</td>
									<td class="fett" align="center" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;Stelle</td>
								</tr>
								<tr>
									<td colspan=1>
										<select	name="aktivesLayout" style="width:250px" onchange="document.GUI.submit()">
										<option value="">--- bitte wählen ---</option>
										<?	
										for($i = 0; $i < count($this->ddl->layouts); $i++){
											echo ($this->formvars['aktivesLayout']<>$this->ddl->layouts[$i]['id']) ? '<option value="'.$this->ddl->layouts[$i]['id'].'">'.$this->ddl->layouts[$i]['name'].' ('.$this->ddl->layouts[$i]['id'].')</option>' : '<option value="'.$this->ddl->layouts[$i]['id'].'" selected>'.$this->ddl->layouts[$i]['name'].' ('.$this->ddl->layouts[$i]['id'].')</option>';
										}
										?>
									</select> 
									</td>
									<td>
										<input type="submit" name="go_plus" value="übernehmen >>">
									</td>
									<td style="border-left:1px solid #C3C7C3">
										<select	name="stelle" style="width:250px">
										<option value="">--- bitte wählen ---</option>
											<?
											for($i = 0; $i < count_or_0($this->stellendaten['ID']); $i++){
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
					</tr> 
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<table width="597" border="0" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td colspan="4" class="fett" align="center" style="border-top:1px solid #C3C7C3;border-bottom:1px solid #C3C7C3">&nbsp;Layoutdaten</td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Name:</span>
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<input type="text" name="name" value="<? echo $this->ddl->selectedlayout[0]['name'] ?>" size="35">
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Typ:</span>
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<select name="type" onchange="update_options();">
											<option value="0" <? if($this->ddl->selectedlayout[0]['type'] == 0)echo 'selected' ?>>neue Seite für jeden Datensatz</option>
											<option value="1" <? if($this->ddl->selectedlayout[0]['type'] == 1)echo 'selected' ?>>Datensätze fortlaufend</option>
											<option value="2" <? if($this->ddl->selectedlayout[0]['type'] == 2)echo 'selected' ?>>eingebettet</option>
										</select>
									</td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Dateiname:</span>&nbsp;
										<span style="--left: 0px" data-tooltip="Hier kann der Name der erzeugten PDF-Datei angegeben werden.&#xa;Im Dateinamen können auch Attribute in der Form ${&lt;attributname&gt;} und die Schlüsselwörter $user, $stelle und $date verwendet werden, wodurch der Dateiname dynamisch wird. Wird kein Dateiname angegeben, erhält die PDF-Datei einen automatisch generierten Namen."></span>
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<input type="text" name="filename" value="<? echo $this->ddl->selectedlayout[0]['filename'] ?>" size="35">
										<div style="position:relative">
											<div id="Tip2" style="visibility:hidden;position:absolute;bottom:20px;z-index:1000;"></div>
										</div>
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Format:</span>
									</td>
									<td style="border-bottom:1px solid #C3C7C3">
										<select name="format">
											<option value="A4 hoch" <? if($this->ddl->selectedlayout[0]['format'] == 'A4 hoch')echo 'selected' ?>>A4 hoch</option>
											<option value="A4 quer" <? if($this->ddl->selectedlayout[0]['format'] == 'A4 quer')echo 'selected' ?>>A4 quer</option>
											<option value="A3 hoch" <? if($this->ddl->selectedlayout[0]['format'] == 'A3 hoch')echo 'selected' ?>>A3 hoch</option>
											<option value="A3 quer" <? if($this->ddl->selectedlayout[0]['format'] == 'A3 quer')echo 'selected' ?>>A3 quer</option>
										</select>
									</td>
								</tr>
								<tr id="list_type_options" style="display:<? if($this->ddl->selectedlayout[0]['type'] == 0)echo 'none' ?>">
									<td colspan="4" style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Datensätze:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fett">Abstand:</span>
										<input type="text" name="gap" title="Der Abstand zwischen den Datensätzen." value="<? echo $this->ddl->selectedlayout[0]['gap'] ?>" size="2">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fett">in Spalten anordnen:</span>
										<input type="checkbox" name="columns" title="" value="1" <? if($this->ddl->selectedlayout[0]['columns'])echo 'checked="true"'; ?>>
										<br><span style="margin-left: 90px" class="fett">nicht durch Seitenumbruch unterbrechen:</span>
										<input type="checkbox" name="no_record_splitting" title="Wenn angehakt, wird ein Seitenumbruch nicht innerhalb eines Datensatzes gemacht, sondern davor." value="1" <? if($this->ddl->selectedlayout[0]['no_record_splitting']) echo 'checked'; ?>>
									</td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid #C3C7C3">
										<span class="fett">Ränder:</span>
									</td>
									<td colspan="3" style="border-bottom:1px solid #C3C7C3">
									oben:
										<input type="text" name="margin_top" value="<? echo $this->ddl->selectedlayout[0]['margin_top'] ?>" size="2">&nbsp;&nbsp;
									unten:
										<input type="text" name="margin_bottom" value="<? echo $this->ddl->selectedlayout[0]['margin_bottom'] ?>" size="2">&nbsp;&nbsp;
									links:
										<input type="text" name="margin_left" value="<? echo $this->ddl->selectedlayout[0]['margin_left'] ?>" size="2">&nbsp;&nbsp;
									rechts:
										<input type="text" name="margin_right" value="<? echo $this->ddl->selectedlayout[0]['margin_right'] ?>" size="2">&nbsp;&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label for="dont_print_empty" class="fett">
											Leere Attributwerte nicht drucken:
										</label>
										<input id="dont_print_empty" type="checkbox" name="dont_print_empty" value="1"<?
											echo ($this->ddl->selectedlayout[0]['dont_print_empty'] == '1' ? ' checked="true"' : ''); ?>
										/>
									</td>
									<td colspan="3">
										<label for="use_previews" class="fett">
											Vorschaubilder verwenden statt Originale:
										</label>
										<input id="use_previews" type="checkbox" name="use_previews" value="1"<?
											echo ($this->ddl->selectedlayout[0]['use_previews'] == '1' ? ' checked="true"' : ''); ?>
										/>
										<span data-tooltip="Kann die Größe der zu druckenden PDF-Dokumente deutlich verringern." style="--left: -400px"></span>
									</td>
								</tr>
							</table>
							<br>
							<table width="597" border="0" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td class="fett" align="center" style="border-bottom:2px solid #C3C7C3;border-top:2px solid #C3C7C3" colspan="8">&nbsp;Grafik&nbsp;</td>
								</tr>
								<tr>
									<td width="50%" style="border-right:1px solid #C3C7C3" colspan=4>&nbsp;<? echo $this->ddl->selectedlayout[0]['bgsrc'] ?></td>
									<td>&nbsp;x:</td>
									<td style="border-right:1px solid #C3C7C3"><input type="text" name="bgposx" value="<? echo $this->ddl->selectedlayout[0]['bgposx'] ?>" size="5"></td>
									<td>&nbsp;Breite:</td>
									<td><input type="text" name="bgwidth" value="<? echo $this->ddl->selectedlayout[0]['bgwidth'] ?>" size="5"></td>
								</tr>
								<tr>
									<td width="50%" style="border-right:1px solid #C3C7C3" colspan=4><input type="file" name="bgsrc" size="10"></td>
									<td>&nbsp;y:</td>
									<td style="border-right:1px solid #C3C7C3"><input type="text" name="bgposy" value="<? echo $this->ddl->selectedlayout[0]['bgposy'] ?>" size="5"></td>
									<td>&nbsp;Höhe:</td>
									<td><input type="text" name="bgheight" value="<? echo $this->ddl->selectedlayout[0]['bgheight'] ?>" size="5"></td>
								</tr>
							</table>
							<br>
							<table border="0" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td align="center" style="border-top:2px solid #C3C7C3" colspan=8><span class="fett">&nbsp;Attribute</span></td>
								</tr><?
								if ($this->formvars['selected_layer_id'] != '') {
									for ($i = 0; $i < count($this->ddl->attributes['type']); $i++){
										if ($this->ddl->attributes['type'][$i] != 'geometry'){
											if ($this->ddl->attributes['alias'][$i] == '') {
												$this->ddl->attributes['alias'][$i] = $this->ddl->attributes['name'][$i];
											} ?>
											<tr>
												<td class="fett" align="left" style="border-top:2px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->ddl->attributes['name'][$i]; ?>');"><?
												if ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == '') { ?>
													<img id="img_<? echo $this->ddl->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;<? echo $this->ddl->attributes['alias'][$i].' ($'.$this->ddl->attributes['name'][$i].')'; }
												else { ?>
													<img id="img_<? echo $this->ddl->attributes['name'][$i]; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;<? echo $this->ddl->attributes['alias'][$i].' ($'.$this->ddl->attributes['name'][$i].')';
												} ?>
												</td>
											</tr><?
											switch ($this->ddl->attributes['form_element_type'][$i]){ 
												case 'SubFormPK' : case 'SubFormEmbeddedPK' : {
													$subformlayouts = $this->ddl->load_layouts(NULL, NULL, $this->ddl->attributes['subform_layer_id'][$i], array(2)); ?>
													<tr id="tr1_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td style="border-top:1px solid #C3C7C3">&nbsp;&nbsp;&nbsp;x:</td>
														<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">
															<input type="text" name="posx_<? echo $this->ddl->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos']; ?>" size="5">
														</td>
														<td style="border-top:1px solid #C3C7C3" align="left" colspan="5" align="center">
															&nbsp;Druckrahmen:&nbsp;
															<select title="Druckrahmen" name="font_<? echo $this->ddl->attributes['name'][$i]; ?>">
																<option value=""> - Bitte wählen - </option><?
																# die font-Spalte wird hier zum Speichern des eingebetteten Layouts genutzt
																for ($j = 0; $j < count($subformlayouts); $j++){
																	echo '<option ';
																	if ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['font'] == $subformlayouts[$j]['id']){
																		echo 'selected ';
																	}
																	echo 'value="'.$subformlayouts[$j]['id'].'">'.$subformlayouts[$j]['name'].'</option>';
																} ?>
															</select>
														</td>
													</tr>
													<tr id="tr2_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td>&nbsp;&nbsp;&nbsp;y:</td>
														<td style="border-right:1px solid #C3C7C3"><input type="text" name="posy_<? echo $this->ddl->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['ypos']; ?>" size="5"></td>							
														<td width="60px">&nbsp;unterhalb&nbsp;von:</td>
														<td>
															<select name="offset_attribute_<? echo $this->ddl->attributes['name'][$i]; ?>">
																<option value="">- Auswahl -</option>
																<?
																for($j = 0; $j < count($this->ddl->attributes['name']); $j++){
																	if($this->ddl->attributes['name'][$j] != $this->ddl->attributes['name'][$i]){
																		echo '<option ';
																		if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['offset_attribute'] == $this->ddl->attributes['name'][$j]){
																			echo 'selected ';
																		}
																		echo 'value="'.$this->ddl->attributes['name'][$j].'">'.$this->ddl->attributes['name'][$j].'</option>';
																	}
																}
																?>
															</select>
														</td>
													</tr><?
												} break;
						
												case 'Dokument' : { ?>
													<tr id="tr1_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td>&nbsp;&nbsp;&nbsp;x:</td>
														<td><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->ddl->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos']; ?>" size="5"></td>
														<td width="60px">&nbsp;Breite:</td>
														<td><input	type="text" name="width_<? echo $this->ddl->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['width']; ?>" size="5"></td>
														<td colspan="3"></td>
													</tr>
													<tr id="tr2_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td>&nbsp;&nbsp;&nbsp;y:</td>
														<td><input type="text" name="posy_<? echo $this->ddl->attributes['name'][$i]; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['ypos']; ?>" size="5"></td>
														<td colspan="5">&nbsp;</td>
													</tr><?
												} break;
										
												default : {	?>
													<tr id="tr1_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td style="border-top:1px solid #C3C7C3">
															<label
																for="posx_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitlePosx; ?>"
															>x:</label>
														</td>
														<td style="border-top:1px solid #C3C7C3">
															<input
																id="posx_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="number"
																min="-596"
																max="596"
																title="<? echo $strTitlePosx; ?>"
																name="posx_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos']; ?>"
																size="5">
														</td>
														<td style="border-top:1px solid #C3C7C3; width: 60px; text-align: left;">
															<label
																for="width_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleWidth; ?>"
															>Breite:</label>
														</td>
														<td style="border-top:1px solid #C3C7C3;">
															<input
																id="width_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="number"
																min="0"
																max="596"
																title="<? echo $strTitleWidth; ?>"
																name="width_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['width']; ?>"
																size="5"
															><label
																for="fontsize_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleFontsize; ?>"
																style="margin-left: 8px"
															>Größe:</label><input
																id="fontsize_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="number"
																min="1"
																max="100"
																title="<? echo $strTitleFontsize; ?>"
																name="fontsize_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['fontsize']; ?>"
																size="2"
																style="margin-left: 2px; width: 45px"
															>
														</td>
														<td style="border-top:1px solid #C3C7C3;">
															<label
																for="font_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleFont; ?>"
															>
																Font:
															</label>
														</td>
														<td style="border-top:1px solid #C3C7C3;">
															<input
																id="font_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="text"
																title="<? echo $strTitleFont; ?>"
																onmouseenter="show_select(this, 'fonts')"
																name="font_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['font']; ?>"
																style="width: 150px"
															>
														</td>
														<td style="border-top:1px solid #C3C7C3">
															<label
																for="border_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleBorder; ?>"
															>
																Rahmen:
															</label>
														</td>
														<td style="border-top:1px solid #C3C7C3; border-right:1px solid #C3C7C3; width: 50px">
															<input
																id="border_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="checkbox"
																title="<? echo $strTitleBorder; ?>"
																name="border_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="1"
																style="margin-left: 0px;"<?
																echo ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['border'] == '1' ? ' checked="true"' : ''); ?>
															>
														</td>
													</tr>
													<tr id="tr2_<? echo $this->ddl->attributes['name'][$i]; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['xpos'] == ''){echo 'style="display:none"';} ?>>
														<td>
															<label
																for="posy_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitlePosy; ?>"
															>y:</label>
														</td>
														<td>
															<input
																id="posy_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="number"
																min="0"
																max="5000"
																title="<? echo $strTitlePosy; ?>"
																name="posy_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['ypos']; ?>"
																size="5"
															>
														</td>
														<td style="width: 60px; text-align: right;">
															<label
																for="offset_attribute_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleOffset_attribute; ?>"
															>
																unterhalb&nbsp;von:
															</label>
														</td>
														<td align="left" align="center">
															<input
																id="offset_attribute_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="text"
																title="<? echo $strTitleOffset_attribute; ?>"
																onmouseenter="show_select(this, 'attributes')"
																name="offset_attribute_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['offset_attribute']; ?>"
																style="width: 150px"
															>
														</td>
														<td>
															<label
																for="label_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleLabel; ?>"
															>
																Label:
															</label>
														</td>
														<td>
															<input
																id="label_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="text"
																title="<? echo $strTitleLabel; ?>"
																name="label_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['label']; ?>"
																onKeyUp="toggle_margin(this, '<? echo $this->ddl->attributes['name'][$i]; ?>');"
																style="width: 150px"
															/>
														</td>
														<td>
															<label
																for="margin_<? echo $this->ddl->attributes['name'][$i]; ?>"
																title="<? echo $strTitleMargin; ?>"
																style="display: <? echo ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['label'] != '' ? 'block' : 'none');?>"
															>
																Abstand:
															</label>
														</td>
														<td style="border-right:1px solid #C3C7C3;">
															<input
																id="margin_<? echo $this->ddl->attributes['name'][$i]; ?>"
																type="number"
																min="0"
																max="596"
																title="<? echo $strTitleMargin; ?>"
																name="margin_<? echo $this->ddl->attributes['name'][$i]; ?>"
																value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['margin']; ?>"
																size="4"
																style="width: 50px; display: <? echo ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['name'][$i]]['label'] != '' ? 'block' : 'none');?>"
															>
														</td>
													</tr><?
												}
											}
										}	
									}
									if ($this->ddl->attributes['the_geom'] != '') { ?>
										<tr>
											<td class="fett" align="left" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="8" onclick="toggle('<? echo $this->ddl->attributes['the_geom']; ?>');">
											<? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['xpos'] == ''){ ?>
												<img id="img_<? echo $this->ddl->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'plus.gif'?>">&nbsp;Geometrie
											<? }else{ ?>
												<img id="img_<? echo $this->ddl->attributes['the_geom']; ?>" src="<? echo GRAPHICSPATH.'minus.gif'?>">&nbsp;Geometrie
											<? } ?>
											</td>
										</tr>
										<tr id="tr1_<? echo $this->ddl->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
											<td>&nbsp;&nbsp;&nbsp;x:</td>
											<td><input type="text" title="negative Werte bewirken eine rechtsbündige Ausrichtung" name="posx_<? echo $this->ddl->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['xpos']; ?>" size="5"></td>
											<td width="60px">&nbsp;Breite:</td>
											<td><input	type="text" name="width_<? echo $this->ddl->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['width']; ?>" size="5"></td>
											<td>
												<label for="border_<? echo $this->ddl->attributes['the_geom']; ?>">
														hervorheben:
												</label>
											</td>
											<td>
												<input
													id="border_<? echo $this->ddl->attributes['the_geom']; ?>"
													type="checkbox"
													name="border_<? echo $this->ddl->attributes['the_geom']; ?>"
													value="1"
													style="margin-left: 0px;"<?
													echo ($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['border'] == '1' ? ' checked="true"' : ''); ?>
												>
											</td>
											<td></td>
										</tr>
										<tr id="tr2_<? echo $this->ddl->attributes['the_geom']; ?>" <? if($this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['xpos'] == ''){echo 'style="display:none"';} ?>>
											<td>&nbsp;&nbsp;&nbsp;y:</td>
											<td><input type="text" name="posy_<? echo $this->ddl->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['ypos']; ?>" size="5"></td>
											<td width="60px">&nbsp;Rand:</td>
											<td><input	type="text" name="fontsize_<? echo $this->ddl->attributes['the_geom']; ?>" value="<? echo $this->ddl->selectedlayout[0]['elements'][$this->ddl->attributes['the_geom']]['fontsize']; ?>" size="5">m</td>
											<td colspan="3"></td>
										</tr><?
									}
								} ?>
							</table>
							<br>
							<table width="597" border=0 cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">	
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
							<table width="597" border=0 cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">
										<span id="freitexte">Freitexte</span>&nbsp;
										<span data-tooltip="In Freitexten können folgende Schlüsselwörter verwendet werden, die dann durch andere Texte ersetzt werden:&#xa;- $stelle: die aktuelle Stellenbezeichung&#xa;- $user: der Name des Nutzers&#xa;- $pagenumber: die aktuelle Seitennummer  (Platzierung 'auf jeder Seite' erforderlich)&#xa;- $pagecount: die Gesamtseitenzahl  (Platzierung 'auf jeder Seite' erforderlich)&#xa;- ${&lt;attributname&gt;}: der Wert des Attributs"></span>
									</td>
								</tr>
						<?  echo $this->ddl->output_freetext_form($this->ddl->selectedlayout[0]['texts'], $this->formvars['selected_layer_id'], $this->formvars['aktivesLayout']); ?>
								<tr id="add_freetext">
									<td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;<a href="javascript:addfreetext(<? echo $this->formvars['selected_layer_id']; ?>, <? echo $this->formvars['aktivesLayout']; ?>);">Freitext hinzufügen</a></td>
								</tr>				
							</table>
							<br>
							<table width="597" border=0 cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">
										<span id="linien">Linien</span>
									</td>
								</tr><?
									for ($i = 0; $i < ($this->formvars['nolines'] == '1' ? 0 : count_or_0($this->ddl->selectedlayout[0]['lines'])); $i++) { ?>
										<tbody
											id="line_form_<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id']; ?>"
											onmouseenter="highlight_line(<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id']; ?>)"
											onmouseleave="de_highlight_line(<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id']; ?>)"
										>
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
												<td colspan="2">Breite:&nbsp;<input type="text" oninput="input_check_num(this);" name="breite<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['breite'] ?>" size="5"></td>
											</tr>
											<tr>
												<td>&nbsp;y:</td>
												<td style="border-right:1px solid #C3C7C3"><input type="text" name="lineposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['posy'] ?>" size="5"></td>
												<td>&nbsp;y:</td>
												<td style="border-right:1px solid #C3C7C3"><input type="text" name="lineendposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['endposy'] ?>" size="5"></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td colspan="2" valign="bottom" style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
												<td colspan="2" valign="bottom" style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
												<td colspan="2" valign="bottom">&nbsp;Platzierung:</td>
											</tr>
											<tr>
												<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
													<input type="text" onmouseenter="show_select(this, 'attributes')" name="lineoffset_attribute_start<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['offset_attribute_start']; ?>">
												</td>
												<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
													<input type="text" onmouseenter="show_select(this, 'attributes')" name="lineoffset_attribute_end<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['lines'][$i]['offset_attribute_end']; ?>">
												</td>
												<td align="left" valign="top">
													<select style="width: 110px" name="linetype<? echo $i ?>">
														<option value="0">normal</option>
														<? if($this->ddl->selectedlayout[0]['type'] != 0){ ?>
														<option value="1" <? if($this->ddl->selectedlayout[0]['lines'][$i]['type'] == 1)echo ' selected '; ?>>fixiert</option>
														<? } ?>
														<option value="2" <? if($this->ddl->selectedlayout[0]['lines'][$i]['type'] == 2)echo ' selected '; ?>>auf jeder Seite</option>
														<option value="3" <? if($this->ddl->selectedlayout[0]['lines'][$i]['type'] == 3)echo ' selected '; ?>>ab der 2. Seite auf jeder Seite</option>
													</select>
												</td>
												<td align="right">
													<a href="javascript:Bestaetigung('index.php?go=sachdaten_druck_editor_Linieloeschen&line_id=<? echo $this->ddl->selectedlayout[0]['lines'][$i]['id'] ?>&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&aktivesLayout=<? echo $this->formvars['aktivesLayout']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>', 'Wollen Sie die Linie wirklich löschen?');">löschen&nbsp;</a>
												</td>
											</tr>
										</tbody><?
									} ?>
								<tr>
									<td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;<a href="javascript:addline();">Linie hinzufügen</a></td>
								</tr>
							</table>
							<br>
							<table width="597" border=0 cellpadding="3" cellspacing="0" style="border-bottom:1px solid #C3C7C3">
								<tr>
									<td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">
										<span id="rechtecke">Rechtecke</span>
									</td>
								</tr>
								<? for($i = 0; $i < count_or_0($this->ddl->selectedlayout[0]['rectangles']); $i++){
									 ?>
									<tbody id="rect_form_<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['id']; ?>" onmouseenter="highlight_rect(<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['id']; ?>)" onmouseleave="de_highlight_rect(<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['id']; ?>)">
									<tr>
										<td colspan="2" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">Start<input type="hidden" name="rect_id<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['id'] ?>"></td>
										<td colspan="2" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">Ende</td>
										<td colspan="2" style="border-top:2px solid #C3C7C3"></td>
									</tr>
									<tr>
										<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
										<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="rectposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['posx'] ?>" size="5"></td>
										<td style="border-top:2px solid #C3C7C3">&nbsp;x:</td>
										<td style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="rectendposx<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['endposx'] ?>" size="5"></td>
										<td colspan="2">Linienbreite:&nbsp;<input type="text" oninput="input_check_num(this);" name="rectbreite<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['breite'] ?>" size="5"></td>
									</tr>
									<tr>
										<td>&nbsp;y:</td>
										<td style="border-right:1px solid #C3C7C3"><input type="text" name="rectposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['posy'] ?>" size="5"></td>
										<td>&nbsp;y:</td>
										<td style="border-right:1px solid #C3C7C3"><input type="text" name="rectendposy<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['endposy'] ?>" size="5"></td>
										<td colspan="2">
											Füllfarbe:
											<?											
												$selected_color = $this->ddl->colors[$this->ddl->selectedlayout[0]['rectangles'][$i]['color']];
												$bgcolor = $selected_color['red'].', '.$selected_color['green'].', '.$selected_color['blue'];
											?>
											<select name="rectcolor<? echo $i ?>" style="background-color: rgb(<? echo $bgcolor; ?>)" onchange="this.setAttribute('style', this.options[this.selectedIndex].getAttribute('style'));">
												<option value=""> - keine - </option>
												<?
												foreach($this->ddl->colors as $color){
													echo '<option ';
													if($selected_color['id'] == $color['id']){
														echo ' selected';
													}
													echo ' style="background-color: rgb('.$color['red'].', '.$color['green'].', '.$color['blue'].')"';
													echo ' value="'.$color['id'].'">';
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
													echo "</option>\n";
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="2" valign="bottom" style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
										<td colspan="2" valign="bottom" style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;unterhalb&nbsp;von:</td>
										<td colspan="2" valign="bottom">&nbsp;Platzierung:</td>
									</tr>
									<tr>
										<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
											<input type="text" onmouseenter="show_select(this, 'attributes')" name="rectoffset_attribute_start<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['offset_attribute_start']; ?>">
										</td>
										<td colspan="2" valign="top" style="border-right:1px solid #C3C7C3">
											<input type="text" onmouseenter="show_select(this, 'attributes')" name="rectoffset_attribute_end<? echo $i ?>" value="<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['offset_attribute_end']; ?>">
										</td>
										<td align="left" valign="top">
											<select style="width: 110px" name="recttype<? echo $i ?>">
												<option value="0">normal</option>
												<? if($this->ddl->selectedlayout[0]['type'] != 0){ ?>
												<option value="3" <? if($this->ddl->selectedlayout[0]['rectangles'][$i]['type'] == 3)echo ' selected '; ?>>alternierend</option>
												<option value="1" <? if($this->ddl->selectedlayout[0]['rectangles'][$i]['type'] == 1)echo ' selected '; ?>>fixiert</option>
												<? } ?>
												<option value="2" <? if($this->ddl->selectedlayout[0]['rectangles'][$i]['type'] == 2)echo ' selected '; ?>>auf jeder Seite</option>
											</select>
										</td>
										<td align="right">
											<a href="javascript:Bestaetigung('index.php?go=sachdaten_druck_editor_Rechteckloeschen&rect_id=<? echo $this->ddl->selectedlayout[0]['rectangles'][$i]['id'] ?>&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&aktivesLayout=<? echo $this->formvars['aktivesLayout']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>', 'Wollen Sie das Rechteck wirklich löschen?');">löschen&nbsp;</a>
										</td>
									</tr>
									</tbody>
								<? } ?>
								<tr>
									<td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;<a href="javascript:addrect();">Rechteck hinzufügen</a></td>
								</tr>
							</table>
						</td>
					</tr>
	<?		} ?>
			</table>
		</div>
<? if($this->formvars['selected_layer_id']){ ?>		
		<div style="margin-top: 10px">
<? if($this->ddl->selectedlayout[0]['name']){ ?>
			<input type="button" name="go_plus" value="Layout löschen" onclick="Bestaetigung('index.php?go=sachdaten_druck_editor_Löschen&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>&selected_layout_id=<? echo $this->ddl->selectedlayout[0]['id']; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>', 'Wollen Sie dieses Layout wirklich löschen?');">&nbsp;
			<input id="save_submit_button" type="submit" name="go_plus" value="Änderungen Speichern">&nbsp;
<? } ?>

			<input type="button" name="go_plus" onclick="save_layout();" value="als neues Layout speichern">
			<select style="width:240px" size="1" id="other_layer_select" name="selected_layer_id" title="Auswahl des anderen Layers" class="hidden" disabled>
				<option value="">--- bitte wählen ---</option><?
				for($i = 0; $i < count($this->layerdaten['ID']); $i++){
					echo '<option';
					if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
						echo ' selected';
					}
					echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
				} ?>
			</select>
			<i
				id="show_attributes_for_other_layer_button"
				title="Als neues Layout für anderen Layer speichern"
				class="fa fa-magic"
				aria-hidden="true"
				onclick="for_other_layer();"
			></i>

		</div>
<? } ?>
	</div>
</div><?php
	$layout_selected = $this->ddl->selectedlayout != NULL AND count($this->ddl->selectedlayout) > 0;
?>
<input type="hidden" name="textcount" value="<? echo ($layout_selected ? count($this->ddl->selectedlayout[0]['texts']) : 0); ?>">
<input type="hidden" name="linecount" value="<? echo ($layout_selected ? count($this->ddl->selectedlayout[0]['lines']) : 0); ?>">
<input type="hidden" name="rectcount" value="<? echo ($layout_selected ? count($this->ddl->selectedlayout[0]['rectangles']) : 0); ?>">
<input type="hidden" name="bgsrc_save" value="<? echo ($layout_selected ? $this->ddl->selectedlayout[0]['bgsrc'] : ''); ?>">

