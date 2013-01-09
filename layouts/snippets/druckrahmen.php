<?
if($this->Document->selectedframe[0]['format'] == 'A5hoch'){ $formatx = 420; $formaty = 595;}
if($this->Document->selectedframe[0]['format'] == 'A5quer'){ $formatx = 595; $formaty = 420;} 
if($this->Document->selectedframe[0]['format'] == 'A4hoch'){ $formatx = 595; $formaty = 842;}
if($this->Document->selectedframe[0]['format'] == 'A4quer'){ $formatx = 842; $formaty = 595;}
if($this->Document->selectedframe[0]['format'] == 'A3hoch'){ $formatx = 842; $formaty = 1191;}
if($this->Document->selectedframe[0]['format'] == 'A3quer'){ $formatx = 1191; $formaty = 842;} 
if($this->Document->selectedframe[0]['format'] == 'A2hoch'){ $formatx = 1191; $formaty = 1684;}
if($this->Document->selectedframe[0]['format'] == 'A2quer'){ $formatx = 1684; $formaty = 1191;}
if($this->Document->selectedframe[0]['format'] == 'A1hoch'){ $formatx = 1684; $formaty = 2384;}
if($this->Document->selectedframe[0]['format'] == 'A1quer'){ $formatx = 2384; $formaty = 1684;}
if($this->Document->selectedframe[0]['format'] == 'A0hoch'){ $formatx = 2384; $formaty = 3370;}
if($this->Document->selectedframe[0]['format'] == 'A0quer'){ $formatx = 3370; $formaty = 2384;}

$preview_height = round(595 * $formaty / $formatx);
?>

<script type="text/javascript">
<!--

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
  
