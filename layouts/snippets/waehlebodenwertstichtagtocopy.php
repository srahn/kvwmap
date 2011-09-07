<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" valign="middle">
      <table border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td colspan="2" align="center" bgcolor="<?php echo BG_FORM ?>"><strong><?php echo $this->titel; ?></strong></td>
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
        <td align="left" bgcolor="<?php echo BG_FORM ?>"><input name="newStichtag" type="text" value="YYYY-12-31" size="11" maxlength="10">
        </td>
      </tr>
      <tr>
        <td bgcolor="<?php echo BG_FORM ?>">Soll auch ein neuer Layer zum Stichtag angelegt werden?<em><font size="-1"><br>
          (Wenn
              ein Layer angelegt werden soll,<br> 
            muss anschlie&szlig;end noch der
              Stelle zugeordnet werden!)</font></em><br>
          <em></em></td>
        <td align="left" bgcolor="<?php echo BG_FORM ?>"><input name="newbwlayer" type="radio" value="1" checked>ja<input name="newbwlayer" type="radio" value="0">nein</td>
      </tr>
      <tr>
        <td bgcolor="<?php echo BG_FORM ?>">Zu welcher Gruppen_id soll
          der Layer geh&ouml;ren?</td>
        <td align="left" bgcolor="<?php echo BG_FORM ?>"><input name="group_id" type="text" id="group_id" size="3" maxlength="5"></td>
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
