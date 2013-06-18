<SCRIPT TYPE="text/javascript">	
<!--

function change_order(order){
	document.GUI.order.value = order;
	document.GUI.submit();
}

function create_protocol(){
	document.GUI.target = '_blank';
	document.GUI.submit();
	document.GUI.target = '';
	document.GUI.go_plus.value = '';
}

-->
</SCRIPT>

<table width="950px" border="0" cellpadding="5" cellspacing="0">
	<tr><td></td></tr>
  <tr> 
    <td align="center" bgcolor="<?php echo BG_FORM ?>"><strong><font size="+1"><?php echo $this->titel; ?></font></strong></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>">
	
	<?php if ($this->Fehlermeldung!='') { include(LAYOUTPATH."snippets/Fehlermeldung.php"); } ?>
	<strong><font color="#FF0000">
	<?php if ($this->Meldung!='') { echo $this->Meldung; } ?>
	</font> </strong>
    </td>
  </tr>
  <tr> 
    <td width="100%" bgcolor="<?php echo BG_FORM ?>">
    	<table width="100%" border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="4">
        <tr bgcolor="#FFFFFF"> 
          <td width="40px" <? if($this->formvars['Lfd'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>Lfd</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Lfd" <? if($this->formvars['Lfd'] == 1)echo 'checked=true;'; ?>></td>
          <td width="106px" <? if($this->formvars['Riss-Nummer'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><a href="javascript:change_order('rissnummer');" title="nach Riss-Nummer sortieren"><strong>Riss-Nummer</strong></a><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Riss-Nummer" <? if($this->formvars['Riss-Nummer'] == 1)echo 'checked=true;'; ?>></td>
          <td width="128px" <? if($this->formvars['Antrags-Nummer'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><a href="javascript:change_order('stammnr');" title="nach Antrags-Nummer sortieren"><strong>Antrags-Nummer</strong></a><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Antrags-Nummer" <? if($this->formvars['Antrags-Nummer'] == 1)echo 'checked=true;'; ?>></td>
          <td width="50px" <? if($this->formvars['FFR'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>FFR</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="FFR" <? if($this->formvars['FFR'] == 1)echo 'checked=true;'; ?>></td>
          <td width="50px" <? if($this->formvars['KVZ'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>KVZ</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="KVZ" <? if($this->formvars['KVZ'] == 1)echo 'checked=true;'; ?>></td>
          <td width="45px" <? if($this->formvars['GN'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>GN</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="GN" <? if($this->formvars['GN'] == 1)echo 'checked=true;'; ?>></td>
          <td width="70px" <? if($this->formvars['andere'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>andere</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="andere" <? if($this->formvars['andere'] == 1)echo 'checked=true;'; ?>></td>
          <td width="60px" <? if($this->formvars['Datum'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>Datum</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Datum" <? if($this->formvars['Datum'] == 1)echo 'checked=true;'; ?>></td>
          <td width="55px" <? if($this->formvars['Datei'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>Datei</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Datei" <? if($this->formvars['Datei'] == 1)echo 'checked=true;'; ?>></td>
          <td width="130px" <? if($this->formvars['gemessendurch'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><strong>gemessen durch</strong><input type="checkbox" onchange="document.GUI.submit();" value="1" name="gemessendurch" <? if($this->formvars['gemessendurch'] == 1)echo 'checked=true;'; ?>></td>
        </tr>
        <? for($i=0; $i < count($this->antrag->FFR); $i++){ ?>
        <tr bgcolor="#ffffff"> 
          <td align="center" <? if($this->formvars['Lfd'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Lfd']; ?></td>
          <td align="center" <? if($this->formvars['Riss-Nummer'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Riss-Nummer']; ?></td>
          <td align="center" <? if($this->formvars['Antrags-Nummer'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Antrags-Nummer']; ?></td>
          <td align="center" <? if($this->formvars['FFR'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['FFR']; ?></td>
          <td align="center" <? if($this->formvars['KVZ'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['KVZ']; ?></td>
          <td align="center" <? if($this->formvars['GN'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['GN']; ?></td>
          <td align="center" <? if($this->formvars['andere'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['andere']; ?></td>
          <td align="center" <? if($this->formvars['Datum'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Datum']; ?></td>
          <td align="center" <? if($this->formvars['Datei'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Datei']; ?></td>
          <td align="center" <? if($this->formvars['gemessendurch'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['gemessen durch']; ?></td>
        </tr>
        <? }?>
      </table>
    </td>
  </tr>
  <tr> 
    <td bgcolor="<? echo BG_FORM ?>">
    	Übergabeprotokoll erzeugen als: 
    	<select name="go_plus" onchange="create_protocol();">
    		<option value="">--- bitte auswählen ---</option>
				<option value="PDF">PDF</option>
				<option value="CSV">CSV</option>
      </select>
      <input type="hidden" name="go" value="Antraganzeige_Uebergabeprotokoll_Erzeugen">
      <input type="hidden" name="order" value="<? echo $this->formvars['order']; ?>">
      <input type="hidden" name="antr_selected" value="<? echo $this->formvars['antr_selected']; ?>">
    </td>
  </tr>
</table>
