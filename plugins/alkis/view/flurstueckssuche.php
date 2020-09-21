<?php
  include(LAYOUTPATH.'languages/flurstueck_suche_'.$this->user->rolle->language.'.php');
 ?>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>

<script type="text/javascript">
<!--

function updateGemarkungsauswahl(){
	document.GUI.gemschl.value = document.GUI.gemschl1.value+document.GUI.gemschl2.value;
	selectbyString(document.GUI.GemkgID, document.GUI.gemschl.value);
	if(document.GUI.gemschl.value.length == 6){
		document.GUI.submit();
	}
}

function showimport(){
	if(document.getElementById('import2').style.visibility == 'visible'){
		document.getElementById('import2').style.visibility = 'hidden';
	}
	else{
		document.getElementById('import2').style.visibility = 'visible';
	}
}

document.onclick =  function(e){
	if(e.target.id != 'importlink' && e.target.closest('div').id != 'import2'){
		document.getElementById('import2').style.visibility = 'hidden';
	}
};

-->
</script>
<style>
#import1 {
	text-align: left;
	margin: 0 0 10px 0;
	padding-left: 20px;
}
#import2 {
	visibility: hidden;
	position: relative;
	margin: 0 0 10px 20px;
	width: 500px;
	height: 40px;
	text-align: left;
	border: 1px solid #aaaaaa;
	padding: 5px;
	background-color: #E6E6E6;
	box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.3);
	z-index: 1;
}
#import2 input[type="submit"] {
	float: right;
	right: 5px;
}
#fsf_formular {
	margin: 40px 0px 20px 0px;
	padding-left: 20px;
}
#fsf_formular select, #fsf_formular input[type="text"], .fsf_suche_fst select {
	border-radius: 2px;
	border: 1px solid #777;
	padding-left: 5px;
}
#fsf_formular .fsf_suche select, #fsf_formular input[type="text"] {
	height: 25px;
}
#fsf_formular select, #fsf_formular input[name="FlstNr"] {
	width: 360px;
}
#fsf_formular input[name="FlurID"] {
	width: 60px;
}
.fsf_suche, .fsf_suche_fst {
	width:100%;
	height: 28px;
	margin: 0px 0px 10px 0px;
	display: flex;
	flex-flow: row nowrap;
}
.fsf_suche_fst {
	<?	if($this->FormObject["selectedFlstNr"]->html != ''){ ?>margin: 0px 0px 250px 0px;<? } ?>
}
.fsf_suche_fst span[data-tooltip]:before {
	vertical-align: bottom;
}
.fsf_suche div:first-child, .fsf_suche_fst>div:first-child {
	width: 180px;
	text-align: left;
	margin: auto 0px;
}
.fsf_suche_form span {
	text-align: left;
	margin: 5px auto;
}
.fsf_suche_form_add {
	width: 80px;
	text-align: center;
}
</style>

<input type="hidden" name="go" value="<? if($this->formvars['ALK_Suche']) echo 'ALK-'; ?>Flurstueck_Auswaehlen">
<br><h2><?php echo $this->titel; ?></h2>
<?php
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH."snippets/Fehlermeldung.php");
}
?>
<div id="fsf_formular">
	<div class="fsf_suche">
		<div><?php echo $strGemkgschl; ?></div>
		<div>
			<input name="gemschl1" type="text" value="<? echo $this->land_schluessel; ?>" style="width:25px;" onkeyup="updateGemarkungsauswahl();">
			<input name="gemschl2" type="text" maxlength="4" value="<? echo substr($this->formvars['GemkgID'], 2, 4); ?>" style="width:46px" onkeyup="updateGemarkungsauswahl();">
			<input name="gemschl" type="hidden" value="<? echo $this->formvars['GemkgID']; ?>">
		</div>
	</div>
	<div class="fsf_suche">
		<div><?php echo $strGemkgGem; ?></div>
		<div><?php echo $this->FormObject["Gemarkungen"]->html; ?></div>
	</div>
	<div class="fsf_suche">
		<div><?php echo $strFlur; ?></div>
		<div><?php echo $this->FormObject["Fluren"]->html; ?></div>
	</div>
	<div class="fsf_suche_fst">
		<div>
			<?php echo $strFst; ?>
		</div>
		<? if($this->FormObject["selectedFlstNr"]->html != ''){ ?>
		<div>
			<table class="fsf_suche_form">
				<tr>
					<td colspan="2"><span><?php echo $strSelected; ?></span></td>
					<td><span><?php echo $strExist; ?></span></td>
				</tr>
				<tr>
					<td><?php echo $this->FormObject["selectedFlstNr"]->html; ?></td>
					<td class="fsf_suche_form_add">
						<div><input type="button" name="addPlaces" value="&laquo;" onClick="addOptions(document.GUI.FlstID,document.GUI.selectedFlstID,document.GUI.selFlstID,'value');"></div>
						<div><input type="button" name="substractPlaces" value="&raquo;" onClick="substractOptions(document.GUI.selectedFlstID,document.GUI.selFlstID,'value');"></div>
					</td>
					<td><?php echo $this->FormObject["FlstNr"]->html; ?></td>
				</tr>		
			</table>
		</div>
		<?	} else { ?>	
		<div>
			<?php echo $this->FormObject["FlstNr"]->html; ?>
			&nbsp;<span style="--left: -300px" data-tooltip="Eingabe eines Kennzeichens ohne Auswahl von Gemarkung/Flur.
Eingabeformate:
13-1234-1-234-5
131234001002340005__
131234-001-00234/005
131234-001-00234/005.00"></span>
		</div>
		<?	} ?>
	</div>
</div>
<div id="import1">
	<a id="importlink" href="javascript:showimport();">Import Flurstücksliste</a>
</div>
<div id="import2">
	<input name="importliste" type="file" value="" tabindex="2">
	<span data-tooltip="Eingabeformate:
13-1234-1-234-5
131234001002340005__
131234-001-00234/005
131234-001-00234/005.00
"></span>
	<input type="submit" value="Laden">
</div>
<div>
	<input type="hidden" name="go_plus" id="go_plus" value="">
	<input type="button" name="dummy" value="<?php echo $strSearch; ?>" onclick="submitWithValue('GUI','go_plus','Suchen')">
</div>
<br>
<br>
<br>
<br>
<input type="hidden" name="selFlstID" value="<? echo $this->formvars['selFlstID']; ?>">
<input name="historical" type="hidden" value="<? echo $this->formvars['historical']; ?>">
<input name="without_temporal_filter" type="hidden" value="<? echo $this->formvars['without_temporal_filter']; ?>">
<input name="ALK_Suche" type="hidden" value="<? echo $this->formvars['ALK_Suche']; ?>">
<input name="go_next" type="hidden" value="<? echo $this->formvars['go_next']; ?>">
