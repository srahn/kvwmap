<?php
  include(LAYOUTPATH.'languages/adresssuche_'.$this->user->rolle->language.'.php');
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
<style>
#ads_formular {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
#ads_formular select, #ads_formular input[type="text"], .ads_suche_hnr select {
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
#ads_formular .ads_suche select, #ads_formular input[type="text"] {
	height: 25px;
}
#ads_formular select {
	width: 260px;
}
.ads_suche, .ads_suche_hnr {
	width:100%;
	height: 28px;
	margin: 0px 0px 10px 0px;
	display: flex;
	flex-flow: row nowrap;
}
.ads_suche_hnr {
	<?	if($this->FormObject["selectedHausNr"]->html != ''){ ?>margin: 0px 0px 250px 0px;<? } ?>
}
.ads_suche div:first-child, .ads_suche_hnr>div:first-child {
	width: 180px;
	text-align: left;
	margin: auto 0px;
}
.ads_suche_form span {
	text-align: left;
	margin: 5px auto;
}
.ads_suche_form_add {
	width: 100px;
	text-align: center;
}
</style>

<input type="hidden" name="go" value="<? if($this->formvars['ALK_Suche']) echo 'ALK-'; ?>Adresse_Auswaehlen">
<br><h2><?php echo $this->titel; ?></h2>
<?php if ($this->Fehlermeldung!='') {
include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>

<div id="ads_formular">
	<div class="ads_suche" <? if($this->FormObject["Gemarkungen"]->selected){ echo 'style="display: none;"'; }?>>
		<div><?php echo $strGem; ?></div>
		<div><?php echo $this->FormObject["Gemeinden"]->html; ?></div>
	</div>
	<div class="ads_suche" <? if($this->FormObject["Gemeinden"]->selected){ echo 'style="display: none;"'; }?>>
		<div><?php echo $strGemkg; ?></div>
		<div><?php echo $this->FormObject["Gemarkungen"]->html; ?></div>
	</div>
<?	if($this->FormObject["Strassen"] != ''){ ?>
	<div class="ads_suche">
		<div><?php echo $strStreet; ?></div>
		<div><?php echo $this->FormObject["Strassen"]->html; ?></div>
	</div>
<?	}
	if($this->FormObject["HausNr"] != ''){ ?>
	<div class="ads_suche_hnr">
		<div><?php echo $strHnr; ?></div>
	<?	if($this->FormObject["selectedHausNr"]->html != ''){ ?>
		<div>
			<table class="ads_suche_form">
				<tr>
					<td colspan="2"><span><?php echo $strSelected; ?></span></td>
					<td><span><?php echo $strExist; ?></span></td>
				</tr>
				<tr>
					<td><?php echo $this->FormObject["selectedHausNr"]->html; ?></td>
					<td class="ads_suche_form_add">
						<div><input type="button" name="addPlaces" value="&laquo;" onClick="addOptions(document.GUI.HausID,document.GUI.selectedHausID,document.GUI.selHausID,'value'); blur();"></div>
						<div><input type="button" name="substractPlaces" value="&raquo;" onClick="substractOptions(document.GUI.selectedHausID,document.GUI.selHausID,'value'); blur();"></div>
					</td>
					<td><?php echo $this->FormObject["HausNr"]->html; ?></td>
				</tr>
				</tr>
			</table>
		</div>
		<?	} else { ?>
		<div><?php echo $this->FormObject["HausNr"]->html; ?></div>
	<?	} ?>
	</div>
<?	} ?>
		
</div>
<div>
	<input type="hidden" name="go_plus" id="go_plus" value="">
	<input type="button" name="dummy" value="<?php echo $strSearch; ?>" onclick="submitWithValue('GUI','go_plus','Suchen')">
	<?php if ($this->Stelle->isFunctionAllowed('Haltestellen_Suche')) { ?>
		<a href="#" onclick="this.href='index.php?go=Haltestellen_Suche&defaultAddress='+getAddressText();">Haltestellen in der Nähe Suchen</a>
	<? } ?>
</div>

<input type="hidden" name="selHausID" value="<? echo $this->formvars['selHausID']; ?>">
<input type="hidden" name="ALK_Suche" value="<? echo $this->formvars['ALK_Suche']; ?>">
<input name="go_next" type="hidden" value="<? echo $this->formvars['go_next']; ?>">
