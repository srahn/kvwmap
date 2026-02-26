<?
	include(LAYOUTPATH . 'languages/menue_formular_' . rolle::$language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<div class="center-outerdiv">
	<div class="input-form" style="min-width: 620px">
		<h2><?php echo $this->titel; ?></h2>
		<em><span class="px13"><? echo $this->strAsteriskRequired; ?></span></em><br><?php
		echo $this->menue->as_form_html(); ?>
		<div class="clear"></div>
		<label class="fetter" for="title">Zuordnung zu Stellen</label>
		<div name="stellenzuweisung" style="margin-top: 5px; text-align: left">
			<?php
			$this->stellen = $this->Stelle->getstellen('bezeichnung');
			// Wenn es eine Fehlermeldung gibt, wurden die Stellen wahrscheinlich nicht korrekt übergeben. In diesem Fall soll die Auswahl der Stellen anhand der übergebenen Formulardaten erfolgen, damit der Benutzer seine Auswahl nicht erneut treffen muss.
			$selstellen = $this->Fehlermeldung != '' ? $this->formvars['selstellen'] : implode(',', array_map(function($stelle) { return $stelle->get('stelle_id'); }, $this->menue->stellen));
			if ($size == 0) $size++;
			echo FormObject::createSelectField(
				'selstellen', // name
				array_map(
					function($stelle) {
						return array(
							'value' => $stelle['ID'],
							'output' => $stelle['Bezeichnung']
						);
					},
					vectors_to_assoc_array($this->stellen)
				), // options
				$selstellen, // values
				max(1, min(20, count($this->stellen))), // size
				'width: 370px', // style
				'', // onchange
				'stellen_ids', // id
				true, // multiple
				'', // class
				'---Stellen auswählen---', // first_option
				'', // option_style
				'', // option_class
				'', // $onclick
				'', // onmouseenter
				'Stellen in denen der Menüpunkt zugeordnet sein soll.', // title
			); ?>
		</div>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input
							value="<? echo $this->strButtonBack; ?>"
							title="<? echo $strShowMenueList; ?>"
							type="button"
							name="go"
							onclick="document.location.href='index.php?go=Menues_Anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>#menue_<?php echo $this->menue->get('id'); ?>'"
						>&nbsp;<?php
						if ($this->menue->get('id') != '') { ?>
							<input value="<? echo $this->strChange; ?>" title="<? echo $this->strChangeTitle; ?>" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Ändern')">&nbsp;
							<input value="<? echo $this->strReset; ?>" title="<? echo $this->strResetTitle; ?>." type="reset" name="reset1">&nbsp;
							<input value="<? echo $strCreateAsNewMenue; ?>" title="Als neuen Menüpunkt eintragen" type="button" onclick="submitWithValue('GUI','go_plus','Als neuen Menüpunkt Eintragen')">
							<input type="hidden" name="selected_menue_id" value="<?php echo  $this->menue->get('id'); ?>"><?php
						}
						else { ?>
							<input value="<? echo $this->strSave; ?>" title="<? echo $this->strSave; ?>" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Speichern')">&nbsp;
							<input value="<? echo $this->strReset; ?>" title="<? echo $this->strResetTitle; ?>" type="button" onclick="document.location.href='index.php?go=Menueeditor&selected_menue_id=<?php echo $this->menue->get('id'); ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>'"><?
						} ?>
						<!--input type="button" name="dummy" value="Als neues Menü eintragen" onclick="submitWithValue('GUI','go_plus','Als neues Menü eintragen')"-->
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="go" value="Menue">
			<input type="hidden" name="selected_menue_id" value="<? echo  $this->menue->get('id'); ?>">
		</div>
	</div>
</div>
<script>
	function getMenueLink(elm) {
		if (elm.name == 'onclick') {
			return '?' + elm.value.match(/'([^']+)'/)[1];
		}
		else {
			return elm.value . '&csrf_token=' . $_SESSION['csrf_token'];
		}
	}
	function createMenueLink(elm) {
		$('input[name=' + elm.name + ']').attr('style', 'width: 374px').after($('<a id="link_' + elm.name + '" style="\
			float: right;\
			margin-top: 2px;\
			font-size: 19px;\
			margin-right: 2px;\
		" href="' + getMenueLink(elm) + '" target="_blank" title="Link in neuem Tab anzeigen"><i style="color: firebrick" class="fa fa-external-link" aria-hidden="true"></i></a>'));
	}
	function updateMenueLink(elm) {
		var link;
		if (elm.value != '' && !elm.value.includes('javascript:void(0)')) {
			if ($('#link_' + elm.name).length == 0) {
				createMenueLink(elm);
			}
			else {
				$('#link_' + elm.name).attr('href', getMenueLink(elm));
			}
		}
		else {
			if ($('#link_' + elm.name).length > 0) {
				removeMenueLink(elm);
			}
		}
	}
	function removeMenueLink(elm) {
		$('input[name=' + elm.name + ']').attr('style', 'width: 400px');
		$('#link_' + elm.name).remove();
	}

	$('input[name=links], input[name=onclick]').on(
		'change',
		function(evt) {
			updateMenueLink(evt.target);
		}
	);

	updateMenueLink($('input[name=links]')[0]);
	updateMenueLink($('input[name=onclick]')[0]);
</script>