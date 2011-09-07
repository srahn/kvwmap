<strong><font size="+2"><?php echo $this->titel; ?></font></strong>
<br><br>
<strong><font size="+1">Neue Kategorie hinzufügen</font></strong>
<br>
<br>
<table align="center" border="1" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" rules="groups">
  <tr align="center"> 
    <td align="center"> 
	  <table align="center" id="tablemonth" style="display: <?php if ($this->formvars['zeitraum']=='month' OR $this->formvars['zeitraum']==''){ echo 'block';} else { echo 'none';} ?>" width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <th> Kategorie: </th>
          <td> <input name="newKategorie" id="newKategorie" type="text" size="20" value=""> </td>
		  <td> <input type="submit" name="senden" value="Hinzufügen"> </td>
        </tr>
      </table> 
</table>
<br><br>

<input type="hidden" name="go" value="NotizKategorie_anlegen">
<input type="hidden" name="go_plus" value="">
