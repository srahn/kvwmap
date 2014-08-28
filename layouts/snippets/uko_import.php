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
  <? if($this->uko->formvars['ukofile'] == ''){ ?>
  <tr>
  	<td>&nbsp;</td>
		<td align="center" style="border-bottom:1px solid #C3C7C3"><span class="fett">UKO-Datei</span>
		<input class="button" type="file" name="ukofile" size="12">
		<input class="button" type="submit" name="go_plus" value="Importieren"></td>
		<td>&nbsp;</td>
	</tr> 
	<? }else{ ?>
	<tr>
		<td>&nbsp;</td>
		<? if($this->uko->success == true){ ?>
		<td>UKO-Datei erfolgreich importiert</td>
		<? }else{ ?>
			<td>UKO-Datei Import fehlgeschlagen.</td>
		<? } ?>
		<td>&nbsp;</td>
	</tr> 
  <? } ?>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<input type="hidden" name="go" value="UKO_Import">


