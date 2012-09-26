<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
 ?>
<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><strong><font size="+1"><?php echo $strTitle; ?></font></strong></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th><a href="index.php?go=Benutzerdaten_Anzeigen&order=ID"><?php echo $this->strID;?></a></th>
        <th><a href="index.php?go=Benutzerdaten_Anzeigen&order=Name"><?php echo $this->strName;?></a></th>
        <th><?php echo $strTel;?></th>
        <th><?php echo $strEMail;?></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php 
      for ($i=0;$i<count($this->userdaten);$i++) { ?>
      <tr onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
        <td><?php echo $this->userdaten[$i]['ID']; ?></td>
        <td><?php echo $this->userdaten[$i]['Namenszusatz'].' '; ?><?php echo $this->userdaten[$i]['Name']; ?>,&nbsp;<?php echo $this->userdaten[$i]['Vorname']; ?></td>
        <td><?php echo $this->userdaten[$i]['phon']; ?>&nbsp;</td>
        <td><?php echo $this->userdaten[$i]['email']; ?>&nbsp;</td>
        <td><a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<?php echo $this->userdaten[$i]['ID']; ?>"><?php echo $this->strChange; ?></a></td>
        <td>&nbsp;&nbsp;<a href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&selected_user_id=<?php echo $this->userdaten[$i]['ID']; ?>&order=<? echo $this->formvars['order']; ?>','Wollen Sie den Benutzer <?php echo $this->userdaten[$i]['Vorname']." ".$this->userdaten[$i]['Name']; ?> wirklich löschen?')"><?php echo $this->strDelete?></a></td>
      </tr>
      <?php  
      }
      ?>
    </table></td>
  </tr>
  <tr> 
    <td align="right">&nbsp;</td>
  </tr>
</table>
      <input type="hidden" name="go" value="Benutzerdaten">
      <input type="hidden" name="order" value="<? echo $this->formvars['order'] ?>">
