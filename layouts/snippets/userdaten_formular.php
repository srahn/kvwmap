<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_formular_'.$this->user->rolle->language.'.php');
 ?>
<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript"></script>
<script type="text/javascript">
<!--

	function toggle_password(){
		if(document.GUI.changepasswd.checked){
			document.GUI.password1.disabled=false;
			document.GUI.password2.disabled=false;
		}
		else{
			document.GUI.password1.disabled=true;
			document.GUI.password2.disabled=true;
		}
	}
	
	function deactivate_layer(user_id, stelle_id, layer_id){
		ahah('index.php?go=Benutzerdaten_Layer_Deaktivieren', 'user_id='+user_id+'&stelle_id='+stelle_id+'&layer_id='+layer_id, new Array(), new Array());
		layer = document.getElementById('layer_'+layer_id);
		layer.parentNode.removeChild(layer);
	}
	
-->
</script>

<input type="hidden" name="go" value="Benutzerdaten">
<input type="hidden" name="selstellen" value="<?
	for($i=0; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
		if($this->userdaten[0]['stelle_id'] == $this->formvars['selstellen']["ID"][$i])$active_stelle = $i;
		if($i > 0)echo ', ';
		echo $this->formvars['selstellen']["ID"][$i];
	}
?>">

<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <tr>
    <td align="center"><?php
