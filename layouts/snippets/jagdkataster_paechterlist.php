<table border="0" width="100%" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr>
    <td align="right">&nbsp;</td>
  </tr>
  <tr align="center">
    <td><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
  </tr>
  <?php
  for ($i = 0; $i < count($this->paechter); $i++) {
    $jagdkataster = new jagdkataster($this->pgdatabase);
	$bezirkliste = $jagdkataster->getjagdbezirkfrompaechter($this->paechter[$i]['id']);
  ?>
  <tr>
    <td>
      <table width="100%" border="1" cellspacing="1" cellpadding="0">
      <colgroup>
       <col width="50%">
       <col width="*">
      <colgroup>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
            <colgroup>
             <col width="25%">
             <col width="*">
            <colgroup>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">P&auml;chter</span>&nbsp;</td>
                <td>&nbsp;<? echo $this->paechter[$i]['nachname'].", ".$this->paechter[$i]['vorname']; ?></td>
              </tr>
            </table>
          </td>
          <td rowspan="2" valign="top">
            <table width="100%" border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Jagdaus&uuml;bung:</span></td>
              </tr>
            <?php
            for ($j = 0; $j < count($bezirkliste); $j++) {
            ?>
              <tr>
                <td>
                  <a href="index.php?go=jagdbezirk_show_data&oid=<?php echo $bezirkliste[$j]['oid']; ?>">
                  <?
                  echo $bezirkliste[$j]['name']." (";
                  $art = $bezirkliste[$j]['art'];
                  switch ($art) {
                    case ejb:
                      echo "Eigenjagdbezirk";
                      break;
                    case gjb:
                      echo "Gemeinschaftlicher Jagdbezirk";
                      break;
                    case tjb:
                      echo "Teiljagdbezirk";
                      break;
                    case sf:
                      echo "Sonderfläche";
                      break;
                  }
                  echo ")";
                  ?>
                  </a>
                </td>
              </tr>
            <? } ?>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="1" cellspacing="2" cellpadding="0">
            <colgroup>
             <col width="25%">
             <col width="*">
            <colgroup>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">PLZ</span></td>
                <td><? if ($this->paechter[$i]['plz']==''){ echo "&nbsp;"; } else { echo $this->paechter[$i]['plz']; } ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Wohnort</span></td>
                <td><? if ($this->paechter[$i]['ort']==''){ echo "&nbsp;"; } else { echo $this->paechter[$i]['ort']; } ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Stra&szlig;e</span></td>
                <td><? if ($this->paechter[$i]['strasse']==''){ echo "&nbsp;"; } else { echo $this->paechter[$i]['strasse']; } ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Telefon</span></td>
                <td><? if ($this->paechter[$i]['telefon']==''){ echo "&nbsp;"; } else { echo $this->paechter[$i]['telefon']; } ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Geburtsort</span></td>
                <td><? if ($this->paechter[$i]['geburtsort']==''){ echo "&nbsp;"; } else { echo $this->paechter[$i]['geburtsort']; } ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo BG_DEFAULT ?>"><span class="fett">Geburtstag</span></td>
                <?php
                $geburtstag = $this->paechter[$i]['geburtstag'];
                if ($geburtstag!='') {
                  $explosion=explode('-',substr($geburtstag,0,10));
                  $geburtstag=$explosion[2].".".$explosion[1].".".$explosion[0];
                }
                ?>
                <td><? if ($geburtstag==''){ echo "&nbsp;"; } else { echo $geburtstag; } ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <?php
  }
  ?>

  <tr>
    <td align="center"><a href="javascript:document.GUI.submit()">zurück zum Jagdbezirk</a></td>
  </tr>


  <tr>
    <td align="right">&nbsp;</td>
  </tr>
</table>

<? if($this->formvars['go'] == 'jagdbezirk_show_data'){ ?>
<input name="form_field_names" type="hidden" value="<?php echo $this->form_field_names; ?>">
<input name="search" type="hidden" value="true">
<input name="selected_layer_id" type="hidden" value="<? echo $this->qlayerset[$i]['Layer_ID']; ?>">
<input name="operator_oid" type="hidden" value="=">
<input name="value_oid" type="hidden" value="<? echo $this->qlayerset[$i]['shape'][0]['oid']; ?>">
<input name="keinzurueck" type="hidden" value="1">
<? } ?>
<input name="go" type="hidden" value="jagdbezirk_show_data">
<input name="name" type="hidden" value="<? echo $this->formvars['name']; ?>">
<input name="oid" type="hidden" value="<?php echo $this->formvars['oid']; ?>">
<input name="search_nummer" type="hidden" value="<?php echo $this->formvars['search_nummer']; ?>">
<input name="search_name" type="hidden" value="<?php echo $this->formvars['search_name']; ?>">
<input name="search_art" type="hidden" value="<?php echo $this->formvars['search_art']; ?>">
<input name="search_status" type="hidden" value="<?php echo $this->formvars['search_status']; ?>">
<input name="FlurstKennz" type="hidden" value="">
<input name="jagdkataster" type="hidden" value="true">