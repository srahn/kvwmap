<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<a name="oben"></a>
<table border="0" cellpadding="5" cellspacing="0" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td><h2><?php echo $strTitle; ?></h2></td>
  </tr>
  <? if($this->formvars['order']=="Name") { ?>
  <tr height="50px" valign="bottom">
    <td>
    <? $umlaute=array("Ä","Ö","Ü");
       for ($i=0;$i<count($this->userdaten);$i++) {
         if(!in_array(strtoupper(substr($this->userdaten[$i]['Name'],0,1)),$umlaute) AND strtolower(substr($this->userdaten[$i]['Name'],0,1)) != $first) {
           echo "<a href='#".strtoupper(substr($this->userdaten[$i]['Name'],0,1))."'>".strtoupper(substr($this->userdaten[$i]['Name'],0,1))."</a>&nbsp;&nbsp;";
           $first=strtolower(substr($this->userdaten[$i]['Name'],0,1));
         }
       } ?> 
    </td>
  </tr>
  <? } ?>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td>&nbsp;</td>
        <th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=ID"><?php echo $this->strID;?></a></th>
        <th align="left"><a href="index.php?go=Benutzerdaten_Anzeigen&order=Name"><?php echo $this->strName;?></a></th>
        <th align="left"><?php echo $strTel;?></th>
        <th align="left"><?php echo $strEMail;?></th>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php 
      for ($i=0;$i<count($this->userdaten);$i++) {
      if($this->formvars['order']=="Name") {
        $first=strtoupper(substr($this->userdaten[$i]['Name'],0,1));
          if (in_array($first,$umlaute)) {
            switch ($first) {
              case 'Ä': {
              $first='A';
              }break;
              case 'Ö': {
              $first='O';
              }break;
              case 'Ü': {
              $first='U';
              }break;                           
            }          
          } 
        if($first != $nextfirst) { ?>
      <tr>
        <th align="left" style="border-top:1px solid #808080; margin:0px;">
          <? echo "<a name='".$first."'>".$first."</a>";
            $nextfirst=$first;
          if (in_array($first,$umlaute)) {
            switch ($first) {
              case 'Ä': {
              $nextfirst='A';
              }break;
              case 'Ö': {
              $nextfirst='O';
              }break;
              case 'Ü': {
              $nextfirst='U';
              }break;                           
            }
          } ?>
        </th>
        <td colspan="6" align="right" style="border-top:1px solid #808080; margin:0px;">
          <a href="#oben"><img src="<? echo GRAPHICSPATH; ?>pfeil2.gif" width="11" height="11" border="0"></a>
        </td>
      </tr>
      <? }
      } ?>
      
      
      
      <tr onMouseover="this.bgColor='<?php echo BG_TR; ?>'" onMouseout="this.bgColor=''">
        <td>&nbsp;</td>
        <td><?php echo $this->userdaten[$i]['ID']; ?>&nbsp;&nbsp;</td>
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
