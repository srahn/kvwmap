<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_formular_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
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
-->
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $strTitle; ?></font></strong></td>
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
 ?>      <table border="0" cellspacing="0" cellpadding="5" style="border:1px solid #C3C7C3">
  <tr align="center">
    <td colspan="2" style="border-bottom:1px solid #C3C7C3"><em><font size="-1"><?php echo $strAsteriskRequired;?></font></em></td>
    </tr><?php if ($this->formvars['selected_user_id']>0) {?>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strDataBankID;?></th>
    <td style="border-bottom:1px solid #C3C7C3">
    	<input name="id" type="text" value="<?php echo $this->formvars['selected_user_id']; ?>" size="25" maxlength="11">
    </td>
  </tr><?php } ?>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strName;?></th>
    <td style="border-bottom:1px solid #C3C7C3">
      <input name="nachname" type="text" value="<?php echo $this->formvars['nachname']; ?>" size="25" maxlength="100">
  	</td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo 'Namenszusatz';?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="Namenszusatz" type="text" value="<?php echo $this->formvars['Namenszusatz']; ?>" size="25" maxlength="100"></td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strForeName;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="vorname" type="text" value="<?php echo $this->formvars['vorname']; ?>" size="25" maxlength="100"></td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strLogInName;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="loginname" type="text" value="<?php echo $this->formvars['loginname']; ?>" size="15" maxlength="15"></td>
  </tr><?php if ($this->formvars['selected_user_id']>0) {?>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strChangePassword;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input type="checkbox" onchange="toggle_password();" name="changepasswd" value="1">&nbsp;Letzte Änderung am: <?php
    $passwordSettingUnixTime=strtotime($this->formvars['password_setting_time']);
    echo date('d.m.Y',$passwordSettingUnixTime); ?><?php
    if ($this->Stelle->check_password_age) {
    	$allowedPasswordAgeRemainDays=checkPasswordAge($this->formvars['password_setting_time'],$this->Stelle->allowed_password_age);
      ?>&nbsp;Es gilt noch <?php echo $allowedPasswordAgeRemainDays; ?> Tage.<?php
     } ?></td>
  </tr><?php } ?>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strPassword;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="password1" <?php if ($this->formvars['selected_user_id']>0)echo'disabled="true"';?> type="password" size="10" maxlength="30"></td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strReEnterPassword;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="password2" <?php if ($this->formvars['selected_user_id']>0)echo'disabled="true"';?> type="password" size="10" maxlength="30"></td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strAllowedIps;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="ips" type="text" size="25" maxlength="100" value="<? echo $this->formvars['ips']; ?>"></td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th align="right"><?php echo $strAuthorizeTask;?></th>
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
            <select name="selectedstellen" size="4" multiple style="width:160px">
            <? 
            for($i=0; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
              	echo '<option value="'.$this->formvars['selstellen']["ID"][$i].'">'.$this->formvars['selstellen']["Bezeichnung"][$i].'</option>';
               }
            ?>
            </select>
          </td>
          <td align="center" valign="middle" width="1"> 
            <input type="button" name="addPlaces" value="&lt;&lt;" onClick=addOptions(document.GUI.allstellen,document.GUI.selectedstellen,document.GUI.selstellen,'value')>
            <input type="button" name="substractPlaces" value="&gt;&gt;" onClick=substractOptions(document.GUI.selectedstellen,document.GUI.selstellen,'value')>
          </td>
          <td> 
            <select name="allstellen" size="4" multiple style="width:160px">
            <? for($i=0; $i < count($this->formvars['stellen']["Bezeichnung"]); $i++){
              	echo '<option value="'.$this->formvars['stellen']["ID"][$i].'">'.$this->formvars['stellen']["Bezeichnung"][$i].'</option>';
               }
            ?>
            </select>
          </td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strTelephone;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="phon" type="text" value="<?php echo $this->formvars['phon']; ?>" size="15" maxlength="15"></td>
  </tr>
    <tr>
    <th align="right" style="border-bottom:1px solid #C3C7C3"><?php echo $strEmail;?></th>
    <td style="border-bottom:1px solid #C3C7C3"><input name="email" type="text" value="<?php echo $this->formvars['email']; ?>" size="30" maxlength="50"></td>
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
      <input type="hidden" name="go" value="Benutzerdaten">
      <input type="hidden" name="selstellen" value="<? 
      	echo $this->formvars['selstellen']["ID"][0];
        for($i=1; $i < count($this->formvars['selstellen']["Bezeichnung"]); $i++){
        	echo ', '.$this->formvars['selstellen']["ID"][$i];
        }
      ?>">
