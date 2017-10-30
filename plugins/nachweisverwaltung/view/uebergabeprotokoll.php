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

<table width="1200px" border="0" cellpadding="5" cellspacing="0">
	<tr><td></td></tr>
  <tr> 
    <td align="center" bgcolor="<?php echo BG_FORM ?>"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>">
	
	<?php if ($this->Fehlermeldung!='') { include(LAYOUTPATH."snippets/Fehlermeldung.php"); } ?>
	<span class="fett"><font color="#FF0000">
	<?php if ($this->Meldung!='') { echo $this->Meldung; } ?>
	</font> </span>
    </td>
  </tr>
  <tr> 
    <td width="100%" bgcolor="<?php echo BG_FORM ?>">
    	<table width="100%" border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="4">
        <tr bgcolor="#FFFFFF"> 
          <td minwidth="130px" <? if($this->formvars['Riss-Nummer'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><a href="javascript:change_order('rissnummer');" title="nach Riss-Nummer sortieren"><span class="fett">Riss-Nummer</span></a><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Riss-Nummer" <? if($this->formvars['Riss-Nummer'] == 1)echo 'checked=true;'; ?>></td>
          <td minwidth="140px" <? if($this->formvars['Antrags-Nummer'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><a href="javascript:change_order('stammnr');" title="nach Antrags-Nummer sortieren"><span class="fett">Antrags-Nummer</span></a><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Antrags-Nummer" <? if($this->formvars['Antrags-Nummer'] == 1)echo 'checked=true;'; ?>></td>
          <td width="50px" <? if($this->formvars['FFR'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">FFR</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="FFR" <? if($this->formvars['FFR'] == 1)echo 'checked=true;'; ?>></td>
          <td width="50px" <? if($this->formvars['KVZ'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">KVZ</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="KVZ" <? if($this->formvars['KVZ'] == 1)echo 'checked=true;'; ?>></td>
          <td width="45px" <? if($this->formvars['GN'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">GN</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="GN" <? if($this->formvars['GN'] == 1)echo 'checked=true;'; ?>></td>
          <td width="70px" <? if($this->formvars['andere'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">andere</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="andere" <? if($this->formvars['andere'] == 1)echo 'checked=true;'; ?>></td>
          <td width="60px" <? if($this->formvars['Datum'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">Datum</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Datum" <? if($this->formvars['Datum'] == 1)echo 'checked=true;'; ?>></td>
          <td width="250px" <? if($this->formvars['Datei'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">Datei</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Datei" <? if($this->formvars['Datei'] == 1)echo 'checked=true;'; ?>></td>
          <td width="130px" <? if($this->formvars['gemessendurch'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">gemessen durch</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="gemessendurch" <? if($this->formvars['gemessendurch'] == 1)echo 'checked=true;'; ?>></td>
		  <td width="85px" <? if($this->formvars['Gueltigkeit'] != 1) echo 'bgcolor="#EBEBEB"'; ?>><span class="fett">Gültigkeit</span><input type="checkbox" onchange="document.GUI.submit();" value="1" name="Gueltigkeit" <? if($this->formvars['Gueltigkeit'] == 1)echo 'checked=true;'; ?>></td>
        </tr>
        <? for($i=0; $i < count($this->antrag->FFR); $i++){ ?>
        <tr bgcolor="#ffffff"> 
          <td valign="top" align="center" <? if($this->formvars['Riss-Nummer'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Rissnummer']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['Antrags-Nummer'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Antragsnummer']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['FFR'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['FFR']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['KVZ'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['KVZ']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['GN'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['GN']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['andere'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['andere']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['Datum'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Datum']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['Datei'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Datei']; ?></td>
          <td valign="top" align="center" <? if($this->formvars['gemessendurch'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.utf8_encode($this->antrag->FFR[$i]['gemessen durch']); ?></td>
		  <td align="center" <? if($this->formvars['Gueltigkeit'] != 1) echo 'bgcolor="#EBEBEB">';else echo '>'.$this->antrag->FFR[$i]['Gueltigkeit']; ?></td>
        </tr>
        <? }?>
      </table>
    </td>
  </tr>
  <tr> 
    <td bgcolor="<? echo BG_FORM ?>">
    	<a href="javascript:create_protocol();">Übergabeprotokoll erzeugen</a> 
      <input type="hidden" name="go" value="Antraganzeige_Uebergabeprotokoll_Erzeugen">
      <input type="hidden" name="order" value="<? echo $this->formvars['order']; ?>">
      <input type="hidden" name="antr_selected" value="<? echo $this->formvars['antr_selected']; ?>">
    </td>
  </tr>
</table>
