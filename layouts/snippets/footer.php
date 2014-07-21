 <?php
  # 2007-12-30 pk
  include(LAYOUTPATH.'languages/footer_'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
?><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<? echo BG_DEFAULT; ?>" style="height: 18px;background: linear-gradient(<? echo BG_GLEATTRIBUTE; ?> 0%, <? echo BG_DEFAULT ?> 100%);">
  <tr> 
    <td align="center"><?php echo $strPublisherName; ?> 
      <a href="http://www.kvwmap.de/index.php" title="Informationen von der kvwmap-Homepage!" target="_blank">kvwmap </a><?php echo $strVersion; ?><?php echo VERSION; ?><?php echo $strPoweredByUMNMapServer; ?><?php echo $strDate; ?><?php echo date("d.m.Y",time()); ?>. 
      <?php echo $strUser; ?><?php echo $this->user->Namenszusatz.' '.$this->user->Vorname.' '.$this->user->Name; ?>. 
      <?php echo $strTask; ?><?php echo $this->Stelle->Bezeichnung; ?>
    </td>
  </tr>
 </table>