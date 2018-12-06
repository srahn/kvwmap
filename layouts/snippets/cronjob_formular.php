<?php
# 2008-01-12 pkvvm
include(LAYOUTPATH.'languages/cronjob_formular_'.$this->user->rolle->language.'.php');
?>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
	<tr> 
		<td align="left"><h2><?php echo $strTitle; ?></h2></td>
		<td align="right"><a class="btn btn-new" href="index.php?go=cronjobs_anzeigen"><i class="fa fa-list" style="color: white;"></i> Alle Jobs</a></td></td>
	</tr>
	<tr>
		<td align="center"><?php
			if ($this->Meldung == 'Daten des Benutzers erfolgreich eingetragen!' OR $this->Meldung == '') {
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
			<?php echo $this->cronjob->get('id'); ?>
		</td>
	</tr><?php
	} ?>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName;?></th>
		<td style="border-bottom:1px solid #C3C7C3">
			<input name="bezeichnung" type="text" value="<?php echo $this->cronjob->get('bezeichnung'); ?>" size="81" maxlength="255" placeholder="Wird zur Identifizierung des Jobs in der Liste verwendet.">
		</td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Beschreibung';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><textarea name="beschreibung" cols="80" rows="1" placeholder="Kurzbeschreibung was der Job machen wird."><?php echo $this->cronjob->get('beschreibung'); ?></textarea></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Zeit';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input name="time" type="text" value="<?php echo $this->cronjob->get('time'); ?>" size="10" maxlength="25" placeholder="m h d M wd"></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Datenbankname';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input type="text" name="dbname" size="81" value="<?php echo $this->cronjob->get('dbname'); ?>" placeholder="Wenn leer wird pgdbname von Stelle oder sonst von GUI genommen."></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'SQL-Anfrage';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><textarea name="query" cols="80" rows="20" placeholder="SQL wird in der unten angegebenen Datenbank ausgeführt. Wenn kein SQL angegeben ist, wird ein Eintrag in Shell-Befehlszeile erwartet. Eines von beiden muss angegeben werden."><?php echo $this->cronjob->get('query'); ?></textarea></td>
	</tr>
	<tr>
		<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Shell Befehlszeile';?></th>
		<td style="border-bottom:1px solid #C3C7C3"><input type="text" name="function" size="81" value="<?php echo htmlspecialchars($this->cronjob->get('function')); ?>" placeholder="Wirkt sich nur aus, wenn kein SQL angegeben ist."></td>
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
		<input type="submit" name="go_plus" value="<?php echo ($this->cronjob->get('id') > 0 ? 'Speichern' : 'Anlegen'); ?>">
	</td>
</tr>
</table>