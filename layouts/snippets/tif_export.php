<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

function DruckAufloesung(pixel,breite) {
	pixel = pixel.replace(/,/g, ".");
	var cm = Math.round((breite/pixel)/200*2.54,1)+' cm';
	document.getElementById("cm").value = cm;
}

//-->
</script>

<? $this->formvars['resolution'] = str_replace(',','.',$this->formvars['resolution']); ?>

<table width="<?php echo ($this->user->rolle->nImageWidth + $sizes[$this->user->rolle->gui]['legend']['width']); ?>" border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center">
    <td colspan="3"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center">
			<span class="fett">Auflösung:</span>  1 Pixel = <input type="text" value="<? echo round($this->formvars['resolution'],3); ?>" name="resolution" size="3" onkeyup="DruckAufloesung(this.value,<? echo $this->map->extent->maxx - $this->map->extent->minx; ?>)">&nbsp;m
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td>&nbsp;</td>
		<td align="center">
		    <? $cm = round((($this->map->extent->maxx - $this->map->extent->minx)/$this->formvars['resolution'])/200*2.54,0).' cm'; ?>
			Für einen Ausdruck dieses TIFs mit 200 dpi<br>
			=> Breite des Ausdrucks :
			<input type="text" readonly="readonly" size="5" id="cm" style="border:0px;background-color:transparent;" value="<? echo $cm; ?>"><br>
            <br>
			(Hinweis: Die Halbierung der Pixelauflösung ergibt die Verdopplung<br>
			 der Druckauflösung und die Vervierfachung der Dateigröße)<br>&nbsp;
		</td>
		<td>&nbsp;</td>
	</tr>
  <tr>
  	<td colspan="3" align="center"><input type="submit" name="go_plus" value="TIF-Datei erzeugen"></td>
  </tr>
  <?if($this->tif->tifimage != ''){
  		if($this->tif->tifimage == 'error'){ ?>
  <tr>
  	<td colspan="3" align="center"><span class="fett">TIF-Erzeugung fehlgeschlagen.</span></td>
  </tr>
  <?	}else{?>
  	<tr>
  		<td colspan="3" align="center">TIF-Datei erzeugt. <a href="<?echo $this->tif->tifimage;?>" type="multipart/form-data">Herunterladen</a></td>
  	</tr>
  	<tr>
  		<td colspan="3" align="center">TFW-Datei erzeugt. <a href="<?echo $this->tif->tfwfile;?>" target="_blank" type="multipart/form-data">Herunterladen</a></td>
  	</tr>
  <?	}
  	}?>
</table>

<input type="hidden" name="go" value="TIF_Export">


