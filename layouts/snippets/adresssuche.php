<?php
  include(LAYOUTPATH.'languages/adresssuche_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script language="JavaScript">
  function getAddressText() {
	return getGemeindeAddressText()+' '+getStrasseAddressText()+' '+getHausNrAddressText(); 
  }
  
  function getGemeindeAddressText() {
    return getSelectedOptionContent('GemID');
  }
  
  function getStrasseAddressText() {
    strasseAddressText = '';
	if (document.getElementsByName('StrID').length == 0) {
	  strasseAddressText = document.getElementsByName('StrName')[0].value;
	} 
	else {
	  strasseAddressText = getSelectedOptionContent('StrID');
    } 	
    return strasseAddressText;
  }

  function getHausNrAddressText() {
    hausNrAddressText = '';
	if (document.getElementsByName('selectedHausID').length == 0) {
	  hausNrAddressText = document.getElementsByName('HausNr')[0].value;
	} 
	else {
	  hausNrAddressText = getFirstOptionContent('selectedHausID');
    } 	
    return hausNrAddressText;
  }
</script>

<input type="hidden" name="go" value="<? if($this->formvars['ALK_Suche']) echo 'ALK-'; ?>Adresse_Auswaehlen">
<br><h2><?php echo $this->titel; ?></h2><br>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
<table border="0" cellspacing="2" cellpadding="0">
<!--
<tr>
  <td>&nbsp;</td>
  <td>
    <input type="checkbox" name="searchInExtent" value="1"<?php if ($this->searchInExtent) { ?> checked<?php } ?>>
    nur im aktuellen Kartenfenster
  </td>
</tr>
//-->
  <tr <? if($this->FormObject["Gemarkungen"]->selected){ echo 'style="display:none"'; }?>>
    <td align="right"><span class="fett px15"><?php echo $strGem; ?>:&nbsp;</span></td>
    <td colspan="3"><?php echo $this->FormObject["Gemeinden"]->html; ?><!-- Name GemID //-->
    </td>
  </tr>
  <tr <? if($this->FormObject["Gemeinden"]->selected){ echo 'style="display:none"'; }?>>
    <td align="right"><span class="fett px15"><?php echo $strGemkg; ?>:&nbsp;</span></td>
    <td colspan="3"><?php echo $this->FormObject["Gemarkungen"]->html; ?><!-- Name GemkgID //-->
    </td>
  </tr>
  <tr>
    <td align="right"><span class="fett px15"><?php echo $strStreet; ?>:&nbsp;</span></td>
    <td colspan="3"><?php echo $this->FormObject["Strassen"]->html; ?><!-- Name StrID //--></td>
  </tr>
  <tr>
    <td align="right"><span class="fett px15"><?php echo $strHnr; ?>:&nbsp;</span></td>
    <td>
    <? if($this->FormObject["selectedHausNr"]->html != ''){ ?>
    	<?php echo $strSelected; ?>:<br>
    	<?php echo $this->FormObject["selectedHausNr"]->html; ?>
    </td>
    <td align="center" valign="middle" width="1">
    	<input type="button" name="addPlaces" value="&lt;&lt;" onClick="addOptions(document.GUI.HausID,document.GUI.selectedHausID,document.GUI.selHausID,'value'); blur();">
    	<input type="button" name="substractPlaces" value="&gt;&gt;" onClick="substractOptions(document.GUI.selectedHausID,document.GUI.selHausID,'value'); blur();">
    </td>
    <td>
    	<?php echo $strExist; ?>:<br>
    	<? } ?>
    	<?php echo $this->FormObject["HausNr"]->html; ?>
    </td>
  </tr>
  <tr align="center">
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr align="center">
    <td align="right">&nbsp;
<!--
      <input type="submit" name="aktualisieren" value="<?php echo $strClear; ?>">
-->
    </td>
    <td align="left" colspan="3">
      <input type="hidden" name="go_plus" id="go_plus" value="">
      <input type="button" name="dummy" value="<?php echo $strSearch; ?>" onclick="submitWithValue('GUI','go_plus','Suchen')">
	  <?php if ($this->Stelle->isFunctionAllowed('Haltestellen_Suche')) {
	    ?><a href="#" onclick="this.href='index.php?go=Haltestellen_Suche&defaultAddress='+getAddressText();">Haltestellen in der Nähe Suchen</a><?php
	  }	?>	  
   </td>
  </tr>
</table>
<input type="hidden" name="selHausID" value="<? echo $this->formvars['selHausID']; ?>">
<input type="hidden" name="ALK_Suche" value="<? echo $this->formvars['ALK_Suche']; ?>">
<input name="go_next" type="hidden" value="<? echo $this->formvars['go_next']; ?>">
