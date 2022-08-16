<?
	include(LAYOUTPATH . 'languages/ows_export_' . $this->user->rolle->language . '.php');
	include(CLASSPATH . 'FormObject.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr align="center">
		<td colspan="2"><h2><?php echo $strTitle; ?></h2></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><?php echo $strWMSExportWarning . WMS_MAPFILE_PATH . $this->Stelle->id; ?>/</td>
	</tr>
	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><?php echo $strAllOrActiveLayer; ?><br>
		<input type="radio" name="nurAktiveLayer" value="0"<?php echo (array_key_exists('nurAktiveLayer', $this->formvars) AND $this->formvars['nurAktiveLayer'] == 1) ? '' : ' checked'; ?>>
		<?php echo $strAll; ?>
		<input type="radio" name="nurAktiveLayer" value="1"<?php echo (array_key_exists('nurAktiveLayer', $this->formvars) AND $this->formvars['nurAktiveLayer'] == 1) ? ' checked' : ''; ?>>
		<?php echo $strActiveLayer; ?></td>
	</tr>
	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><?php echo $strTotalOrCurrentExtent; ?><br>
		<input type="radio" name="totalExtent" value="1"<?php echo (array_key_exists('totalExtent', $this->formvars) AND $this->formvars['totalExtent'] == 0) ? '' : ' checked'; ?>>
		<?php echo $strTotalExtent; ?>
		<input type="radio" name="totalExtent" value="0"<?php echo (array_key_exists('totalExtent', $this->formvars) AND $this->formvars['totalExtent'] == 0) ? ' checked' : ''; ?>>
		<?php echo $strCurrentExtent; ?></td>
	</tr>

	<tr>
		<td align="left">&nbsp;</td>
		<td align="left">
			<input type="checkbox" name="nurVeroeffentlichte" value="1"<?php echo (array_key_exists('nurVeroeffentlichte', $this->formvars) AND $this->formvars['nurVeroeffentlichte'] == 1) ? ' checked' : ''; ?>> <?php echo $strNurVeroeffentlichteFilter; ?>
			<span
				id="debug_t"
				data-tooltip="<?php echo $strNurVeroeffentlichteHilfe; ?>"
				onclick="message([{'type': 'info', 'msg': this.getAttribute('data-tooltip')}])">
			</span>
		</td>
	</tr>

	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><?
			echo $strAttributeFilter; ?>: 
			<input type="text" name="filter_attribute_name" value="<? echo $this->formvars['filter_attribute_name']; ?>"/> <?
			echo FormObject::createSelectField(
				'filter_attribute_operator',
				array('=', '!=', '<', '>', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN'),
				$this->formvars['filter_attribute_operator'],
				1,
				'',
				'',
				'',
				'',
				'',
				''
			); ?> 
			<input type="text" name="filter_attribute_value" value="<? echo $this->formvars['filter_attribute_value']; ?>"/> 
			<span id="debug_t" data-tooltip="<?php echo $strAttributeFilterHilfe; ?>" onclick="message([{'type': 'info', 'msg': this.getAttribute('data-tooltip')}])"></span>
		</td>
	</tr>

	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"> <p><?php echo $strNameOfMapFile; ?><br>
			<input name="mapfile_name" type="text" id="mapfile_name" value="<?php echo ($this->formvars['mapfile_name'] != '' ? $this->formvars['mapfile_name'] : 'wms_test.map'); ?>" size="50" maxlength="50">
		</p>
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><p><?php echo $strOwsTitle; ?><br>
			<input name="ows_title" type="text" id="ows_title" value="<?php echo $this->formvars['ows_title'] ?? $this->Stelle->ows_title ?: OWS_TITLE; ?>" size="100" maxlength="100">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strOwsAbstract; ?><br>
			<textarea name="ows_abstract" cols="80" rows="3" id="ows_abstract"><?php echo $this->formvars['ows_abstract'] ?? $this->Stelle->ows_abstract ?: OWS_ABSTRACT; ?></textarea><br>
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strContactInfo; ?></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strContactPerson; ?>
			<input name="ows_contactperson" type="text" id="ows_contactperson" value="<?php echo $this->formvars['ows_contactperson'] ?? $this->Stelle->ows_contactperson ?: OWS_CONTACTPERSON; ?>" size="50" maxlength="50">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strContactPosition; ?>
			<input name="ows_contactposition" type="text" id="ows_contactposition" value="<?php echo $this->formvars['ows_contactposition'] ?? $this->Stelle->ows_contactposition ?: OWS_CONTACTPOSITION; ?>" size="50" maxlength="50">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strOrganisation; ?>
			<input name="ows_contactorganization" type="text" id="ows_contactorganization" value="<?php echo $this->formvars['ows_contactorganization'] ?? $this->Stelle->ows_contactorganization ?: OWS_CONTACTORGANIZATION; ?>" size="50" maxlength="50">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strEMail; ?>
			<input name="ows_contactelectronicmailaddress" type="text" value="<?php echo $this->formvars['ows_contactelectronicmailaddress'] ?? $this->Stelle->ows_contactelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS; ?>" size="50" maxlength="150">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left"><?php echo $strFee; ?>
			<input name="ows_fees" type="text" value="<?php echo $this->formvars['ows_fees'] ?? $this->Stelle->ows_fees ?: OWS_FEES; ?>" size="50" maxlength="100">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="center">
			<input type="hidden" name="go" value="WMS_Export">
			<input type="hidden" name="go_plus" value="">
			<input type="button" onclick="document.GUI.go_plus.value='Abbrechen';document.GUI.submit();" value="<?php echo $this->strCancel; ?>">&nbsp;
			<input type="button" onclick="document.GUI.go_plus.value='Senden';document.GUI.submit();" value="<?php echo $this->strSend; ?>">
		</td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td align="left">
			<b><?php echo $strExportList; ?>:</b>
			<ul><?
				echo implode('', array_map(
					function($mapfile) {
						return '<li>' . $mapfile . '
							<a title="Map-Datei ' . $mapfile . ' ändern" href="index.php?go=WMS_Export&mapfile_name=' . $mapfile . '&csrf_token=' . $_SESSION['csrf_token'] . '"><i class="fa fa-pencil" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
							<a title="Map-Datei ' . $mapfile . ' löschen" href="index.php?go=ows_export_loeschen&mapfile_name=' . $mapfile . '&csrf_token=' . $_SESSION['csrf_token'] . '"><i class="fa fa-trash" aria-hidden="true" style="margin-left: 10px; color: firebrick"></i></a>
						</li>';
					},
					$this->mapfiles_der_stelle
				)); ?>
			</ul>
		</td>
	</tr>
</table>
