<?
	include(LAYOUTPATH . 'languages/ows_export_' . $this->user->rolle->language . '.php');
	include_once(CLASSPATH . 'FormObject.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>" width="100%">
	<tr align="center">
		<td colspan="2" width="100%"><h2><?php echo $strTitle; ?></h2></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><?php echo $strWMSExportWarning . WMS_MAPFILE_PATH . $this->Stelle->id; ?>/</td>
	</tr>
	<tr>
		<td align="right" width="50%"><?php echo $strNameOfMapFile; ?></td>
		<td align="left" width="50%"><input name="mapfile_name" type="text" id="mapfile_name" value="<?php echo (value_of($this->formvars, 'mapfile_name') ?: MAPFILENAME ?: 'wms_test.map'); ?>" maxlength="50" style="width: 100%"></td>
	</tr>
	<tr>
		<td align="right">
			<? echo $strAllOrActiveLayer; ?>
		</td>
		<td align="left">
			<input type="radio" name="nurAktiveLayer" value="0"<?php echo (array_key_exists('nurAktiveLayer', $this->formvars) AND $this->formvars['nurAktiveLayer'] == 1) ? '' : ' checked'; ?>>
			<?php echo $strAll; ?><br>
			<input type="radio" name="nurAktiveLayer" value="1"<?php echo (array_key_exists('nurAktiveLayer', $this->formvars) AND $this->formvars['nurAktiveLayer'] == 1) ? ' checked' : ''; ?>>
			<?php echo $strActiveLayer; ?>
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strTotalOrCurrentExtent; ?></td>
		<td align="left">
			<input type="radio" name="totalExtent" value="1"<?php echo (array_key_exists('totalExtent', $this->formvars) AND $this->formvars['totalExtent'] == 0) ? '' : ' checked'; ?>>
			<?php echo $strTotalExtent; ?><br>
			<input type="radio" name="totalExtent" value="0"<?php echo (array_key_exists('totalExtent', $this->formvars) AND $this->formvars['totalExtent'] == 0) ? ' checked' : ''; ?>>
			<?php echo $strCurrentExtent; ?>
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strNurVeroeffentlichteFilter; ?></td>
		<td align="left">
			<input type="checkbox" name="nurVeroeffentlichte" value="1"<?php echo (array_key_exists('nurVeroeffentlichte', $this->formvars) AND $this->formvars['nurVeroeffentlichte'] == 1) ? ' checked' : ''; ?>> <span
				id="debug_t"
				data-tooltip="<?php echo $strNurVeroeffentlichteHilfe; ?>"
				onclick="message([{'type': 'info', 'msg': this.getAttribute('data-tooltip')}])">
			</span>
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strOwsTitle; ?></td>
		<td align="left">
			<input name="ows_title" type="text" id="ows_title" value="<? echo (value_of($this->formvars, 'ows_title') ?: $this->Stelle->ows_title ?: OWS_TITLE); ?>" maxlength="100" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strOwsAbstract; ?></td>
		<td align="left">
			<textarea name="ows_abstract" rows="3" id="ows_abstract" style="width: 100%"><?
				echo (value_of($this->formvars, 'ows_abstract') ?: $this->Stelle->ows_abstract ?: OWS_ABSTRACT); ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strAccessConstraints; ?></td>
		<td align="left">
			<input name="wms_accessconstraints" type="text" value="<? echo (value_of($this->formvars, 'wms_accessconstraints') ?: $this->Stelle->wms_accessconstraints ?: OWS_ACCESSCONSTRAINTS); ?>" maxlength="100" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strFee; ?></td>
		<td align="left">
			<input name="ows_fees" type="text" value="<? echo (value_of($this->formvars, 'ows_fees') ?: $this->Stelle->ows_fees ?: OWS_FEES); ?>" maxlength="100" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2"><b><?php echo $strContactInfo; ?></b></td>
	</tr>
	<tr>
		<td align="right"><?php echo $strContactPerson; ?></td>
		<td align="left">
			<input name="ows_contactperson" type="text" id="ows_contactperson" value="<? echo (value_of($this->formvars, 'ows_contactperson') ?: $this->Stelle->ows_contactperson ?: $this->Stelle->ows_contentperson ?: OWS_CONTACTPERSON); ?>" maxlength="50" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strContactPosition; ?></td>
		<td align="left">
			<input name="ows_contactposition" type="text" id="ows_contactposition" value="<? echo (value_of($this->formvars, 'ows_contactposition') ?: $this->Stelle->ows_contactposition ?: $this->Stelle->ows_contentposition ?: OWS_CONTACTPOSITION); ?>" maxlength="50" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strOrganisation; ?></td>
		<td align="left">
			<input name="ows_contactorganization" type="text" id="ows_contactorganization" value="<? echo (value_of($this->formvars, 'ows_contactorganization') ?: $this->Stelle->ows_contactorganization ?: $this->Stelle->ows_contentorganization ?: OWS_CONTACTORGANIZATION); ?>" maxlength="50" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><?php echo $strEMail; ?></td>
		<td align="left">
			<input name="ows_contactelectronicmailaddress" type="text" value="<? echo (value_of($this->formvars, 'ows_contactelectronicmailaddress') ?: $this->Stelle->ows_contactelectronicmailaddress ?: $this->Stelle->ows_contentelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS); ?>" maxlength="150" style="width: 100%">
		</td>
	</tr>
	<tr>
		<td align="right"><? echo $strAttributeFilter; ?></td>
		<td align="left"><div style="width: 400px">
			<input type="text" name="filter_attribute_name" value="<? echo (value_of($this->formvars, 'filter_attribute_name') ?: 'stelle_id'); ?>"/> <?
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
			<input type="text" name="filter_attribute_value" value="<? echo (value_of($this->formvars, 'filter_attribute_value') ?: $this->Stelle->id); ?>"/>
			<span id="debug_t" data-tooltip="<?php echo $strAttributeFilterHilfe; ?>" onclick="message([{'type': 'info', 'msg': this.getAttribute('data-tooltip')}])"></span>
		</div>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<input type="hidden" name="go" value="WMS_Export">
			<input type="hidden" name="go_plus" value="">
			<input type="button" onclick="document.GUI.go_plus.value='Abbrechen';document.GUI.submit();" value="<?php echo $this->strCancel; ?>">&nbsp;
			<input type="button" onclick="document.GUI.go_plus.value='Senden';document.GUI.submit();" value="<?php echo $this->strSend; ?>">
		</td>
	</tr>
</table>
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

