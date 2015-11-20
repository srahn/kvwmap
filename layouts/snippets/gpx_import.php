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
  <tr>
  	<td>&nbsp;</td>
		<td colspan="2" align="center" style="border-bottom:1px solid #C3C7C3">
			<span class="fett">GPX-Datei</span>
			<input class="button" type="file" name="gpxfile" size="12">
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td height="30"><span class="fett">Was soll importiert werden?</span></td>
		<td><input type="checkbox" name="tracks" value="1" checked>Tracks</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><input type="checkbox" name="waypoints" value="1" checked>Waypoints</td>
		<td></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" align="center" style="border-top:1px solid #C3C7C3"><input class="button" type="submit" name="go_plus" value="Laden"></td>
		<td>&nbsp;</td>
	</tr>	
</table>

<input type="hidden" name="go" value="GPX_Import">


