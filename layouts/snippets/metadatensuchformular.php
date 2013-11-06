<?php
if ($this->Fehlermeldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?>

<table border="0" cellpadding="2" cellspacing="0">
  <tr> 
    <td colspan="5" align="center"><strong><font size="+1"> 
      <?php echo $this->titel; ?>
      </font></strong> 
      <?php
	 if ($this->Fehlermeldung!='') {
	echo $this->Fehlermeldung;
	} 
      ?><script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
      <script language="JavaScript" src="funktionen/bboxformfunctions.js" type="text/javascript">
	  </script>
</td>
  </tr>
  <tr>
    <td colspan="5" align="center"><hr></td>
  </tr>
  <tr> 
    <td width="47" valign="middle"><p><strong>Was?</strong></p>
    </td>
    <td colspan="2" valign="top"><input name="was" type="text" value="<?php echo $this->formvars['was']; ?>" size="30">
      <em>Schlagwort</em></td>
    <td width="31" rowspan="5" align="right" valign="top"><strong>Wo?</strong></td>
    <td width="186" rowspan="5" valign="top"> <input name="wo" type="text" value="<?php echo $this->formvars['wo']; ?>" size="26">
      <em>Ort</em>      <table border="0" cellspacing="0" cellpadding="2">
        <tr align="center">
          <td colspan="2"> <em>N</em>&nbsp;<input name="northbl" type="text" value="<?php echo $this->formvars['northbl']; ?>" size="9">
          </td>
        </tr>
        <tr align="center">
          <td><em>W</em>&nbsp;<input name="westbl" type="text" value="<?php echo $this->formvars['westbl']; ?>" size="9">
          </td>
          <td><em>O</em>&nbsp;<input name="eastbl" type="text" value="<?php echo $this->formvars['eastbl']; ?>" size="9">
          </td>
        </tr>
        <tr align="center">
          <td colspan="2"><em>S</em>&nbsp;<input name="southbl" type="text" value="<?php echo $this->formvars['southbl']; ?>" size="9"> 
          </td>
        </tr>
        <tr align="center">
          <td colspan="2"><input type="button" name="bboxfrommap" value="Box aus Karte" onClick=setBBoxFromMap(document.GUI.pathx.value,document.GUI.pathy.value,<?php echo $this->user->rolle->pixsize; ?>,<?php echo $this->map->extent->minx; ?>,<?php echo $this->map->extent->miny; ?>,document.GUI.westbl,document.GUI.eastbl,document.GUI.southbl,document.GUI.northbl)>
          </td>
        </tr>
            </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" valign="middle"><hr></td>
  </tr>
  <tr>
    <td valign="middle"><strong>Wer?</strong></td>
    <td colspan="2" valign="top"><input name="wer" type="text" value="<?php echo $this->formvars['wer']; ?>">
      <em>Person/Organisation</em> </td>
  </tr>
  <tr>
    <td colspan="3" valign="middle"><hr></td>
  </tr>
  <tr>
    <td valign="middle"><strong>Wann?</strong></td>
    <td width="96" valign="top"><table border="0" cellpadding="2" cellspacing="0">
      <tr>
        <td><?php
		 if ($this->formvars['vonwann']!='') {
		   $value=$this->formvars['vonwann'];
		 }
		 else {
		   $value='1970-01-01'; 
		 }
		?><input name="vonwann" type="text" value="<?php echo $value; ?>" size="10">
          </td>
        <td><em>von </em></td>

      </tr>
      <tr>
        <td><?php
		 if ($this->formvars['biswann']!='') {
		   $value=$this->formvars['biswann'];
		 }
		 else {
		   $value=date("Y-m-d",time()); 
		 }
		?><input name="biswann" type="text" value="<?php echo $value; ?>" size="10">
        </td>
        <td> <em>bis</em></td>
        </tr>
    </table></td>
    <td width="185" align="center" valign="middle"><input type="hidden" name="go" value="Metadaten_Auswaehlen" >
    <input type="submit" name="go_plus" value="Senden"></td>
  </tr>
  <tr>
    <td colspan="5" align="center" valign="top"><?php
        include(LAYOUTPATH.'snippets/SVG_metadatenformular.php');
    ?>
    </td>
  </tr>
</table>
<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>">
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>">
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>">
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>">
<INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">            
<input type="hidden" name="imgxy" value="300 300">
<input type="hidden" name="imgbox" value="-1 -1 -1 -1">	      