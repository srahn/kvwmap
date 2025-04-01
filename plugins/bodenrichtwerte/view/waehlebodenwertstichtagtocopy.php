<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" valign="middle">
      <table border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td colspan="2" align="center" bgcolor="<?php echo BG_FORM ?>"><h2><?php echo $this->titel; ?></h2></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="<?php echo BG_FORM ?>"><div align="center"> <br>
          Welche
              Bodenrichtwertzonen sollen kopiert werden? <br> 
              </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="<?php echo BG_FORM ?>"> </td>
      </tr>
      <!--<tr>
    <td align="right" bgcolor="<?php echo BG_FORM ?>"><input type="checkbox" name="loeschenDateien" value="1" checked></td>
    <td bgcolor="<?php echo BG_FORM ?>">Auch die Bilddateien vom Server l&ouml;schen?</td>
  </tr> //-->
      <tr>
        <td bgcolor="<?php echo BG_FORM ?>">Stichtag der Zonen, die kopiert werden
          sollen:</td>
        <td align="left" bgcolor="<?php echo BG_FORM ?>"><?php 
	$this->Stichtagform->outputHTML();
	echo $this->Stichtagform->html;
	 ?>
        </td>
      </tr>
      <tr>
        <td bgcolor="<?php echo BG_FORM ?>">Stichtag,&nbsp;der&nbsp;f&uuml;r&nbsp;die&nbsp;neuen&nbsp;Zonen&nbsp;an&nbsp;gegeben&nbsp;werden
          soll.</td>
        <td align="left" bgcolor="<?php echo BG_FORM ?>"><input name="newStichtag" type="text" value="31.12.YYYY" size="11" maxlength="10">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center" bgcolor="<?php echo BG_FORM ?>">
            <input type="hidden" name="go" value="BodenrichtwertzonenKopieren">
			<input type="submit" name="submit" value="Abbrechen">&nbsp;<input type="submit" name="go_plus" value="Senden">
        </td>
        </tr>
    </table></td>
  </tr>
</table>
