<?php
# 2008-01-12 pkvvm
include(LAYOUTPATH.'languages/cronjobs_'.rolle::$language.'.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr> 
		<td align="left"><h2><?php echo $strTitle; ?></h2></td>
		<td align="right"><a class="btn btn-new" href="index.php?go=cronjobs_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><i class="fa fa-list" style="color: white;"></i> Alle Jobs</a></td></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><?php
			if ($this->Meldung == $strSuccess OR $this->Meldung == '') {
				$bgcolor = BG_FORM;
			}
			else {
				$this->Fehlermeldung = $this->Meldung;
				include('Fehlermeldung.php');
				$bgcolor = BG_FORMFAIL;
			} ?>
		</td>
	</tr><?php
	if (!empty($this->cronjob->get('id'))) { ?>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'ID'; ?></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<input name="id" type="hidden" value="<?php echo $this->cronjob->get('id'); ?>">
			<? echo $this->cronjob->get('id'); ?>
		</td>
	</tr><?
	} ?>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_bezeichnung; ?></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<input name="bezeichnung" type="text" value="<?php echo $this->cronjob->get('bezeichnung'); ?>" size="81" maxlength="255" placeholder="<? echo $strPlaceholder_bezeichnung; ?>">
		</td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_beschreibung; ?></th>
		<td style="border-bottom:1px solid #C3C7C3"><textarea name="beschreibung" cols="80" rows="1" placeholder="<? echo $strPlaceholder_beschreibung; ?>"><?php echo $this->cronjob->get('beschreibung'); ?></textarea></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_zeit;?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input name="time" type="text" value="<?php echo $this->cronjob->get('time'); ?>" size="10" maxlength="25" placeholder="m h d M wd"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_dbname; ?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input type="text" name="dbname" size="81" value="<?php echo $this->cronjob->get('dbname'); ?>" placeholder="<? echo $strPlaceholder_dbname; ?>"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_query; ?></th>
		<td style="border-bottom:1px solid #C3C7C3"><textarea name="query" cols="80" rows="20" placeholder="<? echo $strPlaceholder_query; ?>"><?php echo $this->cronjob->get('query'); ?></textarea></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_function; ?></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<input
				type="text"
				name="function"
				size="81"
				value="<?php echo htmlspecialchars($this->cronjob->get('function')); ?>"
				placeholder="<? echo $strPlaceholder_function; ?>"
				onkeyup="if ($(this).val() == '') $('#user').hide(); else $('#user').show();"
			></td>
	</tr>
	<tr id="user" style="display: <?php echo ($this->cronjob->get('function') == '' ? 'none' : 'contents'); ?>">
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><? echo $strLabel_user; ?></th>
		<td style="border-bottom: 1px solid #C3C7C3;">
			<select name="user">
				<option value="gisadmin"<? echo ($this->cronjob->get('user') == 'root' ? '' : ' selected'); ?>>gisadmin</option>
				<option value="root"<? echo ($this->cronjob->get('user') == 'root' ? ' selected' : ''); ?>>root</option>
			</select> <span style="font-size: 0.9em; color: gray"><? echo $strTip_user; ?></span>
		</td>
	</tr>
	<!--tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'user_id';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input type="text" name="user_id" value = "<?php echo $this->cronjob->get('user_id'); ?>"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'stelle_id';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input type="text" name="stelle_id" value = "<?php echo $this->cronjob->get('stelle_id'); ?>"></td>
	</tr//-->
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="hidden" name="go" value="cronjob_speichern">
		<input type="button" name="cancle" value="<? echo $this->strCancel; ?>" onclick="document.location.href = 'index.php?go=cronjobs_anzeigen&csrf_token=<? echo $_SESSION['csrf_token']; ?>'">
		<input type="submit" name="go_plus" value="<?php echo ($this->cronjob->get('id') > 0 ? $this->strSave : $this->strCreate); ?>">
	</td>
</tr>
</table>