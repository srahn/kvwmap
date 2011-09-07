<table width="100%" height="100" border="0" cellpadding="5" cellspacing="0">
  <tr> 
    <td colspan="2" align="left" bgcolor="<?php echo BG_FORM ?>"><div align="center"><strong><?php echo $this->titel; ?></strong></div></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="<?php echo BG_FORM ?>"><div align="center">
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><?php
    if ($this->Fehlermeldung!='') {
	  echo $this->Fehlermeldung;
	}
	else {
	  echo $this->Protokoll;
	}
	?>
          </td>
        </tr>
      </table>
      </div>
	</td>
  </tr>
   <tr> 
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"><div align="right"> 
      </div></td>
    <td width="50%" bgcolor="<?php echo BG_FORM ?>"><input type="submit" name="submit" value="Weiter"></td>
  </tr>
</table>
