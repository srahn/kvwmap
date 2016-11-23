
<script type="text/javascript">
<!--

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

function updateformatinfo(){ <?
	foreach ($this->Document->din_formats AS $din_format) {
		echo "if (document.GUI.format.value == '{$din_format['value']}'){\n";
		echo "\tdocument.GUI.formatinfo.value = '{$din_format['size']}';\n"
		echo "}";
	} ?>
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
          <td class="fett" colspan=2 style="border-bottom:1px solid #C3C7C3">&nbsp;Druckrahmenauswahl</td>
          <td class="fett" style="border-bottom:1px solid #C3C7C3; border-left:1px solid #C3C7C3">&nbsp;aktuelle Druckvorlage</td>
        </tr>
        <tr>
          <td colspan=1>
            &nbsp;<select  name="aktiverRahmen" onchange="document.GUI.submit()">
            <?  
            for($i = 0; $i < count($this->Document->frames); $i++){
            	
              echo ($this->formvars['aktiverRahmen']<>$this->Document->frames[$i]['id']) ? '<option value="'.$this->Document->frames[$i]['id'].'">'.$this->Document->frames[$i]['Name'].'</option>' : '<option value="'.$this->Document->frames[$i]['id'].'" selected>'.$this->Document->frames[$i]['Name'].'</option>';
            }
            ?>
          </select> 
          </td>
          <td>
            <input class="button" type="submit" name="go_plus" value="übernehmen >>">
          </td>
          <td style="border-left:1px solid #C3C7C3">
          <select  name="stelle">
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
          <td class="fett" style="border-bottom:1px solid #C3C7C3" colspan=8 >&nbsp;Druckrahmendaten</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Druckkopf&nbsp;</td>
        	<td class="fett" align="center" style="border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Referenzkartenhintergrund&nbsp;</td>
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
        	<? if($this->Document->selectedframe != NULL){ ?>
						<div id="main" style="position: relative; left:5px; top:0px">
							<table bgcolor="white" width="595" height="<? echo $this->Document->height; ?>" border="0" cellspacing="0" cellpadding="0">
							  <tr>
							  	<td>&nbsp;</td>
							  </tr>
							</table>
							<div title="Druckkopf" id="head" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->headposx; ?>px; bottom: <? echo $this->Document->headposy; ?>px">
								<img src="<? echo copy_file_to_tmp(DRUCKRAHMEN_PATH.$this->Document->selectedframe[0]['headsrc']); ?>" width="<? echo $this->Document->headwidth; ?>">
							</div>
							<? if($this->Document->mapwidth){ ?>
						  <div title="Karte" id="map" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->mapposx; ?>px; bottom: <? echo $this->Document->mapposy; ?>px">
								<table bgcolor="#00CC99" width="<? echo $this->Document->mapwidth; ?>" height="<? echo $this->Document->mapheight; ?>" border="0" cellspacing="0" cellpadding="0">
								  <tr>
								  	<td align="center"><span class="fett">Karte</span></td>
								  </tr>
								</table>
							</div>
							<? } ?>
							<div title="Datum" id="date" class="" style="font-family:helvetica; font-size: <? echo $this->Document->datesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->dateposx; ?>px; bottom: <? echo $this->Document->dateposy; ?>px">
								<? echo date("d.m.Y"); ?>
							</div>
							<div title="Maßstab" id="scale" class="" style="font-size: <? echo $this->Document->scalesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->scaleposx; ?>px; bottom: <? echo $this->Document->scaleposy; ?>px">
								<? echo '1:1000'; ?>
							</div>
							<div title="Gemarkung" id="gemarkung" class="" style="font-size: <? echo $this->Document->gemarkungsize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->gemarkungposx; ?>px; bottom: <? echo $this->Document->gemarkungposy; ?>px">
								<? echo 'Gemarkung: 123456 / Gemarkungsname'; ?>
							</div>
							<div title="Flur" id="flur" class="" style="font-size: <? echo $this->Document->flursize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->flurposx; ?>px; bottom: <? echo $this->Document->flurposy; ?>px">
								<? echo 'Flur: 23'; ?>
							</div>
							<div title="ursprünglicher Maßstab" id="oscale" class="" style="font-size: <? echo $this->Document->oscalesize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->oscaleposx; ?>px; bottom: <? echo $this->Document->oscaleposy; ?>px">
								<? echo '1: 2000'; ?>
							</div>
							
							<div title="Nutzer" id="user" class="" style="font-size: <? echo $this->Document->usersize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->userposx; ?>px; bottom: <? echo $this->Document->userposy; ?>px">
								<? echo 'Stelle: '.$this->Stelle->Bezeichnung.', Nutzer: '.$this->user->Name; ?>
							</div>
							
							<? if($this->Document->selectedframe[0]['refmapsrc'] != ''){ ?>
							<div id="refmap" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->refmapposx; ?>px; bottom: <? echo $this->Document->refmapposy; ?>px">
								<img src="<? echo copy_file_to_tmp(DRUCKRAHMEN_PATH.$this->Document->selectedframe[0]['refmapsrc']); ?>" width="<? echo $this->Document->refmapwidth; ?>">
							</div>
							<div title="Referenzkarte" id="ref" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->refposx; ?>px; bottom: <? echo $this->Document->refposy; ?>px">
								<table bgcolor="#ccCC99" width="<? echo $this->Document->refwidth; ?>" height="<? echo $this->Document->refheight; ?>" border="0" cellspacing="0" cellpadding="0">
								  <tr>
								  	<td align="center"><span class="fett"><span style="font-size:60%">Referenz-<br>karte</span></span></td>
								  </tr>
								</table>
							</div>
							<? } ?>
							<? if($this->Document->selectedframe[0]['legendsize'] > 0){ ?>
							<div title="Legende" id="legend" class="" style="position: absolute; visibility: visible; left: <? echo $this->Document->legendposx; ?>px; bottom: <? echo $this->Document->legendposy; ?>px">
								<table bgcolor="#bc5656" width="<? echo $this->Document->legendwidth; ?>" height="<? echo $this->Document->legendheight; ?>" border="0" cellspacing="0" cellpadding="0">
								  <tr>
								  	<td align="center"><span class="fett"><span style="font-size:60%">Legende</span></span></td>
								  </tr>
								</table>
							</div>
							<? } ?>
							<div title="Freitext" id="text" class="" style="font-size: <? echo $this->Document->textsize; ?>px; font-weight:bold; position: absolute; visibility: visible; left: <? echo $this->Document->textposx; ?>px; bottom: <? echo $this->Document->textposy; ?>px">
								<? $this->Document->text = str_replace (' ', '&nbsp;', $this->Document->text ); 
								echo $this->Document->text; ?>
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
          <td width="50%" style="border-bottom:1px solid #C3C7C3" colspan=4>&nbsp;<span class="fett">Druckkopf:</span> <? echo $this->Document->selectedframe[0]['headsrc'] ?></td>
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
        	<td class="fett" align="center" style="border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Karte&nbsp;</td>
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
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Gemarkung&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Flur&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemarkungposx" value="<? echo $this->Document->selectedframe[0]['gemarkungposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurposx" value="<? echo $this->Document->selectedframe[0]['flurposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="gemarkungposy" value="<? echo $this->Document->selectedframe[0]['gemarkungposy'] ?>" size="5"></td>
					<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" name="gemarkungsize" value="<? echo $this->Document->selectedframe[0]['gemarkungsize'] ?>" size="5">&nbsp;pt</td>
					<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="flurposy" value="<? echo $this->Document->selectedframe[0]['flurposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="flursize" value="<? echo $this->Document->selectedframe[0]['flursize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Datum&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;ursprünglicher Maßstab&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposx" value="<? echo $this->Document->selectedframe[0]['dateposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="oscaleposx" value="<? echo $this->Document->selectedframe[0]['oscaleposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="dateposy" value="<? echo $this->Document->selectedframe[0]['dateposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" align="center" colspan="2"><input type="text" name="datesize" value="<? echo $this->Document->selectedframe[0]['datesize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="oscaleposy" value="<? echo $this->Document->selectedframe[0]['oscaleposy'] ?>" size="5"></td>
        	<td align="center" colspan="2"><input type="text" name="oscalesize" value="<? echo $this->Document->selectedframe[0]['oscalesize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Legende&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Maßstab&nbsp;</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="legendposx" value="<? echo $this->Document->selectedframe[0]['legendposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scaleposx" value="<? echo $this->Document->selectedframe[0]['scaleposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="legendposy" value="<? echo $this->Document->selectedframe[0]['legendposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center"><input type="text" name="legendsize" value="<? echo $this->Document->selectedframe[0]['legendsize'] ?>" size="5">&nbsp;pt</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="scaleposy" value="<? echo $this->Document->selectedframe[0]['scaleposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="scalesize" value="<? echo $this->Document->selectedframe[0]['scalesize'] ?>" size="5">&nbsp;pt</td>
        </tr>
        
        <tr>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-right:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">&nbsp;Nordpfeil&nbsp;</td>
        	<td class="fett" align="center" style="border-top:2px solid #C3C7C3; border-bottom:1px solid #C3C7C3" colspan="4">Nutzer</td>
        </tr>
        <tr>
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="arrowposx" value="<? echo $this->Document->selectedframe[0]['arrowposx'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">Länge:&nbsp;<input type="text" name="arrowlength" value="<? echo $this->Document->selectedframe[0]['arrowlength'] ?>" size="5">&nbsp;</td>		
        	<td>&nbsp;x:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="userposx" value="<? echo $this->Document->selectedframe[0]['userposx'] ?>" size="5"></td>
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
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="arrowposy" value="<? echo $this->Document->selectedframe[0]['arrowposy'] ?>" size="5"></td>
        	<td style="border-right:2px solid #C3C7C3" colspan="2" align="center">&nbsp;</td>
        	<td>&nbsp;y:</td>
        	<td style="border-right:1px solid #C3C7C3"><input type="text" name="userposy" value="<? echo $this->Document->selectedframe[0]['userposy'] ?>" size="5"></td>
        	<td colspan="2" align="center"><input type="text" name="usersize" value="<? echo $this->Document->selectedframe[0]['usersize'] ?>" size="5">&nbsp;pt</td>
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
	        	<td colspan="2" align="right"><a href="javascript:Bestaetigung('index.php?go=Druckrahmen_Freitextloeschen&freitext_id=<? echo $this->Document->selectedframe[0]['texts'][$i]['id'] ?>', 'Wollen Sie den Freitext wirklich löschen?');">löschen</a></td>
	        </tr>
	      <? } ?>
	      
	      <tr>
          <td style="border-top:2px solid #C3C7C3" colspan=8 align="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfreetext();">Freitext hinzufügen</a></td>
        </tr>        
        
        <tr>
          <td class="fett" style="border-top:2px solid #C3C7C3" colspan=8 align="center">Wasserzeichen</td>
        </tr>
        <tr>
        	<td style="border-top:1px solid #C3C7C3;">&nbsp;x:</td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3"><input type="text" name="watermarkposx" value="<? echo $this->Document->selectedframe[0]['watermarkposx'] ?>" size="5"></td>
        	<td style="border-top:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan=4>Text:&nbsp;<input size="40" type="text" name="watermark" value="<? echo $this->Document->selectedframe[0]['watermark'] ?>"></td>
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
			    	<input type="text" style="border:0px;background-color:transparent;" size="10" readonly name="formatinfo" value="<?
							echo $this->Document->din_formats[$this->Document->selectedframe[0]['format']];
						?>">
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

