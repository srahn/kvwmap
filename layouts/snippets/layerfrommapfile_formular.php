<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/layerfrommapfile_formular_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
<!--

function selectall(count){
	for(i = 0; i < count; i++){
		layer = document.getElementById("layer"+i);
		layer.checked = !layer.checked;
	}
}

  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><br><h2><?php echo $this->titel; ?></h2><br><br></td>
  </tr>
  <tr>
    <td align="center">      
		<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
			<tr>
	    	<td align="right" style="border-bottom:1px solid #C3C7C3"><span class="fett"><?php echo $strMapdata; ?> </span></td>
	    	<td style="border-bottom:1px solid #C3C7C3">&nbsp;<input class="button" type="file" name="mapfile" size="12"></td>
	    	<td style="border-bottom:1px solid #C3C7C3"><input class="button" type="submit" name="go_plus" value="<?php echo $strButtonDataLoad; ?>"></td>
	  	</tr>
			<tr>
	    	<td align="right" style="border-bottom:1px solid #C3C7C3"><span class="fett"><?php echo $strZipArchives; ?> </span></td>
	    	<td style="border-bottom:1px solid #C3C7C3">&nbsp;<input class="button" type="file" name="zipfile" size="12"></td>
	    	<td style="border-bottom:1px solid #C3C7C3"><input class="button" type="submit" name="go_plus" value="<?php echo $strButtonDataLoad; ?>"></td>
	  	</tr>
			<?
			if(count($this->mapfiles) > 0){
				echo '
					<tr>
						<td colspan="3" align="center"><span class="fett">'.count($this->mapfiles).'&nbsp;Mapfiles&nbsp;in '.$this->formvars['zipfile'].' gefunden:</span></td>
					</tr>
				';
				for($i = 0; $i < count($this->mapfiles); $i++){
					echo '
					<tr>
						<td>&nbsp;</td>
						<td>'.basename($this->mapfiles[$i]).'</td>
						<td><input type="radio" name="zipmapfile" value="'.$this->mapfiles[$i].'"></td>
					</tr>
					';
				}
				echo '<tr><td colspan="3" align="right"><input class="button" type="submit" name="go_plus" value="Datei laden"></td></tr>';
			}
			if(count($this->layers) > 0){
				if($this->mapobject->fontsetfilename != '' OR $this->mapobject->symbolsetfilename != ''){
					echo '
						<tr>
							<td colspan="3" align="center"><span class="fett">Symbole und Fonts hinzufügen?</span></td>
						</tr>
					';
				}
				if($this->mapobject->fontsetfilename != ''){
					echo '
						<tr>
							<td colspan="2" align="right">Fontset erweitern&nbsp;<input type="checkbox" name="checkfont" value="1"></td>
							<td></td>
						</tr>
					';
				}
				if($this->mapobject->symbolsetfilename != ''){
					echo '
						<tr>
							<td colspan="2" align="right">Symbolset erweitern&nbsp;<input type="checkbox" name="checksymbol" value="1"></td>
							<td></td>
						</tr>
					';
				}
				echo '
					<tr>
						<td colspan="3" align="center"><span class="fett">'.count($this->layers).' Layer in '.basename($this->formvars['mapfile']).' gefunden:</span></td>
					</tr>
					<tr>
						<td colspan="3" align="right"><input class="button" type="button" value="alle auswählen" name="alleauswaehlen" onClick="selectall('.count($this->layers).')"></td>
					</tr>
				';
				for($i = 0; $i < count($this->layers); $i++){
					if($this->layers[$i]->group != $lastgroup){
						$lastgroup = $this->layers[$i]->group;
						echo '
						<tr>
							<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$this->layers[$i]->group.'</td>
							<td>&nbsp;</td>
						</tr>
						';
					}
					echo '
					<tr>
						<td>&nbsp;</td>
						<td>'.$this->layers[$i]->name.'</td>
						<td><input id="layer'.$i.'" type="checkbox" name="layer'.$i.'" value="'.$i.'"></td>
					</tr>
					';
				}
				echo '
				<tr>
					<td colspan="3" align="right"><input class="button" type="submit" name="go_plus" value="Layer hinzufügen"></td>
				</tr>
				<input type="hidden" name="mapfilename" value="'.$this->formvars['mapfile'].'">
				';
			}
			if($this->layercount > 0){
				echo '
				<tr>
					<td colspan="3">Es wurden '.$this->groupcount.' Gruppen, '.$this->layercount.' Layer, '.$this->classcount.' Klassen, '.$this->stylecount.' Styles und '.$this->labelcount.' Labels in die Datenbank geschrieben.</td>
				</tr>
				';
			}
			if($this->fontsetcount > 0){
				echo '
				<tr>
					<td colspan="3">Die Fontset-Datei '.FONTSET.' wurde um '.$this->fontsetcount.' Einträge erweitert und es wurden '.$this->fontfilecount.' Fontdateien nach '.dirname(FONTSET).' kopiert.</td>
				</tr>
				';
			}
			if($this->symbolcount > 0){
				echo '
				<tr>
					<td colspan="3">Zur Symbolset-Datei '.SYMBOLSET.' wurden '.$this->symbolcount.' Symbole hinzugefügt.</td>
				</tr>
				';
			}
			?>
		</table>
	</td>
  </tr>
</table>

<input type="hidden" name="go" value="layerfrommapfile">
<input type="hidden" name="firstfolder" value="<? echo $this->firstfolder; ?>">
