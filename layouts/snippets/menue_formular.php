<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript">
</script>
<div class="center-outerdiv">
	<div class="input-form">
		<h2><?php echo $this->titel; ?></h2><?php
		echo $this->menue->as_form_html(); ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input value="zurück" title="Zeigt die ganze Liste an." type="button" name="go" onclick="document.location.href='index.php?go=Menues_Anzeigen#menue_<?php echo $this->menue->get('id'); ?>'">&nbsp;<?php
						if ($this->menue->get('id') != '') { ?>
							<input value="Ändern" title="Änderungen Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Ändern')">&nbsp;
							<input value="Zurücksetzen" title="Setzt alle Werte zurück auf die vorhigen." type="reset" name="reset1">&nbsp;
							<input type="hidden" name="selected_menue_id" value="<?php echo  $this->menue->get('id'); ?>"><?php
						}
						else { ?>
							<input value="Speichern" title="Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Speichern')">&nbsp;
							<input value="Zurücksetzen" title="Leert das gesamte Formular." type="button" onclick="document.location.href='index.php?go=Menueeditor&selected_menue_id=<?php echo  $this->menue->get('id'); ?>'">&nbsp;<?
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