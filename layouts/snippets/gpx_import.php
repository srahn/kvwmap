<?php
 # 2008-01-22 pkvvm
  include(LAYOUTPATH.'languages/shape_import_'.$this->user->rolle->language.'.php');
 ?>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="3" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="3"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <? if($this->gpx->formvars['gpxfile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><span class="fett">GPX-Datei</span>
		<input class="button" type="file" name="gpxfile" size="12">
		<input class="button" type="submit" name="goplus" value="Laden"></td>
		<td>&nbsp;</td>
	</tr>
	<? }
			elseif($this->gpx->formvars['dbffile'] == ''){ ?>
	<tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><span class="fett">GPX-Datei</span>
		<input class="button" type="file" name="gpxfile" size="12">
		<input class="button" type="submit" name="goplus" value="Laden"></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>Laden der GPX-Datei nicht erfolgreich.</td>
		<td>&nbsp;</td>
	</tr> 
	<? }else{ ?>
	<tr>
		<td>&nbsp;</td>
		<td>GPX-Datei erfolgreich geladen. Wie soll die PostgreSQL-Tabelle heißen?</td>
		<td>&nbsp;</td>
	</tr> 
  <tr>
  	<td>&nbsp;</td>
  	<td align="center">  		
  		<table border="0">
  			<tr>
  				<td align="right"><span class="fett">Tabellenname:</span></td>
  				<td align="center" height="35"><input name="table_name" type="text" value="<? echo array_pop(explode('/', strtolower(dirname($this->gpx->formvars['dbffile'])))); ?>" size="25" class="input"></td>
  			</tr>
  			<tr>
  				<td height="30" align="right"><span class="fett">Optionen:</span></td>
  				<td><input type="radio" name="table_option" value="-c" checked>Tabelle neu anlegen</td>
  			</tr>
  			<tr>
  				<td></td>
  				<td><input type="radio" name="table_option" value="-d">Tabelle überschreiben</td>
  			</tr>
  			<tr>
  				<td></td>
  				<td><input type="radio" name="table_option" value="-a">Daten anhängen</td>
  			</tr>
  		</table>
  	</td>
  	<td>&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3" align="center"><input name="go_plus" value="importieren" type="submit" class="button" ></td>
  </tr>
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="dbffile" value="<? echo $this->gpx->formvars['dbffile']; ?>">
<input type="hidden" name="go" value="GPX_Import">