if ($this->Meldung=='Daten des Benutzers erfolgreich eingetragen!' OR $this->Meldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $this->Fehlermeldung=$this->Meldung;
  include('Fehlermeldung.php');
  $bgcolor=BG_FORMFAIL;
}
 ?>
			<table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
				<tr align="center">
					<td colspan="2" style="border-bottom:1px solid #C3C7C3"><em><span class="px13"><?php echo $strAsteriskRequired;?></span></em></td>
					</tr><?php if ($this->formvars['selected_user_id']>0) {?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataBankID;?></th>
					<td style="border-bottom:1px solid #C3C7C3">
						<input name="id" type="text" value="<?php echo $this->formvars['selected_user_id']; ?>" size="25" maxlength="11">
					</td>
				</tr><?php } ?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName;?></th>
					<td style="border-bottom:1px solid #C3C7C3">
						<input name="nachname" type="text" value="<?php echo $this->formvars['nachname']; ?>" size="25" maxlength="100">
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Namenszusatz';?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="Namenszusatz" type="text" value="<?php echo $this->formvars['Namenszusatz']; ?>" size="25" maxlength="100"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strForeName;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="vorname" type="text" value="<?php echo $this->formvars['vorname']; ?>" size="25" maxlength="100"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLogInName;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="loginname" readonly onfocus="this.removeAttribute('readonly');" type="text" value="<?php echo $this->formvars['loginname']; ?>" size="15" maxlength="100"></td>
				</tr><?php if ($this->formvars['selected_user_id']>0) {?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strChangePassword;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input type="checkbox" onchange="toggle_password();" name="changepasswd" value="1">&nbsp;Letzte Änderung am: <?php
					$passwordSettingUnixTime=strtotime($this->formvars['password_setting_time']);
					echo date('d.m.Y',$passwordSettingUnixTime); ?><?php
					if ($this->Stelle->check_password_age) {
						$allowedPasswordAgeRemainDays=checkPasswordAge($this->formvars['password_setting_time'],$this->Stelle->allowed_password_age);
						?>&nbsp;Es gilt noch <?php echo $allowedPasswordAgeRemainDays; ?> Tage.<?php
					 } ?></td>
				</tr><?php } ?>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPassword;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="password1" readonly onfocus="this.removeAttribute('readonly');" <?php if ($this->formvars['selected_user_id']>0)echo'disabled="true"';?> type="password" size="10" maxlength="30"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strReEnterPassword;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="password2" readonly onfocus="this.removeAttribute('readonly');" <?php if ($this->formvars['selected_user_id']>0)echo'disabled="true"';?> type="password" size="10" maxlength="30"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStart;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="start" type="text" size="25" maxlength="100" value="<? echo $this->formvars['start']; ?>"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strStop;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="stop" type="text" size="25" maxlength="100" value="<? echo $this->formvars['stop']; ?>"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strAllowedIps;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="ips" type="text" size="25" maxlength="100" value="<? echo $this->formvars['ips']; ?>"></td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<th class="fetter" align="right"><?php echo $strAuthorizeTask;?></th>
							</tr>
							<tr>
								<td align="right">&nbsp;</td>
							</tr>
						</table>
					</th>
					<td valign="top" style="border-bottom:1px solid #C3C7C3">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr valign="top"> 
								<td>
								<?
								?> 
									<select name="selectedstellen" size="4" multiple style="width:300px">
									<? 
									for($i=0; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
											echo '<option value="'.$this->formvars['selstellen']["ID"][$i].'" title="'.$this->formvars['selstellen']["Bezeichnung"][$i].'">'.$this->formvars['selstellen']["Bezeichnung"][$i].'</option>';
										 }
									?>
									</select>
								</td>
								<td align="center" valign="middle" width="1"> 
									<input type="button" name="addPlaces" value="&lt;&lt;" onClick=addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.selstellen,'value')>
									<input type="button" name="substractPlaces" value="&gt;&gt;" onClick=substractOptions(document.GUI.selectedstellen,document.GUI.selstellen,'value')>
								</td>
								<td> 
									<select name="allstellen" size="4" multiple style="width:300px">
									<? for($i=0; $i < count($this->formvars['stellen']["Bezeichnung"]); $i++){
											echo '<option value="'.$this->formvars['stellen']["ID"][$i].'" title="'.$this->formvars['stellen']["Bezeichnung"][$i].'">'.$this->formvars['stellen']["Bezeichnung"][$i].'</option>';
										 }
									?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTelephone;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="phon" type="text" value="<?php echo $this->formvars['phon']; ?>" size="15" maxlength="15"></td>
				</tr>
					<tr>
					<th class="fetter" align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEmail;?></th>
					<td style="border-bottom:1px solid #C3C7C3"><input name="email" type="text" value="<?php echo $this->formvars['email']; ?>" size="30" maxlength="50"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="5" style="width: 100%;border:1px solid #C3C7C3">
				<tr>
					<th class="fetter" align="right" valign="top" style="width: 175px;border-bottom:1px solid #C3C7C3"><?php echo $strActiveStelle;?></th>
					<td style="border-bottom:1px solid #C3C7C3">
						<a href="index.php?go=Stelleneditor&selected_stelle_id=<? echo $this->userdaten[0]['stelle_id']; ?>"><? echo $this->formvars['selstellen']['Bezeichnung'][$active_stelle]; ?></a>
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" valign="top" style="width: 173px;border-bottom:1px solid #C3C7C3"><?php echo $strActiveLayers;?></th>
					<td style="border-bottom:1px solid #C3C7C3">
						<table cellpadding="0" cellspacing="0">
					<?	for($i = 0; $i < count($this->active_layers)-1; $i++){ ?>
							<tr id="layer_<? echo $this->active_layers[$i]['Layer_ID']; ?>" class="tr_hover">
								<td style="padding: 0 10 0 2">
									<? echo $this->active_layers[$i]['alias'].'</td><td><a title="deaktivieren" href="javascript:deactivate_layer('.$this->formvars['selected_user_id'].', '.$this->userdaten[0]['stelle_id'].', '.$this->active_layers[$i]['Layer_ID'].');"><i style="font-size: 19px; color: firebrick" class="fa fa-times" aria-hidden="true"></i></a>'; ?>
								<td>
							</tr>
					<?	}	 ?>
						</table>
					</td>
				</tr>
				<tr>
					<th class="fetter" align="right" valign="top" style="width: 173px;border-bottom:1px solid #C3C7C3"><? echo $strChangeUser;?></th>
					<td style="border-bottom:1px solid #C3C7C3">
						<a href="index.php?go=als_nutzer_anmelden&loginname=<? echo $this->formvars['loginname']; ?>"><? echo $strLoginAsUser; ?></a>
					</td>
				</tr>				
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
		<input type="hidden" name="go_plus" id="go_plus" value=""><?php
			if ($this->formvars['selected_user_id']>0) { ?>
			<input type="reset" name="reset1" value="<?php echo $strButtonBack; ?>">&nbsp;
			<input type="hidden" name="selected_user_id" value="<?php echo $this->formvars['selected_user_id']; ?>">
				<input type="button" name="dummy" value="<?php echo $this->strSave; ?>" onclick="submitWithValue('GUI','go_plus','Ändern')">&nbsp;<?php
			}
			else {
				?><input type="button" value="<?php echo $strButtonBack; ?>" onclick="document.location.href='index.php?go=Benutzerdaten_Formular'">&nbsp;<?
			} 
			?><input type="button" name="dummy" value="<?php echo $strButtonSaveAs; ?>" onclick="submitWithValue('GUI','go_plus','Als neuen Nutzer eintragen')">
		</td>
	</tr>
</table>