//-->
</script>


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
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td >
      <table width=100% cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr>
          <td class="bold" colspan=2 style="border-bottom:1px solid #C3C7C3">&nbsp;Druckrahmenauswahl</td>
          <td class="bold" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;Stelle</td>
        </tr>
        <tr>
          <td colspan=1>
            &nbsp;<select class="select" name="aktiverRahmen" onchange="document.GUI.submit()">
            <?  
            for($i = 0; $i < count($this->Document->frames); $i++){
            	
              echo ($this->formvars['aktiverRahmen']<>$this->Document->frames[$i]['id']) ? '<option value="'.$this->Document->frames[$i]['id'].'">'.$this->Document->frames[$i]['Name'].'  ('.$this->Document->frames[$i]['id'].')</option>' : '<option value="'.$this->Document->frames[$i]['id'].'" selected>'.$this->Document->frames[$i]['Name'].'  ('.$this->Document->frames[$i]['id'].')</option>';
            }
            ?>
          </select> 
          </td>
          <td>
            <input class="button" type="submit" name="go_plus" value="übernehmen >>">
          </td>
          <td style="border-left:1px solid #C3C7C3">
          	<select class="select" name="stelle">
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
      <table width=605 border=0 cellpadding="2" cellspacing="2" style="border:1px solid #C3C7C3">
        <tr>
          <td class="bold" style="border-bottom:1px solid #C3C7C3" colspan=8 >&nbsp;Druckrahmendaten</td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Druckkopf&nbsp;</td>
        	<td class="bold" align="center" style="border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Referenzkartenhintergrund&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="headposx" value="<? echo $this->Document->selectedframe[0]['headposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td style="border-right:2px solid #C3C7C3"><input class="input" onchange="updateheight(<? echo $this->Document->headsize[0].','.$this->Document->headsize[1] ?>);" type="text" name="headwidth" value="<? echo $this->Document->selectedframe[0]['headwidth'] ?>" size="5"></td>       	
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="refmapposx" value="<? echo $this->Document->selectedframe[0]['refmapposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td><input type="text" class="input" name="refmapwidth" onchange="updaterefheight(<? echo $this->Document->refmapsize[0].','.$this->Document->refmapsize[1] ?>);" value="<? echo $this->Document->selectedframe[0]['refmapwidth'] ?>" size="5"></td>					               	
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="headposy" value="<? echo $this->Document->selectedframe[0]['headposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td style="border-right:2px solid #C3C7C3"><input class="input" onchange="updatewidth(<? echo $this->Document->headsize[0].','.$this->Document->headsize[1] ?>);" type="text" name="headheight" value="<? echo $this->Document->selectedframe[0]['headheight'] ?>" size="5"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="refmapposy" value="<? echo $this->Document->selectedframe[0]['refmapposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input type="text" class="input" name="refmapheight" onchange="updaterefwidth(<? echo $this->Document->refmapsize[0].','.$this->Document->refmapsize[1] ?>);" value="<? echo $this->Document->selectedframe[0]['refmapheight'] ?>" size="5"></td>
        </tr>
        <tr>
      </table>
      <table border="1" width="605" cellspacing="0" cellpadding="0">
        	<td colspan=8 align="left">
        		<? if($this->previewfile){ ?>
        			<div id="preview_div" onmouseout="document.getElementById('coords').style.visibility='hidden';" onmousemove="image_coords(event)" style="width:595px;height:<? echo $preview_height; ?>px;background-image:url('<? echo $this->previewfile; ?>');">
        				<div id="coords" style="background-color: white;width:65px;visibility: hidden;position:relative;border: 1px solid black">
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
          <td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;<b>Druckkopf:</b> <? echo $this->Document->selectedframe[0]['headsrc'] ?></td>
          <td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="headsrc" size="10"></td>
        </tr>
        <tr>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;<b>Ref.hintergrund:</b> <? echo $this->Document->selectedframe[0]['refmapsrc'] ?></td>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="refmapsrc" size="10"></td>
        </tr>
        <tr>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;<b>Ref.Mapfile:</b> <? echo $this->Document->selectedframe[0]['refmapfile'] ?></td>
          <td width="50%" style="border-bottom:2px solid #C3C7C3" colspan=4>&nbsp;wählen:&nbsp;<input class="button" type="file" name="refmapfile" size="10"></td>
        </tr>
        <tr>
          <td style="border-bottom:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Karte&nbsp;</td>
        	<td width="50%" align="center" style="border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;<b>Referenzkarte</b>&nbsp;&nbsp;&nbsp;Zoomfaktor:&nbsp;<input type="text" class="input" name="refzoom" value="<? echo $this->Document->selectedframe[0]['refzoom'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td width="7%">&nbsp;x:</td>
        	<td width="18%" style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="mapposx" value="<? echo $this->Document->selectedframe[0]['mapposx'] ?>" size="5"></td>
					<td>&nbsp;Breite:</td>
					<td style="border-right:2px solid #C3C7C3"><input type="text" class="input" name="mapwidth" value="<? echo $this->Document->selectedframe[0]['mapwidth'] ?>" size="5"></td>
					<td width="7%">&nbsp;x:</td>
        	<td width="18%" style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="refposx" value="<? echo $this->Document->selectedframe[0]['refposx'] ?>" size="5"></td>
        	<td>&nbsp;Breite:</td>
					<td><input type="text" class="input" name="refwidth" value="<? echo $this->Document->selectedframe[0]['refwidth'] ?>" size="5"></td>					               	
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="mapposy" value="<? echo $this->Document->selectedframe[0]['mapposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td style="border-right:2px solid #C3C7C3"><input type="text" class="input" name="mapheight" value="<? echo $this->Document->selectedframe[0]['mapheight'] ?>" size="5"></td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="refposy" value="<? echo $this->Document->selectedframe[0]['refposy'] ?>" size="5"></td>
        	<td>&nbsp;Höhe:</td>
        	<td><input type="text" class="input" name="refheight" value="<? echo $this->Document->selectedframe[0]['refheight'] ?>" size="5"></td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Gemarkung&nbsp;</td>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Flur&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="gemarkungposx" value="<? echo $this->Document->selectedframe[0]['gemarkungposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
        		<select name="font_gemarkung">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_gemarkung'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="flurposx" value="<? echo $this->Document->selectedframe[0]['flurposx'] ?>" size="5"></td>
					<td colspan="2" align="center">
						<select name="font_flur">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_flur'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
					</td>        						               	
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="gemarkungposy" value="<? echo $this->Document->selectedframe[0]['gemarkungposy'] ?>" size="5"></td>
					<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" class="input" name="gemarkungsize" value="<? echo $this->Document->selectedframe[0]['gemarkungsize'] ?>" size="5">&nbsp;pt</td>
					<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="flurposy" value="<? echo $this->Document->selectedframe[0]['flurposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" class="input" name="flursize" value="<? echo $this->Document->selectedframe[0]['flursize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Datum&nbsp;</td>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;ursprünglicher Maßstab&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="dateposx" value="<? echo $this->Document->selectedframe[0]['dateposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
        		<select name="font_date">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_date'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="oscaleposx" value="<? echo $this->Document->selectedframe[0]['oscaleposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
        		<select name="font_oscale">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_oscale'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>					               	
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="dateposy" value="<? echo $this->Document->selectedframe[0]['dateposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" align="center" colspan="2"><input type="text" class="input" name="datesize" value="<? echo $this->Document->selectedframe[0]['datesize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="oscaleposy" value="<? echo $this->Document->selectedframe[0]['oscaleposy'] ?>" size="5"></td>
        	<td align="center" colspan="2"><input type="text" class="input" name="oscalesize" value="<? echo $this->Document->selectedframe[0]['oscalesize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Legende&nbsp;</td>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Maßstab&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="legendposx" value="<? echo $this->Document->selectedframe[0]['legendposx'] ?>" size="5"></td>
        	<td colspan="2" style="border-right:2px solid #C3C7C3" align="center">
        		<select name="font_legend">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_legend'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
					<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="scaleposx" value="<? echo $this->Document->selectedframe[0]['scaleposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
        		<select name="font_scale">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_scale'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="legendposy" value="<? echo $this->Document->selectedframe[0]['legendposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" class="input" name="legendsize" value="<? echo $this->Document->selectedframe[0]['legendsize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="scaleposy" value="<? echo $this->Document->selectedframe[0]['scaleposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" class="input" name="scalesize" value="<? echo $this->Document->selectedframe[0]['scalesize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        
        <tr>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Nordpfeil&nbsp;</td>
        	<td class="bold" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">Stelle-Nutzer</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="arrowposx" value="<? echo $this->Document->selectedframe[0]['arrowposx'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">Länge:&nbsp;<input type="text" class="input" name="arrowlength" value="<? echo $this->Document->selectedframe[0]['arrowlength'] ?>" size="5">&nbsp;</td>		
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="userposx" value="<? echo $this->Document->selectedframe[0]['userposx'] ?>" size="5"></td>
        	<td colspan="2" align="center">
        		<select name="font_user">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_scale'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="arrowposy" value="<? echo $this->Document->selectedframe[0]['arrowposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">&nbsp;</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="userposy" value="<? echo $this->Document->selectedframe[0]['userposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" class="input" name="usersize" value="<? echo $this->Document->selectedframe[0]['usersize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        
        <tr>
          <td class="bold" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Freitexte</td>
        </tr>
 
        <? for($i = 0; $i < count($this->Document->selectedframe[0]['texts']); $i++){ ?>
	        <tr>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;">&nbsp;</td>
	        	<td rowspan="1" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3">&nbsp;</td>
	        	<td rowspan="4" style="border-top:2px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=4><textarea class="input" name="text<? echo $i ?>" cols="31" rows="4"><? echo $this->Document->selectedframe[0]['texts'][$i]['text'] ?></textarea></td>
	        	<td style="border-top:2px solid #C3C7C3;" colspan=2 align="center">
	        		<select name="textfont<? echo $i ?>">
		        		<?
		        		for($j = 0; $j < count($this->document->fonts); $j++){
		        			echo '<option ';
		        			if($this->Document->selectedframe[0]['texts'][$i]['font'] == $this->document->fonts[$j]){
		        				echo 'selected ';
		        			}
		        			echo 'value="'.$this->document->fonts[$j].'">'.basename($this->document->fonts[$j]).'</option>';
		        		}
		        		?>
	        		</select>
	        	</td>
	        </tr>
	        <tr>
	        	<td>&nbsp;x:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="textposx<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['posx'] ?>" size="5"></td>	        	
	        	<td colspan="2"><input type="text" class="input" name="textsize<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['size'] ?>" size="5">&nbsp;pt</td>
	        </tr>
	       	<tr>
	       		<td>&nbsp;y:</td>
	        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="textposy<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['posy'] ?>" size="5"><input type="hidden" name="text_id<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['id'] ?>"></td>
	       		<td colspan="2"><input type="text" class="input" name="textangle<? echo $i ?>" value="<? echo $this->Document->selectedframe[0]['texts'][$i]['angle'] ?>" size="5">°</td>
	        </tr>
	        <tr>
	        	<td style="border-right:1px solid #C3C7C3" colspan="2">&nbsp;</td>
	        	<td colspan="2" align="right"><a href="javascript:Bestaetigung('index.php?go=Druckrahmen_Freitextloeschen&freitext_id=<? echo $this->Document->selectedframe[0]['texts'][$i]['id'] ?>&aktiverRahmen=<? echo $this->formvars['aktiverRahmen']; ?>', 'Wollen Sie den Freitext wirklich löschen?');">löschen</a></td>
	        </tr>
	      <? } ?>
	      
	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfreetext();">Freitext hinzufügen</a></td>
        </tr>        
        
        <tr>
          <td class="bold" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Wasserzeichen</td>
        </tr>
        <tr>
        	<td style="border-top:1px solid #C3C7C3;">&nbsp;x:</td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" class="input" name="watermarkposx" value="<? echo $this->Document->selectedframe[0]['watermarkposx'] ?>" size="5"></td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=4>Text:&nbsp;<input size="40" type="text" class="input" name="watermark" value="<? echo $this->Document->selectedframe[0]['watermark'] ?>"></td>
        	<td style="border-top:1px solid #C3C7C3;" colspan=2 align="center">
        		<select name="font_watermark">
	        		<?
	        		for($i = 0; $i < count($this->document->fonts); $i++){
	        			echo '<option ';
	        			if($this->Document->selectedframe[0]['font_watermark'] == $this->document->fonts[$i]){
	        				echo 'selected ';
	        			}
	        			echo 'value="'.$this->document->fonts[$i].'">'.basename($this->document->fonts[$i]).'</option>';
	        		}
	        		?>
        		</select>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" class="input" name="watermarkposy" value="<? echo $this->Document->selectedframe[0]['watermarkposy'] ?>" size="5"></td>
        	<td colspan="1">Drehwinkel:&nbsp;<input type="text" class="input" name="watermarkangle" value="<? echo $this->Document->selectedframe[0]['watermarkangle'] ?>" size="3">°</td>
        	<td colspan="3" style="border-right:1px solid #C3C7C3">Transparenz:&nbsp;<input type="text" class="input" name="watermarktransparency" value="<? echo $this->Document->selectedframe[0]['watermarktransparency'] ?>" size="1"></td>
        	<td colspan="2" align="center"><input type="text" class="input" name="watermarksize" value="<? echo $this->Document->selectedframe[0]['watermarksize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        
        <tr>
          <td style="border-top:1px solid #C3C7C3" colspan=8>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="8" style="border-bottom:1px solid #C3C7C3">
			    	&nbsp;<b>Format:</b>&nbsp;
			    	<select class="select" name="format" onchange="updateformatinfo();">
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A5hoch') echo 'selected'; ?> value="A5hoch">A5 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A5quer') echo 'selected'; ?> value="A5quer">A5 quer</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A4hoch') echo 'selected'; ?> value="A4hoch">A4 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A4quer') echo 'selected'; ?> value="A4quer">A4 quer</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A3hoch') echo 'selected'; ?> value="A3hoch">A3 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A3quer') echo 'selected'; ?> value="A3quer">A3 quer</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A2hoch') echo 'selected'; ?> value="A2hoch">A2 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A2quer') echo 'selected'; ?> value="A2quer">A2 quer</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A1hoch') echo 'selected'; ?> value="A1hoch">A1 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A1quer') echo 'selected'; ?> value="A1quer">A1 quer</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A0hoch') echo 'selected'; ?> value="A0hoch">A0 hoch</option>
			    		<option <? if($this->Document->selectedframe[0]['format'] == 'A0quer') echo 'selected'; ?> value="A0quer">A0 quer</option>
			    	</select>
			    	(
			    	<input type="text" class="input" style="border:0px;background-color:transparent;" size="3" readonly name="formatx" value="<? echo $formatx; ?>">
			    	x
			    	<input type="text" class="input" style="border:0px;background-color:transparent;" size="3" readonly name="formaty" value="<? echo $formaty;	?>">
			    	)
	      </td>
        </tr>
        <tr>
          <td colspan="8" style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<b>Preis:</b>&nbsp;
       	  <input align="right" type="text" class="input" name="euro" value="<? echo $this->Document->euro; ?>" size="1">,<input type="text" class="input" name="cent" value="<? echo $this->Document->cent; ?>" size="1">&nbsp;€          </td>
        </tr>
        <tr>
          <td  colspan=8 style="border-bottom:1px solid #C3C7C3">
          	&nbsp;<b>Name:</b> 
          	<input type="text" class="input" name="Name" value="<? echo $this->Document->selectedframe[0]['Name'] ?>" size="27">
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
    <input class="button" type="button" name="go_plus" value="Rahmen löschen" onclick="Bestaetigung('index.php?go=Druckrahmen_Löschen&selected_frame_id=<? echo $this->Document->selectedframe[0]['id']; ?>', 'Wollen Sie diesen Druckrahmen wirklich löschen?');">&nbsp;<input class="button" type="submit" name="go_plus" value="Änderungen Speichern">&nbsp;<input class="button" type="submit" name="go_plus" value="als neuen Rahmen speichern">
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

