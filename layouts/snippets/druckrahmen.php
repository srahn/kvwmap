<?
$parts = explode(' x ', $this->Document->din_formats[$this->Document->selectedframe[0]['format']]['size']);
$formatx = ltrim($parts[0], '(');
$formaty = rtrim($parts[1], ')');
$preview_height = round(595 * $formaty / $formatx);
?>

<script type="text/javascript">

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

	pos_x2 = Math.round(document.GUI.formatx.value * pos_x / 595);
	pos_y2 = Math.round(document.GUI.formaty.value - document.GUI.formaty.value * pos_y / <? echo $preview_height; ?>);

	document.getElementById("coords").style.left = pos_x+7;
	document.getElementById("coords").style.top = pos_y+7;
	document.getElementById("posx").value = pos_x2;
	document.getElementById("posy").value = pos_y2;
}

function updateheight(imagewidth, imageheight){
	ratio = imageheight/imagewidth;
	document.GUI.headheight.value = Math.round(document.GUI.headwidth.value * ratio);
}

function updatewidth(imagewidth, imageheight){
	ratio = imagewidth/imageheight;
	document.GUI.headwidth.value = Math.round(document.GUI.headheight.value * ratio);
}

function updaterefheight(imagewidth, imageheight){
	ratio = imageheight/imagewidth;
	document.GUI.refmapheight.value = Math.round(document.GUI.refmapwidth.value * ratio);
}

function updaterefwidth(imagewidth, imageheight){
	ratio = imagewidth/imageheight;
	document.GUI.refmapwidth.value = Math.round(document.GUI.refmapheight.value * ratio);
}

function updateformatinfo(){
	if(document.GUI.format.value == 'A5hoch'){
		document.GUI.formatx.value = '420';
		document.GUI.formaty.value = '595';
	}
	if(document.GUI.format.value == 'A5quer'){
		document.GUI.formatx.value = '595';
		document.GUI.formaty.value = '420';
	}
	if(document.GUI.format.value == 'A4hoch'){
		document.GUI.formatx.value = '595';
		document.GUI.formaty.value = '842';
	}
	if(document.GUI.format.value == 'A4quer'){
		document.GUI.formatx.value = '842';
		document.GUI.formaty.value = '595';
	}
	if(document.GUI.format.value == 'A3hoch'){
		document.GUI.formatx.value = '842';
		document.GUI.formaty.value = '1191';
	}
	if(document.GUI.format.value == 'A3quer'){
		document.GUI.formatx.value = '1191';
		document.GUI.formaty.value = '842';
	}
	if(document.GUI.format.value == 'A2hoch'){
		document.GUI.formatx.value = '1191';
		document.GUI.formaty.value = '1684';
	}
	if(document.GUI.format.value == 'A2quer'){
		document.GUI.formatx.value = '1684';
		document.GUI.formaty.value = '1191';
	}
	if(document.GUI.format.value == 'A1hoch'){
		document.GUI.formatx.value = '1684';
		document.GUI.formaty.value = '2384';
	}
	if(document.GUI.format.value == 'A1quer'){
		document.GUI.formatx.value = '2384';
		document.GUI.formaty.value = '1684';
	}
	if(document.GUI.format.value == 'A0hoch'){
		document.GUI.formatx.value = '2384';
		document.GUI.formaty.value = '3370';
	}
	if(document.GUI.format.value == 'A0quer'){
		document.GUI.formatx.value = '3370';
		document.GUI.formaty.value = '2384';
	}
}

function addfreetext(){
	document.GUI.go.value = 'Druckrahmen_Freitexthinzufuegen';
	document.GUI.submit();
}

function art_hide(){
	if(document.GUI.call.checked){
		document.getElementById('art_call').style.visibility = 'hidden';
		document.GUI.dhk_call.options[0].value = '';
		document.GUI.dhk_call.options[1].value = '';
	}
	else{
		document.getElementById('art_call').style.visibility = 'visible';
		document.GUI.dhk_call.options[0].value = '0110';
		document.GUI.dhk_call.options[1].value = '0120';
	}
}

