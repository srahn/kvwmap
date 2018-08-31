<?php
	include(LAYOUTPATH . 'languages/invitation_formular_' . $this->user->rolle->language . '.php');
?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<div class="center-outerdiv">
	<div class="input-form">
		<h2><? echo ($this->formvars['selected_invitation_id'] != '' ? $strTitelEdit : $strTitelNew); ?></h2>
		<em><span class="px13">Werte mit * mussen eingetragen werden</span></em><br>
		<input name="token" type="hidden" value="<? echo $this->formvars['token']; ?>">
		<input name="inviter_id" type="hidden" value="<? echo $this->formvars['inviter_id']; ?>">
		<div class="clear"></div>
		<div style="float: left">	Wer soll eingeladen werden?</div>
		<div class="clear"></div>
		<label class="fetter" for="email">E-Mail *</label>
		<input name="email" type="text" value="<? echo $this->formvars['email']; ?>">
		<div class="clear"></div>
		<label class="fetter" for="name">Nachname *</label>
		<input name="name" type="text" value="<? echo $this->formvars['name']; ?>">
		<div class="clear"></div>
		<label class="fetter" for="name">Vorname</label>
		<input name="vorname" type="text" value="<? echo $this->formvars['vorname']; ?>">
		<div class="clear"></div>
		<div style="float: left">Wo soll mitgearbeitet werden?</div>
		<div class="clear"></div>
		<label class="fetter" for="stelle_id">Arbeitsstelle *</label>
		<? echo FormObject::createSelectField('stelle_id', $this->invitation->stellen, $this->invitation->get('stelle_id')); ?>
		<div class="clear"></div><?
		if ($this->formvars['selected_invitation_id'] != '') { ?>
			<label class="fetter" for="email">Kennung:</label>
			<div style="float: left"><? echo $this->formvars['token']; ?></div>
			<div class="clear"></div>
			<div style="float: left"><a href="mailto:<?php echo $this->invitation->mailto_text(); ?>">Einladung noch mal per E-Mail versenden</a></div><?
		} ?>
		<div class="clear"></div>
		<div style="text-align: -webkit-center">
			<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
				<tr>
					<td align="center">
						<input type="hidden" name="go_plus" id="go_plus" value="">
						<input value="zurück" title="Zeigt die ganze Liste an." type="button" name="go" onclick="document.location.href='index.php?go=Einladungen_Anzeigen#invitation_<?php echo $this->invitation->get(Invitation::$identifier); ?>'">&nbsp;<?php
						if ($this->invitation->get(Invitation::$identifier) != '') { ?>
							<input value="Ändern" title="Änderungen Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Ändern')">&nbsp;
							<input value="Zurücksetzen" title="Setzt alle Werte zurück auf die vorhigen." type="reset" name="reset1">&nbsp;
							<input type="hidden" name="selected_invitation_id" value="<?php echo  $this->invitation->get(Invitation::$identifier); ?>"><?php
						}
						else { ?>
							<input value="Speichern" title="Speichern" type="button" name="dummy" onclick="submitWithValue('GUI','go_plus','Speichern')">&nbsp;
							<input value="Zurücksetzen" title="Setzt die Werte zurück auf den letzten Stand." type="button" onclick="document.location.href='index.php?go=Einladung_Editor&selected_invitation_id=<?php echo  $this->invitation->get(Invitation::$identifier); ?>'">&nbsp;<?
						} ?>
				  </td>
			  </tr>
			</table>
			<input type="hidden" name="go" value="Einladung">
			<input type="hidden" name="selected_invitation_id" value="<? echo  $this->invitation->get(Invitation::$identifier); ?>">
		</div>
	</div>
</div>