</script>

<br>
<input type="hidden" name="go" value="Druckrahmen">

<h2><?php echo $this->titel; ?></h2>

<?php
	if ($this->Document->fehlermeldung != '') {
  echo "<script type=\"text/javascript\">
      <!--
        alert('".$this->Document->fehlermeldung."');
      //-->
      </SCRIPT>"
  ;
}

?>

<table border="0" cellspacing="2" cellpadding="0">
  <tr height="40px">
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td >
      <table border="0" width="100%" cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr height="50px">
          <td colspan="8" valign="top">
            <table border="0" width="100%">
              <tr>
                <td class="fett">&nbsp;Layout</td>
              </tr>
              <tr>
                <td>
                  &nbsp;<select style="width: 200px" name="aktiverRahmen" onchange="document.GUI.submit()">
                  <?
                  for($i = 0; $i < count($this->Document->frames); $i++){
                    echo ($this->formvars['aktiverRahmen']<>$this->Document->frames[$i]['id']) ? '<option value="'.$this->Document->frames[$i]['id'].'">'.$this->Document->frames[$i]['Name'].'  ('.$this->Document->frames[$i]['id'].')</option>' : '<option value="'.$this->Document->frames[$i]['id'].'" selected>'.$this->Document->frames[$i]['Name'].'  ('.$this->Document->frames[$i]['id'].')</option>';
                  }
                  ?>
                  </select>
                 </td>
								 <td align="center">
                  <input class="button" type="submit" name="go_plus" value="zu Stelle &raquo;">
                 </td>
								 <td align="right">
                  <select style="width: 260px" name="stelle">
                		<?
                		for($i = 0; $i < count($this->stellendaten['ID']); $i++){
      			    			echo '<option value="'.$this->stellendaten['ID'][$i].'" ';
      			    			if($this->formvars['stelle'] == $this->stellendaten['ID'][$i]){
      			    				echo 'selected';
      			    			}
      			    			echo '>'.$this->stellendaten['Bezeichnung'][$i].'</option>';
      			    		}
                		?>
                  </select>&nbsp;
                </td>
              </tr>
            </table>
          </td>
        </tr>
			<? if(defined('DHK_CALL_URL') AND DHK_CALL_URL != '') { ?>
				<tr height="50px">
          <td valign="middle" colspan="8" style="border-top:1px solid #C3C7C3">
            <table border="0">
              <tr>
                <td>
        	      <input type="checkbox" name="call" onmousedown="art_hide()" <? if($this->Document->selectedframe[0]['dhk_call'] != '') echo 'checked=true'; ?>>&nbsp;Zugriff auf DHK-Call-Schnittstelle
                </td>
                <td id="art_call" class="fett" <? if($this->Document->selectedframe[0]['dhk_call'] == '')echo 'style="visibility:hidden"'; ?>>
									&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;Art:
									<select style="width: 260px" name="dhk_call">
										<option value="<? if($this->Document->selectedframe[0]['dhk_call'] != '')echo '0110'; ?>" <? if($this->Document->selectedframe[0]['dhk_call'] == '0110')echo 'selected'; ?>>Liegenschaftskarte</option>
										<option value="<? if($this->Document->selectedframe[0]['dhk_call'] != '')echo '0120'; ?>" <? if($this->Document->selectedframe[0]['dhk_call'] == '0120')echo 'selected'; ?>>Liegenschaftskarte mit Bodenschätzung</option>
          	      </select>
                </td>
              </tr>
            </table>
          </td>
        </tr>
			<? } ?>
        <tr>
        	<td class="fett" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3; border-top:1px solid #C3C7C3;" colspan="4">&nbsp;Hintergrundbild&nbsp;</td>
        	<td class="fett" align="center" style="border-bottom:1px solid #C3C7C3; border-top:1px solid #C3C7C3;" colspan="4">&nbsp;Referenzkartenhintergrund&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="headposx" value="<? echo $this->Document->selectedframe[0]['headposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td style="border-right:2px solid #C3C7C3"><input onchange="updateheight(<? echo $this->Document->headsize[0].','.$this->Document->headsize[1] ?>);" type="text" name="headwidth" value="<? echo $this->Document->selectedframe[0]['headwidth'] ?>" size="5"></td>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="refmapposx" value="<? echo $this->Document->selectedframe[0]['refmapposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td><input type="text" name="refmapwidth" onchange="updaterefheight(<? echo $this->Document->refmapsize[0].','.$this->Document->refmapsize[1] ?>);" value="<? echo $this->Document->selectedframe[0]['refmapwidth'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="headposy" value="<? echo $this->Document->selectedframe[0]['headposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td style="border-right:2px solid #C3C7C3"><input onchange="updatewidth(<? echo $this->Document->headsize[0].','.$this->Document->headsize[1] ?>);" type="text" name="headheight" value="<? echo $this->Document->selectedframe[0]['headheight'] ?>" size="5"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="refmapposy" value="<? echo $this->Document->selectedframe[0]['refmapposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input type="text" name="refmapheight" onchange="updaterefwidth(<? echo $this->Document->refmapsize[0].','.$this->Document->refmapsize[1] ?>);" value="<? echo $this->Document->selectedframe[0]['refmapheight'] ?>" size="5"></td>
        </tr>
        <tr>
      </table>
      <table border="1" width="605" cellspacing="0" cellpadding="0">
        	<td colspan=8 align="left">
        		<? if($this->previewfile){ ?>
        			<div id="preview_div" onmouseout="document.getElementById('coords').style.visibility='hidden';" onmousemove="image_coords(event)" style="width:595px; height:<? echo $preview_height; ?>px; background-image: url('<? echo $this->previewfile; ?>');">
        				<div id="coords" style="background-color: white;width:70px;visibility: hidden;position:relative;border: 1px solid black">
        					&nbsp;x:&nbsp;<input type="text" id="posx" size="3" style="border:none"><br>
        					&nbsp;y:&nbsp;<input type="text" id="posy" size="3" style="border:none">
        				</div>
        			</div>
        		<? } ?>
					</td>
        </tr>
      </table>
      <table width=605 border=0 cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">

  			<tr>
          <td style="border-bottom:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;<span class="fett">Hintergrund:</span> <? echo $this->Document->selectedframe[0]['headsrc'] ?></td>
          <td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="headsrc" size="10"></td>
        </tr>
        <tr>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;<span class="fett">Ref.hintergrund:</span> <? echo $this->Document->selectedframe[0]['refmapsrc'] ?></td>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="refmapsrc" size="10"></td>
        </tr>
        <tr>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;<span class="fett">Ref.Mapfile:</span> <? echo $this->Document->selectedframe[0]['refmapfile'] ?></td>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="refmapfile" size="10"></td>
        </tr>
        <tr>
          <td style="border-bottom:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Karte&nbsp;($minx, $miny, $maxx, $maxy)</td>
        	<td width="50%" align="center" style="border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;<span class="fett">Referenzkarte</span>&nbsp;&nbsp;&nbsp;Zoomfaktor:&nbsp;<input type="text" name="refzoom" value="<? echo $this->Document->selectedframe[0]['refzoom'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td width="7%">&nbsp;x:</td>
        	<td width="18%" style="border-right:1px solid #C3C7C3"><input type="text" name="mapposx" value="<? echo $this->Document->selectedframe[0]['mapposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td style="border-right:2px solid #C3C7C3"><input type="text" name="mapwidth" value="<? echo $this->Document->selectedframe[0]['mapwidth'] ?>" size="5"></td>
					<td width="7%">&nbsp;x:</td>
        	<td width="18%" style="border-right:1px solid #C3C7C3"><input type="text" name="refposx" value="<? echo $this->Document->selectedframe[0]['refposx'] ?>" size="5"></td>
        	<td>&nbsp;Breite:</td>
					<td><input type="text" name="refwidth" value="<? echo $this->Document->selectedframe[0]['refwidth'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="mapposy" value="<? echo $this->Document->selectedframe[0]['mapposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td style="border-right:2px solid #C3C7C3"><input type="text" name="mapheight" value="<? echo $this->Document->selectedframe[0]['mapheight'] ?>" size="5"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="refposy" value="<? echo $this->Document->selectedframe[0]['refposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input type="text" name="refheight" value="<? echo $this->Document->selectedframe[0]['refheight'] ?>" size="5"></td>
        </tr>

				<tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Lage&nbsp;($lage)</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Gemeinde&nbsp;($gemeinde)</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="lageposx" value="<? echo $this->Document->selectedframe[0]['lageposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
						<?php echo output_select('font_large', $this->Document->fonts, $this->Document->selectedframe[0]['font_lage']); ?>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemeindeposx" value="<? echo $this->Document->selectedframe[0]['gemeindeposx'] ?>" size="5"></td>
					<td colspan="2" align="center">
						<?php echo output_select('font_gemeinde', $this->Document->fonts, $this->Document->selectedframe[0]['font_gemeinde']); ?>
					</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="lageposy" value="<? echo $this->Document->selectedframe[0]['lageposy'] ?>" size="5"></td>
					<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" name="lagesize" value="<? echo $this->Document->selectedframe[0]['lagesize'] ?>" size="5">&nbsp;pt</td>
					<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemeindeposy" value="<? echo $this->Document->selectedframe[0]['gemeindeposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="gemeindesize" value="<? echo $this->Document->selectedframe[0]['gemeindesize'] ?>" size="5">&nbsp;pt</td>
        </tr>

        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Gemarkung&nbsp;($gemarkung)</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Flur&nbsp;($flur)</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemarkungposx" value="<? echo $this->Document->selectedframe[0]['gemarkungposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
						<?php echo output_select('font_gemarkung', $this->Document->fonts, $this->Document->selectedframe[0]['font_gemarkung']); ?>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurposx" value="<? echo $this->Document->selectedframe[0]['flurposx'] ?>" size="5"></td>
					<td colspan="2" align="center">
						<?php echo output_select('font_flur', $this->Document->fonts, $this->Document->selectedframe[0]['font_flur']); ?>
					</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemarkungposy" value="<? echo $this->Document->selectedframe[0]['gemarkungposy'] ?>" size="5"></td>
					<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" name="gemarkungsize" value="<? echo $this->Document->selectedframe[0]['gemarkungsize'] ?>" size="5">&nbsp;pt</td>
					<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurposy" value="<? echo $this->Document->selectedframe[0]['flurposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="flursize" value="<? echo $this->Document->selectedframe[0]['flursize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Flurstück&nbsp;($flurstueck)</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Datum&nbsp;($date)</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurstposx" value="<? echo $this->Document->selectedframe[0]['flurstposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
						<?php echo output_select('font_flurst', $this->Document->fonts, $this->Document->selectedframe[0]['font_flurst']); ?>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposx" value="<? echo $this->Document->selectedframe[0]['dateposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
						<?php echo output_select('font_date', $this->Document->fonts, $this->Document->selectedframe[0]['font_date']); ?>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurstposy" value="<? echo $this->Document->selectedframe[0]['flurstposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" align="center" colspan="2"><input type="text" name="flurstsize" value="<? echo $this->Document->selectedframe[0]['flurstsize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposy" value="<? echo $this->Document->selectedframe[0]['dateposy'] ?>" size="5"></td>
        	<td align="center" colspan="2"><input type="text" name="datesize" value="<? echo $this->Document->selectedframe[0]['datesize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Legende&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Stelle-Nutzer&nbsp;($stelle, $user)</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="legendposx" value="<? echo $this->Document->selectedframe[0]['legendposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
						<?php echo output_select('font_legend', $this->Document->fonts, $this->Document->selectedframe[0]['font_legend']); ?>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="userposx" value="<? echo $this->Document->selectedframe[0]['userposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
						<?php echo output_select('font_user', $this->Document->fonts, $this->Document->selectedframe[0]['font_user']); ?>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="legendposy" value="<? echo $this->Document->selectedframe[0]['legendposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" name="legendsize" value="<? echo $this->Document->selectedframe[0]['legendsize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="userposy" value="<? echo $this->Document->selectedframe[0]['userposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="usersize" value="<? echo $this->Document->selectedframe[0]['usersize'] ?>" size="5">&nbsp;pt</td>
        </tr>

        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Nordpfeil&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">Maßstab&nbsp;($scale)</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="arrowposx" value="<? echo $this->Document->selectedframe[0]['arrowposx'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">Länge:&nbsp;<input type="text" name="arrowlength" value="<? echo $this->Document->selectedframe[0]['arrowlength'] ?>" size="5">&nbsp;</td>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scaleposx" value="<? echo $this->Document->selectedframe[0]['scaleposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
						<?php echo output_select('font_scale', $this->Document->fonts, $this->Document->selectedframe[0]['font_scale']); ?>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="arrowposy" value="<? echo $this->Document->selectedframe[0]['arrowposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">&nbsp;</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scaleposy" value="<? echo $this->Document->selectedframe[0]['scaleposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="scalesize" value="<? echo $this->Document->selectedframe[0]['scalesize'] ?>" size="5">&nbsp;pt</td>
        </tr>

				 <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Maßstabsleiste&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:0px solid #C3C7C3" colspan="4">&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scalebarposx" value="<? echo $this->Document->selectedframe[0]['scalebarposx'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">&nbsp;</td>
        	<td></td>
        	<td style="border-right:1px solid #C3C7C3"></td>
        	<td colspan="2" align="center">
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scalebarposy" value="<? echo $this->Document->selectedframe[0]['scalebarposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">&nbsp;</td>
        	<td></td>
        	<td style="border-right:1px solid #C3C7C3"></td>
        	<td colspan="2" align="center"></td>
        </tr>

        <tr>
          <td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Freitexte</td>
        </tr>

        <? for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){ ?>
	        <tr>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;">&nbsp;</td>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;</td>
	        	<td rowspan="4" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=4><textarea name="text<? echo $i ?>" cols="31" rows="4"><? echo $this->Document->selectedframe[0]['texts'][$i]['text'] ?></textarea></td>
	        	<td style="border-top:2px solid #C3C7C3;" colspan=2 align="center">
							<?php echo output_select('textfont' . $i, $this->Document->fonts, $this->Document->selectedframe[0]['texts'][$i]['font']); ?>
	        	</td>
	        </tr>
	        <tr>
	        	<td>&nbsp;x:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="textposx<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['posx'] ?>" size="5"></td>
	        	<td colspan="2"><input type="text" name="textsize<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['size'] ?>" size="5">&nbsp;pt</td>
	        </tr>
	       	<tr>
	       		<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="textposy<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['posy'] ?>" size="5"><input type="hidden" name="text_id<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['id'] ?>"></td>
	       		<td colspan="2"><input type="text" name="textangle<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['angle'] ?>" size="5">°</td>
	        </tr>
	        <tr>
	        	<td style="border-right:1px solid #C3C7C3" colspan="2">&nbsp;</td>
	        	<td colspan="2" align="right"><a href="javascript:Bestaetigung('index.php?go=Druckrahmen_Freitextloeschen&freitext_id=<? echo $this->Document->selectedframe[0]['texts'][$i]['id'] ?>&aktiverRahmen=<? echo $this->formvars['aktiverRahmen']; ?>', 'Wollen Sie den Freitext wirklich löschen?');">löschen</a></td>
	        </tr>
	      <? } ?>

	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfreetext();">Freitext hinzufügen</a></td>
          <td style="border-top:2px solid #C3C7C3" colspan="4" align="right"><input type="checkbox" name="variable_freetexts" value="1" <? if($this->Document->selectedframe[0]['variable_freetexts'] == 1) echo 'checked=true'; ?>>&nbsp;variable Freitexte&nbsp;</td>
        </tr>

        <tr>
          <td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Wasserzeichen</td>
        </tr>
        <tr>
        	<td style="border-top:1px solid #C3C7C3;">&nbsp;x:</td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="watermarkposx" value="<? echo $this->Document->selectedframe[0]['watermarkposx'] ?>" size="5"></td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=4>Text:&nbsp;<input size="40" type="text" name="watermark" value="<? echo $this->Document->selectedframe[0]['watermark'] ?>"></td>
        	<td style="border-top:1px solid #C3C7C3;" colspan=2 align="center">
						<?php echo output_select('font_watermark', $this->Document->fonts, $this->Document->selectedframe[0]['font_watermark']); ?>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="watermarkposy" value="<? echo $this->Document->selectedframe[0]['watermarkposy'] ?>" size="5"></td>
        	<td colspan="1">Drehwinkel:&nbsp;<input type="text" name="watermarkangle" value="<? echo $this->Document->selectedframe[0]['watermarkangle'] ?>" size="3">°</td>
        	<td colspan="3" style="border-right:1px solid #C3C7C3">Transparenz:&nbsp;<input type="text" name="watermarktransparency" value="<? echo $this->Document->selectedframe[0]['watermarktransparency'] ?>" size="1"></td>
        	<td colspan="2" align="center"><input type="text" name="watermarksize" value="<? echo $this->Document->selectedframe[0]['watermarksize'] ?>" size="5">&nbsp;pt</td>
        </tr>

        <tr>
          <td style="border-top:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="8" style="border-bottom:1px solid #C3C7C3">
			    	&nbsp;<span class="fett">Format:</span>&nbsp;
						<?php echo output_select('format', $this->Document->din_formats, $this->Document->selectedframe[0]['format'], 'updateformatinfo()'); ?>
			    	(
			    	<input type="text" style="border:0px;background-color:transparent;" size="3" readonly name="formatx" value="<? echo $formatx; ?>">
			    	x
			    	<input type="text" style="border:0px;background-color:transparent;" size="3" readonly name="formaty" value="<? echo $formaty;	?>">
			    	)
	      </td>
        </tr>
        <tr>
          <td colspan="8" style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<span class="fett">Preis:</span>&nbsp;
       	  <input align="right" type="text" name="euro" value="<? echo $this->Document->euro; ?>" size="1">,<input type="text" name="cent" value="<? echo $this->Document->cent; ?>" size="1">&nbsp;€          </td>
        </tr>
        <tr>
          <td  colspan=8 style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<span class="fett">Name:</span>
          	<input type="text" name="Name" value="<? echo $this->Document->selectedframe[0]['Name'] ?>" size="27">
          </td>
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
    <input class="button" type="button" name="go_plus" value="Layout löschen" onclick="Bestaetigung('index.php?go=Druckrahmen_Löschen&selected_frame_id=<? echo $this->Document->selectedframe[0]['id']; ?>', 'Wollen Sie dieses Layout wirklich löschen?');">&nbsp;<input class="button" type="submit" name="go_plus" value="Änderungen Speichern">&nbsp;<input class="button" type="submit" name="go_plus" value="als neues Layout speichern">
    </td>
  </tr>

  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="textcount" value="<? echo count($this->Document->selectedframe[0]['texts']); ?>">
<input type="hidden" name="headsrc_save" value="<? echo $this->Document->selectedframe[0]['headsrc'] ?>">
<input type="hidden" name="refmapsrc_save" value="<? echo $this->Document->selectedframe[0]['refmapsrc'] ?>">
<input type="hidden" name="refmapfile_save" value="<? echo $this->Document->selectedframe[0]['refmapfile'] ?>">

